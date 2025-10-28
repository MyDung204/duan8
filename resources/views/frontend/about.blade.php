@extends('frontend.layouts.app')

@section('title', 'Về chúng tôi')

@section('banner')
<section class="relative py-16 md:py-24 bg-gradient-to-r from-rose-500 via-red-500 to-orange-500">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight">Sứ mệnh của chúng tôi</h1>
            <p class="mt-4 text-lg text-white/80 max-w-3xl mx-auto">Lan tỏa kiến thức, kết nối đam mê và xây dựng một cộng đồng công nghệ vững mạnh tại Việt Nam.</p>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Introduction/Mission Section -->
<section class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold">Câu chuyện của chúng tôi</h2>
                <p class="mt-4 text-neutral-600 dark:text-neutral-400 leading-relaxed">Bắt đầu từ một ý tưởng đơn giản, chúng tôi mong muốn tạo ra một nền tảng nơi mọi người có thể dễ dàng tiếp cận với những kiến thức công nghệ mới nhất, những bài viết chuyên sâu và những chia sẻ kinh nghiệm thực tế. Chúng tôi tin rằng tri thức là sức mạnh, và việc chia sẻ tri thức sẽ giúp cộng đồng cùng nhau phát triển.</p>
                <p class="mt-4 text-neutral-600 dark:text-neutral-400 leading-relaxed">Trải qua nhiều năm phát triển, chúng tôi tự hào đã xây dựng được một cộng đồng lớn mạnh với hàng ngàn độc giả mỗi tháng và hàng trăm bài viết chất lượng được đóng góp bởi các chuyên gia hàng đầu.</p>
            </div>
            <div>
                <img src="https://via.placeholder.com/800x600" alt="Our Story" class="rounded-2xl shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- Our Values Section -->
<section class="py-12 bg-white dark:bg-neutral-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold">Giá trị cốt lõi</h2>
            <p class="text-neutral-600 dark:text-neutral-400 mt-2">Những nguyên tắc định hướng cho mọi hoạt động của chúng tôi.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-rose-500 text-white mx-auto mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-medium">Minh bạch</h3>
                <p class="mt-2 text-base text-neutral-600 dark:text-neutral-400">Chúng tôi cam kết cung cấp thông tin rõ ràng, trung thực và có thể kiểm chứng.</p>
            </div>
            <div class="text-center p-6 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-rose-500 text-white mx-auto mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <h3 class="text-xl font-medium">Sáng tạo</h3>
                <p class="mt-2 text-base text-neutral-600 dark:text-neutral-400">Chúng tôi không ngừng tìm kiếm và áp dụng những ý tưởng mới để mang lại giá trị tốt nhất.</p>
            </div>
            <div class="text-center p-6 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl">
                <div class="flex items-center justify-center h-12 w-12 rounded-md bg-rose-500 text-white mx-auto mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a4 4 0 014-4h2a4 4 0 014 4v2m3-3H9m1.5-3.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" /></svg>
                </div>
                <h3 class="text-xl font-medium">Cộng đồng</h3>
                <p class="mt-2 text-base text-neutral-600 dark:text-neutral-400">Chúng tôi xây dựng một môi trường hợp tác, chia sẻ và tôn trọng lẫn nhau.</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Team Section -->
<section class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold">Đội ngũ của chúng tôi</h2>
            <p class="text-neutral-600 dark:text-neutral-400 mt-2">Những con người tâm huyết đứng sau thành công của dự án.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach(range(1, 4) as $i)
                <div class="text-center bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
                    <img class="w-32 h-32 rounded-full object-cover mx-auto mb-4" src="https://i.pravatar.cc/128?u=a042581f4e29026704{{ 1 + $i }}" alt="Team member {{ $i }}">
                    <h3 class="text-lg font-medium">Thành viên {{ $i }}</h3>
                    <p class="text-rose-500">Chức vụ {{ $i }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Milestones/Impact Section -->
<section class="py-12 bg-white dark:bg-neutral-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold">Những cột mốc đáng nhớ</h2>
            <p class="text-neutral-600 dark:text-neutral-400 mt-2">Hành trình phát triển và những thành tựu chúng tôi đã đạt được.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center p-6 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl">
                <div class="text-5xl font-bold text-rose-500">2019</div>
                <p class="mt-2 text-lg font-medium">Thành lập</p>
            </div>
            <div class="text-center p-6 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl">
                <div class="text-5xl font-bold text-rose-500">500+</div>
                <p class="mt-2 text-lg font-medium">Bài viết</p>
            </div>
            <div class="text-center p-6 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl">
                <div class="text-5xl font-bold text-rose-500">50K+</div>
                <p class="mt-2 text-lg font-medium">Độc giả/tháng</p>
            </div>
            <div class="text-center p-6 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl">
                <div class="text-5xl font-bold text-rose-500">20+</div>
                <p class="mt-2 text-lg font-medium">Cộng tác viên</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="mt-12 py-12 bg-gradient-to-r from-red-600 to-rose-600 rounded-2xl">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h2 class="text-3xl font-bold">Sẵn sàng khám phá?</h2>
        <p class="mt-2 max-w-2xl mx-auto">Bắt đầu hành trình tri thức của bạn ngay hôm nay với hàng ngàn bài viết chất lượng.</p>
        <a href="{{ route('posts.public') }}" class="mt-6 inline-flex items-center px-6 py-3 rounded-lg bg-white text-red-600 font-semibold hover:bg-neutral-200 transition">
            Xem tất cả bài viết
            <svg class="ml-2 -mr-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
        </a>
    </div>
</section>

@endsection