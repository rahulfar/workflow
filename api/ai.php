<?php
/**
 * AI Workflow Assistant API Handler
 * Handles natural-language command processing, implementation plan generation,
 * approval execution, plan cancellation, conversation history, and role-based suggestions.
 */

require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';
require_once __DIR__ . '/../php_app/helpers/ai_assistant.php';

header('Content-Type: application/json');

$user = getCurrentUser();
if (!$user) {
    jsonResponse(false, 'Unauthorized. Please login to use AI Workflow Assistant.', [], 401);
}

$db = Database::getConnection();
ensureAiAssistantSchema($db);

$action = $_POST['action'] ?? ($_GET['action'] ?? 'chat');

switch ($action) {
    case 'chat':
    case 'query':
        handleChatCommand($user, $db);
        break;

    case 'approve_plan':
    case 'execute_plan':
        handleApprovePlan($user, $db);
        break;

    case 'cancel_plan':
        handleCancelPlan($user, $db);
        break;

    case 'get_history':
        handleGetHistory($user, $db);
        break;

    case 'clear_history':
        handleClearHistory($user, $db);
        break;

    case 'get_suggestions':
        handleGetSuggestions($user, $db);
        break;

    case 'get_status':
        jsonResponse(true, 'AI Provider Status', aiGetConfiguredProvider());
        break;

    default:
        jsonResponse(false, "Unknown AI action: {$action}", [], 400);
}

/**
 * Handle incoming natural-language message
 */
function handleChatCommand(array $user, PDO $db): void {
    $message = trim($_POST['message'] ?? ($_POST['query'] ?? ''));
    $conversationId = trim($_POST['conversation_id'] ?? ('conv_' . session_id()));

    if (empty($message)) {
        jsonResponse(false, 'Message cannot be empty.');
    }

    try {
        $result = aiProcessUserCommand($user, $message, $conversationId, $db);
        $result['provider_info'] = aiGetConfiguredProvider();
        jsonResponse(true, 'Command processed', $result);
    } catch (Throwable $e) {
        error_log("AI Chat Error: " . $e->getMessage());
        jsonResponse(false, 'AI processing error: ' . $e->getMessage());
    }
}

/**
 * Handle user clicking "Approve & Execute"
 */
function handleApprovePlan(array $user, PDO $db): void {
    $planToken = trim($_POST['plan_token'] ?? '');

    if (empty($planToken)) {
        jsonResponse(false, 'Plan token is required.');
    }

    // Verify CSRF token
    $csrfToken = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    if (!verifyCsrfToken($csrfToken)) {
        jsonResponse(false, 'Security error: Invalid CSRF token.', [], 403);
    }

    try {
        $execResult = aiApproveAndExecutePlan($user, $planToken, $db);
        if ($execResult['success']) {
            jsonResponse(true, $execResult['message'] ?? 'Plan executed successfully', $execResult['result'] ?? []);
        } else {
            jsonResponse(false, $execResult['message'] ?? 'Execution failed');
        }
    } catch (Throwable $e) {
        error_log("AI Execution Error: " . $e->getMessage());
        jsonResponse(false, 'Execution failed: ' . $e->getMessage());
    }
}

/**
 * Handle user clicking "Cancel"
 */
function handleCancelPlan(array $user, PDO $db): void {
    $planToken = trim($_POST['plan_token'] ?? '');

    if (empty($planToken)) {
        jsonResponse(false, 'Plan token is required.');
    }

    try {
        $cancelResult = aiCancelPlan($user, $planToken, $db);
        jsonResponse($cancelResult['success'], $cancelResult['message']);
    } catch (Throwable $e) {
        jsonResponse(false, 'Cancellation error: ' . $e->getMessage());
    }
}

/**
 * Retrieve recent chat history
 */
