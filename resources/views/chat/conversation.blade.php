@extends('layouts.default')
@section('title', 'Chat with ' . $seller->name)

@section('content')
<main class="max-w-3xl mx-auto mt-10">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-[#E95670] text-white font-semibold text-lg">
            Chat with {{ $seller->name }}
        </div>

        <!-- Chat Messages -->
        <div class="px-6 py-4 h-[400px] overflow-y-auto bg-gray-50" id="chat-box">
            @foreach ($messages as $msg)
                <div class="mb-4 {{ $msg->sender_matricnum === Auth::user()->matricnum ? 'text-right' : 'text-left' }}">
                    <div class="inline-block px-4 py-2 rounded-lg
                        {{ $msg->sender_matricnum === Auth::user()->matricnum ? 'bg-[#E95670] text-white' : 'bg-gray-200 text-gray-800' }}">
                        
                        {{-- 📷 Show uploaded photo if available --}}
                        @if($msg->photo)
                            <img src="{{ $msg->photo }}" alt="Chat Photo" class="rounded-lg max-w-[200px] mb-2">
                        @endif

                        {{-- 💬 Show text message if available --}}
                        @if($msg->message)
                            <p class="text-sm">{{ $msg->message }}</p>
                        @endif

                        <p class="text-xs text-right mt-1 opacity-70">{{ $msg->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Chat Form -->
        <form action="{{ route('chat.send') }}" method="POST" enctype="multipart/form-data" class="flex flex-col px-6 py-4 bg-white border-t gap-2">
            @csrf
            <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">

            {{-- 💬 Message input --}}
            <input type="text" name="message" placeholder="Type a message..."
                   class="flex-grow px-4 py-2 border rounded-full focus:outline-none focus:ring-2 focus:ring-[#E95670]">

            {{-- 📷 Image upload input --}}
            <input type="file" name="photo" accept="image/*"
                   class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#E95670]">

            {{-- 🚀 Send button --}}
            <button type="submit"
                    class="bg-[#E95670] text-white px-5 py-2 rounded-full hover:bg-[#b3425e] transition mt-2">
                Send
            </button>
        </form>
    </div>
</main>

@section('script')
<script>
    const chatBox = document.getElementById('chat-box');

    function scrollToBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // ✅ Scroll to bottom on page load
    document.addEventListener('DOMContentLoaded', scrollToBottom);

    // ✅ Optional: Scroll again when form is submitted
    const chatForm = document.querySelector('form');
    chatForm.addEventListener('submit', function () {
        setTimeout(scrollToBottom, 300); // Wait a little after sending
    });
</script>
@endsection


@endsection


