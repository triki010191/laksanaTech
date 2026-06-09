<?php

namespace App\Http\Controllers\Api\V1\Blog;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\BlogPostResource;
use App\Http\Responses\ApiResponse;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogPostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $posts = BlogPost::query()
            ->with(['category', 'author', 'tags'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate((int) $request->get('per_page', 10));

        return ApiResponse::success(BlogPostResource::collection($posts));
    }

    public function show(string $slug): JsonResponse
    {
        $post = BlogPost::query()
            ->with(['category', 'author', 'tags', 'seo'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $post) {
            return ApiResponse::error('Post not found', 404);
        }

        $post->increment('views_count');

        return ApiResponse::success(new BlogPostResource($post));
    }
}
