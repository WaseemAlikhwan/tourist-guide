<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Activity;
use App\Models\Coupon;
use App\Models\User;
use App\Notifications\BookingCreated;
use App\Notifications\ProviderBookingCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض قائمة الحجوزات
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with('activity.destination')
            ->latest()
            ->paginate(10);

        return view('website.bookings.index', compact('bookings'));
    }

    /**
     * عرض نموذج الحجز
     */
    public function create(Activity $activity)
    {
        if ($activity->provider_review_status !== 'approved') {
            abort(404);
        }

        return view('website.bookings.create', compact('activity'));
    }

    /**
     * حفظ الحجز
     */
    public function store(Request $request, Activity $activity)
    {
        if ($activity->provider_review_status !== 'approved') {
            abort(404);
        }

        // إذا كان النشاط فعالية بتاريخ مثبت، استخدم تاريخ الفعالية تلقائياً
        if ($activity->is_event && $activity->event_date) {
            $bookingDate = $activity->event_date->format('Y-m-d');
        } else {
            $bookingDate = $request->input('booking_date');
        }

        $validated = $request->validate([
            'number_of_people' => 'required|integer|min:1|max:50',
            'special_requests' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string|exists:coupons,code',
        ]);

        // التحقق من تاريخ الحجز فقط إذا لم يكن فعالية بتاريخ مثبت
        if (!$activity->is_event || !$activity->event_date) {
            $request->validate([
                'booking_date' => 'required|date|after:today',
            ]);
            $bookingDate = $request->input('booking_date');
        }

        $validated['booking_date'] = $bookingDate;

        $totalPrice = $activity->price * $validated['number_of_people'];
        $discount = 0;

        // تطبيق الكوبون إن وجد
        if (!empty($validated['coupon_code'])) {
            $coupon = Coupon::where('code', $validated['coupon_code'])->first();
            
            if ($coupon && $coupon->isValid()) {
                $discount = $coupon->calculateDiscount($totalPrice);
                $totalPrice -= $discount;
            }
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'activity_id' => $activity->id,
            'booking_date' => $validated['booking_date'],
            'number_of_people' => $validated['number_of_people'],
            'total_price' => $totalPrice,
            'discount' => $discount,
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => 'pending',
            'payment_method' => 'offline',
        ]);

        // حفظ استخدام الكوبون
        if (!empty($validated['coupon_code']) && $discount > 0) {
            $coupon->users()->attach(Auth::id(), [
                'booking_id' => $booking->id,
                'used_at' => now(),
            ]);
            $coupon->increment('usage_count');
        }

        // إرسال إشعار لجميع الأدمن عند الحجز
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new BookingCreated($booking));
        }

        $booking->load(['activity.provider', 'user']);
        $provider = $booking->activity?->provider;
        if ($provider && $provider->isApprovedContentProvider()) {
            $provider->notify(new ProviderBookingCreated($booking));
        }

        return redirect()->route('bookings.show', $booking)
            ->with('booking_created', true)
            ->with('success', 'تم إنشاء الحجز بنجاح! سنراسلك للدفع وإكمال العملية.');
    }

    /**
     * عرض تفاصيل الحجز
     */
    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load('activity.destination');

        return view('website.bookings.show', compact('booking'));
    }

    /**
     * إلغاء الحجز
     */
    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'لا يمكن إلغاء هذا الحجز');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.index')
            ->with('success', 'تم إلغاء الحجز بنجاح');
    }

    /**
     * التحقق من صحة الكوبون
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
            'total_price' => 'required|numeric|min:0',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'الكوبون غير موجود',
            ]);
        }

        if (!$coupon->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'الكوبون غير صالح أو منتهي الصلاحية',
            ]);
        }

        $discount = $coupon->calculateDiscount($request->total_price);

        if ($discount == 0) {
            return response()->json([
                'valid' => false,
                'message' => 'الحد الأدنى للشراء غير متوفر',
            ]);
        }

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'new_total' => $request->total_price - $discount,
            'message' => 'تم تطبيق الكوبون بنجاح',
        ]);
    }
}




