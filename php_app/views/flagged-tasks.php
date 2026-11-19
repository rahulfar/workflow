<?php
$pageTitle = "Flagged Tasks";
$user = requireLogin();
$db = Database::getConnection();
ensureEmergencyAndFlagSchema($db);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$userId = (int)$user['id'];
$canManage = canCreateOrEditTasks($user);

// Fetch flagged tasks
$statusFilter = sanitize($_GET['status'] ?? 'all');
$where = [];
$params = [];

if (!$canManage) {
    $where[] = "f.employee_id = ?";
    $params[] = $userId;
}

if ($statusFilter !== 'all' && !empty($statusFilter)) {
    $where[] = "f.status = ?";
    $params[] = $statusFilter;
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$flags = [];
$countOpen = 0;
$countUnderReview = 0;
$countWaiting = 0;
$countResolved = 0;
$countReopened = 0;
$countAll = 0;

try {
    $stmt = $db->prepare("
        SELECT f.*, 
               t.title as task_title, t.priority as task_priority, t.status as task_status, t.deadline as task_deadline,
               p.name as project_name,
               d.name as department_name,
               u_emp.name as employee_name, u_emp.avatar as employee_avatar, u_emp.designation as employee_designation,
               u_res.name as resolver_name,
               (SELECT COUNT(*) FROM task_flag_comments WHERE flag_id = f.id) as comment_count
        FROM task_flags f
        JOIN tasks t ON f.task_id = t.id
        LEFT JOIN projects p ON t.project_id = p.id
        LEFT JOIN departments d ON t.department_id = d.id
        JOIN users u_emp ON f.employee_id = u_emp.id
        LEFT JOIN users u_res ON f.resolved_by = u_res.id
        {$whereSql}
        ORDER BY CASE WHEN f.status = 'open' THEN 0 WHEN f.status = 'reopened' THEN 1 WHEN f.status = 'under_review' THEN 2 WHEN f.status = 'waiting_info' THEN 3 ELSE 4 END, f.id DESC
    ");
    $stmt->execute($params);
    $flags = $stmt->fetchAll() ?: [];

    // Counts for filter pills
    if ($canManage) {
        $countOpen = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE status = 'open'")->fetchColumn();
        $countUnderReview = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE status = 'under_review'")->fetchColumn();
        $countWaiting = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE status = 'waiting_info'")->fetchColumn();
        $countResolved = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE status = 'resolved'")->fetchColumn();
        $countReopened = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE status = 'reopened'")->fetchColumn();
        $countAll = (int)$db->query("SELECT COUNT(*) FROM task_flags")->fetchColumn();
    } else {
        $countOpen = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE employee_id = {$userId} AND status = 'open'")->fetchColumn();
        $countUnderReview = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE employee_id = {$userId} AND status = 'under_review'")->fetchColumn();
        $countWaiting = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE employee_id = {$userId} AND status = 'waiting_info'")->fetchColumn();
        $countResolved = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE employee_id = {$userId} AND status = 'resolved'")->fetchColumn();
        $countReopened = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE employee_id = {$userId} AND status = 'reopened'")->fetchColumn();
        $countAll = (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE employee_id = {$userId}")->fetchColumn();
    }
} catch (Throwable $e) {
    error_log("flagged-tasks data fetch error: " . $e->getMessage());
}
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-flag text-warning me-2"></i>Flagged Tasks &amp; Blockers
                </h3>
                <p class="text-secondary small mb-0">
                    <?= $canManage ? 'Review reported missing requirements, credentials, and dependency blockers preventing sprint deliverables.' : 'Track the resolution of missing resources, files, and dependencies you have flagged.' ?>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/tasks" class="btn btn-outline-primary"><i class="fas fa-tasks me-1"></i>Task Management</a>
            </div>
        </div>

        <!-- Metric Badges -->
        <div class="row g-3 mb-4">
            <div class="col-sm-2 col-4">
                <a href="?status=open" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 text-center border-bottom border-warning border-4">
                        <div class="text-secondary small">Open Flags</div>
                        <h4 class="fw-bold text-warning mb-0"><?= $countOpen ?></h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2 col-4">
                <a href="?status=under_review" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 text-center border-bottom border-info border-4">
                        <div class="text-secondary small">In Review</div>
                        <h4 class="fw-bold text-info mb-0"><?= $countUnderReview ?></h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2 col-4">
                <a href="?status=waiting_info" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 text-center border-bottom border-secondary border-4">
                        <div class="text-secondary small">Waiting Info</div>
                        <h4 class="fw-bold text-secondary mb-0"><?= $countWaiting ?></h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2 col-4">
                <a href="?status=reopened" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 text-center border-bottom border-danger border-4">
                        <div class="text-secondary small">Reopened</div>
                        <h4 class="fw-bold text-danger mb-0"><?= $countReopened ?></h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2 col-4">
                <a href="?status=resolved" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 text-center border-bottom border-success border-4">
                        <div class="text-secondary small">Resolved</div>
                        <h4 class="fw-bold text-success mb-0"><?= $countResolved ?></h4>
                    </div>
                </a>
            </div>
            <div class="col-sm-2 col-4">
                <a href="?status=all" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 text-center border-bottom border-primary border-4">
                        <div class="text-secondary small">Total Flags</div>
                        <h4 class="fw-bold text-primary mb-0"><?= $countAll ?></h4>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="glass-card mb-4 p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="btn-group btn-group-sm flex-wrap gap-1">
                <a href="?status=all" class="btn btn-outline-secondary <?= $statusFilter === 'all' ? 'active' : '' ?>">All Flags (<?= $countAll ?>)</a>
                <a href="?status=open" class="btn btn-outline-warning <?= $statusFilter === 'open' ? 'active' : '' ?>">Open (<?= $countOpen ?>)</a>
                <a href="?status=under_review" class="btn btn-outline-info <?= $statusFilter === 'under_review' ? 'active' : '' ?>">Under Review (<?= $countUnderReview ?>)</a>
                <a href="?status=waiting_info" class="btn btn-outline-secondary <?= $statusFilter === 'waiting_info' ? 'active' : '' ?>">Waiting Info (<?= $countWaiting ?>)</a>
                <a href="?status=reopened" class="btn btn-outline-danger <?= $statusFilter === 'reopened' ? 'active' : '' ?>">Reopened (<?= $countReopened ?>)</a>
                <a href="?status=resolved" class="btn btn-outline-success <?= $statusFilter === 'resolved' ? 'active' : '' ?>">Resolved (<?= $countResolved ?>)</a>
            </div>
            <div class="text-secondary small">
                <i class="fas fa-info-circle me-1"></i>Showing <?= count($flags) ?> task blocker flags
            </div>
        </div>

        <!-- Table / Cards List -->
        <div class="glass-card p-0 overflow-hidden">
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-lg-block">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small">
                        <tr>
                            <th class="ps-4">#Flag ID</th>
                            <th>Task Title</th>
                            <th>Reported By</th>
                            <th>Flag Category</th>
                            <th>Impact on Work</th>
                            <th>Reported Date</th>
                            <th>Status</th>
                            <th>Resolution / Comments</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($flags)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-flag fa-3x text-muted mb-3 opacity-25 d-block"></i>
                                    <h5>No flagged task issues found</h5>
                                    <p class="small text-secondary mb-0">There are no flagged items matching your current filter.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($flags as $f): ?>
                                <tr>
                                    <td class="ps-4 font-monospace fw-bold text-warning">
                                        #FLG-<?= $f['id'] ?>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/task-details?id=<?= $f['task_id'] ?>" class="text-decoration-none fw-semibold text-dark hover-primary d-block">
                                            <?= sanitize($f['task_title']) ?>
                                        </a>
                                        <small class="text-muted font-monospace">Task #<?= $f['task_id'] ?> &bull; <?= sanitize($f['department_name'] ?? 'General') ?></small>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-0"><?= sanitize($f['employee_name']) ?></div>
                                        <small class="text-muted"><?= sanitize($f['employee_designation'] ?? 'Staff') ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= sanitize($f['category']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= getTaskFlagImpactBadge($f['impact']) ?>
                                    </td>
                                    <td class="small text-secondary">
                                        <?= formatISTDateTime($f['created_at']) ?>
                                    </td>
                                    <td>
                                        <?= getTaskFlagBadge($f['status']) ?>
                                    </td>
                                    <td class="small" style="max-width: 240px;">
                                        <?php if ($f['status'] === 'resolved'): ?>
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>Resolved by <?= sanitize($f['resolver_name'] ?? 'Manager') ?></span>
                                            <div class="text-muted text-truncate mt-0.5" title="<?= htmlspecialchars($f['resolution_message']) ?>">
                                                <?= sanitize($f['resolution_message']) ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-secondary text-truncate" title="<?= htmlspecialchars($f['missing_requirement']) ?>">
                                                <i class="fas fa-exclamation-circle text-warning me-1"></i><?= sanitize($f['missing_requirement']) ?>
                                            </div>
                                            <small class="text-muted"><i class="fas fa-comments me-1"></i><?= (int)$f['comment_count'] ?> comments</small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-info" 
                                                    onclick="openFlagModal(<?= $f['id'] ?>)"
                                                    title="View Full Flag & Discussion Thread">
                                                <i class="fas fa-comments me-1"></i>Details
                                            </button>

                                            <?php if ($canManage && in_array($f['status'], ['open', 'under_review', 'waiting_info', 'reopened'])): ?>
                                                <button type="button" class="btn btn-success" 
                                                        onclick="resolveFlagPrompt(<?= $f['id'] ?>, '<?= addslashes(sanitize($f['task_title'])) ?>')"
                                                        title="Mark Flag as Resolved">
                                                    <i class="fas fa-check"></i> Resolve
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($f['status'] === 'resolved'): ?>
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="reopenFlagPrompt(<?= $f['id'] ?>, '<?= addslashes(sanitize($f['task_title'])) ?>')"
                                                        title="Reopen Flag">
                                                    <i class="fas fa-redo"></i> Reopen
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile & Tablet Cards View (< 992px) -->
            <div class="d-block d-lg-none p-3">
                <?php if (empty($flags)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-flag fa-2x text-muted mb-2 opacity-50"></i>
                        <div>No flagged task issues found</div>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($flags as $f): ?>
                            <div class="col-12">
                                <div class="border rounded-3 p-3 bg-white shadow-sm position-relative border-start border-warning border-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="font-monospace fw-bold text-warning">#FLG-<?= $f['id'] ?></span>
                                            <h6 class="fw-bold text-dark mt-1 mb-0"><?= sanitize($f['task_title']) ?></h6>
                                        </div>
                                        <div>
                                            <?= getTaskFlagBadge($f['status']) ?>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2 mb-2 flex-wrap align-items-center">
                                        <span class="badge bg-light text-dark border"><?= sanitize($f['category']) ?></span>
                                        <?= getTaskFlagImpactBadge($f['impact']) ?>
                                    </div>

                                    <div class="small text-secondary mb-2">
                                        <i class="fas fa-user text-primary me-1"></i><?= sanitize($f['employee_name']) ?>
                                    </div>

                                    <div class="p-2.5 rounded bg-light small text-dark mb-2 border">
                                        <strong>Missing Requirement:</strong><br>
                                        <?= nl2br(sanitize($f['missing_requirement'])) ?>
                                    </div>

                                    <?php if ($f['status'] === 'resolved' && !empty($f['resolution_message'])): ?>
                                        <div class="p-2.5 rounded bg-success-subtle small text-success mb-2 border border-success">
                                            <strong>Resolution:</strong><br>
                                            <?= nl2br(sanitize($f['resolution_message'])) ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top flex-wrap gap-2">
                                        <small class="text-muted"><i class="fas fa-clock me-1"></i><?= formatISTDateTime($f['created_at']) ?></small>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-info" onclick="openFlagModal(<?= $f['id'] ?>)">
                                                <i class="fas fa-comments me-1"></i>Details
                                            </button>
                                            <?php if ($canManage && in_array($f['status'], ['open', 'under_review', 'waiting_info', 'reopened'])): ?>
                                                <button type="button" class="btn btn-success" onclick="resolveFlagPrompt(<?= $f['id'] ?>, '<?= addslashes(sanitize($f['task_title'])) ?>')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($f['status'] === 'resolved'): ?>
                                                <button type="button" class="btn btn-outline-danger" onclick="reopenFlagPrompt(<?= $f['id'] ?>, '<?= addslashes(sanitize($f['task_title'])) ?>')">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ================= FLAG DETAILS & DISCUSSION MODAL ================= -->
<div class="modal fade" id="flagDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-flag me-2"></i>Task Flag Details &amp; Discussion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="text-secondary small fw-semibold d-block">Flag ID &amp; Status</label>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="font-monospace fw-bold fs-6 text-warning" id="modalFlagId">#FLG</span>
                            <span id="modalFlagStatusBadge"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small fw-semibold d-block">Reported By</label>
                        <div class="fw-bold text-dark mt-1" id="modalFlagEmpName">--</div>
                    </div>
                    <div class="col-md-8">
                        <label class="text-secondary small fw-semibold d-block">Related Task Title</label>
                        <div class="fw-bold text-dark fs-6 mt-1" id="modalFlagTaskTitle">--</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-secondary small fw-semibold d-block">Impact on Work</label>
                        <div id="modalFlagImpactBadge" class="mt-1">--</div>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary small fw-semibold d-block">Flag Category</label>
                        <span class="badge bg-light text-dark border p-2 mt-1" id="modalFlagCategory">--</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-secondary small fw-semibold d-block">Missing Requirements Description</label>
                    <div class="p-3 bg-light rounded-3 text-dark border mt-1" id="modalFlagDesc" style="white-space: pre-line;">--</div>
                </div>

                <div id="modalFlagAttachmentSec" class="mb-3 d-none">
                    <label class="text-secondary small fw-semibold d-block">Supporting Reference Attachment</label>
                    <a href="#" id="modalFlagAttachmentLink" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                        <i class="fas fa-paperclip me-1"></i>View Uploaded File
                    </a>
                </div>

                <div id="modalFlagResolutionSec" class="p-3 rounded-3 border bg-success-subtle mb-3 d-none">
                    <label class="text-success small fw-bold d-block mb-1">
                        <i class="fas fa-check-circle me-1"></i>Manager Resolution
                    </label>
                    <div class="small fw-semibold text-dark mb-1" id="modalFlagResolver">Resolved By: --</div>
                    <div class="small text-dark" id="modalFlagResolutionText">--</div>
                </div>

                <!-- Discussion & Comment Thread -->
                <div class="border-top pt-3 mt-3">
                    <h6 class="fw-bold text-dark mb-3"><i class="fas fa-comments me-2 text-primary"></i>Resolution Discussion &amp; Updates</h6>
                    <div id="flagCommentsThread" class="d-flex flex-column gap-2 mb-3" style="max-height: 250px; overflow-y: auto;">
                        <!-- Injected via JS -->
                    </div>

                    <!-- Add Comment Form -->
                    <form id="addFlagCommentForm" class="d-flex gap-2">
                        <input type="hidden" name="flag_id" id="comment_flag_id">
                        <input type="text" name="comment" class="form-control" placeholder="Write a response or provide missing resource link..." required>
                        <button type="submit" class="btn btn-primary fw-bold px-3">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div id="modalFlagActions" class="d-flex gap-2"></div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    let activeFlagModalInstance = null;
    let currentFlagData = null;

    function openFlagModal(flagId) {
        fetch('<?= BASE_URL ?>/api/task_flags?action=details&flag_id=' + flagId)
        .then(r => r.json())
        .then(res => {
            if (!res.success) {
                Swal.fire('Error', res.message || 'Could not load flag details.', 'error');
                return;
            }
            const data = res.data;
            currentFlagData = data;

            document.getElementById('modalFlagId').textContent = '#FLG-' + data.id;
            document.getElementById('comment_flag_id').value = data.id;
            document.getElementById('modalFlagEmpName').textContent = data.employee_name + (data.employee_designation ? ' (' + data.employee_designation + ')' : '');
            document.getElementById('modalFlagTaskTitle').textContent = data.task_title;
            document.getElementById('modalFlagCategory').textContent = data.category;
            document.getElementById('modalFlagDesc').textContent = data.missing_requirement;

            // Status badge
            let sBadge = '';
            if (data.status === 'resolved') sBadge = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Resolved</span>';
            else if (data.status === 'under_review') sBadge = '<span class="badge bg-info"><i class="fas fa-search me-1"></i>Under Review</span>';
            else if (data.status === 'waiting_info') sBadge = '<span class="badge bg-secondary"><i class="fas fa-clock me-1"></i>Waiting Info</span>';
            else if (data.status === 'reopened') sBadge = '<span class="badge bg-danger pulse-dot"><i class="fas fa-redo me-1"></i>Reopened</span>';
            else sBadge = '<span class="badge bg-warning text-dark pulse-dot"><i class="fas fa-flag me-1"></i>Open Flag</span>';
            document.getElementById('modalFlagStatusBadge').innerHTML = sBadge;

            // Impact badge
            document.getElementById('modalFlagImpactBadge').innerHTML = `<span class="badge bg-danger text-white p-2">${data.impact}</span>`;

            // Attachment
            const attSec = document.getElementById('modalFlagAttachmentSec');
            const attLink = document.getElementById('modalFlagAttachmentLink');
            if (data.attachment) {
                attSec.classList.remove('d-none');
                attLink.href = '<?= BASE_URL ?>/uploads/flags/' + data.attachment;
            } else {
                attSec.classList.add('d-none');
            }

            // Resolution
            const resSec = document.getElementById('modalFlagResolutionSec');
            if (data.status === 'resolved' && data.resolution_message) {
                resSec.classList.remove('d-none');
                document.getElementById('modalFlagResolver').textContent = 'Resolved By: ' + (data.resolver_name || 'Manager') + (data.resolved_at ? ' on ' + data.resolved_at : '');
                document.getElementById('modalFlagResolutionText').textContent = data.resolution_message;
            } else {
                resSec.classList.add('d-none');
            }

            // Render comments thread
            renderFlagComments(data.comments || []);

            // Action buttons
            const actBox = document.getElementById('modalFlagActions');
            actBox.innerHTML = '';
            <?php if ($canManage): ?>
            if (data.status !== 'resolved') {
                actBox.innerHTML += `
                    <button type="button" class="btn btn-outline-info btn-sm fw-bold" onclick="updateStatusPrompt(${data.id})">
                        <i class="fas fa-exchange-alt me-1"></i>Change Status
                    </button>
                    <button type="button" class="btn btn-success btn-sm fw-bold" onclick="resolveFlagPrompt(${data.id}, '${escapeQuote(data.task_title)}')">
                        <i class="fas fa-check me-1"></i>Mark Resolved
                    </button>
                `;
            }
            <?php endif; ?>

            if (data.status === 'resolved') {
                actBox.innerHTML += `
                    <button type="button" class="btn btn-outline-danger btn-sm fw-bold" onclick="reopenFlagPrompt(${data.id}, '${escapeQuote(data.task_title)}')">
                        <i class="fas fa-redo me-1"></i>Reopen Flag
                    </button>
                `;
            }

            const modalEl = document.getElementById('flagDetailsModal');
            if (!activeFlagModalInstance) {
                activeFlagModalInstance = new bootstrap.Modal(modalEl);
            }
            activeFlagModalInstance.show();
        })
        .catch(err => {
            Swal.fire('Error', 'Failed to communicate with server.', 'error');
        });
    }

    function renderFlagComments(comments) {
        const thread = document.getElementById('flagCommentsThread');
        if (!comments || comments.length === 0) {
            thread.innerHTML = '<div class="text-muted small fst-italic p-2 text-center">No discussion comments yet.</div>';
            return;
        }

        let html = '';
        comments.forEach(c => {
            html += `
                <div class="p-2.5 rounded bg-light border">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-dark small"><i class="fas fa-user-circle text-primary me-1"></i>${c.user_name}</strong>
                        <small class="text-muted" style="font-size:11px;">${c.created_at || ''}</small>
                    </div>
                    <div class="text-secondary small" style="white-space: pre-line;">${c.comment}</div>
                </div>
            `;
        });
        thread.innerHTML = html;
        thread.scrollTop = thread.scrollHeight;
    }

    // Submit new comment form
    document.getElementById('addFlagCommentForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'add_comment');

        fetch('<?= BASE_URL ?>/api/task_flags', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                this.reset();
                if (currentFlagData) {
                    openFlagModal(currentFlagData.id);
                }
            } else {
                Swal.fire('Error', res.message || 'Failed to add comment.', 'error');
            }
        });
    });

    function escapeQuote(str) {
        return (str || '').replace(/'/g, "\\'");
    }

    // Resolve Flag Prompt
    function resolveFlagPrompt(flagId, taskTitle) {
        Swal.fire({
            title: 'Resolve Task Flag?',
            html: `You are resolving the flagged blocker for: <b>${taskTitle}</b>.<br><small class="text-muted">Explain how the missing requirements or files were provided.</small>`,
            icon: 'success',
            input: 'textarea',
            inputPlaceholder: 'e.g. All required files and client credentials have been updated in the project folder...',
            inputValidator: (value) => {
                if (!value || !value.trim()) {
                    return 'Resolution message is required!';
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Mark Resolved',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'resolve');
                formData.append('flag_id', flagId);
                formData.append('resolution_message', result.value.trim());
                formData.append('csrf_token', '<?= getCsrfToken() ?>');

                fetch('<?= BASE_URL ?>/api/task_flags', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Resolved!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message || 'Failed to resolve flag.', 'error');
                    }
                });
            }
        });
    }

    // Reopen Flag Prompt
    function reopenFlagPrompt(flagId, taskTitle) {
        Swal.fire({
            title: 'Reopen Task Flag?',
            html: `Explain why this issue for <b>${taskTitle}</b> is still missing or blocked:`,
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Enter reason why the requirement is still unfulfilled...',
            inputValidator: (value) => {
                if (!value || !value.trim()) {
                    return 'Explanation is required to reopen!';
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Reopen Flag',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'reopen');
                formData.append('flag_id', flagId);
                formData.append('reopen_reason', result.value.trim());
                formData.append('csrf_token', '<?= getCsrfToken() ?>');

                fetch('<?= BASE_URL ?>/api/task_flags', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Reopened',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message || 'Failed to reopen flag.', 'error');
                    }
                });
            }
        });
    }

    // Update Status Prompt
    function updateStatusPrompt(flagId) {
        Swal.fire({
            title: 'Update Flag Status',
            input: 'select',
            inputOptions: {
                'under_review': 'Under Review (Manager Investigating)',
                'waiting_info': 'Waiting for Information (Client/External)',
                'open': 'Open (Pending Action)'
            },
            inputPlaceholder: 'Select status...',
            showCancelButton: true,
            confirmButtonText: 'Update Status',
            confirmButtonColor: '#0ea5e9',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed && result.value) {
                const formData = new FormData();
                formData.append('action', 'update_status');
                formData.append('flag_id', flagId);
                formData.append('status', result.value);
                formData.append('notes', 'Status transitioned to ' + result.value);
                formData.append('csrf_token', '<?= getCsrfToken() ?>');

                fetch('<?= BASE_URL ?>/api/task_flags', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Updated', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }
</script>
