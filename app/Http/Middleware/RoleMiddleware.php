<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$role): Response
    {
        $user = $request->user();

        if(!$user)
            return abort(401);

        if(!in_array($user->role->name, $role))
            return abort(403, 'Acesso Negado.');

        return $next($request);
    }
}
