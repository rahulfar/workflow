<?php
/**
 * Model Context Protocol (MCP) Server - WorkFlow Pro
 * Compliant with Anthropic MCP Specification (2024-11-05)
 * Supports:
 *  1. HTTP SSE & JSON-RPC POST (Remote Link / Web URL)
 *  2. Browser Interactive Portal & Connection Manager
 *  3. CLI / Stdio transport (Claude Desktop / Cursor local runner)
 */

if (php_sapi_name() === 'cli') {
    require_once __DIR__ . '/../php_app/config/app.php';
    require_once __DIR__ . '/../php_app/config/database.php';
    require_once __DIR__ . '/../php_app/helpers/functions.php';
    runMcpStdioServer();
    exit;
}

// Web Request Context
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/config/database.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

// Enable CORS for external AI tools
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-MCP-Session');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
$isEventStream = (strpos($acceptHeader, 'text/event-stream') !== false) || (isset($_GET['transport']) && $_GET['transport'] === 'sse');

// 1. SSE Stream Handshake (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $isEventStream) {
    handleMcpSseConnection();
    exit;
}

// 2. JSON-RPC Protocol Execution (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleMcpPostRequest();
    exit;
}

// 3. Browser View: Interactive Portal & Connection Links
renderMcpBrowserPortal();
exit;

// =========================================================================
// MCP PROTOCOL CORE IMPLEMENTATION
// =========================================================================

