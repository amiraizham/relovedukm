<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create($productId)
    {
        // Check if the user can review: confirmed status and buyer
        $canReview = Booking::where('product_id', $productId)
            ->where('buyer_matricnum', Auth::user()->matricnum)
            ->where('status', 'sold') // Ensure only sold items can be reviewed
            ->exists();
    
        if (!$canReview) abort(403, 'Unauthorized');
    
        return view('reviews.create', compact('productId'));
    }
    
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);
    
        $product = Products::findOrFail($productId);
    
        // Store the review
        Review::create([
            'product_id' => $productId,
            'reviewer_matricnum' => Auth::user()->matricnum,
            'seller_matricnum' => $product->matricnum,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);
    
        // Redirect back to the product details page after review submission
        return redirect()->route('notifications.index', ['id' => $productId])
                         ->with('success', 'Thank you for your feedback!');
    }
    
}
