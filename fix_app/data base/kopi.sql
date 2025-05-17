-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2024 at 08:41 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kopi`
--

-- --------------------------------------------------------

--
-- Table structure for table `detailtransaksi`
--

CREATE TABLE `detailtransaksi` (
  `id_detail_transaksi` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_kopi` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detailtransaksi`
--

INSERT INTO `detailtransaksi` (`id_detail_transaksi`, `id_transaksi`, `id_kopi`, `kuantitas`, `harga_satuan`) VALUES
(20, 30, 18, 3, 27000.00),
(21, 30, 19, 1, 28000.00),
(22, 31, 18, 3, 27000.00),
(23, 32, 18, 1, 27000.00),
(24, 33, 18, 1, 27000.00),
(25, 34, 18, 1, 27000.00),
(26, 35, 18, 1, 27000.00),
(27, 36, 18, 1, 27000.00),
(28, 37, 18, 1, 27000.00),
(29, 38, 17, 1, 25000.00);

--
-- Triggers `detailtransaksi`
--
DELIMITER $$
CREATE TRIGGER `after_delete_detailtransaksi` AFTER DELETE ON `detailtransaksi` FOR EACH ROW BEGIN
    UPDATE Kopi
    SET stok = stok + OLD.kuantitas
    WHERE id_kopi = OLD.id_kopi;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_insert_detailtransaksi` AFTER INSERT ON `detailtransaksi` FOR EACH ROW BEGIN
    UPDATE Kopi
    SET stok = stok - NEW.kuantitas
    WHERE id_kopi = NEW.id_kopi;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_detailtransaksi` AFTER UPDATE ON `detailtransaksi` FOR EACH ROW BEGIN
    UPDATE Kopi
    SET stok = stok + OLD.kuantitas - NEW.kuantitas
    WHERE id_kopi = NEW.id_kopi;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kopi` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `gula` varchar(20) NOT NULL DEFAULT 'tanpa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `id_kopi`, `kuantitas`, `gula`) VALUES
