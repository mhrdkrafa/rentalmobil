# AGENTS.md — Panduan untuk AI Coding Agent

Dokumen ini menjadi acuan bagi AI coding agent (Claude Code atau sejenisnya) saat bekerja di repository ini. Baca `PRD.md` dan `DATABASE.md` terlebih dahulu sebelum mengerjakan task apa pun dari `TASKS.md`.

## 1. Ringkasan Project

Sistem booking & rental mobil dengan dua wajah UI:
- **Area publik** (customer-facing): Tailwind CSS + Alpine.js, di-bundle via Vite
- **Area admin** (dashboard): AdminLTE v2.3.0 (Bootstrap-based)
- **Backend**: Laravel 12 + MySQL

## 2. Tech Stack & Versi

| Komponen | Versi/Detail |
|---|---|
| PHP | ^8.3 |
| Laravel | 12.x |
| Database | MySQL 8.x |
| Node | ^20.x |
| Build tool | Vite |
| Admin UI | AdminLTE v2.3.0 (integrasikan sebagai asset, bukan lewat package Node modern — AdminLTE 2.3.0 berbasis Bootstrap 3/jQuery) |
| Public UI | Tailwind CSS v3/v4 + Alpine.js v3 |
| Payment | Midtrans PHP SDK atau Xendit PHP SDK |
| WhatsApp API | Fonnte/WABlas/Twilio (HTTP client, bukan SDK khusus) |
| PDF | barryvdh/laravel-dompdf atau spatie/laravel-pdf |
| Testing | Pest atau PHPUnit (bawaan Laravel 12) |

## 3. Struktur Folder (Target)

```
app/
  Http/
    Controllers/
      Admin/          -- controller untuk area dashboard AdminLTE
      Public/          -- controller untuk area publik (landing, booking flow)
      Api/             -- (opsional) endpoint API, webhook payment gateway
    Middleware/
    Requests/
      Admin/
      Public/
  Models/
  Services/            -- business logic: BookingService, PaymentService, NotificationService, AvailabilityService
  Jobs/                -- kirim notifikasi WA/email secara async
  Notifications/
  Enums/                -- BookingStatus, PaymentStatus, VehicleStatus, dll (pakai native PHP enum)

resources/
  views/
    admin/              -- Blade views AdminLTE (layout, partials, per-modul)
    public/             -- Blade views Tailwind + Alpine (landing, katalog, booking flow)
    components/         -- Blade components reusable
    emails/
    pdf/                -- template invoice PDF
  css/
    admin.css           -- entry AdminLTE (import css AdminLTE + custom override)
    app.css              -- entry Tailwind
  js/
    admin.js             -- entry JS AdminLTE (jQuery, plugin AdminLTE)
    app.js                -- entry JS publik (Alpine.js init, komponen booking calendar dll)

database/
  migrations/
  seeders/
  factories/

routes/
  web.php               -- route publik
  admin.php              -- route admin (di-load dengan prefix /admin & middleware auth+role)
  api.php                -- webhook payment gateway, dsb

vite.config.js           -- multi-entry: admin.css/js dan app.css/js terpisah
```

## 4. Konvensi Penamaan & Kode

