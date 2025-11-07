<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Xác nhận mật khẩu')"
            :description="__('Đây là khu vực bảo mật của ứng dụng. Vui lòng xác nhận mật khẩu của bạn trước khi tiếp tục.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Mật khẩu')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Nhập mật khẩu')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('Xác nhận') }}
            </flux:button>
        </form>
    </div>
</x-layouts.auth>
