<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUserThankYou;
use App\Mail\ContactAdminNotification;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        foreach ($validated as $key => $value) {
            if (is_string($value)) {
                $validated[$key] = strip_tags($value);
            }
        }

        $contactMessage = ContactMessage::create($validated);

        try {
            // Send Thank You email to User
            Mail::to($contactMessage->email)->send(new ContactUserThankYou($contactMessage));

            // Send Notification email to Admin
            $adminEmail = env('MAIL_ADMIN_ADDRESS', 'admin@remenanthealth.com');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new ContactAdminNotification($contactMessage));
            }
        } catch (\Exception $e) {
            // Log the error but don't stop the user experience if mail fails
            \Illuminate\Support\Facades\Log::error('Contact form email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you shortly.');
    }
}
