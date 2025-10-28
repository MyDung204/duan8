<footer class="mt-20 border-t border-neutral-200/70 dark:border-neutral-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10 grid gap-8 md:grid-cols-4">
        <div>
            <div class="flex items-center gap-2 font-semibold">
                <x-app-logo-icon class="size-6" />
                <span>{{ config('app.name', 'Dự án') }}</span>
            </div>
            <p class="mt-3 text-sm text-neutral-600 dark:text-neutral-400">Nội dung chất lượng, cập nhật liên tục.</p>
        </div>
        <div>
            <h4 class="font-semibold mb-3">Điều hướng</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}" class="hover:underline">Trang chủ</a></li>
                <li><a href="{{ route('posts.public') }}" class="hover:underline">Bài viết</a></li>
                <li><a href="{{ route('categories.public') }}" class="hover:underline">Danh mục</a></li>
                <li><a href="{{ route('about') }}" class="hover:underline">Về chúng tôi</a></li>
                <li><a href="{{ route('contact') }}" class="hover:underline">Liên hệ</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold mb-3">Kết nối</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#" class="hover:underline">Facebook</a></li>
                <li><a href="#" class="hover:underline">Twitter</a></li>
                <li><a href="#" class="hover:underline">YouTube</a></li>
            </ul>
        </div>
        <div>
            <h4 class="font-semibold mb-3">Bản tin</h4>
            <form class="grid grid-cols-1 sm:grid-cols-[1fr_auto] gap-2">
                <input type="email" placeholder="Email của bạn" class="w-full px-3 py-2 rounded-md bg-neutral-100 dark:bg-neutral-800 focus:outline-none" />
                <button class="px-4 py-2 rounded-md bg-black text-white dark:bg-white dark:text-black">Đăng ký</button>
            </form>
        </div>
    </div>
    <div class="py-4 text-center text-xs text-neutral-600 dark:text-neutral-500">© {{ date('Y') }} {{ config('app.name', 'Dự án') }}. All rights reserved.</div>
</footer>


