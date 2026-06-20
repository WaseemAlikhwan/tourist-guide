@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'Booking Details' : 'تفاصيل الحجز')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('booking_created') || session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 16px; border: none; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; margin-bottom: 2rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 2.5rem;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div style="flex: 1;">
                            <h5 class="mb-1" style="color: white; font-weight: 700;">
                                <i class="fas fa-calendar-check"></i> {{ $isEn ? 'Booking created successfully!' : 'تم إنشاء الحجز بنجاح!' }}
                            </h5>
                            <p class="mb-0" style="color: rgba(255, 255, 255, 0.95); font-size: 1.05rem;">
                                {{ session('success') ?: ($isEn ? 'We will contact you for payment and completion.' : 'سنراسلك للدفع وإكمال العملية.') }}
                            </p>
                            <div class="mt-2" style="background: rgba(255, 255, 255, 0.2); padding: 0.75rem 1rem; border-radius: 8px; margin-top: 0.75rem;">
                                <strong style="color: white;">{{ $isEn ? 'Booking Ref:' : 'رقم الحجز:' }}</strong>
                                <span style="font-size: 1.2rem; font-weight: 700; letter-spacing: 1px;">{{ $booking->booking_reference }}</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close" style="filter: brightness(0) invert(1);"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle"></i> {{ $isEn ? 'Booking Details' : 'تفاصيل الحجز' }}
                    </h4>
                </div>
                <div class="card-body">
                    <!-- رقم الحجز والحالة -->
                    <div class="text-center mb-4">
                        <h2 class="text-primary">{{ $booking->booking_reference }}</h2>
                        <span class="badge badge-lg badge-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'completed' ? 'info' : 'danger')) }}">
                            @if($booking->status == 'pending')
                                {{ $isEn ? 'Pending' : 'قيد الانتظار' }}
                            @elseif($booking->status == 'confirmed')
                                {{ $isEn ? 'Confirmed' : 'مؤكد' }} ✓
                            @elseif($booking->status == 'completed')
                                {{ $isEn ? 'Completed' : 'مكتمل' }} ✓
                            @else
                                {{ $isEn ? 'Cancelled' : 'ملغي' }} ✗
                            @endif
                        </span>
                    </div>

                    <hr>

                    <!-- معلومات النشاط -->
                    <h5 class="mb-3">
                        <i class="fas fa-info-circle"></i> {{ $isEn ? 'Activity Information' : 'معلومات النشاط' }}
                    </h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <img src="{{ $booking->activity->image_url }}" 
                                 alt="{{ $booking->activity->name }}" 
                                 class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $booking->activity->name }}</h4>
                            <p class="text-muted">
                                <i class="fas fa-map-marker-alt"></i> 
                                {{ $booking->activity->destination->name }}, {{ $booking->activity->destination->country }}
                            </p>
                            <p class="mb-0">
                                <i class="fas fa-location-arrow"></i> 
                                {{ $booking->activity->location }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- تفاصيل الحجز -->
                    <h5 class="mb-3">
                        <i class="fas fa-calendar-alt"></i> {{ $isEn ? 'Booking Details' : 'تفاصيل الحجز' }}
                    </h5>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-box p-3 bg-light rounded mb-3" style="background: @if($booking->activity->is_event && $booking->activity->event_date) linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%) @else linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) @endif !important; border: 2px solid @if($booking->activity->is_event && $booking->activity->event_date) #0ea5e9 @else #e2e8f0 @endif;">
                                <small class="d-block mb-2" style="color: @if($booking->activity->is_event && $booking->activity->event_date) #075985 @else #64748b @endif;">
                                    <i class="fas fa-calendar-alt"></i> 
                                    @if($booking->activity->is_event && $booking->activity->event_date)
                                        {{ $isEn ? 'Fixed Event Date' : 'تاريخ الفعالية المثبت' }}
                                    @else
                                        {{ $isEn ? 'Booking Date' : 'تاريخ الحجز' }}
                                    @endif
                                </small>
                                <p class="mb-0 font-weight-bold" style="font-size: 1.2rem; color: @if($booking->activity->is_event && $booking->activity->event_date) #0369a1 @else #0f172a @endif;">
                                    <i class="far fa-calendar-check"></i> {{ $booking->booking_date->format('d M Y') }}
                                    <small class="text-muted d-block mt-1" style="font-size: 0.9rem;">
                                        ({{ $booking->booking_date->diffForHumans() }})
                                    </small>
                                </p>
                                @if($booking->activity->is_event && $booking->activity->event_date)
                                    <span class="badge badge-info mt-2" style="background: #0ea5e9; padding: 0.4rem 0.75rem; border-radius: 6px;">
                                        <i class="fas fa-info-circle"></i> {{ $isEn ? 'Fixed event date' : 'تاريخ ثابت للفعالية' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box p-3 bg-light rounded mb-3">
                                <small class="text-muted">{{ $isEn ? 'Number of people' : 'عدد الأشخاص' }}</small>
                                <p class="mb-0 font-weight-bold">
                                    <i class="fas fa-users"></i> {{ $booking->number_of_people }} {{ $isEn ? 'person' : 'شخص' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($booking->special_requests)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-comment"></i> {{ $isEn ? 'Special requests:' : 'طلبات خاصة:' }}</strong>
                            <p class="mb-0 mt-2">{{ $booking->special_requests }}</p>
                        </div>
                    @endif

                    <hr>

                    <!-- ملخص السعر -->
                    <h5 class="mb-3">
                        <i class="fas fa-money-bill-wave"></i> {{ $isEn ? 'Price Summary' : 'ملخص السعر' }}
                    </h5>
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $isEn ? 'Price per person:' : 'السعر للشخص:' }}</span>
                                <span>{{ number_format($booking->activity->price, 2) }} {{ $isEn ? 'SYP' : 'ل.س' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $isEn ? 'Number of people:' : 'عدد الأشخاص:' }}</span>
                                <span>{{ $booking->number_of_people }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>{{ $isEn ? 'Total paid:' : 'الإجمالي المدفوع:' }}</strong>
                                <strong class="text-success h4 mb-0">
                                    {{ number_format($booking->total_price, 2) }} {{ $isEn ? 'SYP' : 'ل.س' }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- معلومات إضافية -->
                    <div class="row text-center">
                        <div class="col-md-6">
                            <small class="text-muted">{{ $isEn ? 'Created at' : 'تاريخ الإنشاء' }}</small>
                            <p>{{ $booking->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">{{ $isEn ? 'Last updated' : 'آخر تحديث' }}</small>
                            <p>{{ $booking->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    <!-- الأزرار -->
                    <div class="text-center mt-4">
                        <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-right"></i> {{ $isEn ? 'Back to Bookings' : 'العودة للحجوزات' }}
                        </a>
                        
                        @if($booking->status == 'pending')
                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('{{ $isEn ? 'Are you sure you want to cancel this booking?' : 'هل أنت متأكد من إلغاء الحجز؟' }}')">
                                    <i class="fas fa-times"></i> {{ $isEn ? 'Cancel Booking' : 'إلغاء الحجز' }}
                                </button>
                            </form>
                        @endif
                        
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="fas fa-print"></i> {{ $isEn ? 'Print' : 'طباعة' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




