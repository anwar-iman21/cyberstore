# 🛒 Cyberstore

**Cyberstore** adalah aplikasi toko online berbasis Laravel untuk menjual produk digital di bidang keamanan siber. Aplikasi ini menyediakan katalog produk untuk pelanggan dan panel administrasi untuk mengelola produk, pesanan, pembayaran, pelanggan, serta informasi toko.

> 🇮🇩 Dokumentasi ini ditulis dalam Bahasa Indonesia. Isi README berupa teks, tabel, dan ikon—tanpa foto atau screenshot.

## 📚 Daftar Isi

- [✨ Fitur](#-fitur)
- [🧰 Teknologi](#-teknologi)
- [✅ Persyaratan](#-persyaratan)
- [🚀 Instalasi](#-instalasi)
- [⚙️ Konfigurasi](#️-konfigurasi)
- [🧑‍💻 Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [🛍️ Alur Penggunaan](#️-alur-penggunaan)
- [🔐 Akun Admin Lokal](#-akun-admin-lokal)
- [🗂️ Struktur Direktori](#️-struktur-direktori)
- [🧪 Pengujian](#-pengujian)
- [🛠️ Perintah Berguna](#️-perintah-berguna)
- [🔒 Catatan Keamanan](#-catatan-keamanan)
- [📄 Lisensi](#-lisensi)

## ✨ Fitur

### 🛍️ Toko untuk pelanggan

- 🧭 Menjelajahi katalog produk aktif dan produk unggulan.
- 🔎 Mencari produk berdasarkan nama dan menyaring berdasarkan kategori.
- 📄 Melihat halaman detail produk.
- 👤 Membuat akun, masuk, mengelola profil, dan memperbarui kata sandi.
- 🛒 Menambahkan produk ke keranjang serta memperbarui atau menghapus item.
- 🧾 Membuat pesanan melalui checkout setelah masuk.
- 📦 Melihat daftar pesanan, detail, dan riwayat status pesanan.
- 💳 Melihat informasi pembayaran dan mengajukan konfirmasi pembayaran melalui WhatsApp.
- 🧮 Membuka invoice untuk pesanan.

### 🧑‍💼 Panel admin

- 📊 Melihat dashboard administrasi.
- 🏷️ Membuat, mengubah, dan mengelola kategori.
- 📦 Membuat, mengubah, dan mengelola produk, termasuk status aktif dan unggulan.
- 🧾 Melihat pesanan, detail pesanan, dan informasi pelanggan.
- 🔄 Memperbarui status pesanan mengikuti transisi yang diizinkan aplikasi.
- ✅ Memperbarui status pembayaran secara terpisah dari status pesanan.
- 👥 Melihat daftar pelanggan.
- ⚙️ Mengatur identitas toko, nomor WhatsApp, email, informasi pembayaran, dan informasi invoice.

### 🔁 Status pesanan

Alur status pesanan yang diterapkan aplikasi:

| Status | Arti |
| --- | --- |
| `pending` | Pesanan baru menunggu pemeriksaan admin. |
| `approved` | Pesanan disetujui admin. |
| `rejected` | Pesanan ditolak. |
| `waiting_payment` | Pesanan menunggu pembayaran pelanggan. |
| `payment_confirmed` | Pembayaran dikonfirmasi admin. |
| `processing` | Pesanan sedang diproses. |
| `completed` | Pesanan selesai. |
| `cancelled` | Pesanan dibatalkan. |

🧾 Perubahan status pesanan dicatat dalam riwayat. Status pembayaran disimpan dan dikelola terpisah, dengan nilai `unpaid`, `waiting_confirmation`, `paid`, atau `rejected`.

## 🧰 Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | PHP 8.2+ dan Laravel 12 |
| Autentikasi | Laravel Breeze |
| Database bawaan | SQLite |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Asset bundler | Vite |
| Pengujian | PHPUnit melalui Laravel |

## ✅ Persyaratan

Pastikan perangkat sudah memiliki:

- 🐘 PHP **8.2 atau lebih baru** dengan ekstensi yang dibutuhkan Laravel.
- 📦 Composer.
- 🟩 Node.js dan npm.
- 🗃️ SQLite (untuk konfigurasi database bawaan), atau server database lain yang didukung Laravel.
- 🌐 Browser modern.

Periksa instalasi melalui PowerShell:

```powershell
php -v
composer --version
node --version
npm --version
```

## 🚀 Instalasi

Jalankan perintah berikut dari folder project.

### 1. Pasang dependency PHP

```powershell
composer install
```

### 2. Buat file environment

```powershell
Copy-Item .env.example .env
```

Jika file `.env` sudah tersedia, jangan menimpanya; gunakan konfigurasi yang sudah ada.

### 3. Buat application key

```powershell
php artisan key:generate
```

### 4. Siapkan database SQLite

Konfigurasi bawaan memakai SQLite. Buat file database jika belum ada:

```powershell
if (-not (Test-Path "database\database.sqlite")) {
    New-Item -ItemType File -Path "database\database.sqlite" | Out-Null
}
```

Jalankan migrasi dan data awal:

```powershell
php artisan migrate --seed
```

Seeder membuat akun admin lokal dan beberapa kategori contoh. Lihat [Akun Admin Lokal](#-akun-admin-lokal) sebelum memakai data seed tersebut.

### 5. Pasang dependency frontend dan build asset

```powershell
npm install
npm run build
```

### 6. Siapkan penyimpanan file publik

```powershell
php artisan storage:link
```

Perintah ini diperlukan agar file yang disimpan pada disk publik—misalnya gambar produk atau logo toko—dapat diakses melalui aplikasi.

## ⚙️ Konfigurasi

Salin `.env.example` menjadi `.env`, lalu sesuaikan nilainya dengan lingkungan lokal:

| Variabel | Kegunaan |
| --- | --- |
| `APP_NAME` | Nama aplikasi. |
| `APP_ENV` | Lingkungan aplikasi, misalnya `local` atau `production`. |
| `APP_DEBUG` | Aktifkan detail debug hanya di lingkungan lokal. |
| `APP_URL` | URL aplikasi, misalnya `http://127.0.0.1:8000`. |
| `DB_CONNECTION` | Driver database; bawaan project adalah `sqlite`. |
| `DB_DATABASE` | Lokasi file database jika menggunakan SQLite atau nama database untuk driver lain. |
| `MAIL_MAILER` | Driver email. Bawaan `log`, sehingga email tidak dikirim ke layanan email sungguhan. |
| `QUEUE_CONNECTION` | Driver antrean. Bawaan `database`. |
| `FILESYSTEM_DISK` | Disk penyimpanan file. Bawaan `local`. |

Setelah mengubah konfigurasi, bersihkan cache konfigurasi jika aplikasi masih menggunakan nilai lama:

```powershell
php artisan config:clear
```

### 🗄️ Menggunakan MySQL

Jika ingin menggunakan MySQL, buat database terlebih dahulu, lalu sesuaikan `.env`, misalnya:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cyberstore
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi setelah konfigurasi database diperbarui:

```powershell
php artisan config:clear
php artisan migrate --seed
```

## 🧑‍💻 Menjalankan Aplikasi

### Opsi praktis: jalankan proses development bersama-sama

Composer script `dev` menjalankan server Laravel, worker queue, log viewer, dan Vite:

```powershell
composer run dev
```

Buka URL yang ditampilkan server Laravel, umumnya `http://127.0.0.1:8000`.

### Opsi manual: jalankan server dan Vite terpisah

Terminal pertama:

```powershell
php artisan serve
```

Terminal kedua:

```powershell
npm run dev
```

Untuk membuat asset production:

```powershell
npm run build
```

## 🛍️ Alur Penggunaan

1. 👀 Pengunjung membuka halaman utama untuk melihat dan mencari produk.
2. 🔐 Pelanggan masuk atau mendaftar untuk menggunakan keranjang dan checkout.
3. 🛒 Pelanggan menambahkan produk ke keranjang, mengatur jumlah, lalu checkout.
4. 🧾 Aplikasi membuat pesanan dan menyimpan rincian item serta total berdasarkan harga produk saat checkout.
5. 🧑‍💼 Admin memeriksa pesanan dan memperbarui status melalui panel `/admin`.
6. 💳 Setelah pesanan menunggu pembayaran, pelanggan dapat membuka instruksi pembayaran dan meneruskan konfirmasi melalui WhatsApp.
7. ✅ Admin memverifikasi pembayaran dan memperbarui statusnya.
8. 📦 Pelanggan memantau progres pesanan dan membuka invoice dari halaman pesanan.

## 🔐 Akun Admin Lokal

Seeder lokal menyediakan akun awal berikut:

| Field | Nilai |
| --- | --- |
| Email | `admin@tokocyber.test` |
| Password awal | `password` |

⚠️ Kredensial ini hanya untuk pengembangan lokal. Ganti password segera setelah login pertama kali dan **jangan gunakan kredensial bawaan ini di server publik**. Seeder dapat dijalankan kembali dengan:

```powershell
php artisan db:seed
```

## 🗂️ Struktur Direktori

| Lokasi | Isi |
| --- | --- |
| `app/Http/Controllers/` | Controller toko, checkout, pesanan, invoice, dan admin. |
| `app/Models/` | Model pengguna, produk, kategori, keranjang, pesanan, dan pembayaran. |
| `app/Http/Middleware/` | Middleware otorisasi, termasuk pemeriksaan akses admin. |
| `database/migrations/` | Definisi skema database. |
| `database/seeders/` | Data awal akun admin dan kategori contoh. |
| `resources/views/` | Halaman Blade untuk toko, akun, pesanan, invoice, dan admin. |
| `resources/css/` dan `resources/js/` | Sumber asset frontend. |
| `routes/web.php` | Rute halaman toko dan panel admin. |
| `routes/auth.php` | Rute autentikasi. |
| `tests/` | Pengujian aplikasi. |

## 🧪 Pengujian

Jalankan seluruh pengujian Laravel:

```powershell
php artisan test
```

Atau gunakan Composer script:

```powershell
composer run test
```

## 🛠️ Perintah Berguna

| Perintah | Kegunaan |
| --- | --- |
| `php artisan route:list` | Menampilkan daftar rute aplikasi. |
| `php artisan migrate:status` | Memeriksa status migrasi database. |
| `php artisan migrate:fresh --seed` | Menghapus dan membuat ulang seluruh tabel, lalu mengisi data awal. **Semua data lama akan terhapus.** |
| `php artisan storage:link` | Membuat symbolic link ke penyimpanan publik. |
| `php artisan optimize:clear` | Membersihkan cache Laravel. |
| `npm run dev` | Menjalankan Vite dalam mode development. |
| `npm run build` | Membuat asset frontend untuk production. |

## 🔒 Catatan Keamanan

- 🔑 Jangan commit file `.env`, kredensial, application key, atau data sensitif.
- 🔒 Gunakan password kuat dan ubah password admin awal sebelum aplikasi dipublikasikan.
- 🐞 Pastikan `APP_DEBUG=false` di production.
- 💳 Pembayaran yang tersedia di project ini adalah **transfer manual dengan konfirmasi melalui WhatsApp**; integrasi payment gateway otomatis tidak termasuk.
- 📬 `MAIL_MAILER=log` adalah konfigurasi pengembangan dan tidak mengirim email sungguhan.
- 🧪 Gunakan database dan kredensial terpisah untuk pengujian dan production.

## 📄 Lisensi

Project ini menggunakan lisensi **MIT**, mengikuti metadata pada `composer.json`. Periksa file lisensi project jika tersedia untuk ketentuan lengkap.
