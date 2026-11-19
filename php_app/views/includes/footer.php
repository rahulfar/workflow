</div> <!-- End app-wrapper -->

<!-- Sidebar Overlay Backdrop -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="closeMobileSidebar(event)"></div>

<?php
$footerRoute = trim($_SERVER['REQUEST_URI'] ?? '', '/');
$footerPendingCount = 0;
if (function_exists('canCreateOrEditTasks') && canCreateOrEditTasks()) {
    try {
        $fDb = Database::getConnection();
        $footerPendingCount = (int)$fDb->query("SELECT COUNT(*) FROM tasks WHERE status IN ('pending_approval', 'in_review')")->fetchColumn();
    } catch (Exception $fEx) {}
}
?>

<!-- App-Style Mobile Bottom Navigation Bar (< 768px) -->
<nav class="mobile-bottom-nav d-md-none" id="mobileBottomNav">
    <a href="<?= BASE_URL ?>/dashboard" class="mobile-bottom-nav-item <?= str_contains($footerRoute, 'dashboard') ? 'active' : '' ?>">
        <i class="fas fa-chart-pie" style="pointer-events: none;"></i>
        <span>Home</span>
    </a>
    <a href="<?= BASE_URL ?>/workday" class="mobile-bottom-nav-item <?= str_contains($footerRoute, 'workday') ? 'active' : '' ?>">
        <i class="fas fa-briefcase" style="pointer-events: none;"></i>
        <span>Workday</span>
    </a>
    <a href="<?= BASE_URL ?>/tasks" class="mobile-bottom-nav-item <?= str_contains($footerRoute, 'tasks') || str_contains($footerRoute, 'kanban') ? 'active' : '' ?>">
        <i class="fas fa-tasks" style="pointer-events: none;"></i>
        <span>Tasks</span>
        <?php if ($footerPendingCount > 0): ?>
            <span class="badge-dot" style="background:#f59e0b;" title="<?= $footerPendingCount ?> approvals pending"></span>
        <?php endif; ?>
    </a>
    <a href="<?= BASE_URL ?>/attendance" class="mobile-bottom-nav-item <?= str_contains($footerRoute, 'attendance') ? 'active' : '' ?>">
        <i class="fas fa-user-clock" style="pointer-events: none;"></i>
        <span>Attendance</span>
    </a>
    <a href="javascript:void(0)" class="mobile-bottom-nav-item" id="mobileMoreMenuBtn" onclick="toggleMobileSidebar(event)">
        <i class="fas fa-ellipsis-h" style="pointer-events: none;"></i>
        <span>More</span>
    </a>
</nav>

<!-- jQuery & Bootstrap 5 Bundle JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js 4+ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

<!-- SheetJS for Excel Export -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<!-- Custom JS Engine -->
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<script src="<?= BASE_URL ?>/assets/js/timer.js"></script>
<script src="<?= BASE_URL ?>/assets/js/kanban.js"></script>

<!-- ========================================================= -->
<!-- AI WORKFLOW ASSISTANT INTEGRATION                         -->
<!-- ========================================================= -->
<?php require_once __DIR__ . '/ai_assistant.php'; ?>

<script>
// Backward-compatible global aliases for existing scripts
function toggleAiModal() {
    toggleAiAssistant();
}
function askAiQuestion(q) {
    useAiSuggestedCommand(q);
}
</script>
</body>
</html>

