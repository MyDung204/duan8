<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Tạo tài khoản')" :description="__('Nhập thông tin của bạn bên dưới để tạo tài khoản')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Họ và tên')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Nhập họ và tên')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Địa chỉ email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@gmail.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Mật khẩu')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Vui lòng nhập mật khẩu')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Xác nhận lại mật khẩu')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Xác nhận lại mật khẩu')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Tạo tài khoản') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Bạn đã có tài khoản?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Đăng nhập') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
