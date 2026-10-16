CREATE DATABASE IF NOT EXISTS `projectabsensi` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `projectabsensi`;

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id` int(20) NOT NULL,
  `nip_nis` varchar(30) NOT NULL,
  `id_kelas` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `status` enum('hadir','sakit','izin') NOT NULL,
  `bukti` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `absensi`
--

INSERT INTO `absensi` (`id`, `nip_nis`, `id_kelas`, `tanggal`, `jam`, `status`, `bukti`) VALUES
(10, '008123', 4, '2025-12-16', '20:43:18', 'izin', ''),
(12, '102030', 4, '2025-12-16', '21:29:52', 'hadir', '');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int(11) NOT NULL,
  `nama_kelas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`) VALUES
(1, 'X RPL A'),
(2, 'X RPL B'),
(3, 'XI RPL A'),
(4, 'XI RPL B'),
(5, 'XII RPL A'),
(6, 'XII RPL B');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip_nis` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','siswa') NOT NULL,
  `id_kelas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `nip_nis`, `password`, `role`, `id_kelas`) VALUES
(1, 'Administrator', 'admin', 'admin1', 'admin', NULL),
(2, 'Ganis Alya', '102030', '111', 'siswa', 4),
(3, 'Pak Budi', '001', '123', 'guru', NULL),
(4, 'Bu Sari', '002', '123456', 'guru', NULL),
(6, 'Savira Medina', '008123', '121', 'siswa', 4),
(7, 'Kayla Anindya Maharan', '654787659', '11', 'siswa', 2),
(8, 'Budi Santoso', '20241001', '12', 'siswa', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `fk_absensi_users` (`nip_nis`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nip_nis` (`nip_nis`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_absensi_users` FOREIGN KEY (`nip_nis`) REFERENCES `users` (`nip_nis`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id`) ON DELETE SET NULL;
--
-- Database: `sistemkbm`
--

