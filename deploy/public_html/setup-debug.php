<?php

/**
 * Debug setup — langkah demi langkah, tampilkan error jelas.
 * URL: https://laksanatech.com/setup-debug.php?token=LaksanaSetup2026
 * HAPUS setelah selesai.
 */

declare(strict_types=1);

$token = 'LaksanaSetup2026';
if (($_GET['token'] ?? '') !== $token) {
    exit('Tambahkan ?token='.$token);
}

header('Content-Type: text/plain; charset=utf-8');
ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(120);

$base = __DIR__;
$log = static function (string $msg): void {
    echo $msg."\n";
    if (function_exists('flush')) {
        flush();
    }
};

$log('=== LaksanaTech Setup Debug ===');
$log('PHP: '.PHP_VERSION);
$log('Base: '.$base);
$log('');

$checks = [
    'artisan' => $base.'/artisan',
    '.env' => $base.'/.env',
    'vendor' => $base.'/vendor/autoload.php',
    'bootstrap/app.php' => $base.'/bootstrap/app.php',
];

foreach ($checks as $name => $path) {
    $log(sprintf('[%-20s] %s %s', $name, is_file($path) ? 'OK' : 'MISSING', $path));
}

$log('');
$log('--- Permission ---');
foreach (['storage', 'bootstrap/cache', 'storage/logs', 'storage/framework'] as $dir) {
    $p = $base.'/'.$dir;
    $log(sprintf('[%-20s] exists=%s writable=%s', $dir, is_dir($p) ? 'yes' : 'no', is_writable($p) ? 'yes' : 'no'));
}

$log('');
$log('--- Hapus cache bootstrap ---');
foreach (glob($base.'/bootstrap/cache/*.php') ?: [] as $f) {
    $log('delete: '.basename($f).' → '.(@unlink($f) ? 'ok' : 'fail'));
}

$log('');
$log('--- Load vendor ---');
try {
    require $base.'/vendor/autoload.php';
    $log('vendor/autoload.php: OK');
} catch (Throwable $e) {
    $log('FATAL vendor: '.$e->getMessage());
    exit(1);
}

$log('');
$log('--- Load bootstrap/app.php ---');
try {
    $app = require $base.'/bootstrap/app.php';
    $log('bootstrap/app.php: OK ('.get_class($app).')');
} catch (Throwable $e) {
    $log('FATAL bootstrap: '.$e->getMessage());
    $log('File: '.$e->getFile().':'.$e->getLine());
    exit(1);
}

$log('');
$log('--- Kernel bootstrap ---');
try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    $log('Kernel bootstrap: OK');
} catch (Throwable $e) {
    $log('FATAL kernel: '.$e->getMessage());
    $log('File: '.$e->getFile().':'.$e->getLine());
    $log('');
    $log('Trace:');
    $log($e->getTraceAsString());
    exit(1);
}

$log('');
$log('--- Database ---');
try {
    Illuminate\Support\Facades\Artisan::call('migrate:status');
    $log('migrate:status: OK');
    $log(trim(Illuminate\Support\Facades\Artisan::output()));
} catch (Throwable $e) {
    $log('DB ERROR: '.$e->getMessage());
}

$log('');
$log('--- Selesai — jika sampai sini Laravel jalan. Hapus setup-debug.php ---');
