@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@php
    /* * ĐÃ XÓA BLOCK @php cũ (định nghĩa $heroPost, $recentPosts)
     * vì logic mới sẽ được xử lý trực tiếp bên dưới.
     */
@endphp

@section('content')

{{-- ================ BẮT ĐẦU PHẦN HERO MỚI ================ --}}
<section class="mb-20 md:mb-28 scroll-reveal">
    {{-- =============== PHẦN 1: HERO (TRENDING) =============== --}}
    {{-- ĐÃ SỬA: Dùng $latestPosts thay vì $trendingPosts để khớp với Route --}}
    @if(isset($latestPosts) && $latestPosts->count() >= 3)
        @php
            // Lấy 1 bài chính và 2 bài phụ từ $latestPosts
            $mainHeroPost = $latestPosts->first();
            $sideHeroPosts = $latestPosts->slice(1, 2);
        @endphp
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-12">
            
            {{-- Bài viết chính (Bên trái) --}}
            <div class="lg:col-span-2 group relative block bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
                {{-- ĐÃ SỬA: Dùng route 'posts.show.public' --}}
                <a href="{{ route('posts.show.public', $mainHeroPost->slug) }}">
                    <div class="h-[450px] w-full">
                        @if($mainHeroPost->banner_image_url)
                            {{-- ĐÃ SỬA: Dùng accessor 'banner_image_url' từ Model Post --}}
                            <img src="{{ $mainHeroPost->banner_image_url }}" alt="{{ $mainHeroPost->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                        @else
                            {{-- Placeholder nếu không có ảnh --}}
                            <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-xl">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>
                    <div class="absolute bottom-0 left-0 w-full p-6 bg-gradient-to-t from-black/80 to-transparent">
                        <div class="text-xs font-semibold text-white/90 uppercase tracking-wider mb-2">
                            @if($mainHeroPost->category)
                                {{-- ĐÃ SỬA: Model Category dùng 'title' --}}
                                <span class="bg-indigo-600 py-1 px-2.5 rounded">{{ $mainHeroPost->category->title }}</span>
                            @endif
                            {{-- ĐÃ SỬA: Dùng accessor 'created_date' từ Model Post --}}
                            <span class="ml-2">{{ $mainHeroPost->created_date }}</span>
                        </div>
                        <h3 class="text-2xl lg:text-3xl font-bold text-white transition-colors duration-200 mb-2 leading-tight">
                            {{-- ĐÃ SỬA: Dùng helper chuẩn của Laravel --}}
                            {{ \Illuminate\Support\Str::limit($mainHeroPost->title, 60, '...') }}
                        </h3>
                        <div class="flex items-center text-sm text-gray-200">
                            {{-- ĐÃ SỬA: Dùng trường 'author_name' từ Model Post --}}
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($mainHeroPost->author_name ?? 'U') }}&color=EBF4FF&background=7F9CF5&size=40" alt="{{ $mainHeroPost->author_name ?? 'User' }}" class="w-6 h-6 rounded-full mr-2 border-2 border-white/50" loading="lazy">
                            <span>{{ $mainHeroPost->author_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- 2 Bài viết phụ (Bên phải) --}}
            <div class="lg:col-span-1 space-y-6">
                @foreach($sideHeroPosts as $post)
                <div class="group relative block bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden h-[213px]">
                    {{-- ĐÃ SỬA: Dùng route 'posts.show.public' --}}
                    <a href="{{ route('posts.show.public', $post->slug) }}">
                        @if($post->banner_image_url)
                             {{-- ĐÃ SỬA: Dùng accessor 'banner_image_url' từ Model Post --}}
                            <img src="{{ $post->banner_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                        @else
                             {{-- Placeholder nếu không có ảnh --}}
                            <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded-xl">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black/80 to-transparent">
                            <div class="text-xs font-semibold text-white/90 uppercase tracking-wider mb-1">
                                @if($post->category)
                                    {{-- ĐÃ SỬA: Model Category dùng 'title' --}}
                                    <span class="bg-indigo-600 py-0.5 px-2 rounded">{{ $post->category->title }}</span>
                                @endif
                            </div>
                            <h3 class="text-base font-bold text-white transition-colors duration-200 leading-tight line-clamp-2">
                                {{ $post->title }}
                            </h3>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    {{-- Đây là khối 'else' nếu không đủ 3 bài viết --}}
    @elseif($latestPosts->count() > 0)
        {{-- Xử lý nếu có ít hơn 3 bài (ví dụ: chỉ hiển thị bài chính) --}}
        @php $mainHeroPost = $latestPosts->first(); @endphp
        <div class="lg:col-span-2 group relative block bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
            <a href="{{ route('posts.show.public', $mainHeroPost->slug) }}">
                <div class="h-[450px] w-full">
                    <img src="{{ $mainHeroPost->banner_image_url ?? 'https://via.placeholder.com/1600x900' }}" alt="{{ $mainHeroPost->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" loading="lazy">
                </div>
                <div class="absolute bottom-0 left-0 w-full p-6 bg-gradient-to-t from-black/80 to-transparent">
                    <div class="text-xs font-semibold text-white/90 uppercase tracking-wider mb-2">
                        @if($mainHeroPost->category)
                            <span class="bg-indigo-600 py-1 px-2.5 rounded">{{ $mainHeroPost->category->title }}</span>
                        @endif
                        <span class="ml-2">{{ $mainHeroPost->created_date }}</span>
                    </div>
                    <h3 class="text-2xl lg:text-3xl font-bold text-white transition-colors duration-200 mb-2 leading-tight">
                        {{ \Illuminate\Support\Str::limit($mainHeroPost->title, 60, '...') }}
                    </h3>
                    <div class="flex items-center text-sm text-gray-200">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($mainHeroPost->author_name ?? 'U') }}&color=EBF4FF&background=7F9CF5&size=40" alt="{{ $mainHeroPost->author_name ?? 'User' }}" class="w-6 h-6 rounded-full mr-2 border-2 border-white/50" loading="lazy">
                        <span>{{ $mainHeroPost->author_name ?? 'N/A' }}</span>
                    </div>
                </div>
            </a>
        </div>
    @else
        {{-- Giữ lại phần thông báo nếu không có bài viết nào --}}
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 flex items-center justify-center h-96">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto text-neutral-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-xl text-neutral-500 dark:text-neutral-400">Không có bài viết nào để hiển thị.</p>
            </div>
        </div>
    @endif
    {{-- =============== KẾT THÚC PHẦN 1: HERO (TRENDING) =============== --}}
