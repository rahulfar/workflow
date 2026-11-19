<?php
$pageTitle = "Dashboard";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();
ensureEmployeeSchema($db);
ensurePayrollSchema($db);
ensureShiftAndActivitySchema($db);

$actualRole = strtolower($user['role_name'] ?? '');
$userId = (int)$user['id'];
$userName = strtolower(trim($user['name'] ?? ''));
$userDeptId = (int)($user['department_id'] ?? 1);
$canManageTasks = canCreateOrEditTasks();
$isSeoLead = ($userId === 5 || $userName === 'uvaish' || $actualRole === 'seo_manager');
$isDevLead = ($userId === 4 || $userName === 'rahul' || $actualRole === 'senior_developer');
$isAdmin = hasRole('admin');
$todayDate = date('Y-m-d');
$curMonth = date('Y-m');

// Support role view preview override (Admins can preview any role; any authenticated user can view employee workspace)
$userCategory = getUserRoleCategory($user);
$requestedView = $_GET['view_as'] ?? '';
if ($requestedView === 'employee' || $userCategory === 'employee') {
    $activeRoleView = 'employee';
} elseif ($isAdmin && in_array($requestedView, ['admin', 'manager', 'product_manager'])) {
    $activeRoleView = $requestedView;
} else {
    $activeRoleView = $userCategory;
}
$isGlobalManager = in_array($userCategory, ['admin', 'manager', 'product_manager']) || in_array($activeRoleView, ['admin', 'manager', 'product_manager']);

// ---------------------------------------------------------
// 1. DATA PREPARATION FOR ALL DASHBOARD VIEWS
// ---------------------------------------------------------
autoCloseOverdueShifts($db, $isAdmin ? null : $userId);
ensureTaskApprovalSchema($db);

