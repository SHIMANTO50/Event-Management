-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 01, 2025 at 07:36 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `id` int NOT NULL,
  `event_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `registered_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`id`, `event_id`, `user_id`, `registered_at`) VALUES
(1, 1, 5, '2025-01-26 10:28:23'),
(2, 4, 5, '2025-01-26 11:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `capacity` int DEFAULT NULL,
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `capacity`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'A', 'This is event B', 11, 5, '2025-01-26 10:26:27', '2025-01-26 10:56:33'),
(3, 'Bdsdf asf sdaf dsf ds', 'dfdsfdsfd', 10, 5, '2025-01-26 11:14:28', NULL),
(4, 'C', 'xcfvbxcvbvxcbv', 40, 5, '2025-01-26 11:14:49', NULL),
(5, 'gfdgf', 'gfdgfdgfd', 60, 5, '2025-01-26 11:15:04', NULL),
(6, 'fsddsf', 'sdfgdsfgds', 20, 5, '2025-01-26 11:15:17', NULL),
(7, 'bbngvbncv', 'cvncvbncvbcb', 10, 5, '2025-01-26 11:15:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(5, 'user', 'user@gmail.com', '$2y$10$OAj/pIQX9SlrOU6chNvvCuApyKx0DXeSxRvuVWHVbimk8BhPtKB22', 'user', '2025-01-26 06:20:15'),
(8, 'Admin', 'admin@admin.com', '$2y$10$8IbqOjtOTYHIOS6UYY6nH.gU4UaA.K/R/390iwB8afoR0cIHoPSwe', 'admin', '2025-01-26 11:59:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

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
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendees`
--
ALTER TABLE `attendees`
  ADD CONSTRAINT `attendees_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendees_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
