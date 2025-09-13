<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign('cart_items_cart_id_foreign');
            $table->dropForeign('cart_items_product_id_foreign');

            // Drop the old unique constraint on cart_id and product_id
            $table->dropUnique('cart_items_cart_id_product_id_unique');

            // Add new unique constraint on cart_id, product_id, and variant_id
            $table->unique(['cart_id', 'product_id', 'variant_id'], 'cart_items_cart_id_product_id_variant_id_unique');

            // Recreate foreign keys
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('cart_items_cart_id_product_id_variant_id_unique');

            // Drop foreign keys first
            $table->dropForeign('cart_items_cart_id_foreign');
            $table->dropForeign('cart_items_product_id_foreign');

            // Re-add the old unique constraint on cart_id and product_id
            $table->unique(['cart_id', 'product_id'], 'cart_items_cart_id_product_id_unique');

            // Recreate foreign keys
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
