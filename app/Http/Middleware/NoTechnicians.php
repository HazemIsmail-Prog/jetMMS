<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoTechnicians
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->user()->title_id,[10,11])) {
            return redirect()->route('technician_page');
        }

        return $next($request);
    }
}