// Pending task completion approvals for Manager, Product Manager, and Admin
$pendingApprovals = [];
try {
    $pendingApprovalsStmt = $db->query("
        SELECT t.*, u_to.name as assignee_name, u_to.avatar as assignee_avatar, 
               u_to.designation as assignee_designation,
               p.name as project_name, d.name as department_name
        FROM tasks t
        JOIN users u_to ON t.assigned_to = u_to.id
        LEFT JOIN projects p ON t.project_id = p.id
        LEFT JOIN departments d ON t.department_id = d.id
        WHERE t.status IN ('pending_approval', 'in_review')
        ORDER BY t.submitted_for_approval_at DESC, t.id DESC
    ");
    $pendingApprovals = $pendingApprovalsStmt ? ($pendingApprovalsStmt->fetchAll() ?: []) : [];
} catch (Exception $e) {
    error_log("Dashboard pendingApprovals error: " . $e->getMessage());
    $pendingApprovals = [];
}
$pendingApprovalsCount = count($pendingApprovals);

ensureEmergencyAndFlagSchema($db);

// Pending emergency requests for management
$pendingEmgRequests = [];
try {
    $pendingEmgRequestsStmt = $db->query("
        SELECT r.*, t.title as task_title, p.name as project_name, 
               u.name as employee_name, u.avatar as employee_avatar,
               u.designation as employee_designation, d.name as department_name
        FROM emergency_task_requests r
        JOIN tasks t ON r.task_id = t.id
        JOIN users u ON r.employee_id = u.id
        LEFT JOIN projects p ON t.project_id = p.id
        LEFT JOIN departments d ON t.department_id = d.id
        WHERE r.status = 'pending'
        ORDER BY r.id DESC
    ");
    $pendingEmgRequests = $pendingEmgRequestsStmt ? ($pendingEmgRequestsStmt->fetchAll() ?: []) : [];
} catch (Exception $e) {
    error_log("Dashboard pendingEmgRequests error: " . $e->getMessage());
    $pendingEmgRequests = [];
}

// Open task flags for management
$openTaskFlags = [];
try {
    $openTaskFlagsStmt = $db->query("
        SELECT f.*, t.title as task_title, p.name as project_name,
               u.name as employee_name, u.avatar as employee_avatar,
               u.designation as employee_designation, d.name as department_name
        FROM task_flags f
        JOIN tasks t ON f.task_id = t.id
        JOIN users u ON f.employee_id = u.id
        LEFT JOIN projects p ON t.project_id = p.id
        LEFT JOIN departments d ON t.department_id = d.id
        WHERE f.status != 'resolved'
        ORDER BY f.id DESC
    ");
    $openTaskFlags = $openTaskFlagsStmt ? ($openTaskFlagsStmt->fetchAll() ?: []) : [];
} catch (Exception $e) {
    error_log("Dashboard openTaskFlags error: " . $e->getMessage());
    $openTaskFlags = [];
}

// Employee's own submitted emergency requests
$myEmergencyRequests = [];
try {
    $myEmergencyRequestsStmt = $db->prepare("
        SELECT r.*, t.title as task_title, u_rev.name as reviewer_name
        FROM emergency_task_requests r
        JOIN tasks t ON r.task_id = t.id
        LEFT JOIN users u_rev ON r.reviewer_id = u_rev.id
        WHERE r.employee_id = ?
        ORDER BY r.id DESC LIMIT 5
    ");
    $myEmergencyRequestsStmt->execute([$userId]);
    $myEmergencyRequests = $myEmergencyRequestsStmt->fetchAll() ?: [];
} catch (Exception $e) {
    error_log("Dashboard myEmergencyRequests error: " . $e->getMessage());
    $myEmergencyRequests = [];
}

// Employee's own submitted task flags
$myTaskFlags = [];
try {
    $myTaskFlagsStmt = $db->prepare("
        SELECT f.*, t.title as task_title, u_res.name as resolver_name
        FROM task_flags f
        JOIN tasks t ON f.task_id = t.id
        LEFT JOIN users u_res ON f.resolved_by = u_res.id
        WHERE f.employee_id = ?
        ORDER BY f.id DESC LIMIT 5
    ");
    $myTaskFlagsStmt->execute([$userId]);
    $myTaskFlags = $myTaskFlagsStmt->fetchAll() ?: [];
} catch (Exception $e) {
    error_log("Dashboard myTaskFlags error: " . $e->getMessage());
    $myTaskFlags = [];
}

$shiftSettings = getShiftSettings($db);
$statusDetails = getEmployeeStatusDetails($db, $userId);
$currentAct = $statusDetails['current_activity'];
$isShiftActive = $statusDetails['is_shift_active'];
$isShiftEnded = $statusDetails['is_shift_ended'];

// Shift timing targets
$shiftStartTarget = $shiftSettings['start_time'] ?? '09:40';
$shiftEndTarget = $shiftSettings['end_time'] ?? '18:30';
$lunchStartTarget = $shiftSettings['lunch_start'] ?? '14:00';
$lunchEndTarget = $shiftSettings['lunch_end'] ?? '15:00';

$fmtShiftStart = date('h:i A', strtotime("2026-01-01 {$shiftStartTarget}:00"));
$fmtShiftEnd = date('h:i A', strtotime("2026-01-01 {$shiftEndTarget}:00"));
$fmtLunchStart = date('h:i A', strtotime("2026-01-01 {$lunchStartTarget}:00"));
$fmtLunchEnd = date('h:i A', strtotime("2026-01-01 {$lunchEndTarget}:00"));

$clockInFormatted = $statusDetails['clock_in_time'] ?: '--';
$clockOutFormatted = $statusDetails['clock_out_time'] ?: '--';
$lateMinutes = (int)($statusDetails['late_minutes'] ?? 0);
$isLate = ($lateMinutes > 0);

// Today's Meetings and pending invitations for current employee/user
$dashMeetingsStmt = $db->prepare("
    SELECT m.*, 
           c.name as caller_name, c.designation as caller_designation, c.avatar as caller_avatar,
           e.name as employee_name, e.designation as employee_designation, e.avatar as employee_avatar,
           p.name as project_name
    FROM meetings m
    LEFT JOIN users c ON m.created_by = c.id
    LEFT JOIN users e ON m.employee_id = e.id
    LEFT JOIN projects p ON m.project_id = p.id
    WHERE (m.employee_id = ? OR m.created_by = ? OR m.id IN (SELECT meeting_id FROM meeting_participants WHERE employee_id = ?)) 
      AND (m.meeting_date >= ? OR m.status = 'pending')
    ORDER BY CASE WHEN m.status = 'pending' THEN 0 WHEN m.status IN ('in_progress', 'accepted') THEN 1 ELSE 2 END, m.start_time ASC
");
$dashMeetingsStmt->execute([$userId, $userId, $userId, $todayDate]);
$dashMeetings = $dashMeetingsStmt->fetchAll();

$dashPendingMeetingAlert = null;
foreach ($dashMeetings as $pm) {
    if (((int)$pm['employee_id'] === $userId || (int)$pm['created_by'] !== $userId) && $pm['status'] === 'pending') {
        $dashPendingMeetingAlert = $pm;
        break;
    }
}

// Active projects, tasks, reasons, and team members for employee dashboard controls
$dashProjects = $db->query("SELECT id, name, code, department_id FROM projects WHERE status = 'active' ORDER BY name ASC")->fetchAll() ?: [];
$dashDepartments = $db->query("SELECT id, name, code FROM departments ORDER BY name ASC")->fetchAll() ?: [];
$dashTasksStmt = $db->prepare("SELECT id, project_id, title, priority, status FROM tasks WHERE assigned_to = ? AND status != 'completed' ORDER BY title ASC");
$dashTasksStmt->execute([$userId]);
$dashAssignedTasks = $dashTasksStmt->fetchAll() ?: [];
$dashTeamMembers = $db->query("SELECT id, name, designation, role, department_id FROM users WHERE status = 'active' ORDER BY name ASC")->fetchAll() ?: [];
$dashReasons = $db->query("SELECT * FROM activity_reasons ORDER BY category ASC, id ASC")->fetchAll() ?: [];

// SEO Department Team and Tasks for SEO Leads (Uvaish, etc.)
$seoTeamMembers = getSeoDepartmentTeam($db);
$seoDeptTasks = [];
try {
    $seoTasksStmt = $db->query("
        SELECT t.*, u_to.name as assignee_name, u_to.avatar as assignee_avatar, 
               u_to.designation as assignee_designation,
               p.name as project_name
        FROM tasks t
        JOIN users u_to ON (t.assigned_to = u_to.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u_to.id))
        LEFT JOIN projects p ON t.project_id = p.id
        WHERE (t.assigned_to IN (5, 6, 10) OR t.assigned_to_employee_id IN (5, 6, 10))
        ORDER BY (CASE WHEN t.status IN ('running', 'in_progress') THEN 0 WHEN t.status IN ('pending', 'pending_approval') THEN 1 ELSE 2 END) ASC, t.id DESC
        LIMIT 10
    ");
    $seoDeptTasks = $seoTasksStmt ? ($seoTasksStmt->fetchAll() ?: []) : [];
} catch (Throwable $e) {
    $seoDeptTasks = [];
}

// Development Department Team and Tasks for Dev Leads (Rahul, etc.)
$devTeamMembers = getDevDepartmentTeam($db);
$devDeptTasks = [];
try {
    $devTasksStmt = $db->query("
        SELECT t.*, u_to.name as assignee_name, u_to.avatar as assignee_avatar, 
               u_to.designation as assignee_designation,
               p.name as project_name
        FROM tasks t
        JOIN users u_to ON (t.assigned_to = u_to.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u_to.id))
        LEFT JOIN projects p ON t.project_id = p.id
        WHERE (t.assigned_to IN (4, 9) OR t.assigned_to_employee_id IN (4, 9))
        ORDER BY (CASE WHEN t.status IN ('running', 'in_progress') THEN 0 WHEN t.status IN ('pending', 'pending_approval') THEN 1 ELSE 2 END) ASC, t.id DESC
        LIMIT 10
    ");
    $devDeptTasks = $devTasksStmt ? ($devTasksStmt->fetchAll() ?: []) : [];
} catch (Throwable $e) {
    $devDeptTasks = [];
}

// Today's Shift & Attendance for Current User
$myAttStmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date = ? ORDER BY id DESC LIMIT 1");
$myAttStmt->execute([$userId, $userId, $todayDate]);
$myAttendance = $myAttStmt->fetch();

if (!$myAttendance) {
    $myAttStmtLatest = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) ORDER BY id DESC LIMIT 1");
    $myAttStmtLatest->execute([$userId, $userId]);
    $myAttendance = $myAttStmtLatest->fetch();
}

$isShiftRunning = ($myAttendance && empty($myAttendance['clock_out']) && ($myAttendance['date'] === $todayDate));
$shiftClockInStr = ($myAttendance && $myAttendance['clock_in']) ? (strlen($myAttendance['clock_in']) <= 8 ? ($myAttendance['date'] . ' ' . $myAttendance['clock_in']) : $myAttendance['clock_in']) : null;
$shiftClockOutStr = ($myAttendance && $myAttendance['clock_out']) ? (strlen($myAttendance['clock_out']) <= 8 ? ($myAttendance['date'] . ' ' . $myAttendance['clock_out']) : $myAttendance['clock_out']) : null;

$shiftStartTimestamp = $shiftClockInStr ? strtotime($shiftClockInStr) : 0;
$shiftElapsedSecs = 0;
if ($isShiftRunning) {
    $shiftElapsedSecs = max(0, time() - $shiftStartTimestamp);
} elseif ($myAttendance && $myAttendance['date'] === $todayDate) {
    $shiftElapsedSecs = (int)$myAttendance['total_seconds'];
}
$remainingShiftSecs = max(0, 31800 - $shiftElapsedSecs);

$myLateMins = $myAttendance ? (int)($myAttendance['late_minutes'] ?? 0) : 0;
$myEarlyExitMins = $myAttendance ? (int)($myAttendance['early_exit_minutes'] ?? 0) : 0;
$myStatus = $myAttendance ? $myAttendance['status'] : 'absent';
$isClientIpAuth = isClientIpAuthorized($db);
$clientNetworkName = getIpNetworkName($db);

// Weekly & Monthly Attendance Seconds for Current User
$weekStart = date('Y-m-d', strtotime('monday this week'));
$weekEnd = date('Y-m-d', strtotime('sunday this week'));
$monthStart = date('Y-m-01');
$monthEnd = date('Y-m-t');

$myWeekHoursStmt = $db->prepare("SELECT SUM(total_seconds) FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date >= ? AND date <= ?");
$myWeekHoursStmt->execute([$userId, $userId, $weekStart, $weekEnd]);
$myWeekTotalSecs = (int)($myWeekHoursStmt->fetchColumn() ?? 0) + ($isShiftRunning ? $shiftElapsedSecs : 0);

$myMonthPresentStmt = $db->prepare("SELECT COUNT(DISTINCT date) FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date >= ? AND date <= ? AND status IN ('present', 'late', 'half_day')");
$myMonthPresentStmt->execute([$userId, $userId, $monthStart, $monthEnd]);
$myMonthPresentDays = (int)($myMonthPresentStmt->fetchColumn() ?? 0);

// ---------------------------------------------------------
// 2. ADMIN DASHBOARD METRICS (11 Statistics Cards + Analytics)
// ---------------------------------------------------------
$nowStr = date('Y-m-d H:i:s');
$totalEmpCount = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$activeEmpCount = (int)$db->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn();
$managersCount = (int)$db->query("SELECT COUNT(*) FROM users u JOIN roles r ON u.role_id = r.id WHERE LOWER(r.name) IN ('manager', 'product_manager')")->fetchColumn();
$deptCount = (int)$db->query("SELECT COUNT(*) FROM departments")->fetchColumn();
$totalTasksCount = (int)$db->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
$pendingTasksCount = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE status = 'pending'")->fetchColumn();
$completedTasksCount = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE status = 'completed'")->fetchColumn();

$overdueTasksStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE deadline < ? AND status != 'completed'");
$overdueTasksStmt->execute([$nowStr]);
$overdueTasksCount = (int)$overdueTasksStmt->fetchColumn();

$adminPresentStmt = $db->prepare("SELECT COUNT(DISTINCT user_id) FROM attendance WHERE date = ? AND clock_in IS NOT NULL");
$adminPresentStmt->execute([$todayDate]);
$adminPresentCount = (int)$adminPresentStmt->fetchColumn();
$adminPayrollTotal = 0.0;
$adminPaidSalaries = 0.0;
$adminPendingSalaries = 0.0;

try {
    $pSummary = $db->query("
        SELECT 
            SUM(net_salary) as total_net,
            SUM(CASE WHEN payment_status = 'paid' THEN net_salary ELSE 0 END) as paid_net,
            SUM(CASE WHEN payment_status = 'pending' THEN net_salary ELSE 0 END) as pending_net
        FROM payrolls 
        WHERE month_year = '{$curMonth}'
    ")->fetch();
    $adminPayrollTotal = (float)($pSummary['total_net'] ?? 0);
    $adminPaidSalaries = (float)($pSummary['paid_net'] ?? 0);
    $adminPendingSalaries = (float)($pSummary['pending_net'] ?? 0);
} catch (Exception $e) {}

$avgProjectProgress = (int)round((float)$db->query("SELECT AVG(progress) FROM projects WHERE status = 'active'")->fetchColumn() ?: 68);

// Leaderboard: Top Employees by Tasks Completed & Time Spent
$leaderboard = $db->query("
    SELECT u.id, u.name, u.designation, u.avatar, d.name as dept_name,
           COUNT(t.id) as completed_tasks,
           COALESCE(SUM(t.spent_seconds), 0) as total_seconds
    FROM users u
    JOIN departments d ON u.department_id = d.id
    LEFT JOIN tasks t ON u.id = t.assigned_to AND t.status = 'completed'
    WHERE u.status = 'active'
    GROUP BY u.id
    ORDER BY completed_tasks DESC, total_seconds DESC
    LIMIT 5
")->fetchAll();

// Activity Stream Logs
$activities = $db->query("
    SELECT a.*, u.name as user_name 
    FROM activity_logs a 
    LEFT JOIN users u ON a.user_id = u.id 
    ORDER BY a.id DESC 
    LIMIT 6
")->fetchAll();

// Upcoming Deadlines Calendar
$upcomingDeadlines = $db->query("
    SELECT t.id, t.title, t.deadline, t.priority, t.status, u.name as assigned_to_name
    FROM tasks t
    JOIN users u ON t.assigned_to = u.id
    WHERE t.status != 'completed'
    ORDER BY t.deadline ASC
    LIMIT 5
")->fetchAll();

// All Employee Profiles & Payroll Roster
$adminEmployeeProfiles = $db->query("
    SELECT u.*, r.name as role_name, r.display_name as role_title, d.name as department_name, ep.joining_date, ep.address, ep.emergency_contact
    FROM users u
    JOIN roles r ON u.role_id = r.id
    JOIN departments d ON u.department_id = d.id
    LEFT JOIN employee_profiles ep ON u.id = ep.user_id
    ORDER BY u.id ASC
")->fetchAll();

$defaultSalaries = [
    'Rahul' => 30000.00, 'Anjali' => 25000.00, 'Mrinali' => 25000.00, 'Uvaish' => 25000.00, 'Priyansh' => 15000.00,
    'Aman' => 7000.00, 'Uday' => 20000.00, 'Shivanshu' => 10000.00, 'Sarthak' => 10000.00,
    'Abhay' => 5000.00, 'Kartikey' => 50000.00
];

$adminSalariesRoster = $db->query("
    SELECT u.id as user_id, u.name, u.employee_code, u.designation, u.avatar,
           COALESCE(d.name, 'General') as department_name,
           COALESCE(es.monthly_salary, 0.00) as monthly_salary,
           COALESCE(es.allowance, 0.00) as allowance,
           p.net_salary, p.payment_status
    FROM users u
    LEFT JOIN departments d ON u.department_id = d.id
    LEFT JOIN employee_salaries es ON u.id = es.user_id
    LEFT JOIN payrolls p ON u.id = p.user_id AND p.month_year = '{$curMonth}'
    ORDER BY u.id ASC
")->fetchAll();

foreach ($adminSalariesRoster as &$sRow) {
    if ((float)$sRow['monthly_salary'] <= 0) {
        $sRow['monthly_salary'] = $defaultSalaries[$sRow['name']] ?? 20000.00;
    }
}
unset($sRow);

// ---------------------------------------------------------
// 3. MANAGER DASHBOARD METRICS (7 Statistics Cards + Team Hub)
// ---------------------------------------------------------
$mgrTeamStmt = $db->prepare("SELECT COUNT(*) FROM users WHERE department_id = ? AND status = 'active'");
$mgrTeamStmt->execute([$userDeptId]);
$teamMembersCount = (int)$mgrTeamStmt->fetchColumn();

$mgrAssignedTasksStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE department_id = ?");
$mgrAssignedTasksStmt->execute([$userDeptId]);
$mgrAssignedTasksCount = (int)$mgrAssignedTasksStmt->fetchColumn();

$mgrCompletedTasksStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE department_id = ? AND status = 'completed'");
$mgrCompletedTasksStmt->execute([$userDeptId]);
$mgrCompletedTasksCount = (int)$mgrCompletedTasksStmt->fetchColumn();

$mgrPendingTasksStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE department_id = ? AND status = 'pending'");
$mgrPendingTasksStmt->execute([$userDeptId]);
$mgrPendingTasksCount = (int)$mgrPendingTasksStmt->fetchColumn();

$mgrOverdueTasksStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE department_id = ? AND deadline < ? AND status != 'completed'");
$mgrOverdueTasksStmt->execute([$userDeptId, $nowStr]);
$mgrOverdueTasksCount = (int)$mgrOverdueTasksStmt->fetchColumn();

$mgrTeamAttendanceStmt = $db->prepare("
    SELECT COUNT(DISTINCT a.user_id) 
    FROM attendance a 
    JOIN users u ON a.user_id = u.id 
    WHERE u.department_id = ? AND a.date = ? AND a.clock_in IS NOT NULL
");
$mgrTeamAttendanceStmt->execute([$userDeptId, $todayDate]);
$mgrTeamPresentCount = (int)$mgrTeamAttendanceStmt->fetchColumn();

$mgrTeamPerfScore = $mgrAssignedTasksCount > 0 ? (int)round(($mgrCompletedTasksCount / $mgrAssignedTasksCount) * 100) : 85;

// Team Workload Overview
$mgrWorkloadStmt = $db->prepare("
    SELECT u.id, u.name, u.designation, u.avatar,
           COUNT(t.id) as total_tasks,
           SUM(CASE WHEN t.status IN ('pending', 'running') THEN 1 ELSE 0 END) as active_tasks,
           SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks
    FROM users u
    LEFT JOIN tasks t ON u.id = t.assigned_to
    WHERE u.department_id = ? AND u.status = 'active'
    GROUP BY u.id
    ORDER BY active_tasks DESC
");
$mgrWorkloadStmt->execute([$userDeptId]);
$mgrWorkloadList = $mgrWorkloadStmt->fetchAll();

// Manager Kanban Tasks Preview (grouped by status)
$mgrKanbanTasksStmt = $db->prepare("
    SELECT t.*, u.name as assigned_to_name, u.avatar as assigned_to_avatar 
    FROM tasks t 
    JOIN users u ON t.assigned_to = u.id 
    WHERE t.department_id = ?
    ORDER BY t.id DESC LIMIT 12
");
$mgrKanbanTasksStmt->execute([$userDeptId]);
$mgrKanbanTasks = $mgrKanbanTasksStmt->fetchAll();

$kanbanCols = [
    'pending' => [],
    'running' => [],
    'in_review' => [],
    'completed' => []
];
foreach ($mgrKanbanTasks as $kt) {
    $st = strtolower($kt['status']);
    if (isset($kanbanCols[$st])) {
        $kanbanCols[$st][] = $kt;
    } else {
}
}

// Manager Priority Distribution Counts
$mgrPriorityCounts = ['urgent' => 0, 'high' => 0, 'medium' => 0, 'low' => 0];
$mgrPrioStmt = $db->prepare("SELECT priority, COUNT(*) as cnt FROM tasks WHERE department_id = ? GROUP BY priority");
$mgrPrioStmt->execute([$userDeptId]);
while ($pRow = $mgrPrioStmt->fetch()) {
    $pKey = strtolower($pRow['priority']);
    if (array_key_exists($pKey, $mgrPriorityCounts)) {
        $mgrPriorityCounts[$pKey] = (int)$pRow['cnt'];
    }
}
// 3. PRODUCT MANAGER DASHBOARD METRICS (Projects & Sprints)
// ---------------------------------------------------------
$pmTotalProjects = (int)$db->query("SELECT COUNT(*) FROM projects")->fetchColumn();
$pmActiveProjects = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'active'")->fetchColumn();
$pmCompletedProjects = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'completed'")->fetchColumn();

$pmTotalTasks = (int)$db->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
$pmPendingTasks = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE status = 'pending'")->fetchColumn();
$pmRunningTasks = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE status = 'running'")->fetchColumn();
$pmInReviewTasks = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE status = 'in_review'")->fetchColumn();
$pmCompletedTasks = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE status = 'completed'")->fetchColumn();

$pmProjectsList = $db->query("
    SELECT p.*, d.name as department_name, u.name as creator_name,
           (SELECT COUNT(*) FROM tasks t WHERE t.project_id = p.id) as task_count,
           (SELECT COUNT(*) FROM tasks t WHERE t.project_id = p.id AND t.status = 'completed') as completed_task_count
    FROM projects p
    LEFT JOIN departments d ON p.department_id = d.id
    LEFT JOIN users u ON p.created_by = u.id
    ORDER BY p.id DESC
")->fetchAll();

$pmRecentFiles = $db->query("
    SELECT tf.*, u.name as uploader_name, p.name as project_name
    FROM task_files tf
    JOIN users u ON tf.user_id = u.id
    LEFT JOIN projects p ON tf.project_id = p.id
    ORDER BY tf.id DESC
    LIMIT 5
")->fetchAll();

$pmSprintTasks = $db->query("
    SELECT t.*, u.name as assigned_to_name, p.name as project_name
    FROM tasks t
    LEFT JOIN users u ON t.assigned_to = u.id
    LEFT JOIN projects p ON t.project_id = p.id
    WHERE t.status != 'completed'
    ORDER BY t.priority = 'urgent' DESC, t.deadline ASC
    LIMIT 6
")->fetchAll();

// 4. EMPLOYEE DASHBOARD METRICS (8 Statistics Cards + Productivity)
// ---------------------------------------------------------
$empVisTasks = getTaskVisibilityCondition($user, 'tasks');

$empMyTasksStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE {$empVisTasks['sql']}");
$empMyTasksStmt->execute($empVisTasks['params']);
$empMyTasksCount = (int)$empMyTasksStmt->fetchColumn();

$empInProgressStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE {$empVisTasks['sql']} AND status = 'running'");
$empInProgressStmt->execute($empVisTasks['params']);
$empInProgressCount = (int)$empInProgressStmt->fetchColumn();

$empCompletedStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE {$empVisTasks['sql']} AND status = 'completed'");
$empCompletedStmt->execute($empVisTasks['params']);
$empCompletedCount = (int)$empCompletedStmt->fetchColumn();

$empPendingStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE {$empVisTasks['sql']} AND status = 'pending'");
$empPendingStmt->execute($empVisTasks['params']);
$empPendingCount = (int)$empPendingStmt->fetchColumn();

$threeDaysLater = date('Y-m-d H:i:s', strtotime('+3 days'));
$empUpcomingDeadlinesStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE {$empVisTasks['sql']} AND status != 'completed' AND deadline BETWEEN ? AND ?");
$empUpcomingDeadlinesStmt->execute(array_merge($empVisTasks['params'], [$nowStr, $threeDaysLater]));
$empUpcomingDeadlinesCount = (int)$empUpcomingDeadlinesStmt->fetchColumn();

// Employee Work Queue (filtered by visibility rule)
$empTaskQueueVis = getTaskVisibilityCondition($user, 't');
$empTasksListStmt = $db->prepare("
    SELECT t.*, u.name as assigned_to_name, u.avatar as assigned_to_avatar, p.name as project_name
    FROM tasks t
    LEFT JOIN users u ON (t.assigned_to = u.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u.id))
    LEFT JOIN projects p ON t.project_id = p.id
    WHERE {$empTaskQueueVis['sql']}
    ORDER BY (CASE WHEN t.status IN ('running', 'in_progress') THEN 0 WHEN t.status IN ('pending', 'pending_approval') THEN 1 ELSE 2 END) ASC, t.id DESC
    LIMIT 10
");
$empTasksListStmt->execute($empTaskQueueVis['params']);
$empTasksList = $empTasksListStmt->fetchAll() ?: [];
?>

<style>
/* Robust styles for Give SEO Task and Give Dev Task Modals */
#giveSeoTaskModal, #giveDevTaskModal {
    z-index: 1065 !important;
}
#giveSeoTaskModal .modal-dialog, #giveDevTaskModal .modal-dialog {
    max-width: 720px;
}
.modal-backdrop {
    z-index: 1060 !important;
}

/* Activity Pill Buttons & Mobile Grid Alignment */
.dash-activity-pill-btn, .activity-pill-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 500;
    border-radius: 9999px;
    cursor: pointer;
}
.dash-activity-pill-btn:hover, .activity-pill-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
}
.dash-activity-pill-btn.active, .activity-pill-btn.active {
    font-weight: 700 !important;
    color: #fff !important;
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
    border-color: transparent !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.28) !important;
}
.dash-activity-pill-btn.active i, .activity-pill-btn.active i,
.dash-activity-pill-btn.active span, .activity-pill-btn.active span {
    color: #fff !important;
}
.dash-activity-pill-btn.btn-outline-purple.active, .activity-pill-btn.btn-outline-purple.active {
    background: linear-gradient(135deg, #7c3aed 0%, #8b5cf6 100%) !important;
    border-color: transparent !important;
}
.dash-activity-pill-btn.btn-outline-warning.active, .activity-pill-btn.btn-outline-warning.active {
    background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%) !important;
    color: #fff !important;
    border-color: transparent !important;
}
.dash-activity-pill-btn.btn-outline-danger.active, .activity-pill-btn.btn-outline-danger.active {
    background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%) !important;
    border-color: transparent !important;
}
.dash-activity-pill-btn.btn-outline-info.active, .activity-pill-btn.btn-outline-info.active {
    background: linear-gradient(135deg, #0284c7 0%, #0ea5e9 100%) !important;
    border-color: transparent !important;
}
.dash-activity-pill-btn.btn-outline-secondary.active, .activity-pill-btn.btn-outline-secondary.active {
    background: linear-gradient(135deg, #475569 0%, #64748b 100%) !important;
    border-color: transparent !important;
}

@media (max-width: 767.98px) {
    #dashActivityPillsContainer, #activityPillsContainer {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 0.45rem !important;
        width: 100% !important;
    }
    .dash-activity-pill-btn, .activity-pill-btn {
        width: 100% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        padding: 0.42rem 0.6rem !important;
        font-size: 0.74rem !important;
        font-weight: 600 !important;
        border-radius: 10px !important;
        min-height: 38px !important;
        box-sizing: border-box !important;
        text-align: left !important;
        margin: 0 !important;
        line-height: 1.2 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        background-color: #ffffff;
    }
    .dash-activity-pill-btn i, .activity-pill-btn i {
        font-size: 0.85rem !important;
        width: 18px !important;
        min-width: 18px !important;
        text-align: center !important;
        margin-right: 0.35rem !important;
        flex-shrink: 0 !important;
    }
    .dash-activity-pill-btn .act-label, .activity-pill-btn .act-label {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        flex: 1 !important;
        min-width: 0 !important;
        display: block !important;
    }
    #dashActivityPillsContainer > .dash-activity-pill-btn:last-child:nth-child(odd),
    #activityPillsContainer > .activity-pill-btn:last-child:nth-child(odd) {
        grid-column: 1 / -1 !important;
        justify-content: center !important;
        text-align: center !important;
    }
    #dashActivityPillsContainer > .dash-activity-pill-btn:last-child:nth-child(odd) .act-label,
    #activityPillsContainer > .activity-pill-btn:last-child:nth-child(odd) .act-label {
        flex: 0 1 auto !important;
    }
    .dash-activity-active-banner {
        padding: 0.65rem 0.85rem !important;
        margin-bottom: 0.85rem !important;
    }
    .dash-activity-actions-row {
        display: flex !important;
        flex-direction: column-reverse !important;
        gap: 0.5rem !important;
        width: 100% !important;
        margin-top: 0.75rem !important;
    }
    .dash-activity-actions-row .btn {
        width: 100% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0.5rem 1rem !important;
        font-size: 0.82rem !important;
        min-height: 38px !important;
        border-radius: 9999px !important;
    }
}

@media (min-width: 480px) and (max-width: 767.98px) {
    .dash-activity-actions-row {
        flex-direction: row !important;
        justify-content: space-between !important;
    }
    .dash-activity-actions-row .btn {
        width: auto !important;
        flex: 1 !important;
    }
}

@media (min-width: 768px) {
    #dashActivityPillsContainer {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)) !important;
        gap: 0.5rem !important;
        width: 100% !important;
    }
    .dash-activity-pill-btn, .activity-pill-btn {
        width: 100% !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: flex-start !important;
        padding: 0.42rem 0.75rem !important;
        font-size: 0.78rem !important;
        font-weight: 600 !important;
        border-radius: 0.65rem !important;
        min-height: 38px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
    }
    .dash-activity-pill-btn i, .activity-pill-btn i {
        width: 18px !important;
        text-align: center !important;
        margin-right: 0.45rem !important;
        flex-shrink: 0 !important;
        font-size: 0.85rem !important;
    }
    .dash-activity-pill-btn .act-label, .activity-pill-btn .act-label {
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        white-space: nowrap !important;
        flex: 1 !important;
    }
}
</style>
<script>
window.openGiveSeoTaskModal = function(assigneeId = null, event = null) {
    if (event) {
        if (typeof event.preventDefault === 'function') event.preventDefault();
        if (typeof event.stopPropagation === 'function') event.stopPropagation();
    }
    const modalEl = document.getElementById('giveSeoTaskModal');
    if (!modalEl) {
        window.location.href = '<?= BASE_URL ?>/tasks' + (assigneeId ? '?assign_to=' + assigneeId : '');
        return;
    }
    if (assigneeId) {
        const selectEl = modalEl.querySelector('select[name="assigned_to"]');
        if (selectEl) {
            selectEl.value = String(assigneeId);
        }
    }
    document.querySelectorAll('.modal-backdrop').forEach(b => {
        if (!document.querySelector('.modal.show')) b.remove();
    });
    try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            bsModal.show();
        } else if (typeof $ !== 'undefined' && typeof $(modalEl).modal === 'function') {
            $(modalEl).modal('show');
        } else {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.removeAttribute('aria-hidden');
            modalEl.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');
            if (!document.querySelector('.modal-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'manualModalBackdrop';
                backdrop.onclick = function() {
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                    backdrop.remove();
                    document.body.classList.remove('modal-open');
                };
                document.body.appendChild(backdrop);
            }
        }
    } catch (err) {
        console.error("openGiveSeoTaskModal error:", err);
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        document.body.classList.add('modal-open');
    }
};

window.openGiveDevTaskModal = function(assigneeId = 9, event = null) {
    if (event) {
        if (typeof event.preventDefault === 'function') event.preventDefault();
        if (typeof event.stopPropagation === 'function') event.stopPropagation();
    }
    const modalEl = document.getElementById('giveDevTaskModal');
    if (!modalEl) {
        window.location.href = '<?= BASE_URL ?>/tasks' + (assigneeId ? '?assign_to=' + assigneeId : '');
        return;
    }
    if (assigneeId) {
        const selectEl = modalEl.querySelector('select[name="assigned_to"]');
        if (selectEl) {
            selectEl.value = String(assigneeId);
        }
    }
    document.querySelectorAll('.modal-backdrop').forEach(b => {
        if (!document.querySelector('.modal.show')) b.remove();
    });
    try {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            bsModal.show();
        } else if (typeof $ !== 'undefined' && typeof $(modalEl).modal === 'function') {
            $(modalEl).modal('show');
        } else {
            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.removeAttribute('aria-hidden');
            modalEl.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');
            if (!document.querySelector('.modal-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'manualModalBackdrop';
                backdrop.onclick = function() {
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                    backdrop.remove();
                    document.body.classList.remove('modal-open');
                };
                document.body.appendChild(backdrop);
            }
        }
    } catch (err) {
        console.error("openGiveDevTaskModal error:", err);
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        document.body.classList.add('modal-open');
    }
};

document.addEventListener('click', function(e) {
    const dismissBtn = e.target.closest('[data-bs-dismiss="modal"]');
    if (dismissBtn) {
        const modal = dismissBtn.closest('.modal');
        if (modal) {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const inst = bootstrap.Modal.getInstance(modal);
                if (inst) inst.hide();
            }
            modal.classList.remove('show');
            modal.style.display = 'none';
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            document.body.classList.remove('modal-open');
        }
    }
});
</script>

<?php
if (!function_exists('renderTaskApprovalQueueCard')) {
function renderTaskApprovalQueueCard(array $pendingApprovals): void {
    $count = count($pendingApprovals);
?>
    <!-- TASK COMPLETION APPROVAL QUEUE CARD -->
    <div class="glass-card mb-4 approval-queue-card" id="taskApprovalQueueSection" data-dash-section="approvals">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.15); color: #d97706; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        Task Completion Approval Queue
                    </h5>
                    <small class="text-secondary">Submitted deliverables awaiting Manager & Product Manager sign-off</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                    <i class="fas fa-bell me-1 animate-bounce"></i><?= $count ?> Awaiting Approval
                </span>
                <button type="button" class="btn btn-sm btn-light border rounded-pill px-2.5" onclick="location.reload()" title="Refresh Queue">
                    <i class="fas fa-sync-alt text-secondary"></i>
                </button>
            </div>
        </div>

        <?php if (empty($pendingApprovals)): ?>
            <div class="p-4 text-center bg-light rounded-4 border border-dashed">
                <div class="text-success fs-2 mb-2">🎉</div>
                <h6 class="fw-bold text-dark mb-1">All Caught Up! No Task Approvals Pending</h6>
                <p class="text-secondary small mb-0">When employee tasks are completed and submitted, approval requests will appear here for Manager sign-off.</p>
            </div>
        <?php else: ?>
            <div class="approval-card-list d-flex flex-column gap-3">
                <?php foreach ($pendingApprovals as $apTask): 
                    $assigneeAvatar = !empty($apTask['assignee_avatar']) ? $apTask['assignee_avatar'] : ('https://ui-avatars.com/api/?name=' . urlencode($apTask['assignee_name']) . '&background=4f46e5&color=fff');
                ?>
                    <div class="approval-item-card p-3.5 bg-white border rounded-4 shadow-xs">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2.5">
                            <div class="d-flex align-items-center gap-2.5">
                                <img src="<?= $assigneeAvatar ?>" class="rounded-circle border shadow-xs" width="44" height="44" alt="Employee">
                                <div>
                                    <div class="fw-bold text-dark fs-6"><?= sanitize($apTask['assignee_name']) ?></div>
                                    <small class="text-muted d-block fs-8">
                                        <span class="text-secondary fw-semibold"><?= sanitize($apTask['assignee_designation'] ?? 'Team Member') ?></span>
                                        &bull; <?= sanitize($apTask['department_name'] ?? 'General') ?>
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <?= getPriorityBadge($apTask['priority']) ?>
                                <span class="badge bg-warning-subtle text-warning border border-warning px-2.5 py-1 rounded-pill fs-8 fw-semibold">
                                    <i class="fas fa-hourglass-half me-1"></i>Awaiting Approval
                                </span>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-3 border">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <a href="<?= BASE_URL ?>/task-details?id=<?= $apTask['id'] ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-7">
                                    <span class="text-muted font-monospace me-1">#<?= $apTask['id'] ?></span> <?= sanitize($apTask['title']) ?>
                                </a>
                                <span class="badge bg-white text-secondary border font-monospace fs-8">
                                    <i class="fas fa-clock me-1 text-primary"></i><?= formatDuration($apTask['spent_seconds']) ?>
                                </span>
                            </div>
                            <?php if (!empty($apTask['project_name'])): ?>
                                <small class="text-muted d-block mb-1 fs-8">
                                    <i class="fas fa-folder me-1 text-warning"></i>Project: <strong><?= sanitize($apTask['project_name']) ?></strong>
                                </small>
                            <?php endif; ?>
                            <div class="small text-secondary mt-1 pt-1 border-top border-light-subtle">
                                <i class="fas fa-quote-left text-primary me-1 opacity-50"></i>
                                <strong>Work Notes:</strong> <?= sanitize($apTask['completion_notes'] ?: 'Completed task and submitted for manager approval.') ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <small class="text-muted fs-8">
                                <i class="fas fa-calendar-check me-1 text-secondary"></i>Submitted: <?= formatISTDateTime($apTask['submitted_for_approval_at'] ?? $apTask['updated_at']) ?>
                            </small>
                            <div class="d-flex align-items-center gap-2">
                                <a href="<?= BASE_URL ?>/task-details?id=<?= $apTask['id'] ?>" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                    <i class="fas fa-eye me-1"></i>Details
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold" onclick="rejectApprovalPrompt(<?= $apTask['id'] ?>)">
                                    <i class="fas fa-undo me-1"></i>Revision
                                </button>
                                <button type="button" class="btn btn-sm btn-success rounded-pill px-3.5 fw-bold shadow-sm" onclick="approveTaskPrompt(<?= $apTask['id'] ?>)">
                                    <i class="fas fa-check-circle me-1"></i>Approve & Mark Done
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php }
} ?>

<?php
if (!function_exists('renderEmergencyRequestsQueueCard')) {
function renderEmergencyRequestsQueueCard(array $pendingEmgRequests): void {
    $count = count($pendingEmgRequests);
    if ($count === 0) return;
?>
    <!-- EMERGENCY TASK REQUEST APPROVAL QUEUE CARD -->
    <div class="glass-card mb-4 border-danger-subtle shadow-sm" id="emergencyApprovalQueueSection" data-dash-section="emergency">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.15); color: #dc2626; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-bolt animate-bounce"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        🚨 Emergency Task Escalation Queue
                    </h5>
                    <small class="text-secondary">High-priority requests requiring immediate Admin/Manager decision</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">
                    <i class="fas fa-bell me-1"></i><?= $count ?> Pending Review
                </span>
                <a href="<?= BASE_URL ?>/emergency-approvals" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold">
                    Approvals Hub &rarr;
                </a>
            </div>
        </div>

        <div class="approval-card-list d-flex flex-column gap-3">
            <?php foreach ($pendingEmgRequests as $emg): 
                $empAvatar = !empty($emg['employee_avatar']) ? $emg['employee_avatar'] : ('https://ui-avatars.com/api/?name=' . urlencode($emg['employee_name']) . '&background=dc2626&color=fff');
            ?>
                <div class="p-3.5 bg-white border border-danger-subtle rounded-4 shadow-xs">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2.5">
                        <div class="d-flex align-items-center gap-2.5">
                            <img src="<?= $empAvatar ?>" class="rounded-circle border shadow-xs" width="44" height="44" alt="Employee">
                            <div>
                                <div class="fw-bold text-dark fs-6"><?= sanitize($emg['employee_name']) ?></div>
                                <small class="text-muted d-block fs-8">
                                    <span class="text-secondary fw-semibold"><?= sanitize($emg['employee_designation'] ?? 'Staff') ?></span>
                                    &bull; <?= sanitize($emg['department_name'] ?? 'General') ?>
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-danger border border-danger-subtle font-monospace fs-8">#REQ-<?= $emg['id'] ?></span>
                            <span class="badge bg-danger-subtle text-danger border border-danger px-2.5 py-1 rounded-pill fs-8 fw-semibold">
                                <i class="fas fa-clock me-1"></i>Pending Approval
                            </span>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <a href="<?= BASE_URL ?>/task-details?id=<?= $emg['task_id'] ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-7">
                                <span class="text-muted font-monospace me-1">#<?= $emg['task_id'] ?></span> <?= sanitize($emg['task_title']) ?>
                            </a>
                            <span class="badge bg-danger text-white fs-8"><?= sanitize($emg['reason']) ?></span>
                        </div>
                        <div class="small text-secondary mt-1 pt-1 border-top border-light-subtle">
                            <i class="fas fa-quote-left text-danger me-1 opacity-50"></i>
                            <strong>Urgency Justification:</strong> <?= nl2br(sanitize($emg['message'])) ?>
                        </div>
                        <?php if (!empty($emg['attachment'])): ?>
                            <div class="mt-2">
                                <a href="<?= BASE_URL . sanitize($emg['attachment']) ?>" target="_blank" class="btn btn-xs btn-outline-danger">
                                    <i class="fas fa-paperclip me-1"></i>View Attachment
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted fs-8">
                            <i class="fas fa-calendar-alt me-1 text-secondary"></i>Escalated: <?= formatISTDateTime($emg['created_at']) ?>
                        </small>
                        <div class="d-flex align-items-center gap-2">
                            <a href="<?= BASE_URL ?>/emergency-approvals?id=<?= $emg['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                Review Details
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold" onclick="promptRejectEmergency(<?= $emg['id'] ?>)">
                                Reject
                            </button>
                            <button type="button" class="btn btn-sm btn-danger rounded-pill px-3.5 fw-bold shadow-sm" onclick="promptApproveEmergency(<?= $emg['id'] ?>)">
                                <i class="fas fa-check me-1"></i>Approve
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php }
} ?>

<?php
if (!function_exists('renderFlaggedTasksQueueCard')) {
function renderFlaggedTasksQueueCard(array $openTaskFlags): void {
    $count = count($openTaskFlags);
    if ($count === 0) return;
?>
    <!-- FLAGGED TASKS QUEUE CARD -->
    <div class="glass-card mb-4 border-warning-subtle shadow-sm" id="flaggedTasksQueueSection" data-dash-section="flags">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.15); color: #d97706; width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fas fa-flag"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        🚩 Missing Requirements / Flagged Tasks
                    </h5>
                    <small class="text-secondary">Reported blockers & missing information from team members</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                    <i class="fas fa-flag me-1"></i><?= $count ?> Active Flags
                </span>
                <a href="<?= BASE_URL ?>/flagged-tasks" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 fw-semibold">
                    Flag Center &rarr;
                </a>
            </div>
        </div>

        <div class="approval-card-list d-flex flex-column gap-3">
            <?php foreach ($openTaskFlags as $flag): 
                $empAvatar = !empty($flag['employee_avatar']) ? $flag['employee_avatar'] : ('https://ui-avatars.com/api/?name=' . urlencode($flag['employee_name']) . '&background=f59e0b&color=fff');
            ?>
                <div class="p-3.5 bg-white border border-warning-subtle rounded-4 shadow-xs">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2.5">
                        <div class="d-flex align-items-center gap-2.5">
                            <img src="<?= $empAvatar ?>" class="rounded-circle border shadow-xs" width="44" height="44" alt="Employee">
                            <div>
                                <div class="fw-bold text-dark fs-6"><?= sanitize($flag['employee_name']) ?></div>
                                <small class="text-muted d-block fs-8">
                                    <span class="text-secondary fw-semibold"><?= sanitize($flag['employee_designation'] ?? 'Staff') ?></span>
                                    &bull; <?= sanitize($flag['department_name'] ?? 'General') ?>
                                </small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-warning border font-monospace fs-8">#FLAG-<?= $flag['id'] ?></span>
                            <?= getTaskFlagBadge($flag['status']) ?>
                            <?= getTaskFlagImpactBadge($flag['impact']) ?>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <a href="<?= BASE_URL ?>/task-details?id=<?= $flag['task_id'] ?>" class="fw-bold text-dark text-decoration-none hover-primary fs-7">
                                <span class="text-muted font-monospace me-1">#<?= $flag['task_id'] ?></span> <?= sanitize($flag['task_title']) ?>
                            </a>
                            <span class="badge bg-warning text-dark fs-8"><?= sanitize($flag['category']) ?></span>
                        </div>
                        <div class="small text-secondary mt-1 pt-1 border-top border-light-subtle">
                            <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                            <strong>Missing Item:</strong> <?= nl2br(sanitize($flag['missing_requirement'])) ?>
                        </div>
                        <?php if (!empty($flag['attachment'])): ?>
                            <div class="mt-2">
                                <a href="<?= BASE_URL . sanitize($flag['attachment']) ?>" target="_blank" class="btn btn-xs btn-outline-warning text-dark">
                                    <i class="fas fa-paperclip me-1"></i>View Attachment
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted fs-8">
                            <i class="fas fa-clock me-1 text-secondary"></i>Reported: <?= formatISTDateTime($flag['created_at']) ?>
                        </small>
                        <div class="d-flex align-items-center gap-2">
                            <a href="<?= BASE_URL ?>/flagged-tasks?flag_id=<?= $flag['id'] ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                Discuss / Reply
                            </a>
                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3.5 fw-bold shadow-sm" onclick="promptResolveFlag(<?= $flag['id'] ?>)">
                                <i class="fas fa-check-circle me-1"></i>Mark Resolved
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php }
} ?>

<div class="main-wrapper theme-<?= $activeRoleView ?>">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="dashboard-page-container p-3 p-md-4">
        <!-- ================================================================= -->
        <!-- MOBILE APP VIEW HEADER & QUICK LAUNCHER (< 768px App View)       -->
        <!-- ================================================================= -->
        <div class="mobile-app-shell d-block d-md-none mb-3">
            <!-- App Top Bar Card -->
            <div class="app-mobile-topbar">
                <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="app-user-avatar-wrap">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=4f46e5&color=fff" alt="User">
                            <span class="app-user-status-dot" style="background: <?= $statusDetails['status_color'] ?? '#10b981' ?>;"></span>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-6 line-height-1 mb-0.5">Hi, <?= sanitize(explode(' ', $user['name'])[0]) ?> 👋</div>
                            <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-0.5 rounded-pill fs-9 fw-semibold">
                                <?= sanitize($user['role_title'] ?? $user['designation'] ?? ucfirst(str_replace('_', ' ', $user['role_name'] ?? 'Staff'))) ?>
                            </span>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <span class="app-mode-badge"><i class="fas fa-mobile-screen-button"></i>App View</span>
                    </div>
                </div>

                <!-- Shift Status Info Strip on Mobile -->
                <div class="p-2 bg-light rounded-3 d-flex justify-content-between align-items-center fs-8 text-secondary">
                    <span><i class="fas fa-clock text-primary me-1"></i>Shift: <strong><?= $fmtShiftStart ?> &ndash; <?= $fmtShiftEnd ?></strong></span>
                    <span class="badge <?= $statusDetails['status_badge_class'] ?> rounded-pill fs-9"><?= $statusDetails['status_key'] ?></span>
                </div>
            </div>

            <!-- App Quick Launcher Dock -->
            <div class="app-launcher-grid">
                <?php if ($activeRoleView === 'admin'): ?>
                    <a href="javascript:void(0)" onclick="openGiveSeoTaskModal(null, event)" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-bullhorn"></i></div>
                        <span class="app-launcher-tile-label">Give SEO Task</span>
                    </a>
                    <a href="javascript:void(0)" onclick="openGiveDevTaskModal(9, event)" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;"><i class="fas fa-code"></i></div>
                        <span class="app-launcher-tile-label">Give Dev Task</span>
                    </a>
                    <a href="<?= BASE_URL ?>/payroll" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-wallet"></i></div>
                        <span class="app-launcher-tile-label">Payroll</span>
                    </a>
                    <a href="javascript:void(0)" onclick="switchMobileDashboardTab('approvals')" class="app-launcher-tile">
                        <?php if ($pendingApprovalsCount > 0): ?>
                            <span class="badge-counter"><?= $pendingApprovalsCount ?></span>
                        <?php endif; ?>
                        <div class="app-launcher-tile-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-clipboard-check"></i></div>
                        <span class="app-launcher-tile-label">Approvals</span>
                    </a>
                    <a href="<?= BASE_URL ?>/employees" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5;"><i class="fas fa-users"></i></div>
                        <span class="app-launcher-tile-label">Employees</span>
                    </a>
                    <a href="<?= BASE_URL ?>/tasks" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(6, 182, 212, 0.12); color: #06b6d4;"><i class="fas fa-tasks"></i></div>
                        <span class="app-launcher-tile-label">All Tasks</span>
                    </a>
                <?php elseif ($activeRoleView === 'manager'): ?>
                    <a href="javascript:void(0)" onclick="openGiveSeoTaskModal(null, event)" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-bullhorn"></i></div>
                        <span class="app-launcher-tile-label">Give SEO Task</span>
                    </a>
                    <a href="javascript:void(0)" onclick="openGiveDevTaskModal(9, event)" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;"><i class="fas fa-code"></i></div>
                        <span class="app-launcher-tile-label">Give Dev Task</span>
                    </a>
                    <a href="javascript:void(0)" onclick="switchMobileDashboardTab('approvals')" class="app-launcher-tile">
                        <?php if ($pendingApprovalsCount > 0): ?>
                            <span class="badge-counter"><?= $pendingApprovalsCount ?></span>
                        <?php endif; ?>
                        <div class="app-launcher-tile-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-clipboard-check"></i></div>
                        <span class="app-launcher-tile-label">Approvals</span>
                    </a>
                    <a href="<?= BASE_URL ?>/tasks" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5;"><i class="fas fa-plus"></i></div>
                        <span class="app-launcher-tile-label">Assign Task</span>
                    </a>
                    <a href="<?= BASE_URL ?>/kanban" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(13, 148, 136, 0.12); color: #0d9488;"><i class="fas fa-columns"></i></div>
                        <span class="app-launcher-tile-label">Kanban</span>
                    </a>
                    <a href="<?= BASE_URL ?>/attendance" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(6, 182, 212, 0.12); color: #06b6d4;"><i class="fas fa-user-clock"></i></div>
                        <span class="app-launcher-tile-label">Team Live</span>
                    </a>
                <?php elseif ($activeRoleView === 'product_manager'): ?>
                    <a href="javascript:void(0)" onclick="openGiveSeoTaskModal(null, event)" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-bullhorn"></i></div>
                        <span class="app-launcher-tile-label">Give SEO Task</span>
                    </a>
                    <a href="javascript:void(0)" onclick="openGiveDevTaskModal(9, event)" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;"><i class="fas fa-code"></i></div>
                        <span class="app-launcher-tile-label">Give Dev Task</span>
                    </a>
                    <a href="<?= BASE_URL ?>/projects" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(217, 119, 6, 0.12); color: #d97706;"><i class="fas fa-cubes"></i></div>
                        <span class="app-launcher-tile-label">Sprints</span>
                    </a>
                    <a href="javascript:void(0)" onclick="switchMobileDashboardTab('approvals')" class="app-launcher-tile">
                        <?php if ($pendingApprovalsCount > 0): ?>
                            <span class="badge-counter"><?= $pendingApprovalsCount ?></span>
                        <?php endif; ?>
                        <div class="app-launcher-tile-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-clipboard-check"></i></div>
                        <span class="app-launcher-tile-label">Approvals</span>
                    </a>
                    <a href="<?= BASE_URL ?>/tasks" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(37, 99, 235, 0.12); color: #2563eb;"><i class="fas fa-list-check"></i></div>
                        <span class="app-launcher-tile-label">Backlog</span>
                    </a>
                    <a href="<?= BASE_URL ?>/kanban" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;"><i class="fas fa-columns"></i></div>
                        <span class="app-launcher-tile-label">Sprint Board</span>
                    </a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/work-log" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-calendar-check"></i></div>
                        <span class="app-launcher-tile-label">Work Log</span>
                    </a>
                    <?php if ($isSeoLead): ?>
                        <a href="javascript:void(0)" onclick="openGiveSeoTaskModal(null, event)" class="app-launcher-tile">
                            <div class="app-launcher-tile-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-bullhorn"></i></div>
                            <span class="app-launcher-tile-label">Give SEO Task</span>
                        </a>
                    <?php elseif ($isDevLead): ?>
                        <a href="javascript:void(0)" onclick="openGiveDevTaskModal(9, event)" class="app-launcher-tile">
                            <div class="app-launcher-tile-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;"><i class="fas fa-code"></i></div>
                            <span class="app-launcher-tile-label">Give Dev Task</span>
                        </a>
                    <?php elseif ($canManageTasks): ?>
                        <a href="javascript:void(0)" onclick="openGiveSeoTaskModal(null, event)" class="app-launcher-tile">
                            <div class="app-launcher-tile-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-bullhorn"></i></div>
                            <span class="app-launcher-tile-label">Give SEO Task</span>
                        </a>
                        <a href="javascript:void(0)" onclick="openGiveDevTaskModal(9, event)" class="app-launcher-tile">
                            <div class="app-launcher-tile-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;"><i class="fas fa-code"></i></div>
                            <span class="app-launcher-tile-label">Give Dev Task</span>
                        </a>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/tasks" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5;"><i class="fas fa-tasks"></i></div>
                        <span class="app-launcher-tile-label"><?= ($canManageTasks || $isSeoLead || $isDevLead) ? 'Tasks' : 'My Tasks' ?></span>
                    </a>
                    <a href="<?= BASE_URL ?>/workday" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-bolt"></i></div>
                        <span class="app-launcher-tile-label">Live Activity</span>
                    </a>
                    <a href="javascript:void(0)" onclick="openDashScheduleMeetingModal()" class="app-launcher-tile">
                        <div class="app-launcher-tile-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;"><i class="fas fa-video"></i></div>
                        <span class="app-launcher-tile-label">Meeting Sync</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Segmented Mobile App Tab Navigation Pills -->
            <div class="app-segmented-tabs-wrapper">
                <div class="app-segmented-tabs">
                    <button type="button" class="app-tab-pill active" onclick="switchMobileDashboardTab('all')">
                        <i class="fas fa-layer-group"></i>All
                    </button>
                    <button type="button" class="app-tab-pill" onclick="switchMobileDashboardTab('stats')">
                        <i class="fas fa-chart-pie"></i>Overview
                    </button>
                    <?php if ($activeRoleView !== 'employee'): ?>
                        <button type="button" class="app-tab-pill" onclick="switchMobileDashboardTab('approvals')">
                            <i class="fas fa-clipboard-check"></i>Approvals (<?= $pendingApprovalsCount ?>)
                        </button>
                    <?php endif; ?>
                    <button type="button" class="app-tab-pill" onclick="switchMobileDashboardTab('tasks')">
                        <i class="fas fa-tasks"></i>Tasks
                    </button>
                    <button type="button" class="app-tab-pill" onclick="switchMobileDashboardTab('activity')">
                        <i class="fas fa-business-time"></i>Activity
                    </button>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- DESKTOP BANNER HEADER (>= 768px Desktop View)                     -->
        <!-- ================================================================= -->
        <div class="glass-card mb-4 p-4 d-none d-md-flex align-items-center justify-content-between flex-wrap gap-3 dash-desktop-banner">
            <div class="dash-banner-info">
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill fw-bold">
                        <i class="fas fa-sparkles me-1"></i><?= sanitize($user['role_title'] ?? $user['designation'] ?? ucfirst(str_replace('_', ' ', $user['role_name'] ?? 'Staff'))) ?> Portal
                    </span>
                    <?php if ($isAdmin && $activeRoleView !== 'admin'): ?>
                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                            <i class="fas fa-eye me-1"></i>Previewing View: <?= strtoupper($activeRoleView) ?>
                        </span>
                        <a href="<?= BASE_URL ?>/dashboard?view_as=admin" class="badge bg-secondary text-white text-decoration-none px-2 py-1 rounded">Reset View</a>
                    <?php endif; ?>
                </div>
                <h2 class="fw-bold text-dark mb-1 font-heading">Welcome back, <?= sanitize($user['name']) ?> 👋</h2>
                <p class="text-secondary mb-0 fs-7">Fixed Office Shift: <strong class="text-dark">09:40 AM IST</strong> to <strong class="text-dark">06:30 PM IST</strong> (8h 50m Target | 02:00 PM - 03:00 PM Lunch Break)</p>
            </div>
            
            <div class="dash-banner-actions d-flex gap-2 flex-wrap align-items-center">
                <?php if ($activeRoleView === 'admin'): ?>
                    <a href="<?= BASE_URL ?>/tasks?action=new_task" class="btn btn-primary fw-bold shadow-sm">
                        <i class="fas fa-plus-circle me-1"></i>Create & Assign Task
                    </a>
                    <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="openGiveSeoTaskModal(null, event)">
                        <i class="fas fa-bullhorn me-1"></i>Give SEO Task (Aman & Shivansh)
                    </button>
                    <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="openGiveDevTaskModal(9, event)">
                        <i class="fas fa-code me-1"></i>Give Dev Task (Abhay)
                    </button>
                    <a href="<?= BASE_URL ?>/payroll" class="btn btn-warning text-dark fw-semibold"><i class="fas fa-wallet me-1"></i>Payroll</a>
                    <button type="button" class="btn btn-outline-warning text-dark fw-semibold" data-bs-toggle="modal" data-bs-target="#generatePayrollModal"><i class="fas fa-calculator me-1"></i>Generate Salary</button>
                    <a href="<?= BASE_URL ?>/employees" class="btn btn-outline-primary fw-semibold"><i class="fas fa-user-plus me-1"></i>Add Employee</a>
                <?php elseif ($activeRoleView === 'manager'): ?>
                    <a href="<?= BASE_URL ?>/tasks?action=new_task" class="btn btn-primary fw-bold shadow-sm">
                        <i class="fas fa-plus-circle me-1"></i>Create & Assign Task
                    </a>
                    <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="openGiveSeoTaskModal(null, event)">
                        <i class="fas fa-bullhorn me-1"></i>Give SEO Task (Aman & Shivansh)
                    </button>
                    <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="openGiveDevTaskModal(9, event)">
                        <i class="fas fa-code me-1"></i>Give Dev Task (Abhay)
                    </button>
                    <a href="<?= BASE_URL ?>/tasks" class="btn btn-outline-primary fw-semibold"><i class="fas fa-tasks me-1"></i>Manage Tasks</a>
                    <a href="<?= BASE_URL ?>/kanban" class="btn btn-outline-secondary fw-semibold"><i class="fas fa-columns me-1"></i>Kanban Board</a>
                <?php elseif ($activeRoleView === 'product_manager'): ?>
                    <a href="<?= BASE_URL ?>/tasks?action=new_task" class="btn btn-primary fw-bold shadow-sm">
                        <i class="fas fa-plus-circle me-1"></i>Create & Assign Task
                    </a>
                    <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="openGiveSeoTaskModal(null, event)">
                        <i class="fas fa-bullhorn me-1"></i>Give SEO Task (Aman & Shivansh)
                    </button>
                    <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="openGiveDevTaskModal(9, event)">
                        <i class="fas fa-code me-1"></i>Give Dev Task (Abhay)
                    </button>
                    <a href="<?= BASE_URL ?>/projects" class="btn btn-warning fw-semibold text-dark"><i class="fas fa-plus me-1"></i>New Project / Sprint</a>
                    <a href="<?= BASE_URL ?>/kanban" class="btn btn-outline-primary fw-semibold"><i class="fas fa-columns me-1"></i>Sprint Board</a>
                <?php else: ?>
                    <?php if ($isSeoLead): ?>
                        <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="openGiveSeoTaskModal(null, event)">
                            <i class="fas fa-bullhorn me-1"></i>Give SEO Task (Aman & Shivansh)
                        </button>
                    <?php elseif ($isDevLead): ?>
                        <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="openGiveDevTaskModal(9, event)">
                            <i class="fas fa-code me-1"></i>Give Dev Task (Abhay)
                        </button>
                    <?php elseif ($canManageTasks): ?>
                        <button type="button" class="btn btn-success fw-bold shadow-sm" onclick="openGiveSeoTaskModal(null, event)">
                            <i class="fas fa-bullhorn me-1"></i>Give SEO Task (Aman & Shivansh)
                        </button>
                        <button type="button" class="btn btn-primary fw-bold shadow-sm" onclick="openGiveDevTaskModal(9, event)">
                            <i class="fas fa-code me-1"></i>Give Dev Task (Abhay)
                        </button>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/tasks" class="btn btn-outline-primary fw-semibold"><i class="fas fa-tasks me-1"></i><?= ($canManageTasks || $isSeoLead || $isDevLead) ? 'Task Management' : 'My Work Queue' ?></a>
                <?php endif; ?>
                
                <button onclick="handleClockInOut('<?= $isShiftRunning ? 'clock_out' : 'clock_in' ?>')" 
                        class="btn <?= $isShiftRunning ? 'btn-danger' : 'btn-outline-success' ?> fw-semibold shift-toggle-btn align-items-center wifi-restricted-btn <?= $isClientIpAuth ? 'd-inline-flex' : 'd-none' ?>"
                        style="<?= $isClientIpAuth ? '' : 'display:none !important;' ?>">
                    <i class="fas <?= $isShiftRunning ? 'fa-stop' : 'fa-play' ?> me-1"></i><?= $isShiftRunning ? 'End Shift' : 'Start Shift' ?>
                </button>
            </div>
        </div>

        <?php if ($activeRoleView === 'admin'): ?>
            <!-- ================================================================= -->
            <!-- 1. ADMIN DASHBOARD VIEW (PREMIUM EXECUTIVE COMMAND CENTER)        -->
            <!-- ================================================================= -->

            <?php renderTaskApprovalQueueCard($pendingApprovals); ?>
            <?php renderEmergencyRequestsQueueCard($pendingEmgRequests); ?>
            <?php renderFlaggedTasksQueueCard($openTaskFlags); ?>

            <!-- 11 Statistics Cards Grid -->
            <div class="row g-3 mb-4 dashboard-stats-grid align-items-stretch" data-dash-section="stats">
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #4f46e5;">
                        <div class="stat-card-icon" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5;"><i class="fas fa-users"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $totalEmpCount ?></div>
                            <small class="text-secondary fw-semibold">Total Employees</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #10b981;">
                        <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-user-check"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $activeEmpCount ?></div>
                            <small class="text-secondary fw-semibold">Active Staff</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #0d9488;">
                        <div class="stat-card-icon" style="background: rgba(13, 148, 136, 0.12); color: #0d9488;"><i class="fas fa-user-shield"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $managersCount ?></div>
                            <small class="text-secondary fw-semibold">Managers</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #3b82f6;">
                        <div class="stat-card-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;"><i class="fas fa-building"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $deptCount ?></div>
                            <small class="text-secondary fw-semibold">Departments</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #6366f1;">
                        <div class="stat-card-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;"><i class="fas fa-tasks"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $totalTasksCount ?></div>
                            <small class="text-secondary fw-semibold">Total Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #f59e0b;">
                        <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $pendingTasksCount ?></div>
                            <small class="text-secondary fw-semibold">Pending Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #10b981;">
                        <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $completedTasksCount ?></div>
                            <small class="text-secondary fw-semibold">Completed Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #ef4444;">
                        <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;"><i class="fas fa-exclamation-circle"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $overdueTasksCount ?></div>
                            <small class="text-secondary fw-semibold">Overdue Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #06b6d4;">
                        <div class="stat-card-icon" style="background: rgba(6, 182, 212, 0.12); color: #06b6d4;"><i class="fas fa-user-clock"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1"><span class="stat-value"><?= $adminPresentCount ?></span><span class="fs-7 text-muted">/<?= $activeEmpCount ?></span></div>
                            <small class="text-secondary fw-semibold">Attendance Today</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #ec4899;">
                        <div class="stat-card-icon" style="background: rgba(236, 72, 153, 0.12); color: #ec4899;"><i class="fas fa-coins"></i></div>
                        <div>
                            <div class="fs-5 fw-bold text-dark line-height-1 font-monospace">₹<?= number_format($adminPayrollTotal, 0) ?></div>
                            <small class="text-secondary fw-semibold">Monthly Payroll</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #8b5cf6;">
                        <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;"><i class="fas fa-chart-line"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $avgProjectProgress ?>%</div>
                            <small class="text-secondary fw-semibold">Project Progress</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts & Analytics Row -->
            <div class="row g-4 mb-4 align-items-stretch" data-dash-section="tasks">
                <div class="col-lg-8">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-chart-area me-2 text-primary"></i>Executive Output & Task Analytics</h5>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge bg-success-subtle text-success border border-success fs-8"><i class="fas fa-arrow-up me-1"></i>+18.4% Task Velocity</span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary fs-8"><i class="fas fa-bullseye me-1"></i>88% Target Index</span>
                                </div>
                            </div>
                            <div class="d-flex gap-1" id="adminTimeframePills">
                                <button class="timeframe-pill active" onclick="updateAdminChart('weekly')">Weekly</button>
                                <button class="timeframe-pill" onclick="updateAdminChart('monthly')">Monthly</button>
                                <button class="timeframe-pill" onclick="updateAdminChart('yearly')">Yearly</button>
                            </div>
                        </div>
                        <div style="position: relative; height: 280px; width: 100%;">
                            <canvas id="adminExecutiveChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Employee Leaderboard -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-trophy me-2 text-warning"></i>Top Performance Leaderboard</h5>
                            <span class="badge bg-light text-dark border">Monthly</span>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <?php foreach ($leaderboard as $idx => $lead): 
                                $rank = $idx + 1;
                                $rankClass = $rank <= 3 ? "rank-badge-{$rank}" : "rank-badge-other";
                            ?>
                                <div class="leaderboard-item">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="rank-badge <?= $rankClass ?>"><?= $rank ?></div>
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($lead['name']) ?>&background=4f46e5&color=fff" class="rounded-circle" width="36" height="36" alt="Avatar">
                                        <div>
                                            <div class="fw-bold text-dark fs-7 line-height-1"><?= sanitize($lead['name']) ?></div>
                                            <small class="text-muted fs-8"><?= sanitize($lead['dept_name']) ?></small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success fs-7"><?= $lead['completed_tasks'] ?> Tasks</div>
                                        <small class="text-secondary fs-8 font-monospace"><?= formatDuration((int)$lead['total_seconds']) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Feed, Calendar & Quick Action Cards Row -->
            <div class="row g-4 mb-4 align-items-stretch" data-dash-section="activity">
                <!-- Activity Timeline -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="fas fa-stream me-2 text-info"></i>System Audit Timeline</h5>
                        <div class="list-group list-group-flush">
                            <?php foreach ($activities as $act): ?>
                                <div class="list-group-item bg-transparent text-dark border-bottom px-0 py-2">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="fw-bold text-primary"><i class="fas fa-circle me-1 fs-8"></i><?= sanitize($act['user_name'] ?? 'System') ?></small>
                                        <small class="text-muted fs-8"><?= formatISTTime($act['created_at']) ?></small>
                                    </div>
                                    <div class="small text-secondary line-height-1.3"><?= sanitize($act['description']) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Calendar & Upcoming Deadlines Widget -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-calendar-alt me-2 text-danger"></i>Upcoming Deadlines</h5>
                            <a href="<?= BASE_URL ?>/calendar" class="small text-primary text-decoration-none fw-semibold">Calendar</a>
                        </div>
                        <div class="d-flex flex-column gap-2">
                            <?php if ($upcomingDeadlines): foreach ($upcomingDeadlines as $ud): ?>
                                <div class="p-2.5 rounded bg-light border d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold text-dark fs-7 mb-0"><?= sanitize($ud['title']) ?></div>
                                        <small class="text-muted fs-8"><i class="fas fa-user me-1"></i><?= sanitize($ud['assigned_to_name']) ?></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-danger-subtle text-danger border border-danger mb-1 fs-8"><?= getDeadlineCountdown($ud['deadline']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-muted text-center py-4">No upcoming deadlines.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Cards Grid -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="fas fa-bolt me-2 text-warning"></i>Executive Quick Actions</h5>
                        <div class="row g-2 flex-grow-1 align-items-stretch">
                            <div class="col-6">
                                <a href="<?= BASE_URL ?>/employees" class="p-3 border rounded-3 bg-white text-decoration-none d-flex flex-column align-items-center text-center hover-shadow h-100 justify-content-center">
                                    <div class="p-2 bg-primary-subtle text-primary rounded-circle mb-2"><i class="fas fa-user-plus fs-5"></i></div>
                                    <strong class="text-dark fs-7">Add Employee</strong>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="<?= BASE_URL ?>/tasks" class="p-3 border rounded-3 bg-white text-decoration-none d-flex flex-column align-items-center text-center hover-shadow h-100 justify-content-center">
                                    <div class="p-2 bg-info-subtle text-info rounded-circle mb-2"><i class="fas fa-plus-circle fs-5"></i></div>
                                    <strong class="text-dark fs-7">Assign Task</strong>
                                </a>
                            </div>
                            <div class="col-6">
                                <button type="button" class="p-3 border rounded-3 bg-white w-100 text-decoration-none d-flex flex-column align-items-center text-center hover-shadow h-100 justify-content-center" data-bs-toggle="modal" data-bs-target="#generatePayrollModal">
                                    <div class="p-2 bg-warning-subtle text-warning rounded-circle mb-2"><i class="fas fa-calculator fs-5"></i></div>
                                    <strong class="text-dark fs-7">Generate Salary</strong>
                                </button>
                            </div>
                            <div class="col-6">
                                <a href="<?= BASE_URL ?>/projects" class="p-3 border rounded-3 bg-white text-decoration-none d-flex flex-column align-items-center text-center hover-shadow h-100 justify-content-center">
                                    <div class="p-2 bg-success-subtle text-success rounded-circle mb-2"><i class="fas fa-folder-plus fs-5"></i></div>
                                    <strong class="text-dark fs-7">Create Project</strong>
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="<?= BASE_URL ?>/reports" class="p-3 border rounded-3 bg-white text-decoration-none d-flex align-items-center justify-content-center gap-2 hover-shadow h-100">
                                    <i class="fas fa-file-invoice text-primary fs-5"></i>
                                    <strong class="text-dark fs-7">View Analytics & System Reports</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Profiles Roster & Base Salaries Table Panels -->
            <div class="glass-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="fas fa-id-card text-primary me-2"></i>Company Employee Profiles Roster</h5>
                        <p class="text-secondary small mb-0">Direct view of all staff profiles, credentials, roles, and shift assignments.</p>
                    </div>
                    <a href="<?= BASE_URL ?>/employees" class="btn btn-sm btn-primary fw-bold"><i class="fas fa-users-gear me-1"></i>Manage Employees</a>
                </div>

                <div class="row g-3">
                    <?php foreach ($adminEmployeeProfiles as $emp): 
                        $empCode = $emp['employee_code'] ?: ('EMP-' . str_pad($emp['id'], 3, '0', STR_PAD_LEFT));
                        $shift = $emp['shift_timing'] ?: '09:40 AM - 06:30 PM (Fixed Office)';
                    ?>
                        <div class="col-xl-4 col-md-6">
                            <div class="p-3 border rounded bg-white hover-shadow h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($emp['name']) ?>&background=4f46e5&color=fff" class="rounded-circle border border-2 border-primary" width="48" height="48" alt="Avatar">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0"><?= sanitize($emp['name']) ?></h6>
                                            <span class="badge bg-primary-subtle text-primary fs-8 mb-1"><?= sanitize($emp['role_title'] ?? $emp['designation'] ?? 'Staff') ?></span>
                                            <div class="small text-secondary"><?= sanitize($emp['designation']) ?></div>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-1 small text-secondary mb-3">
                                        <div><i class="fas fa-id-card me-2 text-primary"></i>ID: <strong class="font-monospace text-dark"><?= sanitize($empCode) ?></strong></div>
                                        <div><i class="fas fa-building me-2 text-info"></i>Dept: <strong class="text-dark"><?= sanitize($emp['department_name']) ?></strong></div>
                                        <div><i class="fas fa-clock me-2 text-danger"></i>Shift: <span class="font-monospace text-dark"><?= sanitize($shift) ?></span></div>
                                    </div>
                                </div>
                                <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success-subtle text-success border border-success"><i class="fas fa-circle me-1 fs-8"></i>Active</span>
                                    <a href="<?= BASE_URL ?>/profile?id=<?= $emp['id'] ?>" class="btn btn-sm btn-outline-info fw-semibold"><i class="fas fa-user me-1"></i>View Profile</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Payroll Base Salaries Roster Panel -->
            <?php if (!empty($adminSalariesRoster)): ?>
            <div class="glass-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="fas fa-coins text-warning me-2"></i>Employee Base Salaries & Payroll Roster</h5>
                        <p class="text-secondary small mb-0">Base salary configuration and real-time calculated net salaries for current month (<?= date('F Y') ?>).</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-warning fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#generatePayrollModal"><i class="fas fa-calculator me-1"></i>Generate Salary</button>
                        <a href="<?= BASE_URL ?>/payroll" class="btn btn-sm btn-success fw-bold"><i class="fas fa-wallet me-1"></i>Payroll System</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Employee ID</th>
                                <th>Department</th>
                                <th>Base Salary</th>
                                <th>Calculated Net (<?= date('M Y') ?>)</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($adminSalariesRoster as $sal): 
                                $empCode = $sal['employee_code'] ?: ('EMP-' . str_pad($sal['user_id'], 3, '0', STR_PAD_LEFT));
                            ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($sal['name']) ?>&background=4f46e5&color=fff" class="rounded-circle border" width="34" height="34" alt="Avatar">
                                            <div>
                                                <div class="fw-bold text-dark mb-0"><?= sanitize($sal['name']) ?></div>
                                                <div class="small text-secondary"><?= sanitize($sal['designation']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="font-monospace text-primary fw-bold"><?= sanitize($empCode) ?></span></td>
                                    <td><span class="badge bg-light text-dark border"><?= sanitize($sal['department_name']) ?></span></td>
                                    <td class="font-monospace fw-bold text-success">₹<?= number_format($sal['monthly_salary'], 2) ?></td>
                                    <td class="font-monospace fw-bold text-dark">
                                        <?= $sal['net_salary'] !== null ? '₹' . number_format($sal['net_salary'], 2) : '<span class="text-muted small">Not Generated</span>' ?>
                                    </td>
                                    <td>
                                        <?php if ($sal['payment_status'] === 'paid'): ?>
                                            <span class="badge bg-success-subtle text-success border border-success"><i class="fas fa-check-circle me-1"></i>Paid</span>
                                        <?php elseif ($sal['payment_status'] === 'pending'): ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning"><i class="fas fa-clock me-1"></i>Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary">Pending Sync</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openDashboardQuickEditSalary(<?= $sal['user_id'] ?>, '<?= addslashes(sanitize($sal['name'])) ?>', <?= (float)$sal['monthly_salary'] ?>, <?= (float)$sal['allowance'] ?>)"><i class="fas fa-edit me-1"></i>Edit Salary</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

        <?php elseif ($activeRoleView === 'manager'): ?>
            <!-- ================================================================= -->
            <!-- 2. MANAGER DASHBOARD VIEW (TEAM MANAGEMENT UI)                    -->
            <!-- ================================================================= -->

            <?php renderTaskApprovalQueueCard($pendingApprovals); ?>
            <?php renderEmergencyRequestsQueueCard($pendingEmgRequests); ?>
            <?php renderFlaggedTasksQueueCard($openTaskFlags); ?>

            <!-- 7 Statistics Cards for Team Management -->
            <div class="row g-3 mb-4 dashboard-stats-grid align-items-stretch" data-dash-section="stats">
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #0d9488;">
                        <div class="stat-card-icon" style="background: rgba(13, 148, 136, 0.12); color: #0d9488;"><i class="fas fa-users"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $teamMembersCount ?></div>
                            <small class="text-secondary fw-semibold">My Team Members</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #0284c7;">
                        <div class="stat-card-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;"><i class="fas fa-list-check"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $mgrAssignedTasksCount ?></div>
                            <small class="text-secondary fw-semibold">Team Assigned Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #10b981;">
                        <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-check-double"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $mgrCompletedTasksCount ?></div>
                            <small class="text-secondary fw-semibold">Completed Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #f59e0b;">
                        <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-hourglass-half"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $mgrPendingTasksCount ?></div>
                            <small class="text-secondary fw-semibold">Pending Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #ef4444;">
                        <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $mgrOverdueTasksCount ?></div>
                            <small class="text-secondary fw-semibold">Overdue Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #06b6d4;">
                        <div class="stat-card-icon" style="background: rgba(6, 182, 212, 0.12); color: #06b6d4;"><i class="fas fa-user-clock"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1"><span class="stat-value"><?= $mgrTeamPresentCount ?></span><span class="fs-7 text-muted">/<?= $teamMembersCount ?></span></div>
                            <small class="text-secondary fw-semibold">Team Attendance</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #8b5cf6;">
                        <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;"><i class="fas fa-chart-pie"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $mgrTeamPerfScore ?>%</div>
                            <small class="text-secondary fw-semibold">Team Performance</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanban Preview & Team Velocity Row -->
            <div class="row g-4 mb-4 align-items-stretch" data-dash-section="tasks">
                <!-- Kanban Board Preview -->
                <div class="col-lg-8">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-columns me-2 text-teal"></i>Team Kanban Board Preview</h5>
                                <small class="text-muted">Live snapshot of task status distribution across team members.</small>
                            </div>
                            <a href="<?= BASE_URL ?>/kanban" class="btn btn-sm btn-outline-primary fw-bold"><i class="fas fa-external-link-alt me-1"></i>Full Board</a>
                        </div>

                        <div class="kanban-preview-grid">
                            <?php 
                            $statusLabels = [
                                'pending' => ['Pending', 'warning'],
                                'running' => ['Running', 'info'],
                                'in_review' => ['In Review', 'primary'],
                                'completed' => ['Completed', 'success']
                            ];
                            foreach ($statusLabels as $sKey => [$sTitle, $sColor]): 
                                $cTasks = $kanbanCols[$sKey] ?? [];
                            ?>
                                <div class="kanban-preview-col">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold fs-8 text-uppercase text-dark"><i class="fas fa-circle me-1 text-<?= $sColor ?> fs-9"></i><?= $sTitle ?></span>
                                        <span class="badge bg-<?= $sColor ?>-subtle text-<?= $sColor ?> fs-8"><?= count($cTasks) ?></span>
                                    </div>
                                    <?php if ($cTasks): foreach (array_slice($cTasks, 0, 3) as $kt): ?>
                                        <div class="kanban-preview-card">
                                            <div class="fw-semibold text-dark line-clamp-1 mb-1"><?= sanitize($kt['title']) ?></div>
                                            <div class="d-flex justify-content-between align-items-center fs-8 text-secondary">
                                                <span><i class="fas fa-user me-1 text-primary"></i><?= sanitize($kt['assigned_to_name']) ?></span>
                                                <?= getPriorityBadge($kt['priority']) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; else: ?>
                                        <div class="text-muted fs-8 text-center py-3">Empty</div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Team Velocity Chart -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="fas fa-chart-line me-2 text-primary"></i>Team Progress Velocity</h5>
                        <canvas id="mgrTeamVelocityChart" height="220"></canvas>
                    </div>
                </div>
            </div>

            <!-- Employee Workload Overview & Team Activity Row -->
            <div class="row g-4 mb-4 align-items-stretch" data-dash-section="activity">
                <!-- Employee Workload Overview -->
                <div class="col-lg-6">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-sliders me-2 text-info"></i>Employee Workload Overview</h5>
                            <span class="badge bg-light text-dark border">Capacity Control</span>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <?php if ($mgrWorkloadList): foreach ($mgrWorkloadList as $mw): 
                                $actCount = (int)$mw['active_tasks'];
                                $loadPct = min(100, $actCount * 25);
                                $barColor = $actCount > 3 ? 'bg-danger' : ($actCount > 1 ? 'bg-warning' : 'bg-success');
                                $statusTxt = $actCount > 3 ? 'Heavy Load' : ($actCount > 1 ? 'Balanced' : 'Light Load');
                            ?>
                                <div class="workload-item">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($mw['name']) ?>&background=0d9488&color=fff" class="rounded-circle" width="32" height="32" alt="Avatar">
                                            <div>
                                                <div class="fw-bold text-dark fs-7 line-height-1"><?= sanitize($mw['name']) ?></div>
                                                <small class="text-muted fs-8"><?= sanitize($mw['designation']) ?></small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-light text-dark border fs-8 me-1"><?= $actCount ?> Active Tasks</span>
                                            <small class="fw-bold fs-8 <?= $actCount > 3 ? 'text-danger' : 'text-success' ?>"><?= $statusTxt ?></small>
                                        </div>
                                    </div>
                                    <div class="workload-progress">
                                        <div class="progress-bar <?= $barColor ?>" style="width: <?= $loadPct ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-muted text-center py-4">No workload data available.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Today's Priorities & Priority/Status Graphs -->
                <div class="col-lg-6">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-fire me-2 text-danger"></i>Today's High Priorities</h5>
                                <small class="text-muted">Critical deliverables & priority breakdown.</small>
                            </div>
                            <a href="<?= BASE_URL ?>/tasks" class="small text-primary text-decoration-none fw-semibold">View All</a>
                        </div>

                        <!-- Side-by-side Mini Priority Chart + Task List -->
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-sm-5 col-12 text-center border-end-sm">
                                <div class="p-2 bg-light rounded border mb-1" style="position: relative; max-height: 105px;">
                                    <canvas id="mgrPriorityMiniChart" height="90" style="max-height: 100px;"></canvas>
                                </div>
                                <span class="fs-8 text-secondary fw-semibold"><i class="fas fa-chart-pie me-1 text-danger"></i>Priority Split</span>
                            </div>

                            <div class="col-sm-7 col-12">
                                <div class="table-responsive" style="max-height: 170px; overflow-y: auto;">
                                    <table class="table align-middle table-sm mb-0">
                                        <thead class="table-light fs-8">
                                            <tr>
                                                <th>Priority Task</th>
                                                <th>Level</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($mgrKanbanTasks): foreach (array_slice($mgrKanbanTasks, 0, 4) as $pt): ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold text-dark fs-8 text-truncate" style="max-width: 140px;"><?= sanitize($pt['title']) ?></div>
                                                        <small class="text-muted fs-9"><i class="fas fa-user me-1"></i><?= sanitize($pt['assigned_to_name']) ?></small>
                                                    </td>
                                                    <td><?= getPriorityBadge($pt['priority']) ?></td>
                                                </tr>
                                            <?php endforeach; else: ?>
                                                <tr><td colspan="2" class="text-center text-muted py-3 fs-8">No priority tasks right now.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr class="my-2 border-secondary">
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0 fs-7"><i class="fas fa-tasks me-1 text-primary"></i>Task Distribution by Status</h6>
                            <span class="badge bg-light text-dark border fs-8"><?= $mgrAssignedTasksCount ?> Total Tasks</span>
                        </div>
                        <canvas id="mgrTaskDistChart" height="120"></canvas>
                    </div>
                </div>
            </div>

        <?php elseif ($activeRoleView === 'product_manager'): ?>
            <!-- ================================================================= -->
            <!-- 3. PRODUCT MANAGER DASHBOARD VIEW (SPRINT & PRODUCT ROADMAPS)     -->
            <!-- ================================================================= -->

            <?php renderTaskApprovalQueueCard($pendingApprovals); ?>
            <?php renderEmergencyRequestsQueueCard($pendingEmgRequests); ?>
            <?php renderFlaggedTasksQueueCard($openTaskFlags); ?>

            <!-- 7 Statistics Cards for Product & Sprint Management -->
            <div class="row g-3 mb-4 dashboard-stats-grid align-items-stretch" data-dash-section="stats">
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #D97706;">
                        <div class="stat-card-icon" style="background: rgba(217, 119, 6, 0.12); color: #D97706;"><i class="fas fa-cubes"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $pmTotalProjects ?></div>
                            <small class="text-secondary fw-semibold">Total Projects / Sprints</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #2563eb;">
                        <div class="stat-card-icon" style="background: rgba(37, 99, 235, 0.12); color: #2563eb;"><i class="fas fa-play-circle"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $pmActiveProjects ?></div>
                            <small class="text-secondary fw-semibold">Active Product Sprints</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #4F46E5;">
                        <div class="stat-card-icon" style="background: rgba(79, 70, 229, 0.12); color: #4F46E5;"><i class="fas fa-list-check"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $pmTotalTasks ?></div>
                            <small class="text-secondary fw-semibold">Total Feature Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #f59e0b;">
                        <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-hourglass-half"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $pmPendingTasks ?></div>
                            <small class="text-secondary fw-semibold">Sprint Backlog Queue</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #06B6D4;">
                        <div class="stat-card-icon" style="background: rgba(6, 182, 212, 0.12); color: #06B6D4;"><i class="fas fa-spinner"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $pmRunningTasks ?></div>
                            <small class="text-secondary fw-semibold">In Development</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #10B981;">
                        <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10B981;"><i class="fas fa-check-double"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $pmCompletedTasks ?></div>
                            <small class="text-secondary fw-semibold">Completed Releases</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #8B5CF6;">
                        <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.12); color: #8B5CF6;"><i class="fas fa-chart-pie"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $pmTotalTasks > 0 ? round(($pmCompletedTasks / $pmTotalTasks) * 100) : 0 ?>%</div>
                            <small class="text-secondary fw-semibold">Feature Release Rate</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Sprints & Roadmaps Table Row -->
            <div class="row g-4 mb-4 align-items-stretch" data-dash-section="tasks">
                <div class="col-lg-8">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-cubes me-2 text-warning"></i>Product Roadmaps & Sprint Slices</h5>
                                <small class="text-muted">Live status of active development sprints and product features.</small>
                            </div>
                            <a href="<?= BASE_URL ?>/projects" class="btn btn-sm btn-outline-warning text-dark fw-bold"><i class="fas fa-external-link-alt me-1"></i>All Projects</a>
                        </div>
                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle table-hover mb-0">
                                <thead class="table-light fs-8">
                                    <tr>
                                        <th>Project / Sprint</th>
                                        <th>Department</th>
                                        <th>Priority</th>
                                        <th>Progress</th>
                                        <th>Tasks</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($pmProjectsList): foreach ($pmProjectsList as $prj): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-dark fs-7"><?= sanitize($prj['name']) ?></div>
                                                <small class="text-muted fs-8 font-monospace"><?= sanitize($prj['code']) ?></small>
                                            </td>
                                            <td><span class="badge bg-light text-dark border"><?= sanitize($prj['department_name'] ?? 'General') ?></span></td>
                                            <td><?= getPriorityBadge($prj['priority']) ?></td>
                                            <td style="min-width: 140px;">
                                                <div class="d-flex justify-content-between fs-8 fw-semibold mb-1">
                                                    <span>Progress</span>
                                                    <span><?= (int)$prj['progress'] ?>%</span>
                                                </div>
                                                <div class="progress" style="height: 6px;">
                                                    <div class="progress-bar bg-warning" style="width: <?= (int)$prj['progress'] ?>%"></div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-primary-subtle text-primary"><?= (int)$prj['completed_task_count'] ?> / <?= (int)$prj['task_count'] ?></span></td>
                                            <td><span class="badge bg-success-subtle text-success border border-success"><?= ucfirst($prj['status']) ?></span></td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="6" class="text-center text-muted py-4">No active projects found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View (< 768px App View) -->
                        <div class="mobile-card-list d-block d-md-none">
                            <?php if ($pmProjectsList): foreach ($pmProjectsList as $prj): ?>
                                <div class="p-3 bg-white border rounded-3 mb-2.5 shadow-xs">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-1.5">
                                        <div>
                                            <div class="fw-bold text-dark fs-7"><?= sanitize($prj['name']) ?></div>
                                            <small class="text-muted fs-8 font-monospace"><?= sanitize($prj['code']) ?></small>
                                        </div>
                                        <?= getPriorityBadge($prj['priority']) ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center fs-8 text-secondary mb-2">
                                        <span><i class="fas fa-building me-1"></i><?= sanitize($prj['department_name'] ?? 'General') ?></span>
                                        <span class="badge bg-primary-subtle text-primary"><?= (int)$prj['completed_task_count'] ?> / <?= (int)$prj['task_count'] ?> tasks</span>
                                    </div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between fs-8 fw-semibold mb-1">
                                            <span>Progress</span>
                                            <span><?= (int)$prj['progress'] ?>%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: <?= (int)$prj['progress'] ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="badge bg-success-subtle text-success border border-success"><?= ucfirst($prj['status']) ?></span>
                                        <a href="<?= BASE_URL ?>/projects" class="btn btn-xs btn-outline-warning text-dark fw-bold rounded-pill px-2.5">
                                            View Sprint <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-center text-muted py-4 bg-light rounded-3 border">No active projects found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-fire me-2 text-danger"></i>Sprint Deliverables</h5>
                            <a href="<?= BASE_URL ?>/kanban" class="small text-primary text-decoration-none fw-semibold">Kanban</a>
                        </div>
                        <div class="d-flex flex-column gap-2.5">
                            <?php if ($pmSprintTasks): foreach ($pmSprintTasks as $sTask): ?>
                                <div class="p-2.5 bg-light rounded border d-flex justify-content-between align-items-center">
                                    <div class="overflow-hidden me-2">
                                        <div class="fw-bold text-dark fs-8 text-truncate"><?= sanitize($sTask['title']) ?></div>
                                        <small class="text-secondary fs-9 d-block text-truncate">
                                            <i class="fas fa-user me-1 text-primary"></i><?= sanitize($sTask['assigned_to_name'] ?? 'Unassigned') ?> • <?= sanitize($sTask['project_name'] ?? 'General') ?>
                                        </small>
                                    </div>
                                    <div class="text-end flex-shrink-0">
                                        <?= getPriorityBadge($sTask['priority']) ?>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-muted text-center py-4 fs-8">All sprint tasks completed!</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Documentation & Asset Files Row -->
            <div class="row g-4 mb-4" data-dash-section="activity">
                <div class="col-12">
                    <div class="glass-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-folder-open me-2 text-primary"></i>Product Assets & Task Attachments</h5>
                            <a href="<?= BASE_URL ?>/files" class="btn btn-sm btn-outline-primary"><i class="fas fa-folder me-1"></i>File Vault</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light fs-8">
                                    <tr>
                                        <th>File Name</th>
                                        <th>Uploaded By</th>
                                        <th>Linked Project</th>
                                        <th>File Size</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($pmRecentFiles): foreach ($pmRecentFiles as $pfile): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-file-alt text-primary fs-5"></i>
                                                    <div>
                                                        <span class="fw-semibold text-dark fs-8"><?= sanitize($pfile['file_name']) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="fs-8 text-secondary"><?= sanitize($pfile['uploader_name']) ?></span></td>
                                            <td><span class="badge bg-light text-dark border"><?= sanitize($pfile['project_name'] ?? 'Task Asset') ?></span></td>
                                            <td><span class="fs-8 font-monospace"><?= round(($pfile['file_size'] ?? 1024) / 1024, 1) ?> KB</span></td>
                                            <td><span class="fs-8 text-muted"><?= date('d M Y', strtotime($pfile['created_at'])) ?></span></td>
                                            <td>
                                                <a href="<?= BASE_URL . '/' . sanitize($pfile['file_path']) ?>" download class="btn btn-sm btn-light border py-0.5 px-2 fs-8 text-primary">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="6" class="text-center text-muted py-3 fs-8">No uploaded product files yet.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- ================================================================= -->
            <!-- 3. EMPLOYEE DASHBOARD VIEW (PERSONAL PRODUCTIVITY WORKSPACE)      -->
            <!-- ================================================================= -->

            <!-- ========================================================= -->
            <!-- HIGH-PRIORITY MEETING INVITATION BANNER (If Pending)     -->
            <!-- ========================================================= -->
            <div id="dashboardPendingMeetingBanner" class="alert bg-white border border-warning shadow-sm rounded-4 p-3.5 mb-4 position-relative overflow-hidden" 
                 data-meeting-id="<?= $dashPendingMeetingAlert['id'] ?? '' ?>"
                 style="border-left: 6px solid #f59e0b !important; <?= $dashPendingMeetingAlert ? '' : 'display: none;' ?>">
                <?php
                $dpCallerName = $dashPendingMeetingAlert['caller_name'] ?? 'Boss / Admin';
                $dpCallerAvatar = $dashPendingMeetingAlert['caller_avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($dpCallerName) . '&background=f59e0b&color=fff');
                ?>
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="position-relative flex-shrink-0">
                            <img src="<?= $dpCallerAvatar ?>" id="dashPendingBannerAvatar" class="rounded-circle border shadow-sm" width="50" height="50" alt="Caller">
                            <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning p-1 border border-white">
                                <i class="fas fa-video fs-9 text-dark"></i>
                            </span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill fs-8">
                                    <i class="fas fa-bell me-1 animate-bounce"></i>ACTION REQUIRED: MEETING INVITATION
                                </span>
                                <span class="text-secondary fs-8">From: <strong class="text-dark" id="dashPendingBannerCaller"><?= sanitize($dpCallerName) ?></strong></span>
                                <span class="badge bg-info-subtle text-info border px-2 py-0.5 rounded-pill fs-8" id="dashPendingBannerClientBadge" style="<?= empty($dashPendingMeetingAlert['client_name']) ? 'display: none;' : '' ?>">
                                    <i class="fas fa-handshake me-1"></i>Client: <span id="dashPendingBannerClientText"><?= htmlspecialchars($dashPendingMeetingAlert['client_name'] ?? '') ?></span>
                                </span>
                            </div>
                            <h5 class="fw-bold text-dark mb-1" id="dashPendingBannerTitle"><?= htmlspecialchars($dashPendingMeetingAlert['title'] ?? 'Meeting Discussion') ?></h5>
                            <div class="d-flex align-items-center gap-3 text-secondary small flex-wrap" id="dashPendingBannerMeta">
                                <span><i class="fas fa-clock text-primary me-1"></i><span id="dashPendingBannerTime"><?= $dashPendingMeetingAlert['start_time'] ?? '--' ?></span> (<span id="dashPendingBannerDuration"><?= $dashPendingMeetingAlert['duration'] ?? 30 ?></span> mins)</span>
                                <?php if (!empty($dashPendingMeetingAlert['project_name'])): ?>
                                    <span><i class="fas fa-folder text-warning me-1"></i><span id="dashPendingBannerProject"><?= htmlspecialchars($dashPendingMeetingAlert['project_name']) ?></span></span>
                                <?php endif; ?>
                                <?php if (!empty($dashPendingMeetingAlert['meeting_link'])): ?>
                                    <a href="<?= htmlspecialchars($dashPendingMeetingAlert['meeting_link']) ?>" target="_blank" id="dashPendingBannerLink" class="text-primary fw-semibold text-truncate" style="max-width: 250px;">
                                        <i class="fas fa-link me-1"></i><?= htmlspecialchars($dashPendingMeetingAlert['meeting_link']) ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($dashPendingMeetingAlert['description'])): ?>
                                <div class="mt-1 text-muted fs-8" id="dashPendingBannerDesc">
                                    <i class="fas fa-align-left me-1"></i><?= htmlspecialchars($dashPendingMeetingAlert['description']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-lg-auto justify-content-lg-end">
                        <button type="button" class="btn btn-success btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm" onclick="respondToDashPendingMeeting('accept')">
                            <i class="fas fa-check me-1"></i>Accept & Join
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm fw-semibold px-3 py-2 rounded-pill" onclick="editDashCurrentPendingMeeting()">
                            <i class="fas fa-pen me-1"></i>Edit Request
                        </button>
                        <button type="button" class="btn btn-outline-warning text-dark btn-sm fw-semibold px-3 py-2 rounded-pill" onclick="openDashBusyModal()">
                            <i class="fas fa-clock me-1"></i>I'm Busy
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm fw-semibold px-3 py-2 rounded-pill" onclick="respondToDashPendingMeeting('decline')">
                            Decline
                        </button>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- SECTION: OFFICE SHIFT SCHEDULE & LIVE STATUS HERO         -->
            <!-- ========================================================= -->
            <div class="glass-card mb-4 p-3.5 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.98) 100%);" data-dash-section="stats">
                <div class="row g-3 align-items-stretch">
                    <!-- Shift Timings Pill -->
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="p-3 bg-white rounded-3 border h-100 shadow-xs d-flex flex-column justify-content-between">
                            <div>
                                <small class="text-uppercase tracking-wider text-muted fw-bold fs-9 d-block mb-1">
                                    <i class="fas fa-calendar-day text-primary me-1"></i>Office Shift Schedule
                                </small>
                                <div class="fw-bold text-dark fs-6 line-height-1 mb-1">
                                    <?= $fmtShiftStart ?> &ndash; <?= $fmtShiftEnd ?>
                                </div>
                                <small class="text-secondary fs-8 d-block mb-1">
                                    <i class="fas fa-utensils text-warning me-1"></i>Lunch: <?= $fmtLunchStart ?> &ndash; <?= $fmtLunchEnd ?>
                                </small>
                            </div>
                            <small class="text-muted fs-9 d-block pt-1 border-top border-light-subtle">
                                <i class="fas fa-business-time me-1 text-primary"></i>Working: 9:40-2:00 | 3:00-6:30
                            </small>
                        </div>
                    </div>

                    <!-- Live Shift Status Badge Card -->
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="p-3 bg-white rounded-3 border h-100 shadow-xs d-flex flex-column justify-content-between">
                            <div>
                                <small class="text-uppercase tracking-wider text-muted fw-bold fs-9 d-block mb-1">
                                    Current Status
                                </small>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fs-4"><?= $statusDetails['status_emoji'] ?></span>
                                    <div>
                                        <div class="fw-bold text-dark fs-6" id="dashWorkdayStatusDisplay">
                                            <?= $statusDetails['status_display'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-1">
                                <span class="badge <?= $statusDetails['status_badge_class'] ?> rounded-pill fs-9 px-2.5 py-1" id="dashWorkdayStatusBadge">
                                    <?= $statusDetails['status_key'] ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Current Task & Activity Snapshot -->
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="p-3 bg-white rounded-3 border h-100 shadow-xs d-flex flex-column justify-content-between">
                            <div>
                                <small class="text-uppercase tracking-wider text-muted fw-bold fs-9 d-block mb-1">
                                    <i class="fas fa-tasks text-info me-1"></i>Current Task / Project
                                </small>
                                <div class="fw-bold text-dark fs-7 text-truncate mb-0" id="dashWorkdayCurrentTaskDisplay" title="<?= sanitize($currentAct['task_title'] ?? 'No task active') ?>">
                                    <?= sanitize($currentAct['task_title'] ?? ($currentAct['activity_type'] ?? 'No active task')) ?>
                                </div>
                            </div>
                            <small class="text-muted fs-8 d-block text-truncate pt-1 border-top border-light-subtle" id="dashWorkdayCurrentProjectDisplay">
                                <i class="fas fa-folder me-1 text-secondary"></i><?= sanitize($currentAct['project_name'] ?? 'General Agency Work') ?>
                            </small>
                        </div>
                    </div>

                    <!-- Shift Action Card (Wi-Fi Restricted, Mobile & Desktop Responsive) -->
                    <div class="col-12 col-md-6 col-xl-3 d-flex wifi-restricted-card">
                        <!-- Unauthorized Network Fallback Notice -->
                        <div class="wifi-unauth-notice p-3 bg-white rounded-3 border h-100 shadow-xs d-flex flex-column justify-content-center text-center w-100 <?= $isClientIpAuth ? 'd-none' : 'd-flex' ?>"
                             style="<?= $isClientIpAuth ? 'display:none !important;' : '' ?>">
                            <span class="badge bg-danger-subtle text-danger border border-danger p-2 mb-1">
                                <i class="fas fa-wifi-slash me-1"></i>Office Wi-Fi Required
                            </span>
                            <small class="text-muted fs-9">Connect to authorized office Wi-Fi network to start or end your shift.</small>
                        </div>

                        <!-- Authorized Shift Action Buttons -->
                        <div class="p-3 bg-white rounded-3 border h-100 shadow-xs d-flex flex-column justify-content-center w-100 wifi-restricted-btn <?= $isClientIpAuth ? 'd-flex' : 'd-none' ?>"
                             style="<?= $isClientIpAuth ? '' : 'display:none !important;' ?>">
                            <?php if (!$isShiftActive && !$isShiftEnded): ?>
                                <button type="button" class="btn btn-primary btn-lg w-100 py-2.5 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 shift-toggle-btn" onclick="handleClockInOut('clock_in')">
                                    <i class="fas fa-play fs-5"></i>
                                    <span class="fs-6">Start Shift</span>
                                </button>
                                <small class="text-muted fs-9 d-block text-center mt-1">Shift Start: <?= $fmtShiftStart ?></small>
                            <?php elseif ($isShiftActive): ?>
                                <div class="d-flex flex-column gap-2">
                                    <button type="button" class="btn btn-success btn-lg w-100 py-2 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" disabled>
                                        <i class="fas fa-check-circle fs-5"></i>
                                        <span class="fs-6">Shift Running ✓</span>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm fw-semibold rounded-pill py-1 shift-toggle-btn" onclick="handleClockInOut('clock_out')">
                                        <i class="fas fa-stop me-1"></i>End Shift Today
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="d-flex flex-column gap-2">
                                    <button type="button" class="btn btn-primary btn-lg w-100 py-2 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 shift-toggle-btn" onclick="handleClockInOut('clock_in')">
                                        <i class="fas fa-play fs-5"></i>
                                        <span class="fs-6">Resume / Start Shift</span>
                                    </button>
                                    <small class="text-success fs-9 d-block text-center"><i class="fas fa-check-double me-1"></i>Worked <?= formatDuration((int)($myAttendance['total_seconds'] ?? 0)) ?> earlier today</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Late Arrival Notice if clocked in late -->
                <?php if ($isLate): ?>
                    <div class="mt-3 p-2.5 bg-warning-subtle border border-warning rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock text-warning fs-5"></i>
                            <div>
                                <strong class="text-dark fs-7">Late Arrival Recorded:</strong>
                                <span class="text-secondary small">Shift start was <strong><?= $fmtShiftStart ?></strong>. Your login was at <strong><?= $clockInFormatted ?></strong>.</span>
                            </div>
                        </div>
                        <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 fw-bold fs-8">
                            Late By <?= $lateMinutes ?> Minutes
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 8 Statistics Cards for Employee -->
            <div class="row g-3 mb-4 dashboard-stats-grid align-items-stretch" data-dash-section="stats">
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #2563eb;">
                        <div class="stat-card-icon" style="background: rgba(37, 99, 235, 0.12); color: #2563eb;"><i class="fas fa-tasks"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $empMyTasksCount ?></div>
                            <small class="text-secondary fw-semibold">My Total Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #3b82f6;">
                        <div class="stat-card-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;"><i class="fas fa-play-circle"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $empInProgressCount ?></div>
                            <small class="text-secondary fw-semibold">In Progress</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #10b981;">
                        <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-check-circle"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $empCompletedCount ?></div>
                            <small class="text-secondary fw-semibold">Completed Tasks</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #f59e0b;">
                        <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-clock"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $empPendingCount ?></div>
                            <small class="text-secondary fw-semibold">Pending Queue</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #6366f1;">
                        <div class="stat-card-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;"><i class="fas fa-stopwatch"></i></div>
                        <div>
                            <div class="fs-5 fw-bold text-dark line-height-1 font-monospace"><?= formatDuration($shiftElapsedSecs) ?></div>
                            <small class="text-secondary fw-semibold">Today's Working Time</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #8b5cf6;">
                        <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;"><i class="fas fa-calendar-week"></i></div>
                        <div>
                            <div class="fs-5 fw-bold text-dark line-height-1 font-monospace"><?= formatDuration($myWeekTotalSecs) ?></div>
                            <small class="text-secondary fw-semibold">This Week Hours</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #06b6d4;">
                        <div class="stat-card-icon" style="background: rgba(6, 182, 212, 0.12); color: #06b6d4;"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1"><span class="stat-value"><?= $myMonthPresentDays ?></span><span class="fs-7 text-muted"> Days</span></div>
                            <small class="text-secondary fw-semibold">Attendance (Month)</small>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-3">
                    <div class="stat-card-luxury" style="--card-accent: #ef4444;">
                        <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;"><i class="fas fa-bell"></i></div>
                        <div>
                            <div class="fs-4 fw-bold text-dark line-height-1 stat-value"><?= $empUpcomingDeadlinesCount ?></div>
                            <small class="text-secondary fw-semibold">Upcoming Deadlines</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- SECTION: TODAY'S MEETINGS & SYNCS (Full Responsive View)  -->
            <!-- ========================================================= -->
            <div class="glass-card mb-4 border-0 shadow-sm p-4" id="dashMeetingsCard" data-dash-section="activity">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0 font-heading d-flex align-items-center gap-2">
                            <i class="fas fa-video text-purple"></i>Today's Meetings & Syncs
                            <span class="badge bg-purple-subtle text-purple border border-purple px-2 py-0.5 rounded-pill fs-8 fw-semibold" id="dashMeetingsCountBadge">
                                <?= count($dashMeetings) ?> Scheduled
                            </span>
                        </h5>
                        <small class="text-secondary fs-8">Team discussions, client calls, and boss meetings. Meeting time counts as active productive work.</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-purple btn-sm rounded-pill px-3 fw-bold shadow-sm" onclick="openDashScheduleMeetingModal()">
                            <i class="fas fa-plus me-1"></i>+ Request / Schedule Meeting
                        </button>
                        <button type="button" class="btn btn-light border btn-sm rounded-pill px-2.5" onclick="checkDashboardMeetingRequest(true)" title="Refresh Meetings">
                            <i class="fas fa-sync-alt text-secondary"></i>
                        </button>
                    </div>
                </div>

                <div id="dashMeetingsListContainer">
                    <?php if (empty($dashMeetings)): ?>
                        <div class="p-3.5 bg-light rounded-3 text-center border">
                            <i class="fas fa-calendar-check text-muted fs-4 mb-2 d-block"></i>
                            <div class="fw-semibold text-dark fs-7">No meetings scheduled for today</div>
                            <small class="text-muted fs-8">You have no upcoming meeting requests. You can request a sync with your boss or schedule a client call anytime.</small>
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-purple btn-sm rounded-pill px-3" onclick="openDashScheduleMeetingModal()">
                                    <i class="fas fa-plus me-1"></i>+ Request / Schedule Meeting
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($dashMeetings as $m): 
                                $isPending = ($m['status'] === 'pending');
                                $isAccepted = ($m['status'] === 'accepted');
                                $isInProgress = ($m['status'] === 'in_progress');
                                $isCaller = ((int)$m['created_by'] === $userId);
                                $isRecipient = ((int)$m['employee_id'] === $userId || !$isCaller);
                                
                                $statusBadgeClass = 'bg-secondary';
                                $statusLabel = ucfirst($m['status']);
                                if ($isPending) {
                                    $statusBadgeClass = 'bg-warning text-dark';
                                    $statusLabel = 'Pending Response';
                                } elseif ($isAccepted) {
                                    $statusBadgeClass = 'bg-purple text-white';
                                    $statusLabel = 'Scheduled';
                                } elseif ($isInProgress) {
                                    $statusBadgeClass = 'bg-success text-white';
                                    $statusLabel = 'In Meeting';
                                } elseif ($m['status'] === 'completed') {
                                    $statusBadgeClass = 'bg-info text-dark';
                                    $statusLabel = 'Completed';
                                } elseif ($m['status'] === 'declined' || $m['status'] === 'busy') {
                                    $statusBadgeClass = 'bg-secondary text-white';
                                    $statusLabel = ucfirst($m['status']);
                                }

                                $otherPartyName = $isCaller ? ($m['employee_name'] ?? 'Team') : ($m['caller_name'] ?? 'Boss');
                                $otherPartyRole = $isCaller ? ($m['employee_designation'] ?? 'Employee') : ($m['caller_designation'] ?? 'Manager');
                                $avatarSrc = $isCaller 
                                    ? ($m['employee_avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($otherPartyName) . '&background=4f46e5&color=fff'))
                                    : ($m['caller_avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($otherPartyName) . '&background=7c3aed&color=fff'));
                            ?>
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="p-3 rounded-3 border bg-white shadow-sm h-100 d-flex flex-column justify-content-between position-relative">
                                        <div>
                                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                                <span class="badge <?= $statusBadgeClass ?> px-2.5 py-1 rounded-pill fs-8 fw-semibold">
                                                    <?= $statusLabel ?>
                                                </span>
                                                <span class="font-monospace fs-8 fw-bold text-primary">
                                                    <i class="fas fa-clock me-1"></i><?= $m['start_time'] ?> (<?= $m['duration'] ?>m)
                                                </span>
                                            </div>

                                            <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($m['title']) ?>">
                                                <?= htmlspecialchars($m['title']) ?>
                                            </h6>

                                            <div class="d-flex align-items-center gap-1.5 mb-2 flex-wrap">
                                                <?php if (!empty($m['meeting_type']) && $m['meeting_type'] === 'client'): ?>
                                                    <span class="badge bg-info-subtle text-info border px-2 py-0.5 rounded-pill fs-9">
                                                        <i class="fas fa-handshake me-1"></i>Client Call
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($m['client_name'])): ?>
                                                    <span class="badge bg-light text-dark border px-2 py-0.5 rounded-pill fs-9">
                                                        Client: <?= htmlspecialchars($m['client_name']) ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($m['project_name'])): ?>
                                                    <span class="badge bg-light text-secondary border px-2 py-0.5 rounded-pill fs-9">
                                                        <?= htmlspecialchars($m['project_name']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <img src="<?= $avatarSrc ?>" class="rounded-circle border" width="28" height="28" alt="<?= htmlspecialchars($otherPartyName) ?>">
                                                <div class="text-truncate">
                                                    <small class="text-dark fw-bold d-block fs-8"><?= sanitize($otherPartyName) ?></small>
                                                    <small class="text-muted fs-9"><?= $isCaller ? 'Invited Attendee' : 'Host / Organizer' ?></small>
                                                </div>
                                            </div>

                                            <?php if (!empty($m['meeting_link'])): ?>
                                                <div class="mb-2">
                                                    <a href="<?= htmlspecialchars($m['meeting_link']) ?>" target="_blank" class="btn btn-outline-primary btn-sm py-1 px-2.5 rounded-pill w-100 text-truncate fs-8">
                                                        <i class="fas fa-video me-1"></i>Join Video Call
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($m['description'])): ?>
                                                <p class="text-muted fs-8 mb-2 text-truncate" title="<?= htmlspecialchars($m['description']) ?>">
                                                    <?= htmlspecialchars($m['description']) ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if (!empty($m['response_notes'])): ?>
                                                <div class="p-1.5 bg-light rounded text-secondary fs-9 mb-2 border">
                                                    <strong>Note:</strong> <?= htmlspecialchars($m['response_notes']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="d-flex align-items-center gap-1.5 pt-2 border-top mt-2 justify-content-between flex-wrap">
                                            <button type="button" class="btn btn-light btn-sm text-primary fw-semibold px-2.5 py-1 rounded-pill border fs-8" 
                                                    onclick="openDashEditMeetingModal(<?= (int)$m['id'] ?>)">
                                                <i class="fas fa-pen me-1"></i>Edit Request
                                            </button>

                                            <?php if ($isPending && $isRecipient): ?>
                                                <div class="d-flex gap-1">
                                                    <button type="button" class="btn btn-success btn-sm px-2.5 py-1 rounded-pill fw-bold fs-8" 
                                                            onclick="respondToDashMeetingById(<?= $m['id'] ?>, 'accept')">
                                                        Accept
                                                    </button>
                                                    <button type="button" class="btn btn-outline-warning text-dark btn-sm px-2 py-1 rounded-pill fs-8" 
                                                            onclick="respondToDashMeetingById(<?= $m['id'] ?>, 'busy')">
                                                        Busy
                                                    </button>
                                                </div>
                                            <?php elseif ($isAccepted || $isInProgress): ?>
                                                <?php if ($isInProgress): ?>
                                                    <button type="button" class="btn btn-danger btn-sm px-2.5 py-1 rounded-pill fw-bold fs-8" onclick="endDashCurrentMeeting(<?= $m['id'] ?>)">
                                                        End Meeting
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-purple btn-sm px-2.5 py-1 rounded-pill fw-bold text-white fs-8" onclick="startDashMeetingActivity(<?= $m['id'] ?>, '<?= addslashes($m['title']) ?>')">
                                                        Start Meeting
                                                    </button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- ROW: ACTIVITY SWITCHER & LIVE TIMER + PRODUCTIVITY GRAPH -->
            <!-- ========================================================= -->
            <div class="row g-4 mb-4 align-items-stretch" data-dash-section="activity">
                <!-- What are you doing now? (Activity Switcher) -->
                <div class="col-lg-7">
                    <div class="glass-card h-100 p-4 border-0 shadow-sm d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0 font-heading">
                                        <i class="fas fa-bolt text-warning me-2"></i>What are you doing now?
                                    </h5>
                                    <small class="text-secondary fs-8">Select your current activity. Previous activity is automatically closed and saved.</small>
                                </div>
                                <span class="badge bg-light text-secondary border px-2.5 py-1 fs-8">
                                    <i class="fas fa-sync-alt me-1 text-primary"></i>Live Tracking
                                </span>
                            </div>

                            <!-- Current Active Indicator Banner -->
                            <div class="p-3 rounded-3 bg-light border mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2 dash-activity-active-banner">
                                <div class="d-flex align-items-center gap-2.5" style="min-width: 0; flex: 1;">
                                    <span class="pulse-dot" style="background: <?= $statusDetails['status_color'] ?>; width: 12px; height: 12px; border-radius: 50%; display: inline-block; flex-shrink: 0;"></span>
                                    <div style="min-width: 0;">
                                        <span class="text-muted fs-8 d-block text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.68rem !important;">ACTIVE SINCE <?= $currentAct ? $currentAct['start_time_formatted'] : '--' ?></span>
                                        <strong class="text-dark fs-6 d-inline-block text-truncate align-middle" id="dashActiveActivityTitleDisplay" style="max-width: 100%;">
                                            <?= sanitize($currentAct['activity_type'] ?? 'No activity started') ?>
                                        </strong>
                                        <?php if (!empty($currentAct['task_title'])): ?>
                                            <span class="badge bg-primary-subtle text-primary border border-primary ms-1 fs-9 text-truncate align-middle" style="max-width: 130px;"><?= sanitize($currentAct['task_title']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="text-end ps-2" style="flex-shrink: 0;">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.65rem !important;">SESSION DURATION</small>
                                    <span class="font-monospace fw-bold text-primary fs-5" id="dashBannerLiveActivityTimer">
                                        <?= $currentAct ? formatSecondsToHMS((int)$currentAct['elapsed_seconds']) : '00:00:00' ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Activity Selector Buttons Grid -->
                            <div class="mb-3">
                                <label class="form-label text-dark fw-bold fs-7 mb-2">Select Activity Type:</label>
                                <div class="d-flex flex-wrap gap-2" id="dashActivityPillsContainer">
                                    <?php
                                    $dashActivityOptions = [
                                        ['Working on Task', 'fas fa-tasks', 'btn-outline-primary'],
                                        ['Development', 'fas fa-code', 'btn-outline-primary'],
                                        ['Designing', 'fas fa-paint-brush', 'btn-outline-primary'],
                                        ['Testing', 'fas fa-vial', 'btn-outline-primary'],
                                        ['Client Communication', 'fas fa-comments', 'btn-outline-info'],
                                        ['Client Meeting / Call', 'fas fa-video', 'btn-outline-purple'],
                                        ['Waiting for Client', 'fas fa-user-clock', 'btn-outline-warning'],
                                        ['Client Review & Changes', 'fas fa-pen-ruler', 'btn-outline-primary'],
                                        ['Meeting', 'fas fa-users-viewfinder', 'btn-outline-purple'],
                                        ['Waiting for Approval', 'fas fa-hourglass-start', 'btn-outline-warning'],
                                        ['Waiting for Content', 'fas fa-file-invoice', 'btn-outline-warning'],
                                        ['Technical Issue', 'fas fa-triangle-exclamation', 'btn-outline-danger'],
                                        ['Other', 'fas fa-ellipsis-h', 'btn-outline-secondary']
                                    ];
                                    $dashActiveType = $currentAct['activity_type'] ?? '';
                                    foreach ($dashActivityOptions as $opt):
                                        $isSelected = (strcasecmp($dashActiveType, $opt[0]) === 0);
                                    ?>
                                        <button type="button" 
                                                class="btn btn-sm <?= $opt[2] ?> rounded-pill px-3 py-1.5 fs-7 dash-activity-pill-btn <?= $isSelected ? 'active' : '' ?>"
                                                data-activity="<?= htmlspecialchars($opt[0]) ?>"
                                                onclick="selectDashActivityPill('<?= addslashes($opt[0]) ?>')">
                                            <i class="<?= $opt[1] ?> me-1.5"></i><span class="act-label text-truncate"><?= $opt[0] ?></span>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Form for Project, Task, Reason, Notes -->
                            <form id="dashActivityChangeForm" onsubmit="submitDashActivityChange(event)">
                                <input type="hidden" name="activity_type" id="dashSelectedActivityTypeInput" value="<?= htmlspecialchars($currentAct['activity_type'] ?? 'Development') ?>">

                                <!-- Task & Project Cascading Selectors -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small fw-semibold">Associated Project</label>
                                        <select name="project_id" id="dashActivityProjectSelect" class="form-select form-select-sm" onchange="filterDashTasksByProject()">
                                            <option value="">-- Select Project (Optional) --</option>
                                            <?php foreach ($dashProjects as $p): ?>
                                                <option value="<?= $p['id'] ?>" <?= ($currentAct && $currentAct['project_id'] == $p['id']) ? 'selected' : '' ?>>
                                                    <?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary small fw-semibold">Assigned Task</label>
                                        <select name="task_id" id="dashActivityTaskSelect" class="form-select form-select-sm">
                                            <option value="">-- Select Task (Optional) --</option>
                                            <?php foreach ($dashAssignedTasks as $t): ?>
                                                <option value="<?= $t['id'] ?>" data-project="<?= $t['project_id'] ?>" <?= ($currentAct && $currentAct['task_id'] == $t['id']) ? 'selected' : '' ?>>
                                                    <?= sanitize($t['title']) ?> [<?= ucfirst($t['status']) ?>]
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- CONDITIONAL WAITING / BLOCKED REASON SECTION + CLIENT NAME -->
                                <div id="dashWaitingBlockedReasonContainer" class="p-3 bg-warning-subtle border border-warning rounded-3 mb-3" style="display: none;">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-exclamation-circle text-warning fs-5"></i>
                                        <strong class="text-dark fs-7">Reason for Waiting / Blocker:</strong>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-md-7">
                                            <label class="form-label text-dark small fw-semibold mb-1">Reason / Category:</label>
                                            <select name="reason" id="dashActivityReasonSelect" class="form-select form-select-sm">
                                                <option value="">-- Select Specific Reason --</option>
                                                <?php 
                                                $dClientReasons = [];
                                                $dInternalReasons = [];
                                                $dBlockerReasons = [];
                                                foreach ($dashReasons as $r) {
                                                    $rt = strtolower($r['reason_text']);
                                                    if (str_contains($rt, 'client')) {
                                                        $dClientReasons[] = $r;
                                                    } elseif ($r['category'] === 'blocked') {
                                                        $dBlockerReasons[] = $r;
                                                    } else {
                                                        $dInternalReasons[] = $r;
                                                    }
                                                }
                                                ?>
                                                <optgroup label="Client-Related Waiting & Dependencies">
                                                    <?php foreach ($dClientReasons as $r): ?>
                                                        <option value="<?= htmlspecialchars($r['reason_text']) ?>"><?= sanitize($r['reason_text']) ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                                <optgroup label="Internal Approvals & Team Waiting">
                                                    <?php foreach ($dInternalReasons as $r): ?>
                                                        <option value="<?= htmlspecialchars($r['reason_text']) ?>"><?= sanitize($r['reason_text']) ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                                <optgroup label="Technical & Operational Blockers">
                                                    <?php foreach ($dBlockerReasons as $r): ?>
                                                        <option value="<?= htmlspecialchars($r['reason_text']) ?>"><?= sanitize($r['reason_text']) ?></option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label text-dark small fw-semibold mb-1">Client Name / Contact (Optional):</label>
                                            <input type="text" name="client_name" id="dashActivityClientNameInput" class="form-control form-control-sm" placeholder="e.g. Maharani Website Client / Krafting">
                                        </div>
                                    </div>
                                </div>

                                <!-- Notes Field -->
                                <div class="mb-3">
                                    <label class="form-label text-secondary small fw-semibold">Work Notes / Details (Optional)</label>
                                    <textarea name="notes" id="dashActivityNotesInput" class="form-control form-control-sm" rows="2" placeholder="e.g. Mobile Optimization, working on navigation drawer or waiting on client banners..."><?= sanitize($currentAct['notes'] ?? '') ?></textarea>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 dash-activity-actions-row">
                                    <a href="<?= BASE_URL ?>/workday" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        <i class="fas fa-external-link-alt me-1"></i>Open Full Workday Hub
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" id="dashBtnUpdateActivity">
                                        <i class="fas fa-check me-1"></i>Update Activity Now
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Live Shift Timer & Daily Productivity Graph -->
                <div class="col-lg-5">
                    <div class="d-flex flex-column gap-3 h-100">
                        <!-- Hero Timer Card -->
                        <div class="hero-timer-card p-3.5 flex-fill d-flex flex-column justify-content-between">
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary text-white px-3 py-1 rounded-pill fs-8 fw-bold"><i class="fas fa-stopwatch me-1"></i>Live Work Tracker</span>
                                        <span class="live-lunch-status-badge"><?= getLunchBadge() ?></span>
                                    </div>
                                    <span id="dashEmployeeDayBadge"><?= getAttendanceDayBadge($myStatus, $shiftElapsedSecs) ?></span>
                                </div>
                                <h5 class="fw-bold text-dark mb-0 fs-5">Shift Control & Timer</h5>
                                <small class="text-secondary fs-8 mb-2 d-block">Shift Target: <?= $fmtShiftStart ?> - <?= $fmtShiftEnd ?> (IST) | 8h 50m Target (Lunch: <?= $fmtLunchStart ?> - <?= $fmtLunchEnd ?>)</small>
                                
                                <div class="text-center py-2 mb-2">
                                    <div class="hero-timer-clock text-primary fw-bold" id="empLiveShiftTimer" style="font-size: 2.1rem; line-height: 1.15;"><?= formatDuration($shiftElapsedSecs) ?></div>
                                    <small class="text-muted fs-8"><i class="fas fa-hourglass-half me-1 text-warning"></i>Remaining: <strong id="empRemainingHoursVal" class="font-monospace text-dark fs-7"><?= formatDuration($remainingShiftSecs) ?></strong></small>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button onclick="handleClockInOut('<?= $isShiftRunning ? 'clock_out' : 'clock_in' ?>')" 
                                        class="btn <?= $isShiftRunning ? 'btn-danger' : 'btn-success' ?> flex-fill fw-bold py-2 shift-toggle-btn align-items-center justify-content-center wifi-restricted-btn <?= $isClientIpAuth ? 'd-inline-flex' : 'd-none' ?>"
                                        style="<?= $isClientIpAuth ? '' : 'display:none !important;' ?>">
                                    <i class="fas <?= $isShiftRunning ? 'fa-stop' : 'fa-play' ?> me-1.5"></i><?= $isShiftRunning ? 'Pause / End Shift' : 'Start Shift Now' ?>
                                </button>
                                <div class="wifi-unauth-notice flex-fill <?= $isClientIpAuth ? 'd-none' : 'd-inline-flex' ?> align-items-center justify-content-center p-2 rounded bg-danger-subtle text-danger border border-danger fs-8 fw-semibold text-center"
                                     style="<?= $isClientIpAuth ? 'display:none !important;' : '' ?>">
                                    <i class="fas fa-wifi-slash me-1.5"></i>Office Wi-Fi Required
                                </div>
                                <a href="<?= BASE_URL ?>/work-log" class="btn btn-outline-primary fw-bold px-3.5 py-2 flex-fill flex-md-grow-0 text-center" title="Daily Work Log"><i class="fas fa-book-open me-1"></i>Work Log</a>
                            </div>
                        </div>

                        <!-- Daily Productivity Graph -->
                        <div class="glass-card p-3 flex-fill d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0 fs-6"><i class="fas fa-chart-line me-1.5 text-success"></i>Daily Productivity Graph</h6>
                                    <small class="text-muted fs-9">Hourly output efficiency index.</small>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success fw-bold fs-9">+96% High Velocity</span>
                            </div>
                            <div style="position: relative; height: 140px; width: 100%;">
                                <canvas id="empProductivityChart" height="140" style="max-height: 150px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Achievement Badges & Work Queue Row -->
            <div class="row g-4 mb-4 align-items-stretch" data-dash-section="tasks">
                <!-- Achievement Badges Panel -->
                <div class="col-lg-4">
                    <div class="glass-card h-100">
                        <h5 class="fw-bold text-dark mb-3"><i class="fas fa-award me-2 text-warning"></i>Achievement Badges</h5>
                        <div class="d-flex flex-column gap-2">
                            <div class="achievement-badge-card">
                                <div class="achievement-icon-box" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;"><i class="fas fa-fire"></i></div>
                                <div>
                                    <strong class="text-dark d-block fs-7">Streak Master 🔥</strong>
                                    <small class="text-muted fs-8">15 Days Consistent Shift Clock-In</small>
                                </div>
                            </div>

                            <div class="achievement-badge-card">
                                <div class="achievement-icon-box" style="background: rgba(16, 185, 129, 0.12); color: #10b981;"><i class="fas fa-bolt"></i></div>
                                <div>
                                    <strong class="text-dark d-block fs-7">Speedy Resolver ⚡</strong>
                                    <small class="text-muted fs-8">Completed 10 Tasks Ahead of Deadline</small>
                                </div>
                            </div>

                            <div class="achievement-badge-card">
                                <div class="achievement-icon-box" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;"><i class="fas fa-user-clock"></i></div>
                                <div>
                                    <strong class="text-dark d-block fs-7">Punctual Pro ⏰</strong>
                                    <small class="text-muted fs-8">0 Late Marks This Month</small>
                                </div>
                            </div>

                            <div class="achievement-badge-card">
                                <div class="achievement-icon-box" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;"><i class="fas fa-trophy"></i></div>
                                <div>
                                    <strong class="text-dark d-block fs-7">Top Contributor ⭐</strong>
                                    <small class="text-muted fs-8">Ranked #1 in Department Output</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Tasks Work Queue -->
                <div class="col-lg-8">
                    <div class="glass-card h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-tasks me-2 text-primary"></i><?= ($isSeoLead || $isDevLead) ? 'Team & Assigned Work Queue' : 'My Assigned Work Queue' ?></h5>
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($isSeoLead): ?>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" onclick="openGiveSeoTaskModal(null, event)">
                                        <i class="fas fa-bullhorn me-1"></i>Give SEO Task
                                    </button>
                                <?php elseif ($isDevLead): ?>
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" onclick="openGiveDevTaskModal(9, event)">
                                        <i class="fas fa-code me-1"></i>Give Dev Task (Abhay)
                                    </button>
                                <?php elseif ($canManageTasks): ?>
                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-3 fw-bold" onclick="openGiveSeoTaskModal(null, event)">
                                        <i class="fas fa-bullhorn me-1"></i>Give SEO Task
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" onclick="openGiveDevTaskModal(9, event)">
                                        <i class="fas fa-code me-1"></i>Give Dev Task (Abhay)
                                    </button>
                                <?php endif; ?>
                                <a href="<?= BASE_URL ?>/tasks" class="small text-primary text-decoration-none fw-semibold">View All Tasks</a>
                            </div>
                        </div>

                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Task Title</th>
                                        <?php if ($isSeoLead || $isDevLead): ?>
                                            <th>Assigned To</th>
                                        <?php endif; ?>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Deadline</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($empTasksList): foreach ($empTasksList as $t): 
                                        $isMine = ((int)($t['assigned_to'] ?? 0) === $userId || (int)($t['assigned_to_employee_id'] ?? 0) === $userId);
                                    ?>
                                        <tr>
                                            <td>
                                                <a href="<?= BASE_URL ?>/task-details?id=<?= $t['id'] ?>" class="fw-semibold text-dark text-decoration-none fs-7"><?= sanitize($t['title']) ?></a>
                                                <?php if (!empty($t['project_name'])): ?>
                                                    <div class="small text-muted fs-9"><?= sanitize($t['project_name']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <?php if ($isSeoLead || $isDevLead): ?>
                                                <td>
                                                    <span class="badge bg-light text-dark border fw-semibold fs-9">
                                                        <i class="fas fa-user me-1 text-primary"></i><?= sanitize($t['assigned_to_name'] ?? 'Assigned') ?>
                                                    </span>
                                                </td>
                                            <?php endif; ?>
                                            <td><?= getPriorityBadge($t['priority']) ?></td>
                                            <td><?= getStatusBadge($t['status'], $t['deadline']) ?></td>
                                            <td class="small text-secondary fs-8"><?= getDeadlineCountdown($t['deadline']) ?></td>
                                            <td class="text-end">
                                                <?php if ($isMine): ?>
                                                    <?php if ($t['status'] === 'running'): ?>
                                                        <button class="btn btn-sm btn-warning rounded-pill px-2.5" onclick="pauseTaskTimer(<?= $t['id'] ?>)"><i class="fas fa-pause me-1"></i>Pause</button>
                                                        <button class="btn btn-sm btn-success rounded-pill px-3 fw-bold" onclick="stopTaskTimer(<?= $t['id'] ?>)"><i class="fas fa-paper-plane me-1"></i>Submit</button>
                                                    <?php elseif ($t['status'] === 'pending_approval'): ?>
                                                        <span class="badge bg-warning-subtle text-warning border border-warning fs-8 py-1.5 px-2.5 rounded-pill"><i class="fas fa-hourglass-half me-1"></i>Awaiting Approval</span>
                                                    <?php elseif ($t['status'] === 'completed'): ?>
                                                        <span class="badge bg-success-subtle text-success border border-success fs-8 py-1.5 px-2.5 rounded-pill"><i class="fas fa-check-circle me-1"></i>Done</span>
                                                    <?php else: ?>
                                                        <button class="btn btn-sm btn-primary rounded-pill px-2.5" onclick="startTaskTimer(<?= $t['id'] ?>)"><i class="fas fa-play me-1"></i>Start</button>
                                                        <button class="btn btn-sm btn-outline-success rounded-pill px-2.5 fw-semibold" onclick="submitTaskCompletionPrompt(<?= $t['id'] ?>)" title="Submit for Manager Approval"><i class="fas fa-paper-plane me-1"></i>Submit</button>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <a href="<?= BASE_URL ?>/task-details?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-primary rounded-pill px-2.5">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; else: ?>
                                        <tr><td colspan="<?= ($isSeoLead || $isDevLead) ? 6 : 5 ?>" class="text-center text-muted py-4">No tasks found in your work queue.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Work Queue Card List (< 768px App View) -->
                        <div class="mobile-card-list d-block d-md-none">
                            <?php if ($empTasksList): foreach ($empTasksList as $t): 
                                $isMine = ((int)($t['assigned_to'] ?? 0) === $userId || (int)($t['assigned_to_employee_id'] ?? 0) === $userId);
                            ?>
                                <div class="mobile-data-card">
                                    <div class="mobile-data-card-header">
                                        <div class="flex-grow-1 overflow-hidden me-2">
                                            <span class="text-muted font-monospace fs-9 fw-bold">#<?= $t['id'] ?></span>
                                            <?php if (($isSeoLead || $isDevLead) && !empty($t['assigned_to_name'])): ?>
                                                <span class="badge bg-light text-dark border fs-9 ms-1"><i class="fas fa-user text-primary me-1"></i><?= sanitize($t['assigned_to_name']) ?></span>
                                            <?php endif; ?>
                                            <a href="<?= BASE_URL ?>/task-details?id=<?= $t['id'] ?>" class="fw-bold text-dark text-decoration-none d-block fs-7 mt-0.5 line-height-1-2 text-truncate">
                                                <?= sanitize($t['title']) ?>
                                            </a>
                                        </div>
                                        <div class="text-end flex-shrink-0">
                                            <?= getPriorityBadge($t['priority']) ?>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center my-2 p-1.5 px-2 bg-light rounded-2 border">
                                        <div><?= getStatusBadge($t['status'], $t['deadline']) ?></div>
                                        <span class="text-secondary fs-8 fw-semibold"><i class="fas fa-clock me-1 text-primary"></i><?= getDeadlineCountdown($t['deadline']) ?></span>
                                    </div>
                                    <div class="mobile-data-card-footer">
                                        <a href="<?= BASE_URL ?>/task-details?id=<?= $t['id'] ?>" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 me-auto fw-semibold">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                        <?php if ($isMine): ?>
                                            <?php if ($t['status'] === 'running'): ?>
                                                <button class="btn btn-xs btn-warning rounded-pill px-2 fw-bold" onclick="pauseTaskTimer(<?= $t['id'] ?>)">
                                                    <i class="fas fa-pause me-1"></i>Pause
                                                </button>
                                                <button class="btn btn-xs btn-success rounded-pill px-2.5 fw-bold" onclick="stopTaskTimer(<?= $t['id'] ?>)">
                                                    <i class="fas fa-paper-plane me-1"></i>Submit
                                                </button>
                                            <?php elseif ($t['status'] === 'pending_approval'): ?>
                                                <span class="badge bg-warning-subtle text-warning border border-warning fs-9 py-1 px-2.5 rounded-pill">
                                                    <i class="fas fa-hourglass-half me-1"></i>Awaiting Approval
                                                </span>
                                            <?php elseif ($t['status'] === 'completed'): ?>
                                                <span class="badge bg-success-subtle text-success border border-success fs-9 py-1 px-2.5 rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i>Done
                                                </span>
                                            <?php else: ?>
                                                <button class="btn btn-xs btn-primary rounded-pill px-2.5 fw-bold" onclick="startTaskTimer(<?= $t['id'] ?>)">
                                                    <i class="fas fa-play me-1"></i>Start
                                                </button>
                                                <button class="btn btn-xs btn-outline-success rounded-pill px-2 fw-semibold" onclick="submitTaskCompletionPrompt(<?= $t['id'] ?>)">
                                                    <i class="fas fa-paper-plane me-1"></i>Submit
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; else: ?>
                                <div class="text-center text-muted py-4 bg-light rounded-3 border">No tasks found in your work queue.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php if ($isSeoLead): ?>
                <!-- ================================================================= -->
                <!-- SEO DEPARTMENT TEAM & TASK DELEGATION HUB (UVAISH LEAD PORTAL)   -->
                <!-- ================================================================= -->
                <div class="col-12 mt-4" data-dash-section="tasks">
                    <div class="glass-card shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 pb-2 border-bottom">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill fs-8">
                                        <i class="fas fa-bullhorn me-1"></i>SEO Department Delegation
                                    </span>
                                    <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1 rounded-pill fs-8">
                                        <i class="fas fa-users-cog me-1"></i>Team Lead Controls
                                    </span>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">
                                    <i class="fas fa-share-nodes text-primary me-2"></i>Give & Manage Tasks for SEO Department
                                </h5>
                                <small class="text-secondary">Directly assign and delegate tasks to <strong>Aman</strong> and <strong>Shivanshu (Shivansh)</strong> in the SEO Department.</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-primary fw-bold shadow-xs px-3 py-1.5" onclick="openGiveSeoTaskModal(null, event)">
                                    <i class="fas fa-plus-circle me-1"></i>Give SEO Task
                                </button>
                                <a href="<?= BASE_URL ?>/tasks" class="btn btn-outline-primary fw-semibold px-3 py-1.5 fs-8">
                                    <i class="fas fa-tasks me-1"></i>Manage SEO Tasks
                                </a>
                            </div>
                        </div>

                        <!-- Quick Team Member Assignment Cards -->
                        <div class="row g-3 mb-4">
                            <!-- Card 1: Aman (Intern SEO) -->
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-white border rounded-3 shadow-xs h-100 d-flex flex-column justify-content-between hover-lift">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="position-relative">
                                            <img src="https://ui-avatars.com/api/?name=Aman&background=4f46e5&color=fff&bold=true" class="rounded-circle shadow-xs" width="46" height="46" alt="Aman">
                                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-7">Aman</div>
                                            <div class="text-secondary fs-8">Intern SEO Analyst</div>
                                            <span class="badge bg-light text-muted border fs-9">ID: #10 &bull; SEO Dept</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                        <small class="text-muted fs-8">SEO Deliverables</small>
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" onclick="openGiveSeoTaskModal(10, event)">
                                            <i class="fas fa-plus me-1"></i>Give Task to Aman
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Shivanshu / Shivansh (Senior SEO Executive) -->
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-white border rounded-3 shadow-xs h-100 d-flex flex-column justify-content-between hover-lift">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="position-relative">
                                            <img src="https://ui-avatars.com/api/?name=Shivanshu&background=06b6d4&color=fff&bold=true" class="rounded-circle shadow-xs" width="46" height="46" alt="Shivanshu">
                                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-7">Shivanshu (Shivansh)</div>
                                            <div class="text-secondary fs-8">Senior SEO Executive</div>
                                            <span class="badge bg-light text-muted border fs-9">ID: #6 &bull; SEO Dept</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                        <small class="text-muted fs-8">On-Page & Technical</small>
                                        <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3 fw-bold" onclick="openGiveSeoTaskModal(6, event)">
                                            <i class="fas fa-plus me-1"></i>Give Task to Shivansh
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3: Uvaish (SEO Lead Manager / Lead) -->
                            <div class="col-md-12 col-lg-4">
                                <div class="p-3 bg-white border rounded-3 shadow-xs h-100 d-flex flex-column justify-content-between hover-lift">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="position-relative">
                                            <img src="https://ui-avatars.com/api/?name=Uvaish&background=10b981&color=fff&bold=true" class="rounded-circle shadow-xs" width="46" height="46" alt="Uvaish">
                                            <span class="position-absolute bottom-0 end-0 p-1 bg-primary border border-white rounded-circle"></span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-7">Uvaish</div>
                                            <div class="text-secondary fs-8">SEO Lead Manager</div>
                                            <span class="badge bg-primary-subtle text-primary border fs-9">Team Lead &bull; SEO Dept</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                        <small class="text-muted fs-8">Strategy & Delegation</small>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 fw-bold"><i class="fas fa-crown me-1"></i>SEO Department Head</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent SEO Department Active Tasks Table -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0 fs-7">
                                <i class="fas fa-list-check text-primary me-1"></i>Current SEO Team Tasks (Aman, Shivansh & Uvaish)
                            </h6>
                            <a href="<?= BASE_URL ?>/tasks" class="small text-primary text-decoration-none fw-semibold">View in Task Management &rarr;</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light fs-8">
                                    <tr>
                                        <th>Task Deliverable</th>
                                        <th>Assigned To</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Deadline</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-8">
                                    <?php if (!empty($seoDeptTasks)): ?>
                                        <?php foreach ($seoDeptTasks as $st): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= BASE_URL ?>/task-details?id=<?= $st['id'] ?>" class="fw-bold text-dark text-decoration-none">
                                                        <span class="text-muted font-monospace me-1">#<?= $st['id'] ?></span><?= sanitize($st['title']) ?>
                                                    </a>
                                                    <?php if (!empty($st['project_name'])): ?>
                                                        <div class="small text-muted fs-9"><?= sanitize($st['project_name']) ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border fw-semibold">
                                                        <i class="fas fa-user me-1 text-primary"></i><?= sanitize($st['assignee_name']) ?>
                                                    </span>
                                                </td>
                                                <td><?= getPriorityBadge($st['priority']) ?></td>
                                                <td><?= getStatusBadge($st['status'], $st['deadline']) ?></td>
                                                <td class="text-secondary"><?= getDeadlineCountdown($st['deadline']) ?></td>
                                                <td class="text-end">
                                                    <a href="<?= BASE_URL ?>/task-details?id=<?= $st['id'] ?>" class="btn btn-xs btn-outline-primary rounded-pill px-2.5">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center text-muted py-3">No active SEO tasks found. Click "Give SEO Task" to delegate to your team!</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($isDevLead): ?>
                <!-- ================================================================= -->
                <!-- DEVELOPMENT DEPARTMENT TEAM & TASK DELEGATION HUB (RAHUL LEAD)     -->
                <!-- ================================================================= -->
                <div class="col-12 mt-4" data-dash-section="tasks">
                    <div class="glass-card shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 pb-2 border-bottom">
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill fs-8">
                                        <i class="fas fa-code me-1"></i>Development Department Delegation
                                    </span>
                                    <span class="badge bg-info-subtle text-info fw-bold px-2.5 py-1 rounded-pill fs-8">
                                        <i class="fas fa-laptop-code me-1"></i>Senior Fullstack Lead
                                    </span>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">
                                    <i class="fas fa-code-branch text-primary me-2"></i>Give & Manage Tasks for Development Department
                                </h5>
                                <small class="text-secondary">Directly assign and delegate frontend engineering tasks to <strong>Abhay</strong> (Intern Frontend Developer).</small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-primary fw-bold shadow-xs px-3 py-1.5" onclick="openGiveDevTaskModal(9, event)">
                                    <i class="fas fa-plus-circle me-1"></i>Give Dev Task to Abhay
                                </button>
                                <a href="<?= BASE_URL ?>/tasks" class="btn btn-outline-primary fw-semibold px-3 py-1.5 fs-8">
                                    <i class="fas fa-tasks me-1"></i>Manage Dev Tasks
                                </a>
                            </div>
                        </div>

                        <!-- Quick Team Member Assignment Cards -->
                        <div class="row g-3 mb-4">
                            <!-- Card 1: Abhay (Intern Frontend Developer) -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-3 bg-white border rounded-3 shadow-xs h-100 d-flex flex-column justify-content-between hover-lift">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="position-relative">
                                            <img src="https://ui-avatars.com/api/?name=Abhay&background=3b82f6&color=fff&bold=true" class="rounded-circle shadow-xs" width="46" height="46" alt="Abhay">
                                            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-7">Abhay</div>
                                            <div class="text-secondary fs-8">Intern Frontend Developer</div>
                                            <span class="badge bg-light text-muted border fs-9">ID: #9 &bull; Development Dept</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                        <small class="text-muted fs-8">Frontend & UI Tasks</small>
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" onclick="openGiveDevTaskModal(9, event)">
                                            <i class="fas fa-plus me-1"></i>Give Task to Abhay
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Rahul (Senior Fullstack Developer / Lead) -->
                            <div class="col-md-6 col-lg-6">
                                <div class="p-3 bg-white border rounded-3 shadow-xs h-100 d-flex flex-column justify-content-between hover-lift">
                                    <div class="d-flex align-items-center gap-3 mb-3">
                                        <div class="position-relative">
                                            <img src="https://ui-avatars.com/api/?name=Rahul&background=6366f1&color=fff&bold=true" class="rounded-circle shadow-xs" width="46" height="46" alt="Rahul">
                                            <span class="position-absolute bottom-0 end-0 p-1 bg-primary border border-white rounded-circle"></span>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark fs-7">Rahul</div>
                                            <div class="text-secondary fs-8">Senior Fullstack Developer</div>
                                            <span class="badge bg-primary-subtle text-primary border fs-9">Team Lead &bull; Development Dept</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                        <small class="text-muted fs-8">Architecture & Lead</small>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 fw-bold"><i class="fas fa-crown me-1"></i>Development Department Lead</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Development Department Active Tasks Table -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="fw-bold text-dark mb-0 fs-7">
                                <i class="fas fa-list-check text-primary me-1"></i>Current Development Tasks (Abhay & Rahul)
                            </h6>
                            <a href="<?= BASE_URL ?>/tasks" class="small text-primary text-decoration-none fw-semibold">View in Task Management &rarr;</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0 table-hover">
                                <thead class="table-light fs-8">
                                    <tr>
                                        <th>Task Deliverable</th>
                                        <th>Assigned To</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Deadline</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-8">
                                    <?php if (!empty($devDeptTasks)): ?>
                                        <?php foreach ($devDeptTasks as $dt): ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= BASE_URL ?>/task-details?id=<?= $dt['id'] ?>" class="fw-bold text-dark text-decoration-none">
                                                        <span class="text-muted font-monospace me-1">#<?= $dt['id'] ?></span><?= sanitize($dt['title']) ?>
                                                    </a>
                                                    <?php if (!empty($dt['project_name'])): ?>
                                                        <div class="small text-muted fs-9"><?= sanitize($dt['project_name']) ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border fw-semibold">
                                                        <i class="fas fa-user me-1 text-primary"></i><?= sanitize($dt['assignee_name']) ?>
                                                    </span>
                                                </td>
                                                <td><?= getPriorityBadge($dt['priority']) ?></td>
                                                <td><?= getStatusBadge($dt['status'], $dt['deadline']) ?></td>
                                                <td class="text-secondary"><?= getDeadlineCountdown($dt['deadline']) ?></td>
                                                <td class="text-end">
                                                    <a href="<?= BASE_URL ?>/task-details?id=<?= $dt['id'] ?>" class="btn btn-xs btn-outline-primary rounded-pill px-2.5">
                                                        <i class="fas fa-eye me-1"></i>View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="6" class="text-center text-muted py-3">No active development tasks found. Click "Give Dev Task to Abhay" to delegate!</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Employee Escalations & Flags Section -->
                <div class="col-12 mt-4" data-dash-section="tasks">
                    <div class="row g-4 align-items-stretch">
                        <!-- My Emergency Task Requests -->
                        <div class="col-lg-6">
                            <div class="glass-card h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.12); color: #dc2626; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-0 font-heading">My Emergency Requests</h6>
                                    </div>
                                    <a href="<?= BASE_URL ?>/tasks" class="btn btn-sm btn-outline-danger rounded-pill px-2.5">
                                        <i class="fas fa-plus me-1"></i>New Request
                                    </a>
                                </div>
                                <?php if (!empty($myEmergencyRequests)): ?>
                                    <div class="d-flex flex-column gap-2.5">
                                        <?php foreach ($myEmergencyRequests as $mer): ?>
                                            <div class="p-3 bg-white border rounded-3 shadow-xs">
                                                <div class="d-flex justify-content-between align-items-start gap-2 mb-1.5">
                                                    <div>
                                                        <span class="badge bg-light text-muted border font-monospace fs-8">#REQ-<?= $mer['id'] ?></span>
                                                        <a href="<?= BASE_URL ?>/task-details?id=<?= $mer['task_id'] ?>" class="fw-semibold text-dark text-decoration-none fs-7 ms-1">
                                                            <?= sanitize($mer['task_title']) ?>
                                                        </a>
                                                    </div>
                                                    <?= getEmergencyRequestBadge($mer['status']) ?>
                                                </div>
                                                <div class="small text-secondary mb-1">
                                                    <strong>Reason:</strong> <?= sanitize($mer['reason']) ?>
                                                </div>
                                                <div class="text-muted fs-8 mb-2 text-truncate">
                                                    <?= sanitize($mer['message']) ?>
                                                </div>
                                                <?php if ($mer['status'] === 'approved'): ?>
                                                    <div class="p-2 bg-success-subtle border border-success rounded-2 text-success fs-8">
                                                        <i class="fas fa-check-circle me-1"></i>Approved by <?= sanitize($mer['reviewer_name'] ?? 'Manager') ?>
                                                        <?= !empty($mer['reviewer_comment']) ? ' - ' . sanitize($mer['reviewer_comment']) : '' ?>
                                                    </div>
                                                <?php elseif ($mer['status'] === 'rejected'): ?>
                                                    <div class="p-2 bg-danger-subtle border border-danger rounded-2 text-danger fs-8">
                                                        <i class="fas fa-times-circle me-1"></i>Rejected by <?= sanitize($mer['reviewer_name'] ?? 'Manager') ?>
                                                        <?= !empty($mer['rejection_reason']) ? ' - ' . sanitize($mer['rejection_reason']) : '' ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top text-muted fs-9">
                                                    <span>Submitted: <?= formatISTDateTime($mer['created_at']) ?></span>
                                                    <a href="<?= BASE_URL ?>/task-details?id=<?= $mer['task_id'] ?>" class="text-primary text-decoration-none">View Task &rarr;</a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4 bg-light rounded-3 border text-muted small">
                                        <i class="fas fa-check-circle text-success fs-4 d-block mb-1"></i>
                                        No emergency requests submitted.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- My Task Flags / Missing Requirements -->
                        <div class="col-lg-6">
                            <div class="glass-card h-100">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-flag"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-0 font-heading">My Reported Missing Requirements</h6>
                                    </div>
                                    <a href="<?= BASE_URL ?>/flagged-tasks" class="btn btn-sm btn-outline-warning text-dark rounded-pill px-2.5">
                                        Flag Center &rarr;
                                    </a>
                                </div>
                                <?php if (!empty($myTaskFlags)): ?>
                                    <div class="d-flex flex-column gap-2.5">
                                        <?php foreach ($myTaskFlags as $mtf): ?>
                                            <div class="p-3 bg-white border rounded-3 shadow-xs">
                                                <div class="d-flex justify-content-between align-items-start gap-2 mb-1.5">
                                                    <div>
                                                        <span class="badge bg-light text-muted border font-monospace fs-8">#FLAG-<?= $mtf['id'] ?></span>
                                                        <a href="<?= BASE_URL ?>/task-details?id=<?= $mtf['task_id'] ?>" class="fw-semibold text-dark text-decoration-none fs-7 ms-1">
                                                            <?= sanitize($mtf['task_title']) ?>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <?= getTaskFlagBadge($mtf['status']) ?>
                                                    </div>
                                                </div>
                                                <div class="small text-secondary mb-1">
                                                    <strong><?= sanitize($mtf['category']) ?></strong> &bull; <?= getTaskFlagImpactBadge($mtf['impact']) ?>
                                                </div>
                                                <div class="text-muted fs-8 mb-2 text-truncate">
                                                    <?= sanitize($mtf['missing_requirement']) ?>
                                                </div>
                                                <?php if (!empty($mtf['resolution_message'])): ?>
                                                    <div class="p-2 bg-success-subtle border border-success rounded-2 text-success fs-8">
                                                        <i class="fas fa-check-circle me-1"></i>Resolved by <?= sanitize($mtf['resolver_name'] ?? 'Manager') ?>:
                                                        <span class="text-dark d-block mt-0.5"><?= sanitize($mtf['resolution_message']) ?></span>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="d-flex justify-content-between align-items-center mt-2 pt-1 border-top text-muted fs-9">
                                                    <span>Reported: <?= formatISTDateTime($mtf['created_at']) ?></span>
                                                    <a href="<?= BASE_URL ?>/flagged-tasks" class="text-primary text-decoration-none">Open Thread &rarr;</a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-4 bg-light rounded-3 border text-muted small">
                                        <i class="fas fa-check-circle text-success fs-4 d-block mb-1"></i>
                                        No active task flags reported.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div> <!-- End dashboard-page-container -->
</div> <!-- End main-wrapper -->

<!-- MODAL 1: Generate Monthly Salary Modal -->
<?php if ($isAdmin): ?>
<div class="modal fade" id="generatePayrollModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning-subtle">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-calculator me-2 text-warning"></i>Generate Monthly Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dashGeneratePayrollForm">
                <div class="modal-body">
                    <p class="text-secondary small mb-3">Select the month cycle to generate or recalculate automated net salaries for all active employees based on attendance, half days, and leave records.</p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Select Month Cycle *</label>
                        <input type="month" name="month_year" id="dashPayrollMonthInput" class="form-control form-control-lg font-monospace" value="<?= $curMonth ?>" required>
                    </div>
                    <div class="alert alert-info py-2 small mb-0">
                        <i class="fas fa-info-circle me-1"></i> Net Salary = Base Salary + Allowance + Bonus - Deductions (Absents, Half-Days & Unpaid Leaves).
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark" id="dashBtnGeneratePayroll"><i class="fas fa-play me-1"></i>Generate Salary Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 2: Dashboard Quick Edit Employee Base Salary -->
<div class="modal fade" id="dashQuickEditSalaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit me-2 text-primary"></i>Update Employee Base Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dashQuickEditSalaryForm">
                <input type="hidden" name="user_id" id="dashEditSalUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Employee Name</label>
                        <input type="text" id="dashEditSalEmpName" class="form-control bg-light fw-bold text-dark" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Monthly Base Salary (₹) *</label>
                        <input type="number" step="0.01" min="0" name="monthly_salary" id="dashEditSalMonthly" class="form-control font-monospace fw-bold text-success fs-5" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Standard Monthly Allowance (₹)</label>
                        <input type="number" step="0.01" min="0" name="allowance" id="dashEditSalAllowance" class="form-control font-monospace" value="0.00">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="dashBtnSaveSalary">Save Base Salary</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- MODAL: Employee Schedule / Request Meeting Modal -->
<div class="modal fade" id="dashScheduleMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-purple text-white p-3.5">
                <h5 class="modal-title fw-bold font-heading d-flex align-items-center gap-2">
                    <i class="fas fa-video"></i>Schedule / Request Meeting
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dashScheduleMeetingForm" onsubmit="submitDashScheduleMeeting(event)">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Meeting Title / Topic *</label>
                        <input type="text" name="title" id="dashScheduleMeetingTitle" class="form-control form-control-sm" placeholder="e.g. Maharani Website Client Sync / Review" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Invite Person (Boss, Manager or Colleague) *</label>
                        <select name="employee_id" id="dashScheduleMeetingPerson" class="form-select form-select-sm" required>
                            <option value="">-- Select Person to Meet With --</option>
                            <?php foreach ($dashTeamMembers as $tm): 
                                if ((int)$tm['id'] === $userId) continue;
                            ?>
                                <option value="<?= $tm['id'] ?>"><?= sanitize($tm['name']) ?> (<?= sanitize($tm['designation'] ?: ucfirst($tm['role'])) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Meeting Type</label>
                            <select name="meeting_type" id="dashScheduleMeetingType" class="form-select form-select-sm" onchange="handleDashMeetingTypeChange(this.value)">
                                <option value="client" selected>Client Call / Meeting</option>
                                <option value="boss">Boss / Manager Sync</option>
                                <option value="internal">Internal Team Standup</option>
                                <option value="review">Design / Code Review</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Client Name (Optional)</label>
                            <input type="text" name="client_name" id="dashScheduleMeetingClientName" class="form-control form-control-sm" placeholder="e.g. Maharani Client">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Associated Project</label>
                        <select name="project_id" id="dashScheduleMeetingProjectId" class="form-select form-select-sm">
                            <option value="">-- None / General Sync --</option>
                            <?php foreach ($dashProjects as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Date</label>
                            <input type="date" name="meeting_date" id="dashScheduleMeetingDate" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-dark fw-semibold small">Start Time</label>
                            <input type="time" name="start_time" id="dashScheduleMeetingStartTime" class="form-control form-control-sm" value="<?= date('H:i') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-dark fw-semibold small">Duration (Min)</label>
                            <select name="duration" id="dashScheduleMeetingDuration" class="form-select form-select-sm">
                                <option value="15">15 min</option>
                                <option value="30" selected>30 min</option>
                                <option value="45">45 min</option>
                                <option value="60">60 min</option>
                                <option value="90">90 min</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-dark fw-semibold small mb-0">Meeting Link (Google Meet / Zoom)</label>
                            <button type="button" class="btn btn-link btn-sm p-0 fs-8 text-primary" onclick="generateDashMeetLink('dashScheduleMeetingLink')">
                                <i class="fas fa-magic me-1"></i>Generate Link
                            </button>
                        </div>
                        <input type="url" name="meeting_link" id="dashScheduleMeetingLink" class="form-control form-control-sm" placeholder="https://meet.google.com/krd-...">
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold small">Description / Agenda (Optional)</label>
                        <textarea name="description" id="dashScheduleMeetingDescription" class="form-control form-control-sm" rows="2" placeholder="Brief points or reason for the meeting..."></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" id="submitDashScheduleMeetingBtn">
                        <i class="fas fa-paper-plane me-1"></i>Send Meeting Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: Edit Meeting Request Modal -->
<div class="modal fade" id="dashEditMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-dark text-white p-3.5">
                <h5 class="modal-title fw-bold font-heading d-flex align-items-center gap-2">
                    <i class="fas fa-pen-to-square text-warning"></i>Edit Meeting Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dashEditMeetingForm" onsubmit="submitDashEditMeeting(event)">
                <input type="hidden" name="meeting_id" id="dashEditMeetingId">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Meeting Title *</label>
                        <input type="text" name="title" id="dashEditMeetingTitle" class="form-control form-control-sm" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Meeting Type</label>
                            <select name="meeting_type" id="dashEditMeetingType" class="form-select form-select-sm">
                                <option value="client">Client Call / Meeting</option>
                                <option value="boss">Boss / Manager Sync</option>
                                <option value="internal">Internal Team Standup</option>
                                <option value="review">Design / Code Review</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Client Name (Optional)</label>
                            <input type="text" name="client_name" id="dashEditMeetingClientName" class="form-control form-control-sm" placeholder="e.g. Client Name">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Associated Project</label>
                        <select name="project_id" id="dashEditMeetingProjectId" class="form-select form-select-sm">
                            <option value="">-- None / General Sync --</option>
                            <?php foreach ($dashProjects as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Date</label>
                            <input type="date" name="meeting_date" id="dashEditMeetingDate" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-dark fw-semibold small">Start Time</label>
                            <input type="time" name="start_time" id="dashEditMeetingStartTime" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-dark fw-semibold small">Duration (Min)</label>
                            <select name="duration" id="dashEditMeetingDuration" class="form-select form-select-sm">
                                <option value="15">15 min</option>
                                <option value="30">30 min</option>
                                <option value="45">45 min</option>
                                <option value="60">60 min</option>
                                <option value="90">90 min</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Meeting Link (Google Meet / Zoom)</label>
                        <input type="url" name="meeting_link" id="dashEditMeetingLink" class="form-control form-control-sm" placeholder="https://meet.google.com/...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Description / Agenda</label>
                        <textarea name="description" id="dashEditMeetingDescription" class="form-control form-control-sm" rows="2"></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold small">Response / Participant Notes (Optional)</label>
                        <textarea name="response_notes" id="dashEditMeetingResponseNotes" class="form-control form-control-sm" rows="1" placeholder="e.g. Client requested 15 min delay or waiting for file..."></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" id="submitDashEditMeetingBtn">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL: Employee Busy / Client Waiting Details Modal       -->
<!-- ========================================================= -->
<div class="modal fade" id="dashMeetingBusyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-bottom p-3">
                <h6 class="modal-title fw-bold text-dark font-heading">
                    <i class="fas fa-clock text-warning me-2"></i>I'm Busy / Client Waiting Details
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="dashMeetingBusyForm" onsubmit="submitDashMeetingBusyResponse(event)">
                <input type="hidden" id="dashMeetingBusyMeetingId" value="">
                <div class="modal-body p-3.5">
                    <div class="p-2.5 rounded-3 bg-warning-subtle border border-warning-subtle mb-3 small">
                        <div class="text-dark fw-bold mb-1" id="dashMeetingBusyTargetText">Meeting Discussion</div>
                        <div class="text-secondary fs-8">Let the meeting organizer know what you are currently occupied with. Your active status will also be updated automatically.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Current Activity / Reason <span class="text-danger">*</span></label>
                        <select id="dashMeetingBusyCategory" class="form-select form-select-sm" required>
                            <optgroup label="Client Waiting / Client Relations">
                                <option value="Waiting for Client" selected>Waiting for Client (Feedback / Assets / Approval)</option>
                                <option value="Client Meeting / Call">Client Meeting / Live Client Call</option>
                                <option value="Client Communication">Client Communication (WhatsApp / Email / Sync)</option>
                                <option value="Client Review & Changes">Client Review & Requested Changes</option>
                            </optgroup>
                            <optgroup label="Tasks & Technical Work">
                                <option value="Working on Task">Working on Priority Task</option>
                                <option value="Development">Critical Development / Coding</option>
                                <option value="Testing">Testing & Bug Verification</option>
                                <option value="Technical Issue">Technical Issue / Blocker</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="Meeting">In another internal meeting</option>
                                <option value="Other">Other Shift Work</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Client Name (If applicable)</label>
                            <input type="text" id="dashMeetingBusyClientName" class="form-control form-control-sm" placeholder="e.g. Maharani Website / Krafting">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">When will you be available?</label>
                            <select id="dashMeetingBusyAvailability" class="form-select form-select-sm">
                                <option value="Available in 15 mins">Available in 15 mins</option>
                                <option value="Available in 30 mins" selected>Available in 30 mins</option>
                                <option value="Available in 1 hour">Available in 1 hour</option>
                                <option value="Available after 2:00 PM">Available after 2:00 PM</option>
                                <option value="Available at 4:00 PM">Available at 4:00 PM</option>
                                <option value="Available at 5:30 PM">Available at 5:30 PM</option>
                                <option value="Available tomorrow">Available tomorrow</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Associated Project</label>
                            <select id="dashMeetingBusyProjectId" class="form-select form-select-sm">
                                <option value="">-- None / General --</option>
                                <?php foreach ($dashProjects as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Assigned Task</label>
                            <select id="dashMeetingBusyTaskId" class="form-select form-select-sm">
                                <option value="">-- None / General --</option>
                                <?php foreach ($dashAssignedTasks as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= sanitize($t['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Note for Organizer (Optional)</label>
                        <textarea id="dashMeetingBusyNotes" class="form-control form-control-sm" rows="2" placeholder="e.g. Waiting for client to confirm credentials, will ping as soon as they reply..."></textarea>
                    </div>

                    <div class="form-check form-switch p-2 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="dashMeetingBusyUpdateWorkdayCheck" checked>
                        <label class="form-check-label small fw-semibold text-dark" for="dashMeetingBusyUpdateWorkdayCheck">
                            Update my active Workday status to this activity now
                        </label>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold shadow-sm" id="submitDashMeetingBusyBtn">
                        <i class="fas fa-paper-plane me-1"></i>Send Busy Note & Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: GIVE SEO TASK (QUICK TASK ASSIGNMENT FOR SEO TEAM)                 -->
<!-- ========================================================================= -->
<div class="modal fade" id="giveSeoTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-bullhorn fs-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0">Give & Assign SEO Task</h5>
                        <small class="text-white-50">Delegate directly to Aman or Shivanshu in the SEO Department</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="giveSeoTaskForm" onsubmit="submitGiveSeoTaskForm(event)">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <?php if (!$isGlobalManager): ?>
                    <input type="hidden" name="department_id" value="3">
                <?php endif; ?>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-dark small fw-semibold">Task Title / Deliverable <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Keyword Research for Luxury Wedding, Core Web Vitals Fix, Backlinks" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-semibold">Priority <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select" required>
                                <option value="low">Low Priority</option>
                                <option value="medium" selected>Medium Priority</option>
                                <option value="high">High Priority</option>
                                <option value="urgent">Urgent Escalation</option>
                            </select>
                        </div>

                        <?php if ($isGlobalManager): ?>
                            <!-- Department selection with + Add Dept for Admin, Manager, and Product Manager -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label text-dark small fw-semibold mb-0">Department <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-xs btn-outline-success fw-bold rounded-pill px-2 py-0.5" onclick="openQuickAddDepartmentModal('dashSeoDept')">
                                        <i class="fas fa-plus me-1"></i>Add Dept
                                    </button>
                                </div>
                                <select name="department_id" id="dashSeoDept" class="form-select" onchange="onDashDeptChanged('dashSeoDept', 'dashSeoProject')">
                                    <option value="0">Auto-Detect from Assignee</option>
                                    <?php foreach ($dashDepartments as $dept): ?>
                                        <option value="<?= $dept['id'] ?>" <?= (int)$dept['id'] === 3 ? 'selected' : '' ?>>
                                            <?= sanitize($dept['name']) ?> <?= !empty($dept['code']) ? '(' . sanitize($dept['code']) . ')' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="__ADD_NEW__" class="fw-bold text-success">➕ + Add New Department...</option>
                                </select>
                            </div>
                        <?php endif; ?>

                        <!-- Linked Project with + Add Project for Admin, Manager, and Product Manager -->
                        <div class="<?= $isGlobalManager ? 'col-md-6' : 'col-md-6' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-dark small fw-semibold mb-0">Linked Project (Optional)</label>
                                <?php if ($isGlobalManager): ?>
                                    <button type="button" class="btn btn-xs btn-outline-success fw-bold rounded-pill px-2 py-0.5" onclick="openQuickAddProjectModal('dashSeoProject', 'dashSeoDept')">
                                        <i class="fas fa-plus me-1"></i>Add Project
                                    </button>
                                <?php endif; ?>
                            </div>
                            <select name="project_id" id="dashSeoProject" class="form-select" onchange="onDashProjectChanged('dashSeoProject', 'dashSeoDept')">
                                <option value="">None (Standalone SEO Deliverable)</option>
                                <?php foreach ($dashProjects as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-dept="<?= $p['department_id'] ?? '' ?>"><?= sanitize($p['name']) ?> <?= !empty($p['code']) ? '(' . sanitize($p['code']) . ')' : '' ?></option>
                                <?php endforeach; ?>
                                <?php if ($isGlobalManager): ?>
                                    <option value="__ADD_NEW__" class="fw-bold text-success">➕ + Add New Project...</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Assign To Employee <span class="text-danger">*</span></label>
                            <select name="assigned_to" class="form-select" required onchange="onDashAssigneeChanged(this, 'dashSeoDept')">
                                <?php if ($isSeoLead): ?>
                                    <option value="">Select SEO Team Member...</option>
                                    <optgroup label="⭐ SEO Department (Authorized Assignees)">
                                        <option value="10" data-dept="3" selected>Aman (Intern SEO Analyst)</option>
                                        <option value="6" data-dept="3">Shivanshu / Shivansh (Senior SEO Executive)</option>
                                    </optgroup>
                                <?php else: ?>
                                    <option value="">Select Employee...</option>
                                    <optgroup label="⭐ SEO Department Team">
                                        <option value="10" data-dept="3" selected>Aman (Intern SEO Analyst)</option>
                                        <option value="6" data-dept="3">Shivanshu / Shivansh (Senior SEO Executive)</option>
                                        <option value="5" data-dept="3">Uvaish (SEO Lead Manager)</option>
                                    </optgroup>
                                    <optgroup label="👥 All Other Employees">
                                        <?php foreach ($dashTeamMembers as $tm): ?>
                                            <?php if (!in_array((int)$tm['id'], [5, 6, 10])): ?>
                                                <option value="<?= $tm['id'] ?>" data-dept="<?= $tm['department_id'] ?? '' ?>"><?= sanitize($tm['name']) ?> (<?= sanitize($tm['designation'] ?? 'Staff') ?>)</option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Estimated Working Hours</label>
                            <input type="number" step="0.5" name="estimated_hours" class="form-control" value="4.0" min="0.5">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Deadline Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="deadline" class="form-control" required value="<?= date('Y-m-d\TH:i', strtotime('+3 days 18:30')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-dark small fw-semibold">Task Description & SEO Instructions</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Provide target keywords, URLs, technical audit checklists, ranking goals, or specific guidelines for Aman or Shivansh..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm" id="btnSubmitGiveSeoTask">
                        <i class="fas fa-check-circle me-1"></i>Assign Task Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitGiveSeoTaskForm(event) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = document.getElementById('btnSubmitGiveSeoTask');
    const title = form.querySelector('[name="title"]').value.trim();
    const assignedTo = form.querySelector('[name="assigned_to"]').value;

    if (!title || !assignedTo) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Required Fields',
                text: 'Please provide a task title and choose an assignee (Aman or Shivansh).'
            });
        } else {
            alert('Please provide a task title and choose an assignee (Aman or Shivansh).');
        }
        return;
    }

    const origBtnHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Assigning Task...';

    const formData = new FormData(form);
    formData.append('action', 'create');

    fetch('<?= BASE_URL ?>/api/tasks?action=create', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: formData
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Server responded with HTTP status ' + res.status);
        }
        return res.json();
    })
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = origBtnHtml;
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Task Assigned!',
                    text: data.message || 'Task successfully given to SEO department team member.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(data.message || 'Task assigned successfully!');
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Assignment Failed',
                    text: data.message || 'Could not assign task.'
                });
            } else {
                alert(data.message || 'Could not assign task.');
            }
        }
    })
    .catch(err => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = origBtnHtml;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while creating the task: ' + err.message
            });
        } else {
            alert('An error occurred: ' + err.message);
        }
    });
}
</script>

<!-- ========================================================================= -->
<!-- MODAL: GIVE DEV TASK (QUICK TASK ASSIGNMENT FOR DEVELOPMENT TEAM)         -->
<!-- ========================================================================= -->
<div class="modal fade" id="giveDevTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%) !important;">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fas fa-code fs-6"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-white mb-0">Give & Assign Development Task</h5>
                        <small class="text-white-50">Delegate directly to Abhay (Intern Frontend Developer)</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="giveDevTaskForm" onsubmit="submitGiveDevTaskForm(event)">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <?php if (!$isGlobalManager): ?>
                    <input type="hidden" name="department_id" value="2">
                <?php endif; ?>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-dark small fw-semibold">Task Title / Feature <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Implement responsive dashboard cards, Fix navbar dropdown, API integration" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-dark small fw-semibold">Priority <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select" required>
                                <option value="low">Low Priority</option>
                                <option value="medium" selected>Medium Priority</option>
                                <option value="high">High Priority</option>
                                <option value="urgent">Urgent Escalation</option>
                            </select>
                        </div>

                        <?php if ($isGlobalManager): ?>
                            <!-- Department selection with + Add Dept for Admin, Manager, and Product Manager -->
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label text-dark small fw-semibold mb-0">Department <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-xs btn-outline-primary fw-bold rounded-pill px-2 py-0.5" onclick="openQuickAddDepartmentModal('dashDevDept')">
                                        <i class="fas fa-plus me-1"></i>Add Dept
                                    </button>
                                </div>
                                <select name="department_id" id="dashDevDept" class="form-select" onchange="onDashDeptChanged('dashDevDept', 'dashDevProject')">
                                    <option value="0">Auto-Detect from Assignee</option>
                                    <?php foreach ($dashDepartments as $dept): ?>
                                        <option value="<?= $dept['id'] ?>" <?= (int)$dept['id'] === 2 ? 'selected' : '' ?>>
                                            <?= sanitize($dept['name']) ?> <?= !empty($dept['code']) ? '(' . sanitize($dept['code']) . ')' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="__ADD_NEW__" class="fw-bold text-primary">➕ + Add New Department...</option>
                                </select>
                            </div>
                        <?php endif; ?>

                        <!-- Linked Project with + Add Project for Admin, Manager, and Product Manager -->
                        <div class="<?= $isGlobalManager ? 'col-md-6' : 'col-md-6' ?>">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-dark small fw-semibold mb-0">Linked Project (Optional)</label>
                                <?php if ($isGlobalManager): ?>
                                    <button type="button" class="btn btn-xs btn-outline-primary fw-bold rounded-pill px-2 py-0.5" onclick="openQuickAddProjectModal('dashDevProject', 'dashDevDept')">
                                        <i class="fas fa-plus me-1"></i>Add Project
                                    </button>
                                <?php endif; ?>
                            </div>
                            <select name="project_id" id="dashDevProject" class="form-select" onchange="onDashProjectChanged('dashDevProject', 'dashDevDept')">
                                <option value="">None (Standalone Dev Deliverable)</option>
                                <?php foreach ($dashProjects as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-dept="<?= $p['department_id'] ?? '' ?>"><?= sanitize($p['name']) ?> <?= !empty($p['code']) ? '(' . sanitize($p['code']) . ')' : '' ?></option>
                                <?php endforeach; ?>
                                <?php if ($isGlobalManager): ?>
                                    <option value="__ADD_NEW__" class="fw-bold text-primary">➕ + Add New Project...</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Assign To Employee <span class="text-danger">*</span></label>
                            <select name="assigned_to" class="form-select" required onchange="onDashAssigneeChanged(this, 'dashDevDept')">
                                <?php if ($isDevLead): ?>
                                    <optgroup label="⭐ Development Team (Authorized Assignee)">
                                        <option value="9" data-dept="2" selected>Abhay (Intern Frontend Developer)</option>
                                    </optgroup>
                                <?php else: ?>
                                    <option value="">Select Employee...</option>
                                    <optgroup label="⭐ Development Team">
                                        <option value="9" data-dept="2" selected>Abhay (Intern Frontend Developer)</option>
                                        <option value="4" data-dept="2">Rahul (Senior Fullstack Developer)</option>
                                    </optgroup>
                                    <optgroup label="👥 All Other Employees">
                                        <?php foreach ($dashTeamMembers as $tm): ?>
                                            <?php if (!in_array((int)$tm['id'], [4, 9])): ?>
                                                <option value="<?= $tm['id'] ?>" data-dept="<?= $tm['department_id'] ?? '' ?>"><?= sanitize($tm['name']) ?> (<?= sanitize($tm['designation'] ?? 'Staff') ?>)</option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Estimated Working Hours</label>
                            <input type="number" step="0.5" name="estimated_hours" class="form-control" value="4.0" min="0.5">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Deadline Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="deadline" class="form-control" required value="<?= date('Y-m-d\TH:i', strtotime('+3 days 18:30')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-dark small fw-semibold">Task Description & Requirements</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Provide git branch name, PR requirements, wireframes, technical specs or frontend deliverables for Abhay..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm" id="btnSubmitGiveDevTask">
                        <i class="fas fa-check-circle me-1"></i>Assign Task to Abhay
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function submitGiveDevTaskForm(event) {
    event.preventDefault();
    const form = event.target;
    const submitBtn = document.getElementById('btnSubmitGiveDevTask');
    const title = form.querySelector('[name="title"]').value.trim();
    const assignedTo = form.querySelector('[name="assigned_to"]').value;

    if (!title || !assignedTo) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Required Fields',
                text: 'Please provide a task title and choose an assignee (Abhay).'
            });
        } else {
            alert('Please provide a task title and choose an assignee (Abhay).');
        }
        return;
    }

    const origBtnHtml = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Assigning Task...';

    const formData = new FormData(form);
    formData.append('action', 'create');

    fetch('<?= BASE_URL ?>/api/tasks?action=create', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin',
        body: formData
    })
    .then(res => {
        if (!res.ok) {
            throw new Error('Server responded with HTTP status ' + res.status);
        }
        return res.json();
    })
    .then(data => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = origBtnHtml;
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Task Assigned!',
                    text: data.message || 'Task successfully given to Abhay.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(data.message || 'Task assigned successfully!');
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Assignment Failed',
                    text: data.message || 'Could not assign task.'
                });
            } else {
                alert(data.message || 'Could not assign task.');
            }
        }
    })
    .catch(err => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = origBtnHtml;
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while creating the task: ' + err.message
            });
        } else {
            alert('An error occurred: ' + err.message);
        }
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Admin Executive Chart (Royal Blue #2563EB & Gold #F59E0B)
    const adminChartCtx = document.getElementById('adminExecutiveChart');
    if (adminChartCtx) {
        window.adminChart = new Chart(adminChartCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    {
                        label: 'Tasks Completed',
                        data: [12, 19, 15, 24, 28, 14, 8],
                        borderColor: '#2563EB',
                        backgroundColor: 'rgba(37, 99, 235, 0.12)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Hours Logged',
                        data: [64, 72, 70, 80, 85, 40, 20],
                        borderColor: '#F59E0B',
                        borderDash: [5, 5],
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top' } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: 'rgba(255, 255, 255, 0.1)' } }
                }
            }
        });
    }

    // 2. Manager Team Velocity, Priority & Task Distribution Charts (Emerald #10B981, Cyan #06B6D4, Teal #14B8A6, Orange #F97316)
    const mgrPriorityCtx = document.getElementById('mgrPriorityMiniChart');
    if (mgrPriorityCtx) {
        new Chart(mgrPriorityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Urgent', 'High', 'Medium', 'Low'],
                datasets: [{
                    data: [
                        <?= max(1, $mgrPriorityCounts['urgent']) ?>,
                        <?= max(2, $mgrPriorityCounts['high']) ?>,
                        <?= max(3, $mgrPriorityCounts['medium']) ?>,
                        <?= max(1, $mgrPriorityCounts['low']) ?>
                    ],
                    backgroundColor: ['#ef4444', '#f97316', '#06b6d4', '#10b981'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.label + ': ' + context.raw + ' tasks';
                            }
                        }
                    }
                },
                cutout: '68%'
            }
        });
    }

    const mgrVelCtx = document.getElementById('mgrTeamVelocityChart');
    if (mgrVelCtx) {
        new Chart(mgrVelCtx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Sprint Velocity',
                    data: [28, 35, 42, 38],
                    backgroundColor: '#10B981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false } }, y: { grid: { color: '#f1f5f9' } } }
            }
        });
    }

    const mgrDistCtx = document.getElementById('mgrTaskDistChart');
    if (mgrDistCtx) {
        new Chart(mgrDistCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Running', 'In Review', 'Completed'],
                datasets: [{
                    data: [<?= $mgrPendingTasksCount ?>, 4, 2, <?= $mgrCompletedTasksCount ?>],
                    backgroundColor: ['#f97316', '#06b6d4', '#14b8a6', '#10b981']
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'right' } }
            }
        });
    }

    // 3. Employee Productivity Line Chart (Purple #7C3AED & Indigo #6366F1 & Sky Blue #38BDF8)
    const empProdCtx = document.getElementById('empProductivityChart');
    if (empProdCtx) {
        new Chart(empProdCtx, {
            type: 'line',
            data: {
                labels: ['09:00', '11:00', '13:00', '15:00', '17:00', '18:30'],
                datasets: [{
                    label: 'Productivity Index (%)',
                    data: [80, 92, 88, 96, 94, 90],
                    borderColor: '#7C3AED',
                    backgroundColor: 'rgba(124, 58, 237, 0.12)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false } }, y: { min: 50, max: 100, grid: { color: '#f1f5f9' } } }
            }
        });
    }
});

