<?php
$pageTitle = "Leave Management & Paid Quotas";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();
ensurePayrollSchema($db);

$isAdminOrManager = hasRole(['admin', 'manager']);
$monthYear = date('Y-m');

$leaveBalance = getEmployeeLeaveBalance((int)$user['id'], $monthYear);

// Fetch leaves
if ($isAdminOrManager) {
    $leaves = $db->query("
        SELECT l.*, COALESCE(u.name, 'Unknown') as user_name, u.employee_code, m.name as approver_name
        FROM leave_requests l
        LEFT JOIN users u ON l.user_id = u.id
        LEFT JOIN users m ON l.approved_by = m.id
        ORDER BY l.id DESC
    ")->fetchAll();
} else {
    $stmt = $db->prepare("
        SELECT l.*, COALESCE(u.name, 'Unknown') as user_name, u.employee_code, m.name as approver_name
        FROM leave_requests l
        LEFT JOIN users u ON l.user_id = u.id
        LEFT JOIN users m ON l.approved_by = m.id
        WHERE l.user_id = ?
        ORDER BY l.id DESC
    ");
    $stmt->execute([$user['id']]);
    $leaves = $stmt->fetchAll();
}
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-calendar-minus text-warning me-2"></i>Paid Leave & Time-Off Management</h3>
                <p class="text-secondary small mb-0">Apply for time off, check monthly paid quota balances, and track approvals.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#requestLeaveModal"><i class="fas fa-paper-plane me-1"></i>Apply for Leave</button>
        </div>

        <!-- Leave Balance Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="glass-card stat-card">
                    <div class="stat-icon primary"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <div class="stat-value"><?= htmlspecialchars($leaveBalance['monthly_quota']) ?> Days</div>
                        <div class="stat-label">Monthly Paid Quota</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="glass-card stat-card">
                    <div class="stat-icon success"><i class="fas fa-umbrella-beach"></i></div>
                    <div class="stat-info">
                        <div class="stat-value text-success"><?= htmlspecialchars($leaveBalance['remaining_paid']) ?> Days</div>
                        <div class="stat-label">Remaining Paid Balance</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="glass-card stat-card">
                    <div class="stat-icon warning"><i class="fas fa-user-clock"></i></div>
                    <div class="stat-info">
                        <div class="stat-value text-warning"><?= htmlspecialchars($leaveBalance['used_paid']) ?> Days</div>
                        <div class="stat-label">Used Paid Leaves</div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="glass-card stat-card">
                    <div class="stat-icon danger"><i class="fas fa-money-bill-transfer"></i></div>
                    <div class="stat-info">
                        <div class="stat-value text-danger"><?= htmlspecialchars($leaveBalance['used_unpaid']) ?> Days</div>
                        <div class="stat-label">Unpaid Leave Days</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave History Table -->
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list text-primary me-2"></i>Leave Applications & History</h5>
                <small class="text-muted">Quota: <strong>2 Paid Leaves/Month</strong>.</small>
            </div>
            <!-- Desktop Table View -->
            <div class="table-responsive d-none d-md-block">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Dates & Duration</th>
                            <th>Quota Breakdown</th>
                            <th>Reason & Attachment</th>
                            <th>Status</th>
                            <th>Approver / Remarks</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leaves)): foreach ($leaves as $l): 
                            $statusBadge = '<span class="badge bg-warning text-dark">Pending</span>';
                            if ($l['status'] === 'approved') $statusBadge = '<span class="badge bg-success">Approved</span>';
                            elseif ($l['status'] === 'rejected') $statusBadge = '<span class="badge bg-danger">Rejected</span>';
                            elseif ($l['status'] === 'cancelled') $statusBadge = '<span class="badge bg-secondary">Cancelled</span>';

                            $type = strtolower($l['leave_type'] ?? 'paid');
                            $typeBadge = '<span class="badge bg-primary-subtle text-primary">' . ucfirst($type) . '</span>';
                            if ($type === 'unpaid') $typeBadge = '<span class="badge bg-danger-subtle text-danger">Unpaid</span>';
                            elseif ($type === 'sick') $typeBadge = '<span class="badge bg-info-subtle text-info">Sick</span>';
                            elseif ($type === 'emergency') $typeBadge = '<span class="badge bg-warning-subtle text-warning text-dark">Emergency</span>';
                            elseif ($type === 'casual') $typeBadge = '<span class="badge bg-secondary-subtle text-secondary">Casual</span>';

                            $totalDays = (float)($l['total_days'] ?? 1);
                            $paidDays = (float)($l['paid_days'] ?? 0);
                            $unpaidDays = (float)($l['unpaid_days'] ?? 0);
                        ?>
                            <tr>
                                <td class="fw-bold text-dark">
                                    <?= sanitize($l['user_name']) ?>
                                    <small class="text-muted d-block font-monospace"><?= sanitize($l['employee_code'] ?? ('EMP-' . str_pad($l['user_id'] ?? 0, 3, '0', STR_PAD_LEFT))) ?></small>
                                </td>
                                <td><?= $typeBadge ?></td>
                                <td class="small text-secondary">
                                    <strong><?= formatISTDate($l['start_date']) ?></strong> to <strong><?= formatISTDate($l['end_date']) ?></strong>
                                    <div class="fw-bold text-dark mt-1"><?= $totalDays ?> Total Days</div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="text-success fw-bold"><?= $paidDays ?> Paid</span> | 
                                        <span class="text-danger fw-bold"><?= $unpaidDays ?> Unpaid</span>
                                    </div>
                                </td>
                                <td class="small">
                                    <div class="text-dark mb-1"><?= sanitize($l['reason']) ?></div>
                                    <?php if (!empty($l['attachment'])): ?>
                                        <a href="<?= BASE_URL ?>/api/leaves?action=download_attachment&id=<?= (int)$l['id'] ?>" target="_blank" class="badge bg-light text-primary border"><i class="fas fa-paperclip me-1"></i>View Attachment</a>
                                    <?php endif; ?>
                                </td>
                                <td><?= $statusBadge ?></td>
                                <td class="small text-muted">
                                    <div><strong>Approver:</strong> <?= sanitize($l['approver_name'] ?: 'Pending') ?></div>
                                    <?php if (!empty($l['comment'])): ?>
                                        <div class="text-secondary fst-italic">"<?= sanitize($l['comment']) ?>"</div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <?php if ($l['status'] === 'pending'): ?>
                                        <?php if ($isAdminOrManager): ?>
                                            <button class="btn btn-sm btn-success me-1" onclick="openApproveModal(<?= (int)$l['id'] ?>)" title="Approve"><i class="fas fa-check"></i></button>
                                            <button class="btn btn-sm btn-danger me-1" onclick="openRejectModal(<?= (int)$l['id'] ?>)" title="Reject"><i class="fas fa-times"></i></button>
                                        <?php endif; ?>
                                        <?php if ((int)$l['user_id'] === (int)$user['id']): ?>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="cancelLeave(<?= (int)$l['id'] ?>)" title="Cancel Request"><i class="fas fa-ban"></i></button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">Closed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">No leave records found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Leave Cards View (< 768px) -->
            <div class="mobile-card-list d-block d-md-none">
                <?php if (!empty($leaves)): foreach ($leaves as $l): 
                    $statusBadge = '<span class="badge bg-warning text-dark fs-8">Pending</span>';
                    if ($l['status'] === 'approved') $statusBadge = '<span class="badge bg-success fs-8">Approved</span>';
                    elseif ($l['status'] === 'rejected') $statusBadge = '<span class="badge bg-danger fs-8">Rejected</span>';
                    elseif ($l['status'] === 'cancelled') $statusBadge = '<span class="badge bg-secondary fs-8">Cancelled</span>';

                    $type = strtolower($l['leave_type'] ?? 'paid');
                    $typeBadge = '<span class="badge bg-primary-subtle text-primary fs-8">' . ucfirst($type) . '</span>';
                    if ($type === 'unpaid') $typeBadge = '<span class="badge bg-danger-subtle text-danger fs-8">Unpaid</span>';
                    elseif ($type === 'sick') $typeBadge = '<span class="badge bg-info-subtle text-info fs-8">Sick</span>';
                    elseif ($type === 'emergency') $typeBadge = '<span class="badge bg-warning-subtle text-warning text-dark fs-8">Emergency</span>';
                    elseif ($type === 'casual') $typeBadge = '<span class="badge bg-secondary-subtle text-secondary fs-8">Casual</span>';

                    $totalDays = (float)($l['total_days'] ?? 1);
                    $paidDays = (float)($l['paid_days'] ?? 0);
                    $unpaidDays = (float)($l['unpaid_days'] ?? 0);
                ?>
                    <div class="mobile-data-card p-3 border rounded mb-3 bg-white shadow-sm">
                        <div class="mobile-data-card-header d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="fw-bold text-dark d-block"><?= sanitize($l['user_name']) ?></span>
                                <small class="text-muted font-monospace fs-8"><?= sanitize($l['employee_code'] ?? ('EMP-' . str_pad($l['user_id'] ?? 0, 3, '0', STR_PAD_LEFT))) ?></small>
                            </div>
                            <div><?= $statusBadge ?></div>
                        </div>
                        <div class="mobile-data-card-body row g-2 mb-2">
                            <div class="col-6">
                                <small class="text-muted d-block fs-8">Leave Type</small>
                                <?= $typeBadge ?>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block fs-8">Duration</small>
                                <span class="fw-bold text-dark fs-8"><?= $totalDays ?> Days</span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block fs-8">Dates</small>
                                <span class="text-secondary fs-8"><?= formatISTDate($l['start_date']) ?> - <?= formatISTDate($l['end_date']) ?></span>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block fs-8">Quota Breakdown</small>
                                <span class="text-success fw-bold fs-8"><?= $paidDays ?> Paid</span> / <span class="text-danger fw-bold fs-8"><?= $unpaidDays ?> Unpaid</span>
                            </div>
                            <?php if (!empty($l['reason'])): ?>
                            <div class="col-12">
                                <small class="text-muted d-block fs-8">Reason</small>
                                <span class="text-dark fs-8"><?= sanitize($l['reason']) ?></span>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($l['attachment'])): ?>
                            <div class="col-12">
                                <a href="<?= BASE_URL ?>/api/leaves?action=download_attachment&id=<?= (int)$l['id'] ?>" target="_blank" class="badge bg-light text-primary border"><i class="fas fa-paperclip me-1"></i>View Attachment</a>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="mobile-data-card-footer border-top pt-2 d-flex justify-content-end gap-1">
                            <?php if ($l['status'] === 'pending'): ?>
                                <?php if ($isAdminOrManager): ?>
                                    <button class="btn btn-sm btn-success me-1" onclick="openApproveModal(<?= (int)$l['id'] ?>)"><i class="fas fa-check me-1"></i>Approve</button>
                                    <button class="btn btn-sm btn-danger me-1" onclick="openRejectModal(<?= (int)$l['id'] ?>)"><i class="fas fa-times me-1"></i>Reject</button>
                                <?php endif; ?>
                                <?php if ((int)$l['user_id'] === (int)$user['id']): ?>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="cancelLeave(<?= (int)$l['id'] ?>)"><i class="fas fa-ban me-1"></i>Cancel</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted small">Status: <?= ucfirst($l['status']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; else: ?>
                    <div class="text-center text-muted py-4">No leave records found.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Apply Leave -->
