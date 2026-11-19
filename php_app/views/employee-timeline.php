<?php
/**
 * Boss Employee Detail & Activity Timeline Page - WorkFlow Pro
 * Provides full employee activity breakdown, attendance, time distribution, tasks, meetings, blockers, and weekly performance charts.
 */

$pageTitle = "Employee Activity & Timeline";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();
ensureShiftAndActivitySchema($db);

$targetEmployeeId = (int)($_GET['id'] ?? ($_GET['employee_id'] ?? $user['id']));

// If not admin/manager and trying to view another employee, restrict to self
if ($targetEmployeeId !== (int)$user['id'] && !hasRole(['admin', 'manager', 'product_manager'])) {
    $targetEmployeeId = (int)$user['id'];
}

$selectedDate = sanitize($_GET['date'] ?? date('Y-m-d'));

// Fetch Employee Profile
$empStmt = $db->prepare("
    SELECT u.*, d.name as department_name, r.display_name as role_title, ep.skills, ep.joining_date
    FROM users u
    JOIN departments d ON u.department_id = d.id
    JOIN roles r ON u.role_id = r.id
    LEFT JOIN employee_profiles ep ON u.id = ep.user_id
    WHERE u.id = ?
");
$empStmt->execute([$targetEmployeeId]);
$employee = $empStmt->fetch();

if (!$employee) {
    echo "<div class='main-wrapper p-5 text-center'><h4>Employee not found</h4><a href='" . BASE_URL . "/live-status' class='btn btn-primary mt-3'>Back to Live Team Status</a></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Fetch Status Details & Daily Durations
$statusDetails = getEmployeeStatusDetails($db, $targetEmployeeId);
$dailySummary = calculateEmployeeDailyDurations($db, $targetEmployeeId, $selectedDate);
$settings = getShiftSettings($db);
$currentAct = $statusDetails['current_activity'];
$attendance = $statusDetails['attendance'];

// Completed & In-Progress Tasks
$tCompletedStmt = $db->prepare("SELECT * FROM tasks WHERE assigned_to = ? AND status = 'completed' ORDER BY updated_at DESC LIMIT 8");
$tCompletedStmt->execute([$targetEmployeeId]);
$completedTasks = $tCompletedStmt->fetchAll();

$tPendingStmt = $db->prepare("SELECT * FROM tasks WHERE assigned_to = ? AND status != 'completed' ORDER BY deadline ASC LIMIT 8");
$tPendingStmt->execute([$targetEmployeeId]);
$pendingTasks = $tPendingStmt->fetchAll();

// Meetings for Date
$meetStmt = $db->prepare("
    SELECT m.*, u.name as caller_name 
    FROM meetings m
    JOIN users u ON m.created_by = u.id
    WHERE (m.employee_id = ? OR m.created_by = ?) AND m.meeting_date = ?
    ORDER BY m.start_time ASC
");
$meetStmt->execute([$targetEmployeeId, $targetEmployeeId, $selectedDate]);
$meetingsList = $meetStmt->fetchAll();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-3 p-md-4">
        <!-- Top Back Action & Navigation -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= BASE_URL ?>/live-status" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                <i class="fas fa-arrow-left me-1"></i>Back to Live Team Status
            </a>
            
            <!-- Date Filter Picker -->
            <form method="GET" action="<?= BASE_URL ?>/employee-timeline" class="d-flex align-items-center gap-2">
                <input type="hidden" name="id" value="<?= $targetEmployeeId ?>">
                <label class="form-label text-muted small mb-0 d-none d-sm-inline">Select Date:</label>
                <input type="date" name="date" class="form-control form-control-sm rounded-pill px-3" value="<?= $selectedDate ?>" onchange="this.form.submit()">
            </form>
        </div>

        <!-- EMPLOYEE HERO PROFILE CARD -->
        <div class="glass-card mb-4 p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(248,250,252,0.98) 100%);">
            <div class="row g-3 align-items-center">
                <!-- Avatar & Identity -->
                <div class="col-12 col-md-6 d-flex align-items-center gap-3">
                    <img src="<?= $employee['avatar'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($employee['name']) . '&background=4f46e5&color=fff&size=128' ?>" 
                         alt="<?= sanitize($employee['name']) ?>" class="rounded-circle border shadow-sm" width="68" height="68" style="object-fit: cover;">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h4 class="fw-bold text-dark mb-0 font-heading"><?= sanitize($employee['name']) ?></h4>
                            <span class="badge <?= $statusDetails['status_badge_class'] ?> rounded-pill px-2.5 py-1 fs-8 fw-semibold">
                                <?= $statusDetails['status_emoji'] ?> <?= $statusDetails['status_display'] ?>
                            </span>
                        </div>
                        <div class="text-secondary small">
                            <span class="fw-medium text-dark"><?= sanitize($employee['designation']) ?></span> &bull; 
                            <i class="fas fa-building text-primary me-1"></i><?= sanitize($employee['department_name']) ?> &bull;
                            <span class="font-monospace text-muted"><?= sanitize($employee['employee_code'] ?? ('EMP-' . str_pad($employee['id'], 3, '0', STR_PAD_LEFT))) ?></span>
                        </div>
                        <div class="text-muted fs-8 mt-1">
                            <i class="fas fa-envelope me-1"></i><?= sanitize($employee['email']) ?>
                            <?php if (!empty($employee['phone'])): ?>
                                &bull; <i class="fas fa-phone me-1"></i><?= sanitize($employee['phone']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Action Buttons -->
                <div class="col-12 col-md-6 text-md-end d-flex gap-2 justify-content-md-end flex-wrap">
                    <?php if (hasRole(['admin', 'manager'])): ?>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 fw-bold" onclick="callThisEmployee(<?= $employee['id'] ?>, '<?= addslashes($employee['name']) ?>')">
                            <i class="fas fa-video me-1"></i>Call to Meeting
                        </button>
                    <?php endif; ?>
                    <a href="<?= BASE_URL ?>/chat" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fas fa-comment me-1"></i>Direct Message
                    </a>
                </div>
            </div>
        </div>

        <!-- 5 CATEGORICAL TIME DISTRIBUTION TILES (Section 7 & 17) -->
        <div class="row g-3 mb-4">
            <!-- 1. WORKING TIME -->
            <div class="col-6 col-md col-xl">
                <div class="stat-card-luxury" style="--card-accent: #10b981;">
                    <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-success font-monospace"><?= $dailySummary['working_time_formatted'] ?></div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Working Time</small>
                    </div>
                </div>
            </div>

            <!-- 2. MEETING TIME -->
            <div class="col-6 col-md col-xl">
                <div class="stat-card-luxury" style="--card-accent: #8b5cf6;">
                    <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                        <i class="fas fa-video"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-purple font-monospace"><?= $dailySummary['meeting_time_formatted'] ?></div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Meeting Time</small>
                    </div>
                </div>
            </div>

            <!-- 3. LUNCH TIME -->
            <div class="col-6 col-md col-xl">
                <div class="stat-card-luxury" style="--card-accent: #f97316;">
                    <div class="stat-card-icon" style="background: rgba(249, 115, 22, 0.12); color: #f97316;">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-warning font-monospace"><?= $dailySummary['lunch_time_formatted'] ?></div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Lunch Break</small>
                    </div>
                </div>
            </div>

            <!-- 4. WAITING TIME -->
            <div class="col-6 col-md col-xl">
                <div class="stat-card-luxury" style="--card-accent: #eab308;">
                    <div class="stat-card-icon" style="background: rgba(234, 179, 8, 0.12); color: #eab308;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-dark font-monospace"><?= $dailySummary['waiting_time_formatted'] ?></div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Waiting Time</small>
                    </div>
                </div>
            </div>

            <!-- 5. BLOCKED TIME -->
            <div class="col-6 col-md col-xl">
                <div class="stat-card-luxury" style="--card-accent: #ef4444;">
                    <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <div class="fs-5 fw-bold text-danger font-monospace"><?= $dailySummary['blocked_time_formatted'] ?></div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Blocked Time</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- ========================================================= -->
            <!-- SECTION 1: CHRONOLOGICAL ACTIVITY TIMELINE (Section 8 & 17) -->
            <!-- ========================================================= -->
            <div class="col-lg-7">
                <div class="glass-card p-4 border-0 shadow-sm h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-0 font-heading">
                                <i class="fas fa-stream text-primary me-2"></i>Daily Activity Timeline
                            </h5>
                            <small class="text-secondary fs-8">Activities & shift milestones for <strong><?= formatISTDate($selectedDate) ?></strong></small>
                        </div>
                        <span class="badge bg-light text-secondary border px-2.5 py-1 fs-8">
                            Total: <?= $dailySummary['activities_count'] ?> Activities
                        </span>
                    </div>

                    <!-- Timeline Content Area -->
                    <div id="employeeDetailTimelineArea" class="pe-1" style="max-height: 520px; overflow-y: auto;">
                        <div class="text-center py-4 text-muted small">
                            <div class="spinner-border spinner-border-sm text-primary me-1"></div>
                            Loading employee timeline...
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================= -->
            <!-- SECTION 2: ATTENDANCE & CURRENT STATUS SUMMARY            -->
            <!-- ========================================================= -->
            <div class="col-lg-5">
                <!-- Today's Attendance Details -->
                <div class="glass-card p-4 border-0 shadow-sm mb-4">
                    <h5 class="fw-bold text-dark mb-3 font-heading">
                        <i class="fas fa-user-clock text-info me-2"></i>Attendance & Shift Records
                    </h5>
                    
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted fs-8 d-block">LOGIN (CLOCK IN)</small>
                                <strong class="text-dark fs-7"><?= $statusDetails['clock_in_time'] ?: '--' ?></strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted fs-8 d-block">LOGOUT (CLOCK OUT)</small>
                                <strong class="text-dark fs-7"><?= $statusDetails['clock_out_time'] ?: ($statusDetails['is_shift_active'] ? 'Shift Running' : '--') ?></strong>
                            </div>
                            <div class="col-6 mt-2 pt-2 border-top">
                                <small class="text-muted fs-8 d-block">PUNCTUALITY</small>
                                <span class="<?= $statusDetails['is_late'] ? 'badge bg-warning text-dark' : 'badge bg-success text-white' ?> px-2 py-0.5 fs-8">
                                    <?= $statusDetails['is_late'] ? "Late by {$statusDetails['late_minutes']}m" : 'On Time' ?>
                                </span>
                            </div>
                            <div class="col-6 mt-2 pt-2 border-top">
                                <small class="text-muted fs-8 d-block">CONFIGURED SHIFT</small>
                                <span class="text-dark fw-semibold fs-8"><?= $settings['start_time'] ?> &ndash; <?= $settings['end_time'] ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Issues / Blockers Reported -->
                    <strong class="text-dark fs-7 d-block mb-2">
                        <i class="fas fa-triangle-exclamation text-warning me-1"></i>Reported Blockers / Issues:
                    </strong>
                    <?php if (!empty($dailySummary['issues'])): ?>
                        <div class="d-flex flex-column gap-1.5 mb-2">
                            <?php foreach ($dailySummary['issues'] as $issue): ?>
                                <div class="p-2 bg-warning-subtle text-dark border border-warning rounded fs-8">
                                    <i class="fas fa-exclamation-circle me-1 text-warning"></i><?= sanitize($issue) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="p-2 bg-light rounded text-muted fs-8 mb-2">No active blockers reported for this date.</div>
                    <?php endif; ?>
                </div>

                <!-- Projects Worked On -->
                <div class="glass-card p-4 border-0 shadow-sm">
                    <h5 class="fw-bold text-dark mb-3 font-heading">
                        <i class="fas fa-folder text-warning me-2"></i>Projects Worked On
                    </h5>
                    <?php if (!empty($dailySummary['projects'])): ?>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($dailySummary['projects'] as $proj): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1.5 fs-8 rounded-pill">
                                    <i class="fas fa-diagram-project me-1"></i><?= sanitize($proj) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small mb-0">No specific projects linked to today's activities.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- SECTION 3: WEEKLY REPORT & PRODUCTIVITY CHART (Section 18) -->
        <!-- ========================================================= -->
        <div class="glass-card p-4 border-0 shadow-sm mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-0 font-heading">
                        <i class="fas fa-chart-line text-success me-2"></i>Weekly Performance & Activity Report
                    </h5>
                    <small class="text-secondary fs-8">7-Day activity trends, working hours vs. meetings, and punctuality summary</small>
                </div>
                <div class="d-flex gap-3 text-secondary fs-8">
                    <span><i class="fas fa-square text-success me-1"></i>Working Hours</span>
                    <span><i class="fas fa-square text-purple me-1"></i>Meetings</span>
                    <span><i class="fas fa-square text-danger me-1"></i>Blocked</span>
                </div>
            </div>

            <!-- Weekly Summary Metric Pills -->
            <div class="row g-2.5 mb-4" id="weeklyMetricsRow">
                <div class="col-6 col-md-3">
                    <div class="p-2.5 bg-light rounded-3 border text-center">
                        <small class="text-muted fs-8 d-block">WEEK WORKING TIME</small>
                        <strong class="fs-5 text-dark font-monospace" id="weekTotalWorkingHours">--</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2.5 bg-light rounded-3 border text-center">
                        <small class="text-muted fs-8 d-block">WEEK MEETINGS</small>
                        <strong class="fs-5 text-purple font-monospace" id="weekTotalMeetingHours">--</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2.5 bg-light rounded-3 border text-center">
                        <small class="text-muted fs-8 d-block">TASKS COMPLETED</small>
                        <strong class="fs-5 text-success" id="weekTasksCompletedVal">--</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="p-2.5 bg-light rounded-3 border text-center">
                        <small class="text-muted fs-8 d-block">LATE ARRIVALS</small>
                        <strong class="fs-5 text-warning" id="weekLateArrivalsVal">--</strong>
                    </div>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="weeklyActivityChart" height="260"></canvas>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- SECTION 4: TASKS & MEETINGS TABS                          -->
        <!-- ========================================================= -->
        <div class="glass-card p-4 border-0 shadow-sm">
            <ul class="nav nav-pills mb-3" id="taskMeetingsTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active rounded-pill px-3 py-1.5 fs-7 fw-semibold" id="tasks-tab" data-bs-toggle="pill" data-bs-target="#tab-tasks" type="button">
                        <i class="fas fa-tasks me-1"></i>Tasks Overview (<?= count($completedTasks) + count($pendingTasks) ?>)
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill px-3 py-1.5 fs-7 fw-semibold" id="meetings-tab" data-bs-toggle="pill" data-bs-target="#tab-meetings" type="button">
                        <i class="fas fa-video me-1"></i>Meetings Today (<?= count($meetingsList) ?>)
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="tabContent">
                <!-- Tab 1: Tasks -->
                <div class="tab-pane fade show active" id="tab-tasks">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong class="text-dark fs-7 d-block mb-2"><i class="fas fa-check-circle text-success me-1"></i>Completed Tasks:</strong>
                            <?php if ($completedTasks): ?>
                                <div class="list-group list-group-flush border rounded-3 bg-white">
                                    <?php foreach ($completedTasks as $t): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center p-2.5">
                                            <div>
                                                <strong class="text-dark fs-8 d-block"><?= sanitize($t['title']) ?></strong>
                                                <small class="text-muted fs-9">Completed: <?= formatISTDateTime($t['updated_at']) ?></small>
                                            </div>
                                            <span class="badge bg-success-subtle text-success border border-success fs-9">Done</span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small">No completed tasks yet.</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <strong class="text-dark fs-7 d-block mb-2"><i class="fas fa-hourglass-start text-warning me-1"></i>Pending Tasks:</strong>
                            <?php if ($pendingTasks): ?>
                                <div class="list-group list-group-flush border rounded-3 bg-white">
                                    <?php foreach ($pendingTasks as $t): ?>
                                        <div class="list-group-item d-flex justify-content-between align-items-center p-2.5">
                                            <div>
                                                <strong class="text-dark fs-8 d-block"><?= sanitize($t['title']) ?></strong>
                                                <small class="text-muted fs-9">Deadline: <?= formatISTDateTime($t['deadline']) ?></small>
                                            </div>
                                            <span class="badge bg-warning-subtle text-warning border border-warning fs-9"><?= ucfirst($t['status']) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small">No pending tasks for this employee.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Meetings -->
                <div class="tab-pane fade" id="tab-meetings">
                    <?php if ($meetingsList): ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Topic</th>
                                        <th>Time</th>
                                        <th>Duration</th>
                                        <th>Caller</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($meetingsList as $m): ?>
                                        <tr>
                                            <td class="fw-bold text-dark"><?= sanitize($m['title']) ?></td>
                                            <td class="font-monospace fs-8"><?= sanitize($m['start_time']) ?></td>
                                            <td class="fs-8"><?= $m['duration'] ?> mins</td>
                                            <td class="small"><?= sanitize($m['caller_name']) ?></td>
                                            <td>
                                                <span class="badge <?= ($m['status'] === 'accepted') ? 'bg-success text-white' : 'bg-secondary text-white' ?> fs-9 rounded-pill">
                                                    <?= ucfirst($m['status']) ?>
                                                </span>
                                            </td>
                                            <td class="small text-muted"><?= sanitize($m['response_notes'] ?: 'None') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small py-3 mb-0">No meetings recorded for this employee on <?= formatISTDate($selectedDate) ?>.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- Chart.js & Detail Page JavaScript -->
<script>
const targetEmployeeId = <?= $targetEmployeeId ?>;
const targetDateStr = '<?= $selectedDate ?>';
let weeklyChartInstance = null;

document.addEventListener('DOMContentLoaded', () => {
    loadEmployeeTimelineDetails();
    loadWeeklyReport();
});

function loadEmployeeTimelineDetails() {
    const area = document.getElementById('employeeDetailTimelineArea');
    fetch(resolveApiUrl(`/api/activities?action=timeline&employee_id=${targetEmployeeId}&date=${targetDateStr}`))
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data.timeline) {
                const items = res.data.timeline;
                if (items.length === 0) {
                    area.innerHTML = '<div class="text-center py-4 text-muted small">No activities logged for this date.</div>';
                    return;
                }

                let html = '<div class="activity-timeline-feed py-2">';
                items.forEach((item, index) => {
                    const isLast = (index === items.length - 1);
                    html += `
                        <div class="timeline-entry position-relative d-flex gap-3 mb-3 ${isLast ? '' : 'timeline-has-line'}">
                            <div class="timeline-marker-box rounded-circle shadow-xs d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; background: ${item.color}15; color: ${item.color}; border: 2px solid ${item.color}; z-index: 2;">
                                <i class="${item.icon} fs-8"></i>
                            </div>
                            <div class="timeline-content-card flex-grow-1 p-2.5 rounded-3 bg-white border shadow-xs">
                                <div class="d-flex justify-content-between align-items-start gap-2 mb-1">
                                    <strong class="text-dark fs-7">${escapeHtml(item.title)}</strong>
                                    <span class="badge ${item.badge} fs-9 rounded-pill px-2 py-0.5">${item.duration_formatted ? item.duration_formatted : (item.is_active ? 'Active' : '')}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 text-secondary fs-8">
                                    <span><i class="far fa-clock me-1"></i>${escapeHtml(item.time_range || item.time_formatted)}</span>
                                    ${item.reason ? `<span class="badge bg-warning-subtle text-dark border border-warning fs-9">${escapeHtml(item.reason)}</span>` : ''}
                                </div>
                                ${item.notes ? `<div class="small text-muted mt-1 fst-italic">${escapeHtml(item.notes)}</div>` : ''}
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                area.innerHTML = html;
            } else {
                area.innerHTML = '<div class="text-center py-4 text-muted small">No activities logged.</div>';
            }
        });
}

function loadWeeklyReport() {
    fetch(resolveApiUrl(`/api/work_summary?action=weekly&employee_id=${targetEmployeeId}`))
        .then(res => res.json())
        .then(res => {
            if (res.success && res.data) {
                const m = res.data.metrics;
                const c = res.data.chart;

                document.getElementById('weekTotalWorkingHours').textContent = m.total_working_formatted;
                document.getElementById('weekTotalMeetingHours').textContent = m.total_meeting_formatted;
                document.getElementById('weekTasksCompletedVal').textContent = m.total_tasks_completed;
                document.getElementById('weekLateArrivalsVal').textContent = `${m.late_arrivals} Days`;

                renderWeeklyChart(c);
            }
        });
}

function renderWeeklyChart(c) {
    const ctx = document.getElementById('weeklyActivityChart');
    if (!ctx) return;

    if (weeklyChartInstance) {
        weeklyChartInstance.destroy();
    }

    weeklyChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: c.labels,
            datasets: [
                {
                    label: 'Working Hours',
                    data: c.working_hours,
                    backgroundColor: '#10b981',
                    borderRadius: 6
                },
                {
                    label: 'Meeting Hours',
                    data: c.meeting_hours,
                    backgroundColor: '#8b5cf6',
                    borderRadius: 6
                },
                {
                    label: 'Blocked Hours',
                    data: c.blocked_hours,
                    backgroundColor: '#ef4444',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, title: { display: true, text: 'Hours' } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

function callThisEmployee(id, name) {
    window.location.href = `${BASE_URL}/live-status?call_id=${encodeURIComponent(id)}&call_name=${encodeURIComponent(name || 'Employee')}`;
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
</script>
