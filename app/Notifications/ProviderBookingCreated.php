<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProviderBookingCreated extends Notification
{
    use Queueable;

    public function __construct(private Booking $booking)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->booking_reference,
            'activity_name' => $this->booking->activity->name ?? 'نشاط',
            'activity_id' => $this->booking->activity_id,
            'user_name' => $this->booking->user->name ?? 'عميل',
            'booking_date' => optional($this->booking->booking_date)->format('Y-m-d'),
            'number_of_people' => $this->booking->number_of_people,
            'total_price' => $this->booking->total_price,
            'message' => 'لديك حجز جديد على نشاطك: ' . ($this->booking->activity->name ?? 'نشاط'),
            'type' => 'provider_booking_created',
        ];
    }
}
