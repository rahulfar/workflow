<?php
/**
 * Global Application Helper Functions
 */

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';

// Polyfills for PHP < 8.0 compatibility
if (!function_exists('str_contains')) {
    function str_contains(string $haystack, string $needle): bool {
        return $needle === '' || strpos($haystack, $needle) !== false;
    }
}
if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool {
        return $needle === '' || strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}
if (!function_exists('str_ends_with')) {
    function str_ends_with(string $haystack, string $needle): bool {
        return $needle === '' || $needle === substr($haystack, -strlen($needle));
    }
}

function getCurrentUser(): ?array {
    checkSessionTimeout();
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
        return $_SESSION['user'];
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT u.*, r.name as role_name, r.display_name as role_title, d.name as department_name, d.code as department_code
            FROM users u
            JOIN roles r ON u.role_id = r.id
            JOIN departments d ON u.department_id = d.id
            WHERE u.id = ? AND u.status = 'active'
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user) {
            $_SESSION['user'] = $user;
            return $user;
        }
    } catch (Exception $e) {
        error_log("getCurrentUser Error: " . $e->getMessage());
    }
    return null;
}

function requireLogin(): array {
    $user = getCurrentUser();
    if (!$user) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            jsonResponse(false, 'Unauthorized. Please login.', [], 401);
        } else {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }
    return $user;
}

function getUserRoleCategory(?array $user): string {
    if (!$user) return 'employee';
    $roleName = strtolower($user['role_name'] ?? '');
    $roleId = (int)($user['role_id'] ?? 0);

    if ($roleName === 'admin' || $roleId === 1 || strpos($roleName, 'admin') !== false) {
        return 'admin';
    }
    if ($roleName === 'product_manager' || $roleId === 3 || strpos($roleName, 'product') !== false) {
        return 'product_manager';
    }
    // Only General Manager (Role 2 or exact role 'manager') belongs to the 'manager' login category
    // Department managers/leads (e.g. SEO Manager Uvaish, Social Media Manager Priyansh) log in and operate as 'employee'
    if (($roleName === 'manager' || $roleId === 2) && $roleName !== 'seo_manager' && $roleName !== 'social_media_manager') {
        return 'manager';
    }
    return 'employee';
}

function hasRole($roleNames): bool {
    $user = getCurrentUser();
    if (!$user) return false;
    
    if (is_string($roleNames)) {
        $roleNames = [$roleNames];
    }
    
    $roleName = strtolower($user['role_name'] ?? '');
    $roleId = (int)($user['role_id'] ?? 0);
    $category = getUserRoleCategory($user);

    if ($roleName === 'admin' || $roleId === 1 || $category === 'admin') {
        return true;
    }

    $normalizedRoles = array_map('strtolower', $roleNames);
    if (in_array($category, $normalizedRoles)) {
        return true;
    }
    
    return in_array($roleName, $normalizedRoles);
}

function requireRole($roleCategories): array {
    $user = requireLogin();
    $userCategory = getUserRoleCategory($user);
    
    if (is_string($roleCategories)) {
        $roleCategories = [$roleCategories];
    }
    
    $normalized = array_map('strtolower', $roleCategories);
    
    // Admin always has access to all roles/dashboards
    if ($userCategory === 'admin') {
        return $user;
    }
    
    if (!in_array($userCategory, $normalized)) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            jsonResponse(false, 'Access denied. You do not have permission to access this resource.', [], 403);
        } else {
            http_response_code(403);
            header('Location: ' . BASE_URL . '/dashboard?access_denied=1');
            exit;
        }
    }
    return $user;
}

function requireManager(): array {
    return requireRole(['admin', 'manager']);
}

function requireProductManager(): array {
    return requireRole(['admin', 'product_manager']);
}

function canCreateOrEditTasks(?array $user = null): bool {
    if (!$user) {
        $user = getCurrentUser();
    }
    if (!$user) return false;

    $roleName = strtolower($user['role_name'] ?? '');
    $roleId = (int)($user['role_id'] ?? 0);
    $userId = (int)($user['id'] ?? 0);
    $userName = strtolower(trim($user['name'] ?? ''));
    $category = getUserRoleCategory($user);

    // 1. Admin, General Manager, Product Manager can give tasks to all employees
    if (in_array($category, ['admin', 'manager', 'product_manager']) || 
        in_array($roleName, ['admin', 'manager', 'product_manager']) || 
        in_array($roleId, [1, 2, 3])) {
        return true;
    }
    // 2. SEO Lead Manager (Uvaish: ID 5 or seo_manager) can give tasks to Aman and Shivanshu
    if ($userId === 5 || $userName === 'uvaish' || $roleName === 'seo_manager' || $roleId === 5) {
        return true;
    }
    // 3. Senior Fullstack Developer (Rahul: ID 4 or senior_developer) can give tasks to Abhay
    if ($userId === 4 || $userName === 'rahul' || $roleName === 'senior_developer' || $roleId === 4) {
        return true;
    }

    return false;
}

function canManageProjects(?array $user = null): bool {
    return canCreateOrEditTasks($user);
}

/**
 * Specifically authorized employees for Mobile Shift In / Shift Out Portal.
 * Authorized employees: Uvaish (ID 5) and Rahul (ID 4) + Admin.
 */
function isAuthorizedShiftEmployee(?array $user = null): bool {
    if (!$user) {
        $user = getCurrentUser();
    }
    if (!$user || empty($user['id'])) {
        return false;
    }

    $userId = (int)$user['id'];
    $userName = strtolower(trim($user['name'] ?? ''));
    $userEmail = strtolower(trim($user['email'] ?? ''));
    $roleName = strtolower(trim($user['role_name'] ?? ''));

    // Uvaish: ID 5, SEO Lead Manager, uvaish@kraftingdigital.com
    if ($userId === 5 || $userName === 'uvaish' || str_contains($userName, 'uvaish') || $userEmail === 'uvaish@kraftingdigital.com') {
        return true;
    }

    // Rahul: ID 4, Senior Fullstack Developer, finance@kraftingdigital.com
    if ($userId === 4 || $userName === 'rahul' || str_contains($userName, 'rahul') || $userEmail === 'finance@kraftingdigital.com') {
        return true;
    }

    // Administrator / CEO (Kartikeyan ID 1) for management & audit testing
    if ($userId === 1 || hasRole('admin') || $roleName === 'admin') {
        return true;
    }

    return false;
}

/**
 * Detect whether the current HTTP request originates from a mobile device or mobile parameter
 */
