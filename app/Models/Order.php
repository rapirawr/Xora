<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'total_price',
        'status',
        'purchased_at',
        'order_header_id',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }


    public function orderHeader(): BelongsTo
    {
        return $this->belongsTo(OrderHeader::class);
    }
}
