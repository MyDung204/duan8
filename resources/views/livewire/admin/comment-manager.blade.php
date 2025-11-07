<div>
    <div class="container mx-auto px-4 sm:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Quản lý Bình luận</h1>
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $comments->total() }} bình luận</span>
        </div>


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
                            {{-- Nút phê duyệt - luôn hiển thị --}}
                            <button type="button"
                                    class="btn-approve-comment flex items-center justify-center w-10 h-10 rounded-full transition
                                        @if($comment->status == 'approved')
                                            bg-green-200 dark:bg-green-700 text-green-700 dark:text-green-200 border-2 border-green-500
                                        @else
                                            bg-green-100 dark:bg-green-800/50 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800
                                        @endif" 
                                    data-comment-id="{{ $comment->id }}"
                                    data-comment-status="{{ $comment->status }}"
                                    title="@if($comment->status == 'approved') Đã phê duyệt - Click để thay đổi @else Phê duyệt @endif">
                                <span class="material-symbols-outlined text-xl">check_circle</span>
                            </button>
                            
                            {{-- Nút từ chối - luôn hiển thị --}}
                            <button type="button"
                                    class="btn-reject-comment flex items-center justify-center w-10 h-10 rounded-full transition
                                        @if($comment->status == 'rejected')
                                            bg-red-200 dark:bg-red-700 text-red-700 dark:text-red-200 border-2 border-red-500
                                        @else
                                            bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600
                                        @endif" 
                                    data-comment-id="{{ $comment->id }}"
                                    data-comment-status="{{ $comment->status }}"
                                    title="@if($comment->status == 'rejected') Đã từ chối - Click để thay đổi @else Từ chối @endif">
                                <span class="material-symbols-outlined text-xl">block</span>
                            </button>
                            
                            {{-- Nút xóa - luôn hiển thị --}}
                            <button type="button"
                                    class="btn-delete-comment flex items-center justify-center w-10 h-10 rounded-full bg-red-100 dark:bg-red-800/50 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 transition" 
                                    data-comment-id="{{ $comment->id }}"
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            // Lắng nghe sự kiện show-success
            Livewire.on('show-success', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: event.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        });

        // Xử lý xóa bình luận với SweetAlert
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete-comment');
            if (!btn) return;
            e.preventDefault();
            const commentId = parseInt(btn.getAttribute('data-comment-id'));
            if (!commentId) return;
            
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                text: 'Bạn sẽ không thể hoàn tác hành động này!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Có, xóa!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteComment', commentId);
                }
            });
        });

        // Xử lý phê duyệt bình luận với SweetAlert
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-approve-comment');
            if (!btn) return;
            e.preventDefault();
            const commentId = parseInt(btn.getAttribute('data-comment-id'));
            const currentStatus = btn.getAttribute('data-comment-status');
            if (!commentId) return;
            
            let title, text, icon, confirmText;
            
            if (currentStatus === 'approved') {
                title = 'Bình luận đã được phê duyệt';
                text = 'Bình luận này đã ở trạng thái "Đã duyệt". Bạn có muốn giữ nguyên trạng thái này?';
                icon = 'info';
                confirmText = 'Giữ nguyên';
            } else if (currentStatus === 'rejected') {
                title = 'Chuyển sang trạng thái "Đã duyệt"?';
                text = 'Bình luận này đang bị từ chối. Bạn có muốn chuyển sang trạng thái "Đã duyệt" để hiển thị công khai?';
                icon = 'question';
                confirmText = 'Có, phê duyệt!';
            } else {
                title = 'Phê duyệt bình luận?';
                text = 'Bình luận này sẽ được hiển thị công khai.';
                icon = 'question';
                confirmText = 'Có, phê duyệt!';
            }
            
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed && currentStatus !== 'approved') {
                    @this.call('approveComment', commentId);
                }
            });
        });

        // Xử lý từ chối bình luận với SweetAlert
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-reject-comment');
            if (!btn) return;
            e.preventDefault();
            const commentId = parseInt(btn.getAttribute('data-comment-id'));
            const currentStatus = btn.getAttribute('data-comment-status');
            if (!commentId) return;
            
            let title, text, icon, confirmText;
            
            if (currentStatus === 'rejected') {
                title = 'Bình luận đã bị từ chối';
                text = 'Bình luận này đã ở trạng thái "Từ chối". Bạn có muốn giữ nguyên trạng thái này?';
                icon = 'info';
                confirmText = 'Giữ nguyên';
            } else if (currentStatus === 'approved') {
                title = 'Chuyển sang trạng thái "Từ chối"?';
                text = 'Bình luận này đang được phê duyệt. Bạn có muốn chuyển sang trạng thái "Từ chối" để ẩn khỏi công khai?';
                icon = 'warning';
                confirmText = 'Có, từ chối!';
            } else {
                title = 'Từ chối bình luận?';
                text = 'Bình luận này sẽ bị từ chối và không hiển thị công khai.';
                icon = 'warning';
                confirmText = 'Có, từ chối!';
            }
            
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed && currentStatus !== 'rejected') {
                    @this.call('rejectComment', commentId);
                }
            });
        });
    </script>
</div>
