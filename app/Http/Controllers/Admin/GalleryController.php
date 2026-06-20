<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Destination;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Traits\UploadImageTrait;

class GalleryController extends Controller
{
    use UploadImageTrait;

    /**
     * إضافة صورة للمعرض
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'galleryable_type' => 'required|in:App\Models\Destination,App\Models\Activity',
            'galleryable_id' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'caption' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ]);

        // رفع الصورة
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadImage($request->file('image'), 'gallery');
            $validated['image_path'] = $imagePath;
        }

        unset($validated['image']);

        // الحصول على آخر ترتيب
        $lastOrder = Gallery::where('galleryable_type', $validated['galleryable_type'])
            ->where('galleryable_id', $validated['galleryable_id'])
            ->max('order');

        $validated['order'] = $lastOrder ? $lastOrder + 1 : 0;

        Gallery::create($validated);

        return back()->with('success', 'تم إضافة الصورة بنجاح');
    }

    /**
     * تحديث صورة المعرض
     */
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'caption' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        $gallery->update($validated);

        return back()->with('success', 'تم تحديث الصورة بنجاح');
    }

    /**
     * حذف صورة من المعرض
     */
    public function destroy(Gallery $gallery)
    {
        // حذف الصورة من التخزين
        if ($gallery->image_path && file_exists(storage_path('app/public/' . $gallery->image_path))) {
            unlink(storage_path('app/public/' . $gallery->image_path));
        }

        $gallery->delete();

        return back()->with('success', 'تم حذف الصورة بنجاح');
    }
}