function getMcpToolsDefinition(): array {
    return [
        [
            'name' => 'get_workflow_overview',
            'description' => 'Get live enterprise summary of WorkFlow Pro: active shifts, today attendance, open tasks, pending approvals, and active team members.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => new stdClass()
            ]
        ],
        [
            'name' => 'list_tasks',
            'description' => 'Query and filter tasks from the workflow system.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'status' => [
                        'type' => 'string',
                        'description' => 'Filter by status: pending, in_progress, completed, flagged, pending_approval, or all',
                        'enum' => ['all', 'pending', 'in_progress', 'completed', 'flagged', 'pending_approval']
                    ],
                    'priority' => [
                        'type' => 'string',
                        'description' => 'Filter by priority: low, medium, high, urgent',
                        'enum' => ['all', 'low', 'medium', 'high', 'urgent']
                    ],
                    'assigned_to' => [
                        'type' => 'integer',
                        'description' => 'User ID of employee'
                    ],
                    'limit' => [
                        'type' => 'integer',
                        'description' => 'Number of tasks to return (default 25, max 100)'
                    ]
                ]
            ]
        ],
        [
            'name' => 'get_task_details',
            'description' => 'Retrieve detailed information for a specific task including description, subtasks, notes, assignees, and time logs.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'task_id' => [
                        'type' => 'integer',
                        'description' => 'Unique ID of the task'
                    ]
                ],
                'required' => ['task_id']
            ]
        ],
        [
            'name' => 'create_task',
            'description' => 'Create a new task in WorkFlow Pro.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'title' => [
                        'type' => 'string',
                        'description' => 'Short, descriptive title of the task'
                    ],
                    'description' => [
                        'type' => 'string',
                        'description' => 'Detailed task instructions and requirements'
                    ],
                    'assigned_to' => [
                        'type' => 'integer',
                        'description' => 'User ID of assignee (e.g. 4 for Rahul, 5 for Uvaish)'
                    ],
                    'priority' => [
                        'type' => 'string',
                        'enum' => ['low', 'medium', 'high', 'urgent'],
                        'default' => 'medium'
                    ],
                    'due_date' => [
                        'type' => 'string',
                        'description' => 'Target completion date in YYYY-MM-DD format'
                    ],
                    'project_id' => [
                        'type' => 'integer',
                        'description' => 'Associated project ID (optional)'
                    ]
                ],
                'required' => ['title', 'assigned_to']
            ]
        ],
        [
            'name' => 'update_task_status',
            'description' => 'Change the workflow status of an existing task.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'task_id' => [
                        'type' => 'integer',
                        'description' => 'Unique ID of the task'
                    ],
                    'status' => [
                        'type' => 'string',
                        'enum' => ['pending', 'in_progress', 'completed', 'cancelled']
                    ],
                    'comment' => [
                        'type' => 'string',
                        'description' => 'Optional progress note or status comment'
                    ]
                ],
                'required' => ['task_id', 'status']
            ]
        ],
        [
            'name' => 'list_employees',
            'description' => 'List active staff, designations, roles, employee codes, and departments.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'department_id' => [
                        'type' => 'integer',
                        'description' => 'Filter by department ID'
                    ],
                    'search' => [
                        'type' => 'string',
                        'description' => 'Search name, email, or employee code'
                    ]
                ]
            ]
        ],
        [
            'name' => 'get_attendance_summary',
            'description' => 'Check who is currently clocked in, working shifts, and today summary metrics.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'date' => [
                        'type' => 'string',
                        'description' => 'Date in YYYY-MM-DD format (defaults to today)'
                    ]
                ]
            ]
        ],
        [
            'name' => 'clock_in_shift',
            'description' => 'Clock in / start office shift for an employee.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'user_id' => [
                        'type' => 'integer',
                        'description' => 'ID of the employee starting shift'
                    ],
                    'notes' => [
                        'type' => 'string',
                        'description' => 'Optional shift start note'
                    ]
                ],
                'required' => ['user_id']
            ]
        ],
        [
            'name' => 'clock_out_shift',
            'description' => 'Clock out / end active shift for an employee, calculating total duration and status.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'user_id' => [
                        'type' => 'integer',
                        'description' => 'ID of the employee ending shift'
                    ],
                    'notes' => [
                        'type' => 'string',
                        'description' => 'Optional shift summary note'
                    ]
                ],
                'required' => ['user_id']
            ]
        ],
        [
            'name' => 'list_projects',
            'description' => 'List active projects, sprint status, and deadlines.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => new stdClass()
            ]
        ],
        [
            'name' => 'list_departments',
            'description' => 'List company departments, team sizes, and department codes.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => new stdClass()
            ]
        ],
        [
            'name' => 'get_authorized_ips',
            'description' => 'List whitelisted office Wi-Fi networks and IP permissions governing Shift button visibility.',
            'inputSchema' => [
                'type' => 'object',
                'properties' => new stdClass()
            ]
        ],
        [
            'name' => 'add_authorized_ip',
            'description' => 'Whitelist a new office Wi-Fi network, IP address, or subnet (e.g. 192.168.1.22, 192.168.1.*).',
            'inputSchema' => [
                'type' => 'object',
                'properties' => [
                    'ip_address' => [
                        'type' => 'string',
                        'description' => 'IP address, wildcard (192.168.1.*), or CIDR'
                    ],
                    'location' => [
                        'type' => 'string',
                        'description' => 'Friendly label for network/office location'
                    ],
                    'notes' => [
                        'type' => 'string',
                        'description' => 'Optional administrator notes'
                    ]
                ],
                'required' => ['ip_address', 'location']
            ]
        ]
    ];
}

