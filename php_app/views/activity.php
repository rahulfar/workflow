<?php
$pageTitle = "Activity History";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();

// Fetch currently active working employees (running timer)
$activeWorkers = $db->query("
    SELECT u.name, u.designation, t.title as task_title, tt.start_time
    FROM task_timer tt
    JOIN users u ON tt.user_id = u.id
    JOIN tasks t ON tt.task_id = t.id
    WHERE tt.status = 'running'
")->fetchAll();

// Fetch system activity logs
$activities = $db->query("
    SELECT a.*, u.name as user_name
    FROM activity_logs a
    LEFT JOIN users u ON a.user_id = u.id
    ORDER BY a.id DESC LIMIT 50
")->fetchAll();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-stream text-warning me-2"></i>Activity History & Live Monitor</h3>
            <p class="text-secondary small mb-0">Monitor currently working employees and full audit trail of system events.</p>
        </div>

        <!-- Live Active Working Employees Monitor -->
        <div class="glass-card mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-circle text-success me-2 pulse-dot"></i>Live Active Employees (Currently Working)</h5>
            <div class="row g-3">
                <?php if ($activeWorkers): foreach ($activeWorkers as $aw): 
                    $mins = floor((time() - strtotime($aw['start_time'])) / 60);
                ?>
                    <div class="col-md-4">
                        <div class="p-3 border rounded bg-light">
                            <div class="d-flex justify-content-between mb-1">
                                <strong class="text-dark"><?= sanitize($aw['name']) ?></strong>
                                <span class="badge bg-success-subtle text-success border border-success"><i class="fas fa-spinner fa-spin me-1"></i>Active</span>
                            </div>
                            <small class="text-muted d-block mb-2"><?= sanitize($aw['designation']) ?></small>
                            <div class="small fw-semibold text-primary text-truncate mb-1"><i class="fas fa-tasks me-1"></i><?= sanitize($aw['task_title']) ?></div>
                            <div class="small text-secondary font-monospace"><i class="fas fa-clock me-1"></i>Running for <?= $mins ?> mins</div>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="col-12"><div class="text-muted py-3 text-center small">No employees currently running active task timers.</div></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Activity Audit Logs -->
        <div class="glass-card">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-list text-info me-2"></i>System Audit Log Feed</h5>
            
            <!-- Desktop Table View (>= 768px) -->
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($activities): foreach ($activities as $act): ?>
                            <tr>
                                <td class="small text-secondary font-monospace"><?= formatISTDateTime($act['created_at']) ?></td>
                                <td class="fw-bold text-dark"><?= sanitize($act['user_name'] ?? 'System') ?></td>
                                <td><span class="badge bg-primary-subtle text-primary"><?= sanitize($act['action']) ?></span></td>
                                <td><span class="badge bg-secondary-subtle text-secondary"><?= sanitize($act['module']) ?></span></td>
                                <td class="small text-dark"><?= sanitize($act['description']) ?></td>
                                <td class="small text-muted font-monospace"><i class="fas fa-network-wired text-secondary me-1"></i><?= sanitize($act['ip_address'] ?: '127.0.0.1') ?></td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center text-muted py-3">No activity logs recorded yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View (< 768px) -->
            <div class="mobile-card-list d-block d-md-none">
                <?php if ($activities): foreach ($activities as $act): ?>
                    <div class="p-3 border rounded-3 bg-white mb-2 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary-subtle text-primary fw-semibold"><?= sanitize($act['action']) ?></span>
                            <span class="small text-muted font-monospace"><i class="fas fa-clock me-1"></i><?= formatISTDateTime($act['created_at']) ?></span>
                        </div>
                        <div class="fw-bold text-dark mb-1"><i class="fas fa-user-circle text-secondary me-1"></i><?= sanitize($act['user_name'] ?? 'System') ?></div>
                        <div class="small text-dark mb-2"><?= sanitize($act['description']) ?></div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="badge bg-secondary-subtle text-secondary"><?= sanitize($act['module']) ?></span>
                            <span class="small text-muted font-monospace"><i class="fas fa-network-wired me-1"></i><?= sanitize($act['ip_address'] ?: '127.0.0.1') ?></span>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="text-center text-muted py-3">No activity logs recorded yet.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
