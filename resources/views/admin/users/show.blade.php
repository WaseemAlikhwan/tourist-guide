@extends('admin.layouts.app')

@section('title', 'تفاصيل المستخدم | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.users.index') }}" class="text-decoration-none">المستخدمين</a></span>
        <span>›</span>
        <span>تفاصيل المستخدم</span>
    </div>

    <div class="page-header">
        <div>
            <h1>{{ $user->name }} 👤</h1>
            <p class="subtitle">تفاصيل المستخدم الكاملة</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> تعديل
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card card-custom mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">معلومات المستخدم</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">الاسم الكامل</label>
                        <p class="fs-5 mb-0">{{ $user->name }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">البريد الإلكتروني</label>
                        <p class="fs-5 mb-0">{{ $user->email }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">الدور</label>
                        <p class="fs-5 mb-0">
                            @if($user->role === 'admin')
                                <span class="badge bg-danger fs-6">مدير</span>
                            @else
                                <span class="badge bg-primary fs-6">مستخدم</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small">تاريخ التسجيل</label>
                        <p class="mb-0">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-semibold text-muted small">آخر تحديث</label>
                        <p class="mb-0">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- الإحصائيات -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card card-custom">
                        <div class="card-body text-center">
                            <h3 class="text-primary mb-2">{{ $stats['total_bookings'] }}</h3>
                            <p class="text-muted mb-0">الحجوزات</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-custom">
                        <div class="card-body text-center">
                            <h3 class="text-success mb-2">{{ $stats['total_favorites'] }}</h3>
                            <p class="text-muted mb-0">المفضلة</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- الحجوزات الأخيرة -->
            @if($user->bookings->count() > 0)
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">الحجوزات الأخيرة</h5>
                        <div class="list-group list-group-flush">
                            @foreach($user->bookings as $booking)
                                <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $booking->activity->name ?? 'نشاط محذوف' }}</div>
                                        <small class="text-muted">
                                            {{ $booking->created_at->format('Y-m-d H:i') }} - 
                                            الحالة: 
                                            <span class="badge bg-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ $booking->status === 'confirmed' ? 'مؤكد' : ($booking->status === 'pending' ? 'قيد الانتظار' : 'ملغي') }}
                                            </span>
                                        </small>
                                    </div>
                                    <span class="badge bg-light text-dark">{{ number_format($booking->total_price, 0) }} ل.س</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- المفضلة -->
            @if($user->favorites->count() > 0)
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-4">المفضلة</h5>
                        <div class="list-group list-group-flush">
                            @foreach($user->favorites as $favorite)
                                @if($favorite->favoritable)
                                    <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold">{{ $favorite->favoritable->name ?? 'عنصر محذوف' }}</div>
                                            <small class="text-muted">{{ $favorite->created_at->format('Y-m-d') }}</small>
                                        </div>
                                        <span class="badge bg-primary-subtle text-primary">{{ class_basename($favorite->favoritable_type) }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- الشارات -->
            @if($user->badges->count() > 0)
                <div class="card card-custom">
                    <div class="card-body">
                        <h5 class="card-title mb-4">الشارات المكتسبة</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($user->badges as $badge)
                                <span class="badge bg-warning text-dark fs-6">
                                    {{ $badge->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="card-title mb-4">إجراءات سريعة</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary">
                            <i class="bi bi-pencil"></i> تعديل المستخدم
                        </a>
                        @if(auth()->guard('admin')->id() !== $user->id && $user->role !== 'admin')
                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-trash"></i> حذف المستخدم
                                </button>
                            </form>
                        @elseif($user->role === 'admin')
                            <button type="button" class="btn btn-outline-secondary w-100" disabled>
                                <i class="bi bi-shield-lock"></i> لا يمكن حذف المدير
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

