-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 07:48 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_donasi`
--

CREATE TABLE `data_donasi` (
  `id` int(11) NOT NULL,
  `Nama_Donasi` varchar(70) NOT NULL,
  `Target_Donasi` int(11) NOT NULL,
  `ussername` varchar(70) NOT NULL,
  `Progress` int(11) NOT NULL,
  `Penerima` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_donasi`
--

INSERT INTO `data_donasi` (`id`, `Nama_Donasi`, `Target_Donasi`, `ussername`, `Progress`, `Penerima`) VALUES
(6, 'Korban Banjir', 500000, 'nur12', 0, 'Warga blora');

-- --------------------------------------------------------

--
-- Table structure for table `data_donatur`
--

CREATE TABLE `data_donatur` (
  `id` int(11) NOT NULL,
  `Nama` varchar(70) NOT NULL,
  `Pekerjaan` varchar(70) NOT NULL,
  `Ussername` varchar(70) NOT NULL,
  `Password` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_donatur`
--

INSERT INTO `data_donatur` (`id`, `Nama`, `Pekerjaan`, `Ussername`, `Password`) VALUES
(6, 'Nuryanto12', 'Wiraswasta', 'nur12', '$2y$10$Hu2dkB1kxQl0D3sycNUhlOp0PO4MEQtzcWctzTQxxSf7tlk7VJHNq'),
(8, 'siregar', 'Mahasiswa', 'sir12', '$2y$10$MGR/ltanjusShkKQ1r7DU.dCDEKDJTkABhFz6t.Iki6hVX1YduL16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_donasi`
--
ALTER TABLE `data_donasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ussername` (`ussername`);

--
-- Indexes for table `data_donatur`
--
ALTER TABLE `data_donatur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Ussername` (`Ussername`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_donasi`
--
ALTER TABLE `data_donasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `data_donatur`
--
ALTER TABLE `data_donatur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_donasi`
--
ALTER TABLE `data_donasi`
  ADD CONSTRAINT `data_donasi_ibfk_1` FOREIGN KEY (`ussername`) REFERENCES `data_donatur` (`Ussername`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
