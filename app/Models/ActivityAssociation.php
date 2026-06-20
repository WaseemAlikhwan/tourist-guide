<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityAssociation extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'associated_activity_id',
        'co_occurrence_count',
        'activity_occurrence_count',
        'associated_occurrence_count',
        'total_baskets',
        'support',
        'confidence',
        'lift',
        'calculated_at',
    ];

    protected $casts = [
        'support' => 'decimal:6',
        'confidence' => 'decimal:6',
        'lift' => 'decimal:6',
        'calculated_at' => 'datetime',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function associatedActivity()
    {
        return $this->belongsTo(Activity::class, 'associated_activity_id');
    }
}
