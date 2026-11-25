@extends('layouts.app')

@section('shell')
    <div class="admin-shell">
        <aside class="sidebar">
            <x-brand />
            <nav aria-label="Menu admin" class="side-links">
                @foreach (['dashboard' => ['grid', 'Dashboard'], 'admin.menus' => ['menu', 'Kelola Menu'], 'admin.orders' => ['orders', 'Pesanan Masuk']] as $link => [$icon, $label])
                    <a href="{{ route('preview.'.$link) }}" @class(['nav-link', 'is-current' => request()->routeIs('preview.'.$link)]) @if(request()->routeIs('preview.'.$link)) aria-current="page" @endif>
                        <x-icon :name="$icon" /> {{ $label }}
                    </a>
                @endforeach
            </nav>
            <a class="back-link" href="{{ route('preview.home') }}"><x-icon name="back" /> Pilih peran demo</a>
        </aside>
        <div class="min-w-0">
            <header class="topbar">
                <span>Admin <span class="mx-1 text-slate-400">/</span> @yield('title')</span>
                <div class="profile"><span class="avatar">DA</span><span><strong>Dewi Anggraini</strong><small>Admin</small></span></div>
            </header>
            <main class="content-wrap">@yield('content')</main>
        </div>
    </div>
@endsection
