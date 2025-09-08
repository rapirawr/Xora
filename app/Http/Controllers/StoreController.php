<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the products based on filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Mulai dengan query dasar untuk semua produk
        $query = Product::query();

        // Filter berdasarkan kategori
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter berdasarkan rentang harga
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price', [$request->input('min_price'), $request->input('max_price')]);
        }

        // Filter berdasarkan status (available = in stock)
        if ($request->filled('status') && $request->input('status') == 'available') {
            // Filter produk yang masih ada stoknya
            $query->where('stock', '>', 0);
        }

        // Ambil produk yang sudah difilter dengan relasi user
        $products = $query->with('user')->get();

        // Kirim data produk ke view 'store'
        return view('store', compact('products'));
    }
}