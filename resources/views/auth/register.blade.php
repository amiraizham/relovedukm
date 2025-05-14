<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | RelovedUKM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white flex flex-col items-center">

    <!-- Navbar -->
    <nav class="w-full bg-[#1B1B3A] py-4 px-6 flex justify-between items-center">
    <a class="text-[#E95670] text-2xl font-bold" href="{{ route('welcome') }}">RelovedUKM</a>
    <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="text-white">Login</a>
            <button class="bg-[#E95670] text-white px-4 py-2 rounded-lg">WHAT'S RELOVEDUKM?</button>
        </div>
    </nav>

    <!-- Sign Up Box -->
    <div class="flex flex-grow items-center justify-center mt-10">
        <div class="bg-white p-10 rounded-xl w-96">
            <h2 class="text-2xl font-bold text-center mb-6">Sign Up</h2>
            <h3 class="text-sm text-center mb-6 italic">Join other students in your university's exclusive marketplace.</h3>

            <!-- Display Success Message -->
            @if(session('success'))
                <div class="bg-green-500 text-white p-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Display Error Message -->
            @if(session('error'))
                <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div>
                    <label class="block text-gray-700">Full Name</label>
                    <input type="text" name="name" class="w-full p-2 mt-1 border rounded-lg bg-gray-100" placeholder="Enter your full name" required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700">Matric Number</label>
                    <input type="text" name="matricnum" class="w-full p-2 mt-1 border rounded-lg bg-gray-100" placeholder="e.g. A123456" required>
                    @error('matricnum')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mt-4">
                    <label class="block text-gray-700">Student's Email</label>
                    <input type="email" name="siswa_email" class="w-full p-2 mt-1 border rounded-lg bg-gray-100" placeholder="Enter your student's email" required>
                    @error('siswa_email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full p-2 mt-1 border rounded-lg bg-gray-100" placeholder="******" required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <label class="block text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full p-2 mt-1 border rounded-lg bg-gray-100" placeholder="Confirm your password" required>
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="bg-[#E95670] w-full mt-4 p-2 border-2 rounded-lg font-bold hover:bg-[#B34270] text-white">
                    Sign Up
                </button>

                <p class="text-center mt-4 text-sm">Already have an account? <a href="{{ route('login') }}" class="text-blue-500">Login here</a></p>
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
