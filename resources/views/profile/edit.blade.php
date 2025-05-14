@extends('layouts.default')
@section('title', 'Edit Profile - RelovedUKM')

@section('content')
<main class="p-8 max-w-3xl mx-auto">
    <section class="bg-white p-6 rounded-2xl shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Profile</h2>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-gray-700 font-semibold mb-1">Name</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name', $user->name) }}" 
                    required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#E95670]"
                >
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-gray-700 font-semibold mb-1">Phone</label>
                <input 
                    type="text" 
                    name="phone" 
                    id="phone" 
                    value="{{ old('phone', $user->phone) }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#E95670]"
                >
            </div>

            <!-- Bio -->
            <div>
                <label for="bio" class="block text-gray-700 font-semibold mb-1">Bio</label>
                <textarea 
                    name="bio" 
                    id="bio" 
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#E95670]"
                >{{ old('bio', $user->bio) }}</textarea>
            </div>

            <!-- Avatar Upload -->
            <div>
                <label for="avatar" class="block text-gray-700 font-semibold mb-1">Avatar (Optional)</label>
                <input 
                    type="file" 
                    name="avatar" 
                    id="avatar" 
                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                           file:rounded-lg file:border-0 file:text-sm file:font-semibold
                           file:bg-[#E95670] file:text-white hover:file:bg-[#B34270]"
                >
            </div>

            <!-- Submit Button -->
            <div class="text-right">
                <button 
                    type="submit" 
                    class="bg-[#E95670] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#B34270] transition"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </section>
</main>
@endsection
