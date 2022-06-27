-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2022 at 06:59 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
-- Table structure for table `m_jabatan`
--

CREATE TABLE `m_jabatan` (
  `jb_id` int(11) NOT NULL,
  `jb_nama` varchar(100) NOT NULL,
  `jb_parent` int(11) NOT NULL DEFAULT '0',
  `jb_uk_id` int(11) NOT NULL DEFAULT '0',
  `jb_level` int(11) NOT NULL DEFAULT '0',
  `jb_status` int(1) NOT NULL DEFAULT '1',
  `jb_created_at` datetime DEFAULT NULL,
  `jb_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `m_jabatan`
--

INSERT INTO `m_jabatan` (`jb_id`, `jb_nama`, `jb_parent`, `jb_uk_id`, `jb_level`, `jb_status`, `jb_created_at`, `jb_updated_at`) VALUES
(1, 'Kepala Instalasi', 0, 0, 3, 1, NULL, NULL),
(2, 'KUPP', 1, 0, 4, 1, NULL, NULL),
(3, 'Pelaksana', 2, 0, 5, 1, NULL, NULL),
(4, 'Kepala Subbagian', 0, 0, 3, 1, NULL, NULL),
(5, 'Staff', 4, 0, 5, 1, NULL, NULL),
(6, 'Yayasan', 0, 0, 1, 1, NULL, NULL),
(7, 'Direktur Utama', 0, 0, 1, 1, NULL, NULL),
(8, 'Direktur Pelayanan', 7, 0, 1, 1, NULL, NULL),
(9, 'Direktur Umum dan Keuangan', 7, 0, 1, 1, NULL, NULL),
(10, 'Bidang Pelayanan Medis', 8, 0, 2, 1, NULL, NULL),
(11, 'Bidang Penunjang Medis', 8, 0, 2, 1, NULL, NULL),
(12, 'Bidang Keperawatan', 8, 0, 2, 1, NULL, NULL),
(13, 'Bagian Umum', 9, 0, 2, 1, NULL, NULL),
(14, 'Bagian Keuangan', 9, 0, 2, 1, NULL, NULL),
(15, 'Bagian Administrasi', 9, 0, 2, 1, NULL, NULL),
(16, 'Bagian Pemasaran', 7, 0, 2, 1, NULL, NULL),
(17, 'Urusan Jaringan dan Hardware', 4, 31, 5, 1, NULL, '2022-05-11 11:30:13'),
(18, 'Urusan Software', 4, 31, 5, 1, NULL, '2022-05-11 11:30:13'),
(19, 'Kepala seksi', 0, 0, 3, 1, NULL, NULL),
(20, 'Pelaksana seksi', 19, 0, 5, 1, NULL, NULL),
(21, 'Staff pemasaran', 16, 24, 5, 1, NULL, NULL),
(22, 'Perawat Pelaksana', 2, 0, 5, 1, NULL, NULL),
(23, 'Ahli Gizi', 1, 17, 5, 1, NULL, NULL),
(24, 'Pelaksana Urusan Logistik', 1, 17, 5, 1, '2022-05-07 12:40:36', NULL),
(25, 'Pelaksana Pengolah Makanan Pasien', 1, 17, 5, 1, '2022-05-07 12:40:36', NULL),
(26, 'Pelaksana Urusan Pantry Ruangan', 1, 17, 5, 1, '2022-05-07 12:40:36', NULL),
(27, 'Urusan Inventaris Logistik', 4, 33, 5, 1, '2022-05-09 09:53:38', NULL),
(28, 'Urusan Penerimaan dan Distribusi Logistik', 4, 33, 5, 1, '2022-05-09 09:53:38', NULL),
(29, 'Urusan Pembelian Logistik', 4, 33, 5, 1, '2022-05-09 09:53:38', NULL),
(30, 'Fisikawan Medis', 1, 21, 5, 1, '2022-05-09 13:43:32', NULL),
(31, 'Radiografer', 1, 21, 5, 1, '2022-05-09 13:43:32', NULL),
(32, 'Urusan Kompensasi Kepegawaian', 4, 38, 5, 1, '2022-05-12 11:43:38', NULL),
(33, 'Urusan Administrasi Kepegawaian', 4, 38, 5, 1, '2022-05-12 11:43:38', NULL),
(34, 'Urusan Diklat', 4, 38, 5, 1, '2022-05-12 11:43:38', NULL),
(35, 'Sekretaris', 15, 37, 5, 1, '2022-05-12 14:31:32', NULL),
(36, 'Pemasaran Eksternal', 16, 24, 5, 1, '2022-05-21 13:20:52', NULL),
(37, 'Administrasi Pemasaran', 16, 24, 5, 1, '2022-05-21 13:20:52', NULL),
(38, 'Urusan Media Sosial', 16, 24, 5, 1, '2022-05-21 13:20:52', NULL),
(39, 'Kepala Seksi Pengembangan Mutu Keperawatan', 12, 51, 4, 1, '2022-05-23 10:05:41', NULL),
(40, 'Kepala Seksi SDM Keperawatan', 12, 51, 4, 1, '2022-05-23 10:05:41', NULL),
(41, 'IPCN', 7, 51, 5, 1, '2022-05-23 10:07:22', NULL),
(42, 'Komite Keperawatan', 7, 51, 4, 1, '2022-05-23 10:08:45', NULL),
(43, 'Pelayanan pelanggan', 4, 28, 5, 1, '2022-05-23 13:00:13', NULL),
(44, 'Teknisi Elektromedis (ATEM)', 4, 29, 5, 1, '2022-05-24 11:45:07', '2022-05-24 11:47:38'),
(45, 'Teknisi Umum', 4, 29, 5, 1, '2022-05-24 11:45:07', '2022-05-24 11:47:38'),
(46, 'Admin Pemeliharaan', 4, 29, 5, 1, '2022-05-24 11:45:07', '2022-05-24 11:47:38'),
(47, 'Supervisi mutu dan operasional', 4, 29, 5, 1, '2022-05-24 11:45:07', '2022-05-24 11:47:38'),
(48, 'Sipil', 4, 29, 5, 1, '2022-05-24 11:45:07', '2022-05-24 11:47:38'),
(49, 'Driver', 4, 29, 5, 1, '2022-05-24 11:47:38', NULL),
(50, 'Susteran', 4, 46, 5, 1, '2022-05-24 11:47:38', NULL),
(51, 'Pelaksana Urusan UPL/UKL', 4, 47, 5, 1, '2022-05-24 11:52:25', '2022-05-28 08:48:05'),
(52, 'Kepala Urusan UPL/UKL', 4, 47, 4, 1, '2022-05-24 14:30:24', '2022-05-28 10:33:46'),
(53, 'Pengentri', 4, 25, 5, 1, '2022-05-25 14:30:04', NULL),
(54, 'Scanning', 4, 25, 5, 1, '2022-05-25 14:30:04', NULL),
(55, 'Penataan', 4, 25, 5, 1, '2022-05-25 14:30:04', NULL),
(56, 'Driver', 4, 52, 5, 1, '2022-05-25 14:32:41', NULL),
(57, 'Bagian Hutang', 4, 27, 5, 1, '2022-05-25 14:52:08', NULL),
(58, 'Bagian Piutang', 4, 27, 5, 1, '2022-05-25 14:52:08', NULL),
(59, 'Bagian Penagihan', 4, 27, 5, 1, '2022-05-25 14:52:08', NULL),
(60, 'Bagian Administrasi Piutang', 4, 27, 5, 1, '2022-05-25 14:52:08', NULL),
(61, 'Bagian Akuntansi', 4, 27, 5, 1, '2022-05-25 14:52:08', NULL),
(62, 'Kepala Urusan Linen', 4, 30, 4, 1, '2022-05-28 08:18:12', NULL),
(63, 'Urusan Mesin', 62, 30, 5, 1, '2022-05-28 08:19:40', '2022-06-03 09:52:31'),
(64, 'Linen Ruangan', 62, 30, 5, 1, '2022-05-28 08:19:40', '2022-06-03 09:52:31'),
(65, 'Linen Sentral', 62, 30, 5, 1, '2022-05-28 08:19:40', '2022-06-03 09:52:31'),
(66, 'Kepala Urusan Keamanan', 4, 34, 4, 1, '2022-05-28 08:48:40', NULL),
(67, 'Pelaksana Urusan Keamanan', 4, 34, 5, 1, '2022-05-28 08:48:40', NULL),
(68, 'Cleaning Service (CS)', 52, 47, 5, 1, '2022-05-28 10:36:03', NULL),
(69, 'Pengawas CS', 52, 47, 5, 1, '2022-05-28 10:36:03', NULL),
(70, 'Pelaksana Furniture', 52, 47, 5, 1, '2022-05-28 10:36:03', NULL),
(71, 'Pelaksana Taman', 52, 47, 5, 1, '2022-05-28 10:36:03', NULL),
(72, 'Kepala Kantor Yayasan', 6, 53, 3, 1, '2022-05-30 14:34:38', NULL),
(73, 'Urusan Keuangan', 6, 53, 5, 1, '2022-05-30 14:34:38', NULL),
(74, 'Urusan Korespondensi', 6, 53, 5, 1, '2022-05-30 14:34:38', NULL),
(75, 'Pengentri Poli', 2, 15, 5, 1, '2022-05-31 09:24:50', '2022-05-31 09:26:31'),
(76, 'Admin', 4, 26, 5, 1, '2022-05-31 10:08:44', '2022-06-03 08:13:38'),
(77, 'Urusan HR Dokter', 4, 26, 5, 1, '2022-05-31 10:08:44', '2022-06-03 08:13:38'),
(78, 'Ekspedisi', 4, 26, 5, 1, '2022-05-31 10:08:44', '2022-06-03 08:13:38'),
(79, 'Kasir', 4, 26, 5, 1, '2022-05-31 10:08:44', '2022-06-03 08:13:38'),
(80, 'Apoteker', 83, 16, 5, 1, '2022-05-31 10:17:19', '2022-05-31 10:57:39'),
(81, 'TTK', 83, 16, 5, 1, '2022-05-31 10:17:19', '2022-05-31 10:57:39'),
(83, 'Apoteker Penanggung Jawab', 0, 16, 5, 1, '2022-05-31 00:00:00', '2022-05-31 10:57:39'),
(84, 'Entry dokumen', 1, 19, 5, 1, '2022-05-31 11:12:57', NULL),
(85, 'Urusan Penyimpanan', 1, 19, 5, 1, '2022-05-31 11:12:57', NULL),
(86, 'Assembling', 1, 19, 5, 1, '2022-05-31 11:12:57', NULL),
(87, 'Urusan Pelaporan', 1, 19, 5, 1, '2022-05-31 11:12:57', NULL),
(88, 'Korespondensi', 1, 19, 5, 1, '2022-05-31 11:12:57', NULL),
(89, 'TPP', 1, 19, 5, 1, '2022-05-31 11:12:57', NULL),
(90, 'Urusan Logistik Rekam Medis', 1, 19, 5, 1, '2022-05-31 11:12:57', NULL),
(91, 'Urusan Pendaftaran Online', 1, 19, 5, 1, '2022-05-31 11:12:57', NULL),
(92, 'Dokter Pelaksana', 1, 0, 5, 1, '2022-06-02 12:01:21', NULL),
(93, 'Subbagian Keuangan', 4, 26, 5, 1, '2022-06-03 08:13:38', NULL),
(94, 'Case manager', 12, 49, 5, 1, '2022-06-03 09:09:39', '2022-06-14 11:40:05'),
(95, 'Linen Kamar Jahit', 62, 30, 5, 1, '2022-06-03 09:52:31', NULL),
(96, 'Ketua Umum Yayasan', 0, 54, 1, 1, NULL, NULL),
(97, 'Sekretaris Umum Yayasan', 0, 55, 5, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_jabatan`
--
ALTER TABLE `m_jabatan`
  ADD PRIMARY KEY (`jb_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
