<header x-data="{ open: false }" class="sticky top-0 z-40 w-full backdrop-blur bg-white/70 dark:bg-neutral-900/60 border-b border-neutral-200/70 dark:border-neutral-800 transition-colors duration-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-bold text-lg tracking-tight">
                <x-app-logo-icon class="size-6 text-primary-600" />
                <span>{{ config('app.name', 'Dự án') }}</span>
            </a>
            <nav class="hidden md:flex items-center gap-1">
                @php
                    $navLinks = [
                        'home' => ['name' => 'Trang chủ', 'route' => route('home')],
                        'posts.public' => ['name' => 'Bài viết', 'route' => route('posts.public')],
                        'categories.public' => ['name' => 'Danh mục', 'route' => route('categories.public')],
                        'about' => ['name' => 'Về chúng tôi', 'route' => route('about')],
                        'contact' => ['name' => 'Liên hệ', 'route' => route('contact')],
                    ];
                @endphp
                @foreach ($navLinks as $name => $link)
                    <a href="{{ $link['route'] }}" 
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ request()->routeIs($name) ? 'text-primary-600 bg-primary-50 dark:bg-primary-900/50' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                        {{ $link['name'] }}
                    </a>
                @endforeach
            </nav>
            <div class="hidden md:flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-primary-600">Đăng nhập</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 transition-colors">Đăng ký</a>
                @else
                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-2 text-sm font-medium text-neutral-700 dark:text-neutral-300">
                            <span>{{ Auth::user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 transition-transform duration-200" :class="{'rotate-180': dropdownOpen}"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
                        </button>
                        <div x-show="dropdownOpen" 
                             @click.away="dropdownOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-neutral-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-700">Bảng điều khiển</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-neutral-700 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-700">Đăng xuất</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
            <div class="md:hidden">
                <button @click="open = !open" class="p-2 rounded-md text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800" aria-label="Menu">
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6"><path fill-rule="evenodd" d="M3.75 5.25A.75.75 0 014.5 4.5h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm0 7.5a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm.75 6.75a.75.75 0 000 1.5h15a.75.75 0 000-1.5h-15z" clip-rule="evenodd" /></svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        </div>
    </div>
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden border-t border-neutral-200 dark:border-neutral-800"
         @click.away="open = false">
        <nav class="px-4 py-3 grid gap-y-1 text-sm">
            @foreach ($navLinks as $name => $link)
                <a href="{{ $link['route'] }}" 
                   class="px-3 py-2 rounded-md font-medium {{ request()->routeIs($name) ? 'text-primary-600 bg-primary-50 dark:bg-primary-900/50' : 'text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800' }}">
                    {{ $link['name'] }}
                </a>
            @endforeach
            <div class="border-t border-neutral-200 dark:border-neutral-800 pt-4 mt-3 grid gap-y-1">
            @guest
                <a href="{{ route('login') }}" class="px-3 py-2 rounded-md font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800">Đăng nhập</a>
                <a href="{{ route('register') }}" class="px-3 py-2 rounded-md font-medium text-white bg-primary-600 hover:bg-primary-700">Đăng ký</a>
            @else
                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800">Bảng điều khiển</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-100 dark:hover:bg-neutral-800">Đăng xuất</button>
                </form>
            @endguest
            </div>
        </nav>
    </div>
</header>


