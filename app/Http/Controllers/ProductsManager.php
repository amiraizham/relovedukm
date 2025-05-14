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


class ProductsManager extends Controller
{
    public function index()
    {
        $category = request('category');
        $sort = request('sort'); // new
    
        $products = Products::where('is_approved', 1)
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
    
        // Upload to S3
        try {
            $file = $request->file('image');
    
            if (!$file->isValid()) {
                return back()->with('error', 'Uploaded file is not valid.');
            }
    
            $fileKey = 'products/' . time() . '-' . $file->getClientOriginalName();
            $uploaded = Storage::disk('s3')->put($fileKey, fopen($file->getPathname(), 'r+'));
    
            if (!$uploaded) {
                return back()->with('error', 'Image upload to S3 failed at put().');
            }
    
            $imageUrl = 'https://' . config('filesystems.disks.s3.bucket') . '.s3.' . config('filesystems.disks.s3.region') . '.amazonaws.com/' . $fileKey;
        } catch (\Exception $e) {
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

public function markAsSold($id)
{
    $product = Products::findOrFail($id);

    // Ensure only owner can mark as sold
    if (Auth::user()->matricnum !== $product->matricnum) {
        abort(403);
    }

    $product->stock_status = 'sold';
    $product->save();

    return back()->with('success', 'Product marked as sold.');
}



}