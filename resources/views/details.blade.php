@extends('layouts.default')
@section('title', 'RelovedUKM - Product Details')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp

<main class="py-10 px-4 flex justify-center">
    <div class="bg-white rounded-xl shadow-lg p-8 w-full max-w-4xl">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Product Image -->
            <div class="md:w-1/2">
                <img src="{{ $product->image }}" class="w-full h-auto object-cover rounded-lg shadow-md" alt="Product Image">
            </div>

            <!-- Product Details -->
            <div class="md:w-1/2 space-y-4 relative">
                <!-- Seller Info -->
                <div class="flex items-center gap-4">
                    <img src="{{ $product->user->avatar ?? asset('assets/img/default-profile.jpg') }}"
                         alt="Avatar"
                         class="w-16 h-16 object-cover rounded-full border-2 border-[#E95670]">
                    <div>
                        <a href="{{ route('seller.profile', $product->user->matricnum) }}"
                           class="text-base font-semibold text-gray-800 hover:text-[#E95670]">
                            {{ $product->user->name }}
                        </a>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-gray-800">{{ $product->title }}</h1>

                <p class="text-sm bg-gray-100 text-gray-700 px-3 py-1 rounded-full inline-block">
                    Category: <span class="font-semibold">{{ $product->category }}</span>
                </p>

                <p class="text-2xl text-[#E95670] font-semibold">RM {{ $product->price }}</p>
                @if ($product->stock_status === 'sold')
                    <span class="inline-block bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-full">
                        SOLD
                    </span>
                @endif
                <p class="text-gray-600 text-base leading-relaxed">{{ $product->description }}</p>

                {{-- Buttons --}}
                @auth
                    @if(Auth::user()->matricnum !== $product->matricnum)
                    @if($product->stock_status === 'sold')
                    <div class="mt-4">
                        <p class="text-sm text-red-600 font-medium bg-red-100 px-4 py-2 rounded-lg">
                            This product has been marked as <strong>Sold</strong>. Buyers can no longer make offers or contact the seller.
                        </p>
                    </div>
                @else
                    <div class="flex flex-wrap gap-4 pt-4">
                        @if(!$alreadyInCart)
                            <form action="{{ route('cart.add', ['id' => $product->id, 'slug' => Str::slug($product->title)]) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-[#E95670] text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-[#B34270] transition">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <form action="{{ route('cart.remove', ['id' => $cartItemId, 'slug' => Str::slug($product->title)]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="redirect_to" value="product">
                                <button type="submit"
                                    class="bg-gray-400 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-gray-600 transition">
                                    Remove from Cart
                                </button>
                            </form>
                        @endif

                        <a href="#" class="bg-gray-400 text-white px-6 py-3 rounded-lg text-lg font-semibold cursor-not-allowed opacity-60">
                            Chat Seller
                        </a>
                    </div>
                @endif

                    @else
                        <!-- Owner Edit/Delete Dropdown -->
                        <div class="absolute top-0 right-0">
                            <div class="relative inline-block text-left">
                                <button onclick="toggleMenu()" class="text-black hover:text-gray-700 text-3xl px-2">⋮</button>
                                <div id="menuDropdown" class="hidden absolute right-0 mt-2 w-32 bg-white border rounded-lg shadow-md z-10">
                                @if($product->stock_status !== 'sold')
                                    {{-- Edit --}}
                                    <a href="{{ route('product.edit', ['id' => $product->id, 'slug' => $product->slug]) }}"
                                    class="block px-4 py-2 text-sm text-black-700 hover:bg-gray-100">
                                        Edit
                                    </a>

                                    {{-- Mark as Sold --}}
                                    <button type="button"
                                            data-url="{{ route('product.markSold', $product->id) }}"
                                            onclick="confirmMarkAsSold(this.dataset.url)"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-600 hover:bg-gray-100">
                                        Mark as Sold
                                    </button>
                                @endif

                                {{-- Delete (Always available) --}}
                                <form action="{{ route('product.delete', ['id' => $product->id, 'slug' => $product->slug]) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Delete
                                    </button>
                                </form>

                                </div>
                            </div>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                        <script>
                            function toggleMenu() {
                                const dropdown = document.getElementById('menuDropdown');
                                dropdown.classList.toggle('hidden');
                            }

                            window.addEventListener('click', function (e) {
                                const menu = document.getElementById('menuDropdown');
                                const button = document.querySelector('button[onclick="toggleMenu()"]');
                                if (!button.contains(e.target) && !menu.contains(e.target)) {
                                    menu.classList.add('hidden');
                                }
                            });

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
                    @endif
                @endauth
            </div>
        </div>
    </div>
</main>
@endsection
