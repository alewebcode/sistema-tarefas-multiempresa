<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'message' => 'Token expirado.',
                'error' => 'token_expired'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'message' => 'Token inválido.',
                'error' => 'token_invalid'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Token não fornecido ou inválido.',
                'error' => 'token_absent'
            ], 401);
        }

        return $next($request);
    }
}