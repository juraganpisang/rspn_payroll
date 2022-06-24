-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2022 at 03:16 AM
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
(1, 'Golongan I', 'Pelaksana', 90000, 200000, 1, '2022-06-23 11:00:33', '2022-06-23 11:03:31');

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
(1, 'Tunjangan Gol', NULL, 1231230, 123000, 1, '2022-06-23 12:24:38', '2022-06-23 12:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_user`
--

CREATE TABLE `payroll_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(32) DEFAULT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `user_priv` varchar(20) NOT NULL DEFAULT 'all' COMMENT 'ALL, UP, IKO, IGD, IRJ, IRI',
  `user_level` int(11) NOT NULL DEFAULT 5 COMMENT '1 : Administrator\r\n2 : Verifikator\r\n3 : Direksi\r\n4 : Ka. Unit/ Ruangan\r\n5 : Penginput',
  `user_org_id` int(11) DEFAULT NULL,
  `user_uk_id` varchar(10) DEFAULT NULL,
  `user_record_status` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payroll_user`
--

INSERT INTO `payroll_user` (`user_id`, `user_name`, `user_password`, `user_fullname`, `user_priv`, `user_level`, `user_org_id`, `user_uk_id`, `user_record_status`) VALUES
(1, 'admin', 'NIRMALApanti50', 'Administrator', 'all', 1, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_user_mapping`
--

CREATE TABLE `payroll_user_mapping` (
  `id_user` int(11) NOT NULL DEFAULT 0,
  `id_gol_gaji` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `id_tunj_jabatan` int(11) NOT NULL,
  `id_tunj_fungsi` int(11) NOT NULL,
  `is_transport` int(1) NOT NULL,
  `is_bpjs_jamsostek` int(1) NOT NULL,
  `is_bpjs_pensiun` int(1) NOT NULL,
  `is_bpjs_kesehatan` int(1) NOT NULL,
  `penambahan` double NOT NULL,
  `pengurangan` double NOT NULL,
  `bulan` varchar(4) NOT NULL,
  `tahun` varchar(2) NOT NULL,
  `bruto` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `payroll_user`
--
ALTER TABLE `payroll_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `payroll_user_mapping`
--
ALTER TABLE `payroll_user_mapping`
  ADD UNIQUE KEY `id_user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
