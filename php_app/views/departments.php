<?php
$pageTitle = "Departments";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$canManageDepartments = hasRole(['admin', 'manager', 'product_manager']) || canCreateOrEditTasks();
$db = Database::getConnection();

$departments = $db->query("
    SELECT d.*, 
           (SELECT COUNT(*) FROM users WHERE department_id = d.id) as total_users,
           (SELECT COUNT(*) FROM tasks WHERE department_id = d.id) as total_tasks
    FROM departments d
    ORDER BY d.id ASC
")->fetchAll();

$totalDepts = count($departments);
$totalStaff = (int)$db->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn();
$totalTasks = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE status NOT IN ('completed', 'done')")->fetchColumn();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fas fa-building text-primary me-2"></i>Departments &amp; Units
                </h3>
                <p class="text-secondary small mb-0">Overview of organizational structure, team allocation, and task distribution.</p>
            </div>
            <?php if ($canManageDepartments): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
                <i class="fas fa-plus me-1"></i>New Department
            </button>
            <?php endif; ?>
        </div>

        <!-- Metric Badges -->
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="glass-card py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-secondary small">Total Departments</div>
                        <h4 class="fw-bold text-dark mb-0"><?= $totalDepts ?></h4>
                    </div>
                    <div class="p-3 bg-primary-subtle text-primary rounded-circle">
                        <i class="fas fa-sitemap fa-lg"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="glass-card py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-secondary small">Total Active Staff</div>
                        <h4 class="fw-bold text-info mb-0"><?= $totalStaff ?></h4>
                    </div>
                    <div class="p-3 bg-info-subtle text-info rounded-circle">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="glass-card py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-secondary small">Active Department Tasks</div>
                        <h4 class="fw-bold text-success mb-0"><?= $totalTasks ?></h4>
                    </div>
                    <div class="p-3 bg-success-subtle text-success rounded-circle">
                        <i class="fas fa-tasks fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departments Grid -->
        <div class="row g-4">
            <?php foreach ($departments as $d): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="glass-card h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="badge bg-primary-subtle text-primary font-monospace fs-7 px-3 py-1.5 border border-primary-subtle">
                                    <?= sanitize($d['code']) ?>
                                </span>
                                <span class="badge bg-light text-muted border">#<?= $d['id'] ?></span>
                            </div>

                            <h5 class="fw-bold text-dark mb-2"><?= sanitize($d['name']) ?></h5>
                            <p class="text-secondary small mb-4">
                                <?= sanitize($d['description'] ?: 'No detailed department description.') ?>
                            </p>
                        </div>

                        <div>
                            <div class="row text-center py-2 border-top border-bottom bg-light bg-opacity-50 rounded mb-3">
                                <div class="col-6 border-end">
                                    <a href="<?= BASE_URL ?>/employees" class="text-decoration-none text-reset d-block" title="View Employees">
                                        <div class="fw-bold text-dark fs-5"><?= $d['total_users'] ?></div>
                                        <div class="text-muted small">Employees &rarr;</div>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="<?= BASE_URL ?>/tasks" class="text-decoration-none text-reset d-block" title="View Tasks">
                                        <div class="fw-bold text-info fs-5"><?= $d['total_tasks'] ?></div>
                                        <div class="text-muted small">Active Tasks &rarr;</div>
                                    </a>
                                </div>
                            </div>

                            <!-- Actions Row -->
                            <?php if ($canManageDepartments): ?>
                            <div class="d-flex justify-content-end gap-2 pt-1">
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        data-dept="<?= htmlspecialchars(json_encode([
                                            'id' => (int)$d['id'],
                                            'name' => $d['name'],
                                            'code' => $d['code'],
                                            'description' => $d['description'] ?? ''
                                        ]), ENT_QUOTES, 'UTF-8') ?>"
                                        onclick="openEditDepartmentModal(JSON.parse(this.getAttribute('data-dept')))">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>

                                <?php if ((int)$d['id'] !== 1): ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-id="<?= (int)$d['id'] ?>"
                                            data-name="<?= htmlspecialchars($d['name'], ENT_QUOTES, 'UTF-8') ?>"
                                            data-users="<?= (int)$d['total_users'] ?>"
                                            onclick="deleteDepartmentPrompt(this)">
                                        <i class="fas fa-trash-alt me-1"></i>Delete
                                    </button>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border py-2 px-2 d-inline-flex align-items-center" title="Primary system department (protected)">
                                        <i class="fas fa-shield-alt me-1 text-primary"></i>System
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php else: ?>
                            <div class="d-flex justify-content-between align-items-center pt-1 text-muted small">
                                <span><i class="fas fa-building me-1 text-primary"></i>Department #<?= (int)$d['id'] ?></span>
                                <span class="badge bg-light text-secondary border font-monospace"><?= sanitize($d['code']) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ================= CREATE DEPARTMENT MODAL ================= -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Create New Department
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createDepartmentForm">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Department Name *</label>
                        <input type="text" name="name" id="create_dept_name" class="form-control" placeholder="e.g., Quality Assurance or Marketing" required oninput="suggestDeptCode(this.value, 'create_dept_code')">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Department Code *</label>
                        <input type="text" name="code" id="create_dept_code" class="form-control font-monospace text-uppercase" placeholder="e.g., QA, MKT, HR" maxlength="10" required>
                        <div class="form-text small">Short unique abbreviation (2-6 letters).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Outline functions, deliverables, and responsibilities..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="createDeptSubmitBtn">
                        <i class="fas fa-check me-1"></i>Create Department
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= EDIT DEPARTMENT MODAL ================= -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Department
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editDepartmentForm">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="department_id" id="edit_department_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Department Name *</label>
                        <input type="text" name="name" id="edit_dept_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Department Code *</label>
                        <input type="text" name="code" id="edit_dept_code" class="form-control font-monospace text-uppercase" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Description</label>
                        <textarea name="description" id="edit_dept_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="editDeptSubmitBtn">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    // Auto-suggest department code from name
    function suggestDeptCode(name, targetId) {
        const input = document.getElementById(targetId);
        if (!input || input.dataset.touched === '1') return;
        const words = name.trim().split(/\s+/);
        let code = '';
        if (words.length > 1) {
            code = words.map(w => w[0] || '').join('').substring(0, 4);
        } else {
            code = name.replace(/[^a-zA-Z]/g, '').substring(0, 3);
        }
        input.value = code.toUpperCase();
    }

    document.getElementById('create_dept_code')?.addEventListener('input', function() {
        this.dataset.touched = '1';
    });

    // Create Department
    document.getElementById('createDepartmentForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('createDeptSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating...';

        const formData = new FormData(this);
        formData.append('action', 'create');

        fetch('<?= BASE_URL ?>/api/departments', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Create Department';
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Department Created',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', res.message || 'Failed to create department.', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Create Department';
            Swal.fire('Network Error', 'Could not reach server to create department.', 'error');
        });
    });

    // Open Edit Department Modal
    let editDeptModalInstance = null;
    function openEditDepartmentModal(data) {
        if (!data) return;
        document.getElementById('edit_department_id').value = data.id || '';
        document.getElementById('edit_dept_name').value = data.name || '';
        document.getElementById('edit_dept_code').value = data.code || '';
        document.getElementById('edit_dept_description').value = data.description || '';

        const modalEl = document.getElementById('editDepartmentModal');
        if (!editDeptModalInstance) {
            editDeptModalInstance = new bootstrap.Modal(modalEl);
        }
        editDeptModalInstance.show();
    }

    // Edit Department Form Submit
    document.getElementById('editDepartmentForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('editDeptSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

        const formData = new FormData(this);
        formData.append('action', 'update');

        fetch('<?= BASE_URL ?>/api/departments', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Department Updated',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', res.message || 'Failed to update department.', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
            Swal.fire('Network Error', 'Could not reach server to update department.', 'error');
        });
    });

    // Delete Department Prompt
    function deleteDepartmentPrompt(target, directName) {
        let deptId = null;
        let deptName = 'this department';
        let userCount = 0;

        if (typeof target === 'object' && target !== null && target.dataset) {
            deptId = target.dataset.id;
            deptName = target.dataset.name || 'this department';
            userCount = parseInt(target.dataset.users) || 0;
        } else if (target) {
            deptId = target;
            deptName = directName || 'this department';
        }

        if (!deptId) return;

        let warningExtra = '';
        if (userCount > 0) {
            warningExtra = `<br><span class="badge bg-warning text-dark mt-2">${userCount} employee(s) will be safely reassigned to Administration (#1)</span>`;
        }

        Swal.fire({
            title: 'Delete Department?',
            html: `Are you sure you want to delete <b>${deptName}</b>?${warningExtra}`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete Department'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('department_id', deptId);
                formData.append('csrf_token', '<?= getCsrfToken() ?>');

                fetch('<?= BASE_URL ?>/api/departments', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
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
                        Swal.fire('Error', res.message || 'Failed to delete department.', 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Network Error', 'Failed to communicate with server.', 'error');
                });
            }
        });
    }
</script>
