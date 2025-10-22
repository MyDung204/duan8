<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ImageCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'short_description',
        'content',
        'author_name',
        'banner_image', // Ảnh banner chính
        'parent_id',
        'order',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot method để auto-generate order từ created_at
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Tự động set order dựa trên thời gian tạo
            $model->order = static::max('order') + 1;
        });
    }

    /**
     * Relationship: Danh mục cha
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ImageCategory::class, 'parent_id');
    }

    /**
     * Relationship: Các danh mục con trực tiếp
     */
    public function children(): HasMany
    {
        return $this->hasMany(ImageCategory::class, 'parent_id')->orderBy('order', 'desc');
    }

    /**
     * Relationship: Tất cả danh mục con (recursive)
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Scope: Chỉ lấy danh mục đang kích hoạt
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Chỉ lấy danh mục gốc (không có parent)
     */
    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope: Tìm kiếm theo title và short_description
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('short_description', 'like', "%{$term}%");
        });
    }

    /**
     * Scope: Sắp xếp theo title
     */
    public function scopeSortByTitle(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('title', $direction);
    }

    /**
     * Scope: Sắp xếp theo order (mặc định)
     */
    public function scopeSortByOrder(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy('order', $direction);
    }

    /**
     * Scope: Lọc theo danh mục cha
     */
    public function scopeByParent(Builder $query, ?int $parentId): Builder
    {
        if ($parentId === null) {
            return $query->whereNull('parent_id');
        }
        
        return $query->where('parent_id', $parentId);
    }

    /**
     * Scope: Lọc theo loại danh mục
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return match ($type) {
            'parent' => $query->whereNull('parent_id'),
            'child' => $query->whereNotNull('parent_id'),
            default => $query,
        };
    }

    /**
     * Accessor: Lấy đường dẫn đầy đủ của danh mục
     */
    public function getFullPathAttribute(): string
    {
        $path = [$this->title];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->title);
            $parent = $parent->parent;
        }
        
        return implode(' > ', $path);
    }

    /**
     * Accessor: Lấy URL ảnh banner đầy đủ
     */
    public function getBannerImageUrlAttribute(): ?string
    {
        if (!$this->banner_image) {
            return null;
        }
        
        return asset('storage/' . $this->banner_image);
    }

    /**
     * Accessor: Lấy ngày tạo đã format
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }

    /**
     * Accessor: Lấy ngày tạo chỉ ngày
     */
    public function getCreatedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y');
    }

    /**
     * Accessor: Lấy giờ tạo
     */
    public function getCreatedTimeAttribute(): string
    {
        return $this->created_at->format('H:i:s');
    }

    /**
     * Method: Kiểm tra có danh mục con không
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Method: Lấy tất cả danh mục cha có thể chọn (tránh circular reference)
     */
    public static function getAvailableParents(?int $excludeId = null): array
    {
        $query = static::active();
        
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        
        return $query->orderBy('title')->get()->toArray();
    }

    /**
     * Method: Tạo tree structure cho dropdown
     */
    public static function getTreeForSelect(?int $excludeId = null): array
    {
        $categories = static::active()
            ->where('id', '!=', $excludeId)
            ->orderBy('title')
            ->get();
            
        $tree = [];
        
        foreach ($categories as $category) {
            $tree[] = [
                'id' => $category->id,
                'title' => $category->title,
                'full_path' => $category->full_path,
                'parent_id' => $category->parent_id,
            ];
        }
        
        return $tree;
    }
}
