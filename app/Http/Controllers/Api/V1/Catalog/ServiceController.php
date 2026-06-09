<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ServiceResource;
use App\Http\Responses\ApiResponse;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(
        private readonly ServiceRepositoryInterface $services,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->services->paginateActive((int) $request->get('per_page', 12));

        return ApiResponse::success(ServiceResource::collection($paginator));
    }

    public function show(string $slug): JsonResponse
    {
        $service = $this->services->findBySlug($slug);

        if (! $service) {
            return ApiResponse::error('Service not found', 404);
        }

        return ApiResponse::success(new ServiceResource($service));
    }
}
