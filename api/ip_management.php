<?php
/**
 * Admin Wi-Fi IP Management API - WorkFlow Pro
 * Handles CRUD operations for authorized office Wi-Fi whitelist, live IP validation, and IP attendance logs.
 */

require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

// Strictly require Admin privileges
$user = requireAdmin();
$db = Database::getConnection();
ensureAuthorizedIpTable($db);
ensureAttendanceColumns($db);

// Parse input from GET, POST, or JSON body
$input = array_merge($_GET, $_POST);
$raw = file_get_contents('php://input');
if (!empty($raw)) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $input = array_merge($input, $decoded);
    }
}
$action = $input['action'] ?? 'list';

switch ($action) {
    case 'list':
        listAuthorizedIps($db);
        break;
    case 'add':
        addAuthorizedIp($db, $user, $input);
        break;
    case 'update':
    case 'edit':
        updateAuthorizedIp($db, $user, $input);
        break;
    case 'toggle_status':
        toggleAuthorizedIpStatus($db, $user, $input);
        break;
    case 'delete':
        deleteAuthorizedIp($db, $user, $input);
        break;
    case 'test_ip':
        testIpAuthorization($db, $input);
        break;
    case 'attendance_logs':
        getIpAttendanceLogs($db, $input);
        break;
    default:
        jsonResponse(false, 'Invalid IP management action: ' . htmlspecialchars((string)$action), [], 400);
}