function handleGetHistory(array $user, PDO $db): void {
    $userId = (int)$user['id'];
    $conversationId = trim($_GET['conversation_id'] ?? ('conv_' . session_id()));

    try {
        $stmt = $db->prepare("
            SELECT m.*, p.action_type, p.plan_data, p.summary, p.status as plan_status, p.result_data
            FROM ai_chat_messages m
            LEFT JOIN ai_action_plans p ON m.plan_token = p.plan_token
            WHERE m.user_id = ? AND m.conversation_id = ?
            ORDER BY m.id ASC
            LIMIT 50
        ");
        $stmt->execute([$userId, $conversationId]);
        $rows = $stmt->fetchAll() ?: [];

        jsonResponse(true, 'History loaded', ['messages' => $rows]);
    } catch (Throwable $e) {
        jsonResponse(false, 'Error loading history: ' . $e->getMessage());
    }
}

/**
 * Clear chat history for user
 */
function handleClearHistory(array $user, PDO $db): void {
    $userId = (int)$user['id'];
    $conversationId = trim($_POST['conversation_id'] ?? ('conv_' . session_id()));

    try {
        $stmt = $db->prepare("DELETE FROM ai_chat_messages WHERE user_id = ? AND conversation_id = ?");
        $stmt->execute([$userId, $conversationId]);
        jsonResponse(true, 'Chat history cleared successfully.');
    } catch (Throwable $e) {
        jsonResponse(false, 'Error clearing history: ' . $e->getMessage());
    }
}

/**
 * Get Role-tailored Command Suggestions
 */
function handleGetSuggestions(array $user, PDO $db): void {
    $category = getUserRoleCategory($user);
    $userId = (int)$user['id'];
    $userName = strtolower(trim($user['name'] ?? ''));

    $suggestions = [];

    if (in_array($category, ['admin', 'manager', 'product_manager'])) {
        $suggestions = [
            [
                'text' => 'Assign homepage development task to Abhay with deadline tomorrow',
                'label' => 'Assign Task',
                'icon' => 'fa-user-check'
            ],
            [
                'text' => 'Create a task for Abhay to fix mobile responsive layout',
                'label' => 'Create Task',
                'icon' => 'fa-plus-circle'
            ],
            [
                'text' => 'Change task 25 priority to high',
                'label' => 'Change Priority',
                'icon' => 'fa-exclamation-triangle'
            ],
            [
                'text' => 'Show me all pending tasks assigned to Abhay',
                'label' => 'Review Tasks',
                'icon' => 'fa-tasks'
            ],
            [
                'text' => 'Show all tasks currently flagged because of missing requirements',
                'label' => 'Review Flagged Tasks',
                'icon' => 'fa-flag'
            ],
            [
                'text' => 'Show pending emergency requests',
                'label' => 'Emergency Requests',
                'icon' => 'fa-bolt'
            ],
            [
                'text' => 'Generate today\'s employee work summary',
                'label' => 'Work Summary',
                'icon' => 'fa-chart-line'
            ]
        ];
    } elseif ($userId === 5 || $userName === 'uvaish') {
        // SEO Lead Manager
        $suggestions = [
            [
                'text' => 'Assign keyword research task to Aman with deadline tomorrow',
                'label' => 'Assign SEO Task',
                'icon' => 'fa-bullhorn'
            ],
            [
                'text' => 'Show me all pending tasks assigned to Aman',
                'label' => 'Aman Tasks',
                'icon' => 'fa-tasks'
            ],
            [
                'text' => 'Show me all pending tasks assigned to Shivanshu',
                'label' => 'Shivanshu Tasks',
                'icon' => 'fa-tasks'
            ],
            [
                'text' => 'Show all open task flags',
                'label' => 'Check Flags',
                'icon' => 'fa-flag'
            ],
            [
                'text' => 'Generate today\'s employee work summary',
                'label' => 'Work Summary',
                'icon' => 'fa-chart-line'
            ]
        ];
    } elseif ($userId === 4 || $userName === 'rahul') {
        // Dev Lead
        $suggestions = [
            [
                'text' => 'Assign homepage banner task to Abhay with deadline Friday',
                'label' => 'Assign Dev Task',
                'icon' => 'fa-code'
            ],
            [
                'text' => 'Show me all pending tasks assigned to Abhay',
                'label' => 'Abhay Tasks',
                'icon' => 'fa-tasks'
            ],
            [
                'text' => 'Show all open task flags',
                'label' => 'Review Flags',
                'icon' => 'fa-flag'
            ],
            [
                'text' => 'List tasks overdue this week',
                'label' => 'Overdue Tasks',
                'icon' => 'fa-clock'
            ]
        ];
    } else {
        // General Employee / Intern
        $suggestions = [
            [
                'text' => 'Show me all pending tasks assigned to me',
                'label' => 'My Pending Tasks',
                'icon' => 'fa-tasks'
            ],
            [
                'text' => 'Create an emergency task request for the client website launch',
                'label' => 'Emergency Request',
                'icon' => 'fa-bolt'
            ],
            [
                'text' => 'Show all tasks currently flagged because of missing requirements',
                'label' => 'Check Flags',
                'icon' => 'fa-flag'
            ],
            [
                'text' => 'Show my current shift hours and attendance',
                'label' => 'My Hours',
                'icon' => 'fa-stopwatch'
            ]
        ];
    }

    jsonResponse(true, 'Suggestions loaded', ['suggestions' => $suggestions]);
}
