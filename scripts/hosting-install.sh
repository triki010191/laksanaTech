#!/usr/bin/env bash
# Instalasi pertama kali di server hosting (setelah upload kode & buat database).
# Usage: bash scripts/hosting-install.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if [[ ! -f .env ]]; then
    if [[ -f .env.production.example ]]; then
        cp .env.production.example .env
        echo "File .env dibuat dari .env.production.example"
    else
        cp .env.example .env
        echo "File .env dibuat dari .env.example"
    fi
    php artisan key:generate --force
    echo ""
    echo "Edit .env (DB_*, APP_URL, mail, payment) lalu jalankan ulang:"
    echo "  bash scripts/hosting-install.sh"
    exit 0
fi

echo "==> Composer"
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

if command -v npm >/dev/null 2>&1; then
    echo "==> Build assets"
    npm ci --no-audit --no-fund
    npm run build
else
    echo "WARN: npm tidak ada — upload public/build/ dari komputer lokal jika perlu."
fi

echo "==> Storage"
php artisan storage:link --force 2>/dev/null || php artisan storage:link || true

echo "==> Migrate & seed dasar"
php artisan migrate --force
php artisan db:seed --class=RoleAndPermissionSeeder --force

echo "==> Cache"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

echo ""
echo "Instalasi selesai."
echo "Opsional konten demo: php artisan db:seed --force"
echo "Login admin default (ganti password segera): admin@laksanatech.id / password"
