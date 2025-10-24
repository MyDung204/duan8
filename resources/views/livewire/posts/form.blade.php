<?php

use App\Models\Post;
use App\Models\ImageCategory;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component
{
    use WithFileUploads;

    // Thuộc tính form
    public $postId = null;
    public $title = '';
    public $content = '';
    public $bannerImage = null;
    public $galleryImages = [];
    public $authorName = '';
    public $categoryId = null;
    public $isPublished = false;

    // Thuộc tính preview
    public $bannerPreview = null;
    public $galleryPreviews = [];

    // Thuộc tính tính toán - Lấy danh sách danh mục
    public function getCategoriesProperty()
    {
        return ImageCategory::active()->orderBy('title')->get();
    }

    // Mount component
    public function mount($id = null): void
    {
        if ($id) {
            $post = Post::findOrFail($id);
            $this->postId = $post->id;
            $this->title = $post->title;
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
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'bannerImage' => 'nullable|image|max:2048',
            'galleryImages.*' => 'nullable|image|max:2048',
            'galleryImages' => 'nullable|array|min:2|max:5',
            'authorName' => 'required|string|max:255',
            'categoryId' => 'required|exists:image_categories,id',
            'isPublished' => 'boolean',
        ];
    }

    // Validation messages
    protected function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'content.required' => 'Nội dung là bắt buộc.',
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
        $this->bannerPreview = $this->bannerImage->temporaryUrl();
    }

    // Updated gallery images
    public function updatedGalleryImages(): void
    {
        $this->validateOnly('galleryImages');
        $this->galleryPreviews = [];
        foreach ($this->galleryImages as $image) {
            $this->galleryPreviews[] = $image->temporaryUrl();
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
        unset($this->galleryImages[$index]);
        unset($this->galleryPreviews[$index]);
        $this->galleryImages = array_values($this->galleryImages);
        $this->galleryPreviews = array_values($this->galleryPreviews);
    }

    // Add gallery image
    public function addGalleryImage(): void
    {
        if (count($this->galleryImages) < 5) {
            $this->galleryImages[] = null;
        }
    }

    // Save post
    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => $this->title,
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
        if ($this->isPublished && !$this->postId) {
            $data['published_at'] = now();
        }

        if ($this->postId) {
            $post = Post::findOrFail($this->postId);
            $post->update($data);
            session()->flash('success', 'Bài đăng đã được cập nhật thành công!');
        } else {
            Post::create($data);
            session()->flash('success', 'Bài đăng đã được tạo thành công!');
        }

        return redirect()->route('posts.index');
    }
}; ?>

<div class="space-y-6">
    <!-- Header -->
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

    <!-- Flash Messages -->
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

    <!-- Form -->
    <form wire:submit="save" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tiêu đề -->
                <flux:field>
                    <flux:label>Tiêu đề bài đăng <span class="text-red-500">*</span></flux:label>
                    <flux:input 
                        wire:model="title" 
                        placeholder="Nhập tiêu đề bài đăng..."
                        required
                    />
                    <flux:error name="title" />
                </flux:field>

                <!-- Nội dung -->
                <flux:field>
                    <flux:label>Nội dung bài đăng <span class="text-red-500">*</span></flux:label>
                    <div class="mt-1">
                        <textarea 
                            wire:model="content" 
                            rows="15"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            placeholder="Nhập nội dung bài đăng..."
                            required
                        ></textarea>
                    </div>
                    <flux:error name="content" />
                    <flux:description>
                        Sử dụng các thẻ HTML để định dạng văn bản: &lt;b&gt;đậm&lt;/b&gt;, &lt;i&gt;nghiêng&lt;/i&gt;, &lt;u&gt;gạch chân&lt;/u&gt;
                    </flux:description>
                </flux:field>

                <!-- Thông tin bổ sung -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tác giả -->
                    <flux:field>
                        <flux:label>Tên tác giả <span class="text-red-500">*</span></flux:label>
                        <flux:input 
                            wire:model="authorName" 
                            placeholder="Nhập tên tác giả..."
                            required
                        />
                        <flux:error name="authorName" />
                    </flux:field>

                    <!-- Danh mục -->
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

            <!-- Right Column - Images & Settings -->
            <div class="space-y-6">
                <!-- Ảnh Banner -->
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ảnh Banner</h3>
                    
                    <flux:field>
                        <flux:label>Upload ảnh banner <span class="text-red-500">*</span></flux:label>
                        <flux:input 
                            type="file" 
                            wire:model="bannerImage" 
                            accept="image/*"
                            required
                        />
                        <flux:error name="bannerImage" />
                        <flux:description>Kích thước tối đa: 2MB</flux:description>
                    </flux:field>

                    @if($bannerPreview)
                        <div class="mt-4">
                            <img src="{{ $bannerPreview }}" alt="Banner Preview" class="w-full h-32 object-cover rounded-lg">
                            <flux:button 
                                type="button" 
                                variant="outline" 
                                size="sm" 
                                wire:click="removeBannerImage"
                                class="mt-2"
                            >
                                <flux:icon name="trash" class="size-4" />
                                Xóa ảnh
                            </flux:button>
                        </div>
                    @endif
                </div>

                <!-- Thư viện ảnh -->
                <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Thư viện ảnh</h3>
                        @if(count($galleryImages) < 5)
                            <flux:button 
                                type="button" 
                                variant="outline" 
                                size="sm" 
                                wire:click="addGalleryImage"
                            >
                                <flux:icon name="plus" class="size-4" />
                                Thêm ảnh
                            </flux:button>
                        @endif
                    </div>
                    
                    <flux:field>
                        <flux:label>Upload ảnh thư viện <span class="text-red-500">*</span></flux:label>
                        <flux:description>Yêu cầu: 2-5 ảnh, mỗi ảnh tối đa 2MB</flux:description>
                        <flux:error name="galleryImages" />
                    </flux:field>

                    @foreach($galleryImages as $index => $image)
                        <div class="mt-4 p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                            <flux:input 
                                type="file" 
                                wire:model="galleryImages.{{ $index }}" 
                                accept="image/*"
                            />
                            
                            @if(isset($galleryPreviews[$index]))
                                <div class="mt-2">
                                    <img src="{{ $galleryPreviews[$index] }}" alt="Gallery Preview" class="w-full h-24 object-cover rounded">
                                    <flux:button 
                                        type="button" 
                                        variant="outline" 
                                        size="sm" 
                                        wire:click="removeGalleryImage({{ $index }})"
                                        class="mt-2"
                                    >
                                        <flux:icon name="trash" class="size-4" />
                                        Xóa ảnh
                                    </flux:button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Cài đặt -->
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

        <!-- Form Actions -->
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <flux:button variant="outline" :href="route('posts.index')" wire:navigate>
                Hủy
            </flux:button>
            <flux:button type="submit" variant="primary">
                <flux:icon name="check" class="size-4" />
                {{ $postId ? 'Cập nhật bài đăng' : 'Tạo bài đăng' }}
            </flux:button>
        </div>
    </form>
</div>
