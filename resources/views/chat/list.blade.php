@extends('layouts.default')
@section('title', 'My Chats')

@section('content')
<main class="max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold text-[#E95670] mb-4">My Conversations</h2>

    @forelse ($conversations as $conv)
        @php
            $currentUser = Auth::user()->matricnum;
            $other = $conv->user1_matricnum === $currentUser ? $conv->user2 : $conv->user1;
            $lastMessage = $conv->messages->first();

            $hasUnread = $lastMessage && 
                         $lastMessage->receiver_matricnum === $currentUser && 
                         !$lastMessage->is_read;
        @endphp

        <a href="{{ route('chat.seller', ['seller_id' => $other->matricnum]) }}"
           class="block p-4 mb-3 bg-white rounded-lg shadow hover:bg-gray-100 transition">
            <div class="flex justify-between items-center">
                <!-- Left Side (Name + Message) -->
                <div>
                    <p class="font-semibold text-gray-800">{{ $other->name }}</p>
                    <p class="text-sm text-gray-600">{{ $lastMessage->message ?? 'No messages yet' }}</p>
                </div>

                <!-- Right Side (Timestamp + Red Dot) -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400">{{ $lastMessage->created_at->diffForHumans() ?? '' }}</span>
                    @if($hasUnread)
                        <span class="inline-block w-3 h-3 bg-red-500 rounded-full"></span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <p class="text-gray-500">You have no active conversations.</p>
    @endforelse
</main>
@endsection
