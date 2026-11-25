@extends('layouts.app')

@php($registering = $registering ?? false)
@section('title', $registering ? 'Daftar pelanggan' : 'Masuk')
@section('shell')
    <main class="portal">
        <section class="portal-photo" style="--portal-image: url('{{ asset('demo/ayam-matah.webp') }}')">
            <x-brand />
            <div>
                <p class="portal-eyebrow">WORKSHOP CATERING</p>
                <p class="portal-headline">Dari dapur<br>ke meja Anda.</p>
            </div>
        </section>
        <section class="portal-options">
            <div>
                <h1>{{ $registering ? 'Daftar akun pelanggan' : 'Masuk ke Flup Catering' }}</h1>
                <p>{{ $registering ? 'Buat akun untuk memesan menu katering.' : 'Masuk dengan akun admin atau pelanggan.' }}</p>
                <form method="POST" action="{{ route($registering ? 'register.store' : 'login.store') }}" class="grid gap-4">
                    @csrf
                    @if ($registering)
                        <label class="field"><span>Nama lengkap</span><input name="name" maxlength="120" value="{{ old('name') }}" autocomplete="name" required></label>
                        @error('name') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
                    @endif
                    <label class="field"><span>Email</span><input name="email" type="email" maxlength="255" value="{{ old('email') }}" autocomplete="username" required></label>
                    @error('email') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
                    <label class="field"><span>Kata sandi</span><input name="password" type="password" maxlength="{{ $registering ? 72 : 255 }}" autocomplete="{{ $registering ? 'new-password' : 'current-password' }}" @if($registering) minlength="8" @endif required></label>
                    @error('password') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
                    @if ($registering)
                        <label class="field"><span>Ulangi kata sandi</span><input name="password_confirmation" type="password" minlength="8" maxlength="72" autocomplete="new-password" required></label>
                    @endif
                    <button type="submit" class="button">{{ $registering ? 'Buat akun' : 'Masuk' }} <x-icon name="arrow" /></button>
                </form>
                <p>
                    @if ($registering)
                        Sudah punya akun? <a class="text-link" href="{{ route('login') }}">Masuk</a>
                    @else
                        Belum punya akun? <a class="text-link" href="{{ route('register') }}">Daftar sebagai pelanggan</a>
                    @endif
                </p>
            </div>
        </section>
    </main>
@endsection
