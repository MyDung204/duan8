@extends('layouts.guest')

@section('title', 'Trang chủ')

@section('content')
@if (isset($latestPosts) && $latestPosts->count() > 0)
    @php
        $featuredPosts = $latestPosts->take(3);
        $otherPosts = $latestPosts->skip(3)->take(6);
    @endphp

    {{-- HERO SECTION - Modern Animated Gradient with Featured Posts --}}
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden mb-20" x-data="{ activePost: 0 }">
        {{-- Animated Gradient Background --}}
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 via-pink-600 to-orange-500 animate-gradient-shift"></div>
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
            </div>
        </div>

        {{-- Floating Orbs --}}
        <div class="absolute inset-0 z-10 overflow-hidden">
            <div class="absolute top-20 left-10 w-32 h-32 bg-white/10 rounded-full blur-2xl animate-float"></div>
            <div class="absolute top-40 right-20 w-40 h-40 bg-white/10 rounded-full blur-3xl animate-float animation-delay-1000"></div>
            <div class="absolute bottom-20 left-1/4 w-36 h-36 bg-white/10 rounded-full blur-2xl animate-float animation-delay-2000"></div>
        </div>

        <div class="relative z-20 container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl py-12 lg:py-16">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                {{-- Left: Main Content --}}
                <div class="text-white space-y-6">
                    <div class="space-y-4 animate-fade-in-up">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur-md rounded-full text-xs font-bold border border-white/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Khám phá kiến thức
                        </span>
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight tracking-tight">
                            Chào mừng đến với
                            <span class="block bg-gradient-to-r from-yellow-300 via-pink-300 to-purple-300 bg-clip-text text-transparent mt-2">
                                Thế giới tri thức
                            </span>
                        </h1>
                        
                        <p class="text-lg md:text-xl text-white/90 leading-relaxed max-w-xl">
                            Khám phá hàng ngàn bài viết chất lượng, kiến thức chuyên sâu và những câu chuyện đầy cảm hứng được chia sẻ bởi cộng đồng.
                        </p>
                        
                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <a href="{{ route('posts.public') }}" 
                               class="group inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-900 rounded-full font-bold text-base hover:bg-indigo-50 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105">
                                Khám phá ngay
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                            <a href="{{ route('categories.public') }}" 
                               class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/30 text-white rounded-full font-semibold text-base hover:bg-white/20 transition-all duration-300">
                                Xem danh mục
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right: Featured Posts Cards - Stacked with Rotation --}}
                <div class="relative h-[450px] lg:h-[500px] max-w-2xl mx-auto lg:mx-0">
                    @foreach($featuredPosts as $index => $post)
                        <a href="{{ route('posts.show.public', $post->slug) }}" 
                           @mouseenter="activePost = {{ $index }}"
                           class="absolute inset-0 transition-all duration-700 ease-out transform-gpu"
                           :class="{
                               'z-30 scale-100 opacity-100 translate-y-0': activePost === {{ $index }},
                               'z-20 scale-95 opacity-75 translate-y-4': activePost !== {{ $index }} && {{ $index }} === 0,
                               'z-10 scale-90 opacity-60 translate-y-8': activePost !== {{ $index }} && {{ $index }} === 1,
                               'z-0 scale-85 opacity-40 translate-y-12': activePost !== {{ $index }} && {{ $index }} === 2
                           }">
                            <div class="relative h-full rounded-[2.5rem] overflow-hidden group" 
                                 style="box-shadow: 0 20px 60px -15px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);">
                                <div class="absolute inset-0">
                                    <img src="{{ $post->banner_image_url ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800' }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/45 to-black/25"></div>
                                </div>
                                
                                <div class="absolute inset-0 p-6 lg:p-8 flex flex-col justify-between">
                                    <div>
                                        @if($post->category)
                                            <span class="inline-block px-3 py-1.5 bg-white/20 backdrop-blur-md text-white text-xs font-bold rounded-full border border-white/30">
                                                {{ $post->category->title }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <h3 class="text-2xl lg:text-3xl font-black text-white leading-tight line-clamp-2 group-hover:text-yellow-300 transition-colors">
                                            {{ $post->title }}
                                        </h3>
                                        
                                        @if($post->short_description)
                                            <p class="text-white/90 text-sm line-clamp-2">
                                                {{ $post->short_description }}
                                            </p>
                                        @endif
                                        
                                        <div class="flex items-center gap-3 text-xs text-white/70">
                                            <span class="flex items-center gap-1.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $post->author_name ?? 'Admin' }}
                                            </span>
                                            <span>•</span>
                                            <span>{{ $post->created_date }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                    
                    {{-- Navigation Dots --}}
                    <div class="absolute -bottom-12 left-1/2 -translate-x-1/2 z-40 flex gap-2">
                        @foreach($featuredPosts as $index => $post)
                            <button @click="activePost = {{ $index }}"
                                    class="w-2 h-2 rounded-full transition-all duration-300"
                                    :class="activePost === {{ $index }} ? 'bg-white w-6 scale-125' : 'bg-white/50 hover:bg-white/70'">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </section>

    {{-- SECTION 1: Latest Posts - Modern Grid --}}
    @if($otherPosts->count() > 0)
    <section class="mb-32 scroll-reveal">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <span class="inline-block px-4 py-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-sm font-bold rounded-full mb-4">Mới nhất</span>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-neutral-900 dark:text-white">Bài viết mới</h2>
                </div>
                <a href="{{ route('posts.public') }}" 
                   class="hidden md:flex items-center gap-2 group px-6 py-3 bg-neutral-100 dark:bg-neutral-800 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-all duration-300">
                    <span class="font-semibold">Xem tất cả</span>
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($otherPosts as $index => $post)
                    <a href="{{ route('posts.show.public', $post->slug) }}" 
                       class="group relative overflow-hidden rounded-3xl bg-white dark:bg-neutral-900 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 scroll-reveal-item"
                       style="transition-delay: {{ $index * 100 }}ms">
                        <div class="aspect-[16/10] overflow-hidden relative">
                            <img src="{{ $post->banner_image_url ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800' }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                            
                            @if($post->category)
                                <span class="absolute top-6 left-6 px-4 py-1.5 bg-indigo-600 text-white text-xs font-bold rounded-full backdrop-blur-sm">
                                    {{ $post->category->title }}
                                </span>
                            @endif
                            
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <h3 class="text-xl lg:text-2xl font-black mb-3 line-clamp-2 group-hover:text-indigo-300 transition-colors">
                                    {{ $post->title }}
                                </h3>
                                <div class="flex items-center gap-3 text-xs text-white/80">
                                    <span>{{ $post->author_name ?? 'Admin' }}</span>
                                    <span>•</span>
                                    <span>{{ $post->created_date }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 2: Popular Posts - Masonry Style --}}
    @if (isset($popularPosts) && $popularPosts->count() > 0)
    <section class="mb-32 scroll-reveal bg-gradient-to-br from-neutral-50 via-indigo-50/30 to-purple-50/30 dark:from-neutral-950 dark:via-indigo-950/20 dark:to-purple-950/20 py-20">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-sm font-bold rounded-full mb-4">Trending</span>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-neutral-900 dark:text-white mb-4">
                    Đang thịnh hành
                </h2>
                <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    Những bài viết được yêu thích nhất trong tuần
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($popularPosts as $index => $post)
                    <a href="{{ route('posts.show.public', $post->slug) }}" 
                       class="group relative overflow-hidden rounded-3xl bg-white dark:bg-neutral-900 shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 {{ $index === 0 ? 'md:col-span-2 md:row-span-2' : '' }}">
                        <div class="aspect-[4/3] {{ $index === 0 ? 'md:aspect-auto md:h-full' : '' }} overflow-hidden relative">
                            <img src="{{ $post->banner_image_url ?? 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800' }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            
                            <div class="absolute top-4 left-4">
                                <div class="flex items-center gap-2 px-3 py-1.5 bg-pink-500/90 backdrop-blur-md rounded-full text-white text-xs font-bold">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.834a1 1 0 001.8.6l2.7-3.6-2.7-3.6a1 1 0 00-1.8.6zM12 10.333v5.834a1 1 0 001.8.6l2.7-3.6-2.7-3.6a1 1 0 00-1.8.6zM16.5 12a1.5 1.5 0 011.5 1.5v3a1.5 1.5 0 01-3 0v-3a1.5 1.5 0 011.5-1.5z"></path>
                                    </svg>
                                    <span>{{ number_format($post->views_count ?? 0) }} views</span>
                                </div>
                            </div>
                            
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                @if($post->category)
                                    <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold mb-3">
                                        {{ $post->category->title }}
                                    </span>
                                @endif
                                <h3 class="text-xl {{ $index === 0 ? 'lg:text-3xl' : '' }} font-black mb-2 line-clamp-2 group-hover:text-pink-300 transition-colors">
                                    {{ $post->title }}
                                </h3>
                                @if($post->short_description && $index === 0)
                                    <p class="text-white/90 text-sm mb-3 line-clamp-2">{{ $post->short_description }}</p>
                                @endif
                                <div class="flex items-center gap-2 text-xs text-white/70">
                                    <span>{{ $post->created_date }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 3: Categories - Interactive Grid --}}
    @if ($topCategories->count() > 0)
    <section class="mb-32 scroll-reveal">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-sm font-bold rounded-full mb-4">Khám phá</span>
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-neutral-900 dark:text-white mb-4">
                    Chủ đề nổi bật
                </h2>
                <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">
                    Tìm đọc các bài viết theo những chủ đề mà bạn quan tâm
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
                @foreach($topCategories->take(8) as $index => $category)
                    <a href="{{ route('categories.show.public', $category->slug) }}" 
                       class="group relative aspect-square overflow-hidden rounded-2xl lg:rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2">
                        <img src="{{ $category->banner_image_url ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=600&sig=' . $index }}" 
                             alt="{{ $category->title }}" 
                             class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                        
                        <div class="absolute bottom-0 left-0 right-0 p-4 lg:p-6 text-white">
                            <div class="mb-2">
                                <span class="inline-block px-2 py-1 bg-white/20 backdrop-blur-md rounded-lg text-xs font-bold mb-2">
                                    {{ $category->posts_count ?? 0 }} bài
                                </span>
                            </div>
                            <h3 class="text-base lg:text-xl font-black leading-tight group-hover:text-cyan-300 transition-colors">
                                {{ $category->title }}
                            </h3>
                        </div>
                        
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 to-blue-500/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </a>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('categories.public') }}" 
                   class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-full font-bold text-lg hover:from-cyan-600 hover:to-blue-600 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105">
                    Xem tất cả danh mục
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 4: Newsletter - Glassmorphism Design --}}
    <section class="mb-32 scroll-reveal">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl lg:rounded-[3rem] p-12 lg:p-20">
                {{-- Animated Background --}}
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600"></div>
                <div class="absolute inset-0">
                    <div class="absolute top-0 left-0 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
                    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                </div>
                
                {{-- Content --}}
                <div class="relative z-10 max-w-3xl mx-auto text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white/20 backdrop-blur-md mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6">
                        Tham gia cùng chúng tôi
                    </h2>
                    <p class="text-xl text-white/90 mb-10 leading-relaxed">
                        Nhận những bài viết, tin tức và kiến thức mới nhất được gửi thẳng đến hộp thư của bạn hàng tuần.
                    </p>
                    
                    <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-6 lg:p-8 border border-white/20">
                        @livewire('partials.newsletter-form')
                    </div>
                </div>
            </div>
        </div>
    </section>

