<?php

namespace App\Http\Middleware\CompaniesApp;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;

class Update
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->user_type_id != 1) {
            $user_job_permission = Permission::where('job_id', session('job')['job_id'])
                ->where('app_menu_id', 2)->first();
            if (isset($user_job_permission) && $user_job_permission->permission_update == 1) {
                return $next($request);
            } else {
                return back()->with(['error' => 'ليس لديك صلاحيه الدخول']);
            }
        } else {
            return $next($request);
        }

    }
}
