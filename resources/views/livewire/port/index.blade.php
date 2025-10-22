<?php

use Livewire\Volt\Component;

new class extends Component
{
    // Đây là một component trống.
    // Chúng ta sẽ thêm logic (như lấy data) vào đây sau.
}; ?>

<x-layouts.app>
    <!-- Thêm Tiêu đề cho trang -->
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Quản lý bài viết') }}
        </h2>
    </x-slot>

    <!-- Khu vực nội dung trống -->
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 overflow-hidden bg-white rounded-lg shadow-sm sm:p-6 dark:bg-gray-800">
                <p class="text-gray-900 dark:text-gray-100">
                    {{ __("Nội dung trang quản lý bài viết sẽ hiển thị ở đây.") }}
                </p>
                <!-- (Sau này bạn sẽ thêm bảng, nút 'Thêm mới' v.v... vào đây) -->
            </div>
        </div>
    </div>

</x-layouts.app>
