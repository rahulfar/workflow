<?php
/**
 * Test all primary web routes via HTTP through local server
 */

$routes = [
    '/' => 200,
    '/login' => 200,
    '/logout' => 302,
    '/forgot-password' => 200,
    '/reset-password' => 200,
    '/dashboard' => 200,
    '/admin' => 200,
    '/admin/dashboard' => 200,
    '/manager' => 200,
    '/manager/dashboard' => 200,
    '/product-manager' => 200,
    '/product-manager/dashboard' => 200,
    '/employee' => 200,
    '/employee/dashboard' => 200,
    '/employees' => 200,
    '/employe' => 200,
    '/tasks' => 200,
    '/task-details' => 200,
    '/kanban' => 200,
    '/work-log' => 200,
    '/activity' => 200,
    '/notifications' => 200,
    '/help' => 200,
    '/projects' => 200,
    '/departments' => 200,
    '/emergency-approvals' => 200,
    '/flagged-tasks' => 200,
    '/payroll' => 200,
    '/attendance' => 200,
    '/leaves' => 200,
    '/calendar' => 200,
    '/reports' => 200,
    '/files' => 200,
    '/workday' => 200,
    '/live-status' => 200,
    '/employee-timeline' => 200,
    '/shift-settings' => 200,
    '/ip-settings' => 200,
    '/settings' => 200,
    '/profile' => 200,
    '/chat' => 200,
    '/mobile-shift' => 200,
    '/mcp' => 200
];

require_once __DIR__ . '/../php_app/config/database.php';
$db = Database::getConnection();

// Create fresh session for Admin (user 1)
$adminSess = "httptestadmin01";
$temp = sys_get_temp_dir();
$userAdmin = $db->query("SELECT u.*, r.name as role_name, r.display_name as role_title, d.name as department_name, d.code as department_code FROM users u JOIN roles r ON u.role_id = r.id JOIN departments d ON u.department_id = d.id WHERE u.id = 1")->fetch();

session_id($adminSess);
session_start();
$_SESSION['user_id'] = (int)$userAdmin['id'];
$_SESSION['role'] = 'admin';
$_SESSION['user'] = $userAdmin;
$_SESSION['last_activity'] = time();
session_write_close();

echo "========================================================\n";
echo "WORKFLOW PRO - HTTP PAGE VERIFICATION SUITE (LOCAL)\n";
echo "========================================================\n\n";

$pass = 0;
$fail = 0;

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID={$adminSess}");
curl_setopt($ch, CURLOPT_TIMEOUT, 5);

foreach ($routes as $route => $expectedStatus) {
    if ($route === '/logout') {
        // Do not consume admin session on /logout test
        $sessHeader = "PHPSESSID=logouttestsess01";
    } else {
        $sessHeader = "PHPSESSID={$adminSess}";
    }
    
    curl_setopt($ch, CURLOPT_COOKIE, $sessHeader);
    curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8000" . $route);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    $isOk = ($httpCode === $expectedStatus);
    
    // Check for PHP errors in body
    $hasPhpError = false;
    if (strpos($body, 'Fatal error') !== false || strpos($body, 'Parse error') !== false || strpos($body, 'Uncaught Exception') !== false) {
        $hasPhpError = true;
        $isOk = false;
    }

    if ($isOk) {
        $pass++;
        echo sprintf("  [PASS] %-30s : HTTP %d (Body: %d bytes)\n", $route, $httpCode, strlen($body));
    } else {
        $fail++;
        $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        echo sprintf("  [FAIL] %-30s : HTTP %d (Expected %d) %s\n", $route, $httpCode, $expectedStatus, $redirectUrl ? "-> " . $redirectUrl : "");
        if ($hasPhpError) {
            echo "         PHP ERROR IN BODY:\n" . substr($body, 0, 300) . "\n";
        }
    }
}

curl_close($ch);

echo "\n--------------------------------------------------------\n";
echo "SUMMARY: {$pass} PASSED, {$fail} FAILED (TOTAL " . count($routes) . " ROUTES)\n";
echo "--------------------------------------------------------\n";
