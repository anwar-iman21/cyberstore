# Panduan Setup — Website E-Commerce CyberStore (Full)

Paket ini berisi **seluruh kode aplikasi** untuk website e-commerce cyber/security
sesuai README yang bro berikan: katalog produk, cart, checkout, order flow
lengkap (pending → approved → waiting_payment → payment_confirmed →
processing → completed), konfirmasi pembayaran via WhatsApp, invoice, admin
dashboard, CRUD kategori & produk (dengan upload foto), manajemen order &
pelanggan, dan settings.

Karena proses ini berjalan di sandbox tanpa akses ke Packagist, skeleton
Laravel + Breeze (vendor/, dependency PHP) harus digenerate sekali di
komputer bro. Setelah itu, semua file di paket ini tinggal disalin masuk dan
aplikasinya langsung utuh — tidak perlu coding tambahan untuk fitur-fitur
yang sudah dirinci di README.

## 1. Buat project Laravel + install Breeze

```bash
composer create-project "laravel/laravel:^12.0" nama-project
cd nama-project

> Penting: pakai `^12.0`, JANGAN tanpa version lock. Per Maret 2026, Laravel 13
> sudah rilis dan jadi default — tapi Laravel 13 butuh PHP 8.3+. Kalau PHP
> kamu masih 8.2, Laravel 12 adalah versi terbaru yang cocok. Jangan juga pakai
> `^10.0` — versi itu kena security advisory dan diblokir Composer.

composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build
```

Kalau step Breeze ini error di komputer bro, kabari saya — saya siapkan
alternatif auth manual tanpa Breeze.

## 2. Setup `.env` & database

```
DB_CONNECTION=mysql
DB_DATABASE=nama_database_kamu
DB_USERNAME=root
DB_PASSWORD=
```

Buat database-nya dulu di MySQL.

## 3. Salin seluruh file dari paket ini ke project, dengan aturan:

**TIMPA (overwrite) total** file-file berikut dari hasil generate Breeze:
```
routes/web.php
app/Models/User.php
app/Http/Controllers/ProfileController.php
database/seeders/DatabaseSeeder.php
```

**TAMBAHKAN** (file baru, folder sudah sesuai struktur Laravel):
```
database/migrations/2026_09_09_00000*.php   (11 file — termasuk payment_status & order_status_histories)
database/seeders/AdminUserSeeder.php
database/seeders/CategorySeeder.php

app/Models/Category.php
app/Models/Product.php
app/Models/Cart.php
app/Models/CartItem.php
app/Models/Order.php
app/Models/OrderItem.php
app/Models/PaymentConfirmation.php
app/Models/Setting.php
app/Models/OrderStatusHistory.php

app/Http/Middleware/EnsureUserIsAdmin.php
app/Http/Controllers/ProductController.php
app/Http/Controllers/CartController.php
app/Http/Controllers/CheckoutController.php
app/Http/Controllers/OrderController.php
app/Http/Controllers/InvoiceController.php
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/CategoryController.php
app/Http/Controllers/Admin/ProductController.php
app/Http/Controllers/Admin/OrderController.php
app/Http/Controllers/Admin/CustomerController.php
app/Http/Controllers/Admin/SettingController.php

app/Policies/OrderPolicy.php

resources/views/layouts/shop.blade.php
resources/views/layouts/admin.blade.php
resources/views/admin/partials/sidebar-content.blade.php
resources/views/partials/order-timeline.blade.php
resources/views/profile/edit.blade.php   (TIMPA versi bawaan Breeze)
resources/views/products/*.blade.php
resources/views/cart/*.blade.php
resources/views/checkout/*.blade.php
resources/views/orders/*.blade.php
resources/views/invoices/*.blade.php
resources/views/admin/dashboard.blade.php
resources/views/admin/categories/*.blade.php
resources/views/admin/products/*.blade.php
resources/views/admin/orders/*.blade.php
resources/views/admin/customers/*.blade.php
resources/views/admin/settings/*.blade.php
```

> `resources/views/dashboard.blade.php` bawaan Breeze boleh tetap ada/dihapus
> — tidak dipakai lagi karena diganti alur katalog produk (`/`) untuk semua
> user dan `/admin/dashboard` khusus admin.

