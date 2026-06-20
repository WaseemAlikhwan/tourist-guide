<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Activity;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // الوجهات المميزة أو الأحدث
        $featuredDestinations = Destination::with(['activities', 'gallery'])
            ->withCount('activities')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // الأنشطة المميزة (الأعلى تقييماً)
        $featuredActivities = Activity::with(['destination', 'reviews'])
            ->withCount('reviews')
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();

        // الأنشطة التي يجب زيارتها
        $mustVisitActivities = Activity::with('destination')
            ->where('is_must_visit', true)
            ->orderBy('rating', 'desc')
            ->take(3)
            ->get();

        // جميع الوجهات للخريطة
        $allDestinations = Destination::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // آراء العملاء المعتمدة للعرض في الصفحة الرئيسية
        $featuredReviews = Review::approved()
            // تجنب تقييد الأعمدة باسماء legacy (مثل name) لأن الجداول أصبحت متعددة اللغة
            ->with(['user', 'activity.destination'])
            ->orderByDesc('rating')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        return view('website.home', compact(
            'featuredDestinations',
            'featuredActivities',
            'mustVisitActivities',
            'allDestinations',
            'featuredReviews'
        ));
    }
}

