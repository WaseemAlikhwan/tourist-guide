<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasLocalizedAttributes;

class Destination extends Model
{
    use HasFactory;
    use HasLocalizedAttributes;

    protected $fillable = [
        'name_ar',
        'name_en',
        'country_ar',
        'country_en',
        'description_ar',
        'description_en',
        'image',
        'latitude',
        'longitude',
        'custom_sections_ar',
        'custom_sections_en',
    ];

    protected $casts = [
        'custom_sections_ar' => 'array',
        'custom_sections_en' => 'array',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->localizedValue('name');
    }

    public function getCountryAttribute(): ?string
    {
        return $this->localizedValue('country');
    }

    public function getDescriptionAttribute(): ?string
    {
        return $this->localizedValue('description');
    }

    public function getCustomSectionsAttribute(): ?array
    {
        return $this->localizedValue('custom_sections');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
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
     * الحصول على المفضلات
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    /**
     * التحقق من كون الوجهة مفضلة لدى المستخدم
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
     * الحصول على معلومات الطقس
     */
    public function weather()
    {
        return $this->hasOne(Weather::class)->latest('last_updated');
    }

    /**
     * الحصول على جميع سجلات الطقس
     */
    public function weatherHistory()
    {
        return $this->hasMany(Weather::class);
    }

    /**
     * الحصول على الفنادق القريبة
     */
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    /**
     * الحصول على الفنادق النشطة فقط
     */
    public function activeHotels()
    {
        return $this->hasMany(Hotel::class)->where('is_active', true);
    }
}
