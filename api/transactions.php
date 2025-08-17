<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

try {
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            // Ambil semua transaksi
            $stmt = $pdo->query("SELECT * FROM transaksi ORDER BY tanggal DESC, created_at DESC");
            $transactions = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'transactions' => $transactions
            ]);
            break;
            
        case 'POST':
            // Tambah transaksi baru
            if (!isset($_POST['tanggal']) || !isset($_POST['jenis']) || 
                !isset($_POST['kategori']) || !isset($_POST['jumlah'])) {
                throw new Exception('Semua field wajib diisi');
            }
            
            $tanggal = $_POST['tanggal'];
            $jenis = $_POST['jenis'];
            $kategori = $_POST['kategori'];
            $jumlah = floatval($_POST['jumlah']);
            $deskripsi = $_POST['deskripsi'] ?? '';
            
            // Validasi data
            if ($jumlah <= 0) {
                throw new Exception('Jumlah harus lebih dari 0');
            }
            
            if (!in_array($jenis, ['masuk', 'keluar'])) {
                throw new Exception('Jenis transaksi tidak valid');
            }
            
            // Cek apakah kategori valid
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM kategori WHERE nama = ? AND jenis = ?");
            $stmt->execute([$kategori, $jenis]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception('Kategori tidak valid untuk jenis transaksi ini');
            }
            
            $stmt = $pdo->prepare("INSERT INTO transaksi (tanggal, jenis, kategori, deskripsi, jumlah) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$tanggal, $jenis, $kategori, $deskripsi, $jumlah]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'PUT':
            // Update transaksi
            parse_str(file_get_contents('php://input'), $putData);
            
            if (!isset($putData['id']) || !isset($putData['tanggal']) || 
                !isset($putData['jenis']) || !isset($putData['kategori']) || 
                !isset($putData['jumlah'])) {
                throw new Exception('Semua field wajib diisi');
            }
            
            $id = $putData['id'];
            $tanggal = $putData['tanggal'];
            $jenis = $putData['jenis'];
            $kategori = $putData['kategori'];
            $jumlah = floatval($putData['jumlah']);
            $deskripsi = $putData['deskripsi'] ?? '';
            
            // Validasi data
            if ($jumlah <= 0) {
                throw new Exception('Jumlah harus lebih dari 0');
            }
            
            if (!in_array($jenis, ['masuk', 'keluar'])) {
                throw new Exception('Jenis transaksi tidak valid');
            }
            
            // Cek apakah kategori valid
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM kategori WHERE nama = ? AND jenis = ?");
            $stmt->execute([$kategori, $jenis]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception('Kategori tidak valid untuk jenis transaksi ini');
            }
            
            // Cek apakah transaksi ada
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM transaksi WHERE id = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception('Transaksi tidak ditemukan');
            }
            
            $stmt = $pdo->prepare("UPDATE transaksi SET tanggal = ?, jenis = ?, kategori = ?, deskripsi = ?, jumlah = ? WHERE id = ?");
            $stmt->execute([$tanggal, $jenis, $kategori, $deskripsi, $jumlah, $id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil diupdate'
            ]);
            break;
            
        case 'DELETE':
            // Hapus transaksi
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID transaksi harus diisi');
            }
            
            // Cek apakah transaksi ada
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM transaksi WHERE id = ?");
            $stmt->execute([$id]);
            if ($stmt->fetchColumn() == 0) {
                throw new Exception('Transaksi tidak ditemukan');
            }
            
            $stmt = $pdo->prepare("DELETE FROM transaksi WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
            break;
            
        default:
            throw new Exception('Method tidak didukung');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
