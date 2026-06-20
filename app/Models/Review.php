<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'activity_id', 'rating', 'is_approved'];

    protected $casts = [
        'is_approved' => 'boolean',
    ];
    
    // التأكد من أن timestamps مفعلة (افتراضي في Laravel)
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * Scope للحصول على التقييمات المعتمدة فقط
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }
}
