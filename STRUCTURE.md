# 📁 Struktur Aplikasi Keuangan Simpel

## 🗂️ Struktur Direktori

```
Aplikasi Keuangan Simpel/
├── 📄 index.php                 # Halaman navigasi utama
├── 📄 index.html                # Interface aplikasi keuangan
├── 📄 admin.php                 # Dashboard admin
├── 📄 status.php                # Status aplikasi dan database
├── 📄 test.php                  # Testing aplikasi
├── 📄 install.php               # Instalasi database
├── 📄 export.php                # Export data ke CSV
├── 📄 backup.php                # Backup database
├── 📄 stats.php                 # Statistik keuangan (JSON)
├── 📄 .htaccess                 # Konfigurasi web server
├── 📄 README.md                 # Dokumentasi utama
├── 📄 INSTALASI.md              # Panduan instalasi
├── 📄 STRUCTURE.md              # File ini
│
├── 📁 api/                      # API Endpoints
│   ├── 📄 categories.php        # API untuk kategori
│   └── 📄 transactions.php      # API untuk transaksi
│
├── 📁 assets/                   # File Frontend
│   ├── 📁 css/
│   │   └── 📄 style.css        # Styling aplikasi
│   └── 📁 js/
│       └── 📄 script.js        # JavaScript aplikasi
│
├── 📁 config/                   # Konfigurasi
│   ├── 📄 database.php         # Konfigurasi database
│   └── 📄 config.php           # Konfigurasi umum
│
└── 📁 database/                 # File Database
    └── 📄 schema.sql           # Struktur database
```

## 🎯 Deskripsi File

### 📄 File Utama
- **`index.php`** - Halaman navigasi utama dengan menu semua fitur
- **`index.html`** - Interface aplikasi keuangan untuk user
- **`admin.php`** - Dashboard admin untuk manajemen sistem

### 🔧 File Sistem
- **`status.php`** - Monitoring status aplikasi dan database
- **`test.php`** - Testing komprehensif semua komponen
- **`install.php`** - Setup database otomatis
- **`.htaccess`** - Konfigurasi web server dan keamanan

### 📊 File Data
- **`export.php`** - Export transaksi ke CSV
- **`backup.php`** - Backup database
- **`stats.php`** - API statistik keuangan

### 📁 Direktori API
- **`api/categories.php`** - CRUD kategori transaksi
- **`api/transactions.php`** - CRUD transaksi keuangan

### 🎨 Direktori Assets
- **`assets/css/style.css`** - Styling modern dan responsif
- **`assets/js/script.js`** - JavaScript untuk interaktivitas

### ⚙️ Direktori Config
- **`config/database.php`** - Koneksi database
- **`config/config.php`** - Konfigurasi aplikasi

### 🗄️ Direktori Database
- **`database/schema.sql`** - Struktur tabel database

## 🚀 Cara Akses

### 1. **Halaman Utama**
- `http://localhost/Aplikasi%20Keuangan%20Simpel/` → `index.php` (navigasi)
- `http://localhost/Aplikasi%20Keuangan%20Simpel/index.html` → aplikasi keuangan

### 2. **Admin & Monitoring**
- `http://localhost/Aplikasi%20Keuangan%20Simpel/admin.php` → dashboard admin
- `http://localhost/Aplikasi%20Keuangan%20Simpel/status.php` → status aplikasi
- `http://localhost/Aplikasi%20Keuangan%20Simpel/test.php` → testing

### 3. **Data & Backup**
- `http://localhost/Aplikasi%20Keuangan%20Simpel/export.php` → export CSV
- `http://localhost/Aplikasi%20Keuangan%20Simpel/backup.php` → backup database
- `http://localhost/Aplikasi%20Keuangan%20Simpel/stats.php` → statistik JSON

## 🔒 Keamanan

- File konfigurasi dilindungi dengan `.htaccess`
- API endpoints menggunakan prepared statements
- Validasi input di sisi server
- CORS headers untuk keamanan

## 📱 Fitur

- ✅ Interface responsif untuk mobile dan desktop
- ✅ Dashboard admin yang lengkap
- ✅ Monitoring status aplikasi
- ✅ Testing komprehensif
- ✅ Export dan backup data
- ✅ Statistik keuangan real-time
- ✅ Keamanan yang terjamin

## 🎨 Desain

- Gradient background yang modern
- Cards yang informatif
- Animasi dan transisi smooth
- Icon yang intuitif
- Layout yang rapi dan terorganisir

---

**Struktur ini dirancang untuk kemudahan penggunaan dan maintenance aplikasi keuangan.**

**© 2025 Aplikasi Keuangan Simpel - Dibuat oleh arbain studio dan masih dalam masa percobaan**
