@extends('layouts.frontend.app')

@section('title', 'Về chúng tôi')

@section('banner')
<section class="relative py-16 md:py-24 bg-gradient-to-br from-rose-600 via-red-600 to-orange-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-96 h-96 bg-rose-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-orange-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white tracking-tight mb-4">Về chúng tôi</h1>
            <p class="text-lg md:text-xl text-white/90 max-w-3xl mx-auto">Lan tỏa kiến thức, kết nối đam mê và xây dựng một cộng đồng vững mạnh</p>
        </div>
    </div>
</section>
@endsection

@section('content')

<!-- Introduction/Mission Section -->
<section class="py-16 scroll-reveal">
    <div class="grid md:grid-cols-2 gap-12 lg:gap-16 items-center">
        <div>
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Câu chuyện của chúng tôi</h2>
            <div class="space-y-4 text-neutral-600 dark:text-neutral-400 leading-relaxed text-lg">
                <p>Bắt đầu từ một ý tưởng đơn giản, chúng tôi mong muốn tạo ra một nền tảng nơi mọi người có thể dễ dàng tiếp cận với những kiến thức mới nhất, những bài viết chuyên sâu và những chia sẻ kinh nghiệm thực tế.</p>
                <p>Chúng tôi tin rằng tri thức là sức mạnh, và việc chia sẻ tri thức sẽ giúp cộng đồng cùng nhau phát triển.</p>
                <p>Trải qua nhiều năm phát triển, chúng tôi tự hào đã xây dựng được một cộng đồng lớn mạnh với hàng ngàn độc giả mỗi tháng và hàng trăm bài viết chất lượng được đóng góp bởi các chuyên gia hàng đầu.</p>
            </div>
        </div>
        <div class="relative">
            <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&h=600&fit=crop" alt="Our Story" class="w-full h-full object-cover">
            </div>
            <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-primary-600 rounded-full opacity-20 blur-3xl"></div>
        </div>
    </div>
</section>

<!-- Our Values Section -->
<section class="py-16 bg-gradient-to-br from-neutral-50 to-neutral-100 dark:from-neutral-900 dark:to-neutral-800 rounded-3xl scroll-reveal">
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Giá trị cốt lõi</h2>
        <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">Những nguyên tắc định hướng cho mọi hoạt động của chúng tôi</p>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-rose-500 to-red-600 text-white mb-6 shadow-lg">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3">Minh bạch</h3>
            <p class="text-base text-neutral-600 dark:text-neutral-400 leading-relaxed">Chúng tôi cam kết cung cấp thông tin rõ ràng, trung thực và có thể kiểm chứng trong mọi hoạt động.</p>
        </div>
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-orange-500 to-rose-600 text-white mb-6 shadow-lg">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3">Sáng tạo</h3>
            <p class="text-base text-neutral-600 dark:text-neutral-400 leading-relaxed">Chúng tôi không ngừng tìm kiếm và áp dụng những ý tưởng mới để mang lại giá trị tốt nhất cho cộng đồng.</p>
        </div>
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="inline-flex items-center justify-center h-16 w-16 rounded-2xl bg-gradient-to-br from-red-500 to-pink-600 text-white mb-6 shadow-lg">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2v-2a4 4 0 014-4h2a4 4 0 014 4v2m3-3H9m1.5-3.5a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3">Cộng đồng</h3>
            <p class="text-base text-neutral-600 dark:text-neutral-400 leading-relaxed">Chúng tôi xây dựng một môi trường hợp tác, chia sẻ và tôn trọng lẫn nhau trong cộng đồng.</p>
        </div>
    </div>
</section>

