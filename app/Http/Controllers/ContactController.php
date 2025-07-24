<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Wyświetl stronę kontakt
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Przetwórz formularz kontaktowy
     */
    public function send(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000'
        ]);

        // Tutaj możesz dodać logikę wysyłania emaila
        // np. Mail::send() lub zapisanie do bazy danych

        return back()->with('success', 'Dziękujemy za wiadomość! Odpowiemy w ciągu 24 godzin.');
    }
}
