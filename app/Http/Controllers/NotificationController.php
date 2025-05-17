<?php

// NotificationController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get buyer notifications: bookings where the user is the buyer
        $buyerNotifications = Booking::where('buyer_matricnum', $user->matricnum)
            ->with('product', 'seller') // Include related product and seller data
            ->orderBy('created_at', 'desc')
            ->get();

        // Get seller notifications: bookings where the user is the seller
        $sellerNotifications = Booking::where('seller_matricnum', $user->matricnum)
            ->with('product', 'buyer') // Include related product and buyer data
            ->orderBy('created_at', 'desc')
            ->get();

            // Fetch products rejected by admin where is_approved == 0
        $rejectedProducts = Products::where('is_approved', 0)
        ->where('matricnum', $user->matricnum) // Ensure the rejected product belongs to the logged-in user
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('notifications.list', compact('buyerNotifications', 'sellerNotifications', 'rejectedProducts'));
    }

    public function updateStatus(Request $request, $id, $status)
    {
        $booking = Booking::findOrFail($id);
        
        // Ensure that the current user is the seller
        if ($booking->seller_matricnum !== Auth::user()->matricnum) {
            return back()->with('error', 'You are not the seller of this product.');
        }

        // Update the booking status
        $booking->status = $status;
        $booking->save();

        // Redirect to the notifications page with success
        return redirect()->route('notifications.index')->with('success', 'Booking status updated successfully.');
    }
}



