<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Email atau kata sandi tidak sesuai.'])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function home(Request $request): RedirectResponse
    {
        return redirect()->route($request->user()->role === User::ROLE_ADMIN ? 'admin.dashboard' : 'catalog');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
