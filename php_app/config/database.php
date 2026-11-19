<?php
/**
 * Database Configuration & Connection Handler
 * Supports PDO MySQL (`u254498372_Nd1BV`) & automatic SQLite fallback for zero-config demo mode
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'u254498372_kraft_workflow');
define('DB_USER', 'u254498372_kraft_workflow');
define('DB_PASS', '?xC2cQC5;i');
define('DB_CHARSET', 'utf8mb4');

class Database {
    private static $instance = null;
    private static $driver = 'mysql';

    public static function getConnection() {
        if (self::$instance === null) {
            try {
                if (!extension_loaded('pdo_mysql')) {
                    throw new PDOException("pdo_mysql extension not loaded");
                }
                // Attempt MySQL Connection as specified
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => true,
                    PDO::ATTR_TIMEOUT            => 2
                ];
                if (defined('PDO::MYSQL_ATTR_INIT_COMMAND')) {
                    $options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8mb4";
                }
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);
                try {
                    self::$instance->exec("SET time_zone = '+05:30'");
                } catch (Exception $tzEx) {}
                self::$driver = 'mysql';
                self::ensureChatTableExists(self::$instance);
                self::ensureAuthorizedIpTableExists(self::$instance);
                self::ensureAttendanceColumnsExist(self::$instance);
            } catch (PDOException $e) {
                // Automatic Fallback to SQLite if local MySQL is offline or refused
                try {
                    $sqlitePath = dirname(dirname(__DIR__)) . '/database/work_management.sqlite';
                    $sqliteDir = dirname($sqlitePath);
                    if (!is_dir($sqliteDir)) {
                        @mkdir($sqliteDir, 0777, true);
                    }

                    self::$instance = new PDO("sqlite:" . $sqlitePath);
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    self::$driver = 'sqlite';
                    try {
                        self::$instance->exec("PRAGMA busy_timeout = 5000;");
                        self::$instance->exec("PRAGMA journal_mode = WAL;");
                    } catch (Throwable $pragmaEx) {}

                    // Register MySQL polyfill functions for SQLite compatibility
                    @self::$instance->sqliteCreateFunction('NOW', function() {
                        return date('Y-m-d H:i:s');
                    });
                    @self::$instance->sqliteCreateFunction('CURDATE', function() {
                        return date('Y-m-d');
                    });
                    @self::$instance->sqliteCreateFunction('YEAR', function($d = null) {
                        return $d ? date('Y', strtotime($d)) : date('Y');
                    });
                    @self::$instance->sqliteCreateFunction('MONTH', function($d = null) {
                        return $d ? (int)date('n', strtotime($d)) : (int)date('n');
                    });
                    @self::$instance->sqliteCreateFunction('YEARWEEK', function($d = null, $mode = 1) {
                        return $d ? (int)date('oW', strtotime($d)) : (int)date('oW');
                    });
                    @self::$instance->sqliteCreateFunction('UNIX_TIMESTAMP', function($d = null) {
                        return $d ? strtotime($d) : time();
                    });
                    @self::$instance->sqliteCreateFunction('IFNULL', function($a, $b) {
                        return $a !== null ? $a : $b;
                    });

                    // Verify if database tables exist; if not, run seeder
                    try {
                        self::$instance->query("SELECT 1 FROM users LIMIT 1");
                    } catch (Exception $chkEx) {
                        self::seedSqliteDatabase(self::$instance);
                    }
                } catch (PDOException $sqliteEx) {
                    die("Database Connection Error: " . $e->getMessage() . " | SQLite Fallback Error: " . $sqliteEx->getMessage());
                }
                self::ensureChatTableExists(self::$instance);
                self::ensureAuthorizedIpTableExists(self::$instance);
                self::ensureAttendanceColumnsExist(self::$instance);
            }
        }
        return self::$instance;
    }

    private static function ensureChatTableExists($pdo) {
        try {
            $isSqlite = (self::$driver === 'sqlite');
            $autoInc = $isSqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';
            
            $sql = "CREATE TABLE IF NOT EXISTS `chat_messages` (
                `id` {$autoInc},
                `sender_id` INT NOT NULL,
                `receiver_id` INT NULL,
                `department_id` INT NULL,
                `message` TEXT NOT NULL,
                `file_path` VARCHAR(255) NULL,
                `file_type` VARCHAR(50) NULL,
                `file_name` VARCHAR(255) NULL,
                `reply_to_id` INT NULL,
                `is_seen` TINYINT DEFAULT 0,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            );";
            $pdo->exec($sql);
            self::ensureAiTablesExist($pdo);
        } catch (Throwable $e) {
            error_log("Chat table init notice: " . $e->getMessage());
        }
    }

    private static function ensureAiTablesExist($pdo) {
        try {
            $isSqlite = (self::$driver === 'sqlite');
            $autoInc = $isSqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';

            $pdo->exec("CREATE TABLE IF NOT EXISTS `ai_action_plans` (
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
            );");

            $pdo->exec("CREATE TABLE IF NOT EXISTS `ai_chat_messages` (
                `id` {$autoInc},
                `user_id` INT NOT NULL,
                `conversation_id` VARCHAR(64) NOT NULL,
                `sender` VARCHAR(20) NOT NULL,
                `message` TEXT NOT NULL,
                `plan_token` VARCHAR(64) NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            );");
        } catch (Throwable $e) {
            error_log("AI tables init notice: " . $e->getMessage());
        }
    }

    public static function ensureAuthorizedIpTableExists($pdo) {
        try {
            $isSqlite = (self::$driver === 'sqlite');
            $autoInc = $isSqlite ? 'INTEGER PRIMARY KEY AUTOINCREMENT' : 'INT AUTO_INCREMENT PRIMARY KEY';

            $pdo->exec("CREATE TABLE IF NOT EXISTS `authorized_ips` (
                `id` {$autoInc},
                `ip_address` VARCHAR(45) NOT NULL UNIQUE,
                `location` VARCHAR(100) NOT NULL,
                `status` VARCHAR(20) DEFAULT 'active',
                `notes` TEXT NULL,
                `created_by` INT NULL,
                `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP
            );");

            // Seed default authorized office Wi-Fi networks and IP subnets
            $defaultOfficeIps = [
                ['192.168.29.174', 'Office Wi-Fi 1', 'active', 'Authorized Office Wi-Fi 1 (Static)'],
                ['192.168.1.8', 'Office Wi-Fi 2', 'active', 'Authorized Office Wi-Fi 2 (Static)'],
                ['192.168.1.9', 'Office Wi-Fi 2 Workstation', 'active', 'Current Office Machine'],
                ['192.168.1.22', 'Office Wi-Fi 2 Device', 'active', 'Authorized Office Wi-Fi 2 (192.168.1.22)'],
                ['192.168.1.*', 'Office Wi-Fi 2 Subnet', 'active', 'Authorized Office Wi-Fi 2 Network (*.1-254)'],
                ['192.168.29.*', 'Office Wi-Fi 1 Subnet', 'active', 'Authorized Office Wi-Fi 1 Network (*.1-254)'],
                ['106.219.148.*', 'Office Public Wi-Fi IPv4', 'active', 'Office Public ISP IPv4 Pool'],
                ['2401:4900:883d:6edd:*', 'Office Public Wi-Fi IPv6', 'active', 'Office Public ISP IPv6 Subnet'],
                ['2401:4900:883d:*', 'Office Public Wi-Fi IPv6 Prefix', 'active', 'Office Public ISP IPv6 Prefix'],
                ['2401:4900:*', 'Office Public Wi-Fi IPv6 (All Blocks)', 'active', 'Office Public ISP IPv6 Dynamic Pool'],
                ['127.0.0.1', 'Local Server / Loopback', 'active', 'Local Development & Office Server'],
                ['::1', 'IPv6 Loopback', 'active', 'Local IPv6 Loopback']
            ];

            $isSqliteDriver = str_contains(strtolower(self::$driver), 'sqlite');
            $insertSql = $isSqliteDriver 
                ? "INSERT OR IGNORE INTO `authorized_ips` (ip_address, location, status, notes) VALUES (?, ?, ?, ?)"
                : "INSERT IGNORE INTO `authorized_ips` (ip_address, location, status, notes) VALUES (?, ?, ?, ?)";
            $seedStmt = $pdo->prepare($insertSql);
            foreach ($defaultOfficeIps as $seed) {
                try {
                    $seedStmt->execute($seed);
                } catch (Throwable $ignoreErr) {}
            }
        } catch (Throwable $e) {
            error_log("Authorized IPs table init notice: " . $e->getMessage());
        }
    }

    public static function ensureAttendanceColumnsExist($pdo) {
        try {
            $columnsToAdd = [
                'employee_id' => 'INT DEFAULT 0',
                'user_id' => 'INT DEFAULT 0',
                'early_exit_minutes' => 'INT DEFAULT 0',
                'shift_start' => "VARCHAR(10) DEFAULT '09:40'",
                'shift_end' => "VARCHAR(10) DEFAULT '18:30'",
                'total_seconds' => 'INT DEFAULT 0',
                'break_seconds' => 'INT DEFAULT 0',
                'clock_in_ip' => 'VARCHAR(50) DEFAULT NULL',
                'clock_out_ip' => 'VARCHAR(50) DEFAULT NULL',
                'device_type' => "VARCHAR(50) DEFAULT 'Desktop'",
                'user_agent' => 'TEXT DEFAULT NULL'
            ];

            foreach ($columnsToAdd as $col => $type) {
                try {
                    $pdo->exec("ALTER TABLE attendance ADD COLUMN {$col} {$type}");
                } catch (Throwable $colEx) {}
            }
        } catch (Throwable $e) {
            error_log("Attendance columns init notice: " . $e->getMessage());
        }
    }

    public static function getDriver() {
        return self::$driver;
    }

    private static function seedSqliteDatabase($pdo) {
        $sqlPath = dirname(dirname(__DIR__)) . '/database/schema.sql';
        if (!file_exists($sqlPath)) {
            $sqlPath = __DIR__ . '/../../database/schema.sql';
        }
        if (file_exists($sqlPath)) {
            try {
                $sql = file_get_contents($sqlPath);
                $pdo->exec($sql);
                return;
            } catch (Exception $e) {
                error_log("SQLite Seed via schema.sql notice: " . $e->getMessage());
            }
        }
    }
}
