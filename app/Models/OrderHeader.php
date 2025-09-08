<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_id',
        'total_price',
        'status',
        'purchased_at',
        'shipping_address',
        'payment_method',
        'tracking_number',
        'order_number',
    ];

    /**
     * Boot method to auto-generate tracking number and order number on creating
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderHeader) {
            if (empty($orderHeader->tracking_number)) {
                $orderHeader->tracking_number = self::generateTrackingNumber();
            }
            if (empty($orderHeader->order_number)) {
                $orderHeader->order_number = self::generateOrderNumber();
            }
        });
    }

    /**
     * Generate a random tracking number like 'A1J30NDB'
     */
    public static function generateTrackingNumber()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $length = 8;
        $trackingNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $trackingNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $trackingNumber;
    }

    /**
     * Generate a random order number like '12345678'
     */
    public static function generateOrderNumber()
    {
        $length = 8;
        $orderNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $orderNumber .= rand(0, 9);
        }
        return $orderNumber;
    }

    protected $casts = [
        'total_price' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    /**
     * Get the buyer that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the seller for the order.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the order items for the order header.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the total quantity of items in this order.
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->orders->sum('quantity');
    }

    /**
     * Check if the order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Check if the order can be shipped.
     */
    public function canBeShipped(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if the order can be delivered.
     */
    public function canBeDelivered(): bool
    {
        return $this->status === 'shipped';
    }
}
