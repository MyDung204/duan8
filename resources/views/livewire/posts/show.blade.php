<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component
{
    public Post $post;

    public function mount($id): void
    {
        $this->post = Post::with('category')->findOrFail($id);
    }
}; ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Chi tiết bài đăng</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Xem thông tin chi tiết bài đăng
            </p>
        </div>
        
        <div class="flex gap-3">
            <flux:button variant="outline" :href="route('posts.index')" wire:navigate>
                <flux:icon name="arrow-left" class="size-4" />
                Quay lại danh sách
            </flux:button>
            
            <flux:button variant="primary" :href="route('posts.edit', $post->id)" wire:navigate>
                <flux:icon name="pencil" class="size-4" />
                Chỉnh sửa
            </flux:button>
        </div>
    </div>

    <!-- Post Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Banner Image -->
            @if($post->banner_image)
                <div class="rounded-lg overflow-hidden">
                    <img 
                        src="{{ $post->banner_image_url }}" 
                        alt="{{ $post->title }}"
                        class="w-full h-64 object-cover"
                    />
                </div>
            @endif

            <!-- Post Title -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                    {{ $post->title }}
                </h1>
                
                <!-- Post Meta -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-6">
                    <div class="flex items-center gap-2">
                        <flux:icon name="user" class="size-4" />
                        <span>Tác giả: {{ $post->author_name ?: 'Chưa có' }}</span>
                    </div>
                    
                    <div class="flex items-center gap-2">
                        <flux:icon name="calendar" class="size-4" />
                        <span>Ngày tạo: {{ $post->formatted_created_at }}</span>
                    </div>
                    
                    @if($post->published_at)
                        <div class="flex items-center gap-2">
                            <flux:icon name="clock" class="size-4" />
                            <span>Ngày xuất bản: {{ $post->formatted_published_at }}</span>
                        </div>
                    @endif
                    
                    @if($post->category)
                        <div class="flex items-center gap-2">
                            <flux:icon name="tag" class="size-4" />
                            <span>Danh mục: {{ $post->category->title }}</span>
                        </div>
                    @endif
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    @if($post->is_published)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <flux:icon name="check-circle" class="size-4 mr-1" />
                            Đã xuất bản
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                            <flux:icon name="clock" class="size-4 mr-1" />
                            Bản nháp
                        </span>
                    @endif
                </div>
            </div>

            <!-- Post Content -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Nội dung bài đăng</h2>
                <div class="prose dark:prose-invert max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Post Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Thông tin bài đăng</h3>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">ID:</span>
                        <span class="text-sm text-gray-900 dark:text-white ml-2">{{ $post->id }}</span>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Trạng thái:</span>
                        <span class="text-sm text-gray-900 dark:text-white ml-2">
                            {{ $post->is_published ? 'Đã xuất bản' : 'Bản nháp' }}
                        </span>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Danh mục:</span>
                        <span class="text-sm text-gray-900 dark:text-white ml-2">
                            {{ $post->category ? $post->category->title : 'Chưa phân loại' }}
                        </span>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tác giả:</span>
                        <span class="text-sm text-gray-900 dark:text-white ml-2">
                            {{ $post->author_name ?: 'Chưa có' }}
                        </span>
                    </div>
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Ngày tạo:</span>
                        <span class="text-sm text-gray-900 dark:text-white ml-2">
                            {{ $post->formatted_created_at }}
                        </span>
                    </div>
                    
                    @if($post->published_at)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Ngày xuất bản:</span>
                            <span class="text-sm text-gray-900 dark:text-white ml-2">
                                {{ $post->formatted_published_at }}
                            </span>
                        </div>
                    @endif
                    
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Cập nhật lần cuối:</span>
                        <span class="text-sm text-gray-900 dark:text-white ml-2">
                            {{ $post->updated_at->format('d/m/Y H:i:s') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Gallery Images -->
            @if($post->gallery_images && count($post->gallery_images) > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Thư viện ảnh</h3>
                    
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($post->gallery_image_urls as $index => $imageUrl)
                            <div class="relative group">
                                <img 
                                    src="{{ $imageUrl }}" 
                                    alt="Gallery Image {{ $index + 1 }}"
                                    class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                    onclick="openImageModal('{{ $imageUrl }}')"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Thao tác</h3>
                
                <div class="space-y-3">
                    <flux:button 
                        variant="primary" 
                        :href="route('posts.edit', $post->id)" 
                        wire:navigate
                        class="w-full"
                    >
                        <flux:icon name="pencil" class="size-4" />
                        Chỉnh sửa bài đăng
                    </flux:button>
                    
                    <flux:button 
                        variant="outline" 
                        :href="route('posts.index')" 
                        wire:navigate
                        class="w-full"
                    >
                        <flux:icon name="arrow-left" class="size-4" />
                        Quay lại danh sách
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button 
            onclick="closeImageModal()" 
            class="absolute top-4 right-4 text-white hover:text-gray-300 z-10"
        >
            <flux:icon name="x-mark" class="size-8" />
        </button>
        <img id="modalImage" src="" alt="Modal Image" class="max-w-full max-h-full rounded-lg">
    </div>
</div>

<script>
function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});
</script>
