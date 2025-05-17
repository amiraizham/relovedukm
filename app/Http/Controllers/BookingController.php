<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;

class BookingController extends Controller
{
    public function request($productId) {
        Booking::create([
            'product_id' => $productId,
            'buyer_matricnum' => Auth::id(),
            'status' => 'pending',
        ]);
        return back()->with('success', 'Booking requested.');
    }

    public function sellerBookings() {
        $bookings = Booking::whereHas('product', fn($q) => $q->where('matricnum', Auth::user()->matricnum))
            ->with('product', 'buyer')->get();
        return view('bookings.seller', compact('bookings'));
    }

    public function approve($id) {
        $booking = Booking::findOrFail($id);
        $booking->status = 'approved';
        $booking->save();
        return back()->with('success', 'Booking approved');
    }

    public function reject($id) {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';
        $booking->save();
        return back()->with('info', 'Booking rejected');
    }

    /*public function confirm($id) {
        $booking = Booking::where('id', $id)->where('buyer_matricnum', Auth::id())->firstOrFail();
        $booking->status = 'confirmed';
        $booking->save();
        return back()->with('success', 'Purchase confirmed');
    }*/

    // BookingController.php
public function markSold($bookingId)
{
    // Find the booking by ID
    $booking = Booking::findOrFail($bookingId);

    // Ensure that the current user is the seller
    if ($booking->seller_matricnum !== Auth::user()->matricnum) {
        return redirect()->back()->with('error', 'You are not authorized to mark this booking as sold.');
    }

    // Update the booking status to 'sold'
    $booking->status = 'sold';
    $booking->save();

    // Mark the product as sold
    $product = $booking->product;
    $product->stock_status = 'sold';
    $product->save();

    return back()->with('success', 'Product and booking marked as sold.');
}


    public function store($productId, Request $request)
{
    // Ensure the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to book a product.');
    }

    // Get the product
    $product = Products::findOrFail($productId);

    // Ensure that the product is not already sold
    if ($product->stock_status === 'sold') {
        return back()->with('error', 'This product has already been sold.');
    }

    // Check if the user is the buyer and not the seller
    if ($product->matricnum === Auth::user()->matricnum) {
        return back()->with('error', 'You cannot book your own product.');
    }

    // Create a new booking entry
    $booking = new Booking();
    $booking->product_id = $product->id;
    $booking->buyer_matricnum = Auth::user()->matricnum;
    $booking->seller_matricnum = $product->matricnum;  // Assign the seller's matricnum
    $booking->status = 'pending'; // default to 'pending'
    $booking->save();

    // Notify the seller about the new booking request (can be done via email or another notification system)
    // In this case, you can simply pass this data to the `sellerNotifications` in the view.

    return back()->with('success', 'Your booking request has been sent to the seller.');
}

}

