@extends('layouts.app')

@section('title', 'Masuk')
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
                <h1>Masuk ke Flup Catering</h1>
                <p>Gunakan akun admin atau pelanggan yang diberikan untuk workshop.</p>
                <form method="POST" action="{{ route('login.store') }}" class="grid gap-4">
                    @csrf
                    <label class="field"><span>Email</span><input name="email" type="email" maxlength="255" value="{{ old('email') }}" autocomplete="username" required></label>
                    @error('email') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
                    <label class="field"><span>Kata sandi</span><input name="password" type="password" maxlength="255" autocomplete="current-password" required></label>
                    @error('password') <span class="text-sm text-red-700" role="alert">{{ $message }}</span> @enderror
                    <button type="submit" class="button">Masuk <x-icon name="arrow" /></button>
                </form>
            </div>
        </section>
    </main>
@endsection
