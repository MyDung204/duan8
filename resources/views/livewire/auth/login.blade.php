<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Đăng nhập vào tài khoản của bạn')" :description="__('Nhập email và mật khẩu để đăng nhập vào tài khoản của bạn')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Địa chỉ email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@gmail.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Mật khẩu')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Nhập mật khẩu')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Quên mật khẩu?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Lưu mật khẩu')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Đăng nhập') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Bạn không có tài khoản?') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('Đăng ký') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts.auth>
