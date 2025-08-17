<?php
// File untuk menampilkan status aplikasi dan database
require_once 'config/database.php';
require_once 'config/config.php';

echo "<!DOCTYPE html>";
echo "<html lang='id'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Status Aplikasi Keuangan</title>";
echo "<style>";
echo "body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }";
echo ".container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo ".status-item { padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid; }";
echo ".success { background: #d4edda; border-left-color: #28a745; color: #155724; }";
echo ".error { background: #f8d7da; border-left-color: #dc3545; color: #721c24; }";
echo ".info { background: #d1ecf1; border-left-color: #17a2b8; color: #0c5460; }";
echo ".warning { background: #fff3cd; border-left-color: #ffc107; color: #856404; }";
echo "h1 { color: #333; text-align: center; }";
echo "h2 { color: #555; border-bottom: 2px solid #eee; padding-bottom: 10px; }";
echo ".btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin: 5px; }";
echo ".btn:hover { background: #0056b3; }";
echo "</style>";
echo "</head>";
echo "<body>";

echo "<div class='container'>";
echo "<h1>🔍 Status Aplikasi Keuangan</h1>";

// Status Database
echo "<h2>🗄️ Status Database</h2>";
try {
    // Test koneksi
    $stmt = $pdo->query("SELECT 1");
    echo "<div class='status-item success'>✅ Koneksi database berhasil</div>";
    
    // Cek tabel
    $tables = ['kategori', 'transaksi'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<div class='status-item success'>✅ Tabel '$table' tersedia</div>";
        } else {
            echo "<div class='status-item error'>❌ Tabel '$table' tidak ditemukan</div>";
        }
    }
    
    // Cek data kategori
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM kategori");
    $result = $stmt->fetch();
    echo "<div class='status-item info'>📊 Total kategori: " . $result['total'] . "</div>";
    
    // Cek data transaksi
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM transaksi");
    $result = $stmt->fetch();
    echo "<div class='status-item info'>💰 Total transaksi: " . $result['total'] . "</div>";
    
} catch (Exception $e) {
    echo "<div class='status-item error'>❌ Error database: " . $e->getMessage() . "</div>";
}

// Status File
echo "<h2>📁 Status File</h2>";
$requiredFiles = [
    'index.html' => 'Interface utama',
    'assets/css/style.css' => 'File CSS',
    'assets/js/script.js' => 'File JavaScript',
    'api/categories.php' => 'API Kategori',
    'api/transactions.php' => 'API Transaksi',
    'config/database.php' => 'Konfigurasi Database',
    'config/config.php' => 'Konfigurasi Umum'
];

foreach ($requiredFiles as $file => $description) {
    if (file_exists($file)) {
        $size = filesize($file);
        echo "<div class='status-item success'>✅ $description ($file) - " . number_format($size) . " bytes</div>";
    } else {
        echo "<div class='status-item error'>❌ $description ($file) - File tidak ditemukan</div>";
    }
}

// Status Konfigurasi
echo "<h2>⚙️ Status Konfigurasi</h2>";
echo "<div class='status-item info'>🌍 Environment: " . ENVIRONMENT . "</div>";
echo "<div class='status-item info'>🕐 Timezone: " . date_default_timezone_get() . "</div>";
echo "<div class='status-item info'>💱 Currency: " . CURRENCY_CODE . " (" . CURRENCY_SYMBOL . ")</div>";

// Status Fitur
echo "<h2>🚀 Status Fitur</h2>";
$features = [
    'FEATURE_EXPORT' => 'Export Data',
    'FEATURE_BACKUP' => 'Backup Database',
    'FEATURE_STATS' => 'Statistik Keuangan'
];

foreach ($features as $feature => $name) {
    if (defined($feature)) {
        $status = constant($feature) ? 'Aktif' : 'Nonaktif';
        $color = constant($feature) ? 'success' : 'warning';
        echo "<div class='status-item $color'>🔧 $name: $status</div>";
    }
}

// Tombol Aksi
echo "<h2>🎯 Aksi</h2>";
echo "<a href='index.html' class='btn'>🏠 Buka Aplikasi</a>";
echo "<a href='test.php' class='btn'>🧪 Jalankan Test</a>";
echo "<a href='export.php' class='btn'>📊 Export Data</a>";
echo "<a href='backup.php' class='btn'>💾 Backup Database</a>";
echo "<a href='stats.php' class='btn'>📈 Lihat Statistik</a>";

echo "</div>";
echo "</body>";
echo "</html>";
?>
