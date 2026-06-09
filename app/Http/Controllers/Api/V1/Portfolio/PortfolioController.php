<?php

namespace App\Http\Controllers\Api\V1\Portfolio;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PortfolioResource;
use App\Http\Responses\ApiResponse;
use App\Models\Portfolio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $items = Portfolio::query()
            ->with(['category', 'images'])
            ->where('is_published', true)
            ->when($request->get('featured'), fn ($q) => $q->where('is_featured', true))
            ->orderBy('sort_order')
            ->paginate((int) $request->get('per_page', 12));

        return ApiResponse::success(PortfolioResource::collection($items));
    }

    public function show(string $slug): JsonResponse
    {
        $portfolio = Portfolio::query()
            ->with(['category', 'images', 'seo'])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->first();

        if (! $portfolio) {
            return ApiResponse::error('Portfolio not found', 404);
        }

        return ApiResponse::success(new PortfolioResource($portfolio));
    }
}
