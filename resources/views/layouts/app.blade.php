<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Workshop Catering') · Flup Catering</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @yield('shell')
    @if (session()->has('success'))
        <div hidden data-flash-icon="success" data-flash-message="{{ session('success') }}"></div>
    @elseif (session()->has('error'))
        <div hidden data-flash-icon="error" data-flash-message="{{ session('error') }}"></div>
    @endif
</body>
</html>
