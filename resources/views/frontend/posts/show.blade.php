@extends('frontend.layouts.app')

@section('title', $post->title)

@section('content')
<div class="py-12 md:py-20">
    <div class="container px-4 mx-auto">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <button onclick="history.back()" class="inline-flex items-center text-neutral-600 dark:text-neutral-400 hover:text-primary-600 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Quay lại
                </button>
            </div>
            <!-- Post Header -->
            <div class="text-center mb-8 md:mb-12">
                @if($post->category)
                    <a href="{{ route('posts.public', ['category' => $post->category->id]) }}" class="text-primary-600 font-semibold tracking-wide uppercase">{{ $post->category->title }}</a>
                @endif
                <h1 class="text-3xl md:text-5xl font-bold text-neutral-900 dark:text-white mt-2">{{ $post->title }}</h1>
                @if($post->short_description)
                    <p class="mt-4 text-xl text-neutral-600 dark:text-neutral-300"><span class="font-semibold">Mô tả ngắn:</span> {{ $post->short_description }}</p>
                @endif
                <div class="mt-4 text-sm text-neutral-500 dark:text-neutral-400">
                    <span>Đăng bởi {{ $post->author_name ?? 'Admin' }}</span>
                    <span class="mx-2">&bull;</span>
                    <span>Ngày tạo: {{ $post->created_date }}</span>
                    @if($post->formatted_published_at)
                        <span class="mx-2">&bull;</span>
                        <span>Xuất bản: {{ $post->formatted_published_at }}</span>
                    @endif
                </div>
            </div>

            <!-- Featured Image -->
            @if($post->banner_image_url)
                <div class="mb-8 md:mb-12 rounded-2xl overflow-hidden aspect-w-16 aspect-h-9">
                    <img src="{{ $post->banner_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Gallery Images -->
            @if($post->gallery_image_urls && count($post->gallery_image_urls) > 0)
                <h2 class="text-2xl font-bold text-neutral-900 dark:text-white mb-4">Thư viện ảnh</h2>
                <div class="mb-8 md:mb-12 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($post->gallery_image_urls as $index => $imageUrl)
                        <div class="rounded-lg overflow-hidden aspect-w-16 aspect-h-9 cursor-pointer" @click="open({{ $index }})">
                            <img src="{{ $imageUrl }}" alt="Gallery Image" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Post Content -->
            <div class="prose dark:prose-invert max-w-none mx-auto text-lg leading-relaxed">
                {!! $post->content !!}
            </div>

            <!-- Post Footer/Meta -->
            <div class="mt-12 border-t border-neutral-200 dark:border-neutral-800 pt-8">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-neutral-500 dark:text-neutral-400">
                        <p>Lượt xem: {{ $post->views_count }}</p>
                    </div>
                    <!-- Share links can be added here -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Lightbox Modal -->
<div x-data="lightbox({{ json_encode($post->gallery_image_urls) }})" x-cloak x-show="isOpen"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4">

    <!-- Lightbox Content -->
    <div x-show="isOpen" @click.away="close()" class="relative w-full max-w-4xl max-h-full">
        <!-- Close Button -->
        <button @click="close()" class="absolute top-0 right-0 m-4 text-white text-3xl leading-none hover:text-neutral-300 z-10">
            &times;
        </button>

        <!-- Image -->
        <img :src="images[currentIndex]" alt="Gallery Image" class="max-w-full max-h-full object-contain mx-auto rounded-lg shadow-lg">

        <!-- Navigation Buttons -->
        <button @click="prevImage()"
                class="absolute top-1/2 left-4 -translate-y-1/2 bg-white/50 hover:bg-white/75 rounded-full p-2 shadow-md transition-colors duration-200">
            <svg class="w-6 h-6 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button @click="nextImage()"
                class="absolute top-1/2 right-4 -translate-y-1/2 bg-white/50 hover:bg-white/75 rounded-full p-2 shadow-md transition-colors duration-200">
            <svg class="w-6 h-6 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('lightbox', (imageUrls) => ({
        isOpen: false,
        images: imageUrls,
        currentIndex: 0,
        open(index) {
            this.currentIndex = index;
            this.isOpen = true;
        },
        close() {
            this.isOpen = false;
        },
        nextImage() {
            this.currentIndex = (this.currentIndex + 1) % this.images.length;
        },
        prevImage() {
            this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        }
    }));
});
</script>
