@extends('admin.layouts.app')

@section('title', 'إدارة الحجوزات | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">لوحة التحكم</a></span>
        <span>›</span>
        <span>إدارة الحجوزات</span>
    </div>

    <div class="page-header">
        <div>
            <h1>إدارة الحجوزات 🎫</h1>
            <p class="subtitle">عرض وإدارة جميع حجوزات الأنشطة السياحية</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-secondary">
                <i class="bi bi-printer"></i> طباعة
            </button>
        </div>
    </div>

    
    @php
        $totalBookings = \App\Models\Booking::count();
        $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
        $completedBookings = \App\Models\Booking::where('status', 'completed')->count();
        $paidBookings = \App\Models\Booking::where('payment_status', 'paid')->count();
        $totalRevenue = \App\Models\Booking::where('payment_status', 'paid')->sum('total_price');
    @endphp

    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي الحجوزات</span>
                    <span class="badge rounded-pill bg-primary-subtle text-primary">📋</span>
                </div>
                <div class="stat-number">{{ $totalBookings }}</div>
                <div class="stat-label">حجز إجمالي</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">قيد الانتظار</span>
                    <span class="badge rounded-pill bg-warning-subtle text-warning">⏳</span>
                </div>
                <div class="stat-number">{{ $pendingBookings }}</div>
                <div class="stat-label">حجز معلق</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">مكتملة</span>
                    <span class="badge rounded-pill bg-success-subtle text-success">✅</span>
                </div>
                <div class="stat-number">{{ $completedBookings }}</div>
                <div class="stat-label">حجز مكتمل</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-custom p-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold text-uppercase text-muted small">إجمالي الإيرادات</span>
                    <span class="badge rounded-pill bg-success-subtle text-success">💰</span>
                </div>
                <div class="stat-number">{{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-label">ليرة سورية</div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-funnel"></i> فلترة الحجوزات
            </h5>
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">الحالة</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            ⏳ قيد الانتظار
                        </option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                            ✓ مؤكد
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            ✅ مكتمل
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                            ❌ ملغي
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">حالة الدفع</label>
                    <select name="payment_status" class="form-select" onchange="this.form.submit()">
                        <option value="">جميع حالات الدفع</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                            ⏳ لم يدفع
                        </option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                            ✅ تم الدفع
                        </option>
                        <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>
                            ❌ ملغي
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">من تاريخ</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">إلى تاريخ</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise"></i> إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الحجوزات -->
    <div class="card card-custom">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-list-ul"></i> قائمة الحجوزات
                </h5>
                <span class="badge bg-primary-subtle text-primary">{{ $bookings->total() }} حجز</span>
            </div>

            @if($bookings->count() > 0)
                <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                    <table class="table align-middle table-hover" style="min-width: 1400px;">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 60px; min-width: 60px;">#</th>
                                <th scope="col" style="min-width: 150px;">رقم الحجز</th>
                                <th scope="col" style="min-width: 180px;">المستخدم</th>
                                <th scope="col" style="min-width: 200px;">النشاط</th>
                                <th scope="col" style="min-width: 120px;">الوجهة</th>
                                <th scope="col" style="min-width: 140px;">تاريخ الحجز</th>
                                <th scope="col" class="text-center" style="min-width: 100px;">الأشخاص</th>
                                <th scope="col" class="text-end" style="min-width: 120px;">السعر</th>
                                <th scope="col" style="min-width: 120px;">الحالة</th>
                                <th scope="col" style="min-width: 120px;">حالة الدفع</th>
                                <th scope="col" class="text-center" style="min-width: 100px; position: sticky; right: 0; background: white; z-index: 10;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td class="text-muted">{{ $booking->id }}</td>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $booking->booking_reference }}</div>
                                        <small class="text-muted">{{ $booking->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $booking->user?->name ?? 'مستخدم محذوف' }}</div>
                                        <small class="text-muted">{{ $booking->user?->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($booking->activity?->name ?? 'نشاط محذوف', 30) }}</div>
                                    </td>
                                    <td>
                                        @if($booking->activity?->destination)
                                            <span class="badge bg-info-subtle text-info">
                                                <i class="bi bi-geo-alt"></i> {{ $booking->activity->destination->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $booking->booking_date->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $booking->booking_date->diffForHumans() }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary">
                                            <i class="bi bi-people"></i> {{ $booking->number_of_people }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="fw-bold text-success">{{ number_format($booking->total_price, 0) }}</div>
                                        <small class="text-muted">ل.س</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'success',
                                                'cancelled' => 'danger',
                                                'completed' => 'info'
                                            ];
                                            $statusIcons = [
                                                'pending' => '⏳',
                                                'confirmed' => '✓',
                                                'cancelled' => '❌',
                                                'completed' => '✅'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'قيد الانتظار',
                                                'confirmed' => 'مؤكد',
                                                'cancelled' => 'ملغي',
                                                'completed' => 'مكتمل'
                                            ];
                                            $color = $statusColors[$booking->status] ?? 'secondary';
                                            $icon = $statusIcons[$booking->status] ?? '';
                                            $label = $statusLabels[$booking->status] ?? $booking->status;
                                        @endphp
                                        <span class="badge bg-{{ $color }}-subtle text-{{ $color }} fs-6">
                                            {{ $icon }} {{ $label }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $paymentStatus = $booking->payment_status ?? 'pending';
                                            $paymentColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'cancelled' => 'danger'
                                            ];
                                            $paymentIcons = [
                                                'pending' => '⏳',
                                                'paid' => '✅',
                                                'cancelled' => '❌'
                                            ];
                                            $paymentLabels = [
                                                'pending' => 'لم يدفع',
                                                'paid' => 'تم الدفع',
                                                'cancelled' => 'ملغي'
                                            ];
                                            $paymentColor = $paymentColors[$paymentStatus] ?? 'secondary';
                                            $paymentIcon = $paymentIcons[$paymentStatus] ?? '';
                                            $paymentLabel = $paymentLabels[$paymentStatus] ?? 'غير محدد';
                                        @endphp
                                        <span class="badge bg-{{ $paymentColor }}-subtle text-{{ $paymentColor }} fs-6">
                                            {{ $paymentIcon }} {{ $paymentLabel }}
                                        </span>
                                    </td>
                                    <td class="text-center" style="position: sticky; right: 0; background: white; z-index: 5; border-left: 2px solid #e2e8f0;">
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <a href="{{ route('admin.bookings.show', $booking) }}" 
                                               class="btn btn-sm btn-outline-primary" title="عرض التفاصيل"
                                               style="min-width: 38px; white-space: nowrap;">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($bookings->hasPages())
                    <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted">
                            عرض {{ $bookings->firstItem() ?? 0 }} إلى {{ $bookings->lastItem() ?? 0 }} من {{ $bookings->total() }} حجز
                        </div>
                        <div>
                            {{ $bookings->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-calendar-x" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted">لا توجد حجوزات</h5>
                    <p class="text-muted">لا توجد حجوزات مطابقة لمعايير البحث المحددة</p>
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
        
        .form-select, .form-control {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .form-select:focus, .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
            outline: none;
        }
        
        .badge {
            padding: 6px 12px;
            font-weight: 600;
        }
        
        .btn-outline-primary:hover,
        .btn-outline-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
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
            
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
            }
            
            .page-header .d-flex {
                width: 100%;
                justify-content: flex-start;
                margin-top: 12px;
            }
        }
    </style>
    @endpush
@endsection




