<?php

/**
 * index.php — semua file Laravel ada di public_html (document root terkunci).
 * Ganti public_html/index.php dengan file ini.
 *
 * Struktur:
 *   public_html/index.php
 *   public_html/app/
 *   public_html/vendor/
 *   public_html/.env
 *   public_html/build/
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/vendor/autoload.php';

if (is_file(__DIR__.'/app/Support/helpers.php')) {
    require __DIR__.'/app/Support/helpers.php';
}

/** @var Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
