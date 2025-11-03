<?php

namespace App\Livewire\Frontend;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    public function mount()
    {
        if (request()->has('email')) {
            $this->email = request()->query('email');
        }
    }

    public $success = false;
    public $error = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|min:5|max:255',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submitForm()
    {
        $this->validate();
        $this->success = false;
        $this->error = '';

        try {
            $formData = [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ];

            Mail::to(config('mail.from.address'))->send(new ContactFormMail($formData));

            $this->success = true;
            $this->reset();

        } catch (\Exception $e) {
            Log::error('Contact form submission failed: ' . $e->getMessage());
            $this->error = 'Có lỗi xảy ra khi gửi tin nhắn. Vui lòng thử lại sau.';
        }
    }

    public function render()
    {
        return view('livewire.frontend.contact-form');
    }
}