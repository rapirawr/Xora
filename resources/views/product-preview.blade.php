@extends('layouts.app')

@section('content')
<div class="product-page-container">
    <div class="product-grid-container">
        <div class="product-image-gallery">
            <div class="main-image-container">
                <img class="main-product-image" src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </div>
        </div>

        <div class="product-details-container">
            <h1 class="product-title">{{ $product->name }}</h1>
            @if($product->description)
            <div class="product-description">
                <p>{{ $product->description }}</p>
            </div>
            @endif
            <div class="product-seller">
                <span>Seller: {{ $product->user->name ?? 'Unknown' }}</span>
            </div>
            <div class="product-rating">
                <span class="sold-count">{{ $product->sold }} Terjual</span>
            </div>

            <div class="product-price">
                <span class="price-value">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
            </div>

            <div class="product-stock">
                <span class="info-label">Stok:</span>
                <span class="stock-value">{{ $product->stock }}</span>
            </div>

            <div class="product-quantity">
                <span class="info-label">Kuantitas</span>
                <div class="quantity-selector">
                    <label for="quantity">Quantity:</label>
                    <div class="quantity-controls">
                        <button type="button" class="qty-btn" onclick="changeQuantity(-1)">-</button>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="qty-input">
                        <button type="button" class="qty-btn" onclick="changeQuantity(1)">+</button>
                    </div>
                </div>
            </div>

            <div class="product-actions">
                @auth
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="quantity" id="cart-quantity" value="1">
                        <button type="submit" class="add-to-cart-btn">
                            <i class="fas fa-cart-plus"></i> Masukkan Keranjang
                        </button>
                    </form>
                @endauth
            </div>



        </div>
    </div>
</div>
@endsection

<div id="dynamic-notification" class="dynamic-notification hide" >
    <span class="notification-text"></span>
</div>

<script>



function changeQuantity(amount) {
    const qtyInput = document.getElementById('quantity');
    let newQty = parseInt(qtyInput.value) + amount;
    if (newQty < 1) {
        newQty = 1;
    }
    qtyInput.value = newQty;

    document.getElementById('cart-quantity').value = newQty;
}

function showSuccessNotification(message) {
    const notification = document.getElementById('dynamic-notification');
    const notificationText = notification.querySelector('.notification-text');
    notificationText.textContent = message;

    notification.classList.remove('error-notification');
    notification.classList.remove('hide');
    notification.classList.add('show');

    // Auto-hide setelah 4 detik
    setTimeout(() => {
        notification.classList.remove('show');
        notification.classList.add('hide');
    }, 4000);
}

// Fungsi untuk menampilkan notifikasi error
function showErrorNotification(message) {
    let errorNotification = document.getElementById('dynamic-notification');

    errorNotification.classList.remove('show');
    errorNotification.classList.add('hide');

    setTimeout(() => {
        errorNotification.querySelector('.notification-text').textContent = message;
        errorNotification.classList.remove('hide');
        errorNotification.classList.add('show');
    }, 400);

    setTimeout(() => {
        errorNotification.classList.remove('show');
        errorNotification.classList.add('hide');
    }, 4000);
}

// Fungsi untuk memperbarui jumlah keranjang
function updateCartCount() {
    // Fungsi ini bisa diperluas untuk memperbarui jumlah item di keranjang pada navbar
    console.log('Keranjang diperbarui - Anda dapat mengimplementasikan pembaruan hitungan keranjang di sini');
}

// Perbaikan utama: Menggunakan Fetch API untuk mencegah reload halaman
document.addEventListener('DOMContentLoaded', () => {
    const addToCartForm = document.querySelector('.add-to-cart-form');

    if (addToCartForm) {
        addToCartForm.addEventListener('submit', async (event) => {
            // Prevent default form submission and page reload
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const actionUrl = form.action;

            try {
                const response = await fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData,
                });

                const result = await response.json();

                if (response.ok) {
                    showSuccessNotification(result.message || 'Produk berhasil ditambahkan ke keranjang!');
                    updateCartCount();
                } else {
                    showErrorNotification(result.message || 'Gagal menambahkan produk ke keranjang.');
                }
            } catch (error) {
                console.error('Error:', error);
                showErrorNotification('Terjadi kesalahan saat menambahkan produk. Silakan coba lagi.');
            }
        });
    }
});
</script>

<style>
.ratings-reviews-section {
    margin-top: 30px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.ratings-reviews-section h3 {
    margin-bottom: 20px;
    color: #333;
    font-size: 1.2em;
}

.single-rating-review {
    margin-bottom: 20px;
    padding: 15px;
    background-color: white;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.rating-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.rating-header strong {
    color: #007bff;
}

.rating-stars {
    color: #ffc107;
    font-size: 1.2em;
}

.rating-header small {
    color: #666;
    font-size: 0.9em;
}

.rating-review p {
    margin: 0;
    color: #555;
    line-height: 1.5;
}

/* Order history ratings styles */
.product-ratings-section {
    margin-top: 10px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
    font-size: 0.9em;
}

.product-ratings-section h5 {
    margin-bottom: 10px;
    color: #333;
    font-size: 1em;
}

.single-rating {
    margin-bottom: 8px;
    padding: 8px;
    background-color: white;
    border-radius: 4px;
    border-left: 3px solid #007bff;
}

.single-rating strong {
    color: #007bff;
}

.single-rating span {
    color: #ffc107;
    margin: 0 5px;
}

.single-rating p {
    margin: 5px 0 0 0;
    color: #555;
    font-style: italic;
}

.no-ratings-section {
    margin-top: 30px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
    text-align: center;
    color: #666;
    font-style: italic;
}
</style>
