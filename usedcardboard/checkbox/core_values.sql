-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 21, 2024 at 05:11 AM
-- Server version: 5.7.44-log
-- PHP Version: 8.1.27

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
-- Table structure for table `core_values`
--

CREATE TABLE `core_values` (
  `id` int(11) NOT NULL,
  `title` varchar(225) NOT NULL,
  `description` text NOT NULL,
  `date` datetime NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `core_values`
--

INSERT INTO `core_values` (`id`, `title`, `description`, `date`, `updated_by`) VALUES
(1, 'Core Values', '								<p class=\"core-value-heading\" style=\"text-align: center; \">Reliability</p>\n								<p class=\"core-value-desc\" style=\"text-align: center;\">We do what we are supposed to do, when we are supposed to do it, or we communicate otherwise in advance.</p>\n								<p class=\"core-value-heading\" style=\"text-align: center;\">Transparent Integrity</p>\n								<p class=\"core-value-desc\" style=\"text-align: center;\">Mistakes happen, but when we mess up, we fess up...openly and honestly.</p>\n								<p class=\"core-value-heading\" style=\"text-align: center;\">Innovation</p>\n								<p class=\"core-value-desc\" style=\"text-align: center;\">We proactively seek new information and perspective so we can apply it to our current and future marketplace, thus continuing to always pioneer.</p>\n								<p class=\"core-value-heading\" style=\"text-align: center;\">Bold, Relentless Passion</p>\n								<p class=\"core-value-desc\" style=\"text-align: center;\">We are confident in our abilities and proactively seek opportunities to create synergies with suppliers, customers, partners, and employees. We know that rarely the first attempt is successful, and we are not afraid to try countless times if we believe value can be created.</p>\n								<p class=\"core-value-heading\" style=\"text-align: center;\">Sustainable Sustainability</p>\n								<p class=\"core-value-desc\" style=\"text-align: center;\">We believe in sustainability programs that last. We focus on financial results, in addition to environmental.</p>\n', '2023-12-15 12:55:56', 22);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `core_values`
--
ALTER TABLE `core_values`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `core_values`
--
ALTER TABLE `core_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
