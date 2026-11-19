<?php
/**
 * AI Workflow Assistant Engine - Core Helper
 * Provides natural language understanding, entity extraction, implementation plan generation,
 * authorization validation, transactional execution, and audit logging.
 */

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

/**
 * Ensure database tables for AI action plans and chat messages exist
 */
function ensureAiAssistantSchema(?PDO $db = null): void {
    if (!$db) $db = Database::getConnection();
    $driver = strtolower($db->getAttribute(PDO::ATTR_DRIVER_NAME) ?? Database::getDriver());
    $autoInc = ($driver === 'sqlite') ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';

    try {
        $db->exec("
            CREATE TABLE IF NOT EXISTS `ai_action_plans` (
                `id` {$autoInc},
                `user_id` INT NOT NULL,
                `plan_token` VARCHAR(64) NOT NULL UNIQUE,
                `action_type` VARCHAR(50) NOT NULL,
                `plan_data` TEXT NOT NULL,
                `summary` TEXT NOT NULL,
                `status` VARCHAR(30) DEFAULT 'awaiting_approval',
                `executed_by` INT NULL,
                `result_message` TEXT NULL,
                `result_data` TEXT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `expires_at` DATETIME NULL
            );
        ");

        $db->exec("
            CREATE TABLE IF NOT EXISTS `ai_chat_messages` (
                `id` {$autoInc},
                `user_id` INT NOT NULL,
                `conversation_id` VARCHAR(64) NOT NULL,
                `sender` VARCHAR(20) NOT NULL,
                `message` TEXT NOT NULL,
                `plan_token` VARCHAR(64) NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");
    } catch (Throwable $e) {
        error_log("AI Schema init notice: " . $e->getMessage());
    }
}

/**
 * Detect configured AI Provider
 * Returns 'openai' | 'gemini' | 'custom' | 'deterministic'
 */
function aiGetConfiguredProvider(): array {
    $openaiKey = getenv('OPENAI_API_KEY') ?: '';
    $geminiKey = getenv('GEMINI_API_KEY') ?: '';
    $customKey = getenv('AI_API_KEY') ?: '';

    if (!empty($openaiKey)) {
        return ['provider' => 'OpenAI', 'configured' => true, 'model' => getenv('OPENAI_MODEL') ?: 'gpt-4o-mini'];
    }
    if (!empty($geminiKey)) {
        return ['provider' => 'Google Gemini', 'configured' => true, 'model' => getenv('GEMINI_MODEL') ?: 'gemini-1.5-flash'];
    }
    if (!empty($customKey)) {
        return ['provider' => 'Custom LLM API', 'configured' => true, 'model' => 'custom'];
    }

    return ['provider' => 'Built-in Intelligent Engine', 'configured' => false, 'model' => 'deterministic_nlp_v1'];
}

/**
 * Fuzzy / Exact Employee Search
 * Matches by ID, exact name, partial name, or designation.
 * Respects hierarchy & assignability rules for current user.
 */
function aiSearchEmployee(PDO $db, string $query, array $currentUser): array {
    $q = trim($query);
    if (empty($q)) return [];

    // If query is an integer ID
    if (ctype_digit($q)) {
        $stmt = $db->prepare("SELECT id, name, designation, department_id, email FROM users WHERE id = ? AND status = 'active'");
        $stmt->execute([(int)$q]);
        $row = $stmt->fetch();
        return $row ? [$row] : [];
    }

    $qLower = strtolower($q);

    // Fetch all active employees
    $stmt = $db->query("
        SELECT u.id, u.name, u.designation, u.department_id, u.email, d.name as dept_name
        FROM users u
        LEFT JOIN departments d ON u.department_id = d.id
        WHERE u.status = 'active'
        ORDER BY u.name ASC
    ");
    $allUsers = $stmt->fetchAll() ?: [];

    $exactMatches = [];
    $partialMatches = [];

    foreach ($allUsers as $u) {
        $nameLower = strtolower($u['name']);
        $desigLower = strtolower($u['designation'] ?? '');
        $firstName = strtolower(explode(' ', trim($u['name']))[0]);

        // Exact full name or first name match
        if ($nameLower === $qLower || $firstName === $qLower) {
            $exactMatches[] = $u;
            continue;
        }

        // Substring match in name
        if (str_contains($nameLower, $qLower) || str_contains($qLower, $nameLower) || str_contains($qLower, $firstName)) {
            $partialMatches[] = $u;
            continue;
        }

        // Designation match (e.g. "intern frontend", "seo analyst")
        if (!empty($desigLower) && (str_contains($desigLower, $qLower) || str_contains($qLower, $desigLower))) {
            $partialMatches[] = $u;
        }
    }

    if (!empty($exactMatches)) {
        return $exactMatches;
    }

    return $partialMatches;
}

/**
 * Search Task by Title or ID
 */
function aiSearchTask(PDO $db, string $query, array $currentUser): array {
    $q = trim($query);
    if (empty($q)) return [];

    $vis = getTaskVisibilityCondition($currentUser, 't');
    $visSql = ($vis['sql'] !== '1=1') ? " AND {$vis['sql']}" : "";

    // 1. Check for explicit task ID like "task 25", "task #25", "#25", or "25"
    if (preg_match('/(?:task\s*#?|#)\s*(\d+)/i', $q, $m) || ctype_digit($q)) {
        $taskId = (int)($m[1] ?? $q);
        $stmt = $db->prepare("
            SELECT t.*, p.name as project_name, p.code as project_code,
                   u.name as assignee_name, u.designation as assignee_designation,
                   d.name as dept_name
            FROM tasks t
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN users u ON (t.assigned_to = u.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u.id))
            LEFT JOIN departments d ON t.department_id = d.id
            WHERE t.id = ? {$visSql}
        ");
        $stmt->execute(array_merge([$taskId], $vis['params']));
        $row = $stmt->fetch();
        return $row ? [$row] : [];
    }

    // Clean query from filler words
    $cleanQ = preg_replace('/\b(task|the|for|about|to|in|of|on)\b/i', ' ', $q);
    $cleanQ = trim(preg_replace('/\s+/', ' ', $cleanQ));

    // Try exact or prefix search first
    $stmt = $db->prepare("
        SELECT t.*, p.name as project_name, p.code as project_code,
                   u.name as assignee_name, u.designation as assignee_designation,
                   d.name as dept_name
        FROM tasks t
        LEFT JOIN projects p ON t.project_id = p.id
        LEFT JOIN users u ON (t.assigned_to = u.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u.id))
        LEFT JOIN departments d ON t.department_id = d.id
        WHERE LOWER(t.title) LIKE ? {$visSql}
        ORDER BY t.id DESC
        LIMIT 10
    ");
    $stmt->execute(array_merge(['%' . strtolower($cleanQ) . '%'], $vis['params']));
    $matches = $stmt->fetchAll() ?: [];

    if (!empty($matches)) {
        return $matches;
    }

    // Try word-by-word matching if phrase didn't match directly
    $words = array_filter(explode(' ', strtolower($cleanQ)), fn($w) => strlen($w) > 3);
    if (!empty($words)) {
        $whereClauses = [];
        $params = [];
        foreach ($words as $w) {
            $whereClauses[] = "LOWER(t.title) LIKE ?";
            $params[] = '%' . $w . '%';
        }
        $sql = "
            SELECT t.*, p.name as project_name, p.code as project_code,
                   u.name as assignee_name, u.designation as assignee_designation,
                   d.name as dept_name
            FROM tasks t
            LEFT JOIN projects p ON t.project_id = p.id
            LEFT JOIN users u ON (t.assigned_to = u.id OR (t.assigned_to IS NULL AND t.assigned_to_employee_id = u.id))
            LEFT JOIN departments d ON t.department_id = d.id
            WHERE (" . implode(' OR ', $whereClauses) . ") {$visSql}
            ORDER BY t.id DESC
            LIMIT 10
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute(array_merge($params, $vis['params']));
        return $stmt->fetchAll() ?: [];
    }

    return [];
}

/**
 * Search Project by Name or Code
 */
function aiSearchProject(PDO $db, string $query): ?array {
    $q = trim($query);
    if (empty($q)) return null;

    $stmt = $db->prepare("
        SELECT * FROM projects 
        WHERE LOWER(name) LIKE ? OR LOWER(code) LIKE ?
        ORDER BY id ASC
        LIMIT 1
    ");
    $stmt->execute(['%' . strtolower($q) . '%', '%' . strtolower($q) . '%']);
    return $stmt->fetch() ?: null;
}

/**
 * Natural Language Date/Time Parser for Deadlines
 */
function aiParseDeadline(string $text, ?string $baseTime = null): ?string {
    $t = strtolower(trim($text));
    $refTimestamp = $baseTime ? strtotime($baseTime) : time();
    $defaultHourMinute = '18:30:00';

    // 1. Explicit Relative Dates
    if (str_contains($t, 'tomorrow')) {
        $datePart = date('Y-m-d', strtotime('+1 day', $refTimestamp));
        return $datePart . ' ' . $defaultHourMinute;
    }

    if (str_contains($t, 'today') || str_contains($t, 'tonight') || str_contains($t, 'eod') || str_contains($t, 'end of day')) {
        $datePart = date('Y-m-d', $refTimestamp);
        return $datePart . ' ' . $defaultHourMinute;
    }

    // "in X days"
    if (preg_match('/in\s+(\d+)\s+days?/i', $t, $m)) {
        $days = (int)$m[1];
        $datePart = date('Y-m-d', strtotime("+{$days} days", $refTimestamp));
        return $datePart . ' ' . $defaultHourMinute;
    }

    // Days of the week (e.g. "friday", "next monday", "this saturday")
    $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    foreach ($daysOfWeek as $dow) {
        if (preg_match('/(?:this|next)?\s*' . $dow . '/i', $t)) {
            $parsed = strtotime("next {$dow}", $refTimestamp);
            // If today is that day, or next occurrence
            if (strtolower(date('l', $refTimestamp)) === $dow) {
                $parsed = strtotime("+7 days", $refTimestamp);
            }
            return date('Y-m-d', $parsed) . ' ' . $defaultHourMinute;
        }
    }

    // Explicit date strings like "20 October 2026", "20 Oct", "20/10/2026", "2026-10-20"
    $cleanDate = preg_replace('/^(?:deadline\s*(?:of|to|is)?|due\s*(?:on|by|at)?)\s*/i', '', $t);
    $ts = strtotime($cleanDate);
    if ($ts && $ts > strtotime('2020-01-01')) {
        // If no time was specified, default to 18:30 IST
        if (date('H:i:s', $ts) === '00:00:00') {
            return date('Y-m-d', $ts) . ' ' . $defaultHourMinute;
        }
        return date('Y-m-d H:i:s', $ts);
    }

    return null;
}

/**
 * Natural Language Priority Parser
 */
function aiParsePriority(string $text): ?string {
    $t = strtolower($text);
    if (str_contains($t, 'urgent') || str_contains($t, 'critical') || str_contains($t, 'immediate') || str_contains($t, 'asap')) {
        return 'urgent';
    }
    if (str_contains($t, 'high') || str_contains($t, 'highest') || str_contains($t, 'important')) {
        return 'high';
    }
    if (str_contains($t, 'low') || str_contains($t, 'minor') || str_contains($t, 'lowest')) {
        return 'low';
    }
    if (str_contains($t, 'medium') || str_contains($t, 'normal') || str_contains($t, 'moderate')) {
        return 'medium';
    }
    return null;
}

/**
 * Core Command Processing Engine
 * Analyzes natural language input, matches entities, and generates implementation plans or answers.
 */
function aiProcessUserCommand(array $user, string $message, string $conversationId, PDO $db): array {
    ensureAiAssistantSchema($db);

    $msg = trim($message);
    $msgLower = strtolower($msg);
    $userId = (int)$user['id'];
    $category = getUserRoleCategory($user);
    $isManager = in_array($category, ['admin', 'manager', 'product_manager']);
    $canManageTasks = canCreateOrEditTasks($user);

    // Save user message to chat history
    try {
        $stmt = $db->prepare("INSERT INTO ai_chat_messages (user_id, conversation_id, sender, message) VALUES (?, ?, 'user', ?)");
        $stmt->execute([$userId, $conversationId, $msg]);
    } catch (Exception $e) {}

    // =========================================================================
    // 1. READ-ONLY STATUS & INFORMATIVE QUERIES (NO DATABASE MODIFICATION PLAN)
    // =========================================================================

    // A. Show open task flags / flagged tasks
    if (preg_match('/(?:show|list|view|check)\s+(?:all\s+)?(?:open\s+)?(?:task\s+)?flags?/i', $msgLower) ||
        str_contains($msgLower, 'flagged tasks') || str_contains($msgLower, 'missing requirements')) {
        
        $sql = "
            SELECT f.*, t.title as task_title, u.name as employee_name, p.name as project_name
            FROM task_flags f
            JOIN tasks t ON f.task_id = t.id
            JOIN users u ON f.employee_id = u.id
            LEFT JOIN projects p ON t.project_id = p.id
            WHERE f.status != 'resolved'
            ORDER BY f.id DESC
        ";
        $flags = $db->query($sql)->fetchAll() ?: [];

        if (empty($flags)) {
            $reply = "✅ **No Open Task Flags!**\nAll task requirements are satisfied and no deliverables are currently blocked.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $items = [];
        foreach ($flags as $f) {
            $items[] = "🚩 **Flag #{$f['id']} on Task #{$f['task_id']}** ('{$f['task_title']}')\n" .
                       "• **Raised By**: {$f['employee_name']}\n" .
                       "• **Category**: {$f['category']}\n" .
                       "• **Missing Requirement**: " . sanitize($f['missing_requirement']) . "\n" .
                       "• **Impact**: {$f['impact']}\n" .
                       "• **Status**: `" . strtoupper($f['status']) . "`\n" .
                       "*(To resolve: tell me 'Mark flag {$f['id']} as resolved' or 'Add response to flag {$f['id']}')*";
        }

        $reply = "📋 **Currently Open Task Flags (" . count($flags) . " Found)**:\n\n" . implode("\n\n", $items);
        saveAiAssistantMessage($db, $userId, $conversationId, $reply);
        return ['type' => 'message', 'reply' => $reply];
    }

    // B. Show pending emergency requests
    if (preg_match('/(?:show|list|view|check)\s+(?:all\s+)?(?:pending\s+)?emergency\s*(?:task)?\s*requests?/i', $msgLower) ||
        (str_contains($msgLower, 'emergency') && (str_contains($msgLower, 'pending') || str_contains($msgLower, 'show') || str_contains($msgLower, 'list')))) {
        
        $sql = "
            SELECT e.*, t.title as task_title, u.name as employee_name
            FROM emergency_task_requests e
            JOIN tasks t ON e.task_id = t.id
            JOIN users u ON e.employee_id = u.id
            WHERE e.status = 'pending'
            ORDER BY e.id DESC
        ";
        $emergencies = $db->query($sql)->fetchAll() ?: [];

        if (empty($emergencies)) {
            $reply = "✅ **No Pending Emergency Requests**\nThere are no active emergency escalations awaiting review.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $items = [];
        foreach ($emergencies as $e) {
            $items[] = "🚨 **Emergency Request #{$e['id']} for Task #{$e['task_id']}** ('{$e['task_title']}')\n" .
                       "• **Employee**: {$e['employee_name']}\n" .
                       "• **Reason**: {$e['reason']}\n" .
                       "• **Urgency Message**: " . sanitize($e['message']) . "\n" .
                       "• **Created**: " . formatISTDate($e['created_at']) . "\n" .
                       "*(To approve: tell me 'Approve emergency request {$e['id']}')*";
        }

        $reply = "⚡ **Pending Emergency Requests (" . count($emergencies) . " Awaiting Review)**:\n\n" . implode("\n\n", $items);
        saveAiAssistantMessage($db, $userId, $conversationId, $reply);
        return ['type' => 'message', 'reply' => $reply];
    }

    // C. Show pending tasks assigned to employee X, or list overdue tasks
    if (preg_match('/(?:show|list|get|find)\s+(?:all\s+)?(?:pending\s+)?tasks?\s+(?:assigned\s+to|for)\s+([a-zA-Z\s]+)/i', $msgLower, $m)) {
        $empName = trim($m[1]);
        $employees = aiSearchEmployee($db, $empName, $user);

        if (empty($employees)) {
            $reply = "❓ Could not find an active employee matching **'{$empName}'**. Please check the name and try again.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $targetEmp = $employees[0];
        $empId = (int)$targetEmp['id'];

        $stmt = $db->prepare("
            SELECT t.*, p.name as project_name 
            FROM tasks t
            LEFT JOIN projects p ON t.project_id = p.id
            WHERE t.assigned_to = ? AND t.status != 'completed'
            ORDER BY (CASE WHEN t.deadline IS NOT NULL AND t.deadline < NOW() THEN 0 ELSE 1 END), t.id DESC
        ");
        $stmt->execute([$empId]);
        $empTasks = $stmt->fetchAll() ?: [];

        if (empty($empTasks)) {
            $reply = "🎉 **All Caught Up!** **{$targetEmp['name']}** has no active or pending tasks assigned right now.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $taskList = [];
        foreach ($empTasks as $t) {
            $dl = $t['deadline'] ? formatISTDate($t['deadline']) : 'No deadline';
            $isOverdue = ($t['deadline'] && strtotime($t['deadline']) < time()) ? " ⚠️ **OVERDUE**" : "";
            $taskList[] = "• **Task #{$t['id']}**: [{$t['title']}](" . BASE_URL . "/task-details?id={$t['id']})\n" .
                          "  - Priority: `" . strtoupper($t['priority']) . "` | Status: `" . ucfirst($t['status']) . "`\n" .
                          "  - Deadline: {$dl}{$isOverdue}\n" .
                          "  - Project: " . ($t['project_name'] ? sanitize($t['project_name']) : 'Standalone');
        }

        $reply = "📌 **Active Tasks for {$targetEmp['name']} (" . count($empTasks) . " Total)**:\n\n" . implode("\n\n", $taskList);
        saveAiAssistantMessage($db, $userId, $conversationId, $reply);
        return ['type' => 'message', 'reply' => $reply];
    }

    // D. List overdue tasks this week or across company
    if (str_contains($msgLower, 'overdue') || (str_contains($msgLower, 'delayed') && str_contains($msgLower, 'task'))) {
        $stmt = $db->query("
            SELECT t.*, u.name as assignee_name, p.name as project_name
            FROM tasks t
            JOIN users u ON t.assigned_to = u.id
            LEFT JOIN projects p ON t.project_id = p.id
            WHERE t.deadline < NOW() AND t.status != 'completed'
            ORDER BY t.deadline ASC
            LIMIT 10
        ");
        $overdueTasks = $stmt->fetchAll() ?: [];

        if (empty($overdueTasks)) {
            $reply = "✅ **All Clear!** There are currently no overdue tasks in the workflow system.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $items = [];
        foreach ($overdueTasks as $t) {
            $items[] = "⚠️ **Task #{$t['id']}**: [{$t['title']}](" . BASE_URL . "/task-details?id={$t['id']})\n" .
                       "• **Assigned To**: {$t['assignee_name']}\n" .
                       "• **Deadline Was**: " . formatISTDate($t['deadline']) . " (" . formatRelativeTime($t['deadline']) . ")\n" .
                       "• **Priority**: `" . strtoupper($t['priority']) . "` | Project: " . ($t['project_name'] ?: 'None');
        }

        $reply = "🚨 **Currently Overdue Tasks (" . count($overdueTasks) . " Found)**:\n\n" . implode("\n\n", $items);
        saveAiAssistantMessage($db, $userId, $conversationId, $reply);
        return ['type' => 'message', 'reply' => $reply];
    }

    // E. Today's Employee Work Summary / End-of-Day Report
    if (str_contains($msgLower, 'summary') || str_contains($msgLower, 'work summary') || str_contains($msgLower, 'end of day') || str_contains($msgLower, 'eod report')) {
        $today = date('Y-m-d');
        $stmt = $db->query("
            SELECT u.id, u.name, u.designation, d.name as dept_name,
                   (SELECT COUNT(*) FROM tasks WHERE assigned_to = u.id AND status = 'completed' AND DATE(completed_at) = '{$today}') as completed_today,
                   (SELECT COUNT(*) FROM tasks WHERE assigned_to = u.id AND status != 'completed') as pending_count,
                   (SELECT clock_in FROM attendance WHERE user_id = u.id AND date = '{$today}' LIMIT 1) as clock_in,
                   (SELECT total_seconds FROM attendance WHERE user_id = u.id AND date = '{$today}' LIMIT 1) as total_seconds
            FROM users u
            LEFT JOIN departments d ON u.department_id = d.id
            WHERE u.status = 'active'
            ORDER BY u.department_id ASC, u.name ASC
        ");
        $employees = $stmt->fetchAll() ?: [];

        $lines = [];
        foreach ($employees as $emp) {
            $cIn = $emp['clock_in'] ? formatISTTime($emp['clock_in']) : 'Not clocked in';
            $worked = $emp['total_seconds'] ? formatDuration((int)$emp['total_seconds']) : '--';
            $lines[] = "• **{$emp['name']}** ({$emp['designation']})\n" .
                       "  - Clock In: {$cIn} | Hours: {$worked}\n" .
                       "  - Tasks: **{$emp['completed_today']} completed today**, **{$emp['pending_count']} pending**";
        }

        $reply = "📊 **Today's Team Activity & Work Summary (" . date('d M Y') . ")**:\n\n" . implode("\n\n", $lines);
        saveAiAssistantMessage($db, $userId, $conversationId, $reply);
        return ['type' => 'message', 'reply' => $reply];
    }

    // F. Show current task details / task status
    if (preg_match('/(?:show|view|get|display|check)\s+(?:the\s+)?details?\s+(?:of|for)?\s+(?:task\s*#?\s*(\d+)|([^\'"]+?))$/i', $msgLower, $m) ||
        preg_match('/(?:task\s*#?\s*(\d+))\s+details?/i', $msgLower, $m)) {
        $taskQ = !empty($m[1]) ? $m[1] : (!empty($m[2]) ? trim($m[2]) : '');
        $matched = aiSearchTask($db, $taskQ, $user);
        if (empty($matched)) {
            $reply = preg_match('/^\d+$/', $taskQ)
                ? "❌ Task **#{$taskQ}** was not found in the database."
                : "❓ Could not identify which task you want details for. Please provide a task ID or title.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }
        $t = $matched[0];
        $dl = $t['deadline'] ? formatISTDate($t['deadline']) : 'No deadline set';
        $reply = "📌 **Task Details: #{$t['id']} &ndash; {$t['title']}**\n\n" .
                 "• **Project**: " . ($t['project_name'] ? "{$t['project_name']} (`{$t['project_code']}`)" : "Standalone Deliverable") . "\n" .
                 "• **Assignee**: " . ($t['assignee_name'] ? "{$t['assignee_name']} ({$t['assignee_designation']})" : "Unassigned") . "\n" .
                 "• **Priority**: `" . strtoupper($t['priority']) . "`\n" .
                 "• **Status**: `" . ucfirst($t['status']) . "`\n" .
                 "• **Deadline**: {$dl}\n" .
                 "• **Department**: " . ($t['dept_name'] ?: 'General') . "\n" .
                 (!empty($t['description']) ? "• **Description**: " . sanitize($t['description']) . "\n" : "") .
                 "\n🔗 [View Full Task Page](" . BASE_URL . "/task-details?id={$t['id']})";
        saveAiAssistantMessage($db, $userId, $conversationId, $reply);
        return ['type' => 'message', 'reply' => $reply];
    }

    // =========================================================================
    // 2. ACTION PLAN GENERATION (MUTATING ACTIONS - REQUIRE USER APPROVAL)
    // =========================================================================

    // --- CASE A: RESOLVE TASK FLAG ---
    if (preg_match('/(?:mark\s+)?flag\s*#?\s*(\d+)\s*(?:as\s*)?resolved/i', $msg, $m) ||
        preg_match('/resolve\s+(?:task\s+)?flag\s*#?\s*(\d+)/i', $msg, $m)) {
        
        $flagId = (int)$m[1];
        $fStmt = $db->prepare("
            SELECT f.*, t.title as task_title, u.name as employee_name 
            FROM task_flags f
            JOIN tasks t ON f.task_id = t.id
            JOIN users u ON f.employee_id = u.id
            WHERE f.id = ?
        ");
        $fStmt->execute([$flagId]);
        $flag = $fStmt->fetch();

        if (!$flag) {
            $reply = "❌ Task flag **#{$flagId}** was not found in the database.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        if ($flag['status'] === 'resolved') {
            $reply = "ℹ️ Task flag **#{$flagId}** is already resolved.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        // Extract resolution message if provided in command
        $resMsg = 'Requirements provided and blockers resolved via AI Assistant.';
        if (preg_match('/(?:explaining|reason|message|details|with|note)\s*[:=]?\s*[\'"]?([^\'"]+)[\'"]?$/i', $msg, $rm)) {
            $resMsg = trim($rm[1]);
        }

        $planData = [
            'action_type' => 'resolve_flag',
            'flag_id' => $flagId,
            'task_id' => (int)$flag['task_id'],
            'task_title' => $flag['task_title'],
            'employee_id' => (int)$flag['employee_id'],
            'employee_name' => $flag['employee_name'],
            'resolution_message' => $resMsg
        ];

        $summary = "Resolve Task Flag #{$flagId} for '{$flag['task_title']}' and mark requirements satisfied.";
        $plannedSteps = [
            "1. Verify that Flag #{$flagId} exists and is currently open.",
            "2. Confirm user authorization to resolve task flags.",
            "3. Update Flag #{$flagId} status to 'resolved' in the database.",
            "4. Record resolution message: \"{$resMsg}\".",
            "5. Send resolution notification to {$flag['employee_name']}.",
            "6. Log resolution in the workspace audit activity logs."
        ];

        return createAiImplementationPlan($db, $userId, $conversationId, 'resolve_flag', $planData, $summary, [
            'action_title' => 'Resolve Task Flag',
            'target_name' => "Flag #{$flagId} &ndash; " . sanitize($flag['task_title']),
            'employee_name' => $flag['employee_name'],
            'resolution_notes' => $resMsg,
            'planned_steps' => $plannedSteps
        ]);
    }

    // --- CASE B: ADD COMMENT / RESPONSE TO TASK FLAG ---
    if (preg_match('/add\s+(?:a\s+)?(?:response|comment)\s+to\s+flag\s*#?\s*(\d+)\s*(?:explaining|saying|that|with)?\s*[:=]?\s*(.+)/i', $msg, $m)) {
        $flagId = (int)$m[1];
        $comment = trim($m[2]);

        $fStmt = $db->prepare("SELECT f.*, t.title as task_title FROM task_flags f JOIN tasks t ON f.task_id = t.id WHERE f.id = ?");
        $fStmt->execute([$flagId]);
        $flag = $fStmt->fetch();

        if (!$flag) {
            $reply = "❌ Task flag **#{$flagId}** was not found.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $planData = [
            'action_type' => 'add_flag_comment',
            'flag_id' => $flagId,
            'task_id' => (int)$flag['task_id'],
            'comment' => $comment
        ];

        $summary = "Add response comment to Task Flag #{$flagId}.";
        $plannedSteps = [
            "1. Verify Task Flag #{$flagId} existence.",
            "2. Post authorized comment into flag discussion thread.",
            "3. Notify task owner and assignee regarding update.",
            "4. Record action in audit log."
        ];

        return createAiImplementationPlan($db, $userId, $conversationId, 'add_flag_comment', $planData, $summary, [
            'action_title' => 'Add Comment to Flag',
            'target_name' => "Flag #{$flagId} &ndash; " . sanitize($flag['task_title']),
            'comment_text' => $comment,
            'planned_steps' => $plannedSteps
        ]);
    }

    // --- CASE C: APPROVE EMERGENCY REQUEST ---
    if (preg_match('/approve\s+(?:emergency\s*(?:task)?\s*request)\s*#?\s*(\d+)/i', $msg, $m)) {
        if (!$canManageTasks) {
            $reply = "🔒 **Permission Denied**: Only Administrators, Managers, and Product Managers are authorized to approve emergency task requests.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $emgId = (int)$m[1];
        $stmt = $db->prepare("
            SELECT e.*, t.title as task_title, u.name as employee_name
            FROM emergency_task_requests e
            JOIN tasks t ON e.task_id = t.id
            JOIN users u ON e.employee_id = u.id
            WHERE e.id = ?
        ");
        $stmt->execute([$emgId]);
        $emg = $stmt->fetch();

        if (!$emg) {
            $reply = "❌ Emergency Request **#{$emgId}** was not found.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        if ($emg['status'] !== 'pending') {
            $reply = "ℹ️ Emergency Request **#{$emgId}** is already in `" . strtoupper($emg['status']) . "` state.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $planData = [
            'action_type' => 'approve_emergency',
            'emergency_id' => $emgId,
            'task_id' => (int)$emg['task_id'],
            'employee_id' => (int)$emg['employee_id']
        ];

        $summary = "Approve Emergency Request #{$emgId} for Task #{$emg['task_id']} ('{$emg['task_title']}').";
        $plannedSteps = [
            "1. Verify that Emergency Request #{$emgId} is in pending status.",
            "2. Confirm user authorization as Manager/Admin.",
            "3. Update request status to 'approved' and record reviewer ID.",
            "4. Automatically elevate associated Task #{$emg['task_id']} priority to 'urgent'.",
            "5. Dispatch urgent approval notification to {$emg['employee_name']}.",
            "6. Log emergency approval in the system audit logs."
        ];

        return createAiImplementationPlan($db, $userId, $conversationId, 'approve_emergency', $planData, $summary, [
            'action_title' => 'Approve Emergency Request',
            'target_name' => "Emergency Request #{$emgId} &ndash; " . sanitize($emg['task_title']),
            'employee_name' => $emg['employee_name'],
            'planned_steps' => $plannedSteps
        ]);
    }

    // --- CASE D: CHANGE TASK PRIORITY ---
    if (preg_match('/(?:change|update|set|make)\s+(?:task\s*#?\s*(\d+)|([^\'"]+?))\s+priority\s+(?:to\s+)?(urgent|high|medium|low)/i', $msg, $m) ||
        preg_match('/(?:change|update|set|make)\s+priority\s+(?:of\s+)?(?:task\s*#?\s*(\d+)|([^\'"]+?))\s+(?:to\s+)?(urgent|high|medium|low)/i', $msg, $m)) {
        
        $targetTaskIdOrTitle = !empty($m[1]) ? $m[1] : (!empty($m[2]) ? trim($m[2]) : '');
        $newPriority = strtolower(trim($m[3]));

        $matchedTasks = aiSearchTask($db, $targetTaskIdOrTitle, $user);
        if (empty($matchedTasks)) {
            $reply = preg_match('/^\d+$/', $targetTaskIdOrTitle)
                ? "❌ Task **#{$targetTaskIdOrTitle}** was not found in the database. Please verify the task ID."
                : "❓ Could not identify which task you want to update priority for. Please provide a valid task ID or title (e.g., 'Change task 25 priority to high').";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        if (count($matchedTasks) > 1) {
            $options = [];
            foreach (array_slice($matchedTasks, 0, 4) as $mt) {
                $options[] = "• **Task #{$mt['id']}**: {$mt['title']} (Current: " . ucfirst($mt['priority']) . ")";
            }
            $reply = "🔍 I found multiple tasks matching your description. Which one would you like to update?\n\n" .
                     implode("\n", $options) . "\n\n*Please specify the task ID (e.g. 'Change task {$matchedTasks[0]['id']} priority to {$newPriority}').*";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $task = $matchedTasks[0];

        $planData = [
            'action_type' => 'update_priority',
            'task_id' => (int)$task['id'],
            'task_title' => $task['title'],
            'old_priority' => $task['priority'],
            'new_priority' => $newPriority,
            'assigned_to' => (int)$task['assigned_to']
        ];

        $summary = "Change priority of Task #{$task['id']} ('{$task['title']}') from " . ucfirst($task['priority']) . " to " . ucfirst($newPriority) . ".";
        $plannedSteps = [
            "1. Verify Task #{$task['id']} exists in the database.",
            "2. Confirm permission to modify task priority.",
            "3. Update task priority to '" . ucfirst($newPriority) . "'.",
            "4. Send priority escalation notification to assignee ({$task['assignee_name']}).",
            "5. Save changes and record in audit log."
        ];

        return createAiImplementationPlan($db, $userId, $conversationId, 'update_priority', $planData, $summary, [
            'action_title' => 'Update Task Priority',
            'target_name' => "Task #{$task['id']} &ndash; " . sanitize($task['title']),
            'old_value' => ucfirst($task['priority']),
            'new_value' => ucfirst($newPriority),
            'employee_name' => $task['assignee_name'] ?: 'Unassigned',
            'planned_steps' => $plannedSteps
        ]);
    }

    // --- CASE E: UPDATE TASK DEADLINE ---
    if (preg_match('/(?:update|change|set|extend)\s+(?:the\s+)?deadline\s+(?:of\s+)?(?:task\s*#?\s*(\d+)|([^\'"]+?))\s+(?:to|by|for)\s+([^\.]+)/i', $msg, $m) ||
        preg_match('/(?:update|change|set)\s+(?:task\s*#?\s*(\d+))\s+deadline\s+(?:to|by|for)\s+([^\.]+)/i', $msg, $m)) {
        
        $targetTaskIdOrTitle = !empty($m[1]) ? $m[1] : (!empty($m[2]) ? trim($m[2]) : '');
        $rawDeadline = trim(!empty($m[3]) ? $m[3] : ($m[2] ?? ''));

        $matchedTasks = aiSearchTask($db, $targetTaskIdOrTitle, $user);
        if (empty($matchedTasks)) {
            $reply = preg_match('/^\d+$/', $targetTaskIdOrTitle)
                ? "❌ Task **#{$targetTaskIdOrTitle}** was not found in the database. Please verify the task ID."
                : "❓ Could not identify which task you want to update the deadline for. Please specify a task ID or title.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        if (count($matchedTasks) > 1) {
            $options = [];
            foreach (array_slice($matchedTasks, 0, 4) as $mt) {
                $options[] = "• **Task #{$mt['id']}**: {$mt['title']}";
            }
            $reply = "🔍 Multiple tasks matched. Which one should have its deadline updated?\n\n" .
                     implode("\n", $options) . "\n\n*Please specify the task ID.*";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $task = $matchedTasks[0];
        $parsedDeadline = aiParseDeadline($rawDeadline);

        if (!$parsedDeadline) {
            $reply = "❓ Could not parse the deadline date **'{$rawDeadline}'**. Please provide a date like 'tomorrow', 'Friday', or '20 October 2026'.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $oldDlFormatted = $task['deadline'] ? formatISTDate($task['deadline']) : 'None';
        $newDlFormatted = formatISTDate($parsedDeadline) . " (IST)";

        $planData = [
            'action_type' => 'update_deadline',
            'task_id' => (int)$task['id'],
            'task_title' => $task['title'],
            'old_deadline' => $task['deadline'],
            'new_deadline' => $parsedDeadline,
            'assigned_to' => (int)$task['assigned_to']
        ];

        $summary = "Update deadline for Task #{$task['id']} ('{$task['title']}') to {$newDlFormatted}.";
        $plannedSteps = [
            "1. Verify Task #{$task['id']} exists in the database.",
            "2. Confirm user authorization to update deadlines.",
            "3. Update task deadline to {$newDlFormatted}.",
            "4. Send updated deadline alert to assignee ({$task['assignee_name']}).",
            "5. Save changes and log in audit activity trail."
        ];

        return createAiImplementationPlan($db, $userId, $conversationId, 'update_deadline', $planData, $summary, [
            'action_title' => 'Update Task Deadline',
            'target_name' => "Task #{$task['id']} &ndash; " . sanitize($task['title']),
            'old_value' => $oldDlFormatted,
            'new_value' => $newDlFormatted,
            'employee_name' => $task['assignee_name'] ?: 'Unassigned',
            'planned_steps' => $plannedSteps
        ]);
    }

    // --- CASE F: ASSIGN / REASSIGN EXISTING TASK ---
    if (preg_match('/(?:reassign|assign)\s+(?:the\s+)?(.+?)\s+(?:to\s+)([a-zA-Z\s]+)/i', $msg, $m)) {
        $taskQuery = trim($m[1]);
        $employeePart = trim($m[2]);

        // Extract possible deadline from employee part or remaining text
        // e.g. "Abhay with a deadline of tomorrow"
        $extractedDeadline = null;
        if (preg_match('/^([a-zA-Z\s]+?)\s+(?:with\s+(?:a\s+)?deadline\s+(?:of|to|is)?\s+|and\s+set\s+(?:the\s+)?deadline\s+to\s+)(.+)$/i', $employeePart, $dm)) {
            $employeePart = trim($dm[1]);
            $extractedDeadline = aiParseDeadline(trim($dm[2]));
        }

        // Clean out trailing project specifiers from taskQuery if present
        $matchedProject = null;
        if (preg_match('/^([^\(]+?)\s+(?:in|for|under)\s+(?:the\s+)?([a-zA-Z0-9\s]+?)\s+project/i', $taskQuery, $pm)) {
            $taskQuery = trim($pm[1]);
            $matchedProject = aiSearchProject($db, trim($pm[2]));
        }

        // 1. Resolve Employee
        if (str_contains(strtolower($employeePart), 'another employee') || str_contains(strtolower($employeePart), 'someone else') || str_contains(strtolower($employeePart), 'other employee') || empty($employeePart)) {
            $reply = "❓ **Clarification Needed**: Which employee would you like to reassign this task to, and which task should be reassigned?\n\n*For example: \"Assign task 26 to Abhay with a deadline of tomorrow.\"*";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $matchedEmployees = aiSearchEmployee($db, $employeePart, $user);
        if (empty($matchedEmployees)) {
            $reply = "❓ Could not identify employee **'{$employeePart}'**. Please verify the employee name and try again.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        if (count($matchedEmployees) > 1) {
            $choices = [];
            foreach ($matchedEmployees as $me) {
                $choices[] = "• **{$me['name']}** ({$me['designation']} &ndash; {$me['dept_name']}) [ID: {$me['id']}]";
            }
            $reply = "🔍 Multiple employees match **'{$employeePart}'**. Which employee should be assigned?\n\n" .
                     implode("\n", $choices) . "\n\n*Please specify the exact name or employee ID.*";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $targetEmployee = $matchedEmployees[0];
        $targetEmployeeId = (int)$targetEmployee['id'];

        // 2. Resolve Task
        $matchedTasks = aiSearchTask($db, $taskQuery, $user);
        
        // If task not found and user has create permissions, suggest creating it!
        if (empty($matchedTasks)) {
            if ($canManageTasks) {
                $reply = "ℹ️ I could not find an existing task matching **'{$taskQuery}'**.\n\n" .
                         "Would you like me to **create a new task** titled **'{$taskQuery}'** and assign it to **{$targetEmployee['name']}**?\n\n" .
                         "*Reply with: 'Yes, create task {$taskQuery} for {$targetEmployee['name']}'*";
                saveAiAssistantMessage($db, $userId, $conversationId, $reply);
                return ['type' => 'message', 'reply' => $reply];
            } else {
                $reply = "❌ No task found matching **'{$taskQuery}'**. Please verify the task title or task ID.";
                saveAiAssistantMessage($db, $userId, $conversationId, $reply);
                return ['type' => 'message', 'reply' => $reply];
            }
        }

        if (count($matchedTasks) > 1) {
            $choices = [];
            foreach (array_slice($matchedTasks, 0, 4) as $mt) {
                $choices[] = "• **Task #{$mt['id']}**: {$mt['title']} (Currently assigned to: " . ($mt['assignee_name'] ?: 'None') . ")";
            }
            $reply = "🔍 Multiple tasks match **'{$taskQuery}'**. Which one should be assigned to {$targetEmployee['name']}?\n\n" .
                     implode("\n", $choices) . "\n\n*Please specify with task ID (e.g. 'Assign task {$matchedTasks[0]['id']} to {$targetEmployee['name']}').*";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $task = $matchedTasks[0];

        // 3. Permission Check Pre-flight
        if (!isAssigneeAllowedForUser($user, $targetEmployeeId, $db)) {
            $reply = "🔒 **Permission Restriction**: As " . sanitize($user['role_title'] ?? $user['name']) . ", company policy does not authorize you to assign tasks to **{$targetEmployee['name']}**.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $deadlineStr = $extractedDeadline ? formatISTDate($extractedDeadline) . " (IST)" : ($task['deadline'] ? formatISTDate($task['deadline']) . " (IST)" : "Unchanged (None)");
        $projectName = $task['project_name'] ?: ($matchedProject['name'] ?? 'Unchanged / Standalone');

        $planData = [
            'action_type' => 'assign_task',
            'task_id' => (int)$task['id'],
            'task_title' => $task['title'],
            'employee_id' => $targetEmployeeId,
            'employee_name' => $targetEmployee['name'],
            'employee_designation' => $targetEmployee['designation'],
            'new_deadline' => $extractedDeadline,
            'old_assignee' => $task['assignee_name'] ?: 'Unassigned',
            'project_name' => $projectName
        ];

        $summary = "Assign Task #{$task['id']} ('{$task['title']}') to {$targetEmployee['name']}.";
        $plannedSteps = [
            "1. Verify that Task #{$task['id']} exists in the database.",
            "2. Verify that {$targetEmployee['name']} is an eligible active employee.",
            "3. Confirm that {$user['name']} has authorized permission to assign tasks to {$targetEmployee['name']}.",
            "4. Assign Task #{$task['id']} to {$targetEmployee['name']} in the database.",
            $extractedDeadline ? "5. Update deadline to {$deadlineStr}." : "5. Keep existing deadline ({$deadlineStr}).",
            "6. Dispatch real-time assignment notification to {$targetEmployee['name']}.",
            "7. Record the assignment event in the workspace activity audit logs."
        ];

        return createAiImplementationPlan($db, $userId, $conversationId, 'assign_task', $planData, $summary, [
            'action_title' => 'Assign Existing Task',
            'target_name' => "Task #{$task['id']} &ndash; " . sanitize($task['title']),
            'employee_name' => "{$targetEmployee['name']} (" . sanitize($targetEmployee['designation'] ?? 'Staff') . ")",
            'project_name' => sanitize($projectName),
            'deadline_text' => $deadlineStr,
            'priority_text' => ucfirst($task['priority']) . " (Unchanged)",
            'planned_steps' => $plannedSteps
        ]);
    }

    // --- CASE G: CREATE NEW TASK (WITH OPTIONAL MULTI-ACTION: ASSIGN + PROJECT + DEADLINE + PRIORITY) ---
    $isCreateTask = false;
    $preEmployee = null;
    $preTitle = null;

    if (preg_match('/create\s+(?:a\s+)?task\s+(?:for|to)\s+([a-zA-Z\s]+?)\s+to\s+(.+)/i', $msg, $cm)) {
        // e.g. "Create a task for Abhay to fix the mobile responsive layout."
        $isCreateTask = true;
        $preEmployee = trim($cm[1]);
        $preTitle = trim($cm[2]);
    } elseif (preg_match('/create\s+(?:a\s+)?([^\'"]+?)\s+task\s+(?:for|to)\s+([a-zA-Z\s]+)/i', $msg, $cm)) {
        // e.g. "Create a homepage optimization task for Abhay..."
        $isCreateTask = true;
        $preTitle = trim($cm[1]);
        $preEmployee = trim($cm[2]);
    } elseif (preg_match('/(?:create|add|new)\s+(?:a\s+)?(?:new\s+)?task\s*[:\s](.+)/i', $msg, $cm)) {
        $isCreateTask = true;
        $preTitle = trim($cm[1]);
    }

    if ($isCreateTask) {
        if (!$canManageTasks) {
            $reply = "🔒 **Permission Denied**: Staff members are not permitted to create tasks directly. Please contact your manager or submit an emergency request.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        $fullTaskText = $preTitle ?: trim($msg);
        $targetEmployee = null;
        $extractedPriority = aiParsePriority($msg) ?: 'medium';
        $extractedDeadline = aiParseDeadline($msg);
        $extractedProject = null;

        // 1. Resolve employee
        if (!empty($preEmployee)) {
            $cleanEmp = preg_match('/^([a-zA-Z\s]+?)(?:,|$|\s+(?:and|assign|with|in|at|under|set|deadline|priority))/i', $preEmployee, $empClean) ? trim($empClean[1]) : $preEmployee;
            $empMatches = aiSearchEmployee($db, $cleanEmp, $user);
            if (!empty($empMatches)) {
                $targetEmployee = $empMatches[0];
            }
        }

        if (!$targetEmployee) {
            if (preg_match('/(?:for|to|assign(?:\s+it)?\s+to)\s+([a-zA-Z\s]+?)(?:\s+(?:with|and|in|at|under|deadline|priority)|$)/i', $msg, $em)) {
                $empMatches = aiSearchEmployee($db, trim($em[1]), $user);
                if (!empty($empMatches)) {
                    $targetEmployee = $empMatches[0];
                }
            }
        }

        // 2. Extract project
        if (preg_match('/(?:project\s+([a-zA-Z0-9\s]+)|(?:in|under|to)\s+(?:the\s+)?([a-zA-Z0-9\s]+?)\s+project)/i', $msg, $pm)) {
            $projNameQuery = trim(!empty($pm[1]) ? $pm[1] : $pm[2]);
            $extractedProject = aiSearchProject($db, $projNameQuery);
        }

        if (!$extractedProject) {
            $allProjects = $db->query("SELECT * FROM projects WHERE status = 'active'")->fetchAll() ?: [];
            foreach ($allProjects as $proj) {
                if (str_contains(strtolower($msg), strtolower($proj['name']))) {
                    $extractedProject = $proj;
                    break;
                }
            }
        }

        // 3. Clean up title
        $titleClean = $fullTaskText;
        $titleClean = preg_replace('/(?:for|to|assign(?:\s+it)?\s+to)\s+[a-zA-Z\s]+(?:\s+project|\s+deadline|\s+priority|$)/i', '', $titleClean);
        $titleClean = preg_replace('/(?:assign(?:\s+it)?\s+to\s+(?:the\s+)?[a-zA-Z0-9\s]+?\s+project)/i', '', $titleClean);
        $titleClean = preg_replace('/(?:set\s+(?:the\s+)?deadline\s+to\s+[a-zA-Z0-9\s]+)/i', '', $titleClean);
        $titleClean = preg_replace('/(?:mark\s+it\s+(?:as\s+)?[a-zA-Z]+\s+priority)/i', '', $titleClean);
        $titleClean = preg_replace('/(?:and\s+notify\s+(?:him|her|them))/i', '', $titleClean);
        $titleClean = trim(trim($titleClean, ':,.- '));
        $titleClean = ucfirst($titleClean);

        if (empty($titleClean)) {
            $titleClean = "New Task";
        }

        // If no employee specified, ask clarification
        if (!$targetEmployee) {
            $reply = "❓ Who should this new task **'{$titleClean}'** be assigned to? Please specify an employee name.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        // Validate hierarchy rules
        if (!isAssigneeAllowedForUser($user, (int)$targetEmployee['id'], $db)) {
            $reply = "🔒 **Permission Restriction**: As " . sanitize($user['role_title'] ?? $user['name']) . ", company policy does not authorize you to assign tasks to **{$targetEmployee['name']}**.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }

        // 4. Duplicate Task Check: Prevent creating duplicates if equivalent task exists
        $isForceCreate = (bool)preg_match('/(?:force|confirm|anyway|duplicate|separate)\s+(?:create|new)/i', $msg);
        if (!$isForceCreate) {
            $checkTitle = strtolower(trim($titleClean));
            $stmtDup = $db->prepare("
                SELECT t.id, t.title, t.status, t.priority, t.deadline, u.name as assignee_name
                FROM tasks t
                LEFT JOIN users u ON t.assigned_to = u.id
                WHERE LOWER(t.title) = ? AND t.status != 'completed'
                LIMIT 1
            ");
            $stmtDup->execute([$checkTitle]);
            $existingDup = $stmtDup->fetch();

            if ($existingDup) {
                $dlInfo = $existingDup['deadline'] ? formatISTDate($existingDup['deadline']) : 'No deadline';
                $reply = "⚠️ **Similar Task Already Exists in Workflow**\n\n" .
                         "I found an active task with the same title:\n" .
                         "• **Task #{$existingDup['id']}**: [{$existingDup['title']}](" . BASE_URL . "/task-details?id={$existingDup['id']})\n" .
                         "  - Currently assigned to: **" . ($existingDup['assignee_name'] ?: 'Unassigned') . "**\n" .
                         "  - Status: `" . ucfirst($existingDup['status']) . "` | Priority: `" . strtoupper($existingDup['priority']) . "`\n" .
                         "  - Deadline: {$dlInfo}\n\n" .
                         "Would you like to **update the existing task** or **create a separate new task**?\n" .
                         "• To update: *\"Assign task {$existingDup['id']} to {$targetEmployee['name']}\"*\n" .
                         "• To create new anyway: *\"Confirm create task: {$titleClean} for {$targetEmployee['name']}\"*";
                saveAiAssistantMessage($db, $userId, $conversationId, $reply);
                return ['type' => 'message', 'reply' => $reply];
            }
        }

        // Default deadline: 3 days ahead if not specified
        if (!$extractedDeadline) {
            $extractedDeadline = date('Y-m-d 18:30:00', strtotime('+3 days'));
        }

        $deptId = (int)($targetEmployee['department_id'] ?? 1);
        if ($extractedProject && !empty($extractedProject['department_id'])) {
            $deptId = (int)$extractedProject['department_id'];
        }

        $deadlineStr = formatISTDate($extractedDeadline) . " (IST)";
        $projectNameStr = $extractedProject ? sanitize($extractedProject['name']) : "Standalone Deliverable";

        $planData = [
            'action_type' => 'create_task',
            'title' => $titleClean,
            'assigned_to' => (int)$targetEmployee['id'],
            'employee_name' => $targetEmployee['name'],
            'employee_designation' => $targetEmployee['designation'],
            'project_id' => $extractedProject ? (int)$extractedProject['id'] : null,
            'project_name' => $projectNameStr,
            'department_id' => $deptId,
            'priority' => $extractedPriority,
            'deadline' => $extractedDeadline,
            'description' => "Created via AI Workflow Assistant on " . date('d M Y, h:i A') . "."
        ];

        $summary = "Create new task '{$titleClean}' and assign to {$targetEmployee['name']}.";
        $plannedSteps = [
            "1. Validate task creation payload and inputs.",
            "2. Confirm {$user['name']} has authorization to assign tasks to {$targetEmployee['name']}.",
            "3. Insert new task '{$titleClean}' into the tasks database.",
            $extractedProject ? "4. Link task to Project '{$projectNameStr}'." : "4. Set project as Standalone.",
            "5. Set priority to '" . ucfirst($extractedPriority) . "'.",
            "6. Set deadline to {$deadlineStr}.",
            "7. Save changes in the database.",
            "8. Send assignment notification to {$targetEmployee['name']}.",
            "9. Record task creation in the workspace audit activity logs."
        ];

        return createAiImplementationPlan($db, $userId, $conversationId, 'create_task', $planData, $summary, [
            'action_title' => 'Create & Assign New Task',
            'target_name' => sanitize($titleClean),
            'employee_name' => "{$targetEmployee['name']} (" . sanitize($targetEmployee['designation'] ?? 'Staff') . ")",
            'project_name' => $projectNameStr,
            'deadline_text' => $deadlineStr,
            'priority_text' => ucfirst($extractedPriority),
            'planned_steps' => $plannedSteps
        ]);
    }

    // --- CASE H: CREATE EMERGENCY TASK REQUEST ---
    if (preg_match('/(?:create|submit|make)\s+(?:an?\s+)?emergency\s*(?:task)?\s*request/i', $msg, $m)) {
        // Find task mentioned
        $matchedTasks = [];
        if (preg_match('/(?:for|on|about)\s+(?:task\s*#?\s*(\d+)|([^\'"]+?))(?:\s+(?:with|reason|because)|$)/i', $msg, $tm)) {
            $matchedTasks = aiSearchTask($db, trim(!empty($tm[1]) ? $tm[1] : $tm[2]), $user);
        }

        if (empty($matchedTasks)) {
            // Find user's own active tasks
            $stmt = $db->prepare("SELECT * FROM tasks WHERE assigned_to = ? AND status != 'completed' ORDER BY id DESC LIMIT 5");
            $stmt->execute([$userId]);
            $userTasks = $stmt->fetchAll() ?: [];

            if (empty($userTasks)) {
                $reply = "❓ Which task is this emergency request for? Please specify the task ID or title.";
                saveAiAssistantMessage($db, $userId, $conversationId, $reply);
                return ['type' => 'message', 'reply' => $reply];
            }
            $matchedTasks = [$userTasks[0]];
        }

        $task = $matchedTasks[0];
        $reason = "Emergency Escalation via AI Assistant";
        if (preg_match('/(?:reason|because|explaining|with)\s*[:=]?\s*[\'"]?([^\'"]+)[\'"]?$/i', $msg, $rm)) {
            $reason = trim($rm[1]);
        }

        $planData = [
            'action_type' => 'emergency_request',
            'task_id' => (int)$task['id'],
            'task_title' => $task['title'],
            'employee_id' => $userId,
            'reason' => $reason,
            'message' => "Urgent blocker or launch dependency requiring priority escalation."
        ];

        $summary = "Create Emergency Task Request for Task #{$task['id']} ('{$task['title']}').";
        $plannedSteps = [
            "1. Verify Task #{$task['id']} exists and is associated with employee.",
            "2. Generate Emergency Task Request with reason: \"{$reason}\".",
            "3. Save record in emergency_task_requests database table.",
            "4. Send priority alert notifications to Manager & System Administrator.",
            "5. Record emergency submission in audit log."
        ];

        return createAiImplementationPlan($db, $userId, $conversationId, 'emergency_request', $planData, $summary, [
            'action_title' => 'Submit Emergency Request',
            'target_name' => "Task #{$task['id']} &ndash; " . sanitize($task['title']),
            'reason_text' => $reason,
            'planned_steps' => $plannedSteps
        ]);
    }

    // --- CASE J: UPDATE TASK STATUS ---
    if (preg_match('/(?:mark|change|set|update)\s+(?:task\s*#?\s*(\d+)|([^\'"]+?))\s+(?:as|status\s+to)\s+(completed|in_progress|pending|cancelled)/i', $msg, $m) ||
        preg_match('/(?:complete|cancel)\s+(?:task\s*#?\s*(\d+)|([^\'"]+?))$/i', $msg, $m)) {
        
        $targetTaskIdOrTitle = !empty($m[1]) ? $m[1] : (!empty($m[2]) ? trim($m[2]) : '');
        $newStatus = !empty($m[3]) ? strtolower(trim($m[3])) : (str_contains(strtolower($msg), 'cancel') ? 'cancelled' : 'completed');
        
        $matchedTasks = aiSearchTask($db, $targetTaskIdOrTitle, $user);
        if (empty($matchedTasks)) {
            $reply = preg_match('/^\d+$/', $targetTaskIdOrTitle)
                ? "❌ Task **#{$targetTaskIdOrTitle}** was not found in the database. Please verify the task ID."
                : "❓ Could not identify which task you want to update status for. Please provide a valid task ID or title.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }
        $task = $matchedTasks[0];
        
        // Authorization check: User must be admin/manager OR the assigned employee
        if (!$canManageTasks && (int)$task['assigned_to'] !== $userId) {
            $reply = "🔒 **Permission Denied**: You can only update the status of tasks assigned to you.";
            saveAiAssistantMessage($db, $userId, $conversationId, $reply);
            return ['type' => 'message', 'reply' => $reply];
        }
        
        $planData = [
            'action_type' => 'update_status',
            'task_id' => (int)$task['id'],
            'task_title' => $task['title'],
            'old_status' => $task['status'],
            'new_status' => $newStatus,
            'assigned_to' => (int)$task['assigned_to'],
            'assigned_by' => (int)$task['assigned_by']
        ];
        
        $summary = "Change status of Task #{$task['id']} ('{$task['title']}') from " . ucfirst($task['status']) . " to " . ucfirst($newStatus) . ".";
        $plannedSteps = [
            "1. Verify Task #{$task['id']} exists in the database.",
            "2. Confirm user authorization to modify task status.",
            "3. Update task status to '" . ucfirst($newStatus) . "'.",
            $newStatus === 'completed' ? "4. Record completion timestamp in the database." : "4. Update task timestamp.",
            "5. Dispatch status change alert to assigner and team.",
            "6. Log status modification in audit activity log."
        ];
        
        return createAiImplementationPlan($db, $userId, $conversationId, 'update_status', $planData, $summary, [
            'action_title' => 'Update Task Status',
            'target_name' => "Task #{$task['id']} &ndash; " . sanitize($task['title']),
            'old_value' => ucfirst($task['status']),
            'new_value' => ucfirst($newStatus),
            'employee_name' => $task['assignee_name'] ?: 'Unassigned',
            'planned_steps' => $plannedSteps
        ]);
    }

    // =========================================================================
    // 3. FALLBACK GENERAL CONVERSATIONAL RESPONSE
    // =========================================================================
    $helpCommands = [
        "• **\"Assign the homepage development task to Abhay with a deadline of tomorrow.\"**",
        "• **\"Create a task for Abhay to fix mobile responsive layout.\"**",
        "• **\"Change task 25 priority to high.\"**",
        "• **\"Update the deadline of task 25 to 20 October 2026.\"**",
        "• **\"Show me all pending tasks assigned to Abhay.\"**",
        "• **\"Show all open task flags.\"**",
        "• **\"Show pending emergency requests.\"**",
        "• **\"Generate today's employee work summary.\"**"
    ];

    $reply = "🤖 **AI Workflow Assistant**\n\n" .
             "I am ready to assist you. Tell me what action you want to take, and I will prepare a structured **Implementation Plan** for your review before executing it.\n\n" .
             "**Try these commands**:\n" . implode("\n", $helpCommands);

    saveAiAssistantMessage($db, $userId, $conversationId, $reply);
    return ['type' => 'message', 'reply' => $reply];
}

/**
 * Helper to record assistant text message in chat history
 */
function saveAiAssistantMessage(PDO $db, int $userId, string $conversationId, string $message, ?string $planToken = null): void {
    try {
        $stmt = $db->prepare("INSERT INTO ai_chat_messages (user_id, conversation_id, sender, message, plan_token) VALUES (?, ?, 'assistant', ?, ?)");
        $stmt->execute([$userId, $conversationId, $message, $planToken]);
    } catch (Exception $e) {}
}

/**
 * Generate Structured Implementation Plan and Store in Database in 'awaiting_approval' status
 */
function createAiImplementationPlan(PDO $db, int $userId, string $conversationId, string $actionType, array $planData, string $summary, array $displayMetadata): array {
    $planToken = bin2hex(random_bytes(24));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+2 hours'));

    $stmt = $db->prepare("
        INSERT INTO ai_action_plans (user_id, plan_token, action_type, plan_data, summary, status, expires_at)
        VALUES (?, ?, ?, ?, ?, 'awaiting_approval', ?)
    ");
    $stmt->execute([
        $userId,
        $planToken,
        $actionType,
        json_encode($planData),
        $summary,
        $expiresAt
    ]);

    saveAiAssistantMessage($db, $userId, $conversationId, "I have prepared an Implementation Plan for your review. Please inspect and approve before execution.", $planToken);

    return [
        'type' => 'plan',
        'plan_token' => $planToken,
        'action_type' => $actionType,
        'summary' => $summary,
        'metadata' => $displayMetadata,
        'expires_at' => $expiresAt
    ];
}

/**
 * Plan Approval and Execution Engine
 * Validates authenticated user, checks idempotency & permissions, executes real changes,
 * sends notifications, logs audit trail, and returns execution result.
 */
function aiApproveAndExecutePlan(array $user, string $planToken, PDO $db): array {
    ensureAiAssistantSchema($db);
    $userId = (int)$user['id'];

    // 1. Fetch Plan
    $stmt = $db->prepare("SELECT * FROM ai_action_plans WHERE plan_token = ?");
    $stmt->execute([$planToken]);
    $plan = $stmt->fetch();

    if (!$plan) {
        return ['success' => false, 'message' => 'Action plan not found or has been removed.'];
    }

    // 2. Security Check: Only the plan's creator can approve it
    if ((int)$plan['user_id'] !== $userId) {
        return ['success' => false, 'message' => 'Security Error: You are not authorized to approve plans generated for another user.'];
    }

    // 3. Idempotency Check: Prevent double clicks and duplicate execution
    if ($plan['status'] === 'completed') {
        return [
            'success' => true,
            'already_completed' => true,
            'message' => 'This implementation plan has already been executed successfully.',
            'result' => json_decode($plan['result_data'] ?? '{}', true)
        ];
    }

    if ($plan['status'] === 'cancelled') {
        return ['success' => false, 'message' => 'This plan was previously cancelled and cannot be executed.'];
    }

    if ($plan['status'] === 'executing') {
        return ['success' => false, 'message' => 'This plan is currently executing. Please wait a moment.'];
    }

    // Check expiration
    if (!empty($plan['expires_at']) && strtotime($plan['expires_at']) < time()) {
        $db->prepare("UPDATE ai_action_plans SET status = 'expired' WHERE id = ?")->execute([$plan['id']]);
        return ['success' => false, 'message' => 'This plan has expired. Please submit your request again to generate a fresh plan.'];
    }

    // Mark as executing
    $db->prepare("UPDATE ai_action_plans SET status = 'executing', updated_at = NOW() WHERE id = ?")->execute([$plan['id']]);

    $data = json_decode($plan['plan_data'], true) ?: [];
    $actionType = $plan['action_type'];

    try {
        $result = [];

        // -------------------------------------------------------------
        // EXECUTE: ASSIGN EXISTING TASK
        // -------------------------------------------------------------
        if ($actionType === 'assign_task') {
            $taskId = (int)$data['task_id'];
            $empId = (int)$data['employee_id'];
            $newDeadline = $data['new_deadline'] ?? null;

            // Re-validate permission
            if (!isAssigneeAllowedForUser($user, $empId, $db)) {
                throw new Exception("Permission denied: You are not authorized to assign tasks to this employee.");
            }

            // Re-check task existence
            $tStmt = $db->prepare("SELECT id, title, project_id, priority, deadline FROM tasks WHERE id = ?");
            $tStmt->execute([$taskId]);
            $task = $tStmt->fetch();
            if (!$task) {
                throw new Exception("Task #{$taskId} no longer exists in the database.");
            }

            // Update Task in DB
            if ($newDeadline) {
                $upd = $db->prepare("UPDATE tasks SET assigned_to = ?, assigned_to_employee_id = ?, deadline = ?, updated_at = NOW() WHERE id = ?");
                $upd->execute([$empId, $empId, $newDeadline, $taskId]);
            } else {
                $upd = $db->prepare("UPDATE tasks SET assigned_to = ?, assigned_to_employee_id = ?, updated_at = NOW() WHERE id = ?");
                $upd->execute([$empId, $empId, $taskId]);
            }

            // Send notification
            $notifTitle = "New Task Assigned via AI Assistant";
            $notifMsg = "You were assigned task: '{$task['title']}' by {$user['name']}.";
            sendNotification($empId, $notifTitle, $notifMsg, "task-details?id={$taskId}", 'task');

            // Audit Log
            logActivity('AI Assign Task', 'Tasks', "Task #{$taskId} ('{$task['title']}') assigned to User #{$empId} ({$data['employee_name']}) by {$user['name']}");

            $result = [
                'action' => 'assign_task',
                'task_id' => $taskId,
                'task_title' => $task['title'],
                'employee_name' => $data['employee_name'],
                'deadline' => $newDeadline ? formatISTDate($newDeadline) : ($task['deadline'] ? formatISTDate($task['deadline']) : 'Unchanged'),
                'priority' => ucfirst($task['priority']),
                'notification' => 'Sent successfully'
            ];
        }

        // -------------------------------------------------------------
        // EXECUTE: CREATE NEW TASK
        // -------------------------------------------------------------
        elseif ($actionType === 'create_task') {
            if (!canCreateOrEditTasks($user)) {
                throw new Exception("Permission denied: Only managers and authorized leads can create tasks.");
            }

            $title = sanitize($data['title']);
            $assignedTo = (int)$data['assigned_to'];
            $projectId = !empty($data['project_id']) ? (int)$data['project_id'] : null;
            $deptId = (int)($data['department_id'] ?? 1);
            $priority = sanitize($data['priority'] ?? 'medium');
            $deadline = !empty($data['deadline']) ? $data['deadline'] : date('Y-m-d H:i:s', strtotime('+3 days'));
            $desc = sanitize($data['description'] ?? '');

            if (!isAssigneeAllowedForUser($user, $assignedTo, $db)) {
                throw new Exception("Permission denied: You are not authorized to assign tasks to this employee.");
            }

            $ins = $db->prepare("
                INSERT INTO tasks (project_id, department_id, assigned_to_employee_id, assigned_to, assigned_by, title, description, priority, deadline, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
            ");
            $ins->execute([$projectId, $deptId, $assignedTo, $assignedTo, $userId, $title, $desc, $priority, $deadline]);

            $newTaskId = (int)$db->lastInsertId();
            if (!$newTaskId) {
                $newTaskId = (int)$db->query("SELECT MAX(id) FROM tasks")->fetchColumn();
            }

            // Notification
            sendNotification($assignedTo, 'New Task Created via AI Assistant', "Task '{$title}' was created and assigned to you by {$user['name']}.", "task-details?id={$newTaskId}", 'task');

            // Audit Log
            logActivity('AI Create Task', 'Tasks', "Task #{$newTaskId} ('{$title}') created by {$user['name']} and assigned to User #{$assignedTo} ({$data['employee_name']})");

            $result = [
                'action' => 'create_task',
                'task_id' => $newTaskId,
                'task_title' => $title,
                'employee_name' => $data['employee_name'],
                'project_name' => $data['project_name'],
                'deadline' => formatISTDate($deadline),
                'priority' => ucfirst($priority),
                'notification' => 'Sent successfully'
            ];
        }

        // -------------------------------------------------------------
        // EXECUTE: UPDATE TASK DEADLINE
        // -------------------------------------------------------------
        elseif ($actionType === 'update_deadline') {
            $taskId = (int)$data['task_id'];
            $newDeadline = $data['new_deadline'];
            $assigneeId = (int)($data['assigned_to'] ?? 0);

            $stmt = $db->prepare("UPDATE tasks SET deadline = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$newDeadline, $taskId]);

            if ($assigneeId > 0) {
                sendNotification($assigneeId, 'Task Deadline Updated', "The deadline for task '{$data['task_title']}' was updated to " . formatISTDate($newDeadline) . " by {$user['name']}.", "task-details?id={$taskId}", 'task');
            }

            logActivity('AI Update Deadline', 'Tasks', "Task #{$taskId} deadline updated to {$newDeadline} by {$user['name']}");

            $result = [
                'action' => 'update_deadline',
                'task_id' => $taskId,
                'task_title' => $data['task_title'],
                'deadline' => formatISTDate($newDeadline),
                'notification' => 'Sent successfully'
            ];
        }

        // -------------------------------------------------------------
        // EXECUTE: UPDATE TASK PRIORITY
        // -------------------------------------------------------------
        elseif ($actionType === 'update_priority') {
            $taskId = (int)$data['task_id'];
            $newPriority = $data['new_priority'];
            $assigneeId = (int)($data['assigned_to'] ?? 0);

            $stmt = $db->prepare("UPDATE tasks SET priority = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$newPriority, $taskId]);

            if ($assigneeId > 0) {
                sendNotification($assigneeId, 'Task Priority Updated', "Priority for task '{$data['task_title']}' was changed to " . ucfirst($newPriority) . " by {$user['name']}.", "task-details?id={$taskId}", 'task');
            }

            logActivity('AI Update Priority', 'Tasks', "Task #{$taskId} priority updated to {$newPriority} by {$user['name']}");

            $result = [
                'action' => 'update_priority',
                'task_id' => $taskId,
                'task_title' => $data['task_title'],
                'priority' => ucfirst($newPriority),
                'notification' => 'Sent successfully'
            ];
        }

        // -------------------------------------------------------------
        // EXECUTE: RESOLVE TASK FLAG
        // -------------------------------------------------------------
        elseif ($actionType === 'resolve_flag') {
            $flagId = (int)$data['flag_id'];
            $resMsg = sanitize($data['resolution_message']);

            $stmt = $db->prepare("
                UPDATE task_flags 
                SET status = 'resolved', 
                    resolution_message = ?, 
                    resolved_by = ?, 
                    resolved_at = NOW(), 
                    updated_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$resMsg, $userId, $flagId]);

            // Add resolution comment in discussion
            $cStmt = $db->prepare("INSERT INTO task_flag_comments (flag_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())");
            $cStmt->execute([$flagId, $userId, "Flag marked as resolved: {$resMsg}"]);

            if (!empty($data['employee_id'])) {
                sendNotification((int)$data['employee_id'], 'Task Flag Resolved', "Flag #{$flagId} on '{$data['task_title']}' was marked as resolved by {$user['name']}.", "flagged-tasks", 'task');
            }

            logActivity('AI Resolve Flag', 'Tasks', "Task Flag #{$flagId} resolved by {$user['name']}");

            $result = [
                'action' => 'resolve_flag',
                'flag_id' => $flagId,
                'task_title' => $data['task_title'],
                'resolution' => $resMsg,
                'status' => 'Resolved'
            ];
        }

        // -------------------------------------------------------------
        // EXECUTE: ADD COMMENT TO TASK FLAG
        // -------------------------------------------------------------
        elseif ($actionType === 'add_flag_comment') {
            $flagId = (int)$data['flag_id'];
            $comment = sanitize($data['comment']);

            $cStmt = $db->prepare("INSERT INTO task_flag_comments (flag_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())");
            $cStmt->execute([$flagId, $userId, $comment]);

            logActivity('AI Flag Comment', 'Tasks', "Added response to Task Flag #{$flagId} by {$user['name']}");

            $result = [
                'action' => 'add_flag_comment',
                'flag_id' => $flagId,
                'comment' => $comment,
                'status' => 'Comment Added'
            ];
        }

        // -------------------------------------------------------------
        // EXECUTE: APPROVE EMERGENCY REQUEST
        // -------------------------------------------------------------
        elseif ($actionType === 'approve_emergency') {
            if (!canCreateOrEditTasks($user)) {
                throw new Exception("Permission denied: Only managers or administrators can approve emergency requests.");
            }

            $emgId = (int)$data['emergency_id'];
            $taskId = (int)$data['task_id'];
            $empId = (int)$data['employee_id'];

            $upd = $db->prepare("UPDATE emergency_task_requests SET status = 'approved', reviewer_id = ?, reviewed_at = NOW(), updated_at = NOW() WHERE id = ?");
            $upd->execute([$userId, $emgId]);

            // Elevate task to urgent
            $db->prepare("UPDATE tasks SET priority = 'urgent', updated_at = NOW() WHERE id = ?")->execute([$taskId]);

            sendNotification($empId, 'Emergency Request Approved', "Your emergency request #{$emgId} was approved by {$user['name']} and marked Urgent.", "emergency-approvals", 'emergency');
            logActivity('AI Approve Emergency', 'Emergency', "Emergency Request #{$emgId} approved by {$user['name']}");

            $result = [
                'action' => 'approve_emergency',
                'emergency_id' => $emgId,
                'task_id' => $taskId,
                'status' => 'Approved',
                'task_priority' => 'URGENT'
            ];
        }

        // -------------------------------------------------------------
        // EXECUTE: CREATE EMERGENCY REQUEST
        // -------------------------------------------------------------
        elseif ($actionType === 'emergency_request') {
            $taskId = (int)$data['task_id'];
            $reason = sanitize($data['reason']);
            $msg = sanitize($data['message']);

            $ins = $db->prepare("INSERT INTO emergency_task_requests (task_id, employee_id, reason, message, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'pending', NOW(), NOW())");
            $ins->execute([$taskId, $userId, $reason, $msg]);

            $emgId = (int)$db->lastInsertId();

            // Notify all admins/managers
            $admins = $db->query("SELECT id FROM users WHERE role = 'ADMIN' OR role_id IN (1, 2)")->fetchAll() ?: [];
            foreach ($admins as $adm) {
                sendNotification((int)$adm['id'], 'Emergency Task Escalation', "Employee {$user['name']} submitted Emergency Request #{$emgId} on task '{$data['task_title']}'.", "emergency-approvals", 'emergency');
            }

            logActivity('AI Submit Emergency', 'Emergency', "Emergency Request #{$emgId} submitted on Task #{$taskId} by {$user['name']}");

            $result = [
                'action' => 'emergency_request',
                'emergency_id' => $emgId,
                'task_title' => $data['task_title'],
                'reason' => $reason,
                'status' => 'Submitted & Pending Review'
            ];
        }

        // -------------------------------------------------------------
        // EXECUTE: UPDATE TASK STATUS
        // -------------------------------------------------------------
        elseif ($actionType === 'update_status') {
            $taskId = (int)$data['task_id'];
            $newStatus = sanitize($data['new_status']);
            $completedClause = ($newStatus === 'completed') ? ", completed_at = NOW()" : "";
            
            $stmt = $db->prepare("UPDATE tasks SET status = ?, updated_at = NOW(){$completedClause} WHERE id = ?");
            $stmt->execute([$newStatus, $taskId]);
            
            // Notify assigner or assignee
            $targetUserToNotify = ((int)$data['assigned_to'] === $userId) ? (int)($data['assigned_by'] ?? 1) : (int)$data['assigned_to'];
            if ($targetUserToNotify > 0) {
                sendNotification($targetUserToNotify, 'Task Status Updated', "Status for task '{$data['task_title']}' was updated to " . ucfirst($newStatus) . " by {$user['name']}.", "task-details?id={$taskId}", 'task');
            }
            
            logActivity('AI Update Status', 'Tasks', "Task #{$taskId} status updated to {$newStatus} by {$user['name']}");
            
            $result = [
                'action' => 'update_status',
                'task_id' => $taskId,
                'task_title' => $data['task_title'],
                'status' => ucfirst($newStatus),
                'notification' => 'Sent successfully'
            ];
        } else {
            throw new Exception("Unsupported action type: {$actionType}");
        }

        // Mark plan as completed
        $resultJson = json_encode($result);
        $db->prepare("
            UPDATE ai_action_plans 
            SET status = 'completed', 
                executed_by = ?, 
                result_message = 'Executed successfully', 
                result_data = ?, 
                updated_at = NOW() 
            WHERE id = ?
        ")->execute([$userId, $resultJson, $plan['id']]);

        return [
            'success' => true,
            'message' => 'Action executed successfully and saved to database.',
            'result' => $result
        ];

    } catch (Throwable $e) {
        // Mark plan as failed
        $db->prepare("
            UPDATE ai_action_plans 
            SET status = 'failed', 
                result_message = ?, 
                updated_at = NOW() 
            WHERE id = ?
        ")->execute([$e->getMessage(), $plan['id']]);

        return [
            'success' => false,
            'message' => 'Execution failed: ' . $e->getMessage()
        ];
    }
}

/**
 * Plan Cancellation
 */
function aiCancelPlan(array $user, string $planToken, PDO $db): array {
    ensureAiAssistantSchema($db);
    $userId = (int)$user['id'];

    $stmt = $db->prepare("SELECT * FROM ai_action_plans WHERE plan_token = ?");
    $stmt->execute([$planToken]);
    $plan = $stmt->fetch();

    if (!$plan) {
        return ['success' => false, 'message' => 'Plan not found.'];
    }

    if ((int)$plan['user_id'] !== $userId) {
        return ['success' => false, 'message' => 'Unauthorized cancellation.'];
    }

    if ($plan['status'] === 'completed') {
        return ['success' => false, 'message' => 'Cannot cancel an action that has already completed.'];
    }

    $db->prepare("UPDATE ai_action_plans SET status = 'cancelled', updated_at = NOW() WHERE id = ?")->execute([$plan['id']]);

    return ['success' => true, 'message' => 'Implementation plan was cancelled. No changes were made to the database.'];
}
