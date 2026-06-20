<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingConfirmed;
use App\Notifications\BookingCancelled;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * عرض قائمة الحجوزات
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'activity.destination']);

        // فلترة حسب الحالة
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // فلترة حسب حالة الدفع
        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * عرض تفاصيل الحجز
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'activity.destination', 'couponUsages.coupon']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * تحديث حالة الحجز
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $oldStatus = $booking->status;
        $booking->update($validated);

        // إرسال إشعار للمستخدم عند تأكيد الحجز
        if ($validated['status'] === 'confirmed' && $oldStatus !== 'confirmed' && $booking->user) {
            $booking->user->notify(new BookingConfirmed($booking));
        }

        return back()->with('success', 'تم تحديث حالة الحجز بنجاح');
    }

    /**
     * تأكيد الدفع (تم دفع الفلوس) - الحجز يصبح مكتمل
     */
    public function confirmPayment(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|max:255',
            'payment_note' => 'nullable|string|max:500',
        ]);

        $booking->load('activity');

        $percent = (float) config('provider.commission_percent', 70);
        $total = (float) $booking->total_price;
        $providerEarned = null;
        $platformFee = null;

        if ($booking->activity && $booking->activity->provider_id) {
            $providerEarned = round($total * ($percent / 100), 2);
            $platformFee = round($total - $providerEarned, 2);
        }

        $booking->update([
            'payment_status' => 'paid',
            'status' => 'completed',
            'payment_method' => $validated['payment_method'],
            'payment_note' => $validated['payment_note'] ?? null,
            'provider_earned_amount' => $providerEarned,
            'platform_fee_amount' => $platformFee,
        ]);

        // إرسال إشعار للمستخدم بتأكيد الحجز
        if ($booking->user) {
            $booking->refresh(); // تحديث البيانات من قاعدة البيانات
            $booking->user->notify(new BookingConfirmed($booking));
        }

        return back()->with('success', 'تم تأكيد الدفع والحجز أصبح مكتمل');
    }

    /**
     * إلغاء الحجز (لم يدفع) - حذف الحجز من الجدول
     */
    public function cancelUnpaid(Booking $booking)
    {
        // تحميل العلاقات قبل الحذف
        $booking->load(['user', 'activity']);
        
        // حفظ البيانات قبل الحذف لإرسال الإشعار
        $user = $booking->user;
        $activity = $booking->activity;
        $bookingReference = $booking->booking_reference;
        $bookingId = $booking->id;
        $bookingDate = $booking->booking_date;
        $activityId = $booking->activity_id;
        $activityName = $activity ? $activity->name : 'نشاط محذوف';

        // حذف الحجز من الجدول
        $booking->delete();

        // إرسال إشعار للمستخدم بإلغاء الحجز
        if ($user) {
            // إنشاء نموذج مؤقت للإشعار مع البيانات المحفوظة
            $cancelledBooking = new Booking();
            $cancelledBooking->id = $bookingId;
            $cancelledBooking->booking_reference = $bookingReference;
            $cancelledBooking->booking_date = $bookingDate;
            $cancelledBooking->activity_id = $activityId;
            $cancelledBooking->exists = false;
            
            // ربط النشاط بالنموذج المؤقت
            if ($activity) {
                $cancelledBooking->setRelation('activity', $activity);
            }
            
            $user->notify(new BookingCancelled($cancelledBooking));
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم إلغاء الحجز وحذفه من الجدول بسبب التأخير في الدفع');
    }

    /**
     * حذف الحجز
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم حذف الحجز بنجاح');
    }
}




