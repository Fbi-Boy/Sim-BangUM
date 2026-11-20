# Panduan Instalasi SIM-BangUM

Dokumen ini menjelaskan cara memasang dan menjalankan **SIM-BangUM (Sistem Informasi Manajemen Bagian Umum)** pada komputer atau server lokal menggunakan Laragon atau XAMPP.

> Panduan ini ditujukan untuk lingkungan pengembangan/lokal. Untuk deployment ke server production, diperlukan konfigurasi keamanan, web server, HTTPS, backup, dan environment production tersendiri.

## 1. Persyaratan Sistem

Pastikan perangkat sudah memiliki perangkat lunak berikut:

| Komponen | Versi / Keterangan |
|---|---|
| PHP | 8.3 atau lebih baru yang kompatibel dengan Laravel 13 |
| Composer | Versi stabil terbaru |
| Node.js & npm | Versi LTS yang kompatibel dengan Vite |
| MySQL / MariaDB | Disediakan oleh Laragon atau XAMPP |
| Git | Untuk mengambil source code |
| Browser | Chrome, Firefox, Edge, atau browser modern lainnya |

### Ekstensi PHP

Pastikan ekstensi PHP yang umum dibutuhkan Laravel aktif, khususnya:

- `ctype`
- `curl`
- `dom`
- `fileinfo`
- `filter`
- `hash`
- `mbstring`
- `openssl`
- `pcre`
- `pdo`
- `pdo_mysql` (untuk MySQL)
- `session`
- `tokenizer`
- `xml`

Pada Laragon/XAMPP, ekstensi dapat diaktifkan melalui konfigurasi `php.ini`. Setelah mengubah konfigurasi, restart service atau terminal yang digunakan.

Periksa versi perangkat lunak:

```bash
php -v
composer --version
node -v
npm -v
git --version
```

## 2. Menjalankan Service Lokal

### Menggunakan Laragon

1. Buka Laragon.
2. Jalankan **Start All**.
3. Pastikan Apache/Nginx dan MySQL berjalan.
4. Buka Terminal Laragon.

### Menggunakan XAMPP

1. Buka XAMPP Control Panel.
2. Jalankan **Apache** dan **MySQL**.
3. Buka terminal/CMD atau Git Bash.
4. Pastikan perintah PHP dan Composer dapat dikenali dari terminal. Jika belum, tambahkan direktori PHP ke PATH atau gunakan terminal bawaan XAMPP.

## 3. Clone Repository

Pilih direktori kerja lokal. Contoh untuk Laragon:

```bash
cd C:/laragon/www
git clone https://github.com/Fbi-Boy/Sim-BangUM.git
cd Sim-BangUM
```

Jika menggunakan XAMPP, proyek dapat ditempatkan di:

```text
C:/xampp/htdocs/Sim-BangUM
```

Jika ingin mengambil branch pengembangan dashboard yang sedang digunakan:

```bash
git switch feat/dashboards-all-roles
```

## 4. Instalasi Dependency Backend

Jalankan dari direktori utama proyek:

```bash
composer install
```

Composer akan memasang dependency PHP sesuai konfigurasi `composer.json`.

## 5. Membuat File Environment

Salin file environment contoh:

**Windows CMD:**
```bat
copy .env.example .env
```

**PowerShell:**
```powershell
Copy-Item .env.example .env
```

**Git Bash:**
```bash
cp .env.example .env
```

Kemudian buka file `.env` dan sesuaikan konfigurasi aplikasi serta database.

Contoh konfigurasi MySQL lokal:

```dotenv
APP_NAME="SIM-BangUM"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sim_bangum
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME`, `DB_PASSWORD`, dan port dengan konfigurasi MySQL pada komputer masing-masing. Pada instalasi lokal XAMPP/Laragon, akun database sering kali menggunakan `root` tanpa password, tetapi hal ini tidak selalu sama pada setiap perangkat.

## 6. Membuat Database

1. Buka phpMyAdmin melalui Laragon/XAMPP, atau gunakan klien database pilihan.
2. Buat database baru dengan nama `sim_bangum`.
3. Pilih collation `utf8mb4_unicode_ci` jika tersedia.
4. Pastikan nama database sesuai dengan nilai `DB_DATABASE` di file `.env`.

Jangan mengimpor database proyek lain secara sembarang. Gunakan struktur database yang sesuai dengan migration pada repository.

## 7. Generate Application Key

Jalankan:

```bash
php artisan key:generate
```

