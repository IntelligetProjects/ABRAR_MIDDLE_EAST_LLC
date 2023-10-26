-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2023 at 01:38 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teamwayo_t_v3_abrar`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `acc_name` varchar(255) NOT NULL,
  `acc_code` varchar(255) DEFAULT NULL,
  `acc_parent` int(11) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `is_inactive` int(11) NOT NULL DEFAULT 0,
  `acc_description` text DEFAULT NULL,
  `module_id` int(11) DEFAULT 0,
  `module` enum('suppliers','team_members','clients','expenses','assets') DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `acc_name`, `acc_code`, `acc_parent`, `is_primary`, `is_inactive`, `acc_description`, `module_id`, `module`, `deleted`) VALUES
(1, 'Assets', '1', 0, 1, 0, '', NULL, NULL, 0),
(2, 'Non-Current Assets', '1-1', 1, 0, 0, NULL, 0, NULL, 0),
(3, 'Office Devices, Furniture and Fixtures', '1-1-1', 2, 0, 0, NULL, 0, NULL, 0),
(4, 'Office Devices, Furniture and Fixtures', '1-1-1-1', 3, 0, 0, NULL, 0, NULL, 0),
(5, 'Machinery, Tools & Equipments', '1-1-2', 2, 0, 0, NULL, 0, NULL, 0),
(6, 'Machinery, Tools & Equipments', '1-1-2-1', 5, 0, 0, NULL, 0, NULL, 0),
(7, 'Motor Vehicles', '1-1-3', 2, 0, 0, NULL, 0, NULL, 0),
(8, 'Motor Vehicles', '1-1-3-1', 7, 0, 0, NULL, 0, NULL, 0),
(9, 'Long Term Investments ', '1-1-4', 2, 0, 0, NULL, 0, NULL, 0),
(10, 'Long Term Investments ', '1-1-4-1', 9, 0, 0, NULL, 0, NULL, 0),
(11, 'Intangible Assets', '1-1-5', 2, 0, 0, NULL, 0, NULL, 0),
(12, 'Intangible Assets', '1-1-5-1', 11, 0, 0, NULL, 0, NULL, 0),
(13, 'Current Assets', '1-2', 1, 0, 0, NULL, 0, NULL, 0),
(14, 'Employees Payments', '1-2-1', 13, 0, 0, NULL, 0, NULL, 0),
(15, 'Employees loans', '1-2-1-1', 14, 0, 0, NULL, 0, NULL, 0),
(16, 'Employees petty cash', '1-2-1-2', 14, 0, 0, NULL, 0, NULL, 0),
(17, 'Inventories', '1-2-2', 13, 0, 0, NULL, 0, NULL, 0),
(18, 'Default Inventory', '1-2-2-1', 17, 0, 0, NULL, 0, NULL, 0),
(19, 'Account Receivable - Customers', '1-2-3', 13, 0, 0, NULL, 0, NULL, 0),
(20, 'Account Receivable - Cheques On Hand', '1-2-4', 13, 0, 0, NULL, 0, NULL, 0),
(21, 'PDC Receivable', '1-2-4-1', 20, 0, 0, NULL, 0, NULL, 0),
(22, 'RD Receivable Cheques', '1-2-4-2', 20, 0, 0, NULL, 0, NULL, 0),
(23, 'Legal Cheques', '1-2-4-3', 20, 0, 0, NULL, 0, NULL, 0),
(24, 'Cash-On-Hand(Safes)', '1-2-5', 13, 0, 0, NULL, 0, NULL, 0),
(25, 'Bank Accounts', '1-2-6', 13, 0, 0, NULL, 0, NULL, 0),
(26, 'Deferred Taxes', '1-2-7', 13, 0, 0, NULL, 0, NULL, 0),
(27, 'VAT-In', '1-2-7-1', 26, 0, 0, NULL, 0, NULL, 0),
(28, 'Liabilities', '2', 0, 1, 0, NULL, 0, NULL, 0),
(29, 'Non-Current Liability', '2-1', 28, 0, 0, NULL, 0, NULL, 0),
(30, 'Mortgage Loan Payable', '2-1-1', 29, 0, 0, NULL, 0, NULL, 0),
(31, 'Mortgage Loan Payable', '2-1-1-1', 30, 0, 0, NULL, 0, NULL, 0),
(32, 'Othor -Longe term - Liabilities', '2-1-2', 29, 0, 0, NULL, 0, NULL, 0),
(33, 'Othor -Longe term - Liabilities', '2-1-2-1', 32, 0, 0, NULL, 0, NULL, 0),
(34, 'Deferred Taxes', '2-1-3', 29, 0, 0, NULL, 0, NULL, 0),
(35, 'VAT-Out', '2-1-3-1', 34, 0, 0, NULL, 0, NULL, 0),
(36, 'Current Liability', '2-2', 28, 0, 0, NULL, 0, NULL, 0),
(37, 'Account Payables - Suppliers', '2-2-1', 36, 0, 0, NULL, 0, NULL, 0),
(38, 'Accumulated Depreciation ', '2-2-2', 36, 0, 0, NULL, 0, NULL, 0),
(39, 'Accumulated Depreciation ', '2-2-2-1', 38, 0, 0, NULL, 0, NULL, 0),
(40, 'Service Providers', '2-2-3', 36, 0, 0, NULL, 0, NULL, 0),
(41, 'Default Service Provider', '2-2-3-1', 40, 0, 0, NULL, 0, NULL, 0),
(42, 'Account Payable - Cheques', '2-2-4', 36, 0, 0, NULL, 0, NULL, 0),
(43, 'PDC Payables', '2-2-4-1', 42, 0, 0, NULL, 0, NULL, 0),
(44, 'RD Payables Cheques', '2-2-4-2', 43, 0, 0, NULL, 0, NULL, 0),
(45, 'Employees Payments', '2-2-5', 36, 0, 0, NULL, 0, NULL, 0),
(46, 'Payable Salaries', '2-2-5-1', 45, 0, 0, NULL, 0, NULL, 0),
(47, 'Equity', '3', 0, 1, 0, NULL, 0, NULL, 0),
(48, 'Owners Equity', '3-1', 47, 0, 0, NULL, 0, NULL, 0),
(49, 'SHARE HOLDERS A/C', '3-1-1', 48, 0, 0, NULL, 0, NULL, 0),
(50, 'SHARE HOLDERS A/C', '3-1-1-1', 49, 0, 0, NULL, 0, NULL, 0),
(51, 'OWNER CURRENT A/C', '3-1-2', 48, 0, 0, NULL, 0, NULL, 0),
(52, 'OWNER CURRENT A/C', '3-1-2-1', 51, 0, 0, NULL, 0, NULL, 0),
(53, 'PROFIT & LOSS A/C', '3-1-3', 48, 0, 0, NULL, 0, NULL, 0),
(54, 'Retained Profit & Loss', '3-1-3-1', 53, 0, 0, NULL, 0, NULL, 0),
(55, 'Current Profit & Loss', '3-1-3-2', 53, 0, 0, NULL, 0, NULL, 0),
(56, 'Expenses', '4', 0, 1, 0, NULL, 0, NULL, 0),
(57, 'Direct Expenses', '4-1', 56, 0, 0, NULL, 0, NULL, 0),
(58, 'Sales Returns And Allowances', '4-1-1', 57, 0, 0, NULL, 0, NULL, 0),
(59, 'Sales Returns', '4-1-1-1', 58, 0, 0, NULL, 0, NULL, 0),
(60, 'Sales Discount', '4-1-1-2', 58, 0, 0, NULL, 0, NULL, 0),
(61, 'Cost of Revenue', '4-1-2', 57, 0, 0, NULL, 0, NULL, 0),
(62, 'Default Cost Of Good Sold', '4-1-2-1', 61, 0, 0, NULL, 0, NULL, 0),
(63, 'Indirect Expenses', '4-2', 56, 0, 0, NULL, 0, NULL, 0),
(64, 'Employees & Benefits Expenses', '4-2-1', 63, 0, 0, NULL, 0, NULL, 0),
(65, 'Salaries', '4-2-1-1', 64, 0, 0, NULL, 0, NULL, 0),
(66, 'Advance Salaries', '4-2-1-2', 64, 0, 0, NULL, 0, NULL, 0),
(67, 'Income', '5', 0, 1, 0, NULL, 0, NULL, 0),
(68, 'Direct Income', '5-1', 67, 0, 0, NULL, 0, NULL, 0),
(69, 'Sales Revenue', '5-1-1', 68, 0, 0, NULL, 0, NULL, 0),
(70, 'Default Sales', '5-1-1-1', 69, 0, 0, NULL, 0, NULL, 0),
(71, 'Indirect Income', '5-2', 67, 0, 0, NULL, 0, NULL, 0),
(72, 'Other Revenue', '5-2-1', 71, 0, 0, NULL, 0, NULL, 0),
(73, 'Other Revenue', '5-2-1-1', 72, 0, 0, NULL, 0, NULL, 0),
(74, 'GIT', '1-2-8', 13, 0, 0, NULL, 0, NULL, 0),
(75, 'Default Git', '1-2-8-1', 74, 0, 0, NULL, 0, NULL, 0),
(76, 'Bank Muscat', '1-2-6-1', 25, 0, 0, '', NULL, NULL, 0),
(77, 'NBO', '1-2-6-5', 25, 0, 1, '', NULL, NULL, 0),
(78, 'Safe 1', '1-2-5-1', 24, 0, 0, '', NULL, NULL, 0),
(79, 'MEP Sales', '5-1-1-2', 69, 0, 0, '', NULL, NULL, 0),
(80, 'MEP Cost of Goods', '4-1-2-2', 61, 0, 0, '', NULL, NULL, 0),
(81, 'LULU', '2-2-1-1', 37, 0, 0, NULL, 0, NULL, 0),
(82, 'Rental and Bills', '4-1-3', 57, 0, 0, NULL, 0, NULL, 0),
(83, 'Rent', '4-1-3-1', 82, 0, 0, NULL, 0, NULL, 0),
(84, 'Electricity', '4-1-3-2', 82, 0, 0, NULL, 0, NULL, 0),
(85, 'test', '1-2-3-1', 19, 0, 0, NULL, 0, NULL, 0),
(86, 'Khalid', '1-2-3-2', 19, 0, 0, NULL, 0, NULL, 0),
(87, 'test34', '1-2-3-3', 19, 0, 0, NULL, 0, NULL, 0),
(88, 'Salah', '1-2-3-4', 19, 0, 0, NULL, 0, NULL, 0),
(89, 'test33', '1-2-3-5', 19, 0, 0, NULL, 0, NULL, 0),
(90, 'test3', '1-2-3-6', 19, 0, 0, NULL, 0, NULL, 0),
(91, 'Ali2', '1-2-3-7', 19, 0, 0, NULL, 0, NULL, 0),
(92, 'Ashton Cox', '1-2-3-8', 19, 0, 0, NULL, 0, NULL, 0),
(93, 'Bradley Greer', '1-2-3-9', 19, 0, 0, NULL, 0, NULL, 0),
(94, 'Brielle Williamson', '1-2-3-10', 19, 0, 0, NULL, 0, NULL, 0),
(95, 'Bruno Nash', '1-2-3-11', 19, 0, 0, NULL, 0, NULL, 0),
(96, 'Cara Stevens', '1-2-3-12', 19, 0, 0, NULL, 0, NULL, 0),
(97, 'Cedric Kelly', '1-2-3-13', 19, 0, 0, NULL, 0, NULL, 0),
(98, 'Inv Test', '4-1-1-3', 58, 0, 0, '', NULL, NULL, 0),
(99, 'CK client', '1-2-3-14', 19, 0, 0, NULL, 0, NULL, 0),
(100, 'Test', '4-1-4', 57, 0, 0, NULL, 0, NULL, 1),
(101, 'Test2', '4-1-4-1', 100, 0, 0, NULL, 0, NULL, 0),
(102, 'Ashton Cox', '1-2-3-15', 19, 0, 0, NULL, 0, NULL, 0),
(103, 'Cedric12 Kelly12', '1-2-3-16', 19, 0, 0, NULL, 0, NULL, 0),
(104, 'testing12', '1-1-2-2', 5, 0, 0, 'desc', NULL, NULL, 0),
(105, 'testacc', '1-1-2-1-1', 6, 0, 0, 'desc', NULL, NULL, 0),
(106, 'rental and bills 12', '4-1-3-3', 82, 0, 0, NULL, 0, NULL, 0),
(107, 'office expenses ', '4-1-5', 57, 0, 0, NULL, 0, NULL, 0),
(108, 'dfsadf', '4-1-5-1', 107, 0, 0, NULL, 0, NULL, 1),
(109, 'TransWE', '2-2-1-2', 37, 0, 0, NULL, 0, NULL, 0),
(110, 'Intelligent Projects Supp', '2-2-1-3', 37, 0, 0, NULL, 0, NULL, 0),
(111, 'test 22', '4-1-5', 57, 0, 0, NULL, 0, NULL, 1),
(112, 'general name , or expense ', '4-1-5', 57, 0, 0, NULL, 0, NULL, 0),
(113, 'small expense item', '4-1-5-1', 112, 0, 0, NULL, 0, NULL, 0),
(114, 'second small item ', '4-1-5-2', 112, 0, 0, NULL, 0, NULL, 0),
(115, 'Test', '1-1-6', 2, 0, 0, '', NULL, NULL, 1),
(116, 'Saving Safe', '1-2-6-3', 24, 0, 0, '', NULL, NULL, 0),
(117, 'Sara', '1-2-3-17', 19, 0, 0, NULL, 0, NULL, 0),
(118, 'Category1', '4-1-6', 57, 0, 0, NULL, 0, NULL, 0),
(119, 'Item1', '4-1-6-1', 118, 0, 0, NULL, 0, NULL, 0),
(120, 'Item2', '4-1-6-2', 118, 0, 0, NULL, 0, NULL, 0),
(121, 'tesign rreeee', '1-2-3-18', 19, 0, 0, NULL, 0, NULL, 0),
(122, 'Intelligent Projectssdasdsa', '1-2-3-19', 19, 0, 0, NULL, 0, NULL, 0),
(123, 'lead no 1', '1-2-3-20', 19, 0, 0, NULL, 0, NULL, 0),
(124, 'Intelligent Projectssdfsd', '2-2-1-4', 37, 0, 0, NULL, 0, NULL, 0),
(125, 'Doaa', '1-2-3-21', 19, 0, 0, NULL, 0, NULL, 0),
(126, 'Sohar', '1-2-6-3', 25, 0, 0, '', NULL, NULL, 0),
(127, 'Electricity', '4-1-5-1', 107, 0, 0, NULL, 0, NULL, 0),
(128, 'Card for expense', '1-2-6-4', 25, 0, 0, '', NULL, NULL, 0),
(129, 'C1', '1-2-3-22', 19, 0, 0, NULL, 0, NULL, 0),
(130, 'Intelligent Projects3', '2-2-1-5', 37, 0, 0, NULL, 0, NULL, 0),
(131, 'Save 12', '1-2-5-3', 24, 0, 0, '', NULL, NULL, 0),
(132, 'Amal', '1-2-3-23', 19, 0, 0, NULL, 0, NULL, 0),
(133, 'Transportation', '4-1-7', 57, 0, 0, NULL, 0, NULL, 0),
(134, 'Utilities', '4-2-2', 63, 0, 0, NULL, 0, NULL, 0),
(135, 'Car rent', '4-1-7-1', 133, 0, 0, NULL, 0, NULL, 0),
(136, 'Car maintance', '4-1-7-2', 133, 0, 0, NULL, 0, NULL, 0),
(137, 'Electricity', '4-2-2-1', 134, 0, 0, NULL, 0, NULL, 0),
(138, 'Water', '4-2-2-2', 134, 0, 0, NULL, 0, NULL, 0),
(139, 'XYZ LLC', '1-2-3-24', 19, 0, 0, NULL, 0, NULL, 0),
(140, 'Service Income', '5-3', 67, 0, 0, '', NULL, NULL, 0),
(141, 'Electricity', '4-1-5-2', 107, 0, 0, '', NULL, NULL, 0),
(142, 'Internet', '4-1-5-3', 107, 0, 0, '', NULL, NULL, 0),
(143, 'test', '1-3', 1, 0, 0, '', NULL, NULL, 1),
(144, 'Office expenses', '4-1-8', 57, 0, 0, NULL, 0, NULL, 1),
(145, 'Electricity ', '4-1-5-4', 107, 0, 0, NULL, 0, NULL, 0),
(146, 'Sultan Qaboos University ', '1-2-3-25', 19, 0, 0, NULL, 0, NULL, 0),
(147, 'wqefwdfw', '1-2-3-26', 19, 0, 0, NULL, 0, NULL, 0),
(148, 'Ser LLC ', '2-2-1-6', 37, 0, 0, NULL, 0, NULL, 0),
(149, 'Ahmed', '1-2-3-27', 19, 0, 0, NULL, 0, NULL, 0),
(150, 'Test', '4-1-5-5', 107, 0, 0, NULL, 0, NULL, 0),
(151, 'Test', '1-3', 1, 0, 0, '', NULL, NULL, 0),
(152, 'ى', '2-2-1-7', 37, 0, 0, NULL, 0, NULL, 0),
(153, 'Doaa', '1-2-3-28', 19, 0, 0, NULL, 0, NULL, 0),
(154, 'Office supply', '4-1-5-6', 107, 0, 0, NULL, 0, NULL, 0),
(155, 'Robots', '1-1-1-2', 3, 0, 0, 'office robots ', NULL, NULL, 1),
(156, 'Robots', '1-1-1-2', 3, 0, 0, 'this is office robot ', NULL, NULL, 0),
(157, 'account 4', '1-4', 1, 0, 0, '', NULL, NULL, 0),
(158, 'Intelligent Projects', '2-2-1-8', 37, 0, 0, NULL, 0, NULL, 0),
(159, 'team building exp', '4-1-8', 57, 0, 0, NULL, 0, NULL, 0),
(160, 'food', '4-1-8-1', 159, 0, 0, NULL, 0, NULL, 0),
(161, 'osama', '1-2-3-29', 19, 0, 0, NULL, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `action` enum('created','updated','deleted') COLLATE utf8_unicode_ci NOT NULL,
  `log_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `log_type_title` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `log_type_id` int(11) NOT NULL DEFAULT 0,
  `changes` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_for` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `log_for_id` int(11) NOT NULL DEFAULT 0,
  `log_for2` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_for_id2` int(11) DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `share_with` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `files` text COLLATE utf8_unicode_ci NOT NULL,
  `read_by` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `status` enum('incomplete','pending','approved','rejected','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'incomplete',
  `user_id` int(11) NOT NULL,
  `in_time` datetime NOT NULL,
  `out_time` datetime DEFAULT NULL,
  `checked_by` int(11) DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `checked_at` datetime DEFAULT NULL,
  `reject_reason` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budgeting`
--

CREATE TABLE `budgeting` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `estimate_request_id` int(11) NOT NULL DEFAULT 0,
  `estimate_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_email_sent_date` date DEFAULT NULL,
  `status` enum('draft','sent','accepted','declined','approved','request_approval') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `tax_id2` int(11) NOT NULL DEFAULT 0,
  `discount_type` enum('before_tax','after_tax') COLLATE utf8_unicode_ci DEFAULT NULL,
  `discount_amount` double DEFAULT NULL,
  `profit` double DEFAULT NULL,
  `discount_amount_type` enum('percentage','fixed_amount') COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `budgeting_forms`
--

CREATE TABLE `budgeting_forms` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 0,
  `enable_attachment` tinyint(4) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `budgeting_items`
--

CREATE TABLE `budgeting_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `estimate_id` int(11) NOT NULL,
  `tax_id` int(11) DEFAULT 0,
  `tax_id2` int(11) DEFAULT 0,
  `quotation_uom` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `quotation_quantity` double DEFAULT NULL,
  `item_price` double DEFAULT NULL,
  `quotation_selling_rate` double DEFAULT NULL,
  `quotation_total` double DEFAULT NULL,
  `cost_uom` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cost_quantity` double DEFAULT NULL,
  `cost_rate` double DEFAULT NULL,
  `cost_total` double DEFAULT NULL,
  `material_cost` double DEFAULT NULL,
  `labour_cost` double DEFAULT NULL,
  `machinery` double DEFAULT NULL,
  `others` double DEFAULT NULL,
  `profit` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `budgeting_requests`
--

CREATE TABLE `budgeting_requests` (
  `id` int(11) NOT NULL,
  `estimate_form_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `client_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `status` enum('new','processing','hold','canceled','estimated') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `checklist_items`
--

CREATE TABLE `checklist_items` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `is_checked` int(11) NOT NULL DEFAULT 0,
  `task_id` int(11) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` date NOT NULL,
  `website` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `starred_by` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `group_ids` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `is_lead` tinyint(1) NOT NULL DEFAULT 0,
  `lead_status_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `lead_source_id` int(11) NOT NULL,
  `last_lead_status` text COLLATE utf8_unicode_ci NOT NULL,
  `client_migration_date` date NOT NULL,
  `vat_number` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `disable_online_payment` tinyint(1) NOT NULL DEFAULT 0,
  `geo_location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `advance_account_id` int(11) NOT NULL DEFAULT 0,
  `cost_center_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_groups`
--

CREATE TABLE `client_groups` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_title` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` date NOT NULL,
  `created_by` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cost_centers`
--

CREATE TABLE `cost_centers` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cost_centers`
--

INSERT INTO `cost_centers` (`id`, `name`, `currency_id`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 'Default center', 1, '2023-10-24 17:18:08', '2023-10-24 17:18:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `symbol` varchar(16) NOT NULL,
  `rate` double(8,6) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `name`, `symbol`, `rate`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 'omani riyal', 'OMR', 1.000000, '2023-10-21 16:13:26', '2023-10-21 16:45:24', 0);

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `placeholder` text COLLATE utf8_unicode_ci NOT NULL,
  `example_variable_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `options` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `field_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `related_to` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_table` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `show_in_estimate` tinyint(1) NOT NULL DEFAULT 0,
  `visible_to_admins_only` tinyint(1) NOT NULL DEFAULT 0,
  `hide_from_clients` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_field_values`
--

CREATE TABLE `custom_field_values` (
  `id` int(11) NOT NULL,
  `related_to_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `related_to_id` int(11) NOT NULL,
  `custom_field_id` int(11) NOT NULL,
  `value` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `custom_widgets`
--

CREATE TABLE `custom_widgets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `show_title` tinyint(1) NOT NULL DEFAULT 0,
  `show_border` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboards`
--

CREATE TABLE `dashboards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_notes`
--

CREATE TABLE `delivery_notes` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `delivery_note_date` date NOT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('draft','approved','request_approval') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `deleted` tinyint(1) NOT NULL,
  `cost_center_id` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_note_items`
--

CREATE TABLE `delivery_note_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT 0,
  `invoice_item_id` int(11) DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `delivery_note_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_messages`
--

CREATE TABLE `email_messages` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `to` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Draft',
  `date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `template_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email_subject` text COLLATE utf8_unicode_ci NOT NULL,
  `default_message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `custom_message` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enteries`
--

CREATE TABLE `enteries` (
  `id` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `account` int(11) NOT NULL,
  `type` enum('dr','cr') NOT NULL,
  `amount` double NOT NULL,
  `narration` text NOT NULL,
  `branch_id` int(11) DEFAULT 0,
  `concerned_person` int(11) DEFAULT 0,
  `unit` text DEFAULT NULL,
  `reference` int(11) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE `estimates` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `estimate_request_id` int(11) NOT NULL DEFAULT 0,
  `estimate_date` date NOT NULL,
  `valid_until` date NOT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_email_sent_date` date DEFAULT NULL,
  `status` enum('draft','sent','accepted','declined','approved','request_approval') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `tax_id2` int(11) NOT NULL DEFAULT 0,
  `discount_type` enum('before_tax','after_tax') COLLATE utf8_unicode_ci NOT NULL,
  `discount_amount` double NOT NULL,
  `discount_amount_type` enum('percentage','fixed_amount') COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `cost_center_id` int(11) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_forms`
--

CREATE TABLE `estimate_forms` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `public` tinyint(1) NOT NULL DEFAULT 0,
  `enable_attachment` tinyint(4) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_items`
--

CREATE TABLE `estimate_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `discount_amount` double DEFAULT 0,
  `discount_amount_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estimate_id` int(11) NOT NULL,
  `tax_id` int(11) DEFAULT 0,
  `sort` int(11) DEFAULT 0,
  `tax_id2` int(11) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estimate_requests`
--

CREATE TABLE `estimate_requests` (
  `id` int(11) NOT NULL,
  `estimate_form_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `client_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `assigned_to` int(11) NOT NULL,
  `status` enum('new','processing','hold','canceled','estimated') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `location` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `files` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `labels` text COLLATE utf8_unicode_ci NOT NULL,
  `share_with` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `editable_google_event` tinyint(1) NOT NULL DEFAULT 0,
  `google_event_id` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `color` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `recurring` int(1) NOT NULL DEFAULT 0,
  `repeat_every` int(11) NOT NULL DEFAULT 0,
  `repeat_type` enum('days','weeks','months','years') COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_cycles` int(11) NOT NULL DEFAULT 0,
  `last_start_date` date DEFAULT NULL,
  `recurring_dates` longtext COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_by` text COLLATE utf8_unicode_ci NOT NULL,
  `rejected_by` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_date` date NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL,
  `payment_mode` enum('bank','cash_on_hand','cheque','reimbursement','pt_cash') COLLATE utf8_unicode_ci NOT NULL,
  `cheque_due_date` date DEFAULT NULL,
  `cheque_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_account` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_number` int(11) DEFAULT NULL,
  `cheque_transaction_id` int(11) NOT NULL DEFAULT 0,
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `invoice_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `status` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `tax_id2` int(11) NOT NULL DEFAULT 0,
  `transaction_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `bank` int(11) NOT NULL DEFAULT 0,
  `treasury` int(11) NOT NULL DEFAULT 0,
  `pt_cash` int(11) NOT NULL DEFAULT 0,
  `cost_center_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` int(11) NOT NULL,
  `category_parent` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `category_parent`, `title`, `account_id`, `deleted`) VALUES
(1, 57, 'Transportation', 133, 0),
(2, 63, 'Utilities', 134, 0),
(3, 57, 'Office expenses', 144, 0),
(4, 57, 'team building exp', 159, 0);

-- --------------------------------------------------------

--
-- Table structure for table `expense_items`
--

CREATE TABLE `expense_items` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `expense_items`
--

INSERT INTO `expense_items` (`id`, `title`, `parent`, `account_id`, `deleted`) VALUES
(1, 'Car rent', 133, 135, 0),
(2, 'Car maintance', 133, 136, 0),
(3, 'Electricity', 144, 137, 1),
(4, 'Water', 134, 138, 0),
(5, 'Electricity ', 107, 145, 0),
(6, 'Test', 107, 150, 0),
(7, 'Office supply', 107, 154, 0),
(8, 'food', 159, 160, 0);

-- --------------------------------------------------------

--
-- Table structure for table `expires`
--

CREATE TABLE `expires` (
  `id` int(11) NOT NULL,
  `item` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `recurring_charges` int(11) DEFAULT NULL,
  `responsible_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `status` enum('alert','ignore') COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_files`
--

CREATE TABLE `general_files` (
  `id` int(11) NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `file_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_size` double NOT NULL,
  `created_at` datetime NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `eroom_id` int(11) NOT NULL DEFAULT 0,
  `uploaded_by` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_articles`
--

CREATE TABLE `help_articles` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `files` text COLLATE utf8_unicode_ci NOT NULL,
  `total_views` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `help_categories`
--

CREATE TABLE `help_categories` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('help','knowledge_base') COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `internal_transactions`
--

CREATE TABLE `internal_transactions` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `from_employee` int(11) NOT NULL,
  `to_employee` int(11) NOT NULL,
  `amount` float(10,3) NOT NULL,
  `note` text COLLATE utf8_bin NOT NULL,
  `status` enum('draft','approved') COLLATE utf8_bin NOT NULL DEFAULT 'draft',
  `transaction_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `bank` int(11) NOT NULL DEFAULT 0,
  `treasury` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `files` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_email_sent_date` date DEFAULT NULL,
  `status` enum('draft','not_paid','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `delivery_status` enum('delivered','not_delivered') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'not_delivered',
  `approval_status` enum('not_approved','request_approval','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'not_approved',
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `tax_id2` int(11) NOT NULL DEFAULT 0,
  `recurring` tinyint(4) NOT NULL DEFAULT 0,
  `recurring_invoice_id` int(11) NOT NULL DEFAULT 0,
  `repeat_every` int(11) NOT NULL DEFAULT 0,
  `repeat_type` enum('days','weeks','months','years') COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_cycles` int(11) NOT NULL DEFAULT 0,
  `next_recurring_date` date DEFAULT NULL,
  `no_of_cycles_completed` int(11) NOT NULL DEFAULT 0,
  `due_reminder_date` date DEFAULT NULL,
  `recurring_reminder_date` date DEFAULT NULL,
  `discount_amount` double NOT NULL,
  `discount_amount_type` enum('percentage','fixed_amount') COLLATE utf8_unicode_ci NOT NULL,
  `discount_type` enum('before_tax','after_tax') COLLATE utf8_unicode_ci NOT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancelled_by` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `sale_item` int(11) NOT NULL DEFAULT 0,
  `sale_account_id` int(11) NOT NULL DEFAULT 0,
  `sale_cost_account_id` int(11) NOT NULL DEFAULT 0,
  `cost_center_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `cost` double DEFAULT NULL,
  `discount_amount` double DEFAULT 0,
  `discount_amount_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `invoice_id` int(11) NOT NULL,
  `tax_id` int(11) DEFAULT 0,
  `tax_id2` int(11) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payments`
--

CREATE TABLE `invoice_payments` (
  `id` int(11) NOT NULL,
  `files` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `cheque_due_date` date DEFAULT NULL,
  `cheque_description` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_account` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_number` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_transaction_id` int(11) NOT NULL DEFAULT 0,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_id` int(11) NOT NULL,
  `status` enum('draft','request_approval','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `transaction_id` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_transaction_id` int(11) NOT NULL DEFAULT 0,
  `advance_transaction_id` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT 1,
  `bank` int(11) DEFAULT 0,
  `treasury` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cost` double NOT NULL,
  `rate` double NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_type` enum('product','service') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'product',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `cost_center_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items_levels`
--

CREATE TABLE `items_levels` (
  `id` int(11) NOT NULL,
  `level_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `items_levels`
--

INSERT INTO `items_levels` (`id`, `level_name`, `deleted`) VALUES
(1, '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_uom`
--

CREATE TABLE `item_uom` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` date NOT NULL,
  `website` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lead_source`
--

CREATE TABLE `lead_source` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lead_source`
--

INSERT INTO `lead_source` (`id`, `title`, `sort`, `deleted`) VALUES
(1, 'Network', 1, 0),
(2, 'Social media', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `lead_status`
--

CREATE TABLE `lead_status` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `lead_status`
--

INSERT INTO `lead_status` (`id`, `title`, `color`, `sort`, `deleted`) VALUES
(1, 'won', '#83c340', 1, 0),
(2, 'hot ', '#e74c3c', 2, 0),
(3, 'cold ', '#2d9cdb', 0, 0),
(4, 'free', '#2d9cdb', 4, 1),
(5, 'Lost', '#34495e', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leave_applications`
--

CREATE TABLE `leave_applications` (
  `id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_hours` decimal(7,2) NOT NULL,
  `total_days` decimal(5,2) NOT NULL,
  `applicant_id` int(11) NOT NULL,
  `reason` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','canceled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `checked_at` datetime DEFAULT NULL,
  `checked_by` int(11) NOT NULL DEFAULT 0,
  `paid_unpaid` tinyint(1) DEFAULT 1,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `max_allowed` int(11) DEFAULT 1,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `title`, `status`, `color`, `description`, `max_allowed`, `deleted`) VALUES
(1, 'Test', 'active', '#83c340', 'test', 3, 0),
(2, 'ann ', 'active', '#e18a00', 'dvfdsg', 30, 0),
(3, 'sick ', 'active', '#ad159e', 'sdfsdf', 6, 0),
(4, 'Annual Leave', 'active', '#83c340', '', 30, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `date` date DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_request`
--

CREATE TABLE `material_request` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT 0,
  `material_request_date` date NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `labels` text COLLATE utf8_unicode_ci NOT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('draft','not_paid') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `approval_status` enum('not_approved','request_approval','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'not_approved',
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_request_items`
--

CREATE TABLE `material_request_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `material_request_id` int(11) DEFAULT NULL,
  `tax_id` int(11) DEFAULT 0,
  `tax_id2` int(11) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_request_payments`
--

CREATE TABLE `material_request_payments` (
  `id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `cheque_due_date` date DEFAULT NULL,
  `cheque_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_account` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_number` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_transaction_id` int(11) NOT NULL DEFAULT 0,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `material_request_id` int(11) NOT NULL,
  `status` enum('draft','request_approval','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `transaction_id` int(11) DEFAULT 0,
  `prepayment_transaction_id` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Untitled',
  `message` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `status` enum('unread','read') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'unread',
  `message_id` int(11) NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `files` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted_by_users` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nationality`
--

CREATE TABLE `nationality` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `nationality`
--

INSERT INTO `nationality` (`id`, `title`, `deleted`) VALUES
(1, 'Omani', 0),
(2, 'Sudanese', 0),
(3, 'Indian', 0);

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `files` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `notify_to` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `read_by` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `event` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `project_comment_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `ticket_comment_id` int(11) NOT NULL,
  `project_file_id` int(11) NOT NULL,
  `leave_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `activity_log_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `lead_id` int(11) NOT NULL,
  `invoice_payment_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `estimate_id` int(11) NOT NULL,
  `estimate_request_id` int(11) NOT NULL,
  `actual_message_id` int(11) NOT NULL,
  `parent_message_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `announcement_id` int(11) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_settings`
--

CREATE TABLE `notification_settings` (
  `id` int(11) NOT NULL,
  `event` varchar(250) NOT NULL,
  `category` varchar(50) NOT NULL,
  `enable_email` int(1) NOT NULL DEFAULT 0,
  `enable_web` int(1) NOT NULL DEFAULT 0,
  `notify_to_team` text NOT NULL,
  `notify_to_team_members` text NOT NULL,
  `notify_to_terms` text NOT NULL,
  `sort` int(11) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification_settings`
--

INSERT INTO `notification_settings` (`id`, `event`, `category`, `enable_email`, `enable_web`, `notify_to_team`, `notify_to_team_members`, `notify_to_terms`, `sort`, `deleted`) VALUES
(1, 'project_created', 'project', 0, 1, '1', '5', 'client_primary_contact,client_all_contacts', 1, 0),
(2, 'project_deleted', 'project', 0, 1, '1', '5', 'project_members,client_primary_contact,client_all_contacts', 2, 0),
(3, 'project_task_created', 'project', 0, 1, '', '5', 'project_members,task_assignee', 3, 0),
(4, 'project_task_updated', 'project', 0, 1, '', '', 'task_assignee', 4, 0),
(5, 'project_task_assigned', 'project', 0, 1, '', '', 'task_assignee', 5, 0),
(7, 'project_task_started', 'project', 0, 1, '', '5', 'project_members,client_primary_contact,client_all_contacts', 7, 0),
(8, 'project_task_finished', 'project', 0, 0, '', '', '', 8, 0),
(9, 'project_task_reopened', 'project', 0, 0, '', '', '', 9, 0),
(10, 'project_task_deleted', 'project', 0, 1, '', '', 'task_assignee', 10, 0),
(11, 'project_task_commented', 'project', 0, 1, '', '', 'task_assignee', 11, 0),
(12, 'project_member_added', 'project', 0, 1, '', '', 'project_members', 12, 0),
(13, 'project_member_deleted', 'project', 0, 1, '', '', 'project_members', 13, 0),
(14, 'project_file_added', 'project', 0, 1, '', '', 'project_members', 14, 0),
(15, 'project_file_deleted', 'project', 0, 1, '', '', 'project_members', 15, 0),
(16, 'project_file_commented', 'project', 0, 1, '', '', 'project_members', 16, 0),
(17, 'project_comment_added', 'project', 0, 1, '', '', 'project_members', 17, 0),
(18, 'project_comment_replied', 'project', 0, 1, '', '', 'project_members,comment_creator', 18, 0),
(19, 'project_customer_feedback_added', 'project', 0, 1, '', '', 'project_members', 19, 0),
(20, 'project_customer_feedback_replied', 'project', 0, 1, '', '', 'project_members,comment_creator', 20, 0),
(21, 'client_signup', 'client', 0, 0, '', '', '', 21, 0),
(22, 'invoice_online_payment_received', 'invoice', 0, 0, '', '', '', 22, 0),
(23, 'leave_application_submitted', 'leave', 0, 0, '', '', '', 23, 0),
(24, 'leave_approved', 'leave', 0, 1, '', '', 'leave_applicant', 24, 0),
(25, 'leave_assigned', 'leave', 0, 1, '', '', 'leave_applicant', 25, 0),
(26, 'leave_rejected', 'leave', 0, 1, '', '', 'leave_applicant', 26, 0),
(27, 'leave_canceled', 'leave', 0, 0, '', '', '', 27, 0),
(28, 'ticket_created', 'ticket', 0, 1, '', '6,7,5,11,12,1', 'client_all_contacts', 28, 0),
(29, 'ticket_commented', 'ticket', 0, 1, '', '', 'client_primary_contact,ticket_creator', 29, 0),
(30, 'ticket_closed', 'ticket', 0, 1, '', '', 'client_primary_contact,ticket_creator', 30, 0),
(31, 'ticket_reopened', 'ticket', 0, 1, '', '', 'client_primary_contact,ticket_creator', 31, 0),
(32, 'estimate_request_received', 'estimate', 0, 0, '', '', '', 32, 0),
(33, 'estimate_sent', 'estimate', 0, 0, '', '', '', 33, 0),
(34, 'estimate_accepted', 'estimate', 0, 0, '', '', '', 34, 0),
(35, 'estimate_rejected', 'estimate', 0, 0, '', '', '', 35, 0),
(36, 'new_message_sent', 'message', 0, 0, '', '', '', 36, 0),
(37, 'message_reply_sent', 'message', 0, 0, '', '', '', 37, 0),
(38, 'invoice_payment_confirmation', 'invoice', 0, 0, '', '', '', 22, 0),
(39, 'new_event_added_in_calendar', 'event', 0, 0, '', '', '', 39, 0),
(40, 'recurring_invoice_created_vai_cron_job', 'invoice', 0, 0, '', '', '', 22, 0),
(41, 'new_announcement_created', 'announcement', 1, 1, '', '', 'recipient', 41, 0),
(42, 'invoice_due_reminder_before_due_date', 'invoice', 0, 0, '', '', '', 22, 0),
(43, 'invoice_overdue_reminder', 'invoice', 0, 0, '', '', '', 22, 0),
(44, 'recurring_invoice_creation_reminder', 'invoice', 0, 0, '', '', '', 22, 0),
(45, 'project_completed', 'project', 0, 0, '', '', '', 2, 0),
(46, 'lead_created', 'lead', 0, 0, '', '', '', 21, 0),
(47, 'client_created_from_lead', 'lead', 0, 0, '', '', '', 21, 0),
(48, 'project_task_deadline_pre_reminder', 'project', 0, 1, '', '', 'task_assignee', 20, 0),
(49, 'project_task_reminder_on_the_day_of_deadline', 'project', 0, 1, '', '', 'task_assignee', 20, 0),
(50, 'project_task_deadline_overdue_reminder', 'project', 0, 1, '', '', 'task_assignee', 20, 0),
(51, 'recurring_task_created_via_cron_job', 'project', 0, 1, '', '', 'project_members,task_assignee', 20, 0),
(52, 'calendar_event_modified', 'event', 0, 0, '', '', '', 39, 0),
(53, 'the_inventory_item_is_running_low', 'item', 0, 1, '1,2', '1,9,18', '', 49, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'custom',
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `online_payable` tinyint(1) NOT NULL DEFAULT 0,
  `available_on_invoice` tinyint(1) NOT NULL DEFAULT 0,
  `minimum_payment_amount` double NOT NULL DEFAULT 0,
  `settings` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `title`, `type`, `description`, `online_payable`, `available_on_invoice`, `minimum_payment_amount`, `settings`, `deleted`) VALUES
(1, 'Cash', 'custom', '', 0, 0, 0, 'a:0:{}', 0),
(4, 'Bank transfer / Card', 'custom', '', 0, 0, 0, 'a:0:{}', 0),
(5, 'Cheque', 'custom', '', 0, 0, 0, 'a:0:{}', 0);

-- --------------------------------------------------------

--
-- Table structure for table `paypal_ipn`
--

CREATE TABLE `paypal_ipn` (
  `id` int(11) NOT NULL,
  `transaction_id` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `ipn_hash` longtext COLLATE utf8_unicode_ci NOT NULL,
  `ipn_data` longtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `month` date NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `deleted` int(11) NOT NULL DEFAULT 0,
  `payment_item_id` int(11) NOT NULL DEFAULT 0,
  `cost_center_id` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_detail`
--

CREATE TABLE `payroll_detail` (
  `id` int(11) NOT NULL,
  `payroll_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `salary` float(8,3) NOT NULL,
  `manual_deduction` float(8,3) NOT NULL,
  `manual_deduction_reason` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `manual_bounce` float(8,3) NOT NULL,
  `manual_bonus_reason` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `advance` float(10,3) NOT NULL,
  `loan` float(10,3) NOT NULL,
  `pasi_company` double DEFAULT 0,
  `pasi_employee` double DEFAULT 0,
  `job_s_company` double DEFAULT 0,
  `job_s_employee` double DEFAULT 0,
  `files` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(11) NOT NULL,
  `share_with` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `files` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proforma_invoices`
--

CREATE TABLE `proforma_invoices` (
  `id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `bill_date` date NOT NULL,
  `due_date` date NOT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_email_sent_date` date DEFAULT NULL,
  `status` enum('draft','not_paid','cancelled') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `delivery_status` enum('delivered','not_delivered') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'not_delivered',
  `approval_status` enum('not_approved','request_approval','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'not_approved',
  `tax_id` int(11) NOT NULL DEFAULT 0,
  `tax_id2` int(11) NOT NULL DEFAULT 0,
  `recurring` tinyint(4) NOT NULL DEFAULT 0,
  `recurring_invoice_id` int(11) NOT NULL DEFAULT 0,
  `repeat_every` int(11) NOT NULL DEFAULT 0,
  `repeat_type` enum('days','weeks','months','years') COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_cycles` int(11) NOT NULL DEFAULT 0,
  `next_recurring_date` date DEFAULT NULL,
  `no_of_cycles_completed` int(11) NOT NULL DEFAULT 0,
  `due_reminder_date` date DEFAULT NULL,
  `recurring_reminder_date` date DEFAULT NULL,
  `discount_amount` double NOT NULL,
  `discount_amount_type` enum('percentage','fixed_amount') COLLATE utf8_unicode_ci NOT NULL,
  `discount_type` enum('before_tax','after_tax') COLLATE utf8_unicode_ci DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `cancelled_by` int(11) NOT NULL,
  `owner` int(11) NOT NULL,
  `revised` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `old_project` int(11) DEFAULT 0,
  `old_invoice_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `old_quotation_id` int(11) DEFAULT 0,
  `department` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proforma_invoice_items`
--

CREATE TABLE `proforma_invoice_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `cost` double NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `invoice_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `old_id` int(11) DEFAULT 0,
  `tax_id` int(11) DEFAULT 0,
  `tax_id2` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `proforma_invoice_payments`
--

CREATE TABLE `proforma_invoice_payments` (
  `id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `cheque_due_date` date DEFAULT NULL,
  `cheque_description` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_account` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_number` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_transaction_id` int(11) NOT NULL DEFAULT 0,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_id` int(11) NOT NULL,
  `status` enum('draft','request_approval','approved','bounce','deposited','refunded','cancelled','delayed','on_hold') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `transaction_id` tinytext COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_transaction_id` int(11) NOT NULL DEFAULT 0,
  `advance_transaction_id` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `files` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `created_date` date DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT 0,
  `status` enum('open','completed','hold','canceled','pending','') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending',
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` double NOT NULL DEFAULT 0,
  `starred_by` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `estimate_id` int(11) NOT NULL,
  `geo_location` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `cost_center_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_comments`
--

CREATE TABLE `project_comments` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `comment_id` int(11) NOT NULL DEFAULT 0,
  `task_id` int(11) NOT NULL DEFAULT 0,
  `file_id` int(11) NOT NULL DEFAULT 0,
  `customer_feedback_id` int(11) NOT NULL DEFAULT 0,
  `files` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_files`
--

CREATE TABLE `project_files` (
  `id` int(11) NOT NULL,
  `file_name` text COLLATE utf8_unicode_ci NOT NULL,
  `file_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `service_type` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_size` double NOT NULL,
  `created_at` datetime NOT NULL,
  `project_id` int(11) NOT NULL,
  `uploaded_by` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_members`
--

CREATE TABLE `project_members` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `is_leader` tinyint(1) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_settings`
--

CREATE TABLE `project_settings` (
  `project_id` int(11) NOT NULL,
  `setting_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `setting_value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_time`
--

CREATE TABLE `project_time` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('open','logged','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'logged',
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `purchase_order_date` date NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `labels` text COLLATE utf8_unicode_ci NOT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('draft','not_paid') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `approval_status` enum('not_approved','request_approval','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'not_approved',
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL DEFAULT 0,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `quantity` double NOT NULL,
  `unit_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `purchase_order_id` int(11) NOT NULL,
  `tax_id` int(11) DEFAULT 0,
  `tax_id2` int(11) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_payments`
--

CREATE TABLE `purchase_order_payments` (
  `id` int(11) NOT NULL,
  `files` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `cheque_due_date` date DEFAULT NULL,
  `cheque_description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_account` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_number` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cheque_transaction_id` int(11) NOT NULL DEFAULT 0,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `status` enum('draft','request_approval','approved') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'draft',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `transaction_id` int(11) DEFAULT 0,
  `prepayment_transaction_id` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) DEFAULT 1,
  `bank` int(11) NOT NULL DEFAULT 0,
  `treasury` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `shipment_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT 0,
  `payment_method_id` int(11) DEFAULT 0,
  `transaction_id` int(11) DEFAULT 0,
  `date` date NOT NULL,
  `status` enum('draft','approved') COLLATE utf8_unicode_ci DEFAULT 'draft',
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_items`
--

CREATE TABLE `purchase_return_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `po_item_id` int(11) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `purchase_return_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `permissions`, `deleted`) VALUES
(1, 'Sales Rep', 'a:127:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";s:3:\"all\";s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:10:\"accounting\";s:3:\"all\";s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";s:1:\"1\";s:26:\"can_create_purchase_orders\";s:1:\"1\";s:24:\"can_edit_purchase_orders\";s:1:\"1\";s:26:\"can_delete_purchase_orders\";s:1:\"1\";s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:19:\"can_access_expenses\";N;s:19:\"can_create_expenses\";N;s:17:\"can_edit_expenses\";N;s:19:\"can_delete_expenses\";N;s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";s:1:\"1\";s:27:\"can_create_invoice_payments\";s:1:\"1\";s:25:\"can_edit_invoice_payments\";s:1:\"1\";s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";s:1:\"1\";s:34:\"can_create_purchase_order_payments\";s:1:\"1\";s:32:\"can_edit_purchase_order_payments\";s:1:\"1\";s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";s:0:\"\";s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";N;s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";N;s:9:\"estimates\";N;s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1),
(2, 'Accountant', NULL, 1),
(3, 'Project management Role', 'a:143:{s:5:\"leave\";s:0:\"\";s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";s:0:\"\";s:15:\"account_setting\";N;s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";s:1:\"1\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";s:1:\"1\";s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:20:\"can_comment_on_tasks\";s:1:\"1\";s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";s:1:\"1\";s:21:\"can_delete_milestones\";s:1:\"1\";s:16:\"can_delete_files\";s:1:\"1\";s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";s:1:\"1\";s:28:\"can_delete_leave_application\";N;s:19:\"can_add_team_member\";N;s:21:\"can_view_salary_chart\";s:1:\"0\";s:10:\"accounting\";N;s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";N;s:19:\"can_create_expenses\";N;s:17:\"can_edit_expenses\";N;s:19:\"can_delete_expenses\";N;s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";N;s:16:\"can_create_leads\";N;s:14:\"can_edit_leads\";N;s:16:\"can_delete_leads\";N;s:23:\"leads_manage_permission\";N;s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";N;s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";N;s:26:\"can_create_invoices_return\";N;s:24:\"can_edit_invoices_return\";N;s:26:\"can_delete_invoices_return\";N;s:32:\"invoice_return_manage_permission\";N;s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";N;s:9:\"estimates\";N;s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 0),
(4, 'Sales Role', 'a:141:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";s:3:\"all\";s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";s:3:\"all\";s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";s:3:\"all\";s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:10:\"accounting\";s:3:\"all\";s:7:\"payroll\";s:3:\"all\";s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";s:3:\"all\";s:25:\"can_access_delivery_notes\";s:1:\"1\";s:25:\"can_create_delivery_notes\";s:1:\"1\";s:23:\"can_edit_delivery_notes\";s:1:\"1\";s:25:\"can_delete_delivery_notes\";s:1:\"1\";s:31:\"delivery_note_manage_permission\";s:3:\"all\";s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";s:1:\"1\";s:20:\"can_create_suppliers\";s:1:\"1\";s:18:\"can_edit_suppliers\";s:1:\"1\";s:20:\"can_delete_suppliers\";s:1:\"1\";s:26:\"supplier_manage_permission\";s:3:\"all\";s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";s:1:\"1\";s:26:\"can_create_purchase_orders\";s:1:\"1\";s:24:\"can_edit_purchase_orders\";s:1:\"1\";s:26:\"can_delete_purchase_orders\";s:1:\"1\";s:32:\"purchase_order_manage_permission\";s:3:\"all\";s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";s:1:\"1\";s:16:\"can_create_items\";s:1:\"1\";s:14:\"can_edit_items\";s:1:\"1\";s:16:\"can_delete_items\";s:1:\"1\";s:22:\"item_manage_permission\";s:3:\"all\";s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";s:1:\"1\";s:25:\"can_create_items_category\";s:1:\"1\";s:23:\"can_edit_items_category\";s:1:\"1\";s:25:\"can_delete_items_category\";s:1:\"1\";s:31:\"item_category_manage_permission\";s:3:\"all\";s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";s:1:\"1\";s:19:\"can_create_expenses\";s:1:\"1\";s:17:\"can_edit_expenses\";s:1:\"1\";s:19:\"can_delete_expenses\";s:1:\"1\";s:25:\"expense_manage_permission\";s:8:\"specific\";s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";s:1:\"1\";s:27:\"can_create_invoice_payments\";s:1:\"1\";s:25:\"can_edit_invoice_payments\";s:1:\"1\";s:27:\"can_delete_invoice_payments\";s:1:\"1\";s:34:\"can_access_purchase_order_payments\";s:1:\"1\";s:34:\"can_create_purchase_order_payments\";s:1:\"1\";s:32:\"can_edit_purchase_order_payments\";s:1:\"1\";s:34:\"can_delete_purchase_order_payments\";s:1:\"1\";s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";s:1:\"1\";s:18:\"can_create_clients\";s:1:\"1\";s:16:\"can_edit_clients\";s:1:\"1\";s:18:\"can_delete_clients\";s:1:\"1\";s:24:\"client_manage_permission\";s:3:\"all\";s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";s:1:\"1\";s:16:\"can_create_leads\";s:1:\"1\";s:14:\"can_edit_leads\";s:1:\"1\";s:16:\"can_delete_leads\";s:1:\"1\";s:23:\"leads_manage_permission\";s:3:\"all\";s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";s:1:\"1\";s:19:\"can_create_invoices\";s:1:\"1\";s:17:\"can_edit_invoices\";s:1:\"1\";s:19:\"can_delete_invoices\";s:1:\"1\";s:25:\"invoice_manage_permission\";s:3:\"all\";s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";s:1:\"1\";s:26:\"can_create_invoices_return\";N;s:24:\"can_edit_invoices_return\";N;s:26:\"can_delete_invoices_return\";N;s:32:\"invoice_return_manage_permission\";s:3:\"all\";s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";s:1:\"1\";s:20:\"can_create_estimates\";s:1:\"1\";s:18:\"can_edit_estimates\";s:1:\"1\";s:20:\"can_delete_estimates\";s:1:\"1\";s:26:\"estimate_manage_permission\";s:3:\"all\";s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";s:3:\"all\";s:9:\"estimates\";s:3:\"all\";s:8:\"invoices\";s:3:\"all\";s:16:\"invoice_payments\";s:3:\"all\";s:14:\"delivery_notes\";s:3:\"all\";s:15:\"purchase_orders\";s:3:\"all\";s:23:\"purchase_order_payments\";s:3:\"all\";s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1),
(5, 'Finance Role', 'a:143:{s:5:\"leave\";s:0:\"\";s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";s:0:\"\";s:15:\"account_setting\";s:3:\"all\";s:16:\"final_settelment\";s:0:\"\";s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";s:0:\"\";s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";s:3:\"all\";s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";s:0:\"\";s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";s:0:\"\";s:23:\"can_manage_all_projects\";s:1:\"1\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";s:0:\"\";s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";s:1:\"0\";s:22:\"hide_team_members_list\";s:1:\"1\";s:28:\"can_delete_leave_application\";N;s:19:\"can_add_team_member\";N;s:21:\"can_view_salary_chart\";s:1:\"1\";s:10:\"accounting\";s:3:\"all\";s:7:\"payroll\";s:3:\"all\";s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";s:3:\"all\";s:25:\"can_access_delivery_notes\";s:1:\"1\";s:25:\"can_create_delivery_notes\";s:1:\"1\";s:23:\"can_edit_delivery_notes\";s:1:\"1\";s:25:\"can_delete_delivery_notes\";s:1:\"1\";s:31:\"delivery_note_manage_permission\";s:3:\"all\";s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";s:1:\"1\";s:20:\"can_create_suppliers\";s:1:\"1\";s:18:\"can_edit_suppliers\";s:1:\"1\";s:20:\"can_delete_suppliers\";s:1:\"1\";s:26:\"supplier_manage_permission\";s:3:\"all\";s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";s:1:\"1\";s:26:\"can_create_purchase_orders\";s:1:\"1\";s:24:\"can_edit_purchase_orders\";s:1:\"1\";s:26:\"can_delete_purchase_orders\";s:1:\"1\";s:32:\"purchase_order_manage_permission\";s:3:\"all\";s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";s:1:\"1\";s:16:\"can_create_items\";s:1:\"1\";s:14:\"can_edit_items\";s:1:\"1\";s:16:\"can_delete_items\";s:1:\"1\";s:22:\"item_manage_permission\";s:3:\"all\";s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";s:1:\"1\";s:25:\"can_create_items_category\";s:1:\"1\";s:23:\"can_edit_items_category\";s:1:\"1\";s:25:\"can_delete_items_category\";s:1:\"1\";s:31:\"item_category_manage_permission\";s:3:\"all\";s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";s:1:\"1\";s:19:\"can_create_expenses\";s:1:\"1\";s:17:\"can_edit_expenses\";s:1:\"1\";s:19:\"can_delete_expenses\";s:1:\"1\";s:25:\"expense_manage_permission\";s:3:\"all\";s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";s:1:\"1\";s:27:\"can_create_invoice_payments\";s:1:\"1\";s:25:\"can_edit_invoice_payments\";s:1:\"1\";s:27:\"can_delete_invoice_payments\";s:1:\"1\";s:34:\"can_access_purchase_order_payments\";s:1:\"1\";s:34:\"can_create_purchase_order_payments\";s:1:\"1\";s:32:\"can_edit_purchase_order_payments\";s:1:\"1\";s:34:\"can_delete_purchase_order_payments\";s:1:\"1\";s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";s:1:\"1\";s:18:\"can_create_clients\";s:1:\"1\";s:16:\"can_edit_clients\";s:1:\"1\";s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";s:3:\"all\";s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";s:1:\"1\";s:16:\"can_create_leads\";s:1:\"1\";s:14:\"can_edit_leads\";s:1:\"1\";s:16:\"can_delete_leads\";s:1:\"1\";s:23:\"leads_manage_permission\";N;s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";s:1:\"1\";s:19:\"can_create_invoices\";s:1:\"1\";s:17:\"can_edit_invoices\";s:1:\"1\";s:19:\"can_delete_invoices\";s:1:\"1\";s:25:\"invoice_manage_permission\";s:3:\"all\";s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";s:1:\"1\";s:26:\"can_create_invoices_return\";s:1:\"1\";s:24:\"can_edit_invoices_return\";s:1:\"1\";s:26:\"can_delete_invoices_return\";s:1:\"1\";s:32:\"invoice_return_manage_permission\";N;s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";s:1:\"1\";s:20:\"can_create_estimates\";s:1:\"1\";s:18:\"can_edit_estimates\";s:1:\"1\";s:20:\"can_delete_estimates\";s:1:\"1\";s:26:\"estimate_manage_permission\";s:3:\"all\";s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";s:0:\"\";s:9:\"estimates\";s:0:\"\";s:8:\"invoices\";s:3:\"all\";s:16:\"invoice_payments\";s:3:\"all\";s:14:\"delivery_notes\";s:3:\"all\";s:15:\"purchase_orders\";s:3:\"all\";s:23:\"purchase_order_payments\";s:3:\"all\";s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 0),
(6, 'Finance Manager Role  ', 'a:129:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";N;s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:10:\"petty_cash\";s:3:\"all\";s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:10:\"accounting\";N;s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";s:1:\"1\";s:19:\"can_create_expenses\";s:1:\"1\";s:17:\"can_edit_expenses\";s:1:\"1\";s:19:\"can_delete_expenses\";s:1:\"1\";s:25:\"expense_manage_permission\";s:3:\"all\";s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";s:1:\"1\";s:27:\"can_create_invoice_payments\";s:1:\"1\";s:25:\"can_edit_invoice_payments\";s:1:\"1\";s:27:\"can_delete_invoice_payments\";s:1:\"1\";s:34:\"can_access_purchase_order_payments\";s:1:\"1\";s:34:\"can_create_purchase_order_payments\";s:1:\"1\";s:32:\"can_edit_purchase_order_payments\";s:1:\"1\";s:34:\"can_delete_purchase_order_payments\";s:1:\"1\";s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";s:0:\"\";s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";N;s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";N;s:9:\"estimates\";N;s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1),
(7, 'HR Department Role', 'a:143:{s:5:\"leave\";s:3:\"all\";s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";s:3:\"all\";s:15:\"account_setting\";s:3:\"all\";s:16:\"final_settelment\";s:0:\"\";s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";s:3:\"all\";s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";s:3:\"all\";s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";s:3:\"all\";s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";s:1:\"1\";s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";s:1:\"1\";s:19:\"can_add_team_member\";s:1:\"1\";s:21:\"can_view_salary_chart\";s:1:\"1\";s:10:\"accounting\";s:0:\"\";s:7:\"payroll\";s:3:\"all\";s:5:\"eroom\";N;s:4:\"logs\";s:3:\"all\";s:7:\"reports\";s:3:\"all\";s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";N;s:19:\"can_create_expenses\";N;s:17:\"can_edit_expenses\";N;s:19:\"can_delete_expenses\";N;s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";N;s:16:\"can_create_leads\";N;s:14:\"can_edit_leads\";N;s:16:\"can_delete_leads\";N;s:23:\"leads_manage_permission\";N;s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";N;s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";N;s:26:\"can_create_invoices_return\";N;s:24:\"can_edit_invoices_return\";N;s:26:\"can_delete_invoices_return\";N;s:32:\"invoice_return_manage_permission\";N;s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";s:0:\"\";s:9:\"estimates\";s:0:\"\";s:8:\"invoices\";N;s:16:\"invoice_payments\";s:0:\"\";s:14:\"delivery_notes\";s:0:\"\";s:15:\"purchase_orders\";s:0:\"\";s:23:\"purchase_order_payments\";s:0:\"\";s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 0),
(8, 'Project management Role 2 ', 'a:127:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";N;s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:10:\"accounting\";s:3:\"all\";s:7:\"payroll\";s:3:\"all\";s:5:\"eroom\";s:3:\"all\";s:4:\"logs\";s:3:\"all\";s:7:\"reports\";s:3:\"all\";s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:19:\"can_access_expenses\";N;s:19:\"can_create_expenses\";N;s:17:\"can_edit_expenses\";N;s:19:\"can_delete_expenses\";N;s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";s:0:\"\";s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";N;s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";N;s:9:\"estimates\";N;s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1),
(9, 'team memberww', 'a:127:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";N;s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";s:3:\"all\";s:8:\"expiries\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:10:\"accounting\";N;s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:19:\"can_access_expenses\";s:1:\"1\";s:19:\"can_create_expenses\";s:1:\"1\";s:17:\"can_edit_expenses\";s:1:\"1\";s:19:\"can_delete_expenses\";s:1:\"1\";s:25:\"expense_manage_permission\";s:3:\"all\";s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";s:1:\"1\";s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";s:1:\"1\";s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";s:0:\"\";s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";N;s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";s:3:\"all\";s:9:\"estimates\";s:3:\"all\";s:8:\"invoices\";s:3:\"all\";s:16:\"invoice_payments\";s:3:\"all\";s:14:\"delivery_notes\";s:3:\"all\";s:15:\"purchase_orders\";s:3:\"all\";s:23:\"purchase_order_payments\";s:3:\"all\";s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1),
(10, 'sales Person ', 'a:127:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";N;s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:6:\"client\";N;s:4:\"lead\";s:3:\"all\";s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";s:0:\"\";s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:10:\"accounting\";N;s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:19:\"can_access_expenses\";N;s:19:\"can_create_expenses\";N;s:17:\"can_edit_expenses\";N;s:19:\"can_delete_expenses\";N;s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";s:1:\"1\";s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";s:0:\"\";s:18:\"can_access_clients\";s:1:\"1\";s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";s:1:\"1\";s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";s:1:\"1\";s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";s:3:\"all\";s:8:\"discount\";i:0;s:8:\"expenses\";N;s:9:\"estimates\";N;s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1),
(11, 'sales 12', 'a:129:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";N;s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";s:3:\"all\";s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";N;s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";s:1:\"1\";s:28:\"can_delete_leave_application\";N;s:10:\"accounting\";N;s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";N;s:19:\"can_create_expenses\";N;s:17:\"can_edit_expenses\";N;s:19:\"can_delete_expenses\";N;s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";s:1:\"1\";s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";s:0:\"\";s:18:\"can_access_clients\";s:1:\"1\";s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";s:1:\"1\";s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";s:1:\"1\";s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";N;s:9:\"estimates\";N;s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1);
INSERT INTO `roles` (`id`, `title`, `permissions`, `deleted`) VALUES
(12, 'test m.ali', 'a:141:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";s:3:\"all\";s:15:\"account_setting\";s:3:\"all\";s:16:\"final_settelment\";s:0:\"\";s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";s:0:\"\";s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";s:0:\"\";s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";s:3:\"all\";s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";s:3:\"all\";s:23:\"can_manage_all_projects\";s:1:\"0\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";s:1:\"0\";s:22:\"hide_team_members_list\";s:1:\"1\";s:28:\"can_delete_leave_application\";N;s:10:\"accounting\";s:0:\"\";s:7:\"payroll\";s:3:\"all\";s:5:\"eroom\";N;s:4:\"logs\";s:0:\"\";s:7:\"reports\";s:0:\"\";s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";s:3:\"all\";s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";s:3:\"all\";s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";s:1:\"1\";s:19:\"can_create_expenses\";s:1:\"1\";s:17:\"can_edit_expenses\";s:1:\"1\";s:19:\"can_delete_expenses\";s:1:\"1\";s:25:\"expense_manage_permission\";s:8:\"specific\";s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";s:1:\"1\";s:34:\"can_create_purchase_order_payments\";s:1:\"1\";s:32:\"can_edit_purchase_order_payments\";s:1:\"1\";s:34:\"can_delete_purchase_order_payments\";s:1:\"1\";s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";s:3:\"all\";s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";N;s:16:\"can_create_leads\";N;s:14:\"can_edit_leads\";N;s:16:\"can_delete_leads\";N;s:23:\"leads_manage_permission\";N;s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";s:1:\"1\";s:19:\"can_create_invoices\";s:1:\"1\";s:17:\"can_edit_invoices\";s:1:\"1\";s:19:\"can_delete_invoices\";s:1:\"1\";s:25:\"invoice_manage_permission\";s:3:\"all\";s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";N;s:26:\"can_create_invoices_return\";N;s:24:\"can_edit_invoices_return\";N;s:26:\"can_delete_invoices_return\";N;s:32:\"invoice_return_manage_permission\";N;s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";s:2:\"60\";s:8:\"expenses\";s:3:\"all\";s:9:\"estimates\";s:3:\"all\";s:8:\"invoices\";s:0:\"\";s:16:\"invoice_payments\";s:0:\"\";s:14:\"delivery_notes\";s:3:\"all\";s:15:\"purchase_orders\";s:3:\"all\";s:23:\"purchase_order_payments\";s:0:\"\";s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1),
(13, 'R33', NULL, 1),
(14, 'test ', NULL, 1),
(15, 'HR test tech', 'a:141:{s:5:\"leave\";s:3:\"all\";s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";s:3:\"all\";s:15:\"account_setting\";N;s:16:\"final_settelment\";s:3:\"all\";s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";s:3:\"all\";s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";s:3:\"all\";s:23:\"can_manage_all_projects\";s:1:\"1\";s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";s:1:\"1\";s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";s:3:\"all\";s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";s:1:\"1\";s:22:\"hide_team_members_list\";s:1:\"1\";s:28:\"can_delete_leave_application\";s:1:\"1\";s:10:\"accounting\";N;s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";N;s:19:\"can_create_expenses\";N;s:17:\"can_edit_expenses\";N;s:19:\"can_delete_expenses\";N;s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";N;s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";N;s:16:\"can_create_leads\";N;s:14:\"can_edit_leads\";N;s:16:\"can_delete_leads\";N;s:23:\"leads_manage_permission\";N;s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";N;s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";N;s:26:\"can_create_invoices_return\";N;s:24:\"can_edit_invoices_return\";N;s:26:\"can_delete_invoices_return\";N;s:32:\"invoice_return_manage_permission\";N;s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";N;s:9:\"estimates\";N;s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 1),
(16, 'test', NULL, 0),
(17, 'Sales', 'a:143:{s:5:\"leave\";s:3:\"all\";s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";s:3:\"all\";s:15:\"account_setting\";s:3:\"all\";s:16:\"final_settelment\";s:3:\"all\";s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";s:3:\"all\";s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";s:3:\"all\";s:23:\"can_manage_all_projects\";s:1:\"1\";s:19:\"can_create_projects\";N;s:17:\"can_edit_projects\";N;s:19:\"can_delete_projects\";N;s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";N;s:14:\"can_edit_tasks\";N;s:16:\"can_delete_tasks\";N;s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";N;s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";s:3:\"all\";s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";s:1:\"1\";s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:19:\"can_add_team_member\";N;s:21:\"can_view_salary_chart\";s:1:\"1\";s:10:\"accounting\";s:3:\"all\";s:7:\"payroll\";s:3:\"all\";s:5:\"eroom\";N;s:4:\"logs\";s:3:\"all\";s:7:\"reports\";s:3:\"all\";s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";N;s:19:\"can_create_expenses\";N;s:17:\"can_edit_expenses\";N;s:19:\"can_delete_expenses\";N;s:25:\"expense_manage_permission\";s:3:\"all\";s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";N;s:18:\"can_create_clients\";N;s:16:\"can_edit_clients\";N;s:18:\"can_delete_clients\";N;s:24:\"client_manage_permission\";s:3:\"all\";s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";N;s:16:\"can_create_leads\";s:1:\"1\";s:14:\"can_edit_leads\";s:1:\"1\";s:16:\"can_delete_leads\";s:1:\"1\";s:23:\"leads_manage_permission\";s:3:\"all\";s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";N;s:19:\"can_create_invoices\";N;s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";s:3:\"all\";s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";N;s:26:\"can_create_invoices_return\";s:1:\"1\";s:24:\"can_edit_invoices_return\";s:1:\"1\";s:26:\"can_delete_invoices_return\";s:1:\"1\";s:32:\"invoice_return_manage_permission\";s:3:\"all\";s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";N;s:20:\"can_create_estimates\";N;s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";s:3:\"all\";s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";s:3:\"all\";s:9:\"estimates\";N;s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 0),
(18, 'Accounting', 'a:143:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";N;s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";s:1:\"0\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:19:\"can_add_team_member\";N;s:21:\"can_view_salary_chart\";N;s:10:\"accounting\";N;s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";s:1:\"1\";s:19:\"can_create_expenses\";s:1:\"1\";s:17:\"can_edit_expenses\";s:1:\"1\";s:19:\"can_delete_expenses\";s:1:\"1\";s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";s:1:\"1\";s:18:\"can_create_clients\";s:1:\"1\";s:16:\"can_edit_clients\";s:1:\"1\";s:18:\"can_delete_clients\";s:1:\"1\";s:24:\"client_manage_permission\";s:8:\"specific\";s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";s:1:\"1\";s:16:\"can_create_leads\";s:1:\"1\";s:14:\"can_edit_leads\";s:1:\"1\";s:16:\"can_delete_leads\";s:1:\"1\";s:23:\"leads_manage_permission\";N;s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";s:1:\"1\";s:19:\"can_create_invoices\";s:1:\"1\";s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";s:1:\"1\";s:26:\"can_create_invoices_return\";s:1:\"1\";s:24:\"can_edit_invoices_return\";s:1:\"1\";s:26:\"can_delete_invoices_return\";s:1:\"1\";s:32:\"invoice_return_manage_permission\";N;s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";s:1:\"1\";s:20:\"can_create_estimates\";s:1:\"1\";s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";s:3:\"all\";s:9:\"estimates\";s:0:\"\";s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 0),
(19, 'role t', NULL, 0),
(20, 'acc 2', 'a:143:{s:5:\"leave\";N;s:14:\"leave_specific\";s:0:\"\";s:10:\"attendance\";N;s:19:\"attendance_specific\";s:0:\"\";s:7:\"invoice\";N;s:8:\"job_info\";N;s:15:\"account_setting\";N;s:16:\"final_settelment\";N;s:8:\"estimate\";N;s:7:\"expense\";N;s:8:\"expiries\";N;s:10:\"petty_cash\";N;s:6:\"client\";N;s:4:\"lead\";N;s:6:\"ticket\";N;s:15:\"ticket_specific\";s:0:\"\";s:12:\"announcement\";N;s:17:\"approve_budgeting\";N;s:23:\"help_and_knowledge_base\";N;s:23:\"can_manage_all_projects\";s:1:\"0\";s:19:\"can_create_projects\";s:1:\"1\";s:17:\"can_edit_projects\";s:1:\"1\";s:19:\"can_delete_projects\";s:1:\"1\";s:30:\"can_add_remove_project_members\";N;s:16:\"can_create_tasks\";s:1:\"1\";s:14:\"can_edit_tasks\";s:1:\"1\";s:16:\"can_delete_tasks\";s:1:\"1\";s:20:\"can_comment_on_tasks\";N;s:24:\"show_assigned_tasks_only\";N;s:21:\"can_create_milestones\";s:1:\"1\";s:19:\"can_edit_milestones\";N;s:21:\"can_delete_milestones\";N;s:16:\"can_delete_files\";N;s:34:\"can_view_team_members_contact_info\";N;s:34:\"can_view_team_members_social_links\";N;s:29:\"team_member_update_permission\";N;s:38:\"team_member_update_permission_specific\";s:0:\"\";s:27:\"timesheet_manage_permission\";N;s:36:\"timesheet_manage_permission_specific\";N;s:21:\"disable_event_sharing\";N;s:22:\"hide_team_members_list\";N;s:28:\"can_delete_leave_application\";N;s:19:\"can_add_team_member\";N;s:21:\"can_view_salary_chart\";N;s:10:\"accounting\";N;s:7:\"payroll\";N;s:5:\"eroom\";N;s:4:\"logs\";N;s:7:\"reports\";N;s:25:\"can_access_delivery_notes\";N;s:25:\"can_create_delivery_notes\";N;s:23:\"can_edit_delivery_notes\";N;s:25:\"can_delete_delivery_notes\";N;s:31:\"delivery_note_manage_permission\";N;s:40:\"delivery_note_manage_permission_specific\";s:0:\"\";s:20:\"can_access_suppliers\";N;s:20:\"can_create_suppliers\";N;s:18:\"can_edit_suppliers\";N;s:20:\"can_delete_suppliers\";N;s:26:\"supplier_manage_permission\";N;s:35:\"supplier_manage_permission_specific\";s:0:\"\";s:26:\"can_access_purchase_orders\";N;s:26:\"can_create_purchase_orders\";N;s:24:\"can_edit_purchase_orders\";N;s:26:\"can_delete_purchase_orders\";N;s:32:\"purchase_order_manage_permission\";N;s:41:\"purchase_order_manage_permission_specific\";s:0:\"\";s:16:\"can_access_items\";N;s:16:\"can_create_items\";N;s:14:\"can_edit_items\";N;s:16:\"can_delete_items\";N;s:22:\"item_manage_permission\";N;s:31:\"item_manage_permission_specific\";s:0:\"\";s:25:\"can_access_items_category\";N;s:25:\"can_create_items_category\";N;s:23:\"can_edit_items_category\";N;s:25:\"can_delete_items_category\";N;s:31:\"item_category_manage_permission\";N;s:40:\"item_category_manage_permission_specific\";s:0:\"\";s:19:\"can_access_expiries\";N;s:21:\"can_access_petty_cash\";N;s:19:\"can_access_expenses\";s:1:\"1\";s:19:\"can_create_expenses\";s:1:\"1\";s:17:\"can_edit_expenses\";s:1:\"1\";s:19:\"can_delete_expenses\";s:1:\"1\";s:25:\"expense_manage_permission\";N;s:34:\"expense_manage_permission_specific\";s:0:\"\";s:20:\"internal_transaction\";N;s:27:\"can_access_invoice_payments\";N;s:27:\"can_create_invoice_payments\";N;s:25:\"can_edit_invoice_payments\";N;s:27:\"can_delete_invoice_payments\";N;s:34:\"can_access_purchase_order_payments\";N;s:34:\"can_create_purchase_order_payments\";N;s:32:\"can_edit_purchase_order_payments\";N;s:34:\"can_delete_purchase_order_payments\";N;s:19:\"can_access_contacts\";N;s:19:\"can_create_contacts\";N;s:17:\"can_edit_contacts\";N;s:19:\"can_delete_contacts\";N;s:25:\"contact_manage_permission\";N;s:34:\"contact_manage_permission_specific\";N;s:18:\"can_access_clients\";s:1:\"1\";s:18:\"can_create_clients\";s:1:\"1\";s:16:\"can_edit_clients\";s:1:\"1\";s:18:\"can_delete_clients\";s:1:\"1\";s:24:\"client_manage_permission\";s:8:\"specific\";s:33:\"client_manage_permission_specific\";s:0:\"\";s:16:\"can_access_leads\";s:1:\"1\";s:16:\"can_create_leads\";s:1:\"1\";s:14:\"can_edit_leads\";s:1:\"1\";s:16:\"can_delete_leads\";s:1:\"1\";s:23:\"leads_manage_permission\";N;s:32:\"leads_manage_permission_specific\";s:0:\"\";s:19:\"can_access_invoices\";s:1:\"1\";s:19:\"can_create_invoices\";s:1:\"1\";s:17:\"can_edit_invoices\";N;s:19:\"can_delete_invoices\";N;s:25:\"invoice_manage_permission\";N;s:34:\"invoice_manage_permission_specific\";s:0:\"\";s:26:\"can_access_invoices_return\";s:1:\"1\";s:26:\"can_create_invoices_return\";s:1:\"1\";s:24:\"can_edit_invoices_return\";s:1:\"1\";s:26:\"can_delete_invoices_return\";s:1:\"1\";s:32:\"invoice_return_manage_permission\";N;s:41:\"invoice_return_manage_permission_specific\";s:0:\"\";s:20:\"can_access_estimates\";s:1:\"1\";s:20:\"can_create_estimates\";s:1:\"1\";s:18:\"can_edit_estimates\";N;s:20:\"can_delete_estimates\";N;s:26:\"estimate_manage_permission\";N;s:35:\"estimate_manage_permission_specific\";s:0:\"\";s:16:\"estimate_request\";N;s:8:\"discount\";i:0;s:8:\"expenses\";s:3:\"all\";s:9:\"estimates\";s:0:\"\";s:8:\"invoices\";N;s:16:\"invoice_payments\";N;s:14:\"delivery_notes\";N;s:15:\"purchase_orders\";N;s:23:\"purchase_order_payments\";N;s:8:\"payrolls\";N;s:21:\"internal_transactions\";N;}', 0),
(21, 'Management', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `salary_advance`
--

CREATE TABLE `salary_advance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` float NOT NULL DEFAULT 0,
  `date` date DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_items`
--

CREATE TABLE `sales_items` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `sale_account_id` int(11) NOT NULL,
  `sale_cost_account_id` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `payment_method_id` int(11) DEFAULT 0,
  `transaction_id` int(11) DEFAULT 0,
  `date` date NOT NULL,
  `status` enum('draft','approved') COLLATE utf8_unicode_ci DEFAULT 'draft',
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_items`
--

CREATE TABLE `sale_return_items` (
  `id` int(11) NOT NULL,
  `sale_return_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `invoice_item_id` int(11) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `setting_value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'app',
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_name`, `setting_value`, `type`, `deleted`) VALUES
('accepted_file_formats', 'jpg,jpeg,doc,pdf,csv,pptx,xlsx,docx,png,rar', 'app', 0),
('allow_partial_invoice_payment_from_clients', '', 'app', 0),
('allowed_ip_addresses', '', 'app', 0),
('app_title', 'Teamway', 'app', 0),
('attendance_password', '94269935', 'app', 0),
('banks_accounts_parent', '25', 'app', 0),
('cash_on_hand_accounts_parent', '24', 'app', 0),
('client_can_add_project_files', '1', 'app', 0),
('client_can_comment_on_files', '1', 'app', 0),
('client_can_comment_on_tasks', '1', 'app', 0),
('client_can_create_projects', '1', 'app', 0),
('client_can_create_tasks', '1', 'app', 0),
('client_can_edit_tasks', '1', 'app', 0),
('client_can_view_activity', '1', 'app', 0),
('client_can_view_gantt', '1', 'app', 0),
('client_can_view_milestones', '1', 'app', 0),
('client_can_view_overview', '1', 'app', 0),
('client_can_view_project_files', '1', 'app', 0),
('client_can_view_tasks', '1', 'app', 0),
('client_default_dashboard', '', 'app', 0),
('client_message_own_contacts', '1', 'app', 0),
('client_message_users', '5', 'app', 0),
('clients_accounts_parent', '19', 'app', 0),
('clients_parent', '', 'app', 0),
('company_address', '', 'app', 0),
('company_email', 'info@teamway', 'app', 0),
('company_name', 'Teamway', 'app', 0),
('company_phone', '123456', 'app', 0),
('company_vat_number', '', 'app', 0),
('company_website', 'teamway.om', 'app', 0),
('cost_of_goods_sold', '62', 'app', 0),
('currency_position', 'right', 'app', 0),
('currency_symbol', ' /OMR', 'app', 0),
('date_format', 'd-m-Y', 'app', 0),
('decimal_separator', '.', 'app', 0),
('default_bank', '61', 'app', 0),
('default_cash_on_hand', '12', 'app', 0),
('default_currency', 'OMR', 'app', 0),
('default_due_date_after_billing_date', '30', 'app', 0),
('default_git', '75', 'app', 0),
('default_inventory', '18', 'app', 0),
('default_sales', '70', 'app', 0),
('disable_client_login', '1', 'app', 0),
('disable_client_signup', '1', 'app', 0),
('discount', '60', 'app', 0),
('email_protocol', '', 'app', 0),
('email_sent_from_address', 'info@teamway.om', 'app', 0),
('email_sent_from_name', 'Teamway', 'app', 0),
('email_smtp_host', '', 'app', 0),
('email_smtp_pass', '', 'app', 0),
('email_smtp_port', '', 'app', 0),
('email_smtp_security_type', 'none', 'app', 0),
('email_smtp_user', '', 'app', 0),
('enable_attendance', '', 'app', 0),
('enable_email_piping', '', 'app', 0),
('enable_google_calendar_api', '', 'app', 0),
('enable_push_notification', '', 'app', 0),
('enable_rich_text_editor', '0', 'app', 0),
('estimate_color', '#054d7f ', 'app', 0),
('estimate_logo', 'a:1:{s:9:\"file_name\";s:36:\"_file639ed85f5a902-estimate-logo.png\";}', 'app', 0),
('expenses_accounts_parent', '56', 'app', 0),
('favicon', 'a:1:{s:9:\"file_name\";s:30:\"_file64341cd8d6923-favicon.png\";}', 'app', 0),
('financial_year_end', '2022-01-01', 'app', 0),
('first_day_of_week', '0', 'app', 0),
('hidden_client_menus', '', 'app', 0),
('imap_email', 'info@teamway.om', 'app', 0),
('imap_host', '', 'app', 0),
('imap_password', '00000', 'app', 0),
('imap_port', '', 'app', 0),
('imap_ssl_enabled', '1', 'app', 0),
('initial_number_of_the_estimate', '11', 'app', 0),
('initial_number_of_the_invoice', '39', 'app', 0),
('invoice_color', '#054d7f ', 'app', 0),
('invoice_footer', '<p><br></p>', 'app', 0),
('invoice_logo', 'a:1:{s:9:\"file_name\";s:35:\"_file65213fd597da8-invoice-logo.png\";}', 'app', 0),
('invoice_prefix', 'TAX INVOICE', 'app', 0),
('item_purchase_code', '53434654', 'app', 0),
('language', 'english', 'app', 0),
('leave_salary_expenses', '4', 'app', 0),
('module_announcement', '1', 'app', 0),
('module_attendance', '', 'app', 0),
('module_chat', '1', 'app', 0),
('module_estimate', '1', 'app', 0),
('module_estimate_request', '', 'app', 0),
('module_event', '1', 'app', 0),
('module_expense', '1', 'app', 0),
('module_expires', '', 'app', 0),
('module_help', '1', 'app', 0),
('module_invoice', '1', 'app', 0),
('module_knowledge_base', '', 'app', 0),
('module_lead', '1', 'app', 0),
('module_leave', '1', 'app', 0),
('module_message', '1', 'app', 0),
('module_note', '1', 'app', 0),
('module_petty_cash', '', 'app', 0),
('module_project_timesheet', '1', 'app', 0),
('module_ticket', '', 'app', 0),
('module_timeline', '1', 'app', 0),
('module_todo', '1', 'app', 0),
('no_of_decimals', '2', 'app', 0),
('payable_cheques', '43', 'app', 0),
('payable_EOS_benefits', '15', 'app', 0),
('payable_job_security', '9', 'app', 0),
('payable_leave_salaries', '15', 'app', 0),
('payable_PASI', '53', 'app', 0),
('payable_salaries', '46', 'app', 0),
('petty_cash', '0', 'app', 0),
('petty_cash_parent', '16', 'app', 0),
('project_reference_in_tickets', '1', 'app', 0),
('pusher_app_id', '929038', 'app', 0),
('pusher_cluster', 'eu', 'app', 0),
('pusher_key', '2cbc86a72bfa9a85b0e5', 'app', 0),
('pusher_secret', '53077cbb420c5e79c8f6', 'app', 0),
('receivable_cheques', '21', 'app', 0),
('rows_per_page', '100', 'app', 0),
('salary_advances', '66', 'app', 0),
('salary_expenses', '65', 'app', 0),
('scrollbar', 'native', 'app', 0),
('send_bcc_to', 'info@teamway.om', 'app', 0),
('send_estimate_bcc_to', 'info@teamway.om', 'app', 0),
('show_background_image_in_signin_page', 'yes', 'app', 0),
('show_logo_in_signin_page', 'yes', 'app', 0),
('show_recent_ticket_comments_at_the_top', '1', 'app', 0),
('site_logo', 'a:1:{s:9:\"file_name\";s:32:\"_file6473370ca680f-site-logo.png\";}', 'app', 0),
('suppliers_accounts_parent', '37', 'app', 0),
('ticket_prefix', '', 'app', 0),
('time_format', '24_hours', 'app', 0),
('timezone', 'Asia/Muscat', 'app', 0),
('treasury_accounts_parent', '12', 'app', 0),
('user_1_allowed_distance', '100000', 'user', 0),
('user_1_dashboard', '', 'user', 0),
('user_1_disable_push_notification', '0', 'user', 0),
('user_1_hidden_topbar_menus', '', 'user', 0),
('user_1_location', '23.620058326708097,58.24233084570313', 'user', 0),
('user_1_notification_sound_volume', '4', 'user', 0),
('user_1_personal_language', 'english', 'user', 0),
('user_10_dashboard', '', 'user', 0),
('user_10_personal_language', 'english', 'user', 0),
('user_11_dashboard', '', 'user', 0),
('user_12_dashboard', '', 'user', 0),
('user_13_dashboard', '', 'user', 0),
('user_16_dashboard', '', 'user', 0),
('user_16_personal_language', 'english', 'user', 0),
('user_18_dashboard', '', 'user', 0),
('user_2_dashboard', '', 'user', 0),
('user_21_dashboard', '', 'user', 0),
('user_21_personal_language', 'english', 'user', 0),
('user_22_dashboard', '', 'user', 0),
('user_22_personal_language', 'arabic', 'user', 0),
('user_23_dashboard', '', 'user', 0),
('user_23_personal_language', 'arabic', 'user', 0),
('user_24_dashboard', '', 'user', 0),
('user_24_personal_language', 'english', 'user', 0),
('user_25_dashboard', '', 'user', 0),
('user_25_personal_language', 'english', 'user', 0),
('user_3_dashboard', '', 'user', 0),
('user_39_dashboard', '', 'user', 0),
('user_39_personal_language', 'english', 'user', 0),
('user_4_dashboard', '', 'user', 0),
('user_42_dashboard', '', 'user', 0),
('user_43_dashboard', '', 'user', 0),
('user_44_dashboard', '', 'user', 0),
('user_45_dashboard', '', 'user', 0),
('user_46_dashboard', '', 'user', 0),
('user_47_dashboard', '', 'user', 0),
('user_48_dashboard', '', 'user', 0),
('user_48_personal_language', 'english', 'user', 0),
('user_49_dashboard', '', 'user', 0),
('user_5_allowed_distance', '100000000', 'user', 0),
('user_5_dashboard', '6', 'user', 0),
('user_5_disable_push_notification', '1', 'user', 0),
('user_5_hidden_topbar_menus', '', 'user', 0),
('user_5_location', '23.673522614041882,57.680654820312505', 'user', 0),
('user_5_notification_sound_volume', '7', 'user', 0),
('user_5_personal_language', 'english', 'user', 0),
('user_53_dashboard', '', 'user', 0),
('user_54_dashboard', '', 'user', 0),
('user_54_personal_language', 'english', 'user', 0),
('user_58_dashboard', '', 'user', 0),
('user_6_dashboard', '', 'user', 0),
('user_6_personal_language', 'english', 'user', 0),
('user_64_dashboard', '', 'user', 0),
('user_65_dashboard', '', 'user', 0),
('user_66_dashboard', '', 'user', 0),
('user_69_dashboard', '', 'user', 0),
('user_70_dashboard', '', 'user', 0),
('user_72_dashboard', '', 'user', 0),
('user_9_dashboard', '', 'user', 0),
('VAT_expense', '21', 'app', 0),
('VAT_in', '35', 'app', 0),
('VAT_out', '27', 'app', 0),
('weekend', '5,6', 'app', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` int(11) NOT NULL,
  `purchase_order_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL DEFAULT 0,
  `date` date NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `delivery_note_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_note_date` date DEFAULT NULL,
  `status` enum('draft','approved') COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipment_items`
--

CREATE TABLE `shipment_items` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `po_item_id` int(11) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `shipment_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `facebook` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `twitter` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `linkedin` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `googleplus` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `digg` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `youtube` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `pinterest` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `instagram` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `github` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `tumblr` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `vine` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` double NOT NULL DEFAULT 0,
  `note` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `contact_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_date` date DEFAULT NULL,
  `website` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(3) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vat_number` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `note` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `account_id` int(11) NOT NULL DEFAULT 0,
  `advance_account_id` int(11) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  `cost_center_id` int(11) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `milestone_id` int(11) NOT NULL DEFAULT 0,
  `assigned_to` int(11) NOT NULL,
  `deadline` date DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `points` tinyint(4) NOT NULL DEFAULT 1,
  `status` enum('to_do','in_progress','done') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'to_do',
  `status_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `collaborators` text COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL DEFAULT 0,
  `recurring` tinyint(1) NOT NULL DEFAULT 0,
  `repeat_every` int(11) NOT NULL DEFAULT 0,
  `repeat_type` enum('days','weeks','months','years') COLLATE utf8_unicode_ci DEFAULT NULL,
  `no_of_cycles` int(11) NOT NULL DEFAULT 0,
  `recurring_task_id` int(11) NOT NULL DEFAULT 0,
  `no_of_cycles_completed` int(11) NOT NULL DEFAULT 0,
  `created_date` date NOT NULL,
  `next_recurring_date` date DEFAULT NULL,
  `reminder_date` date NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `deleted` tinyint(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_status`
--

CREATE TABLE `task_status` (
  `id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `key_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task_status`
--

INSERT INTO `task_status` (`id`, `title`, `key_name`, `color`, `sort`, `deleted`) VALUES
(1, 'To Do', 'to_do', '#F9A52D', 0, 0),
(2, 'In progress', 'in_progress', '#1672B9', 1, 0),
(3, 'Done', 'done', '#00B393', 2, 0),
(4, 'holding', '', '#aab7b7', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(11) NOT NULL,
  `title` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `percentage` double NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `title`, `percentage`, `deleted`) VALUES
(1, 'VAT (5%)', 5, 0),
(2, 'SPD- vat (3%)', 3, 1),
(3, 'SPD-Vat ( Nill) new agreement (0%)', 0, 1),
(4, 'name', 50, 0);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `members` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_member_job_info`
--

CREATE TABLE `team_member_job_info` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_of_hire` date DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `salary` double NOT NULL DEFAULT 0,
  `salary_term` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `working_hours` int(11) DEFAULT NULL,
  `yearly_leaves` int(11) DEFAULT NULL,
  `housing` float DEFAULT 0,
  `transportation` float DEFAULT 0,
  `telephone` float DEFAULT 0,
  `utility` float DEFAULT 0,
  `national` tinyint(4) DEFAULT 0,
  `pasi` tinyint(4) DEFAULT 1,
  `bank_title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_title` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `account_no` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL DEFAULT 0,
  `ticket_type_id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `status` enum('new','client_replied','open','closed') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `last_activity_at` datetime DEFAULT NULL,
  `assigned_to` int(11) NOT NULL DEFAULT 0,
  `creator_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `creator_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `task_id` int(11) NOT NULL,
  `closed_at` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_comments`
--

CREATE TABLE `ticket_comments` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `files` longtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `title`, `deleted`) VALUES
(1, 'General Support', 0),
(2, 'One way', 0),
(3, 'Two way', 0);

-- --------------------------------------------------------

--
-- Table structure for table `to_do`
--

CREATE TABLE `to_do` (
  `id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `labels` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('to_do','done') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'to_do',
  `start_date` date DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `type` text NOT NULL,
  `is_manual` tinyint(1) NOT NULL DEFAULT 0,
  `reference` text DEFAULT NULL,
  `bank_cash` int(11) DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nationality` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `superior_id` int(11) DEFAULT -1,
  `user_type` enum('staff','client','lead') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'client',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_super` tinyint(1) NOT NULL DEFAULT 0,
  `role_id` int(11) NOT NULL DEFAULT 0,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `message_checked_at` datetime DEFAULT NULL,
  `client_id` int(11) NOT NULL DEFAULT 0,
  `notification_checked_at` datetime DEFAULT NULL,
  `is_primary_contact` tinyint(1) NOT NULL DEFAULT 0,
  `job_title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Untitled',
  `disable_login` tinyint(1) NOT NULL DEFAULT 0,
  `note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternative_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alternative_phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `ssn` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'male',
  `sticky_note` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `external_links` mediumtext COLLATE utf8_unicode_ci DEFAULT NULL,
  `skype` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `enable_web_notification` tinyint(1) NOT NULL DEFAULT 1,
  `enable_email_notification` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `last_online` datetime DEFAULT NULL,
  `pt_account_id` int(11) NOT NULL DEFAULT 0,
  `sal_account_id` int(11) NOT NULL DEFAULT 0,
  `imb_account_id` int(11) NOT NULL DEFAULT 0,
  `resident_card_no` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `resident_card_expiry` date DEFAULT NULL,
  `passport_no` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `passport_expiry` date DEFAULT NULL,
  `requested_account_removal` tinyint(1) DEFAULT 0,
  `payroll` tinyint(1) DEFAULT 1,
  `cost_center_id` int(11) NOT NULL DEFAULT 1,
  `deleted` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nationality`, `first_name`, `last_name`, `superior_id`, `user_type`, `is_admin`, `is_super`, `role_id`, `email`, `password`, `image`, `status`, `message_checked_at`, `client_id`, `notification_checked_at`, `is_primary_contact`, `job_title`, `disable_login`, `note`, `address`, `alternative_address`, `phone`, `alternative_phone`, `dob`, `ssn`, `gender`, `sticky_note`, `external_links`, `skype`, `enable_web_notification`, `enable_email_notification`, `created_at`, `last_online`, `pt_account_id`, `sal_account_id`, `imb_account_id`, `resident_card_no`, `resident_card_expiry`, `passport_no`, `passport_expiry`, `requested_account_removal`, `payroll`, `cost_center_id`, `deleted`) VALUES
(1, 'sudanese', 'Admin', 'Admin', -1, 'staff', 1, 0, 0, 'admin@admin.net', '25d55ad283aa400af464c76d713c07ad', 'a:1:{s:9:\"file_name\";s:29:\"_file644f783fc6100-avatar.png\";}', 'active', '2023-10-22 12:26:35', 0, '2023-10-24 12:29:43', 0, 'Admin', 0, NULL, '', '', '', '', '2022-07-05', '', 'male', '', NULL, '', 1, 1, '2022-07-05 11:14:59', '2023-10-25 18:33:10', 80, 81, 82, '', '0000-00-00', '', '0000-00-00', 0, 1, 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `checked_by` (`checked_by`);

--
-- Indexes for table `budgeting`
--
ALTER TABLE `budgeting`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `budgeting_forms`
--
ALTER TABLE `budgeting_forms`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `budgeting_items`
--
ALTER TABLE `budgeting_items`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `budgeting_requests`
--
ALTER TABLE `budgeting_requests`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `checklist_items`
--
ALTER TABLE `checklist_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_groups`
--
ALTER TABLE `client_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cost_centers`
--
ALTER TABLE `cost_centers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `currency_id` (`currency_id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_widgets`
--
ALTER TABLE `custom_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dashboards`
--
ALTER TABLE `dashboards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_notes`
--
ALTER TABLE `delivery_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_note_items`
--
ALTER TABLE `delivery_note_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_messages`
--
ALTER TABLE `email_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enteries`
--
ALTER TABLE `enteries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimates`
--
ALTER TABLE `estimates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_forms`
--
ALTER TABLE `estimate_forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_items`
--
ALTER TABLE `estimate_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `estimate_requests`
--
ALTER TABLE `estimate_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_items`
--
ALTER TABLE `expense_items`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `expires`
--
ALTER TABLE `expires`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_files`
--
ALTER TABLE `general_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_articles`
--
ALTER TABLE `help_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_categories`
--
ALTER TABLE `help_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `internal_transactions`
--
ALTER TABLE `internal_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items_levels`
--
ALTER TABLE `items_levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_uom`
--
ALTER TABLE `item_uom`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lead_source`
--
ALTER TABLE `lead_source`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lead_status`
--
ALTER TABLE `lead_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_applications`
--
ALTER TABLE `leave_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_type_id` (`leave_type_id`),
  ADD KEY `user_id` (`applicant_id`),
  ADD KEY `checked_by` (`checked_by`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_request`
--
ALTER TABLE `material_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_request_items`
--
ALTER TABLE `material_request_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `material_request_payments`
--
ALTER TABLE `material_request_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_from` (`from_user_id`),
  ADD KEY `message_to` (`to_user_id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nationality`
--
ALTER TABLE `nationality`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_settings`
--
ALTER TABLE `notification_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event` (`event`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paypal_ipn`
--
ALTER TABLE `paypal_ipn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_detail`
--
ALTER TABLE `payroll_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proforma_invoices`
--
ALTER TABLE `proforma_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proforma_invoice_items`
--
ALTER TABLE `proforma_invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proforma_invoice_payments`
--
ALTER TABLE `proforma_invoice_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_comments`
--
ALTER TABLE `project_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_files`
--
ALTER TABLE `project_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_members`
--
ALTER TABLE `project_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_settings`
--
ALTER TABLE `project_settings`
  ADD UNIQUE KEY `unique_index` (`project_id`,`setting_name`);

--
-- Indexes for table `project_time`
--
ALTER TABLE `project_time`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_payments`
--
ALTER TABLE `purchase_order_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_advance`
--
ALTER TABLE `salary_advance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_items`
--
ALTER TABLE `sales_items`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD UNIQUE KEY `setting_name` (`setting_name`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipment_items`
--
ALTER TABLE `shipment_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_status`
--
ALTER TABLE `task_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_member_job_info`
--
ALTER TABLE `team_member_job_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `to_do`
--
ALTER TABLE `to_do`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `email` (`email`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `deleted` (`deleted`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgeting`
--
ALTER TABLE `budgeting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgeting_forms`
--
ALTER TABLE `budgeting_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgeting_items`
--
ALTER TABLE `budgeting_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budgeting_requests`
--
ALTER TABLE `budgeting_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checklist_items`
--
ALTER TABLE `checklist_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_groups`
--
ALTER TABLE `client_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cost_centers`
--
ALTER TABLE `cost_centers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_field_values`
--
ALTER TABLE `custom_field_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_widgets`
--
ALTER TABLE `custom_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboards`
--
ALTER TABLE `dashboards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_notes`
--
ALTER TABLE `delivery_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_note_items`
--
ALTER TABLE `delivery_note_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_messages`
--
ALTER TABLE `email_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enteries`
--
ALTER TABLE `enteries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimates`
--
ALTER TABLE `estimates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate_forms`
--
ALTER TABLE `estimate_forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate_items`
--
ALTER TABLE `estimate_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estimate_requests`
--
ALTER TABLE `estimate_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expense_items`
--
ALTER TABLE `expense_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expires`
--
ALTER TABLE `expires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_files`
--
ALTER TABLE `general_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_articles`
--
ALTER TABLE `help_articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `help_categories`
--
ALTER TABLE `help_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internal_transactions`
--
ALTER TABLE `internal_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items_levels`
--
ALTER TABLE `items_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_uom`
--
ALTER TABLE `item_uom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lead_source`
--
ALTER TABLE `lead_source`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lead_status`
--
ALTER TABLE `lead_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leave_applications`
--
ALTER TABLE `leave_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_request`
--
ALTER TABLE `material_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_request_items`
--
ALTER TABLE `material_request_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_request_payments`
--
ALTER TABLE `material_request_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nationality`
--
ALTER TABLE `nationality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_settings`
--
ALTER TABLE `notification_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `paypal_ipn`
--
ALTER TABLE `paypal_ipn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_detail`
--
ALTER TABLE `payroll_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proforma_invoices`
--
ALTER TABLE `proforma_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proforma_invoice_items`
--
ALTER TABLE `proforma_invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proforma_invoice_payments`
--
ALTER TABLE `proforma_invoice_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_comments`
--
ALTER TABLE `project_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_files`
--
ALTER TABLE `project_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_members`
--
ALTER TABLE `project_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_time`
--
ALTER TABLE `project_time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_payments`
--
ALTER TABLE `purchase_order_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `salary_advance`
--
ALTER TABLE `salary_advance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_items`
--
ALTER TABLE `sales_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipment_items`
--
ALTER TABLE `shipment_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_status`
--
ALTER TABLE `task_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_member_job_info`
--
ALTER TABLE `team_member_job_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_comments`
--
ALTER TABLE `ticket_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `to_do`
--
ALTER TABLE `to_do`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cost_centers`
--
ALTER TABLE `cost_centers`
  ADD CONSTRAINT `cost_centers_ibfk_1` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
