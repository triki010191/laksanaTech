<?php

namespace App\Livewire\Admin\Services;

use App\Enums\ServiceType;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Layanan')]
class ManageServices extends Component
{
    use WithFileUploads;
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public ?int $service_category_id = null;

    public string $category_icon = 'app';

    public string $name = '';

    public string $slug = '';

    public string $audience = '';

    public string $type = 'custom_software';

    public string $short_description = '';

    public string $description = '';

    public string $starting_price = '';

    public string $delivery_estimate = '';

    public string $features = '';

    public ?string $demo_url = null;

    public int $sort_order = 0;

    public bool $is_featured = false;

    public bool $is_active = true;

    public $preview_image;

    public ?string $currentPreviewUrl = null;

    public string $search = '';

    public function create(): void
    {
        $this->resetForm();
        $this->sort_order = (int) (Service::query()->max('sort_order') ?? 0) + 1;
        $this->service_category_id = ServiceCategory::query()->orderBy('sort_order')->value('id');
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $service = Service::query()->with('category')->findOrFail($id);

        $this->editingId = $service->id;
        $this->service_category_id = $service->service_category_id;
        $this->category_icon = $service->category?->icon ?? 'app';
        $this->name = $service->name;
        $this->slug = $service->slug;
        $this->audience = $service->audience ?? '';
        $this->type = $service->type->value;
        $this->short_description = $service->short_description ?? '';
        $this->description = $service->description ?? '';
        $this->starting_price = $service->starting_price ? (string) $service->starting_price : '';
        $this->delivery_estimate = $service->delivery_estimate ?? '';
        $this->features = is_array($service->features)
            ? implode(', ', $service->features)
            : '';
        $this->demo_url = $service->demo_url;
        $this->sort_order = $service->sort_order;
        $this->is_featured = $service->is_featured;
        $this->is_active = $service->is_active;
        $this->currentPreviewUrl = $service->preview_image ? upload_url($service->preview_image) : null;
        $this->preview_image = null;
        $this->showForm = true;
    }

    public function updatedName(string $value): void
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatedServiceCategoryId(?int $value): void
    {
        if (! $value) {
            return;
        }

        $category = ServiceCategory::query()->find($value);

        if ($category?->icon) {
            $this->category_icon = $category->icon;
        }
    }

    public function save(): void
    {
        $this->validate([
            'service_category_id' => 'required|exists:service_categories,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,'.($this->editingId ?? 'NULL').',id',
            'type' => 'required|in:'.implode(',', array_column(ServiceType::cases(), 'value')),
            'short_description' => 'nullable|string|max:1000',
            'description' => 'nullable|string',
            'starting_price' => 'nullable|numeric|min:0',
            'delivery_estimate' => 'nullable|string|max:255',
            'demo_url' => 'nullable|url|max:500',
            'sort_order' => 'required|integer|min:0|max:9999',
            'preview_image' => 'nullable|image|max:5120',
            'category_icon' => 'required|string|max:50',
        ]);

        $category = ServiceCategory::query()->findOrFail($this->service_category_id);
        $category->update([
            'icon' => $this->category_icon,
            'name' => $this->name,
        ]);

        $featureList = collect(explode(',', $this->features))
            ->map(fn (string $f) => trim($f))
            ->filter()
            ->values()
            ->all();

        $data = [
            'service_category_id' => $this->service_category_id,
            'name' => $this->name,
            'slug' => Str::slug($this->slug ?: $this->name),
            'type' => ServiceType::from($this->type),
            'audience' => $this->audience ?: null,
            'short_description' => $this->short_description ?: null,
            'description' => $this->description ?: null,
            'starting_price' => $this->starting_price !== '' ? $this->starting_price : null,
            'delivery_estimate' => $this->delivery_estimate ?: null,
            'features' => $featureList ?: null,
            'demo_url' => $this->demo_url ?: null,
            'sort_order' => $this->sort_order,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
        ];

        if ($this->preview_image) {
            $data['preview_image'] = $this->preview_image->store('services', 'public');
        }

        if ($this->editingId) {
            Service::query()->findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Layanan diperbarui.');
        } else {
            Service::query()->create($data);
            session()->flash('status', 'Layanan ditambahkan.');
        }

        $this->showForm = false;
        $this->resetForm();
    }

    public function delete(int $id): void
    {
        Service::query()->findOrFail($id)->delete();
        session()->flash('status', 'Layanan dihapus.');
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
        $query = Service::query()
            ->with('category')
            ->orderBy('sort_order');

        if ($this->search !== '') {
            $term = '%'.$this->search.'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('name', 'like', $term)
                    ->orWhere('short_description', 'like', $term);
            });
        }

        return view('livewire.admin.services.manage-services', [
            'services' => $query->paginate(12),
            'categories' => ServiceCategory::query()->orderBy('sort_order')->get(),
            'serviceTypes' => ServiceType::cases(),
            'iconOptions' => $this->iconOptions(),
        ]);
    }

    /** @return array<string, string> */
    private function iconOptions(): array
    {
        return [
            'camera' => 'Kamera / CCTV',
            'network' => 'Jaringan',
            'procurement' => 'Pengadaan',
            'sparkle' => 'Custom / Khusus',
            'government' => 'Instansi / Pemerintah',
            'monitor' => 'Software / Monitor',
            'app' => 'Aplikasi',
            'store' => 'Toko',
            'globe' => 'Website',
            'school' => 'Sekolah',
            'box' => 'Inventory / Box',
            'cart' => 'E-commerce',
            'layout' => 'Layout umum',
        ];
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'service_category_id', 'category_icon', 'name', 'slug', 'audience',
            'type', 'short_description', 'description', 'starting_price', 'delivery_estimate',
            'features', 'demo_url', 'sort_order', 'is_featured', 'is_active',
            'preview_image', 'currentPreviewUrl',
        ]);
        $this->type = ServiceType::CustomSoftware->value;
        $this->category_icon = 'app';
        $this->is_active = true;
    }
}
