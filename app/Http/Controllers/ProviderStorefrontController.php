<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProviderStorefrontController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type');

        $query = User::approvedContentProvidersQuery()
            ->withCount(['providedActivities as approved_activities_count' => function ($q) {
                $q->where('provider_review_status', 'approved');
            }])
            ->withAvg(['providedActivities' => function ($q) {
                $q->where('provider_review_status', 'approved');
            }], 'rating');

        if ($type) {
            $query->where('activity_type', $type);
        }

        $providers = $query->paginate(12)->withQueryString();

        $types = User::approvedContentProvidersQuery()
            ->reorder()
            ->whereNotNull('activity_type')
            ->where('activity_type', '!=', '')
            ->select('activity_type')
            ->distinct()
            ->orderBy('activity_type')
            ->pluck('activity_type');

        return view('website.partners.index', compact('providers', 'types', 'type'));
    }

    public function show(User $user): View
    {
        abort_unless($user->isApprovedContentProvider(), 404);

        $user->load(['badges']);

        $activities = $user->providedActivities()
            ->where('provider_review_status', 'approved')
            ->with(['destination', 'reviews'])
            ->latest()
            ->get();

        $approvedCount = $activities->count();

        $avgRating = $activities->isEmpty()
            ? 0.0
            : round((float) $activities->avg('rating'), 1);

        $bookingsCount = Booking::query()
            ->whereHas('activity', fn ($q) => $q->where('provider_id', $user->id))
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();

        $publicUrl = route('providers.storefront', $user);

        return view('providers.storefront', compact(
            'user',
            'activities',
            'approvedCount',
            'avgRating',
            'bookingsCount',
            'publicUrl'
        ));
    }
}
