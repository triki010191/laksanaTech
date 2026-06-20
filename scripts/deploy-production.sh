#!/usr/bin/env bash
# Deploy / update LaksanaTech di server production.
# Usage: bash scripts/deploy-production.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if [[ ! -f .env ]]; then
    echo "ERROR: File .env tidak ditemukan."
    echo "Salin .env.production.example ke .env, isi database & APP_URL, lalu jalankan:"
    echo "  php artisan key:generate"
    exit 1
fi

if grep -q '^APP_ENV=production' .env 2>/dev/null && grep -q '^APP_DEBUG=true' .env 2>/dev/null; then
    echo "ERROR: APP_DEBUG=true tidak boleh dipakai saat APP_ENV=production."
    exit 1
fi

echo "==> Maintenance mode"
php artisan down --retry=60 --refresh=15 || true

echo "==> Composer (production)"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if command -v npm >/dev/null 2>&1; then
    echo "==> Build frontend assets"
    npm ci --no-audit --no-fund
    npm run build
else
    echo "WARN: npm tidak ditemukan — pastikan folder public/build/ sudah ada (build di lokal)."
fi

echo "==> Storage symlink"
php artisan storage:link --force 2>/dev/null || php artisan storage:link || true

echo "==> Database migrate"
php artisan migrate --force

echo "==> Optimize Laravel"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache 2>/dev/null || true
php artisan optimize

echo "==> Permission direktori writable"
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

echo "==> Live"
php artisan up

echo ""
echo "Deploy selesai. Uji: curl -sS \"\$(grep '^APP_URL=' .env | cut -d= -f2-)/up\""
