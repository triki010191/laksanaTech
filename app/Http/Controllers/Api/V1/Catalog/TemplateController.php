<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TemplateResource;
use App\Http\Responses\ApiResponse;
use App\Repositories\Contracts\TemplateRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function __construct(
        private readonly TemplateRepositoryInterface $templates,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->templates->paginateActive(
            $request->get('category'),
            (int) $request->get('per_page', 12),
        );

        return ApiResponse::success(TemplateResource::collection($paginator));
    }

    public function show(string $slug): JsonResponse
    {
        $template = $this->templates->findBySlug($slug);

        if (! $template) {
            return ApiResponse::error('Template not found', 404);
        }

        return ApiResponse::success(new TemplateResource($template));
    }
}
