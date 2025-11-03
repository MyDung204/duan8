@extends('layouts.guest')

@section('title', $post->title)

@section('content')
<div class="py-8 md:py-16">
    <div class="max-w-5xl mx-auto px-4">
        {{-- Breadcrumbs --}}
        <nav class="mb-8 flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-400 scroll-reveal">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Trang chủ</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            @if($post->category)
                <a href="{{ route('categories.show.public', $post->category->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                    {{ $post->category->title }}
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            @endif
            <span class="text-neutral-900 dark:text-white font-medium line-clamp-1">{{ Str::limit($post->title, 50) }}</span>
        </nav>

        {{-- Post Hero Section --}}
        <article class="bg-white dark:bg-neutral-900 rounded-3xl overflow-hidden shadow-xl mb-12 scroll-reveal">
            {{-- Featured Image --}}
            @if($post->banner_image_url)
                <div class="relative h-[400px] md:h-[500px] overflow-hidden">
                    <img src="{{ $post->banner_image_url }}" 
                         alt="{{ $post->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    @if($post->category)
                        <div class="absolute top-6 left-6">
                            <a href="{{ route('categories.show.public', $post->category->slug) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-md text-white text-sm font-bold rounded-full hover:bg-white/30 transition border border-white/30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                {{ $post->category->title }}
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Post Header --}}
            <div class="px-6 md:px-12 py-8 md:py-12">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-neutral-900 dark:text-white mb-6 leading-tight">
                    {{ $post->title }}
                </h1>
                
                @if($post->short_description)
                    <p class="text-xl md:text-2xl text-neutral-600 dark:text-neutral-300 leading-relaxed mb-8">
                        {{ $post->short_description }}
                    </p>
                @endif

                {{-- Meta Information --}}
                <div class="flex flex-wrap items-center gap-6 text-sm text-neutral-500 dark:text-neutral-400 mb-8 pb-8 border-b border-neutral-200 dark:border-neutral-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">{{ $post->author_name ?? 'Admin' }}</span>
                    </div>
                    <span>&bull;</span>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $post->created_date }}
                    </div>
                    <span>&bull;</span>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span>{{ number_format($post->views_count ?? 0) }} lượt xem</span>
                    </div>
                    <span>&bull;</span>
                    <div class="flex items-center gap-2" x-data="{ readingTime: calculateReadingTime({{ Str::length(strip_tags($post->content)) }}) }">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span x-text="readingTime + ' phút đọc'"></span>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center gap-3">
                    <button onclick="window.print()" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        In bài viết
                    </button>
                    <button onclick="copyToClipboard()" 
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200 dark:hover:bg-neutral-700 transition text-sm font-semibold" 
                            id="copyBtn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span id="copyText">Sao chép link</span>
                    </button>
                </div>
            </div>

            {{-- Gallery Images --}}
            @if($post->gallery_image_urls && count($post->gallery_image_urls) > 0)
                <div class="px-6 md:px-12 pb-8">
                    <h2 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Thư viện ảnh
                    </h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($post->gallery_image_urls as $index => $imageUrl)
                            <button type="button" 
                                    onclick="openLightbox({{ $index }})"
                                    class="relative group aspect-square rounded-2xl overflow-hidden focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                <img src="{{ $imageUrl }}" 
                                     alt="Gallery Image {{ $index + 1 }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                    </svg>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Post Content --}}
            <div class="px-6 md:px-12 pb-12">
                <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:font-black prose-p:text-neutral-700 dark:prose-p:text-neutral-300 prose-a:text-indigo-600 dark:prose-a:text-indigo-400 prose-a:no-underline hover:prose-a:underline prose-img:rounded-2xl prose-img:shadow-xl prose-strong:text-neutral-900 dark:prose-strong:text-white prose-code:text-indigo-600 dark:prose-code:text-indigo-400 prose-pre:bg-neutral-100 dark:prose-pre:bg-neutral-800 leading-relaxed">
                    {!! $post->content !!}
                </div>

                {{-- Tags --}}
                @if($post->tags->isNotEmpty())
                    <div class="mt-12 pt-8 border-t border-neutral-200 dark:border-neutral-800">
                        <h3 class="text-lg font-bold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Thẻ (Tags)
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            @foreach($post->tags as $tag)
                                <a href="{{ route('posts.by_tag', $tag->slug) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 text-indigo-700 dark:text-indigo-300 rounded-full hover:from-indigo-200 hover:to-purple-200 dark:hover:from-indigo-900/50 dark:hover:to-purple-900/50 transition-all text-sm font-semibold">
                                    <span>#</span>{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Share Section --}}
                <div class="mt-12 pt-8 border-t border-neutral-200 dark:border-neutral-800">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div>
                            <span class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 block mb-3">Chia sẻ bài viết:</span>
                            <div class="flex items-center gap-3">
                                @php
                                    $shareUrl = url()->current();
                                    $shareTitle = $post->title;
                                    $shareDescription = $post->short_description ?? '';
                                @endphp
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="w-12 h-12 rounded-xl bg-blue-600 hover:bg-blue-700 flex items-center justify-center transition text-white shadow-lg hover:shadow-xl hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.502 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z"></path>
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($shareTitle) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="w-12 h-12 rounded-xl bg-sky-500 hover:bg-sky-600 flex items-center justify-center transition text-white shadow-lg hover:shadow-xl hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.384 4.482A13.924 13.924 0 013.315 5.253a4.913 4.913 0 001.455 6.572A4.912 4.912 0 01.99 10.187v.062a4.922 4.922 0 003.957 4.827 4.915 4.915 0 01-2.227.084 4.917 4.917 0 004.604 3.417A9.867 9.867 0 010 19.544a13.894 13.894 0 007.546 2.203A13.922 13.922 0 0022.09 7.37c-.39-.39-2.285-1.97-2.909-2.585z"></path>
                                    </svg>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="w-12 h-12 rounded-xl bg-blue-700 hover:bg-blue-800 flex items-center justify-center transition text-white shadow-lg hover:shadow-xl hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($shareTitle . ' ' . $shareUrl) }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer" 
                                   class="w-12 h-12 rounded-xl bg-green-500 hover:bg-green-600 flex items-center justify-center transition text-white shadow-lg hover:shadow-xl hover:scale-110">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        {{-- Previous/Next Navigation --}}
        <div class="grid md:grid-cols-2 gap-6 mb-16 scroll-reveal">
            @if(isset($previousPost) && $previousPost)
                <a href="{{ route('posts.show.public', $previousPost->slug) }}" 
                   class="group flex items-center gap-4 p-6 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 flex items-center justify-center group-hover:from-indigo-200 group-hover:to-purple-200 dark:group-hover:from-indigo-900/50 dark:group-hover:to-purple-900/50 transition">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-1 font-semibold">Bài trước</p>
                        <p class="font-bold text-neutral-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition line-clamp-2">{{ $previousPost->title }}</p>
                    </div>
                </a>
            @else
                <div></div>
            @endif
            
            @if(isset($nextPost) && $nextPost)
                <a href="{{ route('posts.show.public', $nextPost->slug) }}" 
                   class="group flex items-center gap-4 p-6 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl transition-all hover:-translate-y-1 text-right">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-1 font-semibold">Bài tiếp theo</p>
                        <p class="font-bold text-neutral-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition line-clamp-2">{{ $nextPost->title }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 flex items-center justify-center group-hover:from-indigo-200 group-hover:to-purple-200 dark:group-hover:from-indigo-900/50 dark:group-hover:to-purple-900/50 transition">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            @endif
        </div>

        {{-- Related Posts Section --}}
        @if(isset($relatedPosts) && $relatedPosts->count() > 0)
            <section class="mb-16 scroll-reveal">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-1 h-8 bg-gradient-to-b from-indigo-500 to-purple-500 rounded-full"></div>
                        <h2 class="text-3xl md:text-4xl font-black text-neutral-900 dark:text-white">Bài viết liên quan</h2>
                    </div>
                    @if($post->category)
                        <a href="{{ route('categories.show.public', $post->category->slug) }}" 
                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-semibold hidden md:block">
                            Xem tất cả
                        </a>
                    @endif
                </div>
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <a href="{{ route('posts.show.public', $relatedPost->slug) }}" 
                           class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-2 transition-all duration-300">
                            <div class="aspect-[16/10] overflow-hidden">
                                <img src="{{ $relatedPost->banner_image_url ?? 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=800' }}" 
                                     alt="{{ $relatedPost->title }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-5">
                                @if($relatedPost->category)
                                    <p class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 mb-2 uppercase tracking-wide">{{ $relatedPost->category->title }}</p>
                                @endif
                                <h3 class="font-black text-lg text-neutral-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2 mb-2">{{ $relatedPost->title }}</h3>
                                @if($relatedPost->short_description)
                                    <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2 mb-3">{{ $relatedPost->short_description }}</p>
                                @endif
                                <div class="flex items-center gap-3 text-xs text-neutral-500">
                                    <span>{{ $relatedPost->created_date }}</span>
                                    <span>&bull;</span>
                                    <span>{{ number_format($relatedPost->views_count ?? 0) }} lượt xem</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Comments Section --}}
        <section class="mb-16 scroll-reveal">
            @livewire('post-comments', ['post' => $post])
        </section>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightbox" 
     x-data="lightbox({{ json_encode($post->gallery_image_urls ?? []) }})" 
     x-cloak 
     x-show="isOpen"
     @keydown.escape.window="close()"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-sm p-4">
    <div class="absolute inset-0" @click="close()"></div>
    <div class="relative w-full max-w-6xl max-h-full z-10">
        <button @click="close()" 
                class="absolute -top-12 right-0 z-20 w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white flex items-center justify-center transition hover:scale-110 mb-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="relative">
            <img :src="images[currentIndex]" 
                 alt="Gallery Image" 
                 class="max-w-full max-h-[85vh] object-contain mx-auto rounded-2xl shadow-2xl cursor-pointer"
                 @click.stop="nextImage()">
            <button @click.stop="prevImage()" 
                    x-show="images.length > 1"
                    class="absolute top-1/2 left-4 -translate-y-1/2 w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white shadow-lg transition hover:scale-110 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button @click.stop="nextImage()" 
                    x-show="images.length > 1"
                    class="absolute top-1/2 right-4 -translate-y-1/2 w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white shadow-lg transition hover:scale-110 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
        <div x-show="images.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm text-white text-sm font-medium">
            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('lightbox', (imageUrls) => ({
        isOpen: false,
        images: imageUrls || [],
        currentIndex: 0,
        open(index) {
            if (this.images.length > 0 && index >= 0 && index < this.images.length) {
                this.currentIndex = index;
                this.isOpen = true;
                document.body.style.overflow = 'hidden';
            }
        },
        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },
        nextImage() {
            if (this.images.length > 0) {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
            }
        },
        prevImage() {
            if (this.images.length > 0) {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            }
        },
        goToImage(index) {
            if (index >= 0 && index < this.images.length) {
                this.currentIndex = index;
            }
        }
    }));

    document.addEventListener('keydown', (e) => {
        const lightboxEl = document.querySelector('[x-data*="lightbox"]');
        if (!lightboxEl) return;
        const lightbox = Alpine.$data(lightboxEl);
        if (!lightbox || !lightbox.isOpen) return;
        
        if (e.key === 'ArrowRight') {
            e.preventDefault();
            lightbox.nextImage();
        }
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            lightbox.prevImage();
        }
        if (e.key === 'Escape') {
            e.preventDefault();
            lightbox.close();
        }
    });
});

function calculateReadingTime(wordCount) {
    const wordsPerMinute = 200;
    const minutes = Math.ceil(wordCount / wordsPerMinute);
    return minutes || 1;
}

function copyToClipboard() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('copyBtn');
        const text = document.getElementById('copyText');
        if (btn && text) {
            text.textContent = 'Đã sao chép!';
            btn.classList.add('bg-green-100', 'dark:bg-green-900/30', 'text-green-700', 'dark:text-green-400');
            setTimeout(() => {
                text.textContent = 'Sao chép link';
                btn.classList.remove('bg-green-100', 'dark:bg-green-900/30', 'text-green-700', 'dark:text-green-400');
            }, 2000);
        }
    }).catch(() => {
        alert('Không thể sao chép. Vui lòng thử lại.');
    });
}

function openLightbox(index) {
    const lightboxEl = document.getElementById('lightbox');
    if (lightboxEl) {
        const lightbox = Alpine.$data(lightboxEl);
        if (lightbox && lightbox.open) {
            lightbox.open(index);
        }
    }
}

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
    [x-cloak] { display: none !important; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .scroll-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .scroll-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    @media print {
        .no-print { display: none !important; }
        body { background: white; }
        .prose { max-width: 100%; }
    }
</style>
@endpush
