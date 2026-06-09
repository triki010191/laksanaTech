<?php

namespace App\Repositories\Contracts;

use App\Models\Template;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TemplateRepositoryInterface
{
    public function paginateActive(?string $categorySlug = null, int $perPage = 12): LengthAwarePaginator;

    public function findBySlug(string $slug): ?Template;
}
