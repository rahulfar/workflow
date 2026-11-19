<?php
/**
 * Front Controller & Clean URL Router - WorkFlow Pro
 */

require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

// Parse Request URI
$rawUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestPath = parse_url($rawUri, PHP_URL_PATH) ?? '/';

// Decode both URI and Base URL path to handle '%20' and spaces identically
$decodedUri = rawurldecode($requestPath);
$decodedBasePath = rawurldecode(parse_url(BASE_URL, PHP_URL_PATH) ?? '');

// Calculate relative path from BASE_URL
if (!empty($decodedBasePath) && strpos($decodedUri, $decodedBasePath) === 0) {
    $path = substr($decodedUri, strlen($decodedBasePath));
} else {
    $path = $decodedUri;
}

// Strip leading /public, /index.php, /index repeatedly if present in request path
while (preg_match('#^/(public|index\.php|index)(/|$)#i', $path)) {
    $path = preg_replace('#^/(public|index\.php|index)(/|$)#i', '/', $path);
}
// Also strip any trailing .php extension (e.g. /login.php -> /login)
$path = preg_replace('/\.php$/i', '', $path);
$path = '/' . trim($path, '/');
if ($path === '' || $path === '//') {
    $path = '/';
}

// Fallback query routing support for environments with strict rewrite rules
if (!empty($_GET['route'])) {
    $path = '/' . trim(sanitize((string)$_GET['route']), '/');
} elseif (!empty($_GET['page'])) {
    $path = '/' . trim(sanitize((string)$_GET['page']), '/');
}

