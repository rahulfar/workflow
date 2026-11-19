<?php
$pageTitle = "My Profile";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$currentUser = requireLogin();
$db = Database::getConnection();
ensureEmployeeSchema($db);

$targetUserId = isset($_GET['id']) ? (int)$_GET['id'] : $currentUser['id'];

// Non-admins can only view/edit their own profile
if ($targetUserId !== $currentUser['id'] && $currentUser['role_name'] !== 'admin') {
    $targetUserId = $currentUser['id'];
}

$stmt = $db->prepare("
    SELECT u.*, r.name as role_name, r.display_name as role_title, d.name as department_name, d.code as department_code,
           ep.joining_date, ep.address, ep.emergency_contact, ep.experience, ep.skills, ep.bio
    FROM users u
    JOIN roles r ON u.role_id = r.id
    JOIN departments d ON u.department_id = d.id
    LEFT JOIN employee_profiles ep ON u.id = ep.user_id
    WHERE u.id = ?
");
$stmt->execute([$targetUserId]);
$profileUser = $stmt->fetch();

if (!$profileUser) {
    header('Location: ' . BASE_URL . '/dashboard');
    exit;
}

$empCode = $profileUser['employee_code'] ?: ('EMP-' . str_pad($profileUser['id'], 3, '0', STR_PAD_LEFT));
$shiftTiming = $profileUser['shift_timing'] ?: '09:40 AM - 06:30 PM (Fixed Office)';

$avatarUrl = !empty($profileUser['avatar']) && (strpos($profileUser['avatar'], 'http') === 0)
    ? $profileUser['avatar']
    : (!empty($profileUser['avatar']) && file_exists(__DIR__ . '/../../public/uploads/avatars/' . $profileUser['avatar'])
        ? BASE_URL . '/uploads/avatars/' . $profileUser['avatar']
        : 'https://ui-avatars.com/api/?name=' . urlencode($profileUser['name']) . '&background=4f46e5&color=fff&size=128');

$isEditingSelf = ($targetUserId === $currentUser['id']);
$isAdminViewing = ($currentUser['role_name'] === 'admin');
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-user-cog text-primary me-2"></i><?= $isEditingSelf ? 'My Account Profile' : 'Employee Profile: ' . sanitize($profileUser['name']) ?></h3>
                <p class="text-secondary small mb-0">View employee details, edit contact credentials, and manage security options.</p>
            </div>
            <?php if ($isAdminViewing): ?>
                <a href="<?= BASE_URL ?>/employees" class="btn btn-outline-primary fw-semibold"><i class="fas fa-arrow-left me-1"></i>Back to Employee List</a>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <!-- Profile Overview Card -->
            <div class="col-lg-4">
                <div class="glass-card text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img id="avatarPreviewImg" src="<?= $avatarUrl ?>" class="rounded-circle border border-primary border-3 shadow-sm" width="120" height="120" style="object-fit:cover;" alt="Avatar">
                    </div>
                    
                    <h5 class="fw-bold text-dark mb-1"><?= sanitize($profileUser['name']) ?></h5>
                    <div class="mb-2">
                        <span class="badge bg-primary-subtle text-primary px-3 py-1.5"><?= sanitize($profileUser['role_title']) ?></span>
                        <span class="badge bg-light text-dark border ms-1 font-monospace"><?= sanitize($empCode) ?></span>
                    </div>

                    <div class="small text-secondary mb-3"><?= sanitize($profileUser['designation']) ?> • <?= sanitize($profileUser['department_name']) ?></div>
                    <span class="badge <?= $profileUser['status'] === 'active' ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' ?> px-3 py-1 mb-3">
                        <i class="fas fa-circle me-1 fs-8"></i><?= ucfirst($profileUser['status']) ?> Employee
                    </span>

                    <hr class="border-secondary mb-3">
                    
                    <div class="text-start small space-y-2">
                        <div class="text-secondary"><i class="fas fa-id-card me-2 text-primary"></i><strong>Employee ID:</strong> <span class="font-monospace text-dark"><?= sanitize($empCode) ?></span></div>
                        <div class="text-secondary"><i class="fas fa-envelope me-2 text-warning"></i><strong>Email:</strong> <span class="text-dark"><?= sanitize($profileUser['email']) ?></span></div>
                        <div class="text-secondary"><i class="fas fa-phone me-2 text-success"></i><strong>Phone:</strong> <span class="text-dark"><?= sanitize($profileUser['phone'] ?: 'N/A') ?></span></div>
                        <div class="text-secondary"><i class="fas fa-building me-2 text-info"></i><strong>Department:</strong> <span class="text-dark"><?= sanitize($profileUser['department_name']) ?></span></div>
                        <div class="text-secondary"><i class="fas fa-clock me-2 text-danger"></i><strong>Shift Timing:</strong> <span class="text-dark"><?= sanitize($shiftTiming) ?></span></div>
                        <div class="text-secondary"><i class="fas fa-calendar-alt me-2 text-secondary"></i><strong>Date Joined:</strong> <span class="text-dark"><?= formatISTDate($profileUser['joining_date'] ?? '2024-01-01') ?></span></div>
                        <div class="text-secondary"><i class="fas fa-map-marker-alt me-2 text-danger"></i><strong>Address:</strong> <span class="text-dark"><?= sanitize($profileUser['address'] ?: 'Not Provided') ?></span></div>
                        <div class="text-secondary"><i class="fas fa-phone-alt me-2 text-warning"></i><strong>Emergency Contact:</strong> <span class="text-dark"><?= sanitize($profileUser['emergency_contact'] ?: 'Not Provided') ?></span></div>
                    </div>
                </div>
            </div>

            <!-- Profile Forms -->
            <div class="col-lg-8">
                <!-- Editable Profile Information -->
                <div class="glass-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-user-edit me-2 text-info"></i>Edit Profile Information</h5>
                        <span class="badge bg-light text-dark border"><i class="fas fa-shield-alt text-primary me-1"></i><?= $isAdminViewing ? 'Admin Edit Mode' : 'Employee Self-Service' ?></span>
                    </div>

                    <form id="profileUpdateForm" enctype="multipart/form-data">
                        <input type="hidden" name="target_user_id" value="<?= $targetUserId ?>">
                        <!-- File Upload with Preview -->
                        <div class="mb-4 p-3 bg-light rounded border">
                            <label class="form-label text-dark fw-semibold small mb-2"><i class="fas fa-camera me-1 text-primary"></i>Profile Photo Upload</label>
                            <input type="file" name="avatar_file" id="avatarFileInput" class="form-control" accept="image/*">
                            <small class="text-muted d-block mt-1">Accepted formats: JPEG, PNG, WebP (Max 5MB).</small>
                        </div>

                        <!-- Editable Fields for Employee -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?= sanitize($profileUser['name']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Mobile Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="<?= sanitize($profileUser['phone'] ?: '') ?>" placeholder="+91 9876543210">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Emergency Contact</label>
                                <input type="text" name="emergency_contact" class="form-control" value="<?= sanitize($profileUser['emergency_contact'] ?: '') ?>" placeholder="+91 9876500000">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Residential Address</label>
                                <input type="text" name="address" class="form-control" value="<?= sanitize($profileUser['address'] ?: '') ?>" placeholder="Enter home address">
                            </div>
                        </div>

                        <!-- Non-Editable Fields for Employees -->
                        <div class="p-3 bg-light rounded border mb-4">
                            <h6 class="fw-bold text-dark small mb-3"><i class="fas fa-lock me-1 text-secondary"></i>Company Assigned Info (Non-Editable for Employees)</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label text-muted smaller mb-1">Employee ID</label>
                                    <input type="text" class="form-control bg-white font-monospace text-dark" value="<?= sanitize($empCode) ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted smaller mb-1">Company Email</label>
                                    <input type="email" class="form-control bg-white text-dark" value="<?= sanitize($profileUser['email']) ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted smaller mb-1">Department</label>
                                    <input type="text" class="form-control bg-white text-dark" value="<?= sanitize($profileUser['department_name']) ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted smaller mb-1">Designation</label>
                                    <input type="text" class="form-control bg-white text-dark" value="<?= sanitize($profileUser['designation']) ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted smaller mb-1">System Role</label>
                                    <input type="text" class="form-control bg-white text-dark" value="<?= sanitize($profileUser['role_title']) ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted smaller mb-1">Shift Timing</label>
                                    <input type="text" class="form-control bg-white text-dark" value="<?= sanitize($shiftTiming) ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-bold px-4"><i class="fas fa-save me-1"></i>Save Profile Changes</button>
                            <button type="reset" class="btn btn-outline-secondary fw-semibold">Cancel</button>
                        </div>
                    </form>
                </div>

                <!-- Password Change Box -->
                <div class="glass-card">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-lock me-2 text-danger"></i>Change Account Security Password</h5>
                    <form id="passwordChangeForm">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Current Password <span class="text-danger">*</span></label>
                                <input type="password" name="current_password" class="form-control" required placeholder="Enter current password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">New Password <span class="text-danger">*</span></label>
                                <input type="password" name="new_password" class="form-control" required placeholder="Enter new strong password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger fw-bold"><i class="fas fa-key me-1"></i>Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    // Live Image Preview
    document.getElementById('avatarFileInput')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                document.getElementById('avatarPreviewImg').src = evt.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Profile Form Submission
    document.getElementById('profileUpdateForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'update_profile');
        formData.append('csrf_token', CSRF_TOKEN);

        fetch('<?= BASE_URL ?>/api/auth', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Saved',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Update Failed', res.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', 'An error occurred: ' + err.message, 'error');
        });
    });

    // Password Form Submission
    document.getElementById('passwordChangeForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'change_password');
        formData.append('csrf_token', CSRF_TOKEN);

        fetch('<?= BASE_URL ?>/api/auth', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire('Password Changed', res.message, 'success').then(() => this.reset());
            } else {
                Swal.fire('Password Error', res.message, 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error', 'An error occurred: ' + err.message, 'error');
        });
    });
</script>
