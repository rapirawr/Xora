<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua produk dari database
        $products = Product::all();

        // Kirim data produk ke view
        return view('store', compact('products'));
    }
    
    // Metode show() yang sudah ada
    public function show(Product $product)
    {
        // Load the user, images, and variants relationships to display seller information, gallery, and variants
        $product->load(['user', 'images', 'variants']);

        return view('product-preview', compact('product'));
    }

}
