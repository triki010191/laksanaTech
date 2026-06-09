# LaksanaTech.id — Project Structure

## Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 12, PHP 8.4 (target) |
| Frontend | Blade, Tailwind CSS 4, Alpine.js 3, Livewire 4 |
| Database | MySQL 8 |
| Cache / Queue | Redis, Laravel Horizon |
| API Auth | Laravel Sanctum |
| Permissions | Spatie Laravel Permission |
| Activity | Spatie Activity Log |

## Architecture Pattern

```
HTTP Request
    → Controller (flow only)
        → Form Request (validation)
        → Service (business logic)
        → Repository (data access)
        → Model / DTO
    → API Resource / View
```

## Domain Modules

| Module | Path | Description |
|--------|------|-------------|
| Auth | `app/Http/Controllers/Api/V1/Auth` | Register, login, Sanctum tokens |
| Catalog | Services, Templates, FAQ | Public catalog API & web |
| Portfolio | `app/Models/Portfolio*` | Case studies & client work |
| Blog | `app/Models/Blog*` | CMS with SEO |
| Commerce | Orders, Invoices, Payments | Checkout & billing |
| Customer | `app/Http/Controllers/Api/V1/Customer` | Dashboard API |
| Payments | `app/Payments` | Midtrans, Xendit, Tripay abstraction |
| Admin | `routes/admin.php` | Role-protected back office |
| SEO | `app/Http/Controllers/Web/SeoController` | Sitemap, robots, meta |

## Key Directories

```
app/
├── DTO/                    # Data transfer objects
├── Enums/                  # Typed constants
├── Events/                 # Domain events
├── Http/
│   ├── Controllers/
│   │   ├── Api/V1/         # Versioned REST API
│   │   └── Web/            # Public website
│   ├── Requests/Api/V1/
│   ├── Resources/Api/V1/
│   └── Responses/          # ApiResponse helper
├── Listeners/
├── Models/
├── Payments/
│   ├── Contracts/
│   └── Gateways/
├── Repositories/
│   ├── Contracts/
│   └── Eloquent/
├── Services/
└── Traits/
```

## Routes

| File | Prefix | Purpose |
|------|--------|---------|
| `routes/web.php` | `/` | Marketing site |
| `routes/api.php` | `/api/v1` | Mobile / SPA / integrations |
| `routes/admin.php` | `/admin` | Internal dashboard |
| `routes/console.php` | — | Scheduler commands |

## Configuration

- `config/laksana.php` — Brand, company, payment defaults
- `config/permission.php` — RBAC
- `config/horizon.php` — Queue monitoring
