<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Destination;
use App\Services\ProviderEarningsService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function __construct(private ProviderEarningsService $earnings) {}

    public function index(): View
    {
        $user = auth()->user();
        $totalEarned = $this->earnings->totalEarned($user);
        $bookingsCount = $this->earnings->countQualifying($user);
        $recentBookings = $this->earnings->recentBookings($user);
        $commissionPercent = $this->earnings->commissionPercent();
        $activitiesCount = $user->providedActivities()->count();
        $dailySeries = $this->earnings->dailyEarningsSeries($user);
        $monthlySeries = $this->earnings->monthlyEarningsSeries($user);

        $todayBookings = $this->earnings->allBookingsQuery($user)
            ->whereDate('booking_date', today())
            ->with(['activity', 'user'])
            ->orderBy('booking_date')
            ->get();

        $weekEvents = $user->providedActivities()
            ->where('is_event', true)
            ->whereNotNull('event_date')
            ->whereBetween('event_date', [today(), today()->copy()->addDays(7)])
            ->with('destination')
            ->orderBy('event_date')
            ->get();

        $todayEvents = $weekEvents->filter(function ($event) {
            return optional($event->event_date)->isToday();
        })->values();

        $todayAgenda = collect();
        foreach ($todayBookings as $booking) {
            $todayAgenda->push([
                'type' => 'booking',
                'title' => $booking->activity?->name ?? 'نشاط بدون اسم',
                'meta' => $booking->user?->name ?? 'ضيف غير معروف',
                'status' => $booking->status,
                'sort_time' => optional($booking->booking_date)?->format('H:i') ?? '23:59',
            ]);
        }
        foreach ($todayEvents as $event) {
            $todayAgenda->push([
                'type' => 'event',
                'title' => $event->name,
                'meta' => $event->destination?->name ?? 'بدون وجهة',
                'status' => 'فعالية اليوم',
                'sort_time' => optional($event->event_date)?->format('H:i') ?? '23:59',
            ]);
        }
        $todayAgenda = $todayAgenda
            ->sortBy('sort_time')
            ->values();

        $commissionFactor = $this->earnings->commissionPercent() / 100;
        $topActivities = $this->earnings->allBookingsQuery($user)
            ->select([
                'activity_id',
                DB::raw('COUNT(*) as bookings_count'),
                DB::raw('COALESCE(SUM(COALESCE(provider_earned_amount, total_price * ' . $commissionFactor . ')), 0) as provider_earnings'),
            ])
            ->whereNotNull('activity_id')
            ->groupBy('activity_id')
            ->orderByDesc('provider_earnings')
            ->limit(5)
            ->get();

        $topActivityIds = $topActivities->pluck('activity_id')->all();
        $activityNames = Activity::query()
            ->whereIn('id', $topActivityIds)
            ->get()
            ->keyBy('id');
        $topActivities = $topActivities->map(function ($row) use ($activityNames) {
            $activity = $activityNames->get($row->activity_id);
            return [
                'name' => $activity?->name ?? 'نشاط محذوف',
                'bookings_count' => (int) $row->bookings_count,
                'provider_earnings' => (float) $row->provider_earnings,
            ];
        })->values();

        $unreadNotificationsCount = $user->unreadNotifications()->count();

        return view('provider.dashboard', compact(
            'user',
            'totalEarned',
            'bookingsCount',
            'recentBookings',
            'commissionPercent',
            'activitiesCount',
            'dailySeries',
            'monthlySeries',
            'todayBookings',
            'todayAgenda',
            'weekEvents',
            'topActivities',
            'unreadNotificationsCount'
        ));
    }

    public function exportBookingsCsv(): StreamedResponse
    {
        $user = auth()->user();
        $filename = 'provider-bookings-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($user) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, [
                'المرجع',
                'النشاط',
                'العميل',
                'البريد',
                'تاريخ الحجز',
                'الإجمالي',
                'نصيب المزوّد',
                'حالة الدفع',
                'حالة الحجز',
            ]);

            $this->earnings->allBookingsQuery($user)
                ->with(['activity', 'user'])
                ->latest()
                ->chunk(200, function ($rows) use ($out) {
                    foreach ($rows as $b) {
                        fputcsv($out, [
                            $b->booking_reference,
                            $b->activity?->name ?? '',
                            $b->user?->name ?? '',
                            $b->user?->email ?? '',
                            $b->booking_date?->format('Y-m-d') ?? '',
                            $b->total_price,
                            $b->qualifiesForProviderEarnings() ? $b->provider_share : '',
                            $b->payment_status,
                            $b->status,
                        ]);
                    }
                });
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportEarningsCsv(): StreamedResponse
    {
        $user = auth()->user();
        $filename = 'provider-earnings-' . now()->format('Y-m-d') . '.csv';
        $series = $this->earnings->dailyEarningsSeries($user, 90);

        return response()->streamDownload(function () use ($series) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['التاريخ', 'المبلغ', 'عدد الحجوزات']);
            foreach ($series as $row) {
                fputcsv($out, [$row['date'], $row['amount'], $row['count']]);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function bookings(): View
    {
        $user = auth()->user();
        $bookings = $this->earnings->allBookingsQuery($user)
            ->with(['activity.destination', 'user'])
            ->latest()
            ->paginate(15);
        $commissionPercent = $this->earnings->commissionPercent();

        return view('provider.bookings', compact('user', 'bookings', 'commissionPercent'));
    }

    public function activities(): View
    {
        $user = auth()->user();
        $activities = $user->providedActivities()
            ->with('destination')
            ->latest()
            ->paginate(12);

        return view('provider.activities', compact('user', 'activities'));
    }

    public function createActivity(): View
    {
        $destinations = Destination::query()
            ->select(['id', 'name_ar', 'name_en'])
            ->orderByRaw(
                "COALESCE(NULLIF(name_" . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), name_ar, name_en) asc"
            )
            ->get();

        return view('provider.activities-create', compact('destinations'));
    }

    public function storeActivity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'destination_id' => ['required', 'exists:destinations,id'],
            'type' => ['required', 'string', 'max:255'],
            'type_en' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'highlights' => ['nullable', 'string'],
            'highlights_en' => ['nullable', 'string'],
            'whats_included' => ['nullable', 'string'],
            'whats_included_en' => ['nullable', 'string'],
            'whats_not_included' => ['nullable', 'string'],
            'whats_not_included_en' => ['nullable', 'string'],
            'additional_info' => ['nullable', 'string'],
            'additional_info_en' => ['nullable', 'string'],
            'payment_policy' => ['nullable', 'string'],
            'payment_policy_en' => ['nullable', 'string'],
            'cancellation_policy' => ['nullable', 'string'],
            'cancellation_policy_en' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'duration_label' => ['nullable', 'string', 'max:255'],
            'is_event' => ['nullable', 'boolean'],
            'event_date' => ['nullable', 'date'],
            'requires_booking' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'custom_sections_ar' => ['nullable', 'array'],
            'custom_sections_ar.*.title' => ['required_with:custom_sections_ar', 'string', 'max:255'],
            'custom_sections_ar.*.content' => ['required_with:custom_sections_ar', 'string'],
        ]);

        $isEvent = (bool) ($validated['is_event'] ?? false);
        if (!$isEvent) {
            $validated['event_date'] = null;
        }

        $customSections = null;
        if (isset($validated['custom_sections_ar']) && is_array($validated['custom_sections_ar'])) {
            $customSections = array_values(array_filter($validated['custom_sections_ar'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($customSections)) {
                $customSections = null;
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('activities', 'public');
        }

        $nameEn = trim((string) ($validated['name_en'] ?? ''));
        $typeEn = trim((string) ($validated['type_en'] ?? ''));
        $locationEn = trim((string) ($validated['location_en'] ?? ''));
        $descriptionEn = trim((string) ($validated['description_en'] ?? ''));
        $highlightsEn = trim((string) ($validated['highlights_en'] ?? ''));
        $whatsIncludedEn = trim((string) ($validated['whats_included_en'] ?? ''));
        $whatsNotIncludedEn = trim((string) ($validated['whats_not_included_en'] ?? ''));
        $additionalInfoEn = trim((string) ($validated['additional_info_en'] ?? ''));
        $paymentPolicyEn = trim((string) ($validated['payment_policy_en'] ?? ''));
        $cancellationPolicyEn = trim((string) ($validated['cancellation_policy_en'] ?? ''));

        Activity::create([
            'provider_id' => auth()->id(),
            'provider_review_status' => 'approved',
            'provider_reviewed_at' => now(),
            'name_ar' => $validated['name'],
            'name_en' => $nameEn !== '' ? $nameEn : $validated['name'],
            'destination_id' => (int) $validated['destination_id'],
            'type_ar' => $validated['type'],
            'type_en' => $typeEn !== '' ? $typeEn : $validated['type'],
            'price' => $validated['price'] ?? null,
            'rating' => $validated['rating'] ?? 0,
            'location_ar' => $validated['location'] ?? null,
            'location_en' => $locationEn !== '' ? $locationEn : ($validated['location'] ?? null),
            'description_ar' => $validated['description'] ?? null,
            'description_en' => $descriptionEn !== '' ? $descriptionEn : ($validated['description'] ?? null),
            'highlights_ar' => $validated['highlights'] ?? null,
            'highlights_en' => $highlightsEn !== '' ? $highlightsEn : ($validated['highlights'] ?? null),
            'whats_included_ar' => $validated['whats_included'] ?? null,
            'whats_included_en' => $whatsIncludedEn !== '' ? $whatsIncludedEn : ($validated['whats_included'] ?? null),
            'whats_not_included_ar' => $validated['whats_not_included'] ?? null,
            'whats_not_included_en' => $whatsNotIncludedEn !== '' ? $whatsNotIncludedEn : ($validated['whats_not_included'] ?? null),
            'additional_info_ar' => $validated['additional_info'] ?? null,
            'additional_info_en' => $additionalInfoEn !== '' ? $additionalInfoEn : ($validated['additional_info'] ?? null),
            'payment_policy_ar' => $validated['payment_policy'] ?? null,
            'payment_policy_en' => $paymentPolicyEn !== '' ? $paymentPolicyEn : ($validated['payment_policy'] ?? null),
            'cancellation_policy_ar' => $validated['cancellation_policy'] ?? null,
            'cancellation_policy_en' => $cancellationPolicyEn !== '' ? $cancellationPolicyEn : ($validated['cancellation_policy'] ?? null),
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'duration_label' => $validated['duration_label'] ?? null,
            // هذه الحقول حصرية للأدمن فقط
            'is_featured' => false,
            'is_must_visit' => false,
            'is_event' => $isEvent,
            'event_date' => $validated['event_date'] ?? null,
            'requires_booking' => (bool) ($validated['requires_booking'] ?? false),
            'image' => $imagePath,
            'custom_sections_ar' => $customSections,
            'custom_sections_en' => null,
        ]);

        return redirect()
            ->route('provider.activities.index')
            ->with('success', 'تم إنشاء النشاط ونشره.');
    }

    public function editActivity(Activity $activity): View
    {
        abort_unless($activity->provider_id === auth()->id(), 403);
        $activity->load(['gallery' => fn ($q) => $q->orderBy('order')]);
        $destinations = Destination::query()
            ->select(['id', 'name_ar', 'name_en'])
            ->orderByRaw(
                "COALESCE(NULLIF(name_" . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), name_ar, name_en) asc"
            )
            ->get();

        return view('provider.activities-edit', compact('activity', 'destinations'));
    }

    public function updateActivity(Request $request, Activity $activity): RedirectResponse
    {
        abort_unless($activity->provider_id === auth()->id(), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'destination_id' => ['required', 'exists:destinations,id'],
            'type' => ['required', 'string', 'max:255'],
            'type_en' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'location' => ['nullable', 'string', 'max:255'],
            'location_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'highlights' => ['nullable', 'string'],
            'highlights_en' => ['nullable', 'string'],
            'whats_included' => ['nullable', 'string'],
            'whats_included_en' => ['nullable', 'string'],
            'whats_not_included' => ['nullable', 'string'],
            'whats_not_included_en' => ['nullable', 'string'],
            'additional_info' => ['nullable', 'string'],
            'additional_info_en' => ['nullable', 'string'],
            'payment_policy' => ['nullable', 'string'],
            'payment_policy_en' => ['nullable', 'string'],
            'cancellation_policy' => ['nullable', 'string'],
            'cancellation_policy_en' => ['nullable', 'string'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'duration_label' => ['nullable', 'string', 'max:255'],
            'is_event' => ['nullable', 'boolean'],
            'event_date' => ['nullable', 'date'],
            'requires_booking' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'custom_sections_ar' => ['nullable', 'array'],
            'custom_sections_ar.*.title' => ['required_with:custom_sections_ar', 'string', 'max:255'],
            'custom_sections_ar.*.content' => ['required_with:custom_sections_ar', 'string'],
        ]);

        $isEvent = (bool) ($validated['is_event'] ?? false);
        if (!$isEvent) {
            $validated['event_date'] = null;
        }

        $customSections = null;
        if (isset($validated['custom_sections_ar']) && is_array($validated['custom_sections_ar'])) {
            $customSections = array_values(array_filter($validated['custom_sections_ar'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($customSections)) {
                $customSections = null;
            }
        }

        $imagePath = $activity->image;
        if ($request->hasFile('image')) {
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            $imagePath = $request->file('image')->store('activities', 'public');
        }

        $nameEn = trim((string) ($validated['name_en'] ?? ''));
        $typeEn = trim((string) ($validated['type_en'] ?? ''));
        $locationEn = trim((string) ($validated['location_en'] ?? ''));
        $descriptionEn = trim((string) ($validated['description_en'] ?? ''));
        $highlightsEn = trim((string) ($validated['highlights_en'] ?? ''));
        $whatsIncludedEn = trim((string) ($validated['whats_included_en'] ?? ''));
        $whatsNotIncludedEn = trim((string) ($validated['whats_not_included_en'] ?? ''));
        $additionalInfoEn = trim((string) ($validated['additional_info_en'] ?? ''));
        $paymentPolicyEn = trim((string) ($validated['payment_policy_en'] ?? ''));
        $cancellationPolicyEn = trim((string) ($validated['cancellation_policy_en'] ?? ''));

        $activity->update([
            'name_ar' => $validated['name'],
            'name_en' => $nameEn !== '' ? $nameEn : $validated['name'],
            'destination_id' => (int) $validated['destination_id'],
            'type_ar' => $validated['type'],
            'type_en' => $typeEn !== '' ? $typeEn : $validated['type'],
            'price' => $validated['price'] ?? null,
            'rating' => $validated['rating'] ?? 0,
            'location_ar' => $validated['location'] ?? null,
            'location_en' => $locationEn !== '' ? $locationEn : ($validated['location'] ?? null),
            'description_ar' => $validated['description'] ?? null,
            'description_en' => $descriptionEn !== '' ? $descriptionEn : ($validated['description'] ?? null),
            'highlights_ar' => $validated['highlights'] ?? null,
            'highlights_en' => $highlightsEn !== '' ? $highlightsEn : ($validated['highlights'] ?? null),
            'whats_included_ar' => $validated['whats_included'] ?? null,
            'whats_included_en' => $whatsIncludedEn !== '' ? $whatsIncludedEn : ($validated['whats_included'] ?? null),
            'whats_not_included_ar' => $validated['whats_not_included'] ?? null,
            'whats_not_included_en' => $whatsNotIncludedEn !== '' ? $whatsNotIncludedEn : ($validated['whats_not_included'] ?? null),
            'additional_info_ar' => $validated['additional_info'] ?? null,
            'additional_info_en' => $additionalInfoEn !== '' ? $additionalInfoEn : ($validated['additional_info'] ?? null),
            'payment_policy_ar' => $validated['payment_policy'] ?? null,
            'payment_policy_en' => $paymentPolicyEn !== '' ? $paymentPolicyEn : ($validated['payment_policy'] ?? null),
            'cancellation_policy_ar' => $validated['cancellation_policy'] ?? null,
            'cancellation_policy_en' => $cancellationPolicyEn !== '' ? $cancellationPolicyEn : ($validated['cancellation_policy'] ?? null),
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'duration_label' => $validated['duration_label'] ?? null,
            // لا يملك المزوّد صلاحية تعديلهما
            'is_featured' => $activity->is_featured,
            'is_must_visit' => $activity->is_must_visit,
            'is_event' => $isEvent,
            'event_date' => $validated['event_date'] ?? null,
            'requires_booking' => (bool) ($validated['requires_booking'] ?? false),
            'image' => $imagePath,
            'custom_sections_ar' => $customSections,
            'provider_review_status' => 'approved',
            'provider_reviewed_at' => now(),
        ]);

        return redirect()
            ->route('provider.activities.index')
            ->with('success', 'تم تحديث النشاط.');
    }

    public function destroyActivity(Activity $activity): RedirectResponse
    {
        abort_unless($activity->provider_id === auth()->id(), 403);
        $activity->delete();

        return back()->with('success', 'تم حذف النشاط.');
    }

    public function account(): View
    {
        $user = auth()->user();

        return view('provider.account', compact('user'));
    }

    public function updateAccount(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'activity_type' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return back()->with('success', 'تم تحديث بيانات الحساب بنجاح.');
    }

    public function notifications(): View
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(20);

        return view('provider.notifications', compact('user', 'notifications'));
    }

    public function markNotificationAsRead(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'تم تحديد الإشعار كمقروء.');
    }

    public function markAllNotificationsAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'تم تحديد كل إشعارات المزود كمقروءة.');
    }
}
