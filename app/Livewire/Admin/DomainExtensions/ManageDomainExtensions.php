<?php

namespace App\Livewire\Admin\DomainExtensions;

use App\Models\DomainExtension;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Kelola Ekstensi Domain')]
class ManageDomainExtensions extends Component
{
    public bool $showForm = false;

    public ?int $editingId = null;

    public string $extension = '';

    public string $label = '';

    public string $price = '';

    public int $sort_order = 0;

    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'extension', 'label', 'price', 'sort_order', 'is_active']);
        $this->is_active = true;
        $this->sort_order = (DomainExtension::query()->max('sort_order') ?? 0) + 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $ext = DomainExtension::query()->findOrFail($id);
        $this->editingId = $ext->id;
        $this->extension = $ext->extension;
        $this->label = $ext->label ?? '';
        $this->price = (string) $ext->price;
        $this->sort_order = $ext->sort_order;
        $this->is_active = $ext->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'extension' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
        ]);

        $ext = str_starts_with($this->extension, '.') ? $this->extension : '.'.$this->extension;

        $data = [
            'extension' => $ext,
            'label' => $this->label ?: null,
            'price' => $this->price,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            DomainExtension::query()->findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Ekstensi diperbarui.');
        } else {
            DomainExtension::query()->create($data);
            session()->flash('status', 'Ekstensi ditambahkan.');
        }

        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        DomainExtension::query()->findOrFail($id)->delete();
        session()->flash('status', 'Ekstensi dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.domain-extensions.manage-domain-extensions', [
            'extensions' => DomainExtension::query()->orderBy('sort_order')->get(),
        ]);
    }
}
