<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'create':
        createProject($user);
        break;
    case 'update':
    case 'edit':
        updateProject($user);
        break;
    case 'delete':
        deleteProject($user);
        break;
    case 'get':
    case 'details':
        getProjectDetails($user);
        break;
    case 'assign_members':
        assignProjectMembers($user);
        break;
    case 'update_progress':
        updateProjectProgress($user);
        break;
    default:
        jsonResponse(false, 'Invalid project action', [], 400);
}

function createProject(array $user): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers and leads can create projects.', [], 403);
    }

    $name = sanitize($_POST['name'] ?? '');
    $code = sanitize($_POST['code'] ?? '');
    if (empty($code)) {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $name) ?: 'PRJ', 0, 3));
        $code = 'PRJ-' . $prefix . '-' . rand(100, 999);
    }
    $description = sanitize($_POST['description'] ?? '');
    
    // Default department to user's department for department leads (e.g. Uvaish -> SEO)
    $departmentId = (int)($_POST['department_id'] ?? ($user['department_id'] ?? 1));
    $priority = sanitize($_POST['priority'] ?? 'medium');
    $status = sanitize($_POST['status'] ?? 'in_progress');
    $progress = max(0, min(100, (int)($_POST['progress'] ?? 0)));
    $startDate = sanitize($_POST['start_date'] ?? date('Y-m-d'));
    $endDate = sanitize($_POST['end_date'] ?? date('Y-m-d', strtotime('+30 days')));

    if (empty($name)) {
        jsonResponse(false, 'Project Name is required.');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO projects (name, code, description, department_id, priority, status, progress, start_date, end_date, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $code, $description, $departmentId, $priority, $status, $progress, $startDate, $endDate, $user['id']]);
        $projectId = (int)$db->lastInsertId();
        if (!$projectId) {
            $projectId = (int)$db->query("SELECT MAX(id) FROM projects")->fetchColumn();
        }

        // Parse member_ids
        $memberIds = $_POST['member_ids'] ?? [];
        if (is_string($memberIds)) {
            $memberIds = array_filter(array_map('intval', explode(',', $memberIds)));
        } elseif (is_array($memberIds)) {
            $memberIds = array_filter(array_map('intval', $memberIds));
        }

        // Always include creator
        if (!in_array((int)$user['id'], $memberIds)) {
            $memberIds[] = (int)$user['id'];
        }

        $insMember = $db->prepare("INSERT INTO project_members (project_id, user_id, role_in_project) VALUES (?, ?, ?)");
        foreach ($memberIds as $mId) {
            $mId = (int)$mId;
            $roleInProj = ($mId === (int)$user['id']) ? 'Project Lead' : 'Team Member';
            try {
                $insMember->execute([$projectId, $mId, $roleInProj]);
            } catch (Exception $e) {}

            if ($mId !== (int)$user['id']) {
                sendNotification($mId, 'Assigned to Project', "You have been assigned to project '{$name}' by {$user['name']}.", "projects", 'project');
            }
        }

        logActivity('Create Project', 'Projects', "Project #{$projectId} ('{$name}') created by {$user['name']}.");
        jsonResponse(true, 'Project created successfully!', [
            'project_id' => $projectId,
            'name' => $name,
            'code' => $code
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to create project: ' . $e->getMessage());
    }
}

