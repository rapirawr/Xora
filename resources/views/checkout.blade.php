@extends('layouts.app')

@section('content')
    
    <div class="checkout-container">
        <div class="checkout-header">
            <h1 class="neon-text">Checkout</h1>
            <p class="neon-subtext">Complete your purchase</p>
        </div>
        
        @if($cart && $cart->items->count() > 0)
        <div class="checkout-content">
            <div class="checkout-form-section">
                <div class="dashboard-card">
                    <h3>Shipping Information</h3>
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="shipping_address">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" rows="4"
                                      placeholder="Enter your complete shipping address"
                                      required>{{ old('shipping_address') }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_method">Payment Method</label>
                            <select name="payment_method" id="payment_method" required>
                                <option value="">Select Payment Method</option>
                                <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>
                                    Cash on Delivery (COD)
                                </option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                    Bank Transfer
                                </option>
                            </select>
                        </div>

                        <div class="checkout-summary">
                            <h4>Order Summary</h4>
                            <div class="summary-item">
                                <span>Total Items:</span>
                                <span>{{ $cart->total_quantity }}</span>
                            </div>
                            <div class="summary-item total">
                                <span><strong>Total Price:</strong></span>
                                <span><strong>Rp{{ number_format($cart->total, 0, ',', '.') }}</strong></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-custom btn-large">
                            Complete Order
                        </button>
                    </form>
                </div>
            </div>

            <div class="checkout-items-section">
                <div class="dashboard-card">
                    <h3>Order Items</h3>
                    <div class="checkout-items">
                        @foreach($cart->items as $item)
                            <div class="checkout-item">
                                <div class="item-image">
                                    @if($item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                    @else
                                    <div class="no-image">No Image</div>
                                    @endif
                                </div>
                                <div class="item-details">
                                    <h4>{{ $item->product->name }}</h4>
                                    <p class="item-price">Rp{{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    <p class="item-quantity">Quantity: {{ $item->quantity }}</p>
                                    <p class="item-seller">Seller: {{ $item->product->user->name }}</p>
                                </div>
                                <div class="item-subtotal">
                                    <p>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="dashboard-card">
            <p>Your cart is empty. <a href="{{ route('store') }}">Continue shopping</a></p>
        </div>
    @endif
</div>
@endsection

<style>
body {
    background-color: #000;
}

.checkout-container {
    margin: 0 auto;
    padding: 20px;
    margin-top: 145px;
    background: #000;
    color: #fff;
    min-height: 100vh;
}

.checkout-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-top: 30px;
}

.checkout-form-section,
.checkout-items-section {
    min-height: 400px;
}

.checkout-items {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.checkout-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    border: 1px solid #444;
    border-radius: 8px;
    background: #222;
    transition: all 0.3s ease;
}

.checkout-item:hover {
    background: #333;
    border-color: #666;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    background: #444;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ccc;
    font-size: 12px;
}

.item-details {
    flex: 1;
}

.item-details h4 {
    margin: 0 0 5px 0;
    font-size: 16px;
    color: #fff;
    font-weight: 600;
}

.item-price,
.item-quantity,
.item-seller {
    margin: 2px 0;
    font-size: 14px;
    color: #ccc;
}

.item-subtotal {
    display: flex;
    align-items: center;
    font-weight: bold;
    color: #fff;
}

.checkout-summary {
    margin: 20px 0;
    padding: 20px;
    background: #222;
    border-radius: 8px;
    border: 1px solid #444;
}

.checkout-summary h4 {
    margin: 0 0 15px 0;
    color: #fff;
    font-size: 18px;
    font-weight: 600;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding: 8px 0;
    color: #ccc;
}

.summary-item.total {
    border-top: 1px solid #555;
    padding-top: 15px;
    font-size: 18px;
    color: #fff;
    font-weight: 600;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #fff;
    font-size: 14px;
}

.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px;
    border: 1px solid #555;
    border-radius: 6px;
    font-size: 14px;
    background: #333;
    color: #fff;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #888;
    box-shadow: 0 0 0 2px rgba(136, 136, 136, 0.25);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.btn-custom {
    background: linear-gradient(135deg, #fff 0%, #ccc 100%);
    color: #000;
    border: none;
    padding: 15px 30px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-custom:hover {
    background: linear-gradient(135deg, #ccc 0%, #888 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.15);
}

.btn-custom:active {
    transform: translateY(0);
}

.btn-large {
    width: 100%;
    padding: 15px;
    font-size: 16px;
    margin-top: 20px;
}

.dashboard-card {
    background: rgba(34, 34, 34, 0.95);
    border-radius: 12px;
    padding: 25px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 2px 10px rgba(255, 255, 255, 0.1);
}

.dashboard-card h3 {
    margin: 0 0 20px 0;
    color: #fff;
    font-size: 20px;
    font-weight: 600;
    border-bottom: 2px solid #555;
    padding-bottom: 10px;
}

.checkout-header h1,
.checkout-header p {
    color: #fff;
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .checkout-container {
        padding: 15px;
    }

    .dashboard-card {
        padding: 20px;
    }

    .checkout-item {
        flex-direction: column;
        gap: 10px;
    }

    .item-image {
        align-self: center;
    }
}

/* Additional responsive improvements */
@media (max-width: 480px) {
    .checkout-container {
        padding: 10px;
    }

    .checkout-summary {
        padding: 15px;
    }

    .summary-item {
        flex-direction: column;
        gap: 5px;
        align-items: flex-start;
    }

    .btn-custom {
        padding: 12px 20px;
        font-size: 14px;
    }
}
</style>
