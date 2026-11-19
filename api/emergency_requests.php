<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$db = Database::getConnection();
ensureEmergencyAndFlagSchema($db);

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'submit':
        submitEmergencyRequest($user, $db);
        break;
    case 'approve':
        approveEmergencyRequest($user, $db);
        break;
    case 'reject':
        rejectEmergencyRequest($user, $db);
        break;
    case 'cancel':
        cancelEmergencyRequest($user, $db);
        break;
    case 'get_list':
        getEmergencyRequestsList($user, $db);
        break;
    case 'details':
        getEmergencyRequestDetails($user, $db);
        break;
    default:
        jsonResponse(false, 'Invalid emergency request action', [], 400);
}

function submitEmergencyRequest(array $user, PDO $db): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $reason = sanitize($_POST['reason'] ?? '');
    $message = sanitize($_POST['message'] ?? '');

    if (!$taskId) {
        jsonResponse(false, 'Please select a valid task for this emergency request.');
    }
    if (empty($reason)) {
        jsonResponse(false, 'Emergency reason is required.');
    }
    if (empty($message)) {
        jsonResponse(false, 'Emergency description/message explaining the urgency is required.');
    }

    // Verify task exists
    $tStmt = $db->prepare("SELECT * FROM tasks WHERE id = ?");
    $tStmt->execute([$taskId]);
    $task = $tStmt->fetch();

    if (!$task) {
        jsonResponse(false, 'The selected task was not found.');
    }

    $userId = (int)$user['id'];
    $isAssigned = ((int)($task['assigned_to'] ?? 0) === $userId || (int)($task['assigned_to_employee_id'] ?? 0) === $userId);
    $canManage = canCreateOrEditTasks($user);

    // Regular employee can only submit emergency request for their assigned task
    if (!$isAssigned && !$canManage) {
        jsonResponse(false, 'You can only submit emergency requests for tasks assigned to you.', [], 403);
    }

    // Prevent duplicate pending requests for the same task
    $dupStmt = $db->prepare("SELECT id FROM emergency_task_requests WHERE task_id = ? AND employee_id = ? AND status = 'pending'");
    $dupStmt->execute([$taskId, $userId]);
    if ($dupStmt->fetch()) {
        jsonResponse(false, 'An emergency request is already pending approval for this task.');
    }

    // Handle optional file attachment
    $attachmentName = null;
    if (!empty($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $allowedExts = ['pdf', 'png', 'jpg', 'jpeg', 'zip', 'doc', 'docx', 'txt'];
        $origName = basename($_FILES['attachment']['name']);
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (in_array($ext, $allowedExts)) {
            $uploadDir = __DIR__ . '/../public/uploads/emergencies/';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0777, true);
            }
            $newFileName = 'emg_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadDir . $newFileName)) {
                $attachmentName = $newFileName;
            }
        }
    }

    try {
        $stmt = $db->prepare("
            INSERT INTO emergency_task_requests 
            (task_id, employee_id, reason, message, attachment, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([$taskId, $userId, $reason, $message, $attachmentName]);
        $requestId = (int)$db->lastInsertId();
        if (!$requestId) {
            $requestId = (int)$db->query("SELECT MAX(id) FROM emergency_task_requests")->fetchColumn();
        }

        // Notify managers and product managers
        $notifTitle = "🚨 Emergency Request: " . $task['title'];
        $notifMsg = "{$user['name']} submitted an urgent request: '{$reason}'. Action required.";
        $notifLink = "emergency-approvals";
        notifyManagersAndProductManagers($notifTitle, $notifMsg, $notifLink, $userId, 'emergency_request');

        logActivity('Emergency Request', 'Tasks', "Emergency task request #{$requestId} submitted by {$user['name']} for task #{$taskId} ('{$task['title']}')", $userId);

        jsonResponse(true, 'Emergency task request submitted successfully! Your managers have been notified for immediate review.', [
            'request_id' => $requestId,
            'status' => 'pending'
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to submit emergency request: ' . $e->getMessage());
    }
}

function approveEmergencyRequest(array $user, PDO $db): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers and administrators can approve emergency requests.', [], 403);
    }

    $requestId = (int)($_POST['request_id'] ?? 0);
    $comment = sanitize($_POST['reviewer_comment'] ?? '');

    if (!$requestId) {
        jsonResponse(false, 'Emergency request ID is required.');
    }

    try {
        $rStmt = $db->prepare("
            SELECT r.*, t.title as task_title, u.name as employee_name, u.id as emp_id 
            FROM emergency_task_requests r
            JOIN tasks t ON r.task_id = t.id
            JOIN users u ON r.employee_id = u.id
            WHERE r.id = ?
        ");
        $rStmt->execute([$requestId]);
        $req = $rStmt->fetch();

        if (!$req) {
            jsonResponse(false, 'Emergency request not found.');
        }

        if ($req['status'] !== 'pending') {
            jsonResponse(false, "This request has already been processed (Current status: " . ucfirst($req['status']) . ").");
        }

        // Concurrency safeguard
        $upd = $db->prepare("
            UPDATE emergency_task_requests 
            SET status = 'approved', reviewer_id = ?, reviewer_comment = ?, reviewed_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP
            WHERE id = ? AND status = 'pending'
        ");
        $upd->execute([$user['id'], $comment, $requestId]);

        if ($upd->rowCount() === 0) {
            jsonResponse(false, 'Request could not be updated. It may have already been reviewed by another manager.');
        }

        // Notify the employee
        $empId = (int)$req['employee_id'];
        $notifTitle = "✅ Emergency Request Approved";
        $notifMsg = "Your emergency request for '{$req['task_title']}' was APPROVED by {$user['name']}." . (!empty($comment) ? " Note: {$comment}" : "");
        sendNotification($empId, $notifTitle, $notifMsg, "task-details?id={$req['task_id']}", 'emergency_approved');

        logActivity('Approve Emergency Request', 'Tasks', "Emergency request #{$requestId} for task #{$req['task_id']} approved by {$user['name']}", (int)$user['id']);

        jsonResponse(true, 'Emergency request approved successfully! Employee has been notified.', [
            'request_id' => $requestId,
            'status' => 'approved'
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error approving request: ' . $e->getMessage());
    }
}

function rejectEmergencyRequest(array $user, PDO $db): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers and administrators can reject emergency requests.', [], 403);
    }

    $requestId = (int)($_POST['request_id'] ?? 0);
    $rejectionReason = sanitize($_POST['rejection_reason'] ?? '');

    if (!$requestId) {
        jsonResponse(false, 'Emergency request ID is required.');
    }

    if (empty($rejectionReason)) {
        jsonResponse(false, 'Please provide a reason for rejecting this emergency request.');
    }

    try {
        $rStmt = $db->prepare("
            SELECT r.*, t.title as task_title, u.name as employee_name, u.id as emp_id 
            FROM emergency_task_requests r
            JOIN tasks t ON r.task_id = t.id
            JOIN users u ON r.employee_id = u.id
            WHERE r.id = ?
        ");
        $rStmt->execute([$requestId]);
        $req = $rStmt->fetch();

        if (!$req) {
            jsonResponse(false, 'Emergency request not found.');
        }

        if ($req['status'] !== 'pending') {
            jsonResponse(false, "This request has already been processed (Current status: " . ucfirst($req['status']) . ").");
        }

        $upd = $db->prepare("
            UPDATE emergency_task_requests 
            SET status = 'rejected', reviewer_id = ?, rejection_reason = ?, reviewed_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP
            WHERE id = ? AND status = 'pending'
        ");
        $upd->execute([$user['id'], $rejectionReason, $requestId]);

        if ($upd->rowCount() === 0) {
            jsonResponse(false, 'Request could not be updated. It may have already been reviewed by another manager.');
        }

        // Notify the employee
        $empId = (int)$req['employee_id'];
        $notifTitle = "❌ Emergency Request Rejected";
        $notifMsg = "Your emergency request for '{$req['task_title']}' was rejected by {$user['name']}. Reason: {$rejectionReason}";
        sendNotification($empId, $notifTitle, $notifMsg, "task-details?id={$req['task_id']}", 'emergency_rejected');

        logActivity('Reject Emergency Request', 'Tasks', "Emergency request #{$requestId} for task #{$req['task_id']} rejected by {$user['name']}. Reason: {$rejectionReason}", (int)$user['id']);

        jsonResponse(true, 'Emergency request rejected. Employee has been notified with the rejection reason.', [
            'request_id' => $requestId,
            'status' => 'rejected'
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error rejecting request: ' . $e->getMessage());
    }
}

function cancelEmergencyRequest(array $user, PDO $db): void {
    $requestId = (int)($_POST['request_id'] ?? 0);
    if (!$requestId) {
        jsonResponse(false, 'Emergency request ID is required.');
    }

    try {
        $rStmt = $db->prepare("SELECT * FROM emergency_task_requests WHERE id = ?");
        $rStmt->execute([$requestId]);
        $req = $rStmt->fetch();

        if (!$req) {
            jsonResponse(false, 'Request not found.');
        }

        if ((int)$req['employee_id'] !== (int)$user['id'] && !hasRole('admin')) {
            jsonResponse(false, 'Permission denied. You can only cancel your own pending requests.', [], 403);
        }

        if ($req['status'] !== 'pending') {
            jsonResponse(false, 'Only pending requests can be cancelled.');
        }

        $upd = $db->prepare("UPDATE emergency_task_requests SET status = 'cancelled', updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $upd->execute([$requestId]);

        jsonResponse(true, 'Emergency request cancelled successfully.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error cancelling request: ' . $e->getMessage());
    }
}

function getEmergencyRequestsList(array $user, PDO $db): void {
    $statusFilter = sanitize($_GET['status'] ?? 'all');
    $taskIdFilter = (int)($_GET['task_id'] ?? 0);
    $canManage = canCreateOrEditTasks($user);
    $userId = (int)$user['id'];

    $where = [];
    $params = [];

    if (!$canManage) {
        $where[] = "r.employee_id = ?";
        $params[] = $userId;
    }

    if ($statusFilter !== 'all' && !empty($statusFilter)) {
        $where[] = "r.status = ?";
        $params[] = $statusFilter;
    }

    if ($taskIdFilter > 0) {
        $where[] = "r.task_id = ?";
        $params[] = $taskIdFilter;
    }

    $whereSql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

    try {
        $stmt = $db->prepare("
            SELECT r.*, 
                   t.title as task_title, t.priority as task_priority, t.status as task_status,
                   p.name as project_name,
                   d.name as department_name,
                   u_emp.name as employee_name, u_emp.avatar as employee_avatar, u_emp.designation as employee_designation,
                   u_rev.name as reviewer_name
            FROM emergency_task_requests r
            JOIN tasks t ON r.task_id = t.id
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN departments d ON t.department_id = d.id
            JOIN users u_emp ON r.employee_id = u_emp.id
            LEFT JOIN users u_rev ON r.reviewer_id = u_rev.id
            {$whereSql}
            ORDER BY CASE WHEN r.status = 'pending' THEN 0 ELSE 1 END, r.id DESC
        ");
        $stmt->execute($params);
        $requests = $stmt->fetchAll();

        jsonResponse(true, 'Emergency requests fetched.', $requests);
    } catch (Exception $e) {
        jsonResponse(false, 'Error fetching requests: ' . $e->getMessage());
    }
}

function getEmergencyRequestDetails(array $user, PDO $db): void {
    $requestId = (int)($_GET['request_id'] ?? ($_POST['request_id'] ?? 0));
    if (!$requestId) {
        jsonResponse(false, 'Request ID is required.');
    }

    try {
        $stmt = $db->prepare("
            SELECT r.*, 
                   t.title as task_title, t.priority as task_priority, t.status as task_status, t.deadline as task_deadline,
                   p.name as project_name,
                   d.name as department_name,
                   u_emp.name as employee_name, u_emp.avatar as employee_avatar, u_emp.designation as employee_designation,
                   u_rev.name as reviewer_name
            FROM emergency_task_requests r
            JOIN tasks t ON r.task_id = t.id
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN departments d ON t.department_id = d.id
            JOIN users u_emp ON r.employee_id = u_emp.id
            LEFT JOIN users u_rev ON r.reviewer_id = u_rev.id
            WHERE r.id = ?
        ");
        $stmt->execute([$requestId]);
        $req = $stmt->fetch();

        if (!$req) {
            jsonResponse(false, 'Emergency request not found.');
        }

        $canManage = canCreateOrEditTasks($user);
        if (!$canManage && (int)$req['employee_id'] !== (int)$user['id']) {
            jsonResponse(false, 'Permission denied.', [], 403);
        }

        jsonResponse(true, 'Emergency request details fetched.', $req);
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}
