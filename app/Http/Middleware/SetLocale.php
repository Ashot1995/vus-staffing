<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from session
        $locale = Session::get('locale');
        
        // If no locale in session, use default from config (which is 'sv')
        if (!$locale) {
            $locale = config('app.locale', 'sv');
            // Save to session so it persists
            Session::put('locale', $locale);
        }

        // Validate locale - default to Swedish if invalid
        if (! in_array($locale, ['en', 'sv'])) {
            $locale = 'sv';
            Session::put('locale', $locale);
        }

        // Ensure locale is set
        App::setLocale($locale);

        return $next($request);
    }
}
