@extends('frontend.layouts.app')

@section('title', 'Danh mục')

@section('banner')
<section class="relative py-16 md:py-24 bg-gradient-to-r from-teal-500 via-cyan-500 to-sky-500">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight">Khám phá theo chủ đề</h1>
            <p class="mt-4 text-lg text-white/80 max-w-2xl mx-auto">Tìm kiếm nội dung theo các danh mục được phân loại rõ ràng.</p>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Featured Categories Section -->
<section class="mb-12">
    <h2 class="text-2xl font-bold mb-6">Danh mục nổi bật</h2>
    <div class="grid md:grid-cols-3 gap-8">
        @foreach($categories->take(3) as $category)
            <a href="{{ route('posts.public', ['category' => $category->id]) }}" class="group relative block aspect-[4/3] rounded-2xl overflow-hidden text-white">
                <div class="absolute inset-0">
                    <img src="{{ $category->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $category->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="absolute inset-0 bg-black/60 group-hover:bg-black/70 transition-colors duration-300"></div>
                <div class="relative h-full flex flex-col justify-end p-6">
                    <h3 class="text-xl font-bold">{{ $category->title }}</h3>
                    <p class="mt-1 text-sm text-white/80">{{ $category->posts->count() }} bài viết</p>
                </div>
            </a>
        @endforeach
    </div>
</section>

<!-- All Categories Section -->
<section class="mb-12">
    <h2 class="text-2xl font-bold mb-6">Tất cả danh mục</h2>
    <div class="mb-6">
        <input type="text" id="categorySearch" placeholder="Tìm kiếm danh mục..." class="w-full px-4 py-2.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-teal-500" onkeyup="filterCategories()" />
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="categoryList">
        @forelse($categories->skip(3) as $category)
            <a href="{{ route('posts.public', ['category' => $category->id]) }}" class="group relative block aspect-[4/3] rounded-2xl overflow-hidden text-white category-item">
                <div class="absolute inset-0">
                    <img src="{{ $category->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $category->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="absolute inset-0 bg-black/60 group-hover:bg-black/70 transition-colors duration-300"></div>
                <div class="relative h-full flex flex-col justify-end p-6">
                    <h3 class="text-xl font-bold">{{ $category->title }}</h3>
                    <p class="mt-1 text-sm text-white/80">{{ $category->posts->count() }} bài viết</p>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-16">
                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M5 11v2m14-2v2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-white">Chưa có danh mục</h3>
                <p class="mt-1 text-sm text-neutral-500">Hiện tại chưa có danh mục nào được tạo.</p>
            </div>
        @endforelse
    </div>
</section>

<!-- Call to Action / Explore Posts Section -->
<section class="mt-12 py-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="text-3xl font-bold">Khám phá các bài viết</h2>
        <p class="mt-2 max-w-2xl mx-auto">Tìm kiếm các bài viết theo chủ đề bạn quan tâm và mở rộng kiến thức của mình.</p>
        <a href="{{ route('posts.public') }}" class="mt-6 inline-flex items-center px-6 py-3 rounded-lg bg-white text-purple-600 font-semibold hover:bg-neutral-200 transition">
            Xem tất cả bài viết
            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
function filterCategories() {
    const input = document.getElementById('categorySearch');
    const filter = input.value.toLowerCase();
    const categoryList = document.getElementById('categoryList');
    const items = categoryList.getElementsByClassName('category-item');

    for (let i = 0; i < items.length; i++) {
        const title = items[i].querySelector('h3');
        if (title) {
            if (title.textContent.toLowerCase().indexOf(filter) > -1) {
                items[i].style.display = "";
            } else {
                items[i].style.display = "none";
            }
        }
    }
}
</script>
@endpush