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

function copyDirRecursively(string $src, string $dst): void
{
    @mkdir($dst, 0755, true);
    $items = @scandir($src) ?: [];
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        $from = $src.'/'.$item;
        $to = $dst.'/'.$item;
        if (is_dir($from)) {
            copyDirRecursively($from, $to);
        } else {
            @copy($from, $to);
        }
    }
}

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
    $steps[] = ['ok', 'Cache lama', 'bootstrap/cache dibersihkan'];

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
                $steps[] = ['ok', 'Cache', 'Cache konfigurasi, rute, dan tampilan dibersihkan'];
            } catch (Throwable $e) {
                $steps[] = ['err', 'Cache', $e->getMessage()];
            }

            // 1. Hapus file hot jika ada (penyebab Vite error di production)
            if (is_file($webRoot.'/hot')) {
                @unlink($webRoot.'/hot');
                $steps[] = ['ok', 'Hot File', 'File public/hot dihapus (mode dev dinonaktifkan)'];
            }
            if (is_file($basePath.'/public/hot')) {
                @unlink($basePath.'/public/hot');
            }

            // 2. Sinkronkan folder build jika perlu
            $srcBuild = is_dir($webRoot.'/build') ? $webRoot.'/build' : (is_dir($basePath.'/public/build') ? $basePath.'/public/build' : null);
            if ($srcBuild !== null) {
                $dstBuild = ($srcBuild === $webRoot.'/build') ? $basePath.'/public/build' : $webRoot.'/build';
                if (! is_dir($dstBuild)) {
                    copyDirRecursively($srcBuild, $dstBuild);
                    $steps[] = ['ok', 'Asset Build', 'Folder build disinkronkan ke: '.$dstBuild];
                } else {
                    $steps[] = ['ok', 'Asset Build', 'Folder build tersedia di web root dan laksanaTech/public'];
                }
            } else {
                $steps[] = ['err', 'Asset Build', 'Folder build tidak ditemukan! Harap upload folder public/build dari komputer lokal.'];
            }

            // 3. Pastikan helpers.php termuat di index.php
            $indexFile = $webRoot.'/index.php';
            if (is_file($indexFile)) {
                $content = file_get_contents($indexFile);
                $changed = false;
                if (! str_contains($content, 'usePublicPath')) {
                    $search = '$app->handleRequest';
                    $replace = "\$app->usePublicPath(__DIR__);\n\n\$app->handleRequest";
                    if (str_contains($content, $search)) {
                        $content = str_replace($search, $replace, $content);
                        $changed = true;
                        $steps[] = ['ok', 'index.php', 'Ditambahkan $app->usePublicPath(__DIR__)'];
                    }
                }
                if ($changed) {
                    file_put_contents($indexFile, $content);
                }
            }

            // 4. Uji render halaman beranda
            try {
                $rendered = app(\App\Http\Controllers\Web\HomeController::class)->index()->render();
                $steps[] = ['ok', 'Uji Render Halaman Beranda', 'Berhasil me-render halaman beranda ('.strlen($rendered).' bytes). Error 500 teratasi!'];
            } catch (Throwable $e) {
                $steps[] = ['err', 'Uji Render Halaman Beranda Gagal', $e->getMessage().' di '.$e->getFile().':'.$e->getLine()];
            }

            // 5. Cek log Laravel
            $log = $basePath.'/storage/logs/laravel.log';
            if (is_file($log)) {
                $lines = array_slice(file($log) ?: [], -20);
                $logContent = trim(implode('', $lines));
                if ($logContent !== '') {
                    $steps[] = ['warn', 'laravel.log (20 baris terakhir)', $logContent];
                }
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
