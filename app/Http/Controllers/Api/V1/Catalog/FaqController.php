<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FaqResource;
use App\Http\Responses\ApiResponse;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;

class FaqController extends Controller
{
    public function index(): JsonResponse
    {
        $faqs = Faq::query()->where('is_active', true)->orderBy('sort_order')->get();

        return ApiResponse::success(FaqResource::collection($faqs));
    }
}
