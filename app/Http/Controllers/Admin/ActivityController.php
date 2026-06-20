<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Models\Destination;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\MissingEnglishFieldsService;

class ActivityController extends Controller
{
    public function __construct(
        private ActivityService $service,
        private MissingEnglishFieldsService $missingEnglishFieldsService
    ) {}

    public function index()
    {
        $activities = $this->service->listPaginated();
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        $destinations = Destination::query()
            ->select('id', 'name_ar', 'name_en')
            ->orderByRaw("COALESCE(NULLIF(name_" . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), name_ar, name_en) asc")
            ->get();
        $providers = User::approvedContentProvidersQuery()->get(['id', 'name', 'email']);

        return view('admin.activities.create', compact('destinations', 'providers'));
    }

    public function store(ActivityRequest $request)
    {
        $payload = $request->validated();
        $this->service->create($payload);

        $missing = $this->missingEnglishFieldsService->detect($payload, [
            'name_en' => 'اسم النشاط (EN)',
            'type_en' => 'نوع النشاط (EN)',
            'location_en' => 'الموقع (EN)',
            'description_en' => 'الوصف (EN)',
        ]);

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'تمت إضافة النشاط بنجاح')
            ->with('warning', empty($missing) ? null : 'تم الحفظ مع نقص بيانات إنجليزية: ' . implode('، ', $missing));
    }

    public function show(Activity $activity)
    {
        $activity->load(['destination', 'provider', 'reviews.user', 'comments.user', 'bookings.user']);
        return view('admin.activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $destinations = Destination::query()
            ->select('id', 'name_ar', 'name_en')
            ->orderByRaw("COALESCE(NULLIF(name_" . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), name_ar, name_en) asc")
            ->get();
        $providers = User::approvedContentProvidersQuery()->get(['id', 'name', 'email']);

        return view('admin.activities.edit', compact('activity', 'destinations', 'providers'));
    }

    public function update(ActivityRequest $request, Activity $activity)
    {
        $payload = $request->validated();
        $this->service->update($activity, $payload);

        $missing = $this->missingEnglishFieldsService->detect($payload, [
            'name_en' => 'اسم النشاط (EN)',
            'type_en' => 'نوع النشاط (EN)',
            'location_en' => 'الموقع (EN)',
            'description_en' => 'الوصف (EN)',
        ]);

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'تم تحديث النشاط بنجاح')
            ->with('warning', empty($missing) ? null : 'تم الحفظ مع نقص بيانات إنجليزية: ' . implode('، ', $missing));
    }

    public function destroy(Activity $activity)
    {
        $this->service->delete($activity);

        return redirect()
            ->route('admin.activities.index')
            ->with('success', 'تم حذف النشاط بنجاح');
    }
}
