<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GLOW&CO | Skincare & Beauty')</title>
    <meta name="description" content="@yield('description', 'Toko online skincare terpercaya')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @yield('css')
</head>
<body>

    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    @yield('js') 

</body>
</html>