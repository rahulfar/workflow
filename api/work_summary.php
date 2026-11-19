<?php
/**
 * Daily & Weekly Work Summary & Live Team Status API - WorkFlow Pro
 * Provides live team dashboard metrics, daily reports, and weekly analytics.
 */

require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_GET['action'] ?? ($_POST['action'] ?? 'live_team');

switch ($action) {
    case 'live_team':
        getLiveTeamStatusEndpoint($user);
        break;
    case 'daily':
        getDailyReportEndpoint($user);
        break;
    case 'weekly':
        getWeeklyReportEndpoint($user);
        break;
    case 'employee_detail':
        getEmployeeDetailSummaryEndpoint($user);
        break;
    default:
        jsonResponse(false, 'Invalid work summary action', [], 400);
}

function getLiveTeamStatusEndpoint(array $user): void {
    if (!hasRole(['admin', 'manager', 'product_manager'])) {
        jsonResponse(false, 'Access denied. Administrator or Manager privileges required.', [], 403);
    }

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);
        autoCloseOverdueShifts($db);

        $today = date('Y-m-d');
        $shiftSettings = getShiftSettings($db);

        // Fetch all active employees with department details
        $empStmt = $db->query("
            SELECT u.id, u.name, u.email, u.phone, u.avatar, u.designation, u.department_id,
                   d.name as department_name, d.code as department_code,
                   r.name as role_name, r.display_name as role_title
            FROM users u
            JOIN departments d ON u.department_id = d.id
            JOIN roles r ON u.role_id = r.id
            WHERE u.status = 'active'
            ORDER BY u.name ASC
        ");
        $employees = $empStmt->fetchAll();

        $totalCount = count($employees);
        $workingCount = 0;
        $meetingCount = 0;
        $lunchCount = 0;
        $waitingCount = 0;
        $blockedCount = 0;
        $offlineCount = 0;
        $shiftEndedCount = 0;

        $employeeCards = [];

        foreach ($employees as $emp) {
            $eId = (int)$emp['id'];
            $statusDetails = getEmployeeStatusDetails($db, $eId);

            $statusKey = $statusDetails['status_key'];
            switch ($statusKey) {
                case 'Working':
                    $workingCount++;
                    break;
                case 'In Meeting':
                    $meetingCount++;
                    break;
                case 'Lunch':
                    $lunchCount++;
                    break;
                case 'Waiting':
                    $waitingCount++;
                    break;
                case 'Blocked':
                    $blockedCount++;
                    break;
                case 'Shift Ended':
                    $shiftEndedCount++;
                    break;
                default:
                    $offlineCount++;
                    break;
            }

            $currentAct = $statusDetails['current_activity'];
            $actStart = $currentAct ? $currentAct['start_time_formatted'] : null;
            $actDurationSecs = $currentAct ? (int)$currentAct['elapsed_seconds'] : 0;
            $actDurationText = $currentAct ? formatDuration($actDurationSecs) : '--';

            $projectTitle = $currentAct['project_name'] ?? 'General / Internal';
            $taskTitle = $currentAct['task_title'] ?? ($currentAct['activity_type'] ?? 'No active task');

            $avatarUrl = $emp['avatar'] ?: ("https://ui-avatars.com/api/?name=" . urlencode($emp['name']) . "&background=4f46e5&color=fff&size=96");

            // Format card data
            $employeeCards[] = [
                'id' => $eId,
                'name' => $emp['name'],
                'avatar' => $avatarUrl,
                'designation' => $emp['designation'],
                'department_id' => (int)$emp['department_id'],
                'department_name' => $emp['department_name'],
                'status_key' => $statusKey,
                'status_display' => $statusDetails['status_display'],
                'status_emoji' => $statusDetails['status_emoji'],
                'status_color' => $statusDetails['status_color'],
                'status_badge_class' => $statusDetails['status_badge_class'],
                'current_activity' => $currentAct['activity_type'] ?? 'None',
                'current_project' => $projectTitle,
                'current_task' => $taskTitle,
                'task_id' => $currentAct['task_id'] ?? null,
                'activity_start_time' => $actStart ?: '--',
                'activity_start_timestamp' => $currentAct ? strtotime($currentAct['start_time']) : 0,
                'current_duration_seconds' => $actDurationSecs,
                'current_duration_text' => $actDurationText,
                'reason' => $currentAct['reason'] ?? null,
                'notes' => $currentAct['notes'] ?? null,
                'is_shift_active' => $statusDetails['is_shift_active'],
                'shift_status_text' => $statusDetails['is_shift_active'] ? 'Shift Running' : ($statusDetails['is_shift_ended'] ? 'Shift Ended' : 'Not Started'),
                'clock_in_time' => $statusDetails['clock_in_time'] ?: '--',
                'clock_out_time' => $statusDetails['clock_out_time'] ?: '--',
                'late_minutes' => $statusDetails['late_minutes'],
                'is_late' => $statusDetails['is_late'],
                'late_text' => $statusDetails['is_late'] ? "Late by {$statusDetails['late_minutes']}m" : 'On Time',
                'last_activity_update' => $currentAct ? formatISTTime($currentAct['updated_at'] ?? $currentAct['start_time']) : '--'
            ];
        }

        // Departments & Projects for filters
        $departments = $db->query("SELECT id, name FROM departments ORDER BY name ASC")->fetchAll();
        $projects = $db->query("SELECT id, name FROM projects WHERE status = 'active' ORDER BY name ASC")->fetchAll();

        jsonResponse(true, 'Live team status fetched', [
            'counts' => [
                'total' => $totalCount,
                'working' => $workingCount,
                'meeting' => $meetingCount,
                'lunch' => $lunchCount,
                'waiting' => $waitingCount,
                'blocked' => $blockedCount,
                'offline' => $offlineCount,
                'shift_ended' => $shiftEndedCount
            ],
            'employees' => $employeeCards,
            'departments' => $departments,
            'projects' => $projects,
            'shift_settings' => $shiftSettings
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch live team status: ' . $e->getMessage(), [], 500);
    }
}

function getDailyReportEndpoint(array $user): void {
    $targetUserId = (int)($_GET['employee_id'] ?? ($_GET['user_id'] ?? $user['id']));
    $targetDate = sanitize($_GET['date'] ?? date('Y-m-d'));

    if ($targetUserId !== (int)$user['id'] && !hasRole(['admin', 'manager', 'product_manager'])) {
        $targetUserId = (int)$user['id'];
    }

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $report = generateDailyWorkReport($db, $targetUserId, $targetDate);

        // Fetch employee info
        $empStmt = $db->prepare("SELECT id, name, designation, avatar, employee_code FROM users WHERE id = ?");
        $empStmt->execute([$targetUserId]);
        $emp = $empStmt->fetch();

        $settings = getShiftSettings($db);

        jsonResponse(true, 'Daily work report loaded', [
            'employee' => $emp,
            'date' => $targetDate,
            'date_formatted' => formatISTDate($targetDate),
            'shift_timing' => "{$settings['start_time']} – {$settings['end_time']}",
            'lunch_timing' => "{$settings['lunch_start']} – {$settings['lunch_end']}",
            'report' => $report
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to generate daily report: ' . $e->getMessage(), [], 500);
    }
}

function getWeeklyReportEndpoint(array $user): void {
    $targetUserId = (int)($_GET['employee_id'] ?? ($_GET['user_id'] ?? $user['id']));

    if ($targetUserId !== (int)$user['id'] && !hasRole(['admin', 'manager', 'product_manager'])) {
        $targetUserId = (int)$user['id'];
    }

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        // Calculate past 7 days dates
        $days = [];
        $workingHoursSeries = [];
        $meetingHoursSeries = [];
        $blockedHoursSeries = [];
        $dateLabels = [];

        $totalWeekWorkingSecs = 0;
        $totalWeekMeetingSecs = 0;
        $totalWeekBlockedSecs = 0;
        $totalTasksCompleted = 0;
        $lateArrivalCount = 0;
        $attendanceDaysCount = 0;
        $projectsList = [];

        for ($i = 6; $i >= 0; $i--) {
            $dt = new DateTime("-{$i} days", new DateTimeZone('Asia/Kolkata'));
            $dateStr = $dt->format('Y-m-d');
            $dateLabel = $dt->format('D, d M');
            $dateLabels[] = $dateLabel;

            $dayStats = calculateEmployeeDailyDurations($db, $targetUserId, $dateStr);

            $wHours = round($dayStats['working_seconds'] / 3600, 2);
            $mHours = round($dayStats['meeting_seconds'] / 3600, 2);
            $bHours = round($dayStats['blocked_seconds'] / 3600, 2);

            $workingHoursSeries[] = $wHours;
            $meetingHoursSeries[] = $mHours;
            $blockedHoursSeries[] = $bHours;

            $totalWeekWorkingSecs += $dayStats['working_seconds'];
            $totalWeekMeetingSecs += $dayStats['meeting_seconds'];
            $totalWeekBlockedSecs += $dayStats['blocked_seconds'];
            $totalTasksCompleted += $dayStats['tasks_completed'];

            foreach ($dayStats['projects'] as $p) {
                if (!in_array($p, $projectsList)) $projectsList[] = $p;
            }

            // Attendance check
            $attCheck = $db->prepare("SELECT clock_in, late_minutes FROM attendance WHERE user_id = ? AND date = ?");
            $attCheck->execute([$targetUserId, $dateStr]);
            $attRow = $attCheck->fetch();
            if ($attRow) {
                $attendanceDaysCount++;
                if ((int)$attRow['late_minutes'] > 0) {
                    $lateArrivalCount++;
                }
            }
        }

        // Leave count this week
        $startOfWeek = (new DateTime("-6 days", new DateTimeZone('Asia/Kolkata')))->format('Y-m-d');
        $endOfWeek = date('Y-m-d');
        $leaveStmt = $db->prepare("SELECT SUM(total_days) FROM leave_requests WHERE user_id = ? AND status = 'approved' AND start_date <= ? AND end_date >= ?");
        $leaveStmt->execute([$targetUserId, $endOfWeek, $startOfWeek]);
        $leaveDays = (float)($leaveStmt->fetchColumn() ?: 0);

        // Pending tasks
        $pendingTasks = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE assigned_to = {$targetUserId} AND status != 'completed'")->fetchColumn();

        jsonResponse(true, 'Weekly report loaded', [
            'metrics' => [
                'total_working_hours' => round($totalWeekWorkingSecs / 3600, 1),
                'total_working_formatted' => formatDuration($totalWeekWorkingSecs),
                'total_meeting_hours' => round($totalWeekMeetingSecs / 3600, 1),
                'total_meeting_formatted' => formatDuration($totalWeekMeetingSecs),
                'total_blocked_formatted' => formatDuration($totalWeekBlockedSecs),
                'total_tasks_completed' => $totalTasksCompleted,
                'total_tasks_pending' => $pendingTasks,
                'attendance_days' => $attendanceDaysCount,
                'late_arrivals' => $lateArrivalCount,
                'leave_days' => $leaveDays,
                'projects' => $projectsList
            ],
            'chart' => [
                'labels' => $dateLabels,
                'working_hours' => $workingHoursSeries,
                'meeting_hours' => $meetingHoursSeries,
                'blocked_hours' => $blockedHoursSeries
            ]
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch weekly report: ' . $e->getMessage(), [], 500);
    }
}

function getEmployeeDetailSummaryEndpoint(array $user): void {
    if (!hasRole(['admin', 'manager', 'product_manager'])) {
        jsonResponse(false, 'Access denied', [], 403);
    }

    $employeeId = (int)($_GET['id'] ?? 0);
    if (!$employeeId) {
        jsonResponse(false, 'Employee ID required.');
    }

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $today = date('Y-m-d');

        // Fetch employee
        $empStmt = $db->prepare("
            SELECT u.*, d.name as department_name, r.display_name as role_title
            FROM users u
            JOIN departments d ON u.department_id = d.id
            JOIN roles r ON u.role_id = r.id
            WHERE u.id = ?
        ");
        $empStmt->execute([$employeeId]);
        $emp = $empStmt->fetch();
        if (!$emp) {
            jsonResponse(false, 'Employee not found.');
        }

        // Live status & today's durations
        $statusDetails = getEmployeeStatusDetails($db, $employeeId);
        $dailySummary = calculateEmployeeDailyDurations($db, $employeeId, $today);

        // Fetch completed and pending tasks
        $tasksCompleted = $db->prepare("SELECT * FROM tasks WHERE assigned_to = ? AND status = 'completed' ORDER BY updated_at DESC LIMIT 10");
        $tasksCompleted->execute([$employeeId]);
        $completedTasksList = $tasksCompleted->fetchAll();

        $tasksPending = $db->prepare("SELECT * FROM tasks WHERE assigned_to = ? AND status != 'completed' ORDER BY deadline ASC LIMIT 10");
        $tasksPending->execute([$employeeId]);
        $pendingTasksList = $tasksPending->fetchAll();

        // Fetch today's meetings
        $meetingsStmt = $db->prepare("
            SELECT m.*, u.name as caller_name 
            FROM meetings m
            JOIN users u ON m.created_by = u.id
            WHERE (m.employee_id = ? OR m.created_by = ?) AND m.meeting_date = ?
            ORDER BY m.start_time ASC
        ");
        $meetingsStmt->execute([$employeeId, $employeeId, $today]);
        $meetingsList = $meetingsStmt->fetchAll();

        jsonResponse(true, 'Employee detail summary', [
            'employee' => $emp,
            'status' => $statusDetails,
            'durations' => $dailySummary,
            'completed_tasks' => $completedTasksList,
            'pending_tasks' => $pendingTasksList,
            'meetings' => $meetingsList
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error loading employee details: ' . $e->getMessage(), [], 500);
    }
}