// Route Mapping with Server-Side Route Guards
switch ($path) {
    case '/':
    case '/login':
    case '/index':
    case '/index.php':
    case '/signin':
        // If visiting root '/', redirect logged-in users to their role dashboard
        if ($path === '/' && getCurrentUser()) {
            $user = getCurrentUser();
            $roleCat = getUserRoleCategory($user);
            if ($roleCat === 'admin') {
                header('Location: ' . BASE_URL . '/admin/dashboard');
            } elseif ($roleCat === 'manager') {
                header('Location: ' . BASE_URL . '/manager/dashboard');
            } elseif ($roleCat === 'product_manager') {
                header('Location: ' . BASE_URL . '/product-manager/dashboard');
            } else {
                header('Location: ' . BASE_URL . '/employee/dashboard');
            }
            exit;
        }
        require_once __DIR__ . '/../php_app/views/login.php';
        break;

    case '/logout':
    case '/signout':
    case '/sign-out':
    case '/log-out':
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
        @session_destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header('Location: ' . BASE_URL . '/login?logged_out=1');
        exit;

    case '/forgot-password':
        require_once __DIR__ . '/../php_app/views/forgot-password.php';
        break;

    case '/reset-password':
        require_once __DIR__ . '/../php_app/views/reset-password.php';
        break;

    // Universal Dashboard Router (resolves based on authenticated role)
    case '/dashboard':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/dashboard.php';
        break;

    // Role-Specific Dashboard Routes
    case '/admin':
    case '/admin/dashboard':
        requireAdmin();
        $_GET['view_as'] = 'admin';
        require_once __DIR__ . '/../php_app/views/dashboard.php';
        break;

    case '/manager':
    case '/manager/dashboard':
        requireManager();
        $_GET['view_as'] = 'manager';
        require_once __DIR__ . '/../php_app/views/dashboard.php';
        break;

    case '/product-manager':
    case '/product-manager/dashboard':
        requireProductManager();
        $_GET['view_as'] = 'product_manager';
        require_once __DIR__ . '/../php_app/views/dashboard.php';
        break;

    case '/employee':
    case '/employee/dashboard':
        requireLogin();
        $_GET['view_as'] = 'employee';
        require_once __DIR__ . '/../php_app/views/dashboard.php';
        break;

    // Dedicated Hidden Mobile Attendance Portal for Authorized Employees (Uvaish & Rahul)
    case '/mobile-shift':
    case '/mobile-attendance':
    case '/mobile_shift':
        requireAuthorizedShiftEmployee();
        require_once __DIR__ . '/../php_app/views/mobile-shift.php';
        break;


    // Core Functional Pages
    case '/tasks':
    case '/task-management':
    case '/task_management':
    case '/task':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/tasks.php';
        break;

    case '/task-details':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/task-details.php';
        break;

    case '/kanban':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/kanban.php';
        break;

    case '/work-log':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/work-log.php';
        break;

    case '/activity':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/activity.php';
        break;

    case '/notifications':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/notifications.php';
        break;

    case '/help':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/help.php';
        break;

    case '/projects':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/projects.php';
        break;

    case '/departments':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/departments.php';
        break;

    case '/emergency-approvals':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/emergency-approvals.php';
        break;

    case '/flagged-tasks':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/flagged-tasks.php';
        break;

    case '/employees':
    case '/employe':
    case '/employes':
    case '/employee-management':
    case '/staff':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/employees.php';
        break;

    case '/payroll':
        requireAdmin();
        require_once __DIR__ . '/../php_app/views/payroll.php';
        break;

    case '/attendance':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/attendance.php';
        break;

    case '/leaves':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/leaves.php';
        break;

    case '/calendar':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/calendar.php';
        break;

    case '/reports':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/reports.php';
        break;

    case '/files':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/files.php';
        break;

    case '/workday':
    case '/my-workday':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/workday.php';
        break;

    case '/live-status':
    case '/team-status':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/live-status.php';
        break;

    case '/employee-timeline':
    case '/timeline':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/employee-timeline.php';
        break;

    case '/shift-settings':
        requireAdmin();
        require_once __DIR__ . '/../php_app/views/shift-settings.php';
        break;

    case '/ip-settings':
    case '/admin/ip-settings':
    case '/ip_settings':
        requireAdmin();
        require_once __DIR__ . '/../php_app/views/ip-settings.php';
        break;

    case '/settings':
        requireAdmin();
        require_once __DIR__ . '/../php_app/views/settings.php';
        break;

    case '/profile':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/profile.php';
        break;

    case '/chat':
        requireLogin();
        require_once __DIR__ . '/../php_app/views/chat.php';
        break;

    // API Route Dispatchers
    case '/api/chat':
        require_once __DIR__ . '/../api/chat.php';
        break;
    case '/api/ai':
        require_once __DIR__ . '/../api/ai.php';
        break;
    case '/api/auth':
        require_once __DIR__ . '/../api/auth.php';
        break;
    case '/api/payroll':
        require_once __DIR__ . '/../api/payroll.php';
        break;
    case '/api/holidays':
        require_once __DIR__ . '/../api/holidays.php';
        break;
    case '/api/export':
        require_once __DIR__ . '/../api/export.php';
        break;
    case '/api/employees':
        require_once __DIR__ . '/../api/employees.php';
        break;
    case '/api/tasks':
        require_once __DIR__ . '/../api/tasks.php';
        break;
    case '/api/time_tracker':
        require_once __DIR__ . '/../api/time_tracker.php';
        break;
    case '/api/attendance':
        require_once __DIR__ . '/../api/attendance.php';
        break;
    case '/api/ip_management':
    case '/api/ip-management':
    case '/api/ip_settings':
        require_once __DIR__ . '/../api/ip_management.php';
        break;
    case '/api/leaves':
        require_once __DIR__ . '/../api/leaves.php';
        break;
    case '/api/projects':
        require_once __DIR__ . '/../api/projects.php';
        break;
    case '/api/departments':
        require_once __DIR__ . '/../api/departments.php';
        break;
    case '/api/emergency_requests':
    case '/api/emergency':
        require_once __DIR__ . '/../api/emergency_requests.php';
        break;
    case '/api/task_flags':
    case '/api/flags':
        require_once __DIR__ . '/../api/task_flags.php';
        break;
    case '/api/files':
        require_once __DIR__ . '/../api/files.php';
        break;
    case '/api/notifications':
        require_once __DIR__ . '/../api/notifications.php';
        break;
    case '/api/search':
        require_once __DIR__ . '/../api/search.php';
        break;
    case '/api/shift_settings':
        require_once __DIR__ . '/../api/shift_settings.php';
        break;
    case '/api/settings':
        require_once __DIR__ . '/../api/settings.php';
        break;
    case '/api/activities':
    case '/api/activity':
        require_once __DIR__ . '/../api/activities.php';
        break;
    case '/api/meetings':
        require_once __DIR__ . '/../api/meetings.php';
        break;
    case '/api/work_summary':
        require_once __DIR__ . '/../api/work_summary.php';
        break;
    case '/mcp':
    case '/api/mcp':
    case '/api/mcp.php':
        require_once __DIR__ . '/../api/mcp.php';
        break;

    default:
        // Prevent redirect loops if path is already login, root, or dashboard
        if ($path === '/' || $path === '/login') {
            require_once __DIR__ . '/../php_app/views/login.php';
            exit;
        }
        if ($path === '/dashboard') {
            requireLogin();
            require_once __DIR__ . '/../php_app/views/dashboard.php';
            exit;
        }

        http_response_code(404);
        // API routes must return JSON, never redirect to HTML login
        if (strpos($path, '/api/') === 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'API endpoint not found: ' . $path,
                'data' => []
            ]);
            exit;
        }

        if (getCurrentUser()) {
            header('Location: ' . BASE_URL . '/dashboard');
        } else {
            header('Location: ' . BASE_URL . '/login');
        }
        exit;
}
