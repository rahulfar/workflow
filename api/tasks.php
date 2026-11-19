<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'get':
    case 'get_task':
    case 'details':
        getTaskDetails($user);
        break;
    case 'create':
        createTask($user);
        break;
    case 'update':
    case 'edit':
        updateTask($user);
        break;
    case 'update_status':
        updateTaskStatus($user);
        break;
    case 'submit_completion':
        submitTaskCompletion($user);
        break;
    case 'approve_task':
        approveTask($user);
        break;
    case 'reject_approval':
        rejectTaskApproval($user);
        break;
    case 'pending_approvals':
        getPendingApprovals($user);
        break;
    case 'reassign':
        reassignTask($user);
        break;
    case 'reject':
        rejectTask($user);
        break;
    case 'add_comment':
        addComment($user);
        break;
    case 'add_log':
        addWorkLog($user);
        break;
    case 'delete':
        deleteTask($user);
        break;
    case 'clone':
        cloneTask($user);
        break;
    default:
        jsonResponse(false, 'Invalid task action', [], 400);
}

function createTask(array $user): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers and leads are allowed to create tasks.', [], 403);
    }

    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $assignedTo = (int)($_POST['assigned_to'] ?? 0);
    $priority = sanitize($_POST['priority'] ?? 'medium');
    $estimatedHours = (float)($_POST['estimated_hours'] ?? 0);
    $rawDeadline = sanitize($_POST['deadline'] ?? '');
    $deadline = !empty($rawDeadline) ? date('Y-m-d H:i:s', strtotime($rawDeadline)) : date('Y-m-d H:i:s', strtotime('+3 days'));

    if (empty($title) || empty($assignedTo)) {
        jsonResponse(false, 'Task Title and Assignee are required.');
    }

    // Role-specific Assignment Permission Validation
    if (!isAssigneeAllowedForUser($user, $assignedTo)) {
        $userId = (int)($user['id'] ?? 0);
        $userName = strtolower(trim($user['name'] ?? ''));
        if ($userId === 5 || $userName === 'uvaish') {
            jsonResponse(false, 'Permission denied. SEO Lead Manager (Uvaish) can only assign tasks to Intern SEO Analyst (Aman) and Senior SEO Executive (Shivanshu).', [], 403);
        } elseif ($userId === 4 || $userName === 'rahul') {
            jsonResponse(false, 'Permission denied. Senior Fullstack Developer (Rahul) can only assign tasks to Intern Frontend Developer (Abhay).', [], 403);
        } else {
            jsonResponse(false, 'Permission denied. You are not authorized to assign tasks to this employee.', [], 403);
        }
    }

    try {
        $db = Database::getConnection();

        // Determine department: use assigned employee's department if not specified
        $departmentId = (int)($_POST['department_id'] ?? 0);
        if ($departmentId <= 0 && $assignedTo > 0) {
            $uDeptStmt = $db->prepare("SELECT department_id FROM users WHERE id = ?");
            $uDeptStmt->execute([$assignedTo]);
            $departmentId = (int)($uDeptStmt->fetchColumn() ?: ($user['department_id'] ?? 1));
        }
        if ($departmentId <= 0) {
            $departmentId = (int)($user['department_id'] ?? 1);
        }

        $stmt = $db->prepare("
            INSERT INTO tasks (project_id, department_id, assigned_to_employee_id, assigned_to, assigned_by, title, description, priority, estimated_hours, deadline)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$projectId, $departmentId, $assignedTo, $assignedTo, $user['id'], $title, $description, $priority, $estimatedHours, $deadline]);
        $taskId = (int)$db->lastInsertId();
        if (!$taskId) {
            $taskId = (int)$db->query("SELECT MAX(id) FROM tasks")->fetchColumn();
        }

        sendNotification($assignedTo, 'New Task Assigned', "You were assigned task: '{$title}' by {$user['name']}", "task-details?id={$taskId}", 'task');
        logActivity('Create Task', 'Tasks', "Task #{$taskId} ('{$title}') created by {$user['name']} and assigned to User #{$assignedTo}");

        jsonResponse(true, 'Task created and assigned successfully!', ['task_id' => $taskId]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to create task: ' . $e->getMessage());
    }
}

