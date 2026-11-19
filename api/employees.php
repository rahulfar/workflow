<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$db = Database::getConnection();
ensureEmployeeSchema($db);

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'list':
        listEmployees($db);
        break;
    case 'details':
        getEmployeeDetails($db);
        break;
    case 'get_next_code':
        requireAdmin();
        jsonResponse(true, 'Generated Code', ['employee_code' => generateEmployeeCode($db)]);
        break;
    case 'add':
        requireAdmin();
        addEmployee($db);
        break;
    case 'update':
        requireAdmin();
        updateEmployee($db);
        break;
    case 'delete':
        requireAdmin();
        deleteEmployee($db, $user);
        break;
    case 'toggle_status':
        requireAdmin();
        toggleEmployeeStatus($db, $user);
        break;
    case 'reset_password':
        requireAdmin();
        resetEmployeePassword($db);
        break;
    case 'add_role':
        requireAdmin();
        addNewRole($db);
        break;
    case 'list_roles':
    case 'get_roles':
        listRoles($db);
        break;
    case 'add_department':
        requireAdmin();
        addNewDepartmentQuick($db);
        break;
    default:
        jsonResponse(false, 'Invalid employee action', [], 400);
}

function listEmployees($db): void {
    $search = sanitize($_GET['q'] ?? ($_POST['q'] ?? ''));
    $deptId = (int)($_GET['department_id'] ?? ($_POST['department_id'] ?? 0));
    $roleId = (int)($_GET['role_id'] ?? ($_POST['role_id'] ?? 0));
    $status = sanitize($_GET['status'] ?? ($_POST['status'] ?? ''));
    $shift = sanitize($_GET['shift_timing'] ?? ($_POST['shift_timing'] ?? ''));

    $where = ["1=1"];
    $params = [];

    if (!empty($search)) {
        $where[] = "(u.name LIKE ? OR u.email LIKE ? OR u.phone LIKE ? OR u.designation LIKE ? OR u.employee_code LIKE ?)";
        $term = "%{$search}%";
        $params = array_merge($params, [$term, $term, $term, $term, $term]);
    }

    if ($deptId > 0) {
        $where[] = "u.department_id = ?";
        $params[] = $deptId;
    }

    if ($roleId > 0) {
        $where[] = "u.role_id = ?";
        $params[] = $roleId;
    }

    if (!empty($status)) {
        $where[] = "u.status = ?";
        $params[] = $status;
    }

    if (!empty($shift)) {
        $where[] = "u.shift_timing = ?";
        $params[] = $shift;
    }

    $whereSql = implode(' AND ', $where);

    $stmt = $db->prepare("
        SELECT u.*, r.name as role_name, r.display_name as role_title, d.name as department_name, d.code as department_code,
               ep.joining_date, ep.address, ep.emergency_contact, ep.experience, ep.skills
        FROM users u
        JOIN roles r ON u.role_id = r.id
        JOIN departments d ON u.department_id = d.id
        LEFT JOIN employee_profiles ep ON u.id = ep.user_id
        WHERE {$whereSql}
        ORDER BY u.id ASC
    ");
    $stmt->execute($params);
    $employees = $stmt->fetchAll();

    // Formatting
    foreach ($employees as &$emp) {
        if (empty($emp['employee_code'])) {
            $emp['employee_code'] = 'EMP-' . str_pad($emp['id'], 3, '0', STR_PAD_LEFT);
        }
        if (empty($emp['shift_timing'])) {
            $emp['shift_timing'] = '09:40 AM - 06:30 PM (Fixed Office)';
        }
        $emp['avatar_url'] = (!empty($emp['avatar']) && (strpos($emp['avatar'], 'http') === 0))
            ? $emp['avatar']
            : (!empty($emp['avatar'])
                ? BASE_URL . '/uploads/avatars/' . $emp['avatar']
                : 'https://ui-avatars.com/api/?name=' . urlencode($emp['name']) . '&background=4f46e5&color=fff');
    }

    // Counters
    $totalCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $activeCount = $db->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn();
    $inactiveCount = $db->query("SELECT COUNT(*) FROM users WHERE status = 'inactive'")->fetchColumn();
    $newThisMonth = $db->query("SELECT COUNT(*) FROM employee_profiles WHERE MONTH(joining_date) = MONTH(CURDATE()) AND YEAR(joining_date) = YEAR(CURDATE())")->fetchColumn();

    jsonResponse(true, 'Employees fetched successfully', [
        'employees' => $employees,
        'counts' => [
            'total' => (int)$totalCount,
            'active' => (int)$activeCount,
            'inactive' => (int)$inactiveCount,
            'new_this_month' => (int)$newThisMonth
        ]
    ]);
}

function handleAvatarUpload(): ?string {
    if (isset($_FILES['avatar_file']) && $_FILES['avatar_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['avatar_file']['tmp_name'];
        $fileName = $_FILES['avatar_file']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $uploadDir = __DIR__ . '/../public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $newFileName = 'emp_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                return $newFileName;
            }
        }
    }
    return null;
}

