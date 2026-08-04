<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ChatWidget extends Component
{
    public bool $open = false;

    public string $screen = 'menu';

    public ?string $topic = null;

    #[Validate('required|string|max:120')]
    public string $name = '';

    #[Validate('required|email|max:160')]
    public string $email = '';

    #[Validate('required|string|max:1000')]
    public string $message = '';

    public bool $sent = false;

    public function mount(): void
    {
        if (auth()->check()) {
            $this->name = auth()->user()->name;
            $this->email = auth()->user()->email;
        }
    }

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    public function showTopic(string $key): void
    {
        $this->topic = $key;
        $this->screen = 'answer';
    }

    public function showContactForm(): void
    {
        $this->screen = 'contact';
        $this->sent = false;
    }

    public function backToMenu(): void
    {
        $this->screen = 'menu';
        $this->topic = null;
    }

    public function send(): void
    {
        $this->validate();

        ChatMessage::create([
            'user_id' => auth()->id(),
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
            'topic' => $this->topic,
        ]);

        $this->message = '';
        $this->sent = true;
    }

    /**
     * @return array<string, array{question: string, answer: string}>
     */
    public function faqs(): array
    {
        return [
            'shipping' => [
                'question' => 'Shipping & delivery',
                'answer' => "Orders ship within Kathmandu Valley in 1\u{2013}2 business days and elsewhere in Nepal within 3\u{2013}5 business days. Delivery is free on orders over Rs. 10,000, and Rs. 250 flat otherwise.",
            ],
            'returns' => [
                'question' => 'Returns & exchanges',
                'answer' => 'Frames can be returned or exchanged within 14 days of delivery if unworn and in original packaging. Contact us with your order number to start a return.',
            ],
            'payment' => [
                'question' => 'Payment methods',
                'answer' => 'We accept Khalti digital wallet payments. Your order is confirmed automatically once Khalti verifies the payment.',
            ],
            'track' => [
                'question' => 'Track my order',
                'answer' => 'Use your order number and the email you checked out with on our order tracking page to see live status.',
            ],
        ];
    }

    public function render(): View
    {
        return view('livewire.chat-widget', [
            'faqs' => $this->faqs(),
        ]);
    }
}
