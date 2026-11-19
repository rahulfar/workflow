<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

// Role restriction: Admin & Manager can view details; only Admin can mutate/edit
$user = requireLogin();
if (!hasRole(['admin', 'manager', 'product_manager'])) {
    jsonResponse(false, 'Access denied. Authorization required.', [], 403);
}

$db = Database::getConnection();
ensurePayrollSchema($db);

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

$readOnlyActions = ['get_salaries', 'get_payroll_summary', 'get_salary_slip'];
if (!in_array($action, $readOnlyActions) && !hasRole('admin')) {
    jsonResponse(false, 'Access denied. Admin privileges required for payroll modifications.', [], 403);
}

switch ($action) {
    case 'get_salaries':
        getEmployeeSalaries($db);
        break;

    case 'save_salary':
        saveEmployeeSalary($db, $user);
        break;

    case 'delete_salary':
        deleteEmployeeSalary($db, $user);
        break;

    case 'generate_payroll':
        generateMonthlyPayroll($db, $user);
        break;

    case 'update_payroll':
        updatePayrollRecord($db, $user);
        break;

    case 'get_payroll_summary':
        getPayrollSummary($db);
        break;

    case 'get_salary_slip':
        getSalarySlipDetails($db);
        break;

    case 'mark_paid':
        markPayrollPaid($db, $user);
        break;

    default:
        jsonResponse(false, 'Invalid payroll action', [], 400);
}

