-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 06, 2025 at 03:39 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kurban`
--

-- --------------------------------------------------------

--
-- Table structure for table `hewan_qurban`
--

CREATE TABLE `hewan_qurban` (
  `id` int NOT NULL,
  `jenis` enum('kambing','sapi') NOT NULL,
  `jumlah` int DEFAULT '1',
  `harga_total` bigint NOT NULL,
  `biaya_admin` bigint NOT NULL,
  `tanggal` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keuangan`
--

CREATE TABLE `keuangan` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('pemasukan','pengeluaran') NOT NULL,
  `kategori` enum('iuran_qurban','admin_qurban','pembelian_hewan','perlengkapan') NOT NULL,
  `jumlah` bigint NOT NULL,
  `keterangan` text,
  `nik` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembagian_daging`
--

CREATE TABLE `pembagian_daging` (
  `id` int NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `jumlah_kg` decimal(5,2) NOT NULL,
  `status` enum('belum_ambil','sudah_ambil') DEFAULT 'belum_ambil',
  `qrcode_path` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qurban_peserta`
--

CREATE TABLE `qurban_peserta` (
  `id` int NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `hewan_id` int DEFAULT NULL,
  `jumlah_iuran` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `nik` varchar(16) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text,
  `telepon` varchar(20) DEFAULT NULL,
  `is_panitia` tinyint(1) DEFAULT '0',
  `is_berqurban` tinyint(1) DEFAULT '0',
  `role` enum('warga','admin') DEFAULT 'warga',
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`nik`, `nama`, `alamat`, `telepon`, `is_panitia`, `is_berqurban`, `role`, `password`) VALUES
('1234567812345678', 'Admin Qurban', 'RT 001 Desa AAAA', '-', 0, 0, 'admin', '$2b$12$FcW.ioeujBeiV7yrCsgekub1OZBsvEKMa8batrH.LsvgxjMMpR83K'),
('3201234567890001', 'Ahmad Rizki Pratama', 'RT 001 Desa AAAA', '81234567890', 1, 0, 'warga', '$2y$10$1054PzeRZy51XBPW8sWKvOm1Pce2defiOb5qqNNUDZr7sdH4G.C0i'),
('3201234567890002', 'Siti Nurhaliza', 'RT 001 Desa AAAA', '81234567891', 0, 0, 'warga', '$2y$10$CWPw0OkaRswtNZE3lcMEYejObjCMmINQFWRJNXhKEHVIrNnrHiqPC'),
('3201234567890003', 'Budi Santoso', 'RT 001 Desa AAAA', '81234567892', 0, 0, 'warga', '$2y$10$XOyz4J879kyd/ozcxIzp6u3cpVnX7zZ2ITgPMst7XGdV8L/hzxEau'),
('3201234567890004', 'Dewi Sartika', 'RT 001 Desa AAAA', '81234567893', 1, 1, 'warga', '$2y$10$RdfEmoQFqXkzJvMMGHvI4eyHRhkbPrJ8oYwGYCwV/EybZBuLbim3q'),
('3201234567890005', 'Muhammad Hafiz', 'RT 001 Desa AAAA', '81234567894', 0, 0, 'warga', '$2y$10$PZNBO41gk6zqNT9PWUp/zeaHqLP8Q8YALfE6yxyvELCDw2T/6Mt42'),
('3201234567890006', 'Indah Permatasari', 'RT 001 Desa AAAA', '81234567895', 0, 0, 'warga', '$2y$10$3zBD7AGOpH58D4WN.n85OedKCS0yblF3lzA/aFupoxpqw5YaGX0GO'),
('3201234567890007', 'Rudi Hermawan', 'RT 001 Desa AAAA', '81234567896', 0, 1, 'warga', '$2y$10$xFUzsKzHxD8JeRUD8keAoOW4Uwczww9/1u21ZsTZeXuyy/0KghBlG'),
('3201234567890008', 'Fitri Handayani', 'RT 001 Desa AAAA', '81234567897', 1, 0, 'warga', '$2y$10$zU.LMfBagwgOvPHDtnWTmuhZ8DzoVv6JF3utgzV6cAwUEgtXckVvy'),
('3201234567890009', 'Agus Wijaya', 'RT 001 Desa AAAA', '81234567898', 0, 0, 'warga', '$2y$10$J4Gi1c2Wt/0h6.uq8D9O7u1wTA3/eEIvJlx720XAnoBXRHkePrE7O'),
('3201234567890010', 'Maya Sari', 'RT 001 Desa AAAA', '81234567899', 1, 0, 'warga', '$2y$10$yz2uaGmwEMc3VhiW9y8fAOvZMG5d7d9Dm/v4D7PD.AR38DgSbpuo6'),
('3201234567890011', 'Hendra Gunawan', 'RT 001 Desa AAAA', '81234567800', 0, 1, 'warga', '$2y$10$vX5RfbxUKytiY4kEMwa3bOMjdgkd.NtA5QM1Q5sC4o5XPvCFmy3Um'),
('3201234567890012', 'Rina Wulandari', 'RT 001 Desa AAAA', '81234567801', 0, 0, 'warga', '$2y$10$nM0X9C4vbDoSiiQBNl57ROU49Jsf3jeie2aaRJjA8nTVFUgevczsy'),
('3201234567890013', 'Dodi Setiawan', 'RT 001 Desa AAAA', '81234567802', 0, 0, 'warga', '$2y$10$RVhCa/MC8WKyJezWTn6Zsup1jETaCKVhRx1PdGrsTM6xCjrsMVSki'),
('3201234567890014', 'Lisa Marlina', 'RT 001 Desa AAAA', '81234567803', 0, 0, 'warga', '$2y$10$Df/MFV5zo/BP7hM4d.UrsucWuocuD2M5TETDD3il3GAJ986o7wceS'),
('3201234567890015', 'Eko Prasetyo', 'RT 001 Desa AAAA', '81234567804', 1, 0, 'warga', '$2y$10$9A7ePseKhkokKY.6iKDR9uNR35A9fx2kV8sL5V92OaOLn9EzzbXpi'),
('3201234567890016', 'Nani Suryani', 'RT 001 Desa AAAA', '81234567805', 0, 0, 'warga', '$2y$10$5HQJdQbAgD5wFkzRJCFkIuPK1uaZqdkRhTJO4ETxNDnd1IF59ZHbm'),
('3201234567890017', 'Bambang Sutrisno', 'RT 001 Desa AAAA', '81234567806', 0, 0, 'warga', '$2y$10$j9QUFfnUxNiU2XDxoVA1JOWJ0jaxMR/Pu0L9TroSN3yYEQ688QeyK'),
('3201234567890018', 'Ratna Dewi', 'RT 001 Desa AAAA', '81234567807', 0, 1, 'warga', '$2y$10$3Zap6ktikgAQnkXlBBrA.uV.DQTYUGKHsuftpp9bnqfeTQal5RK/a'),
('3201234567890019', 'Yudi Pratama', 'RT 001 Desa AAAA', '81234567808', 1, 0, 'warga', '$2y$10$Po9FTnCuWQHGRsZLoyCPwu8/1i9Oz6sb2V9IvtnGUcIFtkWjzxFbO'),
('3201234567890020', 'Sari Indah', 'RT 001 Desa AAAA', '81234567809', 0, 0, 'warga', '$2y$10$jJki/s.GkM.plvcJAhLxd.vKzp49Zmy77Tb8M20rbsAUScAVSg8ay'),
('3201234567890021', 'Joko Widodo', 'RT 001 Desa AAAA', '81234567810', 0, 0, 'warga', '$2y$10$WploTcYv4k4nHruROchP1up1Mg5gUyFYnpZktp3S8kjZZ4nA1om76'),
('3201234567890022', 'Ani Yulianti', 'RT 001 Desa AAAA', '81234567811', 0, 0, 'warga', '$2y$10$yHJTjcWJ1Kd.RrO007TmreTVE5qdVIqlxPOrtUDSWStsjpXFgLgdi'),
('3201234567890023', 'Surya Dharma', 'RT 001 Desa AAAA', '81234567812', 0, 0, 'warga', '$2y$10$hCbc939k4Z92RwteMSjSE.leSyylCFxOoN78MsE0Ln9IaEKbzqfTK'),
('3201234567890024', 'Tina Kartini', 'RT 001 Desa AAAA', '81234567813', 0, 1, 'warga', '$2y$10$qhtkF8/yRm8nUrak5wue.OPvAQqGLcL9zzmxekKZzLrLL8sAXksAy'),
('3201234567890025', 'Fajar Nugroho', 'RT 001 Desa AAAA', '81234567814', 0, 0, 'warga', '$2y$10$HDHhZB7XMuAWUryiIFHaf.ZNE/7KcycR.eqf3yRCKE9Kz1sOIx8hi'),
('3201234567890026', 'Diana Safitri', 'RT 001 Desa AAAA', '81234567815', 0, 0, 'warga', '$2y$10$SxPAuDCpZ5moVbfOTcojxe0SewrM4cH2nWyuDd97muFAZuNE60lMq'),
('3201234567890027', 'Tony Susanto', 'RT 001 Desa AAAA', '81234567816', 0, 0, 'warga', '$2y$10$/X.YxuoroW6IQzcRjDEsju6w8PKsExqyGOw3S/q/IOFWEkVt3X/iO'),
('3201234567890028', 'Lestari Wati', 'RT 001 Desa AAAA', '81234567817', 0, 0, 'warga', '$2y$10$5VifMk4/Cx15cW1qsUA/rOJnH7FzDrvSz2DAlpq0L13RxeaMzA.uS'),
('3201234567890029', 'Rico Mahendra', 'RT 001 Desa AAAA', '81234567818', 0, 0, 'warga', '$2y$10$zq/Ti7ddUDDJX0xntL1dauUn6CAFdGqMkgXPazspLQSb4bZlzt.72'),
('3201234567890030', 'Putri Amelia', 'RT 001 Desa AAAA', '81234567819', 0, 1, 'warga', '$2y$10$2kXDBA79Xj0ODfUM1.z8m.uRsBvHqN9lyo13AluSEKX1pB.lI/2Qe'),
('3201234567890031', 'Dani Kurniawan', 'RT 001 Desa AAAA', '81234567820', 0, 0, 'warga', '$2y$10$dH.r0x/tZXWQGG91wAFgNez6/FiZwYrbRmWeBdMD2wmvneQGlbP52'),
('3201234567890032', 'Evi Susanti', 'RT 001 Desa AAAA', '81234567821', 0, 0, 'warga', '$2y$10$jAinnk1Zb2f/HP2ci08JpOO4vQSs41Sf.MR3IsFOt4jH13IvNqkh2'),
('3201234567890033', 'Ryan Adiputra', 'RT 001 Desa AAAA', '81234567822', 0, 0, 'warga', '$2y$10$BjiU/KDa4byECQ6Ogus4ye.ETgLwKXosbhO7c/Mh.UV96qQ6TJQyi'),
('3201234567890034', 'Sinta Maharani', 'RT 001 Desa AAAA', '81234567823', 1, 0, 'warga', '$2y$10$PFBDKGqfs9.SVM2Hm.Df/e6J7647hdbKpvu36/hNwaYGm9gvgNi6u'),
('3201234567890035', 'Wahyu Setiadi', 'RT 001 Desa AAAA', '81234567824', 0, 0, 'warga', '$2y$10$vjlp17hHQLqgOOsXqGu5V.Oambhe908ETgUi.FKKNIlWW5FbGRk4S'),
('3201234567890036', 'Mega Sari', 'RT 001 Desa AAAA', '81234567825', 0, 0, 'warga', '$2y$10$BarDC.58B9FXtFeQ5yh8Ce9ehc29QpZcBRgP8Wi76EgU.80WSs0Iu'),
('3201234567890037', 'Ivan Prasetio', 'RT 001 Desa AAAA', '81234567826', 1, 0, 'warga', '$2y$10$b54VaBPifmJ63PvzmhoNzuM6RsOj8KCXkYE2k2dC22sootGXcBty.'),
('3201234567890038', 'Kirana Putri', 'RT 001 Desa AAAA', '81234567827', 0, 0, 'warga', '$2y$10$wgrjul3V27O4xhciSZ1JUuIWOcWTtQRp3Y/RUWUkwSgnoefSZgUX6'),
('3201234567890039', 'Andi Wijaya', 'RT 001 Desa AAAA', '81234567828', 1, 0, 'warga', '$2y$10$GwnC9G9n0Sbl3dcdirsyb.L/mN9PbzzJH8IE5lmQYHfOiToOTcC5W'),
('3201234567890040', 'Novi Handayani', 'RT 001 Desa AAAA', '81234567829', 0, 0, 'warga', '$2y$10$kyTmynqtcSrF/OvR2bOrOenne10GzpEed1pGV7NCxBOIthDUtXLMm'),
('3201234567890041', 'Ferry Gunawan', 'RT 001 Desa AAAA', '81234567830', 0, 0, 'warga', '$2y$10$qT8lfIEYnIbi0RwdON3IFet7.n1/JGkHM6MYT2iV1s9FC4NYwM43y'),
('3201234567890042', 'Lia Marliani', 'RT 001 Desa AAAA', '81234567831', 1, 1, 'warga', '$2y$10$lqh.yJOyWEJDqi0ot1a.POG4RRAwLdILNkvUOgBVMZDZIrVUlw6dW'),
('3201234567890043', 'Arief Nugroho', 'RT 001 Desa AAAA', '81234567832', 0, 0, 'warga', '$2y$10$qP9y5a5QciWrs2DoBedys.DjJ6KGIh1WQ6XnlquZ8WP1lyR/VbcF.'),
('3201234567890044', 'Yuli Astuti', 'RT 001 Desa AAAA', '81234567833', 0, 0, 'warga', '$2y$10$lA4PyEgnVRg27UKqQHgO0.J01IRgJKwzKM/PCrAAe94aau36V9eWe'),
('3201234567890045', 'Deny Firmansyah', 'RT 001 Desa AAAA', '81234567834', 0, 0, 'warga', '$2y$10$tCnAZqX6.LMjYVfz/k7EHO0m5d0sNK4zMXg/Ouiy3ypP5ieXZN4u.'),
('3201234567890046', 'Citra Dewi', 'RT 001 Desa AAAA', '81234567835', 1, 0, 'warga', '$2y$10$ENa0l.8FusAx/OeWyYUSAuBjCPJXVkJDpLt.uFnPLNYRQOgv2jpk2'),
('3201234567890047', 'Bobby Prasetya', 'RT 001 Desa AAAA', '81234567836', 0, 0, 'warga', '$2y$10$J/q3HaAbMmnbdhuqQ/528uz6ZD2t4.lgjXMICiOM.4iDW0bD.18wm'),
('3201234567890048', 'Winda Sari', 'RT 001 Desa AAAA', '81234567837', 0, 0, 'warga', '$2y$10$N/5t0RosktIjh1IB0v6wX.8kvOvSewPZb9gEYaS55bRZS.OGEGAFG'),
('3201234567890049', 'Gilang Ramadhan', 'RT 001 Desa AAAA', '81234567838', 0, 0, 'warga', '$2y$10$7XZNyMVzCCEKHtRFmfOp4OGBD9OTT4pJEBZAMCpk7EDgV5eXND51.'),
('3201234567890050', 'Silvia Anggraeni', 'RT 001 Desa AAAA', '81234567839', 1, 1, 'warga', '$2y$10$K.tKOMOMqGtMQ.jgrc6xj.T3KPbxnVP8h19anN2txQ7MOljWKzYEu'),
('3201234567890051', 'Rizal Hidayat', 'RT 001 Desa AAAA', '81234567840', 0, 0, 'warga', '$2y$10$ZhH7aK6sb.SVtHD9hb1U.eFx2A1nH3o3ujLJORp/7Zn7LlJ7dB1iW'),
('3201234567890052', 'Diah Permata', 'RT 001 Desa AAAA', '81234567841', 0, 0, 'warga', '$2y$10$S/JEgNH9nwyeC8u9gbsR9uYD5F2OJR5/f5cDOZ1gx9WypMbHBgVb.'),
('3201234567890053', 'Alex Hartono', 'RT 001 Desa AAAA', '81234567842', 0, 0, 'warga', '$2y$10$CRfRQ4XVBUrB7pWZv5rO7uUoPga8vhDA.U9JxpB/.lWD24567sTKC'),
('3201234567890054', 'Nina Rahayu', 'RT 001 Desa AAAA', '81234567843', 1, 0, 'warga', '$2y$10$XkuXkNcYJRRRhWOv/KaPpOp5ebFX83z62/fmwy6luAeQg/r1Vc.mO'),
('3201234567890055', 'Hadi Santoso', 'RT 001 Desa AAAA', '81234567844', 0, 0, 'warga', '$2y$10$tzw0iyajquW9P0K9rPCB8eKoFba5HY03/CjrMv2ZGGKEUnVqqMady'),
('3201234567890056', 'Rika Amalia', 'RT 001 Desa AAAA', '81234567845', 0, 0, 'warga', '$2y$10$QVWiE452sgpkmPvt6.3iMOvoQm9jk0FPxTBdj1kVahrc0s8ECgwD2'),
('3201234567890057', 'Bayu Saputra', 'RT 001 Desa AAAA', '81234567846', 1, 1, 'warga', '$2y$10$cq7KbQ172BMAPpCxcfUt5.OOZetkKCX6DUqPAlzpsT7MZ8UHIOQn.'),
('3201234567890058', 'Vera Oktavia', 'RT 001 Desa AAAA', '81234567847', 0, 0, 'warga', '$2y$10$WUXNJqSwVo7IdqVqkvOMBOiBDwKiawKLhDka8TE3fjukSmoDKl2ui'),
('3201234567890059', 'Rendi Maulana', 'RT 001 Desa AAAA', '81234567848', 0, 0, 'warga', '$2y$10$I1MtTwS0CvzI8RK94XwY0uRexCIFicO8.yojYJpoKcAiAiUBA5Fi6'),
('3201234567890060', 'Siska Febriani', 'RT 001 Desa AAAA', '81234567849', 1, 0, 'warga', '$2y$10$Y5R6HZAKW/mJBqxnQ4Efh.tbyq/7SM4Q9hfv8NBostlm75epK3Ah2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hewan_qurban`
--
ALTER TABLE `hewan_qurban`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nik` (`nik`);

--
-- Indexes for table `pembagian_daging`
--
ALTER TABLE `pembagian_daging`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nik` (`nik`);

--
-- Indexes for table `qurban_peserta`
--
ALTER TABLE `qurban_peserta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nik` (`nik`),
  ADD KEY `hewan_id` (`hewan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hewan_qurban`
--
ALTER TABLE `hewan_qurban`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `pembagian_daging`
--
ALTER TABLE `pembagian_daging`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `qurban_peserta`
--
ALTER TABLE `qurban_peserta`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keuangan`
--
ALTER TABLE `keuangan`
  ADD CONSTRAINT `keuangan_ibfk_1` FOREIGN KEY (`nik`) REFERENCES `users` (`nik`) ON DELETE SET NULL;

--
-- Constraints for table `pembagian_daging`
--
ALTER TABLE `pembagian_daging`
  ADD CONSTRAINT `pembagian_daging_ibfk_1` FOREIGN KEY (`nik`) REFERENCES `users` (`nik`) ON DELETE CASCADE;

--
-- Constraints for table `qurban_peserta`
--
ALTER TABLE `qurban_peserta`
  ADD CONSTRAINT `qurban_peserta_ibfk_1` FOREIGN KEY (`nik`) REFERENCES `users` (`nik`) ON DELETE CASCADE,
  ADD CONSTRAINT `qurban_peserta_ibfk_2` FOREIGN KEY (`hewan_id`) REFERENCES `hewan_qurban` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
