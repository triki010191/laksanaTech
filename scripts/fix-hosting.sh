#!/usr/bin/env bash
# Script perbaikan otomatis untuk hosting cPanel / LiteSpeed (LaksanaTech)
# Menghapus file hot, menyelaraskan asset build, usePublicPath, dan clear cache.
# Usage: bash scripts/fix-hosting.sh
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

echo "=== 1. Deteksi Path Hosting ==="
WEB_ROOT=""
for candidate in "$ROOT/../public" "$ROOT/../public_html" "$ROOT/public" "$HOME/public_html/public" "$HOME/public_html"; do
    if [[ -f "$candidate/index.php" || -f "$candidate/robots.txt" ]]; then
        WEB_ROOT="$(cd "$candidate" && pwd)"
        break
    fi
done

if [[ -z "$WEB_ROOT" ]]; then
    WEB_ROOT="$ROOT/public"
fi
echo "Laravel Root: $ROOT"
echo "Web Root:     $WEB_ROOT"

echo "=== 2. Bersihkan File 'hot' (Vite Dev Server) ==="
rm -f "$WEB_ROOT/hot" "$ROOT/public/hot" "$HOME/public_html/hot" "$HOME/public_html/public/hot" 2>/dev/null || true
echo "File hot berhasil dibersihkan."

echo "=== 3. Sinkronkan Folder 'build' (Asset CSS & JS) ==="
if [[ -d "$ROOT/public/build" && "$WEB_ROOT" != "$ROOT/public" ]]; then
    mkdir -p "$WEB_ROOT/build"
    cp -ru "$ROOT/public/build/"* "$WEB_ROOT/build/" 2>/dev/null || cp -r "$ROOT/public/build/"* "$WEB_ROOT/build/" 2>/dev/null || true
    echo "Folder build disinkronkan ke: $WEB_ROOT/build"
elif [[ -d "$WEB_ROOT/build" && ! -d "$ROOT/public/build" ]]; then
    mkdir -p "$ROOT/public/build"
    cp -ru "$WEB_ROOT/build/"* "$ROOT/public/build/" 2>/dev/null || cp -r "$WEB_ROOT/build/"* "$ROOT/public/build/" 2>/dev/null || true
    echo "Folder build disinkronkan ke: $ROOT/public/build"
fi

echo "=== 4. Pastikan index.php Menggunakan usePublicPath ==="
INDEX_FILES=("$WEB_ROOT/index.php" "$ROOT/public/index.php")
for idx in "${INDEX_FILES[@]}"; do
    if [[ -f "$idx" ]]; then
        if ! grep -q "usePublicPath" "$idx"; then
            sed -i '/\$app->handleRequest/i \$app->usePublicPath(__DIR__);' "$idx" 2>/dev/null || true
            echo "usePublicPath ditambahkan ke: $idx"
        fi
    fi
done

echo "=== 5. Storage Symlink ==="
php artisan storage:link --force 2>/dev/null || true
if [[ "$WEB_ROOT" != "$ROOT/public" ]]; then
    mkdir -p "$ROOT/storage/app/public"
    ln -sfn "$ROOT/storage/app/public" "$WEB_ROOT/storage" 2>/dev/null || cp -ru "$ROOT/storage/app/public" "$WEB_ROOT/storage" 2>/dev/null || true
fi

echo "=== 6. Bersihkan Cache Laravel ==="
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

echo "=== 7. Set Permission Direktori ==="
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo ""
echo "=== SELESAI! ==="
echo "Semua perbaikan hosting telah diaplikasikan."
echo "Silakan akses: https://laksanatech.com/"
