<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;

class InteractiveMapController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('activities')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('website.interactive-map.index', compact('destinations'));
    }
}

