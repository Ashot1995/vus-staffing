<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\ContactSetting;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

        // Send email notification
        try {
            Mail::to('abdulrazek.mahmoud@vus-bemanning.se')->send(new ContactFormMail($validated));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send contact form email: ' . $e->getMessage());
        }

        return back()->with('success', __('messages.contact.form.success_message'));
    }
}
