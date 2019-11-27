<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Validation\UnauthorizedException;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\JWTAuth::getToken()) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}