<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;

class AuthenticateUser
{
    protected $auth;
    protected $response;

    public function __construct(Guard $auth, ResponseFactory $response)
    {
        $this->auth = $auth;
        $this->response = $response;
    }

    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            if (in_array(Auth::user()->type, [User::TYPE_COMPANY,User::TYPE_OWNER])) {
                return $next($request);
            }
        }
        return apiResponse(false, [], 'Unauthinticated', null, 400);
        // Auth::logout();
        // return $this->response->redirectTo('/login');
    }
}
