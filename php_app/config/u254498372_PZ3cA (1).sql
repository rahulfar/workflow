-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 26, 2026 at 04:42 AM
-- Server version: 11.8.9-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u254498372_PZ3cA`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `record_type` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(1, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:40:31'),
(2, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:41:56'),
(3, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:42:03'),
(4, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:42:34'),
(5, 2, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:42:39'),
(6, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:43:54'),
(7, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:43:58'),
(8, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:49:49'),
(9, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:3059:25ef:9019:7d73:7abb:4c89', '2026-08-19 12:50:58'),
(10, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3059:25ef:9019:7d73:7abb:4c89', '2026-08-19 12:51:15'),
(11, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/08/2026 12:51:22 PM (IST). Late minutes: 191', '2409:40d2:3059:25ef:9019:7d73:7abb:4c89', '2026-08-19 12:51:22'),
(12, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:51:34'),
(13, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-19 12:52:02'),
(14, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #3', '2409:40d2:3059:25ef:9019:7d73:7abb:4c89', '2026-08-19 12:52:28'),
(15, 9, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee stopped timer & completed task #3.', '2409:40d2:3059:25ef:9019:7d73:7abb:4c89', '2026-08-19 12:52:37'),
(16, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-19 12:52:46'),
(17, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:52:58'),
(18, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/08/2026 12:53:01 PM (IST). Late minutes: 193', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-19 12:53:01'),
(19, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.144.119', '2026-08-19 12:53:06'),
(20, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:c4ea:31d2:71e6:5e13:17fb', '2026-08-19 12:53:12'),
(21, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/08/2026 12:53:15 PM (IST). Late minutes: 193', '106.219.144.119', '2026-08-19 12:53:15'),
(22, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:c4ea:e9ef:89ef:81c1:487b', '2026-08-19 16:45:15'),
(23, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:c523:32f4:396b:bd79', '2026-08-19 18:32:38'),
(24, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:c523:32f4:396b:bd79', '2026-08-19 18:32:47'),
(25, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-19 18:32:59'),
(26, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:c523:32f4:396b:bd79', '2026-08-19 18:33:00'),
(27, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.144.119', '2026-08-19 18:33:05'),
(28, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 19/08/2026 06:33:09 PM (IST). Status: half_day, Duration: 05h 40m 08s, Early Exit: 0m', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-19 18:33:09'),
(29, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 19/08/2026 06:33:15 PM (IST). Status: half_day, Duration: 05h 40m 00s, Early Exit: 0m', '106.219.144.119', '2026-08-19 18:33:15'),
(30, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 19/08/2026 06:33:28 PM (IST). Status: half_day, Duration: 05h 42m 06s, Early Exit: 0m', '2405:201:6804:bcd8:c523:32f4:396b:bd79', '2026-08-19 18:33:28'),
(31, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:c04:ae80:691f:6fff', '2026-08-20 09:53:02'),
(32, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 20/08/2026 09:53:14 AM (IST). Late minutes: 13', '2405:201:6804:bcd8:c04:ae80:691f:6fff', '2026-08-20 09:53:14'),
(33, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-20 09:54:01'),
(34, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 20/08/2026 09:54:07 AM (IST). Late minutes: 14', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-20 09:54:07'),
(35, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:151:a6d2:9dcd:a829', '2026-08-20 09:57:54'),
(36, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 20/08/2026 09:58:04 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:151:a6d2:9dcd:a829', '2026-08-20 09:58:04'),
(37, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.139', '2026-08-20 09:58:38'),
(38, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 20/08/2026 09:58:44 AM (IST). Late minutes: 18', '49.43.160.139', '2026-08-20 09:58:44'),
(39, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:151:a6d2:9dcd:a829', '2026-08-20 16:38:13'),
(40, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-20 17:38:06'),
(41, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.139', '2026-08-20 18:29:54'),
(42, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 20/08/2026 06:30:03 PM (IST). Status: full_day, Duration: 08h 31m 19s, Early Exit: 0m', '49.43.160.139', '2026-08-20 18:30:03'),
(43, 6, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '49.43.160.139', '2026-08-20 18:30:27'),
(44, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.139', '2026-08-20 18:31:23'),
(45, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 20/08/2026 06:31:34 PM (IST). Status: full_day, Duration: 08h 33m 30s, Early Exit: 0m', '49.43.160.139', '2026-08-20 18:31:34'),
(46, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-08-21 09:42:46'),
(47, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/08/2026 09:42:51 AM (IST). Late minutes: 2', '106.219.147.7', '2026-08-21 09:42:51'),
(48, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-21 09:51:57'),
(49, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/08/2026 09:52:05 AM (IST). Status: half_day, Duration: 23h 57m 58s, Early Exit: 517m', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-21 09:52:05'),
(50, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/08/2026 09:52:11 AM (IST). Late minutes: 12', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-08-21 09:52:11'),
(51, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:21:29'),
(52, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/08/2026 10:21:34 AM (IST). Late minutes: 41', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:21:34'),
(53, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:d39e:854f:52ca:87cf:f3dd', '2026-08-21 10:24:58'),
(54, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5416:287b:40af:8040', '2026-08-21 10:26:50'),
(55, 7, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/08/2026 10:27:07 AM (IST). Status: half_day, Duration: 24h 33m 53s, Early Exit: 482m', '2405:201:6804:bcd8:5416:287b:40af:8040', '2026-08-21 10:27:07'),
(56, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/08/2026 10:27:12 AM (IST). Late minutes: 47', '2405:201:6804:bcd8:5416:287b:40af:8040', '2026-08-21 10:27:12'),
(57, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:30:38'),
(58, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:32:06'),
(59, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:36:37'),
(60, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:36:58'),
(61, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/08/2026 10:37:02 AM (IST). Late minutes: 57', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:37:02'),
(62, 10, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:43:43'),
(63, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:b918:c170:3a5a:d8c5', '2026-08-21 10:43:48'),
(64, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:03:14'),
(65, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-21, ID: 8) as full_day', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:03:15'),
(66, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-21, ID: 9) as full_day', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:03:15'),
(67, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-21, ID: 10) as half_day', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:03:15'),
(68, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-21, ID: 11) as half_day', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:03:15'),
(69, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-21, ID: 12) as half_day', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:03:15'),
(70, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.24', '2026-08-24 12:03:44'),
(71, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/08/2026 12:03:53 PM (IST). Late minutes: 143', '49.43.160.24', '2026-08-24 12:03:53'),
(72, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:2884:794d:2947:5b0', '2026-08-24 12:04:12'),
(73, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/08/2026 12:04:20 PM (IST). Late minutes: 144', '2401:4900:883c:d39e:2884:794d:2947:5b0', '2026-08-24 12:04:20'),
(74, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:04:25'),
(75, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:05:52'),
(76, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:c9fa:b54e:3d98:c9fd', '2026-08-24 12:06:10'),
(77, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 13:35:55'),
(78, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #8 created and assigned to User #5', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 13:41:24'),
(79, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #9 created and assigned to User #5', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 13:43:23'),
(80, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #10 created and assigned to User #9', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 13:48:02'),
(81, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #11 created and assigned to User #2', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 13:51:30'),
(82, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #12 created and assigned to User #4', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 13:55:01'),
(83, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #13 created and assigned to User #5', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 13:56:35'),
(84, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #14 created and assigned to User #4', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 14:03:14'),
(85, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #15 created and assigned to User #4', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 14:04:09'),
(86, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:2884:794d:2947:5b0', '2026-08-24 14:20:53'),
(87, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #16 created and assigned to User #5', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 15:47:29'),
(88, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #17 created and assigned to User #5', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 15:50:39'),
(89, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #18 created and assigned to User #5', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 15:52:53'),
(90, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #19 created and assigned to User #5', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 15:55:05'),
(91, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #20 created and assigned to User #5', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 15:55:58'),
(92, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #21 created and assigned to User #4', '2401:4900:883c:d39e:ecdb:9223:fd68:ca37', '2026-08-24 15:58:02'),
(93, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #10', '2401:4900:883c:d39e:2884:794d:2947:5b0', '2026-08-24 17:53:32'),
(94, 9, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee stopped timer & completed task #10.', '2401:4900:883c:d39e:2884:794d:2947:5b0', '2026-08-24 17:53:43'),
(95, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:2cd8:5e52:170:472b', '2026-08-25 10:52:37'),
(96, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-24, ID: 14) as half_day', '2401:4900:883c:d39e:2cd8:5e52:170:472b', '2026-08-25 10:52:38'),
(97, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.190', '2026-08-25 10:52:41'),
(98, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/08/2026 10:52:41 AM (IST). Late minutes: 72', '2401:4900:883c:d39e:2cd8:5e52:170:472b', '2026-08-25 10:52:41'),
(99, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-24, ID: 13) as half_day', '49.43.160.190', '2026-08-25 10:52:43'),
(100, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/08/2026 10:52:45 AM (IST). Late minutes: 72', '49.43.160.190', '2026-08-25 10:52:45'),
(101, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:d39e:bef:7106:909e:7b13', '2026-08-25 11:04:15'),
(102, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #22 created and assigned to User #5', '2401:4900:883c:d39e:bef:7106:909e:7b13', '2026-08-25 11:07:33'),
(103, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #23 created and assigned to User #5', '2401:4900:883c:d39e:bef:7106:909e:7b13', '2026-08-25 11:08:42'),
(104, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #24 created and assigned to User #5', '2401:4900:883c:d39e:bef:7106:909e:7b13', '2026-08-25 11:09:43'),
(105, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:2cd8:5e52:170:472b', '2026-08-25 13:34:59'),
(106, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:2cd8:5e52:170:472b', '2026-08-25 18:09:02'),
(107, 2, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:d39e:388d:6cad:445:e2cc', '2026-08-25 18:24:13'),
(108, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 25/08/2026 06:29:29 PM (IST). Status: half_day, Duration: 07h 36m 48s, Early Exit: 0m', '2401:4900:883c:d39e:2cd8:5e52:170:472b', '2026-08-25 18:29:29'),
(109, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:d39e:444f:af1b:845b:1421', '2026-08-26 09:37:49'),
(110, 2, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #25 created and assigned to User #5', '2401:4900:883c:d39e:444f:af1b:845b:1421', '2026-08-26 09:45:15'),
(111, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:2cd8:5e52:170:472b', '2026-08-26 09:56:06'),
(112, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/08/2026 09:56:21 AM (IST). Late minutes: 16', '2401:4900:883c:d39e:2cd8:5e52:170:472b', '2026-08-26 09:56:21'),
(113, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:6939:3c59:867e:d79a', '2026-08-26 10:26:41'),
(114, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/08/2026 10:26:46 AM (IST). Late minutes: 46', '2405:201:6804:bcd8:6939:3c59:867e:d79a', '2026-08-26 10:26:46'),
(115, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-25, ID: 16) as half_day', '2405:201:6804:bcd8:6939:3c59:867e:d79a', '2026-08-26 10:28:52'),
(116, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:e580:eaa5:9bba:35f0', '2026-08-26 18:26:57'),
(117, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 26/08/2026 06:30:34 PM (IST). Status: half_day, Duration: 08h 34m 13s, Early Exit: 0m', '2401:4900:883c:d39e:e580:eaa5:9bba:35f0', '2026-08-26 18:30:34'),
(118, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8c3b:554f:9fb4:d1e1', '2026-08-27 09:53:38'),
(119, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/08/2026 09:53:43 AM (IST). Late minutes: 13', '2405:201:6804:bcd8:8c3b:554f:9fb4:d1e1', '2026-08-27 09:53:43'),
(120, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-08-27 10:11:25'),
(121, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/08/2026 10:11:32 AM (IST). Late minutes: 31', '106.219.147.7', '2026-08-27 10:11:32'),
(122, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:e580:eaa5:9bba:35f0', '2026-08-27 10:17:03'),
(123, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/08/2026 10:17:17 AM (IST). Late minutes: 37', '2401:4900:883c:d39e:e580:eaa5:9bba:35f0', '2026-08-27 10:17:17'),
(124, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-26, ID: 18) as half_day', '2401:4900:883c:d39e:e580:eaa5:9bba:35f0', '2026-08-27 10:20:55'),
(125, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:4020:a076:b166:21fb', '2026-08-27 10:22:13'),
(126, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:c51:831c:7c50:86e6', '2026-08-27 10:23:26'),
(127, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/08/2026 10:23:31 AM (IST). Late minutes: 43', '2401:4900:883c:d39e:c51:831c:7c50:86e6', '2026-08-27 10:23:31'),
(128, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:4020:a076:b166:21fb', '2026-08-27 10:45:28'),
(129, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:45bf:6427:4eef:f556', '2026-08-27 12:32:46'),
(130, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.100', '2026-08-29 09:43:35'),
(131, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-27, ID: 20) as half_day', '49.43.160.100', '2026-08-29 09:43:37'),
(132, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/08/2026 09:43:41 AM (IST). Late minutes: 3', '49.43.160.100', '2026-08-29 09:43:41'),
(133, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:a0d1:77f3:11fc:de72', '2026-08-29 09:52:54'),
(134, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-27, ID: 21) as half_day', '2401:4900:883c:d39e:a0d1:77f3:11fc:de72', '2026-08-29 09:52:57'),
(135, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/08/2026 09:53:00 AM (IST). Late minutes: 13', '2401:4900:883c:d39e:a0d1:77f3:11fc:de72', '2026-08-29 09:53:00'),
(136, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bd80:543f:9a0a:cc5c', '2026-08-29 09:58:54'),
(137, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-27, ID: 19) as full_day', '2405:201:6804:bcd8:bd80:543f:9a0a:cc5c', '2026-08-29 09:58:55'),
(138, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/08/2026 09:58:59 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:bd80:543f:9a0a:cc5c', '2026-08-29 09:58:59'),
(139, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:5030:d90:b6e1:a955', '2026-08-29 10:40:47'),
(140, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-27, ID: 22) as half_day', '2405:201:6804:bcd8:5030:d90:b6e1:a955', '2026-08-29 10:40:49'),
(141, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:e9c9:783e:eb6f:2146', '2026-08-29 10:42:03'),
(142, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/08/2026 10:42:10 AM (IST). Late minutes: 62', '2405:201:6804:bcd8:e9c9:783e:eb6f:2146', '2026-08-29 10:42:10'),
(143, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:5030:d90:b6e1:a955', '2026-08-29 11:29:35'),
(144, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:a0d1:77f3:11fc:de72', '2026-08-29 15:38:09'),
(145, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:645e:c0fc:3bc9:8bf9', '2026-08-31 09:53:59'),
(146, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-29, ID: 25) as full_day', '2405:201:6804:bcd8:645e:c0fc:3bc9:8bf9', '2026-08-31 09:54:01'),
(147, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/08/2026 09:54:03 AM (IST). Late minutes: 14', '2405:201:6804:bcd8:645e:c0fc:3bc9:8bf9', '2026-08-31 09:54:03'),
(148, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.150', '2026-08-31 10:10:18'),
(149, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-29, ID: 23) as full_day', '49.43.160.150', '2026-08-31 10:10:20'),
(150, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/08/2026 10:10:34 AM (IST). Late minutes: 30', '49.43.160.150', '2026-08-31 10:10:34'),
(151, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:f9bf:ce6:de04:f10', '2026-08-31 10:10:47'),
(152, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-29, ID: 24) as full_day', '2401:4900:883c:d39e:f9bf:ce6:de04:f10', '2026-08-31 10:10:48'),
(153, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/08/2026 10:10:51 AM (IST). Late minutes: 30', '2401:4900:883c:d39e:f9bf:ce6:de04:f10', '2026-08-31 10:10:51'),
(154, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:c874:9bee:2401:e318', '2026-08-31 12:48:12'),
(155, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-29, ID: 26) as half_day', '2405:201:6804:bcd8:c874:9bee:2401:e318', '2026-08-31 12:48:14'),
(156, 1, NULL, 'Generate Payroll', NULL, NULL, NULL, 'Payroll', 'Admin generated monthly payroll for 2026-08 for 10 employees', '2405:201:6804:bcd8:c874:9bee:2401:e318', '2026-08-31 12:49:49'),
(157, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-09-01 09:26:16'),
(158, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/09/2026 09:26:22 AM (IST). Late minutes: 0', '106.219.147.7', '2026-09-01 09:26:22'),
(159, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '106.219.147.7', '2026-09-01 09:26:42'),
(160, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:75d6:6a78:7a49:2e1f', '2026-09-01 09:46:00'),
(161, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/09/2026 09:46:06 AM (IST). Late minutes: 6', '2401:4900:883c:d39e:75d6:6a78:7a49:2e1f', '2026-09-01 09:46:06'),
(162, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-09-01 09:50:42'),
(163, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-31, ID: 28) as half_day', '106.219.147.7', '2026-09-01 09:50:43'),
(164, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/09/2026 09:50:51 AM (IST). Late minutes: 10', '106.219.147.7', '2026-09-01 09:50:51'),
(165, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:502e:565a:2b0c:ea45', '2026-09-01 09:51:27'),
(166, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-31, ID: 27) as full_day', '2405:201:6804:bcd8:502e:565a:2b0c:ea45', '2026-09-01 09:51:28'),
(167, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-08-31, ID: 29) as half_day', '2405:201:6804:bcd8:502e:565a:2b0c:ea45', '2026-09-01 09:51:28'),
(168, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-01 09:59:14'),
(169, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/09/2026 09:59:19 AM (IST). Late minutes: 19', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-01 09:59:19'),
(170, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:ccf9:ea5f:919f:aa64', '2026-09-01 10:01:35'),
(171, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/09/2026 10:01:40 AM (IST). Late minutes: 21', '2405:201:6804:bcd8:ccf9:ea5f:919f:aa64', '2026-09-01 10:01:40'),
(172, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-09-01 10:02:17'),
(173, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:cbc:256f:e53d:6599', '2026-09-01 10:19:31'),
(174, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:502e:565a:2b0c:ea45', '2026-09-01 10:20:22'),
(175, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:502e:565a:2b0c:ea45', '2026-09-01 10:21:11'),
(176, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:cbc:256f:e53d:6599', '2026-09-01 10:22:24'),
(177, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:d39e:cbc:256f:e53d:6599', '2026-09-01 10:24:21'),
(178, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:d39e:cbc:256f:e53d:6599', '2026-09-01 10:25:38'),
(179, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:d39e:cbc:256f:e53d:6599', '2026-09-01 10:29:59'),
(180, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:cbc:256f:e53d:6599', '2026-09-01 10:31:50'),
(181, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:502e:565a:2b0c:ea45', '2026-09-01 10:39:10'),
(182, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:ccf9:ea5f:919f:aa64', '2026-09-01 12:12:47'),
(183, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:bb63:bd65:652a:5c9f:c8dc:c2c6', '2026-09-01 12:33:02'),
(184, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:bb63:bd65:652a:5c9f:c8dc:c2c6', '2026-09-01 12:36:44'),
(185, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:bb63:bd65:652a:5c9f:c8dc:c2c6', '2026-09-01 12:37:34'),
(186, 2, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:bb63:bd65:652a:5c9f:c8dc:c2c6', '2026-09-01 12:38:07'),
(187, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:bb63:bd65:652a:5c9f:c8dc:c2c6', '2026-09-01 12:39:20'),
(188, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:502e:565a:2b0c:ea45', '2026-09-01 12:40:14'),
(189, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-01 16:05:26'),
(190, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3001:3c34:74fa:6f9a:f40d:3713', '2026-09-01 18:24:32'),
(191, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 01/09/2026 06:25:30 PM (IST). Status: half_day, Duration: 08h 23m 50s, Early Exit: 4m', '2409:40d2:3001:3c34:74fa:6f9a:f40d:3713', '2026-09-01 18:25:30'),
(192, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:e413:6d82:6533:dfbd', '2026-09-02 09:30:43'),
(193, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-01, ID: 30) as full_day', '2401:4900:883c:d39e:e413:6d82:6533:dfbd', '2026-09-02 09:30:45'),
(194, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/09/2026 09:30:47 AM (IST). Late minutes: 0', '2401:4900:883c:d39e:e413:6d82:6533:dfbd', '2026-09-02 09:30:47'),
(195, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3001:2071:8000::', '2026-09-02 09:45:47'),
(196, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-01, ID: 31) as full_day', '2409:40d2:3001:2071:8000::', '2026-09-02 09:45:49'),
(197, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/09/2026 09:45:54 AM (IST). Late minutes: 5', '2409:40d2:3001:2071:8000::', '2026-09-02 09:45:54'),
(198, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:3d2a:97a:5d10:1c78', '2026-09-02 09:54:15'),
(199, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/09/2026 09:55:25 AM (IST). Late minutes: 15', '2401:4900:883c:d39e:3d2a:97a:5d10:1c78', '2026-09-02 09:55:25'),
(200, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-09-02 09:56:52'),
(201, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-01, ID: 32) as full_day', '106.219.147.7', '2026-09-02 09:56:54'),
(202, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/09/2026 09:57:07 AM (IST). Late minutes: 17', '106.219.147.7', '2026-09-02 09:57:07'),
(203, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-02 10:46:23'),
(204, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-01, ID: 33) as full_day', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-02 10:46:24'),
(205, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/09/2026 10:46:27 AM (IST). Late minutes: 66', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-02 10:46:27'),
(206, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:ed4d:7757:c763:e7b9', '2026-09-02 11:16:06'),
(207, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:749e:8521:922a:a1b', '2026-09-02 15:51:41'),
(208, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:61b5:1ee0:de69:de36', '2026-09-02 17:24:36'),
(209, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:d960:a25f:1bd1:726c', '2026-09-02 18:06:55'),
(210, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:5549:a3b8:5d9e:ffb1', '2026-09-03 09:33:36'),
(211, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-02, ID: 35) as full_day', '2401:4900:883c:d39e:5549:a3b8:5d9e:ffb1', '2026-09-03 09:33:38'),
(212, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/09/2026 09:33:41 AM (IST). Late minutes: 0', '2401:4900:883c:d39e:5549:a3b8:5d9e:ffb1', '2026-09-03 09:33:41'),
(213, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:d39e:5549:a3b8:5d9e:ffb1', '2026-09-03 09:33:54'),
(214, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304d:3791:8000::', '2026-09-03 09:40:15'),
(215, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-02, ID: 36) as full_day', '2409:40d2:304d:3791:8000::', '2026-09-03 09:40:17'),
(216, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/09/2026 09:40:19 AM (IST). Late minutes: 0', '2409:40d2:304d:3791:8000::', '2026-09-03 09:40:19'),
(217, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-09-03 10:02:59'),
(218, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-02, ID: 38) as full_day', '106.219.147.7', '2026-09-03 10:03:00'),
(219, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/09/2026 10:03:07 AM (IST). Late minutes: 23', '106.219.147.7', '2026-09-03 10:03:07'),
(220, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:94db:a5f8:104d:73b6', '2026-09-03 10:08:53'),
(221, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-02, ID: 37) as full_day', '2401:4900:883c:d39e:94db:a5f8:104d:73b6', '2026-09-03 10:08:55'),
(222, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/09/2026 10:08:58 AM (IST). Late minutes: 28', '2401:4900:883c:d39e:94db:a5f8:104d:73b6', '2026-09-03 10:08:58'),
(223, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:a143:baa0:edc5:35dd', '2026-09-03 10:30:22'),
(224, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-02, ID: 39) as half_day', '2405:201:6804:bcd8:a143:baa0:edc5:35dd', '2026-09-03 10:30:23'),
(225, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:d39e:d7c:9a2e:1f94:64ab', '2026-09-04 09:40:43'),
(226, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-03, ID: 43) as full_day', '2401:4900:883c:d39e:d7c:9a2e:1f94:64ab', '2026-09-04 09:40:45'),
(227, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/09/2026 09:40:47 AM (IST). Late minutes: 0', '2401:4900:883c:d39e:d7c:9a2e:1f94:64ab', '2026-09-04 09:40:47'),
(228, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-09-04 09:43:07'),
(229, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-03, ID: 42) as full_day', '106.219.147.7', '2026-09-04 09:43:08'),
(230, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/09/2026 09:43:17 AM (IST). Late minutes: 3', '106.219.147.7', '2026-09-04 09:43:17'),
(231, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3046:5f4d:8000::', '2026-09-04 09:47:05'),
(232, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-03, ID: 41) as full_day', '2409:40d2:3046:5f4d:8000::', '2026-09-04 09:47:06'),
(233, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/09/2026 09:47:09 AM (IST). Late minutes: 7', '2409:40d2:3046:5f4d:8000::', '2026-09-04 09:47:09'),
(234, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3945:dc49:2a45:ad53', '2026-09-04 09:59:24'),
(235, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/09/2026 09:59:31 AM (IST). Late minutes: 19', '2405:201:6804:bcd8:3945:dc49:2a45:ad53', '2026-09-04 09:59:31'),
(236, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:d39e:5175:d40f:bb19:5344', '2026-09-04 12:51:16'),
(237, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-03, ID: 40) as full_day', '2401:4900:883c:d39e:5175:d40f:bb19:5344', '2026-09-04 12:51:18'),
(238, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:d39e:5175:d40f:bb19:5344', '2026-09-04 12:52:09'),
(239, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:fcd8:66f0:1add:a84f', '2026-09-04 17:27:17'),
(240, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.7', '2026-09-04 17:27:45'),
(241, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883f:d888:81fa:5693:86dc:cfd4', '2026-09-05 09:53:55'),
(242, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-04, ID: 46) as full_day', '2401:4900:883f:d888:81fa:5693:86dc:cfd4', '2026-09-05 09:53:57'),
(243, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/09/2026 09:53:59 AM (IST). Late minutes: 13', '2401:4900:883f:d888:81fa:5693:86dc:cfd4', '2026-09-05 09:53:59'),
(244, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:dd99:d6f2:98ea:13f0', '2026-09-05 10:01:53'),
(245, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-04, ID: 44) as full_day', '2405:201:6804:bcd8:dd99:d6f2:98ea:13f0', '2026-09-05 10:01:54'),
(246, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/09/2026 10:01:59 AM (IST). Late minutes: 21', '2405:201:6804:bcd8:dd99:d6f2:98ea:13f0', '2026-09-05 10:01:59'),
(247, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-04, ID: 45) as full_day', '2405:201:6804:bcd8:dd99:d6f2:98ea:13f0', '2026-09-05 10:02:08'),
(248, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-04, ID: 47) as full_day', '2405:201:6804:bcd8:dd99:d6f2:98ea:13f0', '2026-09-05 10:02:08'),
(249, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:2c10:28e1:4275:c396', '2026-09-05 10:52:57'),
(250, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/09/2026 10:53:02 AM (IST). Late minutes: 73', '2405:201:6804:bcd8:2c10:28e1:4275:c396', '2026-09-05 10:53:02'),
(251, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:dd99:d6f2:98ea:13f0', '2026-09-05 12:26:19'),
(252, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:dd99:d6f2:98ea:13f0', '2026-09-05 17:22:11'),
(253, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.33', '2026-09-07 09:57:17'),
(254, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-05, ID: 49) as full_day', '49.43.160.33', '2026-09-07 09:57:19'),
(255, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/09/2026 09:57:23 AM (IST). Late minutes: 17', '49.43.160.33', '2026-09-07 09:57:23'),
(256, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.150.99', '2026-09-07 10:06:34'),
(257, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/09/2026 10:06:46 AM (IST). Late minutes: 26', '106.219.150.99', '2026-09-07 10:06:46'),
(258, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.150.99', '2026-09-08 09:52:30'),
(259, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-07, ID: 52) as full_day', '106.219.150.99', '2026-09-08 09:52:32'),
(260, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 08/09/2026 09:52:52 AM (IST). Late minutes: 12', '106.219.150.99', '2026-09-08 09:52:52'),
(261, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bd35:2b8c:223e:a565', '2026-09-08 09:58:12'),
(262, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-05, ID: 50) as half_day', '2405:201:6804:bcd8:bd35:2b8c:223e:a565', '2026-09-08 09:58:15'),
(263, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 08/09/2026 09:58:29 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:bd35:2b8c:223e:a565', '2026-09-08 09:58:29'),
(264, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-05, ID: 48) as full_day', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-08 13:34:07'),
(265, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-07, ID: 51) as full_day', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-08 13:34:07'),
(266, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:5c47:498:6066:3a26', '2026-09-09 09:49:54'),
(267, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/09/2026 09:50:06 AM (IST). Late minutes: 10', '2401:4900:883c:79c2:5c47:498:6066:3a26', '2026-09-09 09:50:06'),
(268, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-08, ID: 53) as full_day', '2401:4900:883c:79c2:5c47:498:6066:3a26', '2026-09-09 09:50:12'),
(269, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-08, ID: 54) as full_day', '2401:4900:883c:79c2:5c47:498:6066:3a26', '2026-09-09 09:50:12'),
(270, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:1c68:f0:5ab3:9411', '2026-09-09 09:55:42'),
(271, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/09/2026 09:55:47 AM (IST). Late minutes: 15', '2405:201:6804:bcd8:1c68:f0:5ab3:9411', '2026-09-09 09:55:47'),
(272, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:79c2:4dc6:a8d8:79b0:f3c9', '2026-09-09 15:56:54'),
(273, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.150.99', '2026-09-09 16:06:28'),
(274, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/09/2026 04:06:35 PM (IST). Late minutes: 386', '106.219.150.99', '2026-09-09 16:06:35'),
(275, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:79c2:4dc6:a8d8:79b0:f3c9', '2026-09-09 18:22:25'),
(276, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:7846:b3b8:3293:4431', '2026-09-10 09:53:20'),
(277, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-09, ID: 55) as full_day', '2401:4900:883c:79c2:7846:b3b8:3293:4431', '2026-09-10 09:53:22'),
(278, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/09/2026 09:53:25 AM (IST). Late minutes: 13', '2401:4900:883c:79c2:7846:b3b8:3293:4431', '2026-09-10 09:53:25'),
(279, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-09, ID: 56) as full_day', '2401:4900:883c:79c2:7846:b3b8:3293:4431', '2026-09-10 09:53:29'),
(280, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-09, ID: 57) as half_day', '2401:4900:883c:79c2:7846:b3b8:3293:4431', '2026-09-10 09:53:29'),
(281, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3cc7:5ea0:870e:1bf5', '2026-09-10 09:57:57'),
(282, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/09/2026 09:58:03 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:3cc7:5ea0:870e:1bf5', '2026-09-10 09:58:03'),
(283, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3052:a715:8000::', '2026-09-10 10:01:55'),
(284, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/09/2026 10:01:59 AM (IST). Late minutes: 21', '2409:40d2:3052:a715:8000::', '2026-09-10 10:01:59'),
(285, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:79c2:9886:acc:7532:f7ff', '2026-09-10 10:02:06'),
(286, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.206', '2026-09-10 10:03:12'),
(287, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:79c2:9886:acc:7532:f7ff', '2026-09-10 10:03:19'),
(288, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/09/2026 10:03:20 AM (IST). Late minutes: 23', '49.43.160.206', '2026-09-10 10:03:20'),
(289, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3052:a715:8000::', '2026-09-11 09:48:07'),
(290, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-10, ID: 60) as full_day', '2409:40d2:3052:a715:8000::', '2026-09-11 09:48:08'),
(291, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 11/09/2026 09:48:11 AM (IST). Late minutes: 8', '2409:40d2:3052:a715:8000::', '2026-09-11 09:48:11'),
(292, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:35d0:dcae:d3ec:c602', '2026-09-11 09:57:18'),
(293, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-10, ID: 58) as full_day', '2401:4900:883c:79c2:35d0:dcae:d3ec:c602', '2026-09-11 09:57:26'),
(294, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 11/09/2026 09:57:29 AM (IST). Late minutes: 17', '2401:4900:883c:79c2:35d0:dcae:d3ec:c602', '2026-09-11 09:57:29'),
(295, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-10, ID: 59) as full_day', '2401:4900:883c:79c2:35d0:dcae:d3ec:c602', '2026-09-11 09:57:33'),
(296, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-10, ID: 61) as full_day', '2401:4900:883c:79c2:35d0:dcae:d3ec:c602', '2026-09-11 09:57:33'),
(297, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-11 10:02:19'),
(298, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 11/09/2026 10:02:26 AM (IST). Late minutes: 22', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-11 10:02:26'),
(299, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.150.99', '2026-09-11 10:08:15');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(300, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 11/09/2026 10:08:24 AM (IST). Late minutes: 28', '106.219.150.99', '2026-09-11 10:08:24'),
(301, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:35d0:dcae:d3ec:c602', '2026-09-11 13:40:46'),
(302, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:35d0:dcae:d3ec:c602', '2026-09-11 18:32:12'),
(303, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.150.99', '2026-09-12 09:52:23'),
(304, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-11, ID: 65) as full_day', '106.219.150.99', '2026-09-12 09:52:25'),
(305, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/09/2026 09:52:32 AM (IST). Late minutes: 12', '106.219.150.99', '2026-09-12 09:52:32'),
(306, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-11, ID: 62) as full_day', '106.219.150.99', '2026-09-12 09:52:40'),
(307, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-11, ID: 63) as full_day', '106.219.150.99', '2026-09-12 09:52:40'),
(308, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-11, ID: 64) as full_day', '106.219.150.99', '2026-09-12 09:52:40'),
(309, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3056:991e:8000::', '2026-09-12 09:55:06'),
(310, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/09/2026 09:55:11 AM (IST). Late minutes: 15', '2409:40d2:3056:991e:8000::', '2026-09-12 09:55:11'),
(311, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:e971:be52:7e1d:71d1', '2026-09-12 10:03:47'),
(312, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/09/2026 10:03:52 AM (IST). Late minutes: 23', '2401:4900:883c:79c2:e971:be52:7e1d:71d1', '2026-09-12 10:03:52'),
(313, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3051:d7c5:8000::', '2026-09-14 10:02:33'),
(314, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-12, ID: 67) as full_day', '2409:40d2:3051:d7c5:8000::', '2026-09-14 10:02:34'),
(315, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/09/2026 10:02:37 AM (IST). Late minutes: 22', '2409:40d2:3051:d7c5:8000::', '2026-09-14 10:02:37'),
(316, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-14 10:04:35'),
(317, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/09/2026 10:04:40 AM (IST). Late minutes: 24', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-14 10:04:40'),
(318, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3013:5a6f:381d:47ff:fe33:5f39', '2026-09-14 10:12:11'),
(319, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-12, ID: 66) as full_day', '2409:40d2:3013:5a6f:381d:47ff:fe33:5f39', '2026-09-14 10:12:12'),
(320, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/09/2026 10:12:19 AM (IST). Late minutes: 32', '2409:40d2:3013:5a6f:381d:47ff:fe33:5f39', '2026-09-14 10:12:19'),
(321, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:40d7:4d3b:1dc9:638c', '2026-09-14 10:15:34'),
(322, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-12, ID: 68) as full_day', '2401:4900:883c:79c2:40d7:4d3b:1dc9:638c', '2026-09-14 10:15:37'),
(323, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/09/2026 10:16:25 AM (IST). Late minutes: 36', '2401:4900:883c:79c2:40d7:4d3b:1dc9:638c', '2026-09-14 10:16:25'),
(324, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:79c2:d191:83a8:f1d3:a923', '2026-09-14 12:20:20'),
(325, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:79c2:d191:83a8:f1d3:a923', '2026-09-14 12:20:34'),
(326, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:40d7:4d3b:1dc9:638c', '2026-09-14 13:17:12'),
(327, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-14 18:23:55'),
(328, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 14/09/2026 06:29:06 PM (IST). Status: half_day, Duration: 08h 24m 26s, Early Exit: 0m', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-14 18:29:06'),
(329, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305f:a5e3:8000::', '2026-09-15 09:52:42'),
(330, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-14, ID: 69) as full_day', '2409:40d2:305f:a5e3:8000::', '2026-09-15 09:52:44'),
(331, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/09/2026 09:52:46 AM (IST). Late minutes: 12', '2409:40d2:305f:a5e3:8000::', '2026-09-15 09:52:46'),
(332, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:301c:d06a:c817:86ff:fe64:7767', '2026-09-15 09:55:47'),
(333, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-14, ID: 71) as half_day', '2409:40d2:301c:d06a:c817:86ff:fe64:7767', '2026-09-15 09:55:48'),
(334, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/09/2026 09:55:51 AM (IST). Late minutes: 15', '2409:40d2:301c:d06a:c817:86ff:fe64:7767', '2026-09-15 09:55:51'),
(335, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-15 09:58:44'),
(336, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/09/2026 09:58:49 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-15 09:58:49'),
(337, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-14, ID: 72) as half_day', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-15 09:58:52'),
(338, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:5817:2de1:e19a:e897', '2026-09-15 10:02:33'),
(339, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/09/2026 10:03:30 AM (IST). Late minutes: 23', '2401:4900:883c:79c2:5817:2de1:e19a:e897', '2026-09-15 10:03:30'),
(340, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:79c2:9ef:8a1f:4a36:8ada', '2026-09-15 11:43:36'),
(341, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:79c2:9ef:8a1f:4a36:8ada', '2026-09-15 11:52:52'),
(342, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3016:5c24:8000::', '2026-09-16 09:50:17'),
(343, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-15, ID: 73) as full_day', '2409:40d2:3016:5c24:8000::', '2026-09-16 09:50:18'),
(344, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/09/2026 09:50:21 AM (IST). Late minutes: 10', '2409:40d2:3016:5c24:8000::', '2026-09-16 09:50:21'),
(345, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:c1dd:39f3:a773:df06', '2026-09-16 09:52:20'),
(346, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-15, ID: 76) as full_day', '2401:4900:883c:79c2:c1dd:39f3:a773:df06', '2026-09-16 09:52:22'),
(347, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/09/2026 09:52:28 AM (IST). Late minutes: 12', '2401:4900:883c:79c2:c1dd:39f3:a773:df06', '2026-09-16 09:52:28'),
(348, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3008:c2fa:3c7e:66ff:fed5:c8c3', '2026-09-16 09:54:46'),
(349, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-15, ID: 74) as full_day', '2409:40d2:3008:c2fa:3c7e:66ff:fed5:c8c3', '2026-09-16 09:54:48'),
(350, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/09/2026 09:54:53 AM (IST). Late minutes: 14', '2409:40d2:3008:c2fa:3c7e:66ff:fed5:c8c3', '2026-09-16 09:54:53'),
(351, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-16 09:57:59'),
(352, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-15, ID: 75) as full_day', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-16 09:58:00'),
(353, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/09/2026 09:58:03 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-09-16 09:58:03'),
(354, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:c1dd:39f3:a773:df06', '2026-09-16 16:24:36'),
(355, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:79c2:c1dd:39f3:a773:df06', '2026-09-16 18:24:59'),
(356, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:909d:fd28:18f7:ff46', '2026-09-17 09:59:34'),
(357, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-16, ID: 80) as full_day', '2401:4900:883e:1e94:909d:fd28:18f7:ff46', '2026-09-17 09:59:35'),
(358, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/09/2026 09:59:39 AM (IST). Late minutes: 19', '2401:4900:883e:1e94:909d:fd28:18f7:ff46', '2026-09-17 09:59:39'),
(359, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-16, ID: 77) as full_day', '2401:4900:883e:1e94:909d:fd28:18f7:ff46', '2026-09-17 09:59:44'),
(360, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-16, ID: 78) as full_day', '2401:4900:883e:1e94:909d:fd28:18f7:ff46', '2026-09-17 09:59:44'),
(361, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-16, ID: 79) as full_day', '2401:4900:883e:1e94:909d:fd28:18f7:ff46', '2026-09-17 09:59:44'),
(362, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3012:7ded:9c52:e3ff:fefd:1947', '2026-09-17 10:04:48'),
(363, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/09/2026 10:04:56 AM (IST). Late minutes: 24', '2409:40d2:3012:7ded:9c52:e3ff:fefd:1947', '2026-09-17 10:04:56'),
(364, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:255c:72b4:7ff8:12f0', '2026-09-17 10:18:11'),
(365, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/09/2026 10:18:15 AM (IST). Late minutes: 38', '2401:4900:883e:1e94:255c:72b4:7ff8:12f0', '2026-09-17 10:18:15'),
(366, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:300c:ebb9:9c0f:fbff:fe6c:8901', '2026-09-18 10:03:51'),
(367, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-17, ID: 82) as full_day', '2409:40d2:300c:ebb9:9c0f:fbff:fe6c:8901', '2026-09-18 10:03:52'),
(368, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 18/09/2026 10:03:55 AM (IST). Late minutes: 23', '2409:40d2:300c:ebb9:9c0f:fbff:fe6c:8901', '2026-09-18 10:03:55'),
(369, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-17, ID: 81) as full_day', '2409:40d2:300c:ebb9:9c0f:fbff:fe6c:8901', '2026-09-18 10:04:08'),
(370, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-17, ID: 83) as half_day', '2409:40d2:300c:ebb9:9c0f:fbff:fe6c:8901', '2026-09-18 10:04:08'),
(371, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:60f3:3d65:b963:5e30', '2026-09-18 10:16:52'),
(372, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 18/09/2026 10:16:58 AM (IST). Late minutes: 36', '2401:4900:883e:1e94:60f3:3d65:b963:5e30', '2026-09-18 10:16:58'),
(373, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:89:6bc1:72ff:af32', '2026-09-18 10:33:43'),
(374, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 18/09/2026 10:33:47 AM (IST). Late minutes: 53', '2401:4900:883e:1e94:89:6bc1:72ff:af32', '2026-09-18 10:33:47'),
(375, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:60f3:3d65:b963:5e30', '2026-09-18 16:39:51'),
(376, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:f0a6:9476:4757:16ce', '2026-09-18 18:30:12'),
(377, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 18/09/2026 06:30:20 PM (IST). Status: half_day, Duration: 07h 56m 33s, Early Exit: 0m', '2401:4900:883e:1e94:f0a6:9476:4757:16ce', '2026-09-18 18:30:20'),
(378, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:74c2:2d52:10c0:9d87', '2026-09-18 18:47:04'),
(379, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3013:52e6:982f:6bff:fec7:70a6', '2026-09-19 09:43:59'),
(380, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-18, ID: 84) as full_day', '2409:40d2:3013:52e6:982f:6bff:fec7:70a6', '2026-09-19 09:44:00'),
(381, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/09/2026 09:44:04 AM (IST). Late minutes: 4', '2409:40d2:3013:52e6:982f:6bff:fec7:70a6', '2026-09-19 09:44:04'),
(382, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:f0a6:9476:4757:16ce', '2026-09-19 10:02:20'),
(383, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/09/2026 10:02:26 AM (IST). Late minutes: 22', '2401:4900:883e:1e94:f0a6:9476:4757:16ce', '2026-09-19 10:02:26'),
(384, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-18, ID: 85) as half_day', '2401:4900:883e:1e94:f0a6:9476:4757:16ce', '2026-09-19 10:02:29'),
(385, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:ad2e:9af5:7ffc:8fac', '2026-09-19 10:10:26'),
(386, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/09/2026 10:10:54 AM (IST). Late minutes: 30', '2401:4900:883e:1e94:ad2e:9af5:7ffc:8fac', '2026-09-19 10:10:54'),
(387, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:1e94:c944:7d73:167b:40b5', '2026-09-19 12:11:30'),
(388, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:99a0:6e88:da6d:d062', '2026-09-21 09:56:40'),
(389, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-19, ID: 88) as full_day', '2405:201:6804:bcd8:99a0:6e88:da6d:d062', '2026-09-21 09:56:41'),
(390, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/09/2026 09:56:47 AM (IST). Late minutes: 16', '2405:201:6804:bcd8:99a0:6e88:da6d:d062', '2026-09-21 09:56:47'),
(391, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-19, ID: 87) as full_day', '2405:201:6804:bcd8:99a0:6e88:da6d:d062', '2026-09-21 09:56:52'),
(392, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-19, ID: 89) as half_day', '2405:201:6804:bcd8:99a0:6e88:da6d:d062', '2026-09-21 09:56:52'),
(393, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:39f7:1ef9:24c8:8319', '2026-09-21 10:09:57'),
(394, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/09/2026 10:10:02 AM (IST). Late minutes: 30', '2405:201:6804:bcd8:39f7:1ef9:24c8:8319', '2026-09-21 10:10:02'),
(395, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3010:55cb:949a:61ff:fe28:a8db', '2026-09-21 10:15:06'),
(396, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/09/2026 10:15:13 AM (IST). Late minutes: 35', '2409:40d2:3010:55cb:949a:61ff:fe28:a8db', '2026-09-21 10:15:13'),
(397, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:a635:c908:608f:d397:7a1f:2213', '2026-09-21 10:19:18'),
(398, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:39f7:1ef9:24c8:8319', '2026-09-21 11:02:40'),
(399, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:ad6c:4231:488c:fb8b', '2026-09-21 11:16:20'),
(400, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:300f:f0ac:6873:bff:fe36:e2d5', '2026-09-22 09:39:55'),
(401, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-21, ID: 92) as half_day', '2409:40d2:300f:f0ac:6873:bff:fe36:e2d5', '2026-09-22 09:39:56'),
(402, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/09/2026 09:40:00 AM (IST). Late minutes: 0', '2409:40d2:300f:f0ac:6873:bff:fe36:e2d5', '2026-09-22 09:40:00'),
(403, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:2de5:23b7:d4bd:796d', '2026-09-22 09:51:57'),
(404, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-21, ID: 91) as half_day', '2405:201:6804:bcd8:2de5:23b7:d4bd:796d', '2026-09-22 09:51:58'),
(405, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/09/2026 09:52:02 AM (IST). Late minutes: 12', '2405:201:6804:bcd8:2de5:23b7:d4bd:796d', '2026-09-22 09:52:02'),
(406, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-21, ID: 90) as full_day', '2405:201:6804:bcd8:2de5:23b7:d4bd:796d', '2026-09-22 09:52:09'),
(407, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:f7d:717a:759a:a767:b9ed', '2026-09-22 09:58:05'),
(408, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/09/2026 09:58:09 AM (IST). Late minutes: 18', '2401:4900:883e:f7d:717a:759a:a767:b9ed', '2026-09-22 09:58:09'),
(409, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5456:6f22:a2e3:7ea8', '2026-09-23 09:45:52'),
(410, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-22, ID: 94) as full_day', '2405:201:6804:bcd8:5456:6f22:a2e3:7ea8', '2026-09-23 09:45:56'),
(411, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/09/2026 09:46:03 AM (IST). Late minutes: 6', '2405:201:6804:bcd8:5456:6f22:a2e3:7ea8', '2026-09-23 09:46:03'),
(412, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-22, ID: 93) as full_day', '2405:201:6804:bcd8:5456:6f22:a2e3:7ea8', '2026-09-23 09:46:07'),
(413, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-22, ID: 95) as full_day', '2405:201:6804:bcd8:5456:6f22:a2e3:7ea8', '2026-09-23 09:46:07'),
(414, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:edeb:1af2:b9ad:8d9f', '2026-09-23 09:55:17'),
(415, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/09/2026 09:55:21 AM (IST). Late minutes: 15', '2401:4900:883c:eeb2:edeb:1af2:b9ad:8d9f', '2026-09-23 09:55:21'),
(416, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '187.15.154.213', '2026-09-23 10:03:33'),
(417, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/09/2026 10:03:42 AM (IST). Late minutes: 23', '187.15.154.213', '2026-09-23 10:03:42'),
(418, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f8f0:b90b:866e:3c45', '2026-09-23 10:21:18'),
(419, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/09/2026 10:21:24 AM (IST). Late minutes: 41', '2405:201:6804:bcd8:f8f0:b90b:866e:3c45', '2026-09-23 10:21:24'),
(420, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5456:6f22:a2e3:7ea8', '2026-09-23 11:46:53'),
(421, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:eeb2:dd67:ee8a:1141:7bdd', '2026-09-23 12:16:32'),
(422, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5456:6f22:a2e3:7ea8', '2026-09-23 15:09:23'),
(423, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:327b:446c:a1ea:ca3e', '2026-09-24 09:54:27'),
(424, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-23, ID: 98) as full_day', '2401:4900:883c:eeb2:327b:446c:a1ea:ca3e', '2026-09-24 09:54:28'),
(425, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/09/2026 09:54:32 AM (IST). Late minutes: 14', '2401:4900:883c:eeb2:327b:446c:a1ea:ca3e', '2026-09-24 09:54:32'),
(426, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7428:8c74:2551:56a2', '2026-09-24 09:54:49'),
(427, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-23, ID: 96) as full_day', '2405:201:6804:bcd8:7428:8c74:2551:56a2', '2026-09-24 09:54:50'),
(428, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/09/2026 09:54:54 AM (IST). Late minutes: 14', '2405:201:6804:bcd8:7428:8c74:2551:56a2', '2026-09-24 09:54:54'),
(429, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-23, ID: 97) as full_day', '2405:201:6804:bcd8:7428:8c74:2551:56a2', '2026-09-24 09:54:59'),
(430, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-23, ID: 99) as half_day', '2405:201:6804:bcd8:7428:8c74:2551:56a2', '2026-09-24 09:54:59'),
(431, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:d75:7c2f:7fa2:217', '2026-09-24 10:04:28'),
(432, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/09/2026 10:04:58 AM (IST). Late minutes: 24', '2401:4900:883c:eeb2:d75:7c2f:7fa2:217', '2026-09-24 10:04:58'),
(433, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.222', '2026-09-24 16:58:33'),
(434, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7428:8c74:2551:56a2', '2026-09-24 16:58:42'),
(435, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:11cc:6e4:49c2:b95b', '2026-09-24 17:01:19'),
(436, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:d75:7c2f:7fa2:217', '2026-09-24 17:16:46'),
(437, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '112.110.53.10', '2026-09-25 09:39:57'),
(438, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/09/2026 09:40:05 AM (IST). Late minutes: 0', '112.110.53.10', '2026-09-25 09:40:05'),
(439, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:d539:786d:12f2:e6ee', '2026-09-25 09:42:52'),
(440, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-24, ID: 101) as full_day', '2405:201:6804:bcd8:d539:786d:12f2:e6ee', '2026-09-25 09:42:54'),
(441, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/09/2026 09:43:22 AM (IST). Late minutes: 3', '2405:201:6804:bcd8:d539:786d:12f2:e6ee', '2026-09-25 09:43:22'),
(442, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:d75:7c2f:7fa2:217', '2026-09-25 09:45:04'),
(443, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-24, ID: 102) as full_day', '2401:4900:883c:eeb2:d75:7c2f:7fa2:217', '2026-09-25 09:45:05'),
(444, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/09/2026 09:45:37 AM (IST). Late minutes: 5', '2401:4900:883c:eeb2:d75:7c2f:7fa2:217', '2026-09-25 09:45:37'),
(445, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3044:8e1d:8000::', '2026-09-25 09:45:37'),
(446, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/09/2026 09:46:57 AM (IST). Late minutes: 6', '2409:40d2:3044:8e1d:8000::', '2026-09-25 09:46:57'),
(447, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:e7a3:c87d:3bff:fec9:451c', '2026-09-25 09:51:17'),
(448, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-24, ID: 100) as full_day', '2409:40d2:3053:e7a3:c87d:3bff:fec9:451c', '2026-09-25 09:51:18'),
(449, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/09/2026 09:51:21 AM (IST). Late minutes: 11', '2409:40d2:3053:e7a3:c87d:3bff:fec9:451c', '2026-09-25 09:51:21'),
(450, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 11:09:15'),
(451, NULL, 'System', 'Task Completed', 'task', 9, 'Employee #26 () completed task: Connect and Test Pixel before running ads', NULL, NULL, '127.0.0.1', '2026-09-25 12:55:39'),
(452, NULL, 'System', 'Meeting Scheduled', 'meeting', 4, 'Meeting \'Maharani Website Discussion\' called for employee #9 (Abhay)', NULL, NULL, '127.0.0.1', '2026-09-25 12:55:39'),
(453, NULL, 'System', 'Task Completed', 'task', 26, 'Employee #9 (Abhay) completed task: Mobile Optimization', NULL, NULL, '127.0.0.1', '2026-09-25 12:58:40'),
(454, NULL, 'System', 'Meeting Scheduled', 'meeting', 5, 'Meeting \'Maharani Website Discussion\' called for employee #9 (Abhay)', NULL, NULL, '127.0.0.1', '2026-09-25 12:58:40'),
(455, NULL, 'System', 'Shift Completed', 'attendance', 104, 'Employee #9 (Abhay) ended shift at 12:58 PM', NULL, NULL, '127.0.0.1', '2026-09-25 12:58:40'),
(456, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/09/2026 01:35:31 PM (IST). Late minutes: 235', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 13:35:31'),
(457, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 25/09/2026 03:03:08 PM (IST). Status: half_day, Duration: 01h 27m 37s, Early Exit: 206m', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 15:03:08'),
(458, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5805:3c7f:7887:fce1', '2026-09-25 16:29:35'),
(459, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 16:49:42'),
(460, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 16:51:38'),
(461, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 25/09/2026 04:54:29 PM (IST). Late minutes: 434', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 16:54:29'),
(462, 4, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee stopped timer & completed task #1.', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 16:54:42'),
(463, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 25/09/2026 04:54:56 PM (IST). Status: half_day, Duration: 00h 00m 27s, Early Exit: 95m', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 16:54:56'),
(464, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 17:08:31'),
(465, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 17:09:39'),
(466, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5805:3c7f:7887:fce1', '2026-09-25 17:30:00'),
(467, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 17:47:29'),
(468, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 17:49:32'),
(469, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:41a6:3baf:8324:5d52', '2026-09-25 17:50:31'),
(470, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2c25:af2d::325d:b7f2', '2026-09-25 18:44:21'),
(471, 10, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 25/09/2026 06:44:31 PM (IST). Status: full_day, Duration: 09h 04m 26s, Early Exit: 0m', '2402:8100:2c25:af2d::325d:b7f2', '2026-09-25 18:44:31'),
(472, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3004:b543:8000::', '2026-09-26 10:00:57'),
(473, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-25, ID: 106) as full_day', '2409:40d2:3004:b543:8000::', '2026-09-26 10:00:59'),
(474, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/09/2026 10:01:01 AM (IST). Late minutes: 21', '2409:40d2:3004:b543:8000::', '2026-09-26 10:01:01'),
(475, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3046:84e4:c93:6ff:fe65:e266', '2026-09-26 10:08:53'),
(476, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-25, ID: 107) as full_day', '2409:40d2:3046:84e4:c93:6ff:fe65:e266', '2026-09-26 10:08:55'),
(477, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/09/2026 10:08:58 AM (IST). Late minutes: 28', '2409:40d2:3046:84e4:c93:6ff:fe65:e266', '2026-09-26 10:08:58');

-- --------------------------------------------------------

--
-- Table structure for table `activity_reasons`
--

CREATE TABLE `activity_reasons` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'general',
  `reason_text` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_reasons`