function executeMcpTool(string $name, array $args): array {
    $db = Database::getConnection();

    switch ($name) {
        case 'get_workflow_overview':
            $totalEmployees = (int)$db->query("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn();
            $totalTasks = (int)$db->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
            $pendingTasks = (int)$db->query("SELECT COUNT(*) FROM tasks WHERE status = 'pending'")->fetchColumn();
            $activeShifts = (int)$db->query("SELECT COUNT(*) FROM attendance WHERE clock_out IS NULL AND date = CURRENT_DATE")->fetchColumn();
            $todayShifts = (int)$db->query("SELECT COUNT(*) FROM attendance WHERE date = CURRENT_DATE")->fetchColumn();
            $totalProjects = (int)$db->query("SELECT COUNT(*) FROM projects WHERE status = 'active'")->fetchColumn();

            return [
                'system' => 'WorkFlow Pro Suite',
                'server_time' => date('Y-m-d H:i:s T'),
                'metrics' => [
                    'active_team_members' => $totalEmployees,
                    'currently_clocked_in' => $activeShifts,
                    'total_shifts_today' => $todayShifts,
                    'total_tasks' => $totalTasks,
                    'pending_tasks' => $pendingTasks,
                    'active_projects' => $totalProjects
                ],
                'status' => 'Healthy & Operational'
            ];

        case 'list_tasks':
            $status = $args['status'] ?? 'all';
            $priority = $args['priority'] ?? 'all';
            $assignedTo = isset($args['assigned_to']) ? (int)$args['assigned_to'] : 0;
            $limit = min(100, max(1, (int)($args['limit'] ?? 25)));

            $where = ["1=1"];
            $params = [];
            if ($status !== 'all') {
                $where[] = "t.status = ?";
                $params[] = $status;
            }
            if ($priority !== 'all') {
                $where[] = "t.priority = ?";
                $params[] = $priority;
            }
            if ($assignedTo > 0) {
                $where[] = "t.assigned_to = ?";
                $params[] = $assignedTo;
            }

            $sql = "
                SELECT t.id, t.title, t.status, t.priority, t.due_date, t.created_at,
                       u.name as assignee_name, u.email as assignee_email,
                       p.name as project_name, d.name as department_name
                FROM tasks t
                LEFT JOIN users u ON t.assigned_to = u.id
                LEFT JOIN projects p ON t.project_id = p.id
                LEFT JOIN departments d ON t.department_id = d.id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY t.id DESC
                LIMIT {$limit}
            ";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return ['count' => $stmt->rowCount(), 'tasks' => $stmt->fetchAll()];

        case 'get_task_details':
            $taskId = (int)($args['task_id'] ?? 0);
            $stmt = $db->prepare("
                SELECT t.*, u.name as assignee_name, u.email as assignee_email, u.designation,
                       c.name as creator_name, p.name as project_name, d.name as department_name
                FROM tasks t
                LEFT JOIN users u ON t.assigned_to = u.id
                LEFT JOIN users c ON t.created_by = c.id
                LEFT JOIN projects p ON t.project_id = p.id
                LEFT JOIN departments d ON t.department_id = d.id
                WHERE t.id = ?
            ");
            $stmt->execute([$taskId]);
            $task = $stmt->fetch();
            if (!$task) {
                throw new Exception("Task not found with ID {$taskId}");
            }
            return ['task' => $task];

        case 'create_task':
            $title = trim($args['title'] ?? '');
            $desc = trim($args['description'] ?? '');
            $assignedTo = (int)($args['assigned_to'] ?? 0);
            $priority = in_array($args['priority'] ?? '', ['low', 'medium', 'high', 'urgent']) ? $args['priority'] : 'medium';
            $dueDate = !empty($args['due_date']) ? $args['due_date'] : date('Y-m-d', strtotime('+7 days'));
            $projectId = !empty($args['project_id']) ? (int)$args['project_id'] : null;

            if (empty($title)) throw new Exception("Task title is required.");
            if ($assignedTo <= 0) throw new Exception("Valid assignee ID is required.");

            // Check assignee department
            $uStmt = $db->prepare("SELECT department_id FROM users WHERE id = ?");
            $uStmt->execute([$assignedTo]);
            $deptId = (int)($uStmt->fetchColumn() ?: 1);

            $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? 'mysql');
            if ($driver === 'sqlite') {
                $nextId = (int)$db->query("SELECT COALESCE(MAX(id), 0) + 1 FROM tasks")->fetchColumn();
                $stmt = $db->prepare("INSERT INTO tasks (id, title, description, assigned_to, department_id, project_id, priority, due_date, status, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 1, datetime('now'), datetime('now'))");
                $stmt->execute([$nextId, $title, $desc, $assignedTo, $deptId, $projectId, $priority, $dueDate]);
                $createdId = $nextId;
            } else {
                $stmt = $db->prepare("INSERT INTO tasks (title, description, assigned_to, department_id, project_id, priority, due_date, status, created_by, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 1, NOW(), NOW())");
                $stmt->execute([$title, $desc, $assignedTo, $deptId, $projectId, $priority, $dueDate]);
                $createdId = (int)$db->lastInsertId();
            }

            return ['success' => true, 'task_id' => $createdId, 'message' => "Task '{$title}' created successfully."];

        case 'update_task_status':
            $taskId = (int)($args['task_id'] ?? 0);
            $status = $args['status'] ?? '';
            $valid = ['pending', 'in_progress', 'completed', 'cancelled'];
            if (!in_array($status, $valid)) throw new Exception("Invalid status '{$status}'.");

            $stmt = $db->prepare("UPDATE tasks SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$status, $taskId]);
            return ['success' => true, 'task_id' => $taskId, 'new_status' => $status];

        case 'list_employees':
            $deptId = isset($args['department_id']) ? (int)$args['department_id'] : 0;
            $search = trim($args['search'] ?? '');
            $where = ["u.status = 'active'"];
            $params = [];
            if ($deptId > 0) {
                $where[] = "u.department_id = ?";
                $params[] = $deptId;
            }
            if (!empty($search)) {
                $where[] = "(u.name LIKE ? OR u.email LIKE ? OR u.employee_code LIKE ?)";
                $params[] = "%$search%";
                $params[] = "%$search%";
                $params[] = "%$search%";
            }
            $stmt = $db->prepare("
                SELECT u.id, u.name, u.email, u.employee_code, u.designation, r.display_name as role_title, d.name as department_name
                FROM users u
                JOIN roles r ON u.role_id = r.id
                JOIN departments d ON u.department_id = d.id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY u.name ASC
            ");
            $stmt->execute($params);
            return ['employees' => $stmt->fetchAll()];

        case 'get_attendance_summary':
            $date = !empty($args['date']) ? $args['date'] : date('Y-m-d');
            $stmt = $db->prepare("
                SELECT a.id, a.user_id, a.date, a.clock_in, a.clock_out, a.total_seconds, a.status, a.late_minutes,
                       u.name as employee_name, u.employee_code, d.name as department_name
                FROM attendance a
                JOIN users u ON (u.id = CASE WHEN IFNULL(a.user_id, 0) > 0 THEN a.user_id ELSE a.employee_id END)
                LEFT JOIN departments d ON u.department_id = d.id
                WHERE a.date = ?
                ORDER BY a.clock_in DESC
            ");
            $stmt->execute([$date]);
            $logs = $stmt->fetchAll();
            $running = array_filter($logs, fn($l) => empty($l['clock_out']));
            return [
                'date' => $date,
                'total_records' => count($logs),
                'currently_active_shifts' => count($running),
                'records' => $logs
            ];

        case 'clock_in_shift':
            $userId = (int)($args['user_id'] ?? 0);
            if ($userId <= 0) throw new Exception("Valid user ID is required.");
            $uStmt = $db->prepare("SELECT id, name FROM users WHERE id = ?");
            $uStmt->execute([$userId]);
            $u = $uStmt->fetch();
            if (!$u) throw new Exception("User not found.");

            $today = date('Y-m-d');
            $nowTime = date('H:i:s');
            $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? 'mysql');

            // Check existing active shift
            $chk = $db->prepare("SELECT id FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date = ? AND clock_out IS NULL");
            $chk->execute([$userId, $userId, $today]);
            if ($chk->fetch()) throw new Exception("User already has an active shift today.");

            if ($driver === 'sqlite') {
                $nextId = (int)$db->query("SELECT COALESCE(MAX(id), 0) + 1 FROM attendance")->fetchColumn();
                $ins = $db->prepare("INSERT INTO attendance (id, user_id, employee_id, date, clock_in, status, created_at) VALUES (?, ?, ?, ?, ?, 'half_day', datetime('now'))");
                $ins->execute([$nextId, $userId, $userId, $today, $nowTime]);
                $attId = $nextId;
            } else {
                $ins = $db->prepare("INSERT INTO attendance (user_id, employee_id, date, clock_in, status, created_at) VALUES (?, ?, ?, ?, 'half_day', NOW())");
                $ins->execute([$userId, $userId, $today, $nowTime]);
                $attId = (int)$db->lastInsertId();
            }
            return ['success' => true, 'attendance_id' => $attId, 'message' => "Shift started for {$u['name']} at {$nowTime}"];

        case 'clock_out_shift':
            $userId = (int)($args['user_id'] ?? 0);
            $today = date('Y-m-d');
            $nowTime = date('H:i:s');

            $chk = $db->prepare("SELECT * FROM attendance WHERE (user_id = ? OR employee_id = ?) AND date = ? AND clock_out IS NULL ORDER BY id DESC LIMIT 1");
            $chk->execute([$userId, $userId, $today]);
            $shift = $chk->fetch();
            if (!$shift) throw new Exception("No active shift found for user {$userId} today.");

            $startTs = strtotime($today . ' ' . $shift['clock_in']);
            $endTs = time();
            $durationSec = max(0, $endTs - $startTs);
            $status = ($durationSec >= 31800) ? 'full_day' : 'half_day';

            $upd = $db->prepare("UPDATE attendance SET clock_out = ?, total_seconds = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $upd->execute([$nowTime, $durationSec, $status, $shift['id']]);

            return [
                'success' => true,
                'attendance_id' => (int)$shift['id'],
                'duration_seconds' => $durationSec,
                'duration_formatted' => formatDuration($durationSec),
                'status' => $status
            ];

        case 'list_projects':
            $stmt = $db->query("SELECT id, name, description, status, start_date, end_date FROM projects ORDER BY name ASC");
            return ['projects' => $stmt->fetchAll()];

        case 'list_departments':
            $stmt = $db->query("SELECT id, name, code, description FROM departments ORDER BY name ASC");
            return ['departments' => $stmt->fetchAll()];

        case 'get_authorized_ips':
            $stmt = $db->query("SELECT id, ip_address, location, status, notes, updated_at FROM authorized_ips ORDER BY id ASC");
            return ['authorized_ips' => $stmt->fetchAll()];

        case 'add_authorized_ip':
            $ip = trim($args['ip_address'] ?? '');
            $loc = trim($args['location'] ?? '');
            $notes = trim($args['notes'] ?? 'Added via MCP Server');
            if (empty($ip)) throw new Exception("IP address is required.");
            if (empty($loc)) throw new Exception("Location name is required.");

            $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? 'mysql');
            if ($driver === 'sqlite') {
                $nextId = (int)$db->query("SELECT COALESCE(MAX(id), 0) + 1 FROM authorized_ips")->fetchColumn();
                $stmt = $db->prepare("INSERT OR REPLACE INTO authorized_ips (id, ip_address, location, status, notes, created_by, created_at, updated_at) VALUES (?, ?, ?, 'active', ?, 1, datetime('now'), datetime('now'))");
                $stmt->execute([$nextId, $ip, $loc, $notes]);
            } else {
                $stmt = $db->prepare("INSERT INTO authorized_ips (ip_address, location, status, notes, created_by, created_at, updated_at) VALUES (?, ?, 'active', ?, 1, NOW(), NOW()) ON DUPLICATE KEY UPDATE location = VALUES(location), status = 'active', updated_at = NOW()");
                $stmt->execute([$ip, $loc, $notes]);
            }
            return ['success' => true, 'ip_address' => $ip, 'location' => $loc, 'message' => "IP {$ip} whitelisted successfully."];

        default:
            throw new Exception("Unknown MCP tool: '{$name}'");
    }
}

function processMcpJsonRpc(array $req): array {
    $id = $req['id'] ?? null;
    $method = $req['method'] ?? '';
    $params = $req['params'] ?? [];

    switch ($method) {
        case 'initialize':
            return [
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => [
                    'protocolVersion' => '2024-11-05',
                    'capabilities' => [
                        'tools' => ['listChanged' => false],
                        'resources' => ['subscribe' => false, 'listChanged' => false],
                        'prompts' => ['listChanged' => false]
                    ],
                    'serverInfo' => [
                        'name' => 'WorkFlow Pro MCP Server',
                        'version' => '2.0.0',
                        'vendor' => 'Krafting Digital'
                    ]
                ]
            ];

        case 'notifications/initialized':
            return [
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => ['status' => 'ready']
            ];

        case 'ping':
            return [
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => ['pong' => true]
            ];

        case 'tools/list':
            return [
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => [
                    'tools' => getMcpToolsDefinition()
                ]
            ];

        case 'tools/call':
            $toolName = $params['name'] ?? '';
            $toolArgs = $params['arguments'] ?? [];
            try {
                $output = executeMcpTool($toolName, $toolArgs);
                return [
                    'jsonrpc' => '2.0',
                    'id' => $id,
                    'result' => [
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
                            ]
                        ]
                    ]
                ];
            } catch (Throwable $t) {
                return [
                    'jsonrpc' => '2.0',
                    'id' => $id,
                    'isError' => true,
                    'result' => [
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => "Error executing tool '{$toolName}': " . $t->getMessage()
                            ]
                        ]
                    ]
                ];
            }

        case 'resources/list':
            return [
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => [
                    'resources' => [
                        [
                            'uri' => 'workflow://overview',
                            'name' => 'WorkFlow Pro System Status',
                            'mimeType' => 'application/json'
                        ],
                        [
                            'uri' => 'workflow://authorized-ips',
                            'name' => 'Office Wi-Fi IP Whitelist',
                            'mimeType' => 'application/json'
                        ]
                    ]
                ]
            ];

        case 'resources/read':
            $uri = $params['uri'] ?? '';
            $db = Database::getConnection();
            if ($uri === 'workflow://overview') {
                $data = executeMcpTool('get_workflow_overview', []);
            } elseif ($uri === 'workflow://authorized-ips') {
                $data = executeMcpTool('get_authorized_ips', []);
            } else {
                throw new Exception("Resource not found: {$uri}");
            }
            return [
                'jsonrpc' => '2.0',
                'id' => $id,
                'result' => [
                    'contents' => [
                        [
                            'uri' => $uri,
                            'mimeType' => 'application/json',
                            'text' => json_encode($data, JSON_PRETTY_PRINT)
                        ]
                    ]
                ]
            ];

        default:
            return [
                'jsonrpc' => '2.0',
                'id' => $id,
                'error' => [
                    'code' => -32601,
                    'message' => "Method not found: '{$method}'"
                ]
            ];
    }
}

