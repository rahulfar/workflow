<?php
$pageTitle = "Attendance";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();
autoCloseOverdueShifts($db);
ensureAttendanceColumns($db);
ensureAuthorizedIpTable($db);
$isClientIpAuth = isClientIpAuthorized($db);
$clientNetworkName = getIpNetworkName($db);

// Month filter: defaults to current month (e.g. '2026-10')
$selectedMonth = !empty($_GET['month']) ? sanitize($_GET['month']) : (!empty($_GET['from_date']) ? substr(sanitize($_GET['from_date']), 0, 7) : date('Y-m'));

if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
    $selectedMonth = date('Y-m');
}

// Complete 1 Month: 1st of month to 1st of next month (e.g., 1 Oct to 1 Nov)
$fromDate = $selectedMonth . '-01';
$toDate = date('Y-m-01', strtotime($fromDate . ' +1 month'));

// Search Employee Name
$rawEmp = $_GET['search_employee'] ?? ($_GET['search'] ?? '');
$employeeSearch = trim(sanitize((string)$rawEmp));

// Clean employee search (strip any trailing designation or employee code in parentheses or dashes)
$cleanEmployeeSearch = '';
$codeSearch = '';
if (!empty($employeeSearch)) {
    if (preg_match('/^([^\(]+)\s*\((.*?)\)$/', $employeeSearch, $m)) {
        $cleanEmployeeSearch = trim($m[1]);
        $codeSearch = trim($m[2]);
    } else {
        $cleanEmployeeSearch = $employeeSearch;
    }
}

// Active employees list for Employee datalist suggestions
$usersStmt = $db->query("SELECT id, name, employee_code, designation, department_id FROM users WHERE status = 'active' ORDER BY name ASC");
$allUsers = $usersStmt->fetchAll();

// Build query conditions
$conditions = ["a.date >= ?", "a.date <= ?"];
$queryParams = [$fromDate, $toDate];

if (!empty($cleanEmployeeSearch)) {
    if (!empty($codeSearch)) {
        $conditions[] = "(LOWER(u.name) LIKE ? OR LOWER(u.employee_code) LIKE ?)";
        $queryParams[] = '%' . strtolower($cleanEmployeeSearch) . '%';
        $queryParams[] = '%' . strtolower($codeSearch) . '%';
    } else {
        $conditions[] = "(LOWER(u.name) LIKE ? OR LOWER(u.employee_code) LIKE ?)";
        $queryParams[] = '%' . strtolower($cleanEmployeeSearch) . '%';
        $queryParams[] = '%' . strtolower($cleanEmployeeSearch) . '%';
    }
}

$whereSql = implode(' AND ', $conditions);

