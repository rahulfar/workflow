<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/functions.php';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        body { background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .glass-box { width: 100%; max-width: 450px; background: #ffffff; border: 1px solid #e2e8f0; border-radius:1.5rem; padding:2.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
    </style>
</head>
<body>
<div class="glass-box text-center">
    <i class="fas fa-key text-warning display-4 mb-3"></i>
    <h3 class="fw-bold text-dark mb-2">Reset Password</h3>
    <p class="text-secondary small mb-4">Enter your registered email address to receive password recovery instructions.</p>
    
    <form id="forgotForm">
        <div class="mb-3 text-start">
            <label class="form-label text-secondary small">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="name@company.com" required>
        </div>
        <button type="submit" class="btn btn-warning w-100 fw-bold mb-3">Send Reset Link</button>
        <a href="<?= BASE_URL ?>/login" class="text-secondary small text-decoration-none"><i class="fas fa-arrow-left me-1"></i>Back to Login</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
    document.getElementById('forgotForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'forgot_password');
        fetch('<?= BASE_URL ?>/api/auth', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.success) Swal.fire('Check Email', res.message, 'success');
            else Swal.fire('Error', res.message, 'error');
        });
    });
</script>
</body>
</html>
