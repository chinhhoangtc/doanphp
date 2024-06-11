-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2024 at 09:49 PM
-- Server version: 10.6.16-MariaDB-cll-lve-log
-- PHP Version: 8.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cwehqepi_doanphp`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_list`
--

CREATE TABLE `attendance_list` (
  `attendance_id` varchar(10) NOT NULL,
  `class_id` varchar(10) NOT NULL,
  `create_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_submit` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_list`
--

INSERT INTO `attendance_list` (`attendance_id`, `class_id`, `create_at`, `is_submit`) VALUES
('att001', 'class001', '2024-04-27 14:49:16', 1),
('att002', 'class001', '2024-05-04 14:49:16', 1),
('att003', 'class002', '2024-06-03 21:56:36', 1),
('att004', 'class002', '2024-06-10 21:56:36', 1),
('att005', 'class001', '2024-05-11 23:02:27', 1),
('att006', 'class001', '2024-05-18 23:02:27', 1),
('att007', 'class001', '2024-05-26 23:03:11', 0),
('att008', 'class001', '2024-06-05 13:41:39', 0),
('att009', 'class002', '2024-06-05 13:41:59', 0),
('att010', 'class001', '2024-06-05 14:04:12', 1),
('att011', 'class005', '2024-06-05 15:32:43', 1),
('att012', 'class006', '2024-06-05 15:34:42', 1),
('att013', 'class006', '2024-06-05 15:37:43', 1),
('att014', 'class006', '2024-06-05 15:40:22', 1),
('att015', 'class006', '2024-06-05 15:46:00', 1),
('att016', 'class006', '2024-06-05 15:47:51', 1),
('att017', 'class006', '2024-06-05 15:55:07', 1),
('att018', 'class002', '2024-06-05 19:48:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

CREATE TABLE `attendance_records` (
  `record_id` int(11) NOT NULL,
  `attendance_id` varchar(255) NOT NULL,
  `student_id` varchar(11) NOT NULL,
  `absent` tinyint(1) NOT NULL DEFAULT 0,
  `late` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance_records`
--

INSERT INTO `attendance_records` (`record_id`, `attendance_id`, `student_id`, `absent`, `late`) VALUES
(13, 'att001', 'sv001', 0, 'ff'),
(14, 'att001', 'sv002', 0, 'ff'),
(15, 'att001', 'sv003', 0, 'ff'),
(16, 'att003', 'sv001', 0, 'tre'),
(17, 'att004', 'sv001', 1, ''),
(20, 'att006', 'sv001', 0, ''),
(21, 'att006', 'sv002', 0, ''),
(22, 'att006', 'sv003', 1, ''),
(23, 'att006', 'sv006', 0, ''),
(24, 'att006', 'sv007', 0, ''),
(25, 'att006', 'sv008', 0, 'aaaa'),
(26, 'att006', 'sv009', 0, ''),
(27, 'att006', 'sv010', 0, ''),
(28, 'att006', 'sv011', 0, ''),
(30, 'att011', 'sv012', 0, 'trễ vì mưa'),
(32, 'att012', 'sv013', 0, '0'),
(34, 'att013', 'sv013', 0, '0'),
(36, 'att014', 'sv013', 0, '0'),
(38, 'att015', 'sv013', 0, 'testt'),
(39, 'att016', 'sv013', 0, '0'),
(40, 'att016', 'sv013', 0, '0'),
(41, 'att017', 'sv013', 0, '0'),
(42, 'att017', 'sv013', 0, '0'),
(43, 'att005', 'sv001', 0, ''),
(44, 'att005', 'sv002', 0, ''),
(45, 'att005', 'sv003', 0, ''),
(46, 'att005', 'sv006', 0, ''),
(47, 'att005', 'sv007', 0, ''),
(48, 'att005', 'sv008', 0, ''),
(49, 'att005', 'sv009', 0, ''),
(50, 'att005', 'sv010', 0, ''),
(51, 'att005', 'sv011', 0, ''),
(52, 'att010', 'sv001', 0, ''),
(53, 'att010', 'sv002', 0, ''),
(54, 'att010', 'sv003', 0, ''),
(55, 'att010', 'sv006', 0, ''),
(56, 'att010', 'sv007', 0, ''),
(57, 'att010', 'sv008', 0, ''),
(58, 'att010', 'sv009', 0, ''),
(59, 'att010', 'sv010', 0, ''),
(60, 'att010', 'sv011', 0, ''),
(61, 'att018', 'sv001', 0, '0');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` varchar(10) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `teacher_id` varchar(10) NOT NULL,
  `course_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `class_name`, `teacher_id`, `course_id`) VALUES
