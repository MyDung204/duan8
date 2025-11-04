@php
    // Layout trỏ đến layout thực tế trong layouts/app/sidebar.blade.php
    // Render $slot thành HTML để truyền qua @include
    $content = isset($slot) ? $slot->toHtml() : '';
@endphp

@include('layouts.app.sidebar', ['content' => $content, 'title' => $title ?? null])

{{-- ĐÃ XÓA KHỐI @php ĐỌC SESSION TẠI ĐÂY --}}

@livewireScripts

{{-- @stack('scripts') cho các script con (như confirmDelete) --}}
@stack('scripts')