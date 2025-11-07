<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.shared.head')
        @vite(['resources/css/app.css','resources/js/app.js'])
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
        <div class="bg-neutral-950 flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-16 w-auto mb-1 items-center justify-center rounded-md">
                        <img src="{{ asset('image/logo.png') }}" alt="Logo" class="h-16 w-auto">
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
