<?php
/**
 * Office Wi-Fi IP Management & Whitelist Configuration - WorkFlow Pro
 * Centralized IP security engine for controlling Shift Start & End button visibility.
 */

$user = requireAdmin();
$pageTitle = "Office Wi-Fi IP Whitelist";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$db = Database::getConnection();
ensureAuthorizedIpTable($db);
ensureAttendanceColumns($db);

$currentAdminIp = getClientIp();
$currentDevice = getClientDeviceType();
$isCurrentAuthorized = isClientIpAuthorized($db, $currentAdminIp);
$currentNetwork = getIpNetworkName($db, $currentAdminIp);

$stmt = $db->query("SELECT * FROM authorized_ips ORDER BY status ASC, id ASC");
$ipList = $stmt->fetchAll() ?: [];
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-3 p-md-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 rounded-pill fs-8 fw-bold">
                        <i class="fas fa-wifi me-1"></i>Security Policy
                    </span>
                    <span class="text-secondary small">&bull;</span>
                    <span class="text-secondary small fw-medium">Wi-Fi IP Whitelist Controller</span>
                </div>
                <h3 class="fw-bold text-dark mb-0 font-heading">
                    Office Wi-Fi & IP Whitelist <span class="fs-6 fw-normal text-muted ms-1">| Attendance Access Control</span>
                </h3>
            </div>
            
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= BASE_URL ?>/shift-settings" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="fas fa-business-time me-1"></i>Shift Timing Settings
                </a>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" onclick="openAddIpModal()">
                    <i class="fas fa-plus-circle me-1"></i>Add Authorized IP
                </button>
            </div>
        </div>

        <!-- Current Connection & Security Status Banner -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-8">
                <div class="glass-card p-3.5 h-100 border-0 shadow-sm d-flex flex-column justify-content-between">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="p-2.5 rounded-3 <?= $isCurrentAuthorized ? 'bg-success text-white' : 'bg-danger text-white' ?> shadow-xs">
                                <i class="fas <?= $isCurrentAuthorized ? 'fa-wifi' : 'fa-wifi-slash' ?> fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark mb-0">Your Detected IP Address: <span class="font-monospace text-primary fs-6"><?= htmlspecialchars($currentAdminIp) ?></span></h6>
                                <small class="text-secondary fs-8">
                                    Device: <strong><?= htmlspecialchars($currentDevice) ?></strong> | 
                                    Network: <strong><?= htmlspecialchars($currentNetwork) ?></strong>
                                </small>
                            </div>
                        </div>

                        <div>
                            <?php if ($isCurrentAuthorized): ?>
                                <span class="badge bg-success text-white border border-success px-2.5 py-1.5 rounded-pill fs-8 fw-bold">
                                    <i class="fas fa-check-circle me-1"></i>Authorized Office Network
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger text-white border border-danger px-2.5 py-1.5 rounded-pill fs-8 fw-bold">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Unauthorized Network
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 pt-2 border-top">
                        <small class="text-secondary fs-8">
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            Shift Start & End buttons are strictly visible only to employees connected to authorized office Wi-Fi addresses.
                        </small>
                        <?php if (!$isCurrentAuthorized): ?>
                            <button type="button" class="btn btn-xs btn-outline-success rounded-pill fw-bold px-2.5 text-nowrap" onclick="prefillAddCurrentIp('<?= htmlspecialchars($currentAdminIp) ?>')">
                                <i class="fas fa-plus me-1"></i>Whitelist My Current IP
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Fast IP Tester Card -->
            <div class="col-12 col-lg-4">
                <div class="glass-card p-3.5 h-100 border-0 shadow-sm d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-dark mb-0 fs-7">
                            <i class="fas fa-vial text-primary me-1.5"></i>Live IP Permission Tester
                        </h6>
                        <span class="badge bg-light text-secondary border fs-9">Real-time Check</span>
                    </div>
                    <p class="text-secondary fs-9 mb-2">Test any IP to check if employees on it will see Shift buttons.</p>
                    <div class="input-group input-group-sm mb-2">
                        <input type="text" id="testIpInput" class="form-control font-monospace" placeholder="e.g. 192.168.29.174" value="<?= htmlspecialchars($currentAdminIp) ?>">
                        <button class="btn btn-primary fw-bold" type="button" onclick="runIpTest()">
                            <i class="fas fa-search me-1"></i>Check
                        </button>
                    </div>
                    <div id="testIpResult" class="fs-8 text-muted" style="min-height: 22px;"></div>
                </div>
            </div>
        </div>

        <!-- Authorized IPs Whitelist Table -->
        <div class="glass-card p-4 border-0 shadow-sm mb-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        <i class="fas fa-list-check text-success me-2"></i>Configured Office Wi-Fi Whitelist
                    </h5>
                    <small class="text-secondary">Manage authorized office IP addresses, Wi-Fi router networks, and active toggles.</small>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" onclick="reloadIpList()">
                    <i class="fas fa-sync-alt me-1"></i>Refresh
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="authorizedIpsTable">
                    <thead class="table-light fs-8 text-uppercase">
                        <tr>
                            <th class="ps-3" style="width: 70px;">#ID</th>
                            <th>IP Address / Subnet</th>
                            <th>Location / Network Name</th>
                            <th>Status</th>
                            <th>Description / Notes</th>
                            <th>Created On</th>
                            <th class="text-end pe-3" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ipTableBody" class="fs-7">
                        <?php if (empty($ipList)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-wifi text-secondary fa-2x mb-2 d-block opacity-50"></i>
                                    No authorized IP addresses configured yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($ipList as $ip): 
                                $isActive = ($ip['status'] === 'active');
                                $isCurrent = ($ip['ip_address'] === $currentAdminIp);
                            ?>
                                <tr id="ip-row-<?= (int)$ip['id'] ?>" class="<?= $isCurrent ? 'table-success-subtle' : '' ?>">
                                    <td class="ps-3 fw-bold text-muted"><?= (int)$ip['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-light text-dark font-monospace fs-7 border px-2.5 py-1">
                                                <i class="fas fa-network-wired text-primary me-1.5"></i><?= htmlspecialchars($ip['ip_address']) ?>
                                            </span>
                                            <?php if ($isCurrent): ?>
                                                <span class="badge bg-primary-subtle text-primary border border-primary fs-9 fw-bold">Your IP</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-dark"><?= htmlspecialchars($ip['location']) ?></strong>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch m-0 d-inline-flex align-items-center gap-2">
                                            <input class="form-check-input" type="checkbox" role="switch" id="toggle-<?= (int)$ip['id'] ?>" 
                                                <?= $isActive ? 'checked' : '' ?> 
                                                onchange="toggleIpStatus(<?= (int)$ip['id'] ?>, this.checked)">
                                            <label class="form-check-label fs-8 fw-bold <?= $isActive ? 'text-success' : 'text-secondary' ?>" for="toggle-<?= (int)$ip['id'] ?>">
                                                <?= $isActive ? 'Active' : 'Inactive' ?>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary small"><?= htmlspecialchars($ip['notes'] ?: '--') ?></span>
                                    </td>
                                    <td>
                                        <small class="text-muted font-monospace"><?= htmlspecialchars(substr($ip['created_at'] ?? '', 0, 10)) ?></small>
                                    </td>
                                    <td class="text-end pe-3">
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-secondary" title="Edit IP" 
                                                onclick="openEditIpModal(<?= htmlspecialchars(json_encode($ip)) ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" title="Delete IP" 
                                                onclick="deleteIpRecord(<?= (int)$ip['id'] ?>, '<?= htmlspecialchars($ip['ip_address']) ?>')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Attendance Logs by IP & Device Information -->
        <div class="glass-card p-4 border-0 shadow-sm">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        <i class="fas fa-clock-rotate-left text-primary me-2"></i>Recent Attendance Records by IP & Device
                    </h5>
                    <small class="text-secondary">Audit trail of employee shift records showing recorded client IP addresses and devices.</small>
                </div>
                <div class="d-flex gap-2">
                    <input type="text" id="ipLogFilter" class="form-control form-control-sm font-monospace" placeholder="Filter by IP..." style="width: 180px;" oninput="filterIpLogs(this.value)">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="ipAttendanceTable">
                    <thead class="table-light fs-8 text-uppercase">
                        <tr>
                            <th class="ps-3">Employee</th>
                            <th>Date</th>
                            <th>Clock In Time</th>
                            <th>Clock In IP</th>
                            <th>Clock Out Time</th>
                            <th>Clock Out IP</th>
                            <th>Device</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="ipLogsBody" class="fs-7">
                        <tr>
                            <td colspan="8" class="text-center py-3 text-muted">
                                <i class="fas fa-spinner fa-spin me-1"></i>Loading attendance records...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Authorized IP -->
<div class="modal fade" id="addIpModal" tabindex="-1" aria-labelledby="addIpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title fw-bold" id="addIpModalLabel">
                    <i class="fas fa-plus-circle me-1.5"></i>Add Authorized Office IP
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addIpForm" onsubmit="submitAddIp(event)">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold fs-7">IP Address / Wildcard / Subnet <span class="text-danger">*</span></label>
                        <input type="text" name="ip_address" id="addIpAddress" class="form-control font-monospace" placeholder="e.g. 192.168.29.174 or 192.168.1.*" required>
                        <small class="text-muted fs-8 d-block mt-1">Supports exact IPv4 (192.168.29.174), wildcard (192.168.29.*), or CIDR subnet (192.168.29.0/24).</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold fs-7">Location / Network Name <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="addIpLocation" class="form-control" placeholder="e.g. Office Wi-Fi 1, Office Wi-Fi 2" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold fs-7">Status</label>
                        <select name="status" id="addIpStatus" class="form-select">
                            <option value="active" selected>Active (Allow Shift Control)</option>
                            <option value="inactive">Inactive (Disabled)</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label text-dark fw-bold fs-7">Notes / Description</label>
                        <textarea name="notes" id="addIpNotes" class="form-control" rows="2" placeholder="Optional notes (e.g. 5GHz Main Office Router)"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" id="btnSaveAddIp">
                        <i class="fas fa-save me-1"></i>Save Authorized IP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Authorized IP -->
<div class="modal fade" id="editIpModal" tabindex="-1" aria-labelledby="editIpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title fw-bold" id="editIpModalLabel">
                    <i class="fas fa-edit me-1.5"></i>Edit Authorized IP
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editIpForm" onsubmit="submitEditIp(event)">
                <input type="hidden" name="id" id="editIpId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold fs-7">IP Address / Subnet <span class="text-danger">*</span></label>
                        <input type="text" name="ip_address" id="editIpAddress" class="form-control font-monospace" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold fs-7">Location / Network Name <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="editIpLocation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold fs-7">Status</label>
                        <select name="status" id="editIpStatus" class="form-select">
                            <option value="active">Active (Allow Shift Control)</option>
                            <option value="inactive">Inactive (Disabled)</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label text-dark fw-bold fs-7">Notes / Description</label>
                        <textarea name="notes" id="editIpNotes" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" id="btnSaveEditIp">
                        <i class="fas fa-save me-1"></i>Update IP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    loadIpAttendanceLogs();
});

