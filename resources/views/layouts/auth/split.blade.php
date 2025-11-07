<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <style>
            /* Đảm bảo background luôn là màu tối cho trang auth */
            html.dark,
            html.dark body,
            html.dark .bg-background,
            html.dark [data-flux-field],
            html.dark [data-flux-control] {
                background-color: #0a0a0a !important;
            }
            
            /* Đảm bảo container cũng có background tối */
            html.dark > body > div {
                background-color: #0a0a0a !important;
            }
            
            /* Đảm bảo input và form elements có background tối */
            html.dark input[data-flux-control],
            html.dark input[type="email"],
            html.dark input[type="password"],
            html.dark input[type="text"] {
                background-color: #171717 !important;
                border-color: #404040 !important;
                color: #fafafa !important;
            }
            
            html.dark input[data-flux-control]::placeholder,
            html.dark input[type="email"]::placeholder,
            html.dark input[type="password"]::placeholder,
            html.dark input[type="text"]::placeholder {
                color: #737373 !important;
            }
            
            /* Đảm bảo labels và text có màu sáng */
            html.dark [data-flux-label],
            html.dark label {
                color: #fafafa !important;
            }
            
            /* Đảm bảo form container có background tối */
            html.dark form {
                background-color: transparent !important;
            }
            
            /* Đảm bảo headings và text trong form có màu sáng */
            html.dark form h1,
            html.dark form h2,
            html.dark form h3,
            html.dark form h4,
            html.dark form h5,
            html.dark form h6,
            html.dark form p,
            html.dark [data-flux-heading],
            html.dark [data-flux-subheading] {
                color: #fafafa !important;
            }
            
            /* Đảm bảo links trong form có màu sáng */
            html.dark form a,
            html.dark [data-flux-link] {
                color: #fafafa !important;
            }
            
            html.dark form a:hover,
            html.dark [data-flux-link]:hover {
                color: #d4d4d4 !important;
            }
            
            /* Đảm bảo các phần tử ngoài form cũng có màu sáng */
            html.dark body > div > div h1,
            html.dark body > div > div h2,
            html.dark body > div > div h3,
            html.dark body > div > div h4,
            html.dark body > div > div h5,
            html.dark body > div > div h6,
            html.dark body > div > div p,
            html.dark body > div > div a {
                color: #fafafa !important;
            }
        </style>
    </head>
    <body class="min-h-screen bg-neutral-950 antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-neutral-900 relative hidden h-full flex-col p-10 text-white lg:flex border-e border-neutral-800">
                <div class="absolute inset-0 bg-neutral-900"></div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-md">
                        <x-app-logo-icon class="me-2 h-7 fill-current text-white" />
                    </span>
                    {{ config('app.name', 'Laravel') }}
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <flux:heading size="lg">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer><flux:heading>{{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8 bg-neutral-950">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-white" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
