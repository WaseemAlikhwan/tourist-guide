<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $allowedLocales = ['ar', 'en'];

        // Allow explicit runtime override via query string.
        // Example: ?lang=en or ?locale=en
        $requestedLocale = $request->query('lang', $request->query('locale'));
        if (is_string($requestedLocale) && in_array($requestedLocale, $allowedLocales, true)) {
            session(['locale' => $requestedLocale]);
            $locale = $requestedLocale;
        } else {
            $locale = session('locale', config('app.locale'));
            if (!in_array($locale, $allowedLocales, true)) {
                $locale = 'ar';
            }
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
