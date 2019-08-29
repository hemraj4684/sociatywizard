-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3786
-- Generation Time: May 25, 2019 at 01:34 PM
-- Server version: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `society_wizard`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `bill_late_interest_calculations`$$
CREATE DEFINER=`myself`@`localhost` PROCEDURE `bill_late_interest_calculations` ()  MODIFIES SQL DATA
BEGIN
DECLARE f_date DATE;
DECLARE max_date DATE;
DECLARE done TINYINT DEFAULT 0;
DECLARE rate TINYINT;
DECLARE sid INT(11) UNSIGNED;
DECLARE interest_rates CURSOR FOR SELECT id,late_payment_interest FROM society_main;
DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done=1;
SET f_date = DATE_FORMAT(NOW() ,'%Y-%m-01');
SET max_date = DATE_FORMAT(DATE_SUB(curdate(),INTERVAL 1 YEAR),'%Y-%m-01');
OPEN interest_rates;
read_loop: LOOP
FETCH interest_rates INTO sid,rate;
IF done=1 THEN
LEAVE read_loop;
END IF;
UPDATE flat_invoice SET total_amount = total_amount+(principal_amount*rate/100), interest_applied_times = 1, late_fee_amonut = (principal_amount*rate/100) WHERE is_paid = 2 AND society_id = sid AND due_date < CURDATE() AND invoice_month > max_date AND interest_applied_times = 0;
END LOOP;
CLOSE interest_rates;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
CREATE TABLE IF NOT EXISTS `admin_notifications` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `notification_type` tinyint(3) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `user_refer` int(10) UNSIGNED NOT NULL,
  `society_id` int(10) UNSIGNED NOT NULL,
  `is_active` enum('1','2') NOT NULL DEFAULT '1',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_notif_user` (`user_id`),
  KEY `fk_notif_user_refer` (`user_refer`),
  KEY `fk_notif_society` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `notification_type`, `user_id`, `user_refer`, `society_id`, `is_active`, `created_date`) VALUES
(14, 1, 1, 307, 1, '1', '2016-10-09 09:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `bill_default_particulars`
--

DROP TABLE IF EXISTS `bill_default_particulars`;
CREATE TABLE IF NOT EXISTS `bill_default_particulars` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `bill_group_id` int(10) UNSIGNED NOT NULL,
  `two_wheeler` decimal(10,2) UNSIGNED NOT NULL,
  `four_wheeler` decimal(10,2) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bill_group_id` (`bill_group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_group`
--

DROP TABLE IF EXISTS `bill_group`;
CREATE TABLE IF NOT EXISTS `bill_group` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  `society_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_society_bgroup` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bill_group`
--

INSERT INTO `bill_group` (`id`, `name`, `society_id`) VALUES
(1, 'Group 1', 1),
(2, 'Main', 3);

-- --------------------------------------------------------

--
-- Table structure for table `default_particulars`
--

DROP TABLE IF EXISTS `default_particulars`;
CREATE TABLE IF NOT EXISTS `default_particulars` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(100) NOT NULL,
  `amount` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `group_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_parti_group` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `default_particulars`
--

INSERT INTO `default_particulars` (`id`, `name`, `amount`, `group_id`) VALUES
(1, 'Municipal Tax', '250.00', 1),
(2, 'Water Charges', '250.00', 1),
(3, 'Non Agricultural Assessment', '125.00', 1),
(4, 'Sinking Fund', '75.00', 1),
(5, 'Service Charges', '130.00', 1),
(6, 'Insurance Premium', '120.00', 1),
(7, 'Additional Charges', '50.00', 1),
(8, 'Property Tax', '335.00', 2),
(9, 'Water Charges', '90.00', 2),
(10, 'Repairing Fund', '42.00', 2),
(11, 'Sinking Fund', '16.00', 2),
(12, 'Insurance Charge', '16.00', 2),
(13, 'Cable TV Charge', '330.00', 2),
(14, 'Intercom Maintenance', '12.00', 2),
(15, 'Common Service Charge', '755.00', 2),
(16, 'Sub Letting Charge', '76.00', 2),
(17, 'Education Fund Etc', '10.00', 2),
(18, 'Parking Charge', '300.00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `document_folder`
--

DROP TABLE IF EXISTS `document_folder`;
CREATE TABLE IF NOT EXISTS `document_folder` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `folder_name` char(50) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '2',
  `society_id` int(11) UNSIGNED NOT NULL,
  `date_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_doc_admin` (`created_by`),
  KEY `fk_doc_soc` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `document_folder`
--

INSERT INTO `document_folder` (`id`, `folder_name`, `description`, `created_by`, `status`, `society_id`, `date_created`) VALUES
(1, 'Important', '', 1, '2', 1, '2016-04-19 12:08:38'),
(3, 'Society Bye Laws', 'Society Bye Laws', 85, '2', 3, '2016-08-01 13:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `flats`
--

DROP TABLE IF EXISTS `flats`;
CREATE TABLE IF NOT EXISTS `flats` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `flat_no` char(20) NOT NULL,
  `flat_wing` int(11) UNSIGNED DEFAULT NULL,
  `sq_foot` char(20) NOT NULL DEFAULT '0',
  `owner_name` char(50) NOT NULL,
  `owner_number` bigint(20) UNSIGNED DEFAULT NULL,
  `intercom` char(20) NOT NULL,
  `status` smallint(5) UNSIGNED DEFAULT NULL,
  `total_parking` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
  `society_id` int(11) UNSIGNED NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_flat_status` (`status`),
  KEY `fk_flat_wing` (`flat_wing`),
  KEY `fk_society_flat` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=267 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `flats`
--

INSERT INTO `flats` (`id`, `flat_no`, `flat_wing`, `sq_foot`, `owner_name`, `owner_number`, `intercom`, `status`, `total_parking`, `society_id`, `date_added`) VALUES
(1, '1', 1, '765', 'Savio Dsouza', 9833215446, '101', 1, 2, 1, '2016-04-12 13:47:34'),
(2, '2', 1, '765', 'Gulzar Shah', 8879036118, '102', 1, 1, 1, '2016-04-12 13:50:30'),
(3, '3', 1, '765', 'Subodh Nadkarni', 5959595959, '103', 1, 1, 1, '2016-04-12 13:52:43'),
(4, '4', 1, '765', 'Shashank Patel', 3030303030, '104', 2, 0, 1, '2016-04-12 13:53:41'),
(5, '5', 1, '765', 'Venkatesh Raghavan', 6060606060, '106', 1, 1, 1, '2016-04-12 13:54:02'),
(6, '6', 1, '765', 'Anthony Fernandes', 5050505050, '106', 1, 1, 1, '2016-04-12 13:54:34'),
(7, '7', 1, '765', 'Akhtar Mirza', 2020202020, '107', 2, 0, 1, '2016-04-12 13:54:57'),
(8, '8', 1, '765', 'Vijay Kalsekar', 9090909090, '108', 1, 0, 1, '2016-04-12 13:55:16'),
(9, '9', 1, '765', 'Anand Patwardhan', 1091091091, '109', 2, 0, 1, '2016-04-12 13:55:39'),
(10, '10', 1, '765', 'Murtuza Nagaria', 9821005506, '1010', 1, 0, 1, '2016-04-12 13:57:21'),
(11, '1', 2, '765', 'Vineet Dua', 8787878787, '201', 2, 0, 1, '2016-04-12 13:58:10'),
(12, '2', 2, '765', 'Narendra Tiwari', 5252525252, '202', 1, 1, 1, '2016-04-12 13:58:27'),
(13, '3', 2, '765', 'Rajiv Dixit', 3030303010, '203', 1, 0, 1, '2016-04-12 13:58:51'),
(14, '4', 2, '765', 'Sanjeevan DSouza', 5555555555, '204', 1, 0, 1, '2016-04-12 14:00:13'),
(15, '5', 2, '765', 'Thomas D Costa', 1010101010, '205', 1, 0, 1, '2016-04-12 14:00:52'),
(16, '6', 2, '765', 'Ranjeet Verma', 2020202020, '206', 1, 0, 1, '2016-04-12 14:01:16'),
(17, '7', 2, '765', 'Siddarth Dutt', 9930815474, '207', 1, 0, 1, '2016-04-12 14:01:39'),
(18, '8', 2, '765', 'Dilip Chouhan', 4455445545, '208', 1, 0, 1, '2016-04-12 14:01:58'),
(19, '9', 2, '765', 'Vijay Gorde', 6666666666, '209', 1, 0, 1, '2016-04-12 14:02:24'),
(20, '10', 2, '765', 'Atul Godambe', 4444444444, '210', 2, 0, 1, '2016-04-12 14:03:04'),
(21, '1', 3, '765', 'Shyam Nadkarni', 5555555555, '301', 1, 0, 1, '2016-04-12 14:03:28'),
(22, '2', 3, '765', 'Priya Sharma', 4444444444, '302', 1, 0, 1, '2016-04-12 14:03:50'),
(23, '3', 3, '765', 'Gurmeet Singh Lamba', 1111111111, '303', 1, 0, 1, '2016-04-12 14:04:17'),
(24, '4', 3, '765', 'Rohit Bundela', 7878787878, '304', 1, 0, 1, '2016-04-12 14:04:34'),
(25, '5', 3, '765', 'Rahul Pandey', 7417417417, '305', 1, 0, 1, '2016-04-12 14:04:58'),
(26, '6', 3, '765', 'Sandip Roy', 2105478456, '306', 1, 0, 1, '2016-04-12 14:05:24'),
(27, '7', 3, '765', 'Tarak Mehta', 2587410365, '307', 2, 0, 1, '2016-04-12 14:05:48'),
(28, '8', 3, '765', 'Urusa Lakdawala', 8585858585, '308', 2, 0, 1, '2016-04-12 14:06:09'),
(29, '9', 3, '765', 'Rohit Saxena', 9892222222, '309', 2, 0, 1, '2016-04-12 14:06:52'),
(30, '10', 3, '765', 'Mitesh Mistry', 9833210927, '310', 2, 0, 1, '2016-04-12 14:07:17'),
(31, '11', 1, '765', 'Rustom Banaji', 5421542154, '111', 1, 0, 1, '2016-04-12 14:07:50'),
(32, '11', 2, '765', 'Tarvinder Singh Sobti', 7897897897, '211', 1, 0, 1, '2016-04-12 14:08:11'),
(33, '11', 3, '765', 'Zaid Shaikh', 8879259694, '311', 1, 0, 1, '2016-04-12 14:08:45'),
(34, 'A1', 5, '500', 'Amol Chorghe', 9619664810, '', 1, 0, 2, '2016-05-02 11:33:26'),
(35, 'A5', 5, '590', 'Mahendra Patel', 8879050810, '', 1, 0, 2, '2016-05-02 11:36:42'),
(36, 'A3', 5, '590', 'Patel Jivanji', 8879036120, '', 1, 0, 2, '2016-05-02 23:47:27'),
(38, 'E, G-1', NULL, '82', 'Geeta K', 9898912345, '', 1, 0, 4, '2016-05-16 01:01:56'),
(39, 'E, G-2', NULL, '82', 'Zamir Khan', 8888830515, '', 1, 0, 4, '2016-05-16 01:03:18'),
(40, 'E, F-1', NULL, '82', 'Rauf A.', 9822123456, '', 1, 0, 4, '2016-05-16 01:04:02'),
(41, 'E, F-2', NULL, '82', 'Hema Sardessai', 9822123457, '', 1, 0, 4, '2016-05-16 01:04:31'),
(42, 'E, S-1', NULL, '82', 'A. D\'Souza', 8888812345, '', 1, 0, 4, '2016-05-16 01:05:43'),
(43, 'E, S-2', NULL, '82', 'G. Fernandes', 8888801234, '', 1, 0, 4, '2016-05-16 01:06:41'),
(109, '101', 15, '', 'MR. ANISH BASU', 9820184527, '', 1, 0, 6, '2016-09-09 05:02:16'),
(110, '101', 16, '', 'MR. ALTAF DADANI & MRS. FARIDA A. DADANI', 8097266842, '', 1, 0, 6, '2016-09-09 05:02:58'),
(111, '102', 15, '', 'MR. PARVEZ V. MUKADAM', 9323278118, '', 1, 0, 6, '2016-09-09 05:04:38'),
(112, '103', 15, '', 'MR. PRASHANT A. MITTER', 9821012522, '', 1, 0, 6, '2016-09-09 05:05:55'),
(113, '104', 15, '', 'MR. AFZAL ALTAF CONTRACTOR & MR. ALTAF R. CONTRACT', 9821786216, '', 1, 0, 6, '2016-09-09 05:06:34'),
(114, '201', 15, '', 'MR. JAVED U. MERCHANT', 9820065948, '', 1, 0, 6, '2016-09-09 05:07:21'),
(115, '202', 15, '', 'MR. DEEPAK SHIVATHYA', 9920367281, '', 1, 0, 6, '2016-09-09 05:08:10'),
(116, '203', 15, '', 'MR. BOLAR ABDUL GHANI & BADRUNNISA B. GHANI', 9665404150, '', 1, 0, 6, '2016-09-09 05:11:27'),
(117, '204', 15, '', 'MRS. ANITHA S. KAIMAL/MR. SURESH U. KAIMAL', 9768073773, '', 1, 0, 6, '2016-09-09 05:12:23'),
(118, '301', 15, '', 'MR. ZAMIRABBAS M. MISTRY & MRS. FARNAZ Z. MISTRY', 9820930912, '', 1, 0, 6, '2016-09-09 05:13:04'),
(119, '302', 15, '', 'MR. ABBASALI VIRANI & OTHERS', 9702567786, '', 1, 0, 6, '2016-09-09 05:15:47'),
(120, '303', 15, '', 'MR. & MRS. ARSHI SHAIKH', 9833833014, '', 1, 0, 6, '2016-09-09 05:16:23'),
(121, '304', 15, '', 'MR. ANURAG BHATNAGAR', 9867504957, '', 1, 0, 6, '2016-09-09 05:16:56'),
(122, '401', 15, '', 'MRS. GHAZALA KHTUN SYED FAZAL IMAM', 9920691922, '', 1, 0, 6, '2016-09-09 05:17:31'),
(123, '403', 15, '', 'MR. AMAN ASHOK KHUPASARE', 9819819381, '', 1, 0, 6, '2016-09-09 05:18:32'),
(124, '404', 15, '', 'MR. AMITKUMAR SHAW', 9831094195, '', 1, 0, 6, '2016-09-09 05:19:06'),
(125, '501', 15, '', 'MR. MOHD. FARAZ A. LATIF KHAN & MRS. DINAZ MOHA. F', 9820625110, '', 1, 0, 6, '2016-09-09 05:21:28'),
(126, '502', 15, '', 'MRS. RAFIA S.H.ABEDI', 9930248255, '', 1, 0, 6, '2016-09-09 05:22:08'),
(127, '503', 15, '', 'MR. SABIR AHMED BABU SYED', 9702057977, '', 1, 0, 6, '2016-09-09 05:22:43'),
(128, '601', 15, '', 'MR. SYED IMAM FAZAL', 9920691922, '', 1, 0, 6, '2016-09-09 05:25:00'),
(129, '602', 15, '', 'MR. RAFIQ HOODA', 9323996799, '', 1, 0, 6, '2016-09-09 05:25:27'),
(130, '603', 15, '', 'MR. ANEES S.KAZI', 9820296127, '', 1, 0, 6, '2016-09-09 05:25:53'),
(131, '604', 15, '', 'MRS. SHALINI N. HARJANI', 9820544177, '', 1, 0, 6, '2016-09-09 05:28:25'),
(132, '701', 15, '', 'MR. MEHBOOB S. GILANI', 9324661663, '', 1, 0, 6, '2016-09-09 05:28:49'),
(133, '702', 15, '', 'MS. APARNA PUROHIT & MR. VISHAMBHAR D. PUROHIT', 9819669907, '', 1, 0, 6, '2016-09-09 05:29:10'),
(134, '703', 15, '', 'MR. ABRAHAM KURUVILLA', 9820291750, '', 1, 0, 6, '2016-09-09 05:29:34'),
(135, '704', 15, '', 'MS. P.C. FERNANDES', 9820291750, '', 1, 0, 6, '2016-09-09 05:29:59'),
(136, '102', 16, '', 'MR. VIJAYKUMAR B. NAIR & MRS. SMITA V. NAIR', 9004212229, '', 1, 0, 6, '2016-09-09 05:30:53'),
(137, '103', 16, '', 'MR. BHAVESH J. SONI', 9821349716, '', 1, 0, 6, '2016-09-09 05:31:42'),
(139, '101', 6, '', 'Sagheer Hussain', 9819848315, '', 1, 0, 3, '2016-09-18 09:05:20'),
(140, '102', 6, '', 'Usha P. Thakur', 9769313225, '', 1, 0, 3, '2016-09-18 09:15:15'),
(141, '202', 6, '', 'M. H. Bardai', 9833760022, '', 1, 0, 3, '2016-09-18 09:17:47'),
(142, '303', 6, '', 'Kumar Panjwani', 9819848276, '', 1, 0, 3, '2016-09-18 09:20:18'),
(143, '401', 6, '', 'Manorama P. Gupta', 9324565434, '', 1, 0, 3, '2016-09-18 09:22:11'),
(144, '501', 6, '', 'Sameera M. Mirajkar', 9221102411, '', 1, 0, 3, '2016-09-18 09:24:20'),
(145, '502', 6, '', 'Kumar Shambhu Kunder', 9892000395, '', 1, 0, 3, '2016-09-18 09:26:12'),
(146, '603', 6, '', 'Shabnam N. Pirani & Shabbir N. Pirani', 9769879135, '', 1, 0, 3, '2016-09-18 09:46:42'),
(147, '701', 6, '', 'Sohail B. Wazifdar', 9821985343, '', 1, 0, 3, '2016-09-18 09:48:06'),
(148, '702', 6, '', 'Nazleen D Lakhani & Feroz Lakhani', 9833763950, '', 1, 0, 3, '2016-09-18 09:50:06'),
(149, '104', 7, '', 'Salim G. Parpia', 9820271545, '', 1, 0, 3, '2016-09-18 09:52:24'),
(150, '105', 7, '', 'N. D Mirchandani', 9664907330, '', 1, 0, 3, '2016-09-18 09:53:49'),
(151, '205', 7, '', 'Munira Shahbuddin Poonawala', 9867068414, '', 1, 0, 3, '2016-09-18 09:55:53'),
(152, '304', 7, '', 'Zehra Shoeb G Dawoodji', 9665018992, '', 1, 0, 3, '2016-09-18 09:57:56'),
(153, '305', 7, '', 'Nadir Noorallah Bhalwani', 9769572272, '', 1, 0, 3, '2016-09-18 10:02:16'),
(154, '404', 7, '', 'Q.M Abusufiyan', 9867906215, '', 1, 0, 3, '2016-09-18 10:03:52'),
(155, '504', 7, '', 'Shamima N Chapra', 8879259691, '', 1, 0, 3, '2016-09-18 10:09:39'),
(156, '505', 7, '', 'Rafiq R Gajiyani', 9920270444, '', 1, 1, 3, '2016-09-18 10:11:12'),
(157, '604', 7, '', 'Nilofer Jivan', 9222440786, '', 1, 0, 3, '2016-09-18 10:13:17'),
(158, '605', 7, '', 'Sohail A Ainapore', 8108880511, '', 1, 0, 3, '2016-09-18 10:15:03'),
(159, '704', 7, '', 'Hajira Ahmed Shaikh', 9867419247, '', 1, 0, 3, '2016-09-18 10:16:30'),
(160, '705', 7, '', 'Farida S Khoja & Sameer S Khoja', 9920198168, '', 1, 0, 3, '2016-09-18 10:18:19'),
(161, '106', 8, '', 'Naushad T & Mumtaz N Doctor', 9833687110, '', 1, 0, 3, '2016-09-18 10:20:28'),
(162, '207', 8, '', 'Abdul H M Chamdawala', 9819942240, '', 1, 0, 3, '2016-09-18 10:22:06'),
(163, '306', 8, '', 'Adam U Bandukiya', 9619552408, '', 1, 0, 3, '2016-09-18 10:24:10'),
(164, '307', 8, '', 'Jyoti R Bachwani', 9769724566, '', 1, 0, 3, '2016-09-18 10:25:45'),
(165, '408', 8, '', 'Dilip B Pradhan', 9819089197, '', 1, 0, 3, '2016-09-18 10:27:28'),
(166, '506', 8, '', 'Bhagwandas Bhudrani', 9987818405, '', 1, 0, 3, '2016-09-18 10:29:40'),
(167, '507', 8, '', 'Savitri B Budhrani', 8898338696, '', 1, 0, 3, '2016-09-18 10:31:38'),
(168, '508', 8, '', 'Karim H Kalyani', 9967004544, '', 1, 0, 3, '2016-09-18 10:33:29'),
(169, '606', 8, '', 'Feroz Haji Bardai', 9820781116, '', 1, 0, 3, '2016-09-18 10:34:55'),
(170, '607', 8, '', 'Narendranath Mukherjee', 7095557779, '', 1, 0, 3, '2016-09-18 10:36:27'),
(171, '706', 8, '', 'Ashraf R Virani', 9892822844, '', 1, 0, 3, '2016-09-18 10:39:34'),
(172, '708', 8, '', 'Riyaz Vasani', 9820544555, '', 1, 1, 3, '2016-09-18 10:41:04'),
(173, '101', 9, '', 'S A Valiyani', 9819854018, '', 1, 0, 3, '2016-09-18 10:42:41'),
(174, '102', 9, '', 'Nizamullah S Khan', 9833916126, '', 1, 0, 3, '2016-09-18 10:44:08'),
(175, '201', 9, '', 'Shenaz Mohd Aslam Contractor', 9820039591, '', 1, 0, 3, '2016-09-18 10:46:12'),
(176, '202', 9, '', 'Anwarali Karachiwala', 9819854018, '', 1, 1, 3, '2016-09-18 10:48:09'),
(177, '203', 9, '', 'Esperanca D\'Souza', 9819636929, '', 1, 0, 3, '2016-09-18 10:54:31'),
(178, '301', 9, '', 'Daulatbanoo Bootwala', 9820342147, '', 1, 0, 3, '2016-09-18 10:56:05'),
(179, '302', 9, '', 'Shakil Shafi Ahmed Khan', 9930667724, '', 1, 0, 3, '2016-09-18 10:57:37'),
(180, '303', 9, '', 'Simran Deep', 9920824463, '', 1, 0, 3, '2016-09-18 10:59:38'),
(181, '401', 9, '', 'Zarina A M Shaikh', 9167600263, '', 1, 0, 3, '2016-09-18 11:01:00'),
(182, '402', 9, '', 'Mohsinali Munji', 9867976409, '', 1, 0, 3, '2016-09-18 11:02:59'),
(183, '403', 9, '', 'Rafique Makker', 9833555144, '', 1, 0, 3, '2016-09-18 11:04:47'),
(184, '503', 9, '', 'Azhar A Ginwala', 9819344218, '', 1, 0, 3, '2016-09-18 11:06:07'),
(185, '601', 9, '', 'Rameshkumar G Panicker', 9820024817, '', 1, 0, 3, '2016-09-18 11:07:54'),
(186, '602', 9, '', 'Prem Kumar C Chopra', 9323201549, '', 1, 0, 3, '2016-09-18 11:09:36'),
(187, '702', 9, '', 'Masuma & Riyaz Kerawala', 9930816241, '', 1, 0, 3, '2016-09-18 11:30:12'),
(188, '703', 9, '', 'Riyaz K & Masuma R Kerawala', 9892716492, '', 1, 0, 3, '2016-09-18 11:32:05'),
(189, '105', 10, '', ' S S A Coudhary', 9820088558, '', 1, 0, 3, '2016-09-18 11:34:03'),
(190, '304', 10, '', 'Isidore H D\'Souza', 9617053642, '', 1, 0, 3, '2016-09-18 11:35:39'),
(191, '305', 10, '', 'Khairunisa M H Devji & others', 9223466512, '', 1, 0, 3, '2016-09-18 11:37:47'),
(192, '404', 10, '', 'E D Moorthy & Laxmidevi', 9820285645, '', 1, 0, 3, '2016-09-18 11:39:39'),
(193, '504', 10, '', 'Izzat H H Asgar', 9619472766, '', 1, 0, 3, '2016-09-18 11:41:36'),
(194, '505', 10, '', 'Aziz A Y Chatriwala', 9819949352, '', 1, 0, 3, '2016-09-18 11:43:10'),
(195, '605', 10, '', 'T Selva Kumar Mudaliar', 9619635956, '', 1, 1, 3, '2016-09-18 11:45:46'),
(196, '704', 10, '', 'Bakhtawar S Dharani', 9833832450, '', 1, 0, 3, '2016-09-18 11:48:06'),
(197, '705', 10, '', 'Jagdish Singh Raikar', 9820007765, '', 1, 0, 3, '2016-09-18 11:49:51'),
(198, '108', 11, '', 'Riyaz Dandawala', 9820453521, '', 1, 2, 3, '2016-09-18 11:56:57'),
(199, '206', 11, '', 'Bilquis A Kapadia', 9820846122, '', 1, 0, 3, '2016-09-18 11:58:42'),
(200, '207', 11, '', 'Gayatri Arun Walanj', 9320479559, '', 1, 0, 3, '2016-09-19 04:50:11'),
(201, '208', 11, '', 'Sunil Mayekar & Mita Mayekar', 9920401326, '', 1, 0, 3, '2016-09-19 04:52:25'),
(202, '306', 11, '', 'Khairunnissa A R Belim', 9930824269, '', 1, 1, 3, '2016-09-19 04:54:36'),
(203, '307', 11, '', 'S K Kerosenewala', 9870816186, '', 1, 0, 3, '2016-09-19 04:56:20'),
(204, '308', 11, '', 'Arfina Khan', 9821320093, '', 1, 0, 3, '2016-09-19 04:57:38'),
(205, '407', 11, '', 'Dilip Kumar M Pradhan', 9967394604, '', 1, 1, 3, '2016-09-19 04:59:47'),
(206, '507', 11, '', 'Noorjehan J Vasani', 9619472768, '', 1, 0, 3, '2016-09-19 05:01:44'),
(207, '606', 11, '', 'Riyaz Merchant', 9869673942, '', 1, 0, 3, '2016-09-19 05:03:14'),
(208, '607', 11, '', 'Mahmood Khan & Munna Khan', 9320780625, '', 1, 0, 3, '2016-09-19 05:05:02'),
(209, '706', 11, '', 'Feroz H & Amina Fidai', 9819173268, '', 1, 0, 3, '2016-09-19 05:07:59'),
(210, '101', 12, '', 'Shahin Arif Gazali', 9819089210, '', 1, 0, 3, '2016-09-19 05:11:26'),
(211, '103', 12, '', 'Mohammad Khalid Anjum', 9920012294, '', 1, 0, 3, '2016-09-19 05:13:07'),
(212, '201', 12, '', 'Pradeep M Bidri', 9821215154, '', 1, 0, 3, '2016-09-19 05:14:46'),
(213, '202', 12, '', 'Fatema Shaukat Dodhiya', 9167933559, '', 1, 0, 3, '2016-09-19 05:16:09'),
(214, '203', 12, '', 'K K Khakoo & Karimah Khakoo', 9819379898, '', 1, 0, 3, '2016-09-19 05:18:11'),
(215, '301', 12, '', 'Zubeda R Lalani', 9819598538, '', 1, 0, 3, '2016-09-19 05:20:35'),
(216, '302', 12, '', 'Gulam Hussain K Dinani', 9967704800, '', 1, 0, 3, '2016-09-19 05:22:10'),
(217, '303', 12, '', 'Ruksana N A Khan', 9821858322, '', 1, 0, 3, '2016-09-19 05:23:54'),
(218, '402', 12, '', 'Abdul R H Malim', 9769385531, '', 1, 0, 3, '2016-09-19 05:26:03'),
(219, '403', 12, '', 'B S Dharania', 9819100274, '', 1, 0, 3, '2016-09-19 05:27:31'),
(220, '601', 12, '', 'Nazim N Furniturewala & Nizar', 9819182946, '', 1, 0, 3, '2016-09-19 05:29:27'),
(221, '603', 12, '', 'S H Musanna', 9870417195, '', 1, 0, 3, '2016-09-19 07:25:00'),
(222, '701', 12, '', 'Sulema S Samani', 7303377777, '', 1, 0, 3, '2016-09-19 07:29:01'),
(223, '104', 13, '', 'Zulfikar C Tejani', 9892927775, '', 1, 0, 3, '2016-09-19 07:30:46'),
(224, '204', 13, '', 'Payarali Pirbhai Niyani', 9833214266, '', 1, 0, 3, '2016-09-19 07:32:36'),
(225, '404', 13, '', 'Shahzana & Shahid P Khan', 9869352493, '', 1, 0, 3, '2016-09-19 07:37:49'),
(226, '405', 13, '', 'Santosh Motilal Valecha', 9810246329, '', 1, 0, 3, '2016-09-19 07:39:15'),
(227, '504', 13, '', 'Shirin J Sutarwala', 9004843095, '', 1, 0, 3, '2016-09-19 07:41:02'),
(228, '505', 13, '', 'Cynthia Dar', 9867281252, '', 1, 0, 3, '2016-09-19 07:42:18'),
(229, '604', 13, '', 'A G Roy', 9967556045, '', 1, 0, 3, '2016-09-19 07:43:35'),
(230, '704', 13, '', 'Lachman K Bhatia', 9987202009, '', 1, 0, 3, '2016-09-19 07:46:18'),
(231, '705', 13, '', 'Afzal Sajan & Sajid Sajan', 9323232287, '', 1, 0, 3, '2016-09-19 07:53:25'),
(232, '106', 14, '', 'Parvez U Mhaldar', 9819529528, '', 1, 0, 3, '2016-09-19 07:54:55'),
(233, '108', 14, '', 'Raess J A Khan', 9820129835, '', 1, 1, 3, '2016-09-19 08:03:08'),
(234, '206', 14, '', 'Dilip & Kalyani Patra', 9820103003, '', 1, 0, 3, '2016-09-19 08:10:06'),
(235, '406', 14, '', 'Nizarali H Bhanvadia', 9004344300, '', 1, 0, 3, '2016-09-19 08:12:55'),
(236, '408', 14, '', 'Shashank Solanki', 9821234636, '', 1, 0, 3, '2016-09-19 08:14:37'),
(237, '508', 14, '', 'Mohammad Jafar Shauket Ali Dodhiya', 9167933559, '', 1, 0, 3, '2016-09-19 08:16:38'),
(238, '207', 14, '', 'Dilip K Patra', 9820103003, '', 1, 0, 3, '2016-09-19 08:22:57'),
(239, '306', 14, '', 'Shefali Patra', 9820103003, '', 1, 0, 3, '2016-09-19 08:46:38'),
(240, '606', 14, '', 'Irfan H Patel', 9821718280, '', 1, 0, 3, '2016-09-19 08:49:57'),
(241, '607', 14, '', 'Imran Salauddin Khalifa', 9930899035, '', 1, 0, 3, '2016-09-19 08:51:30'),
(242, '706', 14, '', 'Ahmed Batliwala', 9820012712, '', 1, 1, 3, '2016-09-19 08:52:46'),
(243, 'Shop no 08', NULL, '', 'Mehboob A Sutaria & others', 9820776768, '', 1, 0, 3, '2016-09-19 08:55:14'),
(244, '707', 8, '', 'Mustafa R Virani', 9892822844, '', 1, 0, 3, '2016-09-19 09:08:57'),
(245, '701', 9, '', 'Kasamali & Ashraf K Kerawala', 9930816241, '', 1, 0, 3, '2016-09-19 09:13:19'),
(246, '702', 12, '', 'Sulema S Samani', 7303377777, '', 1, 0, 3, '2016-09-19 09:21:29'),
(247, '406', 11, '', 'Indumati M Pradhan', 9967394604, '', 1, 0, 3, '2016-09-19 09:25:23'),
(248, '201', 16, '', 'NIDA FAZALI M. HUSSAIN', 8425933990, '', 1, 0, 6, '2016-09-22 02:21:08'),
(249, '202', 16, '', 'KARISHMA HASIJA', 9820931535, '', 1, 0, 6, '2016-09-22 02:23:26'),
(250, '203', 16, '', 'SUBHASH R. DURAGKAR', 9322009265, '', 1, 0, 6, '2016-09-22 02:24:35'),
(251, '204', 16, '', 'HASSAN H. KESHWANI & SHEHNAZ H. KESHWANI', 9821541382, '', 1, 0, 6, '2016-09-22 02:26:26'),
(252, '301', 16, '', 'VIKRAM JIT SINGH', 9969313637, '', 1, 0, 6, '2016-09-22 02:27:39'),
(253, '401', 16, '', 'MOHD. IQBAL ABDUL K. GANDHI', 9833089441, '', 1, 0, 6, '2016-09-22 02:30:00'),
(254, '402', 16, '', 'LOKHANDE MANOHAR K.', 9699796666, '', 1, 0, 6, '2016-09-22 02:31:45'),
(255, '403', 16, '', 'ANWARALI R. MERCHANT', 9869278414, '', 1, 0, 6, '2016-09-22 02:32:41'),
(256, '404', 16, '', 'LIYAKAT J. ANKLESHWARIA', 9819992555, '', 1, 0, 6, '2016-09-22 02:33:35'),
(257, '503', 16, '', 'ANUP N. SHUKLA', 9833161433, '', 1, 0, 6, '2016-09-22 02:36:17'),
(258, '504', 16, '', 'VENGURLEKAR', 9004273473, '', 1, 0, 6, '2016-09-22 02:37:18'),
(259, '601', 16, '', 'AJAY N. SHUKLA', 9892498188, '', 1, 0, 6, '2016-09-22 02:38:38'),
(260, '602', 16, '', 'FAREEDA C. LOUIS', 9820081696, '', 1, 0, 6, '2016-09-22 02:39:39'),
(261, '603', 16, '', 'HARSHA N. IYER', 9820138290, '', 1, 0, 6, '2016-09-22 02:40:36'),
(262, '604', 16, '', 'S.C. GHORAI', 9820319999, '', 1, 0, 6, '2016-09-22 02:41:52'),
(263, '701', 16, '', 'JOHN RAO PRAKASH RAO JANUMALA & SUJHATHA JOHN RAO', 9819939977, '', 1, 0, 6, '2016-09-22 02:43:19'),
(264, '702', 16, '', 'IMTIYAZ A. PUNJABI', 9820092658, '', 1, 0, 6, '2016-09-22 02:44:15'),
(265, '703', 16, '', 'RAVINDRA B. KATARIA', 8097305530, '', 1, 0, 6, '2016-09-22 02:45:23'),
(266, '704', 16, '', 'MEDHA A. MASURKAR', 8976515031, '', 1, 0, 6, '2016-09-22 02:46:17');

-- --------------------------------------------------------

--
-- Table structure for table `flat_bill_relation`
--

DROP TABLE IF EXISTS `flat_bill_relation`;
CREATE TABLE IF NOT EXISTS `flat_bill_relation` (
  `flat_id` int(10) UNSIGNED NOT NULL,
  `bill_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `flat_bill_relation`
--

INSERT INTO `flat_bill_relation` (`flat_id`, `bill_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(5, 1),
(6, 1),
(8, 1),
(10, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(31, 1),
(32, 1),
(33, 1),
(34, NULL),
(35, NULL),
(36, NULL),
(38, NULL),
(39, NULL),
(40, NULL),
(41, NULL),
(42, NULL),
(43, NULL),
(109, NULL),
(110, NULL),
(111, NULL),
(112, NULL),
(113, NULL),
(114, NULL),
(115, NULL),
(116, NULL),
(117, NULL),
(118, NULL),
(119, NULL),
(120, NULL),
(121, NULL),
(122, NULL),
(123, NULL),
(124, NULL),
(125, NULL),
(126, NULL),
(127, NULL),
(128, NULL),
(129, NULL),
(130, NULL),
(131, NULL),
(132, NULL),
(133, NULL),
(134, NULL),
(135, NULL),
(136, NULL),
(137, NULL),
(139, NULL),
(140, NULL),
(141, NULL),
(142, NULL),
(143, NULL),
(144, NULL),
(145, NULL),
(146, NULL),
(147, NULL),
(148, NULL),
(149, NULL),
(150, NULL),
(151, NULL),
(152, NULL),
(153, NULL),
(154, NULL),
(155, NULL),
(156, NULL),
(157, NULL),
(158, NULL),
(159, NULL),
(160, NULL),
(161, NULL),
(162, NULL),
(163, NULL),
(164, NULL),
(165, NULL),
(166, NULL),
(167, NULL),
(168, NULL),
(169, NULL),
(170, NULL),
(171, NULL),
(172, NULL),
(173, NULL),
(174, NULL),
(175, NULL),
(176, NULL),
(177, NULL),
(178, NULL),
(179, NULL),
(180, NULL),
(181, NULL),
(182, NULL),
(183, NULL),
(184, NULL),
(185, NULL),
(186, NULL),
(187, NULL),
(188, NULL),
(189, NULL),
(190, NULL),
(191, NULL),
(192, NULL),
(193, NULL),
(194, NULL),
(195, NULL),
(196, NULL),
(197, NULL),
(198, NULL),
(199, NULL),
(200, NULL),
(201, NULL),
(202, NULL),
(203, NULL),
(204, NULL),
(205, NULL),
(206, NULL),
(207, NULL),
(208, NULL),
(209, NULL),
(210, NULL),
(211, NULL),
(212, NULL),
(213, NULL),
(214, NULL),
(215, NULL),
(216, NULL),
(217, NULL),
(218, NULL),
(219, NULL),
(220, NULL),
(221, NULL),
(222, NULL),
(223, NULL),
(224, NULL),
(225, NULL),
(226, NULL),
(227, NULL),
(228, NULL),
(229, NULL),
(230, NULL),
(231, NULL),
(232, NULL),
(233, NULL),
(234, NULL),
(235, NULL),
(236, NULL),
(237, NULL),
(238, NULL),
(239, NULL),
(240, NULL),
(241, NULL),
(242, NULL),
(243, NULL),
(244, NULL),
(245, NULL),
(246, NULL),
(247, NULL),
(248, NULL),
(249, NULL),
(250, NULL),
(251, NULL),
(252, NULL),
(253, NULL),
(254, NULL),
(255, NULL),
(256, NULL),
(257, NULL),
(258, NULL),
(259, NULL),
(260, NULL),
(261, NULL),
(262, NULL),
(263, NULL),
(264, NULL),
(265, NULL),
(266, NULL),
(4, 1),
(7, 1),
(9, 1),
(11, 1),
(20, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `flat_invoice`
--

DROP TABLE IF EXISTS `flat_invoice`;
CREATE TABLE IF NOT EXISTS `flat_invoice` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_month` date NOT NULL,
  `advance_month` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `flat_id` int(11) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) UNSIGNED NOT NULL,
  `principal_amount` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `is_paid` enum('1','2','3') NOT NULL DEFAULT '2',
  `amount_paid` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `cheque_amount` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `payment_method` enum('1','2','3','4') NOT NULL DEFAULT '4',
  `note` text NOT NULL,
  `cheque_no` char(100) NOT NULL,
  `cheque_date` date DEFAULT NULL,
  `date_of_payment` date DEFAULT NULL,
  `bill_arrears` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `bill_advance_amount` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `cheque_status` enum('1','2','3','4') NOT NULL DEFAULT '3',
  `debit_arrear` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `credit_arrear` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `fine` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `late_fee_amonut` decimal(10,2) UNSIGNED NOT NULL DEFAULT '0.00',
  `interest_applied_times` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `society_id` int(11) UNSIGNED NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_flat_invoice` (`flat_id`),
  KEY `fk_inv_soc` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=185 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `flat_invoice`
--

INSERT INTO `flat_invoice` (`id`, `invoice_month`, `advance_month`, `due_date`, `flat_id`, `total_amount`, `principal_amount`, `is_paid`, `amount_paid`, `cheque_amount`, `payment_method`, `note`, `cheque_no`, `cheque_date`, `date_of_payment`, `bill_arrears`, `bill_advance_amount`, `cheque_status`, `debit_arrear`, `credit_arrear`, `fine`, `late_fee_amonut`, `interest_applied_times`, `society_id`, `date_created`) VALUES
(173, '2016-01-01', NULL, '2016-01-15', 1, '1150.00', '1000.00', '1', '1150.00', '0.00', '2', '', 'faadfdg', NULL, '2016-05-09', '0.00', '0.00', '1', '0.00', '0.00', 0, '150.00', 1, 1, '2016-05-08 09:34:38'),
(174, '2016-01-01', NULL, '2016-01-15', 2, '1150.00', '1000.00', '2', '0.00', '0.00', '4', '', '', NULL, NULL, '0.00', '0.00', '3', '0.00', '0.00', 0, '150.00', 1, 1, '2016-05-08 09:34:38'),
(175, '2016-01-01', NULL, '2016-01-15', 3, '1000.00', '1000.00', '1', '1000.00', '0.00', '1', '', '', NULL, '2016-05-08', '0.00', '0.00', '3', '0.00', '0.00', 0, '0.00', 0, 1, '2016-05-08 09:34:38'),
(176, '2016-01-01', NULL, '2016-01-15', 4, '1000.00', '1000.00', '1', '1150.00', '0.00', '2', '', '756287548', '2016-01-23', '2016-05-08', '0.00', '150.00', '1', '0.00', '0.00', 0, '0.00', 0, 1, '2016-05-08 09:34:38'),
(177, '2016-01-01', NULL, '2016-01-15', 5, '1000.00', '1000.00', '1', '975.00', '0.00', '1', '', '', NULL, '2016-05-08', '25.00', '0.00', '3', '0.00', '0.00', 0, '0.00', 0, 1, '2016-05-08 09:34:38'),
(178, '2016-02-01', NULL, '2016-02-15', 1, '1150.00', '1000.00', '2', '0.00', '0.00', '4', '', '', NULL, NULL, '0.00', '0.00', '3', '0.00', '0.00', 0, '150.00', 1, 1, '2016-05-08 09:39:29'),
(179, '2016-02-01', NULL, '2016-02-15', 2, '1150.00', '1000.00', '2', '0.00', '0.00', '4', '', '', NULL, NULL, '0.00', '0.00', '3', '0.00', '0.00', 0, '150.00', 1, 1, '2016-05-08 09:39:29'),
(180, '2016-02-01', NULL, '2016-02-15', 3, '1150.00', '1000.00', '2', '0.00', '0.00', '4', '', '', NULL, NULL, '0.00', '0.00', '3', '0.00', '0.00', 0, '150.00', 1, 1, '2016-05-08 09:39:29'),
(181, '2016-02-01', NULL, '2016-02-15', 4, '1000.00', '1000.00', '2', '0.00', '0.00', '4', '', '', NULL, NULL, '0.00', '0.00', '3', '150.00', '0.00', 0, '150.00', 1, 1, '2016-05-08 09:39:29'),
(182, '2016-02-01', NULL, '2016-02-15', 5, '1175.00', '1000.00', '2', '0.00', '0.00', '4', '', '', NULL, NULL, '0.00', '0.00', '3', '0.00', '25.00', 0, '150.00', 1, 1, '2016-05-08 09:39:29'),
(183, '2016-03-01', NULL, '2016-04-09', 1, '1150.00', '1000.00', '2', '0.00', '0.00', '4', '', '', NULL, NULL, '0.00', '0.00', '3', '0.00', '0.00', 0, '150.00', 1, 1, '2016-05-09 01:27:26'),
(184, '2016-04-01', NULL, '2016-05-10', 1, '1150.00', '1000.00', '2', '0.00', '0.00', '4', '', '', NULL, NULL, '0.00', '0.00', '3', '0.00', '0.00', 0, '150.00', 1, 1, '2016-05-09 02:01:21');

-- --------------------------------------------------------

--
-- Table structure for table `flat_status`
--

DROP TABLE IF EXISTS `flat_status`;
CREATE TABLE IF NOT EXISTS `flat_status` (
  `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `flat_status`
--

INSERT INTO `flat_status` (`id`, `name`) VALUES
(1, 'Residing'),
(2, 'Rent'),
(3, 'Empty');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_files`
--

DROP TABLE IF EXISTS `gallery_files`;
CREATE TABLE IF NOT EXISTS `gallery_files` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `folder_id` int(11) UNSIGNED NOT NULL,
  `image_name` char(40) NOT NULL,
  `caption` char(255) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_gal_folder` (`folder_id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gallery_files`
--

INSERT INTO `gallery_files` (`id`, `folder_id`, `image_name`, `caption`, `date_added`) VALUES
(1, 1, '124bed071bfc608f6933b5b4e7f3ac05.jpg', '', '2016-04-14 10:18:04'),
(2, 1, 'adec7acc23cdd480b08f8c7855f9141f.jpg', '', '2016-04-14 10:18:04'),
(3, 1, '3bd0b977f88297086670b9a50fe47c35.jpg', '', '2016-04-14 10:18:04'),
(10, 1, 'e250ff7f6a01222759dcd615826f6042.jpg', '', '2016-04-14 10:18:47'),
(11, 1, '812d55761b29b0bfeb905df3a7e622d0.jpg', '', '2016-04-14 10:20:39'),
(12, 1, '2f8dbf5e10b8d842df9dbb23ad065743.jpg', '', '2016-04-14 10:20:39'),
(13, 1, '02c9f527ffe80e59b456ca65ef87fc7b.jpg', '', '2016-04-14 10:20:39'),
(14, 1, '56b793b7d045bdc67153f15a323dd257.jpg', '', '2016-04-14 10:20:39'),
(15, 1, 'a4425b9ea629d177093e1716d70851e4.jpg', '', '2016-04-14 10:20:39'),
(16, 2, '1b96cdaa6d63adc265393a8295ecfe62.jpg', '', '2016-04-14 10:25:04'),
(18, 2, 'ae7996970c77979b2d4858bedb436d83.jpg', '', '2016-04-14 10:25:04'),
(26, 2, 'eeb83e87baf7446c42984cc0e29edba6.jpg', '', '2016-04-14 10:25:22'),
(27, 2, '38fb3e067ceff28ffaed46967e1b28a0.jpg', '', '2016-04-14 10:25:22'),
(28, 2, '6218fcfda1f897d77cb1c41f2d6001eb.jpg', '', '2016-04-14 10:25:22'),
(29, 2, '7c7235efc82d855965f2036365b00ea0.jpg', '', '2016-04-14 10:25:22'),
(30, 2, 'c0bc12ebadcdbc3025aaa817d2c5d210.jpg', '', '2016-04-14 10:25:22'),
(47, 4, 'ab5f93b38a116868e82434b73e06b8f9.gif', '', '2016-08-02 01:11:47'),
(48, 4, '4b703e7d5078e33599f6ce98322bd19d.jpg', '', '2016-08-02 01:12:22'),
(49, 4, '82669dfe7538083929431260961f3cc8.jpg', '', '2016-08-02 01:12:22'),
(50, 4, 'e0814b7c87ce50ad0914f509aaafd2d3.jpg', '', '2016-08-02 01:13:37'),
(51, 5, 'b7c234f33c885393e6552393d9813456.gif', '', '2016-08-02 01:14:17'),
(52, 5, '3205b57011360ef53c4d4aaf9bd0af06.jpg', '', '2016-08-02 01:14:17'),
(53, 5, 'a1287efe2d1c892bce3f5157d7786286.jpg', '', '2016-08-02 01:14:17'),
(54, 5, 'a5acaef5ce43f3b3f7c60d5f6b85aa6b.jpg', '', '2016-08-02 01:14:17'),
(55, 6, '9e1be527c8d8f0a7786074cbf4dd4230.jpg', '', '2016-08-15 03:15:11'),
(56, 6, '07932f77e746b6542ec34a0fe75d96e8.jpg', '', '2016-08-15 03:15:11'),
(57, 6, '24538b641f1a0c4ed184a88d75583bfd.jpg', '', '2016-08-15 03:15:11'),
(58, 6, '6ef69b118a3f1ce857f9496e37188ba4.jpg', '', '2016-08-15 03:15:11'),
(59, 6, 'e8f479f3e351402973d68d752092aab2.jpg', '', '2016-08-15 03:15:11'),
(60, 6, '9b7b3b282421b1d53e0e40e81668ab2c.jpg', '', '2016-08-15 03:15:33'),
(61, 6, 'bbcf81765679499fbe1741a553dddcc7.jpg', '', '2016-08-15 03:15:33'),
(62, 6, '6d8abcff9356d8f9942df23cae8afb2c.jpg', '', '2016-08-15 03:15:33'),
(63, 6, '11d783826653d541bc74d4362e62bcf2.jpg', '', '2016-08-15 03:15:33'),
(64, 6, '21b66893d6e1aa9d0dbcc3e88b10caa5.jpg', '', '2016-08-15 03:15:33'),
(65, 6, '83b804076e3dfd46a43c05fcfb46af2c.jpg', '', '2016-08-15 03:15:57'),
(66, 6, 'd27a2ca52c2e710f9ab397423fe34727.jpg', '', '2016-08-15 03:15:57'),
(67, 6, '08132fc157f3c2eb30a8675799326542.jpg', '', '2016-08-15 03:15:57'),
(68, 6, 'dff35114348df6260a47c2fb8d312823.jpg', '', '2016-08-15 03:15:57'),
(69, 6, 'cb2a38978d5c3af876cc3f926c9172ca.jpg', '', '2016-08-15 03:15:57'),
(70, 6, '4083aad4a9dae585af7454acb786d987.jpg', '', '2016-08-15 03:16:12'),
(71, 6, '79dd673f119659ef238884158572eab8.jpg', '', '2016-08-15 03:16:12'),
(72, 6, '10766470596656cb0465a5f90060d0b4.jpg', '', '2016-08-15 03:16:12'),
(73, 6, '3ea20b4109f575cec0a819e36ada89cb.jpg', '', '2016-08-15 06:33:06'),
(74, 6, '92e3912a8a45dcf430d284b9e287f70a.jpg', '', '2016-08-15 06:33:06'),
(75, 6, '053c53a15adc55c561ef76e554fdbd76.jpg', '', '2016-08-15 06:33:06'),
(76, 6, 'c2af5ead83d544ed0977304aa6570500.jpg', '', '2016-08-15 06:33:06'),
(77, 6, 'a8e28af830d89c4003882730ba02eb1d.jpg', '', '2016-08-15 06:33:06'),
(78, 6, '75d103fabdccadbb0895576db59d6f5c.jpg', '', '2016-08-15 06:33:51'),
(79, 6, '306c115b486a9f5ed50bcbe7b19adcaf.jpg', '', '2016-08-15 06:33:51'),
(80, 6, 'ebd3c4ba500943c4add23df15d9bf1ef.jpg', '', '2016-08-15 06:33:51'),
(81, 6, '05f54a94836ae55019144760d22dc685.jpg', '', '2016-08-15 06:33:51'),
(82, 6, 'a320a82b6d3434d7408894b78a5ad22f.jpg', '', '2016-08-15 06:33:51'),
(83, 6, '8c30cf3f5c7a602c1b20df075a05da16.jpg', '', '2016-08-15 06:34:16'),
(84, 6, 'b8f93631c50a58bd23224d11727520ce.jpg', '', '2016-08-15 06:34:16'),
(85, 7, '68efe622a1d94467fa244427e8596a2c.jpeg', '', '2016-09-19 04:53:35'),
(86, 7, 'a7d19d4bd6a91e216a810b967324cc8a.jpeg', '', '2016-09-19 04:53:35');

-- --------------------------------------------------------

--
-- Table structure for table `gallery_folder`
--

DROP TABLE IF EXISTS `gallery_folder`;
CREATE TABLE IF NOT EXISTS `gallery_folder` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `folder_name` char(100) NOT NULL,
  `description` text NOT NULL,
  `uploaded_by` int(11) UNSIGNED NOT NULL,
  `no_of_pics` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `society_id` int(11) UNSIGNED NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_gal_soc` (`society_id`),
  KEY `fk_user_gal` (`uploaded_by`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `gallery_folder`
--

INSERT INTO `gallery_folder` (`id`, `folder_name`, `description`, `uploaded_by`, `no_of_pics`, `society_id`, `date_created`) VALUES
(1, 'Wedding at our society', '', 1, 9, 1, '2016-04-14 10:17:53'),
(2, 'Carnival Season', '', 1, 7, 1, '2016-04-14 10:24:55'),
(4, 'Birthday Celebration', 'Birthday Celebration of Master Ajay Gandhi', 85, 4, 3, '2016-08-01 13:01:06'),
(5, 'New Year Celebration', '', 85, 4, 3, '2016-08-02 01:03:27'),
(6, '15 Aug 2016', '', 85, 30, 3, '2016-08-15 03:14:19'),
(7, '28 AGM 18092016', '', 85, 2, 3, '2016-09-19 04:48:21');

-- --------------------------------------------------------

--
-- Table structure for table `ie_category`
--

DROP TABLE IF EXISTS `ie_category`;
CREATE TABLE IF NOT EXISTS `ie_category` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(25) NOT NULL,
  `c_type` enum('1','2') NOT NULL DEFAULT '1',
  `society_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_iec_soc` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ie_category`
--

INSERT INTO `ie_category` (`id`, `name`, `c_type`, `society_id`) VALUES
(1, 'Watchmen', '2', 1),
(2, 'Painter', '2', 1),
(5, 'Electrician', '2', 1),
(6, 'House Rent Tokens', '1', 1),
(7, 'Voluntary Donation', '1', 1),
(9, 'Telephone', '2', 1),
(10, 'Rent for Hall or Garden', '1', 1),
(11, 'Transfer Premium', '1', 1),
(12, 'Non Residents Charge', '1', 1),
(13, 'Civil Work', '2', 1);

-- --------------------------------------------------------

--
-- Table structure for table `income_expense`
--

DROP TABLE IF EXISTS `income_expense`;
CREATE TABLE IF NOT EXISTS `income_expense` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `amount` decimal(10,2) UNSIGNED NOT NULL,
  `date_of_payment` date NOT NULL,
  `giver_taker` char(50) NOT NULL,
  `category_id` int(11) UNSIGNED DEFAULT NULL,
  `payment_method` enum('1','2','3') NOT NULL DEFAULT '1',
  `cheque_no` char(50) NOT NULL,
  `added_by` int(11) UNSIGNED NOT NULL,
  `society_id` int(11) UNSIGNED NOT NULL,
  `trans_type` enum('1','2') NOT NULL DEFAULT '1',
  `bill_id` bigint(20) UNSIGNED DEFAULT NULL,
  `authorised_by` int(10) UNSIGNED DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ie_society` (`society_id`),
  KEY `fk_ie_user` (`added_by`),
  KEY `fk_ie_category` (`category_id`),
  KEY `fk_ie_auth_user` (`authorised_by`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `income_expense`
--

INSERT INTO `income_expense` (`id`, `amount`, `date_of_payment`, `giver_taker`, `category_id`, `payment_method`, `cheque_no`, `added_by`, `society_id`, `trans_type`, `bill_id`, `authorised_by`, `date_added`) VALUES
(38, '10000.00', '2016-04-13', 'Bahubali', 1, '1', '', 1, 1, '2', NULL, NULL, '2016-04-13 09:58:15'),
(40, '5000.00', '2016-03-16', 'Buluf', NULL, '2', '', 1, 1, '2', NULL, NULL, '2016-04-13 11:16:11'),
(47, '3250.00', '2016-04-12', 'Babu bhai', 1, '1', '', 1, 1, '2', NULL, NULL, '2016-04-13 14:03:52'),
(50, '1231.00', '2016-04-19', 'Flat No : O-11', NULL, '1', '', 1, 1, '1', 134, NULL, '2016-04-19 11:19:46'),
(51, '1200.00', '2016-04-19', 'Flat No : O-10', NULL, '1', '', 1, 1, '1', 133, NULL, '2016-04-19 12:06:20'),
(52, '1231.00', '2016-04-27', 'Flat No : O-9', NULL, '1', '', 1, 1, '1', 132, NULL, '2016-04-27 01:30:31'),
(53, '1231.00', '2016-04-27', 'Flat No : M-1', NULL, '2', '', 1, 1, '1', 102, NULL, '2016-04-27 01:33:57'),
(54, '1500.00', '2016-04-28', 'Rohan Electricals', 5, '2', '215635', 1, 1, '2', NULL, NULL, '2016-04-28 07:13:03'),
(55, '10000.00', '2016-04-28', 'Savio DSouza', 6, '2', '123254', 1, 1, '1', NULL, NULL, '2016-04-28 07:14:11'),
(56, '3250.00', '2016-04-29', 'Bank of India', 7, '2', '524123', 1, 1, '1', NULL, NULL, '2016-04-29 01:19:16'),
(57, '1231.00', '2016-05-13', 'Flat No : M-1', NULL, '2', '6354757', 1, 1, '1', 135, NULL, '2016-04-29 01:31:16'),
(58, '1231.00', '2016-04-29', 'Flat No : N-7', NULL, '3', '', 1, 1, '1', 172, NULL, '2016-04-29 03:30:31'),
(60, '1231.00', '2016-04-30', 'Flat No : N-11', NULL, '3', '', 1, 1, '1', 123, NULL, '2016-04-30 01:40:18'),
(61, '1231.00', '2016-04-30', 'Flat No : N-11', NULL, '3', '', 1, 1, '1', 166, NULL, '2016-04-30 01:40:29'),
(62, '5500.00', '2016-05-01', 'mohan bhai', 2, '2', '1452369', 1, 1, '2', NULL, NULL, '2016-05-01 01:57:43'),
(63, '515.00', '2016-05-02', 'Manish singh', 6, '2', '584754465', 1, 1, '1', NULL, NULL, '2016-05-02 05:20:33'),
(65, '100.00', '2016-05-05', '', 2, '2', '', 1, 1, '2', NULL, NULL, '2016-05-05 00:09:57'),
(67, '1231.00', '2016-05-05', 'Flat No : M-11', NULL, '3', '', 1, 1, '1', 165, NULL, '2016-05-05 01:26:25'),
(68, '1262.00', '2016-05-05', 'Flat No : O-10', NULL, '3', '', 1, 1, '1', 164, NULL, '2016-05-05 01:26:25'),
(69, '975.00', '2016-05-08', 'Flat No : M-5', NULL, '1', '', 1, 1, '1', 177, NULL, '2016-05-08 09:36:49'),
(70, '1150.00', '2016-05-08', 'Flat No : M-4', NULL, '2', '756287548', 1, 1, '1', 176, NULL, '2016-05-08 09:37:37'),
(71, '1000.00', '2016-05-08', 'Flat No : M-3', NULL, '1', '', 1, 1, '1', 175, NULL, '2016-05-08 09:38:25'),
(72, '1150.00', '2016-05-09', 'Flat No : M-1', NULL, '2', 'faadfdg', 1, 1, '1', 173, NULL, '2016-05-09 02:49:20'),
(73, '5000.00', '2016-07-11', '', 2, '2', '', 1, 1, '2', NULL, NULL, '2016-07-11 04:19:37'),
(74, '1000.00', '2016-10-09', 'Pankur for painting', 5, '2', '5465 8454', 1, 1, '2', NULL, 22, '2016-10-09 06:03:46'),
(75, '3535.00', '2016-10-09', '', 5, '2', '', 1, 1, '2', NULL, NULL, '2016-10-09 06:38:18'),
(76, '5454.00', '2016-10-09', 'hfbfhndfhdd', 5, '2', '', 1, 1, '2', NULL, 22, '2016-10-09 06:50:02'),
(77, '500.00', '2016-10-09', 'Khalid Lakdawala', NULL, '1', '', 85, 3, '2', NULL, NULL, '2016-10-09 09:04:44');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_particular`
--

DROP TABLE IF EXISTS `invoice_particular`;
CREATE TABLE IF NOT EXISTS `invoice_particular` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `particulars` char(255) NOT NULL,
  `amount` decimal(10,2) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoice_id` (`invoice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1254 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoice_particular`
--

INSERT INTO `invoice_particular` (`id`, `invoice_id`, `particulars`, `amount`) VALUES
(1170, 173, 'Municipal Tax', '250.00'),
(1171, 173, 'Water Charges', '250.00'),
(1172, 173, 'Non Agricultural Assessment', '125.00'),
(1173, 173, 'Sinking Fund', '75.00'),
(1174, 173, 'Service Charges', '130.00'),
(1175, 173, 'Insurance Premium', '120.00'),
(1176, 173, 'Additional Charges', '50.00'),
(1177, 174, 'Municipal Tax', '250.00'),
(1178, 174, 'Water Charges', '250.00'),
(1179, 174, 'Non Agricultural Assessment', '125.00'),
(1180, 174, 'Sinking Fund', '75.00'),
(1181, 174, 'Service Charges', '130.00'),
(1182, 174, 'Insurance Premium', '120.00'),
(1183, 174, 'Additional Charges', '50.00'),
(1184, 175, 'Municipal Tax', '250.00'),
(1185, 175, 'Water Charges', '250.00'),
(1186, 175, 'Non Agricultural Assessment', '125.00'),
(1187, 175, 'Sinking Fund', '75.00'),
(1188, 175, 'Service Charges', '130.00'),
(1189, 175, 'Insurance Premium', '120.00'),
(1190, 175, 'Additional Charges', '50.00'),
(1191, 176, 'Municipal Tax', '250.00'),
(1192, 176, 'Water Charges', '250.00'),
(1193, 176, 'Non Agricultural Assessment', '125.00'),
(1194, 176, 'Sinking Fund', '75.00'),
(1195, 176, 'Service Charges', '130.00'),
(1196, 176, 'Insurance Premium', '120.00'),
(1197, 176, 'Additional Charges', '50.00'),
(1198, 177, 'Municipal Tax', '250.00'),
(1199, 177, 'Water Charges', '250.00'),
(1200, 177, 'Non Agricultural Assessment', '125.00'),
(1201, 177, 'Sinking Fund', '75.00'),
(1202, 177, 'Service Charges', '130.00'),
(1203, 177, 'Insurance Premium', '120.00'),
(1204, 177, 'Additional Charges', '50.00'),
(1205, 178, 'Municipal Tax', '250.00'),
(1206, 178, 'Water Charges', '250.00'),
(1207, 178, 'Non Agricultural Assessment', '125.00'),
(1208, 178, 'Sinking Fund', '75.00'),
(1209, 178, 'Service Charges', '130.00'),
(1210, 178, 'Insurance Premium', '120.00'),
(1211, 178, 'Additional Charges', '50.00'),
(1212, 179, 'Municipal Tax', '250.00'),
(1213, 179, 'Water Charges', '250.00'),
(1214, 179, 'Non Agricultural Assessment', '125.00'),
(1215, 179, 'Sinking Fund', '75.00'),
(1216, 179, 'Service Charges', '130.00'),
(1217, 179, 'Insurance Premium', '120.00'),
(1218, 179, 'Additional Charges', '50.00'),
(1219, 180, 'Municipal Tax', '250.00'),
(1220, 180, 'Water Charges', '250.00'),
(1221, 180, 'Non Agricultural Assessment', '125.00'),
(1222, 180, 'Sinking Fund', '75.00'),
(1223, 180, 'Service Charges', '130.00'),
(1224, 180, 'Insurance Premium', '120.00'),
(1225, 180, 'Additional Charges', '50.00'),
(1226, 181, 'Municipal Tax', '250.00'),
(1227, 181, 'Water Charges', '250.00'),
(1228, 181, 'Non Agricultural Assessment', '125.00'),
(1229, 181, 'Sinking Fund', '75.00'),
(1230, 181, 'Service Charges', '130.00'),
(1231, 181, 'Insurance Premium', '120.00'),
(1232, 181, 'Additional Charges', '50.00'),
(1233, 182, 'Municipal Tax', '250.00'),
(1234, 182, 'Water Charges', '250.00'),
(1235, 182, 'Non Agricultural Assessment', '125.00'),
(1236, 182, 'Sinking Fund', '75.00'),
(1237, 182, 'Service Charges', '130.00'),
(1238, 182, 'Insurance Premium', '120.00'),
(1239, 182, 'Additional Charges', '50.00'),
(1240, 183, 'Municipal Tax', '250.00'),
(1241, 183, 'Water Charges', '250.00'),
(1242, 183, 'Non Agricultural Assessment', '125.00'),
(1243, 183, 'Sinking Fund', '75.00'),
(1244, 183, 'Service Charges', '130.00'),
(1245, 183, 'Insurance Premium', '120.00'),
(1246, 183, 'Additional Charges', '50.00'),
(1247, 184, 'Municipal Tax', '250.00'),
(1248, 184, 'Water Charges', '250.00'),
(1249, 184, 'Non Agricultural Assessment', '125.00'),
(1250, 184, 'Sinking Fund', '75.00'),
(1251, 184, 'Service Charges', '130.00'),
(1252, 184, 'Insurance Premium', '120.00'),
(1253, 184, 'Additional Charges', '50.00');

-- --------------------------------------------------------

--
-- Table structure for table `members_messages`
--

DROP TABLE IF EXISTS `members_messages`;
CREATE TABLE IF NOT EXISTS `members_messages` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `read_by_sender` enum('1','2') NOT NULL DEFAULT '1',
  `read_by_reciever` enum('1','2') NOT NULL DEFAULT '1',
  `message_subject` char(255) NOT NULL,
  `message` text NOT NULL,
  `message_type` smallint(5) UNSIGNED NOT NULL,
  `sent_from` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `conv_type` enum('1','2') NOT NULL DEFAULT '1',
  `society_id` int(11) UNSIGNED NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_msg_society` (`society_id`),
  KEY `fk_msg_sender` (`sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `members_messages`
--

INSERT INTO `members_messages` (`id`, `sender_id`, `read_by_sender`, `read_by_reciever`, `message_subject`, `message`, `message_type`, `sent_from`, `conv_type`, `society_id`, `date_sent`) VALUES
(1, 1, '2', '1', 'My first complaints', 'Please stop this noise during the afternoon', 2, 1, '2', 1, '2016-04-18 01:25:36'),
(2, 1, '2', '1', 'Water leakage', 'Thetr uh fddhh frr cvhr ouykj sgghh\r\nKhalid', 2, 1, '1', 1, '2016-04-27 03:36:01'),
(3, 1, '2', '1', 'Higifjccccjh', 'Hccchcncnjc', 1, 1, '2', 1, '2016-04-27 04:06:39'),
(4, 78, '2', '1', 'Tedt', 'Tedting by chance', 1, 1, '1', 1, '2016-04-28 07:09:48'),
(5, 78, '2', '1', 'Shifring Inquiry', 'Hello,\r\nI am shifting to another wing. Please advice.', 1, 1, '1', 1, '2016-04-28 23:45:50'),
(6, 6, '1', '1', 'new complaint', 'Hii\r\nPlease consider this as my complaint.\r\nThanks,\r\nAbu', 2, 1, '1', 1, '2016-05-05 02:41:30'),
(7, 78, '1', '1', 'dgdgdsgs', 'dgsdgsdgsdgsdgsdg', 1, 1, '1', 1, '2016-05-10 09:26:10');

-- --------------------------------------------------------

--
-- Table structure for table `members_messages_reply`
--

DROP TABLE IF EXISTS `members_messages_reply`;
CREATE TABLE IF NOT EXISTS `members_messages_reply` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED NOT NULL,
  `reply_text` text NOT NULL,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `admin_id` int(11) UNSIGNED DEFAULT NULL,
  `reply_from` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `reply_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_msg_parent` (`parent_id`),
  KEY `fk_msg_user` (`user_id`),
  KEY `fk_msg_admin` (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `members_messages_reply`
--

INSERT INTO `members_messages_reply` (`id`, `parent_id`, `reply_text`, `user_id`, `admin_id`, `reply_from`, `reply_date`) VALUES
(1, 1, 'ok', NULL, 1, 2, '2016-04-18 01:26:05'),
(2, 3, 'Xbxhcc', 1, NULL, 1, '2016-04-27 04:06:49'),
(3, 3, 'C', 1, NULL, 1, '2016-04-27 04:06:52'),
(4, 3, '\r\nDdk', 1, NULL, 1, '2016-04-27 04:07:11'),
(5, 4, 'ok done', NULL, 1, 2, '2016-04-28 07:10:14'),
(6, 4, 'all well?', NULL, 1, 2, '2016-05-01 04:48:03'),
(7, 2, 'Hii', 1, NULL, 1, '2016-05-03 02:39:31'),
(8, 2, 'fhfdhfdh', NULL, 1, 2, '2016-05-03 03:04:40'),
(9, 2, 'gfjf', NULL, 1, 2, '2016-05-04 01:21:00'),
(10, 6, 'ok', NULL, 1, 2, '2016-05-05 02:44:31'),
(13, 2, 'Still its not done...', 1, NULL, 1, '2016-05-05 04:34:27'),
(14, 2, 'Ddf', NULL, 1, 2, '2016-05-12 13:21:00'),
(15, 2, 'Seef', NULL, 1, 2, '2016-05-12 13:32:25'),
(16, 2, 'Hhh', NULL, 1, 2, '2016-05-12 13:33:35'),
(17, 2, 'Qqq', NULL, 1, 2, '2016-05-12 13:34:11'),
(18, 5, 'ok', NULL, 1, 2, '2016-05-14 13:12:43'),
(19, 5, 'dd', NULL, 1, 2, '2016-05-14 13:13:00'),
(20, 2, 'Hmm', 1, NULL, 1, '2016-05-16 04:05:33'),
(21, 2, 'kgjlhjlhjlhjl\r\ngjlhjl', NULL, 1, 2, '2016-05-16 05:16:57'),
(22, 2, 'gjfhj', NULL, 1, 2, '2016-05-16 05:17:49'),
(23, 2, 'gcjgfjkf', NULL, 1, 2, '2016-05-16 05:21:22'),
(24, 2, 'gj', NULL, 1, 2, '2016-05-16 05:21:47'),
(25, 2, 'gjgf', NULL, 1, 2, '2016-05-16 05:22:01'),
(26, 2, 'hj', NULL, 1, 2, '2016-09-20 06:34:04'),
(27, 2, 'ghgh', NULL, 1, 2, '2016-09-20 06:35:17'),
(28, 2, 'dgbdfh', NULL, 1, 2, '2016-09-20 07:24:32'),
(29, 2, 'm', NULL, 1, 2, '2016-09-20 07:25:13'),
(30, 2, 'new reply\r\nhi', NULL, 1, 2, '2016-09-20 07:29:32'),
(31, 2, ',', NULL, 1, 2, '2016-09-20 07:29:55'),
(32, 3, 'm.mkm,mm,n,', NULL, 1, 2, '2016-09-20 07:30:31'),
(33, 2, 'new version', NULL, 1, 2, '2016-09-20 11:18:29');

-- --------------------------------------------------------

--
-- Table structure for table `member_types`
--

DROP TABLE IF EXISTS `member_types`;
CREATE TABLE IF NOT EXISTS `member_types` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `member_types`
--

INSERT INTO `member_types` (`id`, `name`) VALUES
(1, 'Resident'),
(2, 'Secretary'),
(3, 'President');

-- --------------------------------------------------------

--
-- Table structure for table `news_category`
--

DROP TABLE IF EXISTS `news_category`;
CREATE TABLE IF NOT EXISTS `news_category` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_name` char(100) NOT NULL,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `no_of_news` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `c_icon` char(20) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_news_parent` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news_category`
--

INSERT INTO `news_category` (`id`, `category_name`, `parent_id`, `no_of_news`, `c_icon`, `date_added`) VALUES
(1, 'Business', NULL, 1, 'fa-rupee', '2016-04-19 05:33:14'),
(2, 'Technology', NULL, 0, 'fa-industry', '2016-04-19 05:33:14'),
(3, 'Entertainment', NULL, 0, 'fa-film', '2016-04-19 05:33:37'),
(4, 'Sports', NULL, 0, 'fa-futbol-o', '2016-04-19 05:33:37'),
(5, 'Politics', NULL, 0, 'fa-user-secret', '2016-04-19 05:35:23'),
(6, 'Science', NULL, 0, 'fa-rocket', '2016-04-19 05:35:23'),
(7, 'World', NULL, 1, 'fa-globe', '2016-04-19 07:12:42');

-- --------------------------------------------------------

--
-- Table structure for table `news_data`
--

DROP TABLE IF EXISTS `news_data`;
CREATE TABLE IF NOT EXISTS `news_data` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(11) UNSIGNED NOT NULL,
  `title` char(255) NOT NULL,
  `news_body` text NOT NULL,
  `news_cover` char(40) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_news_p_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news_data`
--

INSERT INTO `news_data` (`id`, `category_id`, `title`, `news_body`, `news_cover`, `date_added`) VALUES
(1, 7, 'Pathankot attack probe: NIA to send fresh LRs to Pak', '<p>NIA has readied fresh Letters Rogatory (LRs) to be sent to <a class=\"storyTags\" href=\"/search?type=news&q=Pakistan\" target=\"_blank\">Pakistan </a>containing the addresses of four Jaish-e-Mohammed terrorists who attacked the strategic Pathankot <a class=\"storyTags\" href=\"/search?type=news&q=Iaf\" target=\"_blank\">IAF </a>base in January.\n<br /><br />	The LRs are being despatched notwithstanding indications from the Pakistani side that it was not yet ready to receive Indian investigators to carry forward the probe in the January two attack that left seven security personnel dead. Four terrorists were also killed in the 80-hour gunbattle.\n<br /><br />	The National Investigation Agency (NIA) had put the pictures of the four dead terrorists on its official website and asked general public for help in identifying them.\n<br /><br />	According to official sources, the central probe agency, set up in the aftermath of 26/11 <a class=\"storyTags\" href=\"/search?type=news&q=Mumbai\" target=\"_blank\">Mumbai </a>attacks, was flooded with many emails, some of which originated from Pakistan also, giving information about the terrorists.\n<br /><br />	NIA, during its interaction with the Joint Investigation Team (JIT) of Pakistan, had sought details about the place of residence of the terrorists whose names had been shared with the visiting probe team. However, there was no response from Pakistan on the India\'s request.\n<br /><br />	The five-member JIT also comprising an ISI officer had visited India from March 27 to April one during which they visited the air base and recorded statements of 16 witnesses.\n<br /><br />	During the exercise of verification of the information gathered through emails, the NIA showed the pictures and addresses to some of the jailed terrorists of Jaish-e-Mohammed terror group lodged in jails here and got important inputs from them.</p>', '391-300x200.jpg', '2016-04-19 07:14:29'),
(2, 1, 'After Protests, New Provident Fund Rules Put On Hold: 10 Developments', 'Confronted by protests - including a riot in Bengaluru that saw buses and police jeeps set on fire - the government has put on hold for three months controversial new rules on when you can withdraw money from your provident fund, which serves as a savings plan for when salaried workers retire.<br /><br />\r\nHere is your 10-point cheat-sheet to this story:<br /><br />\r\n1) In February, the government said that to claim or withdraw what your employer has deposited for you, you must wait till you turn 58 (the age limit was earlier 54). The new rule has been suspended till at least the end of July, the government said today.<br /><br />\r\n2) \"The notification (tightening PF withdrawal norms) will be kept in abeyance for three months till July 31, 2016. We will discuss this issue with the stakeholders,\" Labour Minister Bandaru Dattatreya told reporters.<br /><br />\r\n3) Once you are hired, a portion of your salary with a matching amount from your employer is automatically deposited in an account that earns interest.<br /><br />\r\n4) To withdraw what you have contributed, you need to be 58 years old, which is the official retirement age in India.<br /><br />\r\n5) Many labour unions said that workers in some sectors -garment factories, for example - are unsure of being employed after they turn 50; so making them wait till they are 58 to collect what employers have contributed for them does not make sense.<br /><br />\r\n6) In Bengaluru, thousands of garment factory workers set buses on fire and clashed with the police on major highways on the outskirts of the city.<br /><br />\r\n7) In his Union Budget presented in February, Finance Minister Arun Jaitley announced that 60 per cent of the amount in your provident fund would be taxed when the account was emptied out or when you cashed in what you had saved.<br /><br />\r\n8) After outrage from the middle class, that plan was cancelled.<br /><br />\r\n9) Now, the government is considering whether you should be allowed to withdraw the entire savings accumulated in your provident fund for a fixed set of criteria like medical treatment for a serious illness, a home purchase, or the education or marriage of an employee\'s children.<br /><br />\r\n10) Workers who have been unemployed for two months have till the end of July to claim the entire amount accrued in their provident fund. ', 'provident-fund-pf-money.jpg', '2016-04-19 07:19:13'),
(3, 1, 'FDI inflows hit record high', 'Foreign direct investment (FDI) inflows to India have scaled a record high, reports fe Bureau in New Delhi. Total FDI inflows touched a new peak of $51.64 billion in the first 11 months of 2015-16, compared with the previous high of $46.56 billion in all of the 2011-12 fiscal and $44.29 in the whole of 2014-15, according to the latest data from the Reserve Bank of India.<br>\nThe surge in FDI inflows comes at an opportune time for the country, which is yet to see a meaningful pick-up in domestic investments, especially by the private sector that is battling high indebtedness and excess capacity following a global slowdown.<br>\nThe trend bears testimony to foreign investors feeling confident that their interests are protected here, Department of Industrial Policy and Promotion (DIPP) secretary Ramesh Abhishek said on Monday.<br>\nComputer software and hardware, financial and other services, trading and automobiles witnessed the highest inflows of FDI in equity in 2015-16 (up to December), according to the latest DIPP data. Total FDI includes FDI in equity, reinvested earnings and other capital.<br>\nAbhishek’s statement came close on the heels of a report by the data division of the Financial Times, which showed India pipping China to come out as the highest-ranked country in greenfield capital investment, with $63 billion of FDI announced in 2015. China witnessed a 23% drop in capital investment and a 16% fall in FDI projects due to “plateauing economic growth and rising costs”, the report said.<br>\nAlready, gross fixed capital formation (GFCF) has been estimated at just 31.6% of the country’s GDP in 2015-16, having recorded a decline in each of the years since the new methodology was introduced to calculate national income with 2011-12 as the base year. It dropped from 34.3% in 2011-12 and 32.3% in 2014-15.', 'c63b31d8a7c4f45ea6e76cf531556970.jpg', '2016-04-27 09:33:10'),
(4, 4, 'Kallis ’embarrassed’ by Sports Minister’s ban', '<p><b>Former South Africa all-rounder Jacques Kallis has stated that he is ’embarrassed’ by sports minister Fikile Mbalula’s hosting ban on the country’s main national teams.</b><br>\nMbalula announced on Monday that cricket, rugby, netball, and athletics federations in the country had failed to meet their transformation targets, and were thus banned from hosting or bidding major international events.<br>\nHe said that the ban would be reviewed next year, with Cricket South Africa and the others falling short of the targets set out in a Memorandum of Understanding with the government.<br>\nKallis found the news frustrating, tweeting that ‘politics has no place in sport’ and that he was ’embarrassed to call myself a South African.’</p>', 'sport1.jpg', '2016-04-27 09:37:14'),
(5, 4, 'Rafael Nadal wants drug-test results made public', 'Fed up with being accused of doping, Rafael Nadal has written to the president of the International Tennis Federation and asked for all of his drug-test results and blood profile records to be made public.\n“It can’t be free anymore in our tennis world to speak and to accuse without evidence,” the 14-time Grand Slam champion said in a letter obtained Tuesday by The Associated Press.<br><br>\nNadal’s letter was sent to ITF President David Haggerty on Monday, the same day he filed suit against a former French government minister who suggested he had been doping.<br><br>\n“I know how many times I am tested, on and off competition,” Nadal wrote in the letter. “Please make all my information public. Please make public my biological passport, my complete history of anti-doping controls and tests.\n“From now on I ask you to communicate when I am tested and the results as soon as they are ready from your labs. I also encourage you to start filing lawsuits if there is any misinformation spread by anyone.”\nThe ITF confirmed it received the letter from Nadal, including the request for his test results to be released under the Tennis Anti-Doping Program.<br><br>\n“The ITF can confirm that Mr. Nadal has never failed a test under the TADP and has not been suspended at any time for an anti-doping rule violation or for any other reason related to the TADP,” the ITF said in a statement sent to the AP.\nThe ITF said Nadal, like other players, has access to his anti-doping records through the World Anti-Doping Agency’s database “and is free to make them available.”<br><br>\n“The accuracy of any such release would be verified by the ITF,” the federation said.\nThe Spanish star said he was writing the letter because of remarks by Roselyne Bachelot, France’s former minister for health and sport. She said on a French television show last month that Nadal’s seven-month injury layoff in 2012 was “probably due to a positive doping test.”<br><br>\nNadal, who won his 49th clay-court tournament on Sunday in Barcelona and will go for his 10th French Open title next month, filed a defamation suit against Bachelot in Paris.<br><br>\n“It is unacceptable and mostly unfair that someone that should have knowledge of sports to a certain point and degree can publicly say something like this with no proof or evidence,” Nadal said in the letter to Haggerty.<br><br>\nNadal said some media, fans, and sponsors don’t trust tennis’ anti-doping program.\n“They don’t trust the sport. They think governing bodies cover things up and do nothing,” he said. “We know this is not true. … I believe the time has arrived, and our sport and our governing bodies need to step up in communicating well to the world.”\nNadal said he has never shied away from sharing his thoughts on anti-doping.<br><br>\n“I believe we have to continue with the fight against doping and make the fight stronger and better if possible,” he wrote. “As a player, first an amateur and then a professional, I have been sure that our sport is clean. It is necessary that our sport becomes a flagship in a world where transparency and honesty are two pillars of our conduct and way of living.”<br><br>\nNadal’s letter comes at a time when tennis is dealing with Maria Sharapova’s high-profile doping case. The Russian has been provisionally suspended after testing positive for the newly banned substance meldonium at the Australian Open in January. She is awaiting an ITF disciplinary hearing.', 'nadal1.jpg', '2016-04-27 09:41:51'),
(6, 4, 'Usain Bolt to run 100m again at Golden Spike in Ostrava this May', '<br>Usain Bolt will run the 100 meters at the Golden Spike in the Czech city of Ostrava on May 20.<br><br>\nOrganizers say it will be the Jamaican great’s second start of the Olympic season after a May 14 race in the Cayman Islands, and his first race in Europe.<br><br>\nThe six-time Olympic champion will race for the eighth time at the meet, part of the IAAF world challenge series.<br><br>\nBolt is also scheduled to race in the London Diamond League meet on July 22, two weeks before the Rio de Janeiro Games open.', 'usain-bolt-1.jpg', '2016-04-27 02:49:05'),
(7, 4, 'Zinedine Zidane hopes Real Madrid duo Cristiano Ronaldo and Karim Benzema recover to face Man City', '<p><b>Zinedine Zidane is hopeful both Cristiano Ronaldo and Karim Benzema will be fit for Real Madrid\'s Champions League semi-final second leg at home to Manchester City.</b><br><br>\nRonaldo missed the weekend\'s La Liga win over Rayo Vallecano with a thigh injury and did not feature in the goalless semi-final first leg at the Etihad on Tuesday night, despite training the day before.\n<br><br>\nBenzema, meanwhile, was withdrawn at half-time against City, leaving his participation for the return leg at the Bernabeu on May 4 in doubt.\n\"We hope that they can both be fit for the second leg, that\'s the idea,\" said Real head coach Zidane. \"But we can\'t say right now. We have to see. We have to take it day by day and see how they are.\n<br><br>\n\"Cristiano felt something after that last training session. That\'s why he didn\'t play. Karim, it was a little bit different. But at the end of the day, little by little, he felt a little bit worse in himself. We don\'t want to gamble with his fitness. We know how important both players are for us.\"\n<br><br>\nReal had the better chances in a tight contest, with Benzema\'s replacement Jese heading against the bar in the second half and Pepe firing a close-range shot at Joe Hart.<br><br><b>\"I am happy with the game,\" said Zidane.</b> \"It was not easy but in the end, we defended really well. We had the ball more in the second half and we had more chances after the break. It was a tough game but I am happy with the result.<br><br>\n\"Before this game, it was 50:50 and now it\'s the same. We have the second leg at home, but it\'s 50:50. We are going to have to work like we did today.<br><br>\n\"It is a Champions League semi-final so nothing is easy, but I am happy with the result and the work the players did.\"\n<br><br>\nCity did not have a shot on target until injury-time when Kevin De Bruyne\'s free-kick needed to be tipped over by Keylor Navas.\n<br><br>\n\"Defensively, we played really well,\" said Zidane. \"We know (Sergio) Aguero is a good player so we didn\'t give him much space. The idea was to defend together. We managed to achieve what we wanted.\"</p>', 'ronaldo-1.jpg', '2016-04-27 03:03:02'),
(8, 7, 'Facebook will counter hate speech in Europe', '<blockquote>In a bid to answer critics that it has not done enough to tackle online racist and hate speech at a time when Europe is going through a refugee crisis, Facebook has started a new initiative to counter extremist posts on the social networking website in Europe.</blockquote>\n Called “Online Civil Courage Initiative\", it is based in Berlin and supported by the German Ministry of Justice and Consumer Protection, Financial Times reported on Tuesday.<br><br>\n\"Take a moment to share your story or idea supporting counter speech, with the goal of combatting online extremism and hate speech. In order to make change, everyone needs to “feel empowered to share their voice and exercise? #civilcourage?,” read the message on the initiative\'s Facebook home page.<br><br>\nAccording to Facebook, it will invest one million Euros in European non-governmental organisations that are fighting online extremism.<br><br>\n\"Facebook is not a place for the dissemination of hate speech or incitement to violence. With this new initiative, we can better understand and respond to the challenges of extremist speech on the Internet,” said Facebook chief operating officer Sheryl Sandberg, while announcing the initiative in Berlin.<br><br>\nLondon-based think tank the Institute for Strategic Dialogue will lead the initiative.<br><br>\nThe initiative is a partnership between Facebook, the Institute for Strategic Dialogue, the Amadeu Antonio Foundation and the International Centre for the Study of Radicalisation and Political Violence.<br><br>\nFacebook has faced complaints in the past that it has not done enough to take down racist and xenophobic hate speech.<br><br>\nIn November last year, German prosecutors launched an investigation into the European head of Facebook over the social media platform\'s failure to remove racist hate speech.', 'fb-1.jpg', '2016-04-27 03:17:45'),
(9, 1, 'SC directs Mallya to furnish overseas assets details to banks', '<p>The Supreme Court on Tuesday refused liquor baron Vijay Mallya\'s plea to keep his overseas assets confidential.<br><br>\nThe court ordered his assets to be revealed to banks for recovery of debts.<br><br>\n\"We will even approach the UK government, if required, for recovery of loans through liquidation of Vijay Mallya\'s overseas assets,\" banks tell the apex court.<br><br>\n\"Mallya is a fugitive fleeing from justice. If he really wants to come, a one-way travel permit could have been arranged,\" said the Attorney General.<br><br>\nMr. Mallya said, \"Asking for my overseas assets is a violation of my privacy.\"</p>', 'MALLYA.jpg', '2016-04-27 03:24:28'),
(10, 7, 'EU had offered India gradual, asymmetric elimination of tariffs', 'High on India’s priority list has been access to European markets for Indian service professionals (such as from the IT sector).\r\n<br><br>\r\nThe European Union has said that it offered India the possibility of asymmetric and gradual elimination of tariffs in the car and car parts and wines and spirits sectors as part of the negotiations on the bilateral free trade agreement known as the BTIA (Broad-based Trade and Investment Agreement). A continuing absence of agreement in these sectors has contributed to the lack of progress on the trade deal despite last month’s summit level talks between India and the EU.\r\n<br><br>\r\n“In terms of the car sector, in some cases, EU exporters face Indian import duties of up to 100 per cent on car and car parts,” Daniel Rosario, a European Commission spokesperson for trade told a delegation of journalists from India who are in Brussels as guests of the EU. “We suggested or agreed on long transitional periods for their elimination or even going as far as accepting an asymmetric elimination of these duties in favour of India.”\r\n<br><br>\r\n“The same goes for wines and spirits where our exporters face duties of up to 150 per cent and the proposal made in 2013 was for a gradual if not complete elimination of these duties, again taking into account Indian sensitivities,” Mr. Rosario said.', 'REV_EU_282.jpg', '2016-04-27 03:37:47'),
(11, 1, 'How Baba Ramdev plans to beat Nestlé, P&G and Colgate', '<p>Patanjali Ayurved, co-founded by televangelist Ramdev, is targeting Rs 10,000-crore revenue in 2016-17, after sales grew 150 per cent in the previous financial year to  Rs 5,000 crore (Rs 50 billion).<br /><br />\nThe revenue target, if achieved, will put Patanjali Ayurved ahead of multinationals like Nestlé, Colgate-Palmolive and Procter & Gamble in India.<br /><br />\nDelhi-headquartered Patanjali Ayurved has four business divisions: home care, cosmetics and health, food and beverages, and health drinks.<br /><br />\nThe company would venture into khadi products and animal feed this year, Ramdev said.\n\nPatanjali Ayurved, founded in 2007, has grown more than 10 times in revenue in five years, an unprecedented feat in India’s fast-moving consumer goods industry.\n\n“This is just the beginning. Nestlé, Hindustan Unilever and Colgate- Palmolive will be left clueless eventually,” Ramdev said.\n\nThe company’s Dant Kanti toothpaste posted sales of Rs 450 crore (Rs 4.5 billion) in 2015-16 and Kesh Kanti shampoo and hair oil posted Rs 350 crore (Rs 3.5 billion) sales in less than a year, he pointed out.\n\nPatanjali Ayurved plans to increase its distribution network in 2016-17.\n\nThe company has 4,000 distributors, 10,000 stores and 100 mega-marts.\n\nLast year, Patanjali Ayurved tied up with retail chains the Future Group and Reliance Retail.\n\nTo meet the increasing demand, the FMCG firm will set up at least four new manufacturing units in the current year at a cost of Rs 1,000 crore (Rs 10 billion).\n\n“We will set up six processing units in various parts of the country.\n\nApart from this, we will invest Rs 150 crore in research and development,” Ramdev said.\n\nThe units will come in Vidarbha in Maharashtra, Bundelkhand in Uttar Pradesh and in Madhya Pradesh. “Banks are more than willing to lend to us.<br /><br />\n\"We have no shortage of funds to expand,” Ramdev said.<br /><br />\n“We are going to look at an e-commerce strategy and focus on strengthening our exports to at least 10-12 countries,” said Balkrishna, Ramdev’s disciple and co-founder of Patanjali Ayurved.</p>', 'ram.jpg', '2016-04-27 03:56:36'),
(12, 1, 'Want fast passport? Tap this app for instant police verification', '<p>Cennai: Regional Passport Office Chennai is developing a mobile application for faster police verification to issue passports. It will use the Crime and Criminal Tracking Network System (CCTNS) in the state to track applicants\' antecedents, Regional Passport Officer K Balamurugan told TOI on Tuesday.<br /><br />\r\nWork on the proposal follows the release of Jana Mahiti Report-2016, prepared by a Bengaluru-based NGO which claimed 38.18 lakh was paid as bribe to policemen across the country between 2010 and 2014 to get verification done.<br /><br />\r\n\"There are some glitches between the RPO and the Tamil Nadu police department that are being ironed out,\" Balamurugan said.\r\nSeema Agarwal, additional director general of police (state crime records bureau) who is involved in the proposal, said it was in the conceptual stage.<br /><br />\r\n\"The modalities are being worked out and we have data which can be shared\'\'. Sources said all police stations in the state would have to set up a digital database of crimes and criminals which will be shared on the CCTNS. Former police officers said there was a lack of clarity on what police verification was about, adding that the absence of a digital database of crimes made it futile.\r\nBalamurugan said a number of measures had been introduced to reduce the time taken to process and issue passports to applicants as part of the Centre\'s re-engineering of e-governance.<br /><br /></p>', 'passport.jpg', '2016-04-27 04:33:26'),
(13, 2, 'ECB to experiment with technology behind bitcoin', '<p>The European Central Bank is doing \"experimental work\" with the same ledger technology that underpins virtual currency bitcoin but it needs further research before considering adopting it, an ECB executive board member said on Monday.<br /><br />\nYves Mersch said the ECB would look into whether distributed ledger technology (DLT) -- a shared database that can be used to secure and validate any type of transaction -- could be adopted as the market infrastructure of the euro zone\'s system of central banks.<br /><br />\n\"From a central bank perspective, in the context of our strategic reflections on the future of the Eurosystem’s market infrastructures, we are certainly open to new technologies and, like many market players, have launched some experimental work with DLT,\" Mersch said.<br /><br />\nHe added: \"It is clear that we have a lot of more thinking to do on DLT-related questions and their policy implications.\"<br /><br />\nThe \'blockchain\' technology was first used to support virtual currency bitcoin but has since been tested or even adopted by some brokers and banks for other purposes, such as trading or sharing data.<br /><br />\nBroker ICAP said earlier this year it had become the first to distribute data on trades to customers using this technology and 40 of the world\'s biggest banks, including HSBC and Citi, had also tested a system for trading fixed income based on it.<br /><br />\nA report by the Bank for International Settlements published late last year said this technology could reduce the need for intermediaries such as banks and settlement houses and even pose a \"hypothetical challenge\" to central banks.</p>', 'tech-1.jpg', '2016-04-27 05:09:27'),
(14, 1, 'Apple revenue falls for first time since 2003', '<p><strong>Apple reported a 13% drop in its second quarter revenue on Tuesday as sales of iPhones slipped.</strong><br><br>\r\nThe technology giant reported quarterly sales of $50.56bn (£34.39bn) down from $58bn last year - the first fall in sales for the company since 2003.<br><br>\r\nApple sold 51.2 million iPhones during the quarter, down from 61.2 million in the same quarter of 2015.<br><br>\r\nChina was a particular weak spot - sales there fell 26%. Results were also hit by the impact of a stronger dollar.<br><br>\r\nApple shares fell 8% in after hours trading. Its shares have fallen close to 20% over the last twelve months.<br><br>\r\nApple\'s chief executive Tim Cook said the company performed well \"in the face of strong macroeconomic headwinds\".</p>', 'Iphone_back.jpg', '2016-04-27 05:29:43'),
(15, 2, 'Getty Images Files EU Complaint Against Google Over Search', '<p>US photo agency Getty Images filed a complaint Wednesday with the European Commission accusing Google Inc.\'s web search of hurting its business, opening a new front in the Internet giant\'s anti-competition fight with Brussels.<br><br>\r\nBrussels, which is already investigating Google over alleged anti-competitive practices linked to its Android smartphone operating software and its web search business, said it would look into the unfair competition complaint from Getty Images.<br><br>\r\n\"The commission has received a complaint, which it will assess,\" a European Commission spokeswoman told AFP.<br><br>\r\nIn the latest case, Getty Images accused Google of changing its search functions in 2013 to show galleries of copyrighted photos in high-resolution, large format. Until then, a Google search would only turn up low-resolution thumbnails of the pictures.<br><br>\r\nOnce people had seen the high resolution, large format Getty photo on Google, they no longer had a reason to visit Getty\'s own site to view the image, said a statement by the photo agency, which has been a distribution partner of AFP since 2003.<br><br>\r\n\"These changes have allowed Google to reinforce its role as the Internet\'s dominant search engine, maintaining monopoly over site traffic, engagement data and advertising spend,\" Getty Images general counsel Yoko Miyashita said in the statement.<br><br>\r\n\"This has also promoted piracy, resulting in widespread copyright infringement, turning users into accidental pirates.\"<br><br>\r\nGetty said it had approached Google three years ago with its concerns.<br><br>\r\n\"Google\'s proposed solution was no solution at all: accept its presentation of images in high-res format or opt out of image search. This would mean allowing the harm to continue, or becoming invisible on the Internet,\" it said.<br><br>\r\nGoogle Europe said it would not comment immediately on the case.</p>', 'google.jpg', '2016-04-27 05:38:12'),
(16, 7, 'Thai Facebook group charged over \'foul language\' about draft constitution', '<p>Thailand\'s election commission on Wednesday filed charges against a group for posting \"foul and strong\" comments online criticising a military-backed draft constitution, the first case filed under a law that prohibits campaigning on the charter.<br><br>\r\nGroups on both sides of Thailand\'s political divide have denounced the draft constitution as undemocratic.<br><br>\r\nThe U.N. human rights chief last week urged the junta to curtail \"dangerously sweeping\" powers enshrined in the draft charter and urged the government to \"actively encourage, rather than discourage\" dialogue on the draft.<br><br>\r\nElection commissioner Somchai Srisuthiyakorn filed the charges against members of a Facebook group based in Thailand\'s northeastern province of Khon Kaen.<br><br>\r\n\"They posted comments on Facebook using foul and strong language,\" Somchai told reporters after filing the charges. He did not disclose the group\'s identity.<br><br>\r\n\"We want them to be an example,\" he said. \"From now on, people should talk about the constitution using reason.\"<br><br>\r\nThailand\'s king on Friday approved a law providing a 10-year jail term for those who campaign ahead of an Aug. 7 referendum on the military-backed constitution.<br><br>\r\nEndorsement of legislation by the king, who is a constitutional monarch, is a formality.<br><br>\r\nThe new law criminalises \"forcing or influencing\" a voter to cast or not cast a vote.<br><br>\r\nThe Aug. 7 referendum will be the first time Thais have headed to the polls since the military took power in a May 2014 coup.<br><br>\r\nIn a separate case, ten people, eight from Bangkok and two from Khon Kaen, were detained by the military on Wednesday, junta spokesman Winthai Suvaree told Reuters.<br><br>\r\nThey are suspected of violating the computer crimes law, he said. Winthai said the ten had not been detained over comments about the draft charter.<br><br>\r\n\"They are suspected of breaking the Computer Crimes Act,\" said Winthai. \"We have no details on what they posted as yet.\"<br><br>\r\nOpponents of the military regime, including the Puea Thai Party, have told supporters to vote against the draft charter.<br><br>\r\nJunta chief Prayuth Chan-ocha has said that if the charter is voted down the junta will choose from one the country\'s previous charters, something that could further delay a general election planned for mid-2017.<br><br>\r\nSunai Phasuk, senior researcher in Thailand for Human Rights Watch, told Reuters a \"climate of fear\" was growing in the country ahead of the referendum.<br><br>\r\n\"The junta is mobilising state machinery and everything is being used to promote the draft constitution while people who oppose the draft are being targeted,\" said Sunai.</p>', 'thai-fb.jpg', '2016-04-27 05:43:55');

-- --------------------------------------------------------

--
-- Table structure for table `notice_board`
--

DROP TABLE IF EXISTS `notice_board`;
CREATE TABLE IF NOT EXISTS `notice_board` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `notice_text` text NOT NULL,
  `date_submited` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `society_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_soc_notice` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notice_board`
--

INSERT INTO `notice_board` (`id`, `notice_text`, `date_submited`, `society_id`) VALUES
(1, '<p>Dear Members,</p>\n\n<p>Notice is hereby given to all members that the ANNUAL GENERAL MEETING&nbsp;will be held on 20th April 2016, at Akash Ground 10:00am&nbsp;to conduct the following businesses:</p>\n\n<p><strong><u>A G E N D A</u>&nbsp;</strong></p>\n\n<p>1.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Welcome address by the President.</p>\n\n<p>2.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Confirm and approve minutes of the AGM held on &lt;last AGM&rsquo;s date&gt;.</p>\n\n<p>3.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Receive and adopt annual Report and audited financial statement for the year ending 31.03.&lt;year&gt;.</p>\n\n<p>4.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Consider &amp; approve appointment of Auditors for the year 2010-11.</p>\n\n<p>5.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Following Specific Issues</p>\n\n<p>a.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Issue 1</p>\n\n<p>b.&nbsp;&nbsp;&nbsp;&nbsp; Issue 2</p>\n\n<p>c.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Issue 3</p>\n\n<p>6.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Ratify admission of new Managing committee members (if applicable)</p>\n\n<p>7.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Discuss any other matter with the permission of the chair.</p>\n\n<p>8.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Vote of Thanks.</p>\n\n<p>Please note that:</p>\n\n<p>If there is no quorum till &lt;start time&gt;, the meeting shall be adjourned for another 30 minutes. Following that, the meeting will be held and conducted irrespective of whether there is a quorum or not.</p>\n\n<p>Only members of the society are eligible to attend and participate in the meeting according to the bye-laws of the society. No proxy attendance or voting will be allowed.</p>\n\n<p>&nbsp;</p>\n\n<p>By Order of the Managing Committee<br />\n&lt;Association Name&gt;</p>\n', '2016-04-14 13:35:01', 1),
(2, '<p>To,</p>\n\n<p>&nbsp;&nbsp;&nbsp;&nbsp; All Members of</p>\n\n<p>&nbsp;&nbsp;&nbsp;&nbsp; My Society Co-operative Housing Society Ltd.</p>\n\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Notice is hereby given that the Annual General Meeting of My Society Co-operative Housing Society Ltd. will be held on Sunday 12th August&nbsp; 2012 at&nbsp; 10.30 a.m. in Society premises to transact the following;</p>\n\n<p>&nbsp;</p>\n\n<p><u>AGENDA</u></p>\n\n<ol>\n	<li>To confirm the minutes of last AGM.</li>\n	<li>To receive the Annual report on the working of Society and approve the Income &amp; Expenditure, Balance Sheet &amp; Auditors report for year 2011-12.</li>\n	<li>To appoint Accountant &amp; Auditor for year 2013-14.</li>\n	<li>To admit new members.</li>\n	<li>To discuss and take a decision about repair of safety tank.</li>\n	<li>To discuss and take a decision about opening a new account in another bank.</li>\n	<li>&nbsp;To declare the result of managing committee members election.</li>\n	<li>Any other matter with the permission of the chair.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>\n</ol>\n\n<p>Thanking you,</p>\n\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Secretary&nbsp;</p>\n\n<p>Notes :</p>\n\n<p>&nbsp;&nbsp; 1) It is very important that all members of the Society do attend&nbsp;&nbsp;</p>\n\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; this AGM in order facilitates the working of the Society.</p>\n\n<ol>\n	<li>Only if members are absent, their joint associate member will be permitted to attend the meeting and take part in discussions and vote.</li>\n	<li>If any members have any query about accounts, they may submit the same in writing four days before the AGM.</li>\n	<li>If sufficient quorum is not available meeting will be adjourned &amp; after 15 minutes meeting will be start on same date &amp; same place, that time no sufficient quorum will be required.</li>\n</ol>\n', '2016-04-28 05:23:52', 1),
(5, '<p>This is new notice</p>\n', '2016-05-03 01:13:42', 2),
(32, '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;RIDDHI SIDDHI VRIDDHI</p>\n\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CO-OPERATIVE HSG SOC LTD</p>\n\n<p>&nbsp;</p>\n\n<p>INVITATION</p>\n\n<p>&nbsp;</p>\n\n<h3>The residents of R.S.V. Society are invited to participate in the</h3>\n\n<p>&nbsp;</p>\n\n<h5>FLAG HOISTING CEREMONY</h5>\n\n<p>ON</p>\n\n<p><strong>MONDAY - 15th AUGUST 2016</strong></p>\n\n<p>&nbsp;</p>\n\n<p>At <strong>10.30 AM.</strong></p>\n\n<p><strong><u>The programme would be as follows:</u></strong></p>\n\n<p>&nbsp;</p>\n\n<p><strong>1. FLAG HOISTING</strong> by the Respected Member of the R.S.V. family.</p>\n\n<p><strong>Mr. Jalaluddin Vasani </strong>of Flat No. SC/507.</p>\n\n<p>2. Performances by RSV Children.</p>\n\n<p>&nbsp;</p>\n\n<p>3. Felicitation of students:</p>\n\n<ol>\n	<li>SSC- 80% and above</li>\n	<li>HSC- 75% and above&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</li>\n	<li>BACHELOR&rsquo;s : 70% and above</li>\n</ol>\n\n<p>4. Quiz for All Age Group.</p>\n\n<p>&nbsp;</p>\n\n<p>5. Tol Mol Ke Bol.</p>\n\n<p>&nbsp;</p>\n\n<p>6. Musical Chair (Under 12yrs) &amp; (12yrs &amp; Above).</p>\n\n<p>&nbsp;</p>\n\n<p>7. Housie</p>\n\n<p>&nbsp;</p>\n\n<p>Registration for participation in all the above activities and submission of Mark sheet copies, please do contact the members in the society office on Friday (12th august) and Saturday (13th August) between 8pm-9pm.</p>\n\n<p>&nbsp;</p>\n\n<p><strong>Note: No names will be accepted after Saturday (13th August).</strong></p>\n\n<p>&nbsp;</p>\n\n<p><strong><u>REFRESHMENTS FOR THOSE ATTENDING THE FUNCTION.</u></strong></p>\n\n<h3>&nbsp;</h3>\n\n<h3>&nbsp;</h3>\n\n<h3>&nbsp;</h3>\n\n<p><strong>RAEES KHAN</strong></p>\n\n<h3>SECRETARY&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</h3>\n\n<h3><strong>RIYAZ VASANI</strong></h3>\n\n<p>CHAIRMAN</p>\n\n<div>&nbsp;</div>\n\n<p><strong><u>Managing Committee &amp; Cultural Committee</u></strong></p>\n', '2016-08-12 06:33:46', 3),
(33, '<p>Dear members of Riddhi Siddhi Vriddhi society,</p>\n\n<p>All are requested to follow the instructions given in yesterday&#39;s SMS to download the Society Wizard App on yor mobile and use the Login and Password provided in it.</p>\n\n<p>Thanks</p>\n\n<p>&nbsp;</p>\n', '2016-09-20 06:55:17', 3);

-- --------------------------------------------------------

--
-- Table structure for table `parking_data`
--

DROP TABLE IF EXISTS `parking_data`;
CREATE TABLE IF NOT EXISTS `parking_data` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slot_label` char(30) NOT NULL,
  `no_plate` char(30) NOT NULL,
  `vehicle_model` char(25) NOT NULL,
  `vehicle_type` tinyint(1) UNSIGNED NOT NULL,
  `society_id` int(11) UNSIGNED NOT NULL,
  `flat_id` int(11) UNSIGNED DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_pd_flat` (`flat_id`),
  KEY `fk_p_sid` (`society_id`),
  KEY `fk_vt_id` (`vehicle_type`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `parking_data`
--

INSERT INTO `parking_data` (`id`, `slot_label`, `no_plate`, `vehicle_model`, `vehicle_type`, `society_id`, `flat_id`, `date_added`) VALUES
(1, 'A001', 'MH-01-C-5645', 'Honda City', 2, 1, 1, '2016-05-01 07:59:00'),
(2, 'A002', 'MH-01-V-9898', 'Maruti 800', 2, 1, 2, '2016-05-01 07:59:26'),
(3, 'A003', 'MH-04-VC-7777', 'Hyundai', 2, 1, 3, '2016-05-01 08:00:35'),
(5, 'A010', 'MH-02-AB-1221', 'Honda Activa', 1, 1, 5, '2016-05-01 08:02:24'),
(6, 'A009', 'MH-03-C-0022', '', 2, 1, 12, '2016-05-01 08:03:22'),
(7, 'A011', 'MH-03-F-6758', '', 1, 1, 6, '2016-05-01 08:04:07'),
(8, 'A123', 'MH-03-T-9878', '', 1, 1, 1, '2016-05-04 02:46:19'),
(9, '52', 'MH-02-CJ-8086', 'Toyoto', 2, 3, 172, '2016-08-03 03:03:45'),
(10, '11', 'MH02BY5182', 'i20', 2, 3, 233, '2016-08-03 03:05:44'),
(11, 'Stilt No. 8', 'MH02CH6176', 'Swift', 2, 3, 195, '2016-08-03 03:08:01'),
(12, '10', 'MH02DS3101', 'Ertiga', 2, 3, 198, '2016-08-03 03:10:50'),
(13, '56', 'MH02BJ3161', '', 2, 3, 156, '2016-08-03 03:11:57'),
(14, 'open area', 'MH02BT2925', 'Honda', 1, 3, 198, '2016-08-03 03:12:36'),
(15, 'Stilt No. 5', 'MH-02-CZ-8194', '', 2, 3, 242, '2016-08-03 03:13:29'),
(16, '1', 'MH02CD7149', 'i20', 2, 3, 176, '2016-08-03 03:14:38'),
(17, 'open area', 'MH-03-BG-2577', 'Pulsar 220', 1, 3, 202, '2016-08-03 03:15:12'),
(18, '6', 'MH-02-BM-8615', 'Waganor', 2, 3, 205, '2016-08-03 03:15:58');

-- --------------------------------------------------------

--
-- Table structure for table `sms_storage`
--

DROP TABLE IF EXISTS `sms_storage`;
CREATE TABLE IF NOT EXISTS `sms_storage` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `society_id` int(11) UNSIGNED NOT NULL,
  `notes` char(255) NOT NULL,
  `message` text NOT NULL,
  `total_message` smallint(5) UNSIGNED NOT NULL,
  `numbers` text NOT NULL,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_sms_society` (`society_id`),
  KEY `fk_sms_sender` (`sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sms_storage`
--

INSERT INTO `sms_storage` (`id`, `sender_id`, `society_id`, `notes`, `message`, `total_message`, `numbers`, `date_sent`) VALUES
(1, 1, 1, 'Pending maintenence bill reminder', '', 6, '7897897897,7897897897,5421542154', '2016-04-29 01:20:06'),
(2, 1, 1, 'Pending maintenence bill reminder', '', 14, '9833210927,9833210927,9930815474,9930815474,8879036118,8879036118,9833215446', '2016-04-29 03:11:35'),
(3, 1, 1, 'Pending maintenence bill reminder', '', 14, '9833210927,9833210927,9930815474,9930815474,8879036118,8879036118,9833215446', '2016-04-29 03:23:17'),
(4, 1, 1, 'Pending maintenence bill reminder', '', 2, '7897897897', '2016-04-29 04:04:54'),
(5, 1, 1, 'Pending maintenence bill reminder', '', 2, '9833215446', '2016-05-07 06:24:15'),
(6, 1, 1, 'Pending maintenence bill reminder', '', 6, '9833215446,9833215446,9833215446', '2016-05-09 01:52:07'),
(7, 1, 1, 'Sent From Flats Management Section', 'line 1\nline2\nline  3', 2, '9833210927,9930815474', '2016-05-16 00:22:55'),
(8, 85, 3, 'Sent From Registered Members Section', 'sir call me', 1, '9820453521', '2016-08-01 06:22:03'),
(9, 85, 3, 'Sent From Registered Members Section', 'Please download society wizard mobile app from www.societywizard.com Username and password already provided in the message sent on monday .  Thanks', 2, '9967394604,9820453521', '2016-08-03 06:30:13'),
(10, 85, 3, 'Sent From Registered Members >> Association Members Section', 'Please download  society wizard mobile app from www.societywizard.com Username and password already provided in the massage  sent on monday . Thanks', 8, '9820544555,9820129835,9619635956,9819636929,9920198168,9920270444,9664444556,9820012712', '2016-08-03 06:40:37'),
(11, 85, 3, 'Sent From Registered Members >> Association Members Section', 'SIR PLS CALL', 1, '9820453521', '2016-08-12 01:42:09'),
(12, 85, 3, 'Sent From Registered Members Section', 'call me sir', 1, '9930667724', '2016-08-20 01:55:40'),
(13, 1, 1, 'Sent From Registered Members Section', 'hiiiiiiii...........', 1, '9930815474', '2016-08-20 05:00:10'),
(14, 1, 1, 'Sent From Registered Members Section', 'hiiiiiiii...........', 1, '9930815474', '2016-08-20 05:03:14'),
(15, 1, 1, 'Sent From Registered Members Section', 'hii fofsgvf', 1, '9930815474', '2016-08-20 05:07:43'),
(16, 1, 1, 'Sent From Registered Members Section', 'hii fofsgvf', 1, '9930815474', '2016-08-20 05:12:44'),
(17, 1, 1, 'Sent From Registered Members Section', 'hii fofsgvf', 1, '9930815474', '2016-08-20 05:13:20'),
(18, 1, 1, 'Sent From Registered Members Section', 'hii\nhii\nhii', 1, '9930815474', '2016-08-20 05:16:13'),
(19, 1, 1, 'Sent From Registered Members Section', 'hiiiiiiiiiiiiiiiiiiiiiiiiii', 1, '9930815474', '2016-08-20 05:17:55'),
(20, 1, 1, 'Sent From Registered Members Section', 'sms from production', 1, '9930815474', '2016-08-20 05:26:23');

-- --------------------------------------------------------

--
-- Table structure for table `society_documents`
--

DROP TABLE IF EXISTS `society_documents`;
CREATE TABLE IF NOT EXISTS `society_documents` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_name` char(50) NOT NULL,
  `folder_id` int(11) UNSIGNED NOT NULL,
  `uploaded_by` int(11) UNSIGNED NOT NULL,
  `date_uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_folder` (`folder_id`),
  KEY `fk_folder_added` (`uploaded_by`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `society_documents`
--

INSERT INTO `society_documents` (`id`, `file_name`, `folder_id`, `uploaded_by`, `date_uploaded`) VALUES
(1, 'xlsx.xlsx', 1, 1, '2016-04-19 12:10:52'),
(2, 'cine-_angles_site_details.docx', 1, 1, '2016-04-19 12:11:40'),
(3, 'cine_angles.zip', 1, 1, '2016-04-19 12:12:55'),
(9, 'Model_Bye_Laws_of_Coop_Housing_Society_New_Fla.pdf', 3, 85, '2016-08-02 01:05:49'),
(11, 'Bye-Laws-of-the-Co-Operative-Housing-Societies.pdf', 3, 85, '2016-08-02 01:08:40');

-- --------------------------------------------------------

--
-- Table structure for table `society_enquiry`
--

DROP TABLE IF EXISTS `society_enquiry`;
CREATE TABLE IF NOT EXISTS `society_enquiry` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `society_name` char(50) NOT NULL,
  `contact_person_name` char(50) NOT NULL,
  `contact_person_number` bigint(20) UNSIGNED NOT NULL,
  `date_of_enquiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `society_enquiry`
--

INSERT INTO `society_enquiry` (`id`, `society_name`, `contact_person_name`, `contact_person_number`, `date_of_enquiry`) VALUES
(1, 'New society name', 'Namit Paal', 9857575757, '2016-04-27 07:40:14'),
(2, 'MNO Apartments', 'lakdawala', 9833215446, '2016-04-28 12:35:19'),
(3, 'Alcon Demo Society', 'Aslam Khan', 8888830515, '2016-05-14 05:09:32'),
(4, 'MNO', 'Khalid', 9833215446, '2016-05-14 07:07:47'),
(5, 'ABC', 'Rahul', 7506425060, '2016-07-11 02:15:24'),
(6, 'MNO Apartments', 'Khalid', 9833215446, '2016-09-08 06:24:59'),
(7, 'RSv co hsg soc', 'Yasmeen qureshi', 9867906215, '2016-09-18 22:58:17');

-- --------------------------------------------------------

--
-- Table structure for table `society_main`
--

DROP TABLE IF EXISTS `society_main`;
CREATE TABLE IF NOT EXISTS `society_main` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `society_name` char(255) NOT NULL,
  `society_address` char(255) NOT NULL,
  `society_pincode` char(6) NOT NULL,
  `invoice_note` varchar(1000) NOT NULL,
  `registration_number` char(100) NOT NULL,
  `late_payment_interest` tinyint(1) UNSIGNED NOT NULL DEFAULT '21',
  `monthly_charges` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `subscribed_until` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `society_main`
--

INSERT INTO `society_main` (`id`, `society_name`, `society_address`, `society_pincode`, `invoice_note`, `registration_number`, `late_payment_interest`, `monthly_charges`, `subscribed_until`, `date_added`) VALUES
(1, 'Evershine Society', 'Andheri West, Yari Road, Mumbai - 400061', '400061', '', 'M-365374865834', 15, 1, '2017-07-17 22:30:00', '2016-04-12 13:40:44'),
(2, 'Sameer CHS', 'Borivali West', '400061', '', '', 21, 500, '2016-09-04 13:00:00', '2016-04-18 03:32:32'),
(3, 'Riddhi Siddhi Vriddhi', 'Yari Road, Andheri West, Mumbai 400061.', '400061', 'Pay the dues in time and avoid interest.\nNon default rebate is discontinued from DEC 2015.', 'BOM/K-W/HSG/TC/4364/88-89 (29-03-1989)', 21, 500, '2016-09-04 13:00:00', '2016-05-11 00:41:28'),
(4, 'Alcon Society', 'Goa', '400061', '', '', 21, 500, '2016-09-04 13:00:00', '2016-05-14 08:46:47'),
(5, 'Empyrean Skyline', '', '400061', '', '', 21, 500, '2016-09-04 13:00:00', '2016-07-17 00:46:35'),
(6, 'Aram Nagar Sunshine', 'Yari road, Mumbai - 400061', '400061', '', '', 21, 400, '2016-09-04 13:00:00', '2016-09-08 13:44:22');

-- --------------------------------------------------------

--
-- Table structure for table `society_visitors`
--

DROP TABLE IF EXISTS `society_visitors`;
CREATE TABLE IF NOT EXISTS `society_visitors` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `visitor_name` char(20) NOT NULL,
  `visitor_number` char(15) NOT NULL,
  `visitor_purpose` char(30) NOT NULL,
  `visitor_flat` int(10) UNSIGNED DEFAULT NULL,
  `visitor_image` char(40) NOT NULL,
  `society_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_visitor_added` (`user_id`),
  KEY `fk_visitor_soc` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `society_visitors`
--

INSERT INTO `society_visitors` (`id`, `visitor_name`, `visitor_number`, `visitor_purpose`, `visitor_flat`, `visitor_image`, `society_id`, `user_id`, `created_date`) VALUES
(76, 'loc', '', '', NULL, '', 1, 1, '2016-10-16 12:37:02'),
(77, 'dfgv', '', '', NULL, 'e2eeb1a4153d8f6d6f74083e6a885001.jpg', 1, 1, '2016-10-16 12:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `society_visitors_log`
--

DROP TABLE IF EXISTS `society_visitors_log`;
CREATE TABLE IF NOT EXISTS `society_visitors_log` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `date_of_entry` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `society_visitors_log`
--

INSERT INTO `society_visitors_log` (`id`, `parent_id`, `date_of_entry`) VALUES
(1, 76, NULL),
(2, 77, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcription_history`
--

DROP TABLE IF EXISTS `subcription_history`;
CREATE TABLE IF NOT EXISTS `subcription_history` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `society_id` int(10) UNSIGNED NOT NULL,
  `no_of_months` tinyint(1) UNSIGNED NOT NULL,
  `amount` int(10) UNSIGNED NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_soc_history` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subcription_history`
--

INSERT INTO `subcription_history` (`id`, `society_id`, `no_of_months`, `amount`, `date_added`) VALUES
(1, 1, 3, 1500, '2016-09-11 01:59:14'),
(2, 1, 3, 1, '2016-09-11 02:12:31'),
(3, 1, 6, 6, '2016-09-11 02:21:38'),
(4, 1, 1, 1, '2016-09-19 00:53:45'),
(5, 1, 1, 1, '2016-09-19 00:57:47'),
(6, 1, 2, 2, '2016-09-19 01:08:59'),
(7, 1, 3, 3, '2016-09-19 01:10:51'),
(8, 1, 3, 3, '2016-09-19 01:18:37'),
(9, 1, 3, 3, '2016-09-19 01:22:17'),
(10, 1, 7, 7, '2016-10-08 04:24:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `firstname` char(50) NOT NULL,
  `lastname` char(50) NOT NULL,
  `gender` enum('1','2') NOT NULL DEFAULT '1',
  `email` char(70) DEFAULT NULL,
  `mobile_no` bigint(20) UNSIGNED DEFAULT NULL,
  `password` char(64) NOT NULL,
  `salt` char(32) NOT NULL,
  `token` char(64) NOT NULL,
  `picture` char(50) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `username` char(50) DEFAULT NULL,
  `phone_verified` enum('1','2') NOT NULL DEFAULT '2',
  `email_verified` enum('1','2') NOT NULL DEFAULT '2',
  `phone_privacy` enum('1','2') DEFAULT '1',
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `mobile_no` (`mobile_no`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=308 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `gender`, `email`, `mobile_no`, `password`, `salt`, `token`, `picture`, `date_of_birth`, `username`, `phone_verified`, `email_verified`, `phone_privacy`, `date_register`) VALUES
(1, 'Mukesh', 'Kulkarni', '1', 'socwiz123@gmail.com', 9930815474, '9b5f061f069332703e56e597ecef6f8b0470676df8cab0e215306e48767abe32', '326a1865e973340d369affe6d0328854', '4a948cf2d4c068b33125a4fef67b0b2058c50c9bff7b0c3cdfbd308821ff2b8D', '05_2016/8f77265726c68b18940490de099e7ebf.jpg', '1993-02-26', NULL, '1', '1', '2', '2016-04-12 13:41:16'),
(2, 'Anand', 'Patwardhan', '1', NULL, 8784755545, 'c469f8e8fe4c3418e34130fc4f1a1227c62684b7b1872e96bb3aa6e8f3a3b372', 'e1d88fc3ed84754e7f1fcae4e3d46ac0', '585ec19c948a9591f155600e04dad5ea01798440105c87fefd81dc4ceca54a7f', '', NULL, NULL, '1', '1', '1', '2016-04-14 04:26:51'),
(3, 'Tarvinder Singh', 'Sobti', '1', NULL, 4634367437, 'f2035406c76e0497252e78d44e2d04453b1b2b6ce1aa9ea54347ada7d9b1b3bd', '6e15d3fa3369c3c024977ee95560d59e', '4bce8fd05e47b55ea72e1072b68e4c90a446100815b799d9eaeeaa5c838f0304', '', NULL, NULL, '1', '1', '2', '2016-04-14 04:27:26'),
(4, 'Rustom', 'Banaji', '1', NULL, 5421542154, 'bd5423ed08be75cc6cbda6aba6c694a4a4bd37534bcd34484768f71e871b26a3', '4c2ca14a0f3a8a1bcfc94c37c930ad70', 'c80b40378aa1bd10fd92db5bd4344771d7aa95390107e2ea2c4c762592c38797', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:32:51'),
(5, 'Gulzar', 'Shah', '1', NULL, 1234567890, '9c59dcdf97181b96a83d524ea88874df39b3f7e302920940ffce12ae706a8f3e', '96b85fcbd91807b0d97960202fc851ec', 'f93ddf1787d4001f28218abb29a25d77c84d14887e35e81b6b8061e8df63d004', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:33:40'),
(6, 'Subodh', 'Nadkarni', '1', NULL, 5959595959, 'e859412595a97b9b5429d68788aaa2acd15468a4488c91db6294f47f0beeb9fa', '096fb16fe19ee13813b2d23d82bbaa1a', 'cfa85855e35bfc6829d24bdeaf795a49d907e15b488b7e066b468748f2e93524', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:34:04'),
(7, 'Shashank', 'Patel', '1', NULL, 3030303030, '94483c2256e6e1a6acccb84d10c0cd5930255bb9062907284fa2b02378365915', '00969b8fb193ce5a0f64971309130133', '364d6928b72178aced57bb9612f9cc08e3ce05f931be4347cf26c75a3a79470e', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:35:40'),
(8, 'Venkatesh', 'Raghavan', '1', NULL, 6060606060, '7319416dbf631aee8a832fb3fcca0b013e9ed2f606ff3d1e154a7dbe152f4a16', 'e336d1ab8e080c26c4205c727b8a9c97', '028f9d39f836d711af75edfb40b7fcc4fefec8edd06237d83f6e58bc9d14c9d8', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:36:19'),
(9, 'Anthony', 'Fernandes', '1', NULL, 5050505050, '940180cc2ccfbb718cd25f26097694980e0118d2bdba375023b2197189df237b', '4cf271ecf34a06b03d90f56cd5bd3cc2', '5f5d726e5afdbfa6b948cd58529e41cd2a7354413e95c4810ec952dec3a281c2', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:36:40'),
(10, 'Akhtar', 'Mirza', '1', NULL, 2020202020, '1f44a560038843f7483c4a12cb5f7bc95e94e2c1eb38d000e5192874f28e7f5d', 'e6c4c999698d834499e8f32c356463ff', 'bbaa253d7921f05173ccd6f7485d87f886cdd20fa0b10e203cd66e1db9182449', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:37:19'),
(11, 'Vijay', 'Kalsekar', '1', NULL, 9090909090, 'bb3cc020a9836273957bb07379a45a33704411583ce1014e6861290d71c98f05', 'ed3d2fca9ebe92ac4f998cc10a4f2d88', '5556d008358d7e38e57845bd4f8382f4178ec0208d9608808aa8c2f10eae6368', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:37:43'),
(12, 'gyufufy', 'fvgef', '1', NULL, 4251321321, '157d8de2799f120efd8612cf6c0d598c51c504c59701b302c423c23bba628a9f', 'f88ef3f592db79893103db59303fa2b1', '69bff21b7c88179c0565688c095e94f64b79b178f5d9fce4ff06fd7f71a12284', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:39:06'),
(13, 'fgehrgt', 'fggfg', '1', NULL, 6554676587, 'c62578fb67b9903eb9b27580d6e94825c5feaf951512545b1609b9327f779381', '286f61fb44d805b92335f7116a525f24', '9eedb2e1bb79d5dd966dcd695a988c75206921ac720801e8b2cc89177d47705b', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:40:37'),
(14, 'Atul', 'Godambe', '1', NULL, 4444444444, '574fa6786fdcc95f1b0f31b7d005c044d2fe992ab599304a5c4369bf9d7f2fbb', 'e43a23dc4e30ac7a34a77cccc78125d8', 'd2b8a9edfd23fa22b7736843e26b684f100cb5c2e3103f6e704e57b4f00276ab', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:42:37'),
(15, 'Vijay', 'Gorde', '1', NULL, 6666666666, '32de2ce3eed05eb7db53f96c22f3951e9daa906da82b8266860921a5b81f88e4', '8bf5698eff5f8a8e8d648e938bc56d7d', '37b80b68d930ec476d1d370e91af22c569a476f44140c0f8cbfcece880ca4e02', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:43:32'),
(16, 'Dilip', 'Chouhan', '1', NULL, 4455445545, 'cd1a3401c70b4703a00bf018706cdbb3e8fc82d4f33c2104144ae4996d8d94aa', 'f54a39d01a3bc3e3ff366be18f18f638', '48cb737b791f84b158f57ca43f54b655b7f7cdd7d9e8c992979e1264e078111c', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:44:39'),
(17, 'Siddarth', 'Dutt', '1', NULL, 6565656565, '236f8e4fddef3008b3a19d704c5b506b91a2ca58751c0525a8c6cc042b621f3f', '06580f504f374499252ebdde90c29450', '6aec8848fa0af31769e67ab85325249c222a6776ef5df4ebb967aa6fb1954753', '04_2016/afbcc319b1bfac9a5bab10b3586ad295.jpg', NULL, NULL, '1', '1', '1', '2016-04-14 09:45:01'),
(18, 'Ranjeet', 'Verma', '1', NULL, 9656654646, '806369563a5441523560ef67346c9c1302cafb9e93fd7c8ab462d62fbd3986d3', '7c45d9c21d786ec752944088ede32245', 'b6503da482b1c6f3005b4dc9dd35b3c7d0154da23f94b42833f13a3973706aba', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:45:28'),
(19, 'Thomas D', 'Costa', '1', 'thomas@yahoo.com', 1010101010, 'e666ba135ef269ccd0cf5406bda5fd30be1cc6f2bd46ca7d112ac1b6c038a782', '224948fff7bb10f452f958f412b673c6', 'e97b5d74d226b346b70a1440c4373639b892cb193a7a7644084a1e3d5eb38de9', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:46:05'),
(20, 'Sanjeevan', 'DSouza', '1', NULL, 5555555555, '0a928703aed4c5b4b5e105475889efe5f7818b4f6c744395c387d1394669a327', 'd5e411beb6b17dc670c0cc19bb0b3ac3', '47fa1c368fd272db12a469fbe54df797a04eb88916dea0929c3b56b278bbc510', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:46:28'),
(21, 'Rajiv', 'Dixit', '1', 'rajiv33@gmail.com', 3030303010, '8b7b1f8c41176beb5b632bb0cd84489c33a78b55963f67585905a7f3180bfc9d', '554cd6113e23115cb783c0f49709c1b8', '2322dda63e6b5ed03700eda00d445b5c6132023dcd91dcc4ea64ba7b7ad29c91', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:47:06'),
(22, 'Narendra', 'Tiwari', '1', NULL, 5252525252, '5b03dbf7ca6e04881ed2e1b5c480e153372e4b04aeda831f615abf82c8068f9d', '621d965e8645c3648f8ff979fb709792', 'c9c3de90664176010617f0429995ff09b6cadfedbfe576e0c66e69cb64aaf993', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:47:50'),
(23, 'Vineet', 'Dua', '1', NULL, 8787878787, 'a0dca68184388aea9963b0d66dacbd4bf3f30fc87fa38551d1c8409d08a971e0', '2733175959149d584f788a7b89ac2c7f', 'b32ec6e6259523ae02ad0d35331aa87148e3229a7fcf5f2b1aa780e6fe59bc18', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:48:15'),
(24, 'Shyam', 'Nadkarni', '1', NULL, 5555555550, 'aa73f7fcdd133b0ef8ff871d8904765c0236c813e389312558be33ce6b2807ef', '92693a19a22f51195af53b2e63c252ce', '85cdda2a7ba7416d936486597b11ddf4591e622d57f30436ce197ac61db1fd9c', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:49:31'),
(25, 'Mitesh', 'Mistry', '1', 'mm855@hotmail.com', 9833210920, '236f8e4fddef3008b3a19d704c5b506b91a2ca58751c0525a8c6cc042b621f3f', '06580f504f374499252ebdde90c29450', '7238b8194c963b1d57640b58da8b28c78181ba805a6713a941810d900888bdfb', '04_2016/893825e8639197dbddabfb59b4de957d.jpg', NULL, NULL, '1', '1', '1', '2016-04-14 09:50:17'),
(26, 'Zaid', 'Shaikh', '1', 'zshaikh_88@ymail.in', 8879259694, '236f8e4fddef3008b3a19d704c5b506b91a2ca58751c0525a8c6cc042b621f3f', '06580f504f374499252ebdde90c29450', 'd912ebe70221c8ed04af9d5c079ef8cce0c98517e64603364776fb302059ee9e', '04_2016/143662273d725b36093d5129d27b3082.jpg', NULL, NULL, '1', '1', '1', '2016-04-14 09:51:13'),
(27, 'Priya', 'Sharma', '1', NULL, 4444444440, '934b67c1bfd461a554c7fc23eb7d82451dd4aaf295806f894a66d61718c7e292', 'ba29da1fb69995a22e05be864082eb9b', 'e57c3b620a6b325016541cfd1751406599f58ca30812d251773d6f591ca3934c', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:51:43'),
(28, 'Gurmeet Singh', 'Lamba', '1', NULL, 1111111111, '236f8e4fddef3008b3a19d704c5b506b91a2ca58751c0525a8c6cc042b621f3f', '06580f504f374499252ebdde90c29450', '0a7e61eaa37b4eb3c4da540567851e3744454f657d14b697a36b96e20c2182d7', '04_2016/0bae6bbcb7e0abf6bad8fceb5b2fd313.jpg', NULL, NULL, '1', '1', '1', '2016-04-14 09:51:57'),
(29, 'Rohit', 'Bundela', '1', NULL, 7878787878, 'd03415bc0f0d66856cc40ebee303737e0231dca2cf40fdbe575cd794f9855535', 'fb33d96f4884ea4cababb0e103f5c9df', '6482d835cdec2a42a9026363acdb4983305779aa2dce63e9b42d0c662236bf15', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:53:01'),
(30, 'Rahul', 'Pandey', '1', NULL, 7417417417, 'c14c550795911c3f62acb1b4c6e53bd796d0af50d4c61da8010c88f54cfe155e', '338ff3f5f3d507795d2b796875abbc52', 'c70d3f0cba8ba670881d0f251b43c40cc1a4968a7999d94501216367743e9835', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:53:20'),
(31, 'Sandip', 'Roy', '1', NULL, 2105478456, '2841730ad52844e06795e72b317635066a728172d19b13df856e6f391c4f0c11', '5c5980287083b921ad9987e02417eac7', '693413b1e31d8f8bf415bf056201d4242904a0f82f8a3b6e3a9d9e42f4368dad', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:53:41'),
(32, 'Tarak', 'Mehta', '1', NULL, 2587410365, '917124686a9eb4c8cf8af54f42f67b6e879acb615ad722d9854fcdb87336ce25', '57bd6f1c276c1a66162de2f531246210', '9e62ae8c6b35a5140896ce9a90388321bd735663ea1e905a982c18ab6ac8915e', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:54:06'),
(33, 'Urusa', 'Lakdawala', '1', 'uru_lak@gmail.com', 8585858585, '236f8e4fddef3008b3a19d704c5b506b91a2ca58751c0525a8c6cc042b621f3f', '06580f504f374499252ebdde90c29450', '8c33154718e8bcca421f52f0297f94a5b67162eecc0fb6673d9cfa3a45e38ab6', '04_2016/83dc2e2e2c1d2a123926930f74ba5ab6.jpg', NULL, NULL, '1', '1', '1', '2016-04-14 09:55:12'),
(34, 'Rohit', 'Saxena', '1', NULL, 9892222222, 'b674ae64bd2b0c2c53dcb09dc59158821238f38ecac0e537fb6012ef20cc1e91', '631a055bca41ff757e4ae7abc30a248b', '1953bd2521eb1c9265f906da00d65632934b776639916184bbdeb5f426a40ebf', '', NULL, NULL, '1', '1', '1', '2016-04-14 09:57:44'),
(35, 'Jitendar', 'Patel', '1', NULL, 4564654654, '236f8e4fddef3008b3a19d704c5b506b91a2ca58751c0525a8c6cc042b621f3f', '06580f504f374499252ebdde90c29450', 'c48a9873abd6f6c21118baca8428d2048578e968e5b12ba9c0034fb7c00e92e5', '04_2016/6f14b6e1145c6c6b67ed7936b27ed686.jpg', '1972-06-28', NULL, '1', '1', '1', '2016-04-14 12:45:13'),
(36, 'Vinod', 'Chavan', '1', 'vinod@gmail.com', 5483967943, '1b49b46da29ba46bbbd7460dc12190d907c5b272b556c9d0326ca604d7679e3c', '0c7ef2ddf677f4053bfa486261d20e85', '7168fbf8636658a77c1988ce5c7049b959626df82337d11b5aaa86b466304f2b', '', NULL, NULL, '1', '1', '1', '2016-04-15 00:12:48'),
(40, 'Ketki', 'Pada', '2', 'ketki@gmail.com', 9898989898, '295e8810723e53e31e0a90549511fa971fe76a15e4c060911c729c5e8be8b79a', '6b78799c5b7c42842a51efa564ba37f7', '951ef8ba749a2a46664a1f2b9cdb437286713b4bdbfa9e5d46f5216cacd2613b', '', NULL, NULL, '1', '1', '1', '2016-04-16 03:47:52'),
(41, 'Ajay', 'Rathod', '1', 'ajay_rj@hotmail.com', 9876543211, 'b4724b89f9035f4c32bf61c2b58393d5cb26bb207c10a7a83dd8074f9a3a1543', '75d61e623645d539d4eb08063c7467d4', '3beba5cddaadd0872bc478ee0f492cef2730bf4959a4d276901dbda58f57e761', '', NULL, NULL, '1', '2', '1', '2016-04-18 02:22:13'),
(42, 'Anna', 'Tiwari', '1', 'anna@gmail.com', 1234567899, '4ae8e864908be412ecc78d458e4cd1b66bda4b9230e8146ca97a81a001255e25', 'a4531c85352f1cd2e6ffb8ba68dc770e', '2cc52d8db17371f7f489ce5d21b5aefbe2d5dee49f572f6b1b650d51d1da4626', '', NULL, NULL, '1', '2', '1', '2016-04-18 02:46:48'),
(43, 'Rahil', 'Bijnori', '1', 'rahil_raj11@ymail.com', 1212121212, '40b7239dc7fdf771d42ea8da75acd943871f9672a1f5b1e1439dd6f17d5a9c46', '164390ad3274429727bafd4e8df30b4e', '62706c19630d2016e40f8d6542dfa0da0b116fb44e0b4240eb0d0238670f76cb', '', NULL, NULL, '1', '2', '1', '2016-04-18 02:48:29'),
(44, 'Kailash jha jha', 'Patwardhan', '1', 'K_patwardhan16@gmail.com', 9833215447, 'b45b900fd9514fae7ff0c3147940c698d9364847b32cfc5c76e32c3f4aa31308', '7acb9a25aba9f6c454381f9459072cbc', '7dd20941b796e4f915164ced89378320f5d8ae8e60355bacdff77b35d24fcf93', '04_2016/110a66a8042eacac8680b48f16a1da05.jpg', NULL, NULL, '1', '1', '1', '2016-04-18 05:37:47'),
(45, 'Pappu', 'Thakral', '1', NULL, 4564565776, '7c826dd88540b95522abca832bf2f09252304835361fd1f41e7dc6d7a242b7aa', '2369f9dd2933f37ebf21f7f0b76d2957', '82341c1dea02b5e46664e3ec8d3e8d5975b771ab17417c72fb88f932263c3220', '', NULL, NULL, '1', '1', '1', '2016-04-19 11:01:44'),
(50, 'Lakshman', 'Singh', '1', 'lax_man@yahoo.com', 4568787836, '81ff0a0d0417ec6f4313bf1be1d272e27187276c8c3a0544b763996332edfce6', '37d6faab0b382aa51c60e110f737388d', '4481bb3fba18035f026def2a382d8da60c78f8f2060b9cfdbb59f51da9f553d9', '', NULL, NULL, '1', '2', '1', '2016-04-20 06:30:45'),
(51, 'Jitendra', 'Patel', '1', 'jiten003@gmail.com', 8879036118, 'b29fffb8654e43fe379bfd1092cbc52ed4489bd5b4fa3fe17a4863279b205b0c', '286fe5ce21f72ac550f50a6a841bda4f', '6abcbb03aeb2cc2e38cbdb8e00fc056439007ad2375dbe0c2ebcc0720d65554c', '09_2016/06dee8fb897f141d9f5f230b78e304fc.jpg', NULL, NULL, '1', '2', '1', '2016-04-21 23:05:44'),
(78, 'Khalid', 'Lakdawala', '1', 'Khalid.hl@gmail.com', 9833215446, '5a40bafbe74c2ec136dedd5bd2583ef3a746f5b68145225424f1a3583ebc626e', '461d944f2099b6d0d23bde5de8e0f77f', '280caa26e901f90b6d2ad704aa52874994e350b3c876df054d8ca7fd9e8160af', '04_2016/ac4d7b92730065aaf91fdbc71ad1a909.jpg', '1963-09-05', NULL, '1', '2', '2', '2016-04-27 11:44:04'),
(79, 'saleem', 'sheikh', '1', 'saleem0308@gmail.com', 9920875783, 'c19b2ccddf08a0ce98301e24618f9062a180365e819157a81e98a0f66620c28a', '349f6f18ecef7ffd054f191990aa2254', '5bf49c8839321d920e1c47f05cd1b787663079e588ad420b411ecb0d094f5a2b', '', NULL, NULL, '1', '2', '1', '2016-04-30 00:41:14'),
(80, 'Mahendra', 'Patel', '1', NULL, 8879050810, 'e4f00d0f0d38d70e971b9a7e767d47613ed9bc077e787275e75d3b127d4b646d', '0e976ff92ce1501f8caa0fef78004ea9', '655730fa1e1f557782a911dcb51b630a0607eaf4ed0b8dbd7c9825e5fce78223', '', NULL, NULL, '1', '1', '1', '2016-05-02 23:51:18'),
(81, 'Shweta', 'Manjori', '1', NULL, 9833210911, '1066d1e403b0d2f85ad66b7e6e8dead375cefa592b1ec883cba997bf1ed75acc', '25b5c8325122fb0dafbdd31ba7c86811', 'a3e1e07b9c1fc6f2e968b7ffbff09450d17eae9edbe0c71745c512f5181d0d10', '', NULL, NULL, '1', '1', '1', '2016-05-04 03:01:36'),
(84, 'Misbah', 'Ashraf', '1', 'Misbah.ashraf786@gmail.com', 7042113077, '59c665d450e42f7b749ec0e9e9828a8f8d907f7794fd0810efa4aba4dee9905e', 'a2704905a4161df79075a848323eac0d', 'c23825cff89d4e1a0e79b53a0dcb8830b780440f4e970aa3e3137d8e3d9ab354', '', NULL, NULL, '2', '2', '1', '2016-05-10 12:02:14'),
(85, 'Varsha', 'Pawaskar', '2', 'kcrsv@sw.com', 9867525709, '05c4f4593ebe9b1c6d6d0d51630a56ef0dd35e65898fba21650fa7f4801652ca', 'f90158a45e1fae919ce3a89677346cb1', '3aae751a72738b37a30d2416f6e8e76276c976af1c67e43605a568416a7c4717', '', NULL, NULL, '1', '2', '2', '2016-04-27 11:44:04'),
(86, 'Khalid', 'Lakdawala', '1', NULL, 9833210927, '55f83d3ad1591cce867c088a23bd607a91ed1a42ea3c566b663b10b56784d2a5', '59b1259771be977c6c56d4d76d050b7e', '41262908d34307894adcbe4078168bcb8de6fc4895b7446922269ba06e0f835f', '', NULL, NULL, '1', '1', '1', '2016-05-11 00:59:15'),
(87, 'Aslam', 'Khan', '1', NULL, 8888830515, '33459e36a94a10a6d2b733773d27be1ee015b1e54befb96ca73aec8ed33e3cc9', '3de6e4aa21ad59ed1031385ce6dc5629', '7680f540da6e3f8fcd94b1e618bb398780c89200fe30eae55bdaecfc308fa4a4', '', NULL, NULL, '1', '2', '1', '2016-04-12 13:41:16'),
(88, 'Emp', 'Admin', '1', 'empyrean@sw.com', NULL, 'c94e3deb063c6f2e1a6d54c8f1140cabcd1b14af32775228bcd92d62e756788e', '8e49aa24665b355ab2762cfce30e21cf', '4a948cf2d4c068b33125a4fef67b0b2058c50c9bff7b0c3cdfbd308821ff2b8D', '05_2016/6bf4ed212e25713754c37810b34084d9.jpg', NULL, NULL, '1', '1', '1', '2016-04-12 13:41:16'),
(99, 'Khalid', 'Testing', '1', NULL, 8291379104, '001df2be96f43f060ad5a0c3691ac4f843dc400b19bc65a939b5bb6e787aa80e', 'c1bee941201f2b053a1eac43bc3acf75', '10c6d109b6fd4b81548259348f87ff7b356110d031677714232e40e92fbbb6c0', '08_2016/5e8302160cd24171d686ecea9fd864bd.jpg', NULL, NULL, '1', '1', '1', '2016-08-01 05:29:26'),
(100, 'D P', 'Vishwakarma', '1', 'dpvpost@gmail.com', 9830168201, '075c983949f24ca644aad64ee62f3a33e18157de7cfac914bcd24f539a994a8d', '429a6f22c82964cd61f16298823014bf', 'c19de8539b405f9c856c12ab263877a6f6a9d146b7bc7bf12290df3ba477146f', '', NULL, NULL, '1', '2', '1', '2016-08-06 12:02:19'),
(103, 'Murtuza', 'Nagaria', '1', NULL, 9821005506, '56dcf34c5e95ccb6be03028bed98e17da396883fdf7f7311847f556a047d3eea', 'e49e596791b975eefbb2562642fe2da7', '7894fce0ada5c95f09dd30be0bdc75d218f55477d666f79d58cd53a189d07108', '', NULL, NULL, '1', '1', '1', '2016-08-19 12:42:53'),
(104, 'Rajesh', 'Thakkar', '1', NULL, 9987095075, '7c0d258889905c23e46d187f9b69af5cc23dc6c1efa670f3ec353c7525fdbb90', 'b987b4d4161f57080a2740cfc2e5a72c', 'fce603d94b4c175e63dd4b279bc11a1e555aeb1f993aca921a5c5132e8e74893', '', NULL, NULL, '1', '1', '1', '2016-08-19 12:46:11'),
(105, 'Akhter', 'Lakdawala', '1', NULL, 7738177655, 'ada869cc9f2404e72f02a5f75e3120f17f929c535fd74db1b112c728f218b899', '5177ed80e997d0252c27c1622f2609ec', '7bdb145b4786fc4e021285efc06fd4f4745b825611977f8067d042a56664d6bf', '08_2016/2ddd5e20de954b5b42467f1b9eaf82f2.jpg', '1958-11-10', NULL, '1', '1', '1', '2016-08-19 12:47:20'),
(120, 'gfjhgfj', 'fgjgfkfk', '1', NULL, 7303395461, '0a09ffdda220bc03d86f7216ccea7e94d0808bec6861d452dff66b45d052da28', '858f1072a366760520cd4cd5700a6bab', '11b822ecead7c78a9cdd602a5a97d05bce1f08eee352f9a95a131fffe0822ff3', '', NULL, NULL, '1', '1', '1', '2016-08-20 03:33:37'),
(121, 'Baba', 'Black Sheep', '1', NULL, 7303395462, '7e1673309cbbb44de0e915d99e8784632a5d5b53f7525a3820dcb99ae6eb27a7', 'b038822fb47d5da6d7b3104b366d01d6', '47d924445a4abf6ad1845f2fbac33a8afcee9d2731e14803db7139d42f4cab0d', '', NULL, NULL, '1', '1', '1', '2016-08-20 04:53:11'),
(126, 'Zehra', 'Dawoodji', '1', NULL, 9665018992, '40d4e7aa80f6719dc87c7c2a3640308db1cee9c9c0e7e282e8bdcb905b49efb6', '1b9ad4c20396f2f2b6d5519e9e88a99c', 'c40c8e6f4538d7e552fafe6b650c5cae920da469e2cf8147494373b3da57e83f', '', NULL, NULL, '1', '1', '1', '2016-08-20 05:19:51'),
(127, 'Nadir', 'Balwani', '1', NULL, 9821558883, 'b8fb2d76d05015f8e987491390ecdf025b13e8ac8f4f25c9784c8bcd7a293f43', '7ad54cde0ed083411520392d4678640a', '1725c4eaf888e50bcdeb07c57967cc889e67480febbf2b4c3c96fa519a3398a9', '', NULL, NULL, '1', '1', '1', '2016-08-20 05:21:21'),
(129, 'New user', 'prod', '1', NULL, 7303395465, 'c3f843c69f6d3dd1dc26cb09bade928779db1c0a2daa6c37a4042bd308086525', '3674ae45ad61d0dfadfd28f004aba42a', '8a4ea51ba6d9bf2f2682bd5e9e9ce2656ef00a5923c5a50d61ed332bcdd79ef9', '', NULL, NULL, '1', '1', '1', '2016-08-20 05:28:15'),
(132, 'BABA', 'BLAck', '1', NULL, 7303395466, 'f21e6951cb7fcf01b2d1d781eed6cd990576d29f79c14fee3dc14d01c9d7ae3a', '10897971754a921cbee245d782b3f958', 'cffdfae88c586c728e9d0d71131c457482672a2d27bd5b3901c1afb016569f35', '', NULL, NULL, '1', '1', '1', '2016-08-20 06:09:31'),
(133, 'Atul', 'Godambe', '1', NULL, 9820039336, '17aa1f3790e998d9879a7f14837b52d6a65723313134412f5a0878e1afb113ea', '727ff165fd10c8694b449766d3953aff', '4bc148773f68814ee9e20c754b8a0f8b6a9d95035cf1228bbe6b4f5a603b1429', '', NULL, NULL, '1', '1', '1', '2016-08-20 07:27:56'),
(134, 'Nadir', 'Tejani', '1', NULL, 9820046614, 'de0b5c981980608925edbedba23d378aad873235d6e3f9a4cdfb39e3d5f5df8c', 'f7c690d52740fd65de42b6d13e31f812', '5f16b52fbd426801c710d42cf05b7968222fa2626673bcad78f78507267f24bb', '', NULL, NULL, '1', '1', '1', '2016-08-20 07:30:29'),
(143, 'Narendranath', 'Mukharjee', '1', NULL, 9674054862, 'dbe61c59a2ff052f93fc8cba86ae071c8fa75f42cb9f7cb1500a4f700da15fe0', '96c034f3e0f35286c82236e39573d2d8', '57fad76c050538d9f23bb0ba6728bfb952ec519345d92f5f04fbb4590f791ee0', '', NULL, NULL, '1', '1', '1', '2016-08-21 03:01:29'),
(149, 'Sandeep', 'Roy', '1', 'sandeeproy@hotmail.com', 9820099553, 'c8e7099772478b29bb033d0f9ef320967e381368d197141bf271720f6086c662', 'aadac14430af5eefde23df6d6bd928c7', '42cb9d4e8061b49963c3c2f4dbdcce4c98ffd64fa98f900e1e4baae758713bd0', '', NULL, NULL, '1', '1', '1', '2016-08-27 02:14:56'),
(150, 'Abbu Ji', 'Chacha', '1', NULL, 7303395000, 'e63791487f88dca379152bed1d52abc2e30da0f9f89659c6a1c602d079e160ad', '6f2ba803108cb2dc20ad36caecde7916', 'f423bd0537b613f1d7c896be198a1ab58377f9e2589ee7e8c63efaedbbabd27f', '', NULL, NULL, '1', '1', '1', '2016-08-29 13:23:19'),
(151, 'Asif', 'Tejani', '1', NULL, 9820185926, 'b0d43cf5f8aab4e78e143b7a3e79d1038cb00d452080c68c8bb265bcbe4afcab', '83c98240a480ecfda6fdca1cfe432220', 'f00e02f07cfa0ce101e519a7c55646755989b2860ddfcb851241f4d36c0f0d9f', '', NULL, NULL, '1', '1', '1', '2016-08-30 06:22:39'),
(152, 'Gurmeet Singh', 'Lamba', '1', NULL, 9322226088, 'ee017a5cfa293725ddb480d86482dda3f1c3a5c8134250e128d9b83d8ecac71e', '4cca579b7e636444c7d06f4b23144965', '8424bd59859ebdf00edb5db9f095dfc10706b2ac6a570f6b793105c576c6ae37', '', NULL, NULL, '1', '1', '1', '2016-08-30 07:06:29'),
(154, 'Ranjeet', 'Dutt', '1', NULL, 9819511162, '49843bbf2feefe19fd26d8a6d06241db026966d79df866b839e1cdd876842a00', '752965b0889dfb019121d981ba4b268f', '438e8684f143a4dd2367f5e30f55307026357d90d0cefb470c5967935e714c1d', '', NULL, NULL, '1', '1', '1', '2016-09-04 01:38:21'),
(155, 'Sunshine', 'Admin', '1', 'sunshine@sw.com', NULL, '6d713de7c981fe5c8f793e435c0571b62b7babc5cb930d039fe7d0a410fcea4d', 'bf49869ebb9cc004d6a6b0a1f172e819', '', '', NULL, NULL, '1', '1', '1', '2016-04-12 13:41:16'),
(156, 'ANEES', 'KAZI', '1', NULL, 9820296127, '522234250e3be594960e6f6697d23a39af49aa41333160f61a1b717e6bef5f4d', 'af498f9701ee753452a8e0a9037368df', '1094a8addb0d66b1e147bef234fd658cb5ec0d6432e93df62c042299f0486bf7', '', NULL, NULL, '1', '1', '1', '2016-09-10 01:07:23'),
(157, 'P.C.', 'FERNANDES', '1', NULL, 9820291750, '9c31bf71181069a6c2e6fc055ce89459f35de2ff6c8d1ef6cdd6ee759cc49b2b', '08c00212f07cfd7c052eaa23d403961e', '237ba02c7eea8e3a5ec25698e8711e6d76045cd0923fdbd1d2a5b95ac6d2a470', '', NULL, NULL, '1', '1', '1', '2016-09-10 01:08:06'),
(158, 'ANISH', 'BASU', '1', NULL, 9820184527, '65611eee02606de42ba098bb3fb2baa1cb1667f7af5d143cbddb33d898d01402', '5a49d3fce1dd6387b81915f384f67258', 'bf26293ce5554faf118044fb7abbf4cfe3c7b54af5ae563a5f31affd4d3b53dc', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:04:24'),
(159, 'PARVEZ', 'MUKADAM', '1', NULL, 9323278118, '6eeb01527bd21f8414d2132c12a775245f910bad17d7d4b09274d24f8ff219fc', 'b168476a26c022f21d3808c6a1dabd3d', 'dbb43f7282c80088859b283496e072e961f26b7031573f403c37e8d8fea074cd', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:05:41'),
(160, 'PRASHANT', 'MITTER', '1', NULL, 9821012522, 'f772263d3a604d7c8f4f4c7964485ec1283ad28ad16c06e0f185a469d2cedf91', '2a9d0f7f226b9b106d8187a3c3abb27b', '62886d0be84e1a6b950b5bac605c23c473c3aee0798819caf338b0a199a20dd7', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:13:12'),
(161, 'AFZAL ALTAF', 'CONTRACTOR', '1', NULL, 9821786216, 'd284fce576217c422bba593afbdfff35de76325d0e2421b25de38a913549b16c', '32bc19a5e8c4c60b9e9dcd0aea296a51', '5632fff9c3f2ac33c720162a91c5f06986623bf80c24bd90f6fb4dc4822c1b93', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:13:59'),
(162, 'JAVED', 'MERCHANT', '1', NULL, 9820065948, '02a1501f4f2e0088075ea24f8345c4ee89b0aa9b7ba33b825458aa623fb1a6d6', 'cf8096aac345f1160a220a09aac43e92', '84427f02574a0b763f8e6ed766f9b500f49fce2fc93504bce2949c270c7cc1cb', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:14:39'),
(163, 'DEEPAK', 'SHIVATHAYA', '1', NULL, 9920367281, 'd42954f745c7ff3f6b152cd12d0d29aa25185b32b9abd073210742138956c2c7', 'fa81a3cbb4247968a926823b69ee0512', 'd8f042fc12fae5c3b0c8360a0a92be31512ea8998737fd7522881a88d674d695', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:15:02'),
(164, 'BOLAR ABDUL', 'GHANI', '1', NULL, 9665404150, '3643d2e698dd893e66eb564f1492b53396918689560d0e9dc2250a02a41978f5', 'e671e793c411ee5a002458814836b1b9', '3f9034f84047fb6667a4adf804032e39ee50a2c8127e2cee37b1f22e21277b85', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:16:20'),
(165, 'ANITHA', 'KAIMAL', '1', NULL, 9768073773, '778b79bcc2b236f2ab3f15e22c636bf58cf933f71ff31c703febb4722855317b', 'e63a9c3e39788839c1b4f0f174ed1ef5', '8beb234825d092381001e435ff8574fe44f10699f449aeae40b7b3c0bc436622', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:16:49'),
(166, 'ZAMIRABBAS', 'MISTRY', '1', NULL, 9820930912, '5327b9a171d5608e4e9bda0f89b3382134a8a3567f56da7736b3061c7682aaa2', '020c1398465c2bc4bb41f65d2ba54d7e', '2763b4db44c0738404dbf5e6de0c2ff551e13b2d77d51576bf8ec8a1e7159c62', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:17:17'),
(167, 'ABBASALI', 'VIRANI', '1', NULL, 9702567786, '8755ea642e928a80e874e2444ea0504135bf8d400551534b8a2d1cc74d8d8397', '172d3e6b515895112a5febcb34a0de68', '8e9c0c4bd944d4dfe6175b7c2bba9218ba024fd68b52c2155491d186e1a689f6', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:17:40'),
(168, 'ARSHI', 'SHAIKH', '2', NULL, 9833833014, '1603593905d3b362befc69501f7c0ea9cce540e1d2ec8d1658e6a6c296044d70', '8558952d6bd089ccc302df217365f2e5', '6dd471b7a737efbff9ca9c709ee1694c434b8d76021be9fb387f8d7aae749d47', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:18:15'),
(169, 'ANURAG', 'BHATNAGAR', '1', NULL, 9867504957, '5547f99bb898528ade6713327b303be37e3383ba9be50d429e78744e53aa5e6a', '05deef433e7881fe491d96338260fc3d', 'cf0e1abad2cf36ef140edf4fa717d854418eb4130700e505b524ddcffa07fa98', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:18:40'),
(170, 'GHAZALA KHTUN', 'SYED', '1', NULL, 9920691922, '10d87e06838f9702c61b21a915d76093e0cfbe4ed9ba9564cab8a62d9d8e9b32', '7be8340544f82c1bf8ddaf99ea70cd6d', 'e4f5798f20209f476e8c0504862202a5d5d79deb2dd7b21363ad870827a8babb', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:19:48'),
(171, 'AMAN ASHOK', 'KHUPASARE', '1', NULL, 9819819381, 'e09fe3ad019d1dae959d203ef443bca1e6185660ae1cc87c6fe022c6388b4bce', '2aa5c879514dfef577fd255134d63d47', '6c880796237693ae045ace2abd4d5d4c2fd746e64050921f5594224b7f559b06', '', NULL, NULL, '1', '1', '1', '2016-09-10 02:20:25'),
(172, 'AMITKUMAR', 'SHAW', '1', NULL, 9831094195, 'd6159ad06fd2ebe3ad4b9c23db8031189bf781fc5591dcb1464c5ebb67b61899', 'd7f8007e53d6f4ed1b5cd9d1dcb04c89', 'dca08faa0a930653d4b7e69793e48231f4783e1991a2ebd5e7c67ddaf1debe14', '', '1980-11-09', NULL, '1', '1', '1', '2016-09-10 02:21:08'),
(174, 'Sagheer', 'Hussain', '1', NULL, 9819848315, '09140f4cfe22bd5d3ce8dd160f2612e04c0ff19bf31b38b644a3c7caf2ddbc24', '941a22d58cdee3a8bc82f0c5c5578b60', '87df443549613f179bab844f55bd5127c396083a5a96f7a21e888f998d0a58a4', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:07:14'),
(175, 'Usha P.', 'Thakur', '1', NULL, 9769313225, '8103e199a21e47090ffd6f884f1b64fceeee6074b2d1306075cc7597210b9353', 'd0b3a2b7ed70000d3fe39e146eb986c8', 'eb746cba9d53c1eab9da860c01c1a95d77027e6794296870966a3cb7d49f76b8', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:15:31'),
(176, 'M. H.', 'Bardai', '1', NULL, 9833760022, 'bb3012728432401f12a1beeb97a7bce46fae7cea9a604cf423f81c9059c719a0', '2f6be871e5cb67081ae9d572be7af00f', '2d2f4ecc678c4e32265ff356d2f712b1337242d34b49c5f8fe8a3555273ef150', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:17:52'),
(177, 'Kumar', 'Panjwani', '1', NULL, 9819848276, 'f9e57b9ec6727880122d14f887b7aea152bdc3dcd37b65727d7f7cbaede41051', '4cce59e0f0772124e0d2d9c26183b90f', '45c54e3447f7cf9ed332d1f90c19c5d4caad7539ec2979612403a40ec7d3e9cd', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:20:28'),
(178, 'Manorama P', 'Gupta', '1', NULL, 9324565434, '34cd1458e32b80c89d45b8b1007f86701ad593d59ea972734fb4d801dd9461e3', '7e7241c900dde3a492930a55e34610ca', '34d0de5b79fdc997606e1a8413570f9707a66bdee76ee9866bdc76dca4f909e8', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:22:27'),
(179, 'Sameera M.', 'Mirajkar', '2', NULL, 9221102411, '2ea997ecd151aef4598a87dad174411caffb39a72624a8dfaf8e5c23b0714b92', '19b1db543c1bf744d4d207d87a6a84fd', 'd0defadf87ea7dc72bc665e7bdfd353ad8ce24f7dc2f6dd04619490c175e24fe', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:24:41'),
(180, 'Kumar Shambhu', 'Kunder', '1', NULL, 9892000395, '45b7d209bd59b6db73eadea489c7e42aaae5d48c86c1af551acfdb23298da583', '1d866c3064df58501954305a00921266', 'e1ec2b360ebe2e120f3d0df1b2880256008cfee2c2806d6520cbc4ed0a9454cd', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:26:32'),
(181, 'Shabnam N.', 'Pirani', '1', NULL, 9769879135, 'a33b48f0279b2c405ebaa2807675394fa8c15cfa4111c8442f2b5122beb3cc02', '81dc2a8b7d049b6ffa4bf006a4015c90', 'b545f8230702c69d1db030482d9936563b09f2780fc3c9ab0723bbb380d6117c', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:46:50'),
(182, 'Sohail B.', 'Wazifdar', '1', NULL, 9821985343, '05d25b71daad744dc6577cd170b3d04e06bafb2949fef50529b6566fefd72bba', 'ee4f3d2c7c8eda3a205a93e32b13a85c', 'cefb7bb1f04d1d163d1a9198e71edcf2150e0ceebf4b81e063d113bd37a3287c', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:48:18'),
(183, 'Nazleen D', 'Lakhani', '1', NULL, 9833763950, 'c6f9059474b97cb50a6c0d985ee08a879ca164efba84650a8bc8ec094378fd24', '70a75455b10bf295a8caeaf8db74c890', '9580109906b41c7ded33d1f1c55de9713ff26bcaa1e99dcf8dc6c5f634db7153', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:50:11'),
(184, 'Salim G.', 'Parpia', '1', NULL, 9820271545, '433a7a1b56df4edff577034967c6b5bf11357948977a3394988b278f103afbe5', 'aafd81a50547782dc3ab26d0da4a9016', '6fef5f726680489af32bf1c20def831ef7e6934148d285792d2fcea3f2ff1af0', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:52:31'),
(185, 'N. D', 'Mirchandani', '1', NULL, 9664907330, 'c9e6482b5e72d3a22887114360d6b0edfbe068585c3dec2c3fcb426b8748b15e', 'dc6c5caef906bec7efbd64b923526560', '7c2f881b4566f8627bcb0913b8dd476b155d6839941e603c196f7a0eb17601a4', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:53:54'),
(186, 'Munira Shahbuddin', 'Poonawala', '2', NULL, 9867068414, '9f10b6678fa79b997f2594d2b319acc0140666b98610f9558283c942ddf84aad', '6ecdb0c1731798c1c72fa06c3414f63c', 'f46f71c8373135f795a4d07fa5b7dd292e1554328472b70dd16a39534c5e088e', '', NULL, NULL, '1', '1', '1', '2016-09-18 09:56:05'),
(187, 'Nadir Noorallah', 'Bhalwani', '2', NULL, 9769572272, 'ce3c289cca0a000d99127be8778598bbb28fb8d05685d0a3d4ef852dce4985f0', 'dc51fa41b1201cd26b593ace6512d9ca', 'b76c4a297954a62711919d95a3aa7782044594bc2dbb3fff987e7b7bd74d560e', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:02:20'),
(188, 'Q.M', 'Abusufiyan', '1', NULL, 9867906215, '7fe1dbdbb8271bf72d23788a0e07cdbbfe33ac18305752f6169c217ec40505fb', '49d05b79416ec25cfc42c281fe667316', 'ae4f4b2ac18c3ee3729b38f63433e5774bbd38a9bdc2f07b9842934f8949b7fb', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:03:57'),
(189, 'Shamima N', 'Chapra', '2', NULL, 8879259691, 'c992574f619b7a52f6e53a7f9e839a6b563c3b01ecab5b9fbf9eed76067bd671', '7a5368e56af305eb3a5055ccd823fee5', '9acbc3bf71f0d0431cfa2e43a5c7b644c9783ca71ad2a4b66ff1888cac1c874e', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:09:45'),
(190, 'Rafiq R', 'Gajiyani', '1', NULL, 9920270444, 'd9ba1d55bcf2a9b37c546bc18ffcb3f1c1a29fffabfe4605966f684f20ae63d0', 'a93cb14a3f143dd5f612bab2239c43fa', 'cb7d0d9cd3a8791085d2cc2acc5a401366978007e457a9dd6eb93f6bb2a013f2', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:11:18'),
(191, 'Nilofer', 'Jivan', '2', NULL, 9222440786, '2b98a6581dc44cdd9f99153c147fb2522e0cd32e20eebcd3444b5f83917522f8', '3e6f56f3806e7e1f26c9bfe36f701825', '98ab8cbc496af387d5fddbbf6ee1649cac4cdfe5f7d4ac8dafad5a7a142a12ce', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:13:23'),
(192, 'Sohail A', 'Ainapore', '1', NULL, 8108880511, 'f01511926e2e1f033c776ae4e4e3ae91a5f4c33be7d0d008856cdf7ca66834ad', 'f04bc62c008e4f38a6c5f2316a9331dc', '18554aa0db1cde53479185f89714979f75c5d5a3bef56a7198f8b063f5a67fc6', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:15:08'),
(193, 'Hajira Ahmed', 'Shaikh', '2', NULL, 9867419247, 'bfee53fe6c1ae5e187b5dfc0e306784da38781b80d891f4944b6e2ef6ffffcc1', '78a02b56f9d6a8afe6d7a36566a155e3', 'e432d806df2616e7731c04073f17b2aea4034b01ae0e13bbd1a0e099b1698b61', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:16:37'),
(194, 'Farida S', 'Khoja', '2', NULL, 9920198168, '8bd5407d188ef65350b0627d8990bcb7f34b3a99bcab87c0e8498ce0803ed8a3', '6a4b9ea5a3b48fb3aa132034e8f7c7fc', 'a876b1607633a06733080e1a4b6ea87c99e20b6b0f96e9689b7d9f16360d0fc9', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:18:25'),
(195, 'Mumtaz N', 'Doctor', '2', NULL, 9833687110, 'd3a60beb7c02620796375a2551277346dbc7c5497aecd3b6268845762b788112', 'a2802877517d8dbf9f68e9d712aeb882', '30e85a220dd9bfee4d340a84e611c2d23b6e4d4ab36eb39ed37dcd0e06e10f2d', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:20:33'),
(196, 'Abdul H M', 'Chamdawala', '1', NULL, 9819942240, '763a608fce41373dcc9300d38918dfaf3163672f48f87ea52beadd883cc5e06f', '07cf6da298304eca1563575ddbf5ccaf', '2b03420d7bc32e8f8cafcaf964dd7993077f9087e6dec4f0ea3bd9bd8e508fdd', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:22:12'),
(197, 'Adam U', 'Bandukiya', '1', NULL, 9619552408, 'cbfd34a85bca413746180c3a1a7fec1882ef947b463a0fa3b2b21cdf34cb558e', 'bae857ca7e0853f12bd8677dec8dce90', '008fb9cc5c0983fda0b0156b8aabde3bc0a840e491198941cdf2daa1c16e334f', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:24:15'),
(198, 'Jyoti R', 'Bachwani', '2', NULL, 9769724566, '2d8e5a39580ddf5549b3f09dd79117b0642d886aa42372c7de5a3ed451b5ee82', '0de54ea1df40c13cda844ac8751721d0', '603cb253e9a436c0c7f4e83b227ed93a15d0f6ff73879f5cbba496155a2942c9', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:25:51'),
(199, 'Dilip B', 'Pradhan', '1', NULL, 9819089197, '3d385c92e5e968c5792b1705b1147c4a8655300ecde20e36290509a1cf4838b1', '85f11735edf63c6be418720b329262aa', '92d1cb4b16489cb152a5545127edec5678bd525371a1b2069b920c82b105ee8a', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:27:33'),
(200, 'Bhagwandas', 'Budhrani', '1', NULL, 9987818405, '64813d732af99ee202cbc9e60ee51c531980c3d299c932783d5ca0bf127cd290', '39fc577861e72bd467b161fa8d9452ff', 'a1822c84593495fa77f74f152e080be77b1c2e4fba5701f90440227945338c14', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:29:45'),
(201, 'Savitri B', 'Budhrani', '2', NULL, 8898338696, 'af48b761178fccb861068b9bb3577ae87d6bb2729bd0b1597071c97b8f2da532', '42351db3aecdd707ada40f1afbd4e0d6', 'ae8987cf41706ee6d2fd8bcf61422f7ef25cb277bd228a4f1978c241b4ecf373', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:31:46'),
(202, 'Karim H', 'Kalyani', '1', NULL, 9967004544, 'e17c6aeadd5d65d24e44b26cf535439db87819ec80d3b642f1c93dc1a5e85ee7', '95411369e8a8cb1e43cec44778142418', '0a1896a1aacfe5a711d94246f5ced9b0f520f1013836d183d63e2274921aa6b9', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:33:35'),
(203, 'Feroz Haji', 'Bardai', '1', NULL, 9820781116, 'f1da6a42f95092a24d3669f038fd99dc63894e71d0db99f556fb0f9621b6ede8', '3b0b02f06c7230f5f8bae6cec060acb4', '6264e5794b4a4fd0a0445d0aff3f756bb04c20b5a586a9e3132b0027ac58603e', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:35:15'),
(204, 'Narendranath', 'Mukherjee', '1', NULL, 7095557779, '4cbe010265722460e4cc41c6d29e898f565dafbe9e58514ca72121efdfa69afa', '382f7690aade56ea63dd8ff5fe2e2eef', 'd3f899b9887bafcdaaced51e1553fd60a2ff71a4d771e10926b37514b81add7a', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:36:32'),
(205, 'Ashraf R', 'Virani', '1', NULL, 9892822844, '1b69c676d190042662ce09e7d9eb8af9276d80fd7c7910abd937e308b32135b5', '8aa48c7ff2cefd23f7d5e66f5da8cd41', '9e7dc4cc7ffc1b551b87617824f281665a5d4ba0bf5a1653cfe0906ff66186cd', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:39:40'),
(206, 'Riyaz', 'Vasani', '1', NULL, 9820544555, 'ef0c4cfe0297148df7f6c0934a36e663489c4b5f5bef4e17770273ccf2d6ca22', '838105de75286171daceb5013e4a90bf', '95dcd8682de68fabcac08d06816165dd9083adedd25257591270197f6f2fde8a', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:41:09'),
(207, 'S A', 'Valiyani', '1', NULL, 9819854018, '1f95de92a410a385b71d94a9ff3e1eb913102cec6a171925a0fd28ad33d5275f', 'ffea37a682bcfc106e0d22a8b1cf9f33', '3ec8569e34afa8cf832457cc50a7e959918169117359c4d5c915b5fb63d3e05d', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:42:51'),
(208, 'Nizamullah S', 'Khan', '1', NULL, 9833916126, '0507d27d4e92c284275254af1cff4b47d2d81e447676b2c05fc251f314ca01a2', '74d0b67795a1757f723b25c1c398b858', 'f8a1a90d915b202155c3942671d60e24840ce02170eb828fda043a587f913965', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:44:14'),
(209, 'Shenaz Mohd Aslam', 'Contractor', '2', NULL, 9820039591, 'e32a39e594c02a7a180cef2bc058a103f6b42355e291bc3f1c5b5a0bc0525683', '488ed4275d8c96aec9fff333a0efdeb3', '9411a89907d3cc79769989399a763260076180cc443d47c17663dd52a7380ca5', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:46:18'),
(210, 'Esperanca', 'D\'Souza', '2', NULL, 9819636929, '5f790e10133218eae9b242528a4c94507e24d23050be1701ae7583e99ff03bb8', 'ffa369386d2d5355d39f8572685c7437', '051d69d18af3e53f11f5fe6a28d5f5f83531d37d0d2e0008d9e8c9b94bfb066a', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:54:38'),
(211, 'Daulatbanoo', 'Bootwala', '2', NULL, 9820342147, '4889df5266019216c3b0da38b6a08f5630c0525db516141d822e0edabe024358', 'f3c4198e767f29b37fcd3201214dffcd', '709148b1d5af84612eb0d46baf8249905d2f48d8bc11856d03f43b8a4c0a5ff0', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:56:24'),
(212, 'Shakil Shafi Ahmed', 'Khan', '1', NULL, 9930667724, '58cb51919c6c9963caaabe4f0dad753722953526e4102510081892ff549b7894', 'c207d750046c7b52c1e73403d5866f71', '25519f3ad93d9294be88aa073183def3812b53f666eae950fa42ae08da314277', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:57:42'),
(213, 'Simran', 'Deep', '2', NULL, 9920824463, '334fb470802d9bcf0c0bb706c12dc1f02769f4b241b7a8b25abb55b3f8574b45', '53cb737d20ac666bd736cce687a592f9', 'd3e2c4f36edc924295c41a1dfd730dd6dd39337699bc6e63610fe1bea814aec3', '', NULL, NULL, '1', '1', '1', '2016-09-18 10:59:46'),
(214, 'Zarina A M', 'Shaikh', '2', NULL, 9167600263, '392bf665ea4cbf57bf5d9a79a557e9a82b65c96bd8e12de9b305c9f53879a32f', '88583f61f026757bf787646037274a3a', '73a0435b0626aea0c63f69b6db23f7c8537cbf86b6e4a78cd222c0ecb69e7592', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:01:13'),
(215, 'Mohsinali', 'Munji', '1', NULL, 9867976409, '3b15b5b81af8d0cdf9b00a67558df16a4ee5d0978d2428a2caa35ea61f31e45a', '6b9f4573352a6a4dae988640f140e9e6', '827868ab331fada3f0258aa622f76420bc8719046b4b7515f1ab6445a035f50f', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:03:05'),
(216, 'Rafique', 'Makker', '1', NULL, 9833555144, 'ad62a71396f786e6c0db9b70af3788f459b29b207f5508eab44376d3a61da3fd', 'c33adde5e93087ad0c99bafb78e89b37', '8b86bf1537e14fafb10805a4797993fe1ef3e2ea0007cb489bcb990f30c8acf0', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:04:53'),
(217, 'Azhar A', 'Ginwala', '1', NULL, 9819344218, '2a0dec49f8608a08f33782e15c0fed65421ac6195369d82c663d0c8e3ade70af', '25e2dd7c3db517d85bd7e9459f3ce900', 'ac1329fd3297e3ba3583561f1ff251a6ba4a088dcd8a865c81a5e166886b29ef', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:06:13'),
(218, 'Rameshkumar G', 'Panicker', '1', NULL, 9820024817, 'f2106ffc8646cc291196d9c140ddef0c7ab1ec680e3c53a1a2a5145a275285de', '4f1848bb33a372a7af6ce062fcda6725', '87c8e83cda9f25f621fb9deead5cd6e061ab9ffe26102ae3860094fdacddf612', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:08:00'),
(219, 'Prem Kumar C', 'Chopra', '1', NULL, 9323201549, '398595e3353a4be174ed15f9791edfdf279303487d449b7278933f9c6fdd94b2', 'a21aa8c60a842005fc6be24d4e8c59de', 'e9810324af69cd665efa1950188bf06880400366b3aba82172f390f420e2f314', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:09:42'),
(220, 'Riyaz', 'Kerawala', '1', NULL, 9930816241, 'cf9c36ad5bc9d2f83248439094b0b282ce8834d642c718adf1dab4bee883c3a6', '92f99070b20ee0582e8281bcee2c0c24', '717ae5f5614b61661f1780cd9318488003e79bd6ae8333583e0e50ffb99a0f27', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:30:18'),
(221, 'Masuma R', 'Kerawala', '2', NULL, 9892716492, '490d83aed181f2f87db8761b33fcfbbff686ecd52d3ed6c5e9979aa4cd7b2872', 'bbb965c81b31222cd3f0eaabd03ccd59', '749eca9ae66189f94ea5f74c1f3be13ee10cf8575a982d8807008d04e43b13de', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:32:16'),
(222, 'S S A', 'Coudhary', '1', NULL, 9820088558, '5b9018648ac511c8be82c43fed4cf3b53dbb67e2c9fafcfdbcecb3b2d268e963', '0e25e86d6bdc75cb81efca384e5173e3', 'b8cb73fe6ab52473bf1d24c25f6f20eaee6baf979b94e0fcca3d48e3d0b1431c', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:34:10'),
(223, 'Isidore H', 'D\'Souza', '1', NULL, 9617053642, '6c5ea1bed149403d086921d93e8838653c2df9b765059578895dd73b607c7f2d', '537e9c5fa3f7ee39bc0b730fbc157796', '98b893f889cebabe5fd2c234a983140508ff2c419b70aca325ad74bc33708784', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:35:49'),
(224, 'Khairunisa M H', 'Devji', '2', NULL, 9223466512, '7895f1b3f91b47b1836a19f33802ee16c6436d71decee611f45960c906fafae6', 'a1293639871100cb33d9b648f57aa48a', '448e547d5daf9020cb7729c51883f0e2e8b4cacaa85369fb215ec841abb1fab4', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:37:54'),
(225, 'E D', 'Moorthy', '2', NULL, 9820285645, '564e8a65f37d3d507ec829202645b317b1abe28481200cb776655953312db3c6', '5b6d2d3b74c9fb1d7393cbdab4150dcc', '6fc3cd8951ac99bd62d3351ef2d047b92ed84df5818a3ea940ac8d2c05ad97bb', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:39:53'),
(226, 'Izzat H H', 'Asgar', '1', NULL, 9619472766, '324eaaf24364c19676c7dbc40b3e0d5d12b0fee7258c854fb18557f18676af62', '7ebf005a0516bdf7323b2ccf6f910002', '99b906f9548d0324ed8da44e3fd77b409de39f119e456b6c1cdc7afb8f59881a', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:41:42'),
(227, 'Aziz A Y', 'Chatriwala', '1', NULL, 9819949352, 'e7bd11241e03bba5c3e7e7332dd50881b217fb81e7c6f31fcce864be5a8bfd3a', '943d6bd6309b1657ac37f9fe2c705f3c', '6a640c18b462a6346a287a2a73300cf442bbc4136e82c5b86d8a20ccc2e0893f', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:43:22'),
(228, 'T Selva Kumar', 'Mudaliar', '1', NULL, 9619635956, 'd6d72c8123d5a65fa217d38f0c9fb48e34513f1ee68489d7b84553aa8934af47', '5b53df52530f650d2c7d9acda3ea48fb', '01910e864bbb3f3405ba50ca0700e84be8f380296107ccb6795276d065a62013', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:45:54'),
(229, 'Bakhtawar S', 'Dharani', '1', NULL, 9833832450, 'cbe81384c97c4f8ca686f4dac98d00a1c6c826a691ace95c5b63459e3743c0ac', '7f717e6f097d03433e2f1bf2ee63fc4d', '46b510ce567a97939ce4a531c65c271c2dfa3d9d6718e1abe4bb0c142f02a810', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:48:12'),
(230, 'Jagdish Singh', 'Raikar', '1', NULL, 9820007765, '1f819f306e352725e6851422ce3b77323796d16b098a2b4f97c4e859e9bd05d5', '5215ef1ff602b8dfa916f578f12a4a08', '464e9594b6b7c26595b0f514bc215b6c8a8fd843552629c8493dff4742724289', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:49:58'),
(231, 'Riyaz', 'Dandawala', '1', NULL, 9820453521, 'cdaed16829137f279fc24af7cb3379132781af4308abc8f6f6a0dd3f3e248b7e', '0889e7f411c7b6c31d1146752a53582a', '59487b0bb266aa6fa987cfaedfa96ac947f44419a0c393c26ad9e4f356967560', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:57:28'),
(232, 'Bilquis A', 'Kapadia', '1', NULL, 9820846122, 'a4c784fc0d8ca9929d0ad0b0a5ea74f08d2205759190b73ca97870ea13f085fd', 'ae4f10307f0c401e263338b6afe47cb4', '228cc47472e5b5e5dc95dd8c9a602de5fe478ad4a30bca522781ab670e50f24d', '', NULL, NULL, '1', '1', '1', '2016-09-18 11:59:28'),
(233, 'Gayatri Arun', 'Walanj', '2', NULL, 9320479559, 'c3b78eda788f43cf8110bbd2194879263db8e3b52c0a31c66f0b35e97b28e639', '8b2ddf66e5a2848c641ffb3fe35024bc', 'fcf0e847eb8566e7a4cd73a5a1e41c3408823ce3acfd041c3f2e198da75a5c67', '', NULL, NULL, '1', '1', '1', '2016-09-19 04:50:21'),
(234, 'Sunil', 'Mayekar', '1', NULL, 9920401326, 'a73176d84dab01c5d069c1040c22ce330912d83918db4cb3d7e1ef4692893d9f', '6068e6dee347d639e4622b5b7f6ab150', 'adb1c45fc0477402ebb32348927ec6fa54af09952de9c554e9ad0ff6fc78ca5a', '', NULL, NULL, '1', '1', '1', '2016-09-19 04:52:31'),
(235, 'Khairunnissa A R', 'Belim', '2', NULL, 9930824269, 'e42bb599732b38b6230deb17d6b0b37f73cb81cab56eccdecc5a6e0edbc18197', 'c2488d02d996b58fc3f66a94bfefebb1', '26598fd82dad720a1c5132d115da4fc2c6f002648e158875f60b357dddc3de7d', '', NULL, NULL, '1', '1', '1', '2016-09-19 04:54:48'),
(236, 'S K', 'Kerosenewala', '1', NULL, 9870816186, 'eb6b4f46eb6f6daeac2db665544b81ee812930ba9aa525f87c22b35cf18fbf14', '11e666fb68809dc8ac5a840291e8cd1c', '9288ba602b85af9c3fe60c4672b5d205c284ca3ca150dec8927f30c8c564f1e0', '', NULL, NULL, '1', '1', '1', '2016-09-19 04:56:26'),
(237, 'Arfina', 'Khan', '2', NULL, 9821320093, 'f1722fd37e5911a580aa9c44e12e8ecaec7c61e0a66e59b720d0b7186abcd963', '331a433ff3ed6a4018d94cc5915dce74', 'e2323e7ad667b18d9623b682953d449014a77daf02fc286c7b9f6055c242a3f0', '', NULL, NULL, '1', '1', '1', '2016-09-19 04:57:44'),
(238, 'Dilip Kumar M', 'Pradhan', '1', NULL, 9967394604, '3c69fcb0995d6480d9112babce5fc5049c966b12315056698664992a2b064078', '2455219b18be27e07a6ea7592b291886', '9f059e776e083049e45ba1bc3ac48d9c40212a7c6fb315deb4185428513e432f', '', NULL, NULL, '1', '1', '1', '2016-09-19 04:59:54'),
(239, 'Noorjehan J', 'Vasani', '2', NULL, 9619472768, 'ffac24cc04730450eb49ae388afcc617bd1d2f42f5046fe315de303b2ef8b805', '18999589bcd101c66795deff5db1059a', '3cfe9bd0fb4655b5f85017d809bfe0da31c1a23202c003c30570fce6aece87e2', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:01:50'),
(240, 'Riyaz', 'Merchant', '1', NULL, 9869673942, '64068953b1388af6b0e240d1fbd15ce1fca7507f8e9517a8ecab2008ec0f2606', '92f916dcd4104791447379a9f8a5ec32', '462ac877936211198f36c830a4b9e0b1f1075af35259ac80f1c95b12f745f907', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:03:20'),
(241, 'Mahmood', 'khan', '1', NULL, 9320780625, 'bec65608fe9a2ae695cfdf02ca58594f0d4392b14823f2c0a9045596b42dc763', '9d8a948dfdcf639a5d1907b30f3afb46', '175e5c93018d04a3832ff8fa853a28a0dae6f075998235ee09ca8e8c5e9bc602', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:05:08'),
(242, 'Amina', 'Fidai', '2', NULL, 9819173268, '79fa81462c78657c059eec66275f57ad24cfcbe9da37a590dfa141c31ef2aa41', '54d002bbedabefb5c5066724742d89cb', '6757ab015a6e382817a3e0719a07026ae13dd23e1a9228d771b0b35633301a0d', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:08:04'),
(243, 'Shahin Arif', 'Gazali', '2', NULL, 9819089210, '5abb4677f3c5465928e90e258078222a8896bb192ac6dd726fcc1d84d73bcb9a', '6623952cf64546790ffacaa58a75f7ee', 'ef47d2a6420b85a74d3f9807bfa1feca8fce441e2cfde54f10b461ebc97e9770', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:11:34'),
(244, 'Mohammad', 'Khalid Anjum', '1', NULL, 9920012294, '5b55fd2c0b3a6f66d5c0b74595162f9bea51c614cbba1f1bd3ec16ac76647dc1', '9bd295c84d30013e0908b1839e5fa2e8', '179e65844b67404e8301f3884a6def763189887c44fd1237284b5a49903e56a7', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:13:16'),
(245, 'Pradeep M', 'Bidri', '1', NULL, 9821215154, '1e11b36a9c575b857ae748133fe13e0fa640f8fab1ee40ef8f714ede1cc05d3d', 'b0679dcccef165898115ef2440bb9253', '72dd3010750fee3e409fbea4731826d68636d917d467ff2994483ce4ec587f51', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:14:51'),
(246, 'Fatema Shaukat', 'Dodhiya', '2', NULL, 9167933559, '23b1eadb09561ec5acf498fc268bc6f84b72e3086ca1157fee4a12d2fb52d543', 'e06d5ca18286ac78249834c560067bb2', '25533d28b07d55dbf6acba56ee8b52c0d1649af422d641bf8c676a60b9d414b4', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:16:17'),
(247, 'K K', 'khakoo', '1', NULL, 9819379898, '7a8375a75fe13929a80d9b67cc3d030680ab228a966b1f61ca25195948dac5e8', 'c34d66ca37f2f86ee0a259b4008428c7', 'c5f1a261380ebd693820d8568374f4dce8903e2ba5dd110bfcfe4e2cbe094771', '09_2016/789546e55840a1efade89bd00070fc95.jpg', NULL, NULL, '1', '1', '1', '2016-09-19 05:18:22'),
(248, 'Zubeda R', 'Lalani', '2', NULL, 9819598538, '6a88bcd39ee71c9987f85fbbd6e83239395b359d0cc34e749e05d1bdfb8445b0', 'c146c956ccbae43d1bec2ad1fa052d0c', '29e420b05136fe99c120cbe9a66e0721bd5488ac4e3a10a6218586634ff4e8d4', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:20:44'),
(249, 'Gulam Hussain K', 'Dinani', '1', NULL, 9967704800, '7153c23d7a79abe15f1e4c3d9fb78999b00821b27200cb50b6cfa601a40f48cf', '2f259d97a04559bca2b1609c4bb16fd7', 'd9d858401f892c172c8b8e97c16f000da09fa60ec688284776b7980d001bf652', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:22:16'),
(250, 'Ruksana N A', 'Khan', '2', NULL, 9821858322, 'abeecbb6dfb177972edb3da7e3d04ec9014c3a5ba413e8def6c5c672a48766ad', 'ebc85d1af4852324b5725c5b3e41ad0b', 'a9c21beb4d25d65bed898b5cd3609eaaadd1cf85d28b5044f12d439f0f35345f', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:24:02'),
(251, 'Abdul R H', 'Malim', '1', NULL, 9769385531, '9781db15d7d7cf6c9ef3fbd3bd5ad07d6c2ae264239d77d9e16eee76378bbb22', 'e6b55bb64dd208c5b15c251db8589c3f', 'f2c7f28ae2e319abf1252d7fe0cbfe66620d395c5f04a93c51b85fbe888d07e6', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:26:07'),
(252, 'B S', 'Dharania', '1', NULL, 9819100274, 'fa674070cf5cbb410077b94534058c840b089b9725e0f93aaea08ff61328bb65', 'c43fb814c77859b177d3180335911141', '99df8c63f3d788abb94414e2ddd75801e491486282d29acec542fb75f76c224b', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:27:36'),
(253, 'Nazim N  & Nizar', 'Furniturewala', '1', NULL, 9819182946, '19040738e63adcdad2503445b24694e495ecab863cbb37452e3bcf3b8707bf8e', 'c56e07f77585fc2c4271014947abbd96', '0e93420b7e22d805af16dd8a4bfe8156c0ee661400f46cc6ffb235ef0cdfd3ad', '', NULL, NULL, '1', '1', '1', '2016-09-19 05:29:36'),
(254, 'S H', 'Musanna', '1', NULL, 9870417195, 'c057fd06ff00f9f7614c1a32ef9603ed658ce6c0648b4209c2645180b69136d3', 'f6b42b0ce1a555a0f8d1d28950569b4d', '5aeb6271922a715d58dea9da09b1e2805e9c5d31e2c03a2ba4761107a380c654', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:25:11'),
(255, 'Sulema S', 'Samani', '1', NULL, 7303377777, '9056ee6c3de2a6429322ccf92d00a2f5480fecc170865609cff85f60d1f9ea0f', '53c465ab1b501fc4fa8a04b8e989a7ef', '6709b6684b7ba977a5e70e5db46123b7b71478e453b9e78d6899ba6adf014b85', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:29:09'),
(256, 'Zulfikar C', 'Tejani', '1', NULL, 9892927775, 'fc33206939ed2ab454d9e25a2edf259d3306016e2cee766156927dc0647076eb', 'b96f107ddffe8ed8727922f3906a9a5e', 'a0b4761b338dff0926ec31c035c8ce3100e8954ac102526b638e565043ccfc96', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:30:59'),
(257, 'Payarali Pirbhai', 'Niyani', '1', NULL, 9833214266, 'f93adf3f785049cc8606894a49ec138cb8b70fb5c6b87b315735a054b4e00a8a', '3cf75e12d8b979cbae138096aa404714', '481bb4777e3b8f2731d9e98cfda2b9c807f4aa1edbbdf117412aee1292d67008', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:32:55'),
(258, 'Shahid P', 'Khan', '1', NULL, 9869352493, 'd6583ebc1ddbba5f75e0fa3dae05a04a609f77499e47eee04a644ad1224f40e3', '3d93e90894fbe09ed215bb5869e77683', '50e2aeeb0058d9c57fd67f107b66b02221e6626dd6aa6cea9e14a2852d0e75e4', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:37:55'),
(259, 'Santosh Motilal Valecha', 'Valecha', '1', NULL, 9810246329, '29f5906aeab97d4554ccd59c09e896482c0d7957b921de3fa7d6ae981062b7a9', '09b1b79be5c77f683ad823e138669ea0', '0abb40f0b623effaa9d4df1823f75d41b65f8ed917a8c5f17c48d16b15fd862b', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:39:22');
INSERT INTO `users` (`id`, `firstname`, `lastname`, `gender`, `email`, `mobile_no`, `password`, `salt`, `token`, `picture`, `date_of_birth`, `username`, `phone_verified`, `email_verified`, `phone_privacy`, `date_register`) VALUES
(260, 'Shirin J Sutarwala', 'Sutarwala', '2', NULL, 9004843095, '3fca9d4e4696484308f3bdc2e97192ef328172a285c80eb498e29b34a0a8f82f', 'd1a5bd9fe219e521f67fc658d27b5202', '342f3db928baee10f50db4eeab3c1b57e5315c55bec8e04dcf9a8f31c28bc01f', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:41:10'),
(261, 'Cynthia', 'Dar', '2', NULL, 9867281252, '92f44632e5510b7db726d882f813bb3b09399592eee888b6d1fa1a88bb842ea6', '749a4c29d8ab05bc647a5a46a5121ff7', '1c521b9aaaaa02a78dbadfebebe880f02236eddba51f6c28757e6420dbf9bdd4', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:42:30'),
(262, 'A G', 'Roy', '1', NULL, 9967556045, '88890c9cef15385ccd9bef44526cbcc0a3e10847fde2d90f0012290cb02247e6', 'fa3107261f008b6dfa0fec6b319f3c2b', 'c7b41f66ee597ae91698f27762202149f2a073bc4a0cf859a5a49fec509c4189', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:43:41'),
(263, 'Lachman K', 'Bhatia', '1', NULL, 9987202009, 'aa298095e69bd7125b47162060f21168ba0ff1256d78d4036b779a7279b8fb73', '0d98bc862cd31a2efec53ae6ecef5cfe', '5241bfc165dcfdb9f3d56c99ab538e709492614e5156348c26e3e38966a0a6a3', '09_2016/f5cfc233cb7690efa81772a932ecad42.jpg', NULL, NULL, '1', '1', '2', '2016-09-19 07:46:25'),
(264, 'Afzal & Sajan', 'Sajid', '1', NULL, 9323232287, '7f3a92f35c6c2ab7ee39ebc9691a215a07939af526fe1de8d0e8595adf2207ae', '252b902d9fce00a36c26ed569d30a9a7', 'ec34c99e4613d43bce8432ed7d0b50b1b50b1d94d59a7101ccffb3b8f47a625a', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:53:42'),
(265, 'Parvez U Mhaldar', 'Mhaldar', '1', NULL, 9819529528, '94c392380a25b49c11c721785b5426d94ebd0b862d1d18e1d942608f402bcc9b', 'e1395631b80408a1b775d2eac2012a5a', 'e331c16e2fd6adbf7d84f7dfd80a0eacd7f2ed2e0adcbd9ec4bea42cdc49d0df', '', NULL, NULL, '1', '1', '1', '2016-09-19 07:55:02'),
(266, 'Raess J A', 'Khan', '1', NULL, 9820129835, 'd9a2cc4cb950f3dea8c082d80a0d8a265fc73d45fa0ace3f02f4b7b8e897bc95', '6cd94a20a213f989f3858e7c375f89c1', 'f13fe2eafd5a2624d2b9a787b88d5cd1a9e50fced7977eeb7401c39ae0806b26', '', NULL, NULL, '1', '1', '1', '2016-09-19 08:03:17'),
(267, 'Dilip & Kalyani', 'Patra', '1', NULL, 9820103003, '2f31e40b0931ab5c0f467a18da5f77ba0e5805d53ad51982b3f9ccdb579bb6e5', '58e9e2e3e103cd53893fdf4f02809064', '4b6f92d80cdc88357111ff7c63b6e73e86cf154a1cd1cdc5645d170e0fc4ad17', '', NULL, NULL, '1', '1', '1', '2016-09-19 08:10:12'),
(268, 'Nizarali H', 'Bhanvadia', '1', NULL, 9004344300, '97252a9b73436566d157536add4e5e0ee4adabc464fd8df599a13ab66e7b1ac0', '7febc4eca40945e2db23bd76b1332a2d', '1479e994e0598ea96ccedc6dca01089ce38a894e763dd53a435e892eff71ba32', '', NULL, NULL, '1', '1', '1', '2016-09-19 08:13:01'),
(269, 'Shashank', 'Solanki', '1', NULL, 9821234636, '555e93078a2e45cc2cd294fa6c6922836f5fc4b3baf95b19faa6edcd4eba5583', '13e0cd622df92976bd0e5d35d82f82b5', 'b73062af8c199a4668b13e9435703841871718a072eb4b480b4a419d035f6d8c', '', NULL, NULL, '1', '1', '1', '2016-09-19 08:14:43'),
(270, 'Irfan H', 'Patel', '1', NULL, 9821718280, '493409a188d74f77d731c40c28edd2e35fe1a05a229af1f387edd398b75e2011', '2a805911c0fc63b3f2c64a340713bf2d', '6d44901f8ec98ebe142f3b139a4bacbc9fa8ebda43f63021d4ef23ae629c1b3f', '', NULL, NULL, '1', '1', '1', '2016-09-19 08:50:05'),
(271, 'Imran Salauddin', 'Khalifa', '1', NULL, 9930899035, '3805e0edc406842e24856fdb3b076b2f1d7ede9d94374eb7259961df7c2e90d3', '00b116b9a5bf6f752aedd9e0a4345be6', '505a1449276ff487780d1896c797c38248068100de72df3dedfb4bc7819b6086', '', NULL, NULL, '1', '1', '1', '2016-09-19 08:51:37'),
(272, 'Ahmed', 'Batliwala', '1', NULL, 9820012712, '5911cb6ee94f5656781139d36e8ad38ace738bbe187bbeafd8a9078af6ede7d9', 'c2700c4926aa324054b4e4f9b3810232', 'ab79b97b870d21f6692f14791f8a849587875fad41d1c472dee6ffd81af151a0', '', NULL, NULL, '1', '1', '1', '2016-09-19 08:52:52'),
(273, 'Mehboob A Sutaria & others', 'Sutaria & others', '1', NULL, 9820776768, '5c9617667bf52910c56db891d02bec4468087d8bf2fbd8d1b2344c8b28369206', '76044fecb6fdfc2132731d3f5901286e', '466eb883532dbdfb93604d9855666696c113a7dab11ac0ecb834ec74d374f0e0', '', NULL, NULL, '1', '1', '1', '2016-09-19 08:55:16'),
(274, 'NIDA FAZALI M.', 'HUSSAIN', '2', NULL, 8425933990, '41b6135a8d238edc131690bcd85c0bd47d2fff36820eb9ab83e8266338e438b5', '2de47335a79a94d5b791c60c317d1a24', '3dd64d48011fce3318508066fd2d39f9fc2a2b7c8a3ab93f52ac2c08ba28fb32', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:21:14'),
(275, 'KARISHMA', 'HASIJA', '2', NULL, 9820931535, '61cf30b72f9a89603ff5d032901c56c477a07c13015145853119c85960f8912d', '85728f4d0b9a4c872fb98f827471da58', 'b41a7f9f30e4817c5196d352254fd33c259630f0ae35494c83a2a35f956bacce', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:23:31'),
(276, 'SUBHASH R.', 'DURAGKAR', '1', NULL, 9322009265, 'c8414f435efeb0ba62b0d5383c21016e2daa1c0cede38df275d49d6b86355f8d', 'aa4f09b5bec5fbf70ff8a781212cb30c', '0ca3c8f408e13c6ce7eaa9acc23582f594f686d0f66cf41c6a30feda96894e2f', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:24:43'),
(277, 'HASSAN H.  & SHEHNAZ H', 'KESHWANI', '1', NULL, 9821541382, 'e93be3f1604d313105bfefaeda5639c592587c171d023409af5d839d049f043a', '1a9beaa44079095780956e16e83c779a', '2f77e13d41e7cc794803536746f1239ecef47bde41b920008190687f15a5a77d', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:26:33'),
(278, 'VIKRAM JIT SINGH', 'SINGH', '1', NULL, 9969313637, 'fe2335fcfed4bb6d66cdbfb1d38945b957aed214745893ecfc23043dc4d4a287', '8713928068e947e878c36532b43e2450', '413317fbbd923a2661df38766fc92d7429c6cd0f340bfb4d3a782f944f8f8123', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:28:04'),
(279, 'MOHD. IQBAL ABDUL K.', 'GANDHI', '1', NULL, 9833089441, '540028e56dc2b13ac9b93c14085310c300cafcbe97ed7d5b2133480b340642cf', '10e059e6e8fd3b3442d2f579d7780a0b', 'a6327d1fdce0d2ae14f91e84ccb2be5aed2c56baa83fa3ecc5cf7958eff05f67', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:30:06'),
(280, 'LOKHANDE', 'MANOHAR K.', '1', NULL, 9699796666, 'cce5da204ed602317be057f010786aea6bf5b819ce6682691d6c7c0c13c3dbe0', 'c1a7fb4f387c83bfd374a8d1916ded9f', 'eb1e56775effccf19b6a517c4b3b264231d28f5f8689a84138b7e459441b0ce4', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:31:50'),
(281, 'ANWARALI R.', 'MERCHANT', '1', NULL, 9869278414, '15fce8cb974775f025b701862e1175fd405aba911d035468efe73f11869d4ace', '130ef92eadf97ed900f236d809160e16', 'f1f495bd27d8fdc93aea720ccef8e2c9fe680e1ddf41df58d818ee1e27638079', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:32:45'),
(282, 'LIYAKAT J.', 'ANKLESHWARIA', '1', NULL, 9819992555, 'b989441ae419adb1b0e4ae596646dd5ab39a8fb818d99c0a31c220bc000a15a0', '6f5a481c29987e14c11cf40bf736df41', '8f340bd92633c7fec61535f9df4b1797e74c1d3bf99983ac1a8fc8298ba6406f', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:33:40'),
(283, 'SALIMA R GHASWALA', 'RIZWAN ALI GHASWALA', '2', NULL, 9833161433, 'ecbafa30a572625158ca5a99b35c96fccad4baed79afc3a14854f0cca521b40c', '7dd08071aca355019c2f64ab8f57d8e2', '0d526c42447fe4c042048fa228c3972890054266c7d8a5655febad3a7c555254', '09_2016/e47c55438e5bcc5eca88be304d70b7de.jpg', NULL, NULL, '1', '1', '1', '2016-09-22 02:36:29'),
(284, 'VENGURLEKAR', '.', '1', NULL, 9004273473, '50eab9f7edc401d67524ec2bee26bb22202897edbaab47ecd2b0257fe4294981', '7243ae3d51eab309514c75cbff571124', 'e4aa663a46908d33966afb64bae85c853c5e80710a8ce4f2fe9226a388b25f9d', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:37:33'),
(285, 'AJAY N.', 'SHUKLA', '1', NULL, 9892498188, '767635303d6c098db088c50e6c08b8a6b67c4fe06a24eddc1ad49a734a3f60af', '9fb38ad423c383207cd39614ccbd2c93', '5e0012d4c4c92223c46b18bda46e2fb57dec201c6131a593c58e30cda1e51fc6', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:38:44'),
(286, 'FAREEDA C.', 'LOUIS', '2', NULL, 9820081696, '8c6593f269109e3ddc16357253536a9cbc0dda176631c5199958dae003d090dd', '5e11858f4f3c2ef810607336c763b8b3', 'debc9cc94f6f99e84dbf60bf335861775d0d61356d6598288daf54e9ccd00c3e', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:39:46'),
(287, 'HARSHA N.', 'IYER', '1', NULL, 9820138290, '544a13c7dd586d716a9d0e26d2303121e254356b33e6246194ee4c2b4f9c323d', '349b574797218d3fc5968d7816574bee', '3baa2870ff86368304a7620551ca84742312e840607d51e6a03e65cf37cdf5c6', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:40:42'),
(288, 'S.C.', 'GHORAI', '1', NULL, 9820319999, '4eec7aa82c4f787a07472c2bfcb67a8d7949ac3f72bdfc09981200d06d47268a', '0b9760ec552aa4b4d9911db7f8638e6c', 'de9dfcd437b160b8170d412203edaef7750cdbefce82a24649f2c4076ff33757', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:41:58'),
(289, 'JOHN RAO PRAKASH RAO', 'JANUMALA', '1', NULL, 9819939977, '80be272e148a7b18a8937543f4beca69b573b28661400e4be7b76f90a69fd7e7', '8522e8d4e237311113ce570a1a634aae', 'bd0a54dd4782f8af2e3157ddbcf331023268a46d5627ff6533a7fa9e47e7b0f7', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:43:27'),
(290, 'IMTIYAZ A.', 'PUNJABI', '1', NULL, 9820092658, 'a30660c3d8d3d59212243a59b7ab8586806a144ca98f88b2a5bf354ce43f366c', '3e1fd37b1b5c7980670607d86688d809', '862c5475f8e1cccc0760568f63f454686566e06a9f03f8f5bb0df8a66b3136b8', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:44:20'),
(291, 'RAVINDRA B. KATARIA', 'KATARIA', '1', NULL, 8097305530, 'c3dfbe2341c9fa225d750c86cc4008c25f1a5d7fc470ef81cba8b1b6db028033', 'd98914be8de1e9a0095371183cf10236', '1c01e6fde7a2cfb8ee6511a05f2be851140403e90617cc41a9d91d2d78cf58d8', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:45:28'),
(292, 'MEDHA A.', 'MASURKAR', '1', NULL, 8976515031, '53517135c59eb2f93908e7eb8ef1974255363adfb71423ad9730990b21124d14', '4d220771c8a61306670a6690a449fd95', 'd1f806d674661ee4b2057f6942830e06d322bf464d9421bca4d2636462b74a94', '', NULL, NULL, '1', '1', '1', '2016-09-22 02:46:22'),
(293, 'Rahul', 'Bhojwani', '1', 'Rahulvinod633@gmail.com', 9892730038, 'fcde5b6b11ac76caf5b1a0304a1153141224de05c5019cd9ff4ace14c09d8309', '29aa1e918a30ac1c6b6402a3d78598f0', '6effc1656973614c5db7040bf4f722606eeeabb7216b881349287551f5214fbe', '', NULL, NULL, '1', '2', '1', '2016-09-25 01:38:06'),
(294, 'vikas', 'yadav', '1', 'vikasyadav201192@gmail.com', 8286143533, '3db99a176ddd56a5f3127eebd318baff6644cb633b50514c6e962c57c055b2aa', '18e9c825e216b6b67fbe4851105e577a', '291df5314fd270815d1e437c71b09795882fb84488dcca32b6c4abfde11c1410', '', NULL, NULL, '1', '1', '1', '2016-09-30 01:47:43'),
(307, 'Anand', 'Bakshi', '1', NULL, 7303395460, '5513fb833866ae9e94eba360969a299b60bb8d506f0480410ed2d902b19292a1', 'a16573d80b4cceede0f366a815e2f923', 'ac070b8b0592f68e9d928f926d519ac16372b36e63a89cd2697096ce2e997781', '', NULL, NULL, '1', '2', '2', '2016-10-09 09:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `users_request`
--

DROP TABLE IF EXISTS `users_request`;
CREATE TABLE IF NOT EXISTS `users_request` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `society_id` int(11) UNSIGNED NOT NULL,
  `status` enum('1','2') NOT NULL DEFAULT '1',
  `date_requested` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `user_id` (`user_id`),
  KEY `fk_s_rq` (`society_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_request`
--

INSERT INTO `users_request` (`user_id`, `society_id`, `status`, `date_requested`) VALUES
(43, 1, '1', '2016-04-18 07:24:43'),
(44, 1, '2', '2016-04-19 13:15:09'),
(293, 3, '1', '2016-09-25 01:38:50');

-- --------------------------------------------------------

--
-- Table structure for table `users_single_data`
--

DROP TABLE IF EXISTS `users_single_data`;
CREATE TABLE IF NOT EXISTS `users_single_data` (
  `user` int(10) UNSIGNED NOT NULL,
  `phone_code` char(6) NOT NULL,
  `push_token` char(50) DEFAULT NULL,
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_single_data`
--

INSERT INTO `users_single_data` (`user`, `phone_code`, `push_token`) VALUES
(1, 'ae2fb9', 'c72e2991-49fd-44bf-8ec1-c1c2fd2eaaba'),
(2, '', NULL),
(3, '', NULL),
(4, '', NULL),
(5, '', NULL),
(6, '', NULL),
(7, '', NULL),
(8, '', NULL),
(9, '', NULL),
(10, '', NULL),
(11, '', NULL),
(12, '', NULL),
(13, '', NULL),
(14, '', NULL),
(15, '', NULL),
(16, '', NULL),
(17, '', NULL),
(18, '', NULL),
(19, '', NULL),
(20, '', NULL),
(21, '', NULL),
(22, '', NULL),
(23, '', NULL),
(24, '', NULL),
(25, '', NULL),
(26, '', NULL),
(27, '', NULL),
(28, '', NULL),
(29, '', NULL),
(30, '', NULL),
(31, '', NULL),
(32, '', NULL),
(33, '', NULL),
(34, '', NULL),
(35, '', NULL),
(36, '', NULL),
(40, '', NULL),
(41, '', NULL),
(42, '', NULL),
(43, '', NULL),
(44, '', NULL),
(45, '', NULL),
(50, '', NULL),
(51, '', '0d348500-005b-418a-ac4c-7d9228496426'),
(78, '', '401a9712-c57d-45ed-9f92-39b743799b79'),
(79, '', '7ebb3f71-25e6-4590-be6d-e20b6a8c3435'),
(80, '', NULL),
(81, '', NULL),
(84, '', '23926ddf-812d-4b1c-b8ac-fbdcfcb11f22'),
(85, '', NULL),
(86, '', NULL),
(87, '', NULL),
(88, '', NULL),
(99, '', 'cb6919a9-f984-4e37-bab9-c3d72b97cb6c'),
(100, '', '447250b2-dec9-400d-a21c-030b5e377997'),
(103, '', NULL),
(104, '', '3b5c5bf7-1847-4a48-840f-5030d8204d9b'),
(105, '', '566c187e-70e7-4919-9fa1-36e6dff9374c'),
(120, '', NULL),
(121, '', NULL),
(126, '', NULL),
(127, '', NULL),
(129, '', NULL),
(132, '', NULL),
(133, '', NULL),
(134, '', NULL),
(143, '', NULL),
(149, '', NULL),
(150, '', NULL),
(151, '', NULL),
(152, '', NULL),
(154, '', '19394ab2-fd2c-40b5-ad6c-b4710a995aa6'),
(155, '', NULL),
(156, '', NULL),
(157, '', NULL),
(158, '', NULL),
(159, '', NULL),
(160, '', NULL),
(161, '', NULL),
(162, 'ed1066', '4a0cbc16-2304-44bd-9bc8-c8add16f2fcc'),
(163, '', NULL),
(164, '', NULL),
(165, '', NULL),
(166, '', NULL),
(167, '', NULL),
(168, '', NULL),
(169, '', NULL),
(170, '', NULL),
(171, '', NULL),
(172, '', NULL),
(174, '', NULL),
(175, '', NULL),
(176, '', NULL),
(177, '', NULL),
(178, '', NULL),
(179, '', NULL),
(180, '', NULL),
(181, '', NULL),
(182, '', NULL),
(183, '', NULL),
(184, '', NULL),
(185, '', NULL),
(186, '', NULL),
(187, '', NULL),
(188, '', 'b593ee74-1752-4e73-9628-35ab1a7d9a59'),
(189, '', NULL),
(190, '', NULL),
(191, '', NULL),
(192, '', NULL),
(193, '', NULL),
(194, '', NULL),
(195, '', NULL),
(196, '', NULL),
(197, '', NULL),
(198, '', NULL),
(199, '', NULL),
(200, '', NULL),
(201, '', NULL),
(202, '', NULL),
(203, '', NULL),
(204, '', NULL),
(205, '', NULL),
(206, '', NULL),
(207, '', NULL),
(208, '', NULL),
(209, '', '0a506340-e564-4e74-acf4-891e0ff3a50d'),
(210, '', NULL),
(211, '', 'e6008726-86a2-41f6-bfe1-968a655c5e03'),
(212, '', NULL),
(213, '', NULL),
(214, '', NULL),
(215, '', NULL),
(216, '', NULL),
(217, '', '0ce0a028-e67f-4ad0-b7b5-f9ebb8209d76'),
(218, '', NULL),
(219, '', NULL),
(220, '', NULL),
(221, '', NULL),
(222, '', NULL),
(223, '', NULL),
(224, '', NULL),
(225, '', NULL),
(226, '', NULL),
(227, '', NULL),
(228, '', NULL),
(229, '', NULL),
(230, '', NULL),
(231, '', 'ddd16b57-83c7-42bb-9aea-f3196ed4d39c'),
(232, '', NULL),
(233, '', NULL),
(234, '', NULL),
(235, '', '3520dd84-c49d-4a54-9bbb-434312b73a61'),
(236, '', NULL),
(237, '', NULL),
(238, '', NULL),
(239, '', NULL),
(240, '', NULL),
(241, '', NULL),
(242, '', NULL),
(243, '', NULL),
(244, '', NULL),
(245, '', NULL),
(246, '', NULL),
(247, '', '1af26d47-6484-4e66-b7c9-641d36f46111'),
(248, '', NULL),
(249, '', NULL),
(250, '', NULL),
(251, '', NULL),
(252, '', NULL),
(253, '', NULL),
(254, '', NULL),
(255, '', NULL),
(256, '', NULL),
(257, '', NULL),
(258, '', NULL),
(259, '', NULL),
(260, '', NULL),
(261, '', 'cd59c453-53e7-4449-a6c5-35c0e1b432ce'),
(262, '', NULL),
(263, '', '7da6ada9-1278-4967-ac92-bae23efc2f16'),
(264, '', NULL),
(265, '', NULL),
(266, '', NULL),
(267, '', NULL),
(268, '', NULL),
(269, '', NULL),
(270, '', NULL),
(271, '', NULL),
(272, '', NULL),
(273, '', NULL),
(274, '', NULL),
(275, '', NULL),
(276, '', NULL),
(277, '', NULL),
(278, '', NULL),
(279, '', NULL),
(280, '', NULL),
(281, '', NULL),
(282, '', NULL),
(283, '', 'e963bbf2-9d5f-45b6-a67d-58e4e54d47ac'),
(284, '', NULL),
(285, '', NULL),
(286, '', NULL),
(287, '', NULL),
(288, '', NULL),
(289, '', NULL),
(290, '', NULL),
(291, '', NULL),
(292, '', NULL),
(293, '', 'd959bd83-2f35-423b-adc7-d18dda856d9a'),
(294, '', NULL),
(307, 'e02383', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_created_by_user_logs`
--

DROP TABLE IF EXISTS `user_created_by_user_logs`;
CREATE TABLE IF NOT EXISTS `user_created_by_user_logs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `added_by` int(10) UNSIGNED NOT NULL,
  `added_to` int(10) UNSIGNED NOT NULL,
  `society_id` int(10) UNSIGNED NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ucbu` (`society_id`),
  KEY `fk_ucbub` (`added_by`),
  KEY `fk_ucbut` (`added_to`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_created_by_user_logs`
--

INSERT INTO `user_created_by_user_logs` (`id`, `added_by`, `added_to`, `society_id`, `date_added`) VALUES
(36, 1, 307, 1, '2016-10-09 09:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_flat`
--

DROP TABLE IF EXISTS `user_flat`;
CREATE TABLE IF NOT EXISTS `user_flat` (
  `user` int(11) UNSIGNED NOT NULL,
  `flat_id` int(11) UNSIGNED NOT NULL,
  `society_id` int(11) UNSIGNED NOT NULL,
  `owner_tenant` enum('1','2') NOT NULL DEFAULT '1',
  KEY `fk_flat_user` (`user`),
  KEY `fk_user_flat` (`flat_id`),
  KEY `fk_user_society` (`society_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_flat`
--

INSERT INTO `user_flat` (`user`, `flat_id`, `society_id`, `owner_tenant`) VALUES
(3, 32, 1, '1'),
(4, 31, 1, '1'),
(5, 2, 1, '1'),
(6, 3, 1, '1'),
(7, 4, 1, '1'),
(8, 5, 1, '1'),
(9, 6, 1, '1'),
(10, 7, 1, '1'),
(11, 8, 1, '1'),
(13, 1, 1, '1'),
(14, 20, 1, '1'),
(15, 19, 1, '1'),
(16, 18, 1, '1'),
(17, 17, 1, '1'),
(18, 16, 1, '1'),
(19, 15, 1, '1'),
(20, 14, 1, '1'),
(21, 13, 1, '1'),
(22, 12, 1, '1'),
(23, 11, 1, '1'),
(24, 21, 1, '1'),
(25, 30, 1, '1'),
(26, 33, 1, '1'),
(27, 22, 1, '1'),
(28, 23, 1, '1'),
(29, 24, 1, '1'),
(30, 25, 1, '1'),
(31, 26, 1, '1'),
(32, 27, 1, '1'),
(33, 28, 1, '1'),
(34, 29, 1, '1'),
(12, 10, 1, '1'),
(45, 31, 1, '1'),
(78, 1, 1, '1'),
(2, 9, 1, '1'),
(51, 36, 2, '1'),
(80, 35, 2, '1'),
(81, 22, 1, '1'),
(103, 10, 1, '1'),
(104, 9, 1, '1'),
(105, 8, 1, '1'),
(133, 2, 1, '1'),
(151, 28, 1, '1'),
(152, 19, 1, '1'),
(154, 1, 1, '1'),
(156, 130, 6, '1'),
(157, 135, 6, '1'),
(158, 109, 6, '1'),
(159, 111, 6, '1'),
(160, 112, 6, '1'),
(161, 113, 6, '1'),
(162, 114, 6, '1'),
(163, 115, 6, '1'),
(164, 116, 6, '1'),
(165, 117, 6, '1'),
(166, 118, 6, '1'),
(167, 119, 6, '1'),
(168, 120, 6, '1'),
(169, 121, 6, '1'),
(170, 122, 6, '1'),
(171, 123, 6, '1'),
(172, 124, 6, '1'),
(174, 139, 3, '1'),
(175, 140, 3, '1'),
(176, 141, 3, '1'),
(177, 142, 3, '1'),
(178, 143, 3, '1'),
(179, 144, 3, '1'),
(180, 145, 3, '1'),
(181, 146, 3, '1'),
(182, 147, 3, '1'),
(183, 148, 3, '1'),
(184, 149, 3, '1'),
(185, 150, 3, '1'),
(186, 151, 3, '1'),
(187, 152, 3, '1'),
(126, 152, 3, '1'),
(188, 154, 3, '1'),
(189, 155, 3, '1'),
(190, 156, 3, '1'),
(191, 157, 3, '1'),
(192, 158, 3, '1'),
(193, 159, 3, '1'),
(194, 160, 3, '1'),
(195, 161, 3, '1'),
(196, 162, 3, '1'),
(197, 163, 3, '1'),
(198, 164, 3, '1'),
(199, 165, 3, '1'),
(200, 166, 3, '1'),
(201, 167, 3, '1'),
(202, 168, 3, '1'),
(203, 169, 3, '1'),
(204, 170, 3, '1'),
(206, 172, 3, '1'),
(207, 173, 3, '1'),
(208, 174, 3, '1'),
(209, 175, 3, '1'),
(210, 177, 3, '1'),
(211, 178, 3, '1'),
(212, 179, 3, '1'),
(213, 180, 3, '1'),
(214, 181, 3, '1'),
(215, 182, 3, '1'),
(216, 183, 3, '1'),
(217, 184, 3, '1'),
(218, 185, 3, '1'),
(219, 186, 3, '1'),
(222, 189, 3, '1'),
(223, 190, 3, '1'),
(224, 191, 3, '1'),
(225, 192, 3, '1'),
(226, 193, 3, '1'),
(227, 194, 3, '1'),
(228, 195, 3, '1'),
(229, 196, 3, '1'),
(230, 197, 3, '1'),
(231, 198, 3, '1'),
(232, 199, 3, '1'),
(233, 200, 3, '1'),
(234, 201, 3, '1'),
(235, 202, 3, '1'),
(236, 203, 3, '1'),
(237, 204, 3, '1'),
(239, 206, 3, '1'),
(240, 207, 3, '1'),
(241, 208, 3, '1'),
(242, 209, 3, '1'),
(243, 210, 3, '1'),
(244, 211, 3, '1'),
(245, 212, 3, '1'),
(246, 213, 3, '1'),
(247, 214, 3, '1'),
(248, 215, 3, '1'),
(249, 216, 3, '1'),
(250, 217, 3, '1'),
(251, 218, 3, '1'),
(252, 219, 3, '1'),
(253, 220, 3, '1'),
(254, 221, 3, '1'),
(256, 223, 3, '1'),
(257, 224, 3, '1'),
(258, 225, 3, '1'),
(259, 226, 3, '1'),
(260, 227, 3, '1'),
(261, 228, 3, '1'),
(262, 229, 3, '1'),
(263, 230, 3, '1'),
(264, 231, 3, '1'),
(265, 232, 3, '1'),
(266, 233, 3, '1'),
(268, 235, 3, '1'),
(269, 236, 3, '1'),
(267, 234, 3, '1'),
(267, 238, 3, '1'),
(267, 239, 3, '1'),
(270, 240, 3, '1'),
(271, 241, 3, '1'),
(272, 242, 3, '1'),
(273, 243, 3, '1'),
(205, 171, 3, '1'),
(205, 244, 3, '1'),
(220, 187, 3, '1'),
(220, 188, 3, '1'),
(221, 245, 3, '1'),
(255, 222, 3, '1'),
(255, 246, 3, '1'),
(238, 205, 3, '1'),
(238, 247, 3, '1'),
(1, 1, 1, '1'),
(1, 2, 1, '1'),
(274, 248, 6, '1'),
(275, 249, 6, '1'),
(276, 250, 6, '1'),
(277, 251, 6, '1'),
(278, 252, 6, '1'),
(279, 253, 6, '1'),
(280, 254, 6, '1'),
(281, 255, 6, '1'),
(282, 256, 6, '1'),
(283, 257, 6, '1'),
(284, 258, 6, '1'),
(285, 259, 6, '1'),
(286, 260, 6, '1'),
(287, 261, 6, '1'),
(288, 262, 6, '1'),
(289, 263, 6, '1'),
(290, 264, 6, '1'),
(291, 265, 6, '1'),
(292, 266, 6, '1'),
(307, 1, 1, '1'),
(307, 2, 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_tracking`
--

DROP TABLE IF EXISTS `user_login_tracking`;
CREATE TABLE IF NOT EXISTS `user_login_tracking` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `server_data` text NOT NULL,
  `date_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_user_tracked` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_login_tracking`
--

INSERT INTO `user_login_tracking` (`id`, `user_id`, `server_data`, `date_login`) VALUES
(4, 257, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKExpbnV4OyBBbmRyb2lkIDUuMTsgWjgxMiBCdWlsZFwvTE1ZNDdPKSBBcHBsZVdlYktpdFwvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lXC81Mi4wLjI3NDMuOTggTW9iaWxlIFNhZmFyaVwvNTM3LjM2IiwiU0VSVkVSX0FERFIiOiIxNzguNjIuMjYuMTEwIn0=', '2016-09-19 15:16:23'),
(8, 268, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4zOyBXT1c2NDsgVHJpZGVudFwvNy4wOyBydjoxMS4wKSBsaWtlIEdlY2tvIiwiU0VSVkVSX0FERFIiOiIxNzguNjIuMjYuMTEwIn0=', '2016-09-20 05:52:33'),
(11, 85, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4xOyBXT1c2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNTMuMC4yNzg1LjExNiBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiMTc4LjYyLjI2LjExMCJ9', '2016-09-21 09:32:37'),
(12, 155, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4xKSBBcHBsZVdlYktpdFwvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lXC81My4wLjI3ODUuMTE2IFNhZmFyaVwvNTM3LjM2IiwiU0VSVkVSX0FERFIiOiIxNzguNjIuMjYuMTEwIn0=', '2016-09-22 02:16:31'),
(17, 283, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKExpbnV4OyBBbmRyb2lkIDUuMC4yOyBTTS1BMzAwRlUgQnVpbGRcL0xSWDIyRykgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNDkuMC4yNjIzLjEwNSBNb2JpbGUgU2FmYXJpXC81MzcuMzYiLCJTRVJWRVJfQUREUiI6IjE3OC42Mi4yNi4xMTAifQ==', '2016-09-23 10:56:37'),
(18, 283, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKExpbnV4OyBBbmRyb2lkIDUuMC4yOyBTTS1BMzAwRlUgQnVpbGRcL0xSWDIyRykgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNDkuMC4yNjIzLjEwNSBNb2JpbGUgU2FmYXJpXC81MzcuMzYiLCJTRVJWRVJfQUREUiI6IjE3OC42Mi4yNi4xMTAifQ==', '2016-09-23 11:01:19'),
(19, 283, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKExpbnV4OyBBbmRyb2lkIDUuMC4yOyBTTS1BMzAwRlUgQnVpbGRcL0xSWDIyRykgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNDkuMC4yNjIzLjEwNSBNb2JpbGUgU2FmYXJpXC81MzcuMzYiLCJTRVJWRVJfQUREUiI6IjE3OC42Mi4yNi4xMTAifQ==', '2016-09-23 11:01:38'),
(20, 287, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKExpbnV4OyBBbmRyb2lkIDYuMDsgSFVBV0VJIE1UNy1UTDEwIEJ1aWxkXC9IdWF3ZWlNVDctVEwxMCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNTMuMC4yNzg1LjEyNCBNb2JpbGUgU2FmYXJpXC81MzcuMzYiLCJTRVJWRVJfQUREUiI6IjE3OC42Mi4yNi4xMTAifQ==', '2016-09-24 01:59:06'),
(23, 51, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4yOyBXT1c2NDsgcnY6NDkuMCkgR2Vja29cLzIwMTAwMTAxIEZpcmVmb3hcLzQ5LjAiLCJTRVJWRVJfQUREUiI6IjE3OC42Mi4yNi4xMTAifQ==', '2016-10-05 07:32:13'),
(25, 294, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4xOyBXT1c2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNTMuMC4yNzg1LjE0MyBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiMTc4LjYyLjI2LjExMCJ9', '2016-10-06 05:01:15'),
(44, 294, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4xOyBXT1c2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNTMuMC4yNzg1LjE0MyBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiMTc4LjYyLjI2LjExMCJ9', '2016-10-10 00:21:53'),
(47, 85, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4xKSBBcHBsZVdlYktpdFwvNTM3LjM2IChLSFRNTCwgbGlrZSBHZWNrbykgQ2hyb21lXC81My4wLjI3ODUuMTQzIFNhZmFyaVwvNTM3LjM2IiwiU0VSVkVSX0FERFIiOiIxNzguNjIuMjYuMTEwIn0=', '2016-10-11 01:13:18'),
(48, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4xOyBXT1c2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNjEuMC4zMTYzLjEwMCBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2017-10-07 14:27:39'),
(49, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4xOyBXT1c2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNjEuMC4zMTYzLjEwMCBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2017-10-07 17:37:31'),
(50, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgNi4xOyBXT1c2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNjEuMC4zMTYzLjEwMCBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2017-10-08 12:44:07'),
(51, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNjguMC4zNDQwLjEwNiBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2018-09-09 17:37:42'),
(52, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNjguMC4zNDQwLjEwNiBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2018-09-13 07:52:18'),
(53, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNjguMC4zNDQwLjEwNiBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2018-09-13 16:29:04'),
(54, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNjguMC4zNDQwLjEwNiBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2018-09-20 05:54:11'),
(55, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNjguMC4zNDQwLjEwNiBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2018-09-20 06:06:13'),
(56, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNzEuMC4zNTc4Ljk4IFNhZmFyaVwvNTM3LjM2IiwiU0VSVkVSX0FERFIiOiI6OjEifQ==', '2019-01-15 06:21:56'),
(57, 1, 'eyJIVFRQX1VTRVJfQUdFTlQiOiJNb3ppbGxhXC81LjAgKFdpbmRvd3MgTlQgMTAuMDsgV2luNjQ7IHg2NCkgQXBwbGVXZWJLaXRcLzUzNy4zNiAoS0hUTUwsIGxpa2UgR2Vja28pIENocm9tZVwvNzIuMC4zNjI2LjEwOSBTYWZhcmlcLzUzNy4zNiIsIlNFUlZFUl9BRERSIjoiOjoxIn0=', '2019-02-22 11:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `user_society`
--

DROP TABLE IF EXISTS `user_society`;
CREATE TABLE IF NOT EXISTS `user_society` (
  `user` int(11) UNSIGNED NOT NULL,
  `society` int(11) UNSIGNED NOT NULL,
  `is_admin` enum('1','2') NOT NULL DEFAULT '2',
  `designation` char(25) NOT NULL,
  `assoc_member` enum('1','2') NOT NULL DEFAULT '2',
  `no_of_flats` smallint(1) UNSIGNED NOT NULL DEFAULT '0',
  KEY `fk_u_soc` (`society`),
  KEY `fk_u_user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_society`
--

INSERT INTO `user_society` (`user`, `society`, `is_admin`, `designation`, `assoc_member`, `no_of_flats`) VALUES
(1, 1, '1', 'Committee Member', '1', 2),
(3, 1, '2', '', '2', 1),
(4, 1, '2', '', '2', 1),
(5, 1, '2', '', '2', 1),
(6, 1, '2', '', '2', 1),
(7, 1, '2', '', '2', 1),
(8, 1, '2', '', '2', 1),
(9, 1, '2', '', '2', 1),
(10, 1, '2', '', '2', 1),
(11, 1, '2', '', '2', 1),
(12, 1, '2', 'Treasurer', '1', 1),
(13, 1, '2', '', '2', 1),
(14, 1, '2', '', '2', 1),
(15, 1, '2', 'Committee Member', '1', 1),
(16, 1, '2', '', '2', 1),
(17, 1, '2', '', '2', 1),
(18, 1, '2', '', '2', 1),
(19, 1, '2', '', '2', 1),
(20, 1, '2', '', '2', 1),
(21, 1, '2', '', '2', 1),
(22, 1, '2', 'Vice President', '1', 1),
(23, 1, '2', '', '2', 1),
(24, 1, '2', '', '2', 1),
(25, 1, '2', '', '2', 1),
(26, 1, '2', '', '2', 1),
(27, 1, '2', '', '2', 1),
(28, 1, '2', '', '2', 1),
(29, 1, '2', 'Committee Member', '1', 1),
(30, 1, '2', '', '2', 1),
(31, 1, '2', '', '2', 1),
(32, 1, '2', '', '2', 1),
(33, 1, '2', '', '2', 1),
(34, 1, '2', '', '2', 1),
(36, 1, '2', '', '2', 0),
(35, 1, '2', '', '2', 0),
(2, 1, '2', '', '2', 1),
(40, 2, '1', '', '2', 0),
(45, 1, '2', '', '2', 1),
(51, 2, '1', '', '2', 1),
(78, 1, '2', '', '2', 1),
(80, 2, '2', '', '2', 1),
(81, 1, '2', '', '2', 1),
(85, 3, '1', 'Manager', '2', 0),
(87, 4, '1', '', '2', 0),
(88, 5, '1', '', '2', 0),
(103, 1, '2', '', '2', 1),
(104, 1, '2', '', '2', 1),
(105, 1, '2', '', '2', 1),
(120, 1, '2', '', '2', 0),
(121, 1, '2', '', '2', 0),
(129, 1, '2', '', '2', 0),
(132, 1, '2', '', '2', 0),
(133, 1, '2', '', '2', 1),
(134, 1, '2', '', '2', 0),
(149, 1, '2', '', '2', 0),
(150, 1, '2', '', '2', 0),
(151, 1, '2', '', '2', 1),
(152, 1, '2', '', '2', 1),
(154, 1, '2', '', '2', 1),
(155, 6, '1', '', '2', 0),
(156, 6, '2', 'Secretary', '1', 1),
(157, 6, '2', '', '2', 1),
(158, 6, '2', '', '2', 1),
(159, 6, '2', '', '2', 1),
(160, 6, '2', '', '2', 1),
(161, 6, '2', '', '2', 1),
(162, 6, '2', '', '2', 1),
(163, 6, '2', '', '2', 1),
(164, 6, '2', '', '2', 1),
(165, 6, '2', '', '2', 1),
(166, 6, '2', '', '2', 1),
(167, 6, '2', '', '2', 1),
(168, 6, '2', '', '2', 1),
(169, 6, '2', '', '2', 1),
(170, 6, '2', '', '2', 1),
(171, 6, '2', '', '2', 1),
(172, 6, '2', '', '2', 1),
(174, 3, '2', '', '2', 1),
(175, 3, '2', '', '2', 1),
(176, 3, '2', '', '2', 1),
(177, 3, '2', '', '2', 1),
(178, 3, '2', '', '2', 1),
(179, 3, '2', '', '2', 1),
(180, 3, '2', '', '2', 1),
(181, 3, '2', '', '2', 1),
(182, 3, '2', '', '2', 1),
(183, 3, '2', '', '2', 1),
(184, 3, '2', '', '2', 1),
(185, 3, '2', '', '2', 1),
(186, 3, '2', '', '2', 1),
(187, 3, '2', '', '2', 1),
(126, 3, '2', '', '2', 1),
(188, 3, '2', '', '2', 1),
(189, 3, '2', '', '2', 1),
(190, 3, '2', '', '2', 1),
(191, 3, '2', '', '2', 1),
(192, 3, '2', '', '2', 1),
(193, 3, '2', '', '2', 1),
(194, 3, '2', '', '2', 1),
(195, 3, '2', '', '2', 1),
(196, 3, '2', '', '2', 1),
(197, 3, '2', '', '2', 1),
(198, 3, '2', '', '2', 1),
(199, 3, '2', '', '2', 1),
(200, 3, '2', '', '2', 1),
(201, 3, '2', '', '2', 1),
(202, 3, '2', '', '2', 1),
(203, 3, '2', '', '2', 1),
(204, 3, '2', '', '2', 1),
(205, 3, '2', '', '2', 2),
(206, 3, '2', '', '2', 1),
(207, 3, '2', '', '2', 1),
(208, 3, '2', '', '2', 1),
(209, 3, '2', '', '2', 1),
(210, 3, '2', '', '2', 1),
(211, 3, '2', '', '2', 1),
(212, 3, '2', '', '2', 1),
(213, 3, '2', '', '2', 1),
(214, 3, '2', '', '2', 1),
(215, 3, '2', '', '2', 1),
(216, 3, '2', '', '2', 1),
(217, 3, '2', '', '2', 1),
(218, 3, '2', '', '2', 1),
(219, 3, '2', '', '2', 1),
(220, 3, '2', '', '2', 2),
(221, 3, '2', '', '2', 1),
(222, 3, '2', '', '2', 1),
(223, 3, '2', '', '2', 1),
(224, 3, '2', '', '2', 1),
(225, 3, '2', '', '2', 1),
(226, 3, '2', '', '2', 1),
(227, 3, '2', '', '2', 1),
(228, 3, '2', '', '2', 1),
(229, 3, '2', '', '2', 1),
(230, 3, '2', '', '2', 1),
(231, 3, '2', '', '2', 1),
(232, 3, '2', '', '2', 1),
(233, 3, '2', '', '2', 1),
(234, 3, '2', '', '2', 1),
(235, 3, '2', '', '2', 1),
(236, 3, '2', '', '2', 1),
(237, 3, '2', '', '2', 1),
(238, 3, '2', '', '2', 2),
(239, 3, '2', '', '2', 1),
(240, 3, '2', '', '2', 1),
(241, 3, '2', '', '2', 1),
(242, 3, '2', '', '2', 1),
(243, 3, '2', '', '2', 1),
(244, 3, '2', '', '2', 1),
(245, 3, '2', '', '2', 1),
(246, 3, '2', '', '2', 1),
(247, 3, '2', '', '2', 1),
(248, 3, '2', '', '2', 1),
(249, 3, '2', '', '2', 1),
(250, 3, '2', '', '2', 1),
(251, 3, '2', '', '2', 1),
(252, 3, '2', '', '2', 1),
(253, 3, '2', '', '2', 1),
(254, 3, '2', '', '2', 1),
(255, 3, '2', '', '2', 2),
(256, 3, '2', '', '2', 1),
(257, 3, '2', '', '2', 1),
(258, 3, '2', '', '2', 1),
(259, 3, '2', '', '2', 1),
(260, 3, '2', '', '2', 1),
(261, 3, '2', '', '2', 1),
(262, 3, '2', '', '2', 1),
(263, 3, '2', '', '2', 1),
(264, 3, '2', '', '2', 1),
(265, 3, '2', '', '2', 1),
(266, 3, '2', '', '2', 1),
(267, 3, '2', '', '2', 3),
(268, 3, '2', '', '2', 1),
(269, 3, '2', '', '2', 1),
(270, 3, '2', '', '2', 1),
(271, 3, '2', '', '2', 1),
(272, 3, '2', '', '2', 1),
(273, 3, '2', '', '2', 1),
(274, 6, '2', '', '2', 1),
(275, 6, '2', '', '2', 1),
(276, 6, '2', '', '2', 1),
(277, 6, '2', '', '2', 1),
(278, 6, '2', '', '2', 1),
(279, 6, '2', '', '2', 1),
(280, 6, '2', '', '2', 1),
(281, 6, '2', '', '2', 1),
(282, 6, '2', '', '2', 1),
(283, 6, '2', '', '2', 1),
(284, 6, '2', '', '2', 1),
(285, 6, '2', '', '2', 1),
(286, 6, '2', '', '2', 1),
(287, 6, '2', '', '2', 1),
(288, 6, '2', '', '2', 1),
(289, 6, '2', '', '2', 1),
(290, 6, '2', '', '2', 1),
(291, 6, '2', '', '2', 1),
(292, 6, '2', '', '2', 1),
(294, 1, '1', '', '2', 0),
(307, 1, '2', '', '2', 2);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_type`
--

DROP TABLE IF EXISTS `vehicle_type`;
CREATE TABLE IF NOT EXISTS `vehicle_type` (
  `id` tinyint(1) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vehicle_type`
--

INSERT INTO `vehicle_type` (`id`, `name`) VALUES
(1, 'Two Wheeler'),
(2, 'Four Wheeler');

-- --------------------------------------------------------

--
-- Table structure for table `vendors_data`
--

DROP TABLE IF EXISTS `vendors_data`;
CREATE TABLE IF NOT EXISTS `vendors_data` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contact_name` char(50) NOT NULL,
  `contact_number_1` bigint(20) UNSIGNED DEFAULT NULL,
  `contact_number_2` bigint(20) UNSIGNED DEFAULT NULL,
  `address` char(255) NOT NULL,
  `notes` varchar(1000) NOT NULL,
  `added_by` int(11) UNSIGNED NOT NULL,
  `society_id` int(11) UNSIGNED NOT NULL,
  `category` char(50) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_soc_vendor` (`society_id`),
  KEY `fk_soc_user` (`added_by`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vendors_data`
--

INSERT INTO `vendors_data` (`id`, `contact_name`, `contact_number_1`, `contact_number_2`, `address`, `notes`, `added_by`, `society_id`, `category`, `date_added`) VALUES
(1, 'Ramesh', 678865756, 5555555555, 'Vesave', '', 1, 1, 'Plumber', '2016-04-14 00:34:34'),
(2, 'Jagrut', 9876525646, NULL, '', '', 1, 1, 'Electrcian', '2016-04-19 12:13:46'),
(3, 'Babu', 2020202020, NULL, 'Andheri West', '', 1, 1, 'Istreewala (Clothes Pressing)', '2016-04-21 00:23:37'),
(4, 'Mahesh Bhosle', 2021202120, NULL, 'Apna Bazaar', '', 1, 1, 'Painter', '2016-04-28 07:16:27'),
(5, 'MURLI', 9820928576, NULL, 'Versova', '', 85, 3, 'Electrician', '2016-08-02 01:00:35'),
(6, 'DHARAMPAL', 9930874790, NULL, 'Versova', '', 85, 3, 'Plumber', '2016-08-02 01:01:05'),
(9, 'Akshay', 9619512169, NULL, 'Madh Island', '', 85, 3, 'sweeper', '2016-08-21 03:36:00'),
(10, 'Lal', 7718970041, NULL, 'Versova', '', 85, 3, 'Electrician', '2016-08-21 03:37:15'),
(11, 'Pankaj', 9892099020, NULL, 'Mhada Andheri (W)', '', 85, 3, 'Lift Contractor', '2016-08-21 03:39:17'),
(12, 'Prajapati', 9322751652, NULL, 'Mhada Andheri (W)', '', 85, 3, 'Lift Office', '2016-08-21 03:39:57'),
(13, '7Star Cable', 26362675, 26352675, '7 Bungalows', '', 85, 3, '7 Star Cable', '2016-08-21 03:41:01'),
(14, '7 Star Internet', 67077467, 67077468, '7 Bungalows', '', 85, 3, 'Internet', '2016-08-21 03:41:41'),
(15, 'Bipin', 9321039298, NULL, 'Oshiwara', '', 85, 3, 'Carpentar', '2016-08-21 03:42:33'),
(16, 'Sanjay Devikar', 9820292988, NULL, 'Andheri (E)', '', 85, 3, 'Consultant', '2016-08-21 03:43:26'),
(17, 'Rathod', 8108324223, NULL, 'Ambevali', '', 85, 3, 'Consultant Asst.', '2016-08-21 03:44:11'),
(18, 'M. A. Chohan', 9892131071, NULL, 'Jogeshwari (W)', '', 85, 3, 'Contractor', '2016-08-21 03:45:02'),
(19, 'Mansoor Chohan', 9892113492, NULL, 'Bandra (W)', '', 85, 3, 'Contractor Asst.', '2016-08-21 03:45:39'),
(20, 'Dharminder', 9702541280, NULL, 'Chembur', '', 85, 3, 'Grill Contractor', '2016-08-21 03:46:39'),
(21, 'Hasan Shaikh', 9769534516, NULL, '', '', 85, 3, 'Plumber ( Contractor)', '2016-08-21 03:47:20'),
(22, 'Gora', 9004502637, NULL, 'Bandra', '', 85, 3, 'Plumber ( Contractor)', '2016-08-21 03:47:58'),
(23, 'Patel', 9820078793, NULL, '', '', 85, 3, 'AC Mechanic', '2016-08-21 03:48:32'),
(24, 'Ashraf Hasjmani', 7506450414, NULL, 'Andheri (E)', '', 85, 3, 'Kone Elevator', '2016-08-21 03:49:31'),
(25, 'Sujith Nair', 7045653698, NULL, 'Andheri (E)', '', 85, 3, 'Kone Elevator', '2016-08-21 03:50:05'),
(26, 'Vickey Walia', 9820019308, NULL, 'Jogeshwari (W)', '', 85, 3, 'Electricals Contractor', '2016-08-21 03:50:48'),
(27, 'Jeetendra Yadav', 9833896947, NULL, 'Andheri (E)', '', 85, 3, 'Water pump Repairer', '2016-08-21 03:52:59'),
(28, 'Wishwas Bhinge', 9987363526, NULL, 'Bhandup', '', 85, 3, 'INtercom', '2016-08-21 03:53:32'),
(29, 'Narayan Bangera', 9819546828, NULL, '4 Bungalows', '', 85, 3, 'Accountant', '2016-08-21 03:54:20'),
(30, 'Altaf', 9819191122, NULL, 'Yari Road', '', 85, 3, 'Key Makers', '2016-08-21 03:55:04'),
(31, 'Maqsood Ahmed', 9833305249, 9029467812, 'Yari Road', '', 85, 3, 'Asst.', '2016-08-21 03:56:05'),
(32, 'Varsha Pawaskar', 9867525709, NULL, 'Yari Road', '', 85, 3, 'Manager', '2016-08-21 03:56:34'),
(33, 'Rambohar Shukla', 9892185174, NULL, 'Yari Road', '', 85, 3, 'Security Contractor', '2016-08-21 03:57:44'),
(34, 'Sushil Kumar Pandey', 8454830751, NULL, 'Yari Road', '', 85, 3, '', '2016-08-21 03:58:41'),
(35, 'Savita', 9987395761, NULL, 'Bandra', '', 85, 3, 'Bhajiwali', '2016-08-21 04:01:11'),
(36, 'Nagouri Diary', 26318341, NULL, 'Yari Road', '', 85, 3, '', '2016-08-21 04:02:30'),
(37, 'Santosh Shukla', 9867938172, NULL, 'Yari Road', '', 85, 3, 'Night Watchman', '2016-08-21 04:03:18'),
(38, 'Kalu Pandey', 8898060188, NULL, 'YAri Road', '', 85, 3, 'Night Watchman', '2016-08-21 04:04:01'),
(39, 'Shyam', 9820174050, NULL, 'Yari Road', '', 85, 3, 'Decorators', '2016-08-21 04:04:49'),
(40, 'Baburao', 8652162885, NULL, 'Madh Island', '', 85, 3, 'Mali', '2016-08-21 04:05:49'),
(41, 'Jeetendra Bajpai', 9892104779, NULL, 'Mira Road', '', 85, 3, 'Electric Meter Name Transfer', '2016-08-21 04:06:34'),
(42, 'Dharmesh Shah', 9821370857, NULL, 'Malad (E)', '', 85, 3, 'CA', '2016-08-21 04:07:39'),
(43, 'Jeentendra', 9821834015, NULL, 'Yari Road', '', 85, 3, 'Grill Contractor', '2016-08-21 04:08:58'),
(44, 'Mukhtar', 9821661545, NULL, 'Yari Road', '', 85, 3, 'Plumber', '2016-08-21 04:10:14'),
(45, 'Pandit Chaubey', 9768884621, NULL, 'Yari Road', '', 85, 3, 'Day Watchman', '2016-08-21 04:10:58'),
(46, 'Quadri', 9870899027, NULL, 'Yari Road', '', 85, 3, 'Carpentar', '2016-08-21 04:11:41'),
(47, 'Ramlal', 9930214047, NULL, 'Yari Road', '', 85, 3, 'Painter', '2016-08-21 04:12:27'),
(48, 'Santosh', 9820396472, NULL, '7 Bungalows', '', 85, 3, 'Plumber', '2016-08-21 04:13:32');

-- --------------------------------------------------------

--
-- Table structure for table `watchman_credentials`
--

DROP TABLE IF EXISTS `watchman_credentials`;
CREATE TABLE IF NOT EXISTS `watchman_credentials` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` char(20) NOT NULL,
  `password` char(64) NOT NULL,
  `salt` char(32) NOT NULL,
  `token` char(64) NOT NULL,
  `society_id` int(10) UNSIGNED NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_watchman_user` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `watchman_credentials`
--

INSERT INTO `watchman_credentials` (`id`, `username`, `password`, `salt`, `token`, `society_id`, `date_created`) VALUES
(1, 'watch', '6d713de7c981fe5c8f793e435c0571b62b7babc5cb930d039fe7d0a410fcea4d', 'bf49869ebb9cc004d6a6b0a1f172e819', '4a948cf2d4c068b33125a4fef67b0b2058c50c9bff7b0c3cdfbd308821ff2b8D', 1, '2016-10-16 07:18:33');

-- --------------------------------------------------------

--
-- Table structure for table `wings`
--

DROP TABLE IF EXISTS `wings`;
CREATE TABLE IF NOT EXISTS `wings` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `total_flats` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `society_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_society_wing` (`society_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wings`
--

INSERT INTO `wings` (`id`, `name`, `total_flats`, `society_id`) VALUES
(1, 'M', 11, 1),
(2, 'N', 11, 1),
(3, 'O', 11, 1),
(4, 'I', 0, 2),
(5, 'II', 3, 2),
(6, 'Riddhi - A', 10, 3),
(7, 'Riddhi - B', 12, 3),
(8, 'Riddhi - C', 13, 3),
(9, 'Siddhi - A', 17, 3),
(10, 'Siddhi - B', 9, 3),
(11, 'Siddhi - C', 13, 3),
(12, 'Vriddhi - A', 14, 3),
(13, 'Vriddhi - B', 9, 3),
(14, 'Vriddhi - C', 11, 3),
(15, 'A', 26, 6),
(16, 'B', 22, 6);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD CONSTRAINT `fk_notif_society` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_notif_user_refer` FOREIGN KEY (`user_refer`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bill_group`
--
ALTER TABLE `bill_group`
  ADD CONSTRAINT `fk_society_bgroup` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `default_particulars`
--
ALTER TABLE `default_particulars`
  ADD CONSTRAINT `fk_parti_group` FOREIGN KEY (`group_id`) REFERENCES `bill_group` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `document_folder`
--
ALTER TABLE `document_folder`
  ADD CONSTRAINT `fk_doc_admin` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_doc_soc` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `flats`
--
ALTER TABLE `flats`
  ADD CONSTRAINT `fk_flat_status` FOREIGN KEY (`status`) REFERENCES `flat_status` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_flat_wing` FOREIGN KEY (`flat_wing`) REFERENCES `wings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_society_flat` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `flat_invoice`
--
ALTER TABLE `flat_invoice`
  ADD CONSTRAINT `fk_flat_invoice` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_inv_soc` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gallery_files`
--
ALTER TABLE `gallery_files`
  ADD CONSTRAINT `fk_gal_folder` FOREIGN KEY (`folder_id`) REFERENCES `gallery_folder` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `gallery_folder`
--
ALTER TABLE `gallery_folder`
  ADD CONSTRAINT `fk_gal_soc` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_gal` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `income_expense`
--
ALTER TABLE `income_expense`
  ADD CONSTRAINT `fk_ie_auth_user` FOREIGN KEY (`authorised_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ie_category` FOREIGN KEY (`category_id`) REFERENCES `ie_category` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ie_society` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ie_user` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_particular`
--
ALTER TABLE `invoice_particular`
  ADD CONSTRAINT `fk_invoice_id` FOREIGN KEY (`invoice_id`) REFERENCES `flat_invoice` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `members_messages`
--
ALTER TABLE `members_messages`
  ADD CONSTRAINT `fk_msg_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_msg_society` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `members_messages_reply`
--
ALTER TABLE `members_messages_reply`
  ADD CONSTRAINT `fk_msg_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_msg_parent` FOREIGN KEY (`parent_id`) REFERENCES `members_messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_msg_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_category`
--
ALTER TABLE `news_category`
  ADD CONSTRAINT `fk_news_parent` FOREIGN KEY (`parent_id`) REFERENCES `news_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `news_data`
--
ALTER TABLE `news_data`
  ADD CONSTRAINT `fk_news_p_id` FOREIGN KEY (`category_id`) REFERENCES `news_category` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notice_board`
--
ALTER TABLE `notice_board`
  ADD CONSTRAINT `fk_soc_notice` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parking_data`
--
ALTER TABLE `parking_data`
  ADD CONSTRAINT `fk_p_sid` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pd_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_vt_id` FOREIGN KEY (`vehicle_type`) REFERENCES `vehicle_type` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sms_storage`
--
ALTER TABLE `sms_storage`
  ADD CONSTRAINT `fk_sms_sender` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sms_society` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `society_documents`
--
ALTER TABLE `society_documents`
  ADD CONSTRAINT `fk_folder` FOREIGN KEY (`folder_id`) REFERENCES `document_folder` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_folder_added` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `society_visitors`
--
ALTER TABLE `society_visitors`
  ADD CONSTRAINT `fk_visitor_added` FOREIGN KEY (`user_id`) REFERENCES `watchman_credentials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_visitor_soc` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcription_history`
--
ALTER TABLE `subcription_history`
  ADD CONSTRAINT `fk_soc_history` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_request`
--
ALTER TABLE `users_request`
  ADD CONSTRAINT `fk_s_rq` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_u_rq` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_single_data`
--
ALTER TABLE `users_single_data`
  ADD CONSTRAINT `fk_sn_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_created_by_user_logs`
--
ALTER TABLE `user_created_by_user_logs`
  ADD CONSTRAINT `fk_ucbu` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`),
  ADD CONSTRAINT `fk_ucbub` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_ucbut` FOREIGN KEY (`added_to`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_flat`
--
ALTER TABLE `user_flat`
  ADD CONSTRAINT `fk_flat_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_flat` FOREIGN KEY (`flat_id`) REFERENCES `flats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_society` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_login_tracking`
--
ALTER TABLE `user_login_tracking`
  ADD CONSTRAINT `fk_user_tracked` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_society`
--
ALTER TABLE `user_society`
  ADD CONSTRAINT `fk_u_soc` FOREIGN KEY (`society`) REFERENCES `society_main` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_u_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors_data`
--
ALTER TABLE `vendors_data`
  ADD CONSTRAINT `fk_soc_user` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_soc_vendor` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`);

--
-- Constraints for table `watchman_credentials`
--
ALTER TABLE `watchman_credentials`
  ADD CONSTRAINT `fk_watchman_user` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wings`
--
ALTER TABLE `wings`
  ADD CONSTRAINT `fk_society_wing` FOREIGN KEY (`society_id`) REFERENCES `society_main` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
