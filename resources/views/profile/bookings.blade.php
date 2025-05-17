@extends('layouts.default')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">My Bookings</h2>

    <section class="bg-white p-6 rounded-2xl shadow-md">
        @if($bookings->isEmpty())
            <p class="text-center text-gray-600">You have no bookings yet.</p>
        @else
            <div class="space-y-6">
                @foreach($bookings as $booking)
                    <div class="bg-gray-50 p-4 rounded-lg shadow-md">
                        <h3 class="text-xl font-bold text-gray-800">{{ $booking->product->title }}</h3>
                        <p class="text-gray-600">Status: <span class="font-semibold text-green-600">{{ ucfirst($booking->status) }}</span></p>
                        <p class="text-gray-600">Price: RM {{ $booking->product->price }}</p>
                        <!-- Optionally, add a link to the product details page -->
                        <a href="{{ route('product.details', ['id' => $booking->product->id, 'slug' => $booking->product->slug]) }}" class="text-[#E95670] hover:underline">
                            View Product Details
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