function isMobileRequest(): bool {
    // 1. Explicit request parameter
    if (!empty($_REQUEST['source']) && strtolower($_REQUEST['source']) === 'mobile') {
        return true;
    }
    if (!empty($_REQUEST['is_mobile']) && ($_REQUEST['is_mobile'] == '1' || $_REQUEST['is_mobile'] === 'true')) {
        return true;
    }
    
    // 2. HTTP User-Agent detection
    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
    if (empty($userAgent)) {
        return false;
    }
    
    $mobileKeywords = ['mobile', 'android', 'iphone', 'ipod', 'ipad', 'blackberry', 'webos', 'windows phone', 'opera mini', 'iemobile'];
    foreach ($mobileKeywords as $keyword) {
        if (str_contains($userAgent, $keyword)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Server-side route guard for the Hidden Mobile Shift Attendance page.
 * Blocks unauthorized users with 403 Forbidden.
 */
function requireAuthorizedShiftEmployee(): array {
    $user = requireLogin();
    if (!isAuthorizedShiftEmployee($user)) {
        http_response_code(403);
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            jsonResponse(false, 'Access denied. You are not authorized for mobile shift attendance.', [], 403);
        }
        
        $pageTitle = "Access Denied";
        echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - WorkFlow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: system-ui, -apple-system, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1.5rem; margin: 0; }
        .denied-card { background: rgba(30, 41, 59, 0.95); backdrop-filter: blur(12px); border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 1.5rem; padding: 2.25rem 1.75rem; max-width: 420px; width: 100%; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
        .icon-circle { width: 72px; height: 72px; background: rgba(239, 68, 68, 0.15); color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem; }
    </style>
</head>
<body>
    <div class="denied-card">
        <div class="icon-circle"><i class="fas fa-lock"></i></div>
        <h4 class="fw-bold mb-2 text-white">Access Denied</h4>
        <p class="small mb-3" style="color: #94a3b8;">This hidden mobile shift portal is restricted to authorized employees only. Your account (<strong>' . htmlspecialchars($user['name'] ?? 'User') . '</strong>) does not have authorization to access this page.</p>
        <div class="p-2 bg-dark rounded-3 border border-secondary mb-3 small text-muted font-monospace" style="font-size: 0.75rem;">
            HTTP 403 Forbidden &bull; Restricted Mobile Route
        </div>
        <a href="' . BASE_URL . '/dashboard" class="btn btn-primary w-100 py-2.5 rounded-pill fw-bold shadow-sm">
            <i class="fas fa-arrow-left me-1"></i>Return to Dashboard
        </a>
    </div>
</body>
</html>';
        exit;
    }
    return $user;
}


/**
 * Check whether a user is authorized to assign a task to a specific employee
 */
function isAssigneeAllowedForUser(array $assignerUser, int $assigneeId, ?PDO $db = null): bool {
    $category = getUserRoleCategory($assignerUser);
    $roleName = strtolower($assignerUser['role_name'] ?? '');
    $roleId = (int)($assignerUser['role_id'] ?? 0);
    $userId = (int)($assignerUser['id'] ?? 0);
    $userName = strtolower(trim($assignerUser['name'] ?? ''));

    // Admin, Manager, Product Manager can assign to all active employees
    if (in_array($category, ['admin', 'manager', 'product_manager']) || 
        in_array($roleName, ['admin', 'manager', 'product_manager']) || 
        in_array($roleId, [1, 2, 3])) {
        return true;
    }

    // SEO Lead Manager (Uvaish) can ONLY assign tasks to Aman (10) and Shivanshu (6)
    if ($userId === 5 || $userName === 'uvaish' || $roleName === 'seo_manager' || $roleId === 5) {
        return in_array($assigneeId, [10, 6]);
    }

    // Senior Fullstack Developer (Rahul) can ONLY assign tasks to Abhay (9)
    if ($userId === 4 || $userName === 'rahul' || $roleName === 'senior_developer' || $roleId === 4) {
        return ($assigneeId === 9);
    }

    return false;
}

/**
 * Get List of Assignable Employees according to company hierarchy rules:
 * - Admin, Manager, Product Manager: ALL active employees
 * - SEO Lead Manager (Uvaish): ONLY Intern SEO Analyst (Aman, ID 10) & Senior SEO Executive (Shivanshu, ID 6)
 * - Senior Fullstack Developer (Rahul): ONLY Intern Frontend Developer (Abhay, ID 9)
 * - Other staff: None
 */
function getAssignableEmployeesForUser(?array $user = null, ?PDO $db = null): array {
    if (!$user) $user = getCurrentUser();
    if (!$user) return [];
    if (!$db) $db = Database::getConnection();

    $category = getUserRoleCategory($user);
    $roleName = strtolower($user['role_name'] ?? '');
    $roleId = (int)($user['role_id'] ?? 0);
    $userId = (int)($user['id'] ?? 0);
    $userName = strtolower(trim($user['name'] ?? ''));

    try {
        // 1. Global Admin, Manager, Product Manager can assign to all active staff (functionality preserved without changes)
        if (in_array($category, ['admin', 'manager', 'product_manager']) || 
            in_array($roleName, ['admin', 'manager', 'product_manager']) || 
            in_array($roleId, [1, 2, 3])) {
            return $db->query("
                SELECT u.id, u.name, u.designation, u.department_id, d.name as dept_name, 1 as is_my_dept
                FROM users u
                LEFT JOIN departments d ON u.department_id = d.id
                WHERE u.status = 'active'
                ORDER BY u.department_id ASC, u.name ASC
            ")->fetchAll() ?: [];
        }

        // 2. SEO Lead Manager (Uvaish): can ONLY give tasks to Aman (ID 10) and Shivanshu (ID 6)
        if ($userId === 5 || $userName === 'uvaish' || $roleName === 'seo_manager' || $roleId === 5) {
            $stmt = $db->query("
                SELECT u.id, u.name, u.designation, u.department_id, d.name as dept_name, 1 as is_my_dept
                FROM users u
                LEFT JOIN departments d ON u.department_id = d.id
                WHERE u.status = 'active' AND u.id IN (10, 6)
                ORDER BY (CASE WHEN u.id = 10 THEN 0 ELSE 1 END) ASC, u.name ASC
            ");
            return $stmt ? ($stmt->fetchAll() ?: []) : [];
        }

        // 3. Senior Fullstack Developer (Rahul): can ONLY give tasks to Abhay (ID 9)
        if ($userId === 4 || $userName === 'rahul' || $roleName === 'senior_developer' || $roleId === 4) {
            $stmt = $db->query("
                SELECT u.id, u.name, u.designation, u.department_id, d.name as dept_name, 1 as is_my_dept
                FROM users u
                LEFT JOIN departments d ON u.department_id = d.id
                WHERE u.status = 'active' AND u.id = 9
                ORDER BY u.name ASC
            ");
            return $stmt ? ($stmt->fetchAll() ?: []) : [];
        }

        return [];
    } catch (Throwable $e) {
        error_log("getAssignableEmployeesForUser notice: " . $e->getMessage());
        return [];
    }
}

/**
 * Get SEO Department Team Members (Aman, Shivanshu, Uvaish)
 */
function getSeoDepartmentTeam(?PDO $db = null): array {
    if (!$db) $db = Database::getConnection();
    try {
        $stmt = $db->query("
            SELECT u.id, u.name, u.email, u.phone, u.designation, u.avatar, r.name as role_name, r.display_name as role_title,
                   (SELECT COUNT(*) FROM tasks WHERE assigned_to = u.id AND status NOT IN ('completed', 'done')) as active_tasks_count
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.department_id = 3 AND u.status = 'active'
            ORDER BY (CASE WHEN u.id = 10 THEN 0 WHEN u.id = 6 THEN 1 WHEN u.id = 5 THEN 2 ELSE 3 END) ASC, u.name ASC
        ");
        return $stmt->fetchAll() ?: [];
    } catch (Throwable $e) {
        error_log("getSeoDepartmentTeam notice: " . $e->getMessage());
        return [];
    }
}

/**
 * Get Development Department Team Members (Abhay, Rahul)
 */
function getDevDepartmentTeam(?PDO $db = null): array {
    if (!$db) $db = Database::getConnection();
    try {
        $stmt = $db->query("
            SELECT u.id, u.name, u.email, u.phone, u.designation, u.avatar, r.name as role_name, r.display_name as role_title,
                   (SELECT COUNT(*) FROM tasks WHERE assigned_to = u.id AND status NOT IN ('completed', 'done')) as active_tasks_count
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE u.department_id = 2 AND u.status = 'active'
            ORDER BY (CASE WHEN u.id = 9 THEN 0 WHEN u.id = 4 THEN 1 ELSE 2 END) ASC, u.name ASC
        ");
        return $stmt->fetchAll() ?: [];
    } catch (Throwable $e) {
        error_log("getDevDepartmentTeam notice: " . $e->getMessage());
        return [];
    }
}

/**
 * Get the list of employee user IDs whose tasks should be visible to the user.
 * 
 * Hierarchy & Visibility Rules:
 * - Admin (Kartikeyan, ID 1), General Manager (Mrinali, ID 2), Product Manager (Udit, ID 3):
 *   Returns null (unrestricted, view all tasks across the company).
 * - SEO Lead Manager (Uvaish, ID 5):
 *   Returns [5, 10, 6] (Uvaish, Aman, Shivanshu - SEO department tasks only).
 * - Dev Lead / Senior Fullstack Developer (Rahul, ID 4):
 *   Returns [4, 9] (Rahul and Abhay - Development department tasks only).
 * - Intern SEO Analyst (Aman, ID 10):
 *   Returns [10] (Only Aman's tasks, never other employees' tasks).
 * - Intern Frontend Developer (Abhay, ID 9):
 *   Returns [9] (Only Abhay's tasks, never other employees' tasks).
 * - Senior SEO Executive (Shivanshu, ID 6):
 *   Returns [6] (Only Shivanshu's tasks).
 * - All other employees (Sarthak ID 7, Priyansh ID 8, etc.):
 *   Returns [$userId] (Only their own tasks).
 * 
 * @param array|null $user The user record, defaults to getCurrentUser()
 * @return int[]|null Array of allowed assignee IDs, or null if unrestricted
 */
function getVisibleEmployeeIdsForUser(?array $user = null): ?array {
    if (!$user) {
        $user = getCurrentUser();
    }
    if (!$user) {
        return [];
    }

    $userId = (int)($user['id'] ?? 0);
    $userName = strtolower(trim($user['name'] ?? ''));
    $roleName = strtolower(trim($user['role_name'] ?? ''));
    $roleId = (int)($user['role_id'] ?? 0);
    $category = getUserRoleCategory($user);

    // 1. Global Management (Admin, General Manager, Product Manager) - Unrestricted
    if (in_array($category, ['admin', 'manager', 'product_manager']) || 
        in_array($roleName, ['admin', 'manager', 'product_manager']) || 
        in_array($roleId, [1, 2, 3]) ||
        in_array($userId, [1, 2, 3])) {
        return null;
    }

    // 2. SEO Lead (Uvaish): only Uvaish (5), Aman (10), and Shivanshu (6)
    if ($userId === 5 || $userName === 'uvaish' || $roleName === 'seo_manager' || $roleId === 5) {
        return [5, 10, 6];
    }

    // 3. Dev Lead (Rahul): only Rahul (4) and Abhay (9)
    if ($userId === 4 || $userName === 'rahul' || $roleName === 'senior_developer' || $roleId === 4) {
        return [4, 9];
    }

    // 4. Aman (Intern SEO Analyst): ONLY Aman (10)
    if ($userId === 10 || $userName === 'aman' || $roleName === 'intern_seo' || $roleId === 10) {
        return [10];
    }

    // 5. Abhay (Intern Frontend Developer): ONLY Abhay (9)
    if ($userId === 9 || $userName === 'abhay' || $roleName === 'intern_developer' || $roleId === 9) {
        return [9];
    }

    // 6. Shivanshu (Senior SEO Executive): ONLY Shivanshu (6)
    if ($userId === 6 || strpos($userName, 'shivansh') !== false || $roleName === 'seo_executive' || $roleId === 6) {
        return [6];
    }

    // 7. All other employees: ONLY their own tasks
    return $userId > 0 ? [$userId] : [];
}

/**
 * Builds the SQL WHERE condition and parameters for task visibility filtering.
 * 
 * @param array|null $user The user record, defaults to getCurrentUser()
 * @param string $tableAlias The table alias for tasks (e.g. 't' or 'tasks' or '')
 * @return array ['sql' => string, 'params' => array]
 */
function getTaskVisibilityCondition(?array $user = null, string $tableAlias = 't'): array {
    $visibleIds = getVisibleEmployeeIdsForUser($user);
    if ($visibleIds === null) {
        return ['sql' => '1=1', 'params' => []];
    }
    if (empty($visibleIds)) {
        return ['sql' => '1=0', 'params' => []];
    }

    $prefix = $tableAlias !== '' ? $tableAlias . '.' : '';
    $placeholders = implode(',', array_fill(0, count($visibleIds), '?'));
    $sql = "({$prefix}assigned_to IN ({$placeholders}) OR {$prefix}assigned_to_employee_id IN ({$placeholders}))";
    $params = array_merge($visibleIds, $visibleIds);

    return ['sql' => $sql, 'params' => $params];
}

function getClientIP(): string {
    // If running in CLI / test environment with simulated client IP
    if (isset($_SERVER['TEST_CLIENT_IP']) && !empty($_SERVER['TEST_CLIENT_IP'])) {
        return trim((string)$_SERVER['TEST_CLIENT_IP']);
    }

    // Check Cloudflare Connecting IP header if present and valid
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $cf = trim((string)$_SERVER['HTTP_CF_CONNECTING_IP']);
        if (filter_var($cf, FILTER_VALIDATE_IP)) {
            return substr($cf, 0, 45);
        }
    }

    // Check reverse proxy / edge headers (Hostinger CDN, LiteSpeed, Nginx, ALB)
    $proxyHeaders = [
        'HTTP_TRUE_CLIENT_IP',
        'HTTP_X_REAL_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_CLIENT_IP'
    ];
    foreach ($proxyHeaders as $h) {
        if (!empty($_SERVER[$h])) {
            $ips = explode(',', (string)$_SERVER[$h]);
            foreach ($ips as $candidate) {
                $cand = trim($candidate);
                if (filter_var($cand, FILTER_VALIDATE_IP)) {
                    return substr($cand, 0, 45);
                }
            }
        }
    }

    $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
    if (filter_var($remoteAddr, FILTER_VALIDATE_IP)) {
        return substr($remoteAddr, 0, 45);
    }

    return substr(trim((string)($remoteAddr ?: '127.0.0.1')), 0, 45);
}

// Alias for camelCase consistency
if (!function_exists('getClientIp')) {
    function getClientIp(): string {
        return getClientIP();
    }
}

function logActivity(string $action, string $module, string $description, ?int $userId = null): void {
    try {
        $db = Database::getConnection();
        $uid = $userId ?? ($_SESSION['user_id'] ?? null);
        $ip = getClientIP();
        $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, module, description, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$uid, $action, $module, $description, $ip]);
    } catch (Exception $e) {
        error_log("Activity Log Error: " . $e->getMessage());
    }
}

function sendNotification(int $userId, string $title, string $message, string $link = '#', string $type = 'system'): void {
    try {
        $db = Database::getConnection();
        $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? Database::getDriver());
        if ($driver === 'sqlite') {
            $nextId = (int)$db->query("SELECT COALESCE(MAX(id), 0) + 1 FROM notifications")->fetchColumn();
            $stmt = $db->prepare("INSERT INTO notifications (id, user_id, title, message, link, type) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nextId, $userId, $title, $message, $link, $type]);
        } else {
            $stmt = $db->prepare("INSERT INTO notifications (user_id, title, message, link, type) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $title, $message, $link, $type]);
        }
    } catch (Exception $e) {
        error_log("Notification Error: " . $e->getMessage());
    }
}

function formatDuration($seconds = 0): string {
    $sec = max(0, (int)($seconds ?? 0));
    $hours = floor($sec / 3600);
    $minutes = floor(($sec % 3600) / 60);
    $secs = $sec % 60;
    return sprintf('%02dh %02dm %02ds', $hours, $minutes, $secs);
}

function formatSecondsToHMS($seconds = 0): string {
    $sec = max(0, (int)($seconds ?? 0));
    $hours = floor($sec / 3600);
    $minutes = floor(($sec % 3600) / 60);
    $secs = $sec % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
}

function getPriorityBadge(?string $priority): string {
    $norm = strtolower(trim((string)$priority));
    switch ($norm) {
        case 'urgent':
            return '<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-fire me-1"></i>Urgent</span>';
        case 'high':
            return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-exclamation-triangle me-1"></i>High</span>';
        case 'medium':
            return '<span class="badge bg-primary text-white px-2 py-1"><i class="fas fa-minus me-1"></i>Medium</span>';
        default:
            return '<span class="badge bg-secondary text-white px-2 py-1"><i class="fas fa-arrow-down me-1"></i>Low</span>';
    }
}

function getStatusBadge(?string $status, ?string $deadline = ''): string {
    $normStatus = strtolower(trim((string)$status));
    $isOverdue = (!empty($deadline) && strtotime((string)$deadline) < time() && !in_array($normStatus, ['completed', 'done']));
    
    if ($isOverdue) {
        return '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"><i class="fas fa-clock me-1"></i>Overdue</span>';
    }

    switch ($normStatus) {
        case 'completed':
        case 'done':
            return '<span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>Done</span>';
        case 'pending_approval':
        case 'in_review':
        case 'submitted':
            return '<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1 pulse-dot"><i class="fas fa-hourglass-half me-1"></i>Pending Approval</span>';
        case 'running':
        case 'in_progress':
            return '<span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 pulse-dot"><i class="fas fa-spinner fa-spin me-1"></i>Running</span>';
        case 'paused':
            return '<span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1"><i class="fas fa-pause-circle me-1"></i>Paused</span>';
        case 'rejected':
            return '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"><i class="fas fa-times-circle me-1"></i>Revision Needed</span>';
        default:
            return '<span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1"><i class="fas fa-clock me-1"></i>Pending</span>';
    }
}

function getProjectStatusBadge(?string $status): string {
    $norm = strtolower(trim((string)$status));
    switch ($norm) {
        case 'completed':
            return '<span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>Completed</span>';
        case 'in_progress':
        case 'active':
            return '<span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1"><i class="fas fa-spinner fa-spin me-1"></i>In Progress</span>';
        case 'planning':
            return '<span class="badge bg-info-subtle text-info border border-info px-2 py-1"><i class="fas fa-lightbulb me-1"></i>Planning</span>';
        case 'on_hold':
            return '<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1"><i class="fas fa-pause me-1"></i>On Hold</span>';
        case 'cancelled':
            return '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"><i class="fas fa-times me-1"></i>Cancelled</span>';
        default:
            return '<span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1">' . ucfirst($norm ?: 'Active') . '</span>';
    }
}

function getEmergencyRequestBadge(?string $status): string {
    if (empty($status)) return '';
    $norm = strtolower(trim((string)$status));
    switch ($norm) {
        case 'approved':
            return '<span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>Emergency Approved</span>';
        case 'rejected':
            return '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1"><i class="fas fa-times-circle me-1"></i>Emergency Rejected</span>';
        case 'cancelled':
            return '<span class="badge bg-secondary-subtle text-secondary border border-secondary px-2 py-1"><i class="fas fa-ban me-1"></i>Cancelled</span>';
        case 'pending':
        case 'pending_approval':
        default:
            return '<span class="badge bg-danger text-white border border-danger px-2 py-1 pulse-dot"><i class="fas fa-bolt me-1"></i>Emergency Pending</span>';
    }
}

function getTaskFlagBadge(?string $status): string {
    if (empty($status)) return '';
    $norm = strtolower(trim((string)$status));
    switch ($norm) {
        case 'resolved':
            return '<span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-flag-checkered me-1"></i>Flag Resolved</span>';
        case 'under_review':
            return '<span class="badge bg-info-subtle text-info border border-info px-2 py-1"><i class="fas fa-search me-1"></i>Under Review</span>';
        case 'waiting_info':
        case 'waiting for information':
            return '<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1"><i class="fas fa-clock me-1"></i>Waiting Info</span>';
        case 'reopened':
            return '<span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 pulse-dot"><i class="fas fa-exclamation-circle me-1"></i>Flag Reopened</span>';
        case 'open':
        default:
            return '<span class="badge bg-warning text-dark border border-warning px-2 py-1 pulse-dot"><i class="fas fa-flag me-1"></i>Flagged (Open)</span>';
    }
}

function getTaskFlagImpactBadge(?string $impact): string {
    if (empty($impact)) return '';
    $norm = strtolower(trim((string)$impact));
    switch ($norm) {
        case 'task cannot start':
        case 'blocked':
        case 'work is blocked':
            return '<span class="badge bg-danger text-white px-2 py-0.5"><i class="fas fa-hand-paper me-1"></i>' . htmlspecialchars($impact) . '</span>';
        case 'work can continue partially':
        case 'partially blocked':
            return '<span class="badge bg-warning text-dark px-2 py-0.5"><i class="fas fa-exclamation-triangle me-1"></i>' . htmlspecialchars($impact) . '</span>';
        default:
            return '<span class="badge bg-info-subtle text-info border border-info px-2 py-0.5"><i class="fas fa-question-circle me-1"></i>' . htmlspecialchars($impact) . '</span>';
    }
}

function getDeadlineCountdown(?string $deadline): string {
    if (empty($deadline) || $deadline === '--') {
        return '<span class="text-muted">No Deadline</span>';
    }
    $dTime = strtotime((string)$deadline);
    if (!$dTime) {
        return '<span class="text-muted">--</span>';
    }
    $diff = $dTime - time();
    if ($diff <= 0) {
        return '<span class="text-danger fw-bold"><i class="fas fa-exclamation-circle me-1"></i>Passed</span>';
    }
    $days = floor($diff / 86400);
    $hours = floor(($diff % 86400) / 3600);
    if ($days > 0) {
        return "<span class=\"text-primary fw-semibold\">{$days}d {$hours}h left</span>";
    }
    return "<span class=\"text-warning fw-semibold\">{$hours}h left</span>";
}

/**
 * Format date in IST as DD/MM/YYYY
 */
function formatISTDate($datetime = 'now'): string {
    if (empty($datetime) || $datetime === '--') return '--';
    try {
        $dt = is_numeric($datetime) ? (new DateTime())->setTimestamp((int)$datetime) : new DateTime($datetime);
        $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
        return $dt->format('d/m/Y');
    } catch (Exception $e) {
        return '--';
    }
}

/**
 * Format time in IST as hh:mm:ss AM/PM (IST)
 */
function formatISTTime($datetime = 'now'): string {
    if (empty($datetime) || $datetime === '--') return '--';
    try {
        $dt = is_numeric($datetime) ? (new DateTime())->setTimestamp((int)$datetime) : new DateTime($datetime);
        $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
        return $dt->format('h:i:s A') . ' (IST)';
    } catch (Exception $e) {
        return '--';
    }
}

/**
 * Format full datetime in IST as DD/MM/YYYY hh:mm:ss AM/PM (IST)
 */
function formatISTDateTime($datetime = 'now'): string {
    if (empty($datetime) || $datetime === '--') return '--';
    try {
        $dt = is_numeric($datetime) ? (new DateTime())->setTimestamp((int)$datetime) : new DateTime($datetime);
        $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
        return $dt->format('d/m/Y h:i:s A') . ' (IST)';
    } catch (Exception $e) {
        return '--';
    }
}

/**
 * Check if given datetime (or now) falls in the 02:00 PM to 03:00 PM IST Lunch Window
 */
function isLunchTime($datetime = 'now'): bool {
    try {
        $dt = is_numeric($datetime) ? (new DateTime())->setTimestamp((int)$datetime) : new DateTime($datetime);
        $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $h = (int)$dt->format('H');
        return ($h === 14); // 14:00:00 to 14:59:59 (02:00 PM to 03:00 PM)
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get Lunch Timing Information and Badges
 */
function getLunchTimingInfo($datetime = 'now'): array {
    $isLunch = isLunchTime($datetime);
    return [
        'is_lunch_now' => $isLunch,
        'lunch_start' => '02:00 PM',
        'lunch_end' => '03:00 PM',
        'lunch_window' => '02:00 PM - 03:00 PM (IST)',
        'duration_text' => '1 Hour',
        'status_text' => $isLunch ? 'Lunch Hour Active (02:00 PM - 03:00 PM)' : 'Scheduled Lunch (02:00 PM - 03:00 PM)',
        'badge' => getLunchBadge($datetime)
    ];
}

/**
 * Get Lunch Break Badge HTML
 */
function getLunchBadge($datetime = 'now'): string {
    if (isLunchTime($datetime)) {
        return '<span class="badge bg-warning text-dark border border-warning px-2.5 py-1 fw-bold"><i class="fas fa-utensils me-1"></i>Lunch Break Active</span>';
    }
    return '<span class="badge bg-light text-secondary border px-2 py-1"><i class="fas fa-utensils me-1 text-warning"></i>Lunch (2PM - 3PM)</span>';
}

/**
 * Calculate late minutes based on fixed office start time (09:40 AM IST)
 */
function calculateLateMinutes(string $clockInDateTimeStr): int {
    try {
        $dt = new DateTime($clockInDateTimeStr, new DateTimeZone('Asia/Kolkata'));
        $targetStart = new DateTime($dt->format('Y-m-d') . ' 09:40:00', new DateTimeZone('Asia/Kolkata'));
        if ($dt > $targetStart) {
            return (int)floor(($dt->getTimestamp() - $targetStart->getTimestamp()) / 60);
        }
    } catch (Exception $e) {
        error_log("calculateLateMinutes error: " . $e->getMessage());
    }
    return 0;
}

/**
 * Calculate early exit minutes based on fixed office end time (06:30 PM IST)
 */
function calculateEarlyExitMinutes(string $clockOutDateTimeStr): int {
    try {
        $dt = new DateTime($clockOutDateTimeStr, new DateTimeZone('Asia/Kolkata'));
        $targetEnd = new DateTime($dt->format('Y-m-d') . ' 18:30:00', new DateTimeZone('Asia/Kolkata'));
        if ($dt < $targetEnd) {
            return (int)floor(($targetEnd->getTimestamp() - $dt->getTimestamp()) / 60);
        }
    } catch (Exception $e) {
        error_log("calculateEarlyExitMinutes error: " . $e->getMessage());
    }
    return 0;
}

/**
 * Ensure Attendance Columns
 */
function ensureAttendanceColumns($db): void {
    if (!$db) return;
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN employee_id INT DEFAULT 0");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN user_id INT DEFAULT 0");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN early_exit_minutes INT DEFAULT 0");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN shift_start VARCHAR(10) DEFAULT '09:40'");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN shift_end VARCHAR(10) DEFAULT '18:30'");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN total_seconds INT DEFAULT 0");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN break_seconds INT DEFAULT 0");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN clock_in_ip VARCHAR(50) DEFAULT NULL");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN clock_out_ip VARCHAR(50) DEFAULT NULL");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN device_type VARCHAR(50) DEFAULT 'Desktop'");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance ADD COLUMN user_agent TEXT DEFAULT NULL");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance MODIFY COLUMN employee_id INT DEFAULT 0");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance MODIFY COLUMN user_id INT DEFAULT 0");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance MODIFY COLUMN clock_in VARCHAR(50) NULL");
    } catch (Exception $e) {}
    try {
        $db->exec("ALTER TABLE attendance MODIFY COLUMN clock_out VARCHAR(50) NULL");
    } catch (Exception $e) {}
}

