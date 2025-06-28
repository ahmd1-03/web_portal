<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Tampilkan halaman form login admin
    public function showLoginForm()
    {
        return View::make('admin.login');
    }

    // Proses login admin dengan throttle dan optional reCAPTCHA
    public function login(Request $request)
    {
        // Validasi input login dan password dengan aturan khusus
        $rules = [
            'login' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',      // minimal satu huruf kapital
                'regex:/[a-z]/',      // minimal satu huruf kecil
                'regex:/[0-9]/',      // minimal satu angka
                'regex:/[!@#$%^&*]/', // minimal satu simbol khusus
            ],
        ];

        // Jika input login berupa email, tambahkan validasi format email
        if (filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)) {
            $rules['login'][] = 'email';
        }

        $messages = [
            'login.required' => 'Email atau nama pengguna wajib diisi.',
            'login.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf kapital, huruf kecil, angka, dan simbol khusus (!@#$%^&*).',
        ];

        $request->validate($rules, $messages);

        $throttleKey = Str::lower($request->input('login')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return Redirect::back()->withErrors([
                'login' => "Terlalu banyak percobaan login. Silakan coba lagi dalam $seconds detik.",
            ])->onlyInput('login');
        }

        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $login_type => $request->input('login'),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            // Tambahkan pesan sukses ke session untuk notifikasi
            return Redirect::intended(route('admin.dashboard'))->with('success', 'Login berhasil. Selamat datang!');
        }

        RateLimiter::hit($throttleKey, 60);

        return Redirect::back()->withErrors([
            'login' => 'Login hanya diperbolehkan untuk admin terdaftar.',
        ])->onlyInput('login');
    }

    // Logout admin
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::route('admin.login');
    }
}
