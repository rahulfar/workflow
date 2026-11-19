<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    case 'forgot_password':
        handleForgotPassword();
        break;
    case 'reset_password':
        handleResetPassword();
        break;
    case 'update_profile':
        handleUpdateProfile();
        break;
    case 'change_password':
        handleChangePassword();
        break;
    default:
        jsonResponse(false, 'Invalid action', [], 400);
}

function handleLogin(): void {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, 'Method not allowed', [], 405);
    }

    $selectedRole = strtolower(trim($_POST['role'] ?? ($_POST['selected_role'] ?? '')));
    $email = sanitize($_POST['email'] ?? ($_POST['username'] ?? ''));
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verifyCsrfToken($csrfToken)) {
        jsonResponse(false, 'CSRF verification failed', [], 403);
    }

    if (empty($selectedRole) || !in_array($selectedRole, ['admin', 'manager', 'product_manager', 'employee'])) {
        jsonResponse(false, 'Please select your login type.');
    }

    if (empty($email)) {
        jsonResponse(false, 'Please enter your username.');
    }

    if (empty($password)) {
        jsonResponse(false, 'Please enter your password.');
    }

    try {
        $db = Database::getConnection();
        ensurePayrollSchema($db);

        $stmt = $db->prepare("
            SELECT u.*, r.name as role_name, r.display_name as role_title, d.name as department_name
            FROM users u
            JOIN roles r ON u.role_id = r.id
            JOIN departments d ON u.department_id = d.id
            WHERE (LOWER(u.email) = LOWER(?) OR LOWER(u.name) = LOWER(?) OR LOWER(COALESCE(u.username, '')) = LOWER(?)) AND u.status = 'active'
        ");
        $stmt->execute([$email, $email, $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            jsonResponse(false, 'Invalid username or password.');
        }

        // Verify authenticated user's actual role category matches the selected login role
        $actualCategory = getUserRoleCategory($user);

        if ($actualCategory !== $selectedRole) {
            jsonResponse(false, 'These credentials do not have access to this login type.');
        }

        // Secure session setup
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user'] = $user;
        $_SESSION['role'] = $actualCategory;
        $_SESSION['last_activity'] = time();

        logActivity('Login', 'Auth', 'User logged in as ' . strtoupper($selectedRole), (int)$user['id']);

        // Determine destination dashboard based on role
        if ($selectedRole === 'admin') {
            $redirectUrl = BASE_URL . '/admin/dashboard';
        } elseif ($selectedRole === 'manager') {
            $redirectUrl = BASE_URL . '/manager/dashboard';
        } elseif ($selectedRole === 'product_manager') {
            $redirectUrl = BASE_URL . '/product-manager/dashboard';
        } else {
            $redirectUrl = BASE_URL . '/employee/dashboard';
        }

        jsonResponse(true, 'Login successful! Redirecting...', [
            'redirect' => $redirectUrl,
            'role' => $selectedRole,
            'user_name' => $user['name']
        ]);
    } catch (Throwable $e) {
        jsonResponse(false, 'Login error: ' . $e->getMessage(), [], 500);
    }
}

function handleLogout(): void {
    if (isset($_SESSION['user_id'])) {
        logActivity('Logout', 'Auth', 'User logged out', (int)$_SESSION['user_id']);
    }
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    setcookie('remember_token', '', time() - 3600, '/');
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header('Location: ' . BASE_URL . '/login?logged_out=1');
    exit;
}

function handleForgotPassword(): void {
    $email = sanitize($_POST['email'] ?? '');
    if (empty($email)) {
        jsonResponse(false, 'Please provide your registered email address');
    }
    
    $token = bin2hex(random_bytes(16));
    try {
        $db = Database::getConnection();
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt = $db->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expiresAt]);
        
        jsonResponse(true, 'Password reset instructions sent to your email!');
    } catch (Exception $e) {
        jsonResponse(false, 'Error processing request: ' . $e->getMessage());
    }
}

function handleResetPassword(): void {
    $token = sanitize($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($token) || empty($password)) {
        jsonResponse(false, 'Token and new password required');
    }
    
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if ($reset && !empty($reset['email'])) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $uStmt = $db->prepare("UPDATE users SET password = ? WHERE email = ?");
            $uStmt->execute([$hashed, $reset['email']]);

            $del = $db->prepare("DELETE FROM password_resets WHERE token = ?");
            $del->execute([$token]);

            logActivity('Password Reset', 'Auth', "Password reset successfully for " . $reset['email']);
            jsonResponse(true, 'Password has been reset successfully! You can now login.');
        } else {
            jsonResponse(false, 'Invalid or expired password reset link. Please request a new one.');
        }
    } catch (Exception $e) {
        jsonResponse(false, 'Error resetting password: ' . $e->getMessage());
    }
}

function handleUpdateProfile(): void {
    $user = requireLogin();
    $targetId = (int)($_POST['target_user_id'] ?? $user['id']);
    $isAdmin = (strtolower($user['role_name'] ?? '') === 'admin' || (int)($user['role_id'] ?? 0) === 1);
    if ($targetId !== (int)$user['id'] && !$isAdmin) {
        $targetId = (int)$user['id'];
    }

    $name = sanitize($_POST['name'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $emergencyContact = sanitize($_POST['emergency_contact'] ?? '');

    if (empty($name)) {
        jsonResponse(false, 'Full Name is required.');
    }

    $avatarName = null;
    if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar_file']['tmp_name'];
        $fileName = $_FILES['avatar_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDir = __DIR__ . '/../public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $avatarName = 'user_' . $targetId . '_' . time() . '.' . $fileExtension;
            move_uploaded_file($fileTmpPath, $uploadDir . $avatarName);
        }
    }

    try {
        $db = Database::getConnection();
        ensureEmployeeSchema($db);

        if ($avatarName) {
            $stmt = $db->prepare("UPDATE users SET name = ?, phone = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $avatarName, $targetId]);
        } else {
            $stmt = $db->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $targetId]);
        }

        // Update employee_profiles table
        $profCheck = $db->prepare("SELECT id FROM employee_profiles WHERE user_id = ?");
        $profCheck->execute([$targetId]);
        if ($profCheck->fetch()) {
            $updProf = $db->prepare("UPDATE employee_profiles SET address = ?, emergency_contact = ? WHERE user_id = ?");
            $updProf->execute([$address, $emergencyContact, $targetId]);
        } else {
            $insProf = $db->prepare("INSERT INTO employee_profiles (user_id, joining_date, address, emergency_contact) VALUES (?, CURDATE(), ?, ?)");
            $insProf->execute([$targetId, $address, $emergencyContact]);
        }

        if ($targetId === (int)$user['id']) {
            unset($_SESSION['user']);
        }
        logActivity('Update Profile', 'Auth', "Profile updated for User #{$targetId}", (int)$user['id']);

        jsonResponse(true, 'Profile updated successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update profile: ' . $e->getMessage());
    }
}

function handleChangePassword(): void {
    $user = requireLogin();
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';

    if (empty($current) || empty($new)) {
        jsonResponse(false, 'Both current and new password are required');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $row = $stmt->fetch();

        if ($row && password_verify($current, $row['password'])) {
            $hash = password_hash($new, PASSWORD_BCRYPT);
            $update = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$hash, $user['id']]);
            jsonResponse(true, 'Password updated successfully!');
        } else {
            jsonResponse(false, 'Incorrect current password');
        }
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

