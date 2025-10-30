<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>

{{-- ĐÃ XÓA KHỐI @php ĐỌC SESSION TẠI ĐÂY --}}

@livewireScripts

{{-- @stack('scripts') cho các script con (như confirmDelete) --}}
@stack('scripts')