function getEmployeeSalaries(PDO $db): void {
    try {
        ensurePayrollSchema($db);

        $defaultSalaries = [
            'Rahul' => 30000.00,
            'Anjali' => 25000.00,
            'Mrinali' => 25000.00,
            'Uvaish' => 25000.00,
            'Priyansh' => 15000.00,
            'Aman' => 7000.00,
            'Uday' => 20000.00,
            'Shivanshu' => 10000.00,
            'Sarthak' => 10000.00,
            'Abhay' => 5000.00,
            'Kartikey' => 50000.00
        ];

        $stmt = $db->query("
            SELECT u.id as user_id, u.name, u.employee_code, u.designation, 
                   COALESCE(d.name, 'General') as department_name,
                   COALESCE(es.monthly_salary, 0.00) as monthly_salary,
                   COALESCE(es.allowance, 0.00) as allowance,
                   es.id as salary_id
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.id
            LEFT JOIN employee_salaries es ON u.id = es.user_id
            ORDER BY u.id ASC
        ");
        $salaries = $stmt->fetchAll();

        foreach ($salaries as &$s) {
            if ((float)$s['monthly_salary'] <= 0) {
                $name = trim($s['name']);
                $s['monthly_salary'] = $defaultSalaries[$name] ?? 20000.00;
            }
        }
        unset($s);

        jsonResponse(true, 'Salaries fetched successfully', ['salaries' => $salaries]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch salaries: ' . $e->getMessage());
    }
}

function saveEmployeeSalary(PDO $db, array $user): void {
    $userId = (int)($_POST['user_id'] ?? 0);
    $monthlySalary = (float)($_POST['monthly_salary'] ?? 0);
    $allowance = (float)($_POST['allowance'] ?? 0);

    if ($userId <= 0 || $monthlySalary < 0) {
        jsonResponse(false, 'Invalid user ID or salary amount');
    }

    try {
        $cStmt = $db->prepare("SELECT id FROM employee_salaries WHERE user_id = ?");
        $cStmt->execute([$userId]);
        $salId = $cStmt->fetchColumn();

        if ($salId) {
            $upd = $db->prepare("UPDATE employee_salaries SET monthly_salary = ?, allowance = ? WHERE id = ?");
            $upd->execute([$monthlySalary, $allowance, $salId]);
        } else {
            $ins = $db->prepare("INSERT INTO employee_salaries (user_id, monthly_salary, allowance) VALUES (?, ?, ?)");
            $ins->execute([$userId, $monthlySalary, $allowance]);
        }

        // If payroll for current month exists, update basic_salary & recalculate
        $currentMonth = date('Y-m');
        recalculateUserPayroll($db, $userId, $currentMonth);

        logActivity('Save Salary', 'Payroll', "Admin updated salary for user ID {$userId} to ₹{$monthlySalary}");
        jsonResponse(true, 'Employee salary saved successfully');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to save salary: ' . $e->getMessage());
    }
}

function deleteEmployeeSalary(PDO $db, array $user): void {
    $userId = (int)($_POST['user_id'] ?? 0);
    if ($userId <= 0) {
        jsonResponse(false, 'Invalid employee ID');
    }

    try {
        $stmt = $db->prepare("DELETE FROM employee_salaries WHERE user_id = ?");
        $stmt->execute([$userId]);

        logActivity('Delete Salary', 'Payroll', "Admin deleted salary record for user ID {$userId}");
        jsonResponse(true, 'Employee salary record deleted');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to delete salary: ' . $e->getMessage());
    }
}

function generateMonthlyPayroll(PDO $db, array $user): void {
    $monthYear = sanitize($_POST['month_year'] ?? date('Y-m'));
    if (!preg_match('/^\d{4}-\d{2}$/', $monthYear)) {
        jsonResponse(false, 'Invalid Month-Year format. Expected YYYY-MM.');
    }

    try {
        $users = $db->query("SELECT id, name FROM users WHERE status = 'active'")->fetchAll();
        $generatedCount = 0;

        foreach ($users as $u) {
            recalculateUserPayroll($db, (int)$u['id'], $monthYear);
            $generatedCount++;
        }

        logActivity('Generate Payroll', 'Payroll', "Admin generated monthly payroll for {$monthYear} for {$generatedCount} employees");
        jsonResponse(true, "Monthly payroll for {$monthYear} generated/refreshed successfully for {$generatedCount} employees!");
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to generate payroll: ' . $e->getMessage());
    }
}

function recalculateUserPayroll(PDO $db, int $userId, string $monthYear): void {
    // Fetch basic salary setting
    $salStmt = $db->prepare("SELECT monthly_salary, allowance FROM employee_salaries WHERE user_id = ?");
    $salStmt->execute([$userId]);
    $salData = $salStmt->fetch();
    $basicSalary = (float)($salData['monthly_salary'] ?? 0.00);
    $defaultAllowance = (float)($salData['allowance'] ?? 0.00);

    // Auto-seed basic salary if missing or 0
    if ($basicSalary <= 0) {
        $uStmt = $db->prepare("SELECT name FROM users WHERE id = ?");
        $uStmt->execute([$userId]);
        $uName = $uStmt->fetchColumn() ?: '';

        $defaults = [
            'Rahul' => 30000.00, 'Anjali' => 25000.00, 'Mrinali' => 25000.00, 'Uvaish' => 25000.00,
            'Priyansh' => 15000.00, 'Aman' => 7000.00, 'Uday' => 20000.00,
            'Shivanshu' => 10000.00, 'Sarthak' => 10000.00, 'Abhay' => 5000.00,
            'Kartikey' => 50000.00
        ];
        $basicSalary = $defaults[$uName] ?? 20000.00;
        $defaultAllowance = 0.00;

        $cSal = $db->prepare("SELECT id FROM employee_salaries WHERE user_id = ?");
        $cSal->execute([$userId]);
        $salId = $cSal->fetchColumn();
        if ($salId) {
            $uSal = $db->prepare("UPDATE employee_salaries SET monthly_salary = ? WHERE id = ?");
            $uSal->execute([$basicSalary, $salId]);
        } else {
            $iSal = $db->prepare("INSERT INTO employee_salaries (user_id, monthly_salary, allowance) VALUES (?, ?, 0.00)");
            $iSal->execute([$userId, $basicSalary]);
        }
    }

    // Get existing payroll record to keep existing bonus / manual deduction / payment status / remarks
    $pCheck = $db->prepare("SELECT * FROM payrolls WHERE user_id = ? AND month_year = ?");
    $pCheck->execute([$userId, $monthYear]);
    $existing = $pCheck->fetch();

    $allowance = $existing ? (float)$existing['allowance'] : $defaultAllowance;
    $bonus = $existing ? (float)$existing['bonus'] : 0.00;
    $paymentStatus = $existing ? $existing['payment_status'] : 'pending';
    $paymentDate = $existing ? $existing['payment_date'] : null;
    $remarks = $existing ? $existing['remarks'] : null;

    // Calculate attendance and leave stats for the month
    $stats = calculateAttendanceSalaryStats($userId, $monthYear);
    $totalDays = $stats['total_days_in_month'];
    $completedDays = $stats['completed_days'];
    $dailySalary = ($totalDays > 0) ? ($basicSalary / $totalDays) : 0;

    $paidDaysCount = $stats['present_days'] + ($stats['half_days'] * 0.5) + $stats['paid_leave_days'] + $stats['holiday_days'];
    $earnedSalary = round($dailySalary * $paidDaysCount, 2);

    // Auto deductions
    $absentDeduction = $stats['absent_days'] * $dailySalary;
    $halfDayDeduction = $stats['half_days'] * 0.5 * $dailySalary;
    $unpaidLeaveDeduction = $stats['unpaid_leave_days'] * $dailySalary;
    $totalAutoDeduction = round($absentDeduction + $halfDayDeduction + $unpaidLeaveDeduction, 2);

    // If admin explicitly set a custom deduction value in updatePayrollRecord, preserve it
    if ($existing && isset($existing['deduction']) && (float)$existing['deduction'] > 0 && (float)$existing['deduction'] != $totalAutoDeduction) {
        $totalDeduction = (float)$existing['deduction'];
    } else {
        $totalDeduction = $totalAutoDeduction;
    }

    $expectedSalary = max(0, round($basicSalary + $allowance + $bonus - $totalDeduction, 2));

    // If active month cycle is in progress, compute current net salary from earned salary
    if ($completedDays < $totalDays && $monthYear === date('Y-m')) {
        $netSalary = max(0, round($earnedSalary + $allowance + $bonus - $totalDeduction, 2));
    } else {
        $netSalary = $expectedSalary;
    }

    if ($existing) {
        $upd = $db->prepare("
            UPDATE payrolls SET
                basic_salary = ?, allowance = ?, bonus = ?, deduction = ?,
                present_days = ?, half_days = ?, absent_days = ?, paid_leave_days = ?, unpaid_leave_days = ?,
                holiday_days = ?, working_hours = ?, completed_days = ?, earned_salary = ?, net_salary = ?, payment_status = ?, payment_date = ?, remarks = ?
            WHERE id = ?
        ");
        $upd->execute([
            $basicSalary, $allowance, $bonus, $totalDeduction,
            $stats['present_days'], $stats['half_days'], $stats['absent_days'], $stats['paid_leave_days'], $stats['unpaid_leave_days'],
            $stats['holiday_days'], $stats['working_hours'], $completedDays, $earnedSalary, $netSalary, $paymentStatus, $paymentDate, $remarks, $existing['id']
        ]);
    } else {
        $ins = $db->prepare("
            INSERT INTO payrolls (
                user_id, month_year, basic_salary, allowance, bonus, deduction,
                present_days, half_days, absent_days, paid_leave_days, unpaid_leave_days,
                holiday_days, working_hours, completed_days, earned_salary, net_salary, payment_status, payment_date, remarks
            ) VALUES (
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");
        $ins->execute([
            $userId, $monthYear, $basicSalary, $allowance, $bonus, $totalDeduction,
            $stats['present_days'], $stats['half_days'], $stats['absent_days'], $stats['paid_leave_days'], $stats['unpaid_leave_days'],
            $stats['holiday_days'], $stats['working_hours'], $completedDays, $earnedSalary, $netSalary, $paymentStatus, $paymentDate, $remarks
        ]);
    }
}

function updatePayrollRecord(PDO $db, array $user): void {
    $id = (int)($_POST['payroll_id'] ?? 0);
    $allowance = (float)($_POST['allowance'] ?? 0);
    $bonus = (float)($_POST['bonus'] ?? 0);
    $deduction = (float)($_POST['deduction'] ?? 0);
    $paymentStatus = sanitize($_POST['payment_status'] ?? 'pending');
    $paymentDate = !empty($_POST['payment_date']) ? sanitize($_POST['payment_date']) : ($paymentStatus === 'paid' ? date('Y-m-d') : null);
    $remarks = sanitize($_POST['remarks'] ?? '');

    if ($id <= 0) {
        jsonResponse(false, 'Invalid payroll ID');
    }

    try {
        $stmt = $db->prepare("SELECT * FROM payrolls WHERE id = ?");
        $stmt->execute([$id]);
        $p = $stmt->fetch();

        if (!$p) {
            jsonResponse(false, 'Payroll record not found');
        }

        $basicSalary = (float)$p['basic_salary'];
        $netSalary = max(0, $basicSalary + $allowance + $bonus - $deduction);

        $upd = $db->prepare("
            UPDATE payrolls
            SET allowance = ?, bonus = ?, deduction = ?, net_salary = ?, payment_status = ?, payment_date = ?, remarks = ?
            WHERE id = ?
        ");
        $upd->execute([$allowance, $bonus, $deduction, $netSalary, $paymentStatus, $paymentDate, $remarks, $id]);

        logActivity('Update Payroll', 'Payroll', "Admin updated payroll ID {$id}. Net Salary: ₹{$netSalary}, Status: {$paymentStatus}");
        jsonResponse(true, 'Payroll record updated & recalculated successfully', ['net_salary' => $netSalary]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update payroll: ' . $e->getMessage());
    }
}

function getPayrollSummary(PDO $db): void {
    $monthYear = sanitize($_GET['month_year'] ?? ($_POST['month_year'] ?? date('Y-m')));

    try {
        ensurePayrollSchema($db);

        $users = $db->query("SELECT id FROM users")->fetchAll();
        foreach ($users as $u) {
            recalculateUserPayroll($db, (int)$u['id'], $monthYear);
        }

        $sampleStats = calculateAttendanceSalaryStats((int)($users[0]['id'] ?? 1), $monthYear);

        $stmt = $db->prepare("
            SELECT p.*, u.name as employee_name, u.avatar, u.employee_code, u.designation, u.department_id, COALESCE(d.name, 'General') as department_name
            FROM payrolls p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN departments d ON u.department_id = d.id
            WHERE p.month_year = ?
            ORDER BY u.id ASC
        ");
        $stmt->execute([$monthYear]);
        $rows = $stmt->fetchAll();

        $totalPayroll = 0.0;
        $paidSalaries = 0.0;
        $pendingSalaries = 0.0;
        $totalEmployees = count($rows);

        foreach ($rows as $r) {
            $net = (float)$r['net_salary'];
            $totalPayroll += $net;
            if ($r['payment_status'] === 'paid') {
                $paidSalaries += $net;
            } else {
                $pendingSalaries += $net;
            }
        }

        jsonResponse(true, 'Payroll summary retrieved', [
            'summary' => [
                'total_payroll' => $totalPayroll,
                'paid_salaries' => $paidSalaries,
                'pending_salaries' => $pendingSalaries,
                'total_employees' => $totalEmployees,
                'current_month' => $monthYear,
                'period_label' => $sampleStats['period_label'],
                'completed_days' => $sampleStats['completed_days'],
                'remaining_days' => $sampleStats['remaining_days'],
                'total_days_in_month' => $sampleStats['total_days_in_month'],
                'payroll_progress' => $sampleStats['payroll_progress']
            ],
            'payrolls' => $rows
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch payroll summary: ' . $e->getMessage());
    }
}

function getSalarySlipDetails(PDO $db): void {
    $id = (int)($_GET['id'] ?? ($_POST['payroll_id'] ?? 0));
    if ($id <= 0) {
        jsonResponse(false, 'Invalid payroll ID');
    }

    try {
        $stmt = $db->prepare("
            SELECT p.*, u.name as employee_name, u.email, u.phone, u.employee_code, u.designation,
                   d.name as department_name, ep.joining_date
            FROM payrolls p
            JOIN users u ON p.user_id = u.id
            JOIN departments d ON u.department_id = d.id
            LEFT JOIN employee_profiles ep ON u.id = ep.user_id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $slip = $stmt->fetch();

        if (!$slip) {
            jsonResponse(false, 'Salary slip record not found');
        }

        $company = [
            'name' => 'WorkFlow Pro Suite',
            'address' => 'Corporate Tech Hub, Phase 2, IT Park',
            'email' => 'admin@company.com',
            'phone' => '+91 9876543210'
        ];

        jsonResponse(true, 'Salary slip details loaded', [
            'slip' => $slip,
            'company' => $company
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch salary slip: ' . $e->getMessage());
    }
}

function markPayrollPaid(PDO $db, array $user): void {
    $id = (int)($_POST['payroll_id'] ?? ($_GET['payroll_id'] ?? 0));
    if ($id <= 0) {
        jsonResponse(false, 'Invalid payroll ID');
    }

    try {
        $today = date('Y-m-d');
        $stmt = $db->prepare("UPDATE payrolls SET payment_status = 'paid', payment_date = ? WHERE id = ?");
        $stmt->execute([$today, $id]);

        logActivity('Mark Payroll Paid', 'Payroll', "Admin marked payroll ID {$id} as paid on {$today}");
        jsonResponse(true, 'Payroll marked as paid successfully');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update payment status: ' . $e->getMessage());
    }
}
