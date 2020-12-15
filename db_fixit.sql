-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Des 2020 pada 16.33
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_fixit`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`) VALUES
(1, 'Admin Fixit', 'admin', '$2y$10$FEnutsKviFMAZNa8V.K15OoXZXD83Rn/zMoUis/MaH8zh/5OmlRHu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cashier`
--

CREATE TABLE `cashier` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `cashier`
--

INSERT INTO `cashier` (`id`, `name`, `birth_date`, `phone_number`, `username`, `password`, `gender`, `address`, `created_at`, `updated_at`) VALUES
(2, 'Cashier 1', '2001-11-21', '098765', 'cashier1', '$2y$10$cXfKUdWcFECHGOf29FGU6.azlpR4MbGnZkXG6WGTwOBp1aIYsPydK', 'female', 'Jl. jalan', '2020-12-09 09:47:42', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `vehicle_children_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `in_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`id`, `vehicle_id`, `vehicle_children_id`, `name`, `price`, `stock`, `created_at`, `updated_at`, `in_active`) VALUES
(1, NULL, NULL, 'Oli Repsol', 30000, 84, '2020-12-08 19:48:26', '2020-12-15 22:27:35', 1),
(8, 1, 2, 'Kanvas Rem', 15000, 95, '2020-12-15 10:23:31', '2020-12-15 22:31:43', 1),
(10, 1, NULL, 'Oli Yamalube', 20000, 70, '2020-12-15 22:16:39', '2020-12-15 22:31:43', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase`
--

CREATE TABLE `purchase` (
  `id` int(11) NOT NULL,
  `invoice` text DEFAULT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `purchase`
--

INSERT INTO `purchase` (`id`, `invoice`, `supplier_name`, `total_qty`, `total_price`, `created_at`) VALUES
(1, 'INV-P20121558311', 'Honda Warehouse', 42, 960000, '2020-12-15 22:27:35'),
(2, 'INV-P20121521242', 'Bintara Yamaha Motor', 30, 500000, '2020-12-15 22:31:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `purchase_detail`
--

CREATE TABLE `purchase_detail` (
  `id` int(11) NOT NULL,
  `purchase_id` int(11) NOT NULL,
  `item_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`item_data`)),
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `purchase_detail`
--

