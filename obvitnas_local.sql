-- OBVITNAS Database Setup for Local Development
-- Created for local development environment

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create database
CREATE DATABASE IF NOT EXISTS obvitnas_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE obvitnas_db;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin-gi`
--

CREATE TABLE `admin-gi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `gi_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin-gi`
--

INSERT INTO `admin-gi` (`id`, `nama`, `username`, `email`, `password`, `gi_id`, `created_at`) VALUES
(1, NULL, 'Admin GI 1', 'Admingi1@gmail.com', '$2y$10$/6yrc0HY5NL0.6HGn8SNUu5FVnGqIONt6SBeaaXcaOKyb/MNjLQdi', 1, '2025-07-17 21:21:32'),
(3, NULL, 'Admin GI 4 ', 'Admingi4@gmail.com', '$2y$10$8DCg6Yyl1b2ZFMbZ84oT7u7Z4rJ3ohdzKkjoHDTl1ijVJuchuCW4u', 4, '2025-07-20 18:39:41');

-- --------------------------------------------------------

--
-- Struktur dari tabel `zona_gi`
--

CREATE TABLE `zona_gi` (
  `id` int(11) NOT NULL,
  `nama_gi` varchar(100) NOT NULL,
  `ultg_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `zona_gi`
--

INSERT INTO `zona_gi` (`id`, `nama_gi`, `ultg_id`) VALUES
(1, 'GI 150KV BANDUNG UTARA', 1),
(2, 'GI 150KV CIANJUR', 1),
(3, 'GI 150KV CIBEUREUM BARU', 1),
(4, 'GI 150KV CIGERELENG', 1),
(5, 'GI 150KV PADALARANG BARU', 1),
(6, 'GI 150KV PANASIA', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `zona_ultg`
--

CREATE TABLE `zona_ultg` (
  `id` int(11) NOT NULL,
  `nama_ultg` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `zona_ultg`
--

INSERT INTO `zona_ultg` (`id`, `nama_ultg`) VALUES
(1, 'ULTG BANDUNG');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_identitas` varchar(50) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','satpam','admin','admin_gi') NOT NULL DEFAULT 'user',
  `no_kendaraan` varchar(20) DEFAULT NULL,
  `keperluan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `no_identitas`, `no_hp`, `username`, `password`, `role`, `no_kendaraan`, `keperluan`, `created_at`) VALUES
(1, 'Admin System', '1234567890', '081234567890', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NULL, '2025-01-01 00:00:00'),
(2, 'Satpam 1', '0987654321', '089876543210', 'satpam1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'satpam', NULL, NULL, '2025-01-01 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kunjungan`
--

CREATE TABLE `kunjungan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gi_id` int(11) NOT NULL,
  `status` enum('pending','checkin','checkout','cancelled') NOT NULL DEFAULT 'pending',
  `jam_checkin` datetime DEFAULT NULL,
  `jam_checkout` datetime DEFAULT NULL,
  `undangan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin-gi`
--
ALTER TABLE `admin-gi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `zona_gi`
--
ALTER TABLE `zona_gi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ultg_id` (`ultg_id`);

--
-- Indeks untuk tabel `zona_ultg`
--
ALTER TABLE `zona_ultg`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `no_identitas` (`no_identitas`);

--
-- Indeks untuk tabel `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `gi_id` (`gi_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin-gi`
--
ALTER TABLE `admin-gi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `zona_gi`
--
ALTER TABLE `zona_gi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `zona_ultg`
--
ALTER TABLE `zona_ultg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel yang dibuang
--

--
-- Ketidakleluasaan untuk tabel `zona_gi`
--
ALTER TABLE `zona_gi`
  ADD CONSTRAINT `zona_gi_ibfk_1` FOREIGN KEY (`ultg_id`) REFERENCES `zona_ultg` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD CONSTRAINT `kunjungan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kunjungan_ibfk_2` FOREIGN KEY (`gi_id`) REFERENCES `zona_gi` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; 