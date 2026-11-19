<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'fetch':
        fetchNotifications($user);
        break;
    case 'mark_all_read':
        markAllNotificationsRead($user);
        break;
    case 'mark_read':
        markNotificationRead($user);
        break;
    default:
        jsonResponse(false, 'Invalid notification action', [], 400);
}

function fetchNotifications(array $user): void {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY id DESC LIMIT 15");
        $stmt->execute([$user['id']]);
        $rows = $stmt->fetchAll();

        $unreadStmt = $db->prepare("SELECT COUNT(*) as cnt FROM notifications WHERE user_id = ? AND is_read = 0");
        $unreadStmt->execute([$user['id']]);
        $unreadCount = (int)($unreadStmt->fetch()['cnt'] ?? 0);

        $formatted = array_map(function($n) {
            $n['time_ago'] = formatISTDateTime($n['created_at']);
            return $n;
        }, $rows);

        jsonResponse(true, 'Notifications loaded', [
            'data' => $formatted,
            'unread_count' => $unreadCount
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function markAllNotificationsRead(array $user): void {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        jsonResponse(true, 'All notifications marked as read.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function markNotificationRead(array $user): void {
    $id = (int)($_POST['id'] ?? ($_GET['id'] ?? 0));
    if (!$id) {
        jsonResponse(false, 'Notification ID required.');
    }
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $user['id']]);
        jsonResponse(true, 'Notification marked as read.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}
