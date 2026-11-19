<?php
/**
 * Task Management & Work Queue - WorkFlow Pro
 * Complete Task Management with live timer, approval workflows, emergency escalation, and task flags.
 */

$pageTitle = "Task Management";
$user = requireLogin();
$db = Database::getConnection();

// Ensure all database schemas and columns exist
ensureTaskApprovalSchema($db);
ensureEmergencyAndFlagSchema($db);

$canManageTasks = canCreateOrEditTasks($user);
$userCategory = getUserRoleCategory($user);
$isGlobalManager = in_array($userCategory, ['admin', 'manager', 'product_manager']);
$userId = (int)$user['id'];
$userDeptId = (int)($user['department_id'] ?? 1);
$actualRole = strtolower($user['role_name'] ?? '');
$userName = strtolower(trim($user['name'] ?? ''));
$isSeoLead = ($userId === 5 || $userName === 'uvaish' || $actualRole === 'seo_manager');
$isDevLead = ($userId === 4 || $userName === 'rahul' || $actualRole === 'senior_developer');

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

// Subqueries for live emergency requests and blocker flags
$emgFlagFields = "
    (SELECT status FROM emergency_task_requests WHERE task_id = t.id ORDER BY id DESC LIMIT 1) as emg_status,
    (SELECT reason FROM emergency_task_requests WHERE task_id = t.id ORDER BY id DESC LIMIT 1) as emg_reason,
    (SELECT status FROM task_flags WHERE task_id = t.id ORDER BY id DESC LIMIT 1) as flag_status,
    (SELECT category FROM task_flags WHERE task_id = t.id ORDER BY id DESC LIMIT 1) as flag_category,
    (SELECT impact FROM task_flags WHERE task_id = t.id ORDER BY id DESC LIMIT 1) as flag_impact
";

$tasks = [];
$visCondition = getTaskVisibilityCondition($user, 't');
$isUnrestricted = ($visCondition['sql'] === '1=1');

try {
    if ($isUnrestricted) {
        $stmt = $db->prepare("
            SELECT t.*, p.name as project_name, p.code as project_code, d.name as department_name, 
                   u_to.name as assignee_name, u_to.avatar as assignee_avatar, u_to.designation as assignee_designation,
                   u_by.name as creator_name,
                   {$emgFlagFields}
            FROM tasks t
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN departments d ON t.department_id = d.id
            LEFT JOIN users u_to ON (t.assigned_to = u_to.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u_to.id))
            LEFT JOIN users u_by ON t.assigned_by = u_by.id
            ORDER BY t.id DESC
        ");
        $stmt->execute();
    } else {
        $stmt = $db->prepare("
            SELECT t.*, p.name as project_name, p.code as project_code, d.name as department_name, 
                   u_to.name as assignee_name, u_to.avatar as assignee_avatar, u_to.designation as assignee_designation,
                   u_by.name as creator_name,
                   {$emgFlagFields}
            FROM tasks t
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN departments d ON t.department_id = d.id
            LEFT JOIN users u_to ON (t.assigned_to = u_to.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u_to.id))
            LEFT JOIN users u_by ON t.assigned_by = u_by.id
            WHERE {$visCondition['sql']}
            ORDER BY t.id DESC
        ");
        $stmt->execute($visCondition['params']);
    }
    $tasks = $stmt->fetchAll() ?: [];
} catch (Throwable $e) {
    error_log("Tasks load with emergency/flags fields failed: " . $e->getMessage() . " - falling back to base task query");
    try {
        if ($isUnrestricted) {
            $stmt = $db->prepare("
                SELECT t.*, p.name as project_name, p.code as project_code, d.name as department_name, 
                       u_to.name as assignee_name, u_to.avatar as assignee_avatar, u_to.designation as assignee_designation,
                       u_by.name as creator_name,
                       NULL as emg_status, NULL as emg_reason, NULL as flag_status, NULL as flag_category, NULL as flag_impact
                FROM tasks t
                LEFT JOIN projects p ON t.project_id = p.id
                LEFT JOIN departments d ON t.department_id = d.id
                LEFT JOIN users u_to ON (t.assigned_to = u_to.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u_to.id))
                LEFT JOIN users u_by ON t.assigned_by = u_by.id
                ORDER BY t.id DESC
            ");
            $stmt->execute();
        } else {
            $stmt = $db->prepare("
                SELECT t.*, p.name as project_name, p.code as project_code, d.name as department_name, 
                       u_to.name as assignee_name, u_to.avatar as assignee_avatar, u_to.designation as assignee_designation,
                       u_by.name as creator_name,
                       NULL as emg_status, NULL as emg_reason, NULL as flag_status, NULL as flag_category, NULL as flag_impact
                FROM tasks t
                LEFT JOIN projects p ON t.project_id = p.id
                LEFT JOIN departments d ON t.department_id = d.id
                LEFT JOIN users u_to ON (t.assigned_to = u_to.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u_to.id))
                LEFT JOIN users u_by ON t.assigned_by = u_by.id
                WHERE {$visCondition['sql']}
                ORDER BY t.id DESC
            ");
            $stmt->execute($visCondition['params']);
        }
        $tasks = $stmt->fetchAll() ?: [];
    } catch (Throwable $e2) {
        error_log("Tasks base query fallback failed: " . $e2->getMessage());
        $tasks = [];
    }
}

// Calculate Status and KPI Summary Statistics
$kpiTotal = count($tasks);
$kpiRunning = 0;
$kpiPending = 0;
$kpiInReview = 0;
$kpiCompleted = 0;
$kpiOverdue = 0;
$kpiEmergency = 0;
$kpiFlagged = 0;

$nowTimestamp = time();
foreach ($tasks as $t) {
    $st = strtolower(trim((string)($t['status'] ?? 'pending')));
    $isDone = in_array($st, ['completed', 'done']);
    $isOver = (!empty($t['deadline']) && strtotime((string)$t['deadline']) < $nowTimestamp && !$isDone);
    
    if ($isOver) {
        $kpiOverdue++;
    }
    if ($st === 'running' || $st === 'in_progress') {
        $kpiRunning++;
    } elseif ($st === 'pending_approval' || $st === 'in_review') {
        $kpiInReview++;
    } elseif ($isDone) {
        $kpiCompleted++;
    } else {
        $kpiPending++;
    }

    if (!empty($t['emg_status']) && strtolower((string)$t['emg_status']) === 'pending') {
        $kpiEmergency++;
    }
    if (!empty($t['flag_status']) && strtolower((string)$t['flag_status']) !== 'resolved') {
        $kpiFlagged++;
    }
}

// Employee assigned tasks for emergency request dropdown
$myAssignedTasks = [];
try {
    $myTasksStmt = $db->prepare("SELECT id, title FROM tasks WHERE (assigned_to = ? OR assigned_to_employee_id = ?) AND status NOT IN ('completed', 'done') ORDER BY title ASC");
    $myTasksStmt->execute([$userId, $userId]);
    $myAssignedTasks = $myTasksStmt->fetchAll() ?: [];
} catch (Throwable $e) {
    error_log("Tasks myAssignedTasks fetch error: " . $e->getMessage());
    $myAssignedTasks = [];
}

// Load Assignable Employees, Projects, and Departments
$usersList = getAssignableEmployeesForUser($user, $db);
$myDeptEmployees = array_filter($usersList, function($u) { return !empty($u['is_my_dept']); });
$otherEmployees = array_filter($usersList, function($u) { return empty($u['is_my_dept']); });

