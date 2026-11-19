<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();

// Parse input from GET, POST, or JSON body
$input = array_merge($_GET, $_POST);
$raw = file_get_contents('php://input');
if (!empty($raw)) {
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) {
        $input = array_merge($input, $decoded);
    }
}
$action = $input['action'] ?? '';

switch ($action) {
    case 'clock_in':
    case 'start_shift':
    case 'start':
    case 'mobile_clock_in':
        if (isMobileRequest() || $action === 'mobile_clock_in') {
            if (!isAuthorizedShiftEmployee($user)) {
                jsonResponse(false, 'Access denied. You are not authorized for mobile shift attendance.', [], 403);
            }
        }
        clockIn($user, $input);
        break;
    case 'clock_out':
    case 'end_shift':
    case 'end':
    case 'mobile_clock_out':
        if (isMobileRequest() || $action === 'mobile_clock_out') {
            if (!isAuthorizedShiftEmployee($user)) {
                jsonResponse(false, 'Access denied. You are not authorized for mobile shift attendance.', [], 403);
            }
        }
        clockOut($user, $input);
        break;
    case 'status':
    case 'shift_status':
        getShiftStatus($user);
        break;
    case 'check_ip':
        checkIpAuthorization($user);
        break;
    case 'admin_summary':
        getAdminAttendanceSummary($user);
        break;
    case 'get_logs':
    case 'filter_logs':
    case 'list':
        getAttendanceLogs($user, $input);
        break;
    default:
        jsonResponse(false, 'Invalid attendance action: ' . htmlspecialchars((string)$action), [], 400);
}

