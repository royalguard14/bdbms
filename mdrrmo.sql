-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2024 at 03:05 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mdrrmo`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangay`
--

CREATE TABLE `barangay` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `city_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barangay`
--

INSERT INTO `barangay` (`id`, `name`, `city_id`, `created_at`, `updated_at`) VALUES
(1, 'Aclan', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(2, 'Amontay', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(3, 'Ata-atahon', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(4, 'Barangay 1 (Poblacion)', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(5, 'Barangay 2 (Poblacion)', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(6, 'Barangay 3 (Poblacion)', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(7, 'Barangay 4 (Poblacion)', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(8, 'Barangay 5 (Poblacion)', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(9, 'Barangay 6 (Poblacion)', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(10, 'Barangay 7 (Poblacion)', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(11, 'Camagong', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(12, 'Cubi-Cubi', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(13, 'Culit', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(14, 'Jaguimitan', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(15, 'Kinabjangan', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(16, 'Punta', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(17, 'Santa Ana', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(18, 'Talisay', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34'),
(19, 'Triangulo', 1, '2024-09-19 23:18:34', '2024-09-19 23:18:34');

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`id`, `name`) VALUES
(1, 'Nasipit');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Create Role', '2024-09-17 15:51:42', '2024-09-17 15:51:42'),
(2, 'Read Role', '2024-09-17 15:53:32', '2024-09-17 15:53:32'),
(3, 'Update Role', '2024-09-17 15:57:23', '2024-09-17 15:57:23'),
(4, 'Delete Role', '2024-09-17 15:57:28', '2024-09-17 15:57:28'),
(5, 'Create Permission', '2024-09-17 15:57:54', '2024-09-17 15:57:54'),
(6, 'Read Permission', '2024-09-17 15:58:00', '2024-09-17 15:58:00'),
(7, 'Update Permission', '2024-09-17 15:58:05', '2024-09-17 15:58:05'),
(8, 'Delete Permission', '2024-09-17 15:58:12', '2024-09-17 15:58:12'),
(9, 'Grant Permission', '2024-09-17 15:58:21', '2024-09-17 15:58:21'),
(10, 'Create City', '2024-09-17 16:00:13', '2024-09-17 16:00:13'),
(11, 'Read City', '2024-09-17 16:00:19', '2024-09-17 16:00:19'),
(12, 'Update City', '2024-09-17 16:00:26', '2024-09-17 16:00:26'),
(13, 'Delete City', '2024-09-17 16:00:45', '2024-09-17 16:00:45'),
(14, 'Create Barangay', '2024-09-17 16:00:54', '2024-09-17 16:00:54'),
(15, 'Read Barangay', '2024-09-17 16:00:57', '2024-09-17 16:00:57'),
(16, 'Update Barangay', '2024-09-17 16:01:03', '2024-09-17 16:01:03'),
(17, 'Delete Barangay', '2024-09-17 16:01:08', '2024-09-17 16:01:08'),
(18, 'Create Account', '2024-09-18 01:48:18', '2024-09-18 01:48:18'),
(19, 'Read Account', '2024-09-18 01:48:24', '2024-09-18 01:48:24'),
(20, 'Update Account', '2024-09-18 01:48:43', '2024-09-18 01:48:43'),
(21, 'Delete Account', '2024-09-18 01:48:50', '2024-09-18 01:48:50'),
(22, 'Upload Report', '2024-09-20 10:42:36', '2024-09-20 10:42:36'),
(23, 'View Submitted', '2024-09-20 10:42:42', '2024-09-20 10:42:42'),
(24, 'View Accepted', '2024-09-20 10:42:47', '2024-09-20 10:42:47'),
(25, 'View Reverted', '2024-09-20 10:42:52', '2024-09-20 10:42:52'),
(26, 'View Archived', '2024-09-20 10:42:59', '2024-09-20 10:42:59'),
(27, 'Update Report', '2024-09-26 11:24:03', '2024-09-26 11:24:03'),
(28, 'Delete Report', '2024-09-26 11:24:05', '2024-09-26 11:24:05'),
(29, 'Read Report', '2024-09-26 11:24:13', '2024-09-26 11:24:13'),
(30, 'ToSubmit', '2024-09-28 02:41:58', '2024-09-28 02:41:58'),
(31, 'toVerified', '2024-09-28 02:42:08', '2024-09-28 02:42:08'),
(32, 'ToConfirm', '2024-09-28 02:42:59', '2024-09-28 02:42:59'),
(33, 'toAccept', '2024-09-28 02:43:12', '2024-09-28 02:43:12'),
(34, 'toRevert', '2024-09-28 02:43:30', '2024-09-28 02:43:30'),
(35, 'toArchived', '2024-09-28 02:43:44', '2024-09-28 02:43:44'),
(36, 'Read Confirm', '2024-09-28 09:42:16', '2024-09-28 09:42:16');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) NOT NULL,
  `suffix` varchar(9) DEFAULT NULL,
  `birthdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `birthplace` varchar(50) NOT NULL,
  `contact_number` varchar(16) NOT NULL,
  `address` varchar(255) NOT NULL,
  `profile_pic` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `birthplace`, `contact_number`, `address`, `profile_pic`, `created_at`, `updated_at`) VALUES
(1, 7, 'zhie', 'atara', 'Bautista', '', '2024-10-01 12:51:33', 'manila', '092772944457', 'asdasdasdasdasd', '', '2024-09-29 13:05:04', '2024-10-01 12:00:33'),
(3, 6, 'Mark', '', 'Does', '', '2024-10-01 13:05:03', '', '', '', 'assets/profilePic/6.png', '2024-10-01 12:23:16', '2024-10-01 13:02:47');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `form_type` varchar(100) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `date_uploaded` timestamp NOT NULL DEFAULT current_timestamp(),
  `period_covered` varchar(100) DEFAULT NULL,
  `status` enum('Uploaded','Submitted','Verified','Confirm','Accepted','Reverted','Archived') DEFAULT 'Uploaded',
  `user_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `brgy_id` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remark` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `title`, `form_type`, `file_name`, `date_uploaded`, `period_covered`, `status`, `user_id`, `city_id`, `brgy_id`, `created_at`, `remark`) VALUES
(1, 'xasasdasd', '1', 'ar_day_lessons_from_sonh_and_islamic_laws.pdf', '2024-09-30 09:52:42', '2024-09-30', 'Uploaded', 7, 1, 1, '2024-09-30 09:52:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report_status_logs`
--

CREATE TABLE `report_status_logs` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `previous_status` enum('Uploaded','Submitted','Verified','Confirm','Accepted','Reverted','Archived') DEFAULT NULL,
  `new_status` enum('Uploaded','Submitted','Verified','Confirm','Accepted','Reverted','Archived') DEFAULT NULL,
  `changed_by` int(11) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report_status_logs`
--

INSERT INTO `report_status_logs` (`id`, `report_id`, `previous_status`, `new_status`, `changed_by`, `changed_at`) VALUES
(1, 1, NULL, 'Uploaded', 7, '2024-09-30 09:52:42');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `module_id` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `module_id`, `created_at`, `updated_at`) VALUES
(1, 'Zear Developer', NULL, '2024-09-03 12:10:10', '2024-09-03 12:10:10'),
(16, 'HDRRMO ADMIN', NULL, '2024-09-17 14:58:05', '2024-09-17 14:58:05'),
(17, 'ADMIN ASSISTANT', NULL, '2024-09-17 14:58:18', '2024-09-17 14:58:18'),
(18, 'BRGY USER', NULL, '2024-09-17 14:58:27', '2024-09-17 14:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(16, 14),
(16, 15),
(16, 31),
(16, 32),
(16, 34),
(16, 36),
(17, 14),
(17, 15),
(17, 16),
(17, 23),
(17, 24),
(17, 25),
(17, 31),
(17, 33),
(17, 34),
(17, 36),
(18, 22),
(18, 23),
(18, 24),
(18, 25),
(18, 26),
(18, 27),
(18, 29),
(18, 30),
(18, 35);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `city_id` int(11) NOT NULL DEFAULT 0,
  `brgy_id` int(11) NOT NULL DEFAULT 0,
  `is_active` int(11) NOT NULL DEFAULT 1,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role_id`, `city_id`, `brgy_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$pPx1uOWAuFhxbmbWgIfkQeS1cafJGq0kjIXk.OCYPNg0aTsk09tpG', 1, 0, 0, 1, 0, '2024-09-03 00:38:27', '2024-09-03 00:38:27'),
(5, 'MDRRMO', '$2y$10$pPx1uOWAuFhxbmbWgIfkQeS1cafJGq0kjIXk.OCYPNg0aTsk09tpG', 16, 1, 0, 1, 0, '2024-09-18 21:21:45', '2024-09-18 21:21:45'),
(6, 'Assistant', '$2y$10$pPx1uOWAuFhxbmbWgIfkQeS1cafJGq0kjIXk.OCYPNg0aTsk09tpG', 17, 1, 0, 1, 0, '2024-09-19 14:25:19', '2024-09-19 14:25:19'),
(7, 'BRGY', '$2y$10$pPx1uOWAuFhxbmbWgIfkQeS1cafJGq0kjIXk.OCYPNg0aTsk09tpG', 18, 1, 13, 1, 0, '2024-09-19 14:26:49', '2024-10-01 01:30:14'),
(8, 'zzzz', '$2y$10$Kv1pKfJf24vjZPKAvN6Q2.olACTOgooJFJwa/hbR7AwcIpZAsC3OO', 1, 1, 16, 1, 0, '2024-09-30 10:17:46', '2024-10-01 01:30:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangay`
--
ALTER TABLE `barangay`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`,`city_id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_status_logs`
--
ALTER TABLE `report_status_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangay`
--
ALTER TABLE `barangay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_status_logs`
--
ALTER TABLE `report_status_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangay`
--
ALTER TABLE `barangay`
  ADD CONSTRAINT `barangay_ibfk_1` FOREIGN KEY (`city_id`) REFERENCES `city` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
