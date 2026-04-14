<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && is_null(Auth::user()->employee_id)) {
            return $next($request);
        } else if (!Auth::user() || !auth()->user()->isAdmin()) {
            return redirect()->route('pegawai.homepage');;
        }

        return redirect('/homepage')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
