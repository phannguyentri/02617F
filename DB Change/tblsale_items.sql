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
-- Table structure for table `tblsale_items`
--

CREATE TABLE IF NOT EXISTS `tblsale_items` (
`id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblsale_items`
--

INSERT INTO `tblsale_items` (`id`, `sale_id`, `product_id`, `serial_no`, `unit_id`, `quantity`, `export_quantity`, `tax`, `discount`, `unit_cost`, `sub_total`, `warehouse_id`) VALUES
(87, 87, 55, NULL, 1, 10, 0, '0', '0', '125000', '1250000', NULL),
(88, 87, 54, NULL, 1, 15, 0, '0', '0', '21312', '319680', NULL),
(89, 85, 55, NULL, 1, 100, 100, '0', '0', '125000', '12500000', NULL),
(90, 85, 54, NULL, 1, 150, 150, '0', '0', '21312', '3196800', NULL),
(91, 86, 55, NULL, 1, 100, 0, '0', '0', '125000', '12500000', NULL),
(92, 86, 53, NULL, 1, 50, 0, '0', '0', '15000', '750000', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblsale_items`
--
ALTER TABLE `tblsale_items`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblsale_items`
--
ALTER TABLE `tblsale_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=93;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
