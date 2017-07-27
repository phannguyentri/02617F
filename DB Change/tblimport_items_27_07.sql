-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2017 at 06:23 AM
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
-- Table structure for table `tblimport_items`
--

CREATE TABLE IF NOT EXISTS `tblimport_items` (
`id` int(11) NOT NULL,
  `import_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `specifications` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `quantity_net` int(11) DEFAULT '0',
  `unit_cost` decimal(15,0) DEFAULT NULL,
  `sub_total` decimal(15,0) DEFAULT NULL,
  `warehouse_id` int(10) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblimport_items`
--

INSERT INTO `tblimport_items` (`id`, `import_id`, `product_id`, `specifications`, `unit_id`, `quantity`, `quantity_net`, `unit_cost`, `sub_total`, `warehouse_id`) VALUES
(68, 69, 55, '', 1, 5, 0, '125000', '625000', 1),
(69, 69, 54, '', 1, 10, 0, '21312', '213120', 1),
(70, 69, 53, '', 1, 15, 0, '15000', '225000', 1),
(71, 70, 49, '312', 312, 150, 0, '213', '31950', 1),
(72, 70, 35, '2', 1, 100, 0, '300', '30000', 1),
(73, 71, 34, '1', 1, 10, 0, '10000', '100000', 4),
(74, 71, 36, 'dsa', 1, 15, 0, '2147483647', '32212254705', 4),
(75, 71, 37, '213213', 1, 20, 0, '80000', '1600000', 4),
(76, 73, 52, '312', 312, 100, 0, '213', '21300', 3),
(77, 73, 51, '312', 312, 150, 0, '213', '31950', 3),
(78, 74, 55, '', 1, 100, 0, '125000', '12500000', 2),
(79, 74, 37, '213213', 1, 150, 0, '80000', '12000000', 2),
(80, 75, 52, '312', 312, 50, 0, '213', '10650', 5),
(81, 75, 51, '312', 312, 75, 0, '213', '15975', 5),
(82, 76, 55, '', 1, 15, 0, '125000', '1875000', 1),
(83, 76, 54, '', 1, 19, 0, '21312', '404928', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblimport_items`
--
ALTER TABLE `tblimport_items`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblimport_items`
--
ALTER TABLE `tblimport_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=84;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