INSERT INTO `purchase_detail` (`id`, `purchase_id`, `item_data`, `qty`, `price`) VALUES
(1, 1, '{\"name\":\"Oli Repsol\",\"price\":\"30000\",\"price_currency_format\":\"Rp. 30.000\",\"stock\":\"84\",\"created_at\":\"2020-12-08 19:48:26\",\"updated_at\":\"2020-12-15 22:27:35\",\"in_active\":true,\"vehicle\":null}', 12, 360000),
(2, 1, '{\"name\":\"Oli Yamalube\",\"price\":\"20000\",\"price_currency_format\":\"Rp. 20.000\",\"stock\":\"60\",\"created_at\":\"2020-12-15 22:16:39\",\"updated_at\":\"2020-12-15 22:27:35\",\"in_active\":true,\"vehicle\":{\"name\":\"Yamaha\",\"created_at\":\"2020-12-08 10:47:33\",\"updated_at\":null,\"in_active\":true,\"children\":null}}', 30, 600000),
(3, 2, '{\"name\":\"Oli Yamalube\",\"price\":\"20000\",\"price_currency_format\":\"Rp. 20.000\",\"stock\":\"70\",\"created_at\":\"2020-12-15 22:16:39\",\"updated_at\":\"2020-12-15 22:31:43\",\"in_active\":true,\"vehicle\":{\"name\":\"Yamaha\",\"created_at\":\"2020-12-08 10:47:33\",\"updated_at\":null,\"in_active\":true,\"children\":null}}', 10, 200000),
(4, 2, '{\"name\":\"Kanvas Rem\",\"price\":\"15000\",\"price_currency_format\":\"Rp. 15.000\",\"stock\":\"95\",\"created_at\":\"2020-12-15 10:23:31\",\"updated_at\":\"2020-12-15 22:31:43\",\"in_active\":true,\"vehicle\":{\"name\":\"Yamaha\",\"created_at\":\"2020-12-08 10:47:33\",\"updated_at\":null,\"in_active\":true,\"children\":{\"name\":\"R15 250CC\",\"created_at\":\"2020-12-14 16:54:05\",\"updated_at\":\"2020-12-15 09:49:48\",\"in_active\":true}}}', 20, 300000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `vehicle_children_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `service`
--

INSERT INTO `service` (`id`, `vehicle_id`, `vehicle_children_id`, `name`, `price`, `created_at`, `updated_at`) VALUES
(7, 3, 4, 'Servis Ringan', 40000, '2020-12-15 09:58:15', NULL),
(9, 3, 4, 'Servis Lengkap', 75000, '2020-12-15 10:25:54', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `password_default` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `setting`
--

INSERT INTO `setting` (`id`, `password_default`) VALUES
(1, '123456');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `cashier_id` int(11) NOT NULL,
  `invoice` text NOT NULL,
  `queue` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `discount` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `discount_price` int(11) NOT NULL,
  `status` enum('waiting','repair','complete') NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction_detail`
--

CREATE TABLE `transaction_detail` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `vehicle_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `service_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `item_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `vehicle`
--

CREATE TABLE `vehicle` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `in_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `vehicle`
--

INSERT INTO `vehicle` (`id`, `name`, `created_at`, `updated_at`, `in_active`) VALUES
(1, 'Yamaha', '2020-12-08 10:47:33', NULL, 1),
(3, 'Suzuki', '2020-12-14 16:53:52', '2020-12-15 10:19:13', 1),
(4, 'Honda', '2020-12-15 15:07:05', '2020-12-15 21:37:14', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `vehicle_children`
--

CREATE TABLE `vehicle_children` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `in_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `vehicle_children`
--

INSERT INTO `vehicle_children` (`id`, `vehicle_id`, `name`, `created_at`, `updated_at`, `in_active`) VALUES
(1, 1, 'Jupiter MX 135 CC', '2020-12-08 13:54:29', NULL, 1),
(2, 1, 'R15 250CC', '2020-12-14 16:54:05', '2020-12-15 09:49:48', 1),
(4, 3, 'GSX-R 150', '2020-12-14 16:54:46', NULL, 1),
(5, 4, 'CBR 150', '2020-12-15 15:07:14', NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `cashier`
--
ALTER TABLE `cashier`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `vehicle_children_id` (`vehicle_children_id`);

--
-- Indeks untuk tabel `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `purchase_detail`
--
ALTER TABLE `purchase_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_id` (`purchase_id`);

--
-- Indeks untuk tabel `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `vehicle_children_id` (`vehicle_children_id`);

--
-- Indeks untuk tabel `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cashier_id` (`cashier_id`);

--
-- Indeks untuk tabel `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indeks untuk tabel `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `vehicle_children`
--
ALTER TABLE `vehicle_children`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `cashier`
--
ALTER TABLE `cashier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `purchase`
--
ALTER TABLE `purchase`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `purchase_detail`
--
ALTER TABLE `purchase_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `transaction_detail`
--
ALTER TABLE `transaction_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `vehicle_children`
--
ALTER TABLE `vehicle_children`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`vehicle_children_id`) REFERENCES `vehicle_children` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `purchase_detail`
--
ALTER TABLE `purchase_detail`
  ADD CONSTRAINT `purchase_detail_ibfk_1` FOREIGN KEY (`purchase_id`) REFERENCES `purchase` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `service_ibfk_2` FOREIGN KEY (`vehicle_children_id`) REFERENCES `vehicle_children` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`cashier_id`) REFERENCES `cashier` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaction_detail`
--
ALTER TABLE `transaction_detail`
  ADD CONSTRAINT `transaction_detail_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `vehicle_children`
--
ALTER TABLE `vehicle_children`
  ADD CONSTRAINT `vehicle_children_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
