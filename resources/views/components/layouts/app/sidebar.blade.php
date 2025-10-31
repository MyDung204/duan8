<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @vite(['resources/css/admin.css','resources/js/admin.js'])
        <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style" />
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="min-h-screen flex">
            <aside class="fixed top-0 left-0 h-screen w-64 border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 flex flex-col p-4 space-y-6 z-50">
                <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                    <x-app-logo />
                </a>

                <nav class="flex flex-col gap-1 text-sm">
                    <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center gap-2 px-3 py-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 {{ request()->routeIs('dashboard') ? 'bg-zinc-100 dark:bg-zinc-800' : '' }}">
                        <span class="material-symbols-outlined text-base">home</span>
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('categories.index') }}" wire:navigate class="inline-flex items-center gap-2 px-3 py-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 {{ request()->routeIs('categories.*') ? 'bg-zinc-100 dark:bg-zinc-800' : '' }}">
                        <span class="material-symbols-outlined text-base">photo</span>
                        {{ __('Quản lý danh mục') }}
                    </a>
                    <a href="{{ route('posts.index') }}" wire:navigate class="inline-flex items-center gap-2 px-3 py-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 {{ request()->routeIs('posts.*') ? 'bg-zinc-100 dark:bg-zinc-800' : '' }}">
                        <span class="material-symbols-outlined text-base">description</span>
                        {{ __('Quản lý bài đăng') }}
                    </a>
                </nav>

                <div x-data="{ open: false }" @click.away="open = false" class="relative mt-auto">
                    <button @click="open = !open" class="w-full flex items-center gap-2 p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>
                        <div class="grid flex-1 text-start leading-tight">
                            <span class="truncate text-sm font-semibold">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                        </div>
                        <span class="material-symbols-outlined text-base transition-transform" :class="{'rotate-180': open}">expand_less</span>
                    </button>

                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute bottom-full mb-2 w-[calc(100%-1rem)] left-2 origin-bottom-left bg-white dark:bg-zinc-800 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        style="display: none;"
                    >
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                                <span class="material-symbols-outlined text-base">settings</span>
                                {{ __('Settings') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700">
                                    <span class="material-symbols-outlined text-base">logout</span>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="flex-1 pl-64">
                {{ $slot }}
            </main>
        </div>

        @fluxScripts
    </body>
</html>