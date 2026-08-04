<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * @var array<int, string>
     */
    public const TYPES = ['percent', 'fixed'];

    protected $fillable = [
        'code',
        'type',
        'value',
        'max_uses',
        'used_count',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->expires_at !== null && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    public function discountFor(float $subtotal): float
    {
        return $this->type === 'percent'
            ? round($subtotal * ((float) $this->value / 100), 2)
            : (float) $this->value;
    }
}
