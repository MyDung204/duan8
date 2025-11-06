@props(['post', 'isFirst' => false])

<a href="{{ route('posts.show.public', $post->slug) }}"
   class="group block bg-white dark:bg-neutral-900 rounded-2xl overflow-hidden border border-neutral-200/80 dark:border-neutral-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
    <div class="aspect-[4/3] overflow-hidden relative">
        <img src="{{ $post->banner_image_url ?? 'https://via.placeholder.com/800x600' }}" alt="{{ $post->title }}"
             loading="{{ $isFirst ? 'eager' : 'lazy' }}"
             fetchpriority="{{ $isFirst ? 'high' : 'auto' }}"
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        <div
            class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        </div>
        @if ($post->category)
            <span
                class="absolute top-4 left-4 px-3 py-1 text-xs font-semibold bg-primary-600 text-white rounded-full backdrop-blur-sm">{{ $post->category->title }}</span>
        @endif
    </div>
    <div class="p-5">
        <h3
            class="text-lg font-bold text-neutral-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors duration-200 line-clamp-2 mb-2">
            {{ $post->title }}</h3>
        @if ($post->short_description)
            <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2 mb-3">
                {{ $post->short_description }}</p>
        @endif
        <div class="flex items-center gap-3 text-xs text-neutral-500">
            <span>{{ $post->author_name ?? 'Admin' }}</span>
            <span>&bull;</span>
            <span>{{ $post->created_date }}</span>
        </div>
    </div>
</a>