function openAddIpModal() {
    document.getElementById('addIpForm').reset();
    const modal = new bootstrap.Modal(document.getElementById('addIpModal'));
    modal.show();
}

function prefillAddCurrentIp(ip) {
    openAddIpModal();
    document.getElementById('addIpAddress').value = ip;
    document.getElementById('addIpLocation').value = 'Office Wi-Fi (Current)';
    document.getElementById('addIpNotes').value = 'Added from Admin Dashboard detected connection';
}

function openEditIpModal(ipData) {
    document.getElementById('editIpId').value = ipData.id;
    document.getElementById('editIpAddress').value = ipData.ip_address;
    document.getElementById('editIpLocation').value = ipData.location;
    document.getElementById('editIpStatus').value = ipData.status;
    document.getElementById('editIpNotes').value = ipData.notes || '';
    const modal = new bootstrap.Modal(document.getElementById('editIpModal'));
    modal.show();
}

function submitAddIp(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSaveAddIp');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

    const formData = new FormData(document.getElementById('addIpForm'));
    formData.append('action', 'add');

    fetch(resolveApiUrl('/api/ip_management'), {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Authorized IP';
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('addIpModal')).hide();
            Swal.fire({
                icon: 'success',
                title: 'Authorized IP Added',
                text: res.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Authorized IP';
        Swal.fire('Error', 'Failed to add IP: ' + err.message, 'error');
    });
}

function submitEditIp(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSaveEditIp');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Updating...';

    const formData = new FormData(document.getElementById('editIpForm'));
    formData.append('action', 'update');

    fetch(resolveApiUrl('/api/ip_management'), {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Update IP';
        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('editIpModal')).hide();
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: res.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Update IP';
        Swal.fire('Error', 'Failed to update IP: ' + err.message, 'error');
    });
}

