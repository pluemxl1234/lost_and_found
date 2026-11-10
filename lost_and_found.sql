-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2026 at 08:58 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.3.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lost_and_found`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'อิเล็กทรอนิกส์'),
(2, 'กระเป๋า/กระเป๋าสตางค์'),
(3, 'เอกสาร/บัตร'),
(4, 'เสื้อผ้า/เครื่องแต่งกาย'),
(5, 'อื่นๆ');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `location` varchar(150) NOT NULL,
  `university` varchar(100) NOT NULL,
  `contact_info` varchar(150) NOT NULL,
  `type` enum('lost','found') NOT NULL,
  `status` enum('active','returned') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `user_id`, `category_id`, `title`, `description`, `image_path`, `location`, `university`, `contact_info`, `type`, `status`, `created_at`) VALUES
(2, 1, 1, 'asdasd', 'adsd', 'uploads/1794248678_6af20fe680d3c.jpg', 'asdasd', '', 'dasda', 'lost', 'active', '2026-11-09 18:24:38'),
(3, 1, 1, 'asd', 'asd', '', 'asd', 'มหาวิทยาลัยบูรพา', 'asd', 'lost', 'active', '2026-11-09 18:36:23'),
(4, 1, 1, 'asd', 'asd', '', 'asd', 'มหาวิทยาลัยเกษตรศาสตร์', 'asd', 'lost', 'active', '2026-11-09 18:37:23'),
(5, 1, 1, 'gfdg', 'fgh', '', 'fghfgh', 'มหาวิทยาลัยบูรพา', 'hgfh', 'lost', 'active', '2026-11-09 18:37:38'),
(6, 1, 1, 'asda', 'khjk', '', '53246tyhf', 'มหาวิทยาลัยมหิดล', 'gjghkhg', 'lost', 'active', '2026-11-09 18:52:39'),
(7, 1, 1, 'asdasd', 'fgjghj', 'uploads/1794252148_6af21d74a697c.png', 'hgfh', 'มหาวิทยาลัยบูรพา', 'tjghjh', 'lost', '', '2026-11-09 19:22:28'),
(8, 1, 2, 'ฟหดหกด', '้ดเก้', 'uploads/1794252443_6af21e9bf2d89.jpg', 'เกดเ', 'มหาวิทยาลัยบูรพา', 'เกดเ', 'found', 'active', '2026-11-09 19:27:23'),
(9, 4, 1, '1fsdf', '', 'uploads/1794252665_6af21f79a3099.png', 'hfgj', 'มหาวิทยาลัยบูรพา', 'fgh', 'lost', 'active', '2026-11-09 19:31:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `university` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `phone`, `email`, `university`, `created_at`, `role`) VALUES
(1, 'admin', '1234', 'ผู้ดูแลระบบ', '0812345678', 'admin@example.com', '', '2026-11-09 12:15:31', 'admin'),
(2, '765', '123', '1 2', NULL, '3 4', 'มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี', '2026-11-09 18:50:42', 'user'),
(3, '797897807897789', '9878789', '1', NULL, '23', 'มหาวิทยาลัยเทคโนโลยีพระจอมเกล้าธนบุรี', '2026-11-09 18:51:29', 'user'),
(4, '123', '123', '123', NULL, '123', 'มหาวิทยาลัยบูรพา', '2026-11-09 19:30:24', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