// Admin Chart Timeframe Switcher
function updateAdminChart(tf) {
    document.querySelectorAll('#adminTimeframePills .timeframe-pill').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    if (!window.adminChart) return;
    
    if (tf === 'weekly') {
        window.adminChart.data.labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        window.adminChart.data.datasets[0].data = [12, 19, 15, 24, 28, 14, 8];
        window.adminChart.data.datasets[1].data = [64, 72, 70, 80, 85, 40, 20];
    } else if (tf === 'monthly') {
        window.adminChart.data.labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
        window.adminChart.data.datasets[0].data = [85, 98, 112, 125];
        window.adminChart.data.datasets[1].data = [320, 340, 360, 380];
    } else if (tf === 'yearly') {
        window.adminChart.data.labels = ['Q1', 'Q2', 'Q3', 'Q4'];
        window.adminChart.data.datasets[0].data = [320, 410, 480, 520];
        window.adminChart.data.datasets[1].data = [1200, 1450, 1600, 1750];
    }
    window.adminChart.update();
}

function openDashboardQuickEditSalary(userId, name, monthlySalary, allowance) {
    document.getElementById('dashEditSalUserId').value = userId;
    document.getElementById('dashEditSalEmpName').value = name;
    document.getElementById('dashEditSalMonthly').value = monthlySalary;
    document.getElementById('dashEditSalAllowance').value = allowance;
    new bootstrap.Modal(document.getElementById('dashQuickEditSalaryModal')).show();
}

