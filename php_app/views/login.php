<?php
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/functions.php';

// Handle explicit logout or account switch requests
if (isset($_GET['logout']) || isset($_GET['logged_out']) || isset($_GET['switch']) || isset($_GET['clear'])) {
    if (session_status() === PHP_SESSION_ACTIVE) {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        @session_destroy();
    }
    // Remove remember-me cookies if present
    setcookie('remember_token', '', time() - 3600, '/');
    session_start();
}

$currentUser = getCurrentUser();
$existingRoleCat = $currentUser ? getUserRoleCategory($currentUser) : null;
$dashboardRedirectUrl = BASE_URL . '/dashboard';
if ($existingRoleCat === 'admin') {
    $dashboardRedirectUrl = BASE_URL . '/admin/dashboard';
} elseif ($existingRoleCat === 'manager') {
    $dashboardRedirectUrl = BASE_URL . '/manager/dashboard';
} elseif ($existingRoleCat === 'product_manager') {
    $dashboardRedirectUrl = BASE_URL . '/product-manager/dashboard';
} elseif ($existingRoleCat === 'employee') {
    $dashboardRedirectUrl = BASE_URL . '/employee/dashboard';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?> Smart Office Suite</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <style>
        body {
            font-family: 'Inter', 'Outfit', 'Poppins', sans-serif;
            background-color: #0F172A;
            color: #0F172A;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* 4-Color Gradient Hero Column */
        .login-hero-col {
            background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 35%, #4F46E5 70%, #06B6D4 100%);
            position: relative;
            overflow: hidden;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem;
            min-height: 100vh;
        }

        #particleCanvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }

        .hero-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.45;
            animation: floatBlob 14s infinite alternate ease-in-out;
            pointer-events: none;
        }
        .hero-blob-1 { width: 400px; height: 400px; background: #06B6D4; top: -100px; left: -100px; }
        .hero-blob-2 { width: 500px; height: 500px; background: #7C3AED; bottom: -150px; right: -150px; animation-delay: -6s; }
        .hero-blob-3 { width: 320px; height: 320px; background: #F59E0B; top: 40%; left: 35%; animation-delay: -9s; }

        @keyframes floatBlob {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(40px, 50px) scale(1.18); }
        }

        .floating-glass-card {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 1.15rem;
            padding: 1.1rem 1.35rem;
            display: flex;
            align-items: center;
            gap: 1.1rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .floating-glass-card:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-4px) scale(1.02);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.22);
        }

        .login-form-col {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2.5rem 1.5rem;
            background: radial-gradient(circle at top right, rgba(79, 70, 229, 0.06) 0%, rgba(248, 250, 252, 1) 70%);
        }

        .login-glass-card {
            width: 100%;
            max-width: 580px;
            background: #FFFFFF;
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 1.5rem;
            padding: 2.25rem;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.16), 0 4px 16px -2px rgba(0, 0, 0, 0.04);
            animation: cardFadeIn 0.5s ease-out;
            transition: all 0.3s ease;
        }

        @keyframes cardFadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 4-Role Cards Selection Grid */
        .role-cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }

        @media (max-width: 480px) {
            .role-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        .role-card-item {
            position: relative;
            background: #F8FAFC;
            border: 2px solid #E2E8F0;
            border-radius: 1rem;
            padding: 0.9rem 1rem;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            user-select: none;
        }

        .role-card-item:hover {
            border-color: #94A3B8;
            background: #F1F5F9;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        }

        .role-card-item.active {
            border-color: var(--role-color, #4F46E5);
            background: var(--role-bg, #EEF2FF);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.16);
            transform: translateY(-2px);
        }

        .role-card-item .role-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            color: #64748B;
            transition: all 0.25s ease;
        }

        .role-card-item.active .role-icon-box {
            background: var(--role-color, #4F46E5);
            color: #FFFFFF;
            border-color: var(--role-color, #4F46E5);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        .role-card-item .role-info {
            flex-grow: 1;
        }

        .role-card-item .role-title {
            font-size: 0.92rem;
            font-weight: 700;
            color: #1E293B;
            margin-bottom: 0.15rem;
            line-height: 1.2;
        }

        .role-card-item.active .role-title {
            color: var(--role-color, #4F46E5);
        }

        .role-card-item .role-desc {
            font-size: 0.75rem;
            color: #64748B;
            line-height: 1.35;
            margin-bottom: 0;
        }

        .role-check-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--role-color, #4F46E5);
            color: #FFFFFF;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
        }

        .role-card-item.active .role-check-badge {
            display: flex;
        }

        /* Specific Role Palette Variables */
        .role-card-admin {
            --role-color: #4F46E5;
            --role-bg: #EEF2FF;
        }
        .role-card-manager {
            --role-color: #0D9488;
            --role-bg: #F0FDFA;
        }
        .role-card-product {
            --role-color: #D97706;
            --role-bg: #FFFBEB;
        }
        .role-card-employee {
            --role-color: #2563EB;
            --role-bg: #EFF6FF;
        }

        /* Active Role Header Banner */
        .selected-role-banner {
            border-radius: 0.75rem;
            padding: 0.65rem 1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.85rem;
            font-weight: 600;
            animation: fadeIn 0.3s ease;
        }

        /* Floating Label Custom Inputs */
        .form-floating-custom {
            position: relative;
        }

        .form-control-custom {
            height: 50px;
            font-size: 0.95rem;
            border-radius: 0.75rem;
            border: 1.5px solid #E2E8F0;
            padding-left: 2.85rem;
            background: #FFFFFF;
            transition: all 0.25s ease;
        }

        .form-control-custom:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
            background: #FFFFFF;
        }

        .input-icon-left {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            z-index: 5;
            font-size: 1.1rem;
            transition: all 0.25s ease;
        }

        .form-floating-custom:focus-within .input-icon-left {
            color: #4F46E5;
        }

        /* Ripple Effect Animated Gradient Button */
        .btn-gradient-login {
            position: relative;
            overflow: hidden;
            height: 50px;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 50%, #06B6D4 100%);
            background-size: 200% auto;
            border: none;
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.03em;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.38);
            transition: all 0.3s ease;
        }

        .btn-gradient-login:hover {
            background-position: right center;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(124, 58, 237, 0.48);
            color: #FFFFFF;
        }

        .btn-gradient-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .role-notice-alert {
            background: #FEF3C7;
            border: 1px solid #FDE68A;
            color: #92400E;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">
        <!-- ========================================================= -->
        <!-- LEFT HERO SECTION: 4-COLOR GRADIENT & PARTICLE CANVAS     -->
        <!-- ========================================================= -->
        <div class="col-lg-6 col-xl-6 login-hero-col d-none d-lg-flex">
            <canvas id="particleCanvas"></canvas>
            
            <!-- Animated Particle Blobs -->
            <div class="hero-blob hero-blob-1"></div>
            <div class="hero-blob hero-blob-2"></div>
            <div class="hero-blob hero-blob-3"></div>

            <!-- Top Logo & Live IST Clock -->
            <div class="d-flex justify-content-between align-items-center z-3">
                <div class="d-flex align-items-center gap-2.5">
                    <img src="<?= BASE_URL ?>/assets/img/krafting-logo.webp" alt="Company Logo" style="height: 48px; object-fit: contain; filter: drop-shadow(0 2px 8px rgba(0,0,0,0.25));">
                    <span class="font-heading fs-3 fw-bold text-white tracking-wide">Work<span style="color: #06B6D4;">Flow</span></span>
                </div>
                <div class="px-3 py-1.5 rounded-pill bg-white text-dark fs-8 font-monospace border shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-clock text-warning"></i>
                    <span id="heroLiveClock" style="color: #000000; font-weight: 700;">00:00:00 (IST)</span>
                </div>
            </div>

            <!-- Hero Welcome Heading & Tagline -->
            <div class="my-auto py-5 z-3" style="max-width: 540px;">
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold mb-3 fs-8 text-uppercase tracking-wider">
                    <i class="fas fa-shield-alt me-1"></i> Enterprise RBAC Security
                </span>
                <h1 class="display-5 fw-bold text-white mb-3 line-height-1.2 font-heading">Smart Office Work Management System</h1>
                <p class="fs-6 text-white text-opacity-90 mb-4 fw-medium">Manage Teams, Tasks & Productivity Efficiently with Role-Based Access.</p>

                <!-- Floating 3D Information Cards -->
                <div class="d-flex flex-column gap-3">
                    <div class="floating-glass-card">
                        <div class="p-2.5 rounded-3 bg-white bg-opacity-20 text-white"><i class="fas fa-crown fs-5"></i></div>
                        <div>
                            <strong class="text-white d-block fs-7">Executive Admin & Operations</strong>
                            <small class="text-white text-opacity-80 fs-8">Complete organizational control, team hierarchy, and payroll oversight.</small>
                        </div>
                    </div>

                    <div class="floating-glass-card">
                        <div class="p-2.5 rounded-3 bg-white bg-opacity-20 text-white"><i class="fas fa-cubes fs-5"></i></div>
                        <div>
                            <strong class="text-white d-block fs-7">Product Roadmap & Sprint Planning</strong>
                            <small class="text-white text-opacity-80 fs-8">Dedicated product lifecycle tracking, backlog management, and release velocity.</small>
                        </div>
                    </div>

                    <div class="floating-glass-card">
                        <div class="p-2.5 rounded-3 bg-white bg-opacity-20 text-white"><i class="fas fa-user-check fs-5"></i></div>
                        <div>
                            <strong class="text-white d-block fs-7">Staff Productivity & Shift Tracking</strong>
                            <small class="text-white text-opacity-80 fs-8">Real-time attendance clock-in, daily work logs, and task timers.</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Footer -->
            <div class="z-3 pt-4 border-top border-white border-opacity-15 d-flex justify-content-between align-items-center text-white text-opacity-75 fs-8">
                <span>&copy; <?= date('Y') ?> Krafting WorkFlow Pro</span>
                <span>Version <?= APP_VERSION ?></span>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- RIGHT LOGIN CARD SECTION: 4 ROLE CATEGORIES & CREDENTIALS -->
        <!-- ========================================================= -->
        <div class="col-12 col-lg-6 col-xl-6 login-form-col">
            <div class="login-glass-card">
                <!-- Card Header -->
                <div class="text-center mb-4">
                    <img src="<?= BASE_URL ?>/assets/img/krafting-logo.webp" alt="Company Logo" style="height: 54px; object-fit: contain; margin-bottom: 0.75rem; filter: drop-shadow(0 4px 12px rgba(79,70,229,0.25));">
                    <h4 class="fw-bold text-dark mb-1 font-heading">Portal Access 🔒</h4>
                    <p class="text-secondary fs-8 mb-0">Select your account category and enter your verified credentials.</p>
                </div>

                <?php if ($currentUser): ?>
                <!-- Active Session Banner with 1-Click Dashboard or Switch -->
                <div class="alert alert-info border border-info-subtle rounded-3 p-3 mb-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-user-circle fs-4 text-info"></i>
                            <div>
                                <small class="text-secondary d-block lh-1">Currently Signed In</small>
                                <strong class="text-dark fs-7"><?= htmlspecialchars($currentUser['name'] ?? 'User') ?></strong>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-9 ms-1"><?= htmlspecialchars(strtoupper($currentUser['role'] ?? 'STAFF')) ?></span>
                            </div>
                        </div>
                        <div class="d-flex gap-1.5">
                            <a href="<?= $dashboardRedirectUrl ?>" class="btn btn-xs btn-primary rounded-pill px-3 py-1 fw-bold">
                                <i class="fas fa-tachometer-alt me-1"></i>Go to Dashboard
                            </a>
                            <a href="<?= BASE_URL ?>/login?switch=1" class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 py-1">
                                <i class="fas fa-sign-out-alt me-1"></i>Switch
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- 1. SELECT LOGIN TYPE: 4 ROLE CATEGORIES CARDS -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label text-dark fw-bold fs-8 text-uppercase tracking-wider mb-0">
                            <i class="fas fa-layer-group text-primary me-1"></i> Select Login Type <span class="text-danger">*</span>
                        </label>
                        <span id="roleSelectionBadge" class="badge bg-secondary-subtle text-secondary fs-9">Required</span>
                    </div>

                    <div class="role-cards-grid" id="roleCardsGrid">
                        <!-- Card 1: ADMIN -->
                        <div class="role-card-item role-card-admin" id="roleCardAdmin" onclick="setLoginRole('admin')">
                            <div class="role-icon-box"><i class="fas fa-crown"></i></div>
                            <div class="role-info">
                                <div class="role-title">Admin</div>
                                <p class="role-desc">Executive workspace control</p>
                            </div>
                            <div class="role-check-badge"><i class="fas fa-check"></i></div>
                        </div>

                        <!-- Card 2: MANAGER -->
                        <div class="role-card-item role-card-manager" id="roleCardManager" onclick="setLoginRole('manager')">
                            <div class="role-icon-box"><i class="fas fa-user-shield"></i></div>
                            <div class="role-info">
                                <div class="role-title">Manager</div>
                                <p class="role-desc">Team operations & workflow</p>
                            </div>
                            <div class="role-check-badge"><i class="fas fa-check"></i></div>
                        </div>

                        <!-- Card 3: PRODUCT MANAGER -->
                        <div class="role-card-item role-card-product" id="roleCardProduct" onclick="setLoginRole('product_manager')">
                            <div class="role-icon-box"><i class="fas fa-tasks"></i></div>
                            <div class="role-info">
                                <div class="role-title">Product Manager</div>
                                <p class="role-desc">Roadmaps & sprint management</p>
                            </div>
                            <div class="role-check-badge"><i class="fas fa-check"></i></div>
                        </div>

                        <!-- Card 4: EMPLOYEE -->
                        <div class="role-card-item role-card-employee" id="roleCardEmployee" onclick="setLoginRole('employee')">
                            <div class="role-icon-box"><i class="fas fa-user"></i></div>
                            <div class="role-info">
                                <div class="role-title">Employee</div>
                                <p class="role-desc">Personal tasks & shift work</p>
                            </div>
                            <div class="role-check-badge"><i class="fas fa-check"></i></div>
                        </div>
                    </div>
                </div>

                <!-- Unselected Role Prompt Notice -->
                <div id="noRoleNotice" class="role-notice-alert">
                    <i class="fas fa-info-circle fs-6"></i>
                    <span>Please choose one of the four login roles above to proceed.</span>
                </div>

                <!-- Active Role Banner (Displayed when role is clicked) -->
                <div id="activeRoleBanner" class="selected-role-banner" style="display: none;">
                    <div class="d-flex align-items-center gap-2">
                        <i id="activeBannerIcon" class="fas fa-crown"></i>
                        <span id="activeBannerTitle">LOGIN AS ADMIN</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-link p-0 text-decoration-none fs-8 fw-bold" onclick="clearRoleSelection()">Change</button>
                </div>

                <!-- 2. LOGIN FORM -->
                <form id="loginForm" autocomplete="off" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                    <input type="hidden" name="role" id="selectedRoleInput" value="">

                    <!-- Username / Email Input (EMPTY initially, NO autofill) -->
                    <div class="mb-3">
                        <label for="inputEmail" class="form-label text-dark fs-8 fw-bold text-uppercase tracking-wider">Username / Email Address</label>
                        <div class="form-floating-custom">
                            <i class="fas fa-user input-icon-left"></i>
                            <input 
                                type="text" 
                                name="email" 
                                id="inputEmail" 
                                class="form-control form-control-custom" 
                                placeholder="Enter username or email" 
                                autocomplete="off"
                                autocapitalize="off"
                                spellcheck="false"
                                required
                                value=""
                            >
                        </div>
                        <div class="invalid-feedback d-none" id="emailErrorText">Please enter your username.</div>
                    </div>

                    <!-- Password Input (EMPTY initially, NO autofill, eye toggle) -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="inputPassword" class="form-label text-dark fs-8 fw-bold text-uppercase tracking-wider mb-0">Password</label>
                            <a href="<?= BASE_URL ?>/forgot-password" class="fs-8 text-primary text-decoration-none fw-semibold">Forgot Password?</a>
                        </div>
                        <div class="form-floating-custom position-relative">
                            <i class="fas fa-lock input-icon-left"></i>
                            <input 
                                type="password" 
                                name="password" 
                                id="inputPassword" 
                                class="form-control form-control-custom pe-5" 
                                placeholder="••••••••" 
                                autocomplete="new-password"
                                required
                                value=""
                            >
                            <button 
                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-muted pe-3 text-decoration-none" 
                                type="button" 
                                id="togglePasswordBtn" 
                                title="Show/Hide Password" 
                                style="z-index: 10;"
                            >
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback d-none" id="passwordErrorText">Please enter your password.</div>
                    </div>

                    <!-- Animated Gradient Submit Button -->
                    <button type="submit" id="loginBtn" class="btn btn-gradient-login w-100 d-flex align-items-center justify-content-center mt-4">
                        <span id="loginBtnText"><i class="fas fa-sign-in-alt me-2"></i><span id="loginBtnRoleLabel">Sign In</span></span>
                        <span id="loginBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </form>

                <!-- Footer with Policy Links & Modals -->
                <div class="text-center mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-center gap-3 fs-8 text-muted mb-1">
                        <a href="#" class="text-secondary text-decoration-none" data-bs-toggle="modal" data-bs-target="#privacyModal">Privacy Policy</a>
                        <span>•</span>
                        <a href="#" class="text-secondary text-decoration-none" data-bs-toggle="modal" data-bs-target="#termsModal">Terms & Conditions</a>
                    </div>
                    <small class="text-muted fs-8">&copy; <?= date('Y') ?> WorkFlow Pro Suite • Version <?= APP_VERSION ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-user-shield me-2 text-primary"></i>Privacy Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fs-7 text-secondary">
                <p>WorkFlow Pro Suite is committed to protecting your organizational data and privacy.</p>
                <ul class="mb-0">
                    <li>All employee activities and attendance logs are strictly encrypted.</li>
                    <li>Session tokens are protected with industry-standard CSRF prevention.</li>
                    <li>Data access is governed by strict server-side role-based access control (RBAC).</li>
                </ul>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Terms & Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-file-contract me-2 text-info"></i>Terms & Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body fs-7 text-secondary">
                <p>By logging in to WorkFlow Pro Suite, you agree to comply with organizational IT security guidelines.</p>
                <ul class="mb-0">
                    <li>Do not share your login credentials or password with unauthorized personnel.</li>
                    <li>All task updates and shift time logs are subject to administrative audit.</li>
                </ul>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
    // State: Currently selected role (null by default)
    let currentSelectedRole = null;

    const roleConfig = {
        'admin': {
            title: 'ADMIN',
            bannerTitle: 'LOGIN AS ADMIN',
            buttonText: 'LOGIN AS ADMIN',
            icon: 'fa-crown',
            color: '#4F46E5',
            bg: '#EEF2FF'
        },
        'manager': {
            title: 'MANAGER',
            bannerTitle: 'LOGIN AS MANAGER',
            buttonText: 'LOGIN AS MANAGER',
            icon: 'fa-user-shield',
            color: '#0D9488',
            bg: '#F0FDFA'
        },
        'product_manager': {
            title: 'PRODUCT MANAGER',
            bannerTitle: 'LOGIN AS PRODUCT MANAGER',
            buttonText: 'LOGIN AS PRODUCT MANAGER',
            icon: 'fa-tasks',
            color: '#D97706',
            bg: '#FFFBEB'
        },
        'employee': {
            title: 'EMPLOYEE',
            bannerTitle: 'LOGIN AS EMPLOYEE',
            buttonText: 'LOGIN AS EMPLOYEE',
            icon: 'fa-user',
            color: '#2563EB',
            bg: '#EFF6FF'
        }
    };

    // Role Selection Handler
    function setLoginRole(roleKey) {
        currentSelectedRole = roleKey;
        document.getElementById('selectedRoleInput').value = roleKey;

        // Update card active states
        document.querySelectorAll('.role-card-item').forEach(card => card.classList.remove('active'));
        
        const cardIdMap = {
            'admin': 'roleCardAdmin',
            'manager': 'roleCardManager',
            'product_manager': 'roleCardProduct',
            'employee': 'roleCardEmployee'
        };

        const activeCard = document.getElementById(cardIdMap[roleKey]);
        if (activeCard) {
            activeCard.classList.add('active');
        }

        // Update UI banner & labels
        const config = roleConfig[roleKey];
        if (config) {
            const banner = document.getElementById('activeRoleBanner');
            const notice = document.getElementById('noRoleNotice');
            const bannerIcon = document.getElementById('activeBannerIcon');
            const bannerTitle = document.getElementById('activeBannerTitle');
            const btnLabel = document.getElementById('loginBtnRoleLabel');
            const badge = document.getElementById('roleSelectionBadge');

            banner.style.display = 'flex';
            banner.style.background = config.bg;
            banner.style.color = config.color;
            banner.style.border = `1px solid ${config.color}30`;
            bannerIcon.className = `fas ${config.icon} me-1`;
            bannerTitle.textContent = config.bannerTitle;

            notice.style.display = 'none';
            btnLabel.textContent = config.buttonText;

            badge.className = 'badge bg-success-subtle text-success fs-9';
            badge.textContent = `${config.title} Selected`;
        }

        // Hide any previous validation errors
        hideFieldErrors();
    }

    function clearRoleSelection() {
        currentSelectedRole = null;
        document.getElementById('selectedRoleInput').value = '';
        document.querySelectorAll('.role-card-item').forEach(card => card.classList.remove('active'));

        document.getElementById('activeRoleBanner').style.display = 'none';
        document.getElementById('noRoleNotice').style.display = 'flex';
        document.getElementById('loginBtnRoleLabel').textContent = 'Sign In';

        const badge = document.getElementById('roleSelectionBadge');
        badge.className = 'badge bg-secondary-subtle text-secondary fs-9';
        badge.textContent = 'Required';
    }

    // Password Visibility Toggle
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        const passwordInput = document.getElementById('inputPassword');
        const icon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            icon.className = 'fas fa-eye';
        }
    });

    function showFieldError(inputId, errorId, message) {
        const input = document.getElementById(inputId);
        const err = document.getElementById(errorId);
        if (input && err) {
            input.classList.add('is-invalid');
            err.textContent = message;
            err.classList.remove('d-none');
        }
    }

    function hideFieldErrors() {
        ['inputEmail', 'inputPassword'].forEach(id => {
            const input = document.getElementById(id);
            if (input) input.classList.remove('is-invalid');
        });
        ['emailErrorText', 'passwordErrorText'].forEach(id => {
            const err = document.getElementById(id);
            if (err) err.classList.add('d-none');
        });
    }

    // Form Submission with Strict RBAC Verification
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        hideFieldErrors();

        const role = document.getElementById('selectedRoleInput').value.trim();
        const email = document.getElementById('inputEmail').value.trim();
        const password = document.getElementById('inputPassword').value;

        // 1. Validate role selected
        if (!role) {
            Swal.fire({
                icon: 'warning',
                title: 'Selection Required',
                text: 'Please select your login type.',
                confirmButtonColor: '#4F46E5'
            });
            return;
        }

        // 2. Validate email / username
        if (!email) {
            showFieldError('inputEmail', 'emailErrorText', 'Please enter your username.');
            document.getElementById('inputEmail').focus();
            return;
        }

        // 3. Validate password
        if (!password) {
            showFieldError('inputPassword', 'passwordErrorText', 'Please enter your password.');
            document.getElementById('inputPassword').focus();
            return;
        }

        const btnText = document.getElementById('loginBtnText');
        const spinner = document.getElementById('loginBtnSpinner');
        const submitBtn = document.getElementById('loginBtn');

        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');
        submitBtn.disabled = true;

        const formData = new FormData(this);
        formData.append('action', 'login');

        // Resolve API URL: robust detection for root or subfolder deployments
        const phpBase = '<?= rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/') ?>';
        let detectedBase = phpBase;
        try {
            const cleanPath = window.location.pathname.replace(/\/+$/, '');
            const pathParts = cleanPath ? cleanPath.split('/') : [];
            const last = pathParts.length > 0 ? pathParts[pathParts.length - 1].toLowerCase() : '';
            if (['login', 'login.php', 'index.php', 'signin'].includes(last)) {
                pathParts.pop();
                detectedBase = pathParts.join('/');
            }
        } catch(e) {}

        const normalizedBase = (detectedBase || phpBase || '').replace(/\/+$/, '');
        const endpointsToTry = [
            `${normalizedBase}/api/auth.php`,
            `${normalizedBase}/api/auth`,
            '/api/auth.php',
            '/api/auth',
            'api/auth.php'
        ];
        // Deduplicate endpoints
        const authEndpoints = [...new Set(endpointsToTry.filter(Boolean))];

        async function submitAuthRequest() {
            let lastStatus = 0;
            let lastErr = null;

            for (const endpoint of authEndpoints) {
                try {
                    const res = await fetch(endpoint, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    lastStatus = res.status;
                    const rawText = await res.text();
                    try {
                        const json = JSON.parse(rawText);
                        return json;
                    } catch (parseErr) {
                        // If not valid JSON, proceed to next endpoint candidate
                        console.warn(`Non-JSON response from ${endpoint} (${res.status}):`, rawText.substring(0, 150));
                    }
                } catch (netErr) {
                    lastErr = netErr;
                }
            }

            throw new Error(lastStatus === 404 ? 'Login endpoint not found on server (404).' : (lastStatus ? `Server error (${lastStatus})` : 'Network connection failed. Please verify server is reachable.'));
        }

        submitAuthRequest()
        .then(res => {
            spinner.classList.add('d-none');
            btnText.classList.remove('d-none');
            submitBtn.disabled = false;

            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Welcome Back!',
                    text: res.message || 'Login successful! Redirecting...',
                    timer: 800,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = res.data.redirect;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Authentication Failed',
                    text: res.message || 'Invalid username or password.',
                    confirmButtonColor: '#4F46E5'
                });
            }
        })
        .catch(err => {
            spinner.classList.add('d-none');
            btnText.classList.remove('d-none');
            submitBtn.disabled = false;
            console.error('Login request failed:', err);
            Swal.fire({
                icon: 'error',
                title: 'Connection Error',
                text: err.message || 'Could not connect to the server. Please check your connection and try again.',
                confirmButtonColor: '#4F46E5'
            });
        });
    });

    // Particle Canvas Animation
    (function() {
        const canvas = document.getElementById('particleCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let width = canvas.width = canvas.parentElement.clientWidth;
        let height = canvas.height = canvas.parentElement.clientHeight;

        window.addEventListener('resize', () => {
            if (!canvas.parentElement) return;
            width = canvas.width = canvas.parentElement.clientWidth;
            height = canvas.height = canvas.parentElement.clientHeight;
        });

        const particles = Array.from({ length: 30 }, () => ({
            x: Math.random() * width,
            y: Math.random() * height,
            radius: Math.random() * 2 + 1,
            dx: (Math.random() - 0.5) * 0.5,
            dy: (Math.random() - 0.5) * 0.5,
            alpha: Math.random() * 0.5 + 0.2
        }));

        function animate() {
            ctx.clearRect(0, 0, width, height);
            particles.forEach(p => {
                p.x += p.dx;
                p.y += p.dy;
                if (p.x < 0) p.x = width;
                if (p.x > width) p.x = 0;
                if (p.y < 0) p.y = height;
                if (p.y > height) p.y = 0;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 255, 255, ${p.alpha})`;
                ctx.fill();
            });
            requestAnimationFrame(animate);
        }
        animate();
    })();

    // Live IST Clock
    function updateHeroClock() {
        const el = document.getElementById('heroLiveClock');
        if (!el) return;
        const now = new Date();
        const formatted = new Intl.DateTimeFormat('en-US', {
            timeZone: 'Asia/Kolkata',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        }).format(now);
        el.textContent = `${formatted} (IST)`;
    }
    updateHeroClock();
    setInterval(updateHeroClock, 1000);
</script>
</body>
</html>
