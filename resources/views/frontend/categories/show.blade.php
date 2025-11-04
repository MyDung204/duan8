@extends('layouts.frontend.app')

@section('title', $category->title)

@section('banner')
<section class="relative py-20 md:py-32 overflow-hidden">
    @if($category->banner_image_url)
        <div class="absolute inset-0">
            <img src="{{ $category->banner_image_url }}" alt="{{ $category->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-black/30"></div>
        </div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-teal-600 via-cyan-600 to-sky-600">
            <div class="absolute inset-0 bg-black/30"></div>
            <div class="absolute inset-0">
                <div class="absolute top-0 right-0 w-96 h-96 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            </div>
        </div>
    @endif
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Breadcrumbs -->
            <nav class="mb-8 flex items-center gap-2 text-sm text-white/80">
                <a href="{{ route('home') }}" class="hover:text-white transition">Trang chủ</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('categories.public') }}" class="hover:text-white transition">Danh mục</a>
                @foreach($breadcrumbs as $breadcrumb)
                    @if($breadcrumb->id !== $category->id)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <a href="{{ route('categories.show.public', $breadcrumb->slug ?? $breadcrumb->id) }}" class="hover:text-white transition">{{ $breadcrumb->title }}</a>
                    @endif
                @endforeach
            </nav>
            
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white tracking-tight mb-6 drop-shadow-lg">{{ $category->title }}</h1>
                @if($category->short_description)
                    <p class="text-lg md:text-xl lg:text-2xl text-white/90 max-w-3xl mx-auto mb-8 drop-shadow">{{ $category->short_description }}</p>
                @endif
                
                <!-- Stats -->
                <div class="flex flex-wrap items-center justify-center gap-6 md:gap-8 text-white/90">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="text-lg font-semibold">{{ number_format($totalPosts) }}</span>
                        <span class="text-sm">bài viết</span>
                    </div>
                    @if($totalViews > 0)
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-lg font-semibold">{{ number_format($totalViews) }}</span>
                        <span class="text-sm">lượt xem</span>
                    </div>
                    @endif
                    @if($category->author_name)
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm">{{ $category->author_name }}</span>
                    </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">{{ $category->created_date }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Back Button -->
<div class="mb-8">
    <a href="{{ route('categories.public') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white dark:bg-neutral-900 border-2 border-neutral-200 dark:border-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 hover:border-teal-500 dark:hover:border-teal-500 hover:text-teal-600 dark:hover:text-teal-400 transition-all duration-200 group shadow-sm hover:shadow-md">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        <span class="font-medium">Quay lại danh mục</span>
    </a>
</div>

<!-- Category Full Description -->
@if($category->content)
<section class="mb-12 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 p-8 md:p-10 shadow-sm">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-1 h-8 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
        <h2 class="text-3xl font-bold text-neutral-900 dark:text-white">Giới thiệu về danh mục</h2>
    </div>
    <div class="prose prose-lg dark:prose-invert max-w-none text-neutral-700 dark:text-neutral-300 leading-relaxed">
        {!! $category->content !!}
    </div>
</section>
@endif

<!-- Subcategories -->
@if($category->children->count() > 0)
<section class="mb-12">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-1 h-8 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
        <h2 class="text-3xl font-bold text-neutral-900 dark:text-white">Danh mục con</h2>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($category->children as $childCategory)
            <a href="{{ route('categories.show.public', $childCategory->slug ?? $childCategory->id) }}" class="group block bg-white dark:bg-neutral-900 rounded-xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-[16/9] overflow-hidden">
                    <img src="{{ $childCategory->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $childCategory->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-neutral-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors mb-2">{{ $childCategory->title }}</h3>
                    @if($childCategory->short_description)
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2 mb-2">{{ $childCategory->short_description }}</p>
                    @endif
                    <p class="text-xs text-neutral-500">{{ $childCategory->published_posts_count ?? 0 }} bài viết</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- Featured Posts -->