## 4. Daftarkan middleware `admin` — `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
    ]);
})
```

## 5. Storage link (untuk foto produk)

```bash
php artisan storage:link
```

## 6. Migrate & seed

```bash
php artisan migrate
php artisan db:seed
```

Akun admin awal: `admin@tokocyber.test` / `password` — **wajib diganti**
setelah login pertama (lewat halaman Profil → Ubah Password).

## 7. Jalankan

```bash
php artisan serve
npm run dev   # di terminal terpisah, untuk hot-reload Tailwind/Alpine
```

Buka `http://localhost:8000`.

Isi dulu **Admin → Pengaturan** dengan nomor WhatsApp asli dan info
pembayaran, supaya tombol "Konfirmasi Pembayaran via WhatsApp" dan halaman
instruksi pembayaran customer terisi benar.

---

## Ringkasan alur yang sudah jadi

- **Customer**: register/login (Breeze) → browse & cari produk → tambah ke
  cart (harga dihitung server-side) → checkout → order dibuat dengan nomor
  unik `ORD-YYYYMMDD-00001` dan status `pending` → menunggu admin.
- **Admin**: approve/reject order (reject wajib alasan opsional yang
  tersimpan di histori) → kalau approved, status jadi `waiting_payment` dan
  customer melihat instruksi pembayaran manual → customer bayar manual lalu
  klik "Konfirmasi via WhatsApp" (buka wa.me dengan pesan otomatis,
  payment_status otomatis jadi `waiting_confirmation`) → admin verifikasi
  pembayaran manual → status order `payment_confirmed` (payment_status ikut
  `paid`) → `processing` → `completed`.
- **Order status vs Payment status dipisah total** (README bagian U): order
  status ada di tabel `orders`, payment status ada di tabel
  `payment_confirmations` (field `payment_status`: unpaid / waiting_confirmation
  / paid / rejected) dan admin bisa mengubah keduanya secara independen dari
  halaman detail order.
- **Order status history** (README bagian T): setiap perubahan status order
  tercatat di tabel `order_status_histories` beserta siapa yang mengubah dan
  catatan/alasannya, ditampilkan sebagai riwayat + **timeline visual**
  (✓ selesai / ● sedang berjalan / ○ belum) di halaman order admin maupun
  customer. Kalau order ditolak/dibatalkan, timeline diganti tampilan
  "✕ Pesanan Ditolak" beserta alasannya.
- **Keamanan**: semua transisi status divalidasi backend
  (`Order::ALLOWED_TRANSITIONS`), customer tidak bisa lihat order milik
  customer lain (`OrderPolicy`), harga selalu dihitung ulang dari database,
  produk yang sudah punya histori order tidak pernah dihapus permanen
  (hanya dinonaktifkan) supaya histori transaksi tetap utuh.
- **Admin panel**: dashboard 8 statistik (produk, pelanggan, total order,
  total revenue, pending, waiting payment, processing, completed), CRUD
  kategori, CRUD produk (upload/ganti/hapus foto), manajemen order dengan
  kolom Payment Status & Order Status terpisah, daftar pelanggan, dan
  Settings lengkap (nama, **logo**, deskripsi, WhatsApp, email, info
  pembayaran, info invoice).
- **Invoice**: menampilkan logo & nama perusahaan dari Settings, serta
  status order dan status pembayaran secara terpisah.
- **Responsive**: sidebar admin tetap terlihat di desktop, jadi drawer mobile
  di layar kecil (README bagian Z).

## Yang belum dibuatkan (belum diminta di README, bilang saja kalau perlu)

- Halaman landing/marketing terpisah dari katalog (saat ini `/` langsung
  menampilkan katalog produk)
- Notifikasi email
- Export laporan (misal order ke Excel/PDF)
- Testing otomatis (PHPUnit/Pest) — README bagian AF adalah testing manual,
  bisa saya buatkan test otomatis kalau bro mau

## Kalau ada error setelah setup

Karena saya tidak bisa menjalankan project ini langsung di sisi saya (tidak
ada akses Packagist di sandbox), kemungkinan ada typo kecil atau perbedaan
versi Laravel yang baru ketahuan saat pertama kali dijalankan. Kalau ada
error, **tempel pesan errornya ke saya** (lengkap dengan file & baris kalau
ada) — saya perbaiki langsung, bukan menebak-nebak.
