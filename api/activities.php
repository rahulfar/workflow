<?php
/**
 * Employee Activities & Timeline API - WorkFlow Pro
 * Manages live employee activities, task connections, activity switching, reminders, and daily timelines.
 */

require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_GET['action'] ?? ($_POST['action'] ?? 'current');

switch ($action) {
    case 'current':
        getCurrentActivityEndpoint($user);
        break;
    case 'change':
        changeActivityEndpoint($user);
        break;
    case 'timeline':
        getTimelineEndpoint($user);
        break;
    case 'reasons':
        getActivityReasonsEndpoint($user);
        break;
    case 'projects_tasks':
        getProjectsAndTasksEndpoint($user);
        break;
    case 'ack_reminder':
        acknowledgeReminderEndpoint($user);
        break;
    case 'details':
        getActivityDetailsEndpoint($user);
        break;
    case 'update':
    case 'edit':
        updateActivityEndpoint($user);
        break;
    case 'manual_log':
    case 'add':
        manualLogActivityEndpoint($user);
        break;
    case 'delete':
        deleteActivityEndpoint($user);
        break;
    default:
        jsonResponse(false, 'Invalid activity action', [], 400);
}

function getCurrentActivityEndpoint(array $user): void {
    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $targetUserId = (int)($_GET['user_id'] ?? $user['id']);
        // Only Admin or Manager can view another user's current status details
        if ($targetUserId !== (int)$user['id'] && !hasRole(['admin', 'manager', 'product_manager'])) {
            $targetUserId = (int)$user['id'];
        }

        $details = getEmployeeStatusDetails($db, $targetUserId);
        $settings = $details['shift_settings'];

        // Determine if activity reminder is needed (default 30 mins)
        $reminderThresholdMins = (int)($settings['reminder_interval_minutes'] ?? 30);
        $reminderThresholdSecs = $reminderThresholdMins * 60;
        $needsReminder = false;
        $activeSeconds = 0;

        if ($details['is_shift_active'] && $details['current_activity']) {
            $activeSeconds = (int)$details['current_activity']['elapsed_seconds'];
            // If active for >= reminder interval AND user is current user
            if ($activeSeconds >= $reminderThresholdSecs && $targetUserId === (int)$user['id']) {
                $needsReminder = true;
            }
        }

        jsonResponse(true, 'Current activity status', [
            'status' => $details['status_key'],
            'status_display' => $details['status_display'],
            'status_emoji' => $details['status_emoji'],
            'status_color' => $details['status_color'],
            'status_badge_class' => $details['status_badge_class'],
            'is_shift_active' => $details['is_shift_active'],
            'is_shift_ended' => $details['is_shift_ended'],
            'is_lunch_now' => $details['is_lunch_now'],
            'clock_in_time' => $details['clock_in_time'],
            'clock_out_time' => $details['clock_out_time'],
            'late_minutes' => $details['late_minutes'],
            'is_late' => $details['is_late'],
            'current_activity' => $details['current_activity'],
            'active_seconds' => $activeSeconds,
            'active_duration_formatted' => formatDuration($activeSeconds),
            'needs_reminder' => $needsReminder,
            'reminder_threshold_minutes' => $reminderThresholdMins,
            'shift_settings' => [
                'start_time' => $settings['start_time'],
                'end_time' => $settings['end_time'],
                'lunch_start' => $settings['lunch_start'],
                'lunch_end' => $settings['lunch_end'],
                'auto_lunch' => (bool)$settings['auto_lunch']
            ]
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch activity: ' . $e->getMessage(), [], 500);
    }
}

function changeActivityEndpoint(array $user): void {
    $activityType = sanitize($_POST['activity_type'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $taskId = !empty($_POST['task_id']) ? (int)$_POST['task_id'] : null;
    $reason = sanitize($_POST['reason'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    $clientName = sanitize($_POST['client_name'] ?? '');

    if (!empty($clientName)) {
        $notes = trim("[Client: {$clientName}] " . $notes);
    }

    if (empty($activityType)) {
        jsonResponse(false, 'Activity type is required.');
    }

    // Validation: Waiting or Blocked reason check
    $isWaitingOrBlocked = (
        str_contains(strtolower($activityType), 'waiting') ||
        str_contains(strtolower($activityType), 'blocked') ||
        str_contains(strtolower($activityType), 'technical')
    );

    if ($isWaitingOrBlocked && empty($reason)) {
        // Fallback default reason if not selected
        $reason = str_contains(strtolower($activityType), 'waiting') ? 'Waiting for client content' : 'Technical issue';
    }

    try {
        $db = Database::getConnection();
        ensureAttendanceColumns($db);
        ensureShiftAndActivitySchema($db);

        // Auto-start shift if not started yet
        $today = date('Y-m-d');
        $attStmt = $db->prepare("SELECT id FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date = ? AND clock_out IS NULL");
        $attStmt->execute([$user['id'], $user['id'], $today]);
        if (!$attStmt->fetch()) {
            // Check if clocked in earlier
            $nowDateTimeStr = date('Y-m-d H:i:s');
            $settings = getShiftSettings($db);
            $shiftStart = $settings['start_time'] ?? '09:40';
            $shiftEnd = $settings['end_time'] ?? '18:30';
            $lateMinutes = calculateLateMinutes($nowDateTimeStr);
            $initialStatus = ($lateMinutes > 0) ? 'late' : 'present';
            $insAtt = $db->prepare("
                INSERT INTO attendance 
                (user_id, employee_id, date, shift_start, shift_end, clock_in, status, late_minutes, early_exit_minutes, total_seconds, break_seconds) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 0, 0)
            ");
            $insAtt->execute([$user['id'], $user['id'], $today, $shiftStart, $shiftEnd, $nowDateTimeStr, $initialStatus, $lateMinutes]);
            logActivity('Start Shift', 'Attendance', "Auto-started shift upon activity creation at " . formatISTDateTime($nowDateTimeStr));
        }

        // Connect with task if task_id provided
        if ($taskId) {
            $tStmt = $db->prepare("SELECT id, project_id, title FROM tasks WHERE id = ?");
            $tStmt->execute([$taskId]);
            $tRow = $tStmt->fetch();
            if ($tRow) {
                if (!$projectId) {
                    $projectId = (int)$tRow['project_id'] ?: null;
                }
                // Ensure task status is running
                $db->prepare("UPDATE tasks SET status = 'running' WHERE id = ? AND status NOT IN ('completed')")->execute([$taskId]);
            }
        }

        // Start new activity (which automatically closes previous active activity)
        $newActivity = startEmployeeActivity($db, $user['id'], $activityType, $projectId, $taskId, $reason, $notes, $clientName);

        // Notify Boss/Admin if marked blocked
        if (str_contains(strtolower($activityType), 'blocked') || str_contains(strtolower($activityType), 'technical')) {
            $adminIds = $db->query("SELECT id FROM users WHERE role_id = 1 OR LOWER(designation) LIKE '%admin%'")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($adminIds as $adminId) {
                sendNotification((int)$adminId, "🚨 Blocker Reported: {$user['name']}", "{$user['name']} reported a blocker: {$reason}" . (!empty($notes) ? " ({$notes})" : ""), "live-status", "alert");
            }
        }

        jsonResponse(true, 'Activity updated successfully!', $newActivity);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update activity: ' . $e->getMessage(), [], 500);
    }
}

function getTimelineEndpoint(array $user): void {
    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $targetUserId = (int)($_GET['employee_id'] ?? ($_GET['user_id'] ?? $user['id']));
        $targetDate = sanitize($_GET['date'] ?? date('Y-m-d'));

        // Permission check
        if ($targetUserId !== (int)$user['id'] && !hasRole(['admin', 'manager', 'product_manager'])) {
            $targetUserId = (int)$user['id'];
        }

        // Fetch user profile info
        $uStmt = $db->prepare("SELECT u.*, d.name as department_name, r.display_name as role_title FROM users u JOIN departments d ON u.department_id = d.id JOIN roles r ON u.role_id = r.id WHERE u.id = ?");
        $uStmt->execute([$targetUserId]);
        $empInfo = $uStmt->fetch();

        // Fetch today's attendance
        $attStmt = $db->prepare("SELECT * FROM attendance WHERE user_id = ? AND date = ? ORDER BY id DESC LIMIT 1");
        $attStmt->execute([$targetUserId, $targetDate]);
        $attendance = $attStmt->fetch();

        // Fetch all activities for the target date
        $actStmt = $db->prepare("
            SELECT ea.*, p.name as project_name, p.code as project_code, t.title as task_title, t.status as task_status
            FROM employee_activities ea
            LEFT JOIN projects p ON ea.project_id = p.id
            LEFT JOIN tasks t ON ea.task_id = t.id
            WHERE ea.employee_id = ? AND DATE(ea.start_time) = ?
            ORDER BY ea.id ASC
        ");
        $actStmt->execute([$targetUserId, $targetDate]);
        $activities = $actStmt->fetchAll();

        $timelineItems = [];

        // 1. Shift Start Milestone
        if ($attendance && !empty($attendance['clock_in'])) {
            $cInStr = strlen($attendance['clock_in']) <= 8 ? ($targetDate . ' ' . $attendance['clock_in']) : $attendance['clock_in'];
            $lateMins = (int)($attendance['late_minutes'] ?? 0);
            $timelineItems[] = [
                'type' => 'shift_start',
                'title' => 'Shift Started',
                'time_formatted' => formatISTTime($cInStr),
                'timestamp' => strtotime($cInStr),
                'icon' => 'fas fa-door-open',
                'color' => '#10b981',
                'badge' => 'bg-success text-white',
                'notes' => $lateMins > 0 ? "Late Arrival: {$lateMins} minutes" : "Punctual / On Time",
                'is_late' => ($lateMins > 0),
                'late_minutes' => $lateMins
            ];
        }

        // 2. Activity Entries
        $nowTs = time();
        foreach ($activities as $act) {
            $startTs = strtotime($act['start_time']);
            $endTs = !empty($act['end_time']) ? strtotime($act['end_time']) : ($targetDate === date('Y-m-d') ? $nowTs : $startTs);
            $durationSecs = max(0, $endTs - $startTs);

            $meta = getStatusMeta($act['activity_type']);
            if (str_contains(strtolower($act['activity_type']), 'waiting') || str_contains(strtolower($act['reason'] ?? ''), 'waiting')) {
                $meta = getStatusMeta('waiting');
            } elseif (str_contains(strtolower($act['activity_type']), 'blocked') || str_contains(strtolower($act['activity_type']), 'technical') || str_contains(strtolower($act['reason'] ?? ''), 'issue')) {
                $meta = getStatusMeta('blocked');
            }

            $displayTitle = $act['activity_type'];
            if (!empty($act['project_name'])) {
                $displayTitle = $act['project_name'] . ($act['task_title'] ? ' – ' . $act['task_title'] : '');
            }

            $timeRange = formatISTTime($act['start_time']);
            if (!empty($act['end_time'])) {
                $timeRange .= ' – ' . formatISTTime($act['end_time']);
            } elseif ($act['status'] === 'active' && $targetDate === date('Y-m-d')) {
                $timeRange .= ' – Present (Active)';
            }

            $timelineItems[] = [
                'id' => (int)$act['id'],
                'type' => 'activity',
                'activity_type' => $act['activity_type'],
                'title' => $displayTitle,
                'subtitle' => $act['activity_type'],
                'project_name' => $act['project_name'] ?? null,
                'task_title' => $act['task_title'] ?? null,
                'time_range' => $timeRange,
                'start_time' => $act['start_time'],
                'end_time' => $act['end_time'],
                'timestamp' => $startTs,
                'duration_seconds' => $durationSecs,
                'duration_formatted' => formatDuration($durationSecs),
                'status' => $act['status'],
                'reason' => $act['reason'],
                'notes' => $act['notes'],
                'client_name' => $act['client_name'] ?? null,
                'project_id' => $act['project_id'] ?? null,
                'task_id' => $act['task_id'] ?? null,
                'icon' => $meta['icon'],
                'color' => $meta['color'],
                'badge' => $meta['badge_class'],
                'is_active' => ($act['status'] === 'active')
            ];
        }

        // 3. Shift End Milestone
        if ($attendance && !empty($attendance['clock_out'])) {
            $cOutStr = strlen($attendance['clock_out']) <= 8 ? ($targetDate . ' ' . $attendance['clock_out']) : $attendance['clock_out'];
            $earlyExit = (int)($attendance['early_exit_minutes'] ?? 0);
            $timelineItems[] = [
                'type' => 'shift_end',
                'title' => 'Shift Completed',
                'time_formatted' => formatISTTime($cOutStr),
                'timestamp' => strtotime($cOutStr),
                'icon' => 'fas fa-check-circle',
                'color' => '#1e293b',
                'badge' => 'bg-dark text-white',
                'notes' => $earlyExit > 0 ? "Early Exit: {$earlyExit} minutes" : "Full Shift Completed",
                'duration_formatted' => formatDuration((int)$attendance['total_seconds'])
            ];
        }

        // Sort timeline items chronologically by timestamp
        usort($timelineItems, function($a, $b) {
            return ($a['timestamp'] <=> $b['timestamp']);
        });

        // Compute summary durations
        $summary = calculateEmployeeDailyDurations($db, $targetUserId, $targetDate);

        jsonResponse(true, 'Timeline retrieved', [
            'employee' => [
                'id' => $empInfo['id'],
                'name' => $empInfo['name'],
                'designation' => $empInfo['designation'],
                'department' => $empInfo['department_name'],
                'avatar' => $empInfo['avatar']
            ],
            'date' => $targetDate,
            'date_formatted' => formatISTDate($targetDate),
            'attendance' => $attendance,
            'timeline' => $timelineItems,
            'summary' => $summary
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch timeline: ' . $e->getMessage(), [], 500);
    }
}

function getActivityReasonsEndpoint(array $user): void {
    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $reasons = $db->query("SELECT * FROM activity_reasons ORDER BY category ASC, id ASC")->fetchAll();
        jsonResponse(true, 'Reasons loaded', $reasons);
    } catch (Exception $e) {
        jsonResponse(false, 'Error fetching reasons: ' . $e->getMessage(), [], 500);
    }
}

function getProjectsAndTasksEndpoint(array $user): void {
    try {
        $db = Database::getConnection();

        // Projects
        $projects = $db->query("SELECT id, name, code FROM projects WHERE status = 'active' ORDER BY name ASC")->fetchAll();

        // Tasks assigned to user or all if manager
        $isManager = hasRole(['admin', 'manager', 'product_manager']);
        if ($isManager) {
            $tStmt = $db->query("SELECT id, project_id, title, priority, status FROM tasks WHERE status != 'completed' ORDER BY title ASC");
        } else {
            $tStmt = $db->prepare("SELECT id, project_id, title, priority, status FROM tasks WHERE assigned_to = ? AND status != 'completed' ORDER BY title ASC");
            $tStmt->execute([$user['id']]);
        }
        $tasks = $tStmt->fetchAll();

        jsonResponse(true, 'Projects and tasks loaded', [
            'projects' => $projects,
            'tasks' => $tasks
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error loading projects and tasks: ' . $e->getMessage(), [], 500);
    }
}

function acknowledgeReminderEndpoint(array $user): void {
    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        // Update the updated_at timestamp of the active activity so timer resets
        $nowStr = date('Y-m-d H:i:s');
        $db->prepare("UPDATE employee_activities SET updated_at = ? WHERE employee_id = ? AND status = 'active'")
           ->execute([$nowStr, $user['id']]);

        logActivity('Activity Reminder Confirmed', 'Workflow', "Employee {$user['name']} confirmed still working on current activity.");
        jsonResponse(true, 'Reminder acknowledged. Keep up the great work!');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage(), [], 500);
    }
}

function getActivityDetailsEndpoint(array $user): void {
    $actId = (int)($_GET['id'] ?? 0);
    if (!$actId) {
        jsonResponse(false, 'Activity ID required.');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT ea.*, p.name as project_name, p.code as project_code, t.title as task_title, t.description as task_desc, u.name as employee_name
            FROM employee_activities ea
            JOIN users u ON ea.employee_id = u.id
            LEFT JOIN projects p ON ea.project_id = p.id
            LEFT JOIN tasks t ON ea.task_id = t.id
            WHERE ea.id = ?
        ");
        $stmt->execute([$actId]);
        $row = $stmt->fetch();

        if (!$row) {
            jsonResponse(false, 'Activity not found.');
        }

        $nowTs = time();
        $startTs = strtotime($row['start_time']);
        $endTs = !empty($row['end_time']) ? strtotime($row['end_time']) : $nowTs;
        $row['duration_seconds'] = max(0, $endTs - $startTs);
        $row['duration_formatted'] = formatDuration($row['duration_seconds']);
        $row['start_time_formatted'] = formatISTDateTime($row['start_time']);
        $row['end_time_formatted'] = !empty($row['end_time']) ? formatISTDateTime($row['end_time']) : 'In Progress';
        $row['start_time_time'] = date('H:i', $startTs);
        $row['end_time_time'] = !empty($row['end_time']) ? date('H:i', $endTs) : date('H:i', $nowTs);
        $row['date'] = date('Y-m-d', $startTs);

        // Extract client name if in notes
        if (empty($row['client_name']) && !empty($row['notes'])) {
            if (preg_match('/\[Client:\s*([^\]]+)\]/', $row['notes'], $cm)) {
                $row['client_name'] = trim($cm[1]);
            }
        }

        jsonResponse(true, 'Activity details', $row);
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage(), [], 500);
    }
}

function updateActivityEndpoint(array $user): void {
    $actId = (int)($_POST['id'] ?? ($_POST['activity_id'] ?? 0));
    $activityType = sanitize($_POST['activity_type'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $taskId = !empty($_POST['task_id']) ? (int)$_POST['task_id'] : null;
    $clientName = sanitize($_POST['client_name'] ?? '');
    $startTimeStr = sanitize($_POST['start_time'] ?? ''); // e.g. 10:30 or 2026-09-26 10:30:00
    $endTimeStr = sanitize($_POST['end_time'] ?? '');     // e.g. 11:45 or 2026-09-26 11:45:00
    $reason = sanitize($_POST['reason'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');

    if (!$actId) {
        jsonResponse(false, 'Activity ID required.');
    }
    if (empty($activityType)) {
        jsonResponse(false, 'Activity type required.');
    }

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $stmt = $db->prepare("SELECT * FROM employee_activities WHERE id = ?");
        $stmt->execute([$actId]);
        $activity = $stmt->fetch();

        if (!$activity) {
            jsonResponse(false, 'Activity not found.');
        }

        // Permission check: must be owner or admin/manager
        if ((int)$activity['employee_id'] !== (int)$user['id'] && !hasRole(['admin', 'manager', 'product_manager'])) {
            jsonResponse(false, 'Permission denied to edit this activity.', [], 403);
        }

        $actDate = date('Y-m-d', strtotime($activity['start_time']));

        // Parse start_time and end_time
        if (strlen($startTimeStr) <= 8 && !empty($startTimeStr)) {
            $fullStartTime = $actDate . ' ' . $startTimeStr;
        } else {
            $fullStartTime = $startTimeStr ?: $activity['start_time'];
        }

        $fullEndTime = null;
        if (!empty($endTimeStr)) {
            if (strlen($endTimeStr) <= 8) {
                $fullEndTime = $actDate . ' ' . $endTimeStr;
            } else {
                $fullEndTime = $endTimeStr;
            }
        } elseif ($activity['status'] === 'completed' && !empty($activity['end_time'])) {
            $fullEndTime = $activity['end_time'];
        }

        $startTs = strtotime($fullStartTime);
        $duration = 0;
        if (!empty($fullEndTime)) {
            $endTs = strtotime($fullEndTime);
            if ($endTs < $startTs) {
                jsonResponse(false, 'End time cannot be earlier than start time.');
            }
            $duration = max(0, $endTs - $startTs);
        } else {
            $duration = max(0, time() - $startTs);
        }

        if (!empty($clientName) && !str_contains($notes, "[Client: {$clientName}]")) {
            $notes = trim("[Client: {$clientName}] " . $notes);
        }

        $nowStr = date('Y-m-d H:i:s');
        $upd = $db->prepare("
            UPDATE employee_activities 
            SET activity_type = ?, project_id = ?, task_id = ?, client_name = ?,
                start_time = ?, end_time = ?, duration = ?, reason = ?, notes = ?, updated_at = ?
            WHERE id = ?
        ");
        $upd->execute([
            $activityType, $projectId, $taskId, $clientName,
            $fullStartTime, $fullEndTime, $duration, $reason, $notes, $nowStr, $actId
        ]);

        logActivity('Activity Updated', 'Workflow', "Employee {$user['name']} updated activity #{$actId} '{$activityType}' ({$duration}s).");

        $dailySummary = calculateEmployeeDailyDurations($db, (int)$activity['employee_id'], $actDate);

        jsonResponse(true, 'Workday activity updated successfully!', [
            'activity_id' => $actId,
            'duration' => $duration,
            'duration_formatted' => formatDuration($duration),
            'summary' => $dailySummary
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update activity: ' . $e->getMessage(), [], 500);
    }
}

function manualLogActivityEndpoint(array $user): void {
    $activityType = sanitize($_POST['activity_type'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $taskId = !empty($_POST['task_id']) ? (int)$_POST['task_id'] : null;
    $clientName = sanitize($_POST['client_name'] ?? '');
    $date = sanitize($_POST['date'] ?? date('Y-m-d'));
    $startTimeStr = sanitize($_POST['start_time'] ?? ''); // e.g. 10:00
    $endTimeStr = sanitize($_POST['end_time'] ?? '');     // e.g. 11:30
    $reason = sanitize($_POST['reason'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');

    if (empty($activityType) || empty($startTimeStr) || empty($endTimeStr)) {
        jsonResponse(false, 'Activity type, start time, and end time are required.');
    }

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $fullStartTime = $date . ' ' . (strlen($startTimeStr) <= 5 ? ($startTimeStr . ':00') : $startTimeStr);
        $fullEndTime = $date . ' ' . (strlen($endTimeStr) <= 5 ? ($endTimeStr . ':00') : $endTimeStr);

        $startTs = strtotime($fullStartTime);
        $endTs = strtotime($fullEndTime);

        if ($endTs <= $startTs) {
            jsonResponse(false, 'End time must be after start time.');
        }

        $duration = max(0, $endTs - $startTs);

        if (!empty($clientName) && !str_contains($notes, "[Client: {$clientName}]")) {
            $notes = trim("[Client: {$clientName}] " . $notes);
        }

        $nowStr = date('Y-m-d H:i:s');
        $stmt = $db->prepare("
            INSERT INTO employee_activities (employee_id, project_id, task_id, activity_type, status, start_time, end_time, duration, reason, notes, client_name, created_at, updated_at)
            VALUES (?, ?, ?, ?, 'completed', ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $user['id'], $projectId, $taskId, $activityType,
            $fullStartTime, $fullEndTime, $duration, $reason, $notes, $clientName, $nowStr, $nowStr
        ]);

        $newId = (int)$db->lastInsertId();
        logActivity('Manual Activity Logged', 'Workflow', "Employee {$user['name']} logged activity '{$activityType}' ({$startTimeStr} - {$endTimeStr}, {$duration}s).");

        $dailySummary = calculateEmployeeDailyDurations($db, (int)$user['id'], $date);

        jsonResponse(true, 'Activity entry logged successfully into your workday!', [
            'id' => $newId,
            'duration' => $duration,
            'duration_formatted' => formatDuration($duration),
            'summary' => $dailySummary
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to log activity: ' . $e->getMessage(), [], 500);
    }
}

function deleteActivityEndpoint(array $user): void {
    $actId = (int)($_POST['id'] ?? ($_POST['activity_id'] ?? 0));
    if (!$actId) {
        jsonResponse(false, 'Activity ID required.');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM employee_activities WHERE id = ?");
        $stmt->execute([$actId]);
        $activity = $stmt->fetch();

        if (!$activity) {
            jsonResponse(false, 'Activity not found.');
        }

        if ((int)$activity['employee_id'] !== (int)$user['id'] && !hasRole('admin')) {
            jsonResponse(false, 'Permission denied.', [], 403);
        }

        $db->prepare("DELETE FROM employee_activities WHERE id = ?")->execute([$actId]);
        logActivity('Activity Deleted', 'Workflow', "Employee {$user['name']} deleted activity #{$actId}.");

        jsonResponse(true, 'Activity entry deleted.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error deleting activity: ' . $e->getMessage(), [], 500);
    }
}
