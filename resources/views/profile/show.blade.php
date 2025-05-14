@extends('layouts.default')
@section('title', 'Profile - RelovedUKM')

@section('style')
<style>
    .swal2-popup {
        font-family: 'Poppins', sans-serif;
    }

    .custom-confirm-button {
        border: 2px solid #E95670 !important;
        background-color: transparent !important;
        color: #E95670 !important;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .custom-confirm-button:hover {
        background-color: #E95670 !important;
        color: white !important;
    }
</style>
@endsection

@section('content')
<main class="p-8 max-w-4xl mx-auto">
    <!-- Profile Section -->
    <section class="bg-white p-6 rounded-2xl shadow-md mb-10 flex items-center gap-6">
        <!-- Avatar -->
        <div class="flex-shrink-0">
            <img src="{{ $user->avatar ?? asset('assets/img/default-profile.jpg')}}" alt="Avatar" class="w-24 h-24 object-cover rounded-full border-2 border-[#E95670]">
        </div>

                <!-- User Info & Logout -->
        <div class="flex-1 space-y-2 relative">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}'s Profile</h2>
                    <p class="text-gray-600"><strong>Matric Number:</strong> {{ $user->matricnum }}</p>
                    <p class="text-gray-600"><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                    <p class="text-gray-600"><strong>Bio:</strong> {{ $user->bio ?? 'No bio added' }}</p>
                </div>

                @if(Auth::check() && Auth::user()->matricnum === $user->matricnum)
                    <!-- Logout Icon -->
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
                <a href="{{ route('profile.edit') }}" class="inline-block text-white bg-[#E95670] px-4 py-2 rounded-lg hover:bg-[#B34270] mt-2">
                    Edit Profile
                </a>
            @else
                <!-- Message Seller Button -->
                <a href="{{ route('chat.seller', ['seller_id' => $user->matricnum]) }}" 
                class="inline-block text-white bg-[#E95670] px-4 py-2 rounded-lg hover:bg-[#B34270] mt-2">
                    Message Seller
                </a>
            @endif
        </div>

    </section>

    <!-- Listings Section -->
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
</main>
@endsection

@section('script')
<script>
    function confirmLogout(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel',
            cancelButtonColor: '#d33',
            background: '#fff',
            color: '#000',
            customClass: {
                confirmButton: 'custom-confirm-button',
                cancelButton: 'swal2-cancel'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.closest('form').submit();
            }
        });

    }
</script>
@endsection





