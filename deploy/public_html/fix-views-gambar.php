<?php

/**
 * Patch view lama asset('storage/...') → upload_url(...) di server.
 * https://laksanatech.com/fix-views-gambar.php?token=LaksanaSetup2026
 * HAPUS setelah berhasil.
 */

declare(strict_types=1);

if (($_GET['token'] ?? '') !== 'LaksanaSetup2026') {
    exit('?token=LaksanaSetup2026');
}

header('Content-Type: text/html; charset=utf-8');
$base = __DIR__;
$log = [];

$replacements = [
    "asset('storage/'.\$post->featured_image)" => 'upload_url($post->featured_image)',
    "asset('storage/'.\$template->preview_image)" => 'upload_url($template->preview_image)',
    "asset('storage/'.\$this->selectedTemplate->preview_image)" => 'upload_url($this->selectedTemplate->preview_image)',
    "asset('storage/'.\$tpl->preview_image)" => 'upload_url($tpl->preview_image)',
    "asset('storage/'.\$customLogo)" => 'upload_url($customLogo)',
];

$files = [
    'resources/views/pages/blog.blade.php',
    'resources/views/pages/blog/show.blade.php',
    'resources/views/pages/designs/_card.blade.php',
    'resources/views/pages/templates/_card.blade.php',
    'resources/views/pages/templates/show.blade.php',
    'resources/views/livewire/checkout/partials/step-template.blade.php',
    'resources/views/components/brand-logo.blade.php',
];

foreach ($files as $rel) {
    $path = $base.'/'.$rel;
    if (! is_file($path)) {
        $log[] = ['warn', $rel, 'File tidak ada — lewati'];
        continue;
    }
    $content = file_get_contents($path);
    $original = $content;
    foreach ($replacements as $from => $to) {
        $content = str_replace($from, $to, $content);
    }
    if ($content === $original) {
        if (str_contains($content, 'upload_url(')) {
            $log[] = ['ok', $rel, 'Sudah pakai upload_url'];
        } else {
            $log[] = ['warn', $rel, 'Tidak ada pola asset(storage) — cek manual'];
        }
        continue;
    }
    file_put_contents($path, $content);
    $log[] = ['ok', $rel, 'Diperbarui'];
}

// Pastikan helpers.php ada
$helpers = $base.'/app/Support/helpers.php';
if (! is_file($helpers)) {
    $log[] = ['err', 'helpers.php', 'Upload app/Support/helpers.php dari komputer'];
} else {
    $log[] = ['ok', 'helpers.php', 'Ada'];
}

$publicMedia = $base.'/app/Support/PublicMediaUrl.php';
if (! is_file($publicMedia)) {
    $log[] = ['err', 'PublicMediaUrl.php', 'Upload app/Support/PublicMediaUrl.php'];
} else {
    $log[] = ['ok', 'PublicMediaUrl.php', 'Ada'];
}

// .env PUBLIC_UPLOAD_PATH
$envFile = $base.'/.env';
if (is_file($envFile)) {
    $env = file_get_contents($envFile);
    if (! str_contains($env, 'PUBLIC_UPLOAD_PATH=uploads')) {
        if (! str_contains($env, 'PUBLIC_UPLOAD_PATH=')) {
            $env .= "\nPUBLIC_UPLOAD_PATH=uploads\n";
        } else {
            $env = preg_replace('/^PUBLIC_UPLOAD_PATH=.*$/m', 'PUBLIC_UPLOAD_PATH=uploads', $env);
        }
        file_put_contents($envFile, $env);
        $log[] = ['ok', '.env', 'PUBLIC_UPLOAD_PATH=uploads'];
    } else {
        $log[] = ['ok', '.env', 'Sudah benar'];
    }
}

foreach (glob($base.'/bootstrap/cache/*.php') ?: [] as $f) {
    @unlink($f);
}
foreach (glob($base.'/storage/framework/views/*.php') ?: [] as $f) {
    @unlink($f);
}
$log[] = ['ok', 'Cache', 'bootstrap/cache & views dibersihkan'];

?>
<!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><title>Fix Views</title>
<style>body{font-family:system-ui;max-width:680px;margin:2rem auto;padding:0 1rem}
.row{padding:.6rem .8rem;margin:.4rem 0;border-radius:.5rem;font-size:.9rem}
.ok{background:#f0fdf4}.err{background:#fef2f2}.warn{background:#fffbeb}code{font-size:.85rem}</style>
</head><body>
<h1>Perbaikan view gambar</h1>
<?php foreach ($log as [$s, $f, $m]): ?>
<div class="row <?= $s ?>"><strong><?= htmlspecialchars($f) ?></strong><br><?= htmlspecialchars($m) ?></div>
<?php endforeach; ?>
<p><strong>Selesai.</strong> Uji <a href="/blog">Blog</a> · <a href="/cari-design">Cari Design</a> · hard refresh (Ctrl+F5)</p>
<p>Hapus file <code>fix-views-gambar.php</code> setelah berhasil.</p>
</body></html>
