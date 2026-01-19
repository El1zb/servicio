<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WelcomeSection;

class WelcomeController extends Controller
{
    public function index()
    {
        // Traemos el registro con id = 1
        $welcome = WelcomeSection::find(1);

        return view('welcome', compact('welcome'));
    }
}