('class001', '12DHBM02', 'gv001', '01012001'),
('class002', '11DHTH002', 'gv001', '01012002'),
('class003', '09DHTH03', 'gv001', '01012003'),
('class004', '12DHBM03', 'gv002', 'ctrr01'),
('class005', '10DHTP01', 'gv003', 'cntp001'),
('class006', '10DHTP02', 'gv003', 'cntp002');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` varchar(10) NOT NULL,
  `subject_id` varchar(10) NOT NULL,
  `date_start` datetime NOT NULL DEFAULT current_timestamp(),
  `date_end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `subject_id`, `date_start`, `date_end`) VALUES
('01012001', 'sstk', '2024-04-27 14:48:44', NULL),
('01012002', 'sstk', '2024-04-27 14:48:44', NULL),
('01012003', 'sstk', '2024-04-27 14:48:44', NULL),
('01012004', 'sstk', '2024-04-27 14:48:44', NULL),
('cntp001', 'cbma', '2024-06-05 20:20:43', NULL),
('cntp002', 'khtp', '2024-06-05 20:20:43', NULL),
('ctrr01', 'ctrr', '2024-05-16 21:48:43', NULL),
('tab2002', 'tab1', '2024-04-27 14:48:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `department_id` varchar(10) NOT NULL,
  `dep_name` varchar(50) NOT NULL,
  `create_at` datetime DEFAULT current_timestamp(),
  `is_active` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`department_id`, `dep_name`, `create_at`, `is_active`) VALUES
