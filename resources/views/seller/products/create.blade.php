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

                    <!-- Product Variants Section -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-list"></i> Product Variants *
                        </label>
                        <div class="variant-options">
                            <label class="radio-label">
                                <input type="radio" name="has_variants" value="0" {{ old('has_variants', '0') == '0' ? 'checked' : '' }}>
                                No Variants
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="has_variants" value="1" {{ old('has_variants') == '1' ? 'checked' : '' }}>
                                With Variants
                            </label>
                        </div>
                        @error('has_variants')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Variants Details (shown when "With Variants" is selected) -->
                    <div id="variants-section" style="display: {{ old('has_variants') == '1' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-plus"></i> Add Variants
                            </label>
                            <button type="button" id="add-variant-btn" class="btn btn-secondary">
                                <i class="fas fa-plus"></i> Add Variant
                            </button>
                        </div>

                        <div id="variants-container">
                            @if(old('variants'))
                                @foreach(old('variants') as $index => $variant)
                                    <div class="variant-group" data-index="{{ $index }}">
                                        <div class="variant-header">
                                            <h4>Variant {{ $index + 1 }}</h4>
                                            <button type="button" class="remove-variant-btn btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                        <div class="variant-fields">
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label">Variant Name *</label>
                                                    <input type="text" name="variants[{{ $index }}][variant_name]" class="form-input"
                                                           value="{{ $variant['variant_name'] ?? '' }}" placeholder="e.g., Color, Size">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Variant Value *</label>
                                                    <input type="text" name="variants[{{ $index }}][variant_value]" class="form-input"
                                                           value="{{ $variant['variant_value'] ?? '' }}" placeholder="e.g., Red, L">
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label class="form-label">Price (Optional)</label>
                                                    <input type="number" name="variants[{{ $index }}][price]" class="form-input"
                                                           value="{{ $variant['price'] ?? '' }}" step="0.01" min="0">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Stock (Optional)</label>
                                                    <input type="number" name="variants[{{ $index }}][stock]" class="form-input"
                                                           value="{{ $variant['stock'] ?? '' }}" min="0">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Image URL (Optional)</label>
                                                <input type="url" name="variants[{{ $index }}][image_url]" class="form-input"
                                                       value="{{ $variant['image_url'] ?? '' }}" placeholder="https://example.com/image.jpg">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price" class="form-label">
                                <i class="fas fa-money-bill-wave"></i> Price (Rp) *
                            </label>
                            <div class="input-group">
                                <span class="input-prefix">Rp</span>
                                <input type="text" id="price" name="price" class="form-input"
                                       value="{{ old('price') }}" required
                                       placeholder="0">
                                <input type="hidden" id="price_raw" name="price_raw" value="{{ old('price') }}">
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
                            <i class="fas fa-image"></i> Main Image URL *
                        </label>
                        <input type="url" id="image_url" name="image_url" class="form-input"
                               value="{{ old('image_url') }}" required
                               placeholder="https://example.com/image.jpg">
                        @error('image_url')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Multiple Images Section -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-images"></i> Additional Images
                        </label>
                        <div id="images-container">
                            <div class="image-input-group">
                                <input type="url" name="images[]" class="form-input"
                                       placeholder="https://example.com/image.jpg">
                                <button type="button" class="btn-remove-image" onclick="removeImageField(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" class="btn-add-image" onclick="addImageField()">
                            <i class="fas fa-plus"></i> Add Image
                        </button>
                        @error('images.*')
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

