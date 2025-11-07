<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="{{ asset('image/favicon.png') }}" type="image/png">

{{-- Fonts are self-hosted via resources/css/fonts.css and Vite build --}}
@once
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endonce


{{-- Layouts sẽ tự quyết định @vite entries (public vs admin) --}}
@fluxAppearance

{{-- Script để đảm bảo mặc định là dark mode trước khi Flux UI load - CHẠY NGAY LẬP TỨC --}}
<script>
    // Chạy ngay lập tức, không đợi DOM ready
    (function() {
        // Đặt mặc định là dark mode nếu chưa có preference hoặc đang là light
        const currentAppearance = localStorage.getItem('flux-appearance');
        if (!currentAppearance || currentAppearance === 'light') {
            localStorage.setItem('flux-appearance', 'dark');
        }
        
        // Đảm bảo HTML có class dark ngay lập tức
        document.documentElement.classList.add('dark');
        document.documentElement.classList.remove('light');
    })();
</script>
