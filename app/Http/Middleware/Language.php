<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Session()->has('applocale') && array_key_exists(Session()->get('applocale'),config('languages'))){
            App::setLocale(Session()->get('applocale'));
        }else{
            App::setLocale(config('app.fallback_locale'));
        }
        return $next($request);
    }
}
