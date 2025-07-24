<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Wyświetl stronę "O nas"
     */
    public function index()
    {
        return view('about');
    }
}
