<form wire:submit.prevent="submitForm" class="space-y-6">
    @if (session('success'))
        <div class="p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-semibold text-green-800 dark:text-green-300">Gửi thành công!</p>
                <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
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
            wire:model.defer="subject"
            required
            class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
            placeholder="Tiêu đề tin nhắn"
        />
        @error('subject')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="message" class="block text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-2">
            Nội dung <span class="text-red-500">*</span>
        </label>
        <textarea 
            id="message" 
            rows="6" 
            wire:model.defer="message"
            required
            class="w-full px-4 py-3 rounded-lg bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition resize-none" 
            placeholder="Viết tin nhắn của bạn ở đây..."
        ></textarea>
        @error('message')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        <p class="mt-1 text-xs text-neutral-500">{{ strlen($message) }} ký tự</p>
    </div>
    
    <button 
        type="submit" 
        class="w-full px-6 py-4 rounded-lg bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold hover:from-green-700 hover:to-emerald-700 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
    >
        <span>Gửi tin nhắn</span>
    </button>
</form>