<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $activities = Activity::where('requires_booking', true)->get();

        if ($users->isEmpty() || $activities->isEmpty()) {
            $this->command->warn('لا توجد مستخدمين أو أنشطة تتطلب حجز! يرجى تشغيل UserSeeder و ActivitySeeder أولاً.');
            return;
        }

        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $activities = $activities->values();
        $activityCount = $activities->count();

        // إنشاء حجوزات لكل مستخدم
        foreach ($users->values() as $userIndex => $user) {
            $numBookings = min(3, $activityCount);

            for ($i = 0; $i < $numBookings; $i++) {
                $activity = $activities[($userIndex + $i) % $activityCount];
                $status = $statuses[($userIndex + $i) % count($statuses)];

                $bookingDate = $activity->is_event && $activity->event_date
                    ? $activity->event_date 
                    : now()->startOfDay()->addDays((($userIndex + $i) % 30) + 1);

                $numberOfPeople = (($userIndex + $i) % 5) + 1;
                $totalPrice = $activity->price ? ($activity->price * $numberOfPeople) : 0;
                $reference = sprintf('BK-U%03d-A%03d-%02d', $user->id, $activity->id, $i + 1);

                Booking::updateOrCreate(
                    ['booking_reference' => $reference],
                    [
                        'user_id' => $user->id,
                        'activity_id' => $activity->id,
                        'booking_date' => $bookingDate,
                        'number_of_people' => $numberOfPeople,
                        'total_price' => $totalPrice,
                        'status' => $status,
                        'payment_status' => in_array($status, ['confirmed', 'completed'], true) ? 'paid' : 'pending',
                        'payment_method' => in_array($status, ['confirmed', 'completed'], true) ? 'card' : null,
                        'special_requests' => ($i % 2 === 0)
                            ? 'Special request: please contact me before confirmation.'
                            : null,
                        'booking_reference' => $reference,
                    ]
                );
            }
        }
    }
}
