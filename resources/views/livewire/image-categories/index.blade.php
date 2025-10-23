<?php

use App\Models\ImageCategory;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;
    
    public function mount(): void
    {
        // Dùng session()->pull() để "LẤY và XÓA" tin nhắn
        $messageData = session()->pull('show_toast_message');

        if ($messageData) {
            // Nếu có tin nhắn, bắn sự kiện cho app.blade.php bắt
            $this->dispatch(
                'show-toast', 
                text: $messageData['text'], 
                icon: $messageData['icon']
            );
        }
    }

    // Thuộc tính cho tìm kiếm và bộ lọc
    public string $search = ''; // Từ khóa tìm kiếm
    
    // ===== THAY ĐỔI QUAN TRỌNG =====
    // Mặc định là 'all' (hiển thị tất cả cha và con)
    public string $parentFilter = 'all'; 
    
    public string $childFilter = 'all'; // Mặc định: 'all' (TẮT)
    
    public string $sortField = 'title'; // Trường sắp xếp (chỉ còn 'title')
    public string $sortDirection = 'asc'; // Hướng sắp xếp (asc/desc)
    public int $perPage = 10; // Số danh mục hiển thị mỗi trang

    // Thuộc tính tính toán - lấy danh sách danh mục
    public function getCategoriesProperty()
    {
        $query = ImageCategory::query()
            ->with('parent') // Load 'parent' để dùng cho hiển thị path
            ->withCount('children') // Load count để dùng cho confirm delete
            ->when($this->search, fn($q) => $q->search($this->search)) // Tìm kiếm theo từ khóa
            
            // Xử lý lọc cha: Chỉ chạy khi $parentFilter KHÔNG PHẢI LÀ 'all'
            ->when($this->parentFilter !== 'all', function ($q) {
                if ($this->parentFilter === 'is_parent') {
                    // Lọc: Tất cả danh mục cha (gốc)
                    $q->whereNull('parent_id');
                } else {
                    // Lọc: MỘT danh mục cha CỤ THỂ (theo ID của chính nó)
                    if (is_numeric($this->parentFilter)) {
                        $q->where('id', $this->parentFilter); 
                    }
                }
            })
            
            // Xử lý lọc con: Chỉ chạy khi $childFilter KHÔNG PHẢI LÀ 'all'
            ->when($this->childFilter !== 'all', function ($q) {
                if ($this->childFilter === 'is_child') {
                    // Lọc: Tất cả danh mục con
                    $q->whereNotNull('parent_id');
                } else {
                    // Lọc: Một danh mục con cụ thể (theo ID của chính nó)
                    if (is_numeric($this->childFilter)) {
                        $q->where('id', $this->childFilter);
                    }
                }
            });

        // Áp dụng sắp xếp
        match ($this->sortField) {
            'title' => $query->sortByTitle($this->sortDirection), // Sắp xếp theo tiêu đề
            default => $query->sortByTitle($this->sortDirection),
        };

        return $query->paginate($this->perPage); // Phân trang
    }

    // Thuộc tính tính toán - Lấy danh mục gốc (cha)
    public function getRootCategoriesProperty()
    {
        return ImageCategory::roots()->active()->orderBy('title')->get();
    }

    // Thuộc tính tính toán - Lấy TẤT CẢ danh mục con
    public function getAllChildCategoriesProperty()
    {
        // Lấy tất cả danh mục CON (parent_id != null) đang active
        return ImageCategory::whereNotNull('parent_id')
                            ->with('parent') // Load parent để phòng trường hợp cần dùng
                            ->active()
                            ->orderBy('title')
                            ->get();
    }

    // Phương thức sắp xếp
    public function sortBy(string $field): void
    {
        if ($field !== 'title') {
            $field = 'title';
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Xóa danh mục
    public function delete(int $id): void
    {
        $category = ImageCategory::findOrFail($id);
        $category->delete();
        $this->dispatch('show-toast', text: 'Danh mục đã được xóa thành công.', icon: 'success');
    }

    // Bật/tắt trạng thái hiển thị
    public function toggleActive(int $id): void
    {
        $category = ImageCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        $status = $category->is_active ? 'hiển thị' : 'ẩn';
        $this->dispatch('show-toast', text: "Danh mục đã được {$status}.", icon: 'success');
    }

    // Reset tất cả bộ lọc
    public function resetFilters(): void
    {
        $this->search = '';
        
        // ===== THAY ĐỔI QUAN TRỌNG =====
        // Reset về 'all' để hiển thị tất cả cha và con
        $this->parentFilter = 'all'; 
        $this->childFilter = 'all'; 
        
        $this->sortField = 'title';
        $this->sortDirection = 'asc';
        $this->resetPage(); // Reset về trang đầu
    }

    // Reset trang khi thay đổi tìm kiếm
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Khi lọc cha thay đổi -> TẮT lọc con
    public function updatedParentFilter(string $value): void
    {
        if ($value !== 'all') {
            $this->childFilter = 'all'; // Reset bộ lọc con về 'all' (tắt)
        }
        $this->resetPage();
    }

    // Khi lọc con thay đổi -> TẮT lọc cha
    public function updatedChildFilter(string $value): void
    {
        if ($value !== 'all') {
            $this->parentFilter = 'all'; // Reset bộ lọc cha về 'all' (tắt)
        }
        $this->resetPage();
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6 ">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quản lý danh mục</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Quản lý các danh mục với cấu trúc cấp cha-con
            </p>
        </div>
        <flux:button variant="primary" :href="route('image-categories.create')" wire:navigate>
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <flux:icon name="plus" class="size-4" />
                <span>Thêm danh mục mới</span>
            </div>
        </flux:button>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 space-y-4">
        
        <div class="grid grid-cols-1">
            <flux:field>
                <flux:label>Tìm kiếm</flux:label>
                
                <flux:input 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Tìm theo tiêu đề, mô tả..."
                    icon="magnifying-glass"
                    id="searchInput"
                    :spinner="false"
                    class="![&_svg:not(:first-child)]:hidden"
                />
            </flux:field>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <flux:field>
                <flux:label>Lọc danh mục cha</flux:label>
                <flux:select wire:model.live="parentFilter">
                    
                    {{-- ===== THAY ĐỔI QUAN TRỌNG ===== --}}
                    {{-- Thêm lại tùy chọn 'all' (Mặc định) --}}
                    <option value="all">Tất cả (Cha & Con)</option>
                    
                    <option value="is_parent">Tất cả danh mục cha (gốc)</option>
                    
                    @if($this->rootCategories->count() > 0)
                        <option value="is_parent" disabled>--- Hoặc lọc theo tên cha ---</option>
                        @foreach($this->rootCategories as $rootCategory)
                            <option value="{{ $rootCategory->id }}">{{ $rootCategory->title }}</option>
                        @endforeach
                    @endif
                    
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label>Lọc danh mục con</flux:label>
                <flux:select wire:model.live="childFilter">
                    
                    {{-- ===== THAY ĐỔI QUAN TRỌNG ===== --}}
                    {{-- Thêm lại tùy chọn 'all' (Mặc định) --}}
                    <option value="all">Tất cả (Cha & Con)</option>

                    <option value="is_child">Tất cả danh mục con</option>
                    
                    @if($this->allChildCategories->count() > 0)
                        <option value="all" disabled>--- Lọc theo tên con ---</option>
                        @foreach($this->allChildCategories as $childCategory)
                            <option value="{{ $childCategory->id }}">{{ $childCategory->title }}</option>
                        @endforeach
                    @endif
                </flux:select>
            </flux:field>

            <flux:field>
                <flux:label class="invisible">Reset</flux:label>
                
                <flux:button variant="outline" size="sm" wire:click="resetFilters" class="w-full">
                    <div style="display: flex; align-items: center; gap: 0.5rem; justify-content: center; padding: 5px 0">
                        <flux:icon name="arrow-path" class="size-4" />
                        <span>Reset</span>
                    </div>
                </flux:button>
            </flux:field>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        @if($this->categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">STT</th>
                            
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">Banner</th>
                            
                            <th 
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                wire:click="sortBy('title')"
                            >
                                Tiêu đề
                                @if($sortField === 'title')
                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-4 ml-1 inline" />
                                @endif
                            </th>
                            
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Danh mục</th>
                            
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->categories as $index => $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ ($this->categories->currentPage() - 1) * $this->categories->perPage() + $index + 1 }}
                                    </div>
                                </td>

                                <td class="px-4 py-6 text-center whitespace-nowrap">
                                    @if($category->banner_image)
                                        <img 
                                            src="{{ $category->banner_image_url }}" 
                                            alt="{{ $category->title }}"
                                            class="h-12 w-12 rounded-lg object-contain mx-auto"
                                        />
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center mx-auto">
                                            <flux:icon name="photo" class="size-6 text-gray-400" />
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $category->title }}
                                    </div>
                                </td>

                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($category->parent)
                                        {{-- Đây là danh mục Con (Cha -> Con) --}}
                                        <flux:badge variant="outline" size="sm">
                                            <span>{{ $category->parent->title }}</span>
                                            <span class="mx-1">→</span>
                                            <span class="font-medium text-gray-800 dark:text-gray-100">{{ $category->title }}</span>
                                        </flux:badge>
                                    @else
                                        {{-- Đây là danh mục Cha (Gốc) --}}
                                        <flux:badge variant="primary" size="sm">
                                            {{ $category->title }}
                                        </flux:badge>
                                    @endif
                                </td>

                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <flux:badge 
                                        variant="{{ $category->is_active ? 'success' : 'danger' }}"
                                    >
                                        {{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </flux:badge>
                                </td>

                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <flux:button 
                                            variant="outline" 
                                            size="sm"
                                            :href="route('image-categories.edit', $category->id)"
                                            wire:navigate
                                        >
                                            <flux:icon name="pencil" class="size-4" />
                                        </flux:button>
                                        
                                        <flux:button 
                                            variant="outline" 
                                            size="sm"
                                            wire:click="toggleActive({{ $category->id }})"
                                        >
                                            <flux:icon name="{{ $category->is_active ? 'eye-slash' : 'eye' }}" class="size-4" />
                                        </flux:button>
                                        
                                        <flux:button 
                                            variant="danger" 
                                            size="sm"
                                            onclick="confirmDelete({{ $category->id }}, '{{ addslashes($category->title) }}', {{ $category->children_count }})"
                                        >
                                            <flux:icon name="trash" class="size-4" />
                                        </flux:button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->categories->links() }}
            </div>
        @else
            <div 
                class="text-center" 
                style="padding-top: 3rem; padding-bottom: 6rem; padding-left: 1rem; padding-right: 1rem;"
            > 
                
                <flux:icon name="photo" class="mx-auto size-12 text-gray-400" />
                
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Chưa có danh mục nào</h3>
                
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                    Bắt đầu bằng cách tạo danh mục đầu tiên của bạn.
                </p>
                
                <div class="mt-6">
                    <flux:button variant="primary" :href="route('image-categories.create')" wire:navigate>
                        <div style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                            <flux:icon name="plus" class="size-4" />
                            <span>Thêm danh mục mới</span>
                        </div>
                    </flux:button>
                </div>
            </div>
        @endif
    </div>

@push('scripts')
<script>
    // Cập nhật hàm để chấp nhận tham số childrenCount
    function confirmDelete(id, title, childrenCount) {
        
        let warningText = `Bạn chuẩn bị xóa danh mục "${title}". Bạn sẽ không thể hoàn tác!`;
        let confirmButtonText = 'Vâng, xóa nó!';

        // Nếu có danh mục con, thay đổi nội dung cảnh báo
        if (childrenCount > 0) {
            warningText = `Danh mục "${title}" CÓ ${childrenCount} DANH MỤC CON. 
                           Xóa danh mục này sẽ GỠ LIÊN KẾT (set null) các danh mục con đó (chúng sẽ trở thành danh mục gốc). 
                           Bạn có chắc chắn không?`;
            confirmButtonText = 'Vâng, vẫn xóa!';
        }

        Swal.fire({
            title: 'Bạn có chắc chắn?',
            text: warningText, // Sử dụng nội dung cảnh báo động
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Gọi hàm 'delete' trong component Livewire
                @this.call('delete', id);
            }
        });
    }
</script>
@endpush
</div>