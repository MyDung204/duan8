<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserShow extends Component
{
    use WithPagination;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $comments = $this->user->comments()->latest()->paginate(10);

        return view('livewire.admin.user-show', [
            'comments' => $comments,
        ])->layout('components.layouts.app');
    }
}