--

INSERT INTO `activity_reasons` (`id`, `category`, `reason_text`) VALUES
(1, 'waiting', 'Waiting for client content'),
(2, 'waiting', 'Waiting for manager approval'),
(3, 'waiting', 'Waiting for design'),
(4, 'waiting', 'Waiting for developer'),
(5, 'waiting', 'Waiting for credentials/access'),
(6, 'blocked', 'Server/hosting issue'),
(7, 'blocked', 'Technical issue'),
(8, 'blocked', 'Payment issue'),
(9, 'general', 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `shift_start` varchar(10) NOT NULL DEFAULT '09:40',
  `shift_end` varchar(10) NOT NULL DEFAULT '18:30',
  `clock_in` time DEFAULT NULL,
  `clock_out` time DEFAULT NULL,
  `late_minutes` int(11) DEFAULT 0,
  `early_exit_minutes` int(11) DEFAULT 0,
  `total_seconds` int(11) DEFAULT 0,
  `break_seconds` int(11) DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'not_started',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `user_id`, `date`, `shift_start`, `shift_end`, `clock_in`, `clock_out`, `late_minutes`, `early_exit_minutes`, `total_seconds`, `break_seconds`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 0, 9, '2026-08-19', '09:40', '18:30', '12:51:22', '18:33:28', 191, 0, 20526, 0, 'half_day', NULL, '2026-08-19 12:51:22', '2026-09-25 12:09:49'),
