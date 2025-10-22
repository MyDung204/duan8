<x-layouts.app.sidebar :title="$title ?? null">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
{{-- ... (Code của bạn) ... --}}

        @livewireScripts
        
        {{-- THAY THẾ TOÀN BỘ KHỐI CODE NÀY --}}

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // KỊCH BẢN A: Bắt thông báo sự kiện (dispatch) (ĐÃ SỬA)
            // Dùng cho Xóa, Bật/Tắt (không chuyển trang)
            // Chúng ta lắng nghe 'show-toast' trực tiếp trên 'window'
            window.addEventListener('show-toast', event => {
                
                // Lấy dữ liệu từ event.detail (không có [0])
                const { title, text, icon } = event.detail;

                Swal.fire({
                    title: title || 'Thông báo!', // Dùng title nếu có
                    text: text,
                    icon: icon || 'success', // Dùng icon nếu có
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });

            // KỊCH BẢN B: Bắt thông báo session flash (Giữ nguyên)
            // Dùng cho Thêm mới, Sửa (có chuyển trang)
            document.addEventListener('livewire:navigated', () => {
                @if (session()->has('success'))
                    Swal.fire({
                        title: 'Thành công!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endif
                @if (session()->has('error'))
                    Swal.fire({
                        title: 'Có lỗi!',
                        text: '{{ session('error') }}',
                        icon: 'error',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true
                    });
                @endif
            });
        </script>
        {{-- KẾT THÚC KHỐI THAY THẾ --}}

        @stack('scripts')
    </body>
</html>