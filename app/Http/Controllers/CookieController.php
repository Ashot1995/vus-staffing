<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    public function consent(Request $request)
    {
        $consentType = $request->input('consent_type', 'reject'); // accept, customize, reject
        
        // Store consent in cookie (persists across sessions)
        $cookie = Cookie::make('cookie_consent', $consentType, 60 * 24 * 365); // 1 year
        $cookieAt = Cookie::make('cookie_consent_at', now()->toDateTimeString(), 60 * 24 * 365);
        
        // Also store in session for immediate access
        $request->session()->put('cookie_consent', $consentType);
        $request->session()->put('cookie_consent_at', now());

        // Store preferences if customize was selected
        if ($consentType === 'customize') {
            $analytics = $request->input('analytics', false);
            $marketing = $request->input('marketing', false);
            $request->session()->put('cookie_preferences', [
                'analytics' => $analytics,
                'marketing' => $marketing,
            ]);
        }

        // If user is authenticated, update their GDPR consent
        if (auth()->check()) {
            $user = auth()->user();
            $user->update([
                'gdpr_consent_at' => now(),
            ]);
        }

        return back()
            ->withCookie($cookie)
            ->withCookie($cookieAt)
            ->with('cookie_consent', __('messages.cookie.consent_given'));
    }
}
