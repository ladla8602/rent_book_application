<?php

namespace App\Http\Middleware;

use Closure;
use App\Traits\RestApi;
use Config, Auth;

class CheckRole
{
    use RestApi;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role='')
    {
        if (!Auth::check()){
            return $this->resultResponse(
                      Config::get('restresponsecode.PERMISSION_DENIED'),
                      [],
                      [],
                      'Permission denied!'
                    );
        }

        $user = Auth::user();

        if($user->isAdmin() && $role == 'admin'){
            return $next($request);
        }

        if($user->isRenter() && $role == 'renter'){
            return $next($request);
        }

        return $this->resultResponse(
            Config::get('restresponsecode.PERMISSION_DENIED'),
            [],
            [],
            'Permission denied!'
          );
    }
}
