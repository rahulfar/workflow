<?php
$pageTitle = "Daily Work Logs";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();

// Fetch work logs
$stmt = $db->prepare("
    SELECT tl.*, t.title as task_title, u.name as user_name
    FROM task_logs tl
    JOIN tasks t ON tl.task_id = t.id
    JOIN users u ON tl.user_id = u.id
    ORDER BY tl.id DESC LIMIT 50
");
$stmt->execute();
$logs = $stmt->fetchAll();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-book-open text-primary me-2"></i>Daily Work Logs</h3>
                <p class="text-secondary small mb-0">Record of daily employee progress, work logs, completed tasks, and reported issues.</p>
            </div>
        </div>

        <!-- Desktop Table View (>= 768px) -->
        <div class="glass-card d-none d-md-block">
            <div class="table-responsive">
                <table id="workLogsTable" class="table align-middle">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Task Title</th>
                            <th>Work Progress</th>
                            <th>Progress %</th>
                            <th>Issues</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($logs): foreach ($logs as $l): ?>
                            <tr>
                                <td class="font-monospace text-dark fw-semibold"><?= formatISTDate($l['log_date']) ?></td>
                                <td class="fw-bold text-dark"><?= sanitize($l['user_name']) ?></td>
                                <td><a href="<?= BASE_URL ?>/task-details?id=<?= $l['task_id'] ?>" class="text-primary fw-semibold text-decoration-none"><?= sanitize($l['task_title']) ?></a></td>
                                <td class="small text-secondary"><?= sanitize($l['today_work']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: <?= $l['progress_percentage'] ?>%"></div>
                                        </div>
                                        <span class="small font-monospace fw-bold"><?= $l['progress_percentage'] ?>%</span>
                                    </div>
                                </td>
                                <td class="small text-muted"><?= sanitize($l['issues'] ?: 'None') ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">No daily work logs recorded yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Work Log Cards View (< 768px App View) -->
        <div class="mobile-card-list d-block d-md-none">
            <?php if ($logs): foreach ($logs as $l): ?>
                <div class="mobile-data-card">
                    <div class="mobile-data-card-header">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($l['user_name']) ?>&background=4f46e5&color=fff" class="rounded-circle" width="32" height="32" alt="Avatar">
                            <div>
                                <strong class="text-dark fs-7 d-block line-height-1"><?= sanitize($l['user_name']) ?></strong>
                                <small class="text-secondary font-monospace fs-9"><?= formatISTDate($l['log_date']) ?></small>
                            </div>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary fs-9 fw-bold">
                            <?= (int)$l['progress_percentage'] ?>% Done
                        </span>
                    </div>

                    <div class="py-2">
                        <a href="<?= BASE_URL ?>/task-details?id=<?= $l['task_id'] ?>" class="fw-bold text-dark text-decoration-none fs-7 d-block mb-1">
                            <i class="fas fa-tasks text-primary me-1"></i><?= sanitize($l['task_title']) ?>
                        </a>
                        <p class="text-secondary fs-8 mb-2">
                            <?= sanitize($l['today_work']) ?>
                        </p>

                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="progress flex-grow-1" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: <?= (int)$l['progress_percentage'] ?>%"></div>
                            </div>
                            <span class="fs-9 font-monospace fw-bold text-success"><?= (int)$l['progress_percentage'] ?>%</span>
                        </div>

                        <?php if (!empty($l['issues']) && $l['issues'] !== 'None'): ?>
                            <div class="p-2 bg-danger-subtle border border-danger rounded-2 fs-9 text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i><strong>Issue:</strong> <?= sanitize($l['issues']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mobile-data-card-footer">
                        <a href="<?= BASE_URL ?>/task-details?id=<?= $l['task_id'] ?>" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 fw-semibold">
                            <i class="fas fa-eye me-1"></i>View Task Details
                        </a>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="text-center text-muted py-4 bg-light rounded-3 border">No daily work logs recorded yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#workLogsTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 15
        });
    });
</script>
