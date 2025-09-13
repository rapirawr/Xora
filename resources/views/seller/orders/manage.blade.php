@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="neon-text">Manage Orders</h1>
        <p class="neon-subtext">Process and track customer orders for your products.</p>
    </div>

    @if($orderHeaders->count() > 0)
        @foreach($orderHeaders as $orderHeader)
        <div class="dashboard-card">
            <div class="order-header">
                <h3>Order #{{ $orderHeader->order_number ?: $orderHeader->id }}</h3>
                <div class="order-info">
                    <span><strong>Customer:</strong> {{ $orderHeader->user->name }}</span>
                    <span><strong>Date:</strong> {{ $orderHeader->created_at->format('d M Y H:i') }}</span>
                    <span><strong>Status:</strong>
                        <span class="status-{{ $orderHeader->status }}">
                            {{ ucfirst($orderHeader->status) }}
                        </span>
                    </span>
                    <span><strong>Total:</strong> Rp{{ number_format($orderHeader->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="order-items">
                <h4>Order Items:</h4>
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderHeader->orders as $order)
                        <tr>
                            <td>
                                {{ $order->product->name }}
                                @if($order->variant)
                                    <br><small class="variant-info">{{ $order->variant->variant_name }}: {{ $order->variant->variant_value }}</small>
                                @endif
                            </td>
                            <td>{{ $order->quantity }}</td>
                            <td>Rp{{ number_format($order->variant ? $order->variant->price : $order->product->price, 0, ',', '.') }}</td>
                            <td>Rp{{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orderHeader->shipping_address)
            <div class="shipping-info">
                <h4>Shipping Address:</h4>
                <p>{{ $orderHeader->shipping_address }}</p>
            </div>
            @endif

            <div class="order-actions">
                @if($orderHeader->status === 'pending')
                    <form action="{{ route('seller.orders.update-status', $orderHeader) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="processing">
                        <button type="submit" class="btn btn-primary">Mark as Processing</button>
                    </form>
                    @elseif($orderHeader->status === 'processing')
                        <form action="{{ route('seller.orders.update-status', $orderHeader) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="shipped">
                            {{-- Remove manual tracking number input --}}
                            {{-- <input type="text" name="tracking_number" placeholder="Tracking Number" required> --}}
                            <button type="submit" class="btn btn-success">Mark as Shipped</button>
                        </form>
                @elseif($orderHeader->status === 'shipped')
                    <form action="{{ route('seller.orders.update-status', $orderHeader) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="delivered">
                        <button type="submit" class="btn btn-success">Mark as Delivered</button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div class="dashboard-card">
            <p>No orders found.</p>
        </div>
    @endif
</div>
@endsection

<style>
.status-pending { color: #ffc107; }
.status-processing { color: #007bff; }
.status-shipped { color: #28a745; }
.status-delivered { color: #17a2b8; }
.status-cancelled { color: #dc3545; }

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.order-info {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.order-items-table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
}

.order-items-table th, .order-items-table td {
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.shipping-info {
    margin: 20px 0;
    padding: 15px;
    border-radius: 5px;
}

.order-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-primary { background: #007bff; color: white; }
.btn-success { background: #28a745; color: white; }

.variant-info {
    color: #666;
    font-style: italic;
}
</style>

