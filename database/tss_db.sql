-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2022 at 07:34 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `tss_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `category_list`
--

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `name` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `delete_flag` tinyint(1) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_list`
--

INSERT INTO `category_list` (`id`, `user_id`, `name`, `status`, `delete_flag`, `date_created`, `date_updated`) VALUES
(1, 1, 'Sample Category 101', 1, 0, '2022-05-12 09:36:36', '2022-05-12 09:36:36'),
(2, 1, 'Category 102', 1, 0, '2022-05-12 09:36:56', '2022-05-12 09:36:56'),
(3, 6, 'Category 101', 1, 0, '2022-05-12 09:56:18', '2022-05-12 09:56:18'),
(4, 6, 'Category 102', 1, 0, '2022-05-12 09:56:25', '2022-05-12 09:56:25'),
(5, 7, 'Task Type 101', 1, 0, '2022-05-12 12:03:46', '2022-05-12 12:03:46');

-- --------------------------------------------------------

--
-- Table structure for table `schedule_list`
--

CREATE TABLE `schedule_list` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `category_id` int(30) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `schedule_from` datetime NOT NULL,
  `schedule_to` datetime DEFAULT NULL,
  `is_whole` tinyint(4) NOT NULL DEFAULT 0,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedule_list`
--

INSERT INTO `schedule_list` (`id`, `user_id`, `category_id`, `title`, `description`, `schedule_from`, `schedule_to`, `is_whole`, `date_created`, `date_updated`) VALUES
(1, 6, 3, 'Sample Task 101', 'This is a sample task.', '2022-05-12 09:00:00', '2022-05-12 14:00:00', 0, '2022-05-12 10:51:43', '2022-05-12 11:24:50'),
(2, 6, 4, 'Task 102', 'Test 123', '2022-05-16 13:00:00', '2022-05-17 17:00:00', 0, '2022-05-12 11:05:59', '2022-05-12 11:05:59'),
(4, 7, 5, 'Task 101', 'Test 123', '2022-05-12 15:00:00', '2022-05-12 17:00:00', 0, '2022-05-12 12:04:11', '2022-05-12 12:04:11'),
(5, 7, 5, 'TEst 123', 'Test only', '2022-05-14 10:00:00', '2022-05-14 14:00:00', 0, '2022-05-12 13:15:30', '2022-05-12 13:15:30');

-- --------------------------------------------------------

--
-- Table structure for table `system_info`
--

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `system_info`
--

INSERT INTO `system_info` (`id`, `meta_field`, `meta_value`) VALUES
(1, 'name', 'Task Scheduler System'),
(6, 'short_name', 'TSS - PHP'),
(11, 'logo', 'uploads/logo.png?v=1652317640'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/cover.png?v=1652317642');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(50) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `middlename` text DEFAULT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `middlename`, `lastname`, `username`, `password`, `avatar`, `last_login`, `type`, `date_added`, `date_updated`) VALUES
(1, 'Adminstrator', NULL, 'Admin', 'admin', '0192023a7bbd73250516f069df18b500', 'uploads/avatars/1.png?v=1649834664', NULL, 1, '2021-01-20 14:02:37', '2022-04-13 15:24:24'),
(6, 'Mark', 'D', 'Cooper', 'mcooper', 'c7162ff89c647f444fcaa5c635dac8c3', 'uploads/avatars/6.png?v=1652320347', NULL, 2, '2022-05-12 09:52:27', '2022-05-12 11:31:14'),
(7, 'John', 'D', 'Smith', 'jsmith', '1254737c076cf867dc53d60a0364f38e', 'uploads/avatars/7.png?v=1652328043', NULL, 2, '2022-05-12 12:00:43', '2022-05-12 12:00:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category_list`
--
ALTER TABLE `category_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `schedule_list`
--
ALTER TABLE `schedule_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `system_info`
--
ALTER TABLE `system_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category_list`
--
ALTER TABLE `category_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `schedule_list`
--
ALTER TABLE `schedule_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_info`
--
ALTER TABLE `system_info`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;
