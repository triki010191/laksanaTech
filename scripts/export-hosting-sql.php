<?php

/**
 * Export SQL untuk hosting tanpa terminal (phpMyAdmin import).
 * Jalankan: php scripts/export-hosting-sql.php
 */

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../vendor/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Support\Str;

$out = [];
$out[] = '-- LaksanaTech — Update hosting 2026-06-20';
$out[] = '-- Import via phpMyAdmin (pilih database laksanatech → Import)';
$out[] = '-- Aman dijalankan berulang: pakai INSERT ... ON DUPLICATE KEY UPDATE';
$out[] = '';
$out[] = 'SET NAMES utf8mb4;';
$out[] = 'SET FOREIGN_KEY_CHECKS = 0;';
$out[] = '';

// --- Schema: kolom baru ---
$out[] = '-- 1) Tambah kolom live_demo_url';
$out[] = '-- Jalankan baris di bawah SEKALI. Jika error "Duplicate column name", lewati.';
$out[] = 'ALTER TABLE `templates` ADD COLUMN `live_demo_url` VARCHAR(255) NULL AFTER `demo_url`;';
$out[] = '';

// --- Template categories ---
$out[] = '-- 2) Kategori template';
foreach (TemplateCategory::query()->orderBy('sort_order')->get() as $cat) {
    $out[] = sprintf(
        "INSERT INTO `template_categories` (`slug`, `name`, `sort_order`, `is_active`, `uuid`, `created_at`, `updated_at`) VALUES (%s, %s, %d, %d, UUID(), NOW(), NOW()) ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `sort_order`=VALUES(`sort_order`), `is_active`=VALUES(`is_active`), `updated_at`=NOW();",
        sqlStr($cat->slug),
        sqlStr($cat->name),
        $cat->sort_order,
        $cat->is_active ? 1 : 0,
    );
}
$out[] = '';
$out[] = "UPDATE `template_categories` SET `is_active` = 0 WHERE `slug` = 'website';";
$out[] = '';

// --- Templates ---
$out[] = '-- 3) Template aktif (live demo URL)';
$activeSlugs = [];
foreach (Template::query()->with('category')->orderBy('id')->get() as $t) {
    $activeSlugs[] = $t->slug;
    $catId = TemplateCategory::where('slug', $t->category?->slug)->value('id') ?? 'NULL';
    $out[] = sprintf(
        "INSERT INTO `templates` (`template_category_id`, `name`, `slug`, `short_description`, `description`, `price`, `sale_price`, `demo_url`, `live_demo_url`, `tech_stack`, `is_featured`, `is_active`, `downloads_count`, `uuid`, `created_at`, `updated_at`) VALUES (%s, %s, %s, %s, %s, %s, %s, NULL, %s, %s, %d, %d, %d, UUID(), NOW(), NOW()) ON DUPLICATE KEY UPDATE `template_category_id`=VALUES(`template_category_id`), `name`=VALUES(`name`), `short_description`=VALUES(`short_description`), `description`=VALUES(`description`), `price`=VALUES(`price`), `sale_price`=VALUES(`sale_price`), `live_demo_url`=VALUES(`live_demo_url`), `tech_stack`=VALUES(`tech_stack`), `is_featured`=VALUES(`is_featured`), `is_active`=VALUES(`is_active`), `downloads_count`=VALUES(`downloads_count`), `updated_at`=NOW();",
        $catId,
        sqlStr($t->name),
        sqlStr($t->slug),
        sqlStr($t->short_description),
        sqlStr($t->description ?? $t->short_description),
        sqlNum($t->price),
        $t->sale_price ? sqlNum($t->sale_price) : 'NULL',
        sqlStr($t->live_demo_url),
        sqlStr($t->tech_stack ?? 'HTML, Bootstrap'),
        $t->is_featured ? 1 : 0,
        $t->is_active ? 1 : 0,
        (int) $t->downloads_count,
    );
}
$out[] = '';
if ($activeSlugs) {
    $slugList = implode(', ', array_map('sqlStr', $activeSlugs));
    $out[] = "-- Nonaktifkan template lama yang tidak ada di daftar baru";
    $out[] = "UPDATE `templates` SET `is_active` = 0 WHERE `slug` NOT IN ({$slugList});";
    $out[] = '';
}

