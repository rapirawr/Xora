<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'logo',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the user that owns the store.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the store.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the store logo URL.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/store-default-logo.png');
    }

    /**
     * Check if the store is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
