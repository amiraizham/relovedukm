<!doctype html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield ("title", "RelovedUKM")</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<style>
    .swiper {
        width: 100%;
        height: 300px;
    }

    .swiper-slide {
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        border-radius: 10px;
    }

    .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumbs .swiper-slide {
        height: 100px;
        cursor: pointer;
        opacity: 0.6;
    }

    .thumbs .swiper-slide-thumb-active {
        opacity: 1;
        border: 2px solid #E95670;
    }
</style>
@endsection

    @yield('style')
</head>
<body>
    @php $isAdminView = request()->query('view') === 'admin'; @endphp

    @unless($isAdminView)
        @include("includes.header")
    @endunless

    <main>
        @yield('content')
    </main>

    @unless($isAdminView)
        @include("includes.footer")
    @endunless

    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    @yield('script')
</body>
</html>
