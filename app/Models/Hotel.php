<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasLocalizedAttributes;

class Hotel extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'destination_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'address_ar',
        'address_en',
        'phone',
        'email',
        'website',
        'star_rating',
        'price_per_night',
        'latitude',
        'longitude',
        'image',
        'amenities_ar',
        'amenities_en',
        'is_active',
    ];

    protected $casts = [
        'amenities_ar' => 'array',
        'amenities_en' => 'array',
        'is_active' => 'boolean',
        'star_rating' => 'integer',
        'price_per_night' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->localizedValue('name');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->localizedValue('description');
    }

    public function getAddressAttribute(): ?string
    {
        return $this->localizedValue('address');
    }

    public function getAmenitiesAttribute(): ?array
    {
        return $this->localizedValue('amenities');
    }

    /**
     * العلاقة مع الوجهة
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * الحصول على رابط الصورة
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    /**
     * الحصول على النجوم كأيقونات
     */
    public function getStarsDisplayAttribute()
    {
        return str_repeat('⭐', $this->star_rating);
    }

    /**
     * Scope للفنادق النشطة
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * حساب المسافة من الوجهة (بالكيلومتر)
     * 
     * @param Destination|null $destination الوجهة المراد حساب المسافة منها (اختياري)
     * @return float|null المسافة بالكيلومتر
     */
    public function distanceFromDestination($destination = null)
    {
        $destination = $destination ?? $this->destination;
        
        if (!$destination || !$destination->latitude || !$destination->longitude) {
            return null;
        }

        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $lat1 = $destination->latitude;
        $lon1 = $destination->longitude;
        $lat2 = $this->latitude;
        $lon2 = $this->longitude;

        $earthRadius = 6371; // نصف قطر الأرض بالكيلومتر

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }
}
