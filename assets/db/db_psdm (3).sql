-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2022 at 09:29 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_psdm`
--

-- --------------------------------------------------------

--
-- Table structure for table `payroll_golongan`
--

CREATE TABLE `payroll_golongan` (
  `g_id` int(11) NOT NULL,
  `g_nama` varchar(50) NOT NULL,
  `g_pendidikan` varchar(50) DEFAULT NULL,
  `g_keterangan` varchar(255) DEFAULT NULL,
  `g_created_at` datetime NOT NULL,
  `g_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payroll_golongan`
--

INSERT INTO `payroll_golongan` (`g_id`, `g_nama`, `g_pendidikan`, `g_keterangan`, `g_created_at`, `g_updated_at`) VALUES
(1, 'A1', 'SMA', 'Pelaksana kebersihan ruangan dan taman, Pelaksana cucian, Pelaksana distribusi makan karyawan, Pembantu juru masak', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(2, 'A2', 'SMA', 'Petugas ekspedisi, Petugas kamar jahit, Pelaksana pemeliharaan sarana, Petugas dapur ruangan', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(3, 'A3', 'SMA', 'Petugas kendaraan, Keamanan, Petugas TPP/RM, Petugas administrasi, Juru masak, Pembantu orang sakit (POS), Juru racik / pembantu AA', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(4, 'A4', 'SMA', 'Perkarya, Kasir pembantu, Petugas informasi, Petugas pratama rontgen', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(5, 'B1', 'SMA Plus SMK Kesehatan', 'Perawat SPK, Perawat gigi, Ass Apoteker, Ass Analis, P2B', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(6, 'B2', 'Akademi', 'Ahli Madya', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(7, 'D3', 'D4', 'Perawat mahir bedah, Perawat mahir bius', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(8, 'B4', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(9, 'C1', 'Sarjana', 'Sarjana keperawatan, Sarjana gizi, Sarjana hukum, Sarjana ekonomi, Sarjana psikolog, Sarjana teknik, Sarjana lain', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(10, 'C2', 'Dokter', 'Dokter umum, Dokter gigi, S2 manajemen, Apoteker', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(11, 'C3', 'Spesialis', 'Dokter spesialis', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(12, 'C4', 'Subspesialis', 'Dokter subspesialis', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(13, 'D4', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(14, 'D2', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(15, 'D3', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(16, 'D4', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_golongan_detail`
--

