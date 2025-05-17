@extends('layouts.default')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">

    <!-- Profile Section -->
    <div class="bg-white p-6 rounded-2xl shadow-md mb-10 flex items-center gap-6">
        <div class="flex-shrink-0">
            <img src="{{ $user->avatar ?? asset('assets/img/default-profile.jpg')}}" alt="Avatar" class="w-24 h-24 object-cover rounded-full border-2 border-[#E95670]">
        </div>
        <div class="flex-1 space-y-2 relative">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}'s Profile</h2>
                    <p class="text-gray-600"><strong>Matric Number:</strong> {{ $user->matricnum }}</p>
                    <p class="text-gray-600"><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                    <p class="text-gray-600"><strong>Bio:</strong> {{ $user->bio ?? 'No bio added' }}</p>
                </div>

                @if(Auth::check() && Auth::user()->matricnum === $user->matricnum)
                    <form action="{{ route('logout') }}" method="POST" onsubmit="return confirmLogout(event);">
                        @csrf
                        <button type="submit" title="Logout" class="text-gray-400 hover:text-[#E95670] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mt-1" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v14a2 2 0 002 2h4a2 2 0 002-2v-1" />
                            </svg>
                        </button>
                    </form>
                @endif
            </div>

            @if(Auth::check() && Auth::user()->matricnum === $user->matricnum)
                <!-- Edit Profile Button -->
                <a href="{{ route('profile.update') }}" class="inline-block text-white bg-[#E95670] px-4 py-2 rounded-lg hover:bg-[#B34270] mt-2">
                    Edit Profile
                </a>
            @else
                <a href="{{ route('chat.seller', ['seller_id' => $user->matricnum]) }}" 
                   class="inline-block text-white bg-[#E95670] px-4 py-2 rounded-lg hover:bg-[#B34270] mt-2">
                    Message Seller
                </a>
            @endif
        </div>
    </div>

    <!-- My Listings Section -->
    <section>
        <h3 class="text-xl font-semibold text-gray-800 mb-4">My Listings</h3>
        @if($user->products->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($user->products as $product)
                    <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">
                        <h4 class="text-lg font-bold text-gray-800 mb-2">{{ $product->title }}</h4>
                        <p class="text-gray-600 mb-2">Price: <span class="text-[#E95670] font-semibold">RM {{ $product->price }}</span></p>
                        <a href="{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}" 
                           class="text-sm text-[#E95670] hover:underline font-medium">
                            View Details →
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">You have no listings yet. Start selling something today!</p>
        @endif
    </section>

    <!-- Buyer's Reviews Section -->
    <section>
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Buyer's Reviews</h3>
        @if($user->products->pluck('reviews')->flatten()->isNotEmpty()) <!-- Check if reviews exist -->
            <div class="space-y-4">
                @foreach($user->products as $product)
                    @foreach($product->reviews as $review)
                        <div class="bg-white p-4 rounded-xl shadow-md">
                            <h4 class="font-bold text-gray-800">{{ $review->reviewer->name }}'s Review</h4>
                            <p class="text-gray-600">Rating: {{ $review->rating }}/5</p>
                            <p class="text-gray-600">{{ $review->comment }}</p>
                        </div>
                    @endforeach
                @endforeach
            </div>
        @else
            <p class="text-gray-500">No reviews yet. This product hasn't been reviewed by any buyers.</p>
        @endif
    </section>

</div>
@endsection
