<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class AddToCartForm extends Component
{
    public Product $product;

    public ?string $size = null;

    public ?string $color = null;

    public int $quantity = 1;

    /**
     * 'provided' | 'later' | null (unselected). Only relevant when the
     * product requires a prescription — null forces an explicit choice
     * rather than silently defaulting to one path or the other.
     */
    public ?string $prescriptionStatus = null;

    public ?string $sphRight = null;

    public ?string $sphLeft = null;

    public ?string $cylRight = null;

    public ?string $cylLeft = null;

    public ?string $axisRight = null;

    public ?string $axisLeft = null;

    public ?string $pd = null;

    public bool $added = false;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->size = $product->sizes[0] ?? null;
        $this->color = $product->colors[0] ?? null;
    }

    /**
     * @return array<string, string|array<int, mixed>>
     */
    protected function rules(): array
    {
        $rules = [
            'size' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:30',
            'quantity' => 'required|integer|min:1|max:10',
        ];

        if (! $this->product->requires_prescription) {
            return $rules;
        }

        $rules['prescriptionStatus'] = 'required|in:provided,later';

        if ($this->prescriptionStatus !== 'provided') {
            return $rules;
        }

        return [
            ...$rules,
            'sphRight' => 'required|numeric|between:-20,20',
            'sphLeft' => 'required|numeric|between:-20,20',
            'cylRight' => 'nullable|numeric|between:-10,10',
            'cylLeft' => 'nullable|numeric|between:-10,10',
            'axisRight' => 'nullable|required_with:cylRight|integer|between:0,180',
            'axisLeft' => 'nullable|required_with:cylLeft|integer|between:0,180',
            'pd' => 'required|numeric|between:50,75',
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'prescriptionStatus.required' => 'Let us know whether you have your prescription yet.',
            'sphRight.required' => 'Enter the SPH value for the right eye.',
            'sphLeft.required' => 'Enter the SPH value for the left eye.',
            'axisRight.required_with' => 'Axis (right eye) is required whenever a CYL value is entered.',
            'axisLeft.required_with' => 'Axis (left eye) is required whenever a CYL value is entered.',
            'pd.required' => 'Enter your pupillary distance (PD).',
            'pd.between' => 'PD is usually between 50 and 75mm — double check the value.',
        ];
    }

    #[On('sticky-add-to-cart')]
    public function addToCart(): void
    {
        $this->product->refresh();

        if ($this->product->stock < 1) {
            $this->addError('quantity', 'This frame just sold out.');

            return;
        }

        $this->validate();

        $items = session('cart.items', []);
        $key = implode(':', [$this->product->id, $this->size ?: 'any-size', $this->color ?: 'any-color']);

        $requestedQuantity = ($items[$key]['quantity'] ?? 0) + $this->quantity;

        $items[$key] = [
            'product_id' => $this->product->id,
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'image_url' => $this->product->image_url,
            'price' => (float) $this->product->price,
            'size' => $this->size,
            'color' => $this->color,
            'quantity' => min($requestedQuantity, $this->product->stock, 10),
            'prescription' => $this->buildPrescription(),
        ];

        session(['cart.items' => $items]);

        $this->added = true;
        $this->dispatch('cart-updated');
    }

    /**
     * @return array<string, mixed>|null
     */
    private function buildPrescription(): ?array
    {
        if (! $this->product->requires_prescription) {
            return null;
        }

        if ($this->prescriptionStatus === 'later') {
            return ['status' => 'later'];
        }

        return [
            'status' => 'provided',
            'sph_right' => (float) $this->sphRight,
            'sph_left' => (float) $this->sphLeft,
            'cyl_right' => $this->cylRight !== null && $this->cylRight !== '' ? (float) $this->cylRight : null,
            'cyl_left' => $this->cylLeft !== null && $this->cylLeft !== '' ? (float) $this->cylLeft : null,
            'axis_right' => $this->axisRight !== null && $this->axisRight !== '' ? (int) $this->axisRight : null,
            'axis_left' => $this->axisLeft !== null && $this->axisLeft !== '' ? (int) $this->axisLeft : null,
            'pd' => (float) $this->pd,
        ];
    }

    public function render(): View
    {
        return view('livewire.add-to-cart-form');
    }
}
