<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses panel admin.');
        }

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();

            return redirect()->route('admin.login')->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi administrator.');
        }

        if ($role && ! $user->canManageAll()) {
            if ($role === 'super_admin' && ! $user->isSuperAdmin()) {
                abort(403, 'Akses terbatas untuk Super Admin.');
            }
            if ($role === 'pemred' && ! $user->isPemred()) {
                abort(403, 'Akses terbatas untuk Pemimpin Redaksi.');
            }
            if ($role === 'editor' && ! $user->isEditor()) {
                abort(403, 'Akses terbatas untuk Editor.');
            }
        }

        return $next($request);
    }
}
