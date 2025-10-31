<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        @vite(['resources/css/admin.css','resources/js/admin.js'])
        <link rel="preload" href="{{ Vite::asset('resources/css/app.css') }}" as="style" />
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="min-h-screen flex">
            <aside class="w-64 shrink-0 border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 p-4 space-y-6">
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

                <div class="mt-auto hidden lg:block">
                    <div class="flex items-center gap-2 px-2 py-2 rounded-md">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>
                        <div class="grid flex-1 text-start leading-tight">
                            <span class="truncate text-sm font-semibold">{{ auth()->user()->name }}</span>
                            <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                        </div>
                    </div>
                    <div class="flex flex-col text-sm">
                        <a href="{{ route('profile.edit') }}" wire:navigate class="inline-flex items-center gap-2 px-3 py-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            <span class="material-symbols-outlined text-base">settings</span>
                            {{ __('Settings') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center gap-2 px-3 py-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                <span class="material-symbols-outlined text-base">logout</span>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        @fluxScripts
    </body>
</html>
