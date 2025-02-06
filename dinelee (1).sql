-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2025 at 12:31 AM
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
-- Database: `dinelee`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `full_name`, `phone`, `email`, `password`) VALUES
(1, 'Nathaniel Kwabena Larbi Arthur', '0541987478', 'nathanielk.larthur@gmail.com', '$2y$10$bQEv4XM1dlhg8ngIS0QDkO6STCfWVW2Pgo013OFoFX9nWSEBQsunq');

-- --------------------------------------------------------

--
-- Table structure for table `cashiers`
--

CREATE TABLE `cashiers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','disabled') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cashiers`
--

INSERT INTO `cashiers` (`id`, `name`, `phone`, `email`, `password`, `created_at`, `status`) VALUES
(1, 'Anastasia Esi Arthur', '0249029755', 'anas@gmail.com', '$2y$10$SEl0gAsYc7wcN2muTgstO.WZjyDDcVAQS9eaqYPrSiozlw3whK0u6', '2025-02-06 18:54:56', 'active'),
(2, 'Nathaniel Kwabena Larbi Arthur', '0541987478', 'nathanielk.larthur@gmail.com', '$2y$10$THgpps6rooykgtvo1WcMPu/TGzqZLNVqTO2UzhkVXQQeHJjcCy./6', '2025-02-06 22:37:50', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `food_categories`
--

CREATE TABLE `food_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_categories`
--

INSERT INTO `food_categories` (`id`, `category_name`) VALUES
(6, 'Local Food'),
(7, 'Drinks'),
(8, 'Breakfast'),
(9, 'Rice');

-- --------------------------------------------------------

--
-- Table structure for table `food_menu`
--

CREATE TABLE `food_menu` (
  `id` int(11) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `food_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_menu`
--

INSERT INTO `food_menu` (`id`, `food_name`, `price`, `category_id`, `food_image`) VALUES
(1, 'Sprite', 40.00, 7, '67a4f32a946fb8.52288000.png'),
(2, 'Welchs', 60.00, 7, '67a51616e36ee8.11854034.png'),
(3, 'Burger', 50.00, 8, '67a51eecee5870.55610418.jpeg'),
(4, 'Tell Afar Combo', 120.00, 8, '67a51f38378ad4.17502030.jpeg'),
(5, 'Fried Rice with Chicken', 200.00, 9, '67a5216e375ad4.76605491.png'),
(6, 'Jollof Rice with 3 beef ', 150.00, 9, '67a521d626ae02.92916961.jpg'),
(7, 'Chicken Wigs (30 pieces)', 200.00, 6, '67a52492b5d843.31960754.jpg'),
(8, 'Beef Bucket', 300.00, 6, '67a52652e8d628.87293469.jpg'),
(9, '8 Drums Chicken Wings', 200.00, 6, '67a526875ee5a6.08099388.jpg'),
(10, 'Jollof Rice', 1000.00, 6, '67a526c0c5bdf9.94773363.jpg'),
(11, 'Fried Rice Bucket', 345.00, 6, '67a526e61c48d4.58913970.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `cashier_id` int(11) DEFAULT NULL,
  `cashier_name` varchar(255) DEFAULT NULL,
  `order_date` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_mode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `cashier_id`, `cashier_name`, `order_date`, `total_amount`, `payment_mode`) VALUES
(1, 1, 'Anastasia Esi Arthur', '2025-02-06 23:29:15', 480.00, 'cash'),
(2, 1, 'Anastasia Esi Arthur', '2025-02-06 23:32:17', 720.00, 'cash'),
(3, 1, 'Anastasia Esi Arthur', '2025-02-06 23:33:34', 540.00, 'momo'),
(4, 2, 'Nathaniel Kwabena Larbi Arthur', '2025-02-07 00:08:06', 800.00, 'cash'),
(5, 2, 'Nathaniel Kwabena Larbi Arthur', '2025-02-07 00:13:24', 600.00, 'bank_transfer'),
(6, 2, 'Nathaniel Kwabena Larbi Arthur', '2025-02-07 00:16:23', 150.00, 'cash'),
(7, 2, 'Nathaniel Kwabena Larbi Arthur', '2025-02-07 00:17:17', 300.00, 'momo');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `food_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `food_id`, `quantity`, `price`, `total_price`) VALUES
(1, 1, 1, 6, 40.00, 240.00),
(2, 1, 2, 4, 60.00, 240.00),
(3, 2, 1, 6, 40.00, 240.00),
(4, 2, 2, 8, 60.00, 480.00),
(5, 3, 8, 1, 300.00, 300.00),
(6, 3, 4, 2, 120.00, 240.00),
(7, 4, 5, 2, 200.00, 400.00),
(8, 4, 7, 2, 200.00, 400.00),
(9, 5, 7, 2, 200.00, 400.00),
(10, 5, 9, 1, 200.00, 200.00),
(11, 6, 6, 1, 150.00, 150.00),
(12, 7, 3, 6, 50.00, 300.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cashiers`
--
ALTER TABLE `cashiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `food_categories`
--
ALTER TABLE `food_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `food_menu`
--
ALTER TABLE `food_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `cashier_id` (`cashier_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_id` (`food_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cashiers`
--
ALTER TABLE `cashiers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `food_categories`
--
ALTER TABLE `food_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `food_menu`
--
ALTER TABLE `food_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `food_menu`
--
ALTER TABLE `food_menu`
  ADD CONSTRAINT `food_menu_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `food_categories` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cashier_id`) REFERENCES `cashiers` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `food_menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
