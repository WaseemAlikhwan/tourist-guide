<?php

namespace App\Services;

use App\Models\Destination;
use App\Models\Activity;
use App\Models\Review;
use App\Models\Booking;
use App\Models\Contact;
use Carbon\Carbon;

class DashboardService
{
    public function getStatistics()
    {
        // حساب النمو في الحجوزات (مقارنة هذا الأسبوع بالأسبوع الماضي)
        $thisWeekBookings = Booking::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        $lastWeekBookings = Booking::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count();

        $bookingsGrowth = 0;
        if ($lastWeekBookings > 0) {
            $bookingsGrowth = round((($thisWeekBookings - $lastWeekBookings) / $lastWeekBookings) * 100);
        } elseif ($thisWeekBookings > 0) {
            $bookingsGrowth = 100;
        }

        return [
            'destinationsCount' => Destination::count(),
            'activitiesCount'   => Activity::count(),
            'reviewsCount'      => Review::count(),
            'usersCount'        => \App\Models\User::count(),
            'favoritesCount'    => \App\Models\Favorite::count(),
            'avgRating'         => Activity::avg('rating') ?? 0,
            'totalActivitiesPrice' => Activity::sum('price') ?? 0,
            'thisWeekBookings' => $thisWeekBookings,
            'bookingsGrowth'   => $bookingsGrowth,
            'newContactsCount' => Contact::where('status', 'new')->count(),
        ];
    }

    public function getRecentDestinations()
    {
        return Destination::withCount('activities')
            ->with('activities:id,destination_id,rating')
            ->latest()
            ->take(5)
            ->get();
    }

    public function getRecentActivities()
    {
        return Activity::with('destination')
            ->latest()
            ->take(5)
            ->get();
    }
}
