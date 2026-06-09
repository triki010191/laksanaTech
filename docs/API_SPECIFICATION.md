# API Specification — `/api/v1`

Base URL: `{APP_URL}/api/v1`

## Response Format

**Success**
```json
{
  "success": true,
  "message": "Success",
  "data": {}
}
```

**Error**
```json
{
  "success": false,
  "message": "Validation Error",
  "errors": {}
}
```

## Authentication

Use Laravel Sanctum bearer token:

```
Authorization: Bearer {token}
```

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `/auth/register` | No | Register customer |
| POST | `/auth/login` | No | Login, returns token |
| POST | `/auth/forgot-password` | No | Send reset link |
| POST | `/auth/logout` | Yes | Revoke current token |
| GET | `/auth/me` | Yes | Current user profile |

## Public Catalog

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/health` | API health check |
| GET | `/services` | Paginated services (`?per_page=12`) |
| GET | `/services/{slug}` | Service detail |
| GET | `/templates` | Template marketplace (`?category=`) |
| GET | `/templates/{slug}` | Template detail |
| GET | `/portfolios` | Portfolio list (`?featured=1`) |
| GET | `/portfolios/{slug}` | Portfolio detail |
| GET | `/blog` | Published posts |
| GET | `/blog/{slug}` | Post detail (increments views) |
| GET | `/faqs` | Active FAQs |

## Customer (Authenticated)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/customer/orders` | My orders |
| GET | `/customer/orders/{uuid}` | Order detail |
| GET | `/customer/invoices` | My invoices |
| GET | `/customer/projects` | My projects + milestones |
| GET | `/customer/support-tickets` | My tickets |
| POST | `/customer/support-tickets` | Create ticket |

**Create ticket body**
```json
{
  "subject": "string",
  "message": "string",
  "priority": "low|medium|high|urgent"
}
```

## Payments

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/payments/webhook/{gateway}` | Webhook receiver (`midtrans`, `xendit`, `tripay`) |

## Rate Limiting

API routes use Laravel `throttleApi()` middleware (default 60/min per user/IP).

## Future Endpoints (Phase 2+)

- `POST /orders` — Create service/template order
- `POST /payments/charge` — Initiate payment
- `POST /contact-leads` — Public lead form
- Admin CRUD under `/api/v1/admin/*` with role middleware
