<?php

/**
 * Perbaikan gambar 403 — LiteSpeed memblokir URL /storage/
 * Solusi: salin file ke folder /uploads/ dan set PUBLIC_UPLOAD_PATH=uploads
 *
 * https://laksanatech.com/fix-uploads.php?token=LaksanaSetup2026
 * HAPUS file ini setelah berhasil.
 */

declare(strict_types=1);

if (($_GET['token'] ?? '') !== 'LaksanaSetup2026') {
    exit('?token=LaksanaSetup2026');
}

header('Content-Type: text/html; charset=utf-8');
set_time_limit(300);

$base = __DIR__;
$src = $base.'/storage/app/public';
$dest = $base.'/uploads';
$envFile = $base.'/.env';
$log = [];

function copyDir(string $src, string $dest): int
{
    $count = 0;
    if (! is_dir($src)) {
        return 0;
    }
    if (! is_dir($dest)) {
        mkdir($dest, 0755, true);
    }
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($items as $item) {
        $target = $dest.DIRECTORY_SEPARATOR.$items->getSubPathName();
        if ($item->isDir()) {
            if (! is_dir($target)) {
                mkdir($target, 0755, true);
            }
        } else {
            if (! is_file($target) || filemtime($item->getPathname()) > filemtime($target)) {
                copy($item->getPathname(), $target);
                $count++;
            }
        }
    }

    return $count;
}

if (! is_dir($src)) {
    $log[] = ['err', 'Sumber', 'Folder tidak ada: storage/app/public — upload dari komputer lokal'];
} else {
    $n = copyDir($src, $dest);
    $log[] = ['ok', 'Salin file', "{$n} file disalin ke public_html/uploads/"];
}

if (is_file($envFile)) {
    $env = file_get_contents($envFile);
    if (! str_contains($env, 'PUBLIC_UPLOAD_PATH=')) {
        $env .= "\nPUBLIC_UPLOAD_PATH=uploads\n";
        file_put_contents($envFile, $env);
        $log[] = ['ok', '.env', 'Ditambahkan PUBLIC_UPLOAD_PATH=uploads'];
    } else {
        $env = preg_replace('/^PUBLIC_UPLOAD_PATH=.*$/m', 'PUBLIC_UPLOAD_PATH=uploads', $env);
        file_put_contents($envFile, $env);
        $log[] = ['ok', '.env', 'PUBLIC_UPLOAD_PATH=uploads sudah di-set'];
    }
} else {
    $log[] = ['err', '.env', 'File .env tidak ditemukan'];
}

foreach (glob($base.'/bootstrap/cache/*.php') ?: [] as $f) {
    @unlink($f);
}
$log[] = ['ok', 'Cache', 'bootstrap/cache dibersihkan — config akan dibaca ulang'];

$sample = glob($dest.'/hero/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
$testUrl = '';
if ($sample) {
    $testUrl = 'https://'.($_SERVER['HTTP_HOST'] ?? 'laksanatech.com').'/uploads/hero/'.basename($sample[0]);
}

?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Fix Uploads</title>
<style>body{font-family:system-ui;max-width:640px;margin:2rem auto;padding:0 1rem}
.box{padding:1rem;margin:.5rem 0;border-radius:.5rem}.ok{background:#f0fdf4;border:1px solid #bbf7d0}
.err{background:#fef2f2;border:1px solid #fecaca}code{background:#f1f5f9;padding:.1rem .35rem}</style>
</head><body>
<h1>Perbaikan folder uploads</h1>
<?php foreach ($log as [$s, $t, $m]): ?>
<div class="box <?= $s ?>"><strong><?= htmlspecialchars($t) ?></strong><br><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>

<div class="box ok">
<strong>Langkah berikutnya (wajib):</strong><ol>
<li>Upload file PHP yang di-update dari komputer ke server:
<code>app/Support/PublicMediaUrl.php</code>, <code>app/Support/helpers.php</code>,
<code>config/filesystems.php</code>, <code>composer.json</code></li>
<li>Di komputer jalankan <code>composer dump-autoload</code> lalu upload ulang folder <code>vendor/composer/</code>
atau seluruh <code>vendor/</code></li>
<li>Refresh <a href="/setup-laksana.php?token=LaksanaSetup2026">setup-laksana.php</a> untuk cache ulang</li>
<li>Uji URL: <?php if ($testUrl): ?><a href="<?= htmlspecialchars($testUrl) ?>"><?= htmlspecialchars($testUrl) ?></a><?php else: ?>belum ada file di uploads/hero<?php endif; ?></li>
<li>Hapus fix-uploads.php setelah selesai</li>
</ol>
</div>
</body></html>
