-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Jul 2021 pada 02.54
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kasir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `faktur` varchar(225) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(225) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `tipe` varchar(225) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `foto` varchar(225) NOT NULL,
  `slug` varchar(225) NOT NULL,
  `nama_kategori` varchar(225) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `foto`, `slug`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, '1603171543_8af2dac61c210492b231.jpeg', 'makanan', 'Makanan', '2020-10-20 12:23:19', '2020-11-14 00:48:40'),
(3, '1603174478_e736e40f12c41266b111.jpg', 'minuman', 'Minuman', '2020-10-20 01:14:38', '2020-10-20 01:43:41'),
(12, 'default.jpg', 'sertifikat', 'Sertifikat', '2020-10-20 01:44:41', '2020-10-20 01:44:41'),
(13, '1603171543_8af2dac61c210492b231.jpeg', 'makanan', 'Makanan', '2020-11-13 20:02:02', '2020-11-14 00:48:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `faktur` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_pembeli` varchar(225) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`faktur`, `id_produk`, `nama_pembeli`, `kuantitas`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pembeli no 1', 2, '2020-10-15 20:56:52', '2020-10-15 20:56:52'),
(2, 1, 'Pembeli no 1', 9, '2020-10-15 20:56:52', '2020-10-15 20:56:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(225) NOT NULL,
  `foto` varchar(225) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `nama_produk`, `foto`, `cat_id`, `stok`, `harga`, `created_at`, `updated_at`) VALUES
(1, 'Mie Goreng', '1603080200_7e6e4852a78c285fb328.jpg', 1, 12, 10000, '2020-10-15 20:45:13', '2020-11-20 18:32:25'),
(4, 'Kerupuk Udang', '1602923751_cfe9664cd3546c23c6c5.jpg', 1, 2, 20000, '2020-10-17 03:35:51', '2020-11-20 18:33:19'),
(8, 'Pop Mie', 'default.jpg', 1, 0, 5000, '2020-10-18 03:49:27', NULL),
(9, 'Sarden Kaleng', '1603080809_9663a53cee3c07c02ab5.jpg', 1, 0, 17000, '2020-10-18 23:13:29', NULL),
(10, 'Mie Kari Ayam', 'default.jpg', 1, 0, 2500, '2020-10-21 03:02:07', NULL),
(11, 'Mie Korea', 'default.jpg', 1, 0, 3000, '2020-10-21 03:03:00', NULL),
(12, 'Mie Goreng Aceh', 'default.jpg', 1, 0, 2500, '2020-10-21 03:04:46', NULL),
(13, 'Teh Gelas', 'default.jpg', 3, 0, 1000, '2020-10-21 03:06:05', NULL),
(14, 'Okky Jelly Drink', 'default.jpg', 3, 0, 1000, '2020-10-21 03:06:38', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `struk`
--

CREATE TABLE `struk` (
  `id` int(11) NOT NULL,
  `faktur` varchar(225) NOT NULL,
  `fee` int(11) NOT NULL,
  `pay` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `struk_detail`
--

CREATE TABLE `struk_detail` (
  `id` int(11) NOT NULL,
  `faktur` varchar(225) NOT NULL,
  `nama_produk` varchar(225) NOT NULL,
  `harga` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(225) NOT NULL,
  `nama` varchar(225) NOT NULL,
  `password` varchar(225) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `nama`, `password`, `created_at`, `updated_at`) VALUES
(1, 'wahyu', 'Wahyu Doni', 'wahyudoni', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`faktur`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `struk`
--
ALTER TABLE `struk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `struk_detail`
--
ALTER TABLE `struk_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `faktur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `struk`
--
ALTER TABLE `struk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `struk_detail`
--
ALTER TABLE `struk_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
