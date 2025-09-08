<div class="dashboard-grid">
    <!-- Account Overview Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-user-circle card-icon"></i>
            <h2 class="neon-subtext">My Account</h2>
        </div>
        <div class="card-content">
            <div class="account-stats" style="display: flex; align-items: center; gap: 20px;">
                <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #333;">
                <div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $user->name }}</span>
                        <span class="stat-label">Username</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $user->email }}</span>
                        <span class="stat-label">Email</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ ucfirst($user->role) }}</span>
                        <span class="stat-label">Role</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-bolt card-icon"></i>
            <h2 class="neon-subtext">Quick Actions</h2>
        </div>
        <div class="card-content">
            <div class="action-buttons">
                <a href="{{ route('profile') }}" class="action-btn">
                    <i class="fas fa-user-edit"></i>
                    <span>Edit Profile</span>
                </a>
                @if(!$user->isSeller())
                <a href="{{ route('seller.register') }}" class="action-btn">
                    <i class="fas fa-store"></i>
                    <span>Be a Seller</span>
                </a>
                @else
                <a href="{{ route('store') }}" class="action-btn">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Browse Store</span>
                </a>
                @endif
                <a href="{{ route('orders.index') }}" class="action-btn">
                    <i class="fas fa-history"></i>
                    <span>Order History</span>
                </a>
                <a href="#" class="action-btn">
                    <i class="fas fa-heart"></i>
                    <span>My Wishlist</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-clock card-icon"></i>
            <h2 class="neon-subtext">Recent Activity</h2>
        </div>
        <div class="card-content">
            <div class="activity-timeline">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="activity-content">
                        <p>Welcome to your dashboard!</p>
                        <span class="activity-time">Just now</span>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="activity-content">
                        <p>Account created successfully</p>
                        <span class="activity-time">Today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-chart-bar card-icon"></i>
            <h2 class="neon-subtext">Statistics</h2>
        </div>
        <div class="card-content">
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-value">0</div>
                    <div class="stat-title">Orders</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">0</div>
                    <div class="stat-title">Reviews</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">0</div>
                    <div class="stat-title">Wishlist</div>
                </div>
                <div class="stat-box">
                    <div class="stat-value">0</div>
                    <div class="stat-title">Points</div>
                </div>
            </div>
        </div>
    </div>
</div>
