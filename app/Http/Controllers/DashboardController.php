<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Analytics data for sellers
        if ($user->role === 'seller') {
            $currentMonth = \Carbon\Carbon::now()->format('Y-m');
            $lastMonth = \Carbon\Carbon::now()->subMonth()->format('Y-m');

            $currentRevenue = \App\Models\OrderHeader::where('seller_id', $user->id)
                ->where('status', 'delivered')
                ->where('created_at', 'like', $currentMonth . '%')
                ->sum('total_price');

            $lastRevenue = \App\Models\OrderHeader::where('seller_id', $user->id)
                ->where('status', 'delivered')
                ->where('created_at', 'like', $lastMonth . '%')
                ->sum('total_price');

            if ($lastRevenue > 0) {
                $growth = (($currentRevenue - $lastRevenue) / $lastRevenue) * 100;
            } else {
                $growth = 100; // If no revenue last month, show 100% growth
            }

            $totalOrders = \App\Models\OrderHeader::where('seller_id', $user->id)->count();
            $deliveredOrders = \App\Models\OrderHeader::where('seller_id', $user->id)->where('status', 'delivered')->count();
            $totalRevenue = \App\Models\OrderHeader::where('seller_id', $user->id)->where('status', 'delivered')->sum('total_price');

            $analytics = [
                'pageViews' => rand(100, 999), // Placeholder - implement actual tracking later
                'visitors' => rand(50, 200), // Placeholder - implement actual tracking later
                'conversions' => $totalOrders,
                'avgRating' => $user->products->avg('rating') ?: 0,
                'stockInCount' => $user->products->where('stock', '>', 0)->count(),
                'stockTotalCount' => $user->products->count(),
                'revenueGrowth' => $growth,
                'currentRevenue' => $currentRevenue,
                'totalRevenue' => $totalRevenue,
                'totalOrders' => $totalOrders,
                'deliveredOrders' => $deliveredOrders,
            ];
        } else {
            $analytics = null;
        }

        return view('dashboard', compact('user', 'analytics'));
    }

    public function profile()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function uploadProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if exists
            if ($user->profile_photo && \Storage::disk('public')->exists($user->profile_photo)) {
                \Storage::disk('public')->delete($user->profile_photo);
            }

            // Store new profile photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');

            // Update user profile photo
            $user->update(['profile_photo' => $path]);
        }

        return redirect()->back()->with('success', 'Profile photo updated successfully!');
    }
}
