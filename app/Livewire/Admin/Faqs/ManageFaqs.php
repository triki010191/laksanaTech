<?php

namespace App\Livewire\Admin\Faqs;

use App\Models\Faq;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola FAQ')]
class ManageFaqs extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public ?string $category = null;

    public string $question = '';

    public string $answer = '';

    public int $sort_order = 0;

    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'category', 'question', 'answer', 'sort_order', 'is_active']);
        $this->is_active = true;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $faq = Faq::query()->findOrFail($id);
        $this->editingId = $faq->id;
        $this->category = $faq->category;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->sort_order = $faq->sort_order;
        $this->is_active = $faq->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
        ]);

        $data = [
            'category' => $this->category,
            'question' => $this->question,
            'answer' => $this->answer,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            Faq::query()->findOrFail($this->editingId)->update($data);
        } else {
            Faq::query()->create($data);
        }

        session()->flash('status', 'FAQ disimpan.');
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        Faq::query()->findOrFail($id)->delete();
        session()->flash('status', 'FAQ dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.faqs.manage-faqs', [
            'faqs' => Faq::query()->orderBy('sort_order')->paginate(15),
        ]);
    }
}
