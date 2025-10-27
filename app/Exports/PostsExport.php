<?php

namespace App\Exports;

use App\Models\Post;
use App\Models\Category; // SỬA 1: Thêm dòng này để sử dụng Model Category
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Support\Collection;

class PostsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    // SỬA 2: Sửa toàn bộ phương thức collection()
    public function collection(): Collection
    {
        $query = Post::query()->with('category');

        // === SỬA LỖI LOGIC TÌM KIẾM ===
        // Logic này phải khớp với file index.blade.php
        if (!empty($this->filters['search'])) {
            $search = $this->filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhereHas('category', function ($categoryQuery) use ($search) {
                      $categoryQuery->where('title', 'like', '%' . $search . '%');
                  });
            });
        }

        // === SỬA LỖI LOGIC LỌC DANH MỤC ===
        // Logic này phải khớp với file index.blade.php (bao gồm cả danh mục con)
        if (!empty($this->filters['categoryFilter']) && $this->filters['categoryFilter'] !== 'all') {
            $categoryId = (int) $this->filters['categoryFilter'];
            
            // Lấy ID của các danh mục con
            $childCategoryIds = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
            // Gộp ID cha và ID con
            $allCategoryIds = array_merge([$categoryId], $childCategoryIds);
            
            // Lọc theo tất cả ID này
            $query->whereIn('category_id', $allCategoryIds);
        }

        // Logic lọc trạng thái (Đã đúng)
        if (!empty($this->filters['statusFilter']) && $this->filters['statusFilter'] !== 'all') {
            if ($this->filters['statusFilter'] === 'published') {
                $query->where('is_published', true);
            } elseif ($this->filters['statusFilter'] === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Logic sắp xếp (Đã đúng)
        $sortField = $this->filters['sortField'] ?? 'created_at';
        $sortDirection = $this->filters['sortDirection'] ?? 'desc';
        
        // Đảm bảo sorting field hợp lệ
        $allowedSortFields = ['title', 'created_at', 'published_at'];
        if (in_array($sortField, $allowedSortFields)) {
             $query->orderBy($sortField, $sortDirection);
        } else {
             $query->orderBy('created_at', $sortDirection);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'STT',
            'Tieu de',
            'Danh muc',
            'Tac gia',
            'Ngay tao',
            'Ngay xuat ban',
            'Trang thai',
            'Mo ta ngan',
        ];
    }

    public function map($post): array
    {
        // SỬA 3: Sửa STT để map theo thứ tự 1, 2, 3... thay vì ID
        static $index = 0;
        $index++;

        return [
            $index, // Thay vì $post->id
            $post->title,
            $post->category?->title ?? 'Chua phan loai',
            $post->author_name ?? 'Chua co',
            $post->created_at->format('d/m/Y H:i:s'),
            $post->published_at?->format('d/m/Y H:i:s') ?? 'Chua xuat ban',
            $post->is_published ? 'Da xuat ban' : 'Ban nhap',
            $post->short_description ?? '',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,
            'B' => 40,
            'C' => 25,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 15,
            'H' => 50,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $highestRow = $sheet->getHighestRow();
        
        $sheet->getStyle('A2:A' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('G2:G' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('B2:H' . $highestRow)->applyFromArray([
            'wrapText' => true,
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
            ],
        ]);

        $sheet->getRowDimension('1')->setRowHeight(30);
    }
}