<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityAssociation;
use Illuminate\Http\JsonResponse;

class RecommendationController extends Controller
{
    public function byActivity(Activity $activity): JsonResponse
    {
        $recommendations = ActivityAssociation::query()
            ->with('associatedActivity.destination')
            ->where('activity_id', $activity->id)
            ->where('confidence', '>=', 0.1)
            ->orderByDesc('confidence')
            ->orderByDesc('lift')
            ->take(6)
            ->get()
            ->map(function (ActivityAssociation $association) {
                $associated = $association->associatedActivity;
                if (!$associated || $associated->provider_review_status !== 'approved') {
                    return null;
                }

                return [
                    'id' => $associated->id,
                    'name' => $associated->name,
                    'type' => $associated->type,
                    'price' => $associated->price,
                    'rating' => $associated->rating,
                    'destination' => optional($associated->destination)->name,
                    'confidence' => (float) $association->confidence,
                    'lift' => (float) $association->lift,
                    'co_occurrence_count' => $association->co_occurrence_count,
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'data' => $recommendations,
        ]);
    }
}
