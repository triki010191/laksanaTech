# Hosting cPanel Tanpa Terminal / SSH

Panduan ini untuk paket cPanel yang **tidak punya Terminal**.

---

## Yang Anda Butuhkan dari Komputer Lokal

Sebelum upload, jalankan di komputer (folder project):

```bash
composer install --no-dev --optimize-autoloader
npm ci && npm run build
```

Pastikan folder ini ikut ter-upload ke server:

| Folder / file | Wajib |
|---------------|-------|
| `vendor/` | Ya |
| `public/build/` | Ya (CSS & JS) |
| `.env` | Buat di server (jangan upload .env lokal) |
| `node_modules/` | Tidak perlu |

---

## Struktur di Server

```
public_html/
└── laksanaTech/          ← root project Laravel
    ├── .env              ← buat manual
    ├── app/
    ├── vendor/
    ├── storage/
    ├── bootstrap/
    └── public/           ← document root domain HARUS ke sini
        ├── index.php
        ├── build/
        └── install-cpanel.php
```

### Document root (penting)

Di cPanel → **Domains** → pilih domain → **Document Root**:

```
public_html/laksanaTech/public
```

Bukan `public_html/laksanaTech` saja.

---

## Langkah 1 — File `.env`

1. Buka **File Manager** cPanel
2. Masuk ke `public_html/laksanaTech/`
3. **+ File** → nama: `.env`
4. Salin isi dari file `.env.cpanel` (atau template di bawah)

```env
APP_NAME=LaksanaTech
APP_ENV=production
APP_KEY=base64:ISI_DARI_LOKAL_ATAU_GENERATE
APP_DEBUG=false
APP_URL=https://laksanatech.id

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=laksanat_laksanatech
DB_USERNAME=laksanat_laksanatech
DB_PASSWORD="laksanatech123!@#"

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
FILESYSTEM_DISK=public
CACHE_STORE=database
QUEUE_CONNECTION=database
LOG_LEVEL=warning
```

**Jangan** tambahkan `DB_SOCKET` (itu khusus XAMPP).

`APP_KEY` — salin dari `.env` komputer lokal Anda, atau buat file sementara `public/make-key.php`:

```php
<?php echo 'base64:'.base64_encode(random_bytes(32));
```

Buka di browser, salin hasil ke `.env`, lalu **hapus** `make-key.php`.

---

## Langkah 2 — Permission (File Manager)

Klik kanan folder → **Change Permissions** → centang **Write** untuk group:

| Folder | Permission |
|--------|------------|
| `storage` | 775 (rekursif) |
| `bootstrap/cache` | 775 |

Di File Manager: centang **Recurse into subdirectories**.

---

## Langkah 3 — Setup via Browser (ganti Terminal)

1. Pastikan file `public/install-cpanel.php` sudah ter-upload
2. Buka di browser:

```
https://laksanatech.id/install-cpanel.php?token=LaksanaSetup2026
```

3. Jika semua hijau → **hapus** `install-cpanel.php` segera

---

## Langkah 4 — Cron Job (tanpa Terminal)

cPanel → **Cron Jobs** → tambah:

| Menit | Jam | Perintah |
|-------|-----|----------|
| `*` | `*` | `cd /home/USERNAME/public_html/laksanaTech && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1` |

Ganti `USERNAME` dengan username cPanel Anda (terlihat di path File Manager).

Untuk antrian email (opsional):

```
cd /home/USERNAME/public_html/laksanaTech && /usr/local/bin/php artisan queue:work --stop-when-empty --max-time=55 >> /dev/null 2>&1
```

Path PHP bisa berbeda — cek di cPanel → **Select PHP Version** → **php.ini** atau tanya support hosting.

---

## Langkah 5 — Database

Anda sudah import via **phpMyAdmin** — tidak perlu `php artisan migrate` jika data sudah lengkap.

Jika tabel belum ada, minta hosting jalankan migrate, atau gunakan **phpMyAdmin** import file `.sql` lagi.

---

## Troubleshooting

| Gejala | Solusi |
|--------|--------|
| Halaman putih / 500 | Cek `storage/logs/laravel.log` via File Manager |
| CSS tidak ada | Upload folder `public/build/` dari lokal |
| Class not found | Upload folder `vendor/` |
| Gambar upload gagal | Jalankan install-cpanel.php (storage link) |
| Database error | Cek nama DB/user di cPanel MySQL, harus sama dengan `.env` |
| 403 Forbidden | Document root harus ke folder `public/` |

---

## Fitur cPanel yang Mungkin Ada

Beberapa hosting punya (cek menu cPanel):

- **Terminal** — di bawah Advanced (jika ada, bisa pakai `bash scripts/hosting-install.sh`)
- **Setup Python App / Composer** — jarang untuk Laravel
- **Git Version Control** — clone langsung di server

Jika tidak ada Terminal, ikuti panduan ini saja.
