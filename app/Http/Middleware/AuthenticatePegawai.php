<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatePegawai
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::guard('pegawai')->check()) {
            return redirect()->route('login');
        }

        $pegawai = Auth::guard('pegawai')->user();

        // If roles are specified, check if the pegawai has one of them
        if (!empty($roles) && !in_array($pegawai->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
