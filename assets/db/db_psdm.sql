-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 25 Jun 2022 pada 04.09
-- Versi server: 5.7.33
-- Versi PHP: 7.4.19

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
-- Struktur dari tabel `payroll_golongan`
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
-- Dumping data untuk tabel `payroll_golongan`
--

INSERT INTO `payroll_golongan` (`g_id`, `g_nama`, `g_pendidikan`, `g_keterangan`, `g_created_at`, `g_updated_at`) VALUES
(1, 'A1', 'SMA', 'Pelaksana kebersihan ruangan dan taman, Pelaksana cucian, Pelaksana distribusi makan karyawan, Pembantu juru masak', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(2, 'A2', 'SMA', 'Petugas ekspedisi, Petugas kamar jahit, Pelaksana pemeliharaan sarana, Petugas dapur ruangan', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(3, 'A3', 'SMA', 'Petugas kendaraan, Keamanan, Petugas TPP/RM, Petugas administrasi, Juru masak, Pembantu orang sakit (POS), Juru racik / pembantu AA', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(4, 'A4', 'SMA', 'Perkarya, Kasir pembantu, Petugas informasi, Petugas pratama rontgen', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(5, 'B1', 'SMA Plus SMK Kesehatan', 'Perawat SPK, Perawat gigi, Ass Apoteker, Ass Analis, P2B', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(6, 'B2', 'Akademi', 'Ahli Madya', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(7, 'B3', 'D4', 'Perawat mahir bedah, Perawat mahir bius', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(8, 'B4', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(9, 'C1', 'Sarjana', 'Sarjana keperawatan, Sarjana gizi, Sarjana hukum, Sarjana ekonomi, Sarjana psikolog, Sarjana teknik, Sarjana lain', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(10, 'C2', 'Dokter', 'Dokter umum, Dokter gigi, S2 manajemen, Apoteker', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(11, 'C3', 'Spesialis', 'Dokter spesialis', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(12, 'C4', 'Subspesialis', 'Dokter subspesialis', '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(13, 'D1', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(14, 'D2', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(15, 'D3', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(16, 'D4', NULL, NULL, '2022-06-24 08:41:17', '2022-06-24 08:41:17'),
(17, 'D5', NULL, NULL, '2022-06-25 04:03:07', '2022-06-25 04:03:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_golongan_detail`
--

CREATE TABLE `payroll_golongan_detail` (
  `gd_id` int(11) NOT NULL,
  `gd_golongan_id` int(11) NOT NULL,
  `gd_tahun` year(4) NOT NULL,
  `gd_tahun_kerja` int(11) NOT NULL,
  `gd_nominal` double NOT NULL,
  `gd_created_at` datetime DEFAULT NULL,
  `gd_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payroll_golongan_detail`
--

INSERT INTO `payroll_golongan_detail` (`gd_id`, `gd_golongan_id`, `gd_tahun`, `gd_tahun_kerja`, `gd_nominal`, `gd_created_at`, `gd_updated_at`) VALUES
(1, 1, 2022, 0, 2584668, NULL, NULL),
(2, 1, 2022, 1, 2584668, NULL, NULL),
(3, 1, 2022, 2, 2668609, NULL, NULL),
(4, 1, 2022, 3, 2668609, NULL, NULL),
(5, 1, 2022, 4, 2755464, NULL, NULL),
(6, 1, 2022, 5, 2755464, NULL, NULL),
(7, 1, 2022, 6, 2846186, NULL, NULL),
(8, 1, 2022, 7, 2846186, NULL, NULL),
(9, 1, 2022, 8, 2940141, NULL, NULL),
(10, 1, 2022, 9, 2940141, NULL, NULL),
(11, 1, 2022, 10, 3037647, NULL, NULL),
(12, 1, 2022, 11, 3037647, NULL, NULL),
(13, 1, 2022, 12, 3138225, NULL, NULL),
(14, 1, 2022, 13, 3138225, NULL, NULL),
(15, 1, 2022, 14, 3244273, NULL, NULL),
(16, 1, 2022, 15, 3244273, NULL, NULL),
(17, 1, 2022, 16, 3353720, NULL, NULL),
(18, 1, 2022, 17, 3353720, NULL, NULL),
(19, 1, 2022, 18, 3466722, NULL, NULL),
(20, 1, 2022, 19, 3466722, NULL, NULL),
(21, 1, 2022, 20, 3584885, NULL, NULL),
(22, 1, 2022, 21, 3584885, NULL, NULL),
(23, 1, 2022, 22, 3707574, NULL, NULL),
(24, 1, 2022, 23, 3707574, NULL, NULL),
(25, 1, 2022, 24, 3834781, NULL, NULL),
(26, 1, 2022, 25, 3834781, NULL, NULL),
(27, 1, 2022, 26, 3968128, NULL, NULL),
(28, 1, 2022, 27, 3968128, NULL, NULL),
(29, 1, 2022, 28, 3968128, NULL, NULL),
(30, 2, 2022, 0, 2697350, NULL, NULL),
(31, 2, 2022, 1, 2697350, NULL, NULL),
(32, 2, 2022, 2, 2739483, NULL, NULL),
(33, 2, 2022, 3, 2810024, NULL, NULL),
(34, 2, 2022, 4, 2810024, NULL, NULL),
(35, 2, 2022, 5, 2902689, NULL, NULL),
(36, 2, 2022, 6, 2902689, NULL, NULL),
(37, 2, 2022, 7, 2998255, NULL, NULL),
(38, 2, 2022, 8, 2998255, NULL, NULL),
(39, 2, 2022, 9, 3097364, NULL, NULL),
(40, 2, 2022, 10, 3097364, NULL, NULL),
(41, 2, 2022, 11, 3201007, NULL, NULL),
(42, 2, 2022, 12, 3201007, NULL, NULL),
(43, 2, 2022, 13, 3308204, NULL, NULL),
(44, 2, 2022, 14, 3308204, NULL, NULL),
(45, 2, 2022, 15, 3419263, NULL, NULL),
(46, 2, 2022, 16, 3419263, NULL, NULL),
(47, 2, 2022, 17, 3534843, NULL, NULL),
(48, 2, 2022, 18, 3534843, NULL, NULL),
(49, 2, 2022, 19, 3655593, NULL, NULL),
(50, 2, 2022, 20, 3655593, NULL, NULL),
(51, 2, 2022, 21, 3779897, NULL, NULL),
(52, 2, 2022, 22, 3779897, NULL, NULL),
(53, 2, 2022, 23, 3909682, NULL, NULL),
(54, 2, 2022, 24, 3909682, NULL, NULL),
(55, 2, 2022, 25, 4044644, NULL, NULL),
(56, 2, 2022, 26, 4044644, NULL, NULL),
(57, 2, 2022, 27, 4185736, NULL, NULL),
(58, 2, 2022, 28, 4205106, NULL, NULL),
(59, 3, 2022, 0, 2804539, NULL, NULL),
(60, 3, 2022, 1, 2804539, NULL, NULL),
(61, 3, 2022, 2, 2825516, NULL, NULL),
(62, 3, 2022, 3, 2911728, NULL, NULL),
(63, 3, 2022, 4, 2911728, NULL, NULL),
(64, 3, 2022, 5, 3007611, NULL, NULL),
(65, 3, 2022, 6, 3007611, NULL, NULL),
(66, 3, 2022, 7, 3107379, NULL, NULL),
(67, 3, 2022, 8, 3107379, NULL, NULL),
(68, 3, 2022, 9, 3210046, NULL, NULL),
(69, 3, 2022, 10, 3210046, NULL, NULL),
(70, 3, 2022, 11, 3317239, NULL, NULL),
(71, 3, 2022, 12, 3317239, NULL, NULL),
(72, 3, 2022, 13, 3428950, NULL, NULL),
(73, 3, 2022, 14, 3428950, NULL, NULL),
(74, 3, 2022, 15, 3544210, NULL, NULL),
(75, 3, 2022, 16, 3544210, NULL, NULL),
(76, 3, 2022, 17, 3664312, NULL, NULL),
(77, 3, 2022, 18, 3664312, NULL, NULL),
(78, 3, 2022, 19, 3789260, NULL, NULL),
(79, 3, 2022, 20, 3789260, NULL, NULL),
(80, 3, 2022, 21, 3919053, NULL, NULL),
(81, 3, 2022, 22, 3919053, NULL, NULL),
(82, 3, 2022, 23, 4054008, NULL, NULL),
(83, 3, 2022, 24, 4054008, NULL, NULL),
(84, 3, 2022, 25, 4194127, NULL, NULL),
(85, 3, 2022, 26, 4194127, NULL, NULL),
(86, 3, 2022, 27, 4339733, NULL, NULL),
(87, 3, 2022, 28, 4339733, NULL, NULL),
(88, 4, 2022, 0, 2910760, NULL, NULL),
(89, 4, 2022, 1, 2910760, NULL, NULL),
(90, 4, 2022, 2, 2921415, NULL, NULL),
(91, 4, 2022, 3, 3017298, NULL, NULL),
(92, 4, 2022, 4, 3017298, NULL, NULL),
(93, 4, 2022, 5, 3116738, NULL, NULL),
(94, 4, 2022, 6, 3116738, NULL, NULL),
(95, 4, 2022, 7, 3220057, NULL, NULL),
(96, 4, 2022, 8, 3220057, NULL, NULL),
(97, 4, 2022, 9, 3326926, NULL, NULL),
(98, 4, 2022, 10, 3326926, NULL, NULL),
(99, 4, 2022, 11, 3438637, NULL, NULL),
(100, 4, 2022, 12, 3438637, NULL, NULL),
(101, 4, 2022, 13, 3553897, NULL, NULL),
(102, 4, 2022, 14, 3553897, NULL, NULL),
(103, 4, 2022, 15, 3673676, NULL, NULL),
(104, 4, 2022, 16, 3673676, NULL, NULL),
(105, 4, 2022, 17, 3798947, NULL, NULL),
(106, 4, 2022, 18, 3798947, NULL, NULL),
(107, 4, 2022, 19, 3928416, NULL, NULL),
(108, 4, 2022, 20, 3928416, NULL, NULL),
(109, 4, 2022, 21, 4062719, NULL, NULL),
(110, 4, 2022, 22, 4062719, NULL, NULL),
(111, 4, 2022, 23, 4202843, NULL, NULL),
(112, 4, 2022, 24, 4202843, NULL, NULL),
(113, 4, 2022, 25, 4348456, NULL, NULL),
(114, 4, 2022, 26, 4348456, NULL, NULL),
(115, 4, 2022, 27, 4499874, NULL, NULL),
(116, 4, 2022, 28, 4499874, NULL, NULL),
(117, 5, 2022, 0, 2831269, NULL, NULL),
(118, 5, 2022, 1, 2831269, NULL, NULL),
(119, 5, 2022, 2, 2878364, NULL, NULL),
(120, 5, 2022, 3, 2973941, NULL, NULL),
(121, 5, 2022, 4, 2973941, NULL, NULL),
(122, 5, 2022, 5, 3072834, NULL, NULL),
(123, 5, 2022, 6, 3072834, NULL, NULL),
(124, 5, 2022, 7, 3176175, NULL, NULL),
(125, 5, 2022, 8, 3176175, NULL, NULL),
(126, 5, 2022, 9, 3282828, NULL, NULL),
(127, 5, 2022, 10, 3282828, NULL, NULL),
(128, 5, 2022, 11, 3393917, NULL, NULL),
(129, 5, 2022, 12, 3393917, NULL, NULL),
(130, 5, 2022, 13, 3509711, NULL, NULL),
(131, 5, 2022, 14, 3509711, NULL, NULL),
(132, 5, 2022, 15, 3629386, NULL, NULL),
(133, 5, 2022, 16, 3629386, NULL, NULL),
(134, 5, 2022, 17, 3754328, NULL, NULL),
(135, 5, 2022, 18, 3754328, NULL, NULL),
(136, 5, 2022, 19, 3884532, NULL, NULL),
(137, 5, 2022, 20, 3884532, NULL, NULL),
(138, 5, 2022, 21, 4019168, NULL, NULL),
(139, 5, 2022, 22, 4019168, NULL, NULL),
(140, 5, 2022, 23, 4159626, NULL, NULL),
(141, 5, 2022, 24, 4159626, NULL, NULL),
(142, 5, 2022, 25, 4305342, NULL, NULL),
(143, 5, 2022, 26, 4305342, NULL, NULL),
(144, 5, 2022, 27, 4457433, NULL, NULL),
(145, 5, 2022, 28, 4457433, NULL, NULL),
(146, 5, 2022, 29, 4615617, NULL, NULL),
(147, 5, 2022, 30, 4615617, NULL, NULL),
(148, 5, 2022, 31, 4779614, NULL, NULL),
(149, 5, 2022, 32, 4779614, NULL, NULL),
(150, 5, 2022, 33, 4950545, NULL, NULL),
(151, 5, 2022, 34, 4950545, NULL, NULL),
(152, 5, 2022, 35, 5127717, NULL, NULL),
(153, 5, 2022, 36, 5127717, NULL, NULL),
(154, 5, 2022, 37, 5311359, NULL, NULL),
(155, 5, 2022, 38, 5311359, NULL, NULL),
(156, 6, 2022, 0, 3001284, NULL, NULL),
(157, 6, 2022, 1, 3001284, NULL, NULL),
(158, 6, 2022, 2, 3006975, NULL, NULL),
(159, 6, 2022, 3, 3081978, NULL, NULL),
(160, 6, 2022, 4, 3081978, NULL, NULL),
(161, 6, 2022, 5, 3184757, NULL, NULL),
(162, 6, 2022, 6, 3184757, NULL, NULL),
(163, 6, 2022, 7, 3291410, NULL, NULL),
(164, 6, 2022, 8, 3291410, NULL, NULL),
(165, 6, 2022, 9, 3402500, NULL, NULL),
(166, 6, 2022, 10, 3402500, NULL, NULL),
(167, 6, 2022, 11, 3518297, NULL, NULL),
(168, 6, 2022, 12, 3518297, NULL, NULL),
(169, 6, 2022, 13, 3637976, NULL, NULL),
(170, 6, 2022, 14, 3637976, NULL, NULL),
(171, 6, 2022, 15, 3762917, NULL, NULL),
(172, 6, 2022, 16, 3762917, NULL, NULL),
(173, 6, 2022, 17, 3892563, NULL, NULL),
(174, 6, 2022, 18, 3892563, NULL, NULL),
(175, 6, 2022, 19, 4027203, NULL, NULL),
(176, 6, 2022, 20, 4027203, NULL, NULL),
(177, 6, 2022, 21, 4167103, NULL, NULL),
(178, 6, 2022, 22, 4167103, NULL, NULL),
(179, 6, 2022, 23, 4312826, NULL, NULL),
(180, 6, 2022, 24, 4312826, NULL, NULL),
(181, 6, 2022, 25, 4464638, NULL, NULL),
(182, 6, 2022, 26, 4464638, NULL, NULL),
(183, 6, 2022, 27, 4622267, NULL, NULL),
(184, 6, 2022, 28, 4622267, NULL, NULL),
(185, 6, 2022, 29, 4786265, NULL, NULL),
(186, 6, 2022, 30, 4786265, NULL, NULL),
(187, 6, 2022, 31, 4956916, NULL, NULL),
(188, 6, 2022, 32, 4956916, NULL, NULL),
(189, 6, 2022, 33, 5134769, NULL, NULL),
(190, 6, 2022, 34, 5134769, NULL, NULL),
(191, 6, 2022, 35, 5319133, NULL, NULL),
(192, 6, 2022, 36, 5319133, NULL, NULL),
(193, 6, 2022, 37, 5510247, NULL, NULL),
(194, 6, 2022, 38, 5510247, NULL, NULL),
(195, 7, 2022, 0, 3075052, NULL, NULL),
(196, 7, 2022, 1, 3075052, NULL, NULL),
(197, 7, 2022, 2, 3086965, NULL, NULL),
(198, 7, 2022, 3, 3193622, NULL, NULL),
(199, 7, 2022, 4, 3193622, NULL, NULL),
(200, 7, 2022, 5, 3300830, NULL, NULL),
(201, 7, 2022, 6, 3300830, NULL, NULL),
(202, 7, 2022, 7, 3411919, NULL, NULL),
(203, 7, 2022, 8, 3411919, NULL, NULL),
(204, 7, 2022, 9, 3527162, NULL, NULL),
(205, 7, 2022, 10, 3527162, NULL, NULL),
(206, 7, 2022, 11, 3646841, NULL, NULL),
(207, 7, 2022, 12, 3646841, NULL, NULL),
(208, 7, 2022, 13, 3771782, NULL, NULL),
(209, 7, 2022, 14, 3771782, NULL, NULL),
(210, 7, 2022, 15, 3901157, NULL, NULL),
(211, 7, 2022, 16, 3901157, NULL, NULL),
(212, 7, 2022, 17, 4035510, NULL, NULL),
(213, 7, 2022, 18, 4035510, NULL, NULL),
(214, 7, 2022, 19, 4175410, NULL, NULL),
(215, 7, 2022, 20, 4175410, NULL, NULL),
(216, 7, 2022, 21, 4321412, NULL, NULL),
(217, 7, 2022, 22, 4321412, NULL, NULL),
(218, 7, 2022, 23, 4472670, NULL, NULL),
(219, 7, 2022, 24, 4472670, NULL, NULL),
(220, 7, 2022, 25, 4629741, NULL, NULL),
(221, 7, 2022, 26, 4629741, NULL, NULL),
(222, 7, 2022, 27, 4793470, NULL, NULL),
(223, 7, 2022, 28, 4793470, NULL, NULL),
(224, 7, 2022, 29, 4963563, NULL, NULL),
(225, 7, 2022, 30, 4963563, NULL, NULL),
(226, 7, 2022, 31, 5141141, NULL, NULL),
(227, 7, 2022, 32, 5141141, NULL, NULL),
(228, 7, 2022, 33, 5325638, NULL, NULL),
(229, 7, 2022, 34, 5325638, NULL, NULL),
(230, 7, 2022, 35, 5516884, NULL, NULL),
(231, 7, 2022, 36, 5516884, NULL, NULL),
(232, 7, 2022, 37, 5715129, NULL, NULL),
(233, 7, 2022, 38, 5715129, NULL, NULL),
(234, 8, 2022, 0, 3192796, NULL, NULL),
(235, 8, 2022, 1, 3192796, NULL, NULL),
(236, 8, 2022, 2, 3198606, NULL, NULL),
(237, 8, 2022, 3, 3310529, NULL, NULL),
(238, 8, 2022, 4, 3310529, NULL, NULL),
(239, 8, 2022, 5, 3421060, NULL, NULL),
(240, 8, 2022, 6, 3421060, NULL, NULL),
(241, 8, 2022, 7, 3536861, NULL, NULL),
(242, 8, 2022, 8, 3536861, NULL, NULL),
(243, 8, 2022, 9, 3656536, NULL, NULL),
(244, 8, 2022, 10, 3656536, NULL, NULL),
(245, 8, 2022, 11, 3780927, NULL, NULL),
(246, 8, 2022, 12, 3780927, NULL, NULL),
(247, 8, 2022, 13, 3910294, NULL, NULL),
(248, 8, 2022, 14, 3910294, NULL, NULL),
(249, 8, 2022, 15, 4044662, NULL, NULL),
(250, 8, 2022, 16, 4044662, NULL, NULL),
(251, 8, 2022, 17, 4184278, NULL, NULL),
(252, 8, 2022, 18, 4184278, NULL, NULL),
(253, 8, 2022, 19, 4329723, NULL, NULL),
(254, 8, 2022, 20, 4329723, NULL, NULL),
(255, 8, 2022, 21, 4480980, NULL, NULL),
(256, 8, 2022, 22, 4480980, NULL, NULL),
(257, 8, 2022, 23, 4637501, NULL, NULL),
(258, 8, 2022, 24, 4637501, NULL, NULL),
(259, 8, 2022, 25, 4801498, NULL, NULL),
(260, 8, 2022, 26, 4801498, NULL, NULL),
(261, 8, 2022, 27, 4971323, NULL, NULL),
(262, 8, 2022, 28, 4971323, NULL, NULL),
(263, 8, 2022, 29, 5148343, NULL, NULL),
(264, 8, 2022, 30, 5148343, NULL, NULL),
(265, 8, 2022, 31, 5332292, NULL, NULL),
(266, 8, 2022, 32, 5332292, NULL, NULL),
(267, 8, 2022, 33, 5524001, NULL, NULL),
(268, 8, 2022, 34, 5524001, NULL, NULL),
(269, 8, 2022, 35, 5722732, NULL, NULL),
(270, 8, 2022, 36, 5722732, NULL, NULL),
(271, 8, 2022, 37, 5928742, NULL, NULL),
(272, 8, 2022, 38, 5928742, NULL, NULL),
(273, 9, 2022, 0, 3077688, NULL, NULL),
(274, 9, 2022, 1, 3077688, NULL, NULL),
(275, 9, 2022, 2, 3181110, NULL, NULL),
(276, 9, 2022, 3, 3181110, NULL, NULL),
(277, 9, 2022, 4, 3288593, NULL, NULL),
(278, 9, 2022, 5, 3288593, NULL, NULL),
(279, 9, 2022, 6, 3400636, NULL, NULL),
(280, 9, 2022, 7, 3400636, NULL, NULL),
(281, 9, 2022, 8, 3516740, NULL, NULL),
(282, 9, 2022, 9, 3516740, NULL, NULL),
(283, 9, 2022, 10, 3637397, NULL, NULL),
(284, 9, 2022, 11, 3637397, NULL, NULL),
(285, 9, 2022, 12, 3762601, NULL, NULL),
(286, 9, 2022, 13, 3762601, NULL, NULL),
(287, 9, 2022, 14, 3893068, NULL, NULL),
(288, 9, 2022, 15, 3893068, NULL, NULL),
(289, 9, 2022, 16, 4028569, NULL, NULL),
(290, 9, 2022, 17, 4028569, NULL, NULL),
(291, 9, 2022, 18, 4169815, NULL, NULL),
(292, 9, 2022, 19, 4169815, NULL, NULL),
(293, 9, 2022, 20, 4316084, NULL, NULL),
(294, 9, 2022, 21, 4316084, NULL, NULL),
(295, 9, 2022, 22, 4468821, NULL, NULL),
(296, 9, 2022, 23, 4468821, NULL, NULL),
(297, 9, 2022, 24, 4627539, NULL, NULL),
(298, 9, 2022, 25, 4627539, NULL, NULL),
(299, 9, 2022, 26, 4792723, NULL, NULL),
(300, 9, 2022, 27, 4792723, NULL, NULL),
(301, 9, 2022, 28, 4964609, NULL, NULL),
(302, 9, 2022, 29, 4964609, NULL, NULL),
(303, 9, 2022, 30, 5143916, NULL, NULL),
(304, 9, 2022, 31, 5143916, NULL, NULL),
(305, 9, 2022, 32, 5329927, NULL, NULL),
(306, 9, 2022, 33, 5329927, NULL, NULL),
(307, 9, 2022, 34, 5522797, NULL, NULL),
(308, 9, 2022, 35, 5522797, NULL, NULL),
(309, 9, 2022, 36, 5722778, NULL, NULL),
(310, 9, 2022, 37, 5722778, NULL, NULL),
(311, 9, 2022, 38, 5930132, NULL, NULL),
(312, 10, 2022, 0, 3189967, NULL, NULL),
(313, 10, 2022, 1, 3189967, NULL, NULL),
(314, 10, 2022, 2, 3297457, NULL, NULL),
(315, 10, 2022, 3, 3297457, NULL, NULL),
(316, 10, 2022, 4, 3409011, NULL, NULL),
(317, 10, 2022, 5, 3409011, NULL, NULL),
(318, 10, 2022, 6, 3525121, NULL, NULL),
(319, 10, 2022, 7, 3525121, NULL, NULL),
(320, 10, 2022, 8, 3645297, NULL, NULL),
(321, 10, 2022, 9, 3645297, NULL, NULL),
(322, 10, 2022, 10, 3770740, NULL, NULL),
(323, 10, 2022, 11, 3770740, NULL, NULL),
(324, 10, 2022, 12, 3901210, NULL, NULL),
(325, 10, 2022, 13, 3901210, NULL, NULL),
(326, 10, 2022, 14, 4036233, NULL, NULL),
(327, 10, 2022, 15, 4036233, NULL, NULL),
(328, 10, 2022, 16, 4177236, NULL, NULL),
(329, 10, 2022, 17, 4177236, NULL, NULL),
(330, 10, 2022, 18, 4323744, NULL, NULL),
(331, 10, 2022, 19, 4323744, NULL, NULL),
(332, 10, 2022, 20, 4475999, NULL, NULL),
(333, 10, 2022, 21, 4475999, NULL, NULL),
(334, 10, 2022, 22, 4634484, NULL, NULL),
(335, 10, 2022, 23, 4634484, NULL, NULL),
(336, 10, 2022, 24, 4799425, NULL, NULL),
(337, 10, 2022, 25, 4799425, NULL, NULL),
(338, 10, 2022, 26, 4971072, NULL, NULL),
(339, 10, 2022, 27, 4971072, NULL, NULL),
(340, 10, 2022, 28, 5149427, NULL, NULL),
(341, 10, 2022, 29, 5149427, NULL, NULL),
(342, 10, 2022, 30, 5335432, NULL, NULL),
(343, 10, 2022, 31, 5335432, NULL, NULL),
(344, 10, 2022, 32, 5528628, NULL, NULL),
(345, 10, 2022, 33, 5528628, NULL, NULL),
(346, 10, 2022, 34, 5728952, NULL, NULL),
(347, 10, 2022, 35, 5728952, NULL, NULL),
(348, 10, 2022, 36, 5936666, NULL, NULL),
(349, 10, 2022, 37, 5936666, NULL, NULL),
(350, 10, 2022, 38, 6152043, NULL, NULL),
(351, 11, 2022, 0, 3306311, NULL, NULL),
(352, 11, 2022, 1, 3306311, NULL, NULL),
(353, 11, 2022, 2, 3418108, NULL, NULL),
(354, 11, 2022, 3, 3418108, NULL, NULL),
(355, 11, 2022, 4, 3533736, NULL, NULL),
(356, 11, 2022, 5, 3533736, NULL, NULL),
(357, 11, 2022, 6, 3653911, NULL, NULL),
(358, 11, 2022, 7, 3653911, NULL, NULL),
(359, 11, 2022, 8, 3778876, NULL, NULL),
(360, 11, 2022, 9, 3778876, NULL, NULL),
(361, 11, 2022, 10, 3909106, NULL, NULL),
(362, 11, 2022, 11, 3909106, NULL, NULL),
(363, 11, 2022, 12, 4044368, NULL, NULL),
(364, 11, 2022, 13, 4044368, NULL, NULL),
(365, 11, 2022, 14, 4185375, NULL, NULL),
(366, 11, 2022, 15, 4185375, NULL, NULL),
(367, 11, 2022, 16, 4331410, NULL, NULL),
(368, 11, 2022, 17, 4331410, NULL, NULL),
(369, 11, 2022, 18, 4483423, NULL, NULL),
(370, 11, 2022, 19, 4483423, NULL, NULL),
(371, 11, 2022, 20, 4641662, NULL, NULL),
(372, 11, 2022, 21, 4641662, NULL, NULL),
(373, 11, 2022, 22, 4806131, NULL, NULL),
(374, 11, 2022, 23, 4806131, NULL, NULL),
(375, 11, 2022, 24, 4977538, NULL, NULL),
(376, 11, 2022, 25, 4977538, NULL, NULL),
(377, 11, 2022, 26, 5155407, NULL, NULL),
(378, 11, 2022, 27, 5155407, NULL, NULL),
(379, 11, 2022, 28, 5340940, NULL, NULL),
(380, 11, 2022, 29, 5340940, NULL, NULL),
(381, 11, 2022, 30, 5534133, NULL, NULL),
(382, 11, 2022, 31, 5534133, NULL, NULL),
(383, 11, 2022, 32, 5735225, NULL, NULL),
(384, 11, 2022, 33, 5735225, NULL, NULL),
(385, 11, 2022, 34, 5943758, NULL, NULL),
(386, 11, 2022, 35, 5943758, NULL, NULL),
(387, 11, 2022, 36, 6160005, NULL, NULL),
(388, 11, 2022, 37, 6160005, NULL, NULL),
(389, 11, 2022, 38, 6384252, NULL, NULL),
(390, 12, 2022, 0, 3426729, NULL, NULL),
(391, 12, 2022, 1, 3426729, NULL, NULL),
(392, 12, 2022, 2, 3542593, NULL, NULL),
(393, 12, 2022, 3, 3542593, NULL, NULL),
(394, 12, 2022, 4, 3663015, NULL, NULL),
(395, 12, 2022, 5, 3663015, NULL, NULL),
(396, 12, 2022, 6, 3787976, NULL, NULL),
(397, 12, 2022, 7, 3787976, NULL, NULL),
(398, 12, 2022, 8, 3918213, NULL, NULL),
(399, 12, 2022, 9, 3918213, NULL, NULL),
(400, 12, 2022, 10, 4052989, NULL, NULL),
(401, 12, 2022, 11, 4052989, NULL, NULL),
(402, 12, 2022, 12, 4193756, NULL, NULL),
(403, 12, 2022, 13, 4193756, NULL, NULL),
(404, 12, 2022, 14, 4339549, NULL, NULL),
(405, 12, 2022, 15, 4339549, NULL, NULL),
(406, 12, 2022, 16, 4491322, NULL, NULL),
(407, 12, 2022, 17, 4491322, NULL, NULL),
(408, 12, 2022, 18, 4649086, NULL, NULL),
(409, 12, 2022, 19, 4649086, NULL, NULL),
(410, 12, 2022, 20, 4813554, NULL, NULL),
(411, 12, 2022, 21, 4813554, NULL, NULL),
(412, 12, 2022, 22, 4984479, NULL, NULL),
(413, 12, 2022, 23, 4984479, NULL, NULL),
(414, 12, 2022, 24, 5162109, NULL, NULL),
(415, 12, 2022, 25, 5162109, NULL, NULL),
(416, 12, 2022, 26, 5347403, NULL, NULL),
(417, 12, 2022, 27, 5347403, NULL, NULL),
(418, 12, 2022, 28, 5539881, NULL, NULL),
(419, 12, 2022, 29, 5539881, NULL, NULL),
(420, 12, 2022, 30, 5740251, NULL, NULL),
(421, 12, 2022, 31, 5740251, NULL, NULL),
(422, 12, 2022, 32, 5949004, NULL, NULL),
(423, 12, 2022, 33, 5949004, NULL, NULL),
(424, 12, 2022, 34, 6165480, NULL, NULL),
(425, 12, 2022, 35, 6165480, NULL, NULL),
(426, 12, 2022, 36, 6389967, NULL, NULL),
(427, 12, 2022, 37, 6389967, NULL, NULL),
(428, 12, 2022, 38, 6622761, NULL, NULL),
(429, 13, 2022, 0, 3474635, NULL, NULL),
(430, 13, 2022, 1, 3474635, NULL, NULL),
(431, 13, 2022, 2, 3592169, NULL, NULL),
(432, 13, 2022, 3, 3592169, NULL, NULL),
(433, 13, 2022, 4, 3714323, NULL, NULL),
(434, 13, 2022, 5, 3714323, NULL, NULL),
(435, 13, 2022, 6, 3841083, NULL, NULL),
(436, 13, 2022, 7, 3841083, NULL, NULL),
(437, 13, 2022, 8, 3973195, NULL, NULL),
(438, 13, 2022, 9, 3973195, NULL, NULL),
(439, 13, 2022, 10, 4109913, NULL, NULL),
(440, 13, 2022, 11, 4109913, NULL, NULL),
(441, 13, 2022, 12, 4252706, NULL, NULL),
(442, 13, 2022, 13, 4252706, NULL, NULL),
(443, 13, 2022, 14, 4400598, NULL, NULL),
(444, 13, 2022, 15, 4400598, NULL, NULL),
(445, 13, 2022, 16, 4554554, NULL, NULL),
(446, 13, 2022, 17, 4554554, NULL, NULL),
(447, 13, 2022, 18, 4714589, NULL, NULL),
(448, 13, 2022, 19, 4714589, NULL, NULL),
(449, 13, 2022, 20, 4881426, NULL, NULL),
(450, 13, 2022, 21, 4881426, NULL, NULL),
(451, 13, 2022, 22, 5054810, NULL, NULL),
(452, 13, 2022, 23, 5054810, NULL, NULL),
(453, 13, 2022, 24, 5234997, NULL, NULL),
(454, 13, 2022, 25, 5234997, NULL, NULL),
(455, 13, 2022, 26, 5422959, NULL, NULL),
(456, 13, 2022, 27, 5422959, NULL, NULL),
(457, 13, 2022, 28, 5618206, NULL, NULL),
(458, 13, 2022, 29, 5618206, NULL, NULL),
(459, 13, 2022, 30, 5821461, NULL, NULL),
(460, 13, 2022, 31, 5821461, NULL, NULL),
(461, 13, 2022, 32, 6033219, NULL, NULL),
(462, 13, 2022, 33, 6033219, NULL, NULL),
(463, 13, 2022, 34, 6252812, NULL, NULL),
(464, 13, 2022, 35, 6252812, NULL, NULL),
(465, 13, 2022, 36, 6480530, NULL, NULL),
(466, 13, 2022, 37, 6480530, NULL, NULL),
(467, 13, 2022, 38, 6716675, NULL, NULL),
(468, 14, 2022, 0, 3599547, NULL, NULL),
(469, 14, 2022, 1, 3599547, NULL, NULL),
(470, 14, 2022, 2, 3721430, NULL, NULL),
(471, 14, 2022, 3, 3721430, NULL, NULL),
(472, 14, 2022, 4, 3848104, NULL, NULL),
(473, 14, 2022, 5, 3848104, NULL, NULL),
(474, 14, 2022, 6, 3979554, NULL, NULL),
(475, 14, 2022, 7, 3979554, NULL, NULL),
(476, 14, 2022, 8, 4116554, NULL, NULL),
(477, 14, 2022, 9, 4116554, NULL, NULL),
(478, 14, 2022, 10, 4258330, NULL, NULL),
(479, 14, 2022, 11, 4258330, NULL, NULL),
(480, 14, 2022, 12, 4406407, NULL, NULL),
(481, 14, 2022, 13, 4406407, NULL, NULL),
(482, 14, 2022, 14, 4559770, NULL, NULL),
(483, 14, 2022, 15, 4559770, NULL, NULL),
(484, 14, 2022, 16, 4719423, NULL, NULL),
(485, 14, 2022, 17, 4719423, NULL, NULL),
(486, 14, 2022, 18, 4885379, NULL, NULL),
(487, 14, 2022, 19, 4885379, NULL, NULL),
(488, 14, 2022, 20, 5058389, NULL, NULL),
(489, 14, 2022, 21, 5058389, NULL, NULL),
(490, 14, 2022, 22, 5238189, NULL, NULL),
(491, 14, 2022, 23, 5238189, NULL, NULL),
(492, 14, 2022, 24, 5425042, NULL, NULL),
(493, 14, 2022, 25, 5425042, NULL, NULL),
(494, 14, 2022, 26, 5619959, NULL, NULL),
(495, 14, 2022, 27, 5619959, NULL, NULL),
(496, 14, 2022, 28, 5822430, NULL, NULL),
(497, 14, 2022, 29, 5822430, NULL, NULL),
(498, 14, 2022, 30, 6033205, NULL, NULL),
(499, 14, 2022, 31, 6033205, NULL, NULL),
(500, 14, 2022, 32, 6252798, NULL, NULL),
(501, 14, 2022, 33, 6252798, NULL, NULL),
(502, 14, 2022, 34, 6480516, NULL, NULL),
(503, 14, 2022, 35, 6480516, NULL, NULL),
(504, 14, 2022, 36, 6716660, NULL, NULL),
(505, 14, 2022, 37, 6716660, NULL, NULL),
(506, 14, 2022, 38, 6961542, NULL, NULL),
(507, 15, 2022, 0, 3729081, NULL, NULL),
(508, 15, 2022, 1, 3729081, NULL, NULL),
(509, 15, 2022, 2, 3855473, NULL, NULL),
(510, 15, 2022, 3, 3855473, NULL, NULL),
(511, 15, 2022, 4, 3986834, NULL, NULL),
(512, 15, 2022, 5, 3986834, NULL, NULL),
(513, 15, 2022, 6, 4123148, NULL, NULL),
(514, 15, 2022, 7, 4123148, NULL, NULL),
(515, 15, 2022, 8, 4265217, NULL, NULL),
(516, 15, 2022, 9, 4265217, NULL, NULL),
(517, 15, 2022, 10, 4412239, NULL, NULL),
(518, 15, 2022, 11, 4412239, NULL, NULL),
(519, 15, 2022, 12, 4565794, NULL, NULL),
(520, 15, 2022, 13, 4565794, NULL, NULL),
(521, 15, 2022, 14, 4724832, NULL, NULL),
(522, 15, 2022, 15, 4724832, NULL, NULL),
(523, 15, 2022, 16, 4890392, NULL, NULL),
(524, 15, 2022, 17, 4890392, NULL, NULL),
(525, 15, 2022, 18, 5062489, NULL, NULL),
(526, 15, 2022, 19, 5062489, NULL, NULL),
(527, 15, 2022, 20, 5241900, NULL, NULL),
(528, 15, 2022, 21, 5241900, NULL, NULL),
(529, 15, 2022, 22, 5428352, NULL, NULL),
(530, 15, 2022, 23, 5428352, NULL, NULL),
(531, 15, 2022, 24, 5622119, NULL, NULL),
(532, 15, 2022, 25, 5622119, NULL, NULL),
(533, 15, 2022, 26, 5824248, NULL, NULL),
(534, 15, 2022, 27, 5824248, NULL, NULL),
(535, 15, 2022, 28, 6034211, NULL, NULL),
(536, 15, 2022, 29, 6034211, NULL, NULL),
(537, 15, 2022, 30, 6252784, NULL, NULL),
(538, 15, 2022, 31, 6252784, NULL, NULL),
(539, 15, 2022, 32, 6480502, NULL, NULL),
(540, 15, 2022, 33, 6480502, NULL, NULL),
(541, 15, 2022, 34, 6716645, NULL, NULL),
(542, 15, 2022, 35, 6716645, NULL, NULL),
(543, 15, 2022, 36, 6961527, NULL, NULL),
(544, 15, 2022, 37, 6961527, NULL, NULL),
(545, 15, 2022, 38, 7215469, NULL, NULL),
(546, 16, 2022, 0, 3863407, NULL, NULL),
(547, 16, 2022, 1, 3863407, NULL, NULL),
(548, 16, 2022, 2, 3994476, NULL, NULL),
(549, 16, 2022, 3, 3994476, NULL, NULL),
(550, 16, 2022, 4, 4130697, NULL, NULL),
(551, 16, 2022, 5, 4130697, NULL, NULL),
(552, 16, 2022, 6, 4272054, NULL, NULL),
(553, 16, 2022, 7, 4272054, NULL, NULL),
(554, 16, 2022, 8, 4419380, NULL, NULL),
(555, 16, 2022, 9, 4419380, NULL, NULL),
(556, 16, 2022, 10, 4571842, NULL, NULL),
(557, 16, 2022, 11, 4571842, NULL, NULL),
(558, 16, 2022, 12, 4731079, NULL, NULL),
(559, 16, 2022, 13, 4731079, NULL, NULL),
(560, 16, 2022, 14, 4896001, NULL, NULL),
(561, 16, 2022, 15, 4896001, NULL, NULL),
(562, 16, 2022, 16, 5067687, NULL, NULL),
(563, 16, 2022, 17, 5067687, NULL, NULL),
(564, 16, 2022, 18, 5246151, NULL, NULL),
(565, 16, 2022, 19, 5246151, NULL, NULL),
(566, 16, 2022, 20, 5432200, NULL, NULL),
(567, 16, 2022, 21, 5432200, NULL, NULL),
(568, 16, 2022, 22, 5625551, NULL, NULL),
(569, 16, 2022, 23, 5625551, NULL, NULL),
(570, 16, 2022, 24, 5826487, NULL, NULL),
(571, 16, 2022, 25, 5826487, NULL, NULL),
(572, 16, 2022, 26, 6036095, NULL, NULL),
(573, 16, 2022, 27, 6036095, NULL, NULL),
(574, 16, 2022, 28, 6253827, NULL, NULL),
(575, 16, 2022, 29, 6253827, NULL, NULL),
(576, 16, 2022, 30, 6480488, NULL, NULL),
(577, 16, 2022, 31, 6480488, NULL, NULL),
(578, 16, 2022, 32, 6716631, NULL, NULL),
(579, 16, 2022, 33, 6716631, NULL, NULL),
(580, 16, 2022, 34, 6961512, NULL, NULL),
(581, 16, 2022, 35, 6961512, NULL, NULL),
(582, 16, 2022, 36, 7215454, NULL, NULL),
(583, 16, 2022, 37, 7215454, NULL, NULL),
(584, 16, 2022, 38, 7478792, NULL, NULL),
(585, 17, 2022, 0, 4002703, NULL, NULL),
(586, 17, 2022, 1, 4002703, NULL, NULL),
(587, 17, 2022, 2, 4138621, NULL, NULL),
(588, 17, 2022, 3, 4138621, NULL, NULL),
(589, 17, 2022, 4, 4279883, NULL, NULL),
(590, 17, 2022, 5, 4279883, NULL, NULL),
(591, 17, 2022, 6, 4426471, NULL, NULL),
(592, 17, 2022, 7, 4426471, NULL, NULL),
(593, 17, 2022, 8, 4579247, NULL, NULL),
(594, 17, 2022, 9, 4579247, NULL, NULL),
(595, 17, 2022, 10, 4737350, NULL, NULL),
(596, 17, 2022, 11, 4737350, NULL, NULL),
(597, 17, 2022, 12, 4902479, NULL, NULL),
(598, 17, 2022, 13, 4902479, NULL, NULL),
(599, 17, 2022, 14, 5073503, NULL, NULL),
(600, 17, 2022, 15, 5073503, NULL, NULL),
(601, 17, 2022, 16, 5251542, NULL, NULL),
(602, 17, 2022, 17, 5251542, NULL, NULL),
(603, 17, 2022, 18, 5436609, NULL, NULL),
(604, 17, 2022, 19, 5436609, NULL, NULL),
(605, 17, 2022, 20, 5629542, NULL, NULL),
(606, 17, 2022, 21, 5629542, NULL, NULL),
(607, 17, 2022, 22, 5830047, NULL, NULL),
(608, 17, 2022, 23, 5830047, NULL, NULL),
(609, 17, 2022, 24, 6038417, NULL, NULL),
(610, 17, 2022, 25, 6038417, NULL, NULL),
(611, 17, 2022, 26, 6255781, NULL, NULL),
(612, 17, 2022, 27, 6255781, NULL, NULL),
(613, 17, 2022, 28, 6481568, NULL, NULL),
(614, 17, 2022, 29, 6481568, NULL, NULL),
(615, 17, 2022, 30, 6716616, NULL, NULL),
(616, 17, 2022, 31, 6716616, NULL, NULL),
(617, 17, 2022, 32, 6961496, NULL, NULL),
(618, 17, 2022, 33, 6961496, NULL, NULL),
(619, 17, 2022, 34, 7215438, NULL, NULL),
(620, 17, 2022, 35, 7215438, NULL, NULL),
(621, 17, 2022, 36, 7478776, NULL, NULL),
(622, 17, 2022, 37, 7478776, NULL, NULL),
(623, 17, 2022, 38, 7751858, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_ptkp`
--

CREATE TABLE `payroll_ptkp` (
  `kp_id` int(11) NOT NULL,
  `kp_kode` varchar(50) NOT NULL,
  `kp_nama` varchar(200) NOT NULL,
  `kp_min_gaji` double NOT NULL,
  `kp_golongan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `payroll_ptkp`
--

INSERT INTO `payroll_ptkp` (`kp_id`, `kp_kode`, `kp_nama`, `kp_min_gaji`, `kp_golongan`) VALUES
(1, 'TK0', 'Tanpa tanggungan', 54000000, 'Tidak Kawin (TK)'),
(2, 'TK1', '1 tanggungan', 58500000, 'Tidak Kawin (TK)'),
(3, 'TK2', '2 tanggungan', 63000000, 'Tidak Kawin (TK)'),
(4, 'TK3', '3 tanggungan', 67500000, 'Tidak Kawin (TK)'),
(5, 'K0', 'Tanpa tanggungan', 58500000, 'Kawin (K)'),
(6, 'K1', '1 tanggungan', 63000000, 'Kawin (K)'),
(7, 'K2', '2 tanggungan', 67500000, 'Kawin (K)'),
(8, 'K3', '3 tanggungan', 72000000, 'Kawin (K)'),
(9, 'K/I/0', '-', 112500000, 'Kawin dengan penghasilan istri digabung (K/I)'),
(10, 'K/I/1', '1 tanggungan', 117000000, 'Kawin dengan penghasilan istri digabung (K/I)'),
(11, 'K/I/2', '2 tanggungan', 121500000, 'Kawin dengan penghasilan istri digabung (K/I)'),
(12, 'K/I/3', '3 tanggungan', 126000000, 'Kawin dengan penghasilan istri digabung (K/I)');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_setting`
--

CREATE TABLE `payroll_setting` (
  `set_id` int(11) NOT NULL,
  `set_kode` varchar(100) NOT NULL,
  `set_value` text NOT NULL,
  `set_keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `payroll_setting`
--

INSERT INTO `payroll_setting` (`set_id`, `set_kode`, `set_value`, `set_keterangan`) VALUES
(1, 'is_transport', '250000', 'Nominal transport'),
(2, 'persen_rs_bpjs_jkk', '4.24', 'BPJS (jkk jht jkm)'),
(3, 'persen_rs_bpjs_pensiun', '2', 'BPJS (Pensiun)	\r\n'),
(4, 'persen_rs_bpjs_kesehatan', '4', 'BPJS Kesehatan'),
(5, 'persen_bpjs_jkk', '2', 'Potongan BPJS (jkk jht jkm)'),
(6, 'persen_bpjs_pensiun', '1', 'Potongan BPJS (Pensiun)	'),
(7, 'persen_bpjs_kesehatan', '1', 'Potongan BPJS Kesehatan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_tunjangan_fungsi`
--

CREATE TABLE `payroll_tunjangan_fungsi` (
  `tf_id` int(11) NOT NULL,
  `tf_nama` varchar(255) NOT NULL,
  `tf_keterangan` varchar(255) DEFAULT NULL,
  `tf_baru` double DEFAULT NULL,
  `tf_lama` double DEFAULT NULL,
  `tf_urut` int(11) DEFAULT NULL,
  `tf_status` int(1) NOT NULL DEFAULT '1' COMMENT '0 = tdk aktif, 1 = aktif',
  `tf_created_at` datetime NOT NULL,
  `tf_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payroll_tunjangan_fungsi`
--

INSERT INTO `payroll_tunjangan_fungsi` (`tf_id`, `tf_nama`, `tf_keterangan`, `tf_baru`, `tf_lama`, `tf_urut`, `tf_status`, `tf_created_at`, `tf_updated_at`) VALUES
(1, 'I', 'Pelaksana kebersihan ruangan dan taman, Pelaksana cucian, Pelaksana distribusi makan karyawan, Pembantu juru masak', 101800, 145500, 1, 1, '2022-06-24 12:51:45', '2022-06-25 08:27:20'),
(2, 'II', 'Petugas ekspedisi, Petugas kamar jahit, Pelaksana pemeliharaan sarana, Petugas dapur ruangan, Juru masak, Pembantu orang sakit (POS), Juru racik / pembantu AA', 116400, 167300, 2, 1, '2022-06-24 12:53:41', '2022-06-25 08:27:23'),
(3, 'III', 'Petugas TPP / RM (setara SMA), Pekarya, Petugas informasi', 138200, 181800, 3, 1, '2022-06-24 12:55:14', '2022-06-25 08:27:27'),
(4, 'IV', 'Petugas kendaraan, Teknisi (STM-D1), Keamanan, Petugas administrasi', 159400, 212500, 4, 1, '2022-06-24 12:56:03', '2022-06-25 08:27:34'),
(5, 'V', 'Perawat gigi, Perawat SPK', 167000, 222600, 5, 1, '2022-06-24 12:56:30', '2022-06-25 08:27:39'),
(6, 'VI', 'Asisten apoteker, Asisten analis, P2B', 180900, 243500, 6, 1, '2022-06-24 13:54:45', '2022-06-25 08:27:46'),
(7, 'VII', 'Ahli madya (D-3) Sekretaris / Akuntasi / Manajemen Informasi & Teknologi (Mitek)', 208700, 278300, 7, 1, '2022-06-24 13:56:34', '2022-06-25 08:27:54'),
(8, 'IX', 'D-4 Keperawatan (Perioperatif/Anestesi/dsb)/Rehabilitasi medis/ Radiologi/ Gizi/ Rekam medis/ Laborat', 253000, 316300, 9, 1, '2022-06-24 13:57:56', NULL),
(9, 'X', 'Sarjana hukum, Sarjana ekonomi, Sarjana komputer, Sarjana teknik, Sarjana pendidikan, Sarjana psikolog non profesi, Sarjana lain (S1 lain)', 306000, 408000, 10, 1, '2022-06-24 13:59:24', NULL),
(10, 'XI', 'Sarjana keperawatan, Sarjana psikologi, Sarjana gizi, Apoteker, Dokter umum, Dokter gigi', 329000, 431000, 11, 1, '2022-06-24 14:00:02', NULL),
(11, 'XIII', 'S2 Manajemen', 340500, 442500, 13, 1, '2022-06-24 14:00:26', NULL),
(12, 'XIV', 'Dokter spesialis', 352000, 454000, 14, 1, '2022-06-24 14:00:58', NULL),
(13, 'XV', 'Dokter sup spesialis', 363500, 465500, 15, 1, '2022-06-24 14:01:20', NULL),
(14, 'XVI', 'D3 Keperawatan / D3 Radiografer, D4 Radiografer', 302500, 385000, 16, 1, '2022-06-24 14:02:16', NULL),
(15, 'VIII', 'Ahli Madya (D-3)  keperawatan / Gizi / Analis / Rehabilitasi Medis / Farmasi / Rekam Medis', 220200, 289800, 8, 1, '2022-06-25 08:26:03', NULL),
(16, 'XII', 'Apoteker / Dokter Umum / Dokter Gigi', 329000, 431000, 12, 1, '2022-06-25 08:30:48', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_tunjangan_jabatan`
--

CREATE TABLE `payroll_tunjangan_jabatan` (
  `tj_id` int(11) NOT NULL,
  `tj_nama` varchar(255) NOT NULL,
  `tj_keterangan` varchar(255) DEFAULT NULL,
  `tj_baru` double NOT NULL,
  `tj_lama` double NOT NULL,
  `tj_status` int(1) NOT NULL DEFAULT '1' COMMENT '0 = tdk aktif, 1 = aktif',
  `tj_created_at` datetime NOT NULL,
  `tj_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payroll_tunjangan_jabatan`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_user`
--

CREATE TABLE `payroll_user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_password` varchar(32) DEFAULT NULL,
  `user_fullname` varchar(100) NOT NULL,
  `user_priv` varchar(20) NOT NULL DEFAULT 'all' COMMENT 'ALL, UP, IKO, IGD, IRJ, IRI',
  `user_level` int(11) NOT NULL DEFAULT '5' COMMENT '1 : Administrator\r\n2 : Verifikator\r\n3 : Direksi\r\n4 : Ka. Unit/ Ruangan\r\n5 : Penginput',
  `user_org_id` int(11) DEFAULT NULL,
  `user_uk_id` varchar(10) DEFAULT NULL,
  `user_record_status` varchar(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `payroll_user`
--

INSERT INTO `payroll_user` (`user_id`, `user_name`, `user_password`, `user_fullname`, `user_priv`, `user_level`, `user_org_id`, `user_uk_id`, `user_record_status`) VALUES
(1, 'admin', 'NIRMALApanti50', 'Administrator', 'all', 1, NULL, NULL, 'A');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_user_mapping`
--

CREATE TABLE `payroll_user_mapping` (
  `id_user` int(11) NOT NULL DEFAULT '0',
  `id_gol_gaji` int(11) NOT NULL,
  `id_status` int(11) DEFAULT NULL,
  `id_tunj_jabatan` int(11) DEFAULT NULL,
  `id_tunj_fungsi` int(11) DEFAULT NULL,
  `is_transport` int(1) NOT NULL,
  `is_bpjs` int(1) NOT NULL,
  `penambahan` double NOT NULL,
  `pengurangan` double NOT NULL,
  `bulan` varchar(4) NOT NULL,
  `tahun` varchar(2) NOT NULL,
  `bruto` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `payroll_user_mapping`
--

INSERT INTO `payroll_user_mapping` (`id_user`, `id_gol_gaji`, `id_status`, `id_tunj_jabatan`, `id_tunj_fungsi`, `is_transport`, `is_bpjs`, `penambahan`, `pengurangan`, `bulan`, `tahun`, `bruto`) VALUES
(336, 11, 1, 3, 16, 1, 1, 0, 0, '', '', 0),
(337, 10, 1, NULL, 15, 1, 1, 0, 0, '', '', 0),
(338, 8, 1, NULL, 15, 1, 1, 0, 0, '', '', 0),
(339, 10, 1, NULL, 10, 1, 1, 0, 0, '', '', 0),
(340, 8, 1, NULL, 15, 1, 1, 0, 0, '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `payroll_golongan`
--
ALTER TABLE `payroll_golongan`
  ADD PRIMARY KEY (`g_id`);

--
-- Indeks untuk tabel `payroll_golongan_detail`
--
ALTER TABLE `payroll_golongan_detail`
  ADD PRIMARY KEY (`gd_id`);

--
-- Indeks untuk tabel `payroll_ptkp`
--
ALTER TABLE `payroll_ptkp`
  ADD PRIMARY KEY (`kp_id`);

--
-- Indeks untuk tabel `payroll_setting`
--
ALTER TABLE `payroll_setting`
  ADD PRIMARY KEY (`set_id`);

--
-- Indeks untuk tabel `payroll_tunjangan_fungsi`
--
ALTER TABLE `payroll_tunjangan_fungsi`
  ADD PRIMARY KEY (`tf_id`);

--
-- Indeks untuk tabel `payroll_tunjangan_jabatan`
--
ALTER TABLE `payroll_tunjangan_jabatan`
  ADD PRIMARY KEY (`tj_id`);

--
-- Indeks untuk tabel `payroll_user`
--
ALTER TABLE `payroll_user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indeks untuk tabel `payroll_user_mapping`
--
ALTER TABLE `payroll_user_mapping`
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `payroll_golongan`
--
ALTER TABLE `payroll_golongan`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
