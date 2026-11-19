<?php
$currentUser = requireLogin();
$db = Database::getConnection();
ensureEmployeeSchema($db);

$isAdmin = hasRole('admin');
$pageTitle = "Employee Management";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

// Fetch Departments and Roles for dropdowns
$departments = $db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();
$roles = $db->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();

// Pre-fetch all employees for server-side instant rendering
$initialEmployees = $db->query("
    SELECT u.*, r.name as role_name, r.display_name as role_title, d.name as department_name, d.code as department_code,
           ep.joining_date, ep.address, ep.emergency_contact, ep.experience, ep.skills
    FROM users u
    JOIN roles r ON u.role_id = r.id
    JOIN departments d ON u.department_id = d.id
    LEFT JOIN employee_profiles ep ON u.id = ep.user_id
    ORDER BY u.id ASC
")->fetchAll();

$totalCnt = count($initialEmployees);
$activeCnt = 0;
$inactiveCnt = 0;
$newCnt = 0;
$currentMonth = date('Y-m');

foreach ($initialEmployees as &$e) {
    if ($e['status'] === 'active') {
        $activeCnt++;
    } else {
        $inactiveCnt++;
    }
    if (!empty($e['joining_date']) && date('Y-m', strtotime($e['joining_date'])) === $currentMonth) {
        $newCnt++;
    }
    $e['employee_code'] = $e['employee_code'] ?: ('EMP-' . str_pad($e['id'], 3, '0', STR_PAD_LEFT));
    $e['shift_timing'] = $e['shift_timing'] ?: '09:40 AM - 06:30 PM (Fixed Office)';
    $e['avatar_url'] = !empty($e['avatar']) && (strpos($e['avatar'], 'http') === 0)
        ? $e['avatar']
        : (!empty($e['avatar']) && file_exists(__DIR__ . '/../../public/uploads/avatars/' . $e['avatar'])
            ? BASE_URL . '/uploads/avatars/' . $e['avatar']
            : 'https://ui-avatars.com/api/?name=' . urlencode($e['name']) . '&background=4f46e5&color=fff');
}
unset($e);
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-users-gear text-primary me-2"></i>Employee Management Directory</h3>
                <p class="text-secondary small mb-0">Manage staff profiles, departmental roles, shift timings, and security credentials.</p>
            </div>
            <?php if ($isAdmin): ?>
                <button type="button" class="btn btn-primary fw-bold px-3 py-2 shadow-sm" onclick="openAddEmployeeModal()">
                    <i class="fas fa-user-plus me-1.5"></i>Add New Employee
                </button>
            <?php endif; ?>
        </div>

        <!-- 4 Top Analytics Metric Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card">
                    <div class="stat-icon primary"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="cntTotalEmp"><?= $totalCnt ?></div>
                        <div class="stat-label">Total Employees</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card">
                    <div class="stat-icon success"><i class="fas fa-user-check"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="cntActiveEmp"><?= $activeCnt ?></div>
                        <div class="stat-label">Active Employees</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card">
                    <div class="stat-icon danger"><i class="fas fa-user-slash"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="cntInactiveEmp"><?= $inactiveCnt ?></div>
                        <div class="stat-label">Inactive Employees</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6">
                <div class="glass-card stat-card">
                    <div class="stat-icon info"><i class="fas fa-user-clock"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="cntNewEmp"><?= $newCnt ?></div>
                        <div class="stat-label">New Employees (This Month)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls Bar -->
        <div class="glass-card mb-4 p-3">
            <div class="row g-3 align-items-center">
                <div class="col-lg-4 col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="empSearchInput" class="form-control border-start-0" placeholder="Search by name, email, ID, or phone...">
                    </div>
                </div>

                <div class="col-lg-2 col-md-3">
                    <select id="filterDept" class="form-select">
                        <option value="0">All Departments</option>
                        <?php foreach ($departments as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <select id="filterRole" class="form-select">
                        <option value="0">All Roles</option>
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r['id'] ?>"><?= sanitize($r['display_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <select id="filterStatus" class="form-select">
                        <option value="">All Status</option>
                        <option value="active">Active Only</option>
                        <option value="inactive">Inactive Only</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-3">
                    <select id="filterShift" class="form-select">
                        <option value="">All Shifts</option>
                        <option value="09:40 AM - 06:30 PM (Fixed Office)">Fixed Office (09:40 - 06:30)</option>
                        <option value="09:00 AM - 06:00 PM (Regular)">Regular (09:00 - 06:00)</option>
                        <option value="10:00 AM - 07:00 PM (Late Shift)">Late Shift (10:00 - 07:00)</option>
                        <option value="08:00 PM - 05:00 AM (Night Shift)">Night Shift</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- View Display Mode & List Table -->
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list me-2 text-primary"></i>Employee Records Directory</h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="btnViewTable" onclick="toggleViewMode('table')"><i class="fas fa-table me-1"></i>Table View</button>
                    <button type="button" class="btn btn-outline-primary" id="btnViewGrid" onclick="toggleViewMode('grid')"><i class="fas fa-th-large me-1"></i>Grid Cards</button>
                </div>
            </div>

            <!-- Table View (Desktop) -->
            <div id="tableViewContainer" class="table-responsive d-none d-md-block">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Department</th>
                            <th>Role & Designation</th>
                            <th>Shift Timing</th>
                            <th>Date Joined</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="empTbody">
                        <?php foreach ($initialEmployees as $emp): ?>
                            <tr>
                                <td class="font-monospace fw-bold text-dark"><?= sanitize($emp['employee_code']) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="<?= $emp['avatar_url'] ?>" class="rounded-circle border" width="36" height="36" style="object-fit:cover;" alt="Avatar">
                                        <div>
                                            <div class="fw-semibold text-dark mb-0"><?= sanitize($emp['name']) ?></div>
                                            <small class="text-muted"><?= sanitize($emp['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= sanitize($emp['department_name']) ?></span></td>
                                <td>
                                    <div class="fw-semibold text-dark small"><?= sanitize($emp['role_title']) ?></div>
                                    <small class="text-secondary"><?= sanitize($emp['designation']) ?></small>
                                </td>
                                <td><small class="font-monospace text-secondary"><?= sanitize($emp['shift_timing']) ?></small></td>
                                <td><small class="text-secondary"><?= formatISTDate($emp['joining_date'] ?? '2024-01-01') ?></small></td>
                                <td>
                                    <?php if ($emp['status'] === 'active'): ?>
                                        <span class="badge bg-success-subtle text-success border border-success"><i class="fas fa-circle me-1 fs-8"></i>Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger border border-danger"><i class="fas fa-circle me-1 fs-8"></i>Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>/profile?id=<?= $emp['id'] ?>" class="btn btn-outline-info" title="View Full Profile"><i class="fas fa-user me-1"></i>Profile</a>
                                        <button class="btn btn-outline-secondary" onclick="viewEmployeeDetails(<?= $emp['id'] ?>)" title="Quick Details"><i class="fas fa-eye"></i></button>
                                        <?php if ($isAdmin): ?>
                                            <button class="btn btn-outline-primary" onclick="openEditEmployeeModal(<?= $emp['id'] ?>)" title="Edit Employee"><i class="fas fa-edit"></i></button>
                                            <button class="btn btn-outline-warning" onclick="openResetPasswordModal(<?= $emp['id'] ?>, '<?= sanitize($emp['name']) ?>')" title="Reset Password"><i class="fas fa-key"></i></button>
                                            <button class="btn <?= $emp['status'] === 'active' ? 'btn-outline-secondary' : 'btn-outline-success' ?>" onclick="toggleStatus(<?= $emp['id'] ?>, '<?= $emp['status'] === 'active' ? 'inactive' : 'active' ?>')" title="Toggle Status">
                                                <i class="fas <?= $emp['status'] === 'active' ? 'fa-user-slash' : 'fa-user-check' ?>"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteEmployee(<?= $emp['id'] ?>, '<?= sanitize($emp['name']) ?>')" title="Delete Employee"><i class="fas fa-trash"></i></button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Employee Cards View (< 768px) -->
            <div class="mobile-card-list d-block d-md-none">
                <?php foreach ($initialEmployees as $emp): ?>
                    <div class="mobile-data-card">
                        <div class="mobile-data-card-header">
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= $emp['avatar_url'] ?>" class="rounded-circle border" width="40" height="40" style="object-fit:cover;" alt="Avatar">
                                <div>
                                    <div class="fw-bold text-dark mb-0"><?= sanitize($emp['name']) ?></div>
                                    <small class="text-muted font-monospace fs-8"><?= sanitize($emp['employee_code']) ?></small>
                                </div>
                            </div>
                            <div>
                                <?php if ($emp['status'] === 'active'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success fs-9"><i class="fas fa-circle me-1"></i>Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger fs-9"><i class="fas fa-circle me-1"></i>Inactive</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mobile-data-card-body">
                            <div>
                                <small class="text-muted d-block fs-8">Department</small>
                                <span class="badge bg-light text-dark border fs-8"><?= sanitize($emp['department_name']) ?></span>
                            </div>
                            <div>
                                <small class="text-muted d-block fs-8">Role & Designation</small>
                                <span class="fw-semibold text-dark fs-8"><?= sanitize($emp['role_title']) ?></span>
                                <small class="text-secondary d-block fs-9"><?= sanitize($emp['designation']) ?></small>
                            </div>
                            <div>
                                <small class="text-muted d-block fs-8">Shift</small>
                                <span class="font-monospace text-secondary fs-8"><?= sanitize($emp['shift_timing']) ?></span>
                            </div>
                            <div>
                                <small class="text-muted d-block fs-8">Joined</small>
                                <span class="text-secondary fs-8"><?= formatISTDate($emp['joining_date'] ?? '2024-01-01') ?></span>
                            </div>
                        </div>
                        <div class="mobile-data-card-footer">
                            <a href="<?= BASE_URL ?>/profile?id=<?= $emp['id'] ?>" class="btn btn-sm btn-outline-info me-auto"><i class="fas fa-user me-1"></i>Profile</a>
                            <button class="btn btn-sm btn-outline-secondary" onclick="viewEmployeeDetails(<?= $emp['id'] ?>)"><i class="fas fa-eye me-1"></i>Details</button>
                            <?php if ($isAdmin): ?>
                                <button class="btn btn-sm btn-outline-primary" onclick="openEditEmployeeModal(<?= $emp['id'] ?>)"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-outline-warning" onclick="openResetPasswordModal(<?= $emp['id'] ?>, '<?= sanitize($emp['name']) ?>')"><i class="fas fa-key"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Grid Cards View -->
            <div id="gridViewContainer" class="row g-4" style="display: none;">
                <?php foreach ($initialEmployees as $emp): ?>
                    <div class="col-xl-4 col-md-6">
                        <div class="glass-card">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="<?= $emp['avatar_url'] ?>" class="rounded-circle border border-2 border-primary" width="56" height="56" style="object-fit:cover;" alt="Avatar">
                                <div>
                                    <h6 class="fw-bold text-dark mb-0"><?= sanitize($emp['name']) ?></h6>
                                    <span class="badge bg-primary-subtle text-primary fs-8 mb-1"><?= sanitize($emp['role_title']) ?></span>
                                    <div class="small text-secondary"><?= sanitize($emp['designation']) ?></div>
                                </div>
                            </div>

                            <div class="space-y-2 mb-3 small">
                                <div class="text-secondary"><i class="fas fa-id-card me-2 text-primary"></i>ID: <strong class="font-monospace text-dark"><?= sanitize($emp['employee_code']) ?></strong></div>
                                <div class="text-secondary"><i class="fas fa-building me-2 text-info"></i><?= sanitize($emp['department_name']) ?></div>
                                <div class="text-secondary"><i class="fas fa-clock me-2 text-danger"></i><?= sanitize($emp['shift_timing']) ?></div>
                                <div class="text-secondary"><i class="fas fa-envelope me-2 text-warning"></i><?= sanitize($emp['email']) ?></div>
                                <div class="text-secondary"><i class="fas fa-phone me-2 text-success"></i><?= sanitize($emp['phone'] ?: 'N/A') ?></div>
                            </div>

                            <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                <?php if ($emp['status'] === 'active'): ?>
                                    <span class="badge bg-success-subtle text-success border border-success"><i class="fas fa-circle me-1 fs-8"></i>Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger"><i class="fas fa-circle me-1 fs-8"></i>Inactive</span>
                                <?php endif; ?>
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= BASE_URL ?>/profile?id=<?= $emp['id'] ?>" class="btn btn-outline-info"><i class="fas fa-id-card me-1"></i>Profile</a>
                                    <?php if ($isAdmin): ?>
                                        <button class="btn btn-outline-primary" onclick="openEditEmployeeModal(<?= $emp['id'] ?>)"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-outline-danger" onclick="deleteEmployee(<?= $emp['id'] ?>, '<?= sanitize($emp['name']) ?>')"><i class="fas fa-trash"></i></button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 1: ADD / EDIT EMPLOYEE MODAL -->
<!-- ========================================== -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark" id="employeeModalTitle"><i class="fas fa-user-plus me-2 text-primary"></i>Add New Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="employeeForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="empFormId" value="0">
                <div class="modal-body p-4">
                    <!-- Photo Upload Preview Row -->
                    <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded border">
                        <img id="formPhotoPreview" src="https://ui-avatars.com/api/?name=New+User&background=4f46e5&color=fff" class="rounded-circle border border-primary border-2" width="70" height="70" style="object-fit:cover;" alt="Preview">
                        <div>
                            <label class="form-label text-dark fw-bold small mb-1">Profile Photo Upload</label>
                            <input type="file" name="avatar_file" id="formAvatarInput" class="form-control form-control-sm" accept="image/*">
                            <small class="text-muted fs-8">Accepted formats: JPG, PNG, WebP.</small>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="empFormName" class="form-control" placeholder="John Doe" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Employee ID (Auto-Generated) <span class="text-danger">*</span></label>
                            <input type="text" name="employee_code" id="empFormCode" class="form-control font-monospace" placeholder="EMP-001" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="empFormEmail" class="form-control" placeholder="employee@company.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Mobile Phone Number</label>
                            <input type="text" name="phone" id="empFormPhone" class="form-control" placeholder="+91 9876543210">
                        </div>

                        <div class="col-md-6" id="passwordGroup">
                            <label class="form-label text-secondary small fw-semibold">Password <span class="text-danger" id="passReqStar">*</span></label>
                            <input type="password" name="password" id="empFormPassword" class="form-control" placeholder="Minimum 6 characters">
                            <small class="text-muted fs-8" id="passHelpText" style="display:none;">Leave blank to keep existing password.</small>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-semibold mb-0">Department <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-xs btn-link p-0 text-decoration-none fw-bold text-info fs-8" id="btnToggleNewDept" onclick="toggleNewDeptSection()">
                                    <i class="fas fa-plus-circle me-1"></i>+ New Dept
                                </button>
                            </div>
                            <select name="department_id" id="empFormDept" class="form-select" required onchange="handleDeptSelectChange(this)">
                                <option value="">Select Department</option>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                                <?php endforeach; ?>
                                <option value="__new__" class="fw-bold text-info" style="background: #f0fdfa; color: #0f766e;">🏢 + Add New Department...</option>
                            </select>
                        </div>

                        <!-- Inline Quick Create Department Card -->
                        <div class="col-12" id="newDeptSection" style="display: none;">
                            <div class="p-3 rounded-3 border border-info border-opacity-50 bg-info bg-opacity-10 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom border-info border-opacity-25">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-info text-white rounded-circle p-1"><i class="fas fa-building"></i></span>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 fs-7">Add New Department</h6>
                                            <small class="text-muted fs-9">Create a department branch and immediately link it to this employee.</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close btn-sm" onclick="toggleNewDeptSection(false)" aria-label="Close"></button>
                                </div>
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-6">
                                        <label class="form-label text-secondary fs-8 fw-semibold mb-1">Department Name <span class="text-danger">*</span></label>
                                        <input type="text" id="newDeptNameInput" class="form-control form-control-sm" placeholder="e.g. Meta Ads & Google Ads" oninput="autoGenerateDeptCode(this.value)">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-secondary fs-8 fw-semibold mb-1">Code</label>
                                        <input type="text" id="newDeptCodeInput" class="form-control form-control-sm font-monospace text-uppercase" placeholder="ADS" maxlength="5">
                                    </div>
                                    <div class="col-md-3">
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary w-50" onclick="toggleNewDeptSection(false)">Cancel</button>
                                            <button type="button" class="btn btn-sm btn-info text-white fw-bold w-50" id="btnSaveNewDept" onclick="saveNewDeptAjax()"><i class="fas fa-check me-1"></i>Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Designation <span class="text-danger">*</span></label>
                            <input type="text" name="designation" id="empFormDesignation" class="form-control" placeholder="e.g. Senior Software Engineer" required>
                        </div>

                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label text-secondary small fw-semibold mb-0">System Access Role <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-xs btn-link p-0 text-decoration-none fw-bold text-primary fs-8" id="btnToggleNewRole" onclick="toggleNewRoleSection()">
                                    <i class="fas fa-shield-alt me-1"></i>+ Add New Role
                                </button>
                            </div>
                            <select name="role_id" id="empFormRole" class="form-select" required onchange="handleRoleSelectChange(this)">
                                <option value="">Select Role</option>
                                <?php foreach ($roles as $r): ?>
                                    <option value="<?= $r['id'] ?>"><?= sanitize($r['display_name']) ?></option>
                                <?php endforeach; ?>
                                <option value="__new__" class="fw-bold text-primary" style="background: #eef2ff; color: #4338ca;">✨ + Add New System Access Role...</option>
                            </select>
                        </div>

                        <!-- Inline Quick Create System Access Role Card -->
                        <div class="col-12" id="newRoleSection" style="display: none;">
                            <div class="p-3 rounded-3 border border-primary border-opacity-50 bg-primary bg-opacity-10 shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-1 border-bottom border-primary border-opacity-25">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary text-white rounded-circle p-1"><i class="fas fa-shield-alt"></i></span>
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 fs-7">Create New System Access Role</h6>
                                            <small class="text-muted fs-9">Define a new system access role and assign it to this employee.</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close btn-sm" onclick="toggleNewRoleSection(false)" aria-label="Close"></button>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-5">
                                        <label class="form-label text-secondary fs-8 fw-semibold mb-1">Role Title / Display Name <span class="text-danger">*</span></label>
                                        <input type="text" id="newRoleTitleInput" class="form-control form-control-sm" placeholder="e.g. ADS EXPERT" oninput="autoGenerateRoleSlug(this.value)">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-secondary fs-8 fw-semibold mb-1">System Slug (Key)</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text font-monospace fs-9 bg-light text-muted">@</span>
                                            <input type="text" id="newRoleSlugInput" class="form-control form-control-sm font-monospace" placeholder="ads_expert">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-secondary fs-8 fw-semibold mb-1">Access Permission Level <span class="text-danger">*</span></label>
                                        <select id="newRoleCategoryInput" class="form-select form-select-sm">
                                            <option value="employee" selected>Employee / Specialist (Standard)</option>
                                            <option value="manager">Manager (Staff & Reports)</option>
                                            <option value="product_manager">Product Manager (Sprints/Tasks)</option>
                                            <option value="admin">Administrator (Full Access)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-9">
                                        <label class="form-label text-secondary fs-8 fw-semibold mb-1">Role Description / Scope</label>
                                        <input type="text" id="newRoleDescInput" class="form-control form-control-sm" placeholder="e.g. Meta Ads & Google Ads campaign specialist and advertising operations">
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <div class="d-flex gap-2 w-100">
                                            <button type="button" class="btn btn-sm btn-outline-secondary w-50" onclick="toggleNewRoleSection(false)">Cancel</button>
                                            <button type="button" class="btn btn-sm btn-primary fw-bold w-50" id="btnSaveNewRole" onclick="saveNewRoleAjax()"><i class="fas fa-check me-1"></i>Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Shift Timing <span class="text-danger">*</span></label>
                            <select name="shift_timing" id="empFormShift" class="form-select" required>
                                <option value="09:40 AM - 06:30 PM (Fixed Office)">Fixed Office (09:40 AM - 06:30 PM IST)</option>
                                <option value="09:00 AM - 06:00 PM (Regular)">Regular Shift (09:00 AM - 06:00 PM)</option>
                                <option value="10:00 AM - 07:00 PM (Late Shift)">Late Shift (10:00 AM - 07:00 PM)</option>
                                <option value="08:00 PM - 05:00 AM (Night Shift)">Night Shift (08:00 PM - 05:00 AM)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Date of Joining <span class="text-danger">*</span></label>
                            <input type="date" name="joining_date" id="empFormJoiningDate" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Account Status <span class="text-danger">*</span></label>
                            <select name="status" id="empFormStatus" class="form-select" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Emergency Contact Number</label>
                            <input type="text" name="emergency_contact" id="empFormEmergency" class="form-control" placeholder="+91 9876500000">
                        </div>

                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold">Residential Address</label>
                            <input type="text" name="address" id="empFormAddress" class="form-control" placeholder="Enter home address">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4" id="empSubmitBtn"><i class="fas fa-check me-1"></i>Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2: RESET PASSWORD MODAL -->
<!-- ========================================== -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-key text-warning me-2"></i>Reset Employee Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resetPasswordForm">
                <input type="hidden" name="id" id="resetPassUserId" value="0">
                <div class="modal-body p-4">
                    <p class="text-secondary small mb-3">Resetting password for: <strong id="resetPassUserName" class="text-dark">--</strong></p>
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="new_password" class="form-control" required placeholder="Enter new strong password">
                    </div>
                </div>
                <div class="modal-footer border-top p-3">
                    <button type="button" class="btn btn-outline-secondary fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold text-dark"><i class="fas fa-lock me-1"></i>Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 3: VIEW DETAILS MODAL -->
<!-- ========================================== -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content glass-modal border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-id-card text-info me-2"></i>Employee Profile & Audit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="viewDetailsContent">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading details...</div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    const IS_ADMIN = <?= $isAdmin ? 'true' : 'false' ?>;
    let currentEmployees = <?= json_encode($initialEmployees) ?>;
    let currentViewMode = 'table';

    document.addEventListener('DOMContentLoaded', () => {
        // Filters Event Listeners
        document.getElementById('empSearchInput')?.addEventListener('input', debounce(loadEmployees, 300));
        document.getElementById('filterDept')?.addEventListener('change', loadEmployees);
        document.getElementById('filterRole')?.addEventListener('change', loadEmployees);
        document.getElementById('filterStatus')?.addEventListener('change', loadEmployees);
        document.getElementById('filterShift')?.addEventListener('change', loadEmployees);

        // Photo Preview Input Listener
        document.getElementById('formAvatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    document.getElementById('formPhotoPreview').src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    function debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    function toggleViewMode(mode) {
        currentViewMode = mode;
        const tbl = document.getElementById('tableViewContainer');
        const grd = document.getElementById('gridViewContainer');
        const btnTbl = document.getElementById('btnViewTable');
        const btnGrd = document.getElementById('btnViewGrid');

        if (mode === 'grid') {
            tbl.style.display = 'none';
            grd.style.display = 'flex';
            btnTbl.classList.remove('active');
            btnGrd.classList.add('active');
        } else {
            tbl.style.display = 'block';
            grd.style.display = 'none';
            btnTbl.classList.add('active');
            btnGrd.classList.remove('active');
        }
    }

    function loadEmployees() {
        const q = document.getElementById('empSearchInput')?.value || '';
        const dept = document.getElementById('filterDept')?.value || 0;
        const role = document.getElementById('filterRole')?.value || 0;
        const status = document.getElementById('filterStatus')?.value || '';
        const shift = document.getElementById('filterShift')?.value || '';

        const url = resolveApiUrl(`/api/employees?action=list&q=${encodeURIComponent(q)}&department_id=${dept}&role_id=${role}&status=${status}&shift_timing=${encodeURIComponent(shift)}`);

        fetch(url)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    currentEmployees = res.data.employees;
                    updateCounts(res.data.counts);
                    renderEmployees(currentEmployees);
                }
            })
            .catch(err => console.error("Error loading employees:", err));
    }

    function updateCounts(counts) {
        if (!counts) return;
        document.getElementById('cntTotalEmp').textContent = counts.total || 0;
        document.getElementById('cntActiveEmp').textContent = counts.active || 0;
        document.getElementById('cntInactiveEmp').textContent = counts.inactive || 0;
        document.getElementById('cntNewEmp').textContent = counts.new_this_month || 0;
    }

    function renderEmployees(list) {
        const tbody = document.getElementById('empTbody');
        const grid = document.getElementById('gridViewContainer');

        if (!list || list.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-4 text-muted">No employees found matching the filter criteria.</td></tr>`;
            grid.innerHTML = `<div class="col-12 text-center py-4 text-muted">No employees found matching the filter criteria.</div>`;
            return;
        }

        let tableHtml = '';
        let gridHtml = '';

        list.forEach(emp => {
            const empCode = emp.employee_code || ('EMP-' + String(emp.id).padStart(3, '0'));
            const avatar = emp.avatar_url;
            const shift = emp.shift_timing || '09:40 AM - 06:30 PM (Fixed Office)';
            const statusBadge = emp.status === 'active'
                ? `<span class="badge bg-success-subtle text-success border border-success"><i class="fas fa-circle me-1 fs-8"></i>Active</span>`
                : `<span class="badge bg-danger-subtle text-danger border border-danger"><i class="fas fa-circle me-1 fs-8"></i>Inactive</span>`;

            tableHtml += `
                <tr>
                    <td class="font-monospace fw-bold text-dark">${empCode}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="${avatar}" class="rounded-circle border" width="36" height="36" style="object-fit:cover;" alt="Avatar">
                            <div>
                                <div class="fw-semibold text-dark mb-0">${emp.name}</div>
                                <small class="text-muted">${emp.email}</small>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark border">${emp.department_name}</span></td>
                    <td>
                        <div class="fw-semibold text-dark small">${emp.role_title}</div>
                        <small class="text-secondary">${emp.designation}</small>
                    </td>
                    <td><small class="font-monospace text-secondary">${shift}</small></td>
                    <td><small class="text-secondary">${emp.joining_date || '2024-01-01'}</small></td>
                    <td>${statusBadge}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <a href="${BASE_URL}/profile?id=${emp.id}" class="btn btn-outline-info" title="View Full Profile"><i class="fas fa-user me-1"></i>Profile</a>
                            <button class="btn btn-outline-secondary" onclick="viewEmployeeDetails(${emp.id})" title="Quick Details"><i class="fas fa-eye"></i></button>
                            ${IS_ADMIN ? `
                                <button class="btn btn-outline-primary" onclick="openEditEmployeeModal(${emp.id})" title="Edit Employee"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-outline-warning" onclick="openResetPasswordModal(${emp.id}, '${emp.name}')" title="Reset Password"><i class="fas fa-key"></i></button>
                                <button class="btn ${emp.status === 'active' ? 'btn-outline-secondary' : 'btn-outline-success'}" onclick="toggleStatus(${emp.id}, '${emp.status === 'active' ? 'inactive' : 'active'}')" title="Toggle Status">
                                    <i class="fas ${emp.status === 'active' ? 'fa-user-slash' : 'fa-user-check'}"></i>
                                </button>
                                <button class="btn btn-outline-danger" onclick="deleteEmployee(${emp.id}, '${emp.name}')" title="Delete Employee"><i class="fas fa-trash"></i></button>
                            ` : ''}
                        </div>
                    </td>
                </tr>
            `;

            gridHtml += `
                <div class="col-xl-4 col-md-6">
                    <div class="glass-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="${avatar}" class="rounded-circle border border-2 border-primary" width="56" height="56" style="object-fit:cover;" alt="Avatar">
                            <div>
                                <h6 class="fw-bold text-dark mb-0">${emp.name}</h6>
                                <span class="badge bg-primary-subtle text-primary fs-8 mb-1">${emp.role_title}</span>
                                <div class="small text-secondary">${emp.designation}</div>
                            </div>
                        </div>

                        <div class="space-y-2 mb-3 small">
                            <div class="text-secondary"><i class="fas fa-id-card me-2 text-primary"></i>ID: <strong class="font-monospace text-dark">${empCode}</strong></div>
                            <div class="text-secondary"><i class="fas fa-building me-2 text-info"></i>${emp.department_name}</div>
                            <div class="text-secondary"><i class="fas fa-clock me-2 text-danger"></i>${shift}</div>
                            <div class="text-secondary"><i class="fas fa-envelope me-2 text-warning"></i>${emp.email}</div>
                            <div class="text-secondary"><i class="fas fa-phone me-2 text-success"></i>${emp.phone || 'N/A'}</div>
                        </div>

                        <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                            ${statusBadge}
                            <div class="btn-group btn-group-sm">
                                <a href="${BASE_URL}/profile?id=${emp.id}" class="btn btn-outline-info"><i class="fas fa-id-card me-1"></i>Profile</a>
                                ${IS_ADMIN ? `
                                    <button class="btn btn-outline-primary" onclick="openEditEmployeeModal(${emp.id})"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-outline-danger" onclick="deleteEmployee(${emp.id}, '${emp.name}')"><i class="fas fa-trash"></i></button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        tbody.innerHTML = tableHtml;
        grid.innerHTML = gridHtml;
    }

    function openAddEmployeeModal() {
        document.getElementById('employeeForm').reset();
        document.getElementById('empFormId').value = "0";
        document.getElementById('employeeModalTitle').innerHTML = `<i class="fas fa-user-plus me-2 text-primary"></i>Add New Employee`;
        document.getElementById('formPhotoPreview').src = `https://ui-avatars.com/api/?name=New+User&background=4f46e5&color=fff`;
        document.getElementById('passReqStar').style.display = 'inline';
        document.getElementById('passHelpText').style.display = 'none';
        document.getElementById('empFormPassword').required = true;

        toggleNewRoleSection(false);
        toggleNewDeptSection(false);

        fetch(resolveApiUrl('/api/employees?action=get_next_code'))
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    document.getElementById('empFormCode').value = res.data.employee_code;
                }
            });

        new bootstrap.Modal(document.getElementById('employeeModal')).show();
    }

    function openEditEmployeeModal(id) {
        const emp = currentEmployees.find(e => e.id == id);
        if (!emp) return;

        document.getElementById('employeeForm').reset();
        document.getElementById('empFormId').value = emp.id;
        document.getElementById('employeeModalTitle').innerHTML = `<i class="fas fa-user-edit me-2 text-info"></i>Edit Employee: ${emp.name}`;
        document.getElementById('formPhotoPreview').src = emp.avatar_url;

        document.getElementById('empFormName').value = emp.name;
        document.getElementById('empFormCode').value = emp.employee_code || ('EMP-' + String(emp.id).padStart(3, '0'));
        document.getElementById('empFormEmail').value = emp.email;
        document.getElementById('empFormPhone').value = emp.phone || '';
        document.getElementById('empFormDept').value = emp.department_id;
        document.getElementById('empFormDesignation').value = emp.designation;
        document.getElementById('empFormRole').value = emp.role_id;
        document.getElementById('empFormShift').value = emp.shift_timing || '09:40 AM - 06:30 PM (Fixed Office)';
        document.getElementById('empFormJoiningDate').value = emp.joining_date || '2024-01-01';
        document.getElementById('empFormStatus').value = emp.status || 'active';
        document.getElementById('empFormEmergency').value = emp.emergency_contact || '';
        document.getElementById('empFormAddress').value = emp.address || '';

        document.getElementById('passReqStar').style.display = 'none';
        document.getElementById('passHelpText').style.display = 'block';
        document.getElementById('empFormPassword').required = false;

        toggleNewRoleSection(false);
        toggleNewDeptSection(false);

        new bootstrap.Modal(document.getElementById('employeeModal')).show();
    }

    /* ========================================================
     * Dynamic System Access Role & Department Creation Handlers
     * ======================================================== */
    function toggleNewRoleSection(show) {
        const sec = document.getElementById('newRoleSection');
        if (!sec) return;
        const willShow = (typeof show === 'boolean') ? show : (sec.style.display === 'none' || sec.style.display === '');
        sec.style.display = willShow ? 'block' : 'none';
        if (willShow) {
            setTimeout(() => document.getElementById('newRoleTitleInput')?.focus(), 50);
        } else {
            const roleSelect = document.getElementById('empFormRole');
            if (roleSelect && roleSelect.value === '__new__') {
                roleSelect.value = '';
            }
        }
    }

    function handleRoleSelectChange(select) {
        if (select.value === '__new__') {
            toggleNewRoleSection(true);
        } else {
            toggleNewRoleSection(false);
        }
    }

    function autoGenerateRoleSlug(val) {
        const slug = val.toLowerCase().trim().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
        const slugInput = document.getElementById('newRoleSlugInput');
        if (slugInput) slugInput.value = slug;
    }

    function saveNewRoleAjax() {
        const titleInput = document.getElementById('newRoleTitleInput');
        const slugInput = document.getElementById('newRoleSlugInput');
        const descInput = document.getElementById('newRoleDescInput');
        const catInput = document.getElementById('newRoleCategoryInput');
        const btn = document.getElementById('btnSaveNewRole');

        const title = titleInput.value.trim();
        if (!title) {
            Swal.fire('Required Field', 'Please enter a Role Title (e.g. ADS EXPERT)', 'warning');
            titleInput.focus();
            return;
        }

        const slug = slugInput.value.trim() || title.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
        const desc = descInput.value.trim() || `${title} System Role`;
        const category = catInput.value;

        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i>Saving...`;

        const formData = new FormData();
        formData.append('action', 'add_role');
        formData.append('role_title', title);
        formData.append('display_name', title);
        formData.append('role_slug', slug);
        formData.append('name', slug);
        formData.append('description', desc);
        formData.append('category', category);
        formData.append('csrf_token', CSRF_TOKEN);

        fetch(resolveApiUrl('/api/employees'), { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-check me-1"></i>Save`;

                if (res.success && res.data && res.data.role) {
                    const newRole = res.data.role;
                    const empRoleSelect = document.getElementById('empFormRole');
                    const filterRoleSelect = document.getElementById('filterRole');

                    let opt = empRoleSelect.querySelector(`option[value="${newRole.id}"]`);
                    if (!opt) {
                        opt = document.createElement('option');
                        opt.value = newRole.id;
                        opt.textContent = newRole.display_name;
                        const newOpt = empRoleSelect.querySelector('option[value="__new__"]');
                        if (newOpt) {
                            empRoleSelect.insertBefore(opt, newOpt);
                        } else {
                            empRoleSelect.appendChild(opt);
                        }
                    }

                    if (filterRoleSelect && !filterRoleSelect.querySelector(`option[value="${newRole.id}"]`)) {
                        const fOpt = document.createElement('option');
                        fOpt.value = newRole.id;
                        fOpt.textContent = newRole.display_name;
                        filterRoleSelect.appendChild(fOpt);
                    }

                    empRoleSelect.value = newRole.id;
                    toggleNewRoleSection(false);
                    titleInput.value = '';
                    slugInput.value = '';
                    descInput.value = '';

                    const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
                    toast.fire({ icon: 'success', title: `Role "${newRole.display_name}" created & selected!` });
                } else {
                    Swal.fire('Error', res.message || 'Could not save role', 'error');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-check me-1"></i>Save`;
                Swal.fire('Network Error', err.message, 'error');
            });
    }

    function toggleNewDeptSection(show) {
        const sec = document.getElementById('newDeptSection');
        if (!sec) return;
        const willShow = (typeof show === 'boolean') ? show : (sec.style.display === 'none' || sec.style.display === '');
        sec.style.display = willShow ? 'block' : 'none';
        if (willShow) {
            setTimeout(() => document.getElementById('newDeptNameInput')?.focus(), 50);
        } else {
            const deptSelect = document.getElementById('empFormDept');
            if (deptSelect && deptSelect.value === '__new__') {
                deptSelect.value = '';
            }
        }
    }

    function handleDeptSelectChange(select) {
        if (select.value === '__new__') {
            toggleNewDeptSection(true);
        } else {
            toggleNewDeptSection(false);
        }
    }

    function autoGenerateDeptCode(val) {
        const clean = val.replace(/[^A-Za-z]/g, '');
        const codeInput = document.getElementById('newDeptCodeInput');
        if (codeInput) codeInput.value = clean.substring(0, 3).toUpperCase();
    }

    function saveNewDeptAjax() {
        const nameInput = document.getElementById('newDeptNameInput');
        const codeInput = document.getElementById('newDeptCodeInput');
        const btn = document.getElementById('btnSaveNewDept');

        const name = nameInput.value.trim();
        if (!name) {
            Swal.fire('Required Field', 'Please enter Department Name', 'warning');
            nameInput.focus();
            return;
        }

        const code = codeInput.value.trim().toUpperCase() || name.replace(/[^A-Za-z]/g, '').substring(0, 3).toUpperCase();

        btn.disabled = true;
        btn.innerHTML = `<i class="fas fa-spinner fa-spin me-1"></i>...`;

        const formData = new FormData();
        formData.append('action', 'add_department');
        formData.append('name', name);
        formData.append('department_name', name);
        formData.append('code', code);
        formData.append('description', `${name} Department`);
        formData.append('csrf_token', CSRF_TOKEN);

        fetch(resolveApiUrl('/api/employees'), { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-check me-1"></i>Save`;

                if (res.success && res.data && res.data.department) {
                    const newDept = res.data.department;
                    const empDeptSelect = document.getElementById('empFormDept');
                    const filterDeptSelect = document.getElementById('filterDept');

                    let opt = empDeptSelect.querySelector(`option[value="${newDept.id}"]`);
                    if (!opt) {
                        opt = document.createElement('option');
                        opt.value = newDept.id;
                        opt.textContent = newDept.name;
                        const newOpt = empDeptSelect.querySelector('option[value="__new__"]');
                        if (newOpt) {
                            empDeptSelect.insertBefore(opt, newOpt);
                        } else {
                            empDeptSelect.appendChild(opt);
                        }
                    }

                    if (filterDeptSelect && !filterDeptSelect.querySelector(`option[value="${newDept.id}"]`)) {
                        const fOpt = document.createElement('option');
                        fOpt.value = newDept.id;
                        fOpt.textContent = newDept.name;
                        filterDeptSelect.appendChild(fOpt);
                    }

                    empDeptSelect.value = newDept.id;
                    toggleNewDeptSection(false);
                    nameInput.value = '';
                    codeInput.value = '';

                    const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500 });
                    toast.fire({ icon: 'success', title: `Department "${newDept.name}" created & selected!` });
                } else {
                    Swal.fire('Error', res.message || 'Could not save department', 'error');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-check me-1"></i>Save`;
                Swal.fire('Network Error', err.message, 'error');
            });
    }

    document.getElementById('employeeForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('empFormId').value;
        const apiAction = (id > 0) ? 'update' : 'add';

        const formData = new FormData(this);
        formData.append('action', apiAction);
        formData.append('csrf_token', CSRF_TOKEN);

        // Include new role fields if user opened section or filled inputs
        const newRoleTitle = document.getElementById('newRoleTitleInput')?.value?.trim();
        if (newRoleTitle) {
            formData.append('new_role_title', newRoleTitle);
            formData.append('new_role_slug', document.getElementById('newRoleSlugInput')?.value?.trim() || '');
            formData.append('new_role_desc', document.getElementById('newRoleDescInput')?.value?.trim() || '');
            formData.append('new_role_category', document.getElementById('newRoleCategoryInput')?.value || 'employee');
        }

        // Include new department fields if user opened section or filled inputs
        const newDeptName = document.getElementById('newDeptNameInput')?.value?.trim();
        if (newDeptName) {
            formData.append('new_department_name', newDeptName);
            formData.append('new_department_code', document.getElementById('newDeptCodeInput')?.value?.trim() || '');
        }

        fetch(resolveApiUrl('/api/employees'), { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('employeeModal')).hide();
                    Swal.fire('Success', res.message, 'success');
                    loadEmployees();
                } else {
                    Swal.fire('Validation Error', res.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', err.message, 'error'));
    });

    function openResetPasswordModal(id, name) {
        document.getElementById('resetPassUserId').value = id;
        document.getElementById('resetPassUserName').textContent = name;
        document.getElementById('resetPasswordForm').reset();
        new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
    }

    document.getElementById('resetPasswordForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'reset_password');
        formData.append('csrf_token', CSRF_TOKEN);

        fetch(resolveApiUrl('/api/employees'), { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('resetPasswordModal')).hide();
                    Swal.fire('Success', res.message, 'success');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
    });

    function toggleStatus(id, newStatus) {
        fetch(resolveApiUrl('/api/employees'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=toggle_status&id=${id}&status=${newStatus}&csrf_token=${CSRF_TOKEN}`
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
                toast.fire({ icon: 'success', title: res.message });
                loadEmployees();
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    }

    function deleteEmployee(id, name) {
        Swal.fire({
            title: `Delete Employee ${name}?`,
            text: "This action will permanently delete the employee record and cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Yes, Delete Account'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(resolveApiUrl('/api/employees'), {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete&id=${id}&csrf_token=${CSRF_TOKEN}`
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Deleted!', res.message, 'success');
                        loadEmployees();
                    } else {
                        Swal.fire('Delete Failed', res.message, 'error');
                    }
                });
            }
        });
    }

    function viewEmployeeDetails(id) {
        const modal = new bootstrap.Modal(document.getElementById('viewDetailsModal'));
        const content = document.getElementById('viewDetailsContent');
        content.innerHTML = `<div class="text-center py-4"><i class="fas fa-spinner fa-spin me-2 fs-4 text-primary"></i>Loading employee audit bundle...</div>`;
        modal.show();

        fetch(resolveApiUrl(`/api/employees?action=details&id=${id}`))
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    const d = res.data;
                    const emp = d.employee;
                    const empCode = emp.employee_code || ('EMP-' + String(emp.id).padStart(3, '0'));

                    let html = `
                        <div class="row g-4 mb-4">
                            <div class="col-md-4 text-center">
                                <img src="${emp.avatar ? (emp.avatar.startsWith('http') ? emp.avatar : BASE_URL + '/uploads/avatars/' + emp.avatar) : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(emp.name)}" class="rounded-circle border border-primary border-3 mb-2" width="100" height="100" style="object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent(emp.name)">
                                <h5 class="fw-bold text-dark mb-0">${emp.name}</h5>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 mb-2">${emp.role_title}</span>
                                <div class="small text-secondary mb-3">${emp.designation} • ${emp.department_name}</div>
                                <div>
                                    <a href="${BASE_URL}/profile?id=${emp.id}" class="btn btn-sm btn-primary fw-bold px-3"><i class="fas fa-id-card me-1"></i>Open Full Profile</a>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row g-3 small">
                                    <div class="col-6"><i class="fas fa-id-card text-primary me-2"></i>Employee ID: <strong class="font-monospace text-dark">${empCode}</strong></div>
                                    <div class="col-6"><i class="fas fa-envelope text-warning me-2"></i>Email: <strong class="text-dark">${emp.email}</strong></div>
                                    <div class="col-6"><i class="fas fa-phone text-success me-2"></i>Phone: <strong class="text-dark">${emp.phone || 'N/A'}</strong></div>
                                    <div class="col-6"><i class="fas fa-clock text-danger me-2"></i>Shift: <strong class="text-dark">${emp.shift_timing || '09:40 AM - 06:30 PM (Fixed)'}</strong></div>
                                    <div class="col-6"><i class="fas fa-calendar text-info me-2"></i>Joined: <strong class="text-dark">${emp.joining_date || '2024-01-01'}</strong></div>
                                    <div class="col-6"><i class="fas fa-hourglass-half text-primary me-2"></i>Total Logged Hours: <strong class="text-primary font-monospace">${d.total_worked_hours}</strong></div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-secondary mb-4">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-tasks me-2 text-primary"></i>Assigned Work Tasks (${d.tasks.length})</h6>
                                <div class="list-group list-group-flush rounded border">
                                    ${d.tasks.length > 0 ? d.tasks.map(t => `
                                        <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-dark small">${t.title}</strong>
                                                <small class="d-block text-muted">Status: ${t.status}</small>
                                            </div>
                                            <span class="badge bg-light text-dark border">${t.priority}</span>
                                        </div>
                                    `).join('') : '<div class="p-3 text-muted small">No tasks assigned.</div>'}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-clock me-2 text-success"></i>Recent Attendance Records (${d.attendance.length})</h6>
                                <div class="list-group list-group-flush rounded border">
                                    ${d.attendance.length > 0 ? d.attendance.map(a => `
                                        <div class="list-group-item bg-transparent d-flex justify-content-between align-items-center small">
                                            <div>
                                                <strong class="font-monospace text-dark">${a.date}</strong>
                                                <small class="d-block text-muted">In: ${a.clock_in || '--'} | Out: ${a.clock_out || '--'}</small>
                                            </div>
                                            <span class="badge bg-success-subtle text-success">${a.status}</span>
                                        </div>
                                    `).join('') : '<div class="p-3 text-muted small">No attendance records found.</div>'}
                                </div>
                            </div>
                        </div>
                    `;
                    content.innerHTML = html;
                } else {
                    content.innerHTML = `<div class="alert alert-danger">${res.message}</div>`;
                }
            });
    }
</script>
