-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2017 at 07:59 AM
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
-- Table structure for table `tblstaff`
--

CREATE TABLE IF NOT EXISTS `tblstaff` (
`staffid` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `staff_code` varchar(20) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `staff_manager` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `gender` int(11) DEFAULT '1',
  `date_birth` date DEFAULT NULL,
  `place_birth` varchar(255) DEFAULT NULL,
  `permanent_residence` varchar(255) DEFAULT NULL,
  `current_address` varchar(255) DEFAULT NULL,
  `passport_id` varchar(15) DEFAULT NULL,
  `issued_by` varchar(255) DEFAULT NULL,
  `issued_on` date DEFAULT NULL,
  `hobbies` varchar(255) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `marial_status` varchar(50) DEFAULT NULL,
  `emergency_contact` text,
  `education` text,
  `foreign_languge_skills` text,
  `other_certificates` text,
  `facebook` mediumtext,
  `linkedin` mediumtext,
  `phonenumber` varchar(30) DEFAULT NULL,
  `skype` varchar(50) DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `datecreated` datetime NOT NULL,
  `profile_image` varchar(300) DEFAULT NULL,
  `last_ip` varchar(40) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_password_change` datetime DEFAULT NULL,
  `new_pass_key` varchar(32) DEFAULT NULL,
  `new_pass_key_requested` datetime DEFAULT NULL,
  `admin` int(11) NOT NULL DEFAULT '0',
  `role` int(11) DEFAULT NULL,
  `rule` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `default_language` varchar(40) DEFAULT NULL,
  `direction` varchar(3) DEFAULT NULL,
  `media_path_slug` varchar(300) DEFAULT NULL,
  `is_not_staff` int(11) DEFAULT '0',
  `hourly_rate` decimal(11,2) DEFAULT '0.00',
  `salary` decimal(15,0) DEFAULT NULL,
  `email_signature` text,
  `bank_account` varchar(255) DEFAULT NULL,
  `internal_phone` varchar(255) DEFAULT NULL,
  `date_work` date DEFAULT NULL,
  `place_work` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tblstaff`
--

INSERT INTO `tblstaff` (`staffid`, `email`, `staff_code`, `position_id`, `staff_manager`, `fullname`, `firstname`, `lastname`, `gender`, `date_birth`, `place_birth`, `permanent_residence`, `current_address`, `passport_id`, `issued_by`, `issued_on`, `hobbies`, `height`, `weight`, `marial_status`, `emergency_contact`, `education`, `foreign_languge_skills`, `other_certificates`, `facebook`, `linkedin`, `phonenumber`, `skype`, `password`, `datecreated`, `profile_image`, `last_ip`, `last_login`, `last_password_change`, `new_pass_key`, `new_pass_key_requested`, `admin`, `role`, `rule`, `active`, `default_language`, `direction`, `media_path_slug`, `is_not_staff`, `hourly_rate`, `salary`, `email_signature`, `bank_account`, `internal_phone`, `date_work`, `place_work`) VALUES
(1, 'amin@admin.com', NULL, NULL, NULL, 'admin', '', '', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2a$08$9uFKA7CEZjqLO3zSOQfPBul5FwOw8Xwj6pJs4onV4gHAn9Tlcv762', '2017-03-30 09:24:10', NULL, '192.168.1.3', '2017-07-29 08:17:13', NULL, NULL, NULL, 1, NULL, 1, 1, 'vietnamese', NULL, NULL, 0, '0.00', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'buiphamthanhthuy@gmail.com', 'NV-00002', 0, '["1"]', 'Tan Nguyen', 'Tân', 'Nguyễn', 1, '0000-00-00', '', '', '', '', '', '0000-00-00', '', 0, 0, 'single', '', NULL, NULL, NULL, 'tannguyen', '', '0909365456', '', '$2a$08$GMPg1TJgHJsyM9Oa1bp4veWqPqkglBTxdmU.OkFTM8lJ9OS8oLwRe', '2017-03-31 00:37:49', NULL, '::1', '2017-03-31 00:42:57', NULL, NULL, NULL, 0, 1, 2, 1, 'vietnamese', '', 'tan-nguyễn', 0, '200.00', '0', '', '', '', '0000-00-00', 1),
(3, 'ngocha@gmail.com', 'NV-00003', 0, 'null', 'Ngoc ha', 'Ngọc', 'Hà', 1, '0000-00-00', '', '', '', '', '', '0000-00-00', '', 0, 0, 'single', '', NULL, NULL, NULL, '', '', '0909321456', '', '$2a$08$9uFKA7CEZjqLO3zSOQfPBul5FwOw8Xwj6pJs4onV4gHAn9Tlcv762', '2017-03-31 00:49:22', '2016-09-19-13.jpg', '::1', '2017-07-07 15:48:58', NULL, NULL, NULL, 0, 2, 3, 1, 'vietnamese', '', 'ngọc-ha', 0, '200.00', '0', '', '', '', '0000-00-00', 1),
(4, 'thuy@gmail.com', 'NV00004', 4, '["3"]', 'Thuy linh', 'Thùy', 'Linh', 1, '2017-06-30', 'HCM', 'HCM', 'HCM', '321348455', 'HCM', '2017-06-30', '', 0, 0, 'single', '12345', NULL, NULL, NULL, '', '', '09123456789', '', '$2a$08$mxYDHk1OwXmcx7QVbtKDTeKprQua5DSEDZTLEhpg65wYscNF2RY86', '2017-03-31 00:50:39', NULL, '::1', '2017-04-03 12:57:20', NULL, NULL, NULL, 0, 2, 4, 1, 'vietnamese', '', 'thuy-linh', 0, '150.00', '100', '', '1234', '5678', '2017-06-30', 1),
(5, 'tvtan06@gmail.com', 'NV-00005', NULL, '["2"]', 'Tran Van Tan', 'Tran Van', ' Tan', 0, '2017-06-29', 'HCM', 'HCM', 'HCM', '123345', 'HCM', '2017-06-29', '', 0, 0, 'single', '12342435', NULL, NULL, NULL, NULL, NULL, '0939701693', NULL, '$2a$08$XmWgJQjif5lqJaQ52GrdQu1JwqbSC/3r1Qhvm8pn/JsecZhHVwOfK', '2017-06-29 17:08:57', NULL, NULL, NULL, '2017-06-29 17:31:13', NULL, NULL, 0, 1, 3, 1, NULL, NULL, 'tran-van-tan', 0, '0.00', '0', '', '1234', '', '0000-00-00', 0),
(6, 'ttktien@gmail.com', 'NV-00014', 0, 'null', 'Tran Kieu Tien', 'Tran Thij Kieu', ' Tien', 1, '2017-06-30', 'HCM', 'HCM', 'HCM', '123456', 'HCM', '2017-06-30', '', 0, 0, 'single', '12345', NULL, NULL, NULL, NULL, NULL, '0974497157', NULL, '$2a$08$j910DGb36PHhwxkO5/liBe1ekdF.8TNG8JzISyNHPn6YqvNW3Ed.S', '2017-06-30 08:14:55', NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, 3, 1, NULL, NULL, 'tran-thij-kieu-tien', 0, '0.00', '500', '', '1234', '4321', '2017-06-30', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblstaff`
--
ALTER TABLE `tblstaff`
 ADD PRIMARY KEY (`staffid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblstaff`
--
ALTER TABLE `tblstaff`
MODIFY `staffid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
