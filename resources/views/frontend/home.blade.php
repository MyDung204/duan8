@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@php
    $heroPost = $latestPosts->first();
    $recentPosts = $latestPosts->skip(1);
@endphp

@section('content')

<!-- Hero Slideshow -->
@if($latestPosts->count() > 0)
<section class="mb-20 md:mb-28 scroll-reveal" x-data="heroSlider({{ $latestPosts->take(3)->map(fn($p) => [
    'title' => $p->title,
    'slug' => $p->slug,
    'short_description' => $p->short_description,
    'banner_image_url' => $p->banner_image_url,
    'created_date' => $p->created_date,
    'author_name' => $p->author_name,
    'category' => $p->category ? ['id' => $p->category->id, 'title' => $p->category->title] : null,
])->toJson() }})" x-init="init()">
    <div class="relative rounded-3xl overflow-hidden shadow-2xl">
        <div class="relative hero-aspect">
            <!-- Slides (fade/scale) -->
            <template x-for="(item, idx) in items" :key="idx">
                <a :href="`{{ url('/bai-viet') }}/${item.slug}`" class="absolute inset-0 block" x-show="current === idx"
                   x-transition:enter="transition ease-out duration-700"
                   x-transition:enter-start="opacity-0 scale-105"
                   x-transition:enter-end="opacity-100 scale-100"
                   x-transition:leave="transition ease-in duration-700"
                   x-transition:leave-start="opacity-100 scale-100"
                   x-transition:leave-end="opacity-0 scale-95">
                    <img :src="item.banner_image_url || 'https://via.placeholder.com/1600x900'" :alt="item.title" class="w-full h-full object-cover" />
                </a>
            </template>

            <!-- Controls -->
            <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-5 left-0 right-0 flex items-center justify-center gap-2 z-10">
                <template x-for="(item, idx) in items" :key="idx">
                    <button @click="go(idx)" class="w-2.5 h-2.5 rounded-full" :class="current === idx ? 'bg-white' : 'bg-white/50'"></button>
                </template>
                </div>
        </div>
    </div>
</section>
@else
<section class="mb-20 md:mb-28 scroll-reveal">
    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-neutral-100 to-neutral-200 dark:from-neutral-800 dark:to-neutral-900 flex items-center justify-center h-96">
        <div class="text-center">
            <svg class="w-16 h-16 mx-auto text-neutral-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-xl text-neutral-500 dark:text-neutral-400">Không có bài viết nào để hiển thị.</p>
        </div>
    </div>
</section>
@endif

<!-- Recent Posts -->
@if($recentPosts->count() > 0)
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
        @foreach($recentPosts as $index => $post)
            <a href="{{ route('posts.show.public', $post->slug) }}" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="aspect-[4/3] overflow-hidden relative">
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

<!-- Posts by Category -->
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
    Alpine.data('heroSlider', (items) => ({
        items: items || [],
        current: 0,
        timer: null,
        intervalMs: 5000,
        init() {
            if (this.items.length > 1) {
                this.start();
            }
        },
        start() {
            this.stop();
            this.timer = setInterval(() => this.next(), this.intervalMs);
        },
        stop() {
            if (this.timer) {
                clearInterval(this.timer);
                this.timer = null;
            }
        },
        next() {
            this.current = (this.current + 1) % this.items.length;
        },
        prev() {
            this.current = (this.current - 1 + this.items.length) % this.items.length;
        },
        go(index) {
            this.current = index;
            this.start();
        }
    }));
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
    /* Fallback aspect ratio if Tailwind aspect utilities are not available */
    /* 21:9 ratio for lower height (42.85% padding) */
    .hero-aspect { position: relative; padding-top: 42.85%; }
    @media (min-width: 768px) {
        .hero-aspect { padding-top: 38%; } /* Even lower on desktop */
    }
    .hero-aspect > a { position: absolute; inset: 0; }
    .hero-aspect img { width: 100%; height: 100%; object-fit: cover; }
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