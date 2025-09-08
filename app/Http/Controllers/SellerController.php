<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    // Halaman untuk mengelola produk penjual
    public function manageProducts()
    {
        // Ambil produk dari pengguna yang sedang login
        $products = Auth::user()->products;
        return view('seller.products.manage', compact('products'));
    }

    // Halaman untuk melihat laporan penjualan
    public function salesReports()
    {
        // Ambil data penjualan dan buat laporan
        return view('seller.reports.sales');
    }

    // Halaman untuk mengelola pesanan dari pembeli
    public function manageOrders()
    {
        $seller = Auth::user();

        // Ambil order headers yang terkait dengan produk penjual
        $orderHeaders = \App\Models\OrderHeader::where('seller_id', $seller->id)
            ->with(['user', 'orders.product'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('seller.orders.manage', compact('orderHeaders'));
    }

    // Halaman untuk menambahkan produk baru
    public function createProduct()
    {
        return view('seller.products.create');
    }

    // Menyimpan produk baru
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'sold' => 'nullable|integer|min:0',
            'stock' => 'nullable|integer|min:0',
            'category' => 'required|string|in:' . implode(',', array_keys(\App\Models\Product::getCategoryOptions())),
        ]);

        Auth::user()->products()->create($request->all());

        return redirect()->route('seller.products.manage')->with('success', 'Product created successfully!');
    }

    // Halaman edit produk
    public function editProduct(\App\Models\Product $product)
    {
        // Pastikan produk milik user yang sedang login
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        return view('seller.products.edit', compact('product'));
    }

    // Update produk
    public function updateProduct(Request $request, \App\Models\Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
            'sold' => 'nullable|integer|min:0',
            'stock' => 'nullable|integer|min:0',
            'category' => 'required|string|in:' . implode(',', array_keys(\App\Models\Product::getCategoryOptions())),
        ]);

        $product->update($request->all());

        return redirect()->route('seller.products.manage')->with('success', 'Product updated successfully!');
    }

    // Delete produk
    public function deleteProduct(\App\Models\Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('seller.products.manage')->with('success', 'Product deleted successfully!');
    }

    // Update order status
    public function updateOrderStatus(Request $request, \App\Models\OrderHeader $orderHeader)
    {
        // Ensure the order belongs to the authenticated seller
        if ($orderHeader->seller_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:processing,shipped,delivered',
        ]);

        // Validate status transitions
        $currentStatus = $orderHeader->status;
        $newStatus = $request->status;

        if (!in_array($newStatus, ['processing', 'shipped', 'delivered'])) {
            return redirect()->back()->with('error', 'Invalid status transition.');
        }

        // Check valid transitions
        $validTransitions = [
            'pending' => ['processing'],
            'processing' => ['shipped'],
            'shipped' => ['delivered'],
            'delivered' => [], // No further transitions allowed
        ];

        if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
            return redirect()->back()->with('error', 'Invalid status transition from ' . $currentStatus . ' to ' . $newStatus);
        }

        $updateData = ['status' => $newStatus];

        // Auto-generate tracking number when status changes to shipped
        if ($newStatus === 'shipped' && empty($orderHeader->tracking_number)) {
            $updateData['tracking_number'] = \App\Models\OrderHeader::generateTrackingNumber();
        }

        $orderHeader->update($updateData);

        // Update individual orders status as well
        $orderHeader->orders()->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
