<?php

namespace App\Services;

use App\Models\ActivityAssociation;
use App\Models\Booking;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ActivityAssociationService
{
    public function rebuild(?string $fromDate = null): int
    {
        $bookingQuery = Booking::query()
            ->select(['user_id', 'booking_date', 'activity_id'])
            ->whereIn('status', ['confirmed', 'completed'])
            ->where('payment_status', 'paid')
            ->orderBy('user_id');

        if ($fromDate) {
            $bookingQuery->whereDate('booking_date', '>=', $fromDate);
        }

        $bookings = $bookingQuery->get()->groupBy('user_id');
        $totalBaskets = $bookings->count();

        if ($totalBaskets === 0) {
            ActivityAssociation::query()->delete();
            return 0;
        }

        $activityOccurrences = [];
        $pairOccurrences = [];

        foreach ($bookings as $userBookings) {
            $activityIds = $userBookings->pluck('activity_id')->unique()->values()->all();
            $count = count($activityIds);

            foreach ($activityIds as $activityId) {
                $activityOccurrences[$activityId] = ($activityOccurrences[$activityId] ?? 0) + 1;
            }

            for ($i = 0; $i < $count; $i++) {
                for ($j = 0; $j < $count; $j++) {
                    if ($i === $j) {
                        continue;
                    }
                    $a = $activityIds[$i];
                    $b = $activityIds[$j];
                    $key = $a . ':' . $b;
                    $pairOccurrences[$key] = ($pairOccurrences[$key] ?? 0) + 1;
                }
            }
        }

        $rows = [];
        $now = now();
        foreach ($pairOccurrences as $key => $coOccurrenceCount) {
            [$activityId, $associatedId] = array_map('intval', explode(':', $key));
            $activityCount = $activityOccurrences[$activityId] ?? 1;
            $associatedCount = $activityOccurrences[$associatedId] ?? 1;

            $support = $coOccurrenceCount / $totalBaskets;
            $confidence = $coOccurrenceCount / $activityCount;
            $lift = $confidence / ($associatedCount / $totalBaskets);

            $rows[] = [
                'activity_id' => $activityId,
                'associated_activity_id' => $associatedId,
                'co_occurrence_count' => $coOccurrenceCount,
                'activity_occurrence_count' => $activityCount,
                'associated_occurrence_count' => $associatedCount,
                'total_baskets' => $totalBaskets,
                'support' => round($support, 6),
                'confidence' => round($confidence, 6),
                'lift' => round($lift, 6),
                'calculated_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::transaction(function () use ($rows) {
            ActivityAssociation::query()->delete();

            if (!empty($rows)) {
                ActivityAssociation::query()->insert($rows);
            }
        });

        return count($rows);
    }
}
