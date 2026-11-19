<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

header('Content-Type: application/json');

$user = getCurrentUser();
if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized authentication required']);
    exit;
}

$db = Database::getConnection();

// Ensure chat table exists
try {
    $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? Database::getDriver());
    $autoInc = ($driver === 'sqlite') ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
    $db->exec("
        CREATE TABLE IF NOT EXISTS `chat_messages` (
            `id` {$autoInc},
            `sender_id` INT NOT NULL,
            `receiver_id` INT NULL,
            `department_id` INT NULL,
            `message` TEXT NOT NULL,
            `file_path` VARCHAR(255) NULL,
            `file_type` VARCHAR(50) NULL,
            `file_name` VARCHAR(255) NULL,
            `reply_to_id` INT NULL,
            `is_seen` TINYINT DEFAULT 0,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
} catch (Exception $e) {}

$action = $_REQUEST['action'] ?? '';

switch ($action) {
    case 'get_contacts':
        try {
            $myId = (int)$user['id'];

            // 1. Department Channels (All active departments)
            $deptStmt = $db->query("SELECT id, name, code FROM departments ORDER BY name ASC");
            $departments = $deptStmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Direct Contacts (All active team members except self)
            $userStmt = $db->prepare("
                SELECT u.id, u.name, u.email, u.designation, u.status, u.role_id, u.department_id, d.name as dept_name
                FROM users u
                LEFT JOIN departments d ON u.department_id = d.id
                WHERE u.id != ? AND u.status = 'active'
                ORDER BY u.name ASC
            ");
            $userStmt->execute([$myId]);
            $contacts = $userStmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => ['departments' => $departments, 'contacts' => $contacts]]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    case 'get_messages':
        try {
            $myId = (int)$user['id'];
            $receiverId = (!empty($_GET['receiver_id']) && is_numeric($_GET['receiver_id'])) ? (int)$_GET['receiver_id'] : null;
            $deptId = (!empty($_GET['department_id']) && is_numeric($_GET['department_id'])) ? (int)$_GET['department_id'] : null;

            if ($deptId !== null) {
                // Department Channel Messages
                $stmt = $db->prepare("
                    SELECT m.*, u.name as sender_name, u.avatar as sender_avatar, r.name as role_name
                    FROM chat_messages m
                    JOIN users u ON m.sender_id = u.id
                    LEFT JOIN roles r ON u.role_id = r.id
                    WHERE m.department_id = ?
                    ORDER BY m.created_at ASC
                    LIMIT 200
                ");
                $stmt->execute([$deptId]);
            } elseif ($receiverId !== null) {
                // 1-on-1 Personal Direct Messages (using positional parameters to prevent HY093)
                $stmt = $db->prepare("
                    SELECT m.*, u.name as sender_name, u.avatar as sender_avatar, r.name as role_name
                    FROM chat_messages m
                    JOIN users u ON m.sender_id = u.id
                    LEFT JOIN roles r ON u.role_id = r.id
                    WHERE (
                        (m.sender_id = ? AND m.receiver_id = ?)
                        OR (m.sender_id = ? AND m.receiver_id = ?)
                    ) AND (m.department_id IS NULL OR m.department_id = 0)
                    ORDER BY m.created_at ASC
                    LIMIT 200
                ");
                $stmt->execute([$myId, $receiverId, $receiverId, $myId]);

                // Mark unseen messages as seen
                try {
                    $updStmt = $db->prepare("UPDATE chat_messages SET is_seen = 1 WHERE receiver_id = ? AND sender_id = ? AND is_seen = 0");
                    $updStmt->execute([$myId, $receiverId]);
                } catch (Exception $seeEx) {}
            } else {
                echo json_encode(['success' => false, 'message' => 'Target receiver or department channel required', 'data' => []]);
                exit;
            }

            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'data' => $messages]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage(), 'data' => []]);
        }
        break;

    case 'send_message':
        try {
            $myId = (int)$user['id'];
            $receiverId = (!empty($_POST['receiver_id']) && is_numeric($_POST['receiver_id'])) ? (int)$_POST['receiver_id'] : null;
            $deptId = (!empty($_POST['department_id']) && is_numeric($_POST['department_id'])) ? (int)$_POST['department_id'] : null;
            $messageText = trim($_POST['message'] ?? '');

            // Ensure mutual exclusivity between Channel and Direct Message
            if ($receiverId !== null) {
                $deptId = null;
            } elseif ($deptId !== null) {
                $receiverId = null;
            } else {
                echo json_encode(['success' => false, 'message' => 'Please select a recipient member or channel.']);
                exit;
            }

            // File Attachment Handling
            $filePath = null;
            $fileName = null;
            $fileType = null;

            if (!empty($_FILES['attachment']['name'])) {
                $uploadDir = __DIR__ . '/../public/assets/uploads/chat/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $origName = basename($_FILES['attachment']['name']);
                $ext = pathinfo($origName, PATHINFO_EXTENSION);
                $newName = 'chat_' . time() . '_' . uniqid() . '.' . $ext;
                $targetFile = $uploadDir . $newName;

                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
                    $filePath = '/assets/uploads/chat/' . $newName;
                    $fileName = $origName;
                    $fileType = $_FILES['attachment']['type'] ?? null;
                }
            }

            if (empty($messageText) && empty($filePath)) {
                echo json_encode(['success' => false, 'message' => 'Cannot send an empty message.']);
                exit;
            }

            $stmt = $db->prepare("
                INSERT INTO chat_messages (sender_id, receiver_id, department_id, message, file_path, file_type, file_name, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
            ");
            $stmt->execute([
                $myId,
                $receiverId,
                $deptId,
                $messageText,
                $filePath,
                $fileType,
                $fileName
            ]);

            try {
                logActivity('Send Message', 'Chat', 'Sent message in ' . ($deptId ? 'department channel' : 'direct message'), $myId);
            } catch (Exception $actEx) {}

            echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to send message: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
