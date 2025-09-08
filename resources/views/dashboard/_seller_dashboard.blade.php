<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-content" style="display: flex; align-items: center; gap: 20px;">
        <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #333;">
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
    <div class="dashboard-card animated-card quick-actions-card">
        <div class="card-header">
            <i class="fas fa-rocket card-icon"></i>
            <h2 class="neon-subtext">Quick Actions</h2>
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
    <div class="dashboard-card animated-card performance-card">
        <div class="card-header">
            <i class="fas fa-chart-pie card-icon"></i>
            <h2 class="neon-subtext">Sales Performance</h2>
        </div>
        <div class="card-content">
            <div class="performance-metrics">
                <div class="metric-item">
                    <div class="metric-header">
                        <span class="metric-title">Average Rating</span>
                        <span class="metric-value">{{ number_format($user->products->avg('rating') ?: 0, 1) }}</span>
                    </div>
                    <div class="metric-bar">
                        <div class="metric-fill" style="width: {{ ($user->products->avg('rating') ?: 0) * 20 }}%"></div>
                    </div>
                    <div class="metric-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= ($user->products->avg('rating') ?: 0) ? 'filled' : '' }}"></i>
                        @endfor
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-header">
                        <span class="metric-title">Stock Status</span>
                        <span class="metric-value">{{ $user->products->where('stock', '>', 0)->count() }}/{{ $user->products->count() }}</span>
                    </div>
                    <div class="metric-bar">
                        <div class="metric-fill" style="width: {{ $user->products->count() > 0 ? ($user->products->where('stock', '>', 0)->count() / $user->products->count()) * 100 : 0 }}%"></div>
                    </div>
                    <div class="metric-status">
                        <span class="status-text">{{ $user->products->where('stock', '>', 0)->count() }} in stock</span>
                    </div>
                </div>

                <div class="metric-item">
                    <div class="metric-header">
                        <span class="metric-title">Revenue Growth</span>
                        <span class="metric-value">+12%</span>
                    </div>
                    <div class="metric-bar">
                        <div class="metric-fill growth" style="width: 122%"></div>
                    </div>
                    <div class="metric-trend">
                        <i class="fas fa-arrow-up"></i>
                        <span>vs last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Card -->
    <div class="dashboard-card animated-card orders-card">
        <div class="card-header">
            <i class="fas fa-clock card-icon"></i>
            <h2 class="neon-subtext">Recent Orders</h2>
            <a href="{{ route('seller.orders.manage') }}" class="view-all">View All</a>
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
                            <span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
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

    <!-- Top Products Card -->
    <div class="dashboard-card animated-card products-card">
        <div class="card-header">
            <i class="fas fa-trophy card-icon"></i>
            <h2 class="neon-subtext">Top Products</h2>
            <a href="{{ route('seller.products.manage') }}" class="view-all">View All</a>
        </div>
        <div class="card-content">
            @if($user->products->count() > 0)
                <div class="products-list">
                    @foreach($user->products->sortByDesc('sold')->take(3) as $product)
                    <div class="product-item">
                        <div class="product-image">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" onerror="this.src='{{ asset('assets/depan.png') }}'">
                        </div>
                        <div class="product-info">
                            <h4>{{ Str::limit($product->name, 25) }}</h4>
                            <div class="product-stats">
                                <span class="sold-count">{{ $product->sold }} sold</span>
                                <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-box"></i>
                    <h4>No products yet</h4>
                    <p><a href="{{ route('seller.products.create') }}">Create your first product</a></p>
                </div>
            @endif
        </div>
    </div>

    <!-- Analytics Overview Card -->
    <div class="dashboard-card animated-card analytics-card">
        <div class="card-header">
            <i class="fas fa-chart-bar card-icon"></i>
            <h2 class="neon-subtext">Analytics Overview</h2>
        </div>
        <div class="card-content">
            <div class="analytics-grid">
                <div class="analytics-item">
                    <div class="analytics-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="analytics-info">
                        <div class="analytics-value">{{ rand(100, 999) }}</div>
                        <div class="analytics-label">Page Views</div>
                        <div class="analytics-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+15%</span>
                        </div>
                    </div>
                </div>

                <div class="analytics-item">
                    <div class="analytics-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="analytics-info">
                        <div class="analytics-value">{{ rand(50, 200) }}</div>
                        <div class="analytics-label">Visitors</div>
                        <div class="analytics-change positive">
                            <i class="fas fa-arrow-up"></i>
                            <span>+8%</span>
                        </div>
                    </div>
                </div>

                <div class="analytics-item">
                    <div class="analytics-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="analytics-info">
                        <div class="analytics-value">{{ rand(10, 50) }}</div>
                        <div class="analytics-label">Conversions</div>
                        <div class="analytics-change negative">
                            <i class="fas fa-arrow-down"></i>
                            <span>-3%</span>
                        </div>
                    </div>
                </div>

                <div class="analytics-item">
                    <div class="analytics-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="analytics-info">
                        <div class="analytics-value">{{ number_format($user->products->avg('rating') ?: 0, 1) }}</div>
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
            <i class="fas fa-history card-icon"></i>
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

