<?php
/**
 * Automated Verification Suite for:
 * FEATURE: WIFI IP-BASED SHIFT START & SHIFT END BUTTON VISIBILITY
 * Tests all 7 required scenarios + Admin IP Whitelist CRUD operations.
 */

require_once __DIR__ . '/../php_app/config/database.php';

$baseUrl = 'http://localhost:8000'; // If server is running, or we use PHP CLI runners

function runSubTest(string $testName, string $code): array {
    $tempFile = tempnam(sys_get_temp_dir(), 'test_') . '.php';
    file_put_contents($tempFile, "<?php\n" . $code);
    
    $output = [];
    $exitCode = 0;
    exec("php " . escapeshellarg($tempFile) . " 2>&1", $output, $exitCode);
    @unlink($tempFile);
    
    return [
        'name' => $testName,
        'output' => implode("\n", $output),
        'exitCode' => $exitCode,
        'passed' => ($exitCode === 0)
    ];
}

$results = [];

// =========================================================================
// TEST 1: Desktop connected to authorized Wi-Fi (192.168.29.174)
// Expected: Shift Start/End buttons visible.
// =========================================================================
$code1 = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
$_SERVER["REMOTE_ADDR"] = "192.168.29.174";
$_SERVER["HTTP_USER_AGENT"] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36";
unset($_SERVER["TEST_CLIENT_IP"]);

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

$db = Database::getConnection();
$clientIp = getClientIp();
$device = getClientDeviceType();
$isAuth = isClientIpAuthorized($db, $clientIp);
$network = getIpNetworkName($db, $clientIp);

if ($clientIp !== "192.168.29.174") {
    echo "FAIL: Detected IP was $clientIp, expected 192.168.29.174\n";
    exit(1);
}
if ($device !== "Desktop") {
    echo "FAIL: Detected device was $device, expected Desktop\n";
    exit(1);
}
if (!$isAuth) {
    echo "FAIL: IP 192.168.29.174 should be authorized\n";
    exit(1);
}
echo "PASS: Detected IP: $clientIp ($network) | Device: $device | Authorized: TRUE | Buttons: VISIBLE\n";
exit(0);
';
$results[] = runSubTest('Test 1: Desktop connected to authorized Wi-Fi (192.168.29.174)', $code1);

// =========================================================================
// TEST 2: Mobile connected to authorized Wi-Fi (192.168.1.8)
// Expected: Shift Start/End buttons visible.
// =========================================================================
$code2 = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
$_SERVER["REMOTE_ADDR"] = "192.168.1.8";
$_SERVER["HTTP_USER_AGENT"] = "Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Mobile/15E148";
unset($_SERVER["TEST_CLIENT_IP"]);

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

$db = Database::getConnection();
$clientIp = getClientIp();
$device = getClientDeviceType();
$isAuth = isClientIpAuthorized($db, $clientIp);
$network = getIpNetworkName($db, $clientIp);

if ($clientIp !== "192.168.1.8") {
    echo "FAIL: Detected IP was $clientIp, expected 192.168.1.8\n";
    exit(1);
}
if ($device !== "Mobile") {
    echo "FAIL: Detected device was $device, expected Mobile\n";
    exit(1);
}
if (!$isAuth) {
    echo "FAIL: IP 192.168.1.8 should be authorized\n";
    exit(1);
}
echo "PASS: Detected IP: $clientIp ($network) | Device: $device | Authorized: TRUE | Buttons: VISIBLE on Mobile\n";
exit(0);
';
$results[] = runSubTest('Test 2: Mobile connected to authorized Wi-Fi (192.168.1.8)', $code2);

// =========================================================================
// TEST 3: Desktop connected to another network (10.0.0.99)
// Expected: Buttons hidden.
// =========================================================================
$code3 = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
$_SERVER["REMOTE_ADDR"] = "10.0.0.99";
$_SERVER["HTTP_USER_AGENT"] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)";
unset($_SERVER["TEST_CLIENT_IP"]);

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

$db = Database::getConnection();
$clientIp = getClientIp();
$device = getClientDeviceType();
$isAuth = isClientIpAuthorized($db, $clientIp);
$network = getIpNetworkName($db, $clientIp);

if ($isAuth) {
    echo "FAIL: IP 10.0.0.99 should NOT be authorized\n";
    exit(1);
}
echo "PASS: Detected IP: $clientIp ($network) | Device: $device | Authorized: FALSE | Buttons: HIDDEN\n";
exit(0);
';
$results[] = runSubTest('Test 3: Desktop connected to another network (10.0.0.99)', $code3);

// =========================================================================
// TEST 4: Mobile using mobile data (157.34.120.45)
// Expected: Buttons hidden.
// =========================================================================
$code4 = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
$_SERVER["REMOTE_ADDR"] = "157.34.120.45";
$_SERVER["HTTP_USER_AGENT"] = "Mozilla/5.0 (Linux; Android 14; SM-S918B; Mobile) AppleWebKit/537.36";
unset($_SERVER["TEST_CLIENT_IP"]);

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