function updateProject(array $user): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers and leads can edit projects.', [], 403);
    }

    $projectId = (int)($_POST['project_id'] ?? 0);
    if (!$projectId) {
        jsonResponse(false, 'Project ID is required.');
    }

    try {
        $db = Database::getConnection();
        $pStmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
        $pStmt->execute([$projectId]);
        $project = $pStmt->fetch();

        if (!$project) {
            jsonResponse(false, 'Project not found.');
        }

        // Permission check: Admin, Manager, PM, or Creator, or Department Lead of project's department, or Senior Developer
        $isAdmin = hasRole('admin');
        $isPM = hasRole('product_manager');
        $isManager = hasRole('manager');
        $isCreator = ((int)$project['created_by'] === (int)$user['id']);
        $isDeptLead = (!empty($project['department_id']) && (int)$project['department_id'] === (int)$user['department_id']);
        $isLeadDev = ((int)$user['id'] === 4 || ($user['role_name'] ?? '') === 'senior_developer');

        if (!$isAdmin && !$isPM && !$isManager && !$isCreator && !$isDeptLead && !$isLeadDev) {
            jsonResponse(false, 'Permission denied. You can only edit projects in your department or assigned by you.', [], 403);
        }

        $name = sanitize($_POST['name'] ?? $project['name']);
        $code = sanitize($_POST['code'] ?? $project['code']);
        $description = sanitize($_POST['description'] ?? $project['description']);
        $departmentId = (int)($_POST['department_id'] ?? $project['department_id']);
        $priority = sanitize($_POST['priority'] ?? $project['priority']);
        $status = sanitize($_POST['status'] ?? $project['status']);
        $progress = isset($_POST['progress']) ? max(0, min(100, (int)$_POST['progress'])) : (int)$project['progress'];
        $startDate = sanitize($_POST['start_date'] ?? $project['start_date']);
        $endDate = sanitize($_POST['end_date'] ?? $project['end_date']);

        if (empty($name)) {
            jsonResponse(false, 'Project Name is required.');
        }

        $upd = $db->prepare("
            UPDATE projects 
            SET name = ?, code = ?, description = ?, department_id = ?, priority = ?, status = ?, progress = ?, start_date = ?, end_date = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $upd->execute([$name, $code, $description, $departmentId, $priority, $status, $progress, $startDate, $endDate, $projectId]);

        // Sync member_ids if provided or explicit member form submission
        if (isset($_POST['member_ids']) || isset($_POST['members_submitted'])) {
            $memberIds = $_POST['member_ids'] ?? [];
            if (is_string($memberIds)) {
                $memberIds = array_filter(array_map('intval', explode(',', $memberIds)));
            } elseif (is_array($memberIds)) {
                $memberIds = array_filter(array_map('intval', $memberIds));
            }

            // Always keep creator
            if (!empty($project['created_by']) && !in_array((int)$project['created_by'], $memberIds)) {
                $memberIds[] = (int)$project['created_by'];
            }

            // Existing members
            $currMembers = $db->prepare("SELECT user_id FROM project_members WHERE project_id = ?");
            $currMembers->execute([$projectId]);
            $existingUserIds = array_map('intval', $currMembers->fetchAll(PDO::FETCH_COLUMN) ?: []);

            $insMember = $db->prepare("INSERT INTO project_members (project_id, user_id, role_in_project) VALUES (?, ?, 'Team Member')");
            foreach ($memberIds as $mId) {
                if (!in_array($mId, $existingUserIds)) {
                    try {
                        $insMember->execute([$projectId, $mId]);
                    } catch (Exception $e) {}
                    if ($mId !== (int)$user['id']) {
                        sendNotification($mId, 'Assigned to Project', "You have been assigned to project '{$name}' by {$user['name']}.", "projects", 'project');
                    }
                }
            }

            // Remove members not in new list (preserve creator)
            $delMember = $db->prepare("DELETE FROM project_members WHERE project_id = ? AND user_id = ?");
            foreach ($existingUserIds as $oldId) {
                if (!in_array($oldId, $memberIds) && $oldId !== (int)$project['created_by']) {
                    $delMember->execute([$projectId, $oldId]);
                }
            }
        }

        logActivity('Edit Project', 'Projects', "Project #{$projectId} ('{$name}') updated by {$user['name']}.");
        jsonResponse(true, 'Project updated successfully!', [
            'project_id' => $projectId,
            'name' => $name,
            'status' => $status
        ]);
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to update project: ' . $e->getMessage());
    }
}

function deleteProject(array $user): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied. Only managers and leads can delete projects.', [], 403);
    }

    $projectId = (int)($_POST['project_id'] ?? ($_GET['project_id'] ?? 0));
    if (!$projectId) {
        jsonResponse(false, 'Project ID is required.');
    }

    try {
        $db = Database::getConnection();
        $pStmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
        $pStmt->execute([$projectId]);
        $project = $pStmt->fetch();

        if (!$project) {
            jsonResponse(false, 'Project not found.');
        }

        $isAdmin = hasRole('admin');
        $isPM = hasRole('product_manager');
        $isManager = hasRole('manager');
        $isCreator = ((int)$project['created_by'] === (int)$user['id']);
        $isDeptLead = (!empty($project['department_id']) && (int)$project['department_id'] === (int)$user['department_id']);
        $isLeadDev = ((int)$user['id'] === 4 || ($user['role_name'] ?? '') === 'senior_developer');

        if (!$isAdmin && !$isPM && !$isManager && !$isCreator && !$isDeptLead && !$isLeadDev) {
            jsonResponse(false, 'Permission denied. You can only delete projects in your department or created by you.', [], 403);
        }

        // Unlink any tasks linked to this project
        $db->prepare("UPDATE tasks SET project_id = NULL WHERE project_id = ?")->execute([$projectId]);

        // Delete project members
        $db->prepare("DELETE FROM project_members WHERE project_id = ?")->execute([$projectId]);

        // Delete project
        $db->prepare("DELETE FROM projects WHERE id = ?")->execute([$projectId]);

        logActivity('Delete Project', 'Projects', "Project #{$projectId} ('{$project['name']}') deleted by {$user['name']}.");
        jsonResponse(true, "Project '{$project['name']}' deleted successfully.");
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to delete project: ' . $e->getMessage());
    }
}

