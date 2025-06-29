<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;

/**
 * Controller untuk mengelola autentikasi admin
 * Berisi fungsi login, logout, registrasi, dan reset password
 */
class AuthController extends Controller
{
    /**
     * Menampilkan form login admin
     * Mengembalikan view 'admin.login'
     */
    public function showLoginForm()
    {
        return View::make('admin.login');
    }

    /**
     * Proses login admin dengan validasi dan proteksi brute force
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi input login (email/nama pengguna dan password)
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255',
            'password' => 'required|string|min:8',
        ], [
            'login.required' => 'Email atau nama pengguna wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter'
        ]);

        // Jika validasi gagal, kembalikan response error
        if ($validator->fails()) {
            return $this->sendErrorResponse($validator->errors(), $request);
        }

        // 2. Proteksi brute force dengan rate limiting (maks 5 percobaan)
        $throttleKey = Str::lower($request->input('login')).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return $this->sendErrorResponse([
                'login' => ["Terlalu banyak percobaan. Silakan coba lagi dalam {$seconds} detik."]
            ], $request, 429);
        }

        // 3. Tentukan jenis login: email atau nama pengguna
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        // 4. Cari admin berdasarkan loginType dan input login
        $admin = Admin::where($loginType, $request->login)->first();

        // Jika admin tidak ditemukan, hit rate limiter dan kembalikan error
        if (!$admin) {
            RateLimiter::hit($throttleKey);
            return $this->sendErrorResponse([
                'login' => ['Akun tidak ditemukan']
            ], $request);
        }

        // 5. Verifikasi password yang dimasukkan dengan hash di database
        if (!Hash::check($request->password, $admin->password)) {
            RateLimiter::hit($throttleKey);
            return $this->sendErrorResponse([
                'password' => ['Password yang Anda masukkan salah']
            ], $request);
        }

        // 6. Attempt login menggunakan guard 'admin' dengan credential yang valid
        if (Auth::guard('admin')->attempt([
            $loginType => $request->login,
            'password' => $request->password
        ], $request->filled('remember'))) {
            
            // Bersihkan rate limiter jika login berhasil
            RateLimiter::clear($throttleKey);
            
            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Jika request AJAX, kembalikan response JSON sukses
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'redirect' => route('admin.dashboard')
                ]);
            }

            // Redirect ke halaman dashboard admin
            return redirect()->intended(route('admin.dashboard'));
        }

        // 7. Jika login gagal, hit rate limiter dan kembalikan error
        RateLimiter::hit($throttleKey);
        return $this->sendErrorResponse([
            'login' => ['Gagal melakukan login']
        ], $request);
    }

    /**
     * Helper untuk mengirim response error
     * @param array $errors
     * @param Request $request
     * @param int $status HTTP status code, default 401 Unauthorized
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    private function sendErrorResponse($errors, $request, $status = 401)
    {
        // Jika request AJAX, kembalikan response JSON error
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], $status);
        }

        // Jika request form biasa, redirect kembali dengan input dan error
        return redirect()
            ->back()
            ->withInput()
            ->withErrors($errors);
    }

    /**
     * Proses logout admin
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    /**
     * Menampilkan form registrasi admin baru
     * @return \Illuminate\View\View
     */
    public function showAddAdminForm()
    {
        return View::make('admin.register');
    }

    /**
     * Proses registrasi admin baru dengan validasi input
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function storeAdmin(Request $request)
    {
        // Validasi input registrasi admin
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        // Jika validasi gagal, kembalikan error
        if ($validator->fails()) {
            return $this->sendErrorResponse($validator->errors(), $request, 422);
        }

        // Buat admin baru dengan data valid dan hash password
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Jika request AJAX, kembalikan response JSON sukses
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil, silakan login'
            ]);
        }

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('admin.login')->with('success', 'Registrasi berhasil, silakan login');
    }

    /**
     * Menampilkan form lupa password admin
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        return View::make('admin.lupa-password');
    }

    /**
     * Mengirim link reset password ke email admin
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi email input
        $request->validate(['email' => 'required|email']);

        // Kirim link reset password menggunakan broker 'admins'
        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        // Jika request AJAX, kembalikan response JSON sesuai status
        if ($request->expectsJson()) {
            return $status == Password::RESET_LINK_SENT
                ? response()->json(['success' => true, 'message' => __($status)])
                : response()->json(['success' => false, 'message' => __($status)], 422);
        }

        // Redirect dengan status sesuai hasil pengiriman link
        return $status == Password::RESET_LINK_SENT
            ? redirect()->route('admin.login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Menampilkan form reset password dengan token
     * @param string $token
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm($token)
    {
        return View::make('admin.reset-password')->with(['token' => $token]);
    }

    /**
     * Proses reset password admin dengan validasi input
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request)
    {
        // Validasi input reset password
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Reset password menggunakan broker 'admins'
        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($admin, $password) {
                $admin->password = Hash::make($password);
                $admin->save();
            }
        );

        // Jika request AJAX, kembalikan response JSON sesuai status
        if ($request->expectsJson()) {
            return $status == Password::PASSWORD_RESET
                ? response()->json(['success' => true, 'message' => __($status)])
                : response()->json(['success' => false, 'message' => __($status)], 422);
        }

        // Redirect dengan status sesuai hasil reset password
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
