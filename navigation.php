<?php
session_start();
?>
<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Navigasi Aplikasi Keuangan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .header { text-align: center; color: white; margin-bottom: 40px; }
        .header h1 { font-size: 3rem; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .header p { font-size: 1.2rem; opacity: 0.9; }
        .nav-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .nav-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s ease; display: flex; flex-direction: column; }
        .nav-card:hover { transform: translateY(-5px); }
        .nav-icon { font-size: 3rem; margin-bottom: 20px; color: #667eea; }
        .nav-title { font-size: 1.5rem; font-weight: bold; color: #333; margin-bottom: 15px; }
        .nav-desc { color: #666; margin-bottom: 20px; line-height: 1.5; flex-grow: 1; }
        .nav-btn { display: inline-block; padding: 12px 24px; background: linear-gradient(135deg, #667eea, #764ba2); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: transform 0.3s ease; }
        .nav-btn:hover { transform: translateY(-2px); }
        .footer { text-align: center; color: white; margin-top: 40px; opacity: 0.8; }
    </style>
</head>
<body>

<div class='container'>
    <div class='header'>
        <h1><i class="fas fa-rocket"></i> Navigasi Aplikasi</h1>
        <p>Pilih tujuan Anda dari menu di bawah ini.</p>
    </div>

    <div class='nav-grid'>

        <div class='nav-card utama'>
            <div class='nav-icon'><i class="fas fa-cash-register"></i></div>
            <div class='nav-title'>Aplikasi Utama</div>
            <div class='nav-desc'>Interface utama untuk mencatat dan mengelola transaksi keuangan.</div>
            <a href='index.php' class='nav-btn'>Buka Aplikasi</a>
        </div>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin'): ?>
        <div class='nav-card'>
            <div class='nav-icon'><i class="fas fa-users-cog"></i></div>
            <div class='nav-title'>Manajemen User</div>
            <div class='nav-desc'>Tambah, edit, dan kelola pengguna sistem (khusus admin).</div>
            <a href='user-management.php' class='nav-btn'>Kelola User</a>
        </div>
        <?php endif; ?>

        <div class='nav-card'>
            <div class='nav-icon'><i class="fas fa-chart-pie"></i></div>
            <div class='nav-title'>Statistik</div>
            <div class='nav-desc'>Lihat statistik keuangan dalam format grafik dan JSON.</div>
            <a href='stats.php' class='nav-btn'>Lihat Stats</a>
        </div>

        <div class='nav-card'>
            <div class='nav-icon'><i class="fas fa-file-export"></i></div>
            <div class='nav-title'>Export Data</div>
            <div class='nav-desc'>Export data transaksi ke format CSV untuk analisis lebih lanjut.</div>
            <a href='export.php' class='nav-btn'>Export Data</a>
        </div>

        <div class='nav-card'>
            <div class='nav-icon'><i class="fas fa-database"></i></div>
            <div class='nav-title'>Backup Database</div>
            <div class='nav-desc'>Buat backup database SQL untuk keamanan data.</div>
            <a href='backup.php' class='nav-btn'>Backup DB</a>
        </div>

        <div class='nav-card'>
            <div class='nav-icon'><i class="fas fa-info-circle"></i></div>
            <div class='nav-title'>Status Aplikasi</div>
            <div class='nav-desc'>Lihat status database, file, dan konfigurasi aplikasi.</div>
            <a href='status.php' class='nav-btn'>Lihat Status</a>
        </div>

        <?php if (!isset($_SESSION['user_id'])): ?>
        <div class='nav-card'>
            <div class='nav-icon'><i class="fas fa-sign-in-alt"></i></div>
            <div class='nav-title'>Login</div>
            <div class='nav-desc'>Masuk ke sistem untuk mengakses fitur-fitur aplikasi.</div>
            <a href='login.php' class='nav-btn'>Login</a>
        </div>
        <?php else: ?>
        <div class='nav-card'>
            <div class='nav-icon'><i class="fas fa-sign-out-alt"></i></div>
            <div class='nav-title'>Logout</div>
            <div class='nav-desc'>Keluar dari sesi Anda saat ini.</div>
            <a href='logout.php' class='nav-btn'>Logout</a>
        </div>
        <?php endif; ?>

    </div>

    <div class='footer'>
        <p>&copy; 2025 Aplikasi Keuangan Simpel - Dibuat oleh arbain studio dan masih dalam masa percobaan</p>
    </div>

</div>
</body>
</html>