// =========================================================================
// EMPLOYEE DASHBOARD: SHIFT TIMERS, MEETINGS & ACTIVITY SWITCHER ENGINE
// =========================================================================
let dashActivityStartTs = <?= $currentAct ? strtotime($currentAct['start_time']) : 0 ?>;
let dashShiftStartTs = <?= ($isShiftActive && $myAttendance && !empty($myAttendance['clock_in'])) ? strtotime($shiftClockInStr) : 0 ?>;
let dashPendingMeeting = <?= json_encode($dashPendingMeetingAlert, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
let dashMeetingsList = <?= json_encode($dashMeetings, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?> || [];
const currentDashboardUserId = <?= (int)$userId ?>;

document.addEventListener('DOMContentLoaded', function() {
    initDashISTTimer();
    
    // Initial check for reason container
    const initialInput = document.getElementById('dashSelectedActivityTypeInput');
    if (initialInput) {
        toggleDashReasonContainer(initialInput.value);
    }
    
    // Poll for meeting invitations every 10 seconds
    setInterval(checkDashboardMeetingRequest, 10000);
});

// Live Shift & Activity IST Timer
function initDashISTTimer() {
    function tick() {
        const nowSecs = Math.floor(Date.now() / 1000);
        
        // Live shift elapsed timer
        if (dashShiftStartTs > 0) {
            const shiftElapsed = Math.max(0, nowSecs - dashShiftStartTs);
            const shiftTimerEl = document.getElementById('empLiveShiftTimer');
            if (shiftTimerEl) {
                shiftTimerEl.textContent = formatDurationSeconds(shiftElapsed);
            }
            const remainingHoursEl = document.getElementById('empRemainingHoursVal');
            if (remainingHoursEl) {
                const remaining = Math.max(0, 31800 - shiftElapsed);
                remainingHoursEl.textContent = formatDurationSeconds(remaining);
            }
        }
        
        // Live activity session timer
        if (dashActivityStartTs > 0) {
            const actElapsed = Math.max(0, nowSecs - dashActivityStartTs);
            const actTimerEl = document.getElementById('dashBannerLiveActivityTimer');
            if (actTimerEl) {
                actTimerEl.textContent = formatHMS(actElapsed);
            }
        }
    }
    tick();
    setInterval(tick, 1000);
}

function formatDurationSeconds(secs) {
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = secs % 60;
    return `${h}h ${m}m ${s}s`;
}

function formatHMS(secs) {
    const h = String(Math.floor(secs / 3600)).padStart(2, '0');
    const m = String(Math.floor((secs % 3600) / 60)).padStart(2, '0');
    const s = String(secs % 60).padStart(2, '0');
    return `${h}:${m}:${s}`;
}

// Poll Active / Pending Meeting Requests
function checkDashboardMeetingRequest(isManual = false) {
    fetch(resolveApiUrl('/api/meetings?action=active_request'))
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data && res.data.has_pending_request && res.data.meeting) {
                const m = res.data.meeting;
                dashPendingMeeting = m;
                
                const banner = document.getElementById('dashboardPendingMeetingBanner');
                if (banner) {
                    banner.style.display = 'block';
                    banner.setAttribute('data-meeting-id', m.id);
                    
                    const avatarEl = document.getElementById('dashPendingBannerAvatar');
                    if (avatarEl && m.caller_avatar) avatarEl.src = m.caller_avatar;
                    
                    const callerEl = document.getElementById('dashPendingBannerCaller');
                    if (callerEl) callerEl.textContent = m.caller_name || 'Boss / Admin';
                    
                    const titleEl = document.getElementById('dashPendingBannerTitle');
                    if (titleEl) titleEl.textContent = m.title || 'Meeting Invitation';
                    
                    const timeEl = document.getElementById('dashPendingBannerTime');
                    if (timeEl) timeEl.textContent = m.start_time || '--';
                    
                    const durEl = document.getElementById('dashPendingBannerDuration');
                    if (durEl) durEl.textContent = m.duration || 30;
                    
                    const clientBadge = document.getElementById('dashPendingBannerClientBadge');
                    if (clientBadge) {
                        if (m.client_name) {
                            clientBadge.style.display = 'inline-block';
                            clientBadge.innerHTML = `<i class="fas fa-handshake me-1"></i>Client: ${escapeHtml(m.client_name)}`;
                        } else {
                            clientBadge.style.display = 'none';
                        }
                    }
                    
                    const linkEl = document.getElementById('dashPendingBannerLink');
                    if (linkEl) {
                        if (m.meeting_link) {
                            linkEl.href = m.meeting_link;
                            linkEl.innerHTML = `<i class="fas fa-link me-1"></i>${escapeHtml(m.meeting_link)}`;
                            linkEl.style.display = 'inline';
                        } else {
                            linkEl.style.display = 'none';
                        }
                    }

                    const descEl = document.getElementById('dashPendingBannerDesc');
                    if (descEl) {
                        if (m.description) {
                            descEl.innerHTML = `<i class="fas fa-align-left me-1"></i>${escapeHtml(m.description)}`;
                            descEl.style.display = 'block';
                        } else {
                            descEl.style.display = 'none';
                        }
                    }
                }
                
                if (isManual) {
                    Swal.fire({
                        title: 'Meeting Request Active!',
                        text: `You have a pending meeting invitation from ${m.caller_name}: "${m.title}"`,
                        icon: 'info',
                        confirmButtonText: 'View Details'
                    });
                }
            } else {
                const banner = document.getElementById('dashboardPendingMeetingBanner');
                if (banner && dashPendingMeeting && !isManual) {
                    banner.style.display = 'none';
                    dashPendingMeeting = null;
                }
                if (isManual) {
                    location.reload();
                }
            }
        })
        .catch(err => {
            if (isManual) {
                console.error('Error polling meeting request:', err);
            }
        });
}

