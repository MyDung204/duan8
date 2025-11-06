@props(['src', 'alt' => ''])

@php
    // Use a simple transparent gif as a default placeholder.
    $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';

    // If the image is from Unsplash, we can generate a low-quality, blurred placeholder.
    if (str_contains($src, 'source.unsplash.com')) {
        // Append parameters for a small, blurred version for the placeholder.
        $placeholder = $src . '&w=50&q=30&blur=5';
    }
@endphp

<div x-data="{
        finalSrc: '{{ $src }}',
        loaded: false,
        init() {
            const img = this.$refs.image;
            // Start with the placeholder
            img.src = '{{ $placeholder }}';

            // Create a new image object to load the high-res version in the background
            const highResImg = new Image();
            highResImg.src = this.finalSrc;

            // When the high-res image is loaded, replace the src and mark as loaded
            highResImg.onload = () => {
                img.src = this.finalSrc;
                this.loaded = true;
            };
        }
    }"
    {{ $attributes->merge(['class' => 'relative bg-gray-200 dark:bg-gray-700 overflow-hidden']) }}
>
    <img x-ref="image"
         alt="{{ $alt }}"
         class="w-full h-full object-cover transition-all duration-700"
         :class="!loaded ? 'opacity-20 blur-md scale-110' : 'opacity-100 blur-0 scale-100'"
    >
</div>
