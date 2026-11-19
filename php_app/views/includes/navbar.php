<?php
$user = getCurrentUser();
$requestedView = $_GET['view_as'] ?? '';
$userCategory = getUserRoleCategory($user);
if ($requestedView === 'employee' || $userCategory === 'employee') {
    $navRoleView = 'employee';
} elseif (hasRole('admin') && in_array($requestedView, ['admin', 'manager', 'product_manager'])) {
    $navRoleView = $requestedView;
} else {
    $navRoleView = $userCategory;
}
?>
<header class="top-navbar top-navbar-<?= $navRoleView ?>">
    <div class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden me-2">
        <button class="btn btn-icon d-lg-none text-dark fs-4 p-1 border-0" id="sidebarToggleBtn" onclick="toggleMobileSidebar(event)" aria-label="Toggle Navigation">
            <i class="fas fa-bars" style="pointer-events: none;"></i>
        </button>

        <!-- Mobile Brand Logo -->
        <a href="<?= BASE_URL ?>/dashboard" class="brand-logo d-flex align-items-center gap-1.5 text-decoration-none d-lg-none me-1">
            <img src="<?= BASE_URL ?>/assets/img/krafting-logo.webp" alt="Logo" style="height: 30px; object-fit: contain;">
            <span class="fw-bold text-dark fs-6 font-heading text-nowrap">Work<span class="text-primary">Flow</span></span>
        </a>

        <!-- Global Search Bar -->
        <div class="search-bar-wrap d-none d-md-block">
            <i class="fas fa-search"></i>
            <input type="text" id="globalSearchInput" placeholder="Search tasks, projects, employees..." autocomplete="off">
            <div id="globalSearchResults" class="dropdown-menu glass-card shadow-lg w-100 p-0" style="display:none; position:absolute; top:45px; left:0; z-index:1060;"></div>
        </div>
    </div>

    <div class="nav-actions d-flex align-items-center gap-2 flex-shrink-0">
        <!-- Live Clock -->
        <div class="d-none d-md-flex align-items-center gap-2 text-secondary bg-light px-3 py-1 rounded-pill fs-7 border">
            <i class="fas fa-clock text-primary"></i>
            <span id="navLiveClock" class="font-monospace fw-semibold text-dark">00:00:00</span>
        </div>

        <?php if (hasRole('admin')): ?>
        <!-- Admin Role Preview Switcher -->
        <div class="dropdown d-none d-lg-block">
            <button class="btn btn-sm btn-light border rounded-pill px-3 dropdown-toggle text-dark fw-semibold" type="button" id="rolePreviewDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-eye text-primary me-1"></i> Preview Role View
            </button>
            <ul class="dropdown-menu dropdown-menu-end glass-card shadow-lg p-2" aria-labelledby="rolePreviewDropdown" style="min-width: 210px;">
                <li><h6 class="dropdown-header text-uppercase fs-8 text-muted">Select Dashboard View</h6></li>
                <li><a class="dropdown-item rounded py-1.5 fs-7" href="<?= BASE_URL ?>/dashboard?view_as=admin"><i class="fas fa-crown text-primary me-2"></i>Admin Executive View</a></li>
                <li><a class="dropdown-item rounded py-1.5 fs-7" href="<?= BASE_URL ?>/dashboard?view_as=manager"><i class="fas fa-user-shield text-teal me-2"></i>Manager Team View</a></li>
                <li><a class="dropdown-item rounded py-1.5 fs-7" href="<?= BASE_URL ?>/dashboard?view_as=product_manager"><i class="fas fa-cubes text-warning me-2"></i>Product Manager View</a></li>
                <li><a class="dropdown-item rounded py-1.5 fs-7" href="<?= BASE_URL ?>/dashboard?view_as=employee"><i class="fas fa-user text-info me-2"></i>Employee View</a></li>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Quick Clock-In / Clock-Out Button (Wi-Fi Restricted, Mobile & Desktop Responsive) -->
        <?php $navIpAuth = isClientIpAuthorized(); ?>
        <button class="btn btn-sm btn-outline-success rounded-pill px-2.5 px-sm-3 shift-toggle-btn fs-8 fw-semibold align-items-center wifi-restricted-btn <?= $navIpAuth ? 'd-inline-flex' : 'd-none' ?>" 
                style="<?= $navIpAuth ? '' : 'display:none !important;' ?>"
                onclick="handleClockInOut('clock_in')" title="Shift Control">
            <i class="fas fa-fingerprint me-1"></i><span class="d-none d-sm-inline">Start Shift</span><span class="d-inline d-sm-none">Shift</span>
        </button>

        <!-- Notifications Dropdown -->
        <div class="dropdown">
            <div class="nav-icon-btn" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="badge-dot" id="notifBadgeDot" style="display:none;"></span>
            </div>
            <div class="dropdown-menu dropdown-menu-end glass-card p-0 shadow-lg" style="width: 300px; max-width: 90vw;" aria-labelledby="notificationsDropdown">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-bell me-2 text-warning"></i>Notifications</h6>
                    <small class="text-muted">Real-time alerts</small>
                </div>
                <div id="notifContainer" style="max-height: 300px; overflow-y: auto;">
                    <div class="dropdown-item text-muted text-center py-3">Loading notifications...</div>
                </div>
            </div>
        </div>

        <!-- User Profile Dropdown -->
        <div class="dropdown">
            <div class="user-profile-menu" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=4f46e5&color=fff" alt="User Avatar" class="user-avatar">
                <div class="d-none d-md-block text-start">
                    <div class="fw-bold text-dark fs-7 line-height-1"><?= sanitize($user['name'] ?? 'Guest') ?></div>
                    <small class="text-muted fs-8"><?= sanitize($user['role_title'] ?? 'Role') ?></small>
                </div>
                <i class="fas fa-chevron-down text-muted fs-8 ms-1 d-none d-md-inline"></i>
            </div>
            <ul class="dropdown-menu dropdown-menu-end glass-card shadow-lg">
                <li><a class="dropdown-item text-dark" href="<?= BASE_URL ?>/profile"><i class="fas fa-user-cog me-2 text-primary"></i>My Profile</a></li>
                <?php if (hasRole('admin')): ?>
                <li><a class="dropdown-item text-dark" href="<?= BASE_URL ?>/settings"><i class="fas fa-cog me-2 text-info"></i>Settings</a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider border-secondary"></li>
                <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
            </ul>
        </div>
    </div>
</header>
