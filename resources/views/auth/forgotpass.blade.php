
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | RelovedUKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white flex flex-col items-center">

    <!-- Navbar -->
    <nav class="w-full bg-[#1B1B3A] py-4 px-6 flex justify-between items-center">
        <a class="text-[#E95670] text-2xl font-bold" href="{{ route('welcome') }}">RelovedUKM</a>
        <div class="flex items-center space-x-4">
            <a href="{{ route('register') }}" class="text-white">Sign Up</a>
            <button class="bg-[#E95670] text-white px-4 py-2 rounded-lg">WHAT'S RELOVEDUKM?</button>
        </div>
    </nav>
    <div class="container py-10 max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-6">Forgot Your Password?</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="mb-4">
            <label for="siswa_email" class="block text-gray-700 font-semibold mb-2">Student Email</label>
            <input type="email" name="siswa_email" class="w-full border rounded px-4 py-2" required>
            @error('siswa_email')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="bg-[#E95670] text-white py-2 px-6 rounded hover:bg-[#B34270]">
            Send Reset Link
        </button>
    </form>
</div>

        <p class="text-center text-sm mt-6">
            If you need help, please contact <a href="mailto:support@relovedukm.com" class="text-blue-500">support@relovedukm.com</a>.
        </p>

        <div class="text-center text-gray-500 text-sm mt-4">
            <a href="#" class="mr-2">Terms of Use</a> |
            <a href="#" class="ml-2">Privacy Policy</a>
        </div>
    </div>
</div>

</body>
</html>
