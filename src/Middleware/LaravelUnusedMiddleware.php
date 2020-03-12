<?php
namespace TypeHints\Unused\Middleware;

use Closure;
use Illuminate\Http\Request;

class LaravelUnusedMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!app('config')->get('app.debug')) {
            abort(404);
        }

        return $next($request);
    }
}