CREATE TABLE `payroll_golongan_detail` (
  `gd_id` int(11) NOT NULL,
  `gd_golongan_id` int(11) NOT NULL,
  `gd_tahun` varchar(2) NOT NULL,
  `gd_nominal` double NOT NULL,
  `gd_created_at` datetime NOT NULL,
  `gd_updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_tunjangan_fungsi`
--

CREATE TABLE `payroll_tunjangan_fungsi` (
  `tf_id` int(11) NOT NULL,
  `tf_nama` varchar(255) NOT NULL,
  `tf_keterangan` varchar(255) DEFAULT NULL,
  `tf_baru` double DEFAULT NULL,
  `tf_lama` double DEFAULT NULL,
  `tf_status` int(1) NOT NULL DEFAULT 1 COMMENT '0 = tdk aktif, 1 = aktif',
  `tf_created_at` datetime NOT NULL,
  `tf_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payroll_tunjangan_fungsi`
--

INSERT INTO `payroll_tunjangan_fungsi` (`tf_id`, `tf_nama`, `tf_keterangan`, `tf_baru`, `tf_lama`, `tf_status`, `tf_created_at`, `tf_updated_at`) VALUES
(1, 'Golongan I', 'Pelaksana kebersihan ruangan dan taman, Pelaksana cucian, Pelaksana distribusi makan karyawan, Pembantu juru masak', 101800, 145500, 1, '2022-06-24 12:51:45', NULL),
(2, 'Golongan II', 'Petugas ekspedisi, Petugas kamar jahit, Pelaksana pemeliharaan sarana, Petugas dapur ruangan, Juru masak, Pembantu orang sakit (POS), Juru racik / pembantu AA', 116400, 167300, 1, '2022-06-24 12:53:41', NULL),
(3, 'Golongan III', 'Petugas TPP / RM (setara SMA), Pekarya, Petugas informasi', 138200, 181800, 1, '2022-06-24 12:55:14', NULL),
(4, 'Golongan IV', 'Petugas kendaraan, Teknisi (STM-D1), Keamanan, Petugas administrasi', 159400, 212500, 1, '2022-06-24 12:56:03', NULL),
(5, 'Golongan V', 'Perawat gigi, Perawat SPK', 167000, 222600, 1, '2022-06-24 12:56:30', NULL),
(6, 'Golongan VI', 'Asisten apoteker, Asisten analis, P2B', 180900, 243500, 1, '2022-06-24 13:54:45', NULL),
(7, 'Golongan VII', 'Ahli madya (D-3) Sekretaris / Akuntasi / Manajemen Informasi & Teknologi (Mitek)', 208700, 278300, 1, '2022-06-24 13:56:34', NULL),
(8, 'Golongan IX', 'D-4 Keperawatan (Perioperatif/Anestesi/dsb)/Rehabilitasi medis/ Radiologi/ Gizi/ Rekam medis/ Laborat', 253000, 316300, 1, '2022-06-24 13:57:56', NULL),
(9, 'Golongan X', 'Sarjana hukum, Sarjana ekonomi, Sarjana komputer, Sarjana teknik, Sarjana pendidikan, Sarjana psikolog non profesi, Sarjana lain (S1 lain)', 306000, 408000, 1, '2022-06-24 13:59:24', NULL),
(10, 'Golongan XI', 'Sarjana keperawatan, Sarjana psikologi, Sarjana gizi, Apoteker, Dokter umum, Dokter gigi', 329000, 431000, 1, '2022-06-24 14:00:02', NULL),
(11, 'Golongan XIII', 'S2 Manajemen', 340500, 442500, 1, '2022-06-24 14:00:26', NULL),
(12, 'Golongan XIV', 'Dokter spesialis', 352000, 454000, 1, '2022-06-24 14:00:58', NULL),
(13, 'Golongan XV', 'Dokter sup spesialis', 363500, 465500, 1, '2022-06-24 14:01:20', NULL),
(14, 'Golongan XVI', 'D3 Keperawatan / D3 Radiografer, D4 Radiografer', 302500, 385000, 1, '2022-06-24 14:02:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payroll_tunjangan_jabatan`
--

CREATE TABLE `payroll_tunjangan_jabatan` (
  `tj_id` int(11) NOT NULL,
  `tj_nama` varchar(255) NOT NULL,
  `tj_keterangan` varchar(255) DEFAULT NULL,
  `tj_baru` double NOT NULL,
  `tj_lama` double NOT NULL,
  `tj_status` int(1) NOT NULL DEFAULT 1 COMMENT '0 = tdk aktif, 1 = aktif',
  `tj_created_at` datetime NOT NULL,
  `tj_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payroll_tunjangan_jabatan`
--

INSERT INTO `payroll_tunjangan_jabatan` (`tj_id`, `tj_nama`, `tj_keterangan`, `tj_baru`, `tj_lama`, `tj_status`, `tj_created_at`, `tj_updated_at`) VALUES
(1, 'Kepala Bidang / Kepala Bagian', NULL, 470000, 470000, 1, '2022-06-24 14:02:42', NULL),
(2, 'Kepala SPI', NULL, 300000, 300000, 1, '2022-06-24 14:02:52', NULL),
(3, 'Kepala Instalasi / Kepala Subbagian', NULL, 250000, 250000, 1, '2022-06-24 14:03:12', NULL),
(4, 'Kepala Ruang / Urusan', NULL, 200000, 200000, 1, '2022-06-24 14:03:26', NULL),
(5, 'Kepala Seksi SDM Keperawatan / Askep / Komite / IPCN / Tim Mutu', NULL, 250000, 250000, 1, '2022-06-24 14:03:54', NULL),
(6, 'Apoteker Penanggung Jawab', NULL, 1200000, 1200000, 1, '2022-06-24 14:04:11', NULL),
(7, 'Apoteker Pendamping', NULL, 500000, 500000, 1, '2022-06-24 14:04:22', NULL),
(8, 'Programmer Beginner', NULL, 500000, 500000, 1, '2022-06-24 14:04:38', NULL),
(9, 'Programmer Expert', NULL, 1000000, 1000000, 1, '2022-06-24 14:04:50', NULL),
(10, 'Kasir', NULL, 500000, 500000, 1, '2022-06-24 14:04:57', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `payroll_golongan`
--
ALTER TABLE `payroll_golongan`
  ADD PRIMARY KEY (`g_id`);

--
-- Indexes for table `payroll_golongan_detail`
--
ALTER TABLE `payroll_golongan_detail`
  ADD PRIMARY KEY (`gd_id`);

--
-- Indexes for table `payroll_tunjangan_fungsi`
--
ALTER TABLE `payroll_tunjangan_fungsi`
  ADD PRIMARY KEY (`tf_id`);

--
-- Indexes for table `payroll_tunjangan_jabatan`
--
ALTER TABLE `payroll_tunjangan_jabatan`
  ADD PRIMARY KEY (`tj_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payroll_golongan`
--
ALTER TABLE `payroll_golongan`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
