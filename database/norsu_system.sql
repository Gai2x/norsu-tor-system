-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2026 at 07:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `norsu_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_action_logs`
--

CREATE TABLE `admin_action_logs` (
  `id` int(11) NOT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `target_type` varchar(100) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_action_logs`
--

INSERT INTO `admin_action_logs` (`id`, `actor_id`, `action`, `target_type`, `target_id`, `details`, `created_at`) VALUES
(1, 14, 'override_request_status', 'requests', 5, 'Set status to pending', '2026-05-05 03:53:04');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `appointment_type` varchar(100) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT 30,
  `purpose` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled','completed') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `advisor_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `appointment_type`, `appointment_date`, `appointment_time`, `schedule_id`, `service_type`, `duration`, `purpose`, `status`, `admin_notes`, `created_at`, `updated_at`, `advisor_name`) VALUES
(2, 2, '2026-04-25', '0000-00-00', '00:00:00', NULL, NULL, 30, NULL, 'approved', NULL, '2026-04-25 13:42:51', '2026-04-25 14:38:44', NULL),
(3, 8, '2026-04-27', '0000-00-00', '00:00:00', NULL, NULL, 30, NULL, 'approved', NULL, '2026-04-25 15:33:04', '2026-04-25 15:33:19', NULL),
(4, 8, '2026-04-27', '0000-00-00', '00:00:00', NULL, NULL, 30, NULL, 'rejected', NULL, '2026-04-25 15:33:11', '2026-04-25 17:26:18', NULL),
(6, 2, '2026-04-29', '0000-00-00', '00:00:00', NULL, NULL, 30, 'asdda', 'pending', NULL, '2026-04-28 16:10:08', NULL, NULL),
(7, 2, 'Enrollment Assistance', '2026-05-06', '10:00:00', 2, NULL, 30, 'aaaa', 'approved', NULL, '2026-05-05 13:04:41', '2026-05-05 13:05:15', 'Maam Archel');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_types`
--

CREATE TABLE `appointment_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT 30,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_types`
--

INSERT INTO `appointment_types` (`id`, `name`, `description`, `duration_minutes`, `is_active`, `created_at`) VALUES
(1, 'Grade Consultation', NULL, 30, 1, '2026-04-07 03:27:05'),
(2, 'Enrollment Assistance', NULL, 20, 1, '2026-04-07 03:27:05'),
(3, 'Document Request', NULL, 15, 1, '2026-04-07 03:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `one_time_requests`
--

CREATE TABLE `one_time_requests` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `one_time_requests`
--

INSERT INTO `one_time_requests` (`id`, `student_id`, `fullname`, `contact`, `email`, `service_type`, `notes`, `status`, `created_at`) VALUES
(1, '2021-1312-23', 'teo Lacsamana', '09663471004', 'roycerubiozerna@gmail.com', 'TOR', '123', 'pending', '2026-04-26 03:47:15'),
(2, '2021-1312-23', 'teo Lacsamana', 'aasd', 'roycerubiozerna@gmail.com', 'TOR', 'asdada', 'approved', '2026-04-26 04:04:25'),
(3, '2021-13122', 'teo Lacsamana', '09663471004', 'roycerubiozerna@gmail.com', 'TOR', 'aaa', 'rejected', '2026-04-26 05:06:15');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `year_level` varchar(50) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `document_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `requests`
--

