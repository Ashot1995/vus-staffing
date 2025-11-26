<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function consent(Request $request)
    {
        $request->session()->put('cookie_consent', true);
        $request->session()->put('cookie_consent_at', now());

        // If user is authenticated, update their GDPR consent
        if (auth()->check()) {
            $user = auth()->user();
            $user->update([
                'gdpr_consent_at' => now(),
            ]);
        }

        return back()->with('cookie_consent', __('messages.cookie.consent_given'));
    }
}
