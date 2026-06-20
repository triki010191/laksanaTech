<?php

/**
 * Perbaikan gambar tidak tampil (403 pada /storage/).
 * Upload ke public_html, buka sekali, lalu HAPUS.
 * https://laksanatech.com/fix-gambar.php?token=LaksanaSetup2026
 */

declare(strict_types=1);

if (($_GET['token'] ?? '') !== 'LaksanaSetup2026') {
    exit('?token=LaksanaSetup2026');
}

header('Content-Type: text/html; charset=utf-8');
$base = __DIR__;
$publicStorage = $base.'/storage/app/public';

echo '<h1>Perbaikan gambar</h1><pre>';

$checks = [
    'storage/app/public ada' => is_dir($publicStorage),
    'storage/app/public/hero ada' => is_dir($publicStorage.'/hero'),
];

foreach ($checks as $label => $ok) {
    echo ($ok ? '✓' : '✗')." {$label}\n";
}

$sample = glob($publicStorage.'/hero/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
if ($sample) {
    $name = basename($sample[0]);
    $url = 'https://'.($_SERVER['HTTP_HOST'] ?? 'laksanatech.com').'/storage/hero/'.$name;
    echo "\nUji URL: {$url}\n";
    echo "Jika masih 403 → ganti file .htaccess (lihat deploy/public_html/.htaccess)\n";
} else {
    echo "\n⚠ Folder storage/app/public/hero kosong.\n";
    echo "Upload isi folder storage/app/public/ dari komputer lokal ke server.\n";
}

echo "\n--- Instruksi .htaccess ---\n";
echo "Ganti public_html/.htaccess dengan file deploy/public_html/.htaccess\n";
echo "Atau hapus kata 'storage' dari baris RewriteRule yang memblokir folder.\n";
echo '</pre>';
