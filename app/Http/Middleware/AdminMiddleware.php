<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('pegawai')->check()) {
            return redirect()->route('login');
        }

        $pegawai = Auth::guard('pegawai')->user();

        if ($pegawai->role !== 'admin') {
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
