<?php
/**
 * Office Shift Configuration Page - WorkFlow Pro
 * Centralized Shift Settings section where Admin/Boss configures office timings, lunch hours, grace periods, and meeting settings.
 */

$user = requireAdmin();
$pageTitle = "Office Shift Settings";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$db = Database::getConnection();
ensureShiftAndActivitySchema($db);

$settings = getShiftSettings($db);
$workingDaysArray = explode(',', $settings['working_days'] ?? 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday');
$allDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-3 p-md-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-warning-subtle text-dark border border-warning px-2.5 py-1 rounded-pill fs-8 fw-bold">
                        <i class="fas fa-sliders-h me-1 text-warning"></i>Admin Policy
                    </span>
                    <span class="text-secondary small">&bull;</span>
                    <span class="text-secondary small fw-medium">Centralized Policy Engine</span>
                </div>
                <h3 class="fw-bold text-dark mb-0 font-heading">
                    Office Shift Timing & Settings <span class="fs-6 fw-normal text-muted ms-1">| Company Hours & Breaks</span>
                </h3>
            </div>
            
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= BASE_URL ?>/ip-settings" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                    <i class="fas fa-wifi me-1"></i>Wi-Fi IP Whitelist
                </a>
                <a href="<?= BASE_URL ?>/live-status" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="fas fa-satellite-dish text-success me-1"></i>View Live Team Status
                </a>
            </div>
        </div>

        <div class="row g-4">
            <!-- Settings Form (Left Column) -->
            <div class="col-lg-8">
                <div class="glass-card p-4 border-0 shadow-sm mb-4">
                    <h5 class="fw-bold text-dark mb-3 font-heading">
                        <i class="fas fa-business-time text-primary me-2"></i>Configure Shift & Break Rules
                    </h5>
                    <p class="text-secondary small mb-4">
                        Set company standard office working hours, lunch break windows, late arrival grace period, and activity update intervals.
                    </p>

                    <form id="shiftSettingsForm" onsubmit="submitShiftSettings(event)">
                        <!-- Shift Name -->
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold fs-7">Shift Policy Name</label>
                            <input type="text" name="shift_name" class="form-control form-control-sm" value="<?= sanitize($settings['shift_name']) ?>" required>
                        </div>

                        <!-- 1. Office Working Hours (09:40 AM - 06:30 PM) -->
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <strong class="text-dark fs-7 d-block mb-2">
                                <i class="fas fa-clock text-primary me-1"></i>Daily Shift Timing (Default: 09:40 AM &ndash; 06:30 PM IST)
                            </strong>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-semibold">Shift Start Time (IST)</label>
                                    <input type="time" name="start_time" id="settingStartTime" class="form-control" value="<?= sanitize($settings['start_time']) ?>" required onchange="updateSchedulePreview()">
                                    <small class="text-muted fs-8">Standard: 09:40 AM</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-semibold">Shift End Time (IST)</label>
                                    <input type="time" name="end_time" id="settingEndTime" class="form-control" value="<?= sanitize($settings['end_time']) ?>" required onchange="updateSchedulePreview()">
                                    <small class="text-muted fs-8">Standard: 06:30 PM</small>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Lunch Break Window (02:00 PM - 03:00 PM) -->
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <strong class="text-dark fs-7 d-block mb-2">
                                <i class="fas fa-utensils text-warning me-1"></i>Lunch Break Timing (Default: 02:00 PM &ndash; 03:00 PM IST)
                            </strong>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-semibold">Lunch Start Time (IST)</label>
                                    <input type="time" name="lunch_start" id="settingLunchStart" class="form-control" value="<?= sanitize($settings['lunch_start']) ?>" required onchange="updateSchedulePreview()">
                                    <small class="text-muted fs-8">Standard: 02:00 PM (14:00)</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-semibold">Lunch End Time (IST)</label>
                                    <input type="time" name="lunch_end" id="settingLunchEnd" class="form-control" value="<?= sanitize($settings['lunch_end']) ?>" required onchange="updateSchedulePreview()">
                                    <small class="text-muted fs-8">Standard: 03:00 PM (15:00)</small>
                                </div>
                            </div>

                            <!-- Auto Lunch Toggle (Section 11) -->
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" id="autoLunchSwitch" name="auto_lunch" value="1" <?= $settings['auto_lunch'] ? 'checked' : '' ?>>
                                <label class="form-check-label text-dark fw-semibold fs-7" for="autoLunchSwitch">
                                    Enable Automated Lunch State Transition at 02:00 PM &ndash; 03:00 PM
                                </label>
                                <small class="text-muted d-block fs-8">When enabled, employees will automatically display in Lunch state during this window (unless in an active meeting).</small>
                            </div>
                        </div>

                        <!-- 3. Working Days -->
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold fs-7 mb-2">Working Days</label>
                            <div class="d-flex flex-wrap gap-2">
                                <?php foreach ($allDays as $day): 
                                    $isChecked = in_array($day, $workingDaysArray);
                                ?>
                                    <div class="form-check form-check-inline border rounded-pill px-3 py-1 bg-white shadow-xs">
                                        <input class="form-check-input working-day-chk" type="checkbox" id="day_<?= $day ?>" value="<?= $day ?>" <?= $isChecked ? 'checked' : '' ?> onchange="syncWorkingDays()">
                                        <label class="form-check-label fs-8 fw-semibold" for="day_<?= $day ?>"><?= $day ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="working_days" id="workingDaysHiddenInput" value="<?= sanitize($settings['working_days']) ?>">
                        </div>

                        <!-- 4. Grace Period & Activity Update Interval -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Late Arrival Grace Period (Minutes)</label>
                                <input type="number" name="grace_period_minutes" class="form-control form-control-sm" min="0" max="60" value="<?= (int)$settings['grace_period_minutes'] ?>" required>
                                <small class="text-muted fs-8">Minutes after shift start before late mark is recorded (Default: 15 mins).</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small fw-semibold">Activity Update Reminder Interval (Minutes)</label>
                                <input type="number" name="reminder_interval_minutes" class="form-control form-control-sm" min="5" max="180" value="<?= (int)$settings['reminder_interval_minutes'] ?>" required>
                                <small class="text-muted fs-8">Gentle update reminder shown if no activity change (Default: 30 mins).</small>
                            </div>
                        </div>

                        <!-- 5. Meeting Settings -->
                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <strong class="text-dark fs-7 d-block mb-2">
                                <i class="fas fa-video text-purple me-1"></i>Meeting Configuration
                            </strong>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary small fw-semibold">Default Meeting Duration (Minutes)</label>
                                    <input type="number" name="meeting_default_duration" class="form-control form-control-sm" min="10" max="180" value="<?= (int)$settings['meeting_default_duration'] ?>" required>
                                </div>
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="allowOverlapSwitch" name="allow_overlapping_meetings" value="1" <?= $settings['allow_overlapping_meetings'] ? 'checked' : '' ?>>
                                        <label class="form-check-label text-dark fw-semibold fs-8" for="allowOverlapSwitch">
                                            Allow Overlapping Meetings
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm" id="saveShiftSettingsBtn">
                                <i class="fas fa-save me-1.5"></i>Save Shift Configuration
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="resetToOfficeDefaults()">
                                Reset to Default (09:40 AM &ndash; 06:30 PM)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Shift Information & Periods Summary (Right Column) -->
            <div class="col-lg-4">
                <!-- Working Periods Card (Section 1) -->
                <div class="glass-card p-4 border-0 shadow-sm mb-4">
                    <h5 class="fw-bold text-dark mb-3 font-heading">
                        <i class="fas fa-calendar-check text-success me-2"></i>Daily Working Periods
                    </h5>
                    
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="p-3 rounded-3 bg-white border shadow-xs">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark fs-7">Period 1 (Morning)</strong>
                                <span class="badge bg-success-subtle text-success border border-success fs-9">Active Work</span>
                            </div>
                            <div class="fw-bold text-primary font-monospace fs-6" id="previewPeriod1">
                                09:40 AM &ndash; 02:00 PM
                            </div>
                            <small class="text-muted fs-8">4 Hours 20 Minutes</small>
                        </div>

                        <div class="p-3 rounded-3 bg-warning-subtle border border-warning shadow-xs">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark fs-7">Lunch Break Window</strong>
                                <span class="badge bg-warning text-dark border border-warning fs-9">Break</span>
                            </div>
                            <div class="fw-bold text-dark font-monospace fs-6" id="previewLunchPeriod">
                                02:00 PM &ndash; 03:00 PM
                            </div>
                            <small class="text-muted fs-8">1 Hour Lunch Period</small>
                        </div>

                        <div class="p-3 rounded-3 bg-white border shadow-xs">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark fs-7">Period 2 (Afternoon)</strong>
                                <span class="badge bg-success-subtle text-success border border-success fs-9">Active Work</span>
                            </div>
                            <div class="fw-bold text-primary font-monospace fs-6" id="previewPeriod2">
                                03:00 PM &ndash; 06:30 PM
                            </div>
                            <small class="text-muted fs-8">3 Hours 30 Minutes</small>
                        </div>
                    </div>

                    <div class="p-2.5 bg-light rounded border text-secondary fs-8">
                        <i class="fas fa-info-circle text-primary me-1"></i>Total Shift Duration: <strong>8 Hours 50 Minutes</strong> (Net 7h 50m working + 1h lunch).
                    </div>
                </div>

                <!-- Guidance Info Box -->
                <div class="glass-card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-dark mb-2">
                        <i class="fas fa-shield-halved text-primary me-1.5"></i>Productivity Philosophy
                    </h6>
                    <p class="text-secondary small mb-2">
                        WorkFlow Pro tracks productivity through active task connection, meetings, and blockers &ndash; never through covert surveillance, keystroke logging, or screen captures.
                    </p>
                    <small class="text-muted fs-8 d-block">
                        Meetings and client communications count fully as productive work time.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
function syncWorkingDays() {
    const chks = document.querySelectorAll('.working-day-chk:checked');
    const selected = Array.from(chks).map(c => c.value);
    document.getElementById('workingDaysHiddenInput').value = selected.join(',');
}

function updateSchedulePreview() {
    const sStart = document.getElementById('settingStartTime').value || '09:40';
    const sEnd = document.getElementById('settingEndTime').value || '18:30';
    const lStart = document.getElementById('settingLunchStart').value || '14:00';
    const lEnd = document.getElementById('settingLunchEnd').value || '15:00';

    const formatTimeDisplay = (timeStr) => {
        const parts = timeStr.split(':');
        let h = parseInt(parts[0], 10);
        const m = parts[1];
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12;
        h = h ? h : 12;
        return `${h.toString().padStart(2, '0')}:${m} ${ampm}`;
    };

    document.getElementById('previewPeriod1').textContent = `${formatTimeDisplay(sStart)} – ${formatTimeDisplay(lStart)}`;
    document.getElementById('previewLunchPeriod').textContent = `${formatTimeDisplay(lStart)} – ${formatTimeDisplay(lEnd)}`;
    document.getElementById('previewPeriod2').textContent = `${formatTimeDisplay(lEnd)} – ${formatTimeDisplay(sEnd)}`;
}

function resetToOfficeDefaults() {
    document.getElementById('settingStartTime').value = '09:40';
    document.getElementById('settingEndTime').value = '18:30';
    document.getElementById('settingLunchStart').value = '14:00';
    document.getElementById('settingLunchEnd').value = '15:00';
    updateSchedulePreview();
}

function submitShiftSettings(e) {
    e.preventDefault();
    syncWorkingDays();

    const form = document.getElementById('shiftSettingsForm');
    const formData = new FormData(form);

    const btn = document.getElementById('saveShiftSettingsBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving Settings...';

    fetch(resolveApiUrl('/api/shift_settings?action=save'), {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1.5"></i>Save Shift Configuration';

        if (res.success) {
            Swal.fire({
                icon: 'success',
                title: 'Settings Saved',
                text: res.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1.5"></i>Save Shift Configuration';
        Swal.fire('Error', 'Failed to save settings: ' + err.message, 'error');
    });
}
</script>
