<?php
$user = getCurrentUser();
$currentRoute = trim($_SERVER['REQUEST_URI'] ?? '', '/');
$isAdminOrManager = hasRole(['admin', 'manager', 'product_manager']) || canCreateOrEditTasks();

$sidebarPendingEmergencyCount = $isAdminOrManager ? getPendingEmergencyRequestsCount() : getPendingEmergencyRequestsCount((int)($user['id'] ?? 0));
$sidebarOpenFlagsCount = $isAdminOrManager ? getOpenTaskFlagsCount() : getOpenTaskFlagsCount((int)($user['id'] ?? 0));

$requestedView = $_GET['view_as'] ?? '';
$userCategory = getUserRoleCategory($user);
if ($requestedView === 'employee' || $userCategory === 'employee') {
    $sidebarRoleView = 'employee';
} elseif (hasRole('admin') && in_array($requestedView, ['admin', 'manager', 'product_manager'])) {
    $sidebarRoleView = $requestedView;
} else {
    $sidebarRoleView = $userCategory;
}
?>
<aside class="sidebar sidebar-<?= $sidebarRoleView ?>" id="appSidebar">
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <a href="<?= BASE_URL ?>/dashboard" class="brand-logo">
            <img src="<?= BASE_URL ?>/assets/img/krafting-logo.webp" alt="Company Logo" style="height: 38px; object-fit: contain; margin-right: 0.5rem;">
            <span>Work<span class="text-primary">Flow</span></span>
        </a>
        <button type="button" class="btn btn-icon d-lg-none text-secondary fs-5 p-1 border-0" id="sidebarCloseBtn" onclick="toggleMobileSidebar(event)" aria-label="Close sidebar">
            <i class="fas fa-times" style="pointer-events: none;"></i>
        </button>
    </div>

    <div class="sidebar-menu">
        <div class="menu-category">Main Workspace</div>
        
        <a href="<?= BASE_URL ?>/dashboard" class="nav-link-custom <?= str_contains($currentRoute, 'dashboard') ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= BASE_URL ?>/workday" class="nav-link-custom <?= str_contains($currentRoute, 'workday') ? 'active' : '' ?>">
            <i class="fas fa-briefcase text-primary"></i>
            <span>My Workday</span>
        </a>

        <a href="<?= BASE_URL ?>/live-status" class="nav-link-custom <?= str_contains($currentRoute, 'live-status') || str_contains($currentRoute, 'team-status') ? 'active' : '' ?>">
            <i class="fas fa-satellite-dish text-success"></i>
            <span>Live Team Status</span>
            <span class="badge bg-success rounded-pill ms-auto fs-9">LIVE</span>
        </a>

        <!-- Task Management with Nested Sub-Options -->
        <div class="nav-task-group mb-1">
            <a href="<?= BASE_URL ?>/tasks" class="nav-link-custom <?= ((str_contains($currentRoute, 'tasks') && !str_contains($currentRoute, 'flagged-tasks')) || str_contains($currentRoute, 'task-details')) && !str_contains($currentRoute, 'kanban') && !str_contains($currentRoute, 'emergency-approvals') ? 'active' : '' ?>">
                <i class="fas fa-tasks text-primary"></i>
                <span><?= $isAdminOrManager ? 'Task Management' : 'My Tasks' ?></span>
            </a>
            <div class="nav-sub-items ms-3 ps-2 border-start border-2 border-primary-subtle d-flex flex-column gap-1 my-1">
                <a href="<?= BASE_URL ?>/flagged-tasks" class="nav-link-custom py-1.5 px-2.5 fs-8 <?= str_contains($currentRoute, 'flagged-tasks') ? 'active' : '' ?>" style="margin-bottom: 2px;">
                    <i class="fas fa-flag text-warning fs-8"></i>
                    <span>Flagged Tasks</span>
                    <?php if (!empty($sidebarOpenFlagsCount)): ?>
                        <span class="badge bg-warning text-dark rounded-pill ms-auto fs-9"><?= $sidebarOpenFlagsCount ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?= BASE_URL ?>/emergency-approvals" class="nav-link-custom py-1.5 px-2.5 fs-8 <?= str_contains($currentRoute, 'emergency-approvals') ? 'active' : '' ?>" style="margin-bottom: 2px;">
                    <i class="fas fa-bolt text-danger fs-8"></i>
                    <span><?= $isAdminOrManager ? 'Emergency Approvals' : 'Emergency Requests' ?></span>
                    <?php if (!empty($sidebarPendingEmergencyCount)): ?>
                        <span class="badge bg-danger rounded-pill ms-auto fs-9"><?= $sidebarPendingEmergencyCount ?></span>
                    <?php endif; ?>
                </a>

                <a href="<?= BASE_URL ?>/kanban" class="nav-link-custom py-1.5 px-2.5 fs-8 <?= str_contains($currentRoute, 'kanban') ? 'active' : '' ?>" style="margin-bottom: 2px;">
                    <i class="fas fa-columns text-info fs-8"></i>
                    <span>Kanban Board</span>
                </a>
            </div>
        </div>

        <a href="<?= BASE_URL ?>/work-log" class="nav-link-custom <?= str_contains($currentRoute, 'work-log') ? 'active' : '' ?>">
            <i class="fas fa-book-open"></i>
            <span>Daily Work Logs</span>
        </a>

        <a href="<?= BASE_URL ?>/chat" class="nav-link-custom <?= str_contains($currentRoute, 'chat') ? 'active' : '' ?>">
            <i class="fas fa-comments text-info"></i>
            <span>Internal Chat & AI</span>
            <span class="badge bg-primary rounded-pill ms-auto fs-9" id="sidebarChatBadge" style="display:none;">0</span>
        </a>

        <a href="<?= BASE_URL ?>/projects" class="nav-link-custom <?= str_contains($currentRoute, 'projects') ? 'active' : '' ?>">
            <i class="fas fa-project-diagram"></i>
            <span>Projects & Sprints</span>
        </a>

        <?php if (hasRole('admin')): ?>
        <a href="<?= BASE_URL ?>/payroll" class="nav-link-custom <?= str_contains($currentRoute, 'payroll') ? 'active' : '' ?>">
            <i class="fas fa-wallet text-success"></i>
            <span>Payroll Management</span>
        </a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/employees" class="nav-link-custom <?= str_contains($currentRoute, 'employees') ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            <span>Employees</span>
        </a>

        <a href="<?= BASE_URL ?>/departments" class="nav-link-custom <?= str_contains($currentRoute, 'departments') ? 'active' : '' ?>">
            <i class="fas fa-building"></i>
            <span>Departments</span>
        </a>

        <div class="menu-category">Activity & Analytics</div>

        <a href="<?= BASE_URL ?>/attendance" class="nav-link-custom <?= str_contains($currentRoute, 'attendance') ? 'active' : '' ?>">
            <i class="fas fa-user-clock"></i>
            <span>Attendance</span>
        </a>

        <a href="<?= BASE_URL ?>/activity" class="nav-link-custom <?= str_contains($currentRoute, 'activity') ? 'active' : '' ?>">
            <i class="fas fa-stream"></i>
            <span>Activity History</span>
        </a>

        <a href="<?= BASE_URL ?>/reports" class="nav-link-custom <?= str_contains($currentRoute, 'reports') ? 'active' : '' ?>">
            <i class="fas fa-file-invoice"></i>
            <span><?= $isAdminOrManager ? 'Reports & Exports' : 'My Performance' ?></span>
        </a>

        <a href="<?= BASE_URL ?>/notifications" class="nav-link-custom <?= str_contains($currentRoute, 'notifications') ? 'active' : '' ?>">
            <i class="fas fa-bell"></i>
            <span>Notifications</span>
        </a>

        <a href="<?= BASE_URL ?>/leaves" class="nav-link-custom <?= str_contains($currentRoute, 'leaves') ? 'active' : '' ?>">
            <i class="fas fa-calendar-minus"></i>
            <span>Leave Requests</span>
        </a>

        <a href="<?= BASE_URL ?>/calendar" class="nav-link-custom <?= str_contains($currentRoute, 'calendar') ? 'active' : '' ?>">
            <i class="fas fa-calendar-alt"></i>
            <span>Schedule Calendar</span>
        </a>

        <a href="<?= BASE_URL ?>/files" class="nav-link-custom <?= str_contains($currentRoute, 'files') ? 'active' : '' ?>">
            <i class="fas fa-folder-open"></i>
            <span>File Manager</span>
        </a>

        <div class="menu-category">System & Support</div>

        <?php if (hasRole('admin')): ?>
        <a href="<?= BASE_URL ?>/shift-settings" class="nav-link-custom <?= str_contains($currentRoute, 'shift-settings') ? 'active' : '' ?>">
            <i class="fas fa-business-time text-warning"></i>
            <span>Shift Settings</span>
        </a>
        <a href="<?= BASE_URL ?>/ip-settings" class="nav-link-custom <?= str_contains($currentRoute, 'ip-settings') ? 'active' : '' ?>">
            <i class="fas fa-wifi text-success"></i>
            <span>Wi-Fi IP Whitelist</span>
        </a>
        <a href="<?= BASE_URL ?>/settings" class="nav-link-custom <?= str_contains($currentRoute, 'settings') && !str_contains($currentRoute, 'shift-settings') && !str_contains($currentRoute, 'ip-settings') ? 'active' : '' ?>">
            <i class="fas fa-sliders-h"></i>
            <span>Admin Settings</span>
        </a>
        <?php endif; ?>

        <a href="<?= BASE_URL ?>/profile" class="nav-link-custom <?= str_contains($currentRoute, 'profile') ? 'active' : '' ?>">
            <i class="fas fa-user-cog"></i>
            <span>My Profile</span>
        </a>

        <a href="<?= BASE_URL ?>/help" class="nav-link-custom <?= str_contains($currentRoute, 'help') ? 'active' : '' ?>">
            <i class="fas fa-question-circle"></i>
            <span>Help & Support</span>
        </a>

        <a href="<?= BASE_URL ?>/logout" class="nav-link-custom text-danger mb-3">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>

    <!-- Sidebar Footer User Card -->
    <div class="p-3 border-top bg-light">
        <div class="d-flex align-items-center gap-2">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=4f46e5&color=fff" class="rounded-circle border" width="36" height="36" alt="Avatar">
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold text-dark fs-7 text-truncate"><?= sanitize($user['name'] ?? 'Guest') ?></div>
                <small class="text-primary fw-semibold fs-8 d-block text-truncate"><i class="fas fa-shield-alt me-1"></i><?= sanitize($user['role_title'] ?? 'Role') ?></small>
            </div>
        </div>
    </div>
</aside>
