<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function about()
    {
        return view('pages.about');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function cgv()
    {
        return view('pages.cgv');
    }

    public function contactSend(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string',
            'message' => 'required|string|min:10',
        ]);

        // Log the contact request (mail integration can be added later)
        \Log::info('Contact form submission', $validated);

        // Optionally send a simple notification email if SMTP is configured
        try {
            Mail::raw(
                "Nouveau message de {$validated['name']} ({$validated['email']})\n\nSujet : {$validated['subject']}\n\n{$validated['message']}",
                function ($msg) use ($validated) {
                    $msg->to('support@concertcm.com')
                        ->replyTo($validated['email'], $validated['name'])
                        ->subject("[ConcertCM+] {$validated['subject']} — {$validated['name']}");
                }
            );
        } catch (\Exception $e) {
            // Silently fail if mail is not configured
        }

        return redirect()->route('faq')->with('contact_sent', true);
    }
}