('cntp', 'Công nghệ thực phẩm', '2024-04-27 14:48:23', 1),
('cntt', 'Công nghệ thông tin', '2024-04-27 14:48:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_class`
--

CREATE TABLE `student_class` (
  `student_id` varchar(10) NOT NULL,
  `class_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_class`
--

INSERT INTO `student_class` (`student_id`, `class_id`) VALUES
('sv001', 'class001'),
('sv001', 'class002'),
('sv002', 'class001'),
('sv002', 'class004'),
('sv003', 'class001'),
('sv006', 'class001'),
('sv007', 'class001'),
('sv008', 'class001'),
('sv009', 'class001'),
('sv010', 'class001'),
('sv011', 'class001'),
('sv012', 'class005'),
('sv013', 'class006');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject_id` varchar(10) NOT NULL,
  `subject_name` varchar(50) NOT NULL,
  `department_id` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject_name`, `department_id`) VALUES
('cbma', ' Chế biến món ăn', 'cntp'),
('ctrr', 'Cấu trúc rời rạc', 'cntt'),
('khtp', 'Khoa học thực phẩm', 'cntp'),
('sstk', 'Sắc xuất thống kê', 'cntt'),
('tab1', 'tiếng anh b1', NULL),
('tcc', 'toán cao cấp', 'cntt');

-- --------------------------------------------------------

--
-- Table structure for table `_user`
--

CREATE TABLE `_user` (
  `id` int(11) NOT NULL,
  `_user_id` varchar(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `department_id` varchar(10) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `role` int(1) NOT NULL DEFAULT 0,
  `is_active` int(1) NOT NULL DEFAULT 1,
  `date` date DEFAULT NULL,
  `class` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `_user`
--

INSERT INTO `_user` (`id`, `_user_id`, `username`, `password`, `department_id`, `fullname`, `role`, `is_active`, `date`, `class`, `address`) VALUES
(1, 'sv001', 'sinhvien1', '123', 'cntt', 'Hoàng Văn Chính', 0, 1, '2003-11-27', '12DHBM03', 'Thanh Hóa'),
(2, 'sv002', 'sinhvien2', '123', 'cntt', 'Mai Hữu Thịnh', 0, 1, '2003-03-14', '12DHBM08', 'Hà Nội'),
(3, 'sv003', 'sinhvien3', '123', 'cntt', 'Nguyễn Thị Kim Anh', 0, 1, '2003-01-30', '12DHTH02', 'Bắc Giang'),
(4, 'gv001', 'thuychuppynguyen', '123', 'cntt', 'Nguyễn Thị Thủy', 1, 1, '1985-08-23', '', 'Hà Tĩnh'),
(5, 'gv002', 'thamhoang198', '123', 'cntt', 'Hoàng Thị Thắm', 1, 1, '1990-05-15', '', 'Hải Phòng'),
(26, 'sv006', 'nguyenvananh123', '123', 'cntt', 'Nguyễn Văn Anh', 0, 1, '2000-01-01', '12DHTH01', 'Hà Nội'),
(27, 'sv007', 'tranthimai456', '123', 'cntt', 'Trần Thị Mai', 0, 1, '2000-02-15', '12DHTH02', 'Hồ Chí Minh'),
(28, 'sv008', 'levankhoa789', '123', 'cntt', 'Lê Văn Khoa', 0, 1, '2000-03-20', '12DHTH03', 'Đà Nẵng'),
(29, 'sv009', 'phamthihuong010', '123', 'cntt', 'Phạm Thị Hương', 0, 1, '2000-04-10', '12DHTH04', 'Hải Phòng'),
(30, 'sv010', 'nguyenminhhoa234', '123', 'cntt', 'Nguyễn Minh Hoà', 0, 1, '2000-05-05', '12DHTH05', 'Cần Thơ'),
(31, 'sv011', 'tranduongthanh567', '123', 'cntt', 'Trần Dương Thành', 0, 1, '2000-06-25', '12DHTH06', 'Bình Dương'),
(32, 'gv003', 'nguyenthidinh', '123', 'cntp', 'Nguyễn Thị Định', 1, 1, '1985-07-24', '', 'Vũng Tàu'),
(33, 'sv012', 'quangkhai193', '123', 'cntp', 'Trần Quang Khải', 0, 1, '2003-10-03', '11DHTP09', 'Lâm Đồng'),
(34, 'sv013', 'toi06hoang', '123', 'cntp', 'Hoàng Văn Tùng Tới', 0, 1, '2002-08-19', '10DHTP10', 'Cao Bằng');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_list`
--
ALTER TABLE `attendance_list`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `fk_attendance_list_class` (`class_id`);

--
-- Indexes for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `attendance_id` (`attendance_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `fk_class_teacher` (`teacher_id`),
  ADD KEY `fk_class_course` (`course_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `fk_cour_subj` (`subject_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `student_class`
--
ALTER TABLE `student_class`
  ADD PRIMARY KEY (`student_id`,`class_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `fK_sj_dep` (`department_id`);

--
-- Indexes for table `_user`
--
ALTER TABLE `_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `_user_id` (`_user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_department` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_records`
--
ALTER TABLE `attendance_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `_user`
--
ALTER TABLE `_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance_list`
--
ALTER TABLE `attendance_list`
  ADD CONSTRAINT `fk_attendance_list_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `attendance_records`
--
ALTER TABLE `attendance_records`
  ADD CONSTRAINT `attendance_records_ibfk_1` FOREIGN KEY (`attendance_id`) REFERENCES `attendance_list` (`attendance_id`),
  ADD CONSTRAINT `attendance_records_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `_user` (`_user_id`);

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `fk_class_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`),
  ADD CONSTRAINT `fk_class_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `_user` (`_user_id`);

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `fk_cour_subj` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`subject_id`);

--
-- Constraints for table `student_class`
--
ALTER TABLE `student_class`
  ADD CONSTRAINT `student_class_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `_user` (`_user_id`),
  ADD CONSTRAINT `student_class_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `fK_sj_dep` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);

--
-- Constraints for table `_user`
--
ALTER TABLE `_user`
  ADD CONSTRAINT `fk_user_department` FOREIGN KEY (`department_id`) REFERENCES `department` (`department_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
