<?php

namespace App\Services\Commerce;

use App\Models\DomainExtension;

class DomainLookupService
{
    /** @var array<int, string> */
    private array $reserved = [
        'admin', 'www', 'mail', 'laksana', 'laksanatech', 'google', 'facebook',
    ];

    /**
     * @return array<int, array{extension_id: int, extension: string, label: string|null, full_domain: string, available: bool, price: float, compare_price: float|null, message: string}>
     */
    public function searchAll(string $domainName): array
    {
        $name = strtolower(trim($domainName));

        if (! preg_match('/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/', $name)) {
            return [];
        }

        return DomainExtension::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (DomainExtension $ext) => $this->buildResult($name, $ext))
            ->values()
            ->all();
    }

    public function check(string $domainName, string $extension): array
    {
        $name = strtolower(trim($domainName));
        $ext = str_starts_with($extension, '.') ? $extension : '.'.$extension;

        if (! preg_match('/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/', $name)) {
            return [
                'available' => false,
                'full_domain' => $name.$ext,
                'message' => 'Format domain tidak valid. Gunakan huruf, angka, dan tanda hubung.',
            ];
        }

        if (in_array($name, $this->reserved, true)) {
            return [
                'available' => false,
                'full_domain' => $name.$ext,
                'message' => 'Domain ini sudah dipesan / reserved.',
            ];
        }

        $available = $this->isAvailable($name, $ext);

        return [
            'available' => $available,
            'full_domain' => $name.$ext,
            'message' => $available
                ? 'Domain tersedia!'
                : 'Domain sudah digunakan. Coba nama lain.',
        ];
    }

    /**
     * @return array{extension_id: int, extension: string, label: string|null, full_domain: string, available: bool, price: float, compare_price: float|null, message: string}
     */
    private function buildResult(string $name, DomainExtension $extension): array
    {
        $ext = str_starts_with($extension->extension, '.')
            ? $extension->extension
            : '.'.$extension->extension;

        $available = ! in_array($name, $this->reserved, true) && $this->isAvailable($name, $ext);

        return [
            'extension_id' => $extension->id,
            'extension' => $ext,
            'label' => $extension->label,
            'full_domain' => $name.$ext,
            'available' => $available,
            'price' => (float) $extension->price,
            'compare_price' => $extension->compare_price ? (float) $extension->compare_price : null,
            'message' => $available ? 'Tersedia' : 'Sudah digunakan',
        ];
    }

    private function isAvailable(string $name, string $extension): bool
    {
        // Simulasi — ganti dengan ResellerClub / OpenSRS / Rumahweb API via config.
        $hash = crc32($name.$extension);

        return ($hash % 5) !== 0;
    }
}
