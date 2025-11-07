<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
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
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ], [
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.letters' => 'Mật khẩu phải chứa ít nhất một chữ cái.',
            'password.mixed' => 'Mật khẩu phải chứa cả chữ in hoa và chữ thường.',
            'password.numbers' => 'Mật khẩu phải chứa ít nhất một số.',
            'password.symbols' => 'Mật khẩu phải chứa ít nhất một ký tự đặc biệt (!@#$%^&*()_+-=[]{}|;:,.<>?).',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => 'user',
        ]);
    }
}
