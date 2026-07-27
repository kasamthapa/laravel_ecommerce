<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /**
     * @var array<int, string>
     */
    public const STATUSES = [
        'pending',
        'payment_pending',
        'confirmed',
        'shipped',
        'delivered',
        'cancelled',
        'payment_failed',
    ];

    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'subtotal',
        'shipping_total',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'khalti_pidx',
        'khalti_transaction_id',
        'khalti_amount',
        'paid_at',
        'notes',
    ];

    protected $attributes = [
        'status' => 'pending',
        'payment_method' => 'khalti',
        'payment_status' => 'unpaid',
        'shipping_total' => 0,
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_total' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
