<div>
    <form wire:submit.prevent="subscribe" class="w-full">
        @if ($success)
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                Cảm ơn bạn đã đăng ký! Chúng tôi sẽ sớm gửi cho bạn những thông tin hữu ích.
            </div>
        @endif

        @if ($error)
             <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                {{ $error }}
            </div>
        @endif

        @if (!$success)
            <div class="relative p-2 rounded-xl bg-white/60 dark:bg-white/10 backdrop-blur-sm shadow-md">
                <input wire:model.lazy="email" type="email" placeholder="Nhập email của bạn..." required 
                       class="w-full px-4 pr-24 py-3 rounded-lg border-0 bg-transparent focus:ring-2 focus:ring-primary-500 transition text-sm">
                
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="subscribe"
                        class="absolute right-3 top-1/2 -translate-y-1/2 px-5 py-2 rounded-lg bg-neutral-900 dark:bg-white text-white dark:text-black font-bold hover:bg-black dark:hover:bg-neutral-200 transition-colors disabled:opacity-50 whitespace-nowrap text-sm">
                    <span wire:loading.remove wire:target="subscribe">Đăng ký</span>
                    <span wire:loading wire:target="subscribe" class="flex items-center gap-1">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Đang xử lý...</span>
                    </span>
                </button>
            </div>
        @endif

        @error('email') 
            <p class="text-red-500 text-sm mt-2 text-left">{{ $message }}</p> 
        @enderror
    </form>
</div>
