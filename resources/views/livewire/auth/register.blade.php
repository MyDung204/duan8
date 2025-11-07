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
            <div>
                <flux:input
                    name="email"
                    id="email"
                    :label="__('Địa chỉ email')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="email@gmail.com"
                />
                <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <!-- Password -->
            <div>
                <flux:input
                    name="password"
                    id="password"
                    :label="__('Mật khẩu')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Vui lòng nhập mật khẩu')"
                    viewable
                />
                <!-- Password Requirements - Chỉ hiển thị khi người dùng bắt đầu nhập -->
                <div id="password-requirements" class="mt-3 p-3 bg-neutral-900 rounded-lg border border-neutral-700 hidden">
                    <p class="text-xs font-semibold text-zinc-300 mb-2">Yêu cầu mật khẩu còn thiếu:</p>
                    <div id="password-requirements-list" class="space-y-1.5 text-sm">
                        <div class="password-req flex items-center gap-2 hidden" data-rule="length">
                            <span class="text-red-500 font-bold">✗</span>
                            <span class="text-zinc-300">Mật khẩu phải có ít nhất 8 ký tự</span>
                        </div>
                        <div class="password-req flex items-center gap-2 hidden" data-rule="uppercase">
                            <span class="text-red-500 font-bold">✗</span>
                            <span class="text-zinc-300">Phải có ít nhất một chữ cái in hoa (A-Z)</span>
                        </div>
                        <div class="password-req flex items-center gap-2 hidden" data-rule="lowercase">
                            <span class="text-red-500 font-bold">✗</span>
                            <span class="text-zinc-300">Phải có ít nhất một chữ cái thường (a-z)</span>
                        </div>
                        <div class="password-req flex items-center gap-2 hidden" data-rule="number">
                            <span class="text-red-500 font-bold">✗</span>
                            <span class="text-zinc-300">Phải có ít nhất một số (0-9)</span>
                        </div>
                        <div class="password-req flex items-center gap-2 hidden" data-rule="special">
                            <span class="text-red-500 font-bold">✗</span>
                            <span class="text-zinc-300">Phải có ít nhất một ký tự đặc biệt (!@#$%^&*()_+-=[]{}|;:,.<>?)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <flux:input
                    name="password_confirmation"
                    id="password_confirmation"
                    :label="__('Xác nhận lại mật khẩu')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Xác nhận lại mật khẩu')"
                    viewable
                />
                <div id="password-confirmation-error" class="text-red-500 text-sm mt-1 hidden"></div>
            </div>

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Tạo tài khoản') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-300">
            <span>{{ __('Bạn đã có tài khoản?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Đăng nhập') }}</flux:link>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const passwordConfirmationError = document.getElementById('password-confirmation-error');
            const requirements = document.querySelectorAll('.password-req');
            const requirementsBox = document.getElementById('password-requirements');
            
            // Validation email khi blur
            if (emailInput && emailError) {
                emailInput.addEventListener('blur', function() {
                    validateEmail(this.value);
                });
                
                emailInput.addEventListener('input', function() {
                    // Ẩn lỗi khi người dùng bắt đầu nhập lại
                    if (this.value.length > 0) {
                        emailError.classList.add('hidden');
                    }
                });
            }
            
            // Validation password
            if (passwordInput && requirements.length > 0) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    
                    if (password.length > 0) {
                        checkPasswordRequirements(password);
                        // Kiểm tra lại xác nhận mật khẩu nếu đã nhập
                        if (passwordConfirmationInput && passwordConfirmationInput.value.length > 0) {
                            validatePasswordConfirmation(password, passwordConfirmationInput.value);
                        }
                    } else {
                        // Ẩn box khi mật khẩu rỗng
                        if (requirementsBox) {
                            requirementsBox.classList.add('hidden');
                        }
                    }
                });
                
                passwordInput.addEventListener('blur', function() {
                    const password = this.value;
                    if (password.length > 0) {
                        checkPasswordRequirements(password);
                    }
                });
            }
            
            // Validation xác nhận mật khẩu
            if (passwordConfirmationInput && passwordConfirmationError) {
                passwordConfirmationInput.addEventListener('input', function() {
                    const password = passwordInput ? passwordInput.value : '';
                    const confirmation = this.value;
                    
                    if (confirmation.length > 0) {
                        validatePasswordConfirmation(password, confirmation);
                    } else {
                        passwordConfirmationError.classList.add('hidden');
                    }
                });
                
                passwordConfirmationInput.addEventListener('blur', function() {
                    const password = passwordInput ? passwordInput.value : '';
                    const confirmation = this.value;
                    
                    if (confirmation.length > 0 || password.length > 0) {
                        validatePasswordConfirmation(password, confirmation);
                    }
                });
            }
            
            function validatePasswordConfirmation(password, confirmation) {
                if (!confirmation || confirmation.length === 0) {
                    passwordConfirmationError.textContent = 'Xác nhận mật khẩu là bắt buộc.';
                    passwordConfirmationError.classList.remove('hidden');
                    return false;
                }
                
                if (password !== confirmation) {
                    passwordConfirmationError.textContent = 'Xác nhận mật khẩu không khớp với mật khẩu.';
                    passwordConfirmationError.classList.remove('hidden');
                    return false;
                }
                
                // Mật khẩu khớp
                passwordConfirmationError.classList.add('hidden');
                return true;
            }

            function validateEmail(email) {
                if (!email || email.length === 0) {
                    emailError.textContent = 'Email là bắt buộc.';
                    emailError.classList.remove('hidden');
                    return false;
                }
                
                // Kiểm tra format email cơ bản
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    emailError.textContent = 'Email không hợp lệ.';
                    emailError.classList.remove('hidden');
                    return false;
                }
                
                // Kiểm tra phần trước @
                const emailParts = email.split('@');
                if (emailParts.length !== 2) {
                    emailError.textContent = 'Email không hợp lệ.';
                    emailError.classList.remove('hidden');
                    return false;
                }
                
                const localPart = emailParts[0];
                const domain = emailParts[1].toLowerCase();
                
                // Kiểm tra phần trước @ chỉ chứa chữ cái, số, dấu chấm, dấu gạch dưới
                if (!/^[a-zA-Z0-9._]+$/.test(localPart)) {
                    emailError.textContent = 'Email chỉ được chứa chữ cái, số, dấu chấm (.) và dấu gạch dưới (_) ở phần trước @.';
                    emailError.classList.remove('hidden');
                    return false;
                }
                
                // Chỉ cho phép các domain gmail hợp lệ
                const allowedDomains = ['gmail.com', 'gmail.co.uk', 'gmail.fr', 'gmail.de', 'gmail.it', 'gmail.es', 'gmail.jp', 'gmail.com.vn'];
                if (!allowedDomains.includes(domain)) {
                    emailError.textContent = 'Email phải có đuôi gmail hợp lệ (ví dụ: @gmail.com).';
                    emailError.classList.remove('hidden');
                    return false;
                }
                
                // Email hợp lệ
                emailError.classList.add('hidden');
                return true;
            }

            function checkPasswordRequirements(password) {
                // Kiểm tra từng điều kiện
                const checks = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
                };
                
                let hasMissingRequirements = false;
                
                    // Cập nhật UI cho từng điều kiện - chỉ hiển thị các yêu cầu chưa đáp ứng
                    requirements.forEach(req => {
                        const rule = req.getAttribute('data-rule');
                        const isValid = checks[rule];
                        
                        if (isValid) {
                            // Ẩn yêu cầu đã đáp ứng
                            req.classList.add('hidden');
                        } else {
                            // Hiển thị yêu cầu chưa đáp ứng
                            req.classList.remove('hidden');
                            // Đảm bảo text có màu sáng
                            const textSpan = req.querySelector('span.text-zinc-300');
                            if (textSpan) {
                                textSpan.classList.remove('text-red-600', 'text-green-600');
                                textSpan.classList.add('text-zinc-300');
                            }
                            hasMissingRequirements = true;
                        }
                    });
                
                // Hiển thị/ẩn box cảnh báo
                if (requirementsBox) {
                    if (hasMissingRequirements) {
                        requirementsBox.classList.remove('hidden');
                    } else {
                        // Nếu tất cả yêu cầu đã đáp ứng, ẩn box
                        requirementsBox.classList.add('hidden');
                    }
                }
            }
        });
    </script>
</x-layouts.auth>
