@extends('layouts.app')

@section('content')
<div class="cart-container">
    <h1 class="cart-title">Shopping Cart</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($cart->items->count() > 0)
        <div class="cart-content">
            <div class="cart-items">
                @foreach($cart->items as $item)
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                        </div>
                        <div class="item-details">
                            <h3 class="item-name">{{ $item->product->name }}</h3>
                            @if($item->variant)
                                <p class="item-variant">
                                    <span class="variant-label">Varian:</span>
                                    <span class="variant-value">{{ $item->variant->variant_name }}: {{ $item->variant->variant_value }}</span>
                                </p>
                            @endif
                            <p class="item-price">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                            <p class="item-seller">Seller: {{ $item->product->user->name ?? 'Unknown' }}</p>
                        </div>
                        <div class="item-quantity">
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="quantity-form">
                                @csrf
                                @method('PATCH')
                                <div class="quantity-controls">
                                    <button type="button" class="qty-btn" onclick="changeQuantity(this, -1)">-</button>
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" class="qty-input">
                                    <button type="button" class="qty-btn" onclick="changeQuantity(this, 1)">+</button>
                                </div>
                                <button type="submit" class="update-btn">Update</button>
                            </form>
                        </div>
                        <div class="item-subtotal">
                            <p class="subtotal-price">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="item-actions">
                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="remove-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn" onclick="return confirm('Remove this item from cart?')">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary">
                <div class="summary-content">
                    <h3>Cart Summary</h3>
                    <div class="summary-row">
                        <span>Total Items:</span>
                        <span>{{ $cart->total_quantity }}</span>
                    </div>
                    <div class="summary-row total-row">
                        <span>Total Price:</span>
                        <span>Rp{{ number_format($cart->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-actions">
                        <form action="{{ route('cart.clear') }}" method="POST" class="clear-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="clear-btn" onclick="return confirm('Clear all items from cart?')">Clear Cart</button>
                        </form>
                        <a href="{{ route('checkout.show') }}" class="checkout-btn">Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="empty-cart">
            <h2>Your cart is empty</h2>
            <p>Add some products to your cart to get started!</p>
            <a href="{{ route('store') }}" class="continue-shopping-btn">Continue Shopping</a>
        </div>
    @endif
</div>
@endsection

<script>
function changeQuantity(button, change) {
    const input = button.parentElement.querySelector('.qty-input');
    const currentValue = parseInt(input.value);
    const newValue = currentValue + change;
    const max = parseInt(input.getAttribute('max'));
    const min = parseInt(input.getAttribute('min'));

    if (newValue >= min && newValue <= max) {
        input.value = newValue;
    }
}
</script>
        {{-- @endsection --}}
<style>
    .quantity-controls {
    display: grid;
    grid-template-columns: auto auto auto;
    background-color: #333;
    border-radius: 20px;
    padding: 5px;
    }
</style>