<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthMiddleware
{
    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            $this->auth->parseToken()->authenticate();
        }catch (Exception $e) {
            return response()->json(['message' => 'unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
