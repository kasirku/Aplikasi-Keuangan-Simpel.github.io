<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit();
}

// Jika sudah login, lanjutkan
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pencatatan Keuangan</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div class="header-left">
                    <h1><i class="fas fa-wallet"></i> Aplikasi Keuangan Simpel</h1>
                    <p>Kelola keuangan Anda dengan mudah dan efisien</p>
                    <small>Selamat datang, <?php echo htmlspecialchars($_SESSION['nama_lengkap']); ?>!</small>
                </div>
                <div class="header-actions">
                    <a href="navigation.php" class="btn btn-secondary">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </header>

        <div class="summary-cards">
            <div class="card income">
                <div class="card-icon">
                    <i class="fas fa-arrow-up"></i>
                </div>
                <div class="card-content">
                    <h3>Total Pemasukan</h3>
                    <p class="amount" id="total-income">Rp 0</p>
                </div>
            </div>
            <div class="card expense">
                <div class="card-icon">
                    <i class="fas fa-arrow-down"></i>
                </div>
                <div class="card-content">
                    <h3>Total Pengeluaran</h3>
                    <p class="amount" id="total-expense">Rp 0</p>
                </div>
            </div>
            <div class="card balance">
                <div class="card-icon">
                    <i class="fas fa-balance-scale"></i>
                </div>
                <div class="card-content">
                    <h3>Saldo</h3>
                    <p class="amount" id="total-balance">Rp 0</p>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="form-section">
                <h2><i class="fas fa-plus-circle"></i> Tambah Transaksi Baru</h2>
                <form id="transaction-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" id="tanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="jenis">Jenis</label>
                            <select id="jenis" name="jenis" required>
                                <option value="">Pilih Jenis</option>
                                <option value="masuk">Pemasukan</option>
                                <option value="keluar">Pengeluaran</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <!-- Kategori akan diisi secara dinamis berdasarkan jenis transaksi -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="jumlah">Jumlah (Rp)</label>
                            <input type="number" id="jumlah" name="jumlah" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Keterangan transaksi..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Simpan Transaksi
                    </button>
                </form>
            </div>

            <div class="transactions-section">
                <div class="section-header">
                    <h2><i class="fas fa-list"></i> Riwayat Transaksi</h2>
                    <div class="filters">
                        <select id="filter-jenis">
                            <option value="">Semua Jenis</option>
                            <option value="masuk">Pemasukan</option>
                            <option value="keluar">Pengeluaran</option>
                        </select>
                        <select id="filter-kategori">
                            <option value="">Semua Kategori</option>
                        </select>
                        <input type="date" id="filter-tanggal" placeholder="Filter Tanggal">
                        <input type="text" id="filter-deskripsi" placeholder="Cari deskripsi..." style="margin-left: 10px;">
                    </div>
                </div>
                <div id="transactions-list">
                    <!-- Transaksi akan di-render di sini -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Transaksi -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Transaksi</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <form id="edit-form">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-tanggal">Tanggal</label>
                            <input type="date" id="edit-tanggal" name="tanggal" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-jenis">Jenis</label>
                            <select id="edit-jenis" name="jenis" required>
                                <option value="">Pilih Jenis</option>
                                <option value="masuk">Pemasukan</option>
                                <option value="keluar">Pengeluaran</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit-kategori">Kategori</label>
                            <select id="edit-kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit-jumlah">Jumlah (Rp)</label>
                            <input type="number" id="edit-jumlah" name="jumlah" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit-deskripsi">Deskripsi</label>
                        <textarea id="edit-deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-delete" class="btn-delete">
                    <i class="fas fa-trash"></i> Hapus
                    </button>
                <button type="submit" form="edit-form" class="btn-update">
                    <i class="fas fa-save"></i> Update
                </button>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>
