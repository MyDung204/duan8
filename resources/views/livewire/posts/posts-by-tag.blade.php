<?php

use App\Models\Tag;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public Tag $tag;

    public function mount($slug): void
    {
        $this->tag = Tag::where('slug', $slug)->firstOrFail();
    }

    public function with(): array
    {
        return [
            'posts' => $this->tag->posts()->published()->latest()->paginate(10),
        ];
    }
};
?>

<div>
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold my-8">Posts tagged with "{{ $tag->name }}"</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <x-post-card :post="$post" />
            @empty
                <p>No posts found for this tag.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    </div>
</div>
