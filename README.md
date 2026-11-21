# SIM-BangUM
### Sistem Informasi Manajemen Bagian Umum

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.3+">
  <img src="https://img.shields.io/badge/Frontend-Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/Styling-Tailwind%20CSS-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Database-MySQL%20%7C%20SQLite-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="Database">
</p>

<p align="center">
  <strong>Aplikasi manajemen administrasi dan operasional Bagian Umum dalam satu platform digital.</strong>
</p>

---

## Gambaran Umum

**SIM-BangUM (Sistem Informasi Manajemen Bagian Umum)** merupakan aplikasi berbasis web untuk mendukung digitalisasi proses administrasi dan operasional Bagian Umum. Aplikasi ini menyediakan pengelolaan layanan, dokumen, inventaris, fasilitas, serta alur pengajuan dan persetujuan melalui sistem yang terintegrasi.

Akses aplikasi disesuaikan dengan peran pengguna (*role-based access control*). Setiap pengguna memperoleh dashboard dan menu kerja sesuai tanggung jawabnya.

## Preview Aplikasi

<!-- Simpan screenshot aktual pada docs/images/ dengan nama login.png dan dashboard.png. -->

### Halaman Login

<p align="center">
  <img src="docs/images/login.png" alt="Preview halaman login SIM-BangUM" width="900">
</p>

<p align="center"><sub>Halaman autentikasi pengguna SIM-BangUM.</sub></p>

### Dashboard

<p align="center">
  <img src="docs/images/dashboard.png" alt="Preview dashboard SIM-BangUM" width="900">
</p>

<p align="center"><sub>Dashboard utama setelah pengguna berhasil masuk ke sistem.</sub></p>

## Modul Aplikasi

| Modul | Ringkasan |
|---|---|
| Dashboard & Ringkasan | Informasi dan aktivitas sesuai hak akses pengguna. |
| Manajemen Pengguna | Pengelolaan akun, role, dan hak akses sistem. |
| Persuratan | Pengelolaan surat masuk, surat keluar, dan disposisi. |
| Kearsipan | Pengelolaan data arsip dan dokumen pendukung. |
| Inventaris & Persediaan | Pengelolaan aset, peminjaman, permintaan barang, dan stok ATK. |
| Barang Milik Daerah | Pengelolaan data barang milik daerah. |
| Kendaraan Dinas | Pengelolaan kendaraan dan pengajuan penggunaannya. |
| Fasilitas & Ruangan | Pengelolaan fasilitas serta pemesanan ruangan. |
| Perjalanan Dinas | Pengelolaan pengajuan dan administrasi perjalanan dinas. |
| Protokol & Kegiatan | Pengelolaan kebutuhan kegiatan, agenda, dan tugas pendukung. |
| Persetujuan & Tindak Lanjut | Pemeriksaan dan tindak lanjut pengajuan maupun disposisi. |
| Laporan & Audit | Informasi untuk pemantauan dan pemeriksaan. |
| Activity Log | Pencatatan aktivitas pengguna untuk kebutuhan pengawasan. |

*Ketersediaan menu dan tindakan mengikuti implementasi aplikasi serta role yang diberikan kepada pengguna.*

## Role Pengguna

Role inti yang disediakan oleh data demo:

| Role | Deskripsi |
|---|---|
| super_admin | Mengelola administrasi dan konfigurasi sistem. |
| kepala_bagian | Dashboard pejabat serta proses pemeriksaan dan persetujuan. |
| kasubbag | Dashboard pejabat serta proses pemeriksaan dan persetujuan. |
| pegawai | Mengajukan kebutuhan layanan dan menindaklanjuti tugas atau disposisi. |
| auditor | Mengakses informasi dan laporan untuk kebutuhan audit. |

Route aplikasi juga menyediakan ruang akses untuk role operasional seperti pengelola persuratan, arsip, inventaris, BMD, kendaraan, fasilitas, protokol, dan perjalanan dinas. Penetapan role tersebut mengikuti konfigurasi data pengguna pada aplikasi.

## Teknologi

| Teknologi | Peran |
|---|---|
| PHP 8.3+ | Bahasa pemrograman backend |
| Laravel 13 | Framework aplikasi web |
| MySQL / SQLite | Database relasional |
| Blade | Template halaman aplikasi |
| Tailwind CSS | Styling antarmuka |
| Vite | Build dan development asset frontend |
| Alpine.js | Interaksi antarmuka |
| Flatpickr | Komponen pemilihan tanggal |
| Google Translate PHP | Dukungan layanan penerjemahan |

## Struktur Direktori

