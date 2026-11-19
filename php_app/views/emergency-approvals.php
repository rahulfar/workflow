<?php
$pageTitle = "Emergency Task Approvals";
$user = requireLogin();
$db = Database::getConnection();
ensureEmergencyAndFlagSchema($db);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$userId = (int)$user['id'];
$canManage = canCreateOrEditTasks($user);

// Fetch emergency requests
$statusFilter = sanitize($_GET['status'] ?? 'all');
$where = [];
$params = [];

if (!$canManage) {
    $where[] = "r.employee_id = ?";
    $params[] = $userId;
}

if ($statusFilter !== 'all' && !empty($statusFilter)) {
    $where[] = "r.status = ?";
    $params[] = $statusFilter;
}

$whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$requests = [];
$countPending = 0;
$countApproved = 0;
$countRejected = 0;
$countAll = 0;

try {
    $stmt = $db->prepare("
        SELECT r.*, 
               t.title as task_title, t.priority as task_priority, t.status as task_status, t.deadline as task_deadline,
               p.name as project_name,
               d.name as department_name,
               u_emp.name as employee_name, u_emp.avatar as employee_avatar, u_emp.designation as employee_designation,
               u_rev.name as reviewer_name
        FROM emergency_task_requests r
        JOIN tasks t ON r.task_id = t.id
        LEFT JOIN projects p ON t.project_id = p.id
        LEFT JOIN departments d ON t.department_id = d.id
        JOIN users u_emp ON r.employee_id = u_emp.id
        LEFT JOIN users u_rev ON r.reviewer_id = u_rev.id
        {$whereSql}
        ORDER BY CASE WHEN r.status = 'pending' THEN 0 ELSE 1 END, r.id DESC
    ");
    $stmt->execute($params);
    $requests = $stmt->fetchAll() ?: [];

    // Counts for filter pills
    if ($canManage) {
        $countPending = (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests WHERE status = 'pending'")->fetchColumn();
        $countApproved = (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests WHERE status = 'approved'")->fetchColumn();
        $countRejected = (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests WHERE status = 'rejected'")->fetchColumn();
        $countAll = (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests")->fetchColumn();
    } else {
        $countPending = (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests WHERE employee_id = {$userId} AND status = 'pending'")->fetchColumn();
        $countApproved = (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests WHERE employee_id = {$userId} AND status = 'approved'")->fetchColumn();
        $countRejected = (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests WHERE employee_id = {$userId} AND status = 'rejected'")->fetchColumn();
        $countAll = (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests WHERE employee_id = {$userId}")->fetchColumn();
    }
} catch (Throwable $e) {
    error_log("emergency-approvals data fetch error: " . $e->getMessage());
}
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-bolt text-danger me-2"></i><?= $canManage ? 'Emergency Task Approvals' : 'My Emergency Requests' ?>
                </h3>
                <p class="text-secondary small mb-0">
                    <?= $canManage ? 'Review urgent task escalations, expedited deadline requests, and provide executive decisions.' : 'Track review progress and decisions on your submitted emergency requests.' ?>
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="<?= BASE_URL ?>/tasks" class="btn btn-outline-primary"><i class="fas fa-tasks me-1"></i>Task Management</a>
            </div>
        </div>

        <!-- Metric Badges -->
        <div class="row g-3 mb-4">
            <div class="col-sm-3 col-6">
                <a href="?status=pending" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 d-flex align-items-center justify-content-between border-start border-danger border-4">
                        <div>
                            <div class="text-secondary small">Pending Review</div>
                            <h4 class="fw-bold text-danger mb-0"><?= $countPending ?></h4>
                        </div>
                        <div class="p-2.5 bg-danger-subtle text-danger rounded-circle">
                            <i class="fas fa-hourglass-half fa-lg"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3 col-6">
                <a href="?status=approved" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 d-flex align-items-center justify-content-between border-start border-success border-4">
                        <div>
                            <div class="text-secondary small">Approved</div>
                            <h4 class="fw-bold text-success mb-0"><?= $countApproved ?></h4>
                        </div>
                        <div class="p-2.5 bg-success-subtle text-success rounded-circle">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3 col-6">
                <a href="?status=rejected" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 d-flex align-items-center justify-content-between border-start border-secondary border-4">
                        <div>
                            <div class="text-secondary small">Rejected</div>
                            <h4 class="fw-bold text-secondary mb-0"><?= $countRejected ?></h4>
                        </div>
                        <div class="p-2.5 bg-secondary-subtle text-secondary rounded-circle">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-3 col-6">
                <a href="?status=all" class="text-decoration-none">
                    <div class="glass-card py-3 px-3 d-flex align-items-center justify-content-between border-start border-primary border-4">
                        <div>
                            <div class="text-secondary small">Total Requests</div>
                            <h4 class="fw-bold text-primary mb-0"><?= $countAll ?></h4>
                        </div>
                        <div class="p-2.5 bg-primary-subtle text-primary rounded-circle">
                            <i class="fas fa-list-ul fa-lg"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="glass-card mb-4 p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="btn-group btn-group-sm">
                <a href="?status=all" class="btn btn-outline-secondary <?= $statusFilter === 'all' ? 'active' : '' ?>">All Requests (<?= $countAll ?>)</a>
                <a href="?status=pending" class="btn btn-outline-danger <?= $statusFilter === 'pending' ? 'active' : '' ?>">Pending Review (<?= $countPending ?>)</a>
                <a href="?status=approved" class="btn btn-outline-success <?= $statusFilter === 'approved' ? 'active' : '' ?>">Approved (<?= $countApproved ?>)</a>
                <a href="?status=rejected" class="btn btn-outline-secondary <?= $statusFilter === 'rejected' ? 'active' : '' ?>">Rejected (<?= $countRejected ?>)</a>
            </div>
            <div class="text-secondary small">
                <i class="fas fa-info-circle me-1"></i>Showing <?= count($requests) ?> emergency escalation records
            </div>
        </div>

        <!-- Table / Cards Container -->
        <div class="glass-card p-0 overflow-hidden">
            <!-- Desktop View -->
            <div class="table-responsive d-none d-lg-block">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small">
                        <tr>
                            <th class="ps-4">#Req ID</th>
                            <th>Employee</th>
                            <th>Related Task</th>
                            <th>Emergency Reason</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Decision / Notes</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($requests)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-bolt fa-3x text-muted mb-3 opacity-25 d-block"></i>
                                    <h5>No emergency requests found</h5>
                                    <p class="small text-secondary mb-0">There are no requests matching the current filter.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($requests as $r): ?>
                                <tr>
                                    <td class="ps-4 font-monospace fw-bold text-danger">
                                        #EMG-<?= $r['id'] ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-user-circle fs-4 text-primary"></i>
                                            <div>
                                                <div class="fw-bold text-dark mb-0"><?= sanitize($r['employee_name']) ?></div>
                                                <small class="text-muted"><?= sanitize($r['employee_designation'] ?? 'Staff') ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/task-details?id=<?= $r['task_id'] ?>" class="text-decoration-none fw-semibold text-dark hover-primary d-block">
                                            <?= sanitize($r['task_title']) ?>
                                        </a>
                                        <small class="text-muted font-monospace">Task #<?= $r['task_id'] ?> &bull; <?= sanitize($r['department_name'] ?? 'General') ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i><?= sanitize($r['reason']) ?>
                                        </span>
                                    </td>
                                    <td class="small text-secondary">
                                        <?= formatISTDateTime($r['created_at']) ?>
                                    </td>
                                    <td>
                                        <?= getEmergencyRequestBadge($r['status']) ?>
                                    </td>
                                    <td class="small">
                                        <?php if ($r['status'] === 'approved'): ?>
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i>By <?= sanitize($r['reviewer_name'] ?? 'Manager') ?></span>
                                            <?php if (!empty($r['reviewer_comment'])): ?>
                                                <div class="text-muted fst-italic mt-0.5">"<?= sanitize($r['reviewer_comment']) ?>"</div>
                                            <?php endif; ?>
                                        <?php elseif ($r['status'] === 'rejected'): ?>
                                            <span class="text-danger"><i class="fas fa-times-circle me-1"></i>By <?= sanitize($r['reviewer_name'] ?? 'Manager') ?></span>
                                            <?php if (!empty($r['rejection_reason'])): ?>
                                                <div class="text-danger fst-italic mt-0.5">"<?= sanitize($r['rejection_reason']) ?>"</div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">Awaiting Manager Review</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-info" 
                                                    data-req="<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>"
                                                    onclick="viewEmergencyModal(JSON.parse(this.getAttribute('data-req')))"
                                                    title="View Full Message & Details">
                                                <i class="fas fa-eye"></i> Details
                                            </button>
                                            
                                            <?php if ($canManage && $r['status'] === 'pending'): ?>
                                                <button type="button" class="btn btn-success" 
                                                        onclick="approveEmergencyPrompt(<?= $r['id'] ?>, '<?= addslashes(sanitize($r['task_title'])) ?>')"
                                                        title="Approve Request">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button type="button" class="btn btn-danger" 
                                                        onclick="rejectEmergencyPrompt(<?= $r['id'] ?>, '<?= addslashes(sanitize($r['task_title'])) ?>')"
                                                        title="Reject Request">
                                                    <i class="fas fa-times"></i> Reject
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
                <?php if (empty($requests)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-bolt fa-2x text-muted mb-2 opacity-50"></i>
                        <div>No emergency requests found</div>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($requests as $r): ?>
                            <div class="col-12">
                                <div class="border rounded-3 p-3 bg-white shadow-sm position-relative border-start border-danger border-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="font-monospace fw-bold text-danger">#EMG-<?= $r['id'] ?></span>
                                            <h6 class="fw-bold text-dark mt-1 mb-0"><?= sanitize($r['task_title']) ?></h6>
                                        </div>
                                        <div>
                                            <?= getEmergencyRequestBadge($r['status']) ?>
                                        </div>
                                    </div>

                                    <div class="small text-secondary mb-2">
                                        <i class="fas fa-user text-primary me-1"></i><?= sanitize($r['employee_name']) ?> (<?= sanitize($r['employee_designation'] ?? 'Staff') ?>)
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge bg-danger-subtle text-danger border border-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i><?= sanitize($r['reason']) ?>
                                        </span>
                                    </div>

                                    <div class="p-2 rounded bg-light small text-secondary mb-3">
                                        <?= nl2br(sanitize($r['message'])) ?>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top flex-wrap gap-2">
                                        <small class="text-muted"><i class="fas fa-clock me-1"></i><?= formatISTDateTime($r['created_at']) ?></small>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-info" 
                                                    data-req="<?= htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8') ?>"
                                                    onclick="viewEmergencyModal(JSON.parse(this.getAttribute('data-req')))">
                                                <i class="fas fa-eye me-1"></i>Details
                                            </button>
                                            <?php if ($canManage && $r['status'] === 'pending'): ?>
                                                <button type="button" class="btn btn-success" onclick="approveEmergencyPrompt(<?= $r['id'] ?>, '<?= addslashes(sanitize($r['task_title'])) ?>')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger" onclick="rejectEmergencyPrompt(<?= $r['id'] ?>, '<?= addslashes(sanitize($r['task_title'])) ?>')">
                                                    <i class="fas fa-times"></i>
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

<!-- ================= VIEW EMERGENCY DETAILS MODAL ================= -->
<div class="modal fade" id="emergencyDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-bolt me-2"></i>Emergency Task Request Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="text-secondary small fw-semibold d-block">Request ID &amp; Status</label>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="font-monospace fw-bold fs-6 text-danger" id="modalReqId">#EMG</span>
                            <span id="modalReqStatusBadge"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small fw-semibold d-block">Submitted By</label>
                        <div class="fw-bold text-dark mt-1" id="modalReqEmpName">--</div>
                    </div>
                    <div class="col-md-8">
                        <label class="text-secondary small fw-semibold d-block">Related Task Title</label>
                        <div class="fw-bold text-dark fs-6 mt-1" id="modalReqTaskTitle">--</div>
                    </div>
                    <div class="col-md-4">
                        <label class="text-secondary small fw-semibold d-block">Emergency Reason</label>
                        <div class="badge bg-danger-subtle text-danger border border-danger p-2 fs-7 mt-1 d-inline-block" id="modalReqReason">--</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-secondary small fw-semibold d-block">Emergency Description / Urgency Justification</label>
                    <div class="p-3 bg-light rounded-3 text-dark border mt-1" id="modalReqMessage" style="white-space: pre-line;">--</div>
                </div>

                <div id="modalAttachmentSection" class="mb-3 d-none">
                    <label class="text-secondary small fw-semibold d-block">Supporting Attachment</label>
                    <a href="#" id="modalAttachmentLink" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                        <i class="fas fa-paperclip me-1"></i>View Uploaded Attachment
                    </a>
                </div>

                <div id="modalReviewSection" class="p-3 rounded-3 border bg-light bg-opacity-75 mb-3 d-none">
                    <label class="text-secondary small fw-bold d-block mb-1">
                        <i class="fas fa-user-shield text-primary me-1"></i>Reviewer Decision &amp; Feedback
                    </label>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small fw-semibold" id="modalReviewerName">Reviewed By: --</span>
                        <small class="text-muted" id="modalReviewTime">--</small>
                    </div>
                    <div class="small text-secondary" id="modalReviewNotes">--</div>
                </div>
            </div>
            <div class="modal-footer bg-light d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div id="modalActionButtons" class="d-flex gap-2"></div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    let activeModalInstance = null;

    function viewEmergencyModal(data) {
        if (!data) return;

        document.getElementById('modalReqId').textContent = '#EMG-' + data.id;
        document.getElementById('modalReqEmpName').textContent = data.employee_name + (data.employee_designation ? ' (' + data.employee_designation + ')' : '');
        document.getElementById('modalReqTaskTitle').textContent = data.task_title;
        document.getElementById('modalReqReason').textContent = data.reason;
        document.getElementById('modalReqMessage').textContent = data.message;

        // Status badge
        let badgeHtml = '';
        if (data.status === 'approved') badgeHtml = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Approved</span>';
        else if (data.status === 'rejected') badgeHtml = '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Rejected</span>';
        else if (data.status === 'cancelled') badgeHtml = '<span class="badge bg-secondary"><i class="fas fa-ban me-1"></i>Cancelled</span>';
        else badgeHtml = '<span class="badge bg-danger pulse-dot"><i class="fas fa-bolt me-1"></i>Pending Review</span>';
        document.getElementById('modalReqStatusBadge').innerHTML = badgeHtml;

        // Attachment
        const attSec = document.getElementById('modalAttachmentSection');
        const attLink = document.getElementById('modalAttachmentLink');
        if (data.attachment) {
            attSec.classList.remove('d-none');
            attLink.href = '<?= BASE_URL ?>/uploads/emergencies/' + data.attachment;
        } else {
            attSec.classList.add('d-none');
        }

        // Review notes
        const revSec = document.getElementById('modalReviewSection');
        if (data.reviewer_name || data.reviewer_comment || data.rejection_reason) {
            revSec.classList.remove('d-none');
            document.getElementById('modalReviewerName').textContent = 'Reviewed By: ' + (data.reviewer_name || 'Executive Manager');
            document.getElementById('modalReviewTime').textContent = data.reviewed_at ? data.reviewed_at : '';
            document.getElementById('modalReviewNotes').textContent = data.reviewer_comment || data.rejection_reason || 'No comments.';
        } else {
            revSec.classList.add('d-none');
        }

        // Actions
        const actBox = document.getElementById('modalActionButtons');
        actBox.innerHTML = '';
        <?php if ($canManage): ?>
        if (data.status === 'pending') {
            actBox.innerHTML = `
                <button type="button" class="btn btn-success fw-bold" onclick="approveEmergencyPrompt(${data.id}, '${escapeQuote(data.task_title)}')">
                    <i class="fas fa-check me-1"></i>Approve
                </button>
                <button type="button" class="btn btn-danger fw-bold" onclick="rejectEmergencyPrompt(${data.id}, '${escapeQuote(data.task_title)}')">
                    <i class="fas fa-times me-1"></i>Reject
                </button>
            `;
        }
        <?php endif; ?>

        const modalEl = document.getElementById('emergencyDetailsModal');
        if (!activeModalInstance) {
            activeModalInstance = new bootstrap.Modal(modalEl);
        }
        activeModalInstance.show();
    }

    function escapeQuote(str) {
        return (str || '').replace(/'/g, "\\'");
    }

    // Approve Emergency Request
    function approveEmergencyPrompt(requestId, taskTitle) {
        Swal.fire({
            title: 'Approve Emergency Request?',
            html: `You are approving the emergency escalation for: <b>${taskTitle}</b>.<br><small class="text-muted">You can optionally enter approval notes below.</small>`,
            icon: 'question',
            input: 'textarea',
            inputPlaceholder: 'Optional approval notes or expedited sprint instructions...',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve Request',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'approve');
                formData.append('request_id', requestId);
                formData.append('reviewer_comment', result.value || '');
                formData.append('csrf_token', '<?= getCsrfToken() ?>');

                fetch('<?= BASE_URL ?>/api/emergency_requests', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message || 'Failed to approve request.', 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Network Error', 'Could not reach server to approve request.', 'error');
                });
            }
        });
    }

    // Reject Emergency Request
    function rejectEmergencyPrompt(requestId, taskTitle) {
        Swal.fire({
            title: 'Reject Emergency Request?',
            html: `You are rejecting the emergency escalation for: <b>${taskTitle}</b>.<br><small class="text-danger fw-bold">A rejection reason is required for the employee.</small>`,
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Enter clear reason for rejecting the emergency request...',
            inputValidator: (value) => {
                if (!value || !value.trim()) {
                    return 'Rejection reason is required!';
                }
            },
            showCancelButton: true,
            confirmButtonText: 'Reject Request',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'reject');
                formData.append('request_id', requestId);
                formData.append('rejection_reason', result.value.trim());
                formData.append('csrf_token', '<?= getCsrfToken() ?>');

                fetch('<?= BASE_URL ?>/api/emergency_requests', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Rejected',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message || 'Failed to reject request.', 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Network Error', 'Could not reach server to reject request.', 'error');
                });
            }
        });
    }
</script>
