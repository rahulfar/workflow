<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'status':
        getTimerStatus($user);
        break;
    case 'start':
        startTimer($user);
        break;
    case 'pause':
        pauseTimer($user);
        break;
    case 'stop':
        stopTimer($user);
        break;
    case 'sync':
        syncTimer($user);
        break;
    case 'save_note':
        saveWorkNote($user);
        break;
    default:
        jsonResponse(false, 'Invalid timer action', [], 400);
}

function validateAssignedEmployeeForTask(PDO $db, int $taskId, int $userId): array {
    ensureTaskTimeLogsSchema($db);

    $stmt = $db->prepare("SELECT id, title, assigned_to, status, spent_seconds FROM tasks WHERE id = ?");
    $stmt->execute([$taskId]);
    $task = $stmt->fetch();

    if (!$task) {
        jsonResponse(false, 'Task not found.');
    }

    if ((int)$task['assigned_to'] !== $userId) {
        jsonResponse(false, 'Permission denied. Only the assigned employee can start, pause, resume, or complete this task timer.', [], 403);
    }

    return $task;
}

function getTimerStatus(array $user): void {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT tt.*, t.title as task_title, t.deadline
            FROM task_timer tt
            JOIN tasks t ON tt.task_id = t.id
            WHERE tt.user_id = ? AND tt.status = 'running'
            ORDER BY tt.id DESC LIMIT 1
        ");
        $stmt->execute([$user['id']]);
        $timer = $stmt->fetch();

        if ($timer) {
            $startTime = strtotime($timer['start_time']);
            $elapsed = time() - $startTime + (int)$timer['duration_seconds'];
            jsonResponse(true, 'Active timer running', [
                'active' => true,
                'task_id' => $timer['task_id'],
                'task_title' => $timer['task_title'],
                'deadline' => $timer['deadline'],
                'elapsed_seconds' => $elapsed,
                'work_note' => $timer['idle_seconds']
            ]);
        } else {
            jsonResponse(true, 'No active timer', ['active' => false]);
        }
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function startTimer(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    if (!$taskId) jsonResponse(false, 'Task ID required');

    try {
        $db = Database::getConnection();
        $task = validateAssignedEmployeeForTask($db, $taskId, $user['id']);

        // Single timer rule: pause any currently running timer for this user first
        $runningStmt = $db->prepare("SELECT * FROM task_timer WHERE user_id = ? AND status = 'running'");
        $runningStmt->execute([$user['id']]);
        $activeRun = $runningStmt->fetch();

        $nowStr = date('Y-m-d H:i:s');

        if ($activeRun) {
            $duration = time() - strtotime($activeRun['start_time']);
            $db->prepare("UPDATE task_timer SET status = 'paused', end_time = ?, duration_seconds = duration_seconds + ? WHERE id = ?")
               ->execute([$nowStr, $duration, $activeRun['id']]);
            $db->prepare("UPDATE tasks SET spent_seconds = spent_seconds + ?, status = 'paused' WHERE id = ?")
               ->execute([$duration, $activeRun['task_id']]);

            $db->prepare("INSERT INTO task_time_logs (task_id, employee_id, paused_at, total_work_duration, status) VALUES (?, ?, ?, ?, 'paused')")
               ->execute([$activeRun['task_id'], $user['id'], $nowStr, $duration]);
        }

        // Start new timer
        $stmt = $db->prepare("INSERT INTO task_timer (task_id, user_id, start_time, status) VALUES (?, ?, ?, 'running')");
        $stmt->execute([$taskId, $user['id'], $nowStr]);

        // Update task status to running
        $db->prepare("UPDATE tasks SET status = 'running' WHERE id = ?")->execute([$taskId]);

        // Record in task_time_logs
        $db->prepare("INSERT INTO task_time_logs (task_id, employee_id, started_at, status) VALUES (?, ?, ?, 'started')")
           ->execute([$taskId, $user['id'], $nowStr]);

        // Connect with employee_activities table
        try {
            ensureShiftAndActivitySchema($db);
            $pId = (int)($task['project_id'] ?? 0);
            startEmployeeActivity($db, $user['id'], 'Working on Task', $pId ?: null, $taskId, null, "Working on: " . $task['title']);
        } catch (Exception $actEx) {
            error_log("startTimer activity sync notice: " . $actEx->getMessage());
        }

        logActivity('Start Timer', 'TimeTracker', "Assigned employee started live timer for task #{$taskId}");
        jsonResponse(true, 'Live task timer started!');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to start timer: ' . $e->getMessage());
    }
}

function pauseTimer(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    if (!$taskId) jsonResponse(false, 'Task ID required');

    try {
        $db = Database::getConnection();
        $task = validateAssignedEmployeeForTask($db, $taskId, $user['id']);

        $stmt = $db->prepare("SELECT * FROM task_timer WHERE task_id = ? AND user_id = ? AND status = 'running' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$taskId, $user['id']]);
        $t = $stmt->fetch();

        $nowStr = date('Y-m-d H:i:s');

        if ($t) {
            $duration = time() - strtotime($t['start_time']);
            $upd = $db->prepare("UPDATE task_timer SET status = 'paused', end_time = ?, duration_seconds = duration_seconds + ? WHERE id = ?");
            $upd->execute([$nowStr, $duration, $t['id']]);

            $db->prepare("UPDATE tasks SET spent_seconds = spent_seconds + ?, status = 'paused' WHERE id = ?")->execute([$duration, $taskId]);

            $db->prepare("INSERT INTO task_time_logs (task_id, employee_id, paused_at, total_work_duration, status) VALUES (?, ?, ?, ?, 'paused')")
               ->execute([$taskId, $user['id'], $nowStr, $duration]);

            // Sync with employee_activities table
            try {
                ensureShiftAndActivitySchema($db);
                closeEmployeeCurrentActivity($db, $user['id'], $nowStr);
            } catch (Exception $actEx) {}
        }

        logActivity('Pause Timer', 'TimeTracker', "Assigned employee paused timer for task #{$taskId}");
        jsonResponse(true, 'Task timer paused.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function syncTimer(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    if (!$taskId) jsonResponse(false, 'Task ID required');

    try {
        $db = Database::getConnection();
        $task = validateAssignedEmployeeForTask($db, $taskId, $user['id']);

        $stmt = $db->prepare("SELECT * FROM task_timer WHERE task_id = ? AND user_id = ? AND status = 'running' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$taskId, $user['id']]);
        $t = $stmt->fetch();

        if ($t) {
            $db->prepare("UPDATE task_timer SET duration_seconds = duration_seconds + 10 WHERE id = ?")->execute([$t['id']]);
            $db->prepare("UPDATE tasks SET spent_seconds = spent_seconds + 10 WHERE id = ?")->execute([$taskId]);
        }

        jsonResponse(true, 'Timer auto-synced');
    } catch (Exception $e) {
        jsonResponse(false, 'Sync error: ' . $e->getMessage());
    }
}

function saveWorkNote(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $note = sanitize($_POST['note'] ?? '');
    if (!$taskId) jsonResponse(false, 'Task ID required');

    try {
        $db = Database::getConnection();
        $task = validateAssignedEmployeeForTask($db, $taskId, $user['id']);

        $stmt = $db->prepare("INSERT INTO task_comments (task_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->execute([$taskId, $user['id'], "[Live Work Note] " . $note]);
        jsonResponse(true, 'Work note saved!');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function stopTimer(array $user): void {
    $taskId = (int)($_POST['task_id'] ?? 0);
    $logText = sanitize($_POST['log_text'] ?? 'Completed task work');
    if (!$taskId) jsonResponse(false, 'Task ID required');

    try {
        $db = Database::getConnection();
        ensureTaskApprovalSchema($db);
        $task = validateAssignedEmployeeForTask($db, $taskId, $user['id']);

        $stmt = $db->prepare("SELECT * FROM task_timer WHERE task_id = ? AND user_id = ? AND status = 'running' ORDER BY id DESC LIMIT 1");
        $stmt->execute([$taskId, $user['id']]);
        $t = $stmt->fetch();

        $nowStr = date('Y-m-d H:i:s');
        $sessionDuration = 0;

        if ($t) {
            $sessionDuration = time() - strtotime($t['start_time']);
            $upd = $db->prepare("UPDATE task_timer SET status = 'completed', end_time = ?, duration_seconds = duration_seconds + ? WHERE id = ?");
            $upd->execute([$nowStr, $sessionDuration, $t['id']]);
        }

        $isManager = canCreateOrEditTasks();

        if ($isManager) {
            // Manager/Admin can mark directly completed (Done)
            $db->prepare("
                UPDATE tasks 
                SET spent_seconds = spent_seconds + ?, 
                    status = 'completed', 
                    completed_at = ?, 
                    approved_by = ?, 
                    approved_at = ?,
                    completion_notes = ? 
                WHERE id = ?
            ")->execute([$sessionDuration, $nowStr, $user['id'], $nowStr, $logText, $taskId]);
        } else {
            // Employee completes task -> Sets to pending_approval and sends approval request notification to Manager & Product Manager
            $db->prepare("
                UPDATE tasks 
                SET spent_seconds = spent_seconds + ?, 
                    status = 'pending_approval', 
                    submitted_for_approval_at = ?, 
                    completion_notes = ? 
                WHERE id = ?
            ")->execute([$sessionDuration, $nowStr, $logText, $taskId]);

            // Notify Manager & Product Manager
            $notifTitle = "Task Completion Approval Request";
            $notifMsg = "Employee {$user['name']} has completed task #{$taskId} ('{$task['title']}') and submitted it for your approval.";
            $notifLink = "task-details?id={$taskId}";
            notifyManagersAndProductManagers($notifTitle, $notifMsg, $notifLink, $user['id'], 'task_approval');

            if (!empty($task['assigned_by']) && (int)$task['assigned_by'] !== (int)$user['id']) {
                sendNotification((int)$task['assigned_by'], $notifTitle, $notifMsg, $notifLink, 'task_approval');
            }
        }

        $logDate = date('Y-m-d');
        $logStmt = $db->prepare("INSERT INTO task_logs (task_id, user_id, log_date, today_work, progress_percentage, completed_work) VALUES (?, ?, ?, ?, 100, ?)");
        $logStmt->execute([$taskId, $user['id'], $logDate, $logText, $logText]);

        $db->prepare("INSERT INTO task_time_logs (task_id, employee_id, completed_at, total_work_duration, status) VALUES (?, ?, ?, ?, 'completed')")
           ->execute([$taskId, $user['id'], $nowStr, $sessionDuration]);

        // Sync with employee_activities and daily_work_reports
        try {
            ensureShiftAndActivitySchema($db);
            closeEmployeeCurrentActivity($db, $user['id'], $nowStr);
            generateDailyWorkReport($db, $user['id'], date('Y-m-d'));
        } catch (Exception $actEx) {}

        if ($isManager) {
            logActivity('Stop Timer', 'TimeTracker', "Task #{$taskId} completed & marked Done by {$user['name']}.");
            jsonResponse(true, 'Work log submitted & task marked Done!');
        } else {
            logActivity('Stop Timer', 'TimeTracker', "Assigned employee completed task #{$taskId} & submitted for manager approval.");
            jsonResponse(true, 'Work log submitted & task sent to Manager & Product Manager for approval!', [
                'task_id' => $taskId,
                'status' => 'pending_approval'
            ]);
        }
    } catch (Exception $e) {
        jsonResponse(false, 'Error stopping timer: ' . $e->getMessage());
    }
}
