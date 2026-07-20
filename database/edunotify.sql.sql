-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2026 at 08:09 PM
-- Server version: 8.0.46
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `result_alert_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `full_name`, `email`, `created_at`) VALUES
(1, 'admin', 'admin123', 'System Administrator', 'admin@college.com', '2026-07-17 15:20:56');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `result_id` int NOT NULL,
  `student_id` int NOT NULL,
  `subject_id` int NOT NULL,
  `semester_id` int NOT NULL,
  `marks` int NOT NULL,
  `grade` varchar(5) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`result_id`, `student_id`, `subject_id`, `semester_id`, `marks`, `grade`, `status`, `created_at`) VALUES
(3, 1, 6, 5, 98, 'A+', 'PASS', '2026-07-18 06:36:58'),
(4, 4, 5, 5, 76, 'B', 'PASS', '2026-07-18 07:57:22'),
(5, 3, 5, 5, 71, 'B', 'PASS', '2026-07-19 14:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `semester_id` int NOT NULL,
  `semester_name` varchar(50) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `is_published` tinyint(1) DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`semester_id`, `semester_name`, `academic_year`, `is_published`, `published_at`, `created_at`) VALUES
(1, 'Semester 1', '2025-2026', 1, '2026-07-20 20:36:45', '2026-07-19 12:24:14'),
(2, 'Semester 2', '2025-2026', 1, '2026-07-20 20:36:40', '2026-07-19 12:24:14'),
(3, 'Semester 3', '2026-2027', 1, '2026-07-20 20:36:29', '2026-07-19 12:24:14'),
(4, 'Semester 4', '2026-2027', 1, '2026-07-20 16:56:57', '2026-07-19 12:24:14'),
(5, 'Semester 5', '2027-2028', 1, '2026-07-20 21:23:04', '2026-07-19 12:24:14'),
(6, 'Semester 6', '2027-2028', 1, '2026-07-20 16:57:03', '2026-07-19 12:24:14'),
(7, 'Semester 7', '2028-2029', 1, '2026-07-20 20:36:49', '2026-07-19 12:24:14'),
(8, 'Semester 8', '2028-2029', 1, '2026-07-20 21:07:59', '2026-07-19 12:24:14'),
(18, 'Semester 9', '2025-2026', 1, '2026-07-20 21:08:03', '2026-07-19 13:32:55'),
(19, 'Semester 10', '2024-2025', 1, '2026-07-20 21:08:06', '2026-07-19 13:35:01');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int NOT NULL,
  `hall_ticket` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `department` varchar(20) NOT NULL,
  `year` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `hall_ticket`, `full_name`, `gender`, `dob`, `email`, `phone`, `department`, `year`, `created_at`) VALUES
(1, '21Y1A6701', 'Rahul Kumar', 'Male', '2003-06-15', '217y1a6711@mlritm.ac.in', '9876543210', 'CSE', 4, '2026-07-17 13:57:26'),
(3, '21Y1a6702', 'Aravind', 'Male', '2003-07-10', 'challurinamrutha@gmail.com', '8455791365', 'CSE', 1, '2026-07-17 17:47:56'),
(4, '21Y1a6703', 'Nithya', 'Female', '2004-04-04', 'koushikreddy230803@gmail.com', '6532321879', 'CSE', 2, '2026-07-18 07:57:02'),
(5, '21Y1A6711', 'Ken', 'Male', '2004-08-23', '21y1a6711@mlritm.ac.in', '9603457522', 'CSE', 4, '2026-07-20 15:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int NOT NULL,
  `subject_code` varchar(20) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `semester` int NOT NULL,
  `credits` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_code`, `subject_name`, `semester`, `credits`, `created_at`) VALUES
(1, 'CS301', 'DBMS', 4, 3, '2026-07-17 17:55:54'),
(5, 'CS302', 'Data Structure', 5, 3, '2026-07-17 18:07:28'),
(6, 'CS303', 'Operating Systems', 6, 2, '2026-07-17 18:13:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`semester_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `hall_ticket` (`hall_ticket`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `result_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `semester_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
