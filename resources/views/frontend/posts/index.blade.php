@extends('layouts.frontend.app')

@section('title', 'Bài viết')

@section('banner')
<section class="relative py-16 md:py-24 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/30"></div>
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 right-0 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white tracking-tight mb-4">Tất cả bài viết</h1>
            <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto">Khám phá kho tàng kiến thức được chắt lọc và cập nhật mỗi ngày</p>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Featured Posts Section -->
@if($posts->count() > 0)
<section class="mb-16 scroll-reveal">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl md:text-4xl font-bold">Bài viết nổi bật</h2>
        <div class="hidden md:flex items-center gap-2 text-sm text-neutral-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span>Được chọn lọc cẩn thận</span>
        </div>
    </div>
    <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
        @foreach($posts->take(3) as $index => $post)
            <a href="{{ route('posts.show.public', $post->slug) }}" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300">
                <div class="aspect-[16/9] overflow-hidden relative">
                    <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    @if($post->category)
                        <span class="absolute top-4 left-4 px-3 py-1 text-xs font-semibold bg-indigo-600 text-white rounded-full">{{ $post->category->title }}</span>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-neutral-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">{{ $post->title }}</h3>
                    @if($post->short_description)
                        <p class="mt-3 text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2">{{ $post->short_description }}</p>
                    @endif
                    <div class="mt-4 flex items-center gap-3 text-xs text-neutral-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $post->author_name ?? 'Admin' }}
                        </span>
                        <span>&bull;</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $post->created_date }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<div class="grid lg:grid-cols-3 gap-12 items-start">
    <!-- Main Posts Area -->
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold">Tất cả bài viết</h2>
            <div class="text-sm text-neutral-500">
                Tìm thấy <span class="font-semibold text-neutral-900 dark:text-white">{{ $posts->total() }}</span> bài viết
            </div>
        </div>
        @if($posts->count())
            <div class="grid sm:grid-cols-2 gap-6">
                @foreach($posts->skip(3) as $post)
                    <a href="{{ route('posts.show.public', $post->slug) }}" class="group block bg-white dark:bg-neutral-900 rounded-xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="aspect-[16/9] overflow-hidden relative">
                            <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-5">
                            @if($post->category)
                                <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 mb-2 uppercase tracking-wide">{{ $post->category->title }}</p>
                            @endif
                            <h3 class="text-lg font-bold text-neutral-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 mb-2">{{ $post->title }}</h3>
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
            <div class="mt-12">
                {{ $posts->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
        @else
            <div class="text-center py-20 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-4">
                    <svg class="w-8 h-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">Không tìm thấy bài viết</h3>
                <p class="text-sm text-neutral-500 mb-6">Không có bài viết nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
                <a href="{{ route('posts.public') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                    Xem tất cả bài viết
                </a>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <aside class="space-y-6 sticky top-24">
        <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 shadow-sm">
            <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Tìm kiếm & Lọc
            </h3>
            <form method="get" action="{{ route('posts.public') }}" class="space-y-4">
                <div>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Nhập từ khóa..." class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" />
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-2">Danh mục</label>
                    <select id="category" name="category" class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->title }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full px-4 py-3 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition shadow-sm hover:shadow-md">
                    Áp dụng bộ lọc
                </button>
                @if(request('q') || request('category'))
                    <a href="{{ route('posts.public') }}" class="block w-full px-4 py-2 text-center text-sm text-neutral-600 dark:text-neutral-400 hover:text-indigo-600 transition">
                        Xóa bộ lọc
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 shadow-sm">
            <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Bài viết phổ biến
            </h3>
            <ul class="space-y-4">
                @foreach($posts->take(5) as $post)
                    <li>
                        <a href="{{ route('posts.show.public', $post->slug) }}" class="group flex items-start gap-3 hover:bg-neutral-50 dark:hover:bg-neutral-800 rounded-lg p-2 -m-2 transition">
                            <div class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                                <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/400x400' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-sm text-neutral-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">{{ $post->title }}</h4>
                                <p class="text-xs text-neutral-500 mt-1">{{ $post->created_date }}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

    </aside>
</div>

<!-- Explore Categories Section -->
<section class="mt-16 py-16 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-3xl overflow-hidden relative">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Khám phá thêm danh mục</h2>
        <p class="text-lg text-white/90 max-w-2xl mx-auto mb-8">Tìm kiếm các chủ đề bạn yêu thích và đọc thêm nhiều bài viết hấp dẫn.</p>
        <a href="{{ route('categories.public') }}" class="inline-flex items-center px-8 py-4 rounded-xl bg-white text-indigo-600 font-semibold hover:bg-neutral-100 transition shadow-lg hover:shadow-xl hover:scale-105 transform duration-300">
            Xem tất cả danh mục
            <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
</section>

@endsection

@push('scripts')
<style>
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }
        33% {
            transform: translate(30px, -50px) scale(1.1);
        }
        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
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
<script>
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
@endpush