<div class="modal fade" id="requestLeaveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-paper-plane text-primary me-2"></i>Submit Leave Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="leaveForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <div class="modal-body">
                    <div class="p-3 bg-light rounded border mb-3">
                        <small class="text-secondary d-block">Current Paid Leave Balance for <?= date('F Y') ?>:</small>
                        <strong class="text-success fs-5"><?= htmlspecialchars($leaveBalance['remaining_paid']) ?> Paid Days Left</strong>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Leave Category *</label>
                        <select name="leave_type" class="form-select" required>
                            <option value="paid" selected>Paid Leave (Uses Monthly Quota)</option>
                            <option value="sick">Sick Leave</option>
                            <option value="casual">Casual Leave</option>
                            <option value="emergency">Emergency Leave</option>
                            <option value="unpaid">Unpaid Leave (Salary Deduction)</option>
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">From Date *</label>
                            <input type="date" name="start_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">To Date *</label>
                            <input type="date" name="end_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Reason for Leave *</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Provide reason for time off..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Optional Document Attachment</label>
                        <input type="file" name="attachment" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                        <small class="text-muted">Allowed: JPG, PNG, PDF, DOC (Max 5MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="leaveSubmitBtn"><i class="fas fa-paper-plane me-1"></i>Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Admin Response Remarks Modal -->
<?php if ($isAdminOrManager): ?>
<div class="modal fade" id="leaveActionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="leaveActionTitle">Leave Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="leaveActionForm">
                <input type="hidden" name="leave_id" id="actionLeaveId">
                <input type="hidden" name="action" id="actionType">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Manager Remarks / Comment</label>
                        <textarea name="comment" id="actionComment" class="form-control" rows="3" placeholder="Enter remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="actionSubmitBtn">Submit Response</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    document.getElementById('leaveForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('leaveSubmitBtn');
        const origHtml = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...';
        }

        const formData = new FormData(this);
        formData.append('action', 'request');
        fetch('<?= BASE_URL ?>/api/leaves', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Submitted',
                    text: res.message
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message
                });
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHtml;
                }
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Submission Error',
                text: 'Network or server error. Please try again.'
            });
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origHtml;
            }
        });
    });

    function cancelLeave(id) {
        Swal.fire({
            title: 'Cancel Request?',
            text: 'Are you sure you want to cancel this pending leave application?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Cancel'
        }).then(res => {
            if (res.isConfirmed) {
                const bodyData = new URLSearchParams();
                bodyData.append('action', 'cancel');
                bodyData.append('leave_id', id);
                bodyData.append('csrf_token', typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : '');

                fetch('<?= BASE_URL ?>/api/leaves', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: bodyData.toString()
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Cancelled',
                            text: res.message
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: res.message
                        });
                    }
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to cancel request.'
                    });
                });
            }
        });
    }
    const cancelPendingLeave = cancelLeave;

    <?php if ($isAdminOrManager): ?>
    function openApproveModal(id) {
        document.getElementById('actionLeaveId').value = id;
        document.getElementById('actionType').value = 'approve';
        document.getElementById('leaveActionTitle').innerText = 'Approve Leave Application';
        document.getElementById('actionSubmitBtn').className = 'btn btn-success fw-bold';
        document.getElementById('actionSubmitBtn').innerText = 'Confirm Approval';
        document.getElementById('actionComment').value = 'Approved by manager.';
        new bootstrap.Modal(document.getElementById('leaveActionModal')).show();
    }

    function openRejectModal(id) {
        document.getElementById('actionLeaveId').value = id;
        document.getElementById('actionType').value = 'reject';
        document.getElementById('leaveActionTitle').innerText = 'Reject Leave Application';
        document.getElementById('actionSubmitBtn').className = 'btn btn-danger fw-bold';
        document.getElementById('actionSubmitBtn').innerText = 'Confirm Rejection';
        document.getElementById('actionComment').value = 'Rejected by manager.';
        new bootstrap.Modal(document.getElementById('leaveActionModal')).show();
    }

    document.getElementById('leaveActionForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const submitBtn = document.getElementById('actionSubmitBtn');
        const origHtml = submitBtn ? submitBtn.innerHTML : '';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Processing...';
        }

        const formData = new FormData(this);
        formData.append('csrf_token', typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : '');

        fetch('<?= BASE_URL ?>/api/leaves', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.message
                });
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = origHtml;
                }
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Action Error',
                text: 'Failed to process leave action.'
            });
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = origHtml;
            }
        });
    });
    <?php endif; ?>
</script>
