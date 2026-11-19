<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$db = Database::getConnection();
ensurePayrollSchema($db);

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'request':
        requestLeave($db, $user);
        break;

    case 'cancel':
        cancelLeave($db, $user);
        break;

    case 'approve':
        approveLeave($db, $user);
        break;

    case 'reject':
        rejectLeave($db, $user);
        break;

    case 'get_balance':
        getLeaveBalanceApi($db, $user);
        break;

    case 'download_attachment':
        downloadLeaveAttachment($db, $user);
        break;

    default:
        jsonResponse(false, 'Invalid leave action', [], 400);
}

function downloadLeaveAttachment(PDO $db, array $user): void {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        die('Missing leave ID');
    }
    $stmt = $db->prepare("SELECT * FROM leave_requests WHERE id = ?");
    $stmt->execute([$id]);
    $leave = $stmt->fetch();
    if (!$leave || empty($leave['attachment'])) {
        http_response_code(404);
        die('Attachment not found');
    }

    $isAdmin = (strtolower($user['role_name'] ?? '') === 'admin' || (int)($user['role_id'] ?? 0) === 1);
    $isManager = in_array(strtolower($user['role_name'] ?? ''), ['manager', 'product_manager']);
    if (!$isAdmin && !$isManager && (int)$leave['user_id'] !== (int)$user['id']) {
        http_response_code(403);
        die('Permission denied');
    }

    $candidates = [
        __DIR__ . '/../public/' . ltrim($leave['attachment'], '/'),
        __DIR__ . '/../' . ltrim($leave['attachment'], '/'),
        __DIR__ . '/../public/uploads/leaves/' . basename($leave['attachment']),
    ];

    $filePath = null;
    foreach ($candidates as $cand) {
        if (file_exists($cand)) {
            $filePath = $cand;
            break;
        }
    }

    if (!$filePath) {
        http_response_code(404);
        die('Attachment file not found on disk');
    }

    $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
}

function requestLeave(PDO $db, array $user): void {
    $leaveType = sanitize($_POST['leave_type'] ?? 'paid');
    $startDate = sanitize($_POST['start_date'] ?? '');
    $endDate = sanitize($_POST['end_date'] ?? '');
    $reason = sanitize($_POST['reason'] ?? '');

    if (empty($startDate) || empty($endDate) || empty($reason)) {
        jsonResponse(false, 'Start Date, End Date, and Reason are required.');
    }

    if (strtotime($endDate) < strtotime($startDate)) {
        jsonResponse(false, 'End Date cannot be before Start Date.');
    }

    $days = (float)((strtotime($endDate) - strtotime($startDate)) / 86400 + 1);

    // Calculate Paid vs Unpaid split based on employee's remaining Paid Leave balance for the month
    $monthYear = date('Y-m', strtotime($startDate));
    $balanceInfo = getEmployeeLeaveBalance((int)$user['id'], $monthYear);
    $remainingPaid = (float)$balanceInfo['remaining_paid'];

    $paidDays = 0.0;
    $unpaidDays = 0.0;

    if ($leaveType === 'unpaid') {
        $unpaidDays = $days;
    } else {
        if ($remainingPaid >= $days) {
            $paidDays = $days;
            $unpaidDays = 0.0;
        } else if ($remainingPaid > 0) {
            $paidDays = $remainingPaid;
            $unpaidDays = $days - $remainingPaid;
        } else {
            $paidDays = 0.0;
            $unpaidDays = $days;
        }
    }

    // Handle optional file attachment
    $attachmentPath = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['attachment'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];

        if (in_array($ext, $allowed)) {
            $uploadDir = __DIR__ . '/../public/uploads/leaves/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filename = 'leave_' . $user['id'] . '_' . time() . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                $attachmentPath = 'uploads/leaves/' . $filename;
            }
        }
    }

    try {
        $stmt = $db->prepare("
            INSERT INTO leave_requests (user_id, leave_type, start_date, end_date, total_days, paid_days, unpaid_days, reason, attachment, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$user['id'], $leaveType, $startDate, $endDate, $days, $paidDays, $unpaidDays, $reason, $attachmentPath]);

        $mgrs = $db->query("SELECT id FROM users WHERE role_id IN (1, 2)")->fetchAll();
        foreach ($mgrs as $m) {
            sendNotification($m['id'], 'New Leave Application', "{$user['name']} requested {$days} days leave ({$paidDays} Paid, {$unpaidDays} Unpaid).", 'leaves', 'leave');
        }

        logActivity('Request Leave', 'Leaves', "User {$user['name']} requested {$days} days leave ({$startDate} to {$endDate})");

        $msg = "Leave request submitted successfully!";
        if ($unpaidDays > 0 && $paidDays > 0) {
            $msg .= " ({$paidDays} Paid Days, {$unpaidDays} Unpaid Days due to leave quota limit).";
        } else if ($unpaidDays > 0 && $paidDays == 0) {
            $msg .= " (Exceeded paid leave quota, converted to {$unpaidDays} Unpaid Days).";
        }

        jsonResponse(true, $msg);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to submit leave request: ' . $e->getMessage());
    }
}

