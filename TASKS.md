# TASKS.md — Breakdown Pekerjaan Project

Urutan dikerjakan top-down per fase. Setiap task idealnya jadi 1 PR/commit unit kerja yang bisa diverifikasi dengan checklist di `AGENTS.md` §8.

## Fase 0 — Setup Project

- [ ] Init project Laravel 12 (`composer create-project laravel/laravel`)
- [ ] Setup MySQL database & `.env`
- [ ] Install & konfigurasi Vite multi-entry (admin.css/js, app.css/js) — lihat `AGENTS.md` §7
- [ ] Integrasikan asset AdminLTE v2.3.0 ke `resources/` (copy dist AdminLTE, sesuaikan path via Vite)
- [ ] Install & konfigurasi Tailwind CSS di `resources/css/app.css`
- [ ] Install Alpine.js di `resources/js/app.js`
- [ ] Setup Laravel Breeze/Fortify untuk auth admin (khusus role internal)
- [ ] Buat struktur folder sesuai `AGENTS.md` §3
- [ ] Setup Pint (code style) & Pest/PHPUnit (testing)
- [ ] Setup Git repo, `.gitignore`, README awal

## Fase 1 — Database & Model Foundation

- [ ] Buat migration `vehicle_categories`
- [ ] Buat migration `vehicles`
- [ ] Buat migration `vehicle_images`
- [ ] Buat migration `vehicle_blackout_dates`
- [ ] Buat migration `drivers`
- [ ] Buat migration `customers`
- [ ] Buat migration `bookings` (termasuk index untuk `vehicle_id, start_date, end_date`)
- [ ] Buat migration `booking_documents`
- [ ] Buat migration `payments`
- [ ] Buat migration `reviews`
- [ ] Buat migration `notification_logs`
- [ ] Buat migration `settings`
- [ ] Buat Eloquent Model + relasi untuk semua tabel di atas
- [ ] Buat Enum class: `BookingStatus`, `PaymentStatus`, `VehicleStatus`, `PaymentMethod`
- [ ] Buat Factory & Seeder (data dummy: kategori, mobil, driver, beberapa booking contoh)
- [ ] Verifikasi `migrate:fresh --seed` berjalan tanpa error

## Fase 2 — Admin: Autentikasi & Layout Dasar

- [ ] Setup login page admin dengan AdminLTE layout
- [ ] Buat middleware role (`super_admin`, `admin`, `staff`)
- [ ] Buat layout master AdminLTE (`layouts/admin.blade.php`) — sidebar, navbar, footer
- [ ] Buat komponen navigasi sidebar sesuai modul (Dashboard, Armada, Booking, Driver, Pembayaran, Customer, Laporan, Pengaturan)
- [ ] Buat halaman Dashboard kosong (placeholder widget)

## Fase 3 — Modul Admin: Manajemen Armada

- [ ] CRUD `vehicle_categories`
- [ ] CRUD `vehicles` (form + upload multi-foto)
- [ ] Halaman kelola `vehicle_images` (set primary image, hapus, reorder)
- [ ] Halaman kelola `vehicle_blackout_dates` (tandai tanggal maintenance)
- [ ] List armada dengan filter status (available/rented/maintenance/inactive)
- [ ] CRUD `drivers`

## Fase 4 — Service Layer: Ketersediaan & Booking

- [ ] Buat `AvailabilityService` — cek ketersediaan unit di rentang tanggal (query dari `bookings` + `vehicle_blackout_dates`, pakai locking)
- [ ] Buat `BookingService` — proses create booking (hitung total harga, generate booking_code, snapshot harga, set status awal)
- [ ] Unit test: skenario 2 booking overlap tanggal sama → harus gagal salah satu
- [ ] Unit test: perhitungan total harga (total_days × price_per_day, dengan/tanpa supir)
- [ ] Buat scheduled job auto-cancel booking `pending` yang lewat batas waktu bayar DP

## Fase 5 — Publik: Landing Page & Katalog