$stmt = $db->prepare("
    SELECT a.*, u.name as user_name, u.employee_code, u.avatar, u.designation, d.name as department_name, d.id as department_id
    FROM attendance a
    JOIN users u ON (u.id = CASE WHEN IFNULL(a.user_id, 0) > 0 THEN a.user_id ELSE a.employee_id END)
    LEFT JOIN departments d ON u.department_id = d.id
    WHERE {$whereSql}
    ORDER BY a.date DESC, a.id DESC
");
$stmt->execute($queryParams);
$attendanceLogs = $stmt->fetchAll();

// KPI calculations for filtered data
$kpiTotal = count($attendanceLogs);
$kpiFullDays = 0;
$kpiHalfDays = 0;
$kpiLate = 0;
$kpiEarlyExit = 0;
$kpiTotalSeconds = 0;

foreach ($attendanceLogs as $row) {
    $st = strtolower($row['status'] ?? '');
    if (in_array($st, ['full_day', 'full day'])) {
        $kpiFullDays++;
    } elseif (in_array($st, ['half_day', 'half day'])) {
        $kpiHalfDays++;
    }
    if ((int)($row['late_minutes'] ?? 0) > 0) $kpiLate++;
    if ((int)($row['early_exit_minutes'] ?? 0) > 0) $kpiEarlyExit++;
    $kpiTotalSeconds += (int)($row['total_seconds'] ?? 0);
}

$kpiAvgSeconds = $kpiTotal > 0 ? (int)round($kpiTotalSeconds / $kpiTotal) : 0;
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-3 p-md-4">
        <!-- Page Title & Header Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-user-clock text-success me-2"></i>Attendance & Shift Tracking</h3>
                <p class="text-secondary small mb-0">Fixed Office Shift: <strong>09:40 AM IST</strong> to <strong>06:30 PM IST</strong> (8h 50m Target | <strong>02:00 PM - 03:00 PM</strong> Lunch Break)</p>
            </div>
            <div class="d-flex gap-2 align-items-center flex-wrap">
                <span class="live-lunch-status-badge"><?= getLunchBadge() ?></span>
                <span class="wifi-status-badge badge <?= $isClientIpAuth ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' ?> px-2.5 py-1 fs-9 fw-bold">
                    <i class="fas <?= $isClientIpAuth ? 'fa-wifi' : 'fa-wifi-slash' ?> me-1"></i><?= $isClientIpAuth ? htmlspecialchars($clientNetworkName) : 'Office Wi-Fi Required' ?>
                </span>
                <button onclick="handleClockInOut('clock_in')" 
                        class="btn btn-xs btn-outline-success shift-toggle-btn fw-bold px-2.5 rounded-pill align-items-center wifi-restricted-btn <?= $isClientIpAuth ? 'd-inline-flex' : 'd-none' ?>"
                        style="<?= $isClientIpAuth ? '' : 'display:none !important;' ?>">
                    <i class="fas fa-fingerprint me-1"></i>Start Shift
                </button>
            </div>
        </div>

        <!-- Active Shift Live Widget (Responsive Non-Overflowing Card) -->
        <div id="activeShiftWidget" class="glass-card mb-4 p-3 bg-success-subtle border border-success" style="display: none;">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2.5 bg-success text-white rounded-circle flex-shrink-0">
                        <i class="fas fa-user-clock fs-4"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                            <h6 class="fw-bold text-dark mb-0">Active Shift Running</h6>
                            <span class="live-lunch-status-badge"><?= getLunchBadge() ?></span>
                        </div>
                        <div class="small text-secondary">
                            <div>Started at: <strong id="shiftStartTimeText" class="text-dark font-monospace">--</strong></div>
                            <div>Shift Target: <strong class="text-dark">8h 50m</strong> (09:40 AM - 06:30 PM IST)</div>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 ms-auto ms-sm-0">
                    <div class="text-start text-sm-end">
                        <small class="text-secondary d-block fs-8">Working Duration</small>
                        <span class="live-shift-timer-clock font-monospace fw-bold text-success fs-3">00:00:00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- ATTENDANCE FILTER PANEL: SEARCH EMPLOYEE & PICK MONTH     -->
        <!-- ========================================================= -->
        <div class="glass-card mb-4 p-3 p-md-4 border shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <h5 class="fw-bold text-dark mb-0">
                        <i class="fas fa-filter text-primary me-2"></i>Attendance Filter
                    </h5>
                    <span class="badge bg-primary-subtle text-primary border border-primary font-monospace px-3 py-1.5 fs-8" id="activeFilterBadge">
                        <i class="fas fa-calendar-check me-1"></i>Period: <?= date('01 M Y', strtotime($fromDate)) ?> – <?= date('01 M Y', strtotime($toDate)) ?>
                    </span>
                    <span class="badge bg-light text-secondary border px-2.5 py-1.5 fs-8">
                        <span id="badgeRecordCount" class="fw-bold text-dark"><?= $kpiTotal ?></span> Records Found
                    </span>
                    <span class="badge bg-info-subtle text-info border border-info px-2.5 py-1.5 fs-8" id="badgeSearchName" style="<?= empty($cleanEmployeeSearch) ? 'display: none;' : '' ?>">
                        <i class="fas fa-user me-1"></i>Employee: <span id="badgeSearchNameText"><?= sanitize($cleanEmployeeSearch) ?></span>
                    </span>
                </div>
                <div class="text-secondary small">
                    <i class="fas fa-info-circle text-primary me-1"></i>Showing complete 1 month records
                </div>
            </div>

            <!-- Filter Controls Form: ONLY Search Employee Name & Pick Month -->
            <form id="attendanceFilterForm" method="GET" action="<?= BASE_URL ?>/attendance">
                <div class="row g-3 align-items-end">
                    <!-- 1. Search Employee Name -->
                    <div class="col-12 col-md-5 col-lg-5">
                        <label for="filterEmployeeSearch" class="form-label text-dark fw-bold small mb-1">
                            <i class="fas fa-user text-primary me-1"></i>Search Employee Name
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search"></i></span>
                            <input type="text" name="search_employee" id="filterEmployeeSearch" class="form-control border-start-0" placeholder="Type or select employee name..." value="<?= htmlspecialchars($cleanEmployeeSearch) ?>" list="employeesDatalist" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" title="Quick pick employee from list">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" style="max-height: 280px; overflow-y: auto;">
                                <li><button type="button" class="dropdown-item py-1.5 fs-8 text-primary fw-semibold" onclick="selectEmployeeFilter('')"><i class="fas fa-users me-1.5"></i>All Employees</button></li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <?php foreach ($allUsers as $u): ?>
                                    <li>
                                        <button type="button" class="dropdown-item py-1.5 fs-8 d-flex justify-content-between align-items-center" onclick="selectEmployeeFilter('<?= addslashes($u['name']) ?>')">
                                            <span class="fw-semibold text-dark"><?= sanitize($u['name']) ?></span>
                                            <small class="text-muted ms-2 font-monospace"><?= sanitize($u['employee_code'] ?: '') ?></small>
                                        </button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn btn-outline-secondary border-start-0" id="btnClearSearch" title="Clear employee search" style="<?= empty($cleanEmployeeSearch) ? 'display: none;' : '' ?>" onclick="clearEmployeeSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <datalist id="employeesDatalist">
                            <?php foreach ($allUsers as $u): ?>
                                <option value="<?= sanitize($u['name']) ?>"><?= sanitize($u['name']) ?> (<?= sanitize($u['employee_code'] ?: '') ?> - <?= sanitize($u['designation'] ?: '') ?>)</option>
                            <?php endforeach; ?>
                        </datalist>
                        <div class="form-text fs-9 text-muted">Type name or click arrow to pick employee</div>
                    </div>

                    <!-- 2. Pick Month -->
                    <div class="col-12 col-md-4 col-lg-4">
                        <label for="filterMonth" class="form-label text-dark fw-bold small mb-1">
                            <i class="fas fa-calendar-alt text-primary me-1"></i>Pick Month
                        </label>
                        <input type="month" name="month" id="filterMonth" class="form-control font-monospace fw-semibold" value="<?= htmlspecialchars($selectedMonth) ?>" required>
                        <div class="form-text fs-9 text-muted">Shows complete 1 month (e.g. 1 Oct to 1 Nov)</div>
                    </div>

                    <!-- 3. Actions: Filter & Popup & Reset & Export Buttons -->
                    <div class="col-12 col-md-3 col-lg-3 d-flex gap-2 align-items-end flex-wrap">
                        <button type="submit" class="btn btn-primary fw-semibold px-3 flex-fill" id="btnApplyFilter">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <button type="button" class="btn btn-outline-primary fw-semibold px-2.5" id="btnOpenMonthlyPopup" onclick="openSelectedEmployeeMonthlyModal()" title="View Complete Monthly Attendance in Popup" style="<?= empty($cleanEmployeeSearch) ? 'display: none;' : '' ?>">
                            <i class="fas fa-window-restore me-1"></i>Monthly Popup
                        </button>
                        <button type="button" class="btn btn-light border fw-semibold px-2.5 text-secondary" onclick="resetAttendanceFilters()" title="Reset to current month & all employees">
                            <i class="fas fa-undo-alt me-1"></i>Reset
                        </button>
                        <button type="button" class="btn btn-outline-success fw-semibold px-2.5" onclick="exportAttendance('excel')" title="Export to Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- ========================================================= -->
        <!-- KPI METRICS SUMMARY CARDS FOR FILTERED MONTH & EMPLOYEE   -->
        <!-- ========================================================= -->
        <div class="row g-3 mb-4 d-none d-md-flex" id="kpiDesktopCards">
            <!-- Total Shifts -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 stat-kpi-card hover-lift">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small fw-semibold">Total Shifts</span>
                        <div class="kpi-icon-wrap bg-primary-subtle text-primary p-2 rounded-circle"><i class="fas fa-calendar-check fs-6"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-dark font-monospace" id="kpiCardTotal"><?= number_format($kpiTotal) ?></div>
                    <small class="text-muted fs-9">Attendance logs in month</small>
                </div>
            </div>

            <!-- Full Day -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 stat-kpi-card hover-lift">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-success small fw-semibold">Full Day</span>
                        <div class="kpi-icon-wrap bg-success text-white p-2 rounded-circle"><i class="fas fa-sun fs-6"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-success font-monospace" id="kpiCardFullDays"><?= number_format($kpiFullDays) ?></div>
                    <small class="text-muted fs-9">&ge; 8h 50m completed</small>
                </div>
            </div>

            <!-- Half Day -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 stat-kpi-card hover-lift">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-warning small fw-semibold">Half Day</span>
                        <div class="kpi-icon-wrap bg-warning-subtle text-warning p-2 rounded-circle"><i class="fas fa-cloud-sun fs-6"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-warning font-monospace" id="kpiCardHalfDays"><?= number_format($kpiHalfDays) ?></div>
                    <small class="text-muted fs-9">&lt; 8h 50m / early leave</small>
                </div>
            </div>

            <!-- Late Clock-Ins -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 stat-kpi-card hover-lift <?= $kpiLate > 0 ? 'border-danger-subtle' : '' ?>">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-danger small fw-semibold">Late Arrivals</span>
                        <div class="kpi-icon-wrap bg-danger-subtle text-danger p-2 rounded-circle"><i class="fas fa-user-clock fs-6"></i></div>
                    </div>
                    <div class="fs-4 fw-bold text-danger font-monospace" id="kpiCardLate"><?= number_format($kpiLate) ?></div>
                    <small class="text-muted fs-9">Clock-in past 09:40 AM</small>
                </div>
            </div>

            <!-- Total Working Time -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 stat-kpi-card hover-lift">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-info small fw-semibold">Total Hours</span>
                        <div class="kpi-icon-wrap bg-info-subtle text-info p-2 rounded-circle"><i class="fas fa-business-time fs-6"></i></div>
                    </div>
                    <div class="fs-5 fw-bold text-info font-monospace" id="kpiCardTotalHours"><?= formatDuration($kpiTotalSeconds) ?></div>
                    <small class="text-muted fs-9">Cumulative worked time</small>
                </div>
            </div>

            <!-- Average Shift Time -->
            <div class="col-6 col-md-4 col-xl-2">
                <div class="glass-card p-3 h-100 stat-kpi-card hover-lift">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-secondary small fw-semibold">Avg. Shift</span>
                        <div class="kpi-icon-wrap bg-secondary-subtle text-dark p-2 rounded-circle"><i class="fas fa-chart-line fs-6"></i></div>
                    </div>
                    <div class="fs-5 fw-bold text-dark font-monospace" id="kpiCardAvgHours"><?= formatDuration($kpiAvgSeconds) ?></div>
                    <small class="text-muted fs-9">Mean duration per shift</small>
                </div>
            </div>
        </div>

        <!-- Mobile Scrollable KPI Strip (< 768px) -->
        <div class="d-flex d-md-none gap-2 overflow-x-auto pb-2 mb-3 app-kpi-scroll">
            <div class="stat-kpi-card p-2 text-center flex-shrink-0" style="min-width: 100px;">
                <small class="text-muted fs-9 d-block">Shifts</small>
                <span class="fw-bold fs-6 text-dark font-monospace" id="kpiMobileTotal"><?= $kpiTotal ?></span>
            </div>
            <div class="stat-kpi-card p-2 text-center flex-shrink-0" style="min-width: 100px;">
                <small class="text-success fs-9 d-block">Full Day</small>
                <span class="fw-bold fs-6 text-success font-monospace" id="kpiMobileFull"><?= $kpiFullDays ?></span>
            </div>
            <div class="stat-kpi-card p-2 text-center flex-shrink-0" style="min-width: 100px;">
                <small class="text-warning fs-9 d-block">Half Day</small>
                <span class="fw-bold fs-6 text-warning font-monospace" id="kpiMobileHalf"><?= $kpiHalfDays ?></span>
            </div>
            <div class="stat-kpi-card p-2 text-center flex-shrink-0" style="min-width: 100px;">
                <small class="text-danger fs-9 d-block">Late</small>
                <span class="fw-bold fs-6 text-danger font-monospace" id="kpiMobileLate"><?= $kpiLate ?></span>
            </div>
            <div class="stat-kpi-card p-2 text-center flex-shrink-0" style="min-width: 120px;">
                <small class="text-info fs-9 d-block">Total Hours</small>
                <span class="fw-bold fs-7 text-info font-monospace" id="kpiMobileHours"><?= formatDuration($kpiTotalSeconds) ?></span>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- ATTENDANCE HISTORY LOG TABLE & CARDS                      -->
        <!-- ========================================================= -->
        <div class="glass-card mb-4 p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold text-dark mb-0">Attendance History Log</h5>
                    <small class="text-secondary" id="tableSubtitle">
                        Showing attendance records for <strong class="text-dark font-monospace" id="textPeriodLabel"><?= date('01 M Y', strtotime($fromDate)) ?> to <?= date('01 M Y', strtotime($toDate)) ?></strong>
                        <span id="tableSubtitleEmp"><?= !empty($cleanEmployeeSearch) ? ' for employee: <strong class="text-primary">' . sanitize($cleanEmployeeSearch) . '</strong>' : '' ?></span>
                    </small>
                </div>
                <div class="text-muted small">
                    Showing <strong id="tableCounter"><?= count($attendanceLogs) ?></strong> records
                </div>
            </div>

            <!-- Desktop Table View (>= 768px) -->
            <div class="table-responsive d-none d-md-block" id="desktopTableWrapper">
                <table class="table table-hover align-middle mb-0" id="attendanceHistoryTable">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 110px;">Date & Day</th>
                            <th style="min-width: 180px;">Employee</th>
                            <th>Shift Target</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Working Time</th>
                            <th>Punctuality</th>
                            <th>Early Exit</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceTableBody">
                        <?php if (empty($attendanceLogs)): ?>
                            <tr class="empty-placeholder-row">
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <div class="mb-2"><i class="fas fa-calendar-times fs-1 text-secondary opacity-50"></i></div>
                                    <h6 class="fw-bold text-dark">No Attendance Records Found</h6>
                                    <p class="small text-secondary mb-2">
                                        <?= !empty($cleanEmployeeSearch) ? 'No attendance records found for "<strong>' . sanitize($cleanEmployeeSearch) . '</strong>" in ' . date('F Y', strtotime($fromDate)) . '.' : 'No records match the selected month.' ?>
                                    </p>
                                    <button type="button" class="btn btn-xs btn-primary rounded-pill px-3" onclick="resetAttendanceFilters()">
                                        <i class="fas fa-undo-alt me-1"></i>Reset to Complete Month
                                    </button>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($attendanceLogs as $a): 
                                $cInStr = $a['clock_in'] ? (strlen($a['clock_in']) <= 8 ? ($a['date'] . ' ' . $a['clock_in']) : $a['clock_in']) : null;
                                $cOutStr = $a['clock_out'] ? (strlen($a['clock_out']) <= 8 ? ($a['date'] . ' ' . $a['clock_out']) : $a['clock_out']) : null;
                                $lateMins = (int)($a['late_minutes'] ?? 0);
                                $earlyExitMins = (int)($a['early_exit_minutes'] ?? 0);
                                $dayOfWeek = date('D', strtotime($a['date']));
                                $formattedDate = formatISTDate($a['date']);
                                
                                $avatarUrl = !empty($a['avatar']) && file_exists(dirname(__DIR__, 2) . '/public/uploads/avatars/' . $a['avatar'])
                                    ? BASE_URL . '/uploads/avatars/' . $a['avatar']
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($a['user_name'] ?? 'User') . '&background=4f46e5&color=fff';
                            ?>
                                <tr>
                                    <!-- Date -->
                                    <td>
                                        <div class="font-monospace fw-semibold text-dark"><?= $formattedDate ?></div>
                                        <small class="text-muted fs-9 text-uppercase"><?= $dayOfWeek ?></small>
                                    </td>

                                    <!-- Employee -->
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="<?= $avatarUrl ?>" alt="<?= sanitize($a['user_name']) ?>" class="rounded-circle border" width="32" height="32" style="object-fit:cover;">
                                            <div>
                                                <div class="fw-bold text-dark fs-7"><?= sanitize($a['user_name']) ?></div>
                                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                                    <small class="text-secondary fs-9">
                                                        <?= sanitize($a['department_name'] ?? 'General') ?>
                                                        <?php if (!empty($a['employee_code'])): ?>
                                                            &bull; <span class="font-monospace"><?= sanitize($a['employee_code']) ?></span>
                                                        <?php endif; ?>
                                                    </small>
                                                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-1.5 rounded fs-9" onclick="openMonthlyPopupForEmployee('<?= addslashes($a['user_name']) ?>')" title="View <?= sanitize($a['user_name']) ?>'s complete monthly attendance popup">
                                                        <i class="fas fa-calendar-alt me-1"></i>Monthly View
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Shift Target -->
                                    <td>
                                        <span class="badge bg-light text-secondary border font-monospace fs-9">09:40 – 18:30</span>
                                    </td>

                                    <!-- Clock In -->
                                    <td>
                                        <?php if ($cInStr): ?>
                                            <span class="text-success font-monospace fw-semibold d-block fs-8"><?= formatISTTime($cInStr) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted font-monospace">--</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Clock Out -->
                                    <td>
                                        <?php if ($cOutStr): ?>
                                            <span class="text-danger font-monospace fw-semibold d-block fs-8"><?= formatISTTime($cOutStr) ?></span>
                                        <?php elseif ($cInStr): ?>
                                            <span class="badge bg-warning-subtle text-warning border border-warning fs-9"><i class="fas fa-spinner fa-spin me-1"></i>Running</span>
                                        <?php else: ?>
                                            <span class="text-muted font-monospace">--</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Total Duration -->
                                    <td>
                                        <span class="text-info font-monospace fw-bold fs-8"><?= formatDuration($a['total_seconds']) ?></span>
                                    </td>

                                    <!-- Punctuality -->
                                    <td>
                                        <?= getPunctualityBadge($lateMins) ?>
                                    </td>

                                    <!-- Early Exit -->
                                    <td>
                                        <?php if ($earlyExitMins > 0): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger fs-9"><?= $earlyExitMins ?>m Early</span>
                                        <?php else: ?>
                                            <span class="text-secondary small">No</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Attendance Day Status -->
                                    <td>
                                        <?= getAttendanceDayBadge($a['status'], (int)$a['total_seconds'], $cOutStr) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Attendance Cards View (< 768px) -->
            <div class="mobile-card-list d-block d-md-none" id="mobileCardsWrapper">
                <?php if (empty($attendanceLogs)): ?>
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-calendar-times fs-1 text-secondary opacity-50 mb-2"></i>
                        <h6 class="fw-bold text-dark">No Attendance Records Found</h6>
                        <p class="small text-secondary mb-2">
                            <?= !empty($cleanEmployeeSearch) ? 'No attendance records found for "<strong>' . sanitize($cleanEmployeeSearch) . '</strong>" in ' . date('F Y', strtotime($fromDate)) . '.' : 'No records match the selected month.' ?>
                        </p>
                        <button type="button" class="btn btn-xs btn-primary rounded-pill px-3" onclick="resetAttendanceFilters()">Reset Filters</button>
                    </div>
                <?php else: ?>
                    <?php foreach ($attendanceLogs as $a): 
                        $cInStr = $a['clock_in'] ? (strlen($a['clock_in']) <= 8 ? ($a['date'] . ' ' . $a['clock_in']) : $a['clock_in']) : null;
                        $cOutStr = $a['clock_out'] ? (strlen($a['clock_out']) <= 8 ? ($a['date'] . ' ' . $a['clock_out']) : $a['clock_out']) : null;
                        $lateMins = (int)($a['late_minutes'] ?? 0);
                        $earlyExitMins = (int)($a['early_exit_minutes'] ?? 0);
                        $dayOfWeek = date('D', strtotime($a['date']));
                        $formattedDate = formatISTDate($a['date']);
                        
                        $avatarUrl = !empty($a['avatar']) && file_exists(dirname(__DIR__, 2) . '/public/uploads/avatars/' . $a['avatar'])
                            ? BASE_URL . '/uploads/avatars/' . $a['avatar']
                            : 'https://ui-avatars.com/api/?name=' . urlencode($a['user_name'] ?? 'User') . '&background=4f46e5&color=fff';
                    ?>
                        <div class="mobile-data-card mb-3">
                            <div class="mobile-data-card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= $avatarUrl ?>" alt="<?= sanitize($a['user_name']) ?>" class="rounded-circle border" width="28" height="28" style="object-fit:cover;">
                                    <div>
                                        <span class="fw-bold text-dark d-block fs-7"><?= sanitize($a['user_name']) ?></span>
                                        <small class="text-muted font-monospace fs-9"><?= $formattedDate ?> (<?= $dayOfWeek ?>)</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1.5 ms-auto">
                                    <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fs-9" onclick="openMonthlyPopupForEmployee('<?= addslashes($a['user_name']) ?>')">
                                        <i class="fas fa-calendar-alt me-1"></i>Monthly View
                                    </button>
                                    <?= getAttendanceDayBadge($a['status'], (int)$a['total_seconds'], $cOutStr) ?>
                                </div>
                            </div>
                            <div class="mobile-data-card-body">
                                <div class="mobile-stat-box">
                                    <small class="text-secondary d-block fs-9 fw-bold text-uppercase">Clock In</small>
                                    <span class="text-success font-monospace fw-bold fs-8"><?= $cInStr ? formatISTTime($cInStr) : '--' ?></span>
                                </div>
                                <div class="mobile-stat-box">
                                    <small class="text-secondary d-block fs-9 fw-bold text-uppercase">Clock Out</small>
                                    <span class="text-danger font-monospace fw-bold fs-8"><?= $cOutStr ? formatISTTime($cOutStr) : ($cInStr ? 'Running' : '--') ?></span>
                                </div>
                                <div class="mobile-stat-box">
                                    <small class="text-secondary d-block fs-9 fw-bold text-uppercase">Working Time</small>
                                    <span class="text-info font-monospace fw-bold fs-8"><?= formatDuration($a['total_seconds']) ?></span>
                                </div>
                                <div class="mobile-stat-box">
                                    <small class="text-secondary d-block fs-9 fw-bold text-uppercase">Punctuality</small>
                                    <div class="mt-0.5">
                                        <?= getPunctualityBadge($lateMins) ?>
                                        <?php if ($earlyExitMins > 0): ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger fs-9 ms-1"><?= $earlyExitMins ?>m Early</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 border-top bg-light-subtle d-flex justify-content-between align-items-center fs-9 text-muted">
                                <span><i class="fas fa-building me-1"></i><?= sanitize($a['department_name'] ?? 'General') ?></span>
                                <span>Target: 09:40 - 18:30</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- COMPLETE MONTHLY ATTENDANCE POPUP MODAL FOR EMPLOYEE      -->
<!-- ========================================================= -->
<div class="modal fade" id="employeeMonthlyAttendanceModal" tabindex="-1" aria-labelledby="modalEmpName" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content glass-modal border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom py-3">
                <div class="d-flex align-items-center gap-3">
                    <img id="modalEmpAvatar" src="https://ui-avatars.com/api/?name=User&background=4f46e5&color=fff" class="rounded-circle border" width="46" height="46" style="object-fit:cover;">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="modal-title fw-bold text-dark mb-0" id="modalEmpName">Employee Monthly Attendance</h5>
                            <span class="badge bg-primary-subtle text-primary border border-primary font-monospace fs-8" id="modalEmpCode">EMP</span>
                        </div>
                        <div class="small text-secondary mt-0.5">
                            <span id="modalEmpDept"><i class="fas fa-building me-1"></i>Department</span> &bull; 
                            <span id="modalEmpDesignation">Designation</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1.5 font-monospace fs-8" id="modalPeriodBadge">
                        <i class="fas fa-calendar-alt me-1"></i>Period: --
                    </span>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-2.5" onclick="printMonthlyModal()" title="Print Monthly Report">
                        <i class="fas fa-print"></i>
                    </button>
                    <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-2.5" onclick="exportMonthlyModalExcel()" title="Export to Excel">
                        <i class="fas fa-file-excel"></i>
                    </button>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body p-3 p-md-4" id="monthlyModalBody">
                <!-- KPI Stat Cards Row inside Modal -->
                <div class="row g-2 g-md-3 mb-4" id="modalKpiRow">
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-light border p-2.5 text-center h-100 shadow-sm">
                            <small class="text-secondary fs-9 fw-semibold text-uppercase">Total Shifts</small>
                            <span class="fs-4 fw-bold text-dark font-monospace" id="mKpiTotal">0</span>
                            <small class="text-muted fs-9">Days recorded</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-success-subtle border-success-subtle p-2.5 text-center h-100 shadow-sm">
                            <small class="text-success fs-9 fw-semibold text-uppercase">Full Day</small>
                            <span class="fs-4 fw-bold text-success font-monospace" id="mKpiFull">0</span>
                            <small class="text-muted fs-9">&ge; 8h 50m target</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-warning-subtle border-warning-subtle p-2.5 text-center h-100 shadow-sm">
                            <small class="text-warning-emphasis fs-9 fw-semibold text-uppercase">Half Day</small>
                            <span class="fs-4 fw-bold text-warning-emphasis font-monospace" id="mKpiHalf">0</span>
                            <small class="text-muted fs-9">&lt; 8h 50m / early</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-danger-subtle border-danger-subtle p-2.5 text-center h-100 shadow-sm">
                            <small class="text-danger fs-9 fw-semibold text-uppercase">Late Arrivals</small>
                            <span class="fs-4 fw-bold text-danger font-monospace" id="mKpiLate">0</span>
                            <small class="text-muted fs-9">After 09:40 AM</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-info-subtle border-info-subtle p-2.5 text-center h-100 shadow-sm">
                            <small class="text-info-emphasis fs-9 fw-semibold text-uppercase">Total Hours</small>
                            <span class="fs-5 fw-bold text-info-emphasis font-monospace" id="mKpiHours">00h 00m</span>
                            <small class="text-muted fs-9">Cumulative worked</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-xl-2">
                        <div class="card bg-secondary-subtle border-secondary-subtle p-2.5 text-center h-100 shadow-sm">
                            <small class="text-dark fs-9 fw-semibold text-uppercase">Avg. Shift</small>
                            <span class="fs-5 fw-bold text-dark font-monospace" id="mKpiAvg">00h 00m</span>
                            <small class="text-muted fs-9">Per working day</small>
                        </div>
                    </div>
                </div>

                <!-- Complete 1-Month Attendance Table in Modal -->
                <div class="table-responsive border rounded shadow-sm">
                    <table class="table table-hover align-middle mb-0" id="modalAttendanceTable">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 110px;">Date & Day</th>
                                <th>Shift Target</th>
                                <th>Clock In</th>
                                <th>Clock Out</th>
                                <th>Working Time</th>
                                <th>Punctuality</th>
                                <th>Early Exit</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="modalAttendanceTableBody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light border-top py-2.5 d-flex justify-content-between align-items-center">
                <span class="text-muted small">
                    <i class="fas fa-info-circle text-primary me-1"></i>Showing complete 1 month records for selected employee.
                </span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success px-3" onclick="exportMonthlyModalExcel()">
                        <i class="fas fa-file-excel me-1"></i>Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden !important;
    }
    #employeeMonthlyAttendanceModal,
    #employeeMonthlyAttendanceModal * {
        visibility: visible !important;
    }
    #employeeMonthlyAttendanceModal {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .modal-dialog {
        max-width: 100% !important;
        margin: 0 !important;
    }
    .modal-footer, .btn-close, .modal-header .btn {
        display: none !important;
    }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- DataTables & Filter Scripts -->