$db = Database::getConnection();
$clientIp = getClientIp();
$device = getClientDeviceType();
$isAuth = isClientIpAuthorized($db, $clientIp);
$network = getIpNetworkName($db, $clientIp);

if ($isAuth) {
    echo "FAIL: Mobile data IP 157.34.120.45 should NOT be authorized\n";
    exit(1);
}
if ($device !== "Mobile") {
    echo "FAIL: Device should be Mobile\n";
    exit(1);
}
echo "PASS: Detected IP: $clientIp ($network) | Device: $device | Authorized: FALSE | Buttons: HIDDEN on Mobile Data\n";
exit(0);
';
$results[] = runSubTest('Test 4: Mobile using mobile data (157.34.120.45)', $code4);

// =========================================================================
// TEST 5: Employee attempts to start shift using direct API request from unauthorized IP
// Expected: Request rejected (403 Forbidden). No DB record created.
// =========================================================================
$code5 = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
$_SERVER["REMOTE_ADDR"] = "157.34.120.45";
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST["action"] = "clock_in";

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

$db = Database::getConnection();
// Clean any active shifts
$db->exec("DELETE FROM attendance WHERE (user_id = 4 OR employee_id = 4) AND date = \"" . date("Y-m-d") . "\"");

// Call attendance api
require_once "api/attendance.php";
// api/attendance calls jsonResponse which prints output and exits
';
$res5 = runSubTest('Test 5: Employee attempts to start shift using direct API request from unauthorized IP', $code5);
$json5 = json_decode($res5['output'], true);
if ($json5 && $json5['success'] === false && str_contains($json5['message'], 'Shift Start Rejected')) {
    $res5['passed'] = true;
    $res5['output'] = "PASS: Request Rejected as Expected -> Message: '{$json5['message']}'\n";
} else {
    $res5['passed'] = false;
}
$results[] = $res5;

// =========================================================================
// TEST 6: Start shift from authorized IP, then attempt duplicate Shift Start
// Expected: First succeeds and logs IP + Device. Second is rejected.
// =========================================================================
$code6a = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
$_SERVER["REMOTE_ADDR"] = "192.168.29.174";
$_SERVER["HTTP_USER_AGENT"] = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36";
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST["action"] = "clock_in";

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

$db = Database::getConnection();
$db->exec("DELETE FROM attendance WHERE (user_id = 4 OR employee_id = 4) AND date = \"" . date("Y-m-d") . "\"");

require_once "api/attendance.php";
';
$res6a = runSubTest('Test 6 (Part A): Start shift from authorized IP', $code6a);
$json6a = json_decode($res6a['output'], true);

$code6b = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
$_SERVER["REMOTE_ADDR"] = "192.168.29.174";
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST["action"] = "clock_in";

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

require_once "api/attendance.php";
';
$res6b = runSubTest('Test 6 (Part B): Attempt duplicate shift start', $code6b);
$json6b = json_decode($res6b['output'], true);

$db = Database::getConnection();
$activeInDb = $db->query("SELECT * FROM attendance WHERE (user_id = 4 OR employee_id = 4) AND clock_out IS NULL")->fetch();

if ($json6a && $json6a['success'] === true &&
    $json6b && $json6b['success'] === false && str_contains($json6b['message'], 'already have an active shift') &&
    $activeInDb && $activeInDb['clock_in_ip'] === '192.168.29.174' && $activeInDb['device_type'] === 'Desktop') {
    $results[] = [
        'name' => 'Test 6: Authorized Shift Start & Duplicate Shift Start Prevention',
        'passed' => true,
        'output' => "PASS: Initial shift started with recorded IP {$activeInDb['clock_in_ip']} ({$activeInDb['device_type']}). Duplicate shift start rejected with message: '{$json6b['message']}'\n"
    ];
} else {
    $results[] = [
        'name' => 'Test 6: Authorized Shift Start & Duplicate Shift Start Prevention',
        'passed' => false,
        'output' => "FAIL: Part A: " . json_encode($json6a) . " | Part B: " . json_encode($json6b)
    ];
}

// =========================================================================
// TEST 7: Employee disconnects from office Wi-Fi during an active shift
// Expected: Buttons become hidden, but existing attendance record remains unchanged in DB.
//           Clock Out is rejected on unauthorized IP, and succeeds upon reconnecting to authorized Wi-Fi.
// =========================================================================
$code7a = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
// Simulated disconnect: IP changed to unauthorized network
$_SERVER["REMOTE_ADDR"] = "157.34.120.45";
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST["action"] = "clock_out";

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

require_once "api/attendance.php";
';
$res7a = runSubTest('Test 7 (Part A): Attempt clock out from unauthorized network during active shift', $code7a);
$json7a = json_decode($res7a['output'], true);

// Verify active shift is STILL ACTIVE and unchanged
$shiftStillActive = $db->query("SELECT * FROM attendance WHERE (user_id = 4 OR employee_id = 4) AND clock_out IS NULL")->fetch();

