<?php
namespace TechlifyInc\LaravelRbac\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

/**
 * Middleware to enforce access control on routes
 * 
 * @author 
 * @since 20180407
 */
class LaravelRbacEnforcePermission
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param String $permission A comma separated list of restrictors
     * 
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        /* Check if the user is logged in */
        if (!Auth::check()) {
            return response()->json(['error' => "You're not authorized to perform this action. "], 403);
        }

        /* Check if the user has the permission */
        if (!auth()->user()->hasPermission($permission)) {
            return response()->json(['error' => "You are unauthorized to perform this action. "], 403);
        }

        return $next($request);
    }
}
