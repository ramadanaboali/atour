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
        //lang = header language
        $lang = $request->header('lang');
        if ($lang) {
            App::setLocale($lang);
        } else {
            App::setLocale('ar');
        }

        return $next($request);
    }
}
