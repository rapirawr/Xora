@extends('layouts.app')

<title>{{ config("app.name") }} - {{ $product->name }}</title>


@section('content')
<div class="product-page-container">
    <div class="product-grid-container">
        <div class="product-image-gallery">
            <div class="main-image-container">
                <img class="main-product-image" id="main-image" src="{{ $product->image_url }}" alt="{{ $product->name }}">
            </div>
            @if($product->images && $product->images->count() > 0)
            <div class="image-thumbnails">
                <div class="thumbnail-container">
                    <img class="thumbnail active" src="{{ $product->image_url }}" alt="Main Image" onclick="changeMainImage(this.src)">
                    @foreach($product->images as $image)
                        <img class="thumbnail" src="{{ $image->image_url }}" alt="Product Image" onclick="changeMainImage(this.src)">
                    @endforeach
                </div>
            </div>
            @endif
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

            <!-- Product Variants -->
            @if($product->has_variants && $product->variants && $product->variants->count() > 0)
            <div class="product-variants">
                <h3 class="variants-title">Pilih Varian:</h3>
                <div class="variants-list">
                    @foreach($product->variants as $variant)
                    <div class="variant-option" data-variant-id="{{ $variant->id }}" data-price="{{ $variant->price ?: $product->price }}" data-stock="{{ $variant->stock ?: $product->stock }}" data-image="{{ $variant->image_url ?: $product->image_url }}">
                        <div class="variant-name">{{ $variant->variant_name }}: {{ $variant->variant_value }}</div>
                        @if($variant->price)
                        <div class="variant-price">Rp{{ number_format($variant->price, 0, ',', '.') }}</div>
                        @endif
                        @if($variant->stock)
                        <div class="variant-stock">Stok: {{ $variant->stock }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="product-price">
                <span class="price-value" id="current-price">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
            </div>

            <div class="product-stock">
                <span class="info-label">Stok:</span>
                <span class="stock-value" id="current-stock">{{ $product->stock }}</span>
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
                        <input type="hidden" name="variant_id" id="selected-variant-id" value="">
                        <button type="submit" class="add-to-cart-btn">
                            <i class="fas fa-cart-plus"></i> Masukkan Keranjang
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</div>

<style>
.product-grid-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    max-width: 1000px;
    margin: 0 auto;
    padding: 140px;
}

.product-image-gallery {
    max-width: 100%;
    max-height: 500px;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.2);
}

.main-image-container img.main-product-image {
    width: 100%;
    height: auto;
    object-fit: contain;
    border-radius: 12px;
    transition: opacity 0.3s ease;
}

.image-thumbnails {
    margin-top: 15px;
}

.thumbnail-container {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding: 5px 0;
}

.thumbnail {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.thumbnail:hover {
    border-color: #007bff;
    transform: scale(1.05);
}

.thumbnail.active {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
}

.product-details-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
    color: #fff;
}

.product-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 10px;
}

.product-description p {
    font-size: 1rem;
    line-height: 1.5;
    color: #ccc;
}

.product-seller,
.product-rating,
.product-price,
.product-stock,
.product-quantity,
.product-actions {
    font-size: 1.1rem;
}

/* Variants Styles */
.product-variants {
    margin: 20px 0;
    padding: 15px;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.variants-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #fff;
    margin-bottom: 12px;
}

.variants-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.variant-option {
    padding: 12px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.variant-option:hover {
    background: rgba(255, 255, 255, 0.08);
    border-color: #007bff;
}

.variant-option.selected {
    background: rgba(0, 123, 255, 0.1);
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
}

.variant-name {
    font-weight: 600;
    color: #fff;
    margin-bottom: 4px;
}

.variant-price,
.variant-stock {
    font-size: 0.9rem;
    color: #ccc;
}

.product-quantity .quantity-selector {
    margin-top: 5px;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 10px;
}

.qty-btn {
    background-color: #222;
    border: none;
    color: #fff;
    font-size: 1.2rem;
    padding: 5px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.qty-btn:hover {
    background-color: #444;
}

.qty-input {
    width: 60px;
    text-align: center;
    font-size: 1rem;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #444;
    background-color: #111;
    color: #fff;
}

.add-to-cart-btn {
    background: black;
    border: 1px solid white;
    color: white;
    padding: 12px 20px;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background 0.3s ease;
    display: flex;
    align-items: center;
    gap: 10px;
}

.add-to-cart-btn:hover {
    background: white;
    color: black;
    border: 1px solid black;
}

@media (max-width: 768px) {
    .product-grid-container {
        grid-template-columns: 1fr;
        padding: 10px;
    }

    .product-image-gallery {
        max-height: 300px;
    }

    .thumbnail-container {
        gap: 8px;
    }

    .thumbnail {
        width: 50px;
        height: 50px;
    }

    .variants-list {
        gap: 6px;
    }

    .variant-option {
        padding: 10px;
    }

    .product-title {
        font-size: 1.5rem;
    }

    .product-description p {
        font-size: 0.9rem;
    }

    .product-seller,
    .product-rating,
    .product-price,
    .product-stock,
    .product-quantity,
    .product-actions {
        font-size: 1rem;
    }

    .qty-input {
        width: 50px;
    }
}
</style>
@endsection

<div id="dynamic-notification" class="dynamic-notification hide" >
    <span class="notification-text"></span>
</div>

<script>

// Function to change main image when thumbnail is clicked
function changeMainImage(imageSrc) {
    const mainImage = document.getElementById('main-image');
    const thumbnails = document.querySelectorAll('.thumbnail');

    // Update main image
    mainImage.src = imageSrc;

    // Update active thumbnail
    thumbnails.forEach(thumbnail => {
        thumbnail.classList.remove('active');
        if (thumbnail.src === imageSrc) {
            thumbnail.classList.add('active');
        }
    });
}

// Function to handle variant selection
function selectVariant(variantElement) {
    const variantOptions = document.querySelectorAll('.variant-option');
    const variantId = variantElement.dataset.variantId;
    const variantPrice = variantElement.dataset.price;
    const variantStock = variantElement.dataset.stock;
    const variantImage = variantElement.dataset.image;

    // Remove selected class from all variants
    variantOptions.forEach(option => {
        option.classList.remove('selected');
    });

    // Add selected class to clicked variant
    variantElement.classList.add('selected');

    // Update price display
    const priceElement = document.getElementById('current-price');
    priceElement.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(variantPrice);

    // Update stock display
    const stockElement = document.getElementById('current-stock');
    stockElement.textContent = variantStock;

    // Update quantity input max value
    const qtyInput = document.getElementById('quantity');
    qtyInput.max = variantStock;
    qtyInput.value = Math.min(qtyInput.value, variantStock);

    // Update cart quantity
    document.getElementById('cart-quantity').value = qtyInput.value;

    // Update selected variant ID
    document.getElementById('selected-variant-id').value = variantId;

    // Change main image if variant has different image
    if (variantImage) {
        changeMainImage(variantImage);
    }
}

function changeQuantity(amount) {
    const qtyInput = document.getElementById('quantity');
    let newQty = parseInt(qtyInput.value) + amount;
    if (newQty < 1) {
        newQty = 1;
    }
    if (newQty > parseInt(qtyInput.max)) {
        newQty = parseInt(qtyInput.max);
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
    // Add event listeners for variant options
    const variantOptions = document.querySelectorAll('.variant-option');
    variantOptions.forEach(option => {
        option.addEventListener('click', () => {
            selectVariant(option);
        });
    });

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
