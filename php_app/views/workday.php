<?php
/**
 * Employee "My Workday" Dashboard - WorkFlow Pro
 * Provides personal workday control, shift status, "What are you doing now?" activity tracking,
 * task connections, live activity timer, daily timeline, and work summary report.
 */

$pageTitle = "My Workday";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();
ensureShiftAndActivitySchema($db);
autoCloseOverdueShifts($db, (int)$user['id']);

$userId = (int)$user['id'];
$today = date('Y-m-d');
$settings = getShiftSettings($db);
$statusDetails = getEmployeeStatusDetails($db, $userId);

// Fetch active projects and tasks assigned to user
$projectsStmt = $db->query("SELECT id, name, code FROM projects WHERE status = 'active' ORDER BY name ASC");
$activeProjects = $projectsStmt->fetchAll();

$tasksStmt = $db->prepare("SELECT id, project_id, title, priority, status FROM tasks WHERE assigned_to = ? AND status != 'completed' ORDER BY title ASC");
$tasksStmt->execute([$userId]);
$assignedTasks = $tasksStmt->fetchAll();

// Fetch today's meetings and any pending meeting requests for current employee
$todayMeetingsStmt = $db->prepare("
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
    ORDER BY CASE WHEN m.status = 'pending' THEN 0 WHEN m.status IN ('in_progress', 'accepted') THEN 1 ELSE 2 END, m.start_time ASC
");
$todayMeetingsStmt->execute([$userId, $userId, $userId, $today]);
$myMeetings = $todayMeetingsStmt->fetchAll();

// Check for active pending meeting alert for this employee
$pendingMeetingAlert = null;
foreach ($myMeetings as $pm) {
    if (((int)$pm['employee_id'] === $userId || (int)$pm['created_by'] !== $userId) && $pm['status'] === 'pending') {
        $pendingMeetingAlert = $pm;
        break;
    }
}

// Fetch team members for scheduling modal
$teamMembers = $db->query("SELECT id, name, designation, role FROM users WHERE status = 'active' ORDER BY name ASC")->fetchAll();

// Fetch waiting/blocked reasons
$reasons = $db->query("SELECT * FROM activity_reasons ORDER BY category ASC, id ASC")->fetchAll();

// Daily Durations & Summary
$dailySummary = calculateEmployeeDailyDurations($db, $userId, $today);
$currentAct = $statusDetails['current_activity'];
$isShiftActive = $statusDetails['is_shift_active'];
$isShiftEnded = $statusDetails['is_shift_ended'];
$attendance = $statusDetails['attendance'];

// Attendance timestamps
$clockInFormatted = $statusDetails['clock_in_time'] ?: '--';
$clockOutFormatted = $statusDetails['clock_out_time'] ?: '--';
$lateMinutes = (int)($statusDetails['late_minutes'] ?? 0);
$isLate = ($lateMinutes > 0);

// Shift timing targets
$shiftStartTarget = $settings['start_time'] ?? '09:40';
$shiftEndTarget = $settings['end_time'] ?? '18:30';
$lunchStartTarget = $settings['lunch_start'] ?? '14:00';
$lunchEndTarget = $settings['lunch_end'] ?? '15:00';

// Format 24h targets to AM/PM for display
$fmtShiftStart = date('h:i A', strtotime("2026-01-01 {$shiftStartTarget}:00"));
$fmtShiftEnd = date('h:i A', strtotime("2026-01-01 {$shiftEndTarget}:00"));
$fmtLunchStart = date('h:i A', strtotime("2026-01-01 {$lunchStartTarget}:00"));
$fmtLunchEnd = date('h:i A', strtotime("2026-01-01 {$lunchEndTarget}:00"));
$isClientIpAuth = isClientIpAuthorized($db);
$clientNetworkName = getIpNetworkName($db);

// Shift duration worked in seconds
$shiftWorkedSeconds = 0;
if ($isShiftActive && $attendance) {
    $cInStr = strlen($attendance['clock_in']) <= 8 ? ($today . ' ' . $attendance['clock_in']) : $attendance['clock_in'];
    $shiftWorkedSeconds = max(0, time() - strtotime($cInStr));
} elseif ($attendance) {
    $shiftWorkedSeconds = (int)$attendance['total_seconds'];
}
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-3 p-md-4">
        <!-- Breadcrumb & Page Title -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-primary-subtle text-primary border border-primary px-2.5 py-1 rounded-pill fs-8 fw-semibold">
                        <i class="fas fa-briefcase me-1"></i>Employee Portal
                    </span>
                    <span class="text-secondary small">&bull;</span>
                    <span class="text-secondary small fw-medium"><?= date('l, d F Y') ?></span>
                </div>
                <h3 class="fw-bold text-dark mb-0 font-heading">
                    My Workday <span class="fs-6 fw-normal text-muted ms-1">| Productivity & Activity Hub</span>
                </h3>
            </div>
            
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Today's Live Clock -->
                <div class="bg-white border rounded-pill px-3 py-1.5 shadow-sm d-flex align-items-center gap-2">
                    <i class="fas fa-clock text-primary"></i>
                    <span id="workdayLiveClock" class="font-monospace fw-bold text-dark fs-7">--:--:-- (IST)</span>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold" onclick="openDailyReportModal()">
                    <i class="fas fa-file-invoice text-success me-1"></i>Daily Summary Report
                </button>
            </div>
        </div>

        <!-- TOP BAR: SHIFT OVERVIEW & STATUS HERO -->
        <div class="glass-card mb-4 p-3.5 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.98) 100%);">
            <div class="row g-3 align-items-center">
                <!-- Shift Timings Pill -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="p-3 bg-white rounded-3 border h-100 shadow-xs">
                        <small class="text-uppercase tracking-wider text-muted fw-bold fs-9 d-block mb-1">
                            <i class="fas fa-calendar-day text-primary me-1"></i>Office Shift Schedule
                        </small>
                        <div class="fw-bold text-dark fs-6 line-height-1 mb-1">
                            <?= $fmtShiftStart ?> &ndash; <?= $fmtShiftEnd ?>
                        </div>
                        <small class="text-secondary fs-8 d-block">
                            <i class="fas fa-utensils text-warning me-1"></i>Lunch: <?= $fmtLunchStart ?> &ndash; <?= $fmtLunchEnd ?>
                        </small>
                    </div>
                </div>

                <!-- Live Shift Status Badge Card -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="p-3 bg-white rounded-3 border h-100 shadow-xs">
                        <small class="text-uppercase tracking-wider text-muted fw-bold fs-9 d-block mb-1">
                            Current Status
                        </small>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fs-4"><?= $statusDetails['status_emoji'] ?></span>
                            <div>
                                <div class="fw-bold text-dark fs-6" id="workdayStatusDisplay">
                                    <?= $statusDetails['status_display'] ?>
                                </div>
                                <span class="badge <?= $statusDetails['status_badge_class'] ?> rounded-pill fs-9 px-2 py-0.5" id="workdayStatusBadge">
                                    <?= $statusDetails['status_key'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Task & Activity Snapshot -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="p-3 bg-white rounded-3 border h-100 shadow-xs">
                        <small class="text-uppercase tracking-wider text-muted fw-bold fs-9 d-block mb-1">
                            <i class="fas fa-tasks text-info me-1"></i>Current Task / Project
                        </small>
                        <div class="fw-bold text-dark fs-7 text-truncate mb-0" id="workdayCurrentTaskDisplay" title="<?= sanitize($currentAct['task_title'] ?? 'No task active') ?>">
                            <?= sanitize($currentAct['task_title'] ?? ($currentAct['activity_type'] ?? 'No active task')) ?>
                        </div>
                        <small class="text-muted fs-8 d-block text-truncate" id="workdayCurrentProjectDisplay">
                            <i class="fas fa-folder me-1 text-secondary"></i><?= sanitize($currentAct['project_name'] ?? 'General Agency Work') ?>
                        </small>
                    </div>
                </div>

                <!-- Shift Action Button (Wi-Fi Restricted, Mobile & Desktop Responsive) -->
                <div class="col-12 col-lg-3 text-lg-end workday-shift-action-col wifi-restricted-card">
                    <!-- Unauthorized Fallback Notice -->
                    <div class="wifi-unauth-notice p-3 text-center rounded-3 bg-danger-subtle text-danger border border-danger <?= $isClientIpAuth ? 'd-none' : 'd-flex flex-column' ?>"
                         style="<?= $isClientIpAuth ? 'display:none !important;' : '' ?>">
                        <span class="badge bg-danger text-white border border-danger mb-1"><i class="fas fa-wifi-slash me-1"></i>Office Wi-Fi Required</span>
                        <small class="text-secondary fs-9">Connect to authorized office Wi-Fi network to start or end shift.</small>
                    </div>

                    <!-- Authorized Shift Buttons -->
                    <div class="wifi-restricted-btn <?= $isClientIpAuth ? 'd-block' : 'd-none' ?>"
                         style="<?= $isClientIpAuth ? '' : 'display:none !important;' ?>">
                        <?php if (!$isShiftActive && !$isShiftEnded): ?>
                            <button type="button" id="mainShiftActionBtn" class="btn btn-primary btn-lg w-100 py-3 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 shift-toggle-btn" onclick="handleWorkdayShift('start')">
                                <i class="fas fa-play fs-5"></i>
                                <span class="fs-6">Start Shift</span>
                            </button>
                            <small class="text-muted fs-8 d-block text-center mt-1">Shift Start: <?= $fmtShiftStart ?></small>
                        <?php elseif ($isShiftActive): ?>
                            <div class="d-flex flex-column gap-2">
                                <button type="button" id="mainShiftActionBtn" class="btn btn-success btn-lg w-100 py-2.5 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 shift-toggle-btn" disabled>
                                    <i class="fas fa-check-circle fs-5"></i>
                                    <span class="fs-6">Shift Running ✓</span>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm fw-semibold rounded-pill py-1.5 shift-toggle-btn" onclick="handleWorkdayShift('end')">
                                    <i class="fas fa-stop me-1"></i>End Shift Today
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2">
                                <button type="button" id="mainShiftActionBtn" class="btn btn-primary btn-lg w-100 py-2.5 fw-bold rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 shift-toggle-btn" onclick="handleWorkdayShift('start')">
                                    <i class="fas fa-play fs-5"></i>
                                    <span class="fs-6">Resume / Start Shift</span>
                                </button>
                                <small class="text-success fs-8 d-block text-center"><i class="fas fa-check-double me-1"></i>Worked <?= formatDuration((int)($attendance['total_seconds'] ?? 0)) ?> earlier today</small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Late Arrival Notice if clocked in late -->
            <?php if ($isLate): ?>
                <div class="mt-3 p-2.5 bg-warning-subtle border border-warning rounded-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-clock text-warning fs-5"></i>
                        <div>
                            <strong class="text-dark fs-7">Late Arrival Recorded:</strong>
                            <span class="text-secondary small">Shift start was <strong><?= $fmtShiftStart ?></strong>. Your login was at <strong><?= $clockInFormatted ?></strong>.</span>
                        </div>
                    </div>
                    <span class="badge bg-warning text-dark border border-warning px-2.5 py-1 fw-bold fs-8">
                        Late By <?= $lateMinutes ?> Minutes
                    </span>
                </div>
            <?php endif; ?>
        </div>

        <!-- 4 WORK METRICS COUNTER TILES -->
        <div class="row g-3 mb-4">
            <!-- Time Worked Today (Live Timer) -->
            <div class="col-6 col-md-3">
                <div class="stat-card-luxury" style="--card-accent: #10b981;">
                    <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                        <i class="fas fa-stopwatch"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-dark font-monospace" id="workdayTimeWorkedClock">
                            <?= formatSecondsToHMS($shiftWorkedSeconds) ?>
                        </div>
                        <small class="text-secondary fw-semibold">Time Worked Today</small>
                    </div>
                </div>
            </div>

            <!-- Active Activity Duration -->
            <div class="col-6 col-md-3">
                <div class="stat-card-luxury" style="--card-accent: #4f46e5;">
                    <div class="stat-card-icon" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5;">
                        <i class="fas fa-play-circle"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-primary font-monospace" id="currentActivityLiveTimer">
                            <?= $currentAct ? formatSecondsToHMS((int)$currentAct['elapsed_seconds']) : '00:00:00' ?>
                        </div>
                        <small class="text-secondary fw-semibold">Current Activity Timer</small>
                    </div>
                </div>
            </div>

            <!-- Meeting Time Today -->
            <div class="col-6 col-md-3">
                <div class="stat-card-luxury" style="--card-accent: #8b5cf6;">
                    <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                        <i class="fas fa-video"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-dark font-monospace" id="workdayMeetingTimeVal">
                            <?= $dailySummary['meeting_time_formatted'] ?>
                        </div>
                        <small class="text-secondary fw-semibold">Meetings (Counts as Work)</small>
                    </div>
                </div>
            </div>

            <!-- Tasks Completed Today -->
            <div class="col-6 col-md-3">
                <div class="stat-card-luxury" style="--card-accent: #f59e0b;">
                    <div class="stat-card-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                        <i class="fas fa-check-to-slot"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-dark">
                            <span class="stat-value" id="workdayCompletedTasksVal"><?= $dailySummary['tasks_completed'] ?></span>
                            <span class="fs-7 text-muted"> Done</span>
                        </div>
                        <small class="text-secondary fw-semibold">Tasks Completed Today</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- HIGH-PRIORITY MEETING INVITATION BANNER (If Pending)     -->
        <!-- ========================================================= -->
        <div id="workdayPendingMeetingBanner" class="alert bg-white border border-warning shadow-sm rounded-4 p-3.5 mb-4 position-relative overflow-hidden" 
             data-meeting-id="<?= $pendingMeetingAlert['id'] ?? '' ?>"
             style="border-left: 6px solid #f59e0b !important; <?= $pendingMeetingAlert ? '' : 'display: none;' ?>">
            <?php
            $pCallerName = $pendingMeetingAlert['caller_name'] ?? 'Boss / Admin';
            $pCallerAvatar = $pendingMeetingAlert['caller_avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($pCallerName) . '&background=f59e0b&color=fff');
            ?>
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div class="d-flex align-items-start gap-3">
                    <div class="position-relative flex-shrink-0">
                        <img src="<?= $pCallerAvatar ?>" id="pendingBannerAvatar" class="rounded-circle border shadow-sm" width="50" height="50" alt="Caller">
                        <span class="position-absolute bottom-0 end-0 badge rounded-circle bg-warning p-1 border border-white">
                            <i class="fas fa-video fs-9 text-dark"></i>
                        </span>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                            <span class="badge bg-warning text-dark fw-bold px-2.5 py-1 rounded-pill fs-8">
                                <i class="fas fa-bell me-1 animate-bounce"></i>ACTION REQUIRED: MEETING INVITATION
                            </span>
                            <span class="text-secondary fs-8">From: <strong class="text-dark" id="pendingBannerCaller"><?= sanitize($pCallerName) ?></strong></span>
                            <span class="badge bg-info-subtle text-info border px-2 py-0.5 rounded-pill fs-8" id="pendingBannerClientBadge" style="<?= empty($pendingMeetingAlert['client_name']) ? 'display: none;' : '' ?>">
                                <i class="fas fa-handshake me-1"></i>Client: <span id="pendingBannerClientText"><?= htmlspecialchars($pendingMeetingAlert['client_name'] ?? '') ?></span>
                            </span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1" id="pendingBannerTitle"><?= htmlspecialchars($pendingMeetingAlert['title'] ?? 'Meeting Discussion') ?></h5>
                        <div class="d-flex align-items-center gap-3 text-secondary small flex-wrap" id="pendingBannerMeta">
                            <span><i class="fas fa-clock text-primary me-1"></i><span id="pendingBannerTime"><?= $pendingMeetingAlert['start_time'] ?? '--' ?></span> (<span id="pendingBannerDuration"><?= $pendingMeetingAlert['duration'] ?? 30 ?></span> mins)</span>
                            <?php if (!empty($pendingMeetingAlert['project_name'])): ?>
                                <span><i class="fas fa-folder text-warning me-1"></i><span id="pendingBannerProject"><?= htmlspecialchars($pendingMeetingAlert['project_name']) ?></span></span>
                            <?php endif; ?>
                            <?php if (!empty($pendingMeetingAlert['meeting_link'])): ?>
                                <a href="<?= htmlspecialchars($pendingMeetingAlert['meeting_link']) ?>" target="_blank" id="pendingBannerLink" class="text-primary fw-semibold text-truncate" style="max-width: 250px;">
                                    <i class="fas fa-link me-1"></i><?= htmlspecialchars($pendingMeetingAlert['meeting_link']) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($pendingMeetingAlert['description'])): ?>
                            <div class="mt-1 text-muted fs-8" id="pendingBannerDesc">
                                <i class="fas fa-align-left me-1"></i><?= htmlspecialchars($pendingMeetingAlert['description']) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-lg-auto justify-content-lg-end">
                    <button type="button" class="btn btn-success btn-sm fw-bold px-3.5 py-2 rounded-pill shadow-sm" onclick="handleWorkdayBannerMeetingResponse('accept')">
                        <i class="fas fa-check me-1"></i>Accept & Join
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm fw-semibold px-3 py-2 rounded-pill" onclick="editCurrentPendingMeeting()">
                        <i class="fas fa-pen me-1"></i>Edit Request
                    </button>
                    <button type="button" class="btn btn-outline-warning text-dark btn-sm fw-semibold px-3 py-2 rounded-pill" onclick="openMeetingBusyModal()">
                        <i class="fas fa-clock me-1"></i>I'm Busy
                    </button>
                    <button type="button" class="btn btn-outline-danger btn-sm fw-semibold px-3 py-2 rounded-pill" onclick="handleWorkdayBannerMeetingResponse('decline')">
                        Decline
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- SECTION: TODAY'S MEETINGS & SYNCS (Full Responsive View)  -->
        <!-- ========================================================= -->
        <div class="glass-card mb-4 border-0 shadow-sm p-4" id="workdayMeetingsCard">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0 font-heading d-flex align-items-center gap-2">
                        <i class="fas fa-video text-purple"></i>Today's Meetings & Syncs
                        <span class="badge bg-purple-subtle text-purple border border-purple px-2 py-0.5 rounded-pill fs-8 fw-semibold" id="meetingsCountBadge">
                            <?= count($myMeetings) ?> Scheduled
                        </span>
                    </h5>
                    <small class="text-secondary fs-8">Team discussions, client calls, and boss meetings. Meeting time counts as active productive work.</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-outline-purple btn-sm rounded-pill px-3 fw-bold shadow-sm" onclick="openScheduleMeetingModal()">
                        <i class="fas fa-plus me-1"></i>+ Request / Schedule Meeting
                    </button>
                    <button type="button" class="btn btn-light border btn-sm rounded-pill px-2.5" onclick="checkActiveMeetingRequest(true)" title="Refresh Meetings">
                        <i class="fas fa-sync-alt text-secondary"></i>
                    </button>
                </div>
            </div>

            <div id="workdayMeetingsListContainer">
                <?php if (empty($myMeetings)): ?>
                    <div class="p-3.5 bg-light rounded-3 text-center border">
                        <i class="fas fa-calendar-check text-muted fs-4 mb-2 d-block"></i>
                        <div class="fw-semibold text-dark fs-7">No meetings scheduled for today</div>
                        <small class="text-muted fs-8">You have no upcoming meeting requests. You can request a sync with your boss or schedule a client call anytime.</small>
                    </div>
                <?php else: ?>
                    <div class="row g-3">
                        <?php foreach ($myMeetings as $m): 
                            $isPending = ($m['status'] === 'pending');
                            $isAccepted = ($m['status'] === 'accepted');
                            $isInProgress = ($m['status'] === 'in_progress');
                            $isCaller = ((int)$m['created_by'] === $userId);
                            $isRecipient = ((int)$m['employee_id'] === $userId);
                            
                            $statusBadgeClass = 'bg-secondary';
                            $statusLabel = ucfirst($m['status']);
                            if ($isPending) {
                                $statusBadgeClass = 'bg-warning text-dark';
                                $statusLabel = 'Pending Response';
                            } elseif ($isAccepted) {
                                $statusBadgeClass = 'bg-purple text-white';
                                $statusLabel = 'Scheduled';
                            } elseif ($isInProgress) {
                                $statusBadgeClass = 'bg-success text-white';
                                $statusLabel = 'In Meeting';
                            } elseif ($m['status'] === 'completed') {
                                $statusBadgeClass = 'bg-info text-dark';
                                $statusLabel = 'Completed';
                            } elseif ($m['status'] === 'declined' || $m['status'] === 'busy') {
                                $statusBadgeClass = 'bg-secondary text-white';
                                $statusLabel = ucfirst($m['status']);
                            }

                            $otherPartyName = $isCaller ? ($m['employee_name'] ?? 'Team') : ($m['caller_name'] ?? 'Boss');
                            $otherPartyRole = $isCaller ? ($m['employee_designation'] ?? 'Employee') : ($m['caller_designation'] ?? 'Manager');
                            $avatarSrc = $isCaller 
                                ? ($m['employee_avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($otherPartyName) . '&background=4f46e5&color=fff'))
                                : ($m['caller_avatar'] ?? ('https://ui-avatars.com/api/?name=' . urlencode($otherPartyName) . '&background=7c3aed&color=fff'));
                        ?>
                            <div class="col-12 col-md-6 col-xl-4">
                                <div class="p-3 rounded-3 border bg-white shadow-sm h-100 d-flex flex-column justify-content-between position-relative">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                            <span class="badge <?= $statusBadgeClass ?> px-2.5 py-1 rounded-pill fs-8 fw-semibold">
                                                <?= $statusLabel ?>
                                            </span>
                                            <span class="font-monospace fs-8 fw-bold text-primary">
                                                <i class="fas fa-clock me-1"></i><?= $m['start_time'] ?> (<?= $m['duration'] ?>m)
                                            </span>
                                        </div>

                                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="<?= htmlspecialchars($m['title']) ?>">
                                            <?= htmlspecialchars($m['title']) ?>
                                        </h6>

                                        <div class="d-flex align-items-center gap-1.5 mb-2 flex-wrap">
                                            <?php if (!empty($m['meeting_type']) && $m['meeting_type'] === 'client'): ?>
                                                <span class="badge bg-info-subtle text-info border px-2 py-0.5 rounded-pill fs-9">
                                                    <i class="fas fa-handshake me-1"></i>Client Call
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($m['client_name'])): ?>
                                                <span class="badge bg-light text-dark border px-2 py-0.5 rounded-pill fs-9">
                                                    Client: <?= htmlspecialchars($m['client_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!empty($m['project_name'])): ?>
                                                <span class="badge bg-light text-secondary border px-2 py-0.5 rounded-pill fs-9">
                                                    <?= htmlspecialchars($m['project_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <img src="<?= $avatarSrc ?>" class="rounded-circle border" width="28" height="28" alt="<?= htmlspecialchars($otherPartyName) ?>">
                                            <div class="text-truncate">
                                                <small class="text-dark fw-bold d-block fs-8"><?= sanitize($otherPartyName) ?></small>
                                                <small class="text-muted fs-9"><?= $isCaller ? 'Invited Attendee' : 'Host / Organizer' ?></small>
                                            </div>
                                        </div>

                                        <?php if (!empty($m['meeting_link'])): ?>
                                            <div class="mb-2">
                                                <a href="<?= htmlspecialchars($m['meeting_link']) ?>" target="_blank" class="btn btn-outline-primary btn-sm py-1 px-2.5 rounded-pill w-100 text-truncate fs-8">
                                                    <i class="fas fa-video me-1"></i>Join Video Call
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($m['description'])): ?>
                                            <p class="text-muted fs-8 mb-2 text-truncate" title="<?= htmlspecialchars($m['description']) ?>">
                                                <?= htmlspecialchars($m['description']) ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($m['response_notes'])): ?>
                                            <div class="p-1.5 bg-light rounded text-secondary fs-9 mb-2 border">
                                                <strong>Note:</strong> <?= htmlspecialchars($m['response_notes']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex align-items-center gap-1.5 pt-2 border-top mt-2 justify-content-between flex-wrap">
                                        <button type="button" class="btn btn-light btn-sm text-primary fw-semibold px-2.5 py-1 rounded-pill border fs-8" 
                                                onclick='openEditMeetingModal(<?= json_encode($m, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                            <i class="fas fa-pen me-1"></i>Edit Request
                                        </button>

                                        <?php if ($isPending && $isRecipient): ?>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-success btn-sm px-2.5 py-1 rounded-pill fw-bold fs-8" 
                                                        onclick="respondToMeeting(<?= $m['id'] ?>, 'accept')">
                                                    Accept
                                                </button>
                                                <button type="button" class="btn btn-outline-warning text-dark btn-sm px-2 py-1 rounded-pill fs-8" 
                                                        onclick="respondToMeeting(<?= $m['id'] ?>, 'busy')">
                                                    Busy
                                                </button>
                                            </div>
                                        <?php elseif ($isAccepted || $isInProgress): ?>
                                            <?php if ($isInProgress): ?>
                                                <button type="button" class="btn btn-danger btn-sm px-2.5 py-1 rounded-pill fw-bold fs-8" onclick="endCurrentMeeting(<?= $m['id'] ?>)">
                                                    End Meeting
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-purple btn-sm px-2.5 py-1 rounded-pill fw-bold text-white fs-8" onclick="startMeetingActivity(<?= $m['id'] ?>, '<?= addslashes($m['title']) ?>')">
                                                    Start Meeting
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            <!-- ========================================================= -->
            <!-- SECTION: WHAT ARE YOU DOING NOW? (ACTIVITY SWITCHER)       -->
            <!-- ========================================================= -->
            <div class="col-lg-7">
                <div class="glass-card mb-4 border-0 shadow-sm p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">
                                <i class="fas fa-bolt text-warning me-2"></i>What are you doing now?
                            </h5>
                            <small class="text-secondary fs-8">Select your current activity. Previous activity is automatically closed and saved.</small>
                        </div>
                        <span class="badge bg-light text-secondary border px-2.5 py-1 fs-8">
                            <i class="fas fa-sync-alt me-1 text-primary"></i>Live Tracking
                        </span>
                    </div>

                    <!-- Current Active Indicator Banner -->
                    <div class="p-3 rounded-3 bg-light border mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2 dash-activity-active-banner">
                        <div class="d-flex align-items-center gap-2.5" style="min-width: 0; flex: 1;">
                            <span class="pulse-dot" style="background: <?= $statusDetails['status_color'] ?>; width: 12px; height: 12px; border-radius: 50%; display: inline-block; flex-shrink: 0;"></span>
                            <div style="min-width: 0;">
                                <span class="text-muted fs-8 d-block text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.68rem !important;">ACTIVE SINCE <?= $currentAct ? $currentAct['start_time_formatted'] : '--' ?></span>
                                <strong class="text-dark fs-6 d-inline-block text-truncate align-middle" id="activeActivityTitleDisplay" style="max-width: 100%;">
                                    <?= sanitize($currentAct['activity_type'] ?? 'No activity started') ?>
                                </strong>
                                <?php if (!empty($currentAct['task_title'])): ?>
                                    <span class="badge bg-primary-subtle text-primary border border-primary ms-1 fs-9 text-truncate align-middle" style="max-width: 130px;"><?= sanitize($currentAct['task_title']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-end ps-2" style="flex-shrink: 0;">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="letter-spacing: 0.5px; font-size: 0.65rem !important;">SESSION DURATION</small>
                            <span class="font-monospace fw-bold text-primary fs-5" id="bannerLiveActivityTimer">
                                <?= $currentAct ? formatSecondsToHMS((int)$currentAct['elapsed_seconds']) : '00:00:00' ?>
                            </span>
                        </div>
                    </div>

                    <!-- Activity Selector Buttons Grid -->
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold fs-7 mb-2">Select Activity Type:</label>
                        <div class="d-flex flex-wrap gap-2" id="activityPillsContainer">
                            <?php
                            $activityOptions = [
                                ['Working on Task', 'fas fa-tasks', 'btn-outline-primary'],
                                ['Development', 'fas fa-code', 'btn-outline-primary'],
                                ['Designing', 'fas fa-paint-brush', 'btn-outline-primary'],
                                ['Testing', 'fas fa-vial', 'btn-outline-primary'],
                                ['Client Communication', 'fas fa-comments', 'btn-outline-info'],
                                ['Client Meeting / Call', 'fas fa-video', 'btn-outline-purple'],
                                ['Waiting for Client', 'fas fa-user-clock', 'btn-outline-warning'],
                                ['Client Review & Changes', 'fas fa-pen-ruler', 'btn-outline-primary'],
                                ['Meeting', 'fas fa-users-viewfinder', 'btn-outline-purple'],
                                ['Waiting for Approval', 'fas fa-hourglass-start', 'btn-outline-warning'],
                                ['Waiting for Content', 'fas fa-file-invoice', 'btn-outline-warning'],
                                ['Technical Issue', 'fas fa-triangle-exclamation', 'btn-outline-danger'],
                                ['Other', 'fas fa-ellipsis-h', 'btn-outline-secondary']
                            ];
                            $activeType = $currentAct['activity_type'] ?? '';
                            foreach ($activityOptions as $opt):
                                $isSelected = (strcasecmp($activeType, $opt[0]) === 0);
                            ?>
                                <button type="button" 
                                        class="btn btn-sm <?= $opt[2] ?> rounded-pill px-3 py-1.5 fs-7 activity-pill-btn <?= $isSelected ? 'active' : '' ?>"
                                        data-activity="<?= htmlspecialchars($opt[0]) ?>"
                                        onclick="selectActivityPill('<?= addslashes($opt[0]) ?>')">
                                    <i class="<?= $opt[1] ?> me-1.5"></i><span class="act-label text-truncate"><?= $opt[0] ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Form for Project, Task, Reason, Notes -->
                    <form id="activityChangeForm" onsubmit="submitActivityChange(event)">
                        <input type="hidden" name="activity_type" id="selectedActivityTypeInput" value="<?= htmlspecialchars($currentAct['activity_type'] ?? 'Development') ?>">

                        <!-- Task & Project Cascading Selectors -->
                        <div class="row g-3 mb-3" id="taskProjectRow">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Associated Project</label>
                                <select name="project_id" id="activityProjectSelect" class="form-select form-select-sm" onchange="filterTasksByProject()">
                                    <option value="">-- Select Project (Optional) --</option>
                                    <?php foreach ($activeProjects as $p): ?>
                                        <option value="<?= $p['id'] ?>" <?= ($currentAct && $currentAct['project_id'] == $p['id']) ? 'selected' : '' ?>>
                                            <?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Assigned Task</label>
                                <select name="task_id" id="activityTaskSelect" class="form-select form-select-sm" onchange="handleTaskSelection()">
                                    <option value="">-- Select Task (Optional) --</option>
                                    <?php foreach ($assignedTasks as $t): ?>
                                        <option value="<?= $t['id'] ?>" data-project="<?= $t['project_id'] ?>" <?= ($currentAct && $currentAct['task_id'] == $t['id']) ? 'selected' : '' ?>>
                                            <?= sanitize($t['title']) ?> [<?= ucfirst($t['status']) ?>]
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- CONDITIONAL WAITING / BLOCKED REASON SECTION (Section 10 + Client Waiting) -->
                        <div id="waitingBlockedReasonContainer" class="p-3 bg-warning-subtle border border-warning rounded-3 mb-3" style="display: none;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="fas fa-exclamation-circle text-warning fs-5"></i>
                                <strong class="text-dark fs-7">Reason for Waiting / Blocker:</strong>
                            </div>
                            <div class="row g-2 mb-2">
                                <div class="col-md-7">
                                    <label class="form-label text-dark small fw-semibold mb-1">Reason / Category:</label>
                                    <select name="reason" id="activityReasonSelect" class="form-select form-select-sm">
                                        <option value="">-- Select Specific Reason --</option>
                                        <?php 
                                        $clientReasons = [];
                                        $internalReasons = [];
                                        $blockerReasons = [];
                                        foreach ($reasons as $r) {
                                            $rt = strtolower($r['reason_text']);
                                            if (str_contains($rt, 'client')) {
                                                $clientReasons[] = $r;
                                            } elseif ($r['category'] === 'blocked') {
                                                $blockerReasons[] = $r;
                                            } else {
                                                $internalReasons[] = $r;
                                            }
                                        }
                                        ?>
                                        <optgroup label="Client-Related Waiting & Dependencies">
                                            <?php foreach ($clientReasons as $r): ?>
                                                <option value="<?= htmlspecialchars($r['reason_text']) ?>"><?= sanitize($r['reason_text']) ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="Internal Approvals & Team Waiting">
                                            <?php foreach ($internalReasons as $r): ?>
                                                <option value="<?= htmlspecialchars($r['reason_text']) ?>"><?= sanitize($r['reason_text']) ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                        <optgroup label="Technical & Operational Blockers">
                                            <?php foreach ($blockerReasons as $r): ?>
                                                <option value="<?= htmlspecialchars($r['reason_text']) ?>"><?= sanitize($r['reason_text']) ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label text-dark small fw-semibold mb-1">Client Name / Contact (Optional):</label>
                                    <input type="text" name="client_name" id="activityClientNameInput" class="form-control form-control-sm" placeholder="e.g. Maharani Website Client / Krafting">
                                </div>
                            </div>
                        </div>

                        <!-- Notes Field -->
                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-semibold">Work Notes / Details (Optional)</label>
                            <textarea name="notes" id="activityNotesInput" class="form-control form-control-sm" rows="2" placeholder="e.g. Mobile Optimization, working on navigation drawer or waiting on client banners..."><?= sanitize($currentAct['notes'] ?? '') ?></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 dash-activity-actions-row">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm" id="updateActivitySubmitBtn">
                                <i class="fas fa-check-circle me-1.5"></i>Update Activity Now
                            </button>
                            
                            <?php if ($currentAct && !empty($currentAct['task_id'])): ?>
                                <button type="button" class="btn btn-outline-success btn-sm fw-semibold rounded-pill px-3" onclick="completeCurrentTask(<?= $currentAct['task_id'] ?>)">
                                    <i class="fas fa-check-double me-1"></i>Complete Task
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- SECTION: DAILY ACTIVITY TIMELINE (Section 8)               -->
            <!-- ========================================================= -->
            <div class="col-lg-5">
                <div class="glass-card mb-4 border-0 shadow-sm p-4 h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">
                                <i class="fas fa-stream text-primary me-2"></i>Today's Activity Timeline
                            </h5>
                            <small class="text-secondary fs-8">Chronological work record for <?= date('d M Y') ?></small>
                        </div>
                        <div class="d-flex align-items-center gap-1.5">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 fs-8 fw-semibold" onclick="openLogPastActivityModal()" title="Log activity or client waiting according to shift timings">
                                <i class="fas fa-plus-circle me-1"></i>+ Log / Edit Shift
                            </button>
                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-2.5" onclick="loadWorkdayTimeline()" title="Refresh Timeline">
                                <i class="fas fa-sync-alt text-primary"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Timeline Scrollable Feed -->
                    <div class="flex-grow-1 overflow-y-auto pe-1" id="timelineContainer" style="max-height: 480px;">
                        <div class="text-center py-4 text-muted small">
                            <div class="spinner-border spinner-border-sm text-primary me-1" role="status"></div>
                            Loading timeline...
                        </div>
                    </div>

                    <!-- Timeline Summary Footer -->
                    <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center fs-8 text-secondary">
                        <div><i class="fas fa-stopwatch me-1 text-success"></i>Working: <strong id="timelineSummaryWorking"><?= $dailySummary['working_time_formatted'] ?></strong></div>
                        <div><i class="fas fa-video me-1 text-purple"></i>Meetings: <strong id="timelineSummaryMeetings"><?= $dailySummary['meeting_time_formatted'] ?></strong></div>
                        <div><i class="fas fa-hourglass-half me-1 text-warning"></i>Waiting: <strong id="timelineSummaryWaiting"><?= $dailySummary['waiting_time_formatted'] ?></strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL 1: REAL-TIME MEETING REQUEST INVITATION MODAL       -->
<!-- ========================================================= -->
<div class="modal fade" id="meetingRequestAlertModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white p-3" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%) !important;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-bell me-2 text-warning animate-bounce"></i>Meeting Request
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Boss has requested you to join a meeting.</p>
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img src="" id="meetingCallerAvatar" class="rounded-circle border" width="36" height="36" alt="Caller">
                        <div>
                            <strong class="text-dark d-block fs-7" id="meetingCallerName">Boss / Admin</strong>
                            <small class="text-muted fs-8" id="meetingCallerDesignation">Executive</small>
                        </div>
                    </div>
                    <div class="mb-1"><small class="text-muted fs-8 text-uppercase">Topic:</small> <strong class="text-dark fs-7 d-block" id="meetingTopicText">Maharani Website Discussion</strong></div>
                    <div class="row g-2 mt-1">
                        <div class="col-6"><small class="text-muted fs-8">Time:</small> <div class="fw-bold text-dark fs-7" id="meetingTimeText">--</div></div>
                        <div class="col-6"><small class="text-muted fs-8">Expected Duration:</small> <div class="fw-bold text-dark fs-7" id="meetingDurationText">30 minutes</div></div>
                    </div>
                    <div class="mt-2" id="meetingLinkWrap" style="display:none;">
                        <small class="text-muted fs-8">Location / Link:</small>
                        <a href="#" target="_blank" id="meetingLinkAnchor" class="text-primary small d-block text-truncate fw-semibold"></a>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small">Response Note / Reason (Optional):</label>
                    <input type="text" id="meetingResponseNotesInput" class="form-control form-control-sm" placeholder="e.g. Joining now / In middle of production deploy...">
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-success fw-bold flex-fill py-2" onclick="respondToMeetingModal('accept')">
                        <i class="fas fa-check me-1"></i>Accept & Join
                    </button>
                    <button type="button" class="btn btn-outline-primary fw-semibold px-3 py-2" onclick="editFromAlertModal()">
                        <i class="fas fa-pen me-1"></i>Edit Request
                    </button>
                    <button type="button" class="btn btn-warning fw-bold flex-fill py-2 text-dark" onclick="openMeetingBusyModal()">
                        <i class="fas fa-clock me-1"></i>I'm Busy
                    </button>
                    <button type="button" class="btn btn-outline-danger fw-semibold px-3 py-2" onclick="respondToMeetingModal('decline')">
                        Decline
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL 2: GENTLE ACTIVITY UPDATE REMINDER MODAL (Req #9)   -->
<!-- ========================================================= -->
<div class="modal fade" id="activityReminderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body p-4 text-center">
                <div class="rounded-circle bg-warning-subtle text-warning d-inline-flex p-3 mb-3">
                    <i class="fas fa-hourglass-half fs-2"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Activity Update Reminder</h5>
                <p class="text-secondary small mb-3">
                    Your current activity has not been updated for <strong><?= (int)($settings['reminder_interval_minutes'] ?? 30) ?> minutes</strong>.
                    Please update what you are currently working on.
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-primary fw-bold rounded-pill px-4" data-bs-dismiss="modal" onclick="focusActivitySelector()">
                        <i class="fas fa-edit me-1"></i>Update Activity
                    </button>
                    <button type="button" class="btn btn-outline-secondary fw-semibold rounded-pill px-3" onclick="acknowledgeReminder()">
                        Still Working on This
                    </button>
                </div>
                <small class="text-muted fs-9 d-block mt-3"><i class="fas fa-info-circle me-1"></i>We never automatically mark you idle or assume you are not working.</small>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL 3: ACTIVITY DETAIL INSPECTOR MODAL                  -->
<!-- ========================================================= -->
<div class="modal fade" id="activityDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom p-3">
                <h6 class="modal-title fw-bold text-dark" id="activityDetailModalTitle">
                    <i class="fas fa-info-circle text-primary me-2"></i>Activity Details
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="activityDetailModalBody">
                <!-- Dynamically loaded -->
            </div>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL 4: DAILY WORK SUMMARY REPORT MODAL (Req #16)        -->
<!-- ========================================================= -->
<div class="modal fade" id="dailyWorkReportModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-bottom p-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-file-invoice text-success me-2"></i>Daily Work Summary Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="dailyReportModalBody">
                <div class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div></div>
            </div>
            <div class="modal-footer p-2 bg-light">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" onclick="window.print()"><i class="fas fa-print me-1"></i>Print / Export</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL 5: EDIT / RESCHEDULE MEETING REQUEST MODAL          -->
<!-- ========================================================= -->
<div class="modal fade" id="editMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white p-3" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%) !important;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-pen-to-square me-2"></i>Edit Meeting Request / Reschedule
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMeetingForm" onsubmit="submitEditMeeting(event)">
                <div class="modal-body p-4">
                    <input type="hidden" name="meeting_id" id="editMeetingId">

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Meeting Title / Topic <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editMeetingTitle" class="form-control" required placeholder="e.g. Website Discussion / Client Review">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Meeting Type</label>
                            <select name="meeting_type" id="editMeetingType" class="form-select form-select-sm">
                                <option value="internal">Internal Team Sync</option>
                                <option value="boss">Boss / Management Call</option>
                                <option value="client">Client Meeting / Call</option>
                                <option value="review">Work / Design Review</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Client Name (Optional)</label>
                            <input type="text" name="client_name" id="editMeetingClientName" class="form-control form-control-sm" placeholder="e.g. Maharani Client">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Associated Project</label>
                        <select name="project_id" id="editMeetingProjectId" class="form-select form-select-sm">
                            <option value="">-- None / General Sync --</option>
                            <?php foreach ($activeProjects as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Meeting Date</label>
                            <input type="date" name="meeting_date" id="editMeetingDate" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-dark fw-semibold small">Start Time</label>
                            <input type="time" name="start_time" id="editMeetingStartTime" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-dark fw-semibold small">Duration (Min)</label>
                            <select name="duration" id="editMeetingDuration" class="form-select form-select-sm">
                                <option value="15">15 min</option>
                                <option value="30" selected>30 min</option>
                                <option value="45">45 min</option>
                                <option value="60">60 min</option>
                                <option value="90">90 min</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-dark fw-semibold small mb-0">Meeting Link (Google Meet / Zoom)</label>
                            <button type="button" class="btn btn-link btn-sm p-0 fs-8 text-primary" onclick="generateMeetLink('editMeetingLink')">
                                <i class="fas fa-magic me-1"></i>Generate Link
                            </button>
                        </div>
                        <input type="url" name="meeting_link" id="editMeetingLink" class="form-control form-control-sm" placeholder="https://meet.google.com/xyz-abcd-efg">
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Notes / Reschedule Reason / Agenda</label>
                        <textarea name="response_notes" id="editMeetingNotes" class="form-control form-control-sm" rows="2" placeholder="e.g. Can we meet at 4:45 PM instead? Wrapping up the mobile layout..."></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold small">Meeting Status</label>
                        <select name="status" id="editMeetingStatus" class="form-select form-select-sm">
                            <option value="pending">Keep as Pending Request</option>
                            <option value="accepted">Accepted (Will Join)</option>
                            <option value="busy">Mark as Busy (Need to Reschedule)</option>
                            <option value="declined">Declined</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" id="submitEditMeetingBtn">
                        <i class="fas fa-save me-1"></i>Save Changes & Update Meeting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL 6: SCHEDULE / REQUEST NEW MEETING MODAL            -->
<!-- ========================================================= -->
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white p-3" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%) !important;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-calendar-plus me-2"></i>Schedule / Request a Meeting
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="scheduleMeetingForm" onsubmit="submitScheduleMeeting(event)">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Who do you want to meet with? <span class="text-danger">*</span></label>
                        <select name="employee_id" id="scheduleMeetingTargetUser" class="form-select form-select-sm" required>
                            <option value="">-- Select Person / Boss / Colleague --</option>
                            <?php foreach ($teamMembers as $tm): 
                                $isSelf = ((int)$tm['id'] === $userId);
                            ?>
                                <option value="<?= $tm['id'] ?>" <?= $isSelf ? 'selected' : '' ?>>
                                    <?= sanitize($tm['name']) ?> (<?= sanitize($tm['designation'] ?: ucfirst($tm['role'])) ?>) <?= $isSelf ? ' [Myself / Client Call]' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Meeting Title / Topic <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="scheduleMeetingTitle" class="form-control" required placeholder="e.g. Maharani Website Discussion / Client Feedback">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Meeting Type</label>
                            <select name="meeting_type" id="scheduleMeetingType" class="form-select form-select-sm" onchange="handleMeetingTypeChange(this.value)">
                                <option value="boss">Boss / Manager Sync</option>
                                <option value="client" selected>Client Call / Meeting</option>
                                <option value="internal">Internal Team Standup</option>
                                <option value="review">Design / Code Review</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Client Name (Optional)</label>
                            <input type="text" name="client_name" id="scheduleMeetingClientName" class="form-control form-control-sm" placeholder="e.g. Maharani Client">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Associated Project</label>
                        <select name="project_id" id="scheduleMeetingProjectId" class="form-select form-select-sm">
                            <option value="">-- None / General Sync --</option>
                            <?php foreach ($activeProjects as $p): ?>
                                <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Date</label>
                            <input type="date" name="meeting_date" id="scheduleMeetingDate" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-dark fw-semibold small">Start Time</label>
                            <input type="time" name="start_time" id="scheduleMeetingStartTime" class="form-control form-control-sm" value="<?= date('H:i') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-dark fw-semibold small">Duration (Min)</label>
                            <select name="duration" id="scheduleMeetingDuration" class="form-select form-select-sm">
                                <option value="15">15 min</option>
                                <option value="30" selected>30 min</option>
                                <option value="45">45 min</option>
                                <option value="60">60 min</option>
                                <option value="90">90 min</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label text-dark fw-semibold small mb-0">Meeting Link (Google Meet / Zoom)</label>
                            <button type="button" class="btn btn-link btn-sm p-0 fs-8 text-primary" onclick="generateMeetLink('scheduleMeetingLink')">
                                <i class="fas fa-magic me-1"></i>Generate Link
                            </button>
                        </div>
                        <input type="url" name="meeting_link" id="scheduleMeetingLink" class="form-control form-control-sm" placeholder="https://meet.google.com/krd-...">
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold small">Description / Agenda (Optional)</label>
                        <textarea name="description" id="scheduleMeetingDescription" class="form-control form-control-sm" rows="2" placeholder="Brief points or reason for the meeting..."></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" id="submitScheduleMeetingBtn">
                        <i class="fas fa-paper-plane me-1"></i>Send Meeting Request
                    </button>
                </div>
            </form>
        </div>
    </div>
<!-- ========================================================= -->
<!-- MODAL: I'M BUSY / CLIENT WAITING RESPONSE MODAL           -->
<!-- ========================================================= -->
<div class="modal fade" id="meetingBusyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom p-3 bg-light">
                <h6 class="modal-title fw-bold text-dark font-heading">
                    <i class="fas fa-clock text-warning me-2"></i>I'm Busy / Client Waiting Details
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="meetingBusyForm" onsubmit="submitMeetingBusyResponse(event)">
                <input type="hidden" id="meetingBusyMeetingId" value="">
                <div class="modal-body p-3.5">
                    <div class="p-2.5 rounded-3 bg-warning-subtle border border-warning-subtle mb-3 small">
                        <div class="text-dark fw-bold mb-1" id="meetingBusyTargetText">Meeting Discussion</div>
                        <div class="text-secondary fs-8">Let the meeting organizer know what you are currently occupied with. Your active Workday status will also be updated accordingly.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Current Activity / Reason <span class="text-danger">*</span></label>
                        <select id="meetingBusyCategory" class="form-select form-select-sm" required>
                            <optgroup label="Client Waiting / Client Relations">
                                <option value="Waiting for Client" selected>Waiting for Client (Feedback / Assets / Approval)</option>
                                <option value="Client Meeting / Call">Client Meeting / Live Client Call</option>
                                <option value="Client Communication">Client Communication (WhatsApp / Email / Sync)</option>
                                <option value="Client Review & Changes">Client Review & Requested Changes</option>
                            </optgroup>
                            <optgroup label="Tasks & Technical Work">
                                <option value="Working on Task">Working on Priority Task</option>
                                <option value="Development">Critical Development / Coding</option>
                                <option value="Testing">Testing & Bug Verification</option>
                                <option value="Technical Issue">Technical Issue / Blocker</option>
                            </optgroup>
                            <optgroup label="Other">
                                <option value="Meeting">In another internal meeting</option>
                                <option value="Other">Other Shift Work</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Client Name (If applicable)</label>
                            <input type="text" id="meetingBusyClientName" class="form-control form-control-sm" placeholder="e.g. Maharani Website / Krafting">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">When will you be available?</label>
                            <select id="meetingBusyAvailability" class="form-select form-select-sm">
                                <option value="Available in 15 mins">Available in 15 mins</option>
                                <option value="Available in 30 mins" selected>Available in 30 mins</option>
                                <option value="Available in 1 hour">Available in 1 hour</option>
                                <option value="Available after 2:00 PM">Available after 2:00 PM</option>
                                <option value="Available at 4:00 PM">Available at 4:00 PM</option>
                                <option value="Available at 5:30 PM">Available at 5:30 PM</option>
                                <option value="Available tomorrow">Available tomorrow</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Associated Project</label>
                            <select id="meetingBusyProjectId" class="form-select form-select-sm">
                                <option value="">-- None / General --</option>
                                <?php foreach ($activeProjects as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Assigned Task</label>
                            <select id="meetingBusyTaskId" class="form-select form-select-sm">
                                <option value="">-- None / General --</option>
                                <?php foreach ($assignedTasks as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= sanitize($t['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Note for Organizer (Optional)</label>
                        <textarea id="meetingBusyNotes" class="form-control form-control-sm" rows="2" placeholder="e.g. Waiting for client to confirm credentials, will ping as soon as they reply..."></textarea>
                    </div>

                    <div class="form-check form-switch p-2 bg-light rounded-3 border">
                        <input class="form-check-input ms-0 me-2" type="checkbox" id="meetingBusyUpdateWorkdayCheck" checked>
                        <label class="form-check-label small fw-semibold text-dark" for="meetingBusyUpdateWorkdayCheck">
                            Update my active Workday status to this activity now
                        </label>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-dark btn-sm rounded-pill px-4 fw-bold shadow-sm" id="submitMeetingBusyBtn">
                        <i class="fas fa-paper-plane me-1"></i>Send Busy Note & Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL: EDIT WORKDAY ACTIVITY & SHIFT RECORD               -->
<!-- ========================================================= -->
<div class="modal fade" id="editWorkdayActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom p-3 bg-light">
                <h6 class="modal-title fw-bold text-dark font-heading">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Workday Activity & Shift Timing
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editWorkdayActivityForm" onsubmit="submitEditWorkdayActivity(event)">
                <input type="hidden" name="id" id="editActId" value="">
                <div class="modal-body p-3.5">
                    <div class="p-2.5 rounded-3 bg-primary-subtle border border-primary-subtle mb-3 small d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <i class="fas fa-business-time text-primary me-1"></i>
                            <strong>Office Shift:</strong> <?= $fmtShiftStart ?> – <?= $fmtShiftEnd ?>
                            <span class="text-secondary">(Lunch: <?= $fmtLunchStart ?> – <?= $fmtLunchEnd ?>)</span>
                        </div>
                        <span id="editActDurationPreview" class="badge bg-primary rounded-pill px-2.5 py-1 fs-9">Duration: --</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Activity Type <span class="text-danger">*</span></label>
                        <select name="activity_type" id="editActType" class="form-select form-select-sm" required>
                            <optgroup label="Standard Work">
                                <option value="Working on Task">Working on Task</option>
                                <option value="Development">Development</option>
                                <option value="Designing">Designing</option>
                                <option value="Testing">Testing</option>
                            </optgroup>
                            <optgroup label="Client & Communication">
                                <option value="Client Communication">Client Communication</option>
                                <option value="Client Meeting / Call">Client Meeting / Call</option>
                                <option value="Waiting for Client">Waiting for Client (Feedback / Assets / Approval)</option>
                                <option value="Client Review & Changes">Client Review & Changes</option>
                                <option value="Meeting">Meeting</option>
                            </optgroup>
                            <optgroup label="Blockers & Waiting">
                                <option value="Waiting for Approval">Waiting for Approval</option>
                                <option value="Waiting for Content">Waiting for Content</option>
                                <option value="Technical Issue">Technical Issue</option>
                                <option value="Other">Other</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Date</label>
                            <input type="date" name="date" id="editActDate" class="form-control form-control-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Client Name (Optional)</label>
                            <input type="text" name="client_name" id="editActClientName" class="form-control form-control-sm" placeholder="e.g. Maharani Website">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label text-dark fw-semibold small">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" id="editActStartTime" class="form-control form-control-sm" required onchange="calcEditDuration()">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-dark fw-semibold small">End Time</label>
                            <input type="time" name="end_time" id="editActEndTime" class="form-control form-control-sm" onchange="calcEditDuration()">
                        </div>
                    </div>

                    <!-- Quick Shift Timing Presets -->
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <small class="text-muted fs-9 d-block w-100">Quick Shift Timing Presets:</small>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill fs-9 py-0 px-2" onclick="setEditShiftTime('09:40', '14:00')">Morning (09:40-02:00)</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill fs-9 py-0 px-2" onclick="setEditShiftTime('15:00', '18:30')">Afternoon (03:00-06:30)</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill fs-9 py-0 px-2" onclick="setEditEndTimeNow()">End = Now</button>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Project</label>
                            <select name="project_id" id="editActProjectId" class="form-select form-select-sm">
                                <option value="">-- None / General --</option>
                                <?php foreach ($activeProjects as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Task</label>
                            <select name="task_id" id="editActTaskId" class="form-select form-select-sm">
                                <option value="">-- None / General --</option>
                                <?php foreach ($assignedTasks as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= sanitize($t['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Reason / Blocker (Optional)</label>
                        <select name="reason" id="editActReason" class="form-select form-select-sm">
                            <option value="">-- Select Reason If Waiting/Blocked --</option>
                            <?php foreach ($reasons as $r): ?>
                                <option value="<?= htmlspecialchars($r['reason_text']) ?>"><?= sanitize($r['reason_text']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold small">Work Notes</label>
                        <textarea name="notes" id="editActNotes" class="form-control form-control-sm" rows="2" placeholder="Details of what was done or client waiting notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="deleteCurrentWorkdayActivity()">
                        <i class="fas fa-trash me-1"></i>Delete Entry
                    </button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" id="submitEditActivityBtn">
                            <i class="fas fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL: LOG PAST ACTIVITY OR CLIENT WAITING SESSION        -->
<!-- ========================================================= -->
<div class="modal fade" id="logPastActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom p-3 bg-light">
                <h6 class="modal-title fw-bold text-dark font-heading">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Log Workday Activity / Client Waiting
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="logPastActivityForm" onsubmit="submitLogPastActivity(event)">
                <div class="modal-body p-3.5">
                    <div class="p-2.5 rounded-3 bg-light border mb-3 small d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <i class="fas fa-info-circle text-primary me-1"></i>
                            <strong>Shift:</strong> <?= $fmtShiftStart ?> – <?= $fmtShiftEnd ?> (Lunch: <?= $fmtLunchStart ?> – <?= $fmtLunchEnd ?>)
                        </div>
                        <span id="logPastDurationPreview" class="badge bg-primary-subtle text-primary border rounded-pill px-2.5 py-1 fs-9">Duration: 1h 0m</span>
                    </div>

                    <!-- Quick Shift Presets -->
                    <div class="d-flex flex-wrap gap-1 mb-3">
                        <button type="button" class="btn btn-xs btn-outline-primary rounded-pill py-0.5 px-2.5 fs-8" onclick="setPastLogTimes('09:40', '14:00')">Morning Shift (09:40-02:00)</button>
                        <button type="button" class="btn btn-xs btn-outline-primary rounded-pill py-0.5 px-2.5 fs-8" onclick="setPastLogTimes('15:00', '18:30')">Afternoon Shift (03:00-06:30)</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0.5 px-2.5 fs-8" onclick="setPastLogLastMinutes(60)">Last 1 Hour</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill py-0.5 px-2.5 fs-8" onclick="setPastLogLastMinutes(30)">Last 30 Mins</button>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Activity Type <span class="text-danger">*</span></label>
                        <select name="activity_type" id="logPastType" class="form-select form-select-sm" required>
                            <optgroup label="Client Relations & Waiting">
                                <option value="Waiting for Client" selected>Waiting for Client (Feedback / Assets / Approval)</option>
                                <option value="Client Meeting / Call">Client Meeting / Call</option>
                                <option value="Client Communication">Client Communication (WhatsApp / Email)</option>
                                <option value="Client Review & Changes">Client Review & Changes</option>
                            </optgroup>
                            <optgroup label="Direct Work & Tasks">
                                <option value="Working on Task">Working on Task</option>
                                <option value="Development">Development</option>
                                <option value="Designing">Designing</option>
                                <option value="Testing">Testing</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Other">Other Shift Work</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Date</label>
                            <input type="date" name="date" id="logPastDate" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Client Name (Optional)</label>
                            <input type="text" name="client_name" id="logPastClientName" class="form-control form-control-sm" placeholder="e.g. Maharani Website">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label text-dark fw-semibold small">Start Time <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" id="logPastStartTime" class="form-control form-control-sm" value="09:40" required onchange="calcPastLogDuration()">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-dark fw-semibold small">End Time <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" id="logPastEndTime" class="form-control form-control-sm" value="10:40" required onchange="calcPastLogDuration()">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Associated Project</label>
                            <select name="project_id" id="logPastProjectId" class="form-select form-select-sm">
                                <option value="">-- None / General --</option>
                                <?php foreach ($activeProjects as $p): ?>
                                    <option value="<?= $p['id'] ?>"><?= sanitize($p['name']) ?> (<?= sanitize($p['code']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-dark fw-semibold small">Assigned Task</label>
                            <select name="task_id" id="logPastTaskId" class="form-select form-select-sm">
                                <option value="">-- None / General --</option>
                                <?php foreach ($assignedTasks as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= sanitize($t['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark fw-semibold small">Reason / Blocker (Optional)</label>
                        <select name="reason" id="logPastReason" class="form-select form-select-sm">
                            <option value="">-- Select Reason If Waiting/Blocked --</option>
                            <?php foreach ($reasons as $r): ?>
                                <option value="<?= htmlspecialchars($r['reason_text']) ?>"><?= sanitize($r['reason_text']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label text-dark fw-semibold small">Notes / Summary</label>
                        <textarea name="notes" id="logPastNotes" class="form-control form-control-sm" rows="2" placeholder="Details about this work or client waiting period..."></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" id="submitLogPastActivityBtn">
                        <i class="fas fa-plus me-1"></i>Add to Workday
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- Custom Workday JavaScript Engine -->
<script>
let currentActivityStartTimestamp = <?= $currentAct ? strtotime($currentAct['start_time']) : 0 ?>;
let shiftStartServerTimestamp = <?= ($isShiftActive && $attendance) ? strtotime($attendance['clock_in']) : 0 ?>;
let activeReminderShown = false;
let currentPendingMeetingId = null;

document.addEventListener('DOMContentLoaded', () => {
    initWorkdayClocks();
    loadWorkdayTimeline();
    checkActiveMeetingRequest();
    
    // Polling intervals: 10s for meeting requests and activity reminder checks
    setInterval(checkActiveMeetingRequest, 10000);
    setInterval(checkActivityReminderServer, 30000);

    // Initial check for waiting / blocked container
    const initialAct = document.getElementById('selectedActivityTypeInput').value;
    toggleReasonContainer(initialAct);
});

// Live IST Clocks & Durations
function initWorkdayClocks() {
    function tick() {
        const now = new Date();
        const clockEl = document.getElementById('workdayLiveClock');
        if (clockEl) {
            clockEl.textContent = getISTTimeString(now);
        }

        // Live shift worked timer
        if (shiftStartServerTimestamp > 0) {
            const shiftSecs = Math.max(0, Math.floor((Date.now() / 1000) - shiftStartServerTimestamp));
            const shiftClock = document.getElementById('workdayTimeWorkedClock');
            if (shiftClock) shiftClock.textContent = formatSecondsToHMS(shiftSecs);
        }

        // Live current activity timer
        if (currentActivityStartTimestamp > 0) {
            const actSecs = Math.max(0, Math.floor((Date.now() / 1000) - currentActivityStartTimestamp));
            const actTimerEl = document.getElementById('currentActivityLiveTimer');
            if (actTimerEl) actTimerEl.textContent = formatSecondsToHMS(actSecs);

            const bannerTimerEl = document.getElementById('bannerLiveActivityTimer');
            if (bannerTimerEl) bannerTimerEl.textContent = formatSecondsToHMS(actSecs);
        }
    }
    tick();
    setInterval(tick, 1000);
}

// Activity Pill Selection
function selectActivityPill(activityType) {
    document.getElementById('selectedActivityTypeInput').value = activityType;
    
    const pillBtns = document.querySelectorAll('.activity-pill-btn');
    pillBtns.forEach(btn => {
        if (btn.getAttribute('data-activity') === activityType) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    toggleReasonContainer(activityType);
}

function toggleReasonContainer(activityType) {
    const lower = (activityType || '').toLowerCase();
    const reasonContainer = document.getElementById('waitingBlockedReasonContainer');
    if (!reasonContainer) return;

    if (lower.includes('waiting') || lower.includes('blocked') || lower.includes('technical')) {
        reasonContainer.style.display = 'block';
        // Auto filter reason options
        const select = document.getElementById('activityReasonSelect');
        const filterCat = lower.includes('waiting') ? 'waiting' : 'blocked';
        for (let i = 0; i < select.options.length; i++) {
            const opt = select.options[i];
            const cat = opt.getAttribute('data-category');
            if (!cat || cat === filterCat || cat === 'general') {
                opt.style.display = '';
            } else {
                opt.style.display = 'none';
            }
        }
    } else {
        reasonContainer.style.display = 'none';
    }
}

function filterTasksByProject() {
    const projId = document.getElementById('activityProjectSelect').value;
    const taskSelect = document.getElementById('activityTaskSelect');
    
    for (let i = 0; i < taskSelect.options.length; i++) {
        const opt = taskSelect.options[i];
        const optProj = opt.getAttribute('data-project');
        if (!projId || !optProj || optProj === projId) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    }
}

function handleTaskSelection() {
    const taskSelect = document.getElementById('activityTaskSelect');
    const selectedOpt = taskSelect.options[taskSelect.selectedIndex];
    if (selectedOpt && selectedOpt.value) {
        const pId = selectedOpt.getAttribute('data-project');
        if (pId) {
            document.getElementById('activityProjectSelect').value = pId;
        }
    }
}

// Submit Activity Change
function submitActivityChange(e) {
    e.preventDefault();
    const form = document.getElementById('activityChangeForm');
    const formData = new FormData(form);

    const submitBtn = document.getElementById('updateActivitySubmitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

    fetch(resolveApiUrl('/api/activities?action=change'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-1.5"></i>Update Activity Now';

        if (res.success) {
            const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
            toast.fire({ icon: 'success', title: 'Activity Updated!' });

            // Update live state
            currentActivityStartTimestamp = Math.floor(Date.now() / 1000);
            document.getElementById('activeActivityTitleDisplay').textContent = res.data.activity_type;

            loadWorkdayTimeline();
            setTimeout(() => location.reload(), 1200);
        } else {
            Swal.fire('Notice', res.message, 'warning');
        }
    })
    .catch(err => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-check-circle me-1.5"></i>Update Activity Now';
        Swal.fire('Error', 'Failed to update activity: ' + err.message, 'error');
    });
}

// Complete Task from Activity Card
function completeCurrentTask(taskId) {
    Swal.fire({
        title: 'Complete Task?',
        text: 'This will stop the activity timer and mark this task completed.',
        icon: 'question',
        input: 'textarea',
        inputPlaceholder: 'Enter brief work summary...',
        showCancelButton: true,
        confirmButtonText: 'Yes, Complete Task',
        confirmButtonColor: '#10b981'
    }).then(result => {
        if (result.isConfirmed) {
            const logText = result.value || 'Work completed.';
            fetch(resolveApiUrl('/api/time_tracker'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=stop&task_id=${taskId}&log_text=${encodeURIComponent(logText)}&csrf_token=${CSRF_TOKEN}`
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Task Completed!', res.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Notice', res.message, 'warning');
                }
            });
        }
    });
}

// Handle Shift Start / End
function handleWorkdayShift(action) {
    if (typeof handleClockInOut === 'function') {
        const act = (action === 'start' || action === 'clock_in') ? 'clock_in' : 'clock_out';
        return handleClockInOut(act);
    }
    const isStart = (action === 'start' || action === 'clock_in' || action === 'start_shift');
    const apiAction = isStart ? 'clock_in' : 'clock_out';
    const csrf = (typeof CSRF_TOKEN !== 'undefined' && CSRF_TOKEN) ? CSRF_TOKEN : '';

    const execute = () => {
        fetch(resolveApiUrl('/api/attendance'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=${apiAction}&csrf_token=${encodeURIComponent(csrf)}`
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'success',
                        title: isStart ? 'Shift Started!' : 'Shift Ended!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    alert(res.message);
                    location.reload();
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Shift Notice', res.message, 'warning');
                } else {
                    alert(res.message);
                }
            }
        })
        .catch(err => {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', 'Shift action failed: ' + err.message, 'error');
            } else {
                alert('Shift action failed: ' + err.message);
            }
        });
    };

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: isStart ? 'Start Office Shift?' : 'End Office Shift?',
            text: isStart ? 'Record current login time in IST and start your workday.' : 'Complete today\'s shift session and calculate your total worked hours.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: isStart ? 'Start Shift Now' : 'End Shift',
            confirmButtonColor: isStart ? '#4f46e5' : '#ef4444',
            cancelButtonColor: '#64748b'
        }).then(result => {
            if (result.isConfirmed) {
                execute();
            }
        });
    } else {
        if (confirm(isStart ? 'Start Office Shift?' : 'End Office Shift?')) {
            execute();
        }
    }
}

// Load Workday Chronological Daily Timeline (Section 8)
function loadWorkdayTimeline() {
    const container = document.getElementById('timelineContainer');
    if (!container) return;

    fetch(resolveApiUrl('/api/activities?action=timeline'))
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.timeline) {
                const items = res.data.timeline;
                if (items.length === 0) {
                    container.innerHTML = '<div class="text-center py-4 text-muted small">No activities logged yet today. Start your shift and choose an activity!</div>';
                    return;
                }

                let html = '<div class="activity-timeline-feed py-2">';
                items.forEach((item, index) => {
                    const isLast = (index === items.length - 1);
                    const isShiftStart = (item.type === 'shift_start');
                    const isShiftEnd = (item.type === 'shift_end');
                    const isActivity = (item.type === 'activity' && item.id);

                    let iconClass = item.icon || 'fas fa-circle';
                    let badgeClass = item.badge || 'bg-light text-dark';
                    let timeText = item.time_range || item.time_formatted;

                    html += `
                        <div class="timeline-entry position-relative d-flex gap-3 mb-3 ${isLast ? '' : 'timeline-has-line'}">
                            <div class="timeline-marker-box rounded-circle shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background: ${item.color}15; color: ${item.color}; border: 2px solid ${item.color}; z-index: 2;">
                                <i class="${iconClass} fs-7"></i>
                            </div>
                            <div class="timeline-content-card flex-grow-1 p-2.5 rounded-3 bg-white border shadow-xs hover-shadow" onclick="openActivityDetails(${item.id || 0}, '${item.type}')" style="cursor: pointer;">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                    <strong class="text-dark fs-7">${escapeHtml(item.title)}</strong>
                                    <div class="d-flex align-items-center gap-1.5">
                                        <span class="badge ${badgeClass} fs-9 rounded-pill px-2 py-0.5">${item.duration_formatted ? item.duration_formatted : (item.is_active ? 'Active' : '')}</span>
                                        ${isActivity ? `
                                            <button type="button" class="btn btn-outline-secondary btn-xs py-0.5 px-2 rounded-pill fs-9 text-muted border-secondary-subtle" onclick="event.stopPropagation(); openEditWorkdayActivityModal(${item.id})" title="Edit activity & shift timing">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>
                                        ` : ''}
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2 text-secondary fs-8 flex-wrap">
                                    <span><i class="far fa-clock me-1"></i>${escapeHtml(timeText)}</span>
                                    ${item.client_name ? `<span class="badge bg-info-subtle text-info border px-2 py-0.5 rounded-pill fs-9"><i class="fas fa-handshake me-1"></i>Client: ${escapeHtml(item.client_name)}</span>` : ''}
                                    ${item.reason ? `<span class="badge bg-warning-subtle text-dark border border-warning fs-9">${escapeHtml(item.reason)}</span>` : ''}
                                </div>
                                ${item.notes ? `<div class="small text-muted mt-1 fst-italic text-truncate">${escapeHtml(item.notes)}</div>` : ''}
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                container.innerHTML = html;

                // Update summary footer
                if (res.data.summary) {
                    const s = res.data.summary;
                    document.getElementById('timelineSummaryWorking').textContent = s.working_time_formatted;
                    document.getElementById('timelineSummaryMeetings').textContent = s.meeting_time_formatted;
                    document.getElementById('timelineSummaryWaiting').textContent = s.waiting_time_formatted;
                }
            } else {
                container.innerHTML = '<div class="text-center py-4 text-muted small">No timeline events recorded yet.</div>';
            }
        })
        .catch(err => {
            container.innerHTML = '<div class="text-center py-4 text-danger small">Error loading timeline.</div>';
        });
}

// Activity Details Inspector Modal
function openActivityDetails(activityId, itemType) {
    if (!activityId || itemType === 'shift_start' || itemType === 'shift_end') return;

    fetch(resolveApiUrl(`/api/activities?action=details&id=${activityId}`))
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                const a = res.data;
                const body = document.getElementById('activityDetailModalBody');
                body.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0">${escapeHtml(a.activity_type)}</h6>
                        <span class="badge bg-primary rounded-pill px-2.5 py-1">${escapeHtml(a.duration_formatted)}</span>
                    </div>
                    <div class="row g-2 mb-3 bg-light p-2.5 rounded-3 border">
                        <div class="col-6"><small class="text-muted fs-8">Started At:</small><div class="fw-bold text-dark fs-7">${escapeHtml(a.start_time_formatted)}</div></div>
                        <div class="col-6"><small class="text-muted fs-8">Ended At:</small><div class="fw-bold text-dark fs-7">${escapeHtml(a.end_time_formatted)}</div></div>
                    </div>
                    ${a.client_name ? `<div class="mb-2"><small class="text-muted fs-8">Client Name:</small><div class="fw-bold text-info fs-7"><i class="fas fa-handshake me-1"></i>${escapeHtml(a.client_name)}</div></div>` : ''}
                    ${a.project_name ? `<div class="mb-2"><small class="text-muted fs-8">Project:</small><div class="fw-bold text-dark fs-7">${escapeHtml(a.project_name)} (${escapeHtml(a.project_code || '')})</div></div>` : ''}
                    ${a.task_title ? `<div class="mb-2"><small class="text-muted fs-8">Task Title:</small><div class="fw-bold text-primary fs-7">${escapeHtml(a.task_title)}</div></div>` : ''}
                    ${a.reason ? `<div class="mb-2"><small class="text-muted fs-8">Reported Reason:</small><div class="badge bg-warning text-dark border border-warning fs-8">${escapeHtml(a.reason)}</div></div>` : ''}
                    ${a.notes ? `<div class="mb-2"><small class="text-muted fs-8">Work Notes:</small><div class="p-2 bg-white border rounded text-dark fs-8">${escapeHtml(a.notes)}</div></div>` : ''}
                    <div class="pt-3 border-top mt-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-semibold" onclick="bootstrap.Modal.getInstance(document.getElementById('activityDetailModal'))?.hide(); openEditWorkdayActivityModal(${a.id});">
                            <i class="fas fa-edit me-1"></i>Edit This Activity & Timing
                        </button>
                    </div>
                `;
                const modal = new bootstrap.Modal(document.getElementById('activityDetailModal'));
                modal.show();
            }
        });
}

let currentPendingMeetingObj = <?= $pendingMeetingAlert ? json_encode($pendingMeetingAlert, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) : 'null' ?>;
if (currentPendingMeetingObj) {
    currentPendingMeetingId = currentPendingMeetingObj.id;
}

function getWorkdayPendingMeetingId() {
    if (currentPendingMeetingId) return parseInt(currentPendingMeetingId);
    if (currentPendingMeetingObj && currentPendingMeetingObj.id) return parseInt(currentPendingMeetingObj.id);
    const banner = document.getElementById('workdayPendingMeetingBanner');
    if (banner && banner.getAttribute('data-meeting-id')) {
        const bid = parseInt(banner.getAttribute('data-meeting-id'));
        if (bid) return bid;
    }
    return null;
}

// Meeting Request Polling & Real-time Pop-up (Section 6)
function checkActiveMeetingRequest(isManualRefresh = false) {
    fetch(resolveApiUrl('/api/meetings?action=active_request'))
        .then(res => res.json())
        .then(res => {
            if (!res.success) return;

            // Handle pending request
            const m = res.data.pending_meeting || res.data.meeting;
            if (res.data.has_pending_request && m) {
                const isNew = (!currentPendingMeetingId || currentPendingMeetingId !== m.id);
                currentPendingMeetingObj = m;
                currentPendingMeetingId = m.id;

                // Show top banner
                updatePendingMeetingBanner(m);

                // Show modal popup if new or manual refresh
                if (isNew || isManualRefresh) {
                    showMeetingRequestModal(m);
                }
            } else {
                currentPendingMeetingObj = null;
                currentPendingMeetingId = null;
                const banner = document.getElementById('workdayPendingMeetingBanner');
                if (banner) {
                    banner.style.display = 'none';
                    banner.removeAttribute('data-meeting-id');
                }
            }

            // Update today's meetings list dynamically
            if (res.data.today_meetings) {
                renderMeetingsList(res.data.today_meetings);
            }
        })
        .catch(err => console.error('Meeting check error:', err));
}

function updatePendingMeetingBanner(m) {
    const banner = document.getElementById('workdayPendingMeetingBanner');
    if (!banner) return;

    banner.style.display = 'block';
    banner.setAttribute('data-meeting-id', m.id);
    const callerName = m.caller_name || 'Boss / Admin';
    const avatar = m.caller_avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(callerName)}&background=f59e0b&color=fff`;

    const avatarEl = document.getElementById('pendingBannerAvatar');
    if (avatarEl) avatarEl.src = avatar;

    const callerEl = document.getElementById('pendingBannerCaller');
    if (callerEl) callerEl.textContent = callerName;

    const titleEl = document.getElementById('pendingBannerTitle');
    if (titleEl) titleEl.textContent = m.title;

    const timeEl = document.getElementById('pendingBannerTime');
    if (timeEl) timeEl.textContent = m.start_time;

    const durEl = document.getElementById('pendingBannerDuration');
    if (durEl) durEl.textContent = m.duration;

    const clientBadge = document.getElementById('pendingBannerClientBadge');
    const clientText = document.getElementById('pendingBannerClientText');
    if (clientBadge) {
        if (m.client_name) {
            clientBadge.style.display = 'inline-block';
            if (clientText) {
                clientText.textContent = m.client_name;
            } else {
                clientBadge.innerHTML = `<i class="fas fa-handshake me-1"></i>Client: ${escapeHtml(m.client_name)}`;
            }
        } else {
            clientBadge.style.display = 'none';
        }
    }

    const linkAnchor = document.getElementById('pendingBannerLink');
    if (linkAnchor) {
        if (m.meeting_link) {
            linkAnchor.style.display = 'inline-block';
            linkAnchor.href = m.meeting_link;
            linkAnchor.innerHTML = `<i class="fas fa-link me-1"></i>${escapeHtml(m.meeting_link)}`;
        } else {
            linkAnchor.style.display = 'none';
        }
    }
}

function renderMeetingsList(meetings) {
    const container = document.getElementById('workdayMeetingsListContainer');
    const badge = document.getElementById('meetingsCountBadge');
    if (badge) badge.textContent = `${meetings.length} Scheduled`;
    if (!container) return;

    if (!meetings || meetings.length === 0) {
        container.innerHTML = `
            <div class="p-3.5 bg-light rounded-3 text-center border">
                <i class="fas fa-calendar-check text-muted fs-4 mb-2 d-block"></i>
                <div class="fw-semibold text-dark fs-7">No meetings scheduled for today</div>
                <small class="text-muted fs-8">You have no upcoming meeting requests. You can request a sync with your boss or schedule a client call anytime.</small>
            </div>
        `;
        return;
    }

    let html = '<div class="row g-3">';
    meetings.forEach(m => {
        const isPending = (m.status === 'pending');
        const isAccepted = (m.status === 'accepted');
        const isInProgress = (m.status === 'in_progress');
        const isRecipient = (parseInt(m.employee_id) === CURRENT_USER_ID);
        const isCaller = (parseInt(m.created_by) === CURRENT_USER_ID);

        let statusBadgeClass = 'bg-secondary';
        let statusLabel = m.status.charAt(0).toUpperCase() + m.status.slice(1);
        if (isPending) {
            statusBadgeClass = 'bg-warning text-dark';
            statusLabel = 'Pending Response';
        } else if (isAccepted) {
            statusBadgeClass = 'bg-purple text-white';
            statusLabel = 'Scheduled';
        } else if (isInProgress) {
            statusBadgeClass = 'bg-success text-white';
            statusLabel = 'In Meeting';
        } else if (m.status === 'completed') {
            statusBadgeClass = 'bg-info text-dark';
            statusLabel = 'Completed';
        }

        const otherPartyName = isCaller ? (m.employee_name || 'Team') : (m.caller_name || 'Boss');
        const avatarSrc = isCaller 
            ? (m.employee_avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(otherPartyName)}&background=4f46e5&color=fff`)
            : (m.caller_avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(otherPartyName)}&background=7c3aed&color=fff`);

        const mJsonStr = JSON.stringify(m).replace(/'/g, "&apos;");

        html += `
            <div class="col-12 col-md-6 col-xl-4">
                <div class="p-3 rounded-3 border bg-white shadow-sm h-100 d-flex flex-column justify-content-between position-relative">
                    <div>
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <span class="badge ${statusBadgeClass} px-2.5 py-1 rounded-pill fs-8 fw-semibold">${statusLabel}</span>
                            <span class="font-monospace fs-8 fw-bold text-primary"><i class="fas fa-clock me-1"></i>${m.start_time} (${m.duration}m)</span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 text-truncate" title="${escapeHtml(m.title)}">${escapeHtml(m.title)}</h6>
                        <div class="d-flex align-items-center gap-1.5 mb-2 flex-wrap">
                            ${m.meeting_type === 'client' ? '<span class="badge bg-info-subtle text-info border px-2 py-0.5 rounded-pill fs-9"><i class="fas fa-handshake me-1"></i>Client Call</span>' : ''}
                            ${m.client_name ? `<span class="badge bg-light text-dark border px-2 py-0.5 rounded-pill fs-9">Client: ${escapeHtml(m.client_name)}</span>` : ''}
                            ${m.project_name ? `<span class="badge bg-light text-secondary border px-2 py-0.5 rounded-pill fs-9">${escapeHtml(m.project_name)}</span>` : ''}
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <img src="${avatarSrc}" class="rounded-circle border" width="28" height="28" alt="${escapeHtml(otherPartyName)}">
                            <div class="text-truncate">
                                <small class="text-dark fw-bold d-block fs-8">${escapeHtml(otherPartyName)}</small>
                                <small class="text-muted fs-9">${isCaller ? 'Invited Attendee' : 'Host / Organizer'}</small>
                            </div>
                        </div>
                        ${m.meeting_link ? `<div class="mb-2"><a href="${m.meeting_link}" target="_blank" class="btn btn-outline-primary btn-sm py-1 px-2.5 rounded-pill w-100 text-truncate fs-8"><i class="fas fa-video me-1"></i>Join Video Call</a></div>` : ''}
                        ${m.description ? `<p class="text-muted fs-8 mb-2 text-truncate" title="${escapeHtml(m.description)}">${escapeHtml(m.description)}</p>` : ''}
                        ${m.response_notes ? `<div class="p-1.5 bg-light rounded text-secondary fs-9 mb-2 border"><strong>Note:</strong> ${escapeHtml(m.response_notes)}</div>` : ''}
                    </div>
                    <div class="d-flex align-items-center gap-1.5 pt-2 border-top mt-2 justify-content-between flex-wrap">
                        <button type="button" class="btn btn-light btn-sm text-primary fw-semibold px-2.5 py-1 rounded-pill border fs-8" onclick='openEditMeetingModal(${mJsonStr})'>
                            <i class="fas fa-pen me-1"></i>Edit Request
                        </button>
                        ${isPending && isRecipient ? `
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-success btn-sm px-2.5 py-1 rounded-pill fw-bold fs-8" onclick="respondToMeeting(${m.id}, 'accept')">Accept</button>
                                <button type="button" class="btn btn-outline-warning text-dark btn-sm px-2 py-1 rounded-pill fs-8" onclick="respondToMeeting(${m.id}, 'busy')">Busy</button>
                            </div>
                        ` : ''}
                        ${(isAccepted || isInProgress) ? `
                            ${isInProgress ? `
                                <button type="button" class="btn btn-danger btn-sm px-2.5 py-1 rounded-pill fw-bold fs-8" onclick="endCurrentMeeting(${m.id})">End Meeting</button>
                            ` : `
                                <button type="button" class="btn btn-purple btn-sm px-2.5 py-1 rounded-pill fw-bold text-white fs-8" onclick="startMeetingActivity(${m.id}, '${escapeJs(m.title)}')">Start Meeting</button>
                            `}
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

function showMeetingRequestModal(m) {
    document.getElementById('meetingCallerName').textContent = m.caller_name || 'Boss / Admin';
    document.getElementById('meetingCallerDesignation').textContent = m.caller_designation || 'Management';
    document.getElementById('meetingTopicText').textContent = m.title;
    document.getElementById('meetingTimeText').textContent = m.start_time;
    document.getElementById('meetingDurationText').textContent = `${m.duration} minutes`;

    const avatar = m.caller_avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(m.caller_name || 'Boss')}&background=4f46e5&color=fff`;
    document.getElementById('meetingCallerAvatar').src = avatar;

    if (m.meeting_link) {
        document.getElementById('meetingLinkWrap').style.display = 'block';
        const a = document.getElementById('meetingLinkAnchor');
        a.href = m.meeting_link;
        a.textContent = m.meeting_link;
    } else {
        document.getElementById('meetingLinkWrap').style.display = 'none';
    }

    const modal = new bootstrap.Modal(document.getElementById('meetingRequestAlertModal'));
    modal.show();
}

function editFromAlertModal() {
    bootstrap.Modal.getInstance(document.getElementById('meetingRequestAlertModal'))?.hide();
    if (currentPendingMeetingObj) {
        openEditMeetingModal(currentPendingMeetingObj);
    }
}

function editCurrentPendingMeeting() {
    if (currentPendingMeetingObj) {
        openEditMeetingModal(currentPendingMeetingObj);
        return;
    }
    const mid = getWorkdayPendingMeetingId();
    if (!mid) {
        Swal.fire('Notice', 'No meeting invitation found to edit.', 'info');
        return;
    }
    fetch(resolveApiUrl('/api/meetings?action=list'))
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                const found = res.data.find(m => parseInt(m.id) === mid);
                if (found) {
                    currentPendingMeetingObj = found;
                    openEditMeetingModal(found);
                } else {
                    Swal.fire('Error', 'Meeting details could not be found.', 'error');
                }
            }
        });
}

function openEditMeetingModal(m) {
    if (!m) return;
    document.getElementById('editMeetingId').value = m.id;
    document.getElementById('editMeetingTitle').value = m.title || '';
    document.getElementById('editMeetingType').value = m.meeting_type || 'internal';
    document.getElementById('editMeetingClientName').value = m.client_name || '';
    document.getElementById('editMeetingProjectId').value = m.project_id || '';
    document.getElementById('editMeetingDate').value = m.meeting_date || '';
    document.getElementById('editMeetingStartTime').value = m.start_time || '';
    document.getElementById('editMeetingDuration').value = m.duration || '30';
    document.getElementById('editMeetingLink').value = m.meeting_link || '';
    document.getElementById('editMeetingNotes').value = m.response_notes || '';
    document.getElementById('editMeetingStatus').value = m.status || 'pending';

    const modal = new bootstrap.Modal(document.getElementById('editMeetingModal'));
    modal.show();
}

function submitEditMeeting(e) {
    e.preventDefault();
    const form = document.getElementById('editMeetingForm');
    const formData = new FormData(form);
    formData.append('action', 'update');
    formData.append('csrf_token', CSRF_TOKEN);

    const btn = document.getElementById('submitEditMeetingBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

    fetch(resolveApiUrl('/api/meetings'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes & Update Meeting';

        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('editMeetingModal'))?.hide();
            Swal.fire({
                icon: 'success',
                title: 'Meeting Updated!',
                text: res.message,
                timer: 1800,
                showConfirmButton: false
            }).then(() => checkActiveMeetingRequest(true));
        } else {
            Swal.fire('Error', res.message, 'warning');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes & Update Meeting';
        Swal.fire('Error', err.message, 'error');
    });
}

function openScheduleMeetingModal() {
    const modal = new bootstrap.Modal(document.getElementById('scheduleMeetingModal'));
    modal.show();
}

function handleMeetingTypeChange(type) {
    const clientInput = document.getElementById('scheduleMeetingClientName');
    if (type === 'client') {
        clientInput.closest('.col-md-6').style.display = 'block';
    }
}

function submitScheduleMeeting(e) {
    e.preventDefault();
    const form = document.getElementById('scheduleMeetingForm');
    const formData = new FormData(form);
    formData.append('action', 'create');
    formData.append('csrf_token', CSRF_TOKEN);

    const btn = document.getElementById('submitScheduleMeetingBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Scheduling...';

    fetch(resolveApiUrl('/api/meetings'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Meeting Request';

        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('scheduleMeetingModal'))?.hide();
            form.reset();
            Swal.fire({
                icon: 'success',
                title: 'Meeting Scheduled!',
                text: res.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                checkActiveMeetingRequest(true);
                location.reload();
            });
        } else {
            Swal.fire('Notice', res.message, 'warning');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Meeting Request';
        Swal.fire('Error', err.message, 'error');
    });
}

function generateMeetLink(inputId) {
    const randomCode = 'meet-' + Math.random().toString(36).substring(2, 6) + '-' + Math.random().toString(36).substring(2, 6);
    document.getElementById(inputId).value = `https://meet.google.com/${randomCode}`;
}

function respondToMeeting(meetingId, responseType) {
    Swal.fire({
        title: (responseType === 'accept') ? 'Accept Meeting?' : (responseType === 'busy' ? "Mark as Busy?" : 'Decline Meeting?'),
        text: (responseType === 'accept') ? 'Your status will change to In Meeting and a meeting activity will be recorded.' : 'Enter any quick note for the organizer (optional):',
        input: 'text',
        inputPlaceholder: (responseType === 'accept') ? 'e.g. Joining now / Ready' : 'e.g. In middle of production deploy, can meet at 5:00 PM',
        showCancelButton: true,
        confirmButtonText: (responseType === 'accept') ? 'Confirm & Join' : 'Submit Response',
        confirmButtonColor: (responseType === 'accept') ? '#10b981' : ((responseType === 'busy') ? '#f59e0b' : '#ef4444')
    }).then(result => {
        if (result.isConfirmed) {
            const notes = result.value || '';
            fetch(resolveApiUrl('/api/meetings'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=respond&meeting_id=${meetingId}&response=${responseType}&response_notes=${encodeURIComponent(notes)}&csrf_token=${CSRF_TOKEN}`
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        icon: (responseType === 'accept') ? 'success' : 'info',
                        title: res.message,
                        timer: 1800,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Notice', res.message, 'warning');
                }
            });
        }
    });
}

function startMeetingActivity(meetingId, meetingTitle) {
    fetch(resolveApiUrl('/api/activities'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=change&activity_type=Meeting&notes=${encodeURIComponent('In Meeting: ' + meetingTitle)}&csrf_token=${CSRF_TOKEN}`
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            Swal.fire('Status Updated', 'You are now marked as In Meeting!', 'success').then(() => location.reload());
        } else {
            Swal.fire('Notice', res.message, 'warning');
        }
    });
}

function endCurrentMeeting(meetingId) {
    Swal.fire({
        title: 'Meeting Finished?',
        text: 'What are you working on now?',
        input: 'select',
        inputOptions: {
            'Working on Task': 'Working on Task',
            'Development': 'Development',
            'Designing': 'Designing',
            'Testing': 'Testing',
            'Client Communication': 'Client Communication',
            'Waiting for Client': 'Waiting for Client',
            'Client Review & Changes': 'Client Review & Changes',
            'Other': 'Other'
        },
        inputPlaceholder: 'Select next activity...',
        showCancelButton: true,
        confirmButtonText: 'End Meeting & Switch Activity'
    }).then(result => {
        if (result.isConfirmed) {
            const nextAct = result.value || 'Working on Task';
            fetch(resolveApiUrl('/api/meetings'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=end&meeting_id=${meetingId}&next_activity=${encodeURIComponent(nextAct)}&csrf_token=${CSRF_TOKEN}`
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Meeting Ended', 'Returned to work!', 'success').then(() => location.reload());
                } else {
                    Swal.fire('Notice', res.message, 'warning');
                }
            });
        }
    });
}

// Meeting Banner Response Handlers (Section 6)
function handleWorkdayBannerMeetingResponse(responseType) {
    const meetingId = getWorkdayPendingMeetingId();
    if (!meetingId) {
        Swal.fire('Notice', 'No active meeting invitation found.', 'info');
        return;
    }

    if (responseType === 'busy') {
        openMeetingBusyModal(meetingId);
        return;
    }

    if (responseType === 'decline') {
        Swal.fire({
            title: 'Decline Meeting Invitation?',
            text: 'Enter a reason for declining (optional):',
            input: 'text',
            inputPlaceholder: 'e.g. Schedule conflict, client deadline...',
            showCancelButton: true,
            confirmButtonText: 'Decline Meeting',
            confirmButtonColor: '#ef4444'
        }).then(result => {
            if (result.isConfirmed) {
                sendMeetingResponseApi(meetingId, 'decline', result.value || '');
            }
        });
        return;
    }

    // Accept & Join
    sendMeetingResponseApi(meetingId, 'accept', '');
}

function respondToMeetingModal(responseType) {
    const meetingId = getWorkdayPendingMeetingId();
    if (!meetingId) {
        Swal.fire('Notice', 'No active meeting invitation found.', 'info');
        return;
    }

    if (responseType === 'busy') {
        openMeetingBusyModal(meetingId);
        return;
    }

    const notes = document.getElementById('meetingResponseNotesInput')?.value?.trim() || '';
    sendMeetingResponseApi(meetingId, responseType, notes);
}

function sendMeetingResponseApi(meetingId, responseType, notes, busyReason = '', clientName = '', projectId = '', taskId = '') {
    const body = new URLSearchParams();
    body.append('action', 'respond');
    body.append('meeting_id', meetingId);
    body.append('response', responseType);
    body.append('response_status', responseType);
    body.append('response_notes', notes);
    if (busyReason) body.append('busy_reason', busyReason);
    if (clientName) body.append('client_name', clientName);
    if (projectId) body.append('project_id', projectId);
    if (taskId) body.append('task_id', taskId);
    body.append('csrf_token', CSRF_TOKEN);

    fetch(resolveApiUrl('/api/meetings'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
    })
    .then(res => res.json())
    .then(res => {
        bootstrap.Modal.getInstance(document.getElementById('meetingRequestAlertModal'))?.hide();
        bootstrap.Modal.getInstance(document.getElementById('meetingBusyModal'))?.hide();
        currentPendingMeetingId = null;

        if (res.success) {
            if (responseType === 'accept' && res.data && res.data.meeting_link) {
                window.open(res.data.meeting_link, '_blank');
            }
            Swal.fire({
                icon: (responseType === 'accept') ? 'success' : 'info',
                title: res.message || 'Response recorded!',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Notice', res.message, 'warning');
        }
    })
    .catch(err => {
        Swal.fire('Error', 'Failed to send meeting response: ' + err.message, 'error');
    });
}

function openMeetingBusyModal(meetingId = null) {
    const mid = meetingId || getWorkdayPendingMeetingId();
    if (!mid) {
        Swal.fire('Notice', 'No active meeting request found.', 'info');
        return;
    }
    document.getElementById('meetingBusyMeetingId').value = mid;

    const callerText = (currentPendingMeetingObj && currentPendingMeetingObj.caller_name) 
        ? currentPendingMeetingObj.caller_name 
        : (document.getElementById('pendingBannerCaller')?.textContent || 'Organizer');
    const titleText = (currentPendingMeetingObj && currentPendingMeetingObj.title) 
        ? currentPendingMeetingObj.title 
        : (document.getElementById('pendingBannerTitle')?.textContent || 'Meeting');
    
    document.getElementById('meetingBusyTargetText').textContent = `${titleText} (from ${callerText})`;
    
    if (currentPendingMeetingObj && currentPendingMeetingObj.client_name) {
        document.getElementById('meetingBusyClientName').value = currentPendingMeetingObj.client_name;
    }
    if (currentPendingMeetingObj && currentPendingMeetingObj.project_id) {
        document.getElementById('meetingBusyProjectId').value = currentPendingMeetingObj.project_id;
    }

    bootstrap.Modal.getInstance(document.getElementById('meetingRequestAlertModal'))?.hide();
    const busyModal = new bootstrap.Modal(document.getElementById('meetingBusyModal'));
    busyModal.show();
}

function submitMeetingBusyResponse(e) {
    e.preventDefault();
    const meetingId = document.getElementById('meetingBusyMeetingId').value;
    const category = document.getElementById('meetingBusyCategory').value;
    const clientName = document.getElementById('meetingBusyClientName').value.trim();
    const availTime = document.getElementById('meetingBusyAvailability').value;
    const notesInput = document.getElementById('meetingBusyNotes').value.trim();
    const projectId = document.getElementById('meetingBusyProjectId').value;
    const taskId = document.getElementById('meetingBusyTaskId').value;
    const updateWorkday = document.getElementById('meetingBusyUpdateWorkdayCheck').checked;

    let fullNotes = availTime ? `[Available: ${availTime}] ` : '';
    if (notesInput) fullNotes += notesInput;

    const btn = document.getElementById('submitMeetingBusyBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

    sendMeetingResponseApi(meetingId, 'busy', fullNotes, updateWorkday ? category : '', clientName, projectId, taskId);
}

// =========================================================================
// WORKDAY ACTIVITY EDITING ACCORDING TO SHIFT TIMING
// =========================================================================
function openEditWorkdayActivityModal(activityId) {
    if (!activityId) return;

    fetch(resolveApiUrl(`/api/activities?action=details&id=${activityId}`))
        .then(res => res.json())
        .then(res => {
            if (!res.success || !res.data) {
                Swal.fire('Error', res.message || 'Could not load activity.', 'error');
                return;
            }
            const a = res.data;
            document.getElementById('editActId').value = a.id;
            document.getElementById('editActType').value = a.activity_type || 'Development';
            document.getElementById('editActClientName').value = a.client_name || '';
            document.getElementById('editActProjectId').value = a.project_id || '';
            document.getElementById('editActTaskId').value = a.task_id || '';
            document.getElementById('editActDate').value = a.date || '<?= date("Y-m-d") ?>';
            document.getElementById('editActStartTime').value = a.start_time_time || '09:40';
            document.getElementById('editActEndTime').value = a.end_time_time || '';
            document.getElementById('editActReason').value = a.reason || '';
            document.getElementById('editActNotes').value = a.notes || '';

            calcEditDuration();

            const modal = new bootstrap.Modal(document.getElementById('editWorkdayActivityModal'));
            modal.show();
        })
        .catch(err => {
            Swal.fire('Error', 'Failed to load activity details: ' + err.message, 'error');
        });
}

function calcEditDuration() {
    const s = document.getElementById('editActStartTime')?.value;
    const e = document.getElementById('editActEndTime')?.value;
    const preview = document.getElementById('editActDurationPreview');
    if (!preview) return;

    if (!s || !e) {
        preview.textContent = 'Duration: In Progress';
        return;
    }
    const [sh, sm] = s.split(':').map(Number);
    const [eh, em] = e.split(':').map(Number);
    let diffMins = (eh * 60 + em) - (sh * 60 + sm);
    if (diffMins < 0) {
        preview.textContent = 'Invalid: End before Start';
        preview.className = 'badge bg-danger rounded-pill px-2.5 py-1 fs-9';
    } else {
        const h = Math.floor(diffMins / 60);
        const m = diffMins % 60;
        preview.textContent = `Duration: ${h}h ${m}m`;
        preview.className = 'badge bg-primary rounded-pill px-2.5 py-1 fs-9';
    }
}

function setEditShiftTime(startTime, endTime) {
    if (startTime) document.getElementById('editActStartTime').value = startTime;
    if (endTime) document.getElementById('editActEndTime').value = endTime;
    calcEditDuration();
}

function setEditEndTimeNow() {
    const now = new Date();
    const h = String(now.getHours()).padStart(2, '0');
    const m = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('editActEndTime').value = `${h}:${m}`;
    calcEditDuration();
}

function submitEditWorkdayActivity(e) {
    e.preventDefault();
    const form = document.getElementById('editWorkdayActivityForm');
    const formData = new FormData(form);
    formData.append('action', 'update');
    formData.append('csrf_token', CSRF_TOKEN);

    const btn = document.getElementById('submitEditActivityBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

    fetch(resolveApiUrl('/api/activities'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';

        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('editWorkdayActivityModal'))?.hide();
            Swal.fire({
                icon: 'success',
                title: 'Activity Updated!',
                text: res.message,
                timer: 1600,
                showConfirmButton: false
            }).then(() => {
                loadWorkdayTimeline();
                location.reload();
            });
        } else {
            Swal.fire('Notice', res.message, 'warning');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
        Swal.fire('Error', err.message, 'error');
    });
}

function deleteCurrentWorkdayActivity() {
    const actId = document.getElementById('editActId')?.value;
    if (!actId) return;

    Swal.fire({
        title: 'Delete this activity entry?',
        text: 'This will remove this time block from your daily workday record.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Yes, Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(resolveApiUrl('/api/activities'), {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `action=delete&id=${actId}&csrf_token=${CSRF_TOKEN}`
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editWorkdayActivityModal'))?.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        loadWorkdayTimeline();
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            });
        }
    });
}

// =========================================================================
// MANUAL LOG PAST ACTIVITY / CLIENT WAITING ACCORDING TO SHIFT TIMING
// =========================================================================
function openLogPastActivityModal() {
    calcPastLogDuration();
    const modal = new bootstrap.Modal(document.getElementById('logPastActivityModal'));
    modal.show();
}

function calcPastLogDuration() {
    const s = document.getElementById('logPastStartTime')?.value;
    const e = document.getElementById('logPastEndTime')?.value;
    const preview = document.getElementById('logPastDurationPreview');
    if (!preview) return;

    if (!s || !e) {
        preview.textContent = 'Duration: --';
        return;
    }
    const [sh, sm] = s.split(':').map(Number);
    const [eh, em] = e.split(':').map(Number);
    let diffMins = (eh * 60 + em) - (sh * 60 + sm);
    if (diffMins <= 0) {
        preview.textContent = 'Invalid: End must be after Start';
        preview.className = 'badge bg-danger rounded-pill px-2.5 py-1 fs-9';
    } else {
        const h = Math.floor(diffMins / 60);
        const m = diffMins % 60;
        preview.textContent = `Duration: ${h}h ${m}m`;
        preview.className = 'badge bg-primary rounded-pill px-2.5 py-1 fs-9';
    }
}

function setPastLogTimes(start, end) {
    document.getElementById('logPastStartTime').value = start;
    document.getElementById('logPastEndTime').value = end;
    calcPastLogDuration();
}

function setPastLogLastMinutes(mins) {
    const now = new Date();
    const endStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
    const startObj = new Date(now.getTime() - mins * 60000);
    const startStr = `${String(startObj.getHours()).padStart(2, '0')}:${String(startObj.getMinutes()).padStart(2, '0')}`;
    setPastLogTimes(startStr, endStr);
}

function submitLogPastActivity(e) {
    e.preventDefault();
    const form = document.getElementById('logPastActivityForm');
    const formData = new FormData(form);
    formData.append('action', 'manual_log');
    formData.append('csrf_token', CSRF_TOKEN);

    const btn = document.getElementById('submitLogPastActivityBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Adding...';

    fetch(resolveApiUrl('/api/activities'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-plus me-1"></i>Add to Workday';

        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('logPastActivityModal'))?.hide();
            form.reset();
            Swal.fire({
                icon: 'success',
                title: 'Activity Logged!',
                text: res.message,
                timer: 1800,
                showConfirmButton: false
            }).then(() => {
                loadWorkdayTimeline();
                location.reload();
            });
        } else {
            Swal.fire('Notice', res.message, 'warning');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-plus me-1"></i>Add to Workday';
        Swal.fire('Error', err.message, 'error');
    });
}

// Activity Update Reminder (Section 9)
function checkActivityReminderServer() {
    fetch(resolveApiUrl('/api/activities?action=current'))
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.needs_reminder && !activeReminderShown) {
                activeReminderShown = true;
                const modal = new bootstrap.Modal(document.getElementById('activityReminderModal'));
                modal.show();
            }
        });
}

function acknowledgeReminder() {
    fetch(resolveApiUrl('/api/activities'), {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=ack_reminder&csrf_token=${CSRF_TOKEN}`
    })
    .then(res => res.json())
    .then(res => {
        bootstrap.Modal.getInstance(document.getElementById('activityReminderModal'))?.hide();
        activeReminderShown = false;
        const toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
        toast.fire({ icon: 'success', title: 'Confirmed! Activity timer reset.' });
    });
}

function focusActivitySelector() {
    activeReminderShown = false;
    document.getElementById('activityChangeForm')?.scrollIntoView({ behavior: 'smooth' });
}

// Daily Work Summary Report Modal (Section 16)
function openDailyReportModal() {
    const modal = new bootstrap.Modal(document.getElementById('dailyWorkReportModal'));
    modal.show();

    fetch(resolveApiUrl('/api/work_summary?action=daily'))
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                const d = res.data;
                const r = d.report;
                const body = document.getElementById('dailyReportModalBody');

                let projListHtml = '';
                if (r.projects && r.projects.length > 0) {
                    projListHtml = r.projects.map(p => `<li>${escapeHtml(p)}</li>`).join('');
                } else {
                    projListHtml = '<li class="text-muted">General tasks</li>';
                }

                let issuesHtml = '';
                if (r.issues && r.issues.length > 0) {
                    issuesHtml = r.issues.map(i => `<span class="badge bg-warning-subtle text-dark border border-warning me-1 mb-1">${escapeHtml(i)}</span>`).join('');
                } else {
                    issuesHtml = '<span class="text-muted small">None reported today</span>';
                }

                body.innerHTML = `
                    <div class="border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-0">${escapeHtml(d.employee.name)}</h5>
                            <small class="text-muted">${escapeHtml(d.employee.designation || 'Staff')} &bull; Date: <strong>${escapeHtml(d.date_formatted)}</strong></small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary px-3 py-1.5 rounded-pill fs-8">SHIFT: ${escapeHtml(d.shift_timing)}</span>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border text-center">
                                <small class="text-muted fs-8 d-block">TASKS COMPLETED</small>
                                <strong class="fs-4 text-success">${r.tasks_completed}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border text-center">
                                <small class="text-muted fs-8 d-block">IN PROGRESS</small>
                                <strong class="fs-4 text-primary">${r.tasks_in_progress}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border text-center">
                                <small class="text-muted fs-8 d-block">WORKING TIME</small>
                                <strong class="fs-5 text-dark font-monospace">${r.working_time_formatted}</strong>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="p-2.5 bg-light rounded-3 border text-center">
                                <small class="text-muted fs-8 d-block">MEETING TIME</small>
                                <strong class="fs-5 text-purple font-monospace">${r.meeting_time_formatted}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-white border rounded-3 h-100">
                                <strong class="text-dark fs-7 d-block mb-2"><i class="fas fa-folder text-primary me-1"></i>Projects Worked On:</strong>
                                <ul class="mb-0 ps-3 small text-secondary">
                                    ${projListHtml}
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-white border rounded-3 h-100">
                                <strong class="text-dark fs-7 d-block mb-2"><i class="fas fa-exclamation-triangle text-warning me-1"></i>Reported Blockers / Issues:</strong>
                                <div>${issuesHtml}</div>
                                <div class="mt-2 text-muted fs-8">
                                    Waiting/Blocked Time: <strong class="text-dark font-monospace">${r.waiting_time_formatted}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
</script>
