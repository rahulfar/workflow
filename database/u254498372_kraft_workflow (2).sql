-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 19, 2026 at 04:47 AM
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
-- Database: `u254498372_kraft_workflow`
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
(477, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/09/2026 10:08:58 AM (IST). Late minutes: 28', '2409:40d2:3046:84e4:c93:6ff:fe65:e266', '2026-09-26 10:08:58'),
(478, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:34:54'),
(479, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:35:04'),
(480, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/09/2026 10:35:04 AM (IST). Late minutes: 55', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:35:04'),
(481, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-25, ID: 104) as full_day', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:35:43'),
(482, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-25, ID: 105) as full_day', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:35:43'),
(483, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 26/09/2026 10:36:05 AM (IST). Status: half_day, Duration: 00h 01m 01s, Early Exit: 473m', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:36:05'),
(484, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:36:43'),
(485, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/09/2026 10:36:43 AM (IST). Late minutes: 56', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:36:43'),
(486, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 26/09/2026 10:39:05 AM (IST). Status: half_day, Duration: 00h 02m 22s, Early Exit: 470m', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:39:05'),
(487, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:39:25'),
(488, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5805:3c7f:7887:fce1', '2026-09-26 10:39:31'),
(489, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2405:201:6804:bcd8:5805:3c7f:7887:fce1', '2026-09-26 10:39:36'),
(490, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/09/2026 10:39:36 AM (IST). Late minutes: 59', '2405:201:6804:bcd8:5805:3c7f:7887:fce1', '2026-09-26 10:39:36'),
(491, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:40:51'),
(492, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:48:40'),
(493, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:49:27'),
(494, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/09/2026 10:49:27 AM (IST). Late minutes: 69', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:49:27'),
(495, 1, NULL, 'Call to Meeting', NULL, NULL, NULL, 'Meetings', 'Boss Kartikeyan called employee Rahul to meeting \'Rahul Discussion\' at 10:40.', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 10:49:57'),
(496, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 26/09/2026 11:05:42 AM (IST). Status: half_day, Duration: 00h 16m 15s, Early Exit: 444m', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 11:05:42'),
(497, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 11:07:22'),
(498, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/09/2026 11:07:22 AM (IST). Late minutes: 87', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 11:07:22'),
(499, 1, NULL, 'Call to Meeting', NULL, NULL, NULL, 'Meetings', 'Kartikeyan scheduled meeting \'Rahul Discussion\' with Rahul at 11:06.', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 11:07:37'),
(500, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:f141:b683:9f84:5228', '2026-09-26 11:59:49'),
(501, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3c83:e44:ed7b:79b0', '2026-09-28 09:27:56'),
(502, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305c:dbcb:b047:9eff:fe37:e27a', '2026-09-28 09:42:17'),
(503, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:3c83:e44:ed7b:79b0', '2026-09-28 09:42:46'),
(504, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/09/2026 09:42:46 AM (IST). Late minutes: 2', '2405:201:6804:bcd8:3c83:e44:ed7b:79b0', '2026-09-28 09:42:46'),
(505, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-26, ID: 110) as full_day', '2405:201:6804:bcd8:3c83:e44:ed7b:79b0', '2026-09-28 09:42:53'),
(506, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-26, ID: 111) as full_day', '2405:201:6804:bcd8:3c83:e44:ed7b:79b0', '2026-09-28 09:42:53'),
(507, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-26, ID: 114) as half_day', '2405:201:6804:bcd8:3c83:e44:ed7b:79b0', '2026-09-28 09:42:53'),
(508, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-26, ID: 116) as half_day', '2405:201:6804:bcd8:3c83:e44:ed7b:79b0', '2026-09-28 09:42:53'),
(509, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:a14e:ec35:b4ff:fe86:6c10', '2026-09-28 09:45:30'),
(510, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.222', '2026-09-28 09:47:27'),
(511, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '106.219.147.222', '2026-09-28 09:48:51'),
(512, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/09/2026 09:48:51 AM (IST). Late minutes: 8', '106.219.147.222', '2026-09-28 09:48:51'),
(513, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:245d:5cdf:792d:6bba', '2026-09-28 09:57:38'),
(514, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2405:201:6804:bcd8:245d:5cdf:792d:6bba', '2026-09-28 09:58:57'),
(515, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/09/2026 09:58:57 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:245d:5cdf:792d:6bba', '2026-09-28 09:58:57'),
(516, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3057:5c3c:8000::', '2026-09-28 09:59:04'),
(517, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:24e2:9275:e09a:1de6', '2026-09-28 10:02:31'),
(518, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:eeb2:e4a6:898a:a5bc:a615', '2026-09-28 10:35:51'),
(519, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:388a:6a39:cb23:844b', '2026-09-28 10:54:31'),
(520, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883c:eeb2:388a:6a39:cb23:844b', '2026-09-28 10:54:48'),
(521, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/09/2026 10:54:48 AM (IST). Late minutes: 74', '2401:4900:883c:eeb2:388a:6a39:cb23:844b', '2026-09-28 10:54:48'),
(522, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3c83:e44:ed7b:79b0', '2026-09-28 12:02:42'),
(523, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:eeb2:e4a6:898a:a5bc:a615', '2026-09-28 16:26:46'),
(524, 1, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #1 started activity \'Working on Task\'', '2401:4900:883c:eeb2:e4a6:898a:a5bc:a615', '2026-09-28 16:35:42'),
(525, 1, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/09/2026 04:35:42 PM (IST). Late minutes: 415', '2401:4900:883c:eeb2:e4a6:898a:a5bc:a615', '2026-09-28 16:35:42'),
(526, 1, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 28/09/2026 04:36:03 PM (IST). Status: half_day, Duration: 00h 00m 21s, Early Exit: 113m', '2401:4900:883c:eeb2:e4a6:898a:a5bc:a615', '2026-09-28 16:36:03'),
(527, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:ac25:c5b5:c855:b7ff', '2026-09-28 16:44:37'),
(528, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8d02:2deb:a31d:56b7', '2026-09-28 16:46:13'),
(529, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 16:48:38'),
(530, 2, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 16:49:10'),
(531, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 16:49:35'),
(532, 1, NULL, 'Add Employee', NULL, NULL, NULL, 'Employee Management', 'Admin added new employee: Anjali Singh (EMP-011)', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 16:51:58'),
(533, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 16:52:54'),
(534, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 16:53:24'),
(535, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 16:53:38'),
(536, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/09/2026 04:53:38 PM (IST). Late minutes: 433', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 16:53:38'),
(537, 2, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:ac25:c5b5:c855:b7ff', '2026-09-28 16:55:18'),
(538, 2, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 17:00:38'),
(539, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 17:01:09'),
(540, 1, NULL, 'Delete Employee', NULL, NULL, NULL, 'Employee Management', 'Admin deleted employee ID #11', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 17:01:41'),
(541, 1, NULL, 'Add Employee', NULL, NULL, NULL, 'Employee Management', 'Admin added new employee: Anjali Singh (EMP-011)', '2401:4900:883c:eeb2:c95b:3692:ead7:5582', '2026-09-28 17:03:12'),
(542, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:ac25:c5b5:c855:b7ff', '2026-09-28 17:04:12'),
(543, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:6d69:ce8c:e993:7683', '2026-09-29 09:35:30'),
(544, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-28, ID: 117) as full_day', '2401:4900:883c:eeb2:6d69:ce8c:e993:7683', '2026-09-29 09:35:31'),
(545, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883c:eeb2:6d69:ce8c:e993:7683', '2026-09-29 09:35:36'),
(546, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/09/2026 09:35:36 AM (IST). Late minutes: 0', '2401:4900:883c:eeb2:6d69:ce8c:e993:7683', '2026-09-29 09:35:36'),
(547, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-28, ID: 118) as full_day', '2401:4900:883c:eeb2:6d69:ce8c:e993:7683', '2026-09-29 09:35:43'),
(548, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-28, ID: 119) as full_day', '2401:4900:883c:eeb2:6d69:ce8c:e993:7683', '2026-09-29 09:35:43'),
(549, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-28, ID: 120) as half_day', '2401:4900:883c:eeb2:6d69:ce8c:e993:7683', '2026-09-29 09:35:43'),
(550, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-28, ID: 122) as half_day', '2401:4900:883c:eeb2:6d69:ce8c:e993:7683', '2026-09-29 09:35:43'),
(551, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.28', '2026-09-29 09:48:21'),
(552, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '49.43.160.28', '2026-09-29 09:48:31'),
(553, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/09/2026 09:48:31 AM (IST). Late minutes: 8', '49.43.160.28', '2026-09-29 09:48:31'),
(554, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305e:bb2:8000::', '2026-09-29 09:51:21'),
(555, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:305e:bb2:8000::', '2026-09-29 09:51:25'),
(556, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/09/2026 09:51:25 AM (IST). Late minutes: 11', '2409:40d2:305e:bb2:8000::', '2026-09-29 09:51:25'),
(557, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:91ed:ac31:9263:ce42', '2026-09-29 09:55:00'),
(558, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3052:de5a:a866:fcff:fe15:fa7f', '2026-09-29 09:56:40'),
(559, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3052:de5a:a866:fcff:fe15:fa7f', '2026-09-29 09:56:47'),
(560, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/09/2026 09:56:47 AM (IST). Late minutes: 16', '2409:40d2:3052:de5a:a866:fcff:fe15:fa7f', '2026-09-29 09:56:47'),
(561, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:91ed:ac31:9263:ce42', '2026-09-29 16:50:02'),
(562, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:e4b4:4e1e:a577:1fa4', '2026-09-29 16:51:18'),
(563, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae94:d2df::9509:b104', '2026-09-30 09:40:41'),
(564, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-29, ID: 124) as full_day', '2409:4085:ae94:d2df::9509:b104', '2026-09-30 09:40:42'),
(565, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae94:d2df::9509:b104', '2026-09-30 09:40:50'),
(566, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/09/2026 09:40:50 AM (IST). Late minutes: 0', '2409:4085:ae94:d2df::9509:b104', '2026-09-30 09:40:50'),
(567, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:31f4:3c65:c1f6:5b0b', '2026-09-30 09:44:43'),
(568, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-29, ID: 123) as full_day', '2401:4900:883c:eeb2:31f4:3c65:c1f6:5b0b', '2026-09-30 09:45:18'),
(569, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-29, ID: 125) as full_day', '2401:4900:883c:eeb2:31f4:3c65:c1f6:5b0b', '2026-09-30 09:45:18'),
(570, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-29, ID: 126) as full_day', '2401:4900:883c:eeb2:31f4:3c65:c1f6:5b0b', '2026-09-30 09:45:18'),
(571, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:d95:14a:b701:daa9', '2026-09-30 09:53:26'),
(572, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:d95:14a:b701:daa9', '2026-09-30 09:53:31'),
(573, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/09/2026 09:53:30 AM (IST). Late minutes: 13', '2405:201:6804:bcd8:d95:14a:b701:daa9', '2026-09-30 09:53:31'),
(574, 9, NULL, 'Meeting Declined', NULL, NULL, NULL, 'Meetings', 'Employee Abhay marked meeting \'Maharani Website Discussion\' as Declined.', '2405:201:6804:bcd8:d95:14a:b701:daa9', '2026-09-30 09:53:37'),
(575, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305c:2ea0:889f:a9ff:fe59:49c9', '2026-09-30 09:54:51'),
(576, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:305c:2ea0:889f:a9ff:fe59:49c9', '2026-09-30 09:54:57'),
(577, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/09/2026 09:54:57 AM (IST). Late minutes: 14', '2409:40d2:305c:2ea0:889f:a9ff:fe59:49c9', '2026-09-30 09:54:57'),
(578, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:c97c:6893:c782:6497', '2026-09-30 09:56:57'),
(579, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883c:eeb2:c97c:6893:c782:6497', '2026-09-30 09:57:04'),
(580, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/09/2026 09:57:04 AM (IST). Late minutes: 17', '2401:4900:883c:eeb2:c97c:6893:c782:6497', '2026-09-30 09:57:04'),
(581, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3054:2781:6867:86ff:fef6:325f', '2026-09-30 10:02:52'),
(582, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8430:7777:d406:1ecd', '2026-09-30 16:30:45');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(583, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8430:7777:d406:1ecd', '2026-09-30 17:20:21'),
(584, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:31f4:3c65:c1f6:5b0b', '2026-09-30 18:25:36'),
(585, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 30/09/2026 06:29:29 PM (IST). Status: half_day, Duration: 08h 48m 39s, Early Exit: 0m', '2401:4900:883c:eeb2:31f4:3c65:c1f6:5b0b', '2026-09-30 18:29:29'),
(586, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae94:d2df::9509:b104', '2026-10-01 09:39:41'),
(587, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:148e:587e:2e04:c858', '2026-10-01 09:39:50'),
(588, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae94:d2df::9509:b104', '2026-10-01 09:39:51'),
(589, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/10/2026 09:39:51 AM (IST). Late minutes: 0', '2409:4085:ae94:d2df::9509:b104', '2026-10-01 09:39:51'),
(590, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-30, ID: 128) as full_day', '2405:201:6804:bcd8:148e:587e:2e04:c858', '2026-10-01 09:39:52'),
(591, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:148e:587e:2e04:c858', '2026-10-01 09:39:54'),
(592, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/10/2026 09:39:54 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:148e:587e:2e04:c858', '2026-10-01 09:39:54'),
(593, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:41:37'),
(594, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-30, ID: 129) as full_day', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:42:16'),
(595, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-09-30, ID: 130) as full_day', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:42:16'),
(596, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:8e8e:802f:287e:7d2e', '2026-10-01 09:43:56'),
(597, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:c5ea:678f:2bff:7941', '2026-10-01 09:45:27'),
(598, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883c:eeb2:c5ea:678f:2bff:7941', '2026-10-01 09:45:37'),
(599, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/10/2026 09:45:37 AM (IST). Late minutes: 5', '2401:4900:883c:eeb2:c5ea:678f:2bff:7941', '2026-10-01 09:45:37'),
(600, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:45:42'),
(601, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:47:17'),
(602, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:47:23'),
(603, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/10/2026 09:47:22 AM (IST). Late minutes: 7', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:47:23'),
(604, 10, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:48:57'),
(605, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:50f5:2d4c:49d1:507c', '2026-10-01 09:49:01'),
(606, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:c55f:8000::', '2026-10-01 09:50:47'),
(607, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:304b:c55f:8000::', '2026-10-01 09:50:52'),
(608, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/10/2026 09:50:52 AM (IST). Late minutes: 10', '2409:40d2:304b:c55f:8000::', '2026-10-01 09:50:52'),
(609, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:c5ea:678f:2bff:7941', '2026-10-01 10:05:32'),
(610, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:84ce:25e:924c:87e8', '2026-10-01 10:06:36'),
(611, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:ccaa:10fc:41ff:fefa:315f', '2026-10-01 10:09:38'),
(612, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3053:ccaa:10fc:41ff:fefa:315f', '2026-10-01 10:09:46'),
(613, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 01/10/2026 10:09:46 AM (IST). Late minutes: 29', '2409:40d2:3053:ccaa:10fc:41ff:fefa:315f', '2026-10-01 10:09:46'),
(614, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:d75:7c2f:7fa2:217', '2026-10-01 12:58:00'),
(615, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:5c7a:74ab:63fe:eb93', '2026-10-01 17:37:30'),
(616, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3540:193e:5426:4b25', '2026-10-01 17:54:21'),
(617, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae94:d2df::9509:b104', '2026-10-01 18:28:31'),
(618, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 01/10/2026 06:30:11 PM (IST). Status: full_day, Duration: 08h 50m 20s, Early Exit: 0m', '2409:4085:ae94:d2df::9509:b104', '2026-10-01 18:30:11'),
(619, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae94:d2df::9509:b104', '2026-10-02 09:34:28'),
(620, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae94:d2df::9509:b104', '2026-10-02 09:34:35'),
(621, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/10/2026 09:34:35 AM (IST). Late minutes: 0', '2409:4085:ae94:d2df::9509:b104', '2026-10-02 09:34:35'),
(622, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:37:55'),
(623, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-01, ID: 132) as full_day', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:37:57'),
(624, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:37:59'),
(625, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/10/2026 09:37:59 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:37:59'),
(626, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:5c7a:74ab:63fe:eb93', '2026-10-02 09:39:28'),
(627, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-01, ID: 133) as full_day', '2401:4900:883c:eeb2:5c7a:74ab:63fe:eb93', '2026-10-02 09:39:30'),
(628, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883c:eeb2:5c7a:74ab:63fe:eb93', '2026-10-02 09:39:56'),
(629, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/10/2026 09:39:56 AM (IST). Late minutes: 0', '2401:4900:883c:eeb2:5c7a:74ab:63fe:eb93', '2026-10-02 09:39:56'),
(630, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:40:40'),
(631, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:41:06'),
(632, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-01, ID: 134) as full_day', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:41:08'),
(633, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:41:11'),
(634, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/10/2026 09:41:11 AM (IST). Late minutes: 1', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:41:11'),
(635, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:399c:8eb3:ad8a:f078', '2026-10-02 09:41:16'),
(636, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-01, ID: 135) as full_day', '2401:4900:883c:eeb2:399c:8eb3:ad8a:f078', '2026-10-02 09:41:27'),
(637, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-01, ID: 136) as full_day', '2401:4900:883c:eeb2:399c:8eb3:ad8a:f078', '2026-10-02 09:41:27'),
(638, 10, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:41:46'),
(639, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3436:c21c:1b6b:d8dc', '2026-10-02 09:41:49'),
(640, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3055:e9d3:7478:9cff:fe4f:53f', '2026-10-02 09:46:41'),
(641, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3055:e9d3:7478:9cff:fe4f:53f', '2026-10-02 09:46:46'),
(642, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/10/2026 09:46:46 AM (IST). Late minutes: 6', '2409:40d2:3055:e9d3:7478:9cff:fe4f:53f', '2026-10-02 09:46:46'),
(643, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.147.222', '2026-10-02 18:31:23'),
(644, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 02/10/2026 06:31:36 PM (IST). Status: full_day, Duration: 08h 57m 01s, Early Exit: 0m', '106.219.147.222', '2026-10-02 18:31:36'),
(645, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305a:f445:748a:5ff:fefd:83b0', '2026-10-05 09:39:58'),
(646, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-02, ID: 138) as full_day', '2409:40d2:305a:f445:748a:5ff:fefd:83b0', '2026-10-05 09:39:59'),
(647, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:305a:f445:748a:5ff:fefd:83b0', '2026-10-05 09:40:02'),
(648, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/10/2026 09:40:02 AM (IST). Late minutes: 0', '2409:40d2:305a:f445:748a:5ff:fefd:83b0', '2026-10-05 09:40:02'),
(649, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:1845:a6cf:a827:3b1c', '2026-10-05 09:54:46'),
(650, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-02, ID: 140) as full_day', '2401:4900:883c:eeb2:1845:a6cf:a827:3b1c', '2026-10-05 09:54:48'),
(651, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883c:eeb2:1845:a6cf:a827:3b1c', '2026-10-05 09:54:54'),
(652, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/10/2026 09:54:54 AM (IST). Late minutes: 14', '2401:4900:883c:eeb2:1845:a6cf:a827:3b1c', '2026-10-05 09:54:54'),
(653, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-05 09:57:22'),
(654, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-05 09:57:30'),
(655, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/10/2026 09:57:30 AM (IST). Late minutes: 17', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-05 09:57:30'),
(656, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304a:8e38:94ee:19ff:fe11:d43d', '2026-10-05 10:01:40'),
(657, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-02, ID: 141) as full_day', '2409:40d2:304a:8e38:94ee:19ff:fe11:d43d', '2026-10-05 10:01:42'),
(658, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:304a:8e38:94ee:19ff:fe11:d43d', '2026-10-05 10:01:45'),
(659, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/10/2026 10:01:45 AM (IST). Late minutes: 21', '2409:40d2:304a:8e38:94ee:19ff:fe11:d43d', '2026-10-05 10:01:45'),
(660, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:950:e5a3:e68f:a550', '2026-10-05 10:08:29'),
(661, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-02, ID: 139) as full_day', '2401:4900:883c:eeb2:950:e5a3:e68f:a550', '2026-10-05 10:08:33'),
(662, 4, NULL, 'Meeting Declined', NULL, NULL, NULL, 'Meetings', 'Employee Rahul marked meeting \'Rahul Discussion\' as Declined.', '2401:4900:883c:eeb2:950:e5a3:e68f:a550', '2026-10-05 10:08:38'),
(663, 4, NULL, 'Meeting Declined', NULL, NULL, NULL, 'Meetings', 'Employee Rahul marked meeting \'Rahul Discussion\' as Declined.', '2401:4900:883c:eeb2:950:e5a3:e68f:a550', '2026-10-05 10:08:49'),
(664, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:ad52:3246:2dd0:451c', '2026-10-05 10:13:23'),
(665, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:cd58:c69a:c920:90bd', '2026-10-05 10:20:33'),
(666, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883c:eeb2:cd58:c69a:c920:90bd', '2026-10-05 10:20:39'),
(667, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/10/2026 10:20:39 AM (IST). Late minutes: 40', '2401:4900:883c:eeb2:cd58:c69a:c920:90bd', '2026-10-05 10:20:39'),
(668, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:eeb2:950:e5a3:e68f:a550', '2026-10-05 10:29:22'),
(669, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:dc4e:cb32:30ca:2163', '2026-10-05 17:49:21'),
(670, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:eeb2:f477:8fc5:dc5f:f46a', '2026-10-05 18:16:55'),
(671, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-05 18:30:57'),
(672, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 05/10/2026 06:31:07 PM (IST). Status: half_day, Duration: 08h 33m 37s, Early Exit: 0m', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-05 18:31:07'),
(673, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:45d2:5dc6:e498:84e7', '2026-10-06 09:33:19'),
(674, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2405:201:6804:bcd8:45d2:5dc6:e498:84e7', '2026-10-06 09:33:24'),
(675, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/10/2026 09:33:24 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:45d2:5dc6:e498:84e7', '2026-10-06 09:33:24'),
(676, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:4936:9c4e:ec6c:d38a', '2026-10-06 09:40:20'),
(677, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-05, ID: 146) as full_day', '2401:4900:883c:432e:4936:9c4e:ec6c:d38a', '2026-10-06 09:40:22'),
(678, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883c:432e:4936:9c4e:ec6c:d38a', '2026-10-06 09:40:25'),
(679, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/10/2026 09:40:25 AM (IST). Late minutes: 0', '2401:4900:883c:432e:4936:9c4e:ec6c:d38a', '2026-10-06 09:40:25'),
(680, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-06 09:40:36'),
(681, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2f05:ecbf::28da:c26f', '2026-10-06 09:41:01'),
(682, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-05, ID: 143) as full_day', '2402:8100:2f05:ecbf::28da:c26f', '2026-10-06 09:41:03'),
(683, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2f05:ecbf::28da:c26f', '2026-10-06 09:41:07'),
(684, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/10/2026 09:41:07 AM (IST). Late minutes: 1', '2402:8100:2f05:ecbf::28da:c26f', '2026-10-06 09:41:07'),
(685, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-06 09:41:15'),
(686, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/10/2026 09:41:15 AM (IST). Late minutes: 1', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-06 09:41:15'),
(687, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5ba7:44e5:327c:87ef', '2026-10-06 09:47:38'),
(688, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-05, ID: 142) as full_day', '2405:201:6804:bcd8:5ba7:44e5:327c:87ef', '2026-10-06 09:47:40'),
(689, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:5ba7:44e5:327c:87ef', '2026-10-06 09:47:42'),
(690, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/10/2026 09:47:42 AM (IST). Late minutes: 7', '2405:201:6804:bcd8:5ba7:44e5:327c:87ef', '2026-10-06 09:47:42'),
(691, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3108:81b9:6077:3775', '2026-10-06 09:50:22'),
(692, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304e:4ca4:60dd:b6ff:febc:41cd', '2026-10-06 09:50:22'),
(693, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-05, ID: 145) as full_day', '2409:40d2:304e:4ca4:60dd:b6ff:febc:41cd', '2026-10-06 09:50:24'),
(694, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:304e:4ca4:60dd:b6ff:febc:41cd', '2026-10-06 09:50:28'),
(695, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/10/2026 09:50:28 AM (IST). Late minutes: 10', '2409:40d2:304e:4ca4:60dd:b6ff:febc:41cd', '2026-10-06 09:50:28'),
(696, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:117a:10b6:9da5:d355', '2026-10-06 09:50:37'),
(697, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883c:432e:117a:10b6:9da5:d355', '2026-10-06 09:50:43'),
(698, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/10/2026 09:50:43 AM (IST). Late minutes: 10', '2401:4900:883c:432e:117a:10b6:9da5:d355', '2026-10-06 09:50:43'),
(699, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:35c0:6330:e063:e2b7', '2026-10-06 09:52:59'),
(700, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.145.135', '2026-10-06 18:32:46'),
(701, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 06/10/2026 06:32:58 PM (IST). Status: full_day, Duration: 08h 51m 43s, Early Exit: 0m', '106.219.145.135', '2026-10-06 18:32:58'),
(702, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3819:7a30:5e7d:31f1', '2026-10-07 09:30:48'),
(703, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-06, ID: 147) as full_day', '2401:4900:883c:432e:3819:7a30:5e7d:31f1', '2026-10-07 09:30:50'),
(704, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883c:432e:3819:7a30:5e7d:31f1', '2026-10-07 09:30:53'),
(705, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/10/2026 09:30:53 AM (IST). Late minutes: 0', '2401:4900:883c:432e:3819:7a30:5e7d:31f1', '2026-10-07 09:30:53'),
(706, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-07 09:35:12'),
(707, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-07 09:35:18'),
(708, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/10/2026 09:35:18 AM (IST). Late minutes: 0', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-07 09:35:18'),
(709, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3819:7a30:5e7d:31f1', '2026-10-07 09:40:19'),
(710, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:ec44:eafb:f30a:4cdb', '2026-10-07 09:49:22'),
(711, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-06, ID: 148) as full_day', '2401:4900:883c:432e:ec44:eafb:f30a:4cdb', '2026-10-07 09:49:50'),
(712, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-06, ID: 149) as full_day', '2401:4900:883c:432e:ec44:eafb:f30a:4cdb', '2026-10-07 09:49:50'),
(713, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-06, ID: 151) as full_day', '2401:4900:883c:432e:ec44:eafb:f30a:4cdb', '2026-10-07 09:49:50'),
(714, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-06, ID: 152) as full_day', '2401:4900:883c:432e:ec44:eafb:f30a:4cdb', '2026-10-07 09:49:50'),
(715, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-06, ID: 153) as full_day', '2401:4900:883c:432e:ec44:eafb:f30a:4cdb', '2026-10-07 09:49:50'),
(716, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:2610:d6d2:7700:502c', '2026-10-07 09:55:58'),
(717, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883c:432e:2610:d6d2:7700:502c', '2026-10-07 09:56:03'),
(718, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/10/2026 09:56:03 AM (IST). Late minutes: 16', '2401:4900:883c:432e:2610:d6d2:7700:502c', '2026-10-07 09:56:03'),
(719, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3058:478c:3c57:29ff:fe1b:6ee8', '2026-10-07 10:02:56'),
(720, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304d:5ae6:acf5:80ff:feed:b81c', '2026-10-07 10:02:57'),
(721, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3058:478c:3c57:29ff:fe1b:6ee8', '2026-10-07 10:03:01'),
(722, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/10/2026 10:03:00 AM (IST). Late minutes: 23', '2409:40d2:3058:478c:3c57:29ff:fe1b:6ee8', '2026-10-07 10:03:01'),
(723, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:304d:5ae6:acf5:80ff:feed:b81c', '2026-10-07 10:03:03'),
(724, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/10/2026 10:03:02 AM (IST). Late minutes: 23', '2409:40d2:304d:5ae6:acf5:80ff:feed:b81c', '2026-10-07 10:03:03'),
(725, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:31dc:8c05:95e9:d9b8', '2026-10-07 10:04:25'),
(726, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:fcdb:6125:ee75:4c26', '2026-10-07 10:11:37'),
(727, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883c:432e:fcdb:6125:ee75:4c26', '2026-10-07 10:12:03'),
(728, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/10/2026 10:12:03 AM (IST). Late minutes: 32', '2401:4900:883c:432e:fcdb:6125:ee75:4c26', '2026-10-07 10:12:03'),
(729, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:a517:ad94:5436:9813', '2026-10-07 10:16:53'),
(730, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:a517:ad94:5436:9813', '2026-10-07 10:19:20'),
(731, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-07 18:32:09'),
(732, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 07/10/2026 06:32:25 PM (IST). Status: full_day, Duration: 08h 57m 07s, Early Exit: 0m', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-07 18:32:25'),
(733, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3044:966f:2c24:82ff:fe59:86a0', '2026-10-08 09:40:00'),
(734, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-07, ID: 157) as full_day', '2409:40d2:3044:966f:2c24:82ff:fe59:86a0', '2026-10-08 09:40:02'),
(735, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3044:966f:2c24:82ff:fe59:86a0', '2026-10-08 09:40:04'),
(736, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 08/10/2026 09:40:04 AM (IST). Late minutes: 0', '2409:40d2:3044:966f:2c24:82ff:fe59:86a0', '2026-10-08 09:40:04'),
(737, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-07, ID: 154) as full_day', '2409:40d2:3044:966f:2c24:82ff:fe59:86a0', '2026-10-08 09:40:13'),
(738, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-07, ID: 156) as full_day', '2409:40d2:3044:966f:2c24:82ff:fe59:86a0', '2026-10-08 09:40:13'),
(739, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-07, ID: 158) as full_day', '2409:40d2:3044:966f:2c24:82ff:fe59:86a0', '2026-10-08 09:40:13'),
(740, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-07, ID: 159) as full_day', '2409:40d2:3044:966f:2c24:82ff:fe59:86a0', '2026-10-08 09:40:13'),
(741, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:6962:574d:e3a4:11bd', '2026-10-08 09:43:48'),
(742, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2f41:eea9::29c5:edf8', '2026-10-08 09:43:55'),
(743, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2f41:eea9::29c5:edf8', '2026-10-08 09:43:59'),
(744, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 08/10/2026 09:43:59 AM (IST). Late minutes: 3', '2402:8100:2f41:eea9::29c5:edf8', '2026-10-08 09:43:59'),
(745, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:e040:8000::', '2026-10-08 09:45:50'),
(746, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:304b:e040:8000::', '2026-10-08 09:45:55'),
(747, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 08/10/2026 09:45:55 AM (IST). Late minutes: 5', '2409:40d2:304b:e040:8000::', '2026-10-08 09:45:55'),
(748, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:e040:8000::', '2026-10-08 09:48:59'),
(749, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3003:9964:80e3:15ff:fea5:9a40', '2026-10-08 09:56:36'),
(750, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3003:9964:80e3:15ff:fea5:9a40', '2026-10-08 09:56:44'),
(751, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 08/10/2026 09:56:44 AM (IST). Late minutes: 16', '2409:40d2:3003:9964:80e3:15ff:fea5:9a40', '2026-10-08 09:56:44'),
(752, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:587c:ffa0:fb23:34eb', '2026-10-08 10:00:30'),
(753, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883c:432e:587c:ffa0:fb23:34eb', '2026-10-08 10:00:35'),
(754, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 08/10/2026 10:00:35 AM (IST). Late minutes: 20', '2401:4900:883c:432e:587c:ffa0:fb23:34eb', '2026-10-08 10:00:35'),
(755, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:8cb6:93e5:cbeb:4f63', '2026-10-09 09:37:57'),
(756, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883c:432e:8cb6:93e5:cbeb:4f63', '2026-10-09 09:38:01'),
(757, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/10/2026 09:38:01 AM (IST). Late minutes: 0', '2401:4900:883c:432e:8cb6:93e5:cbeb:4f63', '2026-10-09 09:38:01'),
(758, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-09 09:43:35'),
(759, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-09 09:43:46'),
(760, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/10/2026 09:43:45 AM (IST). Late minutes: 3', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-09 09:43:46'),
(761, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:e040:8000::', '2026-10-09 09:52:45'),
(762, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-08, ID: 162) as full_day', '2409:40d2:304b:e040:8000::', '2026-10-09 09:52:47'),
(763, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:304b:e040:8000::', '2026-10-09 09:52:50'),
(764, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/10/2026 09:52:50 AM (IST). Late minutes: 12', '2409:40d2:304b:e040:8000::', '2026-10-09 09:52:50'),
(765, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2f03:e7fc::2a5c:4d4c', '2026-10-09 09:54:22'),
(766, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-08, ID: 161) as full_day', '2402:8100:2f03:e7fc::2a5c:4d4c', '2026-10-09 09:54:24'),
(767, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2f03:e7fc::2a5c:4d4c', '2026-10-09 09:54:28'),
(768, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/10/2026 09:54:28 AM (IST). Late minutes: 14', '2402:8100:2f03:e7fc::2a5c:4d4c', '2026-10-09 09:54:28'),
(769, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:31be:55f5:dc3b:cfab', '2026-10-09 09:54:36'),
(770, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-08, ID: 160) as full_day', '2401:4900:883c:432e:31be:55f5:dc3b:cfab', '2026-10-09 09:54:37'),
(771, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-08, ID: 163) as full_day', '2401:4900:883c:432e:31be:55f5:dc3b:cfab', '2026-10-09 09:54:37'),
(772, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-08, ID: 164) as full_day', '2401:4900:883c:432e:31be:55f5:dc3b:cfab', '2026-10-09 09:54:37'),
(773, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:e70c:e6c:8c22:f7e9', '2026-10-09 10:03:00'),
(774, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:e70c:e6c:8c22:f7e9', '2026-10-09 10:03:05'),
(775, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/10/2026 10:03:04 AM (IST). Late minutes: 23', '2405:201:6804:bcd8:e70c:e6c:8c22:f7e9', '2026-10-09 10:03:05'),
(776, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:300e:3142:86b:1bff:fe0a:2f62', '2026-10-09 10:06:13'),
(777, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:300e:3142:86b:1bff:fe0a:2f62', '2026-10-09 10:06:22'),
(778, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/10/2026 10:06:22 AM (IST). Late minutes: 26', '2409:40d2:300e:3142:86b:1bff:fe0a:2f62', '2026-10-09 10:06:22'),
(779, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:3929:5e54:d2fd:fe13', '2026-10-09 13:37:58'),
(780, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:2610:d6d2:7700:502c', '2026-10-09 13:40:40'),
(781, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883c:432e:2610:d6d2:7700:502c', '2026-10-09 13:40:44'),
(782, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 09/10/2026 01:40:44 PM (IST). Late minutes: 240', '2401:4900:883c:432e:2610:d6d2:7700:502c', '2026-10-09 13:40:44'),
(783, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:3929:5e54:d2fd:fe13', '2026-10-09 13:52:04'),
(784, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-09 18:35:01'),
(785, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 09/10/2026 06:35:28 PM (IST). Status: full_day, Duration: 08h 51m 43s, Early Exit: 0m', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-09 18:35:28'),
(786, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:8668:bb07:9d1b:6860:e954:a06d', '2026-10-10 08:57:27'),
(787, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-09, ID: 165) as full_day', '2401:4900:8668:bb07:9d1b:6860:e954:a06d', '2026-10-10 08:57:29'),
(788, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:8668:bb07:9d1b:6860:e954:a06d', '2026-10-10 08:57:37'),
(789, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:b028:6c5c:9a03:8bd8', '2026-10-10 09:33:19'),
(790, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883c:432e:b028:6c5c:9a03:8bd8', '2026-10-10 09:33:23'),
(791, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/10/2026 09:33:23 AM (IST). Late minutes: 0', '2401:4900:883c:432e:b028:6c5c:9a03:8bd8', '2026-10-10 09:33:23'),
(792, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3055:df91:341f:f6ff:fe99:8e2e', '2026-10-10 09:40:38'),
(793, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-09, ID: 170) as full_day', '2409:40d2:3055:df91:341f:f6ff:fe99:8e2e', '2026-10-10 09:40:40'),
(794, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3055:df91:341f:f6ff:fe99:8e2e', '2026-10-10 09:40:43'),
(795, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/10/2026 09:40:43 AM (IST). Late minutes: 0', '2409:40d2:3055:df91:341f:f6ff:fe99:8e2e', '2026-10-10 09:40:43'),
(796, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3058:40f1:84c1:e5ff:feaa:dabc', '2026-10-10 09:42:11'),
(797, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-09, ID: 169) as full_day', '2409:40d2:3058:40f1:84c1:e5ff:feaa:dabc', '2026-10-10 09:42:13'),
(798, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3058:40f1:84c1:e5ff:feaa:dabc', '2026-10-10 09:42:20'),
(799, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/10/2026 09:42:20 AM (IST). Late minutes: 2', '2409:40d2:3058:40f1:84c1:e5ff:feaa:dabc', '2026-10-10 09:42:20'),
(800, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304a:568b:8000::', '2026-10-10 09:44:03'),
(801, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-09, ID: 167) as full_day', '2409:40d2:304a:568b:8000::', '2026-10-10 09:44:05'),
(802, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:304a:568b:8000::', '2026-10-10 09:44:09'),
(803, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/10/2026 09:44:09 AM (IST). Late minutes: 4', '2409:40d2:304a:568b:8000::', '2026-10-10 09:44:09'),
(804, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304a:568b:8000::', '2026-10-10 09:58:02'),
(805, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-09, ID: 168) as full_day', '2409:40d2:304a:568b:8000::', '2026-10-10 09:58:54'),
(806, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-09, ID: 171) as half_day', '2409:40d2:304a:568b:8000::', '2026-10-10 09:58:54'),
(807, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:6d77:ad45:1e30:13c0', '2026-10-10 10:00:44'),
(808, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2405:201:6804:bcd8:6d77:ad45:1e30:13c0', '2026-10-10 10:00:51'),
(809, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/10/2026 10:00:51 AM (IST). Late minutes: 20', '2405:201:6804:bcd8:6d77:ad45:1e30:13c0', '2026-10-10 10:00:51'),
(810, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:b9bd:b38c:d9d:7a37', '2026-10-10 10:09:28'),
(811, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:3c25:4bf9:f5a8:b8b2', '2026-10-10 10:25:18'),
(812, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:5d4e:cf20:9bd4:565a', '2026-10-10 10:32:20'),
(813, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3c25:4bf9:f5a8:b8b2', '2026-10-10 10:32:32'),
(814, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6804:bcd8:5d4e:cf20:9bd4:565a', '2026-10-10 10:33:06'),
(815, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/10/2026 10:33:06 AM (IST). Late minutes: 53', '2405:201:6804:bcd8:5d4e:cf20:9bd4:565a', '2026-10-10 10:33:06'),
(816, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3c25:4bf9:f5a8:b8b2', '2026-10-10 10:33:20'),
(817, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3048:667:241a:74ff:fe7b:c748', '2026-10-10 10:46:42'),
(818, 5, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 10/10/2026 10:49:20 AM (IST). Status: half_day, Duration: 01h 03m 14s, Early Exit: 460m', '2409:40d2:3048:667:241a:74ff:fe7b:c748', '2026-10-10 10:49:20'),
(819, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:3048:667:241a:74ff:fe7b:c748', '2026-10-10 10:49:25'),
(820, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/10/2026 10:49:25 AM (IST). Late minutes: 69', '2409:40d2:3048:667:241a:74ff:fe7b:c748', '2026-10-10 10:49:25'),
(821, 5, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 10/10/2026 10:49:32 AM (IST). Status: half_day, Duration: 00h 00m 07s, Early Exit: 460m', '2409:40d2:3048:667:241a:74ff:fe7b:c748', '2026-10-10 10:49:32'),
(822, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8c61:3c06:a2ee:9475', '2026-10-10 10:57:01'),
(823, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6804:bcd8:8c61:3c06:a2ee:9475', '2026-10-10 10:57:08'),
(824, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 10/10/2026 10:57:08 AM (IST). Late minutes: 77', '2405:201:6804:bcd8:8c61:3c06:a2ee:9475', '2026-10-10 10:57:08'),
(825, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:24:46'),
(826, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:24:55'),
(827, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/10/2026 09:24:55 AM (IST). Late minutes: 0', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:24:55'),
(828, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-10, ID: 172) as full_day', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:25:06'),
(829, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-10, ID: 173) as full_day', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:25:06'),
(830, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-10, ID: 174) as full_day', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:25:06'),
(831, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-10, ID: 175) as full_day', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:25:06'),
(832, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-10, ID: 176) as full_day', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:25:06'),
(833, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-10, ID: 179) as half_day', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-12 09:25:06'),
(834, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:3bd8:88c3:6923:b481', '2026-10-12 09:29:36'),
(835, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6818:f058:3bd8:88c3:6923:b481', '2026-10-12 09:29:42'),
(836, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/10/2026 09:29:42 AM (IST). Late minutes: 0', '2405:201:6818:f058:3bd8:88c3:6923:b481', '2026-10-12 09:29:42'),
(837, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.145.135', '2026-10-12 09:30:51'),
(838, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '106.219.145.135', '2026-10-12 09:30:54'),
(839, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/10/2026 09:30:54 AM (IST). Late minutes: 0', '106.219.145.135', '2026-10-12 09:30:54'),
(840, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '106.219.145.135', '2026-10-12 09:31:05'),
(841, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2f49:ec48::2202:418', '2026-10-12 09:31:08'),
(842, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3054:8680:a061:3bff:fe69:2ede', '2026-10-12 09:34:31'),
(843, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3054:8680:a061:3bff:fe69:2ede', '2026-10-12 09:34:46'),
(844, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/10/2026 09:34:46 AM (IST). Late minutes: 0', '2409:40d2:3054:8680:a061:3bff:fe69:2ede', '2026-10-12 09:34:46'),
(845, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3040:38a8:341a:4aff:fe02:9f42', '2026-10-12 09:43:55'),
(846, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2f49:ec48::2202:418', '2026-10-12 09:44:28'),
(847, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/10/2026 09:44:28 AM (IST). Late minutes: 4', '2402:8100:2f49:ec48::2202:418', '2026-10-12 09:44:28'),
(848, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3018:f158:8000::', '2026-10-12 09:45:45'),
(849, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3018:f158:8000::', '2026-10-12 09:47:09'),
(850, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/10/2026 09:47:09 AM (IST). Late minutes: 7', '2409:40d2:3018:f158:8000::', '2026-10-12 09:47:09'),
(851, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3040:38a8:341a:4aff:fe02:9f42', '2026-10-12 09:50:11'),
(852, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/10/2026 09:50:11 AM (IST). Late minutes: 10', '2409:40d2:3040:38a8:341a:4aff:fe02:9f42', '2026-10-12 09:50:11'),
(853, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-12 09:56:50'),
(854, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-12 09:56:55'),
(855, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/10/2026 09:56:55 AM (IST). Late minutes: 16', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-12 09:56:55'),
(856, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3ded:d24f:941d:9018', '2026-10-12 10:00:47'),
(857, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:d087:b77d:bfd4:12f0', '2026-10-12 16:42:46'),
(858, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #28 created and assigned to User #4', '2405:201:6804:bcd8:d087:b77d:bfd4:12f0', '2026-10-12 16:49:03'),
(859, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '47.15.118.207', '2026-10-13 01:04:13'),
(860, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-12, ID: 187) as full_day', '47.15.118.207', '2026-10-13 01:04:15'),
(861, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3049:1d46:78db:93ff:fe1f:1c5f', '2026-10-13 09:25:59'),
(862, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-12, ID: 183) as full_day', '2409:40d2:3049:1d46:78db:93ff:fe1f:1c5f', '2026-10-13 09:26:01'),
(863, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3049:1d46:78db:93ff:fe1f:1c5f', '2026-10-13 09:26:03'),
(864, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 09:26:03 AM (IST). Late minutes: 0', '2409:40d2:3049:1d46:78db:93ff:fe1f:1c5f', '2026-10-13 09:26:03'),
(865, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:8661:e7ed:b8ef:1e50:a739:f3e7', '2026-10-13 09:31:01');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(866, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-12, ID: 182) as full_day', '2401:4900:8661:e7ed:b8ef:1e50:a739:f3e7', '2026-10-13 09:31:03'),
(867, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:8661:e7ed:b8ef:1e50:a739:f3e7', '2026-10-13 09:31:06'),
(868, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 09:31:05 AM (IST). Late minutes: 0', '2401:4900:8661:e7ed:b8ef:1e50:a739:f3e7', '2026-10-13 09:31:06'),
(869, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:8661:e7ed:b8ef:1e50:a739:f3e7', '2026-10-13 09:31:14'),
(870, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304a:206a:241a:74ff:fe7b:c748', '2026-10-13 09:31:30'),
(871, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-12, ID: 181) as full_day', '2409:40d2:304a:206a:241a:74ff:fe7b:c748', '2026-10-13 09:31:32'),
(872, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:304a:206a:241a:74ff:fe7b:c748', '2026-10-13 09:31:37'),
(873, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 09:31:37 AM (IST). Late minutes: 0', '2409:40d2:304a:206a:241a:74ff:fe7b:c748', '2026-10-13 09:31:37'),
(874, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-12, ID: 180) as full_day', '2409:40d2:304a:206a:241a:74ff:fe7b:c748', '2026-10-13 09:31:55'),
(875, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-12, ID: 184) as full_day', '2409:40d2:304a:206a:241a:74ff:fe7b:c748', '2026-10-13 09:31:55'),
(876, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-12, ID: 185) as full_day', '2409:40d2:304a:206a:241a:74ff:fe7b:c748', '2026-10-13 09:31:55'),
(877, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-12, ID: 186) as full_day', '2409:40d2:304a:206a:241a:74ff:fe7b:c748', '2026-10-13 09:31:55'),
(878, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2f57:ef77::2293:90b1', '2026-10-13 09:38:29'),
(879, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2f57:ef77::2293:90b1', '2026-10-13 09:38:54'),
(880, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 09:38:54 AM (IST). Late minutes: 0', '2402:8100:2f57:ef77::2293:90b1', '2026-10-13 09:38:54'),
(881, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304e:3308:7c40:7dff:fe7c:2344', '2026-10-13 09:41:34'),
(882, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:304e:3308:7c40:7dff:fe7c:2344', '2026-10-13 09:41:39'),
(883, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 09:41:39 AM (IST). Late minutes: 1', '2409:40d2:304e:3308:7c40:7dff:fe7c:2344', '2026-10-13 09:41:39'),
(884, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3047:9a2e:8000::', '2026-10-13 09:43:32'),
(885, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3047:9a2e:8000::', '2026-10-13 09:45:01'),
(886, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 09:45:01 AM (IST). Late minutes: 5', '2409:40d2:3047:9a2e:8000::', '2026-10-13 09:45:01'),
(887, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-13 09:46:15'),
(888, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-13 09:46:22'),
(889, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 09:46:22 AM (IST). Late minutes: 6', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-13 09:46:22'),
(890, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3013:f421:bd35:61ba:cba6:6b5c', '2026-10-13 09:48:46'),
(891, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2409:40d2:3013:f421:bd35:61ba:cba6:6b5c', '2026-10-13 09:48:54'),
(892, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 09:48:54 AM (IST). Late minutes: 8', '2409:40d2:3013:f421:bd35:61ba:cba6:6b5c', '2026-10-13 09:48:54'),
(893, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3047:9a2e:8000::', '2026-10-13 09:50:59'),
(894, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c18d:4f9a:37a4:5a08', '2026-10-13 09:55:13'),
(895, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:acea:b574:bab4:fc4c', '2026-10-13 10:29:49'),
(896, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:acea:b574:bab4:fc4c', '2026-10-13 11:33:44'),
(897, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:906e:7a71:8794:8700', '2026-10-13 14:31:48'),
(898, 1, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #1 started activity \'Working on Task\'', '2405:201:6804:bcd8:906e:7a71:8794:8700', '2026-10-13 14:37:16'),
(899, 1, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/10/2026 02:37:16 PM (IST). Late minutes: 297', '2405:201:6804:bcd8:906e:7a71:8794:8700', '2026-10-13 14:37:16'),
(900, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #8 created.', '2405:201:6804:bcd8:906e:7a71:8794:8700', '2026-10-13 15:08:28'),
(901, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:4964:be4b:ac2c:8be4', '2026-10-13 15:11:51'),
(902, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:28f9:ac20:a277:ce03', '2026-10-13 15:16:16'),
(903, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-13 15:16:20'),
(904, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:391e:8f8f:2a61:d9ac', '2026-10-13 18:19:48'),
(905, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:391e:8f8f:2a61:d9ac', '2026-10-13 18:20:47'),
(906, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:391e:8f8f:2a61:d9ac', '2026-10-13 18:22:01'),
(907, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:391e:8f8f:2a61:d9ac', '2026-10-13 18:22:34'),
(908, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:391e:8f8f:2a61:d9ac', '2026-10-13 18:46:14'),
(909, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:00'),
(910, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 188) as full_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(911, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 189) as full_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(912, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 190) as full_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(913, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 191) as full_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(914, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 192) as full_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(915, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 193) as full_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(916, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 194) as full_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(917, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 195) as full_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(918, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-13, ID: 196) as half_day', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 20:21:01'),
(919, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 21:39:30'),
(920, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6817:f006:218a:77d4:9621:8871', '2026-10-13 21:43:09'),
(921, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:78c5:d682:fbd7:88e6', '2026-10-14 09:33:35'),
(922, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2405:201:6804:bcd8:78c5:d682:fbd7:88e6', '2026-10-14 09:33:39'),
(923, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 09:33:39 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:78c5:d682:fbd7:88e6', '2026-10-14 09:33:39'),
(924, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 09:40:19'),
(925, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305b:b6dd:1412:4eff:fe15:d48b', '2026-10-14 09:40:33'),
(926, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:305b:b6dd:1412:4eff:fe15:d48b', '2026-10-14 09:40:38'),
(927, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 09:40:38 AM (IST). Late minutes: 0', '2409:40d2:305b:b6dd:1412:4eff:fe15:d48b', '2026-10-14 09:40:38'),
(928, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 09:40:54'),
(929, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 09:40:54 AM (IST). Late minutes: 0', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 09:40:54'),
(930, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:41:52'),
(931, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3054:ca07:8000::', '2026-10-14 09:42:27'),
(932, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3054:ca07:8000::', '2026-10-14 09:44:52'),
(933, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 09:44:52 AM (IST). Late minutes: 4', '2409:40d2:3054:ca07:8000::', '2026-10-14 09:44:52'),
(934, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:48:44'),
(935, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:48:52'),
(936, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 09:48:52 AM (IST). Late minutes: 8', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:48:52'),
(937, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3054:ca07:8000::', '2026-10-14 09:49:39'),
(938, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-14 09:50:17'),
(939, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-14 09:50:28'),
(940, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 09:50:28 AM (IST). Late minutes: 10', '2409:4085:ae92:547b::95c9:1d0b', '2026-10-14 09:50:28'),
(941, 10, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:52:46'),
(942, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:53:47'),
(943, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:53:54'),
(944, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 09:53:54 AM (IST). Late minutes: 13', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:53:54'),
(945, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:432e:254b:27b8:593:565c', '2026-10-14 09:53:57'),
(946, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e5ab:2647:a742:a741', '2026-10-14 09:55:46'),
(947, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:254b:27b8:593:565c', '2026-10-14 09:56:10'),
(948, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:432e:254b:27b8:593:565c', '2026-10-14 09:56:52'),
(949, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-14 09:58:17'),
(950, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-14 09:58:24'),
(951, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 09:58:24 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-14 09:58:24'),
(952, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.185', '2026-10-14 10:00:19'),
(953, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 10:00:30'),
(954, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:c45b:7658:3683:8fa2', '2026-10-14 10:04:15'),
(955, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 10:47:42'),
(956, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 10:48:44'),
(957, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 10:49:14'),
(958, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:12d:b096:78df:138a', '2026-10-14 11:28:19'),
(959, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 11:32:09'),
(960, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:12d:b096:78df:138a', '2026-10-14 11:49:23'),
(961, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883c:432e:e99c:16b2:3843:dedc', '2026-10-14 11:54:20'),
(962, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 12:24:06'),
(963, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 12:24:49'),
(964, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 13:09:43'),
(965, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 13:10:23'),
(966, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:13:56'),
(967, 1, NULL, 'Create Department', NULL, NULL, NULL, 'Departments', 'Department #7 (\'Online Travel Agents\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:17:48'),
(968, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #9 (\'MJ Hotels\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:19:27'),
(969, 1, NULL, 'Edit Project', NULL, NULL, NULL, 'Projects', 'Project #9 (\'MJ Hotels\') updated by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:19:56'),
(970, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\') created by Kartikeyan and assigned to User #8', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:21:19'),
(971, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 13:23:34'),
(972, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 13:24:32'),
(973, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:470:a6cf:e46e:e1c9', '2026-10-14 13:25:25'),
(974, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 13:31:26'),
(975, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 13:32:01'),
(976, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #30 (\'Mj Residency Upload New Photoshoots On MMT\') created by Kartikeyan and assigned to User #1', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:32:01'),
(977, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #10 (\'Almost Heaven Mussoorie\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:34:46'),
(978, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #31 (\'List Almost Heaven Whole Property ON MMT and airbnb\') created by Kartikeyan and assigned to User #8', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:35:27'),
(979, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #11 (\'Uttrakhand Stays Airbnb\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:38:23'),
(980, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #32 (\'List Wildflower on Booking.com\') created by Kartikeyan and assigned to User #8', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:40:04'),
(981, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #33 (\'List Sunrise On Booking.com\') created by Kartikeyan and assigned to User #8', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:40:38'),
(982, 1, NULL, 'Edit Project', NULL, NULL, NULL, 'Projects', 'Project #6 (\'Krafting Digital\') updated by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:41:32'),
(983, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #34 (\'Change Logo on Krafting digital Website\') created by Kartikeyan and assigned to User #4', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:42:48'),
(984, 1, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #1 started activity \'Working on Task\'', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:42:55'),
(985, 1, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 14/10/2026 01:42:55 PM (IST). Late minutes: 242', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:42:55'),
(986, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #35 (\'Make Website Live for Wildflower and Sunrise with New booking Engine\') created by Kartikeyan and assigned to User #4', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:44:42'),
(987, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #12 (\'Almost Heaven Rishikesh\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:50:47'),
(988, 1, NULL, 'Edit Project', NULL, NULL, NULL, 'Projects', 'Project #9 (\'MJ Hotels\') updated by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:51:20'),
(989, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #36 (\'Triveni Ghat GMB Verification\') created by Kartikeyan and assigned to User #1', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:53:59'),
(990, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #13 (\'Masterpiece Psycology\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:55:12'),
(991, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #37 (\'Make SEO implementation plan for Masterpiece Psycology\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:57:01'),
(992, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #38 (\'Send New SEO plan after KD approval to Tess via email for approval\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 13:59:24'),
(993, 1, NULL, 'Create Department', NULL, NULL, NULL, 'Departments', 'Department #8 (\'Meta Ads &amp; Google Ads\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:01:19'),
(994, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #14 (\'Taj Mussoorie Foothills\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:01:49'),
(995, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') created by Kartikeyan and assigned to User #1', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:02:32'),
(996, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #40 (\'Make New Taj reels from new photoshoots\') created by Kartikeyan and assigned to User #3', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:04:36'),
(997, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #15 (\'Renee Mill Psycology\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:06:00'),
(998, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #41 (\'Shedule a Meeting with Renee Mill\') created by Kartikeyan and assigned to User #2', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:06:39'),
(999, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #42 (\'Send Whatsapp Solution and Table Booking solution to Amardeep\') created by Kartikeyan and assigned to User #1', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:08:05'),
(1000, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #16 (\'Black Apple Cellular\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:10:15'),
(1001, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #43 (\'Shedule a Meeting with Black apple Cellular with Mrinali and KD\') created by Kartikeyan and assigned to User #2', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:10:44'),
(1002, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #44 (\'Make Detailed SOP for Blog Posting\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:12:25'),
(1003, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #45 (\'Make Final Maharani Website\') created by Kartikeyan and assigned to User #4', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:14:31'),
(1004, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #46 (\'Make Final Menu software for Maharani\') created by Kartikeyan and assigned to User #4', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:15:43'),
(1005, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\') created by Kartikeyan and assigned to User #2', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:18:07'),
(1006, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #48 (\'Make Changes on Almost heaven Final website (In cordination with Gurpreet ma&#039;am)\') created by Kartikeyan and assigned to User #1', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:19:47'),
(1007, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #49 (\'Make SEO plan for Blackapple\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:21:20'),
(1008, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #17 (\'MJ Catering\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:22:19'),
(1009, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #50 (\'Make new SEO plan for Mj catering and get it approved by KD\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:22:29'),
(1010, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #18 (\'Jeevan Sankalp\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:23:02'),
(1011, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #51 (\'Make Seo implementation plan for Jeevan sankalp .(local and website )\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:24:30'),
(1012, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #52 (\'Make new seo plan for Turmeric\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:25:41'),
(1013, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #53 (\'Implement the plan made for masterpiece psycology\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:27:12'),
(1014, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #54 (\'Apply SEO Plan For Jeevan Sankalp\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:28:36'),
(1015, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #55 (\'Make 100 backlinks for jeeven sankalp\') created by Kartikeyan and assigned to User #6', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:30:25'),
(1016, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #19 (\'Liquor Fort\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:31:39'),
(1017, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #56 (\'Fix Liquor Fort GMB and Everything\') created by Kartikeyan and assigned to User #1', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:32:04'),
(1018, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #57 (\'Fix Destination Wedding Website .\') created by Kartikeyan and assigned to User #4', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:38:39'),
(1019, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #20 (\'DR SHIVAM DANG\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:42:16'),
(1020, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #58 (\'Make SEO plan for shivam dang new website ( clinic website ) and make changes on gmb as he has asked us to\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:42:25'),
(1021, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #59 (\'APPLY SEO PLAN FOR OCT MONTH THAT WAS APPROVED BY KD\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:43:21'),
(1022, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') created by Kartikeyan and assigned to User #1', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:44:19'),
(1023, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') created by Kartikeyan and assigned to User #1', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:45:43'),
(1024, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #62 (\'CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE\') created by Kartikeyan and assigned to User #5', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:47:41'),
(1025, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #21 (\'STANDARD BAKERY\') created by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:49:22'),
(1026, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\') created by Kartikeyan and assigned to User #8', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 14:49:27'),
(1027, 3, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #64 (\'Standard Social Media\') created by Udit and assigned to User #8', '2401:4900:883c:432e:e99c:16b2:3843:dedc', '2026-10-14 15:06:30'),
(1028, 3, NULL, 'Edit Task', NULL, NULL, NULL, 'Tasks', 'Task #7 (\'Schedule Weekly LinkedIn &amp; Instagram Posts\') updated by Udit', '2401:4900:883c:432e:e99c:16b2:3843:dedc', '2026-10-14 15:09:30'),
(1029, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-14 15:29:53'),
(1030, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:896c:c48a:95fc:7d3f', '2026-10-14 15:31:09'),
(1031, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #65 (\'Make 100 backlinks for jeeven sankalp\') created by Uvaish and assigned to User #6', '2405:201:6804:bcd8:c45b:7658:3683:8fa2', '2026-10-14 15:41:27'),
(1032, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c46e:3337:26fc:14b', '2026-10-14 15:47:51'),
(1033, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 15:48:37'),
(1034, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:e448:a6a4:1a74:79cf', '2026-10-14 15:49:31'),
(1035, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:f050:6f3b:1f11:e9b2', '2026-10-14 17:07:29'),
(1036, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f050:6f3b:1f11:e9b2', '2026-10-14 17:07:34'),
(1037, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:80ed:e9a5:969e:1d9a', '2026-10-14 17:11:21'),
(1038, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.185', '2026-10-14 17:11:51'),
(1039, 9, NULL, 'Send Message', NULL, NULL, NULL, 'Chat', 'Sent message in direct message', '2405:201:6804:bcd8:f050:6f3b:1f11:e9b2', '2026-10-14 17:14:29'),
(1040, 10, NULL, 'Send Message', NULL, NULL, NULL, 'Chat', 'Sent message in direct message', '2405:201:6804:bcd8:80ed:e9a5:969e:1d9a', '2026-10-14 17:15:04'),
(1041, 10, NULL, 'Send Message', NULL, NULL, NULL, 'Chat', 'Sent message in direct message', '2405:201:6804:bcd8:80ed:e9a5:969e:1d9a', '2026-10-14 17:15:41'),
(1042, 9, NULL, 'Send Message', NULL, NULL, NULL, 'Chat', 'Sent message in direct message', '2405:201:6804:bcd8:f050:6f3b:1f11:e9b2', '2026-10-14 17:16:09'),
(1043, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c46e:3337:26fc:14b', '2026-10-14 17:21:17'),
(1044, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:c0e:47e:cd27:9e88', '2026-10-14 17:46:23'),
(1045, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:c0e:47e:cd27:9e88', '2026-10-14 17:47:08'),
(1046, 1, NULL, 'Create Department', NULL, NULL, NULL, 'Departments', 'Department #9 (\'photography shoots\') created by Kartikeyan.', '2401:4900:883c:432e:c0e:47e:cd27:9e88', '2026-10-14 17:49:15'),
(1047, 1, NULL, 'Add Employee', NULL, NULL, NULL, 'Employee Management', 'Admin added new employee: vicky (EMP-013)', '2401:4900:883c:432e:c0e:47e:cd27:9e88', '2026-10-14 17:53:21'),
(1048, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:c0e:47e:cd27:9e88', '2026-10-14 17:53:27'),
(1049, 13, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c0e:47e:cd27:9e88', '2026-10-14 17:53:41'),
(1050, 13, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:c0e:47e:cd27:9e88', '2026-10-14 17:54:40'),
(1051, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:f050:6f3b:1f11:e9b2', '2026-10-14 17:55:07'),
(1052, 13, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f050:6f3b:1f11:e9b2', '2026-10-14 17:56:20'),
(1053, 13, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:f050:6f3b:1f11:e9b2', '2026-10-14 17:56:45'),
(1054, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f050:6f3b:1f11:e9b2', '2026-10-14 17:56:54'),
(1055, 13, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3057:db00:8000::', '2026-10-14 18:01:03'),
(1056, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 18:22:29'),
(1057, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #66 (\'Photo Shoot Mj Grand Wedding\') created by Kartikeyan and assigned to User #13', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 18:23:56'),
(1058, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #67 (\'Mj Dandiya Fair Photoshoot\') created by Kartikeyan and assigned to User #13', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 18:25:01'),
(1059, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #68 (\'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )\') created by Kartikeyan and assigned to User #8', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 18:28:19'),
(1060, 1, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #1 started activity \'Working on Task\' on Task #30', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 18:29:00'),
(1061, 1, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #30', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 18:29:00'),
(1062, 1, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #30', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 18:29:24'),
(1063, 1, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #30 completed & marked Done by Kartikeyan.', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 18:29:32'),
(1064, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 205) as half_day', '2405:201:6804:bcd8:6938:7b9:5082:c4a2', '2026-10-14 19:00:00'),
(1065, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:10'),
(1066, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 199) as full_day', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:12'),
(1067, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 197) as full_day', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:21'),
(1068, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 198) as full_day', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:21'),
(1069, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 200) as full_day', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:21'),
(1070, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 201) as full_day', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:21'),
(1071, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 202) as full_day', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:21'),
(1072, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 203) as full_day', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:21'),
(1073, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-14, ID: 204) as full_day', '2409:40d2:3040:54e1:ece8:83ff:fe9f:3ca2', '2026-10-14 19:02:21'),
(1074, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:bb61:79c8:b0fe:33d6:e00e:caec', '2026-10-14 19:26:14'),
(1075, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:bb61:79c8:b0fe:33d6:e00e:caec', '2026-10-14 19:27:30'),
(1076, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:ab26:bc0f:fbcf:bd39', '2026-10-15 08:45:04'),
(1077, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:ab26:bc0f:fbcf:bd39', '2026-10-15 08:46:18'),
(1078, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:a579:c6af:9c18:7f10:41b1:b738', '2026-10-15 09:34:38'),
(1079, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:a579:c6af:9c18:7f10:41b1:b738', '2026-10-15 09:34:43'),
(1080, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 09:34:43 AM (IST). Late minutes: 0', '2401:4900:a579:c6af:9c18:7f10:41b1:b738', '2026-10-15 09:34:43'),
(1081, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3005:86d1:3852:cfb0:2681:2fc2', '2026-10-15 09:34:50'),
(1082, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2409:40d2:3005:86d1:3852:cfb0:2681:2fc2', '2026-10-15 09:35:02'),
(1083, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 09:35:02 AM (IST). Late minutes: 0', '2409:40d2:3005:86d1:3852:cfb0:2681:2fc2', '2026-10-15 09:35:02'),
(1084, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:39:18'),
(1085, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:39:22'),
(1086, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 09:39:22 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:39:22'),
(1087, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3054:ca07:8000::', '2026-10-15 09:41:44'),
(1088, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:42:48'),
(1089, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:43:02'),
(1090, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:43:07'),
(1091, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 09:43:07 AM (IST). Late minutes: 3', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:43:07'),
(1092, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3054:ca07:8000::', '2026-10-15 09:44:02'),
(1093, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 09:44:02 AM (IST). Late minutes: 4', '2409:40d2:3054:ca07:8000::', '2026-10-15 09:44:02'),
(1094, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 09:45:06'),
(1095, 10, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:45:56'),
(1096, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:47:16'),
(1097, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:47:21'),
(1098, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 09:47:21 AM (IST). Late minutes: 7', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 09:47:21'),
(1099, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c184:f620:7e18:f103', '2026-10-15 09:49:54'),
(1100, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2401:4900:883c:432e:c184:f620:7e18:f103', '2026-10-15 09:50:00'),
(1101, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 09:50:00 AM (IST). Late minutes: 10', '2401:4900:883c:432e:c184:f620:7e18:f103', '2026-10-15 09:50:00'),
(1102, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3041:4d0a:48a3:acff:fe9e:6bda', '2026-10-15 09:54:16'),
(1103, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:884f:1f58:2912:e130', '2026-10-15 09:59:48'),
(1104, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2401:4900:883c:432e:884f:1f58:2912:e130', '2026-10-15 09:59:54'),
(1105, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 09:59:54 AM (IST). Late minutes: 19', '2401:4900:883c:432e:884f:1f58:2912:e130', '2026-10-15 09:59:54'),
(1106, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c184:f620:7e18:f103', '2026-10-15 10:08:03'),
(1107, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 10:15:58'),
(1108, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 10:16:27'),
(1109, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 10:17:41'),
(1110, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.144', '2026-10-15 10:19:23'),
(1111, 4, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #69 (\'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\') created by Rahul and assigned to User #9', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 10:21:45'),
(1112, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\' on Task #46', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 10:23:11'),
(1113, 4, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #46', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 10:23:11'),
(1114, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2405:201:6804:bcd8:3db6:5aed:cf0e:5e70', '2026-10-15 10:25:01'),
(1115, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2405:201:6804:bcd8:3db6:5aed:cf0e:5e70', '2026-10-15 10:25:07'),
(1116, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 15/10/2026 10:25:06 AM (IST). Late minutes: 45', '2405:201:6804:bcd8:3db6:5aed:cf0e:5e70', '2026-10-15 10:25:07'),
(1117, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #65', '49.43.160.144', '2026-10-15 10:28:56'),
(1118, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #65', '49.43.160.144', '2026-10-15 10:28:56'),
(1119, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #65', '49.43.160.144', '2026-10-15 10:29:45'),
(1120, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #65', '49.43.160.144', '2026-10-15 10:29:45'),
(1121, 4, NULL, 'Emergency Request', NULL, NULL, NULL, 'Tasks', 'Emergency task request #4 submitted by Rahul for task #46 (\'Make Final Menu software for Maharani\')', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 10:33:38'),
(1122, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\' on Task #69', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 10:37:37'),
(1123, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #69', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 10:37:37'),
(1124, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\' on Task #45', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:03:07'),
(1125, 4, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #45', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:03:07'),
(1126, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\' on Task #45', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:03:16'),
(1127, 4, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #45', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:03:16'),
(1128, 4, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #46', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:03:19'),
(1129, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:51b9:9856:4dfa:a1a', '2026-10-15 12:05:16'),
(1130, 4, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Rahul completed task #46 (\'Make Final Menu software for Maharani\') and submitted for manager & product manager approval.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:05:19'),
(1131, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:05:54'),
(1132, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:06:34'),
(1133, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:07:43'),
(1134, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:08:08'),
(1135, 4, NULL, 'Update Profile', NULL, NULL, NULL, 'Auth', 'Profile updated for User #4', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:08:55'),
(1136, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:09:09'),
(1137, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:10:14'),
(1138, 1, NULL, 'Update Profile', NULL, NULL, NULL, 'Auth', 'Profile updated for User #1', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:10:57'),
(1139, 1, NULL, 'Generate Payroll', NULL, NULL, NULL, 'Payroll', 'Admin generated monthly payroll for 2026-09 for 12 employees', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:14:00'),
(1140, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #70 (\'Kashmiri sons 5 Blogs Uttarakhand wedding\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:14:33'),
(1141, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:15:57'),
(1142, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:16:35'),
(1143, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #71 (\'kashmiri sons blogs on Uttarakhand Stays\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:18:06'),
(1144, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 12:18:30');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(1145, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #72 (\'Kashmiri Sons blogs on Dehradungram\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:20:41'),
(1146, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #73 (\'Jeevan sankalp blogs on Dehradun gurugram\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:27:13'),
(1147, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:28:49'),
(1148, 5, NULL, 'Edit Task', NULL, NULL, NULL, 'Tasks', 'Task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') updated by Uvaish', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:29:48'),
(1149, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #75 (\'Frontier Jewellers blogs on Uttarakhand wedding\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:32:39'),
(1150, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #76 (\'Frontier Jewellers blogs on Dehradun Gurugram\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:33:51'),
(1151, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #77 (\'Frontier Jewellers blogs on Uttarakhan Diaries\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:35:18'),
(1152, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #78 (\'Uttarakhand Wedding blogs for Aristella\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:37:18'),
(1153, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #79 (\'Uttarakhand Diaries blogs for Aristella\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:40:05'),
(1154, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:41:15'),
(1155, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #80 (\'Dehradun Gurugram blogs for Aristella\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:a961:b597:fc56:beb7', '2026-10-15 12:41:23'),
(1156, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:41:47'),
(1157, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:43:59'),
(1158, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:44:47'),
(1159, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:49:10'),
(1160, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 12:50:28'),
(1161, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 13:11:39'),
(1162, 2, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Anjali completed task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\') and submitted for manager & product manager approval.', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 13:15:05'),
(1163, 2, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Anjali approved completed task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\'). Status changed to Done.', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 13:15:20'),
(1164, 1, NULL, 'Add Employee', NULL, NULL, NULL, 'Employee Management', 'Admin added new employee: Prabhat (EMP-014)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 13:19:02'),
(1165, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 13:19:10'),
(1166, 14, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 13:19:31'),
(1167, 14, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 13:21:17'),
(1168, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 13:21:39'),
(1169, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 13:29:51'),
(1170, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 13:30:17'),
(1171, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #64', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 13:34:56'),
(1172, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #64', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 13:34:56'),
(1173, 8, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #64 & submitted for manager approval.', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 13:36:00'),
(1174, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 13:40:27'),
(1175, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 13:40:27'),
(1176, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:b89e:bcf1:452:78dd', '2026-10-15 14:28:50'),
(1177, 8, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:32:35'),
(1178, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:32:43'),
(1179, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:32:43'),
(1180, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:33:04'),
(1181, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:33:04'),
(1182, 8, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:33:06'),
(1183, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:33:27'),
(1184, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:33:27'),
(1185, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:34:25'),
(1186, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:34:25'),
(1187, 8, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:36:50'),
(1188, 8, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 14:36:52'),
(1189, 1, NULL, 'Approve Emergency Request', NULL, NULL, NULL, 'Tasks', 'Emergency request #4 for task #46 approved by Kartikeyan', '2405:201:6804:bcd8:b89e:bcf1:452:78dd', '2026-10-15 14:38:16'),
(1190, 4, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #45 completed & marked Done by Rahul.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 15:09:07'),
(1191, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\' on Task #34', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 15:09:54'),
(1192, 4, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #34', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 15:09:54'),
(1193, 9, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #69 & submitted for manager approval.', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 15:11:33'),
(1194, 4, NULL, 'Task Flagged', NULL, NULL, NULL, 'Tasks', 'Task #45 flagged by Rahul under \'Other\' (Flag #4)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 15:12:00'),
(1195, 4, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Rahul approved completed task #69 (\'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\'). Status changed to Done.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 15:13:09'),
(1196, 4, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Rahul completed task #57 (\'Fix Destination Wedding Website .\') and submitted for manager & product manager approval.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 15:14:20'),
(1197, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 15:15:05'),
(1198, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 15:16:08'),
(1199, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 15:39:22'),
(1200, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #68', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 15:39:22'),
(1201, 8, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #68 & submitted for manager approval.', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 15:39:37'),
(1202, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:15:02'),
(1203, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:30:15'),
(1204, 4, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #81 (\'Shein Mill Website Development\') created by Rahul and assigned to User #9', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:34:23'),
(1205, 4, NULL, 'Edit Task', NULL, NULL, NULL, 'Tasks', 'Task #81 (\'Shein Mill Website Development\') updated by Rahul', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:34:51'),
(1206, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\' on Task #81', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 16:35:05'),
(1207, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #81', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 16:35:05'),
(1208, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 16:40:14'),
(1209, 14, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 16:53:11'),
(1210, 14, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 16:54:13'),
(1211, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:55:27'),
(1212, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 16:55:28'),
(1213, 2, NULL, 'Edit Task', NULL, NULL, NULL, 'Tasks', 'Task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') updated by Anjali', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 16:56:30'),
(1214, 2, NULL, 'Edit Task', NULL, NULL, NULL, 'Tasks', 'Task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') updated by Anjali', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 16:57:10'),
(1215, 2, NULL, 'Edit Task', NULL, NULL, NULL, 'Tasks', 'Task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') updated by Anjali', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 16:57:22'),
(1216, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:57:23'),
(1217, 2, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 16:57:32'),
(1218, 1, NULL, 'Update Employee', NULL, NULL, NULL, 'Employee Management', 'Admin updated employee profile for ID #14 (PRABHAT)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:57:40'),
(1219, 14, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:79a2:42b9:8508:c745', '2026-10-15 16:58:06'),
(1220, 1, NULL, 'Update Employee', NULL, NULL, NULL, 'Employee Management', 'Admin updated employee profile for ID #14 (PRABHAT)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:59:09'),
(1221, 1, NULL, 'Delete Department', NULL, NULL, NULL, 'Departments', 'Department #10 (\'Meta Ads & Google Ads\') deleted by Kartikeyan. Associated staff/tasks moved to Administration.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:59:25'),
(1222, 1, NULL, 'Update Employee', NULL, NULL, NULL, 'Employee Management', 'Admin updated employee profile for ID #14 (PRABHAT)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:59:37'),
(1223, 1, NULL, 'Update Employee', NULL, NULL, NULL, 'Employee Management', 'Admin updated employee profile for ID #14 (PRABHAT)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 16:59:48'),
(1224, 1, NULL, 'Edit Department', NULL, NULL, NULL, 'Departments', 'Department #8 (\'Meta Ads &amp;amp; Google Ads\') updated by Kartikeyan.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:00:10'),
(1225, 1, NULL, 'Edit Task', NULL, NULL, NULL, 'Tasks', 'Task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') updated by Kartikeyan', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:00:42'),
(1226, 1, NULL, 'Delete Department', NULL, NULL, NULL, 'Departments', 'Department #11 (\'Meta Ads & Google Ads\') deleted by Kartikeyan. Associated staff/tasks moved to Administration.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:01:24'),
(1227, 1, NULL, 'Update Employee', NULL, NULL, NULL, 'Employee Management', 'Admin updated employee profile for ID #14 (PRABHAT)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:01:54'),
(1228, 14, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:681b:c086:b52a:d0be:74b2:b8f0', '2026-10-15 17:06:03'),
(1229, 1, NULL, 'Update Employee', NULL, NULL, NULL, 'Employee Management', 'Admin updated employee profile for ID #13 (vicky)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:09:35'),
(1230, 1, NULL, 'Delete Department', NULL, NULL, NULL, 'Departments', 'Department #13 (\'Meta Ads & Google Ads\') deleted by Kartikeyan. Associated staff/tasks moved to Administration.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:09:56'),
(1231, 1, NULL, 'Edit Department', NULL, NULL, NULL, 'Departments', 'Department #8 (\'Meta Ads &amp;amp;amp; Google Ads\') updated by Kartikeyan.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:10:03'),
(1232, 1, NULL, 'Update Employee', NULL, NULL, NULL, 'Employee Management', 'Admin updated employee profile for ID #13 (vicky)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:10:29'),
(1233, 1, NULL, 'Update Employee', NULL, NULL, NULL, 'Employee Management', 'Admin updated employee profile for ID #14 (PRABHAT)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:10:34'),
(1234, 14, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee PRABHAT completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') and submitted for manager & product manager approval.', '2405:201:681b:c086:b52a:d0be:74b2:b8f0', '2026-10-15 17:39:07'),
(1235, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:40:00'),
(1236, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:40:37'),
(1237, 14, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee PRABHAT completed task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') and submitted for manager & product manager approval.', '2405:201:681b:c086:b52a:d0be:74b2:b8f0', '2026-10-15 17:41:18'),
(1238, 4, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #34 completed & marked Done by Rahul.', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:41:21'),
(1239, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\' on Task #28', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:41:47'),
(1240, 4, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #28', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:41:47'),
(1241, 14, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee PRABHAT completed task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') and submitted for manager & product manager approval.', '2405:201:681b:c086:b52a:d0be:74b2:b8f0', '2026-10-15 17:42:25'),
(1242, 4, NULL, 'Task Flagged', NULL, NULL, NULL, 'Tasks', 'Task #35 flagged by Rahul under \'Waiting for Approval\' (Flag #5)', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 17:48:26'),
(1243, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.144', '2026-10-15 18:02:40'),
(1244, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:07:01'),
(1245, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:07:04'),
(1246, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:07:08'),
(1247, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:07:14'),
(1248, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:08:03'),
(1249, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:08:05'),
(1250, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:08:05'),
(1251, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:08:06'),
(1252, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:08:06'),
(1253, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:08:37'),
(1254, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3054:ca07:8000::', '2026-10-15 18:08:39'),
(1255, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #65', '49.43.160.144', '2026-10-15 18:09:46'),
(1256, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #65', '49.43.160.144', '2026-10-15 18:09:46'),
(1257, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.144', '2026-10-15 18:12:07'),
(1258, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:45a0:d2e5:7832:fdba', '2026-10-15 18:13:48'),
(1259, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:1e32:3252:d280:79b6', '2026-10-15 18:23:58'),
(1260, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 18:25:12'),
(1261, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #68 (\'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )\'). Status changed to Done.', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 18:26:48'),
(1262, 4, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #28', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 18:27:05'),
(1263, 1, NULL, 'Task Revision Requested', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan returned task #57 for revision: not completed', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 18:28:22'),
(1264, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 18:30:31'),
(1265, 9, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #81', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 18:30:45'),
(1266, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 15/10/2026 06:31:01 PM (IST). Status: half_day, Duration: 08h 43m 40s, Early Exit: 0m', '49.43.160.144', '2026-10-15 18:31:01'),
(1267, 5, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 15/10/2026 06:31:11 PM (IST). Status: half_day, Duration: 08h 49m 17s, Early Exit: 0m', '2405:201:6804:bcd8:1e32:3252:d280:79b6', '2026-10-15 18:31:11'),
(1268, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:45a0:d2e5:7832:fdba', '2026-10-15 18:31:40'),
(1269, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3054:ca07:8000::', '2026-10-15 18:31:42'),
(1270, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 15/10/2026 06:32:30 PM (IST). Status: full_day, Duration: 08h 57m 28s, Early Exit: 0m', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-15 18:32:31'),
(1271, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 15/10/2026 06:32:33 PM (IST). Status: full_day, Duration: 08h 53m 11s, Early Exit: 0m', '2405:201:6804:bcd8:8dcd:6f25:c5d:92e', '2026-10-15 18:32:33'),
(1272, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 15/10/2026 06:33:21 PM (IST). Status: full_day, Duration: 08h 58m 38s, Early Exit: 0m', '2401:4900:883c:432e:3475:1032:7173:fa05', '2026-10-15 18:33:21'),
(1273, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-15, ID: 209) as full_day', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:07:04'),
(1274, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-15, ID: 210) as full_day', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:07:04'),
(1275, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-15, ID: 212) as full_day', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:07:04'),
(1276, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-15, ID: 214) as half_day', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:07:04'),
(1277, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\'). Status changed to Done.', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:10:41'),
(1278, 1, NULL, 'Task Revision Requested', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan returned task #60 for revision: we already have a budget of 7000 INR from Shivam bhaiya . Please check the ads spend it it has not touched 7000 (inclusive of tax ) then you can start running the ads . In case the 7000 rs have been fully used ask anjali or mrinali to take it from shivam bhaiya and you can just start running ads . update once the ads are live .', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:12:43'),
(1279, 1, NULL, 'Task Revision Requested', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan returned task #39 for revision: have added 5000 INR in the Kotak ads account . keep the daily budget only 300 rs for the 1st few days and start the ads . i will be adding more funds to this by tomorrow or saturday .', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:13:51'),
(1280, 1, NULL, 'Task Revision Requested', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan returned task #64 for revision: I want a posting report with timelines on my table tomorrow . after that i will approve this .', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:15:06'),
(1281, 1, NULL, 'Task Revision Requested', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan returned task #46 for revision: Do the mentioned changes and then resend for approval . in case of any confusions call me directly .', '2405:201:6804:bcd8:5135:7aab:f37e:e2f2', '2026-10-15 19:15:52'),
(1282, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '103.84.238.248', '2026-10-15 20:31:16'),
(1283, 4, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #57', '103.84.238.248', '2026-10-15 20:32:39'),
(1284, 4, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #57', '103.84.238.248', '2026-10-15 20:32:47'),
(1285, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '103.84.238.248', '2026-10-15 20:33:34'),
(1286, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '103.84.238.248', '2026-10-15 20:34:41'),
(1287, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:20c6:3681:210b:8f57', '2026-10-16 09:36:31'),
(1288, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2405:201:6804:bcd8:20c6:3681:210b:8f57', '2026-10-16 09:36:35'),
(1289, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/10/2026 09:36:35 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:20c6:3681:210b:8f57', '2026-10-16 09:36:35'),
(1290, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:250:460c:9b7a:498c', '2026-10-16 09:38:57'),
(1291, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:c609:8000::', '2026-10-16 09:39:35'),
(1292, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883c:432e:250:460c:9b7a:498c', '2026-10-16 09:40:09'),
(1293, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/10/2026 09:40:09 AM (IST). Late minutes: 0', '2401:4900:883c:432e:250:460c:9b7a:498c', '2026-10-16 09:40:09'),
(1294, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3053:c609:8000::', '2026-10-16 09:42:02'),
(1295, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/10/2026 09:42:01 AM (IST). Late minutes: 2', '2409:40d2:3053:c609:8000::', '2026-10-16 09:42:02'),
(1296, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6803:d181:8993:addc:95fb:e072', '2026-10-16 09:43:03'),
(1297, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6803:d181:8993:addc:95fb:e072', '2026-10-16 09:43:09'),
(1298, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/10/2026 09:43:09 AM (IST). Late minutes: 3', '2405:201:6803:d181:8993:addc:95fb:e072', '2026-10-16 09:43:09'),
(1299, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 09:45:07'),
(1300, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 09:45:13'),
(1301, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/10/2026 09:45:13 AM (IST). Late minutes: 5', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 09:45:13'),
(1302, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 09:45:33'),
(1303, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae96:8999::9589:7a14', '2026-10-16 09:48:38'),
(1304, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.58', '2026-10-16 09:48:49'),
(1305, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae96:8999::9589:7a14', '2026-10-16 09:48:53'),
(1306, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/10/2026 09:48:53 AM (IST). Late minutes: 8', '2409:4085:ae96:8999::9589:7a14', '2026-10-16 09:48:53'),
(1307, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '49.43.160.58', '2026-10-16 09:48:55'),
(1308, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/10/2026 09:48:55 AM (IST). Late minutes: 8', '49.43.160.58', '2026-10-16 09:48:55'),
(1309, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:45a0:d2e5:7832:fdba', '2026-10-16 09:52:31'),
(1310, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 09:55:53'),
(1311, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:c609:8000::', '2026-10-16 09:58:22'),
(1312, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\' on Task #81', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 09:59:20'),
(1313, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #81', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 09:59:20'),
(1314, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #65', '49.43.160.58', '2026-10-16 10:03:06'),
(1315, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #65', '49.43.160.58', '2026-10-16 10:03:06'),
(1316, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #65', '49.43.160.58', '2026-10-16 10:06:05'),
(1317, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #65', '49.43.160.58', '2026-10-16 10:06:05'),
(1318, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:fc58:d2df:3892:5a2f', '2026-10-16 10:09:09'),
(1319, 13, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:301f:d010:8000::', '2026-10-16 10:10:18'),
(1320, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:555f:5c7:794b:4324', '2026-10-16 10:26:38'),
(1321, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:45a0:d2e5:7832:fdba', '2026-10-16 10:47:53'),
(1322, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c5e9:ffc9:485d:3432', '2026-10-16 10:48:13'),
(1323, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c5e9:ffc9:485d:3432', '2026-10-16 10:49:29'),
(1324, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:c5e9:ffc9:485d:3432', '2026-10-16 11:26:47'),
(1325, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bc69:cc9a:dbf9:def3', '2026-10-16 11:31:21'),
(1326, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2405:201:6817:f006:44a2:989a:b749:da16', '2026-10-16 11:36:48'),
(1327, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:b681:3f73:a7a1:5db5', '2026-10-16 11:41:59'),
(1328, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 11:42:29'),
(1329, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 11:42:52'),
(1330, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 11:42:56'),
(1331, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 16/10/2026 11:42:56 AM (IST). Late minutes: 122', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 11:42:56'),
(1332, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f006:5ca8:dccf:bb49:afaf', '2026-10-16 11:44:57'),
(1333, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:2031:7b1b:d056:8844', '2026-10-16 12:27:26'),
(1334, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 12:31:52'),
(1335, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 12:33:07'),
(1336, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 12:33:42'),
(1337, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.58', '2026-10-16 12:44:58'),
(1338, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.58', '2026-10-16 12:45:19'),
(1339, 6, NULL, 'Emergency Request', NULL, NULL, NULL, 'Tasks', 'Emergency task request #5 submitted by Shivanshu for task #65 (\'Make 100 backlinks for jeeven sankalp\')', '49.43.160.58', '2026-10-16 12:46:37'),
(1340, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 12:52:15'),
(1341, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:555f:5c7:794b:4324', '2026-10-16 13:41:35'),
(1342, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.145.135', '2026-10-16 13:53:07'),
(1343, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 15:05:03'),
(1344, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883c:432e:8c3f:9786:c640:b479', '2026-10-16 15:06:29'),
(1345, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:2031:7b1b:d056:8844', '2026-10-16 16:43:37'),
(1346, 1, NULL, 'Approve Emergency Request', NULL, NULL, NULL, 'Tasks', 'Emergency request #5 for task #65 approved by Kartikeyan', '2405:201:6804:bcd8:2031:7b1b:d056:8844', '2026-10-16 16:44:34'),
(1347, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.58', '2026-10-16 16:50:29'),
(1348, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #65', '49.43.160.58', '2026-10-16 16:50:38'),
(1349, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #65', '49.43.160.58', '2026-10-16 16:50:38'),
(1350, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:fd35:a7cc:6c9f:efad', '2026-10-16 17:34:24'),
(1351, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:e464:bc56:203c:799d', '2026-10-16 17:36:20'),
(1352, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:fd35:a7cc:6c9f:efad', '2026-10-16 17:37:48'),
(1353, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:6564:bce7:924e:586', '2026-10-16 17:39:05'),
(1354, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:f5e6:3c18:f1e4:5067', '2026-10-16 17:48:01'),
(1355, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.145.135', '2026-10-16 17:50:15'),
(1356, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:fc58:d2df:3892:5a2f', '2026-10-16 18:03:32'),
(1357, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:465:dcda:7491:9227', '2026-10-16 18:22:57'),
(1358, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 18:24:48'),
(1359, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:c609:8000::', '2026-10-16 18:25:48'),
(1360, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:fd35:a7cc:6c9f:efad', '2026-10-16 18:27:10'),
(1361, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:a1b5:bd94:d570:772d', '2026-10-16 18:27:15'),
(1362, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:38ef:c83a:4707:d9dd', '2026-10-16 18:28:11'),
(1363, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.58', '2026-10-16 18:30:05'),
(1364, 7, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 16/10/2026 06:30:42 PM (IST). Status: half_day, Duration: 08h 48m 41s, Early Exit: 0m', '2401:4900:883c:432e:a1b5:bd94:d570:772d', '2026-10-16 18:30:42'),
(1365, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 16/10/2026 06:30:52 PM (IST). Status: full_day, Duration: 08h 54m 17s, Early Exit: 0m', '2401:4900:883c:432e:465:dcda:7491:9227', '2026-10-16 18:30:52'),
(1366, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883c:432e:294f:e2a3:c91b:6b26', '2026-10-16 18:30:53'),
(1367, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 16/10/2026 06:31:04 PM (IST). Status: half_day, Duration: 08h 42m 09s, Early Exit: 0m', '49.43.160.58', '2026-10-16 18:31:05'),
(1368, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 16/10/2026 06:31:22 PM (IST). Status: half_day, Duration: 08h 48m 13s, Early Exit: 0m', '2405:201:6804:bcd8:fc58:d2df:3892:5a2f', '2026-10-16 18:31:22'),
(1369, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 16/10/2026 06:31:29 PM (IST). Status: full_day, Duration: 08h 51m 20s, Early Exit: 0m', '2405:201:6804:bcd8:8def:95d:d67e:352f', '2026-10-16 18:31:29'),
(1370, 10, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 16/10/2026 06:33:38 PM (IST). Status: half_day, Duration: 08h 48m 25s, Early Exit: 0m', '2405:201:6804:bcd8:38ef:c83a:4707:d9dd', '2026-10-16 18:33:38'),
(1371, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 16/10/2026 06:36:03 PM (IST). Status: half_day, Duration: 08h 47m 10s, Early Exit: 0m', '2401:4900:883c:432e:294f:e2a3:c91b:6b26', '2026-10-16 18:36:03'),
(1372, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4090:b080:ec07:c340:886c:4cc0:dd4a', '2026-10-16 23:53:45'),
(1373, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304f:5cf:8000::', '2026-10-17 09:29:52'),
(1374, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:31be:3887:e00c:3a8f', '2026-10-17 09:36:06'),
(1375, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:91ef:3aa0:e417:cee2', '2026-10-17 09:36:26'),
(1376, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2401:4900:883d:436:91ef:3aa0:e417:cee2', '2026-10-17 09:36:33'),
(1377, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 09:36:33 AM (IST). Late minutes: 0', '2401:4900:883d:436:91ef:3aa0:e417:cee2', '2026-10-17 09:36:33'),
(1378, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2405:201:6804:bcd8:31be:3887:e00c:3a8f', '2026-10-17 09:36:37'),
(1379, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 09:36:37 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:31be:3887:e00c:3a8f', '2026-10-17 09:36:37'),
(1380, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-16, ID: 222) as half_day', '2401:4900:883d:436:91ef:3aa0:e417:cee2', '2026-10-17 09:36:44'),
(1381, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:51c9:c13d:49e8:6686', '2026-10-17 09:42:15'),
(1382, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:51c9:c13d:49e8:6686', '2026-10-17 09:42:21'),
(1383, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 09:42:21 AM (IST). Late minutes: 2', '2405:201:6804:bcd8:51c9:c13d:49e8:6686', '2026-10-17 09:42:21'),
(1384, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2c25:b87e::8816:54d3', '2026-10-17 09:42:27'),
(1385, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2c25:b87e::8816:54d3', '2026-10-17 09:42:33'),
(1386, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 09:42:33 AM (IST). Late minutes: 2', '2402:8100:2c25:b87e::8816:54d3', '2026-10-17 09:42:33'),
(1387, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:304f:5cf:8000::', '2026-10-17 09:44:10'),
(1388, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 09:44:10 AM (IST). Late minutes: 4', '2409:40d2:304f:5cf:8000::', '2026-10-17 09:44:10'),
(1389, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:fc58:d2df:3892:5a2f', '2026-10-17 09:46:38'),
(1390, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6804:bcd8:fc58:d2df:3892:5a2f', '2026-10-17 09:46:45'),
(1391, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 09:46:45 AM (IST). Late minutes: 6', '2405:201:6804:bcd8:fc58:d2df:3892:5a2f', '2026-10-17 09:46:45'),
(1392, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:e595:9f5d:bc8e:a697', '2026-10-17 09:47:18'),
(1393, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304f:5cf:8000::', '2026-10-17 09:50:08'),
(1394, 9, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #81', '2405:201:6804:bcd8:51c9:c13d:49e8:6686', '2026-10-17 09:54:26'),
(1395, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 10:03:11'),
(1396, 4, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Rahul completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\') and submitted for manager & product manager approval.', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 10:03:50'),
(1397, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.58', '2026-10-17 10:05:11'),
(1398, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '49.43.160.58', '2026-10-17 10:05:19'),
(1399, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 10:05:18 AM (IST). Late minutes: 25', '49.43.160.58', '2026-10-17 10:05:19'),
(1400, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #55', '49.43.160.58', '2026-10-17 10:06:59'),
(1401, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #55', '49.43.160.58', '2026-10-17 10:06:59'),
(1402, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8994:b438:efa8:a7a9', '2026-10-17 10:27:20'),
(1403, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6804:bcd8:8994:b438:efa8:a7a9', '2026-10-17 10:27:25'),
(1404, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 10:27:25 AM (IST). Late minutes: 47', '2405:201:6804:bcd8:8994:b438:efa8:a7a9', '2026-10-17 10:27:25'),
(1405, 4, NULL, 'Activity Reminder Confirmed', NULL, NULL, NULL, 'Workflow', 'Employee Rahul confirmed still working on current activity.', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 11:49:40'),
(1406, 4, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #57 completed & marked Done by Rahul.', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 11:50:07'),
(1407, 4, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #46 completed & marked Done by Rahul.', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 11:50:17'),
(1408, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f0d3:58c5:489f:7230:b272', '2026-10-17 12:55:57'),
(1409, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\'). Status changed to Done.', '2405:201:6817:f0d3:58c5:489f:7230:b272', '2026-10-17 12:56:15'),
(1410, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 15:06:42'),
(1411, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 15:07:18'),
(1412, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 15:07:26'),
(1413, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 17/10/2026 03:07:25 PM (IST). Late minutes: 327', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 15:07:26'),
(1414, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:fcb1:6a9a:eefc:46f', '2026-10-17 15:13:32'),
(1415, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #37', '2405:201:6804:bcd8:8994:b438:efa8:a7a9', '2026-10-17 15:32:46'),
(1416, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #37', '2405:201:6804:bcd8:8994:b438:efa8:a7a9', '2026-10-17 15:32:46'),
(1417, 5, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #37', '2405:201:6804:bcd8:8994:b438:efa8:a7a9', '2026-10-17 15:32:59'),
(1418, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:646a:59f9:6b8e:5634', '2026-10-17 16:11:53'),
(1419, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:e1ae:9ce2:3db9:8be6', '2026-10-17 16:39:17'),
(1420, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:2948:b8ca:16ca:87a2', '2026-10-17 18:25:37'),
(1421, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8994:b438:efa8:a7a9', '2026-10-17 18:28:12'),
(1422, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.58', '2026-10-17 18:30:20'),
(1423, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #55', '49.43.160.58', '2026-10-17 18:30:43'),
(1424, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #55', '49.43.160.58', '2026-10-17 18:30:46'),
(1425, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #55', '49.43.160.58', '2026-10-17 18:30:48');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(1426, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:58ab:58b1:20ec:7026', '2026-10-17 18:30:54'),
(1427, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 17/10/2026 06:31:04 PM (IST). Status: full_day, Duration: 08h 54m 27s, Early Exit: 0m', '2401:4900:883d:436:2948:b8ca:16ca:87a2', '2026-10-17 18:31:04'),
(1428, 7, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 17/10/2026 06:31:07 PM (IST). Status: half_day, Duration: 08h 46m 57s, Early Exit: 0m', '2401:4900:883d:436:58ab:58b1:20ec:7026', '2026-10-17 18:31:07'),
(1429, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 17/10/2026 06:31:13 PM (IST). Status: half_day, Duration: 08h 25m 55s, Early Exit: 0m', '49.43.160.58', '2026-10-17 18:31:14'),
(1430, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:a82d:6e19:5baa:7864', '2026-10-17 18:31:32'),
(1431, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 17/10/2026 06:31:45 PM (IST). Status: half_day, Duration: 08h 45m 00s, Early Exit: 0m', '2405:201:6804:bcd8:a82d:6e19:5baa:7864', '2026-10-17 18:31:46'),
(1432, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.48', '2026-10-17 18:34:58'),
(1433, 10, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 17/10/2026 06:35:06 PM (IST). Status: full_day, Duration: 08h 52m 33s, Early Exit: 0m', '106.219.146.48', '2026-10-17 18:35:06'),
(1434, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f0d3:c513:7f0c:5c82:86ba', '2026-10-18 15:08:18'),
(1435, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-17, ID: 223) as full_day', '2405:201:6817:f0d3:c513:7f0c:5c82:86ba', '2026-10-18 15:08:20'),
(1436, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-17, ID: 225) as full_day', '2405:201:6817:f0d3:c513:7f0c:5c82:86ba', '2026-10-18 15:08:20'),
(1437, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-17, ID: 230) as half_day', '2405:201:6817:f0d3:c513:7f0c:5c82:86ba', '2026-10-18 15:08:20'),
(1438, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-17, ID: 231) as half_day', '2405:201:6817:f0d3:c513:7f0c:5c82:86ba', '2026-10-18 15:08:20'),
(1439, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:d415:8000::', '2026-10-19 09:27:45'),
(1440, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:10aa:eafd:a0c:5ad0', '2026-10-19 09:35:34'),
(1441, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883d:436:10aa:eafd:a0c:5ad0', '2026-10-19 09:35:39'),
(1442, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 09:35:39 AM (IST). Late minutes: 0', '2401:4900:883d:436:10aa:eafd:a0c:5ad0', '2026-10-19 09:35:39'),
(1443, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2c39:a6a8::8904:9428', '2026-10-19 09:36:39'),
(1444, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8891:ac9b:7224:45ae', '2026-10-19 09:38:52'),
(1445, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2405:201:6804:bcd8:8891:ac9b:7224:45ae', '2026-10-19 09:39:14'),
(1446, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 09:39:14 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:8891:ac9b:7224:45ae', '2026-10-19 09:39:14'),
(1447, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2c39:a6a8::8904:9428', '2026-10-19 09:39:41'),
(1448, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 09:39:41 AM (IST). Late minutes: 0', '2402:8100:2c39:a6a8::8904:9428', '2026-10-19 09:39:41'),
(1449, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3011:d415:8000::', '2026-10-19 09:42:49'),
(1450, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 09:42:49 AM (IST). Late minutes: 2', '2409:40d2:3011:d415:8000::', '2026-10-19 09:42:49'),
(1451, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:d415:8000::', '2026-10-19 09:49:12'),
(1452, 14, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bdac:c073:47e5:39c1', '2026-10-19 09:51:59'),
(1453, 14, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #14 started activity \'Working on Task\'', '2405:201:6804:bcd8:bdac:c073:47e5:39c1', '2026-10-19 09:52:06'),
(1454, 14, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 09:52:06 AM (IST). Late minutes: 12', '2405:201:6804:bcd8:bdac:c073:47e5:39c1', '2026-10-19 09:52:06'),
(1455, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 09:53:04'),
(1456, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 09:57:09'),
(1457, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 09:58:52'),
(1458, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.78', '2026-10-19 10:01:35'),
(1459, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '49.43.160.78', '2026-10-19 10:01:40'),
(1460, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 10:01:40 AM (IST). Late minutes: 21', '49.43.160.78', '2026-10-19 10:01:40'),
(1461, 9, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Abhay completed task #81 (\'Shein Mill Website Development\') and submitted for manager & product manager approval.', '2405:201:6804:bcd8:846b:4c6a:9b7b:5229', '2026-10-19 10:03:38'),
(1462, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:ec27:ee24:dd12:2a73', '2026-10-19 10:04:34'),
(1463, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2405:201:6804:bcd8:ec27:ee24:dd12:2a73', '2026-10-19 10:04:50'),
(1464, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 10:04:50 AM (IST). Late minutes: 24', '2405:201:6804:bcd8:ec27:ee24:dd12:2a73', '2026-10-19 10:04:50'),
(1465, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:1dd1:6cc5:34e5:ceee', '2026-10-19 10:16:12'),
(1466, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6804:bcd8:1dd1:6cc5:34e5:ceee', '2026-10-19 10:16:21'),
(1467, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 10:16:21 AM (IST). Late minutes: 36', '2405:201:6804:bcd8:1dd1:6cc5:34e5:ceee', '2026-10-19 10:16:21'),
(1468, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #65', '49.43.160.78', '2026-10-19 10:19:03'),
(1469, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #65', '49.43.160.78', '2026-10-19 10:19:03'),
(1470, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bdac:c073:47e5:39c1', '2026-10-19 10:36:06'),
(1471, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6804:bcd8:bdac:c073:47e5:39c1', '2026-10-19 10:36:20'),
(1472, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 10:36:20 AM (IST). Late minutes: 56', '2405:201:6804:bcd8:bdac:c073:47e5:39c1', '2026-10-19 10:36:20'),
(1473, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7911:1c47:cfa4:62f4', '2026-10-19 10:58:25'),
(1474, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7911:1c47:cfa4:62f4', '2026-10-19 10:58:33'),
(1475, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7911:1c47:cfa4:62f4', '2026-10-19 10:59:00'),
(1476, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #70', '49.43.160.78', '2026-10-19 11:18:41'),
(1477, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #70', '49.43.160.78', '2026-10-19 11:18:41'),
(1478, 10, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #70 & submitted for manager approval.', '49.43.160.78', '2026-10-19 11:19:44'),
(1479, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:846b:4c6a:9b7b:5229', '2026-10-19 11:23:13'),
(1480, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:2074:c987:b165:f917', '2026-10-19 11:39:58'),
(1481, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2401:4900:883d:436:2074:c987:b165:f917', '2026-10-19 11:40:06'),
(1482, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 11:40:06 AM (IST). Late minutes: 120', '2401:4900:883d:436:2074:c987:b165:f917', '2026-10-19 11:40:06'),
(1483, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 12:08:39'),
(1484, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 12:09:10'),
(1485, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 12:09:14'),
(1486, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 19/10/2026 12:09:14 PM (IST). Late minutes: 149', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 12:09:14'),
(1487, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 12:20:06'),
(1488, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:2074:c987:b165:f917', '2026-10-19 12:29:15'),
(1489, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #58', '2405:201:6804:bcd8:1dd1:6cc5:34e5:ceee', '2026-10-19 13:01:43'),
(1490, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #58', '2405:201:6804:bcd8:1dd1:6cc5:34e5:ceee', '2026-10-19 13:01:43'),
(1491, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #58 completed & marked Done by Uvaish.', '2405:201:6804:bcd8:1dd1:6cc5:34e5:ceee', '2026-10-19 13:03:05'),
(1492, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:684e:7ef:4dcb:1b4c', '2026-10-19 15:22:51'),
(1493, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:1dd1:6cc5:34e5:ceee', '2026-10-19 17:04:50'),
(1494, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 17:20:35'),
(1495, 1, NULL, 'Update Profile', NULL, NULL, NULL, 'Auth', 'Profile updated for User #4', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 17:35:26'),
(1496, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:8cad:2d10:a18:ecf0', '2026-10-19 18:14:44'),
(1497, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:1dd1:6cc5:34e5:ceee', '2026-10-19 18:25:29'),
(1498, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:f569:e1f7:b46c:91fd', '2026-10-19 18:29:10'),
(1499, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8bc4:8de4:2ccf:43e2', '2026-10-19 18:30:05'),
(1500, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 19/10/2026 06:31:36 PM (IST). Status: half_day, Duration: 06h 51m 30s, Early Exit: 0m', '2401:4900:883d:436:f569:e1f7:b46c:91fd', '2026-10-19 18:31:36'),
(1501, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:2e9f:180f:6d4e:3f0c', '2026-10-21 09:21:39'),
(1502, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 239) as full_day', '2405:201:6818:f058:2e9f:180f:6d4e:3f0c', '2026-10-21 09:21:40'),
(1503, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305a:a823:8000::', '2026-10-21 09:25:24'),
(1504, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 235) as full_day', '2409:40d2:305a:a823:8000::', '2026-10-21 09:25:26'),
(1505, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '112.110.50.103', '2026-10-21 09:31:20'),
(1506, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 234) as full_day', '112.110.50.103', '2026-10-21 09:31:21'),
(1507, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:34:53'),
(1508, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 232) as full_day', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:34:54'),
(1509, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:34:57'),
(1510, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 09:34:57 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:34:57'),
(1511, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:6564:bce7:924e:586', '2026-10-21 09:39:26'),
(1512, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2405:201:6804:bcd8:6564:bce7:924e:586', '2026-10-21 09:39:49'),
(1513, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 09:39:48 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:6564:bce7:924e:586', '2026-10-21 09:39:49'),
(1514, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:39:51'),
(1515, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:39:55'),
(1516, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 09:39:55 AM (IST). Late minutes: 0', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:39:55'),
(1517, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 09:43:19'),
(1518, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '47.15.114.89', '2026-10-21 09:43:23'),
(1519, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 09:43:23 AM (IST). Late minutes: 3', '47.15.114.89', '2026-10-21 09:43:23'),
(1520, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 233) as full_day', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 09:43:30'),
(1521, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 236) as full_day', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 09:43:30'),
(1522, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 237) as full_day', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 09:43:30'),
(1523, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 238) as full_day', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 09:43:30'),
(1524, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 240) as full_day', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 09:43:30'),
(1525, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-19, ID: 242) as half_day', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 09:43:30'),
(1526, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:86a6:acf2:18b3:a842:a088:ba61', '2026-10-21 09:44:26'),
(1527, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '112.110.50.103', '2026-10-21 09:44:29'),
(1528, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 09:44:29 AM (IST). Late minutes: 4', '112.110.50.103', '2026-10-21 09:44:29'),
(1529, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:86a6:acf2:18b3:a842:a088:ba61', '2026-10-21 09:44:35'),
(1530, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:86a6:acf2:18b3:a842:a088:ba61', '2026-10-21 09:44:47'),
(1531, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:86a6:acf2:18b3:a842:a088:ba61', '2026-10-21 09:45:04'),
(1532, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 09:45:04 AM (IST). Late minutes: 5', '2401:4900:86a6:acf2:18b3:a842:a088:ba61', '2026-10-21 09:45:04'),
(1533, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:46:33'),
(1534, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 09:46:39'),
(1535, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305a:a823:8000::', '2026-10-21 09:47:52'),
(1536, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3002:a08:4cb:beff:feb7:3fc', '2026-10-21 09:52:28'),
(1537, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3002:a08:4cb:beff:feb7:3fc', '2026-10-21 09:54:47'),
(1538, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 09:54:47 AM (IST). Late minutes: 14', '2409:40d2:3002:a08:4cb:beff:feb7:3fc', '2026-10-21 09:54:47'),
(1539, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-21 09:58:30'),
(1540, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-21 09:58:34'),
(1541, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 09:58:34 AM (IST). Late minutes: 18', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-21 09:58:34'),
(1542, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:4848:1b5e:ae62:b04c', '2026-10-21 10:15:36'),
(1543, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2405:201:6804:bcd8:4848:1b5e:ae62:b04c', '2026-10-21 10:15:50'),
(1544, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 21/10/2026 10:15:50 AM (IST). Late minutes: 35', '2405:201:6804:bcd8:4848:1b5e:ae62:b04c', '2026-10-21 10:15:50'),
(1545, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 10:28:36'),
(1546, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 11:57:00'),
(1547, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 11:57:26'),
(1548, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 11:57:38'),
(1549, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 11:57:55'),
(1550, 2, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:00:32'),
(1551, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:19:06'),
(1552, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:20:21'),
(1553, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:21:03'),
(1554, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #22 (\'Turmeric victoria\') created by Kartikeyan.', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:22:54'),
(1555, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:23:06'),
(1556, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:23:34'),
(1557, 4, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #82 (\'Turmeric victoria\') created by Rahul and assigned to User #9', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:36:49'),
(1558, 4, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #83 (\'Turmeric victoria\') created by Rahul and assigned to User #9', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:36:52'),
(1559, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 12:37:38'),
(1560, 4, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Rahul approved completed task #81 (\'Shein Mill Website Development\'). Status changed to Done.', '2405:201:6804:bcd8:3081:e308:31d5:1b02', '2026-10-21 12:50:56'),
(1561, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\' on Task #82', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 12:51:56'),
(1562, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #82', '2405:201:6804:bcd8:bd3a:728d:869d:66a4', '2026-10-21 12:51:56'),
(1563, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:9515:8251:6702:9520', '2026-10-21 14:58:13'),
(1564, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:e0fb:6352:75ea:6fa9', '2026-10-21 15:09:53'),
(1565, 4, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Screenshot 2026-10-21 at 2.01.37 PM.png', '2405:201:6804:bcd8:e0fb:6352:75ea:6fa9', '2026-10-21 15:10:04'),
(1566, 4, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Screenshot 2026-10-21 at 2.01.37 PM.png', '2405:201:6804:bcd8:e0fb:6352:75ea:6fa9', '2026-10-21 15:11:11'),
(1567, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: public_html.zip', '2405:201:6804:bcd8:9515:8251:6702:9520', '2026-10-21 15:11:25'),
(1568, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f9df:92fd:d572:f8e9', '2026-10-21 15:12:52'),
(1569, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: public_html.zip', '2405:201:6804:bcd8:f9df:92fd:d572:f8e9', '2026-10-21 15:13:08'),
(1570, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: public_html.zip', '2405:201:6804:bcd8:9515:8251:6702:9520', '2026-10-21 15:13:20'),
(1571, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: index.html', '2405:201:6804:bcd8:f9df:92fd:d572:f8e9', '2026-10-21 15:23:11'),
(1572, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: index.html', '2405:201:6804:bcd8:9515:8251:6702:9520', '2026-10-21 15:23:23'),
(1573, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-21 15:23:29'),
(1574, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: index.html', '2405:201:6804:bcd8:f9df:92fd:d572:f8e9', '2026-10-21 15:24:21'),
(1575, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:b5f9:4434:f910:979e', '2026-10-21 15:37:26'),
(1576, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Masterpiece Psychology - Services  Treatments SEO Plan.pdf', '2405:201:6804:bcd8:f9df:92fd:d572:f8e9', '2026-10-21 15:37:41'),
(1577, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Masterpiece Psychology - Services  Treatments SEO Plan.pdf', '2405:201:6804:bcd8:b5f9:4434:f910:979e', '2026-10-21 15:37:52'),
(1578, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:b5f9:4434:f910:979e', '2026-10-21 15:40:50'),
(1579, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Masterpiece Psychology - Services  Treatments SEO Plan.pdf', '2405:201:6804:bcd8:f9df:92fd:d572:f8e9', '2026-10-21 15:56:27'),
(1580, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #84 (\'ignore\') created by Uvaish and assigned to User #10', '2405:201:6804:bcd8:9515:8251:6702:9520', '2026-10-21 16:16:35'),
(1581, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:7d63:e763:c44e:3423', '2026-10-21 17:57:32'),
(1582, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7037:6804:9084:bd96', '2026-10-21 18:15:06'),
(1583, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 18:30:10'),
(1584, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:555:ff8a:4385:b2c9', '2026-10-21 18:30:26'),
(1585, 5, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/10/2026 06:30:36 PM (IST). Status: full_day, Duration: 08h 50m 41s, Early Exit: 0m', '2405:201:6804:bcd8:555:ff8a:4385:b2c9', '2026-10-21 18:30:36'),
(1586, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/10/2026 06:31:07 PM (IST). Status: half_day, Duration: 08h 46m 03s, Early Exit: 0m', '2405:201:6804:bcd8:7dfd:dff:1c0:31c6', '2026-10-21 18:31:07'),
(1587, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/10/2026 06:31:09 PM (IST). Status: half_day, Duration: 08h 32m 35s, Early Exit: 0m', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-21 18:31:09'),
(1588, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/10/2026 06:31:13 PM (IST). Status: full_day, Duration: 08h 51m 25s, Early Exit: 0m', '2405:201:6804:bcd8:a007:ccbb:7a51:4eee', '2026-10-21 18:31:13'),
(1589, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:555:ff8a:4385:b2c9', '2026-10-21 18:31:19'),
(1590, 9, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #82', '2405:201:6804:bcd8:555:ff8a:4385:b2c9', '2026-10-21 18:31:30'),
(1591, 10, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/10/2026 06:34:46 PM (IST). Status: full_day, Duration: 08h 50m 17s, Early Exit: 0m', '2405:201:6804:bcd8:7037:6804:9084:bd96', '2026-10-21 18:34:46'),
(1592, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305a:a823:8000::', '2026-10-21 18:35:39'),
(1593, 7, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/10/2026 06:35:49 PM (IST). Status: full_day, Duration: 08h 52m 26s, Early Exit: 0m', '2409:40d2:305a:a823:8000::', '2026-10-21 18:35:49'),
(1594, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/10/2026 06:36:41 PM (IST). Status: full_day, Duration: 09h 01m 44s, Early Exit: 0m', '2405:201:6804:bcd8:555:ff8a:4385:b2c9', '2026-10-21 18:36:41'),
(1595, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:4848:1b5e:ae62:b04c', '2026-10-21 18:41:41'),
(1596, 2, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 21/10/2026 06:41:47 PM (IST). Status: half_day, Duration: 08h 25m 57s, Early Exit: 0m', '2405:201:6804:bcd8:4848:1b5e:ae62:b04c', '2026-10-21 18:41:47'),
(1597, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3002:a08:4cb:beff:feb7:3fc', '2026-10-21 19:57:14'),
(1598, 6, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-21, ID: 249) as full_day', '2409:40d2:3002:a08:4cb:beff:feb7:3fc', '2026-10-21 19:57:16'),
(1599, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3019:59bf:8000::', '2026-10-22 09:29:36'),
(1600, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3051:a0a:241a:74ff:fe7b:c748', '2026-10-22 09:33:44'),
(1601, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:3051:a0a:241a:74ff:fe7b:c748', '2026-10-22 09:34:03'),
(1602, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 09:34:03 AM (IST). Late minutes: 0', '2409:40d2:3051:a0a:241a:74ff:fe7b:c748', '2026-10-22 09:34:03'),
(1603, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:86a6:acf2:5877:15b3:71af:aab1', '2026-10-22 09:36:31'),
(1604, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:86a6:acf2:5877:15b3:71af:aab1', '2026-10-22 09:36:39'),
(1605, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:86a6:acf2:5877:15b3:71af:aab1', '2026-10-22 09:36:53'),
(1606, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:86a6:acf2:5877:15b3:71af:aab1', '2026-10-22 09:37:00'),
(1607, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:86a6:acf2:5877:15b3:71af:aab1', '2026-10-22 09:37:32'),
(1608, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:86a6:acf2:5877:15b3:71af:aab1', '2026-10-22 09:37:45'),
(1609, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 09:37:45 AM (IST). Late minutes: 0', '2401:4900:86a6:acf2:5877:15b3:71af:aab1', '2026-10-22 09:37:45'),
(1610, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2c31:b2ee::8a9e:c4dd', '2026-10-22 09:40:36'),
(1611, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2c31:b2ee::8a9e:c4dd', '2026-10-22 09:40:41'),
(1612, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 09:40:41 AM (IST). Late minutes: 0', '2402:8100:2c31:b2ee::8a9e:c4dd', '2026-10-22 09:40:41'),
(1613, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305f:a6ad:38df:41ff:fecc:3732', '2026-10-22 09:44:13'),
(1614, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:305f:a6ad:38df:41ff:fecc:3732', '2026-10-22 09:44:24'),
(1615, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 09:44:24 AM (IST). Late minutes: 4', '2409:40d2:305f:a6ad:38df:41ff:fecc:3732', '2026-10-22 09:44:24'),
(1616, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3019:59bf:8000::', '2026-10-22 09:45:15'),
(1617, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 09:45:15 AM (IST). Late minutes: 5', '2409:40d2:3019:59bf:8000::', '2026-10-22 09:45:15'),
(1618, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:6d0a:8fde:add1:727f', '2026-10-22 09:49:52'),
(1619, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 09:50:10'),
(1620, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 09:50:18'),
(1621, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 09:50:18 AM (IST). Late minutes: 10', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 09:50:18'),
(1622, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:6564:bce7:924e:586', '2026-10-22 09:53:30'),
(1623, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2405:201:6804:bcd8:6564:bce7:924e:586', '2026-10-22 09:53:38'),
(1624, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 09:53:38 AM (IST). Late minutes: 13', '2405:201:6804:bcd8:6564:bce7:924e:586', '2026-10-22 09:53:38'),
(1625, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 09:56:56'),
(1626, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3188:205d:723:a28d', '2026-10-22 09:56:58'),
(1627, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:301e:921f:c46e:a8ff:feaa:cb17', '2026-10-22 09:58:31'),
(1628, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:301e:921f:c46e:a8ff:feaa:cb17', '2026-10-22 09:58:37'),
(1629, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 09:58:37 AM (IST). Late minutes: 18', '2409:40d2:301e:921f:c46e:a8ff:feaa:cb17', '2026-10-22 09:58:37'),
(1630, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #59', '2405:201:6804:bcd8:6d0a:8fde:add1:727f', '2026-10-22 10:02:23'),
(1631, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #59', '2405:201:6804:bcd8:6d0a:8fde:add1:727f', '2026-10-22 10:02:23'),
(1632, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:6cff:7160:42b9:53ac', '2026-10-22 10:08:34'),
(1633, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2405:201:6804:bcd8:6cff:7160:42b9:53ac', '2026-10-22 10:08:39'),
(1634, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 10:08:39 AM (IST). Late minutes: 28', '2405:201:6804:bcd8:6cff:7160:42b9:53ac', '2026-10-22 10:08:39'),
(1635, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:555:ff8a:4385:b2c9', '2026-10-22 10:13:12'),
(1636, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\' on Task #82', '2405:201:6804:bcd8:555:ff8a:4385:b2c9', '2026-10-22 10:13:24'),
(1637, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #82', '2405:201:6804:bcd8:555:ff8a:4385:b2c9', '2026-10-22 10:13:24'),
(1638, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:19:47'),
(1639, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #71', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:20:06'),
(1640, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #71', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:20:06'),
(1641, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #71', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:20:09'),
(1642, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #71', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:20:09'),
(1643, 10, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #71 & submitted for manager approval.', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:20:31'),
(1644, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 10:39:14'),
(1645, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 10:39:35'),
(1646, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #72', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:45:07'),
(1647, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #72', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:45:07'),
(1648, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #72', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:45:07'),
(1649, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #72', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:45:07'),
(1650, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #72', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:45:07'),
(1651, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #72', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:45:07'),
(1652, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #72', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:45:07'),
(1653, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #72', '2405:201:6804:bcd8:997a:18b5:c330:9814', '2026-10-22 10:45:07'),
(1654, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.151', '2026-10-22 10:53:49'),
(1655, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #65', '49.43.160.151', '2026-10-22 10:54:00'),
(1656, 6, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Shivanshu completed task #55 (\'Make 100 backlinks for jeeven sankalp\') and submitted for manager & product manager approval.', '49.43.160.151', '2026-10-22 10:55:02'),
(1657, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #85 (\'backlinks on frontier jewellers\') created by Uvaish and assigned to User #6', '2405:201:6804:bcd8:6d0a:8fde:add1:727f', '2026-10-22 11:01:14'),
(1658, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #85', '49.43.160.151', '2026-10-22 11:02:02'),
(1659, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #85', '49.43.160.151', '2026-10-22 11:02:02'),
(1660, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 11:23:11'),
(1661, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 11:23:51'),
(1662, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 11:23:59'),
(1663, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 22/10/2026 11:23:59 AM (IST). Late minutes: 103', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 11:23:59'),
(1664, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 11:24:04'),
(1665, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:a839:eaa3:a345:6193', '2026-10-22 11:25:36'),
(1666, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #32', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 12:21:37'),
(1667, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #32', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 12:21:37'),
(1668, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.151', '2026-10-22 12:26:17'),
(1669, 8, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #32 & submitted for manager approval.', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 13:15:34'),
(1670, 8, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Priyansh completed task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\') and submitted for manager & product manager approval.', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 13:15:43'),
(1671, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\' on Task #33', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 13:15:50'),
(1672, 8, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #33', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 13:15:50'),
(1673, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8619:6eaa:e9e2:ef49', '2026-10-22 13:43:18'),
(1674, 8, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Priyansh completed task #33 (\'List Sunrise On Booking.com\') and submitted for manager & product manager approval.', '2405:201:6804:bcd8:f541:8a60:9df2:889a', '2026-10-22 13:44:19'),
(1675, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3c53:7e9b:277f:779f', '2026-10-22 13:46:35'),
(1676, 9, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #82', '2405:201:6804:bcd8:80b3:be5b:3ed2:bfd2', '2026-10-22 14:00:49'),
(1677, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:19b1:ae3b:6d3f:7107', '2026-10-22 15:33:29'),
(1678, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:49c5:d2ff:b043:98b7', '2026-10-22 16:11:26'),
(1679, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\' on Task #82', '2401:4900:883d:436:49c5:d2ff:b043:98b7', '2026-10-22 16:11:38'),
(1680, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #82', '2401:4900:883d:436:49c5:d2ff:b043:98b7', '2026-10-22 16:11:38'),
(1681, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883d:436:7555:72d2:5b2b:3414', '2026-10-22 16:51:35'),
(1682, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:6c36:3388:4320:8938', '2026-10-22 16:52:09'),
(1683, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:6c36:3388:4320:8938', '2026-10-22 16:52:46'),
(1684, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:6c36:3388:4320:8938', '2026-10-22 16:52:56'),
(1685, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #33 (\'List Sunrise On Booking.com\'). Status changed to Done.', '2401:4900:883d:436:7555:72d2:5b2b:3414', '2026-10-22 16:53:52'),
(1686, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #32 (\'List Wildflower on Booking.com\'). Status changed to Done.', '2401:4900:883d:436:7555:72d2:5b2b:3414', '2026-10-22 16:54:01'),
(1687, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:6c36:3388:4320:8938', '2026-10-22 16:54:06'),
(1688, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\'). Status changed to Done.', '2401:4900:883d:436:7555:72d2:5b2b:3414', '2026-10-22 16:54:11'),
(1689, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #55 (\'Make 100 backlinks for jeeven sankalp\'). Status changed to Done.', '2401:4900:883d:436:7555:72d2:5b2b:3414', '2026-10-22 16:54:37'),
(1690, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #71 (\'kashmiri sons blogs on Uttarakhand Stays\'). Status changed to Done.', '2401:4900:883d:436:7555:72d2:5b2b:3414', '2026-10-22 16:54:56'),
(1691, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #70 (\'Kashmiri sons 5 Blogs Uttarakhand wedding\'). Status changed to Done.', '2401:4900:883d:436:7555:72d2:5b2b:3414', '2026-10-22 16:55:03'),
(1692, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883d:436:c595:3627:7aa7:5d20', '2026-10-22 17:00:39'),
(1693, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-22 18:06:11'),
(1694, 5, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #86 (\'Liquor fort 50 backlinks\') created by Uvaish and assigned to User #6', '106.219.146.66', '2026-10-22 18:07:17'),
(1695, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-22 18:18:28'),
(1696, 10, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #72 & submitted for manager approval.', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-22 18:18:51'),
(1697, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:9d99:7068:500a:8afe', '2026-10-22 18:29:10'),
(1698, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 22/10/2026 06:30:24 PM (IST). Status: full_day, Duration: 08h 52m 39s, Early Exit: 0m', '2401:4900:883d:436:9d99:7068:500a:8afe', '2026-10-22 18:30:24'),
(1699, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:19b1:ae3b:6d3f:7107', '2026-10-22 18:30:58'),
(1700, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 22/10/2026 06:31:11 PM (IST). Status: half_day, Duration: 08h 37m 33s, Early Exit: 0m', '2401:4900:883d:436:19b1:ae3b:6d3f:7107', '2026-10-22 18:31:11'),
(1701, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:ac6e:eeed:e93b:1535', '2026-10-22 18:31:56'),
(1702, 9, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #82', '2401:4900:883d:436:ac6e:eeed:e93b:1535', '2026-10-22 18:32:07'),
(1703, 9, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #82', '2401:4900:883d:436:ac6e:eeed:e93b:1535', '2026-10-22 18:32:09'),
(1704, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:86a6:acf2:558e:eea2:a348:6e84', '2026-10-22 19:41:11'),
(1705, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2c39:db4a::8adc:6cd8', '2026-10-22 22:14:46'),
(1706, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-22, ID: 254) as full_day', '2402:8100:2c39:db4a::8adc:6cd8', '2026-10-22 22:14:49'),
(1707, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:49c5:d2ff:b043:98b7', '2026-10-23 08:18:41'),
(1708, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-22, ID: 255) as full_day', '2401:4900:883d:436:49c5:d2ff:b043:98b7', '2026-10-23 08:18:43'),
(1709, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883d:436:49c5:d2ff:b043:98b7', '2026-10-23 08:55:21'),
(1710, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 08:55:21 AM (IST). Late minutes: 0', '2401:4900:883d:436:49c5:d2ff:b043:98b7', '2026-10-23 08:55:21'),
(1711, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:2dfa:75ef:7867:8a69', '2026-10-23 09:25:43');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(1712, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883d:436:2dfa:75ef:7867:8a69', '2026-10-23 09:25:56'),
(1713, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 09:25:56 AM (IST). Late minutes: 0', '2401:4900:883d:436:2dfa:75ef:7867:8a69', '2026-10-23 09:25:56'),
(1714, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:b316:8000::', '2026-10-23 09:36:31'),
(1715, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-22, ID: 256) as full_day', '2409:40d2:3011:b316:8000::', '2026-10-23 09:36:32'),
(1716, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2c27:87e6::8b1e:4c5f', '2026-10-23 09:43:21'),
(1717, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2c27:87e6::8b1e:4c5f', '2026-10-23 09:43:25'),
(1718, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 09:43:25 AM (IST). Late minutes: 3', '2402:8100:2c27:87e6::8b1e:4c5f', '2026-10-23 09:43:25'),
(1719, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-23 09:43:38'),
(1720, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-10-23 09:44:03'),
(1721, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 09:44:03 AM (IST). Late minutes: 4', '2409:4085:ae90:3afd::95c9:520c', '2026-10-23 09:44:03'),
(1722, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3011:b316:8000::', '2026-10-23 09:44:35'),
(1723, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 09:44:35 AM (IST). Late minutes: 4', '2409:40d2:3011:b316:8000::', '2026-10-23 09:44:35'),
(1724, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:7898:7389:cd88:69c7', '2026-10-23 09:45:21'),
(1725, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-22, ID: 257) as full_day', '2401:4900:883d:436:7898:7389:cd88:69c7', '2026-10-23 09:45:22'),
(1726, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883d:436:7898:7389:cd88:69c7', '2026-10-23 09:45:25'),
(1727, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 09:45:25 AM (IST). Late minutes: 5', '2401:4900:883d:436:7898:7389:cd88:69c7', '2026-10-23 09:45:25'),
(1728, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 09:47:58'),
(1729, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:45e6:91da:a7c1:ff2c', '2026-10-23 09:47:58'),
(1730, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-22, ID: 252) as full_day', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 09:48:04'),
(1731, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-22, ID: 259) as full_day', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 09:48:04'),
(1732, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-22, ID: 260) as full_day', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 09:48:04'),
(1733, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-22, ID: 261) as half_day', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 09:48:04'),
(1734, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 09:50:12'),
(1735, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:b0ab:f172:9d18:aa1e', '2026-10-23 09:55:34'),
(1736, 8, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Priyansh completed task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\') and submitted for manager & product manager approval.', '2401:4900:883d:436:7898:7389:cd88:69c7', '2026-10-23 10:21:14'),
(1737, 8, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #64 & submitted for manager approval.', '2401:4900:883d:436:7898:7389:cd88:69c7', '2026-10-23 10:21:26'),
(1738, 9, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Abhay completed task #82 (\'Turmeric victoria\') and submitted for manager & product manager approval.', '2401:4900:883d:436:b0ab:f172:9d18:aa1e', '2026-10-23 10:25:08'),
(1739, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 10:29:29'),
(1740, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 10:29:34'),
(1741, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 10:29:34 AM (IST). Late minutes: 49', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 10:29:34'),
(1742, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 10:29:45'),
(1743, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:32:12'),
(1744, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #73', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:32:22'),
(1745, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #73', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:32:22'),
(1746, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #73', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:34:12'),
(1747, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #73', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:34:12'),
(1748, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #73', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:48:55'),
(1749, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #73', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:48:55'),
(1750, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #73', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:48:55'),
(1751, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #73', '2401:4900:883d:436:3884:bc05:1201:fc3b', '2026-10-23 10:48:55'),
(1752, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:bc21:899f::4d2b:81e', '2026-10-23 10:58:52'),
(1753, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #82 (\'Turmeric victoria\'). Status changed to Done.', '2401:4900:bc21:899f::4d2b:81e', '2026-10-23 10:59:17'),
(1754, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #64 (\'Standard Social Media\'). Status changed to Done.', '2401:4900:bc21:899f::4d2b:81e', '2026-10-23 10:59:36'),
(1755, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\'). Status changed to Done.', '2401:4900:bc21:899f::4d2b:81e', '2026-10-23 10:59:49'),
(1756, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #72 (\'Kashmiri Sons blogs on Dehradungram\'). Status changed to Done.', '2401:4900:bc21:899f::4d2b:81e', '2026-10-23 10:59:57'),
(1757, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #87 (\'List Little Lane Cottage on Booking.com\') created by Kartikeyan and assigned to User #8', '2401:4900:bc21:899f::4d2b:81e', '2026-10-23 11:01:20'),
(1758, 1, NULL, 'Create Project', NULL, NULL, NULL, 'Projects', 'Project #23 (\'Sheina Mill\') created by Kartikeyan.', '2401:4900:bc21:899f::4d2b:81e', '2026-10-23 11:02:37'),
(1759, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #88 (\'Check content of new Sheina Mill website\') created by Kartikeyan and assigned to User #5', '2401:4900:bc21:899f::4d2b:81e', '2026-10-23 11:02:53'),
(1760, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #89 (\'Make review QR code for Almost Heaven Mussoorie\') created by Kartikeyan and assigned to User #5', '2405:201:6817:f0d3:845b:5b95:a63c:5a95', '2026-10-23 11:18:32'),
(1761, 1, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #1 started activity \'Working on Task\'', '2405:201:6817:f0d3:845b:5b95:a63c:5a95', '2026-10-23 11:19:10'),
(1762, 1, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 11:19:10 AM (IST). Late minutes: 99', '2405:201:6817:f0d3:845b:5b95:a63c:5a95', '2026-10-23 11:19:10'),
(1763, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883d:436:8940:9677:5881:6804', '2026-10-23 11:25:46'),
(1764, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2401:4900:883d:436:8940:9677:5881:6804', '2026-10-23 11:25:51'),
(1765, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 23/10/2026 11:25:51 AM (IST). Late minutes: 105', '2401:4900:883d:436:8940:9677:5881:6804', '2026-10-23 11:25:51'),
(1766, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f0d3:845b:5b95:a63c:5a95', '2026-10-23 11:45:47'),
(1767, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #90 (\'Remake Turmeric Demo Table Booking Screen Recording in english.\') created by Kartikeyan and assigned to User #5', '2405:201:6817:f0d3:845b:5b95:a63c:5a95', '2026-10-23 11:47:05'),
(1768, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 12:07:47'),
(1769, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 12:09:51'),
(1770, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:300e:2e36:241a:74ff:fe7b:c748', '2026-10-23 12:18:09'),
(1771, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #59 completed & marked Done by Uvaish.', '2409:40d2:300e:2e36:241a:74ff:fe7b:c748', '2026-10-23 12:18:29'),
(1772, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f0d3:4c0a:e589:3585:bfd8', '2026-10-23 13:18:06'),
(1773, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #91 (\'Launch Ads on all the latest reels and post of Renee Mill . She has connected the Card for Ads .\') created by Kartikeyan and assigned to User #14', '2405:201:6817:f0d3:4c0a:e589:3585:bfd8', '2026-10-23 13:20:49'),
(1774, 1, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #92 (\'Add Krafting Digital GST in all the ads account possible (meta and google both )\') created by Kartikeyan and assigned to User #14', '2405:201:6817:f0d3:4c0a:e589:3585:bfd8', '2026-10-23 13:24:22'),
(1775, 1, NULL, 'Emergency Request', NULL, NULL, NULL, 'Tasks', 'Emergency task request #6 submitted by Kartikeyan for task #62 (\'CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE\')', '2405:201:6817:f0d3:4c0a:e589:3585:bfd8', '2026-10-23 13:28:56'),
(1776, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:b316:8000::', '2026-10-23 14:40:36'),
(1777, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:b962:524f:ba80:3497', '2026-10-23 15:09:09'),
(1778, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 15:31:24'),
(1779, 3, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Udit completed task #40 (\'Make New Taj reels from new photoshoots\') and submitted for manager & product manager approval.', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 15:31:47'),
(1780, 3, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Udit approved completed task #40 (\'Make New Taj reels from new photoshoots\'). Status changed to Done.', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 15:32:00'),
(1781, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:3c11:1418:92c2:f38d', '2026-10-23 15:32:24'),
(1782, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:bdbf:adc4:d475:ce70', '2026-10-23 18:14:06'),
(1783, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:6cfb:7f6a:d6e7:88a4', '2026-10-23 18:19:43'),
(1784, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:b0ab:f172:9d18:aa1e', '2026-10-23 18:27:24'),
(1785, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 23/10/2026 06:29:17 PM (IST). Status: full_day, Duration: 09h 33m 56s, Early Exit: 0m', '2401:4900:883d:436:b0ab:f172:9d18:aa1e', '2026-10-23 18:29:17'),
(1786, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:6cfb:7f6a:d6e7:88a4', '2026-10-23 18:30:58'),
(1787, 4, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 23/10/2026 06:31:10 PM (IST). Status: full_day, Duration: 09h 05m 14s, Early Exit: 0m', '2401:4900:883d:436:6cfb:7f6a:d6e7:88a4', '2026-10-23 18:31:10'),
(1788, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:f115:62ce:6d84:acf', '2026-10-23 18:31:23'),
(1789, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-23 18:31:30'),
(1790, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 23/10/2026 06:31:33 PM (IST). Status: half_day, Duration: 08h 46m 08s, Early Exit: 0m', '2401:4900:883d:436:f115:62ce:6d84:acf', '2026-10-23 18:31:33'),
(1791, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:b316:8000::', '2026-10-23 18:31:39'),
(1792, 7, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 23/10/2026 06:31:45 PM (IST). Status: half_day, Duration: 08h 47m 10s, Early Exit: 0m', '2409:40d2:3011:b316:8000::', '2026-10-23 18:31:45'),
(1793, 10, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 23/10/2026 06:33:10 PM (IST). Status: half_day, Duration: 08h 49m 45s, Early Exit: 0m', '106.219.146.66', '2026-10-23 18:33:10'),
(1794, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-23 18:35:57'),
(1795, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 23/10/2026 06:36:07 PM (IST). Status: full_day, Duration: 08h 52m 04s, Early Exit: 0m', '2409:4085:ae90:3afd::95c9:520c', '2026-10-23 18:36:07'),
(1796, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:bbf6:bb72:610d:e695:54fd:a87c', '2026-10-24 08:42:18'),
(1797, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:bbf6:bb72:5db1:3560:7c7a:71c7', '2026-10-24 09:21:37'),
(1798, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:bbf6:bb72:5db1:3560:7c7a:71c7', '2026-10-24 09:21:48'),
(1799, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/10/2026 09:21:48 AM (IST). Late minutes: 0', '2401:4900:bbf6:bb72:5db1:3560:7c7a:71c7', '2026-10-24 09:21:48'),
(1800, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3041:2508:8000::', '2026-10-24 09:35:06'),
(1801, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3041:2508:8000::', '2026-10-24 09:35:14'),
(1802, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/10/2026 09:35:14 AM (IST). Late minutes: 0', '2409:40d2:3041:2508:8000::', '2026-10-24 09:35:14'),
(1803, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3017:4543:504a:c7ff:fe96:e9f1', '2026-10-24 09:42:38'),
(1804, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3017:4543:504a:c7ff:fe96:e9f1', '2026-10-24 09:43:37'),
(1805, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/10/2026 09:43:37 AM (IST). Late minutes: 3', '2409:40d2:3017:4543:504a:c7ff:fe96:e9f1', '2026-10-24 09:43:37'),
(1806, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '112.110.50.185', '2026-10-24 09:44:30'),
(1807, 10, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #73 & submitted for manager approval.', '112.110.50.185', '2026-10-24 09:44:52'),
(1808, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '112.110.50.185', '2026-10-24 09:45:08'),
(1809, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/10/2026 09:45:08 AM (IST). Late minutes: 5', '112.110.50.185', '2026-10-24 09:45:08'),
(1810, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-24 09:45:09'),
(1811, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-10-24 09:45:24'),
(1812, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/10/2026 09:45:24 AM (IST). Late minutes: 5', '2409:4085:ae90:3afd::95c9:520c', '2026-10-24 09:45:24'),
(1813, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:8077:2462:df1c:d96e', '2026-10-24 09:48:50'),
(1814, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-23, ID: 268) as half_day', '2401:4900:883d:436:8077:2462:df1c:d96e', '2026-10-24 09:49:01'),
(1815, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-23, ID: 269) as half_day', '2401:4900:883d:436:8077:2462:df1c:d96e', '2026-10-24 09:49:01'),
(1816, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-23, ID: 270) as half_day', '2401:4900:883d:436:8077:2462:df1c:d96e', '2026-10-24 09:49:01'),
(1817, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:35eb:2685:b45f:265c', '2026-10-24 09:51:04'),
(1818, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:154b:53e7:47e9:1b27', '2026-10-24 09:52:19'),
(1819, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883d:436:81f4:702e:b145:f124', '2026-10-24 10:26:34'),
(1820, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2401:4900:883d:436:81f4:702e:b145:f124', '2026-10-24 10:26:55'),
(1821, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/10/2026 10:26:55 AM (IST). Late minutes: 46', '2401:4900:883d:436:81f4:702e:b145:f124', '2026-10-24 10:26:55'),
(1822, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:154b:53e7:47e9:1b27', '2026-10-24 10:50:07'),
(1823, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883d:436:154b:53e7:47e9:1b27', '2026-10-24 10:50:57'),
(1824, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883d:436:154b:53e7:47e9:1b27', '2026-10-24 10:51:02'),
(1825, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 24/10/2026 10:51:02 AM (IST). Late minutes: 71', '2401:4900:883d:436:154b:53e7:47e9:1b27', '2026-10-24 10:51:02'),
(1826, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:436:154b:53e7:47e9:1b27', '2026-10-24 10:51:07'),
(1827, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:436:9f82:b9f8:eedd:ff0a', '2026-10-24 15:35:03'),
(1828, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #74', '2401:4900:883d:436:9f82:b9f8:eedd:ff0a', '2026-10-24 15:35:17'),
(1829, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #74', '2401:4900:883d:436:9f82:b9f8:eedd:ff0a', '2026-10-24 15:35:17'),
(1830, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f0d3:3d79:876e:b4e1:3b82', '2026-10-24 17:12:02'),
(1831, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #73 (\'Jeevan sankalp blogs on Dehradun gurugram\'). Status changed to Done.', '2405:201:6817:f0d3:3d79:876e:b4e1:3b82', '2026-10-24 17:12:20'),
(1832, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883d:436:81f4:702e:b145:f124', '2026-10-24 17:23:58'),
(1833, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:bf85:ed81:edb1:b3e3:4729:db2a', '2026-10-25 11:59:41'),
(1834, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-24, ID: 271) as full_day', '2401:4900:bf85:ed81:edb1:b3e3:4729:db2a', '2026-10-25 11:59:43'),
(1835, 4, NULL, 'Request Leave', NULL, NULL, NULL, 'Leaves', 'User Rahul requested 1 days leave (2026-10-27 to 2026-10-27)', '2401:4900:bf85:ed81:edb1:b3e3:4729:db2a', '2026-10-25 12:00:31'),
(1836, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:99ea:71cd:5698:e089', '2026-10-26 08:59:16'),
(1837, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3055:48a1:a0a5:38ff:fea7:d068', '2026-10-26 09:29:55'),
(1838, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3055:48a1:a0a5:38ff:fea7:d068', '2026-10-26 09:30:04'),
(1839, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 09:30:04 AM (IST). Late minutes: 0', '2409:40d2:3055:48a1:a0a5:38ff:fea7:d068', '2026-10-26 09:30:04'),
(1840, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6818:f058:99ea:71cd:5698:e089', '2026-10-26 09:33:09'),
(1841, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 09:33:09 AM (IST). Late minutes: 0', '2405:201:6818:f058:99ea:71cd:5698:e089', '2026-10-26 09:33:09'),
(1842, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:cc99:78e5:66ff:feda:752b', '2026-10-26 09:37:31'),
(1843, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-24, ID: 273) as full_day', '2409:40d2:3014:cc99:78e5:66ff:feda:752b', '2026-10-26 09:37:32'),
(1844, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3014:cc99:78e5:66ff:feda:752b', '2026-10-26 09:37:35'),
(1845, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 09:37:35 AM (IST). Late minutes: 0', '2409:40d2:3014:cc99:78e5:66ff:feda:752b', '2026-10-26 09:37:35'),
(1846, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:6d49:6264:dfb5:97a8', '2026-10-26 09:41:00'),
(1847, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:301e:51cb:8000::', '2026-10-26 09:41:19'),
(1848, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-24, ID: 272) as full_day', '2409:40d2:301e:51cb:8000::', '2026-10-26 09:41:20'),
(1849, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:a533:cf3c:c187:ed3b:c8f4:11d5', '2026-10-26 09:42:23'),
(1850, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:a533:cf3c:c187:ed3b:c8f4:11d5', '2026-10-26 09:42:47'),
(1851, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 09:42:47 AM (IST). Late minutes: 2', '2401:4900:a533:cf3c:c187:ed3b:c8f4:11d5', '2026-10-26 09:42:47'),
(1852, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:301e:51cb:8000::', '2026-10-26 09:44:06'),
(1853, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 09:44:05 AM (IST). Late minutes: 4', '2409:40d2:301e:51cb:8000::', '2026-10-26 09:44:06'),
(1854, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2045:7f0f::de9a:5063', '2026-10-26 09:45:50'),
(1855, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-24, ID: 274) as full_day', '2402:8100:2045:7f0f::de9a:5063', '2026-10-26 09:45:51'),
(1856, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2045:7f0f::de9a:5063', '2026-10-26 09:45:55'),
(1857, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 09:45:55 AM (IST). Late minutes: 5', '2402:8100:2045:7f0f::de9a:5063', '2026-10-26 09:45:55'),
(1858, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:2dc1:c8b1:e635:f6e3', '2026-10-26 09:46:01'),
(1859, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:ce9f:2dc1:c8b1:e635:f6e3', '2026-10-26 09:46:08'),
(1860, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 09:46:08 AM (IST). Late minutes: 6', '2401:4900:883e:ce9f:2dc1:c8b1:e635:f6e3', '2026-10-26 09:46:08'),
(1861, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-24, ID: 275) as full_day', '2401:4900:883e:ce9f:6d49:6264:dfb5:97a8', '2026-10-26 09:46:57'),
(1862, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-24, ID: 276) as half_day', '2401:4900:883e:ce9f:6d49:6264:dfb5:97a8', '2026-10-26 09:46:57'),
(1863, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-24, ID: 277) as half_day', '2401:4900:883e:ce9f:6d49:6264:dfb5:97a8', '2026-10-26 09:46:57'),
(1864, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-26 09:55:28'),
(1865, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-10-26 09:55:43'),
(1866, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 09:55:43 AM (IST). Late minutes: 15', '2409:4085:ae90:3afd::95c9:520c', '2026-10-26 09:55:43'),
(1867, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:343f:b78c:6c92:53c2', '2026-10-26 10:01:01'),
(1868, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-26 10:07:27'),
(1869, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #37', '106.219.146.66', '2026-10-26 10:09:36'),
(1870, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #37', '106.219.146.66', '2026-10-26 10:09:36'),
(1871, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #37 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-26 10:10:03'),
(1872, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #38', '106.219.146.66', '2026-10-26 10:10:18'),
(1873, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #38', '106.219.146.66', '2026-10-26 10:10:18'),
(1874, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #38 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-26 10:10:30'),
(1875, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #51', '106.219.146.66', '2026-10-26 10:11:15'),
(1876, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #51', '106.219.146.66', '2026-10-26 10:11:15'),
(1877, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #51 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-26 10:12:40'),
(1878, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 10:16:00'),
(1879, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 10:17:34'),
(1880, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 10:18:16'),
(1881, 1, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 10:19:20'),
(1882, 8, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Priyansh completed task #87 (\'List Little Lane Cottage on Booking.com\') and submitted for manager & product manager approval.', '2401:4900:883e:ce9f:2dc1:c8b1:e635:f6e3', '2026-10-26 10:30:58'),
(1883, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-26 10:43:04'),
(1884, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #85', '106.219.146.66', '2026-10-26 10:43:39'),
(1885, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:1df1:13e3:5252:e658', '2026-10-26 10:46:35'),
(1886, 10, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #74', '2401:4900:883e:ce9f:1df1:13e3:5252:e658', '2026-10-26 10:48:12'),
(1887, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #86', '106.219.146.66', '2026-10-26 10:57:52'),
(1888, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #86', '106.219.146.66', '2026-10-26 10:57:52'),
(1889, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:6d49:6264:dfb5:97a8', '2026-10-26 11:45:55'),
(1890, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6817:f0d3:29f9:c0de:f117:40c2', '2026-10-26 12:01:59'),
(1891, 1, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Kartikeyan approved completed task #87 (\'List Little Lane Cottage on Booking.com\'). Status changed to Done.', '2405:201:6817:f0d3:29f9:c0de:f117:40c2', '2026-10-26 12:02:12'),
(1892, 1, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #1 started activity \'Working on Task\'', '2405:201:6817:f0d3:29f9:c0de:f117:40c2', '2026-10-26 12:29:06'),
(1893, 1, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 12:29:06 PM (IST). Late minutes: 169', '2405:201:6817:f0d3:29f9:c0de:f117:40c2', '2026-10-26 12:29:06'),
(1894, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-26 12:44:16'),
(1895, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #86', '106.219.146.66', '2026-10-26 12:44:24'),
(1896, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #85', '106.219.146.66', '2026-10-26 12:44:34'),
(1897, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #85', '106.219.146.66', '2026-10-26 12:44:34'),
(1898, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #44', '106.219.146.66', '2026-10-26 13:05:53'),
(1899, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #44', '106.219.146.66', '2026-10-26 13:05:53'),
(1900, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #44 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-26 13:08:47'),
(1901, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Blog_Content_SOP.pdf', '106.219.146.66', '2026-10-26 13:09:37'),
(1902, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:2954:b00d:ff96:61c0', '2026-10-26 13:09:51'),
(1903, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Blog_Content_SOP.pdf', '2401:4900:883e:ce9f:2954:b00d:ff96:61c0', '2026-10-26 13:10:47'),
(1904, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #86', '106.219.146.66', '2026-10-26 13:38:59'),
(1905, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #85', '106.219.146.66', '2026-10-26 13:39:03'),
(1906, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #85', '106.219.146.66', '2026-10-26 13:39:51'),
(1907, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #85', '106.219.146.66', '2026-10-26 13:39:51'),
(1908, 5, NULL, 'Approve Emergency Request', NULL, NULL, NULL, 'Tasks', 'Emergency request #6 for task #62 approved by Uvaish', '106.219.146.66', '2026-10-26 13:45:01'),
(1909, 6, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Shivanshu completed task #65 (\'Make 100 backlinks for jeeven sankalp\') and submitted for manager & product manager approval.', '106.219.146.66', '2026-10-26 13:49:44'),
(1910, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #85', '106.219.146.66', '2026-10-26 13:50:04'),
(1911, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #53', '106.219.146.66', '2026-10-26 13:50:12'),
(1912, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #53', '106.219.146.66', '2026-10-26 13:50:12'),
(1913, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #53 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-26 13:50:47'),
(1914, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 14:05:15'),
(1915, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 14:05:23'),
(1916, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 26/10/2026 02:05:23 PM (IST). Late minutes: 265', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 14:05:23'),
(1917, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #88', '106.219.146.66', '2026-10-26 14:08:51'),
(1918, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #88', '106.219.146.66', '2026-10-26 14:08:51'),
(1919, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #88 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-26 14:10:14'),
(1920, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:2dc1:c8b1:e635:f6e3', '2026-10-26 15:29:54'),
(1921, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:1df1:13e3:5252:e658', '2026-10-26 15:30:28'),
(1922, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-26 15:32:31'),
(1923, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #86', '106.219.146.66', '2026-10-26 15:32:37'),
(1924, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #86', '106.219.146.66', '2026-10-26 15:32:37'),
(1925, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #86', '106.219.146.66', '2026-10-26 15:32:39'),
(1926, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #86', '106.219.146.66', '2026-10-26 15:32:39'),
(1927, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #86', '106.219.146.66', '2026-10-26 15:32:43'),
(1928, 6, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Shivanshu completed task #86 (\'Liquor fort 50 backlinks\') and submitted for manager & product manager approval.', '106.219.146.66', '2026-10-26 15:33:03'),
(1929, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #89', '106.219.146.66', '2026-10-26 15:50:29'),
(1930, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #89', '106.219.146.66', '2026-10-26 15:50:29'),
(1931, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #89 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-26 15:50:40'),
(1932, 5, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Uvaish approved completed task #86 (\'Liquor fort 50 backlinks\'). Status changed to Done.', '106.219.146.66', '2026-10-26 15:50:58'),
(1933, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883e:ce9f:d820:cee6:a3e7:8ffb', '2026-10-26 16:49:58'),
(1934, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 16:51:11'),
(1935, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:c8f6:5848:d97a:5280', '2026-10-26 17:27:33'),
(1936, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:542:6cd6:e3b5:b0b6', '2026-10-26 17:44:40'),
(1937, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:4ffa:9c0e:3fe7:15ad', '2026-10-26 18:25:15'),
(1938, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 26/10/2026 06:26:44 PM (IST). Status: half_day, Duration: 08h 56m 40s, Early Exit: 3m', '106.219.146.66', '2026-10-26 18:26:44'),
(1939, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:c8f6:5848:d97a:5280', '2026-10-26 18:27:42'),
(1940, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:50f0:50d1:ee61:41aa', '2026-10-26 18:28:30'),
(1941, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-26 18:28:45'),
(1942, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:50f0:50d1:ee61:41aa', '2026-10-26 18:28:57'),
(1943, 10, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 26/10/2026 06:29:24 PM (IST). Status: half_day, Duration: 08h 43m 29s, Early Exit: 0m', '2401:4900:883e:ce9f:c8f6:5848:d97a:5280', '2026-10-26 18:29:24'),
(1944, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-26 18:42:51'),
(1945, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 26/10/2026 06:44:09 PM (IST). Status: half_day, Duration: 08h 48m 26s, Early Exit: 0m', '2409:4085:ae90:3afd::95c9:520c', '2026-10-26 18:44:09'),
(1946, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:fbd2:a1b9:3d2f:f36f:ccbc', '2026-10-27 09:22:27'),
(1947, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-26, ID: 279) as full_day', '2405:201:6818:fbd2:a1b9:3d2f:f36f:ccbc', '2026-10-27 09:22:28'),
(1948, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:25:07'),
(1949, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-26, ID: 282) as full_day', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:25:09'),
(1950, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6818:fbd2:a1b9:3d2f:f36f:ccbc', '2026-10-27 09:35:11'),
(1951, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/10/2026 09:35:11 AM (IST). Late minutes: 0', '2405:201:6818:fbd2:a1b9:3d2f:f36f:ccbc', '2026-10-27 09:35:11'),
(1952, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305e:7e15:7cf2:2eff:fe34:32c5', '2026-10-27 09:43:49'),
(1953, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:305e:7e15:7cf2:2eff:fe34:32c5', '2026-10-27 09:43:54'),
(1954, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/10/2026 09:43:54 AM (IST). Late minutes: 3', '2409:40d2:305e:7e15:7cf2:2eff:fe34:32c5', '2026-10-27 09:43:54'),
(1955, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:44:33'),
(1956, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/10/2026 09:44:33 AM (IST). Late minutes: 4', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:44:33'),
(1957, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-27 09:52:05'),
(1958, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-10-27 09:52:23'),
(1959, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/10/2026 09:52:23 AM (IST). Late minutes: 12', '2409:4085:ae90:3afd::95c9:520c', '2026-10-27 09:52:23'),
(1960, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:55:34'),
(1961, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-26, ID: 280) as full_day', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:55:48'),
(1962, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-26, ID: 281) as full_day', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:55:48'),
(1963, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-26, ID: 284) as full_day', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:55:48'),
(1964, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-26, ID: 286) as half_day', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:55:48'),
(1965, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-26, ID: 287) as half_day', '2409:40d2:304b:30cb:8000::', '2026-10-27 09:55:48'),
(1966, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:c874:529b:3237:e700', '2026-10-27 09:59:27'),
(1967, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2f0d:edac::1a36:59d5', '2026-10-27 10:01:33'),
(1968, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2f0d:edac::1a36:59d5', '2026-10-27 10:01:44'),
(1969, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/10/2026 10:01:44 AM (IST). Late minutes: 21', '2402:8100:2f0d:edac::1a36:59d5', '2026-10-27 10:01:44'),
(1970, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3015:146b:d494:2eff:febf:18e7', '2026-10-27 10:02:40'),
(1971, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3015:146b:d494:2eff:febf:18e7', '2026-10-27 10:02:47'),
(1972, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/10/2026 10:02:47 AM (IST). Late minutes: 22', '2409:40d2:3015:146b:d494:2eff:febf:18e7', '2026-10-27 10:02:47'),
(1973, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:50f0:50d1:ee61:41aa', '2026-10-27 10:04:40'),
(1974, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:ce9f:50f0:50d1:ee61:41aa', '2026-10-27 10:04:44'),
(1975, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/10/2026 10:04:44 AM (IST). Late minutes: 24', '2401:4900:883e:ce9f:50f0:50d1:ee61:41aa', '2026-10-27 10:04:44'),
(1976, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:c8f6:5848:d97a:5280', '2026-10-27 10:09:11'),
(1977, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-27 10:28:44'),
(1978, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883e:ce9f:ccd3:e11d:bb25:32b3', '2026-10-27 10:44:24'),
(1979, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883e:ce9f:ccd3:e11d:bb25:32b3', '2026-10-27 10:44:42'),
(1980, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 27/10/2026 10:44:42 AM (IST). Late minutes: 64', '2401:4900:883e:ce9f:ccd3:e11d:bb25:32b3', '2026-10-27 10:44:42'),
(1981, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:ccd3:e11d:bb25:32b3', '2026-10-27 10:47:10'),
(1982, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:ccd3:e11d:bb25:32b3', '2026-10-27 10:47:17'),
(1983, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\' on Task #85', '106.219.146.66', '2026-10-27 10:55:50'),
(1984, 6, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #85', '106.219.146.66', '2026-10-27 10:55:50'),
(1985, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:e5b4:40e:d7b2:8060', '2026-10-27 11:47:55'),
(1986, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #74', '2401:4900:883e:ce9f:e5b4:40e:d7b2:8060', '2026-10-27 11:48:32'),
(1987, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #74', '2401:4900:883e:ce9f:e5b4:40e:d7b2:8060', '2026-10-27 11:48:32'),
(1988, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-27 12:15:51'),
(1989, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #62', '106.219.146.66', '2026-10-27 12:17:03'),
(1990, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #62', '106.219.146.66', '2026-10-27 12:17:03'),
(1991, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #62 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-27 12:18:47'),
(1992, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #49', '106.219.146.66', '2026-10-27 12:19:24'),
(1993, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #49', '106.219.146.66', '2026-10-27 12:19:24'),
(1994, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #49 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-27 12:21:17'),
(1995, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #74', '2401:4900:883e:ce9f:e5b4:40e:d7b2:8060', '2026-10-27 13:06:08');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(1996, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #74', '2401:4900:883e:ce9f:e5b4:40e:d7b2:8060', '2026-10-27 13:06:08'),
(1997, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883e:ce9f:3dd7:cc28:f17:19e4', '2026-10-27 16:28:50'),
(1998, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:dd35:48f7:fe03:a7ec', '2026-10-27 16:30:45'),
(1999, 1, NULL, 'Approve Leave', NULL, NULL, NULL, 'Leaves', 'Manager approved leave request ID 1', '2401:4900:883e:ce9f:3dd7:cc28:f17:19e4', '2026-10-27 16:39:32'),
(2000, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-27 18:25:13'),
(2001, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 27/10/2026 06:25:51 PM (IST). Status: half_day, Duration: 08h 41m 57s, Early Exit: 4m', '106.219.146.66', '2026-10-27 18:25:51'),
(2002, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-27 18:28:00'),
(2003, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:30cb:8000::', '2026-10-27 18:28:14'),
(2004, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3015:146b:d494:2eff:febf:18e7', '2026-10-27 18:30:36'),
(2005, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 27/10/2026 06:33:50 PM (IST). Status: half_day, Duration: 08h 41m 27s, Early Exit: 0m', '2409:4085:ae90:3afd::95c9:520c', '2026-10-27 18:33:50'),
(2006, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:96ff:17f8:e097:43d2', '2026-10-28 09:15:08'),
(2007, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-27, ID: 288) as full_day', '2405:201:6818:f058:96ff:17f8:e097:43d2', '2026-10-28 09:15:10'),
(2008, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305d:de9d:8000::', '2026-10-28 09:24:44'),
(2009, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-27, ID: 290) as full_day', '2409:40d2:305d:de9d:8000::', '2026-10-28 09:24:46'),
(2010, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:3003:f41c:b0de:6eff:fec1:fa84', '2026-10-28 09:34:36'),
(2011, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/10/2026 09:34:36 AM (IST). Late minutes: 0', '2409:40d2:3003:f41c:b0de:6eff:fec1:fa84', '2026-10-28 09:34:36'),
(2012, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:a5f5:7226:cc88:d90a:1dc1:6930', '2026-10-28 09:36:16'),
(2013, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:a5f5:7226:cc88:d90a:1dc1:6930', '2026-10-28 09:36:39'),
(2014, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/10/2026 09:36:38 AM (IST). Late minutes: 0', '2401:4900:a5f5:7226:cc88:d90a:1dc1:6930', '2026-10-28 09:36:39'),
(2015, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 09:39:29'),
(2016, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-27, ID: 293) as full_day', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 09:39:30'),
(2017, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 09:41:06'),
(2018, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/10/2026 09:41:06 AM (IST). Late minutes: 1', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 09:41:06'),
(2019, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:305d:de9d:8000::', '2026-10-28 09:43:29'),
(2020, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/10/2026 09:43:29 AM (IST). Late minutes: 3', '2409:40d2:305d:de9d:8000::', '2026-10-28 09:43:29'),
(2021, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:dd35:48f7:fe03:a7ec', '2026-10-28 09:51:06'),
(2022, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305a:e055:8449:53ff:fe67:b1cf', '2026-10-28 09:51:24'),
(2023, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-28 09:51:25'),
(2024, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-27, ID: 294) as full_day', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-28 09:51:26'),
(2025, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:305a:e055:8449:53ff:fe67:b1cf', '2026-10-28 09:51:30'),
(2026, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/10/2026 09:51:30 AM (IST). Late minutes: 11', '2409:40d2:305a:e055:8449:53ff:fe67:b1cf', '2026-10-28 09:51:30'),
(2027, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-28 09:51:30'),
(2028, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 28/10/2026 09:51:30 AM (IST). Late minutes: 11', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-28 09:51:30'),
(2029, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305d:de9d:8000::', '2026-10-28 09:54:09'),
(2030, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:dd35:48f7:fe03:a7ec', '2026-10-28 10:03:36'),
(2031, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-27, ID: 292) as full_day', '2401:4900:883e:ce9f:dd35:48f7:fe03:a7ec', '2026-10-28 10:03:38'),
(2032, 10, NULL, 'Request Leave', NULL, NULL, NULL, 'Leaves', 'User Aman requested 1 days leave (2026-10-28 to 2026-10-28)', '2401:4900:883e:ce9f:dd35:48f7:fe03:a7ec', '2026-10-28 10:04:49'),
(2033, 10, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #74', '2401:4900:883e:ce9f:dd35:48f7:fe03:a7ec', '2026-10-28 10:05:07'),
(2034, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-27, ID: 295) as half_day', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-28 10:07:56'),
(2035, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-28 10:08:13'),
(2036, 6, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #85', '106.219.146.66', '2026-10-28 10:08:21'),
(2037, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 10:08:23'),
(2038, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:503b:b4fd:ddef:1f51', '2026-10-28 10:14:46'),
(2039, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 12:30:23'),
(2040, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-28 15:17:41'),
(2041, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-10-28 15:19:19'),
(2042, 5, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Uvaish approved completed task #65 (\'Make 100 backlinks for jeeven sankalp\'). Status changed to Done.', '49.43.160.210', '2026-10-28 15:20:03'),
(2043, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Turmeric_Victoria_November_2026_SEO_Plan.pdf', '49.43.160.210', '2026-10-28 17:12:21'),
(2044, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Blog_Content_SOP.pdf', '49.43.160.210', '2026-10-28 17:12:54'),
(2045, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:503b:b4fd:ddef:1f51', '2026-10-28 18:18:32'),
(2046, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:dd35:48f7:fe03:a7ec', '2026-10-28 18:29:14'),
(2047, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 28/10/2026 06:29:36 PM (IST). Status: half_day, Duration: 08h 38m 06s, Early Exit: 0m', '106.219.146.66', '2026-10-28 18:29:36'),
(2048, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 18:30:29'),
(2049, 8, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 28/10/2026 06:35:24 PM (IST). Status: half_day, Duration: 08h 43m 54s, Early Exit: 0m', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 18:35:24'),
(2050, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 18:35:45'),
(2051, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 18:35:54'),
(2052, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 28/10/2026 06:36:22 PM (IST). Status: full_day, Duration: 08h 55m 16s, Early Exit: 0m', '2409:40d2:3000:10df:c0b0:37ff:fe4c:b9f1', '2026-10-28 18:36:22'),
(2053, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:8098:c45e:5ac7:ff50', '2026-10-29 09:07:21'),
(2054, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-28, ID: 296) as full_day', '2405:201:6818:f058:8098:c45e:5ac7:ff50', '2026-10-29 09:07:23'),
(2055, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:7cd8:fde0:6a2a:3419', '2026-10-29 09:34:57'),
(2056, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-28, ID: 297) as full_day', '2401:4900:883e:ce9f:7cd8:fde0:6a2a:3419', '2026-10-29 09:34:59'),
(2057, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883e:ce9f:7cd8:fde0:6a2a:3419', '2026-10-29 09:35:23'),
(2058, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/10/2026 09:35:23 AM (IST). Late minutes: 0', '2401:4900:883e:ce9f:7cd8:fde0:6a2a:3419', '2026-10-29 09:35:23'),
(2059, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6818:f058:8098:c45e:5ac7:ff50', '2026-10-29 09:35:43'),
(2060, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/10/2026 09:35:43 AM (IST). Late minutes: 0', '2405:201:6818:f058:8098:c45e:5ac7:ff50', '2026-10-29 09:35:43'),
(2061, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3050:7572:14b2:faff:fecb:550d', '2026-10-29 09:40:29'),
(2062, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2409:40d2:3050:7572:14b2:faff:fecb:550d', '2026-10-29 09:40:35'),
(2063, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/10/2026 09:40:35 AM (IST). Late minutes: 0', '2409:40d2:3050:7572:14b2:faff:fecb:550d', '2026-10-29 09:40:35'),
(2064, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:3050:7572:14b2:faff:fecb:550d', '2026-10-29 09:40:47'),
(2065, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3050:7572:14b2:faff:fecb:550d', '2026-10-29 09:41:02'),
(2066, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3050:7572:14b2:faff:fecb:550d', '2026-10-29 09:52:40'),
(2067, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/10/2026 09:52:40 AM (IST). Late minutes: 12', '2409:40d2:3050:7572:14b2:faff:fecb:550d', '2026-10-29 09:52:40'),
(2068, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3048:36bb:ec06:82ff:fe84:cbd8', '2026-10-29 09:52:46'),
(2069, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3048:36bb:ec06:82ff:fe84:cbd8', '2026-10-29 09:52:51'),
(2070, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/10/2026 09:52:51 AM (IST). Late minutes: 12', '2409:40d2:3048:36bb:ec06:82ff:fe84:cbd8', '2026-10-29 09:52:51'),
(2071, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 09:55:03'),
(2072, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-28, ID: 299) as full_day', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 09:55:06'),
(2073, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-29 09:58:32'),
(2074, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-10-29 09:58:39'),
(2075, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/10/2026 09:58:39 AM (IST). Late minutes: 18', '2409:4085:ae90:3afd::95c9:520c', '2026-10-29 09:58:39'),
(2076, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:f1da:f5e2:3148:1e6d', '2026-10-29 09:58:48'),
(2077, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:ce9f:f1da:f5e2:3148:1e6d', '2026-10-29 09:58:54'),
(2078, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/10/2026 09:58:54 AM (IST). Late minutes: 18', '2401:4900:883e:ce9f:f1da:f5e2:3148:1e6d', '2026-10-29 09:58:54'),
(2079, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:4402:b1ee:100c:5277', '2026-10-29 10:03:58'),
(2080, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-29 10:22:43'),
(2081, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-29 10:42:47'),
(2082, 10, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Aman completed task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') and submitted for manager & product manager approval.', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-29 10:43:06'),
(2083, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-10-29 12:21:00'),
(2084, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-29 12:21:09'),
(2085, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Capture.PNG', '49.43.160.210', '2026-10-29 12:21:47'),
(2086, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Capture.PNG', '106.219.146.66', '2026-10-29 12:21:54'),
(2087, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 12:23:45'),
(2088, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 12:24:05'),
(2089, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 12:24:26'),
(2090, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 29/10/2026 12:24:26 PM (IST). Late minutes: 164', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 12:24:26'),
(2091, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 12:26:06'),
(2092, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 12:32:49'),
(2093, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:89f5:a4e0:faf5:9214', '2026-10-29 13:28:45'),
(2094, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-29 16:23:43'),
(2095, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Deleted Backlinks.xlsx', '106.219.146.66', '2026-10-29 16:23:55'),
(2096, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Deleted Backlinks.xlsx', '106.219.146.66', '2026-10-29 16:24:06'),
(2097, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Deleted Backlinks.xlsx', '106.219.146.66', '2026-10-29 17:48:20'),
(2098, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Capture.PNG', '106.219.146.66', '2026-10-29 17:48:22'),
(2099, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Turmeric_Victoria_November_2026_SEO_Plan.pdf', '106.219.146.66', '2026-10-29 17:48:25'),
(2100, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:8cfd:733c:f61d:6b1b', '2026-10-29 18:08:14'),
(2101, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-29 18:23:50'),
(2102, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 29/10/2026 06:28:14 PM (IST). Status: half_day, Duration: 08h 35m 23s, Early Exit: 1m', '106.219.146.66', '2026-10-29 18:28:14'),
(2103, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-29 18:35:16'),
(2104, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 29/10/2026 06:35:40 PM (IST). Status: half_day, Duration: 08h 37m 01s, Early Exit: 0m', '2409:4085:ae90:3afd::95c9:520c', '2026-10-29 18:35:40'),
(2105, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:bf97:8e50:3c57:3144:5189:38d1', '2026-10-30 08:48:53'),
(2106, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-29, ID: 302) as full_day', '2401:4900:bf97:8e50:3c57:3144:5189:38d1', '2026-10-30 08:48:55'),
(2107, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:c9dd:9019:1116:4405', '2026-10-30 09:00:42'),
(2108, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-29, ID: 303) as full_day', '2405:201:6818:f058:c9dd:9019:1116:4405', '2026-10-30 09:00:44'),
(2109, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305b:a0a8:8000::', '2026-10-30 09:20:42'),
(2110, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:bf97:8e50:498b:609a:6856:775e', '2026-10-30 09:26:20'),
(2111, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '223.188.131.237', '2026-10-30 09:32:04'),
(2112, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 09:32:04 AM (IST). Late minutes: 0', '223.188.131.237', '2026-10-30 09:32:04'),
(2113, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3003:f41c:b0de:6eff:fec1:fa84', '2026-10-30 09:36:18'),
(2114, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:3003:f41c:b0de:6eff:fec1:fa84', '2026-10-30 09:36:40'),
(2115, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 09:36:40 AM (IST). Late minutes: 0', '2409:40d2:3003:f41c:b0de:6eff:fec1:fa84', '2026-10-30 09:36:40'),
(2116, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:305b:a0a8:8000::', '2026-10-30 09:45:18'),
(2117, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 09:45:18 AM (IST). Late minutes: 5', '2409:40d2:305b:a0a8:8000::', '2026-10-30 09:45:18'),
(2118, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:88d3:c498:b43f:4561', '2026-10-30 09:45:37'),
(2119, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-29, ID: 305) as full_day', '2405:201:6804:bcd8:88d3:c498:b43f:4561', '2026-10-30 09:45:39'),
(2120, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2405:201:6804:bcd8:88d3:c498:b43f:4561', '2026-10-30 09:45:42'),
(2121, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 09:45:42 AM (IST). Late minutes: 5', '2405:201:6804:bcd8:88d3:c498:b43f:4561', '2026-10-30 09:45:42'),
(2122, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:88d3:c498:b43f:4561', '2026-10-30 09:45:57'),
(2123, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-29, ID: 304) as full_day', '106.219.146.66', '2026-10-30 09:47:12'),
(2124, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-29, ID: 308) as full_day', '106.219.146.66', '2026-10-30 09:47:12'),
(2125, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-29, ID: 309) as half_day', '106.219.146.66', '2026-10-30 09:47:12'),
(2126, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:88d3:c498:b43f:4561', '2026-10-30 09:47:14'),
(2127, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-30 09:51:14'),
(2128, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-10-30 09:51:32'),
(2129, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 09:51:32 AM (IST). Late minutes: 11', '2409:4085:ae90:3afd::95c9:520c', '2026-10-30 09:51:32'),
(2130, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3056:628a:d4cb:41ff:fe3c:51bb', '2026-10-30 09:53:28'),
(2131, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '2409:40d2:3056:628a:d4cb:41ff:fe3c:51bb', '2026-10-30 09:53:36'),
(2132, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 09:53:36 AM (IST). Late minutes: 13', '2409:40d2:3056:628a:d4cb:41ff:fe3c:51bb', '2026-10-30 09:53:36'),
(2133, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:8cfd:733c:f61d:6b1b', '2026-10-30 10:00:27'),
(2134, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:ce9f:8cfd:733c:f61d:6b1b', '2026-10-30 10:00:31'),
(2135, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 10:00:31 AM (IST). Late minutes: 20', '2401:4900:883e:ce9f:8cfd:733c:f61d:6b1b', '2026-10-30 10:00:31'),
(2136, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:86e9:bca5:fbc5:da21', '2026-10-30 10:10:06'),
(2137, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:86e9:bca5:fbc5:da21', '2026-10-30 10:10:12'),
(2138, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:ce9f:86e9:bca5:fbc5:da21', '2026-10-30 10:10:20'),
(2139, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 10:10:20 AM (IST). Late minutes: 30', '2401:4900:883e:ce9f:86e9:bca5:fbc5:da21', '2026-10-30 10:10:20'),
(2140, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:86e9:bca5:fbc5:da21', '2026-10-30 10:10:28'),
(2141, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:86e9:bca5:fbc5:da21', '2026-10-30 10:10:39'),
(2142, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:86e9:bca5:fbc5:da21', '2026-10-30 10:10:48'),
(2143, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:86e9:bca5:fbc5:da21', '2026-10-30 10:10:54'),
(2144, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:a038:fc83:8e8:9a11', '2026-10-30 10:13:15'),
(2145, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:a038:fc83:8e8:9a11', '2026-10-30 10:17:47'),
(2146, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-30 10:24:33'),
(2147, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:9cf9:ce0c:70eb:7a7c', '2026-10-30 10:37:11'),
(2148, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '106.219.146.66', '2026-10-30 10:41:43'),
(2149, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-30 10:41:50'),
(2150, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883e:ce9f:48fd:1113:b178:9a9f', '2026-10-30 10:42:06'),
(2151, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883e:ce9f:48fd:1113:b178:9a9f', '2026-10-30 10:42:09'),
(2152, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 30/10/2026 10:42:09 AM (IST). Late minutes: 62', '2401:4900:883e:ce9f:48fd:1113:b178:9a9f', '2026-10-30 10:42:09'),
(2153, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:48fd:1113:b178:9a9f', '2026-10-30 10:42:14'),
(2154, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-30 10:42:51'),
(2155, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:a038:fc83:8e8:9a11', '2026-10-30 11:37:13'),
(2156, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 11:52:31'),
(2157, 10, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Aman completed task #75 (\'Frontier Jewellers blogs on Uttarakhand wedding\') and submitted for manager & product manager approval.', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 11:53:52'),
(2158, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #76', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 11:54:12'),
(2159, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #76', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 11:54:12'),
(2160, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:8cfd:733c:f61d:6b1b', '2026-10-30 12:27:46'),
(2161, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #90', '106.219.146.66', '2026-10-30 12:36:42'),
(2162, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #90', '106.219.146.66', '2026-10-30 12:36:42'),
(2163, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #90 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-30 12:36:54'),
(2164, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\' on Task #52', '106.219.146.66', '2026-10-30 12:56:35'),
(2165, 5, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #52', '106.219.146.66', '2026-10-30 12:56:35'),
(2166, 5, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Task #52 completed & marked Done by Uvaish.', '106.219.146.66', '2026-10-30 12:56:39'),
(2167, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Turmeric_Victoria_November_2026_SEO_Plan.pdf', '106.219.146.66', '2026-10-30 12:58:19'),
(2168, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Turmeric_Victoria_November_2026_SEO_Plan.pdf', '106.219.146.66', '2026-10-30 12:58:24'),
(2169, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 13:04:39'),
(2170, 10, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #76 & submitted for manager approval.', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 13:05:24'),
(2171, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 15:59:18'),
(2172, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #77', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 17:14:13'),
(2173, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #77', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-30 17:14:13'),
(2174, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-30 17:56:10'),
(2175, 6, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-30 18:29:35'),
(2176, 6, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 30/10/2026 06:30:17 PM (IST). Status: half_day, Duration: 08h 36m 41s, Early Exit: 0m', '106.219.146.66', '2026-10-30 18:30:17'),
(2177, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:305b:a0a8:8000::', '2026-10-30 18:32:36'),
(2178, 7, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 30/10/2026 06:32:44 PM (IST). Status: half_day, Duration: 08h 47m 26s, Early Exit: 0m', '2409:40d2:305b:a0a8:8000::', '2026-10-30 18:32:44'),
(2179, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-10-30 18:38:24'),
(2180, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 30/10/2026 06:38:49 PM (IST). Status: half_day, Duration: 08h 47m 17s, Early Exit: 0m', '2409:4085:ae90:3afd::95c9:520c', '2026-10-30 18:38:49'),
(2181, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6803:d181:99f7:1943:dbb3:d855', '2026-10-30 20:57:05'),
(2182, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-30, ID: 317) as half_day', '2405:201:6803:d181:99f7:1943:dbb3:d855', '2026-10-30 20:57:07'),
(2183, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-30, ID: 310) as full_day', '2405:201:6803:d181:99f7:1943:dbb3:d855', '2026-10-30 20:57:12'),
(2184, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-30, ID: 311) as full_day', '2405:201:6803:d181:99f7:1943:dbb3:d855', '2026-10-30 20:57:12'),
(2185, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-30, ID: 313) as full_day', '2405:201:6803:d181:99f7:1943:dbb3:d855', '2026-10-30 20:57:12'),
(2186, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-30, ID: 316) as full_day', '2405:201:6803:d181:99f7:1943:dbb3:d855', '2026-10-30 20:57:12'),
(2187, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-30, ID: 318) as half_day', '2405:201:6803:d181:99f7:1943:dbb3:d855', '2026-10-30 20:57:12'),
(2188, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883e:ce9f:f994:63f8:1d70:458b', '2026-10-31 12:28:38'),
(2189, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:28:38 PM (IST). Late minutes: 168', '2401:4900:883e:ce9f:f994:63f8:1d70:458b', '2026-10-31 12:28:38'),
(2190, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883e:ce9f:dcc4:4a10:1d3b:5ae9', '2026-10-31 12:28:53'),
(2191, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:28:53 PM (IST). Late minutes: 168', '2401:4900:883e:ce9f:dcc4:4a10:1d3b:5ae9', '2026-10-31 12:28:53'),
(2192, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '106.219.146.66', '2026-10-31 12:29:09'),
(2193, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-31 12:29:14'),
(2194, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-31 12:29:24'),
(2195, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2405:201:6804:bcd8:d8ff:2f88:b2f5:510e', '2026-10-31 12:29:26'),
(2196, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:29:26 PM (IST). Late minutes: 169', '2405:201:6804:bcd8:d8ff:2f88:b2f5:510e', '2026-10-31 12:29:26'),
(2197, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-31 12:29:26'),
(2198, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:29:26 PM (IST). Late minutes: 169', '2401:4900:883e:ce9f:48fe:ec7f:6362:5470', '2026-10-31 12:29:26'),
(2199, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-31 12:29:30'),
(2200, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:3c1d:d537:db52:38da', '2026-10-31 12:29:44'),
(2201, 6, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #6 started activity \'Working on Task\'', '106.219.146.66', '2026-10-31 12:30:16'),
(2202, 6, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:30:16 PM (IST). Late minutes: 170', '106.219.146.66', '2026-10-31 12:30:16'),
(2203, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883e:ce9f:3c1d:d537:db52:38da', '2026-10-31 12:30:34'),
(2204, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:30:34 PM (IST). Late minutes: 170', '2401:4900:883e:ce9f:3c1d:d537:db52:38da', '2026-10-31 12:30:34'),
(2205, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-31 12:32:06'),
(2206, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:32:06 PM (IST). Late minutes: 172', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-10-31 12:32:06'),
(2207, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2401:4900:883e:ce9f:5c5:77b3:c32d:34f2', '2026-10-31 12:32:26'),
(2208, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:32:26 PM (IST). Late minutes: 172', '2401:4900:883e:ce9f:5c5:77b3:c32d:34f2', '2026-10-31 12:32:26'),
(2209, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-31 12:38:50'),
(2210, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '106.219.146.66', '2026-10-31 12:39:35'),
(2211, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 12:39:35 PM (IST). Late minutes: 179', '106.219.146.66', '2026-10-31 12:39:35'),
(2212, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883e:ce9f:f994:63f8:1d70:458b', '2026-10-31 13:39:49'),
(2213, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883e:ce9f:f994:63f8:1d70:458b', '2026-10-31 13:39:53'),
(2214, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 31/10/2026 01:39:53 PM (IST). Late minutes: 239', '2401:4900:883e:ce9f:f994:63f8:1d70:458b', '2026-10-31 13:39:53'),
(2215, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:f994:63f8:1d70:458b', '2026-10-31 13:40:11'),
(2216, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:dcc4:4a10:1d3b:5ae9', '2026-10-31 13:56:19'),
(2217, 9, NULL, 'Request Leave', NULL, NULL, NULL, 'Leaves', 'User Abhay requested 1 days leave (2026-10-31 to 2026-10-31)', '2401:4900:883e:ce9f:dcc4:4a10:1d3b:5ae9', '2026-10-31 13:58:49'),
(2218, 9, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 31/10/2026 02:10:08 PM (IST). Status: half_day, Duration: 04h 49m 15s, Early Exit: 259m', '2401:4900:883e:ce9f:dcc4:4a10:1d3b:5ae9', '2026-10-31 14:10:08'),
(2219, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:f994:63f8:1d70:458b', '2026-10-31 16:26:56'),
(2220, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Turmeric_Victoria_November_2026_SEO_Plan.pdf', '106.219.146.66', '2026-10-31 17:57:12'),
(2221, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Manor_House_Homestays_SEO_Report_October_2026.pdf', '106.219.146.66', '2026-10-31 17:57:35'),
(2222, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Kashmiri_Sons_SEO_Report_October_2026.pdf', '106.219.146.66', '2026-10-31 17:57:43'),
(2223, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Frontier_Jewellers_SEO_Report_October_2026.pdf', '106.219.146.66', '2026-10-31 17:57:54'),
(2224, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Aristella_SEO_Report_October_2026 (1).pdf', '106.219.146.66', '2026-10-31 17:58:04'),
(2225, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Turmeric_Victoria_SEO_Report_Aug-Oct_2026.pdf', '106.219.146.66', '2026-10-31 17:58:15'),
(2226, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Jeevan_Sankalp_SEO_Report_October_2026.pdf', '106.219.146.66', '2026-10-31 17:58:31'),
(2227, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.146.66', '2026-10-31 18:00:16'),
(2228, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Black_Apple_Cellular_SEO_Report_October_2026.pdf', '106.219.146.66', '2026-10-31 18:01:01'),
(2229, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-10-31 18:25:51'),
(2230, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Black_Apple_Cellular_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-10-31 18:26:14'),
(2231, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Jeevan_Sankalp_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-10-31 18:26:16'),
(2232, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Turmeric_Victoria_SEO_Report_Aug-Oct_2026.pdf', '49.43.160.210', '2026-10-31 18:26:22'),
(2233, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Aristella_SEO_Report_October_2026 (1).pdf', '49.43.160.210', '2026-10-31 18:26:23'),
(2234, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Frontier_Jewellers_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-10-31 18:26:27'),
(2235, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Kashmiri_Sons_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-10-31 18:26:30'),
(2236, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Manor_House_Homestays_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-10-31 18:26:38'),
(2237, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:d8ff:2f88:b2f5:510e', '2026-10-31 18:27:27'),
(2238, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:ce9f:f994:63f8:1d70:458b', '2026-10-31 18:29:12'),
(2239, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 31/10/2026 06:31:54 PM (IST). Status: full_day, Duration: 08h 51m 28s, Early Exit: 0m', '2405:201:6804:bcd8:d8ff:2f88:b2f5:510e', '2026-10-31 18:31:54'),
(2240, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:31'),
(2241, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-31, ID: 319) as full_day', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:32'),
(2242, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-31, ID: 322) as full_day', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:36'),
(2243, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-31, ID: 323) as full_day', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:36'),
(2244, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-31, ID: 324) as full_day', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:36'),
(2245, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-31, ID: 325) as full_day', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:36'),
(2246, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-31, ID: 326) as half_day', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:36'),
(2247, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-31, ID: 327) as full_day', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:36'),
(2248, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-10-31, ID: 328) as half_day', '2401:4900:bf97:8e50:b0c2:e1ef:3fec:cb63', '2026-10-31 21:34:36'),
(2249, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3042:5983:395b:1be6:8fc6:8c3c', '2026-11-01 16:34:57'),
(2250, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:b923:13a8:326e:7090', '2026-11-02 09:03:18'),
(2251, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:a63d:a6de:55f5:5dbf:7452:d8f5', '2026-11-02 09:38:00'),
(2252, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:a63d:a6de:55f5:5dbf:7452:d8f5', '2026-11-02 09:38:16'),
(2253, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/11/2026 09:38:16 AM (IST). Late minutes: 0', '2401:4900:a63d:a6de:55f5:5dbf:7452:d8f5', '2026-11-02 09:38:16'),
(2254, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-02 09:39:29'),
(2255, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6803:d181:dca2:58dd:a6ea:41da', '2026-11-02 09:39:46'),
(2256, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-02 09:39:48'),
(2257, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/11/2026 09:39:48 AM (IST). Late minutes: 0', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-02 09:39:48'),
(2258, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3043:e0b3:846e:84ff:fe85:29b2', '2026-11-02 09:40:49'),
(2259, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3043:e0b3:846e:84ff:fe85:29b2', '2026-11-02 09:40:53'),
(2260, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/11/2026 09:40:53 AM (IST). Late minutes: 0', '2409:40d2:3043:e0b3:846e:84ff:fe85:29b2', '2026-11-02 09:40:53'),
(2261, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:3043:e0b3:846e:84ff:fe85:29b2', '2026-11-02 09:40:58'),
(2262, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3043:e0b3:846e:84ff:fe85:29b2', '2026-11-02 09:41:06'),
(2263, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2409:40d2:3043:e0b3:846e:84ff:fe85:29b2', '2026-11-02 09:41:12'),
(2264, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/11/2026 09:41:12 AM (IST). Late minutes: 1', '2409:40d2:3043:e0b3:846e:84ff:fe85:29b2', '2026-11-02 09:41:12'),
(2265, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:b14b:43e2:c676:3e4f', '2026-11-02 09:48:45'),
(2266, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:ce9f:b14b:43e2:c676:3e4f', '2026-11-02 09:48:52'),
(2267, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/11/2026 09:48:52 AM (IST). Late minutes: 8', '2401:4900:883e:ce9f:b14b:43e2:c676:3e4f', '2026-11-02 09:48:52'),
(2268, 10, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #77', '2401:4900:883e:ce9f:b14b:43e2:c676:3e4f', '2026-11-02 09:49:23'),
(2269, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-11-02 09:54:31'),
(2270, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-11-02 09:54:44'),
(2271, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/11/2026 09:54:44 AM (IST). Late minutes: 14', '2409:4085:ae90:3afd::95c9:520c', '2026-11-02 09:54:44'),
(2272, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:4c17:2488:ccb6:1e50', '2026-11-02 10:05:15'),
(2273, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:301e:2879:8000::', '2026-11-02 11:58:24'),
(2274, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:301e:2879:8000::', '2026-11-02 11:58:30'),
(2275, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 02/11/2026 11:58:30 AM (IST). Late minutes: 138', '2409:40d2:301e:2879:8000::', '2026-11-02 11:58:30'),
(2276, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-11-02 14:09:41'),
(2277, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:cefc:5bd:8758:2893', '2026-11-03 09:20:05'),
(2278, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-02, ID: 330) as full_day', '2405:201:6818:f058:cefc:5bd:8758:2893', '2026-11-03 09:20:07'),
(2279, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2405:201:6818:f058:cefc:5bd:8758:2893', '2026-11-03 09:34:21'),
(2280, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/11/2026 09:34:21 AM (IST). Late minutes: 0', '2405:201:6818:f058:cefc:5bd:8758:2893', '2026-11-03 09:34:21'),
(2281, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:36:50'),
(2282, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-02, ID: 331) as full_day', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:36:51'),
(2283, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:36:57'),
(2284, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/11/2026 09:36:57 AM (IST). Late minutes: 0', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:36:57'),
(2285, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-02, ID: 329) as full_day', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:37:04');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(2286, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-02, ID: 332) as full_day', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:37:04'),
(2287, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-02, ID: 333) as full_day', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:37:04'),
(2288, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-02, ID: 334) as full_day', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:37:04'),
(2289, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-02, ID: 335) as half_day', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:37:04'),
(2290, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-11-03 09:39:55'),
(2291, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-11-03 09:40:03'),
(2292, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/11/2026 09:40:03 AM (IST). Late minutes: 0', '2401:4900:883e:ce9f:1e46:b9d9:7a19:83ea', '2026-11-03 09:40:03'),
(2293, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-11-03 09:40:25'),
(2294, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-11-03 09:40:36'),
(2295, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/11/2026 09:40:36 AM (IST). Late minutes: 0', '2409:4085:ae90:3afd::95c9:520c', '2026-11-03 09:40:36'),
(2296, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304c:cb9f:8000::', '2026-11-03 09:41:18'),
(2297, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:304c:cb9f:8000::', '2026-11-03 09:41:38'),
(2298, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/11/2026 09:41:38 AM (IST). Late minutes: 1', '2409:40d2:304c:cb9f:8000::', '2026-11-03 09:41:38'),
(2299, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:44:40'),
(2300, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:44:44'),
(2301, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 03/11/2026 09:44:44 AM (IST). Late minutes: 4', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 09:44:44'),
(2302, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:9875:8683:7b1f:bcdd', '2026-11-03 09:49:27'),
(2303, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-11-03 11:28:38'),
(2304, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Black_Apple_Cellular_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-11-03 11:28:46'),
(2305, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Jeevan_Sankalp_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-11-03 11:29:36'),
(2306, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Turmeric_Victoria_SEO_Report_Aug-Oct_2026.pdf', '49.43.160.210', '2026-11-03 11:29:39'),
(2307, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Aristella_SEO_Report_October_2026 (1).pdf', '49.43.160.210', '2026-11-03 11:29:41'),
(2308, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Frontier_Jewellers_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-11-03 11:29:44'),
(2309, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Kashmiri_Sons_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-11-03 11:29:48'),
(2310, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Manor_House_Homestays_SEO_Report_October_2026.pdf', '49.43.160.210', '2026-11-03 11:29:50'),
(2311, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:a9b2:1bd:5666:eed4', '2026-11-03 11:31:33'),
(2312, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: public_html.zip', '49.43.160.210', '2026-11-03 11:33:40'),
(2313, 4, NULL, 'Create Task', NULL, NULL, NULL, 'Tasks', 'Task #93 (\'Uttarakhandstays\') created by Rahul and assigned to User #9', '2401:4900:883e:ce9f:a9b2:1bd:5666:eed4', '2026-11-03 11:34:21'),
(2314, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\' on Task #93', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 11:58:41'),
(2315, 9, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #93', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 11:58:41'),
(2316, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:7016:efe0:689:9135', '2026-11-03 12:53:37'),
(2317, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #77', '2401:4900:883e:ce9f:7016:efe0:689:9135', '2026-11-03 13:30:31'),
(2318, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #77', '2401:4900:883e:ce9f:7016:efe0:689:9135', '2026-11-03 13:30:31'),
(2319, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\' on Task #77', '2401:4900:883e:ce9f:7016:efe0:689:9135', '2026-11-03 13:30:31'),
(2320, 10, NULL, 'Start Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee started live timer for task #77', '2401:4900:883e:ce9f:7016:efe0:689:9135', '2026-11-03 13:30:31'),
(2321, 10, NULL, 'Stop Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee completed task #77 & submitted for manager approval.', '2401:4900:883e:ce9f:7016:efe0:689:9135', '2026-11-03 13:32:41'),
(2322, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:641e:3d9e:6f63:503f', '2026-11-03 14:03:32'),
(2323, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304c:cb9f:8000::', '2026-11-03 18:33:25'),
(2324, 7, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 03/11/2026 06:33:31 PM (IST). Status: full_day, Duration: 08h 51m 53s, Early Exit: 0m', '2409:40d2:304c:cb9f:8000::', '2026-11-03 18:33:31'),
(2325, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 18:33:48'),
(2326, 9, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #93', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 18:33:59'),
(2327, 9, NULL, 'Pause Timer', NULL, NULL, NULL, 'TimeTracker', 'Assigned employee paused timer for task #93', '2401:4900:883e:ce9f:5096:9945:4dc5:5bd9', '2026-11-03 18:33:59'),
(2328, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:ce9f:6564:bce7:924e:586', '2026-11-03 18:38:24'),
(2329, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 03/11/2026 06:38:42 PM (IST). Status: full_day, Duration: 08h 58m 06s, Early Exit: 0m', '2401:4900:883e:ce9f:6564:bce7:924e:586', '2026-11-03 18:38:42'),
(2330, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:24:38'),
(2331, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:24:45'),
(2332, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/11/2026 09:24:45 AM (IST). Late minutes: 0', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:24:45'),
(2333, 7, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 04/11/2026 09:24:51 AM (IST). Status: half_day, Duration: 00h 00m 06s, Early Exit: 545m', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:24:51'),
(2334, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:25:05'),
(2335, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/11/2026 09:25:05 AM (IST). Late minutes: 0', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:25:05'),
(2336, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-03, ID: 336) as full_day', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:25:13'),
(2337, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-03, ID: 337) as full_day', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:25:13'),
(2338, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-03, ID: 338) as full_day', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:25:13'),
(2339, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-03, ID: 341) as full_day', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:25:13'),
(2340, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-04 09:39:00'),
(2341, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-04 09:39:10'),
(2342, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/11/2026 09:39:10 AM (IST). Late minutes: 0', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-04 09:39:10'),
(2343, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-11-04 09:40:43'),
(2344, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-11-04 09:41:14'),
(2345, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/11/2026 09:41:14 AM (IST). Late minutes: 1', '2409:4085:ae90:3afd::95c9:520c', '2026-11-04 09:41:14'),
(2346, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3043:e0b3:c015:44ff:fef5:5738', '2026-11-04 09:42:14'),
(2347, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3043:e0b3:c015:44ff:fef5:5738', '2026-11-04 09:42:23'),
(2348, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/11/2026 09:42:23 AM (IST). Late minutes: 2', '2409:40d2:3043:e0b3:c015:44ff:fef5:5738', '2026-11-04 09:42:23'),
(2349, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:8cd1:e5a2:1f93:ec0a', '2026-11-04 09:53:28'),
(2350, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:4dbc:8000::', '2026-11-04 09:55:38'),
(2351, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-04 09:59:40'),
(2352, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:3043:e0b3:5809:ecff:fe11:abcb', '2026-11-04 10:00:45'),
(2353, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3043:e0b3:5809:ecff:fe11:abcb', '2026-11-04 10:00:55'),
(2354, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2409:40d2:3043:e0b3:5809:ecff:fe11:abcb', '2026-11-04 10:00:59'),
(2355, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/11/2026 10:00:59 AM (IST). Late minutes: 20', '2409:40d2:3043:e0b3:5809:ecff:fe11:abcb', '2026-11-04 10:00:59'),
(2356, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.144.177', '2026-11-04 10:08:58'),
(2357, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:dccf:9f95:362e:debc', '2026-11-04 10:31:09'),
(2358, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883d:3bd:dccf:9f95:362e:debc', '2026-11-04 10:31:23'),
(2359, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 04/11/2026 10:31:23 AM (IST). Late minutes: 51', '2401:4900:883d:3bd:dccf:9f95:362e:debc', '2026-11-04 10:31:23'),
(2360, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:94e3:81d1:b2a:9360', '2026-11-04 14:16:34'),
(2361, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:2173:ff3b:cf28:e50e', '2026-11-04 17:50:58'),
(2362, 9, NULL, 'Task Submitted for Approval', NULL, NULL, NULL, 'Tasks', 'Employee Abhay completed task #93 (\'Uttarakhandstays\') and submitted for manager & product manager approval.', '2401:4900:883d:3bd:2173:ff3b:cf28:e50e', '2026-11-04 17:52:08'),
(2363, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.144.177', '2026-11-04 18:02:29'),
(2364, 5, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: public_html.zip', '106.219.144.177', '2026-11-04 18:02:43'),
(2365, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Almost-Heaven.zip', '106.219.144.177', '2026-11-04 18:03:05'),
(2366, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:41f7:9d01:f3ec:b1b6', '2026-11-04 22:01:14'),
(2367, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-04, ID: 344) as full_day', '2405:201:6818:f058:41f7:9d01:f3ec:b1b6', '2026-11-04 22:01:16'),
(2368, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Almost-Heaven.zip', '2405:201:6818:f058:41f7:9d01:f3ec:b1b6', '2026-11-04 22:01:23'),
(2369, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:64c6:f69e:fcab:6259', '2026-11-05 09:08:15'),
(2370, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:4dbc:8000::', '2026-11-05 09:28:06'),
(2371, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-04, ID: 343) as full_day', '2409:40d2:3011:4dbc:8000::', '2026-11-05 09:28:08'),
(2372, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-05 09:34:23'),
(2373, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/11/2026 09:34:23 AM (IST). Late minutes: 0', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-05 09:34:23'),
(2374, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-05 09:34:56'),
(2375, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-05 09:35:18'),
(2376, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-04, ID: 347) as full_day', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-05 09:35:20'),
(2377, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-04, ID: 345) as full_day', '2409:40d2:3011:4dbc:8000::', '2026-11-05 09:35:58'),
(2378, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-04, ID: 346) as full_day', '2409:40d2:3011:4dbc:8000::', '2026-11-05 09:35:58'),
(2379, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-04, ID: 348) as half_day', '2409:40d2:3011:4dbc:8000::', '2026-11-05 09:35:58'),
(2380, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3011:4dbc:8000::', '2026-11-05 09:37:24'),
(2381, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/11/2026 09:37:24 AM (IST). Late minutes: 0', '2409:40d2:3011:4dbc:8000::', '2026-11-05 09:37:24'),
(2382, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:ec9d:2094:cfff:fe31:441d', '2026-11-05 09:40:26'),
(2383, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3014:ec9d:2094:cfff:fe31:441d', '2026-11-05 09:40:31'),
(2384, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/11/2026 09:40:31 AM (IST). Late minutes: 0', '2409:40d2:3014:ec9d:2094:cfff:fe31:441d', '2026-11-05 09:40:31'),
(2385, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:3014:ec9d:2094:cfff:fe31:441d', '2026-11-05 09:40:35'),
(2386, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:ec9d:2094:cfff:fe31:441d', '2026-11-05 09:40:41'),
(2387, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2409:40d2:3014:ec9d:2094:cfff:fe31:441d', '2026-11-05 09:40:45'),
(2388, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/11/2026 09:40:45 AM (IST). Late minutes: 0', '2409:40d2:3014:ec9d:2094:cfff:fe31:441d', '2026-11-05 09:40:45'),
(2389, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:dccf:9f95:362e:debc', '2026-11-05 09:55:04'),
(2390, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883d:3bd:dccf:9f95:362e:debc', '2026-11-05 09:55:10'),
(2391, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/11/2026 09:55:10 AM (IST). Late minutes: 15', '2401:4900:883d:3bd:dccf:9f95:362e:debc', '2026-11-05 09:55:10'),
(2392, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:301c:c991:b88c:1d0:61f9:63ba', '2026-11-05 10:02:18'),
(2393, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:2173:ff3b:cf28:e50e', '2026-11-05 10:03:07'),
(2394, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3011:4dbc:8000::', '2026-11-05 10:04:36'),
(2395, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:3afd::95c9:520c', '2026-11-05 10:05:06'),
(2396, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:3afd::95c9:520c', '2026-11-05 10:05:21'),
(2397, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 05/11/2026 10:05:21 AM (IST). Late minutes: 25', '2409:4085:ae90:3afd::95c9:520c', '2026-11-05 10:05:21'),
(2398, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3899:951:b5a1:46d3', '2026-11-05 10:11:08'),
(2399, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:e1c4:9ca0:569:491b', '2026-11-05 11:33:43'),
(2400, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:d164:de2:64a4:4df9', '2026-11-05 15:18:56'),
(2401, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:fdf9:d987:f96a:f453', '2026-11-05 16:26:44'),
(2402, 4, NULL, 'Task Approved', NULL, NULL, NULL, 'Tasks', 'Manager Rahul approved completed task #93 (\'Uttarakhandstays\'). Status changed to Done.', '2401:4900:883d:3bd:fdf9:d987:f96a:f453', '2026-11-05 16:26:58'),
(2403, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:2180:ad4a:8c5e:1ff4', '2026-11-05 16:54:02'),
(2404, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.144.177', '2026-11-05 18:16:36'),
(2405, 5, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Kashmiri_Sons_SEO_Local_Ranking_Report_Nov_2026.pdf', '106.219.144.177', '2026-11-05 18:17:05'),
(2406, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:d164:de2:64a4:4df9', '2026-11-05 18:29:39'),
(2407, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:35c::95c9:1302', '2026-11-05 18:36:55'),
(2408, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 05/11/2026 06:37:05 PM (IST). Status: half_day, Duration: 08h 31m 44s, Early Exit: 0m', '2409:4085:ae90:35c::95c9:1302', '2026-11-05 18:37:05'),
(2409, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:93b2:55b9:4931:d922', '2026-11-06 09:24:42'),
(2410, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-05, ID: 349) as full_day', '2405:201:6818:f058:93b2:55b9:4931:d922', '2026-11-06 09:24:43'),
(2411, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3058:f5:8000::', '2026-11-06 09:31:04'),
(2412, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-05, ID: 350) as full_day', '2409:40d2:3058:f5:8000::', '2026-11-06 09:31:05'),
(2413, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-06 09:37:06'),
(2414, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/11/2026 09:37:06 AM (IST). Late minutes: 0', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-06 09:37:06'),
(2415, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3058:f5:8000::', '2026-11-06 09:40:02'),
(2416, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/11/2026 09:40:02 AM (IST). Late minutes: 0', '2409:40d2:3058:f5:8000::', '2026-11-06 09:40:02'),
(2417, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:03'),
(2418, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-05, ID: 352) as full_day', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:05'),
(2419, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:09'),
(2420, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/11/2026 09:40:08 AM (IST). Late minutes: 0', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:09'),
(2421, 8, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:14'),
(2422, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:23'),
(2423, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-05, ID: 351) as full_day', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:24'),
(2424, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:27'),
(2425, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/11/2026 09:40:27 AM (IST). Late minutes: 0', '2409:40d2:3045:e3a2:5ca0:61ff:fe67:db76', '2026-11-06 09:40:27'),
(2426, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae90:35c::95c9:1302', '2026-11-06 09:52:21'),
(2427, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae90:35c::95c9:1302', '2026-11-06 09:52:37'),
(2428, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/11/2026 09:52:37 AM (IST). Late minutes: 12', '2409:4085:ae90:35c::95c9:1302', '2026-11-06 09:52:37'),
(2429, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-11-06 09:57:16'),
(2430, 12, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-05, ID: 353) as full_day', '49.43.160.210', '2026-11-06 09:57:21'),
(2431, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.144.177', '2026-11-06 09:58:21'),
(2432, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:686c:4bd3:f50b:b63d', '2026-11-06 10:00:55'),
(2433, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883d:3bd:686c:4bd3:f50b:b63d', '2026-11-06 10:01:06'),
(2434, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 06/11/2026 10:01:06 AM (IST). Late minutes: 21', '2401:4900:883d:3bd:686c:4bd3:f50b:b63d', '2026-11-06 10:01:06'),
(2435, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:d164:de2:64a4:4df9', '2026-11-06 10:24:34'),
(2436, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:88d9:f0e9:490c:78c2', '2026-11-06 17:04:15'),
(2437, 4, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: public_html.zip', '2401:4900:883d:3bd:88d9:f0e9:490c:78c2', '2026-11-06 17:04:39'),
(2438, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:585d:75b3:ccf1:9e5c', '2026-11-06 17:04:43'),
(2439, 9, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: public_html.zip', '2405:201:6804:bcd8:585d:75b3:ccf1:9e5c', '2026-11-06 17:05:48'),
(2440, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:942a:32d1:f04:becb', '2026-11-06 17:06:11'),
(2441, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:585d:75b3:ccf1:9e5c', '2026-11-06 18:04:52'),
(2442, 9, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Almost-Heaven.zip', '2405:201:6804:bcd8:585d:75b3:ccf1:9e5c', '2026-11-06 18:05:31'),
(2443, 9, NULL, 'Delete File', NULL, NULL, NULL, 'Files', 'Deleted file: Almost-Heaven.zip', '2405:201:6804:bcd8:585d:75b3:ccf1:9e5c', '2026-11-06 18:05:44'),
(2444, 9, NULL, 'Upload File', NULL, NULL, NULL, 'Files', 'Uploaded file: Almost-Heaven.zip', '2405:201:6804:bcd8:585d:75b3:ccf1:9e5c', '2026-11-06 18:06:32'),
(2445, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.144.177', '2026-11-06 18:08:06'),
(2446, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Almost-Heaven.zip', '106.219.144.177', '2026-11-06 18:08:14'),
(2447, 5, NULL, 'Download File', NULL, NULL, NULL, 'Files', 'Downloaded file: Almost-Heaven.zip', '106.219.144.177', '2026-11-06 18:11:26'),
(2448, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:cbf:b89e:571d:ff50', '2026-11-07 09:27:05'),
(2449, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-06, ID: 355) as full_day', '2405:201:6818:f058:cbf:b89e:571d:ff50', '2026-11-07 09:27:07'),
(2450, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3058:823e:8000::', '2026-11-07 09:28:02'),
(2451, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-06, ID: 356) as full_day', '2409:40d2:3058:823e:8000::', '2026-11-07 09:28:04'),
(2452, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-07 09:38:17'),
(2453, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/11/2026 09:38:17 AM (IST). Late minutes: 0', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-07 09:38:17'),
(2454, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3045:e3a2:5449:abff:fe42:2b73', '2026-11-07 09:39:29'),
(2455, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-06, ID: 358) as full_day', '2409:40d2:3045:e3a2:5449:abff:fe42:2b73', '2026-11-07 09:39:30'),
(2456, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3045:e3a2:5449:abff:fe42:2b73', '2026-11-07 09:40:00'),
(2457, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/11/2026 09:40:00 AM (IST). Late minutes: 0', '2409:40d2:3045:e3a2:5449:abff:fe42:2b73', '2026-11-07 09:40:00'),
(2458, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3058:823e:8000::', '2026-11-07 09:40:24'),
(2459, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/11/2026 09:40:24 AM (IST). Late minutes: 0', '2409:40d2:3058:823e:8000::', '2026-11-07 09:40:24'),
(2460, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2402:8100:2f45:e040::1fa2:c3f', '2026-11-07 09:45:08'),
(2461, 10, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-06, ID: 360) as full_day', '2402:8100:2f45:e040::1fa2:c3f', '2026-11-07 09:45:09'),
(2462, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2402:8100:2f45:e040::1fa2:c3f', '2026-11-07 09:45:16'),
(2463, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/11/2026 09:45:16 AM (IST). Late minutes: 5', '2402:8100:2f45:e040::1fa2:c3f', '2026-11-07 09:45:16'),
(2464, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:f4ae:9c90:6916:80d7', '2026-11-07 09:46:50'),
(2465, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-06, ID: 357) as full_day', '2405:201:6804:bcd8:f4ae:9c90:6916:80d7', '2026-11-07 09:46:58'),
(2466, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-06, ID: 359) as full_day', '2405:201:6804:bcd8:f4ae:9c90:6916:80d7', '2026-11-07 09:46:58'),
(2467, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:4b38:6aca:80c5:48a0', '2026-11-07 09:48:12'),
(2468, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883d:3bd:4b38:6aca:80c5:48a0', '2026-11-07 09:48:16'),
(2469, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 07/11/2026 09:48:16 AM (IST). Late minutes: 8', '2401:4900:883d:3bd:4b38:6aca:80c5:48a0', '2026-11-07 09:48:16'),
(2470, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3058:823e:8000::', '2026-11-07 10:00:16'),
(2471, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:9c6d:4053:9286:6061', '2026-11-07 10:23:59'),
(2472, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:5c37:60d6:ff47:a8dc', '2026-11-07 10:26:28'),
(2473, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:c520:4928:b878:f014', '2026-11-07 11:39:39'),
(2474, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:3bd:4b38:6aca:80c5:48a0', '2026-11-07 11:40:30'),
(2475, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:90a8:4735:48ce:ad2', '2026-11-12 09:17:47'),
(2476, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-07, ID: 361) as full_day', '2405:201:6818:f058:90a8:4735:48ce:ad2', '2026-11-12 09:17:49'),
(2477, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3051:9f7c:8000::', '2026-11-12 09:26:03'),
(2478, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-07, ID: 363) as full_day', '2409:40d2:3051:9f7c:8000::', '2026-11-12 09:26:04'),
(2479, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-12 09:33:18'),
(2480, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/11/2026 09:33:18 AM (IST). Late minutes: 0', '2409:40d2:304b:8c3c:b0de:6eff:fec1:fa84', '2026-11-12 09:33:18'),
(2481, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3051:9f7c:8000::', '2026-11-12 09:37:51'),
(2482, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/11/2026 09:37:51 AM (IST). Late minutes: 0', '2409:40d2:3051:9f7c:8000::', '2026-11-12 09:37:51'),
(2483, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-07, ID: 362) as full_day', '2409:40d2:3051:9f7c:8000::', '2026-11-12 09:38:02'),
(2484, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-07, ID: 364) as full_day', '2409:40d2:3051:9f7c:8000::', '2026-11-12 09:38:02'),
(2485, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-07, ID: 365) as full_day', '2409:40d2:3051:9f7c:8000::', '2026-11-12 09:38:02'),
(2486, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:44ca:4095:e2ff:fe11:ec99', '2026-11-12 09:39:14'),
(2487, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3053:44ca:4095:e2ff:fe11:ec99', '2026-11-12 09:39:21'),
(2488, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/11/2026 09:39:20 AM (IST). Late minutes: 0', '2409:40d2:3053:44ca:4095:e2ff:fe11:ec99', '2026-11-12 09:39:21'),
(2489, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae84:fdb0::9589:d804', '2026-11-12 09:49:14'),
(2490, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae84:fdb0::9589:d804', '2026-11-12 09:49:26'),
(2491, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/11/2026 09:49:26 AM (IST). Late minutes: 9', '2409:4085:ae84:fdb0::9589:d804', '2026-11-12 09:49:26'),
(2492, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:f922:32ad:c274:51f9', '2026-11-12 09:49:56'),
(2493, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883d:6edd:f922:32ad:c274:51f9', '2026-11-12 09:50:44'),
(2494, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/11/2026 09:50:44 AM (IST). Late minutes: 10', '2401:4900:883d:6edd:f922:32ad:c274:51f9', '2026-11-12 09:50:44'),
(2495, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:95cb:45ea:1636:e5ba', '2026-11-12 09:50:57'),
(2496, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2405:201:6804:bcd8:95cb:45ea:1636:e5ba', '2026-11-12 09:51:03'),
(2497, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/11/2026 09:51:02 AM (IST). Late minutes: 11', '2405:201:6804:bcd8:95cb:45ea:1636:e5ba', '2026-11-12 09:51:03'),
(2498, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:6867:60c7:6355:7c69', '2026-11-12 10:28:30'),
(2499, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883d:6edd:6867:60c7:6355:7c69', '2026-11-12 10:28:35'),
(2500, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 12/11/2026 10:28:35 AM (IST). Late minutes: 48', '2401:4900:883d:6edd:6867:60c7:6355:7c69', '2026-11-12 10:28:35'),
(2501, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:4918:458a:5baf:3556', '2026-11-12 10:28:57'),
(2502, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:6edd:592:b261:41:2075', '2026-11-12 11:05:11'),
(2503, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2405:201:6804:bcd8:fdab:3b7d:ec1e:de8a', '2026-11-12 17:30:51'),
(2504, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae84:fdb0::9589:d804', '2026-11-12 18:32:43'),
(2505, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User ended shift at 12/11/2026 06:32:54 PM (IST). Status: half_day, Duration: 08h 43m 28s, Early Exit: 0m', '2409:4085:ae84:fdb0::9589:d804', '2026-11-12 18:32:54'),
(2506, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3010:a4ff:8000::', '2026-11-13 09:19:40'),
(2507, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-12, ID: 367) as full_day', '2409:40d2:3010:a4ff:8000::', '2026-11-13 09:19:42'),
(2508, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:86a5:469a:80b0:5594:273c:c682', '2026-11-13 09:33:50'),
(2509, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-12, ID: 372) as half_day', '2401:4900:86a5:469a:80b0:5594:273c:c682', '2026-11-13 09:33:52'),
(2510, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '106.219.193.14', '2026-11-13 09:38:25'),
(2511, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/11/2026 09:38:25 AM (IST). Late minutes: 0', '106.219.193.14', '2026-11-13 09:38:25'),
(2512, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:44ca:58f3:ffff:fef7:e084', '2026-11-13 09:38:46'),
(2513, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-12, ID: 368) as full_day', '2409:40d2:3053:44ca:58f3:ffff:fef7:e084', '2026-11-13 09:38:48'),
(2514, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2409:40d2:3053:44ca:58f3:ffff:fef7:e084', '2026-11-13 09:38:54'),
(2515, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/11/2026 09:38:54 AM (IST). Late minutes: 0', '2409:40d2:3053:44ca:58f3:ffff:fef7:e084', '2026-11-13 09:38:54'),
(2516, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2409:40d2:3010:a4ff:8000::', '2026-11-13 09:43:25'),
(2517, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/11/2026 09:43:25 AM (IST). Late minutes: 3', '2409:40d2:3010:a4ff:8000::', '2026-11-13 09:43:25'),
(2518, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3010:a4ff:8000::', '2026-11-13 09:43:52'),
(2519, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:613b:8af0:7099:92ee', '2026-11-13 09:48:27'),
(2520, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:613b:8af0:7099:92ee', '2026-11-13 09:48:38'),
(2521, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-12, ID: 370) as full_day', '2405:201:6804:bcd8:613b:8af0:7099:92ee', '2026-11-13 09:48:40'),
(2522, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2405:201:6804:bcd8:613b:8af0:7099:92ee', '2026-11-13 09:48:43'),
(2523, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/11/2026 09:48:43 AM (IST). Late minutes: 8', '2405:201:6804:bcd8:613b:8af0:7099:92ee', '2026-11-13 09:48:43'),
(2524, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:592:b261:41:2075', '2026-11-13 09:53:22'),
(2525, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-12, ID: 366) as full_day', '2401:4900:883d:6edd:592:b261:41:2075', '2026-11-13 09:53:28'),
(2526, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-12, ID: 371) as full_day', '2401:4900:883d:6edd:592:b261:41:2075', '2026-11-13 09:53:28'),
(2527, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:1dfe:d2e9:5593:add4', '2026-11-13 09:59:36'),
(2528, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae84:fdb0::9589:d804', '2026-11-13 10:00:27'),
(2529, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2409:4085:ae84:fdb0::9589:d804', '2026-11-13 10:00:33'),
(2530, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/11/2026 10:00:33 AM (IST). Late minutes: 20', '2409:4085:ae84:fdb0::9589:d804', '2026-11-13 10:00:33'),
(2531, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:dcae:e95f:d08d:c0e7', '2026-11-13 10:02:08'),
(2532, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883d:6edd:dcae:e95f:d08d:c0e7', '2026-11-13 10:02:13'),
(2533, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/11/2026 10:02:13 AM (IST). Late minutes: 22', '2401:4900:883d:6edd:dcae:e95f:d08d:c0e7', '2026-11-13 10:02:13'),
(2534, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7068:b46a:3ad5:fe', '2026-11-13 10:02:15'),
(2535, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:dd53:f04a:deeb:8c09', '2026-11-13 10:06:25'),
(2536, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7068:b46a:3ad5:fe', '2026-11-13 10:07:41'),
(2537, 9, NULL, 'Cancel Leave', NULL, NULL, NULL, 'Leaves', 'Leave request ID 3 was cancelled by user', '2405:201:6804:bcd8:7068:b46a:3ad5:fe', '2026-11-13 10:26:57'),
(2538, 9, NULL, 'Request Leave', NULL, NULL, NULL, 'Leaves', 'User Abhay requested 1 days leave (2026-11-21 to 2026-11-21)', '2405:201:6804:bcd8:7068:b46a:3ad5:fe', '2026-11-13 10:35:07'),
(2539, 9, NULL, 'Request Leave', NULL, NULL, NULL, 'Leaves', 'User Abhay requested 2 days leave (2026-11-26 to 2026-11-27)', '2405:201:6804:bcd8:7068:b46a:3ad5:fe', '2026-11-13 10:37:02'),
(2540, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '49.43.160.210', '2026-11-13 11:14:16'),
(2541, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '49.43.160.210', '2026-11-13 11:14:22'),
(2542, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User started shift at 13/11/2026 11:14:22 AM (IST). Late minutes: 94', '49.43.160.210', '2026-11-13 11:14:22'),
(2543, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:3085:64f0:b9d0:272b', '2026-11-13 12:49:25'),
(2544, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883d:6edd:3085:64f0:b9d0:272b', '2026-11-13 13:13:09'),
(2545, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:86a5:469a:80b0:5594:273c:c682', '2026-11-13 13:25:41'),
(2546, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2405:201:6804:bcd8:99f9:33de:8ca0:400f', '2026-11-13 13:51:59'),
(2547, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2405:201:6804:bcd8:99f9:33de:8ca0:400f', '2026-11-13 13:53:03'),
(2548, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883d:6edd:7919:181f:4c1a:5c6', '2026-11-13 13:54:31'),
(2549, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Udit started shift at 13/11/2026 01:54:31 PM (IST) from IP 2401:4900:883d:6edd:7919:181f:4c1a:5c6 (Desktop). Late: 254m', '2401:4900:883d:6edd:7919:181f:4c1a:5c6', '2026-11-13 13:54:31'),
(2550, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:7068:b46a:3ad5:fe', '2026-11-13 13:56:24'),
(2551, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:e10e:ec7a:a57a:8fda', '2026-11-13 13:57:31'),
(2552, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:6edd:7919:181f:4c1a:5c6', '2026-11-13 13:59:33'),
(2553, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:ec76:7e72:78fa:2b53', '2026-11-13 14:00:57'),
(2554, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:1dfe:d2e9:5593:add4', '2026-11-13 14:25:52'),
(2555, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:dcae:e95f:d08d:c0e7', '2026-11-13 15:10:12'),
(2556, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae84:fdb0::9589:d804', '2026-11-13 18:16:50'),
(2557, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:7891:f7a7:10e3:c94f', '2026-11-13 18:27:39'),
(2558, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User Anjali Singh ended shift at 13/11/2026 06:30:41 PM (IST) from IP 2401:4900:883d:6edd:7891:f7a7:10e3:c94f. Status: half_day, Duration: 08h 30m 08s, Early Exit: 0m', '2401:4900:883d:6edd:7891:f7a7:10e3:c94f', '2026-11-13 18:30:41'),
(2559, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304f:b81b:8000::', '2026-11-14 09:21:50'),
(2560, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-13, ID: 375) as full_day', '2409:40d2:304f:b81b:8000::', '2026-11-14 09:21:52'),
(2561, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:44ca:78c1:b8ff:fe5a:a5a0', '2026-11-14 09:22:35'),
(2562, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-13, ID: 374) as full_day', '2409:40d2:3053:44ca:78c1:b8ff:fe5a:a5a0', '2026-11-14 09:22:36'),
(2563, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883d:6edd:b842:a9c2:5169:aadc', '2026-11-14 09:44:44'),
(2564, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Sarthak started shift at 14/11/2026 09:44:44 AM (IST) from IP 2401:4900:883d:6edd:b842:a9c2:5169:aadc (Desktop). Late: 4m', '2401:4900:883d:6edd:b842:a9c2:5169:aadc', '2026-11-14 09:44:44'),
(2565, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae84:fdb0::9589:d804', '2026-11-14 09:45:08'),
(2566, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '106.219.148.222', '2026-11-14 09:46:24'),
(2567, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Anjali Singh started shift at 14/11/2026 09:46:24 AM (IST) from IP 106.219.148.222 (Desktop). Late: 6m', '106.219.148.222', '2026-11-14 09:46:24'),
(2568, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', '2026-11-14 09:49:05'),
(2569, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Abhay started shift at 14/11/2026 09:49:05 AM (IST) from IP 2401:4900:883d:6edd:ee27:6ede:c17:6a5c (Desktop). Late: 9m', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', '2026-11-14 09:49:05'),
(2570, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-13, ID: 373) as full_day', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', '2026-11-14 09:49:11'),
(2571, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-13, ID: 376) as full_day', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', '2026-11-14 09:49:11'),
(2572, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-13, ID: 378) as full_day', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', '2026-11-14 09:49:11');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `action`, `record_type`, `record_id`, `details`, `module`, `description`, `ip_address`, `created_at`) VALUES
(2573, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-13, ID: 379) as half_day', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', '2026-11-14 09:49:11'),
(2574, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-13, ID: 380) as half_day', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', '2026-11-14 09:49:11'),
(2575, 9, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', '2026-11-14 09:49:25'),
(2576, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:85c7:a295:fb3e:b7e6', '2026-11-14 09:49:32'),
(2577, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:b842:a9c2:5169:aadc', '2026-11-14 09:50:29'),
(2578, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', '2026-11-14 09:53:24'),
(2579, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', '2026-11-14 09:53:28'),
(2580, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Rahul started shift at 14/11/2026 09:53:28 AM (IST) from IP 2401:4900:883d:6edd:55ac:7c87:97c7:8d42 (Desktop). Late: 13m', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', '2026-11-14 09:53:28'),
(2581, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', '2026-11-14 09:53:45'),
(2582, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:e9fb:e86f:cb53:c23f', '2026-11-14 09:53:53'),
(2583, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883d:6edd:e9fb:e86f:cb53:c23f', '2026-11-14 09:53:59'),
(2584, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Priyansh started shift at 14/11/2026 09:53:59 AM (IST) from IP 2401:4900:883d:6edd:e9fb:e86f:cb53:c23f (Desktop). Late: 13m', '2401:4900:883d:6edd:e9fb:e86f:cb53:c23f', '2026-11-14 09:53:59'),
(2585, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', '2026-11-14 10:00:23'),
(2586, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Aman started shift at 14/11/2026 10:00:23 AM (IST) from IP 2401:4900:883d:6edd:55ac:7c87:97c7:8d42 (Desktop). Late: 20m', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', '2026-11-14 10:00:23'),
(2587, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:304f:b81b:8000::', '2026-11-14 10:00:53'),
(2588, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', '2026-11-14 10:08:05'),
(2589, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883d:6edd:51ef:a2a8:bea1:9e5', '2026-11-14 10:20:52'),
(2590, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2401:4900:883d:6edd:51ef:a2a8:bea1:9e5', '2026-11-14 10:20:57'),
(2591, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Mrinali started shift at 14/11/2026 10:20:57 AM (IST) from IP 2401:4900:883d:6edd:51ef:a2a8:bea1:9e5 (Desktop). Late: 40m', '2401:4900:883d:6edd:51ef:a2a8:bea1:9e5', '2026-11-14 10:20:57'),
(2592, 2, NULL, 'Approve Leave', NULL, NULL, NULL, 'Leaves', 'Manager approved leave request ID 5', '2401:4900:883d:6edd:51ef:a2a8:bea1:9e5', '2026-11-14 10:29:18'),
(2593, 2, NULL, 'Approve Leave', NULL, NULL, NULL, 'Leaves', 'Manager approved leave request ID 4', '2401:4900:883d:6edd:51ef:a2a8:bea1:9e5', '2026-11-14 10:29:33'),
(2594, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883d:6edd:54c:8c08:db4f:5c9a', '2026-11-14 12:56:12'),
(2595, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883d:6edd:54c:8c08:db4f:5c9a', '2026-11-14 12:56:16'),
(2596, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Udit started shift at 14/11/2026 12:56:16 PM (IST) from IP 2401:4900:883d:6edd:54c:8c08:db4f:5c9a (Desktop). Late: 196m', '2401:4900:883d:6edd:54c:8c08:db4f:5c9a', '2026-11-14 12:56:16'),
(2597, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883d:6edd:54c:8c08:db4f:5c9a', '2026-11-14 12:58:20'),
(2598, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883d:6edd:6982:65f2:209d:6167', '2026-11-14 18:31:45'),
(2599, 12, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User Anjali Singh ended shift at 14/11/2026 06:31:51 PM (IST) from IP 2401:4900:883d:6edd:6982:65f2:209d:6167. Status: half_day, Duration: 08h 45m 27s, Early Exit: 0m', '2401:4900:883d:6edd:6982:65f2:209d:6167', '2026-11-14 18:31:51'),
(2600, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:d22c:8000::', '2026-11-16 09:27:59'),
(2601, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-14, ID: 381) as full_day', '2409:40d2:3014:d22c:8000::', '2026-11-16 09:28:01'),
(2602, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:589f:5b6a:415e:7cdf', '2026-11-16 09:31:42'),
(2603, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-14, ID: 384) as full_day', '2401:4900:883e:159b:589f:5b6a:415e:7cdf', '2026-11-16 09:31:44'),
(2604, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:589f:5b6a:415e:7cdf', '2026-11-16 09:32:48'),
(2605, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:589f:5b6a:415e:7cdf', '2026-11-16 09:33:37'),
(2606, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:1808:ea0a:967e:a174', '2026-11-16 09:34:59'),
(2607, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:7891:f7a7:10e3:c94f', '2026-11-16 09:35:58'),
(2608, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3060:f6ca:dbc4:2898', '2026-11-16 09:39:38'),
(2609, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-14, ID: 383) as full_day', '2405:201:6804:bcd8:3060:f6ca:dbc4:2898', '2026-11-16 09:39:40'),
(2610, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:41:18'),
(2611, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:85cb:6044:f4b:c9ea', '2026-11-16 09:41:27'),
(2612, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:3db6:e2c4:3249:a47d', '2026-11-16 09:41:46'),
(2613, 8, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-14, ID: 385) as full_day', '2401:4900:883e:159b:3db6:e2c4:3249:a47d', '2026-11-16 09:41:48'),
(2614, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:44:44'),
(2615, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:54:11'),
(2616, 1, NULL, 'Add Authorized IP', NULL, NULL, NULL, 'IP Whitelist', 'Admin Kartikeyan added authorized IP 192.168.1.22 (Office Wi-Fi 2 (Device 1.22)) with status \'active\'', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:55:07'),
(2617, 1, NULL, 'Add Authorized IP', NULL, NULL, NULL, 'IP Whitelist', 'Admin Kartikeyan added authorized IP 2401:4900:* (Office Wi-Fi Public IPv6 (All Blocks)) with status \'active\'', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:55:07'),
(2618, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-14, ID: 386) as full_day', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:55:15'),
(2619, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-14, ID: 387) as half_day', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:55:15'),
(2620, 1, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-14, ID: 388) as half_day', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:55:15'),
(2621, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:58:12'),
(2622, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Abhay started shift at 16/11/2026 09:58:12 AM (IST) from IP 2401:4900:883e:159b:2da3:d3c:e30e:d56f (Desktop). Late: 18m', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:58:12'),
(2623, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:58:15'),
(2624, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Uvaish started shift at 16/11/2026 09:58:15 AM (IST) from IP 2401:4900:883e:159b:2da3:d3c:e30e:d56f (Desktop). Late: 18m', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:58:15'),
(2625, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:58:20'),
(2626, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:58:38'),
(2627, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:58:42'),
(2628, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Aman started shift at 16/11/2026 09:58:42 AM (IST) from IP 2401:4900:883e:159b:2da3:d3c:e30e:d56f (Desktop). Late: 18m', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', '2026-11-16 09:58:42'),
(2629, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:58:49'),
(2630, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Rahul started shift at 16/11/2026 09:58:49 AM (IST) from IP 2401:4900:883e:159b:647e:d829:6864:809a (Desktop). Late: 18m', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 09:58:49'),
(2631, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2401:4900:883e:159b:85cb:6044:f4b:c9ea', '2026-11-16 09:59:17'),
(2632, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Anjali Singh started shift at 16/11/2026 09:59:17 AM (IST) from IP 2401:4900:883e:159b:85cb:6044:f4b:c9ea (Desktop). Late: 19m', '2401:4900:883e:159b:85cb:6044:f4b:c9ea', '2026-11-16 09:59:17'),
(2633, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:d22c:8000::', '2026-11-16 09:59:31'),
(2634, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:185f:694a:435d:458c', '2026-11-16 10:00:52'),
(2635, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883e:159b:185f:694a:435d:458c', '2026-11-16 10:00:56'),
(2636, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Sarthak started shift at 16/11/2026 10:00:56 AM (IST) from IP 2401:4900:883e:159b:185f:694a:435d:458c (Desktop). Late: 20m', '2401:4900:883e:159b:185f:694a:435d:458c', '2026-11-16 10:00:56'),
(2637, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:159b:3db6:e2c4:3249:a47d', '2026-11-16 10:01:36'),
(2638, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Priyansh started shift at 16/11/2026 10:01:36 AM (IST) from IP 2401:4900:883e:159b:3db6:e2c4:3249:a47d (Desktop). Late: 21m', '2401:4900:883e:159b:3db6:e2c4:3249:a47d', '2026-11-16 10:01:36'),
(2639, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:44ca:dca9:a8ff:fee0:c335', '2026-11-16 10:04:16'),
(2640, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883e:159b:5982:ee0c:8c0f:2b93', '2026-11-16 10:14:24'),
(2641, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2401:4900:883e:159b:5982:ee0c:8c0f:2b93', '2026-11-16 10:14:28'),
(2642, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Mrinali started shift at 16/11/2026 10:14:28 AM (IST) from IP 2401:4900:883e:159b:5982:ee0c:8c0f:2b93 (Desktop). Late: 34m', '2401:4900:883e:159b:5982:ee0c:8c0f:2b93', '2026-11-16 10:14:28'),
(2643, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.151.73', '2026-11-16 10:16:18'),
(2644, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:3db6:e2c4:3249:a47d', '2026-11-16 11:17:35'),
(2645, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 11:35:52'),
(2646, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 11:37:49'),
(2647, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 11:37:54'),
(2648, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Udit started shift at 16/11/2026 11:37:54 AM (IST) from IP 2401:4900:883e:159b:647e:d829:6864:809a (Desktop). Late: 117m', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 11:37:54'),
(2649, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:647e:d829:6864:809a', '2026-11-16 11:38:00'),
(2650, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:3d12:4bb9:3149:6e05', '2026-11-16 13:02:48'),
(2651, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:85cb:6044:f4b:c9ea', '2026-11-16 18:11:46'),
(2652, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:7891:f7a7:10e3:c94f', '2026-11-16 18:24:39'),
(2653, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 396) as half_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2654, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 389) as full_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2655, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 390) as full_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2656, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 391) as full_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2657, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 392) as full_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2658, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 393) as full_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2659, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 394) as full_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2660, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 395) as full_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2661, 2, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-16, ID: 397) as half_day', '2401:4900:883e:159b:91d6:1213:a70d:6ef7', '2026-11-16 19:00:16'),
(2662, 1, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as ADMIN', '2405:201:6804:bcd8:c9ae:7a11:5cac:d063', '2026-11-16 19:22:50'),
(2663, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6818:f058:6376:8e02:91b1:a2a9', '2026-11-17 07:55:37'),
(2664, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '93.88.156.19', '2026-11-17 09:03:18'),
(2665, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:d22c:8000::', '2026-11-17 09:04:48'),
(2666, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:d22c:8000::', '2026-11-17 09:14:48'),
(2667, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:680c:617a:c81b:1968:c3f2:7319', '2026-11-17 09:28:52'),
(2668, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:388a:35c0:acfd:f799', '2026-11-17 09:30:07'),
(2669, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883e:159b:388a:35c0:acfd:f799', '2026-11-17 09:30:13'),
(2670, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Rahul started shift at 17/11/2026 09:30:12 AM (IST) from IP 2401:4900:883e:159b:388a:35c0:acfd:f799 (Mobile). Late: 0m', '2401:4900:883e:159b:388a:35c0:acfd:f799', '2026-11-17 09:30:13'),
(2671, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:388a:35c0:acfd:f799', '2026-11-17 09:33:54'),
(2672, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:44ca:d837:7eff:fe5a:cc43', '2026-11-17 09:34:30'),
(2673, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:388a:35c0:acfd:f799', '2026-11-17 09:35:23'),
(2674, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883e:159b:cf2f:b104:2ad8:1874', '2026-11-17 09:35:56'),
(2675, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Abhay started shift at 17/11/2026 09:35:56 AM (IST) from IP 2401:4900:883e:159b:cf2f:b104:2ad8:1874 (Desktop). Late: 0m', '2401:4900:883e:159b:cf2f:b104:2ad8:1874', '2026-11-17 09:35:56'),
(2676, 7, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:388a:35c0:acfd:f799', '2026-11-17 09:36:03'),
(2677, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:388a:35c0:acfd:f799', '2026-11-17 09:37:40'),
(2678, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:40:14'),
(2679, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:40:21'),
(2680, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Sarthak started shift at 17/11/2026 09:40:21 AM (IST) from IP 2401:4900:883e:159b:8dc2:3baa:8d57:1bf0 (Desktop). Late: 0m', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:40:21'),
(2681, 7, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:40:56'),
(2682, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:41:07'),
(2683, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:1593:6544:2dce:660', '2026-11-17 09:41:42'),
(2684, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:41:48'),
(2685, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Aman started shift at 17/11/2026 09:41:48 AM (IST) from IP 2401:4900:883e:159b:8dc2:3baa:8d57:1bf0 (Desktop). Late: 1m', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:41:48'),
(2686, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:d22c:8000::', '2026-11-17 09:42:21'),
(2687, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:159b:1593:6544:2dce:660', '2026-11-17 09:42:36'),
(2688, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Priyansh started shift at 17/11/2026 09:42:36 AM (IST) from IP 2401:4900:883e:159b:1593:6544:2dce:660 (Desktop). Late: 2m', '2401:4900:883e:159b:1593:6544:2dce:660', '2026-11-17 09:42:36'),
(2689, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:49:23'),
(2690, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:49:42'),
(2691, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Uvaish started shift at 17/11/2026 09:49:42 AM (IST) from IP 2401:4900:883e:159b:8dc2:3baa:8d57:1bf0 (Desktop). Late: 9m', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 09:49:42'),
(2692, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae84:fdb0::9589:d804', '2026-11-17 09:56:55'),
(2693, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3014:d22c:8000::', '2026-11-17 09:58:19'),
(2694, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2401:4900:883e:159b:7891:f7a7:10e3:c94f', '2026-11-17 10:04:19'),
(2695, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Anjali Singh started shift at 17/11/2026 10:04:19 AM (IST) from IP 2401:4900:883e:159b:7891:f7a7:10e3:c94f (Desktop). Late: 24m', '2401:4900:883e:159b:7891:f7a7:10e3:c94f', '2026-11-17 10:04:19'),
(2696, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-17 10:08:01'),
(2697, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:1593:6544:2dce:660', '2026-11-17 10:46:53'),
(2698, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', '2026-11-17 11:29:06'),
(2699, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883e:159b:7d75:2f17:e506:ff82', '2026-11-17 11:31:37'),
(2700, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2401:4900:883e:159b:7d75:2f17:e506:ff82', '2026-11-17 11:31:41'),
(2701, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Mrinali started shift at 17/11/2026 11:31:41 AM (IST) from IP 2401:4900:883e:159b:7d75:2f17:e506:ff82 (Desktop). Late: 111m', '2401:4900:883e:159b:7d75:2f17:e506:ff82', '2026-11-17 11:31:41'),
(2702, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '106.219.151.73', '2026-11-17 15:00:19'),
(2703, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '106.219.151.73', '2026-11-17 15:02:18'),
(2704, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.151.73', '2026-11-17 15:05:30'),
(2705, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '106.219.151.73', '2026-11-17 15:06:05'),
(2706, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-11-17 15:06:57'),
(2707, 3, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as PRODUCT_MANAGER', '2401:4900:883e:159b:b8c8:d2b8:9f23:7d29', '2026-11-17 15:41:59'),
(2708, 3, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #3 started activity \'Working on Task\'', '2401:4900:883e:159b:b8c8:d2b8:9f23:7d29', '2026-11-17 15:42:03'),
(2709, 3, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Udit started shift at 17/11/2026 03:42:03 PM (IST) from IP 2401:4900:883e:159b:b8c8:d2b8:9f23:7d29 (Desktop). Late: 362m', '2401:4900:883e:159b:b8c8:d2b8:9f23:7d29', '2026-11-17 15:42:03'),
(2710, 3, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:b8c8:d2b8:9f23:7d29', '2026-11-17 15:42:38'),
(2711, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.151.73', '2026-11-17 18:38:44'),
(2712, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:7592:8000::', '2026-11-18 09:14:58'),
(2713, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 400) as full_day', '2409:40d2:3053:7592:8000::', '2026-11-18 09:14:59'),
(2714, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:19d0:9429:37d9:4883', '2026-11-18 09:32:07'),
(2715, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 398) as full_day', '2401:4900:883e:159b:19d0:9429:37d9:4883', '2026-11-18 09:32:08'),
(2716, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883e:159b:19d0:9429:37d9:4883', '2026-11-18 09:32:11'),
(2717, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Rahul started shift at 18/11/2026 09:32:11 AM (IST) from IP 2401:4900:883e:159b:19d0:9429:37d9:4883 (Mobile). Late: 0m', '2401:4900:883e:159b:19d0:9429:37d9:4883', '2026-11-18 09:32:11'),
(2718, 4, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:19d0:9429:37d9:4883', '2026-11-18 09:32:17'),
(2719, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:19d0:9429:37d9:4883', '2026-11-18 09:34:17'),
(2720, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3015:8946:54a6:4ff:fe90:e169', '2026-11-18 09:37:28'),
(2721, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 399) as full_day', '2409:40d2:3015:8946:54a6:4ff:fe90:e169', '2026-11-18 09:37:30'),
(2722, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883e:159b:d26c:1fd4:5d37:18b4', '2026-11-18 09:37:51'),
(2723, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Abhay started shift at 18/11/2026 09:37:51 AM (IST) from IP 2401:4900:883e:159b:d26c:1fd4:5d37:18b4 (Desktop). Late: 0m', '2401:4900:883e:159b:d26c:1fd4:5d37:18b4', '2026-11-18 09:37:51'),
(2724, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-11-18 09:42:20'),
(2725, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 403) as full_day', '49.43.160.210', '2026-11-18 09:42:21'),
(2726, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:3127:7303:db14:aeba', '2026-11-18 09:42:48'),
(2727, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2401:4900:883e:159b:3127:7303:db14:aeba', '2026-11-18 09:42:55'),
(2728, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Uvaish started shift at 18/11/2026 09:42:55 AM (IST) from IP 2401:4900:883e:159b:3127:7303:db14:aeba (Desktop). Late: 2m', '2401:4900:883e:159b:3127:7303:db14:aeba', '2026-11-18 09:42:55'),
(2729, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 401) as full_day', '2409:40d2:3053:7592:8000::', '2026-11-18 09:43:23'),
(2730, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 402) as full_day', '2409:40d2:3053:7592:8000::', '2026-11-18 09:43:23'),
(2731, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 404) as full_day', '2409:40d2:3053:7592:8000::', '2026-11-18 09:43:23'),
(2732, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 405) as half_day', '2409:40d2:3053:7592:8000::', '2026-11-18 09:43:23'),
(2733, 7, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-17, ID: 406) as half_day', '2409:40d2:3053:7592:8000::', '2026-11-18 09:43:23'),
(2734, 7, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #7 started activity \'Working on Task\'', '2401:4900:883e:159b:2d84:28ca:db94:4c02', '2026-11-18 09:44:11'),
(2735, 7, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Sarthak started shift at 18/11/2026 09:44:11 AM (IST) from IP 2401:4900:883e:159b:2d84:28ca:db94:4c02 (Desktop). Late: 4m', '2401:4900:883e:159b:2d84:28ca:db94:4c02', '2026-11-18 09:44:11'),
(2736, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:4085:ae8d:d1c7::9589:1519', '2026-11-18 09:48:30'),
(2737, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-18 09:51:49'),
(2738, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-18 09:51:57'),
(2739, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Anjali Singh started shift at 18/11/2026 09:51:57 AM (IST) from IP 2401:4900:883e:159b:720c:ec8d:bac:41ef (Desktop). Late: 11m', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-18 09:51:57'),
(2740, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.151.73', '2026-11-18 09:53:07'),
(2741, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-11-18 09:55:44'),
(2742, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '2401:4900:883e:159b:3127:7303:db14:aeba', '2026-11-18 09:56:02'),
(2743, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:3127:7303:db14:aeba', '2026-11-18 09:56:21'),
(2744, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:159b:3127:7303:db14:aeba', '2026-11-18 09:56:24'),
(2745, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Aman started shift at 18/11/2026 09:56:24 AM (IST) from IP 2401:4900:883e:159b:3127:7303:db14:aeba (Desktop). Late: 16m', '2401:4900:883e:159b:3127:7303:db14:aeba', '2026-11-18 09:56:24'),
(2746, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:e4d6:4adb:186e:cb1a', '2026-11-18 09:57:06'),
(2747, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:159b:b54a:d626:e5:ba33', '2026-11-18 09:58:10'),
(2748, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Priyansh started shift at 18/11/2026 09:58:10 AM (IST) from IP 2401:4900:883e:159b:b54a:d626:e5:ba33 (Desktop). Late: 18m', '2401:4900:883e:159b:b54a:d626:e5:ba33', '2026-11-18 09:58:10'),
(2749, 5, NULL, 'Logout', NULL, NULL, NULL, 'Auth', 'User logged out', '106.219.151.73', '2026-11-18 09:58:35'),
(2750, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.151.73', '2026-11-18 09:58:50'),
(2751, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-18 16:12:15'),
(2752, 2, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as MANAGER', '2401:4900:883e:159b:b059:70f6:dfb7:eb15', '2026-11-18 17:03:29'),
(2753, 2, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #2 started activity \'Working on Task\'', '2401:4900:883e:159b:b059:70f6:dfb7:eb15', '2026-11-18 17:03:35'),
(2754, 2, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Mrinali started shift at 18/11/2026 05:03:35 PM (IST) from IP 2401:4900:883e:159b:b059:70f6:dfb7:eb15 (Desktop). Late: 443m', '2401:4900:883e:159b:b059:70f6:dfb7:eb15', '2026-11-18 17:03:35'),
(2755, 2, NULL, 'End Shift', NULL, NULL, NULL, 'Attendance', 'User Mrinali ended shift at 18/11/2026 05:03:41 PM (IST) from IP 2401:4900:883e:159b:b059:70f6:dfb7:eb15. Status: half_day, Duration: 00h 00m 06s, Early Exit: 86m', '2401:4900:883e:159b:b059:70f6:dfb7:eb15', '2026-11-18 17:03:41'),
(2756, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '106.219.151.73', '2026-11-18 17:09:02'),
(2757, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-18 17:09:38'),
(2758, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:70d8:78e8:79ce:b611', '2026-11-18 18:14:01'),
(2759, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '223.184.179.23', '2026-11-19 08:33:03'),
(2760, 4, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-18, ID: 407) as full_day', '223.184.179.23', '2026-11-19 08:33:05'),
(2761, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '49.43.160.210', '2026-11-19 09:35:37'),
(2762, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:8c61:abfd:8ef8:ffdd', '2026-11-19 09:36:47'),
(2763, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:832a:72a6:b04e:f976', '2026-11-19 09:37:00'),
(2764, 9, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-18, ID: 408) as full_day', '2401:4900:883e:159b:832a:72a6:b04e:f976', '2026-11-19 09:37:02'),
(2765, 5, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:41:03'),
(2766, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-18, ID: 409) as full_day', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:41:05'),
(2767, 5, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #5 started activity \'Working on Task\'', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:41:08'),
(2768, 5, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Uvaish started shift at 19/11/2026 09:41:08 AM (IST) from IP 2401:4900:883e:159b:9d87:2249:99a0:4a54 (Desktop). Late: 1m', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:41:08'),
(2769, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:41:34'),
(2770, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:43:14'),
(2771, 4, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #4 started activity \'Working on Task\'', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:43:24'),
(2772, 4, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Rahul started shift at 19/11/2026 09:43:24 AM (IST) from IP 2401:4900:883e:159b:9d87:2249:99a0:4a54 (Desktop). Late: 3m', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:43:24'),
(2773, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:44:34'),
(2774, 9, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #9 started activity \'Working on Task\'', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:44:38'),
(2775, 9, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Abhay started shift at 19/11/2026 09:44:38 AM (IST) from IP 2401:4900:883e:159b:9d87:2249:99a0:4a54 (Desktop). Late: 4m', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:44:38'),
(2776, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-18, ID: 410) as full_day', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:45:04'),
(2777, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-18, ID: 411) as full_day', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:45:04'),
(2778, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-18, ID: 412) as full_day', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:45:04'),
(2779, 5, NULL, 'Auto Shift End', NULL, NULL, NULL, 'Attendance', 'System auto-ended shift at 07:00 PM IST (Shift Date: 2026-11-18, ID: 413) as full_day', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:45:04'),
(2780, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-19 09:47:59'),
(2781, 12, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-19 09:49:52'),
(2782, 12, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #12 started activity \'Working on Task\'', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-19 09:50:03'),
(2783, 12, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Anjali Singh started shift at 19/11/2026 09:50:03 AM (IST) from IP 2401:4900:883e:159b:720c:ec8d:bac:41ef (Desktop). Late: 10m', '2401:4900:883e:159b:720c:ec8d:bac:41ef', '2026-11-19 09:50:03'),
(2784, 8, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2405:201:6804:bcd8:d895:152e:84e0:dd42', '2026-11-19 09:53:08'),
(2785, 4, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:6cc2:3f5c:8d84:6182', '2026-11-19 09:54:29'),
(2786, 10, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:55:43'),
(2787, 10, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #10 started activity \'Working on Task\'', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:55:46'),
(2788, 10, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Aman started shift at 19/11/2026 09:55:46 AM (IST) from IP 2401:4900:883e:159b:9d87:2249:99a0:4a54 (Desktop). Late: 15m', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 09:55:46'),
(2789, 7, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2409:40d2:3053:7592:8000::', '2026-11-19 10:08:54'),
(2790, 8, NULL, 'Activity Started', NULL, NULL, NULL, 'Workflow', 'Employee User #8 started activity \'Working on Task\'', '2401:4900:883e:159b:ad67:3d99:5ea3:a494', '2026-11-19 10:12:04'),
(2791, 8, NULL, 'Start Shift', NULL, NULL, NULL, 'Attendance', 'User Priyansh started shift at 19/11/2026 10:12:04 AM (IST) from IP 2401:4900:883e:159b:ad67:3d99:5ea3:a494 (Desktop). Late: 32m', '2401:4900:883e:159b:ad67:3d99:5ea3:a494', '2026-11-19 10:12:04'),
(2792, 9, NULL, 'Login', NULL, NULL, NULL, 'Auth', 'User logged in as EMPLOYEE', '2401:4900:883e:159b:9d87:2249:99a0:4a54', '2026-11-19 10:15:09');

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
(9, 'general', 'Other'),
(10, 'waiting', 'Waiting for client content / assets'),
(11, 'waiting', 'Waiting for client feedback / approval'),
(12, 'waiting', 'Waiting for client response / call'),
(13, 'waiting', 'Waiting for client payment / invoice clearance'),
(14, 'waiting', 'Waiting for client credentials / access'),
(15, 'waiting', 'Waiting for client requirements / brief'),
(16, 'waiting', 'Waiting for client review / meeting'),
(17, 'waiting', 'Waiting for manager / lead approval'),
(18, 'waiting', 'Waiting for 3rd-party vendor / API'),
(19, 'blocked', 'Client revision requested'),
(20, 'blocked', 'Payment / contract blocker'),
(21, 'general', 'Other client dependency');

-- --------------------------------------------------------

--
-- Table structure for table `ai_action_plans`
--

CREATE TABLE `ai_action_plans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_token` varchar(64) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `plan_data` text NOT NULL,
  `summary` text NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'awaiting_approval',
  `executed_by` int(11) DEFAULT NULL,
  `result_message` text DEFAULT NULL,
  `result_data` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_chat_messages`
--

CREATE TABLE `ai_chat_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `conversation_id` varchar(64) NOT NULL,
  `sender` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `plan_token` varchar(64) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ai_chat_messages`
--

INSERT INTO `ai_chat_messages` (`id`, `user_id`, `conversation_id`, `sender`, `message`, `plan_token`, `created_at`) VALUES
(1, 4, 'conv_lazbnmrtvl', 'user', 'task assign for abhay Change Logo on Krafting digital Website realted task\r\ndeadliine 15/10/2026\r\nurgent \r\nadd the task for abhay', NULL, '2026-10-14 16:23:48'),
(2, 4, 'conv_lazbnmrtvl', 'assistant', '🤖 **AI Workflow Assistant**\n\nI am ready to assist you. Tell me what action you want to take, and I will prepare a structured **Implementation Plan** for your review before executing it.\n\n**Try these commands**:\n• **\"Assign the homepage development task to Abhay with a deadline of tomorrow.\"**\n• **\"Create a task for Abhay to fix mobile responsive layout.\"**\n• **\"Change task 25 priority to high.\"**\n• **\"Update the deadline of task 25 to 20 October 2026.\"**\n• **\"Show me all pending tasks assigned to Abhay.\"**\n• **\"Show all open task flags.\"**\n• **\"Show pending emergency requests.\"**\n• **\"Generate today\'s employee work summary.\"**', NULL, '2026-10-14 16:23:48'),
(3, 4, 'conv_lazbnmrtvl', 'user', 'Assign the homepage development task to Abhay with a deadline of tomorrow.', NULL, '2026-10-14 16:24:17'),
(4, 4, 'conv_lazbnmrtvl', 'assistant', 'ℹ️ I could not find an existing task matching **\'homepage development task\'**.\n\nWould you like me to **create a new task** titled **\'homepage development task\'** and assign it to **Abhay**?\n\n*Reply with: \'Yes, create task homepage development task for Abhay\'*', NULL, '2026-10-14 16:24:17'),
(5, 4, 'conv_lazbnmrtvl', 'user', 'yes', NULL, '2026-10-14 16:24:29'),
(6, 4, 'conv_lazbnmrtvl', 'assistant', '🤖 **AI Workflow Assistant**\n\nI am ready to assist you. Tell me what action you want to take, and I will prepare a structured **Implementation Plan** for your review before executing it.\n\n**Try these commands**:\n• **\"Assign the homepage development task to Abhay with a deadline of tomorrow.\"**\n• **\"Create a task for Abhay to fix mobile responsive layout.\"**\n• **\"Change task 25 priority to high.\"**\n• **\"Update the deadline of task 25 to 20 October 2026.\"**\n• **\"Show me all pending tasks assigned to Abhay.\"**\n• **\"Show all open task flags.\"**\n• **\"Show pending emergency requests.\"**\n• **\"Generate today\'s employee work summary.\"**', NULL, '2026-10-14 16:24:29'),
(7, 9, 'conv_p3q6exjn6', 'user', 'hi', NULL, '2026-10-14 17:12:47'),
(8, 9, 'conv_p3q6exjn6', 'assistant', '🤖 **AI Workflow Assistant**\n\nI am ready to assist you. Tell me what action you want to take, and I will prepare a structured **Implementation Plan** for your review before executing it.\n\n**Try these commands**:\n• **\"Assign the homepage development task to Abhay with a deadline of tomorrow.\"**\n• **\"Create a task for Abhay to fix mobile responsive layout.\"**\n• **\"Change task 25 priority to high.\"**\n• **\"Update the deadline of task 25 to 20 October 2026.\"**\n• **\"Show me all pending tasks assigned to Abhay.\"**\n• **\"Show all open task flags.\"**\n• **\"Show pending emergency requests.\"**\n• **\"Generate today\'s employee work summary.\"**', NULL, '2026-10-14 17:12:47'),
(9, 9, 'conv_p3q6exjn6', 'user', 'Show me all pending tasks assigned to me', NULL, '2026-10-14 17:13:22'),
(10, 9, 'conv_p3q6exjn6', 'assistant', '🤖 **AI Workflow Assistant**\n\nI am ready to assist you. Tell me what action you want to take, and I will prepare a structured **Implementation Plan** for your review before executing it.\n\n**Try these commands**:\n• **\"Assign the homepage development task to Abhay with a deadline of tomorrow.\"**\n• **\"Create a task for Abhay to fix mobile responsive layout.\"**\n• **\"Change task 25 priority to high.\"**\n• **\"Update the deadline of task 25 to 20 October 2026.\"**\n• **\"Show me all pending tasks assigned to Abhay.\"**\n• **\"Show all open task flags.\"**\n• **\"Show pending emergency requests.\"**\n• **\"Generate today\'s employee work summary.\"**', NULL, '2026-10-14 17:13:22'),
(11, 10, 'conv_fsbfy265ud', 'user', 'hi', NULL, '2026-10-16 10:44:32'),
(12, 10, 'conv_fsbfy265ud', 'assistant', '🤖 **AI Workflow Assistant**\n\nI am ready to assist you. Tell me what action you want to take, and I will prepare a structured **Implementation Plan** for your review before executing it.\n\n**Try these commands**:\n• **\"Assign the homepage development task to Abhay with a deadline of tomorrow.\"**\n• **\"Create a task for Abhay to fix mobile responsive layout.\"**\n• **\"Change task 25 priority to high.\"**\n• **\"Update the deadline of task 25 to 20 October 2026.\"**\n• **\"Show me all pending tasks assigned to Abhay.\"**\n• **\"Show all open task flags.\"**\n• **\"Show pending emergency requests.\"**\n• **\"Generate today\'s employee work summary.\"**', NULL, '2026-10-16 10:44:32'),
(13, 5, 'conv_anbrvwapi2', 'user', 'JI]AlVBo!jl^Ci1=', NULL, '2026-10-21 16:15:47'),
(14, 5, 'conv_anbrvwapi2', 'assistant', '🤖 **AI Workflow Assistant**\n\nI am ready to assist you. Tell me what action you want to take, and I will prepare a structured **Implementation Plan** for your review before executing it.\n\n**Try these commands**:\n• **\"Assign the homepage development task to Abhay with a deadline of tomorrow.\"**\n• **\"Create a task for Abhay to fix mobile responsive layout.\"**\n• **\"Change task 25 priority to high.\"**\n• **\"Update the deadline of task 25 to 20 October 2026.\"**\n• **\"Show me all pending tasks assigned to Abhay.\"**\n• **\"Show all open task flags.\"**\n• **\"Show pending emergency requests.\"**\n• **\"Generate today\'s employee work summary.\"**', NULL, '2026-10-21 16:15:47');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT 0,
  `user_id` int(11) DEFAULT 0,
  `date` date NOT NULL,
  `shift_start` varchar(10) NOT NULL DEFAULT '09:40',
  `shift_end` varchar(10) NOT NULL DEFAULT '18:30',
  `clock_in` varchar(50) DEFAULT NULL,
  `clock_out` varchar(50) DEFAULT NULL,
  `late_minutes` int(11) DEFAULT 0,
  `early_exit_minutes` int(11) DEFAULT 0,
  `total_seconds` int(11) DEFAULT 0,
  `break_seconds` int(11) DEFAULT 0,
  `status` varchar(50) NOT NULL DEFAULT 'not_started',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `clock_in_ip` varchar(50) DEFAULT NULL,
  `clock_out_ip` varchar(50) DEFAULT NULL,
  `device_type` varchar(50) DEFAULT 'Desktop',
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `user_id`, `date`, `shift_start`, `shift_end`, `clock_in`, `clock_out`, `late_minutes`, `early_exit_minutes`, `total_seconds`, `break_seconds`, `status`, `notes`, `created_at`, `updated_at`, `clock_in_ip`, `clock_out_ip`, `device_type`, `user_agent`) VALUES
(1, 0, 9, '2026-08-19', '09:40', '18:30', '12:51:22', '18:33:28', 191, 0, 20526, 0, 'half_day', NULL, '2026-08-19 12:51:22', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(2, 0, 8, '2026-08-19', '09:40', '18:30', '12:53:01', '18:33:09', 193, 0, 20408, 0, 'half_day', NULL, '2026-08-19 12:53:01', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(3, 0, 6, '2026-08-19', '09:40', '18:30', '12:53:15', '18:33:15', 193, 0, 20400, 0, 'half_day', NULL, '2026-08-19 12:53:15', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(4, 0, 7, '2026-08-20', '09:40', '18:30', '09:53:14', '10:27:07', 13, 482, 88433, 0, 'half_day', NULL, '2026-08-20 09:53:14', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(5, 0, 8, '2026-08-20', '09:40', '18:30', '09:54:07', '09:52:05', 14, 517, 86278, 0, 'half_day', NULL, '2026-08-20 09:54:07', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(6, 0, 9, '2026-08-20', '09:40', '18:30', '09:58:04', '18:31:34', 18, 0, 30810, 0, 'full_day', NULL, '2026-08-20 09:58:04', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(7, 0, 6, '2026-08-20', '09:40', '18:30', '09:58:44', '18:30:03', 18, 0, 30679, 0, 'full_day', NULL, '2026-08-20 09:58:44', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(8, 0, 6, '2026-08-21', '09:40', '18:30', '09:42:51', '19:00:00', 2, 0, 33429, 0, 'full_day', NULL, '2026-08-21 09:42:51', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(9, 0, 8, '2026-08-21', '09:40', '18:30', '09:52:11', '19:00:00', 12, 0, 32869, 0, 'full_day', NULL, '2026-08-21 09:52:11', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(10, 0, 9, '2026-08-21', '09:40', '18:30', '10:21:34', '19:00:00', 41, 0, 31106, 0, 'half_day', NULL, '2026-08-21 10:21:34', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(11, 0, 7, '2026-08-21', '09:40', '18:30', '10:27:12', '19:00:00', 47, 0, 30768, 0, 'half_day', NULL, '2026-08-21 10:27:12', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(12, 0, 10, '2026-08-21', '09:40', '18:30', '10:37:02', '19:00:00', 57, 0, 30178, 0, 'half_day', NULL, '2026-08-21 10:37:02', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(13, 0, 6, '2026-08-24', '09:40', '18:30', '12:03:53', '19:00:00', 143, 0, 24967, 0, 'half_day', NULL, '2026-08-24 12:03:53', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(14, 0, 9, '2026-08-24', '09:40', '18:30', '12:04:20', '19:00:00', 144, 0, 24940, 0, 'half_day', NULL, '2026-08-24 12:04:20', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(15, 0, 9, '2026-08-25', '09:40', '18:30', '10:52:41', '18:29:29', 72, 0, 27408, 0, 'half_day', NULL, '2026-08-25 10:52:41', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(16, 0, 6, '2026-08-25', '09:40', '18:30', '10:52:45', '19:00:00', 72, 0, 29235, 0, 'half_day', NULL, '2026-08-25 10:52:45', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(17, 0, 9, '2026-08-26', '09:40', '18:30', '09:56:21', '18:30:34', 16, 0, 30853, 0, 'half_day', NULL, '2026-08-26 09:56:21', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(18, 0, 7, '2026-08-26', '09:40', '18:30', '10:26:46', '19:00:00', 46, 0, 30794, 0, 'half_day', NULL, '2026-08-26 10:26:46', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(19, 0, 8, '2026-08-27', '09:40', '18:30', '09:53:43', '19:00:00', 13, 0, 32777, 0, 'full_day', NULL, '2026-08-27 09:53:43', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(20, 0, 6, '2026-08-27', '09:40', '18:30', '10:11:32', '19:00:00', 31, 0, 31708, 0, 'half_day', NULL, '2026-08-27 10:11:32', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(21, 0, 9, '2026-08-27', '09:40', '18:30', '10:17:17', '19:00:00', 37, 0, 31363, 0, 'half_day', NULL, '2026-08-27 10:17:17', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(22, 0, 7, '2026-08-27', '09:40', '18:30', '10:23:31', '19:00:00', 43, 0, 30989, 0, 'half_day', NULL, '2026-08-27 10:23:31', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(23, 0, 6, '2026-08-29', '09:40', '18:30', '09:43:41', '19:00:00', 3, 0, 33379, 0, 'full_day', NULL, '2026-08-29 09:43:41', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(24, 0, 9, '2026-08-29', '09:40', '18:30', '09:53:00', '19:00:00', 13, 0, 32820, 0, 'full_day', NULL, '2026-08-29 09:53:00', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(25, 0, 8, '2026-08-29', '09:40', '18:30', '09:58:59', '19:00:00', 18, 0, 32461, 0, 'full_day', NULL, '2026-08-29 09:58:59', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(26, 0, 7, '2026-08-29', '09:40', '18:30', '10:42:10', '19:00:00', 62, 0, 29870, 0, 'half_day', NULL, '2026-08-29 10:42:10', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(27, 0, 8, '2026-08-31', '09:40', '18:30', '09:54:03', '19:00:00', 14, 0, 32757, 0, 'full_day', NULL, '2026-08-31 09:54:03', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(28, 0, 6, '2026-08-31', '09:40', '18:30', '10:10:34', '19:00:00', 30, 0, 31766, 0, 'half_day', NULL, '2026-08-31 10:10:34', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(29, 0, 9, '2026-08-31', '09:40', '18:30', '10:10:51', '19:00:00', 30, 0, 31749, 0, 'half_day', NULL, '2026-08-31 10:10:51', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(30, 0, 4, '2026-09-01', '09:40', '18:30', '09:26:22', '19:00:00', 0, 0, 34418, 0, 'full_day', NULL, '2026-09-01 09:26:22', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(31, 0, 7, '2026-09-01', '09:40', '18:30', '09:46:06', '19:00:00', 6, 0, 33234, 0, 'full_day', NULL, '2026-09-01 09:46:06', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(32, 0, 6, '2026-09-01', '09:40', '18:30', '09:50:51', '19:00:00', 10, 0, 32949, 0, 'full_day', NULL, '2026-09-01 09:50:51', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(33, 0, 8, '2026-09-01', '09:40', '18:30', '09:59:19', '19:00:00', 19, 0, 32441, 0, 'full_day', NULL, '2026-09-01 09:59:19', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(34, 0, 9, '2026-09-01', '09:40', '18:30', '10:01:40', '18:25:30', 21, 4, 30230, 0, 'half_day', NULL, '2026-09-01 10:01:40', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(35, 0, 4, '2026-09-02', '09:40', '18:30', '09:30:47', '19:00:00', 0, 0, 34153, 0, 'full_day', NULL, '2026-09-02 09:30:47', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(36, 0, 7, '2026-09-02', '09:40', '18:30', '09:45:54', '19:00:00', 5, 0, 33246, 0, 'full_day', NULL, '2026-09-02 09:45:54', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(37, 0, 9, '2026-09-02', '09:40', '18:30', '09:55:25', '19:00:00', 15, 0, 32675, 0, 'full_day', NULL, '2026-09-02 09:55:25', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(38, 0, 6, '2026-09-02', '09:40', '18:30', '09:57:07', '19:00:00', 17, 0, 32573, 0, 'full_day', NULL, '2026-09-02 09:57:07', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(39, 0, 8, '2026-09-02', '09:40', '18:30', '10:46:27', '19:00:00', 66, 0, 29613, 0, 'half_day', NULL, '2026-09-02 10:46:27', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(40, 0, 4, '2026-09-03', '09:40', '18:30', '09:33:41', '19:00:00', 0, 0, 33979, 0, 'full_day', NULL, '2026-09-03 09:33:41', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(41, 0, 7, '2026-09-03', '09:40', '18:30', '09:40:19', '19:00:00', 0, 0, 33581, 0, 'full_day', NULL, '2026-09-03 09:40:19', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(42, 0, 6, '2026-09-03', '09:40', '18:30', '10:03:07', '19:00:00', 23, 0, 32213, 0, 'full_day', NULL, '2026-09-03 10:03:07', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(43, 0, 9, '2026-09-03', '09:40', '18:30', '10:08:58', '19:00:00', 28, 0, 31862, 0, 'full_day', NULL, '2026-09-03 10:08:58', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(44, 0, 9, '2026-09-04', '09:40', '18:30', '09:40:47', '19:00:00', 0, 0, 33553, 0, 'full_day', NULL, '2026-09-04 09:40:47', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(45, 0, 6, '2026-09-04', '09:40', '18:30', '09:43:17', '19:00:00', 3, 0, 33403, 0, 'full_day', NULL, '2026-09-04 09:43:17', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(46, 0, 7, '2026-09-04', '09:40', '18:30', '09:47:09', '19:00:00', 7, 0, 33171, 0, 'full_day', NULL, '2026-09-04 09:47:09', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(47, 0, 8, '2026-09-04', '09:40', '18:30', '09:59:31', '19:00:00', 19, 0, 32429, 0, 'full_day', NULL, '2026-09-04 09:59:31', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(48, 0, 7, '2026-09-05', '09:40', '18:30', '09:53:59', '19:00:00', 13, 0, 32761, 0, 'full_day', NULL, '2026-09-05 09:53:59', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(49, 0, 9, '2026-09-05', '09:40', '18:30', '10:01:59', '19:00:00', 21, 0, 32281, 0, 'full_day', NULL, '2026-09-05 10:01:59', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(50, 0, 8, '2026-09-05', '09:40', '18:30', '10:53:02', '19:00:00', 73, 0, 29218, 0, 'half_day', NULL, '2026-09-05 10:53:02', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(51, 0, 9, '2026-09-07', '09:40', '18:30', '09:57:23', '19:00:00', 17, 0, 32557, 0, 'full_day', NULL, '2026-09-07 09:57:23', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(52, 0, 6, '2026-09-07', '09:40', '18:30', '10:06:46', '19:00:00', 26, 0, 31994, 0, 'full_day', NULL, '2026-09-07 10:06:46', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(53, 0, 6, '2026-09-08', '09:40', '18:30', '09:52:52', '19:00:00', 12, 0, 32828, 0, 'full_day', NULL, '2026-09-08 09:52:52', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(54, 0, 8, '2026-09-08', '09:40', '18:30', '09:58:29', '19:00:00', 18, 0, 32491, 0, 'full_day', NULL, '2026-09-08 09:58:29', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(55, 0, 9, '2026-09-09', '09:40', '18:30', '09:50:06', '19:00:00', 10, 0, 32994, 0, 'full_day', NULL, '2026-09-09 09:50:06', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(56, 0, 8, '2026-09-09', '09:40', '18:30', '09:55:47', '19:00:00', 15, 0, 32653, 0, 'full_day', NULL, '2026-09-09 09:55:47', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(57, 0, 6, '2026-09-09', '09:40', '18:30', '16:06:35', '19:00:00', 386, 0, 10405, 0, 'half_day', NULL, '2026-09-09 16:06:35', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(58, 0, 9, '2026-09-10', '09:40', '18:30', '09:53:25', '19:00:00', 13, 0, 32795, 0, 'full_day', NULL, '2026-09-10 09:53:25', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(59, 0, 8, '2026-09-10', '09:40', '18:30', '09:58:03', '19:00:00', 18, 0, 32517, 0, 'full_day', NULL, '2026-09-10 09:58:03', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(60, 0, 7, '2026-09-10', '09:40', '18:30', '10:01:59', '19:00:00', 21, 0, 32281, 0, 'full_day', NULL, '2026-09-10 10:01:59', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(61, 0, 6, '2026-09-10', '09:40', '18:30', '10:03:20', '19:00:00', 23, 0, 32200, 0, 'full_day', NULL, '2026-09-10 10:03:20', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(62, 0, 7, '2026-09-11', '09:40', '18:30', '09:48:11', '19:00:00', 8, 0, 33109, 0, 'full_day', NULL, '2026-09-11 09:48:11', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(63, 0, 9, '2026-09-11', '09:40', '18:30', '09:57:29', '19:00:00', 17, 0, 32551, 0, 'full_day', NULL, '2026-09-11 09:57:29', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(64, 0, 8, '2026-09-11', '09:40', '18:30', '10:02:26', '19:00:00', 22, 0, 32254, 0, 'full_day', NULL, '2026-09-11 10:02:26', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(65, 0, 6, '2026-09-11', '09:40', '18:30', '10:08:24', '19:00:00', 28, 0, 31896, 0, 'full_day', NULL, '2026-09-11 10:08:24', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(66, 0, 6, '2026-09-12', '09:40', '18:30', '09:52:32', '19:00:00', 12, 0, 32848, 0, 'full_day', NULL, '2026-09-12 09:52:32', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(67, 0, 7, '2026-09-12', '09:40', '18:30', '09:55:11', '19:00:00', 15, 0, 32689, 0, 'full_day', NULL, '2026-09-12 09:55:11', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(68, 0, 9, '2026-09-12', '09:40', '18:30', '10:03:52', '19:00:00', 23, 0, 32168, 0, 'full_day', NULL, '2026-09-12 10:03:52', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(69, 0, 7, '2026-09-14', '09:40', '18:30', '10:02:37', '19:00:00', 22, 0, 32243, 0, 'full_day', NULL, '2026-09-14 10:02:37', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(70, 0, 8, '2026-09-14', '09:40', '18:30', '10:04:40', '18:29:06', 24, 0, 30266, 0, 'half_day', NULL, '2026-09-14 10:04:40', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(71, 0, 6, '2026-09-14', '09:40', '18:30', '10:12:19', '19:00:00', 32, 0, 31661, 0, 'half_day', NULL, '2026-09-14 10:12:19', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(72, 0, 9, '2026-09-14', '09:40', '18:30', '10:16:25', '19:00:00', 36, 0, 31415, 0, 'half_day', NULL, '2026-09-14 10:16:25', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(73, 0, 7, '2026-09-15', '09:40', '18:30', '09:52:46', '19:00:00', 12, 0, 32834, 0, 'full_day', NULL, '2026-09-15 09:52:46', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(74, 0, 6, '2026-09-15', '09:40', '18:30', '09:55:51', '19:00:00', 15, 0, 32649, 0, 'full_day', NULL, '2026-09-15 09:55:51', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(75, 0, 8, '2026-09-15', '09:40', '18:30', '09:58:49', '19:00:00', 18, 0, 32471, 0, 'full_day', NULL, '2026-09-15 09:58:49', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(76, 0, 9, '2026-09-15', '09:40', '18:30', '10:03:30', '19:00:00', 23, 0, 32190, 0, 'full_day', NULL, '2026-09-15 10:03:30', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(77, 0, 7, '2026-09-16', '09:40', '18:30', '09:50:21', '19:00:00', 10, 0, 32979, 0, 'full_day', NULL, '2026-09-16 09:50:21', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(78, 0, 9, '2026-09-16', '09:40', '18:30', '09:52:28', '19:00:00', 12, 0, 32852, 0, 'full_day', NULL, '2026-09-16 09:52:28', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(79, 0, 6, '2026-09-16', '09:40', '18:30', '09:54:53', '19:00:00', 14, 0, 32707, 0, 'full_day', NULL, '2026-09-16 09:54:53', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(80, 0, 8, '2026-09-16', '09:40', '18:30', '09:58:03', '19:00:00', 18, 0, 32517, 0, 'full_day', NULL, '2026-09-16 09:58:03', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(81, 0, 8, '2026-09-17', '09:40', '18:30', '09:59:39', '19:00:00', 19, 0, 32421, 0, 'full_day', NULL, '2026-09-17 09:59:39', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(82, 0, 6, '2026-09-17', '09:40', '18:30', '10:04:56', '19:00:00', 24, 0, 32104, 0, 'full_day', NULL, '2026-09-17 10:04:56', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(83, 0, 9, '2026-09-17', '09:40', '18:30', '10:18:15', '19:00:00', 38, 0, 31305, 0, 'half_day', NULL, '2026-09-17 10:18:15', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(84, 0, 6, '2026-09-18', '09:40', '18:30', '10:03:55', '19:00:00', 23, 0, 32165, 0, 'full_day', NULL, '2026-09-18 10:03:55', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(85, 0, 9, '2026-09-18', '09:40', '18:30', '10:16:58', '19:00:00', 36, 0, 31382, 0, 'half_day', NULL, '2026-09-18 10:16:58', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(86, 0, 8, '2026-09-18', '09:40', '18:30', '10:33:47', '18:30:20', 53, 0, 28593, 0, 'half_day', NULL, '2026-09-18 10:33:47', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(87, 0, 6, '2026-09-19', '09:40', '18:30', '09:44:04', '19:00:00', 4, 0, 33356, 0, 'full_day', NULL, '2026-09-19 09:44:04', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(88, 0, 8, '2026-09-19', '09:40', '18:30', '10:02:26', '19:00:00', 22, 0, 32254, 0, 'full_day', NULL, '2026-09-19 10:02:26', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(89, 0, 9, '2026-09-19', '09:40', '18:30', '10:10:54', '19:00:00', 30, 0, 31746, 0, 'half_day', NULL, '2026-09-19 10:10:54', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(90, 0, 8, '2026-09-21', '09:40', '18:30', '09:56:47', '19:00:00', 16, 0, 32593, 0, 'full_day', NULL, '2026-09-21 09:56:47', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(91, 0, 9, '2026-09-21', '09:40', '18:30', '10:10:02', '19:00:00', 30, 0, 31798, 0, 'half_day', NULL, '2026-09-21 10:10:02', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(92, 0, 6, '2026-09-21', '09:40', '18:30', '10:15:13', '19:00:00', 35, 0, 31487, 0, 'half_day', NULL, '2026-09-21 10:15:13', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(93, 0, 6, '2026-09-22', '09:40', '18:30', '09:40:00', '19:00:00', 0, 0, 33600, 0, 'full_day', NULL, '2026-09-22 09:40:00', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(94, 0, 9, '2026-09-22', '09:40', '18:30', '09:52:02', '19:00:00', 12, 0, 32878, 0, 'full_day', NULL, '2026-09-22 09:52:02', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(95, 0, 8, '2026-09-22', '09:40', '18:30', '09:58:09', '19:00:00', 18, 0, 32511, 0, 'full_day', NULL, '2026-09-22 09:58:09', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(96, 0, 9, '2026-09-23', '09:40', '18:30', '09:46:03', '19:00:00', 6, 0, 33237, 0, 'full_day', NULL, '2026-09-23 09:46:03', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(97, 0, 8, '2026-09-23', '09:40', '18:30', '09:55:21', '19:00:00', 15, 0, 32679, 0, 'full_day', NULL, '2026-09-23 09:55:21', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(98, 0, 6, '2026-09-23', '09:40', '18:30', '10:03:42', '19:00:00', 23, 0, 32178, 0, 'full_day', NULL, '2026-09-23 10:03:42', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(99, 0, 7, '2026-09-23', '09:40', '18:30', '10:21:24', '19:00:00', 41, 0, 31116, 0, 'half_day', NULL, '2026-09-23 10:21:24', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(100, 0, 6, '2026-09-24', '09:40', '18:30', '09:54:32', '19:00:00', 14, 0, 32728, 0, 'full_day', NULL, '2026-09-24 09:54:32', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(101, 0, 9, '2026-09-24', '09:40', '18:30', '09:54:54', '19:00:00', 14, 0, 32706, 0, 'full_day', NULL, '2026-09-24 09:54:54', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(102, 0, 8, '2026-09-24', '09:40', '18:30', '10:04:58', '19:00:00', 24, 0, 32102, 0, 'full_day', NULL, '2026-09-24 10:04:58', '2026-09-25 12:09:49', NULL, NULL, 'Desktop', NULL),
(103, 0, 10, '2026-09-25', '09:40', '18:30', '09:40:05', '18:44:31', 0, 0, 32666, 0, 'full_day', NULL, '2026-09-25 09:40:05', '2026-09-25 18:44:31', NULL, NULL, 'Desktop', NULL),
(104, 0, 9, '2026-09-25', '09:40', '18:30', '09:43:22', '19:00:00', 3, 0, 33398, 0, 'full_day', NULL, '2026-09-25 09:43:22', '2026-09-26 10:35:43', NULL, NULL, 'Desktop', NULL),
(105, 0, 8, '2026-09-25', '09:40', '18:30', '09:45:37', '19:00:00', 5, 0, 33263, 0, 'full_day', NULL, '2026-09-25 09:45:37', '2026-09-26 10:35:43', NULL, NULL, 'Desktop', NULL),
(106, 0, 7, '2026-09-25', '09:40', '18:30', '09:46:57', '19:00:00', 6, 0, 33183, 0, 'full_day', NULL, '2026-09-25 09:46:57', '2026-09-26 10:00:59', NULL, NULL, 'Desktop', NULL),
(107, 0, 6, '2026-09-25', '09:40', '18:30', '09:51:21', '19:00:00', 11, 0, 32919, 0, 'full_day', NULL, '2026-09-25 09:51:21', '2026-09-26 10:08:55', NULL, NULL, 'Desktop', NULL),
(110, 0, 7, '2026-09-26', '09:40', '18:30', '10:01:01', '19:00:00', 21, 0, 32339, 0, 'full_day', NULL, '2026-09-26 10:01:01', '2026-09-28 09:42:53', NULL, NULL, 'Desktop', NULL),
(111, 0, 6, '2026-09-26', '09:40', '18:30', '10:08:58', '19:00:00', 28, 0, 31862, 0, 'full_day', NULL, '2026-09-26 10:08:58', '2026-09-28 09:42:53', NULL, NULL, 'Desktop', NULL),
(112, 0, 4, '2026-09-26', '09:40', '18:30', '10:35:04', '10:36:05', 55, 473, 61, 0, 'half_day', NULL, '2026-09-26 10:35:04', '2026-09-26 10:36:05', NULL, NULL, 'Desktop', NULL),
(113, 0, 4, '2026-09-26', '09:40', '18:30', '10:36:43', '10:39:05', 56, 470, 142, 0, 'half_day', NULL, '2026-09-26 10:36:43', '2026-09-26 10:39:05', NULL, NULL, 'Desktop', NULL),
(114, 0, 10, '2026-09-26', '09:40', '18:30', '10:39:36', '19:00:00', 59, 0, 30024, 0, 'half_day', NULL, '2026-09-26 10:39:36', '2026-09-28 09:42:53', NULL, NULL, 'Desktop', NULL),
(115, 0, 4, '2026-09-26', '09:40', '18:30', '10:49:27', '11:05:42', 69, 444, 975, 0, 'half_day', NULL, '2026-09-26 10:49:27', '2026-09-26 11:05:42', NULL, NULL, 'Desktop', NULL),
(116, 0, 4, '2026-09-26', '09:40', '18:30', '11:07:22', '19:00:00', 87, 0, 28358, 0, 'half_day', NULL, '2026-09-26 11:07:22', '2026-09-28 09:42:53', NULL, NULL, 'Desktop', NULL),
(117, 0, 9, '2026-09-28', '09:40', '18:30', '09:42:46', '2026-09-28 19:00:00', 2, 0, 33434, 0, 'full_day', NULL, '2026-09-28 09:42:46', '2026-09-29 09:35:31', NULL, NULL, 'Desktop', NULL),
(118, 0, 6, '2026-09-28', '09:40', '18:30', '09:48:51', '2026-09-28 19:00:00', 8, 0, 33069, 0, 'full_day', NULL, '2026-09-28 09:48:51', '2026-09-29 09:35:43', NULL, NULL, 'Desktop', NULL),
(119, 0, 10, '2026-09-28', '09:40', '18:30', '09:58:57', '2026-09-28 19:00:00', 18, 0, 32463, 0, 'full_day', NULL, '2026-09-28 09:58:57', '2026-09-29 09:35:43', NULL, NULL, 'Desktop', NULL),
(120, 0, 7, '2026-09-28', '09:40', '18:30', '10:54:48', '2026-09-28 19:00:00', 74, 0, 29112, 0, 'half_day', NULL, '2026-09-28 10:54:48', '2026-09-29 09:35:43', NULL, NULL, 'Desktop', NULL),
(121, 1, 1, '2026-09-28', '09:40', '18:30', '2026-09-28 16:35:42', '2026-09-28 16:36:03', 415, 113, 21, 0, 'half_day', NULL, '2026-09-28 16:35:42', '2026-09-28 16:36:03', NULL, NULL, 'Desktop', NULL),
(122, 2, 2, '2026-09-28', '09:40', '18:30', '2026-09-28 16:53:38', '2026-09-28 19:00:00', 433, 0, 7582, 0, 'half_day', NULL, '2026-09-28 16:53:38', '2026-09-29 09:35:43', NULL, NULL, 'Desktop', NULL),
(123, 9, 9, '2026-09-29', '09:40', '18:30', '2026-09-29 09:35:36', '2026-09-29 19:00:00', 0, 0, 33864, 0, 'full_day', NULL, '2026-09-29 09:35:36', '2026-09-30 09:45:18', NULL, NULL, 'Desktop', NULL),
(124, 12, 12, '2026-09-29', '09:40', '18:30', '2026-09-29 09:48:31', '2026-09-29 19:00:00', 8, 0, 33089, 0, 'full_day', NULL, '2026-09-29 09:48:31', '2026-09-30 09:40:42', NULL, NULL, 'Desktop', NULL),
(125, 7, 7, '2026-09-29', '09:40', '18:30', '2026-09-29 09:51:25', '2026-09-29 19:00:00', 11, 0, 32915, 0, 'full_day', NULL, '2026-09-29 09:51:25', '2026-09-30 09:45:18', NULL, NULL, 'Desktop', NULL),
(126, 6, 6, '2026-09-29', '09:40', '18:30', '2026-09-29 09:56:47', '2026-09-29 19:00:00', 16, 0, 32593, 0, 'full_day', NULL, '2026-09-29 09:56:47', '2026-09-30 09:45:18', NULL, NULL, 'Desktop', NULL),
(127, 12, 12, '2026-09-30', '09:40', '18:30', '2026-09-30 09:40:50', '2026-09-30 18:29:29', 0, 0, 31719, 0, 'half_day', NULL, '2026-09-30 09:40:50', '2026-09-30 18:29:29', NULL, NULL, 'Desktop', NULL),
(128, 9, 9, '2026-09-30', '09:40', '18:30', '2026-09-30 09:53:30', '2026-09-30 19:00:00', 13, 0, 32790, 0, 'full_day', NULL, '2026-09-30 09:53:31', '2026-10-01 09:39:52', NULL, NULL, 'Desktop', NULL),
(129, 6, 6, '2026-09-30', '09:40', '18:30', '2026-09-30 09:54:57', '2026-09-30 19:00:00', 14, 0, 32703, 0, 'full_day', NULL, '2026-09-30 09:54:57', '2026-10-01 09:42:16', NULL, NULL, 'Desktop', NULL),
(130, 8, 8, '2026-09-30', '09:40', '18:30', '2026-09-30 09:57:04', '2026-09-30 19:00:00', 17, 0, 32576, 0, 'full_day', NULL, '2026-09-30 09:57:04', '2026-10-01 09:42:16', NULL, NULL, 'Desktop', NULL),
(131, 12, 12, '2026-10-01', '09:40', '18:30', '2026-10-01 09:39:51', '2026-10-01 18:30:11', 0, 0, 31820, 0, 'full_day', NULL, '2026-10-01 09:39:51', '2026-10-01 18:30:11', NULL, NULL, 'Desktop', NULL),
(132, 9, 9, '2026-10-01', '09:40', '18:30', '2026-10-01 09:39:54', '2026-10-01 19:00:00', 0, 0, 33606, 0, 'full_day', NULL, '2026-10-01 09:39:54', '2026-10-02 09:37:57', NULL, NULL, 'Desktop', NULL),
(133, 8, 8, '2026-10-01', '09:40', '18:30', '2026-10-01 09:45:37', '2026-10-01 19:00:00', 5, 0, 33263, 0, 'full_day', NULL, '2026-10-01 09:45:37', '2026-10-02 09:39:30', NULL, NULL, 'Desktop', NULL),
(134, 10, 10, '2026-10-01', '09:40', '18:30', '2026-10-01 09:47:22', '2026-10-01 19:00:00', 7, 0, 33158, 0, 'full_day', NULL, '2026-10-01 09:47:22', '2026-10-02 09:41:08', NULL, NULL, 'Desktop', NULL),
(135, 7, 7, '2026-10-01', '09:40', '18:30', '2026-10-01 09:50:52', '2026-10-01 19:00:00', 10, 0, 32948, 0, 'full_day', NULL, '2026-10-01 09:50:52', '2026-10-02 09:41:27', NULL, NULL, 'Desktop', NULL),
(136, 6, 6, '2026-10-01', '09:40', '18:30', '2026-10-01 10:09:46', '2026-10-01 19:00:00', 29, 0, 31814, 0, 'full_day', NULL, '2026-10-01 10:09:46', '2026-10-02 09:41:27', NULL, NULL, 'Desktop', NULL),
(137, 12, 12, '2026-10-02', '09:40', '18:30', '2026-10-02 09:34:35', '2026-10-02 18:31:36', 0, 0, 32221, 0, 'full_day', NULL, '2026-10-02 09:34:35', '2026-10-02 18:31:36', NULL, NULL, 'Desktop', NULL),
(138, 9, 9, '2026-10-02', '09:40', '18:30', '2026-10-02 09:37:59', '2026-10-02 19:00:00', 0, 0, 33721, 0, 'full_day', NULL, '2026-10-02 09:37:59', '2026-10-05 09:39:59', NULL, NULL, 'Desktop', NULL),
(139, 8, 8, '2026-10-02', '09:40', '18:30', '2026-10-02 09:39:56', '2026-10-02 19:00:00', 0, 0, 33604, 0, 'full_day', NULL, '2026-10-02 09:39:56', '2026-10-05 10:08:33', NULL, NULL, 'Desktop', NULL),
(140, 10, 10, '2026-10-02', '09:40', '18:30', '2026-10-02 09:41:11', '2026-10-02 19:00:00', 1, 0, 33529, 0, 'full_day', NULL, '2026-10-02 09:41:11', '2026-10-05 09:54:48', NULL, NULL, 'Desktop', NULL),
(141, 6, 6, '2026-10-02', '09:40', '18:30', '2026-10-02 09:46:46', '2026-10-02 19:00:00', 6, 0, 33194, 0, 'full_day', NULL, '2026-10-02 09:46:46', '2026-10-05 10:01:42', NULL, NULL, 'Desktop', NULL),
(142, 9, 9, '2026-10-05', '09:40', '18:30', '2026-10-05 09:40:02', '2026-10-05 19:00:00', 0, 0, 33598, 0, 'full_day', NULL, '2026-10-05 09:40:02', '2026-10-06 09:47:40', NULL, NULL, 'Desktop', NULL),
(143, 10, 10, '2026-10-05', '09:40', '18:30', '2026-10-05 09:54:54', '2026-10-05 19:00:00', 14, 0, 32706, 0, 'full_day', NULL, '2026-10-05 09:54:54', '2026-10-06 09:41:03', NULL, NULL, 'Desktop', NULL),
(144, 12, 12, '2026-10-05', '09:40', '18:30', '2026-10-05 09:57:30', '2026-10-05 18:31:07', 17, 0, 30817, 0, 'half_day', NULL, '2026-10-05 09:57:30', '2026-10-05 18:31:07', NULL, NULL, 'Desktop', NULL),
(145, 6, 6, '2026-10-05', '09:40', '18:30', '2026-10-05 10:01:45', '2026-10-05 19:00:00', 21, 0, 32295, 0, 'full_day', NULL, '2026-10-05 10:01:45', '2026-10-06 09:50:24', NULL, NULL, 'Desktop', NULL),
(146, 7, 7, '2026-10-05', '09:40', '18:30', '2026-10-05 10:05:39', '2026-10-05 19:00:00', 40, 0, 32061, 0, 'full_day', NULL, '2026-10-05 10:20:39', '2026-10-06 09:40:22', NULL, NULL, 'Desktop', NULL),
(147, 4, 4, '2026-10-06', '09:40', '18:30', '2026-10-06 09:33:24', '2026-10-06 19:00:00', 0, 0, 33996, 0, 'full_day', NULL, '2026-10-06 09:33:24', '2026-10-07 09:30:50', NULL, NULL, 'Desktop', NULL),
(148, 7, 7, '2026-10-06', '09:40', '18:30', '2026-10-06 09:40:25', '2026-10-06 19:00:00', 0, 0, 33575, 0, 'full_day', NULL, '2026-10-06 09:40:25', '2026-10-07 09:49:50', NULL, NULL, 'Desktop', NULL),
(149, 10, 10, '2026-10-06', '09:40', '18:30', '2026-10-06 09:41:07', '2026-10-06 19:00:00', 1, 0, 33533, 0, 'full_day', NULL, '2026-10-06 09:41:07', '2026-10-07 09:49:50', NULL, NULL, 'Desktop', NULL),
(150, 12, 12, '2026-10-06', '09:40', '18:30', '2026-10-06 09:41:15', '2026-10-06 18:32:58', 1, 0, 31903, 0, 'full_day', NULL, '2026-10-06 09:41:15', '2026-10-06 18:32:58', NULL, NULL, 'Desktop', NULL),
(151, 9, 9, '2026-10-06', '09:40', '18:30', '2026-10-06 09:47:42', '2026-10-06 19:00:00', 7, 0, 33138, 0, 'full_day', NULL, '2026-10-06 09:47:42', '2026-10-07 09:49:50', NULL, NULL, 'Desktop', NULL),
(152, 6, 6, '2026-10-06', '09:40', '18:30', '2026-10-06 09:50:28', '2026-10-06 19:00:00', 10, 0, 32972, 0, 'full_day', NULL, '2026-10-06 09:50:28', '2026-10-07 09:49:50', NULL, NULL, 'Desktop', NULL),
(153, 8, 8, '2026-10-06', '09:40', '18:30', '2026-10-06 09:50:43', '2026-10-06 19:00:00', 10, 0, 32957, 0, 'full_day', NULL, '2026-10-06 09:50:43', '2026-10-07 09:49:50', NULL, NULL, 'Desktop', NULL),
(154, 4, 4, '2026-10-07', '09:40', '18:30', '2026-10-07 09:30:53', '2026-10-07 19:00:00', 0, 0, 34147, 0, 'full_day', NULL, '2026-10-07 09:30:53', '2026-10-08 09:40:13', NULL, NULL, 'Desktop', NULL),
(155, 12, 12, '2026-10-07', '09:40', '18:30', '2026-10-07 09:35:18', '2026-10-07 18:32:25', 0, 0, 32227, 0, 'full_day', NULL, '2026-10-07 09:35:18', '2026-10-07 18:32:25', NULL, NULL, 'Desktop', NULL),
(156, 8, 8, '2026-10-07', '09:40', '18:30', '2026-10-07 09:56:03', '2026-10-07 19:00:00', 16, 0, 32637, 0, 'full_day', NULL, '2026-10-07 09:56:03', '2026-10-08 09:40:13', NULL, NULL, 'Desktop', NULL),
(157, 9, 9, '2026-10-07', '09:40', '18:30', '2026-10-07 10:03:00', '2026-10-07 19:00:00', 23, 0, 32220, 0, 'full_day', NULL, '2026-10-07 10:03:00', '2026-10-08 09:40:02', NULL, NULL, 'Desktop', NULL),
(158, 6, 6, '2026-10-07', '09:40', '18:30', '2026-10-07 10:03:02', '2026-10-07 19:00:00', 23, 0, 32218, 0, 'full_day', NULL, '2026-10-07 10:03:02', '2026-10-08 09:40:13', NULL, NULL, 'Desktop', NULL),
(159, 7, 7, '2026-10-07', '09:40', '18:30', '2026-10-07 09:41:03', '2026-10-07 19:00:00', 32, 0, 33537, 0, 'full_day', NULL, '2026-10-07 10:12:03', '2026-10-08 09:40:13', NULL, NULL, 'Desktop', NULL),
(160, 9, 9, '2026-10-08', '09:40', '18:30', '2026-10-08 09:40:04', '2026-10-08 19:00:00', 0, 0, 33596, 0, 'full_day', NULL, '2026-10-08 09:40:04', '2026-10-09 09:54:37', NULL, NULL, 'Desktop', NULL),
(161, 10, 10, '2026-10-08', '09:40', '18:30', '2026-10-08 09:43:59', '2026-10-08 19:00:00', 3, 0, 33361, 0, 'full_day', NULL, '2026-10-08 09:43:59', '2026-10-09 09:54:24', NULL, NULL, 'Desktop', NULL),
(162, 7, 7, '2026-10-08', '09:40', '18:30', '2026-10-08 09:45:55', '2026-10-08 19:00:00', 5, 0, 33245, 0, 'full_day', NULL, '2026-10-08 09:45:55', '2026-10-09 09:52:47', NULL, NULL, 'Desktop', NULL),
(163, 6, 6, '2026-10-08', '09:40', '18:30', '2026-10-08 09:56:44', '2026-10-08 19:00:00', 16, 0, 32596, 0, 'full_day', NULL, '2026-10-08 09:56:44', '2026-10-09 09:54:37', NULL, NULL, 'Desktop', NULL),
(164, 8, 8, '2026-10-08', '09:40', '18:30', '2026-10-08 10:00:35', '2026-10-08 19:00:00', 20, 0, 32365, 0, 'full_day', NULL, '2026-10-08 10:00:35', '2026-10-09 09:54:37', NULL, NULL, 'Desktop', NULL),
(165, 4, 4, '2026-10-09', '09:40', '18:30', '2026-10-09 09:38:01', '2026-10-09 19:00:00', 0, 0, 33719, 0, 'full_day', NULL, '2026-10-09 09:38:01', '2026-10-10 08:57:29', NULL, NULL, 'Desktop', NULL),
(166, 12, 12, '2026-10-09', '09:40', '18:30', '2026-10-09 09:43:45', '2026-10-09 18:35:28', 3, 0, 31903, 0, 'full_day', NULL, '2026-10-09 09:43:45', '2026-10-09 18:35:28', NULL, NULL, 'Desktop', NULL),
(167, 7, 7, '2026-10-09', '09:40', '18:30', '2026-10-09 09:52:50', '2026-10-09 19:00:00', 12, 0, 32830, 0, 'full_day', NULL, '2026-10-09 09:52:50', '2026-10-10 09:44:05', NULL, NULL, 'Desktop', NULL),
(168, 10, 10, '2026-10-09', '09:40', '18:30', '2026-10-09 09:54:28', '2026-10-09 19:00:00', 14, 0, 32732, 0, 'full_day', NULL, '2026-10-09 09:54:28', '2026-10-10 09:58:54', NULL, NULL, 'Desktop', NULL),
(169, 9, 9, '2026-10-09', '09:40', '18:30', '2026-10-09 10:03:04', '2026-10-09 19:00:00', 23, 0, 32216, 0, 'full_day', NULL, '2026-10-09 10:03:04', '2026-10-10 09:42:13', NULL, NULL, 'Desktop', NULL),
(170, 6, 6, '2026-10-09', '09:40', '18:30', '2026-10-09 10:06:22', '2026-10-09 19:00:00', 26, 0, 32018, 0, 'full_day', NULL, '2026-10-09 10:06:22', '2026-10-10 09:40:40', NULL, NULL, 'Desktop', NULL),
(171, 8, 8, '2026-10-09', '09:40', '18:30', '2026-10-09 13:40:44', '2026-10-09 19:00:00', 240, 0, 19156, 0, 'half_day', NULL, '2026-10-09 13:40:44', '2026-10-10 09:58:54', NULL, NULL, 'Desktop', NULL),
(172, 4, 4, '2026-10-10', '09:40', '18:30', '2026-10-10 09:33:23', '2026-10-10 19:00:00', 0, 0, 33997, 0, 'full_day', NULL, '2026-10-10 09:33:23', '2026-10-12 09:25:06', NULL, NULL, 'Desktop', NULL),
(173, 6, 6, '2026-10-10', '09:40', '18:30', '2026-10-10 09:40:43', '2026-10-10 19:00:00', 0, 0, 33557, 0, 'full_day', NULL, '2026-10-10 09:40:43', '2026-10-12 09:25:06', NULL, NULL, 'Desktop', NULL),
(174, 9, 9, '2026-10-10', '09:40', '18:30', '2026-10-10 09:42:20', '2026-10-10 19:00:00', 2, 0, 33460, 0, 'full_day', NULL, '2026-10-10 09:42:20', '2026-10-12 09:25:06', NULL, NULL, 'Desktop', NULL),
(175, 7, 7, '2026-10-10', '09:40', '18:30', '2026-10-10 09:44:09', '2026-10-10 19:00:00', 4, 0, 33351, 0, 'full_day', NULL, '2026-10-10 09:44:09', '2026-10-12 09:25:06', NULL, NULL, 'Desktop', NULL),
(176, 10, 10, '2026-10-10', '09:40', '18:30', '2026-10-10 9:45:51', '2026-10-10 19:00:00', 20, 0, 33249, 0, 'full_day', NULL, '2026-10-10 10:00:51', '2026-10-12 09:25:06', NULL, NULL, 'Desktop', NULL),
(177, 5, 5, '2026-10-10', '09:40', '18:30', '2026-10-10 09:46:06', '2026-10-10 10:49:20', 53, 460, 3794, 0, 'half_day', NULL, '2026-10-10 10:33:06', '2026-10-10 10:49:20', NULL, NULL, 'Desktop', NULL),
(178, 5, 5, '2026-10-10', '09:40', '18:30', '2026-10-10 10:49:25', '2026-10-10 10:49:32', 69, 460, 7, 0, 'half_day', NULL, '2026-10-10 10:49:25', '2026-10-10 10:49:32', NULL, NULL, 'Desktop', NULL),
(179, 8, 8, '2026-10-10', '09:40', '18:30', '2026-10-10 10:57:08', '2026-10-10 19:00:00', 77, 0, 28972, 0, 'half_day', NULL, '2026-10-10 10:57:08', '2026-10-12 09:25:06', NULL, NULL, 'Desktop', NULL),
(180, 12, 12, '2026-10-12', '09:40', '18:30', '2026-10-12 09:24:55', '2026-10-12 19:00:00', 0, 0, 34505, 0, 'full_day', NULL, '2026-10-12 09:24:55', '2026-10-13 09:31:55', NULL, NULL, 'Desktop', NULL),
(181, 5, 5, '2026-10-12', '09:40', '18:30', '2026-10-12 09:29:42', '2026-10-12 19:00:00', 0, 0, 34218, 0, 'full_day', NULL, '2026-10-12 09:29:42', '2026-10-13 09:31:32', NULL, NULL, 'Desktop', NULL),
(182, 4, 4, '2026-10-12', '09:40', '18:30', '2026-10-12 09:30:54', '2026-10-12 19:00:00', 0, 0, 34146, 0, 'full_day', NULL, '2026-10-12 09:30:54', '2026-10-13 09:31:03', NULL, NULL, 'Desktop', NULL),
(183, 6, 6, '2026-10-12', '09:40', '18:30', '2026-10-12 09:34:46', '2026-10-12 19:00:00', 0, 0, 33914, 0, 'full_day', NULL, '2026-10-12 09:34:46', '2026-10-13 09:26:01', NULL, NULL, 'Desktop', NULL),
(184, 10, 10, '2026-10-12', '09:40', '18:30', '2026-10-12 09:44:28', '2026-10-12 19:00:00', 4, 0, 33332, 0, 'full_day', NULL, '2026-10-12 09:44:28', '2026-10-13 09:31:55', NULL, NULL, 'Desktop', NULL),
(185, 7, 7, '2026-10-12', '09:40', '18:30', '2026-10-12 09:47:09', '2026-10-12 19:00:00', 7, 0, 33171, 0, 'full_day', NULL, '2026-10-12 09:47:09', '2026-10-13 09:31:55', NULL, NULL, 'Desktop', NULL),
(186, 9, 9, '2026-10-12', '09:40', '18:30', '2026-10-12 09:50:11', '2026-10-12 19:00:00', 10, 0, 32989, 0, 'full_day', NULL, '2026-10-12 09:50:11', '2026-10-13 09:31:55', NULL, NULL, 'Desktop', NULL),
(187, 8, 8, '2026-10-12', '09:40', '18:30', '2026-10-12 09:56:55', '2026-10-12 19:00:00', 16, 0, 32585, 0, 'full_day', NULL, '2026-10-12 09:56:55', '2026-10-13 01:04:15', NULL, NULL, 'Desktop', NULL),
(188, 6, 6, '2026-10-13', '09:40', '18:30', '2026-10-13 09:26:03', '2026-10-13 19:00:00', 0, 0, 34437, 0, 'full_day', NULL, '2026-10-13 09:26:03', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(189, 4, 4, '2026-10-13', '09:40', '18:30', '2026-10-13 09:31:05', '2026-10-13 19:00:00', 0, 0, 34135, 0, 'full_day', NULL, '2026-10-13 09:31:06', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(190, 5, 5, '2026-10-13', '09:40', '18:30', '2026-10-13 09:31:37', '2026-10-13 19:00:00', 0, 0, 34103, 0, 'full_day', NULL, '2026-10-13 09:31:37', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(191, 10, 10, '2026-10-13', '09:40', '18:30', '2026-10-13 09:38:54', '2026-10-13 19:00:00', 0, 0, 33666, 0, 'full_day', NULL, '2026-10-13 09:38:54', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(192, 9, 9, '2026-10-13', '09:40', '18:30', '2026-10-13 09:41:39', '2026-10-13 19:00:00', 1, 0, 33501, 0, 'full_day', NULL, '2026-10-13 09:41:39', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(193, 7, 7, '2026-10-13', '09:40', '18:30', '2026-10-13 09:45:01', '2026-10-13 19:00:00', 5, 0, 33299, 0, 'full_day', NULL, '2026-10-13 09:45:01', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(194, 12, 12, '2026-10-13', '09:40', '18:30', '2026-10-13 09:46:22', '2026-10-13 19:00:00', 6, 0, 33218, 0, 'full_day', NULL, '2026-10-13 09:46:22', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(195, 8, 8, '2026-10-13', '09:40', '18:30', '2026-10-13 09:48:54', '2026-10-13 19:00:00', 8, 0, 33066, 0, 'full_day', NULL, '2026-10-13 09:48:54', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(196, 1, 1, '2026-10-13', '09:40', '18:30', '2026-10-13 14:37:16', '2026-10-13 19:00:00', 297, 0, 15764, 0, 'half_day', NULL, '2026-10-13 14:37:16', '2026-10-13 20:21:01', NULL, NULL, 'Desktop', NULL),
(197, 4, 4, '2026-10-14', '09:40', '18:30', '2026-10-14 09:33:39', '2026-10-14 19:00:00', 0, 0, 33981, 0, 'full_day', NULL, '2026-10-14 09:33:39', '2026-10-14 19:02:21', NULL, NULL, 'Desktop', NULL),
(198, 6, 6, '2026-10-14', '09:40', '18:30', '2026-10-14 09:40:38', '2026-10-14 19:00:00', 0, 0, 33562, 0, 'full_day', NULL, '2026-10-14 09:40:38', '2026-10-14 19:02:21', NULL, NULL, 'Desktop', NULL),
(199, 9, 9, '2026-10-14', '09:40', '18:30', '2026-10-14 09:40:54', '2026-10-14 19:00:00', 0, 0, 33546, 0, 'full_day', NULL, '2026-10-14 09:40:54', '2026-10-14 19:02:12', NULL, NULL, 'Desktop', NULL),
(200, 7, 7, '2026-10-14', '09:40', '18:30', '2026-10-14 09:44:52', '2026-10-14 19:00:00', 4, 0, 33308, 0, 'full_day', NULL, '2026-10-14 09:44:52', '2026-10-14 19:02:21', NULL, NULL, 'Desktop', NULL),
(201, 10, 10, '2026-10-14', '09:40', '18:30', '2026-10-14 09:48:52', '2026-10-14 19:00:00', 8, 0, 33068, 0, 'full_day', NULL, '2026-10-14 09:48:52', '2026-10-14 19:02:21', NULL, NULL, 'Desktop', NULL),
(202, 12, 12, '2026-10-14', '09:40', '18:30', '2026-10-14 09:50:28', '2026-10-14 19:00:00', 10, 0, 32972, 0, 'full_day', NULL, '2026-10-14 09:50:28', '2026-10-14 19:02:21', NULL, NULL, 'Desktop', NULL),
(203, 5, 5, '2026-10-14', '09:40', '18:30', '2026-10-14 09:53:54', '2026-10-14 19:00:00', 13, 0, 32766, 0, 'full_day', NULL, '2026-10-14 09:53:54', '2026-10-14 19:02:21', NULL, NULL, 'Desktop', NULL),
(204, 8, 8, '2026-10-14', '09:40', '18:30', '2026-10-14 09:58:24', '2026-10-14 19:00:00', 18, 0, 32496, 0, 'full_day', NULL, '2026-10-14 09:58:24', '2026-10-14 19:02:21', NULL, NULL, 'Desktop', NULL),
(205, 1, 1, '2026-10-14', '09:40', '18:30', '2026-10-14 13:42:55', '2026-10-14 19:00:00', 242, 0, 19025, 0, 'half_day', NULL, '2026-10-14 13:42:55', '2026-10-14 19:00:00', NULL, NULL, 'Desktop', NULL),
(206, 4, 4, '2026-10-15', '09:40', '18:30', '2026-10-15 09:34:43', '2026-10-15 18:33:21', 0, 0, 32318, 0, 'full_day', NULL, '2026-10-15 09:34:43', '2026-10-15 18:33:21', NULL, NULL, 'Desktop', NULL),
(207, 8, 8, '2026-10-15', '09:40', '18:30', '2026-10-15 09:35:02', '2026-10-15 18:32:30', 0, 0, 32248, 0, 'full_day', NULL, '2026-10-15 09:35:02', '2026-10-15 18:32:30', NULL, NULL, 'Desktop', NULL),
(208, 9, 9, '2026-10-15', '09:40', '18:30', '2026-10-15 09:39:22', '2026-10-15 18:32:33', 0, 0, 31991, 0, 'full_day', NULL, '2026-10-15 09:39:22', '2026-10-15 18:32:33', NULL, NULL, 'Desktop', NULL),
(209, 10, 10, '2026-10-15', '09:40', '18:30', '2026-10-15 09:43:07', '2026-10-15 19:00:00', 3, 0, 33413, 0, 'full_day', NULL, '2026-10-15 09:43:07', '2026-10-15 19:07:04', NULL, NULL, 'Desktop', NULL),
(210, 7, 7, '2026-10-15', '09:40', '18:30', '2026-10-15 09:44:02', '2026-10-15 19:00:00', 4, 0, 33358, 0, 'full_day', NULL, '2026-10-15 09:44:02', '2026-10-15 19:07:04', NULL, NULL, 'Desktop', NULL),
(211, 6, 6, '2026-10-15', '09:40', '18:30', '2026-10-15 09:47:21', '2026-10-15 18:31:01', 7, 0, 31420, 0, 'half_day', NULL, '2026-10-15 09:47:21', '2026-10-15 18:31:01', NULL, NULL, 'Desktop', NULL),
(212, 12, 12, '2026-10-15', '09:40', '18:30', '2026-10-15 09:50:00', '2026-10-15 19:00:00', 10, 0, 33000, 0, 'full_day', NULL, '2026-10-15 09:50:00', '2026-10-15 19:07:04', NULL, NULL, 'Desktop', NULL),
(213, 5, 5, '2026-10-15', '09:40', '18:30', '2026-10-15 09:41:54', '2026-10-15 18:31:11', 19, 0, 31757, 0, 'half_day', NULL, '2026-10-15 09:59:54', '2026-10-15 18:31:11', NULL, NULL, 'Desktop', NULL),
(214, 3, 3, '2026-10-15', '09:40', '18:30', '2026-10-15 10:25:06', '2026-10-15 19:00:00', 45, 0, 30894, 0, 'half_day', NULL, '2026-10-15 10:25:07', '2026-10-15 19:07:04', NULL, NULL, 'Desktop', NULL),
(215, 4, 4, '2026-10-16', '09:40', '18:30', '2026-10-16 09:36:35', '2026-10-16 18:30:52', 0, 0, 32057, 0, 'full_day', NULL, '2026-10-16 09:36:35', '2026-10-16 18:30:52', NULL, NULL, 'Desktop', NULL),
(216, 9, 9, '2026-10-16', '09:40', '18:30', '2026-10-16 09:40:09', '2026-10-16 18:31:29', 0, 0, 31880, 0, 'full_day', NULL, '2026-10-16 09:40:09', '2026-10-16 18:31:29', NULL, NULL, 'Desktop', NULL),
(217, 7, 7, '2026-10-16', '09:40', '18:30', '2026-10-16 09:42:01', '2026-10-16 18:30:42', 2, 0, 31721, 0, 'half_day', NULL, '2026-10-16 09:42:01', '2026-10-16 18:30:42', NULL, NULL, 'Desktop', NULL),
(218, 8, 8, '2026-10-16', '09:40', '18:30', '2026-10-16 09:43:09', '2026-10-16 18:31:22', 3, 0, 31693, 0, 'half_day', NULL, '2026-10-16 09:43:09', '2026-10-16 18:31:22', NULL, NULL, 'Desktop', NULL),
(219, 10, 10, '2026-10-16', '09:40', '18:30', '2026-10-16 09:45:13', '2026-10-16 18:33:38', 5, 0, 31705, 0, 'half_day', NULL, '2026-10-16 09:45:13', '2026-10-16 18:33:38', NULL, NULL, 'Desktop', NULL),
(220, 12, 12, '2026-10-16', '09:40', '18:30', '2026-10-16 09:48:53', '2026-10-16 18:36:03', 8, 0, 31630, 0, 'half_day', NULL, '2026-10-16 09:48:53', '2026-10-16 18:36:03', NULL, NULL, 'Desktop', NULL),
(221, 6, 6, '2026-10-16', '09:40', '18:30', '2026-10-16 09:48:55', '2026-10-16 18:31:04', 8, 0, 31329, 0, 'half_day', NULL, '2026-10-16 09:48:55', '2026-10-16 18:31:04', NULL, NULL, 'Desktop', NULL),
(222, 3, 3, '2026-10-16', '09:40', '18:30', '2026-10-16 11:42:56', '2026-10-16 19:00:00', 122, 0, 26224, 0, 'half_day', NULL, '2026-10-16 11:42:56', '2026-10-17 09:36:44', NULL, NULL, 'Desktop', NULL),
(223, 12, 12, '2026-10-17', '09:40', '18:30', '2026-10-17 09:36:33', '2026-10-17 19:00:00', 0, 0, 33807, 0, 'full_day', NULL, '2026-10-17 09:36:33', '2026-10-18 15:08:20', NULL, NULL, 'Desktop', NULL),
(224, 4, 4, '2026-10-17', '09:40', '18:30', '2026-10-17 09:36:37', '2026-10-17 18:31:04', 0, 0, 32067, 0, 'full_day', NULL, '2026-10-17 09:36:37', '2026-10-17 18:31:04', NULL, NULL, 'Desktop', NULL),
(225, 9, 9, '2026-10-17', '09:40', '18:30', '2026-10-17 09:42:21', '2026-10-17 19:00:00', 2, 0, 33459, 0, 'full_day', NULL, '2026-10-17 09:42:21', '2026-10-18 15:08:20', NULL, NULL, 'Desktop', NULL),
(226, 10, 10, '2026-10-17', '09:40', '18:30', '2026-10-17 09:42:33', '2026-10-17 18:35:06', 2, 0, 31953, 0, 'full_day', NULL, '2026-10-17 09:42:33', '2026-10-17 18:35:06', NULL, NULL, 'Desktop', NULL),
(227, 7, 7, '2026-10-17', '09:40', '18:30', '2026-10-17 09:44:10', '2026-10-17 18:31:07', 4, 0, 31617, 0, 'half_day', NULL, '2026-10-17 09:44:10', '2026-10-17 18:31:07', NULL, NULL, 'Desktop', NULL),
(228, 8, 8, '2026-10-17', '09:40', '18:30', '2026-10-17 09:46:45', '2026-10-17 18:31:45', 6, 0, 31500, 0, 'half_day', NULL, '2026-10-17 09:46:45', '2026-10-17 18:31:45', NULL, NULL, 'Desktop', NULL),
(229, 6, 6, '2026-10-17', '09:40', '18:30', '2026-10-17 10:05:18', '2026-10-17 18:31:13', 25, 0, 30355, 0, 'half_day', NULL, '2026-10-17 10:05:18', '2026-10-17 18:31:13', NULL, NULL, 'Desktop', NULL),
(230, 5, 5, '2026-10-17', '09:40', '18:30', '2026-10-17 10:27:25', '2026-10-17 19:00:00', 47, 0, 30755, 0, 'half_day', NULL, '2026-10-17 10:27:25', '2026-10-18 15:08:20', NULL, NULL, 'Desktop', NULL),
(231, 3, 3, '2026-10-17', '09:40', '18:30', '2026-10-17 15:07:25', '2026-10-17 19:00:00', 327, 0, 13955, 0, 'half_day', NULL, '2026-10-17 15:07:25', '2026-10-18 15:08:20', NULL, NULL, 'Desktop', NULL),
(232, 9, 9, '2026-10-19', '09:40', '18:30', '2026-10-19 09:35:39', '2026-10-19 19:00:00', 0, 0, 33861, 0, 'full_day', NULL, '2026-10-19 09:35:39', '2026-10-21 09:34:54', NULL, NULL, 'Desktop', NULL),
(233, 4, 4, '2026-10-19', '09:40', '18:30', '2026-10-19 09:39:14', '2026-10-19 19:00:00', 0, 0, 33646, 0, 'full_day', NULL, '2026-10-19 09:39:14', '2026-10-21 09:43:30', NULL, NULL, 'Desktop', NULL),
(234, 10, 10, '2026-10-19', '09:40', '18:30', '2026-10-19 09:39:41', '2026-10-19 19:00:00', 0, 0, 33619, 0, 'full_day', NULL, '2026-10-19 09:39:41', '2026-10-21 09:31:21', NULL, NULL, 'Desktop', NULL),
(235, 7, 7, '2026-10-19', '09:40', '18:30', '2026-10-19 09:42:49', '2026-10-19 19:00:00', 2, 0, 33431, 0, 'full_day', NULL, '2026-10-19 09:42:49', '2026-10-21 09:25:26', NULL, NULL, 'Desktop', NULL),
(236, 14, 14, '2026-10-19', '09:40', '18:30', '2026-10-19 09:52:06', '2026-10-19 19:00:00', 12, 0, 32874, 0, 'full_day', NULL, '2026-10-19 09:52:06', '2026-10-21 09:43:30', NULL, NULL, 'Desktop', NULL),
(237, 6, 6, '2026-10-19', '09:40', '18:30', '2026-10-19 10:01:40', '2026-10-19 19:00:00', 21, 0, 32300, 0, 'full_day', NULL, '2026-10-19 10:01:40', '2026-10-21 09:43:30', NULL, NULL, 'Desktop', NULL),
(238, 2, 2, '2026-10-19', '09:40', '18:30', '2026-10-19 10:04:50', '2026-10-19 19:00:00', 24, 0, 32110, 0, 'full_day', NULL, '2026-10-19 10:04:50', '2026-10-21 09:43:30', NULL, NULL, 'Desktop', NULL),
(239, 5, 5, '2026-10-19', '09:40', '18:30', '2026-10-19 10:02:21', '2026-10-19 19:00:00', 22, 0, 32259, 0, 'full_day', NULL, '2026-10-19 10:16:21', '2026-10-21 09:21:40', NULL, NULL, 'Desktop', NULL),
(240, 8, 8, '2026-10-19', '09:40', '18:30', '2026-10-19 09:39:20', '2026-10-19 19:00:00', 56, 0, 33640, 0, 'full_day', NULL, '2026-10-19 10:36:20', '2026-10-21 09:43:30', NULL, NULL, 'Desktop', NULL),
(241, 12, 12, '2026-10-19', '09:40', '18:30', '2026-10-19 11:40:06', '2026-10-19 18:31:36', 120, 0, 24690, 0, 'half_day', NULL, '2026-10-19 11:40:06', '2026-10-19 18:31:36', NULL, NULL, 'Desktop', NULL),
(242, 3, 3, '2026-10-19', '09:40', '18:30', '2026-10-19 12:09:14', '2026-10-19 19:00:00', 149, 0, 24646, 0, 'half_day', NULL, '2026-10-19 12:09:14', '2026-10-21 09:43:30', NULL, NULL, 'Desktop', NULL),
(243, 9, 9, '2026-10-21', '09:40', '18:30', '2026-10-21 09:34:57', '2026-10-21 18:36:41', 0, 0, 32504, 0, 'full_day', NULL, '2026-10-21 09:34:57', '2026-10-21 18:36:41', NULL, NULL, 'Desktop', NULL),
(244, 12, 12, '2026-10-21', '09:40', '18:30', '2026-10-21 09:39:48', '2026-10-21 18:31:13', 0, 0, 31885, 0, 'full_day', NULL, '2026-10-21 09:39:48', '2026-10-21 18:31:13', NULL, NULL, 'Desktop', NULL),
(245, 5, 5, '2026-10-21', '09:40', '18:30', '2026-10-21 09:39:55', '2026-10-21 18:30:36', 0, 0, 31841, 0, 'full_day', NULL, '2026-10-21 09:39:55', '2026-10-21 18:30:36', NULL, NULL, 'Desktop', NULL),
(246, 7, 7, '2026-10-21', '09:40', '18:30', '2026-10-21 09:43:23', '2026-10-21 18:35:49', 3, 0, 31946, 0, 'full_day', NULL, '2026-10-21 09:43:23', '2026-10-21 18:35:49', NULL, NULL, 'Desktop', NULL),
(247, 10, 10, '2026-10-21', '09:40', '18:30', '2026-10-21 09:44:29', '2026-10-21 18:34:46', 4, 0, 31817, 0, 'full_day', NULL, '2026-10-21 09:44:29', '2026-10-21 18:34:46', NULL, NULL, 'Desktop', NULL),
(248, 4, 4, '2026-10-21', '09:40', '18:30', '2026-10-21 09:45:04', '2026-10-21 18:31:07', 5, 0, 31563, 0, 'half_day', NULL, '2026-10-21 09:45:04', '2026-10-21 18:31:07', NULL, NULL, 'Desktop', NULL),
(249, 6, 6, '2026-10-21', '09:40', '18:30', '2026-10-21 09:54:47', '2026-10-21 19:00:00', 14, 0, 32713, 0, 'full_day', NULL, '2026-10-21 09:54:47', '2026-10-21 19:57:16', NULL, NULL, 'Desktop', NULL),
(250, 8, 8, '2026-10-21', '09:40', '18:30', '2026-10-21 09:58:34', '2026-10-21 18:31:09', 18, 0, 30755, 0, 'half_day', NULL, '2026-10-21 09:58:34', '2026-10-21 18:31:09', NULL, NULL, 'Desktop', NULL),
(251, 2, 2, '2026-10-21', '09:40', '18:30', '2026-10-21 10:15:50', '2026-10-21 18:41:47', 35, 0, 30357, 0, 'half_day', NULL, '2026-10-21 10:15:50', '2026-10-21 18:41:47', NULL, NULL, 'Desktop', NULL),
(252, 5, 5, '2026-10-22', '09:40', '18:30', '2026-10-22 09:34:03', '2026-10-22 19:00:00', 0, 0, 33957, 0, 'full_day', NULL, '2026-10-22 09:34:03', '2026-10-23 09:48:04', NULL, NULL, 'Desktop', NULL),
(253, 4, 4, '2026-10-22', '09:40', '18:30', '2026-10-22 09:37:45', '2026-10-22 18:30:24', 0, 0, 31959, 0, 'full_day', NULL, '2026-10-22 09:37:45', '2026-10-22 18:30:24', NULL, NULL, 'Desktop', NULL),
(254, 10, 10, '2026-10-22', '09:40', '18:30', '2026-10-22 09:40:41', '2026-10-22 19:00:00', 0, 0, 33559, 0, 'full_day', NULL, '2026-10-22 09:40:41', '2026-10-22 22:14:49', NULL, NULL, 'Desktop', NULL),
(255, 9, 9, '2026-10-22', '09:40', '18:30', '2026-10-22 09:44:24', '2026-10-22 19:00:00', 4, 0, 33336, 0, 'full_day', NULL, '2026-10-22 09:44:24', '2026-10-23 08:18:43', NULL, NULL, 'Desktop', NULL),
(256, 7, 7, '2026-10-22', '09:40', '18:30', '2026-10-22 09:45:15', '2026-10-22 19:00:00', 5, 0, 33285, 0, 'full_day', NULL, '2026-10-22 09:45:15', '2026-10-23 09:36:32', NULL, NULL, 'Desktop', NULL),
(257, 8, 8, '2026-10-22', '09:40', '18:30', '2026-10-22 09:50:18', '2026-10-22 19:00:00', 10, 0, 32982, 0, 'full_day', NULL, '2026-10-22 09:50:18', '2026-10-23 09:45:22', NULL, NULL, 'Desktop', NULL),
(258, 12, 12, '2026-10-22', '09:40', '18:30', '2026-10-22 09:53:38', '2026-10-22 18:31:11', 13, 0, 31053, 0, 'half_day', NULL, '2026-10-22 09:53:38', '2026-10-22 18:31:11', NULL, NULL, 'Desktop', NULL),
(259, 6, 6, '2026-10-22', '09:40', '18:30', '2026-10-22 09:58:37', '2026-10-22 19:00:00', 18, 0, 32483, 0, 'full_day', NULL, '2026-10-22 09:58:37', '2026-10-23 09:48:04', NULL, NULL, 'Desktop', NULL),
(260, 2, 2, '2026-10-22', '09:40', '18:30', '2026-10-22 10:08:39', '2026-10-22 19:00:00', 28, 0, 31881, 0, 'full_day', NULL, '2026-10-22 10:08:39', '2026-10-23 09:48:04', NULL, NULL, 'Desktop', NULL),
(261, 3, 3, '2026-10-22', '09:40', '18:30', '2026-10-22 11:23:59', '2026-10-22 19:00:00', 103, 0, 27361, 0, 'half_day', NULL, '2026-10-22 11:23:59', '2026-10-23 09:48:04', NULL, NULL, 'Desktop', NULL),
(262, 9, 9, '2026-10-23', '09:40', '18:30', '2026-10-23 08:55:21', '2026-10-23 18:29:17', 0, 0, 34436, 0, 'full_day', NULL, '2026-10-23 08:55:21', '2026-10-23 18:29:17', NULL, NULL, 'Desktop', NULL),
(263, 4, 4, '2026-10-23', '09:40', '18:30', '2026-10-23 09:25:56', '2026-10-23 18:31:10', 0, 0, 32714, 0, 'full_day', NULL, '2026-10-23 09:25:56', '2026-10-23 18:31:10', NULL, NULL, 'Desktop', NULL),
(264, 10, 10, '2026-10-23', '09:40', '18:30', '2026-10-23 09:43:25', '2026-10-23 18:33:10', 3, 0, 31785, 0, 'half_day', NULL, '2026-10-23 09:43:25', '2026-10-23 18:33:10', NULL, NULL, 'Desktop', NULL),
(265, 12, 12, '2026-10-23', '09:40', '18:30', '2026-10-23 09:44:03', '2026-10-23 18:36:07', 4, 0, 31924, 0, 'full_day', NULL, '2026-10-23 09:44:03', '2026-10-23 18:36:07', NULL, NULL, 'Desktop', NULL);
INSERT INTO `attendance` (`id`, `employee_id`, `user_id`, `date`, `shift_start`, `shift_end`, `clock_in`, `clock_out`, `late_minutes`, `early_exit_minutes`, `total_seconds`, `break_seconds`, `status`, `notes`, `created_at`, `updated_at`, `clock_in_ip`, `clock_out_ip`, `device_type`, `user_agent`) VALUES
(266, 7, 7, '2026-10-23', '09:40', '18:30', '2026-10-23 09:44:35', '2026-10-23 18:31:45', 4, 0, 31630, 0, 'half_day', NULL, '2026-10-23 09:44:35', '2026-10-23 18:31:45', NULL, NULL, 'Desktop', NULL),
(267, 8, 8, '2026-10-23', '09:40', '18:30', '2026-10-23 09:45:25', '2026-10-23 18:31:33', 5, 0, 31568, 0, 'half_day', NULL, '2026-10-23 09:45:25', '2026-10-23 18:31:33', NULL, NULL, 'Desktop', NULL),
(268, 3, 3, '2026-10-23', '09:40', '18:30', '2026-10-23 10:29:34', '2026-10-23 19:00:00', 49, 0, 30626, 0, 'half_day', NULL, '2026-10-23 10:29:34', '2026-10-24 09:49:01', NULL, NULL, 'Desktop', NULL),
(269, 1, 1, '2026-10-23', '09:40', '18:30', '2026-10-23 11:19:10', '2026-10-23 19:00:00', 99, 0, 27650, 0, 'half_day', NULL, '2026-10-23 11:19:10', '2026-10-24 09:49:01', NULL, NULL, 'Desktop', NULL),
(270, 2, 2, '2026-10-23', '09:40', '18:30', '2026-10-23 11:25:51', '2026-10-23 19:00:00', 105, 0, 27249, 0, 'half_day', NULL, '2026-10-23 11:25:51', '2026-10-24 09:49:01', NULL, NULL, 'Desktop', NULL),
(271, 4, 4, '2026-10-24', '09:40', '18:30', '2026-10-24 09:21:48', '2026-10-24 19:00:00', 0, 0, 34692, 0, 'full_day', NULL, '2026-10-24 09:21:48', '2026-10-25 11:59:43', NULL, NULL, 'Desktop', NULL),
(272, 7, 7, '2026-10-24', '09:40', '18:30', '2026-10-24 09:35:14', '2026-10-24 19:00:00', 0, 0, 33886, 0, 'full_day', NULL, '2026-10-24 09:35:14', '2026-10-26 09:41:20', NULL, NULL, 'Desktop', NULL),
(273, 9, 9, '2026-10-24', '09:40', '18:30', '2026-10-24 09:43:37', '2026-10-24 19:00:00', 3, 0, 33383, 0, 'full_day', NULL, '2026-10-24 09:43:37', '2026-10-26 09:37:32', NULL, NULL, 'Desktop', NULL),
(274, 10, 10, '2026-10-24', '09:40', '18:30', '2026-10-24 09:45:08', '2026-10-24 19:00:00', 5, 0, 33292, 0, 'full_day', NULL, '2026-10-24 09:45:08', '2026-10-26 09:45:51', NULL, NULL, 'Desktop', NULL),
(275, 12, 12, '2026-10-24', '09:40', '18:30', '2026-10-24 09:45:24', '2026-10-24 19:00:00', 5, 0, 33276, 0, 'full_day', NULL, '2026-10-24 09:45:24', '2026-10-26 09:46:57', NULL, NULL, 'Desktop', NULL),
(276, 2, 2, '2026-10-24', '09:40', '18:30', '2026-10-24 10:26:55', '2026-10-24 19:00:00', 46, 0, 30785, 0, 'half_day', NULL, '2026-10-24 10:26:55', '2026-10-26 09:46:57', NULL, NULL, 'Desktop', NULL),
(277, 3, 3, '2026-10-24', '09:40', '18:30', '2026-10-24 10:51:02', '2026-10-24 19:00:00', 71, 0, 29338, 0, 'half_day', NULL, '2026-10-24 10:51:02', '2026-10-26 09:46:57', NULL, NULL, 'Desktop', NULL),
(278, 6, 6, '2026-10-26', '09:40', '18:30', '2026-10-26 09:30:04', '2026-10-26 18:26:44', 0, 3, 32200, 0, 'half_day', NULL, '2026-10-26 09:30:04', '2026-10-26 18:26:44', NULL, NULL, 'Desktop', NULL),
(279, 5, 5, '2026-10-26', '09:40', '18:30', '2026-10-26 09:33:09', '2026-10-26 19:00:00', 0, 0, 34011, 0, 'full_day', NULL, '2026-10-26 09:33:09', '2026-10-27 09:22:28', NULL, NULL, 'Desktop', NULL),
(280, 9, 9, '2026-10-26', '09:40', '18:30', '2026-10-26 09:37:35', '2026-10-26 19:00:00', 0, 0, 33745, 0, 'full_day', NULL, '2026-10-26 09:37:35', '2026-10-27 09:55:48', NULL, NULL, 'Desktop', NULL),
(281, 4, 4, '2026-10-26', '09:40', '18:30', '2026-10-26 09:42:47', '2026-10-26 19:00:00', 2, 0, 33433, 0, 'full_day', NULL, '2026-10-26 09:42:47', '2026-10-27 09:55:48', NULL, NULL, 'Desktop', NULL),
(282, 7, 7, '2026-10-26', '09:40', '18:30', '2026-10-26 09:44:05', '2026-10-26 19:00:00', 4, 0, 33355, 0, 'full_day', NULL, '2026-10-26 09:44:05', '2026-10-27 09:25:09', NULL, NULL, 'Desktop', NULL),
(283, 10, 10, '2026-10-26', '09:40', '18:30', '2026-10-26 09:45:55', '2026-10-26 18:29:24', 5, 0, 31409, 0, 'half_day', NULL, '2026-10-26 09:45:55', '2026-10-26 18:29:24', NULL, NULL, 'Desktop', NULL),
(284, 8, 8, '2026-10-26', '09:40', '18:30', '2026-10-26 09:46:08', '2026-10-26 19:00:00', 6, 0, 33232, 0, 'full_day', NULL, '2026-10-26 09:46:08', '2026-10-27 09:55:48', NULL, NULL, 'Desktop', NULL),
(285, 12, 12, '2026-10-26', '09:40', '18:30', '2026-10-26 09:55:43', '2026-10-26 18:44:09', 15, 0, 31706, 0, 'half_day', NULL, '2026-10-26 09:55:43', '2026-10-26 18:44:09', NULL, NULL, 'Desktop', NULL),
(286, 1, 1, '2026-10-26', '09:40', '18:30', '2026-10-26 12:29:06', '2026-10-26 19:00:00', 169, 0, 23454, 0, 'half_day', NULL, '2026-10-26 12:29:06', '2026-10-27 09:55:48', NULL, NULL, 'Desktop', NULL),
(287, 3, 3, '2026-10-26', '09:40', '18:30', '2026-10-26 14:05:23', '2026-10-26 19:00:00', 265, 0, 17677, 0, 'half_day', NULL, '2026-10-26 14:05:23', '2026-10-27 09:55:48', NULL, NULL, 'Desktop', NULL),
(288, 5, 5, '2026-10-27', '09:40', '18:30', '2026-10-27 09:35:11', '2026-10-27 19:00:00', 0, 0, 33889, 0, 'full_day', NULL, '2026-10-27 09:35:11', '2026-10-28 09:15:10', NULL, NULL, 'Desktop', NULL),
(289, 6, 6, '2026-10-27', '09:40', '18:30', '2026-10-27 09:43:54', '2026-10-27 18:25:51', 3, 4, 31317, 0, 'half_day', NULL, '2026-10-27 09:43:54', '2026-10-27 18:25:51', NULL, NULL, 'Desktop', NULL),
(290, 7, 7, '2026-10-27', '09:40', '18:30', '2026-10-27 09:44:33', '2026-10-27 19:00:00', 4, 0, 33327, 0, 'full_day', NULL, '2026-10-27 09:44:33', '2026-10-28 09:24:46', NULL, NULL, 'Desktop', NULL),
(291, 12, 12, '2026-10-27', '09:40', '18:30', '2026-10-27 09:52:23', '2026-10-27 18:33:50', 12, 0, 31287, 0, 'half_day', NULL, '2026-10-27 09:52:23', '2026-10-27 18:33:50', NULL, NULL, 'Desktop', NULL),
(292, 10, 10, '2026-10-27', '09:40', '18:30', '2026-10-27 10:01:44', '2026-10-27 19:00:00', 21, 0, 32296, 0, 'full_day', NULL, '2026-10-27 10:01:44', '2026-10-28 10:03:38', NULL, NULL, 'Desktop', NULL),
(293, 9, 9, '2026-10-27', '09:40', '18:30', '2026-10-27 10:02:47', '2026-10-27 19:00:00', 22, 0, 32233, 0, 'full_day', NULL, '2026-10-27 10:02:47', '2026-10-28 09:39:30', NULL, NULL, 'Desktop', NULL),
(294, 8, 8, '2026-10-27', '09:40', '18:30', '2026-10-27 10:04:44', '2026-10-27 19:00:00', 24, 0, 32116, 0, 'full_day', NULL, '2026-10-27 10:04:44', '2026-10-28 09:51:26', NULL, NULL, 'Desktop', NULL),
(295, 3, 3, '2026-10-27', '09:40', '18:30', '2026-10-27 10:44:42', '2026-10-27 19:00:00', 64, 0, 29718, 0, 'half_day', NULL, '2026-10-27 10:44:42', '2026-10-28 10:07:56', NULL, NULL, 'Desktop', NULL),
(296, 5, 5, '2026-10-28', '09:40', '18:30', '2026-10-28 09:34:36', '2026-10-28 19:00:00', 0, 0, 33924, 0, 'full_day', NULL, '2026-10-28 09:34:36', '2026-10-29 09:07:23', NULL, NULL, 'Desktop', NULL),
(297, 4, 4, '2026-10-28', '09:40', '18:30', '2026-10-28 09:36:38', '2026-10-28 19:00:00', 0, 0, 33802, 0, 'full_day', NULL, '2026-10-28 09:36:39', '2026-10-29 09:34:59', NULL, NULL, 'Desktop', NULL),
(298, 9, 9, '2026-10-28', '09:40', '18:30', '2026-10-28 09:41:06', '2026-10-28 18:36:22', 1, 0, 32116, 0, 'full_day', NULL, '2026-10-28 09:41:06', '2026-10-28 18:36:22', NULL, NULL, 'Desktop', NULL),
(299, 7, 7, '2026-10-28', '09:40', '18:30', '2026-10-28 09:43:29', '2026-10-28 19:00:00', 3, 0, 33391, 0, 'full_day', NULL, '2026-10-28 09:43:29', '2026-10-29 09:55:06', NULL, NULL, 'Desktop', NULL),
(300, 6, 6, '2026-10-28', '09:40', '18:30', '2026-10-28 09:51:30', '2026-10-28 18:29:36', 11, 0, 31086, 0, 'half_day', NULL, '2026-10-28 09:51:30', '2026-10-28 18:29:36', NULL, NULL, 'Desktop', NULL),
(301, 8, 8, '2026-10-28', '09:40', '18:30', '2026-10-28 09:51:30', '2026-10-28 18:35:24', 11, 0, 31434, 0, 'half_day', NULL, '2026-10-28 09:51:30', '2026-10-28 18:35:24', NULL, NULL, 'Desktop', NULL),
(302, 4, 4, '2026-10-29', '09:40', '18:30', '2026-10-29 09:35:23', '2026-10-29 19:00:00', 0, 0, 33877, 0, 'full_day', NULL, '2026-10-29 09:35:23', '2026-10-30 08:48:55', NULL, NULL, 'Desktop', NULL),
(303, 5, 5, '2026-10-29', '09:40', '18:30', '2026-10-29 09:35:43', '2026-10-29 19:00:00', 0, 0, 33857, 0, 'full_day', NULL, '2026-10-29 09:35:43', '2026-10-30 09:00:44', NULL, NULL, 'Desktop', NULL),
(304, 8, 8, '2026-10-29', '09:40', '18:30', '2026-10-29 09:40:35', '2026-10-29 19:00:00', 0, 0, 33565, 0, 'full_day', NULL, '2026-10-29 09:40:35', '2026-10-30 09:47:12', NULL, NULL, 'Desktop', NULL),
(305, 9, 9, '2026-10-29', '09:40', '18:30', '2026-10-29 09:52:40', '2026-10-29 19:00:00', 12, 0, 32840, 0, 'full_day', NULL, '2026-10-29 09:52:40', '2026-10-30 09:45:39', NULL, NULL, 'Desktop', NULL),
(306, 6, 6, '2026-10-29', '09:40', '18:30', '2026-10-29 09:52:51', '2026-10-29 18:28:14', 12, 1, 30923, 0, 'half_day', NULL, '2026-10-29 09:52:51', '2026-10-29 18:28:14', NULL, NULL, 'Desktop', NULL),
(307, 12, 12, '2026-10-29', '09:40', '18:30', '2026-10-29 09:58:39', '2026-10-29 18:35:40', 18, 0, 31021, 0, 'half_day', NULL, '2026-10-29 09:58:39', '2026-10-29 18:35:40', NULL, NULL, 'Desktop', NULL),
(308, 10, 10, '2026-10-29', '09:40', '18:30', '2026-10-29 09:58:54', '2026-10-29 19:00:00', 18, 0, 32466, 0, 'full_day', NULL, '2026-10-29 09:58:54', '2026-10-30 09:47:12', NULL, NULL, 'Desktop', NULL),
(309, 3, 3, '2026-10-29', '09:40', '18:30', '2026-10-29 12:24:26', '2026-10-29 19:00:00', 164, 0, 23734, 0, 'half_day', NULL, '2026-10-29 12:24:26', '2026-10-30 09:47:12', NULL, NULL, 'Desktop', NULL),
(310, 4, 4, '2026-10-30', '09:40', '18:30', '2026-10-30 09:32:04', '2026-10-30 19:00:00', 0, 0, 34076, 0, 'full_day', NULL, '2026-10-30 09:32:04', '2026-10-30 20:57:12', NULL, NULL, 'Desktop', NULL),
(311, 5, 5, '2026-10-30', '09:40', '18:30', '2026-10-30 09:36:40', '2026-10-30 19:00:00', 0, 0, 33800, 0, 'full_day', NULL, '2026-10-30 09:36:40', '2026-10-30 20:57:12', NULL, NULL, 'Desktop', NULL),
(312, 7, 7, '2026-10-30', '09:40', '18:30', '2026-10-30 09:45:18', '2026-10-30 18:32:44', 5, 0, 31646, 0, 'half_day', NULL, '2026-10-30 09:45:18', '2026-10-30 18:32:44', NULL, NULL, 'Desktop', NULL),
(313, 9, 9, '2026-10-30', '09:40', '18:30', '2026-10-30 09:45:42', '2026-10-30 19:00:00', 5, 0, 33258, 0, 'full_day', NULL, '2026-10-30 09:45:42', '2026-10-30 20:57:12', NULL, NULL, 'Desktop', NULL),
(314, 12, 12, '2026-10-30', '09:40', '18:30', '2026-10-30 09:51:32', '2026-10-30 18:38:49', 11, 0, 31637, 0, 'half_day', NULL, '2026-10-30 09:51:32', '2026-10-30 18:38:49', NULL, NULL, 'Desktop', NULL),
(315, 6, 6, '2026-10-30', '09:40', '18:30', '2026-10-30 09:53:36', '2026-10-30 18:30:17', 13, 0, 31001, 0, 'half_day', NULL, '2026-10-30 09:53:36', '2026-10-30 18:30:17', NULL, NULL, 'Desktop', NULL),
(316, 10, 10, '2026-10-30', '09:40', '18:30', '2026-10-30 10:00:31', '2026-10-30 19:00:00', 20, 0, 32369, 0, 'full_day', NULL, '2026-10-30 10:00:31', '2026-10-30 20:57:12', NULL, NULL, 'Desktop', NULL),
(317, 8, 8, '2026-10-30', '09:40', '18:30', '2026-10-30 10:10:20', '2026-10-30 19:00:00', 30, 0, 31780, 0, 'half_day', NULL, '2026-10-30 10:10:20', '2026-10-30 20:57:07', NULL, NULL, 'Desktop', NULL),
(318, 3, 3, '2026-10-30', '09:40', '18:30', '2026-10-30 10:42:09', '2026-10-30 19:00:00', 62, 0, 29871, 0, 'half_day', NULL, '2026-10-30 10:42:09', '2026-10-30 20:57:12', NULL, NULL, 'Desktop', NULL),
(319, 4, 4, '2026-10-31', '09:40', '18:30', '2026-10-31 09:38:38', '2026-10-31 19:00:00', 168, 0, 33682, 0, 'full_day', NULL, '2026-10-31 12:28:38', '2026-10-31 21:34:32', NULL, NULL, 'Desktop', NULL),
(320, 9, 9, '2026-10-31', '09:40', '18:30', '2026-10-31 09:20:53', '2026-10-31 14:10:08', 168, 259, 17355, 0, 'half_day', NULL, '2026-10-31 12:28:53', '2026-10-31 14:10:08', NULL, NULL, 'Desktop', NULL),
(321, 12, 12, '2026-10-31', '09:40', '18:30', '2026-10-31 09:40:26', '2026-10-31 18:31:54', 169, 0, 31888, 0, 'full_day', NULL, '2026-10-31 12:29:26', '2026-10-31 18:31:54', NULL, NULL, 'Desktop', NULL),
(322, 10, 10, '2026-10-31', '09:40', '18:30', '2026-10-31 09:55:26', '2026-10-31 19:00:00', 169, 0, 32674, 0, 'full_day', NULL, '2026-10-31 12:29:26', '2026-10-31 21:34:36', NULL, NULL, 'Desktop', NULL),
(323, 6, 6, '2026-10-31', '09:40', '18:30', '2026-10-31 09:47:16', '2026-10-31 19:00:00', 170, 0, 33164, 0, 'full_day', NULL, '2026-10-31 12:30:16', '2026-10-31 21:34:36', NULL, NULL, 'Desktop', NULL),
(324, 7, 7, '2026-10-31', '09:40', '18:30', '2026-10-31 09:38:34', '2026-10-31 19:00:00', 170, 0, 33686, 0, 'full_day', NULL, '2026-10-31 12:30:34', '2026-10-31 21:34:36', NULL, NULL, 'Desktop', NULL),
(325, 8, 8, '2026-10-31', '09:40', '18:30', '2026-10-31 09:40:06', '2026-10-31 19:00:00', 172, 0, 33594, 0, 'full_day', NULL, '2026-10-31 12:32:06', '2026-10-31 21:34:36', NULL, NULL, 'Desktop', NULL),
(326, 2, 2, '2026-10-31', '09:40', '18:30', '2026-10-31 10:40:26', '2026-10-31 19:00:00', 172, 0, 29974, 0, 'half_day', NULL, '2026-10-31 12:32:26', '2026-10-31 21:34:36', NULL, NULL, 'Desktop', NULL),
(327, 5, 5, '2026-10-31', '09:40', '18:30', '2026-10-31 09:35:35', '2026-10-31 19:00:00', 179, 0, 33865, 0, 'full_day', NULL, '2026-10-31 12:39:35', '2026-10-31 21:34:36', NULL, NULL, 'Desktop', NULL),
(328, 3, 3, '2026-10-31', '09:40', '18:30', '2026-10-31 13:39:53', '2026-10-31 19:00:00', 239, 0, 19207, 0, 'half_day', NULL, '2026-10-31 13:39:53', '2026-10-31 21:34:36', NULL, NULL, 'Desktop', NULL),
(329, 4, 4, '2026-11-02', '09:40', '18:30', '2026-11-02 09:38:16', '2026-11-02 19:00:00', 0, 0, 33704, 0, 'full_day', NULL, '2026-11-02 09:38:16', '2026-11-03 09:37:04', NULL, NULL, 'Desktop', NULL),
(330, 5, 5, '2026-11-02', '09:40', '18:30', '2026-11-02 09:39:48', '2026-11-02 19:00:00', 0, 0, 33612, 0, 'full_day', NULL, '2026-11-02 09:39:48', '2026-11-03 09:20:07', NULL, NULL, 'Desktop', NULL),
(331, 9, 9, '2026-11-02', '09:40', '18:30', '2026-11-02 09:40:53', '2026-11-02 19:00:00', 0, 0, 33547, 0, 'full_day', NULL, '2026-11-02 09:40:53', '2026-11-03 09:36:51', NULL, NULL, 'Desktop', NULL),
(332, 8, 8, '2026-11-02', '09:40', '18:30', '2026-11-02 09:41:12', '2026-11-02 19:00:00', 1, 0, 33528, 0, 'full_day', NULL, '2026-11-02 09:41:12', '2026-11-03 09:37:04', NULL, NULL, 'Desktop', NULL),
(333, 10, 10, '2026-11-02', '09:40', '18:30', '2026-11-02 09:48:52', '2026-11-02 19:00:00', 8, 0, 33068, 0, 'full_day', NULL, '2026-11-02 09:48:52', '2026-11-03 09:37:04', NULL, NULL, 'Desktop', NULL),
(334, 12, 12, '2026-11-02', '09:40', '18:30', '2026-11-02 09:54:44', '2026-11-02 19:00:00', 14, 0, 32716, 0, 'full_day', NULL, '2026-11-02 09:54:44', '2026-11-03 09:37:04', NULL, NULL, 'Desktop', NULL),
(335, 7, 7, '2026-11-02', '09:40', '18:30', '2026-11-02 11:58:30', '2026-11-02 19:00:00', 138, 0, 25290, 0, 'half_day', NULL, '2026-11-02 11:58:30', '2026-11-03 09:37:04', NULL, NULL, 'Desktop', NULL),
(336, 5, 5, '2026-11-03', '09:40', '18:30', '2026-11-03 09:34:21', '2026-11-03 19:00:00', 0, 0, 33939, 0, 'full_day', NULL, '2026-11-03 09:34:21', '2026-11-04 09:25:13', NULL, NULL, 'Desktop', NULL),
(337, 9, 9, '2026-11-03', '09:40', '18:30', '2026-11-03 09:36:57', '2026-11-03 19:00:00', 0, 0, 33783, 0, 'full_day', NULL, '2026-11-03 09:36:57', '2026-11-04 09:25:13', NULL, NULL, 'Desktop', NULL),
(338, 8, 8, '2026-11-03', '09:40', '18:30', '2026-11-03 09:40:03', '2026-11-03 19:00:00', 0, 0, 33597, 0, 'full_day', NULL, '2026-11-03 09:40:03', '2026-11-04 09:25:13', NULL, NULL, 'Desktop', NULL),
(339, 12, 12, '2026-11-03', '09:40', '18:30', '2026-11-03 09:40:36', '2026-11-03 18:38:42', 0, 0, 32286, 0, 'full_day', NULL, '2026-11-03 09:40:36', '2026-11-03 18:38:42', NULL, NULL, 'Desktop', NULL),
(340, 7, 7, '2026-11-03', '09:40', '18:30', '2026-11-03 09:41:38', '2026-11-03 18:33:31', 1, 0, 31913, 0, 'full_day', NULL, '2026-11-03 09:41:38', '2026-11-03 18:33:31', NULL, NULL, 'Desktop', NULL),
(341, 10, 10, '2026-11-03', '09:40', '18:30', '2026-11-03 09:44:44', '2026-11-03 19:00:00', 4, 0, 33316, 0, 'full_day', NULL, '2026-11-03 09:44:44', '2026-11-04 09:25:13', NULL, NULL, 'Desktop', NULL),
(342, 7, 7, '2026-11-04', '09:40', '18:30', '2026-11-04 09:24:45', '2026-11-04 09:24:51', 0, 545, 6, 0, 'half_day', NULL, '2026-11-04 09:24:45', '2026-11-04 09:24:51', NULL, NULL, 'Desktop', NULL),
(343, 7, 7, '2026-11-04', '09:40', '18:30', '2026-11-04 09:25:05', '2026-11-04 19:00:00', 0, 0, 34495, 0, 'full_day', NULL, '2026-11-04 09:25:05', '2026-11-05 09:28:08', NULL, NULL, 'Desktop', NULL),
(344, 5, 5, '2026-11-04', '09:40', '18:30', '2026-11-04 09:39:10', '2026-11-04 19:00:00', 0, 0, 33650, 0, 'full_day', NULL, '2026-11-04 09:39:10', '2026-11-04 22:01:16', NULL, NULL, 'Desktop', NULL),
(345, 12, 12, '2026-11-04', '09:40', '18:30', '2026-11-04 09:41:14', '2026-11-04 19:00:00', 1, 0, 33526, 0, 'full_day', NULL, '2026-11-04 09:41:14', '2026-11-05 09:35:58', NULL, NULL, 'Desktop', NULL),
(346, 9, 9, '2026-11-04', '09:40', '18:30', '2026-11-04 09:42:23', '2026-11-04 19:00:00', 2, 0, 33457, 0, 'full_day', NULL, '2026-11-04 09:42:23', '2026-11-05 09:35:58', NULL, NULL, 'Desktop', NULL),
(347, 8, 8, '2026-11-04', '09:40', '18:30', '2026-11-04 10:00:59', '2026-11-04 19:00:00', 20, 0, 32341, 0, 'full_day', NULL, '2026-11-04 10:00:59', '2026-11-05 09:35:20', NULL, NULL, 'Desktop', NULL),
(348, 10, 10, '2026-11-04', '09:40', '18:30', '2026-11-04 10:31:23', '2026-11-04 19:00:00', 51, 0, 30517, 0, 'half_day', NULL, '2026-11-04 10:31:23', '2026-11-05 09:35:58', NULL, NULL, 'Desktop', NULL),
(349, 5, 5, '2026-11-05', '09:40', '18:30', '2026-11-05 09:34:23', '2026-11-05 19:00:00', 0, 0, 33937, 0, 'full_day', NULL, '2026-11-05 09:34:23', '2026-11-06 09:24:43', NULL, NULL, 'Desktop', NULL),
(350, 7, 7, '2026-11-05', '09:40', '18:30', '2026-11-05 09:37:24', '2026-11-05 19:00:00', 0, 0, 33756, 0, 'full_day', NULL, '2026-11-05 09:37:24', '2026-11-06 09:31:05', NULL, NULL, 'Desktop', NULL),
(351, 9, 9, '2026-11-05', '09:40', '18:30', '2026-11-05 09:40:31', '2026-11-05 19:00:00', 0, 0, 33569, 0, 'full_day', NULL, '2026-11-05 09:40:31', '2026-11-06 09:40:24', NULL, NULL, 'Desktop', NULL),
(352, 8, 8, '2026-11-05', '09:40', '18:30', '2026-11-05 09:40:45', '2026-11-05 19:00:00', 0, 0, 33555, 0, 'full_day', NULL, '2026-11-05 09:40:45', '2026-11-06 09:40:05', NULL, NULL, 'Desktop', NULL),
(353, 10, 10, '2026-11-05', '09:40', '18:30', '2026-11-05 09:55:10', '2026-11-05 19:00:00', 15, 0, 32690, 0, 'full_day', NULL, '2026-11-05 09:55:10', '2026-11-06 09:57:21', NULL, NULL, 'Desktop', NULL),
(354, 12, 12, '2026-11-05', '09:40', '18:30', '2026-11-05 10:05:21', '2026-11-05 18:37:05', 25, 0, 30704, 0, 'half_day', NULL, '2026-11-05 10:05:21', '2026-11-05 18:37:05', NULL, NULL, 'Desktop', NULL),
(355, 5, 5, '2026-11-06', '09:40', '18:30', '2026-11-06 09:37:06', '2026-11-06 19:00:00', 0, 0, 33774, 0, 'full_day', NULL, '2026-11-06 09:37:06', '2026-11-07 09:27:07', NULL, NULL, 'Desktop', NULL),
(356, 7, 7, '2026-11-06', '09:40', '18:30', '2026-11-06 09:40:02', '2026-11-06 19:00:00', 0, 0, 33598, 0, 'full_day', NULL, '2026-11-06 09:40:02', '2026-11-07 09:28:04', NULL, NULL, 'Desktop', NULL),
(357, 8, 8, '2026-11-06', '09:40', '18:30', '2026-11-06 09:40:08', '2026-11-06 19:00:00', 0, 0, 33592, 0, 'full_day', NULL, '2026-11-06 09:40:08', '2026-11-07 09:46:58', NULL, NULL, 'Desktop', NULL),
(358, 9, 9, '2026-11-06', '09:40', '18:30', '2026-11-06 09:40:27', '2026-11-06 19:00:00', 0, 0, 33573, 0, 'full_day', NULL, '2026-11-06 09:40:27', '2026-11-07 09:39:30', NULL, NULL, 'Desktop', NULL),
(359, 12, 12, '2026-11-06', '09:40', '18:30', '2026-11-06 09:52:37', '2026-11-06 19:00:00', 12, 0, 32843, 0, 'full_day', NULL, '2026-11-06 09:52:37', '2026-11-07 09:46:58', NULL, NULL, 'Desktop', NULL),
(360, 10, 10, '2026-11-06', '09:40', '18:30', '2026-11-06 10:01:06', '2026-11-06 19:00:00', 21, 0, 32334, 0, 'full_day', NULL, '2026-11-06 10:01:06', '2026-11-07 09:45:09', NULL, NULL, 'Desktop', NULL),
(361, 5, 5, '2026-11-07', '09:40', '18:30', '2026-11-07 09:38:17', '2026-11-07 19:00:00', 0, 0, 33703, 0, 'full_day', NULL, '2026-11-07 09:38:17', '2026-11-12 09:17:49', NULL, NULL, 'Desktop', NULL),
(362, 9, 9, '2026-11-07', '09:40', '18:30', '2026-11-07 09:40:00', '2026-11-07 19:00:00', 0, 0, 33600, 0, 'full_day', NULL, '2026-11-07 09:40:00', '2026-11-12 09:38:02', NULL, NULL, 'Desktop', NULL),
(363, 7, 7, '2026-11-07', '09:40', '18:30', '2026-11-07 09:40:24', '2026-11-07 19:00:00', 0, 0, 33576, 0, 'full_day', NULL, '2026-11-07 09:40:24', '2026-11-12 09:26:04', NULL, NULL, 'Desktop', NULL),
(364, 10, 10, '2026-11-07', '09:40', '18:30', '2026-11-07 09:45:16', '2026-11-07 19:00:00', 5, 0, 33284, 0, 'full_day', NULL, '2026-11-07 09:45:16', '2026-11-12 09:38:02', NULL, NULL, 'Desktop', NULL),
(365, 8, 8, '2026-11-07', '09:40', '18:30', '2026-11-07 09:48:16', '2026-11-07 19:00:00', 8, 0, 33104, 0, 'full_day', NULL, '2026-11-07 09:48:16', '2026-11-12 09:38:02', NULL, NULL, 'Desktop', NULL),
(366, 5, 5, '2026-11-12', '09:40', '18:30', '2026-11-12 09:33:18', '2026-11-12 19:00:00', 0, 0, 34002, 0, 'full_day', NULL, '2026-11-12 09:33:18', '2026-11-13 09:53:28', NULL, NULL, 'Desktop', NULL),
(367, 7, 7, '2026-11-12', '09:40', '18:30', '2026-11-12 09:37:51', '2026-11-12 19:00:00', 0, 0, 33729, 0, 'full_day', NULL, '2026-11-12 09:37:51', '2026-11-13 09:19:42', NULL, NULL, 'Desktop', NULL),
(368, 9, 9, '2026-11-12', '09:40', '18:30', '2026-11-12 09:39:20', '2026-11-12 19:00:00', 0, 0, 33640, 0, 'full_day', NULL, '2026-11-12 09:39:20', '2026-11-13 09:38:48', NULL, NULL, 'Desktop', NULL),
(369, 12, 12, '2026-11-12', '09:40', '18:30', '2026-11-12 09:49:26', '2026-11-12 18:32:54', 9, 0, 31408, 0, 'half_day', NULL, '2026-11-12 09:49:26', '2026-11-12 18:32:54', NULL, NULL, 'Desktop', NULL),
(370, 8, 8, '2026-11-12', '09:40', '18:30', '2026-11-12 09:50:44', '2026-11-12 19:00:00', 10, 0, 32956, 0, 'full_day', NULL, '2026-11-12 09:50:44', '2026-11-13 09:48:40', NULL, NULL, 'Desktop', NULL),
(371, 10, 10, '2026-11-12', '09:40', '18:30', '2026-11-12 09:51:02', '2026-11-12 19:00:00', 11, 0, 32938, 0, 'full_day', NULL, '2026-11-12 09:51:02', '2026-11-13 09:53:28', NULL, NULL, 'Desktop', NULL),
(372, 4, 4, '2026-11-12', '09:40', '18:30', '2026-11-12 10:28:35', '2026-11-12 19:00:00', 48, 0, 30685, 0, 'half_day', NULL, '2026-11-12 10:28:35', '2026-11-13 09:33:52', NULL, NULL, 'Desktop', NULL),
(373, 4, 4, '2026-11-13', '09:40', '18:30', '2026-11-13 09:38:25', '2026-11-13 19:00:00', 0, 0, 33695, 0, 'full_day', NULL, '2026-11-13 09:38:25', '2026-11-14 09:49:11', NULL, NULL, 'Desktop', NULL),
(374, 9, 9, '2026-11-13', '09:40', '18:30', '2026-11-13 09:38:54', '2026-11-13 19:00:00', 0, 0, 33666, 0, 'full_day', NULL, '2026-11-13 09:38:54', '2026-11-14 09:22:36', NULL, NULL, 'Desktop', NULL),
(375, 7, 7, '2026-11-13', '09:40', '18:30', '2026-11-13 09:43:25', '2026-11-13 19:00:00', 3, 0, 33395, 0, 'full_day', NULL, '2026-11-13 09:43:25', '2026-11-14 09:21:52', NULL, NULL, 'Desktop', NULL),
(376, 8, 8, '2026-11-13', '09:40', '18:30', '2026-11-13 09:48:43', '2026-11-13 19:00:00', 8, 0, 33077, 0, 'full_day', NULL, '2026-11-13 09:48:43', '2026-11-14 09:49:11', NULL, NULL, 'Desktop', NULL),
(377, 12, 12, '2026-11-13', '09:40', '18:30', '2026-11-13 10:00:33', '2026-11-13 18:30:41', 20, 0, 30608, 0, 'half_day', NULL, '2026-11-13 10:00:33', '2026-11-13 18:30:41', NULL, '2401:4900:883d:6edd:7891:f7a7:10e3:c94f', 'Desktop', NULL),
(378, 10, 10, '2026-11-13', '09:40', '18:30', '2026-11-13 10:02:13', '2026-11-13 19:00:00', 22, 0, 32267, 0, 'full_day', NULL, '2026-11-13 10:02:13', '2026-11-14 09:49:11', NULL, NULL, 'Desktop', NULL),
(379, 2, 2, '2026-11-13', '09:40', '18:30', '2026-11-13 11:14:22', '2026-11-13 19:00:00', 94, 0, 27938, 0, 'half_day', NULL, '2026-11-13 11:14:22', '2026-11-14 09:49:11', NULL, NULL, 'Desktop', NULL),
(380, 3, 3, '2026-11-13', '09:40', '18:30', '2026-11-13 11:25:31', '2026-11-13 19:00:00', 254, 0, 27269, 0, 'half_day', NULL, '2026-11-13 13:54:31', '2026-11-14 09:49:11', '2401:4900:883d:6edd:7919:181f:4c1a:5c6', NULL, 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(381, 7, 7, '2026-11-14', '09:40', '18:30', '2026-11-14 09:44:44', '2026-11-14 19:00:00', 4, 0, 33316, 0, 'full_day', NULL, '2026-11-14 09:44:44', '2026-11-16 09:28:01', '2401:4900:883d:6edd:b842:a9c2:5169:aadc', NULL, 'Desktop', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36'),
(382, 12, 12, '2026-11-14', '09:40', '18:30', '2026-11-14 09:46:24', '2026-11-14 18:31:51', 6, 0, 31527, 0, 'half_day', NULL, '2026-11-14 09:46:24', '2026-11-14 18:31:51', '106.219.148.222', '2401:4900:883d:6edd:6982:65f2:209d:6167', 'Desktop', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36'),
(383, 9, 9, '2026-11-14', '09:40', '18:30', '2026-11-14 09:49:05', '2026-11-14 19:00:00', 9, 0, 33055, 0, 'full_day', NULL, '2026-11-14 09:49:05', '2026-11-16 09:39:40', '2401:4900:883d:6edd:ee27:6ede:c17:6a5c', NULL, 'Desktop', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36'),
(384, 4, 4, '2026-11-14', '09:40', '18:30', '2026-11-14 09:53:28', '2026-11-14 19:00:00', 13, 0, 32792, 0, 'full_day', NULL, '2026-11-14 09:53:28', '2026-11-16 09:31:44', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(385, 8, 8, '2026-11-14', '09:40', '18:30', '2026-11-14 09:53:59', '2026-11-14 19:00:00', 13, 0, 32761, 0, 'full_day', NULL, '2026-11-14 09:53:59', '2026-11-16 09:41:48', '2401:4900:883d:6edd:e9fb:e86f:cb53:c23f', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(386, 10, 10, '2026-11-14', '09:40', '18:30', '2026-11-14 10:00:23', '2026-11-14 19:00:00', 20, 0, 32377, 0, 'full_day', NULL, '2026-11-14 10:00:23', '2026-11-16 09:55:15', '2401:4900:883d:6edd:55ac:7c87:97c7:8d42', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(387, 2, 2, '2026-11-14', '09:40', '18:30', '2026-11-14 10:20:57', '2026-11-14 19:00:00', 40, 0, 31143, 0, 'half_day', NULL, '2026-11-14 10:20:57', '2026-11-16 09:55:15', '2401:4900:883d:6edd:51ef:a2a8:bea1:9e5', NULL, 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36'),
(388, 3, 3, '2026-11-14', '09:40', '18:30', '2026-11-14 11:30:16', '2026-11-14 19:00:00', 196, 0, 26984, 0, 'half_day', NULL, '2026-11-14 12:56:16', '2026-11-16 09:55:15', '2401:4900:883d:6edd:54c:8c08:db4f:5c9a', NULL, 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(389, 9, 9, '2026-11-16', '09:40', '18:30', '2026-11-16 09:40:12', '2026-11-16 19:00:00', 18, 0, 33588, 0, 'full_day', NULL, '2026-11-16 09:58:12', '2026-11-16 19:00:16', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(390, 5, 5, '2026-11-16', '09:40', '18:30', '2026-11-16 09:58:15', '2026-11-16 19:00:00', 18, 0, 32505, 0, 'full_day', NULL, '2026-11-16 09:58:15', '2026-11-16 19:00:16', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(391, 10, 10, '2026-11-16', '09:40', '18:30', '2026-11-16 09:58:42', '2026-11-16 19:00:00', 18, 0, 32478, 0, 'full_day', NULL, '2026-11-16 09:58:42', '2026-11-16 19:00:16', '2401:4900:883e:159b:2da3:d3c:e30e:d56f', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(392, 4, 4, '2026-11-16', '09:40', '18:30', '2026-11-16 09:33:49', '2026-11-16 19:00:00', 18, 0, 33971, 0, 'full_day', NULL, '2026-11-16 09:58:49', '2026-11-16 19:00:16', '2401:4900:883e:159b:647e:d829:6864:809a', NULL, 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(393, 12, 12, '2026-11-16', '09:40', '18:30', '2026-11-16 09:37:17', '2026-11-16 19:00:00', 19, 0, 33763, 0, 'full_day', NULL, '2026-11-16 09:59:17', '2026-11-16 19:00:16', '2401:4900:883e:159b:85cb:6044:f4b:c9ea', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(394, 7, 7, '2026-11-16', '09:40', '18:30', '2026-11-16 09:40:56', '2026-11-16 19:00:00', 20, 0, 33544, 0, 'full_day', NULL, '2026-11-16 10:00:56', '2026-11-16 19:00:16', '2401:4900:883e:159b:185f:694a:435d:458c', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(395, 8, 8, '2026-11-16', '09:40', '18:30', '2026-11-16 09:42:36', '2026-11-16 19:00:00', 21, 0, 33444, 0, 'full_day', NULL, '2026-11-16 10:01:36', '2026-11-16 19:00:16', '2401:4900:883e:159b:3db6:e2c4:3249:a47d', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(396, 2, 2, '2026-11-16', '09:40', '18:30', '2026-11-16 10:14:28', '2026-11-16 19:00:00', 34, 0, 31532, 0, 'half_day', NULL, '2026-11-16 10:14:28', '2026-11-16 19:00:16', '2401:4900:883e:159b:5982:ee0c:8c0f:2b93', NULL, 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36'),
(397, 3, 3, '2026-11-16', '09:40', '18:30', '2026-11-16 11:15:54', '2026-11-16 19:00:00', 117, 0, 27846, 0, 'half_day', NULL, '2026-11-16 11:37:54', '2026-11-16 19:00:16', '2401:4900:883e:159b:647e:d829:6864:809a', NULL, 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(398, 4, 4, '2026-11-17', '09:40', '18:30', '2026-11-17 09:30:12', '2026-11-17 19:00:00', 0, 0, 34188, 0, 'full_day', NULL, '2026-11-17 09:30:12', '2026-11-18 09:32:08', '2401:4900:883e:159b:388a:35c0:acfd:f799', NULL, 'Mobile', 'Mozilla/5.0 (iPhone; CPU iPhone OS 27_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/157.0.8103.37 Mobile/15E148 Safari/604.1'),
(399, 9, 9, '2026-11-17', '09:40', '18:30', '2026-11-17 09:35:56', '2026-11-17 19:00:00', 0, 0, 33844, 0, 'full_day', NULL, '2026-11-17 09:35:56', '2026-11-18 09:37:30', '2401:4900:883e:159b:cf2f:b104:2ad8:1874', NULL, 'Desktop', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(400, 7, 7, '2026-11-17', '09:40', '18:30', '2026-11-17 09:40:21', '2026-11-17 19:00:00', 0, 0, 33579, 0, 'full_day', NULL, '2026-11-17 09:40:21', '2026-11-18 09:14:59', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(401, 10, 10, '2026-11-17', '09:40', '18:30', '2026-11-17 09:41:48', '2026-11-17 19:00:00', 1, 0, 33492, 0, 'full_day', NULL, '2026-11-17 09:41:48', '2026-11-18 09:43:23', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(402, 8, 8, '2026-11-17', '09:40', '18:30', '2026-11-17 09:42:36', '2026-11-17 19:00:00', 2, 0, 33444, 0, 'full_day', NULL, '2026-11-17 09:42:36', '2026-11-18 09:43:23', '2401:4900:883e:159b:1593:6544:2dce:660', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(403, 5, 5, '2026-11-17', '09:40', '18:30', '2026-11-17 09:49:42', '2026-11-17 19:00:00', 9, 0, 33018, 0, 'full_day', NULL, '2026-11-17 09:49:42', '2026-11-18 09:42:21', '2401:4900:883e:159b:8dc2:3baa:8d57:1bf0', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(404, 12, 12, '2026-11-17', '09:40', '18:30', '2026-11-17 10:04:19', '2026-11-17 19:00:00', 24, 0, 32141, 0, 'full_day', NULL, '2026-11-17 10:04:19', '2026-11-18 09:43:23', '2401:4900:883e:159b:7891:f7a7:10e3:c94f', NULL, 'Desktop', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/156.0.0.0 Safari/537.36'),
(405, 2, 2, '2026-11-17', '09:40', '18:30', '2026-11-17 11:31:41', '2026-11-17 19:00:00', 111, 0, 26899, 0, 'half_day', NULL, '2026-11-17 11:31:41', '2026-11-18 09:43:23', '2401:4900:883e:159b:7d75:2f17:e506:ff82', NULL, 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36'),
(406, 3, 3, '2026-11-17', '09:40', '18:30', '2026-11-17 15:01:03', '2026-11-17 19:00:00', 362, 0, 14337, 0, 'half_day', NULL, '2026-11-17 15:42:03', '2026-11-18 09:43:23', '2401:4900:883e:159b:b8c8:d2b8:9f23:7d29', NULL, 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(407, 4, 4, '2026-11-18', '09:40', '18:30', '2026-11-18 09:32:11', '2026-11-18 19:00:00', 0, 0, 34069, 0, 'full_day', NULL, '2026-11-18 09:32:11', '2026-11-19 08:33:05', '2401:4900:883e:159b:19d0:9429:37d9:4883', NULL, 'Mobile', 'Mozilla/5.0 (iPhone; CPU iPhone OS 27_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/157.0.8103.37 Mobile/15E148 Safari/604.1'),
(408, 9, 9, '2026-11-18', '09:40', '18:30', '2026-11-18 09:37:51', '2026-11-18 19:00:00', 0, 0, 33729, 0, 'full_day', NULL, '2026-11-18 09:37:51', '2026-11-19 09:37:02', '2401:4900:883e:159b:d26c:1fd4:5d37:18b4', NULL, 'Desktop', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(409, 5, 5, '2026-11-18', '09:40', '18:30', '2026-11-18 09:42:55', '2026-11-18 19:00:00', 2, 0, 33425, 0, 'full_day', NULL, '2026-11-18 09:42:55', '2026-11-19 09:41:05', '2401:4900:883e:159b:3127:7303:db14:aeba', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(410, 7, 7, '2026-11-18', '09:40', '18:30', '2026-11-18 09:44:11', '2026-11-18 19:00:00', 4, 0, 33349, 0, 'full_day', NULL, '2026-11-18 09:44:11', '2026-11-19 09:45:04', '2401:4900:883e:159b:2d84:28ca:db94:4c02', NULL, 'Desktop', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(411, 12, 12, '2026-11-18', '09:40', '18:30', '2026-11-18 09:51:57', '2026-11-18 19:00:00', 11, 0, 32883, 0, 'full_day', NULL, '2026-11-18 09:51:57', '2026-11-19 09:45:04', '2401:4900:883e:159b:720c:ec8d:bac:41ef', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(412, 10, 10, '2026-11-18', '09:40', '18:30', '2026-11-18 09:56:24', '2026-11-18 19:00:00', 16, 0, 32616, 0, 'full_day', NULL, '2026-11-18 09:56:24', '2026-11-19 09:45:04', '2401:4900:883e:159b:3127:7303:db14:aeba', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(413, 8, 8, '2026-11-18', '09:40', '18:30', '2026-11-18 09:58:10', '2026-11-18 19:00:00', 18, 0, 32510, 0, 'full_day', NULL, '2026-11-18 09:58:10', '2026-11-19 09:45:04', '2401:4900:883e:159b:b54a:d626:e5:ba33', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(414, 2, 2, '2026-11-18', '09:40', '18:30', '2026-11-18 17:03:35', '2026-11-18 17:03:41', 443, 86, 6, 0, 'half_day', NULL, '2026-11-18 17:03:35', '2026-11-18 17:03:41', '2401:4900:883e:159b:b059:70f6:dfb7:eb15', '2401:4900:883e:159b:b059:70f6:dfb7:eb15', 'Desktop', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36'),
(415, 5, 5, '2026-11-19', '09:40', '18:30', '2026-11-19 09:41:08', NULL, 1, 0, 0, 0, 'late', NULL, '2026-11-19 09:41:08', '2026-11-19 09:41:08', '2401:4900:883e:159b:9d87:2249:99a0:4a54', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(416, 4, 4, '2026-11-19', '09:40', '18:30', '2026-11-19 09:37:24', NULL, 3, 0, 0, 0, 'late', NULL, '2026-11-19 09:43:24', '2026-11-19 04:26:18', '2401:4900:883e:159b:9d87:2249:99a0:4a54', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(417, 9, 9, '2026-11-19', '09:40', '18:30', '2026-11-19 09:40:38', NULL, 4, 0, 0, 0, 'late', NULL, '2026-11-19 09:44:38', '2026-11-19 04:26:46', '2401:4900:883e:159b:9d87:2249:99a0:4a54', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(418, 12, 12, '2026-11-19', '09:40', '18:30', '2026-11-19 09:50:03', NULL, 10, 0, 0, 0, 'late', NULL, '2026-11-19 09:50:03', '2026-11-19 09:50:03', '2401:4900:883e:159b:720c:ec8d:bac:41ef', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(419, 10, 10, '2026-11-19', '09:40', '18:30', '2026-11-19 09:51:46', NULL, 15, 0, 0, 0, 'late', NULL, '2026-11-19 09:55:46', '2026-11-19 04:45:07', '2401:4900:883e:159b:9d87:2249:99a0:4a54', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36'),
(420, 8, 8, '2026-11-19', '09:40', '18:30', '2026-11-19 09:43:04', NULL, 32, 0, 0, 0, 'late', NULL, '2026-11-19 10:12:04', '2026-11-19 04:44:46', '2401:4900:883e:159b:ad67:3d99:5ea3:a494', NULL, 'Desktop', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36');

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
-- Table structure for table `authorized_ips`
--

CREATE TABLE `authorized_ips` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `location` varchar(100) NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `authorized_ips`
--

INSERT INTO `authorized_ips` (`id`, `ip_address`, `location`, `status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '192.168.29.174', 'Office Wi-Fi 1', 'active', 'Initial Authorized Office Wi-Fi 1', NULL, '2026-11-13 12:49:24', '2026-11-13 12:49:24'),
(2, '192.168.1.8', 'Office Wi-Fi 2', 'active', 'Initial Authorized Office Wi-Fi 2', NULL, '2026-11-13 12:49:24', '2026-11-13 12:49:24'),
(5, '192.168.1.9', 'Office Wi-Fi 2 Workstation', 'active', 'Current Office Machine', NULL, '2026-11-13 13:51:44', '2026-11-13 13:51:44'),
(6, '192.168.1.*', 'Office Wi-Fi 2 Subnet', 'active', 'Authorized Office Wi-Fi 2 Network (*.1-254)', NULL, '2026-11-13 13:51:44', '2026-11-13 13:51:44'),
(7, '192.168.29.*', 'Office Wi-Fi 1 Subnet', 'active', 'Authorized Office Wi-Fi 1 Network (*.1-254)', NULL, '2026-11-13 13:51:44', '2026-11-13 13:51:44'),
(8, '106.219.148.*', 'Office Public Wi-Fi IPv4', 'active', 'Office Public ISP IPv4 Pool', NULL, '2026-11-13 13:51:44', '2026-11-13 13:51:44'),
(9, '2401:4900:883d:6edd:*', 'Office Public Wi-Fi IPv6', 'active', 'Office Public ISP IPv6 Subnet', NULL, '2026-11-13 13:51:44', '2026-11-13 13:51:44'),
(10, '2401:4900:883d:*', 'Office Public Wi-Fi IPv6 Prefix', 'active', 'Office Public ISP IPv6 Prefix', NULL, '2026-11-13 13:51:44', '2026-11-13 13:51:44'),
(11, '127.0.0.1', 'Local Server / Loopback', 'active', 'Local Development & Office Server', NULL, '2026-11-13 13:51:44', '2026-11-13 13:51:44'),
(12, '::1', 'IPv6 Loopback', 'active', 'Local IPv6 Loopback', NULL, '2026-11-13 13:51:44', '2026-11-13 13:51:44'),
(118313, '192.168.1.22', 'Office Wi-Fi 2 (Device 1.22)', 'active', 'Requested Office Wi-Fi IP 192.168.1.22', 1, '2026-11-16 09:55:07', '2026-11-16 09:55:07'),
(118334, '2401:4900:*', 'Office Wi-Fi Public IPv6 (All Blocks)', 'active', 'Office Airtel/Jio dynamic IPv6 pool (covers 883d, 883e, etc.)', 1, '2026-11-16 09:55:07', '2026-11-16 09:55:07');

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

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `sender_id`, `receiver_id`, `department_id`, `message`, `file_path`, `file_type`, `file_name`, `reply_to_id`, `is_seen`, `created_at`) VALUES
(1, 9, 10, NULL, 'bsdk', NULL, NULL, NULL, NULL, 1, '2026-10-14 17:14:29'),
(2, 10, 9, NULL, 'gandu', NULL, NULL, NULL, NULL, 1, '2026-10-14 17:15:04'),
(3, 10, 9, NULL, '', '/assets/uploads/chat/chat_1791978341_6acf6b65717e7.png', 'image/png', 'Clock Tower Roundabout with Mountain Backdrop.png', NULL, 1, '2026-10-14 17:15:41'),
(4, 9, 10, NULL, 'rat ko party', NULL, NULL, NULL, NULL, 1, '2026-10-14 17:16:09');

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
(2, 9, '2026-09-25', '09:40', '18:30', '0000-00-00 00:00:00', '2026-09-25 12:58:40', 7503, 0, 0, 0, 0, 1, 1, '[\"Office Work Management Portal\",\"Maharani Website\"]', 'Working on Kanban Drag & Drop Task Board; Waiting for client content', NULL, NULL, '2026-09-25 12:58:40', '2026-09-25 12:58:40'),
(3, 4, '2026-09-26', '09:40', '18:30', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1178, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-09-26 10:36:05', '2026-09-26 11:05:42'),
(4, 1, '2026-09-28', '09:40', '18:30', '2026-09-28 16:35:42', '2026-09-28 16:36:03', 21, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-09-28 16:36:03', '2026-09-28 16:36:03'),
(5, 12, '2026-09-30', '09:40', '18:30', '2026-09-30 09:40:50', '2026-09-30 18:29:29', 31719, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-09-30 18:29:29', '2026-09-30 18:29:29'),
(6, 12, '2026-10-01', '09:40', '18:30', '2026-10-01 09:39:51', '2026-10-01 18:30:11', 31820, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-01 18:30:11', '2026-10-01 18:30:11'),
(7, 12, '2026-10-02', '09:40', '18:30', '2026-10-02 09:34:35', '2026-10-02 18:31:36', 32221, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-02 18:31:36', '2026-10-02 18:31:36'),
(8, 12, '2026-10-05', '09:40', '18:30', '2026-10-05 09:57:30', '2026-10-05 18:31:07', 30817, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-05 18:31:07', '2026-10-05 18:31:07'),
(9, 12, '2026-10-06', '09:40', '18:30', '2026-10-06 09:41:15', '2026-10-06 18:32:58', 31903, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-06 18:32:58', '2026-10-06 18:32:58'),
(10, 12, '2026-10-07', '09:40', '18:30', '2026-10-07 09:35:18', '2026-10-07 18:32:25', 32227, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-07 18:32:25', '2026-10-07 18:32:25'),
(11, 12, '2026-10-09', '09:40', '18:30', '2026-10-09 09:43:45', '2026-10-09 18:35:28', 31902, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-09 18:35:28', '2026-10-09 18:35:28'),
(12, 5, '2026-10-10', '09:40', '18:30', '2026-10-10 10:49:25', '2026-10-10 10:49:32', 981, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-10 10:49:20', '2026-10-10 10:49:32'),
(13, 1, '2026-10-14', '09:40', '18:30', '2026-10-14 13:42:55', NULL, 17189, 0, 0, 0, 0, 1, 0, '[\"MJ Hotels\"]', 'None reported', NULL, NULL, '2026-10-14 18:29:32', '2026-10-14 18:29:32'),
(14, 8, '2026-10-15', '09:40', '18:30', '2026-10-15 09:35:02', '2026-10-15 18:32:30', 17827, 0, 0, 0, 0, 1, 1, '[\"STANDARD BAKERY\",\"Almost Heaven Rishikesh\"]', 'None reported', NULL, NULL, '2026-10-15 13:36:00', '2026-10-15 18:32:31'),
(15, 4, '2026-10-15', '09:40', '18:30', '2026-10-15 09:34:43', '2026-10-15 18:33:21', 31868, 0, 0, 0, 0, 2, 2, '[\"Maharani Website\",\"Krafting Digital\"]', 'None reported', NULL, NULL, '2026-10-15 15:09:07', '2026-10-15 18:33:21'),
(16, 9, '2026-10-15', '09:40', '18:30', '2026-10-15 09:39:22', '2026-10-15 18:32:33', 26871, 0, 0, 0, 0, 1, 1, '[\"Taj Mussoorie Foothills\"]', 'None reported', NULL, NULL, '2026-10-15 15:11:33', '2026-10-15 18:32:33'),
(17, 6, '2026-10-15', '09:40', '18:30', '2026-10-15 09:47:21', '2026-10-15 18:31:01', 30120, 0, 0, 0, 0, 0, 1, '[\"Jeevan Sankalp\"]', 'None reported', NULL, NULL, '2026-10-15 18:31:01', '2026-10-15 18:31:01'),
(18, 5, '2026-10-15', '09:40', '18:30', '2026-10-15 09:41:54', '2026-10-15 18:31:11', 30677, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-15 18:31:11', '2026-10-15 18:31:11'),
(19, 7, '2026-10-16', '09:40', '18:30', '2026-10-16 09:42:01', '2026-10-16 18:30:42', 31720, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-16 18:30:42', '2026-10-16 18:30:42'),
(20, 4, '2026-10-16', '09:40', '18:30', '2026-10-16 09:36:35', '2026-10-16 18:30:52', 32057, 0, 0, 0, 0, 0, 3, '[]', 'None reported', NULL, NULL, '2026-10-16 18:30:52', '2026-10-16 18:30:52'),
(21, 6, '2026-10-16', '09:40', '18:30', '2026-10-16 09:48:55', '2026-10-16 18:31:04', 16551, 0, 0, 0, 0, 0, 1, '[\"Jeevan Sankalp\"]', 'None reported', NULL, NULL, '2026-10-16 18:31:05', '2026-10-16 18:31:05'),
(22, 8, '2026-10-16', '09:40', '18:30', '2026-10-16 09:43:09', '2026-10-16 18:31:22', 31693, 0, 0, 0, 0, 0, 2, '[]', 'None reported', NULL, NULL, '2026-10-16 18:31:22', '2026-10-16 18:31:22'),
(23, 9, '2026-10-16', '09:40', '18:30', '2026-10-16 09:40:09', '2026-10-16 18:31:29', 31880, 0, 0, 0, 0, 0, 1, '[]', 'None reported', NULL, NULL, '2026-10-16 18:31:29', '2026-10-16 18:31:29'),
(24, 10, '2026-10-16', '09:40', '18:30', '2026-10-16 09:45:13', '2026-10-16 18:33:38', 31705, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-16 18:33:38', '2026-10-16 18:33:38'),
(25, 12, '2026-10-16', '09:40', '18:30', '2026-10-16 09:48:53', '2026-10-16 18:36:03', 31630, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-16 18:36:03', '2026-10-16 18:36:03'),
(26, 4, '2026-10-17', '09:40', '18:30', '2026-10-17 09:36:37', '2026-10-17 18:31:04', 8010, 0, 0, 0, 0, 3, 0, '[]', 'None reported', NULL, NULL, '2026-10-17 11:50:07', '2026-10-17 18:31:04'),
(27, 7, '2026-10-17', '09:40', '18:30', '2026-10-17 09:44:10', '2026-10-17 18:31:07', 31617, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-17 18:31:07', '2026-10-17 18:31:07'),
(28, 6, '2026-10-17', '09:40', '18:30', '2026-10-17 10:05:18', '2026-10-17 18:31:13', 30324, 0, 0, 0, 0, 0, 2, '[\"Jeevan Sankalp\"]', 'None reported', NULL, NULL, '2026-10-17 18:31:14', '2026-10-17 18:31:14'),
(29, 8, '2026-10-17', '09:40', '18:30', '2026-10-17 09:46:45', '2026-10-17 18:31:45', 31500, 0, 0, 0, 0, 0, 2, '[]', 'None reported', NULL, NULL, '2026-10-17 18:31:46', '2026-10-17 18:31:46'),
(30, 10, '2026-10-17', '09:40', '18:30', '2026-10-17 09:42:33', '2026-10-17 18:35:06', 31953, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-17 18:35:06', '2026-10-17 18:35:06'),
(31, 10, '2026-10-19', '09:40', '18:30', '2026-10-19 09:39:41', NULL, 6003, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-19 11:19:44', '2026-10-19 11:19:44'),
(32, 5, '2026-10-19', '09:40', '18:30', '2026-10-19 10:02:21', NULL, 10004, 0, 0, 0, 0, 1, 1, '[\"DR SHIVAM DANG\"]', 'None reported', NULL, NULL, '2026-10-19 13:03:05', '2026-10-19 13:03:05'),
(33, 12, '2026-10-19', '09:40', '18:30', '2026-10-19 11:40:06', '2026-10-19 18:31:36', 24690, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-19 18:31:36', '2026-10-19 18:31:36'),
(34, 5, '2026-10-21', '09:40', '18:30', '2026-10-21 09:39:55', '2026-10-21 18:30:36', 31841, 0, 0, 0, 0, 0, 1, '[]', 'None reported', NULL, NULL, '2026-10-21 18:30:36', '2026-10-21 18:30:36'),
(35, 4, '2026-10-21', '09:40', '18:30', '2026-10-21 09:45:04', '2026-10-21 18:31:07', 31563, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-21 18:31:07', '2026-10-21 18:31:07'),
(36, 8, '2026-10-21', '09:40', '18:30', '2026-10-21 09:58:34', '2026-10-21 18:31:09', 30755, 0, 0, 0, 0, 0, 2, '[]', 'None reported', NULL, NULL, '2026-10-21 18:31:09', '2026-10-21 18:31:09'),
(37, 12, '2026-10-21', '09:40', '18:30', '2026-10-21 09:39:48', '2026-10-21 18:31:13', 31884, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-21 18:31:13', '2026-10-21 18:31:13'),
(38, 10, '2026-10-21', '09:40', '18:30', '2026-10-21 09:44:29', '2026-10-21 18:34:46', 31817, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-21 18:34:46', '2026-10-21 18:34:46'),
(39, 7, '2026-10-21', '09:40', '18:30', '2026-10-21 09:43:23', '2026-10-21 18:35:49', 31946, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-21 18:35:49', '2026-10-21 18:35:49'),
(40, 9, '2026-10-21', '09:40', '18:30', '2026-10-21 09:34:57', '2026-10-21 18:36:41', 32193, 0, 0, 0, 0, 1, 1, '[]', 'None reported', NULL, NULL, '2026-10-21 18:36:41', '2026-10-21 18:36:41'),
(41, 2, '2026-10-21', '09:40', '18:30', '2026-10-21 10:15:50', '2026-10-21 18:41:47', 30357, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-21 18:41:47', '2026-10-21 18:41:47'),
(42, 10, '2026-10-22', '09:40', '18:30', '2026-10-22 09:40:41', NULL, 29614, 0, 0, 0, 0, 2, 0, '[]', 'None reported', NULL, NULL, '2026-10-22 10:20:31', '2026-10-22 18:18:51'),
(43, 8, '2026-10-22', '09:40', '18:30', '2026-10-22 09:50:18', NULL, 12316, 0, 0, 0, 0, 0, 2, '[\"Uttrakhand Stays Airbnb\"]', 'None reported', NULL, NULL, '2026-10-22 13:15:34', '2026-10-22 13:15:34'),
(44, 4, '2026-10-22', '09:40', '18:30', '2026-10-22 09:37:45', '2026-10-22 18:30:24', 31959, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-22 18:30:24', '2026-10-22 18:30:24'),
(45, 12, '2026-10-22', '09:40', '18:30', '2026-10-22 09:53:38', '2026-10-22 18:31:11', 31053, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-22 18:31:11', '2026-10-22 18:31:11'),
(46, 8, '2026-10-23', '09:40', '18:30', '2026-10-23 09:45:25', '2026-10-23 18:31:33', 2161, 0, 0, 0, 0, 2, 1, '[]', 'None reported', NULL, NULL, '2026-10-23 10:21:26', '2026-10-23 18:31:33'),
(47, 5, '2026-10-23', '09:40', '18:30', NULL, NULL, 0, 0, 0, 0, 0, 1, 1, '[]', 'None reported', NULL, NULL, '2026-10-23 12:18:29', '2026-10-23 12:18:29'),
(48, 9, '2026-10-23', '09:40', '18:30', '2026-10-23 08:55:21', '2026-10-23 18:29:17', 34436, 0, 0, 0, 0, 1, 0, '[]', 'None reported', NULL, NULL, '2026-10-23 18:29:17', '2026-10-23 18:29:17'),
(49, 4, '2026-10-23', '09:40', '18:30', '2026-10-23 09:25:56', '2026-10-23 18:31:10', 32714, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-23 18:31:10', '2026-10-23 18:31:10'),
(50, 7, '2026-10-23', '09:40', '18:30', '2026-10-23 09:44:35', '2026-10-23 18:31:45', 31630, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-23 18:31:45', '2026-10-23 18:31:45'),
(51, 10, '2026-10-23', '09:40', '18:30', '2026-10-23 09:43:25', '2026-10-23 18:33:10', 31785, 0, 0, 0, 0, 1, 1, '[]', 'None reported', NULL, NULL, '2026-10-23 18:33:10', '2026-10-23 18:33:10'),
(52, 12, '2026-10-23', '09:40', '18:30', '2026-10-23 09:44:03', '2026-10-23 18:36:07', 31924, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-23 18:36:07', '2026-10-23 18:36:07'),
(53, 10, '2026-10-24', '09:40', '18:30', NULL, NULL, 0, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-24 09:44:52', '2026-10-24 09:44:52'),
(54, 5, '2026-10-26', '09:40', '18:30', '2026-10-26 09:33:09', NULL, 2614, 0, 0, 0, 0, 7, 0, '[\"Masterpiece Psycology\",\"Jeevan Sankalp\",\"Krafting Digital\",\"Sheina Mill\",\"Almost Heaven Mussoorie\"]', 'None reported', NULL, NULL, '2026-10-26 10:10:03', '2026-10-26 15:50:40'),
(55, 6, '2026-10-26', '09:40', '18:30', '2026-10-26 09:30:04', '2026-10-26 18:26:44', 14695, 0, 0, 0, 0, 1, 1, '[]', 'None reported', NULL, NULL, '2026-10-26 18:26:44', '2026-10-26 18:26:44'),
(56, 10, '2026-10-26', '09:40', '18:30', '2026-10-26 09:45:55', '2026-10-26 18:29:24', 3737, 0, 0, 0, 0, 0, 1, '[]', 'None reported', NULL, NULL, '2026-10-26 18:29:24', '2026-10-26 18:29:24'),
(57, 12, '2026-10-26', '09:40', '18:30', '2026-10-26 09:55:43', '2026-10-26 18:44:09', 31706, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-26 18:44:09', '2026-10-26 18:44:09'),
(58, 5, '2026-10-27', '09:40', '18:30', '2026-10-27 09:35:11', NULL, 9929, 0, 0, 0, 0, 2, 0, '[\"Black Apple Cellular\"]', 'None reported', NULL, NULL, '2026-10-27 12:18:47', '2026-10-27 12:21:17'),
(59, 6, '2026-10-27', '09:40', '18:30', '2026-10-27 09:43:54', '2026-10-27 18:25:51', 31317, 0, 0, 0, 0, 0, 1, '[]', 'None reported', NULL, NULL, '2026-10-27 18:25:51', '2026-10-27 18:25:51'),
(60, 12, '2026-10-27', '09:40', '18:30', '2026-10-27 09:52:23', '2026-10-27 18:33:50', 31287, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-27 18:33:50', '2026-10-27 18:33:50'),
(61, 6, '2026-10-28', '09:40', '18:30', '2026-10-28 09:51:30', '2026-10-28 18:29:36', 1011, 0, 0, 0, 0, 1, 1, '[]', 'None reported', NULL, NULL, '2026-10-28 18:29:36', '2026-10-28 18:29:36'),
(62, 8, '2026-10-28', '09:40', '18:30', '2026-10-28 09:51:30', '2026-10-28 18:35:24', 31434, 0, 0, 0, 0, 0, 1, '[]', 'None reported', NULL, NULL, '2026-10-28 18:35:24', '2026-10-28 18:35:24'),
(63, 9, '2026-10-28', '09:40', '18:30', '2026-10-28 09:41:06', '2026-10-28 18:36:22', 32116, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-28 18:36:22', '2026-10-28 18:36:22'),
(64, 6, '2026-10-29', '09:40', '18:30', '2026-10-29 09:52:51', '2026-10-29 18:28:14', 30923, 0, 0, 0, 0, 0, 1, '[]', 'None reported', NULL, NULL, '2026-10-29 18:28:14', '2026-10-29 18:28:14'),
(65, 12, '2026-10-29', '09:40', '18:30', '2026-10-29 09:58:39', '2026-10-29 18:35:40', 31021, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-29 18:35:40', '2026-10-29 18:35:40'),
(66, 5, '2026-10-30', '09:40', '18:30', '2026-10-30 09:36:40', NULL, 10818, 0, 0, 0, 0, 2, 0, '[\"Turmeric SEO and Web development\"]', 'None reported', NULL, NULL, '2026-10-30 12:36:54', '2026-10-30 12:56:39'),
(67, 10, '2026-10-30', '09:40', '18:30', '2026-10-30 10:00:31', NULL, 11093, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-30 13:05:24', '2026-10-30 13:05:24'),
(68, 6, '2026-10-30', '09:40', '18:30', '2026-10-30 09:53:36', '2026-10-30 18:30:17', 31001, 0, 0, 0, 0, 0, 1, '[]', 'None reported', NULL, NULL, '2026-10-30 18:30:17', '2026-10-30 18:30:17'),
(69, 7, '2026-10-30', '09:40', '18:30', '2026-10-30 09:45:18', '2026-10-30 18:32:44', 31646, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-30 18:32:44', '2026-10-30 18:32:44'),
(70, 12, '2026-10-30', '09:40', '18:30', '2026-10-30 09:51:32', '2026-10-30 18:38:49', 31637, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-30 18:38:49', '2026-10-30 18:38:49'),
(71, 9, '2026-10-31', '09:40', '18:30', '2026-10-31 09:20:53', '2026-10-31 14:10:08', 6075, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-31 14:10:08', '2026-10-31 14:10:08'),
(72, 12, '2026-10-31', '09:40', '18:30', '2026-10-31 09:40:26', '2026-10-31 18:31:54', 21748, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-10-31 18:31:54', '2026-10-31 18:31:54'),
(73, 10, '2026-11-03', '09:40', '18:30', '2026-11-03 09:44:44', NULL, 13677, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-03 13:32:41', '2026-11-03 13:32:41'),
(74, 7, '2026-11-03', '09:40', '18:30', '2026-11-03 09:41:38', '2026-11-03 18:33:31', 31913, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-03 18:33:31', '2026-11-03 18:33:31'),
(75, 12, '2026-11-03', '09:40', '18:30', '2026-11-03 09:40:36', '2026-11-03 18:38:42', 32286, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-03 18:38:42', '2026-11-03 18:38:42'),
(76, 7, '2026-11-04', '09:40', '18:30', '2026-11-04 09:24:45', '2026-11-04 09:24:51', 6, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-04 09:24:51', '2026-11-04 09:24:51'),
(77, 12, '2026-11-05', '09:40', '18:30', '2026-11-05 10:05:21', '2026-11-05 18:37:05', 30704, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-05 18:37:05', '2026-11-05 18:37:05'),
(78, 12, '2026-11-12', '09:40', '18:30', '2026-11-12 09:49:26', '2026-11-12 18:32:54', 31408, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-12 18:32:54', '2026-11-12 18:32:54'),
(79, 12, '2026-11-13', '09:40', '18:30', '2026-11-13 10:00:33', '2026-11-13 18:30:41', 30608, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-13 18:30:41', '2026-11-13 18:30:41'),
(80, 12, '2026-11-14', '09:40', '18:30', '2026-11-14 09:46:24', '2026-11-14 18:31:51', 31527, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-14 18:31:51', '2026-11-14 18:31:51'),
(81, 2, '2026-11-18', '09:40', '18:30', '2026-11-18 17:03:35', '2026-11-18 17:03:41', 6, 0, 0, 0, 0, 0, 0, '[]', 'None reported', NULL, NULL, '2026-11-18 17:03:41', '2026-11-18 17:03:41');

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
(6, 'Product Management', 'PMO', 'Product roadmap, sprints, and feature planning', '2026-08-19 07:09:49'),
(7, 'Online Travel Agents', 'OTA', 'All task related to OTA will be mentioned', '2026-10-14 13:17:48'),
(8, 'Meta Ads &amp;amp;amp; Google Ads', 'M&AMP;AMP;AMP;G', 'All task related to ads', '2026-10-14 14:01:19'),
(9, 'photography shoots', 'PS', 'A professional photographer specializing in real estate, fashion, and hospitality photography, capturing stunning property interiors, stylish clothing collections, and beautiful homestay experiences through creative and high-quality visuals.', '2026-10-14 17:49:15'),
(14, 'Meta Ads & Google Ads', 'ADS', 'Meta Ads & Google Ads Campaign Management', '2026-10-15 17:10:07');

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
-- Table structure for table `emergency_task_requests`
--

CREATE TABLE `emergency_task_requests` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `reviewer_id` int(11) DEFAULT NULL,
  `reviewer_comment` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `emergency_task_requests`
--

INSERT INTO `emergency_task_requests` (`id`, `task_id`, `employee_id`, `reason`, `message`, `attachment`, `status`, `reviewer_id`, `reviewer_comment`, `rejection_reason`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 'Deadline Pressure & Sprint Deliverable', 'Requesting emergency priority approval to expedite live timer deployment before today 6 PM release.', NULL, 'pending', NULL, NULL, NULL, NULL, '2026-10-13 09:53:36', '2026-10-13 09:53:36'),
(2, 4, 6, 'Client Live Production Bug', 'Schema errors causing indexing drops on client production domain. Urgent hotfix needed.', NULL, 'approved', 5, 'Approved for immediate deployment. Coordinate with Rahul on release.', NULL, '2026-10-13 10:53:36', '2026-10-13 07:53:36', '2026-10-13 10:53:36'),
(3, 26, 9, 'Mobile Optimization Blocker', 'Need immediate clearance for touch drawer gesture redesign.', NULL, 'pending', NULL, NULL, NULL, NULL, '2026-10-13 11:23:36', '2026-10-13 11:23:36'),
(4, 46, 4, 'Other emergency', 'The &#039;Viral Bug Films&#039; website has received spam emails again.\r\nI am working on updating the code for all the forms on the website.', NULL, 'approved', 1, 'get this done and update me personally once done .', NULL, '2026-10-15 14:38:16', '2026-10-15 10:33:38', '2026-10-15 14:38:16'),
(5, 65, 6, 'Other emergency', 'Removing the backlinks for the masterpiece psychology', NULL, 'approved', 1, '', NULL, '2026-10-16 16:44:34', '2026-10-16 12:46:37', '2026-10-16 16:44:34'),
(6, 62, 1, 'Urgent client deadline', 'Get this done asap .', NULL, 'approved', 5, '', NULL, '2026-10-26 13:45:01', '2026-10-23 13:28:56', '2026-10-26 13:45:01');

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
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `client_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_activities`
--

INSERT INTO `employee_activities` (`id`, `employee_id`, `project_id`, `task_id`, `activity_type`, `status`, `start_time`, `end_time`, `duration`, `reason`, `notes`, `created_at`, `updated_at`, `client_name`) VALUES
(26, 9, 1, 3, 'task', 'completed', '2026-09-25 09:45:00', '2026-09-25 12:50:16', 7500, 'Working on Kanban Drag & Drop Task Board', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(27, 4, 1, 1, 'task', 'completed', '2026-09-25 09:40:00', '2026-09-26 10:35:04', 89704, 'Implementing Glassmorphic UI', NULL, '0000-00-00 00:00:00', '2026-09-26 10:35:04', NULL),
(28, 7, 3, 6, 'task', 'completed', '2026-09-25 09:50:00', '2026-09-28 10:54:48', 263088, 'Designing Dark Mode Color Palette', NULL, '0000-00-00 00:00:00', '2026-09-28 10:54:48', NULL),
(29, 5, 2, 4, 'meeting', 'completed', '2026-09-25 11:30:00', '2026-10-10 10:33:06', 1292586, 'SEO Strategy review with Client', NULL, '0000-00-00 00:00:00', '2026-10-10 10:33:06', NULL),
(30, 6, 2, 4, 'task', 'completed', '2026-09-25 10:00:00', '2026-09-28 09:48:51', 258531, 'Technical SEO site audit', NULL, '0000-00-00 00:00:00', '2026-09-28 09:48:51', NULL),
(31, 8, 4, 7, 'waiting', 'completed', '2026-09-25 11:15:00', '2026-09-30 09:57:04', 427324, 'Waiting for design creative approval', NULL, '0000-00-00 00:00:00', '2026-09-30 09:57:04', NULL),
(32, 9, 5, 26, 'Development', 'completed', '2026-09-25 15:15:00', '2026-09-25 12:54:19', 0, NULL, 'Mobile optimization, touch targets and cart drawer speed', '2026-09-25 12:50:16', '2026-09-25 12:54:19', NULL),
(33, 9, 5, 26, 'Development', 'completed', '2026-09-25 12:54:19', '2026-09-25 12:54:20', 1, NULL, 'Developing responsive touch drawer', '2026-09-25 12:54:19', '2026-09-25 12:54:20', NULL),
(34, 9, 5, 26, 'Client Communication', 'completed', '2026-09-25 12:54:20', '2026-09-25 12:54:20', 0, NULL, 'Discussed responsive changes with client', '2026-09-25 12:54:20', '2026-09-25 12:54:20', NULL),
(35, 9, 5, 26, 'Waiting for Content', 'completed', '2026-09-25 12:54:20', '2026-09-25 12:54:20', 0, 'Waiting for client content', 'Waiting for final high-res mobile banner images', '2026-09-25 12:54:20', '2026-09-25 12:54:20', NULL),
(36, 9, NULL, NULL, 'Meeting', 'completed', '2026-09-25 12:54:20', '2026-09-25 12:54:20', 0, NULL, 'In Meeting: Maharani Website Discussion', '2026-09-25 12:54:20', '2026-09-25 12:54:20', NULL),
(37, 9, 5, 26, 'Development', 'completed', '2026-09-25 12:55:38', '2026-09-25 12:55:39', 1, NULL, 'Developing responsive touch drawer', '2026-09-25 12:55:38', '2026-09-25 12:55:39', NULL),
(38, 9, 5, 26, 'Client Communication', 'completed', '2026-09-25 12:55:39', '2026-09-25 12:55:39', 0, NULL, 'Discussed responsive changes with client', '2026-09-25 12:55:39', '2026-09-25 12:55:39', NULL),
(39, 9, 5, 26, 'Waiting for Content', 'completed', '2026-09-25 12:55:39', '2026-09-25 12:55:39', 0, 'Waiting for client content', 'Waiting for final high-res mobile banner images', '2026-09-25 12:55:39', '2026-09-25 12:55:39', NULL),
(40, 9, NULL, NULL, 'Meeting', 'completed', '2026-09-25 12:55:39', '2026-09-25 12:55:39', 0, NULL, 'In Meeting: Maharani Website Discussion', '2026-09-25 12:55:39', '2026-09-25 12:55:39', NULL),
(41, 9, 5, 26, 'Development', 'completed', '2026-09-25 12:58:39', '2026-09-25 12:58:40', 1, NULL, 'Developing responsive touch drawer', '2026-09-25 12:58:39', '2026-09-25 12:58:40', NULL),
(42, 9, 5, 26, 'Client Communication', 'completed', '2026-09-25 12:58:40', '2026-09-25 12:58:40', 0, NULL, 'Discussed responsive changes with client', '2026-09-25 12:58:40', '2026-09-25 12:58:40', NULL),
(43, 9, 5, 26, 'Waiting for Content', 'completed', '2026-09-25 12:58:40', '2026-09-25 12:58:40', 0, 'Waiting for client content', 'Waiting for final high-res mobile banner images', '2026-09-25 12:58:40', '2026-09-25 12:58:40', NULL),
(44, 9, NULL, NULL, 'Meeting', 'completed', '2026-09-25 12:58:40', '2026-09-25 12:58:40', 0, NULL, 'In Meeting: Maharani Website Discussion', '2026-09-25 12:58:40', '2026-09-25 12:58:40', NULL),
(45, 4, NULL, NULL, 'Working on Task', 'completed', '2026-09-26 10:35:04', '2026-09-26 10:36:05', 61, NULL, 'Shift started', '2026-09-26 10:35:04', '2026-09-26 10:36:05', NULL),
(46, 4, NULL, NULL, 'Working on Task', 'completed', '2026-09-26 10:36:43', '2026-09-26 10:39:05', 142, NULL, 'Shift started', '2026-09-26 10:36:43', '2026-09-26 10:39:05', NULL),
(47, 10, NULL, NULL, 'Working on Task', 'completed', '2026-09-26 10:39:36', '2026-09-28 09:58:57', 170361, NULL, 'Shift started', '2026-09-26 10:39:36', '2026-09-28 09:58:57', NULL),
(48, 4, NULL, NULL, 'Working on Task', 'completed', '2026-09-26 10:49:27', '2026-09-26 11:05:42', 975, NULL, 'Shift started', '2026-09-26 10:49:27', '2026-09-26 11:05:42', NULL),
(49, 4, NULL, NULL, 'Working on Task', 'completed', '2026-09-26 11:07:22', '2026-10-06 09:33:24', 858362, NULL, 'Shift started', '2026-09-26 11:07:22', '2026-10-06 09:33:24', NULL),
(50, 9, NULL, NULL, 'Working on Task', 'completed', '2026-09-28 09:42:46', '2026-09-29 09:35:36', 85970, NULL, 'Shift started', '2026-09-28 09:42:46', '2026-09-29 09:35:36', NULL),
(51, 6, NULL, NULL, 'Working on Task', 'completed', '2026-09-28 09:48:51', '2026-09-29 09:56:47', 86876, NULL, 'Shift started', '2026-09-28 09:48:51', '2026-09-29 09:56:47', NULL),
(52, 10, NULL, NULL, 'Working on Task', 'completed', '2026-09-28 09:58:57', '2026-10-01 09:47:23', 258506, NULL, 'Shift started', '2026-09-28 09:58:57', '2026-10-01 09:47:23', NULL),
(53, 7, NULL, NULL, 'Working on Task', 'completed', '2026-09-28 10:54:48', '2026-09-29 09:51:25', 82597, NULL, 'Shift started', '2026-09-28 10:54:48', '2026-09-29 09:51:25', NULL),
(54, 1, NULL, NULL, 'Working on Task', 'completed', '2026-09-28 16:35:42', '2026-09-28 16:36:03', 21, NULL, 'Shift started', '2026-09-28 16:35:42', '2026-09-28 16:36:03', NULL),
(55, 2, NULL, NULL, 'Working on Task', 'completed', '2026-09-28 16:53:38', '2026-10-19 10:04:50', 1789872, NULL, 'Shift started', '2026-09-28 16:53:38', '2026-10-19 10:04:50', NULL),
(56, 9, NULL, NULL, 'Working on Task', 'completed', '2026-09-29 09:35:36', '2026-09-30 09:53:31', 87475, NULL, 'Shift started', '2026-09-29 09:35:36', '2026-09-30 09:53:31', NULL),
(57, 12, NULL, NULL, 'Working on Task', 'completed', '2026-09-29 09:48:31', '2026-09-30 09:40:50', 85939, NULL, 'Shift started', '2026-09-29 09:48:31', '2026-09-30 09:40:50', NULL),
(58, 7, NULL, NULL, 'Working on Task', 'completed', '2026-09-29 09:51:25', '2026-10-01 09:50:52', 172767, NULL, 'Shift started', '2026-09-29 09:51:25', '2026-10-01 09:50:52', NULL),
(59, 6, NULL, NULL, 'Working on Task', 'completed', '2026-09-29 09:56:47', '2026-09-30 09:54:57', 86290, NULL, 'Shift started', '2026-09-29 09:56:47', '2026-09-30 09:54:57', NULL),
(60, 12, NULL, NULL, 'Working on Task', 'completed', '2026-09-30 09:40:50', '2026-09-30 18:29:29', 31719, NULL, 'Shift started', '2026-09-30 09:40:50', '2026-09-30 18:29:29', NULL),
(61, 9, NULL, NULL, 'Working on Task', 'completed', '2026-09-30 09:53:31', '2026-10-01 09:39:54', 85583, NULL, 'Shift started', '2026-09-30 09:53:31', '2026-10-01 09:39:54', NULL),
(62, 6, NULL, NULL, 'Working on Task', 'completed', '2026-09-30 09:54:57', '2026-10-01 10:09:46', 87289, NULL, 'Shift started', '2026-09-30 09:54:57', '2026-10-01 10:09:46', NULL),
(63, 8, NULL, NULL, 'Working on Task', 'completed', '2026-09-30 09:57:04', '2026-10-01 09:45:37', 85713, NULL, 'Shift started', '2026-09-30 09:57:04', '2026-10-01 09:45:37', NULL),
(64, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-01 09:39:51', '2026-10-01 18:30:11', 31820, NULL, 'Shift started', '2026-10-01 09:39:51', '2026-10-01 18:30:11', NULL),
(65, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-01 09:39:54', '2026-10-02 09:37:59', 86285, NULL, 'Shift started', '2026-10-01 09:39:54', '2026-10-02 09:37:59', NULL),
(66, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-01 09:45:37', '2026-10-02 09:39:56', 86059, NULL, 'Shift started', '2026-10-01 09:45:37', '2026-10-02 09:39:56', NULL),
(67, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-01 09:47:23', '2026-10-02 09:41:11', 86028, NULL, 'Shift started', '2026-10-01 09:47:23', '2026-10-02 09:41:11', NULL),
(68, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-01 09:50:52', '2026-10-05 10:20:39', 347387, NULL, 'Shift started', '2026-10-01 09:50:52', '2026-10-05 10:20:39', NULL),
(69, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-01 10:09:46', '2026-10-02 09:46:46', 85020, NULL, 'Shift started', '2026-10-01 10:09:46', '2026-10-02 09:46:46', NULL),
(70, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-02 09:34:35', '2026-10-02 18:31:36', 32221, NULL, 'Shift started', '2026-10-02 09:34:35', '2026-10-02 18:31:36', NULL),
(71, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-02 09:37:59', '2026-10-05 09:40:02', 259323, NULL, 'Shift started', '2026-10-02 09:37:59', '2026-10-05 09:40:02', NULL),
(72, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-02 09:39:56', '2026-10-06 09:50:43', 346247, NULL, 'Shift started', '2026-10-02 09:39:56', '2026-10-06 09:50:43', NULL),
(73, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-02 09:41:11', '2026-10-05 09:54:54', 260023, NULL, 'Shift started', '2026-10-02 09:41:11', '2026-10-05 09:54:54', NULL),
(74, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-02 09:46:46', '2026-10-05 10:01:45', 260099, NULL, 'Shift started', '2026-10-02 09:46:46', '2026-10-05 10:01:45', NULL),
(75, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-05 09:40:02', '2026-10-06 09:47:42', 86860, NULL, 'Shift started', '2026-10-05 09:40:02', '2026-10-06 09:47:42', NULL),
(76, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-05 09:54:54', '2026-10-06 09:41:07', 85573, NULL, 'Shift started', '2026-10-05 09:54:54', '2026-10-06 09:41:07', NULL),
(77, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-05 09:57:30', '2026-10-05 18:31:07', 30817, NULL, 'Shift started', '2026-10-05 09:57:30', '2026-10-05 18:31:07', NULL),
(78, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-05 10:01:45', '2026-10-06 09:50:28', 85723, NULL, 'Shift started', '2026-10-05 10:01:45', '2026-10-06 09:50:28', NULL),
(79, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-05 10:20:39', '2026-10-06 09:40:25', 83986, NULL, 'Shift started', '2026-10-05 10:20:39', '2026-10-06 09:40:25', NULL),
(80, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-06 09:33:24', '2026-10-07 09:30:53', 86249, NULL, 'Shift started', '2026-10-06 09:33:24', '2026-10-07 09:30:53', NULL),
(81, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-06 09:40:25', '2026-10-07 10:12:03', 88298, NULL, 'Shift started', '2026-10-06 09:40:25', '2026-10-07 10:12:03', NULL),
(82, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-06 09:41:07', '2026-10-08 09:43:59', 172972, NULL, 'Shift started', '2026-10-06 09:41:07', '2026-10-08 09:43:59', NULL),
(83, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-06 09:41:15', '2026-10-06 18:32:58', 31903, NULL, 'Shift started', '2026-10-06 09:41:15', '2026-10-06 18:32:58', NULL),
(84, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-06 09:47:42', '2026-10-07 10:03:01', 87319, NULL, 'Shift started', '2026-10-06 09:47:42', '2026-10-07 10:03:01', NULL),
(85, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-06 09:50:28', '2026-10-07 10:03:03', 87155, NULL, 'Shift started', '2026-10-06 09:50:28', '2026-10-07 10:03:03', NULL),
(86, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-06 09:50:43', '2026-10-07 09:56:03', 86720, NULL, 'Shift started', '2026-10-06 09:50:43', '2026-10-07 09:56:03', NULL),
(87, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-07 09:30:53', '2026-10-09 09:38:01', 173228, NULL, 'Shift started', '2026-10-07 09:30:53', '2026-10-09 09:38:01', NULL),
(88, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-07 09:35:18', '2026-10-07 18:32:25', 32227, NULL, 'Shift started', '2026-10-07 09:35:18', '2026-10-07 18:32:25', NULL),
(89, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-07 09:56:03', '2026-10-08 10:00:35', 86672, NULL, 'Shift started', '2026-10-07 09:56:03', '2026-10-08 10:00:35', NULL),
(90, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-07 10:03:01', '2026-10-08 09:40:04', 85023, NULL, 'Shift started', '2026-10-07 10:03:01', '2026-10-08 09:40:04', NULL),
(91, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-07 10:03:03', '2026-10-08 09:56:44', 86021, NULL, 'Shift started', '2026-10-07 10:03:03', '2026-10-08 09:56:44', NULL),
(92, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-07 10:12:03', '2026-10-08 09:45:55', 84832, NULL, 'Shift started', '2026-10-07 10:12:03', '2026-10-08 09:45:55', NULL),
(93, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-08 09:40:04', '2026-10-09 10:03:05', 87781, NULL, 'Shift started', '2026-10-08 09:40:04', '2026-10-09 10:03:05', NULL),
(94, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-08 09:43:59', '2026-10-09 09:54:28', 87029, NULL, 'Shift started', '2026-10-08 09:43:59', '2026-10-09 09:54:28', NULL),
(95, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-08 09:45:55', '2026-10-09 09:52:50', 86815, NULL, 'Shift started', '2026-10-08 09:45:55', '2026-10-09 09:52:50', NULL),
(96, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-08 09:56:44', '2026-10-09 10:06:22', 86978, NULL, 'Shift started', '2026-10-08 09:56:44', '2026-10-09 10:06:22', NULL),
(97, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-08 10:00:35', '2026-10-09 13:40:44', 99609, NULL, 'Shift started', '2026-10-08 10:00:35', '2026-10-09 13:40:44', NULL),
(98, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-09 09:38:01', '2026-10-10 09:33:23', 86122, NULL, 'Shift started', '2026-10-09 09:38:01', '2026-10-10 09:33:23', NULL),
(99, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-09 09:43:46', '2026-10-09 18:35:28', 31902, NULL, 'Shift started', '2026-10-09 09:43:46', '2026-10-09 18:35:28', NULL),
(100, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-09 09:52:50', '2026-10-10 09:44:09', 85879, NULL, 'Shift started', '2026-10-09 09:52:50', '2026-10-10 09:44:09', NULL),
(101, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-09 09:54:28', '2026-10-10 10:00:51', 86783, NULL, 'Shift started', '2026-10-09 09:54:28', '2026-10-10 10:00:51', NULL),
(102, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-09 10:03:05', '2026-10-10 09:42:20', 85155, NULL, 'Shift started', '2026-10-09 10:03:05', '2026-10-10 09:42:20', NULL),
(103, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-09 10:06:22', '2026-10-10 09:40:43', 84861, NULL, 'Shift started', '2026-10-09 10:06:22', '2026-10-10 09:40:43', NULL),
(104, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-09 13:40:44', '2026-10-10 10:57:08', 76584, NULL, 'Shift started', '2026-10-09 13:40:44', '2026-10-10 10:57:08', NULL),
(105, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-10 09:33:23', '2026-10-12 09:30:54', 172651, NULL, 'Shift started', '2026-10-10 09:33:23', '2026-10-12 09:30:54', NULL),
(106, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-10 09:40:43', '2026-10-12 09:34:46', 172443, NULL, 'Shift started', '2026-10-10 09:40:43', '2026-10-12 09:34:46', NULL),
(107, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-10 09:42:20', '2026-10-12 09:50:11', 173271, NULL, 'Shift started', '2026-10-10 09:42:20', '2026-10-12 09:50:11', NULL),
(108, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-10 09:44:09', '2026-10-12 09:47:09', 172980, NULL, 'Shift started', '2026-10-10 09:44:09', '2026-10-12 09:47:09', NULL),
(109, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-10 10:00:51', '2026-10-12 09:44:28', 171817, NULL, 'Shift started', '2026-10-10 10:00:51', '2026-10-12 09:44:28', NULL),
(110, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-10 10:33:06', '2026-10-10 10:49:20', 974, NULL, 'Shift started', '2026-10-10 10:33:06', '2026-10-10 10:49:20', NULL),
(111, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-10 10:49:25', '2026-10-10 10:49:32', 7, NULL, 'Shift started', '2026-10-10 10:49:25', '2026-10-10 10:49:32', NULL),
(112, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-10 10:57:08', '2026-10-12 09:56:55', 169187, NULL, 'Shift started', '2026-10-10 10:57:08', '2026-10-12 09:56:55', NULL),
(113, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-12 09:24:55', '2026-10-13 09:46:22', 87687, NULL, 'Shift started', '2026-10-12 09:24:55', '2026-10-13 09:46:22', NULL),
(114, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-12 09:29:42', '2026-10-13 09:31:37', 86515, NULL, 'Shift started', '2026-10-12 09:29:42', '2026-10-13 09:31:37', NULL),
(115, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-12 09:30:54', '2026-10-13 09:31:06', 86412, NULL, 'Shift started', '2026-10-12 09:30:54', '2026-10-13 09:31:06', NULL),
(116, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-12 09:34:46', '2026-10-13 09:26:03', 85877, NULL, 'Shift started', '2026-10-12 09:34:46', '2026-10-13 09:26:03', NULL),
(117, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-12 09:44:28', '2026-10-13 09:38:54', 86066, NULL, 'Shift started', '2026-10-12 09:44:28', '2026-10-13 09:38:54', NULL),
(118, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-12 09:47:09', '2026-10-13 09:45:01', 86272, NULL, 'Shift started', '2026-10-12 09:47:09', '2026-10-13 09:45:01', NULL),
(119, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-12 09:50:11', '2026-10-13 09:41:39', 85888, NULL, 'Shift started', '2026-10-12 09:50:11', '2026-10-13 09:41:39', NULL),
(120, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-12 09:56:55', '2026-10-13 09:48:54', 85919, NULL, 'Shift started', '2026-10-12 09:56:55', '2026-10-13 09:48:54', NULL),
(121, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 09:26:03', '2026-10-14 09:40:38', 87275, NULL, 'Shift started', '2026-10-13 09:26:03', '2026-10-14 09:40:38', NULL),
(122, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 09:31:06', '2026-10-14 09:33:39', 86553, NULL, 'Shift started', '2026-10-13 09:31:06', '2026-10-14 09:33:39', NULL),
(123, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 09:31:37', '2026-10-14 09:53:54', 87737, NULL, 'Shift started', '2026-10-13 09:31:37', '2026-10-14 09:53:54', NULL),
(124, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 09:38:54', '2026-10-14 09:48:52', 86998, NULL, 'Shift started', '2026-10-13 09:38:54', '2026-10-14 09:48:52', NULL),
(125, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 09:41:39', '2026-10-14 09:40:54', 86355, NULL, 'Shift started', '2026-10-13 09:41:39', '2026-10-14 09:40:54', NULL),
(126, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 09:45:01', '2026-10-14 09:44:52', 86391, NULL, 'Shift started', '2026-10-13 09:45:01', '2026-10-14 09:44:52', NULL),
(127, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 09:46:22', '2026-10-14 09:50:28', 86646, NULL, 'Shift started', '2026-10-13 09:46:22', '2026-10-14 09:50:28', NULL),
(128, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 09:48:54', '2026-10-14 09:58:24', 86970, NULL, 'Shift started', '2026-10-13 09:48:54', '2026-10-14 09:58:24', NULL),
(129, 1, NULL, NULL, 'Working on Task', 'completed', '2026-10-13 14:37:16', '2026-10-14 13:42:55', 83139, NULL, 'Shift started', '2026-10-13 14:37:16', '2026-10-14 13:42:55', NULL),
(130, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 09:33:39', '2026-10-15 09:34:43', 86464, NULL, 'Shift started', '2026-10-14 09:33:39', '2026-10-15 09:34:43', NULL),
(131, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 09:40:38', '2026-10-15 09:47:21', 86803, NULL, 'Shift started', '2026-10-14 09:40:38', '2026-10-15 09:47:21', NULL),
(132, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 09:40:54', '2026-10-15 09:39:22', 86308, NULL, 'Shift started', '2026-10-14 09:40:54', '2026-10-15 09:39:22', NULL),
(133, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 09:44:52', '2026-10-15 09:44:02', 86350, NULL, 'Shift started', '2026-10-14 09:44:52', '2026-10-15 09:44:02', NULL),
(134, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 09:48:52', '2026-10-15 09:43:07', 86055, NULL, 'Shift started', '2026-10-14 09:48:52', '2026-10-15 09:43:07', NULL),
(135, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 09:50:28', '2026-10-15 09:50:00', 86372, NULL, 'Shift started', '2026-10-14 09:50:28', '2026-10-15 09:50:00', NULL),
(136, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 09:53:54', '2026-10-15 09:59:54', 86760, NULL, 'Shift started', '2026-10-14 09:53:54', '2026-10-15 09:59:54', NULL),
(137, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 09:58:24', '2026-10-15 09:35:02', 84998, NULL, 'Shift started', '2026-10-14 09:58:24', '2026-10-15 09:35:02', NULL),
(138, 1, NULL, NULL, 'Working on Task', 'completed', '2026-10-14 13:42:55', '2026-10-14 18:29:00', 17165, NULL, 'Shift started', '2026-10-14 13:42:55', '2026-10-14 18:29:00', NULL),
(139, 1, 9, 30, 'Working on Task', 'completed', '2026-10-14 18:29:00', '2026-10-14 18:29:24', 24, NULL, 'Working on: Mj Residency Upload New Photoshoots On MMT', '2026-10-14 18:29:00', '2026-10-14 18:29:24', NULL),
(140, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 09:34:43', '2026-10-15 10:23:11', 2908, NULL, 'Shift started', '2026-10-15 09:34:43', '2026-10-15 10:23:11', NULL),
(141, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 09:35:02', '2026-10-15 13:34:56', 14394, NULL, 'Shift started', '2026-10-15 09:35:02', '2026-10-15 13:34:56', NULL),
(142, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 09:39:22', '2026-10-15 10:37:37', 3495, NULL, 'Shift started', '2026-10-15 09:39:22', '2026-10-15 10:37:37', NULL),
(143, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 09:43:07', '2026-10-16 09:45:13', 86526, NULL, 'Shift started', '2026-10-15 09:43:07', '2026-10-16 09:45:13', NULL),
(144, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 09:44:02', '2026-10-16 09:42:02', 86280, NULL, 'Shift started', '2026-10-15 09:44:02', '2026-10-16 09:42:02', NULL),
(145, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 09:47:21', '2026-10-15 10:28:56', 2495, NULL, 'Shift started', '2026-10-15 09:47:21', '2026-10-15 10:28:56', NULL),
(146, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 09:50:00', '2026-10-16 09:48:53', 86333, NULL, 'Shift started', '2026-10-15 09:50:00', '2026-10-16 09:48:53', NULL),
(147, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 09:59:54', '2026-10-15 18:31:11', 30677, NULL, 'Shift started', '2026-10-15 09:59:54', '2026-10-15 18:31:11', NULL),
(148, 4, 5, 46, 'Working on Task', 'completed', '2026-10-15 10:23:11', '2026-10-15 12:03:07', 5996, NULL, 'Working on: Make Final Menu software for Maharani', '2026-10-15 10:23:11', '2026-10-15 12:03:07', NULL),
(149, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-15 10:25:07', '2026-10-16 11:42:56', 91069, NULL, 'Shift started', '2026-10-15 10:25:07', '2026-10-16 11:42:56', NULL),
(150, 6, 18, 65, 'Working on Task', 'completed', '2026-10-15 10:28:56', '2026-10-15 10:29:45', 49, NULL, 'Working on: Make 100 backlinks for jeeven sankalp', '2026-10-15 10:28:56', '2026-10-15 10:29:45', NULL),
(151, 6, 18, 65, 'Working on Task', 'completed', '2026-10-15 10:29:45', '2026-10-15 18:07:01', 27436, NULL, 'Working on: Make 100 backlinks for jeeven sankalp', '2026-10-15 10:29:45', '2026-10-15 18:07:01', NULL),
(152, 9, 14, 69, 'Working on Task', 'completed', '2026-10-15 10:37:37', '2026-10-15 15:11:33', 16436, NULL, 'Working on: Fix Destination Wedding Website .(The task has been assigned to Abhay.)', '2026-10-15 10:37:37', '2026-10-15 15:11:33', NULL),
(153, 4, 5, 45, 'Working on Task', 'completed', '2026-10-15 12:03:07', '2026-10-15 12:03:16', 9, NULL, 'Working on: Make Final Maharani Website', '2026-10-15 12:03:07', '2026-10-15 12:03:16', NULL),
(154, 4, 5, 45, 'Working on Task', 'completed', '2026-10-15 12:03:16', '2026-10-15 15:09:07', 11151, NULL, 'Working on: Make Final Maharani Website', '2026-10-15 12:03:16', '2026-10-15 15:09:07', NULL),
(155, 8, 21, 64, 'Working on Task', 'completed', '2026-10-15 13:34:56', '2026-10-15 13:36:00', 64, NULL, 'Working on: Standard Social Media', '2026-10-15 13:34:56', '2026-10-15 13:36:00', NULL),
(156, 8, 12, 68, 'Working on Task', 'completed', '2026-10-15 13:40:27', '2026-10-15 14:32:35', 3128, NULL, 'Working on: Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )', '2026-10-15 13:40:27', '2026-10-15 14:32:35', NULL),
(157, 8, 12, 68, 'Working on Task', 'completed', '2026-10-15 14:32:43', '2026-10-15 14:33:04', 21, NULL, 'Working on: Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )', '2026-10-15 14:32:43', '2026-10-15 14:33:04', NULL),
(158, 8, 12, 68, 'Working on Task', 'completed', '2026-10-15 14:33:04', '2026-10-15 14:33:06', 2, NULL, 'Working on: Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )', '2026-10-15 14:33:04', '2026-10-15 14:33:06', NULL),
(159, 8, 12, 68, 'Working on Task', 'completed', '2026-10-15 14:33:27', '2026-10-15 14:34:25', 58, NULL, 'Working on: Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )', '2026-10-15 14:33:27', '2026-10-15 14:34:25', NULL),
(160, 8, 12, 68, 'Working on Task', 'completed', '2026-10-15 14:34:25', '2026-10-15 14:36:50', 145, NULL, 'Working on: Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )', '2026-10-15 14:34:25', '2026-10-15 14:36:50', NULL),
(161, 4, 6, 34, 'Working on Task', 'completed', '2026-10-15 15:09:54', '2026-10-15 17:41:21', 9087, NULL, 'Working on: Change Logo on Krafting digital Website', '2026-10-15 15:09:54', '2026-10-15 17:41:21', NULL),
(162, 8, 12, 68, 'Working on Task', 'completed', '2026-10-15 15:39:22', '2026-10-15 15:39:37', 15, NULL, 'Working on: Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )', '2026-10-15 15:39:22', '2026-10-15 15:39:37', NULL),
(163, 9, NULL, 81, 'Working on Task', 'completed', '2026-10-15 16:35:05', '2026-10-15 18:30:45', 6940, NULL, 'Working on: Shein Mill Website Development', '2026-10-15 16:35:05', '2026-10-15 18:30:45', NULL),
(164, 4, 5, 28, 'Working on Task', 'completed', '2026-10-15 17:41:47', '2026-10-15 18:27:04', 2717, NULL, 'Working on: Make Website Requirement Docs and Send in Maharani Client Group', '2026-10-15 17:41:47', '2026-10-15 18:27:04', NULL),
(165, 6, 18, 65, 'Working on Task', 'completed', '2026-10-15 18:09:46', '2026-10-15 18:12:06', 140, NULL, 'Working on: Make 100 backlinks for jeeven sankalp', '2026-10-15 18:09:46', '2026-10-15 18:12:06', NULL),
(166, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-16 09:36:35', '2026-10-16 18:30:52', 32057, NULL, 'Shift started', '2026-10-16 09:36:35', '2026-10-16 18:30:52', NULL),
(167, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-16 09:40:09', '2026-10-16 09:59:20', 1151, NULL, 'Shift started', '2026-10-16 09:40:09', '2026-10-16 09:59:20', NULL),
(168, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-16 09:42:02', '2026-10-16 18:30:42', 31720, NULL, 'Shift started', '2026-10-16 09:42:02', '2026-10-16 18:30:42', NULL),
(169, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-16 09:43:09', '2026-10-16 18:31:22', 31693, NULL, 'Shift started', '2026-10-16 09:43:09', '2026-10-16 18:31:22', NULL),
(170, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-16 09:45:13', '2026-10-16 18:33:38', 31705, NULL, 'Shift started', '2026-10-16 09:45:13', '2026-10-16 18:33:38', NULL),
(171, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-16 09:48:53', '2026-10-16 18:36:03', 31630, NULL, 'Shift started', '2026-10-16 09:48:53', '2026-10-16 18:36:03', NULL),
(172, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-16 09:48:55', '2026-10-16 10:03:06', 851, NULL, 'Shift started', '2026-10-16 09:48:55', '2026-10-16 10:03:06', NULL),
(173, 9, NULL, 81, 'Working on Task', 'completed', '2026-10-16 09:59:20', '2026-10-16 18:31:29', 30729, NULL, 'Working on: Shein Mill Website Development', '2026-10-16 09:59:20', '2026-10-16 18:31:29', NULL),
(174, 6, 18, 65, 'Working on Task', 'completed', '2026-10-16 10:03:06', '2026-10-16 10:06:05', 179, NULL, 'Working on: Make 100 backlinks for jeeven sankalp', '2026-10-16 10:03:06', '2026-10-16 10:06:05', NULL),
(175, 6, 18, 65, 'Working on Task', 'completed', '2026-10-16 10:06:05', '2026-10-16 12:45:19', 9554, NULL, 'Working on: Make 100 backlinks for jeeven sankalp', '2026-10-16 10:06:05', '2026-10-16 12:45:19', NULL),
(176, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-16 11:42:56', '2026-10-17 15:07:26', 98670, NULL, 'Shift started', '2026-10-16 11:42:56', '2026-10-17 15:07:26', NULL),
(177, 6, 18, 65, 'Working on Task', 'completed', '2026-10-16 16:50:38', '2026-10-16 18:30:05', 5967, NULL, 'Working on: Make 100 backlinks for jeeven sankalp', '2026-10-16 16:50:38', '2026-10-16 18:30:05', NULL),
(178, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 09:36:33', '2026-10-19 11:40:06', 180213, NULL, 'Shift started', '2026-10-17 09:36:33', '2026-10-19 11:40:06', NULL),
(179, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 09:36:37', '2026-10-17 11:50:07', 8010, NULL, 'Shift started', '2026-10-17 09:36:37', '2026-10-17 11:50:07', NULL),
(180, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 09:42:21', '2026-10-17 09:54:26', 725, NULL, 'Shift started', '2026-10-17 09:42:21', '2026-10-17 09:54:26', NULL),
(181, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 09:42:33', '2026-10-17 18:35:06', 31953, NULL, 'Shift started', '2026-10-17 09:42:33', '2026-10-17 18:35:06', NULL),
(182, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 09:44:10', '2026-10-17 18:31:07', 31617, NULL, 'Shift started', '2026-10-17 09:44:10', '2026-10-17 18:31:07', NULL),
(183, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 09:46:45', '2026-10-17 18:31:45', 31500, NULL, 'Shift started', '2026-10-17 09:46:45', '2026-10-17 18:31:45', NULL),
(184, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 10:05:19', '2026-10-17 10:06:59', 100, NULL, 'Shift started', '2026-10-17 10:05:19', '2026-10-17 10:06:59', NULL),
(185, 6, 18, 55, 'Working on Task', 'completed', '2026-10-17 10:06:59', '2026-10-17 18:30:43', 30224, NULL, 'Working on: Make 100 backlinks for jeeven sankalp', '2026-10-17 10:06:59', '2026-10-17 18:30:43', NULL),
(186, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 10:27:25', '2026-10-17 15:32:46', 18321, NULL, 'Shift started', '2026-10-17 10:27:25', '2026-10-17 15:32:46', NULL),
(187, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-17 15:07:26', '2026-10-19 12:09:14', 162108, NULL, 'Shift started', '2026-10-17 15:07:26', '2026-10-19 12:09:14', NULL),
(188, 5, 13, 37, 'Working on Task', 'completed', '2026-10-17 15:32:46', '2026-10-17 15:32:59', 13, NULL, 'Working on: Make SEO implementation plan for Masterpiece Psycology', '2026-10-17 15:32:46', '2026-10-17 15:32:59', NULL),
(189, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 09:35:39', '2026-10-21 09:34:57', 172758, NULL, 'Shift started', '2026-10-19 09:35:39', '2026-10-21 09:34:57', NULL),
(190, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 09:39:14', '2026-10-21 09:45:04', 173150, NULL, 'Shift started', '2026-10-19 09:39:14', '2026-10-21 09:45:04', NULL),
(191, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 09:39:41', '2026-10-19 11:18:41', 5940, NULL, 'Shift started', '2026-10-19 09:39:41', '2026-10-19 11:18:41', NULL),
(192, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 09:42:49', '2026-10-21 09:43:23', 172834, NULL, 'Shift started', '2026-10-19 09:42:49', '2026-10-21 09:43:23', NULL),
(193, 14, NULL, NULL, 'Working on Task', 'active', '2026-10-19 09:52:06', NULL, 0, NULL, 'Shift started', '2026-10-19 09:52:06', '2026-10-19 09:52:06', NULL),
(194, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 10:01:40', '2026-10-19 10:19:03', 1043, NULL, 'Shift started', '2026-10-19 10:01:40', '2026-10-19 10:19:03', NULL),
(195, 2, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 10:04:50', '2026-10-21 10:15:50', 173460, NULL, 'Shift started', '2026-10-19 10:04:50', '2026-10-21 10:15:50', NULL),
(196, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 10:16:21', '2026-10-19 13:01:43', 9922, NULL, 'Shift started', '2026-10-19 10:16:21', '2026-10-19 13:01:43', NULL),
(197, 6, 18, 65, 'Working on Task', 'completed', '2026-10-19 10:19:03', '2026-10-21 09:54:47', 171344, NULL, 'Working on: Make 100 backlinks for jeeven sankalp', '2026-10-19 10:19:03', '2026-10-21 09:54:47', NULL),
(198, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 10:36:20', '2026-10-21 09:58:34', 170534, NULL, 'Shift started', '2026-10-19 10:36:20', '2026-10-21 09:58:34', NULL),
(199, 10, NULL, 70, 'Working on Task', 'completed', '2026-10-19 11:18:41', '2026-10-19 11:19:44', 63, NULL, 'Working on: Kashmiri sons 5 Blogs Uttarakhand wedding', '2026-10-19 11:18:41', '2026-10-19 11:19:44', NULL),
(200, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 11:40:06', '2026-10-19 18:31:36', 24690, NULL, 'Shift started', '2026-10-19 11:40:06', '2026-10-19 18:31:36', NULL),
(201, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-19 12:09:14', '2026-10-22 11:23:59', 256485, NULL, 'Shift started', '2026-10-19 12:09:14', '2026-10-22 11:23:59', NULL),
(202, 5, 20, 58, 'Working on Task', 'completed', '2026-10-19 13:01:43', '2026-10-19 13:03:05', 82, NULL, 'Working on: Make SEO plan for shivam dang new website ( clinic website ) and make changes on gmb as he has asked us to', '2026-10-19 13:01:43', '2026-10-19 13:03:05', NULL),
(203, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 09:34:57', '2026-10-21 12:51:56', 11819, NULL, 'Shift started', '2026-10-21 09:34:57', '2026-10-21 12:51:56', NULL),
(204, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 09:39:49', '2026-10-21 18:31:13', 31884, NULL, 'Shift started', '2026-10-21 09:39:49', '2026-10-21 18:31:13', NULL),
(205, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 09:39:55', '2026-10-21 18:30:36', 31841, NULL, 'Shift started', '2026-10-21 09:39:55', '2026-10-21 18:30:36', NULL),
(206, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 09:43:23', '2026-10-21 18:35:49', 31946, NULL, 'Shift started', '2026-10-21 09:43:23', '2026-10-21 18:35:49', NULL),
(207, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 09:44:29', '2026-10-21 18:34:46', 31817, NULL, 'Shift started', '2026-10-21 09:44:29', '2026-10-21 18:34:46', NULL),
(208, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 09:45:04', '2026-10-21 18:31:07', 31563, NULL, 'Shift started', '2026-10-21 09:45:04', '2026-10-21 18:31:07', NULL),
(209, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 09:54:47', '2026-10-22 09:58:37', 86630, NULL, 'Shift started', '2026-10-21 09:54:47', '2026-10-22 09:58:37', NULL),
(210, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 09:58:34', '2026-10-21 18:31:09', 30755, NULL, 'Shift started', '2026-10-21 09:58:34', '2026-10-21 18:31:09', NULL),
(211, 2, NULL, NULL, 'Working on Task', 'completed', '2026-10-21 10:15:50', '2026-10-21 18:41:47', 30357, NULL, 'Shift started', '2026-10-21 10:15:50', '2026-10-21 18:41:47', NULL),
(212, 9, NULL, 82, 'Working on Task', 'completed', '2026-10-21 12:51:56', '2026-10-21 18:31:30', 20374, NULL, 'Working on: Turmeric victoria', '2026-10-21 12:51:56', '2026-10-21 18:31:30', NULL),
(213, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 09:34:03', '2026-10-22 10:02:23', 1700, NULL, 'Shift started', '2026-10-22 09:34:03', '2026-10-22 10:02:23', NULL),
(214, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 09:37:45', '2026-10-22 18:30:24', 31959, NULL, 'Shift started', '2026-10-22 09:37:45', '2026-10-22 18:30:24', NULL),
(215, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 09:40:41', '2026-10-22 10:20:06', 2365, NULL, 'Shift started', '2026-10-22 09:40:41', '2026-10-22 10:20:06', NULL),
(216, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 09:44:24', '2026-10-22 10:13:24', 1740, NULL, 'Shift started', '2026-10-22 09:44:24', '2026-10-22 10:13:24', NULL),
(217, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 09:45:15', '2026-10-23 09:44:35', 86360, NULL, 'Shift started', '2026-10-22 09:45:15', '2026-10-23 09:44:35', NULL),
(218, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 09:50:18', '2026-10-22 12:21:37', 9079, NULL, 'Shift started', '2026-10-22 09:50:18', '2026-10-22 12:21:37', NULL),
(219, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 09:53:38', '2026-10-22 18:31:11', 31053, NULL, 'Shift started', '2026-10-22 09:53:38', '2026-10-22 18:31:11', NULL),
(220, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 09:58:37', '2026-10-22 10:54:00', 3323, NULL, 'Shift started', '2026-10-22 09:58:37', '2026-10-22 10:54:00', NULL),
(221, 5, 20, 59, 'Working on Task', 'completed', '2026-10-22 10:02:23', '2026-10-23 12:18:29', 94566, NULL, 'Working on: APPLY SEO PLAN FOR OCT MONTH THAT WAS APPROVED BY KD', '2026-10-22 10:02:23', '2026-10-23 12:18:29', NULL),
(222, 2, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 10:08:39', '2026-10-23 11:25:51', 91032, NULL, 'Shift started', '2026-10-22 10:08:39', '2026-10-23 11:25:51', NULL),
(223, 9, NULL, 82, 'Working on Task', 'completed', '2026-10-22 10:13:24', '2026-10-22 14:00:49', 13645, NULL, 'Working on: Turmeric victoria', '2026-10-22 10:13:24', '2026-10-22 14:00:49', NULL),
(224, 10, NULL, 71, 'Working on Task', 'completed', '2026-10-22 10:20:06', '2026-10-22 10:20:09', 3, NULL, 'Working on: kashmiri sons blogs on Uttarakhand Stays', '2026-10-22 10:20:06', '2026-10-22 10:20:09', NULL),
(225, 10, NULL, 71, 'Working on Task', 'completed', '2026-10-22 10:20:09', '2026-10-22 10:20:31', 22, NULL, 'Working on: kashmiri sons blogs on Uttarakhand Stays', '2026-10-22 10:20:09', '2026-10-22 10:20:31', NULL),
(226, 10, NULL, 72, 'Working on Task', 'completed', '2026-10-22 10:45:07', '2026-10-22 10:45:07', 0, NULL, 'Working on: Kashmiri Sons blogs on Dehradungram', '2026-10-22 10:45:07', '2026-10-22 10:45:07', NULL),
(227, 10, NULL, 72, 'Working on Task', 'completed', '2026-10-22 10:45:07', '2026-10-22 10:45:07', 0, NULL, 'Working on: Kashmiri Sons blogs on Dehradungram', '2026-10-22 10:45:07', '2026-10-22 10:45:07', NULL),
(228, 10, NULL, 72, 'Working on Task', 'completed', '2026-10-22 10:45:07', '2026-10-22 10:45:07', 0, NULL, 'Working on: Kashmiri Sons blogs on Dehradungram', '2026-10-22 10:45:07', '2026-10-22 10:45:07', NULL),
(229, 10, NULL, 72, 'Working on Task', 'completed', '2026-10-22 10:45:07', '2026-10-22 18:18:51', 27224, NULL, 'Working on: Kashmiri Sons blogs on Dehradungram', '2026-10-22 10:45:07', '2026-10-22 18:18:51', NULL),
(230, 6, NULL, 85, 'Working on Task', 'completed', '2026-10-22 11:02:02', '2026-10-26 09:30:04', 340082, NULL, 'Working on: backlinks on frontier jewellers', '2026-10-22 11:02:02', '2026-10-26 09:30:04', NULL),
(231, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-22 11:23:59', '2026-10-23 10:29:34', 83135, NULL, 'Shift started', '2026-10-22 11:23:59', '2026-10-23 10:29:34', NULL),
(232, 8, 11, 32, 'Working on Task', 'completed', '2026-10-22 12:21:37', '2026-10-22 13:15:34', 3237, NULL, 'Working on: List Wildflower on Booking.com', '2026-10-22 12:21:37', '2026-10-22 13:15:34', NULL),
(233, 8, 11, 33, 'Working on Task', 'completed', '2026-10-22 13:15:50', '2026-10-23 09:45:25', 73775, NULL, 'Working on: List Sunrise On Booking.com', '2026-10-22 13:15:50', '2026-10-23 09:45:25', NULL),
(234, 9, NULL, 82, 'Working on Task', 'completed', '2026-10-22 16:11:38', '2026-10-22 18:32:07', 8429, NULL, 'Working on: Turmeric victoria', '2026-10-22 16:11:38', '2026-10-22 18:32:07', NULL),
(235, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 08:55:21', '2026-10-23 18:29:17', 34436, NULL, 'Shift started', '2026-10-23 08:55:21', '2026-10-23 18:29:17', NULL),
(236, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 09:25:56', '2026-10-23 18:31:10', 32714, NULL, 'Shift started', '2026-10-23 09:25:56', '2026-10-23 18:31:10', NULL),
(237, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 09:43:25', '2026-10-23 10:32:22', 2937, NULL, 'Shift started', '2026-10-23 09:43:25', '2026-10-23 10:32:22', NULL),
(238, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 09:44:03', '2026-10-23 18:36:07', 31924, NULL, 'Shift started', '2026-10-23 09:44:03', '2026-10-23 18:36:07', NULL),
(239, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 09:44:35', '2026-10-23 18:31:45', 31630, NULL, 'Shift started', '2026-10-23 09:44:35', '2026-10-23 18:31:45', NULL),
(240, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 09:45:25', '2026-10-23 10:21:26', 2161, NULL, 'Shift started', '2026-10-23 09:45:25', '2026-10-23 10:21:26', NULL),
(241, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 10:29:34', '2026-10-24 10:51:02', 87688, NULL, 'Shift started', '2026-10-23 10:29:34', '2026-10-24 10:51:02', NULL),
(242, 10, NULL, 73, 'Working on Task', 'completed', '2026-10-23 10:32:22', '2026-10-23 10:34:12', 110, NULL, 'Working on: Jeevan sankalp blogs on Dehradun gurugram', '2026-10-23 10:32:22', '2026-10-23 10:34:12', NULL),
(243, 10, NULL, 73, 'Working on Task', 'completed', '2026-10-23 10:34:12', '2026-10-23 10:48:55', 883, NULL, 'Working on: Jeevan sankalp blogs on Dehradun gurugram', '2026-10-23 10:34:12', '2026-10-23 10:48:55', NULL),
(244, 10, NULL, 73, 'Working on Task', 'completed', '2026-10-23 10:48:55', '2026-10-23 10:48:55', 0, NULL, 'Working on: Jeevan sankalp blogs on Dehradun gurugram', '2026-10-23 10:48:55', '2026-10-23 10:48:55', NULL),
(245, 10, NULL, 73, 'Working on Task', 'completed', '2026-10-23 10:48:55', '2026-10-23 18:33:10', 27855, NULL, 'Working on: Jeevan sankalp blogs on Dehradun gurugram', '2026-10-23 10:48:55', '2026-10-23 18:33:10', NULL),
(246, 1, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 11:19:10', '2026-10-26 12:29:06', 263396, NULL, 'Shift started', '2026-10-23 11:19:10', '2026-10-26 12:29:06', NULL),
(247, 2, NULL, NULL, 'Working on Task', 'completed', '2026-10-23 11:25:51', '2026-10-24 10:26:55', 82864, NULL, 'Shift started', '2026-10-23 11:25:51', '2026-10-24 10:26:55', NULL),
(248, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-24 09:21:48', '2026-10-26 09:42:47', 174059, NULL, 'Shift started', '2026-10-24 09:21:48', '2026-10-26 09:42:47', NULL),
(249, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-24 09:35:14', '2026-10-26 09:44:06', 173332, NULL, 'Shift started', '2026-10-24 09:35:14', '2026-10-26 09:44:06', NULL),
(250, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-24 09:43:37', '2026-10-26 09:37:35', 172438, NULL, 'Shift started', '2026-10-24 09:43:37', '2026-10-26 09:37:35', NULL),
(251, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-24 09:45:08', '2026-10-24 15:35:17', 21009, NULL, 'Shift started', '2026-10-24 09:45:08', '2026-10-24 15:35:17', NULL),
(252, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-24 09:45:24', '2026-10-26 09:55:43', 173419, NULL, 'Shift started', '2026-10-24 09:45:24', '2026-10-26 09:55:43', NULL),
(253, 2, NULL, NULL, 'Working on Task', 'completed', '2026-10-24 10:26:55', '2026-10-31 12:32:26', 612331, NULL, 'Shift started', '2026-10-24 10:26:55', '2026-10-31 12:32:26', NULL),
(254, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-24 10:51:02', '2026-10-26 14:05:23', 184461, NULL, 'Shift started', '2026-10-24 10:51:02', '2026-10-26 14:05:23', NULL),
(255, 10, NULL, 74, 'Working on Task', 'completed', '2026-10-24 15:35:17', '2026-10-26 09:45:55', 151838, NULL, 'Working on: Jeevan sankalp blogs on Uttarakhand Diaries', '2026-10-24 15:35:17', '2026-10-26 09:45:55', NULL),
(256, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 09:30:04', '2026-10-26 10:43:39', 4415, NULL, 'Shift started', '2026-10-26 09:30:04', '2026-10-26 10:43:39', NULL),
(257, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 09:33:09', '2026-10-26 10:09:36', 2187, NULL, 'Shift started', '2026-10-26 09:33:09', '2026-10-26 10:09:36', NULL),
(258, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 09:37:35', '2026-10-27 10:02:47', 87912, NULL, 'Shift started', '2026-10-26 09:37:35', '2026-10-27 10:02:47', NULL),
(259, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 09:42:47', '2026-10-28 09:36:39', 172432, NULL, 'Shift started', '2026-10-26 09:42:47', '2026-10-28 09:36:39', NULL),
(260, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 09:44:06', '2026-10-27 09:44:33', 86427, NULL, 'Shift started', '2026-10-26 09:44:06', '2026-10-27 09:44:33', NULL),
(261, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 09:45:55', '2026-10-26 10:48:12', 3737, NULL, 'Shift started', '2026-10-26 09:45:55', '2026-10-26 10:48:12', NULL),
(262, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 09:46:08', '2026-10-27 10:04:44', 87516, NULL, 'Shift started', '2026-10-26 09:46:08', '2026-10-27 10:04:44', NULL),
(263, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 09:55:43', '2026-10-26 18:44:09', 31706, NULL, 'Shift started', '2026-10-26 09:55:43', '2026-10-26 18:44:09', NULL),
(264, 5, 13, 37, 'Working on Task', 'completed', '2026-10-26 10:09:36', '2026-10-26 10:10:03', 27, NULL, 'Working on: Make SEO implementation plan for Masterpiece Psycology', '2026-10-26 10:09:36', '2026-10-26 10:10:03', NULL),
(265, 5, 13, 38, 'Working on Task', 'completed', '2026-10-26 10:10:18', '2026-10-26 10:10:30', 12, NULL, 'Working on: Send New SEO plan after KD approval to Tess via email for approval', '2026-10-26 10:10:18', '2026-10-26 10:10:30', NULL),
(266, 5, 18, 51, 'Working on Task', 'completed', '2026-10-26 10:11:15', '2026-10-26 10:12:40', 85, NULL, 'Working on: Make Seo implementation plan for Jeevan sankalp .(local and website )', '2026-10-26 10:11:15', '2026-10-26 10:12:40', NULL),
(267, 6, NULL, 86, 'Working on Task', 'completed', '2026-10-26 10:57:52', '2026-10-26 12:44:24', 6392, NULL, 'Working on: Liquor fort 50 backlinks', '2026-10-26 10:57:52', '2026-10-26 12:44:24', NULL),
(268, 1, NULL, NULL, 'Working on Task', 'active', '2026-10-26 12:29:06', NULL, 0, NULL, 'Shift started', '2026-10-26 12:29:06', '2026-10-26 12:29:06', NULL),
(269, 6, NULL, 85, 'Working on Task', 'completed', '2026-10-26 12:44:34', '2026-10-26 13:39:03', 3269, NULL, 'Working on: backlinks on frontier jewellers', '2026-10-26 12:44:34', '2026-10-26 13:39:03', NULL),
(270, 5, 6, 44, 'Working on Task', 'completed', '2026-10-26 13:05:53', '2026-10-26 13:08:47', 174, NULL, 'Working on: Make Detailed SOP for Blog Posting', '2026-10-26 13:05:53', '2026-10-26 13:08:47', NULL),
(271, 6, NULL, 85, 'Working on Task', 'completed', '2026-10-26 13:39:51', '2026-10-26 13:50:04', 613, NULL, 'Working on: backlinks on frontier jewellers', '2026-10-26 13:39:51', '2026-10-26 13:50:04', NULL),
(272, 5, 13, 53, 'Working on Task', 'completed', '2026-10-26 13:50:12', '2026-10-26 13:50:47', 35, NULL, 'Working on: Implement the plan made for masterpiece psycology', '2026-10-26 13:50:12', '2026-10-26 13:50:47', NULL),
(273, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-26 14:05:23', '2026-10-27 10:44:42', 74359, NULL, 'Shift started', '2026-10-26 14:05:23', '2026-10-27 10:44:42', NULL),
(274, 5, 23, 88, 'Working on Task', 'completed', '2026-10-26 14:08:51', '2026-10-26 14:10:14', 83, NULL, 'Working on: Check content of new Sheina Mill website', '2026-10-26 14:08:51', '2026-10-26 14:10:14', NULL),
(275, 6, NULL, 86, 'Working on Task', 'completed', '2026-10-26 15:32:37', '2026-10-26 15:32:39', 2, NULL, 'Working on: Liquor fort 50 backlinks', '2026-10-26 15:32:37', '2026-10-26 15:32:39', NULL),
(276, 6, NULL, 86, 'Working on Task', 'completed', '2026-10-26 15:32:39', '2026-10-26 15:32:43', 4, NULL, 'Working on: Liquor fort 50 backlinks', '2026-10-26 15:32:39', '2026-10-26 15:32:43', NULL),
(277, 5, 10, 89, 'Working on Task', 'completed', '2026-10-26 15:50:29', '2026-10-26 15:50:40', 11, NULL, 'Working on: Make review QR code for Almost Heaven Mussoorie', '2026-10-26 15:50:29', '2026-10-26 15:50:40', NULL),
(278, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-27 09:35:11', '2026-10-27 12:17:03', 9712, NULL, 'Shift started', '2026-10-27 09:35:11', '2026-10-27 12:17:03', NULL),
(279, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-27 09:43:54', '2026-10-27 10:55:50', 4316, NULL, 'Shift started', '2026-10-27 09:43:54', '2026-10-27 10:55:50', NULL),
(280, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-27 09:44:33', '2026-10-28 09:43:29', 86336, NULL, 'Shift started', '2026-10-27 09:44:33', '2026-10-28 09:43:29', NULL),
(281, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-27 09:52:23', '2026-10-27 18:33:50', 31287, NULL, 'Shift started', '2026-10-27 09:52:23', '2026-10-27 18:33:50', NULL),
(282, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-27 10:01:44', '2026-10-27 11:48:32', 6408, NULL, 'Shift started', '2026-10-27 10:01:44', '2026-10-27 11:48:32', NULL),
(283, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-27 10:02:47', '2026-10-28 09:41:06', 85099, NULL, 'Shift started', '2026-10-27 10:02:47', '2026-10-28 09:41:06', NULL),
(284, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-27 10:04:44', '2026-10-28 09:51:30', 85606, NULL, 'Shift started', '2026-10-27 10:04:44', '2026-10-28 09:51:30', NULL),
(285, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-27 10:44:42', '2026-10-29 12:24:26', 178784, NULL, 'Shift started', '2026-10-27 10:44:42', '2026-10-29 12:24:26', NULL),
(286, 6, NULL, 85, 'Working on Task', 'completed', '2026-10-27 10:55:50', '2026-10-27 18:25:51', 27001, NULL, 'Working on: backlinks on frontier jewellers', '2026-10-27 10:55:50', '2026-10-27 18:25:51', NULL),
(287, 10, NULL, 74, 'Working on Task', 'completed', '2026-10-27 11:48:32', '2026-10-27 13:06:08', 4656, NULL, 'Working on: Jeevan sankalp blogs on Uttarakhand Diaries', '2026-10-27 11:48:32', '2026-10-27 13:06:08', NULL);
INSERT INTO `employee_activities` (`id`, `employee_id`, `project_id`, `task_id`, `activity_type`, `status`, `start_time`, `end_time`, `duration`, `reason`, `notes`, `created_at`, `updated_at`, `client_name`) VALUES
(288, 5, NULL, 62, 'Working on Task', 'completed', '2026-10-27 12:17:03', '2026-10-27 12:18:47', 104, NULL, 'Working on: CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE', '2026-10-27 12:17:03', '2026-10-27 12:18:47', NULL),
(289, 5, 16, 49, 'Working on Task', 'completed', '2026-10-27 12:19:24', '2026-10-27 12:21:17', 113, NULL, 'Working on: Make SEO plan for Blackapple', '2026-10-27 12:19:24', '2026-10-27 12:21:17', NULL),
(290, 10, NULL, 74, 'Working on Task', 'completed', '2026-10-27 13:06:08', '2026-10-28 10:05:07', 75539, NULL, 'Working on: Jeevan sankalp blogs on Uttarakhand Diaries', '2026-10-27 13:06:08', '2026-10-28 10:05:07', NULL),
(291, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-28 09:34:36', '2026-10-29 09:35:43', 86467, NULL, 'Shift started', '2026-10-28 09:34:36', '2026-10-29 09:35:43', NULL),
(292, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-28 09:36:39', '2026-10-29 09:35:23', 86324, NULL, 'Shift started', '2026-10-28 09:36:39', '2026-10-29 09:35:23', NULL),
(293, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-28 09:41:06', '2026-10-28 18:36:22', 32116, NULL, 'Shift started', '2026-10-28 09:41:06', '2026-10-28 18:36:22', NULL),
(294, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-28 09:43:29', '2026-10-30 09:45:18', 172909, NULL, 'Shift started', '2026-10-28 09:43:29', '2026-10-30 09:45:18', NULL),
(295, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-28 09:51:30', '2026-10-28 10:08:21', 1011, NULL, 'Shift started', '2026-10-28 09:51:30', '2026-10-28 10:08:21', NULL),
(296, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-28 09:51:30', '2026-10-28 18:35:24', 31434, NULL, 'Shift started', '2026-10-28 09:51:30', '2026-10-28 18:35:24', NULL),
(297, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-29 09:35:23', '2026-10-30 09:32:04', 86201, NULL, 'Shift started', '2026-10-29 09:35:23', '2026-10-30 09:32:04', NULL),
(298, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-29 09:35:43', '2026-10-30 09:36:40', 86457, NULL, 'Shift started', '2026-10-29 09:35:43', '2026-10-30 09:36:40', NULL),
(299, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-29 09:40:35', '2026-10-30 10:10:20', 88185, NULL, 'Shift started', '2026-10-29 09:40:35', '2026-10-30 10:10:20', NULL),
(300, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-29 09:52:40', '2026-10-30 09:45:42', 85982, NULL, 'Shift started', '2026-10-29 09:52:40', '2026-10-30 09:45:42', NULL),
(301, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-29 09:52:51', '2026-10-29 18:28:14', 30923, NULL, 'Shift started', '2026-10-29 09:52:51', '2026-10-29 18:28:14', NULL),
(302, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-29 09:58:39', '2026-10-29 18:35:40', 31021, NULL, 'Shift started', '2026-10-29 09:58:39', '2026-10-29 18:35:40', NULL),
(303, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-29 09:58:54', '2026-10-30 10:00:31', 86497, NULL, 'Shift started', '2026-10-29 09:58:54', '2026-10-30 10:00:31', NULL),
(304, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-29 12:24:26', '2026-10-30 10:42:09', 80263, NULL, 'Shift started', '2026-10-29 12:24:26', '2026-10-30 10:42:09', NULL),
(305, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 09:32:04', '2026-10-31 12:28:38', 96994, NULL, 'Shift started', '2026-10-30 09:32:04', '2026-10-31 12:28:38', NULL),
(306, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 09:36:40', '2026-10-30 12:36:42', 10802, NULL, 'Shift started', '2026-10-30 09:36:40', '2026-10-30 12:36:42', NULL),
(307, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 09:45:18', '2026-10-30 18:32:44', 31646, NULL, 'Shift started', '2026-10-30 09:45:18', '2026-10-30 18:32:44', NULL),
(308, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 09:45:42', '2026-10-31 12:28:53', 96191, NULL, 'Shift started', '2026-10-30 09:45:42', '2026-10-31 12:28:53', NULL),
(309, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 09:51:32', '2026-10-30 18:38:49', 31637, NULL, 'Shift started', '2026-10-30 09:51:32', '2026-10-30 18:38:49', NULL),
(310, 6, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 09:53:36', '2026-10-30 18:30:17', 31001, NULL, 'Shift started', '2026-10-30 09:53:36', '2026-10-30 18:30:17', NULL),
(311, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 10:00:31', '2026-10-30 11:54:12', 6821, NULL, 'Shift started', '2026-10-30 10:00:31', '2026-10-30 11:54:12', NULL),
(312, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 10:10:20', '2026-10-31 12:32:06', 94906, NULL, 'Shift started', '2026-10-30 10:10:20', '2026-10-31 12:32:06', NULL),
(313, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-30 10:42:09', '2026-10-31 13:39:53', 97064, NULL, 'Shift started', '2026-10-30 10:42:09', '2026-10-31 13:39:53', NULL),
(314, 10, NULL, 76, 'Working on Task', 'completed', '2026-10-30 11:54:12', '2026-10-30 13:05:24', 4272, NULL, 'Working on: Frontier Jewellers blogs on Dehradun Gurugram', '2026-10-30 11:54:12', '2026-10-30 13:05:24', NULL),
(315, 5, NULL, 90, 'Working on Task', 'completed', '2026-10-30 12:36:42', '2026-10-30 12:36:54', 12, NULL, 'Working on: Remake Turmeric Demo Table Booking Screen Recording in english.', '2026-10-30 12:36:42', '2026-10-30 12:36:54', NULL),
(316, 5, 8, 52, 'Working on Task', 'completed', '2026-10-30 12:56:35', '2026-10-30 12:56:39', 4, NULL, 'Working on: Make new seo plan for Turmeric', '2026-10-30 12:56:35', '2026-10-30 12:56:39', NULL),
(317, 10, NULL, 77, 'Working on Task', 'completed', '2026-10-30 17:14:13', '2026-10-31 12:29:26', 69313, NULL, 'Working on: Frontier Jewellers blogs on Uttarakhan Diaries', '2026-10-30 17:14:13', '2026-10-31 12:29:26', NULL),
(318, 4, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 12:28:38', '2026-11-02 09:38:16', 162578, NULL, 'Shift started', '2026-10-31 12:28:38', '2026-11-02 09:38:16', NULL),
(319, 9, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 12:28:53', '2026-10-31 14:10:08', 6075, NULL, 'Shift started', '2026-10-31 12:28:53', '2026-10-31 14:10:08', NULL),
(320, 12, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 12:29:26', '2026-10-31 18:31:54', 21748, NULL, 'Shift started', '2026-10-31 12:29:26', '2026-10-31 18:31:54', NULL),
(321, 10, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 12:29:26', '2026-11-02 09:48:52', 163166, NULL, 'Shift started', '2026-10-31 12:29:26', '2026-11-02 09:48:52', NULL),
(322, 6, NULL, NULL, 'Working on Task', 'active', '2026-10-31 12:30:16', NULL, 0, NULL, 'Shift started', '2026-10-31 12:30:16', '2026-10-31 12:30:16', NULL),
(323, 7, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 12:30:34', '2026-11-02 11:58:30', 170876, NULL, 'Shift started', '2026-10-31 12:30:34', '2026-11-02 11:58:30', NULL),
(324, 8, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 12:32:06', '2026-11-02 09:41:12', 162546, NULL, 'Shift started', '2026-10-31 12:32:06', '2026-11-02 09:41:12', NULL),
(325, 2, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 12:32:26', '2026-11-13 11:14:22', 1118516, NULL, 'Shift started', '2026-10-31 12:32:26', '2026-11-13 11:14:22', NULL),
(326, 5, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 12:39:35', '2026-11-02 09:39:48', 162013, NULL, 'Shift started', '2026-10-31 12:39:35', '2026-11-02 09:39:48', NULL),
(327, 3, NULL, NULL, 'Working on Task', 'completed', '2026-10-31 13:39:53', '2026-11-13 13:54:31', 1124078, NULL, 'Shift started', '2026-10-31 13:39:53', '2026-11-13 13:54:31', NULL),
(328, 4, NULL, NULL, 'Working on Task', 'completed', '2026-11-02 09:38:16', '2026-11-12 10:28:35', 867019, NULL, 'Shift started', '2026-11-02 09:38:16', '2026-11-12 10:28:35', NULL),
(329, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-02 09:39:48', '2026-11-03 09:34:21', 86073, NULL, 'Shift started', '2026-11-02 09:39:48', '2026-11-03 09:34:21', NULL),
(330, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-02 09:40:53', '2026-11-03 09:36:57', 86164, NULL, 'Shift started', '2026-11-02 09:40:53', '2026-11-03 09:36:57', NULL),
(331, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-02 09:41:12', '2026-11-03 09:40:03', 86331, NULL, 'Shift started', '2026-11-02 09:41:12', '2026-11-03 09:40:03', NULL),
(332, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-02 09:48:52', '2026-11-02 09:49:23', 31, NULL, 'Shift started', '2026-11-02 09:48:52', '2026-11-02 09:49:23', NULL),
(333, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-02 09:54:44', '2026-11-03 09:40:36', 85552, NULL, 'Shift started', '2026-11-02 09:54:44', '2026-11-03 09:40:36', NULL),
(334, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-02 11:58:30', '2026-11-03 09:41:38', 78188, NULL, 'Shift started', '2026-11-02 11:58:30', '2026-11-03 09:41:38', NULL),
(335, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-03 09:34:21', '2026-11-04 09:39:10', 86689, NULL, 'Shift started', '2026-11-03 09:34:21', '2026-11-04 09:39:10', NULL),
(336, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-03 09:36:57', '2026-11-03 11:58:41', 8504, NULL, 'Shift started', '2026-11-03 09:36:57', '2026-11-03 11:58:41', NULL),
(337, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-03 09:40:03', '2026-11-04 10:00:59', 87656, NULL, 'Shift started', '2026-11-03 09:40:03', '2026-11-04 10:00:59', NULL),
(338, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-03 09:40:36', '2026-11-03 18:38:42', 32286, NULL, 'Shift started', '2026-11-03 09:40:36', '2026-11-03 18:38:42', NULL),
(339, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-03 09:41:38', '2026-11-03 18:33:31', 31913, NULL, 'Shift started', '2026-11-03 09:41:38', '2026-11-03 18:33:31', NULL),
(340, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-03 09:44:44', '2026-11-03 13:30:31', 13547, NULL, 'Shift started', '2026-11-03 09:44:44', '2026-11-03 13:30:31', NULL),
(341, 9, NULL, 93, 'Working on Task', 'completed', '2026-11-03 11:58:41', '2026-11-03 18:33:59', 23718, NULL, 'Working on: Uttarakhandstays', '2026-11-03 11:58:41', '2026-11-03 18:33:59', NULL),
(342, 10, NULL, 77, 'Working on Task', 'completed', '2026-11-03 13:30:31', '2026-11-03 13:30:31', 0, NULL, 'Working on: Frontier Jewellers blogs on Uttarakhan Diaries', '2026-11-03 13:30:31', '2026-11-03 13:30:31', NULL),
(343, 10, NULL, 77, 'Working on Task', 'completed', '2026-11-03 13:30:31', '2026-11-03 13:32:41', 130, NULL, 'Working on: Frontier Jewellers blogs on Uttarakhan Diaries', '2026-11-03 13:30:31', '2026-11-03 13:32:41', NULL),
(344, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-04 09:24:45', '2026-11-04 09:24:51', 6, NULL, 'Shift started', '2026-11-04 09:24:45', '2026-11-04 09:24:51', NULL),
(345, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-04 09:25:05', '2026-11-05 09:37:24', 87139, NULL, 'Shift started', '2026-11-04 09:25:05', '2026-11-05 09:37:24', NULL),
(346, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-04 09:39:10', '2026-11-05 09:34:23', 86113, NULL, 'Shift started', '2026-11-04 09:39:10', '2026-11-05 09:34:23', NULL),
(347, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-04 09:41:14', '2026-11-05 10:05:21', 87847, NULL, 'Shift started', '2026-11-04 09:41:14', '2026-11-05 10:05:21', NULL),
(348, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-04 09:42:23', '2026-11-05 09:40:31', 86288, NULL, 'Shift started', '2026-11-04 09:42:23', '2026-11-05 09:40:31', NULL),
(349, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-04 10:00:59', '2026-11-05 09:40:45', 85186, NULL, 'Shift started', '2026-11-04 10:00:59', '2026-11-05 09:40:45', NULL),
(350, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-04 10:31:23', '2026-11-05 09:55:10', 84227, NULL, 'Shift started', '2026-11-04 10:31:23', '2026-11-05 09:55:10', NULL),
(351, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-05 09:34:23', '2026-11-06 09:37:06', 86563, NULL, 'Shift started', '2026-11-05 09:34:23', '2026-11-06 09:37:06', NULL),
(352, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-05 09:37:24', '2026-11-06 09:40:02', 86558, NULL, 'Shift started', '2026-11-05 09:37:24', '2026-11-06 09:40:02', NULL),
(353, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-05 09:40:31', '2026-11-06 09:40:27', 86396, NULL, 'Shift started', '2026-11-05 09:40:31', '2026-11-06 09:40:27', NULL),
(354, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-05 09:40:45', '2026-11-06 09:40:09', 86364, NULL, 'Shift started', '2026-11-05 09:40:45', '2026-11-06 09:40:09', NULL),
(355, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-05 09:55:10', '2026-11-06 10:01:06', 86756, NULL, 'Shift started', '2026-11-05 09:55:10', '2026-11-06 10:01:06', NULL),
(356, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-05 10:05:21', '2026-11-05 18:37:05', 30704, NULL, 'Shift started', '2026-11-05 10:05:21', '2026-11-05 18:37:05', NULL),
(357, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-06 09:37:06', '2026-11-07 09:38:17', 86471, NULL, 'Shift started', '2026-11-06 09:37:06', '2026-11-07 09:38:17', NULL),
(358, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-06 09:40:02', '2026-11-07 09:40:24', 86422, NULL, 'Shift started', '2026-11-06 09:40:02', '2026-11-07 09:40:24', NULL),
(359, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-06 09:40:09', '2026-11-07 09:48:16', 86887, NULL, 'Shift started', '2026-11-06 09:40:09', '2026-11-07 09:48:16', NULL),
(360, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-06 09:40:27', '2026-11-07 09:40:00', 86373, NULL, 'Shift started', '2026-11-06 09:40:27', '2026-11-07 09:40:00', NULL),
(361, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-06 09:52:37', '2026-11-12 09:49:26', 518209, NULL, 'Shift started', '2026-11-06 09:52:37', '2026-11-12 09:49:26', NULL),
(362, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-06 10:01:06', '2026-11-07 09:45:16', 85450, NULL, 'Shift started', '2026-11-06 10:01:06', '2026-11-07 09:45:16', NULL),
(363, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-07 09:38:17', '2026-11-12 09:33:18', 431701, NULL, 'Shift started', '2026-11-07 09:38:17', '2026-11-12 09:33:18', NULL),
(364, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-07 09:40:00', '2026-11-12 09:39:21', 431961, NULL, 'Shift started', '2026-11-07 09:40:00', '2026-11-12 09:39:21', NULL),
(365, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-07 09:40:24', '2026-11-12 09:37:51', 431847, NULL, 'Shift started', '2026-11-07 09:40:24', '2026-11-12 09:37:51', NULL),
(366, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-07 09:45:16', '2026-11-12 09:51:03', 432347, NULL, 'Shift started', '2026-11-07 09:45:16', '2026-11-12 09:51:03', NULL),
(367, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-07 09:48:16', '2026-11-12 09:50:44', 432148, NULL, 'Shift started', '2026-11-07 09:48:16', '2026-11-12 09:50:44', NULL),
(368, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-12 09:33:18', '2026-11-16 09:58:15', 347097, NULL, 'Shift started', '2026-11-12 09:33:18', '2026-11-16 09:58:15', NULL),
(369, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-12 09:37:51', '2026-11-13 09:43:25', 86734, NULL, 'Shift started', '2026-11-12 09:37:51', '2026-11-13 09:43:25', NULL),
(370, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-12 09:39:21', '2026-11-13 09:38:54', 86373, NULL, 'Shift started', '2026-11-12 09:39:21', '2026-11-13 09:38:54', NULL),
(371, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-12 09:49:26', '2026-11-12 18:32:54', 31408, NULL, 'Shift started', '2026-11-12 09:49:26', '2026-11-12 18:32:54', NULL),
(372, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-12 09:50:44', '2026-11-13 09:48:43', 86279, NULL, 'Shift started', '2026-11-12 09:50:44', '2026-11-13 09:48:43', NULL),
(373, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-12 09:51:03', '2026-11-13 10:02:13', 87070, NULL, 'Shift started', '2026-11-12 09:51:03', '2026-11-13 10:02:13', NULL),
(374, 4, NULL, NULL, 'Working on Task', 'completed', '2026-11-12 10:28:35', '2026-11-13 09:38:25', 83390, NULL, 'Shift started', '2026-11-12 10:28:35', '2026-11-13 09:38:25', NULL),
(375, 4, NULL, NULL, 'Working on Task', 'completed', '2026-11-13 09:38:25', '2026-11-14 09:53:28', 87303, NULL, 'Shift started', '2026-11-13 09:38:25', '2026-11-14 09:53:28', NULL),
(376, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-13 09:38:54', '2026-11-14 09:49:05', 87011, NULL, 'Shift started', '2026-11-13 09:38:54', '2026-11-14 09:49:05', NULL),
(377, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-13 09:43:25', '2026-11-14 09:44:44', 86479, NULL, 'Shift started', '2026-11-13 09:43:25', '2026-11-14 09:44:44', NULL),
(378, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-13 09:48:43', '2026-11-14 09:53:59', 86716, NULL, 'Shift started', '2026-11-13 09:48:43', '2026-11-14 09:53:59', NULL),
(379, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-13 10:00:33', '2026-11-13 18:30:41', 30608, NULL, 'Shift started', '2026-11-13 10:00:33', '2026-11-13 18:30:41', NULL),
(380, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-13 10:02:13', '2026-11-14 10:00:23', 86290, NULL, 'Shift started', '2026-11-13 10:02:13', '2026-11-14 10:00:23', NULL),
(381, 2, NULL, NULL, 'Working on Task', 'completed', '2026-11-13 11:14:22', '2026-11-14 10:20:57', 83195, NULL, 'Shift started', '2026-11-13 11:14:22', '2026-11-14 10:20:57', NULL),
(382, 3, NULL, NULL, 'Working on Task', 'completed', '2026-11-13 13:54:31', '2026-11-14 12:56:16', 82905, NULL, 'Shift started', '2026-11-13 13:54:31', '2026-11-14 12:56:16', NULL),
(383, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-14 09:44:44', '2026-11-16 10:00:56', 173772, NULL, 'Shift started', '2026-11-14 09:44:44', '2026-11-16 10:00:56', NULL),
(384, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-14 09:46:24', '2026-11-14 18:31:51', 31527, NULL, 'Shift started', '2026-11-14 09:46:24', '2026-11-14 18:31:51', NULL),
(385, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-14 09:49:05', '2026-11-16 09:58:12', 173347, NULL, 'Shift started', '2026-11-14 09:49:05', '2026-11-16 09:58:12', NULL),
(386, 4, NULL, NULL, 'Working on Task', 'completed', '2026-11-14 09:53:28', '2026-11-16 09:58:49', 173121, NULL, 'Shift started', '2026-11-14 09:53:28', '2026-11-16 09:58:49', NULL),
(387, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-14 09:53:59', '2026-11-16 10:01:36', 173257, NULL, 'Shift started', '2026-11-14 09:53:59', '2026-11-16 10:01:36', NULL),
(388, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-14 10:00:23', '2026-11-16 09:58:42', 172699, NULL, 'Shift started', '2026-11-14 10:00:23', '2026-11-16 09:58:42', NULL),
(389, 2, NULL, NULL, 'Working on Task', 'completed', '2026-11-14 10:20:57', '2026-11-16 10:14:28', 172411, NULL, 'Shift started', '2026-11-14 10:20:57', '2026-11-16 10:14:28', NULL),
(390, 3, NULL, NULL, 'Working on Task', 'completed', '2026-11-14 12:56:16', '2026-11-16 11:37:54', 168098, NULL, 'Shift started', '2026-11-14 12:56:16', '2026-11-16 11:37:54', NULL),
(391, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 09:58:12', '2026-11-17 09:35:56', 85064, NULL, 'Shift started', '2026-11-16 09:58:12', '2026-11-17 09:35:56', NULL),
(392, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 09:58:15', '2026-11-17 09:49:42', 85887, NULL, 'Shift started', '2026-11-16 09:58:15', '2026-11-17 09:49:42', NULL),
(393, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 09:58:42', '2026-11-17 09:41:48', 85386, NULL, 'Shift started', '2026-11-16 09:58:42', '2026-11-17 09:41:48', NULL),
(394, 4, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 09:58:49', '2026-11-17 09:30:13', 84684, NULL, 'Shift started', '2026-11-16 09:58:49', '2026-11-17 09:30:13', NULL),
(395, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 09:59:17', '2026-11-17 10:04:19', 86702, NULL, 'Shift started', '2026-11-16 09:59:17', '2026-11-17 10:04:19', NULL),
(396, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 10:00:56', '2026-11-17 09:40:21', 85165, NULL, 'Shift started', '2026-11-16 10:00:56', '2026-11-17 09:40:21', NULL),
(397, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 10:01:36', '2026-11-17 09:42:36', 85260, NULL, 'Shift started', '2026-11-16 10:01:36', '2026-11-17 09:42:36', NULL),
(398, 2, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 10:14:28', '2026-11-17 11:31:41', 91033, NULL, 'Shift started', '2026-11-16 10:14:28', '2026-11-17 11:31:41', NULL),
(399, 3, NULL, NULL, 'Working on Task', 'completed', '2026-11-16 11:37:54', '2026-11-17 15:42:03', 101049, NULL, 'Shift started', '2026-11-16 11:37:54', '2026-11-17 15:42:03', NULL),
(400, 4, NULL, NULL, 'Working on Task', 'completed', '2026-11-17 09:30:13', '2026-11-18 09:32:11', 86518, NULL, 'Shift started', '2026-11-17 09:30:13', '2026-11-18 09:32:11', NULL),
(401, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-17 09:35:56', '2026-11-18 09:37:51', 86515, NULL, 'Shift started', '2026-11-17 09:35:56', '2026-11-18 09:37:51', NULL),
(402, 7, NULL, NULL, 'Working on Task', 'completed', '2026-11-17 09:40:21', '2026-11-18 09:44:11', 86630, NULL, 'Shift started', '2026-11-17 09:40:21', '2026-11-18 09:44:11', NULL),
(403, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-17 09:41:48', '2026-11-18 09:56:24', 87276, NULL, 'Shift started', '2026-11-17 09:41:48', '2026-11-18 09:56:24', NULL),
(404, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-17 09:42:36', '2026-11-18 09:58:10', 87334, NULL, 'Shift started', '2026-11-17 09:42:36', '2026-11-18 09:58:10', NULL),
(405, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-17 09:49:42', '2026-11-18 09:42:55', 85993, NULL, 'Shift started', '2026-11-17 09:49:42', '2026-11-18 09:42:55', NULL),
(406, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-17 10:04:19', '2026-11-18 09:51:57', 85658, NULL, 'Shift started', '2026-11-17 10:04:19', '2026-11-18 09:51:57', NULL),
(407, 2, NULL, NULL, 'Working on Task', 'completed', '2026-11-17 11:31:41', '2026-11-18 17:03:35', 106314, NULL, 'Shift started', '2026-11-17 11:31:41', '2026-11-18 17:03:35', NULL),
(408, 3, NULL, NULL, 'Working on Task', 'active', '2026-11-17 15:42:03', NULL, 0, NULL, 'Shift started', '2026-11-17 15:42:03', '2026-11-17 15:42:03', NULL),
(409, 4, NULL, NULL, 'Working on Task', 'completed', '2026-11-18 09:32:11', '2026-11-19 09:43:24', 87073, NULL, 'Shift started', '2026-11-18 09:32:11', '2026-11-19 09:43:24', NULL),
(410, 9, NULL, NULL, 'Working on Task', 'completed', '2026-11-18 09:37:51', '2026-11-19 09:44:38', 86807, NULL, 'Shift started', '2026-11-18 09:37:51', '2026-11-19 09:44:38', NULL),
(411, 5, NULL, NULL, 'Working on Task', 'completed', '2026-11-18 09:42:55', '2026-11-19 09:41:08', 86293, NULL, 'Shift started', '2026-11-18 09:42:55', '2026-11-19 09:41:08', NULL),
(412, 7, NULL, NULL, 'Working on Task', 'active', '2026-11-18 09:44:11', NULL, 0, NULL, 'Shift started', '2026-11-18 09:44:11', '2026-11-18 09:44:11', NULL),
(413, 12, NULL, NULL, 'Working on Task', 'completed', '2026-11-18 09:51:57', '2026-11-19 09:50:03', 86286, NULL, 'Shift started', '2026-11-18 09:51:57', '2026-11-19 09:50:03', NULL),
(414, 10, NULL, NULL, 'Working on Task', 'completed', '2026-11-18 09:56:24', '2026-11-19 09:55:46', 86362, NULL, 'Shift started', '2026-11-18 09:56:24', '2026-11-19 09:55:46', NULL),
(415, 8, NULL, NULL, 'Working on Task', 'completed', '2026-11-18 09:58:10', '2026-11-19 10:12:04', 87234, NULL, 'Shift started', '2026-11-18 09:58:10', '2026-11-19 10:12:04', NULL),
(416, 2, NULL, NULL, 'Working on Task', 'completed', '2026-11-18 17:03:35', '2026-11-18 17:03:41', 6, NULL, 'Shift started', '2026-11-18 17:03:35', '2026-11-18 17:03:41', NULL),
(417, 5, NULL, NULL, 'Working on Task', 'active', '2026-11-19 09:41:08', NULL, 0, NULL, 'Shift started', '2026-11-19 09:41:08', '2026-11-19 09:41:08', NULL),
(418, 4, NULL, NULL, 'Working on Task', 'active', '2026-11-19 09:43:24', NULL, 0, NULL, 'Shift started', '2026-11-19 09:43:24', '2026-11-19 09:43:24', NULL),
(419, 9, NULL, NULL, 'Working on Task', 'active', '2026-11-19 09:44:38', NULL, 0, NULL, 'Shift started', '2026-11-19 09:44:38', '2026-11-19 09:44:38', NULL),
(420, 12, NULL, NULL, 'Working on Task', 'active', '2026-11-19 09:50:03', NULL, 0, NULL, 'Shift started', '2026-11-19 09:50:03', '2026-11-19 09:50:03', NULL),
(421, 10, NULL, NULL, 'Working on Task', 'active', '2026-11-19 09:55:46', NULL, 0, NULL, 'Shift started', '2026-11-19 09:55:46', '2026-11-19 09:55:46', NULL),
(422, 8, NULL, NULL, 'Working on Task', 'active', '2026-11-19 10:12:04', NULL, 0, NULL, 'Shift started', '2026-11-19 10:12:04', '2026-11-19 10:12:04', NULL);

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
(1, 1, '2024-01-01', 'PHP 8, Architecture, MySQL, Security, Product Design', '10 Years', '', '', 'Founding Admin & System Architect', NULL, '2026-08-19 07:09:49'),
(2, 2, '2024-01-15', 'Team Operations, Agile Management, Workflow Auditing', '7 Years', NULL, NULL, 'Operations Lead managing day-to-day work metrics', NULL, '2026-08-19 07:09:49'),
(3, 3, '2024-02-01', 'Product Roadmap, Sprint Planning, Jira Workflow', '6 Years', NULL, NULL, 'Product Manager leading SaaS feature sprints', NULL, '2026-08-19 07:09:49'),
(4, 4, '2024-02-10', 'PHP 8, Laravel, Vue.js, MySQL, REST APIs, Microservices', '5 Years', 'Dehradun', '+91 8791972367', 'Senior Developer leading backend architecture', NULL, '2026-08-19 07:09:49'),
(5, 5, '2024-03-01', 'Technical SEO, Ahrefs, SEMrush, Schema Markup', '5 Years', NULL, NULL, 'SEO Specialist focusing on organic growth', NULL, '2026-08-19 07:09:49'),
(6, 6, '2024-03-15', 'On-Page SEO, Link Building, Keyword Strategy', '3 Years', NULL, NULL, 'SEO Executive executing search optimization campaigns', NULL, '2026-08-19 07:09:49'),
(7, 7, '2024-04-01', 'Figma, Adobe XD, Illustrator, Glassmorphism UI', '4 Years', NULL, NULL, 'Creative UI/UX Designer crafting premium design systems', NULL, '2026-08-19 07:09:49'),
(8, 8, '2024-04-10', 'Content Strategy, Meta Ads, Brand Management', '4 Years', NULL, NULL, 'Social Media Lead growing online engagement', NULL, '2026-08-19 07:09:49'),
(9, 9, '2024-05-01', 'HTML5, CSS3, JavaScript, PHP basics', '6 Months', NULL, NULL, 'Enthusiastic Developer Intern', NULL, '2026-08-19 07:09:49'),
(10, 10, '2024-05-15', 'Directory Submissions, Social Bookmarking', '3 Months', NULL, NULL, 'Dedicated SEO Intern learning search analytics', NULL, '2026-08-19 07:09:49'),
(11, 11, '2026-09-28', NULL, NULL, '', '', NULL, NULL, '2026-09-28 16:51:58'),
(12, 12, '2026-09-28', NULL, NULL, '', '', NULL, NULL, '2026-09-28 17:03:12'),
(13, 13, '2026-10-14', NULL, NULL, '', '', NULL, NULL, '2026-10-14 17:53:21'),
(14, 14, '2026-10-15', NULL, NULL, '', '', NULL, NULL, '2026-10-15 13:19:02');

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
(10, 10, 7000.00, 0.00, '2026-08-19 07:09:49', '2026-08-19 07:09:49'),
(11, 12, 20000.00, 0.00, '2026-10-09 10:07:29', '2026-10-09 10:07:29'),
(12, 13, 20000.00, 0.00, '2026-10-15 12:11:33', '2026-10-15 12:11:33'),
(13, 14, 25000.00, 0.00, '2026-10-15 13:19:02', '2026-10-15 13:19:02');

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

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `user_id`, `leave_type`, `start_date`, `end_date`, `total_days`, `paid_days`, `unpaid_days`, `reason`, `attachment`, `status`, `approved_by`, `comment`, `created_at`) VALUES
(1, 4, 'paid', '2026-10-27', '2026-10-27', 1.0, 1.0, 0.0, 'Dear Sir,\r\nI would like to request leave for Tuesday, 27 October 2026, as I need to visit Srinagar for some important personal work and to collect my university degree.\r\nKindly approve my leave for the day. I will ensure that my pending tasks are managed accordingly', NULL, 'approved', 1, 'Approved by manager.', '2026-10-25 12:00:31'),
(2, 10, 'paid', '2026-10-28', '2026-10-28', 1.0, 1.0, 0.0, 'Dear Ma’am,\r\nI would like to request leave for today as I need to take my mother to the hospital for a medical check-up.\r\nKindly approve my leave for the day.\r\nThank you for your understanding.\r\nRegards,\r\nAman Gauniyal', NULL, 'pending', NULL, NULL, '2026-10-28 10:04:49'),
(3, 9, 'paid', '2026-10-31', '2026-10-31', 1.0, 1.0, 0.0, 'Good Afternoon Mam,\r\nI would like to request half-day leave for today due to some personal work.\r\nKindly approve my request. I will ensure that any pending work is managed accordingly.\r\nThank you for your understanding.\r\nRegards,\r\nAbhay', NULL, 'cancelled', NULL, NULL, '2026-10-31 13:58:49'),
(4, 9, 'paid', '2026-11-21', '2026-11-21', 1.0, 1.0, 0.0, 'Dear Mam,\r\nI would like to request leave on 21/11/26 as I need to attend my brother’s wedding and take part in the wedding preparations and ceremonies.\r\nKindly approve my leave for the mentioned period. I will ensure that my pending work is completed or properly managed before my leave.\r\nThank you for your understanding and consideration.\r\nRegards,\r\nAbhay', NULL, 'approved', 2, 'Approved by manager.', '2026-11-13 10:35:07'),
(5, 9, 'paid', '2026-11-26', '2026-11-27', 2.0, 2.0, 0.0, 'Dear Sir,\r\nI would like to request leave from 26/11/26 to 27/11/26 as I need to attend my brother’s wedding and take part in the wedding preparations and ceremonies.\r\nKindly approve my leave for the mentioned period. I will ensure that my pending work is completed or properly managed before my leave.\r\nThank you for your understanding and consideration.\r\nRegards,\r\nAbhay', NULL, 'approved', 2, 'Approved by manager.', '2026-11-13 10:37:02');

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
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `project_id` int(11) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `meeting_type` varchar(50) DEFAULT 'internal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meetings`
--

INSERT INTO `meetings` (`id`, `created_by`, `employee_id`, `title`, `description`, `meeting_date`, `start_time`, `end_time`, `duration`, `duration_minutes`, `meeting_time`, `meeting_link`, `location_link`, `status`, `response_notes`, `created_at`, `updated_at`, `project_id`, `client_name`, `meeting_type`) VALUES
(1, 1, 9, 'Maharani Website Discussion', 'Review mobile optimization progress and touch targets', '2026-09-25', '16:30', '17:00', 30, NULL, NULL, 'https://meet.google.com/krd-meet-mhr', NULL, 'declined', '', '2026-09-25 11:34:02', '2026-09-30 09:53:37', NULL, NULL, 'internal'),
(2, 7, 2, 'UI/UX Design Review', 'Cart drawer & checkout flow Figma review', '2026-09-25', '11:15', '11:45', 30, NULL, NULL, 'https://meet.google.com/krd-meet-ux', NULL, 'accepted', NULL, '2026-09-25 11:34:02', '2026-09-25 11:34:02', NULL, NULL, 'internal'),
(3, 1, 9, 'Maharani Website Discussion', 'Review mobile optimization progress and touch targets', '2026-09-25', '16:30', NULL, 30, NULL, NULL, 'https://meet.google.com/krd-meet-mhr', NULL, 'completed', 'Joining on Google Meet', '2026-09-25 12:54:20', '2026-09-25 12:54:20', NULL, NULL, 'internal'),
(4, 1, 9, 'Maharani Website Discussion', 'Review mobile optimization progress and touch targets', '2026-09-25', '16:30', NULL, 30, NULL, NULL, 'https://meet.google.com/krd-meet-mhr', NULL, 'completed', 'Joining on Google Meet', '2026-09-25 12:55:39', '2026-09-25 12:55:39', NULL, NULL, 'internal'),
(5, 1, 9, 'Maharani Website Discussion', 'Review mobile optimization progress and touch targets', '2026-09-25', '16:30', NULL, 30, NULL, NULL, 'https://meet.google.com/krd-meet-mhr', NULL, 'completed', 'Joining on Google Meet', '2026-09-25 12:58:40', '2026-09-25 12:58:40', NULL, NULL, 'internal'),
(6, 1, 4, 'Rahul Discussion', '', '2026-09-26', '10:40', '11:25', 45, 45, NULL, 'https://meet.google.com/krd-26630a6d', NULL, 'declined', '', '2026-09-26 10:49:57', '2026-10-05 10:08:49', NULL, NULL, 'internal'),
(7, 1, 4, 'Rahul Discussion', '', '2026-09-26', '11:06', '11:36', 30, 30, NULL, 'https://meet.google.com/krd-e311a01b', NULL, 'declined', '', '2026-09-26 11:07:37', '2026-10-05 10:08:38', NULL, '', 'boss');

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

--
-- Dumping data for table `meeting_participants`
--

INSERT INTO `meeting_participants` (`id`, `meeting_id`, `employee_id`, `status`, `created_at`) VALUES
(1, 6, 4, 'declined', '2026-09-26 10:49:57'),
(2, 7, 4, 'declined', '2026-09-26 11:07:37');

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
(34, 1, 'meeting_response', 'Meeting Accepted', 'Abhay Accepted meeting: \'Maharani Website Discussion\' (Joining on Google Meet)', 'pages/live-team-status.php', '{\"meeting_id\":5,\"employee_id\":9,\"status\":\"accepted\"}', 0, '2026-09-25 12:58:40'),
(35, 4, 'meeting', '🔔 Meeting Request: Rahul Discussion', 'Kartikeyan has requested you to join a meeting today at 10:40 (45 mins).', 'workday', NULL, 0, '2026-09-26 10:49:57'),
(36, 4, 'meeting', '🔔 Meeting Request: Rahul Discussion', 'Kartikeyan has requested a meeting on 2026-09-26 at 11:06 (30 mins).', 'workday', NULL, 0, '2026-09-26 11:07:37'),
(37, 1, 'warning', '⚠️ Meeting Declined: Maharani Website Discussion', 'Abhay marked: Declined', 'live-status', NULL, 0, '2026-09-30 09:53:37'),
(38, 1, 'warning', '⚠️ Meeting Declined: Rahul Discussion', 'Rahul marked: Declined', 'live-status', NULL, 0, '2026-10-05 10:08:38'),
(39, 1, 'warning', '⚠️ Meeting Declined: Rahul Discussion', 'Rahul marked: Declined', 'live-status', NULL, 0, '2026-10-05 10:08:49'),
(40, 4, 'task', 'New Task Assigned', 'You were assigned task: Make Website Requirement Docs and Send in Maharani Client Group', 'tasks', NULL, 0, '2026-10-12 16:49:03'),
(41, 8, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:19:27'),
(42, 8, 'task', 'New Task Assigned', 'You were assigned task: \'Upload New Photoshoot on MMT for MJ Aerosuites\' by Kartikeyan', 'task-details?id=29', NULL, 0, '2026-10-14 13:21:19'),
(43, 1, 'task', 'New Task Assigned', 'You were assigned task: \'Mj Residency Upload New Photoshoots On MMT\' by Kartikeyan', 'task-details?id=30', NULL, 0, '2026-10-14 13:32:01'),
(44, 8, 'project', 'Assigned to Project', 'You have been assigned to project \'Almost Heaven Mussoorie\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:34:46'),
(45, 8, 'task', 'New Task Assigned', 'You were assigned task: \'List Almost Heaven Whole Property ON MMT and airbnb\' by Kartikeyan', 'task-details?id=31', NULL, 0, '2026-10-14 13:35:27'),
(46, 4, 'project', 'Assigned to Project', 'You have been assigned to project \'Uttrakhand Stays Airbnb\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:38:23'),
(47, 6, 'project', 'Assigned to Project', 'You have been assigned to project \'Uttrakhand Stays Airbnb\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:38:23'),
(48, 5, 'project', 'Assigned to Project', 'You have been assigned to project \'Uttrakhand Stays Airbnb\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:38:23'),
(49, 8, 'project', 'Assigned to Project', 'You have been assigned to project \'Uttrakhand Stays Airbnb\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:38:23'),
(50, 3, 'project', 'Assigned to Project', 'You have been assigned to project \'Uttrakhand Stays Airbnb\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:38:23'),
(51, 8, 'task', 'New Task Assigned', 'You were assigned task: \'List Wildflower on Booking.com\' by Kartikeyan', 'task-details?id=32', NULL, 0, '2026-10-14 13:40:04'),
(52, 8, 'task', 'New Task Assigned', 'You were assigned task: \'List Sunrise On Booking.com\' by Kartikeyan', 'task-details?id=33', NULL, 0, '2026-10-14 13:40:38'),
(53, 2, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(54, 9, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(55, 4, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(56, 10, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(57, 12, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(58, 6, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(59, 5, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(60, 7, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(61, 8, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(62, 3, 'project', 'Assigned to Project', 'You have been assigned to project \'Krafting Digital\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:41:32'),
(63, 4, 'task', 'New Task Assigned', 'You were assigned task: \'Change Logo on Krafting digital Website\' by Kartikeyan', 'task-details?id=34', NULL, 0, '2026-10-14 13:42:48'),
(64, 4, 'task', 'New Task Assigned', 'You were assigned task: \'Make Website Live for Wildflower and Sunrise with New booking Engine\' by Kartikeyan', 'task-details?id=35', NULL, 0, '2026-10-14 13:44:42'),
(65, 2, 'project', 'Assigned to Project', 'You have been assigned to project \'Almost Heaven Rishikesh\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:50:47'),
(66, 9, 'project', 'Assigned to Project', 'You have been assigned to project \'Almost Heaven Rishikesh\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:50:47'),
(67, 4, 'project', 'Assigned to Project', 'You have been assigned to project \'Almost Heaven Rishikesh\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:50:47'),
(68, 5, 'project', 'Assigned to Project', 'You have been assigned to project \'Almost Heaven Rishikesh\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:50:47'),
(69, 8, 'project', 'Assigned to Project', 'You have been assigned to project \'Almost Heaven Rishikesh\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:50:47'),
(70, 2, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(71, 9, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(72, 4, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(73, 10, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(74, 12, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(75, 6, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(76, 5, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(77, 7, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(78, 3, 'project', 'Assigned to Project', 'You have been assigned to project \'MJ Hotels\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:51:20'),
(79, 1, 'task', 'New Task Assigned', 'You were assigned task: \'Triveni Ghat GMB Verification\' by Kartikeyan', 'task-details?id=36', NULL, 0, '2026-10-14 13:53:59'),
(80, 2, 'project', 'Assigned to Project', 'You have been assigned to project \'Masterpiece Psycology\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:55:12'),
(81, 9, 'project', 'Assigned to Project', 'You have been assigned to project \'Masterpiece Psycology\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:55:12'),
(82, 4, 'project', 'Assigned to Project', 'You have been assigned to project \'Masterpiece Psycology\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:55:12'),
(83, 10, 'project', 'Assigned to Project', 'You have been assigned to project \'Masterpiece Psycology\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:55:12'),
(84, 12, 'project', 'Assigned to Project', 'You have been assigned to project \'Masterpiece Psycology\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:55:12'),
(85, 6, 'project', 'Assigned to Project', 'You have been assigned to project \'Masterpiece Psycology\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:55:12'),
(86, 5, 'project', 'Assigned to Project', 'You have been assigned to project \'Masterpiece Psycology\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:55:12'),
(87, 7, 'project', 'Assigned to Project', 'You have been assigned to project \'Masterpiece Psycology\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-14 13:55:12'),
(88, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Make SEO implementation plan for Masterpiece Psycology\' by Kartikeyan', 'task-details?id=37', NULL, 0, '2026-10-14 13:57:01'),
(89, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Send New SEO plan after KD approval to Tess via email for approval\' by Kartikeyan', 'task-details?id=38', NULL, 0, '2026-10-14 13:59:24'),
(90, 1, 'task', 'New Task Assigned', 'You were assigned task: \'Launch TAj meta ads again . Check ads and make detailed report\' by Kartikeyan', 'task-details?id=39', NULL, 0, '2026-10-14 14:02:32'),
(91, 3, 'task', 'New Task Assigned', 'You were assigned task: \'Make New Taj reels from new photoshoots\' by Kartikeyan', 'task-details?id=40', NULL, 0, '2026-10-14 14:04:36'),
(92, 2, 'task', 'New Task Assigned', 'You were assigned task: \'Shedule a Meeting with Renee Mill\' by Kartikeyan', 'task-details?id=41', NULL, 0, '2026-10-14 14:06:39'),
(93, 1, 'task', 'New Task Assigned', 'You were assigned task: \'Send Whatsapp Solution and Table Booking solution to Amardeep\' by Kartikeyan', 'task-details?id=42', NULL, 0, '2026-10-14 14:08:05'),
(94, 2, 'task', 'New Task Assigned', 'You were assigned task: \'Shedule a Meeting with Black apple Cellular with Mrinali and KD\' by Kartikeyan', 'task-details?id=43', NULL, 0, '2026-10-14 14:10:44'),
(95, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Make Detailed SOP for Blog Posting\' by Kartikeyan', 'task-details?id=44', NULL, 0, '2026-10-14 14:12:25'),
(96, 4, 'task', 'New Task Assigned', 'You were assigned task: \'Make Final Maharani Website\' by Kartikeyan', 'task-details?id=45', NULL, 0, '2026-10-14 14:14:31'),
(97, 4, 'task', 'New Task Assigned', 'You were assigned task: \'Make Final Menu software for Maharani\' by Kartikeyan', 'task-details?id=46', NULL, 0, '2026-10-14 14:15:43'),
(98, 2, 'task', 'New Task Assigned', 'You were assigned task: \'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\' by Kartikeyan', 'task-details?id=47', NULL, 0, '2026-10-14 14:18:07'),
(99, 1, 'task', 'New Task Assigned', 'You were assigned task: \'Make Changes on Almost heaven Final website (In cordination with Gurpreet ma&#039;am)\' by Kartikeyan', 'task-details?id=48', NULL, 0, '2026-10-14 14:19:47'),
(100, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Make SEO plan for Blackapple\' by Kartikeyan', 'task-details?id=49', NULL, 0, '2026-10-14 14:21:20'),
(101, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Make new SEO plan for Mj catering and get it approved by KD\' by Kartikeyan', 'task-details?id=50', NULL, 0, '2026-10-14 14:22:29'),
(102, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Make Seo implementation plan for Jeevan sankalp .(local and website )\' by Kartikeyan', 'task-details?id=51', NULL, 0, '2026-10-14 14:24:30'),
(103, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Make new seo plan for Turmeric\' by Kartikeyan', 'task-details?id=52', NULL, 0, '2026-10-14 14:25:41'),
(104, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Implement the plan made for masterpiece psycology\' by Kartikeyan', 'task-details?id=53', NULL, 0, '2026-10-14 14:27:12'),
(105, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Apply SEO Plan For Jeevan Sankalp\' by Kartikeyan', 'task-details?id=54', NULL, 0, '2026-10-14 14:28:36'),
(106, 6, 'task', 'New Task Assigned', 'You were assigned task: \'Make 100 backlinks for jeeven sankalp\' by Kartikeyan', 'task-details?id=55', NULL, 0, '2026-10-14 14:30:25'),
(107, 1, 'task', 'New Task Assigned', 'You were assigned task: \'Fix Liquor Fort GMB and Everything\' by Kartikeyan', 'task-details?id=56', NULL, 0, '2026-10-14 14:32:04'),
(108, 4, 'task', 'New Task Assigned', 'You were assigned task: \'Fix Destination Wedding Website .\' by Kartikeyan', 'task-details?id=57', NULL, 0, '2026-10-14 14:38:39'),
(109, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Make SEO plan for shivam dang new website ( clinic website ) and make changes on gmb as he has asked us to\' by Kartikeyan', 'task-details?id=58', NULL, 0, '2026-10-14 14:42:25'),
(110, 5, 'task', 'New Task Assigned', 'You were assigned task: \'APPLY SEO PLAN FOR OCT MONTH THAT WAS APPROVED BY KD\' by Kartikeyan', 'task-details?id=59', NULL, 0, '2026-10-14 14:43:21'),
(111, 1, 'task', 'New Task Assigned', 'You were assigned task: \'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\' by Kartikeyan', 'task-details?id=60', NULL, 0, '2026-10-14 14:44:19'),
(112, 1, 'task', 'New Task Assigned', 'You were assigned task: \'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\' by Kartikeyan', 'task-details?id=61', NULL, 0, '2026-10-14 14:45:43'),
(113, 5, 'task', 'New Task Assigned', 'You were assigned task: \'CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE\' by Kartikeyan', 'task-details?id=62', NULL, 0, '2026-10-14 14:47:41'),
(114, 8, 'task', 'New Task Assigned', 'You were assigned task: \'GET STANDARD SMM PHOTOSHOOT DONE\' by Kartikeyan', 'task-details?id=63', NULL, 0, '2026-10-14 14:49:27'),
(115, 8, 'task', 'New Task Assigned', 'You were assigned task: \'Standard Social Media\' by Udit', 'task-details?id=64', NULL, 0, '2026-10-14 15:06:30'),
(116, 6, 'task', 'New Task Assigned', 'You were assigned task: \'Make 100 backlinks for jeeven sankalp\' by Uvaish', 'task-details?id=65', NULL, 0, '2026-10-14 15:41:27'),
(117, 13, 'task', 'New Task Assigned', 'You were assigned task: \'Photo Shoot Mj Grand Wedding\' by Kartikeyan', 'task-details?id=66', NULL, 0, '2026-10-14 18:23:56'),
(118, 13, 'task', 'New Task Assigned', 'You were assigned task: \'Mj Dandiya Fair Photoshoot\' by Kartikeyan', 'task-details?id=67', NULL, 0, '2026-10-14 18:25:01'),
(119, 8, 'task', 'New Task Assigned', 'You were assigned task: \'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )\' by Kartikeyan', 'task-details?id=68', NULL, 0, '2026-10-14 18:28:19'),
(120, 9, 'task', 'New Task Assigned', 'You were assigned task: \'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\' by Rahul', 'task-details?id=69', NULL, 0, '2026-10-15 10:21:45'),
(121, 1, 'emergency_request', '🚨 Emergency Request: Make Final Menu software for Maharani', 'Rahul submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-15 10:33:38'),
(122, 2, 'emergency_request', '🚨 Emergency Request: Make Final Menu software for Maharani', 'Rahul submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-15 10:33:38'),
(123, 3, 'emergency_request', '🚨 Emergency Request: Make Final Menu software for Maharani', 'Rahul submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-15 10:33:38'),
(124, 5, 'emergency_request', '🚨 Emergency Request: Make Final Menu software for Maharani', 'Rahul submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-15 10:33:38'),
(125, 8, 'emergency_request', '🚨 Emergency Request: Make Final Menu software for Maharani', 'Rahul submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-15 10:33:38'),
(126, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #46 (\'Make Final Menu software for Maharani\') and submitted it for your approval.', 'task-details?id=46', NULL, 0, '2026-10-15 12:05:19'),
(127, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #46 (\'Make Final Menu software for Maharani\') and submitted it for your approval.', 'task-details?id=46', NULL, 0, '2026-10-15 12:05:19'),
(128, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #46 (\'Make Final Menu software for Maharani\') and submitted it for your approval.', 'task-details?id=46', NULL, 0, '2026-10-15 12:05:19'),
(129, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #46 (\'Make Final Menu software for Maharani\') and submitted it for your approval.', 'task-details?id=46', NULL, 0, '2026-10-15 12:05:19'),
(130, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #46 (\'Make Final Menu software for Maharani\') and submitted it for your approval.', 'task-details?id=46', NULL, 0, '2026-10-15 12:05:19'),
(131, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #46 (\'Make Final Menu software for Maharani\') and submitted it for your approval.', 'task-details?id=46', NULL, 0, '2026-10-15 12:05:19'),
(132, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Kashmiri sons 5 Blogs Uttarakhand wedding\' by Uvaish', 'task-details?id=70', NULL, 0, '2026-10-15 12:14:33'),
(133, 10, 'task', 'New Task Assigned', 'You were assigned task: \'kashmiri sons blogs on Uttarakhand Stays\' by Uvaish', 'task-details?id=71', NULL, 0, '2026-10-15 12:18:06'),
(134, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Kashmiri Sons blogs on Dehradungram\' by Uvaish', 'task-details?id=72', NULL, 0, '2026-10-15 12:20:41'),
(135, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Jeevan sankalp blogs on Dehradun gurugram\' by Uvaish', 'task-details?id=73', NULL, 0, '2026-10-15 12:27:13'),
(136, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Jeevan sankalp blogs on Uttarakhand Diaries\' by Uvaish', 'task-details?id=74', NULL, 0, '2026-10-15 12:28:49'),
(137, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Frontier Jewellers blogs on Uttarakhand wedding\' by Uvaish', 'task-details?id=75', NULL, 0, '2026-10-15 12:32:39'),
(138, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Frontier Jewellers blogs on Dehradun Gurugram\' by Uvaish', 'task-details?id=76', NULL, 0, '2026-10-15 12:33:51'),
(139, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Frontier Jewellers blogs on Uttarakhan Diaries\' by Uvaish', 'task-details?id=77', NULL, 0, '2026-10-15 12:35:18'),
(140, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Uttarakhand Wedding blogs for Aristella\' by Uvaish', 'task-details?id=78', NULL, 0, '2026-10-15 12:37:18'),
(141, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Uttarakhand Diaries blogs for Aristella\' by Uvaish', 'task-details?id=79', NULL, 0, '2026-10-15 12:40:05'),
(142, 10, 'task', 'New Task Assigned', 'You were assigned task: \'Dehradun Gurugram blogs for Aristella\' by Uvaish', 'task-details?id=80', NULL, 0, '2026-10-15 12:41:23'),
(143, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Anjali has completed task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\') and submitted it for your approval.', 'task-details?id=47', NULL, 0, '2026-10-15 13:15:05'),
(144, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Anjali has completed task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\') and submitted it for your approval.', 'task-details?id=47', NULL, 0, '2026-10-15 13:15:05'),
(145, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Anjali has completed task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\') and submitted it for your approval.', 'task-details?id=47', NULL, 0, '2026-10-15 13:15:05'),
(146, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Anjali has completed task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\') and submitted it for your approval.', 'task-details?id=47', NULL, 0, '2026-10-15 13:15:05'),
(147, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Anjali has completed task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\') and submitted it for your approval.', 'task-details?id=47', NULL, 0, '2026-10-15 13:15:05'),
(148, 2, 'task', 'Task Approved & Marked Done!', 'Your completed task #47 (\'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment\') was approved by Anjali and marked as Done.', 'task-details?id=47', NULL, 0, '2026-10-15 13:15:20'),
(149, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #64 (\'Standard Social Media\') and submitted it for your approval.', 'task-details?id=64', NULL, 0, '2026-10-15 13:36:00'),
(150, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #64 (\'Standard Social Media\') and submitted it for your approval.', 'task-details?id=64', NULL, 0, '2026-10-15 13:36:00'),
(151, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #64 (\'Standard Social Media\') and submitted it for your approval.', 'task-details?id=64', NULL, 0, '2026-10-15 13:36:00'),
(152, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #64 (\'Standard Social Media\') and submitted it for your approval.', 'task-details?id=64', NULL, 0, '2026-10-15 13:36:00'),
(153, 4, 'emergency_approved', '✅ Emergency Request Approved', 'Your emergency request for \'Make Final Menu software for Maharani\' was APPROVED by Kartikeyan. Note: get this done and update me personally once done .', 'task-details?id=46', NULL, 0, '2026-10-15 14:38:16'),
(154, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #69 (\'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\') and submitted it for your approval.', 'task-details?id=69', NULL, 0, '2026-10-15 15:11:33'),
(155, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #69 (\'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\') and submitted it for your approval.', 'task-details?id=69', NULL, 0, '2026-10-15 15:11:33'),
(156, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #69 (\'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\') and submitted it for your approval.', 'task-details?id=69', NULL, 0, '2026-10-15 15:11:33'),
(157, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #69 (\'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\') and submitted it for your approval.', 'task-details?id=69', NULL, 0, '2026-10-15 15:11:33'),
(158, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #69 (\'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\') and submitted it for your approval.', 'task-details?id=69', NULL, 0, '2026-10-15 15:11:33'),
(159, 1, 'task_flag', '🚩 Task Flagged: Make Final Maharani Website', 'Rahul flagged \'Make Final Maharani Website\' [Other]. Impact: Clarification Required.', 'flagged-tasks', NULL, 0, '2026-10-15 15:12:00'),
(160, 2, 'task_flag', '🚩 Task Flagged: Make Final Maharani Website', 'Rahul flagged \'Make Final Maharani Website\' [Other]. Impact: Clarification Required.', 'flagged-tasks', NULL, 0, '2026-10-15 15:12:00'),
(161, 3, 'task_flag', '🚩 Task Flagged: Make Final Maharani Website', 'Rahul flagged \'Make Final Maharani Website\' [Other]. Impact: Clarification Required.', 'flagged-tasks', NULL, 0, '2026-10-15 15:12:00'),
(162, 5, 'task_flag', '🚩 Task Flagged: Make Final Maharani Website', 'Rahul flagged \'Make Final Maharani Website\' [Other]. Impact: Clarification Required.', 'flagged-tasks', NULL, 0, '2026-10-15 15:12:00'),
(163, 8, 'task_flag', '🚩 Task Flagged: Make Final Maharani Website', 'Rahul flagged \'Make Final Maharani Website\' [Other]. Impact: Clarification Required.', 'flagged-tasks', NULL, 0, '2026-10-15 15:12:00'),
(164, 9, 'task', 'Task Approved & Marked Done!', 'Your completed task #69 (\'Fix Destination Wedding Website .(The task has been assigned to Abhay.)\') was approved by Rahul and marked as Done.', 'task-details?id=69', NULL, 0, '2026-10-15 15:13:09'),
(165, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #57 (\'Fix Destination Wedding Website .\') and submitted it for your approval.', 'task-details?id=57', NULL, 0, '2026-10-15 15:14:20'),
(166, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #57 (\'Fix Destination Wedding Website .\') and submitted it for your approval.', 'task-details?id=57', NULL, 0, '2026-10-15 15:14:20'),
(167, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #57 (\'Fix Destination Wedding Website .\') and submitted it for your approval.', 'task-details?id=57', NULL, 0, '2026-10-15 15:14:20'),
(168, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #57 (\'Fix Destination Wedding Website .\') and submitted it for your approval.', 'task-details?id=57', NULL, 0, '2026-10-15 15:14:20'),
(169, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #57 (\'Fix Destination Wedding Website .\') and submitted it for your approval.', 'task-details?id=57', NULL, 0, '2026-10-15 15:14:20'),
(170, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #57 (\'Fix Destination Wedding Website .\') and submitted it for your approval.', 'task-details?id=57', NULL, 0, '2026-10-15 15:14:20'),
(171, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #68 (\'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )\') and submitted it for your approval.', 'task-details?id=68', NULL, 0, '2026-10-15 15:39:37'),
(172, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #68 (\'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )\') and submitted it for your approval.', 'task-details?id=68', NULL, 0, '2026-10-15 15:39:37'),
(173, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #68 (\'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )\') and submitted it for your approval.', 'task-details?id=68', NULL, 0, '2026-10-15 15:39:37'),
(174, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #68 (\'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )\') and submitted it for your approval.', 'task-details?id=68', NULL, 0, '2026-10-15 15:39:37'),
(175, 9, 'task', 'New Task Assigned', 'You were assigned task: \'Shein Mill Website Development\' by Rahul', 'task-details?id=81', NULL, 0, '2026-10-15 16:34:23'),
(176, 14, 'task', 'Task Reassigned to You', 'Task \'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\' has been assigned to you by Anjali.', 'task-details?id=61', NULL, 0, '2026-10-15 16:56:30'),
(177, 14, 'task', 'Task Reassigned to You', 'Task \'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\' has been assigned to you by Anjali.', 'task-details?id=60', NULL, 0, '2026-10-15 16:57:10'),
(178, 14, 'task', 'Task Reassigned to You', 'Task \'Launch TAj meta ads again . Check ads and make detailed report\' has been assigned to you by Anjali.', 'task-details?id=39', NULL, 0, '2026-10-15 16:57:22'),
(179, 1, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') and submitted it for your approval.', 'task-details?id=61', NULL, 0, '2026-10-15 17:39:07'),
(180, 2, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') and submitted it for your approval.', 'task-details?id=61', NULL, 0, '2026-10-15 17:39:07'),
(181, 3, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') and submitted it for your approval.', 'task-details?id=61', NULL, 0, '2026-10-15 17:39:07'),
(182, 5, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') and submitted it for your approval.', 'task-details?id=61', NULL, 0, '2026-10-15 17:39:07'),
(183, 8, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') and submitted it for your approval.', 'task-details?id=61', NULL, 0, '2026-10-15 17:39:07'),
(184, 1, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') and submitted it for your approval.', 'task-details?id=61', NULL, 0, '2026-10-15 17:39:07'),
(185, 1, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') and submitted it for your approval.', 'task-details?id=39', NULL, 0, '2026-10-15 17:41:18'),
(186, 2, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') and submitted it for your approval.', 'task-details?id=39', NULL, 0, '2026-10-15 17:41:18'),
(187, 3, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') and submitted it for your approval.', 'task-details?id=39', NULL, 0, '2026-10-15 17:41:18'),
(188, 5, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') and submitted it for your approval.', 'task-details?id=39', NULL, 0, '2026-10-15 17:41:18'),
(189, 8, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') and submitted it for your approval.', 'task-details?id=39', NULL, 0, '2026-10-15 17:41:18'),
(190, 1, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') and submitted it for your approval.', 'task-details?id=39', NULL, 0, '2026-10-15 17:41:18'),
(191, 1, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') and submitted it for your approval.', 'task-details?id=60', NULL, 0, '2026-10-15 17:42:25'),
(192, 2, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') and submitted it for your approval.', 'task-details?id=60', NULL, 0, '2026-10-15 17:42:25'),
(193, 3, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') and submitted it for your approval.', 'task-details?id=60', NULL, 0, '2026-10-15 17:42:25'),
(194, 5, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') and submitted it for your approval.', 'task-details?id=60', NULL, 0, '2026-10-15 17:42:25'),
(195, 8, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') and submitted it for your approval.', 'task-details?id=60', NULL, 0, '2026-10-15 17:42:25'),
(196, 1, 'task_approval', 'Task Completion Approval Request', 'Employee PRABHAT has completed task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') and submitted it for your approval.', 'task-details?id=60', NULL, 0, '2026-10-15 17:42:25'),
(197, 1, 'task_flag', '🚩 Task Flagged: Make Website Live for Wildflower and Sunrise with New booking Engine', 'Rahul flagged \'Make Website Live for Wildflower and Sunrise with New booking Engine\' [Waiting for Approval]. Impact: Task Cannot Start.', 'flagged-tasks', NULL, 0, '2026-10-15 17:48:26'),
(198, 2, 'task_flag', '🚩 Task Flagged: Make Website Live for Wildflower and Sunrise with New booking Engine', 'Rahul flagged \'Make Website Live for Wildflower and Sunrise with New booking Engine\' [Waiting for Approval]. Impact: Task Cannot Start.', 'flagged-tasks', NULL, 0, '2026-10-15 17:48:26'),
(199, 3, 'task_flag', '🚩 Task Flagged: Make Website Live for Wildflower and Sunrise with New booking Engine', 'Rahul flagged \'Make Website Live for Wildflower and Sunrise with New booking Engine\' [Waiting for Approval]. Impact: Task Cannot Start.', 'flagged-tasks', NULL, 0, '2026-10-15 17:48:26'),
(200, 5, 'task_flag', '🚩 Task Flagged: Make Website Live for Wildflower and Sunrise with New booking Engine', 'Rahul flagged \'Make Website Live for Wildflower and Sunrise with New booking Engine\' [Waiting for Approval]. Impact: Task Cannot Start.', 'flagged-tasks', NULL, 0, '2026-10-15 17:48:26'),
(201, 8, 'task_flag', '🚩 Task Flagged: Make Website Live for Wildflower and Sunrise with New booking Engine', 'Rahul flagged \'Make Website Live for Wildflower and Sunrise with New booking Engine\' [Waiting for Approval]. Impact: Task Cannot Start.', 'flagged-tasks', NULL, 0, '2026-10-15 17:48:26'),
(202, 8, 'task', 'Task Approved & Marked Done!', 'Your completed task #68 (\'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )\') was approved by Kartikeyan and marked as Done.', 'task-details?id=68', NULL, 0, '2026-10-15 18:26:48'),
(203, 4, 'task', 'Task Completion Returned for Revision', 'Task #57 (\'Fix Destination Wedding Website .\') was reviewed by Kartikeyan. Revisions requested: not completed', 'task-details?id=57', NULL, 0, '2026-10-15 18:28:22'),
(204, 4, 'flag_comment', '💬 Update on Flag #5', 'Kartikeyan replied on your flagged task \'Make Website Live for Wildflower and Sunrise with New booking Engine\': please call zimmerstack and get this completed . you have there numbers in the group . call them and complete the task .', 'task-details?id=35', NULL, 0, '2026-10-15 18:31:39'),
(205, 4, 'flag_comment', '💬 Update on Flag #4', 'Kartikeyan replied on your flagged task \'Make Final Maharani Website\': We have to provide the demo on temporary link only .', 'task-details?id=45', NULL, 0, '2026-10-15 19:03:23'),
(206, 4, 'flag_comment', '💬 Update on Flag #4', 'Kartikeyan replied on your flagged task \'Make Final Maharani Website\': integrate the whole system with the current website . also add a dashbord for inhouse table orders through the menu qr code. also make 10 qr codes for different tables so that people of inhouse dining can scan the qr code and order there food from the interactive menu we have made.', 'task-details?id=45', NULL, 0, '2026-10-15 19:05:14'),
(207, 4, 'flag_comment', '💬 Update on Flag #4', 'Kartikeyan replied on your flagged task \'Make Final Maharani Website\': once done give me a demo then we can take this forward', 'task-details?id=45', NULL, 0, '2026-10-15 19:05:31'),
(208, 4, 'flag_status', 'Flag Status Updated', 'Your flagged issue on \'Make Final Maharani Website\' is now Waiting Info', 'task-details?id=45', NULL, 0, '2026-10-15 19:06:03'),
(209, 4, 'flag_status', 'Flag Status Updated', 'Your flagged issue on \'Make Website Live for Wildflower and Sunrise with New booking Engine\' is now Open', 'task-details?id=35', NULL, 0, '2026-10-15 19:06:32'),
(210, 14, 'task', 'Task Approved & Marked Done!', 'Your completed task #61 (\'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY\') was approved by Kartikeyan and marked as Done.', 'task-details?id=61', NULL, 0, '2026-10-15 19:10:41'),
(211, 14, 'task', 'Task Completion Returned for Revision', 'Task #60 (\'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION\') was reviewed by Kartikeyan. Revisions requested: we already have a budget of 7000 INR from Shivam bhaiya . Please check the ads spend it it has not touched 7000 (inclusive of tax ) then you can start running the ads . In case the 7000 rs have been fully used ask anjali or mrinali to take it from shivam bhaiya and you can just start running ads . update once the ads are live .', 'task-details?id=60', NULL, 0, '2026-10-15 19:12:43'),
(212, 14, 'task', 'Task Completion Returned for Revision', 'Task #39 (\'Launch TAj meta ads again . Check ads and make detailed report\') was reviewed by Kartikeyan. Revisions requested: have added 5000 INR in the Kotak ads account . keep the daily budget only 300 rs for the 1st few days and start the ads . i will be adding more funds to this by tomorrow or saturday .', 'task-details?id=39', NULL, 0, '2026-10-15 19:13:51'),
(213, 8, 'task', 'Task Completion Returned for Revision', 'Task #64 (\'Standard Social Media\') was reviewed by Kartikeyan. Revisions requested: I want a posting report with timelines on my table tomorrow . after that i will approve this .', 'task-details?id=64', NULL, 0, '2026-10-15 19:15:06'),
(214, 4, 'task', 'Task Completion Returned for Revision', 'Task #46 (\'Make Final Menu software for Maharani\') was reviewed by Kartikeyan. Revisions requested: Do the mentioned changes and then resend for approval . in case of any confusions call me directly .', 'task-details?id=46', NULL, 0, '2026-10-15 19:15:52'),
(215, 4, 'flag_status', 'Flag Status Updated', 'Your flagged issue on \'Make Website Live for Wildflower and Sunrise with New booking Engine\' is now Open', 'task-details?id=35', NULL, 0, '2026-10-15 19:16:21'),
(216, 1, 'emergency_request', '🚨 Emergency Request: Make 100 backlinks for jeeven sankalp', 'Shivanshu submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-16 12:46:37'),
(217, 2, 'emergency_request', '🚨 Emergency Request: Make 100 backlinks for jeeven sankalp', 'Shivanshu submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-16 12:46:37'),
(218, 3, 'emergency_request', '🚨 Emergency Request: Make 100 backlinks for jeeven sankalp', 'Shivanshu submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-16 12:46:37'),
(219, 5, 'emergency_request', '🚨 Emergency Request: Make 100 backlinks for jeeven sankalp', 'Shivanshu submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-16 12:46:37'),
(220, 8, 'emergency_request', '🚨 Emergency Request: Make 100 backlinks for jeeven sankalp', 'Shivanshu submitted an urgent request: \'Other emergency\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-16 12:46:37'),
(221, 6, 'emergency_approved', '✅ Emergency Request Approved', 'Your emergency request for \'Make 100 backlinks for jeeven sankalp\' was APPROVED by Kartikeyan.', 'task-details?id=65', NULL, 0, '2026-10-16 16:44:34'),
(222, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\') and submitted it for your approval.', 'task-details?id=28', NULL, 0, '2026-10-17 10:03:50'),
(223, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\') and submitted it for your approval.', 'task-details?id=28', NULL, 0, '2026-10-17 10:03:50'),
(224, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\') and submitted it for your approval.', 'task-details?id=28', NULL, 0, '2026-10-17 10:03:50'),
(225, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\') and submitted it for your approval.', 'task-details?id=28', NULL, 0, '2026-10-17 10:03:50'),
(226, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\') and submitted it for your approval.', 'task-details?id=28', NULL, 0, '2026-10-17 10:03:50'),
(227, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Rahul has completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\') and submitted it for your approval.', 'task-details?id=28', NULL, 0, '2026-10-17 10:03:50'),
(228, 4, 'task', 'Task Approved & Marked Done!', 'Your completed task #28 (\'Make Website Requirement Docs and Send in Maharani Client Group\') was approved by Kartikeyan and marked as Done.', 'task-details?id=28', NULL, 0, '2026-10-17 12:56:15'),
(229, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #81 (\'Shein Mill Website Development\') and submitted it for your approval.', 'task-details?id=81', NULL, 0, '2026-10-19 10:03:38'),
(230, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #81 (\'Shein Mill Website Development\') and submitted it for your approval.', 'task-details?id=81', NULL, 0, '2026-10-19 10:03:38'),
(231, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #81 (\'Shein Mill Website Development\') and submitted it for your approval.', 'task-details?id=81', NULL, 0, '2026-10-19 10:03:38'),
(232, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #81 (\'Shein Mill Website Development\') and submitted it for your approval.', 'task-details?id=81', NULL, 0, '2026-10-19 10:03:38');
INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `link`, `data`, `is_read`, `created_at`) VALUES
(233, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #81 (\'Shein Mill Website Development\') and submitted it for your approval.', 'task-details?id=81', NULL, 0, '2026-10-19 10:03:38'),
(234, 4, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #81 (\'Shein Mill Website Development\') and submitted it for your approval.', 'task-details?id=81', NULL, 0, '2026-10-19 10:03:38'),
(235, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #70 (\'Kashmiri sons 5 Blogs Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=70', NULL, 0, '2026-10-19 11:19:44'),
(236, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #70 (\'Kashmiri sons 5 Blogs Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=70', NULL, 0, '2026-10-19 11:19:44'),
(237, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #70 (\'Kashmiri sons 5 Blogs Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=70', NULL, 0, '2026-10-19 11:19:44'),
(238, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #70 (\'Kashmiri sons 5 Blogs Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=70', NULL, 0, '2026-10-19 11:19:44'),
(239, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #70 (\'Kashmiri sons 5 Blogs Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=70', NULL, 0, '2026-10-19 11:19:44'),
(240, 9, 'project', 'Assigned to Project', 'You have been assigned to project \'Turmeric victoria\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-21 12:22:54'),
(241, 4, 'project', 'Assigned to Project', 'You have been assigned to project \'Turmeric victoria\' by Kartikeyan.', 'projects', NULL, 0, '2026-10-21 12:22:54'),
(242, 9, 'task', 'New Task Assigned', 'You were assigned task: \'Turmeric victoria\' by Rahul', 'task-details?id=82', NULL, 0, '2026-10-21 12:36:49'),
(243, 9, 'task', 'New Task Assigned', 'You were assigned task: \'Turmeric victoria\' by Rahul', 'task-details?id=83', NULL, 0, '2026-10-21 12:36:52'),
(244, 9, 'task', 'Task Approved & Marked Done!', 'Your completed task #81 (\'Shein Mill Website Development\') was approved by Rahul and marked as Done.', 'task-details?id=81', NULL, 0, '2026-10-21 12:50:56'),
(245, 10, 'task', 'New Task Assigned', 'You were assigned task: \'ignore\' by Uvaish', 'task-details?id=84', NULL, 0, '2026-10-21 16:16:35'),
(246, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #71 (\'kashmiri sons blogs on Uttarakhand Stays\') and submitted it for your approval.', 'task-details?id=71', NULL, 0, '2026-10-22 10:20:31'),
(247, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #71 (\'kashmiri sons blogs on Uttarakhand Stays\') and submitted it for your approval.', 'task-details?id=71', NULL, 0, '2026-10-22 10:20:31'),
(248, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #71 (\'kashmiri sons blogs on Uttarakhand Stays\') and submitted it for your approval.', 'task-details?id=71', NULL, 0, '2026-10-22 10:20:31'),
(249, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #71 (\'kashmiri sons blogs on Uttarakhand Stays\') and submitted it for your approval.', 'task-details?id=71', NULL, 0, '2026-10-22 10:20:31'),
(250, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #71 (\'kashmiri sons blogs on Uttarakhand Stays\') and submitted it for your approval.', 'task-details?id=71', NULL, 0, '2026-10-22 10:20:31'),
(251, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #55 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=55', NULL, 0, '2026-10-22 10:55:02'),
(252, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #55 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=55', NULL, 0, '2026-10-22 10:55:02'),
(253, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #55 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=55', NULL, 0, '2026-10-22 10:55:02'),
(254, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #55 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=55', NULL, 0, '2026-10-22 10:55:02'),
(255, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #55 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=55', NULL, 0, '2026-10-22 10:55:02'),
(256, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #55 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=55', NULL, 0, '2026-10-22 10:55:02'),
(257, 6, 'task', 'New Task Assigned', 'You were assigned task: \'backlinks on frontier jewellers\' by Uvaish', 'task-details?id=85', NULL, 0, '2026-10-22 11:01:14'),
(258, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #32 (\'List Wildflower on Booking.com\') and submitted it for your approval.', 'task-details?id=32', NULL, 0, '2026-10-22 13:15:34'),
(259, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #32 (\'List Wildflower on Booking.com\') and submitted it for your approval.', 'task-details?id=32', NULL, 0, '2026-10-22 13:15:34'),
(260, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #32 (\'List Wildflower on Booking.com\') and submitted it for your approval.', 'task-details?id=32', NULL, 0, '2026-10-22 13:15:34'),
(261, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #32 (\'List Wildflower on Booking.com\') and submitted it for your approval.', 'task-details?id=32', NULL, 0, '2026-10-22 13:15:34'),
(262, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\') and submitted it for your approval.', 'task-details?id=63', NULL, 0, '2026-10-22 13:15:43'),
(263, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\') and submitted it for your approval.', 'task-details?id=63', NULL, 0, '2026-10-22 13:15:43'),
(264, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\') and submitted it for your approval.', 'task-details?id=63', NULL, 0, '2026-10-22 13:15:43'),
(265, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\') and submitted it for your approval.', 'task-details?id=63', NULL, 0, '2026-10-22 13:15:43'),
(266, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\') and submitted it for your approval.', 'task-details?id=63', NULL, 0, '2026-10-22 13:15:43'),
(267, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #33 (\'List Sunrise On Booking.com\') and submitted it for your approval.', 'task-details?id=33', NULL, 0, '2026-10-22 13:44:19'),
(268, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #33 (\'List Sunrise On Booking.com\') and submitted it for your approval.', 'task-details?id=33', NULL, 0, '2026-10-22 13:44:19'),
(269, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #33 (\'List Sunrise On Booking.com\') and submitted it for your approval.', 'task-details?id=33', NULL, 0, '2026-10-22 13:44:19'),
(270, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #33 (\'List Sunrise On Booking.com\') and submitted it for your approval.', 'task-details?id=33', NULL, 0, '2026-10-22 13:44:19'),
(271, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #33 (\'List Sunrise On Booking.com\') and submitted it for your approval.', 'task-details?id=33', NULL, 0, '2026-10-22 13:44:19'),
(272, 8, 'task', 'Task Approved & Marked Done!', 'Your completed task #33 (\'List Sunrise On Booking.com\') was approved by Kartikeyan and marked as Done.', 'task-details?id=33', NULL, 0, '2026-10-22 16:53:52'),
(273, 8, 'task', 'Task Approved & Marked Done!', 'Your completed task #32 (\'List Wildflower on Booking.com\') was approved by Kartikeyan and marked as Done.', 'task-details?id=32', NULL, 0, '2026-10-22 16:54:01'),
(274, 8, 'task', 'Task Approved & Marked Done!', 'Your completed task #63 (\'GET STANDARD SMM PHOTOSHOOT DONE\') was approved by Kartikeyan and marked as Done.', 'task-details?id=63', NULL, 0, '2026-10-22 16:54:11'),
(275, 6, 'task', 'Task Approved & Marked Done!', 'Your completed task #55 (\'Make 100 backlinks for jeeven sankalp\') was approved by Kartikeyan and marked as Done.', 'task-details?id=55', NULL, 0, '2026-10-22 16:54:37'),
(276, 10, 'task', 'Task Approved & Marked Done!', 'Your completed task #71 (\'kashmiri sons blogs on Uttarakhand Stays\') was approved by Kartikeyan and marked as Done.', 'task-details?id=71', NULL, 0, '2026-10-22 16:54:56'),
(277, 10, 'task', 'Task Approved & Marked Done!', 'Your completed task #70 (\'Kashmiri sons 5 Blogs Uttarakhand wedding\') was approved by Kartikeyan and marked as Done.', 'task-details?id=70', NULL, 0, '2026-10-22 16:55:03'),
(278, 6, 'task', 'New Task Assigned', 'You were assigned task: \'Liquor fort 50 backlinks\' by Uvaish', 'task-details?id=86', NULL, 0, '2026-10-22 18:07:17'),
(279, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #72 (\'Kashmiri Sons blogs on Dehradungram\') and submitted it for your approval.', 'task-details?id=72', NULL, 0, '2026-10-22 18:18:51'),
(280, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #72 (\'Kashmiri Sons blogs on Dehradungram\') and submitted it for your approval.', 'task-details?id=72', NULL, 0, '2026-10-22 18:18:51'),
(281, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #72 (\'Kashmiri Sons blogs on Dehradungram\') and submitted it for your approval.', 'task-details?id=72', NULL, 0, '2026-10-22 18:18:51'),
(282, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #72 (\'Kashmiri Sons blogs on Dehradungram\') and submitted it for your approval.', 'task-details?id=72', NULL, 0, '2026-10-22 18:18:51'),
(283, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #72 (\'Kashmiri Sons blogs on Dehradungram\') and submitted it for your approval.', 'task-details?id=72', NULL, 0, '2026-10-22 18:18:51'),
(284, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\') and submitted it for your approval.', 'task-details?id=29', NULL, 0, '2026-10-23 10:21:14'),
(285, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\') and submitted it for your approval.', 'task-details?id=29', NULL, 0, '2026-10-23 10:21:14'),
(286, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\') and submitted it for your approval.', 'task-details?id=29', NULL, 0, '2026-10-23 10:21:14'),
(287, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\') and submitted it for your approval.', 'task-details?id=29', NULL, 0, '2026-10-23 10:21:14'),
(288, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\') and submitted it for your approval.', 'task-details?id=29', NULL, 0, '2026-10-23 10:21:14'),
(289, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #64 (\'Standard Social Media\') and submitted it for your approval.', 'task-details?id=64', NULL, 0, '2026-10-23 10:21:26'),
(290, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #64 (\'Standard Social Media\') and submitted it for your approval.', 'task-details?id=64', NULL, 0, '2026-10-23 10:21:26'),
(291, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #64 (\'Standard Social Media\') and submitted it for your approval.', 'task-details?id=64', NULL, 0, '2026-10-23 10:21:26'),
(292, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #64 (\'Standard Social Media\') and submitted it for your approval.', 'task-details?id=64', NULL, 0, '2026-10-23 10:21:26'),
(293, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #82 (\'Turmeric victoria\') and submitted it for your approval.', 'task-details?id=82', NULL, 0, '2026-10-23 10:25:08'),
(294, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #82 (\'Turmeric victoria\') and submitted it for your approval.', 'task-details?id=82', NULL, 0, '2026-10-23 10:25:08'),
(295, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #82 (\'Turmeric victoria\') and submitted it for your approval.', 'task-details?id=82', NULL, 0, '2026-10-23 10:25:08'),
(296, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #82 (\'Turmeric victoria\') and submitted it for your approval.', 'task-details?id=82', NULL, 0, '2026-10-23 10:25:08'),
(297, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #82 (\'Turmeric victoria\') and submitted it for your approval.', 'task-details?id=82', NULL, 0, '2026-10-23 10:25:08'),
(298, 4, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #82 (\'Turmeric victoria\') and submitted it for your approval.', 'task-details?id=82', NULL, 0, '2026-10-23 10:25:08'),
(299, 9, 'task', 'Task Approved & Marked Done!', 'Your completed task #82 (\'Turmeric victoria\') was approved by Kartikeyan and marked as Done.', 'task-details?id=82', NULL, 0, '2026-10-23 10:59:17'),
(300, 8, 'task', 'Task Approved & Marked Done!', 'Your completed task #64 (\'Standard Social Media\') was approved by Kartikeyan and marked as Done.', 'task-details?id=64', NULL, 0, '2026-10-23 10:59:36'),
(301, 8, 'task', 'Task Approved & Marked Done!', 'Your completed task #29 (\'Upload New Photoshoot on MMT for MJ Aerosuites\') was approved by Kartikeyan and marked as Done.', 'task-details?id=29', NULL, 0, '2026-10-23 10:59:49'),
(302, 10, 'task', 'Task Approved & Marked Done!', 'Your completed task #72 (\'Kashmiri Sons blogs on Dehradungram\') was approved by Kartikeyan and marked as Done.', 'task-details?id=72', NULL, 0, '2026-10-23 10:59:57'),
(303, 8, 'task', 'New Task Assigned', 'You were assigned task: \'List Little Lane Cottage on Booking.com\' by Kartikeyan', 'task-details?id=87', NULL, 0, '2026-10-23 11:01:20'),
(304, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Check content of new Sheina Mill website\' by Kartikeyan', 'task-details?id=88', NULL, 0, '2026-10-23 11:02:53'),
(305, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Make review QR code for Almost Heaven Mussoorie\' by Kartikeyan', 'task-details?id=89', NULL, 0, '2026-10-23 11:18:32'),
(306, 5, 'task', 'New Task Assigned', 'You were assigned task: \'Remake Turmeric Demo Table Booking Screen Recording in english.\' by Kartikeyan', 'task-details?id=90', NULL, 0, '2026-10-23 11:47:05'),
(307, 14, 'task', 'New Task Assigned', 'You were assigned task: \'Launch Ads on all the latest reels and post of Renee Mill . She has connected the Card for Ads .\' by Kartikeyan', 'task-details?id=91', NULL, 0, '2026-10-23 13:20:49'),
(308, 14, 'task', 'New Task Assigned', 'You were assigned task: \'Add Krafting Digital GST in all the ads account possible (meta and google both )\' by Kartikeyan', 'task-details?id=92', NULL, 0, '2026-10-23 13:24:22'),
(309, 2, 'emergency_request', '🚨 Emergency Request: CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE', 'Kartikeyan submitted an urgent request: \'Urgent client deadline\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-23 13:28:56'),
(310, 3, 'emergency_request', '🚨 Emergency Request: CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE', 'Kartikeyan submitted an urgent request: \'Urgent client deadline\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-23 13:28:56'),
(311, 5, 'emergency_request', '🚨 Emergency Request: CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE', 'Kartikeyan submitted an urgent request: \'Urgent client deadline\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-23 13:28:56'),
(312, 8, 'emergency_request', '🚨 Emergency Request: CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE', 'Kartikeyan submitted an urgent request: \'Urgent client deadline\'. Action required.', 'emergency-approvals', NULL, 0, '2026-10-23 13:28:56'),
(313, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Udit has completed task #40 (\'Make New Taj reels from new photoshoots\') and submitted it for your approval.', 'task-details?id=40', NULL, 0, '2026-10-23 15:31:47'),
(314, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Udit has completed task #40 (\'Make New Taj reels from new photoshoots\') and submitted it for your approval.', 'task-details?id=40', NULL, 0, '2026-10-23 15:31:47'),
(315, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Udit has completed task #40 (\'Make New Taj reels from new photoshoots\') and submitted it for your approval.', 'task-details?id=40', NULL, 0, '2026-10-23 15:31:47'),
(316, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Udit has completed task #40 (\'Make New Taj reels from new photoshoots\') and submitted it for your approval.', 'task-details?id=40', NULL, 0, '2026-10-23 15:31:47'),
(317, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Udit has completed task #40 (\'Make New Taj reels from new photoshoots\') and submitted it for your approval.', 'task-details?id=40', NULL, 0, '2026-10-23 15:31:47'),
(318, 3, 'task', 'Task Approved & Marked Done!', 'Your completed task #40 (\'Make New Taj reels from new photoshoots\') was approved by Udit and marked as Done.', 'task-details?id=40', NULL, 0, '2026-10-23 15:32:00'),
(319, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #73 (\'Jeevan sankalp blogs on Dehradun gurugram\') and submitted it for your approval.', 'task-details?id=73', NULL, 0, '2026-10-24 09:44:52'),
(320, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #73 (\'Jeevan sankalp blogs on Dehradun gurugram\') and submitted it for your approval.', 'task-details?id=73', NULL, 0, '2026-10-24 09:44:52'),
(321, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #73 (\'Jeevan sankalp blogs on Dehradun gurugram\') and submitted it for your approval.', 'task-details?id=73', NULL, 0, '2026-10-24 09:44:52'),
(322, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #73 (\'Jeevan sankalp blogs on Dehradun gurugram\') and submitted it for your approval.', 'task-details?id=73', NULL, 0, '2026-10-24 09:44:52'),
(323, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #73 (\'Jeevan sankalp blogs on Dehradun gurugram\') and submitted it for your approval.', 'task-details?id=73', NULL, 0, '2026-10-24 09:44:52'),
(324, 10, 'task', 'Task Approved & Marked Done!', 'Your completed task #73 (\'Jeevan sankalp blogs on Dehradun gurugram\') was approved by Kartikeyan and marked as Done.', 'task-details?id=73', NULL, 0, '2026-10-24 17:12:20'),
(325, 1, 'leave', 'New Leave Application', 'Rahul requested 1 days leave (1 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-10-25 12:00:31'),
(326, 2, 'leave', 'New Leave Application', 'Rahul requested 1 days leave (1 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-10-25 12:00:31'),
(327, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #87 (\'List Little Lane Cottage on Booking.com\') and submitted it for your approval.', 'task-details?id=87', NULL, 0, '2026-10-26 10:30:58'),
(328, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #87 (\'List Little Lane Cottage on Booking.com\') and submitted it for your approval.', 'task-details?id=87', NULL, 0, '2026-10-26 10:30:58'),
(329, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #87 (\'List Little Lane Cottage on Booking.com\') and submitted it for your approval.', 'task-details?id=87', NULL, 0, '2026-10-26 10:30:58'),
(330, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #87 (\'List Little Lane Cottage on Booking.com\') and submitted it for your approval.', 'task-details?id=87', NULL, 0, '2026-10-26 10:30:58'),
(331, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Priyansh has completed task #87 (\'List Little Lane Cottage on Booking.com\') and submitted it for your approval.', 'task-details?id=87', NULL, 0, '2026-10-26 10:30:58'),
(332, 8, 'task', 'Task Approved & Marked Done!', 'Your completed task #87 (\'List Little Lane Cottage on Booking.com\') was approved by Kartikeyan and marked as Done.', 'task-details?id=87', NULL, 0, '2026-10-26 12:02:12'),
(333, 1, 'emergency_approved', '✅ Emergency Request Approved', 'Your emergency request for \'CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE\' was APPROVED by Uvaish.', 'task-details?id=62', NULL, 0, '2026-10-26 13:45:01'),
(334, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #65 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=65', NULL, 0, '2026-10-26 13:49:44'),
(335, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #65 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=65', NULL, 0, '2026-10-26 13:49:44'),
(336, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #65 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=65', NULL, 0, '2026-10-26 13:49:44'),
(337, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #65 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=65', NULL, 0, '2026-10-26 13:49:44'),
(338, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #65 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=65', NULL, 0, '2026-10-26 13:49:44'),
(339, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #65 (\'Make 100 backlinks for jeeven sankalp\') and submitted it for your approval.', 'task-details?id=65', NULL, 0, '2026-10-26 13:49:44'),
(340, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #86 (\'Liquor fort 50 backlinks\') and submitted it for your approval.', 'task-details?id=86', NULL, 0, '2026-10-26 15:33:03'),
(341, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #86 (\'Liquor fort 50 backlinks\') and submitted it for your approval.', 'task-details?id=86', NULL, 0, '2026-10-26 15:33:03'),
(342, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #86 (\'Liquor fort 50 backlinks\') and submitted it for your approval.', 'task-details?id=86', NULL, 0, '2026-10-26 15:33:03'),
(343, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #86 (\'Liquor fort 50 backlinks\') and submitted it for your approval.', 'task-details?id=86', NULL, 0, '2026-10-26 15:33:03'),
(344, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #86 (\'Liquor fort 50 backlinks\') and submitted it for your approval.', 'task-details?id=86', NULL, 0, '2026-10-26 15:33:03'),
(345, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Shivanshu has completed task #86 (\'Liquor fort 50 backlinks\') and submitted it for your approval.', 'task-details?id=86', NULL, 0, '2026-10-26 15:33:03'),
(346, 6, 'task', 'Task Approved & Marked Done!', 'Your completed task #86 (\'Liquor fort 50 backlinks\') was approved by Uvaish and marked as Done.', 'task-details?id=86', NULL, 0, '2026-10-26 15:50:58'),
(347, 4, 'leave', 'Leave Request Approved', 'Your leave request has been approved!', 'leaves', NULL, 0, '2026-10-27 16:39:32'),
(348, 1, 'leave', 'New Leave Application', 'Aman requested 1 days leave (1 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-10-28 10:04:49'),
(349, 2, 'leave', 'New Leave Application', 'Aman requested 1 days leave (1 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-10-28 10:04:49'),
(350, 6, 'task', 'Task Approved & Marked Done!', 'Your completed task #65 (\'Make 100 backlinks for jeeven sankalp\') was approved by Uvaish and marked as Done.', 'task-details?id=65', NULL, 0, '2026-10-28 15:20:03'),
(351, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') and submitted it for your approval.', 'task-details?id=74', NULL, 0, '2026-10-29 10:43:06'),
(352, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') and submitted it for your approval.', 'task-details?id=74', NULL, 0, '2026-10-29 10:43:06'),
(353, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') and submitted it for your approval.', 'task-details?id=74', NULL, 0, '2026-10-29 10:43:06'),
(354, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') and submitted it for your approval.', 'task-details?id=74', NULL, 0, '2026-10-29 10:43:06'),
(355, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') and submitted it for your approval.', 'task-details?id=74', NULL, 0, '2026-10-29 10:43:06'),
(356, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #74 (\'Jeevan sankalp blogs on Uttarakhand Diaries\') and submitted it for your approval.', 'task-details?id=74', NULL, 0, '2026-10-29 10:43:06'),
(357, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #75 (\'Frontier Jewellers blogs on Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=75', NULL, 0, '2026-10-30 11:53:52'),
(358, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #75 (\'Frontier Jewellers blogs on Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=75', NULL, 0, '2026-10-30 11:53:52'),
(359, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #75 (\'Frontier Jewellers blogs on Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=75', NULL, 0, '2026-10-30 11:53:52'),
(360, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #75 (\'Frontier Jewellers blogs on Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=75', NULL, 0, '2026-10-30 11:53:52'),
(361, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #75 (\'Frontier Jewellers blogs on Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=75', NULL, 0, '2026-10-30 11:53:52'),
(362, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #75 (\'Frontier Jewellers blogs on Uttarakhand wedding\') and submitted it for your approval.', 'task-details?id=75', NULL, 0, '2026-10-30 11:53:52'),
(363, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #76 (\'Frontier Jewellers blogs on Dehradun Gurugram\') and submitted it for your approval.', 'task-details?id=76', NULL, 0, '2026-10-30 13:05:24'),
(364, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #76 (\'Frontier Jewellers blogs on Dehradun Gurugram\') and submitted it for your approval.', 'task-details?id=76', NULL, 0, '2026-10-30 13:05:24'),
(365, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #76 (\'Frontier Jewellers blogs on Dehradun Gurugram\') and submitted it for your approval.', 'task-details?id=76', NULL, 0, '2026-10-30 13:05:24'),
(366, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #76 (\'Frontier Jewellers blogs on Dehradun Gurugram\') and submitted it for your approval.', 'task-details?id=76', NULL, 0, '2026-10-30 13:05:24'),
(367, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #76 (\'Frontier Jewellers blogs on Dehradun Gurugram\') and submitted it for your approval.', 'task-details?id=76', NULL, 0, '2026-10-30 13:05:24'),
(368, 1, 'leave', 'New Leave Application', 'Abhay requested 1 days leave (1 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-10-31 13:58:49'),
(369, 2, 'leave', 'New Leave Application', 'Abhay requested 1 days leave (1 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-10-31 13:58:49'),
(370, 9, 'task', 'New Task Assigned', 'You were assigned task: \'Uttarakhandstays\' by Rahul', 'task-details?id=93', NULL, 0, '2026-11-03 11:34:21'),
(371, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #77 (\'Frontier Jewellers blogs on Uttarakhan Diaries\') and submitted it for your approval.', 'task-details?id=77', NULL, 0, '2026-11-03 13:32:41'),
(372, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #77 (\'Frontier Jewellers blogs on Uttarakhan Diaries\') and submitted it for your approval.', 'task-details?id=77', NULL, 0, '2026-11-03 13:32:41'),
(373, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #77 (\'Frontier Jewellers blogs on Uttarakhan Diaries\') and submitted it for your approval.', 'task-details?id=77', NULL, 0, '2026-11-03 13:32:41'),
(374, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #77 (\'Frontier Jewellers blogs on Uttarakhan Diaries\') and submitted it for your approval.', 'task-details?id=77', NULL, 0, '2026-11-03 13:32:41'),
(375, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Aman has completed task #77 (\'Frontier Jewellers blogs on Uttarakhan Diaries\') and submitted it for your approval.', 'task-details?id=77', NULL, 0, '2026-11-03 13:32:41'),
(376, 1, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #93 (\'Uttarakhandstays\') and submitted it for your approval.', 'task-details?id=93', NULL, 0, '2026-11-04 17:52:08'),
(377, 2, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #93 (\'Uttarakhandstays\') and submitted it for your approval.', 'task-details?id=93', NULL, 0, '2026-11-04 17:52:08'),
(378, 3, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #93 (\'Uttarakhandstays\') and submitted it for your approval.', 'task-details?id=93', NULL, 0, '2026-11-04 17:52:08'),
(379, 5, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #93 (\'Uttarakhandstays\') and submitted it for your approval.', 'task-details?id=93', NULL, 0, '2026-11-04 17:52:08'),
(380, 8, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #93 (\'Uttarakhandstays\') and submitted it for your approval.', 'task-details?id=93', NULL, 0, '2026-11-04 17:52:08'),
(381, 4, 'task_approval', 'Task Completion Approval Request', 'Employee Abhay has completed task #93 (\'Uttarakhandstays\') and submitted it for your approval.', 'task-details?id=93', NULL, 0, '2026-11-04 17:52:08'),
(382, 9, 'task', 'Task Approved & Marked Done!', 'Your completed task #93 (\'Uttarakhandstays\') was approved by Rahul and marked as Done.', 'task-details?id=93', NULL, 0, '2026-11-05 16:26:58'),
(383, 1, 'leave', 'New Leave Application', 'Abhay requested 1 days leave (1 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-11-13 10:35:07'),
(384, 2, 'leave', 'New Leave Application', 'Abhay requested 1 days leave (1 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-11-13 10:35:07'),
(385, 1, 'leave', 'New Leave Application', 'Abhay requested 2 days leave (2 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-11-13 10:37:02'),
(386, 2, 'leave', 'New Leave Application', 'Abhay requested 2 days leave (2 Paid, 0 Unpaid).', 'leaves', NULL, 0, '2026-11-13 10:37:02'),
(387, 9, 'leave', 'Leave Request Approved', 'Your leave request has been approved!', 'leaves', NULL, 0, '2026-11-14 10:29:18'),
(388, 9, 'leave', 'Leave Request Approved', 'Your leave request has been approved!', 'leaves', NULL, 0, '2026-11-14 10:29:33');

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
(21, 1, '2026-09', 50000.00, 0.00, 0.00, 1666.67, 0.0, 1.0, 29.5, 0.0, 0.0, 0, 0.01, 48333.33, 'pending', NULL, NULL, 30, 833.33, '2026-09-01 10:17:37', '2026-10-15 12:11:40'),
(22, 2, '2026-09', 25000.00, 0.00, 0.00, 833.33, 0.0, 1.0, 29.5, 0.0, 0.0, 0, 2.11, 24166.67, 'pending', NULL, NULL, 30, 416.67, '2026-09-01 10:17:38', '2026-10-15 12:11:40'),
(23, 3, '2026-09', 20000.00, 0.00, 0.00, 666.67, 0.0, 0.0, 30.0, 0.0, 0.0, 0, 0.00, 19333.33, 'pending', NULL, NULL, 30, 0.00, '2026-09-01 10:17:38', '2026-10-15 12:11:41'),
(24, 4, '2026-09', 30000.00, 0.00, 0.00, 6000.00, 3.0, 4.0, 25.0, 0.0, 0.0, 0, 36.69, 24000.00, 'pending', NULL, NULL, 30, 5000.00, '2026-09-01 10:17:39', '2026-10-15 12:11:42'),
(25, 5, '2026-09', 25000.00, 0.00, 0.00, 833.33, 0.0, 0.0, 30.0, 0.0, 0.0, 0, 0.00, 24166.67, 'pending', NULL, NULL, 30, 0.00, '2026-09-01 10:17:39', '2026-10-15 12:11:42'),
(26, 6, '2026-09', 10000.00, 0.00, 0.00, 333.33, 22.0, 3.0, 6.5, 0.0, 0.0, 0, 219.75, 9666.67, 'pending', NULL, NULL, 30, 7833.33, '2026-09-01 10:17:40', '2026-10-15 12:11:43'),
(27, 7, '2026-09', 10000.00, 0.00, 0.00, 333.33, 14.0, 2.0, 15.0, 0.0, 0.0, 0, 144.66, 9666.67, 'pending', NULL, NULL, 30, 5000.00, '2026-09-01 10:17:41', '2026-10-15 12:11:43'),
(28, 8, '2026-09', 15000.00, 0.00, 0.00, 500.00, 16.0, 4.0, 12.0, 0.0, 0.0, 0, 177.18, 14500.00, 'pending', NULL, NULL, 30, 9000.00, '2026-09-01 10:17:41', '2026-10-15 12:11:44'),
(29, 9, '2026-09', 5000.00, 0.00, 0.00, 166.67, 18.0, 6.0, 9.0, 0.0, 0.0, 0, 216.29, 4833.33, 'pending', NULL, NULL, 30, 3500.00, '2026-09-01 10:17:42', '2026-10-15 12:11:45'),
(30, 10, '2026-09', 7000.00, 0.00, 0.00, 233.33, 2.0, 1.0, 27.5, 0.0, 0.0, 0, 26.43, 6766.67, 'pending', NULL, NULL, 30, 583.33, '2026-09-01 10:17:42', '2026-10-15 12:11:46'),
(31, 1, '2026-10', 50000.00, 0.00, 0.00, 12903.23, 0.0, 4.0, 24.0, 0.0, 0.0, 1, 23.86, 0.00, 'pending', NULL, NULL, 27, 4838.71, '2026-10-09 10:07:24', '2026-10-27 16:29:44'),
(32, 2, '2026-10', 25000.00, 0.00, 0.00, 6451.61, 2.0, 3.0, 22.5, 0.0, 0.0, 1, 42.33, 0.00, 'pending', NULL, NULL, 27, 3629.03, '2026-10-09 10:07:24', '2026-10-27 16:29:45'),
(33, 3, '2026-10', 20000.00, 0.00, 0.00, 5161.29, 0.0, 8.0, 22.0, 0.0, 0.0, 1, 55.76, 0.00, 'pending', NULL, NULL, 27, 3225.81, '2026-10-09 10:07:25', '2026-10-27 16:29:45'),
(34, 4, '2026-10', 30000.00, 0.00, 0.00, 4838.71, 15.0, 1.0, 10.5, 0.0, 0.0, 1, 147.94, 11129.03, 'pending', NULL, NULL, 27, 15967.74, '2026-10-09 10:07:26', '2026-10-27 16:29:46'),
(35, 5, '2026-10', 25000.00, 0.00, 0.00, 6451.61, 8.0, 4.0, 16.0, 0.0, 0.0, 1, 83.19, 2419.36, 'pending', NULL, NULL, 27, 8870.97, '2026-10-09 10:07:26', '2026-10-27 16:29:46'),
(36, 6, '2026-10', 10000.00, 0.00, 0.00, 645.16, 14.0, 4.0, 10.0, 0.0, 0.0, 1, 162.60, 4838.71, 'pending', NULL, NULL, 27, 5483.87, '2026-10-09 10:07:27', '2026-10-27 16:29:47'),
(37, 7, '2026-10', 10000.00, 0.00, 0.00, 967.74, 16.0, 3.0, 8.5, 0.0, 0.0, 1, 173.77, 5000.00, 'pending', NULL, NULL, 27, 5967.74, '2026-10-09 10:07:27', '2026-10-27 16:29:48'),
(38, 8, '2026-10', 15000.00, 0.00, 0.00, 1451.61, 12.0, 6.0, 11.0, 0.0, 0.0, 1, 157.98, 6290.33, 'pending', NULL, NULL, 27, 7741.94, '2026-10-09 10:07:28', '2026-10-27 16:29:49'),
(39, 9, '2026-10', 5000.00, 0.00, 0.00, 322.58, 20.0, 0.0, 6.0, 0.0, 0.0, 1, 184.50, 3064.52, 'pending', NULL, NULL, 27, 3387.10, '2026-10-09 10:07:28', '2026-10-27 16:29:49'),
(40, 10, '2026-10', 7000.00, 0.00, 0.00, 677.42, 16.0, 3.0, 8.5, 0.0, 0.0, 1, 173.58, 3500.00, 'pending', NULL, NULL, 27, 4177.42, '2026-10-09 10:07:29', '2026-10-27 16:29:50'),
(41, 12, '2026-10', 20000.00, 0.00, 0.00, 2580.65, 13.0, 5.0, 10.5, 0.0, 0.0, 1, 159.60, 8064.51, 'pending', NULL, NULL, 27, 10645.16, '2026-10-09 10:07:30', '2026-10-27 16:29:51'),
(42, 13, '2026-10', 20000.00, 0.00, 0.00, 9032.26, 0.0, 0.0, 26.0, 0.0, 0.0, 1, 0.00, 0.00, 'pending', NULL, NULL, 27, 645.16, '2026-10-15 12:11:34', '2026-10-27 16:29:48'),
(43, 13, '2026-09', 20000.00, 0.00, 0.00, 20000.00, 0.0, 0.0, 30.0, 0.0, 0.0, 0, 0.00, 0.00, 'pending', NULL, NULL, 30, 0.00, '2026-10-15 12:11:44', '2026-10-15 12:11:44'),
(44, 12, '2026-09', 20000.00, 0.00, 0.00, 19333.33, 1.0, 1.0, 28.5, 0.0, 0.0, 0, 18.00, 666.67, 'pending', NULL, NULL, 30, 1000.00, '2026-10-15 12:11:46', '2026-10-15 12:11:46'),
(45, 14, '2026-10', 25000.00, 0.00, 0.00, 11290.32, 1.0, 0.0, 25.0, 0.0, 0.0, 1, 9.13, 0.00, 'pending', NULL, NULL, 27, 1612.90, '2026-10-15 15:18:50', '2026-10-27 16:29:51');

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
(6, NULL, 1, 'Krafting Digital', 'PRJ-KD-CORE', 'Agency internal operations &amp; digital billing platform', 'high', 'in_progress', '2026-09-25', '2026-12-31', '0000-00-00', 0, NULL, NULL, '2026-09-25 12:50:16', '2026-10-14 13:41:32'),
(7, NULL, NULL, 'Casa Tupka', 'PRJ-CT-02', 'Resort website redesign & booking engine optimization', 'medium', 'in_progress', '2026-09-25', '2026-11-20', NULL, NULL, NULL, NULL, '2026-09-25 12:50:16', '2026-09-25 12:50:16'),
(8, NULL, 1, 'Turmeric SEO and Web development', 'PRJ-243', '', 'medium', 'in_progress', '2026-10-13', NULL, '2026-11-12', 0, 0.00, 1, '2026-10-13 15:08:28', '2026-10-13 15:08:28'),
(9, NULL, 7, 'MJ Hotels', 'PRJ-MJH-304', '', 'high', 'in_progress', '2026-10-15', NULL, '2026-12-31', 0, 0.00, 1, '2026-10-14 13:19:27', '2026-10-14 13:51:20'),
(10, NULL, 7, 'Almost Heaven Mussoorie', 'PRJ-ALM-899', 'If there are any confusion talk to me or flag the task .', 'medium', 'planning', '2026-10-14', NULL, '2026-10-18', 0, 0.00, 1, '2026-10-14 13:34:46', '2026-10-14 13:34:46'),
(11, NULL, 7, 'Uttrakhand Stays Airbnb', 'PRJ-UTT-128', '', 'medium', 'in_progress', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 13:38:23', '2026-10-14 13:38:23'),
(12, NULL, 7, 'Almost Heaven Rishikesh', 'PRJ-ALM-295', 'All Task Related to Almost Heaven Rishikesh', 'medium', 'in_progress', '2026-10-14', NULL, '2026-12-31', 0, 0.00, 1, '2026-10-14 13:50:47', '2026-10-14 13:50:47'),
(13, NULL, 3, 'Masterpiece Psycology', 'PRJ-MAS-132', '', 'high', 'in_progress', '2026-10-14', NULL, '2026-12-31', 0, 0.00, 1, '2026-10-14 13:55:12', '2026-10-14 13:55:12'),
(14, NULL, 8, 'Taj Mussoorie Foothills', 'PRJ-TAJM-56', '', 'medium', 'in_progress', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 14:01:49', '2026-10-14 14:01:49'),
(15, NULL, 1, 'Renee Mill Psycology', 'PRJ-RENE-15', 'All task related to renee mill', 'medium', 'in_progress', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 14:06:00', '2026-10-14 14:06:00'),
(16, NULL, 1, 'Black Apple Cellular', 'PRJ-BLAC-29', 'All Tasks related to black apple cellular', 'medium', 'active', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 14:10:15', '2026-10-14 14:10:15'),
(17, NULL, 3, 'MJ Catering', 'PRJ-MJCA-89', '', 'medium', 'active', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 14:22:19', '2026-10-14 14:22:19'),
(18, NULL, 3, 'Jeevan Sankalp', 'PRJ-JEEV-53', '', 'medium', 'active', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 14:23:02', '2026-10-14 14:23:02'),
(19, NULL, 3, 'Liquor Fort', 'PRJ-LIQU-72', '', 'medium', 'active', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 14:31:39', '2026-10-14 14:31:39'),
(20, NULL, 3, 'DR SHIVAM DANG', 'PRJ-DRSH-49', '', 'medium', 'in_progress', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 14:42:16', '2026-10-14 14:42:16'),
(21, NULL, 5, 'STANDARD BAKERY', 'PRJ-STAN-60', '', 'medium', 'in_progress', '2026-10-14', NULL, '2026-11-13', 0, 0.00, 1, '2026-10-14 14:49:22', '2026-10-14 14:49:22'),
(22, NULL, 2, 'Turmeric victoria', 'PRJ-DEV-243', '', 'medium', 'in_progress', '2026-10-21', NULL, '2026-11-24', 0, 0.00, 1, '2026-10-21 12:22:54', '2026-10-21 12:22:54'),
(23, NULL, 3, 'Sheina Mill', 'PRJ-SHEI-68', '', 'medium', 'in_progress', '2026-10-23', NULL, '2026-11-22', 0, 0.00, 1, '2026-10-23 11:02:37', '2026-10-23 11:02:37');

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
(11, 4, 7, 'Visual Designer', '2026-08-19 07:09:49'),
(12, 8, 1, 'Owner', '2026-10-13 15:08:28'),
(13, 9, 1, 'Project Lead', '2026-10-14 13:19:27'),
(14, 9, 8, 'Team Member', '2026-10-14 13:19:27'),
(15, 10, 8, 'Team Member', '2026-10-14 13:34:46'),
(16, 10, 1, 'Project Lead', '2026-10-14 13:34:46'),
(17, 11, 1, 'Project Lead', '2026-10-14 13:38:23'),
(18, 11, 4, 'Team Member', '2026-10-14 13:38:23'),
(19, 11, 6, 'Team Member', '2026-10-14 13:38:23'),
(20, 11, 5, 'Team Member', '2026-10-14 13:38:23'),
(21, 11, 8, 'Team Member', '2026-10-14 13:38:23'),
(22, 11, 3, 'Team Member', '2026-10-14 13:38:23'),
(23, 6, 2, 'Team Member', '2026-10-14 13:41:32'),
(24, 6, 1, 'Team Member', '2026-10-14 13:41:32'),
(25, 6, 9, 'Team Member', '2026-10-14 13:41:32'),
(26, 6, 4, 'Team Member', '2026-10-14 13:41:32'),
(27, 6, 10, 'Team Member', '2026-10-14 13:41:32'),
(28, 6, 12, 'Team Member', '2026-10-14 13:41:32'),
(29, 6, 6, 'Team Member', '2026-10-14 13:41:32'),
(30, 6, 5, 'Team Member', '2026-10-14 13:41:32'),
(31, 6, 7, 'Team Member', '2026-10-14 13:41:32'),
(32, 6, 8, 'Team Member', '2026-10-14 13:41:32'),
(33, 6, 3, 'Team Member', '2026-10-14 13:41:32'),
(34, 12, 2, 'Team Member', '2026-10-14 13:50:47'),
(35, 12, 1, 'Project Lead', '2026-10-14 13:50:47'),
(36, 12, 9, 'Team Member', '2026-10-14 13:50:47'),
(37, 12, 4, 'Team Member', '2026-10-14 13:50:47'),
(38, 12, 5, 'Team Member', '2026-10-14 13:50:47'),
(39, 12, 8, 'Team Member', '2026-10-14 13:50:47'),
(40, 9, 2, 'Team Member', '2026-10-14 13:51:20'),
(41, 9, 9, 'Team Member', '2026-10-14 13:51:20'),
(42, 9, 4, 'Team Member', '2026-10-14 13:51:20'),
(43, 9, 10, 'Team Member', '2026-10-14 13:51:20'),
(44, 9, 12, 'Team Member', '2026-10-14 13:51:20'),
(45, 9, 6, 'Team Member', '2026-10-14 13:51:20'),
(46, 9, 5, 'Team Member', '2026-10-14 13:51:20'),
(47, 9, 7, 'Team Member', '2026-10-14 13:51:20'),
(48, 9, 3, 'Team Member', '2026-10-14 13:51:20'),
(49, 13, 2, 'Team Member', '2026-10-14 13:55:12'),
(50, 13, 1, 'Project Lead', '2026-10-14 13:55:12'),
(51, 13, 9, 'Team Member', '2026-10-14 13:55:12'),
(52, 13, 4, 'Team Member', '2026-10-14 13:55:12'),
(53, 13, 10, 'Team Member', '2026-10-14 13:55:12'),
(54, 13, 12, 'Team Member', '2026-10-14 13:55:12'),
(55, 13, 6, 'Team Member', '2026-10-14 13:55:12'),
(56, 13, 5, 'Team Member', '2026-10-14 13:55:12'),
(57, 13, 7, 'Team Member', '2026-10-14 13:55:12'),
(58, 14, 1, 'Project Lead', '2026-10-14 14:01:49'),
(59, 15, 1, 'Project Lead', '2026-10-14 14:06:00'),
(60, 16, 1, 'Project Lead', '2026-10-14 14:10:15'),
(61, 17, 1, 'Project Lead', '2026-10-14 14:22:19'),
(62, 18, 1, 'Project Lead', '2026-10-14 14:23:02'),
(63, 19, 1, 'Project Lead', '2026-10-14 14:31:39'),
(64, 20, 1, 'Project Lead', '2026-10-14 14:42:16'),
(65, 21, 1, 'Project Lead', '2026-10-14 14:49:22'),
(66, 22, 9, 'Team Member', '2026-10-21 12:22:54'),
(67, 22, 4, 'Team Member', '2026-10-21 12:22:54'),
(68, 22, 1, 'Project Lead', '2026-10-21 12:22:54'),
(69, 23, 1, 'Project Lead', '2026-10-23 11:02:37');

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
(11, 'ads_expert', 'ADS EXPERT', 'Meta Ads & Google Ads Campaign Specialist', '2026-10-15 13:17:12');

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
  `submitted_for_approval_at` datetime DEFAULT NULL,
  `completion_notes` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `is_archived` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `department_id`, `assigned_to_employee_id`, `assigned_to`, `assigned_by`, `title`, `description`, `priority`, `status`, `rejection_reason`, `estimated_hours`, `spent_seconds`, `deadline`, `due_date`, `completed_at`, `submitted_for_approval_at`, `completion_notes`, `approved_by`, `approved_at`, `is_archived`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 4, 4, 1, 'Implement Glassmorphism Admin Dashboard UI', 'Design responsive Bootstrap 5 cards, sidebar, and navbar with glass dynamic effects.', 'urgent', 'completed', NULL, 8.00, 14400, '2026-08-10 18:00:00', '2026-08-10', NULL, NULL, NULL, NULL, NULL, 0, '2026-08-19 07:09:49', '2026-09-25 16:54:42'),
(7, 6, 5, 8, 8, 2, 'Schedule Weekly LinkedIn &amp; Instagram Posts', 'Create engaging carousels and write copy for Q3 feature highlights.', 'medium', 'running', NULL, 4.00, 10800, '2026-10-31 15:00:00', '2026-08-11', NULL, NULL, NULL, NULL, NULL, 0, '2026-08-19 07:09:49', '2026-10-14 15:09:30'),
(10, NULL, 1, 9, 9, 2, 'Arrange All the Photos Property Vice', '', 'urgent', 'completed', NULL, 0.00, 21, '2026-08-21 13:43:00', '2026-08-21', NULL, NULL, NULL, NULL, NULL, 0, '2026-08-24 13:48:02', '2026-08-24 17:53:43'),
(28, 5, 1, NULL, 4, 1, 'Make Website Requirement Docs and Send in Maharani Client Group', 'Make the requirement docs . Send it to the client group for approval. Check Kartikeyan sir email first that was forwarded to you by kartikeyan sir from email id dangkartikeyan@gmail.com. on 12/10/26.If needed Discuss with Kartikey sir .', 'high', 'completed', NULL, 1.00, 4787, '2026-10-15 16:44:00', NULL, '2026-10-17 12:56:15', '2026-10-17 10:03:50', 'Done', 1, '2026-10-17 12:56:15', 0, '2026-10-12 16:49:03', '2026-10-17 12:56:15'),
(29, 9, 7, 8, 8, 1, 'Upload New Photoshoot on MMT for MJ Aerosuites', 'if you have any confusion please come in my cabin  sit with me and i will guide you how to upload images on mmt . and how to set tags of all the images .', 'medium', 'completed', NULL, 1.00, 0, '2026-10-17 18:30:00', NULL, '2026-10-23 10:59:49', '2026-10-23 10:21:14', 'Work completed successfully.', 1, '2026-10-23 10:59:49', 0, '2026-10-14 13:21:19', '2026-10-23 10:59:49'),
(30, 9, 7, 1, 1, 1, 'Mj Residency Upload New Photoshoots On MMT', '', 'medium', 'completed', NULL, 2.00, 34, '2026-10-15 18:30:00', NULL, '2026-10-14 18:29:32', NULL, 'done', 1, '2026-10-14 18:29:32', 0, '2026-10-14 13:32:01', '2026-10-14 18:29:32'),
(31, 10, 7, 8, 8, 1, 'List Almost Heaven Whole Property ON MMT and airbnb', '', 'medium', 'pending', NULL, 4.00, 0, '2026-10-17 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 13:35:27', '2026-10-14 13:35:27'),
(32, 11, 5, 8, 8, 1, 'List Wildflower on Booking.com', 'call in case of any doubts . Or sit alongside me and do this task .', 'medium', 'completed', NULL, 2.00, 4197, '2026-10-17 18:30:00', NULL, '2026-10-22 16:54:01', '2026-10-22 13:15:34', 'Work completed successfully.', 1, '2026-10-22 16:54:01', 0, '2026-10-14 13:40:04', '2026-10-22 16:54:01'),
(33, 11, 7, 8, 8, 1, 'List Sunrise On Booking.com', '', 'medium', 'completed', NULL, 2.00, 2749, '2026-10-21 18:30:00', NULL, '2026-10-22 16:53:52', '2026-10-22 13:44:19', 'Work completed successfully.', 1, '2026-10-22 16:53:52', 0, '2026-10-14 13:40:38', '2026-10-22 16:53:52'),
(34, 6, 2, 4, 4, 1, 'Change Logo on Krafting digital Website', 'The new website dosent have our official logo . please fix that and replace the logo', 'medium', 'completed', NULL, 1.00, 10097, '2026-10-15 18:30:00', NULL, '2026-10-15 17:41:21', NULL, 'Updated the logo across the entire website.\n\nReplaced the old logo with the new logo in all relevant sections and pages.\n\nChecked logo alignment, sizing, and spacing to maintain a consistent design.\n\nEnsured the updated logo displays correctly across desktop, tablet, and mobile devices.\n\nVerified the overall website appearance after the logo update.', 4, '2026-10-15 17:41:21', 0, '2026-10-14 13:42:48', '2026-10-15 17:41:21'),
(35, 11, 2, 4, 4, 1, 'Make Website Live for Wildflower and Sunrise with New booking Engine', 'Cordinate with ishan of zimmerstack and do the task .', 'medium', 'pending', NULL, 1.00, 0, '2026-10-15 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 13:44:42', '2026-10-14 13:44:42'),
(36, 12, 1, 1, 1, 1, 'Triveni Ghat GMB Verification', 'Talk to gurpreet maam as there is dublicate listing error. this villa profile has been listed already by saffron . if we dont get the old id password we will have to raise this issue with google .', 'medium', 'pending', NULL, 3.00, 0, '2026-10-21 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 13:53:59', '2026-10-14 13:53:59'),
(37, 13, 3, 5, 5, 1, 'Make SEO implementation plan for Masterpiece Psycology', 'Detailed plan offpage and onpage both .', 'medium', 'completed', NULL, 3.00, 70, '2026-10-15 18:30:00', NULL, '2026-10-26 10:10:03', NULL, 'plan approved by Sir waiting for the meeting with tess', 5, '2026-10-26 10:10:03', 0, '2026-10-14 13:57:01', '2026-10-26 10:10:03'),
(38, 13, 3, 5, 5, 1, 'Send New SEO plan after KD approval to Tess via email for approval', 'Send SEO plan  only after MY approval', 'medium', 'completed', NULL, 0.50, 22, '2026-10-17 18:30:00', NULL, '2026-10-26 10:10:30', NULL, 'Done and plan approved by KD', 5, '2026-10-26 10:10:30', 0, '2026-10-14 13:59:24', '2026-10-26 10:10:30'),
(39, 14, 8, 14, 14, 1, 'Launch TAj meta ads again . Check ads and make detailed report', 'Check and involve prabhat and daman if needed', 'medium', 'running', 'have added 5000 INR in the Kotak ads account . keep the daily budget only 300 rs for the 1st few days and start the ads . i will be adding more funds to this by tomorrow or saturday .', 2.00, 0, '2026-10-17 18:30:00', NULL, NULL, '2026-10-15 17:41:18', 'Made budget report and Lead meta ad is live but need remaining budget to start other ads.', NULL, NULL, 0, '2026-10-14 14:02:32', '2026-10-15 19:13:51'),
(40, 14, 5, 3, 3, 1, 'Make New Taj reels from new photoshoots', 'Get this done Immediately', 'urgent', 'completed', NULL, 4.00, 0, '2026-10-15 18:30:00', NULL, '2026-10-23 15:32:00', '2026-10-23 15:31:47', 'DONE', 3, '2026-10-23 15:32:00', 0, '2026-10-14 14:04:36', '2026-10-23 15:32:00'),
(41, 15, 1, 2, 2, 1, 'Shedule a Meeting with Renee Mill', 'Shedule a meeting according to my time and mrinali availability.', 'medium', 'pending', NULL, 1.00, 0, '2026-10-15 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 14:06:39', '2026-10-14 14:06:39'),
(42, 8, 1, 1, 1, 1, 'Send Whatsapp Solution and Table Booking solution to Amardeep', 'Make a email with all the info and ask anjali to send it to amardeep with the costings .', 'medium', 'pending', NULL, 4.00, 0, '2026-10-17 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 14:08:05', '2026-10-14 14:08:05'),
(43, 16, 1, 2, 2, 1, 'Shedule a Meeting with Black apple Cellular with Mrinali and KD', 'Inform after sheduling', 'medium', 'pending', NULL, 4.00, 0, '2026-10-21 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 14:10:44', '2026-10-14 14:10:44'),
(44, 6, 3, 5, 5, 1, 'Make Detailed SOP for Blog Posting', 'Make a Sop that will be strictly followed by everyone in the seo team .', 'medium', 'completed', NULL, 1.50, 344, '2026-10-17 18:30:00', NULL, '2026-10-26 13:08:47', NULL, 'Done with the help of claud.ai but i will add more thing after printing in hard copy', 5, '2026-10-26 13:08:47', 0, '2026-10-14 14:12:25', '2026-10-26 13:08:47'),
(45, 5, 2, 4, 4, 1, 'Make Final Maharani Website', 'Make final Demos with everything done . Make sure there are no errors in mobile and desktop optimisation both . Please test properly before sending the link .', 'high', 'completed', NULL, 4.00, 13360, '2026-10-15 18:30:00', NULL, '2026-10-15 15:09:07', NULL, 'Tested all website sections to ensure proper functionality and layout consistency.\nChecked and corrected image alignment, sizing, spacing, and positioning across all sections.\nOptimized all website sections for mobile responsiveness.\nAdjusted padding, margins, typography, and element alignment to ensure a clean and professional mobile layout.\nVerified the website design across different mobile screen sizes to ensure a smooth and consistent user experience.\nImproved the overall UI/UX to maintain a polished and responsive design across desktop and mobile devices.', 4, '2026-10-15 15:09:07', 0, '2026-10-14 14:14:31', '2026-10-15 15:09:07'),
(46, 5, 2, 4, 4, 1, 'Make Final Menu software for Maharani', 'Make final Demos with everything done . Make sure there are no errors in mobile and desktop optimisation both . Please test properly before sending the link .', 'high', 'completed', 'Do the mentioned changes and then resend for approval . in case of any confusions call me directly .', 4.00, 9816, '2026-10-15 18:30:00', NULL, '2026-10-17 11:50:17', '2026-10-15 12:05:19', 'done', 4, '2026-10-17 11:50:17', 0, '2026-10-14 14:15:43', '2026-10-17 11:50:17'),
(47, 5, 1, 2, 2, 1, 'Send Maharani Menu and Website Bill to Ashish and Aditya and ask for atleast 50% payment', 'You can send it to ashish pundir or just send it to the client directly . client name is aditya pundir .Do whatever it takes just get the payment anyhow .', 'high', 'completed', NULL, 4.00, 0, '2026-10-17 18:30:00', NULL, '2026-10-15 13:15:20', '2026-10-15 13:15:05', 'Sent bill', 2, '2026-10-15 13:15:20', 0, '2026-10-14 14:18:07', '2026-10-15 13:15:20'),
(48, 12, 2, 1, 1, 1, 'Make Changes on Almost heaven Final website (In cordination with Gurpreet ma&#039;am)', 'Only can be done in a meeting with her .', 'medium', 'pending', NULL, 4.00, 0, '2026-10-21 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 14:19:47', '2026-10-14 14:19:47'),
(49, 16, 3, 5, 5, 1, 'Make SEO plan for Blackapple', 'Make a new plan for seo of black apple cellular and get it approved by KD', 'medium', 'completed', NULL, 4.00, 223, '2026-10-21 18:30:00', NULL, '2026-10-27 12:21:17', NULL, 'SEO plan is already applied targeting new high traffic keywords and i removed canniblaization from the whole website and then indexing again so right page will target right page', 5, '2026-10-27 12:21:17', 0, '2026-10-14 14:21:20', '2026-10-27 12:21:17'),
(50, 17, 3, 5, 5, 1, 'Make new SEO plan for Mj catering and get it approved by KD', '', 'medium', 'pending', NULL, 4.00, 0, '2026-10-21 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 14:22:29', '2026-10-14 14:22:29'),
(51, 18, 3, 5, 5, 1, 'Make Seo implementation plan for Jeevan sankalp .(local and website )', 'Get this done on urgent basis .', 'medium', 'completed', NULL, 4.00, 165, '2026-10-17 18:30:00', NULL, '2026-10-26 10:12:40', NULL, 'One plan is already applied location vise like Haridwar, Roorkee, Chandigrah and all places now i will more Optimise those pages again', 5, '2026-10-26 10:12:40', 0, '2026-10-14 14:24:30', '2026-10-26 10:12:40'),
(52, 8, 3, 5, 5, 1, 'Make new seo plan for Turmeric', 'New plan that will be followed this month ( month of oct)', 'medium', 'completed', NULL, 4.00, 4, '2026-10-21 18:30:00', NULL, '2026-10-30 12:56:39', NULL, 'Work completed successfully.', 5, '2026-10-30 12:56:39', 0, '2026-10-14 14:25:41', '2026-10-30 12:56:39'),
(53, 13, 3, 5, 5, 1, 'Implement the plan made for masterpiece psycology', 'Apply seo on the basis of the plan approved by Tess', 'medium', 'completed', NULL, 12.00, 65, '2026-10-31 18:30:00', NULL, '2026-10-26 13:50:47', NULL, 'did`nt get any approval from the client side but get approved by KD', 5, '2026-10-26 13:50:47', 0, '2026-10-14 14:27:12', '2026-10-26 13:50:47'),
(54, 18, 3, 5, 5, 1, 'Apply SEO Plan For Jeevan Sankalp', 'Apply the SEO plan made for jeevan sankalp after its approved by me.', 'medium', 'pending', NULL, 13.00, 0, '2026-10-17 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 14:28:36', '2026-10-14 14:28:36'),
(55, 18, 3, 6, 6, 1, 'Make 100 backlinks for jeeven sankalp', 'All type of backlinks bt focus on Blog posting sites more or locally relevant sites .', 'medium', 'completed', NULL, 10.00, 31434, '2026-10-25 18:30:00', NULL, '2026-10-22 16:54:37', '2026-10-22 10:55:02', 'Completed', 1, '2026-10-22 16:54:37', 0, '2026-10-14 14:30:25', '2026-10-22 16:54:37'),
(56, 19, 3, 1, 1, 1, 'Fix Liquor Fort GMB and Everything', '', 'medium', 'pending', NULL, 4.00, 0, '2026-10-17 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 14:32:04', '2026-10-14 14:32:04'),
(57, 14, 2, 4, 4, 1, 'Fix Destination Wedding Website .', 'THe website still has my personal phone number . please remove mmy number from all pages.\r\nThe single Property Page is not mobile friendly redesign the whole page . \r\nevery single property page has a form about book your stay . make that form name change to Book your wedding. and also change the form fields accordingly .', 'medium', 'completed', 'not completed', 5.00, 0, '2026-10-21 18:30:00', NULL, '2026-10-17 11:50:07', '2026-10-15 15:14:20', 'Done', 4, '2026-10-17 11:50:07', 0, '2026-10-14 14:38:39', '2026-10-17 11:50:07'),
(58, 20, 3, 5, 5, 1, 'Make SEO plan for shivam dang new website ( clinic website ) and make changes on gmb as he has asked us to', 'discuss the gmb changes with KD first', 'medium', 'completed', NULL, 4.00, 162, '2026-10-17 18:30:00', NULL, '2026-10-19 13:03:05', NULL, 'About shivam dang website plan I already had a discussion with Sir and i will apply this plan to the website then we will move forward', 5, '2026-10-19 13:03:05', 0, '2026-10-14 14:42:25', '2026-10-19 13:03:05'),
(59, 20, 3, 5, 5, 1, 'APPLY SEO PLAN FOR OCT MONTH THAT WAS APPROVED BY KD', '', 'medium', 'completed', NULL, 10.00, 96736, '2026-10-30 18:30:00', NULL, '2026-10-23 12:18:29', NULL, 'Work completed successfully.', 5, '2026-10-23 12:18:29', 0, '2026-10-14 14:43:21', '2026-10-23 12:18:29'),
(60, 20, 8, 14, 14, 1, 'MAKE ADS REPORT OF SHIVAM DANG ADS AND MAKE CORRECTION', '', 'medium', 'running', 'we already have a budget of 7000 INR from Shivam bhaiya . Please check the ads spend it it has not touched 7000 (inclusive of tax ) then you can start running the ads . In case the 7000 rs have been fully used ask anjali or mrinali to take it from shivam bhaiya and you can just start running ads . update once the ads are live .', 4.00, 0, '2026-10-17 18:30:00', NULL, NULL, '2026-10-15 17:42:25', 'Made the report. You told me to wait for budget approval', NULL, NULL, 0, '2026-10-14 14:44:19', '2026-10-15 19:12:43'),
(61, 14, 8, 14, 14, 1, 'CHECK TAJ  META ADS FOR THIS MONTH AND  MAKE CHANGES ACCORDINGLY', '', 'medium', 'completed', NULL, 4.00, 0, '2026-10-17 18:30:00', NULL, '2026-10-15 19:10:41', '2026-10-15 17:39:07', 'Work completed successfully.', 1, '2026-10-15 19:10:41', 0, '2026-10-14 14:45:43', '2026-10-15 19:10:41'),
(62, NULL, 2, 5, 5, 1, 'CHECK RENEE MILL WEBSITE FOR APPROVAL AFTER CHECKING EVERYPAGE OF THE WEBSITE AND MAKE IT LIVE', 'mAKE THE WEBSITE LIVE AFTER APPROVAL AND DO THE BASIC SEO THEN .', 'medium', 'completed', NULL, 4.00, 204, '2026-10-21 18:30:00', NULL, '2026-10-27 12:18:47', NULL, 'done from my side added new icon some realistic icon correct grammatical mistakes and remove unnecessary things', 5, '2026-10-27 12:18:47', 0, '2026-10-14 14:47:41', '2026-10-27 12:18:47'),
(63, 21, 5, 8, 8, 1, 'GET STANDARD SMM PHOTOSHOOT DONE', '', 'medium', 'completed', NULL, 4.00, 0, '2026-10-17 18:30:00', NULL, '2026-10-22 16:54:11', '2026-10-22 13:15:43', 'Work completed successfully.', 1, '2026-10-22 16:54:11', 0, '2026-10-14 14:49:27', '2026-10-22 16:54:11'),
(64, 21, 4, 8, 8, 3, 'Standard Social Media', 'Standard all posting for social media.', 'medium', 'completed', NULL, 8.00, 114, '2026-10-21 18:30:00', NULL, '2026-10-23 10:59:36', '2026-10-23 10:21:26', 'Work completed successfully.', 1, '2026-10-23 10:59:36', 0, '2026-10-14 15:06:30', '2026-10-23 10:59:36'),
(65, 18, 3, 6, 6, 5, 'Make 100 backlinks for jeeven sankalp', '', 'medium', 'completed', NULL, 0.00, 308842, '2026-10-21 18:30:00', NULL, '2026-10-28 15:20:03', '2026-10-26 13:49:44', '51 profile creation, 21 social bookmarking, 6 business listing,', 5, '2026-10-28 15:20:03', 0, '2026-10-14 15:41:27', '2026-10-28 15:20:03'),
(66, 9, 5, 13, 13, 1, 'Photo Shoot Mj Grand Wedding', 'Wedding photoshoot', 'high', 'pending', NULL, 4.00, 0, '2026-10-20 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 18:23:56', '2026-10-14 18:23:56'),
(67, 9, 5, 13, 13, 1, 'Mj Dandiya Fair Photoshoot', '', 'high', 'pending', NULL, 4.00, 0, '2026-10-25 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-14 18:25:01', '2026-10-14 18:25:01'),
(68, 12, 7, 8, 8, 1, 'Make Listing of Triveni House on Airbnb (3bhk listing of above 2 floors )', 'Maam wants the listing of Triveni house ( 1st floor and penthouse combined ) \r\nIt will be a 3bhk listing which will be in sync with 1st floor, penthouse, and whole property calander. \r\n3bhk is a highly sellable listing get this done asap .', 'high', 'completed', NULL, 4.00, 4209, '2026-10-15 18:30:00', NULL, '2026-10-15 18:26:48', '2026-10-15 15:39:37', 'done', 1, '2026-10-15 18:26:48', 0, '2026-10-14 18:28:19', '2026-10-15 18:26:48'),
(69, 14, 2, 9, 9, 4, 'Fix Destination Wedding Website .(The task has been assigned to Abhay.)', 'THe website still has my personal phone number . please remove mmy number from all pages.\r\nThe single Property Page is not mobile friendly redesign the whole page .\r\nevery single property page has a form about book your stay . make that form name change to Book your wedding. and also change the form fields accordingly .', 'high', 'completed', NULL, 4.00, 20136, '2026-10-15 18:30:00', NULL, '2026-10-15 15:13:09', '2026-10-15 15:11:33', 'changed the number in all pages, update the form, and updated the layout in the phone view', 4, '2026-10-15 15:13:09', 0, '2026-10-15 10:21:45', '2026-10-15 15:13:09'),
(70, NULL, 3, 10, 10, 5, 'Kashmiri sons 5 Blogs Uttarakhand wedding', 'it is a coded website so give me all blogs in google docs with keywords and everything', 'medium', 'completed', NULL, 1.00, 123, '2026-10-18 18:30:00', NULL, '2026-10-22 16:55:03', '2026-10-19 11:19:44', 'complete 5 blog in kasmirisons', 1, '2026-10-22 16:55:03', 0, '2026-10-15 12:14:33', '2026-10-22 16:55:03'),
(71, NULL, 3, 10, 10, 5, 'kashmiri sons blogs on Uttarakhand Stays', 'it is a coded website so give me all blogs in google docs i will upload and make sure check all the keywords without caniblazation', 'medium', 'completed', NULL, 9.00, 45, '2026-10-21 21:30:00', NULL, '2026-10-22 16:54:56', '2026-10-22 10:20:31', 'done', 1, '2026-10-22 16:54:56', 0, '2026-10-15 12:18:06', '2026-10-22 16:54:56'),
(72, NULL, 3, 10, 10, 5, 'Kashmiri Sons blogs on Dehradungram', 'it is wordpress based website and these blogs will upload by yourself make sure canniblazation wont happen.', 'medium', 'completed', NULL, 4.00, 28074, '2026-10-22 21:30:00', NULL, '2026-10-23 10:59:57', '2026-10-22 18:18:51', 'done', 1, '2026-10-23 10:59:57', 0, '2026-10-15 12:20:41', '2026-10-23 10:59:57'),
(73, NULL, 3, 10, 10, 5, 'Jeevan sankalp blogs on Dehradun gurugram', 'it is a code based website so give Uvaish all blogs in google docs', 'medium', 'completed', NULL, 4.00, 86530, '2026-10-24 21:30:00', NULL, '2026-10-24 17:12:20', '2026-10-24 09:44:52', 'done', 1, '2026-10-24 17:12:20', 0, '2026-10-15 12:27:13', '2026-10-24 17:12:20'),
(74, NULL, 3, 10, 10, 5, 'Jeevan sankalp blogs on Uttarakhand Diaries', 'wordpress based website revamp by abhay then aman will start work again', 'medium', 'pending_approval', NULL, 24.00, 238900, '2026-10-27 21:30:00', NULL, NULL, '2026-10-29 10:43:06', 'done', NULL, NULL, 0, '2026-10-15 12:28:49', '2026-10-29 10:43:06'),
(75, NULL, 3, 10, 10, 5, 'Frontier Jewellers blogs on Uttarakhand wedding', 'it is a coded website so give it me ion docs with proper keywords and content then uvaish will upload', 'medium', 'pending_approval', NULL, 9.00, 0, '2026-10-28 18:30:00', NULL, NULL, '2026-10-30 11:53:52', 'done', NULL, NULL, 0, '2026-10-15 12:32:39', '2026-10-30 11:53:52'),
(76, NULL, 3, 10, 10, 5, 'Frontier Jewellers blogs on Dehradun Gurugram', 'it is a coded website and share in goolge docs  so Uvaish will upload the blogs', 'medium', 'pending_approval', NULL, 4.00, 5502, '2026-10-29 18:30:00', NULL, NULL, '2026-10-30 13:05:24', 'done', NULL, NULL, 0, '2026-10-15 12:33:51', '2026-10-30 13:05:24'),
(77, NULL, 3, 10, 10, 5, 'Frontier Jewellers blogs on Uttarakhan Diaries', 'it is a wordpress based website so aman will upload all the blogs', 'medium', 'pending_approval', NULL, 9.00, 235460, '2026-10-30 18:30:00', NULL, NULL, '2026-11-03 13:32:41', 'done', NULL, NULL, 0, '2026-10-15 12:35:18', '2026-11-03 13:32:41'),
(78, NULL, 3, 10, 10, 5, 'Uttarakhand Wedding blogs for Aristella', 'it is a coded website so Uvaish Sir will upload and aman will share in goolge docs all blogs with their keywords and all', 'medium', 'pending', NULL, 4.00, 0, '2026-10-31 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-15 12:37:18', '2026-10-15 12:37:18'),
(79, NULL, 3, 10, 10, 5, 'Uttarakhand Diaries blogs for Aristella', 'it is a wordpress based website so aman will uplaod all the blogs', 'medium', 'pending', NULL, 9.00, 0, '2026-11-02 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-15 12:40:05', '2026-10-15 12:40:05'),
(80, NULL, 3, 10, 10, 5, 'Dehradun Gurugram blogs for Aristella', 'it is a coded website so aman will share in google docs with uvaish will upload', 'medium', 'pending', NULL, 9.00, 0, '2026-11-03 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-15 12:41:23', '2026-10-15 12:41:23'),
(81, NULL, 2, 9, 9, 4, 'Shein Mill Website Development', 'Started developing a new website for Shein Mill using custom coding.\r\n\r\nCreated the website structure and developed multiple pages with relevant and accurate content.\r\n\r\nWorked on page layouts, UI/UX design, and section alignment to maintain a professional appearance.\r\n\r\nOptimized all pages for mobile responsiveness to ensure a seamless experience across desktop, tablet, and mobile devices.\r\n\r\nFocused on consistent styling, proper image alignment, responsive layouts, and smooth navigation.', 'medium', 'completed', NULL, 4.00, 107766, '2026-10-16 18:30:00', NULL, '2026-10-21 12:50:56', '2026-10-19 10:03:38', 'Completed , 14 blog pages left and handover to Kartikeyan sir', 4, '2026-10-21 12:50:56', 0, '2026-10-15 16:34:23', '2026-10-21 12:50:56'),
(82, NULL, 2, 9, 9, 4, 'Turmeric victoria', 'Migrate all blog posts and internal blog pages from the existing website to the newly coded website. The task includes copying all blog content, maintaining the existing page structure, implementing proper internal linking between relevant blogs and website pages, and ensuring all links work correctly', 'medium', 'completed', NULL, 19.00, 45899, '2026-10-24 18:30:00', NULL, '2026-10-23 10:59:17', '2026-10-23 10:25:08', 'completed all 59 blog pages', 1, '2026-10-23 10:59:17', 0, '2026-10-21 12:36:49', '2026-10-23 10:59:17'),
(85, NULL, 3, 6, 6, 5, 'backlinks on frontier jewellers', '', 'medium', 'paused', NULL, 24.00, 436511, '2026-10-25 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-22 11:01:14', '2026-10-28 10:08:21'),
(86, NULL, 3, 6, 6, 5, 'Liquor fort 50 backlinks', '', 'medium', 'completed', NULL, 8.00, 6928, '2026-10-23 18:30:00', NULL, '2026-10-26 15:50:58', '2026-10-26 15:33:03', 'Cancelled by Karthikey sir due to GMB hacked', 5, '2026-10-26 15:50:58', 0, '2026-10-22 18:07:17', '2026-10-26 15:50:58'),
(87, NULL, 7, 8, 8, 1, 'List Little Lane Cottage on Booking.com', 'List on booking.com', 'high', 'completed', NULL, 4.00, 0, '2026-10-24 18:30:00', NULL, '2026-10-26 12:02:12', '2026-10-26 10:30:58', 'Work completed successfully.', 1, '2026-10-26 12:02:12', 0, '2026-10-23 11:01:20', '2026-10-26 12:02:12'),
(88, 23, 3, 5, 5, 1, 'Check content of new Sheina Mill website', 'Urgent .', 'urgent', 'completed', NULL, 4.00, 163, '2026-10-23 18:30:00', NULL, '2026-10-26 14:10:14', NULL, 'Done and given to sarthak for design', 5, '2026-10-26 14:10:14', 0, '2026-10-23 11:02:53', '2026-10-26 14:10:14'),
(89, 10, 3, 5, 5, 1, 'Make review QR code for Almost Heaven Mussoorie', 'Make a good looking qr code for review and send it to me .', 'urgent', 'completed', NULL, 4.00, 11, '2026-10-23 18:30:00', NULL, '2026-10-26 15:50:40', NULL, 'Done', 5, '2026-10-26 15:50:40', 0, '2026-10-23 11:18:32', '2026-10-26 15:50:40'),
(90, NULL, 3, 5, 5, 1, 'Remake Turmeric Demo Table Booking Screen Recording in english.', 'Make sure you show front end and backend in 1 video only. With functionality. \r\nTry to make the video in full english.', 'urgent', 'completed', NULL, 4.00, 22, '2026-10-23 18:30:00', NULL, '2026-10-30 12:36:54', NULL, 'Work completed successfully.', 5, '2026-10-30 12:36:54', 0, '2026-10-23 11:47:05', '2026-10-30 12:36:54'),
(91, 15, 8, 14, 14, 1, 'Launch Ads on all the latest reels and post of Renee Mill . She has connected the Card for Ads .', '', 'urgent', 'pending', NULL, 4.00, 0, '2026-10-23 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-23 13:20:49', '2026-10-23 13:20:49'),
(92, 6, 14, 14, 14, 1, 'Add Krafting Digital GST in all the ads account possible (meta and google both )', '', 'urgent', 'pending', NULL, 4.00, 0, '2026-10-23 18:30:00', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-10-23 13:24:22', '2026-10-23 13:24:22'),
(93, NULL, 2, 9, 9, 4, 'Uttarakhandstays', 'frontier jewellers blogs on uttarakhand stays and publish blogs and take it file from aman', 'high', 'completed', NULL, 4.00, 24008, '2026-11-03 18:30:00', NULL, '2026-11-05 16:26:58', '2026-11-04 17:52:08', 'completed the blog task, added the given blog in the uttarakhandstays website', 4, '2026-11-05 16:26:58', 0, '2026-11-03 11:34:21', '2026-11-05 16:26:58');

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

--
-- Dumping data for table `task_comments`
--

INSERT INTO `task_comments` (`id`, `task_id`, `user_id`, `comment`, `attachments_json`, `created_at`) VALUES
(1, 57, 4, 'The task has been assigned to Abhay', NULL, '2026-10-15 10:26:34'),
(2, 77, 10, '[Live Work Note] done', NULL, '2026-11-03 13:32:13');

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

--
-- Dumping data for table `task_files`
--

INSERT INTO `task_files` (`id`, `task_id`, `project_id`, `user_id`, `file_name`, `file_path`, `file_size`, `file_type`, `created_at`) VALUES
(18, NULL, NULL, 5, 'Almost-Heaven.zip', '1793795585_Almost-Heaven.zip', 114335716, 'application/x-zip-compressed', '2026-11-04 18:03:05'),
(19, NULL, NULL, 5, 'Kashmiri_Sons_SEO_Local_Ranking_Report_Nov_2026.pdf', '1793882825_Kashmiri_Sons_SEO_Local_Ranking_Report_Nov_2026.pdf', 642319, 'application/pdf', '2026-11-05 18:17:05'),
(20, NULL, NULL, 4, 'public_html.zip', '1793964878_public_html.zip', 116030580, 'application/zip', '2026-11-06 17:04:39'),
(22, NULL, NULL, 9, 'Almost-Heaven.zip', '1793968592_Almost-Heaven.zip', 122478146, 'application/x-zip-compressed', '2026-11-06 18:06:32');

-- --------------------------------------------------------

--
-- Table structure for table `task_flags`
--

CREATE TABLE `task_flags` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `category` varchar(100) NOT NULL,
  `missing_requirement` text NOT NULL,
  `impact` varchar(100) NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'open',
  `resolution_message` text DEFAULT NULL,
  `resolved_by` int(11) DEFAULT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_flags`
--

INSERT INTO `task_flags` (`id`, `task_id`, `employee_id`, `category`, `missing_requirement`, `impact`, `attachment`, `status`, `resolution_message`, `resolved_by`, `resolved_at`, `created_at`, `updated_at`) VALUES
(1, 26, 9, 'Missing Assets / Design', 'Client has not provided 2x high-resolution banners and logo SVG for luxury resort mobile drawer.', 'high', NULL, 'open', NULL, NULL, NULL, '2026-10-13 08:53:36', '2026-10-13 08:53:36'),
(2, 5, 10, 'Credentials / Access', 'Access credentials to client Google Search Console and local directories pending from account manager.', 'medium', NULL, 'open', NULL, NULL, NULL, '2026-10-13 06:53:36', '2026-10-13 06:53:36'),
(3, 1, 4, 'API Documentation', 'Third-party webhook payload schema missing from client tech team.', 'low', NULL, 'resolved', 'KD provided technical API documentation PDF and endpoint credentials.', 1, '2026-10-13 10:53:36', '2026-10-12 11:53:36', '2026-10-13 10:53:36'),
(4, 45, 4, 'Other', 'The website is currently running on the local server.\r\nServer access details have not yet been received, so deployment to the live server is pending.\r\nOnce the server credentials and configuration details are provided, the website will be deployed to the live server.\r\nAll remaining deployment activities will be completed after receiving the required server information.', 'Clarification Required', NULL, 'waiting_info', NULL, NULL, NULL, '2026-10-15 15:12:00', '2026-10-15 19:06:03'),
(5, 35, 4, 'Waiting for Approval', 'Successfully integrated the booking engine into the Sunrish website.\r\n\r\nThe Wild Flower booking engine is still pending, as the required booking engine details have not yet been received from the client.\r\n\r\nCurrently waiting for the client to provide the necessary access and integration details to proceed with the Wild Flower booking engine setup.\r\n\r\nThe integration will be completed once the required details are received.', 'Task Cannot Start', NULL, 'open', NULL, NULL, NULL, '2026-10-15 17:48:26', '2026-10-15 19:16:21');

-- --------------------------------------------------------

--
-- Table structure for table `task_flag_comments`
--

CREATE TABLE `task_flag_comments` (
  `id` int(11) NOT NULL,
  `flag_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task_flag_comments`
--

INSERT INTO `task_flag_comments` (`id`, `flag_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 4, 4, 'Flag reported with impact: \'Clarification Required\'. Requirement: The website is currently running on the local server.\r\nServer access details have not yet been received, so deployment to the live server is pending.\r\nOnce the server credentials and configuration details are provided, the website will be deployed to the live server.\r\nAll remaining deployment activities will be completed after receiving the required server information.', '2026-10-15 15:12:00'),
(2, 5, 4, 'Flag reported with impact: \'Task Cannot Start\'. Requirement: Successfully integrated the booking engine into the Sunrish website.\r\n\r\nThe Wild Flower booking engine is still pending, as the required booking engine details have not yet been received from the client.\r\n\r\nCurrently waiting for the client to provide the necessary access and integration details to proceed with the Wild Flower booking engine setup.\r\n\r\nThe integration will be completed once the required details are received.', '2026-10-15 17:48:26'),
(3, 5, 1, 'please call zimmerstack and get this completed . you have there numbers in the group . call them and complete the task .', '2026-10-15 18:31:39'),
(4, 4, 1, 'We have to provide the demo on temporary link only .', '2026-10-15 19:03:23'),
(5, 4, 1, 'integrate the whole system with the current website . also add a dashbord for inhouse table orders through the menu qr code. also make 10 qr codes for different tables so that people of inhouse dining can scan the qr code and order there food from the interactive menu we have made.', '2026-10-15 19:05:14'),
(6, 4, 1, 'once done give me a demo then we can take this forward', '2026-10-15 19:05:31'),
(7, 4, 1, 'Status updated to Waiting Info: Status transitioned to waiting_info', '2026-10-15 19:06:03'),
(8, 5, 1, 'Status updated to Open: Status transitioned to open', '2026-10-15 19:06:32'),
(9, 5, 1, 'Status updated to Open: Status transitioned to open', '2026-10-15 19:16:21');

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
(3, 1, 4, '2026-09-25', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-09-25 16:54:42'),
(4, 30, 1, '2026-10-14', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-14 18:29:32'),
(5, 46, 4, '2026-10-15', 'Completed website testing to verify functionality across different sections.\r\n\r\nChecked and refined the layout and alignment for mobile devices.\r\n\r\nVerified mobile responsiveness to ensure a consistent user experience across different screen sizes.\r\n\r\nReviewed spacing, padding, typography, and element positioning to maintain a clean and professional design.\r\n\r\nEnsured the website layout displays correctly on mobile screens and provides smooth navigation.\r\n\r\nhttps://whitesmoke-moose-857463.hostingersite.com/theme-2/admin/login.php', 100, NULL, 'Completed website testing to verify functionality across different sections.\r\n\r\nChecked and refined the layout and alignment for mobile devices.\r\n\r\nVerified mobile responsiveness to ensure a consistent user experience across different screen sizes.\r\n\r\nReviewed spacing, padding, typography, and element positioning to maintain a clean and professional design.\r\n\r\nEnsured the website layout displays correctly on mobile screens and provides smooth navigation.\r\n\r\nhttps://whitesmoke-moose-857463.hostingersite.com/theme-2/admin/login.php', NULL, NULL, '2026-10-15 12:05:19'),
(6, 47, 2, '2026-10-15', 'Sent bill', 100, NULL, 'Sent bill', NULL, NULL, '2026-10-15 13:15:05'),
(7, 64, 8, '2026-10-15', 'task complete monthly posting calender', 100, NULL, 'task complete monthly posting calender', NULL, NULL, '2026-10-15 13:36:00'),
(8, 68, 8, '2026-10-15', 'done', 50, '', NULL, NULL, NULL, '2026-10-15 14:33:20'),
(9, 45, 4, '2026-10-15', 'Tested all website sections to ensure proper functionality and layout consistency.\nChecked and corrected image alignment, sizing, spacing, and positioning across all sections.\nOptimized all website sections for mobile responsiveness.\nAdjusted padding, margins, typography, and element alignment to ensure a clean and professional mobile layout.\nVerified the website design across different mobile screen sizes to ensure a smooth and consistent user experience.\nImproved the overall UI/UX to maintain a polished and responsive design across desktop and mobile devices.', 100, NULL, 'Tested all website sections to ensure proper functionality and layout consistency.\nChecked and corrected image alignment, sizing, spacing, and positioning across all sections.\nOptimized all website sections for mobile responsiveness.\nAdjusted padding, margins, typography, and element alignment to ensure a clean and professional mobile layout.\nVerified the website design across different mobile screen sizes to ensure a smooth and consistent user experience.\nImproved the overall UI/UX to maintain a polished and responsive design across desktop and mobile devices.', NULL, NULL, '2026-10-15 15:09:07'),
(10, 69, 9, '2026-10-15', 'changed the number in all pages, update the form, and updated the layout in the phone view', 100, NULL, 'changed the number in all pages, update the form, and updated the layout in the phone view', NULL, NULL, '2026-10-15 15:11:33'),
(11, 57, 4, '2026-10-15', 'The task has been assigned to Abhay\r\nAbhay completed the task', 100, NULL, 'The task has been assigned to Abhay\r\nAbhay completed the task', NULL, NULL, '2026-10-15 15:14:20'),
(12, 68, 8, '2026-10-15', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-15 15:39:37'),
(13, 61, 14, '2026-10-15', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-15 17:39:07'),
(14, 39, 14, '2026-10-15', 'Made budget report and Lead meta ad is live but need remaining budget to start other ads.', 100, NULL, 'Made budget report and Lead meta ad is live but need remaining budget to start other ads.', NULL, NULL, '2026-10-15 17:41:18'),
(15, 34, 4, '2026-10-15', 'Updated the logo across the entire website.\n\nReplaced the old logo with the new logo in all relevant sections and pages.\n\nChecked logo alignment, sizing, and spacing to maintain a consistent design.\n\nEnsured the updated logo displays correctly across desktop, tablet, and mobile devices.\n\nVerified the overall website appearance after the logo update.', 100, NULL, 'Updated the logo across the entire website.\n\nReplaced the old logo with the new logo in all relevant sections and pages.\n\nChecked logo alignment, sizing, and spacing to maintain a consistent design.\n\nEnsured the updated logo displays correctly across desktop, tablet, and mobile devices.\n\nVerified the overall website appearance after the logo update.', NULL, NULL, '2026-10-15 17:41:21'),
(16, 60, 14, '2026-10-15', 'Made the report. You told me to wait for budget approval', 100, NULL, 'Made the report. You told me to wait for budget approval', NULL, NULL, '2026-10-15 17:42:25'),
(17, 28, 4, '2026-10-17', 'Done', 100, NULL, 'Done', NULL, NULL, '2026-10-17 10:03:50'),
(18, 57, 4, '2026-10-17', 'Done', 100, NULL, 'Done', NULL, NULL, '2026-10-17 11:50:07'),
(19, 46, 4, '2026-10-17', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-17 11:50:17'),
(20, 81, 9, '2026-10-19', 'Completed , 14 blog pages left and handover to Kartikeyan sir', 100, NULL, 'Completed , 14 blog pages left and handover to Kartikeyan sir', NULL, NULL, '2026-10-19 10:03:38'),
(21, 70, 10, '2026-10-19', 'complete 5 blog in kasmirisons', 100, NULL, 'complete 5 blog in kasmirisons', NULL, NULL, '2026-10-19 11:19:44'),
(22, 58, 5, '2026-10-19', 'About shivam dang website plan I already had a discussion with Sir and i will apply this plan to the website then we will move forward', 100, NULL, 'About shivam dang website plan I already had a discussion with Sir and i will apply this plan to the website then we will move forward', NULL, NULL, '2026-10-19 13:03:05'),
(23, 71, 10, '2026-10-22', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-22 10:20:31'),
(24, 55, 6, '2026-10-22', 'Completed', 100, NULL, 'Completed', NULL, NULL, '2026-10-22 10:55:02'),
(25, 32, 8, '2026-10-22', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-22 13:15:34'),
(26, 63, 8, '2026-10-22', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-22 13:15:43'),
(27, 33, 8, '2026-10-22', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-22 13:44:19'),
(28, 72, 10, '2026-10-22', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-22 18:18:51'),
(29, 29, 8, '2026-10-23', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-23 10:21:14'),
(30, 64, 8, '2026-10-23', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-23 10:21:26'),
(31, 82, 9, '2026-10-23', 'completed all 59 blog pages', 100, NULL, 'completed all 59 blog pages', NULL, NULL, '2026-10-23 10:25:08'),
(32, 59, 5, '2026-10-23', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-23 12:18:29'),
(33, 40, 3, '2026-10-23', 'DONE', 100, NULL, 'DONE', NULL, NULL, '2026-10-23 15:31:47'),
(34, 73, 10, '2026-10-24', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-24 09:44:52'),
(35, 37, 5, '2026-10-26', 'plan approved by Sir waiting for the meeting with tess', 100, NULL, 'plan approved by Sir waiting for the meeting with tess', NULL, NULL, '2026-10-26 10:10:03'),
(36, 38, 5, '2026-10-26', 'Done and plan approved by KD', 100, NULL, 'Done and plan approved by KD', NULL, NULL, '2026-10-26 10:10:30'),
(37, 51, 5, '2026-10-26', 'One plan is already applied location vise like Haridwar, Roorkee, Chandigrah and all places now i will more Optimise those pages again', 100, NULL, 'One plan is already applied location vise like Haridwar, Roorkee, Chandigrah and all places now i will more Optimise those pages again', NULL, NULL, '2026-10-26 10:12:40'),
(38, 87, 8, '2026-10-26', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-26 10:30:58'),
(39, 44, 5, '2026-10-26', 'Done with the help of claud.ai but i will add more thing after printing in hard copy', 100, NULL, 'Done with the help of claud.ai but i will add more thing after printing in hard copy', NULL, NULL, '2026-10-26 13:08:47'),
(40, 65, 6, '2026-10-26', '51 profile creation, 21 social bookmarking, 6 business listing,', 100, NULL, '51 profile creation, 21 social bookmarking, 6 business listing,', NULL, NULL, '2026-10-26 13:49:44'),
(41, 53, 5, '2026-10-26', 'did`nt get any approval from the client side but get approved by KD', 100, NULL, 'did`nt get any approval from the client side but get approved by KD', NULL, NULL, '2026-10-26 13:50:47'),
(42, 88, 5, '2026-10-26', 'Done and given to sarthak for design', 100, NULL, 'Done and given to sarthak for design', NULL, NULL, '2026-10-26 14:10:14'),
(43, 86, 6, '2026-10-26', 'Cancelled by Karthikey sir due to GMB hacked', 100, NULL, 'Cancelled by Karthikey sir due to GMB hacked', NULL, NULL, '2026-10-26 15:33:03'),
(44, 89, 5, '2026-10-26', 'Done', 100, NULL, 'Done', NULL, NULL, '2026-10-26 15:50:40'),
(45, 62, 5, '2026-10-27', 'done from my side added new icon some realistic icon correct grammatical mistakes and remove unnecessary things', 100, NULL, 'done from my side added new icon some realistic icon correct grammatical mistakes and remove unnecessary things', NULL, NULL, '2026-10-27 12:18:47'),
(46, 49, 5, '2026-10-27', 'SEO plan is already applied targeting new high traffic keywords and i removed canniblaization from the whole website and then indexing again so right page will target right page', 100, NULL, 'SEO plan is already applied targeting new high traffic keywords and i removed canniblaization from the whole website and then indexing again so right page will target right page', NULL, NULL, '2026-10-27 12:21:17'),
(47, 74, 10, '2026-10-29', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-29 10:43:06'),
(48, 75, 10, '2026-10-30', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-30 11:53:52'),
(49, 90, 5, '2026-10-30', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-30 12:36:54'),
(50, 52, 5, '2026-10-30', 'Work completed successfully.', 100, NULL, 'Work completed successfully.', NULL, NULL, '2026-10-30 12:56:39'),
(51, 76, 10, '2026-10-30', 'done', 100, NULL, 'done', NULL, NULL, '2026-10-30 13:05:24'),
(52, 77, 10, '2026-11-03', 'done', 50, '', NULL, NULL, NULL, '2026-11-03 13:30:34'),
(53, 77, 10, '2026-11-03', 'done', 100, NULL, 'done', NULL, NULL, '2026-11-03 13:32:41'),
(54, 93, 9, '2026-11-04', 'completed the blog task, added the given blog in the uttarakhandstays website', 100, NULL, 'completed the blog task, added the given blog in the uttarakhandstays website', NULL, NULL, '2026-11-04 17:52:08');

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
(2, 10, 9, '2026-08-24 17:53:32', '2026-08-24 17:53:43', NULL, NULL, 21, 0, 'completed', '2026-08-24 17:53:32'),
(3, 30, 1, '2026-10-14 18:29:00', '2026-10-14 18:29:24', NULL, NULL, 34, 0, 'paused', '2026-10-14 18:29:00'),
(4, 46, 4, '2026-10-15 10:23:11', '2026-10-15 12:03:07', NULL, NULL, 9816, 0, 'paused', '2026-10-15 10:23:11'),
(5, 65, 6, '2026-10-15 10:28:56', '2026-10-15 10:29:45', NULL, NULL, 89, 0, 'paused', '2026-10-15 10:28:56'),
(6, 65, 6, '2026-10-15 10:29:45', '2026-10-15 18:07:01', NULL, NULL, 27896, 0, 'paused', '2026-10-15 10:29:45'),
(7, 69, 9, '2026-10-15 10:37:37', '2026-10-15 15:11:33', NULL, NULL, 20136, 0, 'completed', '2026-10-15 10:37:37'),
(8, 45, 4, '2026-10-15 12:03:07', '2026-10-15 12:03:16', NULL, NULL, 9, 0, 'paused', '2026-10-15 12:03:07'),
(9, 45, 4, '2026-10-15 12:03:16', '2026-10-15 15:09:07', NULL, NULL, 13351, 0, 'completed', '2026-10-15 12:03:16'),
(10, 64, 8, '2026-10-15 13:34:56', '2026-10-15 13:36:00', NULL, NULL, 114, 0, 'completed', '2026-10-15 13:34:56'),
(11, 68, 8, '2026-10-15 13:40:27', '2026-10-15 14:32:35', NULL, NULL, 3828, 0, 'paused', '2026-10-15 13:40:27'),
(12, 68, 8, '2026-10-15 14:32:43', '2026-10-15 14:33:04', NULL, NULL, 41, 0, 'paused', '2026-10-15 14:32:43'),
(13, 68, 8, '2026-10-15 14:33:04', '2026-10-15 14:33:06', NULL, NULL, 2, 0, 'paused', '2026-10-15 14:33:04'),
(14, 68, 8, '2026-10-15 14:33:27', '2026-10-15 14:34:25', NULL, NULL, 108, 0, 'paused', '2026-10-15 14:33:27'),
(15, 68, 8, '2026-10-15 14:34:25', '2026-10-15 14:36:50', NULL, NULL, 205, 0, 'paused', '2026-10-15 14:34:25'),
(16, 34, 4, '2026-10-15 15:09:54', '2026-10-15 17:41:21', NULL, NULL, 10097, 0, 'completed', '2026-10-15 15:09:54'),
(17, 68, 8, '2026-10-15 15:39:22', '2026-10-15 15:39:37', NULL, NULL, 25, 0, 'completed', '2026-10-15 15:39:22'),
(18, 81, 9, '2026-10-15 16:35:05', '2026-10-15 18:30:45', NULL, NULL, 7620, 0, 'paused', '2026-10-15 16:35:05'),
(19, 28, 4, '2026-10-15 17:41:47', '2026-10-15 18:27:04', NULL, NULL, 4787, 0, 'paused', '2026-10-15 17:41:47'),
(20, 65, 6, '2026-10-15 18:09:46', '2026-10-15 18:12:06', NULL, NULL, 270, 0, 'paused', '2026-10-15 18:09:46'),
(21, 81, 9, '2026-10-16 09:59:20', '2026-10-17 09:54:26', NULL, NULL, 100146, 0, 'paused', '2026-10-16 09:59:20'),
(22, 65, 6, '2026-10-16 10:03:06', '2026-10-16 10:06:05', NULL, NULL, 349, 0, 'paused', '2026-10-16 10:03:06'),
(23, 65, 6, '2026-10-16 10:06:05', '2026-10-16 12:45:19', NULL, NULL, 10284, 0, 'paused', '2026-10-16 10:06:05'),
(24, 65, 6, '2026-10-16 16:50:38', '2026-10-16 18:30:05', NULL, NULL, 7167, 0, 'paused', '2026-10-16 16:50:38'),
(25, 55, 6, '2026-10-17 10:06:59', '2026-10-17 18:30:43', NULL, NULL, 31434, 0, 'paused', '2026-10-17 10:06:59'),
(26, 37, 5, '2026-10-17 15:32:46', '2026-10-17 15:32:59', NULL, NULL, 23, 0, 'paused', '2026-10-17 15:32:46'),
(27, 65, 6, '2026-10-19 10:19:03', '2026-10-22 10:54:00', NULL, NULL, 262787, 0, 'paused', '2026-10-19 10:19:03'),
(28, 70, 10, '2026-10-19 11:18:41', '2026-10-19 11:19:44', NULL, NULL, 123, 0, 'completed', '2026-10-19 11:18:41'),
(29, 58, 5, '2026-10-19 13:01:43', '2026-10-19 13:03:05', NULL, NULL, 162, 0, 'completed', '2026-10-19 13:01:43'),
(30, 82, 9, '2026-10-21 12:51:56', '2026-10-21 18:31:30', NULL, NULL, 21074, 0, 'paused', '2026-10-21 12:51:56'),
(31, 59, 5, '2026-10-22 10:02:23', '2026-10-23 12:18:29', NULL, NULL, 96736, 0, 'completed', '2026-10-22 10:02:23'),
(32, 82, 9, '2026-10-22 10:13:23', '2026-10-22 14:00:49', NULL, NULL, 16186, 0, 'paused', '2026-10-22 10:13:23'),
(33, 71, 10, '2026-10-22 10:20:06', '2026-10-22 10:20:09', NULL, NULL, 3, 0, 'paused', '2026-10-22 10:20:06'),
(34, 71, 10, '2026-10-22 10:20:09', '2026-10-22 10:20:31', NULL, NULL, 42, 0, 'completed', '2026-10-22 10:20:09'),
(35, 72, 10, '2026-10-22 10:45:07', '2026-10-22 10:45:07', NULL, NULL, 0, 0, 'paused', '2026-10-22 10:45:07'),
(36, 72, 10, '2026-10-22 10:45:07', '2026-10-22 10:45:07', NULL, NULL, 0, 0, 'paused', '2026-10-22 10:45:07'),
(37, 72, 10, '2026-10-22 10:45:07', '2026-10-22 10:45:07', NULL, NULL, 0, 0, 'paused', '2026-10-22 10:45:07'),
(38, 72, 10, '2026-10-22 10:45:07', '2026-10-22 18:18:51', NULL, NULL, 28074, 0, 'completed', '2026-10-22 10:45:07'),
(39, 85, 6, '2026-10-22 11:02:02', '2026-10-26 10:43:39', NULL, NULL, 346407, 0, 'paused', '2026-10-22 11:02:02'),
(40, 32, 8, '2026-10-22 12:21:37', '2026-10-22 13:15:34', NULL, NULL, 4197, 0, 'completed', '2026-10-22 12:21:37'),
(41, 33, 8, '2026-10-22 13:15:50', '2026-10-22 13:44:19', NULL, NULL, 2749, 0, 'completed', '2026-10-22 13:15:50'),
(42, 82, 9, '2026-10-22 16:11:38', '2026-10-22 18:32:07', NULL, NULL, 8639, 0, 'paused', '2026-10-22 16:11:38'),
(43, 73, 10, '2026-10-23 10:32:22', '2026-10-23 10:34:12', NULL, NULL, 210, 0, 'paused', '2026-10-23 10:32:22'),
(44, 73, 10, '2026-10-23 10:34:12', '2026-10-23 10:48:55', NULL, NULL, 1623, 0, 'paused', '2026-10-23 10:34:12'),
(45, 73, 10, '2026-10-23 10:48:55', '2026-10-23 10:48:55', NULL, NULL, 10, 0, 'paused', '2026-10-23 10:48:55'),
(46, 73, 10, '2026-10-23 10:48:55', '2026-10-24 09:44:52', NULL, NULL, 84687, 0, 'completed', '2026-10-23 10:48:55'),
(47, 74, 10, '2026-10-24 15:35:17', '2026-10-26 10:48:12', NULL, NULL, 156835, 0, 'paused', '2026-10-24 15:35:17'),
(48, 37, 5, '2026-10-26 10:09:36', '2026-10-26 10:10:03', NULL, NULL, 47, 0, 'completed', '2026-10-26 10:09:36'),
(49, 38, 5, '2026-10-26 10:10:18', '2026-10-26 10:10:30', NULL, NULL, 22, 0, 'completed', '2026-10-26 10:10:18'),
(50, 51, 5, '2026-10-26 10:11:15', '2026-10-26 10:12:40', NULL, NULL, 165, 0, 'completed', '2026-10-26 10:11:15'),
(51, 86, 6, '2026-10-26 10:57:52', '2026-10-26 12:44:24', NULL, NULL, 6922, 0, 'paused', '2026-10-26 10:57:52'),
(52, 85, 6, '2026-10-26 12:44:34', '2026-10-26 13:39:03', NULL, NULL, 3929, 0, 'paused', '2026-10-26 12:44:34'),
(53, 44, 5, '2026-10-26 13:05:53', '2026-10-26 13:08:47', NULL, NULL, 344, 0, 'completed', '2026-10-26 13:05:53'),
(54, 85, 6, '2026-10-26 13:39:50', '2026-10-26 13:50:04', NULL, NULL, 884, 0, 'paused', '2026-10-26 13:39:50'),
(55, 53, 5, '2026-10-26 13:50:12', '2026-10-26 13:50:47', NULL, NULL, 65, 0, 'completed', '2026-10-26 13:50:12'),
(56, 88, 5, '2026-10-26 14:08:51', '2026-10-26 14:10:14', NULL, NULL, 163, 0, 'completed', '2026-10-26 14:08:51'),
(57, 86, 6, '2026-10-26 15:32:37', '2026-10-26 15:32:39', NULL, NULL, 2, 0, 'paused', '2026-10-26 15:32:37'),
(58, 86, 6, '2026-10-26 15:32:39', '2026-10-26 15:32:43', NULL, NULL, 4, 0, 'paused', '2026-10-26 15:32:39'),
(59, 89, 5, '2026-10-26 15:50:29', '2026-10-26 15:50:40', NULL, NULL, 11, 0, 'completed', '2026-10-26 15:50:29'),
(60, 85, 6, '2026-10-27 10:55:50', '2026-10-28 10:08:21', NULL, NULL, 85291, 0, 'paused', '2026-10-27 10:55:50'),
(61, 74, 10, '2026-10-27 11:48:32', '2026-10-27 13:06:08', NULL, NULL, 5806, 0, 'paused', '2026-10-27 11:48:32'),
(62, 62, 5, '2026-10-27 12:17:03', '2026-10-27 12:18:47', NULL, NULL, 204, 0, 'completed', '2026-10-27 12:17:03'),
(63, 49, 5, '2026-10-27 12:19:24', '2026-10-27 12:21:17', NULL, NULL, 223, 0, 'completed', '2026-10-27 12:19:24'),
(64, 74, 10, '2026-10-27 13:06:08', '2026-10-28 10:05:07', NULL, NULL, 76259, 0, 'paused', '2026-10-27 13:06:08'),
(65, 76, 10, '2026-10-30 11:54:12', '2026-10-30 13:05:24', NULL, NULL, 5502, 0, 'completed', '2026-10-30 11:54:12'),
(66, 90, 5, '2026-10-30 12:36:42', '2026-10-30 12:36:54', NULL, NULL, 22, 0, 'completed', '2026-10-30 12:36:42'),
(67, 52, 5, '2026-10-30 12:56:35', '2026-10-30 12:56:39', NULL, NULL, 4, 0, 'completed', '2026-10-30 12:56:35'),
(68, 77, 10, '2026-10-30 17:14:13', '2026-11-02 09:49:23', NULL, NULL, 235210, 0, 'paused', '2026-10-30 17:14:13'),
(69, 93, 9, '2026-11-03 11:58:41', '2026-11-03 18:33:59', NULL, NULL, 24008, 0, 'paused', '2026-11-03 11:58:41'),
(70, 77, 10, '2026-11-03 13:30:31', '2026-11-03 13:30:31', NULL, NULL, 0, 0, 'paused', '2026-11-03 13:30:31'),
(71, 77, 10, '2026-11-03 13:30:31', '2026-11-03 13:32:41', NULL, NULL, 250, 0, 'completed', '2026-11-03 13:30:31');

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
(5, 1, 4, NULL, NULL, NULL, '2026-09-25 16:54:42', 0, 'completed', '2026-09-25 16:54:42', '2026-09-25 16:54:42'),
(6, 30, 1, '2026-10-14 18:29:00', NULL, NULL, NULL, 0, 'started', '2026-10-14 18:29:00', '2026-10-14 18:29:00'),
(7, 30, 1, NULL, '2026-10-14 18:29:24', NULL, NULL, 24, 'paused', '2026-10-14 18:29:24', '2026-10-14 18:29:24'),
(8, 30, 1, NULL, NULL, NULL, '2026-10-14 18:29:32', 0, 'completed', '2026-10-14 18:29:32', '2026-10-14 18:29:32'),
(9, 46, 4, '2026-10-15 10:23:11', NULL, NULL, NULL, 0, 'started', '2026-10-15 10:23:11', '2026-10-15 10:23:11'),
(10, 65, 6, '2026-10-15 10:28:56', NULL, NULL, NULL, 0, 'started', '2026-10-15 10:28:56', '2026-10-15 10:28:56'),
(11, 65, 6, NULL, '2026-10-15 10:29:45', NULL, NULL, 49, 'paused', '2026-10-15 10:29:45', '2026-10-15 10:29:45'),
(12, 65, 6, '2026-10-15 10:29:45', NULL, NULL, NULL, 0, 'started', '2026-10-15 10:29:45', '2026-10-15 10:29:45'),
(13, 69, 9, '2026-10-15 10:37:37', NULL, NULL, NULL, 0, 'started', '2026-10-15 10:37:37', '2026-10-15 10:37:37'),
(14, 46, 4, NULL, '2026-10-15 12:03:07', NULL, NULL, 5996, 'paused', '2026-10-15 12:03:07', '2026-10-15 12:03:07'),
(15, 45, 4, '2026-10-15 12:03:07', NULL, NULL, NULL, 0, 'started', '2026-10-15 12:03:07', '2026-10-15 12:03:07'),
(16, 45, 4, NULL, '2026-10-15 12:03:16', NULL, NULL, 9, 'paused', '2026-10-15 12:03:16', '2026-10-15 12:03:16'),
(17, 45, 4, '2026-10-15 12:03:16', NULL, NULL, NULL, 0, 'started', '2026-10-15 12:03:16', '2026-10-15 12:03:16'),
(18, 64, 8, '2026-10-15 13:34:56', NULL, NULL, NULL, 0, 'started', '2026-10-15 13:34:56', '2026-10-15 13:34:56'),
(19, 64, 8, NULL, NULL, NULL, '2026-10-15 13:36:00', 64, 'completed', '2026-10-15 13:36:00', '2026-10-15 13:36:00'),
(20, 68, 8, '2026-10-15 13:40:27', NULL, NULL, NULL, 0, 'started', '2026-10-15 13:40:27', '2026-10-15 13:40:27'),
(21, 68, 8, NULL, '2026-10-15 14:32:35', NULL, NULL, 3128, 'paused', '2026-10-15 14:32:35', '2026-10-15 14:32:35'),
(22, 68, 8, '2026-10-15 14:32:43', NULL, NULL, NULL, 0, 'started', '2026-10-15 14:32:43', '2026-10-15 14:32:43'),
(23, 68, 8, NULL, '2026-10-15 14:33:04', NULL, NULL, 21, 'paused', '2026-10-15 14:33:04', '2026-10-15 14:33:04'),
(24, 68, 8, '2026-10-15 14:33:04', NULL, NULL, NULL, 0, 'started', '2026-10-15 14:33:04', '2026-10-15 14:33:04'),
(25, 68, 8, NULL, '2026-10-15 14:33:06', NULL, NULL, 2, 'paused', '2026-10-15 14:33:06', '2026-10-15 14:33:06'),
(26, 68, 8, '2026-10-15 14:33:27', NULL, NULL, NULL, 0, 'started', '2026-10-15 14:33:27', '2026-10-15 14:33:27'),
(27, 68, 8, NULL, '2026-10-15 14:34:25', NULL, NULL, 58, 'paused', '2026-10-15 14:34:25', '2026-10-15 14:34:25'),
(28, 68, 8, '2026-10-15 14:34:25', NULL, NULL, NULL, 0, 'started', '2026-10-15 14:34:25', '2026-10-15 14:34:25'),
(29, 68, 8, NULL, '2026-10-15 14:36:50', NULL, NULL, 145, 'paused', '2026-10-15 14:36:50', '2026-10-15 14:36:50'),
(30, 45, 4, NULL, NULL, NULL, '2026-10-15 15:09:07', 11151, 'completed', '2026-10-15 15:09:07', '2026-10-15 15:09:07'),
(31, 34, 4, '2026-10-15 15:09:54', NULL, NULL, NULL, 0, 'started', '2026-10-15 15:09:54', '2026-10-15 15:09:54'),
(32, 69, 9, NULL, NULL, NULL, '2026-10-15 15:11:33', 16436, 'completed', '2026-10-15 15:11:33', '2026-10-15 15:11:33'),
(33, 68, 8, '2026-10-15 15:39:22', NULL, NULL, NULL, 0, 'started', '2026-10-15 15:39:22', '2026-10-15 15:39:22'),
(34, 68, 8, NULL, NULL, NULL, '2026-10-15 15:39:37', 15, 'completed', '2026-10-15 15:39:37', '2026-10-15 15:39:37'),
(35, 81, 9, '2026-10-15 16:35:05', NULL, NULL, NULL, 0, 'started', '2026-10-15 16:35:05', '2026-10-15 16:35:05'),
(36, 34, 4, NULL, NULL, NULL, '2026-10-15 17:41:21', 9087, 'completed', '2026-10-15 17:41:21', '2026-10-15 17:41:21'),
(37, 28, 4, '2026-10-15 17:41:47', NULL, NULL, NULL, 0, 'started', '2026-10-15 17:41:47', '2026-10-15 17:41:47'),
(38, 65, 6, NULL, '2026-10-15 18:07:01', NULL, NULL, 27436, 'paused', '2026-10-15 18:07:01', '2026-10-15 18:07:01'),
(39, 65, 6, '2026-10-15 18:09:46', NULL, NULL, NULL, 0, 'started', '2026-10-15 18:09:46', '2026-10-15 18:09:46'),
(40, 65, 6, NULL, '2026-10-15 18:12:06', NULL, NULL, 140, 'paused', '2026-10-15 18:12:06', '2026-10-15 18:12:06'),
(41, 28, 4, NULL, '2026-10-15 18:27:04', NULL, NULL, 2717, 'paused', '2026-10-15 18:27:04', '2026-10-15 18:27:04'),
(42, 81, 9, NULL, '2026-10-15 18:30:45', NULL, NULL, 6940, 'paused', '2026-10-15 18:30:45', '2026-10-15 18:30:45'),
(43, 81, 9, '2026-10-16 09:59:20', NULL, NULL, NULL, 0, 'started', '2026-10-16 09:59:20', '2026-10-16 09:59:20'),
(44, 65, 6, '2026-10-16 10:03:06', NULL, NULL, NULL, 0, 'started', '2026-10-16 10:03:06', '2026-10-16 10:03:06'),
(45, 65, 6, NULL, '2026-10-16 10:06:05', NULL, NULL, 179, 'paused', '2026-10-16 10:06:05', '2026-10-16 10:06:05'),
(46, 65, 6, '2026-10-16 10:06:05', NULL, NULL, NULL, 0, 'started', '2026-10-16 10:06:05', '2026-10-16 10:06:05'),
(47, 65, 6, NULL, '2026-10-16 12:45:19', NULL, NULL, 9554, 'paused', '2026-10-16 12:45:19', '2026-10-16 12:45:19'),
(48, 65, 6, '2026-10-16 16:50:38', NULL, NULL, NULL, 0, 'started', '2026-10-16 16:50:38', '2026-10-16 16:50:38'),
(49, 65, 6, NULL, '2026-10-16 18:30:05', NULL, NULL, 5967, 'paused', '2026-10-16 18:30:05', '2026-10-16 18:30:05'),
(50, 81, 9, NULL, '2026-10-17 09:54:26', NULL, NULL, 86106, 'paused', '2026-10-17 09:54:26', '2026-10-17 09:54:26'),
(51, 55, 6, '2026-10-17 10:06:59', NULL, NULL, NULL, 0, 'started', '2026-10-17 10:06:59', '2026-10-17 10:06:59'),
(52, 57, 4, NULL, NULL, NULL, '2026-10-17 11:50:07', 0, 'completed', '2026-10-17 11:50:07', '2026-10-17 11:50:07'),
(53, 46, 4, NULL, NULL, NULL, '2026-10-17 11:50:17', 0, 'completed', '2026-10-17 11:50:17', '2026-10-17 11:50:17'),
(54, 37, 5, '2026-10-17 15:32:46', NULL, NULL, NULL, 0, 'started', '2026-10-17 15:32:46', '2026-10-17 15:32:46'),
(55, 37, 5, NULL, '2026-10-17 15:32:59', NULL, NULL, 13, 'paused', '2026-10-17 15:32:59', '2026-10-17 15:32:59'),
(56, 55, 6, NULL, '2026-10-17 18:30:43', NULL, NULL, 30224, 'paused', '2026-10-17 18:30:43', '2026-10-17 18:30:43'),
(57, 65, 6, '2026-10-19 10:19:03', NULL, NULL, NULL, 0, 'started', '2026-10-19 10:19:03', '2026-10-19 10:19:03'),
(58, 70, 10, '2026-10-19 11:18:41', NULL, NULL, NULL, 0, 'started', '2026-10-19 11:18:41', '2026-10-19 11:18:41'),
(59, 70, 10, NULL, NULL, NULL, '2026-10-19 11:19:44', 63, 'completed', '2026-10-19 11:19:44', '2026-10-19 11:19:44'),
(60, 58, 5, '2026-10-19 13:01:43', NULL, NULL, NULL, 0, 'started', '2026-10-19 13:01:43', '2026-10-19 13:01:43'),
(61, 58, 5, NULL, NULL, NULL, '2026-10-19 13:03:05', 82, 'completed', '2026-10-19 13:03:05', '2026-10-19 13:03:05'),
(62, 82, 9, '2026-10-21 12:51:56', NULL, NULL, NULL, 0, 'started', '2026-10-21 12:51:56', '2026-10-21 12:51:56'),
(63, 82, 9, NULL, '2026-10-21 18:31:30', NULL, NULL, 20374, 'paused', '2026-10-21 18:31:30', '2026-10-21 18:31:30'),
(64, 59, 5, '2026-10-22 10:02:23', NULL, NULL, NULL, 0, 'started', '2026-10-22 10:02:23', '2026-10-22 10:02:23'),
(65, 82, 9, '2026-10-22 10:13:23', NULL, NULL, NULL, 0, 'started', '2026-10-22 10:13:23', '2026-10-22 10:13:23'),
(66, 71, 10, '2026-10-22 10:20:06', NULL, NULL, NULL, 0, 'started', '2026-10-22 10:20:06', '2026-10-22 10:20:06'),
(67, 71, 10, NULL, '2026-10-22 10:20:09', NULL, NULL, 3, 'paused', '2026-10-22 10:20:09', '2026-10-22 10:20:09'),
(68, 71, 10, '2026-10-22 10:20:09', NULL, NULL, NULL, 0, 'started', '2026-10-22 10:20:09', '2026-10-22 10:20:09'),
(69, 71, 10, NULL, NULL, NULL, '2026-10-22 10:20:31', 22, 'completed', '2026-10-22 10:20:31', '2026-10-22 10:20:31'),
(70, 72, 10, '2026-10-22 10:45:07', NULL, NULL, NULL, 0, 'started', '2026-10-22 10:45:07', '2026-10-22 10:45:07'),
(71, 72, 10, NULL, '2026-10-22 10:45:07', NULL, NULL, 0, 'paused', '2026-10-22 10:45:07', '2026-10-22 10:45:07'),
(72, 72, 10, '2026-10-22 10:45:07', NULL, NULL, NULL, 0, 'started', '2026-10-22 10:45:07', '2026-10-22 10:45:07'),
(73, 72, 10, NULL, '2026-10-22 10:45:07', NULL, NULL, 0, 'paused', '2026-10-22 10:45:07', '2026-10-22 10:45:07'),
(74, 72, 10, '2026-10-22 10:45:07', NULL, NULL, NULL, 0, 'started', '2026-10-22 10:45:07', '2026-10-22 10:45:07'),
(75, 72, 10, NULL, '2026-10-22 10:45:07', NULL, NULL, 0, 'paused', '2026-10-22 10:45:07', '2026-10-22 10:45:07'),
(76, 72, 10, '2026-10-22 10:45:07', NULL, NULL, NULL, 0, 'started', '2026-10-22 10:45:07', '2026-10-22 10:45:07'),
(77, 65, 6, NULL, '2026-10-22 10:54:00', NULL, NULL, 261297, 'paused', '2026-10-22 10:54:00', '2026-10-22 10:54:00'),
(78, 85, 6, '2026-10-22 11:02:02', NULL, NULL, NULL, 0, 'started', '2026-10-22 11:02:02', '2026-10-22 11:02:02'),
(79, 32, 8, '2026-10-22 12:21:37', NULL, NULL, NULL, 0, 'started', '2026-10-22 12:21:37', '2026-10-22 12:21:37'),
(80, 32, 8, NULL, NULL, NULL, '2026-10-22 13:15:34', 3237, 'completed', '2026-10-22 13:15:34', '2026-10-22 13:15:34'),
(81, 33, 8, '2026-10-22 13:15:50', NULL, NULL, NULL, 0, 'started', '2026-10-22 13:15:50', '2026-10-22 13:15:50'),
(82, 82, 9, NULL, '2026-10-22 14:00:49', NULL, NULL, 13646, 'paused', '2026-10-22 14:00:49', '2026-10-22 14:00:49'),
(83, 82, 9, '2026-10-22 16:11:38', NULL, NULL, NULL, 0, 'started', '2026-10-22 16:11:38', '2026-10-22 16:11:38'),
(84, 72, 10, NULL, NULL, NULL, '2026-10-22 18:18:51', 27224, 'completed', '2026-10-22 18:18:51', '2026-10-22 18:18:51'),
(85, 82, 9, NULL, '2026-10-22 18:32:07', NULL, NULL, 8429, 'paused', '2026-10-22 18:32:07', '2026-10-22 18:32:07'),
(86, 64, 8, NULL, NULL, NULL, '2026-10-23 10:21:26', 0, 'completed', '2026-10-23 10:21:26', '2026-10-23 10:21:26'),
(87, 73, 10, '2026-10-23 10:32:22', NULL, NULL, NULL, 0, 'started', '2026-10-23 10:32:22', '2026-10-23 10:32:22'),
(88, 73, 10, NULL, '2026-10-23 10:34:12', NULL, NULL, 110, 'paused', '2026-10-23 10:34:12', '2026-10-23 10:34:12'),
(89, 73, 10, '2026-10-23 10:34:12', NULL, NULL, NULL, 0, 'started', '2026-10-23 10:34:12', '2026-10-23 10:34:12'),
(90, 73, 10, NULL, '2026-10-23 10:48:55', NULL, NULL, 883, 'paused', '2026-10-23 10:48:55', '2026-10-23 10:48:55'),
(91, 73, 10, '2026-10-23 10:48:55', NULL, NULL, NULL, 0, 'started', '2026-10-23 10:48:55', '2026-10-23 10:48:55'),
(92, 73, 10, NULL, '2026-10-23 10:48:55', NULL, NULL, 0, 'paused', '2026-10-23 10:48:55', '2026-10-23 10:48:55'),
(93, 73, 10, '2026-10-23 10:48:55', NULL, NULL, NULL, 0, 'started', '2026-10-23 10:48:55', '2026-10-23 10:48:55'),
(94, 59, 5, NULL, NULL, NULL, '2026-10-23 12:18:29', 94566, 'completed', '2026-10-23 12:18:29', '2026-10-23 12:18:29'),
(95, 73, 10, NULL, NULL, NULL, '2026-10-24 09:44:52', 82557, 'completed', '2026-10-24 09:44:52', '2026-10-24 09:44:52'),
(96, 74, 10, '2026-10-24 15:35:17', NULL, NULL, NULL, 0, 'started', '2026-10-24 15:35:17', '2026-10-24 15:35:17'),
(97, 37, 5, '2026-10-26 10:09:36', NULL, NULL, NULL, 0, 'started', '2026-10-26 10:09:36', '2026-10-26 10:09:36'),
(98, 37, 5, NULL, NULL, NULL, '2026-10-26 10:10:03', 27, 'completed', '2026-10-26 10:10:03', '2026-10-26 10:10:03'),
(99, 38, 5, '2026-10-26 10:10:18', NULL, NULL, NULL, 0, 'started', '2026-10-26 10:10:18', '2026-10-26 10:10:18'),
(100, 38, 5, NULL, NULL, NULL, '2026-10-26 10:10:30', 12, 'completed', '2026-10-26 10:10:30', '2026-10-26 10:10:30'),
(101, 51, 5, '2026-10-26 10:11:15', NULL, NULL, NULL, 0, 'started', '2026-10-26 10:11:15', '2026-10-26 10:11:15'),
(102, 51, 5, NULL, NULL, NULL, '2026-10-26 10:12:40', 85, 'completed', '2026-10-26 10:12:40', '2026-10-26 10:12:40'),
(103, 85, 6, NULL, '2026-10-26 10:43:39', NULL, NULL, 344497, 'paused', '2026-10-26 10:43:39', '2026-10-26 10:43:39'),
(104, 74, 10, NULL, '2026-10-26 10:48:12', NULL, NULL, 155575, 'paused', '2026-10-26 10:48:12', '2026-10-26 10:48:12'),
(105, 86, 6, '2026-10-26 10:57:52', NULL, NULL, NULL, 0, 'started', '2026-10-26 10:57:52', '2026-10-26 10:57:52'),
(106, 86, 6, NULL, '2026-10-26 12:44:24', NULL, NULL, 6392, 'paused', '2026-10-26 12:44:24', '2026-10-26 12:44:24'),
(107, 85, 6, '2026-10-26 12:44:34', NULL, NULL, NULL, 0, 'started', '2026-10-26 12:44:34', '2026-10-26 12:44:34'),
(108, 44, 5, '2026-10-26 13:05:53', NULL, NULL, NULL, 0, 'started', '2026-10-26 13:05:53', '2026-10-26 13:05:53'),
(109, 44, 5, NULL, NULL, NULL, '2026-10-26 13:08:47', 174, 'completed', '2026-10-26 13:08:47', '2026-10-26 13:08:47'),
(110, 85, 6, NULL, '2026-10-26 13:39:03', NULL, NULL, 3269, 'paused', '2026-10-26 13:39:03', '2026-10-26 13:39:03'),
(111, 85, 6, '2026-10-26 13:39:50', NULL, NULL, NULL, 0, 'started', '2026-10-26 13:39:50', '2026-10-26 13:39:50'),
(112, 85, 6, NULL, '2026-10-26 13:50:04', NULL, NULL, 614, 'paused', '2026-10-26 13:50:04', '2026-10-26 13:50:04'),
(113, 53, 5, '2026-10-26 13:50:12', NULL, NULL, NULL, 0, 'started', '2026-10-26 13:50:12', '2026-10-26 13:50:12'),
(114, 53, 5, NULL, NULL, NULL, '2026-10-26 13:50:47', 35, 'completed', '2026-10-26 13:50:47', '2026-10-26 13:50:47'),
(115, 88, 5, '2026-10-26 14:08:51', NULL, NULL, NULL, 0, 'started', '2026-10-26 14:08:51', '2026-10-26 14:08:51'),
(116, 88, 5, NULL, NULL, NULL, '2026-10-26 14:10:14', 83, 'completed', '2026-10-26 14:10:14', '2026-10-26 14:10:14'),
(117, 86, 6, '2026-10-26 15:32:37', NULL, NULL, NULL, 0, 'started', '2026-10-26 15:32:37', '2026-10-26 15:32:37'),
(118, 86, 6, NULL, '2026-10-26 15:32:39', NULL, NULL, 2, 'paused', '2026-10-26 15:32:39', '2026-10-26 15:32:39'),
(119, 86, 6, '2026-10-26 15:32:39', NULL, NULL, NULL, 0, 'started', '2026-10-26 15:32:39', '2026-10-26 15:32:39'),
(120, 86, 6, NULL, '2026-10-26 15:32:43', NULL, NULL, 4, 'paused', '2026-10-26 15:32:43', '2026-10-26 15:32:43'),
(121, 89, 5, '2026-10-26 15:50:29', NULL, NULL, NULL, 0, 'started', '2026-10-26 15:50:29', '2026-10-26 15:50:29'),
(122, 89, 5, NULL, NULL, NULL, '2026-10-26 15:50:40', 11, 'completed', '2026-10-26 15:50:40', '2026-10-26 15:50:40'),
(123, 85, 6, '2026-10-27 10:55:50', NULL, NULL, NULL, 0, 'started', '2026-10-27 10:55:50', '2026-10-27 10:55:50'),
(124, 74, 10, '2026-10-27 11:48:32', NULL, NULL, NULL, 0, 'started', '2026-10-27 11:48:32', '2026-10-27 11:48:32'),
(125, 62, 5, '2026-10-27 12:17:03', NULL, NULL, NULL, 0, 'started', '2026-10-27 12:17:03', '2026-10-27 12:17:03'),
(126, 62, 5, NULL, NULL, NULL, '2026-10-27 12:18:47', 104, 'completed', '2026-10-27 12:18:47', '2026-10-27 12:18:47'),
(127, 49, 5, '2026-10-27 12:19:24', NULL, NULL, NULL, 0, 'started', '2026-10-27 12:19:24', '2026-10-27 12:19:24'),
(128, 49, 5, NULL, NULL, NULL, '2026-10-27 12:21:17', 113, 'completed', '2026-10-27 12:21:17', '2026-10-27 12:21:17'),
(129, 74, 10, NULL, '2026-10-27 13:06:08', NULL, NULL, 4656, 'paused', '2026-10-27 13:06:08', '2026-10-27 13:06:08'),
(130, 74, 10, '2026-10-27 13:06:08', NULL, NULL, NULL, 0, 'started', '2026-10-27 13:06:08', '2026-10-27 13:06:08'),
(131, 74, 10, NULL, '2026-10-28 10:05:07', NULL, NULL, 75539, 'paused', '2026-10-28 10:05:07', '2026-10-28 10:05:07'),
(132, 85, 6, NULL, '2026-10-28 10:08:21', NULL, NULL, 83551, 'paused', '2026-10-28 10:08:21', '2026-10-28 10:08:21'),
(133, 76, 10, '2026-10-30 11:54:12', NULL, NULL, NULL, 0, 'started', '2026-10-30 11:54:12', '2026-10-30 11:54:12'),
(134, 90, 5, '2026-10-30 12:36:42', NULL, NULL, NULL, 0, 'started', '2026-10-30 12:36:42', '2026-10-30 12:36:42'),
(135, 90, 5, NULL, NULL, NULL, '2026-10-30 12:36:54', 12, 'completed', '2026-10-30 12:36:54', '2026-10-30 12:36:54'),
(136, 52, 5, '2026-10-30 12:56:35', NULL, NULL, NULL, 0, 'started', '2026-10-30 12:56:35', '2026-10-30 12:56:35'),
(137, 52, 5, NULL, NULL, NULL, '2026-10-30 12:56:39', 4, 'completed', '2026-10-30 12:56:39', '2026-10-30 12:56:39'),
(138, 76, 10, NULL, NULL, NULL, '2026-10-30 13:05:24', 4272, 'completed', '2026-10-30 13:05:24', '2026-10-30 13:05:24'),
(139, 77, 10, '2026-10-30 17:14:13', NULL, NULL, NULL, 0, 'started', '2026-10-30 17:14:13', '2026-10-30 17:14:13'),
(140, 77, 10, NULL, '2026-11-02 09:49:23', NULL, NULL, 232510, 'paused', '2026-11-02 09:49:23', '2026-11-02 09:49:23'),
(141, 93, 9, '2026-11-03 11:58:41', NULL, NULL, NULL, 0, 'started', '2026-11-03 11:58:41', '2026-11-03 11:58:41'),
(142, 77, 10, '2026-11-03 13:30:31', NULL, NULL, NULL, 0, 'started', '2026-11-03 13:30:31', '2026-11-03 13:30:31'),
(143, 77, 10, NULL, '2026-11-03 13:30:31', NULL, NULL, 0, 'paused', '2026-11-03 13:30:31', '2026-11-03 13:30:31'),
(144, 77, 10, '2026-11-03 13:30:31', NULL, NULL, NULL, 0, 'started', '2026-11-03 13:30:31', '2026-11-03 13:30:31'),
(145, 77, 10, NULL, NULL, NULL, '2026-11-03 13:32:41', 130, 'completed', '2026-11-03 13:32:41', '2026-11-03 13:32:41'),
(146, 93, 9, NULL, '2026-11-03 18:33:59', NULL, NULL, 23718, 'paused', '2026-11-03 18:33:59', '2026-11-03 18:33:59');

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
(1, 1, 1, 'KD-EMP-001', 'Kartikeyan', 'kartikeyan@kraftingdigital.com', 'kartikeyan', '$2y$10$vkIWq60IxBxk4ygo1JbWEOjRpPAbJVn52/umceITFpty.71IB9JkS', '$2y$10$vkIWq60IxBxk4ygo1JbWEOjRpPAbJVn52/umceITFpty.71IB9JkS', '+91 9876543210', 'user_1_1792046457.jpeg', 'System Administrator & CEO', '09:40 AM - 06:30 PM (Fixed Office)', 'ADMIN', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:55'),
(2, 2, 1, 'KD-EMP-002', 'Mrinali', 'mrinali@kraftingdigital.com', 'mrinali', '$2y$10$U16XicqOoe37wy0nD6bCWuNtDkRg5a26pUjRdm.TEu38uA29.Xo8u', '$2y$10$U16XicqOoe37wy0nD6bCWuNtDkRg5a26pUjRdm.TEu38uA29.Xo8u', '+91 9876543211', 'default_avatar.png', 'Operations Manager', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:55'),
(3, 3, 6, 'KD-EMP-003', 'Udit', 'contact@kraftingdigital.com', 'contact', '$2y$10$UPD6X5xnC/YCy1Cm47V1fOT0KoaMuZ1YgPI.GfT2UZyeJdKKUvp3y', '$2y$10$UPD6X5xnC/YCy1Cm47V1fOT0KoaMuZ1YgPI.GfT2UZyeJdKKUvp3y', '+91 9876543212', 'default_avatar.png', 'Product Manager', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:55'),
(4, 4, 2, 'KD-EMP-004', 'Rahul', 'finance@kraftingdigital.com', 'finance', '$2y$10$ew7r/g2uWyq3u0BVrTu65ekPhWfPrrkEuGyT6pNrM6w0OxpS32kSO', '$2y$10$ew7r/g2uWyq3u0BVrTu65ekPhWfPrrkEuGyT6pNrM6w0OxpS32kSO', '+91 9876543213', 'user_4_1792046335.jpeg', 'Senior Fullstack Developer', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:55'),
(5, 5, 3, 'KD-EMP-005', 'Uvaish', 'uvaish@kraftingdigital.com', 'uvaish', '$2y$10$CsYFnXhx.jF.vepxkiO.weVGFXGTuhEeYUQFZYaWSMXzDO/KVNHIm', '$2y$10$CsYFnXhx.jF.vepxkiO.weVGFXGTuhEeYUQFZYaWSMXzDO/KVNHIm', '+91 9876543214', 'default_avatar.png', 'SEO Lead Manager', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:55'),
(6, 6, 3, 'KD-EMP-006', 'Shivanshu', 'shivanshu@kraftingdigital.com', 'shivanshu', '$2y$10$Me3ki1iHvERZypnLfLJ7oOC5SywrGY/DfM9m89r3/jYiXbjK1aCla', '$2y$10$Me3ki1iHvERZypnLfLJ7oOC5SywrGY/DfM9m89r3/jYiXbjK1aCla', '+91 9876543215', 'default_avatar.png', 'Senior SEO Executive', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:55'),
(7, 7, 4, 'KD-EMP-007', 'Sarthak', 'sarthak@kraftingdigital.com', 'sarthak', '$2y$10$7d.x9PyC5IoqnfofonmUyOzRvrM7nYBTru.nWXWGGZSLDBfOSgcey', '$2y$10$7d.x9PyC5IoqnfofonmUyOzRvrM7nYBTru.nWXWGGZSLDBfOSgcey', '+91 9876543216', 'default_avatar.png', 'Lead UI/UX Graphic Designer', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:55'),
(8, 8, 5, 'KD-EMP-008', 'Priyansh', 'priyansh@kraftingdigital.com', 'priyansh', '$2y$10$aZHo6SdzD07aYUxv1puNnulJDz4HzX38VYtmbw/cnbiB4UXBMuWuy', '$2y$10$aZHo6SdzD07aYUxv1puNnulJDz4HzX38VYtmbw/cnbiB4UXBMuWuy', '+91 9876543217', 'default_avatar.png', 'Social Media Strategist', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:56'),
(9, 9, 2, 'KD-EMP-009', 'Abhay', 'abhay@kraftingdigital.com', 'abhay', '$2y$10$V2R/BXxajs3nY6NIx1Zq9uGmbel/MagjsgvVviehHRrk50RVcxj1a', '$2y$10$V2R/BXxajs3nY6NIx1Zq9uGmbel/MagjsgvVviehHRrk50RVcxj1a', '+91 9876543218', 'default_avatar.png', 'Intern Frontend Developer', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:56'),
(10, 10, 3, 'KD-EMP-010', 'Aman', 'aman@kraftingdigital.com', 'aman', '$2y$10$tTMPDBBeU8mOFyC9pKprPOqk0cZ.fUZXI0Wcs/EHi4YLpjAbnVcUq', '$2y$10$tTMPDBBeU8mOFyC9pKprPOqk0cZ.fUZXI0Wcs/EHi4YLpjAbnVcUq', '+91 9876543219', 'default_avatar.png', 'Intern SEO Analyst', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-08-19 07:09:49', '2026-11-19 10:16:56'),
(12, 10, 3, 'EMP-011', 'Anjali Singh', 'anjalisingh@kraftingdigital.com', NULL, '$2y$10$WJJVoepPnBIVfKv30Vyqx.HFSy/dXQVgusvpKBEut5wVJdfGI35cm', '', '', 'default_avatar.png', 'SEO', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-09-28 17:03:12', '2026-09-28 17:03:12'),
(13, 7, 9, 'EMP-013', 'vicky', 'vicky@kraftingdigital.com', NULL, '$2y$10$ojKF.F/xTWo6k9.CF/8MW.xV5T0l44zkeqtiMNDjN3u9gn6N83Jfe', '', '', 'default_avatar.png', 'Photographer', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-10-14 17:53:21', '2026-10-15 17:10:29'),
(14, 11, 14, 'KD-EMP-011', 'PRABHAT', 'prabhat@kraftingdigital.com', 'prabhat', '$2y$10$J3zS6tHSJd2lB.Z1pa45EeBz9VUVrYTgsx5Jxm7XX8xsueHQD/UsO', '$2y$10$J3zS6tHSJd2lB.Z1pa45EeBz9VUVrYTgsx5Jxm7XX8xsueHQD/UsO', '+91 9876543220', 'default_avatar.png', 'ADS EXPERT', '09:40 AM - 06:30 PM (Fixed Office)', 'STAFF', 'active', NULL, NULL, '2026-10-15 13:19:02', '2026-11-19 10:16:56');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `wdbi_reason_text_54b3c749d3ee` (`reason_text`);

--
-- Indexes for table `ai_action_plans`
--
ALTER TABLE `ai_action_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_ai_plan_token` (`plan_token`),
  ADD KEY `idx_ai_plan_user` (`user_id`),
  ADD KEY `idx_ai_plan_status` (`status`);

--
-- Indexes for table `ai_chat_messages`
--
ALTER TABLE `ai_chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ai_chat_user_conv` (`user_id`,`conversation_id`);

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
-- Indexes for table `authorized_ips`
--
ALTER TABLE `authorized_ips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ip_address` (`ip_address`);

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
-- Indexes for table `emergency_task_requests`
--
ALTER TABLE `emergency_task_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_emg_task` (`task_id`),
  ADD KEY `idx_emg_employee` (`employee_id`),
  ADD KEY `idx_emg_status` (`status`);

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
-- Indexes for table `task_flags`
--
ALTER TABLE `task_flags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_flag_task` (`task_id`),
  ADD KEY `idx_flag_employee` (`employee_id`),
  ADD KEY `idx_flag_status` (`status`);

--
-- Indexes for table `task_flag_comments`
--
ALTER TABLE `task_flag_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_flag_comment_flag` (`flag_id`),
  ADD KEY `idx_flag_comment_user` (`user_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2793;

--
-- AUTO_INCREMENT for table `activity_reasons`
--
ALTER TABLE `activity_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `ai_action_plans`
--
ALTER TABLE `ai_action_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_chat_messages`
--
ALTER TABLE `ai_chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=421;

--
-- AUTO_INCREMENT for table `attendance_breaks`
--
ALTER TABLE `attendance_breaks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `authorized_ips`
--
ALTER TABLE `authorized_ips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=406545;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `daily_work_reports`
--
ALTER TABLE `daily_work_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `email_logs`
--
ALTER TABLE `email_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emergency_task_requests`
--
ALTER TABLE `emergency_task_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee_activities`
--
ALTER TABLE `employee_activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=423;

--
-- AUTO_INCREMENT for table `employee_profiles`
--
ALTER TABLE `employee_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `employee_salaries`
--
ALTER TABLE `employee_salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `meeting_participants`
--
ALTER TABLE `meeting_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=389;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `task_comments`
--
ALTER TABLE `task_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `task_files`
--
ALTER TABLE `task_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `task_flags`
--
ALTER TABLE `task_flags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `task_flag_comments`
--
ALTER TABLE `task_flag_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `task_logs`
--
ALTER TABLE `task_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `task_timer`
--
ALTER TABLE `task_timer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `task_time_logs`
--
ALTER TABLE `task_time_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
