<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HotelRequest;
use App\Models\Hotel;
use App\Models\Destination;
use App\Services\HotelService;
use App\Services\MissingEnglishFieldsService;

class HotelController extends Controller
{
    public function __construct(
        private HotelService $service,
        private MissingEnglishFieldsService $missingEnglishFieldsService
    ) {}

    public function index()
    {
        $hotels = $this->service->listPaginated();
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $destinations = Destination::query()
            ->select('id', 'name_ar', 'name_en')
            ->orderByRaw("COALESCE(NULLIF(name_" . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), name_ar, name_en) asc")
            ->get();
        return view('admin.hotels.create', compact('destinations'));
    }

    public function store(HotelRequest $request)
    {
        $payload = $request->validated();
        $this->service->create($payload);

        $missing = $this->missingEnglishFieldsService->detect($payload, [
            'name_en' => 'اسم الفندق (EN)',
            'description_en' => 'الوصف (EN)',
            'address_en' => 'العنوان (EN)',
        ]);

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', 'تمت إضافة الفندق بنجاح')
            ->with('warning', empty($missing) ? null : 'تم الحفظ مع نقص بيانات إنجليزية: ' . implode('، ', $missing));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load('destination');
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        $destinations = Destination::query()
            ->select('id', 'name_ar', 'name_en')
            ->orderByRaw("COALESCE(NULLIF(name_" . (app()->getLocale() === 'en' ? 'en' : 'ar') . ", ''), name_ar, name_en) asc")
            ->get();
        return view('admin.hotels.edit', compact('hotel', 'destinations'));
    }

    public function update(HotelRequest $request, Hotel $hotel)
    {
        $payload = $request->validated();
        $this->service->update($hotel, $payload);

        $missing = $this->missingEnglishFieldsService->detect($payload, [
            'name_en' => 'اسم الفندق (EN)',
            'description_en' => 'الوصف (EN)',
            'address_en' => 'العنوان (EN)',
        ]);

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', 'تم تحديث الفندق بنجاح')
            ->with('warning', empty($missing) ? null : 'تم الحفظ مع نقص بيانات إنجليزية: ' . implode('، ', $missing));
    }

    public function destroy(Hotel $hotel)
    {
        $this->service->delete($hotel);

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', 'تم حذف الفندق بنجاح');
    }
}
