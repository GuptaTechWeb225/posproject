<?php

namespace App\Http\Middleware;

use App\Exceptions\GeneralException;
use App\Helpers\Route\RouteToPermissionString;
use Closure;

class PermissionMiddleware
{
    use RouteToPermissionString;

    public function replace_first_position_string($string="", $finds = [], $replace="")
    {
        foreach($finds as $find){
            if (strpos($string, $find) === 0){
                return substr_replace($string,$replace,0, strlen($find));
                break;
            }
        }
        return $string;
    }

    private function return_custom_permission_string($permission_string)
    {
        $redirect_permission = [];
        return $redirect_permission[$permission_string] ?? $permission_string;
    }

    public function handle($request, Closure $next)
    {
        $route_name = $request->route()->getName();

        $route_name = $this->replace_first_position_string($route_name, ['core.','app.','tenant.']);

        $app_prefix = (strpos($route_name,"app_permission.") === 0) ? 'can_':"";
        $route_name = $this->replace_first_position_string($route_name, ['app_permission.']);

        if (!$route_name) {
            throw new GeneralException('Route must have a name');
        }

        /**
         * Avoid if the user is appAdmin
         */
        if (auth()->user()->isAppAdmin()) {
            return $next($request);
        }

        $permission_string = $this->setRouteName($route_name, $app_prefix)
            ->validateRouteName()
            ->getPermissionString();

        $permission_string = $this->return_custom_permission_string($app_prefix.$permission_string);


        /*
        * Authorizing user with permission and merge allowed resource into allowed_resource index
        * if the allowed_resource is empty array then all resource is allowed
        * otherwise only take what in that array
        * allowed resource will contain ID's of model which is permitted for currently logged in user
        */
        $meta = get_authorized($permission_string);
        $request->merge([
            'allowed_resource' => $meta
        ]);

        return $next($request);
    }

}
