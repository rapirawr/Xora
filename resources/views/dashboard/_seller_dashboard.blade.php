<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-content">
        <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="profile-photo">
        <div>
            <h1 class="welcome-title">Welcome back, {{ $user->name }}!</h1>
            <p class="welcome-subtitle">Here's what's happening with your store today</p>
        </div>
    </div>
    <div class="welcome-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $user->products->count() }}</div>
                <div class="stat-label">Total Products</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ $user->products->sum('sold') }}</div>
                <div class="stat-label">Items Sold</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">Rp {{ number_format($user->products->sum('price') * $user->products->sum('sold'), 0, ',', '.') }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Grid -->
<div class="dashboard-grid">
    <!-- Quick Actions Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <div class="action-icon">
                <i class="fas fa-rocket"></i>
            </div>
            <h2 class="card-title">Quick Actions</h2>
        </div>
        <div class="card-content">
            <div class="action-grid">
                <a href="{{ route('seller.products.create') }}" class="action-item primary">
                    <div class="action-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="action-content">
                        <h4>Add Product</h4>
                        <p>Create new listing</p>
                    </div>
                </a>
                <a href="{{ route('seller.products.manage') }}" class="action-item">
                    <div class="action-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="action-content">
                        <h4>Manage Products</h4>
                        <p>Edit & organize</p>
                    </div>
                </a>
                <a href="{{ route('seller.orders.manage') }}" class="action-item">
                    <div class="action-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="action-content">
                        <h4>Orders</h4>
                        <p>Process orders</p>
                    </div>
                </a>
                <a href="{{ route('seller.reports.sales') }}" class="action-item">
                    <div class="action-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="action-content">
                        <h4>Analytics</h4>
                        <p>View reports</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Sales Performance Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <div class="action-icon">
                <i class="fas fa-chart-pie "></i>
            </div>
                <h2 class="card-title">Sales Performance</h2>
        </div>
        <div class="card-content">
            <div class="performance-metrics">
                <div class="metric-item">
                    <div class="metric-header">
                        <span class="metric-title">Stock Status</span>
                        <span class="metric-value">{{ $analytics['stockInCount'] ?? 0 }}/{{ $analytics['stockTotalCount'] ?? 0 }}</span>
                    </div>
                    <div class="metric-bar">
                        <div class="metric-fill" data-width="{{ $analytics['stockTotalCount'] > 0 ? ($analytics['stockInCount'] / $analytics['stockTotalCount']) * 100 : 0 }}%"></div>
                    </div>
                    <div class="metric-status">
                        <span class="status-text">{{ $analytics['stockInCount'] ?? 0 }} in stock</span>
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-header">
                        <span class="metric-title">Revenue Growth</span>
                        <span class="metric-value">{{ ($analytics['revenueGrowth'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($analytics['revenueGrowth'] ?? 0, 2) }}%</span>
                    </div>
                    <div class="metric-bar">
                        <div class="metric-fill growth" data-width="{{ min(max($analytics['revenueGrowth'] ?? 0, 0), 200) }}%"></div>
                    </div>
                    <div class="metric-trend">
                        <i class="fas fa-arrow-{{ ($analytics['revenueGrowth'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                        <span>vs last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <div class="action-icon">
                <i class="fas fa-clock "></i>
            </div>
            <h2 class="card-title">Recent Orders</h2>
            <a href="{{ route('seller.orders.manage') }}" class="view-all-link">View All</a>
        </div>
        <div class="card-content">
            @php
                $recentOrders = \App\Models\OrderHeader::where('seller_id', $user->id)
                    ->with(['user', 'orders.product'])
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            @endphp

            @if($recentOrders->count() > 0)
                <div class="orders-list">
                    @foreach($recentOrders as $order)
                    <div class="order-item">
                        <div class="order-info">
                            <div class="order-customer">
                                <i class="fas fa-user"></i>
                                <span>{{ $order->user->name }}</span>
                            </div>
                            <div class="order-details">
                                <span class="order-id">#{{ $order->id }}</span>
                                <span class="order-time">{{ $order->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <h4>No orders yet</h4>
                    <p>Your recent orders will appear here</p>
                </div>
            @endif
        </div>
    </div>


    <!-- Analytics Overview Card -->
    <div class="dashboard-card animated-card analytics-card">
        <div class="card-header">
            <div class="action-icon">
                <i class="fas fa-chart-bar "></i>
            </div>
            <h2 class="neon-subtext">Analytics Overview</h2>
        </div>
        <div class="card-content">
            <div class="analytics-grid">
                <div class="analytics-item">
                    <div class="analytics-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="analytics-info">
                        <div class="analytics-value">{{ $analytics['totalOrders'] ?? 0 }}</div>
                        <div class="analytics-label">Total Orders</div>
                        <div class="analytics-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+{{ number_format(($analytics['totalOrders'] ?? 0) * 0.1, 0) }}</span>
                        </div>
                    </div>
                </div>

                <div class="analytics-item">
                    <div class="analytics-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="analytics-info">
                        <div class="analytics-value">{{ $analytics['deliveredOrders'] ?? 0 }}</div>
                        <div class="analytics-label">Delivered Orders</div>
                        <div class="analytics-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>{{ $analytics['totalOrders'] > 0 ? number_format(($analytics['deliveredOrders'] / $analytics['totalOrders']) * 100, 0) : 0 }}%</span>
                        </div>
                    </div>
                </div>

                <div class="analytics-item">
                    <div class="analytics-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="analytics-info">
                        <div class="analytics-value">Rp {{ number_format($analytics['totalRevenue'] ?? 0, 0, ',', '.') }}</div>
                        <div class="analytics-label">Total Revenue</div>
                        <div class="analytics-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+{{ number_format(($analytics['revenueGrowth'] ?? 0), 1) }}%</span>
                        </div>
                    </div>
                </div>

                <div class="analytics-item">
                    <div class="analytics-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="analytics-info">
                        <div class="analytics-value">{{ number_format($analytics['avgRating'] ?? 0, 1) }}</div>
                        <div class="analytics-label">Avg Rating</div>
                        <div class="analytics-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+0.2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Timeline Card -->
    <div class="dashboard-card animated-card activity-card">
        <div class="card-header">
            <div class="action-icon">
                <i class="fas fa-history "></i>
            </div>
            <h2 class="neon-subtext">Recent Activity</h2>
        </div>
        <div class="card-content">
            <div class="activity-timeline">
                @if($user->products->count() > 0)
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="activity-content">
                            <p>New product added: <strong>{{ $user->products->last()->name }}</strong></p>
                            <span class="activity-time">{{ $user->products->last()->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @endif

                <div class="activity-item">
                    <div class="activity-icon info">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="activity-content">
                        <p>Seller account activated</p>
                        <span class="activity-time">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-icon primary">
                        <i class="fas fa-store"></i>
                    </div>
                    <div class="activity-content">
                        <p>Store dashboard accessed</p>
                        <span class="activity-time">Just now</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


