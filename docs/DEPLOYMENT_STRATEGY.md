# Production Deployment Strategy

> **Panduan praktis hosting (cPanel / VPS):** lihat [HOSTING.md](HOSTING.md)

## Target Infrastructure

```
Internet → Cloudflare CDN → Nginx → PHP-FPM (Laravel)
                              ↓
                         MySQL 8 (Primary)
                              ↓
                         Redis (cache, queue, session)
                              ↓
                         Horizon workers
                              ↓
                         Scheduler (cron)
```

## Docker Production (recommended)

1. Build image from `docker/php/Dockerfile` (PHP 8.4 + extensions)
2. Run `docker compose` with production overrides:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `QUEUE_CONNECTION=redis`
   - `CACHE_STORE=redis`
   - `SESSION_DRIVER=redis`
3. Nginx serves `public/` only; deny access to `.env`
4. Separate containers: `app`, `nginx`, `mysql`, `redis`, `horizon`, `scheduler`

## Pre-deploy Checklist

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan db:seed --class=RoleAndPermissionSeeder --force
npm ci && npm run build
```

## Environment Variables (production)

| Variable | Purpose |
|----------|---------|
| `APP_KEY` | Encryption |
| `APP_URL` | Canonical URL |
| `DB_*` | MySQL connection |
| `REDIS_*` | Cache & queues |
| `MIDTRANS_*` / `XENDIT_*` / `TRIPAY_*` | Payment gateways |
| `MAIL_*` | Transactional email |
| `STITCH_API_KEY` | Optional UI generation (never commit) |

## Security Hardening

- Force HTTPS (Nginx + `APP_URL` https)
- CSRF on all web forms (Laravel default)
- API rate limiting (`throttleApi`)
- Spatie roles on `/admin`
- Horizon dashboard restricted to `super_admin`
- Rotate `admin@laksanatech.id` password immediately
- Store secrets in server env / vault, not git

## Backup & Monitoring

- Daily MySQL dump + off-site storage
- Redis persistence (AOF) if using queues critically
- Log aggregation (Laravel log → syslog / CloudWatch)
- Uptime monitor on `/up` and `/api/v1/health`
- Horizon metrics for failed jobs

## Zero-downtime Deploy

1. Put app in maintenance: `php artisan down`
2. Pull release, run migrations, clear caches
3. Restart PHP-FPM & Horizon
4. `php artisan up`
5. Smoke test: homepage, API health, webhook endpoint

## XAMPP Local Development

```bash
cp .env.example .env
# Set DB_CONNECTION=mysql, DB_DATABASE=laksanatech
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

Access: `http://localhost:8000` or configure Apache vhost to `public/`.