function listAuthorizedIps(PDO $db): void {
    try {
        $currentIp = getClientIp();
        $currentDevice = getClientDeviceType();
        $isCurrentAuthorized = isClientIpAuthorized($db, $currentIp);
        $currentNetwork = getIpNetworkName($db, $currentIp);

        $stmt = $db->query("SELECT * FROM authorized_ips ORDER BY status ASC, id ASC");
        $ips = $stmt->fetchAll() ?: [];

        // Count active shifts right now
        $activeShiftStmt = $db->query("SELECT COUNT(*) as c FROM attendance WHERE clock_out IS NULL");
        $activeShiftCount = (int)($activeShiftStmt->fetchColumn() ?? 0);

        jsonResponse(true, 'Authorized IPs fetched successfully', [
            'ips' => $ips,
            'current_client' => [
                'ip' => $currentIp,
                'device_type' => $currentDevice,
                'is_authorized' => $isCurrentAuthorized,
                'network_name' => $currentNetwork
            ],
            'total_count' => count($ips),
            'active_shifts' => $activeShiftCount
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch authorized IPs: ' . $e->getMessage());
    }
}

function addAuthorizedIp(PDO $db, array $user, array $params): void {
    try {
        $ip = trim($params['ip_address'] ?? '');
        $location = trim($params['location'] ?? '');
        $status = in_array(strtolower($params['status'] ?? 'active'), ['active', 'inactive'], true) ? strtolower($params['status']) : 'active';
        $notes = trim($params['notes'] ?? '');

        if (empty($ip)) {
            jsonResponse(false, 'IP Address cannot be empty.');
            return;
        }

        if (empty($location)) {
            $location = 'Office Wi-Fi';
        }

        // Validate IP format (allows exact IPv4/IPv6, wildcard e.g. 192.168.29.*, or CIDR e.g. 192.168.29.0/24)
        $isValid = filter_var($ip, FILTER_VALIDATE_IP) || str_contains($ip, '*') || (str_contains($ip, '/') && filter_var(explode('/', $ip)[0], FILTER_VALIDATE_IP));
        if (!$isValid) {
            jsonResponse(false, 'Invalid IP Address format. Please provide a valid IP, wildcard (192.168.29.*), or CIDR notation.');
            return;
        }

        // Check for duplicates
        $chk = $db->prepare("SELECT id FROM authorized_ips WHERE ip_address = ?");
        $chk->execute([$ip]);
        if ($chk->fetch()) {
            jsonResponse(false, "IP address {$ip} is already configured in the whitelist.");
            return;
        }

        $now = date('Y-m-d H:i:s');
        $stmt = $db->prepare("INSERT INTO authorized_ips (ip_address, location, status, notes, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$ip, $location, $status, $notes, $user['id'], $now, $now]);
        $newId = (int)$db->lastInsertId();

        logActivity('Add Authorized IP', 'IP Whitelist', "Admin {$user['name']} added authorized IP {$ip} ({$location}) with status '{$status}'");

        jsonResponse(true, "Authorized IP {$ip} added successfully!", [
            'id' => $newId,
            'ip_address' => $ip,
            'location' => $location,
            'status' => $status
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to add authorized IP: ' . $e->getMessage());
    }
}

function updateAuthorizedIp(PDO $db, array $user, array $params): void {
    try {
        $id = (int)($params['id'] ?? 0);
        $ip = trim($params['ip_address'] ?? '');
        $location = trim($params['location'] ?? '');
        $status = in_array(strtolower($params['status'] ?? 'active'), ['active', 'inactive'], true) ? strtolower($params['status']) : 'active';
        $notes = trim($params['notes'] ?? '');

        if ($id <= 0) {
            jsonResponse(false, 'Invalid IP record ID.');
            return;
        }

        if (empty($ip)) {
            jsonResponse(false, 'IP Address cannot be empty.');
            return;
        }

        // Validate IP format
        $isValid = filter_var($ip, FILTER_VALIDATE_IP) || str_contains($ip, '*') || (str_contains($ip, '/') && filter_var(explode('/', $ip)[0], FILTER_VALIDATE_IP));
        if (!$isValid) {
            jsonResponse(false, 'Invalid IP Address format.');
            return;
        }

        // Check if duplicate on other record
        $chk = $db->prepare("SELECT id FROM authorized_ips WHERE ip_address = ? AND id != ?");
        $chk->execute([$ip, $id]);
        if ($chk->fetch()) {
            jsonResponse(false, "IP address {$ip} is already used by another record.");
            return;
        }

        $now = date('Y-m-d H:i:s');
        $stmt = $db->prepare("UPDATE authorized_ips SET ip_address = ?, location = ?, status = ?, notes = ?, updated_at = ? WHERE id = ?");
        $stmt->execute([$ip, $location, $status, $notes, $now, $id]);

        logActivity('Update Authorized IP', 'IP Whitelist', "Admin {$user['name']} updated authorized IP #{$id} to {$ip} ({$location}, status: {$status})");

        jsonResponse(true, "Authorized IP {$ip} updated successfully!");
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update authorized IP: ' . $e->getMessage());
    }
}

function toggleAuthorizedIpStatus(PDO $db, array $user, array $params): void {
    try {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            jsonResponse(false, 'Invalid IP record ID.');
            return;
        }

        $stmt = $db->prepare("SELECT * FROM authorized_ips WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            jsonResponse(false, 'IP record not found.');
            return;
        }

        $newStatus = ($row['status'] === 'active') ? 'inactive' : 'active';
        if (!empty($params['status']) && in_array(strtolower($params['status']), ['active', 'inactive'], true)) {
            $newStatus = strtolower($params['status']);
        }

        $now = date('Y-m-d H:i:s');
        $upd = $db->prepare("UPDATE authorized_ips SET status = ?, updated_at = ? WHERE id = ?");
        $upd->execute([$newStatus, $now, $id]);

        logActivity('Toggle Authorized IP', 'IP Whitelist', "Admin {$user['name']} set IP #{$id} ({$row['ip_address']}) status to '{$newStatus}'");

        jsonResponse(true, "IP {$row['ip_address']} is now " . ucfirst($newStatus), [
            'id' => $id,
            'status' => $newStatus
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to toggle IP status: ' . $e->getMessage());
    }
}

function deleteAuthorizedIp(PDO $db, array $user, array $params): void {
    try {
        $id = (int)($params['id'] ?? 0);
        if ($id <= 0) {
            jsonResponse(false, 'Invalid IP record ID.');
            return;
        }

        $stmt = $db->prepare("SELECT * FROM authorized_ips WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            jsonResponse(false, 'IP record not found.');
            return;
        }

        $del = $db->prepare("DELETE FROM authorized_ips WHERE id = ?");
        $del->execute([$id]);

        logActivity('Delete Authorized IP', 'IP Whitelist', "Admin {$user['name']} deleted authorized IP #{$id} ({$row['ip_address']} - {$row['location']})");

        jsonResponse(true, "Authorized IP {$row['ip_address']} deleted successfully.");
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to delete authorized IP: ' . $e->getMessage());
    }
}

function testIpAuthorization(PDO $db, array $params): void {
    try {
        $testIp = trim($params['test_ip'] ?? '');
        if (empty($testIp)) {
            $testIp = getClientIp();
        }

        $isAuthorized = isClientIpAuthorized($db, $testIp);
        $networkName = getIpNetworkName($db, $testIp);

        jsonResponse(true, 'IP test completed', [
            'test_ip' => $testIp,
            'is_authorized' => $isAuthorized,
            'network_name' => $networkName,
            'message' => $isAuthorized 
                ? "IP {$testIp} is AUTHORIZED for Office Shift attendance ({$networkName})."
                : "IP {$testIp} is NOT authorized. Shift Start / End buttons will be hidden."
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'IP test error: ' . $e->getMessage());
    }
}

function getIpAttendanceLogs(PDO $db, array $params): void {
    try {
        $filterIp = trim($params['ip_address'] ?? '');
        $limit = min(100, max(10, (int)($params['limit'] ?? 50)));

        $sql = "
            SELECT a.id, a.user_id, a.employee_id, a.date, a.clock_in, a.clock_out,
                   a.total_seconds, a.status, a.late_minutes, a.early_exit_minutes,
                   a.clock_in_ip, a.clock_out_ip, a.device_type, a.user_agent,
                   u.name as employee_name, u.employee_code, u.designation, u.avatar
            FROM attendance a
            JOIN users u ON (u.id = CASE WHEN IFNULL(a.user_id, 0) > 0 THEN a.user_id ELSE a.employee_id END)
        ";
        $queryParams = [];

        if (!empty($filterIp)) {
            $sql .= " WHERE (a.clock_in_ip LIKE ? OR a.clock_out_ip LIKE ?)";
            $queryParams[] = "%{$filterIp}%";
            $queryParams[] = "%{$filterIp}%";
        }

        $sql .= " ORDER BY a.id DESC LIMIT {$limit}";

        $stmt = $db->prepare($sql);
        $stmt->execute($queryParams);
        $rows = $stmt->fetchAll() ?: [];

        $formatted = [];
        foreach ($rows as $r) {
            $cIn = $r['clock_in'] ? (strlen($r['clock_in']) <= 8 ? ($r['date'] . ' ' . $r['clock_in']) : $r['clock_in']) : null;
            $cOut = $r['clock_out'] ? (strlen($r['clock_out']) <= 8 ? ($r['date'] . ' ' . $r['clock_out']) : $r['clock_out']) : null;
            
            $formatted[] = [
                'id' => (int)$r['id'],
                'employee_name' => $r['employee_name'],
                'employee_code' => $r['employee_code'] ?? '',
                'designation' => $r['designation'] ?? '',
                'date' => $r['date'],
                'date_formatted' => formatISTDate($r['date']),
                'clock_in' => $cIn ? formatISTTime($cIn) : '--',
                'clock_out' => $cOut ? formatISTTime($cOut) : '--',
                'clock_in_ip' => $r['clock_in_ip'] ?: '--',
                'clock_out_ip' => $r['clock_out_ip'] ?: '--',
                'device_type' => $r['device_type'] ?: 'Desktop',
                'total_duration' => formatDuration((int)$r['total_seconds']),
                'status' => $r['status']
            ];
        }

        jsonResponse(true, 'Attendance logs by IP fetched', [
            'logs' => $formatted,
            'count' => count($formatted)
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch IP attendance logs: ' . $e->getMessage());
    }
}