// =========================================================================
// TRANSPORTS: SSE, POST, STDIO
// =========================================================================

function handleMcpSseConnection(): void {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache, no-transform');
    header('Connection: keep-alive');
    header('X-Accel-Buffering: no');

    $sessionId = bin2hex(random_bytes(16));
    $postEndpoint = BASE_URL . '/api/mcp.php?sessionId=' . $sessionId;

    echo "event: endpoint\n";
    echo "data: {$postEndpoint}\n\n";
    @ob_flush();
    @flush();

    // Keep SSE heartbeat alive
    $start = time();
    while (time() - $start < 30) {
        if (connection_aborted()) break;
        echo ": heartbeat\n\n";
        @ob_flush();
        @flush();
        sleep(5);
    }
    exit;
}

function handleMcpPostRequest(): void {
    header('Content-Type: application/json; charset=UTF-8');
    $rawInput = file_get_contents('php://input');
    if (empty($rawInput)) {
        echo json_encode([
            'jsonrpc' => '2.0',
            'id' => null,
            'error' => ['code' => -32700, 'message' => 'Parse error: Empty request body']
        ]);
        exit;
    }

    $req = json_decode($rawInput, true);
    if (!is_array($req)) {
        echo json_encode([
            'jsonrpc' => '2.0',
            'id' => null,
            'error' => ['code' => -32700, 'message' => 'Parse error: Invalid JSON']
        ]);
        exit;
    }

    $resp = processMcpJsonRpc($req);
    echo json_encode($resp, JSON_UNESCAPED_SLASHES);
    exit;
}