function cancelLeave(PDO $db, array $user): void {
    $id = (int)($_POST['leave_id'] ?? 0);
    if ($id <= 0) {
        jsonResponse(false, 'Invalid leave request ID');
    }

    try {
        $stmt = $db->prepare("SELECT * FROM leave_requests WHERE id = ?");
        $stmt->execute([$id]);
        $leave = $stmt->fetch();

        if (!$leave) {
            jsonResponse(false, 'Leave request not found');
        }

        if ($leave['user_id'] != $user['id'] && !hasRole(['admin', 'manager'])) {
            jsonResponse(false, 'Access denied', [], 403);
        }

        if ($leave['status'] !== 'pending') {
            jsonResponse(false, 'Only pending leave requests can be cancelled');
        }

        $upd = $db->prepare("UPDATE leave_requests SET status = 'cancelled' WHERE id = ?");
        $upd->execute([$id]);

        logActivity('Cancel Leave', 'Leaves', "Leave request ID {$id} was cancelled by user");
        jsonResponse(true, 'Leave request cancelled successfully');
    } catch (Exception $e) {
        jsonResponse(false, 'Error cancelling leave: ' . $e->getMessage());
    }
}

function approveLeave(PDO $db, array $user): void {
    if (!hasRole(['admin', 'manager'])) {
        jsonResponse(false, 'Permission denied', [], 403);
    }

    $id = (int)($_POST['leave_id'] ?? 0);
    $comment = sanitize($_POST['comment'] ?? 'Approved by manager');

    if ($id <= 0) {
        jsonResponse(false, 'Invalid leave ID');
    }

    try {
        $stmt = $db->prepare("UPDATE leave_requests SET status = 'approved', approved_by = ?, comment = ? WHERE id = ?");
        $stmt->execute([$user['id'], $comment, $id]);

        $lStmt = $db->prepare("SELECT user_id, start_date FROM leave_requests WHERE id = ?");
        $lStmt->execute([$id]);
        $l = $lStmt->fetch();

        if ($l) {
            sendNotification($l['user_id'], 'Leave Request Approved', 'Your leave request has been approved!', 'leaves', 'leave');
            $monthYear = date('Y-m', strtotime($l['start_date']));
            if (function_exists('recalculateUserPayroll')) {
                recalculateUserPayroll($db, (int)$l['user_id'], $monthYear);
            }
        }

        logActivity('Approve Leave', 'Leaves', "Manager approved leave request ID {$id}");
        jsonResponse(true, 'Leave request approved successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Error approving leave: ' . $e->getMessage());
    }
}

function rejectLeave(PDO $db, array $user): void {
    if (!hasRole(['admin', 'manager'])) {
        jsonResponse(false, 'Permission denied', [], 403);
    }

    $id = (int)($_POST['leave_id'] ?? 0);
    $comment = sanitize($_POST['comment'] ?? 'Rejected by manager');

    if ($id <= 0) {
        jsonResponse(false, 'Invalid leave ID');
    }

    try {
        $stmt = $db->prepare("UPDATE leave_requests SET status = 'rejected', approved_by = ?, comment = ? WHERE id = ?");
        $stmt->execute([$user['id'], $comment, $id]);

        $lStmt = $db->prepare("SELECT user_id, start_date FROM leave_requests WHERE id = ?");
        $lStmt->execute([$id]);
        $l = $lStmt->fetch();

        if ($l) {
            sendNotification($l['user_id'], 'Leave Request Rejected', "Leave request rejected: {$comment}", 'leaves', 'leave');
            $monthYear = date('Y-m', strtotime($l['start_date']));
            if (function_exists('recalculateUserPayroll')) {
                recalculateUserPayroll($db, (int)$l['user_id'], $monthYear);
            }
        }

        logActivity('Reject Leave', 'Leaves', "Manager rejected leave request ID {$id}");
        jsonResponse(true, 'Leave request rejected.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error rejecting leave: ' . $e->getMessage());
    }
}

function getLeaveBalanceApi(PDO $db, array $user): void {
    $targetUserId = (int)($_GET['user_id'] ?? ($_POST['user_id'] ?? $user['id']));
    $monthYear = sanitize($_GET['month_year'] ?? ($_POST['month_year'] ?? date('Y-m')));

    if ($targetUserId != $user['id'] && !hasRole(['admin', 'manager'])) {
        $targetUserId = $user['id'];
    }

    try {
        $balance = getEmployeeLeaveBalance($targetUserId, $monthYear);
        jsonResponse(true, 'Leave balance retrieved', ['balance' => $balance]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch leave balance: ' . $e->getMessage());
    }
}
