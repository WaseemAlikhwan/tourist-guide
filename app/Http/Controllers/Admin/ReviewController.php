<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Activity;
use App\Models\Review;

class ReviewController extends Controller
{
    public function update(ReviewRequest $request, Review $review)
    {
        $data = $request->validated();
        $data['is_approved'] = true; // عند الحفظ من الأدمن، يتم اعتماده تلقائياً
        
        $review->update($data);
        $this->recalculateActivityRating($review->activity);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'تم حفظ واعتماد التقييم بنجاح']);
        }

        return back()->with('success', 'تم حفظ واعتماد التقييم بنجاح');
    }

    public function toggleApproval(Review $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $this->recalculateActivityRating($review->activity);

        $message = $review->is_approved ? 'تم اعتماد التقييم' : 'تم إلغاء اعتماد التقييم';

        return back()->with('success', $message);
    }

    public function destroy(Review $review)
    {
        $activity = $review->activity;
        $review->delete();
        $this->recalculateActivityRating($activity);

        return back()->with('success', 'تم حذف التقييم');
    }

    private function recalculateActivityRating(Activity $activity): void
    {
        // حساب التقييم بناءً على التقييمات المعتمدة فقط
        $average = $activity->approvedReviews()->avg('rating');
        $activity->rating = $average ? round($average, 1) : 0;
        $activity->save();
    }
}