@if($featuredPosts->count() > 0)
<section class="mb-12">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-1 h-8 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
        <h2 class="text-3xl font-bold text-neutral-900 dark:text-white">Bài viết nổi bật</h2>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        @foreach($featuredPosts as $post)
            <a href="{{ route('posts.show.public', $post->slug) }}" class="group block bg-white dark:bg-neutral-900 rounded-xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-[16/9] overflow-hidden relative">
                    <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 text-xs font-semibold text-white bg-teal-600 rounded-full">Nổi bật</span>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="text-lg font-bold text-neutral-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-2 mb-2">{{ $post->title }}</h3>
                    @if($post->short_description)
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-3 mb-3">{{ $post->short_description }}</p>
                    @endif
                    <div class="flex items-center gap-3 text-xs text-neutral-500">
                        <span>{{ $post->author_name ?? 'Admin' }}</span>
                        <span>&bull;</span>
                        <span>{{ $post->created_date }}</span>
                        @if($post->views_count)
                            <span>&bull;</span>
                            <span>{{ number_format($post->views_count) }} lượt xem</span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- All Posts Grid -->
<section class="mb-12">
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <div class="w-1 h-8 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
            <div>
                <h2 class="text-3xl md:text-4xl font-bold mb-2">Tất cả bài viết</h2>
                <p class="text-neutral-600 dark:text-neutral-400">Hiển thị {{ $posts->count() }} / {{ $posts->total() }} bài viết</p>
            </div>
        </div>
    </div>
    
    @if($posts->count() > 0)
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($posts as $post)
                <a href="{{ route('posts.show.public', $post->slug) }}" class="group block bg-white dark:bg-neutral-900 rounded-xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="aspect-[16/9] overflow-hidden relative">
                        <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        @if($post->category)
                            <div class="absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="px-3 py-1 text-xs font-semibold text-white bg-teal-600/90 rounded-full backdrop-blur-sm">{{ $post->category->title }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-neutral-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors line-clamp-2 mb-2">{{ $post->title }}</h3>
                        @if($post->short_description)
                            <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2 mb-3">{{ $post->short_description }}</p>
                        @endif
                        <div class="flex items-center gap-3 text-xs text-neutral-500">
                            <span>{{ $post->author_name ?? 'Admin' }}</span>
                            <span>&bull;</span>
                            <span>{{ $post->created_date }}</span>
                            @if($post->views_count)
                                <span>&bull;</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    {{ number_format($post->views_count) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-12">
            {{ $posts->links('vendor.pagination.tailwind') }}
        </div>
    @else
        <div class="text-center py-20 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-4">
                <svg class="w-8 h-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">Chưa có bài viết</h3>
            <p class="text-sm text-neutral-500 mb-6">Danh mục này chưa có bài viết nào.</p>
            <a href="{{ route('posts.public') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition">
                Xem tất cả bài viết
            </a>
        </div>
    @endif
</section>

<!-- Related Categories -->
@if($relatedCategories->count() > 0)
<section class="mb-12">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-1 h-8 bg-gradient-to-b from-teal-500 to-cyan-500 rounded-full"></div>
        <h2 class="text-2xl md:text-3xl font-bold text-neutral-900 dark:text-white">Danh mục liên quan</h2>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($relatedCategories as $relatedCategory)
            <a href="{{ route('categories.show.public', $relatedCategory->slug ?? $relatedCategory->id) }}" class="group block bg-white dark:bg-neutral-900 rounded-xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-[16/9] overflow-hidden">
                    <img src="{{ $relatedCategory->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $relatedCategory->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-neutral-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors mb-2">{{ $relatedCategory->title }}</h3>
                    @if($relatedCategory->short_description)
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2 mb-2">{{ $relatedCategory->short_description }}</p>
                    @endif
                    <p class="text-xs text-neutral-500">{{ $relatedCategory->published_posts_count ?? 0 }} bài viết</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

@endsection

@push('scripts')
<style>
    @keyframes blob {
        0%, 100% {
            transform: translate(0px, 0px) scale(1);
        }
        33% {
            transform: translate(30px, -50px) scale(1.1);
        }
        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .prose {
        color: inherit;
    }
    .prose p {
        margin-top: 1em;
        margin-bottom: 1em;
    }
    .prose h1, .prose h2, .prose h3 {
        font-weight: 700;
        margin-top: 1.5em;
        margin-bottom: 0.5em;
    }
</style>
@endpush
