# Panduan Pull ke Hosting (Tanpa Terminal)

Update: **20 Juni 2026** — Live demo template, screenshot otomatis, portofolio website live preview.

---

## Ringkasan perubahan

| Item | Detail |
|------|--------|
| Database | Kolom baru `templates.live_demo_url` + data template & portofolio |
| File SQL | `deploy/hosting-update-2026-06-20.sql` — import lewat **phpMyAdmin** |
| Gambar | Folder `storage/app/public/` (blog, template, portofolio) — ikut di Git |
| Kode | Pull/ upload dari GitHub seperti biasa |

---

## Langkah 1 — Pull kode dari GitHub

### Opsi A: Git di cPanel (jika ada menu Git Version Control)

1. cPanel → **Git Version Control** → Clone / Pull repository
2. Branch: `main`

### Opsi B: Upload ZIP dari GitHub

1. GitHub → **Code** → **Download ZIP**
2. Extract ke `public_html/laksanaTech/` (ganti file lama)
3. **Jangan timpa** file `.env` yang sudah ada di server

Pastikan ikut ter-upload:

- `vendor/` (jalankan `composer install --no-dev` di lokal dulu)
- `public/build/` (jalankan `npm run build` di lokal dulu)
- `storage/app/public/` (gambar upload)

---

## Langkah 2 — Update database (phpMyAdmin)

1. Login **phpMyAdmin** di cPanel
2. Pilih database situs Anda (mis. `laksanat_laksanatech`)
3. Tab **Import**
4. Pilih file: `deploy/hosting-update-2026-06-20.sql`
5. Klik **Go** / **Jalankan**

### Jika error "Duplicate column name live_demo_url"

Kolom sudah ada — **abaikan error ALTER**, buka file SQL di editor teks, hapus baris:

```sql
ALTER TABLE `templates` ADD COLUMN IF NOT EXISTS `live_demo_url` ...
```

Lalu import ulang, atau jalankan manual hanya bagian INSERT/UPDATE (bagian 2–6).

### Jika MySQL tidak mendukung `ADD COLUMN IF NOT EXISTS`

Ganti dengan (jalankan sekali saja):

```sql
ALTER TABLE `templates` ADD COLUMN `live_demo_url` VARCHAR(255) NULL AFTER `demo_url`;
```

---

## Langkah 3 — Upload gambar

Gambar disimpan di `storage/app/public/`:

```
storage/app/public/
├── blog/           ← gambar artikel
├── templates/      ← preview template (fallback manual)
├── portfolios/     ← screenshot portofolio lama
└── portfolio/      ← upload admin per proyek
```

Setelah pull Git, pastikan folder ini ada di server. Jika hosting tidak menyalin dari Git:

1. Download folder `storage/app/public/` dari repo
2. Upload via **File Manager** ke `public_html/laksanaTech/storage/app/public/`

### Symlink storage (wajib agar gambar tampil)

Buka di browser (sekali saja):

```
https://domain-anda.com/install-cpanel.php?token=LaksanaSetup2026
```

Atau pastikan `public/storage` → menunjuk ke `storage/app/public` (install-cpanel.php membuat ini).

---

## Langkah 4 — Permission

File Manager → klik kanan → **Change Permissions** (rekursif):

| Folder | Permission |
|--------|------------|
| `storage` | 775 |
| `bootstrap/cache` | 775 |

---

## Langkah 5 — Verifikasi

| URL | Harus |
|-----|-------|
| `/cari-design` | Template + thumbnail screenshot + Lihat Demo |
| `/portofolio` | Website = live preview, lainnya = foto manual |
| `/admin/templates` | Field URL Demo Live |
| `/admin/portfolios` | URL Live untuk kategori Website |

---

## File SQL cadangan

| File | Isi |
|------|-----|
| `deploy/hosting-update-2026-06-20.sql` | Schema + data template & portofolio terbaru |

Regenerate SQL dari lokal (opsional):

```bash
php scripts/export-hosting-sql.php
```

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Thumbnail tidak muncul | Cek `storage` writable + jalankan install-cpanel.php |
| Screenshot lambat | Normal — pertama kali server fetch dari microlink |
| Lihat Demo error | Pastikan hosting bisa akses internet (HTTPS outbound) |
| 500 setelah pull | Cek `storage/logs/laravel.log` |

Lihat juga: [HOSTING_CPANEL_TANPA_TERMINAL.md](./HOSTING_CPANEL_TANPA_TERMINAL.md)
