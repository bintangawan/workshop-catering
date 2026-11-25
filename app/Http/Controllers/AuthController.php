<?php

namespace App\Http\Controllers;

use App\Models\User;
use Closure;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $this->normalizeEmail($request);
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

    public function register(Request $request): RedirectResponse
    {
        $this->normalizeEmail($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['bail', 'required', 'string', 'min:8', 'confirmed', function (string $attribute, string $value, Closure $fail) {
                if (strlen($value) > 72 || str_contains($value, "\0")) {
                    $fail('Panjang kata sandi maksimal 72 byte dan tidak boleh berisi karakter null.');
                }
            }],
        ]);

        try {
            $user = new User($data);
            $user->role = User::ROLE_CUSTOMER;
            $user->save();
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages(['email' => 'Email ini sudah terdaftar.']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('catalog')->with('success', 'Akun pelanggan berhasil dibuat.');
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

    private function normalizeEmail(Request $request): void
    {
        $email = $request->input('email');

        if (is_string($email)) {
            $request->merge(['email' => mb_strtolower(trim($email))]);
        }
    }
}
