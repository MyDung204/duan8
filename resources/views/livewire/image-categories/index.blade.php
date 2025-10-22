<?php

use App\Models\ImageCategory;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // Thuộc tính cho tìm kiếm và bộ lọc
    public string $search = ''; // Từ khóa tìm kiếm
    public ?int $parentFilter = null; // Lọc theo danh mục cha
    public string $categoryTypeFilter = 'all'; // Lọc theo loại danh mục (all/parent/child)
    public string $sortField = 'title'; // Trường sắp xếp (title/created_at)
    public string $sortDirection = 'asc'; // Hướng sắp xếp (asc/desc)
    public int $perPage = 10; // Số danh mục hiển thị mỗi trang

    // Thuộc tính tính toán - lấy danh sách danh mục
    public function getCategoriesProperty()
    {
        $query = ImageCategory::query()
            ->with('parent') // Load danh mục cha để hiển thị
            ->when($this->search, fn($q) => $q->search($this->search)) // Tìm kiếm theo từ khóa
            ->when($this->parentFilter !== null, fn($q) => $q->byParent($this->parentFilter)) // Lọc theo danh mục cha
            ->when($this->categoryTypeFilter !== 'all', fn($q) => $q->byType($this->categoryTypeFilter)); // Lọc theo loại

        // Áp dụng sắp xếp
        match ($this->sortField) {
            'title' => $query->sortByTitle($this->sortDirection), // Sắp xếp theo tiêu đề
            'created_at' => $query->orderBy('created_at', $this->sortDirection), // Sắp xếp theo ngày tạo
            default => $query->sortByTitle($this->sortDirection),
        };

        return $query->paginate($this->perPage); // Phân trang
    }

    // Lấy danh sách danh mục cha để hiển thị trong dropdown
    public function getParentCategoriesProperty()
    {
        return ImageCategory::active()
            ->roots() // Chỉ lấy danh mục gốc
            ->orderBy('title')
            ->get();
    }

    // Phương thức sắp xếp
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            // Nếu đang sắp xếp theo trường này, đảo ngược hướng
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Nếu sắp xếp theo trường mới, đặt hướng mặc định là asc
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Xóa danh mục
    public function delete(int $id): void
    {
        $category = ImageCategory::findOrFail($id);
        
        // Kiểm tra có danh mục con không
        if ($category->hasChildren()) {
            session()->flash('error', 'Không thể xóa danh mục có danh mục con.');
            return;
        }

        $category->delete();
        session()->flash('success', 'Danh mục đã được xóa thành công.');
    }

    // Bật/tắt trạng thái hiển thị
    public function toggleActive(int $id): void
    {
        $category = ImageCategory::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        
        $status = $category->is_active ? 'hiển thị' : 'ẩn';
        session()->flash('success', "Danh mục đã được {$status}.");
    }

    // Reset tất cả bộ lọc
    public function resetFilters(): void
    {
        $this->search = '';
        $this->parentFilter = null;
        $this->categoryTypeFilter = 'all';
        $this->sortField = 'title';
        $this->sortDirection = 'asc';
        $this->resetPage(); // Reset về trang đầu
    }

    // Reset trang khi thay đổi tìm kiếm
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // Reset trang khi thay đổi bộ lọc danh mục cha
    public function updatedParentFilter(): void
    {
        $this->resetPage();
    }

    // Reset trang khi thay đổi loại danh mục
    public function updatedCategoryTypeFilter(): void
    {
        $this->resetPage();
    }
}; ?>

