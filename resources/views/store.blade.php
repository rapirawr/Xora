@extends('layouts.app')

@section('content')
<div class="store-container">
    <!-- Enhanced Sidebar -->
    <aside class="store-sidebar animated-sidebar">
        <div class="sidebar-header">
            <i class="fas fa-filter sidebar-icon"></i>
            <h3 class="neon-subtext">Filter Items</h3>
        </div>

        <form action="{{ route('store') }}" method="GET" class="filter-form">
            <!-- Category Filter -->
            <div class="filter-group">
                <div class="filter-header">
                    <i class="fas fa-tags"></i>
                    <h4>Category</h4>
                </div>
                <ul class="category-list">
                    <li>
                        <a href="{{ route('store', ['category' => 'electronics']) }}" class="category-link {{ request('category') == 'electronics' ? 'active' : '' }}">
                            <i class="fas fa-laptop"></i>
                            <span>Electronics</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store', ['category' => 'clothing']) }}" class="category-link {{ request('category') == 'clothing' ? 'active' : '' }}">
                            <i class="fas fa-tshirt"></i>
                            <span>Clothing</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store', ['category' => 'books']) }}" class="category-link {{ request('category') == 'books' ? 'active' : '' }}">
                            <i class="fas fa-book"></i>
                            <span>Books</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store', ['category' => 'home']) }}" class="category-link {{ request('category') == 'home' ? 'active' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Home & Garden</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store', ['category' => 'sports']) }}" class="category-link {{ request('category') == 'sports' ? 'active' : '' }}">
                            <i class="fas fa-futbol"></i>
                            <span>Sports</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store', ['category' => 'other']) }}" class="category-link {{ request('category') == 'other' ? 'active' : '' }}">
                            <i class="fas fa-ellipsis-h"></i>
                            <span>Other</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('store') }}" class="category-link {{ !request('category') ? 'active' : '' }}">
                            <i class="fas fa-th-large"></i>
                            <span>All Categories</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Price Filter -->
            <div class="filter-group">
                <div class="filter-header">
                    <i class="fas fa-dollar-sign"></i>
                    <h4>Price Range</h4>
                </div>
                <div class="price-inputs">
                    <div class="price-input-group">
                        <input type="number" name="min_price" placeholder="Min Price" class="price-input" value="{{ request('min_price') }}">
                        <span class="price-label">Rp</span>
                    </div>
                    <div class="price-input-group">
                        <input type="number" name="max_price" placeholder="Max Price" class="price-input" value="{{ request('max_price') }}">
                        <span class="price-label">Rp</span>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="filter-group">
                <div class="filter-header">
                    <i class="fas fa-check-circle"></i>
                    <h4>Availability</h4>
                </div>
                <div class="status-options">
                    <label class="status-option">
                        <input type="checkbox" name="status" value="available" {{ request('status') == 'available' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        <span>In Stock Only</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="filter-actions">
                <button type="submit" class="apply-filter-btn">
                    <i class="fas fa-search"></i>
                    <span>Apply Filters</span>
                </button>
                <a href="{{ route('store') }}" class="clear-filter-btn">
                    <i class="fas fa-times"></i>
                    <span>Clear All</span>
                </a>
            </div>
        </form>
    </aside>

    <!-- Enhanced Main Content -->
    <main class="store-main">
        <!-- Store Header -->
        <div class="store-header">
            <div class="header-content">
                <h1 class="neon-text">Black Market Store</h1>
                <p class="neon-subtext">Top secret items available for underground buyers only.</p>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="product-grid">
            @forelse($products as $product)
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
            @empty
            <div class="no-products">
                <div class="empty-state">
                    <i class="fas fa-search empty-icon"></i>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your filters or search criteria.</p>
                    <a href="{{ route('store') }}" class="btn-primary">
                        <i class="fas fa-th-large"></i>
                        View All Products
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
