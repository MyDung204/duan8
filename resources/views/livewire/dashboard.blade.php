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
        $now = now();
        $this->stats = Cache::remember('dashboard::stats', $now->copy()->addMinutes(5), function () use ($now) {
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
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek(),
                    $now->year,
                    $now->month,
                    $now->toDateString(),
                ])
                ->first();
        });
    }

    public function getKpiCardsProperty(): array
    {
        return [
            ['label' => 'Tổng số bài đăng', 'value' => $this->stats->total ?? 0, 'icon' => 'document-text'],
            ['label' => 'Bài đăng trong tuần', 'value' => $this->stats->this_week ?? 0, 'icon' => 'calendar'],
            ['label' => 'Bài đăng trong tháng', 'value' => $this->stats->this_month ?? 0, 'icon' => 'calendar-days'],
            ['label' => 'Đã xuất bản', 'value' => $this->stats->published ?? 0, 'icon' => 'check-circle'],
            ['label' => 'Bản nháp', 'value' => $this->stats->draft ?? 0, 'icon' => 'clipboard-document'],
            ['label' => 'Hôm nay', 'value' => $this->stats->today ?? 0, 'icon' => 'sun'],
        ];
    }

    public function getQuickActionsProperty(): array
    {
        return [
            ['label' => 'Tạo bài đăng mới', 'icon' => 'plus', 'route' => 'posts.create', 'variant' => 'primary'],
            ['label' => 'Thêm danh mục', 'icon' => 'folder-plus', 'route' => 'categories.create', 'variant' => 'outline'],
            ['label' => 'Quản lý bài đăng', 'icon' => 'document-text', 'route' => 'posts.index', 'variant' => 'outline'],
            ['label' => 'Quản lý danh mục', 'icon' => 'folder', 'route' => 'categories.index', 'variant' => 'outline'],
        ];
    }

    public function getPublicationRateProperty(): float
    {
        if (empty($this->stats->total)) {
            return 0;
        }
        return round(($this->stats->published / $this->stats->total) * 100, 1);
    }

    public function getChartDataProperty()
    {
        $now = now();
        return Cache::remember('dashboard::chartData::' . $this->chartTimeframe, $now->copy()->addMinutes(5), function () use ($now) {
            if ($this->chartTimeframe === 'week') {
                $posts = Post::query()
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                    ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
                    ->groupBy('date')
                    ->pluck('count', 'date');

                $data = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $formattedDate = $date->format('Y-m-d');
                    $data[] = [
                        'label' => $date->format('d/m'),
                        'value' => $posts->get($formattedDate) ?? 0,
                    ];
                }
                return $data;

            } elseif ($this->chartTimeframe === 'month') {
                $posts = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                    ->whereYear('created_at', $now->year)
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
                $startYear = $now->copy()->subYears(4)->year;
                $endYear = $now->year;

                $posts = Post::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
                    ->whereBetween('created_at', [
                        $now->copy()->subYears(4)->startOfYear(),
                        $now->copy()->endOfYear()
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
        $now = now();
        return Cache::remember('dashboard::postsByCategory::' . $this->chartTimeframe, $now->copy()->addMinutes(5), function () use ($now) {
            $query = Post::query()
                ->leftJoin('categories', 'categories.id', '=', 'posts.category_id')
                ->select(DB::raw('COALESCE(categories.title, "Chưa phân loại") as label'), DB::raw('COUNT(*) as count'))
                ->whereNotNull('posts.category_id');

            if ($this->chartTimeframe === 'week') {
                $query->whereBetween('posts.created_at', [$now->copy()->subDays(6)->startOfDay(), $now->copy()->endOfDay()]);
            } elseif ($this->chartTimeframe === 'month') {
                $query->whereYear('posts.created_at', $now->year);
            } elseif ($this->chartTimeframe === 'year') {
                $query->whereBetween('posts.created_at', [$now->copy()->subYears(4)->startOfYear(), $now->copy()->endOfYear()]);
            }

            $results = $query->groupBy('posts.category_id', 'categories.title')->get();

            if ($results->isEmpty()) {
                return [['label' => 'Chưa có dữ liệu', 'value' => 0]];
            }

            return $results->map(function ($item) {
                return [
                    'label' => (string) $item->label,
                    'value' => (int) $item->count,
                ];
            })->toArray();
        });
    }

    public function getRecentPostsProperty()
    {
        return Cache::remember('dashboard::recentPosts', now()->addMinutes(5), function () {
            return Post::query()
                ->select(['id','title','category_id','is_published','created_at'])
                ->with(['category:id,title'])
                ->latest('created_at')
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

    public function getIcon(string $name, string $class = '')
    {
        $icons = [
            'document-text' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>',
            'calendar' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>',
            'calendar-days' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/></svg>',
            'check-circle' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>',
            'clipboard-document' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z"/></svg>',
            'sun' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/></svg>',
            'arrow-right' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>',
            'plus' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>',
            'folder-plus' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/></svg>',
            'folder' => '<svg class="' . $class . '" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/></svg>',
        ];

        return $icons[$name] ?? '';
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
        @foreach ($this->kpiCards as $card)
            <div class="kpi-card rounded-lg bg-gradient-to-br from-gray-900 via-indigo-900 to-purple-900 p-6 shadow-lg shadow-white/10">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-white opacity-90">{{ $card['label'] }}</h3>
                        <p class="mt-2 text-3xl font-bold text-white">{{ $card['value'] }}</p>
                    </div>
                    {!! $this->getIcon($card['icon'], 'size-8 text-white opacity-50') !!}
                </div>
            </div>
        @endforeach
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
                    {!! $this->getIcon('arrow-right', 'size-4') !!}
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
                        {!! $this->getIcon('document-text', 'mx-auto size-12') !!}
                        <p class="mt-2">Chưa có bài đăng nào</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div id="quickActionsPanel" class="rounded-lg bg-gray-800 p-6 shadow-lg shadow-white/10">
            <h2 class="mb-4 text-xl font-bold text-white">Thao tác nhanh</h2>

            <div class="space-y-3">
                @foreach ($this->quickActions as $action)
                    <flux:button :variant="$action['variant']" class="w-full justify-start" :href="route($action['route'])" wire:navigate>
                        {!! $this->getIcon($action['icon'], 'size-4') !!}
                        {{ $action['label'] }}
                    </flux:button>
                @endforeach
            </div>

            <div class="mt-6 space-y-3 rounded-lg bg-gray-700/50 p-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Tuần này</span>
                    <span class="font-semibold text-white">{{ $this->stats->this_week ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Tháng này</span>
                    <span class="font-semibold text-white">{{ $this->stats->this_month ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Tỷ lệ xuất bản</span>
                    <span class="font-semibold text-white">
                        {{ $this->publicationRate }}%
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
<script>
    // Khai báo biến global để lưu trữ instance của biểu đồ
    let barChart = null;
    let pieChart = null;

    /**
     * Hàm này sẽ tìm các canvas và vẽ biểu đồ.
     * Nó cũng sẽ hủy các biểu đồ cũ nếu chúng tồn tại.
     */
    function initializeCharts() {

        // --- BAR CHART ---
        const barCanvas = document.getElementById('postsChart');
        if (barCanvas) {
            const barCtx = barCanvas.getContext('2d');
            
            // Đọc dữ liệu mới nhất từ thuộc tính data-* trên canvas
            const labels = JSON.parse(barCanvas.getAttribute('data-chart-labels'));
            const values = JSON.parse(barCanvas.getAttribute('data-chart-values'));
            


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

    // 1. Khởi chạy khi idle + panel hiển thị để giảm main-thread work
    function onIdle(cb){
        if (window.requestIdleCallback) { requestIdleCallback(cb, { timeout: 1500 }); }
        else { setTimeout(cb, 500); }
    }

    function observeAndInit() {
        const panels = [
            document.getElementById('postsChartPanel'),
            document.getElementById('categoryChartPanel')
        ].filter(Boolean);
        if (panels.length === 0) return (window.Chart ? initializeCharts() : document.addEventListener('chartjs:ready', initializeCharts));

        const io = new IntersectionObserver((entries, obs) => {
            if (entries.some(e => e.isIntersecting)) {
                obs.disconnect();
                if (window.Chart) { initializeCharts(); }
                else { document.addEventListener('chartjs:ready', initializeCharts, { once: true }); }
            }
        }, { root: null, threshold: 0.1 });
        panels.forEach(p => io.observe(p));
    }

    document.addEventListener('DOMContentLoaded', () => {
        onIdle(observeAndInit);
    });

    // 2. Chạy khi Livewire điều hướng đến trang này
    document.addEventListener('livewire:navigated', () => {
        onIdle(observeAndInit);
    });

    // 3. Chạy khi bộ lọc thời gian thay đổi (dispatch('chart-updated'))
    document.addEventListener('livewire:init', () => {
        Livewire.on('chart-updated', () => {
            onIdle(observeAndInit);
        });
    });

</script>
@endpush