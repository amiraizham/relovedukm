@extends('layouts.auth')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container" >
@include("admin.adminheader")

    <h2>Pending Product Approvals</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Action ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Seller Matricnum</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pendingProducts as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    <a href="{{ route('admin.products.preview', $product->id) }}">{{ $product->id }}</a>
                </td>
                <td>{{ $product->title }}</td>
                <td>{{ $product->matricnum }}</td>
                <td>
                    <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('admin.products.reject', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </form>
                </td>
                <td>{{ $product->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
