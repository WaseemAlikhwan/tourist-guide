@extends('admin.layouts.app')

@section('title', 'الرئيسية | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span>لوحة التحكم</span>
    </div>

    <div class="page-header">
        <div>
            <h1>مرحباً {{ Auth::guard('admin')->user()->name }} 👋</h1>
            <p class="subtitle">هنا تجد لمحة كاملة عن أداء Wander Point in Syria وتحديثاته اليومية.</p>
        </div>
        <a href="{{ route('admin.destinations.create') }}" class="primary-action d-flex align-items-center gap-3 text-decoration-none">
            <span class="icon">＋</span>
            <div class="text-end">
                <div class="fw-bold">إضافة وجهة</div>
                <small class="text-white-50">شارك مكاناً مميزاً</small>
            </div>
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card card-custom overflow-hidden">
                <div class="card-body d-lg-flex align-items-center justify-content-between gap-4">
                    <div class="d-flex align-items-center gap-4">
                        <div class="rounded-4 bg-light p-4 text-center">
                            <span class="fs-1">🧭</span>
                        </div>
                        <div>
                            <h4 class="fw-bolder mb-1">Wander Point في أرقام</h4>
                            <p class="text-muted mb-0">تابع أداء المنصة، واكتشف الأنشطة الأكثر رواجاً هذا الأسبوع.</p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-end gap-3">
                        <div class="px-4 py-3 rounded-4 bg-light text-end">
                            <div class="text-muted small">نمو الحجوزات</div>
                            <div class="fw-bold fs-4 {{ $bookingsGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $bookingsGrowth >= 0 ? '+' : '' }}{{ $bookingsGrowth }}%
                            </div>
                        </div>
                        <div class="px-4 py-3 rounded-4 bg-light text-end">
                            <div class="text-muted small">حجوزات هذا الأسبوع</div>
                            <div class="fw-bold fs-4 text-success">{{ $thisWeekBookings ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card card-custom p-4 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold text-uppercase text-muted small">عدد الوجهات</span>
                    <div class="badge rounded-pill bg-success-subtle text-success fs-6">🧭</div>
                </div>
                <div class="stat-number mb-2">{{ $destinationsCount }}</div>
                <div class="stat-label">وجهة مفعّلة حالياً</div>
                <div class="position-absolute" style="bottom: -10px; left: -10px; width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 50%;"></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-custom p-4 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold text-uppercase text-muted small">الأنشطة</span>
                    <div class="badge rounded-pill bg-info-subtle text-info fs-6">🎯</div>
                </div>
                <div class="stat-number mb-2">{{ $activitiesCount }}</div>
                <div class="stat-label">نشاط سياحي</div>
                <div class="position-absolute" style="bottom: -10px; left: -10px; width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); border-radius: 50%;"></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-custom p-4 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold text-uppercase text-muted small">التعليقات</span>
                    <div class="badge rounded-pill bg-warning-subtle text-warning fs-6">💬</div>
                </div>
                <div class="stat-number mb-2">{{ $reviewsCount }}</div>
                <div class="stat-label">تعليق موجود</div>
                <div class="position-absolute" style="bottom: -10px; left: -10px; width: 80px; height: 80px; background: rgba(245, 158, 11, 0.1); border-radius: 50%;"></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card card-custom p-4 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold text-uppercase text-muted small">المستخدمين</span>
                    <div class="badge rounded-pill bg-primary-subtle text-primary fs-6">👥</div>
                </div>
                <div class="stat-number mb-2">{{ $usersCount ?? 0 }}</div>
                <div class="stat-label">مستخدم مسجل</div>
                <div class="position-absolute" style="bottom: -10px; left: -10px; width: 80px; height: 80px; background: rgba(14, 165, 233, 0.1); border-radius: 50%;"></div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card card-custom p-4 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold text-uppercase text-muted small">المفضلات</span>
                    <div class="badge rounded-pill bg-danger-subtle text-danger fs-6">❤️</div>
                </div>
                <div class="stat-number mb-2">{{ $favoritesCount ?? 0 }}</div>
                <div class="stat-label">عنصر في المفضلة</div>
                <div class="position-absolute" style="bottom: -10px; left: -10px; width: 80px; height: 80px; background: rgba(239, 68, 68, 0.1); border-radius: 50%;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card card-custom p-4 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold text-uppercase text-muted small">متوسط التقييم</span>
                    <div class="badge rounded-pill bg-warning-subtle text-warning fs-6">⭐</div>
                </div>
                <div class="stat-number mb-2">{{ number_format($avgRating ?? 0, 1) }}</div>
                <div class="stat-label">من 5 نجوم</div>
                <div class="position-absolute" style="bottom: -10px; left: -10px; width: 80px; height: 80px; background: rgba(245, 158, 11, 0.1); border-radius: 50%;"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card card-custom p-4 position-relative overflow-hidden">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي القيمة</span>
                    <div class="badge rounded-pill bg-success-subtle text-success fs-6">💰</div>
                </div>
                <div class="stat-number mb-2">{{ number_format($totalActivitiesPrice ?? 0, 0) }}</div>
                <div class="stat-label">ليرة سورية</div>
                <div class="position-absolute" style="bottom: -10px; left: -10px; width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 50%;"></div>
            </div>
        </div>
    </div>

    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-geo-alt"></i> آخر الوجهات المضافة
                    </h5>
                    <p class="text-muted small mb-0">أحدث الوجهات التي تم إضافتها للمنصة</p>
                </div>
                <a class="btn btn-outline-primary" href="{{ route('admin.destinations.index') }}">
                    <i class="bi bi-arrow-left"></i> عرض الكل
                </a>
            </div>
            @if($recentDestinations->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">الوجهة</th>
                                <th scope="col">الدولة</th>
                                <th scope="col" class="text-center">عدد الأنشطة</th>
                                <th scope="col" class="text-center">متوسط التقييم</th>
                                <th scope="col" class="text-center">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDestinations as $destination)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $destination->name }}</div>
                                        @if($destination->description)
                                            <div class="text-muted small">{{ Str::limit($destination->description, 40) }}</div>
                                        @endif
                                        <small class="text-muted">تم التحديث {{ $destination->updated_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info-subtle text-info">
                                            <i class="bi bi-globe"></i> {{ $destination->country }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary fs-6">
                                            {{ $destination->activities_count }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $avgRating = $destination->activities->avg('rating');
                                        @endphp
                                        @if($avgRating && $avgRating > 0)
                                            <span class="badge bg-success-subtle text-success fs-6">
                                                <i class="bi bi-star-fill"></i> {{ number_format($avgRating, 1) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.destinations.show', $destination) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="عرض">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.destinations.edit', $destination) }}" 
                                               class="btn btn-sm btn-outline-primary" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-inbox" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا توجد وجهات</h5>
                    <p class="text-muted">لا توجد وجهات مضافة حتى الآن</p>
                    <a href="{{ route('admin.destinations.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> إضافة وجهة جديدة
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card card-custom h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-activity"></i> أحدث الأنشطة
                            </h5>
                            <p class="text-muted small mb-0">أحدث الأنشطة المضافة للمنصة</p>
                        </div>
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> عرض الكل
                        </a>
                    </div>
                    @if($recentActivities->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivities as $activity)
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-start border-bottom">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold mb-1">{{ $activity->name }}</div>
                                        <small class="text-muted d-block mb-2">
                                            {{ Str::limit($activity->description ?? 'لا يوجد وصف', 50) }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div>
                                        @if($activity->destination)
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="bi bi-geo-alt"></i> {{ $activity->destination->name }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">-</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-activity" style="font-size: 48px; color: #cbd5e1;"></i>
                            <p class="text-muted mt-3">لا توجد أنشطة حالياً</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card card-custom h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-lightning-charge"></i> المهام السريعة
                            </h5>
                            <p class="text-muted small mb-0">اختر مهمة وابدأ العمل الآن</p>
                        </div>
                    </div>
                    <div class="d-grid gap-3">
                        @php
                            $recentReviewsCount = \App\Models\Review::where('created_at', '>=', now()->subDays(7))->count();
                            $newContactsCount = $newContactsCount ?? 0;
                            $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
                        @endphp
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="btn btn-outline-primary d-flex justify-content-between align-items-center text-decoration-none p-3 rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="badge bg-danger-subtle text-danger fs-5">📅</div>
                                <div class="text-start">
                                    <div class="fw-bold">الحجوزات المعلقة</div>
                                    <small class="text-muted">مراجعة الحجوزات الجديدة</small>
                                </div>
                            </div>
                            @if($pendingBookingsCount > 0)
                                <span class="badge bg-danger text-white rounded-pill fs-6">{{ $pendingBookingsCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.activities.create') }}" 
                           class="btn btn-outline-success d-flex justify-content-between align-items-center text-decoration-none p-3 rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="badge bg-success-subtle text-success fs-5">➕</div>
                                <div class="text-start">
                                    <div class="fw-bold">إضافة نشاط سياحي</div>
                                    <small class="text-muted">إنشاء نشاط جديد</small>
                                </div>
                            </div>
                            <span class="badge bg-success rounded-pill">جديد</span>
                        </a>
                        <a href="{{ route('admin.contacts.index') }}" 
                           class="btn btn-outline-warning d-flex justify-content-between align-items-center text-decoration-none p-3 rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="badge bg-warning-subtle text-warning fs-5">📧</div>
                                <div class="text-start">
                                    <div class="fw-bold">رسائل التواصل</div>
                                    <small class="text-muted">مراجعة الرسائل الجديدة</small>
                                </div>
                            </div>
                            @if($newContactsCount > 0)
                                <span class="badge bg-warning text-dark rounded-pill fs-6">{{ $newContactsCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.comments.index') }}" 
                           class="btn btn-outline-info d-flex justify-content-between align-items-center text-decoration-none p-3 rounded-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="badge bg-info-subtle text-info fs-5">💬</div>
                                <div class="text-start">
                                    <div class="fw-bold">التعليقات الجديدة</div>
                                    <small class="text-muted">مراجعة التعليقات الأخيرة</small>
                                </div>
                            </div>
                            @if($recentReviewsCount > 0)
                                <span class="badge bg-info text-white rounded-pill fs-6">{{ $recentReviewsCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('head')
    <style>
        .list-group-item {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .list-group-item:hover {
            background-color: #f8f9fa;
            border-left-color: var(--primary);
            transform: translateX(-4px);
        }
        .btn-outline-primary,
        .btn-outline-success,
        .btn-outline-warning,
        .btn-outline-info {
            transition: all 0.2s ease;
            border-width: 2px;
        }
        .btn-outline-primary:hover {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
        }
        .btn-outline-success:hover {
            background: #10b981;
            border-color: #10b981;
            color: white;
            transform: translateY(-2px);
        }
        .btn-outline-warning:hover {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
            transform: translateY(-2px);
        }
        .btn-outline-info:hover {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
            transform: translateY(-2px);
        }
    </style>
    @endpush
@endsection

