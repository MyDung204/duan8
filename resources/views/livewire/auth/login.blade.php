<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Đăng nhập vào tài khoản của bạn')" :description="__('Nhập email và mật khẩu để đăng nhập vào tài khoản của bạn')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <div>
                <flux:input
                    name="email"
                    id="email"
                    :label="__('Địa chỉ email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@gmail.com"
                />
                <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    id="password"
                    :label="__('Mật khẩu')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Nhập mật khẩu')"
                    viewable
                />
                <div id="password-error" class="text-red-500 text-sm mt-1 hidden"></div>

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
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-300">
                <span>{{ __('Bạn không có tài khoản?') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('Đăng ký') }}</flux:link>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('password-error');
            
            // Validation email khi blur
            if (emailInput && emailError) {
                emailInput.addEventListener('blur', function() {
                    validateEmail(this.value);
                });
                
                emailInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        emailError.classList.add('hidden');
                    }
                });
            }
            
            // Validation password khi blur
            if (passwordInput && passwordError) {
                passwordInput.addEventListener('blur', function() {
                    validatePassword(this.value);
                });
                
                passwordInput.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        passwordError.classList.add('hidden');
                    }
                });
            }

            function validateEmail(email) {
                if (!email || email.length === 0) {
                    emailError.textContent = 'Email là bắt buộc.';
                    emailError.classList.remove('hidden');
                    return false;
                }
                
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    emailError.textContent = 'Email không hợp lệ.';
                    emailError.classList.remove('hidden');
                    return false;
                }
                
                emailError.classList.add('hidden');
                return true;
            }

            function validatePassword(password) {
                if (!password || password.length === 0) {
                    passwordError.textContent = 'Mật khẩu là bắt buộc.';
                    passwordError.classList.remove('hidden');
                    return false;
                }
                
                passwordError.classList.add('hidden');
                return true;
            }
        });
    </script>
</x-layouts.auth>
