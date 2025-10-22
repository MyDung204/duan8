<?php

use App\Models\ImageCategory;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    // Thuộc tính form
    public string $title = '';
    public string $short_description = '';
    public string $content = '';
    public string $author_name = '';
    public $bannerImage; // Ảnh banner
    public ?int $parent_id = null;
    public bool $is_active = true;

    // Trạng thái component
    public ?int $categoryId = null;
    public $bannerPreview = null; // Preview ảnh banner
    public bool $isEditing = false;

    // Quy tắc validation
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'author_name' => 'nullable|string|max:255',
            'bannerImage' => 'nullable|image|max:2048', // Giới hạn 2MB
            'parent_id' => 'nullable|exists:image_categories,id',
            'is_active' => 'boolean',
        ];
    }

    // Thông báo lỗi validation
    protected function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 500 ký tự.',
            'author_name.max' => 'Tên tác giả không được vượt quá 255 ký tự.',
            'bannerImage.image' => 'File banner phải là hình ảnh.',
            'bannerImage.max' => 'Kích thước ảnh banner không được vượt quá 2MB.',
            'galleryImages.*.image' => 'File gallery phải là hình ảnh.',
            'galleryImages.*.max' => 'Kích thước ảnh gallery không được vượt quá 2MB.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
        ];
    }

    // Thuộc tính tính toán
    public function getAvailableParentsProperty()
    {
        return ImageCategory::getAvailableParents($this->categoryId);
    }

    public function getPageTitleProperty(): string
    {
        return $this->isEditing ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới';
    }

    public function getSubmitButtonTextProperty(): string
    {
        return $this->isEditing ? 'Cập nhật' : 'Tạo mới';
    }

    // Phương thức xử lý
    public function mount(?int $id = null): void
    {
        $this->categoryId = $id;
        $this->isEditing = $id !== null;

        if ($this->isEditing) {
            $category = ImageCategory::findOrFail($id);
            $this->title = $category->title;
            $this->short_description = $category->short_description ?? '';
            $this->content = $category->content ?? '';
            $this->author_name = $category->author_name ?? '';
            $this->parent_id = $category->parent_id;
            $this->is_active = $category->is_active;
            
            // Load ảnh banner hiện tại
            if ($category->banner_image) {
                $this->bannerPreview = $category->banner_image_url;
            }
        }
    }

    // Xử lý khi upload ảnh banner
    public function updatedBannerImage(): void
    {
        $this->validateOnly('bannerImage');
        $this->bannerPreview = $this->bannerImage->temporaryUrl();
    }
    
    // Xóa ảnh banner
    public function removeBannerImage(): void
    {
        $this->bannerImage = null;
        $this->bannerPreview = null;
    }
    // Lưu danh mục
    public function save(): void
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'short_description' => $this->short_description,
                'content' => $this->content,
                'author_name' => $this->author_name,
                'parent_id' => $this->parent_id,
                'is_active' => $this->is_active,
            ];

            // Xử lý upload ảnh banner
            if ($this->bannerImage) {
                $bannerPath = $this->bannerImage->store('image-categories/banners', 'public');
                $data['banner_image'] = $bannerPath;
            }
            if ($this->isEditing) {
                $category = ImageCategory::findOrFail($this->categoryId);
                $category->update($data);
                session()->flash('success', 'Danh mục đã được cập nhật thành công.');
            } else {
                ImageCategory::create($data);
                session()->flash('success', 'Danh mục đã được tạo thành công.');
            }

            $this->redirect(route('image-categories.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Hủy và quay lại
    public function cancel(): void
    {
        $this->redirect(route('image-categories.index'), navigate: true);
    }
}; ?>

<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->pageTitle }}</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $this->isEditing ? 'Chỉnh sửa thông tin danh mục' : 'Tạo danh mục mới với đầy đủ thông tin' }}
            </p>
        </div>
        <flux:button variant="outline" wire:click="cancel">
            <flux:icon name="arrow-left" class="size-4" />
            Quay lại
        </flux:button>
    </div>

    <!-- Form -->
    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Title -->
                    <flux:field>
                        <flux:label>Tiêu đề <span class="text-red-500">*</span></flux:label>
                        <flux:input 
                            wire:model.blur="title" 
                            placeholder="Nhập tiêu đề danh mục..."
                        />
                    </flux:field>

                    <!-- Short Description -->
                    <flux:field>
                        <flux:label>Mô tả ngắn</flux:label>
                        <flux:textarea 
                            wire:model.blur="short_description" 
                            placeholder="Nhập mô tả ngắn về danh mục..."
                            rows="3"
                        />
                        <flux:description>Tối đa 500 ký tự</flux:description>
                    </flux:field>

                    <!-- Content -->
                    <flux:field>
                        <flux:label>Nội dung chi tiết</flux:label>
                        <flux:textarea 
                            wire:model.blur="content" 
                            placeholder="Nhập nội dung chi tiết về danh mục..."
                            rows="6"
                        />
                    </flux:field>

                    <!-- Author Name -->
                    <flux:field>
                        <flux:label>Tên tác giả</flux:label>
                        <flux:input 
                            wire:model.blur="author_name" 
                            placeholder="Nhập tên tác giả..."
                        />
                    </flux:field>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Banner Image Upload -->
                    <flux:field>
                        <flux:label>Ảnh Banner</flux:label>
                        
                        @if($bannerPreview)
                            <div class="mb-4">
                                <img 
                                    src="{{ $bannerPreview }}" 
                                    alt="Banner Preview" 
                                    class="w-full h-48 object-cover rounded-lg border border-gray-200 dark:border-gray-700"
                                />
                                <div class="mt-2 flex gap-2">
                                    <flux:button 
                                        type="button" 
                                        variant="outline" 
                                        size="sm"
                                        wire:click="removeBannerImage"
                                    >
                                        <flux:icon name="trash" class="size-4" />
                                        Xóa banner
                                    </flux:button>
                                </div>
                            </div>
                        @endif

                        <flux:input 
                            type="file" 
                            wire:model="bannerImage"
                            accept="image/*"
                        />
                        <flux:description>Chọn ảnh banner cho danh mục (tối đa 2MB)</flux:description>
                    </flux:field>

                    

                    <!-- Parent Category -->
                    <flux:field>
                        <flux:label>Danh mục cha</flux:label>
                        <flux:select wire:model.blur="parent_id">
                            <option value="">Không có (danh mục gốc)</option>
                            @foreach($this->availableParents as $parent)
                                <option value="{{ $parent['id'] }}">{{ $parent['title'] }}</option>
                            @endforeach
                        </flux:select>
                        <flux:description>Chọn danh mục cha để tạo cấu trúc phân cấp</flux:description>
                    </flux:field>

                    <!-- Active Status -->
                    <flux:field>
                        <flux:label>Trạng thái</flux:label>
                        <flux:checkbox 
                            wire:model.blur="is_active"
                        >
                            Hiển thị danh mục
                        </flux:checkbox>
                        <flux:description>Danh mục sẽ hiển thị khi được kích hoạt</flux:description>
                    </flux:field>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 border-t border-gray-200 dark:border-gray-700" style="padding-top: 1.5rem;">
                <flux:button type="button" variant="outline" wire:click="cancel">
                    Hủy
                </flux:button>
                <flux:button type="submit" variant="primary" >
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <flux:icon name="{{ $this->isEditing ? 'check' : 'plus' }}" class="size-4" />
                        {{ $this->submitButtonText }}
                    </div>
                   
                </flux:button>
            </div>
        </form>
    </div>

    <!-- Flash Messages -->
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
