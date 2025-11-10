<form wire:submit.prevent="submitForm" class="space-y-6">
    @if ($success)
        <div class="p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-semibold text-green-800 dark:text-green-300">Gửi thành công!</p>
                <p class="text-sm text-green-700 dark:text-green-400">Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất có thể.</p>
            </div>
        </div>
    @endif

    @if ($error)
        <div class="p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-semibold text-red-800 dark:text-red-300">Có lỗi xảy ra</p>
                <p class="mt-1 text-sm text-red-700 dark:text-red-400">{{ $error }}</p>
            </div>
        </div>
    @endif

    <div class="grid sm:grid-cols-2 gap-6">
        <div>
            <label for="name" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                Họ và tên <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                wire:model.defer="name"
                required
                class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                placeholder="Nhập họ và tên"
            />
            @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input 
                type="email" 
                id="email" 
                wire:model.defer="email"
                required
                class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                placeholder="your@email.com"
            />
            @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
    </div>
    <div>
        <label for="subject" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
            Chủ đề <span class="text-red-500">*</span>
        </label>
        <input 
            type="text" 
            id="subject" 
            wire:model.blur="subject"
            oninput="updateWordCount('subject', this.value, 'subjectWordCount')"
            required
            class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
            placeholder="Tiêu đề tin nhắn"
        />
        @error('subject')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        <p class="mt-1 text-xs text-neutral-500">
            @php
                $subjectText = $subject ? strip_tags($subject) : '';
                $subjectWords = $subjectText ? count(preg_split('/\s+/', trim($subjectText), -1, PREG_SPLIT_NO_EMPTY)) : 0;
            @endphp
            <span id="subjectWordCount" class="{{ $subjectWords > 500 ? 'text-red-500 font-semibold' : '' }}">{{ $subjectWords }}/500 từ</span>
        </p>
    </div>
    <div>
        <label for="message" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
            Nội dung <span class="text-red-500">*</span>
        </label>
        <textarea 
            id="message" 
            rows="6" 
            wire:model.blur="message"
            oninput="updateWordCount('message', this.value, 'messageWordCount')"
            required
            class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none" 
            placeholder="Viết tin nhắn của bạn ở đây..."
        ></textarea>
        @error('message')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        <p class="mt-1 text-xs text-neutral-500">
            @php
                $messageText = $message ? strip_tags($message) : '';
                $messageWords = $messageText ? count(preg_split('/\s+/', trim($messageText), -1, PREG_SPLIT_NO_EMPTY)) : 0;
            @endphp
            <span id="messageWordCount" class="{{ $messageWords > 500 ? 'text-red-500 font-semibold' : '' }}">{{ $messageWords }}/500 từ</span>
        </p>
    </div>
    
    <button 
        type="submit"
        wire:loading.attr="disabled"
        wire:target="submitForm"
        class="w-full px-6 py-4 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold hover:from-green-700 hover:to-emerald-700 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
    >
        <span wire:loading.remove wire:target="submitForm">Gửi tin nhắn</span>
        <span wire:loading wire:target="submitForm" class="flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Đang gửi...
        </span>
    </button>
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Hàm đếm từ real-time
    function updateWordCount(fieldId, value, countElementId) {
        const text = value.trim();
        const words = text ? text.split(/\s+/).filter(word => word.length > 0) : [];
        const wordCount = words.length;
        const maxWords = 500;
        
        const countElement = document.getElementById(countElementId);
        if (countElement) {
            countElement.textContent = `${wordCount}/${maxWords} từ`;
            if (wordCount > maxWords) {
                countElement.className = 'text-red-500 font-semibold';
            } else {
                countElement.className = '';
            }
        }
    }

    document.addEventListener('livewire:init', () => {
        // Thông báo thành công sau khi gửi
        Livewire.on('contactFormSubmitted', () => {
            Swal.fire({
                icon: 'success',
                title: 'Gửi thành công!',
                text: 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất có thể.',
                confirmButtonText: 'Đóng',
                confirmButtonColor: '#10b981',
                timer: 5000,
                timerProgressBar: true
            });
        });
    });
</script>
@endpush