<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DemoImageDownloader
{
    public function download(string $url, string $diskPath): ?string
    {
        if (Storage::disk('public')->exists($diskPath)) {
            return $diskPath;
        }

        try {
            $response = Http::timeout(45)
                ->withHeaders(['User-Agent' => 'LaksanaTech-Seeder/1.0'])
                ->get($url);

            if (! $response->successful()) {
                return null;
            }

            Storage::disk('public')->put($diskPath, $response->body());

            return $diskPath;
        } catch (\Throwable) {
            return null;
        }
    }

    public function downloadOrFallback(string $url, string $diskPath, string $seed): ?string
    {
        $stored = $this->download($url, $diskPath);

        if ($stored) {
            return $stored;
        }

        return $this->download(
            'https://picsum.photos/seed/'.urlencode($seed).'/960/640.jpg',
            $diskPath
        );
    }

    public function unsplash(string $photoId, int $width = 1200): string
    {
        return "https://images.unsplash.com/{$photoId}?w={$width}&q=80&auto=format&fit=crop";
    }
}
