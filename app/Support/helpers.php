<?php

use App\Support\LiveScreenshotUrl;
use App\Support\PublicMediaUrl;

if (! function_exists('upload_url')) {
    /** URL publik untuk file di storage/app/public (hero, template, portofolio, dll.) */
    function upload_url(?string $path): ?string
    {
        return PublicMediaUrl::fromPath($path);
    }
}

if (! function_exists('screenshot_url')) {
    /** URL thumbnail via proxy lokal (server yang fetch screenshot eksternal). */
    function screenshot_url(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        return LiveScreenshotUrl::proxy($url);
    }
}