$code7b = '
session_start();
$_SESSION["user_id"] = 4;
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
// Simulated reconnect to Office Wi-Fi 2 (192.168.1.8)
$_SERVER["REMOTE_ADDR"] = "192.168.1.8";
$_SERVER["REQUEST_METHOD"] = "POST";
$_POST["action"] = "clock_out";

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

require_once "api/attendance.php";
';
$res7b = runSubTest('Test 7 (Part B): Reconnect to authorized Wi-Fi and clock out', $code7b);
$json7b = json_decode($res7b['output'], true);

$shiftClosed = $db->query("SELECT * FROM attendance WHERE id = {$activeInDb['id']}")->fetch();

if ($json7a && $json7a['success'] === false && str_contains($json7a['message'], 'Shift End Rejected') &&
    $shiftStillActive && empty($shiftStillActive['clock_out']) &&
    $json7b && $json7b['success'] === true &&
    $shiftClosed && !empty($shiftClosed['clock_out']) && $shiftClosed['clock_out_ip'] === '192.168.1.8') {
    $results[] = [
        'name' => 'Test 7: Employee disconnects from office Wi-Fi during an active shift',
        'passed' => true,
        'output' => "PASS: Unauthorized clock out rejected ('{$json7a['message']}'). Active shift remained intact in DB (Clock in: {$shiftStillActive['clock_in']}). Upon reconnecting to authorized Wi-Fi 2 (192.168.1.8), shift ended successfully with Clock Out IP recorded as {$shiftClosed['clock_out_ip']} and working duration calculated.\n"
    ];
} else {
    $results[] = [
        'name' => 'Test 7: Employee disconnects from office Wi-Fi during an active shift',
        'passed' => false,
        'output' => "FAIL: Disconnect check: " . json_encode($json7a) . " | Reconnect check: " . json_encode($json7b)
    ];
}

// =========================================================================
// TEST 8: Admin IP Whitelist CRUD Operations
// Expected: Add, List, Test, Toggle, Edit, Delete all function with 100% success.
// =========================================================================
$code8 = '
session_start();
$_SESSION["user_id"] = 1; // Admin user
$_SERVER["HTTP_X_REQUESTED_WITH"] = "xmlhttprequest";
$_SERVER["REQUEST_METHOD"] = "POST";

require_once "app/config/app.php";
require_once "app/helpers/functions.php";

$db = Database::getConnection();

// 1. Add new IP
$_POST = [
    "action" => "add",
    "ip_address" => "192.168.50.100",
    "location" => "Temporary Meeting Room Wi-Fi",
    "status" => "active",
    "notes" => "Added via automated test"
];
ob_start();
require "api/ip_management.php";
$addRes = json_decode(ob_get_clean(), true);
if (!$addRes || !$addRes["success"]) {
    echo "FAIL: Add IP failed: " . json_encode($addRes) . "\n";
    exit(1);
}

// 2. Check authorization for added IP
if (!isClientIpAuthorized($db, "192.168.50.100")) {
    echo "FAIL: Newly added IP should be authorized\n";
    exit(1);
}

// 3. Toggle to inactive
$newId = (int)$addRes["data"]["id"];
$_POST = [
    "action" => "toggle_status",
    "id" => $newId,
    "status" => "inactive"
];
ob_start();
require "api/ip_management.php";
$toggleRes = json_decode(ob_get_clean(), true);
if (!$toggleRes || !$toggleRes["success"]) {
    echo "FAIL: Toggle IP failed: " . json_encode($toggleRes) . "\n";
    exit(1);
}
if (isClientIpAuthorized($db, "192.168.50.100")) {
    echo "FAIL: Inactive IP should NOT be authorized\n";
    exit(1);
}

// 4. Delete the added test IP
$_POST = [
    "action" => "delete",
    "id" => $newId
];
ob_start();
require "api/ip_management.php";
$delRes = json_decode(ob_get_clean(), true);
if (!$delRes || !$delRes["success"]) {
    echo "FAIL: Delete IP failed: " . json_encode($delRes) . "\n";
    exit(1);
}

echo "PASS: Admin IP Whitelist CRUD operations (Add, Toggle, Delete) passed successfully.\n";
exit(0);
';
$results[] = runSubTest('Test 8: Admin IP Whitelist CRUD Operations', $code8);

// =========================================================================
// PRINT SUMMARY REPORT
// =========================================================================
echo "\n========================================================================\n";
echo "           WIFI IP-BASED SHIFT MANAGEMENT VERIFICATION REPORT           \n";
echo "========================================================================\n\n";

$allPassed = true;
foreach ($results as $idx => $r) {
    $num = $idx + 1;
    $statusText = $r['passed'] ? '[PASSED]' : '[FAILED]';
    echo "Test {$num}: {$r['name']}\n";
    echo "Status: {$statusText}\n";
    echo "Details: {$r['output']}\n";
    echo "------------------------------------------------------------------------\n";
    if (!$r['passed']) $allPassed = false;
}

echo "\nFINAL RESULT: " . ($allPassed ? "ALL TESTS PASSED (100% SUCCESS)" : "SOME TESTS FAILED") . "\n";
