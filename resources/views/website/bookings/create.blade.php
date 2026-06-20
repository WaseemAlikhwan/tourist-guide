@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', ($isEn ? 'Book ' : 'حجز ') . $activity->name)

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-check"></i> {{ $isEn ? 'Activity Booking' : 'حجز نشاط' }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- معلومات النشاط -->
                    <div class="activity-info mb-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="{{ $activity->image_url }}" alt="{{ $activity->name }}" 
                                     class="img-fluid rounded">
                            </div>
                            <div class="col-md-9">
                                <h5>{{ $activity->name }}</h5>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    {{ $activity->destination->name }}
                                </p>
                                <p class="text-success font-weight-bold mb-0">
                                    {{ $isEn ? 'Price:' : 'السعر:' }} {{ number_format($activity->price, 2) }} {{ $isEn ? 'SYP per person' : 'ل.س للشخص' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- نموذج الحجز -->
                    <form action="{{ route('bookings.store', $activity) }}" method="POST" id="bookingForm">
                        @csrf

                        <div class="form-group">
                            <label for="booking_date">
                                <i class="fas fa-calendar"></i> 
                                @if($activity->is_event && $activity->event_date)
                                    {{ $isEn ? 'Event Date' : 'تاريخ الفعالية' }}
                                @else
                                    {{ $isEn ? 'Booking Date' : 'تاريخ الحجز' }}
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            @if($activity->is_event && $activity->event_date)
                                <div class="alert alert-info mb-0" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border: 2px solid #0ea5e9; border-radius: 12px; padding: 1.25rem; box-shadow: 0 4px 12px rgba(14, 165, 233, 0.15);">
                                    <div class="d-flex align-items-start gap-3">
                                        <div style="background: white; border-radius: 12px; padding: 0.75rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                            <i class="fas fa-calendar-check" style="font-size: 2rem; color: #0ea5e9;"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <strong style="color: #0c4a6e; display: block; margin-bottom: 0.5rem; font-size: 1rem;">{{ $isEn ? 'Fixed Event Date:' : 'تاريخ الفعالية المثبت:' }}</strong>
                                            <div style="background: white; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 0.75rem;">
                                                <span style="color: #0369a1; font-size: 1.3rem; font-weight: 700; display: block;">
                                                    {{ $activity->event_date->format('d M Y') }}
                                                </span>
                                                <small class="text-muted" style="font-size: 0.9rem;">
                                                    <i class="far fa-calendar"></i> {{ $activity->event_date->format('l') }}
                                                </small>
                                            </div>
                                            <small class="d-block" style="color: #075985; font-size: 0.9rem;">
                                                <i class="fas fa-info-circle"></i> {{ $isEn ? 'This date is fixed for the event and cannot be changed' : 'هذا التاريخ مثبت للفعالية ولا يمكن تغييره' }}
                                            </small>
                                        </div>
                                    </div>
                                    <input type="hidden" name="booking_date" value="{{ $activity->event_date->format('Y-m-d') }}">
                                </div>
                            @else
                                <input type="date" 
                                       class="form-control @error('booking_date') is-invalid @enderror" 
                                       id="booking_date" 
                                       name="booking_date" 
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       value="{{ old('booking_date') }}" 
                                       required>
                            @endif
                            @error('booking_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="number_of_people">
                                <i class="fas fa-users"></i> {{ $isEn ? 'Number of People' : 'عدد الأشخاص' }}
                                <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('number_of_people') is-invalid @enderror" 
                                   id="number_of_people" 
                                   name="number_of_people" 
                                   min="1" 
                                   max="50"
                                   value="{{ old('number_of_people', 1) }}" 
                                   required>
                            @error('number_of_people')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="coupon_code">
                                <i class="fas fa-ticket-alt"></i> {{ $isEn ? 'Coupon Code (Optional)' : 'كود الخصم (اختياري)' }}
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control @error('coupon_code') is-invalid @enderror" 
                                       id="coupon_code" 
                                       name="coupon_code"
                                       value="{{ old('coupon_code') }}"
                                       placeholder="{{ $isEn ? 'Enter coupon code' : 'أدخل كود الخصم' }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="validateCoupon">
                                        {{ $isEn ? 'Validate' : 'تحقق' }}
                                    </button>
                                </div>
                            </div>
                            <div id="couponMessage"></div>
                            @error('coupon_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="special_requests">
                                <i class="fas fa-comment"></i> {{ $isEn ? 'Special Requests (Optional)' : 'طلبات خاصة (اختياري)' }}
                            </label>
                            <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                      id="special_requests" 
                                      name="special_requests" 
                                      rows="3"
                                      placeholder="{{ $isEn ? 'Any special requests or notes...' : 'أي طلبات أو ملاحظات خاصة...' }}">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ملخص السعر -->
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $isEn ? 'Booking Summary' : 'ملخص الحجز' }}</h5>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $isEn ? 'Price per person:' : 'السعر للشخص:' }}</span>
                                    <span id="pricePerPerson">{{ number_format($activity->price, 2) }} {{ $isEn ? 'SYP' : 'ل.س' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $isEn ? 'Number of people:' : 'عدد الأشخاص:' }}</span>
                                    <span id="totalPeople">1</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2" id="discountRow" style="display: none !important;">
                                    <span class="text-success">{{ $isEn ? 'Discount:' : 'الخصم:' }}</span>
                                    <span class="text-success" id="discountAmount">0 {{ $isEn ? 'SYP' : 'ل.س' }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $isEn ? 'Total:' : 'الإجمالي:' }}</strong>
                                    <strong class="text-success" id="totalPrice">{{ number_format($activity->price, 2) }} {{ $isEn ? 'SYP' : 'ل.س' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">
                                <i class="fas fa-check"></i> {{ $isEn ? 'Confirm Booking' : 'تأكيد الحجز' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const activityPrice = {{ $activity->price }};
let discount = 0;
const isEnglish = @json($isEn);
const currencyUnit = isEnglish ? 'SYP' : 'ل.س';

// تحديث الإجمالي
function updateTotal() {
    const people = parseInt(document.getElementById('number_of_people').value) || 1;
    const subtotal = activityPrice * people;
    const total = subtotal - discount;
    
    document.getElementById('totalPeople').textContent = people;
    document.getElementById('totalPrice').textContent = total.toFixed(2) + ' ' + currencyUnit;
}

document.getElementById('number_of_people').addEventListener('input', updateTotal);

// التحقق من الكوبون
document.getElementById('validateCoupon').addEventListener('click', async function() {
    const couponCode = document.getElementById('coupon_code').value;
    const people = parseInt(document.getElementById('number_of_people').value) || 1;
    const totalPrice = activityPrice * people;
    
    if (!couponCode) {
        alert(isEnglish ? 'Please enter a coupon code' : 'الرجاء إدخال كود الخصم');
        return;
    }
    
    try {
        const response = await fetch('{{ route("bookings.validate-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                coupon_code: couponCode,
                total_price: totalPrice
            })
        });
        
        const data = await response.json();
        const messageDiv = document.getElementById('couponMessage');
        
        if (data.valid) {
            discount = data.discount;
            messageDiv.innerHTML = '<div class="alert alert-success mt-2">' + data.message + '</div>';
            document.getElementById('discountAmount').textContent = discount.toFixed(2) + ' ' + currencyUnit;
            document.getElementById('discountRow').style.display = 'flex';
            updateTotal();
        } else {
            discount = 0;
            messageDiv.innerHTML = '<div class="alert alert-danger mt-2">' + data.message + '</div>';
            document.getElementById('discountRow').style.display = 'none';
            updateTotal();
        }
    } catch (error) {
        console.error('Error:', error);
        alert(isEnglish ? 'An error occurred while validating the coupon' : 'حدث خطأ أثناء التحقق من الكوبون');
    }
});
</script>
@endpush
@endsection




