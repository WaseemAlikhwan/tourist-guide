@extends('admin.layouts.app')

@section('title', 'لوحة المزوّد: ' . $user->name)

@section('content')
    <div class="breadcrumb-custom">
        <a href="{{ route('admin.dashboard') }}">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('admin.content-providers.index') }}">مزوّدو المحتوى</a>
        <span>/</span>
        <span>{{ $user->name }}</span>
    </div>

    <div class="page-header d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <h1 class="mb-1"><i class="bi bi-person-workspace me-2"></i>{{ $user->name }}</h1>
            <p class="subtitle mb-0">{{ $user->email }}</p>
            <div class="mt-2 d-flex flex-wrap gap-2 align-items-center">
                @if($user->isApprovedContentProvider())
                    <span class="badge bg-success">نشط</span>
                @elseif($user->content_provider_status === 'pending')
                    <span class="badge bg-warning text-dark">طلب مزوّد: قيد المراجعة</span>
                @elseif($user->content_provider_status === 'rejected' || ! $user->can_login)
                    <span class="badge bg-secondary">معلّق / غير فعّال</span>
                @else
                    <span class="badge bg-light text-dark">{{ $user->content_provider_status }}</span>
                @endif
                @if($user->activity_type)
                    <span class="badge bg-info text-dark">{{ $user->activity_type }}</span>
                @endif
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if($user->isApprovedContentProvider())
                <a href="{{ route('providers.storefront', $user) }}" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener">
                    <i class="bi bi-shop"></i> الصفحة العامّة
                </a>
            @endif
            @if($latestApplication)
                <a href="{{ route('admin.content-providers.show', $latestApplication) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-text"></i> طلب الاعتماد
                </a>
            @endif
            @if($user->isApprovedContentProvider())
                <form action="{{ route('admin.providers.toggle-active', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('تعليق هذا المزوّد وتعطيل تسجيل الدخول؟');">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="bi bi-pause-circle"></i> تعليق
                    </button>
                </form>
            @elseif($user->content_provider_status !== 'pending' && ($user->content_provider_status === 'rejected' || ! $user->can_login))
                <form action="{{ route('admin.providers.toggle-active', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('إعادة تفعيل هذا المزوّد؟');">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-play-circle"></i> إعادة تفعيل
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-box">
                <div class="stat-number">{{ $activities->count() }}</div>
                <div class="stat-label">أنشطة</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="stat-number">{{ number_format($countQualifying) }}</div>
                <div class="stat-label">حجوزات مؤهّلة للأرباح</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="stat-number">{{ number_format($totalEarned, 0) }}</div>
                <div class="stat-label">إجمالي أرباح المزوّد (ل.س)</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="stat-number">{{ number_format($commissionPercent, 0) }}٪</div>
                <div class="stat-label">نسبة المزوّد</div>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="providerTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-activities" data-bs-toggle="tab" data-bs-target="#pane-activities" type="button" role="tab">الأنشطة</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-bookings" data-bs-toggle="tab" data-bs-target="#pane-bookings" type="button" role="tab">الحجوزات</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-earnings" data-bs-toggle="tab" data-bs-target="#pane-earnings" type="button" role="tab">الأرباح</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-account" data-bs-toggle="tab" data-bs-target="#pane-account" type="button" role="tab">الحساب</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active card-custom p-4" id="pane-activities" role="tabpanel">
            @if($activities->isEmpty())
                <p class="text-muted mb-0">لا توجد أنشطة لهذا المزوّد.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الوجهة</th>
                                <th>النوع</th>
                                <th class="text-end">السعر</th>
                                <th>مراجعة النشر</th>
                                <th class="text-center">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activities as $act)
                                <tr>
                                    <td class="fw-semibold">{{ $act->name }}</td>
                                    <td>{{ $act->destination?->name ?? '—' }}</td>
                                    <td><span class="badge bg-secondary-subtle text-secondary">{{ $act->type }}</span></td>
                                    <td class="text-end">
                                        @if(!is_null($act->price))
                                            {{ number_format($act->price, 0) }} ل.س
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>
                                        @if($act->provider_review_status === 'approved')
                                            <span class="badge bg-success">معتمد للنشر</span>
                                        @elseif($act->provider_review_status === 'rejected')
                                            <span class="badge bg-danger">مرفوض</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ $act->provider_review_status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.activities.edit', $act) }}" class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.activities.destroy', $act) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف هذا النشاط؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="tab-pane fade card-custom p-4" id="pane-bookings" role="tabpanel">
            @if($bookings->isEmpty())
                <p class="text-muted mb-0">لا حجوزات مرتبطة بأنشطة هذا المزوّد.</p>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>المرجع</th>
                                <th>النشاط</th>
                                <th>العميل</th>
                                <th>التاريخ</th>
                                <th class="text-end">الإجمالي</th>
                                <th class="text-end">نصيب المزوّد</th>
                                <th>الدفع</th>
                                <th>الحالة</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $b)
                                <tr>
                                    <td><code>{{ $b->booking_reference }}</code></td>
                                    <td>{{ $b->activity?->name ?? '—' }}</td>
                                    <td>{{ $b->user?->name ?? '—' }}</td>
                                    <td>{{ $b->booking_date?->format('Y-m-d') }}</td>
                                    <td class="text-end">{{ number_format($b->total_price, 0) }} ل.س</td>
                                    <td class="text-end">
                                        @if($b->qualifiesForProviderEarnings())
                                            {{ number_format($b->provider_share, 0) }} ل.س
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $b->payment_status }}</td>
                                    <td>{{ $b->status }}</td>
                                    <td>
                                        <a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-sm btn-outline-secondary">عرض</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $bookings->links() }}</div>
            @endif
        </div>

        <div class="tab-pane fade card-custom p-4" id="pane-earnings" role="tabpanel">
            @php
                $maxDaily = max(1, collect($dailySeries)->max('amount'));
                $maxMonthly = max(1, collect($monthlySeries)->max('amount'));
            @endphp
            <div class="row g-3">
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3">أرباح آخر 7 أيام</h6>
                    @foreach($dailySeries as $item)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="text-muted small" style="width: 42px;">{{ $item['label'] }}</div>
                            <div class="flex-grow-1" style="background:#f1f5f9;border-radius:999px;height:10px;">
                                <div style="height:10px;border-radius:999px;background:linear-gradient(90deg,#b89ff0,#8b6fc9);width: {{ round(($item['amount'] / $maxDaily) * 100, 2) }}%;"></div>
                            </div>
                            <div class="small fw-semibold" style="min-width: 95px;">{{ number_format($item['amount'], 0) }} ل.س</div>
                        </div>
                    @endforeach
                </div>
                <div class="col-lg-6">
                    <h6 class="fw-bold mb-3">أرباح آخر 6 أشهر</h6>
                    @foreach($monthlySeries as $item)
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="text-muted small" style="width: 74px;">{{ $item['label'] }}</div>
                            <div class="flex-grow-1" style="background:#f1f5f9;border-radius:999px;height:10px;">
                                <div style="height:10px;border-radius:999px;background:linear-gradient(90deg,#34d399,#059669);width: {{ round(($item['amount'] / $maxMonthly) * 100, 2) }}%;"></div>
                            </div>
                            <div class="small fw-semibold" style="min-width: 95px;">{{ number_format($item['amount'], 0) }} ل.س</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="tab-pane fade card-custom p-4" id="pane-account" role="tabpanel" style="max-width: 640px;">
            <form method="POST" action="{{ route('admin.providers.update', $user) }}">
                @csrf
                @method('PATCH')
                <div class="mb-3">
                    <label class="form-label fw-semibold">الاسم</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">البريد</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">نوع النشاط</label>
                    <input type="text" name="activity_type" class="form-control @error('activity_type') is-invalid @enderror" value="{{ old('activity_type', $user->activity_type) }}">
                    @error('activity_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> حفظ التغييرات</button>
            </form>
        </div>
    </div>
@endsection
