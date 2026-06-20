@extends('admin.layouts.app')

@section('title', 'تفاصيل الفندق | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.hotels.index') }}" class="text-decoration-none">الفنادق</a></span>
        <span>›</span>
        <span>تفاصيل الفندق</span>
    </div>

    <div class="page-header">
        <div>
            <h1>{{ $hotel->name }} 🏨</h1>
            <p class="subtitle">تفاصيل الفندق</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> تعديل
            </a>
            <a href="{{ route('admin.hotels.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">معلومات الفندق</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">اسم الفندق</label>
                        <p class="fs-5 mb-0">{{ $hotel->name }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">الوجهة المرتبطة</label>
                        <p class="fs-5 mb-0">
                            <a href="{{ route('admin.destinations.show', $hotel->destination) }}" class="badge bg-info-subtle text-info fs-6 text-decoration-none">
                                {{ $hotel->destination->name ?? '-' }}
                            </a>
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">التصنيف</label>
                        <p class="fs-5 mb-0">
                            <span class="badge bg-warning text-dark fs-6">
                                {!! str_repeat('⭐', $hotel->star_rating) !!} ({{ $hotel->star_rating }} نجوم)
                            </span>
                        </p>
                    </div>

                    @if($hotel->description)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">الوصف</label>
                            <p class="mb-0" style="line-height: 1.8;">{{ $hotel->description }}</p>
                        </div>
                    @endif

                    @if($hotel->address)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">العنوان</label>
                            <p class="mb-0">
                                <i class="bi bi-geo-alt"></i> {{ $hotel->address }}
                            </p>
                        </div>
                    @endif

                    <div class="row mb-4">
                        @if($hotel->phone)
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">رقم الهاتف</label>
                                <p class="mb-0">
                                    <i class="bi bi-telephone"></i> {{ $hotel->phone }}
                                </p>
                            </div>
                        @endif

                        @if($hotel->email)
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">البريد الإلكتروني</label>
                                <p class="mb-0">
                                    <i class="bi bi-envelope"></i> <a href="mailto:{{ $hotel->email }}">{{ $hotel->email }}</a>
                                </p>
                            </div>
                        @endif
                    </div>

                    @if($hotel->website)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">الموقع الإلكتروني</label>
                            <p class="mb-0">
                                <i class="bi bi-globe"></i> <a href="{{ $hotel->website }}" target="_blank">{{ $hotel->website }}</a>
                            </p>
                        </div>
                    @endif

                    @if($hotel->price_per_night)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">السعر لليلة الواحدة</label>
                            <p class="fs-5 mb-0">
                                <span class="badge bg-success-subtle text-success fs-6">{{ number_format($hotel->price_per_night, 0) }} ل.س</span>
                            </p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">الحالة</label>
                        <p class="fs-5 mb-0">
                            @if($hotel->is_active)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-secondary">غير نشط</span>
                            @endif
                        </p>
                    </div>

                    @if($hotel->latitude && $hotel->longitude)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">الإحداثيات الجغرافية</label>
                            <p class="mb-0">
                                <i class="bi bi-geo-alt-fill"></i> 
                                خط العرض: {{ $hotel->latitude }}, خط الطول: {{ $hotel->longitude }}
                                @php
                                    $distance = $hotel->destination ? $hotel->distanceFromDestination($hotel->destination) : null;
                                @endphp
                                @if($distance !== null)
                                    <span class="badge bg-primary-subtle text-primary ms-2">على بعد {{ $distance }} كم من الوجهة</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    @if($hotel->amenities && count($hotel->amenities) > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">المرافق والخدمات</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($hotel->amenities as $amenity)
                                    <span class="badge bg-light text-dark">{{ $amenity }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">تاريخ الإضافة</label>
                        <p class="mb-0">{{ $hotel->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold text-muted small">آخر تحديث</label>
                        <p class="mb-0">{{ $hotel->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title mb-4">صورة الفندق</h5>
                    @if($hotel->image)
                        <img src="{{ asset('storage/' . $hotel->image) }}" 
                             alt="{{ $hotel->name }}" 
                             class="img-fluid rounded"
                             style="width: 100%; border-radius: 12px;">
                    @else
                        <div class="text-center py-5" style="background: #f0f0f0; border-radius: 12px;">
                            <i class="bi bi-building" style="font-size: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-3">لا توجد صورة</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card card-custom mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">إجراءات سريعة</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> تعديل الفندق
                        </a>
                        <form action="{{ route('admin.hotels.destroy', $hotel) }}" 
                              method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الفندق؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> حذف الفندق
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