<style>
.welcome-section {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 30px;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.welcome-content {
    margin-bottom: 25px;
}

.welcome-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #fff;
    margin: 0 0 8px 0;
    background: linear-gradient(135deg, #fff 0%, #e0e0e0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-subtitle {
    font-size: 1.1rem;
    color: #9ca3af;
    margin: 0;
}

.welcome-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.stat-card {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.3s ease;
}

.stat-card:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-2px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #333 0%, #666 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.stat-info {
    flex: 1;
}

.stat-info .stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 4px;
}

.stat-info .stat-label {
    font-size: 0.9rem;
    color: #9ca3af;
    font-weight: 500;
}

.quick-actions-card .action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 15px;
}

.action-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    text-decoration: none;
    color: #fff;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.action-item:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: translateY(-2px);
    color: #fff;
}

.action-item.primary {
    background: linear-gradient(135deg, #333 0%, #666 100%);
    border: none;
}

.action-item.primary:hover {
    background: linear-gradient(135deg, #666 0%, #999 100%);
}

.action-icon {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.1rem;
}

.action-item.primary .action-icon {
    background: rgba(255, 255, 255, 0.2);
}

.action-content h4 {
    margin: 0 0 4px 0;
    font-size: 0.95rem;
    font-weight: 600;
}

.action-content p {
    margin: 0;
    font-size: 0.8rem;
    color: #9ca3af;
}

.performance-metrics {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.metric-item {
    padding: 20px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.metric-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.metric-title {
    font-size: 0.9rem;
    color: #9ca3af;
    font-weight: 500;
}

.metric-value {
    font-size: 1.2rem;
    font-weight: 700;
    color: #fff;
}

.metric-bar {
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 8px;
}

.metric-fill {
    height: 100%;
    background: linear-gradient(90deg, #666 0%, #999 100%);
    border-radius: 3px;
    transition: width 0.3s ease;
}

.metric-fill.growth {
    background: linear-gradient(90deg, #888 0%, #aaa 100%);
}

.metric-stars {
    display: flex;
    gap: 2px;
}

.metric-stars .fa-star {
    font-size: 0.8rem;
    color: #ddd;
}

.metric-stars .fa-star.filled {
    color: #ffc107;
}

.metric-status, .metric-trend {
    font-size: 0.8rem;
    color: #9ca3af;
    display: flex;
    align-items: center;
    gap: 4px;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.order-customer {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    color: #fff;
}

.order-customer i {
    color: #666;
}

.order-details {
    display: flex;
    gap: 12px;
    font-size: 0.85rem;
    color: #9ca3af;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.pending {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
}

.status-badge.processing {
    background: rgba(108, 117, 125, 0.2);
    color: #6c757d;
}

.status-badge.shipped {
    background: rgba(255, 152, 0, 0.2);
    color: #ff9800;
}

.status-badge.delivered {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.products-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.product-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 16px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.product-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    flex: 1;
}

.product-info h4 {
    margin: 0 0 8px 0;
    font-size: 0.95rem;
    font-weight: 600;
    color: #fff;
}

.product-stats {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.sold-count {
    font-size: 0.8rem;
    color: #28a745;
    font-weight: 500;
}

.price {
    font-size: 0.85rem;
    color: #666;
    font-weight: 600;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 4px;
}

.product-rating .fa-star {
    font-size: 0.7rem;
    color: #ddd;
}

.product-rating .fa-star.filled {
    color: #ffc107;
}

.rating-value {
    font-size: 0.8rem;
    color: #9ca3af;
}

.analytics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 20px;
}

.analytics-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.analytics-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #333 0%, #666 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.analytics-info {
    flex: 1;
}

.analytics-value {
    font-size: 1.4rem;
    font-weight: 700;
    color: #fff;
    margin-bottom: 2px;
}

.analytics-label {
    font-size: 0.8rem;
    color: #9ca3af;
    margin-bottom: 4px;
}

.analytics-change {
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 2px;
}

.analytics-change.positive {
    color: #28a745;
}

.analytics-change.negative {
    color: #dc3545;
}

.activity-timeline {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 16px;
    background: rgba(255, 255, 255, 0.03);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.activity-icon.success {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.activity-icon.info {
    background: rgba(108, 117, 125, 0.2);
    color: #6c757d;
}

.activity-icon.primary {
    background: rgba(52, 58, 64, 0.2);
    color: #343a40;
}

.activity-content {
    flex: 1;
}

.activity-content p {
    margin: 0 0 4px 0;
    font-size: 0.9rem;
    color: #fff;
    line-height: 1.4;
}

.activity-time {
    font-size: 0.75rem;
    color: #9ca3af;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state h4 {
    margin: 0 0 8px 0;
    color: #fff;
    font-size: 1.1rem;
}

.empty-state p {
    margin: 0;
    font-size: 0.9rem;
}

.view-all {
    font-size: 0.85rem;
    color: #666;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.view-all:hover {
    color: #999;
    text-decoration: underline;
}

.card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.card-header .card-icon {
    margin-right: 12px;
}

.card-header h2 {
    margin: 0;
    flex: 1;
}

@media (max-width: 768px) {
    .welcome-section {
        padding: 20px;
    }

    .welcome-title {
        font-size: 1.8rem;
    }

    .welcome-stats {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .action-grid {
        grid-template-columns: 1fr;
    }

    .analytics-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .product-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>
