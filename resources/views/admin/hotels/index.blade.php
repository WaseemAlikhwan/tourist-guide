@extends('admin.layouts.app')

@section('title', 'الفنادق | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span>الفنادق</span>
    </div>

    <div class="page-header">
        <div>
            <h1>الفنادق 🏨</h1>
            <p class="subtitle">إدارة جميع الفنادق القريبة من الوجهات السياحية</p>
        </div>
        <a href="{{ route('admin.hotels.create') }}" class="primary-action d-flex align-items-center gap-3 text-decoration-none">
            <span class="icon">＋</span>
            <div class="text-end">
                <div class="fw-bold">إضافة فندق</div>
                <small class="text-white-50">أضف فندقاً جديداً</small>
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
        $totalHotels = \App\Models\Hotel::count();
        $activeHotels = \App\Models\Hotel::where('is_active', true)->count();
        $inactiveHotels = \App\Models\Hotel::where('is_active', false)->count();
        $hotelsWithPrice = \App\Models\Hotel::whereNotNull('price_per_night')->count();
    @endphp

    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي الفنادق</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary">🏨</span>
                </div>
                <div class="stat-number">{{ $totalHotels }}</div>
                <div class="stat-label">فندق إجمالي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">نشطة</span>
                    <span class="badge rounded-pill bg-success-subtle text-success">✅</span>
                </div>
                <div class="stat-number">{{ $activeHotels }}</div>
                <div class="stat-label">فندق نشط</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">غير نشطة</span>
                    <span class="badge rounded-pill bg-warning-subtle text-warning">⏸️</span>
                </div>
                <div class="stat-number">{{ $inactiveHotels }}</div>
                <div class="stat-label">فندق غير نشط</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">ذات سعر</span>
                    <span class="badge rounded-pill bg-info-subtle text-info">💰</span>
                </div>
                <div class="stat-number">{{ $hotelsWithPrice }}</div>
                <div class="stat-label">فندق بسعر محدد</div>
            </div>
        </div>
    </div>

    <!-- جدول الفنادق -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul"></i> قائمة الفنادق
                </h5>
                <span class="badge bg-primary-subtle text-primary">{{ $hotels->total() }} فندق</span>
            </div>

            @if($hotels->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="table align-middle table-hover" style="min-width: 1100px;">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 80px; min-width: 80px;">الصورة</th>
                                <th scope="col" style="min-width: 200px;">اسم الفندق</th>
                                <th scope="col" style="min-width: 120px;">الوجهة</th>
                                <th scope="col" style="min-width: 100px;">التصنيف</th>
                                <th scope="col" class="text-end" style="min-width: 120px;">السعر/الليلة</th>
                                <th scope="col" class="text-center" style="min-width: 100px;">الحالة</th>
                                <th scope="col" style="min-width: 120px;">تاريخ الإضافة</th>
                                <th scope="col" class="text-center" style="min-width: 140px; position: sticky; right: 0; background: white; z-index: 10;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hotels as $hotel)
                                <tr>
                                    <td>
                                        @if($hotel->image)
                                            <img src="{{ asset('storage/' . $hotel->image) }}"
                                                 alt="{{ $hotel->name }}"
                                                 class="rounded-3"
                                                 style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #e2e8f0; transition: all 0.3s ease;">
                                        @else
                                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                                 style="width: 70px; height: 70px; background: linear-gradient(135deg, #f0f0f0, #e0e0e0);">
                                                <i class="bi bi-building text-muted" style="font-size: 24px;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold mb-1">{{ $hotel->name }}</div>
                                        @if($hotel->address)
                                            <div class="text-muted small">
                                                <i class="bi bi-geo-alt"></i> {{ Str::limit($hotel->address, 50) }}
                                            </div>
                                        @endif
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $hotel->updated_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($hotel->destination)
                                            <span class="badge bg-info-subtle text-info fs-6">
                                                <i class="bi bi-geo-alt"></i> {{ $hotel->destination->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hotel->star_rating)
                                            <span class="badge bg-warning-subtle text-warning fs-6">
                                                {!! str_repeat('⭐', $hotel->star_rating) !!} {{ $hotel->star_rating }} نجوم
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($hotel->price_per_night)
                                            <div class="fw-bold text-success">{{ number_format($hotel->price_per_night, 0) }}</div>
                                            <small class="text-muted">ل.س / ليلة</small>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($hotel->is_active)
                                            <span class="badge bg-success-subtle text-success fs-6">
                                                <i class="bi bi-check-circle"></i> نشط
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary fs-6">
                                                <i class="bi bi-pause-circle"></i> غير نشط
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $hotel->created_at->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $hotel->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="text-center" style="position: sticky; right: 0; background: white; z-index: 5; border-left: 2px solid #e2e8f0;">
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <a href="{{ route('admin.hotels.show', $hotel) }}"
                                               class="btn btn-sm btn-outline-secondary" title="عرض"
                                               style="min-width: 38px; white-space: nowrap;">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.hotels.edit', $hotel) }}"
                                               class="btn btn-sm btn-outline-primary" title="تعديل"
                                               style="min-width: 38px; white-space: nowrap;">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.hotels.destroy', $hotel) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الفندق؟');">
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
                @if($hotels->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            عرض {{ $hotels->firstItem() ?? 0 }} إلى {{ $hotels->lastItem() ?? 0 }} من {{ $hotels->total() }} فندق
                        </div>
                        <div>
                            {{ $hotels->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-building" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا توجد فنادق</h5>
                    <p class="text-muted">لا توجد فنادق مضافة حتى الآن</p>
                    <a href="{{ route('admin.hotels.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> إضافة فندق جديد
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
        
        .table tbody img {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover img {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
