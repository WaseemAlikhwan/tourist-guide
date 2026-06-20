<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmed extends Notification
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
        $paymentMethod = $this->booking->payment_method ?? 'غير محدد';
        $paymentNote = $this->booking->payment_note ? ' - ' . $this->booking->payment_note : '';
        $activityName = $this->booking->activity->name ?? 'نشاط محذوف';
        
        $message = 'تم تأكيد الدفع وحجزك على النشاط "' . $activityName . '" أصبح مكتملاً';
        if ($this->booking->payment_method) {
            $message .= ' | طريقة الدفع: ' . $paymentMethod;
            if ($this->booking->payment_note) {
                $message .= ' (' . $this->booking->payment_note . ')';
            }
        }
        
        return [
            'booking_id' => $this->booking->id,
            'booking_reference' => $this->booking->booking_reference,
            'activity_name' => $activityName,
            'activity_id' => $this->booking->activity_id,
            'booking_date' => $this->booking->booking_date->format('Y-m-d'),
            'total_price' => $this->booking->total_price,
            'payment_method' => $this->booking->payment_method,
            'payment_note' => $this->booking->payment_note,
            'message' => $message,
            'type' => 'booking_confirmed',
        ];
    }
}




