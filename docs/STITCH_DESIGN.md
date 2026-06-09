# Google Stitch Design Integration

Design direction follows [stitch.withgoogle.com](https://stitch.withgoogle.com): clean modular layouts, generous whitespace, rounded cards (`rounded-2xl` / `rounded-3xl`), subtle gradients, and conversion-focused CTAs.

## Brand Alignment (CV. Laksana Solution)

| Token | Value | Usage |
|-------|-------|-------|
| Primary | `#00AEEF` | Buttons, links, highlights |
| Secondary | `#A6CE39` | Success, secondary accents |
| Accent | `#F9B233` | CTA emphasis (WhatsApp) |
| Background | `#FFFFFF` | Light professional theme |
| Heading font | Poppins | `font-display` |
| Body font | Inter | `font-sans` |

Logos: `public/images/brand/logo-main.png`, `logo-horizontal.png`, `logo-icon.png`

## Stitch SDK (optional automation)

Store API key in `.env` only:

```env
STITCH_API_KEY=your_key_here
```

Use [@google/stitch-sdk](https://github.com/google-labs-code/stitch-sdk) to generate screen HTML from prompts, e.g. homepage variants, admin tables, checkout flow.

**Prompt example for homepage hero:**
> Professional IT company landing page, white background, sky blue #00AEEF primary, orange CTA, rounded cards, portfolio grid, Poppins headings, Indonesian copy, mobile-first, Stitch-style clean layout.

Export generated HTML into Blade partials under `resources/views/components/`.

## Implemented UI (Phase 1)

- `resources/views/layouts/app.blade.php` — SEO meta, OG tags
- `resources/views/pages/home.blade.php` — Hero, services, portfolio, CTA
- Tailwind 4 `@theme` brand colors in `resources/css/app.css`

## Next Stitch Screens to Generate

1. Template marketplace grid + detail
2. Customer dashboard sidebar layout
3. Admin order management table
4. Checkout / invoice payment page
