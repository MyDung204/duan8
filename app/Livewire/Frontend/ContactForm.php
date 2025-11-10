<?php

namespace App\Livewire\Frontend;

use App\Mail\ContactFormMail;
use App\Models\Contact;
use App\Rules\MaxWords;
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
            'email' => ['required', 'email', 'max:255', function ($attribute, $value, $fail) {
                // Kiểm tra định dạng email chặt chẽ hơn
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $fail('Email không đúng định dạng.');
                }
            }],
            'subject' => ['required', 'string', 'min:5', new MaxWords(500)],
            'message' => ['required', 'string', 'min:10', new MaxWords(500)],
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.string' => 'Họ và tên phải là chuỗi ký tự.',
            'name.min' => 'Họ và tên phải có ít nhất 3 ký tự.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'subject.required' => 'Vui lòng nhập chủ đề.',
            'subject.string' => 'Chủ đề phải là chuỗi ký tự.',
            'subject.min' => 'Chủ đề phải có ít nhất 5 ký tự.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.string' => 'Nội dung phải là chuỗi ký tự.',
            'message.min' => 'Nội dung phải có ít nhất 10 ký tự.',
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
            // Create and save contact to database
            $contact = Contact::create([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ]);

            // Send email with Contact model instance
            Mail::to(config('mail.from.address'))->send(new ContactFormMail($contact));

            $this->success = true;
            $this->reset();
            
            // Dispatch event to show SweetAlert
            $this->dispatch('contactFormSubmitted');

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