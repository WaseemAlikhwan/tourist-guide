<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasLocalizedAttributes;

class Activity extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'destination_id',
        'provider_id',
        'provider_review_status',
        'provider_reviewed_at',
        'name_ar',
        'name_en',
        'type_ar',
        'type_en',
        'price',
        'rating',
        'location_ar',
        'location_en',
        'description_ar',
        'description_en',
        'image',
        'is_featured',
        'is_must_visit',
        'is_event',
        'event_date',
        'requires_booking',
        'duration_minutes',
        'duration_label',
        'highlights_ar',
        'highlights_en',
        'whats_included_ar',
        'whats_included_en',
        'whats_not_included_ar',
        'whats_not_included_en',
        'additional_info_ar',
        'additional_info_en',
        'payment_policy_ar',
        'payment_policy_en',
        'cancellation_policy_ar',
        'cancellation_policy_en',
        'custom_sections_ar',
        'custom_sections_en',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_must_visit' => 'boolean',
        'is_event' => 'boolean',
        'requires_booking' => 'boolean',
        'event_date' => 'date',
        'provider_reviewed_at' => 'datetime',
        'custom_sections_ar' => 'array',
        'custom_sections_en' => 'array',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->localizedValue('name');
    }

    public function getTypeAttribute(): ?string
    {
        return $this->localizedValue('type');
    }

    public function getLocationAttribute(): ?string
    {
        return $this->localizedValue('location');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->localizedValue('description');
    }

    public function getHighlightsAttribute(): ?string
    {
        return $this->localizedValue('highlights');
    }

    public function getWhatsIncludedAttribute(): ?string
    {
        return $this->localizedValue('whats_included');
    }

    public function getWhatsNotIncludedAttribute(): ?string
    {
        return $this->localizedValue('whats_not_included');
    }

    public function getAdditionalInfoAttribute(): ?string
    {
        return $this->localizedValue('additional_info');
    }

    public function getPaymentPolicyAttribute(): ?string
    {
        return $this->localizedValue('payment_policy');
    }

    public function getCancellationPolicyAttribute(): ?string
    {
        return $this->localizedValue('cancellation_policy');
    }

    public function getCustomSectionsAttribute(): ?array
    {
        return $this->localizedValue('custom_sections');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * الحصول على التقييمات المعتمدة فقط
     */
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * الحصول على التعليقات المعتمدة فقط
     */
    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true);
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        return null;
    }

    /**
     * الحصول على المفضلات
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * التحقق من كون النشاط مفضل لدى المستخدم
     */
    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }

    /**
     * الحصول على معرض الصور
     */
    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'galleryable')->orderBy('order');
    }

    /**
     * الحصول على الحجوزات
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function associations()
    {
        return $this->hasMany(ActivityAssociation::class, 'activity_id');
    }

    /**
     * Scope للحصول على الأنشطة المميزة
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('provider_review_status', 'approved');
    }

    /**
     * Scope للحصول على المعالم التي يجب زيارتها
     */
    public function scopeMustVisit($query)
    {
        return $query->where('is_must_visit', true)->where('provider_review_status', 'approved');
    }

    /**
     * Scope للحصول على الفعاليات هذا الشهر
     */
    public function scopeThisMonth($query)
    {
        $now = now();
        return $query->whereNotNull('event_date')
            ->where('provider_review_status', 'approved')
            ->whereMonth('event_date', $now->month)
            ->whereYear('event_date', $now->year);
    }

    /**
     * Scope للحصول على الفعاليات فقط
     */
    public function scopeEvents($query)
    {
        return $query->where('is_event', true)
            ->where('provider_review_status', 'approved')
            ->whereNotNull('event_date');
    }

    /**
     * الحصول على المدة بشكل منسق
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->duration_label) {
            return $this->duration_label;
        }

        if ($this->duration_minutes) {
            if ($this->duration_minutes >= 60) {
                $hours = floor($this->duration_minutes / 60);
                $minutes = $this->duration_minutes % 60;
                
                $result = $hours . ' ساعة';
                if ($minutes > 0) {
                    $result .= ' و ' . $minutes . ' دقيقة';
                }
                return $result;
            } else {
                return $this->duration_minutes . ' دقيقة';
            }
        }

        return null;
    }
}
