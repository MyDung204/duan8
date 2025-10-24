<?php

use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;

    // Thuộc tính form
    public string $title = '';
    public string $slug = '';
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
        // ĐÃ SỬA: Logic validation cho ảnh banner
        // Nếu đang chỉnh sửa VÀ đã có ảnh (bannerPreview tồn tại)
        // thì bannerImage (file upload mới) là không bắt buộc (nullable)
        if ($this->isEditing && $this->bannerPreview) {
            $bannerRule = 'nullable|image|max:2048';
        } else {
            // Ngược lại (hoặc là tạo mới, hoặc là chỉnh sửa nhưng chưa có ảnh)
            // thì bannerImage là bắt buộc
            $bannerRule = 'required|image|max:2048';
        }

        return [
            'title' => 'required|string|max:100',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $this->categoryId,
            'short_description' => 'required|string|max:200',
            'content' => 'required|string|max:1000',
            'author_name' => 'required|string|max:50',
            'bannerImage' => $bannerRule, // Sử dụng quy tắc động
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
        ];
    }

    // Thông báo lỗi validation
    protected function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.max' => 'Tiêu đề không được vượt quá 100 ký tự.',
            'slug.required' => 'Slug là bắt buộc.',
            'slug.max' => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique' => 'Slug này đã tồn tại.',
            'short_description.required' => 'Mô tả ngắn là bắt buộc.',
            'short_description.max' => 'Mô tả ngắn không được vượt quá 200 ký tự.',
            'content.required' => 'Nội dung chi tiết là bắt buộc.',
            'content.max' => 'Nội dung chi tiết không được vượt quá 1000 ký tự.',
            'author_name.required' => 'Tên tác giả là bắt buộc.',
            'author_name.max' => 'Tên tác giả không được vượt quá 50 ký tự.',
            'bannerImage.required' => 'Ảnh banner là bắt buộc.',
            'bannerImage.image' => 'File banner phải là hình ảnh.',
            'bannerImage.max' => 'Kích thước ảnh banner không được vượt quá 2MB.',
            'parent_id.exists' => 'Danh mục cha không tồn tại.',
        ];
    }

    // Thuộc tính tính toán
    public function getAvailableParentsProperty()
    {
        return Category::getAvailableParents($this->categoryId);
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
            $category = Category::findOrFail($id);
            $this->title = $category->title;
            $this->slug = $category->slug ?? '';
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
        // ĐÃ SỬA: Chỉ validate quy tắc hình ảnh, không validate 'required' khi chỉ cập nhật
        $this->validate([
            'bannerImage' => 'image|max:2048'
        ]); 
        if ($this->bannerImage) {
            $this->bannerPreview = $this->bannerImage->temporaryUrl();
        }
    }

    // Kiểm tra giới hạn ký tự và hiển thị cảnh báo
    public function checkCharacterLimit($field, $value): void
    {
        $limits = [
            'title' => 100,
            'short_description' => 200,
            'content' => 1000,
            'author_name' => 50,
        ];

        if (isset($limits[$field])) {
            $limit = $limits[$field];
            $currentLength = strlen($value);
            
            if ($currentLength >= $limit) {
                $this->dispatch('show-limit-warning', [
                    'field' => $field,
                    'current' => $currentLength,
                    'limit' => $limit
                ]);
            }
        }
    }

    // Xử lý khi thay đổi tiêu đề
    public function updatedTitle($value): void
    {
        $this->checkCharacterLimit('title', $value);
        // Tự động tạo slug từ tiêu đề
        $this->slug = Str::slug($value);
    }

    // Xử lý khi thay đổi mô tả ngắn
    public function updatedShortDescription($value): void
    {
        $this->checkCharacterLimit('short_description', $value);
    }

    // Xử lý khi thay đổi nội dung
    public function updatedContent($value): void
    {
        $this->checkCharacterLimit('content', $value);
    }

    // Xử lý khi thay đổi tên tác giả
    public function updatedAuthorName($value): void
    {
        $this->checkCharacterLimit('author_name', $value);
    }

    // Phương thức để kiểm tra giới hạn từ JavaScript
    public function checkLimitFromJS($field, $value): void
    {
        $this->checkCharacterLimit($field, $value);
    }

    // Listeners
    protected $listeners = ['checkLimitFromJS'];
    
    // Xóa ảnh banner
    public function removeBannerImage(): void
    {
        $this->bannerImage = null;
        $this->bannerPreview = null;
    }
    // Lưu danh mục
    public function save(): void
    {
        // Kiểm tra giới hạn ký tự trước khi validate
        $this->checkAllLimits();

        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'slug' => $this->slug,
                'short_description' => $this->short_description,
                'content' => $this->content,
                'author_name' => $this->author_name,
                'parent_id' => $this->parent_id,
                'is_active' => $this->is_active,
            ];

            // Xử lý upload ảnh banner (Chỉ lưu nếu có bannerImage MỚI)
            if ($this->bannerImage) {
                $bannerPath = $this->bannerImage->store('categories/banners', 'public');
                $data['banner_image'] = $bannerPath;
            }
           $message = ''; // Khởi tạo message
            if ($this->isEditing) {
                $category = Category::findOrFail($this->categoryId);
                $category->update($data);
                $message = 'Danh mục đã được cập nhật thành công.';
            } else {
                Category::create($data);
                $message = 'Danh mục đã được tạo thành công.';
            }
            session()->put('show_toast_message', [
                'text' => $message,
                'icon' => 'success'
            ]);

           

            // Vẫn chuyển hướng như cũ
            $this->redirect(route('categories.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Kiểm tra tất cả giới hạn
    public function checkAllLimits(): void
    {
        $fields = [
            'title' => $this->title,
            'short_description' => $this->short_description,
            'content' => $this->content,
            'author_name' => $this->author_name,
        ];

        foreach ($fields as $field => $value) {
            $this->checkCharacterLimit($field, $value);
        }
    }

    // Hủy và quay lại
    public function cancel(): void
    {
        $this->redirect(route('image-categories.index'), navigate: true);
    }
}; ?>

<div class="space-y-6">
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

    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <flux:field>
                        <flux:label>Tiêu đề <span class="text-red-500">*</span></flux:label>
                        <flux:input 
                            wire:model.live="title" 
                            placeholder="Nhập tiêu đề danh mục..."
                            maxlength="100"
                            id="title"
                            :spinner="false"
                        />
                        <flux:error name="title" />
                        <div class="text-sm text-gray-500 mt-1">
                            <span id="title-count">{{ strlen($title) }}</span>/100 ký tự
                        </div>
                    </flux:field>

                    <flux:field>
                        <flux:label>Slug <span class="text-red-500">*</span></flux:label>
                        <flux:input 
                            wire:model="slug" 
                            placeholder="Slug sẽ được tạo tự động..."
                            readonly
                            class="bg-gray-50 dark:bg-gray-700"
                        />
                        <flux:error name="slug" />
                        <flux:description>URL-friendly version của tiêu đề (tự động tạo)</flux:description>
                    </flux:field>

                    <flux:field>
                        <flux:label>Mô tả ngắn <span class="text-red-500">*</span></flux:label>
                        <flux:textarea 
                            wire:model.live="short_description" 
                            placeholder="Nhập mô tả ngắn về danh mục..."
                            rows="3"
                            maxlength="200"
                            id="short_description"
                        />
                        <flux:error name="short_description" />
                        <div class="text-sm text-gray-500 mt-1">
                            <span id="short-description-count">{{ strlen($short_description) }}</span>/200 ký tự
                        </div>
                    </flux:field>

                    <flux:field>
                        <flux:label>Nội dung chi tiết <span class="text-red-500">*</span></flux:label>
                        <flux:textarea 
                            wire:model.live="content" 
                            placeholder="Nhập nội dung chi tiết về danh mục..."
                            rows="6"
                            maxlength="1000"
                            id="content"
                        />
                        <flux:error name="content" />
                        <div class="text-sm text-gray-500 mt-1">
                            <span id="content-count">{{ strlen($content) }}</span>/1000 ký tự
                        </div>
                    </flux:field>

                    <flux:field>
                        <flux:label>Tên tác giả <span class="text-red-500">*</span></flux:label>
                        <flux:input 
                            wire:model.live="author_name" 
                            placeholder="Nhập tên tác giả..."
                            maxlength="50"
                            id="author_name"
                            :spinner="false"
                        />
                        <flux:error name="author_name" />
                        <div class="text-sm text-gray-500 mt-1">
                            <span id="author-name-count">{{ strlen($author_name) }}</span>/50 ký tự
                        </div>
                    </flux:field>
                </div>

                <div class="space-y-6">
                    <flux:field>
                        <flux:label>Ảnh Banner <span class="text-red-500">*</span></flux:label>
                        
                        {{-- ĐÃ SỬA: Chỉ hiển thị ảnh và nút xóa trong @if --}}
                        @if($bannerPreview)
                            <div class="mb-4">
                                <img 
                                    src="{{ $bannerPreview }}" 
                                    alt="Banner Preview" 
                                    class="w-full h-48 object-cover rounded-lg border border-gray-200 dark:border-gray-700"
                                />
                                <div class="mt-4 flex gap-2"> 
                                    <flux:button 
                                        type="button" 
                                        variant="outline" 
                                        size="sm"
                                        wire:click="removeBannerImage"
                                    >
                                        <div class="flex items-center gap-1.5">
                                            <flux:icon name="trash" class="size-4" />
                                            <span>Xóa banner</span>
                                        </div>
                                    </flux:button>
                                </div>
                            </div>
                        @endif

                        {{-- ĐÃ SỬA: Đưa input ra ngoài @if để chỉ có 1 input duy nhất --}}
                        <flux:input 
                            type="file" 
                            wire:model="bannerImage"
                            accept="image/*"
                        />
                        
                        {{-- ĐÃ SỬA: Xóa khối @else --}}

                        <flux:error name="bannerImage" />
                        <flux:description>Chọn ảnh banner cho danh mục (tối đa 2MB)</flux:description>
                    </flux:field>

                    

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('livewire:init', () => {
        // Lắng nghe sự kiện cảnh báo giới hạn ký tự
        Livewire.on('show-limit-warning', (event) => {
            const data = event[0];
            const fieldNames = {
                'title': 'Tiêu đề',
                'short_description': 'Mô tả ngắn',
                'content': 'Nội dung chi tiết',
                'author_name': 'Tên tác giả'
            };
            
            Swal.fire({
                icon: 'warning',
                title: 'Đã đạt giới hạn ký tự!',
                text: `${fieldNames[data.field]} đã đạt ${data.current}/${data.limit} ký tự. Bạn không thể nhập thêm nội dung.`,
                confirmButtonText: 'Đã hiểu',
                confirmButtonColor: '#ef4444'
            });
        });

        // Cập nhật số ký tự real-time và kiểm tra giới hạn
        function updateCharacterCount(fieldId, countId, fieldName) {
            const field = document.getElementById(fieldId);
            const count = document.getElementById(countId);
            
            if (field && count) {
                field.addEventListener('input', function() {
                    const currentLength = this.value.length;
                    count.textContent = currentLength;
                    
                    // Đổi màu khi gần đạt giới hạn
                    const maxLength = parseInt(this.getAttribute('maxlength'));
                    
                    if (currentLength >= maxLength * 0.9) {
                        count.style.color = '#ef4444';
                    } else if (currentLength >= maxLength * 0.8) {
                        count.style.color = '#f59e0b';
                    } else {
                        count.style.color = '#6b7280';
                    }

                    // Kiểm tra giới hạn và hiển thị cảnh báo
                    if (currentLength >= maxLength) {
                        // Gọi Livewire method để hiển thị SweetAlert2
                        Livewire.dispatch('checkLimitFromJS', {
                            field: fieldName,
                            value: this.value
                        });
                    }
                });

                // Kiểm tra khi paste
                field.addEventListener('paste', function(e) {
                    setTimeout(() => {
                        const currentLength = this.value.length;
                        const maxLength = parseInt(this.getAttribute('maxlength'));
                        
                        if (currentLength >= maxLength) {
                            Livewire.dispatch('checkLimitFromJS', {
                                field: fieldName,
                                value: this.value
                            });
                        }
                    }, 10);
                });
            }
        }

        // Khởi tạo cho tất cả các trường
        updateCharacterCount('title', 'title-count', 'title');
        updateCharacterCount('short_description', 'short-description-count', 'short_description');
        updateCharacterCount('content', 'content-count', 'content');
        updateCharacterCount('author_name', 'author-name-count', 'author_name');
    });
    </script>
</div>