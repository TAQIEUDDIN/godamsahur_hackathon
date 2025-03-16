<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrayerViewController extends Controller
{
    public function index()
    {
        return view('prayer-times');
    }
} 