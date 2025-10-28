<header class="sticky top-0 z-40 backdrop-blur bg-white/70 dark:bg-neutral-900/60 border-b border-neutral-200/70 dark:border-neutral-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-semibold tracking-tight">
            <x-app-logo-icon class="size-6" />
            <span>{{ config('app.name', 'Dự án') }}</span>
        </a>
        <nav class="hidden md:flex items-center gap-6 text-sm">
            <a href="{{ route('home') }}" class="hover:text-primary-600">Trang chủ</a>
            <a href="{{ route('posts.public') }}" class="hover:text-primary-600">Bài viết</a>
            <a href="{{ route('categories.public') }}" class="hover:text-primary-600">Danh mục</a>
            <a href="{{ route('about') }}" class="hover:text-primary-600">Về chúng tôi</a>
            <a href="{{ route('contact') }}" class="hover:text-primary-600">Liên hệ</a>
        </nav>
        <div class="hidden md:flex items-center gap-4">
            @guest
                <a href="{{ route('login') }}" class="text-sm hover:text-primary-600">Đăng nhập</a>
                <a href="{{ route('register') }}" class="text-sm hover:text-primary-600">Đăng ký</a>
            @else
                <a href="{{ route('dashboard') }}" class="text-sm hover:text-primary-600">Bảng điều khiển</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm hover:text-primary-600">Đăng xuất</button>
                </form>
            @endguest
        </div>
        <div class="md:hidden">
            <button id="mobileMenuBtn" class="p-2 rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-800" aria-label="Menu">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6"><path fill-rule="evenodd" d="M3.75 5.25A.75.75 0 014.5 4.5h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm0 7.5a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm.75 6.75a.75.75 0 000 1.5h15a.75.75 0 000-1.5h-15z" clip-rule="evenodd" /></svg>
            </button>
        </div>
    </div>
    <div id="mobileMenu" class="md:hidden hidden border-t border-neutral-200 dark:border-neutral-800">
        <nav class="px-4 py-3 grid gap-3 text-sm">
            <a href="{{ route('home') }}" class="py-2">Trang chủ</a>
            <a href="{{ route('posts.public') }}" class="py-2">Bài viết</a>
            <a href="{{ route('categories.public') }}" class="py-2">Danh mục</a>
            <a href="{{ route('about') }}" class="py-2">Về chúng tôi</a>
            <a href="{{ route('contact') }}" class="py-2">Liên hệ</a>
            <div class="border-t border-neutral-200 dark:border-neutral-800 pt-3 mt-3">
            @guest
                <a href="{{ route('login') }}" class="py-2">Đăng nhập</a>
                <a href="{{ route('register') }}" class="py-2">Đăng ký</a>
            @else
                <a href="{{ route('dashboard') }}" class="py-2">Bảng điều khiển</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="py-2 w-full text-left">Đăng xuất</button>
                </form>
            @endguest
            </div>
        </nav>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobileMenuBtn');
            const menu = document.getElementById('mobileMenu');
            if (btn && menu) {
                btn.addEventListener('click', () => menu.classList.toggle('hidden'));
            }
        });
    </script>
</header>


