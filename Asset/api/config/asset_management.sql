-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2021 at 07:23 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asset_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `daily_report`
--

CREATE TABLE `daily_report` (
  `daily_report_id` bigint(14) NOT NULL,
  `gatepass_link_id` bigint(14) NOT NULL,
  `safety_clearence` varchar(1) NOT NULL,
  `hr_clearence` varchar(1) NOT NULL,
  `report_date_time` datetime NOT NULL,
  `supervisor_name` longtext NOT NULL,
  `work_done` varchar(255) NOT NULL,
  `work_status` varchar(255) NOT NULL,
  `work_done_by` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `details` longtext NOT NULL,
  `pending_work` varchar(255) NOT NULL,
  `material_shortage` varchar(255) NOT NULL,
  `emp_remark` longtext NOT NULL,
  `supervisor_remark` longtext,
  `report_media_count` bigint(10) NOT NULL,
  `report_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `daily_report_media`
--

CREATE TABLE `daily_report_media` (
  `report_media_id` bigint(14) NOT NULL,
  `report_id` bigint(14) NOT NULL,
  `report_media_path` varchar(150) NOT NULL,
  `report_media_type` varchar(50) NOT NULL,
  `report_media_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gatepass`
--

CREATE TABLE `gatepass` (
  `gatepass_id` bigint(14) NOT NULL,
  `username` varchar(30) NOT NULL,
  `project_id` bigint(14) NOT NULL,
  `gatepass_start_date` date NOT NULL,
  `gatepass_end_date` date NOT NULL,
  `gatepass_string` longtext NOT NULL,
  `gatepass_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gatepass_link`
--

CREATE TABLE `gatepass_link` (
  `gatepass_link_id` bigint(38) NOT NULL,
  `gatepass_id` bigint(14) NOT NULL,
  `gatepass_attendence` int(1) NOT NULL,
  `gatepass_date` date NOT NULL,
  `login_logout_status` int(1) NOT NULL,
  `login_time` time DEFAULT NULL,
  `logout_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `manual`
--

CREATE TABLE `manual` (
  `manual_id` bigint(14) NOT NULL,
  `project_id` bigint(14) NOT NULL,
  `manual_title` varchar(255) NOT NULL,
  `manual_descr` longtext NOT NULL,
  `manual_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mas_category`
--

CREATE TABLE `mas_category` (
  `category_id` bigint(14) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mas_emp_category`
--

CREATE TABLE `mas_emp_category` (
  `category_id` bigint(14) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `category_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mas_media`
--

CREATE TABLE `mas_media` (
  `media_id` bigint(14) NOT NULL,
  `media_path` varchar(255) NOT NULL,
  `media_extension` varchar(200) NOT NULL,
  `media_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mas_role`
--

CREATE TABLE `mas_role` (
  `role_id` bigint(10) NOT NULL,
  `role_name` varchar(200) NOT NULL,
  `role_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mas_role`
--

INSERT INTO `mas_role` (`role_id`, `role_name`, `role_status`) VALUES
(-2, 'Super-admin', 1),
(-1, 'Admin', 1),
(1, 'HR', 1),
(2, 'Supervisor', 1),
(3, 'Employee', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mas_rule`
--

CREATE TABLE `mas_rule` (
  `rule_id` double(10,2) NOT NULL,
  `rule_name` varchar(255) NOT NULL,
  `page_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `member_login_access`
--

CREATE TABLE `member_login_access` (
  `member_id` bigint(14) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_role` bigint(10) NOT NULL,
  `user_is_pass_change` int(1) NOT NULL,
  `user_previliges` varchar(255) DEFAULT NULL,
  `login_token_value` varchar(255) DEFAULT NULL,
  `user_status` int(1) NOT NULL,
  `create_under` varchar(50) DEFAULT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_login_access`
--

INSERT INTO `member_login_access` (`member_id`, `username`, `password`, `user_role`, `user_is_pass_change`, `user_previliges`, `login_token_value`, `user_status`, `create_under`, `create_user`, `create_date`, `modify_user`, `modify_date`) VALUES
(20210818195200, 'superadmin', 'MmFiWUZvTGpZdVdxSVdVajFYUDZFdz09', -2, 1, '0', '', 1, NULL, 'superadmin', '2021-08-18 00:00:00', 'superadmin', '2021-11-22 19:02:13'),
(20211122190041, 'admin', 'YWpCelJUSnNrVmN6dWx3N2Q4Y3NNQT09', -1, 0, '0', '', 1, 'admin', 'superadmin', '2021-11-22 19:00:41', 'superadmin', '2021-11-22 19:01:19'),
(20211122190907, 'supervisor', 'dGtlVUQrWVUvV1lSM21zVWVEUzE5QT09', 2, 0, '0', '', 1, 'admin', 'admin', '2021-11-22 19:09:07', 'admin', '2021-11-22 19:09:07'),
(20211122191620, 'emp', 'VjV6TktjRzZQYXFaZWdSTlh6NkxVdz09', 3, 0, '0', '', 1, 'admin', 'admin', '2021-11-22 19:16:20', 'admin', '2021-11-22 19:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `member_profile`
--

CREATE TABLE `member_profile` (
  `member_id` bigint(14) NOT NULL,
  `emp_code` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `member_first_name` varchar(100) NOT NULL,
  `member_middle_name` varchar(100) DEFAULT NULL,
  `member_last_name` varchar(100) NOT NULL,
  `member_email` varchar(150) DEFAULT NULL,
  `member_mobile` bigint(13) DEFAULT NULL,
  `member_address` longtext,
  `member_category` bigint(14) DEFAULT NULL,
  `member_type` varchar(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `member_profile`
--

INSERT INTO `member_profile` (`member_id`, `emp_code`, `username`, `member_first_name`, `member_middle_name`, `member_last_name`, `member_email`, `member_mobile`, `member_address`, `member_category`, `member_type`, `create_user`, `create_date`, `modify_user`, `modify_date`) VALUES
(20210818195200, 'SUP/ADMN/001', 'superadmin', 'Super', 'Test', 'Admin', 'superadmin@gmail.com', 7894561230, '<p data-f-id=\"pbf\" style=\"text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;\">Powered by <a href=\"https://www.froala.com/wysiwyg-editor?pb=1\" title=\"Froala Editor\">Froala Editor</a></p>', NULL, 'P', 'superadmin', '2021-08-18 00:00:00', 'superadmin', '2021-11-22 19:02:13'),
(20211122190041, 'admn/001', 'admin', 'Admin', NULL, 'User', 'admin@gmail.com', 1234567890, '<p data-f-id=\"pbf\" style=\"text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;\">Powered by <a href=\"https://www.froala.com/wysiwyg-editor?pb=1\" title=\"Froala Editor\">Froala Editor</a></p>', NULL, 'P', 'superadmin', '2021-11-22 19:00:41', 'superadmin', '2021-11-22 19:00:41'),
(20211122190907, 'SUP/5t/00', 'supervisor', 'Supervisor', NULL, 'User', 'supervisor@gmail.com', 5423656300, '<p data-f-id=\"pbf\" style=\"text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;\">Powered by <a href=\"https://www.froala.com/wysiwyg-editor?pb=1\" title=\"Froala Editor\">Froala Editor</a></p>', NULL, 'P', 'admin', '2021-11-22 19:09:07', 'admin', '2021-11-22 19:09:07'),
(20211122191620, 'emp/009', 'emp', 'Employee', NULL, 'User', 'tutorcode992@gmail.com', 1234567895, '<p data-f-id=\"pbf\" style=\"text-align: center; font-size: 14px; margin-top: 30px; opacity: 0.65; font-family: sans-serif;\">Powered by <a href=\"https://www.froala.com/wysiwyg-editor?pb=1\" title=\"Froala Editor\">Froala Editor</a></p>', NULL, 'C', 'admin', '2021-11-22 19:16:20', 'admin', '2021-11-22 19:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `project_info`
--

CREATE TABLE `project_info` (
  `project_info_id` bigint(14) NOT NULL,
  `project_id` bigint(14) NOT NULL,
  `project_supervisor` longtext NOT NULL,
  `project_supervisor_start_date` date NOT NULL,
  `project_supervisor_end_date` date DEFAULT NULL,
  `project_info_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `project_list`
--

CREATE TABLE `project_list` (
  `project_id` bigint(14) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_location` varchar(255) NOT NULL,
  `project_scope` longtext NOT NULL,
  `project_address` longtext NOT NULL,
  `project_start_date` date NOT NULL,
  `project_end_date` date DEFAULT NULL,
  `project_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `task_details`
--

CREATE TABLE `task_details` (
  `task_detail_id` bigint(14) NOT NULL,
  `task_master_id` bigint(14) NOT NULL,
  `actual_value` double(10,2) NOT NULL,
  `checked_by` varchar(30) NOT NULL,
  `admin_approval` int(1) DEFAULT NULL,
  `admin_approval_by` varchar(30) DEFAULT NULL,
  `hr_approval` int(1) DEFAULT NULL,
  `hr_approval_by` varchar(30) DEFAULT NULL,
  `supervisor_approval` int(1) DEFAULT NULL,
  `supervisor_approval_by` varchar(30) DEFAULT NULL,
  `admin_remarks` longtext,
  `hr_remarks` longtext,
  `supervisor_remarks` longtext,
  `emp_remarks` longtext,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `task_master`
--

CREATE TABLE `task_master` (
  `task_master_id` bigint(14) NOT NULL,
  `project_id` bigint(14) NOT NULL,
  `category_id` bigint(14) NOT NULL,
  `task_description` varchar(255) NOT NULL,
  `task_unit` varchar(50) NOT NULL,
  `standard_value` double(10,2) NOT NULL,
  `min_deviation` double(10,2) NOT NULL,
  `max_deviation` double(10,2) NOT NULL,
  `task_status` int(1) NOT NULL,
  `create_user` varchar(30) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_user` varchar(30) NOT NULL,
  `modify_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_report`
--
ALTER TABLE `daily_report`
  ADD PRIMARY KEY (`daily_report_id`);

--
-- Indexes for table `daily_report_media`
--
ALTER TABLE `daily_report_media`
  ADD PRIMARY KEY (`report_media_id`);

--
-- Indexes for table `gatepass`
--
ALTER TABLE `gatepass`
  ADD PRIMARY KEY (`gatepass_id`);

--
-- Indexes for table `gatepass_link`
--
ALTER TABLE `gatepass_link`
  ADD PRIMARY KEY (`gatepass_link_id`);

--
-- Indexes for table `manual`
--
ALTER TABLE `manual`
  ADD PRIMARY KEY (`manual_id`);

--
-- Indexes for table `mas_category`
--
ALTER TABLE `mas_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `mas_emp_category`
--
ALTER TABLE `mas_emp_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `mas_media`
--
ALTER TABLE `mas_media`
  ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `mas_role`
--
ALTER TABLE `mas_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `mas_rule`
--
ALTER TABLE `mas_rule`
  ADD PRIMARY KEY (`rule_id`);

--
-- Indexes for table `member_login_access`
--
ALTER TABLE `member_login_access`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `member_profile`
--
ALTER TABLE `member_profile`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `username_profile` (`username`);

--
-- Indexes for table `project_info`
--
ALTER TABLE `project_info`
  ADD PRIMARY KEY (`project_info_id`);

--
-- Indexes for table `project_list`
--
ALTER TABLE `project_list`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `task_details`
--
ALTER TABLE `task_details`
  ADD PRIMARY KEY (`task_detail_id`);

--
-- Indexes for table `task_master`
--
ALTER TABLE `task_master`
  ADD PRIMARY KEY (`task_master_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gatepass_link`
--
ALTER TABLE `gatepass_link`
  MODIFY `gatepass_link_id` bigint(38) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mas_role`
--
ALTER TABLE `mas_role`
  MODIFY `role_id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
