@extends('admin.layouts.app')

@section('title', 'إدارة الكوبونات | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">لوحة التحكم</a></span>
        <span>›</span>
        <span>الكوبونات</span>
    </div>

    <div class="page-header">
        <div>
            <h1>الكوبونات 🎫</h1>
            <p class="subtitle">إدارة جميع كوبونات الخصم والعروض الخاصة</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="primary-action d-flex align-items-center gap-3 text-decoration-none">
            <span class="icon">＋</span>
            <div class="text-end">
                <div class="fw-bold">إضافة كوبون</div>
                <small class="text-white-50">إنشاء كوبون خصم جديد</small>
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
        $totalCoupons = \App\Models\Coupon::count();
        $activeCoupons = \App\Models\Coupon::where('is_active', true)->count();
        $expiredCoupons = \App\Models\Coupon::where('valid_until', '<', now())->count();
        $totalUsage = \App\Models\Coupon::sum('usage_count');
    @endphp

    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي الكوبونات</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary">🎫</span>
                </div>
                <div class="stat-number">{{ $totalCoupons }}</div>
                <div class="stat-label">كوبون إجمالي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">نشطة</span>
                    <span class="badge rounded-pill bg-success-subtle text-success">✅</span>
                </div>
                <div class="stat-number">{{ $activeCoupons }}</div>
                <div class="stat-label">كوبون نشط</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">منتهية</span>
                    <span class="badge rounded-pill bg-warning-subtle text-warning">⏰</span>
                </div>
                <div class="stat-number">{{ $expiredCoupons }}</div>
                <div class="stat-label">كوبون منتهي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي الاستخدام</span>
                    <span class="badge rounded-pill bg-info-subtle text-info">📊</span>
                </div>
                <div class="stat-number">{{ $totalUsage }}</div>
                <div class="stat-label">مرة استخدام</div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-funnel"></i> فلترة الكوبونات
            </h5>
            <form method="GET" action="{{ route('admin.coupons.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">الحالة</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                            ✅ نشط
                        </option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                            ⏸️ معطل
                        </option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>
                            ⏰ منتهي
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">نوع الخصم</label>
                    <select name="discount_type" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الأنواع</option>
                        <option value="percentage" {{ request('discount_type') == 'percentage' ? 'selected' : '' }}>
                            نسبة مئوية
                        </option>
                        <option value="fixed" {{ request('discount_type') == 'fixed' ? 'selected' : '' }}>
                            قيمة ثابتة
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">بحث (كود أو وصف)</label>
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           value="{{ request('search') }}" 
                           placeholder="ابحث بالكود أو الوصف...">
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> بحث
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الكوبونات -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul"></i> قائمة الكوبونات
                </h5>
                <span class="badge bg-primary-subtle text-primary">{{ $coupons->total() }} كوبون</span>
            </div>

            @if($coupons->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="table align-middle table-hover" style="min-width: 1300px;">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 60px; min-width: 60px;">#</th>
                                <th scope="col" style="min-width: 150px;">كود الكوبون</th>
                                <th scope="col" style="min-width: 200px;">الوصف</th>
                                <th scope="col" style="min-width: 120px;">نوع الخصم</th>
                                <th scope="col" class="text-end" style="min-width: 120px;">قيمة الخصم</th>
                                <th scope="col" class="text-end" style="min-width: 120px;">الحد الأدنى</th>
                                <th scope="col" class="text-center" style="min-width: 120px;">الاستخدام</th>
                                <th scope="col" style="min-width: 160px;">فترة الصلاحية</th>
                                <th scope="col" class="text-center" style="min-width: 100px;">الحالة</th>
                                <th scope="col" class="text-center" style="min-width: 140px; position: sticky; right: 0; background: white; z-index: 10;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($coupons as $coupon)
                                @php
                                    $isExpired = $coupon->valid_until < now();
                                    $isRunningOut = $coupon->usage_limit && $coupon->usage_count >= ($coupon->usage_limit * 0.8);
                                @endphp
                                <tr class="{{ $isExpired ? 'table-secondary' : '' }}">
                                    <td class="text-muted">{{ $coupon->id }}</td>
                                    <td>
                                        <div class="fw-bold">
                                            <code class="bg-light p-2 rounded-2" style="color: var(--primary); font-size: 14px; font-weight: 700;">
                                                {{ $coupon->code }}
                                            </code>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($coupon->description ?? 'لا يوجد وصف', 50) }}</div>
                                    </td>
                                    <td>
                                        @if($coupon->discount_type == 'percentage')
                                            <span class="badge bg-info-subtle text-info fs-6">
                                                <i class="bi bi-percent"></i> نسبة مئوية
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success fs-6">
                                                <i class="bi bi-currency-dollar"></i> قيمة ثابتة
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($coupon->discount_type == 'percentage')
                                            <div class="fw-bold text-info fs-5">{{ number_format($coupon->discount_value, 0) }}%</div>
                                        @else
                                            <div class="fw-bold text-success fs-5">{{ number_format($coupon->discount_value, 0) }}</div>
                                            <small class="text-muted">ل.س</small>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($coupon->minimum_purchase)
                                            <div class="fw-semibold">{{ number_format($coupon->minimum_purchase, 0) }}</div>
                                            <small class="text-muted">ل.س</small>
                                        @else
                                            <span class="text-muted">لا يوجد</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center gap-1">
                                            <span class="badge {{ $isRunningOut ? 'bg-warning-subtle text-warning' : 'bg-primary-subtle text-primary' }} fs-6">
                                                {{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}
                                            </span>
                                            @if($coupon->usage_limit)
                                                @php
                                                    $usagePercent = ($coupon->usage_count / $coupon->usage_limit) * 100;
                                                @endphp
                                                <div class="progress" style="width: 80px; height: 6px;">
                                                    <div class="progress-bar {{ $usagePercent >= 80 ? 'bg-warning' : 'bg-primary' }}" 
                                                         role="progressbar" 
                                                         style="width: {{ min($usagePercent, 100) }}%"></div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="fw-semibold mb-1">
                                                <i class="bi bi-calendar-check"></i> من: {{ $coupon->valid_from->format('Y-m-d') }}
                                            </div>
                                            <div class="fw-semibold {{ $isExpired ? 'text-danger' : 'text-success' }}">
                                                <i class="bi bi-calendar-x"></i> إلى: {{ $coupon->valid_until->format('Y-m-d') }}
                                            </div>
                                            @if($isExpired)
                                                <span class="badge bg-danger-subtle text-danger small mt-1">منتهي</span>
                                            @elseif($coupon->valid_until->diffInDays(now()) <= 7)
                                                <span class="badge bg-warning-subtle text-warning small mt-1">
                                                    ينتهي خلال {{ $coupon->valid_until->diffInDays(now()) }} يوم
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm {{ $coupon->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                    title="{{ $coupon->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}">
                                                @if($coupon->is_active)
                                                    <i class="bi bi-check-circle"></i> نشط
                                                @else
                                                    <i class="bi bi-x-circle"></i> معطل
                                                @endif
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-center" style="position: sticky; right: 0; background: white; z-index: 5; border-left: 2px solid #e2e8f0;">
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <a href="{{ route('admin.coupons.show', $coupon) }}" 
                                               class="btn btn-sm btn-outline-secondary" title="عرض"
                                               style="min-width: 38px; white-space: nowrap;">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                               class="btn btn-sm btn-outline-primary" title="تعديل"
                                               style="min-width: 38px; white-space: nowrap;">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الكوبون؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="حذف"
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
                @if($coupons->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            عرض {{ $coupons->firstItem() ?? 0 }} إلى {{ $coupons->lastItem() ?? 0 }} من {{ $coupons->total() }} كوبون
                        </div>
                        <div>
                            {{ $coupons->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-ticket-perforated" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا توجد كوبونات</h5>
                    <p class="text-muted">لا توجد كوبونات مضافة حتى الآن</p>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> إضافة كوبون جديد
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
        
        .table-secondary {
            background-color: rgba(108, 117, 125, 0.05) !important;
        }
        
        code {
            font-family: 'Courier New', monospace;
        }
        
        .progress {
            border-radius: 4px;
            overflow: hidden;
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
        .btn-outline-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        /* تحسين الإجراءات للشاشات الصغيرة */
        @media (max-width: 1600px) {
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




