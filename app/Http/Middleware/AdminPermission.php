<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;

class AdminPermission
{
    protected $auth;
    protected $response;

    public function __construct(Guard $auth, ResponseFactory $response)
    {
        $this->auth = $auth;
        $this->response = $response;
    }

    public function handle($request, Closure $next, $permissionName)
    {

        if (auth()->user()->getAllPermissions()->where('name',$permissionName)->first()) {
            return $next($request);
        }
        abort(403);
        return apiResponse(false, null, 'You Dont have Permissions', null, 403);

        // return $this->response->redirectTo('/admin/login');
    }
}
