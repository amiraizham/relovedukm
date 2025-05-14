<!doctype html>
<html lang="en">
<head>
    <meta charset = "UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield ("title", "RelovedUKM")</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('style')
</head>
    <body class = "d-flex align-items-center py-4 bg-body-tertiary">
    @yield ('content')
    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>
    @yield('script')
    </body>
</html>