<?php
/**
 * Application Core Configuration & Security Helpers
 */

date_default_timezone_set('Asia/Kolkata');

// Detect HTTPS accurately behind mobile networks, reverse proxies & load balancers
$isHttpsEarly = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off')
    || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
    || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
    || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower((string)$_SERVER['HTTP_X_FORWARDED_SSL']) === 'on')
    || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower((string)$_SERVER['HTTP_FRONT_END_HTTPS']) === 'on');

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '', // empty string allows cookies on both LAN IP address and domain
            'secure'   => $isHttpsEarly,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    } else {
        session_set_cookie_params(0, '/; samesite=Lax', '', $isHttpsEarly, true);
    }
    session_start();
}

define('APP_NAME', 'WorkFlow Pro');
define('APP_VERSION', '2.0.0');

// Calculate Clean, Valid Base URL (RFC 3986 compliant, safe for mobile & IP access)
$protocol = $isHttpsEarly ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');

$projectRoot = str_replace('\\', '/', realpath(dirname(__DIR__, 2)) ?: dirname(__DIR__, 2));
$docRoot = !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim(str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT']) ?: $_SERVER['DOCUMENT_ROOT']), '/') : '';
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

$rawBasePath = '';
if (!empty($docRoot) && strpos($projectRoot, $docRoot) === 0) {
    $rawBasePath = substr($projectRoot, strlen($docRoot));
} elseif (!empty($scriptName) && preg_match('#^(.+?)/(public/index\.php|public|index\.php)#i', $scriptName, $m)) {
    $rawBasePath = $m[1];
} elseif (!empty($scriptName) && in_array(basename($scriptName), ['index.php', 'public'])) {
    $rawBasePath = dirname($scriptName);
}

$rawBasePath = rtrim(str_replace('\\', '/', $rawBasePath), '/');
if ($rawBasePath === '/' || $rawBasePath === '\\' || $rawBasePath === '.') {
    $rawBasePath = '';
}

// Ensure spaces in directory path ('system software') are safely encoded to '%20'
if (!empty($rawBasePath)) {
    $segments = explode('/', trim($rawBasePath, '/'));
    $encodedSegments = array_map(function($seg) {
        return rawurlencode(rawurldecode($seg));
    }, $segments);
    $cleanBasePath = '/' . implode('/', $encodedSegments);
} else {
    $cleanBasePath = '';
}

define('BASE_URL', $protocol . $domainName . $cleanBasePath);

// CSRF Token Management
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function getCsrfToken(): string {
    return $_SESSION['csrf_token'] ?? '';
}

function verifyCsrfToken(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

// XSS Protection & Sanitization
function sanitize($input) {
    if (is_array($input)) {
        foreach ($input as $key => $value) {
            $input[$key] = sanitize($value);
        }
        return $input;
    }
    return htmlspecialchars(trim((string)$input), ENT_QUOTES, 'UTF-8');
}

// JSON API Response Helper
function jsonResponse(bool $success, string $message = '', array $data = [], int $httpCode = 200): void {
    http_response_code($httpCode);
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

// Session Timeout Check (30 minutes)
function checkSessionTimeout(): void {
    $timeout = 1800;
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            jsonResponse(false, 'Session expired. Please login again.', [], 401);
        } else {
            header('Location: ' . BASE_URL . '/login?expired=1');
            exit;
        }
    }
    $_SESSION['last_activity'] = time();
}
