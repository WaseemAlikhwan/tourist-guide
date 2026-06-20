<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DestinationRequest;
use App\Models\Destination;
use App\Services\DestinationService;
use App\Services\MissingEnglishFieldsService;

class DestinationController extends Controller
{
    public function __construct(
        private DestinationService $service,
        private MissingEnglishFieldsService $missingEnglishFieldsService
    ) {}

    public function index()
    {
        $destinations = $this->service->listPaginated();
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(DestinationRequest $request)
    {
        $payload = $request->validated();
        $this->service->create($payload);

        $missing = $this->missingEnglishFieldsService->detect($payload, [
            'name_en' => 'اسم الوجهة (EN)',
            'country_en' => 'الدولة (EN)',
            'description_en' => 'الوصف (EN)',
        ]);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'تمت الإضافة بنجاح')
            ->with('warning', empty($missing) ? null : 'تم الحفظ مع نقص بيانات إنجليزية: ' . implode('، ', $missing));
    }

    public function show(Destination $destination)
    {
        $destination->load('activities');
        return view('admin.destinations.show', compact('destination'));
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(DestinationRequest $request, Destination $destination)
    {
        $payload = $request->validated();
        $this->service->update($destination, $payload);

        $missing = $this->missingEnglishFieldsService->detect($payload, [
            'name_en' => 'اسم الوجهة (EN)',
            'country_en' => 'الدولة (EN)',
            'description_en' => 'الوصف (EN)',
        ]);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'تم التحديث بنجاح')
            ->with('warning', empty($missing) ? null : 'تم الحفظ مع نقص بيانات إنجليزية: ' . implode('، ', $missing));
    }

    public function destroy(Destination $destination)
    {
        $this->service->delete($destination);
        return redirect()->route('admin.destinations.index')->with('success', 'تم الحذف بنجاح');
    }
}
