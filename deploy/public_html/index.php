<?php

/**
 * index.php untuk cPanel — document root terkunci ke public_html.
 * Letakkan file ini di: /home/laksanat/public_html/index.php
 * Laravel core di:     /home/laksanat/laksanaTech/
 */

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$laravelRoot = is_file(__DIR__.'/../laksanaTech/artisan')
    ? __DIR__.'/../laksanaTech'
    : (is_file(__DIR__.'/laksanaTech/artisan') ? __DIR__.'/laksanaTech' : null);

if ($laravelRoot === null) {
    http_response_code(500);
    exit('Laravel tidak ditemukan. Pastikan folder laksanaTech ada di /home/laksanat/laksanaTech/');
}

if (file_exists($maintenance = $laravelRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $laravelRoot.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $laravelRoot.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
