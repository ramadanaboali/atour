<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Currency;

class CurrencyMiddleware
{
    public function handle($request, Closure $next)
    {
        $currencyCode = $request->header('currency', 'SAR'); // default SAR
        $currency = Currency::where('code', $currencyCode)->first();

        if (!$currency) {
            $currency = Currency::where('code', 'SAR')->first();
        }

        app()->instance('currency', $currency);

        // app()->singleton('currency', fn () => $currency);

        return $next($request);
    }
}
