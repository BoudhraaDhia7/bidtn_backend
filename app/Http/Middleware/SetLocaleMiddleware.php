<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocaleMiddleware
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
        if ($request->hasHeader('Accept-Language')) {
            $locale = $request->header('Accept-Language');

            if (in_array($locale, ['en', 'fr'])) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
