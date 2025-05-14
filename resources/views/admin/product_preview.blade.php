@extends('layouts.default')
@section('title', 'Admin Product Preview')

@php $isAdminView = true; @endphp

@section('content')
<main class="d-flex justify-content-center align-items-center py-5 px-3" style="min-height: 90vh;">
    <div class="card shadow-sm w-100" style="max-width: 960px; border: 2px solid #E95670; border-radius: 15px; padding: 30px; position: relative;">

        {{-- Back Button --}}
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary position-absolute top-0 start-0 m-3">
            ← Back to Dashboard
        </a>

        {{-- Label --}}
        <div class="text-end mb-3">
            <span class="badge bg-black text-white px-3 py-2 fs-6">Admin's Preview</span>
        </div>

        <div class="row g-4 align-items-start">
            <div class="col-md-6 text-center">
                <img src="{{ $product->image }}" class="img-fluid rounded shadow-sm" alt="Product Image">
            </div>

            <div class="col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $product->user->avatar ?? asset('assets/img/default-profile.jpg') }}"
                         alt="Avatar"
                         class="rounded-circle me-3"
                         style="width: 60px; height: 60px; object-fit: cover; border: 2px solid #E95670;">
                    <span class="fw-semibold text-dark fs-5">{{ $product->user->name }}</span>
                </div>

                <h2 class="fw-bold text-dark mb-2">{{ $product->title }}</h2>

                <span class="badge bg-secondary mb-2">
                    Category: {{ $product->category }}
                </span>

                <p class="text-danger fs-4 fw-semibold mt-2">RM {{ $product->price }}</p>
                <p class="text-muted fs-6">{{ $product->description }}</p>
            </div>
        </div>

        {{-- Admin Quick Actions --}}
        <div class="mt-4 d-flex justify-content-end gap-2">
            <form id="approveForm" action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Approve</button>
            </form>

            <form id="rejectForm" action="{{ route('admin.products.reject', $product->id) }}" method="POST">
                @csrf
                <button type="button" onclick="confirmReject()" class="btn btn-danger">Reject</button>
            </form>
        </div>
    </div>
</main>

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function confirmReject() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You are about to reject this product.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Yes, reject it'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('rejectForm').submit();
        }
    });
}
</script>

@if(session('approved'))
<script>
    Swal.fire({
        title: 'Approved!',
        text: "{{ session('approved') }}",
        icon: 'success',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif
@endsection

@endsection