(2, 0, 8, '2026-08-19', '09:40', '18:30', '12:53:01', '18:33:09', 193, 0, 20408, 0, 'half_day', NULL, '2026-08-19 12:53:01', '2026-09-25 12:09:49'),
(3, 0, 6, '2026-08-19', '09:40', '18:30', '12:53:15', '18:33:15', 193, 0, 20400, 0, 'half_day', NULL, '2026-08-19 12:53:15', '2026-09-25 12:09:49'),
(4, 0, 7, '2026-08-20', '09:40', '18:30', '09:53:14', '10:27:07', 13, 482, 88433, 0, 'half_day', NULL, '2026-08-20 09:53:14', '2026-09-25 12:09:49'),
(5, 0, 8, '2026-08-20', '09:40', '18:30', '09:54:07', '09:52:05', 14, 517, 86278, 0, 'half_day', NULL, '2026-08-20 09:54:07', '2026-09-25 12:09:49'),
(6, 0, 9, '2026-08-20', '09:40', '18:30', '09:58:04', '18:31:34', 18, 0, 30810, 0, 'full_day', NULL, '2026-08-20 09:58:04', '2026-09-25 12:09:49'),
(7, 0, 6, '2026-08-20', '09:40', '18:30', '09:58:44', '18:30:03', 18, 0, 30679, 0, 'full_day', NULL, '2026-08-20 09:58:44', '2026-09-25 12:09:49'),
(8, 0, 6, '2026-08-21', '09:40', '18:30', '09:42:51', '19:00:00', 2, 0, 33429, 0, 'full_day', NULL, '2026-08-21 09:42:51', '2026-09-25 12:09:49'),
(9, 0, 8, '2026-08-21', '09:40', '18:30', '09:52:11', '19:00:00', 12, 0, 32869, 0, 'full_day', NULL, '2026-08-21 09:52:11', '2026-09-25 12:09:49'),
(10, 0, 9, '2026-08-21', '09:40', '18:30', '10:21:34', '19:00:00', 41, 0, 31106, 0, 'half_day', NULL, '2026-08-21 10:21:34', '2026-09-25 12:09:49'),
(11, 0, 7, '2026-08-21', '09:40', '18:30', '10:27:12', '19:00:00', 47, 0, 30768, 0, 'half_day', NULL, '2026-08-21 10:27:12', '2026-09-25 12:09:49'),
(12, 0, 10, '2026-08-21', '09:40', '18:30', '10:37:02', '19:00:00', 57, 0, 30178, 0, 'half_day', NULL, '2026-08-21 10:37:02', '2026-09-25 12:09:49'),
(13, 0, 6, '2026-08-24', '09:40', '18:30', '12:03:53', '19:00:00', 143, 0, 24967, 0, 'half_day', NULL, '2026-08-24 12:03:53', '2026-09-25 12:09:49'),
(14, 0, 9, '2026-08-24', '09:40', '18:30', '12:04:20', '19:00:00', 144, 0, 24940, 0, 'half_day', NULL, '2026-08-24 12:04:20', '2026-09-25 12:09:49'),
(15, 0, 9, '2026-08-25', '09:40', '18:30', '10:52:41', '18:29:29', 72, 0, 27408, 0, 'half_day', NULL, '2026-08-25 10:52:41', '2026-09-25 12:09:49'),
(16, 0, 6, '2026-08-25', '09:40', '18:30', '10:52:45', '19:00:00', 72, 0, 29235, 0, 'half_day', NULL, '2026-08-25 10:52:45', '2026-09-25 12:09:49'),
(17, 0, 9, '2026-08-26', '09:40', '18:30', '09:56:21', '18:30:34', 16, 0, 30853, 0, 'half_day', NULL, '2026-08-26 09:56:21', '2026-09-25 12:09:49'),
(18, 0, 7, '2026-08-26', '09:40', '18:30', '10:26:46', '19:00:00', 46, 0, 30794, 0, 'half_day', NULL, '2026-08-26 10:26:46', '2026-09-25 12:09:49'),
(19, 0, 8, '2026-08-27', '09:40', '18:30', '09:53:43', '19:00:00', 13, 0, 32777, 0, 'full_day', NULL, '2026-08-27 09:53:43', '2026-09-25 12:09:49'),
(20, 0, 6, '2026-08-27', '09:40', '18:30', '10:11:32', '19:00:00', 31, 0, 31708, 0, 'half_day', NULL, '2026-08-27 10:11:32', '2026-09-25 12:09:49'),
(21, 0, 9, '2026-08-27', '09:40', '18:30', '10:17:17', '19:00:00', 37, 0, 31363, 0, 'half_day', NULL, '2026-08-27 10:17:17', '2026-09-25 12:09:49'),
(22, 0, 7, '2026-08-27', '09:40', '18:30', '10:23:31', '19:00:00', 43, 0, 30989, 0, 'half_day', NULL, '2026-08-27 10:23:31', '2026-09-25 12:09:49'),
(23, 0, 6, '2026-08-29', '09:40', '18:30', '09:43:41', '19:00:00', 3, 0, 33379, 0, 'full_day', NULL, '2026-08-29 09:43:41', '2026-09-25 12:09:49'),
(24, 0, 9, '2026-08-29', '09:40', '18:30', '09:53:00', '19:00:00', 13, 0, 32820, 0, 'full_day', NULL, '2026-08-29 09:53:00', '2026-09-25 12:09:49'),
(25, 0, 8, '2026-08-29', '09:40', '18:30', '09:58:59', '19:00:00', 18, 0, 32461, 0, 'full_day', NULL, '2026-08-29 09:58:59', '2026-09-25 12:09:49'),
(26, 0, 7, '2026-08-29', '09:40', '18:30', '10:42:10', '19:00:00', 62, 0, 29870, 0, 'half_day', NULL, '2026-08-29 10:42:10', '2026-09-25 12:09:49'),
(27, 0, 8, '2026-08-31', '09:40', '18:30', '09:54:03', '19:00:00', 14, 0, 32757, 0, 'full_day', NULL, '2026-08-31 09:54:03', '2026-09-25 12:09:49'),
(28, 0, 6, '2026-08-31', '09:40', '18:30', '10:10:34', '19:00:00', 30, 0, 31766, 0, 'half_day', NULL, '2026-08-31 10:10:34', '2026-09-25 12:09:49'),
(29, 0, 9, '2026-08-31', '09:40', '18:30', '10:10:51', '19:00:00', 30, 0, 31749, 0, 'half_day', NULL, '2026-08-31 10:10:51', '2026-09-25 12:09:49'),
(30, 0, 4, '2026-09-01', '09:40', '18:30', '09:26:22', '19:00:00', 0, 0, 34418, 0, 'full_day', NULL, '2026-09-01 09:26:22', '2026-09-25 12:09:49'),
(31, 0, 7, '2026-09-01', '09:40', '18:30', '09:46:06', '19:00:00', 6, 0, 33234, 0, 'full_day', NULL, '2026-09-01 09:46:06', '2026-09-25 12:09:49'),
(32, 0, 6, '2026-09-01', '09:40', '18:30', '09:50:51', '19:00:00', 10, 0, 32949, 0, 'full_day', NULL, '2026-09-01 09:50:51', '2026-09-25 12:09:49'),
(33, 0, 8, '2026-09-01', '09:40', '18:30', '09:59:19', '19:00:00', 19, 0, 32441, 0, 'full_day', NULL, '2026-09-01 09:59:19', '2026-09-25 12:09:49'),
(34, 0, 9, '2026-09-01', '09:40', '18:30', '10:01:40', '18:25:30', 21, 4, 30230, 0, 'half_day', NULL, '2026-09-01 10:01:40', '2026-09-25 12:09:49'),
(35, 0, 4, '2026-09-02', '09:40', '18:30', '09:30:47', '19:00:00', 0, 0, 34153, 0, 'full_day', NULL, '2026-09-02 09:30:47', '2026-09-25 12:09:49'),
(36, 0, 7, '2026-09-02', '09:40', '18:30', '09:45:54', '19:00:00', 5, 0, 33246, 0, 'full_day', NULL, '2026-09-02 09:45:54', '2026-09-25 12:09:49'),
(37, 0, 9, '2026-09-02', '09:40', '18:30', '09:55:25', '19:00:00', 15, 0, 32675, 0, 'full_day', NULL, '2026-09-02 09:55:25', '2026-09-25 12:09:49'),
(38, 0, 6, '2026-09-02', '09:40', '18:30', '09:57:07', '19:00:00', 17, 0, 32573, 0, 'full_day', NULL, '2026-09-02 09:57:07', '2026-09-25 12:09:49'),
(39, 0, 8, '2026-09-02', '09:40', '18:30', '10:46:27', '19:00:00', 66, 0, 29613, 0, 'half_day', NULL, '2026-09-02 10:46:27', '2026-09-25 12:09:49'),
(40, 0, 4, '2026-09-03', '09:40', '18:30', '09:33:41', '19:00:00', 0, 0, 33979, 0, 'full_day', NULL, '2026-09-03 09:33:41', '2026-09-25 12:09:49'),
(41, 0, 7, '2026-09-03', '09:40', '18:30', '09:40:19', '19:00:00', 0, 0, 33581, 0, 'full_day', NULL, '2026-09-03 09:40:19', '2026-09-25 12:09:49'),
(42, 0, 6, '2026-09-03', '09:40', '18:30', '10:03:07', '19:00:00', 23, 0, 32213, 0, 'full_day', NULL, '2026-09-03 10:03:07', '2026-09-25 12:09:49'),
(43, 0, 9, '2026-09-03', '09:40', '18:30', '10:08:58', '19:00:00', 28, 0, 31862, 0, 'full_day', NULL, '2026-09-03 10:08:58', '2026-09-25 12:09:49'),
(44, 0, 9, '2026-09-04', '09:40', '18:30', '09:40:47', '19:00:00', 0, 0, 33553, 0, 'full_day', NULL, '2026-09-04 09:40:47', '2026-09-25 12:09:49'),
(45, 0, 6, '2026-09-04', '09:40', '18:30', '09:43:17', '19:00:00', 3, 0, 33403, 0, 'full_day', NULL, '2026-09-04 09:43:17', '2026-09-25 12:09:49'),
(46, 0, 7, '2026-09-04', '09:40', '18:30', '09:47:09', '19:00:00', 7, 0, 33171, 0, 'full_day', NULL, '2026-09-04 09:47:09', '2026-09-25 12:09:49'),
(47, 0, 8, '2026-09-04', '09:40', '18:30', '09:59:31', '19:00:00', 19, 0, 32429, 0, 'full_day', NULL, '2026-09-04 09:59:31', '2026-09-25 12:09:49'),
(48, 0, 7, '2026-09-05', '09:40', '18:30', '09:53:59', '19:00:00', 13, 0, 32761, 0, 'full_day', NULL, '2026-09-05 09:53:59', '2026-09-25 12:09:49'),
(49, 0, 9, '2026-09-05', '09:40', '18:30', '10:01:59', '19:00:00', 21, 0, 32281, 0, 'full_day', NULL, '2026-09-05 10:01:59', '2026-09-25 12:09:49'),
(50, 0, 8, '2026-09-05', '09:40', '18:30', '10:53:02', '19:00:00', 73, 0, 29218, 0, 'half_day', NULL, '2026-09-05 10:53:02', '2026-09-25 12:09:49'),
(51, 0, 9, '2026-09-07', '09:40', '18:30', '09:57:23', '19:00:00', 17, 0, 32557, 0, 'full_day', NULL, '2026-09-07 09:57:23', '2026-09-25 12:09:49'),
(52, 0, 6, '2026-09-07', '09:40', '18:30', '10:06:46', '19:00:00', 26, 0, 31994, 0, 'full_day', NULL, '2026-09-07 10:06:46', '2026-09-25 12:09:49'),
(53, 0, 6, '2026-09-08', '09:40', '18:30', '09:52:52', '19:00:00', 12, 0, 32828, 0, 'full_day', NULL, '2026-09-08 09:52:52', '2026-09-25 12:09:49'),
(54, 0, 8, '2026-09-08', '09:40', '18:30', '09:58:29', '19:00:00', 18, 0, 32491, 0, 'full_day', NULL, '2026-09-08 09:58:29', '2026-09-25 12:09:49'),
(55, 0, 9, '2026-09-09', '09:40', '18:30', '09:50:06', '19:00:00', 10, 0, 32994, 0, 'full_day', NULL, '2026-09-09 09:50:06', '2026-09-25 12:09:49'),
(56, 0, 8, '2026-09-09', '09:40', '18:30', '09:55:47', '19:00:00', 15, 0, 32653, 0, 'full_day', NULL, '2026-09-09 09:55:47', '2026-09-25 12:09:49'),
(57, 0, 6, '2026-09-09', '09:40', '18:30', '16:06:35', '19:00:00', 386, 0, 10405, 0, 'half_day', NULL, '2026-09-09 16:06:35', '2026-09-25 12:09:49'),
(58, 0, 9, '2026-09-10', '09:40', '18:30', '09:53:25', '19:00:00', 13, 0, 32795, 0, 'full_day', NULL, '2026-09-10 09:53:25', '2026-09-25 12:09:49'),
(59, 0, 8, '2026-09-10', '09:40', '18:30', '09:58:03', '19:00:00', 18, 0, 32517, 0, 'full_day', NULL, '2026-09-10 09:58:03', '2026-09-25 12:09:49'),
(60, 0, 7, '2026-09-10', '09:40', '18:30', '10:01:59', '19:00:00', 21, 0, 32281, 0, 'full_day', NULL, '2026-09-10 10:01:59', '2026-09-25 12:09:49'),
(61, 0, 6, '2026-09-10', '09:40', '18:30', '10:03:20', '19:00:00', 23, 0, 32200, 0, 'full_day', NULL, '2026-09-10 10:03:20', '2026-09-25 12:09:49'),
(62, 0, 7, '2026-09-11', '09:40', '18:30', '09:48:11', '19:00:00', 8, 0, 33109, 0, 'full_day', NULL, '2026-09-11 09:48:11', '2026-09-25 12:09:49'),
(63, 0, 9, '2026-09-11', '09:40', '18:30', '09:57:29', '19:00:00', 17, 0, 32551, 0, 'full_day', NULL, '2026-09-11 09:57:29', '2026-09-25 12:09:49'),
(64, 0, 8, '2026-09-11', '09:40', '18:30', '10:02:26', '19:00:00', 22, 0, 32254, 0, 'full_day', NULL, '2026-09-11 10:02:26', '2026-09-25 12:09:49'),
(65, 0, 6, '2026-09-11', '09:40', '18:30', '10:08:24', '19:00:00', 28, 0, 31896, 0, 'full_day', NULL, '2026-09-11 10:08:24', '2026-09-25 12:09:49'),
(66, 0, 6, '2026-09-12', '09:40', '18:30', '09:52:32', '19:00:00', 12, 0, 32848, 0, 'full_day', NULL, '2026-09-12 09:52:32', '2026-09-25 12:09:49'),
(67, 0, 7, '2026-09-12', '09:40', '18:30', '09:55:11', '19:00:00', 15, 0, 32689, 0, 'full_day', NULL, '2026-09-12 09:55:11', '2026-09-25 12:09:49'),
(68, 0, 9, '2026-09-12', '09:40', '18:30', '10:03:52', '19:00:00', 23, 0, 32168, 0, 'full_day', NULL, '2026-09-12 10:03:52', '2026-09-25 12:09:49'),
(69, 0, 7, '2026-09-14', '09:40', '18:30', '10:02:37', '19:00:00', 22, 0, 32243, 0, 'full_day', NULL, '2026-09-14 10:02:37', '2026-09-25 12:09:49'),
(70, 0, 8, '2026-09-14', '09:40', '18:30', '10:04:40', '18:29:06', 24, 0, 30266, 0, 'half_day', NULL, '2026-09-14 10:04:40', '2026-09-25 12:09:49'),
(71, 0, 6, '2026-09-14', '09:40', '18:30', '10:12:19', '19:00:00', 32, 0, 31661, 0, 'half_day', NULL, '2026-09-14 10:12:19', '2026-09-25 12:09:49'),
(72, 0, 9, '2026-09-14', '09:40', '18:30', '10:16:25', '19:00:00', 36, 0, 31415, 0, 'half_day', NULL, '2026-09-14 10:16:25', '2026-09-25 12:09:49'),
(73, 0, 7, '2026-09-15', '09:40', '18:30', '09:52:46', '19:00:00', 12, 0, 32834, 0, 'full_day', NULL, '2026-09-15 09:52:46', '2026-09-25 12:09:49'),
(74, 0, 6, '2026-09-15', '09:40', '18:30', '09:55:51', '19:00:00', 15, 0, 32649, 0, 'full_day', NULL, '2026-09-15 09:55:51', '2026-09-25 12:09:49'),
(75, 0, 8, '2026-09-15', '09:40', '18:30', '09:58:49', '19:00:00', 18, 0, 32471, 0, 'full_day', NULL, '2026-09-15 09:58:49', '2026-09-25 12:09:49'),
(76, 0, 9, '2026-09-15', '09:40', '18:30', '10:03:30', '19:00:00', 23, 0, 32190, 0, 'full_day', NULL, '2026-09-15 10:03:30', '2026-09-25 12:09:49'),
(77, 0, 7, '2026-09-16', '09:40', '18:30', '09:50:21', '19:00:00', 10, 0, 32979, 0, 'full_day', NULL, '2026-09-16 09:50:21', '2026-09-25 12:09:49'),
(78, 0, 9, '2026-09-16', '09:40', '18:30', '09:52:28', '19:00:00', 12, 0, 32852, 0, 'full_day', NULL, '2026-09-16 09:52:28', '2026-09-25 12:09:49'),
(79, 0, 6, '2026-09-16', '09:40', '18:30', '09:54:53', '19:00:00', 14, 0, 32707, 0, 'full_day', NULL, '2026-09-16 09:54:53', '2026-09-25 12:09:49'),
(80, 0, 8, '2026-09-16', '09:40', '18:30', '09:58:03', '19:00:00', 18, 0, 32517, 0, 'full_day', NULL, '2026-09-16 09:58:03', '2026-09-25 12:09:49'),
(81, 0, 8, '2026-09-17', '09:40', '18:30', '09:59:39', '19:00:00', 19, 0, 32421, 0, 'full_day', NULL, '2026-09-17 09:59:39', '2026-09-25 12:09:49'),
(82, 0, 6, '2026-09-17', '09:40', '18:30', '10:04:56', '19:00:00', 24, 0, 32104, 0, 'full_day', NULL, '2026-09-17 10:04:56', '2026-09-25 12:09:49'),
(83, 0, 9, '2026-09-17', '09:40', '18:30', '10:18:15', '19:00:00', 38, 0, 31305, 0, 'half_day', NULL, '2026-09-17 10:18:15', '2026-09-25 12:09:49'),
(84, 0, 6, '2026-09-18', '09:40', '18:30', '10:03:55', '19:00:00', 23, 0, 32165, 0, 'full_day', NULL, '2026-09-18 10:03:55', '2026-09-25 12:09:49'),
(85, 0, 9, '2026-09-18', '09:40', '18:30', '10:16:58', '19:00:00', 36, 0, 31382, 0, 'half_day', NULL, '2026-09-18 10:16:58', '2026-09-25 12:09:49'),
(86, 0, 8, '2026-09-18', '09:40', '18:30', '10:33:47', '18:30:20', 53, 0, 28593, 0, 'half_day', NULL, '2026-09-18 10:33:47', '2026-09-25 12:09:49'),
(87, 0, 6, '2026-09-19', '09:40', '18:30', '09:44:04', '19:00:00', 4, 0, 33356, 0, 'full_day', NULL, '2026-09-19 09:44:04', '2026-09-25 12:09:49'),
(88, 0, 8, '2026-09-19', '09:40', '18:30', '10:02:26', '19:00:00', 22, 0, 32254, 0, 'full_day', NULL, '2026-09-19 10:02:26', '2026-09-25 12:09:49'),
(89, 0, 9, '2026-09-19', '09:40', '18:30', '10:10:54', '19:00:00', 30, 0, 31746, 0, 'half_day', NULL, '2026-09-19 10:10:54', '2026-09-25 12:09:49'),
(90, 0, 8, '2026-09-21', '09:40', '18:30', '09:56:47', '19:00:00', 16, 0, 32593, 0, 'full_day', NULL, '2026-09-21 09:56:47', '2026-09-25 12:09:49'),
(91, 0, 9, '2026-09-21', '09:40', '18:30', '10:10:02', '19:00:00', 30, 0, 31798, 0, 'half_day', NULL, '2026-09-21 10:10:02', '2026-09-25 12:09:49'),
(92, 0, 6, '2026-09-21', '09:40', '18:30', '10:15:13', '19:00:00', 35, 0, 31487, 0, 'half_day', NULL, '2026-09-21 10:15:13', '2026-09-25 12:09:49'),
(93, 0, 6, '2026-09-22', '09:40', '18:30', '09:40:00', '19:00:00', 0, 0, 33600, 0, 'full_day', NULL, '2026-09-22 09:40:00', '2026-09-25 12:09:49'),
(94, 0, 9, '2026-09-22', '09:40', '18:30', '09:52:02', '19:00:00', 12, 0, 32878, 0, 'full_day', NULL, '2026-09-22 09:52:02', '2026-09-25 12:09:49'),
(95, 0, 8, '2026-09-22', '09:40', '18:30', '09:58:09', '19:00:00', 18, 0, 32511, 0, 'full_day', NULL, '2026-09-22 09:58:09', '2026-09-25 12:09:49'),
(96, 0, 9, '2026-09-23', '09:40', '18:30', '09:46:03', '19:00:00', 6, 0, 33237, 0, 'full_day', NULL, '2026-09-23 09:46:03', '2026-09-25 12:09:49'),
(97, 0, 8, '2026-09-23', '09:40', '18:30', '09:55:21', '19:00:00', 15, 0, 32679, 0, 'full_day', NULL, '2026-09-23 09:55:21', '2026-09-25 12:09:49'),
(98, 0, 6, '2026-09-23', '09:40', '18:30', '10:03:42', '19:00:00', 23, 0, 32178, 0, 'full_day', NULL, '2026-09-23 10:03:42', '2026-09-25 12:09:49'),
(99, 0, 7, '2026-09-23', '09:40', '18:30', '10:21:24', '19:00:00', 41, 0, 31116, 0, 'half_day', NULL, '2026-09-23 10:21:24', '2026-09-25 12:09:49'),
(100, 0, 6, '2026-09-24', '09:40', '18:30', '09:54:32', '19:00:00', 14, 0, 32728, 0, 'full_day', NULL, '2026-09-24 09:54:32', '2026-09-25 12:09:49'),
(101, 0, 9, '2026-09-24', '09:40', '18:30', '09:54:54', '19:00:00', 14, 0, 32706, 0, 'full_day', NULL, '2026-09-24 09:54:54', '2026-09-25 12:09:49'),
(102, 0, 8, '2026-09-24', '09:40', '18:30', '10:04:58', '19:00:00', 24, 0, 32102, 0, 'full_day', NULL, '2026-09-24 10:04:58', '2026-09-25 12:09:49'),
(103, 0, 10, '2026-09-25', '09:40', '18:30', '09:40:05', '18:44:31', 0, 0, 32666, 0, 'full_day', NULL, '2026-09-25 09:40:05', '2026-09-25 18:44:31'),
(104, 0, 9, '2026-09-25', '09:40', '18:30', '09:43:22', NULL, 3, 0, 0, 0, 'late', NULL, '2026-09-25 09:43:22', '2026-09-25 12:09:49'),
(105, 0, 8, '2026-09-25', '09:40', '18:30', '09:45:37', NULL, 5, 0, 0, 0, 'late', NULL, '2026-09-25 09:45:37', '2026-09-25 12:09:49'),
(106, 0, 7, '2026-09-25', '09:40', '18:30', '09:46:57', '19:00:00', 6, 0, 33183, 0, 'full_day', NULL, '2026-09-25 09:46:57', '2026-09-26 10:00:59'),
(107, 0, 6, '2026-09-25', '09:40', '18:30', '09:51:21', '19:00:00', 11, 0, 32919, 0, 'full_day', NULL, '2026-09-25 09:51:21', '2026-09-26 10:08:55'),
(110, 0, 7, '2026-09-26', '09:40', '18:30', '10:01:01', NULL, 21, 0, 0, 0, 'late', NULL, '2026-09-26 10:01:01', '2026-09-26 10:01:01'),
(111, 0, 6, '2026-09-26', '09:40', '18:30', '10:08:58', NULL, 28, 0, 0, 0, 'late', NULL, '2026-09-26 10:08:58', '2026-09-26 10:08:58');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_breaks`
--

CREATE TABLE `attendance_breaks` (
  `id` int(11) NOT NULL,
  `attendance_id` int(11) NOT NULL,
  `break_start` datetime NOT NULL,
  `break_end` datetime DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT 0,
  `break_type` varchar(50) DEFAULT 'Lunch'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `reply_to_id` int(11) DEFAULT NULL,
  `is_seen` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `client_code` varchar(50) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `contact_person` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `alt_mobile` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `pincode` varchar(20) DEFAULT NULL,
  `gstin` varchar(30) DEFAULT NULL,
  `pan` varchar(20) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `client_code`, `name`, `company_name`, `contact_person`, `email`, `mobile`, `alt_mobile`, `address`, `city`, `state`, `country`, `pincode`, `gstin`, `pan`, `website`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 'KD-CL-0001', 'Acme Global Technologies', 'Acme Global Pvt Ltd', 'Johnathan Carter', 'billing@acmeglobal.com', '+91 98111 22334', '+91 98111 22335', 'Tower A, 5th Floor, Cyber Hub, DLF Phase 2', 'Gurugram', 'Haryana', 'India', '122002', '06AABCA1234A1Z5', 'AABCA1234A', 'https://acmeglobal.com', 'Enterprise web client', 'active', '2026-09-12 10:04:16', '2026-09-12 10:04:16'),
(2, 'KD-CL-0002', 'Zenith Retail Solutions', 'Zenith Retails LLP', 'Rohan Mehta', 'accounts@zenithretail.in', '+91 98222 33445', '', '304, Business Arcade, Nariman Point', 'Mumbai', 'Maharashtra', 'India', '400021', '27AABCZ5678B1Z2', 'AABCZ5678B', 'https://zenithretail.in', 'Ecommerce SEO and PPC', 'active', '2026-09-12 10:04:16', '2026-09-12 10:04:16'),
(3, 'KD-CL-0003', 'Apex Logistics India', 'Apex Global Logistics', 'Priya Nair', 'priya@apexlogistics.com', '+91 98333 44556', '', 'Plot 88, Electronic City Phase 1', 'Bengaluru', 'Karnataka', 'India', '560100', '29AABCA9012C1Z8', 'AABCA9012C', 'https://apexlogistics.com', 'SMM retainership client', 'active', '2026-09-12 10:04:16', '2026-09-12 10:04:16'),
(4, 'KD-CL-0004', 'Starlight Healthcare & Labs', 'Starlight Medicare Group', 'Dr. Arvind Sharma', 'arvind@starlightlabs.com', '+91 98444 55667', '', '14, Barakhamba Road, Connaught Place', 'New Delhi', 'Delhi', 'India', '110001', '07AABCS3456D1Z1', 'AABCS3456D', 'https://starlightlabs.com', 'Local SEO & Brand Identity', 'active', '2026-09-12 10:04:16', '2026-09-12 10:04:16'),
(5, 'KD-CL-0005', 'Krafting Test Client', 'Test Digital Agency Partner', 'Alex Morgan', 'alex@testclient.com', '+91 98765 11111', '', '100 Cyber City, Sector 29', 'New Delhi', 'Delhi', 'India', '110001', '07AAACT1234E1Z9', 'AAACT1234E', 'https://testclient.com', 'Standard test account', 'active', '2026-09-12 10:04:16', '2026-09-12 10:04:16'),
(6, 'KD-CL-0006', 'Kashmiri Sons', 'Kashmiri Sons', 'Kashmiri Sons', '', '9756609350', '', 'Place of Supply: Uttarakhand', 'Dehradun', 'Uttarakhand', 'India', '248001', '', '', '', 'Client from Invoice #386', 'active', '2026-09-25 12:31:10', '2026-09-25 12:31:10'),
(7, 'KD-CL-0007', 'Narang Ethnics', 'Narang Ethnics', 'Narang Ethnics', '', '9997244000', '', 'Rajpur Road Dehradun, Dehradun, Uttarakhand, 248001', 'Dehradun', 'Uttarakhand', 'India', '248001', '', '', '', 'Client from Invoice #394', 'active', '2026-09-25 12:31:10', '2026-09-25 12:31:10'),
(8, 'KD-CL-0008', 'Turmeric LANGFORD', 'Turmeric LANGFORD', 'Turmeric LANGFORD', '', '', '', 'Langford Canada, langford', 'Langford', 'Uttarakhand', 'Canada', '', '', '', '', 'Client from Invoice #362', 'active', '2026-09-25 12:31:10', '2026-09-25 12:31:10'),
(9, 'KD-CL-0009', 'Jeevan Sankalp', 'Jeevan Sankalp', 'Jeevan Sankalp', '', '', '', 'Place of Supply: Uttarakhand', 'Dehradun', 'Uttarakhand', 'India', '248001', '', '', '', 'Client from Invoice #407', 'active', '2026-09-25 12:31:10', '2026-09-25 12:31:10'),
(10, 'KD-CL-0010', 'MJ Aerosuites', 'MJ Aerosuites', 'MJ Aerosuites', '', '', '', 'Place of Supply: Uttarakhand', 'Dehradun', 'Uttarakhand', 'India', '248001', '', '', '', 'Client from Invoice #399', 'active', '2026-09-25 12:31:10', '2026-09-25 12:31:10'),
(11, 'KD-CL-0011', 'MJ Grand Inn & Zion', 'MJ Grand Inn & Zion', 'MJ Grand Inn & Zion', '', '', '', 'Place of Supply: Uttarakhand', 'Dehradun', 'Uttarakhand', 'India', '248001', '', '', '', 'Client from Invoice #400', 'active', '2026-09-25 12:31:10', '2026-09-25 12:31:10'),
(12, 'KD-CL-0012', 'MJ Residency', 'MJ Residency', 'MJ Residency', '', '', '', 'Place of Supply: Uttarakhand', 'Dehradun', 'Uttarakhand', 'India', '248001', '', '', '', 'Client from Invoice #397', 'active', '2026-09-25 12:31:10', '2026-09-25 12:31:10'),
(13, 'KD-CL-0013', 'MJ Properties', 'MJ Properties', 'MJ Properties', '', '', '', 'Place of Supply: Uttarakhand', 'Dehradun', 'Uttarakhand', 'India', '248001', '', '', '', 'Client from Invoice #398', 'active', '2026-09-25 12:31:10', '2026-09-25 12:31:10');

-- --------------------------------------------------------

--
-- Table structure for table `daily_work_reports`
--

CREATE TABLE `daily_work_reports` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `shift_start` varchar(10) DEFAULT '09:40',
  `shift_end` varchar(10) DEFAULT '18:30',
  `actual_clock_in` datetime DEFAULT NULL,
  `actual_clock_out` datetime DEFAULT NULL,
  `working_seconds` int(11) DEFAULT 0,
  `meeting_seconds` int(11) DEFAULT 0,
  `lunch_seconds` int(11) DEFAULT 0,
  `waiting_seconds` int(11) DEFAULT 0,
  `blocked_seconds` int(11) DEFAULT 0,
  `tasks_completed` int(11) DEFAULT 0,
  `tasks_in_progress` int(11) DEFAULT 0,
  `projects_summary` text DEFAULT NULL,
  `issues_summary` text DEFAULT NULL,
  `task_details` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_work_reports`
