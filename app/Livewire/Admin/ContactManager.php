<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Contact;
use Livewire\WithPagination;

class ContactManager extends Component
{
    use WithPagination;

    public function deleteContact($id)
    {
        Contact::destroy($id);
        session()->flash('message', 'Contact message deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.contact-manager', [
            'contacts' => Contact::latest()->paginate(10),
        ]);
    }
}
