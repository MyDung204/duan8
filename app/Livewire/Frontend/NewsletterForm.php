<?php

namespace App\Livewire\Frontend;

use App\Models\NewsletterSubscription;
use Livewire\Component;

class NewsletterForm extends Component
{
    public $email = '';
    public $success = false;
    public $error = '';

    protected function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->resetErrorBag();
        $this->success = false;
        $this->error = '';
    }

    public function subscribe()
    {
        $this->validateOnly('email'); // Validate only the email field

        return redirect()->route('contact', ['email' => $this->email]);
    }

    public function render()
    {
        return view('livewire.frontend.newsletter-form');
    }
}