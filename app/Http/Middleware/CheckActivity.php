<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek jika admin sudah login menggunakan admin guard
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            
            // Perbarui last_activity pada setiap request
            $user->last_activity = now();
            $user->save();
            
            // Cek jika user tidak aktif selama 1 jam (60 menit)
            $lastActivity = Carbon::parse($user->last_activity);
            $inactiveTime = $lastActivity->diffInMinutes(now());
            
            if ($inactiveTime > 60) {
                // Logout admin jika tidak aktif selama lebih dari 1 jam
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirect ke halaman login dengan pesan
                return redirect()->route('admin.login')
                    ->with('error', 'Sesi Anda telah berakhir karena tidak ada aktivitas selama 1 jam.');
            }
        }

        return $next($request);
    }
}
