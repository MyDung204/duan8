<?php

use App\Models\Post;
use App\Models\Category;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\On; // SỬA LỖI: Thêm "On" attribute

new class extends Component
{
    use WithFileUploads;

    // Thuộc tính form
    public $postId = null;
    public $title = '';
    public $shortDescription = '';
    public $content = '';
    public $bannerImage = null;
    public $galleryImages = [];
    public $authorName = '';
    public $categoryId = null;
    public $isPublished = false;
    public $publishNow = false;

    // Thuộc tính preview
    public $bannerPreview = null;
    public $galleryPreviews = [];

    // Thuộc tính tính toán - Lấy danh sách danh mục
    public function getCategoriesProperty()
    {
        return Category::active()->orderBy('title')->get();
    }

    // Mount component
    public function mount($id = null): void
    {
        if ($id) {
            $post = Post::findOrFail($id);
            $this->postId = $post->id;
            $this->title = $post->title;
            $this->shortDescription = $post->short_description ?? '';
            $this->content = $post->content;
            $this->authorName = $post->author_name;
            $this->categoryId = $post->category_id;
            $this->isPublished = $post->is_published;
            
            // Load existing images
            if ($post->banner_image) {
                $this->bannerPreview = $post->banner_image_url;
            }
            
            if ($post->gallery_images) {
                $this->galleryPreviews = $post->gallery_image_urls;
            }
        }
    }

    // Validation rules
    protected function rules(): array
    {
        // SỬA LỖI VALIDATION: Yêu cầu ảnh banner khi tạo mới
        $bannerRule = 'nullable|image|max:2048'; // Mặc định là không bắt buộc
        if (!$this->postId && !$this->bannerPreview) { // Nếu là tạo mới VÀ chưa có ảnh (tức là chưa upload)
            $bannerRule = 'required|image|max:2048'; // Thì yêu cầu bắt buộc
        } elseif ($this->bannerImage) { // Nếu có file MỚI được upload (kể cả khi edit)
            $bannerRule = 'required|image|max:2048'; // Thì cũng validate file đó
        }

        return [
            'title' => 'required|string|max:255',
            'shortDescription' => 'required|string|max:500',
            'content' => 'required|string',
            'bannerImage' => $bannerRule, // Áp dụng quy tắc đã sửa
            'galleryImages.*' => 'nullable|image|max:2048',
            'galleryImages' => 'nullable|array|min:2|max:5',
            'authorName' => 'required|string|max:255',
            'categoryId' => 'required|exists:categories,id',
            'isPublished' => 'boolean',
        ];
    }

    // Validation messages
    protected function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'shortDescription.required' => 'Mô tả ngắn là bắt buộc.',
            'shortDescription.max' => 'Mô tả ngắn không được vượt quá 500 ký tự.',
            'content.required' => 'Nội dung là bắt buộc.',
            'bannerImage.required' => 'Ảnh banner là bắt buộc.', // Thêm message này
            'bannerImage.image' => 'Ảnh banner phải là file ảnh.',
            'bannerImage.max' => 'Ảnh banner không được vượt quá 2MB.',
            'galleryImages.min' => 'Thư viện ảnh phải có ít nhất 2 ảnh.',
            'galleryImages.max' => 'Thư viện ảnh không được vượt quá 5 ảnh.',
            'galleryImages.*.image' => 'Tất cả ảnh trong thư viện phải là file ảnh.',
            'galleryImages.*.max' => 'Mỗi ảnh trong thư viện không được vượt quá 2MB.',
            'authorName.required' => 'Tên tác giả là bắt buộc.',
            'categoryId.required' => 'Danh mục là bắt buộc.',
            'categoryId.exists' => 'Danh mục không tồn tại.',
        ];
    }

    // Updated banner image
    public function updatedBannerImage(): void
    {
        $this->validateOnly('bannerImage');
        if ($this->bannerImage) {
            $this->bannerPreview = $this->bannerImage->temporaryUrl();
        }
    }

    // Updated gallery images
    public function updatedGalleryImages(): void
    {
        $this->validateOnly('galleryImages');
        $this->galleryPreviews = [];
        foreach ($this->galleryImages as $image) {
            if ($image) {
                $this->galleryPreviews[] = $image->temporaryUrl();
            }
        }
    }

    // Remove banner image
    public function removeBannerImage(): void
    {
        $this->bannerImage = null;
        $this->bannerPreview = null;
    }

    // Remove gallery image
    public function removeGalleryImage($index): void
    {
        // Chuyển mảng galleryImages sang kiểu collection để dễ thao tác
        $images = collect($this->galleryImages);
        $previews = collect($this->galleryPreviews);

        // Xóa phần tử tại index
        $images->forget($index);
        $previews->forget($index);

        // Đặt lại giá trị
        $this->galleryImages = $images->values()->all();
        $this->galleryPreviews = $previews->values()->all();
    }

    // SỬA LỖI TRIX: Dùng "On" attribute
    #[On('sync-content')]
    public function syncContent($content): void
    {
        $this->content = $content;
    }

    // SỬA LỖI TRIX: Xóa listener kiểu cũ
    // protected $listeners = ['sync-content' => 'syncContent'];

    // Save post
    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'short_description' => $this->shortDescription,
            'content' => $this->content,
            'author_name' => $this->authorName,
            'category_id' => $this->categoryId,
            'is_published' => $this->isPublished,
        ];

        // Handle banner image
        if ($this->bannerImage) {
            $bannerPath = $this->bannerImage->store('posts/banners', 'public');
            $data['banner_image'] = basename($bannerPath);
        }

        // Handle gallery images
        if (!empty($this->galleryImages)) {
            $galleryPaths = [];
            foreach ($this->galleryImages as $image) {
                if ($image) {
                    $galleryPath = $image->store('posts/gallery', 'public');
                    $galleryPaths[] = basename($galleryPath);
                }
            }
            $data['gallery_images'] = $galleryPaths;
        }

        // Set published_at if publishing
        if ($this->isPublished && !$this->postId) { // Chỉ set khi tạo mới
            $data['published_at'] = now();
        } elseif ($this->isPublished && $this->postId) { // Xử lý khi cập nhật
            $postCheck = Post::find($this->postId);
            if (!$postCheck->is_published) { // Nếu trước đó chưa publish thì mới set
                $data['published_at'] = now();
            }
        }

        // SỬA LỖI THÔNG BÁO: Dùng session() flash cho redirect
        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update($data);
            session()->flash('show_toast_message', [
                'text' => 'Bài đăng đã được cập nhật thành công!',
                'icon' => 'success'
            ]);
        } else {
            Post::create($data);
            session()->flash('show_toast_message', [
                'text' => 'Bài đăng đã được tạo thành công!',
                'icon' => 'success'
            ]);
        }

        return redirect()->route('posts.index');
    }
}; ?>

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between pb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ $postId ? 'Chỉnh sửa bài đăng' : 'Tạo bài đăng mới' }}
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ $postId ? 'Cập nhật thông tin bài đăng' : 'Tạo bài đăng với đầy đủ tính năng' }}
            </p>
        </div>
        
        <div class="flex gap-3">
            <flux:button variant="outline" :href="route('posts.index')" wire:navigate>
                <flux:icon name="arrow-left" class="size-4" />
                Quay lại
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

    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <flux:field>
                    <flux:label>Tiêu đề bài đăng <span class="text-red-500">*</span></flux:label>
                    <flux:input 
                        wire:model="title" 
                        placeholder="Nhập tiêu đề bài đăng..."
                        required
                    />
                    <flux:error name="title" />
                </flux:field>

                <flux:field>
                    <flux:label>Mô tả ngắn <span class="text-red-500">*</span></flux:label>
                    <flux:textarea 
                        wire:model="shortDescription" 
                        placeholder="Nhập mô tả ngắn về bài đăng..."
                        rows="3"
                        required
                    />
                    <flux:error name="shortDescription" />
                    <flux:description>Tóm tắt ngắn gọn về nội dung bài đăng (tối đa 500 ký tự)</flux:description>
                </flux:field>

                <flux:field wire:ignore> <flux:label>Nội dung bài đăng <span class="text-red-500">*</span></flux:label>
                    <div class="mt-1">
                        <textarea 
                            wire:model="content" 
                            id="content-backup"
                            style="display: none;"
                        ></textarea>
                        
                        <trix-editor 
                            input="content-input"
                            class="trix-content"
                            placeholder="Nhập nội dung bài đăng..."
                        ></trix-editor>
                        
                        <input 
                            id="content-input" 
                            type="hidden" 
                            name="content" 
                        >
                    </div>
                    <flux:error name="content" />
                    <flux:description>
                        Sử dụng Rich Text Editor với giao diện giống Microsoft Word: in đậm, nghiêng, gạch chân, căn chỉnh, danh sách, liên kết...
                    </flux:description>
                </flux:field>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:field>
                        <flux:label>Tên tác giả <span class="text-red-500">*</span></flux:label>
                        <flux:input 
                            wire:model="authorName" 
                            placeholder="Nhập tên tác giả..."
                            required
                        />
                        <flux:error name="authorName" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Danh mục <span class="text-red-500">*</span></flux:label>
                        <flux:select wire:model="categoryId" required>
                            <option value="">Chọn danh mục...</option>
                            @foreach($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </flux:select>
                        <flux:error name="categoryId" />
                    </flux:field>
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ảnh Banner</h3>
                    
                    <flux:field>
                        <flux:label>Upload ảnh banner 
                            @if(!$postId)
                                <span class="text-red-500">*</span>
                            @endif
                        </flux:label>
                        <flux:input 
                            type="file" 
                            wire:model="bannerImage" 
                            accept="image/*"
                        />
                        <flux:error name="bannerImage" />
                        <flux:description>Kích thước tối đa: 2MB</flux:description>
                    </flux:field>
                    
                    <div wire:loading wire:target="bannerImage" class="text-sm text-blue-500">Đang tải lên...</div>

                    {{-- === SỬA NÚT XÓA ẢNH BANNER === --}}
                    @if($bannerPreview)
                        {{-- Sửa: Bọc ảnh trong div relative --}}
                        <div class="mt-4 relative group">
                            {{-- Sửa: Giảm chiều cao từ h-32 thành h-24 --}}
                            <img src="{{ $bannerPreview }}" alt="Banner Preview" 
                                 class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                            
                            {{-- Sửa: Đặt nút "X" vào TRONG góc trên bên phải --}}
                            <button 
                                type="button" 
                                wire:click="removeBannerImage"
                                class="absolute top-2 right-2 bg-black bg-opacity-50 text-white rounded-full w-6 h-6 flex items-center justify-center text-lg font-bold hover:bg-opacity-75 transition-colors"
                                title="Xóa ảnh banner"
                            >
                                &times;
                            </button>
                        </div>
                    @endif
                    {{-- === KẾT THÚC SỬA NÚT XÓA ẢNH BANNER === --}}

                </div>

                <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Thư viện ảnh</h3>
                    </div>
                    
                    <flux:field>
                        <flux:label>Upload ảnh thư viện</flux:label>
                        <input 
                            type="file" 
                            wire:model="galleryImages" 
                            accept="image/*"
                            multiple
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-600 dark:file:text-gray-200 dark:hover:file:bg-gray-500"
                        />
                        <flux:error name="galleryImages" />
                        <flux:error name="galleryImages.*" />
                        <flux:description>Chọn 2-5 ảnh cùng một lần, mỗi ảnh tối đa 2MB</flux:description>
                    </flux:field>
                    
                    <div wire:loading wire:target="galleryImages" class="text-sm text-blue-500">Đang tải lên...</div>

                    @if(count($galleryPreviews) > 0)
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Ảnh đã chọn ({{ count($galleryPreviews) }}/5):
                            </h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @foreach($galleryPreviews as $index => $preview)
                                    {{-- === SỬA NÚT XÓA THƯ VIỆN ẢNH === --}}
                                    <div class="relative group" wire:key="gallery-preview-{{ $index }}">
                                        <img src="{{ $preview }}" alt="Gallery Preview {{ $index + 1 }}" class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
                                        
                                        {{-- Sửa: Đổi class để đưa nút "X" vào trong góc trên bên phải --}}
                                        <button 
                                            type="button" 
                                            wire:click="removeGalleryImage({{ $index }})"
                                            class="absolute top-2 right-2 bg-black bg-opacity-50 text-white rounded-full w-6 h-6 flex items-center justify-center text-lg font-bold hover:bg-opacity-75 transition-colors"
                                            title="Xóa ảnh"
                                        >
                                            &times;
                                        </button>
                                    </div>
                                    {{-- === KẾT THÚC SỬA NÚT XÓA THƯ VIỆN ẢNH === --}}
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Cài đặt</h3>
                    
                    <flux:field>
                        <flux:checkbox wire:model="isPublished">
                            Xuất bản ngay
                        </flux:checkbox>
                        <flux:description>
                            Nếu không chọn, bài đăng sẽ được lưu dưới dạng bản nháp
                        </flux:description>
                    </flux:field>
                </div>
            </div>
        </div>

        {{-- SỬA KHOẢNG TRẮNG NÚT BẤM: Thêm py-8 --}}
        <div class="flex justify-end gap-3 py-8 border-t border-gray-200 dark:border-gray-700">
            <flux:button variant="outline" :href="route('posts.index')" wire:navigate>
                Hủy
            </flux:button>
            
            {{-- SỬA NÚT TẠO BÀI ĐĂNG: Icon + Chữ --}}
            <flux:button type="submit" variant="primary">
                {{-- Spinner (hiển thị khi loading) --}}
                <div wire:loading wire:target="save" class="animate-spin h-4 w-4 border-t-2 border-r-2 border-white rounded-full"></div>

                {{-- Content (ẩn khi loading, icon và chữ nằm cạnh nhau) --}}
                <span wire:loading.remove wire:target="save" class="inline-flex items-center gap-x-1.5">
                    <flux:icon name="check" class="size-4" />
                    <span>
                        {{ $postId ? 'Cập nhật bài đăng' : 'Tạo bài đăng' }}
                    </span>
                </span>
            </flux:button>
        </div>
    </form>

    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    
    <style>
    /* ... (Phần CSS giữ nguyên, không cần thay đổi) ... */
/* Trix Editor Custom Styling - Cải thiện độ tương phản */
trix-editor {
    min-height: 400px;
    border: 2px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1.5rem;
    font-family: 'Times New Roman', Times, serif;
    font-size: 14pt;
    line-height: 1.6;
    background-color: #ffffff;
    color: #1f2937;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

trix-editor:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
    outline: none;
}

.dark trix-editor {
    background-color: #ffffff;
    border-color: #d1d5db;
    color: #1f2937;
}

/* Trix Toolbar Styling - Sáng và nổi bật */
trix-toolbar {
    border: 2px solid #e5e7eb;
    border-bottom: none;
    border-radius: 0.5rem 0.5rem 0 0;
    background-color: #ffffff;
    padding: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.dark trix-toolbar {
    background-color: #ffffff;
    border-color: #d1d5db;
}

/* Trix Button Styling */
trix-toolbar .trix-button-group {
    border-right: 1px solid #d1d5db;
}

.dark trix-toolbar .trix-button-group {
    border-color: #4b5563;
}

trix-toolbar .trix-button {
    border: none;
    background: white;
    color: #374151;
    padding: 0.5rem;
    border-radius: 0.25rem;
    margin: 0.125rem;
    transition: all 0.2s;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

trix-toolbar .trix-button:hover {
    background-color: #f8fafc;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
}

trix-toolbar .trix-button.trix-active {
    background-color: #3b82f6;
    color: white;
    box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
}

.dark trix-toolbar .trix-button {
    background: #374151;
    color: #f9fafb;
    border: 1px solid #4b5563;
}

.dark trix-toolbar .trix-button:hover {
    background-color: #4b5563;
    border-color: #6b7280;
}

.dark trix-toolbar .trix-button.trix-active {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

/* Content Styling */
trix-editor h1 {
    font-size: 24pt;
    font-weight: bold;
    margin: 0 0 0.5em 0;
}

trix-editor h2 {
    font-size: 18pt;
    font-weight: bold;
    margin: 0 0 0.5em 0;
}

trix-editor h3 {
    font-size: 14pt;
    font-weight: bold;
    margin: 0 0 0.5em 0;
}

trix-editor p {
    margin: 0 0 1em 0;
}

trix-editor ul,
trix-editor ol {
    margin: 0 0 1em 0;
    padding-left: 1.5em;
}

trix-editor blockquote {
    border-left: 4px solid #3b82f6;
    margin: 1em 0;
    padding-left: 1em;
    font-style: italic;
    color: #6b7280;
}

trix-editor a {
    color: #3b82f6;
    text-decoration: underline;
}

trix-editor img {
    max-width: 100%;
    height: auto;
    border-radius: 0.25rem;
}

/* Cải thiện độ tương phản cho các nút */
trix-toolbar .trix-button {
    border: 2px solid #e5e7eb !important;
    background: #ffffff !important;
    color: #1f2937 !important;
    padding: 0.75rem !important;
    border-radius: 0.375rem !important;
    margin: 0.25rem !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    font-weight: 600 !important;
    font-size: 14px !important;
}

trix-toolbar .trix-button:hover {
    background-color: #f3f4f6 !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
    transform: translateY(-2px) !important;
    color: #3b82f6 !important;
}

trix-toolbar .trix-button.trix-active {
    background-color: #3b82f6 !important;
    color: #ffffff !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
    transform: translateY(-1px) !important;
}

/* Cải thiện placeholder text */
trix-editor:empty:before {
    color: #9ca3af !important;
    font-style: italic !important;
    font-size: 14pt !important;
}

/* Cải thiện dropdown */
trix-toolbar .trix-dialog {
    background-color: #ffffff !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 0.5rem !important;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

trix-toolbar .trix-dialog .trix-button {
    background-color: #f9fafb !important;
    color: #374151 !important;
    border: 1px solid #d1d5db !important;
}

trix-toolbar .trix-dialog .trix-button:hover {
    background-color: #3b82f6 !important;
    color: #ffffff !important;
    border-color: #3b82f6 !important;
}
    </style>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- SỬA LỖI TRIX EDITOR TRIỆT ĐỂ (Javascript) --}}
    <script>
    // Hàm khởi tạo Trix
    function initializeTrix() {
        const trixEditor = document.querySelector('trix-editor');
        const hiddenInput = document.getElementById('content-input');
        
        // Chỉ chạy nếu Trix Editor tồn tại trên trang này
        if (trixEditor && hiddenInput) {
            
            // Ngăn khởi tạo lại nếu đã có
            if (trixEditor.listenerAttached) {
                return;
            }

            // Load nội dung ban đầu (khi edit)
            // Dùng json_encode để lấy giá trị $this->content từ PHP
            const initialContent = {!! json_encode($this->content) !!} || '';
            if (initialContent) {
                // Chỉ load nếu editor rỗng, tránh ghi đè khi validation fail
                if (!trixEditor.editor.getDocument().toString().trim()) {
                    trixEditor.editor.loadHTML(initialContent);
                }
            }
            
            // Sync với Livewire khi nội dung thay đổi
            trixEditor.addEventListener('trix-change', function(event) {
                const content = event.target.innerHTML;
                hiddenInput.value = content;
                // Dispatch sự kiện để PHP #[On('sync-content')] bắt
                Livewire.dispatch('sync-content', { content: content }); 
            });
            
            // Sync khi paste
            trixEditor.addEventListener('trix-paste', function(event) {
                setTimeout(function() {
                    const content = trixEditor.innerHTML;
                    hiddenInput.value = content;
                    Livewire.dispatch('sync-content', { content: content });
                }, 100);
            });

            // Đánh dấu là đã khởi tạo
            trixEditor.listenerAttached = true;
            console.log('Trix Editor đã được khởi tạo và gắn listener.');
        }
    }

    // --- Chạy khởi tạo Trix ---
    
    // 1. Chạy khi trang được điều hướng bằng wire:navigate
    document.addEventListener('livewire:navigated', () => {
        console.log('Sự kiện: livewire:navigated');
        initializeTrix();
    });
    
    // 2. Chạy khi trang được tải lần đầu (F5, tải cứng)
    // Cần đảm bảo Livewire đã sẵn sàng
    document.addEventListener('livewire:init', () => {
         console.log('Sự kiện: livewire:init');
         // Chạy lần đầu phòng trường hợp tải F5
         initializeTrix(); 

        // Đăng ký listener cho SweetAlert (chỉ cần 1 lần)
        // Listener này sẽ KHÔNG chạy ở trang này vì có redirect
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

    </script>
</div>