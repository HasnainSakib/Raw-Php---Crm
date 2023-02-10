-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2021 at 02:56 AM
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
-- Database: `crms`
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
(1, 'admin', '123456', '21/12/2020', 'qf7bQqTD7ctBtujV');

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

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`Id`, `date`, `name`, `subject`, `email`, `number`, `message`, `admin_read`, `reply`) VALUES
(4, '2020-12-26 12:09:22', 'sksojib', 'i need 3 mb', 'ssk58021@gmail.com', '+8801718825371', 'pase gsdf me', 1, 'just+wait+a+aminute'),
(3, '2020-12-26 12:09:22', 'sksojib', 'need booking', 'ssk58021@gmail.com', '+8801718825371', 'ertterte', 1, ''),
(5, '2020-12-26 12:09:22', 'sksojib', 'test', 'ssk58021@gmail.com', '+8801718825371', 'sdfsdf', 1, 'okk+');

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

--
-- Dumping data for table `customer_orders`
--

INSERT INTO `customer_orders` (`id`, `date`, `username`, `ad_manager_id`, `service_type`, `package`, `amount`, `payment_id`, `rm`, `status`) VALUES
(20, '2020-12-25 12:09:22', 'ssk58021@gmail.com', '', 1, 7, 1500, '25', 0, 3),
(19, '2020-12-24 12:09:22', 'ssk58021@gmail.com', '', 1, 4, 1200, '24', 0, 3),
(18, '2020-12-23 12:09:22', 'ssk58021@gmail.com', '', 1, 2, 800, '23', 0, 3),
(17, '2020-12-22 12:09:22', 'ssk58021@gmail.com', '', 1, 8, 2000, '22', 0, 3),
(16, '2020-12-21 12:09:22', 'ssk58021@gmail.com', '', 1, 2, 800, '21', 0, 3),
(21, '2021-01-17 16:27:00', 'sazzad@gmail.com', '', 1, 2, 800, '27', 0, 3);

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
(16, '24/12/20', 'Internet service', '3 mb', 800, 'bkash', 'Md. Zabed nasiruddin'),
(17, '24/12/20', 'digital marketing', 'facebook add', 2000, 'bkash', 'MD mijanur rahman');

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
(8, '20 MBPS', 2000, 5, '<li><div class=\"tooltip-wide\">Optical Fiber connectivity</div></li><li><div class=\"tooltip-wide\">27Ã—7 Online Support</div></li><li><div class=\"tooltip-wide\">With buffer free Youtube & Facebook</div></li><li><div class=\"tooltip-wide\">With Largest FTP and Highest number of partner FTP</div></li><li><div class=\"tooltip-wide\">Live Streaming</div></li><li><div class=\"tooltip-wide\">With BDIX Facility</div></li><li><div class=\"tooltip-wide\">Online Gaming Server</div></li>', 1);

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

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `date`, `username`, `type`, `amount`, `method`, `payee_number`, `trxn_id`, `reference`, `bank_name`, `bank_attachment`, `remark`) VALUES
(25, '2020-12-31 11:19:58', 'ssk58021@gmail.com', 'due_payment', 0, 'due', '', '', '', '', '', 0),
(24, '2020-12-31 11:19:58', 'ssk58021@gmail.com', 'due_payment', 0, 'due', '', '', '', '', '', 0),
(23, '2020-12-31 11:19:58', 'ssk58021@gmail.com', 'order_payment', 0, 'bkash', '017188253711', '0fd5g56d0fg0dfg11', '', '', '', 0),
(21, '2020-12-31 11:19:58', 'ssk58021@gmail.com', 'order_payment', 0, 'bkash', '01718825371', '0fd5g56d0fg0dfg', '', '', '', 0),
(22, '2020-12-31 11:19:58', 'ssk58021@gmail.com', 'due_payment', 0, 'due', '', '', '', '', '', 0),
(27, '2021-01-17 16:27:00', 'sazzad@gmail.com', 'order_payment', 0, 'bkash', '01718825371', '06165165chgv', '', '', '', 0);

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
(207, 'ssk58021@gmail.com', '123456', 'QOq9PNyKhIV4ntN1', 'sksojib', '', 'ssk58021@gmail.com', 'akaar it', '', '', '+8801718825371', 'sksojib', '2020-12-31 11:19:58'),
(208, 'sazzad@gmail.com', '123456', '2uD1JhXGfGJMkanJ', 'sazzad', '', 'sazzad@gmail.com', 'akaar it', '', '', '+881718825371', 'sk', '2021-01-17 16:24:21');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
