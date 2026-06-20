<?php

$data = json_decode(file_get_contents(__DIR__.'/../storage/app/demo-urls.json'), true);
$seen = [];
$results = ['ok' => [], 'fail' => [], 'duplicate' => []];

foreach ($data as $cat) {
    foreach ($cat['templates'] as $t) {
        $url = rtrim($t['live_demo_url'], '/');
        $key = strtolower($url);

        if (isset($seen[$key])) {
            $results['duplicate'][] = ['category' => $cat['category'], 'name' => $t['name'], 'url' => $url, 'duplicate_of' => $seen[$key]];

            continue;
        }

        $seen[$key] = $t['name'];

        $ch = curl_init($url.'/');
        curl_setopt_array($ch, [
            CURLOPT_NOBODY => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; LaksanaTech/1.0)',
        ]);
        curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $entry = ['category' => $cat['category'], 'name' => $t['name'], 'url' => $url, 'status' => $code];

        if ($code >= 200 && $code < 400) {
            $results['ok'][] = $entry;
        } else {
            $results['fail'][] = $entry;
        }
    }
}

echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
