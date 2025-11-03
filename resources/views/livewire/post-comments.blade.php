<div class="mt-12 pt-8 border-t border-neutral-200 dark:border-neutral-800">
    <h2 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">Bình luận ({{ $post->comments()->approved()->count() }})</h2>

    @if (session()->has('comment_message'))
        <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl relative mb-4" role="alert">
            <span class="block sm:inline font-medium">{{ session('comment_message') }}</span>
        </div>
    @endif

    @if (session()->has('comment_error'))
        <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-400 px-4 py-3 rounded-xl relative mb-4" role="alert">
            <span class="block sm:inline font-medium">{{ session('comment_error') }}</span>
        </div>
    @endif

    @auth
        <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200/80 dark:border-neutral-800 p-6 mb-8 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div>
                    <h3 class="text-lg font-bold text-neutral-900 dark:text-white">
                        @if($replyToId)
                            Trả lời bình luận
                        @else
                            Viết bình luận của bạn
                        @endif
                    </h3>
                    @if($replyToId)
                        <button wire:click="cancelReply" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline mt-1">Hủy trả lời</button>
                    @endif
                </div>
            </div>
            <form wire:submit.prevent="postComment">
                <textarea 
                    wire:model="newCommentText" 
                    id="comment-input" 
                    rows="5" 
                    class="w-full px-5 py-4 rounded-xl border-2 border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-neutral-900 dark:text-neutral-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none" 
                    placeholder="Chia sẻ suy nghĩ của bạn..."></textarea>
                @error('newCommentText') 
                    <span class="text-red-500 dark:text-red-400 text-sm mt-2 block">{{ $message }}</span> 
                @enderror
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Tối thiểu 3 ký tự</p>
                    <button 
                        type="submit" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800 transition shadow-lg hover:shadow-xl disabled:opacity-50">
                        <span wire:loading.remove>Gửi bình luận</span>
                        <span wire:loading>Sending...</span>
                        <svg wire:loading.remove class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-2xl border border-indigo-200 dark:border-indigo-800 p-6 mb-8 text-center">
            <p class="text-neutral-700 dark:text-neutral-300 mb-3">Bạn cần đăng nhập để bình luận</p>
            <a href="{{ route('login') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition font-bold shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Đăng nhập ngay
            </a>
        </div>
    @endauth

    <div class="space-y-6">
        @forelse ($comments as $comment)
            <x-comment-item :comment="$comment" :post-id="$post->id" />
        @empty
            <p class="text-neutral-600 dark:text-neutral-400">Chưa có bình luận nào. Hãy là người đầu tiên!</p>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $comments->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('focusCommentInput', () => {
            document.getElementById('comment-input').focus();
        });
    });
</script>
@endpush