-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2017 at 03:58 AM
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
-- Table structure for table `tblsale_orders`
--

CREATE TABLE IF NOT EXISTS `tblsale_orders` (
`id` int(11) NOT NULL,
  `rel_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rel_id` int(11) DEFAULT NULL,
  `prefix` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
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
  `export_status` int(11) DEFAULT '0',
  `delivery_status` int(11) DEFAULT '0',
  `shipping` decimal(25,0) DEFAULT NULL,
  `discount` decimal(25,0) DEFAULT NULL,
  `tax` decimal(25,0) DEFAULT NULL,
  `payment_status` int(11) DEFAULT '0',
  `paid` decimal(25,0) DEFAULT NULL,
  `total` decimal(25,0) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblsale_orders`
--

INSERT INTO `tblsale_orders` (`id`, `rel_type`, `rel_id`, `prefix`, `code`, `delivery_code`, `customer_id`, `name`, `reason`, `note`, `date`, `create_by`, `user_head_id`, `user_head_date`, `user_admin_id`, `user_admin_date`, `total_items`, `status`, `export_status`, `delivery_status`, `shipping`, `discount`, `tax`, `payment_status`, `paid`, `total`) VALUES
(102, 'sale_order', NULL, 'PO-', '00001', NULL, 4, 'Phiếu Đặt Hàng', '', NULL, '2017-08-07 17:00:00', 1, 1, '2017-08-08 16:36:51', 1, '2017-08-08 16:36:51', NULL, 2, 0, 0, NULL, NULL, NULL, 0, NULL, '14631200');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblsale_orders`
--
ALTER TABLE `tblsale_orders`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblsale_orders`
--
ALTER TABLE `tblsale_orders`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=103;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
