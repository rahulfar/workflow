<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$db = Database::getConnection();
ensurePayrollSchema($db);

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'list':
        getHolidays($db);
        break;

    case 'add':
        addHoliday($db, $user);
        break;

    case 'edit':
        editHoliday($db, $user);
        break;

    case 'delete':
        deleteHoliday($db, $user);
        break;

    default:
        jsonResponse(false, 'Invalid holiday action', [], 400);
}

function getHolidays(PDO $db): void {
    try {
        $stmt = $db->query("SELECT * FROM holidays ORDER BY date ASC");
        $holidays = $stmt->fetchAll();
        jsonResponse(true, 'Holidays fetched successfully', ['holidays' => $holidays]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch holidays: ' . $e->getMessage());
    }
}

function addHoliday(PDO $db, array $user): void {
    if (!hasRole('admin')) {
        jsonResponse(false, 'Access denied. Admin privileges required.', [], 403);
    }

    $title = sanitize($_POST['title'] ?? '');
    $date = sanitize($_POST['date'] ?? '');
    $type = sanitize($_POST['type'] ?? 'company');
    $description = sanitize($_POST['description'] ?? '');

    if (empty($title) || empty($date)) {
        jsonResponse(false, 'Holiday Name and Date are required.');
    }

    if (!in_array($type, ['national', 'festival', 'company', 'optional'])) {
        $type = 'company';
    }

    try {
        $stmt = $db->prepare("INSERT INTO holidays (title, date, type, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $date, $type, $description]);

        // Auto recalculate payrolls for this month
        $monthYear = date('Y-m', strtotime($date));
        $users = $db->query("SELECT id FROM users WHERE status = 'active'")->fetchAll();
        foreach ($users as $u) {
            if (function_exists('recalculateUserPayroll')) {
                recalculateUserPayroll($db, (int)$u['id'], $monthYear);
            }
        }

        logActivity('Add Holiday', 'Holidays', "Admin added holiday: {$title} on {$date}");
        jsonResponse(true, 'Holiday added successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to add holiday: ' . $e->getMessage());
    }
}

function editHoliday(PDO $db, array $user): void {
    if (!hasRole('admin')) {
        jsonResponse(false, 'Access denied. Admin privileges required.', [], 403);
    }

    $id = (int)($_POST['holiday_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $date = sanitize($_POST['date'] ?? '');
    $type = sanitize($_POST['type'] ?? 'company');
    $description = sanitize($_POST['description'] ?? '');

    if ($id <= 0 || empty($title) || empty($date)) {
        jsonResponse(false, 'Invalid holiday data');
    }

    if (!in_array($type, ['national', 'festival', 'company', 'optional'])) {
        $type = 'company';
    }

    try {
        $stmt = $db->prepare("UPDATE holidays SET title = ?, date = ?, type = ?, description = ? WHERE id = ?");
        $stmt->execute([$title, $date, $type, $description, $id]);

        logActivity('Edit Holiday', 'Holidays', "Admin edited holiday ID {$id}: {$title}");
        jsonResponse(true, 'Holiday updated successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update holiday: ' . $e->getMessage());
    }
}

function deleteHoliday(PDO $db, array $user): void {
    if (!hasRole('admin')) {
        jsonResponse(false, 'Access denied. Admin privileges required.', [], 403);
    }

    $id = (int)($_POST['holiday_id'] ?? 0);
    if ($id <= 0) {
        jsonResponse(false, 'Invalid holiday ID');
    }

    try {
        $stmt = $db->prepare("DELETE FROM holidays WHERE id = ?");
        $stmt->execute([$id]);

        logActivity('Delete Holiday', 'Holidays', "Admin deleted holiday ID {$id}");
        jsonResponse(true, 'Holiday deleted successfully');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to delete holiday: ' . $e->getMessage());
    }
}
