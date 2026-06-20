<?php

namespace App\Support;

class LiveScreenshotUrl
{
    /** URL proxy lokal — browser hanya akses server sendiri (hindari blokir thum.io/microlink). */
    public static function proxy(string $url): string
    {
        return route('screenshot', ['url' => $url]);
    }

    public static function microlink(string $url): string
    {
        return 'https://api.microlink.io/?url='.rawurlencode(trim($url))
            .'&screenshot=true&meta=false&embed=screenshot.url';
    }

    public static function mshots(string $url, int $width = 800): string
    {
        return 'https://s0.wp.com/mshots/v1/'.rawurlencode(trim($url)).'?w='.$width;
    }

    public static function thumio(string $url, int $width = 800): string
    {
        return sprintf(
            'https://image.thum.io/get/width/%d/noanimate/%s',
            $width,
            trim($url)
        );
    }

    /**
     * Provider eksternal (dipanggil server-side dari proxy).
     *
     * @return list<string>
     */
    public static function externalProviders(string $url): array
    {
        return [
            self::microlink($url),
            self::mshots($url),
            self::thumio($url),
        ];
    }
}
