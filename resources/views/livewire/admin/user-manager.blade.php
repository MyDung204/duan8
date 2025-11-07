<div>
    <div class="container mx-auto px-4 sm:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Quản lý Người dùng</h1>
            <a href="{{ route('admin.users.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-md">
                <span class="material-symbols-outlined text-lg">add</span>
                Thêm người dùng mới
            </a>
        </div>


        <div class="mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="w-full md:w-1/3">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm kiếm theo tên hoặc email..." class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="w-full md:w-1/4">
                <select wire:model.live="roleFilter" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tất cả vai trò</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Người dùng</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Email</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Vai trò</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10">
                                            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-indigo-500">
                                                {{ $user->initials() }}
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap">{{ $user->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight 
                                        @if($user->role == 'admin') text-red-900 bg-red-200 dark:text-red-100 dark:bg-red-800/50
                                        @elseif($user->role == 'vip') text-purple-900 bg-purple-200 dark:text-purple-100 dark:bg-purple-800/50
                                        @elseif($user->role == 'editor') text-blue-900 bg-blue-200 dark:text-blue-100 dark:bg-blue-800/50
                                        @else text-green-900 bg-green-200 dark:text-green-100 dark:bg-green-800/50 @endif rounded-full">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    <p class="text-gray-900 dark:text-gray-100 whitespace-no-wrap">{{ $user->created_at->format('d/m/Y') }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-sm">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-800/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 transition" 
                                           title="Xem chi tiết">
                                            <span class="material-symbols-outlined text-xl">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                           class="flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 dark:bg-yellow-800/50 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-800 transition" 
                                           title="Chỉnh sửa">
                                            <span class="material-symbols-outlined text-xl">edit</span>
                                        </a>
                                        @if($user->role !== 'admin')
                                            <button type="button"
                                                    class="btn-delete-user flex items-center justify-center w-10 h-10 rounded-full bg-red-100 dark:bg-red-800/50 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800 transition" 
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    title="Xóa">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        @else
                                            <button type="button"
                                                    disabled
                                                    class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800/50 text-gray-400 dark:text-gray-600 cursor-not-allowed transition" 
                                                    title="Không thể xóa tài khoản quản trị viên">
                                                <span class="material-symbols-outlined text-xl">lock</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-5 border-b border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500 dark:text-gray-400">Không tìm thấy người dùng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-5 bg-white dark:bg-gray-800 border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $users->links() }}
            </div>
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
            
            // Lắng nghe sự kiện show-error
            Livewire.on('show-error', (event) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: event.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        });

        // Xử lý xóa người dùng với SweetAlert
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete-user');
            if (!btn) return;
            e.preventDefault();
            const userId = parseInt(btn.getAttribute('data-user-id'));
            const userName = btn.getAttribute('data-user-name');
            if (!userId) return;
            
            Swal.fire({
                title: 'Bạn có chắc chắn?',
                html: `Bạn chuẩn bị xóa người dùng <strong>${userName}</strong>.<br>Bạn sẽ không thể hoàn tác hành động này!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Có, xóa!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteUser', userId);
                }
            });
        });
    </script>
</div>