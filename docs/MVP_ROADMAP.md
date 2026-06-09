# MVP Roadmap — LaksanaTech.id

Goal: Launch a conversion-focused marketing site with lead capture and foundation for payments.

## MVP (8–10 weeks)

### Sprint 1 — Foundation ✅
- [x] Laravel 12 scaffold, Docker, Redis, Horizon
- [x] Database schema & migrations
- [x] RBAC roles (super_admin, admin, sales, customer)
- [x] API v1 skeleton + Sanctum auth
- [x] Brand homepage (Tailwind 4 + Alpine)

### Sprint 2 — Public Site
- [ ] Service detail pages with order CTA
- [ ] Portfolio detail + case study layout
- [x] Blog listing & single post (admin CRUD Livewire)
- [x] FAQ admin CRUD (Livewire)
- [x] Contact form → `contact_leads` + email notification
- [ ] WhatsApp floating button

### Sprint 3 — Customer Area
- [x] Web login/register
- [x] Customer dashboard (orders, invoices, projects, tickets)
- [x] Support ticket create (web Livewire)

### Sprint 4 — Commerce MVP
- [ ] Template marketplace browse + purchase flow
- [ ] Order creation service + invoice PDF
- [x] Midtrans Snap integration + webhook handler
- [x] Payment webhook → invoice paid event
- [x] Admin: blog, templates, FAQ, leads

## Post-MVP (Phase 2)

- Xendit & Tripay gateways
- Template download delivery
- Procurement catalog checkout
- Social login (Google)
- Admin analytics dashboard
- Email marketing integration

## Phase 3 — Growth

- Full API for mobile app
- Project tracking portal (milestones, files)
- SIPLah / government procurement workflows
- Multi-tenant reseller (optional)

## Phase 4 — Scale

- CDN & media optimization
- Elasticsearch for blog/search
- Kubernetes deployment
- SLA monitoring & status page

## Success Metrics (MVP)

| Metric | Target |
|--------|--------|
| Homepage LCP | < 2.5s |
| Lead form conversion | Track via `contact_leads.source` |
| SEO indexed pages | 50+ within 60 days |
| API uptime | 99.5% |
