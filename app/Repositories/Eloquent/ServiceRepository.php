<?php

namespace App\Repositories\Eloquent;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function paginateActive(int $perPage = 12): LengthAwarePaginator
    {
        return Service::query()
            ->with(['category', 'template'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    public function findBySlug(string $slug): ?Service
    {
        return Service::query()
            ->with(['category', 'seo', 'template'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->first();
    }

    public function featured(int $limit = 6): Collection
    {
        return Service::query()
            ->with(['category', 'template'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit($limit)
            ->get();
    }
}
