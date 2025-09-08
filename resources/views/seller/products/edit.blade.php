@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="dashboard-card">
                <div class="card-header">
                    <h2 class="neon-subtext">Edit Product</h2>
                    <a href="{{ route('seller.products.manage') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>

                <div class="card-content">
                    <form action="{{ route('seller.products.update', $product) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="name">Product Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="4" placeholder="Describe your product...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="price">Price (Rp) *</label>
                                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="number" name="stock" id="stock" min="0" value="{{ old('stock', $product->stock) }}">
                                @error('stock')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="category">Category *</label>
                            <select name="category" id="category" required>
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Product::getCategoryOptions() as $key => $value)
                                    <option value="{{ $key }}" {{ old('category', $product->category) == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="image_url">Image URL</label>
                            <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $product->image_url) }}" placeholder="https://example.com/image.jpg">
                            @error('image_url')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="sold">Items Sold</label>
                            <input type="number" name="sold" id="sold" min="0" value="{{ old('sold', $product->sold) }}">
                            @error('sold')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-custom">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                            <a href="{{ route('seller.products.manage') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.dashboard-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    padding: 25px;
    border: 1px solid rgba(0, 0, 0, 0.1);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.card-header h2 {
    margin: 0;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.card-content {
    padding: 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 14px;
    background: #fff;
    color: #333;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #6c757d;
    box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.25);
}

.form-group textarea {
    resize: vertical;
    min-height: 100px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    border: none;
}

.btn-custom {
    background: linear-gradient(135deg, #333 0%, #666 100%);
    color: white;
}

.btn-custom:hover {
    background: linear-gradient(135deg, #666 0%, #999 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .container {
        padding: 15px;
    }

    .dashboard-card {
        padding: 20px;
    }

    .card-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        justify-content: center;
    }
}
</style>
