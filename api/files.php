<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireLogin();
$action = $_POST['action'] ?? ($_GET['action'] ?? '');

switch ($action) {
    case 'upload':
        handleFileUpload($user);
        break;
    case 'download':
        handleFileDownload($user);
        break;
    case 'delete':
        handleFileDelete($user);
        break;
    default:
        jsonResponse(false, 'Invalid file action', [], 400);
}

function handleFileUpload(array $user): void {
    if (empty($_FILES['file'])) {
        jsonResponse(false, 'No file uploaded.');
    }

    $file = $_FILES['file'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        jsonResponse(false, 'File upload error code: ' . $file['error']);
    }

    $uploadDir = __DIR__ . '/../public/uploads/files/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
    $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', basename($file['name']));
    $targetPath = $uploadDir . $safeName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO task_files (task_id, project_id, user_id, file_name, file_path, file_size, file_type)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $taskId = !empty($_POST['task_id']) ? (int)$_POST['task_id'] : null;
            $projectId = !empty($_POST['project_id']) ? (int)$_POST['project_id'] : null;

            $stmt->execute([$taskId, $projectId, $user['id'], $file['name'], $safeName, $file['size'], $file['type']]);
            $fileId = $db->lastInsertId();

            logActivity('Upload File', 'Files', "Uploaded file: " . $file['name'], (int)$user['id']);

            jsonResponse(true, 'File uploaded successfully!', ['file_id' => $fileId]);
        } catch (Exception $e) {
            jsonResponse(false, 'DB Error: ' . $e->getMessage());
        }
    } else {
        jsonResponse(false, 'Failed to save uploaded file on server.');
    }
}

function handleFileDownload(array $user): void {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) {
        http_response_code(400);
        die('Missing file ID');
    }

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM task_files WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetch();

        if (!$file) {
            http_response_code(404);
            die('File record not found');
        }

        $candidates = [
            __DIR__ . '/../public/uploads/files/' . $file['file_path'],
            __DIR__ . '/../uploads/' . $file['file_path'],
            __DIR__ . '/../public/uploads/' . $file['file_path']
        ];

        $filePath = null;
        foreach ($candidates as $cand) {
            if (file_exists($cand)) {
                $filePath = $cand;
                break;
            }
        }

        if (!$filePath) {
            http_response_code(404);
            die('File physical asset not found on server.');
        }

        logActivity('Download File', 'Files', "Downloaded file: " . $file['file_name'], (int)$user['id']);

        $mimeType = $file['file_type'] ?: mime_content_type($filePath) ?: 'application/octet-stream';
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . basename($file['file_name']) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        die('File download error: ' . $e->getMessage());
    }
}

function handleFileDelete(array $user): void {
    $id = (int)($_POST['id'] ?? 0);
    if (!$id) {
        jsonResponse(false, 'File ID required.');
    }

    $isAdmin = (strtolower($user['role_name'] ?? '') === 'admin' || (int)($user['role_id'] ?? 0) === 1);

    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM task_files WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetch();

        if (!$file) {
            jsonResponse(false, 'File not found.');
        }

        if (!$isAdmin && (int)$file['user_id'] !== (int)$user['id']) {
            jsonResponse(false, 'Permission denied to delete this file.', [], 403);
        }

        $delStmt = $db->prepare("DELETE FROM task_files WHERE id = ?");
        $delStmt->execute([$id]);

        $candidates = [
            __DIR__ . '/../public/uploads/files/' . $file['file_path'],
            __DIR__ . '/../uploads/' . $file['file_path']
        ];
        foreach ($candidates as $cand) {
            if (file_exists($cand)) {
                @unlink($cand);
            }
        }

        logActivity('Delete File', 'Files', "Deleted file: " . $file['file_name'], (int)$user['id']);
        jsonResponse(true, 'File deleted successfully.');
    } catch (Exception $e) {
        jsonResponse(false, 'Error deleting file: ' . $e->getMessage());
    }
}
