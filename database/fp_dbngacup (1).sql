-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Jun 2025 pada 16.08
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fp_dbngacup`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `tipe` enum('hot','cold') NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi`
--

CREATE TABLE `lokasi` (
  `id` int(11) NOT NULL,
  `nama_tempat` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `google_maps_link` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `harga_hot` int(11) DEFAULT NULL,
  `harga_cold` int(11) DEFAULT NULL,
  `is_best_seller` tinyint(1) DEFAULT 0,
  `is_recommended` tinyint(1) DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `nama_produk`, `kategori`, `harga_hot`, `harga_cold`, `is_best_seller`, `is_recommended`, `gambar`) VALUES
(1, 'Americano', 'Kopi Aja', 13000, 19000, 0, 1, 'menu_683bd38eae6ce0.30156299.jpg'),
(2, 'Cappuccino', 'Coffee', 19000, 22000, 0, 1, '68453586ed08b_R.jpeg'),
(3, 'Magic Latte', 'Kopi Aja', 28000, NULL, 1, 0, NULL),
(4, 'Teh Lychee', 'Teh Teh An', 16000, 20000, 1, 0, NULL),
(5, 'Teh Lemon', 'Teh Teh An', 16000, 20000, 0, 0, NULL),
(6, 'Teh Peach', 'Teh Teh An', 16000, 20000, 0, 0, NULL),
(7, 'Soda Jeruk', 'Seger Segeran', NULL, 10000, 1, 0, NULL),
(8, 'Soda Strawberry', 'Seger Segeran', NULL, 10000, 0, 0, NULL),
(9, 'Soda Blue', 'Seger Segeran', NULL, 10000, 0, 0, NULL),
(10, 'Indomie', 'Maem', NULL, 10000, 0, 0, NULL),
(11, 'Indomie Telor', 'Maem', NULL, 12000, 0, 0, NULL),
(12, 'Nasi Goreng', 'Maem', NULL, 15000, 1, 0, NULL),
(13, 'Brownies', 'Nyemil', NULL, 20000, 0, 1, NULL),
(14, 'Singkong Goreng', 'Nyemil', NULL, 8000, 0, 0, NULL),
(15, 'Tahu Crispy', 'Nyemil', NULL, 10000, 0, 0, NULL),
(16, 'es teh', 'minuman', 20000, 30000, 0, 0, NULL),
(17, 'kopi', 'minuman', 10000, 12000, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `alamat` text NOT NULL,
  `metode_pengiriman` varchar(50) NOT NULL,
  `metode_pembayaran` varchar(50) NOT NULL,
  `total_bayar` int(11) NOT NULL,
  `biaya_kurir` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Menunggu Konfirmasi',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `user_id`, `alamat`, `metode_pengiriman`, `metode_pembayaran`, `total_bayar`, `biaya_kurir`, `status`, `created_at`) VALUES
(1, 6, 'sidoarjo', 'Kurir Lokal', 'Transfer', 13000, 0, 'Selesai', '2025-06-14 18:52:06'),
(2, 6, 'sidoarjo', 'Kurir Lokal', 'COD', 1913000, 0, 'Menunggu Konfirmasi', '2025-06-14 20:06:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `tipe` varchar(10) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id`, `transaksi_id`, `produk_id`, `tipe`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 'hot', 1, 13000, 13000),
(2, 2, 1, 'hot', 1, 13000, 13000),
(3, 2, 1, 'cold', 100, 19000, 1900000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `jenis_user` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('aktif','suspended','banned') DEFAULT 'aktif',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `jenis_user`, `created_at`, `status`, `foto`) VALUES
(1, 'salman', 'kustomer@email.com', '$2y$10$HUidzfjhJ51l/Vtwsbknq.YAc4XLrOTEQJgVRd9SnwQWTN13WcDtm', '', '2025-06-01 11:30:15', 'banned', NULL),
(4, 'admin1', 'admin1@email.com', '$2y$10$GeroSMBSx2IPf/Jce2NWmOCUluu41lKi.tOZ12lzB8Lp2DxpgdtdW', 'admin', '2025-06-08 07:40:50', 'aktif', NULL),
(5, '', '', '$2y$10$Tjri3NHeK6VdXjPCVC4qtOTvS5o2sQndzubAUrodSImJtjZt6xR2C', '', '2025-06-14 06:00:36', '', NULL),
(6, 'coba', 'coba@email.com', '$2y$10$HII0.mzHXDP7bgSpcnYTQeaD.X4wlge2inx7aFbVzDc4hWuWezzVa', '', '2025-06-14 06:02:22', 'aktif', '684d800b0e00f.png');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indeks untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
