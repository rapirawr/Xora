@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Welcome to your personalized dashboard</p>
    </div>

    @if($user->role === 'user')
        @include('dashboard._user_dashboard')
    @elseif($user->role === 'seller')
        @include('dashboard._seller_dashboard')
    @elseif($user->role === 'developer')
        @include('dashboard._developer_dashboard')
    @endif
</div>
@endsection
