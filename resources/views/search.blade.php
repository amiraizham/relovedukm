@php use Illuminate\Support\Facades\Storage; @endphp

@auth
@extends('layouts.default')
@section('title','Search Results - RelovedUKM')

@section('style')
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Poppins', sans-serif;
    }

    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding-top: 20px;
    }

    .product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: space-between;
    }

    .product-card {
        width: calc(25% - 30px);
        min-width: 290px;
        background: white;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        text-align: center;
    }

    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
    }

    .product-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }

    .product-info {
        padding: 15px;
    }

    .product-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        transition: color 0.3s ease;
        text-transform: capitalize;
    }

    .product-card:hover .product-title {
        color: #E95670;
    }

    .product-price {
        font-size: 15px;
        color: #E95670;
        font-weight: bold;
    }

    .pagination {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
</style>
@endsection


@section('content')
<main class="container">
    <h2 class="text-center mb-4">Search Results:</h2>

    @if($products->isEmpty())
        <p class="text-center">No products found for "{{ request('query') }}"</p>
    @else
        <section>
            <div class="product-grid">
                @foreach($products as $product)
                <div class="product-card">
                    <a href="{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}">
                        <img src="{{ $product->image }}" alt="Product Image">
                        <div class="product-info">
                            <p class="product-title">{{ $product->title }}</p>
                            <span class="product-price">RM {{ $product->price }}</span>
                        </div>
                    </a>
                </div>

                @endforeach
            </div>
            <!-- Pagination -->
            <div class="pagination">
                {{$products->links()}}
            </div>
        </section>
    @endif
</main>
@endsection
@endauth
