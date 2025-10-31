@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')

    @if (isset($latestPosts) && $latestPosts->count() > 0)
        @php
            $slideshowPosts = $latestPosts->take(4);
            $remainingPosts = $latestPosts->slice(4);
        @endphp

        {{-- ====================================================== --}}
        {{-- ================== HERO SLIDESHOW ================== --}}
        {{-- ====================================================== --}}
        <section x-data="heroSlideshow({{ $slideshowPosts->map(function($post) { return ['title' => $post->title, 'category' => $post->category?->title, 'category_slug' => $post->category?->slug, 'author' => $post->author_name ?? 'Admin', 'date' => $post->created_date, 'slug' => $post->slug, 'image' => $post->banner_image_url ?? 'https://source.unsplash.com/random/1920x1080?sig='.$post->id]; })->toJson() }})" x-init="init()"
            class="relative w-full min-h-[90vh] flex items-center mb-24 overflow-hidden">
            
            {{-- Slides --}}
            <div class="absolute inset-0 w-full h-full">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeIndex === index"
                        x-transition:enter="transition ease-in-out duration-1000"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in-out duration-1000"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0 w-full h-full">
                        
                        {{-- Background Image with Ken Burns Effect --}}
                        <div class="absolute inset-0 w-full h-full bg-neutral-800" :class="{'ken-burns': activeIndex === index}">
                            <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    </div>
                </template>
            </div>

            {{-- Content --}}
            <div class="relative z-10 container mx-auto px-4">
                <div class="max-w-3xl text-center mx-auto">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="activeIndex === index" 
                             x-transition:enter="transition ease-out duration-1000 delay-300"
                             x-transition:enter-start="opacity-0 translate-y-8"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="flex flex-col items-center">
                            
                            <a :href="`{{ url('/danh-muc') }}/${slide.category_slug}`" x-show="slide.category" class="inline-block bg-white/10 backdrop-blur-md text-white text-sm font-bold px-4 py-2 rounded-full mb-6 transition hover:bg-white/20">
                                <span x-text="slide.category"></span>
                            </a>

                            <h1 class="text-4xl md:text-6xl font-extrabold text-white !leading-tight mb-6">
                                <a :href="`{{ url('/bai-viet') }}/${slide.slug}`" class="hover:opacity-80 transition-opacity">
                                    <span x-text="slide.title"></span>
                                </a>
                            </h1>

                            <div class="flex items-center justify-center gap-4 text-neutral-200">
                                <span x-text="slide.author"></span>
                                <span class="opacity-60">&bull;</span>
                                <span x-text="slide.date"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Navigation --}}
            <div class="absolute z-20 bottom-8 left-1/2 -translate-x-1/2 flex gap-3">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="setActive(index)" class="w-3 h-3 rounded-full transition-all duration-300"
                            :class="activeIndex === index ? 'bg-white scale-125' : 'bg-white/40 hover:bg-white/70'"></button>
                </template>
            </div>
            <button @click="prev()" class="absolute z-20 left-4 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/20 transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </button>
            <button @click="next()" class="absolute z-20 right-4 top-1/2 -translate-y-1/2 p-3 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/20 transition">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </button>
        </section>

        {{-- ====================================================== --}}
        {{-- ================= SECTION 1: LATEST ================== --}}
        {{-- ====================================================== --}}
        @if ($remainingPosts->count() > 0)
            <section class="mb-24 scroll-reveal">
                <div class="container mx-auto px-4">
                    <div class="flex items-baseline justify-between mb-10">
                        <h2 class="text-3xl md:text-4xl font-bold">Bài viết khác</h2>
                        <a href="{{ route('posts.public') }}"
                            class="font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors group flex items-center gap-2">
                            Xem tất cả
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($remainingPosts as $key => $post)
                            <div class="scroll-reveal-item" style="transition-delay: {{ $key * 100 }}ms">
                                <x-post-card :post="$post" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ====================================================== --}}
        {{-- ================= SECTION 2: CATEGORIES ============ --}}
        {{-- ====================================================== --}}
        @if ($topCategories->count() > 0)
            <section class="mb-24 scroll-reveal">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl md:text-4xl font-bold mb-2">Khám phá chủ đề</h2>
                        <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">Tìm đọc các bài viết theo những chủ đề mà bạn quan tâm.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach ($topCategories->take(4) as $key => $category)
                            <div class="scroll-reveal-item" style="transition-delay: {{ $key * 100 }}ms">
                                <a href="{{ route('categories.show.public', $category->slug) }}" class="group block relative rounded-2xl overflow-hidden aspect-square">
                                    <img src="{{ $category->banner_image_url ?? 'https://source.unsplash.com/random/800x800?sig=' . $key }}" 
                                         alt="{{ $category->title }}" 
                                         class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                                    <div class="absolute bottom-0 left-0 p-6">
                                        <h3 class="text-2xl font-bold text-white">{{ $category->title }}</h3>
                                        <p class="text-white/80 text-sm">{{ $category->posts_count }} bài viết</p>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ====================================================== --}}
        {{-- ================= SECTION 3: NEWSLETTER ============ --}}
        {{-- ====================================================== --}}
        <section class="mb-24 scroll-reveal">
            <div class="container mx-auto px-4">
                <div class="relative bg-neutral-100 dark:bg-neutral-900 rounded-3xl px-8 py-16 md:p-20 lg:p-24 overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-primary-200/50 dark:bg-primary-900/30 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-purple-200/50 dark:bg-purple-900/30 rounded-full blur-3xl"></div>
                    <div class="relative z-10 text-center max-w-2xl mx-auto">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">Tham gia cùng chúng tôi</h2>
                        <p class="text-neutral-600 dark:text-neutral-400 text-lg mb-8">Nhận những bài viết, tin tức và kiến thức mới nhất được gửi thẳng đến hộp thư của bạn hàng tuần.</p>
                        <form action="#" method="POST" class="w-full max-w-lg mx-auto">
                            <div class="flex flex-col sm:flex-row gap-4 p-2 rounded-xl bg-white/60 dark:bg-white/10 backdrop-blur-sm shadow-md">
                                <input type="email" placeholder="Nhập email của bạn..." required class="w-full px-5 py-3 rounded-lg border-0 bg-transparent focus:ring-2 focus:ring-primary-500 transition">
                                <button type="submit" class="px-8 py-3 rounded-lg bg-neutral-900 dark:bg-white text-white dark:text-black font-bold hover:bg-black dark:hover:bg-neutral-200 transition-colors">Đăng ký</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    @else
        {{-- Fallback if no posts at all --}}
        <section class="flex items-center justify-center h-[80vh]">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto text-neutral-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <p class="text-xl text-neutral-500 dark:text-neutral-400">Chào mừng! Hiện chưa có bài viết nào.</p>
            </div>
        </section>
    @endif

