<?php

/**
 * Generate PDF audit report from HTML template.
 * Usage: php scripts/generate-audit-pdf.php
 */

require __DIR__.'/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$htmlPath = __DIR__.'/../docs/AUDIT_LAKSANATECH_2026-06-14.html';
$pdfPath = __DIR__.'/../docs/AUDIT_LAKSANATECH_2026-06-14.pdf';

if (! is_file($htmlPath)) {
    fwrite(STDERR, "HTML template not found: {$htmlPath}\n");
    exit(1);
}

$html = file_get_contents($htmlPath);

$options = new Options;
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html, 'UTF-8');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

file_put_contents($pdfPath, $dompdf->output());

echo "PDF created: {$pdfPath} (".number_format(filesize($pdfPath)).' bytes)'."\n";
