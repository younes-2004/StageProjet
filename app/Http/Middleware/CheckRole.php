<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect('login');
        }

        // Vérifier si l'utilisateur a le rôle requis
        if (Auth::user()->role != $role) {
            return redirect()->route('dashboard')
                ->with('error', 'Vous n\'avez pas l\'autorisation d\'accéder à cette page.');
        }

        return $next($request);
    }
}