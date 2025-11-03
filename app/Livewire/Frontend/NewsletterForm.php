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
            'email' => 'required|email|unique:newsletter_subscriptions,email',
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
        $this->validate();
        $this->success = false;
        $this->error = '';

        try {
            NewsletterSubscription::create(['email' => $this->email]);
            $this->success = true;
            $this->reset('email');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $this->error = 'Địa chỉ email này đã được đăng ký.';
            } else {
                $this->error = 'Đã có lỗi xảy ra. Vui lòng thử lại.';
            }
        }
    }

    public function render()
    {
        return view('livewire.frontend.newsletter-form');
    }
}