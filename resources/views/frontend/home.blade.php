@extends('layouts.frontend.app')

@section('title', 'Trang chủ')

@section('content')

    @if (isset($latestPosts) && $latestPosts->count() > 0)
        @php
            $featuredPosts = $latestPosts->take(5);
            $trendingPosts = $popularPosts->take(8);
            $dontMissPosts = $popularPosts->slice(0, 6);
            $recentPosts = $latestPosts->slice(5, 9);
            $mostPopular = $mostCommentedPosts->take(4);
            $whatsNewPosts = $latestPosts->slice(0, 6);
            $featuredSectionPosts = $latestPosts->slice(0, 5);
        @endphp

        {{-- ====================================================== --}}
        {{-- ================== FEATURED AREA ===================== --}}
        {{-- ====================================================== --}}
        <section class="mb-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:h-[600px]">
                    @if($featuredPosts->first())
                        <a href="{{ route('posts.show.public', $featuredPosts->first()->slug) }}" 
                           class="group relative block rounded-xl overflow-hidden h-[400px] lg:h-full shadow-lg hover:shadow-2xl transition-all duration-500">
                            <img src="{{ $featuredPosts->first()->banner_image_url ?? 'https://source.unsplash.com/random/800x1000?sig='.$featuredPosts->first()->id }}" 
                                 alt="{{ $featuredPosts->first()->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 lg:p-8">
                                @if($featuredPosts->first()->category)
                                <span class="inline-block bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-full mb-3 uppercase tracking-wide">
                                    {{ $featuredPosts->first()->category->title }}
                                </span>
                                @endif
                                <h2 class="text-2xl lg:text-3xl font-bold text-white leading-tight mb-2 line-clamp-3 group-hover:text-red-400 transition-colors duration-300">
                                    {{ $featuredPosts->first()->title }}
                                </h2>
                                <div class="flex items-center text-white/80 text-sm">
                                    <span class="mr-3">{{ $featuredPosts->first()->author_name ?? 'Admin' }}</span>
                                    <span>•</span>
                                    <span class="ml-3">{{ $featuredPosts->first()->created_date }}</span>
                                </div>
                            </div>
                        </a>
                    @endif
                    <div class="grid grid-cols-2 gap-4 h-[400px] lg:h-full">
                        @foreach($featuredPosts->slice(1, 4) as $post)
                            <a href="{{ route('posts.show.public', $post->slug) }}" 
                               class="group relative block rounded-xl overflow-hidden h-full shadow-md hover:shadow-xl transition-all duration-500">
                                <img src="{{ $post->banner_image_url ?? 'https://source.unsplash.com/random/400x300?sig='.$post->id }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    @if($post->category)
                                    <span class="inline-block bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full mb-2 uppercase">
                                        {{ $post->category->title }}
                                    </span>
                                    @endif
                                    <h3 class="text-sm lg:text-base font-bold text-white leading-tight line-clamp-2 group-hover:text-red-400 transition-colors duration-300">
                                        {{ Str::limit($post->title, 60) }}
                                    </h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ====================================================== --}}
        {{-- =================== LATEST NEWS ====================== --}}
        {{-- ====================================================== --}}
        <section class="mb-12 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-12 rounded-2xl">
            <div class="container mx-auto px-4">
                <div class="text-center mb-8">
                    <h2 class="text-3xl lg:text-4xl font-bold mb-2">Tin tức mới nhất</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">Vì bạn xứng đáng với sự thật</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($whatsNewPosts as $post)
                        <article class="group bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300">
                            <a href="{{ route('posts.show.public', $post->slug) }}" class="block relative overflow-hidden">
                                <img src="{{ $post->banner_image_url ?? 'https://source.unsplash.com/random/400x250?sig=new-'.$post->id }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute top-4 left-4">
                                    @if($post->category)
                                    <span class="inline-block bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-xs font-bold px-3 py-1 rounded-full">
                                        {{ $post->category->title }}
                                    </span>
                                    @endif
                                </div>
                            </a>
                            <div class="p-5">
                                <h3 class="text-lg font-bold mb-2 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                    <a href="{{ route('posts.show.public', $post->slug) }}">{{ $post->title }}</a>
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                    {{ $post->short_description ?? Str::limit($post->title, 100) }}
                                </p>
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>{{ $post->author_name ?? 'Admin' }}</span>
                                    <span>{{ $post->created_date }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ====================================================== --}}
        {{-- =================== TRENDING STORIES ================== --}}
        {{-- ====================================================== --}}
        <section class="mb-12">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold">Tin tức nổi bật</h2>
                    <a href="{{ route('posts.public') }}" class="text-red-600 dark:text-red-400 font-semibold hover:underline">
                        Xem tất cả →
                    </a>
                </div>
                <div class="relative" x-data="{ scrollContainer: null, scrollAmount: 300 }" x-init="scrollContainer = $refs.scrollContent">
                    <button @click="scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' })" 
                            class="absolute left-0 top-2/5 -translate-y-1/2 bg-white dark:bg-gray-800 p-2 rounded-full shadow-md z-10 focus:outline-none focus:ring-2 focus:ring-red-500 hidden md:block">
                        <span class="material-symbols-outlined">arrow_back_ios</span>
                    </button>
                    <div x-ref="scrollContent" class="flex space-x-6 overflow-x-auto pb-4 scrollbar-hide scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
                        @foreach($trendingPosts as $post)
                            <div class="flex-shrink-0 w-80">
                                <a href="{{ route('posts.show.public', $post->slug) }}" class="group block rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                    <div class="relative overflow-hidden">
                                        <img src="{{ $post->banner_image_url ?? 'https://source.unsplash.com/random/320x400?sig='.$post->id }}" 
                                             alt="{{ $post->title }}" 
                                             class="w-full h-64 object-cover transition-transform duration-700 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                        <div class="absolute top-4 left-4">
                                            @if($post->category)
                                            <span class="inline-block bg-white text-gray-900 text-xs font-bold px-3 py-1 rounded-full">
                                                {{ $post->category->title }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                <div class="pt-4">
                                    <h3 class="text-lg font-bold mb-2 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                        <a href="{{ route('posts.show.public', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ $post->author_name ?? 'Admin' }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $post->created_date }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button @click="scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' })" 
                            class="absolute right-0 top-2/5 -translate-y-1/2 bg-white dark:bg-gray-800 p-2 rounded-full shadow-md z-10 focus:outline-none focus:ring-2 focus:ring-red-500 hidden md:block">
                        <span class="material-symbols-outlined">arrow_forward_ios</span>
                    </button>
                </div>
            </div>
        </section>

        {{-- ====================================================== --}}
        {{-- =================== MAIN CONTENT AREA ================= --}}
        {{-- ====================================================== --}}
        <section class="mb-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Main Content Column --}}
                    <div class="lg:col-span-2 space-y-8">
                        {{-- Latest Posts --}}
                        <div>
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-3xl font-bold">Bài viết mới nhất</h2>
                                <a href="{{ route('posts.public') }}" class="text-red-600 dark:text-red-400 font-semibold hover:underline">
                                    Xem tất cả →
                                </a>
                            </div>
                            <div class="space-y-6">
                                @foreach($recentPosts->take(6) as $post)
                                    <article class="group grid grid-cols-1 md:grid-cols-3 gap-4 items-center bg-white dark:bg-gray-800 rounded-xl p-4 shadow-md hover:shadow-xl transition-all duration-300">
                                        <a href="{{ route('posts.show.public', $post->slug) }}" class="block rounded-lg overflow-hidden md:col-span-1">
                                            <img src="{{ $post->banner_image_url ?? 'https://source.unsplash.com/random/300x200?sig='.$post->id }}" 
                                                 alt="{{ $post->title }}" 
                                                 class="w-full h-40 object-cover transition-transform duration-500 group-hover:scale-110">
                                        </a>
                                        <div class="md:col-span-2">
                                            @if($post->category)
                                            <a href="{{ route('categories.show.public', $post->category->slug ?? $post->category->id) }}" 
                                               class="text-red-600 dark:text-red-400 font-bold text-xs uppercase tracking-wide">
                                                {{ $post->category->title }}
                                            </a>
                                            @endif
                                            <h3 class="text-xl font-bold mt-2 mb-2 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                                <a href="{{ route('posts.show.public', $post->slug) }}">{{ $post->title }}</a>
                                            </h3>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-3 line-clamp-2">
                                                {{ $post->short_description ?? Str::limit($post->title, 120) }}
                                            </p>
                                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                                <div class="flex items-center">
                                                    <span>{{ $post->author_name ?? 'Admin' }}</span>
                                                    <span class="mx-2">•</span>
                                                    <span>{{ $post->created_date }}</span>
                                                </div>
                                                @if(isset($post->comments_count))
                                                <span>{{ $post->comments_count }} bình luận</span>
                                                @endif
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>

                        {{-- Don't Miss Section --}}
                        <div>
                            <h2 class="text-3xl font-bold mb-6">Đừng bỏ lỡ</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($dontMissPosts as $post)
                                    <article class="group bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                                        <a href="{{ route('posts.show.public', $post->slug) }}" class="block relative overflow-hidden">
                                            <img src="{{ $post->banner_image_url ?? 'https://source.unsplash.com/random/400x250?sig=miss-'.$post->id }}" 
                                                 alt="{{ $post->title }}" 
                                                 class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110">
                                        </a>
                                        <div class="p-5">
                                            @if($post->category)
                                            <a href="{{ route('categories.show.public', $post->category->slug ?? $post->category->id) }}" 
                                               class="text-red-600 dark:text-red-400 font-bold text-xs uppercase tracking-wide">
                                                {{ $post->category->title }}
                                            </a>
                                            @endif
                                            <h3 class="text-lg font-bold mt-2 mb-2 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                                <a href="{{ route('posts.show.public', $post->slug) }}">{{ $post->title }}</a>
                                            </h3>
                                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <span>{{ $post->author_name ?? 'Admin' }}</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $post->created_date }}</span>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <aside class="space-y-8">
                        {{-- Most Popular --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md top-0 py-4">
                            <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-gray-200 dark:border-gray-700">Đọc nhiều nhất</h2>
                            <div class="space-y-5">
                                @foreach($mostPopular as $post)
                                    <a href="{{ route('posts.show.public', $post->slug) }}" 
                                       class="group flex items-start gap-4 pb-5 border-b border-gray-200 dark:border-gray-700 last:border-0 last:pb-0">
                                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-600 to-red-700 rounded-lg flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold mb-1 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
                                                {{ $post->title }}
                                            </h3>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $post->comments_count ?? 0 }} bình luận
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Categories --}}
                        @if(isset($topCategories) && $topCategories->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md sticky top-[50px] py-4">
                            <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-gray-200 dark:border-gray-700">Danh mục</h2>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($topCategories->take(6) as $category)
                                    <a href="{{ route('categories.show.public', $category->slug ?? $category->id) }}" 
                                       class="group relative block rounded-lg overflow-hidden aspect-square shadow-md hover:shadow-xl transition-all duration-300">
                                        <img src="{{ $category->banner_image_url ?? 'https://source.unsplash.com/random/400x300?sig=cat-'.$category->id }}" 
                                             alt="{{ $category->title }}" 
                                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-br from-black/60 to-transparent opacity-100 group-hover:opacity-100 transition-opacity duration-300 z-10"></div>
                                        <div class="absolute inset-0 flex flex-col justify-center items-center z-20 p-2">
                                            <span class="text-white font-bold text-center px-2 text-lg group-hover:scale-110 transition-transform duration-300">
                                                {{ $category->title }}
                                            </span>
                                            <span class="text-white text-xs font-semibold text-center mt-1">
                                                {{ $category->posts_count ?? 0 }} bài viết
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif



                        {{-- Tags --}}
                        @if(isset($topTags) && $topTags->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-md">
                            <h2 class="text-2xl font-bold mb-6 pb-3 border-b-2 border-gray-200 dark:border-gray-700">Thẻ</h2>
                            <div class="flex flex-wrap gap-2">
                                @foreach($topTags as $tag)
                                <a href="#" 
                                   class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs font-semibold px-4 py-2 rounded-full hover:bg-red-600 hover:text-white dark:hover:bg-red-600 transition-all duration-300">
                                    {{ $tag->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </aside>
                </div>
            </div>
        </section>

    @else
        <section class="flex items-center justify-center h-[80vh]">
            <div class="text-center">
                <p class="text-xl text-gray-500 dark:text-gray-400">Chào mừng! Hiện chưa có bài viết nào.</p>
            </div>
        </section>
    @endif

    {{-- Custom Styles --}}
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
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
        
        .newsletter-in-red form {
            max-width: 100%;
        }
        
        .newsletter-in-red .bg-white\/60,
        .newsletter-in-red .dark\:bg-white\/10 {
            background-color: rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(8px);
        }
        
        .newsletter-in-red input {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .newsletter-in-red input::placeholder {
            color: rgba(255, 255, 255, 0.7) !important;
        }
        
        .newsletter-in-red button {
            background-color: white !important;
            color: #dc2626 !important;
        }
        
        .newsletter-in-red button:hover {
            background-color: rgba(255, 255, 255, 0.9) !important;
        }
        
        .newsletter-in-red .text-red-500,
        .newsletter-in-red .text-red-700 {
            color: #fee2e2 !important;
        }
        
        .newsletter-in-red .bg-red-100,
        .newsletter-in-red .bg-red-200 {
            background-color: rgba(255, 255, 255, 0.2) !important;
            color: white !important;
        }
    </style>

@endsection
