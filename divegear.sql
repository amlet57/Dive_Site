-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 14, 2026 at 01:07 PM
-- Server version: 10.11.13-MariaDB-log
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `divegear`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','manager') DEFAULT 'manager',
  `is_active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `full_name`, `email`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'razemsb', '$2y$10$iMz/HRxOlYCjJtKYeQSdIu3dZC0zdrdwBWVTzNYKyM.eRuVmvdT5a', 'Главный администратор', 'admin@divegear.ru', 'admin', 1, '2026-02-14 10:07:20', '2026-02-13 12:10:35', '2026-02-14 10:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 09:48:33'),
(2, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 09:49:07'),
(3, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 09:50:21'),
(4, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 09:51:37'),
(5, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 09:53:06'),
(6, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 09:56:26'),
(7, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 10:00:32'),
(8, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 10:00:46'),
(9, 1, 'login', 'Успешный вход в систему', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-14 10:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `clothing_types`
--

CREATE TABLE `clothing_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clothing_types`
--

INSERT INTO `clothing_types` (`id`, `name`, `category`, `description`, `base_price`, `sort_order`, `is_active`, `created_at`) VALUES
(1, 'Сухой гидрокостюм «Арктика»', 'Гидрокостюмы', NULL, '18900.00', 1, 1, '2026-02-13 12:10:36'),
(2, 'Мокрый гидрокостюм 3 мм', 'Гидрокостюмы', NULL, '8900.00', 2, 1, '2026-02-13 12:10:36'),
(3, 'Мокрый гидрокостюм 5 мм', 'Гидрокостюмы', NULL, '9900.00', 3, 1, '2026-02-13 12:10:36'),
(4, 'Мокрый гидрокостюм 7 мм', 'Гидрокостюмы', NULL, '10900.00', 4, 1, '2026-02-13 12:10:36'),
(5, 'Термобелье дайвера', 'Термобелье', NULL, '4500.00', 5, 1, '2026-02-13 12:10:36'),
(6, 'Перчатки неопреновые 3 мм', 'Перчатки', NULL, '1900.00', 6, 1, '2026-02-13 12:10:36'),
(7, 'Перчатки неопреновые 5 мм', 'Перчатки', NULL, '2100.00', 7, 1, '2026-02-13 12:10:36'),
(8, 'Перчатки неопреновые 7 мм', 'Перчатки', NULL, '2300.00', 8, 1, '2026-02-13 12:10:36'),
(9, 'Ботинки неопреновые 3 мм', 'Обувь', NULL, '3200.00', 9, 1, '2026-02-13 12:10:36'),
(10, 'Ботинки неопреновые 5 мм', 'Обувь', NULL, '3500.00', 10, 1, '2026-02-13 12:10:36'),
(11, 'Ботинки неопреновые 7 мм', 'Обувь', NULL, '3800.00', 11, 1, '2026-02-13 12:10:36'),
(12, 'Носки под ласты 2 мм', 'Обувь', NULL, '1200.00', 12, 1, '2026-02-13 12:10:36'),
(13, 'Носки под ласты 3 мм', 'Обувь', NULL, '1300.00', 13, 1, '2026-02-13 12:10:36'),
(14, 'Сумка-туба «Нерпа» 40л', 'Аксессуары', NULL, '3900.00', 14, 1, '2026-02-13 12:10:36'),
(15, 'Сумка для ласт', 'Аксессуары', NULL, '2900.00', 15, 1, '2026-02-13 12:10:36'),
(16, 'Чехол для регата', 'Аксессуары', NULL, '2500.00', 16, 1, '2026-02-13 12:10:36'),
(17, 'Коврик неопреновый', 'Аксессуары', NULL, '1800.00', 17, 1, '2026-02-13 12:10:36'),
(18, 'Кастомный пошив (индивидуальный заказ)', 'Кастом', NULL, '0.00', 18, 1, '2026-02-13 12:10:36');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(30) NOT NULL,
  `clothing_type` varchar(100) NOT NULL,
  `size` varchar(50) NOT NULL,
  `quantity` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(30) NOT NULL,
  `delivery_address` text NOT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('new','processing','completed','cancelled') DEFAULT 'new',
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `processed_by` int(11) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `clothing_type`, `size`, `quantity`, `customer_name`, `customer_phone`, `delivery_address`, `comment`, `status`, `total_price`, `created_at`, `updated_at`, `processed_by`, `processed_at`) VALUES
(1, 'DG-20260214-9015AF', 'Ботинки неопреновые 5 мм', 'L (56-58)', 199, 'форд чпокус 2 дорест', '+7 (888) 888-88-88', 'форд чпокус 2 дорест', 'форд чпокус 2 дорест', 'new', '696500.00', '2026-02-14 09:46:33', '2026-02-14 09:46:33', NULL, NULL),
(2, 'DG-20260214-77C769', 'Мокрый гидрокостюм 7 мм', 'M (52-54)', 100, '$2y$10$iMz/HRxOlYCjJtKYeQSdIu3dZC0zdrdwBWVTzNYKyM.eRuVmvdT5a', '+7 (888) 888-88-88', '$2y$10$iMz/HRxOlYCjJtKYeQSdIu3dZC0zdrdwBWVTzNYKyM.eRuVmvdT5a', '$2y$10$iMz/HRxOlYCjJtKYeQSdIu3dZC0zdrdwBWVTzNYKyM.eRuVmvdT5a', 'new', '1090000.00', '2026-02-14 09:56:07', '2026-02-14 09:56:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES
(1, 'site_name', 'DiveGear', 'Название сайта', '2026-02-13 12:10:37'),
(2, 'company_phone', '+7 (495) 123-45-67', 'Телефон компании', '2026-02-13 12:10:37'),
(3, 'company_email', 'info@divegear.ru', 'Email компании', '2026-02-13 12:10:37'),
(4, 'company_address', 'г. Москва, ул. Промышленная, 12', 'Адрес компании', '2026-02-13 12:10:37'),
(5, 'company_inn', '7712345678', 'ИНН', '2026-02-13 12:10:37'),
(6, 'company_kpp', '771201001', 'КПП', '2026-02-13 12:10:37'),
(7, 'company_ogrn', '1157746123456', 'ОГРН', '2026-02-13 12:10:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `clothing_types`
--
ALTER TABLE `clothing_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `processed_by` (`processed_by`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `processed_by` (`processed_by`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_customer_phone` (`customer_phone`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `clothing_types`
--
ALTER TABLE `clothing_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`processed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`processed_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
