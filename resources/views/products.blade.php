@auth
@extends('layouts.default')
@section('title','RelovedUKM - Home')

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

/* Category Dropdown Wrapper */
.category-dropdown-wrapper {
    display: flex;
    justify-content: flex-start;

    margin-bottom: 30px;
}

.sort-dropdown {
    padding: 5px 30px 5px 20px;
    border: 2px solid #E95670;
    border-radius: 10px;
    font-size: 16px;
    color: #E95670;
    font-weight: 700;
    background-color: #fff;
    cursor: pointer;
    outline: none;
    appearance: none;
}


.category-dropdown {
    padding: 5px 30px 5px 20px;
    border: 2px solid #E95670;
    border-radius: 10px;
    font-size: 16px;
    color: #E95670;
    font-weight: 700;
    background-color: #fff;
    cursor: pointer;
    outline: none;
    appearance: none;

    /* Larger arrow */
    background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 512 512' fill='%23E95670' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M128 192l128 128 128-128z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 15px center;
    background-size: 16px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* Hover state for arrow and background */
.category-dropdown:hover {
    background-color: #E95670;
    color: white;

    /* Change arrow fill to white */
    background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 512 512' fill='%23ffffff' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M128 192l128 128 128-128z'/%3E%3C/svg%3E");
}


    /* Product Grid */
    .product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 30px;
        justify-content: space-between;
    }

    .product-card {
        width: calc(25% - 30px); /* ✅ 4 cards per row with 30px gap */
        min-width: 270px;
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
    <section>


        <div class="category-dropdown-wrapper" style="gap: 10px;">
            <form method="GET" action="{{ route('home') }}" style="display: flex; gap: 10px;">
                <!-- Category -->
                <select name="category" onchange="this.form.submit()" class="category-dropdown">
                    <option value="">All Categories</option>
                    @foreach(['clothes', 'shoes', 'accessories', 'electronics', 'makeup', 'sports', 'books'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach
                </select>

                <!-- Sort -->
                <select name="sort" onchange="this.form.submit()" class="category-dropdown sort-animated">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest</option>
                    <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low → High</option>
                    <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High → Low</option>
                </select>
            </form>
        </div>


        <!-- Product Grid -->
        <div class="product-grid">
            @foreach($products as $product)
                <a href="{{ route('product.details', ['id' => $product->id, 'slug' => $product->slug]) }}">
                    <div class="product-card">
                    <img src="{{ $product->image }}" alt="Product Image">
                    <div class="product-info">
                            <p class="product-title">{{ $product->title }}</p>
                            <span class="product-price">RM {{ $product->price }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>

    </section>
</main>

@section('script')
<script>
    // Animate dropdown on hover
    document.querySelectorAll('.sort-animated').forEach(dropdown => {
        dropdown.addEventListener('mouseenter', () => {
            dropdown.style.transition = 'all 0.3s ease';
            dropdown.style.boxShadow = '0 4px 12px rgba(0,0,0,0.2)';
            dropdown.style.transform = 'scale(1.03)';
        });

        dropdown.addEventListener('mouseleave', () => {
            dropdown.style.boxShadow = 'none';
            dropdown.style.transform = 'scale(1)';
        });
    });
</script>
@endsection


@endsection
@endauth
