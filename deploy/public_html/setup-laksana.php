<?php

/**
 * Setup LaksanaTech — logic dulu, HTML belakangan (agar error tetap tampil).
 * https://laksanatech.com/setup-laksana.php?token=LaksanaSetup2026
 */

declare(strict_types=1);

$setupToken = 'LaksanaSetup2026';
$givenToken = trim((string) ($_GET['token'] ?? ''));

if ($givenToken !== $setupToken) {
    header('Content-Type: text/html; charset=utf-8');
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
        .'://'.($_SERVER['HTTP_HOST'] ?? 'laksanatech.com')
        .strtok($_SERVER['REQUEST_URI'] ?? '/setup-laksana.php', '?');
    exit('<p>Tambahkan token: <a href="'.htmlspecialchars($url.'?token='.$setupToken).'">'.$url.'?token='.$setupToken.'</a></p>');
}

ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(180);

$webRoot = __DIR__;
$basePath = null;
$scan = [];
$candidates = [$webRoot, $webRoot.'/laksanaTech', dirname($webRoot).'/laksanaTech'];

foreach ($candidates as $path) {
    $real = @realpath($path) ?: $path;
    $scan[] = [
        'path' => $real,
        'artisan' => is_file($path.'/artisan'),
        'env' => is_file($path.'/.env'),
        'vendor' => is_file($path.'/vendor/autoload.php'),
    ];
    if (is_file($path.'/artisan')) {
        $basePath = $real;
        break;
    }
}

$steps = [];

if ($basePath === null) {
    $steps[] = ['err', 'Laravel', 'Folder artisan tidak ditemukan'];
} else {
    foreach (glob($basePath.'/bootstrap/cache/*.php') ?: [] as $f) {
        @unlink($f);
    }
    foreach (glob($basePath.'/storage/framework/views/*.php') ?: [] as $f) {
        @unlink($f);
    }
    $steps[] = ['ok', 'Cache lama', 'bootstrap/cache & views dibersihkan'];

    // Wajib: load helpers.php di index.php (upload_url di view)
    $helpersFile = $basePath.'/app/Support/helpers.php';
    if (! is_file($helpersFile)) {
        $steps[] = ['warn', 'helpers.php', 'Belum ada — upload app/Support/helpers.php'];
    }
    $indexFile = $webRoot.'/index.php';
    if (is_file($indexFile)) {
        $index = file_get_contents($indexFile);
        $needle = "require __DIR__.'/vendor/autoload.php';";
        $patch = "require __DIR__.'/vendor/autoload.php';\n\nif (is_file(__DIR__.'/app/Support/helpers.php')) {\n    require __DIR__.'/app/Support/helpers.php';\n}";
        if (! str_contains($index, 'app/Support/helpers.php')) {
            $index = str_replace($needle, $patch, $index);
            file_put_contents($indexFile, $index);
            $steps[] = ['ok', 'index.php', 'helpers.php ditambahkan'];
        } else {
            $steps[] = ['ok', 'index.php', 'Sudah load helpers'];
        }
    }

    if (! is_file($basePath.'/.env')) {
        $steps[] = ['err', '.env', 'Belum ada di '.$basePath.'/.env'];
    } elseif (! is_file($basePath.'/vendor/autoload.php')) {
        $steps[] = ['err', 'vendor', 'Upload folder vendor/ ke '.$basePath];
    } else {
        try {
            require $basePath.'/vendor/autoload.php';
            $app = require $basePath.'/bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
            $steps[] = ['ok', 'Bootstrap', 'PHP '.PHP_VERSION.' — Laravel OK'];

            try {
                Illuminate\Support\Facades\Artisan::call('migrate:status');
                $steps[] = ['ok', 'Database', 'Terhubung'];
            } catch (Throwable $e) {
                $steps[] = ['err', 'Database', $e->getMessage()];
            }

            $link = $webRoot.'/storage';
            if (! is_link($link) && ! is_dir($link)) {
                $target = $basePath.'/storage/app/public';
                if (! is_dir($target)) {
                    mkdir($target, 0755, true);
                }
                if (@symlink($target, $link)) {
                    $steps[] = ['ok', 'Storage link', 'public_html/storage dibuat'];
                } else {
                    $steps[] = ['err', 'Storage link', 'Symlink gagal — minta hosting aktifkan symlink'];
                }
            } else {
                $steps[] = ['ok', 'Storage link', 'Sudah ada'];
            }

            try {
                Illuminate\Support\Facades\Artisan::call('config:clear');
                Illuminate\Support\Facades\Artisan::call('route:clear');
                Illuminate\Support\Facades\Artisan::call('view:clear');
                $steps[] = ['ok', 'Cache', 'Config, route, view dibersihkan'];
            } catch (Throwable $e) {
                $steps[] = ['err', 'Cache', $e->getMessage()];
            }

            $log = $basePath.'/storage/logs/laravel.log';
            if (is_file($log)) {
                $lines = array_slice(file($log) ?: [], -15);
                $steps[] = ['warn', 'laravel.log (15 baris terakhir)', implode('', $lines) ?: '(kosong)'];
            }
        } catch (Throwable $e) {
            $steps[] = ['err', 'Bootstrap gagal', $e->getMessage()];
            $steps[] = ['err', 'Lokasi error', $e->getFile().':'.$e->getLine()];
            $log = $basePath.'/storage/logs/laravel.log';
            if (is_file($log)) {
                $lines = array_slice(file($log) ?: [], -20);
                $steps[] = ['err', 'laravel.log', implode('', $lines)];
            }
        }
    }
}

