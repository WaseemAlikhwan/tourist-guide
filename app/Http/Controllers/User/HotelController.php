<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class HotelController extends Controller
{
    public function show(Hotel $hotel)
    {
        // تحميل العلاقات المطلوبة
        $hotel->load('destination');
        
        // حساب المسافة من الوجهة إذا كانت موجودة
        $distance = null;
        if ($hotel->destination) {
            $distance = $hotel->distanceFromDestination($hotel->destination);
        }

        return view('website.hotels.show', compact('hotel', 'distance'));
    }
}
