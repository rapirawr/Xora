@extends("layouts.app")
<div class="dashboard-grid">
    <!-- Developer Tools Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-code card-icon"></i>
            <h2 class="neon-subtext">Developer Portal</h2>
        </div>
        <div class="card-content">
            <div class="dev-tools">
                <a href="#" class="dev-tool-btn">
                    <i class="fas fa-key"></i>
                    <span>API Keys</span>
                </a>
                <a href="#" class="dev-tool-btn">
                    <i class="fas fa-file-alt"></i>
                    <span>System Logs</span>
                </a>
                <a href="#" class="dev-tool-btn">
                    <i class="fas fa-book"></i>
                    <span>Documentation</span>
                </a>
                <a href="{{ route('developer.users') }}" class="dev-tool-btn">
                    <i class="fas fa-users-cog"></i>
                    <span>Manage Users</span>
                </a>
            </div>
        </div>
    </div>


    <!-- System Status Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-server card-icon"></i>
            <h2 class="neon-subtext">System Status</h2>
        </div>
        <div class="card-content">
            <div class="system-metrics">
                <div class="metric-item">
                    <div class="metric-status online">
                        <i class="fas fa-circle"></i>
                        <span>Online</span>
                    </div>
                    <div class="metric-label">Server Status</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ \App\Models\User::count() }}</div>
                    <div class="metric-label">Total Users</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ \App\Models\Product::count() }}</div>
                    <div class="metric-label">Total Products</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ \App\Models\Product::sum('sold') }}</div>
                    <div class="metric-label">Total Sales</div>
                </div>
                <div class="metric-item">
                    <div class="metric-value">{{ \App\Models\User::where('role', 'seller')->count() }}</div>
                    <div class="metric-label">Total Sellers</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Management Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-database card-icon"></i>
            <h2 class="neon-subtext">Database</h2>
        </div>
        <div class="card-content">
            <div class="db-tools">
                <div class="db-stat">
                    <span class="db-stat-label">Users Table:</span>
                    <span class="db-stat-value">{{ \App\Models\User::count() }} records</span>
                </div>
                <div class="db-stat">
                    <span class="db-stat-label">Products Table:</span>
                    <span class="db-stat-value">{{ \App\Models\Product::count() }} records</span>
                </div>
                <div class="db-actions">
                    <button class="db-btn backup-btn">
                        <i class="fas fa-download"></i>
                        Backup
                    </button>
                    <button class="db-btn optimize-btn">
                        <i class="fas fa-wrench"></i>
                        Optimize
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Card -->
    <div class="dashboard-card animated-card">
        <div class="card-header">
            <i class="fas fa-chart-line card-icon"></i>
            <h2 class="neon-subtext">Analytics</h2>
        </div>
        <div class="card-content">
            <div class="analytics-grid">
                <div class="analytics-item">
                    <div class="analytics-value">{{ \App\Models\User::where('role', 'seller')->count() }}</div>
                    <div class="analytics-label">Active Sellers</div>
                </div>
                <div class="analytics-item">
                    <div class="analytics-value">{{ \App\Models\User::where('role', 'user')->count() }}</div>
                    <div class="analytics-label">Registered Users</div>
                </div>
                <div class="analytics-item">
                    <div class="analytics-value">{{ \App\Models\Product::where('stock', '>', 0)->count() }}</div>
                    <div class="analytics-label">Products in Stock</div>
                </div>
            </div>
        </div>
    </div>
</div>
