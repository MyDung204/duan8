@extends('layouts.guest')

@section('title', 'Bài viết')

@section('content')

{{-- HERO BANNER - Full Width --}}
<section class="relative h-[40vh] md:h-[50vh] lg:h-[60vh] mb-20 overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/80 via-purple-900/70 to-pink-900/80 z-10"></div>
        <img src="https://images.unsplash.com/photo-1488998427799-e3362cec87c3?w=1920" 
             alt="Background" 
             class="w-full h-full object-cover scale-110 animate-zoom-slow">
    </div>
    <div class="absolute inset-0 z-20 flex items-center justify-center">
        <div class="text-center text-white px-4 max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-black mb-6 tracking-tight">
                Tất cả bài viết
            </h1>
            <p class="text-xl md:text-2xl text-white/90">
                Khám phá kho tàng kiến thức được chắt lọc và cập nhật mỗi ngày
            </p>
        </div>
    </div>
</section>

{{-- SECTION 1: Featured Posts Grid --}}
@if($posts->count() > 0)
<section class="mb-32 scroll-reveal">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <span class="inline-block px-4 py-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-sm font-bold rounded-full mb-4">Nổi bật</span>
                <h2 class="text-4xl md:text-5xl font-black text-neutral-900 dark:text-white">Bài viết đặc sắc</h2>
            </div>
            <div class="hidden md:block text-sm text-neutral-500">
                {{ $posts->total() }} bài viết
            </div>
        </div>
        
        {{-- Featured Large Card + Grid --}}
        <div class="grid md:grid-cols-3 gap-6 lg:gap-8 mb-12">
            @if($posts->first())
                <a href="{{ route('posts.show.public', $posts->first()->slug) }}" 
                   class="md:col-span-2 group relative overflow-hidden rounded-3xl bg-white dark:bg-neutral-900 shadow-2xl hover:shadow-3xl transition-all duration-500">
                    <div class="aspect-[21/9] overflow-hidden">
                        <img src="{{ $posts->first()->banner_image_url ?? 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=1200' }}" 
                             alt="{{ $posts->first()->title }}" 
                             class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                        
                        @if($posts->first()->category)
                            <span class="absolute top-6 left-6 px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-full backdrop-blur-sm">
                                {{ $posts->first()->category->title }}
                            </span>
                        @endif
                        
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                            <h3 class="text-3xl lg:text-4xl font-black mb-4 leading-tight group-hover:text-indigo-300 transition-colors">
                                {{ $posts->first()->title }}
                            </h3>
                            @if($posts->first()->short_description)
                                <p class="text-white/90 text-base mb-4 line-clamp-2">{{ $posts->first()->short_description }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm text-white/80">
                                <span>{{ $posts->first()->author_name ?? 'Admin' }}</span>
                                <span>•</span>
                                <span>{{ $posts->first()->created_date }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @endif
            
            <div class="space-y-6">
                @foreach($posts->skip(1)->take(2) as $post)
                    <a href="{{ route('posts.show.public', $post->slug) }}" 
                       class="group block relative overflow-hidden rounded-2xl bg-white dark:bg-neutral-900 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                        <div class="aspect-[4/3] overflow-hidden">
                            <img src="{{ $post->banner_image_url ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=600' }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-5 text-white">
                                <h3 class="text-lg font-black mb-2 line-clamp-2">{{ $post->title }}</h3>
                                <div class="flex items-center gap-2 text-xs text-white/70">
                                    <span>{{ $post->created_date }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- SECTION 2: All Posts Grid with Sidebar --}}
<section class="mb-32 scroll-reveal">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-8 lg:gap-12">
            {{-- Main Content --}}
            <div class="lg:col-span-3">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-1 h-10 bg-gradient-to-b from-purple-500 to-pink-500 rounded-full"></div>
                    <h2 class="text-3xl md:text-4xl font-black text-neutral-900 dark:text-white">Tất cả bài viết</h2>
                </div>
                
                @if($posts->count() > 3)
                    <div class="grid sm:grid-cols-2 gap-6 mb-12">
                        @foreach($posts->skip(3) as $post)
                            <a href="{{ route('posts.show.public', $post->slug) }}" 
                               class="group block relative overflow-hidden rounded-2xl bg-white dark:bg-neutral-900 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                                <div class="aspect-[16/10] overflow-hidden">
                                    <img src="{{ $post->banner_image_url ?? 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800' }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                                    
                                    @if($post->category)
                                        <span class="absolute top-4 left-4 px-3 py-1 bg-indigo-600/90 backdrop-blur-sm text-white text-xs font-bold rounded-full">
                                            {{ $post->category->title }}
                                        </span>
                                    @endif
                                </div>
                                <div class="p-6">
                                    <h3 class="text-xl font-black text-neutral-900 dark:text-white mb-3 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                        {{ $post->title }}
                                    </h3>
                                    @if($post->short_description)
                                        <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4 line-clamp-2">{{ $post->short_description }}</p>
                                    @endif
                                    <div class="flex items-center gap-3 text-xs text-neutral-500">
                                        <span>{{ $post->author_name ?? 'Admin' }}</span>
                                        <span>•</span>
                                        <span>{{ $post->created_date }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="flex justify-center">
                        {{ $posts->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
            
            {{-- Sidebar --}}
            <aside class="space-y-6 sticky top-24 h-fit">
                {{-- Search & Filter --}}
                <div class="bg-gradient-to-br from-white to-neutral-50 dark:from-neutral-900 dark:to-neutral-950 p-6 rounded-3xl border border-neutral-200/80 dark:border-neutral-800 shadow-xl">
                    <h3 class="text-xl font-black mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Tìm kiếm
                    </h3>
                    <form method="get" action="{{ route('posts.public') }}" class="space-y-4">
                        <input type="text" name="q" value="{{ request('q') }}" 
                               placeholder="Nhập từ khóa..." 
                               class="w-full px-4 py-3 rounded-xl bg-white dark:bg-neutral-800 border-2 border-neutral-200 dark:border-neutral-700 focus:border-indigo-500 focus:outline-none transition">
                        <select name="category" 
                                class="w-full px-4 py-3 rounded-xl bg-white dark:bg-neutral-800 border-2 border-neutral-200 dark:border-neutral-700 focus:border-indigo-500 focus:outline-none transition">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->title }}</option>
                            @endforeach
                        </select>
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl">
                            Tìm kiếm
                        </button>
                        @if(request('q') || request('category'))
                            <a href="{{ route('posts.public') }}" 
                               class="block text-center text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                Xóa bộ lọc
                            </a>
                        @endif
                    </form>
                </div>
                
                {{-- Newsletter --}}
                <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 p-6 rounded-3xl text-white shadow-xl">
                    <h3 class="text-xl font-black mb-3">Đăng ký nhận tin</h3>
                    <p class="text-sm text-white/90 mb-4">Nhận bài viết mới nhất qua email</p>
                    @livewire('frontend.newsletter-form')
                </div>
            </aside>
        </div>
    </div>
</section>

{{-- SECTION 3: Explore Categories CTA --}}
<section class="mb-32 scroll-reveal">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative overflow-hidden rounded-3xl lg:rounded-[3rem] p-12 lg:p-16">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600"></div>
            <div class="absolute inset-0">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            </div>
            <div class="relative z-10 text-center text-white">
                <h2 class="text-4xl md:text-5xl font-black mb-4">Khám phá theo danh mục</h2>
                <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                    Tìm kiếm các chủ đề bạn yêu thích và đọc thêm nhiều bài viết hấp dẫn
                </p>
                <a href="{{ route('categories.public') }}" 
                   class="inline-flex items-center gap-3 px-8 py-4 bg-white text-indigo-600 rounded-full font-black text-lg hover:bg-indigo-50 transition-all duration-300 shadow-2xl hover:scale-105">
                    Xem tất cả danh mục
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

@else
{{-- Empty State --}}
<section class="flex items-center justify-center min-h-[60vh] mb-32">
    <div class="text-center max-w-md mx-auto px-4">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 mb-6">
            <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-black text-neutral-900 dark:text-white mb-4">Không tìm thấy bài viết</h2>
        <p class="text-lg text-neutral-600 dark:text-neutral-400 mb-8">Không có bài viết nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
        <a href="{{ route('posts.public') }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full font-bold hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg">
            Xem tất cả bài viết
        </a>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
    @keyframes zoom-slow {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    .animate-zoom-slow {
        animation: zoom-slow 20s ease-in-out infinite;
    }
    
    .scroll-reveal {
        opacity: 0;
        transform: translateY(60px);
        transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1), transform 1s cubic-bezier(0.4, 0, 0.2, 1);
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

@push('scripts')
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