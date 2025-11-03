<div>
    <div class="container mx-auto px-4 sm:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Chi tiết Người dùng: {{ $user->name }}</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition shadow-md">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Chỉnh sửa
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition shadow-md">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Quay lại
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Tên:</p>
                    <p class="text-lg text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Email:</p>
                    <p class="text-lg text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Vai trò:</p>
                    <p class="text-lg text-gray-900 dark:text-gray-100">{{ ucfirst($user->role) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Ngày tạo:</p>
                    <p class="text-lg text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Bình luận của người dùng</h2>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nội dung</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Bài viết</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ngày gửi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $comment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap">{{ Str::limit($comment->content, 70) }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    <a href="{{ route('posts.show.public', $comment->post->slug) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline" target="_blank">{{ Str::limit($comment->post->title, 40) }}</a>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    @if ($comment->status == 'approved')
                                        <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                            <span class="relative">Đã duyệt</span>
                                        </span>
                                    @elseif ($comment->status == 'pending')
                                        <span class="relative inline-block px-3 py-1 font-semibold text-amber-900 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-amber-200 opacity-50 rounded-full"></span>
                                            <span class="relative">Chờ duyệt</span>
                                        </span>
                                    @elseif ($comment->status == 'rejected')
                                        <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
                                            <span aria-hidden="true" class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
                                            <span class="relative">Đã từ chối</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap">{{ $comment->created_at->format('d/m/Y H:i') }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">Người dùng này chưa có bình luận nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-5 bg-white dark:bg-gray-800 border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</div>