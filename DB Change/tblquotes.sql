-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2017 at 07:43 AM
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
-- Table structure for table `tblquotes`
--

CREATE TABLE IF NOT EXISTS `tblquotes` (
`id` int(11) NOT NULL,
  `prefix` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `note` text COLLATE utf8_unicode_ci,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_by` int(11) DEFAULT NULL,
  `user_head_id` int(11) DEFAULT NULL,
  `user_head_date` datetime DEFAULT NULL,
  `user_admin_id` int(11) DEFAULT NULL,
  `user_admin_date` datetime DEFAULT NULL,
  `total_items` tinyint(5) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `shipping` decimal(25,0) DEFAULT NULL,
  `discount` decimal(25,0) DEFAULT NULL,
  `tax` decimal(25,0) DEFAULT NULL,
  `total` decimal(25,0) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblquotes`
--

INSERT INTO `tblquotes` (`id`, `prefix`, `code`, `customer_id`, `name`, `reason`, `note`, `date`, `create_by`, `user_head_id`, `user_head_date`, `user_admin_id`, `user_admin_date`, `total_items`, `status`, `shipping`, `discount`, `tax`, `total`) VALUES
(21, 'QU-', '00001', 4, 'Bảng báo giá', '', NULL, '2017-08-14 17:00:00', 1, 1, '2017-08-15 08:43:11', 1, '2017-08-15 08:43:11', NULL, 2, NULL, NULL, NULL, '14631200'),
(22, 'QU-', '00022', 5, 'Bảng báo giá', '', NULL, '2017-08-14 17:00:00', 1, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '28196800');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblquotes`
--
ALTER TABLE `tblquotes`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblquotes`
--
ALTER TABLE `tblquotes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