function toggleIpStatus(id, isChecked) {
    const status = isChecked ? 'active' : 'inactive';
    const label = document.querySelector(`label[for="toggle-${id}"]`);
    if (label) {
        label.className = `form-check-label fs-8 fw-bold ${isChecked ? 'text-success' : 'text-secondary'}`;
        label.textContent = isChecked ? 'Active' : 'Inactive';
    }

    const formData = new URLSearchParams();
    formData.append('action', 'toggle_status');
    formData.append('id', id);
    formData.append('status', status);

    fetch(resolveApiUrl('/api/ip_management'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
    })
    .then(r => r.json())
    .then(res => {
        if (!res.success) {
            Swal.fire('Error', res.message, 'error');
            location.reload();
        } else {
            const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
            toast.fire({ icon: 'success', title: res.message });
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Toggle failed: ' + err.message, 'error');
        location.reload();
    });
}

function deleteIpRecord(id, ipAddress) {
    Swal.fire({
        title: 'Delete Authorized IP?',
        text: `Are you sure you want to remove ${ipAddress} from the authorized whitelist? Employees on this IP will no longer see Shift buttons.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete IP'
    }).then(result => {
        if (result.isConfirmed) {
            const formData = new URLSearchParams();
            formData.append('action', 'delete');
            formData.append('id', id);

            fetch(resolveApiUrl('/api/ip_management'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Delete failed: ' + err.message, 'error');
            });
        }
    });
}

function runIpTest() {
    const input = document.getElementById('testIpInput');
    const resultEl = document.getElementById('testIpResult');
    const ip = (input.value || '').trim();
    if (!ip) {
        resultEl.innerHTML = '<span class="text-danger">Please enter an IP address.</span>';
        return;
    }

    resultEl.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Checking authorization...';

    fetch(resolveApiUrl(`/api/ip_management?action=test_ip&test_ip=${encodeURIComponent(ip)}`))
    .then(r => r.json())
    .then(res => {
        if (res.success && res.data) {
            const isAuth = res.data.is_authorized;
            if (isAuth) {
                resultEl.innerHTML = `<span class="badge bg-success text-white border border-success p-1.5"><i class="fas fa-check-circle me-1"></i>AUTHORIZED: ${escapeHtml(res.data.network_name)}</span>`;
            } else {
                resultEl.innerHTML = `<span class="badge bg-danger text-white border border-danger p-1.5"><i class="fas fa-times-circle me-1"></i>UNAUTHORIZED: Shift buttons will be hidden</span>`;
            }
        } else {
            resultEl.innerHTML = `<span class="text-danger">${escapeHtml(res.message || 'Check failed')}</span>`;
        }
    })
    .catch(err => {
        resultEl.innerHTML = `<span class="text-danger">Check failed: ${escapeHtml(err.message)}</span>`;
    });
}

let cachedIpLogs = [];
function loadIpAttendanceLogs() {
    fetch(resolveApiUrl('/api/ip_management?action=attendance_logs&limit=50'))
    .then(r => r.json())
    .then(res => {
        if (res.success && res.data) {
            cachedIpLogs = res.data.logs || [];
            renderIpLogs(cachedIpLogs);
        } else {
            document.getElementById('ipLogsBody').innerHTML = '<tr><td colspan="8" class="text-center py-3 text-muted">No attendance logs available.</td></tr>';
        }
    })
    .catch(err => {
        document.getElementById('ipLogsBody').innerHTML = `<tr><td colspan="8" class="text-center py-3 text-danger">Failed to load logs: ${escapeHtml(err.message)}</td></tr>`;
    });
}

function renderIpLogs(logs) {
    const tbody = document.getElementById('ipLogsBody');
    if (!logs || logs.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center py-3 text-muted">No matching attendance records found.</td></tr>';
        return;
    }

    let html = '';
    logs.forEach(r => {
        const devBadge = (r.device_type === 'Mobile') 
            ? '<span class="badge bg-info-subtle text-info border border-info fs-9"><i class="fas fa-mobile-alt me-1"></i>Mobile</span>'
            : ((r.device_type === 'Tablet')
                ? '<span class="badge bg-purple-subtle text-purple border border-purple fs-9"><i class="fas fa-tablet-alt me-1"></i>Tablet</span>'
                : '<span class="badge bg-secondary-subtle text-secondary border fs-9"><i class="fas fa-desktop me-1"></i>Desktop</span>');

        const inIpBadge = (r.clock_in_ip && r.clock_in_ip !== '--')
            ? `<span class="badge bg-light text-dark font-monospace fs-8 border">${escapeHtml(r.clock_in_ip)}</span>`
            : '<span class="text-muted">--</span>';

        const outIpBadge = (r.clock_out_ip && r.clock_out_ip !== '--')
            ? `<span class="badge bg-light text-dark font-monospace fs-8 border">${escapeHtml(r.clock_out_ip)}</span>`
            : '<span class="text-muted">--</span>';

        const statusBadge = (r.status === 'full_day') 
            ? '<span class="badge bg-success-subtle text-success border border-success fs-9">Full Day</span>'
            : ((r.status === 'half_day') 
                ? '<span class="badge bg-warning-subtle text-warning border border-warning fs-9">Half Day</span>'
                : `<span class="badge bg-light text-muted border fs-9">${escapeHtml(r.status || 'Active')}</span>`);

        html += `
            <tr>
                <td class="ps-3">
                    <strong class="text-dark d-block">${escapeHtml(r.employee_name)}</strong>
                    <small class="text-muted fs-8">${escapeHtml(r.employee_code || r.designation || '')}</small>
                </td>
                <td class="font-monospace">${escapeHtml(r.date_formatted || r.date)}</td>
                <td class="font-monospace text-success fw-bold">${escapeHtml(r.clock_in)}</td>
                <td>${inIpBadge}</td>
                <td class="font-monospace text-danger fw-bold">${escapeHtml(r.clock_out)}</td>
                <td>${outIpBadge}</td>
                <td>${devBadge}</td>
                <td>${statusBadge}</td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function filterIpLogs(query) {
    const q = (query || '').toLowerCase().trim();
    if (!q) {
        renderIpLogs(cachedIpLogs);
        return;
    }
    const filtered = cachedIpLogs.filter(r => {
        return (r.employee_name && r.employee_name.toLowerCase().includes(q)) ||
               (r.clock_in_ip && r.clock_in_ip.toLowerCase().includes(q)) ||
               (r.clock_out_ip && r.clock_out_ip.toLowerCase().includes(q)) ||
               (r.device_type && r.device_type.toLowerCase().includes(q));
    });
    renderIpLogs(filtered);
}

function reloadIpList() {
    location.reload();
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[m]);
}
</script>

