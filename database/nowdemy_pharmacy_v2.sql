-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2025 at 07:31 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nowdemy_pharmacy`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `brand_active` int(11) NOT NULL DEFAULT 0,
  `brand_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`brand_id`, `brand_name`, `brand_active`, `brand_status`) VALUES
(1, 'Cipla', 1, 1),
(2, 'Mankind', 1, 1),
(3, 'Sunpharma', 1, 1),
(4, 'MicroLabs', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `categories_name` varchar(255) NOT NULL,
  `categories_active` int(11) NOT NULL DEFAULT 0,
  `categories_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`categories_id`, `categories_name`, `categories_active`, `categories_status`) VALUES
(1, 'Tablets', 1, 1),
(2, 'Syrup', 1, 1),
(3, 'SkinLiquid', 1, 1),
(4, 'PainKiller', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(15) NOT NULL,
  `uno` varchar(50) NOT NULL,
  `orderDate` date NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT 1,
  `clientName` text NOT NULL,
  `projectName` varchar(30) NOT NULL,
  `clientContact` int(15) NOT NULL,
  `address` varchar(30) NOT NULL,
  `subTotal` int(100) NOT NULL,
  `totalAmount` int(100) NOT NULL,
  `discount` int(100) NOT NULL,
  `grandTotalValue` int(100) NOT NULL,
  `gstn` int(100) NOT NULL,
  `paid` int(100) NOT NULL,
  `dueValue` int(100) NOT NULL,
  `paymentType` int(15) NOT NULL,
  `paymentStatus` int(15) NOT NULL,
  `paymentPlace` int(5) NOT NULL,
  `delete_status` tinyint(5) NOT NULL,
  `shipping_status` varchar(255) NOT NULL DEFAULT 'Order Placed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `uno`, `orderDate`, `client_id`, `clientName`, `projectName`, `clientContact`, `address`, `subTotal`, `totalAmount`, `discount`, `grandTotalValue`, `gstn`, `paid`, `dueValue`, `paymentType`, `paymentStatus`, `paymentPlace`, `delete_status`, `shipping_status`) VALUES
(1, 'INV-0001', '2022-02-28', 1, 'Santosh Kadam', '', 2147483647, '', 100, 10, 108, 49, 0, 49, 49, 2, 1, 0, 0, 'Order Placed'),
(2, 'INV-0002', '2022-03-24', 1, 'Aishwarya Joshi', '', 2147483647, '', 300, 0, 354, 0, 0, 354, 354, 3, 3, 1, 0, 'Order Placed'),
(3, 'INV-0003', '2022-04-15', 1, 'Saurabh Katkar', '', 2147483647, '', 860, 1015, 10, 1005, 155, 500, 505, 2, 2, 1, 0, 'Order Placed'),
(4, 'INV-0004', '2022-04-15', 1, 'Mayuri K', '', 2147483647, '', 60, 71, 0, 71, 11, 50, 21, 5, 2, 1, 0, 'Order Placed'),
(5, 'INV-0005', '2025-01-23', 1, 'asdfgadf', '', 3213123, '', 900, 0, 1062, 300, 0, 762, 762, 1, 1, 0, 0, 'Order Placed'),
(6, 'INV-0006', '2025-01-23', 1, 'afsda', '', 232, '', 35100, 41418, 0, 41418, 6318, 234, 41184, 2, 1, 1, 0, 'Order Placed'),
(7, 'INV-0007', '2025-01-23', 1, 'check', '', 123, '', 250, 295, 0, 295, 45, 200, 95, 2, 1, 1, 0, 'Order Placed'),
(8, 'INV-0008', '2025-01-23', 1, '123', '', 123, '', 375, 443, 0, 443, 68, 200, 243, 2, 1, 1, 0, 'Order Placed'),
(9, 'INV-0009', '2025-01-23', 1, '1234', '', 1234, '', 2000, 2360, 123, 2237, 360, 123, 2114, 2, 1, 1, 0, 'Order Placed'),
(10, 'INV-00010', '2025-01-23', 2, 'khan', '', 123, '', 300, 354, 0, 354, 0, 354, 354, 2, 3, 0, 0, 'Loaded in Container'),
(11, 'INV-00011', '2025-01-23', 2, 'khan', '', 123, '', 100, 0, 118, 0, 0, 118, 118, 3, 1, 0, 0, 'in mart'),
(12, 'INV-00012', '2025-01-23', 2, 'khan', '', 123, '', 300, 354, 0, 354, 54, 0, 354, 2, 3, 1, 0, 'Order Placed');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `id` int(15) NOT NULL,
  `productName` int(100) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `lastid` int(50) NOT NULL,
  `added_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`id`, `productName`, `quantity`, `rate`, `total`, `lastid`, `added_date`) VALUES
(5, 2, '1', '100', '100.00', 1, '0000-00-00'),
(6, 2, '2', '150', '300.00', 2, '0000-00-00'),
(7, 1, '2', '30', '60.00', 3, '2022-04-15'),
(8, 2, '4', '150', '600.00', 3, '2022-04-15'),
(9, 3, '1', '200', '200.00', 3, '2022-04-15'),
(10, 1, '2', '30', '60.00', 4, '2022-04-15'),
(13, 2, '2', '150', '300.00', 5, '0000-00-00'),
(14, 3, '3', '200', '600.00', 5, '0000-00-00'),
(15, 2, '234', '150', '35100.00', 6, '2025-01-23'),
(16, 4, '10', '25', '250.00', 7, '2025-01-23'),
(17, 4, '14', '25', '375.00', 8, '2025-01-23'),
(18, 5, '20', '100', '2000.00', 9, '2025-01-23'),
(21, 2, '2', '150', '300.00', 12, '2025-01-23'),
(23, 2, '2', '150', '300.00', 10, '0000-00-00'),
(24, 5, '1', '100', '100.00', 11, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` text NOT NULL,
  `brand_id` int(11) NOT NULL,
  `categories_id` int(11) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `mrp` int(100) NOT NULL,
  `bno` varchar(50) NOT NULL,
  `expdate` date NOT NULL,
  `added_date` date NOT NULL,
  `active` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_image`, `brand_id`, `categories_id`, `quantity`, `rate`, `mrp`, `bno`, `expdate`, `added_date`, `active`, `status`) VALUES
(1, 'Cipla Inhaler', 'tab.jpg', 1, 1, '50', '30', 40, '307002', '2022-02-28', '2022-02-28', 2, 2),
(2, 'Abevia 200 SR Tablet', 'tab1.jpg', 2, 1, '30', '150', 200, '307003', '2025-01-23', '2022-02-28', 1, 1),
(3, 'Arpizol 20 Tablet', 'tab3.jpg', 3, 3, '69', '200', 300, '307004', '2025-11-23', '2022-02-28', 1, 1),
(4, 'DOLO 650mg', 'tab4.jpg', 4, 1, '500', '25', 30, '307005', '2022-05-31', '2022-04-15', 1, 1),
(5, 'Bethany Herrera', 'banner1.ico', 2, 1, '174', '100', 234, '23', '2025-05-22', '2025-01-23', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `shop_tel_no` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `Role` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `mobile_no`, `shop_tel_no`, `address`, `Role`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@gmail.com', '+923129988123', '123', 'adsfa', 'Admin'),
(2, 'khan', '9e95f6d797987b7da0fb293a760fe57e', 'khan@gmail.com', '123', '12332', 'this is my address ', 'Client');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
