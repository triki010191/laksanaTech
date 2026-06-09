<?php

namespace App\Repositories\Contracts;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ServiceRepositoryInterface
{
    public function paginateActive(int $perPage = 12): LengthAwarePaginator;

    public function findBySlug(string $slug): ?Service;

    public function featured(int $limit = 6): Collection;
}
