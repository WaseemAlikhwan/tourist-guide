<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $service) {}

    public function index()
    {
        $stats = $this->service->getStatistics();
        $recentDestinations = $this->service->getRecentDestinations();
        $recentActivities = $this->service->getRecentActivities();

        return view('admin.dashboard', array_merge($stats, [
            'recentDestinations' => $recentDestinations,
            'recentActivities' => $recentActivities,
        ]));
    }
}