function addEmployee($db): void {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $roleId = (int)($_POST['role_id'] ?? 0);
    $departmentId = (int)($_POST['department_id'] ?? 0);
    $designation = sanitize($_POST['designation'] ?? '');
    $shiftTiming = sanitize($_POST['shift_timing'] ?? '09:40 AM - 06:30 PM (Fixed Office)');
    $employeeCode = sanitize($_POST['employee_code'] ?? '');
    $joiningDate = sanitize($_POST['joining_date'] ?? date('Y-m-d'));
    $address = sanitize($_POST['address'] ?? '');
    $emergencyContact = sanitize($_POST['emergency_contact'] ?? '');
    $status = sanitize($_POST['status'] ?? 'active');

    // Auto-create new role if passed via form
    $newRoleTitle = trim(sanitize($_POST['new_role_title'] ?? ($_POST['custom_role_name'] ?? '')));
    if ($roleId <= 0 && !empty($newRoleTitle)) {
        $roleId = createOrFetchRoleId($db, $newRoleTitle, $_POST['new_role_slug'] ?? '', $_POST['new_role_desc'] ?? '');
    }

    // Auto-create new department if passed via form
    $newDeptName = trim(sanitize($_POST['new_department_name'] ?? ''));
    if ($departmentId <= 0 && !empty($newDeptName)) {
        $departmentId = createOrFetchDeptId($db, $newDeptName, $_POST['new_department_code'] ?? '');
    }

    if (empty($name) || empty($email) || empty($password) || $roleId <= 0 || $departmentId <= 0 || empty($designation)) {
        jsonResponse(false, 'Please fill in all required fields (Name, Email, Password, Department, Role, Designation).');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(false, 'Invalid email address format.');
    }

    // Check email uniqueness
    $check = $db->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        jsonResponse(false, 'An employee with this email address already exists.');
    }

    if (empty($employeeCode)) {
        $employeeCode = generateEmployeeCode($db);
    }

    $uploadedAvatar = handleAvatarUpload();
    $avatar = $uploadedAvatar ?: 'default_avatar.png';
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $db->beginTransaction();

        $insUser = $db->prepare("
            INSERT INTO users (role_id, department_id, employee_code, name, email, password, phone, avatar, designation, shift_timing, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insUser->execute([$roleId, $departmentId, $employeeCode, $name, $email, $passwordHash, $phone, $avatar, $designation, $shiftTiming, $status]);
        $newUserId = $db->lastInsertId();

        $insProfile = $db->prepare("
            INSERT INTO employee_profiles (user_id, joining_date, address, emergency_contact)
            VALUES (?, ?, ?, ?)
        ");
        $insProfile->execute([$newUserId, $joiningDate, $address, $emergencyContact]);

        $db->commit();

        logActivity('Add Employee', 'Employee Management', "Admin added new employee: {$name} ({$employeeCode})");

        jsonResponse(true, "Employee {$name} added successfully!", ['user_id' => $newUserId]);
    } catch (Exception $e) {
        $db->rollBack();
        jsonResponse(false, 'Failed to add employee: ' . $e->getMessage());
    }
}

function updateEmployee($db): void {
    $userId = (int)($_POST['id'] ?? 0);
    if ($userId <= 0) {
        jsonResponse(false, 'Invalid employee ID.');
    }

    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $roleId = (int)($_POST['role_id'] ?? 0);
    $departmentId = (int)($_POST['department_id'] ?? 0);
    $designation = sanitize($_POST['designation'] ?? '');
    $shiftTiming = sanitize($_POST['shift_timing'] ?? '09:40 AM - 06:30 PM (Fixed Office)');
    $employeeCode = sanitize($_POST['employee_code'] ?? '');
    $joiningDate = sanitize($_POST['joining_date'] ?? date('Y-m-d'));
    $address = sanitize($_POST['address'] ?? '');
    $emergencyContact = sanitize($_POST['emergency_contact'] ?? '');
    $status = sanitize($_POST['status'] ?? 'active');

    // Auto-create new role if passed via form
    $newRoleTitle = trim(sanitize($_POST['new_role_title'] ?? ($_POST['custom_role_name'] ?? '')));
    if ($roleId <= 0 && !empty($newRoleTitle)) {
        $roleId = createOrFetchRoleId($db, $newRoleTitle, $_POST['new_role_slug'] ?? '', $_POST['new_role_desc'] ?? '');
    }

    // Auto-create new department if passed via form
    $newDeptName = trim(sanitize($_POST['new_department_name'] ?? ''));
    if ($departmentId <= 0 && !empty($newDeptName)) {
        $departmentId = createOrFetchDeptId($db, $newDeptName, $_POST['new_department_code'] ?? '');
    }

    if (empty($name) || empty($email) || $roleId <= 0 || $departmentId <= 0 || empty($designation)) {
        jsonResponse(false, 'Please fill in all required fields.');
    }

    // Check email uniqueness
    $check = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check->execute([$email, $userId]);
    if ($check->fetch()) {
        jsonResponse(false, 'Email address is already in use by another employee.');
    }

    $uploadedAvatar = handleAvatarUpload();

    try {
        $db->beginTransaction();

        if ($uploadedAvatar) {
            $updUser = $db->prepare("
                UPDATE users SET role_id = ?, department_id = ?, employee_code = ?, name = ?, email = ?, phone = ?, avatar = ?, designation = ?, shift_timing = ?, status = ?
                WHERE id = ?
            ");
            $updUser->execute([$roleId, $departmentId, $employeeCode, $name, $email, $phone, $uploadedAvatar, $designation, $shiftTiming, $status, $userId]);
        } else {
            $updUser = $db->prepare("
                UPDATE users SET role_id = ?, department_id = ?, employee_code = ?, name = ?, email = ?, phone = ?, designation = ?, shift_timing = ?, status = ?
                WHERE id = ?
            ");
            $updUser->execute([$roleId, $departmentId, $employeeCode, $name, $email, $phone, $designation, $shiftTiming, $status, $userId]);
        }

        if (!empty($password)) {
            $passHash = password_hash($password, PASSWORD_BCRYPT);
            $updPass = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updPass->execute([$passHash, $userId]);
        }

        // Profile update or insert
        $profCheck = $db->prepare("SELECT id FROM employee_profiles WHERE user_id = ?");
        $profCheck->execute([$userId]);
        if ($profCheck->fetch()) {
            $updProf = $db->prepare("UPDATE employee_profiles SET joining_date = ?, address = ?, emergency_contact = ? WHERE user_id = ?");
            $updProf->execute([$joiningDate, $address, $emergencyContact, $userId]);
        } else {
            $insProf = $db->prepare("INSERT INTO employee_profiles (user_id, joining_date, address, emergency_contact) VALUES (?, ?, ?, ?)");
            $insProf->execute([$userId, $joiningDate, $address, $emergencyContact]);
        }

        $db->commit();

        logActivity('Update Employee', 'Employee Management', "Admin updated employee profile for ID #{$userId} ({$name})");

        jsonResponse(true, "Employee details updated successfully!");
    } catch (Exception $e) {
        $db->rollBack();
        jsonResponse(false, 'Failed to update employee: ' . $e->getMessage());
    }
}

function deleteEmployee($db, array $adminUser): void {
    $userId = (int)($_POST['id'] ?? ($_GET['id'] ?? 0));
    if ($userId <= 0) {
        jsonResponse(false, 'Invalid employee ID.');
    }

    if ($userId === (int)$adminUser['id']) {
        jsonResponse(false, 'You cannot delete your own Administrator account!');
    }

    try {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);

        logActivity('Delete Employee', 'Employee Management', "Admin deleted employee ID #{$userId}");

        jsonResponse(true, 'Employee account deleted successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Error deleting employee: ' . $e->getMessage());
    }
}

function toggleEmployeeStatus($db, array $adminUser): void {
    $userId = (int)($_POST['id'] ?? 0);
    $status = sanitize($_POST['status'] ?? '');

    if ($userId <= 0 || !in_array($status, ['active', 'inactive'])) {
        jsonResponse(false, 'Invalid arguments.');
    }

    if ($userId === (int)$adminUser['id'] && $status === 'inactive') {
        jsonResponse(false, 'You cannot deactivate your own Administrator account!');
    }

    try {
        $stmt = $db->prepare("UPDATE users SET status = ? WHERE id = ?");
        $stmt->execute([$status, $userId]);

        logActivity('Toggle Employee Status', 'Employee Management', "Admin changed status of user ID #{$userId} to {$status}");

        jsonResponse(true, "Employee status set to " . ucfirst($status) . "!");
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to toggle status: ' . $e->getMessage());
    }
}

function resetEmployeePassword($db): void {
    $userId = (int)($_POST['id'] ?? 0);
    $newPassword = $_POST['new_password'] ?? '';

    if ($userId <= 0 || empty($newPassword)) {
        jsonResponse(false, 'Employee ID and New Password are required.');
    }

    try {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $userId]);

        logActivity('Reset Employee Password', 'Employee Management', "Admin reset password for user ID #{$userId}");

        jsonResponse(true, 'Employee password has been reset successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to reset password: ' . $e->getMessage());
    }
}

