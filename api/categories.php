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
            // Ambil semua kategori
            $stmt = $pdo->query("SELECT * FROM kategori ORDER BY jenis, nama");
            $categories = $stmt->fetchAll();
            
            echo json_encode([
                'success' => true,
                'categories' => $categories
            ]);
            break;
            
        case 'POST':
            // Tambah kategori baru
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['nama']) || !isset($data['jenis'])) {
                throw new Exception('Nama dan jenis kategori harus diisi');
            }
            
            $stmt = $pdo->prepare("INSERT INTO kategori (nama, jenis) VALUES (?, ?)");
            $stmt->execute([$data['nama'], $data['jenis']]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'id' => $pdo->lastInsertId()
            ]);
            break;
            
        case 'PUT':
            // Update kategori
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['id']) || !isset($data['nama']) || !isset($data['jenis'])) {
                throw new Exception('ID, nama, dan jenis kategori harus diisi');
            }
            
            $stmt = $pdo->prepare("UPDATE kategori SET nama = ?, jenis = ? WHERE id = ?");
            $stmt->execute([$data['nama'], $data['jenis'], $data['id']]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Kategori berhasil diupdate'
            ]);
            break;
            
        case 'DELETE':
            // Hapus kategori
            $id = $_GET['id'] ?? null;
            
            if (!$id) {
                throw new Exception('ID kategori harus diisi');
            }
            
            // Cek apakah kategori digunakan dalam transaksi
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM transaksi WHERE kategori = (SELECT nama FROM kategori WHERE id = ?)");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                throw new Exception('Kategori tidak dapat dihapus karena masih digunakan dalam transaksi');
            }
            
            $stmt = $pdo->prepare("DELETE FROM kategori WHERE id = ?");
            $stmt->execute([$id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Kategori berhasil dihapus'
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
