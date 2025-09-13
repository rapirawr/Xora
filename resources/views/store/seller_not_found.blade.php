@extends('layouts.app')

@section('content')
<div class="store-container">
    <main class="store-main">
        <div class="store-header">
            <div class="header-content">
                <h1 class="neon-text">Seller Not Found</h1>
                <p class="neon-subtext">The seller "{{ $usernameSeller }}" could not be found.</p>
            </div>
        </div>

        <div class="no-products">
            <div class="empty-state">
                <i class="fas fa-user-times empty-icon"></i>
                <h3>Seller Not Found</h3>
                <p>The seller with username "{{ $usernameSeller }}" does not exist or is not a seller.</p>
                <a href="{{ route('store') }}" class="btn-primary">
                    <i class="fas fa-th-large"></i>
                    Browse All Products
                </a>
            </div>
        </div>
    </main>
</div>
@endsection