function getDashPendingMeetingId() {
    if (dashPendingMeeting && dashPendingMeeting.id) return parseInt(dashPendingMeeting.id);
    const banner = document.getElementById('dashboardPendingMeetingBanner');
    if (banner && banner.getAttribute('data-meeting-id')) {
        const bid = parseInt(banner.getAttribute('data-meeting-id'));
        if (bid) return bid;
    }
    return null;
}

// Respond to Meeting from Banner
function respondToDashPendingMeeting(action) {
    const meetingId = getDashPendingMeetingId();
    if (!meetingId) {
        Swal.fire('Error', 'No active meeting request found.', 'error');
        return;
    }
    if (action === 'busy') {
        openDashBusyModal(meetingId);
        return;
    }
    respondToDashMeetingById(meetingId, action, dashPendingMeeting);
}

function openDashBusyModal(meetingId = null) {
    const mid = meetingId || getDashPendingMeetingId();
    if (!mid) {
        Swal.fire('Notice', 'No active meeting request found.', 'info');
        return;
    }
    document.getElementById('dashMeetingBusyMeetingId').value = mid;

    const m = (dashPendingMeeting && parseInt(dashPendingMeeting.id) === parseInt(mid))
        ? dashPendingMeeting
        : ((window.dashMeetingsList && window.dashMeetingsList.find(x => parseInt(x.id) === parseInt(mid))) || dashPendingMeeting || {});

    const callerText = m.caller_name || document.getElementById('dashPendingBannerCaller')?.textContent || 'Organizer';
    const titleText = m.title || document.getElementById('dashPendingBannerTitle')?.textContent || 'Meeting';
    
    document.getElementById('dashMeetingBusyTargetText').textContent = `${titleText} (from ${callerText})`;
    
    if (m.client_name) {
        document.getElementById('dashMeetingBusyClientName').value = m.client_name;
    } else {
        document.getElementById('dashMeetingBusyClientName').value = '';
    }
    if (m.project_id) {
        document.getElementById('dashMeetingBusyProjectId').value = m.project_id;
    } else {
        document.getElementById('dashMeetingBusyProjectId').value = '';
    }

    const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('dashMeetingBusyModal'));
    modal.show();
}

