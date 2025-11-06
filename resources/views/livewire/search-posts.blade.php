<div class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Search Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-neutral-900 dark:text-white mb-4">Tìm kiếm bài viết</h1>
            <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">Khám phá kho kiến thức của chúng tôi bằng cách nhập từ khóa bạn quan tâm.</p>
        </div>

        <!-- Search Input -->
        <div class="relative max-w-2xl mx-auto mb-12">
            <input 
                type="text" 
                wire:model.live.debounce.500ms="query"
                placeholder="{{ $placeholder }}"
                class="w-full pl-6 pr-14 py-4 text-lg text-neutral-900 bg-white dark:bg-neutral-800 border-2 border-neutral-200 dark:border-neutral-700 rounded-full shadow-lg focus:outline-none focus:ring-4 focus:ring-primary-500/30 focus:border-primary-500 transition-all duration-300"
            />
            <div class="absolute inset-y-0 right-0 flex items-center pr-5 pointer-events-none">
                <!-- Loading Spinner -->
                <svg wire:loading wire:target="query" class="animate-spin h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <!-- Search Icon -->
                <svg wire:loading.remove wire:target="query" class="h-6 w-6 text-neutral-400 dark:text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Search Results -->
        <div wire:loading.class.delay="opacity-50 transition-opacity" class="min-h-[400px]">
            @if(empty($query))
                <div class="text-center py-16">
                    <div class="inline-block p-5 bg-primary-100/50 dark:bg-primary-900/30 rounded-full mb-6">
                        <svg class="w-12 h-12 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-neutral-800 dark:text-neutral-200">Bắt đầu tìm kiếm</h2>
                    <p class="text-neutral-600 dark:text-neutral-400 mt-2">Nhập vào ô tìm kiếm phía trên để xem kết quả.</p>
                </div>
            @elseif($posts->count() > 0)
                <h2 class="text-2xl font-bold text-neutral-900 dark:text-white mb-8">
                    Kết quả cho <span class="text-primary-600">"{{ $query }}"</span> ({{ $posts->total() }} bài viết)
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <x-post-card :post="$post" :isFirst="$loop->first" />
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="inline-block p-5 bg-red-100/50 dark:bg-red-900/30 rounded-full mb-6">
                        <svg class="w-12 h-12 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-semibold text-neutral-800 dark:text-neutral-200">Không tìm thấy kết quả</h2>
                    <p class="text-neutral-600 dark:text-neutral-400 mt-2">Rất tiếc, không có bài viết nào phù hợp với từ khóa <span class="font-semibold text-neutral-800 dark:text-neutral-200">"{{ $query }}"</span>.</p>
                    <p class="text-neutral-500 mt-4">Vui lòng thử lại với một từ khóa khác.</p>
                </div>
            @endif
        </div>
    </div>
</div>