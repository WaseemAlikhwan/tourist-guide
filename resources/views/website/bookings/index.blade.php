@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'My Bookings' : 'حجوزاتي')

@push('head')
<style>
    .booking-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .booking-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(2, 132, 199, 0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .booking-card:hover::before {
        opacity: 1;
    }
    
    .booking-card.status-pending {
        border-left-color: #f59e0b;
    }
    
    .booking-card.status-confirmed {
        border-left-color: #10b981;
    }
    
    .booking-card.status-completed {
        border-left-color: #3b82f6;
    }
    
    .booking-card.status-cancelled {
        border-left-color: #ef4444;
    }
    
    .booking-reference {
        font-family: 'Courier New', monospace;
        background: var(--bg);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        display: inline-block;
        font-weight: 700;
        color: var(--primary-dark);
    }
    
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .empty-state i {
        font-size: 5rem;
        color: var(--text-light);
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="mb-2">
                        <i class="fas fa-calendar-check text-primary me-2"></i>
                        {{ $isEn ? 'My Bookings' : 'حجوزاتي' }}
                    </h1>
                    <p class="text-muted mb-0">{{ $isEn ? 'Manage and track all your bookings' : 'إدارة ومتابعة جميع حجوزاتك' }}</p>
                </div>
                <a href="{{ route('activities.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>
                    {{ $isEn ? 'New Booking' : 'حجز جديد' }}
                </a>
            </div>
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body empty-state">
                <i class="fas fa-calendar-times"></i>
                <h3 class="mb-3">{{ $isEn ? 'No bookings yet' : 'لا توجد حجوزات حالياً' }}</h3>
                <p class="text-muted mb-4">{{ $isEn ? 'Start exploring activities and book your first experience!' : 'ابدأ باستكشاف الأنشطة وقم بحجز تجربتك الأولى!' }}</p>
                <a href="{{ route('activities.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-search me-2"></i>
                    {{ $isEn ? 'Browse Activities' : 'تصفح الأنشطة' }}
                </a>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($bookings as $booking)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card booking-card status-{{ $booking->status }} h-100 border-0 shadow-sm">
                        <div class="card-body position-relative">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-2 fw-bold">
                                        {{ $booking->activity->name }}
                                    </h5>
                                    <p class="text-muted mb-0 small">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                        {{ $booking->activity->destination->name }}
                                    </p>
                                </div>
                                <span class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : ($booking->status == 'completed' ? 'info' : 'danger')) }} rounded-pill px-3 py-2">
                                    @if($booking->status == 'pending')
                                        <i class="fas fa-clock me-1"></i>{{ $isEn ? 'Pending' : 'قيد الانتظار' }}
                                    @elseif($booking->status == 'confirmed')
                                        <i class="fas fa-check me-1"></i>{{ $isEn ? 'Confirmed' : 'مؤكد' }}
                                    @elseif($booking->status == 'completed')
                                        <i class="fas fa-check-double me-1"></i>{{ $isEn ? 'Completed' : 'مكتمل' }}
                                    @else
                                        <i class="fas fa-times me-1"></i>{{ $isEn ? 'Cancelled' : 'ملغي' }}
                                    @endif
                                </span>
                            </div>

                            <div class="mb-3">
                                <span class="booking-reference">
                                    <i class="fas fa-hashtag me-1"></i>{{ $booking->booking_reference }}
                                </span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="border rounded p-2 bg-light">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-calendar me-1"></i>{{ $isEn ? 'Date' : 'التاريخ' }}
                                        </small>
                                        <strong class="d-block">{{ $booking->booking_date->format('Y-m-d') }}</strong>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded p-2 bg-light">
                                        <small class="text-muted d-block mb-1">
                                            <i class="fas fa-users me-1"></i>{{ $isEn ? 'People' : 'الأشخاص' }}
                                        </small>
                                        <strong class="d-block">{{ $booking->number_of_people }} {{ $isEn ? 'person' : 'شخص' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-success bg-opacity-10 rounded">
                                <span class="text-muted">{{ $isEn ? 'Total Price:' : 'السعر الإجمالي:' }}</span>
                                <strong class="text-success fs-5">
                                    {{ number_format($booking->total_price, 2) }} {{ $isEn ? 'SYP' : 'ل.س' }}
                                </strong>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-eye me-2"></i>
                                    {{ $isEn ? 'Details' : 'التفاصيل' }}
                                </a>
                                
                                @if($booking->status == 'pending')
                                    <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="flex-grow-1">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger w-100" 
                                            onclick="return confirm('{{ $isEn ? 'Are you sure you want to cancel this booking?' : 'هل أنت متأكد من إلغاء الحجز؟' }}')">
                                            <i class="fas fa-times me-2"></i>
                                            {{ $isEn ? 'Cancel' : 'إلغاء' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection



