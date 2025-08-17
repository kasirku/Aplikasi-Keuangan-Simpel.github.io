<?php
require_once 'config/database.php';

header('Content-Type: application/json');

try {
    // Statistik bulanan
    $stmt = $pdo->query("
        SELECT 
            DATE_FORMAT(tanggal, '%Y-%m') as bulan,
            SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END) as total_masuk,
            SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) as total_keluar,
            SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE -jumlah END) as saldo
        FROM transaksi 
        GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
        ORDER BY bulan DESC
        LIMIT 12
    ");
    $monthlyStats = $stmt->fetchAll();
    
    // Statistik kategori
    $stmt = $pdo->query("
        SELECT 
            kategori,
            jenis,
            SUM(jumlah) as total
        FROM transaksi 
        GROUP BY kategori, jenis
        ORDER BY total DESC
    ");
    $categoryStats = $stmt->fetchAll();
    
    // Total keseluruhan
    $stmt = $pdo->query("
        SELECT 
            SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END) as total_masuk,
            SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) as total_keluar
        FROM transaksi
    ");
    $totalStats = $stmt->fetch();
    
    echo json_encode([
        'success' => true,
        'monthly_stats' => $monthlyStats,
        'category_stats' => $categoryStats,
        'total_stats' => $totalStats
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
