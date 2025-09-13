<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerStoreController extends Controller
{
    /**
     * Display the store page for a specific seller by username.
     *
     * @param string $usernameSeller
     * @return \Illuminate\View\View
     */
    public function show($usernameSeller)
    {
        // Find the seller user by username
        $seller = User::where('username', $usernameSeller)->where('role', 'seller')->first();

        if (!$seller) {
            // Seller not found, show error message
            return view('store.seller_not_found', ['usernameSeller' => $usernameSeller]);
        }

        // Get products for the seller
        $products = Product::where('user_id', $seller->id)->get();

        // Pass seller and products data to the view
        return view('store.seller_store', compact('seller', 'products'));
    }
}