@endsection

@push('styles')
<style>
    .scroll-reveal, .scroll-reveal-item {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.8s cubic-bezier(0.5, 0, 0, 1), transform 0.8s cubic-bezier(0.5, 0, 0, 1);
    }
    .scroll-reveal.is-visible, .scroll-reveal-item.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    @keyframes ken-burns-animation {
        0% { transform: scale(1) rotate(0deg); }
        100% { transform: scale(1.1) rotate(1deg); }
    }
    .ken-burns img {
        animation: ken-burns-animation 7s ease-in-out infinite alternate-reverse both;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('heroSlideshow', (slidesData) => ({
        slides: slidesData,
        activeIndex: 0,
        autoplayInterval: null,
        init() {
            if (this.slides.length > 0) {
                this.startAutoplay();
            }
        },
        startAutoplay() {
            this.autoplayInterval = setInterval(() => {
                this.next();
            }, 7000); // 7 seconds per slide
        },
        stopAutoplay() {
            clearInterval(this.autoplayInterval);
        },
        next() {
            this.activeIndex = (this.activeIndex + 1) % this.slides.length;
        },
        prev() {
            this.activeIndex = (this.activeIndex - 1 + this.slides.length) % this.slides.length;
        },
        setActive(index) {
            this.activeIndex = index;
            this.stopAutoplay();
            this.startAutoplay(); // Restart timer on manual interaction
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
    }, { threshold: 0.05 });

    document.querySelectorAll('.scroll-reveal, .scroll-reveal-item').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush