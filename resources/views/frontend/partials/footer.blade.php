<footer class="bg-gradient-to-b from-neutral-50 via-neutral-100 to-neutral-200 dark:from-neutral-950 dark:via-neutral-900 dark:to-neutral-950 border-t-2 border-primary-500/20 mt-24 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute top-0 left-0 w-96 h-96 bg-primary-500/5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-500/5 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Company Info -->
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-bold text-2xl tracking-tight mb-6 group">
                    <div class="p-2 bg-gradient-to-br from-primary-500 to-purple-600 rounded-xl shadow-lg group-hover:shadow-xl transition-shadow">
                        <x-app-logo-icon class="size-6 text-white" />
                    </div>
                    <span class="text-neutral-900 dark:text-white">{{ config('app.name', 'Dự án') }}</span>
                </a>
                <p class="text-neutral-600 dark:text-neutral-300 leading-relaxed mb-6 text-sm">Nền tảng cung cấp kiến thức và thông tin hữu ích, được cập nhật liên tục để phục vụ cộng đồng.</p>
                <div class="flex items-center gap-3">
                    <a href="#" target="_blank" rel="noopener noreferrer" class="group w-11 h-11 rounded-xl bg-white dark:bg-neutral-800 hover:bg-primary-500 flex items-center justify-center transition-all hover:scale-110 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-neutral-600 dark:text-neutral-400 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5Z"/>
                        </svg>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class="group w-11 h-11 rounded-xl bg-white dark:bg-neutral-800 hover:bg-sky-500 flex items-center justify-center transition-all hover:scale-110 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-neutral-600 dark:text-neutral-400 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M22.25 5.3c-.7.3-1.5.5-2.2.6a4 4 0 0 0 1.7-2.1 8 8 0 0 1-2.5 1 4 4 0 0 0-6.7 3.6 11.3 11.3 0 0 1-8.2-4.2 4 4 0 0 0 1.2 5.3 4 4 0 0 1-1.8-.5v.1a4 4 0 0 0 3.2 3.9 4 4 0 0 1-1.8.1 4 4 0 0 0 3.7 2.8 8 8 0 0 1-5.1 1.7A8.3 8.3 0 0 1 2 18a11.2 11.2 0 0 0 6.2 1.8c7.5 0 11.5-6.2 11.5-11.5 0-.2 0-.4 0-.6a8.2 8.2 0 0 0 2-2.2Z"/>
                        </svg>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class="group w-11 h-11 rounded-xl bg-white dark:bg-neutral-800 hover:bg-red-600 flex items-center justify-center transition-all hover:scale-110 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-neutral-600 dark:text-neutral-400 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M19.802 5.513a3.5 3.5 0 0 0-2.475-2.475C15.55 2.5 12 2.5 12 2.5s-3.55 0-5.327.538a3.5 3.5 0 0 0-2.475 2.475C3.66 7.29 3.5 9.08 3.5 12s.16 4.71.698 6.487a3.5 3.5 0 0 0 2.475 2.475C8.45 21.5 12 21.5 12 21.5s3.55 0 5.327-.538a3.5 3.5 0 0 0 2.475-2.475c.538-1.777.698-3.567.698-6.487s-.16-4.71-.698-6.487ZM9.545 15.5v-7l6 3.5-6 3.5Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class="group w-11 h-11 rounded-xl bg-white dark:bg-neutral-800 hover:bg-blue-600 flex items-center justify-center transition-all hover:scale-110 shadow-md hover:shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-neutral-600 dark:text-neutral-400 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <div>
                <h4 class="font-bold text-lg mb-6 text-neutral-900 dark:text-white flex items-center gap-2">
                    <div class="w-1 h-6 bg-gradient-to-b from-primary-500 to-purple-600 rounded-full"></div>
                    Điều hướng
                </h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('home') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-2 group font-medium">
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Trang chủ</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('posts.public') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-2 group font-medium">
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Bài viết</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.public') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-2 group font-medium">
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Danh mục</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('about') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-2 group font-medium">
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Về chúng tôi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contact') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-2 group font-medium">
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Liên hệ</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-bold text-lg mb-6 text-neutral-900 dark:text-white flex items-center gap-2">
                    <div class="w-1 h-6 bg-gradient-to-b from-purple-500 to-pink-600 rounded-full"></div>
                    Liên kết nhanh
                </h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('posts.public') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-2 group font-medium">
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Bài viết mới nhất</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.public') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-2 group font-medium">
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Tất cả danh mục</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('posts.public') }}" class="text-neutral-600 dark:text-neutral-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors inline-flex items-center gap-2 group font-medium">
                            <svg class="w-4 h-4 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Tất cả bài viết</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="lg:col-span-1">
                <h4 class="font-bold text-lg mb-6 text-neutral-900 dark:text-white flex items-center gap-2">
                    <div class="w-1 h-6 bg-gradient-to-b from-pink-500 to-rose-600 rounded-full"></div>
                    Đăng ký nhận bản tin
                </h4>
                <p class="text-neutral-600 dark:text-neutral-300 text-sm mb-6 leading-relaxed">Nhận thông tin và bài viết mới nhất trực tiếp vào email của bạn.</p>
                @livewire('frontend.newsletter-form')
                <div class="mt-6 p-4 bg-gradient-to-r from-primary-50 to-purple-50 dark:from-primary-900/20 dark:to-purple-900/20 rounded-xl border border-primary-200 dark:border-primary-800">
                    <p class="text-xs text-neutral-600 dark:text-neutral-400 flex items-start gap-2">
                        <svg class="w-4 h-4 text-primary-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Chúng tôi cam kết bảo vệ quyền riêng tư của bạn và không chia sẻ thông tin với bên thứ ba.</span>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="pt-8 border-t-2 border-neutral-200 dark:border-neutral-800">
            <div class="text-center">
                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                    @php echo '@' . date('Y'); @endphp <span class="font-semibold text-neutral-900 dark:text-white">Mỹ Dung</span>. Bảo lưu mọi quyền.
                </p>
            </div>
        </div>
    </div>
</footer>
