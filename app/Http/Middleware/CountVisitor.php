<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class CountVisitor
{
    public function handle(Request $request, Closure $next)
    {
        if (!str_starts_with($request->path(), 'admin')) {
            $ip = $request->ip();
            $recentVisitor = Visitor::where('ip_address', $ip)
                ->where('created_at', '>=', now()->subHour())
                ->first();

            if (!$recentVisitor) {
                Visitor::create(['ip_address' => $ip]);
            }
        }

        return $next($request);
    }
}
