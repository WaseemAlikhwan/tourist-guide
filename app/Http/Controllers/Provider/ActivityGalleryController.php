<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityGalleryController extends Controller
{
    public function store(Request $request, Activity $activity): RedirectResponse|JsonResponse
    {
        abort_unless($activity->provider_id === auth()->id(), 403);

        $validated = $request->validate([
            'images' => ['required', 'array', 'max:12'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $maxOrder = (int) $activity->gallery()->max('order');
        $next = $maxOrder + 1;

        foreach ($validated['images'] as $file) {
            $path = $file->store('galleries', 'public');
            $activity->gallery()->create([
                'image_path' => $path,
                'caption' => null,
                'order' => $next++,
                'is_featured' => false,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => 'تم رفع الصور.']);
        }

        return back()->with('success', 'تمت إضافة الصور إلى المعرض.');
    }

    public function destroy(Activity $activity, Gallery $gallery): RedirectResponse|JsonResponse
    {
        abort_unless($activity->provider_id === auth()->id(), 403);
        abort_unless(
            $gallery->galleryable_type === Activity::class && (int) $gallery->galleryable_id === (int) $activity->id,
            404
        );

        if ($gallery->image_path) {
            Storage::disk('public')->delete($gallery->image_path);
        }
        $gallery->delete();

        if (request()->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return back()->with('success', 'تم حذف الصورة من المعرض.');
    }

    public function reorder(Request $request, Activity $activity): JsonResponse
    {
        abort_unless($activity->provider_id === auth()->id(), 403);

        $validated = $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer', 'exists:galleries,id'],
        ]);

        $ids = array_map('intval', $validated['order']);
        $existing = $activity->gallery()->pluck('id')->map(fn ($id) => (int) $id)->sort()->values()->all();
        $sentSorted = collect($ids)->sort()->values()->all();

        if ($existing !== $sentSorted) {
            return response()->json(['ok' => false, 'message' => 'ترتيب غير صالح.'], 422);
        }

        foreach ($ids as $index => $id) {
            Gallery::query()
                ->where('id', $id)
                ->where('galleryable_type', Activity::class)
                ->where('galleryable_id', $activity->id)
                ->update(['order' => $index]);
        }

        return response()->json(['ok' => true]);
    }
}
