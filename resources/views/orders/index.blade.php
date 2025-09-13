@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="neon-text">My Orders</h1>
        <p class="neon-subtext">Track and manage your purchases</p>
    </div>

    @if($orderHeaders->count() > 0)
        @foreach($orderHeaders as $orderHeader)
        <div class="dashboard-card receipt-card">
            <div class="receipt-header">
                <h3>Order #{{ $orderHeader->order_number ?: $orderHeader->id }}</h3>
                <div class="receipt-info">
                    <div><strong>Seller:</strong> {{ $orderHeader->seller->name }}</div>
                    <div><strong>Date:</strong> {{ $orderHeader->created_at->format('d M Y H:i') }}</div>
                    <div><strong>Status:</strong> <span class="status-{{ $orderHeader->status }}">{{ ucfirst($orderHeader->status) }}</span></div>
                    <div><strong>Total:</strong> Rp{{ number_format($orderHeader->total_price, 0, ',', '.') }}</div>
                </div>
            </div>

            <div class="receipt-items">
                <h4>Items Purchased:</h4>
                <table class="receipt-items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderHeader->orders as $order)
                        <tr>
                            <td>
                                <div class="product-info">
                                    @if($order->product->image_url)
                                        <img src="{{ $order->product->image_url }}" alt="{{ $order->product->name }}" class="product-thumb">
                                    @endif
                                    <span>{{ $order->product->name }}</span>
                                    @if($order->variant)
                                        <br><small class="variant-info">{{ $order->variant->variant_name }}: {{ $order->variant->variant_value }}</small>
                                    @endif
                                </div>
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
            <div class="receipt-shipping">
                <h4>Shipping Address</h4>
                <p>{{ $orderHeader->shipping_address }}</p>
            </div>
            @endif

            @if($orderHeader->tracking_number)
            <div class="receipt-tracking">
                <h4>Tracking Number</h4>
                <p>{{ $orderHeader->tracking_number }}</p>
            </div>
            @endif

            <!-- Barcode section removed as per user request to shorten the page -->

            <div class="receipt-actions">
                @if($orderHeader->status === 'shipped')
                    <button type="button" class="btn btn-success" id="mark-received-{{ $orderHeader->id }}" onclick="markAsReceived({{ $orderHeader->id }})">
                        Mark as Received
                    </button>
                @elseif($orderHeader->canBeCancelled())
                    <form action="{{ route('orders.cancel', $orderHeader) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to cancel this order?')">
                            Cancel Order
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div class="dashboard-card">
            <p>You haven't placed any orders yet. <a href="{{ route('store') }}">Start shopping</a></p>
        </div>
    @endif
</div>
@endsection

