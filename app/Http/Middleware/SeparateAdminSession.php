<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeparateAdminSession
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin/*') || $request->is('admin')) {
            config(['session.cookie' => 'jobportal_admin_session']);
        }

        return $next($request);
    }
}