-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2017 at 07:58 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dudoffhoa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblexports`
--

CREATE TABLE IF NOT EXISTS `tblexports` (
`id` int(11) NOT NULL,
  `rel_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rel_id` int(11) DEFAULT NULL,
  `rel_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prefix` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_by` int(11) DEFAULT NULL,
  `user_head_id` int(11) DEFAULT NULL,
  `user_head_date` datetime DEFAULT NULL,
  `user_admin_id` int(11) DEFAULT NULL,
  `user_admin_date` datetime DEFAULT NULL,
  `total_items` tinyint(5) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `delivery_status` int(11) DEFAULT '0',
  `shipping` decimal(25,0) DEFAULT NULL,
  `discount` decimal(25,0) DEFAULT NULL,
  `tax` decimal(25,0) DEFAULT NULL,
  `payment_status` int(11) DEFAULT '0',
  `paid` decimal(25,0) DEFAULT NULL,
  `total` decimal(25,0) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblexports`
--

INSERT INTO `tblexports` (`id`, `rel_type`, `rel_id`, `rel_code`, `prefix`, `code`, `receiver_id`, `customer_id`, `name`, `reason`, `note`, `date`, `create_by`, `user_head_id`, `user_head_date`, `user_admin_id`, `user_admin_date`, `total_items`, `status`, `delivery_status`, `shipping`, `discount`, `tax`, `payment_status`, `paid`, `total`) VALUES
(81, 'sale_order', NULL, 'S0-00009', 'XK-', '00001', 0, 1, 'Phiếu Đặt Hàng', 'dfbgvfdb', NULL, '2017-07-26 17:00:00', 1, 1, '2017-07-27 17:45:16', 1, '2017-07-27 17:45:16', NULL, 2, 0, NULL, NULL, NULL, 0, NULL, NULL),
(82, 'sale_order', NULL, NULL, 'XK-', '00002', 0, 1, 'Phiếu Đặt Hàng', 'dfbgvfdb', NULL, '2017-07-26 17:00:00', 1, 1, '2017-07-27 17:46:02', 1, '2017-07-27 17:46:02', NULL, 2, 0, NULL, NULL, NULL, 0, NULL, NULL),
(83, 'sale_order', NULL, NULL, 'XK-', '00003', 0, 1, 'Phiếu Đặt Hàng', 'dfbgvfdb', NULL, '2017-07-26 17:00:00', 1, 1, '2017-07-27 17:46:45', 1, '2017-07-27 17:46:45', NULL, 2, 0, NULL, NULL, NULL, 0, NULL, NULL),
(84, 'sale_order', NULL, NULL, 'XK-', '00004', 0, 1, 'Phiếu Đặt Hàng', 'dfbgvfdb', NULL, '2017-07-26 17:00:00', 1, 1, '2017-07-28 11:26:19', 1, '2017-07-28 11:26:19', NULL, 2, 0, NULL, NULL, NULL, 0, NULL, NULL),
(85, 'sale_order', NULL, 'SO-00013', 'XK-', '00005', 0, 6, 'Phiếu Đặt Hàng', 'dhgdfh', NULL, '2017-07-27 17:00:00', 1, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, 0, NULL, '15696800');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblexports`
--
ALTER TABLE `tblexports`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblexports`
--
ALTER TABLE `tblexports`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=86;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
