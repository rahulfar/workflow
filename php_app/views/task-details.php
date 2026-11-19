<?php
$pageTitle = "Task Details";
$user = requireLogin();
$db = Database::getConnection();
ensureEmergencyAndFlagSchema($db);

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$taskId = (int)($_GET['id'] ?? 1);

// Fetch Task Details
$stmt = $db->prepare("
    SELECT t.*, p.name as project_name, d.name as department_name, 
           u_to.name as assignee_name, u_by.name as creator_name,
           u_app.name as approver_name
    FROM tasks t
    LEFT JOIN projects p ON t.project_id = p.id
    LEFT JOIN departments d ON t.department_id = d.id
    LEFT JOIN users u_to ON t.assigned_to = u_to.id
    LEFT JOIN users u_by ON t.assigned_by = u_by.id
    LEFT JOIN users u_app ON t.approved_by = u_app.id
    WHERE t.id = ?
");
$stmt->execute([$taskId]);
$task = $stmt->fetch();

if (!$task) {
    echo "<div class='main-wrapper p-4'><h3>Task not found</h3><a href='" . BASE_URL . "/tasks'>Back to tasks</a></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Fetch Timer Logs
$timers = $db->prepare("SELECT * FROM task_timer WHERE task_id = ? ORDER BY id DESC");
$timers->execute([$taskId]);
$timerLogs = $timers->fetchAll();

// Fetch Comments
$commentsStmt = $db->prepare("SELECT c.*, u.name as user_name FROM task_comments c JOIN users u ON c.user_id = u.id WHERE c.task_id = ? ORDER BY c.id DESC");
$commentsStmt->execute([$taskId]);
$comments = $commentsStmt->fetchAll();

ensureEmergencyAndFlagSchema($db);

// Fetch Emergency Requests for this task
$taskEmergencyRequests = [];
$activeEmgRequest = null;
try {
    $emgRequestsStmt = $db->prepare("
        SELECT r.*, u_emp.name as employee_name, u_rev.name as reviewer_name
        FROM emergency_task_requests r
        JOIN users u_emp ON r.employee_id = u_emp.id
        LEFT JOIN users u_rev ON r.reviewer_id = u_rev.id
        WHERE r.task_id = ?
        ORDER BY r.id DESC
    ");
    $emgRequestsStmt->execute([$taskId]);
    $taskEmergencyRequests = $emgRequestsStmt->fetchAll() ?: [];
    $activeEmgRequest = !empty($taskEmergencyRequests) ? $taskEmergencyRequests[0] : null;
} catch (Exception $e) {
    error_log("task-details emg fetch error: " . $e->getMessage());
}

// Fetch Task Flags for this task
$taskFlags = [];
$activeFlag = null;
try {
    $flagsStmt = $db->prepare("
        SELECT f.*, u_emp.name as employee_name, u_res.name as resolver_name
        FROM task_flags f
        JOIN users u_emp ON f.employee_id = u_emp.id
        LEFT JOIN users u_res ON f.resolved_by = u_res.id
        WHERE f.task_id = ?
        ORDER BY f.id DESC
    ");
    $flagsStmt->execute([$taskId]);
    $taskFlags = $flagsStmt->fetchAll() ?: [];
    foreach ($taskFlags as $fl) {
        if ($fl['status'] !== 'resolved') {
            $activeFlag = $fl;
            break;
        }
    }
} catch (Exception $e) {
    error_log("task-details flags fetch error: " . $e->getMessage());
}

$isAssignedEmployee = ((int)$task['assigned_to'] === (int)$user['id']);
$canManageTasks = canCreateOrEditTasks();
$isAwaitingApproval = in_array($task['status'], ['pending_approval', 'in_review']);
$isCompletedDone = in_array($task['status'], ['completed', 'done']);
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <!-- Approval Status Notice Banner -->
        <?php if ($isAwaitingApproval): ?>
            <?php if ($canManageTasks): ?>
                <div class="alert bg-warning-subtle border border-warning rounded-4 p-3.5 mb-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">
                                    <i class="fas fa-hourglass-half me-1"></i>APPROVAL REQUIRED
                                </span>
                                <small class="text-secondary">Submitted: <?= formatISTDateTime($task['submitted_for_approval_at'] ?? $task['updated_at']) ?></small>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">Task Completed by <?= sanitize($task['assignee_name']) ?> & Awaiting Sign-Off</h5>
                            <?php if (!empty($task['completion_notes'])): ?>
                                <div class="text-secondary small mt-1">
                                    <i class="fas fa-comment-alt me-1 text-primary"></i><strong>Employee Work Summary:</strong> <?= sanitize($task['completion_notes']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <button class="btn btn-success fw-bold px-3.5 py-2 rounded-pill shadow-sm" onclick="approveTaskPrompt(<?= $task['id'] ?>)">
                                <i class="fas fa-check-circle me-1"></i>Approve (Mark Done)
                            </button>
                            <button class="btn btn-outline-danger fw-semibold px-3 py-2 rounded-pill" onclick="rejectApprovalPrompt(<?= $task['id'] ?>)">
                                <i class="fas fa-undo me-1"></i>Request Revision
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert bg-warning-subtle border border-warning rounded-4 p-3 mb-4 shadow-sm">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-clock-rotate-left text-warning fs-4"></i>
                        <div>
                            <strong class="text-dark">Task Submitted for Manager & Product Manager Approval</strong>
                            <div class="text-secondary small">Your completed work is in review. Once approved by your manager, status will automatically update to <strong>Done</strong>.</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php elseif ($isCompletedDone): ?>
            <div class="alert bg-success-subtle border border-success rounded-4 p-3 mb-4 shadow-sm">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle text-success fs-4"></i>
                    <div>
                        <strong class="text-success">Task Approved & Done ✓</strong>
                        <div class="text-secondary small">
                            Completed <?= !empty($task['approver_name']) ? 'and approved by <strong>' . sanitize($task['approver_name']) . '</strong>' : '' ?> on <?= formatISTDateTime($task['approved_at'] ?? $task['completed_at']) ?>.
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Active Emergency Request Banner -->
        <?php if ($activeEmgRequest): ?>
            <?php if ($activeEmgRequest['status'] === 'pending'): ?>
                <div class="alert bg-danger-subtle border border-danger rounded-4 p-3.5 mb-4 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge bg-danger text-white fw-bold px-2.5 py-1 rounded-pill">
                                    <i class="fas fa-bolt me-1 animate-bounce"></i>EMERGENCY TASK ESCALATION PENDING
                                </span>
                                <small class="text-secondary">Submitted: <?= formatISTDateTime($activeEmgRequest['created_at']) ?> by <strong><?= sanitize($activeEmgRequest['employee_name']) ?></strong></small>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">Reason: <?= sanitize($activeEmgRequest['reason']) ?></h5>
                            <div class="text-secondary small mt-1">
                                <i class="fas fa-quote-left text-danger me-1"></i><?= nl2br(sanitize($activeEmgRequest['message'])) ?>
                            </div>
                            <?php if (!empty($activeEmgRequest['attachment'])): ?>
                                <div class="mt-2">
                                    <a href="<?= BASE_URL . sanitize($activeEmgRequest['attachment']) ?>" target="_blank" class="btn btn-xs btn-outline-danger">
                                        <i class="fas fa-paperclip me-1"></i>View Supporting Attachment
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($canManageTasks): ?>
                            <a href="<?= BASE_URL ?>/emergency-approvals?status=pending" class="btn btn-danger fw-bold px-3.5 py-2 rounded-pill shadow-sm">
                                <i class="fas fa-shield-alt me-1"></i>Review in Approvals
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif ($activeEmgRequest['status'] === 'approved'): ?>
                <div class="alert bg-success-subtle border border-success rounded-4 p-3 mb-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-double text-success fs-4"></i>
                            <div>
                                <strong class="text-success">Emergency Escalation Approved ✓</strong>
                                <div class="text-secondary small">Approved by <strong><?= sanitize($activeEmgRequest['reviewer_name'] ?? 'Management') ?></strong> on <?= formatISTDateTime($activeEmgRequest['reviewed_at']) ?>. <?= !empty($activeEmgRequest['reviewer_comment']) ? 'Notes: ' . sanitize($activeEmgRequest['reviewer_comment']) : '' ?></div>
                            </div>
                        </div>
                        <span class="badge bg-success text-white px-2.5 py-1 rounded-pill">Approved</span>
                    </div>
                </div>
            <?php elseif ($activeEmgRequest['status'] === 'rejected'): ?>
                <div class="alert bg-danger-subtle border border-danger rounded-4 p-3 mb-4 shadow-sm">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-times-circle text-danger fs-4"></i>
                            <div>
                                <strong class="text-danger">Emergency Escalation Rejected</strong>
                                <div class="text-secondary small">Reviewed by <strong><?= sanitize($activeEmgRequest['reviewer_name'] ?? 'Management') ?></strong> on <?= formatISTDateTime($activeEmgRequest['reviewed_at']) ?>. <strong>Reason:</strong> <?= sanitize($activeEmgRequest['rejection_reason']) ?></div>
                            </div>
                        </div>
                        <span class="badge bg-danger text-white px-2.5 py-1 rounded-pill">Rejected</span>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Active Task Flag Banner -->
        <?php if ($activeFlag): ?>
            <div class="alert bg-warning-subtle border border-warning rounded-4 p-3.5 mb-4 shadow-sm">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill">
                                <i class="fas fa-flag me-1"></i>TASK FLAGGED: <?= strtoupper(sanitize($activeFlag['status'])) ?>
                            </span>
                            <?= getTaskFlagImpactBadge($activeFlag['impact']) ?>
                            <small class="text-secondary">Reported: <?= formatISTDateTime($activeFlag['created_at']) ?> by <strong><?= sanitize($activeFlag['employee_name']) ?></strong></small>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Category: <?= sanitize($activeFlag['category']) ?></h5>
                        <div class="text-secondary small mt-1">
                            <strong>Missing Requirement:</strong> <?= nl2br(sanitize($activeFlag['missing_requirement'])) ?>
                        </div>
                        <?php if (!empty($activeFlag['attachment'])): ?>
                            <div class="mt-2">
                                <a href="<?= BASE_URL . sanitize($activeFlag['attachment']) ?>" target="_blank" class="btn btn-xs btn-outline-warning text-dark">
                                    <i class="fas fa-paperclip me-1"></i>View Supporting Attachment
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <a href="<?= BASE_URL ?>/flagged-tasks" class="btn btn-warning text-dark fw-bold px-3.5 py-2 rounded-pill shadow-sm">
                        <i class="fas fa-flag me-1"></i>Open Flag Center
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Task Header Banner -->
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                <div>
                    <span class="badge bg-light text-dark border font-monospace mb-2">Task #<?= $task['id'] ?></span>
                    <h3 class="fw-bold text-dark mb-1"><?= sanitize($task['title']) ?></h3>
                    <div class="small text-secondary">
                        <span class="me-3"><i class="fas fa-project-diagram me-1 text-primary"></i><?= sanitize($task['project_name'] ?? 'Direct Task') ?></span>
                        <span class="me-3"><i class="fas fa-building me-1 text-info"></i><?= sanitize($task['department_name']) ?></span>
                        <span><i class="fas fa-user-circle me-1 text-success"></i>Assigned to: <?= sanitize($task['assignee_name']) ?></span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <?= getPriorityBadge($task['priority']) ?>
                    <?= getStatusBadge($task['status'], $task['deadline']) ?>
                </div>
            </div>

            <div class="row g-3 pt-3 border-top">
                <div class="col-md-3 col-6">
                    <small class="text-secondary d-block">Deadline</small>
                    <strong class="text-dark"><?= formatISTDateTime($task['deadline']) ?></strong>
                    <div class="small"><?= getDeadlineCountdown($task['deadline']) ?></div>
                </div>
                <div class="col-md-3 col-6">
                    <small class="text-secondary d-block">Total Spent Time</small>
                    <strong class="text-primary font-monospace fs-5" id="inlineTaskTimer_<?= $task['id'] ?>"><?= formatDuration($task['spent_seconds']) ?></strong>
                </div>
                <div class="col-md-3 col-6">
                    <small class="text-secondary d-block">Estimated Hours</small>
                    <strong class="text-dark font-monospace"><?= number_format($task['estimated_hours'], 1) ?> Hrs</strong>
                </div>
                <div class="col-md-3 col-6 text-md-end">
                    <small class="text-secondary d-block mb-1">Task Action</small>
                    <?php if ($isAssignedEmployee): ?>
                        <?php if ($task['status'] === 'running'): ?>
                            <button class="btn btn-warning btn-sm" onclick="pauseTaskTimer(<?= $task['id'] ?>)"><i class="fas fa-pause me-1"></i>Pause</button>
                            <button class="btn btn-success btn-sm" onclick="stopTaskTimer(<?= $task['id'] ?>)"><i class="fas fa-check-circle me-1"></i>Submit for Approval</button>
                        <?php elseif ($isAwaitingApproval): ?>
                            <span class="badge bg-warning-subtle text-warning border border-warning px-2.5 py-1.5"><i class="fas fa-hourglass-half me-1"></i>Pending Approval</span>
                        <?php elseif ($isCompletedDone): ?>
                            <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1.5"><i class="fas fa-check-circle me-1"></i>Done</span>
                        <?php else: ?>
                            <button class="btn btn-primary btn-sm me-1" onclick="startTaskTimer(<?= $task['id'] ?>)"><i class="fas fa-play me-1"></i>Start Timer</button>
                            <button class="btn btn-outline-success btn-sm" onclick="submitTaskCompletionPrompt(<?= $task['id'] ?>)"><i class="fas fa-paper-plane me-1"></i>Submit Work</button>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($canManageTasks && $isAwaitingApproval): ?>
                            <button class="btn btn-success btn-sm" onclick="approveTaskPrompt(<?= $task['id'] ?>)"><i class="fas fa-check-circle me-1"></i>Approve</button>
                            <button class="btn btn-outline-danger btn-sm" onclick="rejectApprovalPrompt(<?= $task['id'] ?>)"><i class="fas fa-undo me-1"></i>Return</button>
                        <?php elseif ($isCompletedDone): ?>
                            <span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>Done</span>
                        <?php else: ?>
                            <span class="badge bg-light text-secondary border px-2 py-1.5" title="Timer is controlled by assigned employee"><i class="fas fa-lock me-1 text-muted"></i>Assigned to <?= sanitize($task['assignee_name']) ?></span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="mt-2 d-flex gap-1 justify-content-md-end flex-wrap">
                        <button type="button" class="btn btn-outline-danger btn-xs rounded-pill" onclick="openEmergencyModal()"><i class="fas fa-bolt me-1"></i>Emergency</button>
                        <button type="button" class="btn btn-outline-warning text-dark btn-xs rounded-pill" onclick="openFlagModal()"><i class="fas fa-flag me-1"></i>Flag Task</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Description & Comments -->
            <div class="col-lg-8">
                <!-- Description -->
                <div class="glass-card mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-align-left text-primary me-2"></i>Task Instructions</h5>
                    <p class="text-secondary mb-0"><?= nl2br(sanitize($task['description'] ?: 'No detailed instructions provided.')) ?></p>
                </div>

                <!-- Emergency Escalation History Card -->
                <?php if (!empty($taskEmergencyRequests)): ?>
                    <div class="glass-card mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-bolt text-danger me-2"></i>Emergency Escalation History</h5>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="openEmergencyModal()"><i class="fas fa-plus me-1"></i>New Emergency Request</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#Req</th>
                                        <th>Reason & Justification</th>
                                        <th>Reported By</th>
                                        <th>Status</th>
                                        <th>Reviewer Decision</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($taskEmergencyRequests as $er): ?>
                                        <tr>
                                            <td class="font-monospace fw-bold text-muted">#<?= $er['id'] ?></td>
                                            <td>
                                                <strong class="text-dark d-block fs-7"><?= sanitize($er['reason']) ?></strong>
                                                <small class="text-secondary"><?= nl2br(sanitize($er['message'])) ?></small>
                                                <?php if (!empty($er['attachment'])): ?>
                                                    <div class="mt-1">
                                                        <a href="<?= BASE_URL . sanitize($er['attachment']) ?>" target="_blank" class="small text-danger text-decoration-none"><i class="fas fa-paperclip me-1"></i>Attachment</a>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-dark fw-semibold d-block"><?= sanitize($er['employee_name']) ?></small>
                                                <small class="text-muted fs-8"><?= formatISTDateTime($er['created_at']) ?></small>
                                            </td>
                                            <td><?= getEmergencyRequestBadge($er['status']) ?></td>
                                            <td>
                                                <?php if ($er['status'] === 'approved'): ?>
                                                    <small class="text-success fw-semibold d-block"><i class="fas fa-check-circle me-1"></i>Approved by <?= sanitize($er['reviewer_name'] ?? 'Manager') ?></small>
                                                    <small class="text-secondary"><?= sanitize($er['reviewer_comment'] ?? 'Approved') ?></small>
                                                    <small class="text-muted d-block fs-8"><?= formatISTDateTime($er['reviewed_at']) ?></small>
                                                <?php elseif ($er['status'] === 'rejected'): ?>
                                                    <small class="text-danger fw-semibold d-block"><i class="fas fa-times-circle me-1"></i>Rejected by <?= sanitize($er['reviewer_name'] ?? 'Manager') ?></small>
                                                    <small class="text-danger"><?= sanitize($er['rejection_reason'] ?? 'Rejected') ?></small>
                                                    <small class="text-muted d-block fs-8"><?= formatISTDateTime($er['reviewed_at']) ?></small>
                                                <?php else: ?>
                                                    <span class="badge bg-warning-subtle text-warning border">Pending Review</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Missing Requirements & Flags Card -->
                <?php if (!empty($taskFlags)): ?>
                    <div class="glass-card mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="fw-bold text-dark mb-0"><i class="fas fa-flag text-warning me-2"></i>Missing Requirements & Flags</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-warning text-dark" onclick="openFlagModal()"><i class="fas fa-flag me-1"></i>Report Missing Info</button>
                                <a href="<?= BASE_URL ?>/flagged-tasks" class="btn btn-sm btn-light border"><i class="fas fa-external-link-alt me-1"></i>Flag Center</a>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-3">
                            <?php foreach ($taskFlags as $tf): ?>
                                <div class="p-3 bg-white border rounded-3 shadow-xs">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                        <div>
                                            <span class="badge bg-light text-dark border font-monospace me-1">Flag #<?= $tf['id'] ?></span>
                                            <strong class="text-dark"><?= sanitize($tf['category']) ?></strong>
                                            <span class="ms-1"><?= getTaskFlagImpactBadge($tf['impact']) ?></span>
                                        </div>
                                        <div><?= getTaskFlagBadge($tf['status']) ?></div>
                                    </div>
                                    <p class="text-secondary small mb-2"><?= nl2br(sanitize($tf['missing_requirement'])) ?></p>
                                    <?php if (!empty($tf['attachment'])): ?>
                                        <div class="mb-2">
                                            <a href="<?= BASE_URL . sanitize($tf['attachment']) ?>" target="_blank" class="small text-warning text-decoration-none"><i class="fas fa-paperclip me-1"></i>View Supporting Attachment</a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($tf['resolution_message'])): ?>
                                        <div class="p-2.5 bg-success-subtle border border-success rounded-3 mt-2">
                                            <div class="d-flex align-items-center gap-1.5 text-success small fw-bold mb-1">
                                                <i class="fas fa-check-circle"></i>Resolution by <?= sanitize($tf['resolver_name'] ?? 'Management') ?> (<?= formatISTDateTime($tf['resolved_at']) ?>):
                                            </div>
                                            <div class="text-dark small"><?= nl2br(sanitize($tf['resolution_message'])) ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center pt-2 mt-2 border-top text-muted fs-8">
                                        <span>Reported by <strong><?= sanitize($tf['employee_name']) ?></strong> on <?= formatISTDateTime($tf['created_at']) ?></span>
                                        <a href="<?= BASE_URL ?>/flagged-tasks" class="text-primary text-decoration-none fw-semibold">Discussion Thread in Flag Center &rarr;</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Discussion & Work Notes -->
                <div class="glass-card">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-comments text-info me-2"></i>Activity Notes & Comments</h5>
                    
                    <form id="commentForm" class="mb-4">
                        <div class="input-group">
                            <input type="text" name="comment" id="commentInput" class="form-control" placeholder="Write a comment or work update..." required>
                            <button class="btn btn-primary fw-bold" type="submit"><i class="fas fa-paper-plane me-1"></i>Post Update</button>
                        </div>
                    </form>

                    <div class="list-group list-group-flush">
                        <?php if ($comments): foreach ($comments as $c): ?>
                            <div class="list-group-item bg-transparent text-dark border-bottom py-3 px-0">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong class="text-primary small"><?= sanitize($c['user_name']) ?></strong>
                                    <small class="text-muted"><?= date('M d, H:i', strtotime($c['created_at'])) ?></small>
                                </div>
                                <div class="text-secondary small"><?= sanitize($c['comment']) ?></div>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="text-muted text-center py-3 small">No comments posted yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Timer History Sessions -->
            <div class="col-lg-4">
                <div class="glass-card">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-history text-warning me-2"></i>Timer Session History</h5>
                    <div class="list-group list-group-flush">
                        <?php if ($timerLogs): foreach ($timerLogs as $tl): ?>
                            <div class="list-group-item bg-transparent border-bottom px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark border"><?= ucfirst($tl['status']) ?></span>
                                    <span class="font-monospace text-primary fw-bold"><?= formatDuration($tl['duration_seconds']) ?></span>
                                </div>
                                <small class="text-muted d-block mt-1">Start: <?= formatISTDateTime($tl['start_time']) ?></small>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="text-muted text-center py-3 small">No timer sessions logged yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EMERGENCY TASK REQUEST MODAL -->
<div class="modal fade" id="emergencyRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom bg-danger-subtle text-danger py-3 px-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-bolt me-2"></i>Emergency Task Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emergencyRequestForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                <div class="modal-body p-4">
                    <div class="alert alert-warning py-2 px-3 small border mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Submitting an emergency request alerts Admin & Managers immediately for priority review.
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Related Task</label>
                        <input type="text" class="form-control bg-light fw-bold" value="#<?= $task['id'] ?> - <?= sanitize($task['title']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Emergency Reason *</label>
                        <select name="reason" id="emgReasonSelect" class="form-select" required>
                            <option value="">-- Select Emergency Reason --</option>
                            <option value="Urgent client deadline">Urgent client deadline</option>
                            <option value="Critical production blocker / dependency">Critical production blocker / dependency</option>
                            <option value="System downtime / service outage">System downtime / service outage</option>
                            <option value="High-priority production bug fix">High-priority production bug fix</option>
                            <option value="Immediate client escalation">Immediate client escalation</option>
                            <option value="Scope or spec change escalation">Scope or spec change escalation</option>
                            <option value="Other emergency">Other emergency</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Emergency Description / Justification *</label>
                        <textarea name="message" id="emgMessageTextarea" class="form-control" rows="4" placeholder="Explain why this task is urgent and what needs immediate attention..." required minlength="10"></textarea>
                        <div class="form-text fs-8">Explain the urgency clearly for manager & admin evaluation.</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-secondary small fw-semibold">Supporting Attachment (Optional)</label>
                        <input type="file" name="attachment" id="emgAttachmentInput" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf,.zip,.doc,.docx,.txt">
                        <div class="form-text fs-8">Supported: JPG, PNG, PDF, ZIP, DOCX (Max 15MB).</div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fw-bold px-3.5" id="btnSubmitEmergency">
                        <i class="fas fa-paper-plane me-1"></i>Submit Emergency Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TASK FLAG / MISSING REQUIREMENTS MODAL -->
<div class="modal fade" id="flagTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header border-bottom bg-warning-subtle text-dark py-3 px-4">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-flag text-warning me-2"></i>Report Missing Requirements / Flag Task
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="flagTaskForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Task</label>
                        <input type="text" class="form-control bg-light fw-semibold" value="#<?= $task['id'] ?> - <?= sanitize($task['title']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Flag Category *</label>
                        <select name="category" id="flagCategorySelect" class="form-select" required>
                            <option value="">-- Select Category --</option>
                            <option value="Missing Task Requirements">Missing Task Requirements</option>
                            <option value="Missing Content or Images">Missing Content or Images</option>
                            <option value="Missing Client Information">Missing Client Information</option>
                            <option value="Missing Credentials or Access">Missing Credentials or Access</option>
                            <option value="Missing Design or Reference Files">Missing Design or Reference Files</option>
                            <option value="Waiting for Approval">Waiting for Approval</option>
                            <option value="Unclear Instructions">Unclear Instructions</option>
                            <option value="Missing Dependency">Missing Dependency</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Missing Requirement Description *</label>
                        <textarea name="missing_requirement" id="flagMissingRequirementTextarea" class="form-control" rows="4" placeholder="Detail precisely what information, files, access, or instructions are missing..." required minlength="10"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small fw-semibold">Impact on Task *</label>
                        <select name="impact" id="flagImpactSelect" class="form-select" required>
                            <option value="">-- Select Impact Level --</option>
                            <option value="Task Cannot Start">Task Cannot Start</option>
                            <option value="Work Is Blocked">Work Is Blocked</option>
                            <option value="Work Can Continue Partially">Work Can Continue Partially</option>
                            <option value="Clarification Required">Clarification Required</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-secondary small fw-semibold">Supporting Attachment (Optional)</label>
                        <input type="file" name="attachment" id="flagAttachmentInput" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf,.zip,.doc,.docx,.txt">
                        <div class="form-text fs-8">Attach screenshot or error log if applicable (Max 15MB).</div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light py-2.5 px-4">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-3.5" id="btnSubmitFlag">
                        <i class="fas fa-flag me-1"></i>Submit Flag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    function openEmergencyModal() {
        const modalEl = document.getElementById('emergencyRequestModal');
        if (!modalEl) return;
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    }

    function openFlagModal() {
        const modalEl = document.getElementById('flagTaskModal');
        if (!modalEl) return;
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    }

    document.getElementById('emergencyRequestForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitEmergency');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting...';

        const formData = new FormData(this);
        formData.append('action', 'submit');

        fetch('<?= BASE_URL ?>/api/emergency_requests', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Submit Emergency Request';
            if (res.success) {
                Swal.fire({
                    title: 'Emergency Request Submitted!',
                    html: `<strong>Request ID: #${res.request_id}</strong><br>${res.message}<br><small class="text-secondary">Admin & Managers have been notified for immediate review.</small>`,
                    icon: 'success'
                }).then(() => location.reload());
            } else {
                Swal.fire('Submission Failed', res.message, 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Submit Emergency Request';
            Swal.fire('Error', 'Network or server error while submitting emergency request', 'error');
        });
    });

    document.getElementById('flagTaskForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitFlag');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Submitting Flag...';

        const formData = new FormData(this);
        formData.append('action', 'submit');

        fetch('<?= BASE_URL ?>/api/task_flags', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-flag me-1"></i>Submit Flag';
            if (res.success) {
                Swal.fire({
                    title: 'Task Flagged Successfully!',
                    html: `<strong>Flag ID: #${res.flag_id}</strong><br>${res.message}<br><small class="text-secondary">Responsible Manager & Admins have been alerted about the missing requirements.</small>`,
                    icon: 'success'
                }).then(() => location.reload());
            } else {
                Swal.fire('Submission Failed', res.message, 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-flag me-1"></i>Submit Flag';
            Swal.fire('Error', 'Network or server error while submitting task flag', 'error');
        });
    });

    document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('commentInput');
        if (!input.value.trim()) return;

        const formData = new FormData();
        formData.append('action', 'add_comment');
        formData.append('task_id', '<?= $task['id'] ?>');
        formData.append('comment', input.value.trim());
        formData.append('csrf_token', CSRF_TOKEN);

        fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
        .then(res => res.json()).then(res => {
            if (res.success) {
                input.value = '';
                location.reload();
            }
        });
    });

    function approveTaskPrompt(taskId) {
        Swal.fire({
            title: 'Approve Task Completion?',
            text: 'Are you sure you want to approve this task and mark it as Done?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check-circle me-1"></i>Yes, Approve & Mark Done',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'approve_task');
                formData.append('task_id', taskId);
                formData.append('csrf_token', CSRF_TOKEN);

                fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Approved!', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }

    function rejectApprovalPrompt(taskId) {
        Swal.fire({
            title: 'Request Task Revision?',
            text: 'Enter feedback or revisions required for the employee:',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'State what changes or fixes are needed...',
            showCancelButton: true,
            confirmButtonText: 'Return for Revision',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            inputValidator: (value) => {
                if (!value || !value.trim()) {
                    return 'Please provide a reason or revision instructions.';
                }
            }
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'reject_approval');
                formData.append('task_id', taskId);
                formData.append('reason', result.value.trim());
                formData.append('csrf_token', CSRF_TOKEN);

                fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Returned for Revision', res.message, 'info').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }

    function submitTaskCompletionPrompt(taskId) {
        Swal.fire({
            title: 'Submit Task for Approval?',
            text: 'Submit completed deliverables to Manager and Product Manager for approval?',
            icon: 'question',
            input: 'textarea',
            inputPlaceholder: 'Enter summary of work completed (deliverables, links, notes)...',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-paper-plane me-1"></i>Submit for Approval',
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'submit_completion');
                formData.append('task_id', taskId);
                formData.append('notes', result.value || 'Work completed successfully.');
                formData.append('csrf_token', CSRF_TOKEN);

                fetch('<?= BASE_URL ?>/api/tasks', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Submitted!', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }
</script>
