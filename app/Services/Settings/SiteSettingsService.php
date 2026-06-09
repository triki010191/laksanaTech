<?php

namespace App\Services\Settings;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cache;

class SiteSettingsService
{
    private const CACHE_KEY = 'site_settings_all';

    public function get(string $key, mixed $default = null): mixed
    {
        $all = $this->all();

        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    /** @return array<string, mixed> */
    public function all(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function (): array {
            return SiteSetting::query()
                ->pluck('value', 'key')
                ->toArray();
        });
    }

    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : (string) $value, 'group' => $group],
        );

        $this->flush();
    }

    /** @param array<string, mixed> $settings */
    public function setMany(array $settings, string $group = 'general'): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value, $group);
        }
    }

    public function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function normalizeWhatsappNumber(?string $number): string
    {
        $digits = preg_replace('/\D/', '', (string) $number);

        if ($digits === '') {
            return preg_replace('/\D/', '', (string) config('laksana.brand.whatsapp', '6285212455505'));
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return $digits;
    }

    public function whatsappUrl(?string $message = null): string
    {
        $number = $this->normalizeWhatsappNumber($this->get('whatsapp_number'));
        $text = $message ?? $this->get('whatsapp_float_message', 'Halo, saya ingin konsultasi website.');
        $query = http_build_query(['text' => $text]);

        return 'https://wa.me/'.$number.'?'.$query;
    }
}
