<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';

    protected $queryString = ['search' => ['except' => ''], 'roleFilter' => ['except' => '']];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->latest()
            ->paginate(10);

        // Define available roles for the filter dropdown
        $roles = ['admin', 'user', 'vip', 'editor']; // Example roles, adjust as needed

        return view('livewire.admin.user-manager', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.app');
    }
}