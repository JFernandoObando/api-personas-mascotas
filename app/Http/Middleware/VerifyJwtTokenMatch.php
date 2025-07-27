<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyJwtTokenMatch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['error' => 'Usuario no autenticado'], 401);
            }

            $incomingToken = JWTAuth::getToken()->get();
            if ($user->jwt_token !== $incomingToken) {
                return response()->json(['error' => 'Token revocado o inválido'], 401);
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No autorizado: ' . $e->getMessage()], 401);
        }
    }
}