function getEmployeeDetails($db): void {
    $userId = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
    if ($userId <= 0) {
        jsonResponse(false, 'Invalid employee ID.');
    }

    try {
        $stmt = $db->prepare("
            SELECT u.*, r.display_name as role_title, d.name as department_name,
                   ep.joining_date, ep.address, ep.emergency_contact, ep.skills, ep.experience, ep.bio
            FROM users u
            JOIN roles r ON u.role_id = r.id
            JOIN departments d ON u.department_id = d.id
            LEFT JOIN employee_profiles ep ON u.id = ep.user_id
            WHERE u.id = ?
        ");
        $stmt->execute([$userId]);
        $emp = $stmt->fetch();

        if (!$emp) {
            jsonResponse(false, 'Employee not found.');
        }

        // Attendance stats
        $attStmt = $db->prepare("SELECT * FROM attendance WHERE user_id = ? ORDER BY date DESC LIMIT 10");
        $attStmt->execute([$userId]);
        $attendance = $attStmt->fetchAll();

        // Tasks assigned
        $taskStmt = $db->prepare("SELECT * FROM tasks WHERE assigned_to = ? ORDER BY id DESC LIMIT 5");
        $taskStmt->execute([$userId]);
        $tasks = $taskStmt->fetchAll();

        // Total working seconds logged
        $totalSecs = $db->query("SELECT SUM(total_seconds) FROM attendance WHERE user_id = {$userId}")->fetchColumn() ?: 0;

        // Login history / activity
        $actStmt = $db->prepare("SELECT * FROM activity_logs WHERE user_id = ? ORDER BY id DESC LIMIT 10");
        $actStmt->execute([$userId]);
        $activities = $actStmt->fetchAll();

        jsonResponse(true, 'Employee details fetched', [
            'employee' => $emp,
            'attendance' => $attendance,
            'tasks' => $tasks,
            'total_worked_hours' => formatDuration((int)$totalSecs),
            'activities' => $activities
        ]);

    } catch (Exception $e) {
        jsonResponse(false, 'Error fetching details: ' . $e->getMessage());
    }
}

function listRoles($db): void {
    try {
        $roles = $db->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();
        jsonResponse(true, 'Roles fetched successfully', ['roles' => $roles]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to fetch roles: ' . $e->getMessage());
    }
}

function addNewRole($db): void {
    $displayName = trim(sanitize($_POST['display_name'] ?? ($_POST['role_title'] ?? ($_POST['role_name'] ?? ''))));
    $name = trim(sanitize($_POST['name'] ?? ($_POST['role_slug'] ?? '')));
    $description = trim(sanitize($_POST['description'] ?? ''));

    if (empty($displayName)) {
        jsonResponse(false, 'Role Name / Title is required.');
    }

    if (empty($name)) {
        $name = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $displayName));
        $name = trim($name, '_');
    }

    if (empty($description)) {
        $description = $displayName . ' - Custom System Access Role';
    }

    // Check if role already exists
    $chk = $db->prepare("SELECT id, name, display_name, description FROM roles WHERE LOWER(name) = LOWER(?) OR LOWER(display_name) = LOWER(?)");
    $chk->execute([$name, $displayName]);
    $existing = $chk->fetch();
    if ($existing) {
        jsonResponse(true, 'Role already exists and was selected.', ['role' => $existing, 'existing' => true]);
    }

    try {
        $ins = $db->prepare("INSERT INTO roles (name, display_name, description) VALUES (?, ?, ?)");
        $ins->execute([$name, $displayName, $description]);
        $newId = (int)$db->lastInsertId();

        $role = [
            'id' => $newId,
            'name' => $name,
            'display_name' => $displayName,
            'description' => $description
        ];

        logActivity('Add Role', 'Role Management', "Admin created new system access role: {$displayName} ({$name})");

        jsonResponse(true, "Role '{$displayName}' added successfully!", ['role' => $role, 'existing' => false]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to add role: ' . $e->getMessage());
    }
}

function addNewDepartmentQuick($db): void {
    $name = trim(sanitize($_POST['name'] ?? ($_POST['department_name'] ?? '')));
    $code = strtoupper(trim(sanitize($_POST['code'] ?? '')));
    $description = trim(sanitize($_POST['description'] ?? ''));

    if (empty($name)) {
        jsonResponse(false, 'Department name is required.');
    }

    if (empty($code)) {
        $clean = preg_replace('/[^A-Za-z]/', '', $name);
        $code = strtoupper(substr($clean ?: 'DPT', 0, 3));
    }

    if (empty($description)) {
        $description = $name . ' Department';
    }

    $chk = $db->prepare("SELECT id, name, code, description FROM departments WHERE LOWER(name) = LOWER(?) OR LOWER(code) = LOWER(?)");
    $chk->execute([$name, $code]);
    $existing = $chk->fetch();
    if ($existing) {
        jsonResponse(true, 'Department already exists and was selected.', ['department' => $existing, 'existing' => true]);
    }

    try {
        $ins = $db->prepare("INSERT INTO departments (name, code, description) VALUES (?, ?, ?)");
        $ins->execute([$name, $code, $description]);
        $newId = (int)$db->lastInsertId();

        $department = [
            'id' => $newId,
            'name' => $name,
            'code' => $code,
            'description' => $description
        ];

        logActivity('Add Department', 'Department Management', "Admin created new department: {$name} ({$code})");

        jsonResponse(true, "Department '{$name}' added successfully!", ['department' => $department, 'existing' => false]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to add department: ' . $e->getMessage());
    }
}

function createOrFetchRoleId($db, string $title, string $slug = '', string $desc = ''): int {
    if (empty($title)) return 0;
    if (empty($slug)) {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $title));
        $slug = trim($slug, '_');
    }
    if (empty($desc)) {
        $desc = $title . ' - Custom System Access Role';
    }

    $chk = $db->prepare("SELECT id FROM roles WHERE LOWER(name) = LOWER(?) OR LOWER(display_name) = LOWER(?)");
    $chk->execute([$slug, $title]);
    $existingId = $chk->fetchColumn();
    if ($existingId) {
        return (int)$existingId;
    }

    $ins = $db->prepare("INSERT INTO roles (name, display_name, description) VALUES (?, ?, ?)");
    $ins->execute([$slug, $title, $desc]);
    return (int)$db->lastInsertId();
}

function createOrFetchDeptId($db, string $name, string $code = '', string $desc = ''): int {
    if (empty($name)) return 0;
    if (empty($code)) {
        $clean = preg_replace('/[^A-Za-z]/', '', $name);
        $code = strtoupper(substr($clean ?: 'DPT', 0, 3));
    }
    if (empty($desc)) {
        $desc = $name . ' Department';
    }

    $chk = $db->prepare("SELECT id FROM departments WHERE LOWER(name) = LOWER(?) OR LOWER(code) = LOWER(?)");
    $chk->execute([$name, $code]);
    $existingId = $chk->fetchColumn();
    if ($existingId) {
        return (int)$existingId;
    }

    $ins = $db->prepare("INSERT INTO departments (name, code, description) VALUES (?, ?, ?)");
    $ins->execute([$name, $code, $desc]);
    return (int)$db->lastInsertId();
}

