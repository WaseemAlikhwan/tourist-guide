@extends('admin.layouts.app')

@section('title', 'طلبات مزوّدي المحتوى السياحي')

@section('content')
    <div class="page-header">
        <div>
            <h1>
                <i class="bi bi-person-badge me-2"></i>
                طلبات مزوّدي المحتوى السياحي
            </h1>
            <p class="subtitle">
                مراجعة طلبات التوثيق والاعتماد لمزوّدي المحتوى (فنادق، شركات سياحية، منظمي فعاليات، أدلاء سياحيين).
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ $stats['pending'] }}</div>
                <div class="stat-label">طلبات قيد المراجعة</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ $stats['approved'] }}</div>
                <div class="stat-label">مزودو محتوى معتمدون</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ $stats['rejected'] }}</div>
                <div class="stat-label">طلبات مرفوضة</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ $kpis['reviewed_today'] }}</div>
                <div class="stat-label">مراجعات اليوم</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ $kpis['oldest_pending_days'] }}</div>
                <div class="stat-label">أقدم طلب قيد المراجعة (يوم)</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-number">{{ $kpis['rejection_ratio'] }}%</div>
                <div class="stat-label">نسبة الرفض من الطلبات المُعالَجة</div>
            </div>
        </div>
    </div>

    <div class="card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="mb-0">
                قائمة الطلبات
            </h5>
            <form method="GET" class="d-flex align-items-center gap-2">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>معتمد</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>مرفوض</option>
                </select>
            </form>
        </div>

        @if($applications->count() === 0)
            <div class="text-center py-5">
                <i class="bi bi-inboxes" style="font-size: 3rem; color: #cbd5e1;"></i>
                <p class="mt-3 mb-1 fw-bold">لا توجد طلبات حالياً</p>
                <p class="text-muted mb-0">سيتم عرض أي طلبات جديدة لمزوّدي المحتوى هنا فور استلامها.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم الكامل</th>
                        <th>البريد الإلكتروني</th>
                        <th>نوع النشاط</th>
                        <th>الحالة</th>
                        <th>تاريخ الطلب</th>
                        <th>آخر تحديث</th>
                        <th>إجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($applications as $application)
                        <tr>
                            <td>{{ $application->id }}</td>
                            <td>{{ $application->full_name }}</td>
                            <td>{{ $application->email }}</td>
                            <td>
                                @php
                                    $labels = [
                                        'hotel' => 'فندق',
                                        'travel_company' => 'شركة سياحية',
                                        'event_organizer' => 'منظم فعاليات',
                                        'tour_guide' => 'دليل سياحي',
                                    ];
                                @endphp
                                <span class="badge bg-light text-dark">
                                    {{ $labels[$application->activity_type] ?? $application->activity_type }}
                                </span>
                            </td>
                            <td>
                                @if($application->status === 'pending')
                                    <span class="badge bg-warning text-dark">قيد المراجعة</span>
                                @elseif($application->status === 'approved')
                                    <span class="badge bg-success">معتمد</span>
                                @else
                                    <span class="badge bg-danger">مرفوض</span>
                                @endif
                            </td>
                            <td>{{ $application->created_at?->format('Y-m-d H:i') }}</td>
                            <td>{{ $application->updated_at?->diffForHumans() }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('admin.content-providers.show', $application) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> عرض
                                    </a>
                                    @if($application->status === 'approved' && $application->user)
                                        <a href="{{ route('admin.providers.show', $application->user) }}" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-speedometer2"></i> لوحة المزوّد
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper mt-3">
                <div class="pagination-info">
                    عرض {{ $applications->firstItem() }}–{{ $applications->lastItem() }} من {{ $applications->total() }} طلباً
                </div>
                {{ $applications->withQueryString()->links() }}
            </div>
        @endif
    </div>
@endsection