INSERT INTO `requests` (`id`, `user_id`, `service_type`, `notes`, `year_level`, `contact_number`, `document_file`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Document Request', 'I need TOR for job application', '3rd Year', '09663471004', '', 'rejected', '2026-04-07 12:02:11', '2026-04-25 15:31:17'),
(4, 2, 'Document Request', 'asdfg', '1st Year', '', '1777363791_2_Screenshot_2026_04_28_111756_png', 'pending', '2026-04-28 16:09:51', NULL),
(5, 2, 'Document Request', 'sssssssa', '3rd Year', '', '1777444293_2_c9a745ce773173ab8c151d71cd3259b3__wild_rabbit_bunny_jpg', 'pending', '2026-04-29 14:31:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `day_of_week` varchar(20) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `office` varchar(100) DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `max_slots` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `day_of_week`, `start_time`, `end_time`, `office`, `service_type`, `max_slots`, `status`) VALUES
(1, 'Tuesday', '10:00:00', '16:00:00', 'Guidance', NULL, 10, 'Available'),
(2, 'Wednesday', '10:00:00', '10:30:00', 'Admin Office', 'Enrollment Assistance', 1, 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','student') NOT NULL DEFAULT 'student',
  `middle_name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `name`, `course`, `email`, `password`, `role`, `middle_name`, `dob`, `profile_image`, `phone_number`, `created_at`, `updated_at`) VALUES
(2, '2023-00927', 'Daniel Saavedra', 'BSIT', 'dzdanielsaavedra@gmail.com', '$2y$10$Mbo7.x50kxkLgQ7u/DAn9OrtvqM2wg4scxoDUFkYyQ4V8QYwW/3Du', 'student', 'Arsenal', '2005-09-11', '1776675027_1776669288_1776666102_IMG_20250622_115111_614.jpg', '09663471004', '2026-04-25 06:10:07', '2026-04-25 06:22:43'),
(3, '2021-13122', 'juan dela cruz', 'BSF', 'saavedra@gmail.com', '$2y$10$0q4777IkLWN5EanZEh7n1Oulydni75pcgLd6PGIQ/saMQdOdcql76', 'student', NULL, NULL, NULL, NULL, '2026-04-25 06:10:07', '2026-04-25 06:38:17'),
(4, '2021-22234', 'juan dela cruz sn', 'BSIT', 'saavedra0@gmail.com', '$2y$10$3Jo7wKu/dl/wm0McLnlEr.96zxmm/.k3z/8spuN6.M.G7BlW1FS52', 'student', NULL, NULL, NULL, NULL, '2026-04-25 06:10:07', '2026-04-25 06:22:43'),
(8, '0000-00000', 'Administrator', 'ADMIN', 'admin@norsu.edu', '$2y$10$DFu8bFKGSknPkVMLblcJ8.oLTD.25XqqIJhGS6jqIVc.aC3R8Rjuy', 'admin', NULL, NULL, NULL, NULL, '2026-04-25 06:10:07', '2026-04-25 06:22:43'),
(11, '2021-22227', 'Ran Rey Naling', 'BSIT', 'roycerubiozerna@gmail.com', '$2y$10$pY0QKC/PRh6pkj2Ckf6.HuN32K0IukX9vjjqeO4qY6ThgdZyZsZUK', 'student', NULL, NULL, NULL, NULL, '2026-04-28 03:10:47', '2026-04-28 03:10:47'),
(12, '2021-22220', 'Agala Joaquin Andrew C.', 'BSIT', 'joaquinagala6117@gmail.com', '$2y$10$6E6nqmk5//VG6SMOnmZ3FevKhXiV6C0keX.kOwQ4hJvgzPPmie7tG', 'student', NULL, NULL, NULL, NULL, '2026-04-28 03:21:16', '2026-04-28 03:21:16'),
(13, '2021-13313', 'James Andrew Dalmacio', 'BSIT', 'jamesandrewdalmacio@gmail.com', '$2y$10$wF2iUuHS7v2gbTR4KQopi.ToiqK2suCh2f62mwIzRzDkeCf/IuzVy', 'student', NULL, NULL, NULL, NULL, '2026-04-28 04:24:15', '2026-04-28 04:24:15'),
(14, '', 'Super Admin', '', 'superadmin@norsu.edu', '$2y$10$Ja8h9cfIYnwDEPuU4ZseU.FFBO79NJBT7y/enmnbDCrbPlb6n19gy', 'super_admin', NULL, NULL, NULL, NULL, '2026-05-05 03:51:00', '2026-05-05 03:51:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_actor_id` (`actor_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_appointment_date` (`appointment_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_schedule_id` (`schedule_id`);

--
-- Indexes for table `appointment_types`
--
ALTER TABLE `appointment_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `one_time_requests`
--
ALTER TABLE `one_time_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_students_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_action_logs`
--
ALTER TABLE `admin_action_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `appointment_types`
--
ALTER TABLE `appointment_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `one_time_requests`
--
ALTER TABLE `one_time_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
