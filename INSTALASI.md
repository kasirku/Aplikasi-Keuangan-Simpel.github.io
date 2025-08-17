# 📋 Panduan Instalasi Aplikasi Keuangan

## 🚀 Langkah Instalasi

### 1. Persiapan Awal
- Pastikan XAMPP/WAMP sudah terinstall dan berjalan
- Pastikan Apache dan MySQL service aktif
- Pastikan PHP versi 7.4 atau lebih tinggi

### 2. Setup Database
1. Buka browser dan akses: `http://localhost/Aplikasi%20Keuangan%20Simpel/install.php`
2. File ini akan otomatis:
   - Membuat database `aplikasi_keuangan`
   - Membuat tabel `kategori` dan `transaksi`
   - Menambahkan kategori default
3. Jika berhasil, akan muncul pesan "Instalasi berhasil!"

### 3. Konfigurasi Database (Opsional)
Jika ingin mengubah konfigurasi database:
1. Edit file `config/database.php`
2. Sesuaikan host, username, password, dan nama database
3. Restart web server jika diperlukan

### 4. Akses Aplikasi
1. Buka browser
2. Akses: `http://localhost/Aplikasi%20Keuangan%20Simpel/`
3. Aplikasi siap digunakan!

## 🔧 Troubleshooting

### Masalah Koneksi Database
- Pastikan MySQL service berjalan
- Cek username/password database
- Pastikan user memiliki hak akses CREATE DATABASE

### Error 500
- Cek error log Apache di folder logs
- Pastikan semua file dapat diakses
- Cek permission folder

### Transaksi Tidak Tersimpan
- Buka Developer Tools (F12) di browser
- Cek tab Console untuk error JavaScript
- Cek tab Network untuk error API

## 📁 Struktur File

```
Aplikasi Keuangan Simpel/
├── index.html              # Interface utama
├── install.php             # File instalasi database
├── test.php                # File testing
├── export.php              # Export data ke CSV
├── backup.php              # Backup database
├── stats.php               # Statistik keuangan
├── .htaccess               # Konfigurasi web server
├── README.md               # Dokumentasi
├── INSTALASI.md            # File ini
├── api/                    # API endpoints
│   ├── categories.php      # API kategori
│   └── transactions.php    # API transaksi
├── assets/                 # File frontend
│   ├── css/
│   │   └── style.css      # Styling aplikasi
│   └── js/
│       └── script.js      # JavaScript aplikasi
├── config/                 # Konfigurasi
│   ├── database.php       # Konfigurasi database
│   └── config.php         # Konfigurasi umum
└── database/               # File database
    └── schema.sql         # Struktur database
```

## 🌟 Fitur Aplikasi

- ✅ Dashboard ringkasan keuangan
- ✅ Pencatatan pemasukan dan pengeluaran
- ✅ Sistem kategori yang terorganisir
- ✅ Filter dan pencarian transaksi
- ✅ Edit dan hapus transaksi
- ✅ Interface yang responsif
- ✅ Export data ke CSV
- ✅ Backup database
- ✅ Statistik keuangan

## 🔒 Keamanan

- File konfigurasi dilindungi dengan .htaccess
- Menggunakan prepared statements untuk mencegah SQL injection
- Validasi input di sisi server
- CORS headers untuk keamanan API

## 📱 Kompatibilitas

- **Browser**: Chrome, Firefox, Safari, Edge (versi modern)
- **Mobile**: Responsif untuk smartphone dan tablet
- **OS**: Windows, macOS, Linux

## 🆘 Support

Jika mengalami masalah:
1. Jalankan `test.php` untuk diagnosis
2. Cek error log web server
3. Pastikan semua persyaratan terpenuhi
4. Restart web server jika diperlukan

---

**Selamat menggunakan Aplikasi Keuangan Simpel! 🎉**

**© 2025 Aplikasi Keuangan Simpel - Dibuat oleh arbain studio dan masih dalam masa percobaan**
