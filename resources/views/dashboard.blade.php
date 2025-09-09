    @if($user->role === 'user')
        @include('dashboard._user_dashboard')
    @elseif($user->role === 'seller')
        @include('dashboard._seller_dashboard')
    @elseif($user->role === 'developer')
        @include('dashboard._developer_dashboard')
    @endif
</div>