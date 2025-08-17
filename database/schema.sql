-- Buat database
CREATE DATABASE IF NOT EXISTS aplikasi_keuangan;
USE aplikasi_keuangan;

-- Buat tabel transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    jenis ENUM('masuk', 'keluar') NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    jumlah DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Buat tabel kategori
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jenis ENUM('masuk', 'keluar') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

        -- Insert kategori default yang baru
        INSERT INTO kategori (nama, jenis) VALUES
        ('Penjualan', 'masuk'),
        ('Modal', 'masuk'),
        ('Investasi', 'masuk'),
        ('Bonus', 'masuk'),
        ('Lainnya', 'masuk'),
        ('Pembelian', 'keluar'),
        ('Operasional', 'keluar'),
        ('Gaji Karyawan', 'keluar'),
        ('Biaya Utilitas', 'keluar'),
        ('Lainnya', 'keluar');

-- Tabel untuk pengguna
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Menambahkan pengguna admin default
-- Username: admin
-- Password: admin123
INSERT INTO `users` (`username`, `password`, `nama_lengkap`, `role`, `is_active`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Aplikasi', 'admin', 1);

-- =================================================================
-- TABEL DAN TRIGGER UNTUK HISTORI TRANSAKSI (SISTEM BACKUP REALTIME)
-- =================================================================

-- Tabel untuk histori/log transaksi
CREATE TABLE IF NOT EXISTS `transaksi_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_transaksi` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('masuk','keluar') NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `deskripsi` text,
  `jumlah` decimal(15,2) NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trigger untuk INSERT
DELIMITER $$
CREATE TRIGGER `trg_transaksi_insert` AFTER INSERT ON `transaksi`
FOR EACH ROW BEGIN
    INSERT INTO `transaksi_history` (`id_transaksi`, `tanggal`, `jenis`, `kategori`, `deskripsi`, `jumlah`, `action`)
    VALUES (NEW.id, NEW.tanggal, NEW.jenis, NEW.kategori, NEW.deskripsi, NEW.jumlah, 'INSERT');
END$$
DELIMITER ;

-- Trigger untuk UPDATE
DELIMITER $$
CREATE TRIGGER `trg_transaksi_update` AFTER UPDATE ON `transaksi`
FOR EACH ROW BEGIN
    INSERT INTO `transaksi_history` (`id_transaksi`, `tanggal`, `jenis`, `kategori`, `deskripsi`, `jumlah`, `action`)
    VALUES (NEW.id, NEW.tanggal, NEW.jenis, NEW.kategori, NEW.deskripsi, NEW.jumlah, 'UPDATE');
END$$
DELIMITER ;

-- Trigger untuk DELETE
DELIMITER $$
CREATE TRIGGER `trg_transaksi_delete` BEFORE DELETE ON `transaksi`
FOR EACH ROW BEGIN
    INSERT INTO `transaksi_history` (`id_transaksi`, `tanggal`, `jenis`, `kategori`, `deskripsi`, `jumlah`, `action`)
    VALUES (OLD.id, OLD.tanggal, OLD.jenis, OLD.kategori, OLD.deskripsi, OLD.jumlah, 'DELETE');
END$$
DELIMITER ;
