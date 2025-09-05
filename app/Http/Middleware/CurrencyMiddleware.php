<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Currency;

class CurrencyMiddleware
{
    public function handle($request, Closure $next)
    {
        $currencyCode = $request->header('X-Currency', 'SAR'); // default SAR
        $currency = Currency::where('code', $currencyCode)->first();

        if (!$currency) {
            $currency = Currency::where('code', 'SAR')->first();
        }

        app()->singleton('currency', fn () => $currency);

        return $next($request);
    }
}
