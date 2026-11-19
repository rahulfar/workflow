<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$query = sanitize($_GET['q'] ?? '');

if (strlen($query) < 2) {
    jsonResponse(true, 'Query too short', []);
}

$results = [];
try {
    $db = Database::getConnection();
    $like = "%{$query}%";

    // 1. Tasks Search
    $tStmt = $db->prepare("SELECT id, title, status FROM tasks WHERE title LIKE ? OR description LIKE ? LIMIT 5");
    $tStmt->execute([$like, $like]);
    while ($t = $tStmt->fetch()) {
        $results[] = [
            'title' => $t['title'],
            'subtitle' => 'Task • ' . ucfirst($t['status']),
            'type' => 'Task',
            'icon' => 'fas fa-tasks',
            'link' => BASE_URL . '/tasks?id=' . $t['id']
        ];
    }

    // 2. Projects Search
    $pStmt = $db->prepare("SELECT id, name, code FROM projects WHERE name LIKE ? OR code LIKE ? LIMIT 5");
    $pStmt->execute([$like, $like]);
    while ($p = $pStmt->fetch()) {
        $results[] = [
            'title' => $p['name'],
            'subtitle' => 'Project • ' . $p['code'],
            'type' => 'Project',
            'icon' => 'fas fa-project-diagram',
            'link' => BASE_URL . '/projects?id=' . $p['id']
        ];
    }

    // 3. Employees Search
    $eStmt = $db->prepare("SELECT id, name, designation FROM users WHERE name LIKE ? OR email LIKE ? LIMIT 5");
    $eStmt->execute([$like, $like]);
    while ($e = $eStmt->fetch()) {
        $results[] = [
            'title' => $e['name'],
            'subtitle' => $e['designation'],
            'type' => 'Employee',
            'icon' => 'fas fa-user-circle',
            'link' => BASE_URL . '/profile?id=' . $e['id']
        ];
    }

    jsonResponse(true, 'Results found', $results);
} catch (Exception $e) {
    jsonResponse(false, 'Search Error: ' . $e->getMessage());
}
