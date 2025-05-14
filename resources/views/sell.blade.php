@extends('layouts.default')

@if(session('success'))
    <div class="bg-green-500 text-white p-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
        {{ session('error') }}
    </div>
@endif


@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6">Sell Your Preloved Items</h2>

    <!-- Notification Messages -->
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 mx-auto w-full max-w-2xl rounded-md relative" role="alert">
            {{ session('success') }}
            <button class="absolute top-2 right-2 text-green-700" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 mx-auto w-full max-w-2xl rounded-md relative" role="alert">
            {{ session('error') }}
            <button class="absolute top-2 right-2 text-red-700" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <!-- Sell Product Form --> a
    <form action="{{ route('sell.post') }}" method="POST" enctype="multipart/form-data" class="space-y-6 w-full max-w-2xl mx-auto px-6">
        @csrf
        
        <!-- Product Name -->
        <div>
            <label for="title" class="block text-gray-700 font-medium">Product Name:</label>
            <input type="text" name="title" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-400 focus:outline-none" required>
        </div>

        <!-- Description -->
        <div>
            <label for="description" class="block text-gray-700 font-medium">Description:</label>
            <textarea name="description" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-400 focus:outline-none" rows="4" required></textarea>
        </div>

        <!-- Price -->
        <div>
            <label for="price" class="block text-gray-700 font-medium">Price (RM):</label>
            <input type="number" name="price" step="0.01" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-400 focus:outline-none" required>
        </div>

        <!-- Category -->
        <div>
            <label for="category" class="block text-gray-700 font-medium">Category:</label>
            <select name="category" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-400 focus:outline-none" required>
                <option value="clothes">Clothes</option>
                <option value="shoes">Shoes</option>
                <option value="books">Books</option>
                <option value="makeup">Accessories</option>
                <option value="makeup">Electronics</option>
                <option value="makeup">Sports</option>
                <option value="makeup">Makeup</option>
            </select>
        </div>

        <!-- Product Image -->
        <div>
            <label for="image" class="block text-gray-700 font-medium">Product Image:</label>
            <input type="file" name="image" class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-pink-400 focus:outline-none" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-[#E95670] text-white py-3 rounded-lg font-semibold hover:bg-[#B34270] transition duration-300">
            Sell Product
        </button>
    </form>
</div>

<!-- Auto-dismiss alert messages after 5 seconds -->
<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => alert.remove());
    }, 5000);
</script>

@endsection