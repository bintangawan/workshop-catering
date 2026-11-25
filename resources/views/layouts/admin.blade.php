@extends('layouts.app')

@section('shell')
    <div class="admin-shell">
        <aside class="sidebar">
            <x-brand />
            <nav aria-label="Menu admin" class="side-links">
                @foreach (['admin.dashboard' => ['grid', 'Dashboard'], 'admin.menus' => ['menu', 'Kelola Menu'], 'admin.orders' => ['orders', 'Pesanan Masuk']] as $link => [$icon, $label])
                    <a href="{{ route($link) }}" @class(['nav-link', 'is-current' => request()->routeIs($link)]) @if(request()->routeIs($link)) aria-current="page" @endif>
                        <x-icon :name="$icon" /> {{ $label }}
                    </a>
                @endforeach
            </nav>
            <form class="lg:mt-auto" method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="back-link" type="submit"><x-icon name="back" /> Keluar</button>
            </form>
        </aside>
        <div class="min-w-0">
            <header class="topbar">
                <span>Admin <span class="mx-1 text-slate-400">/</span> @yield('title')</span>
                <div class="profile"><span class="avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span><span><strong>{{ auth()->user()->name }}</strong><small>Admin</small></span></div>
            </header>
            <main class="content-wrap">@yield('content')</main>
        </div>
    </div>
@endsection
