<?php
/**
 * Live Team Status for Boss / Admin - WorkFlow Pro
 * Provides real-time team monitor cards, dynamic summary counters, filters, "Call to Meeting" modal,
 * and direct links to messaging, tasks, and detailed employee activity timelines.
 */

$pageTitle = "Live Team Status";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$canManage = hasRole(['admin', 'manager', 'product_manager']) || canCreateOrEditTasks();
$db = Database::getConnection();
ensureShiftAndActivitySchema($db);
autoCloseOverdueShifts($db);

$today = date('Y-m-d');
$settings = getShiftSettings($db);

// Fetch departments & projects for filter controls
$departments = $db->query("SELECT id, name FROM departments ORDER BY name ASC")->fetchAll();
$projects = $db->query("SELECT id, name FROM projects WHERE status = 'active' ORDER BY name ASC")->fetchAll();
$allEmployees = $db->query("SELECT id, name, designation, avatar FROM users WHERE status = 'active' ORDER BY name ASC")->fetchAll();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>
<!-- ========================================================================== -->
<!-- LIVE TEAM STATUS - LUXURY SAAS EMPLOYEE MONITOR STYLES                    -->
<!-- Strictly scoped to Live Team Status Page Only                              -->
<!-- ========================================================================== -->
<style id="liveTeamStatusCustomStyles">
/* ========================================================================== */
/* LIVE TEAM STATUS - LUXURY SAAS EMPLOYEE MONITOR STYLES                    */
/* Strictly Scoped to Live Team Status Page Only                              */
/* ========================================================================== */

/* Main Card Architecture */
.live-emp-card {
    background: #ffffff !important;
    border: 1px solid rgba(226, 232, 240, 0.95) !important;
    border-radius: 20px !important;
    box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.04), 0 2px 6px -1px rgba(15, 23, 42, 0.02) !important;
    transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease !important;
    padding: 1.25rem !important;
    overflow: hidden !important;
    position: relative !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: space-between !important;
}

.live-emp-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 18px 36px -6px rgba(15, 23, 42, 0.09), 0 6px 14px -2px rgba(15, 23, 42, 0.03) !important;
}

/* Status Dynamic Top Accent Line */
.live-emp-accent-bar {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #94a3b8;
    border-radius: 20px 20px 0 0;
    z-index: 2;
}

