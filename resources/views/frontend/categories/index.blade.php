@extends('frontend.layouts.app')

@section('title', 'Danh mục')

@section('banner')
<section class="relative py-16 md:py-24 bg-gradient-to-br from-teal-600 via-cyan-600 to-sky-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white tracking-tight mb-4">Khám phá theo chủ đề</h1>
            <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto">Tìm kiếm nội dung theo các danh mục được phân loại rõ ràng</p>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- All Categories Section -->
<section class="mb-16 scroll-reveal">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h2 class="text-3xl md:text-4xl font-bold mb-2">Tất cả danh mục</h2>
            <p class="text-neutral-600 dark:text-neutral-400">Khám phá toàn bộ các chủ đề có sẵn</p>
        </div>
        <div class="hidden md:block text-sm text-neutral-500">
            Tổng cộng <span class="font-semibold text-neutral-900 dark:text-white">{{ $categories->count() }}</span> danh mục
        </div>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="categoryList">
        @forelse($categories as $category)
            <a href="{{ route('categories.show.public', $category->slug ?? $category->id) }}" class="group relative block aspect-[4/3] rounded-xl overflow-hidden text-white shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <div class="absolute inset-0">
                    <img src="{{ $category->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $category->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-black/20 group-hover:from-black/80 group-hover:via-black/50 transition-colors duration-300"></div>
                <div class="relative h-full flex flex-col justify-between p-5">
                    <div>
                        <span class="inline-block px-2.5 py-1 text-xs font-semibold bg-white/20 backdrop-blur-sm rounded-full mb-2">
                            {{ $category->posts()->published()->count() }} bài viết
                        </span>
                        <h3 class="text-xl font-bold mb-1 line-clamp-2">{{ $category->title }}</h3>
                    </div>
                    <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center gap-2 text-sm font-medium">
                        Khám phá
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-20 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-100 dark:bg-neutral-800 mb-4">
                    <svg class="w-8 h-8 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white mb-2">Chưa có danh mục</h3>
                <p class="text-sm text-neutral-500 mb-6">Hiện tại chưa có danh mục nào được tạo.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Why Browse By Categories Section -->
<section class="mb-16 py-12 bg-white dark:bg-neutral-900 rounded-3xl border border-neutral-200/80 dark:border-neutral-800 scroll-reveal">
    <div class="text-center mb-10">
        <h2 class="text-3xl md:text-4xl font-bold mb-2">Vì sao duyệt theo danh mục?</h2>
        <p class="text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">Nhanh chóng tìm đúng nội dung bạn quan tâm với cấu trúc chủ đề rõ ràng.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="p-6 rounded-2xl bg-neutral-50 dark:bg-neutral-800 border border-neutral-200/80 dark:border-neutral-700 hover:shadow-lg transition-all">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-teal-600 text-white mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Tìm kiếm nhanh</h3>
            <p class="text-neutral-600 dark:text-neutral-400">Lọc nội dung theo chủ đề thay vì cuộn tìm thủ công.</p>
        </div>
        <div class="p-6 rounded-2xl bg-neutral-50 dark:bg-neutral-800 border border-neutral-200/80 dark:border-neutral-700 hover:shadow-lg transition-all">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-cyan-600 text-white mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2-1.343-2-3-2zm0 7c-4.418 0-8 1.79-8 4v1h16v-1c0-2.21-3.582-4-8-4z"/></svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Trải nghiệm rõ ràng</h3>
            <p class="text-neutral-600 dark:text-neutral-400">Cấu trúc theo cha-con giúp định hướng và khám phá nội dung dễ dàng.</p>
        </div>
        <div class="p-6 rounded-2xl bg-neutral-50 dark:bg-neutral-800 border border-neutral-200/80 dark:border-neutral-700 hover:shadow-lg transition-all">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-sky-600 text-white mb-4">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h10a4 4 0 004-4V9a4 4 0 00-4-4H7a4 4 0 00-4 4v6z"/></svg>
            </div>
            <h3 class="text-xl font-bold mb-2">Hiệu năng tốt</h3>
            <p class="text-neutral-600 dark:text-neutral-400">Danh mục và số liệu đã được tối ưu truy vấn và cache cho tốc độ tải nhanh.</p>
        </div>
    </div>
</section>

<!-- Call to Action / Explore Posts Section -->
<section class="mt-16 py-16 bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600 rounded-3xl overflow-hidden relative">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Khám phá các bài viết</h2>
        <p class="text-lg text-white/90 max-w-2xl mx-auto mb-8">Tìm kiếm các bài viết theo chủ đề bạn quan tâm và mở rộng kiến thức của mình.</p>
        <a href="{{ route('posts.public') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-white text-purple-600 font-semibold hover:bg-neutral-100 transition shadow-lg hover:shadow-xl hover:scale-105 transform duration-300">
            Xem tất cả bài viết
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
</section>

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