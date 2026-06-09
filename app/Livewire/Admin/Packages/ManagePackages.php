<?php

namespace App\Livewire\Admin\Packages;

use App\Models\WebsitePackage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Kelola Paket Website')]
class ManagePackages extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $name = '';

    public string $slug = '';

    public string $description = '';

    public string $price = '';

    public string $billing_period = 'yearly';

    public string $featuresText = '';

    public int $sort_order = 0;

    public bool $is_featured = false;

    public bool $is_active = true;

    public function create(): void
    {
        $this->resetForm();
        $this->sort_order = (WebsitePackage::query()->max('sort_order') ?? 0) + 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $pkg = WebsitePackage::query()->findOrFail($id);
        $this->editingId = $pkg->id;
        $this->name = $pkg->name;
        $this->slug = $pkg->slug;
        $this->description = $pkg->description ?? '';
        $this->price = (string) $pkg->price;
        $this->billing_period = $pkg->billing_period;
        $this->featuresText = implode("\n", $pkg->features ?? []);
        $this->sort_order = $pkg->sort_order;
        $this->is_featured = $pkg->is_featured;
        $this->is_active = $pkg->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_period' => 'required|in:monthly,yearly',
        ]);

        $features = array_values(array_filter(array_map('trim', explode("\n", $this->featuresText))));

        $data = [
            'name' => $this->name,
            'slug' => $this->slug ?: Str::slug($this->name),
            'description' => $this->description,
            'price' => $this->price,
            'billing_period' => $this->billing_period,
            'features' => $features,
            'sort_order' => $this->sort_order,
            'is_featured' => $this->is_featured,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            WebsitePackage::query()->findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Paket diperbarui.');
        } else {
            WebsitePackage::query()->create($data);
            session()->flash('status', 'Paket ditambahkan.');
        }

        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        WebsitePackage::query()->findOrFail($id)->delete();
        session()->flash('status', 'Paket dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.packages.manage-packages', [
            'packages' => WebsitePackage::query()->orderBy('sort_order')->get(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'slug', 'description', 'price', 'billing_period', 'featuresText', 'sort_order', 'is_featured', 'is_active']);
        $this->billing_period = 'yearly';
        $this->is_active = true;
    }
}
