-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2024 at 01:08 AM
-- Server version: 5.7.44-log
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `usedcard_production`
--

-- --------------------------------------------------------

--
-- Table structure for table `water_savings_calculation_category`
--

CREATE TABLE `water_savings_calculation_category` (
  `savings_calculation_category_id` int(11) NOT NULL,
  `savings_calculation_category` varchar(100) NOT NULL,
  `commodity_flg` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `water_savings_calculation_category`
--

INSERT INTO `water_savings_calculation_category` (`savings_calculation_category_id`, `savings_calculation_category`, `commodity_flg`) VALUES
(1, 'Divide Cost Proportially by Materials Weight', 0),
(2, 'Divide Cost Proportially by Materials Costs', 0),
(3, 'Pass-through', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `water_savings_calculation_category`
--
ALTER TABLE `water_savings_calculation_category`
  ADD PRIMARY KEY (`savings_calculation_category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `water_savings_calculation_category`
--
ALTER TABLE `water_savings_calculation_category`
  MODIFY `savings_calculation_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