<script>
    let attendanceDataTable = null;
    let currentAttendanceLogs = <?= json_encode($attendanceLogs) ?>;
    let searchDebounceTimer = null;
    let modalCurrentLogs = [];
    let modalCurrentEmp = '<?= addslashes($cleanEmployeeSearch) ?>';
    let modalCurrentMonth = '<?= addslashes($selectedMonth) ?>';
    let filterAbortController = null;
    let isFilterRunning = false;

    // Helper to resolve clean API URL without CORS or mixed-content protocol mismatches
    function resolveApiUrl(path) {
        let base = '';
        if (typeof BASE_URL !== 'undefined' && BASE_URL) {
            try {
                const u = new URL(BASE_URL, window.location.origin);
                base = u.pathname.replace(/\/+$/, '');
            } catch(e) {
                base = String(BASE_URL).replace(/\/+$/, '');
            }
        }
        return `${base}${path.startsWith('/') ? path : '/' + path}`;
    }

    $(document).ready(function() {
        initAttendanceDataTable(currentAttendanceLogs && currentAttendanceLogs.length > 0);

        // Standard Form Submit Event: AJAX with automatic fallback to standard GET submission
        $('#attendanceFilterForm').on('submit', function(e) {
            e.preventDefault();
            applyAttendanceFilters(true);
        });

        // Month filter changed: trigger filter update
        $('#filterMonth').on('change', function() {
            applyAttendanceFilters(false);
        });

        // Real-time debounce typing in employee search (updates page filter smoothly)
        $('#filterEmployeeSearch').on('input', function() {
            const val = $(this).val().trim();
            $('#btnClearSearch').toggle(val.length > 0);
            $('#btnOpenMonthlyPopup').toggle(val.length > 0);
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(function() {
                applyAttendanceFilters(false);
            }, 400);
        });

        // Trigger filter & popup when an employee is selected from datalist or dropdown
        $('#filterEmployeeSearch').on('change', function() {
            clearTimeout(searchDebounceTimer);
            const val = $(this).val().trim();
            if (val) {
                applyAttendanceFilters(true);
            } else {
                applyAttendanceFilters(false);
            }
        });

        // Automatically open Complete Monthly Attendance Popup if page was loaded with an employee search
        <?php if (!empty($cleanEmployeeSearch)): ?>
        setTimeout(function() {
            openMonthlyPopupForEmployee('<?= addslashes($cleanEmployeeSearch) ?>', '<?= addslashes($selectedMonth) ?>');
        }, 350);
        <?php endif; ?>
    });

    // Quick pick employee from dropdown list
    function selectEmployeeFilter(empName) {
        $('#filterEmployeeSearch').val(empName);
        if (empName) {
            $('#btnClearSearch').show();
            $('#btnOpenMonthlyPopup').show();
            applyAttendanceFilters(true);
        } else {
            $('#btnClearSearch').hide();
            $('#btnOpenMonthlyPopup').hide();
            applyAttendanceFilters(false);
        }
    }

    function clearEmployeeSearch() {
        $('#filterEmployeeSearch').val('');
        $('#btnClearSearch').hide();
        $('#btnOpenMonthlyPopup').hide();
        applyAttendanceFilters(false);
    }

    function resetAttendanceFilters() {
        $('#filterEmployeeSearch').val('');
        $('#btnClearSearch').hide();
        $('#btnOpenMonthlyPopup').hide();
        $('#filterMonth').val('<?= date('Y-m') ?>');
        window.location.href = resolveApiUrl('/attendance');
    }

    function openSelectedEmployeeMonthlyModal() {
        const rawEmp = $('#filterEmployeeSearch').val() || '';
        const cleanEmp = rawEmp.replace(/^([^\(]+)\s*\(.*$/, '$1').trim();
        const month = $('#filterMonth').val() || '<?= date('Y-m') ?>';
        if (!cleanEmp) {
            Swal.fire('Search Required', 'Please type or select an employee name first.', 'info');
            return;
        }
        openMonthlyPopupForEmployee(cleanEmp, month);
    }

    // Populate and show the modal with provided logs (fast, instant, zero redundant network fetch)
    function populateAndShowMonthlyModal(cleanEmp, month, logs, kpis) {
        if (!cleanEmp) return;
        cleanEmp = cleanEmp.replace(/^([^\(]+)\s*\(.*$/, '$1').trim();
        month = month || $('#filterMonth').val() || '<?= date('Y-m') ?>';
        modalCurrentEmp = cleanEmp;
        modalCurrentMonth = month;
        modalCurrentLogs = logs || [];

        // Show Bootstrap Modal
        const modalEl = document.getElementById('employeeMonthlyAttendanceModal');
        let modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();

        // Update Header info
        if (logs && logs.length > 0) {
            const first = logs[0];
            $('#modalEmpName').text(first.user_name || cleanEmp);
            $('#modalEmpCode').text(first.employee_code || 'EMP');
            $('#modalEmpDept').html(`<i class="fas fa-building me-1"></i>${escapeHtml(first.department_name || 'General')}`);
            $('#modalEmpDesignation').text(first.designation || 'Staff Member');
            if (first.avatar_url) {
                $('#modalEmpAvatar').attr('src', first.avatar_url);
            }
        } else {
            $('#modalEmpName').text(cleanEmp);
            $('#modalEmpCode').text('EMP');
            $('#modalEmpDept').html('<i class="fas fa-building me-1"></i>Staff');
            $('#modalEmpDesignation').text('Employee');
            $('#modalEmpAvatar').attr('src', `https://ui-avatars.com/api/?name=${encodeURIComponent(cleanEmp)}&background=4f46e5&color=fff`);
        }

        // Update Period Badge
        if (kpis && kpis.from_date && kpis.to_date) {
            const fromParts = kpis.from_date.split('-');
            const toParts = kpis.to_date.split('-');
            const fromD = new Date(parseInt(fromParts[0]), parseInt(fromParts[1]) - 1, parseInt(fromParts[2]));
            const toD = new Date(parseInt(toParts[0]), parseInt(toParts[1]) - 1, parseInt(toParts[2]));
            const formatOpts = { day: '2-digit', month: 'short', year: 'numeric' };
            $('#modalPeriodBadge').html(`<i class="fas fa-calendar-check me-1"></i>Period: ${fromD.toLocaleDateString('en-GB', formatOpts)} – ${toD.toLocaleDateString('en-GB', formatOpts)}`);
        } else {
            $('#modalPeriodBadge').html(`<i class="fas fa-calendar-check me-1"></i>Period: ${escapeHtml(month)}`);
        }

        // Update KPI Cards in Modal
        const total = kpis ? (kpis.total_records ?? logs.length) : logs.length;
        $('#mKpiTotal').text(total);
        $('#mKpiFull').text(kpis ? (kpis.full_days || 0) : 0);
        $('#mKpiHalf').text(kpis ? (kpis.half_days || 0) : 0);
        $('#mKpiLate').text(kpis ? (kpis.late_count || 0) : 0);
        $('#mKpiHours').text(kpis ? (kpis.total_duration || '00h 00m 00s') : '00h 00m 00s');
        $('#mKpiAvg').text(kpis ? (kpis.avg_duration || '00h 00m 00s') : '00h 00m 00s');

        // Render Table Rows in Modal
        if (!logs || logs.length === 0) {
            $('#modalAttendanceTableBody').html(`
                <tr>
                    <td colspan="8" class="text-center py-5 text-muted">
                        <div class="mb-2"><i class="fas fa-calendar-times fs-1 text-secondary opacity-50"></i></div>
                        <h6 class="fw-bold text-dark">No Attendance Records Found</h6>
                        <p class="small text-secondary mb-0">No records found for "<strong>${escapeHtml(cleanEmp)}</strong>" in ${escapeHtml(month)}.</p>
                    </td>
                </tr>
            `);
        } else {
            const html = logs.map(a => {
                const clockIn = (a.clock_in && a.clock_in !== '--') ? `<span class="text-success font-monospace fw-semibold fs-8">${escapeHtml(a.clock_in)}</span>` : `<span class="text-muted font-monospace">--</span>`;
                const clockOut = (a.clock_out && a.clock_out !== '--') ? `<span class="text-danger font-monospace fw-semibold fs-8">${escapeHtml(a.clock_out)}</span>` : ((a.clock_in && a.clock_in !== '--') ? `<span class="badge bg-warning-subtle text-warning border border-warning fs-9"><i class="fas fa-spinner fa-spin me-1"></i>Running</span>` : `<span class="text-muted font-monospace">--</span>`);
                const duration = a.duration_formatted || '--';
                const punctuality = a.punctuality_badge || `<span class="badge bg-secondary-subtle text-secondary fs-9">--</span>`;
                const earlyExit = (parseInt(a.early_exit_minutes) > 0) ? `<span class="badge bg-danger-subtle text-danger border border-danger fs-9">${a.early_exit_minutes}m Early</span>` : `<span class="text-secondary small">No</span>`;
                const statusBadge = a.day_badge || `<span class="badge bg-secondary fs-9">${escapeHtml(a.status || '')}</span>`;

                return `
                    <tr>
                        <td>
                            <div class="font-monospace fw-semibold text-dark">${escapeHtml(a.date_formatted || a.date)}</div>
                            <small class="text-muted fs-9 text-uppercase">${escapeHtml(a.day_of_week || '')}</small>
                        </td>
                        <td><span class="badge bg-light text-secondary border font-monospace fs-9">09:40 – 18:30</span></td>
                        <td>${clockIn}</td>
                        <td>${clockOut}</td>
                        <td><span class="text-info font-monospace fw-bold fs-8">${escapeHtml(duration)}</span></td>
                        <td>${punctuality}</td>
                        <td>${earlyExit}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
            }).join('');
            $('#modalAttendanceTableBody').html(html);
        }
    }

    function openMonthlyPopupForEmployee(empName, month) {
        if (!empName) return;
        const cleanEmp = empName.replace(/^([^\(]+)\s*\(.*$/, '$1').trim();
        month = month || $('#filterMonth').val() || '<?= date('Y-m') ?>';

        // Check if logs are already loaded in currentAttendanceLogs for this employee
        if (modalCurrentEmp === cleanEmp && currentAttendanceLogs && currentAttendanceLogs.length > 0) {
            populateAndShowMonthlyModal(cleanEmp, month, currentAttendanceLogs, null);
            return;
        }

        modalCurrentEmp = cleanEmp;
        modalCurrentMonth = month;

        // Show Bootstrap Modal with loading state
        const modalEl = document.getElementById('employeeMonthlyAttendanceModal');
        let modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
        modalInstance.show();

        // Initial loading state
        $('#modalEmpName').text(cleanEmp);
        $('#modalEmpCode').text('...');
        $('#modalEmpDept').html('<i class="fas fa-building me-1"></i>Loading...');
        $('#modalEmpDesignation').text('Employee');
        $('#modalPeriodBadge').html('<i class="fas fa-spinner fa-spin me-1"></i>Loading period...');
        $('#mKpiTotal, #mKpiFull, #mKpiHalf, #mKpiLate').text('0');
        $('#mKpiHours, #mKpiAvg').text('00h 00m');
        $('#modalAttendanceTableBody').html(`
            <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fas fa-spinner fa-spin fs-2 text-primary mb-3 d-block"></i>
                    <h6 class="fw-bold text-dark">Loading Complete 1-Month Attendance...</h6>
                    <p class="small text-secondary mb-0">Fetching records for <strong>${escapeHtml(cleanEmp)}</strong></p>
                </td>
            </tr>
        `);

        const apiUrl = resolveApiUrl(`/api/attendance?action=get_logs&month=${encodeURIComponent(month)}&search_employee=${encodeURIComponent(cleanEmp)}`);

        fetch(apiUrl)
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    populateAndShowMonthlyModal(cleanEmp, month, res.data.logs || [], res.data.kpis);
                } else {
                    $('#modalAttendanceTableBody').html(`
                        <tr>
                            <td colspan="8" class="text-center py-4 text-danger">
                                Failed to load attendance logs: ${escapeHtml(res.message || 'Unknown error')}
                            </td>
                        </tr>
                    `);
                }
            })
            .catch(err => {
                $('#modalAttendanceTableBody').html(`
                    <tr>
                        <td colspan="8" class="text-center py-4 text-danger">
                            Network error while fetching logs: ${escapeHtml(err.message)}
                        </td>
                    </tr>
                `);
            });
    }

    function applyAttendanceFilters(openModal = false) {
        if (isFilterRunning && filterAbortController) {
            filterAbortController.abort();
        }

        const rawEmp = $('#filterEmployeeSearch').val() || '';
        const cleanEmp = rawEmp.replace(/^([^\(]+)\s*\(.*$/, '$1').trim();
        const month = $('#filterMonth').val() || '<?= date('Y-m') ?>';

        $('#btnApplyFilter').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Filtering...');
        $('#btnOpenMonthlyPopup').toggle(cleanEmp.length > 0);

        // Update URL query parameters in address bar without full page reload
        try {
            const url = new URL(window.location.href);
            if (cleanEmp) {
                url.searchParams.set('search_employee', cleanEmp);
            } else {
                url.searchParams.delete('search_employee');
                url.searchParams.delete('search');
            }
            if (month) {
                url.searchParams.set('month', month);
            } else {
                url.searchParams.delete('month');
            }
            window.history.replaceState({}, '', url.toString());
        } catch (e) {}

        const apiUrl = resolveApiUrl(`/api/attendance?action=get_logs&month=${encodeURIComponent(month)}&search_employee=${encodeURIComponent(cleanEmp)}`);

        filterAbortController = new AbortController();
        isFilterRunning = true;

        fetch(apiUrl, { signal: filterAbortController.signal })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error ${res.status}`);
                return res.json();
            })
            .then(res => {
                isFilterRunning = false;
                $('#btnApplyFilter').prop('disabled', false).html('<i class="fas fa-filter me-1"></i>Filter');
                if (res.success && res.data) {
                    currentAttendanceLogs = res.data.logs || [];
                    renderAttendanceView(currentAttendanceLogs, res.data.kpis, cleanEmp, month);

                    // If openModal is requested and employee is selected, automatically show modal popup
                    if (openModal && cleanEmp) {
                        populateAndShowMonthlyModal(cleanEmp, month, currentAttendanceLogs, res.data.kpis);
                    }
                } else {
                    console.warn("Filter warning:", res.message);
                }
            })
            .catch(err => {
                isFilterRunning = false;
                $('#btnApplyFilter').prop('disabled', false).html('<i class="fas fa-filter me-1"></i>Filter');
                if (err.name === 'AbortError') return;
                console.error("Attendance filter fetch error, falling back to form submit:", err);
                // Fallback to standard form GET submission if AJAX fails
                document.getElementById('attendanceFilterForm').submit();
            });
    }

    function initAttendanceDataTable(hasRows = true) {
        if (!$('#attendanceHistoryTable').length) return;
        try {
            if ($.fn.DataTable.isDataTable('#attendanceHistoryTable')) {
                $('#attendanceHistoryTable').DataTable().destroy();
            }
            if (hasRows) {
                attendanceDataTable = $('#attendanceHistoryTable').DataTable({
                    order: [[0, 'desc']],
                    pageLength: 25,
                    lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search table rows...",
                        lengthMenu: "Show _MENU_ records",
                        info: "Showing _START_ to _END_ of _TOTAL_ attendance logs",
                        emptyTable: "No attendance logs found matching the filter."
                    },
                    responsive: true
                });
            } else {
                attendanceDataTable = null;
            }
        } catch (e) {
            console.warn("DataTables initialization warning:", e);
        }
    }

    function renderAttendanceView(logs, kpis, cleanEmp, month) {
        const total = kpis ? kpis.total_records : logs.length;
        
        // 1. Update KPI Numbers
        $('#badgeRecordCount').text(total);
        $('#kpiCardTotal, #kpiMobileTotal').text(total);
        $('#kpiCardFullDays, #kpiMobileFull').text(kpis ? kpis.full_days : 0);
        $('#kpiCardHalfDays, #kpiMobileHalf').text(kpis ? kpis.half_days : 0);
        $('#kpiCardLate, #kpiMobileLate').text(kpis ? kpis.late_count : 0);
        $('#kpiCardTotalHours, #kpiMobileHours').text(kpis ? kpis.total_duration : '00h 00m 00s');
        $('#kpiCardAvgHours').text(kpis ? kpis.avg_duration : '00h 00m 00s');
        $('#tableCounter').text(total);

        // 2. Update Period label
        if (kpis && kpis.from_date && kpis.to_date) {
            const fromParts = kpis.from_date.split('-');
            const toParts = kpis.to_date.split('-');
            const fromD = new Date(parseInt(fromParts[0]), parseInt(fromParts[1]) - 1, parseInt(fromParts[2]));
            const toD = new Date(parseInt(toParts[0]), parseInt(toParts[1]) - 1, parseInt(toParts[2]));
            const formatOpts = { day: '2-digit', month: 'short', year: 'numeric' };
            const labelText = `${fromD.toLocaleDateString('en-GB', formatOpts)} – ${toD.toLocaleDateString('en-GB', formatOpts)}`;
            $('#activeFilterBadge').html(`<i class="fas fa-calendar-check me-1"></i>Period: ${labelText}`);
            $('#textPeriodLabel').text(labelText);
        }

        // 3. Update Employee search badge
        if (cleanEmp) {
            $('#badgeSearchName').show();
            $('#badgeSearchNameText').text(cleanEmp);
            $('#tableSubtitleEmp').html(` for employee: <strong class="text-primary">${escapeHtml(cleanEmp)}</strong>`);
            $('#btnOpenMonthlyPopup').show();
        } else {
            $('#badgeSearchName').hide();
            $('#tableSubtitleEmp').empty();
            $('#btnOpenMonthlyPopup').hide();
        }

        // 4. Update Desktop Table Rows & Re-init DataTables
        if ($.fn.DataTable.isDataTable('#attendanceHistoryTable')) {
            $('#attendanceHistoryTable').DataTable().destroy();
        }
        $('#attendanceTableBody').html(renderDesktopRows(logs, cleanEmp));
        initAttendanceDataTable(logs.length > 0);

        // 5. Update Mobile Cards
        $('#mobileCardsWrapper').html(renderMobileCards(logs, cleanEmp));
    }

    function renderDesktopRows(logs, cleanEmp) {
        if (!logs || logs.length === 0) {
            const empLabel = cleanEmp ? ` for "<strong>${escapeHtml(cleanEmp)}</strong>"` : '';
            return `
                <tr class="empty-placeholder-row">
                    <td colspan="9" class="text-center py-5 text-muted">
                        <div class="mb-2"><i class="fas fa-calendar-times fs-1 text-secondary opacity-50"></i></div>
                        <h6 class="fw-bold text-dark">No Attendance Records Found</h6>
                        <p class="small text-secondary mb-2">No attendance records found${empLabel} in the selected month.</p>
                        <button type="button" class="btn btn-xs btn-primary rounded-pill px-3" onclick="resetAttendanceFilters()">
                            <i class="fas fa-undo-alt me-1"></i>Reset to Complete Month
                        </button>
                    </td>
                </tr>
            `;
        }

        return logs.map(a => {
            const avatarUrl = a.avatar_url || (a.avatar && a.avatar !== 'default_avatar.png' ? resolveApiUrl('/uploads/avatars/' + a.avatar) : `https://ui-avatars.com/api/?name=${encodeURIComponent(a.user_name || 'User')}&background=4f46e5&color=fff`);
            const dept = a.department_name || 'General';
            const empCode = a.employee_code ? `&bull; <span class="font-monospace">${escapeHtml(a.employee_code)}</span>` : '';
            const dayOfWeek = a.day_of_week || '';
            const dateFormatted = a.date_formatted || a.date;
            const clockIn = (a.clock_in && a.clock_in !== '--') ? `<span class="text-success font-monospace fw-semibold d-block fs-8">${escapeHtml(a.clock_in)}</span>` : `<span class="text-muted font-monospace">--</span>`;
            const clockOut = (a.clock_out && a.clock_out !== '--') ? `<span class="text-danger font-monospace fw-semibold d-block fs-8">${escapeHtml(a.clock_out)}</span>` : ((a.clock_in && a.clock_in !== '--') ? `<span class="badge bg-warning-subtle text-warning border border-warning fs-9"><i class="fas fa-spinner fa-spin me-1"></i>Running</span>` : `<span class="text-muted font-monospace">--</span>`);
            const duration = a.duration_formatted || '--';
            const punctuality = a.punctuality_badge || `<span class="badge bg-secondary-subtle text-secondary fs-9">--</span>`;
            const earlyExit = (parseInt(a.early_exit_minutes) > 0) ? `<span class="badge bg-danger-subtle text-danger border border-danger fs-9">${a.early_exit_minutes}m Early</span>` : `<span class="text-secondary small">No</span>`;
            const statusBadge = a.day_badge || `<span class="badge bg-secondary fs-9">${escapeHtml(a.status || '')}</span>`;

            return `
                <tr>
                    <td>
                        <div class="font-monospace fw-semibold text-dark">${escapeHtml(dateFormatted)}</div>
                        <small class="text-muted fs-9 text-uppercase">${escapeHtml(dayOfWeek)}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="${avatarUrl}" alt="${escapeHtml(a.user_name)}" class="rounded-circle border" width="32" height="32" style="object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(a.user_name || 'User')}&background=4f46e5&color=fff'">
                            <div>
                                <div class="fw-bold text-dark fs-7">${escapeHtml(a.user_name)}</div>
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <small class="text-secondary fs-9">
                                        ${escapeHtml(dept)} ${empCode}
                                    </small>
                                    <button type="button" class="btn btn-xs btn-outline-primary py-0 px-1.5 rounded fs-9" onclick="openMonthlyPopupForEmployee('${escapeJs(a.user_name)}')" title="View ${escapeHtml(a.user_name)}'s monthly attendance popup">
                                        <i class="fas fa-calendar-alt me-1"></i>Monthly View
                                    </button>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-secondary border font-monospace fs-9">09:40 – 18:30</span></td>
                    <td>${clockIn}</td>
                    <td>${clockOut}</td>
                    <td><span class="text-info font-monospace fw-bold fs-8">${escapeHtml(duration)}</span></td>
                    <td>${punctuality}</td>
                    <td>${earlyExit}</td>
                    <td>${statusBadge}</td>
                </tr>
            `;
        }).join('');
    }

    function renderMobileCards(logs, cleanEmp) {
        if (!logs || logs.length === 0) {
            const empLabel = cleanEmp ? ` for "<strong>${escapeHtml(cleanEmp)}</strong>"` : '';
            return `
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-calendar-times fs-1 text-secondary opacity-50 mb-2"></i>
                    <h6 class="fw-bold text-dark">No Attendance Records Found</h6>
                    <p class="small text-secondary mb-2">No attendance records found${empLabel} in this period.</p>
                    <button type="button" class="btn btn-xs btn-primary rounded-pill px-3" onclick="resetAttendanceFilters()">Reset Filters</button>
                </div>
            `;
        }

        return logs.map(a => {
            const avatarUrl = a.avatar_url || (a.avatar && a.avatar !== 'default_avatar.png' ? resolveApiUrl('/uploads/avatars/' + a.avatar) : `https://ui-avatars.com/api/?name=${encodeURIComponent(a.user_name || 'User')}&background=4f46e5&color=fff`);
            const dept = a.department_name || 'General';
            const dayOfWeek = a.day_of_week || '';
            const dateFormatted = a.date_formatted || a.date;
            const clockIn = a.clock_in || '--';
            const clockOut = a.clock_out !== '--' ? a.clock_out : (a.clock_in !== '--' ? 'Running' : '--');
            const duration = a.duration_formatted || '--';
            const punctuality = a.punctuality_badge || '';
            const earlyBadge = (parseInt(a.early_exit_minutes) > 0) ? `<span class="badge bg-danger-subtle text-danger border border-danger fs-9 ms-1">${a.early_exit_minutes}m Early</span>` : '';
            const statusBadge = a.day_badge || '';

            return `
                <div class="mobile-data-card mb-3">
                    <div class="mobile-data-card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <img src="${avatarUrl}" alt="${escapeHtml(a.user_name)}" class="rounded-circle border" width="28" height="28" style="object-fit:cover;" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(a.user_name || 'User')}&background=4f46e5&color=fff'">
                            <div>
                                <span class="fw-bold text-dark d-block fs-7">${escapeHtml(a.user_name)}</span>
                                <small class="text-muted font-monospace fs-9">${escapeHtml(dateFormatted)} (${escapeHtml(dayOfWeek)})</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1.5 ms-auto">
                            <button type="button" class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fs-9" onclick="openMonthlyPopupForEmployee('${escapeJs(a.user_name)}')">
                                <i class="fas fa-calendar-alt me-1"></i>Monthly View
                            </button>
                            ${statusBadge}
                        </div>
                    </div>
                    <div class="mobile-data-card-body">
                        <div class="mobile-stat-box">
                            <small class="text-secondary d-block fs-9 fw-bold text-uppercase">Clock In</small>
                            <span class="text-success font-monospace fw-bold fs-8">${escapeHtml(clockIn)}</span>
                        </div>
                        <div class="mobile-stat-box">
                            <small class="text-secondary d-block fs-9 fw-bold text-uppercase">Clock Out</small>
                            <span class="text-danger font-monospace fw-bold fs-8">${escapeHtml(clockOut)}</span>
                        </div>
                        <div class="mobile-stat-box">
                            <small class="text-secondary d-block fs-9 fw-bold text-uppercase">Working Time</small>
                            <span class="text-info font-monospace fw-bold fs-8">${escapeHtml(duration)}</span>
                        </div>
                        <div class="mobile-stat-box">
                            <small class="text-secondary d-block fs-9 fw-bold text-uppercase">Punctuality</small>
                            <div class="mt-0.5">${punctuality} ${earlyBadge}</div>
                        </div>
                    </div>
                    <div class="p-2 border-top bg-light-subtle d-flex justify-content-between align-items-center fs-9 text-muted">
                        <span><i class="fas fa-building me-1"></i>${escapeHtml(dept)}</span>
                        <span>Target: 09:40 - 18:30</span>
                    </div>
                </div>
            `;
        }).join('');
    }

    function printMonthlyModal() {
        window.print();
    }

    function exportMonthlyModalExcel() {
        if (!modalCurrentLogs || modalCurrentLogs.length === 0) {
            Swal.fire('Notice', 'No attendance records available to export for this employee.', 'info');
            return;
        }

        const fileName = `monthly_attendance_${(modalCurrentEmp || 'employee').replace(/[^a-zA-Z0-9]/g, '_')}_${modalCurrentMonth || 'month'}`;
        const headers = [
            'Date', 'Day', 'Employee Name', 'Employee Code', 'Department', 'Designation',
            'Shift Target', 'Clock In', 'Clock Out', 'Total Duration',
            'Late Minutes', 'Early Exit Minutes', 'Status', 'Notes'
        ];

        const rows = modalCurrentLogs.map(l => [
            l.date_formatted || l.date,
            l.day_of_week || '',
            l.user_name || modalCurrentEmp,
            l.employee_code || '',
            l.department_name || '',
            l.designation || '',
            '09:40 AM - 06:30 PM',
            l.clock_in || '--',
            l.clock_out || '--',
            l.duration_formatted || '',
            l.late_minutes || 0,
            l.early_exit_minutes || 0,
            (l.status || '').toUpperCase(),
            l.notes || ''
        ]);

        if (typeof XLSX !== 'undefined') {
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Monthly Attendance");
            XLSX.writeFile(wb, `${fileName}.xlsx`);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Exported Employee Monthly Excel',
                showConfirmButton: false,
                timer: 2500
            });
        } else {
            const csvData = [headers, ...rows].map(row => 
                row.map(cell => `"${String(cell || '').replace(/"/g, '""')}"`).join(',')
            ).join('\r\n');

            const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", `${fileName}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Exported Employee Monthly CSV',
                showConfirmButton: false,
                timer: 2500
            });
        }
    }

    // Export current page attendance dataset to Excel or CSV
    function exportAttendance(type) {
        if (!currentAttendanceLogs || currentAttendanceLogs.length === 0) {
            Swal.fire('Notice', 'No attendance records available to export for this filter.', 'info');
            return;
        }

        const month = document.getElementById('filterMonth').value || '<?= date('Y-m') ?>';
        const empSearch = document.getElementById('filterEmployeeSearch').value.trim();
        const fileName = `attendance_log_${month}` + (empSearch ? `_${empSearch.replace(/[^a-zA-Z0-9]/g, '_')}` : '');

        const headers = [
            'Date', 'Employee Name', 'Employee Code', 'Department', 'Designation',
            'Shift Target', 'Clock In', 'Clock Out', 'Total Duration',
            'Late Minutes', 'Early Exit Minutes', 'Status', 'Notes'
        ];

        const rows = currentAttendanceLogs.map(l => [
            l.date || l.date_formatted,
            l.user_name || '',
            l.employee_code || '',
            l.department_name || '',
            l.designation || '',
            '09:40 AM - 06:30 PM',
            l.clock_in || '--',
            l.clock_out || '--',
            l.duration_formatted || '',
            l.late_minutes || 0,
            l.early_exit_minutes || 0,
            (l.status || '').toUpperCase(),
            l.notes || ''
        ]);

        if (type === 'excel' && typeof XLSX !== 'undefined') {
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet([headers, ...rows]);
            XLSX.utils.book_append_sheet(wb, ws, "Attendance");
            XLSX.writeFile(wb, `${fileName}.xlsx`);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Exported Excel Successfully',
                showConfirmButton: false,
                timer: 2500
            });
        } else {
            const csvData = [headers, ...rows].map(row => 
                row.map(cell => `"${String(cell || '').replace(/"/g, '""')}"`).join(',')
            ).join('\r\n');

            const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", `${fileName}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Exported CSV Successfully',
                showConfirmButton: false,
                timer: 2500
            });
        }
    }
</script>
