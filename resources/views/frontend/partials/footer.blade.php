<footer class="bg-neutral-100 dark:bg-neutral-900 border-t border-neutral-200/70 dark:border-neutral-800/50 mt-20">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <div class="md:col-span-2 lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-bold text-lg tracking-tight">
                    <x-app-logo-icon class="size-7 text-primary-600" />
                    <span>{{ config('app.name', 'Dự án') }}</span>
                </a>
                <p class="mt-4 text-sm text-neutral-600 dark:text-neutral-400">Nền tảng cung cấp kiến thức và thông tin hữu ích, được cập nhật liên tục để phục vụ cộng đồng.</p>
                <div class="mt-6 flex items-center space-x-4">
                    <a href="#" class="text-neutral-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><span class="sr-only">Facebook</span><svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="currentColor"><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5Z"/></svg></a>
                    <a href="#" class="text-neutral-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><span class="sr-only">Twitter</span><svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="currentColor"><path d="M22.25 5.3c-.7.3-1.5.5-2.2.6a4 4 0 0 0 1.7-2.1 8 8 0 0 1-2.5 1 4 4 0 0 0-6.7 3.6 11.3 11.3 0 0 1-8.2-4.2 4 4 0 0 0 1.2 5.3 4 4 0 0 1-1.8-.5v.1a4 4 0 0 0 3.2 3.9 4 4 0 0 1-1.8.1 4 4 0 0 0 3.7 2.8 8 8 0 0 1-5.1 1.7A8.3 8.3 0 0 1 2 18a11.2 11.2 0 0 0 6.2 1.8c7.5 0 11.5-6.2 11.5-11.5 0-.2 0-.4 0-.6a8.2 8.2 0 0 0 2-2.2Z"/></svg></a>
                    <a href="#" class="text-neutral-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"><span class="sr-only">YouTube</span><svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M19.802 5.513a3.5 3.5 0 0 0-2.475-2.475C15.55 2.5 12 2.5 12 2.5s-3.55 0-5.327.538a3.5 3.5 0 0 0-2.475 2.475C3.66 7.29 3.5 9.08 3.5 12s.16 4.71.698 6.487a3.5 3.5 0 0 0 2.475 2.475C8.45 21.5 12 21.5 12 21.5s3.55 0 5.327-.538a3.5 3.5 0 0 0 2.475-2.475c.538-1.777.698-3.567.698-6.487s-.16-4.71-.698-6.487ZM9.545 15.5v-7l6 3.5-6 3.5Z" clip-rule="evenodd"/></svg></a>
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-neutral-800 dark:text-neutral-200 mb-4">Điều hướng</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Trang chủ</a></li>
                    <li><a href="{{ route('posts.public') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Bài viết</a></li>
                    <li><a href="{{ route('categories.public') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Danh mục</a></li>
                    <li><a href="{{ route('about') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Về chúng tôi</a></li>
                    <li><a href="{{ route('contact') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Liên hệ</a></li>
                </ul>
            </div>

            <div class="md:col-span-2 lg:col-span-2">
                 <h4 class="font-semibold text-neutral-800 dark:text-neutral-200 mb-4">Đăng ký nhận bản tin</h4>
                 <p class="text-sm text-neutral-600 dark:text-neutral-400 mb-4">Nhận thông tin và bài viết mới nhất trực tiếp vào email của bạn.</p>
                <form class="flex items-center gap-2 max-w-md">
                    <label for="email-address" class="sr-only">Email address</label>
                    <input type="email" id="email-address" autocomplete="email" placeholder="Nhập email của bạn" required class="appearance-none w-full px-4 py-2 text-sm bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 rounded-md shadow-sm placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500" />
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:ring-offset-neutral-900 transition-colors">Đăng ký</button>
                </form>
            </div>
        </div>
        <div class="mt-10 pt-8 border-t border-neutral-200 dark:border-neutral-800/80 flex flex-col sm:flex-row justify-between items-center text-sm text-neutral-500 dark:text-neutral-400">
            <p class="text-center sm:text-left">© {{ date('Y') }} {{ config('app.name', 'Dự án') }}. All Rights Reserved.</p>
            <div class="flex space-x-4 mt-4 sm:mt-0">
                <a href="#" class="hover:text-primary-600">Terms of Service</a>
                <a href="#" class="hover:text-primary-600">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>


