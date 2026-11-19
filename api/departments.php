<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'create':
        if (!hasRole(['admin', 'manager', 'product_manager']) && !canCreateOrEditTasks($user)) {
            jsonResponse(false, 'Permission denied. Only administrators and managers can create departments.', [], 403);
        }
        createDepartment($user);
        break;
    case 'update':
    case 'edit':
        if (!hasRole(['admin', 'manager', 'product_manager']) && !canCreateOrEditTasks($user)) {
            jsonResponse(false, 'Permission denied. Only administrators and managers can edit departments.', [], 403);
        }
        updateDepartment($user);
        break;
    case 'delete':
        if (!hasRole(['admin', 'manager', 'product_manager']) && !canCreateOrEditTasks($user)) {
            jsonResponse(false, 'Permission denied. Only administrators and managers can delete departments.', [], 403);
        }
        deleteDepartment($user);
        break;
    case 'get':
    case 'details':
        getDepartmentDetails($user);
        break;
    default:
        jsonResponse(false, 'Invalid department action', [], 400);
}

function createDepartment(array $user): void {
    $name = sanitize($_POST['name'] ?? '');
    $code = strtoupper(trim(sanitize($_POST['code'] ?? '')));
    $description = sanitize($_POST['description'] ?? '');

    if (empty($name)) {
        jsonResponse(false, 'Department name is required.');
    }

    if (empty($code)) {
        $clean = preg_replace('/[^A-Za-z]/', '', $name);
        $code = strtoupper(substr($clean ?: 'DPT', 0, 3));
    }

    try {
        $db = Database::getConnection();

        // Check if department name or code already exists
        $chk = $db->prepare("SELECT id FROM departments WHERE LOWER(name) = LOWER(?) OR LOWER(code) = LOWER(?)");
        $chk->execute([$name, $code]);
        if ($chk->fetch()) {
            jsonResponse(false, "A department with the name '{$name}' or code '{$code}' already exists.");
        }

        $stmt = $db->prepare("
            INSERT INTO departments (name, code, description, created_at)
            VALUES (?, ?, ?, CURRENT_TIMESTAMP)
        ");
        $stmt->execute([$name, $code, $description]);
        $deptId = (int)$db->lastInsertId();
        if (!$deptId) {
            $deptId = (int)$db->query("SELECT MAX(id) FROM departments")->fetchColumn();
        }

        logActivity('Create Department', 'Departments', "Department #{$deptId} ('{$name}') created by {$user['name']}.");
        jsonResponse(true, 'Department created successfully!', [
            'department_id' => $deptId,
            'name' => $name,
            'code' => $code
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to create department: ' . $e->getMessage());
    }
}

function updateDepartment(array $user): void {
    $deptId = (int)($_POST['department_id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $code = strtoupper(trim(sanitize($_POST['code'] ?? '')));
    $description = sanitize($_POST['description'] ?? '');

    if (!$deptId) {
        jsonResponse(false, 'Department ID is required.');
    }

    if (empty($name)) {
        jsonResponse(false, 'Department name is required.');
    }

    if (empty($code)) {
        $clean = preg_replace('/[^A-Za-z]/', '', $name);
        $code = strtoupper(substr($clean ?: 'DPT', 0, 3));
    }

    try {
        $db = Database::getConnection();

        // Check if department exists
        $curr = $db->prepare("SELECT * FROM departments WHERE id = ?");
        $curr->execute([$deptId]);
        $department = $curr->fetch();
        if (!$department) {
            jsonResponse(false, 'Department not found.');
        }

        // Check duplicate name or code (excluding current)
        $dup = $db->prepare("SELECT id FROM departments WHERE (LOWER(name) = LOWER(?) OR LOWER(code) = LOWER(?)) AND id != ?");
        $dup->execute([$name, $code, $deptId]);
        if ($dup->fetch()) {
            jsonResponse(false, "Another department already uses the name '{$name}' or code '{$code}'.");
        }

        $upd = $db->prepare("
            UPDATE departments 
            SET name = ?, code = ?, description = ?
            WHERE id = ?
        ");
        $upd->execute([$name, $code, $description, $deptId]);

        logActivity('Edit Department', 'Departments', "Department #{$deptId} ('{$name}') updated by {$user['name']}.");
        jsonResponse(true, 'Department updated successfully!', [
            'department_id' => $deptId,
            'name' => $name,
            'code' => $code
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update department: ' . $e->getMessage());
    }
}

function deleteDepartment(array $user): void {
    $deptId = (int)($_POST['department_id'] ?? ($_GET['department_id'] ?? 0));
    if (!$deptId) {
        jsonResponse(false, 'Department ID is required.');
    }

    // Protect primary department #1 (Administration)
    if ($deptId === 1) {
        jsonResponse(false, 'The primary Administration department (#1) cannot be deleted as it is required by the system.');
    }

    try {
        $db = Database::getConnection();

        $curr = $db->prepare("SELECT * FROM departments WHERE id = ?");
        $curr->execute([$deptId]);
        $department = $curr->fetch();
        if (!$department) {
            jsonResponse(false, 'Department not found.');
        }

        // Reassign any employees to Administration (#1) to prevent orphaned users
        $db->prepare("UPDATE users SET department_id = 1 WHERE department_id = ?")->execute([$deptId]);

        // Reassign any projects to Administration (#1)
        $db->prepare("UPDATE projects SET department_id = 1 WHERE department_id = ?")->execute([$deptId]);

        // Reassign any tasks to Administration (#1)
        $db->prepare("UPDATE tasks SET department_id = 1 WHERE department_id = ?")->execute([$deptId]);

        // Delete department
        $del = $db->prepare("DELETE FROM departments WHERE id = ?");
        $del->execute([$deptId]);

        logActivity('Delete Department', 'Departments', "Department #{$deptId} ('{$department['name']}') deleted by {$user['name']}. Associated staff/tasks moved to Administration.");
        jsonResponse(true, "Department '{$department['name']}' deleted successfully.");
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to delete department: ' . $e->getMessage());
    }
}

function getDepartmentDetails(array $user): void {
    $deptId = (int)($_GET['department_id'] ?? ($_POST['department_id'] ?? 0));
    if (!$deptId) {
        jsonResponse(false, 'Department ID is required.');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT d.*, 
                   (SELECT COUNT(*) FROM users WHERE department_id = d.id) as total_users,
                   (SELECT COUNT(*) FROM tasks WHERE department_id = d.id) as total_tasks
            FROM departments d
            WHERE d.id = ?
        ");
        $stmt->execute([$deptId]);
        $dept = $stmt->fetch();

        if (!$dept) {
            jsonResponse(false, 'Department not found.');
        }

        jsonResponse(true, 'Department details fetched.', $dept);
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}
