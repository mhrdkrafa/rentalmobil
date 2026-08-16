# PRD — Sistem Booking & Rental Mobil

## 1. Ringkasan Produk

**Nama Project:** RentCar (nama sementara, bisa diganti sesuai branding)
**Jenis:** Web application — sistem booking & manajemen rental mobil
**Tujuan:** Menggantikan proses booking manual (telepon/WA/catatan) milik rental mobil dengan sistem online yang otomatis: ketersediaan unit real-time, pembayaran DP, notifikasi WhatsApp, dan dashboard admin untuk kelola armada, driver, dan transaksi.

**Model bisnis produk:** Bisa dijual sebagai:
- Produk jasa (dibangun 1x untuk 1 rental mobil), atau
- SaaS multi-tenant (1 platform dipakai banyak rental mobil dengan subdomain/branding masing-masing) — disiapkan sebagai opsi pengembangan lanjutan, tapi MVP fokus single-tenant dulu.

## 2. Target Pengguna

| Role | Deskripsi |
|---|---|
| **Customer / Penyewa** | Publik yang mencari & booking mobil secara online |
| **Admin/Owner** | Pemilik rental, kelola armada, booking, transaksi, laporan |
| **Staff/Operator** | Karyawan yang menangani konfirmasi booking, check-in/out unit |
| **Driver** (opsional) | Jika rental menyediakan layanan "dengan supir" |

## 3. Masalah yang Diselesaikan

- Pemilik rental sulit memantau jadwal unit yang sedang disewa vs kosong → sering *double booking*
- Transaksi & DP dicatat manual (buku/Excel) → rawan selisih, susah rekap laporan
- Customer harus telepon/chat manual untuk cek ketersediaan mobil
- Tidak ada reminder otomatis (H-1 sewa, jatuh tempo pelunasan, dsb)

## 4. Tujuan Produk (Goals)

1. Customer bisa cek ketersediaan mobil & booking online tanpa kontak manual
2. Admin punya kalender armada real-time — tidak ada lagi double booking
3. Pembayaran DP & pelunasan tercatat otomatis dengan invoice digital
4. Notifikasi WA otomatis untuk booking baru, konfirmasi, reminder, dan pengingat pengembalian
5. Laporan keuangan & okupansi armada bisa diakses admin kapan saja

## 5. Ruang Lingkup (Scope)

### 5.1 In-Scope (MVP)
- Landing page publik (Tailwind + Alpine.js) — katalog mobil, filter, detail unit
- Kalender ketersediaan per unit (date-range based, karena sewa mobil umumnya harian)
- Proses booking: pilih mobil → pilih tanggal → isi data penyewa → pilih dengan/tanpa supir → bayar DP
- Integrasi payment gateway (Midtrans atau Xendit) — VA, QRIS, e-wallet
- Upload bukti transfer manual (fallback untuk transfer langsung)
- Invoice/kwitansi PDF otomatis
- Dashboard admin (AdminLTE 2.3.0) — kelola unit, driver, booking, pembayaran, laporan
- Notifikasi WhatsApp otomatis (via API pihak ketiga: Fonnte/WABlas/Twilio WA)
- Manajemen role (Admin, Staff)
- Riwayat booking & status untuk customer (tanpa harus login — pakai kode booking + verifikasi no HP, atau opsional login)

### 5.2 Out-of-Scope (Fase Selanjutnya)
- Multi-tenant/white-label penuh (subdomain per tenant)
- Aplikasi mobile native
- Sistem GPS tracking kendaraan real-time
- Integrasi asuransi kendaraan otomatis
- Multi-bahasa

## 6. Alur Pengguna Utama (User Flow)

### 6.1 Customer — Booking Mobil
1. Buka landing page → lihat katalog mobil
2. Filter berdasarkan tanggal, kategori (city car, MPV, SUV, dsb), dengan/tanpa supir
3. Klik detail mobil → lihat foto, spesifikasi, harga per hari, ketersediaan di kalender
4. Pilih tanggal mulai & selesai sewa
5. Isi data diri (nama, no HP, email, alamat, upload KTP/SIM)
6. Pilih opsi: sewa lepas kunci / dengan supir
7. Sistem hitung total biaya + DP minimum
8. Bayar DP via payment gateway atau upload bukti transfer
9. Terima notifikasi WA + email berisi kode booking & status "menunggu konfirmasi"
10. Admin konfirmasi → customer dapat notifikasi WA "booking dikonfirmasi"
11. H-1 sebelum sewa → reminder WA otomatis
12. Setelah selesai sewa → customer bisa kasih rating/review

### 6.2 Admin — Kelola Booking
1. Login ke dashboard AdminLTE
2. Lihat notifikasi booking baru di dashboard
3. Cek detail booking (data penyewa, dokumen, tanggal, unit)
4. Konfirmasi atau tolak booking
5. Update status pembayaran (DP diterima / lunas)
6. Assign driver jika sewa dengan supir
7. Update status unit (dipakai → dikembalikan → siap sewa lagi)
8. Lihat laporan pendapatan & okupansi armada

