<?php
/**
 * Meeting Management API - WorkFlow Pro
 * Handles Boss/Admin "Call Employee to Meeting", employee invitations, responses, and live meeting activity integration.
 */

require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_GET['action'] ?? ($_POST['action'] ?? 'list');

switch ($action) {
    case 'create':
    case 'call_meeting':
        callMeetingEndpoint($user);
        break;
    case 'update':
    case 'edit':
        updateMeetingEndpoint($user);
        break;
    case 'respond':
        respondMeetingEndpoint($user);
        break;
    case 'end':
        endMeetingEndpoint($user);
        break;
    case 'active_request':
        getActiveMeetingRequestEndpoint($user);
        break;
    case 'list':
        listMeetingsEndpoint($user);
        break;
    default:
        jsonResponse(false, 'Invalid meeting action', [], 400);
}

function callMeetingEndpoint(array $user): void {
    $employeeId = (int)($_POST['employee_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $meetingDate = sanitize($_POST['meeting_date'] ?? date('Y-m-d'));
    $startTime = sanitize($_POST['start_time'] ?? date('H:i'));
    $duration = max(5, (int)($_POST['duration'] ?? 30));
    $meetingLink = sanitize($_POST['meeting_link'] ?? 'https://meet.google.com/krd-' . substr(md5(uniqid()), 0, 8));
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $clientName = sanitize($_POST['client_name'] ?? '');
    $meetingType = sanitize($_POST['meeting_type'] ?? 'internal'); // 'client', 'boss', 'internal', 'review'

    if (empty($title)) {
        jsonResponse(false, 'Meeting title / topic is required.');
    }

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $isManager = hasRole(['admin', 'manager', 'product_manager']);

        // If target employee not selected by employee, default to an admin/manager or self for client meeting
        if (!$employeeId) {
            if ($meetingType === 'client') {
                $employeeId = (int)$user['id'];
            } else {
                // Find admin/manager
                $adminUser = $db->query("SELECT id FROM users WHERE role IN ('admin', 'manager') ORDER BY id ASC LIMIT 1")->fetch();
                $employeeId = $adminUser ? (int)$adminUser['id'] : (int)$user['id'];
            }
        }

        // Fetch employee details
        $empStmt = $db->prepare("SELECT id, name, email FROM users WHERE id = ?");
        $empStmt->execute([$employeeId]);
        $emp = $empStmt->fetch();
        if (!$emp) {
            jsonResponse(false, 'Target employee / participant not found.');
        }

        // Calculate end_time string
        $startDt = new DateTime("{$meetingDate} {$startTime}:00", new DateTimeZone('Asia/Kolkata'));
        $endDt = (clone $startDt)->modify("+{$duration} minutes");
        $endTime = $endDt->format('H:i');

        // Initial status: if client meeting created by employee for themselves, can be accepted immediately
        $initialStatus = ($employeeId === (int)$user['id'] && $meetingType === 'client') ? 'accepted' : 'pending';

        $nowStr = date('Y-m-d H:i:s');
        $ins = $db->prepare("
            INSERT INTO meetings 
            (created_by, employee_id, title, description, meeting_date, start_time, end_time, duration, duration_minutes, meeting_link, project_id, client_name, meeting_type, status, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $ins->execute([
            $user['id'], $employeeId, $title, $description, $meetingDate, $startTime, $endTime, $duration, $duration, $meetingLink, $projectId, $clientName, $meetingType, $initialStatus, $nowStr, $nowStr
        ]);
        $meetingId = (int)$db->lastInsertId();

        // Also record participant
        $db->prepare("INSERT INTO meeting_participants (meeting_id, employee_id, status, created_at) VALUES (?, ?, ?, ?)")
           ->execute([$meetingId, $employeeId, $initialStatus, $nowStr]);

        // If employee self-scheduled client meeting, start meeting activity
        if ($initialStatus === 'accepted') {
            startEmployeeActivity(
                $db,
                $user['id'],
                'Meeting',
                $projectId,
                null,
                null,
                "Client Meeting: {$title}" . ($clientName ? " (Client: {$clientName})" : "")
            );
        } else {
            // Send high-priority real-time notification to the invited party
            $notifTitle = "🔔 Meeting Request: {$title}";
            $clientNote = !empty($clientName) ? " [Client: {$clientName}]" : "";
            $notifMsg = "{$user['name']} has requested a meeting on {$meetingDate} at {$startTime} ({$duration} mins).{$clientNote}";
            $targetUrl = ($employeeId === (int)$user['id']) ? "live-status" : "workday";
            sendNotification($employeeId, $notifTitle, $notifMsg, $targetUrl, "meeting");
        }

        logActivity('Call to Meeting', 'Meetings', "{$user['name']} scheduled meeting '{$title}' with {$emp['name']} at {$startTime}.");

        jsonResponse(true, "Meeting request created successfully!", [
            'meeting_id' => $meetingId,
            'employee_name' => $emp['name'],
            'title' => $title,
            'start_time' => $startTime,
            'duration' => $duration,
            'client_name' => $clientName
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to create meeting: ' . $e->getMessage(), [], 500);
    }
}

function updateMeetingEndpoint(array $user): void {
    $meetingId = (int)($_POST['meeting_id'] ?? 0);
    $title = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $meetingDate = sanitize($_POST['meeting_date'] ?? date('Y-m-d'));
    $startTime = sanitize($_POST['start_time'] ?? date('H:i'));
    $duration = max(5, (int)($_POST['duration'] ?? 30));
    $meetingLink = sanitize($_POST['meeting_link'] ?? '');
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;
    $clientName = sanitize($_POST['client_name'] ?? '');
    $meetingType = sanitize($_POST['meeting_type'] ?? 'internal');
    $responseNotes = sanitize($_POST['response_notes'] ?? '');
    $status = sanitize($_POST['status'] ?? '');

    if (!$meetingId) {
        jsonResponse(false, 'Meeting ID is required.');
    }

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $mStmt = $db->prepare("SELECT * FROM meetings WHERE id = ?");
        $mStmt->execute([$meetingId]);
        $meeting = $mStmt->fetch();

        if (!$meeting) {
            jsonResponse(false, 'Meeting not found.');
        }

        // Permission check: admin, manager, organizer (created_by), invited employee, or participant
        $isCreator = ((int)$meeting['created_by'] === (int)$user['id']);
        $isInvitee = ((int)$meeting['employee_id'] === (int)$user['id']);
        $isManager = hasRole(['admin', 'manager', 'product_manager']);
        $isParticipant = false;
        try {
            $pCheck = $db->prepare("SELECT COUNT(*) FROM meeting_participants WHERE meeting_id = ? AND employee_id = ?");
            $pCheck->execute([$meetingId, $user['id']]);
            $isParticipant = ((int)$pCheck->fetchColumn() > 0);
        } catch (Exception $e) {}

        if (!$isCreator && !$isInvitee && !$isParticipant && !$isManager) {
            jsonResponse(false, 'Permission denied to edit this meeting.', [], 403);
        }

        // Calculate end_time string
        $startDt = new DateTime("{$meetingDate} {$startTime}:00", new DateTimeZone('Asia/Kolkata'));
        $endDt = (clone $startDt)->modify("+{$duration} minutes");
        $endTime = $endDt->format('H:i');

        $finalTitle = !empty($title) ? $title : $meeting['title'];
        $finalStatus = !empty($status) ? $status : $meeting['status'];
        $finalNotes = !empty($responseNotes) ? $responseNotes : $meeting['response_notes'];
        $finalLink = !empty($meetingLink) ? $meetingLink : $meeting['meeting_link'];

        $nowStr = date('Y-m-d H:i:s');
        $upd = $db->prepare("
            UPDATE meetings 
            SET title = ?, description = ?, meeting_date = ?, start_time = ?, end_time = ?,
                duration = ?, duration_minutes = ?, meeting_link = ?, project_id = ?, client_name = ?,
                meeting_type = ?, response_notes = ?, status = ?, updated_at = ?
            WHERE id = ?
        ");
        $upd->execute([
            $finalTitle, $description, $meetingDate, $startTime, $endTime,
            $duration, $duration, $finalLink, $projectId, $clientName,
            $meetingType, $finalNotes, $finalStatus, $nowStr, $meetingId
        ]);

        // If status changed to accepted, start meeting activity
        if ($finalStatus === 'accepted' && $meeting['status'] !== 'accepted') {
            startEmployeeActivity(
                $db,
                $meeting['employee_id'],
                'Meeting',
                $projectId,
                null,
                null,
                "In Meeting: {$finalTitle}" . ($clientName ? " (Client: {$clientName})" : "")
            );
        }

        // Notifications
        if ($isInvitee && !$isCreator) {
            // Employee edited it -> notify organizer
            sendNotification(
                (int)$meeting['created_by'],
                "📝 Meeting Updated by {$user['name']}: {$finalTitle}",
                "Schedule/notes updated to {$meetingDate} at {$startTime} ({$duration}m). Notes: {$finalNotes}",
                "live-status",
                "info"
            );
        } elseif (!$isInvitee) {
            // Organizer edited it -> notify employee
            sendNotification(
                (int)$meeting['employee_id'],
                "📝 Meeting Updated: {$finalTitle}",
                "Host {$user['name']} updated the meeting schedule to {$meetingDate} at {$startTime} ({$duration}m).",
                "workday",
                "info"
            );
        }

        logActivity('Meeting Updated', 'Meetings', "Meeting #{$meetingId} '{$finalTitle}' updated by {$user['name']}.");

        $refreshed = $db->query("SELECT * FROM meetings WHERE id = {$meetingId}")->fetch();
        jsonResponse(true, 'Meeting updated successfully!', [
            'meeting' => $refreshed
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update meeting: ' . $e->getMessage(), [], 500);
    }
}

function respondMeetingEndpoint(array $user): void {
    $meetingId = (int)($_POST['meeting_id'] ?? 0);
    $response = strtolower(sanitize($_POST['response'] ?? ($_POST['response_status'] ?? ''))); // 'accept', 'decline', 'busy'
    $responseNotes = sanitize($_POST['response_notes'] ?? '');
    $busyReason = sanitize($_POST['busy_reason'] ?? '');
    $clientName = sanitize($_POST['client_name'] ?? '');
    $taskId = !empty($_POST['task_id']) ? (int)$_POST['task_id'] : null;
    $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;

    if (!$meetingId || !in_array($response, ['accept', 'accepted', 'decline', 'declined', 'busy'])) {
        jsonResponse(false, 'Valid meeting ID and response (accept, decline, busy) required.');
    }

    $statusMap = [
        'accept' => 'accepted',
        'accepted' => 'accepted',
        'decline' => 'declined',
        'declined' => 'declined',
        'busy' => 'busy'
    ];
    $finalStatus = $statusMap[$response];

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $mStmt = $db->prepare("SELECT * FROM meetings WHERE id = ?");
        $mStmt->execute([$meetingId]);
        $meeting = $mStmt->fetch();

        if (!$meeting) {
            jsonResponse(false, 'Meeting request not found.');
        }

        // Verify that current user is the invited employee, a participant, or admin/manager
        $isParticipant = false;
        try {
            $pCheck = $db->prepare("SELECT COUNT(*) FROM meeting_participants WHERE meeting_id = ? AND employee_id = ?");
            $pCheck->execute([$meetingId, $user['id']]);
            $isParticipant = ((int)$pCheck->fetchColumn() > 0);
        } catch (Exception $e) {}

        $isCreator = ((int)$meeting['created_by'] === (int)$user['id']);
        $isInvitee = ((int)$meeting['employee_id'] === (int)$user['id']);
        if (!$isInvitee && !$isCreator && !$isParticipant && !hasRole(['admin', 'manager', 'product_manager'])) {
            jsonResponse(false, 'Permission denied. You are not the recipient of this meeting.', [], 403);
        }

        // If marked busy with a specific activity (e.g. Waiting for Client, Task, etc.)
        if ($finalStatus === 'busy') {
            if (!empty($busyReason)) {
                $clientTag = $clientName ? " [Client: {$clientName}]" : "";
                $responseNotes = trim("[Busy: {$busyReason}]{$clientTag} " . $responseNotes);

                // Update employee live activity to what they are busy with
                startEmployeeActivity(
                    $db,
                    $user['id'],
                    $busyReason,
                    $projectId ?: ($meeting['project_id'] ?? null),
                    $taskId,
                    $busyReason,
                    "Busy: " . ($clientName ? "Client {$clientName} - " : "") . ($responseNotes ?: $busyReason),
                    $clientName
                );
            }
        }

        // Update meeting status
        $nowStr = date('Y-m-d H:i:s');
        $upd = $db->prepare("UPDATE meetings SET status = ?, response_notes = ?, updated_at = ? WHERE id = ?");
        $upd->execute([$finalStatus, $responseNotes, $nowStr, $meetingId]);

        try {
            $db->prepare("UPDATE meeting_participants SET status = ? WHERE meeting_id = ? AND employee_id = ?")
               ->execute([$finalStatus, $meetingId, $user['id']]);
        } catch (Exception $e) {}

        // If employee accepts meeting:
        if ($finalStatus === 'accepted') {
            // Automatically close previous activity and start a Meeting Activity
            startEmployeeActivity(
                $db,
                $user['id'],
                'Meeting',
                $meeting['project_id'] ?? null,
                null,
                null,
                "In Meeting: {$meeting['title']} (" . ($meeting['description'] ?: 'Review') . ")"
            );

            // Notify Boss
            sendNotification((int)$meeting['created_by'], "✓ Meeting Accepted: {$meeting['title']}", "{$user['name']} accepted your meeting request.", "live-status", "success");
            logActivity('Meeting Accepted', 'Meetings', "Employee {$user['name']} accepted meeting '{$meeting['title']}'. Status changed to In Meeting.");
            
            jsonResponse(true, 'Meeting accepted! Status updated to In Meeting.', [
                'status' => 'In Meeting',
                'meeting' => $meeting,
                'meeting_link' => $meeting['meeting_link'] ?? ''
            ]);
        } else {
            // Declined or Busy
            $reasonText = ($finalStatus === 'busy') ? "I'm Busy" : "Declined";
            sendNotification((int)$meeting['created_by'], "⚠️ Meeting {$reasonText}: {$meeting['title']}", "{$user['name']} marked: {$reasonText}" . (!empty($responseNotes) ? " ({$responseNotes})" : ""), "live-status", "warning");
            logActivity("Meeting {$reasonText}", 'Meetings', "Employee {$user['name']} marked meeting '{$meeting['title']}' as {$reasonText}.");

            jsonResponse(true, "Meeting marked as {$reasonText}.", [
                'status' => $finalStatus,
                'response_notes' => $responseNotes,
                'busy_reason' => $busyReason
            ]);
        }
    } catch (Exception $e) {
        jsonResponse(false, 'Error responding to meeting: ' . $e->getMessage(), [], 500);
    }
}

function endMeetingEndpoint(array $user): void {
    $meetingId = (int)($_POST['meeting_id'] ?? 0);
    $nextActivity = sanitize($_POST['next_activity'] ?? 'Working on Task');
    $nextNotes = sanitize($_POST['next_notes'] ?? '');

    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        // Close current Meeting activity for this employee
        closeEmployeeCurrentActivity($db, $user['id']);

        if ($meetingId) {
            $nowStr = date('Y-m-d H:i:s');
            $db->prepare("UPDATE meetings SET status = 'completed', updated_at = ? WHERE id = ?")->execute([$nowStr, $meetingId]);
        }

        // Automatically start the next activity selected by employee
        $newAct = startEmployeeActivity($db, $user['id'], $nextActivity, null, null, null, $nextNotes);

        logActivity('Meeting Ended', 'Meetings', "Employee {$user['name']} ended meeting and switched to '{$nextActivity}'.");

        jsonResponse(true, 'Meeting completed successfully! Returned to active work.', [
            'new_activity' => $newAct
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to end meeting: ' . $e->getMessage(), [], 500);
    }
}

function getActiveMeetingRequestEndpoint(array $user): void {
    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $today = date('Y-m-d');
        // Fetch any pending meeting request for current employee
        $stmt = $db->prepare("
            SELECT m.*, 
                   u.name as caller_name, u.designation as caller_designation, u.avatar as caller_avatar,
                   p.name as project_name
            FROM meetings m
            LEFT JOIN users u ON m.created_by = u.id
            LEFT JOIN projects p ON m.project_id = p.id
            WHERE (m.employee_id = ? OR m.id IN (SELECT meeting_id FROM meeting_participants WHERE employee_id = ?)) 
              AND m.status = 'pending'
            ORDER BY m.id DESC LIMIT 1
        ");
        $stmt->execute([$user['id'], $user['id']]);
        $pending = $stmt->fetch();

        // Also check if employee is currently in an active / accepted meeting
        $inMeetingStmt = $db->prepare("
            SELECT m.*, u.name as caller_name, p.name as project_name
            FROM meetings m
            LEFT JOIN users u ON m.created_by = u.id
            LEFT JOIN projects p ON m.project_id = p.id
            WHERE (m.employee_id = ? OR m.id IN (SELECT meeting_id FROM meeting_participants WHERE employee_id = ?)) 
              AND (m.status = 'accepted' OR m.status = 'in_progress') AND m.meeting_date = ?
            ORDER BY m.id DESC LIMIT 1
        ");
        $inMeetingStmt->execute([$user['id'], $user['id'], $today]);
        $activeMeeting = $inMeetingStmt->fetch();

        // List of all today's meetings and pending requests for current user
        $todayStmt = $db->prepare("
            SELECT m.*, 
                   c.name as caller_name, c.designation as caller_designation, c.avatar as caller_avatar,
                   e.name as employee_name, e.designation as employee_designation,
                   p.name as project_name
            FROM meetings m
            LEFT JOIN users c ON m.created_by = c.id
            LEFT JOIN users e ON m.employee_id = e.id
            LEFT JOIN projects p ON m.project_id = p.id
            WHERE (m.employee_id = ? OR m.created_by = ? OR m.id IN (SELECT meeting_id FROM meeting_participants WHERE employee_id = ?))
              AND (m.meeting_date >= ? OR m.status = 'pending')
            ORDER BY CASE WHEN m.status = 'pending' THEN 0 WHEN m.status IN ('accepted', 'in_progress') THEN 1 ELSE 2 END, m.start_time ASC
        ");
        $todayStmt->execute([$user['id'], $user['id'], $user['id'], $today]);
        $todayMeetings = $todayStmt->fetchAll();

        jsonResponse(true, 'Meeting status checked', [
            'has_pending_request' => !empty($pending),
            'pending_meeting' => $pending,
            'meeting' => $pending,
            'is_in_meeting' => !empty($activeMeeting),
            'active_meeting' => $activeMeeting,
            'today_meetings' => $todayMeetings
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Error checking meeting request: ' . $e->getMessage(), [], 500);
    }
}

function listMeetingsEndpoint(array $user): void {
    try {
        $db = Database::getConnection();
        ensureShiftAndActivitySchema($db);

        $targetDate = sanitize($_GET['date'] ?? date('Y-m-d'));
        $isManager = hasRole(['admin', 'manager', 'product_manager']);

        if ($isManager && !isset($_GET['mine'])) {
            $stmt = $db->prepare("
                SELECT m.*, 
                       e.name as employee_name, e.designation as employee_designation, e.avatar as employee_avatar, 
                       c.name as caller_name, c.designation as caller_designation,
                       p.name as project_name
                FROM meetings m
                LEFT JOIN users e ON m.employee_id = e.id
                LEFT JOIN users c ON m.created_by = c.id
                LEFT JOIN projects p ON m.project_id = p.id
                WHERE m.meeting_date = ? OR m.status = 'pending'
                ORDER BY m.start_time ASC
            ");
            $stmt->execute([$targetDate]);
        } else {
            $stmt = $db->prepare("
                SELECT m.*, 
                       e.name as employee_name, e.designation as employee_designation,
                       c.name as caller_name, c.designation as caller_designation,
                       p.name as project_name
                FROM meetings m
                LEFT JOIN users e ON m.employee_id = e.id
                LEFT JOIN users c ON m.created_by = c.id
                LEFT JOIN projects p ON m.project_id = p.id
                WHERE (m.employee_id = ? OR m.created_by = ?) AND (m.meeting_date = ? OR m.status = 'pending')
                ORDER BY m.start_time ASC
            ");
            $stmt->execute([$user['id'], $user['id'], $targetDate]);
        }

        $meetings = $stmt->fetchAll();
        jsonResponse(true, 'Meetings loaded', $meetings);
    } catch (Exception $e) {
        jsonResponse(false, 'Error loading meetings: ' . $e->getMessage(), [], 500);
    }
}
