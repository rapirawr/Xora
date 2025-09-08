<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SellerRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('seller.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($user->isSeller()) {
            return redirect()->route('store')->with('info', 'You are already a seller.');
        }

        DB::beginTransaction();

        try {
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('store-logos', 'public');
            }

            // Create store
            $store = Store::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'description' => $request->description,
                'logo' => $logoPath,
                'status' => 'active',
            ]);

            // Update user role to seller
            $user->role = 'seller';
            $user->save();

            DB::commit();

            // Redirect to product creation page for first product
            return redirect()->route('seller.products.create')->with('success', 'Seller registration successful. You can now add your first product.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'Failed to register as seller. Please try again.']);
        }
    }
}
