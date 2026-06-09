<?php

namespace App\Livewire\Admin\Blog;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Kelola Blog')]
class ManagePosts extends Component
{
    use WithPagination;

    public bool $showForm = false;

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public ?int $blog_category_id = null;

    public string $excerpt = '';

    public string $content = '';

    public string $status = 'draft';

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $post = BlogPost::query()->findOrFail($id);
        $this->editingId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->blog_category_id = $post->blog_category_id;
        $this->excerpt = $post->excerpt ?? '';
        $this->content = $post->content;
        $this->status = $post->status;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,published,archived',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug ?: Str::slug($this->title),
            'blog_category_id' => $this->blog_category_id,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->editingId) {
            BlogPost::query()->findOrFail($this->editingId)->update($data);
            session()->flash('status', 'Artikel diperbarui.');
        } else {
            $data['author_id'] = auth()->id();
            BlogPost::query()->create($data);
            session()->flash('status', 'Artikel dibuat.');
        }

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        BlogPost::query()->findOrFail($id)->delete();
        session()->flash('status', 'Artikel dihapus.');
    }

    public function updatedTitle(): void
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($this->title);
        }
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->blog_category_id = null;
        $this->excerpt = '';
        $this->content = '';
        $this->status = 'draft';
    }

    public function render()
    {
        return view('livewire.admin.blog.manage-posts', [
            'posts' => BlogPost::query()->with('category')->latest()->paginate(10),
            'categories' => BlogCategory::query()->orderBy('name')->get(),
        ]);
    }
}
