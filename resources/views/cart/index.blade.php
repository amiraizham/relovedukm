@extends('layouts.default')
@section('title', 'Your Cart')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Your Shopping Cart</h1>

    @if($cartItems->isEmpty())
        <p class="text-center text-gray-600">Your cart is empty.</p>
    @else
        <div class="space-y-6">
            @foreach ($cartItems as $item)
                <div class="flex items-center justify-between bg-white shadow-md rounded-lg p-4">
                    <!-- Product Info -->
                    <div class="flex items-center gap-4">
                        <img src="{{ $item->product->image }}" alt="Product Image" class="w-24 h-24 object-cover rounded-md">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">{{ $item->product->title }}</h2>
                            <p class="text-pink-500 font-bold">RM {{ $item->product->price }}</p>
                            <p class="text-sm text-gray-500">Category: {{ $item->product->category }}</p>
                        </div>
                    </div>

                    <!-- Sold Tag -->
                    @if($item->product->stock_status === 'sold')
                        <span class="text-white bg-red-500 px-2 py-1 rounded-full text-xs font-semibold">Sold</span>
                    @endif

                    <!-- Remove from Cart -->
                    <form action="{{ route('cart.remove', ['id' => $item->id, 'slug' => Str::slug($item->product->title)]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_to" value="cart">
                        <button class="text-red-600 hover:text-red-800 font-medium">Remove</button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Optional: Total Price -->
        <div class="mt-10 text-right">
            <p class="text-xl font-bold text-gray-700">
                Total: RM {{ $cartItems->sum(fn($item) => $item->product->price) }}
            </p>
        </div>
    @endif
</div>
@endsection
