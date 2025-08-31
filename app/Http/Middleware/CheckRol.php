<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $rol
     * @return mixed
     */
    public function handle($request, Closure $next, $rol)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Si el usuario tiene el rol solicitado o es superadmin
        if ($user->roles->contains('slug', $rol) || $user->roles->contains('slug', 'superadmin')) {
            return $next($request);
        }

        abort(403, 'No tienes permisos para acceder a esta sección.');
    }
} 