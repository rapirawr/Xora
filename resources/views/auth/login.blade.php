@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="neon-text">Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required autofocus>
                @error('email')<span class="error-message">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                @error('password')<span class="error-message">{{ $message }}</span>@enderror
            </div>
            <button type="submit" class="auth-btn">Login</button>
        </form>
        <p class="auth-link">Don't have an account? <a href="{{ route('register') }}">Sign Up</a></p>
    </div>
</div>
@endsection