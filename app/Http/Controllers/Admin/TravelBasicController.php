<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TravelBasic;
use App\Services\MissingEnglishFieldsService;
use Illuminate\Http\Request;

class TravelBasicController extends Controller
{
    public function __construct(private MissingEnglishFieldsService $missingEnglishFieldsService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $travelBasics = TravelBasic::ordered()->paginate(20);
        return view('admin.travel-basics.index', compact('travelBasics'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.travel-basics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'content_ar' => 'nullable|string',
            'content_en' => 'nullable|string',
            'items_ar' => 'nullable|array',
            'items_ar.*' => 'string|max:500',
            'items_en' => 'nullable|array',
            'items_en.*' => 'string|max:500',
            'custom_sections_ar' => 'nullable|array',
            'custom_sections_ar.*.title' => 'required_with:custom_sections_ar|string|max:255',
            'custom_sections_ar.*.content' => 'required_with:custom_sections_ar|string',
            'custom_sections_en' => 'nullable|array',
            'custom_sections_en.*.title' => 'required_with:custom_sections_en|string|max:255',
            'custom_sections_en.*.content' => 'required_with:custom_sections_en|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // معالجة نوع المحتوى
        $contentType = $request->input('content_type', 'list');
        if ($contentType === 'list') {
            $validated['content_ar'] = null;
            $validated['content_en'] = null;
            if ($request->has('items_ar') && is_array($request->items_ar)) {
                $validated['items_ar'] = array_values(array_filter($request->items_ar, fn ($item) => !empty(trim((string) $item))));
            }
            if ($request->has('items_en') && is_array($request->items_en)) {
                $validated['items_en'] = array_values(array_filter($request->items_en, fn ($item) => !empty(trim((string) $item))));
            }
        } else {
            $validated['items_ar'] = null;
            $validated['items_en'] = null;
        }

        if (isset($validated['custom_sections_ar']) && is_array($validated['custom_sections_ar'])) {
            $validated['custom_sections_ar'] = array_values(array_filter($validated['custom_sections_ar'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($validated['custom_sections_ar'])) {
                $validated['custom_sections_ar'] = null;
            }
        } else {
            $validated['custom_sections_ar'] = null;
        }

        if (isset($validated['custom_sections_en']) && is_array($validated['custom_sections_en'])) {
            $validated['custom_sections_en'] = array_values(array_filter($validated['custom_sections_en'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($validated['custom_sections_en'])) {
                $validated['custom_sections_en'] = null;
            }
        } else {
            $validated['custom_sections_en'] = null;
        }

        $validated['is_active'] = $request->has('is_active') && $request->is_active == '1';
        TravelBasic::create($validated);

        $missing = $this->missingEnglishFieldsService->detect($validated, [
            'title_en' => 'العنوان (EN)',
            'content_en' => 'المحتوى (EN)',
        ]);

        return redirect()
            ->route('admin.travel-basics.index')
            ->with('success', 'تمت إضافة أساسيات السفر بنجاح')
            ->with('warning', empty($missing) ? null : 'تم الحفظ مع نقص بيانات إنجليزية: ' . implode('، ', $missing));
    }

    /**
     * Display the specified resource.
     */
    public function show(TravelBasic $travelBasic)
    {
        return view('admin.travel-basics.show', compact('travelBasic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TravelBasic $travelBasic)
    {
        return view('admin.travel-basics.edit', compact('travelBasic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TravelBasic $travelBasic)
    {
        $validated = $request->validate([
            'title_ar' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'content_ar' => 'nullable|string',
            'content_en' => 'nullable|string',
            'items_ar' => 'nullable|array',
            'items_ar.*' => 'string|max:500',
            'items_en' => 'nullable|array',
            'items_en.*' => 'string|max:500',
            'custom_sections_ar' => 'nullable|array',
            'custom_sections_ar.*.title' => 'required_with:custom_sections_ar|string|max:255',
            'custom_sections_ar.*.content' => 'required_with:custom_sections_ar|string',
            'custom_sections_en' => 'nullable|array',
            'custom_sections_en.*.title' => 'required_with:custom_sections_en|string|max:255',
            'custom_sections_en.*.content' => 'required_with:custom_sections_en|string',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // معالجة نوع المحتوى
        $contentType = $request->input('content_type', 'list');
        if ($contentType === 'list') {
            $validated['content_ar'] = null;
            $validated['content_en'] = null;
            if ($request->has('items_ar') && is_array($request->items_ar)) {
                $validated['items_ar'] = array_values(array_filter($request->items_ar, fn ($item) => !empty(trim((string) $item))));
            }
            if ($request->has('items_en') && is_array($request->items_en)) {
                $validated['items_en'] = array_values(array_filter($request->items_en, fn ($item) => !empty(trim((string) $item))));
            }
        } else {
            $validated['items_ar'] = null;
            $validated['items_en'] = null;
        }

        if (isset($validated['custom_sections_ar']) && is_array($validated['custom_sections_ar'])) {
            $validated['custom_sections_ar'] = array_values(array_filter($validated['custom_sections_ar'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($validated['custom_sections_ar'])) {
                $validated['custom_sections_ar'] = null;
            }
        } else {
            $validated['custom_sections_ar'] = null;
        }

        if (isset($validated['custom_sections_en']) && is_array($validated['custom_sections_en'])) {
            $validated['custom_sections_en'] = array_values(array_filter($validated['custom_sections_en'], function ($section) {
                return !empty($section['title']) && !empty($section['content']);
            }));
            if (empty($validated['custom_sections_en'])) {
                $validated['custom_sections_en'] = null;
            }
        } else {
            $validated['custom_sections_en'] = null;
        }

        $validated['is_active'] = $request->has('is_active') && $request->is_active == '1';
        $travelBasic->update($validated);

        $missing = $this->missingEnglishFieldsService->detect($validated, [
            'title_en' => 'العنوان (EN)',
            'content_en' => 'المحتوى (EN)',
        ]);

        return redirect()
            ->route('admin.travel-basics.index')
            ->with('success', 'تم تحديث أساسيات السفر بنجاح')
            ->with('warning', empty($missing) ? null : 'تم الحفظ مع نقص بيانات إنجليزية: ' . implode('، ', $missing));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TravelBasic $travelBasic)
    {
        $travelBasic->delete();

        return redirect()
            ->route('admin.travel-basics.index')
            ->with('success', 'تم حذف أساسيات السفر بنجاح');
    }
}
