@extends('admin.layouts.app')

@section('title', 'تفاصيل الكوبون | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">لوحة التحكم</a></span>
        <span>›</span>
        <span><a href="{{ route('admin.coupons.index') }}" class="text-decoration-none">الكوبونات</a></span>
        <span>›</span>
        <span>تفاصيل الكوبون</span>
    </div>

    <div class="page-header">
        <div>
            <h1>تفاصيل الكوبون 🎫</h1>
            <p class="subtitle">معلومات كاملة عن الكوبون واستخداماته</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> تعديل
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    @php
        $isExpired = $coupon->valid_until < now();
        $isActive = $coupon->is_active && !$isExpired;
        $usagePercent = $coupon->usage_limit ? ($coupon->usage_count / $coupon->usage_limit) * 100 : 0;
        $isRunningOut = $coupon->usage_limit && $coupon->usage_count >= ($coupon->usage_limit * 0.8);
    @endphp

    <!-- بطاقة الكوبون الرئيسية -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <code class="bg-primary-subtle text-primary p-3 rounded-3" style="font-size: 24px; font-weight: 800; font-family: 'Courier New', monospace;">
                                    {{ $coupon->code }}
                                </code>
                                @if($isActive)
                                    <span class="badge bg-success-subtle text-success fs-6">
                                        <i class="bi bi-check-circle"></i> نشط
                                    </span>
                                @elseif($isExpired)
                                    <span class="badge bg-danger-subtle text-danger fs-6">
                                        <i class="bi bi-x-circle"></i> منتهي
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary fs-6">
                                        <i class="bi bi-pause-circle"></i> معطل
                                    </span>
                                @endif
                            </div>
                            @if($coupon->description)
                                <p class="text-muted mb-0">{{ $coupon->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3">
                                <div class="text-muted small mb-2">نوع الخصم</div>
                                <div class="fw-bold fs-4">
                                    @if($coupon->discount_type == 'percentage')
                                        <span class="text-info">
                                            <i class="bi bi-percent"></i> {{ number_format($coupon->discount_value, 0) }}%
                                        </span>
                                    @else
                                        <span class="text-success">
                                            <i class="bi bi-currency-dollar"></i> {{ number_format($coupon->discount_value, 0) }} ل.س
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded-3">
                                <div class="text-muted small mb-2">الحد الأدنى للشراء</div>
                                <div class="fw-bold fs-4">
                                    @if($coupon->minimum_purchase)
                                        {{ number_format($coupon->minimum_purchase, 0) }} ل.س
                                    @else
                                        <span class="text-muted">لا يوجد</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-bar-chart"></i> إحصائيات الاستخدام
                    </h5>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">الاستخدام</span>
                            <span class="fw-bold">
                                {{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}
                            </span>
                        </div>
                        @if($coupon->usage_limit)
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ $isRunningOut ? 'bg-warning' : 'bg-primary' }}" 
                                     role="progressbar" 
                                     style="width: {{ min($usagePercent, 100) }}%"></div>
                            </div>
                            <small class="text-muted">
                                استخدام {{ number_format($usagePercent, 1) }}% من الحد المسموح
                            </small>
                        @else
                            <div class="text-muted small">استخدام غير محدود</div>
                        @endif
                    </div>

                    <div class="border-top pt-3">
                        <div class="mb-2">
                            <span class="text-muted small">تاريخ الإنشاء</span>
                            <div class="fw-semibold">{{ $coupon->created_at->format('Y-m-d H:i') }}</div>
                        </div>
                        <div>
                            <span class="text-muted small">آخر تحديث</span>
                            <div class="fw-semibold">{{ $coupon->updated_at->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تفاصيل فترة الصلاحية -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-calendar"></i> فترة الصلاحية
                    </h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 bg-info-subtle rounded-3">
                                <div class="text-muted small mb-1">تاريخ البدء</div>
                                <div class="fw-bold">
                                    <i class="bi bi-calendar-check"></i> {{ $coupon->valid_from->format('Y-m-d') }}
                                </div>
                                <small class="text-muted">{{ $coupon->valid_from->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 {{ $isExpired ? 'bg-danger-subtle' : 'bg-success-subtle' }} rounded-3">
                                <div class="text-muted small mb-1">تاريخ الانتهاء</div>
                                <div class="fw-bold {{ $isExpired ? 'text-danger' : 'text-success' }}">
                                    <i class="bi bi-calendar-x"></i> {{ $coupon->valid_until->format('Y-m-d') }}
                                </div>
                                <small class="text-muted">
                                    @if($isExpired)
                                        منتهي منذ {{ $coupon->valid_until->diffForHumans() }}
                                    @else
                                        ينتهي خلال {{ $coupon->valid_until->diffInDays(now()) }} يوم
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-gear"></i> الإجراءات السريعة
                    </h5>
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $coupon->is_active ? 'warning' : 'success' }} w-100">
                                @if($coupon->is_active)
                                    <i class="bi bi-pause-circle"></i> تعطيل الكوبون
                                @else
                                    <i class="bi bi-play-circle"></i> تفعيل الكوبون
                                @endif
                            </button>
                        </form>
                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" 
                              method="POST" 
                              class="d-inline"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟ سيتم حذف جميع البيانات المرتبطة به.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i class="bi bi-trash"></i> حذف الكوبون
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة المستخدمين الذين استخدموا الكوبون -->
    @php
        $couponUsers = \App\Models\CouponUser::where('coupon_id', $coupon->id)
            ->with(['user', 'booking'])
            ->latest('used_at')
            ->get();
    @endphp
    
    @if($couponUsers->count() > 0)
        <div class="card card-custom">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-people"></i> المستخدمين الذين استخدموا الكوبون
                    <span class="badge bg-primary-subtle text-primary">{{ $couponUsers->count() }}</span>
                </h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">المستخدم</th>
                                <th scope="col">البريد الإلكتروني</th>
                                <th scope="col">رقم الحجز</th>
                                <th scope="col">تاريخ الاستخدام</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($couponUsers as $index => $couponUser)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $couponUser->user?->name ?? 'مستخدم محذوف' }}</div>
                                    </td>
                                    <td>{{ $couponUser->user?->email ?? '-' }}</td>
                                    <td>
                                        @if($couponUser->booking_id)
                                            @if($couponUser->booking)
                                                <a href="{{ route('admin.bookings.show', $couponUser->booking_id) }}" 
                                                   class="text-decoration-none">
                                                    #{{ $couponUser->booking_id }}
                                                </a>
                                            @else
                                                <span class="text-muted">#{{ $couponUser->booking_id }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($couponUser->used_at)
                                            <div class="fw-semibold">{{ $couponUser->used_at->format('Y-m-d') }}</div>
                                            <small class="text-muted">{{ $couponUser->used_at->format('H:i') }}</small>
                                        @elseif($couponUser->created_at)
                                            <div class="fw-semibold">{{ $couponUser->created_at->format('Y-m-d') }}</div>
                                            <small class="text-muted">{{ $couponUser->created_at->format('H:i') }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card card-custom">
            <div class="card-body text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-person-x" style="font-size: 64px; color: #cbd5e1;"></i>
                </div>
                <h5 class="text-muted">لم يتم استخدام الكوبون بعد</h5>
                <p class="text-muted">لا يوجد مستخدمين استخدموا هذا الكوبون حتى الآن</p>
            </div>
        </div>
    @endif

    @push('head')
    <style>
        code {
            font-family: 'Courier New', monospace;
        }
        .progress {
            border-radius: 6px;
            overflow: hidden;
        }
        .card-custom {
            transition: all 0.3s ease;
        }
        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
    </style>
    @endpush
@endsection
