<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'auth_provider',
        'auth_provider_id',
        'avatar',
        'role',
        'is_content_provider',
        'content_provider_status',
        'activity_type',
        'can_login',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_content_provider' => 'boolean',
        'can_login' => 'boolean',
    ];

    /**
     * الحصول على المفضلات
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * الحصول على الحجوزات
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * الحصول على الشارات
     */
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'badge_user')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * الحصول على الكوبونات المستخدمة
     */
    public function usedCoupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user')
            ->withPivot('booking_id', 'used_at')
            ->withTimestamps();
    }

    public function contentProviderApplications()
    {
        return $this->hasMany(ContentProviderApplication::class);
    }

    public function providedActivities()
    {
        return $this->hasMany(Activity::class, 'provider_id');
    }

    public static function approvedContentProvidersQuery()
    {
        return static::query()
            ->where('is_content_provider', true)
            ->where('content_provider_status', 'approved')
            ->where('can_login', true)
            ->orderBy('name');
    }

    public function latestContentProviderApplication()
    {
        return $this->hasOne(ContentProviderApplication::class)->latestOfMany();
    }

    public function isApprovedContentProvider(): bool
    {
        return (bool) $this->is_content_provider
            && $this->content_provider_status === 'approved'
            && $this->can_login;
    }

    /**
     * التحقق من امتلاك شارة معينة
     */
    public function hasBadge($badgeSlug)
    {
        return $this->badges()->where('slug', $badgeSlug)->exists();
    }
}
