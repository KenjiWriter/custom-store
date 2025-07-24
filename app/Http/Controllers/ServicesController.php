<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * Wyświetl stronę usług
     */
    public function index()
    {
        return view('services');
    }
}
