<?php

use App\Models\User;
use App\Actions\Fortify\PasswordValidationRules;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

new class extends Component
{
    use PasswordValidationRules;

    public ?User $user = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = '';

    public $availableRoles = [
        'user' => 'Người dùng',
        'vip' => 'VIP',
        'editor' => 'Biên tập',
        'admin' => 'Quản trị viên',
    ];

    public function mount($user = null)
    {
        // Reset tất cả các trường về rỗng khi thêm mới
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = '';
        $this->user = null;
        
        if ($user) {
            // Nếu $user là ID (số), tìm User
            if (is_numeric($user)) {
                $this->user = User::findOrFail($user);
            } 
            // Nếu $user là User object (route model binding)
            elseif ($user instanceof User) {
                $this->user = $user;
            }
            
            if ($this->user) {
                $this->name = $this->user->name;
                $this->email = $this->user->email;
                $this->role = $this->user->role;
            }
        }
    }

    protected function rules()
    {
        $passwordRules = $this->user 
            ? ['nullable', 'string', 'min:8', Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 'confirmed']
            : array_merge(['required'], $this->passwordRules());

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    // Kiểm tra phần trước @ chỉ chứa chữ cái, số, dấu chấm, dấu gạch dưới
                    $emailParts = explode('@', $value);
                    if (count($emailParts) !== 2) {
                        $fail('Email không hợp lệ.');
                        return;
                    }
                    
                    $localPart = $emailParts[0];
                    $domain = strtolower($emailParts[1]);
                    
                    // Kiểm tra phần trước @ không chứa ký tự đặc biệt ngoài . và _
                    if (!preg_match('/^[a-zA-Z0-9._]+$/', $localPart)) {
                        $fail('Email chỉ được chứa chữ cái, số, dấu chấm (.) và dấu gạch dưới (_) ở phần trước @.');
                        return;
                    }
                    
                    // Chỉ cho phép các domain gmail hợp lệ
                    $allowedDomains = ['gmail.com', 'gmail.co.uk', 'gmail.fr', 'gmail.de', 'gmail.it', 'gmail.es', 'gmail.jp', 'gmail.com.vn'];
                    if (!in_array($domain, $allowedDomains)) {
                        $fail('Email phải có đuôi gmail hợp lệ (ví dụ: @gmail.com).');
                        return;
                    }
                },
                Rule::unique('users')->ignore($this->user ? $this->user->id : null),
            ],
            'password' => $passwordRules,
            'role' => ['required', Rule::in(array_keys($this->availableRoles))],
        ];
    }

    protected function messages()
    {
        return [
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.mixed' => 'Mật khẩu phải chứa cả chữ in hoa và chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt (!@#$%^&*()_+-=[]{}|;:,.<>?).',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->user) {
            $this->user->update($data);
            session()->flash('show_toast_message', [
                'text' => 'Người dùng đã được cập nhật thành công!',
                'icon' => 'success'
            ]);
        } else {
            User::create($data);
            session()->flash('show_toast_message', [
                'text' => 'Người dùng đã được tạo thành công!',
                'icon' => 'success'
            ]);
        }

        return $this->redirect(route('admin.users.index'), navigate: true);
    }
}; ?>

