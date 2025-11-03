<div>
    <div class="container mx-auto px-4 sm:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Quản lý Bình luận</h1>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $comments->total() }} bình luận</span>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-6" role="alert">
                <p class="font-bold">Thành công</p>
                <p>{{ session('message') }}</p>
            </div>
        @endif

        <div class="grid gap-6">
            @forelse ($comments as $comment)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-indigo-500 text-xl">
                                        {{ $comment->user->initials() ?? 'G' }}
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <h3 class="font-bold text-gray-900 dark:text-white">{{ $comment->user->name ?? 'Khách' }}</h3>
                                        @if ($comment->user && $comment->user->isAdmin())
                                            <span class="px-2 py-0.5 text-xs font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full">Admin</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $comment->created_at->format('d/m/Y H:i') }} &bull; trong bài viết 
                                        <a href="{{ route('posts.show.public', $comment->post->slug) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline" target="_blank">{{ Str::limit($comment->post->title, 40) }}</a>
                                    </p>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if ($comment->status == 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full text-green-900 bg-green-100 dark:text-green-100 dark:bg-green-800/50">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        Đã duyệt
                                    </span>
                                @elseif ($comment->status == 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full text-amber-900 bg-amber-100 dark:text-amber-100 dark:bg-amber-800/50">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                        Chờ duyệt
                                    </span>
                                @elseif ($comment->status == 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium rounded-full text-red-900 bg-red-100 dark:text-red-100 dark:bg-red-800/50">
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                        Đã từ chối
                                    </span>
                                @endif
                            </div>
                        </div>

                        <p class="text-gray-700 dark:text-gray-300 mt-4 pl-16">{{ $comment->content }}</p>

                        <div class="flex items-center justify-end gap-2 mt-4 pl-16">
                            @if ($comment->status == 'pending')
                                <button wire:click="approveComment({{ $comment->id }})" 
                                        class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 dark:bg-green-800/50 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800 transition" 
                                        title="Phê duyệt">
                                    <span class="material-symbols-outlined text-xl">check_circle</span>
                                </button>
                                <button wire:click="rejectComment({{ $comment->id }})" 
                                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition" 
                                        title="Từ chối">
                                    <span class="material-symbols-outlined text-xl">block</span>
                                </button>
                            @endif
                            <button wire:click="deleteComment({{ $comment->id }})" 
                                    onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')" 
                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 dark:bg-red-800/50 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 transition" 
                                    title="Xóa vĩnh viễn">
                                <span class="material-symbols-outlined text-xl">delete</span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700">
                    <span class="material-symbols-outlined text-5xl text-gray-400">sms</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Không có bình luận nào</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hiện tại chưa có bình luận nào để quản lý.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $comments->links() }}
        </div>
    </div>
</div>
