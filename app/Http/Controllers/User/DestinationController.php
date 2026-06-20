<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        $query = Destination::with('activities');

        // البحث بالاسم أو البلد
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%")
                  ->orWhere('country_ar', 'like', "%{$search}%")
                  ->orWhere('country_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%")
                  ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        // الفلترة حسب البلد
        if ($request->has('country') && $request->country) {
            $query->where(function ($q) use ($request) {
                $q->where('country_ar', $request->country)
                    ->orWhere('country_en', $request->country);
            });
        }

        // الترتيب
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'name':
                $query->orderByRaw("COALESCE(NULLIF(name_" . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), name_ar, name_en) asc");
                break;
            case 'activities_count':
                $query->withCount('activities')->orderBy('activities_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $destinations = $query->paginate(12)->withQueryString();
        $countries = Destination::query()
            ->selectRaw('DISTINCT COALESCE(NULLIF(country_' . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), country_ar, country_en) as country_name")
            ->pluck('country_name')
            ->filter()
            ->sort()
            ->values();

        return view('website.destinations.index', compact('destinations', 'countries'));
    }

    public function show(Destination $destination)
    {
        $destination->load(['activities', 'activeHotels']);
        
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = $destination->isFavoritedBy(auth()->id());
        }

        // الحصول على معلومات الطقس إذا كانت الوجهة لديها إحداثيات
        $weather = null;
        if ($destination->latitude && $destination->longitude) {
            $weather = $this->weatherService->getFullWeatherInfo($destination);
        }
        
        return view('website.destinations.show', compact('destination', 'isFavorited', 'weather'));
    }

    /**
     * مقارنة الوجهات
     */
    public function compare(Request $request)
    {
        $destinationIds = $request->input('destinations', []);
        
        // الحد الأقصى 4 وجهات للمقارنة
        $destinationIds = array_slice(array_filter($destinationIds), 0, 4);
        
        if (empty($destinationIds)) {
            return redirect()->route('destinations.index')
                ->with('error', 'يرجى اختيار وجهة واحدة على الأقل للمقارنة');
        }

        $destinations = Destination::with(['activities', 'activeHotels', 'weather'])
            ->whereIn('id', $destinationIds)
            ->get()
            ->keyBy('id')
            ->sortBy(function($dest) use ($destinationIds) {
                $index = array_search($dest->id, $destinationIds);
                return $index !== false ? $index : 999;
            })
            ->values();

        if ($destinations->isEmpty()) {
            return redirect()->route('destinations.index')
                ->with('error', 'الوجهات المحددة غير موجودة');
        }

        return view('website.destinations.compare', compact('destinations'));
    }

    /**
     * مولد خط سير ذكي
     */
    public function generateItinerary(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'days' => 'required|integer|min:1|max:14',
            'budget' => 'nullable|numeric|min:0',
            'interests' => 'nullable|array'
        ]);

        $destination = Destination::with('activities')->findOrFail($request->destination_id);
        $days = $request->days;
        $budget = $request->budget ?? PHP_INT_MAX;
        $interests = $request->interests ?? [];

        // إنشاء خط سير ذكي بناءً على المعايير
        $activities = $destination->activities;
        
        if ($activities->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد أنشطة متاحة لهذه الوجهة'
            ], 400);
        }
        
        // فلترة حسب الميزانية والاهتمامات
        if ($budget < PHP_INT_MAX && $budget > 0) {
            $budgetPerDay = $budget / $days;
            $activities = $activities->filter(function($activity) use ($budgetPerDay) {
                return ($activity->price ?? 0) <= $budgetPerDay;
            })->values();
        }

        if (!empty($interests)) {
            $activities = $activities->filter(function($activity) use ($interests) {
                return in_array($activity->type, $interests);
            })->values();
        }

        if ($activities->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد أنشطة متاحة بناءً على المعايير المحددة'
            ], 400);
        }

        // توزيع الأنشطة على الأيام
        $itinerary = [];
        $activitiesCount = $activities->count();
        $activitiesPerDay = max(1, ceil($activitiesCount / $days));
        $activityChunks = $activities->chunk($activitiesPerDay);

        foreach ($activityChunks as $day => $dayActivities) {
            if ($day >= $days) break;
            
            $totalPrice = $dayActivities->sum('price');
            $itinerary[] = [
                'day' => $day + 1,
                'activities' => $dayActivities->values(),
                'total_price' => $totalPrice,
                'estimated_duration' => $dayActivities->count() * 3 . ' ساعة' // تقدير: 3 ساعات لكل نشاط
            ];
        }

        $itineraryData = array_map(function($day) {
            return [
                'day' => $day['day'],
                'activities' => $day['activities']->map(function($activity) {
                    return [
                        'id' => $activity->id,
                        'name' => $activity->name ?? '',
                        'type' => $activity->type ?? '',
                        'price' => floatval($activity->price ?? 0),
                        'description' => strip_tags($activity->description ?? ''),
                        'rating' => floatval($activity->rating ?? 0)
                    ];
                })->values()->toArray(),
                'total_price' => floatval($day['total_price']),
                'estimated_duration' => $day['estimated_duration']
            ];
        }, $itinerary);

        return response()->json([
            'success' => true,
            'itinerary' => $itineraryData,
            'total_budget' => collect($itinerary)->sum('total_price'),
            'destination' => $destination->name
        ]);
    }

}

