<?php

namespace App\Livewire\Admin\Portfolios;

use App\Models\Portfolio;
use App\Models\PortfolioCategory;
use App\Models\PortfolioImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Portofolio')]
class ManagePortfolios extends Component
{
    use WithFileUploads;
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public ?int $portfolio_category_id = null;

    public string $title = '';

    public string $slug = '';

    public string $client_name = '';

    public string $project_url = '';

    public string $short_description = '';

    public string $case_study = '';

    public string $technologies = '';

    public ?string $completed_at = null;

    public int $sort_order = 0;

    public bool $is_featured = false;

    public bool $is_published = true;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $screenshots = [];

    public string $search = '';

    public function create(): void
    {
        $this->resetForm();
        $this->sort_order = (Portfolio::query()->max('sort_order') ?? 0) + 1;
        $this->portfolio_category_id = PortfolioCategory::query()->orderBy('sort_order')->value('id');
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $portfolio = Portfolio::query()->with('images')->findOrFail($id);

        $this->editingId = $portfolio->id;
        $this->portfolio_category_id = $portfolio->portfolio_category_id;
        $this->title = $portfolio->title;
        $this->slug = $portfolio->slug;
        $this->client_name = $portfolio->client_name;
        $this->project_url = $portfolio->project_url ?? '';
        $this->short_description = $portfolio->short_description ?? '';
        $this->case_study = $portfolio->case_study ?? '';
        $this->technologies = is_array($portfolio->technologies)
            ? implode(', ', $portfolio->technologies)
            : '';
        $this->completed_at = $portfolio->completed_at?->format('Y-m-d');
        $this->sort_order = $portfolio->sort_order;
        $this->is_featured = $portfolio->is_featured;
        $this->is_published = $portfolio->is_published;
        $this->screenshots = [];
        $this->showForm = true;
    }

    public function updatedTitle(string $value): void
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $this->validate([
            'portfolio_category_id' => 'required|exists:portfolio_categories,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:portfolios,slug,'.($this->editingId ?? 'NULL').',id',
            'client_name' => 'required|string|max:255',
            'project_url' => 'nullable|url|max:500',
            'short_description' => 'nullable|string|max:1000',
            'case_study' => 'nullable|string',
            'technologies' => 'nullable|string|max:500',
            'completed_at' => 'nullable|date',
            'sort_order' => 'required|integer|min:0|max:9999',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'screenshots.*' => 'nullable|image|max:5120',
        ]);

        $technologies = collect(explode(',', $this->technologies))
            ->map(fn (string $tech) => trim($tech))
            ->filter()
            ->values()
            ->all();

        $data = [
            'portfolio_category_id' => $this->portfolio_category_id,
            'title' => $this->title,
            'slug' => Str::slug($this->slug ?: $this->title),
            'client_name' => $this->client_name,
            'project_url' => $this->project_url ?: null,
            'short_description' => $this->short_description ?: null,
            'case_study' => $this->case_study ?: null,
            'technologies' => $technologies ?: null,
            'completed_at' => $this->completed_at ?: null,
            'sort_order' => $this->sort_order,
            'is_featured' => $this->is_featured,
            'is_published' => $this->is_published,
        ];

        if ($this->editingId) {
            $portfolio = Portfolio::query()->findOrFail($this->editingId);
            $portfolio->update($data);
            session()->flash('status', 'Portofolio diperbarui.');
        } else {
            $portfolio = Portfolio::query()->create($data);
            session()->flash('status', 'Portofolio ditambahkan.');
        }

        $this->storeScreenshots($portfolio);

        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $portfolio = Portfolio::query()->with('images')->findOrFail($id);

        foreach ($portfolio->images as $image) {
            $this->deleteStoredImage($image->path);
        }

        $portfolio->delete();

        session()->flash('status', 'Portofolio dihapus.');
    }

    public function deleteScreenshot(int $imageId): void
    {
        $image = PortfolioImage::query()->findOrFail($imageId);

        if ($this->editingId && $image->portfolio_id !== $this->editingId) {
            return;
        }

        $this->deleteStoredImage($image->path);
        $image->delete();

        session()->flash('status', 'Screenshot dihapus.');
    }

    public function cancel(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Portfolio::query()
            ->with(['category', 'images'])
            ->orderBy('sort_order');

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function ($builder) use ($term) {
                $builder
                    ->where('title', 'like', $term)
                    ->orWhere('client_name', 'like', $term);
            });
        }

        return view('livewire.admin.portfolios.manage-portfolios', [
            'portfolios' => $query->paginate(12),
            'categories' => PortfolioCategory::query()->orderBy('sort_order')->get(),
            'editingImages' => $this->editingId
                ? PortfolioImage::query()->where('portfolio_id', $this->editingId)->orderBy('sort_order')->get()
                : collect(),
        ]);
    }

    private function storeScreenshots(Portfolio $portfolio): void
    {
        if ($this->screenshots === []) {
            return;
        }

        $nextOrder = (int) $portfolio->images()->max('sort_order') + 1;

        foreach ($this->screenshots as $file) {
            if (! $file) {
                continue;
            }

            $path = $file->store('portfolio/'.$portfolio->id, 'public');

            PortfolioImage::query()->create([
                'portfolio_id' => $portfolio->id,
                'path' => $path,
                'sort_order' => $nextOrder++,
            ]);
        }

        $this->screenshots = [];
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'portfolio_category_id',
            'title',
            'slug',
            'client_name',
            'project_url',
            'short_description',
            'case_study',
            'technologies',
            'completed_at',
            'sort_order',
            'is_featured',
            'is_published',
            'screenshots',
        ]);
        $this->is_published = true;
    }

    private function deleteStoredImage(string $path): void
    {
        if (! str_starts_with($path, 'images/') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
