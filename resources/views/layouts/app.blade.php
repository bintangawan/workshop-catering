<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-amber-50 text-stone-900 antialiased">
    <main class="mx-auto w-full max-w-5xl px-6 py-12">
        @yield('content')
    </main>

    @if (session()->has('success'))
        <div hidden data-flash-icon="success" data-flash-message="{{ session('success') }}"></div>
    @elseif (session()->has('error'))
        <div hidden data-flash-icon="error" data-flash-message="{{ session('error') }}"></div>
    @endif
</body>
</html>
