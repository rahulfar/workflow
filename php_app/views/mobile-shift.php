<?php
/**
 * Dedicated Hidden Mobile Attendance Portal
 * Specifically authorized for: Uvaish (ID 5) & Rahul (ID 4) + Admin
 * Route: /mobile-shift or /mobile-attendance
 */
$pageTitle = "Mobile Shift Portal";
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../helpers/functions.php';

// Strict server-side route guard: Only Uvaish, Rahul, and Admin can access
$user = requireAuthorizedShiftEmployee();
$userId = (int)$user['id'];
$userName = $user['name'] ?? 'Authorized Employee';
$userEmail = $user['email'] ?? '';
$userRoleTitle = $user['role_title'] ?? ($user['designation'] ?? 'Team Member');
$userAvatar = !empty($user['avatar']) ? $user['avatar'] : ('https://ui-avatars.com/api/?name=' . urlencode($userName) . '&background=4f46e5&color=fff&bold=true');

$db = Database::getConnection();
ensureAttendanceColumns($db);
ensureAuthorizedIpTable($db);
ensureShiftAndActivitySchema($db);
autoCloseOverdueShifts($db, $userId);

$clientIp = getClientIp();
$isClientIpAuth = isClientIpAuthorized($db, $clientIp);
$clientNetworkName = getIpNetworkName($db, $clientIp);

$today = date('Y-m-d');
$settings = getShiftSettings($db);
$shiftStartTarget = $settings['start_time'] ?? '09:40 AM';
$shiftEndTarget = $settings['end_time'] ?? '06:30 PM';
$lunchStart = $settings['lunch_start'] ?? '02:00 PM';
$lunchEnd = $settings['lunch_end'] ?? '03:00 PM';
$lunchInfo = getLunchTimingInfo();

// Check for active shift
$activeStmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND clock_out IS NULL ORDER BY id DESC LIMIT 1");
$activeStmt->execute([$userId, $userId]);
$activeShift = $activeStmt->fetch();
$isShiftRunning = !empty($activeShift);

$activeClockInStr = '';
$elapsedSeconds = 0;
$lateMinutes = 0;
$isLate = false;
$clockInFormatted = '--:--';
$clockInTimeOnly = '--:--';

if ($isShiftRunning) {
    $activeClockInStr = strlen($activeShift['clock_in']) <= 8 ? ($activeShift['date'] . ' ' . $activeShift['clock_in']) : $activeShift['clock_in'];
    $startTimestamp = strtotime($activeClockInStr);
    $elapsedSeconds = max(0, time() - $startTimestamp);
    $lateMinutes = (int)($activeShift['late_minutes'] ?? calculateLateMinutes($activeClockInStr));
    $isLate = ($lateMinutes > 0);
    $clockInFormatted = formatISTDateTime($activeClockInStr);
    $clockInTimeOnly = formatISTTime($activeClockInStr);
}

// Check for today's completed shift (if any)
$todayStmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date = ? AND clock_out IS NOT NULL ORDER BY id DESC LIMIT 1");
$todayStmt->execute([$userId, $userId, $today]);
$completedToday = $todayStmt->fetch();

$completedClockIn = '--:--';
$completedClockOut = '--:--';
$completedDuration = '--';
$completedStatus = '';
if ($completedToday) {
    $cIn = strlen($completedToday['clock_in']) <= 8 ? ($completedToday['date'] . ' ' . $completedToday['clock_in']) : $completedToday['clock_in'];
    $cOut = strlen($completedToday['clock_out']) <= 8 ? ($completedToday['date'] . ' ' . $completedToday['clock_out']) : $completedToday['clock_out'];
    $completedClockIn = formatISTTime($cIn);
    $completedClockOut = formatISTTime($cOut);
    $completedDuration = formatDuration((int)$completedToday['total_seconds']);
    $completedStatus = $completedToday['status'] ?? 'completed';
}