- **Model**: singular, PascalCase → `Vehicle`, `Booking`, `Customer`
- **Controller**: PascalCase + suffix `Controller` → `BookingController`, dipisah namespace `Admin\` dan `Public\`
- **Migration**: gunakan nama tabel plural snake_case, ikuti urutan dependency FK (buat tabel induk dulu: `vehicle_categories` → `vehicles` → `bookings`)
- **Status/Enum**: gunakan native PHP 8.1+ enum class di `app/Enums/`, jangan hardcode string status di controller
- **Service Layer**: logika bisnis kompleks (cek ketersediaan, hitung total harga, proses booking) **wajib** ditaruh di `app/Services/`, bukan langsung di controller — memudahkan testing dan reuse antara area publik & admin
- **Form Request**: semua validasi input pakai `FormRequest` class, jangan validasi inline di controller
- **Blade Component**: elemen UI berulang (card mobil, badge status booking, kalender) dibuat sebagai Blade component agar konsisten antara admin & publik jika relevan

## 5. Aturan Khusus Domain

1. **Cek ketersediaan unit HARUS pakai DB transaction + row locking** (`DB::transaction()` + `lockForUpdate()`) saat proses create booking, untuk mencegah race condition double booking. Lihat contoh query di `DATABASE.md` §4.
2. **Snapshot harga**: kolom `price_per_day` di tabel `bookings` adalah snapshot saat booking dibuat — jangan pernah ambil harga langsung dari `vehicles.price_per_day` untuk booking yang sudah ada.
3. **Auto-cancel booking pending**: buat scheduled job (Laravel Task Scheduling) yang membatalkan booking berstatus `pending` tanpa pembayaran setelah durasi tertentu (default 24 jam, harus configurable via tabel `settings`).
4. **Notifikasi WA dikirim via Queue Job**, jangan sync langsung di controller — supaya request booking tidak lambat menunggu response API pihak ketiga.
5. **Dokumen customer (KTP/SIM)** disimpan di disk **privat** (`storage/app/private` atau setara), akses hanya lewat route ter-otorisasi untuk role admin — jangan pernah expose lewat `public` disk.
6. **Booking code** di-generate unik dengan format `RC-YYYYMMDD-XXXX`, generate di Service layer, bukan di observer/model event agar mudah ditest.

## 6. Setup & Perintah Umum

```bash
# Install dependency
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate --seed

# Development (jalankan bersamaan)
php artisan serve
npm run dev

# Build production
npm run build

# Testing
php artisan test
# atau jika pakai Pest
./vendor/bin/pest

# Code style
./vendor/bin/pint
```

## 7. Vite Multi-Entry Setup (Catatan Penting)

AdminLTE 2.3.0 bergantung pada jQuery + Bootstrap 3, sedangkan area publik pakai Tailwind + Alpine.js — **jangan digabung dalam satu entry point** agar tidak terjadi konflik CSS/JS (Bootstrap 3 vs Tailwind reset). Gunakan konfigurasi Vite multi-entry:

```js
// vite.config.js
export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/admin.css',
        'resources/js/admin.js',
        'resources/css/app.css',
        'resources/js/app.js',
      ],
      refresh: true,
    }),
  ],
});
```

Layout admin (`layouts/admin.blade.php`) hanya me-load `admin.css`/`admin.js`. Layout publik (`layouts/public.blade.php`) hanya me-load `app.css`/`app.js`. Jangan pernah load keduanya di halaman yang sama.

## 8. Testing Checklist Sebelum PR/Selesai Task

- [ ] Migration bisa `migrate:fresh --seed` tanpa error
- [ ] Tidak ada query N+1 di halaman list (cek pakai `debugbar` saat development)
- [ ] Validasi form sudah mengembalikan pesan error dalam Bahasa Indonesia yang jelas
- [ ] Fitur yang menyentuh tanggal booking sudah ditest untuk kasus overlap/double booking
- [ ] Notifikasi WA/email di-mock saat testing (jangan hit API asli di test suite)
- [ ] Halaman publik responsive di viewport mobile (< 640px)
- [ ] Role & middleware admin sudah dicek (staff tidak bisa akses fitur khusus super_admin jika ada pembatasan)

## 9. Hal yang Harus Dihindari Agent

- Jangan hardcode kredensial API (Midtrans/Xendit/WA) — selalu lewat `.env` dan `config/services.php`
- Jangan taruh logika bisnis di Blade view
- Jangan gunakan query mentah tanpa parameter binding (hindari SQL injection)
- Jangan mengubah struktur tabel di `DATABASE.md` tanpa mengupdate dokumen ini juga
- Jangan install package Node tambahan yang berat tanpa alasan jelas — target performa halaman publik harus tetap ringan

## 10. Referensi Dokumen Lain

- `PRD.md` — kebutuhan produk & fitur lengkap
- `DATABASE.md` — skema database & relasi
- `TASKS.md` — breakdown pekerjaan per fase, gunakan ini sebagai sumber task yang dikerjakan berurutan