<div class="space-y-6">
    <!-- Phần tiêu đề và nút thêm mới -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quản lý danh mục</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Quản lý các danh mục với cấu trúc cấp cha-con
            </p>
        </div>
        <flux:button variant="primary" :href="route('image-categories.create')" wire:navigate>
            <flux:icon name="plus" class="size-4" />
            Thêm danh mục mới
        </flux:button>
    </div>

    <!-- Phần bộ lọc và tìm kiếm -->
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Ô tìm kiếm -->
            <flux:field>
                <flux:label>Tìm kiếm</flux:label>
                        <flux:input 
                            wire:model.live.debounce.300ms="search" 
                            placeholder="Tìm theo tiêu đề, mô tả..."
                            icon="magnifying-glass"
                        />
            </flux:field>

            <!-- Bộ lọc theo danh mục cha -->
            <flux:field>
                <flux:label>Danh mục cha</flux:label>
                <flux:select wire:model.live="parentFilter">
                    <option value="">Tất cả</option>
                    <option value="null">Danh mục gốc</option>
                    @foreach($this->parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->title }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <!-- Bộ lọc theo loại danh mục -->
            <flux:field>
                <flux:label>Loại danh mục</flux:label>
                <flux:select wire:model.live="categoryTypeFilter">
                    <option value="all">Tất cả</option>
                    <option value="parent">Danh mục cha</option>
                    <option value="child">Danh mục con</option>
                </flux:select>
            </flux:field>

            <!-- Bộ lọc sắp xếp -->
            <flux:field>
                <flux:label>Sắp xếp</flux:label>
                <flux:select wire:model.live="sortField">
                    <option value="title">Tiêu đề</option>
                    <option value="created_at">Ngày tạo</option>
                </flux:select>
            </flux:field>
        </div>

        <!-- Nút sắp xếp và reset -->
        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <flux:button 
                    variant="outline" 
                    size="sm"
                    wire:click="sortBy('{{ $sortField }}')"
                >
                    <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-4" />
                    {{ $sortDirection === 'asc' ? 'A-Z' : 'Z-A' }}
                </flux:button>
            </div>
            
            <flux:button variant="outline" size="sm" wire:click="resetFilters">
                <flux:icon name="arrow-path" class="size-4" />
                Reset bộ lọc
            </flux:button>
        </div>
    </div>

    <!-- Phần hiển thị kết quả -->
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        @if($this->categories->count() > 0)
            <!-- Bảng danh sách danh mục -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Header của bảng -->
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">STT</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20">Banner</th>
                            <th 
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                wire:click="sortBy('title')"
                            >
                                Tiêu đề
                                @if($sortField === 'title')
                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-4 ml-1 inline" />
                                @endif
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mô tả ngắn</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Danh mục cha</th>
                            <th 
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                                wire:click="sortBy('created_at')"
                            >
                                Ngày tạo
                                @if($sortField === 'created_at')
                                    <flux:icon name="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="size-4 ml-1 inline" />
                                @endif
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">Thao tác</th>
                        </tr>
                    </thead>
                    <!-- Nội dung của bảng -->
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->categories as $index => $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <!-- Cột số thứ tự -->
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ ($this->categories->currentPage() - 1) * $this->categories->perPage() + $index + 1 }}
                                    </div>
                                </td>

                                <!-- Cột ảnh banner -->
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    @if($category->banner_image)
                                        <img 
                                            src="{{ $category->banner_image_url }}" 
                                            alt="{{ $category->title }}"
                                            class="w-16 h-16 rounded-lg object-cover mx-auto"
                                        />
                                    @else
                                        <div class="w-16 h-16 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center mx-auto">
                                            <flux:icon name="photo" class="size-8 text-gray-400" />
                                        </div>
                                    @endif
                                </td>

                                <!-- Cột tiêu đề danh mục -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $category->title }}
                                    </div>
                                </td>

                                <!-- Cột mô tả ngắn -->
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">
                                        {{ $category->short_description ?: 'Chưa có mô tả' }}
                                    </div>
                                </td>

                                <!-- Cột danh mục cha -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    @if($category->parent)
                                        <flux:badge variant="outline">
                                            {{ $category->parent->title }}
                                        </flux:badge>
                                    @else
                                        <flux:badge variant="primary">Danh mục gốc</flux:badge>
                                    @endif
                                </td>

                                <!-- Cột ngày tạo -->
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        <div class="font-medium">{{ $category->created_date }}</div>
                                        <div class="text-xs text-gray-500">{{ $category->created_time }}</div>
                                    </div>
                                </td>

                                <!-- Cột trạng thái hiển thị -->
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <flux:badge 
                                        variant="{{ $category->is_active ? 'success' : 'danger' }}"
                                    >
                                        {{ $category->is_active ? 'Hiển thị' : 'Ẩn' }}
                                    </flux:badge>
                                </td>

                                <!-- Cột các thao tác -->
                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- Nút chỉnh sửa -->
                                        <flux:button 
                                            variant="outline" 
                                            size="sm"
                                            :href="route('image-categories.edit', $category->id)"
                                            wire:navigate
                                        >
                                            <flux:icon name="pencil" class="size-4" />
                                        </flux:button>
                                        
                                        <!-- Nút bật/tắt hiển thị -->
                                        <flux:button 
                                            variant="outline" 
                                            size="sm"
                                            wire:click="toggleActive({{ $category->id }})"
                                        >
                                            <flux:icon name="{{ $category->is_active ? 'eye-slash' : 'eye' }}" class="size-4" />
                                        </flux:button>
                                        
                                        <!-- Nút xóa -->
                                        <flux:button 
                                            variant="danger" 
                                            size="sm"
                                            wire:click="delete({{ $category->id }})"
                                            wire:confirm="Bạn có chắc chắn muốn xóa danh mục này?"
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

            <!-- Phân trang -->
            <div class="px-4 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->categories->links() }}
            </div>
        @else
            <!-- Trạng thái không có dữ liệu -->
            <div class="text-center py-12">
                <flux:icon name="photo" class="mx-auto size-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Chưa có danh mục nào</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Bắt đầu bằng cách tạo danh mục đầu tiên của bạn.
                </p>
                <div class="mt-6">
                    <flux:button variant="primary" :href="route('image-categories.create')" wire:navigate>
                        <flux:icon name="plus" class="size-4" />
                        Thêm danh mục mới
                    </flux:button>
                </div>
            </div>
        @endif
    </div>

    <!-- Thông báo thành công/lỗi -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
            {{ session('error') }}
        </div>
    @endif
</div>
