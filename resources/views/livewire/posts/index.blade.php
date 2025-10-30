<?php

use App\Models\Post;
use App\Models\Category;
use App\Exports\PostsExport;
use Illuminate\Support\Facades\Cache;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $parentCategoryFilter = 'all';
    public string $childCategoryFilter = 'all';
    public string $statusFilter = 'all';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public function mount(): void
    {
        if (session()->has('show_toast_message')) {
            $message = session('show_toast_message');
            $this->dispatch('show-toast', text: $message['text'], icon: $message['icon']);
        }
    }

    public function getPostsProperty()
    {
        $query = Post::query()
            ->select(['id','title','short_description','author_name','category_id','is_published','created_at','views_count','banner_image'])
            ->with(['category:id,title,parent_id','category.parent:id,title,parent_id'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($categoryQuery) {
                          $categoryQuery->where('title', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->parentCategoryFilter !== 'all', function ($q) {
                $categoryId = (int) $this->parentCategoryFilter;
                $childCategoryIds = Cache::remember(
                    'posts::child_ids_parent_' . $categoryId,
                    now()->addMinutes(10),
                    fn () => Category::where('parent_id', $categoryId)->pluck('id')->toArray()
                );
                $allCategoryIds = array_merge([$categoryId], $childCategoryIds);
                $q->whereIn('category_id', $allCategoryIds);
            })
            ->when($this->childCategoryFilter !== 'all', function ($q) {
                $q->where('category_id', (int) $this->childCategoryFilter);
            })
            ->when($this->statusFilter !== 'all', function($q) {
                if ($this->statusFilter === 'published') {
                    $q->published();
                } elseif ($this->statusFilter === 'draft') {
                    $q->where('is_published', false);
                }
            });

        match ($this->sortField) {
            'title' => $query->orderBy('title', $this->sortDirection),
            'created_at' => $query->orderBy('created_at', $this->sortDirection),
            'published_at' => $query->orderBy('published_at', $this->sortDirection),
            default => $query->orderBy('created_at', $this->sortDirection),
        };

        return $query->paginate($this->perPage);
    }

    public function getAllCategoriesForFilterProperty()
    {
        return Cache::remember('categories::for_post_filter', now()->addMinutes(10), function () {
            $categories = Category::query()
                ->select('id', 'title', 'parent_id')
                ->where('is_active', true)
                ->with(['parent' => function($query) { $query->select('id', 'title', 'parent_id');
                    // Eager load parent's parent to avoid lazy loading
                    // Eager load parent's parent to avoid lazy loading
                    $query->with('parent:id,title');
                }])
                ->orderBy('title')
                ->get();

            return $categories;
        });
    }

    public function getRootCategoriesProperty()
    {
        return $this->allCategoriesForFilter->whereNull('parent_id');
    }

    public function getChildCategoriesProperty()
    {
        return $this->allCategoriesForFilter->whereNotNull('parent_id');
    }

    protected $listeners = ['delete-post' => 'delete'];

    public function delete($postId): void
    {
        $post = Post::findOrFail($postId);
        $post->delete();
        $this->dispatch('show-success', message: 'Bài đăng đã được xóa thành công!');
    }

    public function togglePublished($id): void
    {
        $post = Post::findOrFail($id);
        if ($post->is_published) {
            $post->unpublish();
            $this->dispatch('show-success', message: 'Bài đăng đã được hủy xuất bản!');
        } else {
            $post->publish();
            $this->dispatch('show-success', message: 'Bài đăng đã được xuất bản!');
        }
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->parentCategoryFilter = 'all';
        $this->childCategoryFilter = 'all';
        $this->statusFilter = 'all';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    public function exportExcel()
    {
        try {
            $filters = [
                'search' => $this->search,
                'parentCategoryFilter' => $this->parentCategoryFilter,
                'childCategoryFilter' => $this->childCategoryFilter,
                'statusFilter' => $this->statusFilter,
                'sortField' => $this->sortField,
                'sortDirection' => $this->sortDirection,
            ];

            $filename = 'danh_sach_bai_dang_' . now()->format('Y-m-d_His') . '.xlsx';
            return Excel::download(new PostsExport($filters), $filename);
        } catch (\Exception $e) {
            $this->dispatch('show-toast', text: 'Có lỗi xảy ra khi xuất Excel: ' . $e->getMessage(), icon: 'error');
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedParentCategoryFilter(string $value): void
    {
        if ($value !== 'all') {
            $this->childCategoryFilter = 'all';
        }
        $this->resetPage();
    }

    public function updatedChildCategoryFilter(string $value): void
    {
        if ($value !== 'all') {
            $this->parentCategoryFilter = 'all';
        }
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }
}; ?>

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quản lý bài đăng</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Quản lý các bài đăng với đầy đủ tính năng
            </p>
        </div>
        <div class="flex gap-3">
           <flux:button variant="outline" wire:click="exportExcel">
                <span class="inline-flex items-center gap-x-1.5">
                    <flux:icon name="arrow-down-tray" class="size-4" />
                    <span>Xuất Excel</span>
                </span>
            </flux:button>
            <flux:button variant="primary" :href="route('posts.create')" wire:navigate>
                <flux:icon name="plus" class="size-4" />
                Thêm bài đăng mới
            </flux:button>
        </div>
    </div>

    {{-- Thông báo --}}
    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-4 border border-green-200 dark:bg-green-900 dark:border-green-700">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon name="check-circle" class="size-5 text-green-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('info'))
         <div class="rounded-md bg-blue-50 p-4 border border-blue-200 dark:bg-blue-900 dark:border-blue-700">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon name="information-circle" class="size-5 text-blue-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        {{ session('info') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    @if (session()->has('error'))
         <div class="rounded-md bg-red-50 p-4 border border-red-200 dark:bg-red-900 dark:border-red-700">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon name="x-circle" class="size-5 text-red-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- BỘ LỌC: Nền SÁNG + Viền Xanh Nổi bật --}}
    <div x-data="{ open: false }" class="rounded-lg bg-white dark:bg-gray-800 p-6 shadow-xl border-t-4 border-blue-500">
        <button @click="open = !open" class="flex justify-between items-center w-full">
            <span class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
                <flux:icon name="magnifying-glass" class="size-5" />
                Tìm kiếm & Lọc
            </span>
            <flux:icon name="chevron-down" class="size-5 text-gray-500 transition-transform" ::class="{ 'rotate-180': open }" />
        </button>

        <div x-show="open" x-collapse class="mt-6 space-y-4">
            {{-- Tìm kiếm --}}
            <div class="grid grid-cols-1">
                 <flux:field>
                    <flux:label>Tìm kiếm</flux:label>
                    <flux:input
                        wire:model.live.debounce.300ms="search"
                        placeholder="Tìm theo tiêu đề, danh mục..."
                        icon="magnifying-glass"
                    />
                </flux:field>
            </div>

            {{-- === THAY ĐỔI: Chia bộ lọc danh mục === --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Bộ lọc danh mục cha --}}
                <flux:field>
                    <flux:label>Lọc theo danh mục cha</flux:label>
                    <flux:select wire:model.live="parentCategoryFilter">
                        <option value="all">Tất cả danh mục cha</option>
                        @foreach($this->rootCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>

                {{-- Bộ lọc danh mục con --}}
                 <flux:field>
                    <flux:label>Lọc theo danh mục con</flux:label>
                    <flux:select wire:model.live="childCategoryFilter">
                        <option value="all">Tất cả danh mục con</option>
                        @foreach($this->childCategories as $category)
                            {{-- Hiển thị cả đường dẫn để phân biệt các con cùng tên --}}
                            <option value="{{ $category->id }}">{{ $category->full_path }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>

                {{-- Bộ lọc trạng thái (giữ nguyên) --}}
                <flux:field>
                    <flux:label>Lọc theo trạng thái</flux:label>
                    <flux:select wire:model.live="statusFilter">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="published">Đã xuất bản</option>
                        <option value="draft">Bản nháp</option>
                    </flux:select>
                </flux:field>

                {{-- Nút Reset (thêm vào cột cuối cùng nếu cần) --}}
                 <div class="md:col-start-3"> {{-- Đặt vào cột thứ 3 trên màn hình md trở lên --}}
                    <flux:field>
                        <flux:label class="invisible">Reset</flux:label>
                        <flux:button variant="outline" size="sm" wire:click="resetFilters" class="w-full">
                            <span class="inline-flex items-center justify-center gap-x-1.5 w-full">
                                <flux:icon name="arrow-path" class="size-4" />
                                <span>Reset bộ lọc</span>
                            </span>
                        </flux:button>
                    </flux:field>
                 </div>
            </div>
            {{-- === KẾT THÚC THAY ĐỔI === --}}
        </div>
    </div>
    {{-- KẾT THÚC BỘ LỌC --}}

    {{-- Bảng dữ liệu: Nền SÁNG + Shadow Nổi bật --}}
    <div class="rounded-lg bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 mx-auto max-w-7xl">
        @if($this->posts->count() > 0)
            <div class="posts-table-container">
                <table class="posts-table min-w-full divide-y divide-gray-200 dark:divide-gray-700 w-full">
                   {{-- Thead --}}
                   <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">STT</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                wire:click="sortBy('title')"
                            >
                                <div class="flex items-center gap-1">
                                    Tiêu đề
                                    @if($sortField === 'title')
                                        <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Danh mục</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tác giả</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                wire:click="sortBy('created_at')"
                            >
                                <div class="flex items-center gap-1">
                                    Ngày tạo
                                    @if($sortField === 'created_at')
                                        <flux:icon name="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="size-4" />
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    {{-- Tbody --}}
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->posts as $index => $post)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 w-full" wire:key="post-{{ $post->id }}">
                                {{-- Các cột dữ liệu (td) --}}
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ ($this->posts->currentPage() - 1) * $this->posts->perPage() + $index + 1 }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ Str::limit($post->title, 40) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($post->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ Str::limit($post->category->title, 30) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">Chưa phân loại</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ Str::limit($post->author_name ?: 'Chưa có', 15) }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        <div class="font-medium">{{ $post->created_date }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_time }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    @if($post->is_published)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Đã xuất bản
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            Bản nháp
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <flux:button
                                            variant="outline"
                                            size="sm"
                                            :href="route('posts.show', $post->id)"
                                            wire:navigate
                                        >
                                            <flux:icon name="document-text" class="size-4" />
                                        </flux:button>
                                        <flux:button
                                            variant="outline"
                                            size="sm"
                                            :href="route('posts.edit', $post->id)"
                                            wire:navigate
                                        >
                                            <flux:icon name="pencil" class="size-4" />
                                        </flux:button>
                                        <flux:button
                                            variant="outline"
                                            size="sm"
                                            wire:click="togglePublished({{ $post->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="togglePublished({{ $post->id }})"
                                        >
                                            <flux:icon name="{{ $post->is_published ? 'eye-slash' : 'eye' }}" class="size-4" />
                                        </flux:button>
                                        <flux:button
                                            variant="outline"
                                            size="sm"
                                            class="btn-delete-post"
                                            data-post-id="{{ $post->id }}"
                                        >
                                            <flux:icon name="trash" class="size-4 text-red-600" />
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->posts->links() }}
            </div>
        @else
            {{-- Thông báo không có bài đăng --}}
            <div class="text-center py-12">
                <flux:icon name="document-text" class="mx-auto size-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Không tìm thấy bài đăng</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Hiện tại chưa có bài đăng nào.
                </p>
                <div class="mt-6">
                    <flux:button variant="primary" :href="route('posts.create')" wire:navigate>
                        <flux:icon name="plus" class="size-4" />
                        Tạo bài đăng mới
                    </flux:button>
                </div>
            </div>
        @endif
    </div>

    {{-- CSS (Nếu cần tùy chỉnh thêm) --}}
    <style>
       .posts-table-container {
            overflow-x: auto;
            width: 100%;
        }
        .posts-table {
            width: 100%;
            min-width: 800px; /* Đảm bảo bảng có độ rộng tối thiểu */
        }

        /* Thêm hiệu ứng con trỏ cho các nút */
        button,
        a[role="button"],
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    
    {{-- Javascript (SweetAlert) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <script>
        document.addEventListener('livewire:init', () => {
            // Lắng nghe sự kiện show-success
            Livewire.on('show-success', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: event.message, // Sử dụng 'message'
                    timer: 3000,
                    showConfirmButton: false
                });
            });

            // Lắng nghe sự kiện show-toast (từ mount)
            Livewire.on('show-toast', (event) => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                Toast.fire({
                    icon: event.icon || 'success',
                    title: event.text
                });
            });
        });

        // Ủy quyền sự kiện click cho nút xóa để tránh inline JS
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete-post');
            if (!btn) return;
            const postId = parseInt(btn.getAttribute('data-post-id'));
            if (!postId) return;
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Bạn sẽ không thể hoàn tác hành động này!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Có, xóa!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('delete-post', { postId });
                }
            });
        });
    </script>
</div>