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
-- Table structure for table `tblimports`
--

CREATE TABLE IF NOT EXISTS `tblimports` (
`id` int(11) NOT NULL,
  `rel_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rel_id` int(11) DEFAULT NULL,
  `prefix` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `create_by` int(11) DEFAULT NULL,
  `user_head_id` int(11) DEFAULT NULL,
  `user_head_date` datetime DEFAULT NULL,
  `user_admin_id` int(11) DEFAULT NULL,
  `user_admin_date` datetime DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `total` decimal(15,0) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tblimports`
--

INSERT INTO `tblimports` (`id`, `rel_type`, `rel_id`, `prefix`, `code`, `name`, `reason`, `note`, `date`, `create_by`, `user_head_id`, `user_head_date`, `user_admin_id`, `user_admin_date`, `status`, `total`) VALUES
(69, 'adjustment', NULL, 'ĐC-', '00001', 'Phieu dck', 'Note', NULL, '2017-07-25 17:00:00', 1, 1, '2017-07-26 15:25:24', 1, '2017-07-26 15:25:24', 2, '1063120'),
(70, 'adjustment', NULL, 'ĐC-', '00070', 'PDCK', 'Note', NULL, '2017-07-25 17:00:00', 1, 1, '2017-07-26 15:27:29', 1, '2017-07-26 15:27:29', 2, '61950'),
(71, 'adjustment', NULL, 'ĐC-', '00071', 'DC KHo', 'Note', NULL, '2017-07-25 17:00:00', 1, 1, '2017-07-26 16:01:13', 1, '2017-07-26 16:01:13', 2, '32213954705'),
(74, 'adjustment', NULL, 'ĐC-', '00073', 'DCK', 'Note', NULL, '2017-07-25 17:00:00', 1, 1, '2017-07-26 17:24:24', 1, '2017-07-26 17:24:24', 2, '24500000'),
(75, 'internal', NULL, 'NĐ-', '00075', 'New ND', 'Ghi chu', NULL, '2017-07-25 17:00:00', 1, 1, '2017-07-26 17:26:09', 1, '2017-07-26 17:26:09', 2, '26625'),
(76, 'adjustment', NULL, 'ĐC-', '00076', 'xfgvbdf', 'dfbdfb', NULL, '2017-07-14 17:00:00', 1, NULL, NULL, NULL, NULL, 0, '2279928');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblimports`
--
ALTER TABLE `tblimports`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblimports`
--
ALTER TABLE `tblimports`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
