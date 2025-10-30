@extends('frontend.layouts.app')

@section('title', 'Liên hệ')

@section('banner')
<section class="relative py-16 md:py-24 bg-gradient-to-br from-lime-600 via-green-600 to-emerald-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0">
        <div class="absolute top-0 right-0 w-96 h-96 bg-green-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    </div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white tracking-tight mb-4">Kết nối với chúng tôi</h1>
            <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto">Chúng tôi luôn sẵn sàng lắng nghe từ bạn. Gửi cho chúng tôi một tin nhắn!</p>
        </div>
    </div>
</section>
@endsection

@section('content')
<div class="grid lg:grid-cols-3 gap-12 items-start">
    <!-- Contact Form -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-neutral-900 p-8 md:p-10 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 shadow-lg">
            <div class="mb-8">
                <h2 class="text-3xl font-bold mb-3 flex items-center gap-3">
                    <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    Gửi tin nhắn cho chúng tôi
                </h2>
                <p class="text-neutral-600 dark:text-neutral-400">Điền thông tin bên dưới và chúng tôi sẽ phản hồi trong vòng 24 giờ</p>
            </div>
            <form x-data="contactForm()" @submit.prevent="submitForm" class="space-y-6">
            <div class="grid sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            x-model="form.name"
                            required
                            class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                            placeholder="Nhập họ và tên"
                        />
                        <p x-show="errors.name" class="mt-1 text-xs text-red-500" x-text="errors.name"></p>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            x-model="form.email"
                            required
                            class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                            placeholder="your@email.com"
                        />
                        <p x-show="errors.email" class="mt-1 text-xs text-red-500" x-text="errors.email"></p>
                    </div>
                </div>
                <div>
                    <label for="subject" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                        Chủ đề <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="subject" 
                        x-model="form.subject"
                        required
                        class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                        placeholder="Tiêu đề tin nhắn"
                    />
                    <p x-show="errors.subject" class="mt-1 text-xs text-red-500" x-text="errors.subject"></p>
                </div>
                <div>
                    <label for="message" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                        Nội dung <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="message" 
                        rows="6" 
                        x-model="form.message"
                        required
                        class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none" 
                        placeholder="Viết tin nhắn của bạn ở đây..."
                    ></textarea>
                    <p x-show="errors.message" class="mt-1 text-xs text-red-500" x-text="errors.message"></p>
                    <p class="mt-1 text-xs text-neutral-500" x-text="`${form.message.length} ký tự`"></p>
                </div>
                
                <!-- Success Message -->
                <div x-show="success" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-green-800 dark:text-green-300">Gửi thành công!</p>
                        <p class="text-sm text-green-700 dark:text-green-400">Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất có thể.</p>
            </div>
            </div>

                <!-- Error Message -->
                <div x-show="error" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
            <div>
                        <p class="font-semibold text-red-800 dark:text-red-300">Có lỗi xảy ra</p>
                        <p class="text-sm text-red-700 dark:text-red-400" x-text="error"></p>
            </div>
            </div>

                <button 
                    type="submit" 
                    :disabled="loading"
                    class="w-full px-6 py-4 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold hover:from-green-700 hover:to-emerald-700 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                    <svg x-show="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-show="!loading">Gửi tin nhắn</span>
                    <span x-show="loading">Đang gửi...</span>
                </button>
        </form>
        </div>
    </div>

    <!-- Contact Info & Socials -->
    <div class="space-y-6">
        <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 shadow-sm">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Thông tin liên hệ
            </h3>
            <ul class="space-y-5">
                <li class="flex items-start gap-4 group">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-neutral-900 dark:text-white mb-1">Địa chỉ</p>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400">18 Phạm Hùng, Mễ Trì, Nam Từ Liêm, Hà Nội</p>
                    </div>
                </li>
                <li class="flex items-start gap-4 group">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-neutral-900 dark:text-white mb-1">Email</p>
                        <a href="mailto:contact@example.com" class="text-sm text-green-600 dark:text-green-400 hover:underline">lemydung204@gmail.com</a>
                    </div>
                </li>
                <li class="flex items-start gap-4 group">
                    <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.135a11.249 11.249 0 005.422 5.422l1.135-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-neutral-900 dark:text-white mb-1">Điện thoại</p>
                        <a href="tel:0123456789" class="text-sm text-green-600 dark:text-green-400 hover:underline">0123 456 789</a>
                    </div>
                </li>
            </ul>
        </div>

        <div class="bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 shadow-sm">
            <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Theo dõi chúng tôi
            </h3>
            <div class="flex gap-4">
                <a href="#" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-lg bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center hover:bg-green-100 dark:hover:bg-green-900/30 transition text-neutral-600 dark:text-neutral-400 hover:text-green-600 dark:hover:text-green-400 group">
                    <svg class="h-6 w-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.502 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z"></path>
                    </svg>
                </a>
                <a href="#" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-lg bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center hover:bg-green-100 dark:hover:bg-green-900/30 transition text-neutral-600 dark:text-neutral-400 hover:text-green-600 dark:hover:text-green-400 group">
                    <svg class="h-6 w-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 4.557a9.83 9.83 0 01-2.828.775 4.932 4.932 0 002.165-2.724 9.864 9.864 0 01-3.127 1.195 4.916 4.916 0 00-8.384 4.482A13.924 13.924 0 013.315 5.253a4.913 4.913 0 001.455 6.572A4.912 4.912 0 01.99 10.187v.062a4.922 4.922 0 003.957 4.827 4.915 4.915 0 01-2.227.084 4.917 4.917 0 004.604 3.417A9.867 9.867 0 010 19.544a13.894 13.894 0 007.546 2.203A13.922 13.922 0 0022.09 7.37c-.39-.39-2.285-1.97-2.909-2.585z"></path>
                    </svg>
                </a>
                <a href="#" target="_blank" rel="noopener noreferrer" class="w-12 h-12 rounded-lg bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center hover:bg-green-100 dark:hover:bg-green-900/30 transition text-neutral-600 dark:text-neutral-400 hover:text-green-600 dark:hover:text-green-400 group">
                    <svg class="h-6 w-6 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M19.802 5.513a3.5 3.5 0 0 0-2.475-2.475C15.55 2.5 12 2.5 12 2.5s-3.55 0-5.327.538a3.5 3.5 0 0 0-2.475 2.475C3.66 7.29 3.5 9.08 3.5 12s.16 4.71.698 6.487a3.5 3.5 0 0 0 2.475 2.475C8.45 21.5 12 21.5 12 21.5s3.55 0 5.327-.538a3.5 3.5 0 0 0 2.475-2.475c.538-1.777.698-3.567.698-6.487s-.16-4.71-.698-6.487ZM9.545 15.5v-7l6 3.5-6 3.5Z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-600 to-emerald-600 p-6 rounded-2xl text-white">
            <h3 class="text-xl font-bold mb-3">Thời gian làm việc</h3>
            <ul class="space-y-2 text-sm">
                <li class="flex justify-between">
                    <span>Thứ 2 - Thứ 6</span>
                    <span class="font-semibold">8:00 - 17:00</span>
                </li>
                <li class="flex justify-between">
                    <span>Thứ 7</span>
                    <span class="font-semibold">8:00 - 12:00</span>
                </li>
                <li class="flex justify-between">
                    <span>Chủ nhật</span>
                    <span class="font-semibold">Nghỉ</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Map Section -->
