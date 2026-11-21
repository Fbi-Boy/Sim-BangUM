<!-- Header -->
<div align="center">

# SIM-BangUM

### Sistem Informasi Manajemen Bagian Umum

**Satu platform untuk mengelola administrasi, layanan, dan operasional Bagian Umum secara terintegrasi.**

<br>

![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-Frontend-646CFF?style=flat-square&logo=vite&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-UI-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Database](https://img.shields.io/badge/MySQL%20%7C%20SQLite-Database-4479A1?style=flat-square&logo=mysql&logoColor=white)

[Ringkasan](#gambaran-umum) · [Preview](#preview-aplikasi) · [Modul](#modul-aplikasi) · [Teknologi](#teknologi) · [Instalasi](#instalasi)

</div>

---

## Gambaran Umum

**SIM-BangUM** merupakan aplikasi berbasis web untuk mendukung digitalisasi proses administrasi dan operasional Bagian Umum. Sistem ini mengintegrasikan pengelolaan persuratan, arsip, inventaris, fasilitas, perjalanan dinas, serta proses pengajuan dan persetujuan.

Akses aplikasi menerapkan *role-based access control* sehingga dashboard dan menu yang tersedia menyesuaikan tanggung jawab setiap pengguna.

## Preview Aplikasi

<!-- Letakkan screenshot aktual pada docs/images/ dengan nama login.png dan dashboard.png. -->

| Halaman Login | Dashboard |
|:---:|:---:|
| <img src="docs/images/login.png" alt="Halaman Login SIM-BangUM" width="100%"> | <img src="docs/images/dashboard.png" alt="Dashboard SIM-BangUM" width="100%"> |

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

## Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/Fbi-Boy/Sim-BangUM.git
   cd Sim-BangUM
   ```

2. **Install dependensi**
   ```bash
   composer install
   npm install
   ```

3. **Siapkan environment dan database**  
   Salin `.env.example` menjadi `.env`, lalu sesuaikan konfigurasi database.

4. **Jalankan aplikasi**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   npm run build
   php artisan serve
   ```

Buka **http://127.0.0.1:8000** melalui browser.

> Seeder menyediakan akun demo dengan password `password`. Gunakan hanya untuk pengembangan lokal, bukan produksi.

---

<div align="center">

**SIM-BangUM**  
*Sistem Informasi Manajemen Bagian Umum*

</div>