function updateTaskStatus(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $status = sanitize($_POST['status'] ?? '');

    if (!$taskId || !$status) {
        jsonResponse(false, 'Task ID and Status required.');
    }

    try {
        $db = Database::getConnection();
        ensureTaskApprovalSchema($db);

        $tStmt = $db->prepare("SELECT id, title, assigned_to, assigned_by, status, spent_seconds FROM tasks WHERE id = ?");
        $tStmt->execute([$taskId]);
        $task = $tStmt->fetch();

        if (!$task) {
            jsonResponse(false, 'Task not found.');
        }

        $isAssigned = ((int)$task['assigned_to'] === (int)$user['id']);
        $canManage = canCreateOrEditTasks();

        // If target status is completed/done
        if (in_array(strtolower($status), ['completed', 'done'])) {
            if (!$canManage) {
                // Employee trying to complete task -> Intercept and route to completion approval request!
                $_POST['task_id'] = $taskId;
                $_POST['notes'] = sanitize($_POST['notes'] ?? 'Task completed and submitted for manager approval.');
                submitTaskCompletion($user);
                return;
            } else {
                // Manager/Admin approving/marking completed directly
                $_POST['task_id'] = $taskId;
                approveTask($user);
                return;
            }
        }

        // If target status is pending_approval or in_review
        if (in_array(strtolower($status), ['pending_approval', 'in_review'])) {
            $_POST['task_id'] = $taskId;
            submitTaskCompletion($user);
            return;
        }

        // Permission check for other statuses
        if (!$isAssigned && !$canManage) {
            jsonResponse(false, 'Permission denied. You cannot alter this task status.', [], 403);
        }

        $stmt = $db->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        $stmt->execute([$status, $taskId]);

        logActivity('Task Status Update', 'Tasks', "Task #{$taskId} status updated to {$status}");
        jsonResponse(true, 'Task status updated.', ['task_id' => $taskId, 'status' => $status]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function submitTaskCompletion(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $completionNotes = sanitize($_POST['notes'] ?? ($_POST['completion_notes'] ?? 'Task completed and submitted for approval.'));

    if (!$taskId) {
        jsonResponse(false, 'Task ID required.');
    }

    try {
        $db = Database::getConnection();
        ensureTaskApprovalSchema($db);

        $tStmt = $db->prepare("SELECT id, title, assigned_to, assigned_by, status, spent_seconds FROM tasks WHERE id = ?");
        $tStmt->execute([$taskId]);
        $task = $tStmt->fetch();

        if (!$task) {
            jsonResponse(false, 'Task not found.');
        }

        $isAssigned = ((int)$task['assigned_to'] === (int)$user['id']);
        $canManage = canCreateOrEditTasks();

        if (!$isAssigned && !$canManage) {
            jsonResponse(false, 'Permission denied. Only assigned employee or manager can submit this task.', [], 403);
        }

        // Close running timer if active
        $nowStr = date('Y-m-d H:i:s');
        $timerStmt = $db->prepare("SELECT * FROM task_timer WHERE task_id = ? AND user_id = ? AND status = 'running' ORDER BY id DESC LIMIT 1");
        $timerStmt->execute([$taskId, $user['id']]);
        $activeTimer = $timerStmt->fetch();
        if ($activeTimer) {
            $sessionDuration = time() - strtotime($activeTimer['start_time']);
            $db->prepare("UPDATE task_timer SET status = 'completed', end_time = ?, duration_seconds = duration_seconds + ? WHERE id = ?")
               ->execute([$nowStr, $sessionDuration, $activeTimer['id']]);
            $db->prepare("UPDATE tasks SET spent_seconds = spent_seconds + ? WHERE id = ?")
               ->execute([$sessionDuration, $taskId]);
        }

        // Update task status to pending_approval
        $upd = $db->prepare("
            UPDATE tasks 
            SET status = 'pending_approval', 
                submitted_for_approval_at = ?, 
                completion_notes = ? 
            WHERE id = ?
        ");
        $upd->execute([$nowStr, $completionNotes, $taskId]);

        // Log work record in task_logs
        try {
            $logDate = date('Y-m-d');
            $logStmt = $db->prepare("INSERT INTO task_logs (task_id, user_id, log_date, today_work, progress_percentage, completed_work) VALUES (?, ?, ?, ?, 100, ?)");
            $logStmt->execute([$taskId, $user['id'], $logDate, $completionNotes, $completionNotes]);
        } catch (Exception $logEx) {}

        // Send real-time notifications to Manager and Product Manager
        $notifTitle = "Task Completion Approval Request";
        $notifMsg = "Employee {$user['name']} has completed task #{$taskId} ('{$task['title']}') and submitted it for your approval.";
        $notifLink = "task-details?id={$taskId}";
        notifyManagersAndProductManagers($notifTitle, $notifMsg, $notifLink, $user['id'], 'task_approval');

        // Also notify the assigner if different and manager/admin
        if (!empty($task['assigned_by']) && (int)$task['assigned_by'] !== (int)$user['id']) {
            sendNotification((int)$task['assigned_by'], $notifTitle, $notifMsg, $notifLink, 'task_approval');
        }

        logActivity('Task Submitted for Approval', 'Tasks', "Employee {$user['name']} completed task #{$taskId} ('{$task['title']}') and submitted for manager & product manager approval.");

        jsonResponse(true, 'Task completed and submitted to Manager & Product Manager for approval!', [
            'task_id' => $taskId,
            'status' => 'pending_approval'
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error submitting task: ' . $e->getMessage());
    }
}

function approveTask(array $user): void {
    if (!canCreateOrEditTasks()) {
        jsonResponse(false, 'Permission denied. Only Manager, Product Manager, or Admin can approve completed tasks.', [], 403);
    }

    $taskId = (int)($_POST['task_id'] ?? 0);

    if (!$taskId) {
        jsonResponse(false, 'Task ID required.');
    }

    try {
        $db = Database::getConnection();
        ensureTaskApprovalSchema($db);

        $tStmt = $db->prepare("SELECT id, title, assigned_to, assigned_by, status FROM tasks WHERE id = ?");
        $tStmt->execute([$taskId]);
        $task = $tStmt->fetch();

        if (!$task) {
            jsonResponse(false, 'Task not found.');
        }

        $nowStr = date('Y-m-d H:i:s');

        // Change task status to 'completed' (Done)
        $upd = $db->prepare("
            UPDATE tasks 
            SET status = 'completed', 
                completed_at = ?, 
                approved_by = ?, 
                approved_at = ?,
                rejection_reason = NULL
            WHERE id = ?
        ");
        $upd->execute([$nowStr, $user['id'], $nowStr, $taskId]);

        // Close any running timers on this task
        $db->prepare("UPDATE task_timer SET status = 'completed', end_time = ? WHERE task_id = ? AND status = 'running'")
           ->execute([$nowStr, $taskId]);

        // Send approval confirmation notification to the employee
        if (!empty($task['assigned_to'])) {
            $empNotifTitle = "Task Approved & Marked Done!";
            $empNotifMsg = "Your completed task #{$taskId} ('{$task['title']}') was approved by {$user['name']} and marked as Done.";
            sendNotification((int)$task['assigned_to'], $empNotifTitle, $empNotifMsg, "task-details?id={$taskId}", 'task');
        }

        logActivity('Task Approved', 'Tasks', "Manager {$user['name']} approved completed task #{$taskId} ('{$task['title']}'). Status changed to Done.");

        jsonResponse(true, "Task #{$taskId} approved successfully and marked as Done!", [
            'task_id' => $taskId,
            'status' => 'completed',
            'approved_by' => $user['name'],
            'approved_at' => $nowStr
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error approving task: ' . $e->getMessage());
    }
}

function rejectTaskApproval(array $user): void {
    if (!canCreateOrEditTasks()) {
        jsonResponse(false, 'Permission denied. Only Manager, Product Manager, or Admin can return tasks for revision.', [], 403);
    }

    $taskId = (int)($_POST['task_id'] ?? 0);
    $reason = sanitize($_POST['reason'] ?? ($_POST['rejection_reason'] ?? 'Revisions requested by manager.'));

    if (!$taskId) {
        jsonResponse(false, 'Task ID required.');
    }

    try {
        $db = Database::getConnection();
        ensureTaskApprovalSchema($db);

        $tStmt = $db->prepare("SELECT id, title, assigned_to, assigned_by, status FROM tasks WHERE id = ?");
        $tStmt->execute([$taskId]);
        $task = $tStmt->fetch();

        if (!$task) {
            jsonResponse(false, 'Task not found.');
        }

        // Return status to 'running' or 'pending' so employee can make revisions
        $upd = $db->prepare("
            UPDATE tasks 
            SET status = 'running', 
                rejection_reason = ? 
            WHERE id = ?
        ");
        $upd->execute([$reason, $taskId]);

        // Send notification to the employee
        if (!empty($task['assigned_to'])) {
            $empNotifTitle = "Task Completion Returned for Revision";
            $empNotifMsg = "Task #{$taskId} ('{$task['title']}') was reviewed by {$user['name']}. Revisions requested: {$reason}";
            sendNotification((int)$task['assigned_to'], $empNotifTitle, $empNotifMsg, "task-details?id={$taskId}", 'task');
        }

        logActivity('Task Revision Requested', 'Tasks', "Manager {$user['name']} returned task #{$taskId} for revision: {$reason}");

        jsonResponse(true, "Task #{$taskId} returned to employee for revision.", [
            'task_id' => $taskId,
            'status' => 'running',
            'reason' => $reason
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error returning task: ' . $e->getMessage());
    }
}

function getPendingApprovals(array $user): void {
    if (!canCreateOrEditTasks()) {
        jsonResponse(false, 'Permission denied.', [], 403);
    }

    try {
        $db = Database::getConnection();
        ensureTaskApprovalSchema($db);

        $stmt = $db->query("
            SELECT t.*, u_to.name as assignee_name, u_to.avatar as assignee_avatar, 
                   u_to.designation as assignee_designation,
                   p.name as project_name, d.name as department_name
            FROM tasks t
            JOIN users u_to ON t.assigned_to = u_to.id
            LEFT JOIN projects p ON t.project_id = p.id
            JOIN departments d ON t.department_id = d.id
            WHERE t.status IN ('pending_approval', 'in_review')
            ORDER BY t.submitted_for_approval_at DESC, t.id DESC
        ");
        $tasks = $stmt->fetchAll() ?: [];

        jsonResponse(true, 'Pending approvals loaded', ['data' => $tasks, 'count' => count($tasks)]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function reassignTask(array $user): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Employees cannot reassign tasks.', [], 403);
    }

    $taskId = (int)($_POST['task_id'] ?? 0);
    $newAssignee = (int)($_POST['assigned_to'] ?? 0);

    if (empty($newAssignee)) {
        jsonResponse(false, 'Assignee is required.');
    }

    // Role-specific Assignment Permission Validation
    if (!isAssigneeAllowedForUser($user, $newAssignee)) {
        $userId = (int)($user['id'] ?? 0);
        $userName = strtolower(trim($user['name'] ?? ''));
        if ($userId === 5 || $userName === 'uvaish') {
            jsonResponse(false, 'Permission denied. SEO Lead Manager (Uvaish) can only reassign tasks to Intern SEO Analyst (Aman) and Senior SEO Executive (Shivanshu).', [], 403);
        } elseif ($userId === 4 || $userName === 'rahul') {
            jsonResponse(false, 'Permission denied. Senior Fullstack Developer (Rahul) can only reassign tasks to Intern Frontend Developer (Abhay).', [], 403);
        } else {
            jsonResponse(false, 'Permission denied. You are not authorized to assign tasks to this employee.', [], 403);
        }
    }

    try {
        $db = Database::getConnection();
        $tStmt = $db->prepare("SELECT id, status, spent_seconds FROM tasks WHERE id = ?");
        $tStmt->execute([$taskId]);
        $task = $tStmt->fetch();

        if (!$task) {
            jsonResponse(false, 'Task not found.');
        }

        if ((int)$task['spent_seconds'] > 0 || in_array($task['status'], ['running', 'in_progress', 'paused', 'completed'])) {
            jsonResponse(false, 'Cannot reassign task after the employee has already started work.', [], 400);
        }

        $stmt = $db->prepare("UPDATE tasks SET assigned_to = ?, assigned_to_employee_id = ? WHERE id = ?");
        $stmt->execute([$newAssignee, $newAssignee, $taskId]);

        sendNotification($newAssignee, 'Task Reassigned', "Task #{$taskId} has been reassigned to you.", "tasks", 'task');
        jsonResponse(true, 'Task reassigned successfully.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function rejectTask(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $reason = sanitize($_POST['reason'] ?? '');

    if (empty($reason)) {
        jsonResponse(false, 'Rejection reason is required.');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE tasks SET status = 'rejected', rejection_reason = ? WHERE id = ?");
        $stmt->execute([$reason, $taskId]);

        $taskStmt = $db->prepare("SELECT assigned_by, title FROM tasks WHERE id = ?");
        $taskStmt->execute([$taskId]);
        $task = $taskStmt->fetch();

        if ($task) {
            sendNotification($task['assigned_by'], 'Task Rejected', "Task '{$task['title']}' was rejected: {$reason}", "tasks", 'task');
        }

        jsonResponse(true, 'Task rejected.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function addComment(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $comment = sanitize($_POST['comment'] ?? '');

    if (!$taskId || empty($comment)) {
        jsonResponse(false, 'Comment text required.');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO task_comments (task_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$taskId, $user['id'], $comment]);

        jsonResponse(true, 'Comment added.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function addWorkLog(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $todayWork = sanitize($_POST['today_work'] ?? '');
    $progress = (int)($_POST['progress_percentage'] ?? 0);
    $issues = sanitize($_POST['issues'] ?? '');

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO task_logs (task_id, user_id, log_date, today_work, progress_percentage, issues)
            VALUES (?, ?, CURDATE(), ?, ?, ?)
        ");
        $stmt->execute([$taskId, $user['id'], $todayWork, $progress, $issues]);

        jsonResponse(true, 'Work log submitted.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function deleteTask(array $user): void {
    if (!canCreateOrEditTasks()) {
        jsonResponse(false, 'Permission denied. Employees cannot delete tasks.', [], 403);
    }
    $taskId = (int)($_POST['task_id'] ?? 0);
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->execute([$taskId]);
        jsonResponse(true, 'Task deleted.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function cloneTask(array $user): void {
    if (!canCreateOrEditTasks()) {
        jsonResponse(false, 'Permission denied. Employees cannot clone tasks.', [], 403);
    }
    $taskId = (int)($_POST['task_id'] ?? 0);
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$taskId]);
        $t = $stmt->fetch();
        if ($t) {
            $ins = $db->prepare("
                INSERT INTO tasks (project_id, department_id, assigned_to_employee_id, assigned_to, assigned_by, title, description, priority, estimated_hours, deadline)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $ins->execute([$t['project_id'], $t['department_id'], $t['assigned_to'], $t['assigned_to'], $user['id'], $t['title'] . ' (Copy)', $t['description'], $t['priority'], $t['estimated_hours'], $t['deadline']]);
            jsonResponse(true, 'Task cloned.');
        } else {
            jsonResponse(false, 'Task not found.');
        }
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function updateTask(array $user): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers and leads are allowed to edit tasks.', [], 403);
    }

    $taskId = (int)($_POST['task_id'] ?? 0);
    if (!$taskId) {
        jsonResponse(false, 'Task ID is required.');
    }

    try {
        $db = Database::getConnection();
        $tStmt = $db->prepare("SELECT * FROM tasks WHERE id = ?");
        $tStmt->execute([$taskId]);
        $task = $tStmt->fetch();
        if (!$task) {
            jsonResponse(false, 'Task not found.');
        }

        $title = sanitize($_POST['title'] ?? $task['title']);
        $description = sanitize($_POST['description'] ?? $task['description']);
        $projectId = isset($_POST['project_id']) ? (!empty($_POST['project_id']) ? (int)$_POST['project_id'] : null) : $task['project_id'];
        $assignedTo = (int)($_POST['assigned_to'] ?? $task['assigned_to']);

        // Role-specific Assignment Permission Validation
        if ($assignedTo > 0 && !isAssigneeAllowedForUser($user, $assignedTo)) {
            $userId = (int)($user['id'] ?? 0);
            $userName = strtolower(trim($user['name'] ?? ''));
            if ($userId === 5 || $userName === 'uvaish') {
                jsonResponse(false, 'Permission denied. SEO Lead Manager (Uvaish) can only assign tasks to Intern SEO Analyst (Aman) and Senior SEO Executive (Shivanshu).', [], 403);
            } elseif ($userId === 4 || $userName === 'rahul') {
                jsonResponse(false, 'Permission denied. Senior Fullstack Developer (Rahul) can only assign tasks to Intern Frontend Developer (Abhay).', [], 403);
            } else {
                jsonResponse(false, 'Permission denied. You are not authorized to assign tasks to this employee.', [], 403);
            }
        }

        $priority = sanitize($_POST['priority'] ?? $task['priority']);
        $estimatedHours = isset($_POST['estimated_hours']) ? (float)$_POST['estimated_hours'] : (float)$task['estimated_hours'];
        
        $rawDeadline = sanitize($_POST['deadline'] ?? '');
        $deadline = !empty($rawDeadline) ? date('Y-m-d H:i:s', strtotime($rawDeadline)) : $task['deadline'];

        // Determine department
        $departmentId = (int)($_POST['department_id'] ?? 0);
        if ($departmentId <= 0 && $assignedTo > 0) {
            $uDept = $db->prepare("SELECT department_id FROM users WHERE id = ?");
            $uDept->execute([$assignedTo]);
            $departmentId = (int)($uDept->fetchColumn() ?: ($task['department_id'] ?? 1));
        }
        if ($departmentId <= 0) {
            $departmentId = (int)($task['department_id'] ?? 1);
        }

        $upd = $db->prepare("
            UPDATE tasks 
            SET title = ?, description = ?, project_id = ?, department_id = ?, assigned_to = ?, assigned_to_employee_id = ?, priority = ?, estimated_hours = ?, deadline = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $upd->execute([$title, $description, $projectId, $departmentId, $assignedTo, $assignedTo, $priority, $estimatedHours, $deadline, $taskId]);

        // If assignee changed, notify
        if ($assignedTo !== (int)$task['assigned_to']) {
            sendNotification($assignedTo, 'Task Reassigned to You', "Task '{$title}' has been assigned to you by {$user['name']}.", "task-details?id={$taskId}", 'task');
        }

        logActivity('Edit Task', 'Tasks', "Task #{$taskId} ('{$title}') updated by {$user['name']}");
        jsonResponse(true, 'Task updated successfully!', ['task_id' => $taskId]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update task: ' . $e->getMessage());
    }
}

function getTaskDetails(array $user): void {
    $taskId = (int)($_GET['task_id'] ?? ($_POST['task_id'] ?? 0));
    if (!$taskId) {
        jsonResponse(false, 'Task ID is required.');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT t.*, p.name as project_name, d.name as department_name, 
                   u_to.name as assignee_name, u_by.name as creator_name
            FROM tasks t
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN departments d ON t.department_id = d.id
            LEFT JOIN users u_to ON (t.assigned_to = u_to.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u_to.id))
            LEFT JOIN users u_by ON t.assigned_by = u_by.id
            WHERE t.id = ?
        ");
        $stmt->execute([$taskId]);
        $task = $stmt->fetch();

        if (!$task) {
            jsonResponse(false, 'Task not found.');
        }

        jsonResponse(true, 'Task details loaded successfully.', ['task' => $task]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error fetching task details: ' . $e->getMessage());
    }
}