--

INSERT INTO `daily_work_reports` (`id`, `employee_id`, `report_date`, `shift_start`, `shift_end`, `actual_clock_in`, `actual_clock_out`, `working_seconds`, `meeting_seconds`, `lunch_seconds`, `waiting_seconds`, `blocked_seconds`, `tasks_completed`, `tasks_in_progress`, `projects_summary`, `issues_summary`, `task_details`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-09-25', '09:40', '18:30', '2026-09-25 09:40:00', NULL, 21900, 4500, 3600, 600, 0, 4, 2, '[\"Maharani Website\", \"Krafting Digital\", \"Casa Tupka\"]', 'Waiting for client content on product page banners', 'Mobile Optimization (in progress), Checkout UX Redesign (completed)', 'Completed morning sprint items, working on mobile menu optimization.', '2026-09-25 11:34:02', '2026-09-25 11:34:02'),
(2, 9, '2026-09-25', '09:40', '18:30', '0000-00-00 00:00:00', '2026-09-25 12:58:40', 7503, 0, 0, 0, 0, 1, 1, '[\"Office Work Management Portal\",\"Maharani Website\"]', 'Working on Kanban Drag & Drop Task Board; Waiting for client content', NULL, NULL, '2026-09-25 12:58:40', '2026-09-25 12:58:40');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `description`, `created_at`) VALUES
(1, 'Administration', 'ADM', 'Executive leadership and company administration', '2026-08-19 07:09:49'),
(2, 'Development', 'DEV', 'Software engineering and web application development', '2026-08-19 07:09:49'),
(3, 'SEO', 'SEO', 'Search engine optimization and digital marketing', '2026-08-19 07:09:49'),
(4, 'Design', 'DSG', 'UI/UX design and creative graphics', '2026-08-19 07:09:49'),
(5, 'Social Media', 'SMM', 'Social media marketing and branding', '2026-08-19 07:09:49'),
(6, 'Product Management', 'PMO', 'Product roadmap, sprints, and feature planning', '2026-08-19 07:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `email_logs`
--

CREATE TABLE `email_logs` (
  `id` int(11) NOT NULL,
  `record_type` varchar(50) NOT NULL DEFAULT 'invoice',
  `record_id` int(11) NOT NULL,
  `sent_to` varchar(150) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'sent',
  `error_message` text DEFAULT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_code` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `department` varchar(100) NOT NULL,
  `designation` varchar(150) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default_avatar.png',
  `current_status` varchar(50) NOT NULL DEFAULT 'offline',
  `current_activity_id` int(11) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `employee_code`, `name`, `email`, `phone`, `department`, `designation`, `avatar`, `current_status`, `current_activity_id`, `joining_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'KD-EMP-001', 'Kartikeyan', 'kartikeyan@kraftingdigital.com', '+91 9876543210', 'Administration', 'System Administrator & CEO', NULL, 'working', NULL, '2024-01-01', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07'),
(2, 2, 'KD-EMP-002', 'Mrinali', 'mrinali@kraftingdigital.com', '+91 9876543211', 'Administration', 'Operations Manager', NULL, 'working', NULL, '2024-01-15', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07'),
(3, 3, 'KD-EMP-003', 'Udit', 'contact@kraftingdigital.com', '+91 9876543212', 'Product Management', 'Product Manager', NULL, 'working', NULL, '2024-02-01', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07'),
(4, 4, 'KD-EMP-004', 'Rahul', 'finance@kraftingdigital.com', '+91 9876543213', 'Development', 'Senior Fullstack Developer', NULL, 'working', NULL, '2024-02-10', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07'),
(5, 5, 'KD-EMP-005', 'Uvaish', 'uvaish@kraftingdigital.com', '+91 9876543214', 'SEO', 'SEO Lead Manager', NULL, 'meeting', NULL, '2024-03-01', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07'),
(6, 6, 'KD-EMP-006', 'Shivanshu', 'shivanshu@kraftingdigital.com', '+91 9876543215', 'SEO', 'Senior SEO Executive', NULL, 'working', NULL, '2024-03-15', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07'),
(7, 7, 'KD-EMP-007', 'Sarthak', 'sarthak@kraftingdigital.com', '+91 9876543216', 'Design', 'Lead UI/UX Graphic Designer', NULL, 'working', NULL, '2024-04-01', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07'),
(8, 8, 'KD-EMP-008', 'Priyansh', 'priyansh@kraftingdigital.com', '+91 9876543217', 'Social Media', 'Social Media Strategist', NULL, 'waiting', NULL, '2024-04-10', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07'),
(9, 9, 'KD-EMP-009', 'Abhay', 'abhay@kraftingdigital.com', '+91 9876543218', 'Development', 'Intern Frontend Developer', NULL, 'shift_ended', NULL, '2024-05-01', 'active', '2026-09-25 12:42:07', '2026-09-25 12:58:40'),
(10, 10, 'KD-EMP-010', 'Aman', 'aman@kraftingdigital.com', '+91 9876543219', 'SEO', 'Intern SEO Analyst', NULL, 'working', NULL, '2024-05-15', 'active', '2026-09-25 12:42:07', '2026-09-25 12:42:07');

-- --------------------------------------------------------

--
-- Table structure for table `employee_activities`
--

CREATE TABLE `employee_activities` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `activity_type` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT 0,
  `reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_activities`
--

INSERT INTO `employee_activities` (`id`, `employee_id`, `project_id`, `task_id`, `activity_type`, `status`, `start_time`, `end_time`, `duration`, `reason`, `notes`, `created_at`, `updated_at`) VALUES
(26, 9, 1, 3, 'task', 'completed', '2026-09-25 09:45:00', '2026-09-25 12:50:16', 7500, 'Working on Kanban Drag & Drop Task Board', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 4, 1, 1, 'task', 'active', '2026-09-25 09:40:00', NULL, 7800, 'Implementing Glassmorphic UI', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 7, 3, 6, 'task', 'active', '2026-09-25 09:50:00', NULL, 7200, 'Designing Dark Mode Color Palette', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 5, 2, 4, 'meeting', 'active', '2026-09-25 11:30:00', NULL, 1200, 'SEO Strategy review with Client', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 6, 2, 4, 'task', 'active', '2026-09-25 10:00:00', NULL, 6600, 'Technical SEO site audit', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(31, 8, 4, 7, 'waiting', 'active', '2026-09-25 11:15:00', NULL, 2100, 'Waiting for design creative approval', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(32, 9, 5, 26, 'Development', 'completed', '2026-09-25 15:15:00', '2026-09-25 12:54:19', 0, NULL, 'Mobile optimization, touch targets and cart drawer speed', '2026-09-25 12:50:16', '2026-09-25 12:54:19'),
(33, 9, 5, 26, 'Development', 'completed', '2026-09-25 12:54:19', '2026-09-25 12:54:20', 1, NULL, 'Developing responsive touch drawer', '2026-09-25 12:54:19', '2026-09-25 12:54:20'),
(34, 9, 5, 26, 'Client Communication', 'completed', '2026-09-25 12:54:20', '2026-09-25 12:54:20', 0, NULL, 'Discussed responsive changes with client', '2026-09-25 12:54:20', '2026-09-25 12:54:20'),
(35, 9, 5, 26, 'Waiting for Content', 'completed', '2026-09-25 12:54:20', '2026-09-25 12:54:20', 0, 'Waiting for client content', 'Waiting for final high-res mobile banner images', '2026-09-25 12:54:20', '2026-09-25 12:54:20'),
(36, 9, NULL, NULL, 'Meeting', 'completed', '2026-09-25 12:54:20', '2026-09-25 12:54:20', 0, NULL, 'In Meeting: Maharani Website Discussion', '2026-09-25 12:54:20', '2026-09-25 12:54:20'),
(37, 9, 5, 26, 'Development', 'completed', '2026-09-25 12:55:38', '2026-09-25 12:55:39', 1, NULL, 'Developing responsive touch drawer', '2026-09-25 12:55:38', '2026-09-25 12:55:39'),
(38, 9, 5, 26, 'Client Communication', 'completed', '2026-09-25 12:55:39', '2026-09-25 12:55:39', 0, NULL, 'Discussed responsive changes with client', '2026-09-25 12:55:39', '2026-09-25 12:55:39'),
(39, 9, 5, 26, 'Waiting for Content', 'completed', '2026-09-25 12:55:39', '2026-09-25 12:55:39', 0, 'Waiting for client content', 'Waiting for final high-res mobile banner images', '2026-09-25 12:55:39', '2026-09-25 12:55:39'),
(40, 9, NULL, NULL, 'Meeting', 'completed', '2026-09-25 12:55:39', '2026-09-25 12:55:39', 0, NULL, 'In Meeting: Maharani Website Discussion', '2026-09-25 12:55:39', '2026-09-25 12:55:39'),
(41, 9, 5, 26, 'Development', 'completed', '2026-09-25 12:58:39', '2026-09-25 12:58:40', 1, NULL, 'Developing responsive touch drawer', '2026-09-25 12:58:39', '2026-09-25 12:58:40'),
(42, 9, 5, 26, 'Client Communication', 'completed', '2026-09-25 12:58:40', '2026-09-25 12:58:40', 0, NULL, 'Discussed responsive changes with client', '2026-09-25 12:58:40', '2026-09-25 12:58:40'),
(43, 9, 5, 26, 'Waiting for Content', 'completed', '2026-09-25 12:58:40', '2026-09-25 12:58:40', 0, 'Waiting for client content', 'Waiting for final high-res mobile banner images', '2026-09-25 12:58:40', '2026-09-25 12:58:40'),
(44, 9, NULL, NULL, 'Meeting', 'completed', '2026-09-25 12:58:40', '2026-09-25 12:58:40', 0, NULL, 'In Meeting: Maharani Website Discussion', '2026-09-25 12:58:40', '2026-09-25 12:58:40');

-- --------------------------------------------------------

--
-- Table structure for table `employee_profiles`
--

CREATE TABLE `employee_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joining_date` date NOT NULL,
  `skills` text DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `documents_json` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_profiles`
--

INSERT INTO `employee_profiles` (`id`, `user_id`, `joining_date`, `skills`, `experience`, `address`, `emergency_contact`, `bio`, `documents_json`, `created_at`) VALUES
(1, 1, '2024-01-01', 'PHP 8, Architecture, MySQL, Security, Product Design', '10 Years', NULL, NULL, 'Founding Admin & System Architect', NULL, '2026-08-19 07:09:49'),
(2, 2, '2024-01-15', 'Team Operations, Agile Management, Workflow Auditing', '7 Years', NULL, NULL, 'Operations Lead managing day-to-day work metrics', NULL, '2026-08-19 07:09:49'),
(3, 3, '2024-02-01', 'Product Roadmap, Sprint Planning, Jira Workflow', '6 Years', NULL, NULL, 'Product Manager leading SaaS feature sprints', NULL, '2026-08-19 07:09:49'),
(4, 4, '2024-02-10', 'PHP 8, Laravel, Vue.js, MySQL, REST APIs, Microservices', '5 Years', NULL, NULL, 'Senior Developer leading backend architecture', NULL, '2026-08-19 07:09:49'),
(5, 5, '2024-03-01', 'Technical SEO, Ahrefs, SEMrush, Schema Markup', '5 Years', NULL, NULL, 'SEO Specialist focusing on organic growth', NULL, '2026-08-19 07:09:49'),
(6, 6, '2024-03-15', 'On-Page SEO, Link Building, Keyword Strategy', '3 Years', NULL, NULL, 'SEO Executive executing search optimization campaigns', NULL, '2026-08-19 07:09:49'),
(7, 7, '2024-04-01', 'Figma, Adobe XD, Illustrator, Glassmorphism UI', '4 Years', NULL, NULL, 'Creative UI/UX Designer crafting premium design systems', NULL, '2026-08-19 07:09:49'),
(8, 8, '2024-04-10', 'Content Strategy, Meta Ads, Brand Management', '4 Years', NULL, NULL, 'Social Media Lead growing online engagement', NULL, '2026-08-19 07:09:49'),
(9, 9, '2024-05-01', 'HTML5, CSS3, JavaScript, PHP basics', '6 Months', NULL, NULL, 'Enthusiastic Developer Intern', NULL, '2026-08-19 07:09:49'),
(10, 10, '2024-05-15', 'Directory Submissions, Social Bookmarking', '3 Months', NULL, NULL, 'Dedicated SEO Intern learning search analytics', NULL, '2026-08-19 07:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `employee_salaries`
--

CREATE TABLE `employee_salaries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `monthly_salary` decimal(12,2) DEFAULT 0.00,
  `allowance` decimal(12,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_salaries`
--

INSERT INTO `employee_salaries` (`id`, `user_id`, `monthly_salary`, `allowance`, `created_at`, `updated_at`) VALUES
(1, 1, 50000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(2, 2, 25000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(3, 3, 20000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(4, 4, 30000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(5, 5, 25000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(6, 6, 10000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(7, 7, 10000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(8, 8, 15000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(9, 9, 5000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(10, 10, 7000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `vendor` varchar(150) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'Other',
  `description` text DEFAULT NULL,
  `date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_mode` varchar(50) NOT NULL DEFAULT 'ONLINE',
  `notes` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `date` date NOT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(50) DEFAULT 'company'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `title`, `date`, `description`, `type`) VALUES
(1, 'Independence Day', '2026-08-15', 'National Holiday', 'national'),
(2, 'Gandhi Jayanti', '2026-10-02', 'National Holiday', 'national'),
(3, 'Diwali Festival', '2026-11-08', 'Company Holiday', 'company'),
(4, 'New Year Day', '2026-01-01', 'Company Holiday', 'company');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `financial_year` varchar(20) NOT NULL,
  `client_id` int(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(20) NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_type` varchar(20) DEFAULT 'GST',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 18.00,
  `cgst_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sgst_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `igst_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `additional_charges` decimal(12,2) NOT NULL DEFAULT 0.00,
  `round_off` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'unpaid',
  `template` varchar(50) NOT NULL DEFAULT 'modern',
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `public_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `service_type` varchar(50) NOT NULL DEFAULT 'STANDARD',
  `service_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 18.00,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `leave_type` varchar(50) DEFAULT 'paid',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` decimal(4,1) DEFAULT 1.0,
  `paid_days` decimal(4,1) DEFAULT 0.0,
  `unpaid_days` decimal(4,1) DEFAULT 0.0,
  `reason` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `meeting_date` date NOT NULL,
  `start_time` varchar(10) NOT NULL,
  `end_time` varchar(10) DEFAULT NULL,
  `duration` int(11) DEFAULT 30,
  `duration_minutes` int(11) DEFAULT 30,
  `meeting_time` varchar(10) DEFAULT NULL,
  `meeting_link` varchar(255) DEFAULT NULL,
  `location_link` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `response_notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `created_by`, `employee_id`, `title`, `description`, `meeting_date`, `start_time`, `end_time`, `duration`, `duration_minutes`, `meeting_time`, `meeting_link`, `location_link`, `status`, `response_notes`, `created_at`, `updated_at`) VALUES
(1, 1, 9, 'Maharani Website Discussion', 'Review mobile optimization progress and touch targets', '2026-09-25', '16:30', '17:00', 30, NULL, NULL, 'https://meet.google.com/krd-meet-mhr', NULL, 'pending', NULL, '2026-09-25 11:34:02', '2026-09-25 11:34:02'),
(2, 7, 2, 'UI/UX Design Review', 'Cart drawer & checkout flow Figma review', '2026-09-25', '11:15', '11:45', 30, NULL, NULL, 'https://meet.google.com/krd-meet-ux', NULL, 'accepted', NULL, '2026-09-25 11:34:02', '2026-09-25 11:34:02'),
(3, 1, 9, 'Maharani Website Discussion', 'Review mobile optimization progress and touch targets', '2026-09-25', '16:30', NULL, 30, NULL, NULL, 'https://meet.google.com/krd-meet-mhr', NULL, 'completed', 'Joining on Google Meet', '2026-09-25 12:54:20', '2026-09-25 12:54:20'),
(4, 1, 9, 'Maharani Website Discussion', 'Review mobile optimization progress and touch targets', '2026-09-25', '16:30', NULL, 30, NULL, NULL, 'https://meet.google.com/krd-meet-mhr', NULL, 'completed', 'Joining on Google Meet', '2026-09-25 12:55:39', '2026-09-25 12:55:39'),
(5, 1, 9, 'Maharani Website Discussion', 'Review mobile optimization progress and touch targets', '2026-09-25', '16:30', NULL, 30, NULL, NULL, 'https://meet.google.com/krd-meet-mhr', NULL, 'completed', 'Joining on Google Meet', '2026-09-25 12:58:40', '2026-09-25 12:58:40');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_participants`
--

CREATE TABLE `meeting_participants` (
  `id` int(11) NOT NULL,
  `meeting_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `data`, `is_read`, `created_at`) VALUES
(1, 1, 'system', 'Welcome Admin', 'Work Management Portal initialized successfully.', 'dashboard.php', NULL, 0, '2026-08-19 07:09:49'),
(2, 4, 'task', 'New Task Assigned', 'You have been assigned to: Implement Glassmorphism UI', 'tasks.php', NULL, 0, '2026-08-19 07:09:49'),
(3, 2, 'leave', 'Leave Request Submitted', 'Shivanshu submitted a sick leave request.', 'leaves.php', NULL, 0, '2026-08-19 07:09:49'),
(4, 5, 'task', 'New Task Assigned', 'You were assigned task: Make the Website Required Changes', 'tasks', NULL, 0, '2026-08-24 13:41:24'),
(5, 5, 'task', 'New Task Assigned', 'You were assigned task: Connect and Test Pixel before running ads', 'tasks', NULL, 0, '2026-08-24 13:43:23'),
(6, 9, 'task', 'New Task Assigned', 'You were assigned task: Arrange All the Photos Property Vice', 'tasks', NULL, 0, '2026-08-24 13:48:02'),
(7, 2, 'task', 'New Task Assigned', 'You were assigned task: Ask Creative team for more Ads Videos', 'tasks', NULL, 0, '2026-08-24 13:51:30'),
(8, 4, 'task', 'New Task Assigned', 'You were assigned task: Update The Latest File for Luxury Destination Wedding on Hostinger', 'tasks', NULL, 0, '2026-08-24 13:55:01'),
(9, 5, 'task', 'New Task Assigned', 'You were assigned task: Make a upcomming month SEO plan for Luxury Destination wedding', 'tasks', NULL, 0, '2026-08-24 13:56:35'),
(10, 4, 'task', 'New Task Assigned', 'You were assigned task: Almost heaven Website Design Final Design Ready ( gurpreet Ma&#039;am)', 'tasks', NULL, 0, '2026-08-24 14:03:14'),
(11, 4, 'task', 'New Task Assigned', 'You were assigned task: Almost heaven Website Design Final Design Ready ( Meeta Johar )', 'tasks', NULL, 0, '2026-08-24 14:04:09'),
(12, 5, 'task', 'New Task Assigned', 'You were assigned task: Do research on Keywords for Catering , Events like birthdays , anniversary', 'tasks', NULL, 0, '2026-08-24 15:47:29'),
(13, 5, 'task', 'New Task Assigned', 'You were assigned task: Allot task according to the SEO plan for', 'tasks', NULL, 0, '2026-08-24 15:50:39'),
(14, 5, 'task', 'New Task Assigned', 'You were assigned task: Allot task according to the SEO plan for Taj', 'tasks', NULL, 0, '2026-08-24 15:52:53'),
(15, 5, 'task', 'New Task Assigned', 'You were assigned task: Make a seo plan for Dr shivam dang website', 'tasks', NULL, 0, '2026-08-24 15:55:05'),
(16, 5, 'task', 'New Task Assigned', 'You were assigned task: Make a seo plan for Shivam dang and vishakha clinic website', 'tasks', NULL, 0, '2026-08-24 15:55:58'),
(17, 4, 'task', 'New Task Assigned', 'You were assigned task: Take Access of Emergent from Karan Sir', 'tasks', NULL, 0, '2026-08-24 15:58:02'),
(18, 5, 'task', 'New Task Assigned', 'You were assigned task: Make a proper SEO plan for Aristella ( Around Dehradun , food Items )', 'tasks', NULL, 0, '2026-08-25 11:07:33'),
(19, 5, 'task', 'New Task Assigned', 'You were assigned task: Get the research approved by KD for Turmeric', 'tasks', NULL, 0, '2026-08-25 11:08:42'),
(20, 5, 'task', 'New Task Assigned', 'You were assigned task: Start Building SEO pages according to research for Turmeric', 'tasks', NULL, 0, '2026-08-25 11:09:43'),
(21, 5, 'task', 'New Task Assigned', 'You were assigned task: Get the plan approved by KD for Aristella', 'tasks', NULL, 0, '2026-08-26 09:45:15'),
(22, 9, 'meeting_request', 'Meeting Request: Maharani Website Discussion', 'Boss has requested you to join a meeting on Maharani Website Discussion at 4:30 PM (30 minutes).', 'pages/my-workday.php', NULL, 0, '2026-09-25 12:50:16'),
(23, 1, 'employee_waiting', '⏳ Employee Waiting', 'Abhay is waiting: Waiting for client content (Waiting for final high-res mobile banner images)', 'pages/live-team-status.php', '{\"employee_id\":9,\"reason\":\"Waiting for client content\",\"notes\":\"Waiting for final high-res mobile banner images\"}', 0, '2026-09-25 12:54:20'),
(24, 1, 'task_completed', 'Task Completed', ' completed task: Connect and Test Pixel before running ads (Global Organic SEO Campaign 2026)', 'pages/live-team-status.php', '{\"employee_id\":26,\"task_id\":9}', 0, '2026-09-25 12:54:20'),
(25, 9, 'meeting_request', '🔔 Meeting Request', 'Boss has requested you to join a meeting: Maharani Website Discussion at 4:30 PM (30 mins)', 'pages/my-workday.php', '{\"meeting_id\":3,\"title\":\"Maharani Website Discussion\",\"meeting_date\":\"2026-09-25\",\"start_time\":\"16:30\",\"duration\":30,\"meeting_link\":\"https:\\/\\/meet.google.com\\/krd-meet-mhr\",\"description\":\"Review mobile optimization progress and touch targets\"}', 0, '2026-09-25 12:54:20'),
(26, 1, 'meeting_response', 'Meeting Accepted', 'Abhay Accepted meeting: \'Maharani Website Discussion\' (Joining on Google Meet)', 'pages/live-team-status.php', '{\"meeting_id\":3,\"employee_id\":9,\"status\":\"accepted\"}', 0, '2026-09-25 12:54:20'),
(27, 1, 'employee_waiting', '⏳ Employee Waiting', 'Abhay is waiting: Waiting for client content (Waiting for final high-res mobile banner images)', 'pages/live-team-status.php', '{\"employee_id\":9,\"reason\":\"Waiting for client content\",\"notes\":\"Waiting for final high-res mobile banner images\"}', 0, '2026-09-25 12:55:39'),
(28, 1, 'task_completed', 'Task Completed', ' completed task: Connect and Test Pixel before running ads (Global Organic SEO Campaign 2026)', 'pages/live-team-status.php', '{\"employee_id\":26,\"task_id\":9}', 0, '2026-09-25 12:55:39'),
(29, 9, 'meeting_request', '🔔 Meeting Request', 'Boss has requested you to join a meeting: Maharani Website Discussion at 4:30 PM (30 mins)', 'pages/my-workday.php', '{\"meeting_id\":4,\"title\":\"Maharani Website Discussion\",\"meeting_date\":\"2026-09-25\",\"start_time\":\"16:30\",\"duration\":30,\"meeting_link\":\"https:\\/\\/meet.google.com\\/krd-meet-mhr\",\"description\":\"Review mobile optimization progress and touch targets\"}', 0, '2026-09-25 12:55:39'),
(30, 1, 'meeting_response', 'Meeting Accepted', 'Abhay Accepted meeting: \'Maharani Website Discussion\' (Joining on Google Meet)', 'pages/live-team-status.php', '{\"meeting_id\":4,\"employee_id\":9,\"status\":\"accepted\"}', 0, '2026-09-25 12:55:39'),
(31, 1, 'employee_waiting', '⏳ Employee Waiting', 'Abhay is waiting: Waiting for client content (Waiting for final high-res mobile banner images)', 'pages/live-team-status.php', '{\"employee_id\":9,\"reason\":\"Waiting for client content\",\"notes\":\"Waiting for final high-res mobile banner images\"}', 0, '2026-09-25 12:58:40'),
(32, 1, 'task_completed', 'Task Completed', 'Abhay completed task: Mobile Optimization (Maharani Website)', 'pages/live-team-status.php', '{\"employee_id\":9,\"task_id\":26}', 0, '2026-09-25 12:58:40'),
(33, 9, 'meeting_request', '🔔 Meeting Request', 'Boss has requested you to join a meeting: Maharani Website Discussion at 4:30 PM (30 mins)', 'pages/my-workday.php', '{\"meeting_id\":5,\"title\":\"Maharani Website Discussion\",\"meeting_date\":\"2026-09-25\",\"start_time\":\"16:30\",\"duration\":30,\"meeting_link\":\"https:\\/\\/meet.google.com\\/krd-meet-mhr\",\"description\":\"Review mobile optimization progress and touch targets\"}', 0, '2026-09-25 12:58:40'),
(34, 1, 'meeting_response', 'Meeting Accepted', 'Abhay Accepted meeting: \'Maharani Website Discussion\' (Joining on Google Meet)', 'pages/live-team-status.php', '{\"meeting_id\":5,\"employee_id\":9,\"status\":\"accepted\"}', 0, '2026-09-25 12:58:40');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'abcd@gmail.com', '475bc36e2dc5e64de76a7cae76df4808', '2026-08-20 00:56:26', '2026-08-19 23:56:26');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `receipt_number` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(50) NOT NULL DEFAULT 'ONLINE',
  `online_type` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `recorded_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `month_year` varchar(20) NOT NULL,
  `basic_salary` decimal(12,2) DEFAULT 0.00,
  `allowance` decimal(12,2) DEFAULT 0.00,
  `bonus` decimal(12,2) DEFAULT 0.00,
  `deduction` decimal(12,2) DEFAULT 0.00,
  `present_days` decimal(5,1) DEFAULT 0.0,
  `half_days` decimal(5,1) DEFAULT 0.0,
  `absent_days` decimal(5,1) DEFAULT 0.0,
  `paid_leave_days` decimal(5,1) DEFAULT 0.0,
  `unpaid_leave_days` decimal(5,1) DEFAULT 0.0,
  `holiday_days` int(11) DEFAULT 0,
  `working_hours` decimal(6,2) DEFAULT 0.00,
  `net_salary` decimal(12,2) DEFAULT 0.00,
  `payment_status` varchar(50) DEFAULT 'pending',
  `payment_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `completed_days` int(11) DEFAULT 0,
  `earned_salary` decimal(12,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `user_id`, `month_year`, `basic_salary`, `allowance`, `bonus`, `deduction`, `present_days`, `half_days`, `absent_days`, `paid_leave_days`, `unpaid_leave_days`, `holiday_days`, `working_hours`, `net_salary`, `payment_status`, `payment_date`, `remarks`, `completed_days`, `earned_salary`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-08', 50000.00, 0.00, 0.00, 37096.77, 0.0, 0.0, 30.0, 0.0, 0.0, 1, 0.00, 12903.23, 'pending', NULL, NULL, 31, 1612.90, '2026-08-24 12:03:54', '2026-08-31 12:49:24'),
(2, 2, '2026-08', 25000.00, 0.00, 0.00, 18548.39, 0.0, 0.0, 30.0, 0.0, 0.0, 1, 0.00, 6451.61, 'pending', NULL, NULL, 31, 806.45, '2026-08-24 12:03:54', '2026-08-31 12:49:25'),
(3, 3, '2026-08', 20000.00, 0.00, 0.00, 14838.71, 0.0, 0.0, 30.0, 0.0, 0.0, 1, 0.00, 5161.29, 'pending', NULL, NULL, 31, 645.16, '2026-08-24 12:03:55', '2026-08-31 12:49:26'),
(4, 4, '2026-08', 30000.00, 0.00, 0.00, 22258.06, 0.0, 0.0, 30.0, 0.0, 0.0, 1, 0.00, 7741.94, 'pending', NULL, NULL, 31, 967.74, '2026-08-24 12:03:55', '2026-08-31 12:49:26'),
(5, 5, '2026-08', 25000.00, 0.00, 0.00, 18548.39, 0.0, 0.0, 30.0, 0.0, 0.0, 1, 0.00, 6451.61, 'pending', NULL, NULL, 31, 806.45, '2026-08-24 12:03:56', '2026-08-31 12:49:27'),
(6, 6, '2026-08', 10000.00, 0.00, 0.00, 6774.19, 3.0, 4.0, 25.0, 0.0, 0.0, 1, 56.61, 3225.81, 'pending', NULL, NULL, 31, 1935.48, '2026-08-24 12:03:56', '2026-08-31 12:49:27'),
(7, 7, '2026-08', 10000.00, 0.00, 0.00, 7419.35, 0.0, 5.0, 27.5, 0.0, 0.0, 1, 58.57, 2580.65, 'pending', NULL, NULL, 31, 1129.03, '2026-08-24 12:03:57', '2026-08-31 12:49:28'),
(8, 8, '2026-08', 15000.00, 0.00, 0.00, 10645.16, 3.0, 2.0, 26.0, 0.0, 0.0, 1, 56.89, 4354.84, 'pending', NULL, NULL, 31, 2419.35, '2026-08-24 12:03:58', '2026-08-31 12:49:29'),
(9, 9, '2026-08', 5000.00, 0.00, 0.00, 3548.39, 2.0, 6.0, 25.0, 0.0, 0.0, 1, 63.84, 1451.61, 'pending', NULL, NULL, 31, 967.74, '2026-08-24 12:03:58', '2026-08-31 12:49:29'),
(10, 10, '2026-08', 7000.00, 0.00, 0.00, 5193.55, 0.0, 1.0, 29.5, 0.0, 0.0, 1, 8.38, 1806.45, 'pending', NULL, NULL, 31, 338.71, '2026-08-24 12:03:59', '2026-08-31 12:49:30'),
(11, 1, '2026-07', 50000.00, 0.00, 0.00, 50000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:00', '2026-08-31 12:51:00'),
(12, 2, '2026-07', 25000.00, 0.00, 0.00, 25000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:01', '2026-08-31 12:51:01'),
(13, 3, '2026-07', 20000.00, 0.00, 0.00, 20000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:03', '2026-08-31 12:51:03'),
(14, 4, '2026-07', 30000.00, 0.00, 0.00, 30000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:03', '2026-08-31 12:51:03'),
(15, 5, '2026-07', 25000.00, 0.00, 0.00, 25000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:05', '2026-08-31 12:51:05'),
(16, 6, '2026-07', 10000.00, 0.00, 0.00, 10000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:06', '2026-08-31 12:51:06'),
(17, 7, '2026-07', 10000.00, 0.00, 0.00, 10000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:08', '2026-08-31 12:51:08'),
(18, 8, '2026-07', 15000.00, 0.00, 0.00, 15000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:10', '2026-08-31 12:51:10'),
(19, 9, '2026-07', 5000.00, 0.00, 0.00, 5000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:13', '2026-08-31 12:51:13'),
(20, 10, '2026-07', 7000.00, 0.00, 0.00, 7000.00, 0.0, 0.0, 31.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 31, 0.00, '2026-08-31 12:51:15', '2026-08-31 12:51:15'),
(21, 1, '2026-09', 50000.00, 0.00, 0.00, 1666.67, 0.0, 0.0, 15.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 15, 0.00, '2026-09-01 10:17:37', '2026-09-15 11:43:49'),
(22, 2, '2026-09', 25000.00, 0.00, 0.00, 833.33, 0.0, 0.0, 15.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 15, 0.00, '2026-09-01 10:17:38', '2026-09-15 11:43:50'),
(23, 3, '2026-09', 20000.00, 0.00, 0.00, 666.67, 0.0, 0.0, 15.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 15, 0.00, '2026-09-01 10:17:38', '2026-09-15 11:43:50'),
(24, 4, '2026-09', 30000.00, 0.00, 0.00, 6000.00, 3.0, 0.0, 12.0, 0.0, 0.0, 0, 28.49, 0.00, 'pending', NULL, NULL, 15, 3000.00, '2026-09-01 10:17:39', '2026-09-15 11:43:51'),
(25, 5, '2026-09', 25000.00, 0.00, 0.00, 833.33, 0.0, 0.0, 15.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 15, 0.00, '2026-09-01 10:17:39', '2026-09-15 11:43:51'),
(26, 6, '2026-09', 10000.00, 0.00, 0.00, 333.33, 9.0, 2.0, 5.0, 0.0, 0.0, 0, 93.05, 3000.00, 'pending', NULL, NULL, 15, 3333.33, '2026-09-01 10:17:40', '2026-09-15 11:43:52'),
(27, 7, '2026-09', 10000.00, 0.00, 0.00, 333.33, 9.0, 0.0, 6.0, 0.0, 0.0, 0, 82.31, 2666.67, 'pending', NULL, NULL, 15, 3000.00, '2026-09-01 10:17:41', '2026-09-15 11:43:53'),
(28, 8, '2026-09', 15000.00, 0.00, 0.00, 500.00, 6.0, 3.0, 7.5, 0.0, 0.0, 0, 78.86, 3250.00, 'pending', NULL, NULL, 15, 3750.00, '2026-09-01 10:17:41', '2026-09-15 11:43:53'),
(29, 9, '2026-09', 5000.00, 0.00, 0.00, 166.67, 9.0, 2.0, 5.0, 0.0, 0.0, 0, 98.63, 1500.00, 'pending', NULL, NULL, 15, 1666.67, '2026-09-01 10:17:42', '2026-09-15 11:43:54'),
(30, 10, '2026-09', 7000.00, 0.00, 0.00, 233.33, 0.0, 0.0, 15.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 15, 0.00, '2026-09-01 10:17:42', '2026-09-15 11:43:54');

-- --------------------------------------------------------

--
-- Table structure for table `performance_reports`
--

CREATE TABLE `performance_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `month_year` varchar(20) NOT NULL,
  `total_tasks` int(11) DEFAULT 0,
  `completed_tasks` int(11) DEFAULT 0,
  `late_tasks` int(11) DEFAULT 0,
  `working_seconds` int(11) DEFAULT 0,
  `task_score` decimal(5,2) DEFAULT 0.00,
  `performance_percentage` decimal(5,2) DEFAULT 0.00,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proforma_invoices`
--

CREATE TABLE `proforma_invoices` (
  `id` int(11) NOT NULL,
  `proforma_number` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `converted_invoice_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proforma_items`
--

CREATE TABLE `proforma_items` (
  `id` int(11) NOT NULL,
  `proforma_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `service_type` varchar(50) NOT NULL DEFAULT 'STANDARD',
  `service_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `priority` varchar(20) DEFAULT 'medium',
  `status` varchar(50) NOT NULL DEFAULT 'in_progress',
  `start_date` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `progress` int(11) DEFAULT 0,
  `budget` decimal(12,2) DEFAULT 0.00,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `client_id`, `department_id`, `name`, `code`, `description`, `priority`, `status`, `start_date`, `deadline`, `end_date`, `progress`, `budget`, `created_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 2, 'Office Work Management Portal', 'PRJ-WORK-01', 'Complete PHP 8 + MySQL office management web application with Notion/ClickUp UI', 'urgent', 'active', '2026-08-01', '2026-08-31', '2026-08-31', 65, 0.00, 1, '2026-08-19 07:09:49', '2026-09-25 12:11:01'),
(2, NULL, 3, 'Global Organic SEO Campaign 2026', 'PRJ-SEO-2026', 'Complete technical & content SEO strategy to boost organic search volume', 'high', 'active', '2026-08-01', '2026-11-30', '2026-11-30', 40, 0.00, 5, '2026-08-19 07:09:49', '2026-09-25 12:11:01'),
(3, NULL, 4, 'Brand UI Refresh & Design System', 'PRJ-DESIGN-02', 'Modern Glassmorphic design system and website re-skin', 'medium', 'active', '2026-08-05', '2026-09-15', '2026-09-15', 25, 0.00, 3, '2026-08-19 07:09:49', '2026-09-25 12:11:01'),
(4, NULL, 5, 'Q3 Social Media Viral Strategy', 'PRJ-SMM-Q3', 'Multi-channel social video reels, infographics, and marketing ads', 'medium', 'active', '2026-08-01', '2026-09-30', '2026-09-30', 50, 0.00, 8, '2026-08-19 07:09:49', '2026-09-25 12:11:01'),
(5, NULL, NULL, 'Maharani Website', 'PRJ-MHR-01', 'E-commerce luxury brand website redesign & mobile optimization', 'urgent', 'in_progress', '2026-09-25', '2026-10-15', NULL, NULL, NULL, NULL, '2026-09-25 12:50:16', '2026-09-25 12:50:16'),
(6, NULL, NULL, 'Krafting Digital', 'PRJ-KD-CORE', 'Agency internal operations & digital billing platform', 'high', 'in_progress', '2026-09-25', '2026-12-31', NULL, NULL, NULL, NULL, '2026-09-25 12:50:16', '2026-09-25 12:50:16'),
(7, NULL, NULL, 'Casa Tupka', 'PRJ-CT-02', 'Resort website redesign & booking engine optimization', 'medium', 'in_progress', '2026-09-25', '2026-11-20', NULL, NULL, NULL, NULL, '2026-09-25 12:50:16', '2026-09-25 12:50:16');

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_in_project` varchar(50) DEFAULT 'Member',
  `joined_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_members`
--

INSERT INTO `project_members` (`id`, `project_id`, `user_id`, `role_in_project`, `joined_at`) VALUES
(1, 1, 1, 'Owner', '2026-08-19 07:09:49'),
(2, 1, 3, 'Product Owner', '2026-08-19 07:09:49'),
(3, 1, 4, 'Lead Dev', '2026-08-19 07:09:49'),
(4, 1, 9, 'Dev Intern', '2026-08-19 07:09:49'),
(5, 2, 5, 'Project Lead', '2026-08-19 07:09:49'),
(6, 2, 6, 'SEO Specialist', '2026-08-19 07:09:49'),
(7, 2, 10, 'SEO Intern', '2026-08-19 07:09:49'),
(8, 3, 7, 'Lead Designer', '2026-08-19 07:09:49'),
(9, 3, 3, 'Product Manager', '2026-08-19 07:09:49'),
(10, 4, 8, 'Campaign Lead', '2026-08-19 07:09:49'),
(11, 4, 7, 'Visual Designer', '2026-08-19 07:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `quotation_number` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `quotation_date` date NOT NULL,
  `valid_until` date NOT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_type` varchar(20) NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 18.00,
  `total_tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `converted_invoice_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `terms` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `service_type` varchar(50) NOT NULL DEFAULT 'STANDARD',
  `service_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 1.00,
  `rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 18.00,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recurring_profiles`
--

CREATE TABLE `recurring_profiles` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `frequency` varchar(50) NOT NULL DEFAULT 'monthly',
  `next_invoice_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`) VALUES
(1, 'admin', 'Administrator', 'Full access to all system features and settings', '2026-08-19 07:09:49'),
(2, 'manager', 'Manager', 'Manages employees, tasks, reports, and approvals', '2026-08-19 07:09:49'),
(3, 'product_manager', 'Product Manager', 'Manages projects, sprint tasks, and product releases', '2026-08-19 07:09:49'),
(4, 'senior_developer', 'Senior Developer', 'Leads tech development and architecture tasks', '2026-08-19 07:09:49'),
(5, 'seo_manager', 'SEO Manager', 'Oversees SEO strategy, campaigns, and execution', '2026-08-19 07:09:49'),
(6, 'seo_executive', 'SEO Executive', 'Executes SEO activities, backlinks, and optimization', '2026-08-19 07:09:49'),
(7, 'graphic_designer', 'Graphic Designer', 'Creates UI designs, graphics, and visual assets', '2026-08-19 07:09:49'),
(8, 'social_media_manager', 'Social Media Manager', 'Manages social media channels and campaigns', '2026-08-19 07:09:49'),
(9, 'intern_developer', 'Intern Developer', 'Assists development team with tasks', '2026-08-19 07:09:49'),
(10, 'intern_seo', 'Intern SEO', 'Assists SEO team with research and submissions', '2026-08-19 07:09:49'),
(11, 'ads_expert', 'ADS EXPERT', 'Meta Ads & Google Ads Specialist', '2026-10-15 13:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `default_rate` decimal(12,2) NOT NULL DEFAULT 0.00,
  `billing_type` varchar(20) NOT NULL DEFAULT 'one-time',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT 18.00,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `code`, `description`, `default_rate`, `billing_type`, `tax_rate`, `status`, `created_at`) VALUES
(1, 'Web Development', 'WEB-DEV', 'Custom web application & responsive CMS development', 45000.00, 'one-time', 18.00, 'active', '2026-09-12 10:04:16'),
(2, 'Search Engine Optimization (SEO)', 'SEO', 'Technical SEO, on-page optimization, content strategy & backlinks', 25000.00, 'monthly', 18.00, 'active', '2026-09-12 10:04:16'),
(3, 'Social Media Marketing (SMM)', 'SMM', 'Multi-channel social campaigns, creatives, community management', 20000.00, 'monthly', 18.00, 'active', '2026-09-12 10:04:16'),
(4, 'Performance Marketing (PPC)', 'PPC', 'Google Ads, Meta Ads management, conversion optimization', 30000.00, 'monthly', 18.00, 'active', '2026-09-12 10:04:16'),
(5, 'UI/UX & Brand Design', 'DESIGN', 'Brand identity, website wireframes, Figma design systems', 15000.00, 'one-time', 18.00, 'active', '2026-09-12 10:04:16'),
(6, 'Cloud Hosting & Maintenance', 'HOSTING', 'Server management, daily backups, SSL and security patches', 5000.00, 'yearly', 18.00, 'active', '2026-09-12 10:04:16'),
(7, 'Model Shoot', 'SHOOT', 'Professional model shoot, styling and photography', 3500.00, 'one-time', 0.00, 'active', '2026-09-25 12:31:10'),
(8, 'Social Media Marketing & Website SEO Service', 'SMM-SEO', 'Integrated social media campaign with complete website search engine optimization', 55000.00, 'monthly', 0.00, 'active', '2026-09-25 12:31:10'),
(9, 'SEO (LOCAL & WEBSITE)', 'SEO-LOC', 'Local and website search engine optimization', 16000.00, 'monthly', 0.00, 'active', '2026-09-25 12:31:10'),
(10, 'SOCIAL MEDIA & AD SERVICES', 'SMM-ADS', 'Social media marketing, creatives, and ad campaign management', 15000.00, 'monthly', 0.00, 'active', '2026-09-25 12:31:10');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(128) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `group_name` varchar(50) NOT NULL DEFAULT 'general',
  `setting_group` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shift_settings`
--

CREATE TABLE `shift_settings` (
  `id` int(11) NOT NULL,
  `shift_name` varchar(100) NOT NULL DEFAULT 'Standard Agency Shift',
  `start_time` varchar(10) NOT NULL DEFAULT '09:40',
  `end_time` varchar(10) NOT NULL DEFAULT '18:30',
  `lunch_start` varchar(10) NOT NULL DEFAULT '14:00',
  `lunch_end` varchar(10) NOT NULL DEFAULT '15:00',
  `working_days` varchar(255) NOT NULL DEFAULT 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
  `grace_period_minutes` int(11) NOT NULL DEFAULT 15,
  `reminder_interval_minutes` int(11) NOT NULL DEFAULT 30,
  `auto_lunch` tinyint(1) NOT NULL DEFAULT 1,
  `allow_overlapping_meetings` tinyint(1) NOT NULL DEFAULT 0,
  `meeting_default_duration` int(11) NOT NULL DEFAULT 30,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shift_settings`
--

INSERT INTO `shift_settings` (`id`, `shift_name`, `start_time`, `end_time`, `lunch_start`, `lunch_end`, `working_days`, `grace_period_minutes`, `reminder_interval_minutes`, `auto_lunch`, `allow_overlapping_meetings`, `meeting_default_duration`, `created_at`, `updated_at`) VALUES
(1, 'Standard Agency Shift', '09:40', '18:30', '14:00', '15:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday', 15, 30, 1, 0, 30, '2026-09-25 11:34:02', '2026-09-25 11:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `system_logs`
--

CREATE TABLE `system_logs` (
  `id` int(11) NOT NULL,
  `level` varchar(20) DEFAULT 'INFO',
  `message` text NOT NULL,
  `context` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `assigned_to_employee_id` int(11) DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `assigned_by` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` varchar(20) NOT NULL DEFAULT 'medium',
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `estimated_hours` decimal(6,2) DEFAULT 0.00,
  `spent_seconds` int(11) DEFAULT 0,
  `deadline` datetime DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `department_id`, `assigned_to_employee_id`, `assigned_to`, `assigned_by`, `title`, `description`, `priority`, `status`, `rejection_reason`, `estimated_hours`, `spent_seconds`, `deadline`, `due_date`, `completed_at`, `is_archived`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 4, 4, 1, 'Implement Glassmorphism Admin Dashboard UI', 'Design responsive Bootstrap 5 cards, sidebar, and navbar with glass dynamic effects.', 'urgent', 'completed', NULL, 8.00, 14400, '2026-08-10 18:00:00', '2026-08-10', NULL, 0, '2026-08-19 07:09:49', '2026-09-25 16:54:42'),
(2, 1, 2, 4, 4, 2, 'Create Live Task Timer & Timer Widget', 'Build JavaScript AJAX live timer with Start, Pause, Resume, and Stop functionality.', 'high', 'pending', NULL, 6.00, 0, '2026-08-12 17:00:00', '2026-08-12', NULL, 0, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(3, 1, 2, 9, 9, 4, 'Build Kanban Drag & Drop Task Board', 'Implement HTML5 drag-and-drop workflow columns connected with PHP API.', 'medium', 'completed', NULL, 5.00, 9, '2026-08-14 18:00:00', '2026-08-14', NULL, 0, '2026-08-19 07:09:49', '2026-08-19 12:52:37'),
(4, 2, 3, 6, 6, 5, 'Perform Comprehensive Site Audit & Schema Fixes', 'Check all page titles, meta descriptions, canonical URLs, and structured data.', 'high', 'running', NULL, 10.00, 7200, '2026-08-09 17:00:00', '2026-08-09', NULL, 0, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(5, 2, 3, 10, 10, 5, 'Weekly Directory & Bookmark Submissions', 'Submit top 50 high-DA web directories and social bookmarking links.', 'low', 'pending', NULL, 4.00, 0, '2026-08-08 16:00:00', '2026-08-08', NULL, 0, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(6, 3, 4, 7, 7, 3, 'Design Dark Mode Color Palette & Glass Cards', 'Figma prototypes and SVG icons for new office portal design language.', 'urgent', 'completed', NULL, 12.00, 43200, '2026-08-05 18:00:00', '2026-08-05', NULL, 0, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(7, 4, 5, 8, 8, 2, 'Schedule Weekly LinkedIn & Instagram Posts', 'Create engaging carousels and write copy for Q3 feature highlights.', 'medium', 'running', NULL, 6.00, 10800, '2026-08-11 15:00:00', '2026-08-11', NULL, 0, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(8, 2, 1, 5, 5, 2, 'Make the Website Required Changes', '', 'urgent', 'pending', NULL, 0.00, 0, '2026-08-22 13:36:00', '2026-08-22', NULL, 0, '2026-08-24 13:41:24', '2026-08-24 13:41:24'),
(9, 2, 1, 5, 5, 2, 'Connect and Test Pixel before running ads', '', 'urgent', 'completed', NULL, 0.00, 0, '2026-08-24 13:41:00', '2026-08-24', '2026-09-25 12:55:39', 0, '2026-08-24 13:43:23', '2026-09-25 12:55:39'),
(10, NULL, 1, 9, 9, 2, 'Arrange All the Photos Property Vice', '', 'urgent', 'completed', NULL, 0.00, 21, '2026-08-21 13:43:00', '2026-08-21', NULL, 0, '2026-08-24 13:48:02', '2026-08-24 17:53:43'),
(11, NULL, 1, 2, 2, 2, 'Ask Creative team for more Ads Videos', '', 'urgent', 'pending', NULL, 0.00, 0, '2026-08-21 13:48:00', '2026-08-21', NULL, 0, '2026-08-24 13:51:30', '2026-08-24 13:51:30'),
(12, 3, 1, 4, 4, 2, 'Update The Latest File for Luxury Destination Wedding on Hostinger', '', 'urgent', 'pending', NULL, 0.00, 0, '2026-08-21 13:52:00', '2026-08-21', NULL, 0, '2026-08-24 13:55:01', '2026-08-24 13:55:01'),
(13, 2, 1, 5, 5, 2, 'Make a upcomming month SEO plan for Luxury Destination wedding', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-24 13:55:00', '2026-08-24', NULL, 0, '2026-08-24 13:56:35', '2026-08-24 13:56:35'),
(14, 3, 1, 4, 4, 2, 'Almost heaven Website Design Final Design Ready ( gurpreet Ma&#039;am)', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-25 13:00:00', '2026-08-25', NULL, 0, '2026-08-24 14:03:14', '2026-08-24 14:03:14'),
(15, 3, 1, 4, 4, 2, 'Almost heaven Website Design Final Design Ready ( Meeta Johar )', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-25 14:03:00', '2026-08-25', NULL, 0, '2026-08-24 14:04:09', '2026-08-24 14:04:09'),
(16, 2, 1, 5, 5, 2, 'Do research on Keywords for Catering , Events like birthdays , anniversary', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-25 15:46:00', '2026-08-25', NULL, 0, '2026-08-24 15:47:29', '2026-08-24 15:47:29'),
(18, NULL, 1, 5, 5, 2, 'Allot task according to the SEO plan for Taj', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-25 15:51:00', '2026-08-25', NULL, 0, '2026-08-24 15:52:53', '2026-08-24 15:52:53'),
(19, 2, 1, 5, 5, 2, 'Make a seo plan for Dr shivam dang website', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-25 15:52:00', '2026-08-25', NULL, 0, '2026-08-24 15:55:05', '2026-08-24 15:55:05'),
(20, 2, 1, 5, 5, 2, 'Make a seo plan for Shivam dang and vishakha clinic website', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-25 15:55:00', '2026-08-25', NULL, 0, '2026-08-24 15:55:58', '2026-08-24 15:55:58'),
(21, 3, 1, 4, 4, 2, 'Take Access of Emergent from Karan Sir', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-25 15:56:00', '2026-08-25', NULL, 0, '2026-08-24 15:58:02', '2026-08-24 15:58:02'),
(22, 2, 1, 5, 5, 2, 'Make a proper SEO plan for Aristella ( Around Dehradun , food Items )', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-26 11:05:00', '2026-08-26', NULL, 0, '2026-08-25 11:07:33', '2026-08-25 11:07:33'),
(23, 2, 1, 5, 5, 2, 'Get the research approved by KD for Turmeric', '', 'high', 'pending', NULL, 4.00, 0, '2026-08-26 11:07:00', '2026-08-26', NULL, 0, '2026-08-25 11:08:42', '2026-08-25 11:08:42'),
(24, 2, 1, 5, 5, 2, 'Start Building SEO pages according to research for Turmeric', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-26 11:08:00', '2026-08-26', NULL, 0, '2026-08-25 11:09:43', '2026-08-25 11:09:43'),
(25, 2, 1, 5, 5, 2, 'Get the plan approved by KD for Aristella', '', 'high', 'pending', NULL, 0.00, 0, '2026-08-27 09:44:00', '2026-08-27', NULL, 0, '2026-08-26 09:45:15', '2026-08-26 09:45:15'),
(26, 5, NULL, 9, 9, NULL, 'Mobile Optimization', 'Mobile touch targets, responsive navigation drawer, and performance optimization.', 'urgent', 'in_progress', NULL, 8.00, NULL, NULL, '2026-09-30', '2026-09-25 12:58:40', NULL, '2026-09-25 12:50:16', '2026-09-25 12:58:40'),
(27, 7, NULL, 9, 9, NULL, 'Resort Gallery & Touch Navigation', 'High-res image gallery and touch swipe responsiveness.', 'medium', 'pending', NULL, 6.00, NULL, NULL, '2026-10-10', NULL, NULL, '2026-09-25 12:50:16', '2026-09-25 12:50:16');

-- --------------------------------------------------------

--
-- Table structure for table `task_comments`
--

CREATE TABLE `task_comments` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `attachments_json` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_files`
--

CREATE TABLE `task_files` (
  `id` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_logs`
--

CREATE TABLE `task_logs` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `today_work` text NOT NULL,
  `progress_percentage` int(11) DEFAULT 0,
  `issues` text DEFAULT NULL,
  `completed_work` text DEFAULT NULL,
  `remaining_work` text DEFAULT NULL,
  `manager_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_logs`
--

INSERT INTO `task_logs` (`id`, `task_id`, `user_id`, `log_date`, `today_work`, `progress_percentage`, `issues`, `completed_work`, `remaining_work`, `manager_notes`, `created_at`) VALUES
(1, 3, 9, '2026-08-19', 'done', 100, NULL, 'done', NULL, NULL, '2026-08-19 12:52:37'),
(2, 10, 9, '2026-08-24', 'COMPLETED', 100, NULL, 'COMPLETED', NULL, NULL, '2026-08-24 17:53:43'),
(3, 1, 4, '2026-09-25', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-09-25 16:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `task_timer`
--

CREATE TABLE `task_timer` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `pause_time` datetime DEFAULT NULL,
  `resume_time` datetime DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT 0,
  `idle_seconds` int(11) DEFAULT 0,
  `status` varchar(50) DEFAULT 'running',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_timer`
--

INSERT INTO `task_timer` (`id`, `task_id`, `user_id`, `start_time`, `end_time`, `pause_time`, `resume_time`, `duration_seconds`, `idle_seconds`, `status`, `created_at`) VALUES
(1, 3, 9, '2026-08-19 12:52:28', '2026-08-19 12:52:37', NULL, NULL, 9, 0, 'completed', '2026-08-19 12:52:28'),
(2, 10, 9, '2026-08-24 17:53:32', '2026-08-24 17:53:43', NULL, NULL, 21, 0, 'completed', '2026-08-24 17:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `task_time_logs`
--

CREATE TABLE `task_time_logs` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `started_at` datetime DEFAULT NULL,
  `paused_at` datetime DEFAULT NULL,
  `resumed_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `total_work_duration` int(11) DEFAULT 0,
  `status` varchar(50) DEFAULT 'started',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_time_logs`
--

INSERT INTO `task_time_logs` (`id`, `task_id`, `employee_id`, `started_at`, `paused_at`, `resumed_at`, `completed_at`, `total_work_duration`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 9, '2026-08-19 12:52:28', NULL, NULL, NULL, 0, 'started', '2026-08-19 12:52:28', '2026-08-19 12:52:28'),
(2, 3, 9, NULL, NULL, NULL, '2026-08-19 12:52:37', 9, 'completed', '2026-08-19 12:52:37', '2026-08-19 12:52:37'),
(3, 10, 9, '2026-08-24 17:53:32', NULL, NULL, NULL, 0, 'started', '2026-08-24 17:53:32', '2026-08-24 17:53:32'),
(4, 10, 9, NULL, NULL, NULL, '2026-08-24 17:53:43', 11, 'completed', '2026-08-24 17:53:43', '2026-08-24 17:53:43'),
(5, 1, 4, NULL, NULL, NULL, '2026-09-25 16:54:42', 0, 'completed', '2026-09-25 16:54:42', '2026-09-25 16:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `employee_code` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default_avatar.png',
  `designation` varchar(150) DEFAULT NULL,
  `shift_timing` varchar(100) DEFAULT '09:40 AM - 06:30 PM (Fixed Office)',
  `role` varchar(20) NOT NULL DEFAULT 'STAFF',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `department_id`, `employee_code`, `name`, `email`, `username`, `password`, `password_hash`, `phone`, `avatar`, `designation`, `shift_timing`, `role`, `status`, `remember_token`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'KD-EMP-001', 'Kartikeyan', 'kartikeyan@kraftingdigital.com', 'admin', '$2y$10$zTqo5WfuTeFPW..gSYxP4.N5lnQqDyRFLoo55UPzNq9DGrzux7W5q', '$2y$10$UVOX8at822RDb0AdAFDezelJEswz2QjYH6GbPdV59lqzhOygGmNLy', '+91 9876543210', 'default_avatar.png', 'System Administrator & CEO', '09:40 AM - 06:30 PM (Fixed Office)', 'ADMIN', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:01'),
(2, 2, 1, 'KD-EMP-002', 'Mrinali', 'mrinali@kraftingdigital.com', 'mrinali', '$2y$10$Q2oDLAnk6vEdQOm8gXd8J.ymmvySSGfbViOI9ZmfkH8nYSXRaRHBq', '$2y$10$Q2oDLAnk6vEdQOm8gXd8J.ymmvySSGfbViOI9ZmfkH8nYSXRaRHBq', '+91 9876543211', 'default_avatar.png', 'Operations Manager', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:01'),
(3, 3, 6, 'KD-EMP-003', 'Udit', 'contact@kraftingdigital.com', 'contact', '$2y$10$hwgsrqVFvZk5FwEgldlWPun.ghUl/FA70cIVYArb3ydsPB70P1vMC', '$2y$10$nYezPBoSO.sieyr5yJ3ALuKn56GazyzLrl3sJFhVGLroYotE5HNXG', '+91 9876543212', 'default_avatar.png', 'Product Manager', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:01'),
(4, 4, 2, 'KD-EMP-004', 'Rahul', 'finance@kraftingdigital.com', 'finance', '$2y$10$nS/L/fCZEQoGMPcFlG7GrOWi4on5GM.IDYJnLFa8KzrjWg2HVOBgS', '$2y$10$iWmO4xpV8nDnNNTUJ6chYefdtiMgo3L0ZyMYo53dJ8jNJ7Arn.WPy', '+91 9876543213', 'default_avatar.png', 'Senior Fullstack Developer', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:01'),
(5, 5, 3, 'KD-EMP-005', 'Uvaish', 'uvaish@kraftingdigital.com', 'uvaish', '$2y$10$loGewoEcil/b2WpSU3Sm0uqXeh67YkyZPFZgQQbg1tZHVwpQQGJ4q', '$2y$10$3aDOpyYcLwhZfhOGiJhC5.sUZTflmggdrEsP.IURIPzVqv4V4c.qm', '+91 9876543214', 'default_avatar.png', 'SEO Lead Manager', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:01'),
(6, 6, 3, 'KD-EMP-006', 'Shivanshu', 'shivanshu@kraftingdigital.com', 'shivanshu', '$2y$10$wzrW4aex2ZFOqHI2VOW/r.15omfKgXHIuxGT8rzTr5ZnFpMGyJaja', '$2y$10$7hJSHl70QNfvtT2zPfBKtOhvDRD4q0hyLijsZO0xLRP0LVjL5l9pe', '+91 9876543215', 'default_avatar.png', 'Senior SEO Executive', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:01'),
(7, 7, 4, 'KD-EMP-007', 'Sarthak', 'sarthak@kraftingdigital.com', 'sarthak', '$2y$10$0m/W7BPdAJTH12qKmB/nKOO4h7zEdWhs0YPxhJmWFxoxoQHNrwy/6', '$2y$10$MuTrsEYaM183LauXLMOI0eUWo9OMZO4VQRjXqZfiNo591.N3SBjh2', '+91 9876543216', 'default_avatar.png', 'Lead UI/UX Graphic Designer', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:02'),
(8, 8, 5, 'KD-EMP-008', 'Priyansh', 'priyansh@kraftingdigital.com', 'priyansh', '$2y$10$3ytwoI05GkUIFmT2XMdtMeFY8a8i.3XEoiPSkyQ/X4eHy4VRvWszi', '$2y$10$1nIEzmqi.n2BtZqoprTai.Ljj/0ssCRIVNrxYHrpbKkkjhLX5N5ee', '+91 9876543217', 'default_avatar.png', 'Social Media Strategist', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:02'),
(9, 9, 2, 'KD-EMP-009', 'Abhay', 'abhay@kraftingdigital.com', 'abhay', '$2y$10$FTXg/PhGhFuekRXtyOKTUu4Ug77x7EBe4Z0TW4BW1y8Q3kXwXFS/K', '$2y$10$ZH3N7varpG5k4jgMcLRaEu7NPfNPad0svJ9alyYqGCzE5CEgsqt3O', '+91 9876543218', 'default_avatar.png', 'Intern Frontend Developer', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:02'),
(10, 10, 3, 'KD-EMP-010', 'Aman', 'aman@kraftingdigital.com', 'aman', '$2y$10$5ik/pmwvjNe16pfEKhgasOaj36UkgX1VFz22nVzNOPoQwNDCDvcKK', '$2y$10$0qTlxzNUHVaTZ2gE9P403Ollh.b5fVKym4TbGQKjTkiN2j6W7Esd2', '+91 9876543219', 'default_avatar.png', 'Intern SEO Analyst', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-09-26 10:09:02'),
(11, 11, 7, 'KD-EMP-011', 'PRABHAT', 'prabhat@kraftingdigital.com', 'prabhat', '$2y$10$9/LZZ8xxPLgvyw17y1OV6OziKguBnOVpDv41oMm8CwxyA4/hi45cK', '$2y$10$9/LZZ8xxPLgvyw17y1OV6OziKguBnOVpDv41oMm8CwxyA4/hi45cK', '+91 9876543220', 'default_avatar.png', 'ADS EXPERT', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-10-15 13:00:00', '2026-10-15 13:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `activity_reasons`
--
ALTER TABLE `activity_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `date` (`date`);

--
-- Indexes for table `attendance_breaks`
--
ALTER TABLE `attendance_breaks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_id` (`attendance_id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_code` (`client_code`);

--
-- Indexes for table `daily_work_reports`
--
ALTER TABLE `daily_work_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `report_date` (`report_date`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `email_logs`
--
ALTER TABLE `email_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `employee_code` (`employee_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `employee_activities`
--
ALTER TABLE `employee_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `employee_salaries`
--
ALTER TABLE `employee_salaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `meeting_id` (`meeting_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_number` (`receipt_number`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `performance_reports`
--
ALTER TABLE `performance_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `proforma_invoices`
--
ALTER TABLE `proforma_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `proforma_number` (`proforma_number`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `proforma_items`
--
ALTER TABLE `proforma_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proforma_id` (`proforma_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotation_number` (`quotation_number`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indexes for table `recurring_profiles`
--
ALTER TABLE `recurring_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `shift_settings`
--
ALTER TABLE `shift_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_logs`
--
ALTER TABLE `system_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `assigned_to_employee_id` (`assigned_to_employee_id`),
  ADD KEY `assigned_to` (`assigned_to`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_files`
--
ALTER TABLE `task_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_logs`
--
ALTER TABLE `task_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_timer`
--
ALTER TABLE `task_timer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `task_time_logs`
--
ALTER TABLE `task_time_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=478;

--
-- AUTO_INCREMENT for table `activity_reasons`
--
ALTER TABLE `activity_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `attendance_breaks`
--
ALTER TABLE `attendance_breaks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `daily_work_reports`
--
ALTER TABLE `daily_work_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee_activities`
--
ALTER TABLE `employee_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee_salaries`
--
ALTER TABLE `employee_salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `performance_reports`
--
ALTER TABLE `performance_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proforma_invoices`
--
ALTER TABLE `proforma_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proforma_items`
--
ALTER TABLE `proforma_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recurring_profiles`
--
ALTER TABLE `recurring_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `shift_settings`
--
ALTER TABLE `shift_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_logs`
--
ALTER TABLE `system_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `task_comments`
--
ALTER TABLE `task_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_files`
--
ALTER TABLE `task_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_logs`
--
ALTER TABLE `task_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `task_timer`
--
ALTER TABLE `task_timer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `task_time_logs`
--
ALTER TABLE `task_time_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
