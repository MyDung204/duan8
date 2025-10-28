@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@section('content')

<!-- Featured Post Slideshow -->
<section class="mb-12">
    <div id="slideshow" class="relative h-[500px] rounded-2xl overflow-hidden text-white">
        @foreach($latestPosts->take(5) as $index => $post)
            <div class="slide absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'opacity-100' : 'opacity-0' }}" data-slide="{{ $index }}">
                <a href="#" class="block w-full h-full group">
                    <div class="absolute inset-0 bg-neutral-800">
                        <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/1600x900' }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                    <div class="relative h-full flex flex-col justify-end p-8 md:p-12">
                        <p class="text-sm font-medium bg-indigo-600 px-3 py-1 rounded-full self-start transform translate-y-full opacity-0 transition-all duration-500 ease-out slide-active:translate-y-0 slide-active:opacity-100">{{ optional($post->category)->title }}</p>
                        <h1 class="text-3xl md:text-5xl font-bold mt-4 leading-tight transform translate-y-full opacity-0 transition-all duration-500 ease-out delay-100 slide-active:translate-y-0 slide-active:opacity-100">{{ $post->title }}</h1>
                        <p class="mt-2 text-white/80 max-w-2xl transform translate-y-full opacity-0 transition-all duration-500 ease-out delay-200 slide-active:translate-y-0 slide-active:opacity-100">{{ $post->short_description }}</p>
                    </div>
                </a>
            </div>
        @endforeach

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2">
            @foreach($latestPosts->take(5) as $index => $post)
                <button class="slide-indicator h-2 w-6 rounded-full bg-white/50 transition-colors {{ $index === 0 ? 'bg-white' : '' }}" data-slide-to="{{ $index }}"></button>
            @endforeach
        </div>

        <button class="slide-control absolute top-1/2 left-4 -translate-y-1/2 h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30" data-direction="-1">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button class="slide-control absolute top-1/2 right-4 -translate-y-1/2 h-10 w-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center hover:bg-white/30" data-direction="1">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
    </div>
</section>

<!-- Recent Posts -->
<section class="mb-12">
    <h2 class="text-2xl font-bold mb-6">Bài viết gần đây</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($latestPosts->skip(5)->take(4) as $post)
            <a href="#" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-[4/3] overflow-hidden">
                    <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-4">
                    <h3 class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $post->title }}</h3>
                    <p class="text-xs text-neutral-500 mt-1">{{ $post->created_date }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>

<!-- Posts by Category -->
<section class="mb-12">
    <div id="category-posts">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold">Bài viết theo danh mục</h2>
            <div class="flex gap-2" id="category-tabs">
                @foreach($topCategories->take(3) as $index => $category)
                    <button class="category-tab px-4 py-2 rounded-lg text-sm font-medium {{ $index === 0 ? 'bg-indigo-600 text-white' : 'bg-neutral-100 dark:bg-neutral-800' }}" data-category="{{ $category->id }}">{{ $category->title }}</button>
                @endforeach
            </div>
        </div>

        @foreach($topCategories->take(3) as $index => $category)
            <div class="category-content grid md:grid-cols-3 gap-8 {{ $index === 0 ? '' : 'hidden' }}" data-category-content="{{ $category->id }}">
                @foreach($category->posts()->published()->latest()->take(3) as $post)
                    <a href="#" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-4">
                            <h3 class="mt-1 text-lg font-semibold text-neutral-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $post->title }}</h3>
                            <p class="text-xs text-neutral-500 mt-1">{{ $post->created_date }}</p>
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
document.addEventListener('DOMContentLoaded', function () {
    // Slideshow
    const slideshow = document.getElementById('slideshow');
    if (slideshow) {
        const slides = slideshow.querySelectorAll('.slide');
        const indicators = slideshow.querySelectorAll('.slide-indicator');
        const controls = slideshow.querySelectorAll('.slide-control');
        let currentSlide = 0;
        let slideInterval = setInterval(nextSlide, 5000);

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('opacity-100', i === index);
                slide.classList.toggle('opacity-0', i !== index);
                // Add/remove active class for text animations
                slide.querySelectorAll('p, h1').forEach(el => {
                    el.classList.toggle('slide-active', i === index);
                });
            });
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('bg-white', i === index);
                indicator.classList.toggle('bg-white/50', i !== index);
            });
            currentSlide = index;
        }

        function nextSlide() {
            showSlide((currentSlide + 1) % slides.length);
        }

        function prevSlide() {
            showSlide((currentSlide - 1 + slides.length) % slides.length);
        }

        indicators.forEach(indicator => {
            indicator.addEventListener('click', () => {
                showSlide(parseInt(indicator.dataset.slideTo));
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            });
        });

        controls.forEach(control => {
            control.addEventListener('click', () => {
                if (control.dataset.direction === '1') {
                    nextSlide();
                } else {
                    prevSlide();
                }
                clearInterval(slideInterval);
                slideInterval = setInterval(nextSlide, 5000);
            });
        });
        // Initialize first slide's animation
        showSlide(0);
    }

    // Category Tabs
    const categoryPosts = document.getElementById('category-posts');
    if (categoryPosts) {
        const tabs = categoryPosts.querySelectorAll('.category-tab');
        const contents = categoryPosts.querySelectorAll('.category-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const categoryId = tab.dataset.category;

                tabs.forEach(t => {
                    t.classList.toggle('bg-indigo-600', t === tab);
                    t.classList.toggle('text-white', t === tab);
                    t.classList.toggle('bg-neutral-100', t !== tab);
                    t.classList.toggle('dark:bg-neutral-800', t !== tab);
                });

                contents.forEach(content => {
                    content.classList.toggle('hidden', content.dataset.categoryContent !== categoryId);
                });
            });
        });
    }
});
</script>
@endpush