function submitDashMeetingBusyResponse(e) {
    e.preventDefault();
    const meetingId = document.getElementById('dashMeetingBusyMeetingId').value;
    const category = document.getElementById('dashMeetingBusyCategory').value;
    const clientName = document.getElementById('dashMeetingBusyClientName').value.trim();
    const availTime = document.getElementById('dashMeetingBusyAvailability').value;
    const notesInput = document.getElementById('dashMeetingBusyNotes').value.trim();
    const projectId = document.getElementById('dashMeetingBusyProjectId').value;
    const taskId = document.getElementById('dashMeetingBusyTaskId').value;
    const updateWorkday = document.getElementById('dashMeetingBusyUpdateWorkdayCheck').checked;

    let fullNotes = availTime ? `[Available: ${availTime}] ` : '';
    if (notesInput) fullNotes += notesInput;

    const btn = document.getElementById('submitDashMeetingBusyBtn');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    }

    sendDashMeetingResponse(meetingId, 'busy', fullNotes, updateWorkday ? category : '', clientName, projectId, taskId);
}

// Respond to Meeting by ID
function respondToDashMeetingById(meetingId, action, meetingObj = null) {
    const isBusy = (action === 'busy');
    const isDecline = (action === 'decline' || action === 'declined');
    
    if (isBusy) {
        openDashBusyModal(meetingId);
        return;
    }

    if (isDecline) {
        Swal.fire({
            title: 'Decline Meeting?',
            text: 'Please enter a reason for declining:',
            input: 'text',
            inputPlaceholder: 'e.g. Schedule conflict with client review',
            showCancelButton: true,
            confirmButtonText: 'Decline Meeting',
            confirmButtonColor: '#ef4444'
        }).then(result => {
            if (result.isConfirmed) {
                sendDashMeetingResponse(meetingId, 'declined', result.value || '');
            }
        });
        return;
    }
    
    // Accept & Join
    sendDashMeetingResponse(meetingId, 'accepted', '');
}

