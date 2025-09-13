<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config("app.name") }}</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/store.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@stack('styles')
</head>
<body>

<nav class="navbar">
    <div class="logo">{{ config("app.name") }}</div>
    <div class="menu">
        <a href="{{ url('/') }}">_Home</a>
        <a href="{{ route('store') }}">_Store</a>
    </div>
    <div class="auth-links">
        @auth
            <a href="{{ route('cart.index') }}" class="cart-icon-wrapper" style="position: relative; display: inline-block;">
                <i class="fa-solid fa-cart-shopping"></i>
                @if($cartItemCount > 0)
                    <span class="cart-badge">{{ $cartItemCount }}</span>
                @endif
            </a>
            <a href="{{ route('profile') }}"><i class="fa fa-user"></i></a>
        @else
            <a href="{{ route('login') }}" class="login-btn">Login</a>
            <a href="{{ route('register') }}" class="register-btn">Sign Up</a>
        @endauth
    </div>
</nav>

@yield('content')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
