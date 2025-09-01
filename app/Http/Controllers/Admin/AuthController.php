<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\PasswordResetCode;
use App\Mail\SendPasswordResetCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
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
     * Send password reset code
     */
    public function sendResetCodeEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if admin exists
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => 'Email tidak ditemukan dalam sistem.']
                ], 422);
            }
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Generate 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing codes for this email
        PasswordResetCode::where('email', $request->email)->delete();

        // Create new reset code
        PasswordResetCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Send email
        try {
            Mail::to($request->email)->send(new SendPasswordResetCode($code, $request->email));

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode reset password telah dikirim ke email Anda.',
                    'redirect' => route('admin.verifyCodeForm', ['email' => $request->email])
                ]);
            }

            return redirect()->route('admin.verifyCodeForm', ['email' => $request->email])
                ->with('status', 'Kode reset password telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => 'Gagal mengirim email. Silakan coba lagi.']
                ], 422);
            }
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.']);
        }
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm($token = null)
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
     * Show verify code form
     */
    public function showVerifyCodeForm(Request $request)
    {
        $email = $request->query('email');
        return view('admin.verify-code', compact('email'));
    }

    /**
     * Verify the reset code
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $resetCode = PasswordResetCode::findByEmailAndCode($request->email, $request->code);

        if (!$resetCode) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['code' => 'Kode verifikasi tidak valid atau telah kedaluwarsa.']
                ], 422);
            }
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid atau telah kedaluwarsa.']);
        }

        // Delete the used code
        $resetCode->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kode verifikasi berhasil.',
                'redirect' => route('admin.newPasswordForm', ['email' => $request->email])
            ]);
        }

        return redirect()->route('admin.newPasswordForm', ['email' => $request->email])
            ->with('status', 'Kode verifikasi berhasil.');
    }

    /**
     * Resend reset code
     */
    public function resendCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Check if admin exists
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => 'Email tidak ditemukan dalam sistem.']
                ], 422);
            }
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Generate new 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Delete any existing codes for this email
        PasswordResetCode::where('email', $request->email)->delete();

        // Create new reset code
        PasswordResetCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Send email
        try {
            Mail::to($request->email)->send(new SendPasswordResetCode($code, $request->email));

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode reset password baru telah dikirim ke email Anda.'
                ]);
            }

            return back()->with('status', 'Kode reset password baru telah dikirim ke email Anda.');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => 'Gagal mengirim email. Silakan coba lagi.']
                ], 422);
            }
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi.']);
        }
    }

    /**
     * Show new password form
     */
    public function showNewPasswordForm(Request $request)
    {
        $email = $request->query('email');
        return view('admin.new-password', compact('email'));
    }

    /**
     * Update password with new password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['email' => 'Email tidak ditemukan dalam sistem.']
                ], 422);
            }
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Update password
        $admin->password = Hash::make($request->password);
        $admin->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah.',
                'redirect' => route('admin.login')
            ]);
        }

        return redirect()->route('admin.login')->with('status', 'Password berhasil diubah.');
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
