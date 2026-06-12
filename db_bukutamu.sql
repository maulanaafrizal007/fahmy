-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2026 at 03:09 PM
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
-- Database: `db_bukutamu`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku_tamu`
--

CREATE TABLE `buku_tamu` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `instansi` varchar(150) NOT NULL,
  `tujuan` text NOT NULL,
  `tanggal` date NOT NULL DEFAULT curdate(),
  `waktu` time NOT NULL DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buku_tamu`
--

INSERT INTO `buku_tamu` (`id`, `nama`, `instansi`, `tujuan`, `tanggal`, `waktu`) VALUES
(1, 'Fahmy', 'Sekretariat jenderal dewan perwakilan daerah', 'bertanya tentang SPMB Online', '2026-06-07', '02:42:55'),
(2, 'Endah Fujiarti', 'Sekretariat jenderal dewan perwakilan daerah republik indonesia', 'spmb online untuk jalur mutasi', '2026-06-07', '02:45:01'),
(4, 'zetta', 'Sekretariat jenderal dewan perwakilan daerah', 'ingin bertanya spmb online jalur domisili', '2026-06-07', '02:54:28'),
(5, 'thamrin', 'Sekretariat jenderal dewan perwakilan daerah', 'spmb online', '2026-06-07', '03:27:15'),
(7, 'alif', 'paud  darussalam', 'konsultasi spmb online', '2026-06-07', '06:52:10'),
(8, 'zaenudin', 'paud  darussalam', 'konsultasi spmb online', '2026-06-07', '15:56:58'),
(9, 'suardi', 'kebon jeruk', 'konsultasi spmb online jalur mutasi', '2026-06-09', '04:21:11'),
(10, 'freya', 'TK Aisiyah Bustanul Athfal', 'konsultasi spmb online SD', '2026-06-12', '20:07:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku_tamu`
--
ALTER TABLE `buku_tamu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku_tamu`
--
ALTER TABLE `buku_tamu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
