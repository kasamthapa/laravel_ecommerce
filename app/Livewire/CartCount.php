<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCount extends Component
{
    public int $count = 0;

    public function mount(?int $count = null): void
    {
        $this->count = $count ?? $this->currentCount();
    }

    #[On('cart-updated')]
    public function refresh(): void
    {
        $this->count = $this->currentCount();
    }

    private function currentCount(): int
    {
        return (int) collect(session('cart.items', []))->sum('quantity');
    }

    public function render(): View
    {
        return view('livewire.cart-count');
    }
}
