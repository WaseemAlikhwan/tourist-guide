<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Models\Activity;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(ReviewRequest $request, Activity $activity)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['activity_id'] = $activity->id;
        $data['is_approved'] = false; // يحتاج موافقة من الأدمن

        // السماح بتحديث تقييم المستخدم لنفس النشاط بدلاً من التكرار (تقييم واحد فقط)
        Review::updateOrCreate(
            ['user_id' => $data['user_id'], 'activity_id' => $activity->id],
            ['rating' => $data['rating'], 'is_approved' => false]
        );

        // إعادة حساب التقييم بناءً على التقييمات المعتمدة فقط
        $this->recalculateActivityRating($activity);

        return back()->with('success', 'تم إضافة/تحديث التقييم. سيظهر بعد اعتماده من الإدارة.');
    }

    public function update(ReviewRequest $request, Review $review)
    {
        $this->authorizeOwner($review);

        $review->update($request->validated());
        $this->recalculateActivityRating($review->activity);

        return back()->with('success', 'تم تحديث التقييم بنجاح');
    }

    public function destroy(Review $review)
    {
        $this->authorizeOwner($review);

        $activity = $review->activity;
        $review->delete();
        $this->recalculateActivityRating($activity);

        return back()->with('success', 'تم حذف التقييم');
    }

    private function authorizeOwner(Review $review): void
    {
        abort_unless(Auth::id() === $review->user_id, 403);
    }

    private function recalculateActivityRating(Activity $activity): void
    {
        // حساب التقييم بناءً على التقييمات المعتمدة فقط
        $average = $activity->approvedReviews()->avg('rating');
        $activity->rating = $average ? round($average, 1) : 0;
        $activity->save();
    }
}

