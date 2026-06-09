<?php

namespace App\Livewire\Admin\Templates;

use App\Models\Template;
use App\Models\TemplateCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Produk Template')]
class ManageTemplates extends Component
{
    use WithFileUploads;
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public ?int $template_category_id = null;

    public string $short_description = '';

    public string $description = '';

    public string $price = '';

    public string $sale_price = '';

    public ?string $demo_url = null;

    public bool $is_featured = false;

    public bool $is_active = true;

    public $preview_image;

    public ?string $currentPreviewUrl = null;

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $t = Template::query()->findOrFail($id);
        $this->editingId = $t->id;
        $this->name = $t->name;
        $this->slug = $t->slug;
        $this->template_category_id = $t->template_category_id;
        $this->short_description = $t->short_description ?? '';
        $this->description = $t->description ?? '';
        $this->price = (string) $t->price;
        $this->sale_price = $t->sale_price ? (string) $t->sale_price : '';
        $this->demo_url = $t->demo_url;
        $this->is_featured = $t->is_featured;
        $this->is_active = $t->is_active;
        $this->currentPreviewUrl = $t->preview_image ? asset('storage/'.$t->preview_image) : null;
        $this->preview_image = null;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'template_category_id' => 'required|exists:template_categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'preview_image' => 'nullable|image|max:4096',
        ]);

        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'template_category_id' => $this->template_category_id,
            'short_description' => $this->short_description,
            'description' => $this->description ?: $this->short_description,
            'price' => $this->price,
            'sale_price' => $this->sale_price ?: null,
            'demo_url' => $this->demo_url,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
        ];

        if ($this->preview_image) {
            $data['preview_image'] = $this->preview_image->store('templates', 'public');
        }

        if ($this->editingId) {
            Template::query()->findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Produk template diperbarui.');
        } else {
            Template::query()->create($data);
            session()->flash('status', 'Produk template ditambahkan.');
        }

        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Template::query()->findOrFail($id)->delete();
        session()->flash('status', 'Produk template dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.templates.manage-templates', [
            'templates' => Template::query()->with('category')->latest()->paginate(10),
            'categories' => TemplateCategory::query()->orderBy('name')->get(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'name', 'slug', 'template_category_id', 'short_description',
            'description', 'price', 'sale_price', 'demo_url', 'is_featured', 'is_active',
            'preview_image', 'currentPreviewUrl',
        ]);
        $this->is_active = true;
    }
}
