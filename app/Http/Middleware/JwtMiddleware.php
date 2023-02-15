<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->header('Authorization');

            if (!$token) {
                throw new JWTException('Token not found.');
            }

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                throw new JWTException('User not found');
            }

            $request->merge(['user' => $user]);

            return $next($request);
        } catch (JWTException $error) {
            return response()->json(['message' => $error->getMessage()], 401);
        }
    }
}