<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Display the user's cart.
     */
    public function index(): View
    {
        $user = Auth::user();
        $cart = $user->getOrCreateCart()->load('items.product');

        return view('cart.index', compact('cart'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $user = Auth::user();
        $cart = $user->getOrCreateCart();

        // Determine stock and price based on variant or product
        $variant = null;
        $stock = $product->stock;
        $price = $product->price;

        if ($request->variant_id) {
            $variant = \App\Models\ProductVariant::find($request->variant_id);
            if ($variant && $variant->product_id === $product->id) {
                $stock = $variant->stock ?: $product->stock;
                $price = $variant->price ?: $product->price;
            } else {
                return redirect()->back()->with('error', 'Invalid variant selected.');
            }
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $stock,
        ]);

        // Check if product/variant combination is already in cart
        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem->quantity + $request->quantity;

            if ($newQuantity > $stock) {
                $message = 'Not enough stock available.';

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ]);
                }

                return redirect()->back()->with('error', $message);
            }

            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            $cart->items()->create([
                'product_id' => $product->id,
                'variant_id' => $request->variant_id,
                'quantity' => $request->quantity,
                'price' => $price,
            ]);
        }

        $message = 'Product added to cart successfully!';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(Request $request, CartItem $cartItem): RedirectResponse
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock,
        ]);

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(CartItem $cartItem): RedirectResponse
    {
        // Ensure the cart item belongs to the authenticated user
        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart successfully!');
    }

    /**
     * Clear all items from the cart.
     */
    public function clear(): RedirectResponse
    {
        $user = Auth::user();
        $cart = $user->cart;

        if ($cart) {
            $cart->items()->delete();
        }

        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }

    /**
     * Process checkout and create orders.
     */
    public function checkout(): RedirectResponse
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check stock availability
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('cart.index')->with('error', "Not enough stock for {$item->product->name}.");
            }
        }

        // Group cart items by seller
        $itemsBySeller = $cart->items->groupBy(function ($item) {
            return $item->product->user_id;
        });

        foreach ($itemsBySeller as $sellerId => $items) {
            $totalPrice = $items->sum(function ($item) {
                return $item->subtotal;
            });

            // Create order header per seller
            $orderHeader = \App\Models\OrderHeader::create([
                'user_id' => $user->id,
                'seller_id' => $sellerId,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'purchased_at' => now(),
            ]);

            // Create orders linked to order header
            foreach ($items as $item) {
                \App\Models\Order::create([
                    'user_id' => $user->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->subtotal,
                    'status' => 'pending',
                    'purchased_at' => now(),
                    'order_header_id' => $orderHeader->id,
                ]);

                // Update product stock and sold count
                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('sold', $item->quantity);
            }
        }

        // Clear the cart
        $cart->items()->delete();

        return redirect()->route('dashboard')->with('success', 'Purchase completed successfully! Thank you for your order.');
    }

    /**
     * Show the checkout page.
     */
    public function showCheckout()
    {
        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('checkout', compact('cart'));
    }

    /**
     * Process the checkout with shipping and payment information.
     */
    public function processCheckout(Request $request): RedirectResponse
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cod,bank_transfer',
        ]);

        $user = Auth::user();
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Check stock availability
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->route('checkout.show')->with('error', "Not enough stock for {$item->product->name}.");
            }
        }

        // Group cart items by seller
        $itemsBySeller = $cart->items->groupBy(function ($item) {
            return $item->product->user_id;
        });

        foreach ($itemsBySeller as $sellerId => $items) {
            $totalPrice = $items->sum(function ($item) {
                return $item->subtotal;
            });

            // Create order header per seller
            $orderHeader = \App\Models\OrderHeader::create([
                'user_id' => $user->id,
                'seller_id' => $sellerId,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'purchased_at' => now(),
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
            ]);

            // Create orders linked to order header
            foreach ($items as $item) {
                // Remove unique constraint check to allow multiple orders for same user and product
                \App\Models\Order::create([
                    'user_id' => $user->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'total_price' => $item->subtotal,
                    'status' => 'pending',
                    'purchased_at' => now(),
                    'order_header_id' => $orderHeader->id,
                ]);

                // Update product stock and sold count
                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('sold', $item->quantity);
            }
        }

        // Clear the cart
        $cart->items()->delete();

        return redirect()->route('dashboard')->with('success', 'Purchase completed successfully! Thank you for your order.');
    }
}