/* Variant and Image Styles */
.variant-options {
    display: flex;
    gap: 20px;
    margin-top: 8px;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.radio-label input[type="radio"] {
    margin: 0;
    width: 16px;
    height: 16px;
}

.variant-group {
    margin-bottom: 20px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.variant-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.variant-header h4 {
    margin: 0;
    color: #fff;
    font-size: 16px;
}

.variant-fields {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.image-input-group {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    align-items: center;
    margin-bottom: 10px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.02);
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.btn-add-variant,
.btn-add-image,
.btn-remove-variant,
.btn-remove-image {
    background: rgba(255, 255, 255, 0.1);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 6px;
    padding: 8px 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 12px;
}

.btn-add-variant:hover,
.btn-add-image:hover {
    background: rgba(0, 123, 255, 0.2);
    border-color: #007bff;
}

.btn-remove-variant:hover,
.btn-remove-image:hover {
    background: rgba(255, 107, 107, 0.2);
    border-color: #ff6b6b;
}

.btn-add-variant,
.btn-add-image {
    margin-top: 10px;
    width: fit-content;
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

    .variant-options {
        flex-direction: column;
        gap: 10px;
    }

    .variant-group {
        padding: 10px;
    }

    .variant-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .image-input-group {
        grid-template-columns: 1fr;
        gap: 8px;
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
    const imageUrlInput = document.getElementById('image_url');
    const imagePreview = document.getElementById('image-preview');
    const priceInput = document.getElementById('price');
    const stockInput = document.getElementById('stock');
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');

    // Image preview
    imageUrlInput.addEventListener('input', function() {
        const url = this.value.trim();
        imagePreview.src = url ? url : '{{ asset("assets/depan.png") }}';
    });

    // Validation visual feedback
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            this.style.borderColor = this.value.trim() === '' ? '#ff6b6b' : 'rgba(255, 255, 255, 0.1)';
        });
        input.addEventListener('focus', function() {
            this.style.borderColor = '#007bff';
        });
    });

    // Format Rupiah
    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix === undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }

    priceInput.addEventListener('input', function() {
        this.value = formatRupiah(this.value, '');
        document.getElementById('price_raw').value = this.value.replace(/\./g, '');
    });

    // Stock minimal 0
    stockInput.addEventListener('input', function() {
        if (parseInt(this.value) < 0) this.value = 0;
    });

    // Replace price with raw before submit
    form.addEventListener('submit', function() {
        priceInput.value = document.getElementById('price_raw').value;
    });

    // Variant selection handling
    const variantRadios = document.querySelectorAll('input[name="has_variants"]');
    const variantsSection = document.getElementById('variants-section');

    variantRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === '1') {
                variantsSection.style.display = 'block';
            } else {
                variantsSection.style.display = 'none';
            }
        });
    });

    // Add variant field
    document.getElementById('add-variant-btn').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const index = container.children.length;
        const variantGroup = document.createElement('div');
        variantGroup.className = 'variant-group';
        variantGroup.setAttribute('data-index', index);
        variantGroup.innerHTML = `
            <div class="variant-header">
                <h4>Variant ${index + 1}</h4>
                <button type="button" class="remove-variant-btn btn btn-danger btn-sm">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
            <div class="variant-fields">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Variant Name *</label>
                        <input type="text" name="variants[${index}][variant_name]" class="form-input" placeholder="e.g., Color, Size" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Variant Value *</label>
                        <input type="text" name="variants[${index}][variant_value]" class="form-input" placeholder="e.g., Red, L" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Price (Optional)</label>
                        <input type="number" name="variants[${index}][price]" class="form-input" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stock (Optional)</label>
                        <input type="number" name="variants[${index}][stock]" class="form-input" min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Image URL (Optional)</label>
                    <input type="url" name="variants[${index}][image_url]" class="form-input" placeholder="https://example.com/image.jpg">
                </div>
            </div>
        `;
        container.appendChild(variantGroup);

        // Add event listener to remove button
        variantGroup.querySelector('.remove-variant-btn').addEventListener('click', function() {
            variantGroup.remove();
            updateVariantIndices();
        });
    });

    // Function to update variant indices after removal
    function updateVariantIndices() {
        const groups = document.querySelectorAll('.variant-group');
        groups.forEach((group, index) => {
            group.setAttribute('data-index', index);
            group.querySelector('h4').textContent = `Variant ${index + 1}`;
            const inputs = group.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name.replace(/\[\d+\]/, `[${index}]`);
                input.name = name;
            });
        });
    }

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-variant-btn').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.variant-group').remove();
            updateVariantIndices();
        });
    });

    // Add image field
    document.querySelector('.btn-add-image').addEventListener('click', function() {
        const container = document.getElementById('images-container');
        const imageGroup = document.createElement('div');
        imageGroup.className = 'image-input-group';
        imageGroup.innerHTML = `
            <input type="url" name="images[]" class="form-input" placeholder="https://example.com/image.jpg">
            <button type="button" class="btn-remove-image">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(imageGroup);

        // Add event listener to remove button
        imageGroup.querySelector('.btn-remove-image').addEventListener('click', function() {
            imageGroup.remove();
        });
    });

    // Add event listeners to existing remove buttons
    document.querySelectorAll('.btn-remove-image').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.image-input-group').remove();
        });
    });
});
</script>

@endsection