(21, 8, 18, 10, 'sedikit'),
(22, 8, 17, 1, ''),
(39, 4, 18, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `kopi`
--

CREATE TABLE `kopi` (
  `id_kopi` int(11) NOT NULL,
  `nama_kopi` varchar(100) NOT NULL,
  `jenis_kopi` varchar(50) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `stok` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `gambar_produk` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kopi`
--

INSERT INTO `kopi` (`id_kopi`, `nama_kopi`, `jenis_kopi`, `harga`, `stok`, `deskripsi`, `gambar_produk`) VALUES
(17, 'BTS Choco', 'Minuman', 25000.00, 18, 'Minuman cokelat dengan cita rasa kopi khas', 'btschoco.png'),
(18, 'BTS Coffee', 'Minuman', 27000.00, 21, 'Kopi klasik dengan rasa khas BTS', 'btscoffe.png'),
(19, 'BTS White', 'Minuman', 28000.00, 10, 'Kopi susu dengan rasa creamy', 'btswhite.png'),
(20, 'Eskosu', 'Minuman', 22000.00, 42, 'Es kopi susu lokal dengan rasa khas', 'eskosu.jpg'),
(21, 'Espresso', 'Minuman', 15000.00, 53, 'Kopi hitam murni dengan rasa pekat', 'espresso.jpg'),
(22, 'Frape', 'Minuman', 30000.00, 35, 'Minuman kopi dingin yang menyegarkan', 'frape.jpg'),
(23, 'Manual Brew', 'Minuman', 40000.00, 48, 'Kopi manual brew untuk pecinta kopi sejati', 'manualbrew.jpg'),
(24, 'Nitro Coffee', 'Minuman', 45000.00, 20, 'Kopi dengan sensasi nitrogen', 'nitrocoffee.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `log_transaksi`
--

CREATE TABLE `log_transaksi` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `waktu_aksi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_transaksi`
--

INSERT INTO `log_transaksi` (`id`, `id_transaksi`, `action`, `waktu_aksi`) VALUES
(1, 27, 'Status Pesanan Selesai', '2024-12-14 16:00:02'),
(2, 27, 'Status Pesanan Selesai', '2024-12-14 16:00:02'),
(3, 28, 'Status Pesanan Selesai', '2024-12-14 16:00:02'),
(4, 26, 'Status Pesanan Selesai', '2024-12-14 16:11:45'),
(5, 29, 'Status Pesanan Selesai', '2024-12-15 12:52:41'),
(6, 24, 'Status Pesanan Selesai', '2024-12-15 13:33:52'),
(7, 30, 'Status Pesanan Selesai', '2024-12-15 16:18:05'),
(8, 31, 'Status Pesanan Selesai', '2024-12-15 18:50:11'),
(9, 32, 'Status Pesanan Selesai', '2024-12-15 18:50:17'),
(10, 38, 'Status Pesanan Selesai', '2024-12-15 19:35:41'),
(11, 33, 'Status Pesanan Selesai', '2024-12-15 19:35:47'),
(12, 34, 'Status Pesanan Selesai', '2024-12-15 19:35:54'),
(13, 35, 'Status Pesanan Selesai', '2024-12-15 19:36:05'),
(14, 36, 'Status Pesanan Selesai', '2024-12-15 19:36:10');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `metode_pembayaran` enum('Transfer Bank','GoPay','Dana','COD') NOT NULL,
  `status_pembayaran` enum('Menunggu Pembayaran','Dibayar','Gagal') DEFAULT 'Menunggu Pembayaran',
  `nomor_rekening` varchar(50) DEFAULT NULL,
  `nomor_hp` varchar(20) DEFAULT NULL,
  `tanggal_pembayaran` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promo`
--

CREATE TABLE `promo` (
  `id_promo` int(11) NOT NULL,
  `nama_promo` varchar(100) NOT NULL,
  `diskon` decimal(5,2) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `id_produk` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo`
--

INSERT INTO `promo` (`id_promo`, `nama_promo`, `diskon`, `tanggal_mulai`, `tanggal_selesai`, `id_produk`) VALUES
(1, 'Diskon 10% Desember', 10.00, '2024-12-01', '2024-12-31', NULL),
(2, 'Promo 2 Kopi 1 Gratis', 50.00, '2024-12-10', '2024-12-20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT 'Tunai',
  `status_pesanan` enum('Diproses','Selesai','Dibatalkan') DEFAULT 'Diproses',
  `nomor_meja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `tanggal_transaksi`, `total_harga`, `metode_pembayaran`, `status_pesanan`, `nomor_meja`) VALUES
(30, 4, '2024-12-15', 109000.00, 'COD', 'Diproses', '3'),
(31, 4, '2024-12-16', 81000.00, 'COD', 'Selesai', '2'),
(32, 4, '2024-12-16', 27000.00, 'COD', 'Selesai', '2'),
(33, 4, '2024-12-16', 27000.00, 'Transfer Bank', 'Selesai', ''),
(34, 4, '2024-12-16', 27000.00, 'OVO', 'Selesai', ''),
(35, 4, '2024-12-16', 27000.00, 'Transfer Bank', 'Selesai', '2'),
(36, 4, '2024-12-16', 27000.00, 'GoPay', 'Selesai', ''),
(37, 4, '2024-12-16', 27000.00, 'GoPay', 'Diproses', ''),
(38, 4, '2024-12-16', 25000.00, 'GoPay', 'Selesai', '2');

--
-- Triggers `transaksi`
--
DELIMITER $$
CREATE TRIGGER `after_update_transaksi_status` AFTER UPDATE ON `transaksi` FOR EACH ROW BEGIN
    IF NEW.status_pesanan = 'Selesai' THEN
        
        
        
        INSERT INTO log_transaksi (id_transaksi, action) 
        VALUES (NEW.id_transaksi, 'Status Pesanan Selesai');
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_backup`
--

CREATE TABLE `transaksi_backup` (
  `id_transaksi` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL,
  `tanggal_transaksi` date NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `metode_pembayaran` varchar(50) DEFAULT 'Tunai',
  `status_pesanan` enum('Diproses','Selesai','Dibatalkan') DEFAULT 'Diproses',
  `nomor_meja` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi_backup`
--

INSERT INTO `transaksi_backup` (`id_transaksi`, `id_user`, `tanggal_transaksi`, `total_harga`, `metode_pembayaran`, `status_pesanan`, `nomor_meja`) VALUES
(24, 4, '2024-12-14', 405000.00, 'COD', 'Dibatalkan', '3'),
(26, 4, '2024-12-14', 40000.00, 'OVO', 'Diproses', '7'),
(27, 4, '2024-12-14', 54000.00, 'GoPay', 'Selesai', ''),
(28, 4, '2024-12-14', 25000.00, 'COD', 'Selesai', '9');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kopi` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `komentar` text DEFAULT NULL,
  `tanggal_ulas` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `id_user`, `id_kopi`, `rating`, `komentar`, `tanggal_ulas`) VALUES
(5, 4, 23, 5, 'fdvr', '2024-12-08 18:09:21'),
(6, 4, 23, 5, 'fdvr', '2024-12-08 18:09:21'),
(7, 4, 23, 5, 'fdvr', '2024-12-08 18:09:21'),
(8, 4, 19, 4, 'enakk', '2024-12-08 18:09:21'),
(9, 4, 21, 2, 'inii ga enak jangan di beli', '2024-12-08 18:30:05'),
(10, 4, 18, 3, 'enak pol kata irfan', '2024-12-15 18:37:08');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nomor_telepon` varchar(15) NOT NULL,
  `alamat` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`, `nama`, `email`, `nomor_telepon`, `alamat`, `status`, `created_at`, `updated_at`) VALUES
(4, 'nurul', '$2y$10$EYY9SRId5Sw7UPjZq0c5yO8le2JNUhMfNob2RDF2lEAeSG3gLsQYC', 'customer', 'nurul fitriyah', 'nfitriyah878@gmail.com', '098765', 'parung panjang', 'active', '2024-12-06 14:48:17', '2024-12-06 14:48:17'),
(8, 'irfan', '827ccb0eea8a706c4c34a16891f84e7b', 'admin', 'irfan fauzi', 'afst@gmail', '893487', 'c ', 'active', '2024-12-14 05:25:57', '2024-12-14 05:25:57'),
(9, 'irfann', '$2y$10$wZbx7wtr1DdHUWOdS36ZkOTx0xMIJ602cjQIInUkUeFex.Klgl08e', 'customer', 'irfanfauzi', 'nhuf@gmail', '346', 'fdg', 'active', '2024-12-15 12:47:01', '2024-12-15 12:47:01'),
(10, 'fauzi', '202cb962ac59075b964b07152d234b70', 'admin', 'irfan ganteng', 'skdnfjhe@gmail', '435', 'fgb', 'active', '2024-12-15 12:58:31', '2024-12-15 12:58:31'),
(12, 'aku', '827ccb0eea8a706c4c34a16891f84e7b', 'admin', 'jskdvn', 'jkh@hhdj', '423', 'dfv', 'active', '2024-12-15 14:48:20', '2024-12-15 14:48:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detailtransaksi`
--
ALTER TABLE `detailtransaksi`
  ADD PRIMARY KEY (`id_detail_transaksi`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_kopi` (`id_kopi`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `fk_user` (`id_user`),
  ADD KEY `fk_kopi` (`id_kopi`);

--
-- Indexes for table `kopi`
--
ALTER TABLE `kopi`
  ADD PRIMARY KEY (`id_kopi`);

--
-- Indexes for table `log_transaksi`
--
ALTER TABLE `log_transaksi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `promo`
--
ALTER TABLE `promo`
  ADD PRIMARY KEY (`id_promo`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `idx_tanggal_transaksi` (`tanggal_transaksi`),
  ADD KEY `fk_transaksi_user` (`id_user`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kopi` (`id_kopi`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detailtransaksi`
--
ALTER TABLE `detailtransaksi`
  MODIFY `id_detail_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `kopi`
--
ALTER TABLE `kopi`
  MODIFY `id_kopi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `log_transaksi`
--
ALTER TABLE `log_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `promo`
--
ALTER TABLE `promo`
  MODIFY `id_promo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detailtransaksi`
--
ALTER TABLE `detailtransaksi`
  ADD CONSTRAINT `detailtransaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detailtransaksi_ibfk_2` FOREIGN KEY (`id_kopi`) REFERENCES `kopi` (`id_kopi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `fk_kopi` FOREIGN KEY (`id_kopi`) REFERENCES `kopi` (`id_kopi`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_kopi`) REFERENCES `kopi` (`id_kopi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `fk_transaksi_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ulasan_ibfk_2` FOREIGN KEY (`id_kopi`) REFERENCES `kopi` (`id_kopi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