.live-emp-card.status-working .live-emp-accent-bar { background: linear-gradient(90deg, #10b981 0%, #34d399 50%, #6ee7b7 100%); }
.live-emp-card.status-in-meeting .live-emp-accent-bar { background: linear-gradient(90deg, #8b5cf6 0%, #a855f7 50%, #c084fc 100%); }
.live-emp-card.status-lunch .live-emp-accent-bar { background: linear-gradient(90deg, #f97316 0%, #fb923c 50%, #fed7aa 100%); }
.live-emp-card.status-waiting .live-emp-accent-bar { background: linear-gradient(90deg, #eab308 0%, #facc15 50%, #fef08a 100%); }
.live-emp-card.status-blocked .live-emp-accent-bar { background: linear-gradient(90deg, #ef4444 0%, #f87171 50%, #fca5a5 100%); }
.live-emp-card.status-shift-ended .live-emp-accent-bar { background: linear-gradient(90deg, #334155 0%, #64748b 100%); }
.live-emp-card.status-offline .live-emp-accent-bar { background: linear-gradient(90deg, #94a3b8 0%, #cbd5e1 100%); }

/* Status Hover Borders & Ambient Accents */
.live-emp-card.status-working:hover { border-color: rgba(16, 185, 129, 0.45) !important; }
.live-emp-card.status-in-meeting:hover { border-color: rgba(139, 92, 246, 0.45) !important; }
.live-emp-card.status-lunch:hover { border-color: rgba(249, 115, 22, 0.45) !important; }
.live-emp-card.status-waiting:hover { border-color: rgba(234, 179, 8, 0.45) !important; }
.live-emp-card.status-blocked:hover { border-color: rgba(239, 68, 68, 0.45) !important; }
.live-emp-card.status-shift-ended:hover { border-color: rgba(51, 65, 85, 0.45) !important; }
.live-emp-card.status-offline:hover { border-color: rgba(148, 163, 184, 0.45) !important; }

/* Blocked Status Emergency Pulse */
.live-emp-card.status-blocked {
    border-color: rgba(239, 68, 68, 0.3) !important;
}

/* Top Meta Bar (Status Pill + Shift Pill) */
.live-top-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
}

/* Status Pill with Pulsing Live Dot */
.live-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6.5px;
    padding: 3.5px 10px;
    border-radius: 9999px;
    font-size: 0.70rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    line-height: 1.2;
}

.live-status-pill.status-working { background: rgba(16, 185, 129, 0.1); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); }
.live-status-pill.status-in-meeting { background: rgba(139, 92, 246, 0.1); color: #6d28d9; border: 1px solid rgba(139, 92, 246, 0.3); }
.live-status-pill.status-lunch { background: rgba(249, 115, 22, 0.1); color: #c2410c; border: 1px solid rgba(249, 115, 22, 0.3); }
.live-status-pill.status-waiting { background: rgba(234, 179, 8, 0.12); color: #a16207; border: 1px solid rgba(234, 179, 8, 0.35); }
.live-status-pill.status-blocked { background: rgba(239, 68, 68, 0.1); color: #b91c1c; border: 1px solid rgba(239, 68, 68, 0.35); }
.live-status-pill.status-shift-ended { background: #f8fafc; color: #475569; border: 1px solid rgba(100, 116, 139, 0.25); }
.live-status-pill.status-offline { background: #f1f5f9; color: #64748b; border: 1px solid rgba(148, 163, 184, 0.25); }

/* Live Pulsing Dot Animation */
.live-status-dot {
    width: 7.5px;
    height: 7.5px;
    border-radius: 50%;
    display: inline-block;
    position: relative;
    flex-shrink: 0;
}

.status-working .live-status-dot { background: #10b981; }
.status-in-meeting .live-status-dot { background: #8b5cf6; }
.status-lunch .live-status-dot { background: #f97316; }
.status-waiting .live-status-dot { background: #eab308; }
.status-blocked .live-status-dot { background: #ef4444; }
.status-shift-ended .live-status-dot { background: #475569; }
.status-offline .live-status-dot { background: #94a3b8; }

.live-status-dot.pulsing::after {
    content: '';
    position: absolute;
    top: -2.5px;
    left: -2.5px;
    right: -2.5px;
    bottom: -2.5px;
    border-radius: 50%;
    animation: livePulseRadar 1.8s infinite ease-out;
}

.status-working .live-status-dot.pulsing::after { border: 2px solid #10b981; }
.status-in-meeting .live-status-dot.pulsing::after { border: 2px solid #8b5cf6; }
.status-waiting .live-status-dot.pulsing::after { border: 2px solid #eab308; }
.status-blocked .live-status-dot.pulsing::after { border: 2px solid #ef4444; }

@keyframes livePulseRadar {
    0% { transform: scale(1); opacity: 0.95; }
    70% { transform: scale(2.4); opacity: 0; }
    100% { transform: scale(2.4); opacity: 0; }
}

/* Shift Tag on Top Right */
.live-shift-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3.5px 9px;
    border-radius: 9999px;
    font-size: 0.70rem;
    font-weight: 600;
    white-space: nowrap;
    line-height: 1.2;
}

.live-shift-tag.running {
    background: rgba(16, 185, 129, 0.08);
    color: #047857;
    border: 1px solid rgba(16, 185, 129, 0.25);
}

.live-shift-tag.ended {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.live-shift-tag.pending {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #e2e8f0;
}

/* Profile Header Section & Avatar */
.live-profile-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}

.live-avatar-wrapper {
    width: 50px;
    height: 50px;
    position: relative;
    flex-shrink: 0;
}

.live-avatar {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    object-fit: cover;
    border: 2px solid #ffffff;
    box-shadow: 0 3px 10px rgba(15, 23, 42, 0.08);
    background: #f1f5f9;
}

.live-avatar-indicator {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 13px;
    height: 13px;
    border-radius: 50%;
    border: 2.5px solid #ffffff;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
}

.live-avatar-indicator.status-working { background: #10b981; }
.live-avatar-indicator.status-in-meeting { background: #8b5cf6; }
.live-avatar-indicator.status-lunch { background: #f97316; }
.live-avatar-indicator.status-waiting { background: #eab308; }
.live-avatar-indicator.status-blocked { background: #ef4444; }
.live-avatar-indicator.status-shift-ended { background: #334155; }
.live-avatar-indicator.status-offline { background: #94a3b8; }

.live-profile-info {
    flex-grow: 1;
    min-width: 0;
}

.live-emp-name {
    font-size: 0.98rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.25;
    letter-spacing: -0.01em;
    margin-bottom: 2px;
}

.live-emp-role {
    font-size: 0.77rem;
    color: #64748b;
    font-weight: 500;
    line-height: 1.2;
    margin-bottom: 5px;
}

.live-dept-badge {
    display: inline-flex;
    align-items: center;
    background: #f8fafc;
    color: #475569;
    padding: 2px 7.5px;
    border-radius: 6px;
    font-size: 0.68rem;
    font-weight: 600;
    border: 1px solid #e2e8f0;
    line-height: 1.2;
}

/* Active Work Stage Container (Telemetry Box) */
.live-work-stage {
    background: #f8fafc;
    border: 1px solid rgba(226, 232, 240, 0.95);
    border-radius: 14px;
    padding: 12px 13px;
    margin-bottom: 12px;
    position: relative;
}

.work-stage-item {
    position: relative;
}

.work-stage-label {
    display: flex;
    align-items: center;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    color: #64748b;
    text-transform: uppercase;
    margin-bottom: 3px;
}

.work-stage-value {
    font-size: 0.83rem;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.3;
}

.work-stage-value.project-value {
    color: #1e293b;
    font-weight: 600;
    padding-left: 1px;
}

.work-stage-value.task-value {
    color: #4338ca;
    background: rgba(79, 70, 229, 0.06);
    padding: 3.5px 8.5px;
    border-radius: 7px;
    display: inline-block;
    max-width: 100%;
    border: 1px solid rgba(79, 70, 229, 0.12);
    font-size: 0.81rem;
}

/* Work Time Strip (Bilateral Symmetrical Alignment) */
.work-time-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-top: 1px solid rgba(226, 232, 240, 0.9);
    margin-top: 10px;
    padding-top: 10px;
}

.time-stat-item {
    flex: 1;
    min-width: 0;
}

.time-stat-label {
    display: flex;
    align-items: center;
    font-size: 0.64rem;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.05em;
    margin-bottom: 3px;
    text-transform: uppercase;
}

.time-stat-item.text-end .time-stat-label {
    justify-content: flex-end;
}

.time-stat-val {
    font-size: 0.82rem;
    font-weight: 700;
    color: #1e293b;
}

/* Sleek Chronometer Pill */
.duration-counter-box {
    display: inline-flex;
    align-items: center;
    gap: 5.5px;
    background: #0f172a;
    color: #38bdf8;
    border: 1px solid rgba(15, 23, 42, 0.8);
    padding: 3px 8.5px;
    border-radius: 8px;
    font-family: 'JetBrains Mono', Consolas, monospace;
    font-size: 0.77rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.duration-counter-box i {
    color: #38bdf8;
    font-size: 0.72rem;
}

/* Blocker & Waiting Issue Notice */
.live-issue-banner {
    padding: 8px 11px;
    border-radius: 10px;
    font-size: 0.76rem;
    border-left: 3.5px solid transparent;
    margin-top: 10px;
}

.live-issue-banner.issue-danger {
    background: #fef2f2;
    border: 1px solid rgba(239, 68, 68, 0.25);
    border-left: 3.5px solid #ef4444;
    color: #991b1b;
}

.live-issue-banner.issue-warning {
    background: #fffbeb;
    border: 1px solid rgba(245, 158, 11, 0.25);
    border-left: 3.5px solid #f59e0b;
    color: #92400e;
}

.issue-title {
    font-size: 0.77rem;
    font-weight: 700;
    line-height: 1.25;
}

.issue-notes {
    font-size: 0.72rem;
    opacity: 0.9;
    margin-top: 2px;
    line-height: 1.2;
}

/* Attendance & Punctuality Footnote */
.live-attendance-strip {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.75rem;
    color: #64748b;
    margin-bottom: 12px;
    padding: 0 2px;
}

.attendance-time strong {
    color: #1e293b;
}

.punctuality-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2.5px 8px;
    border-radius: 9999px;
    font-size: 0.69rem;
    font-weight: 600;
}

.punctuality-badge.on-time {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.punctuality-badge.late {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid rgba(249, 115, 22, 0.2);
}

/* Action Toolbar */
.live-card-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid rgba(241, 245, 249, 1) !important;
}

.btn-call-direct {
    background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
    color: #ffffff !important;
    border: none !important;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25) !important;
    transition: all 0.22s cubic-bezier(0.16, 1, 0.3, 1) !important;
    font-size: 0.80rem !important;
    font-weight: 600 !important;
    height: 38px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6.5px !important;
    border-radius: 9999px !important;
    padding: 0 16px !important;
    text-decoration: none !important;
}

.btn-call-direct:hover {
    background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 5px 14px rgba(79, 70, 229, 0.38) !important;
    color: #ffffff !important;
}

.btn-action-icon {
    width: 38px !important;
    height: 38px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    font-size: 0.80rem !important;
    border-radius: 50% !important;
    transition: all 0.22s ease !important;
    padding: 0 !important;
    flex-shrink: 0 !important;
    text-decoration: none !important;
}

.btn-action-icon:hover {
    background: #f8fafc !important;
    color: #4f46e5 !important;
    border-color: #cbd5e1 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 3px 8px rgba(15, 23, 42, 0.08) !important;
}

.btn-action-icon.btn-action-timeline:hover {
    background: #eef2ff !important;
    border-color: #c7d2fe !important;
    color: #4338ca !important;
}

/* Mobile Responsive Optimization (Strictly for Live Team Status) */
@media (max-width: 768px) {
    .live-emp-card {
        padding: 1rem !important;
        border-radius: 18px !important;
    }
    
    .live-avatar-wrapper {
        width: 46px !important;
        height: 46px !important;
    }
    
    .live-avatar {
        width: 46px !important;
        height: 46px !important;
        border-radius: 13px !important;
    }
    
    .live-emp-name {
        font-size: 0.93rem !important;
    }
    
    .live-emp-role {
        font-size: 0.75rem !important;
    }
    
    .live-work-stage {
        padding: 10px 11px !important;
    }
    
    .work-stage-value {
        font-size: 0.80rem !important;
    }

    .btn-call-direct {
        height: 38px !important;
        font-size: 0.78rem !important;
    }

    .btn-action-icon {
        width: 38px !important;
        height: 38px !important;
        font-size: 0.78rem !important;
    }

    #statusFilterPills {
        flex-wrap: nowrap !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
        padding-bottom: 6px !important;
        scrollbar-width: none !important;
    }

    #statusFilterPills::-webkit-scrollbar {
        display: none !important;
    }

    .filter-pill-btn {
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }
}

@media (max-width: 420px) {
    .live-card-actions {
        gap: 6px !important;
    }

    .duration-counter-box {
        padding: 2.5px 6.5px !important;
        font-size: 0.72rem !important;
    }

    .time-stat-val {
        font-size: 0.78rem !important;
    }

    .time-stat-label {
        font-size: 0.60rem !important;
    }
}
</style>

    <div class="p-3 p-md-4">
        <!-- Page Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-success-subtle text-success border border-success px-2.5 py-1 rounded-pill fs-8 fw-bold">
                        <i class="fas fa-satellite-dish me-1"></i>Live Team Operations
                    </span>
                    <span class="text-secondary small">&bull;</span>
                    <span class="text-secondary small fw-medium" id="liveAutoRefreshIndicator">
                        <i class="fas fa-sync-alt fa-spin text-primary me-1" id="refreshSpinIcon" style="display:none;"></i>Auto-refreshing every 8s
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-0 font-heading">
                    Live Team Status <span class="fs-6 fw-normal text-muted ms-1">| Real-Time Employee Monitor</span>
                </h3>
            </div>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <?php if (hasRole('admin')): ?>
                <a href="<?= BASE_URL ?>/shift-settings" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="fas fa-clock text-warning me-1"></i>Shift Settings
                </a>
                <?php endif; ?>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3.5 py-1.5 fw-bold shadow-sm d-flex align-items-center gap-1.5" onclick="openCallMeetingModal()">
                    <i class="fas fa-plus-circle"></i>
                    <span>+ Call Employee to Meeting</span>
                </button>
            </div>
        </div>

        <!-- TOP SUMMARY COUNTER CARDS (Section 14) -->
        <div class="row g-2.5 g-md-3 mb-4" id="teamSummaryCounters">
            <!-- TOTAL EMPLOYEES -->
            <div class="col-6 col-sm-4 col-md-3 col-xl">
                <div class="stat-card-luxury cursor-pointer" onclick="applyStatusFilter('all')" style="--card-accent: #4f46e5;">
                    <div class="stat-card-icon" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-dark line-height-1" id="sumTotalEmployees">0</div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Total Team</small>
                    </div>
                </div>
            </div>

            <!-- WORKING (🟢) -->
            <div class="col-6 col-sm-4 col-md-3 col-xl">
                <div class="stat-card-luxury cursor-pointer" onclick="applyStatusFilter('Working')" style="--card-accent: #10b981;">
                    <div class="stat-card-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                        <i class="fas fa-circle-dot"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-success line-height-1" id="sumWorkingEmployees">0</div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Working 🟢</small>
                    </div>
                </div>
            </div>

            <!-- IN MEETING (🟣) -->
            <div class="col-6 col-sm-4 col-md-3 col-xl">
                <div class="stat-card-luxury cursor-pointer" onclick="applyStatusFilter('In Meeting')" style="--card-accent: #8b5cf6;">
                    <div class="stat-card-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                        <i class="fas fa-video"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-purple line-height-1" id="sumMeetingEmployees">0</div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Meeting 🟣</small>
                    </div>
                </div>
            </div>

            <!-- LUNCH (🟠) -->
            <div class="col-6 col-sm-4 col-md-3 col-xl">
                <div class="stat-card-luxury cursor-pointer" onclick="applyStatusFilter('Lunch')" style="--card-accent: #f97316;">
                    <div class="stat-card-icon" style="background: rgba(249, 115, 22, 0.12); color: #f97316;">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-warning line-height-1" id="sumLunchEmployees">0</div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Lunch 🟠</small>
                    </div>
                </div>
            </div>

            <!-- WAITING (🟡) -->
            <div class="col-6 col-sm-4 col-md-3 col-xl">
                <div class="stat-card-luxury cursor-pointer" onclick="applyStatusFilter('Waiting')" style="--card-accent: #eab308;">
                    <div class="stat-card-icon" style="background: rgba(234, 179, 8, 0.12); color: #eab308;">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-warning line-height-1" id="sumWaitingEmployees">0</div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Waiting 🟡</small>
                    </div>
                </div>
            </div>

            <!-- BLOCKED (🔴) -->
            <div class="col-6 col-sm-4 col-md-3 col-xl">
                <div class="stat-card-luxury cursor-pointer" onclick="applyStatusFilter('Blocked')" style="--card-accent: #ef4444;">
                    <div class="stat-card-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-danger line-height-1" id="sumBlockedEmployees">0</div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Blocked 🔴</small>
                    </div>
                </div>
            </div>

            <!-- OFFLINE (⚪) -->
            <div class="col-6 col-sm-4 col-md-3 col-xl">
                <div class="stat-card-luxury cursor-pointer" onclick="applyStatusFilter('Offline')" style="--card-accent: #94a3b8;">
                    <div class="stat-card-icon" style="background: rgba(148, 163, 184, 0.12); color: #94a3b8;">
                        <i class="fas fa-power-off"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold text-secondary line-height-1" id="sumOfflineEmployees">0</div>
                        <small class="text-secondary fw-semibold fs-8 text-uppercase">Offline ⚪</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTERS & SEARCH CONTROLS (Section 15) -->
        <div class="glass-card mb-4 p-3 border-0 shadow-sm">
            <div class="row g-2 align-items-center">
                <!-- Status Pills Filter -->
                <div class="col-12 col-xl-6">
                    <div class="d-flex flex-wrap gap-1" id="statusFilterPills">
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fs-8 filter-pill-btn active" data-filter="all" onclick="applyStatusFilter('all')">
                            All Employees
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2.5 py-1 fs-8 filter-pill-btn" data-filter="Working" onclick="applyStatusFilter('Working')">
                            🟢 Working
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-purple rounded-pill px-2.5 py-1 fs-8 filter-pill-btn" data-filter="In Meeting" onclick="applyStatusFilter('In Meeting')">
                            🟣 Meeting
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 fs-8 filter-pill-btn" data-filter="Lunch" onclick="applyStatusFilter('Lunch')">
                            🟠 Lunch
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2.5 py-1 fs-8 filter-pill-btn" data-filter="Waiting" onclick="applyStatusFilter('Waiting')">
                            🟡 Waiting
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1 fs-8 filter-pill-btn" data-filter="Blocked" onclick="applyStatusFilter('Blocked')">
                            🔴 Blocked
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-2.5 py-1 fs-8 filter-pill-btn" data-filter="Offline" onclick="applyStatusFilter('Offline')">
                            ⚪ Offline
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-2.5 py-1 fs-8 filter-pill-btn" data-filter="Shift Ended" onclick="applyStatusFilter('Shift Ended')">
                            ⚫ Shift Ended
                        </button>
                    </div>
                </div>

                <!-- Department Filter Dropdown -->
                <div class="col-6 col-md-4 col-xl-2">
                    <select id="filterDepartmentSelect" class="form-select form-select-sm rounded-pill" onchange="filterEmployeeCards()">
                        <option value="all">All Departments</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?= $dept['id'] ?>"><?= sanitize($dept['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Project Filter Dropdown -->
                <div class="col-6 col-md-4 col-xl-2">
                    <select id="filterProjectSelect" class="form-select form-select-sm rounded-pill" onchange="filterEmployeeCards()">
                        <option value="all">All Projects</option>
                        <?php foreach ($projects as $prj): ?>
                            <option value="<?= htmlspecialchars($prj['name']) ?>"><?= sanitize($prj['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="col-12 col-md-4 col-xl-2">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="employeeSearchInput" class="form-control border-start-0 rounded-end-pill" placeholder="Search employee..." oninput="filterEmployeeCards()">
                    </div>
                </div>
            </div>
        </div>

        <!-- EMPLOYEE STATUS CARDS GRID (Section 5) -->
        <div class="row g-3.5" id="employeeCardsGrid">
            <div class="col-12 text-center py-5 text-muted">
                <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                Loading live team statuses...
            </div>
        </div>
    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL: CALL EMPLOYEE TO MEETING (Section 6)               -->
<!-- ========================================================= -->
<div class="modal fade" id="callMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white p-3" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-video me-2 text-warning"></i>Call Employee to Meeting
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="callMeetingForm" onsubmit="submitCallMeeting(event)">
                <div class="modal-body p-4">
                    <p class="text-secondary small mb-3">Send a live invitation to join a meeting. The employee will receive an instant high-priority popup alert.</p>
                    
                    <div class="mb-3">
                        <label class="form-label text-dark fw-bold fs-7">Select Employee</label>
                        <select name="employee_id" id="meetingEmployeeSelect" class="form-select form-select-sm" required>
                            <option value="">-- Choose Employee --</option>
                            <?php foreach ($allEmployees as $emp): ?>
                                <option value="<?= $emp['id'] ?>"><?= sanitize($emp['name']) ?> (<?= sanitize($emp['designation']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-7">
                            <label class="form-label text-dark fw-bold fs-7">Meeting Title / Topic</label>
                            <input type="text" name="title" id="meetingTitleInput" class="form-control form-control-sm" placeholder="e.g. Maharani Website Discussion" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label text-secondary small">Meeting Type</label>
                            <select name="meeting_type" class="form-select form-select-sm">
                                <option value="boss" selected>Boss / Management Sync</option>
                                <option value="client">Client Meeting / Call</option>
                                <option value="internal">Team Standup</option>
                                <option value="review">Work Review</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-secondary small">Associated Project (Optional)</label>
                            <select name="project_id" class="form-select form-select-sm">
                                <option value="">-- General / No Project --</option>
                                <?php foreach (($projects ?? []) as $prj): ?>
                                    <option value="<?= $prj['id'] ?>"><?= sanitize($prj['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small">Client Name (Optional)</label>
                            <input type="text" name="client_name" class="form-control form-control-sm" placeholder="e.g. Maharani Client / Krafting">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Meeting Date</label>
                            <input type="date" name="meeting_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Start Time</label>
                            <input type="time" name="start_time" class="form-control form-control-sm" value="<?= date('H:i') ?>" required>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Expected Duration</label>
                            <select name="duration" class="form-select form-select-sm">
                                <option value="15">15 Minutes</option>
                                <option value="30" selected>30 Minutes</option>
                                <option value="45">45 Minutes</option>
                                <option value="60">1 Hour</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Location / Link</label>
                            <input type="url" name="meeting_link" class="form-control form-control-sm" placeholder="https://meet.google.com/..." value="https://meet.google.com/krd-<?= substr(md5(uniqid()), 0, 8) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-secondary small">Description / Agenda (Optional)</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Brief agenda or points to review..."></textarea>
                    </div>
                </div>

                <div class="modal-footer p-3 bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold" id="submitMeetingCallBtn">
                        <i class="fas fa-paper-plane me-1"></i>Send Meeting Invitation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<!-- Custom JavaScript for Live Team Status -->
<script>
let currentLiveEmployees = [];
let currentSelectedFilter = 'all';

document.addEventListener('DOMContentLoaded', () => {
    fetchLiveTeamData();
    setInterval(fetchLiveTeamData, 8000); // 8s dynamic polling
    setInterval(tickCardTimers, 1000); // 1s live duration counter tick

    const urlParams = new URLSearchParams(window.location.search);
    const callId = urlParams.get('call_id');
    const callName = urlParams.get('call_name');
    if (callId) {
        setTimeout(() => {
            callEmployeeDirect(callId, decodeURIComponent(callName || 'Employee'));
        }, 400);
    }
});

function fetchLiveTeamData() {
    const spinIcon = document.getElementById('refreshSpinIcon');
    if (spinIcon) spinIcon.style.display = 'inline-block';

    fetch(resolveApiUrl('/api/work_summary?action=live_team'))
        .then(res => res.json())
        .then(res => {
            if (spinIcon) spinIcon.style.display = 'none';
            if (res.success && res.data) {
                currentLiveEmployees = res.data.employees || [];
                updateSummaryCounters(res.data.counts);
                filterEmployeeCards();
            }
        })
        .catch(err => {
            if (spinIcon) spinIcon.style.display = 'none';
            console.error("Live team fetch error:", err);
        });
}

function updateSummaryCounters(c) {
    if (!c) return;
    document.getElementById('sumTotalEmployees').textContent = c.total || 0;
    document.getElementById('sumWorkingEmployees').textContent = c.working || 0;
    document.getElementById('sumMeetingEmployees').textContent = c.meeting || 0;
    document.getElementById('sumLunchEmployees').textContent = c.lunch || 0;
    document.getElementById('sumWaitingEmployees').textContent = c.waiting || 0;
    document.getElementById('sumBlockedEmployees').textContent = c.blocked || 0;
    document.getElementById('sumOfflineEmployees').textContent = c.offline || 0;
}

function applyStatusFilter(status) {
    currentSelectedFilter = status;
    
    const pills = document.querySelectorAll('.filter-pill-btn');
    pills.forEach(p => {
        if (p.getAttribute('data-filter') === status) {
            p.classList.add('active');
        } else {
            p.classList.remove('active');
        }
    });

    filterEmployeeCards();
}

function filterEmployeeCards() {
    const grid = document.getElementById('employeeCardsGrid');
    if (!grid) return;

    const deptFilter = document.getElementById('filterDepartmentSelect').value;
    const projFilter = document.getElementById('filterProjectSelect').value.toLowerCase();
    const searchVal = document.getElementById('employeeSearchInput').value.toLowerCase().trim();

    const filtered = currentLiveEmployees.filter(emp => {
        // Status filter
        if (currentSelectedFilter !== 'all') {
            if (emp.status_key.toLowerCase() !== currentSelectedFilter.toLowerCase()) {
                return false;
            }
        }
        // Dept filter
        if (deptFilter !== 'all' && String(emp.department_id) !== String(deptFilter)) {
            return false;
        }
        // Project filter
        if (projFilter !== 'all' && !emp.current_project.toLowerCase().includes(projFilter)) {
            return false;
        }
        // Search query
        if (searchVal && !emp.name.toLowerCase().includes(searchVal) && !emp.designation.toLowerCase().includes(searchVal)) {
            return false;
        }
        return true;
    });

    if (filtered.length === 0) {
        grid.innerHTML = '<div class="col-12 text-center py-5 text-muted small"><i class="fas fa-users-slash fs-3 d-block mb-2 text-secondary"></i>No employees found matching the selected filters.</div>';
        return;
    }

        let html = '';
    filtered.forEach(emp => {
        const isWaiting = (emp.status_key === 'Waiting');
        const isBlocked = (emp.status_key === 'Blocked');
        const isWorking = (emp.status_key === 'Working');

        const statusKey = (emp.status_key || 'Offline').toLowerCase();
        let statusClass = 'offline';
        let isPulse = false;

        if (statusKey === 'working') {
            statusClass = 'working';
            isPulse = true;
        } else if (statusKey === 'in meeting' || statusKey === 'meeting') {
            statusClass = 'in-meeting';
            isPulse = true;
        } else if (statusKey === 'lunch') {
            statusClass = 'lunch';
        } else if (statusKey === 'waiting') {
            statusClass = 'waiting';
            isPulse = true;
        } else if (statusKey === 'blocked') {
            statusClass = 'blocked';
            isPulse = true;
        } else if (statusKey === 'shift ended') {
            statusClass = 'shift-ended';
        } else {
            statusClass = 'offline';
        }

        let shiftPillClass = 'pending';
        let shiftIcon = 'far fa-circle';
        if (emp.is_shift_active) {
            shiftPillClass = 'running';
            shiftIcon = 'fas fa-bolt';
        } else if (emp.shift_status_text === 'Shift Ended') {
            shiftPillClass = 'ended';
            shiftIcon = 'fas fa-moon';
        }

        html += `
            <div class="col-12 col-md-6 col-lg-4 col-xl-3 employee-status-card-col mb-3">
                <div class="live-emp-card status-${statusClass} h-100 position-relative">
                    <!-- Top Dynamic Accent Bar -->
                    <div class="live-emp-accent-bar"></div>

                    <div class="live-emp-body">
                        <!-- Top Meta Bar (Status + Shift) -->
                        <div class="live-top-meta">
                            <div class="live-status-pill status-${statusClass}">
                                <span class="live-status-dot ${isPulse ? 'pulsing' : ''}"></span>
                                <span>${escapeHtml(emp.status_display)}</span>
                            </div>
                            <div class="live-shift-tag ${shiftPillClass}">
                                <i class="${shiftIcon} me-1"></i>
                                <span>${escapeHtml(emp.shift_status_text)}</span>
                            </div>
                        </div>

                        <!-- Profile Header -->
                        <div class="live-profile-header">
                            <div class="live-avatar-wrapper">
                                <img src="${emp.avatar}" alt="${escapeHtml(emp.name)}" class="live-avatar" width="50" height="50" loading="lazy" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('${escapeJs(emp.name)}') + '&background=6366f1&color=fff&size=96';">
                                <span class="live-avatar-indicator status-${statusClass}"></span>
                            </div>
                            <div class="live-profile-info">
                                <h5 class="live-emp-name text-truncate" title="${escapeHtml(emp.name)}">
                                    ${escapeHtml(emp.name)}
                                </h5>
                                <div class="live-emp-role text-truncate" title="${escapeHtml(emp.designation)}">
                                    ${escapeHtml(emp.designation)}
                                </div>
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <span class="live-dept-badge">
                                        <i class="fas fa-layer-group text-primary me-1"></i>${escapeHtml(emp.department_name)}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Work Details Box -->
                        <div class="live-work-stage">
                            <div class="work-stage-item mb-2">
                                <span class="work-stage-label">
                                    <i class="fas fa-folder-closed text-primary me-1.5"></i>PROJECT
                                </span>
                                <div class="work-stage-value project-value text-truncate" title="${escapeHtml(emp.current_project)}">
                                    ${escapeHtml(emp.current_project || 'Internal / General')}
                                </div>
                            </div>

                            <div class="work-stage-item mb-2.5">
                                <span class="work-stage-label">
                                    <i class="fas fa-bolt-lightning text-indigo me-1.5"></i>TASK / ACTIVITY
                                </span>
                                <div class="work-stage-value task-value text-truncate" title="${escapeHtml(emp.current_task)}">
                                    ${escapeHtml(emp.current_task || 'No active task')}
                                </div>
                            </div>

                            <div class="work-time-strip">
                                <div class="time-stat-item">
                                    <span class="time-stat-label">
                                        <i class="far fa-clock me-1 text-muted"></i>STARTED
                                    </span>
                                    <span class="time-stat-val">${escapeHtml(emp.activity_start_time)}</span>
                                </div>
                                <div class="time-stat-item text-end">
                                    <span class="time-stat-label">
                                        <i class="fas fa-stopwatch me-1 text-muted"></i>LIVE DURATION
                                    </span>
                                    <div class="duration-counter-box">
                                        <i class="fas fa-stopwatch"></i>
                                        <span class="live-emp-duration font-monospace fw-bold" data-start="${emp.activity_start_timestamp}">${escapeHtml(emp.current_duration_text)}</span>
                                    </div>
                                </div>
                            </div>

                            ${isWaiting || isBlocked ? `
                                <div class="live-issue-banner ${isBlocked ? 'issue-danger' : 'issue-warning'}">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="fas fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
                                        <div class="min-w-0">
                                            <div class="issue-title text-truncate">${escapeHtml(emp.reason || 'Issue Reported')}</div>
                                            ${emp.notes ? `<div class="issue-notes text-truncate">${escapeHtml(emp.notes)}</div>` : ''}
                                        </div>
                                    </div>
                                </div>
                            ` : ''}
                        </div>

                        <!-- Punctuality & Last Update Footnote -->
                        <div class="live-attendance-strip">
                            <span class="attendance-time">
                                <i class="far fa-calendar-check me-1.5 text-muted"></i>Login: <strong>${escapeHtml(emp.clock_in_time)}</strong>
                            </span>
                            <span class="punctuality-badge ${emp.is_late ? 'late' : 'on-time'}">
                                <i class="${emp.is_late ? 'fas fa-circle-exclamation' : 'fas fa-check-circle'} me-1"></i>${escapeHtml(emp.late_text)}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons Toolbar -->
                    <div class="live-card-actions">
                        <button type="button" class="btn btn-call-direct flex-fill" onclick="callEmployeeDirect(${emp.id}, '${escapeJs(emp.name)}')" title="Instant Meeting Call">
                            <i class="fas fa-video"></i>
                            <span>Call</span>
                        </button>
                        <a href="<?= BASE_URL ?>/chat" class="btn btn-action-icon" title="Send Direct Message">
                            <i class="fas fa-comment-dots"></i>
                        </a>
                        ${emp.task_id ? `
                            <a href="<?= BASE_URL ?>/task-details?id=${emp.task_id}" class="btn btn-action-icon" title="View Task Details">
                                <i class="fas fa-arrow-up-right-from-square"></i>
                            </a>
                        ` : ''}
                        <a href="<?= BASE_URL ?>/employee-timeline?id=${emp.id}" class="btn btn-action-icon btn-action-timeline" title="View Full Employee Timeline">
                            <i class="fas fa-chart-line text-primary"></i>
                        </a>
                    </div>
                </div>
            </div>
        `;
    });

    grid.innerHTML = html;
}

// 1-second live duration clock tick on all active cards
function tickCardTimers() {
    const now = Math.floor(Date.now() / 1000);
    const durationEls = document.querySelectorAll('.live-emp-duration');
    durationEls.forEach(el => {
        const startTs = parseInt(el.getAttribute('data-start') || '0', 10);
        if (startTs > 0) {
            const elapsed = Math.max(0, now - startTs);
            el.textContent = formatSecondsToHMS(elapsed);
        }
    });
}

// Call to Meeting Modal Handling
function openCallMeetingModal() {
    const modal = new bootstrap.Modal(document.getElementById('callMeetingModal'));
    modal.show();
}

function callEmployeeDirect(empId, empName) {
    document.getElementById('meetingEmployeeSelect').value = empId;
    document.getElementById('meetingTitleInput').value = `${empName} Discussion`;
    openCallMeetingModal();
}

function submitCallMeeting(e) {
    e.preventDefault();
    const form = document.getElementById('callMeetingForm');
    const formData = new FormData(form);

    const btn = document.getElementById('submitMeetingCallBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sending...';

    fetch(resolveApiUrl('/api/meetings?action=call_meeting'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Meeting Invitation';

        if (res.success) {
            bootstrap.Modal.getInstance(document.getElementById('callMeetingModal'))?.hide();
            form.reset();
            Swal.fire('Invitation Sent!', res.message, 'success');
            fetchLiveTeamData();
        } else {
            Swal.fire('Notice', res.message, 'warning');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i>Send Meeting Invitation';
        Swal.fire('Error', 'Failed to call meeting: ' + err.message, 'error');
    });
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

function escapeJs(str) {
    if (!str) return '';
    return String(str).replace(/'/g, "\\'");
}

function formatSecondsToHMS(totalSeconds) {
    const secs = Math.max(0, parseInt(totalSeconds, 10) || 0);
    const h = Math.floor(secs / 3600);
    const m = Math.floor((secs % 3600) / 60);
    const s = secs % 60;
    return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
}
</script>
