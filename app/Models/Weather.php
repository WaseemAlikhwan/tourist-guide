<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Weather extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'temperature',
        'feels_like',
        'humidity',
        'wind_speed',
        'wind_degree',
        'pressure',
        'visibility',
        'clouds',
        'condition',
        'description',
        'icon',
        'date',
        'time',
        'forecast',
        'alerts',
        'last_updated',
    ];

    protected $casts = [
        'temperature' => 'decimal:2',
        'feels_like' => 'decimal:2',
        'wind_speed' => 'decimal:2',
        'forecast' => 'array',
        'alerts' => 'array',
        'date' => 'date',
        'time' => 'datetime',
        'last_updated' => 'datetime',
    ];

    /**
     * العلاقة مع الوجهة
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    /**
     * الحصول على اتجاه الرياح بالعربية
     */
    public function getWindDirectionAttribute()
    {
        if (!$this->wind_degree) {
            return null;
        }

        $directions = app()->getLocale() === 'en'
            ? ['North', 'North East', 'East', 'South East', 'South', 'South West', 'West', 'North West']
            : ['شمال', 'شمال شرق', 'شرق', 'جنوب شرق', 'جنوب', 'جنوب غرب', 'غرب', 'شمال غرب'];
        
        $index = round($this->wind_degree / 45) % 8;
        return $directions[$index];
    }

    /**
     * الحصول على حالة الطقس بالعربية
     */
    public function getConditionArabicAttribute()
    {
        $conditions = [
            'clear' => 'صافي',
            'clouds' => 'غائم',
            'rain' => 'ممطر',
            'drizzle' => 'رذاذ',
            'thunderstorm' => 'عاصفة رعدية',
            'snow' => 'ثلج',
            'mist' => 'ضباب',
            'fog' => 'ضباب كثيف',
            'haze' => 'ضباب خفيف',
        ];

        $condition = strtolower($this->condition ?? '');
        return $conditions[$condition] ?? $this->condition ?? 'غير معروف';
    }

    /**
     * التحقق من وجود تحذيرات
     */
    public function hasAlerts()
    {
        return !empty($this->alerts) && count($this->alerts) > 0;
    }

    /**
     * الحصول على الطقس الحالي لوجهة
     */
    public static function getCurrentForDestination($destinationId)
    {
        return static::where('destination_id', $destinationId)
            ->where('date', Carbon::today())
            ->latest('last_updated')
            ->first();
    }

    /**
     * Scope للطقس الحالي
     */
    public function scopeCurrent($query)
    {
        return $query->where('date', Carbon::today())
            ->latest('last_updated');
    }

    /**
     * Scope للتنبؤات
     */
    public function scopeForecast($query, $days = 7)
    {
        return $query->where('date', '>=', Carbon::today())
            ->where('date', '<=', Carbon::today()->addDays($days))
            ->orderBy('date');
    }
}
