<?php
$pageTitle = "Projects & Sprints";
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';

$user = requireLogin();
$db = Database::getConnection();
$canManageProjects = canCreateOrEditTasks();
$userId = (int)($user['id'] ?? 0);
$userDeptId = (int)($user['department_id'] ?? 0);
$userCategory = getUserRoleCategory($user);
$isGlobalManager = in_array($userCategory, ['admin', 'manager', 'product_manager']);
$isLeadDev = ($userId === 4 || ($user['role_name'] ?? '') === 'senior_developer');

// Fetch all projects with department and creator details
$projects = $db->query("
    SELECT p.*, d.name as department_name, d.code as department_code, u.name as creator_name,
           (SELECT COUNT(*) FROM tasks WHERE project_id = p.id) as total_tasks,
           (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND status IN ('completed', 'done')) as completed_tasks
    FROM projects p
    LEFT JOIN departments d ON p.department_id = d.id
    LEFT JOIN users u ON p.created_by = u.id
    ORDER BY p.id DESC
")->fetchAll();

$departments = $db->query("SELECT * FROM departments ORDER BY name ASC")->fetchAll();

// Fetch all assigned project members grouped by project_id
$allProjectMembers = $db->query("
    SELECT pm.project_id, pm.user_id, pm.role_in_project, u.name, u.designation, u.department_id, u.avatar
    FROM project_members pm
    JOIN users u ON pm.user_id = u.id
    ORDER BY pm.id ASC
")->fetchAll();

$projectMembersMap = [];
foreach ($allProjectMembers as $pm) {
    $projectMembersMap[$pm['project_id']][] = $pm;
}

// Fetch all assignable employees for current user (sorted with own department team members first)
$assignableUsers = getAssignableEmployeesForUser($user, $db);
$myDeptUsers = array_filter($assignableUsers, function($u) { return !empty($u['is_my_dept']); });
$otherUsers = array_filter($assignableUsers, function($u) { return empty($u['is_my_dept']); });

$totalProjectsCount = count($projects);
$inProgressCount = count(array_filter($projects, function($p) { return in_array($p['status'], ['in_progress', 'active']); }));
$completedCount = count(array_filter($projects, function($p) { return in_array($p['status'], ['completed', 'done']); }));
?>

<div class="main-wrapper">
    <?php require_once __DIR__ . '/includes/navbar.php'; ?>

    <div class="p-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h3 class="fw-bold text-dark mb-1"><i class="fas fa-project-diagram text-warning me-2"></i>Projects &amp; Sprints</h3>
                <p class="text-secondary small mb-0">Plan milestones, assign department specialists, and track deliverables with live progress updates.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= BASE_URL ?>/kanban" class="btn btn-outline-info"><i class="fas fa-columns me-1"></i>Kanban Board</a>
                <a href="<?= BASE_URL ?>/tasks" class="btn btn-outline-primary"><i class="fas fa-tasks me-1"></i>Tasks</a>
                <?php if ($canManageProjects): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                    <i class="fas fa-plus me-1"></i>New Project
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Metric Badges & Quick Stats -->
        <div class="row g-3 mb-4">
            <div class="col-sm-4">
                <div class="glass-card py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-secondary small">Total Active Projects</div>
                        <h4 class="fw-bold text-dark mb-0"><?= $totalProjectsCount ?></h4>
                    </div>
                    <div class="p-3 bg-primary-subtle text-primary rounded-circle">
                        <i class="fas fa-layer-group fa-lg"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="glass-card py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-secondary small">In Progress</div>
                        <h4 class="fw-bold text-primary mb-0"><?= $inProgressCount ?></h4>
                    </div>
                    <div class="p-3 bg-info-subtle text-info rounded-circle">
                        <i class="fas fa-spinner fa-spin fa-lg"></i>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="glass-card py-3 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-secondary small">Completed</div>
                        <h4 class="fw-bold text-success mb-0"><?= $completedCount ?></h4>
                    </div>
                    <div class="p-3 bg-success-subtle text-success rounded-circle">
                        <i class="fas fa-check-double fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="glass-card mb-4 p-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="fas fa-search"></i></span>
                        <input type="text" id="projectSearchInput" class="form-control border-start-0" placeholder="Search project name, code, or description...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="projectDeptFilter" class="form-select">
                        <option value="">All Departments</option>
                        <?php foreach ($departments as $d): ?>
                            <option value="<?= strtolower(sanitize($d['name'])) ?>"><?= sanitize($d['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="projectStatusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="in_progress">In Progress</option>
                        <option value="planning">Planning</option>
                        <option value="on_hold">On Hold</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Projects Cards Grid -->
        <div class="row g-4" id="projectsContainer">
            <?php if (empty($projects)): ?>
                <div class="col-12 text-center py-5">
                    <i class="fas fa-project-diagram fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted">No projects found.</h5>
                    <p class="text-secondary small">Create a new project sprint to begin allocating team tasks and tracking deadlines.</p>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $p): 
                    $pMems = $projectMembersMap[$p['id']] ?? [];
                    $memberIds = array_map('intval', array_column($pMems, 'user_id'));
                    
                    // Permission to edit/delete: Admins, PMs, GMs, Project Creator, or Department Lead of that project's department
                    $canEditThisProject = $canManageProjects && (
                        $isGlobalManager ||
                        $isLeadDev ||
                        ((int)$p['created_by'] === $userId) ||
                        (!empty($p['department_id']) && (int)$p['department_id'] === $userDeptId)
                    );

                    $progressColor = 'bg-primary';
                    if ((int)$p['progress'] >= 100 || in_array($p['status'], ['completed', 'done'])) {
                        $progressColor = 'bg-success';
                    } elseif ((int)$p['progress'] < 30) {
                        $progressColor = 'bg-info';
                    }
                ?>
                    <div class="col-lg-6 project-card-item" 
                         data-name="<?= strtolower(sanitize($p['name'])) ?>"
                         data-code="<?= strtolower(sanitize($p['code'] ?? '')) ?>"
                         data-dept="<?= strtolower(sanitize($p['department_name'] ?? '')) ?>"
                         data-status="<?= strtolower(sanitize($p['status'])) ?>">
                        <div class="glass-card h-100 d-flex flex-column justify-content-between position-relative">
                            <div>
                                <!-- Card Header -->
                                <div class="d-flex justify-content-between align-items-start mb-2 gap-2 flex-wrap">
                                    <div>
                                        <span class="badge bg-primary-subtle text-primary font-monospace mb-1 border border-primary-subtle"><?= sanitize($p['code'] ?: 'PRJ-' . $p['id']) ?></span>
                                        <?php if (!empty($p['department_name'])): ?>
                                            <span class="badge bg-secondary-subtle text-secondary border ms-1"><i class="fas fa-building me-1"></i><?= sanitize($p['department_name']) ?></span>
                                        <?php endif; ?>
                                        <h5 class="fw-bold text-dark mt-1 mb-1"><?= sanitize($p['name']) ?></h5>
                                        <small class="text-muted">
                                            <i class="fas fa-user-circle me-1"></i>Lead: <?= sanitize($p['creator_name'] ?? 'Manager') ?>
                                        </small>
                                    </div>
                                    <div class="d-flex flex-column align-items-end gap-1">
                                        <?= getProjectStatusBadge($p['status'] ?? 'in_progress') ?>
                                        <?= getPriorityBadge($p['priority'] ?? 'medium') ?>
                                    </div>
                                </div>

                                <!-- Description -->
                                <p class="text-secondary small mb-3">
                                    <?= sanitize($p['description'] ?: 'No detailed project sprint description.') ?>
                                </p>

                                <!-- Assigned Team Members -->
                                <div class="mb-3 p-2 rounded bg-light bg-opacity-50 border">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-secondary fw-semibold">
                                            <i class="fas fa-users text-primary me-1"></i>Assigned Team
                                        </small>
                                        <span class="badge bg-white text-muted border"><?= count($pMems) ?> assigned</span>
                                    </div>
                                    <div class="d-flex flex-wrap gap-1 align-items-center mt-1">
                                        <?php if (!empty($pMems)): ?>
                                            <?php foreach ($pMems as $mem): ?>
                                                <span class="badge bg-white text-dark border px-2 py-1 d-inline-flex align-items-center" 
                                                      title="<?= sanitize($mem['designation'] ?? 'Staff') ?> - <?= sanitize($mem['role_in_project'] ?? 'Member') ?>">
                                                    <i class="fas fa-user-circle text-primary me-1"></i>
                                                    <span><?= sanitize($mem['name']) ?></span>
                                                    <?php if (!empty($mem['designation'])): ?>
                                                        <small class="text-muted ms-1">(<?= sanitize($mem['designation']) ?>)</small>
                                                    <?php endif; ?>
                                                </span>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <span class="text-muted small fst-italic">
                                                <i class="fas fa-info-circle me-1"></i>No team members assigned yet.
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Milestone Progress -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small text-secondary mb-1">
                                        <span>Milestone Progress</span>
                                        <span class="text-dark fw-bold"><?= (int)$p['progress'] ?>%</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 6px; background-color: #e2e8f0;">
                                        <div class="progress-bar <?= $progressColor ?> progress-bar-striped progress-bar-animated" 
                                             role="progressbar" 
                                             style="width: <?= (int)$p['progress'] ?>%" 
                                             aria-valuenow="<?= (int)$p['progress'] ?>" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer: Stats & Action Buttons -->
                            <div class="pt-3 border-top mt-2">
                                <div class="d-flex justify-content-between align-items-center small text-secondary mb-2">
                                    <div>
                                        <a href="<?= BASE_URL ?>/tasks" class="text-decoration-none text-info fw-semibold" title="View Project Tasks">
                                            <i class="fas fa-tasks me-1"></i><?= $p['completed_tasks'] ?> / <?= $p['total_tasks'] ?> Tasks Done &rarr;
                                        </a>
                                    </div>
                                    <div>
                                        <i class="fas fa-calendar-alt me-1 text-warning"></i>Deadline: <?= formatISTDate($p['end_date']) ?>
                                    </div>
                                </div>

                                <?php if ($canEditThisProject): ?>
                                    <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-project" 
                                                data-project="<?= htmlspecialchars(json_encode([
                                                    'id' => (int)$p['id'],
                                                    'name' => $p['name'],
                                                    'code' => $p['code'],
                                                    'department_id' => (int)$p['department_id'],
                                                    'priority' => $p['priority'] ?? 'medium',
                                                    'status' => $p['status'] ?? 'in_progress',
                                                    'progress' => (int)$p['progress'],
                                                    'start_date' => $p['start_date'] ? substr($p['start_date'], 0, 10) : '',
                                                    'end_date' => $p['end_date'] ? substr($p['end_date'], 0, 10) : '',
                                                    'description' => $p['description'] ?? '',
                                                    'member_ids' => $memberIds
                                                ]), ENT_QUOTES, 'UTF-8') ?>"
                                                onclick="openEditProjectModal(JSON.parse(this.getAttribute('data-project')))">
                                            <i class="fas fa-edit me-1"></i>Edit Project
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-project" 
                                                data-id="<?= (int)$p['id'] ?>"
                                                data-name="<?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>"
                                                onclick="deleteProjectPrompt(this)">
                                            <i class="fas fa-trash-alt me-1"></i>Delete
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($canManageProjects): ?>
<!-- ================= CREATE PROJECT MODAL ================= -->
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-plus-circle text-primary me-2"></i>Create New Project
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createProjectForm">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-secondary small fw-semibold">Project Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g., Organic SEO Campaign or Website Overhaul">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Project Code (Optional)</label>
                            <input type="text" name="code" class="form-control font-monospace" placeholder="e.g., PRJ-SEO-101">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Department *</label>
                            <select name="department_id" class="form-select" required>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d['id'] ?>" <?= ((int)$d['id'] === $userDeptId) ? 'selected' : '' ?>>
                                        <?= sanitize($d['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Priority</label>
                            <select name="priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Initial Status</label>
                            <select name="status" class="form-select">
                                <option value="planning">Planning</option>
                                <option value="in_progress" selected>In Progress</option>
                                <option value="on_hold">On Hold</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Target Deadline / End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold">Initial Progress (0 - 100%)</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" name="progress" class="form-range flex-grow-1" min="0" max="100" value="0" id="createProgressRange" oninput="document.getElementById('createProgressDisplay').textContent = this.value + '%'">
                                <span class="fw-bold text-primary font-monospace" id="createProgressDisplay" style="min-width: 45px;">0%</span>
                            </div>
                        </div>

                        <!-- Assigned Team Members Selection -->
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user-plus me-1 text-primary"></i>Assign Team Members to Project</span>
                                <small class="text-muted">Select specialists who can work on this sprint</small>
                            </label>
                            <div class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <?php if (!empty($myDeptUsers)): ?>
                                    <div class="small fw-bold text-primary mb-2">
                                        <i class="fas fa-star text-warning me-1"></i>My Department Team (Quick Select)
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <?php foreach ($myDeptUsers as $u): ?>
                                            <div class="col-md-6">
                                                <div class="form-check bg-white p-2 border rounded">
                                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="member_ids[]" value="<?= $u['id'] ?>" id="create_mem_<?= $u['id'] ?>" <?= ((int)$u['id'] === $userId) ? 'checked' : '' ?>>
                                                    <label class="form-check-label small fw-semibold" for="create_mem_<?= $u['id'] ?>">
                                                        <?= sanitize($u['name']) ?>
                                                        <span class="text-muted fw-normal d-block" style="font-size: 11px;"><?= sanitize($u['designation'] ?? 'Specialist') ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($otherUsers)): ?>
                                    <div class="small fw-bold text-secondary mb-2">
                                        <i class="fas fa-users me-1"></i>Other Staff &amp; Collaborators
                                    </div>
                                    <div class="row g-2">
                                        <?php foreach ($otherUsers as $u): ?>
                                            <div class="col-md-6">
                                                <div class="form-check bg-white p-2 border rounded">
                                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="member_ids[]" value="<?= $u['id'] ?>" id="create_mem_<?= $u['id'] ?>">
                                                    <label class="form-check-label small fw-semibold" for="create_mem_<?= $u['id'] ?>">
                                                        <?= sanitize($u['name']) ?>
                                                        <span class="text-muted fw-normal d-block" style="font-size: 11px;"><?= sanitize($u['designation'] ?? 'Staff') ?> &bull; <?= sanitize($u['dept_name'] ?? 'General') ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold">Project Scope &amp; Deliverables</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Outline sprint objectives, KPIs, deliverables, and instructions..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="createProjectSubmitBtn">
                        <i class="fas fa-check me-1"></i>Create Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= EDIT PROJECT MODAL ================= -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-edit text-primary me-2"></i>Edit Project &amp; Sprint
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProjectForm">
                <input type="hidden" name="csrf_token" value="<?= getCsrfToken() ?>">
                <input type="hidden" name="project_id" id="edit_project_id">
                <input type="hidden" name="members_submitted" value="1">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-secondary small fw-semibold">Project Name *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Project Code</label>
                            <input type="text" name="code" id="edit_code" class="form-control font-monospace">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Department</label>
                            <select name="department_id" id="edit_department_id" class="form-select" required>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Priority</label>
                            <select name="priority" id="edit_priority" class="form-select">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-secondary small fw-semibold">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="planning">Planning</option>
                                <option value="in_progress">In Progress</option>
                                <option value="on_hold">On Hold</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Start Date</label>
                            <input type="date" name="start_date" id="edit_start_date" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-secondary small fw-semibold">Target Deadline / End Date</label>
                            <input type="date" name="end_date" id="edit_end_date" class="form-control">
                        </div>

                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold">Milestone Progress (0 - 100%)</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="range" name="progress" class="form-range flex-grow-1" min="0" max="100" id="edit_progress" oninput="document.getElementById('edit_progress_display').textContent = this.value + '%'">
                                <span class="fw-bold text-primary font-monospace" id="edit_progress_display" style="min-width: 45px;">0%</span>
                            </div>
                        </div>

                        <!-- Assigned Team Members Selection -->
                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-users-cog me-1 text-primary"></i>Assigned Team Members</span>
                                <small class="text-muted">Check or uncheck members assigned to this sprint</small>
                            </label>
                            <div class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                                <?php if (!empty($myDeptUsers)): ?>
                                    <div class="small fw-bold text-primary mb-2">
                                        <i class="fas fa-star text-warning me-1"></i>My Department Team
                                    </div>
                                    <div class="row g-2 mb-3">
                                        <?php foreach ($myDeptUsers as $u): ?>
                                            <div class="col-md-6">
                                                <div class="form-check bg-white p-2 border rounded">
                                                    <input class="form-check-input edit-member-cb ms-0 me-2" type="checkbox" name="member_ids[]" value="<?= $u['id'] ?>" id="edit_mem_<?= $u['id'] ?>">
                                                    <label class="form-check-label small fw-semibold" for="edit_mem_<?= $u['id'] ?>">
                                                        <?= sanitize($u['name']) ?>
                                                        <span class="text-muted fw-normal d-block" style="font-size: 11px;"><?= sanitize($u['designation'] ?? 'Specialist') ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($otherUsers)): ?>
                                    <div class="small fw-bold text-secondary mb-2">
                                        <i class="fas fa-users me-1"></i>Other Staff &amp; Collaborators
                                    </div>
                                    <div class="row g-2">
                                        <?php foreach ($otherUsers as $u): ?>
                                            <div class="col-md-6">
                                                <div class="form-check bg-white p-2 border rounded">
                                                    <input class="form-check-input edit-member-cb ms-0 me-2" type="checkbox" name="member_ids[]" value="<?= $u['id'] ?>" id="edit_mem_<?= $u['id'] ?>">
                                                    <label class="form-check-label small fw-semibold" for="edit_mem_<?= $u['id'] ?>">
                                                        <?= sanitize($u['name']) ?>
                                                        <span class="text-muted fw-normal d-block" style="font-size: 11px;"><?= sanitize($u['designation'] ?? 'Staff') ?> &bull; <?= sanitize($u['dept_name'] ?? 'General') ?></span>
                                                    </label>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-secondary small fw-semibold">Project Scope &amp; Notes</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="editProjectSubmitBtn">
                        <i class="fas fa-save me-1"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script>
    // Live Search & Filter
    function filterProjects() {
        const searchVal = (document.getElementById('projectSearchInput')?.value || '').toLowerCase().trim();
        const deptVal = (document.getElementById('projectDeptFilter')?.value || '').toLowerCase().trim();
        const statusVal = (document.getElementById('projectStatusFilter')?.value || '').toLowerCase().trim();

        document.querySelectorAll('.project-card-item').forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const code = card.getAttribute('data-code') || '';
            const dept = card.getAttribute('data-dept') || '';
            const status = card.getAttribute('data-status') || '';

            const matchesSearch = !searchVal || name.includes(searchVal) || code.includes(searchVal);
            const matchesDept = !deptVal || dept.includes(deptVal);
            const matchesStatus = !statusVal || status === statusVal || (statusVal === 'in_progress' && status === 'active');

            if (matchesSearch && matchesDept && matchesStatus) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    document.getElementById('projectSearchInput')?.addEventListener('input', filterProjects);
    document.getElementById('projectDeptFilter')?.addEventListener('change', filterProjects);
    document.getElementById('projectStatusFilter')?.addEventListener('change', filterProjects);

    // Create Project Form Submit
    document.getElementById('createProjectForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('createProjectSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Creating...';

        const formData = new FormData(this);
        formData.append('action', 'create');

        fetch('<?= BASE_URL ?>/api/projects', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Create Project';
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Project Created',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', res.message || 'Failed to create project.', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i>Create Project';
            Swal.fire('Network Error', 'Could not reach server to create project.', 'error');
        });
    });

    // Open Edit Project Modal
    let editModalInstance = null;
    function openEditProjectModal(data) {
        if (!data) return;

        document.getElementById('edit_project_id').value = data.id || '';
        document.getElementById('edit_name').value = data.name || '';
        document.getElementById('edit_code').value = data.code || '';
        if (data.department_id) {
            document.getElementById('edit_department_id').value = data.department_id;
        }
        if (data.priority) {
            document.getElementById('edit_priority').value = data.priority;
        }
        if (data.status) {
            document.getElementById('edit_status').value = data.status;
        }
        const prog = parseInt(data.progress) || 0;
        document.getElementById('edit_progress').value = prog;
        document.getElementById('edit_progress_display').textContent = prog + '%';
        document.getElementById('edit_start_date').value = data.start_date || '';
        document.getElementById('edit_end_date').value = data.end_date || '';
        document.getElementById('edit_description').value = data.description || '';

        // Check/uncheck member checkboxes
        const memberIds = Array.isArray(data.member_ids) ? data.member_ids.map(Number) : [];
        document.querySelectorAll('.edit-member-cb').forEach(cb => {
            cb.checked = memberIds.includes(Number(cb.value));
        });

        const modalEl = document.getElementById('editProjectModal');
        if (!editModalInstance) {
            editModalInstance = new bootstrap.Modal(modalEl);
        }
        editModalInstance.show();
    }

    // Edit Project Form Submit
    document.getElementById('editProjectForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('editProjectSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Saving...';

        const formData = new FormData(this);
        formData.append('action', 'update');

        fetch('<?= BASE_URL ?>/api/projects', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(res => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Project Updated',
                    text: res.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => location.reload());
            } else {
                Swal.fire('Error', res.message || 'Failed to update project.', 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
            Swal.fire('Network Error', 'Could not reach server to update project.', 'error');
        });
    });

    // Delete Project Prompt
    function deleteProjectPrompt(target, directName) {
        let projectId = null;
        let projectName = 'this project';

        if (typeof target === 'object' && target !== null && target.dataset) {
            projectId = target.dataset.id;
            projectName = target.dataset.name || 'this project';
        } else if (target) {
            projectId = target;
            projectName = directName || 'this project';
        }

        if (!projectId) {
            Swal.fire('Error', 'Project ID is missing.', 'error');
            return;
        }

        Swal.fire({
            title: 'Delete Project?',
            html: `Are you sure you want to delete <b>${projectName}</b>?<br><small class="text-muted">Associated tasks will be detached.</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, Delete Project'
        }).then(result => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('project_id', projectId);
                formData.append('csrf_token', '<?= getCsrfToken() ?>');

                fetch('<?= BASE_URL ?>/api/projects', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message || 'Failed to delete project.', 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Network Error', 'Failed to communicate with server.', 'error');
                });
            }
        });
    }
</script>
