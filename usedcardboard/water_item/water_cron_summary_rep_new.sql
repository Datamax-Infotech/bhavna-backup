-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 15, 2024 at 02:41 AM
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
-- Table structure for table `water_cron_summary_rep_new`
--

CREATE TABLE `water_cron_summary_rep_new` (
  `unqid` int(10) UNSIGNED NOT NULL,
  `data_year` int(11) DEFAULT NULL,
  `warehouse_id` int(10) UNSIGNED NOT NULL,
  `outlet` varchar(50) NOT NULL,
  `weight_tot` double NOT NULL,
  `perc_val` double NOT NULL,
  `amount_tot` double NOT NULL,
  `sumtot_weight` double NOT NULL,
  `sumtot_amount` double NOT NULL,
  `other_charges` double NOT NULL,
  `Recycling_tot` double NOT NULL,
  `Ruse_tot` double NOT NULL,
  `WasteToEnergy_tot` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `water_cron_summary_rep_new`
--
ALTER TABLE `water_cron_summary_rep_new`
  ADD PRIMARY KEY (`unqid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `water_cron_summary_rep_new`
--
ALTER TABLE `water_cron_summary_rep_new`
  MODIFY `unqid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