```text
Sim-BangUM/
├── app/                  # Controller, model, middleware, dan logic aplikasi
├── bootstrap/            # Bootstrap aplikasi Laravel
├── config/               # Konfigurasi aplikasi
├── database/
│   ├── migrations/       # Struktur tabel database
│   ├── factories/        # Factory data
│   └── seeders/          # Seeder dan data demo
├── public/               # Entry point dan asset publik
├── resources/
│   ├── views/            # Halaman Blade
│   ├── css/              # Sumber stylesheet
│   └── js/               # Sumber JavaScript
├── routes/               # Definisi route aplikasi
├── storage/              # Log, cache, dan file aplikasi
├── tests/                # Pengujian aplikasi
├── .env.example          # Contoh konfigurasi environment
├── composer.json         # Dependensi PHP
├── package.json          # Dependensi frontend
└── README.md             # Dokumentasi proyek
```

---

## Instalasi dan Konfigurasi Lokal

Panduan ini digunakan untuk menjalankan SIM-BangUM pada lingkungan pengembangan lokal seperti **Laragon, XAMPP, atau PHP development environment**.

### 1. Persyaratan Sistem

Pastikan perangkat telah memiliki:

- PHP **8.3 atau lebih baru**.
- Composer.
- Node.js dan npm.
- MySQL/MariaDB atau SQLite.
- Git.

Periksa instalasi melalui terminal:

```bash
php -v
composer -V
node -v
npm -v
git --version
```

### 2. Clone Repository

```bash
git clone https://github.com/Fbi-Boy/Sim-BangUM.git
cd Sim-BangUM
```

### 3. Install Dependensi

Install dependensi backend dan frontend:

```bash
composer install
npm install
```

### 4. Siapkan File Environment

Salin .env.example menjadi .env.

**Windows PowerShell:**

```powershell
Copy-Item .env.example .env
```

**Linux / macOS / Git Bash:**

```bash
cp .env.example .env
```

### 5. Konfigurasi Database

Buka file .env dan sesuaikan konfigurasi aplikasi.

Contoh konfigurasi MySQL:

```env
APP_NAME="SIM-BangUM"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sim_bangum
DB_USERNAME=root
DB_PASSWORD=
```

Buat database kosong bernama sim_bangum melalui phpMyAdmin atau MySQL. Sesuaikan nama database, username, dan password dengan konfigurasi lokal masing-masing.

**Alternatif SQLite:** File .env.example menggunakan SQLite secara default. Jika memilih SQLite, pastikan file database/database.sqlite tersedia dan gunakan DB_CONNECTION=sqlite. Tidak perlu membuat database MySQL.

### 6. Generate Application Key

```bash
php artisan key:generate
```

### 7. Migrasi Database dan Data Demo

Jalankan migration untuk membuat tabel sekaligus seeder untuk mengisi data awal:

```bash
php artisan migrate --seed
```

Seeder demo menyediakan akun pengujian berikut:

| Role | Email | Password |
|---|---|---|
| Super Admin | admin@sim-bu.local | password |
| Kepala Bagian | kaban@sim-bu.local | password |
| Kasubbag | kasubbag@sim-bu.local | password |
| Pegawai | pegawai1@sim-bu.local | password |
| Pegawai | pegawai2@sim-bu.local | password |
| Auditor | auditor@sim-bu.local | password |

> **Catatan keamanan:** Akun di atas hanya untuk pengembangan dan pengujian lokal. Jangan gunakan kredensial demo pada server produksi. Ganti password dan tinjau data demo sebelum aplikasi dipublikasikan.

### 8. Build Asset Frontend

```bash
npm run build
```

### 9. Jalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Buka aplikasi melalui browser:

```text
http://127.0.0.1:8000
```

Untuk pengembangan antarmuka dengan Vite, jalankan perintah berikut pada terminal terpisah:

```bash
npm run dev
```

### 10. Perintah Pengembangan Tambahan

| Perintah | Fungsi |
|---|---|
| php artisan migrate:status | Memeriksa status migration |
| php artisan migrate:fresh --seed | Membuat ulang seluruh tabel dan data demo |
| php artisan optimize:clear | Membersihkan cache aplikasi |
| php artisan route:list | Melihat daftar route |
| php artisan test | Menjalankan pengujian aplikasi |
| npm run build | Build asset frontend |

> **Perhatian:** Perintah migrate:fresh --seed menghapus seluruh tabel dan data pada database yang sedang digunakan. Gunakan hanya pada database pengembangan.

---

<p align="center">
  <strong>SIM-BangUM</strong><br>
  Sistem Informasi Manajemen Bagian Umum
</p>