@else
    {{-- Empty State --}}
    <section class="flex items-center justify-center min-h-[60vh]">
        <div class="text-center max-w-md mx-auto px-4">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 mb-6">
                <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-neutral-900 dark:text-white mb-4">Chào mừng!</h2>
            <p class="text-lg text-neutral-600 dark:text-neutral-400 mb-8">Hiện chưa có bài viết nào. Hãy quay lại sau nhé!</p>
        </div>
    </section>
@endif

@endsection

@push('styles')
<style>
    @keyframes gradient-shift {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }
    .animate-gradient-shift {
        background-size: 200% 200%;
        animation: gradient-shift 15s ease infinite;
    }
    
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
        animation: blob 20s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px) translateX(0px);
        }
        33% {
            transform: translateY(-30px) translateX(20px);
        }
        66% {
            transform: translateY(20px) translateX(-20px);
        }
    }
    .animate-float {
        animation: float 15s ease-in-out infinite;
    }
    .animation-delay-1000 {
        animation-delay: 1s;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fade-in-up 1s ease-out;
    }
    
    .scroll-reveal, .scroll-reveal-item {
        opacity: 0;
        transform: translateY(60px);
        transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1), transform 1s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scroll-reveal.is-visible, .scroll-reveal-item.is-visible {
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

    document.querySelectorAll('.scroll-reveal, .scroll-reveal-item').forEach(el => {
        observer.observe(el);
    });
});
</script>
@endpush
