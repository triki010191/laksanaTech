#!/bin/bash
# Development server + Cloudflare tunnel (setelah MySQL aktif).
set -e

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if ! /Applications/XAMPP/xamppfiles/bin/mysqladmin -u root ping 2>/dev/null | grep -q "alive"; then
    echo "MySQL belum jalan. Jalankan dulu:"
    echo "  bash scripts/start-mysql-xampp.sh"
    exit 1
fi

php artisan config:clear

echo "==> Laravel: http://127.0.0.1:8001"
php artisan serve --host=127.0.0.1 --port=8001 &
SERVE_PID=$!

sleep 2

echo "==> Tunnel Cloudflare (URL publik muncul di bawah)..."
cloudflared tunnel --url http://127.0.0.1:8001 &
TUNNEL_PID=$!

trap 'kill $SERVE_PID $TUNNEL_PID 2>/dev/null' EXIT INT TERM

wait
