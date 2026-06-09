<?php

namespace App\Support;

class PublicMediaUrl
{
    public static function fromPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'images/')) {
            $filesystemPath = public_path($path);
            $urlPath = '/'.$path;
        } else {
            $filesystemPath = storage_path('app/public/'.$path);
            $urlPath = '/storage/'.$path;
        }

        $version = is_file($filesystemPath) ? (string) filemtime($filesystemPath) : (string) time();

        if (! app()->runningInConsole() && request()->hasHeader('Host')) {
            return request()->getSchemeAndHttpHost()
                .request()->getBaseUrl()
                .$urlPath
                .'?v='.$version;
        }

        return asset(ltrim($urlPath, '/')).'?v='.$version;
    }
}
