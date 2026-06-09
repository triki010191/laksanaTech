@props(['status', 'type' => 'order'])

@php
$labels = match ($type) {
    'order' => [
        'pending' => ['Menunggu', 'amber'],
        'confirmed' => ['Dikonfirmasi', 'blue'],
        'in_progress' => ['Progres', 'purple'],
        'completed' => ['Selesai', 'green'],
        'cancelled' => ['Batal', 'slate'],
        'refunded' => ['Refund', 'rose'],
    ],
    'lead' => [
        'new' => ['Baru', 'amber'],
        'contacted' => ['Dihubungi', 'blue'],
        'qualified' => ['Qualified', 'purple'],
        'converted' => ['Konversi', 'green'],
        'closed' => ['Tutup', 'slate'],
    ],
    'template' => [
        'active' => ['Aktif', 'green'],
        'inactive' => ['Nonaktif', 'slate'],
        'featured' => ['Unggulan', 'amber'],
    ],
    default => [],
};
[$label, $color] = $labels[$status] ?? [ucfirst(str_replace('_', ' ', $status)), 'slate'];
@endphp

<span {{ $attributes->merge(['class' => "admin-badge admin-badge--{$color}"]) }}>{{ $label }}</span>
