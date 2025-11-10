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
            <div class="flex flex-col sm:flex-row gap-3 p-2 rounded-xl bg-white/60 dark:bg-white/10 backdrop-blur-sm shadow-md">
                <input wire:model.lazy="email" type="email" placeholder="Nhập email của bạn..." required 
                       class="flex-1 w-full min-w-[180px] px-4 py-3 rounded-lg border-0 bg-transparent focus:ring-2 focus:ring-primary-500 transition text-sm">
                
                <button type="submit" 
                        wire:loading.attr="disabled"
                        wire:target="subscribe"
                        class="flex-shrink-0 px-6 py-3 rounded-lg bg-neutral-900 dark:bg-white text-white dark:text-black font-bold hover:bg-black dark:hover:bg-neutral-200 transition-colors disabled:opacity-50 whitespace-nowrap text-sm">
                    <span wire:loading.remove wire:target="subscribe">Đăng ký</span>
                    <span wire:loading wire:target="subscribe">Đang xử lý...</span>
                </button>
            </div>
        @endif

        @error('email') 
            <p class="text-red-500 text-sm mt-2 text-left">{{ $message }}</p> 
        @enderror
    </form>
</div>
