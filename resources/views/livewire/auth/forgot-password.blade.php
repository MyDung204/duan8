<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Quên mật khẩu')" :description="__('Nhập email của bạn để nhận liên kết đặt lại mật khẩu')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Địa chỉ email')"
                type="email"
                required
                autofocus
                placeholder="email@gmail.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('Gửi link đặt lại mật khẩu') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('Hoặc quay lại đăng nhập') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Đăng nhập') }}</flux:link>
        </div>
    </div>
</x-layouts.auth>
