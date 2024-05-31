-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2023 at 10:53 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `econav`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `name` varchar(40) NOT NULL,
  `uname` varchar(60) NOT NULL,
  `pswd` varchar(60) NOT NULL,
  `id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`name`, `uname`, `pswd`, `id`) VALUES
('ADMIN', 'admin@evs.com', '0e7517141fb53f21ee439b355b5a1d0a', 1);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `sid` int(11) NOT NULL,
  `bid` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_slot` varchar(20) NOT NULL,
  `unit` float NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `uid`, `sid`, `bid`, `date`, `time_slot`, `unit`, `status`) VALUES
(2, 2, 6, 1, '2023-11-30', '08.00 AM - 03.59 PM', 0, 0),
(3, 2, 6, 1, '2023-11-30', '08.00 AM - 03.59 PM', 20, 1),
(6, 2, 6, 1, '2023-11-30', '04.00 PM - 11.59 PM', 0, 1),
(7, 2, 17, 2, '2023-12-05', '08.00 AM - 03.59 PM', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bunk_admin`
--

CREATE TABLE `bunk_admin` (
  `id` int(8) NOT NULL,
  `name` varchar(60) NOT NULL,
  `uname` varchar(60) NOT NULL,
  `pswd` varchar(60) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(140) NOT NULL,
  `district` varchar(60) NOT NULL,
  `state` varchar(60) NOT NULL,
  `pin` varchar(6) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bunk_admin`
--

INSERT INTO `bunk_admin` (`id`, `name`, `uname`, `pswd`, `phone`, `address`, `district`, `state`, `pin`, `status`) VALUES
(1, 'K-EV Station', 'kevpta@gmail.com', '9befee61b8e513733d36d0d1b57994f9', '7034834276', 'Chuttippara', 'Pathanamthitta', 'Kerala', '689645', 1),
(2, 'HP EV Station', 'ev.hp@gmail.com', '6618dfe4acb8db5b4beca4d99c36e6ad', '1234567890', 'New Street Road', 'Kanyakumari', 'Kerala', '629702', 1),
(3, 'Tesla Charging Station', 'tesla.pta@gmail.com', '657f48faae5af1208fddde2ac5d7682a', '1234567890', 'Pandalam', 'Pathanamthitta', 'Kerala', '689645', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bunk_slots`
--

CREATE TABLE `bunk_slots` (
  `sid` int(11) NOT NULL,
  `sname` varchar(7) NOT NULL,
  `stype` varchar(25) NOT NULL,
  `sload` int(11) NOT NULL,
  `svolt` int(11) NOT NULL,
  `scon` text NOT NULL,
  `status` varchar(15) NOT NULL,
  `curstat` varchar(15) NOT NULL,
  `time` time NOT NULL,
  `bid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bunk_slots`
--

INSERT INTO `bunk_slots` (`sid`, `sname`, `stype`, `sload`, `svolt`, `scon`, `status`, `curstat`, `time`, `bid`) VALUES
(4, 'SLT0001', 'IEC 62196-2', 22, 230, 'Type 2 Connectors', 'Operational', 'AVAILABLE', '00:00:00', 1),
(5, 'SLT0002', 'IEC 62196-2', 22, 230, 'Type 2 Connectors', 'Operational', 'AVAILABLE', '00:00:00', 1),
(6, 'SLT0003', 'IEC 62196-2', 22, 230, 'Type 2 Connectors', 'Operational', 'AVAILABLE', '00:00:00', 1),
(7, 'SLT0004', 'IEC 62196-2', 22, 230, 'Type 2 Connectors', 'Operational', 'AVAILABLE', '00:00:00', 1),
(8, 'SLT0005', 'Scame Type 3', 50, 400, 'Type 3 Connectors', 'Operational', 'AVAILABLE', '00:00:00', 1),
(9, 'SLT0006', 'Scame Type 3', 50, 400, 'Type 3 Connectors', 'Operational', 'AVAILABLE', '00:00:00', 1),
(12, 'SLT0007', 'Tesla Supercharger', 250, 800, 'Tesla Supercharge Connector', 'Out of Service', 'AVAILABLE', '00:00:00', 1),
(13, 'SLT0001', 'Tesla Supercharger', 250, 800, 'Tesla Supercharger', 'Operational', 'AVAILABLE', '00:00:00', 3),
(14, 'SLT0002', 'CHAdeMO', 50, 400, 'CHAdeMO', 'Operational', 'AVAILABLE', '00:00:00', 3),
(15, 'SLT0001', 'IEC 62196-2', 22, 230, 'Type 2 Connector', 'Operational', 'AVAILABLE', '00:00:00', 2),
(16, 'SLT0002', 'IEC 62196-2', 22, 230, 'Type 2 Connector', 'Operational', 'AVAILABLE', '00:00:00', 2),
(17, 'SLT0003', 'CCS', 100, 400, 'CCS Connector', 'Operational', 'AVAILABLE', '00:00:00', 2);

-- --------------------------------------------------------

--
-- Table structure for table `geolocation`
--

CREATE TABLE `geolocation` (
  `id` int(11) NOT NULL,
  `location` text NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `geolocation`
--

INSERT INTO `geolocation` (`id`, `location`, `latitude`, `longitude`) VALUES
(1, 'https://www.google.co.in/maps/place/Chuttippara/@9.2620763,76.7852664,15z/data=!4m14!1m7!3m6!1s0x3b06151a76fbb60d:0xf11bf55266703150!2sChuttippara!8m2!3d9.2649991!4d76.7957378!16s%2Fg%2F1tgvc17n!3m5!1s0x3b06151a76fbb60d:0xf11bf55266703150!8m2!3d9.2649991!4d76.7957378!16s%2Fg%2F1tgvc17n?entry=ttu', 9.26208, 76.7853),
(2, 'https://www.google.co.in/maps/place/Aisu+E+Centre/@8.0925096,77.5426205,19.43z/data=!4m15!1m8!3m7!1s0x3b04ed3d2a087861:0x1e790e896aeffaa0!2sKanyakumari,+Tamil+Nadu!3b1!8m2!3d8.0843512!4d77.5495019!16zL20vMDF0eG1s!3m5!1s0x3b04ed84c8d26ca5:0xb39fcee85a6f677c!8m2!3d8.0926073!4d77.5424993!16s%2Fg%2F11sc0ftjtp?entry=ttu', 8.09251, 77.5426),
(3, 'https://www.google.co.in/maps/place/Pandalam,+Kerala/@9.2225782,76.6694699,15z/data=!3m1!4b1!4m6!3m5!1s0x3b06172717978767:0x8f1885cdfa41a69!8m2!3d9.2249929!4d76.678471!16zL20vMDlwaG4x?entry=ttu', 9.22258, 76.6695);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `uname` varchar(60) NOT NULL,
  `pswd` varchar(60) NOT NULL,
  `phone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `uname`, `pswd`, `phone`) VALUES
(2, 'Sagar', 'sagar@gmail.com', 'cf299ba19ce28e89a055d8db3e5578a0', '9847318245'),
(3, 'Ajmal', 'majmals1998@gmail.com', '6618dfe4acb8db5b4beca4d99c36e6ad', '7034834276');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`uname`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bid` (`uid`),
  ADD KEY `sid` (`sid`),
  ADD KEY `bid_2` (`bid`);

--
-- Indexes for table `bunk_admin`
--
ALTER TABLE `bunk_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uname` (`uname`);

--
-- Indexes for table `bunk_slots`
--
ALTER TABLE `bunk_slots`
  ADD PRIMARY KEY (`sid`),
  ADD KEY `bid` (`bid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uname` (`uname`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bunk_admin`
--
ALTER TABLE `bunk_admin`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bunk_slots`
--
ALTER TABLE `bunk_slots`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `bunk_slots` (`sid`),
  ADD CONSTRAINT `booking_ibfk_3` FOREIGN KEY (`bid`) REFERENCES `bunk_admin` (`id`);

--
-- Constraints for table `bunk_slots`
--
ALTER TABLE `bunk_slots`
  ADD CONSTRAINT `bunk_slots_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `bunk_admin` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
