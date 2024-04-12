<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if(Session::get('lang') == 'en') {
            App::setLocale('en');
        } else {
            App::setLocale('ar');
        }
        // dd(Session::get('lang'));

        return $next($request);
    }
}
