<?php
$pageTitle = "Reports & Excel Export Hub";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();
ensurePayrollSchema($db);

$isAdmin = hasRole('admin');
$currentMonth = date('Y-m');

$performance = $db->query("
    SELECT u.name, u.employee_code, r.display_name as role_title, d.name as department_name,
           COUNT(t.id) as total_tasks,
           SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
           SUM(t.spent_seconds) as total_spent
    FROM users u
    JOIN roles r ON u.role_id = r.id
    JOIN departments d ON u.department_id = d.id
    LEFT JOIN tasks t ON t.assigned_to = u.id
    GROUP BY u.id
    ORDER BY completed_tasks DESC
")->fetchAll();
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-file-invoice text-success me-2"></i>Reports & Excel Export Hub</h3>
                <p class="text-secondary small mb-0">Detailed task statistics, employee efficiency ranking, and formatted Excel (.xlsx) exports.</p>
            </div>
            <div class="d-flex gap-2">
                <button onclick="window.print()" class="btn btn-outline-secondary"><i class="fas fa-print me-1"></i>Print Page</button>
            </div>
        </div>

        <?php if ($isAdmin): ?>
        <!-- Admin Excel Export Module Grid -->
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-file-excel text-success me-2"></i>Excel (.xlsx) Report Exporter</h5>
                <div class="d-flex align-items-center gap-2">
                    <label class="small text-muted mb-0">Select Month:</label>
                    <input type="month" id="reportMonthSelector" class="form-control form-control-sm" value="<?= $currentMonth ?>">
                </div>
            </div>
            <p class="text-secondary small mb-4">Download structured Microsoft Excel files formatted with headers, attendance metrics, leave balances, and salary breakdowns.</p>

            <div class="row g-3">
                <!-- 1. Payroll Report -->
                <div class="col-md-4 col-sm-6">
                    <div class="p-3 border rounded bg-white hover-shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="p-2 bg-success bg-opacity-10 text-success rounded fs-4"><i class="fas fa-wallet"></i></div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Payroll Report</h6>
                                    <small class="text-muted">Monthly salary register & net pay</small>
                                </div>
                            </div>
                            <p class="small text-secondary mb-3">Includes basic salary, allowance, bonus, absent/half-day deductions, paid leaves, and payment status.</p>
                        </div>
                        <button onclick="triggerExcelExport('payroll')" class="btn btn-sm btn-success w-100 fw-bold"><i class="fas fa-download me-1"></i>Export Payroll Excel</button>
                    </div>
                </div>

                <!-- 2. Salary Report -->
                <div class="col-md-4 col-sm-6">
                    <div class="p-3 border rounded bg-white hover-shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="p-2 bg-primary bg-opacity-10 text-primary rounded fs-4"><i class="fas fa-coins"></i></div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Salary Report</h6>
                                    <small class="text-muted">Employee base salary structures</small>
                                </div>
                            </div>
                            <p class="small text-secondary mb-3">Master list of employee monthly base salaries, allowances, codes, and designations.</p>
                        </div>
                        <button onclick="triggerExcelExport('salary')" class="btn btn-sm btn-primary w-100 fw-bold"><i class="fas fa-download me-1"></i>Export Salary Excel</button>
                    </div>
                </div>

                <!-- 3. Attendance Report -->
                <div class="col-md-4 col-sm-6">
                    <div class="p-3 border rounded bg-white hover-shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="p-2 bg-info bg-opacity-10 text-info rounded fs-4"><i class="fas fa-user-clock"></i></div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Attendance Report</h6>
                                    <small class="text-muted">Clock in/out logs & punctuality</small>
                                </div>
                            </div>
                            <p class="small text-secondary mb-3">Full attendance logs, shift durations, late arrival minutes, and early exit minutes.</p>
                        </div>
                        <button onclick="triggerExcelExport('attendance')" class="btn btn-sm btn-info text-white w-100 fw-bold"><i class="fas fa-download me-1"></i>Export Attendance Excel</button>
                    </div>
                </div>

                <!-- 4. Leave Report -->
                <div class="col-md-4 col-sm-6">
                    <div class="p-3 border rounded bg-white hover-shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="p-2 bg-warning bg-opacity-10 text-warning rounded fs-4"><i class="fas fa-calendar-minus"></i></div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Leave Report</h6>
                                    <small class="text-muted">Time-off applications & quotas</small>
                                </div>
                            </div>
                            <p class="small text-secondary mb-3">Leave applications, paid vs unpaid day breakdown, reasons, and manager approval logs.</p>
                        </div>
                        <button onclick="triggerExcelExport('leave')" class="btn btn-sm btn-warning text-dark w-100 fw-bold"><i class="fas fa-download me-1"></i>Export Leave Excel</button>
                    </div>
                </div>

                <!-- 5. Employee Report -->
                <div class="col-md-4 col-sm-6">
                    <div class="p-3 border rounded bg-white hover-shadow h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="p-2 bg-secondary bg-opacity-10 text-secondary rounded fs-4"><i class="fas fa-users-gear"></i></div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0">Employee Report</h6>
                                    <small class="text-muted">Staff master directory roster</small>
                                </div>
                            </div>
                            <p class="small text-secondary mb-3">Complete staff list with employee codes, emails, phones, departments, and joining dates.</p>
                        </div>
                        <button onclick="triggerExcelExport('employee')" class="btn btn-sm btn-secondary w-100 fw-bold"><i class="fas fa-download me-1"></i>Export Employee Excel</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Performance Leaderboard -->
        <div class="glass-card mb-4">
            <h5 class="fw-bold text-dark mb-3">Employee Performance Leaderboard</h5>
            <div class="table-responsive">
                <table class="table align-middle" id="reportTable">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Total Tasks</th>
                            <th>Completed</th>
                            <th>Total Hours</th>
                            <th>Efficiency Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($performance as $p): 
                            $rate = $p['total_tasks'] > 0 ? round(($p['completed_tasks'] / $p['total_tasks']) * 100) : 100;
                        ?>
                            <tr>
                                <td class="fw-bold text-dark">
                                    <?= sanitize($p['name']) ?>
                                    <small class="text-muted font-monospace d-block"><?= sanitize($p['employee_code'] ?? '') ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?= sanitize($p['department_name']) ?></span></td>
                                <td class="small text-secondary"><?= sanitize($p['role_title']) ?></td>
                                <td class="text-dark"><?= $p['total_tasks'] ?></td>
                                <td class="text-success fw-bold"><?= $p['completed_tasks'] ?></td>
                                <td class="font-monospace text-info"><?= formatDuration($p['total_spent'] ?: 0) ?></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: <?= $rate ?>%"></div>
                                        </div>
                                        <span class="small fw-bold text-dark font-monospace"><?= $rate ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    function triggerExcelExport(type) {
        const monthYear = document.getElementById('reportMonthSelector').value;
        fetch(`<?= BASE_URL ?>/api/export?type=${type}&month_year=${monthYear}`)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.rows && res.rows.length > 0) {
                const worksheet = XLSX.utils.json_to_sheet(res.rows);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, type.toUpperCase());
                XLSX.writeFile(workbook, `${res.filename}.xlsx`);
                Swal.fire({
                    icon: 'success',
                    title: 'Excel Generated!',
                    text: `Exported ${res.rows.length} records to ${res.filename}.xlsx`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Export Info', 'No records found for the selected filter to export.', 'info');
            }
        });
    }
</script>
