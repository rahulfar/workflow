<?php
// Strict Admin validation: Non-admins cannot view or access payroll details
$user = requireAdmin();
$isAdmin = true;

$pageTitle = "Payroll Management";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$db = Database::getConnection();
ensurePayrollSchema($db);

$currentMonth = date('Y-m');
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <!-- Header Title Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-wallet text-success me-2"></i>Payroll Management System</h3>
                <p class="text-secondary small mb-0">Attendance-based automated salary computation, monthly payroll reports, and salary slips.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#manageSalariesModal"><i class="fas fa-coins me-1"></i>Employee Base Salaries</button>
                <button onclick="triggerGeneratePayroll()" class="btn btn-primary"><i class="fas fa-calculator me-1"></i>Generate Payroll</button>
                <button onclick="exportPayrollExcel()" class="btn btn-success"><i class="fas fa-file-excel me-1"></i>Export to Excel</button>
            </div>
        </div>

        <!-- Live Payroll Cycle & Progress Banner -->
        <div class="glass-card mb-4 p-3 border-start border-4 border-warning">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-3 py-1 mb-1"><i class="fas fa-arrows-rotate me-1"></i>Active Payroll Cycle</span>
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-calendar-days me-2 text-warning"></i>Period: <span id="cardPeriodLabel">01 Aug 2026 → 01 Sep 2026</span></h5>
                    <small class="text-secondary">Automatic daily rollover & attendance salary tracking up to current date.</small>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <div class="text-center">
                        <small class="text-muted d-block">Completed Days</small>
                        <strong id="cardCompletedDays" class="fs-5 text-success font-monospace">0</strong>
                    </div>
                    <div class="text-center">
                        <small class="text-muted d-block">Remaining Days</small>
                        <strong id="cardRemainingDays" class="fs-5 text-secondary font-monospace">0</strong>
                    </div>
                    <div class="text-center" style="min-width: 140px;">
                        <small class="text-muted d-block mb-1">Payroll Progress</small>
                        <div class="progress" style="height: 10px;">
                            <div id="cardProgressBar" class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;"></div>
                        </div>
                        <small id="cardProgressText" class="fw-bold text-dark mt-1 d-block font-monospace">0%</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Summary Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4 col-xl-2.4 flex-fill">
                <div class="glass-card stat-card">
                    <div class="stat-icon primary"><i class="fas fa-money-bill-wave"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="cardTotalPayroll">₹0</div>
                        <div class="stat-label">Total Payroll</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2.4 flex-fill">
                <div class="glass-card stat-card">
                    <div class="stat-icon success"><i class="fas fa-check-double"></i></div>
                    <div class="stat-info">
                        <div class="stat-value text-success" id="cardPaidSalaries">₹0</div>
                        <div class="stat-label">Paid Salaries</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2.4 flex-fill">
                <div class="glass-card stat-card">
                    <div class="stat-icon danger"><i class="fas fa-clock-history"></i></div>
                    <div class="stat-info">
                        <div class="stat-value text-danger" id="cardPendingSalaries">₹0</div>
                        <div class="stat-label">Pending Salaries</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2.4 flex-fill">
                <div class="glass-card stat-card">
                    <div class="stat-icon info"><i class="fas fa-users-gear"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="cardTotalEmployees">0</div>
                        <div class="stat-label">Total Staff</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2.4 flex-fill">
                <div class="glass-card stat-card">
                    <div class="stat-icon warning"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <div class="stat-value" id="cardWorkingDays">0</div>
                        <div class="stat-label">Working Days</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2.4 flex-fill">
                <div class="glass-card stat-card">
                    <div class="stat-icon info"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-info">
                        <div class="stat-value font-monospace" id="cardCurrentMonth"><?= date('M Y') ?></div>
                        <div class="stat-label">Current Payroll Cycle</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="glass-card mb-4 p-3">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="searchEmployeeInput" class="form-control" placeholder="Search employee name, code...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-calendar-alt text-primary"></i></span>
                        <input type="month" id="payrollMonthFilter" class="form-control font-monospace" value="<?= $currentMonth ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <?php 
                        $deptList = $db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll(); 
                    ?>
                    <select id="departmentFilter" class="form-select">
                        <option value="">All Departments</option>
                        <?php foreach ($deptList as $d): ?>
                            <option value="<?= sanitize($d['name']) ?>"><?= sanitize($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Payment Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <button onclick="loadPayrollData()" class="btn btn-outline-secondary w-100"><i class="fas fa-filter me-1"></i>Apply Filter</button>
                </div>
            </div>
        </div>

        <!-- Payroll Table -->
        <div class="glass-card mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-table-list text-primary me-2"></i>Employee Payroll Register</h5>
                <small class="text-muted">Calculations include: Attendance, Half Days, Approved Paid Leave, Holidays, Allowances & Deductions.</small>
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover" id="payrollMainTable">
                    <thead class="table-light">
                        <tr>
                            <th>Employee</th>
                            <th>ID & Dept</th>
                            <th>Basic Salary</th>
                            <th>Allowance</th>
                            <th>Bonus</th>
                            <th>Deduction</th>
                            <th>Present</th>
                            <th>Half Days</th>
                            <th>Absent</th>
                            <th>Paid Leave</th>
                            <th>Holidays</th>
                            <th>Earned Salary (Current)</th>
                            <th>Expected Net Salary</th>
                            <th>Payment Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="payrollTbody">
                        <tr>
                            <td colspan="14" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading payroll records...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 1: Base Salaries Management -->
<div class="modal fade" id="manageSalariesModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-coins text-warning me-2"></i>Employee Default Salaries Roster</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary small mb-3">Preloaded employee monthly salaries. Admin can edit default base salary and allowance at any time.</p>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Monthly Base Salary</th>
                                <th>Standard Allowance</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="baseSalariesTbody">
                            <!-- Populated dynamically via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL 2: Edit Employee Base Salary -->
<div class="modal fade" id="editSalaryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark">Edit Base Salary</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editSalaryForm">
                <input type="hidden" name="user_id" id="editSalUserId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Employee Name</label>
                        <input type="text" id="editSalEmpName" class="form-control bg-light" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Monthly Base Salary (₹) *</label>
                        <input type="number" step="0.01" name="monthly_salary" id="editSalMonthly" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Standard Monthly Allowance (₹)</label>
                        <input type="number" step="0.01" name="allowance" id="editSalAllowance" class="form-control" value="0.00">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary fw-bold">Save Base Salary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 3: Edit Payroll Record -->
<div class="modal fade" id="editPayrollModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-edit text-primary me-2"></i>Update Monthly Payroll Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPayrollForm">
                <input type="hidden" name="payroll_id" id="editPayrollId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Employee</label>
                        <input type="text" id="editPayrollEmpName" class="form-control bg-light" readonly>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-secondary">Allowance (₹)</label>
                            <input type="number" step="0.01" name="allowance" id="editPayrollAllowance" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-secondary">Bonus (₹)</label>
                            <input type="number" step="0.01" name="bonus" id="editPayrollBonus" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Deduction (₹)</label>
                        <input type="number" step="0.01" name="deduction" id="editPayrollDeduction" class="form-control">
                        <small class="text-muted">Auto-calculated absent/half day/unpaid leave deductions applied automatically if left standard.</small>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small text-secondary">Payment Status</label>
                            <select name="payment_status" id="editPayrollStatus" class="form-select">
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-secondary">Payment Date</label>
                            <input type="date" name="payment_date" id="editPayrollDate" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Remarks / Admin Notes</label>
                        <textarea name="remarks" id="editPayrollRemarks" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary fw-bold">Update & Recalculate Net Salary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 4: Professional Salary Slip Printable View -->
<div class="modal fade" id="salarySlipModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-file-invoice-dollar text-success me-2"></i>Official Salary Slip</h5>
                <div>
                    <button onclick="printSalarySlip()" class="btn btn-sm btn-outline-secondary me-2"><i class="fas fa-print me-1"></i>Print</button>
                    <button onclick="printSalarySlip()" class="btn btn-sm btn-primary me-2"><i class="fas fa-download me-1"></i>Download PDF</button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-4" id="printableSalarySlipArea">
                <!-- Salary Slip Template -->
                <div class="border rounded p-4 bg-white text-dark shadow-sm">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div>
                            <h4 class="fw-bold text-primary mb-1"><i class="fas fa-cubes-stacked me-2"></i>WorkFlow Pro Suite</h4>
                            <div class="small text-secondary">Corporate Tech Hub, Phase 2, IT Park</div>
                            <div class="small text-secondary">Email: admin@company.com | Phone: +91 9876543210</div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary text-white fs-6 px-3 py-2">SALARY SLIP</span>
                            <div class="small fw-bold text-dark mt-2" id="slipMonthHeader">Month: August 2026</div>
                        </div>
                    </div>

                    <!-- Employee Info Grid -->
                    <div class="row g-3 mb-4 bg-light p-3 rounded">
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Employee Name</small>
                            <strong id="slipEmpName" class="text-dark">--</strong>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Employee ID</small>
                            <span id="slipEmpCode" class="font-monospace text-dark fw-bold">--</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Department</small>
                            <span id="slipEmpDept" class="text-dark">--</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Designation</small>
                            <span id="slipEmpDesignation" class="text-dark">--</span>
                        </div>
                    </div>

                    <!-- Attendance Summary Grid -->
                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-calendar-check me-2 text-info"></i>Attendance & Working Hours Summary</h6>
                    <div class="row g-2 mb-4 text-center">
                        <div class="col"><div class="p-2 border rounded"><small class="text-muted d-block">Present Days</small><strong id="slipPresentDays" class="text-success">0</strong></div></div>
                        <div class="col"><div class="p-2 border rounded"><small class="text-muted d-block">Half Days</small><strong id="slipHalfDays" class="text-warning">0</strong></div></div>
                        <div class="col"><div class="p-2 border rounded"><small class="text-muted d-block">Absent Days</small><strong id="slipAbsentDays" class="text-danger">0</strong></div></div>
                        <div class="col"><div class="p-2 border rounded"><small class="text-muted d-block">Paid Leaves</small><strong id="slipPaidLeaves" class="text-info">0</strong></div></div>
                        <div class="col"><div class="p-2 border rounded"><small class="text-muted d-block">Holidays</small><strong id="slipHolidays" class="text-primary">0</strong></div></div>
                        <div class="col"><div class="p-2 border rounded"><small class="text-muted d-block">Worked Hours</small><strong id="slipWorkedHours" class="font-monospace text-dark">0h</strong></div></div>
                    </div>

                    <!-- Financial Breakdown Table -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold text-success border-bottom pb-2 mb-3"><i class="fas fa-plus-circle me-1"></i>Earnings</h6>
                                <div class="d-flex justify-content-between mb-2"><span>Basic Salary</span><span id="slipBasicSalary" class="font-monospace fw-bold">₹0.00</span></div>
                                <div class="d-flex justify-content-between mb-2"><span>Allowances</span><span id="slipAllowance" class="font-monospace">₹0.00</span></div>
                                <div class="d-flex justify-content-between mb-2"><span>Bonus / Incentives</span><span id="slipBonus" class="font-monospace">₹0.00</span></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold text-danger border-bottom pb-2 mb-3"><i class="fas fa-minus-circle me-1"></i>Deductions</h6>
                                <div class="d-flex justify-content-between mb-2"><span>Attendance & Absent Deductions</span><span id="slipDeduction" class="font-monospace text-danger">₹0.00</span></div>
                                <div class="d-flex justify-content-between mb-2"><span>Other Policy Deductions</span><span class="font-monospace text-muted">₹0.00</span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Salary Banner -->
                    <div class="p-3 bg-primary bg-opacity-10 border border-primary rounded d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="text-secondary small d-block">TOTAL NET PAYABLE SALARY</span>
                            <h3 id="slipNetSalary" class="fw-bold text-primary mb-0">₹0.00</h3>
                        </div>
                        <div class="text-end">
                            <span class="text-secondary small d-block">PAYMENT STATUS</span>
                            <span id="slipPaymentStatusBadge" class="badge bg-warning text-dark fs-6 px-3 py-1.5">Pending</span>
                        </div>
                    </div>

                    <div class="small text-muted text-center pt-2 border-top">
                        This is a computer-generated salary slip and does not require a physical signature.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    const IS_ADMIN = <?= json_encode($isAdmin) ?>;
    let currentPayrollRows = [];

    document.addEventListener('DOMContentLoaded', () => {
        loadPayrollData();
        loadBaseSalariesData();

        document.getElementById('manageSalariesModal')?.addEventListener('show.bs.modal', loadBaseSalariesData);

        document.getElementById('payrollMonthFilter').addEventListener('change', loadPayrollData);
        document.getElementById('statusFilter').addEventListener('change', filterPayrollTable);
        document.getElementById('departmentFilter').addEventListener('change', filterPayrollTable);
        document.getElementById('searchEmployeeInput').addEventListener('input', filterPayrollTable);

        if (document.getElementById('editSalaryForm')) {
            document.getElementById('editSalaryForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', 'save_salary');
                fetch('<?= BASE_URL ?>/api/payroll', { method: 'POST', body: formData })
                .then(res => res.json()).then(res => {
                    if (res.success) {
                        Swal.fire('Saved!', res.message, 'success');
                        bootstrap.Modal.getInstance(document.getElementById('editSalaryModal')).hide();
                        loadBaseSalariesData();
                        loadPayrollData();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            });
        }

        if (document.getElementById('editPayrollForm')) {
            document.getElementById('editPayrollForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('action', 'update_payroll');
                fetch('<?= BASE_URL ?>/api/payroll', { method: 'POST', body: formData })
                .then(res => res.json()).then(res => {
                    if (res.success) {
                        Swal.fire('Updated!', res.message, 'success');
                        bootstrap.Modal.getInstance(document.getElementById('editPayrollModal')).hide();
                        loadPayrollData();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            });
        }
    });

    function loadPayrollData() {
        const monthYear = document.getElementById('payrollMonthFilter').value;
        fetch(`<?= BASE_URL ?>/api/payroll?action=get_payroll_summary&month_year=${monthYear}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const payload = res.data || res;
                const summary = payload.summary || res.summary || {};
                document.getElementById('cardTotalPayroll').innerText = '₹' + Number(summary.total_payroll || 0).toLocaleString('en-IN');
                document.getElementById('cardPaidSalaries').innerText = '₹' + Number(summary.paid_salaries || 0).toLocaleString('en-IN');
                document.getElementById('cardPendingSalaries').innerText = '₹' + Number(summary.pending_salaries || 0).toLocaleString('en-IN');
                document.getElementById('cardTotalEmployees').innerText = summary.total_employees || 0;
                document.getElementById('cardCurrentMonth').innerText = monthYear;

                if (document.getElementById('cardPeriodLabel')) {
                    document.getElementById('cardPeriodLabel').innerText = summary.period_label || (monthYear + '-01');
                    document.getElementById('cardCompletedDays').innerText = summary.completed_days || 0;
                    document.getElementById('cardRemainingDays').innerText = summary.remaining_days || 0;
                    const prog = summary.payroll_progress || 0;
                    document.getElementById('cardProgressBar').style.width = prog + '%';
                    document.getElementById('cardProgressText').innerText = prog + '% Completed';
                }

                currentPayrollRows = payload.payrolls || res.payrolls || [];
                filterPayrollTable();
            } else {
                console.error("Payroll Summary Error:", res.message);
                renderPayrollTable([]);
            }
        })
        .catch(err => {
            console.error("Payroll Fetch Error:", err);
            renderPayrollTable([]);
        });
    }

    function renderPayrollTable(rows) {
        const tbody = document.getElementById('payrollTbody');
        if (!rows || rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="15" class="text-center py-4 text-muted"><i class="fas fa-exclamation-circle me-1"></i>No payroll records found for this period. Click "Generate Monthly Payroll" to compute.</td></tr>`;
            return;
        }

        let html = '';
        rows.forEach(r => {
            const statusBadge = r.payment_status === 'paid'
                ? `<span class="badge bg-success-subtle text-success border border-success px-2 py-1"><i class="fas fa-check-circle me-1"></i>Paid</span>`
                : `<span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1"><i class="fas fa-clock me-1"></i>Pending</span>`;

            const avatarSrc = r.avatar && r.avatar.startsWith('http')
                ? r.avatar
                : (r.avatar && r.avatar !== 'default_avatar.png'
                    ? `<?= BASE_URL ?>/uploads/avatars/${r.avatar}`
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(r.employee_name)}&background=4f46e5&color=fff`);

            let actionBtns = `<button class="btn btn-outline-info" title="View Salary Slip" onclick="openSalarySlipModal(${r.id})"><i class="fas fa-file-invoice"></i> Slip</button>`;
            
            if (IS_ADMIN) {
                actionBtns += `<button class="btn btn-outline-primary ms-1" title="Edit Payroll Record" onclick="openEditPayrollModal(${r.id})"><i class="fas fa-edit"></i> Edit</button>`;
                if (r.payment_status === 'pending') {
                    actionBtns += `<button class="btn btn-outline-success ms-1" title="Mark as Paid" onclick="markPayrollPaid(${r.id}, '${r.employee_name.replace(/'/g, "\\'")}')"><i class="fas fa-check"></i> Paid</button>`;
                }
            }

            const earnedSal = Number(r.earned_salary || r.net_salary);
            const expectedNetSal = Number(r.net_salary);

            html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="${avatarSrc}" class="rounded-circle border" width="32" height="32" style="object-fit:cover;" alt="Avatar">
                            <div>
                                <div class="fw-bold text-dark mb-0">${r.employee_name}</div>
                                <div class="small text-muted">${r.designation || ''}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="font-monospace text-primary small d-block">${r.employee_code || 'EMP-00' + r.user_id}</span>
                        <span class="small text-muted">${r.department_name}</span>
                    </td>
                    <td class="font-monospace fw-bold">₹${Number(r.basic_salary).toLocaleString('en-IN')}</td>
                    <td class="font-monospace text-muted">₹${Number(r.allowance).toLocaleString('en-IN')}</td>
                    <td class="font-monospace text-success">₹${Number(r.bonus).toLocaleString('en-IN')}</td>
                    <td class="font-monospace text-danger">₹${Number(r.deduction).toLocaleString('en-IN')}</td>
                    <td class="text-success font-monospace">${r.present_days}</td>
                    <td class="text-warning font-monospace">${r.half_days}</td>
                    <td class="text-danger font-monospace">${r.absent_days}</td>
                    <td class="text-info font-monospace">${r.paid_leave_days}</td>
                    <td class="text-primary font-monospace">${r.holiday_days || 0}</td>
                    <td class="font-monospace fw-bold text-success fs-6">₹${earnedSal.toLocaleString('en-IN')}</td>
                    <td class="font-monospace fw-bold text-primary fs-6">₹${expectedNetSal.toLocaleString('en-IN')}</td>
                    <td>${statusBadge}</td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            ${actionBtns}
                        </div>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    function markPayrollPaid(id, empName) {
        Swal.fire({
            title: `Mark Salary as Paid?`,
            text: `Confirm marking salary payment status for ${empName} as Paid.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Mark Paid'
        }).then(res => {
            if (res.isConfirmed) {
                fetch('<?= BASE_URL ?>/api/payroll', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=mark_paid&payroll_id=${id}&csrf_token=${typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : ''}`
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire('Paid!', res.message, 'success');
                        loadPayrollData();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }

    function filterPayrollTable() {
        const query = document.getElementById('searchEmployeeInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        const dept = document.getElementById('departmentFilter') ? document.getElementById('departmentFilter').value : '';

        const filtered = currentPayrollRows.filter(r => {
            const matchesQuery = r.employee_name.toLowerCase().includes(query) || (r.employee_code && r.employee_code.toLowerCase().includes(query)) || r.department_name.toLowerCase().includes(query);
            const matchesStatus = !status || r.payment_status === status;
            const matchesDept = !dept || r.department_name === dept;
            return matchesQuery && matchesStatus && matchesDept;
        });

        renderPayrollTable(filtered);
    }

    function loadBaseSalariesData() {
        fetch('<?= BASE_URL ?>/api/payroll?action=get_salaries', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const payload = res.data || res;
                const salaries = payload.salaries || res.salaries || [];
                const tbody = document.getElementById('baseSalariesTbody');
                if (!tbody) return;
                let html = '';
                salaries.forEach(s => {
                    const editBtns = IS_ADMIN ? `
                        <button class="btn btn-sm btn-outline-primary" onclick="openEditSalaryModal(${s.user_id}, '${s.name.replace(/'/g, "\\'")}', ${s.monthly_salary}, ${s.allowance})"><i class="fas fa-edit me-1"></i>Edit</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteBaseSalary(${s.user_id}, '${s.name.replace(/'/g, "\\'")}')"><i class="fas fa-trash"></i></button>
                    ` : '<span class="badge bg-light text-muted border">Read Only</span>';

                    html += `
                        <tr>
                            <td class="fw-bold text-dark">${s.name}</td>
                            <td><span class="badge bg-light text-dark border">${s.department_name}</span></td>
                            <td class="font-monospace fw-bold text-primary">₹${Number(s.monthly_salary).toLocaleString('en-IN')}</td>
                            <td class="font-monospace text-muted">₹${Number(s.allowance).toLocaleString('en-IN')}</td>
                            <td>${editBtns}</td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            }
        })
        .catch(err => console.error("Salaries Fetch Error:", err));
    }

    function openEditSalaryModal(userId, name, salary, allowance) {
        document.getElementById('editSalUserId').value = userId;
        document.getElementById('editSalEmpName').value = name;
        document.getElementById('editSalMonthly').value = salary;
        document.getElementById('editSalAllowance').value = allowance;
        new bootstrap.Modal(document.getElementById('editSalaryModal')).show();
    }

    function deleteBaseSalary(userId, name) {
        Swal.fire({
            title: `Delete Salary Record?`,
            text: `Are you sure you want to delete default salary record for ${name}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Delete'
        }).then(res => {
            if (res.isConfirmed) {
                fetch('<?= BASE_URL ?>/api/payroll', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=delete_salary&user_id=${userId}&csrf_token=${CSRF_TOKEN}`
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        Swal.fire('Deleted', res.message, 'success');
                        loadBaseSalariesData();
                        loadPayrollData();
                    }
                });
            }
        });
    }

    function triggerGeneratePayroll() {
        const monthYear = document.getElementById('payrollMonthFilter').value;
        Swal.fire({
            title: `Generate Payroll for ${monthYear}?`,
            text: `This will recalculate attendance, working hours, leaves, and net salary for all active employees.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Generate Payroll'
        }).then(res => {
            if (res.isConfirmed) {
                fetch('<?= BASE_URL ?>/api/payroll', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `action=generate_payroll&month_year=${monthYear}&csrf_token=${CSRF_TOKEN}`
                }).then(res => res.json()).then(res => {
                    if (res.success) {
                        Swal.fire('Success', res.message, 'success');
                        loadPayrollData();
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                });
            }
        });
    }

    function openEditPayrollModal(id) {
        const rec = currentPayrollRows.find(r => r.id == id);
        if (!rec) return;

        document.getElementById('editPayrollId').value = rec.id;
        document.getElementById('editPayrollEmpName').value = rec.employee_name;
        document.getElementById('editPayrollAllowance').value = rec.allowance;
        document.getElementById('editPayrollBonus').value = rec.bonus;
        document.getElementById('editPayrollDeduction').value = rec.deduction;
        document.getElementById('editPayrollStatus').value = rec.payment_status;
        document.getElementById('editPayrollDate').value = rec.payment_date || '';
        document.getElementById('editPayrollRemarks').value = rec.remarks || '';

        new bootstrap.Modal(document.getElementById('editPayrollModal')).show();
    }

    function openSalarySlipModal(id) {
        fetch(`<?= BASE_URL ?>/api/payroll?action=get_salary_slip&id=${id}`)
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const s = res.slip;
                document.getElementById('slipMonthHeader').innerText = `Month: ${s.month_year}`;
                document.getElementById('slipEmpName').innerText = s.employee_name;
                document.getElementById('slipEmpCode').innerText = s.employee_code || ('EMP-00' + s.user_id);
                document.getElementById('slipEmpDept').innerText = s.department_name;
                document.getElementById('slipEmpDesignation').innerText = s.designation;

                document.getElementById('slipPresentDays').innerText = s.present_days;
                document.getElementById('slipHalfDays').innerText = s.half_days;
                document.getElementById('slipAbsentDays').innerText = s.absent_days;
                document.getElementById('slipPaidLeaves').innerText = s.paid_leave_days;
                document.getElementById('slipHolidays').innerText = s.holiday_days;
                document.getElementById('slipWorkedHours').innerText = s.working_hours + ' hrs';

                document.getElementById('slipBasicSalary').innerText = '₹' + Number(s.basic_salary).toLocaleString('en-IN');
                document.getElementById('slipAllowance').innerText = '₹' + Number(s.allowance).toLocaleString('en-IN');
                document.getElementById('slipBonus').innerText = '₹' + Number(s.bonus).toLocaleString('en-IN');
                document.getElementById('slipDeduction').innerText = '₹' + Number(s.deduction).toLocaleString('en-IN');
                document.getElementById('slipNetSalary').innerText = '₹' + Number(s.net_salary).toLocaleString('en-IN');

                const statusBadge = document.getElementById('slipPaymentStatusBadge');
                if (s.payment_status === 'paid') {
                    statusBadge.className = 'badge bg-success text-white fs-6 px-3 py-1.5';
                    statusBadge.innerText = 'Paid (' + (s.payment_date || '') + ')';
                } else {
                    statusBadge.className = 'badge bg-warning text-dark fs-6 px-3 py-1.5';
                    statusBadge.innerText = 'Pending';
                }

                new bootstrap.Modal(document.getElementById('salarySlipModal')).show();
            }
        });
    }

    function printSalarySlip() {
        const content = document.getElementById('printableSalarySlipArea').innerHTML;
        const win = window.open('', '_blank');
        win.document.write(`
            <html>
                <head>
                    <title>Salary Slip Print</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>body { font-family: sans-serif; padding: 20px; }</style>
                </head>
                <body onload="window.print(); window.close();">
                    ${content}
                </body>
            </html>
        `);
        win.document.close();
    }

    function exportPayrollExcel() {
        const monthYear = document.getElementById('payrollMonthFilter').value;
        fetch(`<?= BASE_URL ?>/api/export?type=payroll&month_year=${monthYear}`)
        .then(res => res.json())
        .then(res => {
            if (res.success && res.rows && res.rows.length > 0) {
                const worksheet = XLSX.utils.json_to_sheet(res.rows);
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, "Payroll");
                XLSX.writeFile(workbook, `${res.filename}.xlsx`);
            } else {
                Swal.fire('Export Info', 'No payroll records available to export for this month.', 'info');
            }
        });
    }
</script>