- [ ] Buat layout publik (`layouts/public.blade.php`) dengan Tailwind
- [ ] Halaman landing (hero, keunggulan, CTA)
- [ ] Halaman katalog mobil (list + filter tanggal, kategori, transmisi, kapasitas, harga)
- [ ] Halaman detail mobil (galeri foto, spesifikasi, fasilitas)
- [ ] Komponen kalender ketersediaan di halaman detail (Alpine.js, ambil data availability via endpoint)
- [ ] Endpoint AJAX/API internal untuk cek ketersediaan per unit per rentang tanggal

## Fase 6 — Publik: Alur Booking

- [ ] Form booking multi-step (Alpine.js untuk step wizard: tanggal → data diri → opsi supir → ringkasan)
- [ ] Validasi form (Form Request) — data diri, tanggal valid, upload KTP/SIM
- [ ] Simpan booking via `BookingService`
- [ ] Halaman ringkasan/konfirmasi booking + kode booking
- [ ] Halaman cek status booking (input kode booking + no HP)

## Fase 7 — Pembayaran

- [ ] Pilih & konfigurasi payment gateway (Midtrans atau Xendit) di `config/services.php`
- [ ] Buat `PaymentService` — generate transaksi ke gateway, terima callback/webhook
- [ ] Route & controller webhook payment gateway (update status booking otomatis saat pembayaran sukses)
- [ ] Alur pembayaran manual (upload bukti transfer) sebagai fallback
- [ ] Halaman verifikasi pembayaran manual di admin (approve/reject bukti transfer)
- [ ] Generate invoice/kwitansi PDF (dompdf/spatie-pdf) setelah pembayaran terverifikasi
- [ ] Halaman download invoice untuk customer

## Fase 8 — Modul Admin: Manajemen Booking

- [ ] List semua booking dengan filter status, tanggal, unit
- [ ] Detail booking (data penyewa, dokumen, riwayat pembayaran)
- [ ] Aksi konfirmasi/tolak booking
- [ ] Assign driver ke booking (jika with_driver = true)
- [ ] Update status booking (confirmed → ongoing → completed) + update status unit otomatis
- [ ] Kalender master booking (tampilan kalender semua unit, bisa pakai plugin AdminLTE — FullCalendar)
- [ ] Fitur reschedule/cancel booking oleh admin

## Fase 9 — Notifikasi WhatsApp

- [ ] Setup integrasi API WA (Fonnte/WABlas/Twilio) di `config/services.php`
- [ ] Buat `NotificationService` + Job untuk kirim WA async
- [ ] Template pesan: booking baru (ke admin), konfirmasi booking (ke customer), pembayaran diterima, reminder H-1, reminder pengembalian
- [ ] Halaman admin untuk kelola template pesan (tabel `settings`)
- [ ] Logging semua notifikasi ke `notification_logs`
- [ ] Fallback kirim email jika WA gagal

## Fase 10 — Customer, Review & Laporan
- [x] **Customer Management**:
  - [x] CRUD Customer sederhana di Admin.
  - [x] Fitur blacklist customer nakal.
- [x] **Sistem Review**:
  - [x] Form review di halaman publik setelah booking `completed`.
  - [x] Admin bisa hide/publish review.
  - [x] Tampilkan review di halaman detail mobil.
- [x] **Laporan**:
  - [x] Update Dashboard dengan grafik penyewaan & pendapatan (Chart.js).
  - [x] Halaman Laporan Transaksi (filter tanggal, export PDF).

## Fase 11 — Polish, Testing, Deployment

- [ ] Review keamanan: rate limiting form booking, validasi upload file, cek middleware role di semua route admin
- [ ] Optimasi query (cek N+1, tambah index yang kurang)
- [ ] Responsive check halaman publik di berbagai device
- [ ] Setup queue worker (supervisor) untuk production
- [ ] Setup scheduled task (cron) untuk auto-cancel booking & reminder
- [ ] Siapkan environment production (`.env` production, storage link, `npm run build`)
- [ ] Dokumentasi deployment (README deployment steps)
- [ ] UAT (User Acceptance Test) bersama calon pemilik rental mobil

## Fase 12 (Opsional/Lanjutan) — Persiapan Multi-Tenant

- [ ] Riset penambahan kolom `tenant_id` di tabel-tabel utama (lihat `DATABASE.md` §5)
- [ ] Riset arsitektur subdomain per tenant di Laravel
- [ ] Rancang sistem billing/langganan untuk tenant
