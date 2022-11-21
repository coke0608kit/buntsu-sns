<?php

namespace App\Http\Middleware;

class ForceHttps
{
    public function handle($request, Closure $next)
    {
        if (\App::environment(['production']) && $_SERVER["HTTP_X_FORWARDED_PROTO"] != 'https') {
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
