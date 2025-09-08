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
        Schema::create('order_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Buyer
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade'); // Seller
            $table->decimal('total_price', 15, 2);
            $table->string('status')->default('pending'); // pending, processing, shipped, delivered, cancelled
            $table->timestamp('purchased_at')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('payment_method')->default('cod'); // cod, bank_transfer, etc.
            $table->string('tracking_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_headers');
    }
};