/**
 * Ensure Authorized IPs Table Exists & Seeded
 */
function ensureAuthorizedIpTable($db): void {
    if (!$db) return;
    try {
        Database::ensureAuthorizedIpTableExists($db);
    } catch (Exception $e) {}
}

/**
 * Detect Client Device Type (Mobile, Tablet, Desktop)
 */
function getClientDeviceType(?string $userAgent = null): string {
    $ua = $userAgent ?? ($_SERVER['HTTP_USER_AGENT'] ?? '');
    if (empty($ua)) {
        return 'Desktop';
    }
    // Tablet check first (iPad, Android Tablet, Kindle, etc.)
    if (preg_match('/(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk)/i', $ua)) {
        return 'Tablet';
    }
    // Mobile check (iPhone, Android Mobile, Windows Phone, etc.)
    if (preg_match('/(mobile|ipod|iphone|android|blackberry|bb10|rim|fennec|opera mini|iemobile|wpdesktop|windows phone)/i', $ua)) {
        return 'Mobile';
    }
    return 'Desktop';
}

/**
 * Check if a Client IP Address is in the Authorized Office Wi-Fi Whitelist
 */
function isClientIpAuthorized($db = null, ?string $clientIp = null): bool {
    try {
        if ($db === null) {
            $db = Database::getConnection();
        }
        ensureAuthorizedIpTable($db);

        $ip = $clientIp ?? getClientIp();
        if (empty($ip)) {
            return false;
        }

        $stmt = $db->query("SELECT * FROM authorized_ips WHERE status = 'active'");
        $allowedList = $stmt->fetchAll();

        foreach ($allowedList as $row) {
            $allowedIp = trim($row['ip_address'] ?? '');
            if (empty($allowedIp)) continue;

            // 1. Direct exact match (e.g. 192.168.29.174 or 192.168.1.8)
            if ($allowedIp === $ip) {
                return true;
            }

            // 2. Wildcard match (e.g. 192.168.29.*)
            if (str_contains($allowedIp, '*')) {
                if (fnmatch($allowedIp, $ip)) {
                    return true;
                }
            }

            // 3. CIDR subnet match (e.g. 192.168.29.0/24 or 2401:4900::/32)
            if (str_contains($allowedIp, '/')) {
                list($subnet, $bits) = explode('/', $allowedIp, 2);
                $bits = (int)$bits;
                // IPv4 CIDR check
                if (filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $subnetLong = ip2long($subnet);
                    $ipLong = ip2long($ip);
                    $mask = -1 << (32 - $bits);
                    if (($ipLong & $mask) === ($subnetLong & $mask)) {
                        return true;
                    }
                }
                // IPv6 CIDR check
                if (filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                    $subnetBin = inet_pton($subnet);
                    $ipBin = inet_pton($ip);
                    if ($subnetBin !== false && $ipBin !== false) {
                        $fullBytes = intdiv($bits, 8);
                        $remainingBits = $bits % 8;
                        if (substr($subnetBin, 0, $fullBytes) === substr($ipBin, 0, $fullBytes)) {
                            if ($remainingBits === 0) {
                                return true;
                            }
                            $maskByte = chr((0xFF << (8 - $remainingBits)) & 0xFF);
                            if (($subnetBin[$fullBytes] & $maskByte) === ($ipBin[$fullBytes] & $maskByte)) {
                                return true;
                            }
                        }
                    }
                }
            }
        }

        return false;
    } catch (Exception $e) {
        error_log("isClientIpAuthorized error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get Location / Network Name for an IP Address
 */
function getIpNetworkName($db = null, ?string $clientIp = null): string {
    try {
        if ($db === null) {
            $db = Database::getConnection();
        }
        ensureAuthorizedIpTable($db);

        $ip = $clientIp ?? getClientIp();
        if (empty($ip)) {
            return 'Unknown Network';
        }

        $stmt = $db->query("SELECT * FROM authorized_ips WHERE status = 'active'");
        $allowedList = $stmt->fetchAll();

        foreach ($allowedList as $row) {
            $allowedIp = trim($row['ip_address'] ?? '');
            if (empty($allowedIp)) continue;

            // Direct exact match
            if ($allowedIp === $ip) {
                return $row['location'] ?: 'Authorized Office Wi-Fi';
            }

            // Wildcard match
            if (str_contains($allowedIp, '*') && fnmatch($allowedIp, $ip)) {
                return $row['location'] ?: 'Authorized Office Wi-Fi';
            }

            // CIDR subnet match
            if (str_contains($allowedIp, '/')) {
                list($subnet, $bits) = explode('/', $allowedIp, 2);
                if (filter_var($subnet, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) && filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    $subnetLong = ip2long($subnet);
                    $ipLong = ip2long($ip);
                    $mask = -1 << (32 - (int)$bits);
                    if (($ipLong & $mask) === ($subnetLong & $mask)) {
                        return $row['location'] ?: 'Authorized Office Wi-Fi';
                    }
                }
            }
        }

        return 'Unauthorized Network';
    } catch (Exception $e) {
        return 'Unknown Network';
    }
}

/**
 * Get All Authorized IPs List
 */
function getAuthorizedIpsList($db = null): array {
    if ($db === null) {
        $db = Database::getConnection();
    }
    ensureAuthorizedIpTable($db);
    $stmt = $db->query("SELECT * FROM authorized_ips ORDER BY id ASC");
    return $stmt->fetchAll() ?: [];
}

/**
 * Automatically close active shifts if past 07:00 PM (19:00:00) IST or from previous days.
 * If employee has not manually ended shift after 06:30 PM, the system auto-ends it at 07:00 PM IST.
 */
function autoCloseOverdueShifts(PDO $db, ?int $userId = null): int {
    try {
        ensureAttendanceColumns($db);
        $nowDT = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        $todayStr = $nowDT->format('Y-m-d');
        $currentISTTime = $nowDT->format('H:i:s');

        $sql = "SELECT * FROM attendance WHERE clock_out IS NULL";
        $params = [];
        if ($userId !== null) {
            $sql .= " AND user_id = ?";
            $params[] = $userId;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $openShifts = $stmt->fetchAll();

        $closedCount = 0;
        foreach ($openShifts as $shift) {
            $shiftDate = $shift['date'];
            $isPastDay = ($shiftDate < $todayStr);
            $isPast7PM = ($shiftDate === $todayStr && $currentISTTime >= '19:00:00');

            if ($isPastDay || $isPast7PM) {
                // Auto close at 07:00 PM IST on that shift's date
                $autoClockOutStr = $shiftDate . ' 19:00:00';
                $clockInStr = strlen($shift['clock_in']) <= 8 ? ($shiftDate . ' ' . $shift['clock_in']) : $shift['clock_in'];
                $clockInTime = strtotime($clockInStr);
                $clockOutTime = strtotime($autoClockOutStr);
                $totalSecs = max(0, $clockOutTime - $clockInTime);
                $lateMinutes = (int)($shift['late_minutes'] ?? calculateLateMinutes($clockInStr));
                $earlyExitMinutes = 0; // 07:00 PM is after 06:30 PM (No early exit)
                
                // Evaluated as Full Day if >= 8h 50m (31800s) and closed at 7 PM
                $finalStatus = ($totalSecs >= 31800) ? 'full_day' : 'half_day';

                $upd = $db->prepare("UPDATE attendance SET clock_out = ?, total_seconds = ?, late_minutes = ?, early_exit_minutes = ?, status = ? WHERE id = ?");
                $upd->execute([$autoClockOutStr, $totalSecs, $lateMinutes, $earlyExitMinutes, $finalStatus, $shift['id']]);

                logActivity('Auto Shift End', 'Attendance', "System auto-ended shift at 07:00 PM IST (Shift Date: {$shiftDate}, ID: {$shift['id']}) as {$finalStatus}");
                $closedCount++;
            }
        }
        return $closedCount;
    } catch (Exception $e) {
        error_log("autoCloseOverdueShifts error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Get On Time / Late Badge HTML
 */
function getPunctualityBadge(int $lateMinutes): string {
    if ($lateMinutes <= 0) {
        return '<span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>On Time</span>';
    }
    return '<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1"><i class="fas fa-clock me-1"></i>Late (' . $lateMinutes . 'm)</span>';
}

/**
 * Get Full Day / Half Day Badge HTML
 */
function getAttendanceDayBadge(string $status, int $totalSeconds = 0, ?string $clockOut = null): string {
    $statusLower = strtolower($status);
    if ($statusLower === 'full_day' || $statusLower === 'full day') {
        return '<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-sun me-1"></i>Full Day</span>';
    } elseif ($statusLower === 'half_day' || $statusLower === 'half day') {
        return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fas fa-cloud-sun me-1"></i>Half Day</span>';
    } elseif ($statusLower === 'present') {
        return '<span class="badge bg-primary text-white px-2 py-1"><i class="fas fa-user-check me-1"></i>Present</span>';
    } elseif ($statusLower === 'absent') {
        return '<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-user-times me-1"></i>Absent</span>';
    } elseif ($statusLower === 'on_leave') {
        return '<span class="badge bg-info text-white px-2 py-1"><i class="fas fa-plane-departure me-1"></i>On Leave</span>';
    }
    return '<span class="badge bg-secondary text-white px-2 py-1">' . ucfirst($status) . '</span>';
}

function requireAdmin(): array {
    $user = requireLogin();
    $roleName = strtolower($user['role_name'] ?? '');
    $roleId = (int)($user['role_id'] ?? 0);

    if ($roleName !== 'admin' && $roleId !== 1 && strpos($roleName, 'admin') === false) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            jsonResponse(false, 'Access denied. Admin privileges required.', [], 403);
        } else {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }
    return $user;
}

function ensureEmployeeSchema($db): void {
    try {
        $db->exec("ALTER TABLE users ADD COLUMN employee_code VARCHAR(50) NULL UNIQUE");
    } catch (Exception $e) {}

    try {
        $db->exec("ALTER TABLE users ADD COLUMN shift_timing VARCHAR(100) DEFAULT '09:40 AM - 06:30 PM (Fixed Office)'");
    } catch (Exception $e) {}

    try {
        $db->exec("ALTER TABLE employee_profiles ADD COLUMN address TEXT NULL");
    } catch (Exception $e) {}

    try {
        $db->exec("ALTER TABLE employee_profiles ADD COLUMN emergency_contact VARCHAR(50) NULL");
    } catch (Exception $e) {}

    // Ensure Ads Expert role exists
    try {
        $chkRole = $db->prepare("SELECT id FROM roles WHERE LOWER(name) = 'ads_expert' OR LOWER(display_name) = 'ads expert'");
        $chkRole->execute();
        if (!$chkRole->fetch()) {
            $db->exec("INSERT INTO roles (name, display_name, description) VALUES ('ads_expert', 'ADS EXPERT', 'Meta Ads & Google Ads Campaign Specialist')");
        }
    } catch (Exception $e) {}

    // Ensure Meta Ads & Google Ads department exists
    try {
        $chkDept = $db->prepare("SELECT id FROM departments WHERE LOWER(name) = 'meta ads & google ads' OR LOWER(code) = 'ads'");
        $chkDept->execute();
        if (!$chkDept->fetch()) {
            $db->exec("INSERT INTO departments (name, code, description) VALUES ('Meta Ads & Google Ads', 'ADS', 'Meta Ads & Google Ads Campaign Management')");
        }
    } catch (Exception $e) {}
}

function generateEmployeeCode($db): string {
    ensureEmployeeSchema($db);
    try {
        $maxId = $db->query("SELECT MAX(id) FROM users")->fetchColumn() ?: 0;
        $nextId = (int)$maxId + 1;
        return 'EMP-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
    } catch (Exception $e) {
        return 'EMP-' . rand(100, 999);
    }
}

function ensureTaskTimeLogsSchema($db): void {
    try {
        $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? Database::getDriver());
    } catch (Exception $e) {
        $driver = Database::getDriver();
    }

    if ($driver === 'sqlite') {
        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `task_time_logs` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `task_id` INTEGER NOT NULL,
                  `employee_id` INTEGER NOT NULL,
                  `started_at` DATETIME NULL,
                  `paused_at` DATETIME NULL,
                  `resumed_at` DATETIME NULL,
                  `completed_at` DATETIME NULL,
                  `total_work_duration` INTEGER NOT NULL DEFAULT 0,
                  `status` TEXT DEFAULT 'started',
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
                );
            ");
        } catch (Exception $e) {}
    } else {
        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `task_time_logs` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `task_id` INT NOT NULL,
                  `employee_id` INT NOT NULL,
                  `started_at` DATETIME NULL,
                  `paused_at` DATETIME NULL,
                  `resumed_at` DATETIME NULL,
                  `completed_at` DATETIME NULL,
                  `total_work_duration` INT NOT NULL DEFAULT 0,
                  `status` VARCHAR(50) DEFAULT 'started',
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
                  FOREIGN KEY (`employee_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (Exception $e) {}
    }
}

function ensureTaskApprovalSchema(?PDO $db = null): void {
    if (!$db) {
        $db = Database::getConnection();
    }
    try {
        $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? Database::getDriver());
    } catch (Exception $e) {
        $driver = Database::getDriver();
    }

    $columnsToAdd = [
        'submitted_for_approval_at' => ($driver === 'sqlite') ? 'DATETIME NULL' : 'DATETIME NULL',
        'completion_notes'          => ($driver === 'sqlite') ? 'TEXT NULL' : 'TEXT NULL',
        'approved_by'               => ($driver === 'sqlite') ? 'INTEGER NULL' : 'INT NULL',
        'approved_at'               => ($driver === 'sqlite') ? 'DATETIME NULL' : 'DATETIME NULL',
        'rejection_reason'          => ($driver === 'sqlite') ? 'TEXT NULL' : 'TEXT NULL'
    ];

    foreach ($columnsToAdd as $colName => $colDef) {
        try {
            $db->exec("ALTER TABLE tasks ADD COLUMN `{$colName}` {$colDef}");
        } catch (Exception $ex) {
            // Column already exists or error ignored
        }
    }
}

function ensureEmergencyAndFlagSchema($db = null) {
    static $ensured = false;
    if ($ensured) {
        return;
    }
    $ensured = true;

    if (!$db) {
        $db = Database::getConnection();
    }
    try {
        $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? Database::getDriver());
    } catch (Throwable $e) {
        $driver = Database::getDriver();
    }
    $isSqlite = ($driver === 'sqlite');

    if ($isSqlite) {
        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS emergency_task_requests (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    task_id INTEGER NOT NULL,
                    employee_id INTEGER NOT NULL,
                    reason TEXT NOT NULL,
                    message TEXT NOT NULL,
                    attachment TEXT DEFAULT NULL,
                    status TEXT NOT NULL DEFAULT 'pending',
                    reviewer_id INTEGER DEFAULT NULL,
                    reviewer_comment TEXT DEFAULT NULL,
                    rejection_reason TEXT DEFAULT NULL,
                    reviewed_at DATETIME DEFAULT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                );
            ");
        } catch (Throwable $e) {
            error_log("ensureEmergencyAndFlagSchema emergency_task_requests sqlite: " . $e->getMessage());
        }

        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS task_flags (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    task_id INTEGER NOT NULL,
                    employee_id INTEGER NOT NULL,
                    category TEXT NOT NULL,
                    missing_requirement TEXT NOT NULL,
                    impact TEXT NOT NULL,
                    attachment TEXT DEFAULT NULL,
                    status TEXT NOT NULL DEFAULT 'open',
                    resolution_message TEXT DEFAULT NULL,
                    resolved_by INTEGER DEFAULT NULL,
                    resolved_at DATETIME DEFAULT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                );
            ");
        } catch (Throwable $e) {
            error_log("ensureEmergencyAndFlagSchema task_flags sqlite: " . $e->getMessage());
        }

        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS task_flag_comments (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    flag_id INTEGER NOT NULL,
                    user_id INTEGER NOT NULL,
                    comment TEXT NOT NULL,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                );
            ");
        } catch (Throwable $e) {
            error_log("ensureEmergencyAndFlagSchema task_flag_comments sqlite: " . $e->getMessage());
        }
    } else {
        // MySQL / MariaDB (e.g. u254498372_kraft_workflow on Hostinger)
        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `emergency_task_requests` (
                    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `task_id` INT NOT NULL,
                    `employee_id` INT NOT NULL,
                    `reason` VARCHAR(255) NOT NULL,
                    `message` TEXT NOT NULL,
                    `attachment` VARCHAR(255) DEFAULT NULL,
                    `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
                    `reviewer_id` INT DEFAULT NULL,
                    `reviewer_comment` TEXT DEFAULT NULL,
                    `rejection_reason` TEXT DEFAULT NULL,
                    `reviewed_at` DATETIME DEFAULT NULL,
                    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX `idx_emg_task` (`task_id`),
                    INDEX `idx_emg_employee` (`employee_id`),
                    INDEX `idx_emg_status` (`status`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        } catch (Throwable $e) {
            error_log("ensureEmergencyAndFlagSchema emergency_task_requests mysql: " . $e->getMessage());
        }

        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `task_flags` (
                    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `task_id` INT NOT NULL,
                    `employee_id` INT NOT NULL,
                    `category` VARCHAR(100) NOT NULL,
                    `missing_requirement` TEXT NOT NULL,
                    `impact` VARCHAR(100) NOT NULL,
                    `attachment` VARCHAR(255) DEFAULT NULL,
                    `status` VARCHAR(50) NOT NULL DEFAULT 'open',
                    `resolution_message` TEXT DEFAULT NULL,
                    `resolved_by` INT DEFAULT NULL,
                    `resolved_at` DATETIME DEFAULT NULL,
                    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX `idx_flag_task` (`task_id`),
                    INDEX `idx_flag_employee` (`employee_id`),
                    INDEX `idx_flag_status` (`status`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        } catch (Throwable $e) {
            error_log("ensureEmergencyAndFlagSchema task_flags mysql: " . $e->getMessage());
        }

        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `task_flag_comments` (
                    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `flag_id` INT NOT NULL,
                    `user_id` INT NOT NULL,
                    `comment` TEXT NOT NULL,
                    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    INDEX `idx_comment_flag` (`flag_id`),
                    INDEX `idx_comment_user` (`user_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");
        } catch (Throwable $e) {
            error_log("ensureEmergencyAndFlagSchema task_flag_comments mysql: " . $e->getMessage());
        }
    }

    // Auto-repair/migration for any pre-existing tables missing newly added columns
    $emgCols = [
        'reviewer_id'      => $isSqlite ? 'INTEGER NULL' : 'INT NULL',
        'reviewer_comment' => $isSqlite ? 'TEXT NULL' : 'TEXT NULL',
        'rejection_reason' => $isSqlite ? 'TEXT NULL' : 'TEXT NULL',
        'reviewed_at'      => $isSqlite ? 'DATETIME NULL' : 'DATETIME NULL',
        'attachment'       => $isSqlite ? 'TEXT NULL' : 'VARCHAR(255) NULL'
    ];
    foreach ($emgCols as $cName => $cDef) {
        try {
            $db->exec("ALTER TABLE emergency_task_requests ADD COLUMN {$cName} {$cDef}");
        } catch (Throwable $ex) {}
    }

    $flagCols = [
        'attachment'         => $isSqlite ? 'TEXT NULL' : 'VARCHAR(255) NULL',
        'resolution_message' => $isSqlite ? 'TEXT NULL' : 'TEXT NULL',
        'resolved_by'        => $isSqlite ? 'INTEGER NULL' : 'INT NULL',
        'resolved_at'        => $isSqlite ? 'DATETIME NULL' : 'DATETIME NULL'
    ];
    foreach ($flagCols as $cName => $cDef) {
        try {
            $db->exec("ALTER TABLE task_flags ADD COLUMN {$cName} {$cDef}");
        } catch (Throwable $ex) {}
    }

    // Ensure both assigned_to and assigned_to_employee_id exist on tasks table
    try {
        $db->exec("ALTER TABLE tasks ADD COLUMN assigned_to " . ($isSqlite ? "INTEGER NULL" : "INT NULL"));
    } catch (Throwable $tEx1) {}
    try {
        $db->exec("ALTER TABLE tasks ADD COLUMN assigned_to_employee_id " . ($isSqlite ? "INTEGER NULL" : "INT NULL"));
    } catch (Throwable $tEx2) {}
}

function getPendingEmergencyRequestsCount($userId = null, $db = null) {
    if (!$db) $db = Database::getConnection();
    ensureEmergencyAndFlagSchema($db);
    try {
        if ($userId !== null && $userId > 0) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM emergency_task_requests WHERE employee_id = ? AND status = 'pending'");
            $stmt->execute([(int)$userId]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$db->query("SELECT COUNT(*) FROM emergency_task_requests WHERE status = 'pending'")->fetchColumn();
    } catch (Throwable $e) {
        return 0;
    }
}

function getOpenTaskFlagsCount($userId = null, $db = null) {
    if (!$db) $db = Database::getConnection();
    ensureEmergencyAndFlagSchema($db);
    try {
        if ($userId !== null && $userId > 0) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM task_flags WHERE employee_id = ? AND status IN ('open', 'under_review', 'waiting_info', 'reopened')");
            $stmt->execute([(int)$userId]);
            return (int)$stmt->fetchColumn();
        }
        return (int)$db->query("SELECT COUNT(*) FROM task_flags WHERE status IN ('open', 'under_review', 'waiting_info', 'reopened')")->fetchColumn();
    } catch (Throwable $e) {
        return 0;
    }
}

function getManagersAndProductManagers(?PDO $db = null): array {
    if (!$db) {
        $db = Database::getConnection();
    }
    try {
        $stmt = $db->query("
            SELECT u.id, u.name, u.email, u.role_id, r.name as role_name
            FROM users u
            JOIN roles r ON u.role_id = r.id
            WHERE (r.name IN ('admin', 'manager', 'product_manager') 
                   OR u.role_id IN (1, 2, 3) 
                   OR LOWER(r.name) LIKE '%manager%' 
                   OR LOWER(r.name) LIKE '%admin%')
              AND u.status = 'active'
        ");
        return $stmt->fetchAll() ?: [];
    } catch (Exception $e) {
        error_log("getManagersAndProductManagers Error: " . $e->getMessage());
        return [];
    }
}

function notifyManagersAndProductManagers(string $title, string $message, string $link = '#', ?int $exceptUserId = null, string $type = 'task_approval'): array {
    $db = Database::getConnection();
    $recipients = getManagersAndProductManagers($db);
    $notified = [];
    foreach ($recipients as $recipient) {
        $rid = (int)$recipient['id'];
        if ($exceptUserId !== null && $rid === $exceptUserId) {
            continue;
        }
        sendNotification($rid, $title, $message, $link, $type);
        $notified[] = $rid;
    }
    return $notified;
}

function ensurePayrollSchema($db): void {
    ensureEmployeeSchema($db);
    ensureTaskTimeLogsSchema($db);

    try {
        $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? Database::getDriver());
    } catch (Exception $e) {
        $driver = Database::getDriver();
    }

    if ($driver === 'sqlite') {
        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `employee_salaries` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `user_id` INTEGER NOT NULL UNIQUE,
                  `monthly_salary` REAL NOT NULL DEFAULT 0.00,
                  `allowance` REAL NOT NULL DEFAULT 0.00,
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
                );
            ");
        } catch (Exception $e) {}

        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `payrolls` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `user_id` INTEGER NOT NULL,
                  `month_year` TEXT NOT NULL,
                  `basic_salary` REAL NOT NULL DEFAULT 0.00,
                  `allowance` REAL NOT NULL DEFAULT 0.00,
                  `bonus` REAL NOT NULL DEFAULT 0.00,
                  `deduction` REAL NOT NULL DEFAULT 0.00,
                  `present_days` REAL NOT NULL DEFAULT 0.0,
                  `half_days` REAL NOT NULL DEFAULT 0.0,
                  `absent_days` REAL NOT NULL DEFAULT 0.0,
                  `paid_leave_days` REAL NOT NULL DEFAULT 0.0,
                  `unpaid_leave_days` REAL NOT NULL DEFAULT 0.0,
                  `holiday_days` INTEGER NOT NULL DEFAULT 0,
                  `working_hours` REAL NOT NULL DEFAULT 0.00,
                  `net_salary` REAL NOT NULL DEFAULT 0.00,
                  `payment_status` TEXT DEFAULT 'pending',
                  `payment_date` TEXT NULL,
                  `remarks` TEXT NULL,
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  UNIQUE (`user_id`, `month_year`)
                );
            ");
        } catch (Exception $e) {}
        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `leave_requests` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `user_id` INTEGER NOT NULL,
                  `leave_type` TEXT DEFAULT 'paid',
                  `start_date` DATE NOT NULL,
                  `end_date` DATE NOT NULL,
                  `total_days` REAL NOT NULL DEFAULT 1.0,
                  `paid_days` REAL NOT NULL DEFAULT 0.0,
                  `unpaid_days` REAL NOT NULL DEFAULT 0.0,
                  `reason` TEXT NOT NULL,
                  `attachment` TEXT DEFAULT NULL,
                  `status` TEXT DEFAULT 'pending',
                  `approved_by` INTEGER DEFAULT NULL,
                  `comment` TEXT DEFAULT NULL,
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
                );
            ");
        } catch (Exception $e) {}
    } else {
        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `employee_salaries` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `user_id` INT NOT NULL UNIQUE,
                  `monthly_salary` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                  `allowance` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (Exception $e) {}

        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `payrolls` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `user_id` INT NOT NULL,
                  `month_year` VARCHAR(7) NOT NULL,
                  `basic_salary` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                  `allowance` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                  `bonus` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                  `deduction` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                  `present_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0,
                  `half_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0,
                  `absent_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0,
                  `paid_leave_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0,
                  `unpaid_leave_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0,
                  `holiday_days` INT NOT NULL DEFAULT 0,
                  `working_hours` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
                  `net_salary` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                  `payment_status` ENUM('pending', 'paid') DEFAULT 'pending',
                  `payment_date` DATE NULL,
                  `remarks` TEXT NULL,
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  UNIQUE KEY `unique_user_month` (`user_id`, `month_year`),
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (Exception $e) {}

        try {
            $db->exec("
                CREATE TABLE IF NOT EXISTS `leave_requests` (
                  `id` INT AUTO_INCREMENT PRIMARY KEY,
                  `user_id` INT NOT NULL,
                  `leave_type` ENUM('paid','sick','casual','emergency','unpaid') DEFAULT 'paid',
                  `start_date` DATE NOT NULL,
                  `end_date` DATE NOT NULL,
                  `total_days` DECIMAL(5,1) NOT NULL DEFAULT 1.0,
                  `paid_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0,
                  `unpaid_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0,
                  `reason` TEXT NOT NULL,
                  `attachment` VARCHAR(255) DEFAULT NULL,
                  `status` ENUM('pending','approved','rejected','cancelled') DEFAULT 'pending',
                  `approved_by` INT DEFAULT NULL,
                  `comment` TEXT DEFAULT NULL,
                  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            ");
        } catch (Exception $e) {}
    }

    try { $db->exec("ALTER TABLE `payrolls` ADD COLUMN `completed_days` INT NOT NULL DEFAULT 0"); } catch (Exception $e) {}
    try { $db->exec("ALTER TABLE `payrolls` ADD COLUMN `earned_salary` DECIMAL(10,2) NOT NULL DEFAULT 0.00"); } catch (Exception $e) {}
    try { $db->exec("ALTER TABLE `leave_requests` ADD COLUMN `attachment` VARCHAR(255) NULL"); } catch (Exception $e) {}
    try { $db->exec("ALTER TABLE `leave_requests` ADD COLUMN `paid_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0"); } catch (Exception $e) {}
    try { $db->exec("ALTER TABLE `leave_requests` ADD COLUMN `unpaid_days` DECIMAL(5,1) NOT NULL DEFAULT 0.0"); } catch (Exception $e) {}

    // Seed default departments if empty
    try {
        $deptCount = (int)$db->query("SELECT COUNT(*) FROM departments")->fetchColumn();
        if ($deptCount === 0) {
            $db->exec("INSERT INTO `departments` (`id`, `name`, `code`, `description`) VALUES
                (1, 'Administration', 'ADM', 'Executive leadership and company administration'),
                (2, 'Development', 'DEV', 'Software engineering and web application development'),
                (3, 'SEO', 'SEO', 'Search engine optimization and digital marketing'),
                (4, 'Design', 'DSG', 'UI/UX design and creative graphics'),
                (5, 'Social Media', 'SMM', 'Social media marketing and branding'),
                (6, 'Product Management', 'PMO', 'Product roadmap, sprints, and feature planning')
            ");
        }
    } catch (Exception $e) {}

    // Synchronize and enforce all required accounts with secure bcrypt hashing
    ensureCorrectUserAccounts($db);
}

function ensureCorrectUserAccounts($db): void {
    ensureEmployeeSchema($db);

    $accounts = [
        [
            'id' => 1,
            'role_id' => 1,
            'department_id' => 1,
            'name' => 'Kartikeyan',
            'email' => 'kartikeyan@kraftingdigital.com',
            'password' => 'krafting@123',
            'phone' => '+91 9876543210',
            'designation' => 'System Administrator & CEO',
            'salary' => 50000.00
        ],
        [
            'id' => 2,
            'role_id' => 2,
            'department_id' => 1,
            'name' => 'Mrinali',
            'email' => 'mrinali@kraftingdigital.com',
            'username' => 'mrinali',
            'password' => 'Mrinali123@@',
            'phone' => '+91 9876543211',
            'designation' => 'Operations Manager',
            'salary' => 25000.00
        ],
        [
            'id' => 3,
            'role_id' => 3,
            'department_id' => 6,
            'name' => 'Udit',
            'email' => 'contact@kraftingdigital.com',
            'password' => 'Udit123@@',
            'phone' => '+91 9876543212',
            'designation' => 'Product Manager',
            'salary' => 20000.00
        ],
        [
            'id' => 4,
            'role_id' => 4,
            'department_id' => 2,
            'name' => 'Rahul',
            'email' => 'finance@kraftingdigital.com',
            'password' => 'Rahul123@@',
            'phone' => '+91 9876543213',
            'designation' => 'Senior Fullstack Developer',
            'salary' => 30000.00
        ],
        [
            'id' => 5,
            'role_id' => 5,
            'department_id' => 3,
            'name' => 'Uvaish',
            'email' => 'uvaish@kraftingdigital.com',
            'password' => '@Uvaish123@',
            'phone' => '+91 9876543214',
            'designation' => 'SEO Lead Manager',
            'salary' => 25000.00
        ],
        [
            'id' => 6,
            'role_id' => 6,
            'department_id' => 3,
            'name' => 'Shivanshu',
            'email' => 'shivanshu@kraftingdigital.com',
            'password' => '@Shivanshu123@',
            'phone' => '+91 9876543215',
            'designation' => 'Senior SEO Executive',
            'salary' => 10000.00
        ],
        [
            'id' => 7,
            'role_id' => 7,
            'department_id' => 4,
            'name' => 'Sarthak',
            'email' => 'sarthak@kraftingdigital.com',
            'password' => '@Sarthak@123',
            'phone' => '+91 9876543216',
            'designation' => 'Lead UI/UX Graphic Designer',
            'salary' => 10000.00
        ],
        [
            'id' => 8,
            'role_id' => 8,
            'department_id' => 5,
            'name' => 'Priyansh',
            'email' => 'priyansh@kraftingdigital.com',
            'password' => 'Priyansh@123@',
            'phone' => '+91 9876543217',
            'designation' => 'Social Media Strategist',
            'salary' => 15000.00
        ],
        [
            'id' => 9,
            'role_id' => 9,
            'department_id' => 2,
            'name' => 'Abhay',
            'email' => 'abhay@kraftingdigital.com',
            'password' => '123@@Abhay',
            'phone' => '+91 9876543218',
            'designation' => 'Intern Frontend Developer',
            'salary' => 5000.00
        ],
        [
            'id' => 10,
            'role_id' => 10,
            'department_id' => 3,
            'name' => 'Aman',
            'email' => 'aman@kraftingdigital.com',
            'password' => '@Aman@123',
            'phone' => '+91 9876543219',
            'designation' => 'Intern SEO Analyst',
            'salary' => 7000.00
        ],
        [
            'id' => 11,
            'role_id' => 11,
            'department_id' => 7,
            'name' => 'PRABHAT',
            'email' => 'prabhat@kraftingdigital.com',
            'password' => 'Prabhat123@@',
            'phone' => '+91 9876543220',
            'designation' => 'ADS EXPERT',
            'salary' => 25000.00
        ]
    ];

    // Dynamically match role and department for Prabhat if available
    try {
        $adsRoleId = (int)$db->query("SELECT id FROM roles WHERE LOWER(name) = 'ads_expert' OR LOWER(display_name) = 'ads expert'")->fetchColumn();
        if ($adsRoleId > 0) {
            $accounts[10]['role_id'] = $adsRoleId;
        }
        $adsDeptId = (int)$db->query("SELECT id FROM departments WHERE LOWER(name) = 'meta ads & google ads' OR LOWER(code) = 'ads'")->fetchColumn();
        if ($adsDeptId > 0) {
            $accounts[10]['department_id'] = $adsDeptId;
        }
    } catch (Exception $e) {}

    foreach ($accounts as $acc) {
        try {
            $checkStmt = $db->prepare("SELECT id, password FROM users WHERE email = ? OR id = ?");
            $checkStmt->execute([$acc['email'], $acc['id']]);
            $existing = $checkStmt->fetch();

            $pwdHash = password_hash($acc['password'], PASSWORD_BCRYPT);

            if ($existing) {
                $updStmt = $db->prepare("
                    UPDATE users 
                    SET name = ?, email = ?, password = ?, role_id = ?, department_id = ?, phone = ?, designation = ?, status = 'active'
                    WHERE id = ?
                ");
                $updStmt->execute([
                    $acc['name'],
                    $acc['email'],
                    $pwdHash,
                    $acc['role_id'],
                    $acc['department_id'],
                    $acc['phone'],
                    $acc['designation'],
                    $existing['id']
                ]);
                $userId = (int)$existing['id'];
            } else {
                $insStmt = $db->prepare("
                    INSERT INTO users (id, role_id, department_id, name, email, password, phone, designation, status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')
                ");
                $insStmt->execute([
                    $acc['id'],
                    $acc['role_id'],
                    $acc['department_id'],
                    $acc['name'],
                    $acc['email'],
                    $pwdHash,
                    $acc['phone'],
                    $acc['designation']
                ]);
                $userId = (int)$acc['id'];
            }

            // Keep username, password_hash, and employee_code synchronized
            try {
                $uName = $acc['username'] ?? strtolower(explode('@', $acc['email'])[0]);
                $empCode = 'KD-EMP-' . str_pad($acc['id'], 3, '0', STR_PAD_LEFT);
                $db->prepare("UPDATE users SET username = ?, password_hash = ?, employee_code = ? WHERE id = ?")
                   ->execute([$uName, $pwdHash, $empCode, $userId]);
            } catch (Exception $e) {}

            // Keep employees table synchronized
            try {
                $empCode = 'KD-EMP-' . str_pad($acc['id'], 3, '0', STR_PAD_LEFT);
                $db->prepare("UPDATE employees SET name = ?, email = ? WHERE user_id = ? OR employee_code = ?")
                   ->execute([$acc['name'], $acc['email'], $userId, $empCode]);
            } catch (Exception $e) {}

            // Ensure profile exists
            $profCheck = $db->prepare("SELECT id FROM employee_profiles WHERE user_id = ?");
            $profCheck->execute([$userId]);
            if (!$profCheck->fetch()) {
                $skills = ($acc['email'] === 'prabhat@kraftingdigital.com') ? 'Meta Ads & Google Ads' : 'Enterprise Management';
                $bio = ($acc['email'] === 'prabhat@kraftingdigital.com') ? 'Meta Ads & Google Ads Specialist' : 'Team Member';
                $insProf = $db->prepare("INSERT INTO employee_profiles (user_id, joining_date, skills, experience, bio) VALUES (?, '2024-01-01', ?, '4+ Years', ?)");
                $insProf->execute([$userId, $skills, $bio]);
            }

            // Ensure salary exists
            $salCheck = $db->prepare("SELECT id FROM employee_salaries WHERE user_id = ?");
            $salCheck->execute([$userId]);
            if (!$salCheck->fetch()) {
                $insSal = $db->prepare("INSERT INTO employee_salaries (user_id, monthly_salary, allowance) VALUES (?, ?, 0.00)");
                $insSal->execute([$userId, $acc['salary']]);
            } else {
                $updSal = $db->prepare("UPDATE employee_salaries SET monthly_salary = ? WHERE user_id = ?");
                $updSal->execute([$acc['salary'], $userId]);
            }
        } catch (Exception $e) {
            error_log("Account Sync Error for " . $acc['email'] . ": " . $e->getMessage());
        }
    }
}

function getEmployeeLeaveBalance(int $userId, string $monthYear): array {
    $db = Database::getConnection();
    ensurePayrollSchema($db);

    $totalAllowedPaid = 2.0;

    $startDate = "{$monthYear}-01";
    $daysInMonth = (int)date('t', strtotime($startDate));
    $endDate = "{$monthYear}-{$daysInMonth}";

    $stmt = $db->prepare("
        SELECT 
            SUM(CASE WHEN status = 'approved' THEN paid_days ELSE 0 END) as used_paid,
            SUM(CASE WHEN status = 'approved' THEN unpaid_days ELSE 0 END) as used_unpaid
        FROM leave_requests
        WHERE user_id = ? AND status = 'approved' AND start_date >= ? AND start_date <= ?
    ");
    $stmt->execute([$userId, $startDate, $endDate]);
    $res = $stmt->fetch();

    $usedPaid = (float)($res['used_paid'] ?? 0.0);
    $usedUnpaid = (float)($res['used_unpaid'] ?? 0.0);
    $remainingPaid = max(0.0, $totalAllowedPaid - $usedPaid);

    return [
        'monthly_quota' => $totalAllowedPaid,
        'used_paid' => $usedPaid,
        'used_unpaid' => $usedUnpaid,
        'remaining_paid' => $remainingPaid
    ];
}

function calculateAttendanceSalaryStats(int $userId, string $monthYear): array {
    $db = Database::getConnection();
    ensurePayrollSchema($db);

    $startDate = "{$monthYear}-01";
    $totalDaysInMonth = (int)date('t', strtotime($startDate));
    $endDate = "{$monthYear}-{$totalDaysInMonth}";

    $dtStart = new DateTime($startDate);
    $dtNext = (clone $dtStart)->modify('+1 month');
    $periodLabel = $dtStart->format('01 M Y') . ' → ' . $dtNext->format('01 M Y');

    $currentMonthStr = date('Y-m');
    if ($monthYear === $currentMonthStr) {
        $todayDay = (int)date('d');
        $completedDays = min($totalDaysInMonth, $todayDay);
    } else if (strcmp($monthYear, $currentMonthStr) < 0) {
        $completedDays = $totalDaysInMonth;
    } else {
        $completedDays = 0;
    }

    $remainingDays = max(0, $totalDaysInMonth - $completedDays);
    $payrollProgress = ($totalDaysInMonth > 0) ? round(($completedDays / $totalDaysInMonth) * 100, 1) : 0.0;

    $holidayStmt = $db->prepare("SELECT COUNT(*) FROM holidays WHERE date >= ? AND date <= ?");
    $holidayStmt->execute([$startDate, $endDate]);
    $holidayCount = (int)$holidayStmt->fetchColumn();

    $attStmt = $db->prepare("
        SELECT 
            COUNT(CASE WHEN status IN ('full_day', 'full day', 'present') THEN 1 END) as full_days,
            COUNT(CASE WHEN status IN ('half_day', 'half day') THEN 1 END) as half_days,
            COUNT(CASE WHEN status = 'absent' THEN 1 END) as explicit_absent_days,
            SUM(total_seconds) as total_seconds
        FROM attendance
        WHERE user_id = ? AND date >= ? AND date <= ?
    ");
    $attStmt->execute([$userId, $startDate, $endDate]);
    $att = $attStmt->fetch();

    $presentDays = (float)($att['full_days'] ?? 0);
    $halfDays = (float)($att['half_days'] ?? 0);
    $explicitAbsentDays = (float)($att['explicit_absent_days'] ?? 0);
    $totalSecondsWorked = (int)($att['total_seconds'] ?? 0);
    $workingHours = round($totalSecondsWorked / 3600, 2);

    $leaveStmt = $db->prepare("
        SELECT 
            SUM(paid_days) as paid_leave_days,
            SUM(unpaid_days) as unpaid_leave_days
        FROM leave_requests
        WHERE user_id = ? AND status = 'approved' AND start_date >= ? AND start_date <= ?
    ");
    $leaveStmt->execute([$userId, $startDate, $endDate]);
    $leaves = $leaveStmt->fetch();

    $paidLeaveDays = (float)($leaves['paid_leave_days'] ?? 0);
    $unpaidLeaveDays = (float)($leaves['unpaid_leave_days'] ?? 0);

    if ($completedDays > 0) {
        $daysAccounted = $presentDays + ($halfDays * 0.5) + $paidLeaveDays + $holidayCount;
        $absentDays = max(0.0, $completedDays - $daysAccounted);
    } else {
        $absentDays = 0.0;
    }

    if ($explicitAbsentDays > $absentDays) {
        $absentDays = $explicitAbsentDays;
    }

    return [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'period_label' => $periodLabel,
        'total_days_in_month' => $totalDaysInMonth,
        'completed_days' => $completedDays,
        'remaining_days' => $remainingDays,
        'payroll_progress' => $payrollProgress,
        'present_days' => $presentDays,
        'half_days' => $halfDays,
        'absent_days' => $absentDays,
        'paid_leave_days' => $paidLeaveDays,
        'unpaid_leave_days' => $unpaidLeaveDays,
        'holiday_days' => $holidayCount,
        'working_hours' => $workingHours
    ];
}

if (!function_exists('str_startswith')) {
    function str_startswith(string $haystack, string $needle): bool {
        if ($needle === '') return true;
        return (strpos($haystack, $needle) === 0);
    }
}

if (!function_exists('str_starts_with')) {
    function str_starts_with(string $haystack, string $needle): bool {
        if ($needle === '') return true;
        return (strpos($haystack, $needle) === 0);
    }
}

/**
 * ============================================================================
 * SHIFT TIMING, LIVE WORK STATUS, MEETINGS & ACTIVITY TIMELINE HELPERS
 * ============================================================================
 */

/**
 * Ensure all shift, activities, meetings, reasons, and report tables exist
 */
function ensureShiftAndActivitySchema(?PDO $db = null): void {
    if (!$db) {
        $db = Database::getConnection();
    }

    try {
        $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? Database::getDriver());
    } catch (Exception $e) {
        $driver = Database::getDriver();
    }
    $isSqlite = ($driver === 'sqlite');
    $autoInc = $isSqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
    $tableEngine = $isSqlite ? '' : ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

    // 1. Centralized shift_settings
    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS `shift_settings` (
                `id` {$autoInc},
                `shift_name` VARCHAR(100) NOT NULL DEFAULT 'Standard Agency Shift',
                `start_time` VARCHAR(10) NOT NULL DEFAULT '09:40',
                `end_time` VARCHAR(10) NOT NULL DEFAULT '18:30',
                `lunch_start` VARCHAR(10) NOT NULL DEFAULT '14:00',
                `lunch_end` VARCHAR(10) NOT NULL DEFAULT '15:00',
                `working_days` VARCHAR(255) NOT NULL DEFAULT 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
                `grace_period_minutes` INT NOT NULL DEFAULT 15,
                `reminder_interval_minutes` INT NOT NULL DEFAULT 30,
                `auto_lunch` TINYINT(1) NOT NULL DEFAULT 1,
                `allow_overlapping_meetings` TINYINT(1) NOT NULL DEFAULT 0,
                `meeting_default_duration` INT NOT NULL DEFAULT 30,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ){$tableEngine};
        ");

        $chk = $db->query("SELECT COUNT(*) FROM `shift_settings`")->fetchColumn();
        if ((int)$chk === 0) {
            $db->exec("
                INSERT INTO `shift_settings` 
                (`id`, `shift_name`, `start_time`, `end_time`, `lunch_start`, `lunch_end`, `working_days`, `grace_period_minutes`, `reminder_interval_minutes`, `auto_lunch`, `allow_overlapping_meetings`, `meeting_default_duration`)
                VALUES
                (1, 'Standard Agency Shift', '09:40', '18:30', '14:00', '15:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday', 15, 30, 1, 0, 30);
            ");
        }
    } catch (Exception $e) {
        error_log("ensureShiftAndActivitySchema shift_settings: " . $e->getMessage());
    }

    // 2. activity_reasons
    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS `activity_reasons` (
                `id` {$autoInc},
                `category` VARCHAR(50) NOT NULL DEFAULT 'general',
                `reason_text` VARCHAR(255) NOT NULL
            ){$tableEngine};
        ");

        // Seed rich client-related and operational waiting/blocked reasons
        $allReasons = [
            ['waiting', 'Waiting for client content / assets'],
            ['waiting', 'Waiting for client feedback / approval'],
            ['waiting', 'Waiting for client response / call'],
            ['waiting', 'Waiting for client payment / invoice clearance'],
            ['waiting', 'Waiting for client credentials / access'],
            ['waiting', 'Waiting for client requirements / brief'],
            ['waiting', 'Waiting for client review / meeting'],
            ['waiting', 'Waiting for manager / lead approval'],
            ['waiting', 'Waiting for design'],
            ['waiting', 'Waiting for developer'],
            ['waiting', 'Waiting for credentials/access'],
            ['waiting', 'Waiting for 3rd-party vendor / API'],
            ['blocked', 'Client revision requested'],
            ['blocked', 'Server/hosting issue'],
            ['blocked', 'Technical issue'],
            ['blocked', 'Payment issue'],
            ['blocked', 'Payment / contract blocker'],
            ['general', 'Other client dependency'],
            ['general', 'Other']
        ];
        
        $chkStmt = $db->prepare("SELECT COUNT(*) FROM `activity_reasons` WHERE `reason_text` = ?");
        $insStmt = $db->prepare("INSERT INTO `activity_reasons` (`category`, `reason_text`) VALUES (?, ?)");
        foreach ($allReasons as $r) {
            try {
                $chkStmt->execute([$r[1]]);
                if ((int)$chkStmt->fetchColumn() === 0) {
                    $insStmt->execute([$r[0], $r[1]]);
                }
            } catch (Exception $e) {}
        }
    } catch (Exception $e) {
        error_log("ensureShiftAndActivitySchema activity_reasons: " . $e->getMessage());
    }

    // 3. employee_activities
    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS `employee_activities` (
                `id` {$autoInc},
                `employee_id` INT NOT NULL,
                `project_id` INT NULL,
                `task_id` INT NULL,
                `activity_type` VARCHAR(100) NOT NULL,
                `status` VARCHAR(20) NOT NULL DEFAULT 'active',
                `start_time` DATETIME NOT NULL,
                `end_time` DATETIME NULL,
                `duration` INT DEFAULT 0,
                `reason` TEXT NULL,
                `notes` TEXT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ){$tableEngine};
        ");
        } catch (Exception $e) {
            error_log("ensureShiftAndActivitySchema employee_activities create: " . $e->getMessage());
        }

        // Ensure employee_activities columns exist
        $activityExtraCols = [
            'project_id' => 'INT NULL',
            'task_id' => 'INT NULL',
            'duration' => 'INT DEFAULT 0',
            'reason' => 'TEXT NULL',
            'notes' => 'TEXT NULL',
            'client_name' => 'VARCHAR(255) NULL',
            'status' => "VARCHAR(20) DEFAULT 'active'"
        ];
        foreach ($activityExtraCols as $col => $definition) {
            try {
                $db->exec("ALTER TABLE `employee_activities` ADD COLUMN `{$col}` {$definition}");
            } catch (Exception $ign) {}
        }

    // 4. meetings
    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS `meetings` (
                `id` {$autoInc},
                `created_by` INT NOT NULL,
                `employee_id` INT NOT NULL,
                `title` VARCHAR(255) NOT NULL,
                `description` TEXT NULL,
                `meeting_date` DATE NOT NULL,
                `start_time` VARCHAR(10) NOT NULL,
                `end_time` VARCHAR(10) NULL,
                `duration` INT DEFAULT 30,
                `duration_minutes` INT DEFAULT 30,
                `meeting_time` VARCHAR(10) NULL,
                `meeting_link` VARCHAR(255) NULL,
                `location_link` VARCHAR(255) NULL,
                `project_id` INT NULL,
                `client_name` VARCHAR(255) NULL,
                `meeting_type` VARCHAR(50) DEFAULT 'internal',
                `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
                `response_notes` TEXT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ){$tableEngine};
        ");

        } catch (Exception $e) {
            error_log("ensureShiftAndActivitySchema meetings create: " . $e->getMessage());
        }

        // Ensure columns exist if table was already created
        $meetingExtraCols = [
            'project_id' => 'INT NULL',
            'client_name' => 'VARCHAR(255) NULL',
            'meeting_type' => "VARCHAR(50) DEFAULT 'internal'",
            'response_notes' => 'TEXT NULL',
            'duration' => 'INT DEFAULT 30',
            'duration_minutes' => 'INT DEFAULT 30',
            'start_time' => "VARCHAR(10) DEFAULT '10:00'",
            'end_time' => 'VARCHAR(10) NULL',
            'meeting_link' => 'VARCHAR(255) NULL',
            'location_link' => 'VARCHAR(255) NULL',
            'description' => 'TEXT NULL',
            'status' => "VARCHAR(50) DEFAULT 'pending'",
            'updated_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
        ];
        foreach ($meetingExtraCols as $col => $definition) {
            try {
                $db->exec("ALTER TABLE `meetings` ADD COLUMN `{$col}` {$definition}");
            } catch (Exception $ign) {}
        }

    // 5. meeting_participants
    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS `meeting_participants` (
                `id` {$autoInc},
                `meeting_id` INT NOT NULL,
                `employee_id` INT NOT NULL,
                `status` VARCHAR(50) NOT NULL DEFAULT 'pending',
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ){$tableEngine};
        ");
    } catch (Exception $e) {
        error_log("ensureShiftAndActivitySchema meeting_participants: " . $e->getMessage());
    }

    // 6. daily_work_reports
    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS `daily_work_reports` (
                `id` {$autoInc},
                `employee_id` INT NOT NULL,
                `report_date` DATE NOT NULL,
                `shift_start` VARCHAR(10) DEFAULT '09:40',
                `shift_end` VARCHAR(10) DEFAULT '18:30',
                `actual_clock_in` DATETIME NULL,
                `actual_clock_out` DATETIME NULL,
                `working_seconds` INT DEFAULT 0,
                `meeting_seconds` INT DEFAULT 0,
                `lunch_seconds` INT DEFAULT 0,
                `waiting_seconds` INT DEFAULT 0,
                `blocked_seconds` INT DEFAULT 0,
                `tasks_completed` INT DEFAULT 0,
                `tasks_in_progress` INT DEFAULT 0,
                `projects_summary` TEXT NULL,
                `issues_summary` TEXT NULL,
                `task_details` TEXT NULL,
                `notes` TEXT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            ){$tableEngine};
        ");
    } catch (Exception $e) {
        error_log("ensureShiftAndActivitySchema daily_work_reports: " . $e->getMessage());
    }
}

/**
 * Fetch centralized office shift settings
 */
function getShiftSettings(?PDO $db = null): array {
    if (!$db) {
        $db = Database::getConnection();
    }
    ensureShiftAndActivitySchema($db);

    $defaults = [
        'id' => 1,
        'shift_name' => 'Standard Agency Shift',
        'start_time' => '09:40',
        'end_time' => '18:30',
        'lunch_start' => '14:00',
        'lunch_end' => '15:00',
        'working_days' => 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
        'grace_period_minutes' => 15,
        'reminder_interval_minutes' => 30,
        'auto_lunch' => 1,
        'allow_overlapping_meetings' => 0,
        'meeting_default_duration' => 30
    ];

    try {
        $stmt = $db->query("SELECT * FROM `shift_settings` ORDER BY id ASC LIMIT 1");
        $row = $stmt->fetch();
        if ($row) {
            return array_merge($defaults, $row);
        }
    } catch (Exception $e) {
        error_log("getShiftSettings error: " . $e->getMessage());
    }

    return $defaults;
}

/**
 * Check if given time is in Lunch Window according to configured shift settings
 */
function isShiftLunchWindow($datetime = 'now', ?array $settings = null): bool {
    if ($settings === null) {
        $settings = getShiftSettings();
    }

    try {
        $dt = is_numeric($datetime) ? (new DateTime())->setTimestamp((int)$datetime) : new DateTime($datetime);
        $dt->setTimezone(new DateTimeZone('Asia/Kolkata'));
        $currentTime = $dt->format('H:i');

        $lStart = $settings['lunch_start'] ?? '14:00';
        $lEnd = $settings['lunch_end'] ?? '15:00';

        return ($currentTime >= $lStart && $currentTime < $lEnd);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get currently active activity for an employee
 */
function getEmployeeCurrentActivity(PDO $db, int $userId): ?array {
    ensureShiftAndActivitySchema($db);

    try {
        $stmt = $db->prepare("
            SELECT ea.*, p.name as project_name, p.code as project_code, t.title as task_title, t.status as task_status
            FROM employee_activities ea
            LEFT JOIN projects p ON ea.project_id = p.id
            LEFT JOIN tasks t ON ea.task_id = t.id
            WHERE ea.employee_id = ? AND ea.status = 'active'
            ORDER BY ea.id DESC LIMIT 1
        ");
        $stmt->execute([$userId]);
        $act = $stmt->fetch();

        if ($act) {
            $startTimestamp = strtotime($act['start_time']);
            $act['elapsed_seconds'] = max(0, time() - $startTimestamp);
            $act['start_time_formatted'] = formatISTTime($act['start_time']);
            $act['elapsed_formatted'] = formatDuration($act['elapsed_seconds']);
            return $act;
        }
    } catch (Exception $e) {
        error_log("getEmployeeCurrentActivity error: " . $e->getMessage());
    }

    return null;
}

/**
 * Close any active activity for employee
 */
function closeEmployeeCurrentActivity(PDO $db, int $userId, ?string $endTimeStr = null): ?array {
    ensureShiftAndActivitySchema($db);

    try {
        $stmt = $db->prepare("SELECT * FROM employee_activities WHERE employee_id = ? AND status = 'active' ORDER BY id DESC");
        $stmt->execute([$userId]);
        $actives = $stmt->fetchAll();

        $closed = null;
        $nowStr = $endTimeStr ?? date('Y-m-d H:i:s');
        $nowTs = strtotime($nowStr);

        foreach ($actives as $act) {
            $startTs = strtotime($act['start_time']);
            $duration = max(0, $nowTs - $startTs);

            $upd = $db->prepare("UPDATE employee_activities SET status = 'completed', end_time = ?, duration = ?, updated_at = ? WHERE id = ?");
            $upd->execute([$nowStr, $duration, $nowStr, $act['id']]);

            $act['end_time'] = $nowStr;
            $act['duration'] = $duration;
            $act['status'] = 'completed';
            $closed = $act;
        }

        return $closed;
    } catch (Exception $e) {
        error_log("closeEmployeeCurrentActivity error: " . $e->getMessage());
        return null;
    }
}

/**
 * Start a new activity for an employee, automatically closing previous active activity
 */
function startEmployeeActivity(PDO $db, int $userId, string $activityType, ?int $projectId = null, ?int $taskId = null, ?string $reason = null, ?string $notes = null, ?string $clientName = null): array {
    ensureShiftAndActivitySchema($db);

    // 1. Close previously active activity
    closeEmployeeCurrentActivity($db, $userId);

    // 2. If task provided without project, look up project_id
    if ($taskId && !$projectId) {
        try {
            $tStmt = $db->prepare("SELECT project_id FROM tasks WHERE id = ?");
            $tStmt->execute([$taskId]);
            $projectId = (int)$tStmt->fetchColumn() ?: null;
        } catch (Exception $e) {}
    }

    // 3. If task is provided, update task state to 'running'
    if ($taskId) {
        try {
            $db->prepare("UPDATE tasks SET status = 'running' WHERE id = ? AND status NOT IN ('completed')")->execute([$taskId]);
        } catch (Exception $e) {}
    }

    $nowStr = date('Y-m-d H:i:s');

    $ins = $db->prepare("
        INSERT INTO employee_activities 
        (employee_id, project_id, task_id, activity_type, status, start_time, duration, reason, notes, client_name, created_at, updated_at)
        VALUES (?, ?, ?, ?, 'active', ?, 0, ?, ?, ?, ?, ?)
    ");
    $ins->execute([$userId, $projectId, $taskId, $activityType, $nowStr, $reason, $notes, $clientName, $nowStr, $nowStr]);
    $newId = (int)$db->lastInsertId();

    logActivity('Activity Started', 'Workflow', "Employee User #{$userId} started activity '{$activityType}'" . ($taskId ? " on Task #{$taskId}" : ''));

    return [
        'id' => $newId,
        'employee_id' => $userId,
        'activity_type' => $activityType,
        'project_id' => $projectId,
        'task_id' => $taskId,
        'start_time' => $nowStr,
        'start_time_formatted' => formatISTTime($nowStr),
        'reason' => $reason,
        'notes' => $notes,
        'client_name' => $clientName,
        'status' => 'active'
    ];
}

/**
 * Standard Status metadata (color, icon, badge)
 */
function getStatusMeta(string $status): array {
    $clean = strtolower(trim($status));
    switch ($clean) {
        case 'working':
            return [
                'name' => 'Working',
                'color' => '#10b981',
                'icon' => 'fas fa-circle',
                'pulse' => true,
                'emoji' => '🟢',
                'badge_class' => 'bg-success text-white'
            ];
        case 'meeting':
        case 'in meeting':
            return [
                'name' => 'In Meeting',
                'color' => '#8b5cf6',
                'icon' => 'fas fa-video',
                'pulse' => true,
                'emoji' => '🟣',
                'badge_class' => 'bg-purple text-white'
            ];
        case 'lunch':
        case 'lunch break':
            return [
                'name' => 'Lunch',
                'color' => '#f97316',
                'icon' => 'fas fa-utensils',
                'pulse' => false,
                'emoji' => '🟠',
                'badge_class' => 'bg-warning text-dark'
            ];
        case 'waiting':
            return [
                'name' => 'Waiting',
                'color' => '#eab308',
                'icon' => 'fas fa-hourglass-half',
                'pulse' => false,
                'emoji' => '🟡',
                'badge_class' => 'bg-warning-subtle text-dark border border-warning'
            ];
        case 'blocked':
            return [
                'name' => 'Blocked',
                'color' => '#ef4444',
                'icon' => 'fas fa-ban',
                'pulse' => false,
                'emoji' => '🔴',
                'badge_class' => 'bg-danger text-white'
            ];
        case 'shift ended':
        case 'shift completed':
            return [
                'name' => 'Shift Ended',
                'color' => '#1e293b',
                'icon' => 'fas fa-check-double',
                'pulse' => false,
                'emoji' => '⚫',
                'badge_class' => 'bg-dark text-white'
            ];
        case 'on leave':
            return [
                'name' => 'On Leave',
                'color' => '#06b6d4',
                'icon' => 'fas fa-plane-departure',
                'pulse' => false,
                'emoji' => '🔵',
                'badge_class' => 'bg-info text-white'
            ];
        default:
            return [
                'name' => 'Offline',
                'color' => '#94a3b8',
                'icon' => 'fas fa-power-off',
                'pulse' => false,
                'emoji' => '⚪',
                'badge_class' => 'bg-secondary text-white'
            ];
    }
}

/**
 * Get comprehensive live status for an employee
 */
function getEmployeeStatusDetails(PDO $db, int $userId): array {
    ensureShiftAndActivitySchema($db);
    $settings = getShiftSettings($db);
    $today = date('Y-m-d');
    $nowTs = time();

    // 1. Fetch today's attendance record
    $attStmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date = ? ORDER BY id DESC LIMIT 1");
    $attStmt->execute([$userId, $userId, $today]);
    $attendance = $attStmt->fetch();

    $isShiftActive = ($attendance && empty($attendance['clock_out']));
    $isShiftEnded = ($attendance && !empty($attendance['clock_out']));
    $clockInTime = $attendance ? $attendance['clock_in'] : null;
    $clockOutTime = $attendance ? $attendance['clock_out'] : null;
    $lateMinutes = $attendance ? (int)($attendance['late_minutes'] ?? 0) : 0;

    // Check Leave
    $isLeave = false;
    try {
        $lStmt = $db->prepare("SELECT COUNT(*) FROM leave_requests WHERE user_id = ? AND status = 'approved' AND start_date <= ? AND end_date >= ?");
        $lStmt->execute([$userId, $today, $today]);
        $isLeave = ((int)$lStmt->fetchColumn() > 0);
    } catch (Exception $e) {}

    // 2. Fetch current active activity
    $currentActivity = getEmployeeCurrentActivity($db, $userId);

    // 3. Determine status
    $statusKey = 'Offline';
    if ($isLeave) {
        $statusKey = 'On Leave';
    } elseif (!$attendance) {
        $statusKey = 'Offline';
    } elseif ($isShiftEnded) {
        $statusKey = 'Shift Ended';
    } elseif ($isShiftActive) {
        // Shift is active
        $actType = $currentActivity ? strtolower(trim($currentActivity['activity_type'])) : '';
        $actReason = $currentActivity ? strtolower(trim($currentActivity['reason'] ?? '')) : '';

        if ($actType === 'meeting' || str_contains($actType, 'meeting')) {
            $statusKey = 'In Meeting';
        } elseif (str_contains($actType, 'waiting') || str_contains($actReason, 'waiting')) {
            $statusKey = 'Waiting';
        } elseif (str_contains($actType, 'blocked') || str_contains($actType, 'technical issue') || str_contains($actReason, 'issue') || str_contains($actReason, 'blocked')) {
            $statusKey = 'Blocked';
        } elseif ($settings['auto_lunch'] && isShiftLunchWindow('now', $settings)) {
            $statusKey = 'Lunch';
        } else {
            $statusKey = 'Working';
        }
    }

    $meta = getStatusMeta($statusKey);

    return [
        'user_id' => $userId,
        'status_key' => $statusKey,
        'status_display' => $meta['name'],
        'status_emoji' => $meta['emoji'],
        'status_color' => $meta['color'],
        'status_badge_class' => $meta['badge_class'],
        'is_shift_active' => $isShiftActive,
        'is_shift_ended' => $isShiftEnded,
        'is_lunch_now' => isShiftLunchWindow('now', $settings),
        'attendance' => $attendance,
        'clock_in_time' => $clockInTime ? formatISTTime($clockInTime) : null,
        'clock_out_time' => $clockOutTime ? formatISTTime($clockOutTime) : null,
        'late_minutes' => $lateMinutes,
        'is_late' => ($lateMinutes > 0),
        'current_activity' => $currentActivity,
        'shift_settings' => $settings
    ];
}

/**
 * Calculate total breakdown of working, meeting, lunch, waiting, blocked seconds for employee today
 */
function calculateEmployeeDailyDurations(PDO $db, int $userId, string $date): array {
    ensureShiftAndActivitySchema($db);

    $sql = "
        SELECT ea.*, p.name as project_name, t.title as task_title
        FROM employee_activities ea
        LEFT JOIN projects p ON ea.project_id = p.id
        LEFT JOIN tasks t ON ea.task_id = t.id
        WHERE ea.employee_id = ? AND DATE(ea.start_time) = ?
        ORDER BY ea.id ASC
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([$userId, $date]);
    $activities = $stmt->fetchAll();

    $nowTs = time();
    $workingSecs = 0;
    $meetingSecs = 0;
    $lunchSecs = 0;
    $waitingSecs = 0;
    $blockedSecs = 0;

    $projectsMap = [];
    $issuesList = [];

    foreach ($activities as $act) {
        $startTs = strtotime($act['start_time']);
        $endTs = !empty($act['end_time']) ? strtotime($act['end_time']) : $nowTs;
        $dur = max(0, $endTs - $startTs);

        $type = strtolower(trim($act['activity_type']));
        $reason = strtolower(trim($act['reason'] ?? ''));

        if ($type === 'meeting' || str_contains($type, 'meeting')) {
            $meetingSecs += $dur;
        } elseif ($type === 'lunch' || str_contains($type, 'lunch')) {
            $lunchSecs += $dur;
        } elseif (str_contains($type, 'waiting') || str_contains($reason, 'waiting')) {
            $waitingSecs += $dur;
            if (!empty($act['reason']) && !in_array($act['reason'], $issuesList)) {
                $issuesList[] = $act['reason'];
            }
        } elseif (str_contains($type, 'blocked') || str_contains($type, 'technical issue') || str_contains($reason, 'blocked') || str_contains($reason, 'issue')) {
            $blockedSecs += $dur;
            if (!empty($act['reason']) && !in_array($act['reason'], $issuesList)) {
                $issuesList[] = $act['reason'];
            }
        } else {
            // Working activity (Task, Development, Designing, Testing, Client Communication, etc.)
            $workingSecs += $dur;
        }

        if (!empty($act['project_name']) && !in_array($act['project_name'], $projectsMap)) {
            $projectsMap[] = $act['project_name'];
        }
    }

    // Also check attendance record for overall shift duration
    $attStmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date = ? ORDER BY id DESC LIMIT 1");
    $attStmt->execute([$userId, $userId, $date]);
    $attendance = $attStmt->fetch();

    $actualClockIn = $attendance ? $attendance['clock_in'] : null;
    $actualClockOut = $attendance ? $attendance['clock_out'] : null;

    // Count tasks completed and in progress for employee
    $tasksCompletedStmt = $db->prepare("
        SELECT COUNT(*) FROM tasks 
        WHERE assigned_to = ? AND status = 'completed' AND (DATE(updated_at) = ? OR id IN (SELECT task_id FROM task_logs WHERE user_id = ? AND log_date = ?))
    ");
    $tasksCompletedStmt->execute([$userId, $date, $userId, $date]);
    $tasksCompleted = (int)$tasksCompletedStmt->fetchColumn();

    $tasksInProgressStmt = $db->prepare("SELECT COUNT(*) FROM tasks WHERE assigned_to = ? AND status IN ('running', 'in_progress', 'paused')");
    $tasksInProgressStmt->execute([$userId]);
    $tasksInProgress = (int)$tasksInProgressStmt->fetchColumn();

    return [
        'employee_id' => $userId,
        'report_date' => $date,
        'working_seconds' => $workingSecs,
        'meeting_seconds' => $meetingSecs,
        'lunch_seconds' => $lunchSecs,
        'waiting_seconds' => $waitingSecs,
        'blocked_seconds' => $blockedSecs,
        'working_time_formatted' => formatDuration($workingSecs),
        'meeting_time_formatted' => formatDuration($meetingSecs),
        'lunch_time_formatted' => formatDuration($lunchSecs),
        'waiting_time_formatted' => formatDuration($waitingSecs),
        'blocked_time_formatted' => formatDuration($blockedSecs),
        'tasks_completed' => $tasksCompleted,
        'tasks_in_progress' => $tasksInProgress,
        'projects' => $projectsMap,
        'issues' => $issuesList,
        'actual_clock_in' => $actualClockIn,
        'actual_clock_out' => $actualClockOut,
        'activities_count' => count($activities)
    ];
}

/**
 * Generate or update Daily Work Summary Report
 */
function generateDailyWorkReport(PDO $db, int $userId, string $date): array {
    ensureShiftAndActivitySchema($db);
    $settings = getShiftSettings($db);
    $durations = calculateEmployeeDailyDurations($db, $userId, $date);

    $projSummary = json_encode($durations['projects']);
    $issueSummary = !empty($durations['issues']) ? implode('; ', $durations['issues']) : 'None reported';

    try {
        $chk = $db->prepare("SELECT id FROM daily_work_reports WHERE employee_id = ? AND report_date = ?");
        $chk->execute([$userId, $date]);
        $existingId = $chk->fetchColumn();

        $nowStr = date('Y-m-d H:i:s');
        if ($existingId) {
            $upd = $db->prepare("
                UPDATE daily_work_reports SET
                    shift_start = ?, shift_end = ?, actual_clock_in = ?, actual_clock_out = ?,
                    working_seconds = ?, meeting_seconds = ?, lunch_seconds = ?, waiting_seconds = ?, blocked_seconds = ?,
                    tasks_completed = ?, tasks_in_progress = ?, projects_summary = ?, issues_summary = ?, updated_at = ?
                WHERE id = ?
            ");
            $upd->execute([
                $settings['start_time'], $settings['end_time'], $durations['actual_clock_in'], $durations['actual_clock_out'],
                $durations['working_seconds'], $durations['meeting_seconds'], $durations['lunch_seconds'], $durations['waiting_seconds'], $durations['blocked_seconds'],
                $durations['tasks_completed'], $durations['tasks_in_progress'], $projSummary, $issueSummary, $nowStr, $existingId
            ]);
            $reportId = (int)$existingId;
        } else {
            $ins = $db->prepare("
                INSERT INTO daily_work_reports 
                (employee_id, report_date, shift_start, shift_end, actual_clock_in, actual_clock_out,
                 working_seconds, meeting_seconds, lunch_seconds, waiting_seconds, blocked_seconds,
                 tasks_completed, tasks_in_progress, projects_summary, issues_summary, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $ins->execute([
                $userId, $date, $settings['start_time'], $settings['end_time'], $durations['actual_clock_in'], $durations['actual_clock_out'],
                $durations['working_seconds'], $durations['meeting_seconds'], $durations['lunch_seconds'], $durations['waiting_seconds'], $durations['blocked_seconds'],
                $durations['tasks_completed'], $durations['tasks_in_progress'], $projSummary, $issueSummary, $nowStr, $nowStr
            ]);
            $reportId = (int)$db->lastInsertId();
        }

        $durations['report_id'] = $reportId;
        return $durations;
    } catch (Exception $e) {
        error_log("generateDailyWorkReport error: " . $e->getMessage());
        return $durations;
    }
}





