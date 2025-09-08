@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="neon-text">Sign Up</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div>
                <label for="name">Name</label>
                <input type="text" name="name" id="name" required autofocus>
                @error('name')<span class="error-message">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
                @error('username')<span class="error-message">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                @error('email')<span class="error-message">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                @error('password')<span class="error-message">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>
            <button type="submit" class="auth-btn">Register</button>
        </form>
        <p class="auth-link">Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </div>
</div>
@endsection