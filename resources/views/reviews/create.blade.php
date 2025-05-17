@extends('layouts.default')

@section('title', 'Leave a Review')

@section('content')
<div class="max-w-xl mx-auto py-10">
    <h2 class="text-2xl font-semibold mb-6">Leave a Review</h2>

    <form action="{{ route('review.store', $productId) }}" method="POST" class="space-y-4">
        @csrf

        <label for="rating" class="block font-medium">Rating (1-5)</label>
        <input type="number" name="rating" min="1" max="5" class="w-full border rounded px-4 py-2" required>

        <label for="comment" class="block font-medium">Comment</label>
        <textarea name="comment" rows="5" class="w-full border rounded px-4 py-2" required></textarea>

        <button type="submit" class="bg-[#E95670] text-white px-6 py-2 rounded hover:bg-[#B34270]">Submit Review</button>
    </form>
</div>
@endsection
