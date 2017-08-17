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
-- Table structure for table `tblquote_items`
--

CREATE TABLE IF NOT EXISTS `tblquote_items` (
`id` int(11) NOT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `serial_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `tax` decimal(15,0) DEFAULT '0',
  `discount` decimal(15,0) DEFAULT '0',
  `unit_cost` decimal(15,0) DEFAULT NULL,
  `sub_total` decimal(15,0) DEFAULT NULL,
  `warehouse_id` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblquote_items`
--

INSERT INTO `tblquote_items` (`id`, `quote_id`, `product_id`, `serial_no`, `unit_id`, `quantity`, `tax`, `discount`, `unit_cost`, `sub_total`, `warehouse_id`) VALUES
(28, 21, 55, NULL, 1, 100, '0', '0', '125000', '12500000', 1),
(29, 21, 54, NULL, 1, 100, '0', '0', '21312', '2131200', 1),
(30, 22, 55, NULL, 1, 200, '0', '0', '125000', '25000000', 1),
(31, 22, 54, NULL, 1, 150, '0', '0', '21312', '3196800', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblquote_items`
--
ALTER TABLE `tblquote_items`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblquote_items`
--
ALTER TABLE `tblquote_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
