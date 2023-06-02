<?php

namespace App\Http\Middleware\JournalEntries;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;

class Approve
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
            $user_job_permission = Permission::where('job_id', session('job')['job_id'])
                ->where('app_menu_id', 33)->first();
            if (isset($user_job_permission) && $user_job_permission->permission_approve == 1) {
                return $next($request);
            } else {
                return back()->with(['error' => 'ليس لديك صلاحيه']);
            }
        } else {
            return $next($request);
        }
    }
}
