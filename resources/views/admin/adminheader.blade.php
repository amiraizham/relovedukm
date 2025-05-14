{{-- Admin Navbar --}}
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <a class="navbar-brand text-white fw-bold" href="{{ route('admin.dashboard') }}">Admin Panel</a>

            <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                
                <button type="submit" class="btn btn-outline-light">Logout</button>
                @csrf
            </form>
        </div>
    </nav>