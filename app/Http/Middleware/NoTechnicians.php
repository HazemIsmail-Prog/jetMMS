<?php

namespace App\Http\Middleware;

use App\Models\Title;
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
        if (in_array($request->user()->title_id,Title::TECHNICIANS_GROUP)) {
            return redirect()->route('technicianPage.index');
        }

        return $next($request);
    }
}
