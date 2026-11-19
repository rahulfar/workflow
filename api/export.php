<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireAdmin();
$db = Database::getConnection();
ensurePayrollSchema($db);

$reportType = sanitize($_GET['type'] ?? ($_POST['type'] ?? 'payroll'));
$monthYear = sanitize($_GET['month_year'] ?? ($_POST['month_year'] ?? date('Y-m')));
$format = sanitize($_GET['format'] ?? ($_POST['format'] ?? 'json'));

switch ($reportType) {
    case 'payroll':
        getPayrollReportData($db, $monthYear, $format);
        break;

    case 'salary':
        getSalaryReportData($db, $format);
        break;

    case 'attendance':
        getAttendanceReportData($db, $monthYear, $format);
        break;

    case 'leave':
        getLeaveReportData($db, $monthYear, $format);
        break;

    case 'employee':
        getEmployeeReportData($db, $format);
        break;

    default:
        jsonResponse(false, 'Invalid export report type', [], 400);
}

function getPayrollReportData(PDO $db, string $monthYear, string $format): void {
    // Refresh payroll entries first
    $users = $db->query("SELECT id FROM users WHERE status = 'active'")->fetchAll();
    foreach ($users as $u) {
        if (function_exists('recalculateUserPayroll')) {
            recalculateUserPayroll($db, (int)$u['id'], $monthYear);
        }
    }

    $stmt = $db->prepare("
        SELECT u.name as employee_name, u.employee_code, u.id as user_id, d.name as department_name,
               p.basic_salary, p.allowance, p.bonus, p.deduction, p.present_days, p.half_days,
               p.absent_days, p.paid_leave_days, p.holiday_days, p.net_salary, p.payment_status
        FROM payrolls p
        JOIN users u ON p.user_id = u.id
        JOIN departments d ON u.department_id = d.id
        WHERE p.month_year = ?
        ORDER BY u.id ASC
    ");
    $stmt->execute([$monthYear]);
    $raw = $stmt->fetchAll();

    $data = [];
    foreach ($raw as $r) {
        $empCode = !empty($r['employee_code']) ? $r['employee_code'] : ('EMP-' . str_pad($r['user_id'], 3, '0', STR_PAD_LEFT));
        $data[] = [
            'Employee Name'  => $r['employee_name'],
            'Employee ID'    => $empCode,
            'Department'     => $r['department_name'],
            'Basic Salary'   => (float)$r['basic_salary'],
            'Allowance'      => (float)$r['allowance'],
            'Bonus'          => (float)$r['bonus'],
            'Deduction'      => (float)$r['deduction'],
            'Present Days'   => (float)$r['present_days'],
            'Half Days'      => (float)$r['half_days'],
            'Absent Days'    => (float)$r['absent_days'],
            'Paid Leave'     => (float)$r['paid_leave_days'],
            'Holiday Count'  => (int)$r['holiday_days'],
            'Net Salary'     => (float)$r['net_salary'],
            'Payment Status' => ucfirst($r['payment_status'])
        ];
    }

    outputReportResponse($data, "payroll_report_{$monthYear}", $format);
}

function getSalaryReportData(PDO $db, string $format): void {
    $stmt = $db->query("
        SELECT u.name as employee_name, u.employee_code, u.designation, d.name as department_name,
               COALESCE(es.monthly_salary, 0.00) as basic_salary,
               COALESCE(es.allowance, 0.00) as allowance,
               u.status as employee_status
        FROM users u
        JOIN departments d ON u.department_id = d.id
        LEFT JOIN employee_salaries es ON u.id = es.user_id
        ORDER BY u.id ASC
    ");
    $data = $stmt->fetchAll();
    outputReportResponse($data, "salary_master_report", $format);
}

function getAttendanceReportData(PDO $db, string $monthYear, string $format): void {
    $startDate = "{$monthYear}-01";
    $daysInMonth = (int)date('t', strtotime($startDate));
    $endDate = "{$monthYear}-{$daysInMonth}";

    $stmt = $db->prepare("
        SELECT u.name as employee_name, u.employee_code, u.designation, d.name as department_name,
               a.date, a.clock_in, a.clock_out, a.total_seconds, a.status, a.late_minutes, a.early_exit_minutes
        FROM attendance a
        JOIN users u ON a.user_id = u.id
        JOIN departments d ON u.department_id = d.id
        WHERE a.date >= ? AND a.date <= ?
        ORDER BY a.date DESC, u.name ASC
    ");
    $stmt->execute([$startDate, $endDate]);
    $raw = $stmt->fetchAll();

    $data = [];
    foreach ($raw as $r) {
        $cInStr = $r['clock_in'] ? (strlen($r['clock_in']) <= 8 ? ($r['date'] . ' ' . $r['clock_in']) : $r['clock_in']) : null;
        $cOutStr = $r['clock_out'] ? (strlen($r['clock_out']) <= 8 ? ($r['date'] . ' ' . $r['clock_out']) : $r['clock_out']) : null;

        $data[] = [
            'Employee Name' => $r['employee_name'],
            'Employee Code' => $r['employee_code'],
            'Department' => $r['department_name'],
            'Date' => formatISTDate($r['date']),
            'Clock In' => $cInStr ? formatISTTime($cInStr) : '--',
            'Clock Out' => $cOutStr ? formatISTTime($cOutStr) : '--',
            'Working Hours' => formatDuration((int)$r['total_seconds']),
            'Late Minutes' => (int)$r['late_minutes'],
            'Early Exit Minutes' => (int)$r['early_exit_minutes'],
            'Attendance Status' => ucfirst($r['status'])
        ];
    }

    outputReportResponse($data, "attendance_report_{$monthYear}", $format);
}

function getLeaveReportData(PDO $db, string $monthYear, string $format): void {
    $startDate = "{$monthYear}-01";
    $daysInMonth = (int)date('t', strtotime($startDate));
    $endDate = "{$monthYear}-{$daysInMonth}";

    $stmt = $db->prepare("
        SELECT u.name as employee_name, u.employee_code, u.designation, d.name as department_name,
               l.leave_type, l.start_date, l.end_date, l.total_days, l.paid_days, l.unpaid_days,
               l.reason, l.status, m.name as approved_by_name, l.comment
        FROM leave_requests l
        JOIN users u ON l.user_id = u.id
        JOIN departments d ON u.department_id = d.id
        LEFT JOIN users m ON l.approved_by = m.id
        WHERE l.start_date >= ? AND l.start_date <= ?
        ORDER BY l.id DESC
    ");
    $stmt->execute([$startDate, $endDate]);
    $raw = $stmt->fetchAll();

    $data = [];
    foreach ($raw as $r) {
        $data[] = [
            'Employee Name' => $r['employee_name'],
            'Employee Code' => $r['employee_code'],
            'Department' => $r['department_name'],
            'Leave Type' => ucfirst($r['leave_type']),
            'From Date' => formatISTDate($r['start_date']),
            'To Date' => formatISTDate($r['end_date']),
            'Total Days' => $r['total_days'],
            'Paid Days' => $r['paid_days'],
            'Unpaid Days' => $r['unpaid_days'],
            'Reason' => $r['reason'],
            'Approval Status' => ucfirst($r['status']),
            'Approver' => $r['approved_by_name'] ?: 'N/A',
            'Remarks' => $r['comment'] ?: 'N/A'
        ];
    }

    outputReportResponse($data, "leave_report_{$monthYear}", $format);
}

function getEmployeeReportData(PDO $db, string $format): void {
    $stmt = $db->query("
        SELECT u.name as employee_name, u.employee_code, u.email, u.phone, u.designation,
               d.name as department_name, r.display_name as role_title, u.shift_timing,
               ep.joining_date, ep.emergency_contact, u.status
        FROM users u
        JOIN roles r ON u.role_id = r.id
        JOIN departments d ON u.department_id = d.id
        LEFT JOIN employee_profiles ep ON u.id = ep.user_id
        ORDER BY u.id ASC
    ");
    $raw = $stmt->fetchAll();

    $data = [];
    foreach ($raw as $r) {
        $data[] = [
            'Employee Name' => $r['employee_name'],
            'Employee ID' => $r['employee_code'] ?: ('EMP-' . str_pad($r['id'] ?? 1, 3, '0', STR_PAD_LEFT)),
            'Department' => $r['department_name'],
            'Role' => $r['role_title'],
            'Designation' => $r['designation'],
            'Email' => $r['email'],
            'Phone' => $r['phone'] ?: 'N/A',
            'Shift Timing' => $r['shift_timing'],
            'Joining Date' => !empty($r['joining_date']) ? formatISTDate($r['joining_date']) : 'N/A',
            'Emergency Contact' => $r['emergency_contact'] ?: 'N/A',
            'Account Status' => ucfirst($r['status'])
        ];
    }

    outputReportResponse($data, "employee_directory_report", $format);
}

function outputReportResponse(array $data, string $filename, string $format): void {
    if ($format === 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename . '.csv');
        $output = fopen('php://output', 'w');
        if (!empty($data)) {
            fputcsv($output, array_keys($data[0]));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        fclose($output);
        exit;
    }

    jsonResponse(true, 'Report dataset generated', [
        'filename' => $filename,
        'rows' => $data
    ]);
}
