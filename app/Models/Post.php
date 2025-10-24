<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    /**
     * Các trường có thể gán hàng loạt
     */
    protected $fillable = [
        'title',
        'content', 
        'banner_image',
        'gallery_images',
        'author_name',
        'category_id',
        'is_published',
        'published_at',
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
     * Relationship: Post thuộc về một Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ImageCategory::class, 'category_id');
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
