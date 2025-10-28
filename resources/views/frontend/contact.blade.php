@extends('frontend.layouts.app')

@section('title', 'Liên hệ')

@section('banner')
<section class="relative py-16 md:py-24 bg-gradient-to-r from-lime-500 via-green-500 to-emerald-500">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight">Kết nối với chúng tôi</h1>
            <p class="mt-4 text-lg text-white/80 max-w-2xl mx-auto">Chúng tôi luôn sẵn sàng lắng nghe từ bạn. Gửi cho chúng tôi một tin nhắn!</p>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="grid lg:grid-cols-3 gap-12 items-start">
    <!-- Contact Form -->
    <div class="lg:col-span-2 bg-white dark:bg-neutral-900 p-8 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
        <h2 class="text-2xl font-bold mb-6">Gửi tin nhắn cho chúng tôi</h2>
        <form class="space-y-6">
            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Họ và tên</label>
                    <input type="text" id="name" class="mt-1 block w-full px-4 py-2.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Email</label>
                    <input type="email" id="email" class="mt-1 block w-full px-4 py-2.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-green-500" />
                </div>
            </div>
            <div>
                <label for="subject" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Chủ đề</label>
                <input type="text" id="subject" class="mt-1 block w-full px-4 py-2.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-green-500" />
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">Nội dung</label>
                <textarea id="message" rows="5" class="mt-1 block w-full px-4 py-2.5 rounded-lg bg-neutral-100 dark:bg-neutral-800 focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
            </div>
            <div>
                <button type="submit" class="px-6 py-3 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">Gửi tin nhắn</button>
            </div>
        </form>
    </div>

    <!-- Contact Info & Socials -->
    <div class="space-y-8">
        <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
            <h3 class="text-xl font-bold mb-4">Thông tin liên hệ</h3>
            <ul class="space-y-4 text-sm">
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    <span>123 Nguyễn Văn Cừ, Quận 5, TP.HCM</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <span>contact@example.com</span>
                </li>
                <li class="flex items-start">
                    <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.135a11.249 11.249 0 005.422 5.422l1.135-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    <span>0123 456 789</span>
                </li>
            </ul>
        </div>

        <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
            <h3 class="text-xl font-bold mb-4">Theo dõi chúng tôi</h3>
            <div class="flex gap-4">
                <a href="#" class="text-neutral-500 hover:text-green-600 transition">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.502 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z" /></svg>
                </a>
                <a href="#" class="text-neutral-500 hover:text-green-600 transition">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.384 4.482A13.924 13.924 0 013.315 5.253a4.913 4.913 0 001.455 6.572A4.912 4.912 0 01.99 10.187v.062a4.922 4.922 0 003.957 4.827 4.915 4.915 0 01-2.227.084 4.917 4.917 0 004.604 3.417A9.867 9.867 0 010 19.544a13.894 13.894 0 007.546 2.203A13.922 13.922 0 0022.09 7.37c-.39-.39-2.285-1.97-2.909-2.585z" /></svg>
                </a>
                <a href="#" class="text-neutral-500 hover:text-green-600 transition">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.291 2.766 7.95 6.602 9.297-.487-.291-1.57-.96-1.57-2.215v-.751c0-.75-.245-1.229-.757-1.523C6.452 16.273 6 15.688 6 15c0-1.782 1.452-3.235 3.235-3.235S12.47 13.218 12.47 15c0 .688-.452 1.273-1.077 1.583-.512.294-.757.773-.757 1.523v.751c0 1.255-1.083 1.924-1.57 2.215C19.234 20.088 22 16.291 22 12c0-5.523-4.477-10-10-10zm-.751 18.069C13.255 20.007 16.593 17 19.069 13.917V12c0-3.3-2.7-6-6-6s-6 2.7-6 6v1.917C7.407 17 10.745 20.007 1.249 18.069z" /></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<section class="mt-12">
    <h2 class="text-2xl font-bold mb-6 text-center">Chúng tôi đang ở đâu?</h2>
    <div class="aspect-[16/9] rounded-2xl overflow-hidden shadow-lg">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.631885548651!2d106.68220381533396!3d10.76287949233079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f1c0973e533%3A0x4919124758d3198f!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBLaG9hIGjhu41jIFThu7Egbmhpw6puLCDEkEhRRy1IQ00!5e0!3m2!1svi!2s!4v1678886345678!5m2!1svi!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

<!-- FAQ/Support Links Section -->
<section class="mt-12 py-12 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold">Cần hỗ trợ thêm?</h2>
        <p class="mt-4 max-w-2xl mx-auto text-neutral-600 dark:text-neutral-400">Nếu bạn có bất kỳ câu hỏi nào khác hoặc cần hỗ trợ, vui lòng kiểm tra các tài nguyên sau hoặc liên hệ trực tiếp với chúng tôi.</p>
        <div class="mt-8 flex justify-center gap-6">
            <a href="#" class="inline-flex items-center px-6 py-3 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition">
                <p>Xem FAQs</p>
            </a>
            <a href="#" class="inline-flex items-center px-6 py-3 rounded-lg border border-green-600 text-green-600 font-semibold hover:bg-green-50 transition">
                <p>Trung tâm hỗ trợ</p>
            </a>
        </div>
    </div>
</section>

@endsection