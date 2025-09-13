<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'sold',
        'stock',
        'user_id',
        'category',
        'has_variants',
    ];

    /**
     * Available product categories
     *
     * @var array
     */
    public static $categories = [
        'electronics' => 'Electronics',
        'clothing' => 'Clothing',
        'books' => 'Books',
        'home' => 'Home & Garden',
        'sports' => 'Sports',
        'other' => 'Other',
    ];

    /**
     * Get the available categories as options for forms
     *
     * @return array
     */
    public static function getCategoryOptions()
    {
        return self::$categories;
    }

    /**
     * Get the display name for a category
     *
     * @param string $category
     * @return string
     */
    public static function getCategoryDisplayName($category)
    {
        return self::$categories[$category] ?? ucfirst($category);
    }

    /**
     * Get the user that owns the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the orders for the product.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }



    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the variants for the product.
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Check if a user has purchased this product.
     */
    public function hasBeenPurchasedBy(User $user)
    {
        return $this->orders()->where('user_id', $user->id)->where('status', 'delivered')->exists();
    }



    /**
     * Get the wishlist entries for this product.
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Check if a user has this product in their wishlist.
     */
    public function isInWishlist(User $user)
    {
        return $this->wishlists()->where('user_id', $user->id)->exists();
    }
}
