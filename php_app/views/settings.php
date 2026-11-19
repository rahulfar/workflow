<?php
$user = requireAdmin();
$pageTitle = "Admin Settings";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
$db = Database::getConnection();

$settingsRows = $db->query("SELECT * FROM settings")->fetchAll();
$settings = [];
foreach ($settingsRows as $s) {
    $settings[$s['setting_key']] = $s['setting_value'];
}
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="mb-4">
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-sliders-h text-primary me-2"></i>System & Admin Settings</h3>
            <p class="text-secondary small mb-0">Configure company branding, office timings, database backups, and security policies.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="glass-card mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-building me-2 text-warning"></i>Company Information</h5>
                    <form id="settingsForm">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-secondary small">Company Name</label>
                                <input type="text" name="company_name" class="form-control" value="<?= sanitize($settings['company_name'] ?? 'WorkFlow Pro') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small">Company Email</label>
                                <input type="email" name="company_email" class="form-control" value="<?= sanitize($settings['company_email'] ?? 'admin@company.com') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small">Office Start Time (IST)</label>
                                <input type="time" name="office_start_time" class="form-control" value="<?= sanitize($settings['office_start_time'] ?? '09:40') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small">Office End Time (IST)</label>
                                <input type="time" name="office_end_time" class="form-control" value="<?= sanitize($settings['office_end_time'] ?? '18:30') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small">Lunch Start Time (IST)</label>
                                <input type="time" name="lunch_start_time" class="form-control" value="<?= sanitize($settings['lunch_start_time'] ?? '14:00') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-secondary small">Lunch End Time (IST)</label>
                                <input type="time" name="lunch_end_time" class="form-control" value="<?= sanitize($settings['lunch_end_time'] ?? '15:00') ?>">
                            </div>
                            <div class="col-12">
                                <div class="p-2.5 bg-light rounded border small text-secondary">
                                    <i class="fas fa-info-circle text-primary me-1"></i>Standard Work Duration: <strong>8 Hours 50 Minutes</strong> (09:40 AM &ndash; 06:30 PM IST), including <strong>02:00 PM &ndash; 03:00 PM Lunch Break</strong>.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" id="saveSettingsBtn" class="btn btn-primary fw-bold"><i class="fas fa-save me-1"></i>Save Configuration</button>
                            <a href="<?= BASE_URL ?>/shift-settings" class="btn btn-outline-primary fw-semibold"><i class="fas fa-business-time me-1"></i>Full Shift Timing Policy Center &rarr;</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="glass-card">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-database me-2 text-danger"></i>Database Maintenance</h5>
                    <p class="text-secondary small mb-3">Export an instant SQL backup file of the complete system database.</p>
                    <a href="<?= BASE_URL ?>/api/settings?action=backup" class="btn btn-outline-danger w-100 mb-2"><i class="fas fa-download me-1"></i>Download Database Backup</a>
                    <button type="button" id="optimizeDbBtn" onclick="optimizeDatabaseTables()" class="btn btn-outline-warning w-100"><i class="fas fa-sync me-1"></i>Optimize Tables</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    document.getElementById('settingsForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveSettingsBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

        const formData = new FormData(this);
        formData.append('csrf_token', CSRF_TOKEN);

        try {
            const res = await fetch('<?= BASE_URL ?>/api/settings?action=save', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved',
                    text: data.message || 'System settings saved successfully!',
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to save settings.', 'error');
            }
        } catch (err) {
            Swal.fire('Error', 'A network error occurred while saving settings.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    });

    async function optimizeDatabaseTables() {
        const btn = document.getElementById('optimizeDbBtn');
        const origText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Optimizing...';

        try {
            const res = await fetch('<?= BASE_URL ?>/api/settings?action=optimize', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `csrf_token=${encodeURIComponent(CSRF_TOKEN)}`
            });
            const data = await res.json();
            if (data.success) {
                Swal.fire('Optimized', data.message || 'Database tables and indexes optimized successfully!', 'success');
            } else {
                Swal.fire('Notice', data.message || 'Database optimization completed.', 'info');
            }
        } catch (err) {
            Swal.fire('Error', 'Failed to communicate with database server.', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = origText;
        }
    }
</script>
