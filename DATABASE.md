# DATABASE.md — Skema Database Sistem Booking & Rental Mobil

Database: **MySQL** | ORM: **Eloquent (Laravel 12)**

## 1. Ringkasan Entitas

```
users            -- admin, staff (pengguna internal sistem)
customers        -- data penyewa (publik, non-login/opsional login)
vehicles         -- data unit mobil
vehicle_images   -- galeri foto mobil (1-to-many dari vehicles)
vehicle_categories -- kategori mobil (city car, MPV, SUV, dst)
drivers          -- data supir (untuk sewa dengan supir)
bookings         -- transaksi booking/sewa
booking_documents -- dokumen penyewa (KTP, SIM) per booking
payments         -- pembayaran (DP & pelunasan) per booking
reviews          -- rating & ulasan customer setelah sewa selesai
notification_logs -- log pengiriman notifikasi WA/email
settings         -- pengaturan umum (template notifikasi, DP minimum, dll)
vehicle_blackout_dates -- tanggal unit tidak tersedia (maintenance dll)
```

## 2. Detail Tabel

### 2.1 `users`
Pengguna internal (admin & staff), login ke dashboard AdminLTE.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| name | varchar(100) | |
| email | varchar(150), unique | |
| password | varchar(255) | hashed |
| role | enum('super_admin','admin','staff') | default 'staff' |
| phone | varchar(20) | nullable |
| is_active | boolean | default true |
| email_verified_at | timestamp | nullable |
| remember_token | varchar(100) | nullable |
| created_at, updated_at | timestamp | |

### 2.2 `customers`
Data penyewa. Booking bisa dilakukan tanpa akun (guest), tapi tetap dicatat sebagai customer record berdasarkan no HP.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| name | varchar(100) | |
| email | varchar(150) | nullable |
| phone | varchar(20), unique | dipakai sebagai identifier utama untuk cek status booking |
| address | text | nullable |
| id_card_number | varchar(50) | nomor KTP, nullable |
| password | varchar(255) | nullable, jika customer memilih buat akun |
| is_blacklisted | boolean | default false |
| blacklist_reason | text | nullable |
| created_at, updated_at | timestamp | |

### 2.3 `vehicle_categories`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| name | varchar(50) | contoh: City Car, MPV, SUV, Luxury |
| slug | varchar(60), unique | |
| created_at, updated_at | timestamp | |

### 2.4 `vehicles`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| category_id | bigint, FK → vehicle_categories.id | |
| name | varchar(100) | contoh: "Toyota Avanza 2022" |
| plate_number | varchar(20), unique | nomor polisi |
| transmission | enum('manual','automatic') | |
| fuel_type | enum('bensin','diesel','listrik','hybrid') | |
| capacity | tinyint | jumlah kursi |
| year | year | tahun kendaraan |
| price_per_day | decimal(12,2) | harga sewa lepas kunci/hari |
| price_per_day_with_driver | decimal(12,2) | harga sewa dengan supir/hari, nullable |
| deposit_amount | decimal(12,2) | jaminan/deposit kendaraan, nullable |
| min_dp_percentage | tinyint | persentase DP minimum, default 30 |
| description | text | nullable |
| status | enum('available','rented','maintenance','inactive') | default 'available' |
| location | varchar(150) | lokasi unit/garasi, nullable |
| created_at, updated_at | timestamp | |
| deleted_at | timestamp | soft delete |

### 2.5 `vehicle_images`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| vehicle_id | bigint, FK → vehicles.id | |
| image_path | varchar(255) | |
| is_primary | boolean | default false |
| sort_order | tinyint | default 0 |
| created_at, updated_at | timestamp | |

### 2.6 `vehicle_blackout_dates`
Tanggal unit tidak bisa disewa (maintenance, servis, dsb) — dipisah dari booking supaya kalender ketersediaan bisa cek dua sumber sekaligus.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| vehicle_id | bigint, FK → vehicles.id | |
| start_date | date | |
| end_date | date | |
| reason | varchar(255) | nullable, contoh: "Servis rutin" |
| created_by | bigint, FK → users.id | |
| created_at, updated_at | timestamp | |

### 2.7 `drivers`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| name | varchar(100) | |
| phone | varchar(20) | |
| license_number | varchar(50) | no. SIM driver |
| status | enum('available','on_duty','off') | default 'available' |
| created_at, updated_at | timestamp | |
| deleted_at | timestamp | soft delete |

