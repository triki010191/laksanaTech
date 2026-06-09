# LaksanaTech.id

Platform digital **CV. Laksana Solution** — marketplace template, pemesanan website & aplikasi, portofolio, blog, pengadaan IT, dan pembayaran online.

## Tech Stack

- Laravel 12 · PHP 8.4 (Docker) / 8.2+ (local)
- MySQL 8 · Redis · Horizon
- Blade · Tailwind CSS 4 · Alpine.js · Livewire 4
- Sanctum API · Spatie Permission & Activity Log

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run dev
php artisan serve
```

- Website: http://localhost:8000
- API: http://localhost:8000/api/v1/health
- Admin (after login): http://localhost:8000/admin

**Default admin:** `admin@laksanatech.id` / `password`

## Docker

```bash
docker compose up -d --build
# App: http://localhost:8080
```

## Documentation

| Doc | Description |
|-----|-------------|
| [docs/PROJECT_STRUCTURE.md](docs/PROJECT_STRUCTURE.md) | Architecture & modules |
| [docs/DATABASE_ERD.md](docs/DATABASE_ERD.md) | Schema & ERD |
| [docs/API_SPECIFICATION.md](docs/API_SPECIFICATION.md) | REST API v1 |
| [docs/MVP_ROADMAP.md](docs/MVP_ROADMAP.md) | MVP & phases |
| [docs/DEPLOYMENT_STRATEGY.md](docs/DEPLOYMENT_STRATEGY.md) | Production deploy |
| [docs/STITCH_DESIGN.md](docs/STITCH_DESIGN.md) | UI / Stitch guidelines |
| [docs/BRAND_GUIDELINE.md](docs/BRAND_GUIDELINE.md) | Brand colors & fonts |

## API Example

```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@laksanatech.id","password":"password"}'
```

## License

Proprietary — CV. Laksana Solution.
