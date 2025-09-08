<?php

namespace App\Http\Controllers;

use App\Models\OrderHeader;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display the user's orders.
     */
    public function index(): View
    {
        $user = Auth::user();

        $orderHeaders = OrderHeader::where('user_id', $user->id)
            ->with(['seller', 'orders.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orderHeaders'));
    }

    /**
     * Show the details of a specific order.
     */
    public function show(OrderHeader $orderHeader): View
    {
        // Ensure the order belongs to the authenticated user
        if ($orderHeader->user_id !== Auth::id()) {
            abort(403);
        }

        $orderHeader->load(['seller', 'orders.product']);

        return view('orders.show', compact('orderHeader'));
    }

    /**
     * Cancel an order.
     */
    public function cancel(OrderHeader $orderHeader): RedirectResponse
    {
        // Ensure the order belongs to the authenticated user
        if ($orderHeader->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if the order can be cancelled
        if (!$orderHeader->canBeCancelled()) {
            return redirect()->back()->with('error', 'This order cannot be cancelled at this stage.');
        }

        // Update order status
        $orderHeader->update(['status' => 'cancelled']);
        $orderHeader->orders()->update(['status' => 'cancelled']);

        // Restore product stock
        foreach ($orderHeader->orders as $order) {
            $order->product->increment('stock', $order->quantity);
        }

        return redirect()->back()->with('success', 'Order has been cancelled successfully.');
    }

    /**
     * Mark an order as received (for delivered orders).
     */
    public function markAsReceived(OrderHeader $orderHeader)
    {
        // Ensure the order belongs to the authenticated user
        if ($orderHeader->user_id !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403);
        }

        // Check if the order can be marked as received
        if ($orderHeader->status !== 'shipped') {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'This order cannot be marked as received.']);
            }
            return redirect()->back()->with('error', 'This order cannot be marked as received.');
        }

        $orderHeader->update(['status' => 'delivered']);
        $orderHeader->orders()->update(['status' => 'delivered']);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Order marked as received successfully!']);
        }

        return redirect()->back()->with('success', 'Order marked as received successfully!');
    }
}
