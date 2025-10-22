<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>

{{-- ĐÃ XÓA KHỐI @php ĐỌC SESSION TẠI ĐÂY --}}

@livewireScripts

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // KỊCH BẢN A: Bắt thông báo (dispatch) (Giữ lại)
    // Dùng cho Xóa, Bật/Tắt (không chuyển trang)
    window.addEventListener('show-toast', event => {
        const { title, text, icon } = event.detail;
        Swal.fire({
            title: title || 'Thông báo!',
            text: text,
            icon: icon || 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    });

    // KỊCH BẢN B (SESSION FLASH) ĐÃ BỊ XÓA HOÀN TOÀN KHỎI FILE NÀY
</script>

{{-- @stack('scripts') cho các script con (như confirmDelete) --}}
@stack('scripts')