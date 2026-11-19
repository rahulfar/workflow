<?php
/**
 * Shift Settings API Endpoint - WorkFlow Pro
 * Handles centralized configuration of office shift timings, lunch hours, grace periods, and activity update intervals.
 */

require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_GET['action'] ?? ($_POST['action'] ?? 'get');

switch ($action) {
    case 'get':
        getSettings($user);
        break;
    case 'save':
        saveSettings($user);
        break;
    default:
        jsonResponse(false, 'Invalid action', [], 400);
}

function getSettings(array $user): void {
    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);
        $settings = getShiftSettings($db);
        jsonResponse(true, 'Shift settings retrieved', $settings);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch shift settings: ' . $e->getMessage(), [], 500);
    }
}

function saveSettings(array $user): void {
    // Only Admin or Manager can modify shift settings
    if (!hasRole(['admin', 'manager'])) {
        jsonResponse(false, 'Access denied. Administrator privileges required.', [], 403);
    }

    $startTime = sanitize($_POST['start_time'] ?? '09:40');
    $endTime = sanitize($_POST['end_time'] ?? '18:30');
    $lunchStart = sanitize($_POST['lunch_start'] ?? '14:00');
    $lunchEnd = sanitize($_POST['lunch_end'] ?? '15:00');
    $workingDays = sanitize($_POST['working_days'] ?? 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday');
    $gracePeriod = max(0, (int)($_POST['grace_period_minutes'] ?? 15));
    $reminderInterval = max(5, (int)($_POST['reminder_interval_minutes'] ?? 30));
    $autoLunch = isset($_POST['auto_lunch']) ? (int)($_POST['auto_lunch']) : 1;
    $allowOverlapping = isset($_POST['allow_overlapping_meetings']) ? (int)($_POST['allow_overlapping_meetings']) : 0;
    $meetingDuration = max(10, (int)($_POST['meeting_default_duration'] ?? 30));
    $shiftName = sanitize($_POST['shift_name'] ?? 'Standard Agency Shift');

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $chk = $db->query("SELECT id FROM shift_settings LIMIT 1")->fetchColumn();
        $nowStr = date('Y-m-d H:i:s');
        if ($chk) {
            $stmt = $db->prepare("
                UPDATE shift_settings SET
                    shift_name = ?,
                    start_time = ?,
                    end_time = ?,
                    lunch_start = ?,
                    lunch_end = ?,
                    working_days = ?,
                    grace_period_minutes = ?,
                    reminder_interval_minutes = ?,
                    auto_lunch = ?,
                    allow_overlapping_meetings = ?,
                    meeting_default_duration = ?,
                    updated_at = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $shiftName, $startTime, $endTime, $lunchStart, $lunchEnd, $workingDays,
                $gracePeriod, $reminderInterval, $autoLunch, $allowOverlapping, $meetingDuration, $nowStr, $chk
            ]);
        } else {
            $stmt = $db->prepare("
                INSERT INTO shift_settings
                (shift_name, start_time, end_time, lunch_start, lunch_end, working_days,
                 grace_period_minutes, reminder_interval_minutes, auto_lunch, allow_overlapping_meetings, meeting_default_duration, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $shiftName, $startTime, $endTime, $lunchStart, $lunchEnd, $workingDays,
                $gracePeriod, $reminderInterval, $autoLunch, $allowOverlapping, $meetingDuration, $nowStr, $nowStr
            ]);
        }

        logActivity('Update Shift Settings', 'Settings', "Admin {$user['name']} updated office shift configuration ({$startTime} - {$endTime}, Lunch: {$lunchStart} - {$lunchEnd}).");

        $updated = getShiftSettings($db);
        jsonResponse(true, 'Shift settings successfully updated!', $updated);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to save shift settings: ' . $e->getMessage(), [], 500);
    }
}
