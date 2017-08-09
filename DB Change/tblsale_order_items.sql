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
-- Table structure for table `tblsale_order_items`
--

CREATE TABLE IF NOT EXISTS `tblsale_order_items` (
`id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `reject_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `serial_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `export_quantity` int(11) DEFAULT NULL,
  `tax` decimal(15,0) DEFAULT '0',
  `discount` decimal(15,0) DEFAULT '0',
  `unit_cost` decimal(15,0) DEFAULT NULL,
  `sub_total` decimal(15,0) DEFAULT NULL,
  `warehouse_id` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblsale_order_items`
--

INSERT INTO `tblsale_order_items` (`id`, `sale_id`, `reject_id`, `product_id`, `serial_no`, `unit_id`, `quantity`, `export_quantity`, `tax`, `discount`, `unit_cost`, `sub_total`, `warehouse_id`) VALUES
(129, 102, NULL, 55, NULL, 1, 100, NULL, '0', '0', '125000', '12500000', 1),
(130, 102, NULL, 54, NULL, 1, 100, NULL, '0', '0', '21312', '2131200', 1),
(131, NULL, 102, 55, NULL, 1, 1, NULL, '0', '0', '125000', '125000', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblsale_order_items`
--
ALTER TABLE `tblsale_order_items`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblsale_order_items`
--
ALTER TABLE `tblsale_order_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=132;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