$allOk = $steps !== [] && ! in_array('err', array_column($steps, 0), true);

header('Content-Type: text/html; charset=utf-8');

function h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup LaksanaTech</title>
    <style>
        body{font-family:system-ui,sans-serif;max-width:720px;margin:1.5rem auto;padding:0 1rem;color:#1e293b}
        table{width:100%;border-collapse:collapse;font-size:.9rem;margin:1rem 0}
        th,td{border:1px solid #e2e8f0;padding:.5rem;text-align:left}
        th{background:#f8fafc}
        .ok{color:#15803d}.no{color:#b91c1c}
        .box{padding:1rem;border-radius:.5rem;margin:.75rem 0;white-space:pre-wrap;word-break:break-word;font-size:.9rem}
        .box-ok{background:#f0fdf4;border:1px solid #bbf7d0}
        .box-err{background:#fef2f2;border:1px solid #fecaca}
        code{background:#f1f5f9;padding:.1rem .35rem;border-radius:.25rem}
    </style>
</head>
<body>
<h1>Setup LaksanaTech</h1>
<p>Web root: <code><?= h($webRoot) ?></code></p>

<h2>Scan folder</h2>
<table>
    <tr><th>Path</th><th>artisan</th><th>.env</th><th>vendor</th></tr>
    <?php foreach ($scan as $row): ?>
    <tr>
        <td><code><?= h($row['path']) ?></code></td>
        <td class="<?= $row['artisan'] ? 'ok' : 'no' ?>"><?= $row['artisan'] ? '✓' : '✗' ?></td>
        <td class="<?= $row['env'] ? 'ok' : 'no' ?>"><?= $row['env'] ? '✓' : '✗' ?></td>
        <td class="<?= $row['vendor'] ? 'ok' : 'no' ?>"><?= $row['vendor'] ? '✓' : '✗' ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Hasil setup</h2>
<?php foreach ($steps as [$status, $title, $msg]): ?>
<div class="box <?= $status === 'ok' ? 'box-ok' : 'box-err' ?>">
    <strong><?= h($title) ?></strong><br><?= h($msg) ?>
</div>
<?php endforeach; ?>

<?php if ($allOk): ?>
<div class="box box-ok">
    <strong>Berhasil.</strong>
    1. Hapus setup-laksana.php, setup-debug.php, install-cpanel.php
    2. Uji <a href="/">beranda</a> · <a href="/up">/up</a>
    3. Ganti password admin
</div>
<?php endif; ?>
</body>
</html>