function getProjectDetails(array $user): void {
    $projectId = (int)($_GET['project_id'] ?? ($_POST['project_id'] ?? 0));
    if (!$projectId) {
        jsonResponse(false, 'Project ID is required.');
    }

    try {
        $db = Database::getConnection();
        $pStmt = $db->prepare("
            SELECT p.*, d.name as department_name, u.name as creator_name
            FROM projects p
            LEFT JOIN departments d ON p.department_id = d.id
            LEFT JOIN users u ON p.created_by = u.id
            WHERE p.id = ?
        ");
        $pStmt->execute([$projectId]);
        $project = $pStmt->fetch();

        if (!$project) {
            jsonResponse(false, 'Project not found.');
        }

        $mStmt = $db->prepare("
            SELECT pm.user_id, pm.role_in_project, u.name, u.avatar, u.designation, u.department_id
            FROM project_members pm
            JOIN users u ON pm.user_id = u.id
            WHERE pm.project_id = ?
        ");
        $mStmt->execute([$projectId]);
        $members = $mStmt->fetchAll();

        $project['members'] = $members;
        $project['member_ids'] = array_map('intval', array_column($members, 'user_id'));

        jsonResponse(true, 'Project details fetched.', $project);
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}

function assignProjectMembers(array $user): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied.', [], 403);
    }

    $projectId = (int)($_POST['project_id'] ?? 0);
    $memberIds = $_POST['member_ids'] ?? [];
    if (is_string($memberIds)) {
        $memberIds = array_filter(array_map('intval', explode(',', $memberIds)));
    } elseif (is_array($memberIds)) {
        $memberIds = array_filter(array_map('intval', $memberIds));
    }

    if (!$projectId) {
        jsonResponse(false, 'Project ID is required.');
    }

    try {
        $db = Database::getConnection();
        $pStmt = $db->prepare("SELECT name, created_by FROM projects WHERE id = ?");
        $pStmt->execute([$projectId]);
        $project = $pStmt->fetch();
        if (!$project) jsonResponse(false, 'Project not found.');

        // Current members
        $curr = $db->prepare("SELECT user_id FROM project_members WHERE project_id = ?");
        $curr->execute([$projectId]);
        $existing = array_map('intval', $curr->fetchAll(PDO::FETCH_COLUMN) ?: []);

        $ins = $db->prepare("INSERT INTO project_members (project_id, user_id, role_in_project) VALUES (?, ?, 'Team Member')");
        foreach ($memberIds as $mId) {
            if (!in_array($mId, $existing)) {
                try {
                    $ins->execute([$projectId, $mId]);
                } catch (Exception $e) {}
                if ($mId !== (int)$user['id']) {
                    sendNotification($mId, 'Assigned to Project', "You have been assigned to project '{$project['name']}' by {$user['name']}.", "projects", 'project');
                }
            }
        }

        $del = $db->prepare("DELETE FROM project_members WHERE project_id = ? AND user_id = ?");
        foreach ($existing as $oldId) {
            if (!in_array($oldId, $memberIds) && $oldId !== (int)$project['created_by']) {
                $del->execute([$projectId, $oldId]);
            }
        }

        logActivity('Assign Project Members', 'Projects', "Members updated for Project #{$projectId}.");
        jsonResponse(true, 'Project members updated successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Error updating project members: ' . $e->getMessage());
    }
}

function updateProjectProgress(array $user): void {
    if (!canCreateOrEditTasks($user)) {
        jsonResponse(false, 'Permission denied', [], 403);
    }
    $projectId = (int)($_POST['project_id'] ?? 0);
    $progress = (int)($_POST['progress'] ?? 0);

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE projects SET progress = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->execute([$progress, $projectId]);
        jsonResponse(true, 'Project progress updated.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error: ' . $e->getMessage());
    }
}