function runMcpStdioServer(): void {
    while ($line = fgets(STDIN)) {
        $line = trim($line);
        if (empty($line)) continue;
        $req = json_decode($line, true);
        if (!is_array($req)) continue;
        $resp = processMcpJsonRpc($req);
        fwrite(STDOUT, json_encode($resp, JSON_UNESCAPED_SLASHES) . "\n");
        fflush(STDOUT);
    }
}

// =========================================================================
// BROWSER INTERACTIVE MCP PORTAL
// =========================================================================

function renderMcpBrowserPortal(): void {
    $tools = getMcpToolsDefinition();
    $liveUrl = "https://kraftworflow.in/api/mcp.php";
    $localUrl = (defined('BASE_URL') ? BASE_URL : 'http://localhost:8000') . '/api/mcp.php';
    $projectDir = realpath(dirname(__DIR__));
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Context Protocol (MCP) Server - WorkFlow Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap">
    <style>
        :root {
            --primary: #4F46E5;
            --primary-dark: #3730A3;
            --primary-subtle: rgba(79, 70, 229, 0.08);
            --bg: #F8FAFC;
            --surface: #FFFFFF;
            --border: #E2E8F0;
            --text-dark: #0F172A;
            --text-muted: #64748B;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-dark);
            min-height: 100vh;
        }
        .code-box {
            font-family: 'JetBrains Mono', monospace;
            background: #0F172A;
            color: #E2E8F0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            position: relative;
            overflow-x: auto;
            font-size: 0.88rem;
        }
        .copy-btn {
            position: absolute;
            top: 0.65rem;
            right: 0.65rem;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            border: 0;
            padding: 0.25rem 0.65rem;
            border-radius: 6px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .copy-btn:hover { background: rgba(255, 255, 255, 0.3); }
        .glass-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .tool-badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            background: #EEF2FF;
            color: #4F46E5;
            padding: 0.3rem 0.65rem;
            border-radius: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body class="py-4 py-md-5">
    <div class="container" style="max-width: 1080px;">
        
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-primary px-3 py-1.5 rounded-pill fw-bold">
                        <i class="fas fa-plug me-1.5"></i>Model Context Protocol
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success px-3 py-1.5 rounded-pill fw-bold">
                        <i class="fas fa-check-circle me-1"></i>Active &amp; Online
                    </span>
                    <span class="badge bg-light text-secondary border px-2.5 py-1.5 rounded-pill fs-8">Spec: 2024-11-05</span>
                </div>
                <h2 class="fw-bold text-dark font-heading mb-1">WorkFlow Pro &bull; MCP Server Interface</h2>
                <p class="text-secondary mb-0">Connect Claude Desktop, Cursor, Antigravity, or any AI coding agent directly to your enterprise tasks, shifts, attendance, and team data.</p>
            </div>
            <div>
                <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-secondary rounded-pill px-3.5 fw-bold">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Connection Endpoints Grid -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-globe text-primary me-2"></i>Live Production MCP Link</h6>
                        <span class="badge bg-success text-white">Live Remote URL</span>
                    </div>
                    <p class="text-secondary fs-8 mb-3">Use this URL for remote MCP clients, web agent hooks, or cloud agent workflows:</p>
                    <div class="code-box">
                        <button class="copy-btn" onclick="copyText('<?= $liveUrl ?>', this)"><i class="fas fa-copy me-1"></i>Copy</button>
                        <span><?= $liveUrl ?></span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-laptop-code text-info me-2"></i>Local Development MCP Link</h6>
                        <span class="badge bg-info text-white">Local Port 8000</span>
                    </div>
                    <p class="text-secondary fs-8 mb-3">Use this URL when connecting agents locally on this machine:</p>
                    <div class="code-box">
                        <button class="copy-btn" onclick="copyText('<?= $localUrl ?>', this)"><i class="fas fa-copy me-1"></i>Copy</button>
                        <span><?= $localUrl ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Snippets -->
        <div class="glass-card p-4 mb-4">
            <h5 class="fw-bold text-dark mb-3"><i class="fas fa-sliders-h text-primary me-2"></i>One-Click Client Configurations</h5>
            
            <ul class="nav nav-pills mb-3" id="configTabs">
                <li class="nav-item">
                    <button class="nav-link active rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tabClaude">Claude Desktop</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tabCursor">Cursor (.cursor/mcp.json)</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tabAntigravity">Antigravity / Gemini CLI</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link rounded-pill fw-bold" data-bs-toggle="pill" data-bs-target="#tabStdio">Stdio CLI Command</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Claude Desktop -->
                <div class="tab-pane fade show active" id="tabClaude">
                    <p class="text-secondary fs-8 mb-2">Paste into your <code>~/Library/Application Support/Claude/claude_desktop_config.json</code>:</p>
                    <div class="code-box">
                        <button class="copy-btn" onclick="copyCode('claudeCode', this)"><i class="fas fa-copy me-1"></i>Copy JSON</button>
                        <pre id="claudeCode" class="m-0">{
  "mcpServers": {
    "workflow-pro": {
      "command": "php",
      "args": [
        "<?= addslashes($projectDir) ?>/api/mcp.php"
      ]
    }
  }
}</pre>
                    </div>
                </div>

                <!-- Cursor -->
                <div class="tab-pane fade" id="tabCursor">
                    <p class="text-secondary fs-8 mb-2">Paste into your workspace <code>.cursor/mcp.json</code>:</p>
                    <div class="code-box">
                        <button class="copy-btn" onclick="copyCode('cursorCode', this)"><i class="fas fa-copy me-1"></i>Copy JSON</button>
                        <pre id="cursorCode" class="m-0">{
  "mcpServers": {
    "workflow-pro": {
      "url": "<?= $liveUrl ?>"
    }
  }
}</pre>
                    </div>
                </div>

                <!-- Antigravity -->
                <div class="tab-pane fade" id="tabAntigravity">
                    <p class="text-secondary fs-8 mb-2">Saved automatically to <code>~/.gemini/config/mcp_config.json</code>:</p>
                    <div class="code-box">
                        <button class="copy-btn" onclick="copyCode('antigravityCode', this)"><i class="fas fa-copy me-1"></i>Copy JSON</button>
                        <pre id="antigravityCode" class="m-0">{
  "mcpServers": {
    "workflow-pro": {
      "command": "php",
      "args": [
        "<?= addslashes($projectDir) ?>/api/mcp.php"
      ]
    }
  }
}</pre>
                    </div>
                </div>

                <!-- Stdio -->
                <div class="tab-pane fade" id="tabStdio">
                    <p class="text-secondary fs-8 mb-2">Run directly via terminal or custom agent script:</p>
                    <div class="code-box">
                        <button class="copy-btn" onclick="copyCode('stdioCmd', this)"><i class="fas fa-copy me-1"></i>Copy Command</button>
                        <pre id="stdioCmd" class="m-0">php "<?= addslashes($projectDir) ?>/api/mcp.php"</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available MCP Tools Reference -->
        <div class="glass-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-tools text-primary me-2"></i>Exposed MCP Tools (<?= count($tools) ?> Tools Available)
                </h5>
                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" onclick="testOverviewTool()">
                    <i class="fas fa-play me-1"></i>Live Test in Browser
                </button>
            </div>
            
            <div class="row g-3">
                <?php foreach ($tools as $t): ?>
                    <div class="col-12 col-md-6">
                        <div class="p-3 border rounded-3 bg-light h-100">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="tool-badge"><?= htmlspecialchars($t['name']) ?></span>
                            </div>
                            <p class="text-secondary fs-8 mb-0"><?= htmlspecialchars($t['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Test Output Box -->
            <div id="testOutputCard" class="mt-4 d-none">
                <h6 class="fw-bold text-dark mb-2"><i class="fas fa-terminal text-success me-1.5"></i>Live Tool Test Output:</h6>
                <div class="code-box">
                    <pre id="testOutputPre" class="m-0"></pre>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.bundle.min.js"></script>
    <script>
        function copyText(text, btn) {
            navigator.clipboard.writeText(text).then(() => {
                const prev = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
                btn.classList.add('bg-success');
                setTimeout(() => {
                    btn.innerHTML = prev;
                    btn.classList.remove('bg-success');
                }, 2000);
            });
        }

        function copyCode(elementId, btn) {
            const el = document.getElementById(elementId);
            if (!el) return;
            copyText(el.innerText, btn);
        }

        function testOverviewTool() {
            const outCard = document.getElementById('testOutputCard');
            const outPre = document.getElementById('testOutputPre');
            outCard.classList.remove('d-none');
            outPre.textContent = 'Executing tools/call for get_workflow_overview...';

            fetch('<?= BASE_URL ?>/api/mcp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    jsonrpc: '2.0',
                    id: 1,
                    method: 'tools/call',
                    params: {
                        name: 'get_workflow_overview',
                        arguments: {}
                    }
                })
            })
            .then(r => r.json())
            .then(data => {
                outPre.textContent = JSON.stringify(data, null, 2);
            })
            .catch(err => {
                outPre.textContent = 'Error: ' + err.message;
            });
        }
    </script>
</body>
</html>
<?php
}
