<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contactSetting = ContactSetting::first();
        
        return view('pages.contact', [
            'contactSetting' => $contactSetting,
        ]);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:private,company',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'privacy_consent' => 'required|accepted',
        ]);

        // Save the submission to database
        ContactSubmission::create($validated);

        return back()->with('success', __('messages.contact.form.success_message'));
    }
}
