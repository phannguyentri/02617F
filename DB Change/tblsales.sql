-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2017 at 08:46 AM
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
-- Table structure for table `tblsales`
--

CREATE TABLE IF NOT EXISTS `tblsales` (
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
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblsales`
--

INSERT INTO `tblsales` (`id`, `rel_type`, `rel_id`, `prefix`, `code`, `delivery_code`, `customer_id`, `name`, `reason`, `note`, `date`, `create_by`, `user_head_id`, `user_head_date`, `user_admin_id`, `user_admin_date`, `total_items`, `status`, `export_status`, `delivery_status`, `shipping`, `discount`, `tax`, `payment_status`, `paid`, `total`) VALUES
(85, 'sale_order', NULL, 'SO-', '00085', NULL, 6, 'Phiếu Đặt Hàng', 'dhgdfh', NULL, '2017-07-27 17:00:00', 1, 1, '2017-07-31 15:56:54', 1, '2017-07-31 15:56:54', NULL, 2, 1, 0, NULL, NULL, NULL, 0, NULL, '15696800'),
(86, 'sale_order', NULL, 'SO-', '00086', NULL, 4, 'Phiếu Đặt Hàng', 'dsfgvdgvdf &nbsp;dffrff', NULL, '2017-07-31 17:00:00', 1, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, NULL, NULL, NULL, 0, NULL, '13250000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblsales`
--
ALTER TABLE `tblsales`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblsales`
--
ALTER TABLE `tblsales`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=87;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
