# Panduan Deployment (Production)

Dokumen ini berisi panduan teknis langkah demi langkah untuk melakukan *deployment* aplikasi **AutoRent** ke server produksi (VPS Ubuntu/Debian atau panel server dengan akses SSH).

## 1. Persyaratan Sistem (System Requirements)
Pastikan server produksi Anda telah memenuhi persyaratan berikut:
- PHP >= 8.2
- Ekstensi PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL.
- MySQL >= 8.0 atau MariaDB >= 10.3
- Composer >= 2.0
- Node.js >= 20.x & NPM

## 2. Persiapan Direktori & Kloning Repository
1. Masuk ke server melalui SSH.
2. Buat direktori (folder) aplikasi, contoh: `/var/www/rentalmobil`.
3. Klon kode aplikasi dari repository Git atau unggah file *source code* secara langsung.

## 3. Instalasi Dependency
Masuk ke direktori utama aplikasi dan jalankan perintah:

```bash
# Instal library PHP (tanpa paket development)
composer install --optimize-autoloader --no-dev

# Instal library NPM
npm install
```

## 4. Konfigurasi Environment (`.env`)
Salin template konfigurasi:
```bash
cp .env.example .env
```
Lalu edit file `.env` (misal menggunakan `nano .env`) dan sesuaikan beberapa paramater krusial ini:

```env
APP_NAME=AutoRent
APP_ENV=production
APP_KEY= # (nanti diisi oleh perintah artisan generate)
APP_DEBUG=false
APP_URL=https://rentalmobil.com # Sesuaikan domain Anda

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=user_database_anda
DB_PASSWORD=password_database_anda

# Konfigurasi Queue (Wajib diganti ke database/redis untuk production agar tidak sinkron/lemot)
QUEUE_CONNECTION=database

# Kunci API Midtrans (Gunakan kunci Production)
MIDTRANS_SERVER_KEY=Mid-server-xxx
MIDTRANS_CLIENT_KEY=Mid-client-xxx
MIDTRANS_IS_PRODUCTION=true

# Kunci API WhatsApp (Fonnte)
FONNTE_TOKEN=TokenAndaDariFonnte
```

## 5. Inisialisasi Aplikasi (Generate Key & Database)
Jalankan rentetan perintah berikut:

```bash
# Generate Security Key
php artisan key:generate

# Lakukan Migrasi Struktur Database beserta Data Awal (Seeder)
php artisan migrate --force
php artisan db:seed --force

# Sinkronisasi folder penyimpanan file (KTP/SIM/Foto Mobil) ke folder publik
php artisan storage:link
```

## 6. Build Frontend Assets
Aplikasi ini memiliki multi-entry asset (Satu untuk publik menggunakan Tailwind, satu untuk admin menggunakan AdminLTE). Build keduanya untuk *production* agar file CSS/JS di-minify:

```bash
npm run build
```

## 7. Optimasi Cache Laravel
Untuk meningkatkan performa baca di server produksi, gunakan *caching* bawaan Laravel:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## 8. Mengatur Worker Queue (Supervisor)
Karena pengiriman notifikasi WhatsApp diatur agar berjalan di latar belakang (melalui `Jobs`), Anda wajib mengonfigurasi **Supervisor** agar antrean pengiriman pesan terus dieksekusi secara instan.

1. Install supervisor (Jika di Ubuntu): `sudo apt install supervisor`
2. Buat konfigurasi baru: `sudo nano /etc/supervisor/conf.d/rentalmobil-worker.conf`
3. Isi konfigurasinya dengan:

```ini
[program:rentalmobil-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/rentalmobil/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/rentalmobil/storage/logs/worker.log
stopwaitsecs=3600
```

4. Simpan, dan jalankan perintah:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start rentalmobil-worker:*
```

## 9. Mengatur Scheduled Tasks (Cronjob)
Aplikasi ini memiliki fitur **Auto-Cancel Booking** (pembatalan booking yang tidak dibayar dalam 24 jam) yang harus dijalankan secara berkala (setiap jam).
Konfigurasikan Cron di server Linux Anda:

```bash
crontab -e
```

Tambahkan baris berikut di paling bawah (ganti `/var/www/rentalmobil` sesuai path aplikasi Anda):

```bash
* * * * * cd /var/www/rentalmobil && php artisan schedule:run >> /dev/null 2>&1
```

## 10. Pengaturan Web Server & Permissions
Pastikan _web server_ Anda (Nginx/Apache) mengarah ke direktori `public/` sebagai *Document Root*.

Beri hak akses baca-tulis kepada pengguna *web server* (`www-data` di Ubuntu) pada folder `storage` dan `bootstrap/cache`:

```bash
sudo chown -R www-data:www-data /var/www/rentalmobil/storage
sudo chown -R www-data:www-data /var/www/rentalmobil/bootstrap/cache
sudo chmod -R 775 /var/www/rentalmobil/storage
sudo chmod -R 775 /var/www/rentalmobil/bootstrap/cache
```

## Selesai!
Aplikasi rental mobil Anda kini siap digunakan secara publik!
