-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2024 at 12:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mikrotik`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `email`) VALUES
(1, 'aku', '89ccfac87d8d06db06bf3211cb2d69ed', 'aku@gmail.com'),
(2, 'ado', '421359a899e6aeb972c11a26fb52ad15', 'ado@gmail.com'),
(3, 'anis', '38a1ffb5ccad9612d3d28d99488ca94b', 'ana@gmail.com'),
(4, 'aniss', '38a1ffb5ccad9612d3d28d99488ca94b', 'anad@gmail.com'),
(5, 'aaaa', '74b87337454200d4d33f80c4663dc5e5', 'agus@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `customer_id`) VALUES
(8, 4),
(9, 5);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `product_id`, `quantity`, `total_price`) VALUES
(25, 9, 1, 3, 60000.00);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'router'),
(3, 'antena'),
(4, 'switch'),
(5, 'RJ45 Connector');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `customer_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `first_name`, `last_name`, `email`, `phone_number`, `customer_address`) VALUES
(4, 'agus', 'efendi', 'agus@gmail.com', '082132137431', 'Jl. Sudirman No. 45, Kelurahan Setiabudi, Kecamatan Setiabudi, Jakarta Selatan, DKI Jakarta 12920'),
(5, 'arifin', 'ilham', 'ilhamku2005@gmail.com', '08213107431', 'Jl. Diponegoro No. 17, Kelurahan Citarum, Kecamatan Bandung Wetan, Bandung, Jawa Barat 40115'),
(7, 'ana', 'eni', 'ana@gmail.com', '085132137431', 'Jl. Malioboro No. 12, Kelurahan Suryatmajan, Kecamatan Danurejan, Yogyakarta 55213'),
(8, 'arian', 'febrian', 'arian@gmail.com', '08213336631', 'Jl. Pemuda No. 88, Kelurahan Sekayu, Kecamatan Semarang Tengah, Semarang, Jawa Tengah 50132'),
(9, 'agung', 'raidi', 'agungrde@gmail.com', '082135037431', 'Jl. Basuki Rahmat No. 25, Kelurahan Kedungdoro, Kecamatan Tegalsari, Surabaya, Jawa Timur 60261'),
(11, 'anas', 'haryadi', 'an.harya@gmail.com', '08515037431', 'Jl. Ahmad Yani No. 10, Kelurahan Kedungrejo, Kecamatan Waru, Sidoarjo, Jawa Timur 61256'),
(12, 'ari', 'gumilang', 'arigu@gmail.com', '082135037431', 'Jl. Asia Afrika No. 9, Kelurahan Braga, Kecamatan Sumur Bandung, Bandung, Jawa Barat 40111'),
(13, 'arzak', 'kristiawan', 'arzakchillguyjakbar@gmail.com', '085132137431', 'Jl. Panglima Sudirman No. 34, Kelurahan Sukorejo, Kecamatan Blimbing, Malang, Jawa Timur 65125'),
(14, 'ahmad', 'dana', 'danaahmad@gmail.com', '0815537431', 'Jl. Kartini No. 5, Kelurahan Kartini, Kecamatan Sawah Besar, Jakarta Pusat, DKI Jakarta 10750');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `login_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`login_id`, `customer_id`, `username`, `password`) VALUES
(4, 4, 'agus', '$2y$10$TvyzDpYcAtE5QlJb5UtUJOBceRCTOrvmOKJkq.jCLJnCgcYZTaET6'),
(5, 5, 'arifin', '$2y$10$wEq0cqY2cx1nCwGT/lfpSODFuex3MqSE3v9pk3cwNjiCw/BWajP5i'),
(6, 7, 'ae', '$2y$10$LgynevrxA6OVR/2/DawT6u0KjELgLMaK00.gNtxSR/fm6jZlzEmx.'),
(7, 8, 'ags', '$2y$10$c3IUJwoMD6a8IytQ9LTb3.YqotPbFD4ao43ffn2fm12FKbWqaYYQy'),
(8, 9, 'ads', '$2y$10$na4VEwbzaIigNYStLWgF5.3JHDyPX5r1Jovvls/BY1zoRkzazALTa'),
(9, 11, 'agoes', '$2y$10$TJe7fDxtkQrz5HVJu/Eln.oJikBKYpPX24UnNSkvXipPCvBEhWO/a'),
(10, 12, 'arian', '$2y$10$nXMTQbVYfH.aCgb9OJTZVuBPAw8n5jQyZkzxaa7K3PH05fgNQ.XMS'),
(11, 13, 'arz', '$2y$10$d2TKtSziZwz7HZKd.aHH5.ADxLKrr.BJqW3oI2B9RlGgdFW.ZiVCG'),
(12, 14, 'qwe', '$2y$10$f2V/zM9sdkeoS7las6DSv.A/NED1g.FsMeQPO6dgYk5XH1YCfoSHK');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_description` text DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_stock` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_price`, `product_description`, `category_id`, `product_image`, `product_stock`) VALUES
(1, 'Antena Omni Vezatech 15 dbi 2.4 Ghz ( Connect )', 775000.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 3, 'images/yy.jpg', 11),
(22, 'UBNT Edge Point Router 6 24 V EP-R6 ( Gloria )', 1500000.00, 'LorLorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborumx ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 1, 'images/ubiquiti.jpg', 22),
(28, 'UBNT Unifi Switch FLEX USW-FLEX ( Spectrum )', 1688865.00, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum', 4, 'images/ubiquitiswc.jpeg', 10),
(29, 'SFP to RJ45 S-RJ01', 460000.00, 'MikroTik S+RJ10 / SFP S RJ10 / SFP MikroTik S+RJ10 ini membuka seluruh dunia dengan kemungkinan konektivitas berkecepatan tinggi, menawarkan kecepatan hingga 10 Gbps', 5, 'images/S-RJ01_2.jpg', 10),
(30, 'Dlink DWR-M910 Wireless N300 4G LTE Router', 500000.00, 'Dlink DWR-M910 Wireless N300 4G LTE Router  2.4GHz (300Mbps)', 1, 'images/drou.png', 30),
(31, 'Antena Omni HG5812U-PRO Hyperlink 12 dbi', 1850000.00, 'Hyperlink Omni Directional 12db 5,8ghz', 3, 'images/ann 11.jpg', 8),
(32, 'Switch Dlink 24 Port DGS 1100-24 EasySmart Switch 12 10', 1614000.00, 'TEG1024F provides 24 10/100/1000 Mbps auto-negotiation RJ45 ports, 2 gigabit SFP slots and offers a data rate in full duplex of as high as 2000 Mbps.', 4, 'images/dgs1100.jpg', 13),
(33, 'Switch HUB 8 Port Gigabit 1420-8G JH329A', 671000.00, 'Switch Unmanaged, plug-n-play\r\n8 Port Gigabit Ethernet Support CAT5e', 4, 'images/hpJH3A.jpg', 18);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `transaction_date` datetime DEFAULT current_timestamp(),
  `customer_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `transaction_date`, `customer_id`, `admin_id`) VALUES
(9, '2024-05-15 11:09:00', 8, NULL),
(10, '2024-04-21 12:00:00', 12, NULL),
(11, '2024-03-12 10:00:00', 9, NULL),
(12, '2024-02-10 13:00:00', 5, NULL),
(13, '2024-01-18 08:00:00', 7, NULL),
(14, '2024-04-30 10:12:25', 14, NULL),
(15, '2024-05-20 07:15:00', 11, NULL),
(16, '2024-03-01 06:21:00', 4, NULL),
(17, '2024-02-25 09:00:00', 13, NULL),
(18, '2024-01-05 05:12:00', 8, NULL),
(29, '2024-12-16 08:22:54', 7, 1),
(30, '2024-11-20 08:25:09', 7, NULL),
(31, '2024-08-21 08:25:09', 7, NULL),
(32, '2024-12-16 19:04:49', 7, NULL),
(33, '2024-12-17 18:40:53', 7, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_detail`
--

CREATE TABLE `transaction_detail` (
  `transaction_detail_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('cancelled','pending','completed') NOT NULL DEFAULT 'pending',
  `payment_proof` varchar(255) NOT NULL,
  `transaction_description` varchar(255) DEFAULT 'sedang diproses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_detail`
--

INSERT INTO `transaction_detail` (`transaction_detail_id`, `transaction_id`, `product_id`, `quantity`, `total_price`, `status`, `payment_proof`, `transaction_description`) VALUES
(10, 9, 1, 3, 23250000.00, 'completed', 'uploads/proof.jpeg', 'Pembayaran Berhasil'),
(11, 10, 30, 1, 500000.00, 'completed', 'uploads/proof.jpeg', 'Pembayaran Berhasil'),
(12, 11, 22, 2, 3000000.00, 'pending', 'uploads/proof.jpeg', ''),
(13, 12, 28, 1, 1688865.00, 'completed', 'uploads/proof.jpeg', 'Pembayaran Berhasil'),
(14, 13, 31, 2, 3700000.00, 'completed', 'uploads/proof.jpeg', 'Pembayaran Berhasil'),
(15, 14, 33, 1, 6710000.00, 'pending', 'uploads/proof.jpeg', ''),
(16, 15, 32, 1, 1614000.00, 'completed', 'uploads/proof.jpeg', 'Pembayaran Berhasil'),
(17, 16, 29, 2, 920000.00, 'completed', 'uploads/proof.jpeg', 'Pembayaran Berhasil'),
(18, 17, 22, 1, 1500000.00, 'completed', 'uploads/proof.jpeg', 'Pembayaran Berhasil'),
(19, 18, 28, 3, 5066595.00, 'pending', 'uploads/proof.jpeg', ''),
(29, 29, 22, 3, 4500000.00, 'completed', 'uploads/proof.jpeg', 'berhasil'),
(30, 30, 1, 1, 775000.00, 'pending', 'uploads/proof.jpeg', ''),
(31, 31, 28, 1, 1688865.00, 'pending', 'uploads/proof.jpeg', ''),
(32, 32, 22, 1, 1500000.00, 'pending', 'uploads/proof.jpeg', ''),
(33, 32, 1, 2, 1550000.00, 'pending', 'uploads/proof.jpeg', ''),
(34, 33, 29, 2, 920000.00, 'pending', 'uploads/proof.jpeg', 'sedang diproses');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `fk_customer_id` (`customer_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`login_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `fk_admin_id` (`admin_id`);

--
-- Indexes for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD PRIMARY KEY (`transaction_detail_id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  MODIFY `transaction_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `login_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`),
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD CONSTRAINT `transaction_detail_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`transaction_id`),
  ADD CONSTRAINT `transaction_detail_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
