-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2017 at 07:44 AM
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
-- Table structure for table `tblcontracts`
--

CREATE TABLE IF NOT EXISTS `tblcontracts` (
`id` int(11) NOT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `rel_id` tinyint(4) DEFAULT NULL,
  `content` longtext,
  `description` text,
  `subject` varchar(300) DEFAULT NULL,
  `client` int(11) NOT NULL,
  `datestart` date DEFAULT NULL,
  `dateend` date DEFAULT NULL,
  `contract_type` int(11) DEFAULT NULL,
  `addedfrom` int(11) NOT NULL,
  `dateadded` datetime NOT NULL,
  `isexpirynotified` int(11) NOT NULL DEFAULT '0',
  `contract_value` decimal(11,2) DEFAULT NULL,
  `trash` tinyint(1) DEFAULT '0',
  `not_visible_to_client` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tblcontracts`
--

INSERT INTO `tblcontracts` (`id`, `prefix`, `code`, `rel_id`, `content`, `description`, `subject`, `client`, `datestart`, `dateend`, `contract_type`, `addedfrom`, `dateadded`, `isexpirynotified`, `contract_value`, `trash`, `not_visible_to_client`) VALUES
(5, 'HĐ-', '00005', 21, '<p style="text-align: center;"><span style="font-size: 14pt;"><strong>HỢP ĐỒNG MUA B&Aacute;N THIẾT BỊ NH&Agrave; BẾP</strong></span></p><p style="text-align: center;">Số : {contract_code}</p><ul><li><em>Căn cứ Bộ luật d&acirc;n sự số 33/2005/QH11 ng&agrave;y 14/06/2005 của Quốc hội nước Cộng h&ograve;a x&atilde; hội chủ nghĩa Việt Nam.</em></li><li><em>Căn cứ Nghị định số 163/2006/NĐ-CP ng&agrave;y 29/12/2006 của Ch&iacute;nh phủ về giao dịch bảo đảm.</em></li></ul><p><strong><em>&nbsp;</em></strong></p><p style="text-align: right;"><strong><em>{invoice_company_city} ,{contract_date}</em></strong></p><p>&nbsp;</p><p><strong><u>B&Ecirc;N B&Aacute;N:</u></strong><strong> &nbsp;&nbsp;&nbsp; (B&Ecirc;N A)</strong></p><p>Đơn vị &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: {invoice_company_name}</p><p>M&atilde; số thuế &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : {company_vat}</p><p>Người đại diện &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : {company_deputation}</p><p>Chức vụ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: {company_contract_role}</p><p>Địa chỉ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : {invoice_company_address}</p><p>Điện thoại &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : {invoice_company_phonenumber}</p><p>T&agrave;i khoản&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : {company_contract_bank_account}</p><p>&nbsp;</p><p><strong><u>B&Ecirc;N MUA:</u></strong> &nbsp;&nbsp; <strong>(B&Ecirc;N B)</strong></p><p>Đơn vị&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;{client_company}</p><p>M&atilde; số thuế &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; :&nbsp;{client_vat_number}</p><p>Người đại diện &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; :&nbsp;{contact_firstname} {contact_lastname}</p><p>Chức vụ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;<span>{contact_position}</span></p><p>Địa chỉ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;{client_address}</p><p>Điện thoại&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;{client_phonenumber}</p><p>&nbsp;</p><p><em><strong>Hai b&ecirc;n thống nhất k&yacute; kết Hợp đồng với c&aacute;c điều khoản sau:</strong></em></p><ol style="list-style-type: upper-roman;"><li><strong>Nội dung giao dịch, mua b&aacute;n:</strong><p style="text-align: center;">{contract_item_list}</p><p><strong>Tổng gi&aacute; trị h&agrave;ng h&oacute;a: {contract_contract_value}&nbsp;đồng</strong></p><p>Số tiền thanh to&aacute;n: {contract_value_vat} ( đ&atilde; bao gồm VAT 10%). ( {contract_value_words}<em>&nbsp;đồng). </em></p></li><li><strong> </strong><strong>H&igrave;nh thức, thời hạn thanh to&aacute;n:</strong><ul style="list-style-type: disc;"><li><strong> </strong><strong>H&igrave;nh thức thanh to&aacute;n: Mọi khoản thanh to&aacute;n giữa B&ecirc;n A v&agrave; B&ecirc;n B đều được thực hiện bằng h&igrave;nh thức chuyển khoản qua t&agrave;i khoản ng&acirc;n h&agrave;ng của B&ecirc;n A.</strong></li><li><strong> </strong><strong>Thời hạn thanh to&aacute;n: </strong></li><li><strong> </strong><strong>Lần 1: B&ecirc;n B thanh to&aacute;n số tiền &hellip;&hellip;&hellip;&hellip;&hellip; đồng (<em>&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;. đồng</em>) v&agrave;o t&agrave;i khoản ng&acirc;n h&agrave;ng của B&ecirc;n A trong v&ograve;ng 24 giờ sau khi hợp đồng được k&yacute; kết.</strong></li><li><strong> </strong><strong>Lần 2: B&ecirc;n B thanh to&aacute;n số tiền &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.. đồng <em>(&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.. đồng)</em> v&agrave;o t&agrave;i khoản ng&acirc;n h&agrave;ng của B&ecirc;n A trước khi giao h&agrave;ng.</strong></li><li><strong> </strong><strong>Chất lượng v&agrave; suất xứ h&agrave;ng h&oacute;a:</strong></li><li><strong> </strong><strong>Chất lượng h&agrave;ng h&oacute;a B&ecirc;n A cung cấp l&agrave; h&agrave;ng mới 100%, đ&uacute;ng chủng loại, chất lượng ti&ecirc;u chuẩn của nh&agrave; sản xuất.</strong></li><li><strong> </strong><strong>Nguồn gốc xuất xứ h&agrave;ng h&oacute;a: Sản xuất v&agrave; lắp r&aacute;p tại &Yacute; ( Italy). </strong></li></ul></li><li><strong> </strong><strong>Phương thức giao nhận v&agrave; lắp đặt:</strong><ul style="list-style-type: disc;"><li>Địa chỉ giao h&agrave;ng: {client_address} Hồ Ch&iacute; Minh</li><li>B&ecirc;n A thực hiện thi c&ocirc;ng lắp đặt tất cả h&agrave;ng h&oacute;a cho b&ecirc;n B tại vị tr&iacute; sử dụng theo chỉ định của b&ecirc;n B.</li><li>Cung cấp miễn ph&iacute; tất cả vật tư phụ cần thiết cho việc lắp đặt. Thời gian giao h&agrave;ng v&agrave; lắp đặt theo y&ecirc;u cầu của b&ecirc;n B.</li></ul></li><li><strong> </strong><strong>Bảo h&agrave;nh v&agrave; dịch vụ:</strong><ul style="list-style-type: disc;"><li>B&ecirc;n A c&oacute; tr&aacute;ch nhiệm bảo h&agrave;nh to&agrave;n bộ h&agrave;ng h&oacute;a v&agrave; c&aacute;c c&ocirc;ng việc thực hiện trong thời gian 05 năm kể từ ng&agrave;y nghiệm thu b&agrave;n giao đưa v&agrave;o sử dụng.</li><li>B&ecirc;n A sẽ thực hiện việc chăm s&oacute;c sản phẩm định kỳ miễn ph&iacute; h&agrave;ng năm.</li><li>Kh&ocirc;ng bảo h&agrave;nh khi cố t&igrave;nh l&agrave;m hư hỏng v&agrave; c&aacute;c yếu tố kh&aacute;ch quan như b&atilde;o lũ, thi&ecirc;n tai&hellip;</li></ul></li><li><strong> </strong><strong>Điều khoản chung:</strong><ul style="list-style-type: disc;"><li>Hai b&ecirc;n cam kết thực hiện đ&uacute;ng c&aacute;c điều khoản đ&atilde; ghi trong hợp đồng.</li><li>Hợp đồng n&agrave;y được x&aacute;c lập bằng sự thỏa thuận ho&agrave;n to&agrave;n về c&aacute;c điều khoản tr&ecirc;n giữa hai b&ecirc;n. Tất cả mọi sự thay đổi, điều chỉnh hợp đồng phải được thống nhất bằng văn bản v&agrave; c&oacute; chữ k&yacute; x&aacute;c nhận của hai b&ecirc;n.</li><li>Hợp đồng c&oacute; hiệu lực kể từ ng&agrave;y k&yacute; v&agrave; được lập th&agrave;nh 02 bản, mỗi b&ecirc;n giữ 01 bản c&oacute; gi&aacute; trị ph&aacute;p l&yacute; như nhau.</li></ul></li></ol><table width="100%" class="table" height="80" style="height: 80px; margin-left: auto; margin-right: auto;"><tbody><tr><td><p style="text-align: center;"><strong>ĐẠI DIỆN B&Ecirc;N A</strong></p></td><td><p style="text-align: center;"><strong>ĐẠI DIỆN B&Ecirc;N B</strong></p></td></tr><tr><td><p style="text-align: center;"><strong>&nbsp;</strong></p></td><td><p style="text-align: center;"><strong>&nbsp;</strong></p></td></tr><tr><td><p style="text-align: center;"></p></td><td><p style="text-align: center;"><strong></strong></p></td></tr><tr><td><p style="text-align: center;"></p></td><td></td></tr></tbody></table><p>&nbsp;</p><p>&nbsp;</p>', 'Mua hang', 'HĐ Mua', 4, '2017-08-14', '2017-08-31', 1, 1, '2017-08-14 11:25:49', 0, '1000000.00', 0, 0),
(7, NULL, NULL, NULL, NULL, '', 'Hợp đồng bán hàng - Cty TNHH MTV A', 4, '2017-08-17', NULL, 2, 1, '2017-08-17 11:35:57', 0, '14631200.00', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblcontracts`
--
ALTER TABLE `tblcontracts`
 ADD PRIMARY KEY (`id`), ADD KEY `client` (`client`), ADD KEY `contract_type` (`contract_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tblcontracts`
--
ALTER TABLE `tblcontracts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
