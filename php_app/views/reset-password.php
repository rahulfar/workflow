<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/functions.php';

$token = sanitize($_GET['token'] ?? '');
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        body { 
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 1.5rem;
        }
        .glass-box { 
            width: 100%; 
            max-width: 450px; 
            background: #ffffff; 
            border: 1px solid #e2e8f0; 
            border-radius: 1.5rem; 
            padding: 2.5rem; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.06); 
        }
    </style>
</head>
<body>
<div class="glass-box text-center">
    <div class="mb-3">
        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-3 text-primary">
            <i class="fas fa-shield-alt fa-2x"></i>
        </span>
    </div>
    <h3 class="fw-bold text-dark mb-2">Set New Password</h3>
    <p class="text-secondary small mb-4">Please choose a strong, secure password for your account.</p>
    
    <form id="resetPasswordForm">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES) ?>">

        <div class="mb-3 text-start">
            <label class="form-label text-secondary small fw-semibold">New Password</label>
            <div class="input-group">
                <input type="password" name="password" id="newPassword" class="form-control" placeholder="••••••••" required minlength="6">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassVisibility('newPassword', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-4 text-start">
            <label class="form-label text-secondary small fw-semibold">Confirm Password</label>
            <div class="input-group">
                <input type="password" name="confirm_password" id="confirmPassword" class="form-control" placeholder="••••••••" required minlength="6">
                <button class="btn btn-outline-secondary" type="button" onclick="togglePassVisibility('confirmPassword', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" id="submitBtn" class="btn btn-primary w-100 fw-bold mb-3 py-2">
            <i class="fas fa-check-circle me-1"></i> Update Password
        </button>
        <a href="<?= BASE_URL ?>/login" class="text-secondary small text-decoration-none">
            <i class="fas fa-arrow-left me-1"></i> Back to Login
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
    function togglePassVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const pass = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmPassword').value;

        if (pass.length < 6) {
            Swal.fire('Password Too Short', 'Password must be at least 6 characters long.', 'warning');
            return;
        }

        if (pass !== confirm) {
            Swal.fire('Passwords Mismatch', 'New password and confirmation password do not match.', 'error');
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';

        const formData = new FormData(this);
        formData.append('action', 'reset_password');

        try {
            const res = await fetch('<?= BASE_URL ?>/api/auth', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Updated!',
                    text: data.message || 'You can now sign in with your new password.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '<?= BASE_URL ?>/login';
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to reset password.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Update Password';
            }
        } catch (err) {
            Swal.fire('Error', 'A network error occurred. Please try again.', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Update Password';
        }
    });
</script>
</body>
</html>
