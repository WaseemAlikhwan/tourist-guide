<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * إضافة/إزالة من المفضلة
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'favoritable_type' => 'required|in:destination,activity',
            'favoritable_id' => 'required|integer',
        ]);

        $modelClass = $request->favoritable_type === 'destination' 
            ? \App\Models\Destination::class 
            : \App\Models\Activity::class;

        $favoritable = $modelClass::findOrFail($request->favoritable_id);
        
        $fullType = $request->favoritable_type === 'destination' 
            ? 'App\\Models\\Destination' 
            : 'App\\Models\\Activity';
        
        $favorite = Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', $fullType)
            ->where('favoritable_id', $request->favoritable_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'isFavorited' => false,
                'message' => 'تمت الإزالة من المفضلة'
            ]);
        } else {
            Favorite::create([
                'user_id' => auth()->id(),
                'favoritable_type' => $fullType,
                'favoritable_id' => $request->favoritable_id,
            ]);
            return response()->json([
                'success' => true,
                'isFavorited' => true,
                'message' => 'تمت الإضافة إلى المفضلة'
            ]);
        }
    }

    /**
     * عرض المفضلات
     */
    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with('favoritable')
            ->latest()
            ->paginate(12);

        return view('website.favorites.index', compact('favorites'));
    }
}
