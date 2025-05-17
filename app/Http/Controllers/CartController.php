<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart($id, $slug)
    {
        $product = Products::findOrFail($id);

        if (Auth::check()) {
            $matricnum = Auth::user()->matricnum;

            // Check if product already in cart
            $existingCartItem = Cart::where('matricnum', $matricnum)
                ->where('product_id', $product->id)
                ->first();

            if ($existingCartItem) {
                return redirect()->route('product.details', ['id' => $id, 'slug' => $slug])
                    ->with('error', 'This product is already in your cart.');
            }

            // Add to cart
            Cart::create([
                'matricnum' => $matricnum,
                'product_id' => $product->id,
            ]);

            return redirect()->route('product.details', ['id' => $id, 'slug' => $slug])
                ->with('success', 'Product added to cart!');
        }

        return redirect()->route('login')->with('error', 'Please log in to add items to the cart.');
    }

    public function remove($id, $slug, Request $request)
    {
        $item = Cart::findOrFail($id);
    
        if ($item->matricnum !== Auth::user()->matricnum) {
            return redirect()->route('cart.index')->with('error', 'Unauthorized action.');
        }
    
        $product = $item->product;
        $item->delete();
    
        // Check redirect source
        $redirectTarget = $request->input('redirect_to');
    
        if ($redirectTarget === 'product') {
            return redirect()->route('product.details', [
                'id' => $product->id,
                'slug' => Str::slug($product->title),
            ])->with('success', 'Item removed from cart.');
        }
    
        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
    
    public function index()
    {
        if (Auth::check()) {
            $matricnum = Auth::user()->matricnum;
    
            $cartItems = Cart::where('matricnum', $matricnum)
                ->with('product')  // Make sure to load the product
                ->get();
    
            return view('cart.index', compact('cartItems'));
        }
    
        return redirect()->route('login')->with('error', 'Please log in to view your cart.');
    }
    

    /**
     * Show the product details with cart status
     */
    public function showProductDetails($id, $slug)
{
    $product = Products::findOrFail($id);
    $alreadyInCart = false;
    $cartItemId = null;

    if (Auth::check() && Auth::user()->matricnum !== $product->matricnum) {
        $cartItem = Cart::where('matricnum', Auth::user()->matricnum)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $alreadyInCart = true;
            $cartItemId = $cartItem->id;
        }
    }

    return view('details', compact('product', 'alreadyInCart', 'cartItemId'));
}

}