<!-- Our Team Section -->
<section class="mt-16 py-16 bg-gradient-to-br from-neutral-50 to-neutral-100 dark:from-neutral-900 dark:to-neutral-800 rounded-3xl scroll-reveal">
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Đội ngũ của chúng tôi</h2>
        <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">Những con người tâm huyết đứng sau thành công của dự án</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {{-- Card 1 --}}
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <img class="w-32 h-32 rounded-full mx-auto mb-6 object-cover border-4 border-neutral-200 dark:border-neutral-700 shadow-lg" src="https://ui-avatars.com/api/?name=Long+Nguyen&size=128&background=7F9CF5&color=EBF4FF" alt="Nguyễn Văn Bảo Long" loading="lazy">
            <h3 class="text-2xl font-bold mb-1">Lê Mỹ Dung</h3>
            <p class="text-primary-600 dark:text-primary-400 font-medium mb-3">Founder & Developer</p>
            <p class="text-base text-neutral-600 dark:text-neutral-400 leading-relaxed">
                Sinh viên Công nghệ Thông tin, người đã xây dựng nền tảng này.
            </p>
        </div>
        {{-- Card 2 --}}
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <img class="w-32 h-32 rounded-full mx-auto mb-6 object-cover border-4 border-neutral-200 dark:border-neutral-700 shadow-lg" src="https://ui-avatars.com/api/?name=Minh+Anh&size=128&background=6EE7B7&color=065F46" alt="Trần Thị Minh Anh" loading="lazy">
            <h3 class="text-2xl font-bold mb-1">Nguyễn Văn Bảo Long</h3>
            <p class="text-primary-600 dark:text-primary-400 font-medium mb-3">Trưởng ban Biên tập</p>
            <p class="text-base text-neutral-600 dark:text-neutral-400 leading-relaxed">
                Đảm bảo chất lượng nội dung và định hướng phát triển chủ đề.
            </p>
        </div>
        {{-- Card 3 --}}
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <img class="w-32 h-32 rounded-full mx-auto mb-6 object-cover border-4 border-neutral-200 dark:border-neutral-700 shadow-lg" src="https://ui-avatars.com/api/?name=Van+Hung&size=128&background=FDBA74&color=7C2D12" alt="Lê Văn Hùng" loading="lazy">
            <h3 class="text-2xl font-bold mb-1">Nguyễn Thị Quỳnh Hương</h3>
            <p class="text-primary-600 dark:text-primary-400 font-medium mb-3">Chuyên gia Tài chính</p>
            <p class="text-base text-neutral-600 dark:text-neutral-400 leading-relaxed">
                Cung cấp các bài viết chuyên sâu về thị trường ngân hàng và đầu tư.
            </p>
        </div>
    </div>
</section>

<!-- Milestones/Impact Section -->
<section class="mt-16 py-16 bg-gradient-to-br from-rose-50 via-red-50 to-orange-50 dark:from-neutral-900 dark:via-neutral-800 dark:to-neutral-900 rounded-3xl scroll-reveal">
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Những cột mốc đáng nhớ</h2>
        <p class="text-lg text-neutral-600 dark:text-neutral-400 max-w-2xl mx-auto">Hành trình phát triển và những thành tựu chúng tôi đã đạt được</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="text-5xl font-bold bg-gradient-to-br from-rose-500 to-red-600 bg-clip-text text-transparent mb-3">2019</div>
            <p class="text-lg font-semibold text-neutral-900 dark:text-white">Thành lập</p>
            <p class="text-sm text-neutral-500 mt-2">Khởi đầu hành trình</p>
        </div>
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="text-5xl font-bold bg-gradient-to-br from-orange-500 to-rose-600 bg-clip-text text-transparent mb-3">500+</div>
            <p class="text-lg font-semibold text-neutral-900 dark:text-white">Bài viết</p>
            <p class="text-sm text-neutral-500 mt-2">Nội dung chất lượng</p>
        </div>
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="text-5xl font-bold bg-gradient-to-br from-red-500 to-pink-600 bg-clip-text text-transparent mb-3">50K+</div>
            <p class="text-lg font-semibold text-neutral-900 dark:text-white">Độc giả/tháng</p>
            <p class="text-sm text-neutral-500 mt-2">Cộng đồng lớn mạnh</p>
        </div>
        <div class="text-center p-8 bg-white dark:bg-neutral-900 border border-neutral-200/80 dark:border-neutral-800 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="text-5xl font-bold bg-gradient-to-br from-pink-500 to-rose-600 bg-clip-text text-transparent mb-3">20+</div>
            <p class="text-lg font-semibold text-neutral-900 dark:text-white">Cộng tác viên</p>
            <p class="text-sm text-neutral-500 mt-2">Chuyên gia hàng đầu</p>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="mt-16 py-16 bg-gradient-to-r from-red-600 via-rose-600 to-pink-600 rounded-3xl overflow-hidden relative">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative text-center text-white">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Sẵn sàng khám phá?</h2>
        <p class="text-lg text-white/90 max-w-2xl mx-auto mb-8">Bắt đầu hành trình tri thức của bạn ngay hôm nay với hàng ngàn bài viết chất lượng.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('posts.public') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-white text-red-600 font-semibold hover:bg-neutral-100 transition shadow-lg hover:shadow-xl hover:scale-105 transform duration-300">
                Xem tất cả bài viết
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white font-semibold hover:bg-white/20 transition">
                Liên hệ với chúng tôi
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<style>
    @keyframes blob {
        0%, 100% {
            transform: translate(0px, 0px) scale(1);
        }
        33% {
            transform: translate(30px, -50px) scale(1.1);
        }
        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .scroll-reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .scroll-reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.scroll-reveal').forEach(section => {
        observer.observe(section);
    });
});
</script>
@endpush