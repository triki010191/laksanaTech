<?php

/**
 * DARURAT — perbaiki error 500 setelah patch gambar.
 * https://laksanatech.com/fix-500.php?token=LaksanaSetup2026
 * HAPUS setelah website normal.
 */

declare(strict_types=1);

if (($_GET['token'] ?? '') !== 'LaksanaSetup2026') {
    exit('?token=LaksanaSetup2026');
}

header('Content-Type: text/html; charset=utf-8');
$base = __DIR__;
$log = [];

function row(string $status, string $title, string $msg): void
{
    global $log;
    $log[] = [$status, $title, $msg];
}

// 1. Tampilkan error terakhir dari laravel.log
$logFile = $base.'/storage/logs/laravel.log';
$errorHint = '';
if (is_file($logFile)) {
    $lines = array_slice(file($logFile) ?: [], -40);
    $errorHint = implode('', $lines);
    row('ok', 'laravel.log (40 baris terakhir)', $errorHint ?: '(kosong)');
} else {
    row('warn', 'laravel.log', 'Belum ada');
}

// 2. Pastikan helpers.php ada
$helpers = $base.'/app/Support/helpers.php';
$helpersContent = <<<'PHP'
<?php

use App\Support\PublicMediaUrl;

if (! function_exists('upload_url')) {
    function upload_url(?string $path): ?string
    {
        return PublicMediaUrl::fromPath($path);
    }
}

PHP;

if (! is_dir($base.'/app/Support')) {
    mkdir($base.'/app/Support', 0755, true);
}
file_put_contents($helpers, $helpersContent);
row('ok', 'helpers.php', 'Ditulis ulang');

// 3. Patch index.php — load helpers setelah autoload
$indexFile = $base.'/index.php';
if (is_file($indexFile)) {
    $index = file_get_contents($indexFile);
    $needle = "require __DIR__.'/vendor/autoload.php';";
    $patch = "require __DIR__.'/vendor/autoload.php';\n\nif (is_file(__DIR__.'/app/Support/helpers.php')) {\n    require __DIR__.'/app/Support/helpers.php';\n}";
    if (! str_contains($index, 'app/Support/helpers.php')) {
        $index = str_replace($needle, $patch, $index);
        file_put_contents($indexFile, $index);
        row('ok', 'index.php', 'helpers.php ditambahkan');
    } else {
        row('ok', 'index.php', 'Sudah ada load helpers');
    }
} else {
    row('err', 'index.php', 'Tidak ditemukan');
}

// 4. PublicMediaUrl minimal jika hilang
$pmu = $base.'/app/Support/PublicMediaUrl.php';
if (! is_file($pmu)) {
    row('err', 'PublicMediaUrl.php', 'Upload dari komputer!');
} else {
    row('ok', 'PublicMediaUrl.php', 'Ada');
}

// 5. .env
$envFile = $base.'/.env';
if (is_file($envFile)) {
    $env = file_get_contents($envFile);
    if (! str_contains($env, 'PUBLIC_UPLOAD_PATH=uploads')) {
        $env .= "\nPUBLIC_UPLOAD_PATH=uploads\n";
        file_put_contents($envFile, $env);
    }
    row('ok', '.env', 'PUBLIC_UPLOAD_PATH=uploads');
}

// 6. Hapus cache
foreach (glob($base.'/bootstrap/cache/*.php') ?: [] as $f) {
    @unlink($f);
}
foreach (glob($base.'/storage/framework/views/*.php') ?: [] as $f) {
    @unlink($f);
}
row('ok', 'Cache', 'Dibersihkan');

// 7. Coba bootstrap Laravel
try {
    require $base.'/vendor/autoload.php';
    require $helpers;
    $app = require $base.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    row('ok', 'Test Laravel', 'Bootstrap berhasil');
} catch (Throwable $e) {
    row('err', 'Test Laravel', $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Fix 500</title>
<style>body{font-family:system-ui;max-width:800px;margin:1.5rem auto;padding:0 1rem}
.box{padding:.8rem;margin:.5rem 0;border-radius:.5rem;font-size:.85rem;white-space:pre-wrap;word-break:break-word}
.ok{background:#f0fdf4;border:1px solid #bbf7d0}.err{background:#fef2f2;border:1px solid #fecaca}.warn{background:#fffbeb}
</style></head><body>
<h1>Perbaikan darurat error 500</h1>
<?php foreach ($log as [$s, $t, $m]): ?>
<div class="box <?= $s ?>"><strong><?= htmlspecialchars($t) ?></strong><br><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>
<p><a href="/">Coba buka beranda</a></p>
<p>Hapus <code>fix-500.php</code> setelah website normal.</p>
</body></html>
