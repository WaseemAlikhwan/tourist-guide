<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BadgeController extends Controller
{
    /**
     * عرض قائمة الشارات
     */
    public function index()
    {
        $badges = Badge::withCount('users')->latest()->paginate(20);

        return view('admin.badges.index', compact('badges'));
    }

    /**
     * عرض نموذج إنشاء شارة جديدة
     */
    public function create()
    {
        return view('admin.badges.create');
    }

    /**
     * حفظ الشارة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|max:20',
            'points_required' => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Badge::create($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'تم إنشاء الشارة بنجاح');
    }

    /**
     * عرض نموذج تعديل الشارة
     */
    public function edit(Badge $badge)
    {
        return view('admin.badges.edit', compact('badge'));
    }

    /**
     * تحديث الشارة
     */
    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'color' => 'required|string|max:20',
            'points_required' => 'required|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $badge->update($validated);

        return redirect()->route('admin.badges.index')
            ->with('success', 'تم تحديث الشارة بنجاح');
    }

    /**
     * حذف الشارة
     */
    public function destroy(Badge $badge)
    {
        $badge->delete();

        return redirect()->route('admin.badges.index')
            ->with('success', 'تم حذف الشارة بنجاح');
    }
}




