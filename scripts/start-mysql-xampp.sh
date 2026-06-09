#!/bin/bash
# Jalankan di Terminal Mac (akan minta password admin sekali).
set -e

XAMPP="/Applications/XAMPP/xamppfiles"

echo "==> Membersihkan PID MySQL yang tertinggal..."
sudo rm -f "$XAMPP/var/mysql/"*.pid 2>/dev/null || true

echo "==> Menjalankan MySQL XAMPP..."
sudo "$XAMPP/xampp" startmysql

sleep 2

if "$XAMPP/bin/mysqladmin" -u root ping 2>/dev/null | grep -q "alive"; then
    echo "OK: MySQL berjalan."
    "$XAMPP/bin/mysql" -u root -e "SHOW DATABASES LIKE 'laksanatech';"
else
    echo "GAGAL: MySQL belum hidup."
    echo "Buka XAMPP Manager: open /Applications/XAMPP/manager-osx.app"
    echo "Lalu klik Start pada MySQL."
    exit 1
fi
