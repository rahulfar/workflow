<?php
$pageTitle = "Kanban Board";
$user = requireLogin();
$db = Database::getConnection();
$canManageTasks = canCreateOrEditTasks();
$userCategory = getUserRoleCategory($user);
$isGlobalManager = in_array($userCategory, ['admin', 'manager', 'product_manager']);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$visCondition = getTaskVisibilityCondition($user, 't');
if ($visCondition['sql'] === '1=1') {
    $stmt = $db->prepare("
        SELECT t.*, u.name as assignee_name, d.code as dept_code
        FROM tasks t
        LEFT JOIN users u ON (t.assigned_to = u.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u.id))
        LEFT JOIN departments d ON t.department_id = d.id
        ORDER BY t.id DESC
    ");
    $stmt->execute();
} else {
    $stmt = $db->prepare("
        SELECT t.*, u.name as assignee_name, d.code as dept_code
        FROM tasks t
        LEFT JOIN users u ON (t.assigned_to = u.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u.id))
        LEFT JOIN departments d ON t.department_id = d.id
        WHERE {$visCondition['sql']}
        ORDER BY t.id DESC
    ");
    $stmt->execute($visCondition['params']);
}
$allTasks = $stmt->fetchAll() ?: [];

$columns = [
    'pending' => ['title' => 'To Do / Pending', 'badge' => 'bg-secondary', 'icon' => 'fas fa-clock'],
    'running' => ['title' => 'In Progress', 'badge' => 'bg-primary', 'icon' => 'fas fa-spinner fa-spin'],
    'pending_approval' => ['title' => 'Awaiting Approval', 'badge' => 'bg-warning', 'icon' => 'fas fa-hourglass-half'],
    'completed' => ['title' => 'Done', 'badge' => 'bg-success', 'icon' => 'fas fa-check-circle']
];
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-columns text-info me-2"></i>Kanban Workflow Board</h3>
                <p class="text-secondary small mb-0">Drag and drop task cards to transition work stages. Tasks moved to completion require manager approval.</p>
            </div>
            <a href="<?= BASE_URL ?>/tasks" class="btn btn-outline-primary"><i class="fas fa-list me-1"></i>List View</a>
        </div>

        <div class="kanban-board">
            <?php foreach ($columns as $statusKey => $col): 
                $colTasks = array_filter($allTasks, function($t) use ($statusKey) {
                    if ($statusKey === 'pending_approval') {
                        return in_array($t['status'], ['pending_approval', 'in_review']);
                    }
                    if ($statusKey === 'completed') {
                        return in_array($t['status'], ['completed', 'done']);
                    }
                    return $t['status'] === $statusKey;
                });
            ?>
                <div class="kanban-column">
                    <div class="kanban-header">
                        <div class="d-flex align-items-center gap-2">
                            <i class="<?= $col['icon'] ?> text-dark"></i>
                            <span class="fw-bold text-dark"><?= $col['title'] ?></span>
                        </div>
                        <span class="badge <?= $col['badge'] ?> rounded-pill"><?= count($colTasks) ?></span>
                    </div>

                    <div class="kanban-cards" data-status="<?= $statusKey ?>">
                        <?php foreach ($colTasks as $task): ?>
                            <div class="kanban-card" data-task-id="<?= $task['id'] ?>">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge bg-light text-dark border font-monospace">#<?= $task['id'] ?></span>
                                    <?= getPriorityBadge($task['priority'] ?? 'medium') ?>
                                </div>
                                <h6 class="fw-bold text-dark mb-2"><?= sanitize($task['title'] ?? '') ?></h6>
                                <p class="small text-secondary mb-3 line-clamp-2"><?= sanitize(!empty($task['description']) ? $task['description'] : 'No details provided.') ?></p>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top small">
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="fas fa-user-circle text-primary"></i>
                                        <span class="text-dark fw-semibold"><?= sanitize($task['assignee_name'] ?? 'Unassigned') ?></span>
                                    </div>
                                    <span class="text-muted font-monospace"><i class="fas fa-hourglass-half me-1"></i><?= formatDuration($task['spent_seconds'] ?? 0) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
