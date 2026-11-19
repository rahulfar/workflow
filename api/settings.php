<?php
require_once __DIR__ . '/../php_app/config/app.php';
require_once __DIR__ . '/../php_app/helpers/functions.php';

$user = requireAdmin();
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($action) {
    case 'save':
        handleSaveSettings($user);
        break;
    case 'backup':
        handleDatabaseBackup($user);
        break;
    case 'optimize':
        handleDatabaseOptimize($user);
        break;
    default:
        jsonResponse(false, 'Invalid settings action', [], 400);
}

function handleSaveSettings(array $user): void {
    $companyName = sanitize($_POST['company_name'] ?? 'WorkFlow Pro');
    $companyEmail = sanitize($_POST['company_email'] ?? 'admin@company.com');
    $officeStart = sanitize($_POST['office_start_time'] ?? '09:40');
    $officeEnd = sanitize($_POST['office_end_time'] ?? '18:30');
    $lunchStart = sanitize($_POST['lunch_start_time'] ?? '14:00');
    $lunchEnd = sanitize($_POST['lunch_end_time'] ?? '15:00');

    $settingsToUpdate = [
        'company_name' => $companyName,
        'company_email' => $companyEmail,
        'office_start_time' => $officeStart,
        'office_end_time' => $officeEnd,
        'lunch_start_time' => $lunchStart,
        'lunch_end_time' => $lunchEnd
    ];

    try {
        $db = Database::getConnection();

        // Ensure settings table exists
        $db->exec("CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT NULL,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $checkStmt = $db->prepare("SELECT setting_key FROM settings WHERE setting_key = ?");
        $updateStmt = $db->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = ?");
        $insertStmt = $db->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");

        foreach ($settingsToUpdate as $key => $val) {
            $checkStmt->execute([$key]);
            if ($checkStmt->fetch()) {
                $updateStmt->execute([$val, $key]);
            } else {
                $insertStmt->execute([$key, $val]);
            }
        }

        // Also update default shift_settings if existing
        try {
            $shiftStmt = $db->prepare("
                UPDATE shift_settings 
                SET start_time = ?, end_time = ?, lunch_start = ?, lunch_end = ? 
                WHERE id = 1
            ");
            $shiftStmt->execute([
                $officeStart,
                $officeEnd,
                $lunchStart,
                $lunchEnd
            ]);
        } catch (Exception $e) {
            // Silently skip if shift_settings table has different columns
        }

        logActivity('Update Settings', 'Settings', 'Admin updated system settings and company configuration', (int)$user['id']);
        jsonResponse(true, 'System settings saved successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Failed to save settings: ' . $e->getMessage());
    }
}

function handleDatabaseBackup(array $user): void {
    try {
        $db = Database::getConnection();
        $driver = Database::getDriver();
        $dump = "-- WorkFlow Pro Database Backup\n";
        $dump .= "-- Generated: " . date('Y-m-d H:i:s') . " IST\n";
        $dump .= "-- Admin: " . $user['name'] . " (ID #" . $user['id'] . ")\n\n";

        if ($driver === 'sqlite') {
            $tablesStmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                $schemaStmt = $db->prepare("SELECT sql FROM sqlite_master WHERE type='table' AND name = ?");
                $schemaStmt->execute([$table]);
                $createSql = $schemaStmt->fetchColumn();

                $dump .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $dump .= $createSql . ";\n\n";

                $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $keys = array_map(function($k) { return "`$k`"; }, array_keys($row));
                    $vals = array_map(function($v) use ($db) {
                        return $v === null ? 'NULL' : $db->quote((string)$v);
                    }, array_values($row));
                    $dump .= "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $vals) . ");\n";
                }
                $dump .= "\n";
            }
        } else {
            $tables = $db->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'")->fetchAll(PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                $dump .= "DROP TABLE IF EXISTS `{$table}`;\n";
                $createTable = $db->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_NUM);
                $dump .= $createTable[1] . ";\n\n";

                $rows = $db->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $keys = array_map(function($k) { return "`$k`"; }, array_keys($row));
                    $vals = array_map(function($v) use ($db) {
                        return $v === null ? 'NULL' : $db->quote((string)$v);
                    }, array_values($row));
                    $dump .= "INSERT INTO `{$table}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $vals) . ");\n";
                }
                $dump .= "\n";
            }
        }

        logActivity('Database Backup', 'Settings', 'Admin downloaded SQL database backup', (int)$user['id']);

        header('Content-Type: application/sql; charset=utf-8');
        header('Content-Disposition: attachment; filename="workflow_backup_' . date('Y-m-d_His') . '.sql"');
        header('Content-Length: ' . strlen($dump));
        echo $dump;
        exit;
    } catch (Exception $e) {
        header('Content-Type: text/plain');
        http_response_code(500);
        echo "Database backup failed: " . $e->getMessage();
        exit;
    }
}

function handleDatabaseOptimize(array $user): void {
    try {
        $db = Database::getConnection();
        $driver = Database::getDriver();

        if ($driver === 'sqlite') {
            $db->exec("VACUUM");
        } else {
            $tables = $db->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'")->fetchAll(PDO::FETCH_COLUMN);
            foreach ($tables as $table) {
                $db->exec("OPTIMIZE TABLE `{$table}`");
            }
        }

        logActivity('Database Optimize', 'Settings', 'Admin triggered database optimization', (int)$user['id']);
        jsonResponse(true, 'Database tables and indexes optimized successfully!');
    } catch (Exception $e) {
        jsonResponse(false, 'Optimization failed: ' . $e->getMessage());
    }
}
