-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2020 at 06:28 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_crm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `password` varchar(200) COLLATE latin1_general_ci NOT NULL,
  `date_added` varchar(900) COLLATE latin1_general_ci NOT NULL,
  `token` varchar(20) COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `date_added`, `token`) VALUES
(1, 'admin', '123456', '21/11/17', 'qf7bQqTD7ctBtujV');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `Id` int(11) NOT NULL,
  `date` datetime DEFAULT NULL,
  `name` varchar(900) COLLATE latin1_general_ci NOT NULL,
  `subject` varchar(900) COLLATE latin1_general_ci NOT NULL,
  `email` varchar(900) COLLATE latin1_general_ci NOT NULL,
  `number` varchar(90) COLLATE latin1_general_ci NOT NULL,
  `message` longtext COLLATE latin1_general_ci NOT NULL,
  `admin_read` int(11) NOT NULL,
  `reply` text COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_orders`
--

CREATE TABLE `customer_orders` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `username` varchar(90) NOT NULL,
  `ad_manager_id` varchar(900) NOT NULL,
  `service_type` int(11) NOT NULL,
  `package` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_id` varchar(20) NOT NULL,
  `rm` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `service` varchar(20) NOT NULL,
  `name` varchar(90) NOT NULL,
  `email` varchar(90) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `id` int(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`service`, `name`, `email`, `mobile`, `id`) VALUES
('internet service', 'sojib', 'sojib@gmail.com', '01718825371', 1);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `service` varchar(100) NOT NULL,
  `package` varchar(100) NOT NULL,
  `amount` int(100) NOT NULL,
  `payment-type` varchar(100) NOT NULL,
  `bill_to` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `date`, `service`, `package`, `amount`, `payment-type`, `bill_to`) VALUES
(16, '24/3/19', 'Internet service', '3 mb', 800, 'bkash', 'Md. Zabed nasiruddin');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `pack_name` varchar(90) NOT NULL,
  `price` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `pack_info` text NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `pack_name`, `price`, `amount`, `pack_info`, `service_id`) VALUES
(2, '3 Mbps', 800, 3, '<li><div class=\"tooltip-wide\">Optical Fiber connectivity</div></li><li><div class=\"tooltip-wide\">27Ã—7 Online Support</div></li><li><div class=\"tooltip-wide\">With buffer free Youtube & Facebook</div></li><li><div class=\"tooltip-wide\">With Largest FTP and Highest number of partner FTP</div></li><li><div class=\"tooltip-wide\">Live Streaming</div></li><li><div class=\"tooltip-wide\">With BDIX Facility</div></li><li><div class=\"tooltip-wide\">Online Gaming Server</div></li>', 1),
(3, '5 Mbps', 1000, 0, '<li><div class=\"tooltip-wide\">Optical Fiber connectivity</div></li><li><div class=\"tooltip-wide\">27Ã—7 Online Support</div></li><li><div class=\"tooltip-wide\">With buffer free Youtube & Facebook</div></li><li><div class=\"tooltip-wide\">With Largest FTP and Highest number of partner FTP</div></li><li><div class=\"tooltip-wide\">Live Streaming</div></li><li><div class=\"tooltip-wide\">With BDIX Facility</div></li><li><div class=\"tooltip-wide\">Online Gaming Server</div></li>', 1),
(4, '8 Mbps', 1200, 0, '<li><div class=\"tooltip-wide\">Optical Fiber connectivity</div></li><li><div class=\"tooltip-wide\">27Ã—7 Online Support</div></li><li><div class=\"tooltip-wide\">With buffer free Youtube & Facebook</div></li><li><div class=\"tooltip-wide\">With Largest FTP and Highest number of partner FTP</div></li><li><div class=\"tooltip-wide\">Live Streaming</div></li><li><div class=\"tooltip-wide\">With BDIX Facility</div></li><li><div class=\"tooltip-wide\">Online Gaming Server</div></li>', 1),
(7, '10 Mbps', 1500, 0, '<li><div class=\"tooltip-wide\">Optical Fiber connectivity</div></li><li><div class=\"tooltip-wide\">27Ã—7 Online Support</div></li><li><div class=\"tooltip-wide\">With buffer free Youtube & Facebook</div></li><li><div class=\"tooltip-wide\">With Largest FTP and Highest number of partner FTP</div></li><li><div class=\"tooltip-wide\">Live Streaming</div></li><li><div class=\"tooltip-wide\">With BDIX Facility</div></li><li><div class=\"tooltip-wide\">Online Gaming Server</div></li>', 1),
(8, 'Digital marketing', 2000, 5, '<li><div class=\"tooltip-wide\">Optical Fiber connectivity</div></li><li><div class=\"tooltip-wide\">27Ã—7 Online Support</div></li><li><div class=\"tooltip-wide\">With buffer free Youtube & Facebook</div></li><li><div class=\"tooltip-wide\">With Largest FTP and Highest number of partner FTP</div></li><li><div class=\"tooltip-wide\">Live Streaming</div></li><li><div class=\"tooltip-wide\">With BDIX Facility</div></li><li><div class=\"tooltip-wide\">Online Gaming Server</div></li>', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `username` varchar(90) NOT NULL,
  `type` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `method` varchar(20) NOT NULL,
  `payee_number` varchar(20) NOT NULL,
  `trxn_id` varchar(50) NOT NULL,
  `reference` varchar(900) NOT NULL,
  `bank_name` varchar(20) NOT NULL,
  `bank_attachment` varchar(900) NOT NULL,
  `remark` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `username` varchar(90) NOT NULL,
  `userid` int(11) NOT NULL,
  `message` longtext NOT NULL,
  `attatchments` varchar(900) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `support_ticket`
--

CREATE TABLE `support_ticket` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(90) NOT NULL,
  `email` varchar(90) NOT NULL,
  `type` varchar(9) NOT NULL,
  `priority` varchar(9) NOT NULL,
  `subject` varchar(900) NOT NULL,
  `related_service` varchar(9) NOT NULL,
  `status` int(11) NOT NULL,
  `last_updated` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(900) NOT NULL,
  `password` varchar(90) NOT NULL,
  `token` varchar(90) NOT NULL,
  `first_name` varchar(900) NOT NULL,
  `last_name` varchar(900) NOT NULL,
  `email` varchar(900) NOT NULL,
  `address` varchar(900) NOT NULL,
  `city` varchar(900) NOT NULL,
  `district` varchar(900) NOT NULL,
  `mobile_number` varchar(900) NOT NULL,
  `facebook_page` varchar(90) NOT NULL,
  `joined` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `token`, `first_name`, `last_name`, `email`, `address`, `city`, `district`, `mobile_number`, `facebook_page`, `joined`) VALUES
(209, 'user@gmail.com', '123', 'CeKFcY42FF91jZRl', 'sazzad', '', 'user@gmail.com', 'akaar it', '', '', '+8801718825371', 'sazzad', '2020-12-10 23:20:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `customer_orders`
--
ALTER TABLE `customer_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_ticket`
--
ALTER TABLE `support_ticket`
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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customer_orders`
--
ALTER TABLE `customer_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
