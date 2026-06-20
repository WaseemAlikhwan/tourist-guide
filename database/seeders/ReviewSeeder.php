<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $activities = Activity::all();

        if ($users->isEmpty() || $activities->isEmpty()) {
            $this->command->warn('لا توجد مستخدمين أو أنشطة! يرجى تشغيل UserSeeder و ActivitySeeder أولاً.');
            return;
        }

        $ratings = [5, 4, 5, 4, 3, 5];
        $users = $users->values();
        $userCount = $users->count();

        foreach ($activities as $activityIndex => $activity) {
            $numReviews = min(3, $userCount);

            for ($i = 0; $i < $numReviews; $i++) {
                $user = $users[($activityIndex + $i) % $userCount];
                $rating = $ratings[($activityIndex + $i) % count($ratings)];
                $isApproved = (($activityIndex + $i) % 5) !== 0;

                Review::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'activity_id' => $activity->id,
                    ],
                    [
                        'rating' => $rating,
                        'is_approved' => $isApproved,
                    ]
                );
            }
        }

        // تحديث تقييمات الأنشطة بناءً على التقييمات المعتمدة فقط
        foreach ($activities as $activity) {
            $activity->refresh(); // تحديث البيانات من قاعدة البيانات
            $avgRating = $activity->reviews()->where('is_approved', true)->avg('rating');
            if ($avgRating) {
                $activity->update(['rating' => round($avgRating, 1)]);
            }
        }

        $this->command->info('تم إنشاء ' . Review::count() . ' تقييم بنجاح.');
    }
}
