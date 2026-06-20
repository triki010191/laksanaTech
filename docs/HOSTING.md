# Panduan Hosting LaksanaTech

Panduan praktis men-deploy ke **shared hosting (cPanel)** atau **VPS**.

## Persyaratan Server

| Komponen | Minimum |
|----------|---------|
| PHP | 8.2+ (ekstensi: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, cURL, GD atau Imagick) |
| MySQL | 8.0+ / MariaDB 10.6+ |
| Node.js | 18+ (hanya saat build asset; bisa di komputer lokal) |
| Composer | 2.x |
| SSL | Wajib (HTTPS) |

---

## Opsi A — Shared Hosting (cPanel)

> **Tidak ada Terminal?** Ikuti panduan khusus: [HOSTING_CPANEL_TANPA_TERMINAL.md](HOSTING_CPANEL_TANPA_TERMINAL.md)

### 1. Upload kode

- Clone/upload repo ke folder di server (mis. `~/laksanatech`)
- **Jangan** upload folder `vendor/`, `node_modules/`, `.env`
- Atau di server: `git clone` lalu `composer install`

### 2. Document root

**Paling aman:** arahkan domain/subdomain ke folder **`public/`**

```
/home/user/laksanatech/public   ← document root
```

**Alternatif** (jika tidak bisa ubah document root): upload ke `public_html` dan arahkan isi folder `public/` ke sana, atau pakai `.htaccess` di root proyek (sudah disediakan) yang mengarahkan ke `public/`.

### 3. Database

1. Buat database MySQL + user di cPanel
2. Salin kredensial ke `.env`

```bash
cp .env.production.example .env
# Edit DB_*, APP_URL=https://domainanda.id
php artisan key:generate
```

### 4. Instalasi

Via SSH (jika tersedia):

```bash
cd ~/laksanatech
bash scripts/hosting-install.sh
```

Tanpa SSH — jalankan perintah yang sama via **Terminal cPanel** atau lakukan manual:

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan db:seed --class=RoleAndPermissionSeeder --force
php artisan storage:link
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

### 5. Build CSS/JS (jika server tanpa Node.js)

Di komputer lokal:

```bash
npm ci && npm run build
```

Upload folder **`public/build/`** ke server.

### 6. Permission

Pastikan writable:

```bash
chmod -R 775 storage bootstrap/cache
```

Di cPanel File Manager: `storage` dan `bootstrap/cache` → permission **775**.

### 7. Cron job (wajib)

Tambah cron **setiap menit**:

```cron
* * * * * cd /home/user/laksanatech && php artisan schedule:run >> /dev/null 2>&1
```

Untuk antrian email/notifikasi (tanpa Redis):

```cron
* * * * * cd /home/user/laksanatech && php artisan queue:work --stop-when-empty --max-time=55 >> /dev/null 2>&1
```

### 8. `.env` production — checklist

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.id
SESSION_SECURE_COOKIE=true
MIDTRANS_IS_PRODUCTION=true
```

---

## Opsi B — VPS / Cloud (Nginx + PHP-FPM)

### Deploy / update

```bash
git pull origin main
bash scripts/deploy-production.sh
```

### Nginx (contoh)

```nginx
server {
    listen 443 ssl http2;
    server_name laksanatech.id;
    root /var/www/laksanatech/public;

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Redis + Horizon (opsional, direkomendasikan VPS)

```env
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis
```

```bash
php artisan horizon
# Supervisor: jalankan horizon sebagai daemon
```

### Scheduler (cron)

```cron
* * * * * cd /var/www/laksanatech && php artisan schedule:run >> /dev/null 2>&1
```

---

## Setelah Deploy — Uji Cepat

```bash
curl -sS https://domainanda.id/up
curl -sS https://domainanda.id/api/v1/health
```

| Cek | URL |
|-----|-----|
| Health | `/up` |
| API | `/api/v1/health` |
| Beranda | `/` |
| Admin | `/admin` |

---

## Keamanan

1. **Ganti password admin** default (`admin@laksanatech.id`)
2. Jangan commit `.env` ke Git
3. `APP_DEBUG=false` di production
4. Aktifkan SSL (Let's Encrypt / Cloudflare)
5. Backup database harian

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| CSS/JS tidak load | Jalankan `npm run build`, pastikan `public/build/` ada |
| Gambar upload 404 | `php artisan storage:link` |
| Gambar tidak tampil di HP | Pastikan `APP_URL` = domain HTTPS yang dipakai |
| Error 500 setelah deploy | `php artisan config:clear` lalu `config:cache`; cek `storage/logs/laravel.log` |
| Mixed content (http/https) | `APP_URL` pakai `https://`, aktifkan SSL |

---

## File Penting

| File | Fungsi |
|------|--------|
| `.env.production.example` | Template environment production |
| `scripts/hosting-install.sh` | Instalasi pertama kali |
| `scripts/deploy-production.sh` | Update / redeploy |
| `.htaccess` (root) | Redirect ke `public/` (shared hosting) |
| `public/.htaccess` | Rewrite Laravel (sudah ada) |