<!-- JsBarcode Library for generating barcodes -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<style>
.status-pending { color: #ffc107; }
.status-processing { color: #007bff; }
.status-shipped { color: #28a745; }
.status-delivered { color: #17a2b8; }
.status-cancelled { color: #dc3545; }

    /* Receipt Card Styling */
    .receipt-card {
        background: #fff;
        border: 2px solid #333;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        font-family: 'Poppins', sans-serif;
        position: relative;
    }

.receipt-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #333, #666, #333);
}

.receipt-header {
    text-align: center;
    border-bottom: 1px dashed #666;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

.receipt-header h3 {
    color: #ffffffff;
    font-size: 1.4em;
    margin-bottom: 10px;
    font-weight: bold;
}

.receipt-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 10px;
    font-size: 0.9em;
}

.receipt-info div {
    display: flex;
    padding: 5px 0;
}

.receipt-items h4 {
    color: #ffffffff;
    font-size: 1.1em;
    margin-bottom: 10px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
}

.receipt-items-table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
    font-size: 0.9em;
}

.receipt-items-table th {
    padding: 10px 8px;
    text-align: left;
    font-weight: bold;
    border-bottom: 2px solid #333;
}

.receipt-items-table td {
    padding: 8px;
    border-bottom: 1px solid #eee;
}

.receipt-items-table tbody tr:last-child td {
    border-bottom: 2px solid #333;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.product-thumb {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.receipt-shipping, .receipt-tracking {
    margin: 15px 0;
    padding: 12px;
    background: linear-gradient(135deg, rgb(26, 26, 26) 0%, rgb(42, 42, 42) 100%);    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 0.9em;
}

.receipt-shipping h4, .receipt-tracking h4 {
    margin: 0 0 8px 0;
    color: #ffffffff;
    font-size: 1em;
}

.receipt-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: center;
    border-top: 1px dashed #666;
    padding-top: 15px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    font-family: Arial, sans-serif;
    font-weight: bold;
}

.btn-success { background: #28a745; color: white; }
.btn-danger { background: #dc3545; color: white; }

/* Barcode Styling */
.receipt-barcode {
    margin: 15px 0;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
    border-top: 1px dashed #666;
    border-bottom: 1px dashed #666;
}

.barcode-container {
    display: inline-block;
    padding: 10px;
    width: 100%;
    border: 1px solid #333;
    border-radius: 4px;
}

.barcode-container svg {
    display: block;
    margin: 0 auto 5px auto;
    width: 300px;
    height: 60px;
}

.barcode-number {
    font-family: 'Courier New', monospace;
    font-size: 1em;
    font-weight: bold;
    color: #ffffffff;
    letter-spacing: 1px;
}

/* Receipt-like dotted lines */
.receipt-card::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: repeating-linear-gradient(
        90deg,
        #333,
        #333 4px,
        transparent 4px,
        transparent 8px
    );
}

/* Dynamic Island Animations */
@keyframes dynamicIslandSlideIn {
    0% {
        opacity: 0;
        transform: translateY(-20px) scale(0.8);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes dynamicIslandSlideOut {
    0% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
    100% {
        opacity: 0;
        transform: translateY(-20px) scale(0.8);
    }
}

/* Dynamic Island Hover Effects */
.dynamic-island-notification:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 40px rgba(0,0,0,0.4);
    transition: all 0.2s ease;
}
</style>

<script>
function markAsReceived(orderId) {
    if (!confirm('Are you sure you want to mark this order as received?')) {
        return;
    }

    const button = document.getElementById('mark-received-' + orderId);
    const originalText = button.textContent;

    // Disable button and show loading
    button.disabled = true;
    button.textContent = 'Processing...';

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        button.disabled = false;
        button.textContent = originalText;
        showMessage('Security token missing. Please refresh the page.', 'error');
        return;
    }

    // Make AJAX request
    fetch('/orders/' + orderId + '/received', {
        method: 'PATCH',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Update status display
            const statusElement = button.closest('.dashboard-card').querySelector('.status-shipped');
            if (statusElement) {
                statusElement.textContent = 'Delivered';
                statusElement.className = 'status-delivered';
            }

            // Remove the button
            button.remove();

            // Show success message
            showMessage('Order marked as received successfully!', 'success');
        } else {
            throw new Error(data.message || 'Failed to mark as received');
        }
    })
    .catch(error => {
        button.disabled = false;
        button.textContent = originalText;
        showMessage('Failed to mark order as received. Please try again.', 'error');
    });
}

function showMessage(message, type) {
    // Remove any existing dynamic notifications
    const existingNotifications = document.querySelectorAll('.dynamic-notification');
    existingNotifications.forEach(notification => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    });

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'dynamic-notification hide';
    if (type === 'error') {
        notification.classList.add('error-notification');
    }

    // Create notification text element
    const notificationText = document.createElement('span');
    notificationText.className = 'notification-text';
    notificationText.textContent = message;

    // Add icon based on type
    const icon = document.createElement('span');
    icon.className = 'notification-icon';
    icon.textContent = type === 'success' ? '✓' : '⚠';

    notification.appendChild(icon);
    notification.appendChild(notificationText);

    // Add click to dismiss
    notification.addEventListener('click', () => {
        notification.classList.remove('show');
        notification.classList.add('hide');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    });

    document.body.appendChild(notification);

    // Show notification
    setTimeout(() => {
        notification.classList.remove('hide');
        notification.classList.add('show');
    }, 100);

    // Auto remove after 4 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            notification.classList.add('hide');
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 400);
        }
    }, 4000);
}

</script>
