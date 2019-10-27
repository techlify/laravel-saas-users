<?php
namespace Techlify\LaravelSaasUser\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Techlify\LaravelSaasUser\Entities\UserType;

/**
 * Middleware to enforce access control on routes
 * 
 * @author 
 * @since 20180407
 */
class TechlifyAccessControl
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param String $restrictors A comma separated list of restrictors
     *
     * @return mixed
     *
     * Restrictors format
     *  Permission Restrictor: ps:permission (eg: ps:user_read)
     *  Permission Restrictor: permission (eg: user_read)
     *  User Type Restrictor: ut:type (eg: ut:bis-admin)
     *  State Restrictor: state:state eg(state:logged-in)
     */
    public function handle($request, Closure $next, ...$restrictors)
    {
        foreach ($restrictors as $restrictor) {
            /* Lets break down the restrictor to get the type */
            $restrictorParts = explode(":", $restrictor);

            switch ($restrictorParts[0]) {
                    /* If no restrictor type is specified, or if it's a Permission Restrictor (PS) - then we need to handle it as a permission slug */
                default:
                case "ps":
                    if (!Auth::check()) {
                        return response()->json(['error' => "You're not authorized to perform this action. "], 403);
                    }
                    $permission = $restrictorParts[0] == "ps" ? $restrictorParts[1] : $restrictorParts[0];
                    if (!auth()->user()->hasPermission($permission)) {
                        return response()->json(['error' => "You're not authorized to perform this action. "], 403);
                    }
                    break;
                    /* If System Role (SR) is specified, it's a restrictor based on the System Role of the user */
                case "ut":
                    if (!Auth::check()) {
                        return response()->json(['error' => "You're not authorized to perform this action. "], 403);
                    }
                    $userTypeCode = $restrictorParts[1];
                    if (UserType::getUserTypeIdFromCode($userTypeCode) != auth()->user()->user_type_id) {
                        return response()->json(['error' => "You're not authorized to perform this action. "], 403);
                    }
                    break;
                    /* State restrictors restrict based on the state of the system */
                case "state":
                    $state = isset($restrictorParts[1]) ? $restrictorParts[1] : 'logged-in';
                    if (!$this->checkStateRestrictor($state)) {
                        return response()->json(['error' => "You're not authorized to perform this action. "], 403);
                    }
                    break;
            }
        }

        return $next($request);
    }

    /**
     * @param string $state
     * @return bool
     */
    final private function checkStateRestrictor($state = "logged-in")
    {
        switch ($state) {
            case "logged-in":
                return Auth::check();
            case "not-logged-in":
                return !Auth::check();
        }

        return false;
    }
}
