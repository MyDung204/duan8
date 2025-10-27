<?php

namespace App\Exports;

use App\Models\Post;
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

    public function collection(): Collection
    {
        $query = Post::query()->with('category');

        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->filters['search'] . '%')
                  ->orWhere('content', 'like', '%' . $this->filters['search'] . '%')
                  ->orWhere('author_name', 'like', '%' . $this->filters['search'] . '%');
            });
        }

        if (!empty($this->filters['categoryFilter']) && $this->filters['categoryFilter'] !== 'all') {
            $categoryId = (int) $this->filters['categoryFilter'];
            $query->where('category_id', $categoryId);
        }

        if (!empty($this->filters['statusFilter']) && $this->filters['statusFilter'] !== 'all') {
            if ($this->filters['statusFilter'] === 'published') {
                $query->where('is_published', true);
            } elseif ($this->filters['statusFilter'] === 'draft') {
                $query->where('is_published', false);
            }
        }

        $sortField = $this->filters['sortField'] ?? 'created_at';
        $sortDirection = $this->filters['sortDirection'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

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
        return [
            $post->id,
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

