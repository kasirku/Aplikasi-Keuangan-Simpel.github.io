<?php
session_start();

// Cek login dan role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once 'config/database.php';

$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_user':
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $nama_lengkap = trim($_POST['nama_lengkap']);
                $email = trim($_POST['email']);
                $role = $_POST['role'];
                
                if (empty($username) || empty($password) || empty($nama_lengkap)) {
                    $message = 'Username, password, dan nama lengkap harus diisi!';
                    $messageType = 'error';
                } else {
                    try {
                        // Cek username sudah ada atau belum
                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
                        $stmt->execute([$username]);
                        if ($stmt->fetchColumn() > 0) {
                            $message = 'Username sudah digunakan!';
                            $messageType = 'error';
                        } else {
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                            $stmt = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, email, role) VALUES (?, ?, ?, ?, ?)");
                            $stmt->execute([$username, $hashedPassword, $nama_lengkap, $email, $role]);
                            
                            $message = 'User berhasil ditambahkan!';
                            $messageType = 'success';
                        }
                    } catch (Exception $e) {
                        $message = 'Gagal menambahkan user: ' . $e->getMessage();
                        $messageType = 'error';
                    }
                }
                break;
                
            case 'update_user':
                $user_id = $_POST['user_id'];
                $nama_lengkap = trim($_POST['nama_lengkap']);
                $email = trim($_POST['email']);
                $role = $_POST['role'];
                $is_active = isset($_POST['is_active']) ? 1 : 0;
                
                try {
                    $stmt = $pdo->prepare("UPDATE users SET nama_lengkap = ?, email = ?, role = ?, is_active = ? WHERE id = ?");
                    $stmt->execute([$nama_lengkap, $email, $role, $is_active, $user_id]);
                    
                    $message = 'User berhasil diupdate!';
                    $messageType = 'success';
                } catch (Exception $e) {
                    $message = 'Gagal mengupdate user: ' . $e->getMessage();
                    $messageType = 'error';
                }
                break;
                
            case 'change_password':
                $user_id = $_POST['user_id'];
                $new_password = $_POST['new_password'];
                
                if (empty($new_password)) {
                    $message = 'Password baru harus diisi!';
                    $messageType = 'error';
                } else {
                    try {
                        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                        $stmt->execute([$hashedPassword, $user_id]);
                        
                        $message = 'Password berhasil diubah!';
                        $messageType = 'success';
                    } catch (Exception $e) {
                        $message = 'Gagal mengubah password: ' . $e->getMessage();
                        $messageType = 'error';
                    }
                }
                break;
                
            case 'delete_user':
                $user_id = $_POST['user_id'];
                
                if ($user_id == $_SESSION['user_id']) {
                    $message = 'Tidak bisa menghapus akun sendiri!';
                    $messageType = 'error';
                } else {
                    try {
                        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                        $stmt->execute([$user_id]);
                        
                        $message = 'User berhasil dihapus!';
                        $messageType = 'success';
                    } catch (Exception $e) {
                        $message = 'Gagal menghapus user: ' . $e->getMessage();
                        $messageType = 'error';
                    }
                }
                break;
        }
    }
}

// Load users
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (Exception $e) {
    $users = [];
    $message = 'Gagal memuat data user: ' . $e->getMessage();
    $messageType = 'error';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Aplikasi Keuangan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #f5f7fa; color: #333; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 0; }
        .header-content { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger { background: #dc3545; color: white; }
        .card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; padding: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #e9ecef; }
        .table th { background: #f8f9fa; font-weight: 600; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-error { background: #f8d7da; color: #721c24; }
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: white; margin: 5% auto; padding: 0; border-radius: 10px; width: 90%; max-width: 500px; }
        .modal-header { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 10px 10px 0 0; }
        .modal-body { padding: 20px; }
        .modal-footer { padding: 20px; border-top: 1px solid #e9ecef; text-align: right; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-control { width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; }
        .close { color: white; float: right; font-size: 28px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1><i class="fas fa-users"></i> Manajemen User</h1>
            <div>
                <a href="index.html" class="btn btn-primary">Beranda</a>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Daftar User</h2>
            <button class="btn btn-primary" onclick="openModal('addUserModal')">
                <i class="fas fa-plus"></i> Tambah User
            </button>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['nama_lengkap']); ?></td>
                            <td><?php echo htmlspecialchars($user['email'] ?: '-'); ?></td>
                            <td><?php echo ucfirst($user['role']); ?></td>
                            <td><?php echo $user['is_active'] ? 'Aktif' : 'Nonaktif'; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editUser(<?php echo htmlspecialchars(json_encode($user)); ?>)">Edit</button>
                                <button class="btn btn-success btn-sm" onclick="changePassword(<?php echo $user['id']; ?>)">Password</button>
                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                    <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['id']; ?>)">Hapus</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah User -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah User Baru</h3>
                <span class="close" onclick="closeModal('addUserModal')">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add_user">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('addUserModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit User</h3>
                <span class="close" onclick="closeModal('editUserModal')">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="update_user">
                <input type="hidden" id="edit-user-id" name="user_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" id="edit-username" name="username" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" id="edit-nama-lengkap" name="nama_lengkap" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="edit-email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select id="edit-role" name="role" class="form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="edit-is-active" name="is_active" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('editUserModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ganti Password -->
    <div id="changePasswordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ganti Password</h3>
                <span class="close" onclick="closeModal('changePasswordModal')">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="change_password">
                <input type="hidden" id="change-password-user-id" name="user_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Password Baru *</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('changePasswordModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editUser(user) {
            document.getElementById('edit-user-id').value = user.id;
            document.getElementById('edit-username').value = user.username;
            document.getElementById('edit-nama-lengkap').value = user.nama_lengkap;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-role').value = user.role;
            document.getElementById('edit-is-active').value = user.is_active;
            openModal('editUserModal');
        }

        function changePassword(userId) {
            document.getElementById('change-password-user-id').value = userId;
            openModal('changePasswordModal');
        }

        function deleteUser(userId) {
            if (confirm('Yakin ingin menghapus user ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = '<input type="hidden" name="action" value="delete_user"><input type="hidden" name="user_id" value="' + userId + '">';
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>
