<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TemplateController extends Controller
{
    public function index(Request $request): View
    {
        $categories = TemplateCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $query = Template::query()
            ->where('is_active', true)
            ->with('category');

        if ($request->filled('q')) {
            $term = $request->string('q')->trim();
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $query->whereHas('category', fn ($builder) => $builder->where('slug', $request->category));
        }

        $templates = $query->orderByDesc('is_featured')->latest()->paginate(9)->withQueryString();

        return view('pages.designs.index', [
            'templates' => $templates,
            'categories' => $categories,
            'activeCategory' => $request->get('category', 'all'),
            'searchQuery' => $request->get('q', ''),
        ]);
    }

    public function show(string $slug): View
    {
        $template = Template::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        return view('pages.templates.show', compact('template'));
    }
}
