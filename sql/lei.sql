-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2024 at 12:18 AM
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
-- Database: `lei`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `user` varchar(255) DEFAULT NULL,
  `action` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`id`, `timestamp`, `user`, `action`) VALUES
(1, '2024-06-10 21:14:34', 'edwardjomari@gmail.com', 'Edited product: Kape Brusko'),
(2, '2024-06-10 21:14:43', 'edwardjomari@gmail.com', 'Edited product: Kape Brusko'),
(3, '2024-06-13 21:44:18', 'carmonamj0323@gmail.com', 'Edited product: Beef and Mushroom 12\"'),
(4, '2024-06-13 21:50:18', 'carmonamj0323@gmail.com', 'Added new product: Barkada Box 4');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `uContact` varchar(255) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `reference_number` varchar(255) NOT NULL,
  `receipt` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `uContact`, `total_amount`, `pickup_date`, `pickup_time`, `payment_method`, `reference_number`, `receipt`, `created_at`) VALUES
(1, 'edwardjomari@gmail.com', 78.00, '2024-06-11', '09:40:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 19:35:03'),
(2, 'edwardjomari@gmail.com', 68.00, '2024-06-11', '09:40:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 19:36:51'),
(3, 'edwardjomari@gmail.com', 97.00, '2024-06-11', '17:30:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 19:38:38'),
(4, 'edwardjomari@gmail.com', 29.00, '2024-06-12', '16:45:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 19:43:35'),
(5, 'edwardjomari@gmail.com', 68.00, '2024-06-11', '17:00:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 19:48:29'),
(6, 'edwardjomari@gmail.com', 68.00, '2024-06-11', '17:00:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 19:50:50'),
(7, 'brix@gmail.com', 39.00, '2024-06-11', '08:00:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 19:55:36'),
(8, 'brix@gmail.com', 78.00, '2024-06-11', '10:00:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 20:00:19'),
(9, 'edwardjomari@gmail.com', 39.00, '2024-06-11', '07:30:00', 'gcash', '2018084695999', 'uploads/gcashapi.png', '2024-06-10 20:30:53'),
(10, 'carmonamj0323@gmail.com', 292.00, '2024-06-12', '22:46:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-12 14:46:03'),
(11, 'yoo@gmail.com', 10821.00, '2024-06-13', '05:01:00', 'paymaya', '1231231231231', 'uploads/gcashapi.png', '2024-06-12 21:02:02'),
(12, 'yoo@gmail.com', 2995.00, '2024-06-28', '00:15:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 16:14:18'),
(13, 'yoo@gmail.com', 250.00, '2024-06-06', '00:17:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 16:14:49'),
(14, 'yoo@gmail.com', 0.00, '2024-06-30', '00:57:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 16:53:01'),
(15, 'yoo@gmail.com', 0.00, '2024-06-29', '07:12:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 17:09:48'),
(16, 'yoo@gmail.com', 0.00, '2024-06-29', '07:12:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 17:11:30'),
(17, 'yoo@gmail.com', 250.00, '2024-06-23', '01:29:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 17:28:38'),
(18, 'yoo@gmail.com', 250.00, '2024-06-30', '04:39:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 17:40:06'),
(19, 'yoo@gmail.com', 250.00, '2024-06-30', '01:46:00', 'paymaya', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 17:41:45'),
(20, 'yoo@gmail.com', 935.00, '2024-06-27', '18:47:00', 'paymaya', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 17:42:39'),
(21, 'yoo@gmail.com', 3557.00, '2024-06-29', '17:47:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 17:43:10'),
(22, 'yoo@gmail.com', 225.00, '2024-06-30', '08:05:00', 'gcash', '1231231231231', 'uploads/gcashapi.png', '2024-06-13 21:05:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_price`, `quantity`, `total_price`, `created_at`) VALUES
(1, 6, 2, 'Kape Macch', 39.00, 1, 39.00, '2024-06-10 19:50:50'),
(2, 6, 3, 'Kape Moca', 29.00, 1, 29.00, '2024-06-10 19:50:50'),
(3, 7, 2, 'Kape Macch', 39.00, 1, 39.00, '2024-06-10 19:55:36'),
(4, 8, 11, 'Matcha', 39.00, 2, 78.00, '2024-06-10 20:00:19'),
(5, 9, 14, 'Dark Choco', 39.00, 1, 39.00, '2024-06-10 20:30:53'),
(6, 10, 1, 'Kape Brusko', 39.00, 3, 117.00, '2024-06-12 14:46:03'),
(7, 10, 2, 'Kape Macch', 39.00, 1, 39.00, '2024-06-12 14:46:03'),
(8, 10, 3, 'Kape Moca', 29.00, 1, 29.00, '2024-06-12 14:46:03'),
(9, 10, 5, 'Kape Fudge', 29.00, 1, 29.00, '2024-06-12 14:46:03'),
(10, 10, 4, 'Kape Blanco', 39.00, 2, 78.00, '2024-06-12 14:46:03'),
(11, 11, 1, 'SPECIAL PIZZA', 220.00, 6, 1320.00, '2024-06-12 21:02:02'),
(12, 11, 3, 'SPECIAL PIZZA', 250.00, 6, 1500.00, '2024-06-12 21:02:02'),
(13, 11, 5, 'SPECIAL PIZZA', 235.00, 3, 705.00, '2024-06-12 21:02:02'),
(14, 11, 10, 'PASKYS COOLERS', 55.00, 5, 275.00, '2024-06-12 21:02:02'),
(15, 11, 8, 'PASKYS COOLERS', 80.00, 3, 240.00, '2024-06-12 21:02:02'),
(16, 11, 4, 'SPECIAL PIZZA', 255.00, 3, 765.00, '2024-06-12 21:02:02'),
(17, 11, 2, 'SPECIAL PIZZA', 225.00, 3, 675.00, '2024-06-12 21:02:02'),
(18, 11, 12, 'COMBO 2', 179.00, 4, 716.00, '2024-06-12 21:02:02'),
(19, 11, 7, 'PASKYS COOLERS', 70.00, 4, 280.00, '2024-06-12 21:02:02'),
(20, 11, 6, 'PASKYS COOLERS', 70.00, 2, 140.00, '2024-06-12 21:02:02'),
(21, 11, 9, 'PASKYS COOLERS', 45.00, 2, 90.00, '2024-06-12 21:02:02'),
(22, 11, 11, 'COMBO 1', 199.00, 2, 398.00, '2024-06-12 21:02:02'),
(23, 11, 13, 'COMBO 3', 169.00, 2, 338.00, '2024-06-12 21:02:02'),
(24, 11, 14, 'COMBO 4', 159.00, 2, 318.00, '2024-06-12 21:02:02'),
(25, 11, 15, 'COMBO 5', 199.00, 2, 398.00, '2024-06-12 21:02:02'),
(26, 11, 17, 'BARKADA BOX 2', 429.00, 2, 858.00, '2024-06-12 21:02:02'),
(27, 11, 16, 'BARKADA BOX 1', 349.00, 2, 698.00, '2024-06-12 21:02:02'),
(28, 11, 18, 'BARKADA BOX 3', 369.00, 3, 1107.00, '2024-06-12 21:02:02'),
(29, 12, 3, '3 in 1 Mozzarella 12\"', 250.00, 3, 750.00, '2024-06-13 16:14:18'),
(30, 12, 2, 'Overload 12\"', 225.00, 3, 675.00, '2024-06-13 16:14:18'),
(31, 12, 1, 'Beef and Mushroom\n12\"', 220.00, 2, 440.00, '2024-06-13 16:14:18'),
(32, 12, 4, 'Shawarma Pizza 12\"', 255.00, 2, 510.00, '2024-06-13 16:14:18'),
(33, 12, 5, 'Bacon and Beef 12\"', 235.00, 2, 470.00, '2024-06-13 16:14:18'),
(34, 12, 8, 'Paskys Special', 80.00, 1, 80.00, '2024-06-13 16:14:18'),
(35, 12, 7, 'Strawberry Milk', 70.00, 1, 70.00, '2024-06-13 16:14:18'),
(36, 13, 3, '3 in 1 Mozzarella 12\"', 250.00, 1, 250.00, '2024-06-13 16:14:49'),
(37, 17, 3, '3 in 1 Mozzarella 12\"', 250.00, 1, 250.00, '2024-06-13 17:28:38'),
(38, 18, 3, '3 in 1 Mozzarella 12\"', 250.00, 1, 250.00, '2024-06-13 17:40:06'),
(39, 19, 3, '3 in 1 Mozzarella 12\"', 250.00, 1, 250.00, '2024-06-13 17:41:45'),
(40, 20, 1, 'Beef and Mushroom\n12\"', 220.00, 1, 220.00, '2024-06-13 17:42:39'),
(41, 20, 2, 'Overload 12\"', 225.00, 1, 225.00, '2024-06-13 17:42:39'),
(42, 20, 4, 'Shawarma Pizza 12\"', 255.00, 1, 255.00, '2024-06-13 17:42:39'),
(43, 20, 5, 'Bacon and Beef 12\"', 235.00, 1, 235.00, '2024-06-13 17:42:39'),
(44, 21, 1, 'Beef and Mushroom\n12\"', 220.00, 1, 220.00, '2024-06-13 17:43:10'),
(45, 21, 2, 'Overload 12\"', 225.00, 1, 225.00, '2024-06-13 17:43:10'),
(46, 21, 3, '3 in 1 Mozzarella 12\"', 250.00, 1, 250.00, '2024-06-13 17:43:10'),
(47, 21, 4, 'Shawarma Pizza 12\"', 255.00, 1, 255.00, '2024-06-13 17:43:10'),
(48, 21, 5, 'Bacon and Beef 12\"', 235.00, 1, 235.00, '2024-06-13 17:43:10'),
(49, 21, 6, 'Strawberry Frappe', 70.00, 1, 70.00, '2024-06-13 17:43:10'),
(50, 21, 7, 'Strawberry Milk', 70.00, 1, 70.00, '2024-06-13 17:43:10'),
(51, 21, 8, 'Paskys Special', 80.00, 1, 80.00, '2024-06-13 17:43:10'),
(52, 21, 9, 'Green Apple Bomb', 45.00, 1, 45.00, '2024-06-13 17:43:10'),
(53, 21, 10, 'Lychee Sakura Blossom', 55.00, 1, 55.00, '2024-06-13 17:43:10'),
(54, 21, 11, 'COMBO 1', 199.00, 1, 199.00, '2024-06-13 17:43:10'),
(55, 21, 12, 'COMBO 2', 179.00, 1, 179.00, '2024-06-13 17:43:10'),
(56, 21, 13, 'COMBO 3', 169.00, 1, 169.00, '2024-06-13 17:43:10'),
(57, 21, 14, 'COMBO 4', 159.00, 1, 159.00, '2024-06-13 17:43:10'),
(58, 21, 15, 'COMBO 5', 199.00, 1, 199.00, '2024-06-13 17:43:10'),
(59, 21, 16, 'BARKADA BOX 1', 349.00, 1, 349.00, '2024-06-13 17:43:10'),
(60, 21, 17, 'BARKADA BOX 2', 429.00, 1, 429.00, '2024-06-13 17:43:10'),
(61, 21, 18, 'BARKADA BOX 3', 369.00, 1, 369.00, '2024-06-13 17:43:10'),
(62, 22, 2, 'Overload 12\"', 225.00, 1, 225.00, '2024-06-13 21:05:20');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text NOT NULL,
  `product_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_description`, `product_price`) VALUES
(1, 'Beef and Mushroom 12\"', 'SPECIAL PIZZA', 220.00),
(2, 'Overload 12\"', 'SPECIAL PIZZA', 225.00),
(3, '3 in 1 Mozzarella 12\"', 'SPECIAL PIZZA', 250.00),
(4, 'Shawarma Pizza 12\"', 'SPECIAL PIZZA', 255.00),
(5, 'Bacon and Beef 12\"', 'SPECIAL PIZZA', 235.00),
(6, 'Strawberry Frappe', 'PASKYS COOLERS', 70.00),
(7, 'Strawberry Milk', 'PASKYS COOLERS', 70.00),
(8, 'Paskys Special', 'Ube Cheesecake Halo-halo', 80.00),
(9, 'Green Apple Bomb', 'PASKYS COOLERS', 45.00),
(10, 'Lychee Sakura Blossom', 'PASKYS COOLERS', 55.00),
(11, 'COMBO 1', 'Strawberry Frappe and Chicken Poppers', 199.00),
(12, 'COMBO 2', 'Strawberry Frappe and Shawarma Fries', 179.00),
(13, 'COMBO 3', 'Strawberry Frappe and Cheesy Nachos', 169.00),
(14, 'COMBO 4', 'Ube Cheesecake Halo-halo and Cheesy Bacon Fries', 159.00),
(15, 'COMBO 5', 'Ube Cheesecake Halo-halo and Cheesy Bacon Nachos', 199.00),
(16, 'BARKADA BOX 1', 'Cheesy Nachos, Chicken Poppers, Crinkle / Thick Cut Fries, and Floss Mozzarella Stick', 349.00),
(17, 'BARKADA BOX 2', '1/2 Pepperoni Special Pizza,\nChicken Poppers,\nQuesadilla, and\nCrinkle Cut / Thick Cut Fries', 429.00),
(18, 'BARKADA BOX 3', '1/2 Hawaiian Special Pizza, Chicken Poppers, Crinkle/Thick Cut Fries, and Classic Mozzarella Corndog', 369.00),
(19, 'Barkada Box 4', 'Secret', 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uID` int(11) NOT NULL,
  `uFname` varchar(255) DEFAULT NULL,
  `uLname` varchar(255) DEFAULT NULL,
  `uContact` varchar(50) DEFAULT NULL,
  `uPassword` varchar(255) DEFAULT NULL,
  `uType` int(11) DEFAULT 2,
  `uRegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `uProfilePic` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uID`, `uFname`, `uLname`, `uContact`, `uPassword`, `uType`, `uRegDate`, `uProfilePic`) VALUES
(1, 'Edward', 'Garcia', 'edwardjomari@gmail.com', '$2y$10$iIM9.3x2TWIUS5di3FIsIucidjvqrVV2rM05YvzfXsN5nIVIqye.y', 1, '2024-06-10 18:30:29', 'profile.jpg'),
(2, 'Marc', 'Joseph', 'marc@gmail.com', '$2y$10$p919UVQHJNT/CDu1HOsPFOoz0tr3rpplRFlcxRn/GmfnJXZ.IpKEy', 2, '2024-06-10 19:54:18', 'default.png'),
(3, 'Brix', 'Angad', 'brix@gmail.com', '$2y$10$UhQvoECZMGahpZMRqzAnkujW3dHZqaomO2jGWekh3ndMEbZ0nE/PG', 2, '2024-06-10 19:54:56', 'default.png'),
(4, 'Lei', 'Evangelista', 'lei@gmail.com', '$2y$10$hZXONIWsR0kkzXiuKI41NOOLrPAgxFlKRD9x19Og0K.m4NK82zR3C', 2, '2024-06-10 20:31:22', 'default.png'),
(5, 'Marc', 'Carmona', 'carmonamj0323@gmail.com', '$2y$10$fLtPJLjVvt005kmgAIT8wOrbB2MSPurDIM82wWz1PPJgunjHfWN16', 1, '2024-06-12 14:42:49', 'yooyoo.jfif'),
(6, 'Eunice', 'Pascual', 'yoo@gmail.com', '$2y$10$TMNA1FcefSKmaLU71yPr5OCOHe5kVsKOijpwJWJtHVs.HwxBlxw1u', 2, '2024-06-12 17:53:40', 'yooyoo.jfif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
