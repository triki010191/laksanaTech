<?php

/**
 * Instalasi sekali jalan untuk cPanel TANPA Terminal/SSH.
 *
 * 1. Upload seluruh project + folder vendor/ ke server
 * 2. Isi file .env di folder atas public_html/laksanaTech/.env
 * 3. Buka: https://domainanda.id/install-cpanel.php?token=GANTI_TOKEN_INI
 * 4. HAPUS file ini segera setelah berhasil
 */

declare(strict_types=1);

$setupToken = 'LaksanaSetup2026';

if (($_GET['token'] ?? '') !== $setupToken) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    exit("Akses ditolak. Tambahkan ?token={$setupToken} di URL.\n");
}

header('Content-Type: text/html; charset=utf-8');

$basePath = resolveLaravelBasePath();
$log = [];

function resolveLaravelBasePath(): string
{
    $candidates = [
        __DIR__,
        __DIR__.'/../laksanaTech',
        __DIR__.'/laksanaTech',
        dirname(__DIR__),
    ];

    foreach ($candidates as $path) {
        if (is_file($path.'/artisan')) {
            return realpath($path) ?: $path;
        }
    }

    exit('<h1>Error</h1><p>Folder Laravel tidak ditemukan. Harus ada di <code>/home/laksanat/laksanaTech/</code> (sejajar dengan <code>public_html</code>).</p>');
}

function step(string $title, callable $fn): void
{
    global $log;

    try {
        $result = $fn();
        $log[] = ['ok', $title, $result ?: 'OK'];
    } catch (Throwable $e) {
        $log[] = ['err', $title, $e->getMessage()];
    }
}

if (! is_file($basePath.'/.env')) {
    $hint = htmlspecialchars($basePath.'/.env');
    exit(<<<HTML
<h1>File .env belum ada</h1>
<p>Installer mencari file di:</p>
<p><code>{$hint}</code></p>
<ol>
<li>Buka cPanel → <strong>File Manager</strong></li>
<li>Masuk folder <code>public_html</code> (sama dengan folder <code>index.php</code>)</li>
<li>Klik <strong>Settings</strong> → centang <strong>Show Hidden Files (dotfiles)</strong> → Save</li>
<li><strong>+ File</strong> → nama file: <code>.env</code> (pakai titik di depan)</li>
<li>Salin isi dari <code>deploy/public_html/env-untuk-upload.txt</code> di project lokal</li>
<li>Atau upload <code>env-untuk-upload.txt</code> lalu rename menjadi <code>.env</code></li>
</ol>
<p><strong>Jangan</strong> pakai nama <code>env.txt</code> atau <code>.env.txt</code> — harus persis <code>.env</code></p>
HTML);
}

if (! is_file($basePath.'/vendor/autoload.php')) {
    exit('<h1>Error</h1><p>Folder <code>vendor/</code> belum ada. Jalankan <code>composer install --no-dev</code> di komputer lokal, lalu upload folder <code>vendor/</code> ke server.</p>');
}

require $basePath.'/vendor/autoload.php';

$app = require_once $basePath.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Artisan;

step('Cek koneksi database', function () {
    Artisan::call('migrate:status');
    $output = trim(Artisan::output());

    if (str_contains(strtolower($output), 'error') || str_contains(strtolower($output), 'exception')) {
        throw new RuntimeException($output);
    }

    return 'Terhubung — migrate:status berhasil';
});

step('Storage symlink (public/storage)', function () use ($basePath) {
    $link = is_file(__DIR__.'/index.php') ? __DIR__.'/storage' : public_path('storage');
    $target = $basePath.'/storage/app/public';

    if (is_link($link) || is_dir($link)) {
        return 'Sudah ada — dilewati';
    }

    if (! is_dir($target)) {
        mkdir($target, 0755, true);
    }

    if (@symlink($target, $link)) {
        return 'Symlink dibuat';
    }

    // Fallback: salin folder jika hosting melarang symlink
    if (! is_dir($link)) {
        mkdir($link, 0755, true);
    }

    return 'Symlink gagal — hosting mungkin memblokir. Hubungi support atau buat symlink manual di File Manager.';
});

step('Clear cache lama', function () {
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');

    return 'Cache lama dibersihkan';
});

step('Optimize untuk production', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return 'Config, route, view di-cache';
});

step('Cek folder writable', function () use ($basePath) {
    $dirs = [
        $basePath.'/storage',
        $basePath.'/storage/logs',
        $basePath.'/storage/framework',
        $basePath.'/storage/framework/cache',
        $basePath.'/storage/framework/sessions',
        $basePath.'/storage/framework/views',
        $basePath.'/bootstrap/cache',
    ];

    $issues = [];
    foreach ($dirs as $dir) {
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        if (! is_writable($dir)) {
            $issues[] = str_replace($basePath.'/', '', $dir);
        }
    }

    if ($issues !== []) {
        throw new RuntimeException('Folder belum writable (set permission 775 di File Manager): '.implode(', ', $issues));
    }

    return 'Semua folder storage & bootstrap/cache writable';
});

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaksanaTech — Setup cPanel</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 640px; margin: 2rem auto; padding: 0 1rem; color: #1e293b; }
        h1 { font-size: 1.25rem; }
        .step { padding: .75rem 1rem; margin: .5rem 0; border-radius: .5rem; border: 1px solid #e2e8f0; }
        .ok { background: #f0fdf4; border-color: #bbf7d0; }
        .err { background: #fef2f2; border-color: #fecaca; }
        .warn { background: #fffbeb; border: 1px solid #fde68a; padding: 1rem; border-radius: .5rem; margin-top: 1.5rem; }
        code { background: #f1f5f9; padding: .1rem .35rem; border-radius: .25rem; font-size: .9em; }
    </style>
</head>
<body>
    <h1>Setup cPanel — LaksanaTech</h1>

    <?php foreach ($log as [$status, $title, $message]): ?>
        <div class="step <?= $status === 'ok' ? 'ok' : 'err' ?>">
            <strong><?= htmlspecialchars($title) ?></strong><br>
            <?= nl2br(htmlspecialchars($message)) ?>
        </div>
    <?php endforeach; ?>

    <?php $hasError = in_array('err', array_column($log, 0), true); ?>

    <?php if (! $hasError): ?>
        <div class="warn">
            <strong>Berhasil.</strong> Langkah selanjutnya:<br>
            1. <strong>HAPUS</strong> file <code>install-cpanel.php</code> di <code>public_html</code> sekarang juga.<br>
            2. Document root <code>public_html</code> + Laravel di <code>laksanaTech/</code> — sudah benar.<br>
            3. Uji situs: <a href="/">Beranda</a> · <a href="/up">Health check</a><br>
            4. Ganti password admin default di panel admin.
        </div>
    <?php else: ?>
        <div class="warn">
            Ada error di atas. Perbaiki lalu refresh halaman ini. Cek juga file <code>storage/logs/laravel.log</code> via File Manager.
        </div>
    <?php endif; ?>
</body>
</html>
