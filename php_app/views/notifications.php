<?php
$pageTitle = "Notifications";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();

// Fetch notifications
$stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$user['id']]);
$notifications = $stmt->fetchAll();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-bell text-warning me-2"></i>Notifications Center</h3>
                <p class="text-secondary small mb-0">System alerts, task assignments, deadline reminders, and leave updates.</p>
            </div>
            <button onclick="markAllAsRead()" class="btn btn-outline-primary btn-sm"><i class="fas fa-check-double me-1"></i>Mark All as Read</button>
        </div>

        <div class="glass-card">
            <div class="list-group list-group-flush">
                <?php if ($notifications): foreach ($notifications as $n): 
                    $targetUrl = !empty($n['link']) ? (str_starts_with($n['link'], 'http') ? $n['link'] : BASE_URL . '/' . ltrim($n['link'], '/')) : 'javascript:void(0)';
                ?>
                    <a href="<?= htmlspecialchars($targetUrl, ENT_QUOTES) ?>" onclick="handleNotifClick(event, <?= (int)$n['id'] ?>, '<?= htmlspecialchars($targetUrl, ENT_QUOTES) ?>')" class="list-group-item list-group-item-action border-bottom p-3 <?= $n['is_read'] ? 'bg-light text-secondary' : 'bg-white text-dark fw-semibold' ?>">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <i class="fas fa-bell text-warning fs-5"></i>
                                <div>
                                    <h6 class="mb-1 text-dark fw-bold"><?= sanitize($n['title']) ?></h6>
                                    <div class="small text-secondary"><?= sanitize($n['message']) ?></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted font-monospace d-block"><?= date('M d, H:i', strtotime($n['created_at'])) ?></small>
                                <?php if (!$n['is_read']): ?>
                                    <span class="badge bg-primary-subtle text-primary mt-1">Unread</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; else: ?>
                    <div class="text-muted text-center py-5">
                        <i class="fas fa-bell-slash fa-3x mb-3 text-secondary opacity-50"></i>
                        <p class="mb-0">No notifications in your inbox.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    async function markAllAsRead() {
        try {
            const formData = new FormData();
            formData.append('csrf_token', CSRF_TOKEN);
            const res = await fetch('<?= BASE_URL ?>/api/notifications?action=mark_all_read', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    text: 'All notifications marked as read.',
                    timer: 1000,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', data.message || 'Failed to update notifications', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'Network error marking notifications', 'error');
        }
    }

    async function handleNotifClick(e, id, url) {
        try {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('csrf_token', CSRF_TOKEN);
            fetch('<?= BASE_URL ?>/api/notifications?action=mark_read', {
                method: 'POST',
                body: formData
            });
        } catch (err) {}

        if (url && url !== 'javascript:void(0)' && url !== '#') {
            // allow link navigation
        } else {
            e.preventDefault();
        }
    }
</script>
