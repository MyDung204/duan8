<?php

use App\Models\Post;
use App\Models\Category;
use App\Exports\PostsExport;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public string $categoryFilter = 'all';
    public string $statusFilter = 'all';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public function mount(): void
    {
        // (Không có logic mount)
    }

    // Thuộc tính tính toán - lấy danh sách bài đăng
    public function getPostsProperty()
    {
        $query = Post::query()
            ->with('category')
            // Tìm kiếm theo Tiêu đề và Tên Danh mục
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // SỬA LỖI: Thêm $this->search
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function ($categoryQuery) {
                          // SỬA LỖI: Thêm $this->search
                          $categoryQuery->where('title', 'like', '%' . $this->search . '%');
                      });
                });
            })
            
            // Logic lọc danh mục
            ->when($this->categoryFilter !== 'all', function ($q) {
                $categoryId = (int) $this->categoryFilter;
                $childCategoryIds = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
                $allCategoryIds = array_merge([$categoryId], $childCategoryIds);
                $q->whereIn('category_id', $allCategoryIds);
            })

            // Lọc trạng thái
            ->when($this->statusFilter !== 'all', function($q) {
                if ($this->statusFilter === 'published') {
                    $q->published();
                } elseif ($this->statusFilter === 'draft') {
                    $q->where('is_published', false);
                }
            });

        // Áp dụng sắp xếp
        match ($this->sortField) {
            'title' => $query->orderBy('title', $this->sortDirection),
            'created_at' => $query->orderBy('created_at', $this->sortDirection),
            'published_at' => $query->orderBy('published_at', $this->sortDirection),
            default => $query->orderBy('created_at', $this->sortDirection),
        };

        return $query->paginate($this->perPage);
    }

    // Lấy danh sách tất cả danh mục
    public function getCategoriesProperty()
    {
        return Category::active()->with('parent')->orderBy('title')->get();
    }

    protected $listeners = ['delete-post' => 'delete'];

    // Method: Xóa bài đăng
    public function delete($postId): void
    {
        $post = Post::findOrFail($postId);
        $post->delete();
        
        // SỬA LỖI: Dùng -> thay vì .
        $this->dispatch('show-success', message: 'Bài đăng đã được xóa thành công!');
    }

    // Method: Thay đổi trạng thái xuất bản
    public function togglePublished($id): void
    {
        $post = Post::findOrFail($id);
        
        if ($post->is_published) {
            $post->unpublish();
            session()->flash('success', 'Bài đăng đã được hủy xuất bản!');
        } else {
            $post->publish();
            session()->flash('success', 'Bài đăng đã được xuất bản!');
        }
    }

    // Method: Sắp xếp
    public function sortBy($field): void
    {
        // SỬA LỖI: Dùng -> thay vì .
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // SỬA LỖI: Dùng -> thay vì .
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        // SỬA LỖI: Dùng -> thay vì .
        $this->resetPage();
    }

    // Method: Reset tất cả filters
    public function resetFilters(): void
    {
        $this->search = '';
        $this->categoryFilter = 'all';
        $this->statusFilter = 'all';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        // SỬA LỖI: Dùng -> thay vì .
        $this->resetPage();
    }

    // Method: Xuất Excel
    public function exportExcel()
    {
        try {
            $filters = [
                'search' => $this->search,
                'categoryFilter' => $this->categoryFilter,
                'statusFilter' => $this->statusFilter,
                'sortField' => $this->sortField,
                'sortDirection' => $this->sortDirection,
            ];

            $filename = 'danh_sach_bai_dang_' . now()->format('Y-m-d_His') . '.xlsx';
            
            return Excel::download(
                new PostsExport($filters),
                $filename
            );
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi xuất Excel: ' . $e->getMessage());
        }
    }

    // Reset trang khi thay đổi tìm kiếm
    public function updatedSearch(): void
    {
        // SỬA LỖI: Dùng -> thay vì .
        $this->resetPage();
    }
    
    public function updatedCategoryFilter(): void
    {
        // SỬA LỖI: Dùng -> thay vì .
        $this->resetPage();
    }

    public function updatedStatusFilter(): void
    {
        // SỬA LỖI: Dùng -> thay vì .
        $this->resetPage();
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quản lý bài đăng</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Quản lý các bài đăng với đầy đủ tính năng
            </p>
        </div>
        
        <div class="flex gap-3">
            <flux:button variant="outline" wire:click="exportExcel">
                <flux:icon name="arrow-down-tray" class="size-4" />
                Xuất Excel
            </flux:button>
            
            <flux:button variant="primary" :href="route('posts.create')" wire:navigate>
                <flux:icon name="plus" class="size-4" />
                Thêm bài đăng mới
            </flux:button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="rounded-md bg-green-50 p-4 dark:bg-green-900/20">
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
        <div class="rounded-md bg-blue-50 p-4 dark:bg-blue-900/20">
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
        <div class="rounded-md bg-red-50 p-4 dark:bg-red-900/20">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon name="exclamation-circle" class="size-5 text-red-400" />
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- BỘ LỌC ĐƠN GIẢN VÀ GỌN GÀNG --}}
    <div x-data="{ open: false }" class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
        
        <button @click="open = !open" class="flex justify-between items-center w-full">
            <span class="text-lg font-medium text-gray-900 dark:text-white flex items-center gap-2">
                <flux:icon name="magnifying-glass" class="size-5" />
                Tìm kiếm & Lọc
            </span>
            <flux:icon name="chevron-down" class="size-5 text-gray-500 transition-transform" ::class="{ 'rotate-180': open }" />
        </button>

        <div x-show="open" x-collapse class="mt-6 space-y-4">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <flux:field class="md:col-span-3">
                    <flux:label>Tìm kiếm</flux:label>
                    <flux:input 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Tìm theo tiêu đề, danh mục..."
                        icon="magnifying-glass"
                    />
                </flux:field>

                <flux:field>
                    <flux:label>Lọc theo danh mục</flux:label>
                    <flux:select wire:model.live="categoryFilter">
                        <option value="all">Tất cả danh mục</option>
                        @foreach($this->categories as $category)
                            <option value="{{ $category->id }}">{{ $category->full_path }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>
    
                <flux:field>
                    <flux:label>Lọc theo trạng thái</flux:label>
                    <flux:select wire:model.live="statusFilter">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="published">Đã xuất bản</option>
                        <option value="draft">Bản nháp</option>
                    </flux:select>
                </flux:field>
    
                <flux:field>
                    <flux:label class="invisible">Reset</flux:label>
                    <flux:button variant="outline" size="sm" wire:click="resetFilters" class="w-full">
                        <flux:icon name="arrow-path" class="size-4" />
                        Reset bộ lọc
                    </flux:button>
                </flux:field>
            </div>
        </div>
    </div>
    {{-- KẾT THÚC BỘ LỌC --}}

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 mx-auto max-w-7xl">
        @if($this->posts->count() > 0)
            <div class="posts-table-container">
                <table class="posts-table min-w-full divide-y divide-gray-200 dark:divide-gray-700 w-full">
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
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->posts as $index => $post)
                            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 w-full">
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
                                            {{ Str::limit($post->category->title, 20) }}
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
                                            <flux:icon name="eye" class="size-4" />
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
                                        >
                                            <flux:icon name="{{ $post->is_published ? 'eye-slash' : 'eye' }}" class="size-4" />
                                        </flux:button>
                                        
                                        <flux:button 
                                            variant="outline" 
                                            size="sm"
                                            onclick="confirmDelete({{ $post->id }})"
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

            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->posts->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <flux:icon name="document-text" class="mx-auto size-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Chưa có bài đăng nào</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                    Bắt đầu bằng cách tạo bài đăng đầu tiên của bạn.
                </p>
                <div class="mt-6">
                    <flux:button variant="primary" :href="route('posts.create')" wire:navigate>
                        <flux:icon name="plus" class="size-4" />
                        Thêm bài đăng mới
                    </flux:button>
                </div>
            </div>
        @endif
    </div>

    {{-- CSS CĂN CHỈNH ĐỀU --}}
    <style>
        .posts-table {
            /* table-layout: fixed; */ /* Bỏ layout fixed để tự căn */
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0 auto;
        }
        
        .posts-table tbody tr {
            width: 100% !important;
            background-color: white !important;
            height: 60px; /* Cố định chiều cao hàng */
        }
        
        .dark .posts-table tbody tr {
            background-color: rgb(31 41 55) !important;
        }
        
        .posts-table tbody tr:hover {
            background-color: rgb(249 250 251) !important;
        }
        
        .dark .posts-table tbody tr:hover {
            background-color: rgb(55 65 81) !important;
        }
        
        .posts-table-container {
            width: 100%;
            overflow-x: auto; /* Dùng 'auto' để có scroll ngang nếu cần */
            display: flex;
            justify-content: center;
        }
        
        .posts-table th, .posts-table td {
            vertical-align: middle;
        }
        
        .dark .posts-table th, .dark .posts-table td {
            /* border-right: 1px solid #374151; */
        }
        
        .posts-table thead th {
            border-bottom: 2px solid #d1d5db;
        }
        
        .dark .posts-table thead th {
            border-bottom: 2px solid #4b5563;
        }
        
        .posts-table tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .dark .posts-table tbody tr {
            border-bottom: 1px solid #374151;
        }
        
        .posts-table th:last-child, .posts-table td:last-child {
            border-right: none;
        }
        
        .posts-table th, .posts-table td {
            padding: 12px 10px !important; /* Tăng padding ngang 1 chút */
        }
        
        /* Responsive cho mobile (đã cập nhật) */
        @media (max-width: 768px) {
            /* Ẩn tác giả (cột 4) */
            .posts-table th:nth-child(4), .posts-table td:nth-child(4) { display: none; } 
        }
        
        .posts-table td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; /* Giữ 1 hàng */
        }
        
        .posts-table td:nth-child(2) {
            padding-left: 8px !important;
            padding-right: 8px !important;
        }
        
        /* Cho phép wrap cho các cột quan trọng (cập nhật index) */
        .posts-table td:nth-child(2), /* Tiêu đề */
        .posts-table td:nth-child(3), /* Danh mục */
        .posts-table td:nth-child(6) { /* Trạng thái */
            white-space: normal;
            word-wrap: break-word;
            line-height: 1.4;
        }
        
        /* Đảm bảo cột thao tác hiển thị đầy đủ các nút (cập nhật index) */
        .posts-table td:nth-child(7) {
            min-width: 130px !important; /* Tăng min-width 1 chút */
            white-space: nowrap; /* Bắt buộc 1 hàng */
        }
        
        .posts-table td:nth-child(7) .flex {
            flex-wrap: nowrap;
            gap: 4px;
        }
        
        .posts-table td:nth-child(7) button {
            min-width: 28px;
            height: 28px;
            padding: 4px;
        }

        /* Căn giữa cột STT và Trạng thái */
        .posts-table th:nth-child(1), .posts-table td:nth-child(1),
        .posts-table th:nth-child(6), .posts-table td:nth-child(6) {
            text-align: center;
        }

        /* Căn trái cho các cột còn lại (ngoại trừ thao tác) */
        .posts-table th:nth-child(2), .posts-table td:nth-child(2),
        .posts-table th:nth-child(3), .posts-table td:nth-child(3),
        .posts-table th:nth-child(4), .posts-table td:nth-child(4),
        .posts-table th:nth-child(5), .posts-table td:nth-child(5) {
            text-align: left;
        }

        /* Căn giữa cột thao tác */
        .posts-table th:nth-child(7), .posts-table td:nth-child(7) {
            text-align: center;
        }

    </style>
    {{-- ===== KẾT THÚC SỬA CSS ===== --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-success', (event) => {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: event.message,
                timer: 3000,
                showConfirmButton: false
            });
        });
    });

    // Confirm delete
    function confirmDelete(postId) {
        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: "Bạn sẽ không thể hoàn tác hành động này!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Có, xóa!',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('delete-post', { postId: postId });
            }
        });
    }
    </script>
</div>