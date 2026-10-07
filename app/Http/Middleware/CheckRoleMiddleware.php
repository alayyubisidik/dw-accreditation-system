<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {

        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }

        if ($user->role !== $role) {
            $redirectUrl = $user->role === 'admin' ? '/dashboard-admin/university' : '/dashboard-' . $user->role;
            return redirect($redirectUrl);
        }

        return $next($request);
    }
}
