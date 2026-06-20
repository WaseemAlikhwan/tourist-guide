@extends('admin.layouts.app')

@section('title', 'الأنشطة السياحية | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">لوحة التحكم</a></span>
        <span>›</span>
        <span>الأنشطة السياحية</span>
    </div>

    <div class="page-header">
        <div>
            <h1>الأنشطة السياحية 🎯</h1>
            <p class="subtitle">إدارة جميع الأنشطة السياحية المتاحة في المنصة</p>
        </div>
        <a href="{{ route('admin.activities.create') }}" class="primary-action d-flex align-items-center gap-3 text-decoration-none">
            <span class="icon">＋</span>
            <div class="text-end">
                <div class="fw-bold">إضافة نشاط</div>
                <small class="text-white-50">إنشاء نشاط جديد</small>
            </div>
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $totalActivities = \App\Models\Activity::count();
        $featuredActivities = \App\Models\Activity::where('is_featured', true)->count();
        $mustVisitActivities = \App\Models\Activity::where('is_must_visit', true)->count();
        $activitiesRequiringBooking = \App\Models\Activity::where('requires_booking', true)->count();
    @endphp

    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي الأنشطة</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary">🎯</span>
                </div>
                <div class="stat-number">{{ $totalActivities }}</div>
                <div class="stat-label">نشاط سياحي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">مميزة</span>
                    <span class="badge rounded-pill bg-warning-subtle text-warning">⭐</span>
                </div>
                <div class="stat-number">{{ $featuredActivities }}</div>
                <div class="stat-label">نشاط مميز</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">يجب زيارتها</span>
                    <span class="badge rounded-pill bg-danger-subtle text-danger">❤️</span>
                </div>
                <div class="stat-number">{{ $mustVisitActivities }}</div>
                <div class="stat-label">نشاط مميز</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">تتطلب حجز</span>
                    <span class="badge rounded-pill bg-info-subtle text-info">📅</span>
                </div>
                <div class="stat-number">{{ $activitiesRequiringBooking }}</div>
                <div class="stat-label">نشاط يتطلب حجز</div>
            </div>
        </div>
    </div>

    <!-- جدول الأنشطة -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul"></i> قائمة الأنشطة
                </h5>
                <span class="badge bg-primary-subtle text-primary">{{ $activities->total() }} نشاط</span>
            </div>

            @if($activities->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="table align-middle table-hover" style="min-width: 1200px;">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 60px; min-width: 60px;">#</th>
                                <th scope="col" style="min-width: 200px;">النشاط</th>
                                <th scope="col" style="min-width: 120px;">الوجهة</th>
                                <th scope="col" class="text-end" style="min-width: 100px;">السعر</th>
                                <th scope="col" style="min-width: 100px;">النوع</th>
                                <th scope="col" class="text-center" style="min-width: 90px;">مميز</th>
                                <th scope="col" class="text-center" style="min-width: 110px;">يجب زيارته</th>
                                <th scope="col" class="text-center" style="min-width: 110px;">يتطلب حجز</th>
                                <th scope="col" style="min-width: 100px;">المدة</th>
                                <th scope="col" style="min-width: 120px;">التاريخ</th>
                                <th scope="col" class="text-center" style="min-width: 140px; position: sticky; right: 0; background: white; z-index: 10;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $activity)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration + ($activities->currentPage() - 1) * $activities->perPage() }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $activity->name }}</div>
                                        @if($activity->description)
                                            <small class="text-muted">{{ Str::limit($activity->description, 40) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->destination)
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="bi bi-geo-alt"></i> {{ $activity->destination->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if(!is_null($activity->price))
                                            <div class="fw-bold text-success">{{ number_format($activity->price, 0) }}</div>
                                            <small class="text-muted">ل.س</small>
                                        @else
                                            <span class="text-muted">مجاني</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->type)
                                            <span class="badge bg-secondary-subtle text-secondary">{{ $activity->type }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($activity->is_featured)
                                            <span class="badge bg-warning-subtle text-warning">
                                                <i class="bi bi-star-fill"></i> نعم
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">لا</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($activity->is_must_visit)
                                            <span class="badge bg-danger-subtle text-danger">
                                                <i class="bi bi-heart-fill"></i> نعم
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">لا</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($activity->requires_booking ?? true)
                                            <span class="badge bg-primary-subtle text-primary">
                                                <i class="bi bi-calendar-check"></i> نعم
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success">
                                                <i class="bi bi-walking"></i> لا
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($activity->formatted_duration)
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="bi bi-clock"></i> {{ $activity->formatted_duration }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $activity->created_at?->format('Y-m-d') ?? '-' }}</div>
                                        <small class="text-muted">{{ $activity->created_at?->diffForHumans() ?? '-' }}</small>
                                    </td>
                                    <td class="text-center" style="position: sticky; right: 0; background: white; z-index: 5; border-left: 2px solid #e2e8f0;">
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <a href="{{ route('admin.activities.show', $activity) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="عرض"
                                               style="min-width: 38px; white-space: nowrap;">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.activities.edit', $activity) }}" 
                                               class="btn btn-sm btn-outline-primary" title="تعديل"
                                               style="min-width: 38px; white-space: nowrap;">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.activities.destroy', $activity) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"
                                                        style="min-width: 38px; white-space: nowrap;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($activities->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            عرض {{ $activities->firstItem() ?? 0 }} إلى {{ $activities->lastItem() ?? 0 }} من {{ $activities->total() }} نشاط
                        </div>
                        <div>
                            {{ $activities->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-activity" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا توجد أنشطة</h5>
                    <p class="text-muted">لا توجد أنشطة مضافة حتى الآن</p>
                    <a href="{{ route('admin.activities.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> إضافة نشاط جديد
                    </a>
                </div>
            @endif
        </div>
    </div>

    @push('head')
    <style>
        .table-responsive {
            position: relative;
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
            border-radius: 12px;
            margin: -1px;
            padding: 1px;
        }
        
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }
        
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 4px;
        }
        
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
        
        .table tbody tr {
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .table tbody tr:hover td[style*="position: sticky"] {
            background-color: #f8f9fa !important;
        }
        
        .table thead th[style*="position: sticky"],
        .table tbody td[style*="position: sticky"] {
            box-shadow: -2px 0 4px rgba(0,0,0,0.05);
        }
        
        .table thead th[style*="position: sticky"] {
            background-color: #f8f9fa !important;
        }
        
        .card-custom .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            margin: 8px 0;
        }
        
        .stat-label {
            color: var(--text-light);
            font-size: 14px;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 600;
        }
        
        .btn-outline-primary:hover,
        .btn-outline-secondary:hover,
        .btn-outline-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* تحسين الإجراءات للشاشات الصغيرة */
        @media (max-width: 1400px) {
            .table {
                font-size: 13px;
            }
            
            .table th,
            .table td {
                padding: 10px 8px;
            }
            
            .btn-sm {
                padding: 4px 8px;
                font-size: 12px;
                min-width: 34px;
            }
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                border: 1px solid #e2e8f0;
            }
            
            .table {
                font-size: 12px;
            }
            
            .table th,
            .table td {
                padding: 8px 6px;
            }
            
            .btn-sm {
                padding: 3px 6px;
                font-size: 11px;
                min-width: 30px;
            }
        }
    </style>
    @endpush
@endsection
