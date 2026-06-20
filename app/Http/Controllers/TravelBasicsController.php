<?php

namespace App\Http\Controllers;

use App\Models\TravelBasic;
use Illuminate\Http\Request;

class TravelBasicsController extends Controller
{
    public function index()
    {
        $travelBasics = TravelBasic::active()->ordered()->get();
        return view('website.travel-basics.index', compact('travelBasics'));
    }

    public function show(TravelBasic $travelBasic)
    {
        // التأكد من أن الأساسيات نشطة
        if (!$travelBasic->is_active) {
            abort(404);
        }
        
        return view('website.travel-basics.show', compact('travelBasic'));
    }
}

