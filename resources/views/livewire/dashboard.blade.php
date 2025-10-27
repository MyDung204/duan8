<?php

use App\Models\Post;
use App\Models\Category;
use Livewire\Volt\Component;

new class extends Component
{
    public string $chartTimeframe = 'month';

    public function getTotalPostsProperty()
    {
        return Post::count();
    }

    public function getPublishedPostsProperty()
    {
        return Post::where('is_published', true)->count();
    }

    public function getDraftPostsProperty()
    {
        return Post::where('is_published', false)->count();
    }

    public function getTotalCategoriesProperty()
    {
        return Category::count();
    }

    public function getPostsThisWeekProperty()
    {
        return Post::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])->count();
    }

    public function getPostsThisMonthProperty()
    {
        return Post::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
    }

    public function getPostsTodayProperty()
    {
        return Post::whereDate('created_at', today())->count();
    }

    public function getChartDataProperty()
    {
        if ($this->chartTimeframe === 'week') {
            // Data for current week (7 days)
            $data = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $count = Post::whereDate('created_at', $date->format('Y-m-d'))->count();
                $data[] = [
                    'label' => $date->format('d/m'),
                    'value' => $count
                ];
            }
            return $data;

        } elseif ($this->chartTimeframe === 'month') {
            // Data for 12 months of current year
            $posts = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', now()->year) 
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('count', 'month')
                ->toArray();

            $months = ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'];
            $data = [];
            for ($i = 1; $i <= 12; $i++) {
                $data[] = [
                    'label' => $months[$i - 1],
                    'value' => $posts[$i] ?? 0
                ];
            }
            return $data;

        } else { // 'year'
            // Data for 5 recent years
            $startYear = now()->subYears(4)->year;
            $endYear = now()->year;
            
            $posts = Post::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
                ->whereBetween('created_at', [
                    now()->subYears(4)->startOfYear(), 
                    now()->endOfYear()
                ])
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('count', 'year')
                ->toArray();

            $data = [];
            for ($year = $startYear; $year <= $endYear; $year++) {
                $data[] = [
                    'label' => (string)$year,
                    'value' => $posts[$year] ?? 0
                ];
            }
            return $data;
        }
    }

    // === BẮT ĐẦU SỬA LỖI LOGIC PIE CHART ===
    public function getPostsByCategoryProperty()
    {
        $query = Post::query() // Bắt đầu query
            ->selectRaw('category_id, COUNT(*) as count')
            ->whereNotNull('category_id');

        // ÁP DỤNG BỘ LỌC THỜI GIAN (GIỐNG HỆT BAR CHART)
        if ($this->chartTimeframe === 'week') {
            // 7 ngày gần nhất
            $query->whereBetween('created_at', [
                now()->subDays(6)->startOfDay(), 
                now()->endOfDay()
            ]);
        } elseif ($this->chartTimeframe === 'month') {
            // 12 tháng của năm nay
            $query->whereYear('created_at', now()->year);
        } elseif ($this->chartTimeframe === 'year') {
            // 5 năm gần nhất
            $query->whereBetween('created_at', [
                now()->subYears(4)->startOfYear(), 
                now()->endOfYear()
            ]);
        }
        // Nếu không có timeframe (mặc định), nó sẽ lấy của 'month' (năm nay)

        $result = $query->groupBy('category_id') // Áp dụng groupBy và get
            ->with('category')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => $item->category->title ?? 'Chưa phân loại',
                    'value' => (int)$item->count
                ];
            })
            ->toArray();

        // Nếu không có dữ liệu, trả về mảng rỗng
        if (empty($result)) {
            return [
                ['label' => 'Chưa có dữ liệu', 'value' => 0]
            ];
        }

        return $result;
    }
    // === KẾT THÚC SỬA LỖI LOGIC PIE CHART ===

    public function getRecentPostsProperty()
    {
        return Post::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getChartLabelsProperty()
    {
        $data = $this->chartData;
        return array_map(function($item) {
            return (string)$item['label'];
        }, $data);
    }

    public function getChartValuesProperty()
    {
        $data = $this->chartData;
        return array_map(function($item) {
            return (int)$item['value'];
        }, $data);
    }

    public function updatedChartTimeframe()
    {
        $this->dispatch('chart-updated');
    }
}; ?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-400">Tổng quan hệ thống quản lý bài đăng</p>
        </div>
        <div class="rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3">
            <div x-data="{ 
                time: new Date().toLocaleTimeString('vi-VN', { hour12: false }),
                date: new Date().toLocaleDateString('vi-VN', { weekday: 'long', year: 'numeric', month: '2-digit', day: '2-digit' })
            }" 
            x-init="
                setInterval(() => {
                    time = new Date().toLocaleTimeString('vi-VN', { hour12: false });
                }, 1000);
            ">
                <p class="text-sm font-medium text-white">
                    <span x-text="time"></span> - <span x-text="date"></span>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-5">
        <div class="rounded-lg bg-gradient-to-r from-purple-500 to-pink-500 p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Tổng bài đăng</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->totalPosts }}</p>
                </div>
                <flux:icon name="document-text" class="size-8 text-white opacity-50" />
            </div>
        </div>
        <div class="rounded-lg bg-gradient-to-r from-green-500 to-emerald-500 p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Đã xuất bản</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->publishedPosts }}</p>
                </div>
                <flux:icon name="check-circle" class="size-8 text-white opacity-50" />
            </div>
        </div>
        <div class="rounded-lg bg-gradient-to-r from-yellow-500 to-orange-500 p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Bản nháp</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->draftPosts }}</p>
                </div>
                <flux:icon name="clipboard-document" class="size-8 text-white opacity-50" />
            </div>
        </div>
        <div class="rounded-lg bg-gradient-to-r from-blue-500 to-cyan-500 p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Danh mục</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->totalCategories }}</p>
                </div>
                <flux:icon name="folder" class="size-8 text-white opacity-50" />
            </div>
        </div>
        <div class="rounded-lg bg-gradient-to-r from-indigo-500 to-purple-500 p-6 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Hôm nay</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->postsToday }}</p>
                </div>
                <flux:icon name="calendar-days" class="size-8 text-white opacity-50" />
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="rounded-lg bg-gray-800 p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">Biểu đồ số bài đăng</h2>
                <div class="flex items-center gap-3">
                    <flux:field class="mb-0 w-32">
                        <flux:select wire:model.live="chartTimeframe">
                            <option value="week">Tuần</option>
                            <option value="month">Tháng</option>
                            <option value="year">Năm</option>
                        </flux:select>
                    </flux:field>
                    <div class="text-sm">
                        <span class="inline-flex items-center gap-2 text-blue-400">
                            <span class="h-3 w-3 rounded bg-blue-500"></span>
                            Số bài đăng
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="chart-container" style="height: 400px; width: 100%; position: relative;">
                <canvas 
                    id="postsChart" 
                    data-chart-labels="{{ json_encode($this->chartLabels) }}"
                    data-chart-values="{{ json_encode($this->chartValues) }}"
                ></canvas>
            </div>
        </div>

        <div class="rounded-lg bg-gray-800 p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">Phân bố theo danh mục</h2>
            </div>
            
            <div class="chart-container" style="height: 400px; width: 100%; position: relative;">
                <canvas id="categoryChart" data-chart-data="{{ json_encode($this->postsByCategory) }}"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 rounded-lg bg-gray-800 p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">Bài đăng gần đây</h2>
                <flux:button variant="outline" size="sm" :href="route('posts.index')" wire:navigate>
                    Xem tất cả
                    <flux:icon name="arrow-right" class="size-4" />
                </flux:button>
            </div>
            
            <div class="space-y-4">
                @forelse($this->recentPosts as $post)
                    <div class="rounded-lg border border-gray-700 bg-gray-700/50 p-4 hover:bg-gray-700 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-white">{{ Str::limit($post->title, 50) }}</h3>
                                <p class="mt-1 text-sm text-gray-400">
                                    {{ $post->category?->title ?? 'Chưa phân loại' }} • 
                                    {{ $post->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                            <div class="ml-4">
                                @if($post->is_published)
                                    <span class="rounded-full bg-green-500/20 px-3 py-1 text-xs font-medium text-green-400">
                                        Đã xuất bản
                                    </span>
                                @else
                                    <span class="rounded-full bg-yellow-500/20 px-3 py-1 text-xs font-medium text-yellow-400">
                                        Bản nháp
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-gray-400">
                        <flux:icon name="document-text" class="mx-auto size-12" />
                        <p class="mt-2">Chưa có bài đăng nào</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg bg-gray-800 p-6 shadow-lg">
            <h2 class="mb-4 text-xl font-bold text-white">Thao tác nhanh</h2>
            
            <div class="space-y-3">
                <flux:button variant="primary" class="w-full justify-start" :href="route('posts.create')" wire:navigate>
                    <flux:icon name="plus" class="size-4" />
                    Tạo bài đăng mới
                </flux:button>
                
                <flux:button variant="outline" class="w-full justify-start" :href="route('categories.create')" wire:navigate>
                    <flux:icon name="folder-plus" class="size-4" />
                    Thêm danh mục
                </flux:button>
                
                <flux:button variant="outline" class="w-full justify-start" :href="route('posts.index')" wire:navigate>
                    <flux:icon name="document-text" class="size-4" />
                    Quản lý bài đăng
                </flux:button>
                
                <flux:button variant="outline" class="w-full justify-start" :href="route('categories.index')" wire:navigate>
                    <flux:icon name="folder" class="size-4" />
                    Quản lý danh mục
                </flux:button>
            </div>

            <div class="mt-6 space-y-3 rounded-lg bg-gray-700/50 p-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Tuần này</span>
                    <span class="font-semibold text-white">{{ $this->postsThisWeek }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Tháng này</span>
                    <span class="font-semibold text-white">{{ $this->postsThisMonth }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Tỷ lệ xuất bản</span>
                    <span class="font-semibold text-white">
                        {{ $this->totalPosts > 0 ? round(($this->publishedPosts / $this->totalPosts) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:init', () => {
    // === BẮT ĐẦU SỬA LỖI JAVASCRIPT ===

    // --- BAR CHART ---
    const barCanvas = document.getElementById('postsChart');
    const barCtx = barCanvas.getContext('2d');
    let barChart; // Biến toàn cục cho Bar Chart
    
    const updateBarChart = () => {
        if (!barCanvas) return;
        const labels = JSON.parse(barCanvas.getAttribute('data-chart-labels'));
        const values = JSON.parse(barCanvas.getAttribute('data-chart-values'));
        
        if (barChart) {
            barChart.destroy();
        }
        
        barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Số bài đăng',
                    data: values,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: '#3B82F6',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#9CA3AF' },
                        grid: { color: '#374151' }
                    },
                    x: {
                        ticks: { color: '#9CA3AF' },
                        grid: { display: false }
                    }
                }
            }
        });
    };

    // --- PIE CHART ---
    const pieCanvas = document.getElementById('categoryChart');
    let pieChart; // Biến toàn cục cho Pie Chart
    
    const updatePieChart = () => {
        if (!pieCanvas) return;
        
        const pieCtx = pieCanvas.getContext('2d');
        // Lấy dữ liệu MỚI NHẤT từ attribute
        const pieChartData = JSON.parse(pieCanvas.getAttribute('data-chart-data')); 
        
        // Hủy biểu đồ cũ nếu đã tồn tại
        if (pieChart) {
            pieChart.destroy();
        }
        
        const colors = [
            '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B', 
            '#10B981', '#EF4444', '#06B6D4', '#F97316'
        ];
        
        while (pieChartData.length > colors.length) {
            colors.push(`rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.8)`);
        }
        
        // Vẽ biểu đồ mới
        pieChart = new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: pieChartData.map(item => item.label || 'N/A'),
                datasets: [{
                    data: pieChartData.map(item => parseInt(item.value) || 0),
                    backgroundColor: colors.slice(0, pieChartData.length),
                    borderColor: '#1F2937',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#9CA3AF',
                            padding: 15
                        }
                    }
                }
            }
        });
    };
    
    // --- KHỞI TẠO BIỂU ĐỒ ---
    updateBarChart();
    updatePieChart();
    
    // --- LẮNG NGHE SỰ KIỆN CẬP NHẬT TỪ LIVEWIRE ---
    Livewire.on('$refresh', () => {
        setTimeout(() => {
            updateBarChart();
            updatePieChart(); // Cập nhật cả Pie Chart
        }, 200);
    });
    
    Livewire.on('chart-updated', () => {
        setTimeout(() => {
            updateBarChart();
            updatePieChart(); // Cập nhật cả Pie Chart
        }, 200);
    });

    // === KẾT THÚC SỬA LỖI JAVASCRIPT ===
});
</script>
@endpush