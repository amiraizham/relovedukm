@extends('layouts.default')

@section('content')
    <div class="container mx-auto py-10">
        <h2 class="text-2xl font-semibold mb-6">Notifications</h2>

        <!-- Buyer Notifications -->
        <h3 class="text-xl font-semibold mb-4">Bookings with Sellers</h3>
        @foreach($buyerNotifications as $booking)
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                <h4 class="font-bold">{{ $booking->product->title }}</h4>
                <p>Status: {{ ucfirst($booking->status) }}</p>
                <p>Seller: {{ $booking->seller->name }}</p>
                <p>Price: RM {{ $booking->product->price }}</p>
                @if($booking->status == 'pending')
                    <p class="text-orange-500">Waiting for approval.</p>
                @elseif($booking->status == 'approved')
                    <p class="text-green-500">Booking approved, chat seller now!</p>
                @elseif($booking->status == 'rejected')
                    <p class="text-red-500">Booking rejected by the seller.</p>
                @elseif($booking->status == 'sold')
                    <!-- Only show for the buyer -->
                    @if(Auth::user()->matricnum == $booking->buyer_matricnum && !$booking->review()->exists()) 
                        <p class="text-orange-500">This product has been marked as sold.</p>
                        <a href="{{ route('review.create', ['product' => $booking->product->id]) }}" 
                        class="text-blue-500 hover:text-blue-700">
                            Leave a Review
                        </a>
                    @endif
                @endif
            </div>
        @endforeach

        <!-- Seller Notifications -->
        <h3 class="text-xl font-semibold mb-4">Booking Requests</h3>
        @foreach($sellerNotifications as $booking)
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                <h4 class="font-bold">{{ $booking->product->title }}</h4>
                <p>Status: {{ ucfirst($booking->status) }}</p>
                <p>Buyer: {{ $booking->buyer->name }}</p>
                <p>Price: RM {{ $booking->product->price }}</p>
                
                <!-- Only show actions for pending bookings -->
                @if($booking->status == 'pending')
                    <form action="{{ route('notifications.updateStatus', ['id' => $booking->id, 'status' => 'approved']) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Approve</button>
                    </form>
                    <form action="{{ route('notifications.updateStatus', ['id' => $booking->id, 'status' => 'rejected']) }}" method="POST" class="inline-block ml-2">
                        @csrf
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Reject</button>
                    </form>


                @elseif($booking->status == 'approve')
                    <p class="text-green-500">Booking approved, waiting for buyer's message.</p>
                    
                    <!-- Mark as Sold Button (after approval) -->
                    <button type="button"
                            data-url="{{ route('booking.markSold', $booking->id) }}"
                            onclick="confirmMarkAsSold(this.dataset.url)"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                        Mark as Sold
                    </button>

                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                    <script>

                        function confirmMarkAsSold(actionUrl) {
                            Swal.fire({
                                title: 'Mark this product as SOLD?',
                                text: "You can’t undo this action. Buyers can no longer chat with you or make offers for this listing.",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#aaa',
                                confirmButtonText: 'Yes, mark as sold'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Create a temporary form and submit
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = actionUrl;

                                    const token = document.createElement('input');
                                    token.type = 'hidden';
                                    token.name = '_token';
                                    token.value = '{{ csrf_token() }}';

                                    form.appendChild(token);
                                    document.body.appendChild(form);
                                    form.submit();
                                }
                            });
                        }
                    </script>
                @elseif($booking->status == 'rejected')
                    <p class="text-red-500">Booking rejected.</p>
                @endif

            </div>
        @endforeach

        <!-- Rejected Products Section -->
        <h3 class="text-xl font-semibold mb-4">Rejected Products</h3>
        @foreach($rejectedProducts as $product)
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                <h4 class="font-bold">{{ $product->title }}</h4>
                <p>Status: <span class="text-red-500">Rejected by Admin</span></p>
                <p>Price: RM {{ $product->price }}</p>
                <p class="text-sm text-gray-500">Reason: N/A</p>
            </div>
        @endforeach
    </div>
@endsection