// --- Portfolio categories ---
$out[] = '-- 4) Kategori portofolio';
foreach (PortfolioCategory::query()->orderBy('sort_order')->get() as $cat) {
    $out[] = sprintf(
        "INSERT INTO `portfolio_categories` (`slug`, `name`, `sort_order`, `is_active`, `uuid`, `created_at`, `updated_at`) VALUES (%s, %s, %d, %d, UUID(), NOW(), NOW()) ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `sort_order`=VALUES(`sort_order`), `is_active`=VALUES(`is_active`), `updated_at`=NOW();",
        sqlStr($cat->slug),
        sqlStr($cat->name),
        $cat->sort_order,
        $cat->is_active ? 1 : 0,
    );
}
$out[] = '';

// --- Portfolios ---
$out[] = '-- 5) Portofolio';
foreach (Portfolio::query()->with('category')->orderBy('sort_order')->get() as $p) {
    $catId = PortfolioCategory::where('slug', $p->category?->slug)->value('id') ?? 'NULL';
    $tech = $p->technologies ? sqlStr(json_encode($p->technologies, JSON_UNESCAPED_UNICODE)) : 'NULL';
    $completed = $p->completed_at ? sqlStr($p->completed_at->format('Y-m-d')) : 'NULL';
    $out[] = sprintf(
        "INSERT INTO `portfolios` (`portfolio_category_id`, `title`, `slug`, `client_name`, `project_url`, `short_description`, `technologies`, `completed_at`, `is_featured`, `is_published`, `sort_order`, `uuid`, `created_at`, `updated_at`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %d, %d, %d, UUID(), NOW(), NOW()) ON DUPLICATE KEY UPDATE `portfolio_category_id`=VALUES(`portfolio_category_id`), `title`=VALUES(`title`), `client_name`=VALUES(`client_name`), `project_url`=VALUES(`project_url`), `short_description`=VALUES(`short_description`), `technologies`=VALUES(`technologies`), `completed_at`=VALUES(`completed_at`), `is_featured`=VALUES(`is_featured`), `is_published`=VALUES(`is_published`), `sort_order`=VALUES(`sort_order`), `updated_at`=NOW();",
        $catId,
        sqlStr($p->title),
        sqlStr($p->slug),
        sqlStr($p->client_name),
        $p->project_url ? sqlStr($p->project_url) : 'NULL',
        sqlStr($p->short_description),
        $tech,
        $completed,
        $p->is_featured ? 1 : 0,
        $p->is_published ? 1 : 0,
        $p->sort_order,
    );
}
$out[] = '';

// --- Migration record ---
$out[] = '-- 6) Tandai migrasi sudah dijalankan';
$out[] = "INSERT INTO `migrations` (`migration`, `batch`) SELECT '2026_06_20_100000_add_live_demo_url_to_templates_table', COALESCE(MAX(`batch`), 0) + 1 FROM `migrations` WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_06_20_100000_add_live_demo_url_to_templates_table');";
$out[] = '';
$out[] = 'SET FOREIGN_KEY_CHECKS = 1;';
$out[] = '-- Selesai. Upload folder storage/app/public/ ke server lalu jalankan install-cpanel.php untuk storage link.';

$file = __DIR__.'/../deploy/hosting-update-2026-06-20.sql';
file_put_contents($file, implode("\n", $out)."\n");
echo "Written: {$file}\n";
echo 'Lines: '.count($out)."\n";

function sqlStr(?string $value): string
{
    if ($value === null) {
        return 'NULL';
    }

    return "'".addslashes($value)."'";
}

function sqlNum($value): string
{
    return (string) (float) $value;
}