</section>
{{-- ================ KẾT THÚC PHẦN HERO MỚI ================ --}}


{{-- ĐÃ SỬA: Chỉ hiển thị section này nếu có nhiều hơn 3 bài (vì 3 bài đầu đã ở Hero) --}}
@if($latestPosts->count() > 3)
<section class="mb-20 md:mb-28 scroll-reveal">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h2 class="text-3xl md:text-4xl font-bold mb-2">Bài viết gần đây</h2>
            <p class="text-neutral-600 dark:text-neutral-400">Những bài viết mới nhất từ chúng tôi</p>
        </div>
        <a href="{{ route('posts.public') }}" class="hidden md:inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition shadow-sm hover:shadow-md">
            Xem tất cả
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- ĐÃ SỬA: Bắt đầu từ bài thứ 4 (skip 3 bài đầu tiên đã dùng cho Hero) --}}
        @foreach($latestPosts->skip(3) as $index => $post)
            <a href="{{ route('posts.show.public', $post->slug) }}" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="aspect-[4/3] overflow-hidden relative">
                    {{-- Giữ nguyên logic ảnh --}}
                    <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    @if($post->category)
                        <span class="absolute top-4 left-4 px-3 py-1 text-xs font-semibold bg-primary-600 text-white rounded-full backdrop-blur-sm">{{ $post->category->title }}</span>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-bold text-neutral-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200 line-clamp-2 mb-2">{{ $post->title }}</h3>
                    @if($post->short_description)
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2 mb-3">{{ $post->short_description }}</p>
                    @endif
                    <div class="flex items-center gap-3 text-xs text-neutral-500">
                         {{-- Giữ nguyên logic author/date --}}
                        <span>{{ $post->author_name ?? 'Admin' }}</span>
                        <span>&bull;</span>
                        <span>{{ $post->created_date }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <div class="mt-8 text-center md:hidden">
        <a href="{{ route('posts.public') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary-600 text-white font-semibold hover:bg-primary-700 transition">
            Xem tất cả bài viết
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
</section>
@endif

@if($topCategories->count() > 0)
<section class="scroll-reveal">
    <div x-data="categoryTabs({{ $topCategories->map(fn($c) => $c->id)->toJson() }})" x-init="init()">
        <div class="flex flex-col items-center mb-10">
            <div class="mb-6 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-2">Theo danh mục</h2>
                <p class="text-neutral-600 dark:text-neutral-400">Tìm kiếm bài viết theo danh mục yêu thích</p>
            </div>
            <div class="w-full max-w-6xl">
                <div class="flex flex-wrap gap-2 p-1.5 rounded-xl bg-neutral-100 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 justify-center">
                    @foreach($topCategories as $category)
                        <button @click="setActiveTab({{ $category->id }})" 
                                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 whitespace-nowrap"
                                :class="{ 'bg-white text-primary-600 shadow-md dark:bg-neutral-700 dark:text-primary-400': activeTab === {{ $category->id }}, 'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-200/50 dark:hover:bg-neutral-700/50': activeTab !== {{ $category->id }} }">
                            {{ $category->title }}
                            @if($category->posts_count)
                                <span class="ml-1 text-xs opacity-70">({{ $category->posts_count }})</span>
                            @endif
                        </button>
                @endforeach
                </div>
            </div>
        </div>

        <div x-show="activeTab" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div x-show="loading" class="grid place-items-center h-64">
                <div class="text-center">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-primary-200 border-t-primary-600 mb-4"></div>
                    <p class="text-neutral-500 dark:text-neutral-400">Đang tải...</p>
                </div>
            </div>
            <div x-show="!loading && posts.length > 0" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="grid md:grid-cols-3 gap-6">
                <template x-for="post in posts" :key="post.id">
                    <a :href="`{{ url('/bai-viet') }}/${post.slug}`" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="aspect-[4/3] overflow-hidden relative">
                            <img :src="post.banner_image_url ? post.banner_image_url : 'https://via.placeholder.com/800x600'" :alt="post.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-neutral-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200 line-clamp-2 mb-2" x-text="post.title"></h3>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 line-clamp-2 mb-3" x-text="post.short_description || ''"></p>
                            <p class="text-xs text-neutral-500" x-text="post.created_date"></p>
                        </div>
                    </a>
                </template>
            </div>
            <div x-show="!loading && posts.length === 0" class="grid place-items-center h-64">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-neutral-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                <p class="text-neutral-500 dark:text-neutral-400">Không có bài viết nào trong danh mục này.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-10 text-center">
        <a href="{{ route('categories.public') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-gradient-to-r from-primary-600 to-purple-600 text-white font-semibold hover:shadow-lg hover:scale-105 transition transform duration-300">
            Xem tất cả danh mục
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    // ĐÃ XÓA: Alpine.data('heroSlider', ...) vì không còn dùng slideshow
    
    // Giữ nguyên Alpine.data('categoryTabs', ...)
    Alpine.data('categoryTabs', (categoryIds) => ({
        activeTab: null,
        loading: false,
        posts: [],
        init() {
            if (categoryIds && categoryIds.length > 0) {
                this.activeTab = categoryIds[0];
                this.fetchPosts(this.activeTab);
            }
        },
        setActiveTab(categoryId) {
            this.activeTab = categoryId;
            this.fetchPosts(categoryId);
        },
        async fetchPosts(categoryId) {
            this.loading = true;
            this.posts = [];
            try {
                // ĐÃ SỬA LỖI: Quay lại dùng {{ url('/') }} để tránh lỗi UrlGenerationException
                const response = await fetch(`{{ url('/') }}/api/categories/${categoryId}/posts`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                this.posts = data;
            } catch (error) {
                console.error('Error fetching posts:', error);
                this.posts = [];
            } finally {
                this.loading = false;
            }
        }
    }));
});

// Giữ nguyên IntersectionObserver
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            } 
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.scroll-reveal').forEach(section => {
        observer.observe(section);
    });
});
</script>

<style>
    /* ĐÃ XÓA: CSS cho .hero-aspect vì không còn dùng slideshow */
    
    /* Giữ nguyên các style còn lại */
    .scroll-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .scroll-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush