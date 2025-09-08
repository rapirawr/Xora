@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <h2 class="neon-text">My Profile</h2>

        <!-- Profile Photo Section -->
        <div class="profile-photo-section" style="text-align: center; margin-bottom: 30px;">
            <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #333; margin-bottom: 15px;">
            <form action="{{ route('profile.upload-photo') }}" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                @csrf
                <input type="file" name="profile_photo" accept="image/*" style="margin-bottom: 10px;" required>
                <br>
                <button type="submit" class="auth-btn" style="background-color: #28a745;">Upload Photo</button>
            </form>
        </div>

        <div class="profile-info">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="auth-btn" style="display:block; margin-top:20px;">Go to Dashboard</a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
            @csrf
            <button type="submit" class="auth-btn" style="background-color: #e3342f; color: white; border: none; padding: 10px 20px; cursor: pointer;">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