function getShiftStatus(array $user): void {
    try {
        $db = Database::getConnection();
        ensureAttendanceColumns($db);
        ensureAuthorizedIpTable($db);
        ensureShiftAndActivitySchema($db);
        autoCloseOverdueShifts($db, (int)$user['id']);

        $clientIp = getClientIp();
        $isIpAuth = isClientIpAuthorized($db, $clientIp);
        $networkName = getIpNetworkName($db, $clientIp);
        $deviceType = getClientDeviceType();
        
        $settings = getShiftSettings($db);
        $today = date('Y-m-d');
        
        // Find active shift for user (clock_out IS NULL)
        $stmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND clock_out IS NULL ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user['id'], $user['id']]);
        $activeShift = $stmt->fetch();

        if ($activeShift) {
            $clockInStr = strlen($activeShift['clock_in']) <= 8 ? ($activeShift['date'] . ' ' . $activeShift['clock_in']) : $activeShift['clock_in'];
            $startTimestamp = strtotime($clockInStr);
            $currentServerTimestamp = time();
            $elapsedSeconds = max(0, $currentServerTimestamp - $startTimestamp);
            $remainingSeconds = max(0, 31800 - $elapsedSeconds);

            $lateMinutes = (int)($activeShift['late_minutes'] ?? calculateLateMinutes($clockInStr));
            $isLate = ($lateMinutes > 0);

            // Live status estimation based on 8h 50m shift rules & 06:30 PM end time
            $nowDateTimeStr = date('Y-m-d H:i:s');
            $earlyExitMinutes = calculateEarlyExitMinutes($nowDateTimeStr);
            $liveStatus = ($elapsedSeconds >= 31800 && $earlyExitMinutes == 0) ? 'full_day' : 'half_day';
            $lunchInfo = getLunchTimingInfo();

            jsonResponse(true, 'Active shift running', [
                'active' => true,
                'ip_authorized' => $isIpAuth,
                'client_ip' => $clientIp,
                'network_name' => $networkName,
                'device_type' => $deviceType,
                'attendance_id' => (int)$activeShift['id'],
                'shift_start_target' => $settings['start_time'] ?? '09:40 AM',
                'shift_end_target' => $settings['end_time'] ?? '06:30 PM',
                'shift_duration_target' => '8h 50m',
                'shift_target_seconds' => 31800,
                'lunch_timing' => ($settings['lunch_start'] ?? '02:00 PM') . ' - ' . ($settings['lunch_end'] ?? '03:00 PM') . ' (IST)',
                'lunch_info' => $lunchInfo,
                'clock_in_raw' => $activeShift['clock_in'],
                'clock_in_formatted' => formatISTDateTime($clockInStr),
                'clock_in_time' => formatISTTime($clockInStr),
                'date_formatted' => formatISTDate($activeShift['date']),
                'time_formatted' => formatISTTime($clockInStr),
                'start_timestamp' => $startTimestamp,
                'current_server_timestamp' => $currentServerTimestamp,
                'elapsed_seconds' => $elapsedSeconds,
                'remaining_seconds' => $remainingSeconds,
                'worked_hours_text' => formatDuration($elapsedSeconds),
                'remaining_hours_text' => formatDuration($remainingSeconds),
                'late_minutes' => $lateMinutes,
                'is_late' => $isLate,
                'late_text' => $isLate ? "{$lateMinutes} min late" : "On Time",
                'early_exit_minutes' => $earlyExitMinutes,
                'early_exit_text' => ($earlyExitMinutes > 0) ? "{$earlyExitMinutes} min early exit" : "No",
                'status' => $liveStatus,
                'punctuality_badge' => getPunctualityBadge($lateMinutes),
                'day_badge' => getAttendanceDayBadge($liveStatus, $elapsedSeconds),
                'lunch_badge' => $lunchInfo['badge']
            ]);
        } else {
            // Check if user completed a shift today
            $completedStmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date = ? AND clock_out IS NOT NULL ORDER BY id DESC LIMIT 1");
            $completedStmt->execute([$user['id'], $user['id'], $today]);
            $completedShift = $completedStmt->fetch();
            $lunchInfo = getLunchTimingInfo();

            $completedData = null;
            if ($completedShift) {
                $cInStr = strlen($completedShift['clock_in']) <= 8 ? ($completedShift['date'] . ' ' . $completedShift['clock_in']) : $completedShift['clock_in'];
                $cOutStr = strlen($completedShift['clock_out']) <= 8 ? ($completedShift['date'] . ' ' . $completedShift['clock_out']) : $completedShift['clock_out'];
                $totalSecs = (int)$completedShift['total_seconds'];
                $lateMins = (int)$completedShift['late_minutes'];
                $earlyExitMins = (int)($completedShift['early_exit_minutes'] ?? 0);
                $status = $completedShift['status'];

                $completedData = [
                    'shift_start_target' => $settings['start_time'] ?? '09:40 AM',
                    'shift_end_target' => $settings['end_time'] ?? '06:30 PM',
                    'shift_duration_target' => '8h 50m',
                    'shift_target_seconds' => 31800,
                    'lunch_timing' => ($settings['lunch_start'] ?? '02:00 PM') . ' - ' . ($settings['lunch_end'] ?? '03:00 PM') . ' (IST)',
                    'clock_in_formatted' => formatISTDateTime($cInStr),
                    'clock_in_time' => formatISTTime($cInStr),
                    'clock_out_formatted' => formatISTDateTime($cOutStr),
                    'clock_out_time' => formatISTTime($cOutStr),
                    'total_seconds' => $totalSecs,
                    'duration_formatted' => formatDuration($totalSecs),
                    'late_minutes' => $lateMins,
                    'late_text' => ($lateMins > 0) ? "{$lateMins} min late" : "On Time",
                    'early_exit_minutes' => $earlyExitMins,
                    'early_exit_text' => ($earlyExitMins > 0) ? "{$earlyExitMins} min early exit" : "No",
                    'status' => $status,
                    'punctuality_badge' => getPunctualityBadge($lateMins),
                    'day_badge' => getAttendanceDayBadge($status, $totalSecs, $cOutStr)
                ];
            }

            jsonResponse(true, 'No active shift', [
                'active' => false,
                'ip_authorized' => $isIpAuth,
                'client_ip' => $clientIp,
                'network_name' => $networkName,
                'device_type' => $deviceType,
                'shift_start_target' => $settings['start_time'] ?? '09:40 AM',
                'shift_end_target' => $settings['end_time'] ?? '06:30 PM',
                'shift_duration_target' => '8h 50m',
                'shift_target_seconds' => 31800,
                'lunch_timing' => ($settings['lunch_start'] ?? '02:00 PM') . ' - ' . ($settings['lunch_end'] ?? '03:00 PM') . ' (IST)',
                'lunch_info' => $lunchInfo,
                'completed_today' => $completedData
            ]);
        }
    } catch (Exception $e) {
        jsonResponse(false, 'Error fetching shift status: ' . $e->getMessage());
    }
}

