<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$db = Database::getConnection();
ensureEmergencyAndFlagSchema($db);

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'submit':
        submitTaskFlag($user, $db);
        break;
    case 'add_comment':
        addFlagComment($user, $db);
        break;
    case 'update_status':
        updateFlagStatus($user, $db);
        break;
    case 'resolve':
        resolveTaskFlag($user, $db);
        break;
    case 'reopen':
        reopenTaskFlag($user, $db);
        break;
    case 'get_list':
        getTaskFlagsList($user, $db);
        break;
    case 'details':
        getTaskFlagDetails($user, $db);
        break;
    default:
        jsonResponse(false, 'Invalid task flag action', [], 400);
}

function submitTaskFlag(array $user, PDO $db): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $category = sanitize($_POST['category'] ?? '');
    $missingRequirement = sanitize($_POST['missing_requirement'] ?? '');
    $impact = sanitize($_POST['impact'] ?? '');

    if (!$taskId) {
        jsonResponse(false, 'Please select a valid task to flag.');
    }
    if (empty($category)) {
        jsonResponse(false, 'Flag category is required.');
    }
    if (empty($missingRequirement)) {
        jsonResponse(false, 'Please detail the missing requirements or blockers.');
    }
    if (empty($impact)) {
        jsonResponse(false, 'Please specify the impact on the task.');
    }

    // Check task
    $tStmt = $db->prepare("SELECT * FROM tasks WHERE id = ?");
    $tStmt->execute([$taskId]);
    $task = $tStmt->fetch();

    if (!$task) {
        jsonResponse(false, 'Task not found.');
    }

    $userId = (int)$user['id'];
    $isAssigned = ((int)($task['assigned_to'] ?? 0) === $userId || (int)($task['assigned_to_employee_id'] ?? 0) === $userId);
    $canManage = canCreateOrEditTasks($user);

    if (!$isAssigned && !$canManage) {
        jsonResponse(false, 'You can only flag tasks assigned to you.', [], 403);
    }

    // Optional attachment
    $attachmentName = null;
    if (!empty($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $allowedExts = ['pdf', 'png', 'jpg', 'jpeg', 'zip', 'doc', 'docx', 'txt'];
        $origName = basename($_FILES['attachment']['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (in_array($ext, $allowedExts)) {
            $uploadDir = __DIR__ . '/../public/uploads/flags/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $newFileName = 'flag_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $newFileName)) {
                $attachmentName = $newFileName;
            }
        }
    }

    try {
        $stmt = $db->prepare("
            INSERT INTO task_flags 
            (task_id, employee_id, category, missing_requirement, impact, attachment, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, 'open', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([$taskId, $userId, $category, $missingRequirement, $impact, $attachmentName]);
        $flagId = (int)$db->lastInsertId();
        if (!$flagId) {
            $flagId = (int)$db->query("SELECT MAX(id) FROM task_flags")->fetchColumn();
        }

        // Add initial system thread comment
        $commentText = "Flag reported with impact: '{$impact}'. Requirement: {$missingRequirement}";
        $insComment = $db->prepare("INSERT INTO task_flag_comments (flag_id, user_id, comment, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
        $insComment->execute([$flagId, $userId, $commentText]);

        // Notify managers and admins
        $notifTitle = "🚩 Task Flagged: " . $task['title'];
        $notifMsg = "{$user['name']} flagged '{$task['title']}' [{$category}]. Impact: {$impact}.";
        $notifLink = "flagged-tasks";
        notifyManagersAndProductManagers($notifTitle, $notifMsg, $notifLink, $userId, 'task_flag');

        logActivity('Task Flagged', 'Tasks', "Task #{$taskId} flagged by {$user['name']} under '{$category}' (Flag #{$flagId})", $userId);

        jsonResponse(true, 'Task flagged successfully! Your managers have been notified to provide the missing requirements.', [
            'flag_id' => $flagId,
            'status' => 'open'
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to submit task flag: ' . $e->getMessage());
    }
}

function addFlagComment(array $user, PDO $db): void {
    $flagId = (int)($_POST['flag_id'] ?? 0);
    $comment = sanitize($_POST['comment'] ?? '');

    if (!$flagId || empty($comment)) {
        jsonResponse(false, 'Flag ID and comment are required.');
    }

    try {
        $fStmt = $db->prepare("SELECT f.*, t.title as task_title FROM task_flags f JOIN tasks t ON f.task_id = t.id WHERE f.id = ?");
        $fStmt->execute([$flagId]);
        $flag = $fStmt->fetch();

        if (!$flag) {
            jsonResponse(false, 'Flag not found.');
        }

        $userId = (int)$user['id'];
        $canManage = canCreateOrEditTasks($user);
        if (!$canManage && (int)$flag['employee_id'] !== $userId) {
            jsonResponse(false, 'Permission denied.', [], 403);
        }

        $ins = $db->prepare("INSERT INTO task_flag_comments (flag_id, user_id, comment, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
        $ins->execute([$flagId, $userId, $comment]);

        $db->prepare("UPDATE task_flags SET updated_at = CURRENT_TIMESTAMP WHERE id = ?")->execute([$flagId]);

        // Notification: if manager commented, notify employee. If employee commented, notify managers.
        if ($canManage && (int)$flag['employee_id'] !== $userId) {
            sendNotification((int)$flag['employee_id'], "💬 Update on Flag #{$flagId}", "{$user['name']} replied on your flagged task '{$flag['task_title']}': {$comment}", "task-details?id={$flag['task_id']}", 'flag_comment');
        } elseif (!$canManage) {
            notifyManagersAndProductManagers("💬 Update on Flag #{$flagId}", "{$user['name']} commented on flagged task '{$flag['task_title']}': {$comment}", "flagged-tasks", $userId, 'flag_comment');
        }

        jsonResponse(true, 'Comment added successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Error adding comment: ' . $e->getMessage());
    }
}

function updateFlagStatus(array $user, PDO $db): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers can change flag status.', [], 403);
    }

    $flagId = (int)($_POST['flag_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');

    $allowed = ['open', 'under_review', 'waiting_info'];
    if (!in_array($status, $allowed)) {
        jsonResponse(false, 'Invalid flag status transition.');
    }

    try {
        $fStmt = $db->prepare("SELECT f.*, t.title as task_title FROM task_flags f JOIN tasks t ON f.task_id = t.id WHERE f.id = ?");
        $fStmt->execute([$flagId]);
        $flag = $fStmt->fetch();

        if (!$flag) {
            jsonResponse(false, 'Flag not found.');
        }

        $upd = $db->prepare("UPDATE task_flags SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $upd->execute([$status, $flagId]);

        if (!empty($notes)) {
            $ins = $db->prepare("INSERT INTO task_flag_comments (flag_id, user_id, comment, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
            $ins->execute([$flagId, $user['id'], "Status updated to " . ucwords(str_replace('_', ' ', $status)) . ": " . $notes]);
        }

        // Notify employee
        sendNotification((int)$flag['employee_id'], "Flag Status Updated", "Your flagged issue on '{$flag['task_title']}' is now " . ucwords(str_replace('_', ' ', $status)), "task-details?id={$flag['task_id']}", 'flag_status');

        jsonResponse(true, 'Flag status updated successfully!', ['status' => $status]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error updating status: ' . $e->getMessage());
    }
}

function resolveTaskFlag(array $user, PDO $db): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers and administrators can resolve task flags.', [], 403);
    }

    $flagId = (int)($_POST['flag_id'] ?? 0);
    $resolutionMessage = sanitize($_POST['resolution_message'] ?? '');

    if (!$flagId) {
        jsonResponse(false, 'Flag ID is required.');
    }
    if (empty($resolutionMessage)) {
        jsonResponse(false, 'Please describe how the missing requirement was resolved.');
    }

    try {
        $fStmt = $db->prepare("SELECT f.*, t.title as task_title FROM task_flags f JOIN tasks t ON f.task_id = t.id WHERE f.id = ?");
        $fStmt->execute([$flagId]);
        $flag = $fStmt->fetch();

        if (!$flag) {
            jsonResponse(false, 'Flag not found.');
        }

        $upd = $db->prepare("
            UPDATE task_flags 
            SET status = 'resolved', resolution_message = ?, resolved_by = ?, resolved_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP 
            WHERE id = ?
        ");
        $upd->execute([$resolutionMessage, $user['id'], $flagId]);

        // Insert resolution log into comments thread
        $db->prepare("INSERT INTO task_flag_comments (flag_id, user_id, comment, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)")
           ->execute([$flagId, $user['id'], "Resolved: " . $resolutionMessage]);

        // Notify employee
        $notifTitle = "✅ Flag Resolved: " . $flag['task_title'];
        $notifMsg = "{$user['name']} has resolved the flagged requirement: {$resolutionMessage}";
        sendNotification((int)$flag['employee_id'], $notifTitle, $notifMsg, "task-details?id={$flag['task_id']}", 'flag_resolved');

        logActivity('Resolve Task Flag', 'Tasks', "Flag #{$flagId} for task #{$flag['task_id']} marked as resolved by {$user['name']}", (int)$user['id']);

        jsonResponse(true, 'Task flag marked as resolved! Employee has been notified.', [
            'flag_id' => $flagId,
            'status' => 'resolved'
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error resolving flag: ' . $e->getMessage());
    }
}

function reopenTaskFlag(array $user, PDO $db): void {
    $flagId = (int)($_POST['flag_id'] ?? 0);
    $reason = sanitize($_POST['reopen_reason'] ?? '');

    if (!$flagId) {
        jsonResponse(false, 'Flag ID is required.');
    }
    if (empty($reason)) {
        jsonResponse(false, 'Please explain why the issue is still unresolved.');
    }

    try {
        $fStmt = $db->prepare("SELECT f.*, t.title as task_title FROM task_flags f JOIN tasks t ON f.task_id = t.id WHERE f.id = ?");
        $fStmt->execute([$flagId]);
        $flag = $fStmt->fetch();

        if (!$flag) {
            jsonResponse(false, 'Flag not found.');
        }

        $userId = (int)$user['id'];
        $canManage = canCreateOrEditTasks($user);
        if (!$canManage && (int)$flag['employee_id'] !== $userId) {
            jsonResponse(false, 'Permission denied.', [], 403);
        }

        $upd = $db->prepare("UPDATE task_flags SET status = 'reopened', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $upd->execute([$flagId]);

        // Thread comment
        $db->prepare("INSERT INTO task_flag_comments (flag_id, user_id, comment, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)")
           ->execute([$flagId, $userId, "Flag Reopened: " . $reason]);

        // Notify managers
        notifyManagersAndProductManagers("⚠️ Flag Reopened: " . $flag['task_title'], "{$user['name']} has reopened flag #{$flagId}. Note: {$reason}", "flagged-tasks", $userId, 'flag_reopened');

        logActivity('Reopen Task Flag', 'Tasks', "Flag #{$flagId} reopened by {$user['name']}. Reason: {$reason}", (int)$user['id']);

        jsonResponse(true, 'Task flag has been reopened. Managers have been alerted.', [
            'flag_id' => $flagId,
            'status' => 'reopened'
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error reopening flag: ' . $e->getMessage());
    }
}

function getTaskFlagsList(array $user, PDO $db): void {
    $statusFilter = sanitize($_GET['status'] ?? 'all');
    $taskIdFilter = (int)($_GET['task_id'] ?? 0);
    $canManage = canCreateOrEditTasks($user);
    $userId = (int)$user['id'];

    $where = [];
    $params = [];

    if (!$canManage) {
        $where[] = "f.employee_id = ?";
        $params[] = $userId;
    }

    if ($statusFilter !== 'all' && !empty($statusFilter)) {
        $where[] = "f.status = ?";
        $params[] = $statusFilter;
    }

    if ($taskIdFilter > 0) {
        $where[] = "f.task_id = ?";
        $params[] = $taskIdFilter;
    }

    $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    try {
        $stmt = $db->prepare("
            SELECT f.*, 
                   t.title as task_title, t.priority as task_priority, t.status as task_status,
                   p.name as project_name,
                   d.name as department_name,
                   u_emp.name as employee_name, u_emp.avatar as employee_avatar, u_emp.designation as employee_designation,
                   u_res.name as resolver_name,
                   (SELECT COUNT(*) FROM task_flag_comments WHERE flag_id = f.id) as comment_count
            FROM task_flags f
            JOIN tasks t ON f.task_id = t.id
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN departments d ON t.department_id = d.id
            JOIN users u_emp ON f.employee_id = u_emp.id
            LEFT JOIN users u_res ON f.resolved_by = u_res.id
            {$whereSql}
            ORDER BY CASE WHEN f.status = 'open' THEN 0 WHEN f.status = 'reopened' THEN 1 WHEN f.status = 'under_review' THEN 2 ELSE 3 END, f.id DESC
        ");
        $stmt->execute($params);
        $flags = $stmt->fetchAll();

        jsonResponse(true, 'Task flags fetched.', $flags);
    } catch (Exception $e) {
        jsonResponse(false, 'Error fetching flags: ' . $e->getMessage());
    }
}

function getTaskFlagDetails(array $user, PDO $db): void {
    $flagId = (int)($_GET['flag_id'] ?? ($_POST['flag_id'] ?? 0));
    if (!$flagId) {
        jsonResponse(false, 'Flag ID is required.');
    }

    try {
        $stmt = $db->prepare("
            SELECT f.*, 
                   t.title as task_title, t.priority as task_priority, t.status as task_status, t.deadline as task_deadline,
                   p.name as project_name,
                   d.name as department_name,
                   u_emp.name as employee_name, u_emp.avatar as employee_avatar, u_emp.designation as employee_designation,
                   u_res.name as resolver_name
            FROM task_flags f
            JOIN tasks t ON f.task_id = t.id
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN departments d ON t.department_id = d.id
            JOIN users u_emp ON f.employee_id = u_emp.id
            LEFT JOIN users u_res ON f.resolved_by = u_res.id
            WHERE f.id = ?
        ");
        $stmt->execute([$flagId]);
        $flag = $stmt->fetch();

        if (!$flag) {
            jsonResponse(false, 'Flag not found.');
        }

        $canManage = canCreateOrEditTasks($user);
        if (!$canManage && (int)$flag['employee_id'] !== (int)$user['id']) {
            jsonResponse(false, 'Permission denied.', [], 403);
        }

        // Fetch comments
        $cStmt = $db->prepare("
            SELECT c.*, u.name as user_name, u.avatar as user_avatar, u.designation as user_designation
            FROM task_flag_comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.flag_id = ?
            ORDER BY c.id ASC
        ");
        $cStmt->execute([$flagId]);
        $comments = $cStmt->fetchAll();

        $flag['comments'] = $comments;

        jsonResponse(true, 'Flag details fetched.', $flag);
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}
