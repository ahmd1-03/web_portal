<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        // Validasi reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        $verifyResponse = $this->verifyRecaptcha($recaptchaResponse);
        
        if (!$verifyResponse['success']) {
            $errors = ['g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.'];
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $errors
                ], 422);
            }
            
            return back()->withErrors($errors)->withInput($request->only('login', 'remember'));
        }

        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
        
        $admin = Admin::where($loginType, $request->login)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            // Update last_activity sebelum login
            $admin->last_activity = now();
            $admin->save();
            
            Auth::guard('admin')->login($admin, $request->remember);
            
            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'redirect' => route('admin.dashboard')
                ]);
            }
            
            return redirect()->intended(route('admin.dashboard'));
        }

        $errors = ['login' => 'These credentials do not match our records.'];
        
        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ], 422);
        }

        return back()->withErrors($errors)->withInput($request->only('login', 'remember'));
    }

    /**
     * Verify reCAPTCHA response
     */
    private function verifyRecaptcha($recaptchaResponse)
    {
        $secretKey = config('services.recaptcha.secret_key');
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => request()->ip()
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        return json_decode($result, true);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Check if this is an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.login')
            ]);
        }
        
        return redirect()->route('admin.login');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('admin.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('admin')->login($admin);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'redirect' => route('admin.dashboard')
            ]);
        }
        
        return redirect()->route('admin.dashboard');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('admin.lupa-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        if ($request->ajax() || $request->wantsJson()) {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => __($status)
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => __($status)]
                ], 422);
            }
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token)
    {
        return view('admin.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($request->ajax() || $request->wantsJson()) {
            if ($status === Password::PASSWORD_RESET) {
                return response()->json([
                    'success' => true,
                    'message' => __($status),
                    'redirect' => route('admin.login')
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => __($status)]
                ], 422);
            }
        }

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Handle heartbeat request untuk memperbarui last_activity
     */
    public function heartbeat(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $user->last_activity = now();
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Activity updated',
                'last_activity' => $user->last_activity
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Not authenticated'
        ], 401);
    }
}
