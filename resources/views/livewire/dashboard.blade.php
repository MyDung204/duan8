<?php

use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Volt\Component;

new class extends Component
{
    public string $chartTimeframe = 'month';

    public ?object $stats = null;

    public function mount(): void
    {
        $this->loadStats();
    }

    public function loadStats(): void
    {
        $this->stats = Cache::remember('dashboard::stats', now()->addMinutes(5), function () {
            return DB::table('posts')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(IF(is_published, 1, 0)) as published'),
                    DB::raw('SUM(IF(NOT is_published, 1, 0)) as draft'),
                    DB::raw('SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as this_week'),
                    DB::raw('SUM(CASE WHEN YEAR(created_at) = ? AND MONTH(created_at) = ? THEN 1 ELSE 0 END) as this_month'),
                    DB::raw('SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today')
                )
                ->setBindings([
                    now()->startOfWeek(),
                    now()->endOfWeek(),
                    now()->year,
                    now()->month,
                    today(),
                ])
                ->first();
        });
    }

    public function getTotalPostsProperty(): int
    {
        return $this->stats->total ?? 0;
    }

    public function getPublishedPostsProperty(): int
    {
        return $this->stats->published ?? 0;
    }

    public function getDraftPostsProperty(): int
    {
        return $this->stats->draft ?? 0;
    }

    public function getPostsThisWeekProperty(): int
    {
        return $this->stats->this_week ?? 0;
    }

    public function getPostsThisMonthProperty(): int
    {
        return $this->stats->this_month ?? 0;
    }

    public function getPostsTodayProperty(): int
    {
        return $this->stats->today ?? 0;
    }

    public function getChartDataProperty()
    {
        return Cache::remember('dashboard::chartData::' . $this->chartTimeframe, now()->addMinutes(5), function () {
            if ($this->chartTimeframe === 'week') {
                $posts = Post::query()
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                    ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                    ->groupBy('date')
                    ->pluck('count', 'date');

                $data = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $formattedDate = $date->format('Y-m-d');
                    $data[] = [
                        'label' => $date->format('d/m'),
                        'value' => $posts->get($formattedDate) ?? 0,
                    ];
                }
                return $data;

            } elseif ($this->chartTimeframe === 'month') {
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
        });
    }

    public function getPostsByCategoryProperty()
    {
        return Cache::remember('dashboard::postsByCategory::' . $this->chartTimeframe, now()->addMinutes(5), function () {
            $query = Post::query()
                ->select('category_id', DB::raw('COUNT(*) as count'))
                ->whereNotNull('category_id');

            if ($this->chartTimeframe === 'week') {
                $query->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]);
            } elseif ($this->chartTimeframe === 'month') {
                $query->whereYear('created_at', now()->year);
            } elseif ($this->chartTimeframe === 'year') {
                $query->whereBetween('created_at', [now()->subYears(4)->startOfYear(), now()->endOfYear()]);
            }

            $results = $query->groupBy('category_id')->get();

            if ($results->isEmpty()) {
                return [['label' => 'Chưa có dữ liệu', 'value' => 0]];
            }

            $categoryIds = $results->pluck('category_id');
            $categories = Category::whereIn('id', $categoryIds)->pluck('title', 'id');

            return $results->map(function ($item) use ($categories) {
                return [
                    'label' => $categories->get($item->category_id) ?? 'Chưa phân loại',
                    'value' => (int)$item->count,
                ];
            })->toArray();
        });
    }

    public function getRecentPostsProperty()
    {
        return Cache::remember('dashboard::recentPosts', now()->addMinutes(5), function () {
            return Post::with('category')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });
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

{{-- BẮT ĐẦU THẺ DIV GỐC DUY NHẤT --}}
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

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

        {{-- Lớp gradient chung: bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 --}}

        <div class="kpi-card rounded-lg bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 p-6 shadow-lg shadow-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Tổng số bài đăng</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->totalPosts }}</p>
                </div>
                <flux:icon name="document-text" class="size-8 text-white opacity-50" />
            </div>
        </div>

        <div class="kpi-card rounded-lg bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 p-6 shadow-lg shadow-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Bài đăng trong tuần</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->postsThisWeek }}</p>
                </div>
                <flux:icon name="calendar" class="size-8 text-white opacity-50" />
            </div>
        </div>

        <div class="kpi-card rounded-lg bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 p-6 shadow-lg shadow-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Bài đăng trong tháng</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->postsThisMonth }}</p>
                </div>
                <flux:icon name="calendar-days" class="size-8 text-white opacity-50" />
            </div>
        </div>

        <div class="kpi-card rounded-lg bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 p-6 shadow-lg shadow-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Đã xuất bản</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->publishedPosts }}</p>
                </div>
                <flux:icon name="check-circle" class="size-8 text-white opacity-50" />
            </div>
        </div>

        <div class="kpi-card rounded-lg bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 p-6 shadow-lg shadow-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Bản nháp</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->draftPosts }}</p>
                </div>
                <flux:icon name="clipboard-document" class="size-8 text-white opacity-50" />
            </div>
        </div>

        <div class="kpi-card rounded-lg bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 p-6 shadow-lg shadow-white/10">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-white opacity-90">Hôm nay</h3>
                    <p class="mt-2 text-3xl font-bold text-white">{{ $this->postsToday }}</p>
                </div>
                <flux:icon name="sun" class="size-8 text-white opacity-50" />
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div id="postsChartPanel" class="rounded-lg bg-gray-800 p-6 shadow-lg shadow-white/10">
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

        <div id="categoryChartPanel" class="rounded-lg bg-gray-800 p-6 shadow-lg shadow-white/10">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-white">Phân bố theo danh mục</h2>
            </div>

            <div class="chart-container" style="height: 400px; width: 100%; position: relative;">
                <canvas id="categoryChart" data-chart-data="{{ json_encode($this->postsByCategory) }}"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div id="recentPostsPanel" class="lg:col-span-2 rounded-lg bg-gray-800 p-6 shadow-lg shadow-white/10">
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

        <div id="quickActionsPanel" class="rounded-lg bg-gray-800 p-6 shadow-lg shadow-white/10">
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
                    <span class="font-semibold text-white">{{ $this->postsThisMonth }}</p>
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

    {{-- CSS nội tuyến cho hiệu ứng hover và viền --}}
    <style>
        .kpi-card {
            transition: all 0.3s ease-in-out;
        }
        .kpi-card:hover {
            transform: scale(1.03);
            /* Thay đổi bóng hover thành màu trắng mờ hơn */
            box-shadow: 0 10px 15px -3px rgba(255, 255, 255, 0.1), 0 4px 6px -4px rgba(255, 255, 255, 0.1);
        }
        /* Bỏ các bóng màu riêng lẻ vì đã có màu nền gradient chung */

        /* Giữ lại viền màu cho các panel */
        #postsChartPanel   { border-top: 4px solid #3B82F6; } /* Blue-500 */
        #categoryChartPanel { border-top: 4px solid #8B5CF6; } /* Purple-500 */
        #recentPostsPanel  { border-top: 4px solid #22C55E; } /* Green-500 */
        #quickActionsPanel { border-top: 4px solid #F59E0B; } /* Yellow-500 */
    </style>

</div>
{{-- KẾT THÚC THẺ DIV GỐC DUY NHẤT --}}

{{-- ====================================================================== --}}
{{-- BẮT ĐẦU PHẦN SCRIPT ĐÃ SỬA --}}
{{-- ====================================================================== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Khai báo biến global để lưu trữ instance của biểu đồ
    let barChart = null;
    let pieChart = null;

    /**
     * Hàm này sẽ tìm các canvas và vẽ biểu đồ.
     * Nó cũng sẽ hủy các biểu đồ cũ nếu chúng tồn tại.
     */
    function initializeCharts() {
        console.log('Running initializeCharts...');
        // --- BAR CHART ---
        const barCanvas = document.getElementById('postsChart');
        if (barCanvas) {
            const barCtx = barCanvas.getContext('2d');
            
            // Đọc dữ liệu mới nhất từ thuộc tính data-* trên canvas
            const labels = JSON.parse(barCanvas.getAttribute('data-chart-labels'));
            const values = JSON.parse(barCanvas.getAttribute('data-chart-values'));
            
            console.log('Bar Chart Data:', { labels: labels, values: values }); // Log data

            // Hủy biểu đồ cũ nếu tồn tại
            if (barChart) {
                barChart.destroy();
            }

            // Tạo biểu đồ mới và lưu instance vào biến global
            barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Số bài đăng',
                        data: values,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: '#3B82F6',
                        borderWidth: 1,
                        // ===================================
                        // THÊM HIỆU ỨNG TĂNG CƯỜNG (HOVER EFFECT)
                        hoverBackgroundColor: 'rgba(59, 130, 246, 1)', // Màu đậm hơn khi hover
                        hoverBorderColor: 'rgba(59, 130, 246, 1)',
                        hoverBorderWidth: 2 
                        // ===================================
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    },
                    plugins: { 
                        legend: { display: false },
                        tooltip: { // Tùy chỉnh Tooltip để hiển thị tốt hơn
                            backgroundColor: '#1F2937',
                            titleColor: '#FFFFFF',
                            bodyColor: '#D1D5DB',
                        }
                    },
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
        }

        // --- PIE CHART ---
        const pieCanvas = document.getElementById('categoryChart');
        if (pieCanvas) {
            const pieCtx = pieCanvas.getContext('2d');
            const pieChartData = JSON.parse(pieCanvas.getAttribute('data-chart-data'));

            // Hủy biểu đồ cũ nếu tồn tại
            if (pieChart) {
                pieChart.destroy();
            }

            // Dữ liệu màu (giữ nguyên từ code gốc của bạn)
            const colors = [
                '#3B82F6', '#8B5CF6', '#EC4899', '#F59E0B',
                '#10B981', '#EF4444', '#06B6D4', '#F97316'
            ];
            while (pieChartData.length > colors.length) {
                colors.push(`rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.8)`);
            }

            // Tạo biểu đồ mới và lưu instance vào biến global
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
                    animateRotate: true,
                    animateScale: true,
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuad'
                    },
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
        }
    }

    // --- BỘ LẮNG NGHE SỰ KIỆN ---

    // 1. Chạy khi tải trang lần đầu (F5)
    document.addEventListener('DOMContentLoaded', () => {
        // Tăng delay lên 100ms
        setTimeout(initializeCharts, 100); 
    });

    // 2. Chạy khi Livewire điều hướng đến trang này
    document.addEventListener('livewire:navigated', () => {
        // Tăng delay lên 100ms
        setTimeout(initializeCharts, 100);
    });

    // 3. Chạy khi bộ lọc thời gian thay đổi (dispatch('chart-updated'))
    document.addEventListener('livewire:init', () => {
        Livewire.on('chart-updated', () => {
            // Tăng delay lên 100ms
            setTimeout(initializeCharts, 100);
        });
    });

</script>
@endpush