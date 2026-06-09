<?php

namespace App\Repositories\Eloquent;

use App\Models\Template;
use App\Repositories\Contracts\TemplateRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TemplateRepository implements TemplateRepositoryInterface
{
    public function paginateActive(?string $categorySlug = null, int $perPage = 12): LengthAwarePaginator
    {
        return Template::query()
            ->with('category')
            ->where('is_active', true)
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            })
            ->orderByDesc('is_featured')
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Template
    {
        return Template::query()
            ->with(['category', 'seo'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }
}