function sendDashMeetingResponse(meetingId, responseStatus, notes, busyReason = '', clientName = '', projectId = '', taskId = '') {
    const formData = new URLSearchParams();
    formData.append('meeting_id', meetingId);
    formData.append('response', responseStatus);
    formData.append('response_status', responseStatus);
    formData.append('response_notes', notes || '');
    if (busyReason) formData.append('busy_reason', busyReason);
    if (clientName) formData.append('client_name', clientName);
    if (projectId) formData.append('project_id', projectId);
    if (taskId) formData.append('task_id', taskId);
    formData.append('csrf_token', CSRF_TOKEN);
    
    // Instant user feedback
    Swal.fire({
        title: 'Recording Response...',
        text: 'Please wait while your meeting response is processed.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(resolveApiUrl('/api/meetings?action=respond'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
    })
    .then(res => res.json())
    .then(res => {
        try {
            const busyModalEl = document.getElementById('dashMeetingBusyModal');
            if (busyModalEl) {
                bootstrap.Modal.getInstance(busyModalEl)?.hide();
            }
        } catch (e) {}

        const busyBtn = document.getElementById('submitDashMeetingBusyBtn');
        if (busyBtn) {
            busyBtn.disabled = false;
            busyBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Busy Note & Update Status';
        }

        if (res.success) {
            if (responseStatus === 'accepted' || responseStatus === 'accept') {
                if (res.data && res.data.meeting_link) {
                    window.open(res.data.meeting_link, '_blank');
                }
                Swal.fire({
                    title: 'Meeting Accepted!',
                    text: 'You joined the meeting. Status updated to In Meeting.',
                    icon: 'success',
                    confirmButtonText: 'Continue',
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    location.reload();
                });
            } else if (responseStatus === 'busy') {
                Swal.fire({
                    title: 'Busy Status Saved',
                    text: res.message || 'Your status note has been communicated to the meeting organizer.',
                    icon: 'info',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#f59e0b'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Meeting Declined',
                    text: res.message || 'The organizer has been notified.',
                    icon: 'info',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#64748b'
                }).then(() => {
                    location.reload();
                });
            }
        } else {
            Swal.fire('Error', res.message || 'Failed to update meeting response', 'error');
        }
    })
    .catch(err => {
        const busyBtn = document.getElementById('submitDashMeetingBusyBtn');
        if (busyBtn) {
            busyBtn.disabled = false;
            busyBtn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Busy Note & Update Status';
        }
        Swal.fire('Error', 'Network error: ' + err.message, 'error');
    });
}

