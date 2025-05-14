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

    <!-- Login Box -->
    <div class="flex flex-grow items-center justify-center mt-20">
        <div class="bg-white p-10 rounded-xl w-96">
        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div>
            <label class="block text-gray-700">Matric Number</label>
            <input type="text" name="matricnum" class="w-full p-2 mt-1 border rounded-lg bg-gray-100" placeholder="e.g. A123456" required>
            @error('matricnum')
                <span class="text-danger">{{ $message }}</span>
            @enderror
            </div>

            <label class="block text-gray-700 mt-4">Password</label>
            <input type="password" name="password" class="w-full p-2 mt-1 border rounded-lg bg-gray-100" placeholder="******" required>

            <div class="flex justify-between items-center mt-2">
                <a href="{{route('password.email')}}" class="text-blue-500 text-sm">Forgot Password?</a>
            </div>

            <button type="submit" class=" bg-[#E95670] w-full mt-4 p-2 border-2 rounded-lg font-bold hover:bg-[#B34270] text-white">
                Log In
            </button>

            <p class="text-center mt-4 text-sm">Don't have an account? <a href="{{ route('register') }}" class="text-blue-500">Sign Up</a></p>
        </form>

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
