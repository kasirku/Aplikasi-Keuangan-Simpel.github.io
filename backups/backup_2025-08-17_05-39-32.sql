

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

INSERT INTO `kategori` VALUES
('1','Penjualan','masuk','2025-08-17 05:57:50'),
('2','Modal','masuk','2025-08-17 05:57:50'),
('3','Investasi','masuk','2025-08-17 05:57:50'),
('4','Bonus','masuk','2025-08-17 05:57:51'),
('5','Lainnya','masuk','2025-08-17 05:57:51'),
('6','Pembelian','keluar','2025-08-17 05:57:51'),
('7','Operasional','keluar','2025-08-17 05:57:51'),
('8','Gaji Karyawan','keluar','2025-08-17 05:57:51'),
('9','Biaya Utilitas','keluar','2025-08-17 05:57:51'),
('10','Lainnya','keluar','2025-08-17 05:57:51'),
('11','Penjualan','masuk','2025-08-17 06:20:54'),
('12','Modal','masuk','2025-08-17 06:20:54'),
('13','Investasi','masuk','2025-08-17 06:20:54'),
('14','Bonus','masuk','2025-08-17 06:20:54'),
('15','Lainnya','masuk','2025-08-17 06:20:54'),
('16','Pembelian','keluar','2025-08-17 06:20:54'),
('17','Operasional','keluar','2025-08-17 06:20:54'),
('18','Gaji Karyawan','keluar','2025-08-17 06:20:54'),
('19','Biaya Utilitas','keluar','2025-08-17 06:20:54'),
('20','Lainnya','keluar','2025-08-17 06:20:54');


CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

INSERT INTO `transaksi` VALUES
('3','2025-08-17','masuk','Modal','modal','300000.00','2025-08-17 07:19:42','2025-08-17 07:19:42'),
('4','2025-08-17','keluar','Pembelian','beli','200000.00','2025-08-17 07:20:00','2025-08-17 07:20:00'),
('5','2025-08-17','masuk','Penjualan','jual','250000.00','2025-08-17 09:58:22','2025-08-17 10:03:08'),
('6','2025-08-17','keluar','Pembelian','beli','300000.00','2025-08-17 09:58:43','2025-08-17 09:58:43'),
('7','2025-08-17','masuk','Bonus','backup','20000.00','2025-08-17 10:33:09','2025-08-17 10:33:09');


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` VALUES
('1','admin','$2y$10$1wsx773n9R/y37qIGyFeau6KdHWI8KuXQUnb3vyAW.Aiq2VntMCHe','Administrator','admin@example.com','admin','1','2025-08-17 06:20:54','2025-08-17 06:20:54'),
('3','123','$2y$10$s0UPkdwsTSx9pwCVMi6vT.lhbCeGzMoWPufYzD6hlAugVq9E2zC1e','Abdul Rozak','','user','1','2025-08-17 10:05:02','2025-08-17 10:05:02');