### 2.8 `bookings`
Tabel inti transaksi sewa.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| booking_code | varchar(20), unique | kode booking untuk dicek customer, contoh: `RC-20260816-0001` |
| customer_id | bigint, FK → customers.id | |
| vehicle_id | bigint, FK → vehicles.id | |
| driver_id | bigint, FK → drivers.id | nullable, hanya jika with_driver = true |
| with_driver | boolean | default false |
| start_date | date | tanggal mulai sewa |
| end_date | date | tanggal selesai sewa |
| pickup_location | varchar(255) | nullable |
| dropoff_location | varchar(255) | nullable |
| total_days | smallint | dihitung otomatis dari start-end |
| price_per_day | decimal(12,2) | snapshot harga saat booking dibuat (harga bisa berubah nanti) |
| total_price | decimal(12,2) | total_days × price_per_day |
| dp_amount | decimal(12,2) | jumlah DP yang harus dibayar |
| paid_amount | decimal(12,2) | default 0, akumulasi dari tabel payments |
| status | enum('pending','confirmed','ongoing','completed','cancelled','rejected') | default 'pending' |
| payment_status | enum('unpaid','dp_paid','paid_full','refunded') | default 'unpaid' |
| notes | text | nullable, catatan dari customer |
| admin_notes | text | nullable, catatan internal admin |
| cancelled_reason | text | nullable |
| confirmed_by | bigint, FK → users.id | nullable |
| confirmed_at | timestamp | nullable |
| created_at, updated_at | timestamp | |

**Index penting:**
- `INDEX (vehicle_id, start_date, end_date)` — untuk query cek ketersediaan cepat
- `UNIQUE (booking_code)`

**Catatan concurrency:** saat proses create booking, wajib pakai DB transaction + lock (`lockForUpdate()`) pada query pengecekan overlap tanggal untuk `vehicle_id` yang sama, agar tidak terjadi double booking saat dua request masuk bersamaan.

### 2.9 `booking_documents`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| booking_id | bigint, FK → bookings.id | |
| type | enum('ktp','sim','other') | |
| file_path | varchar(255) | disimpan di storage privat |
| created_at, updated_at | timestamp | |

### 2.10 `payments`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| booking_id | bigint, FK → bookings.id | |
| payment_type | enum('dp','pelunasan','refund') | |
| method | enum('gateway','manual_transfer','cash') | |
| amount | decimal(12,2) | |
| gateway_transaction_id | varchar(100) | nullable, dari Midtrans/Xendit |
| proof_file_path | varchar(255) | nullable, bukti transfer manual |
| status | enum('pending','verified','failed') | default 'pending' |
| verified_by | bigint, FK → users.id | nullable |
| verified_at | timestamp | nullable |
| created_at, updated_at | timestamp | |

### 2.11 `reviews`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| booking_id | bigint, FK → bookings.id, unique | 1 booking hanya bisa 1 review |
| customer_id | bigint, FK → customers.id | |
| vehicle_id | bigint, FK → vehicles.id | |
| rating | tinyint | 1-5 |
| comment | text | nullable |
| is_published | boolean | default true, admin bisa moderasi |
| created_at, updated_at | timestamp | |

### 2.12 `notification_logs`
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| booking_id | bigint, FK → bookings.id | nullable |
| channel | enum('whatsapp','email') | |
| recipient | varchar(150) | no HP atau email |
| type | varchar(50) | contoh: 'new_booking','confirmed','reminder_h1','payment_received' |
| payload | text | isi pesan yang dikirim |
| status | enum('sent','failed','pending') | |
| response | text | nullable, response dari API pihak ketiga |
| created_at, updated_at | timestamp | |

### 2.13 `settings`
Key-value store untuk pengaturan umum.

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint, PK | |
| key | varchar(100), unique | contoh: `default_dp_percentage`, `wa_template_confirmed` |
| value | text | |
| created_at, updated_at | timestamp | |

## 3. Relasi Antar Tabel (Ringkas)

```
vehicle_categories 1───N vehicles
vehicles            1───N vehicle_images
vehicles            1───N vehicle_blackout_dates
vehicles            1───N bookings
customers           1───N bookings
drivers             1───N bookings (nullable)
bookings            1───N booking_documents
bookings            1───N payments
bookings            1───1 reviews
bookings            1───N notification_logs
users               1───N bookings (confirmed_by)
```

## 4. Query Kritis: Cek Ketersediaan Unit

Contoh logika pengecekan overlap tanggal (dipakai di kalender & saat create booking):

```sql
SELECT COUNT(*) FROM bookings
WHERE vehicle_id = ?
  AND status NOT IN ('cancelled', 'rejected')
  AND start_date <= ?  -- end_date yang diminta
  AND end_date   >= ?  -- start_date yang diminta

UNION

SELECT COUNT(*) FROM vehicle_blackout_dates
WHERE vehicle_id = ?
  AND start_date <= ?
  AND end_date   >= ?
```
Jika total > 0 → unit tidak tersedia di rentang tanggal tersebut.

## 5. Pertimbangan Migrasi ke Multi-Tenant (Fase 3, tidak dikerjakan di MVP)

Jika nanti dikembangkan jadi SaaS multi-tenant, tambahkan kolom `tenant_id` pada hampir semua tabel (`vehicles`, `bookings`, `customers`, `drivers`, `payments`, dst) dan buat tabel `tenants` baru. Struktur di atas sudah dirancang agar penambahan ini tidak memerlukan perombakan besar.
