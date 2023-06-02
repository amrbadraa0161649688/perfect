<?php

namespace App\Http\Middleware\Trips;

use Closure;
use Illuminate\Http\Request;

class Launch
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->user_type_id != 1) {
            if (auth()->user()->additionRols->where('rols_id', 10)) {
                return $next($request);
            } else {
                return back()->with(['error' => 'ليس لديك صلاحيه الدخول']);
            }
        } else {
            return $next($request);
        }
    }
}
