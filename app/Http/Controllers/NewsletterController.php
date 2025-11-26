<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;

        // Check if already subscribed
        $subscription = NewsletterSubscription::where('email', $email)->first();

        if ($subscription) {
            if ($subscription->is_active) {
                return back()->with('newsletter_error', __('messages.newsletter.already_subscribed'));
            } else {
                // Reactivate subscription
                $subscription->update([
                    'is_active' => true,
                    'subscribed_at' => now(),
                    'unsubscribed_at' => null,
                ]);
                return back()->with('newsletter_success', __('messages.newsletter.subscribed'));
            }
        }

        // Create new subscription
        NewsletterSubscription::create([
            'email' => $email,
            'is_active' => true,
            'subscribed_at' => now(),
        ]);

        return back()->with('newsletter_success', __('messages.newsletter.subscribed'));
    }
}
