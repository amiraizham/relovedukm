<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Google\Cloud\Vision\V1\ImageAnnotatorClient;
use Google\Cloud\Core\ExponentialBackoff;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;



class ProductsManager extends Controller
{
    public function index()
    {
        $category = request('category');
        $sort = request('sort'); // new
    
        $products = Products::where('is_approved', 1)
            // Exclude products with the status 'sold'
            ->where('stock_status', '!=', 'sold')
            ->when($category, function ($query, $category) {
                return $query->where('category', $category);
            })
            ->when($sort, function ($query, $sort) {
                if ($sort === 'latest') {
                    return $query->orderBy('created_at', 'desc');
                } elseif ($sort === 'price_low_high') {
                    return $query->orderBy('price', 'asc');
                } elseif ($sort === 'price_high_low') {
                    return $query->orderBy('price', 'desc');
                }
            }, function ($query) {
                return $query->orderBy('created_at', 'desc'); // default sort
            })
            ->paginate(8)
            ->withQueryString();
    
        return view('products', compact('products'));
    }
    
    
    
    
    public function details($id, $slug)
    {
        $product = Products::with('user')->findOrFail($id); // Load seller
    
        $alreadyInCart = false;
        $cartItemId = null;
    
        if (Auth::check()) {
            $cartItem = Cart::where('matricnum', Auth::id())
                ->where('product_id', $product->id)
                ->first();
    
            if ($cartItem) {
                $alreadyInCart = true;
                $cartItemId = $cartItem->id;
            }
        }
    
        return view('details', compact('product', 'alreadyInCart', 'cartItemId'));
    }
    
    public function sellProduct(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to sell products.');
        }
    
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // Generate unique slug
        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Products::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
    
 

try {
    // Get the file from the request
    $file = $request->file('image');
    
    // Log the file information for debugging
    Log::info('File received for upload:', ['file' => $file]);

    // Check if the file is valid
    if (!$file->isValid()) {
        Log::error('Uploaded file is not valid.');
        return back()->with('error', 'Uploaded file is not valid.');
    }
    
    // Generate a unique file key for S3
    $fileKey = 'products/' . time() . '-' . $file->getClientOriginalName();
    
    // Log the file key to ensure it's generated correctly
    Log::info('Generated file key for upload:', ['fileKey' => $fileKey]);

    // Try uploading the file to S3
    $uploaded = Storage::disk('s3')->put($fileKey, fopen($file->getPathname(), 'r+'));
    
    // Log the result of the upload attempt
    if (!$uploaded) {
        Log::error('Image upload to S3 failed at put().', ['fileKey' => $fileKey]);
        return back()->with('error', 'Image upload to S3 failed at put().');
    }
    
    // Log the successful upload and URL generation
    $imageUrl = 'https://' . config('filesystems.disks.s3.bucket') . '.s3.' . config('filesystems.disks.s3.region') . '.amazonaws.com/' . $fileKey;
    Log::info('File uploaded successfully to S3', ['imageUrl' => $imageUrl]);
    
} catch (\Exception $e) {
    // Log any exceptions that occur during the upload process
    Log::error('S3 Upload Exception:', ['exception' => $e->getMessage()]);
    return back()->with('error', 'S3 Upload Exception: ' . $e->getMessage());
}

    
        // Save product
        $product = new Products();
        $product->matricnum = Auth::user()->matricnum;
        $product->title = $request->title;
        $product->slug = $slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category = $request->category;
        $product->image = $imageUrl;
        $product->is_approved = 0;
    
        if ($product->save()) {
            return redirect()->back()->with('success', 'Your product has been submitted and is being reviewed by the admin.');
        }
    
        return back()->with('error', 'Failed to list product. Please try again.');
    }
    

    // Show the edit form
public function edit($id)
{
    $product = Products::findOrFail($id);

    if ($product->matricnum !== Auth::user()->matricnum) {
        abort(403); // Forbidden
    }

    return view('edit', compact('product'));
}

// Handle update
public function update(Request $request, $id)
{
    $product = Products::findOrFail($id);

    if ($product->matricnum !== Auth::user()->matricnum) {
        abort(403);
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'price' => 'required|numeric',
        'category' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $product->title = $request->title;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->category = $request->category;

    if ($request->hasFile('image')) {
        $product->image = $request->file('image')->store('products', 'public');
    }

    $product->save();

    return redirect()->route('product.details', ['id' => $product->id, 'slug' => $product->slug])->with('success', 'Product updated!');
}

// Handle delete
public function delete($id)
{
    $product = Products::findOrFail($id);

    if ($product->matricnum !== Auth::user()->matricnum) {
        abort(403);
    }

    $product->delete();

    return redirect()->route('home')->with('success', 'Product deleted.');
}


    public function showSellForm(){
    return view('sell'); // Load the sell page
}

public function search(Request $request){
    $query = $request->input('query');
    
    // Search in product titles and descriptions
    $products = Products::where('title', 'like', '%' . $query . '%')->paginate(12);


    return view('search', compact('products'));
}

public function storeBooking(Request $request, $productId)
{
    // Fetch the product and ensure it exists
    $product = Products::findOrFail($productId);

    // Create the booking
    $booking = new Booking();
    $booking->product_id = $product->id;
    $booking->buyer_matricnum = Auth::user()->matricnum;
    $booking->seller_matricnum = $product->matricnum; // Seller's matricnum
    $booking->status = 'pending'; // Default status is pending
    $booking->save();

    // Redirect with success message
    return redirect()->route('profile.bookings')->with('success', 'Booking successfully created. Awaiting seller approval.');
}


public function approveBooking($bookingId)
{
    // Get the booking
    $booking = Booking::findOrFail($bookingId);

    // Ensure the user is the seller of the product
    if ($booking->product->matricnum !== Auth::user()->matricnum) {
        return back()->with('error', 'You are not the seller of this product.');
    }

    // Change booking status to 'approved'
    $booking->status = 'approved';
    $booking->save();

    return back()->with('success', 'Booking has been approved.');
}

public function markAsSold($productId)
{
    // Find the product by its ID
    $product = Products::findOrFail($productId);

    // Ensure that the current authenticated user is the seller of the product
    if ($product->matricnum !== Auth::user()->matricnum) {
        return back()->with('error', 'You are not the seller of this product.');
    }

    // Mark the product as sold
    $product->stock_status = 'sold';
    $product->save();

    return back()->with('success', 'Product has been marked as sold.');
}





}