## 7. Fitur Detail

### A. Fitur Publik (Customer-Facing)
- Landing page (hero, keunggulan, testimoni, CTA booking)
- Katalog mobil dengan filter (tanggal, kategori, transmisi, kapasitas, harga)
- Halaman detail mobil (galeri foto, spek, fasilitas, syarat sewa, kalender ketersediaan)
- Form booking multi-step (pilih tanggal → data diri → opsi supir → pembayaran)
- Halaman status booking (cek pakai kode booking + no HP)
- Halaman invoice/kwitansi (bisa didownload PDF)
- Form review & rating setelah sewa selesai

### B. Fitur Admin (AdminLTE Dashboard)
- Dashboard ringkasan: booking hari ini, mobil tersedia/tersewa, pendapatan bulan ini, grafik okupansi
- CRUD armada mobil (foto, spesifikasi, harga/hari, harga dengan supir, status maintenance)
- Kalender master semua booking (per unit, drag untuk lihat detail)
- Manajemen booking (list, detail, konfirmasi, tolak, batalkan, reschedule)
- Manajemen driver (CRUD data driver, assign ke booking, jadwal driver)
- Manajemen pembayaran (tracking DP, pelunasan, riwayat transaksi)
- Manajemen customer (data penyewa, riwayat sewa, blacklist jika perlu)
- Laporan (pendapatan harian/bulanan, okupansi per unit, export Excel/PDF)
- Pengaturan notifikasi WA (template pesan, on/off per jenis notifikasi)
- Manajemen user & role (admin, staff)

### C. Notifikasi WhatsApp (Otomatis)
- Booking baru masuk → ke admin
- Booking dikonfirmasi/ditolak → ke customer
- Pembayaran diterima → ke customer
- Reminder H-1 sebelum sewa → ke customer
- Reminder pengembalian mobil (H-0 jam tertentu) → ke customer
- Reminder pelunasan jika masih ada sisa pembayaran → ke customer

## 8. Kebutuhan Non-Fungsional

- **Performance:** halaman publik load < 2 detik (asset di-bundle via Vite, gambar dioptimasi)
- **Security:** validasi input server-side, CSRF protection (bawaan Laravel), enkripsi password, rate limiting untuk form booking, upload file dibatasi tipe & ukuran
- **Reliability:** kalender ketersediaan harus konsisten — cegah race condition saat 2 customer booking tanggal sama secara bersamaan (pakai database transaction/locking)
- **Usability:** dashboard admin harus bisa dipakai staff non-teknis tanpa training panjang
- **Scalability:** struktur database disiapkan agar mudah dikembangkan ke multi-tenant di masa depan
- **Compatibility:** responsive di mobile untuk halaman publik (mayoritas customer akan akses via HP)

## 9. Tech Stack

| Layer | Teknologi |
|---|---|
| Backend Framework | Laravel 12 |
| Database | MySQL |
| Admin Dashboard UI | AdminLTE v2.3.0 |
| Public Frontend UI | Tailwind CSS + Alpine.js |
| Build Tool | Vite |
| Payment Gateway | Midtrans / Xendit (pilih salah satu di awal) |
| WhatsApp API | Fonnte / WABlas / Twilio WhatsApp API |
| PDF Generator | barryvdh/laravel-dompdf atau spatie/laravel-pdf |
| Auth | Laravel Breeze/Fortify (untuk admin), guest checkout untuk customer |
| Queue/Job | Laravel Queue (database/redis driver) untuk kirim notifikasi WA & email async |
| File Storage | Laravel Storage (local/S3-compatible) untuk foto mobil & dokumen |

## 10. Metrik Keberhasilan (Success Metrics)

- Jumlah booking online per bulan meningkat dibanding metode manual
- Rasio double-booking = 0
- Waktu konfirmasi booking oleh admin < 1 jam (berkat notifikasi WA real-time)
- Okupansi armada terpantau > 80% termonitor akurat di laporan
- Tingkat komplain terkait kesalahan jadwal turun signifikan

## 11. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Race condition saat booking tanggal sama | Gunakan DB transaction + unique constraint pada rentang tanggal per unit |
| API WA pihak ketiga down | Fallback notifikasi via email, queue retry otomatis |
| Customer tidak bayar DP setelah booking | Auto-cancel booking pending setelah batas waktu tertentu (misal 1x24 jam) via scheduled job |
| Data dokumen (KTP/SIM) sensitif | Simpan di storage privat, akses terbatas role admin, pertimbangkan enkripsi |

## 12. Rencana Fase Pengembangan

- **Fase 1 (MVP):** Fitur di atas, single-tenant, 1 metode payment gateway
- **Fase 2:** Multi-driver scheduling lebih detail, integrasi GPS/tracking dasar, aplikasi mobile (opsional)
- **Fase 3:** Multi-tenant/white-label untuk dijual ke banyak rental mobil
