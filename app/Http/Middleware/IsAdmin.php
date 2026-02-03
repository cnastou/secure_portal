<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated. If not, try to authenticate from a JWT
        // token provided in the Authorization header or in a cookie named "jwt_token".
        if (!auth()->check()) {
            $token = null;
            if ($request->bearerToken()) {
                $token = $request->bearerToken();
            } elseif ($request->cookie('jwt_token')) {
                $token = $request->cookie('jwt_token');
            }

            if ($token) {
                try {
                    $user = auth('api')->setToken($token)->authenticate();
                    if ($user) {
                        auth()->login($user);
                    }
                } catch (Exception $e) {
                    // Failed to authenticate with token; fall through to redirect
                }
            }

            if (!auth()->check()) {
                return redirect('/login');
            }
        }

        // Check if user is admin
        if (!auth()->user()->is_admin) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}
