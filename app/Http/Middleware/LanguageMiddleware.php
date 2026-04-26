<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Admin rotaları için her zaman Türkçe zorla (Prefix olsa bile)
        if ($request->is('admin*') || $request->is('*/admin*')) {
            App::setLocale('tr');
            return $next($request);
        }

        if (Session::has('locale')) {
            $locale = Session::get('locale');
            App::setLocale($locale);
            \Carbon\Carbon::setLocale($locale);
        } else {
            \Carbon\Carbon::setLocale(App::getLocale());
        }

        return $next($request);
    }
}
