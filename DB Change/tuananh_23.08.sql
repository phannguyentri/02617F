-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 23, 2017 lúc 03:38 SA
-- Phiên bản máy phục vụ: 10.1.21-MariaDB
-- Phiên bản PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dudoffhoa`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblpurchase_contracts`
--

CREATE TABLE `tblpurchase_contracts` (
  `id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `id_order` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_user_create` int(11) NOT NULL,
  `converted` int(11) NOT NULL DEFAULT '0',
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `terms_of_sale` longtext COLLATE utf8_unicode_ci NOT NULL,
  `shipping_terms` longtext COLLATE utf8_unicode_ci NOT NULL,
  `template` longtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tblpurchase_contracts`
--

INSERT INTO `tblpurchase_contracts` (`id`, `code`, `id_order`, `currency_id`, `id_supplier`, `id_user_create`, `converted`, `date_create`, `terms_of_sale`, `shipping_terms`, `template`) VALUES
(7, 'HĐ-00001', 9, 3, 6, 1, 0, '2017-08-19 00:00:00', '', '', ''),
(14, 'HĐ-00008', 8, 1, 1, 1, 0, '2017-08-21 00:00:00', '<span>Điều khoản thanh toán</span>', '<span>Điều khoản vận chuyển</span>', '<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td style=\"width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 18pt;\"><strong>{companyname}</strong></span></td>\n<td style=\"width: 50%; text-align: right;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 24pt;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><span style=\"color: #800000;\"><span style=\"color: #800000;\"><span style=\"color: #c04e4e;\"><span style=\"color: #c04e4e;\"><span style=\"color: #c04e4e;\"><span style=\"color: #c04e4e;\"><span style=\"color: #c04e4e;\"><span style=\"color: #ff0000;\"><strong>PRO FORMA INVOICE</strong></span></span></span></span></span></span></span></span></span></span></td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 14pt;\"><strong>&nbsp;</strong></span></p>\n<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td style=\"width: 50%; vertical-align: top;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\">{invoice_company_address}</span><br /><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\">{invoice_company_city}</span><br /><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\">Phone: {invoice_company_phonenumber}</span><br /><br /></td>\n<td style=\"width: 50%; text-align: right;\">\n<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td style=\"text-align: left; width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\"><strong>Date</strong></span></td>\n<td style=\"width: 50%; text-align: center;\"></td>\n</tr>\n<tr>\n<td style=\"text-align: left; width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\"><strong>Expiration Date</strong></span></td>\n<td style=\"width: 50%; text-align: center;\"></td>\n</tr>\n<tr>\n<td style=\"text-align: left; width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\"><strong>Invoice #</strong></span></td>\n<td style=\"width: 50%; text-align: center;\">{contract_id}</td>\n</tr>\n<tr>\n<td style=\"text-align: left; width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\"><strong>Customer ID</strong></span></td>\n<td style=\"width: 50%; text-align: center;\"></td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 14pt;\"><strong>&nbsp;</strong></span></p>\n<table style=\"width: 100%; border-spacing: 10px 0;\">\n<tbody>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px; background-color: #800000;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><strong><span style=\"background-color: #800000; color: #ffffff;\">CUSTOMER</span></strong></span></span></span></span></span></span></span></td>\n<td style=\"height: 12px; width: 33.33%; background-color: #800000;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><strong><span style=\"color: #ffffff;\">SHIP TO</span></strong></span></span></span></td>\n<td style=\"height: 12px; width: 33.33%; background-color: #800000;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><strong><span style=\"color: #ffffff;\">SHIPPING DETAILS</span></strong></span></span></span></span></td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{invoice_company_name}</td>\n<td style=\"height: 12px; width: 33.33%;\">Freight Type</td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{companyname}</td>\n<td style=\"height: 12px; width: 33.33%;\">Est Ship Date</td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{invoice_company_address}</td>\n<td style=\"height: 12px; width: 33.33%;\">Est Gross Weight</td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{invoice_company_city}</td>\n<td style=\"height: 12px; width: 33.33%;\">Est Cubic Weight</td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{invoice_company_phonenumber}</td>\n<td style=\"height: 12px; width: 33.33%;\">Total Packages</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><span style=\"font-size: 14pt;\"><strong><br /></strong></span>{contract_item_list}<br /><br /></span></p>\n<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td style=\"width: 100%; background-color: #800000;\"><span style=\"font-size: 12pt;\"><strong><span style=\"font-family: arial, helvetica, sans-serif; color: #ffffff;\"><span style=\"font-family: arial, helvetica, sans-serif;\">TERMS OF SALE AND OTHER COMMENTS</span></span></strong></span></td>\n</tr>\n<tr>\n<td style=\"width: 100%;\">{terms_of_sale}<br /><br />{terms_of_payment}</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"></span><br /><br /></p>\n<table style=\"width: 100%;\" class=\"table\">\n<tbody>\n<tr>\n<td colspan=\"2\" style=\"background-color: #800000;\"><span style=\"color: #ffffff; font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><strong>ADDITIONAL DETAILS</strong></span></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\">Country of Origin</span></td>\n<td style=\"width: 70%;\"></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\">Port of Embarkation</td>\n<td style=\"width: 70%;\"></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\">Port of Discharge</td>\n<td style=\"width: 70%;\"></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\"></td>\n<td style=\"width: 70%;\"></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\">Reason for Export:</td>\n<td style=\"width: 70%; border: 1px solid #000000; border-color: #000000;\"></td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: left;\"><br />I certify the above to be true and correct to the best of my knowledge.<br /><br /></p>\n<table width=\"100%\">\n<tbody>\n<tr>\n<td width=\"80\">x&nbsp;</td>\n<td width=\"83\"></td>\n<td width=\"89\"></td>\n<td width=\"47\"></td>\n<td width=\"80\"></td>\n<td colspan=\"2\" width=\"169\">&nbsp;</td>\n</tr>\n<tr>\n<td colspan=\"2\">[Typed Name]</td>\n<td>&nbsp;</td>\n<td>&nbsp;</td>\n<td></td>\n<td>Date</td>\n<td>&nbsp;</td>\n</tr>\n<tr>\n<td colspan=\"2\">[Company Name]</td>\n<td></td>\n<td></td>\n<td></td>\n<td></td>\n<td></td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: left;\"><br /><span style=\"font-size: 14pt;\"><strong></strong></span></p>'),
(15, 'HĐ-00008', 8, 1, 1, 1, 0, '2017-08-21 00:00:00', '1231231', '213123123213', '<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td style=\"width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 18pt;\"><strong>{companyname}</strong></span></td>\n<td style=\"width: 50%; text-align: right;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 24pt;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><span style=\"color: #800000;\"><span style=\"color: #800000;\"><span style=\"color: #c04e4e;\"><span style=\"color: #c04e4e;\"><span style=\"color: #c04e4e;\"><span style=\"color: #c04e4e;\"><span style=\"color: #c04e4e;\"><span style=\"color: #ff0000;\"><strong>PRO FORMA INVOICE</strong></span></span></span></span></span></span></span></span></span></span></td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 14pt;\"><strong>&nbsp;</strong></span></p>\n<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td style=\"width: 50%; vertical-align: top;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\">{invoice_company_address}</span><br /><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\">{invoice_company_city}</span><br /><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\">Phone: {invoice_company_phonenumber}</span><br /><br /></td>\n<td style=\"width: 50%; text-align: right;\">\n<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td style=\"text-align: left; width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\"><strong>Date</strong></span></td>\n<td style=\"width: 50%; text-align: center;\"></td>\n</tr>\n<tr>\n<td style=\"text-align: left; width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\"><strong>Expiration Date</strong></span></td>\n<td style=\"width: 50%; text-align: center;\"></td>\n</tr>\n<tr>\n<td style=\"text-align: left; width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\"><strong>Invoice #</strong></span></td>\n<td style=\"width: 50%; text-align: center;\">{contract_id}</td>\n</tr>\n<tr>\n<td style=\"text-align: left; width: 50%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\"><strong>Customer ID</strong></span></td>\n<td style=\"width: 50%; text-align: center;\"></td>\n</tr>\n</tbody>\n</table>\n</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 14pt;\"><strong>&nbsp;</strong></span></p>\n<table style=\"width: 100%; border-spacing: 10px 0;\">\n<tbody>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px; background-color: #800000;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><strong><span style=\"background-color: #800000; color: #ffffff;\">CUSTOMER</span></strong></span></span></span></span></span></span></span></td>\n<td style=\"height: 12px; width: 33.33%; background-color: #800000;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><strong><span style=\"color: #ffffff;\">SHIP TO</span></strong></span></span></span></td>\n<td style=\"height: 12px; width: 33.33%; background-color: #800000;\"><span style=\"font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: arial, helvetica, sans-serif;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><strong><span style=\"color: #ffffff;\">SHIPPING DETAILS</span></strong></span></span></span></span></td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{invoice_company_name}</td>\n<td style=\"height: 12px; width: 33.33%;\">Freight Type</td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{companyname}</td>\n<td style=\"height: 12px; width: 33.33%;\">Est Ship Date</td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{invoice_company_address}</td>\n<td style=\"height: 12px; width: 33.33%;\">Est Gross Weight</td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{invoice_company_city}</td>\n<td style=\"height: 12px; width: 33.33%;\">Est Cubic Weight</td>\n</tr>\n<tr style=\"height: 12px;\">\n<td style=\"width: 33.33%; height: 12px;\"></td>\n<td style=\"height: 12px; width: 33.33%;\">{invoice_company_phonenumber}</td>\n<td style=\"height: 12px; width: 33.33%;\">Total Packages</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"><span style=\"font-size: 14pt;\"><strong><br /></strong></span>{contract_item_list}<br /><br /></span></p>\n<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td style=\"width: 100%; background-color: #800000;\"><span style=\"font-size: 12pt;\"><strong><span style=\"font-family: arial, helvetica, sans-serif; color: #ffffff;\"><span style=\"font-family: arial, helvetica, sans-serif;\">TERMS OF SALE AND OTHER COMMENTS</span></span></strong></span></td>\n</tr>\n<tr>\n<td style=\"width: 100%;\">{terms_of_sale}<br /><br />{terms_of_payment}</td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: center;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif;\"></span><br /><br /></p>\n<table style=\"width: 100%;\">\n<tbody>\n<tr>\n<td colspan=\"2\" style=\"background-color: #800000;\"><span style=\"color: #ffffff; font-family: arial, helvetica, sans-serif; font-size: 12pt;\"><strong>ADDITIONAL DETAILS</strong></span></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\"><span style=\"font-family: \'trebuchet ms\', geneva, sans-serif; font-size: 10pt;\">Country of Origin</span></td>\n<td style=\"width: 70%;\"></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\">Port of Embarkation</td>\n<td style=\"width: 70%;\"></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\">Port of Discharge</td>\n<td style=\"width: 70%;\"></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\"></td>\n<td style=\"width: 70%;\"></td>\n</tr>\n<tr>\n<td style=\"width: 30%;\">Reason for Export:</td>\n<td style=\"width: 70%; border: 1px solid #000000; border-color: #000000;\"></td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: left;\"><br />I certify the above to be true and correct to the best of my knowledge.<br /><br /></p>\n<table width=\"100%\">\n<tbody>\n<tr>\n<td width=\"80\">x&nbsp;</td>\n<td width=\"83\"></td>\n<td width=\"89\"></td>\n<td width=\"47\"></td>\n<td width=\"80\"></td>\n<td colspan=\"2\" width=\"169\">&nbsp;</td>\n</tr>\n<tr>\n<td colspan=\"2\">[Typed Name]</td>\n<td>&nbsp;</td>\n<td>&nbsp;</td>\n<td></td>\n<td>Date</td>\n<td>&nbsp;</td>\n</tr>\n<tr>\n<td colspan=\"2\">[Company Name]</td>\n<td></td>\n<td></td>\n<td></td>\n<td></td>\n<td></td>\n</tr>\n</tbody>\n</table>\n<p style=\"text-align: left;\"><br /><span style=\"font-size: 14pt;\"><strong></strong></span></p>');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblpurchase_costs`
--

CREATE TABLE `tblpurchase_costs` (
  `id` int(11) NOT NULL,
  `code` text COLLATE utf8_unicode_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_create` int(11) NOT NULL,
  `unit_shipping_name` text COLLATE utf8_unicode_ci NOT NULL,
  `unit_shipping_address` text COLLATE utf8_unicode_ci NOT NULL,
  `unit_shipping_unit` text COLLATE utf8_unicode_ci NOT NULL,
  `purchase_contract_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tblpurchase_costs_detail`
--

CREATE TABLE `tblpurchase_costs_detail` (
  `id` int(11) NOT NULL,
  `purchase_costs_id` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `tblpurchase_contracts`
--
ALTER TABLE `tblpurchase_contracts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tblpurchase_costs`
--
ALTER TABLE `tblpurchase_costs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tblpurchase_costs_detail`
--
ALTER TABLE `tblpurchase_costs_detail`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `tblpurchase_contracts`
--
ALTER TABLE `tblpurchase_contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT cho bảng `tblpurchase_costs`
--
ALTER TABLE `tblpurchase_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT cho bảng `tblpurchase_costs_detail`
--
ALTER TABLE `tblpurchase_costs_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
