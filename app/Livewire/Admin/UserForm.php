<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserForm extends Component
{
    public ?User $user = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'user';

    public $availableRoles = [
        'user' => 'Người dùng',
        'vip' => 'VIP',
        'editor' => 'Biên tập',
        'admin' => 'Quản trị viên',
    ];

    public function mount(?User $user = null)
    {
        if ($user) {
            $this->user = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user ? $this->user->id : null),
            ],
            'password' => $this->user ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(array_keys($this->availableRoles))],
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
            session()->flash('message', 'Người dùng đã được cập nhật thành công.');
        } else {
            User::create($data);
            session()->flash('message', 'Người dùng đã được tạo thành công.');
        }

        return $this->redirect(route('admin.users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.user-form', [
            'availableRoles' => $this->availableRoles,
        ])->layout('components.layouts.app');
    }
}