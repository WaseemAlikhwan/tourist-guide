<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCreated extends Notification
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->booking_reference,
            'activity_name' => $this->booking->activity->name ?? 'نشاط محذوف',
            'activity_id' => $this->booking->activity_id,
            'user_name' => $this->booking->user->name ?? 'مستخدم',
            'booking_date' => $this->booking->booking_date->format('Y-m-d'),
            'number_of_people' => $this->booking->number_of_people,
            'total_price' => $this->booking->total_price,
            'message' => 'تم إنشاء حجز جديد على النشاط: ' . ($this->booking->activity->name ?? 'نشاط محذوف'),
            'type' => 'booking_created',
        ];
    }
}
