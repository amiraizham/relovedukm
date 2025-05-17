<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Products;
use App\Models\Booking;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Cart;
use App\Models\Review;
use Illuminate\Support\Facades\Log;



class ProfileController extends Controller
{
    public function show()
    {
        // Fetch the currently authenticated user with their products
        $user = Auth::user();
        /*$user->load(['products' => function ($query) {
            $query->where('is_approved', 1);
        }]);*/

        $user->load([
            'products' => function ($query) {
                $query->where('is_approved', 1); // Only approved products
            },
            'products.reviews' => function ($query) {
                $query->with('reviewer'); // Eager load the reviewer (buyer) relationship
            }
        ]);

        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:15',
        'bio' => 'nullable|string|max:500',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $user->name = $request->input('name');
    $user->phone = $request->input('phone');
    $user->bio = $request->input('bio');

    // ✅ Handle avatar upload and DELETE old one
    if ($request->hasFile('avatar')) {

        // Delete old avatar if it exists and is from S3
        if ($user->avatar && str_contains($user->avatar, '.amazonaws.com')) {
            $oldAvatarPath = parse_url($user->avatar, PHP_URL_PATH);
            $oldAvatarKey = ltrim($oldAvatarPath, '/');
            Storage::disk('s3')->delete($oldAvatarKey);
        }

        // Upload new avatar
        $file = $request->file('avatar');
        $fileKey = 'avatars/' . time() . '-' . $file->getClientOriginalName();
        Storage::disk('s3')->put($fileKey, file_get_contents($file));

        $imageUrl = 'https://' 
            . config('filesystems.disks.s3.bucket') 
            . '.s3.' 
            . config('filesystems.disks.s3.region') 
            . '.amazonaws.com/' 
            . $fileKey;
        
        $user->avatar = $imageUrl;
    }

    $user->save();

    return redirect()->route('profile.show')->with('success', 'Profile updated!');
}


    // ✅ New: View a seller's public profile
    public function viewSellerProfile($matricnum)
    {
        $user = User::with(['products' => function ($query) {
            $query->where('is_approved', 1);
        }])->where('matricnum', $matricnum)->firstOrFail();

        return view('profile.show', compact('user'));
    }

    public function bookings()
{
    // Get all the bookings for the authenticated user
   // Get all the bookings for the authenticated user
   $bookings = Booking::where('buyer_matricnum', Auth::user()->matricnum)
   ->with('product') // Ensure the related products are eager loaded
   ->get();

   dd($bookings);  // Debug here


return view('profile.bookings', compact('bookings'));
}

public function bookingsForSeller()
{
    $sellerMatricnum = Auth::user()->matricnum;
    $bookings = Booking::where('seller_matricnum', $sellerMatricnum)->with('product', 'buyer')->get();

    return view('profile.seller_bookings', compact('bookings'));
}

/*public function showProfile($matricnum)
{
    $user = User::where('matricnum', $matricnum)->firstOrFail();

    // Eager load products and reviews for the user
    $user->load([
        'products' => function ($query) {
            $query->where('is_approved', 1)->where('stock_status', 'sold');
        },
        'products.reviews' => function ($query) {
            $query->with('reviewer'); // Eager load reviewer (buyer) relationship
        }
    ]);

    return view('profile.show', compact('user'));
}*/










}
