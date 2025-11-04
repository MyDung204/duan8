<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.frontend.app')]
#[Title('Tìm kiếm bài viết')]
class SearchPosts extends Component
{
    use WithPagination;

    #[Url(as: 'q', keep: true)]
    public $query = '';

    public $placeholder = 'Nhập từ khóa...';

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function render()
    {
        $posts = Post::published()
            ->when($this->query, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->query}%")
                      ->orWhere('short_description', 'like', "%{$this->query}%")
                      ->orWhere('content', 'like', "%{$this->query}%");
                });
            })
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('livewire.search-posts', [
            'posts' => $posts,
        ]);
    }
}