<?php


namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OpAuthenticate
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
        if (!\UserService::getCurrentUser()->hasRole(['examiner'])) {
            throw new NotFoundHttpException('无权访问');
        }

        return $next($request);
    }
}