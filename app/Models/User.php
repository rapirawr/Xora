<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'record',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the user's wishlist items.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the user's wishlist products.
     */
    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlists');
    }

    /**
     * Get the user's cart.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Get or create the user's cart.
     */
    public function getOrCreateCart()
    {
        return $this->cart()->firstOrCreate();
    }

    /**
     * Get the products for the user.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the ratings for the user.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Check if user has purchased a specific product.
     */
    public function hasPurchased(Product $product)
    {
        return $this->orders()->where('product_id', $product->id)->where('status', 'completed')->exists();
    }

    /**
     * Check if user has rated a specific product.
     */
    public function hasRated(Product $product)
    {
        return $this->ratings()->where('product_id', $product->id)->exists();
    }

    /**
     * Get the user's store.
     */
    public function store()
    {
        return $this->hasOne(Store::class);
    }

    /**
     * Check if user is a seller.
     */
    public function isSeller()
    {
        return $this->role === 'seller';
    }

    /**
     * Check if user has a store.
     */
    public function hasStore()
    {
        return $this->store()->exists();
    }

    /**
     * Get the profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo ? asset('storage/' . $this->profile_photo) : asset('images/default-avatar.svg');
    }
}
