@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="neon-text">Add New Product</h1>
        <p class="neon-subtext">Fill out the form to add a new item to your store.</p>
    </div>

    <div class="dashboard-card">
        <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-grid">
                <!-- Left Column -->
                <div class="form-column">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-tag"></i> Product Name *
                        </label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="{{ old('name') }}" required
                               placeholder="Enter product name">
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i> Description *
                        </label>
                        <textarea id="description" name="description" class="form-textarea" rows="4"
                                  required placeholder="Describe your product">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price" class="form-label">
                                <i class="fas fa-dollar-sign"></i> Price *
                            </label>
                            <div class="input-group">
                                <span class="input-prefix">$</span>
                                <input type="number" id="price" name="price" class="form-input"
                                       step="0.01" min="0" value="{{ old('price') }}" required
                                       placeholder="0.00">
                            </div>
                            @error('price')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="stock" class="form-label">
                                <i class="fas fa-boxes"></i> Stock *
                            </label>
                            <input type="number" id="stock" name="stock" class="form-input"
                                   min="0" value="{{ old('stock', 0) }}" required
                                   placeholder="0">
                            @error('stock')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="form-column">
                    <div class="form-group">
                        <label for="category" class="form-label">
                            <i class="fas fa-list"></i> Category
                        </label>
                    <select id="category" name="category" class="form-select" required>
                        <option value="" disabled selected>Select Category</option>
                        @foreach(\App\Models\Product::getCategoryOptions() as $key => $label)
                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                        @error('category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="image_url" class="form-label">
                            <i class="fas fa-image"></i> Image URL *
                        </label>
                        <input type="url" id="image_url" name="image_url" class="form-input"
                               value="{{ old('image_url') }}" required
                               placeholder="https://example.com/image.jpg">
                        @error('image_url')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>


                </div>
            </div>

            <!-- Image Preview Section -->
            <div class="image-preview-section">
                <h3 class="preview-title">
                    <i class="fas fa-eye"></i> Image Preview
                </h3>
                <div class="image-preview-container">
                    <img id="image-preview" src="{{ old('image_url') }}"
                         alt="Product Image Preview" class="preview-image">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <a href="{{ route('seller.products.manage') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 30px;
}

.form-column {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-label {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-label i {
    color: #9ca3af;
    font-size: 14px;
}

.form-input,
.form-textarea,
.form-select {
    padding: 12px 16px;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #fff;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.2);
    background: rgba(255, 255, 255, 0.08);
}

.form-input::placeholder,
.form-textarea::placeholder {
    color: #9ca3af;
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.form-select {
    cursor: pointer;
}

.input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.input-prefix {
    position: absolute;
    left: 16px;
    color: #9ca3af;
    font-weight: 600;
    z-index: 1;
}

.input-group .form-input {
    padding-left: 35px;
}

.error-message {
    color: #ff6b6b;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

.image-preview-section {
    margin: 30px 0;
    padding: 20px;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.preview-title {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.image-preview-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
    border: 2px dashed rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.preview-image {
    max-width: 100%;
    max-height: 200px;
    object-fit: contain;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.preview-image:hover {
    transform: scale(1.05);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.btn-primary,
.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 25px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    font-size: 14px;
}

.btn-primary {
    background: linear-gradient(135deg, #d0d0d0 0%, #5d5d5d 100%);
    color: #fff;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #a8a8a8 0%, #424242 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(131, 131, 131, 0.4);
}

.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: #9ca3af;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #fff;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn-primary,
    .btn-secondary {
        width: 100%;
        justify-content: center;
    }

    .image-preview-container {
        min-height: 150px;
    }

    .preview-image {
        max-height: 150px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const imageUrlInput = document.getElementById('image_url');
    const imagePreview = document.getElementById('image-preview');

    imageUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        if (url) {
            imagePreview.src = url;
            imagePreview.style.display = 'block';
        } else {
            imagePreview.src = '{{ asset("assets/depan.png") }}';
        }
    });

    // Form validation enhancement
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');

    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.style.borderColor = '#ff6b6b';
            } else {
                this.style.borderColor = 'rgba(255, 255, 255, 0.1)';
            }
        });

        input.addEventListener('focus', function() {
            this.style.borderColor = '#007bff';
        });
    });

    // Price input formatting
    const priceInput = document.getElementById('price');
    priceInput.addEventListener('input', function() {
        let value = parseFloat(this.value);
        if (value < 0) {
            this.value = 0;
        }
    });

    // Stock input validation
    const stockInput = document.getElementById('stock');
    stockInput.addEventListener('input', function() {
        let value = parseInt(this.value);
        if (value < 0) {
            this.value = 0;
        }
    });
});
</script>
@endsection
