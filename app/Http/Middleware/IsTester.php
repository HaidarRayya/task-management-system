<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTester
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = Role::find(Auth::user()->role_id);
        if (!($role->name == UserRole::TESTER->value)) {
            abort(403, "You don't have tester access.");
        }
        return $next($request);
    }
}