// Recent 5 shifts history for this employee
$histStmt = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) ORDER BY date DESC, id DESC LIMIT 5");
$histStmt->execute([$userId, $userId]);
$recentShifts = $histStmt->fetchAll() ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#0f172a">
    <title>Mobile Shift In/Out - <?= sanitize($userName) ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Google Fonts Outfit & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-card: rgba(255, 255, 255, 0.96);
            --border-glass: rgba(226, 232, 240, 0.85);
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            padding-bottom: calc(40px + env(safe-area-inset-bottom, 0px));
            overflow-x: hidden;
            -webkit-tap-highlight-color: transparent;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Outfit', sans-serif;
        }

        /* Top App Navigation Bar */
        .portal-topbar {
            background: #0f172a;
            color: #ffffff;
            padding: calc(14px + env(safe-area-inset-top, 0px)) 16px 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .portal-container {
            max-width: 520px;
            margin: 0 auto;
            padding: 16px 14px;
        }

        /* Luxury Glass Card */
        .portal-card {
            background: #ffffff;
            border: 1px solid var(--border-glass);
            border-radius: 1.25rem;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 6px -1px rgba(0, 0, 0, 0.02);
            padding: 1.25rem;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        .portal-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.85rem;
        }

        /* Employee Profile Card */
        .emp-profile-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
            border-left: 4px solid #4f46e5;
        }

        .emp-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #4f46e5;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
            flex-shrink: 0;
        }

        /* Live Clock & Pulse Banner */
        .clock-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #ffffff;
            text-align: center;
            padding: 1.5rem 1rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .digital-clock {
            font-family: 'Outfit', monospace;
            font-size: 2.25rem;
            font-weight: 800;
            color: #38bdf8;
            letter-spacing: 1px;
            text-shadow: 0 0 20px rgba(56, 189, 248, 0.4);
            line-height: 1.1;
        }

        /* Stopwatch for Active Shift */
        .shift-stopwatch {
            font-family: 'Outfit', monospace;
            font-size: 2.5rem;
            font-weight: 800;
            color: #10b981;
            letter-spacing: 1px;
            line-height: 1.15;
            text-shadow: 0 2px 10px rgba(16, 185, 129, 0.25);
        }

        /* Giant Touch Shift Buttons */
        .btn-shift-action {
            min-height: 70px;
            border-radius: 1.15rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
        }

        .btn-shift-in {
            background: var(--success-gradient);
            color: #ffffff;
            box-shadow: 0 10px 25px -4px rgba(16, 185, 129, 0.45);
        }

        .btn-shift-in:active {
            transform: scale(0.97);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-shift-out {
            background: var(--danger-gradient);
            color: #ffffff;
            box-shadow: 0 10px 25px -4px rgba(239, 68, 68, 0.45);
        }

        .btn-shift-out:active {
            transform: scale(0.97);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-shift-disabled {
            background: #94a3b8 !important;
            cursor: not-allowed;
            box-shadow: none !important;
            opacity: 0.85;
        }

        .shift-btn-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        /* Pulse animations */
        @keyframes pulseGreen {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .status-dot-active {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #10b981;
            display: inline-block;
            animation: pulseGreen 2s infinite ease-in-out;
        }

        /* Stat Snapshot Grid */
        .snapshot-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .snapshot-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.85rem;
            padding: 0.75rem 0.85rem;
        }

        .snapshot-label {
            font-size: 0.725rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .snapshot-value {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
        }

        /* History Items */
        .history-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .history-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
    </style>
</head>
<body>

    <!-- TOP PORTAL HEADER -->
    <header class="portal-topbar">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-primary rounded-pill px-2.5 py-1 fs-9 fw-bold">
                    <i class="fas fa-shield-halved me-1"></i>Secure
                </span>
                <span class="fw-bold font-heading fs-6 text-nowrap">Mobile Shift Portal</span>
            </div>
            <div class="d-flex gap-1.5 align-items-center">
                <a href="<?= BASE_URL ?>/dashboard" class="btn btn-sm btn-outline-light rounded-pill px-2.5 py-1 fs-8 fw-semibold">
                    <i class="fas fa-arrow-left me-1"></i>Dashboard
                </a>
                <a href="<?= BASE_URL ?>/logout" class="btn btn-sm btn-danger rounded-pill px-2.5 py-1 fs-8 fw-semibold">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </header>

    <div class="portal-container">

        <!-- 1. EMPLOYEE PROFILE CHIP -->
        <div class="portal-card emp-profile-card">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= $userAvatar ?>" alt="Avatar" class="emp-avatar">
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex align-items-center gap-2 mb-0.5">
                        <h5 class="fw-bold text-dark mb-0 font-heading text-truncate"><?= sanitize($userName) ?></h5>
                        <span class="badge bg-success-subtle text-success border border-success px-2 py-0.5 rounded-pill fs-9 fw-bold">
                            Authorized
                        </span>
                    </div>
                    <div class="small text-secondary text-truncate mb-1">
                        <i class="fas fa-briefcase text-primary me-1"></i><?= sanitize($userRoleTitle) ?>
                    </div>
                    <div class="fs-9 text-muted font-monospace">
                        <i class="fas fa-id-badge text-info me-1"></i>ID: #<?= $userId ?> &bull; <?= sanitize($userEmail) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. LIVE DIGITAL IST CLOCK & SCHEDULE -->
        <div class="portal-card clock-card">
            <small class="text-uppercase tracking-wider text-slate-300 fs-9 d-block mb-1" style="color: #94a3b8; letter-spacing: 1px;">
                <i class="fas fa-clock text-info me-1"></i>Server Time (IST &bull; GMT+5:30)
            </small>
            <div class="digital-clock" id="liveDigitalClock">00:00:00 AM</div>
            <div class="fs-8 mt-1 text-slate-300" style="color: #cbd5e1;" id="liveDateDisplay">
                <?= formatISTDate($today) ?>
            </div>
            <div class="mt-2.5 pt-2 border-top border-secondary-subtle small text-slate-400 d-flex justify-content-between align-items-center fs-8" style="color: #94a3b8;">
                <span><i class="fas fa-business-time me-1 text-primary"></i>Target: <strong><?= $shiftStartTarget ?> &ndash; <?= $shiftEndTarget ?></strong></span>
                <span><i class="fas fa-utensils me-1 text-warning"></i>Lunch: <strong><?= $lunchStart ?>-<?= $lunchEnd ?></strong></span>
            </div>
        </div>

        <!-- 3. CURRENT LIVE SHIFT STATUS CARD -->
        <div class="portal-card" id="shiftStatusCard">
            <div class="portal-card-header">
                <div class="d-flex align-items-center gap-2">
                    <?php if ($isShiftRunning): ?>
                        <span class="status-dot-active"></span>
                        <strong class="text-dark fs-7 font-heading">Shift In Progress</strong>
                    <?php elseif ($completedToday): ?>
                        <i class="fas fa-check-circle text-primary fs-5"></i>
                        <strong class="text-dark fs-7 font-heading">Shift Completed Today</strong>
                    <?php else: ?>
                        <i class="fas fa-circle-dot text-secondary fs-5"></i>
                        <strong class="text-dark fs-7 font-heading">Not Shifted In Yet</strong>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if ($isShiftRunning): ?>
                        <span class="badge bg-success text-white px-2.5 py-1 rounded-pill fs-8 fw-bold">
                            <i class="fas fa-play me-1"></i>Active Shift
                        </span>
                    <?php elseif ($completedToday): ?>
                        <span class="badge bg-primary-subtle text-primary border border-primary px-2.5 py-1 rounded-pill fs-8 fw-bold">
                            Completed Today
                        </span>
                    <?php else: ?>
                        <span class="badge bg-light text-secondary border px-2.5 py-1 rounded-pill fs-8 fw-semibold">
                            Shift Offline
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Live Session Stopwatch when Active -->
            <?php if ($isShiftRunning): ?>
                <div class="text-center py-2 mb-3 bg-light rounded-3 border">
                    <small class="text-uppercase text-muted fw-bold fs-9 d-block mb-0.5" style="letter-spacing: 0.5px;">Current Session Duration</small>
                    <div class="shift-stopwatch" id="liveShiftStopwatch">
                        <?= formatSecondsToHMS($elapsedSeconds) ?>
                    </div>
                    <small class="text-secondary fs-8 mt-1 d-block">
                        <i class="fas fa-hourglass-half me-1 text-warning"></i>Target: 8 Hours 50 Minutes
                    </small>
                </div>
            <?php endif; ?>

            <!-- Today's Attendance Metrics Snapshot -->
            <div class="snapshot-grid">
                <div class="snapshot-item">
                    <div class="snapshot-label"><i class="fas fa-sign-in-alt text-success me-1"></i>Shift In Time</div>
                    <div class="snapshot-value text-success font-monospace" id="displayShiftInTime">
                        <?= $isShiftRunning ? $clockInTimeOnly : ($completedToday ? $completedClockIn : '--:--') ?>
                    </div>
                </div>
                <div class="snapshot-item">
                    <div class="snapshot-label"><i class="fas fa-sign-out-alt text-danger me-1"></i>Shift Out Time</div>
                    <div class="snapshot-value text-danger font-monospace" id="displayShiftOutTime">
                        <?= $completedToday ? $completedClockOut : ($isShiftRunning ? 'Running...' : '--:--') ?>
                    </div>
                </div>
                <div class="snapshot-item">
                    <div class="snapshot-label"><i class="fas fa-stopwatch text-primary me-1"></i>Worked Duration</div>
                    <div class="snapshot-value text-primary font-monospace" id="displayWorkedDuration">
                        <?= $isShiftRunning ? formatDuration($elapsedSeconds) : ($completedToday ? $completedDuration : '0m') ?>
                    </div>
                </div>
                <div class="snapshot-item">
                    <div class="snapshot-label"><i class="fas fa-calendar-check text-warning me-1"></i>Punctuality</div>
                    <div class="snapshot-value" id="displayPunctuality">
                        <?php if ($isShiftRunning): ?>
                            <?= $isLate ? "<span class='text-danger fs-8 fw-bold'>{$lateMinutes}m Late</span>" : "<span class='text-success fs-8 fw-bold'>On Time</span>" ?>
                        <?php elseif ($completedToday): ?>
                            <?= ((int)($completedToday['late_minutes'] ?? 0) > 0) ? "<span class='text-danger fs-8 fw-bold'>" . (int)$completedToday['late_minutes'] . "m Late</span>" : "<span class='text-success fs-8 fw-bold'>On Time</span>" ?>
                        <?php else: ?>
                            <span class="text-muted fs-8 fw-semibold">Pending</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. PRIMARY TOUCH ACTION BUTTON (SHIFT IN / SHIFT OUT - WI-FI RESTRICTED) -->
        <div class="mb-4">
            <!-- Unauthorized Fallback Card -->
            <div class="wifi-unauth-notice p-4 text-center rounded-4 bg-danger-subtle text-danger border border-danger shadow-sm <?= $isClientIpAuth ? 'd-none' : 'd-block' ?>"
                 style="<?= $isClientIpAuth ? 'display:none !important;' : '' ?>">
                <i class="fas fa-wifi-slash fa-2x mb-2 text-danger"></i>
                <div class="fw-bold fs-6 text-danger">Office Wi-Fi Required</div>
                <div class="small text-secondary mt-1">Please connect your phone to authorized office Wi-Fi to start or end your shift.</div>
                <div class="mt-2"><span class="badge bg-light text-dark font-monospace border fs-9">Your IP: <?= htmlspecialchars($clientIp) ?></span></div>
            </div>

            <!-- Authorized Touch Shift Button -->
            <div class="wifi-restricted-btn <?= $isClientIpAuth ? 'd-block' : 'd-none' ?>"
                 style="<?= $isClientIpAuth ? '' : 'display:none !important;' ?>">
                <?php if (!$isShiftRunning): ?>
                    <!-- BIG SHIFT IN BUTTON -->
                    <button type="button" 
                            id="mainShiftActionBtn" 
                            class="btn-shift-action btn-shift-in w-100" 
                            onclick="triggerMobileShiftAction('clock_in')">
                        <div class="shift-btn-icon"><i class="fas fa-fingerprint"></i></div>
                        <div class="text-start flex-grow-1">
                            <div class="fs-5 line-height-1 mb-1">
                                <?= $completedToday ? 'Resume / Shift In' : 'Shift In (Clock In)' ?>
                            </div>
                            <div class="fs-8 opacity-75 fw-normal text-nowrap">Tap to record official start timestamp</div>
                        </div>
                        <i class="fas fa-chevron-right opacity-75 fs-6"></i>
                    </button>
                <?php else: ?>
                    <!-- BIG SHIFT OUT BUTTON -->
                    <button type="button" 
                            id="mainShiftActionBtn" 
                            class="btn-shift-action btn-shift-out w-100" 
                            onclick="triggerMobileShiftAction('clock_out')">
                        <div class="shift-btn-icon"><i class="fas fa-sign-out-alt"></i></div>
                        <div class="text-start flex-grow-1">
                            <div class="fs-5 line-height-1 mb-1">Shift Out (Clock Out)</div>
                            <div class="fs-8 opacity-75 fw-normal text-nowrap">End shift & save total worked duration</div>
                        </div>
                        <i class="fas fa-chevron-right opacity-75 fs-6"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- 5. RECENT 5 ATTENDANCE RECORDS -->
        <div class="portal-card">
            <div class="portal-card-header mb-2 pb-2 border-bottom">
                <h6 class="fw-bold text-dark mb-0 font-heading">
                    <i class="fas fa-history text-primary me-2"></i>Recent Attendance History
                </h6>
                <span class="badge bg-light text-secondary border fs-9">Last 5 Shifts</span>
            </div>

            <?php if (!empty($recentShifts)): ?>
                <div class="d-flex flex-column">
                    <?php foreach ($recentShifts as $rs): 
                        $rDate = formatISTDate($rs['date']);
                        $rIn = strlen($rs['clock_in']) <= 8 ? ($rs['date'] . ' ' . $rs['clock_in']) : $rs['clock_in'];
                        $rInTime = formatISTTime($rIn);
                        $rOutTime = !empty($rs['clock_out']) ? formatISTTime(strlen($rs['clock_out']) <= 8 ? ($rs['date'] . ' ' . $rs['clock_out']) : $rs['clock_out']) : 'Running...';
                        $rDuration = !empty($rs['total_seconds']) ? formatDuration((int)$rs['total_seconds']) : ($rs['clock_out'] ? '0m' : 'In Progress');
                        $rStatus = $rs['status'] ?? 'present';
                        $statusBadge = ($rStatus === 'full_day') ? 'bg-success text-white' : (($rStatus === 'half_day') ? 'bg-warning text-dark' : 'bg-info text-white');
                    ?>
                        <div class="history-item">
                            <div>
                                <div class="fw-bold text-dark fs-7"><?= $rDate ?></div>
                                <small class="text-secondary fs-8">
                                    <i class="fas fa-clock text-primary me-1"></i><?= $rInTime ?> &rarr; <?= $rOutTime ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge <?= $statusBadge ?> rounded-pill fs-9 px-2 py-0.5 fw-bold mb-0.5 d-inline-block">
                                    <?= ucfirst(str_replace('_', ' ', $rStatus)) ?>
                                </span>
                                <div class="font-monospace text-dark fs-8 fw-bold"><?= $rDuration ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-3 text-muted small">
                    <i class="fas fa-calendar-check text-secondary fs-4 mb-1 d-block opacity-50"></i>
                    No previous attendance records recorded.
                </div>
            <?php endif; ?>
        </div>

        <!-- 6. FOOTER SECURITY NOTICE & DASHBOARD LINK -->
        <div class="text-center pt-2">
            <p class="text-muted fs-9 mb-2">
                <i class="fas fa-lock text-success me-1"></i>Encrypted Server-Generated Timestamps &bull; WorkFlow Pro
            </p>
            <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-secondary btn-sm rounded-pill px-4 fw-semibold">
                <i class="fas fa-arrow-left me-1"></i>Back to Full Dashboard
            </a>
        </div>

    </div>

    <!-- CLIENT SCRIPT -->
    <script>
        const BASE_URL = '<?= BASE_URL ?>';
        const CSRF_TOKEN = '<?= getCsrfToken() ?>';
        let isShiftActive = <?= $isShiftRunning ? 'true' : 'false' ?>;
        let elapsedSeconds = <?= (int)$elapsedSeconds ?>;
        let isProcessingRequest = false;

        // 1. Real-time Digital Clock (IST)
        function updateLiveClock() {
            const now = new Date();
            // Calculate IST time (UTC + 5.5 hours)
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const istTime = new Date(utc + (3600000 * 5.5));

            let hours = istTime.getHours();
            let minutes = istTime.getMinutes();
            let seconds = istTime.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12; // 12-hour format
            const strHours = String(hours).padStart(2, '0');
            const strMinutes = String(minutes).padStart(2, '0');
            const strSeconds = String(seconds).padStart(2, '0');

            const clockEl = document.getElementById('liveDigitalClock');
            if (clockEl) {
                clockEl.textContent = `${strHours}:${strMinutes}:${strSeconds} ${ampm}`;
            }

            // If shift is running, also tick the live stopwatch
            if (isShiftActive) {
                elapsedSeconds++;
                const hours = Math.floor(elapsedSeconds / 3600);
                const mins = Math.floor((elapsedSeconds % 3600) / 60);
                const secs = elapsedSeconds % 60;
                const stopwatchEl = document.getElementById('liveShiftStopwatch');
                if (stopwatchEl) {
                    stopwatchEl.textContent = `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                }
                const workedEl = document.getElementById('displayWorkedDuration');
                if (workedEl) {
                    workedEl.textContent = `${hours}h ${mins}m`;
                }
            }
        }
        setInterval(updateLiveClock, 1000);
        updateLiveClock();

        // 2. Trigger Mobile Shift Action (Shift In / Shift Out)
        function triggerMobileShiftAction(action) {
            if (isProcessingRequest) return;

            const isClockIn = (action === 'clock_in' || action === 'start');
            const apiAction = isClockIn ? 'mobile_clock_in' : 'mobile_clock_out';

            const proceedWithAction = () => {
                const btn = document.getElementById('mainShiftActionBtn');
                isProcessingRequest = true;

                if (btn) {
                    btn.classList.add('btn-shift-disabled');
                    btn.disabled = true;
                    btn.innerHTML = `
                        <div class="spinner-border spinner-border-sm text-light me-2" role="status"></div>
                        <div class="text-start flex-grow-1">
                            <div class="fs-5 line-height-1 mb-1">Recording Timestamp...</div>
                            <div class="fs-8 opacity-75 fw-normal">Saving server attendance entry</div>
                        </div>
                    `;
                }

                // Send POST to /api/attendance with mobile source & CSRF token
                fetch(resolveApiUrl('/api/attendance'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `action=${encodeURIComponent(apiAction)}&source=mobile&is_mobile=1&csrf_token=${encodeURIComponent(CSRF_TOKEN)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Haptic feedback if available on mobile
                        if (navigator.vibrate) {
                            navigator.vibrate([100, 50, 150]);
                        }

                        Swal.fire({
                            icon: 'success',
                            title: isClockIn ? 'Shift Started!' : 'Shift Ended!',
                            text: data.message || (isClockIn ? 'Shift In recorded successfully!' : 'Shift Out recorded successfully!'),
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            willClose: () => {
                                window.location.reload();
                            }
                        });
                        setTimeout(() => { window.location.reload(); }, 2000);
                    } else {
                        isProcessingRequest = false;
                        if (btn) {
                            btn.classList.remove('btn-shift-disabled');
                            btn.disabled = false;
                            btn.innerHTML = isClockIn ? `
                                <div class="shift-btn-icon"><i class="fas fa-fingerprint"></i></div>
                                <div class="text-start flex-grow-1">
                                    <div class="fs-5 line-height-1 mb-1">Shift In (Clock In)</div>
                                    <div class="fs-8 opacity-75 fw-normal">Tap to record official start timestamp</div>
                                </div>
                                <i class="fas fa-chevron-right opacity-75 fs-6"></i>
                            ` : `
                                <div class="shift-btn-icon"><i class="fas fa-sign-out-alt"></i></div>
                                <div class="text-start flex-grow-1">
                                    <div class="fs-5 line-height-1 mb-1">Shift Out (Clock Out)</div>
                                    <div class="fs-8 opacity-75 fw-normal">End shift & save total worked duration</div>
                                </div>
                                <i class="fas fa-chevron-right opacity-75 fs-6"></i>
                            `;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Attendance Error',
                            text: data.message || 'Unable to update shift attendance.'
                        });
                    }
                })
                .catch(err => {
                    isProcessingRequest = false;
                    if (btn) {
                        btn.classList.remove('btn-shift-disabled');
                        btn.disabled = false;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error',
                        text: 'Failed to connect to the server. Please check your connection and try again.'
                    });
                });
            };

            // Confirmation modal before Clock Out
            if (!isClockIn) {
                Swal.fire({
                    title: 'End Shift Today?',
                    text: 'This will record your departure timestamp and calculate your total worked hours for today.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Shift Out Now',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        proceedWithAction();
                    }
                });
            } else {
                proceedWithAction();
            }
        }

        // Periodic Wi-Fi IP validation on Mobile
        function checkMobileIpAuthorization() {
            fetch(resolveApiUrl('/api/attendance?action=check_ip'))
                .then(r => r.json())
                .then(res => {
                    if (res.success && res.data) {
                        const isAuth = Boolean(res.data.ip_authorized);
                        const restrictedBtns = document.querySelectorAll('.wifi-restricted-btn');
                        const unauthNotices = document.querySelectorAll('.wifi-unauth-notice');

                        restrictedBtns.forEach(el => {
                            if (isAuth) {
                                el.style.removeProperty('display');
                                el.classList.remove('d-none');
                                el.classList.add('d-block');
                            } else {
                                el.style.setProperty('display', 'none', 'important');
                                el.classList.add('d-none');
                                el.classList.remove('d-block');
                            }
                        });

                        unauthNotices.forEach(el => {
                            if (isAuth) {
                                el.style.setProperty('display', 'none', 'important');
                                el.classList.add('d-none');
                                el.classList.remove('d-block');
                            } else {
                                el.style.removeProperty('display');
                                el.classList.remove('d-none');
                                el.classList.add('d-block');
                            }
                        });
                    }
                })
                .catch(() => {});
        }

        setInterval(checkMobileIpAuthorization, 20000);
        window.addEventListener('online', checkMobileIpAuthorization);
        window.addEventListener('focus', checkMobileIpAuthorization);
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) checkMobileIpAuthorization();
        });
    </script>
</body>
</html>
