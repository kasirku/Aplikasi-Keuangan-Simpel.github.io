<?php
require_once 'config/database.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=transaksi_' . date('Y-m-d') . '.csv');

echo "\xEF\xBB\xBF"; // UTF-8 BOM

$output = fopen('php://output', 'w');
fputcsv($output, ['Tanggal', 'Jenis', 'Kategori', 'Deskripsi', 'Jumlah']);

$stmt = $pdo->query("SELECT * FROM transaksi ORDER BY tanggal DESC");
while ($row = $stmt->fetch()) {
    fputcsv($output, [
        $row['tanggal'],
        $row['jenis'],
        $row['kategori'],
        $row['deskripsi'],
        $row['jumlah']
    ]);
}

fclose($output);
?>