// Start / End Meeting Actions
function startDashMeetingActivity(meetingId, title) {
    Swal.fire({
        title: 'Start Meeting Session?',
        text: `Switch your live status to Meeting for "${title}"? Meeting time is tracked as productive working time.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Start Meeting',
        confirmButtonColor: '#8b5cf6'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(resolveApiUrl('/api/activities?action=change'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `activity_type=Meeting&notes=${encodeURIComponent('In meeting: ' + title)}&csrf_token=${CSRF_TOKEN}`
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Started', 'Meeting status is now active.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}

function endDashCurrentMeeting(meetingId) {
    Swal.fire({
        title: 'End Meeting?',
        text: 'Conclude this meeting session and resume normal work activity.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, End Meeting',
        confirmButtonColor: '#ef4444'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(resolveApiUrl('/api/meetings?action=end'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `meeting_id=${meetingId}&csrf_token=${CSRF_TOKEN}`
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Completed', 'Meeting marked as completed.', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}

// Schedule / Request Meeting Modal & Form
function openDashScheduleMeetingModal() {
    const modalEl = document.getElementById('dashScheduleMeetingModal');
    if (modalEl) {
        document.getElementById('dashScheduleMeetingForm').reset();
        document.getElementById('dashScheduleMeetingDate').value = new Date().toISOString().split('T')[0];
        const now = new Date();
        const curTime = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
        document.getElementById('dashScheduleMeetingStartTime').value = curTime;
        generateDashMeetLink('dashScheduleMeetingLink');
        new bootstrap.Modal(modalEl).show();
    }
}

function handleDashMeetingTypeChange(type) {
    const clientNameInput = document.getElementById('dashScheduleMeetingClientName');
    if (clientNameInput && type === 'client') {
        clientNameInput.focus();
    }
}

function generateDashMeetLink(targetId) {
    const chars = 'abcdefghijklmnopqrstuvwxyz';
    let code1 = '', code2 = '', code3 = '';
    for (let i = 0; i < 3; i++) code1 += chars.charAt(Math.floor(Math.random() * chars.length));
    for (let i = 0; i < 4; i++) code2 += chars.charAt(Math.floor(Math.random() * chars.length));
    for (let i = 0; i < 3; i++) code3 += chars.charAt(Math.floor(Math.random() * chars.length));
    const meetUrl = `https://meet.google.com/${code1}-${code2}-${code3}`;
    const target = document.getElementById(targetId);
    if (target) target.value = meetUrl;
}

function submitDashScheduleMeeting(e) {
    e.preventDefault();
    const btn = document.getElementById('submitDashScheduleMeetingBtn');
    if (btn) btn.disabled = true;
    
    const form = document.getElementById('dashScheduleMeetingForm');
    const formData = new FormData(form);
    
    fetch(resolveApiUrl('/api/meetings?action=create'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            Swal.fire('Meeting Requested!', res.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Scheduling Error', res.message || 'Failed to schedule meeting', 'error');
            if (btn) btn.disabled = false;
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Network error: ' + err.message, 'error');
        if (btn) btn.disabled = false;
    });
}

// Edit Meeting Modal & Form
function editDashCurrentPendingMeeting() {
    const mid = getDashPendingMeetingId();
    const m = (dashPendingMeeting && (!mid || parseInt(dashPendingMeeting.id) === parseInt(mid)))
        ? dashPendingMeeting
        : ((window.dashMeetingsList && window.dashMeetingsList.find(x => parseInt(x.id) === parseInt(mid))) || dashPendingMeeting);

    if (m) {
        openDashEditMeetingModal(m);
    } else {
        Swal.fire('Notice', 'No meeting selected for editing.', 'info');
    }
}

function openDashEditMeetingModal(m) {
    if (typeof m === 'number' || typeof m === 'string') {
        const mid = parseInt(m);
        m = (window.dashMeetingsList && window.dashMeetingsList.find(x => parseInt(x.id) === mid)) || (dashPendingMeeting && parseInt(dashPendingMeeting.id) === mid ? dashPendingMeeting : null);
    }
    if (!m) {
        Swal.fire('Notice', 'Meeting details could not be found for editing.', 'info');
        return;
    }
    document.getElementById('dashEditMeetingId').value = m.id || '';
    document.getElementById('dashEditMeetingTitle').value = m.title || '';
    document.getElementById('dashEditMeetingType').value = m.meeting_type || 'client';
    document.getElementById('dashEditMeetingClientName').value = m.client_name || '';
    document.getElementById('dashEditMeetingProjectId').value = m.project_id || '';
    document.getElementById('dashEditMeetingDate').value = m.meeting_date || '';
    document.getElementById('dashEditMeetingStartTime').value = m.start_time || '';
    document.getElementById('dashEditMeetingDuration').value = m.duration || '30';
    document.getElementById('dashEditMeetingLink').value = m.meeting_link || '';
    document.getElementById('dashEditMeetingDescription').value = m.description || '';
    document.getElementById('dashEditMeetingResponseNotes').value = m.response_notes || '';
    
    bootstrap.Modal.getOrCreateInstance(document.getElementById('dashEditMeetingModal')).show();
}

function submitDashEditMeeting(e) {
    e.preventDefault();
    const btn = document.getElementById('submitDashEditMeetingBtn');
    if (btn) btn.disabled = true;
    
    const form = document.getElementById('dashEditMeetingForm');
    const formData = new FormData(form);
    
    fetch(resolveApiUrl('/api/meetings?action=update'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            Swal.fire('Updated!', res.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Error', res.message || 'Failed to update meeting', 'error');
            if (btn) btn.disabled = false;
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Network error: ' + err.message, 'error');
        if (btn) btn.disabled = false;
    });
}

// Activity Switcher
function selectDashActivityPill(activityType) {
    const input = document.getElementById('dashSelectedActivityTypeInput');
    if (input) input.value = activityType;
    
    document.querySelectorAll('.dash-activity-pill-btn').forEach(btn => {
        if (btn.getAttribute('data-activity') === activityType) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
    
    toggleDashReasonContainer(activityType);
}

function toggleDashReasonContainer(activityType) {
    const lower = (activityType || '').toLowerCase();
    const container = document.getElementById('dashWaitingBlockedReasonContainer');
    if (!container) return;
    
    if (lower.includes('waiting') || lower.includes('blocked') || lower.includes('technical')) {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

function filterDashTasksByProject() {
    const projSelect = document.getElementById('dashActivityProjectSelect');
    const taskSelect = document.getElementById('dashActivityTaskSelect');
    if (!projSelect || !taskSelect) return;
    
    const selectedProjectId = projSelect.value;
    for (let i = 1; i < taskSelect.options.length; i++) {
        const opt = taskSelect.options[i];
        const optProjId = opt.getAttribute('data-project');
        if (!selectedProjectId || optProjId === selectedProjectId) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    }
}

function submitDashActivityChange(e) {
    e.preventDefault();
    const btn = document.getElementById('dashBtnUpdateActivity');
    if (btn) btn.disabled = true;
    
    const form = document.getElementById('dashActivityChangeForm');
    const formData = new FormData(form);
    formData.append('csrf_token', CSRF_TOKEN);
    
    fetch(resolveApiUrl('/api/activities?action=change'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            Swal.fire({
                title: 'Activity Updated!',
                text: res.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Update Failed', res.message || 'Could not update activity', 'warning');
            if (btn) btn.disabled = false;
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Network error: ' + err.message, 'error');
        if (btn) btn.disabled = false;
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#039;');
}

function switchMobileDashboardTab(tabName) {
    // 1. Update active tab pill buttons
    const pillButtons = document.querySelectorAll('.app-segmented-tabs .app-tab-pill');
    pillButtons.forEach(btn => {
        const onclickAttr = btn.getAttribute('onclick') || '';
        if (onclickAttr.includes("'" + tabName + "'")) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // 2. Filter sections with data-dash-section on mobile
    const sections = document.querySelectorAll('[data-dash-section]');
    sections.forEach(sec => {
        const secType = sec.getAttribute('data-dash-section');
        if (tabName === 'all' || secType === tabName) {
            sec.style.removeProperty('display');
            sec.classList.remove('d-none');
        } else {
            sec.style.display = 'none';
        }
    });

    // 3. Smooth scroll to targeted section
    if (tabName === 'approvals') {
        const approvalEl = document.getElementById('taskApprovalQueueSection');
        if (approvalEl) {
            approvalEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}

function promptApproveEmergency(requestId) {
    Swal.fire({
        title: 'Approve Emergency Request?',
        text: 'Optional reviewer remarks / guidance:',
        input: 'textarea',
        inputPlaceholder: 'Enter remarks or leave blank...',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check me-1"></i>Approve Request',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b'
    }).then(r => {
        if (r.isConfirmed) {
            const formData = new FormData();
            formData.append('action', 'approve');
            formData.append('request_id', requestId);
            formData.append('reviewer_comment', r.value || '');
            formData.append('csrf_token', CSRF_TOKEN);

            fetch('<?= BASE_URL ?>/api/emergency_requests', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Approved!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}

function promptRejectEmergency(requestId) {
    Swal.fire({
        title: 'Reject Emergency Request?',
        text: 'Enter required rejection reason:',
        input: 'textarea',
        inputPlaceholder: 'State why this emergency escalation cannot be approved...',
        showCancelButton: true,
        confirmButtonText: 'Reject Request',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        inputValidator: (val) => (!val || !val.trim()) ? 'Rejection reason is mandatory.' : null
    }).then(r => {
        if (r.isConfirmed && r.value) {
            const formData = new FormData();
            formData.append('action', 'reject');
            formData.append('request_id', requestId);
            formData.append('rejection_reason', r.value.trim());
            formData.append('csrf_token', CSRF_TOKEN);

            fetch('<?= BASE_URL ?>/api/emergency_requests', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Rejected', res.message, 'info').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}

function promptResolveFlag(flagId) {
    Swal.fire({
        title: 'Mark Flag as Resolved',
        text: 'Enter resolution details or how the missing requirement was provided:',
        input: 'textarea',
        inputPlaceholder: 'e.g., Missing credentials uploaded to secure vault / client provided phone number...',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check-circle me-1"></i>Resolve Flag',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        inputValidator: (val) => (!val || !val.trim()) ? 'Resolution message is mandatory.' : null
    }).then(r => {
        if (r.isConfirmed && r.value) {
            const formData = new FormData();
            formData.append('action', 'resolve');
            formData.append('flag_id', flagId);
            formData.append('resolution_message', r.value.trim());
            formData.append('csrf_token', CSRF_TOKEN);

            fetch('<?= BASE_URL ?>/api/task_flags', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Resolved!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}
</script>

<!-- ========================================================================= -->
<!-- MODAL: QUICK ADD DEPARTMENT (ADMIN, MANAGER, PRODUCT MANAGER)            -->
<!-- ========================================================================= -->
<div class="modal fade" id="quickAddDepartmentModal" tabindex="-1" aria-hidden="true" style="z-index: 1075;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-primary text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;">
                <h6 class="modal-title fw-bold text-white mb-0">
                    <i class="fas fa-building me-2"></i>Create New Department
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickAddDepartmentForm" onsubmit="submitQuickAddDepartment(event)">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="action" value="create">
                <input type="hidden" id="quickDeptTargetSelect" value="">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-dark small fw-semibold">Department Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="quickDeptNameInput" class="form-control" placeholder="e.g. Mobile Apps, Performance Marketing" required oninput="autoSuggestDeptCode(this.value)">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-dark small fw-semibold">Department Code (Short 3-4 letters)</label>
                            <input type="text" name="code" id="quickDeptCodeInput" class="form-control text-uppercase" placeholder="e.g. MOB, PMK" maxlength="10">
                            <small class="text-muted fs-9">Optional. Auto-generated from name if left empty.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-dark small fw-semibold">Description (Optional)</label>
                            <textarea name="description" id="quickDeptDescInput" class="form-control" rows="2" placeholder="Brief scope and focus of this department..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold" id="btnSubmitQuickDept">
                        <i class="fas fa-check me-1"></i>Save & Select Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: QUICK ADD PROJECT (ADMIN, MANAGER, PRODUCT MANAGER)               -->
<!-- ========================================================================= -->
<div class="modal fade" id="quickAddProjectModal" tabindex="-1" aria-hidden="true" style="z-index: 1075;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 text-white" style="background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%) !important;">
                <h6 class="modal-title fw-bold text-white mb-0">
                    <i class="fas fa-folder-plus me-2"></i>Create New Project
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickAddProjectForm" onsubmit="submitQuickAddProject(event)">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="action" value="create">
                <input type="hidden" id="quickProjTargetSelect" value="">
                <input type="hidden" id="quickProjTargetDeptSelect" value="">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label text-dark small fw-semibold">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="quickProjNameInput" class="form-control" placeholder="e.g. Mobile App Redesign, Client SEO Q4" required oninput="autoSuggestProjCode(this.value)">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Project Code (Optional)</label>
                            <input type="text" name="code" id="quickProjCodeInput" class="form-control text-uppercase" placeholder="e.g. PRJ-MOB-01" maxlength="30">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Department</label>
                            <select name="department_id" id="quickProjDeptSelect" class="form-select">
                                <option value="1">Administration</option>
                                <?php foreach ($dashDepartments as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark small fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="in_progress" selected>In Progress</option>
                                <option value="active">Active</option>
                                <option value="planning">Planning</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-dark small fw-semibold">Description (Optional)</label>
                            <textarea name="description" id="quickProjDescInput" class="form-control" rows="2" placeholder="Key deliverables, client scope, milestones..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2 px-4">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold" id="btnSubmitQuickProj">
                        <i class="fas fa-check me-1"></i>Save & Select Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function autoSuggestDeptCode(name) {
    const codeInp = document.getElementById('quickDeptCodeInput');
    if (!codeInp || codeInp.dataset.touched === 'true') return;
    const clean = name.replace(/[^a-zA-Z]/g, '').toUpperCase();
    codeInp.value = clean.substring(0, 3);
}

function autoSuggestProjCode(name) {
    const codeInp = document.getElementById('quickProjCodeInput');
    if (!codeInp || codeInp.dataset.touched === 'true') return;
    const clean = name.replace(/[^a-zA-Z]/g, '').toUpperCase();
    if (clean.length > 0) {
        codeInp.value = 'PRJ-' + clean.substring(0, 3) + '-' + Math.floor(10 + Math.random() * 90);
    } else {
        codeInp.value = '';
    }
}

function openQuickAddDepartmentModal(targetSelectId) {
    const targetInput = document.getElementById('quickDeptTargetSelect');
    if (targetInput) targetInput.value = targetSelectId || 'dashSeoDept';
    const form = document.getElementById('quickAddDepartmentForm');
    if (form) {
        form.reset();
        const codeInp = document.getElementById('quickDeptCodeInput');
        if (codeInp) delete codeInp.dataset.touched;
    }
    const modalEl = document.getElementById('quickAddDepartmentModal');
    if (modalEl) {
        const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;
        if (bsModal) bsModal.show();
    }
}

function submitQuickAddDepartment(e) {
    e.preventDefault();
    const form = document.getElementById('quickAddDepartmentForm');
    const btn = document.getElementById('btnSubmitQuickDept');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    }

    const formData = new FormData(form);
    formData.append('csrf_token', CSRF_TOKEN);

    fetch('<?= BASE_URL ?>/api/departments', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(res => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Save & Select Department';
        }
        if (res.success && res.data) {
            const deptId = res.data.department_id;
            const deptName = res.data.name;
            const deptCode = res.data.code ? ` (${res.data.code})` : '';
            const targetSelectId = document.getElementById('quickDeptTargetSelect')?.value || 'dashSeoDept';

            // Append to all department select elements on dashboard
            const deptSelects = [
                document.getElementById('dashSeoDept'),
                document.getElementById('dashDevDept'),
                document.getElementById('quickProjDeptSelect')
            ];

            deptSelects.forEach(selectEl => {
                if (selectEl) {
                    const opt = document.createElement('option');
                    opt.value = deptId;
                    opt.textContent = deptName + deptCode;
                    opt.setAttribute('data-code', res.data.code || '');
                    
                    const addNewOpt = selectEl.querySelector('option[value="__ADD_NEW__"]');
                    if (addNewOpt) {
                        selectEl.insertBefore(opt, addNewOpt);
                    } else {
                        selectEl.appendChild(opt);
                    }
                }
            });

            // Select in the target select
            const activeSelect = document.getElementById(targetSelectId);
            if (activeSelect) {
                activeSelect.value = String(deptId);
            }

            const modalEl = document.getElementById('quickAddDepartmentModal');
            if (modalEl) {
                const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? bootstrap.Modal.getInstance(modalEl) : null;
                if (bsModal) bsModal.hide();
            }

            setTimeout(() => {
                if (document.querySelector('.modal.show')) {
                    document.body.classList.add('modal-open');
                }
            }, 350);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Department Added!',
                    text: `Department "${deptName}" created and selected!`,
                    icon: 'success',
                    timer: 1600,
                    showConfirmButton: false
                });
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', res.message || 'Could not create department', 'error');
            } else {
                alert(res.message || 'Could not create department');
            }
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Save & Select Department';
        }
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Network or server error: ' + err.message, 'error');
        } else {
            alert('Network or server error: ' + err.message);
        }
    });
}

function openQuickAddProjectModal(targetSelectId, targetDeptSelectId) {
    const targetInput = document.getElementById('quickProjTargetSelect');
    if (targetInput) targetInput.value = targetSelectId || 'dashSeoProject';
    const targetDeptInput = document.getElementById('quickProjTargetDeptSelect');
    if (targetDeptInput) targetDeptInput.value = targetDeptSelectId || '';

    const form = document.getElementById('quickAddProjectForm');
    if (form) {
        form.reset();
        const codeInp = document.getElementById('quickProjCodeInput');
        if (codeInp) delete codeInp.dataset.touched;
    }

    // Sync department if selected in task modal
    let currentDeptId = 0;
    if (targetDeptSelectId) {
        const dEl = document.getElementById(targetDeptSelectId);
        if (dEl) currentDeptId = dEl.value;
    }

    const projDeptSelect = document.getElementById('quickProjDeptSelect');
    if (projDeptSelect && currentDeptId && currentDeptId !== '0' && currentDeptId !== '__ADD_NEW__') {
        projDeptSelect.value = currentDeptId;
    }

    const modalEl = document.getElementById('quickAddProjectModal');
    if (modalEl) {
        const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? bootstrap.Modal.getOrCreateInstance(modalEl) : null;
        if (bsModal) bsModal.show();
    }
}

function submitQuickAddProject(e) {
    e.preventDefault();
    const form = document.getElementById('quickAddProjectForm');
    const btn = document.getElementById('btnSubmitQuickProj');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
    }

    const formData = new FormData(form);
    formData.append('csrf_token', CSRF_TOKEN);

    fetch('<?= BASE_URL ?>/api/projects', { method: 'POST', body: formData })
    .then(res => res.json())
    .then(res => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Save & Select Project';
        }
        if (res.success && res.data) {
            const projId = res.data.project_id;
            const projName = res.data.name;
            const projCode = res.data.code ? ` (${res.data.code})` : '';
            const deptId = form.querySelector('select[name="department_id"]')?.value || '';
            const targetSelectId = document.getElementById('quickProjTargetSelect')?.value || 'dashSeoProject';

            // Append to all project select elements
            const projSelects = [
                document.getElementById('dashSeoProject'),
                document.getElementById('dashDevProject')
            ];

            projSelects.forEach(selectEl => {
                if (selectEl) {
                    const opt = document.createElement('option');
                    opt.value = projId;
                    opt.textContent = projName + projCode;
                    opt.setAttribute('data-dept', deptId);
                    opt.setAttribute('data-code', res.data.code || '');

                    const addNewOpt = selectEl.querySelector('option[value="__ADD_NEW__"]');
                    if (addNewOpt) {
                        selectEl.insertBefore(opt, addNewOpt);
                    } else {
                        selectEl.appendChild(opt);
                    }
                }
            });

            // Select in current target select
            const activeSelect = document.getElementById(targetSelectId);
            if (activeSelect) {
                activeSelect.value = String(projId);
            }

            const modalEl = document.getElementById('quickAddProjectModal');
            if (modalEl) {
                const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? bootstrap.Modal.getInstance(modalEl) : null;
                if (bsModal) bsModal.hide();
            }

            setTimeout(() => {
                if (document.querySelector('.modal.show')) {
                    document.body.classList.add('modal-open');
                }
            }, 350);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Project Added!',
                    text: `Project "${projName}" created and selected!`,
                    icon: 'success',
                    timer: 1600,
                    showConfirmButton: false
                });
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', res.message || 'Could not create project', 'error');
            } else {
                alert(res.message || 'Could not create project');
            }
        }
    })
    .catch(err => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Save & Select Project';
        }
        if (typeof Swal !== 'undefined') {
            Swal.fire('Error', 'Network or server error: ' + err.message, 'error');
        } else {
            alert('Network or server error: ' + err.message);
        }
    });
}

function onDashDeptChanged(deptSelectId, projectSelectId) {
    const deptEl = document.getElementById(deptSelectId);
    if (!deptEl) return;
    if (deptEl.value === '__ADD_NEW__') {
        deptEl.value = '0';
        openQuickAddDepartmentModal(deptSelectId);
    }
}

function onDashProjectChanged(projectSelectId, deptSelectId) {
    const projEl = document.getElementById(projectSelectId);
    if (!projEl) return;
    if (projEl.value === '__ADD_NEW__') {
        projEl.value = '';
        openQuickAddProjectModal(projectSelectId, deptSelectId);
        return;
    }
    const selectedOpt = projEl.options[projEl.selectedIndex];
    const deptId = selectedOpt ? selectedOpt.getAttribute('data-dept') : '';
    if (deptId && deptSelectId) {
        const deptEl = document.getElementById(deptSelectId);
        if (deptEl && (deptEl.value === '0' || !deptEl.value)) {
            deptEl.value = deptId;
        }
    }
}

function onDashAssigneeChanged(assigneeSelect, deptSelectId) {
    if (!assigneeSelect || !deptSelectId) return;
    const deptEl = document.getElementById(deptSelectId);
    if (!deptEl || (deptEl.value !== '0' && deptEl.value !== '')) return;
    const selectedOpt = assigneeSelect.options[assigneeSelect.selectedIndex];
    const userDept = selectedOpt ? selectedOpt.getAttribute('data-dept') : '';
    if (userDept && parseInt(userDept, 10) > 0) {
        deptEl.value = userDept;
    }
}
</script>

