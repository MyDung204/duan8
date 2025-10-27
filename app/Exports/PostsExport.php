<?php

namespace App\Exports;

use App\Models\Post;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PostsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Post::query()
            ->with('category')
            ->when($this->filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                      ->orWhereHas('category', function ($categoryQuery) use ($search) {
                          $categoryQuery->where('title', 'like', '%' . $search . '%');
                      });
                });
            })
            
            // === THAY ĐỔI LOGIC LỌC DANH MỤC ===
            ->when($this->filters['parentCategoryFilter'] ?? 'all', function ($q, $parentCategoryId) {
                if ($parentCategoryId === 'all') {
                    return;
                }
                $categoryId = (int) $parentCategoryId;
                $childCategoryIds = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
                $allCategoryIds = array_merge([$categoryId], $childCategoryIds);
                $q->whereIn('category_id', $allCategoryIds);
            })
            ->when($this->filters['childCategoryFilter'] ?? 'all', function ($q, $childCategoryId) {
                if ($childCategoryId === 'all') {
                    return;
                }
                $q->where('category_id', (int) $childCategoryId);
            })
            // === KẾT THÚC THAY ĐỔI ===

            ->when($this->filters['statusFilter'] ?? 'all', function($q, $statusFilter) {
                if ($statusFilter === 'published') {
                    $q->published();
                } elseif ($statusFilter === 'draft') {
                    $q->where('is_published', false);
                }
            });

        $sortField = $this->filters['sortField'] ?? 'created_at';
        $sortDirection = $this->filters['sortDirection'] ?? 'desc';

        match ($sortField) {
            'title' => $query->orderBy('title', $sortDirection),
            'created_at' => $query->orderBy('created_at', $sortDirection),
            'published_at' => $query->orderBy('published_at', $sortDirection),
            default => $query->orderBy('created_at', $sortDirection),
        };

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tiêu đề',
            'Danh mục',
            'Tác giả',
            'Trạng thái',
            'Ngày tạo',
            'Ngày xuất bản',
        ];
    }

    public function map($post): array
    {
        return [
            $post->id,
            $post->title,
            $post->category ? $post->category->full_path : 'N/A',
            $post->author_name,
            $post->is_published ? 'Đã xuất bản' : 'Bản nháp',
            $post->created_at->format('Y-m-d H:i:s'),
            $post->published_at ? $post->published_at->format('Y-m-d H:i:s') : 'N/A',
        ];
    }
}