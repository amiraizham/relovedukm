<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | RelovedUKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@heroicons/vue@2.0.13/dist/heroicons.min.js"></script>
</head>

<body class="bg-gray-50">

<!-- Navbar -->
<nav class="w-full bg-[#1B1B3A] py-3 px-6 flex justify-between items-center">
    <!-- Logo -->
    <a class="text-[#E95670] text-2xl font-bold" href="{{ route('welcome') }}">RelovedUKM</a>

    <!-- Mobile Menu Button -->
    <button id="menu-btn" class="lg:hidden text-white focus:outline-none">
        ☰
    </button>

    <!-- Navbar Items (Hidden on Mobile) -->
    <div id="menu" class="hidden lg:flex items-center space-x-6">
        <!-- Search Bar -->
        <form action="{{ route('search') }}" method="GET" class="flex">
            <input class="px-3 py-2 rounded-l-lg border border-gray-300 focus:ring focus:ring-pink-400" 
                   type="search" name="query" placeholder="Search products...">
            <button class="bg-[#E95670] text-white px-4 py-2 rounded-r-lg hover:bg-[#B34270]">Search</button>
        </form>

        <!-- Navigation Links -->
        <div id="navbar-items" class="lg:flex items-center space-x-6">
        <a href="{{ route('home') }}" class="text-white group-hover:text-[#E95670] transition duration-300 relative group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:scale-110 transition" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 9.75L12 3l9 6.75M4.5 10.5v9h5.25v-6.75h4.5V19.5H19.5v-9L12 4.5 4.5 10.5z"/>
            </svg>
            <span class="absolute bottom-[-28px] left-1/2 -translate-x-1/2 text-xs bg-black text-white px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition">
                Home
            </span>
        </a>

        <a href="{{ route('cart.index') }}" class="text-white hover:text-[#E95670] transition relative group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:scale-110 transition" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3h2l.4 2M7 13h10l1.38-5.52a1 1 0 00-.97-1.23H6.59M16 16a2 2 0 110 4 2 2 0 010-4zm-8 0a2 2 0 110 4 2 2 0 010-4z"/>
            </svg>
        </a>
        @php
            $unreadCount = 0;
            if (Auth::check()) {
                $unreadCount = \App\Models\Message::where('is_read', false)
                    ->where('receiver_matricnum', Auth::user()->matricnum)
                    ->count();
            }
        @endphp


        <a href="{{ route('chat.list') }}" class="relative group">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7 text-white transition duration-300 group-hover:scale-110"
                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4.422-1.01L3 20l1.519-3.797A7.965 7.965 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>

            @if($unreadCount > 0)
                <!-- 🔴 Red notification dot -->
                <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-500 ring-2 ring-white animate-ping"></span>
                <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-500"></span>
            @endif
        </a>
<a href="{{ route('notifications.index') }}" class="text-white hover:text-[#E95670] transition relative group">
    <svg xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6 text-white transition duration-300 group-hover:scale-110"
        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M12 22c1.104 0 2-.896 2-2H10c0 1.104.896 2 2 2zm6-4V9c0-3.313-2.687-6-6-6s-6 2.687-6 6v9l-1.333 2H19.333L18 18z"/>
    </svg>
    @if($unreadCount > 0)
        <!-- Red notification dot -->
        <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-500 ring-2 ring-white animate-ping"></span>
        <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-500"></span>
    @endif
</a>



        <a href="{{ route('profile.show')}}" class="text-white hover:text-[#E95670] transition relative group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 group-hover:scale-110 transition" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.121 17.804A6 6 0 0112 15a6 6 0 016.879 2.804M12 12a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
        </a>
        </div>

            <!-- Sell Button (styled like a button) -->
        <a href="{{ route('sell') }}" 
        class="bg-[#E95670] text-white px-4 py-2 rounded-lg hover:bg-[#B34270] font-semibold">
        Sell
        </a>

    </div>
</nav>

<!-- Mobile Menu (Hidden by Default) -->
<div id="mobile-menu" class="lg:hidden hidden flex flex-col bg-[#1B1B3A] text-white px-6 py-4 space-y-2">
    <a href="{{ route('home') }}" class="hover:text-[#E95670]">Home</a>
    <a href="{{ route('sell') }}" class="hover:text-[#E95670]">Sell</a>
    <a href="#" class="hover:text-[#E95670]">Favorites</a>
    <a href="#" class="hover:text-[#E95670]">Chat</a>
    <a href="#" class="hover:text-[#E95670]">Profile</a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="bg-[#E95670] text-white px-4 py-2 rounded-lg w-full hover:bg-[#B34270]">
            Logout
        </button>
    </form>
</div>

<!-- Script for Mobile Menu -->
<script>
    document.getElementById('menu-btn').addEventListener('click', function() {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>

</body>
</html>
