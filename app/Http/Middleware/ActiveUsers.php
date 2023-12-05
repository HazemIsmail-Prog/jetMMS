<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if($request->user()){
        //     dd(auth()->user()->acitve);
        // };
        // if (!$request->user()->acitve) {
        //     $request->session()->flush();
        //     return redirect()->route('login')->with(['fail' => 'Your account is not activated!']);
        // }
        return $next($request);
    }
}
