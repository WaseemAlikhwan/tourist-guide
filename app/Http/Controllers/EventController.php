<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\Destination;

class EventController extends Controller
{
    public function calendar(Request $request)
    {
        // جلب جميع الوجهات للفلتر
        $destinations = Destination::query()
            ->select('id', 'name_ar', 'name_en')
            ->orderByRaw("COALESCE(NULLIF(name_" . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), name_ar, name_en) asc")
            ->get();
        
        // جلب أنواع الفعاليات المختلفة للفلتر
        $eventTypes = Activity::events()
            ->whereNotNull('type_ar')
            ->distinct()
            ->pluck(app()->getLocale() === 'en' ? 'type_en' : 'type_ar')
            ->sort()
            ->values();
        
        return view('website.events.calendar', compact('destinations', 'eventTypes'));
    }

    /**
     * جلب الفعاليات بصيغة JSON للتقويم
     */
    public function getEvents(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');
            $destination_id = $request->input('destination_id');
            $type = $request->input('type');
            $search = $request->input('search');

            $query = Activity::events()
                ->with('destination')
                ->select(
                    'id',
                    'name_ar',
                    'name_en',
                    'event_date',
                    'type_ar',
                    'type_en',
                    'destination_id',
                    'description_ar',
                    'description_en',
                    'location_ar',
                    'location_en',
                    'image',
                    'price'
                )
                ->whereNotNull('event_date')
                ->orderBy('event_date', 'asc');

            // فلترة حسب النطاق الزمني
            if ($start && $end) {
                $query->whereBetween('event_date', [$start, $end]);
            } else {
                // إذا لم يتم تحديد نطاق، جلب الفعاليات من السنة الحالية والمستقبلية
                $currentYear = now()->year;
                $query->whereYear('event_date', '>=', $currentYear);
            }

            // فلترة حسب الوجهة
            if ($destination_id) {
                $query->where('destination_id', $destination_id);
            }

            // فلترة حسب النوع
            if ($type) {
                $query->where(function ($q) use ($type) {
                    $q->where('type_ar', $type)->orWhere('type_en', $type);
                });
            }

            // البحث في الاسم والوصف والموقع
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name_ar', 'like', '%' . $search . '%')
                      ->orWhere('name_en', 'like', '%' . $search . '%')
                      ->orWhere('description_ar', 'like', '%' . $search . '%')
                      ->orWhere('description_en', 'like', '%' . $search . '%')
                      ->orWhere('location_ar', 'like', '%' . $search . '%')
                      ->orWhere('location_en', 'like', '%' . $search . '%');
                });
            }

            $events = $query->get()->map(function ($activity) {
                // تحديد لون الفعالية حسب النوع
                $color = $this->getEventColor($activity->type);
                
                return [
                    'id' => $activity->id,
                    'title' => $activity->name,
                    'start' => $activity->event_date->format('Y-m-d'),
                    'color' => $color,
                    'url' => route('activities.show', $activity->id),
                    'image' => $activity->image_url ?? null,
                    'price' => $activity->price,
                    'location' => $activity->location,
                    'extendedProps' => [
                        'type' => $activity->type ?? 'فعالية',
                        'destination' => $activity->destination->name ?? 'غير محدد',
                        'destination_id' => $activity->destination_id,
                        'description' => $activity->description,
                    ]
                ];
            });

            return response()->json($events);
        } catch (\Exception $e) {
            \Log::error('Error fetching events: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء جلب الفعاليات'], 500);
        }
    }

    /**
     * تحديد لون الفعالية حسب النوع
     */
    private function getEventColor($type)
    {
        $colors = [
            'مهرجان' => '#DC143C',
            'معرض' => '#228B22',
            'حفلة موسيقية' => '#8B4513',
            'مؤتمر' => '#4169E1',
            'ورشة عمل' => '#FF6347',
            'مسابقة' => '#FFD700',
            'احتفال' => '#FF1493',
            'معرض فني' => '#9932CC',
        ];

        return $colors[$type] ?? '#A0522D';
    }
}

