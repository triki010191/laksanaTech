<?php

namespace App\Livewire\Admin\HeroSlides;

use App\Models\HeroSlide;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Kelola Hero Slider')]
class ManageHeroSlides extends Component
{
    use WithFileUploads;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $badge = '';

    public string $title = '';

    public string $emphasis = '';

    public string $description = '';

    public string $cta_primary_label = '';

    public string $cta_primary_route = '';

    public string $cta_secondary_label = '';

    public string $cta_secondary_route = '';

    public string $cta_secondary_url = '';

    public int $sort_order = 0;

    public bool $is_active = true;

    public $image;

    public ?string $currentImageUrl = null;

    public bool $replacingImage = false;

    /** @var array<int, string> */
    public array $routeOptions = [
        '' => '— Pilih route —',
        'contact' => 'Kontak',
        'portfolio' => 'Portofolio',
        'tutorial' => 'Tutorial',
        'services' => 'Layanan',
        'home' => 'Beranda',
        'blog' => 'Blog',
        'templates' => 'Templates',
    ];

    public function create(): void
    {
        $this->resetForm();
        $this->sort_order = (HeroSlide::query()->max('sort_order') ?? 0) + 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $slide = HeroSlide::query()->findOrFail($id);

        $this->editingId = $slide->id;
        $this->badge = $slide->badge ?? '';
        $this->title = $slide->title;
        $this->emphasis = $slide->emphasis ?? '';
        $this->description = $slide->description ?? '';
        $this->cta_primary_label = $slide->cta_primary_label ?? '';
        $this->cta_primary_route = $slide->cta_primary_route ?? '';
        $this->cta_secondary_label = $slide->cta_secondary_label ?? '';
        $this->cta_secondary_route = $slide->cta_secondary_route ?? '';
        $this->cta_secondary_url = $slide->cta_secondary_url ?? '';
        $this->sort_order = $slide->sort_order;
        $this->is_active = $slide->is_active;
        $this->currentImageUrl = $slide->image_url;
        $this->image = null;
        $this->replacingImage = false;
        $this->showForm = true;
    }

    public function enableImageReplace(): void
    {
        $this->replacingImage = true;
        $this->image = null;
    }

    public function cancelImageReplace(): void
    {
        $this->replacingImage = false;
        $this->image = null;
    }

    public function save(): void
    {
        $rules = [
            'title' => 'required|string|max:255',
            'badge' => 'nullable|string|max:120',
            'emphasis' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'cta_primary_label' => 'nullable|string|max:120',
            'cta_primary_route' => 'nullable|string|max:120',
            'cta_secondary_label' => 'nullable|string|max:120',
            'cta_secondary_route' => 'nullable|string|max:120',
            'cta_secondary_url' => 'nullable|url|max:500',
            'sort_order' => 'required|integer|min:0|max:999',
            'is_active' => 'boolean',
        ];

        if (! $this->editingId) {
            $rules['image'] = 'required|image|max:5120';
        } elseif ($this->image) {
            $rules['image'] = 'image|max:5120';
        }

        $this->validate($rules);

        $data = [
            'badge' => $this->badge ?: null,
            'title' => $this->title,
            'emphasis' => $this->emphasis ?: null,
            'description' => $this->description ?: null,
            'cta_primary_label' => $this->cta_primary_label ?: null,
            'cta_primary_route' => $this->cta_primary_route ?: null,
            'cta_secondary_label' => $this->cta_secondary_label ?: null,
            'cta_secondary_route' => $this->cta_secondary_route ?: null,
            'cta_secondary_url' => $this->cta_secondary_url ?: null,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];

        if ($this->image) {
            $path = $this->image->store('hero', 'public');
            $data['image_path'] = $path;
        }

        if ($this->editingId) {
            $slide = HeroSlide::query()->findOrFail($this->editingId);

            if (isset($data['image_path'])) {
                $this->deleteStoredImage($slide->image_path);
            }

            $slide->update($data);
            session()->flash('status', 'Slide diperbarui.');
        } else {
            HeroSlide::query()->create($data);
            session()->flash('status', 'Slide ditambahkan.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        $slide = HeroSlide::query()->findOrFail($id);
        $this->deleteStoredImage($slide->image_path);
        $slide->delete();

        session()->flash('status', 'Slide dihapus.');
    }

    public function cancel(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.admin.hero-slides.manage-hero-slides', [
            'slides' => HeroSlide::query()->orderBy('sort_order')->orderBy('id')->get(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId',
            'badge',
            'title',
            'emphasis',
            'description',
            'cta_primary_label',
            'cta_primary_route',
            'cta_secondary_label',
            'cta_secondary_route',
            'cta_secondary_url',
            'sort_order',
            'is_active',
            'image',
            'currentImageUrl',
            'replacingImage',
        ]);
        $this->is_active = true;
        $this->replacingImage = false;
    }

    private function deleteStoredImage(string $path): void
    {
        if (! str_starts_with($path, 'images/') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
