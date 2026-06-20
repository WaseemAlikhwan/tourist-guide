<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityAssociation;
use Illuminate\Http\Request;

class RecommendationInsightsController extends Controller
{
    public function index(Request $request)
    {
        $minConfidence = (float) $request->get('min_confidence', 0.1);
        $minSupport = (float) $request->get('min_support', 0.02);

        $associations = ActivityAssociation::query()
            ->with(['activity', 'associatedActivity'])
            ->where('confidence', '>=', $minConfidence)
            ->where('support', '>=', $minSupport)
            ->orderByDesc('co_occurrence_count')
            ->orderByDesc('lift')
            ->paginate(20)
            ->withQueryString();

        return view('admin.recommendations.index', compact('associations', 'minConfidence', 'minSupport'));
    }
}
