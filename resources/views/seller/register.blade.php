@extends('layouts.app')

@section('title', 'Become a Seller')

@section('content')
<div class="seller-register-container">
    <div class="register-header">
        <h1>Become a Seller</h1>
        <p>Set up your store and start selling your products</p>
    </div>

    <form action="{{ route('seller.register') }}" method="POST" enctype="multipart/form-data" class="register-form">
        @csrf

        <!-- Store Name -->
        <div class="form-group">
            <label for="name">Store Name <span class="required">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Store Description -->
        <div class="form-group">
            <label for="description">Store Description</label>
            <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @error('description')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Store Logo -->
        <div class="form-group">
            <label for="logo">Store Logo</label>
            <div class="file-upload-area">
                <div class="upload-content">
                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                    <p class="upload-text"><span class="upload-bold">Click to upload</span> or drag and drop</p>
                    <p class="upload-hint">PNG, JPG, GIF up to 2MB</p>
                </div>
                <input type="file" id="logo" name="logo" accept="image/*">
            </div>
            @error('logo')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <!-- Logo Preview -->
        <div id="logo-preview" class="logo-preview hidden">
            <label class="preview-label">Logo Preview</label>
            <div class="preview-content">
                <img id="preview-image" src="" alt="Logo Preview">
                <button type="button" id="remove-logo" class="remove-btn">Remove</button>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="button-group">
            <button type="submit" class="btn-submit">
                <i class="fas fa-store"></i>
                Create Store & Continue
            </button>
            <a href="{{ route('dashboard') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>

    <!-- Info Section -->
    <div class="info-section">
        <div class="info-content">
            <i class="fas fa-info-circle info-icon"></i>
            <div class="info-text">
                <h3>What happens next?</h3>
                <ul>
                    <li>• Your store will be created and activated immediately</li>
                    <li>• You'll be redirected to add your first product</li>
                    <li>• Once you add a product, you'll be ready to start selling</li>
                    <li>• You can manage your store and products from your seller dashboard</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
/* Main Container */
.seller-register-container {
    max-width: 600px;
    margin: 140px auto;
    padding: 30px;
    background-color: #1a1a1a;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
    color: #fff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Header */
.register-header h1 {
    font-size: 2.2rem;
    margin-bottom: 8px;
    color: #fff;
    text-align: center;
}

.register-header p {
    font-size: 1rem;
    margin-bottom: 24px;
    text-align: center;
    color: #9ca3af;
}

/* Form */
.register-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Form Groups */
.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 6px;
    color: #fff;
}

.required {
    color: #ff6b6b;
}

/* Input Styles */
.form-group input[type="text"],
.form-group textarea {
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    background-color: #2a2a2a;
    color: #fff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input[type="text"]:focus,
.form-group textarea:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.3);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

/* File Upload Area */
.file-upload-area {
    position: relative;
    width: 100%;
    height: 120px;
    border: 2px dashed rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    background-color: #2a2a2a;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
}

.file-upload-area:hover {
    border-color: rgba(255, 255, 255, 0.4);
    background-color: #3a3a3a;
}

.upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    padding: 20px;
    text-align: center;
}

.upload-icon {
    font-size: 2rem;
    color: #9ca3af;
    margin-bottom: 8px;
}

.upload-text {
    margin: 0 0 4px 0;
    color: #9ca3af;
    font-size: 0.9rem;
}

.upload-bold {
    font-weight: 600;
    color: #fff;
}

.upload-hint {
    margin: 0;
    color: #666;
    font-size: 0.8rem;
}

.file-upload-area input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

/* Logo Preview */
.logo-preview {
    margin-top: 10px;
}

.preview-label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #fff;
}

.preview-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.preview-content img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.remove-btn {
    padding: 8px 12px;
    background-color: rgba(255, 0, 0, 0.2);
    color: #ff6b6b;
    border: 1px solid rgba(255, 0, 0, 0.3);
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.remove-btn:hover {
    background-color: rgba(255, 0, 0, 0.3);
    transform: translateY(-1px);
}

/* Buttons */
.button-group {
    display: flex;
    gap: 15px;
    margin-top: 10px;
}

.btn-submit {
    flex: 1;
    background: linear-gradient(135deg, #d0d0d0 0%, #5d5d5d 100%);
    color: #fff;
    font-weight: 700;
    padding: 14px 20px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-submit:hover {
    background: linear-gradient(135deg, #a8a8a8 0%, #424242 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(131, 131, 131, 0.4);
}

.btn-cancel {
    padding: 14px 20px;
    background-color: rgba(255, 255, 255, 0.1);
    color: #9ca3af;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-cancel:hover {
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff;
    transform: translateY(-1px);
}

/* Error Messages */
.error-message {
    margin-top: 4px;
    color: #ff6b6b;
    font-size: 0.875rem;
}

/* Info Section */
.info-section {
    margin-top: 30px;
    padding: 20px;
    background-color: rgba(102, 126, 234, 0.1);
    border: 1px solid rgba(102, 126, 234, 0.3);
    border-radius: 8px;
}

.info-content {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.info-icon {
    color: #667eea;
    font-size: 1.5rem;
    margin-top: 2px;
}

.info-text h3 {
    color: #667eea;
    margin: 0 0 8px 0;
    font-size: 1.1rem;
}

.info-text ul {
    margin: 0;
    padding-left: 20px;
    color: #9ca3af;
}

.info-text li {
    margin-bottom: 4px;
    font-size: 0.9rem;
}

/* Hidden Class */
.hidden {
    display: none !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .seller-register-container {
        margin: 30px 15px;
        padding: 20px;
    }

    .register-header h1 {
        font-size: 1.8rem;
    }

    .button-group {
        flex-direction: column;
    }

    .btn-submit,
    .btn-cancel {
        width: 100%;
    }

    .preview-content {
        flex-direction: column;
        align-items: flex-start;
    }

    .info-content {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .seller-register-container {
        margin: 20px 10px;
        padding: 15px;
    }

    .register-header h1 {
        font-size: 1.6rem;
    }

    .upload-content {
        padding: 15px;
    }

    .upload-icon {
        font-size: 1.5rem;
    }
}
</style>

<script>
// Logo preview functionality
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('logo-preview');
    const previewImage = document.getElementById('preview-image');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        preview.classList.add('hidden');
    }
});

// Remove logo functionality
document.getElementById('remove-logo').addEventListener('click', function() {
    document.getElementById('logo').value = '';
    document.getElementById('logo-preview').classList.add('hidden');
});

// Drag and drop functionality
const uploadArea = document.querySelector('.file-upload-area');
const fileInput = document.getElementById('logo');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    uploadArea.classList.add('dragover');
}

function unhighlight(e) {
    uploadArea.classList.remove('dragover');
}

uploadArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    const dt = e.dataTransfer;
    const files = dt.files;

    if (files.length > 0) {
        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change'));
    }
}
</script>
@endsection
