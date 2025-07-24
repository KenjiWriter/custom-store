<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        try {
            // Tutaj możesz dodać logikę wysyłania emaila

            return back()->with('success', 'Dziękujemy za wiadomość! Odpowiemy najszybciej jak to możliwe.');
        } catch (\Exception $e) {
            return back()->with('error', 'Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie.');
        }
    }
}
