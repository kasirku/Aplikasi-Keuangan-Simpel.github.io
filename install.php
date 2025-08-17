<?php
// File instalasi database otomatis
// Jalankan file ini sekali untuk setup database

echo "<h1>Instalasi Database Aplikasi Keuangan</h1>";

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Koneksi ke MySQL tanpa database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Berhasil koneksi ke MySQL</p>";
    
    // Buat database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS aplikasi_keuangan");
    echo "<p>✅ Database 'aplikasi_keuangan' berhasil dibuat</p>";
    
    // Pilih database
    $pdo->exec("USE aplikasi_keuangan");
    
    // Buat tabel kategori
    $pdo->exec("CREATE TABLE IF NOT EXISTS kategori (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(100) NOT NULL,
        jenis ENUM('masuk', 'keluar') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "<p>✅ Tabel 'kategori' berhasil dibuat</p>";
    
    // Buat tabel transaksi
    $pdo->exec("CREATE TABLE IF NOT EXISTS transaksi (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tanggal DATE NOT NULL,
        jenis ENUM('masuk', 'keluar') NOT NULL,
        kategori VARCHAR(100) NOT NULL,
        deskripsi TEXT,
        jumlah DECIMAL(15,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "<p>✅ Tabel 'transaksi' berhasil dibuat</p>";
    
    // Buat tabel user
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        nama_lengkap VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        role ENUM('admin', 'user') DEFAULT 'user',
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
    echo "<p>✅ Tabel 'users' berhasil dibuat</p>";
    
    // Insert kategori default yang baru
    $kategoriDefault = [
        // Kategori Pemasukan
        ['Penjualan', 'masuk'],
        ['Modal', 'masuk'],
        ['Investasi', 'masuk'],
        ['Bonus', 'masuk'],
        ['Lainnya', 'masuk'],
        
        // Kategori Pengeluaran
        ['Pembelian', 'keluar'],
        ['Operasional', 'keluar'],
        ['Gaji Karyawan', 'keluar'],
        ['Biaya Utilitas', 'keluar'],
        ['Lainnya', 'keluar']
    ];
    
    $stmt = $pdo->prepare("INSERT IGNORE INTO kategori (nama, jenis) VALUES (?, ?)");
    foreach ($kategoriDefault as $kategori) {
        $stmt->execute($kategori);
    }
    echo "<p>✅ Kategori default berhasil ditambahkan</p>";
    
    // Insert user default admin
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (username, password, nama_lengkap, email, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin', $adminPassword, 'Administrator', 'admin@example.com', 'admin']);
    echo "<p>✅ User admin default berhasil ditambahkan</p>";
    echo "<p><strong>Username: admin</strong></p>";
    echo "<p><strong>Password: admin123</strong></p>";
    
    echo "<h2>🎉 Instalasi berhasil!</h2>";
    echo "<p>Aplikasi keuangan siap digunakan.</p>";
    echo "<p><a href='index.html'>Klik di sini untuk membuka aplikasi</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Pastikan:</p>";
    echo "<ul>";
    echo "<li>MySQL/MariaDB berjalan</li>";
    echo "<li>Username dan password database benar</li>";
    echo "<li>User database memiliki hak akses untuk membuat database</li>";
    echo "</ul>";
}
?>
