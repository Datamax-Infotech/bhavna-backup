-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 15, 2024 at 02:40 AM
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
-- Table structure for table `water_cron_fordash_piechart_new`
--

CREATE TABLE `water_cron_fordash_piechart_new` (
  `unqid` int(10) UNSIGNED NOT NULL,
  `data_year` int(11) DEFAULT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `sumtot` double NOT NULL,
  `recycling_tot` double NOT NULL,
  `ruse_tot` double NOT NULL,
  `outlet` varchar(100) NOT NULL,
  `weight` double NOT NULL,
  `display_order` int(10) UNSIGNED NOT NULL,
  `WasteToEnergy_tot` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `water_cron_fordash_piechart_new`
--
ALTER TABLE `water_cron_fordash_piechart_new`
  ADD PRIMARY KEY (`unqid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `water_cron_fordash_piechart_new`
--
ALTER TABLE `water_cron_fordash_piechart_new`
  MODIFY `unqid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
