-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 01, 2024 at 09:45 AM
-- Server version: 5.7.44-log
-- PHP Version: 7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ucbdata_usedcard_production`
--

-- --------------------------------------------------------

--
-- Table structure for table `water_ocr_mapping_item_list_table_custom_header`
--

CREATE TABLE `water_ocr_mapping_item_list_table_custom_header` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `table_no` int(11) NOT NULL,
  `row_no` int(11) NOT NULL,
  `header_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `water_ocr_mapping_item_list_table_custom_header`
--
ALTER TABLE `water_ocr_mapping_item_list_table_custom_header`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `water_ocr_mapping_item_list_table_custom_header`
--
ALTER TABLE `water_ocr_mapping_item_list_table_custom_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
