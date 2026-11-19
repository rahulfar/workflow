<?php
$pageTitle = "Schedule Calendar & Holidays";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();
ensurePayrollSchema($db);

$isAdmin = hasRole('admin');
$holidays = $db->query("SELECT * FROM holidays ORDER BY date ASC")->fetchAll();
$tasks = $db->query("SELECT id, title, deadline, priority FROM tasks LIMIT 10")->fetchAll();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-calendar-alt text-info me-2"></i>Schedule & Holiday Calendar</h3>
                <p class="text-secondary small mb-0">Upcoming task deadlines, company milestones, and official paid holidays.</p>
            </div>
            <?php if ($isAdmin): ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHolidayModal"><i class="fas fa-plus me-1"></i>Add Holiday</button>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <!-- Left Side: Task Deadlines -->
            <div class="col-lg-7">
                <div class="glass-card h-100">
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-list-check me-2 text-primary"></i>Upcoming Task Deadlines</h5>
                    <div class="list-group list-group-flush">
                        <?php foreach ($tasks as $t): ?>
                            <div class="list-group-item bg-transparent text-dark border-bottom d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold"><?= sanitize($t['title']) ?></span>
                                    <div class="small text-muted">Deadline: <?= date('M d, Y H:i', strtotime($t['deadline'])) ?></div>
                                </div>
                                <?= getPriorityBadge($t['priority']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Right Side: Official Holidays List -->
            <div class="col-lg-5">
                <div class="glass-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-umbrella-beach me-2 text-warning"></i>Official Company Holidays</h5>
                        <span class="badge bg-light text-dark border"><?= count($holidays) ?> Holidays</span>
                    </div>

                    <div class="list-group list-group-flush">
                        <?php if ($holidays): foreach ($holidays as $h): 
                            $badgeClass = 'bg-primary';
                            if ($h['type'] === 'national') $badgeClass = 'bg-danger';
                            elseif ($h['type'] === 'festival') $badgeClass = 'bg-warning text-dark';
                            elseif ($h['type'] === 'optional') $badgeClass = 'bg-secondary';
                        ?>
                            <div class="list-group-item bg-transparent text-dark border-bottom d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="d-flex align-items-center gap-2">
                                        <strong class="text-dark"><?= sanitize($h['title']) ?></strong>
                                        <span class="badge <?= $badgeClass ?> fs-8"><?= ucfirst($h['type']) ?></span>
                                    </div>
                                    <div class="small text-secondary"><i class="fas fa-calendar-day me-1"></i><?= date('D, M d, Y', strtotime($h['date'])) ?></div>
                                    <?php if (!empty($h['description'])): ?>
                                        <div class="small text-muted italic"><?= sanitize($h['description']) ?></div>
                                    <?php endif; ?>
                                </div>

                                <?php if ($isAdmin): ?>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="openEditHolidayModal(<?= $h['id'] ?>, '<?= sanitize($h['title']) ?>', '<?= $h['date'] ?>', '<?= $h['type'] ?>', '<?= sanitize($h['description'] ?? '') ?>')"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-outline-danger" onclick="deleteHoliday(<?= $h['id'] ?>, '<?= sanitize($h['title']) ?>')"><i class="fas fa-trash"></i></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; else: ?>
                            <div class="text-muted text-center py-4">No holidays recorded yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Add Holiday Modal -->
<?php if ($isAdmin): ?>
<div class="modal fade" id="addHolidayModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-umbrella-beach text-warning me-2"></i>Add Official Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addHolidayForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Holiday Name *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Independence Day" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Holiday Date *</label>
                            <input type="date" name="date" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Holiday Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="national">National Holiday</option>
                                <option value="festival">Festival Holiday</option>
                                <option value="company" selected>Company Holiday</option>
                                <option value="optional">Optional Holiday</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Optional details..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary fw-bold">Save Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Admin Edit Holiday Modal -->
<div class="modal fade" id="editHolidayModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark">Edit Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editHolidayForm">
                <input type="hidden" name="holiday_id" id="editHolidayId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Holiday Name *</label>
                        <input type="text" name="title" id="editHolidayTitle" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-secondary small">Holiday Date *</label>
                            <input type="date" name="date" id="editHolidayDate" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-secondary small">Holiday Type *</label>
                            <select name="type" id="editHolidayType" class="form-select" required>
                                <option value="national">National Holiday</option>
                                <option value="festival">Festival Holiday</option>
                                <option value="company">Company Holiday</option>
                                <option value="optional">Optional Holiday</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-secondary small">Description</label>
                        <textarea name="description" id="editHolidayDescription" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary fw-bold">Update Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    <?php if ($isAdmin): ?>
    document.getElementById('addHolidayForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'add');
        formData.append('csrf_token', CSRF_TOKEN);
        fetch('<?= BASE_URL ?>/api/holidays', { method: 'POST', body: formData })
        .then(res => res.json()).then(res => {
            if (res.success) Swal.fire('Success', res.message, 'success').then(() => location.reload());
            else Swal.fire('Error', res.message, 'error');
        });
    });

    document.getElementById('editHolidayForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'edit');
        formData.append('csrf_token', CSRF_TOKEN);
        fetch('<?= BASE_URL ?>/api/holidays', { method: 'POST', body: formData })
        .then(res => res.json()).then(res => {
            if (res.success) Swal.fire('Success', res.message, 'success').then(() => location.reload());
            else Swal.fire('Error', res.message, 'error');
        });
    });

    function openEditHolidayModal(id, title, date, type, description) {
        document.getElementById('editHolidayId').value = id;
        document.getElementById('editHolidayTitle').value = title;
        document.getElementById('editHolidayDate').value = date;
        document.getElementById('editHolidayType').value = type;
        document.getElementById('editHolidayDescription').value = description;
        new bootstrap.Modal(document.getElementById('editHolidayModal')).show();
    }

    function deleteHoliday(id, title) {
        Swal.fire({
            title: `Delete '${title}'?`,
            text: 'Are you sure you want to remove this holiday from the schedule?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete'
        }).then(res => {
            if (res.isConfirmed) {
                fetch('<?= BASE_URL ?>/api/holidays', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete&holiday_id=${id}&csrf_token=${CSRF_TOKEN}`
                }).then(res => res.json()).then(res => {
                    if (res.success) Swal.fire('Deleted', res.message, 'success').then(() => location.reload());
                });
            }
        });
    }
    <?php endif; ?>
</script>