<div>
    <div class="container mx-auto px-4 sm:px-8 py-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $this->user ? 'Chỉnh sửa Người dùng' : 'Thêm Người dùng mới' }}</h1>
            <a href="{{ route('admin.users.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition shadow-md">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                Quay lại
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
            <form wire:submit="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" id="name" autocomplete="off" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                        @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" wire:model.blur="email" id="email" autocomplete="off" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                        @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <div id="email-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Mật khẩu
                            @if(!$this->user)
                                <span class="text-red-500">*</span>
                            @else
                                <span class="text-gray-500 text-xs">(Để trống nếu không đổi)</span>
                            @endif
                        </label>
                        <input type="password" wire:model.blur="password" id="password" autocomplete="new-password" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                        @error('password') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <div id="password-error" class="text-red-500 text-sm mt-1 hidden"></div>
                        @if(!$this->user)
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
                        @endif
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Xác nhận Mật khẩu <span class="text-red-500">*</span>
                        </label>
                        <input type="password" wire:model.blur="password_confirmation" id="password_confirmation" autocomplete="new-password" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                        @error('password_confirmation') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        <div id="password-confirmation-error" class="text-red-500 text-sm mt-1 hidden"></div>
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vai trò <span class="text-red-500">*</span></label>
                        <select wire:model="role" id="role" class="w-full px-4 py-2.5 rounded-lg border-2 border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 shadow-sm">
                            <option value="">Chọn vai trò...</option>
                            @foreach ($this->availableRoles as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('role') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                        {{ $this->user ? 'Cập nhật người dùng' : 'Tạo người dùng' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(!$this->user)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const requirements = document.querySelectorAll('.password-req');
            
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('email-error');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const passwordConfirmationError = document.getElementById('password-confirmation-error');
            
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
            
            if (passwordInput && requirements.length > 0) {
                const requirementsBox = document.getElementById('password-requirements');
                const passwordError = document.getElementById('password-error');
                
                // Xử lý khi Livewire cập nhật
                Livewire.hook('morph.updated', () => {
                    setTimeout(() => {
                        const newPasswordInput = document.getElementById('password');
                        const newPasswordConfirmationInput = document.getElementById('password_confirmation');
                        if (newPasswordInput && newPasswordInput.value.length > 0) {
                            checkPasswordRequirements(newPasswordInput.value);
                        }
                        // Kiểm tra lại xác nhận mật khẩu
                        if (newPasswordConfirmationInput && newPasswordConfirmationInput.value.length > 0 && newPasswordInput) {
                            validatePasswordConfirmation(newPasswordInput.value, newPasswordConfirmationInput.value);
                        }
                    }, 100);
                });

                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    if (password.length > 0) {
                        checkPasswordRequirements(password);
                        if (passwordError) {
                            passwordError.classList.add('hidden');
                        }
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
                    const isNewUser = {{ $this->user ? 'false' : 'true' }};
                    if (isNewUser && (!password || password.length === 0)) {
                        if (passwordError) {
                            passwordError.textContent = 'Mật khẩu là bắt buộc.';
                            passwordError.classList.remove('hidden');
                        }
                    } else if (password.length > 0) {
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
                    const isNewUser = {{ $this->user ? 'false' : 'true' }};
                    
                    if (isNewUser && (!confirmation || confirmation.length === 0)) {
                        passwordConfirmationError.textContent = 'Xác nhận mật khẩu là bắt buộc.';
                        passwordConfirmationError.classList.remove('hidden');
                    } else if (confirmation.length > 0 || password.length > 0) {
                        validatePasswordConfirmation(password, confirmation);
                    }
                });
            }
            
            function validatePasswordConfirmation(password, confirmation) {
                if (!confirmation || confirmation.length === 0) {
                    if (passwordConfirmationError) {
                        passwordConfirmationError.textContent = 'Xác nhận mật khẩu là bắt buộc.';
                        passwordConfirmationError.classList.remove('hidden');
                    }
                    return false;
                }
                
                if (password !== confirmation) {
                    if (passwordConfirmationError) {
                        passwordConfirmationError.textContent = 'Xác nhận mật khẩu không khớp với mật khẩu.';
                        passwordConfirmationError.classList.remove('hidden');
                    }
                    return false;
                }
                
                // Mật khẩu khớp
                if (passwordConfirmationError) {
                    passwordConfirmationError.classList.add('hidden');
                }
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
            }

            function validateEmail(email) {
                if (!email || email.length === 0) {
                    if (emailError) {
                        emailError.textContent = 'Email là bắt buộc.';
                        emailError.classList.remove('hidden');
                    }
                    return false;
                }
                
                // Kiểm tra format email cơ bản
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    if (emailError) {
                        emailError.textContent = 'Email không hợp lệ.';
                        emailError.classList.remove('hidden');
                    }
                    return false;
                }
                
                // Kiểm tra phần trước @
                const emailParts = email.split('@');
                if (emailParts.length !== 2) {
                    if (emailError) {
                        emailError.textContent = 'Email không hợp lệ.';
                        emailError.classList.remove('hidden');
                    }
                    return false;
                }
                
                const localPart = emailParts[0];
                const domain = emailParts[1].toLowerCase();
                
                // Kiểm tra phần trước @ chỉ chứa chữ cái, số, dấu chấm, dấu gạch dưới
                if (!/^[a-zA-Z0-9._]+$/.test(localPart)) {
                    if (emailError) {
                        emailError.textContent = 'Email chỉ được chứa chữ cái, số, dấu chấm (.) và dấu gạch dưới (_) ở phần trước @.';
                        emailError.classList.remove('hidden');
                    }
                    return false;
                }
                
                // Chỉ cho phép các domain gmail hợp lệ
                const allowedDomains = ['gmail.com', 'gmail.co.uk', 'gmail.fr', 'gmail.de', 'gmail.it', 'gmail.es', 'gmail.jp', 'gmail.com.vn'];
                if (!allowedDomains.includes(domain)) {
                    if (emailError) {
                        emailError.textContent = 'Email phải có đuôi gmail hợp lệ (ví dụ: @gmail.com).';
                        emailError.classList.remove('hidden');
                    }
                    return false;
                }
                
                // Email hợp lệ
                if (emailError) {
                    emailError.classList.add('hidden');
                }
                return true;
            }
        });
    </script>
    @endif
</div>
