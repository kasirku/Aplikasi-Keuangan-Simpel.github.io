<?php
// File backup database sederhana
// Hanya untuk development/testing

require_once 'config/database.php';

$backupDir = 'backups/';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

$filename = $backupDir . 'backup_' . date('Y-m-d_H-i-s') . '.sql';

try {
    // Backup struktur tabel
    $tables = ['kategori', 'transaksi', 'users'];
    $backup = '';
    
    foreach ($tables as $table) {
        // Struktur tabel
        $stmt = $pdo->query("SHOW CREATE TABLE $table");
        $row = $stmt->fetch();
        $backup .= "\n\n" . $row['Create Table'] . ";\n\n";
        
        // Data tabel
        $stmt = $pdo->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll();
        
        if (!empty($rows)) {
            $backup .= "INSERT INTO `$table` VALUES\n";
            $values = [];
            foreach ($rows as $row) {
                $rowValues = array_map(function($value) {
                    if ($value === null) return 'NULL';
                    return "'" . addslashes($value) . "'";
                }, $row);
                $values[] = "(" . implode(',', $rowValues) . ")";
            }
            $backup .= implode(",\n", $values) . ";\n";
        }
    }
    
    file_put_contents($filename, $backup);
    
    echo "Backup berhasil dibuat: $filename";
    
} catch (Exception $e) {
    echo "Error backup: " . $e->getMessage();
}
?>
