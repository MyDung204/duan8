<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Post extends Model
{
    /**
     * Các trường có thể gán hàng loạt
     */
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content', 
        'banner_image',
        'gallery_images',
        'author_name',
        'category_id',
        'is_published',
        'published_at',
        'views_count',
    ];

    /**
     * Các trường được cast
     */
    protected $casts = [
        'gallery_images' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Boot method để auto-generate slug từ title
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug) && !empty($post->title)) {
                $post->slug = static::generateUniqueSlug($post->title);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('title') && empty($post->slug)) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
        });
    }

    /**
     * Tạo slug duy nhất từ title
     */
    protected static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $excludeId)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Relationship: Post thuộc về một Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Scope: Lấy các bài đăng đã xuất bản
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Tìm kiếm theo từ khóa
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('content', 'like', "%{$term}%")
              ->orWhere('author_name', 'like', "%{$term}%");
        });
    }

    /**
     * Scope: Lọc theo danh mục
     */
    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: Sắp xếp theo ngày tạo
     */
    public function scopeSortByCreated(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('created_at', $direction);
    }

    /**
     * Scope: Tìm kiếm theo slug
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    /**
     * Accessor: URL ảnh banner
     */
    public function getBannerImageUrlAttribute(): ?string
    {
        if (!$this->banner_image) {
            return null;
        }
        
        return asset('storage/posts/banners/' . $this->banner_image);
    }

    /**
     * Accessor: URLs thư viện ảnh
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        if (!$this->gallery_images) {
            return [];
        }
        
        return array_map(function ($image) {
            return asset('storage/posts/gallery/' . $image);
        }, $this->gallery_images);
    }

    /**
     * Accessor: Ngày tạo đã format
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    /**
     * Accessor: Ngày tạo format ngắn gọn cho bảng
     */
    public function getCreatedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y');
    }

    /**
     * Accessor: Giờ tạo format ngắn gọn cho bảng
     */
    public function getCreatedTimeAttribute(): string
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Accessor: Ngày xuất bản đã format
     */
    public function getFormattedPublishedAtAttribute(): ?string
    {
        return $this->published_at ? $this->published_at->format('d/m/Y H:i:s') : null;
    }

    /**
     * Method: Kiểm tra bài đăng có được xuất bản không
     */
    public function isPublished(): bool
    {
        return $this->is_published && $this->published_at;
    }

    /**
     * Method: Xuất bản bài đăng
     */
    public function publish(): void
    {
        $this->update([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Method: Hủy xuất bản bài đăng
     */
    public function unpublish(): void
    {
        $this->update([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
