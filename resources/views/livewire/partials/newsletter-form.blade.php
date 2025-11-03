<div>
    <form wire:submit.prevent="subscribe" class="w-full max-w-lg mx-auto">
        <div class="flex flex-col sm:flex-row gap-4 p-2 rounded-xl bg-white/60 dark:bg-white/10 backdrop-blur-sm shadow-md">
            <input 
                type="email" 
                wire:model.lazy="email"
                placeholder="Nhập email của bạn..." 
                required 
                class="w-full px-5 py-3 rounded-lg border-0 bg-transparent focus:ring-2 @error('email') focus:ring-red-500 @else focus:ring-primary-500 @enderror transition">
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="px-8 py-3 rounded-lg bg-neutral-900 dark:bg-white text-white dark:text-black font-bold hover:bg-black dark:hover:bg-neutral-200 transition-colors disabled:opacity-70">
                <span wire:loading.remove>Đăng ký</span>
                <span wire:loading>Đang xử lý...</span>
            </button>
        </div>
    </form>
    <div class="text-center mt-4 h-5">
        @if($success)
            <p class="text-green-600 dark:text-green-400 text-sm font-medium">Cảm ơn bạn đã đăng ký!</p>
        @endif
        @if($error)
            <p class="text-red-500 dark:text-red-400 text-sm font-medium">{{ $error }}</p>
        @endif
        @error('email')
            <p class="text-red-500 dark:text-red-400 text-sm font-medium">{{ $message }}</p>
        @enderror
    </div>
</div>