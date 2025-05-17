<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Products;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login'); // Load the admin login page
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'matricnum' => 'required|string',
            'password' => 'required|string',
        ]);

        // Debugging: Check what user is found
        $user = \App\Models\User::where('matricnum', $request->matricnum)->first();

        if (!$user) {
            return back()->withErrors(['matricnum' => 'Matric number not found.']);
        }

        if (!Auth::attempt(['matricnum' => $request->matricnum, 'password' => $request->password])) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        if ($user->role !== 'admin') {
            Auth::logout();
            return back()->withErrors(['matricnum' => 'You are not authorized as an admin.']);
        }

        return redirect()->route('admin.dashboard');
    }

    public function dashboard()
    {
        $pendingProducts = Products::where('is_approved', 0)->get();
        return view('admin.dashboard', compact('pendingProducts')); 
    }

    public function pendingProducts()
    {
        $products = Products::where('is_approved', 0)->get();
        return view('admin.pending-products', compact('products'));
    }

    public function preview($id)
    {
        $product = Products::with('user')->findOrFail($id);
        return view('admin.product_preview', compact('product'));
    }


    public function approveProduct($id)
    {
        $product = Products::findOrFail($id);
        $product->is_approved = 1;
        $product->save();

        return redirect()->route('admin.dashboard')->with('approved', 'Product has been approved successfully.');

    }

    public function rejectProduct($id)
    {
        $product = Products::findOrFail($id);
        $product->is_approved = 0;
        $product->save();

        return redirect()->route('admin.dashboard')->with('rejected', 'Product has been rejected.');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

}
