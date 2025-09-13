@extends('layouts.app')
<title> {{ config("app.name") }} - {{ $seller->name }}</title>
@section('content')
<div class="store-container">
    <main class="store-main">
        <div class="store-header">
            <div class="header-content" style="text-align: left;">
                <div class="seller-info">
                    <img src="{{ $seller->profile_photo_url }}" alt="{{ $seller->name }}'s Profile Photo" class="seller-profile-photo">
                    <div class="seller-details">
                        <h1 class="neon-text">{{ $seller->name }}'s Store</h1>
                        <p class="neon-subtext"></p> {{ $seller->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($products->isEmpty())
        <div class="no-products">
            <div class="empty-state">
                <i class="fas fa-box-open empty-icon"></i>
                <h3>No Products Found</h3>
                <p>This seller has not listed any products yet.</p>
                <a href="{{ route('store') }}" class="btn-primary">
                    <i class="fas fa-th-large"></i>
                    View All Stores
                </a>
            </div>
        </div>
        @else
        <div class="product-grid">
            @foreach($products as $product)
            <a href="{{ route('product.show', $product->id) }}" class="product-card-link">
                <div class="product-card animated-card">
                    <div class="product-image-container">
                        <div class="product-image" style="background-image: url('{{ $product->image_url }}');">
                            <div class="product-overlay">
                                <div class="product-badges">
                                    @if($product->stock > 0)
                                        <span class="badge in-stock">In Stock</span>
                                    @else
                                        <span class="badge out-of-stock">Out of Stock</span>
                                    @endif

                                    @if($product->category)
                                        <span class="badge category">{{ ucfirst($product->category) }}</span>
                                    @endif
                                </div>
                                <div class="quick-view">
                                    <i class="fas fa-eye"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="product-info">
                        <div class="product-header">
                            <h3 class="product-title">{{ $product->name }}</h3>
                        </div>

                        <p class="product-description">{{ Str::limit($product->description, 80) }}</p>

                        <div class="product-price-section">
                            <span class="price">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>

                        <div class="product-meta">
                            <div class="meta-item">
                                <i class="fas fa-shopping-cart"></i>
                                <span>{{ $product->sold }} sold</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-boxes"></i>
                                <span>{{ $product->stock }} left</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </main>
</div>
@endsection
