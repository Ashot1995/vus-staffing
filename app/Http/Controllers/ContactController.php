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
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Save the submission to database
        ContactSubmission::create($validated);

        return back()->with('success', 'Tack för ditt meddelande! Vi återkommer så snart som möjligt.');
    }
}