<section class="mt-16">
    <h2 class="text-3xl font-bold mb-8 text-center">Chúng tôi đang ở đâu?</h2>
    <div class="aspect-[16/9] rounded-2xl overflow-hidden shadow-2xl border border-neutral-200 dark:border-neutral-800">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.631885548651!2d106.68220381533396!3d10.76287949233079!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f1c0973e533%3A0x4919124758d3198f!2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBLaG9hIGjhu41jIFThu7Egbmhpw6puLCDEkEhRRy1IQ00!5e0!3m2!1svi!2s!4v1678886345678!5m2!1svi!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</section>

<!-- FAQ/Support Links Section -->
<section class="mt-16 py-12 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Cần hỗ trợ thêm?</h2>
        <p class="mt-4 max-w-2xl mx-auto text-neutral-600 dark:text-neutral-400 mb-8">Nếu bạn có bất kỳ câu hỏi nào khác hoặc cần hỗ trợ, vui lòng kiểm tra các tài nguyên sau hoặc liên hệ trực tiếp với chúng tôi.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="#" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Xem FAQs
            </a>
            <a href="#" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg border-2 border-green-600 text-green-600 font-semibold hover:bg-green-50 dark:hover:bg-green-900/20 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Trung tâm hỗ trợ
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function contactForm() {
    return {
        form: {
            name: '',
            email: '',
            subject: '',
            message: ''
        },
        errors: {},
        loading: false,
        success: false,
        error: '',

        validate() {
            this.errors = {};
            
            if (!this.form.name.trim()) {
                this.errors.name = 'Vui lòng nhập họ và tên';
            }
            
            if (!this.form.email.trim()) {
                this.errors.email = 'Vui lòng nhập email';
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                this.errors.email = 'Email không hợp lệ';
            }
            
            if (!this.form.subject.trim()) {
                this.errors.subject = 'Vui lòng nhập chủ đề';
            }
            
            if (!this.form.message.trim()) {
                this.errors.message = 'Vui lòng nhập nội dung';
            } else if (this.form.message.length < 10) {
                this.errors.message = 'Nội dung phải có ít nhất 10 ký tự';
            }

            return Object.keys(this.errors).length === 0;
        },

        async submitForm() {
            this.success = false;
            this.error = '';

            if (!this.validate()) {
                return;
            }

            this.loading = true;

            try {
                // Simulate API call - replace with actual endpoint
                await new Promise(resolve => setTimeout(resolve, 1500));
                
                // In a real app, you would make an actual API call here:
                // const response = await fetch('/api/contact', {
                //     method: 'POST',
                //     headers: { 'Content-Type': 'application/json' },
                //     body: JSON.stringify(this.form)
                // });
                
                this.success = true;
                this.form = { name: '', email: '', subject: '', message: '' };
                
                // Scroll to success message
                setTimeout(() => {
                    document.querySelector('[x-show="success"]')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            } catch (err) {
                this.error = 'Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

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
</style>
@endpush