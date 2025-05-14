<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RelovedUKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 ">

    <!-- Navbar -->
    <nav class="w-full bg-[#1B1B3A] text-white py-4 px-6 flex justify-between items-center">
        <div class="text-2xl font-bold text-[#E95670]">RelovedUKM</div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('register') }}" class="text-white mr-4">Sign Up</a>
            <a href="{{ route('login') }}" class="bg-[#E95670] text-white px-4 py-2 rounded-lg">Log In</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative">
        <img src="assets/img/welcomebg3.jpg" alt="Background Image" class="w-full h-screen object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center items-center text-center text-white p-6">
            <h1 class="text-6xl font-extrabold uppercase leading-tight">Reuse. Rehome. Reloved</h1>
            <p class="text-lg mt-4">Sell second-hand in your uni community. Pass on what you love.</p>
            <a href="{{ route('register') }}" class="bg-[#E95670] text-white text-lg font-bold px-6 py-3 mt-6 rounded-lg">Join The Community</a>
        </div>
    </div>

</body>
</html>