/**
 * Lightweight check client IP authorization endpoint
 */
function checkIpAuthorization(array $user): void {
    try {
        $db = Database::getConnection();
        ensureAuthorizedIpTable($db);
        $clientIp = getClientIp();
        $isAuthorized = isClientIpAuthorized($db, $clientIp);
        $networkName = getIpNetworkName($db, $clientIp);
        $deviceType = getClientDeviceType();

        jsonResponse(true, 'IP status checked', [
            'ip_authorized' => $isAuthorized,
            'client_ip' => $clientIp,
            'network_name' => $networkName,
            'device_type' => $deviceType
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error checking IP: ' . $e->getMessage());
    }
}

function clockIn(array $user, array $params = []): void {
    try {
        $db = Database::getConnection();
        ensureAttendanceColumns($db);
        ensureAuthorizedIpTable($db);
        ensureShiftAndActivitySchema($db);
        autoCloseOverdueShifts($db, (int)$user['id']);

        $clientIp = getClientIp();
        $deviceType = getClientDeviceType();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        // Strict Server-Side Wi-Fi IP Whitelist Validation
        if (!isClientIpAuthorized($db, $clientIp)) {
            jsonResponse(false, "Shift Start Rejected: You must be connected to authorized office Wi-Fi to start your shift. (Detected IP: {$clientIp})", [
                'ip_authorized' => false,
                'client_ip' => $clientIp,
                'device_type' => $deviceType
            ], 403);
            return;
        }

        $today = date('Y-m-d');
        $nowDateTimeStr = date('Y-m-d H:i:s');

        // Check if user already has an active (unclosed) shift
        $activeCheck = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND clock_out IS NULL");
        $activeCheck->execute([$user['id'], $user['id']]);
        if ($activeCheck->fetch()) {
            jsonResponse(false, 'You already have an active shift running!');
            return;
        }

        // Calculate late minutes using shift settings
        $settings = getShiftSettings($db);
        $shiftStart = $settings['start_time'] ?? '09:40';
        $shiftEnd = $settings['end_time'] ?? '18:30';
        $lateMinutes = calculateLateMinutes($nowDateTimeStr);
        $initialStatus = ($lateMinutes > 0) ? 'late' : 'present';

        $ins = $db->prepare("
            INSERT INTO attendance 
            (user_id, employee_id, date, shift_start, shift_end, clock_in, status, late_minutes, early_exit_minutes, total_seconds, break_seconds, clock_in_ip, device_type, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 0, 0, ?, ?, ?)
        ");
        $ins->execute([
            $user['id'],
            $user['id'],
            $today,
            $shiftStart,
            $shiftEnd,
            $nowDateTimeStr,
            $initialStatus,
            $lateMinutes,
            $clientIp,
            $deviceType,
            $userAgent
        ]);

        $clockInFormatted = formatISTDateTime($nowDateTimeStr);
        $startTimestamp = strtotime($nowDateTimeStr);

        try {
            startEmployeeActivity($db, (int)$user['id'], 'Working on Task', null, null, null, 'Shift started');
        } catch (Exception $actEx) {}

        logActivity('Start Shift', 'Attendance', "User {$user['name']} started shift at {$clockInFormatted} from IP {$clientIp} ({$deviceType}). Late: {$lateMinutes}m");
        
        jsonResponse(true, "Shift started successfully!", [
            'active' => true,
            'ip_authorized' => true,
            'client_ip' => $clientIp,
            'device_type' => $deviceType,
            'clock_in_formatted' => $clockInFormatted,
            'date_formatted' => formatISTDate($today),
            'time_formatted' => formatISTTime($nowDateTimeStr),
            'start_timestamp' => $startTimestamp,
            'current_server_timestamp' => time(),
            'elapsed_seconds' => 0,
            'late_minutes' => $lateMinutes,
            'late_text' => ($lateMinutes > 0) ? "{$lateMinutes} min late" : "On Time"
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Start shift failed: ' . $e->getMessage());
    }
}

function clockOut(array $user, array $params = []): void {
    try {
        $db = Database::getConnection();
        ensureAttendanceColumns($db);
        ensureAuthorizedIpTable($db);
        ensureShiftAndActivitySchema($db);

        $clientIp = getClientIp();
        $deviceType = getClientDeviceType();

        // Strict Server-Side Wi-Fi IP Whitelist Validation
        if (!isClientIpAuthorized($db, $clientIp)) {
            jsonResponse(false, "Shift End Rejected: You must be connected to authorized office Wi-Fi to end your shift. (Detected IP: {$clientIp})", [
                'ip_authorized' => false,
                'client_ip' => $clientIp,
                'device_type' => $deviceType
            ], 403);
            return;
        }

        $stmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND clock_out IS NULL ORDER BY id DESC LIMIT 1");
        $stmt->execute([$user['id'], $user['id']]);
        $att = $stmt->fetch();

        if (!$att) {
            jsonResponse(false, 'No active shift session found to end.');
            return;
        }

        $clockInStr = strlen($att['clock_in']) <= 8 ? ($att['date'] . ' ' . $att['clock_in']) : $att['clock_in'];
        $clockInTimestamp = strtotime($clockInStr);
        $nowTimestamp = time();
        $nowDateTimeStr = date('Y-m-d H:i:s');
        $totalSecs = max(0, $nowTimestamp - $clockInTimestamp);

        $lateMinutes = (int)($att['late_minutes'] ?? calculateLateMinutes($clockInStr));
        $earlyExitMinutes = calculateEarlyExitMinutes($nowDateTimeStr);

        // Shift Evaluation:
        // Worked >= 8h 50m (31800s) AND clock_out >= 06:30 PM (18:30:00) => Full Day
        // Otherwise => Half Day
        if ($totalSecs >= 31800 && $earlyExitMinutes == 0) {
            $finalStatus = 'full_day';
        } else {
            $finalStatus = 'half_day';
        }

        $upd = $db->prepare("UPDATE attendance SET clock_out = ?, total_seconds = ?, late_minutes = ?, early_exit_minutes = ?, status = ?, clock_out_ip = ? WHERE id = ?");
        $upd->execute([$nowDateTimeStr, $totalSecs, $lateMinutes, $earlyExitMinutes, $finalStatus, $clientIp, $att['id']]);

        try {
            closeEmployeeCurrentActivity($db, (int)$user['id'], $nowDateTimeStr);
            generateDailyWorkReport($db, (int)$user['id'], $att['date']);
        } catch (Exception $actEx) {}

        $clockOutFormatted = formatISTDateTime($nowDateTimeStr);
        $durationFormatted = formatDuration($totalSecs);

        logActivity('End Shift', 'Attendance', "User {$user['name']} ended shift at {$clockOutFormatted} from IP {$clientIp}. Status: {$finalStatus}, Duration: {$durationFormatted}, Early Exit: {$earlyExitMinutes}m");
        
        jsonResponse(true, 'Shift ended successfully!', [
            'active' => false,
            'ip_authorized' => true,
            'client_ip' => $clientIp,
            'device_type' => $deviceType,
            'clock_out_formatted' => $clockOutFormatted,
            'total_seconds' => $totalSecs,
            'duration_formatted' => $durationFormatted,
            'late_minutes' => $lateMinutes,
            'early_exit_minutes' => $earlyExitMinutes,
            'status' => $finalStatus,
            'punctuality_badge' => getPunctualityBadge($lateMinutes),
            'day_badge' => getAttendanceDayBadge($finalStatus, $totalSecs, $nowDateTimeStr)
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'End shift failed: ' . $e->getMessage());
    }
}

function getAdminAttendanceSummary(array $user): void {
    if (!canCreateOrEditTasks()) {
        jsonResponse(false, 'Access denied.', [], 403);
    }

    try {
        $db = Database::getConnection();
        ensureAttendanceColumns($db);
        autoCloseOverdueShifts($db);

        $today = date('Y-m-d');
        $stmt = $db->query("
            SELECT u.id as user_id, u.name as employee_name, u.designation, u.avatar,
                   a.clock_in, a.clock_out, a.total_seconds, a.status, a.late_minutes, a.early_exit_minutes
            FROM users u
            LEFT JOIN attendance a ON (u.id = a.user_id OR u.id = a.employee_id) AND a.date = '{$today}'
            WHERE u.status = 'active'
            ORDER BY u.name ASC
        ");
        $records = $stmt->fetchAll();

        $presentCount = 0;
        $absentCount = 0;
        $lateCount = 0;
        $halfDayCount = 0;
        $summaryList = [];

        foreach ($records as $r) {
            $clockIn = $r['clock_in'];
            $clockOut = $r['clock_out'];

            $cInStr = $clockIn ? (strlen($clockIn) <= 8 ? ($today . ' ' . $clockIn) : $clockIn) : null;
            $cOutStr = $clockOut ? (strlen($clockOut) <= 8 ? ($today . ' ' . $clockOut) : $clockOut) : null;

            $isLive = ($clockIn && !$clockOut);
            $totalSecs = (int)($r['total_seconds'] ?? 0);

            if ($isLive) {
                $startTimestamp = strtotime($cInStr);
                $totalSecs = max(0, time() - $startTimestamp);
            }

            $lateMins = (int)($r['late_minutes'] ?? 0);
            $earlyExitMins = (int)($r['early_exit_minutes'] ?? 0);
            $status = $r['status'] ?? 'absent';

            if (!$clockIn) {
                $absentCount++;
                $liveState = 'Not Started';
                $statusText = 'Absent';
            } elseif ($isLive) {
                $presentCount++;
                if ($lateMins > 0) $lateCount++;
                $liveState = 'Shift Running';
                $statusText = $lateMins > 0 ? 'Late (Running)' : 'On Time (Running)';
            } else {
                $presentCount++;
                if ($lateMins > 0) $lateCount++;
                if (in_array($status, ['half_day', 'half day'])) $halfDayCount++;
                $liveState = 'Shift Completed';
                $statusText = ucfirst($status);
            }

            $summaryList[] = [
                'user_id' => $r['user_id'],
                'employee_name' => $r['employee_name'],
                'designation' => $r['designation'],
                'avatar' => $r['avatar'],
                'shift_start_target' => '09:40 AM',
                'shift_end_target' => '06:30 PM',
                'clock_in' => $cInStr ? formatISTTime($cInStr) : '--',
                'clock_out' => $cOutStr ? formatISTTime($cOutStr) : ($isLive ? 'Running...' : '--'),
                'total_hours' => formatDuration($totalSecs),
                'late_minutes' => $lateMins,
                'late_text' => $lateMins > 0 ? "{$lateMins} m" : 'On Time',
                'early_exit_minutes' => $earlyExitMins,
                'early_exit_text' => $earlyExitMins > 0 ? "{$earlyExitMins} m" : 'No',
                'status' => $statusText,
                'live_state' => $liveState,
                'punctuality_badge' => getPunctualityBadge($lateMins),
                'day_badge' => getAttendanceDayBadge($status, $totalSecs, $cOutStr)
            ];
        }

        jsonResponse(true, 'Admin attendance summary fetched', [
            'counts' => [
                'present' => $presentCount,
                'absent' => $absentCount,
                'late' => $lateCount,
                'half_day' => $halfDayCount
            ],
            'employees' => $summaryList
        ]);

    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch admin summary: ' . $e->getMessage());
    }
}

function getAttendanceLogs(array $user, array $params = []): void {
    try {
        $db = Database::getConnection();
        ensureAttendanceColumns($db);

        if (!empty($params['month'])) {
            $month = sanitize($params['month']);
            $fromDate = $month . '-01';
            $toDate = date('Y-m-01', strtotime($fromDate . ' +1 month'));
        } else {
            $fromDate = !empty($params['from_date']) ? sanitize($params['from_date']) : date('Y-m-01');
            $toDate = !empty($params['to_date']) ? sanitize($params['to_date']) : date('Y-m-01', strtotime('+1 month'));
        }
        $userId = !empty($params['user_id']) ? sanitize((string)$params['user_id']) : '';
        $deptId = !empty($params['department_id']) ? sanitize((string)$params['department_id']) : '';
        $status = !empty($params['status']) ? sanitize((string)$params['status']) : '';
        $punctuality = !empty($params['punctuality']) ? sanitize((string)$params['punctuality']) : '';
        $search = !empty($params['search']) ? trim(sanitize((string)$params['search'])) : '';

        $conditions = ["a.date >= ?", "a.date <= ?"];
        $queryParams = [$fromDate, $toDate];

        if (!empty($userId) && $userId !== 'all') {
            $conditions[] = "(a.user_id = ? OR a.employee_id = ?)";
            $queryParams[] = (int)$userId;
            $queryParams[] = (int)$userId;
        }

        if (!empty($deptId) && $deptId !== 'all') {
            $conditions[] = "u.department_id = ?";
            $queryParams[] = (int)$deptId;
        }

        if (!empty($status) && $status !== 'all') {
            $conditions[] = "LOWER(a.status) = ?";
            $queryParams[] = strtolower($status);
        }

        if ($punctuality === 'late') {
            $conditions[] = "a.late_minutes > 0";
        } elseif ($punctuality === 'on_time') {
            $conditions[] = "(a.late_minutes IS NULL OR a.late_minutes = 0)";
        } elseif ($punctuality === 'early_exit') {
            $conditions[] = "a.early_exit_minutes > 0";
        }

        $employeeSearch = !empty($params['search_employee']) ? trim(sanitize((string)$params['search_employee'])) : (!empty($params['search']) ? trim(sanitize((string)$params['search'])) : '');
        if (!empty($employeeSearch)) {
            $cleanSearch = $employeeSearch;
            $codeSearch = '';
            if (preg_match('/^([^\(]+)\s*\((.*?)\)$/', $employeeSearch, $m)) {
                $cleanSearch = trim($m[1]);
                $codeSearch = trim($m[2]);
            }
            if (!empty($codeSearch)) {
                $conditions[] = "(LOWER(u.name) LIKE ? OR LOWER(u.employee_code) LIKE ?)";
                $queryParams[] = '%' . strtolower($cleanSearch) . '%';
                $queryParams[] = '%' . strtolower($codeSearch) . '%';
            } else {
                $conditions[] = "(LOWER(u.name) LIKE ? OR LOWER(u.employee_code) LIKE ?)";
                $queryParams[] = '%' . strtolower($cleanSearch) . '%';
                $queryParams[] = '%' . strtolower($cleanSearch) . '%';
            }
        }

        $whereSql = implode(' AND ', $conditions);

        $stmt = $db->prepare("
            SELECT a.*, u.name as user_name, u.employee_code, u.avatar, u.designation, d.name as department_name, d.id as department_id
            FROM attendance a
            JOIN users u ON (u.id = CASE WHEN IFNULL(a.user_id, 0) > 0 THEN a.user_id ELSE a.employee_id END)
            LEFT JOIN departments d ON u.department_id = d.id
            WHERE {$whereSql}
            ORDER BY a.date DESC, a.id DESC
        ");
        $stmt->execute($queryParams);
        $logs = $stmt->fetchAll();

        $totalLogs = count($logs);
        $fullDays = 0;
        $halfDays = 0;
        $lateCount = 0;
        $earlyExitCount = 0;
        $totalSeconds = 0;

        $formattedLogs = [];
        foreach ($logs as $a) {
            $statusLower = strtolower($a['status'] ?? '');
            if (in_array($statusLower, ['full_day', 'full day'])) {
                $fullDays++;
            } elseif (in_array($statusLower, ['half_day', 'half day'])) {
                $halfDays++;
            }
            $lateMins = (int)($a['late_minutes'] ?? 0);
            if ($lateMins > 0) $lateCount++;
            $earlyExitMins = (int)($a['early_exit_minutes'] ?? 0);
            if ($earlyExitMins > 0) $earlyExitCount++;
            $totalSeconds += (int)($a['total_seconds'] ?? 0);

            $cInStr = $a['clock_in'] ? (strlen($a['clock_in']) <= 8 ? ($a['date'] . ' ' . $a['clock_in']) : $a['clock_in']) : null;
            $cOutStr = $a['clock_out'] ? (strlen($a['clock_out']) <= 8 ? ($a['date'] . ' ' . $a['clock_out']) : $a['clock_out']) : null;
            $avatarUrl = !empty($a['avatar']) && file_exists(dirname(__DIR__) . '/public/uploads/avatars/' . $a['avatar'])
                ? BASE_URL . '/uploads/avatars/' . $a['avatar']
                : 'https://ui-avatars.com/api/?name=' . urlencode($a['user_name'] ?? 'User') . '&background=4f46e5&color=fff';

            $formattedLogs[] = [
                'id' => (int)$a['id'],
                'user_id' => (int)($a['user_id'] ?: $a['employee_id']),
                'user_name' => $a['user_name'],
                'employee_code' => $a['employee_code'] ?? '',
                'avatar' => $a['avatar'] ?? 'default_avatar.png',
                'avatar_url' => $avatarUrl,
                'designation' => $a['designation'] ?? '',
                'department_name' => $a['department_name'] ?? 'General',
                'date' => $a['date'],
                'day_of_week' => date('D', strtotime($a['date'])),
                'date_formatted' => formatISTDate($a['date']),
                'clock_in' => $cInStr ? formatISTTime($cInStr) : '--',
                'clock_out' => $cOutStr ? formatISTTime($cOutStr) : '--',
                'raw_clock_in' => $a['clock_in'],
                'raw_clock_out' => $a['clock_out'],
                'total_seconds' => (int)$a['total_seconds'],
                'duration_formatted' => formatDuration((int)$a['total_seconds']),
                'late_minutes' => $lateMins,
                'early_exit_minutes' => $earlyExitMins,
                'status' => $a['status'],
                'punctuality_badge' => getPunctualityBadge($lateMins),
                'day_badge' => getAttendanceDayBadge($a['status'], (int)$a['total_seconds'], $cOutStr),
                'clock_in_ip' => $a['clock_in_ip'] ?? '',
                'clock_out_ip' => $a['clock_out_ip'] ?? '',
                'device_type' => $a['device_type'] ?? 'Desktop',
                'notes' => $a['notes'] ?? ''
            ];
        }

        $avgSeconds = $totalLogs > 0 ? (int)round($totalSeconds / $totalLogs) : 0;

        jsonResponse(true, 'Attendance logs fetched successfully', [
            'logs' => $formattedLogs,
            'kpis' => [
                'total_records' => $totalLogs,
                'full_days' => $fullDays,
                'half_days' => $halfDays,
                'late_count' => $lateCount,
                'early_exit_count' => $earlyExitCount,
                'total_duration' => formatDuration($totalSeconds),
                'total_seconds' => $totalSeconds,
                'avg_duration' => formatDuration($avgSeconds),
                'from_date' => $fromDate,
                'to_date' => $toDate
            ]
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch attendance logs: ' . $e->getMessage());
    }
}
