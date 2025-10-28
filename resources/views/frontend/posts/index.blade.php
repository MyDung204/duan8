@extends('frontend.layouts.app')

@section('title', 'Bài viết')

@section('banner')
<section class="relative py-16 md:py-24 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight">Tất cả bài viết</h1>
            <p class="mt-4 text-lg text-white/80 max-w-2xl mx-auto">Khám phá kho tàng kiến thức được chắt lọc và cập nhật mỗi ngày.</p>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Featured Posts Section -->
<section class="mb-12">
    <h2 class="text-2xl font-bold mb-6">Bài viết nổi bật</h2>
    <div class="grid md:grid-cols-3 gap-8">
        @foreach($posts->take(3) as $post)
            <a href="#" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                <div class="aspect-[16/9] overflow-hidden">
                    <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-5">
                    <p class="text-sm text-indigo-500 font-medium">{{ optional($post->category)->title }}</p>
                    <h3 class="mt-2 text-lg font-semibold text-neutral-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $post->title }}</h3>
                    <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2">{{ $post->short_description }}</p>
                    <div class="mt-4 text-xs text-neutral-500 dark:text-neutral-500 flex items-center gap-2">
                        <span>{{ $post->author_name }}</span>
                        <span>&bull;</span>
                        <span>{{ $post->created_date }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>

<div class="grid lg:grid-cols-3 gap-12 items-start">
    <!-- Main Posts Area -->
    <div class="lg:col-span-2">
        <h2 class="text-2xl font-bold mb-6">Tất cả bài viết</h2>
        @if($posts->count())
            <div class="grid sm:grid-cols-2 gap-8">
                @foreach($posts->skip(3) as $post)
                    <a href="#" class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="aspect-[16/9] overflow-hidden">
                            <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="p-5">
                            <p class="text-sm text-indigo-500 font-medium">{{ optional($post->category)->title }}</p>
                            <h3 class="mt-2 text-lg font-semibold text-neutral-900 dark:text-white group-hover:text-indigo-600 transition-colors">{{ $post->title }}</h3>
                            <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2">{{ $post->short_description }}</p>
                            <div class="mt-4 text-xs text-neutral-500 dark:text-neutral-500 flex items-center gap-2">
                                <span>{{ $post->author_name }}</span>
                                <span>&bull;</span>
                                <span>{{ $post->created_date }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-10">
                {{ $posts->links('vendor.pagination.tailwind') }}
            </div>
        @else
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-white">Không có bài viết</h3>
                <p class="mt-1 text-sm text-neutral-500">Không tìm thấy bài viết nào phù hợp với tiêu chí của bạn.</p>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <aside class="space-y-8 sticky top-24">
        <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
            <h3 class="text-lg font-semibold mb-4">Tìm kiếm & Lọc</h3>
            <form method="get" class="space-y-4">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm..." class="w-full px-4 py-2.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                <div>
                    <label for="category" class="sr-only">Danh mục</label>
                    <select id="category" name="category" class="w-full px-4 py-2.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->title }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="w-full px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">Lọc bài viết</button>
            </form>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-4">Bài viết phổ biến</h3>
            <ul class="space-y-4">
                @foreach($posts->take(4) as $post)
                    <li>
                        <a href="#" class="group flex items-center gap-4">
                            <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden">
                                <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/400x400' }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-semibold text-sm group-hover:text-indigo-600 transition-colors">{{ $post->title }}</h4>
                                <p class="text-xs text-neutral-500 mt-1">{{ $post->created_date }}</p>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 text-center">
            <h3 class="text-lg font-semibold mb-4">Đăng ký nhận tin</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">Nhận các bài viết mới nhất trực tiếp vào hộp thư của bạn.</p>
            <form class="space-y-3">
                <input type="email" placeholder="Email của bạn" class="w-full px-4 py-2.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                <button class="w-full px-4 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">Đăng ký</button>
            </form>
        </div>
    </aside>
</div>

<!-- Explore Categories Section -->
<section class="mt-12 py-12 bg-gradient-to-r from-teal-600 to-cyan-600 rounded-2xl">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="text-3xl font-bold">Khám phá thêm danh mục</h2>
        <p class="mt-2 max-w-2xl mx-auto">Tìm kiếm các chủ đề bạn yêu thích và đọc thêm nhiều bài viết hấp dẫn.</p>
        <a href="{{ route('categories.public') }}" class="mt-6 inline-flex items-center px-6 py-3 rounded-lg bg-white text-teal-600 font-semibold hover:bg-neutral-200 transition">
            Xem tất cả danh mục
            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
        </a>
    </div>
</section>

@endsection