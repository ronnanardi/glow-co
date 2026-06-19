<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GLOW&CO | Skincare & Beauty')</title>
    <meta name="description" content="@yield('description', 'Toko online skincare terpercaya')">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('css')
</head>
<body>

    {{-- Loader --}}
    <div id="pageLoader">
        <div class="loader-brand">GLOW<span>&CO</span></div>
    </div>

    @include('partials.navbar')

    @yield('content')

    @include('partials.footer')

    @if(session('success'))
        <span id="flash-success" data-message="{{ session('success') }}" style="display:none"></span>
    @endif
    @if(session('error'))
        <span id="flash-error" data-message="{{ session('error') }}" style="display:none"></span>
    @endif

    @yield('js') 

    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
    
</body>
</html>