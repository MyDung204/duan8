@extends('frontend.layouts.app')

@section('title', 'Trang chủ')

@php
    $heroPost = $latestPosts->first();
    $recentPosts = $latestPosts->skip(1);
@endphp

@section('content')

<!-- Static Hero Section -->
@if($heroPost)
<section class="mb-16 md:mb-24 scroll-reveal">
    <div class="relative rounded-2xl overflow-hidden">
        <div class="aspect-w-16 aspect-h-9 md:aspect-h-7">
            <a href="{{ route('posts.show.public', $heroPost->slug) }}" class="block w-full h-full group">
                <div class="absolute inset-0">
                    <img src="{{ $heroPost->banner_image_url ?? 'https://via.placeholder.com/1600x900' }}" alt="{{ $heroPost->title }}" class="w-full h-full object-cover">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                <div class="relative h-full flex flex-col justify-end p-6 sm:p-8 md:p-12 text-white">
                    <div>
                        <a href="{{ route('posts.public', ['category' => optional($heroPost->category)->id]) }}" class="text-sm font-medium bg-primary-600 px-3 py-1 rounded-full self-start">{{ optional($heroPost->category)->title }}</a>
                        <h1 class="text-3xl md:text-5xl font-bold mt-4 leading-tight">{{ $heroPost->title }}</h1>
                        <p class="mt-3 text-white/80 max-w-2xl">{{ $heroPost->short_description }}</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>
@else
<section class="mb-16 md:mb-24 scroll-reveal">
    <div class="relative rounded-2xl overflow-hidden">
        <div class="aspect-w-16 aspect-h-9 md:aspect-h-7 bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center">
            <p class="text-neutral-500 dark:text-neutral-400">Không có bài viết nào để hiển thị.</p>
        </div>
    </div>
</section>
@endif

<!-- Recent Posts -->
@if($recentPosts->count() > 0)
<section class="mb-16 md:mb-24 scroll-reveal">
    <h2 class="text-3xl font-bold mb-8 text-center">Bài viết gần đây</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($recentPosts as $post)
            <a href="{{ route('posts.show.public', $post->slug) }}" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
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
@endif

<!-- Posts by Category -->
@if($topCategories->count() > 0)
<section class="scroll-reveal">
    <div x-data="categoryTabs({{ $topCategories->take(3)->map(fn($c) => $c->id)->toJson() }})" x-init="init()">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8">
            <a href="{{ route('categories.public') }}" class="text-3xl font-bold mb-4 sm:mb-0">Khám phá theo chủ đề</a>
            <div class="flex gap-2 p-1 rounded-lg bg-neutral-100 dark:bg-neutral-800">
                @foreach($topCategories->take(3) as $category)
                    <button @click="setActiveTab({{ $category->id }})" class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" :class="{ 'bg-white text-primary-600 shadow dark:bg-neutral-700': activeTab === {{ $category->id }}, 'text-neutral-600 dark:text-neutral-300 hover:bg-neutral-200/50 dark:hover:bg-neutral-700/50': activeTab !== {{ $category->id }} }">{{ $category->title }}</button>
                @endforeach
            </div>
        </div>

        <div x-show="activeTab" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div x-show="loading" class="grid place-items-center h-32">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
            </div>
            <div x-show="!loading && posts.length > 0" class="grid md:grid-cols-3 gap-8">
                <template x-for="post in posts" :key="post.id">
                    <a :href="`{{ url('/bai-viet') }}/${post.slug}`" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300">
                        <div class="aspect-w-4 aspect-h-3 overflow-hidden">
                            <img :src="post.banner_image_url ? post.banner_image_url : 'https://via.placeholder.com/800x600'" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white group-hover:text-primary-600 transition-colors duration-200" x-text="post.title"></h3>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-2" x-text="post.created_date"></p>
                        </div>
                    </a>
                </template>
            </div>
            <div x-show="!loading && posts.length === 0" class="grid place-items-center h-32">
                <p class="text-neutral-500 dark:text-neutral-400">Không có bài viết nào trong danh mục này.</p>
            </div>
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
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
            this.posts = []; // Clear previous posts
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
