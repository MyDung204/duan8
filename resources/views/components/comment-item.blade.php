@props(['comment', 'postId'])

<div class="flex space-x-4 {{ $comment->parent_id ? 'ml-8 mt-4' : '' }}">
    <div class="flex-shrink-0">
        <div class="h-10 w-10 rounded-full bg-neutral-200 dark:bg-neutral-700 flex items-center justify-center font-bold text-indigo-500">
            {{ $comment->user->initials() ?? 'G' }}
        </div>
    </div>
    <div class="flex-1">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $comment->user->name ?? 'Khách' }}</h4>
                    @if ($comment->user && $comment->user->isAdmin())
                        <span class="px-2 py-0.5 text-xs font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full">Admin</span>
                    @endif
                </div>
                <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
            @auth
                <button wire:click="replyTo({{ $comment->id }})" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                    Trả lời
                </button>
            @endauth
        </div>
        <p class="mt-2 text-neutral-700 dark:text-neutral-300">{{ $comment->content }}</p>
        @if ($comment->status === 'pending')
            <p class="mt-1 text-xs text-amber-600 dark:text-amber-500 italic">Đợi phê duyệt</p>
        @elseif ($comment->status === 'rejected')
            <p class="mt-1 text-xs text-red-600 dark:text-red-500 italic">Bị từ chối</p>
        @endif

        @if ($comment->replies->isNotEmpty())
            <div class="mt-4 space-y-4">
                @foreach ($comment->replies as $reply)
                    <x-comment-item :comment="$reply" :post-id="$postId" />
                @endforeach
            </div>
        @endif
    </div>
</div>