Perintah ini akan mengisi `APP_KEY` pada file `.env`.

## 8. Migrasi Database

Pastikan service MySQL aktif dan konfigurasi database pada `.env` sudah benar. Kemudian jalankan:

```bash
php artisan migrate
```

Perintah tersebut membuat tabel berdasarkan migration yang tersedia pada proyek.

Jika ingin memeriksa status migration:

```bash
php artisan migrate:status
```

> **Perhatian:** Jangan menjalankan `php artisan migrate:fresh` pada database yang berisi data penting karena perintah tersebut menghapus tabel sebelum membuatnya kembali.

## 9. Instalasi Dependency Frontend

Jalankan:

```bash
npm install
```

Kemudian build asset frontend:

```bash
npm run build
```

Untuk proses pengembangan dengan hot reload, gunakan `npm run dev` pada terminal tersendiri.

## 10. Storage Link dan Cache

Buat symbolic link untuk file storage publik:

```bash
php artisan storage:link
```

Bersihkan cache konfigurasi dan aplikasi:

```bash
php artisan optimize:clear
```

Jika perintah `storage:link` menyatakan link sudah tersedia, periksa terlebih dahulu apakah link tersebut mengarah ke lokasi yang benar.

## 11. Menjalankan Aplikasi

Jalankan server Laravel:

```bash
php artisan serve
```

Buka terminal kedua pada direktori proyek, lalu jalankan Vite:

```bash
npm run dev
```

Akses aplikasi melalui browser:

**http://127.0.0.1:8000**

Biarkan kedua proses terminal tetap berjalan selama pengembangan:
- Terminal 1: Laravel Development Server.
- Terminal 2: Vite Development Server.

Jika hanya ingin menjalankan asset hasil build, jalankan `npm run build` dan server Laravel dapat dijalankan tanpa proses `npm run dev`.

## 12. Login dan Akun Pengguna

Setelah aplikasi terbuka, masuk melalui halaman login.

Akun yang digunakan harus sudah tersedia pada database dan memiliki role yang sesuai. Pastikan data pengguna, role, serta relasinya disiapkan menggunakan mekanisme seeding atau administrasi yang memang tersedia pada versi source code yang digunakan.

Dokumentasi ini tidak mencantumkan username atau password default karena kredensial akun tidak ditetapkan dalam panduan ini. Jangan menggunakan akun contoh dari lingkungan lain tanpa memastikan akun tersebut memang dibuat pada database lokal.

## 13. Troubleshooting

| Kendala | Kemungkinan Penyebab | Solusi |
|---|---|---|
| `php is not recognized` | PHP belum masuk PATH | Tambahkan direktori PHP Laragon/XAMPP ke PATH atau gunakan terminal bawaan. |
| `composer is not recognized` | Composer belum terpasang atau PATH belum benar | Instal Composer dan pastikan executable terdeteksi terminal. |
| `could not find driver` | Ekstensi PDO database belum aktif | Aktifkan `pdo_mysql` pada `php.ini`, lalu restart terminal/web server. |
| `Access denied for user` | Username/password database salah | Periksa `DB_USERNAME` dan `DB_PASSWORD` pada `.env`. |
| `Unknown database` | Database belum dibuat atau namanya berbeda | Buat database dan samakan dengan `DB_DATABASE`. |
| `No application encryption key has been specified` | APP_KEY belum dibuat | Jalankan `php artisan key:generate`. |
| Halaman tidak memuat style | Asset frontend belum dibangun | Jalankan `npm install` lalu `npm run build`, atau gunakan `npm run dev`. |
| Perubahan konfigurasi tidak terbaca | Cache konfigurasi lama | Jalankan `php artisan optimize:clear`. |
| Gambar/dokumen tidak tampil | Storage link belum tersedia | Jalankan `php artisan storage:link` dan periksa permission folder storage. |
| Port 8000 sudah digunakan | Ada proses lain memakai port tersebut | Jalankan `php artisan serve --port=8001`, lalu buka port tersebut. |

## 14. Perintah yang Sering Digunakan

```bash
# Menjalankan server Laravel
php artisan serve

# Menjalankan Vite
npm run dev

# Build asset frontend
npm run build

# Menjalankan migration
php artisan migrate

# Melihat status migration
php artisan migrate:status

# Membersihkan cache
php artisan optimize:clear

# Membuat storage link
php artisan storage:link
```

---

**Repository:** https://github.com/Fbi-Boy/Sim-BangUM

**Dokumentasi utama:** [README.md](README.md)
