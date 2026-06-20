<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ProviderEarningsService
{
    public function allBookingsQuery(User $provider): Builder
    {
        return Booking::query()
            ->whereHas('activity', fn (Builder $q) => $q->where('provider_id', $provider->id));
    }

    public function qualifyingBookingsQuery(User $provider): Builder
    {
        return $this->allBookingsQuery($provider)
            ->where('payment_status', 'paid')
            ->whereIn('status', ['confirmed', 'completed']);
    }

    public function totalEarned(User $provider): float
    {
        return (float) $this->qualifyingBookingsQuery($provider)
            ->get()
            ->sum(fn (Booking $b) => $b->provider_share);
    }

    public function countQualifying(User $provider): int
    {
        return $this->qualifyingBookingsQuery($provider)->count();
    }

    public function recentBookings(User $provider, int $limit = 8)
    {
        return $this->qualifyingBookingsQuery($provider)
            ->with(['activity.destination', 'user'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function commissionPercent(): float
    {
        return (float) config('provider.commission_percent', 70);
    }

    public function dailyEarningsSeries(User $provider, int $days = 7): array
    {
        $fromDate = Carbon::today()->subDays($days - 1);
        $raw = $this->qualifyingBookingsQuery($provider)
            ->whereDate('booking_date', '>=', $fromDate)
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->booking_date?->format('Y-m-d'));

        $series = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $fromDate->copy()->addDays($i);
            $key = $date->format('Y-m-d');
            $bookings = $raw->get($key, collect());
            $amount = $bookings->sum(fn (Booking $booking) => $booking->provider_share);

            $series[] = [
                'date' => $key,
                'label' => $date->translatedFormat('D'),
                'amount' => (float) $amount,
                'count' => $bookings->count(),
            ];
        }

        return $series;
    }

    public function monthlyEarningsSeries(User $provider, int $months = 6): array
    {
        $start = Carbon::now()->startOfMonth()->subMonths($months - 1);
        $raw = $this->qualifyingBookingsQuery($provider)
            ->whereDate('booking_date', '>=', $start)
            ->get()
            ->groupBy(fn (Booking $booking) => $booking->booking_date?->format('Y-m'));

        $series = [];
        for ($i = 0; $i < $months; $i++) {
            $month = $start->copy()->addMonths($i);
            $key = $month->format('Y-m');
            $bookings = $raw->get($key, collect());
            $amount = $bookings->sum(fn (Booking $booking) => $booking->provider_share);

            $series[] = [
                'month' => $key,
                'label' => $month->translatedFormat('M Y'),
                'amount' => (float) $amount,
                'count' => $bookings->count(),
            ];
        }

        return $series;
    }
}
