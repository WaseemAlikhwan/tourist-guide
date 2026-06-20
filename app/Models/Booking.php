<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'booking_date',
        'number_of_people',
        'total_price',
        'provider_earned_amount',
        'platform_fee_amount',
        'status',
        'payment_status',
        'payment_method',
        'payment_note',
        'special_requests',
        'booking_reference',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'provider_earned_amount' => 'decimal:2',
        'platform_fee_amount' => 'decimal:2',
    ];

    public function getProviderShareAttribute(): float
    {
        if ($this->provider_earned_amount !== null) {
            return (float) $this->provider_earned_amount;
        }

        $percent = (float) config('provider.commission_percent', 70);

        return round((float) $this->total_price * ($percent / 100), 2);
    }

    public function getPlatformShareAttribute(): float
    {
        if ($this->platform_fee_amount !== null) {
            return (float) $this->platform_fee_amount;
        }

        return round((float) $this->total_price - $this->provider_share, 2);
    }

    public function qualifiesForProviderEarnings(): bool
    {
        return $this->payment_status === 'paid'
            && in_array($this->status, ['confirmed', 'completed'], true);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            if (empty($booking->booking_reference)) {
                $booking->booking_reference = 'BK-' . strtoupper(Str::random(10));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUser::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }
}