$projectsList = [];
$departmentsList = [];
try {
    $projectsList = $db->query("SELECT id, name, code, department_id FROM projects ORDER BY name ASC")->fetchAll() ?: [];
} catch (Throwable $pe) {
    $projectsList = [];
}
try {
    $departmentsList = $db->query("SELECT id, name, code FROM departments ORDER BY name ASC")->fetchAll() ?: [];
} catch (Throwable $de) {
    $departmentsList = [];
}
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-3 p-md-4">
        <!-- ========================================================= -->
        <!-- 1. DESKTOP PAGE HEADER (>= 768px)                         -->
        <!-- ========================================================= -->
        <div class="d-none d-md-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1 rounded-pill fs-8">
                        <i class="fas fa-layer-group me-1"></i>Deliverables Hub
                    </span>
                    <?php if ($canManageTasks): ?>
                        <span class="badge bg-success-subtle text-success fw-bold px-2.5 py-1 rounded-pill fs-8">
                            <i class="fas fa-user-shield me-1"></i>Manager Access
                        </span>
                    <?php else: ?>
                        <span class="badge bg-info-subtle text-info fw-bold px-2.5 py-1 rounded-pill fs-8">
                            <i class="fas fa-user-check me-1"></i>Employee Queue
                        </span>
                    <?php endif; ?>
                </div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-tasks text-primary me-2"></i><?= $canManageTasks ? 'Task Management & Operations' : 'My Assigned Tasks & Queue' ?>
                </h3>
                <p class="text-secondary small mb-0">
                    <?= $canManageTasks ? 'Create, delegate, track live timer progress, review completion requests, and manage deliverables.' : 'View your assigned work queue, start live task timers, submit completed deliverables, and report blockers.' ?>
                </p>
            </div>
            
            <div class="d-flex gap-2 flex-wrap align-items-center">
                <!-- Emergency Request Button -->
                <button type="button" class="btn btn-outline-danger fw-semibold shadow-xs" onclick="openEmergencyModal()">
                    <i class="fas fa-bolt me-1 text-danger"></i>Emergency Request
                </button>
                
                <!-- Flagged Tasks Link -->
                <a href="<?= BASE_URL ?>/flagged-tasks" class="btn btn-outline-warning text-dark fw-semibold shadow-xs">
                    <i class="fas fa-flag me-1 text-warning"></i>Flagged Tasks
                    <?php if ($kpiFlagged > 0): ?>
                        <span class="badge bg-warning text-dark rounded-pill ms-1"><?= $kpiFlagged ?></span>
                    <?php endif; ?>
                </a>

                <?php if ($canManageTasks): ?>
                    <!-- Emergency Approvals Link -->
                    <a href="<?= BASE_URL ?>/emergency-approvals" class="btn btn-outline-danger fw-semibold shadow-xs">
                        <i class="fas fa-shield-alt me-1"></i>Emergency Approvals
                        <?php if ($kpiEmergency > 0): ?>
                            <span class="badge bg-danger text-white rounded-pill ms-1"><?= $kpiEmergency ?></span>
                        <?php endif; ?>
                    </a>
                <?php endif; ?>

                <!-- Kanban Board Switch -->
                <a href="<?= BASE_URL ?>/kanban" class="btn btn-outline-info fw-semibold shadow-xs">
                    <i class="fas fa-columns me-1"></i>Kanban Board
                </a>

                <!-- Export to Excel -->
                <button type="button" class="btn btn-outline-secondary fw-semibold shadow-xs" onclick="exportTasksToExcel()" title="Export Task List">
                    <i class="fas fa-file-excel text-success me-1"></i>Export
                </button>

                <?php if ($canManageTasks): ?>
                    <!-- Create Task Button -->
                    <button type="button" class="btn btn-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                        <i class="fas fa-plus-circle me-1"></i><?= $isSeoLead ? 'Give SEO Task' : ($isDevLead ? 'Give Dev Task' : 'Create Task') ?>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 2. MOBILE APP PAGE HEADER (< 768px App View)              -->
        <!-- ========================================================= -->
        <div class="app-page-header-mobile d-block d-md-none">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center gap-1.5">
                    <span class="app-mode-badge"><i class="fas fa-tasks text-primary"></i>Task App</span>
                    <span class="badge bg-primary text-white rounded-pill px-2 py-0.5 fs-9 fw-bold" id="mobileTaskTotalBadge"><?= $kpiTotal ?> Tasks</span>
                </div>
                <?php if ($canManageTasks): ?>
                    <button type="button" class="btn btn-xs btn-primary fw-bold rounded-pill px-2.5 shadow-xs" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                        <i class="fas fa-plus me-1"></i><?= $isSeoLead ? 'Give SEO Task' : ($isDevLead ? 'Give Dev Task' : 'Create Task') ?>
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-xs btn-outline-danger fw-bold rounded-pill px-2.5 shadow-xs" onclick="openEmergencyModal()">
                        <i class="fas fa-bolt me-1"></i>Emergency
                    </button>
                <?php endif; ?>
            </div>

            <div class="app-actions-scroll">
                <a href="<?= BASE_URL ?>/kanban" class="btn btn-outline-info btn-xs rounded-pill">
                    <i class="fas fa-columns me-1"></i>Kanban
                </a>
                <a href="<?= BASE_URL ?>/flagged-tasks" class="btn btn-outline-warning text-dark btn-xs rounded-pill">
                    <i class="fas fa-flag me-1"></i>Flagged <?= $kpiFlagged > 0 ? "({$kpiFlagged})" : '' ?>
                </a>
                <?php if ($canManageTasks): ?>
                    <a href="<?= BASE_URL ?>/emergency-approvals" class="btn btn-outline-danger btn-xs rounded-pill">
                        <i class="fas fa-shield-alt me-1"></i>Approvals <?= $kpiEmergency > 0 ? "({$kpiEmergency})" : '' ?>
                    </a>
                <?php endif; ?>
                <button type="button" class="btn btn-outline-secondary btn-xs rounded-pill" onclick="exportTasksToExcel()">
                    <i class="fas fa-file-excel text-success me-1"></i>Export
                </button>
                <button type="button" class="btn btn-light border btn-xs text-secondary rounded-pill" id="mobileFilterToggleBtn" onclick="toggleMobileTaskFilters()">
                    <i class="fas fa-sliders me-1 text-primary"></i>Filters
                </button>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 3. KPI SUMMARY: DESKTOP GRID (>= 768px)                   -->
        <!-- ========================================================= -->
        <div class="row g-3 mb-4 d-none d-md-flex">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 cursor-pointer stat-kpi-card hover-lift" onclick="filterByKpi('all')" title="Show All Tasks">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-semibold">Total Tasks</span>
                        <div class="kpi-icon-wrap bg-primary-subtle text-primary"><i class="fas fa-list-check"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-dark font-heading"><?= $kpiTotal ?></div>
                    <small class="text-muted fs-8">Entire task register</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 cursor-pointer stat-kpi-card hover-lift" onclick="filterByKpi('running')" title="Show Running / In Progress Tasks">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-primary small fw-semibold">In Progress</span>
                        <div class="kpi-icon-wrap bg-primary text-white"><i class="fas fa-spinner fa-spin"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-primary font-heading"><?= $kpiRunning ?></div>
                    <small class="text-primary fs-8 fw-semibold">Live timer running</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 cursor-pointer stat-kpi-card hover-lift <?= $kpiInReview > 0 ? 'border-warning shadow-xs' : '' ?>" onclick="filterByKpi('in_review')" title="Show In Review / Awaiting Approval">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-warning small fw-semibold">In Review</span>
                        <div class="kpi-icon-wrap bg-warning-subtle text-warning"><i class="fas fa-hourglass-half"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-warning font-heading"><?= $kpiInReview ?></div>
                    <small class="text-muted fs-8">Awaiting manager sign-off</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 cursor-pointer stat-kpi-card hover-lift" onclick="filterByKpi('pending')" title="Show Pending Queue">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-semibold">Pending</span>
                        <div class="kpi-icon-wrap bg-secondary-subtle text-secondary"><i class="fas fa-clock"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-secondary font-heading"><?= $kpiPending ?></div>
                    <small class="text-muted fs-8">Work queue backlog</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 cursor-pointer stat-kpi-card hover-lift" onclick="filterByKpi('completed')" title="Show Completed Deliverables">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-success small fw-semibold">Completed</span>
                        <div class="kpi-icon-wrap bg-success-subtle text-success"><i class="fas fa-check-circle"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-success font-heading"><?= $kpiCompleted ?></div>
                    <small class="text-muted fs-8">Approved & delivered</small>
                </div>
            </div>

            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 cursor-pointer stat-kpi-card hover-lift <?= $kpiOverdue > 0 ? 'border-danger shadow-xs' : '' ?>" onclick="filterByKpi('overdue')" title="Show Overdue Tasks">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-danger small fw-semibold">Overdue</span>
                        <div class="kpi-icon-wrap bg-danger-subtle text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-danger font-heading"><?= $kpiOverdue ?></div>
                    <small class="text-danger fs-8 fw-semibold">Action required immediately</small>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 4. KPI SUMMARY: MOBILE SNAP SCROLL STRIP (< 768px App View) -->
        <!-- ========================================================= -->
        <div class="app-kpi-scroll d-flex d-md-none">
            <div class="col-auto">
                <div class="glass-card p-3 cursor-pointer stat-kpi-card hover-lift" onclick="filterByKpi('all')" title="Show All Tasks">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-secondary fs-9 fw-bold text-uppercase">Total</span>
                        <div class="kpi-icon-wrap bg-primary-subtle text-primary"><i class="fas fa-list-check"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-dark font-heading"><?= $kpiTotal ?></div>
                    <small class="text-muted fs-9">All Tasks</small>
                </div>
            </div>

            <div class="col-auto">
                <div class="glass-card p-3 cursor-pointer stat-kpi-card hover-lift" onclick="filterByKpi('running')" title="Show In Progress">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-primary fs-9 fw-bold text-uppercase">Progress</span>
                        <div class="kpi-icon-wrap bg-primary text-white"><i class="fas fa-spinner fa-spin"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-primary font-heading"><?= $kpiRunning ?></div>
                    <small class="text-primary fs-9 fw-semibold">Running Now</small>
                </div>
            </div>

            <div class="col-auto">
                <div class="glass-card p-3 cursor-pointer stat-kpi-card hover-lift <?= $kpiInReview > 0 ? 'border-warning shadow-xs' : '' ?>" onclick="filterByKpi('in_review')" title="Show In Review">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-warning fs-9 fw-bold text-uppercase">Review</span>
                        <div class="kpi-icon-wrap bg-warning-subtle text-warning"><i class="fas fa-hourglass-half"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-warning font-heading"><?= $kpiInReview ?></div>
                    <small class="text-muted fs-9">Awaiting Sign-off</small>
                </div>
            </div>

            <div class="col-auto">
                <div class="glass-card p-3 cursor-pointer stat-kpi-card hover-lift" onclick="filterByKpi('pending')" title="Show Pending">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-secondary fs-9 fw-bold text-uppercase">Pending</span>
                        <div class="kpi-icon-wrap bg-secondary-subtle text-secondary"><i class="fas fa-clock"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-secondary font-heading"><?= $kpiPending ?></div>
                    <small class="text-muted fs-9">In Queue</small>
                </div>
            </div>

            <div class="col-auto">
                <div class="glass-card p-3 cursor-pointer stat-kpi-card hover-lift" onclick="filterByKpi('completed')" title="Show Completed">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-success fs-9 fw-bold text-uppercase">Done</span>
                        <div class="kpi-icon-wrap bg-success-subtle text-success"><i class="fas fa-check-circle"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-success font-heading"><?= $kpiCompleted ?></div>
                    <small class="text-muted fs-9">Delivered</small>
                </div>
            </div>

            <div class="col-auto">
                <div class="glass-card p-3 cursor-pointer stat-kpi-card hover-lift <?= $kpiOverdue > 0 ? 'border-danger shadow-xs' : '' ?>" onclick="filterByKpi('overdue')" title="Show Overdue">
                    <div class="d-flex justify-content-between align-items-center mb-1.5">
                        <span class="text-danger fs-9 fw-bold text-uppercase">Overdue</span>
                        <div class="kpi-icon-wrap bg-danger-subtle text-danger"><i class="fas fa-exclamation-triangle"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-danger font-heading"><?= $kpiOverdue ?></div>
                    <small class="text-danger fs-9 fw-semibold">Action Due</small>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 5. FILTER & SEARCH TOOLBAR (Responsive with Mobile Toggle)-->
        <!-- ========================================================= -->
        <div class="glass-card p-3 mb-4" id="taskFiltersPanel">
            <div class="row g-2 align-items-center">
                <!-- Search Box -->
                <div class="col-12 col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="taskSearchFilter" class="form-control bg-light border-start-0" placeholder="Search title, keyword, ID..." oninput="applyAllTaskFilters()">
                        <button class="btn btn-outline-secondary border-start-0" type="button" onclick="clearSearchFilter()" title="Clear search"><i class="fas fa-times"></i></button>
                    </div>
                </div>

                <!-- Status Filter Dropdown -->
                <div class="col-6 col-md-2">
                    <select id="taskStatusFilter" class="form-select form-select-sm" onchange="applyAllTaskFilters()">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="running">Running / In Progress</option>
                        <option value="in_review">In Review / Pending Approval</option>
                        <option value="completed">Completed / Done</option>
                        <option value="overdue">Overdue Only</option>
                        <option value="rejected">Rejected / Revision Needed</option>
                        <option value="paused">Paused</option>
                    </select>
                </div>

                <!-- Priority Filter Dropdown -->
                <div class="col-6 col-md-2">
                    <select id="taskPriorityFilter" class="form-select form-select-sm" onchange="applyAllTaskFilters()">
                        <option value="">All Priorities</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High</option>
                        <option value="medium">Medium</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <!-- Department Filter Dropdown -->
                <div class="col-6 col-md-2">
                    <select id="taskDepartmentFilter" class="form-select form-select-sm" onchange="applyAllTaskFilters()">
                        <option value="">All Departments</option>
                        <?php foreach ($departmentsList as $d): ?>
                            <option value="<?= sanitize($d['name']) ?>"><?= sanitize($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Project Filter Dropdown -->
                <div class="col-6 col-md-2">
                    <select id="taskProjectFilter" class="form-select form-select-sm" onchange="applyAllTaskFilters()">
                        <option value="">All Projects</option>
                        <?php foreach ($projectsList as $p): ?>
                            <option value="<?= sanitize($p['name']) ?>"><?= sanitize($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Reset Filters Button -->
                <div class="col-12 col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-light border w-100 fw-semibold text-secondary" onclick="resetTaskFilters()" title="Reset all filters">
                        <i class="fas fa-undo-alt me-1"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Task List Container -->
        <div class="glass-card p-0 overflow-hidden">
            <!-- Desktop Table View (>= 768px) -->
            <div class="table-responsive d-none d-md-block">
                <table id="tasksDataTable" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">#ID</th>
                            <th>Task Title & Context</th>
                            <th>Assignee</th>
                            <th>Department</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Spent Time</th>
                            <th>Deadline</th>
                            <th class="text-end" style="min-width: 170px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tasks as $t): 
                            $taskId = (int)$t['id'];
                            $taskStatus = strtolower(trim((string)($t['status'] ?? 'pending')));
                            $taskPriority = strtolower(trim((string)($t['priority'] ?? 'medium')));
                            $isDone = in_array($taskStatus, ['completed', 'done']);
                            $isOverdue = (!empty($t['deadline']) && strtotime((string)$t['deadline']) < $nowTimestamp && !$isDone);
                            $filterStatus = $isOverdue ? 'overdue' : ($taskStatus === 'in_progress' ? 'running' : ($taskStatus === 'pending_approval' ? 'in_review' : $taskStatus));
                            $isAssignedEmployee = ((int)($t['assigned_to'] ?? 0) === $userId || (int)($t['assigned_to_employee_id'] ?? 0) === $userId);
                            $isAwaitingApproval = in_array($taskStatus, ['pending_approval', 'in_review', 'submitted']);
                        ?>
                            <tr data-task-id="<?= $taskId ?>"
                                data-status="<?= $filterStatus ?>"
                                data-priority="<?= $taskPriority ?>"
                                data-department="<?= sanitize($t['department_name'] ?? 'General') ?>"
                                data-project="<?= sanitize($t['project_name'] ?? 'Direct Task') ?>"
                                data-assignee="<?= sanitize($t['assignee_name'] ?? 'Unassigned') ?>">
                                
                                <!-- Column 0: #ID -->
                                <td class="text-muted font-monospace fw-semibold" data-order="<?= $taskId ?>">
                                    #<?= $taskId ?>
                                </td>

                                <!-- Column 1: Task Title & Context -->
                                <td>
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="flex-grow-1">
                                            <a href="<?= BASE_URL ?>/task-details?id=<?= $taskId ?>" class="fw-bold text-dark text-decoration-none hover-primary d-inline-block fs-7 mb-0">
                                                <?= sanitize($t['title'] ?? '') ?>
                                            </a>
                                            <div class="d-flex flex-wrap align-items-center gap-1.5 mt-0.5">
                                                <span class="badge bg-light text-secondary border fs-8">
                                                    <i class="fas fa-folder me-1 text-muted"></i><?= sanitize($t['project_name'] ?? 'Direct Task') ?>
                                                </span>
                                                <?php if (!empty($t['estimated_hours']) && (float)$t['estimated_hours'] > 0): ?>
                                                    <span class="text-muted fs-8 font-monospace">
                                                        <i class="fas fa-stopwatch me-0.5"></i><?= (float)$t['estimated_hours'] ?>h est
                                                    </span>
                                                <?php endif; ?>
                                            </div>

                                            <!-- Emergency Escalation Indicator -->
                                            <?php if (!empty($t['emg_status'])): ?>
                                                <div class="mt-1 d-inline-block me-1">
                                                    <?= getEmergencyRequestBadge($t['emg_status']) ?>
                                                </div>
                                            <?php endif; ?>

                                            <!-- Missing Requirements / Blocker Flag Indicator -->
                                            <?php if (!empty($t['flag_status'])): ?>
                                                <div class="mt-1 d-inline-flex flex-wrap gap-1 align-items-center">
                                                    <?= getTaskFlagBadge($t['flag_status']) ?>
                                                    <?= !empty($t['flag_impact']) ? getTaskFlagImpactBadge($t['flag_impact']) : '' ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <!-- Column 2: Assignee -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($t['assignee_name'] ?? 'Unassigned') ?>&background=4f46e5&color=fff" class="rounded-circle" width="28" height="28" alt="Avatar">
                                        <div>
                                            <div class="fw-semibold text-dark fs-8 line-height-1"><?= sanitize($t['assignee_name'] ?? 'Unassigned') ?></div>
                                            <small class="text-muted fs-9"><?= sanitize($t['assignee_designation'] ?? 'Staff') ?></small>
                                        </div>
                                    </div>
                                </td>

                                <!-- Column 3: Department -->
                                <td>
                                    <span class="badge bg-light text-dark border fs-8"><?= sanitize($t['department_name'] ?? 'General') ?></span>
                                </td>

                                <!-- Column 4: Priority -->
                                <td>
                                    <?= getPriorityBadge($t['priority'] ?? 'medium') ?>
                                </td>

                                <!-- Column 5: Status -->
                                <td>
                                    <?= getStatusBadge($t['status'] ?? 'pending', $t['deadline'] ?? null) ?>
                                </td>

                                <!-- Column 6: Spent Time -->
                                <td>
                                    <span class="font-monospace text-primary fw-semibold fs-8" id="inlineTaskTimer_<?= $taskId ?>">
                                        <?= formatDuration((int)($t['spent_seconds'] ?? 0)) ?>
                                    </span>
                                </td>

                                <!-- Column 7: Deadline Countdown -->
                                <td class="small" data-order="<?= !empty($t['deadline']) ? strtotime($t['deadline']) : 0 ?>">
                                    <?= getDeadlineCountdown($t['deadline'] ?? null) ?>
                                </td>

                                <!-- Column 8: Actions -->
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <!-- View Details -->
                                        <a href="<?= BASE_URL ?>/task-details?id=<?= $taskId ?>" class="btn btn-outline-info" title="View Full Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Edit Task (Available to Managers & Admins) -->
                                        <?php if ($canManageTasks): ?>
                                            <button type="button" class="btn btn-outline-primary" onclick="openEditTaskModal(<?= htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8') ?>)" title="Edit Task Deliverables">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="openReassignModal(<?= $taskId ?>, <?= (int)($t['assigned_to'] ?? 0) ?>, <?= htmlspecialchars(json_encode((string)($t['title'] ?? '')), ENT_QUOTES, 'UTF-8') ?>)" title="Reassign Employee">
                                                <i class="fas fa-user-tag"></i>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Manager Approval & Revision Controls -->
                                        <?php if ($canManageTasks && $isAwaitingApproval): ?>
                                            <button type="button" class="btn btn-success fw-bold" onclick="approveTaskPrompt(<?= $taskId ?>)" title="Approve & Mark Done">
                                                <i class="fas fa-check-circle me-1"></i>Approve
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="rejectApprovalPrompt(<?= $taskId ?>)" title="Request Revision">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        <?php endif; ?>

                                        <!-- Employee Live Timer Controls -->
                                        <?php if ($isAssignedEmployee): ?>
                                            <?php if ($taskStatus === 'running'): ?>
                                                <button type="button" class="btn btn-warning" onclick="pauseTaskTimer(<?= $taskId ?>)" title="Pause Live Timer">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                                <button type="button" class="btn btn-success fw-bold" onclick="stopTaskTimer(<?= $taskId ?>)" title="Submit for Approval">
                                                    <i class="fas fa-paper-plane me-1"></i>Submit
                                                </button>
                                            <?php elseif ($isAwaitingApproval): ?>
                                                <span class="btn btn-sm btn-warning text-dark border disabled" title="Awaiting Manager Approval">
                                                    <i class="fas fa-hourglass-half"></i>
                                                </span>
                                            <?php elseif (!$isDone): ?>
                                                <button type="button" class="btn btn-primary" onclick="startTaskTimer(<?= $taskId ?>)" title="Start Live Timer">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-success" onclick="submitTaskCompletionPrompt(<?= $taskId ?>)" title="Submit Completed Work">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-info" onclick="openWorkLogModal(<?= $taskId ?>, <?= htmlspecialchars(json_encode((string)($t['title'] ?? '')), ENT_QUOTES, 'UTF-8') ?>)" title="Log Work Progress">
                                                    <i class="fas fa-clipboard-list"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <!-- Emergency Escalation Button -->
                                        <button type="button" class="btn btn-outline-danger" onclick="openEmergencyModal(<?= $taskId ?>, <?= htmlspecialchars(json_encode((string)($t['title'] ?? '')), ENT_QUOTES, 'UTF-8') ?>)" title="Emergency Request">
                                            <i class="fas fa-bolt"></i>
                                        </button>

                                        <!-- Flag Task / Missing Requirements Button -->
                                        <button type="button" class="btn btn-outline-warning text-dark" onclick="openFlagModal(<?= $taskId ?>, <?= htmlspecialchars(json_encode((string)($t['title'] ?? '')), ENT_QUOTES, 'UTF-8') ?>)" title="Report Missing Requirements / Blocker">
                                            <i class="fas fa-flag"></i>
                                        </button>

                                        <!-- Clone & Delete Controls -->
                                        <?php if ($canManageTasks): ?>
                                            <button type="button" class="btn btn-outline-secondary" onclick="cloneTask(<?= $taskId ?>)" title="Clone Task">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="deleteTask(<?= $taskId ?>)" title="Delete Task">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Task Cards View (< 768px App View) -->
            <div class="mobile-card-list d-block d-md-none p-3">
                <?php if (empty($tasks)): ?>
                    <div class="text-center p-4 text-muted bg-light rounded-3 border">
                        <i class="fas fa-tasks fs-2 text-muted mb-2 d-block"></i>No tasks found in your register.
                    </div>
                <?php else: ?>
                    <?php foreach ($tasks as $t): 
                        $taskId = (int)$t['id'];
                        $taskStatus = strtolower(trim((string)($t['status'] ?? 'pending')));
                        $taskPriority = strtolower(trim((string)($t['priority'] ?? 'medium')));
                        $isDone = in_array($taskStatus, ['completed', 'done']);
                        $isOverdue = (!empty($t['deadline']) && strtotime((string)$t['deadline']) < $nowTimestamp && !$isDone);
                        $filterStatus = $isOverdue ? 'overdue' : ($taskStatus === 'in_progress' ? 'running' : ($taskStatus === 'pending_approval' ? 'in_review' : $taskStatus));
                        $isAssignedEmployee = ((int)($t['assigned_to'] ?? 0) === $userId || (int)($t['assigned_to_employee_id'] ?? 0) === $userId);
                        $isAwaitingApproval = in_array($taskStatus, ['pending_approval', 'in_review', 'submitted']);
                    ?>
                        <div class="mobile-data-card"
                             data-task-id="<?= $taskId ?>"
                             data-status="<?= $filterStatus ?>"
                             data-priority="<?= $taskPriority ?>"
                             data-department="<?= sanitize($t['department_name'] ?? 'General') ?>"
                             data-project="<?= sanitize($t['project_name'] ?? 'Direct Task') ?>"
                             data-assignee="<?= sanitize($t['assignee_name'] ?? 'Unassigned') ?>">
                            
                            <!-- Header: Title & Status -->
                            <div class="mobile-data-card-header">
                                <div class="flex-grow-1 overflow-hidden me-2">
                                    <div class="d-flex align-items-center gap-1.5 mb-0.5">
                                        <span class="text-muted font-monospace fs-9 fw-bold">#<?= $taskId ?></span>
                                        <span class="text-secondary fs-9 text-truncate">
                                            <i class="fas fa-folder me-1 text-primary"></i><?= sanitize($t['project_name'] ?? 'Direct Task') ?>
                                        </span>
                                    </div>
                                    <a href="<?= BASE_URL ?>/task-details?id=<?= $taskId ?>" class="fw-bold text-dark text-decoration-none d-block fs-7 line-height-1-2 text-truncate">
                                        <?= sanitize($t['title'] ?? '') ?>
                                    </a>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <?= getStatusBadge($t['status'] ?? 'pending', $t['deadline'] ?? null) ?>
                                </div>
                            </div>

                            <!-- Emergency & Flag Badges Strip -->
                            <?php if (!empty($t['emg_status']) || !empty($t['flag_status'])): ?>
                                <div class="my-1.5 d-flex flex-wrap gap-1">
                                    <?php if (!empty($t['emg_status'])): ?>
                                        <?= getEmergencyRequestBadge($t['emg_status']) ?>
                                    <?php endif; ?>
                                    <?php if (!empty($t['flag_status'])): ?>
                                        <?= getTaskFlagBadge($t['flag_status']) ?>
                                        <?= !empty($t['flag_impact']) ? getTaskFlagImpactBadge($t['flag_impact']) : '' ?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- 2-Column Precision Micro Metadata Grid -->
                            <div class="mobile-data-card-body">
                                <div class="mobile-stat-box">
                                    <span class="text-secondary d-block fs-9 fw-bold text-uppercase">Assignee</span>
                                    <strong class="text-dark fs-8 line-height-1-1 d-block mt-0.5 text-truncate"><?= sanitize($t['assignee_name'] ?? 'Unassigned') ?></strong>
                                </div>
                                <div class="mobile-stat-box">
                                    <span class="text-secondary d-block fs-9 fw-bold text-uppercase">Department</span>
                                    <span class="text-dark fs-8 fw-semibold line-height-1-1 d-block mt-0.5 text-truncate"><?= sanitize($t['department_name'] ?? 'General') ?></span>
                                </div>
                                <div class="mobile-stat-box">
                                    <span class="text-secondary d-block fs-9 fw-bold text-uppercase">Priority</span>
                                    <div class="mt-0.5"><?= getPriorityBadge($t['priority'] ?? 'medium') ?></div>
                                </div>
                                <div class="mobile-stat-box">
                                    <span class="text-secondary d-block fs-9 fw-bold text-uppercase">Spent Time</span>
                                    <span class="font-monospace text-primary fw-bold fs-8 line-height-1-1 d-block mt-0.5"><?= formatDuration((int)($t['spent_seconds'] ?? 0)) ?></span>
                                </div>
                                <div class="mobile-stat-box" style="grid-column: span 2;">
                                    <span class="text-secondary d-block fs-9 fw-bold text-uppercase">Deadline</span>
                                    <span class="text-dark fs-8 fw-semibold mt-0.5 d-block"><i class="fas fa-clock me-1 text-primary"></i><?= getDeadlineCountdown($t['deadline'] ?? null) ?></span>
                                </div>
                            </div>

                            <!-- Mobile Action Bar - Small Sleek Buttons -->
                            <div class="mobile-data-card-footer">
                                <a href="<?= BASE_URL ?>/task-details?id=<?= $taskId ?>" class="btn btn-xs btn-outline-info rounded-pill px-2.5 me-auto fw-semibold">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>

                                <?php if ($canManageTasks): ?>
                                    <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2" onclick="openEditTaskModal(<?= htmlspecialchars(json_encode($t), ENT_QUOTES, 'UTF-8') ?>)" title="Edit Task">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($canManageTasks && $isAwaitingApproval): ?>
                                    <button type="button" class="btn btn-xs btn-success rounded-pill fw-bold px-2.5" onclick="approveTaskPrompt(<?= $taskId ?>)">
                                        <i class="fas fa-check-circle me-1"></i>Approve
                                    </button>
                                    <button type="button" class="btn btn-xs btn-outline-danger rounded-pill px-2" onclick="rejectApprovalPrompt(<?= $taskId ?>)" title="Revision">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($isAssignedEmployee): ?>
                                    <?php if ($taskStatus === 'running'): ?>
                                        <button type="button" class="btn btn-xs btn-warning rounded-pill px-2 fw-bold" onclick="pauseTaskTimer(<?= $taskId ?>)">
                                            <i class="fas fa-pause me-1"></i>Pause
                                        </button>
                                        <button type="button" class="btn btn-xs btn-success rounded-pill px-2.5 fw-bold" onclick="stopTaskTimer(<?= $taskId ?>)">
                                            <i class="fas fa-paper-plane me-1"></i>Submit
                                        </button>
                                    <?php elseif (!$isDone && !$isAwaitingApproval): ?>
                                        <button type="button" class="btn btn-xs btn-primary rounded-pill px-2.5 fw-bold" onclick="startTaskTimer(<?= $taskId ?>)">
                                            <i class="fas fa-play me-1"></i>Start
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-success rounded-pill px-2 fw-semibold" onclick="submitTaskCompletionPrompt(<?= $taskId ?>)" title="Submit Completed Work">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <button type="button" class="btn btn-xs btn-outline-danger rounded-pill px-2" onclick="openEmergencyModal(<?= $taskId ?>, <?= htmlspecialchars(json_encode((string)($t['title'] ?? '')), ENT_QUOTES, 'UTF-8') ?>)" title="Emergency Request">
                                    <i class="fas fa-bolt"></i>
                                </button>
                                <button type="button" class="btn btn-xs btn-outline-warning text-dark rounded-pill px-2" onclick="openFlagModal(<?= $taskId ?>, <?= htmlspecialchars(json_encode((string)($t['title'] ?? '')), ENT_QUOTES, 'UTF-8') ?>)" title="Flag Task">
                                    <i class="fas fa-flag"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: CREATE & ASSIGN TASK                                               -->
<!-- ========================================================================= -->
<?php if ($canManageTasks): ?>
<div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-primary text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%) !important;">
                <h5 class="modal-title fw-bold text-white">
                    <i class="fas fa-plus-circle me-2"></i><?= $isSeoLead ? 'Give SEO Task (Aman & Shivanshu)' : ($isDevLead ? 'Give Dev Task (Abhay)' : 'Create & Assign New Task') ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createTaskForm">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-secondary small fw-semibold">Task Title *</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter clear, actionable task title" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Priority *</label>
                            <select name="priority" class="form-select" required>
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <!-- Department Selector with Add Department Option -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-semibold mb-0">Department <span class="text-danger">*</span></label>
                                <?php if ($isGlobalManager): ?>
                                    <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fs-9 fw-bold shadow-xs" onclick="openQuickAddDepartmentModal('create')">
                                        <i class="fas fa-plus me-1"></i>Add Department
                                    </button>
                                <?php endif; ?>
                            </div>
                            <select name="department_id" id="createTaskDepartmentSelect" class="form-select" onchange="onTaskDepartmentChanged('create')">
                                <option value="0">Auto-Detect from Assignee</option>
                                <?php foreach ($departmentsList as $d): ?>
                                    <option value="<?= $d['id'] ?>" data-code="<?= htmlspecialchars($d['code'] ?? '') ?>">
                                        <?= sanitize($d['name']) ?> <?= !empty($d['code']) ? '(' . sanitize($d['code']) . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if ($isGlobalManager): ?>
                                    <option value="__add_new_dept__" class="fw-bold text-primary">➕ + Add New Department...</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Project Selector with Add Project Option -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-semibold mb-0">Project (Optional)</label>
                                <?php if ($isGlobalManager): ?>
                                    <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fs-9 fw-bold shadow-xs" onclick="openQuickAddProjectModal('create')">
                                        <i class="fas fa-plus me-1"></i>Add Project
                                    </button>
                                <?php endif; ?>
                            </div>
                            <select name="project_id" id="createTaskProjectSelect" class="form-select" onchange="onTaskProjectChanged('create')">
                                <option value="">None (Standalone Deliverable)</option>
                                <?php foreach ($projectsList as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-dept="<?= $p['department_id'] ?? '' ?>" data-code="<?= htmlspecialchars($p['code'] ?? '') ?>">
                                        <?= sanitize($p['name']) ?> <?= !empty($p['code']) ? '(' . sanitize($p['code']) . ')' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                                <?php if ($isGlobalManager): ?>
                                    <option value="__add_new_project__" class="fw-bold text-primary">➕ + Add New Project...</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Assign To Employee -->
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Assign To Employee *</label>
                            <select name="assigned_to" id="createTaskAssigneeSelect" class="form-select" required onchange="onTaskAssigneeChanged('create')">
                                <option value="">Select Employee...</option>
                                <?php if (!empty($myDeptEmployees)): ?>
                                    <optgroup label="⭐ <?= $isSeoLead ? 'SEO Team (Aman & Shivanshu)' : ($isDevLead ? 'Development Team (Abhay)' : 'Team Members') ?>" id="createOptGroupMyDept">
                                        <?php foreach ($myDeptEmployees as $u): ?>
                                            <option value="<?= $u['id'] ?>" data-dept="<?= $u['department_id'] ?? '' ?>" data-deptname="<?= htmlspecialchars($u['dept_name'] ?? '') ?>" data-designation="<?= htmlspecialchars($u['designation'] ?? '') ?>">
                                                <?= sanitize($u['name']) ?> (<?= sanitize($u['designation'] ?? 'Specialist') ?> - <?= sanitize($u['dept_name'] ?? 'General') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                                <?php if (!empty($otherEmployees)): ?>
                                    <optgroup label="👥 Other Staff" id="createOptGroupOtherDept">
                                        <?php foreach ($otherEmployees as $u): ?>
                                            <option value="<?= $u['id'] ?>" data-dept="<?= $u['department_id'] ?? '' ?>" data-deptname="<?= htmlspecialchars($u['dept_name'] ?? '') ?>" data-designation="<?= htmlspecialchars($u['designation'] ?? '') ?>">
                                                <?= sanitize($u['name']) ?> (<?= sanitize($u['designation'] ?? 'Staff') ?> - <?= sanitize($u['dept_name'] ?? 'General') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Estimated Working Hours -->
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Estimated Working Hours</label>
                            <input type="number" step="0.5" name="estimated_hours" class="form-control" value="4.0" min="0.5">
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-semibold mb-0">Deadline Date & Time *</label>
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-xs btn-light border py-0.5 px-2 fs-9" onclick="setDeadlinePreset('today')">Today</button>
                                    <button type="button" class="btn btn-xs btn-light border py-0.5 px-2 fs-9" onclick="setDeadlinePreset('tomorrow')">Tomorrow</button>
                                    <button type="button" class="btn btn-xs btn-light border py-0.5 px-2 fs-9" onclick="setDeadlinePreset('3days')">+3 Days</button>
                                    <button type="button" class="btn btn-xs btn-light border py-0.5 px-2 fs-9" onclick="setDeadlinePreset('1week')">+1 Week</button>
                                </div>
                            </div>
                            <input type="datetime-local" name="deadline" id="createTaskDeadlineInput" class="form-control" required value="<?= date('Y-m-d\TH:i', strtotime('+3 days 18:30:00')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold">Task Description & Deliverables</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Detail deliverables, reference links, and acceptance criteria..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-3.5" id="btnSubmitCreateTask">
                        <i class="fas fa-check-circle me-1"></i>Create & Assign Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ========================================================================= -->
<!-- MODAL: EDIT TASK                                                          -->
<!-- ========================================================================= -->
<?php if ($canManageTasks): ?>
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-dark text-white">
                <h5 class="modal-title fw-bold text-white">
                    <i class="fas fa-pencil-alt me-2 text-warning"></i>Edit Task #<span id="editTaskIdBadge"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editTaskForm">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="task_id" id="editTaskIdInput">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-secondary small fw-semibold">Task Title *</label>
                            <input type="text" name="title" id="editTaskTitle" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Priority</label>
                            <select name="priority" id="editTaskPriority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Assign To Employee *</label>
                            <select name="assigned_to" id="editTaskAssignee" class="form-select" required>
                                <option value="">Select Employee...</option>
                                <?php if (!empty($myDeptEmployees)): ?>
                                    <optgroup label="⭐ <?= $isSeoLead ? 'SEO Team (Aman & Shivanshu)' : ($isDevLead ? 'Development Team (Abhay)' : 'My Department Team') ?>">
                                        <?php foreach ($myDeptEmployees as $u): ?>
                                            <option value="<?= $u['id'] ?>">
                                                <?= sanitize($u['name']) ?> (<?= sanitize($u['designation'] ?? 'Specialist') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                                <?php if (!empty($otherEmployees)): ?>
                                    <optgroup label="👥 Other Staff">
                                        <?php foreach ($otherEmployees as $u): ?>
                                            <option value="<?= $u['id'] ?>">
                                                <?= sanitize($u['name']) ?> (<?= sanitize($u['designation'] ?? 'Staff') ?> - <?= sanitize($u['dept_name'] ?? 'General') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endif; ?>
                            </select>
                        </div>
                        <!-- Project Selector with Add Project Option -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-semibold mb-0">Project</label>
                                <?php if ($isGlobalManager): ?>
                                    <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fs-9 fw-bold shadow-xs" onclick="openQuickAddProjectModal('edit')">
                                        <i class="fas fa-plus me-1"></i>Add Project
                                    </button>
                                <?php endif; ?>
                            </div>
                            <select name="project_id" id="editTaskProject" class="form-select" onchange="onTaskProjectChanged('edit')">
                                <option value="">None (Standalone)</option>
                                <?php foreach ($projectsList as $p): ?>
                                    <option value="<?= $p['id'] ?>" data-dept="<?= $p['department_id'] ?? '' ?>"><?= sanitize($p['name']) ?> <?= !empty($p['code']) ? '(' . sanitize($p['code']) . ')' : '' ?></option>
                                <?php endforeach; ?>
                                <?php if ($isGlobalManager): ?>
                                    <option value="__add_new_project__" class="fw-bold text-primary">➕ + Add New Project...</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Department Selector with Add Department Option -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-semibold mb-0">Department</label>
                                <?php if ($isGlobalManager): ?>
                                    <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fs-9 fw-bold shadow-xs" onclick="openQuickAddDepartmentModal('edit')">
                                        <i class="fas fa-plus me-1"></i>Add Department
                                    </button>
                                <?php endif; ?>
                            </div>
                            <select name="department_id" id="editTaskDepartment" class="form-select" onchange="onTaskDepartmentChanged('edit')">
                                <?php foreach ($departmentsList as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?> <?= !empty($d['code']) ? '(' . sanitize($d['code']) . ')' : '' ?></option>
                                <?php endforeach; ?>
                                <?php if ($isGlobalManager): ?>
                                    <option value="__add_new_dept__" class="fw-bold text-primary">➕ + Add New Department...</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Estimated Working Hours</label>
                            <input type="number" step="0.5" name="estimated_hours" id="editTaskEstimatedHours" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label text-secondary small fw-semibold">Deadline *</label>
                            <input type="datetime-local" name="deadline" id="editTaskDeadline" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold">Task Description & Deliverables</label>
                            <textarea name="description" id="editTaskDescription" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-3.5" id="btnSubmitEditTask">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ========================================================================= -->
<!-- MODAL: REASSIGN TASK                                                      -->
<!-- ========================================================================= -->
<?php if ($canManageTasks): ?>
<div class="modal fade" id="reassignTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-primary text-white">
                <h5 class="modal-title fw-bold text-white">
                    <i class="fas fa-user-tag me-2"></i>Reassign Task
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reassignTaskForm">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="task_id" id="reassignTaskIdInput">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Task</label>
                        <input type="text" id="reassignTaskTitleDisplay" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Reassign To Employee *</label>
                        <select name="assigned_to" id="reassignNewAssignee" class="form-select" required>
                            <option value="">Select New Assignee...</option>
                            <?php if (!empty($myDeptEmployees)): ?>
                                <optgroup label="⭐ <?= $isSeoLead ? 'SEO Team (Aman & Shivanshu)' : ($isDevLead ? 'Development Team (Abhay)' : 'My Department Team') ?>">
                                    <?php foreach ($myDeptEmployees as $u): ?>
                                        <option value="<?= $u['id'] ?>">
                                            <?= sanitize($u['name']) ?> (<?= sanitize($u['designation'] ?? 'Specialist') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if (!empty($otherEmployees)): ?>
                                <optgroup label="👥 Other Staff">
                                    <?php foreach ($otherEmployees as $u): ?>
                                        <option value="<?= $u['id'] ?>">
                                            <?= sanitize($u['name']) ?> (<?= sanitize($u['designation'] ?? 'Staff') ?> - <?= sanitize($u['dept_name'] ?? 'General') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-3.5">
                        <i class="fas fa-check me-1"></i>Reassign Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ========================================================================= -->
<!-- MODAL: QUICK WORK LOG / PROGRESS UPDATE                                   -->
<!-- ========================================================================= -->
<div class="modal fade" id="taskWorkLogModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom py-3 px-4 bg-info-subtle text-dark">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-clipboard-list text-info me-2"></i>Log Daily Task Progress
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="taskWorkLogForm">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="task_id" id="logTaskIdInput">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Task</label>
                        <input type="text" id="logTaskTitleDisplay" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Today's Work Completed *</label>
                        <textarea name="today_work" class="form-control" rows="3" placeholder="Detail deliverables and progress made today..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Overall Task Progress (%) *</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="range" class="form-range" name="progress_percentage" id="logProgressRange" min="0" max="100" value="50" oninput="document.getElementById('logProgressVal').textContent = this.value + '%'">
                            <span class="badge bg-primary px-2.5 py-1.5 fs-7 font-monospace" id="logProgressVal">50%</span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label text-secondary small fw-semibold">Blockers / Issues (Optional)</label>
                        <input type="text" name="issues" class="form-control" placeholder="Any roadblocks or waiting on dependencies?">
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info fw-bold px-3.5">
                        <i class="fas fa-paper-plane me-1"></i>Submit Daily Progress
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: EMERGENCY TASK REQUEST                                             -->
<!-- ========================================================================= -->
<div class="modal fade" id="emergencyRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom bg-danger text-white py-3 px-4" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;">
                <h5 class="modal-title fw-bold text-white">
                    <i class="fas fa-bolt me-2"></i>Emergency Task Escalation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emergencyRequestForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <div class="modal-body p-4">
                    <div class="alert alert-warning py-2 px-3 small border mb-3">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Submitting an emergency request alerts Admin & Managers immediately for priority review.
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Related Task *</label>
                        <select name="task_id" id="emgTaskSelect" class="form-select" required>
                            <option value="">-- Select Relevant Task --</option>
                            <?php foreach ($myAssignedTasks as $mat): ?>
                                <option value="<?= $mat['id'] ?>">#<?= $mat['id'] ?> - <?= sanitize($mat['title'] ?? '') ?></option>
                            <?php endforeach; ?>
                            <?php if (empty($myAssignedTasks) && !empty($tasks)): ?>
                                <?php foreach ($tasks as $mat): ?>
                                    <option value="<?= $mat['id'] ?>">#<?= $mat['id'] ?> - <?= sanitize($mat['title'] ?? '') ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Emergency Reason *</label>
                        <select name="reason" id="emgReasonSelect" class="form-select" required>
                            <option value="">-- Select Emergency Reason --</option>
                            <option value="Urgent client deadline">Urgent client deadline</option>
                            <option value="Critical production blocker / dependency">Critical production blocker / dependency</option>
                            <option value="System downtime / service outage">System downtime / service outage</option>
                            <option value="High-priority production bug fix">High-priority production bug fix</option>
                            <option value="Immediate client escalation">Immediate client escalation</option>
                            <option value="Scope or spec change escalation">Scope or spec change escalation</option>
                            <option value="Other emergency">Other emergency</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Emergency Justification & Details *</label>
                        <textarea name="message" id="emgMessageTextarea" class="form-control" rows="4" placeholder="Explain clearly why this task is critical and what immediate action is required..." required minlength="10"></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-secondary small fw-semibold">Supporting Attachment (Optional)</label>
                        <input type="file" name="attachment" id="emgAttachmentInput" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf,.zip,.doc,.docx,.txt">
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-bold px-3.5" id="btnSubmitEmergency">
                        <i class="fas fa-paper-plane me-1"></i>Submit Emergency Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: REPORT MISSING REQUIREMENTS / TASK FLAG                            -->
<!-- ========================================================================= -->
<div class="modal fade" id="flagTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom bg-warning-subtle text-dark py-3 px-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-flag text-warning me-2"></i>Report Missing Requirements / Flag Task
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="flagTaskForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="task_id" id="flagTaskIdInput">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Task</label>
                        <input type="text" id="flagTaskTitleDisplay" class="form-control bg-light fw-semibold" readonly>
                        <small class="text-muted fs-8 font-monospace" id="flagTaskIdDisplay"></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Flag Category *</label>
                        <select name="category" id="flagCategorySelect" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <option value="Missing Task Requirements">Missing Task Requirements</option>
                            <option value="Missing Content or Images">Missing Content or Images</option>
                            <option value="Missing Client Information">Missing Client Information</option>
                            <option value="Missing Credentials or Access">Missing Credentials or Access</option>
                            <option value="Missing Design or Reference Files">Missing Design or Reference Files</option>
                            <option value="Waiting for Approval">Waiting for Approval</option>
                            <option value="Unclear Instructions">Unclear Instructions</option>
                            <option value="Missing Dependency">Missing Dependency</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Missing Requirement Description *</label>
                        <textarea name="missing_requirement" id="flagMissingRequirementTextarea" class="form-control" rows="4" placeholder="Detail precisely what information, files, access, or instructions are missing..." required minlength="10"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Impact on Task *</label>
                        <select name="impact" id="flagImpactSelect" class="form-select" required>
                            <option value="">-- Select Impact Level --</option>
                            <option value="Task Cannot Start">Task Cannot Start</option>
                            <option value="Work Is Blocked">Work Is Blocked</option>
                            <option value="Work Can Continue Partially">Work Can Continue Partially</option>
                            <option value="Clarification Required">Clarification Required</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-secondary small fw-semibold">Supporting Attachment (Optional)</label>
                        <input type="file" name="attachment" id="flagAttachmentInput" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf,.zip,.doc,.docx,.txt">
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-3.5" id="btnSubmitFlag">
                        <i class="fas fa-flag me-1"></i>Submit Flag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                <input type="hidden" id="quickDeptTargetMode" value="create">
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
                <input type="hidden" id="quickProjTargetMode" value="create">
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
                                <?php foreach ($departmentsList as $d): ?>
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- ========================================================================= -->
<!-- JAVASCRIPT ENGINE & FILTERS                                               -->
<!-- ========================================================================= -->
<script>
    let tasksDataTable = null;

    $(document).ready(function() {
        if ($('#tasksDataTable').length && !$.fn.DataTable.isDataTable('#tasksDataTable')) {
            tasksDataTable = $('#tasksDataTable').DataTable({
                order: [[0, 'desc']],
                pageLength: 15,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: [8] }
                ],
                language: {
                    emptyTable: "No tasks found matching your criteria",
                    search: "Quick Filter Table:"
                }
            });
        }
    });

    // Combined Multi-Filter Engine
    function applyAllTaskFilters() {
        const query = (document.getElementById('taskSearchFilter')?.value || '').toLowerCase().trim();
        const statusFilter = (document.getElementById('taskStatusFilter')?.value || '').toLowerCase().trim();
        const priorityFilter = (document.getElementById('taskPriorityFilter')?.value || '').toLowerCase().trim();
        const deptFilter = (document.getElementById('taskDepartmentFilter')?.value || '').toLowerCase().trim();
        const projectFilter = (document.getElementById('taskProjectFilter')?.value || '').toLowerCase().trim();

        // 1. Filter Desktop DataTables Rows
        if (tasksDataTable) {
            $.fn.dataTable.ext.search = [];
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'tasksDataTable') return true;
                const row = $(tasksDataTable.row(dataIndex).node());

                const rStatus = (row.attr('data-status') || '').toLowerCase();
                const rPriority = (row.attr('data-priority') || '').toLowerCase();
                const rDept = (row.attr('data-department') || '').toLowerCase();
                const rProj = (row.attr('data-project') || '').toLowerCase();
                const rText = row.text().toLowerCase();

                if (statusFilter && rStatus !== statusFilter) return false;
                if (priorityFilter && rPriority !== priorityFilter) return false;
                if (deptFilter && rDept !== deptFilter) return false;
                if (projectFilter && rProj !== projectFilter) return false;
                if (query && !rText.includes(query)) return false;

                return true;
            });
            tasksDataTable.draw();
        }

        // 2. Filter Mobile Cards
        const mobileCards = document.querySelectorAll('.mobile-data-card');
        mobileCards.forEach(card => {
            const cStatus = (card.getAttribute('data-status') || '').toLowerCase();
            const cPriority = (card.getAttribute('data-priority') || '').toLowerCase();
            const cDept = (card.getAttribute('data-department') || '').toLowerCase();
            const cProj = (card.getAttribute('data-project') || '').toLowerCase();
            const cText = card.textContent.toLowerCase();

            let visible = true;
            if (statusFilter && cStatus !== statusFilter) visible = false;
            if (priorityFilter && cPriority !== priorityFilter) visible = false;
            if (deptFilter && cDept !== deptFilter) visible = false;
            if (projectFilter && cProj !== projectFilter) visible = false;
            if (query && !cText.includes(query)) visible = false;

            card.style.display = visible ? '' : 'none';
        });
    }

    function filterByKpi(status) {
        const select = document.getElementById('taskStatusFilter');
        if (!select) return;
        if (status === 'all') {
            select.value = '';
        } else {
            select.value = status;
        }
        applyAllTaskFilters();
    }

    function clearSearchFilter() {
        const input = document.getElementById('taskSearchFilter');
        if (input) {
            input.value = '';
            applyAllTaskFilters();
        }
    }

    function resetTaskFilters() {
        if (document.getElementById('taskSearchFilter')) document.getElementById('taskSearchFilter').value = '';
        if (document.getElementById('taskStatusFilter')) document.getElementById('taskStatusFilter').value = '';
        if (document.getElementById('taskPriorityFilter')) document.getElementById('taskPriorityFilter').value = '';
        if (document.getElementById('taskDepartmentFilter')) document.getElementById('taskDepartmentFilter').value = '';
        if (document.getElementById('taskProjectFilter')) document.getElementById('taskProjectFilter').value = '';
        applyAllTaskFilters();
    }

    function toggleMobileTaskFilters() {
        const panel = document.getElementById('taskFiltersPanel');
        if (!panel) return;
        if (panel.style.display === 'none' || window.getComputedStyle(panel).display === 'none') {
            panel.style.display = 'block';
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            panel.style.display = 'none';
        }
    }

    function setDeadlinePreset(preset) {
        const input = document.getElementById('createTaskDeadlineInput');
        if (!input) return;
        const d = new Date();
        if (preset === 'today') {
            d.setHours(18, 30, 0, 0);
        } else if (preset === 'tomorrow') {
            d.setDate(d.getDate() + 1);
            d.setHours(18, 30, 0, 0);
        } else if (preset === '3days') {
            d.setDate(d.getDate() + 3);
            d.setHours(18, 30, 0, 0);
        } else if (preset === '1week') {
            d.setDate(d.getDate() + 7);
            d.setHours(18, 30, 0, 0);
        }
        const pad = (n) => String(n).padStart(2, '0');
        const formatted = `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
        input.value = formatted;
    }

    // Modal Triggers
    function openEmergencyModal(taskId = null, taskTitle = null) {
        const modalEl = document.getElementById('emergencyRequestModal');
        if (!modalEl) return;
        const select = document.getElementById('emgTaskSelect');
        if (taskId && select) {
            let exists = false;
            for (let opt of select.options) {
                if (opt.value == taskId) {
                    opt.selected = true;
                    exists = true;
                    break;
                }
            }
            if (!exists) {
                const newOpt = new Option('#' + taskId + ' - ' + (taskTitle || 'Task #' + taskId), taskId, true, true);
                select.add(newOpt);
            }
        }
        const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)) : null;
        if (bsModal) bsModal.show();
    }

    function openFlagModal(taskId, taskTitle) {
        const modalEl = document.getElementById('flagTaskModal');
        if (!modalEl) return;
        document.getElementById('flagTaskIdInput').value = taskId;
        document.getElementById('flagTaskTitleDisplay').value = taskTitle || ('Task #' + taskId);
        document.getElementById('flagTaskIdDisplay').textContent = 'Task ID: #' + taskId;
        const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)) : null;
        if (bsModal) bsModal.show();
    }

    function openEditTaskModal(taskData) {
        const modalEl = document.getElementById('editTaskModal');
        if (!modalEl || !taskData) return;

        document.getElementById('editTaskIdInput').value = taskData.id;
        document.getElementById('editTaskIdBadge').textContent = taskData.id;
        document.getElementById('editTaskTitle').value = taskData.title || '';
        document.getElementById('editTaskPriority').value = (taskData.priority || 'medium').toLowerCase();
        document.getElementById('editTaskAssignee').value = taskData.assigned_to || taskData.assigned_to_employee_id || '';
        document.getElementById('editTaskProject').value = taskData.project_id || '';
        document.getElementById('editTaskDepartment').value = taskData.department_id || '';
        document.getElementById('editTaskEstimatedHours').value = taskData.estimated_hours || '4.0';
        document.getElementById('editTaskDescription').value = taskData.description || '';

        if (taskData.deadline) {
            const rawDl = taskData.deadline.replace(' ', 'T').substring(0, 16);
            document.getElementById('editTaskDeadline').value = rawDl;
        }

        const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)) : null;
        if (bsModal) bsModal.show();
    }

    function openReassignModal(taskId, currentAssigneeId, taskTitle) {
        const modalEl = document.getElementById('reassignTaskModal');
        if (!modalEl) return;
        document.getElementById('reassignTaskIdInput').value = taskId;
        document.getElementById('reassignTaskTitleDisplay').value = '#' + taskId + ' - ' + (taskTitle || '');
        if (document.getElementById('reassignNewAssignee')) {
            document.getElementById('reassignNewAssignee').value = currentAssigneeId || '';
        }
        const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)) : null;
        if (bsModal) bsModal.show();
    }

    function openWorkLogModal(taskId, taskTitle) {
        const modalEl = document.getElementById('taskWorkLogModal');
        if (!modalEl) return;
        document.getElementById('logTaskIdInput').value = taskId;
        document.getElementById('logTaskTitleDisplay').value = '#' + taskId + ' - ' + (taskTitle || '');
        const bsModal = (typeof bootstrap !== 'undefined' && bootstrap.Modal) ? (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)) : null;
        if (bsModal) bsModal.show();
    }

    // Export Tasks to Excel
    function exportTasksToExcel() {
        if (typeof XLSX === 'undefined') {
            Swal.fire('Export Notice', 'Excel export engine is initializing...', 'info');
            return;
        }
        const table = document.getElementById('tasksDataTable');
        if (!table) return;

        const wb = XLSX.utils.table_to_book(table, { sheet: "Tasks" });
        XLSX.writeFile(wb, `tasks_export_${new Date().toISOString().slice(0, 10)}.xlsx`);
    }

    // Create Task AJAX Form Submission
    document.getElementById('createTaskForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitCreateTask');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating Task...';
        }
        const formData = new FormData(this);
        formData.append('action', 'create');

        fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle me-1"></i>Create & Assign Task';
            }
            if (res.success) {
                Swal.fire({
                    title: 'Task Created!',
                    text: res.message,
                    icon: 'success',
                    timer: 1800
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle me-1"></i>Create & Assign Task';
            }
            Swal.fire('Error', 'Network or server error creating task: ' + err.message, 'error');
        });
    });

    // Edit Task AJAX Form Submission
    document.getElementById('editTaskForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitEditTask');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';
        }
        const formData = new FormData(this);
        formData.append('action', 'update');

        fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
            }
            if (res.success) {
                Swal.fire({
                    title: 'Task Updated!',
                    text: res.message,
                    icon: 'success',
                    timer: 1800
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
            }
            Swal.fire('Error', 'Network or server error updating task: ' + err.message, 'error');
        });
    });

    // Reassign Task AJAX Form Submission
    document.getElementById('reassignTaskForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'reassign');

        fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Task Reassigned!', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Reassign Error', res.message, 'error');
            }
        });
    });

    // Work Log AJAX Form Submission
    document.getElementById('taskWorkLogForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'add_log');

        fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Progress Logged!', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });

    // Emergency Request Form Submission
    document.getElementById('emergencyRequestForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitEmergency');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...';
        }

        const formData = new FormData(this);
        formData.append('action', 'submit');

        fetch('<?= BASE_URL ?>/api/emergency_requests', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Submit Emergency Request';
            }
            if (res.success) {
                Swal.fire({
                    title: 'Emergency Request Submitted!',
                    html: `<strong>Request ID: #${res.request_id}</strong><br>${res.message}<br><small class="text-secondary">Admin & Managers have been alerted for priority review.</small>`,
                    icon: 'success'
                }).then(() => location.reload());
            } else {
                Swal.fire('Submission Failed', res.message, 'error');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Submit Emergency Request';
            }
            Swal.fire('Error', 'Network or server error while submitting emergency request: ' + err.message, 'error');
        });
    });

    // Flag Task Form Submission
    document.getElementById('flagTaskForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitFlag');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting Flag...';
        }

        const formData = new FormData(this);
        formData.append('action', 'submit');

        fetch('<?= BASE_URL ?>/api/task_flags', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-flag me-1"></i>Submit Flag';
            }
            if (res.success) {
                Swal.fire({
                    title: 'Task Flagged Successfully!',
                    html: `<strong>Flag ID: #${res.flag_id}</strong><br>${res.message}<br><small class="text-secondary">Managers & Admins have been notified about the missing requirements.</small>`,
                    icon: 'success'
                }).then(() => location.reload());
            } else {
                Swal.fire('Submission Failed', res.message, 'error');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-flag me-1"></i>Submit Flag';
            }
            Swal.fire('Error', 'Network or server error submitting task flag: ' + err.message, 'error');
        });
    });

    // Clone Task
    function cloneTask(id) {
        Swal.fire({
            title: 'Clone Task?',
            text: 'Duplicate this deliverable with identical details?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-copy me-1"></i>Yes, Clone',
            confirmButtonColor: '#4f46e5'
        }).then(r => {
            if (r.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'clone');
                formData.append('task_id', id);
                formData.append('csrf_token', CSRF_TOKEN);

                fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Cloned!', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }

    // Delete Task
    function deleteTask(id) {
        Swal.fire({
            title: 'Delete Task?',
            text: 'Are you sure you want to delete this task? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete',
            confirmButtonColor: '#ef4444'
        }).then(r => {
            if (r.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('task_id', id);
                formData.append('csrf_token', CSRF_TOKEN);

                fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Deleted', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }

    // Reject Task by Employee
    function rejectTaskPrompt(id) {
        Swal.fire({
            title: 'Reject Assigned Task?',
            input: 'textarea',
            inputPlaceholder: 'State reason why you cannot accept this assignment...',
            showCancelButton: true,
            confirmButtonText: 'Reject Task',
            confirmButtonColor: '#ef4444',
            inputValidator: (val) => {
                if (!val || !val.trim()) return 'Reason is required.';
            }
        }).then(r => {
            if (r.isConfirmed && r.value) {
                const formData = new FormData();
                formData.append('action', 'reject');
                formData.append('task_id', id);
                formData.append('reason', r.value.trim());
                formData.append('csrf_token', CSRF_TOKEN);

                fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Task Rejected', res.message, 'info').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }

    // Auto-suggest code helpers for Quick Add modals
    function autoSuggestDeptCode(name) {
        const input = document.getElementById('quickDeptCodeInput');
        if (input && (!input.value || input.dataset.touched !== 'true')) {
            const clean = (name || '').replace(/[^a-zA-Z]/g, '').substr(0, 3).toUpperCase();
            input.value = clean;
        }
    }
    document.getElementById('quickDeptCodeInput')?.addEventListener('input', function() {
        this.dataset.touched = 'true';
    });

    function autoSuggestProjCode(name) {
        const input = document.getElementById('quickProjCodeInput');
        if (input && (!input.value || input.dataset.touched !== 'true')) {
            const clean = (name || '').replace(/[^a-zA-Z]/g, '').substr(0, 4).toUpperCase();
            const rand = Math.floor(10 + Math.random() * 90);
            input.value = clean ? `PRJ-${clean}-${rand}` : '';
        }
    }
    document.getElementById('quickProjCodeInput')?.addEventListener('input', function() {
        this.dataset.touched = 'true';
    });

    // Quick Add Department Modal Functions
    function openQuickAddDepartmentModal(targetMode) {
        const modeInput = document.getElementById('quickDeptTargetMode');
        if (modeInput) modeInput.value = targetMode || 'create';
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
                const targetMode = document.getElementById('quickDeptTargetMode')?.value || 'create';

                // Append to all department select elements
                const deptSelects = [
                    document.getElementById('createTaskDepartmentSelect'),
                    document.getElementById('editTaskDepartment'),
                    document.getElementById('quickProjDeptSelect')
                ];

                deptSelects.forEach(selectEl => {
                    if (selectEl) {
                        const opt = document.createElement('option');
                        opt.value = deptId;
                        opt.textContent = deptName + deptCode;
                        opt.setAttribute('data-code', res.data.code || '');
                        
                        const addNewOpt = selectEl.querySelector('option[value="__add_new_dept__"]');
                        if (addNewOpt) {
                            selectEl.insertBefore(opt, addNewOpt);
                        } else {
                            selectEl.appendChild(opt);
                        }
                    }
                });

                // Select in current active target mode
                let activeSelect = null;
                if (targetMode === 'create') activeSelect = document.getElementById('createTaskDepartmentSelect');
                else if (targetMode === 'edit') activeSelect = document.getElementById('editTaskDepartment');

                if (activeSelect) {
                    activeSelect.value = String(deptId);
                    onTaskDepartmentChanged(targetMode);
                }

                const modalEl = document.getElementById('quickAddDepartmentModal');
                if (modalEl) {
                    const bsModal = bootstrap.Modal.getInstance(modalEl);
                    if (bsModal) bsModal.hide();
                }

                setTimeout(() => {
                    if (document.querySelector('.modal.show')) {
                        document.body.classList.add('modal-open');
                    }
                }, 350);

                Swal.fire({
                    title: 'Department Added!',
                    text: `Department "${deptName}" created and selected!`,
                    icon: 'success',
                    timer: 1600,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', res.message || 'Could not create department', 'error');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Save & Select Department';
            }
            Swal.fire('Error', 'Network or server error: ' + err.message, 'error');
        });
    }

    // Quick Add Project Modal Functions
    function openQuickAddProjectModal(targetMode) {
        const modeInput = document.getElementById('quickProjTargetMode');
        if (modeInput) modeInput.value = targetMode || 'create';
        const form = document.getElementById('quickAddProjectForm');
        if (form) {
            form.reset();
            const codeInp = document.getElementById('quickProjCodeInput');
            if (codeInp) delete codeInp.dataset.touched;
        }

        // Sync department if selected in task form
        let currentDeptId = 0;
        if (targetMode === 'create') currentDeptId = document.getElementById('createTaskDepartmentSelect')?.value;
        else if (targetMode === 'edit') currentDeptId = document.getElementById('editTaskDepartment')?.value;

        const projDeptSelect = document.getElementById('quickProjDeptSelect');
        if (projDeptSelect && currentDeptId && currentDeptId !== '0' && currentDeptId !== '__add_new_dept__') {
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
                const targetMode = document.getElementById('quickProjTargetMode')?.value || 'create';

                // Append to all project select elements
                const projSelects = [
                    document.getElementById('createTaskProjectSelect'),
                    document.getElementById('editTaskProject')
                ];

                projSelects.forEach(selectEl => {
                    if (selectEl) {
                        const opt = document.createElement('option');
                        opt.value = projId;
                        opt.textContent = projName + projCode;
                        opt.setAttribute('data-dept', deptId);
                        opt.setAttribute('data-code', res.data.code || '');

                        const addNewOpt = selectEl.querySelector('option[value="__add_new_project__"]');
                        if (addNewOpt) {
                            selectEl.insertBefore(opt, addNewOpt);
                        } else {
                            selectEl.appendChild(opt);
                        }
                    }
                });

                // Select in current target mode
                let activeSelect = null;
                if (targetMode === 'create') activeSelect = document.getElementById('createTaskProjectSelect');
                else if (targetMode === 'edit') activeSelect = document.getElementById('editTaskProject');

                if (activeSelect) {
                    activeSelect.value = String(projId);
                    onTaskProjectChanged(targetMode);
                }

                const modalEl = document.getElementById('quickAddProjectModal');
                if (modalEl) {
                    const bsModal = bootstrap.Modal.getInstance(modalEl);
                    if (bsModal) bsModal.hide();
                }

                setTimeout(() => {
                    if (document.querySelector('.modal.show')) {
                        document.body.classList.add('modal-open');
                    }
                }, 350);

                Swal.fire({
                    title: 'Project Added!',
                    text: `Project "${projName}" created and selected!`,
                    icon: 'success',
                    timer: 1600,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', res.message || 'Could not create project', 'error');
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Save & Select Project';
            }
            Swal.fire('Error', 'Network or server error: ' + err.message, 'error');
        });
    }

    // Dynamic Linking Logic between Department, Project, and Assignee
    function onTaskDepartmentChanged(mode) {
        let deptSelect = null;
        let assigneeSelect = null;
        let projectSelect = null;

        if (mode === 'create') {
            deptSelect = document.getElementById('createTaskDepartmentSelect');
            assigneeSelect = document.getElementById('createTaskAssigneeSelect');
            projectSelect = document.getElementById('createTaskProjectSelect');
        } else if (mode === 'edit') {
            deptSelect = document.getElementById('editTaskDepartment');
            assigneeSelect = document.getElementById('editTaskAssignee');
            projectSelect = document.getElementById('editTaskProject');
        }

        if (!deptSelect) return;
        const val = deptSelect.value;

        if (val === '__add_new_dept__') {
            deptSelect.value = '0';
            openQuickAddDepartmentModal(mode);
            return;
        }

        const deptId = parseInt(val, 10);

        // Highlight/indicate assignees in this department
        if (assigneeSelect && deptId > 0) {
            const options = assigneeSelect.querySelectorAll('option[data-dept]');
            options.forEach(opt => {
                const optDept = parseInt(opt.getAttribute('data-dept'), 10);
                if (optDept === deptId) {
                    if (!opt.textContent.startsWith('⭐ ')) {
                        opt.textContent = '⭐ ' + opt.textContent;
                    }
                    opt.style.fontWeight = 'bold';
                    opt.style.color = '#4f46e5';
                } else {
                    opt.textContent = opt.textContent.replace(/^⭐\s*/, '');
                    opt.style.fontWeight = 'normal';
                    opt.style.color = '';
                }
            });
        }
    }

    function onTaskProjectChanged(mode) {
        let projectSelect = null;
        let deptSelect = null;

        if (mode === 'create') {
            projectSelect = document.getElementById('createTaskProjectSelect');
            deptSelect = document.getElementById('createTaskDepartmentSelect');
        } else if (mode === 'edit') {
            projectSelect = document.getElementById('editTaskProject');
            deptSelect = document.getElementById('editTaskDepartment');
        }

        if (!projectSelect) return;
        const val = projectSelect.value;

        if (val === '__add_new_project__') {
            projectSelect.value = '';
            openQuickAddProjectModal(mode);
            return;
        }

        const selOption = projectSelect.options[projectSelect.selectedIndex];
        const projDept = selOption ? selOption.getAttribute('data-dept') : null;
        if (deptSelect && projDept && parseInt(projDept, 10) > 0) {
            if (deptSelect.value === '0' || deptSelect.value === '') {
                deptSelect.value = projDept;
                onTaskDepartmentChanged(mode);
            }
        }
    }

    function onTaskAssigneeChanged(mode) {
        let assigneeSelect = null;
        let deptSelect = null;

        if (mode === 'create') {
            assigneeSelect = document.getElementById('createTaskAssigneeSelect');
            deptSelect = document.getElementById('createTaskDepartmentSelect');
        } else if (mode === 'edit') {
            assigneeSelect = document.getElementById('editTaskAssignee');
            deptSelect = document.getElementById('editTaskDepartment');
        }

        if (!assigneeSelect || !deptSelect) return;
        const selOption = assigneeSelect.options[assigneeSelect.selectedIndex];
        const userDept = selOption ? selOption.getAttribute('data-dept') : null;
        if (userDept && parseInt(userDept, 10) > 0) {
            if (deptSelect.value === '0' || deptSelect.value === '') {
                deptSelect.value = userDept;
            }
        }
    }

    // Auto-open Create Task modal if URL contains ?action=new_task or ?assign_to=...
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'new_task' || urlParams.get('new_task') || urlParams.get('assign_to')) {
            const assignTo = urlParams.get('assign_to');
            const modalEl = document.getElementById('createTaskModal');
            if (modalEl) {
                if (assignTo) {
                    const selectEl = modalEl.querySelector('select[name="assigned_to"]');
                    if (selectEl) {
                        selectEl.value = assignTo;
                        onTaskAssigneeChanged('create');
                    }
                }
                const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                bsModal.show();
            }
        }
    });
</script>
