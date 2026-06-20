# Hosting cPanel — Document Root Terkunci ke `public_html`

Untuk domain utama **laksanatech.com** yang document root-nya **hanya bisa `public_html`**.

---

## Struktur folder yang benar

```
/home/laksanat/
│
├── laksanaTech/              ← INTI Laravel (TIDAK bisa diakses publik)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env                  ← file environment
│   └── artisan
│   ❌ JANGAN taruh folder public/ di sini untuk diakses web
│
└── public_html/              ← Document root (yang bisa diakses browser)
    ├── index.php             ← pakai deploy/public_html/index.php
    ├── .htaccess
    ├── build/                ← CSS/JS (dari npm run build)
    ├── images/
    ├── install-cpanel.php
    ├── favicon.ico
    └── storage/              ← symlink ke ../laksanaTech/storage/app/public
```

**Penting:** Isi `public_html` = isi folder `public/` Laravel + `index.php` khusus hosting.

---

## Langkah perbaikan (jika sudah salah upload)

### 1. Pindahkan file Laravel

Di File Manager:

1. Buat/pastikan ada folder `/home/laksanat/laksanaTech/`
2. Pindahkan ke `laksanaTech/`:
   - `app`, `bootstrap`, `config`, `database`, `resources`, `routes`, `storage`, `vendor`, `artisan`, `composer.json`
   - file `.env`
3. **Jangan** pindahkan isi `public/` ke `laksanaTech/` — itu untuk `public_html`

### 2. Isi `public_html`

Copy ke `public_html/`:

| Dari project lokal | Ke server |
|------------------|-----------|
| `public/build/` | `public_html/build/` |
| `public/images/` | `public_html/images/` |
| `public/favicon.ico` | `public_html/` |
| `deploy/public_html/index.php` | `public_html/index.php` |
| `deploy/public_html/.htaccess` | `public_html/.htaccess` |
| `public/install-cpanel.php` | `public_html/install-cpanel.php` |

**Ganti** `public_html/index.php` lama dengan file dari `deploy/public_html/index.php`.

### 3. File `.env`

Di `/home/laksanat/laksanaTech/.env` pastikan ada:

```env
APP_URL=https://laksanatech.com
APP_PUBLIC_PATH=/home/laksanat/public_html
APP_ENV=production
APP_DEBUG=false

DB_HOST=localhost
DB_DATABASE=laksanat_laksanatech
DB_USERNAME=laksanat_laksanatech
DB_PASSWORD="laksanatech123!@#"
```

### 4. Permission

| Folder | Permission |
|--------|------------|
| `laksanaTech/storage` | 775 (rekursif) |
| `laksanaTech/bootstrap/cache` | 775 |

### 5. Setup via browser

```
https://laksanatech.com/install-cpanel.php?token=LaksanaSetup2026
```

Hapus `install-cpanel.php` setelah berhasil.

---

## Kenapa tidak cukup copy `public/` saja?

`index.php` bawaan Laravel mencari:

```
../vendor/autoload.php
../bootstrap/app.php
```

Dari `public_html`, folder `vendor` ada di `../laksanaTech/vendor`, bukan `../vendor`.

`index.php` khusus hosting (`deploy/public_html/index.php`) sudah mengarah ke path yang benar.

---

## Cek cepat

| URL | Harapan |
|-----|---------|
| https://laksanatech.com | Beranda Laravel |
| https://laksanatech.com/up | Status OK |
| https://laksanatech.com/admin | Login admin |

Jika error 500 → cek `laksanaTech/storage/logs/laravel.log`
