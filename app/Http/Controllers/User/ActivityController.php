<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityAssociation;
use App\Models\Destination;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['destination', 'reviews.user'])
            ->where('provider_review_status', 'approved');

        // البحث بالاسم أو الوصف
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%")
                  ->orWhere('location_ar', 'like', "%{$search}%")
                  ->orWhere('location_en', 'like', "%{$search}%");
            });
        }

        // البحث حسب النوع
        if ($request->has('type') && $request->type) {
            $query->where(function ($q) use ($request) {
                $q->where('type_ar', $request->type)
                    ->orWhere('type_en', $request->type);
            });
        }

        // البحث حسب الوجهة
        if ($request->has('destination_id') && $request->destination_id) {
            $query->where('destination_id', $request->destination_id);
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->boolean('must_visit')) {
            $query->where('is_must_visit', true);
        }

        if ($request->boolean('monthly')) {
            $query->whereNotNull('event_date')
                ->whereMonth('event_date', now()->month)
                ->whereYear('event_date', now()->year);
        }

        // الفلترة حسب السعر
        if ($request->has('price_min') && $request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max') && $request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        // الفلترة حسب التقييم
        if ($request->has('min_rating') && $request->min_rating) {
            $query->where('rating', '>=', $request->min_rating);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        if ($request->filled('duration_min')) {
            $query->where('duration_minutes', '>=', (int) $request->duration_min);
        }

        if ($request->filled('duration_max')) {
            $query->where('duration_minutes', '<=', (int) $request->duration_max);
        }

        if ($request->filled('requires_booking')) {
            $query->where('requires_booking', (bool) $request->requires_booking);
        }

        // الترتيب
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $activities = $query->paginate(12)->withQueryString();
        $destinations = Destination::all();

        // الجولات والتجارب المميزة
        $featuredActivities = Activity::with(['destination', 'reviews'])
            ->featured()
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();
        
        // المعالم التي يجب عليك زيارتها
        $mustVisitActivities = Activity::with(['destination', 'reviews'])
            ->mustVisit()
            ->orderBy('rating', 'desc')
            ->take(6)
            ->get();
        
        // الفعاليات هذا الشهر
        $monthlyEvents = Activity::with(['destination', 'reviews'])
            ->thisMonth()
            ->orderBy('event_date', 'asc')
            ->get();

        return view('website.activities.index', compact('activities', 'destinations', 'featuredActivities', 'mustVisitActivities', 'monthlyEvents'));
    }

    public function show(Activity $activity)
    {
        if ($activity->provider_review_status !== 'approved') {
            abort(404);
        }

        $activity->load([
            'destination.activeHotels',
            'provider',
            'gallery' => fn ($q) => $q->orderBy('order'),
            'approvedReviews' => function($query) {
                $query->with('user')->latest();
            },
            'approvedComments' => function($query) {
                $query->with('user')->latest();
            }
        ]);
        
        // الحصول على تقييم المستخدم الحالي إن وجد (حتى لو غير معتمد)
        $userReview = null;
        $isFavorited = false;
        if (auth()->check()) {
            $userReview = $activity->reviews()
                ->where('user_id', auth()->id())
                ->with('user')
                ->first();
            $isFavorited = $activity->isFavoritedBy(auth()->id());
        }

        $relatedActivityIds = ActivityAssociation::query()
            ->where('activity_id', $activity->id)
            ->where('confidence', '>=', 0.1)
            ->orderByDesc('confidence')
            ->orderByDesc('lift')
            ->take(6)
            ->pluck('associated_activity_id');

        // توصية مبنية على الارتباط، ثم fallback ذكي لو لا يوجد بيانات
        if ($relatedActivityIds->isNotEmpty()) {
            $relatedActivities = Activity::with(['destination', 'reviews'])
                ->whereIn('id', $relatedActivityIds)
                ->where('provider_review_status', 'approved')
                ->get()
                ->sortBy(fn ($item) => $relatedActivityIds->search($item->id))
                ->values();
        } else {
            $activityTypes = collect([$activity->type_ar, $activity->type_en, $activity->type])
                ->filter()
                ->unique()
                ->values();

            $relatedActivities = Activity::where('id', '!=', $activity->id)
                ->where('provider_review_status', 'approved')
                ->where(function ($query) use ($activity, $activityTypes) {
                    $query->where('destination_id', $activity->destination_id)
                        ->orWhere(function ($typeQuery) use ($activityTypes) {
                            $typeQuery->whereIn('type_ar', $activityTypes)
                                ->orWhereIn('type_en', $activityTypes);
                        });
                })
                ->with('destination')
                ->withCount('bookings')
                ->orderByDesc('bookings_count')
                ->orderByDesc('rating')
                ->take(6)
                ->get();
        }

        return view('website.activities.show', compact('activity', 'userReview', 'isFavorited', 'relatedActivities'));
    }
}

