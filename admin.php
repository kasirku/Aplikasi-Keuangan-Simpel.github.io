<?php
// Dashboard Admin untuk Aplikasi Keuangan
require_once 'config/database.php';
require_once 'config/config.php';

echo "<!DOCTYPE html>";
echo "<html lang='id'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Admin Dashboard - Aplikasi Keuangan</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 0; background: #f5f5f5; }";
echo ".header { background: #333; color: white; padding: 20px; text-align: center; }";
echo ".container { max-width: 1200px; margin: 0 auto; padding: 20px; }";
echo ".stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }";
echo ".stat-card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; }";
echo ".stat-number { font-size: 2rem; font-weight: bold; color: #007bff; }";
echo ".stat-label { color: #666; margin-top: 10px; }";
echo ".admin-section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin: 20px 0; }";
echo ".btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }";
echo ".btn:hover { background: #0056b3; }";
echo ".btn-danger { background: #dc3545; }";
echo ".btn-danger:hover { background: #c82333; }";
echo ".btn-success { background: #28a745; }";
echo ".btn-success:hover { background: #218838; }";
echo "table { width: 100%; border-collapse: collapse; margin: 20px 0; }";
echo "th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }";
echo "th { background: #f8f9fa; font-weight: bold; }";
echo "tr:hover { background: #f5f5f5; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='header'>";
echo "<h1>🔧 Admin Dashboard</h1>";
echo "<p>Kelola Aplikasi Keuangan Simpel</p>";
echo "</div>";

echo "<div class='container'>";

// Statistik Umum
echo "<div class='admin-section'>";
echo "<h2>📊 Statistik Umum</h2>";
echo "<div class='stats-grid'>";

try {
    // Total kategori
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM kategori");
    $result = $stmt->fetch();
    echo "<div class='stat-card'>";
    echo "<div class='stat-number'>" . $result['total'] . "</div>";
    echo "<div class='stat-label'>Total Kategori</div>";
    echo "</div>";
    
    // Total transaksi
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM transaksi");
    $result = $stmt->fetch();
    echo "<div class='stat-card'>";
    echo "<div class='stat-number'>" . $result['total'] . "</div>";
    echo "<div class='stat-label'>Total Transaksi</div>";
    echo "</div>";
    
    // Total pemasukan
    $stmt = $pdo->query("SELECT SUM(jumlah) as total FROM transaksi WHERE jenis = 'masuk'");
    $result = $stmt->fetch();
    $totalMasuk = $result['total'] ?: 0;
    echo "<div class='stat-card'>";
    echo "<div class='stat-number'>Rp " . number_format($totalMasuk, 0, ',', '.') . "</div>";
    echo "<div class='stat-label'>Total Pemasukan</div>";
    echo "</div>";
    
    // Total pengeluaran
    $stmt = $pdo->query("SELECT SUM(jumlah) as total FROM transaksi WHERE jenis = 'keluar'");
    $result = $stmt->fetch();
    $totalKeluar = $result['total'] ?: 0;
    echo "<div class='stat-card'>";
    echo "<div class='stat-number'>Rp " . number_format($totalKeluar, 0, ',', '.') . "</div>";
    echo "<div class='stat-label'>Total Pengeluaran</div>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='stat-card'>";
    echo "<div class='stat-number'>Error</div>";
    echo "<div class='stat-label'>Database Error</div>";
    echo "</div>";
}

echo "</div>";
echo "</div>";

// Manajemen Kategori
echo "<div class='admin-section'>";
echo "<h2>🏷️ Manajemen Kategori</h2>";

try {
    $stmt = $pdo->query("SELECT * FROM kategori ORDER BY jenis, nama");
    $categories = $stmt->fetchAll();
    
    if (count($categories) > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr><th>ID</th><th>Nama</th><th>Jenis</th><th>Dibuat</th><th>Aksi</th></tr>";
        echo "</thead>";
        echo "<tbody>";
        
        foreach ($categories as $cat) {
            echo "<tr>";
            echo "<td>" . $cat['id'] . "</td>";
            echo "<td>" . $cat['nama'] . "</td>";
            echo "<td>" . ($cat['jenis'] === 'masuk' ? 'Pemasukan' : 'Pengeluaran') . "</td>";
            echo "<td>" . $cat['created_at'] . "</td>";
            echo "<td>";
            echo "<a href='#' class='btn btn-success'>Edit</a>";
            echo "<a href='#' class='btn btn-danger'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>Tidak ada kategori tersedia.</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Transaksi Terbaru
echo "<div class='admin-section'>";
echo "<h2>💰 Transaksi Terbaru</h2>";

try {
    $stmt = $pdo->query("SELECT * FROM transaksi ORDER BY created_at DESC LIMIT 10");
    $transactions = $stmt->fetchAll();
    
    if (count($transactions) > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr><th>ID</th><th>Tanggal</th><th>Jenis</th><th>Kategori</th><th>Jumlah</th><th>Dibuat</th></tr>";
        echo "</thead>";
        echo "<tbody>";
        
        foreach ($transactions as $trans) {
            echo "<tr>";
            echo "<td>" . $trans['id'] . "</td>";
            echo "<td>" . $trans['tanggal'] . "</td>";
            echo "<td>" . ($trans['jenis'] === 'masuk' ? 'Pemasukan' : 'Pengeluaran') . "</td>";
            echo "<td>" . $trans['kategori'] . "</td>";
            echo "<td>Rp " . number_format($trans['jumlah'], 0, ',', '.') . "</td>";
            echo "<td>" . $trans['created_at'] . "</td>";
            echo "</tr>";
        }
        
        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>Belum ada transaksi tersedia.</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

echo "</div>";

// Aksi Admin
echo "<div class='admin-section'>";
echo "<h2>🎯 Aksi Admin</h2>";
echo "<a href='index.html' class='btn'>🏠 Buka Aplikasi</a>";
echo "<a href='export.php' class='btn'>📊 Export Data</a>";
echo "<a href='backup.php' class='btn'>💾 Backup Database</a>";
echo "<a href='stats.php' class='btn'>📈 Lihat Statistik</a>";
echo "<a href='test.php' class='btn'>🧪 Jalankan Test</a>";
echo "<a href='status.php' class='btn'>🔍 Lihat Status</a>";
echo "</div>";

echo "</div>";
echo "</body>";
echo "</html>";
?>
