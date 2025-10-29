@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')

<!-- Hero Section with Slideshow -->
<section class="mb-16 md:mb-24 scroll-reveal">
    <div x-data="slideshow()" x-init="init()" class="relative rounded-2xl overflow-hidden">
        <div class="aspect-w-16 aspect-h-9 md:aspect-h-7">
            @foreach($latestPosts->take(5) as $index => $post)
                <div x-show="currentSlide === {{ $index }}" 
                     class="absolute inset-0 transition-opacity duration-1000 ease-in-out" 
                     x-transition:enter="transition-opacity duration-1000 ease-in-out" 
                     x-transition:enter-start="opacity-0" 
                     x-transition:enter-end="opacity-100" 
                     x-transition:leave="transition-opacity duration-1000 ease-in-out" 
                     x-transition:leave-start="opacity-100" 
                     x-transition:leave-end="opacity-0">
                    <a href="#" class="block w-full h-full group">
                        <div class="absolute inset-0">
                            <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/1600x900' }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                        <div class="relative h-full flex flex-col justify-end p-6 sm:p-8 md:p-12 text-white">
                            <div x-show="currentSlide === {{ $index }}">
                                <p class="text-sm font-medium bg-primary-600 px-3 py-1 rounded-full self-start transform transition-all duration-500 ease-out" x-show="active" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">{{ optional($post->category)->title }}</p>
                                <h1 class="text-3xl md:text-5xl font-bold mt-4 leading-tight transition-all duration-500 ease-out delay-100" x-show="active" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">{{ $post->title }}</h1>
                                <p class="mt-3 text-white/80 max-w-2xl transition-all duration-500 ease-out delay-200" x-show="active" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">{{ $post->short_description }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
            @foreach($latestPosts->take(5) as $index => $post)
                <button @click="setSlide({{ $index }})" class="h-2 w-6 rounded-full transition-colors duration-300" :class="{ 'bg-white': currentSlide === {{ $index }}, 'bg-white/50 hover:bg-white/75': currentSlide !== {{ $index }} }"></button>
            @endforeach
        </div>

        <button @click="prevSlide()" class="slide-control absolute top-1/2 left-4 -translate-y-1/2 h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-colors z-10">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button @click="nextSlide()" class="slide-control absolute top-1/2 right-4 -translate-y-1/2 h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30 transition-colors z-10">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
    </div>
</section>

<!-- Recent Posts -->
<section class="mb-16 md:mb-24 scroll-reveal">
    <h2 class="text-3xl font-bold mb-8 text-center">Bài viết gần đây</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($latestPosts->skip(5)->take(4) as $post)
            <a href="#" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                    <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-neutral-900 dark:text-white group-hover:text-primary-600 transition-colors duration-200">{{ $post->title }}</h3>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">{{ $post->created_date }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>

<!-- Posts by Category -->
<section class="scroll-reveal">
    <div x-data="categoryTabs({{ $topCategories->take(3)->map(fn($c) => $c->id)->toJson() }})" x-init="init()">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8">
            <h2 class="text-3xl font-bold mb-4 sm:mb-0">Khám phá theo chủ đề</h2>
            <div class="flex gap-2 p-1 rounded-lg bg-neutral-100 dark:bg-neutral-800">
                @foreach($topCategories->take(3) as $category)
                    <button @click="activeTab = {{ $category->id }}" class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" :class="{ 'bg-white text-primary-600 shadow dark:bg-neutral-700': activeTab === {{ $category->id }}, 'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-200/50 dark:hover:bg-neutral-700/50': activeTab !== {{ $category->id }} }">{{ $category->title }}</button>
                @endforeach
            </div>
        </div>

        @foreach($topCategories->take(3) as $category)
            <div x-show="activeTab === {{ $category->id }}" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="grid md:grid-cols-3 gap-8">
                @foreach($category->posts()->published()->latest()->take(3) as $post)
                    <a href="#" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                        <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                            <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white group-hover:text-primary-600 transition-colors duration-200">{{ $post->title }}</h3>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2">{{ $post->created_date }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('slideshow', () => ({
        currentSlide: 0,
        totalSlides: {{ $latestPosts->take(5)->count() }},
        slideInterval: null,
        active: false,
        init() {
            this.slideInterval = setInterval(() => this.nextSlide(), 5000);
            this.$watch('currentSlide', () => {
                this.active = false;
                this.$nextTick(() => this.active = true);
            });
            this.$nextTick(() => this.active = true);
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        },
        setSlide(index) {
            this.currentSlide = index;
            clearInterval(this.slideInterval);
            this.slideInterval = setInterval(() => this.nextSlide(), 5000);
        }
    }));

    Alpine.data('categoryTabs', (categoryIds) => ({
        activeTab: null,
        init() {
            if (categoryIds && categoryIds.length > 0) {
                this.activeTab = categoryIds[0];
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
    .scroll-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .scroll-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush