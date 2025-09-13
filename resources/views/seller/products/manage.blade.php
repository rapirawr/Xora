@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="neon-text">Manage Products</h1>
        <p class="neon-subtext">Manage your product listings and inventory.</p>
    </div>

    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-box card-icon"></i>
            <h2 class="neon-subtext">Your Products</h2>
            <a href="{{ route('seller.products.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>
        <div class="card-content">
            @if($products->count() > 0)
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Sold</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-thumb">
                            </td>
                            <td>
                                <div class="product-info-cell">
                                    <strong>{{ $product->name }}</strong>
                                    <small>{{ Str::limit($product->description, 50) }}</small>
                                </div>
                            </td>
                            <td class="price-cell">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->sold }}</td>
                            <td>
                                <span class="stock-badge {{ $product->stock > 10 ? 'good' : ($product->stock > 0 ? 'warning' : 'danger') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('seller.products.edit', $product) }}" class="btn-icon edit-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('seller.products.delete', $product) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-box-open empty-icon"></i>
                <h3>No Products Yet</h3>
                <p>You haven't added any products to your store.</p>
                <a href="{{ route('seller.products.create') }}" class="btn-primary">
                    <i class="fas fa-plus"></i> Add Your First Product
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection