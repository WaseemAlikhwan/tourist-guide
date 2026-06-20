@extends('provider.layouts.app')

@section('title', 'نظرة عامة')

@section('content')
<div class="provider-page-header d-flex flex-wrap justify-content-between align-items-start gap-3">
    <div>
        <h1>مرحباً، {{ $user->name }}</h1>
        <p class="mb-0">ملخص سريع لأداء حسابك كـ مزوّد محتوى معتمد في المنصة.</p>
    </div>
</div>

<div class="provider-panel p-3 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div class="fw-semibold"><i class="fas fa-bolt text-warning"></i> إجراءات سريعة</div>
        <span class="provider-badge provider-badge-soft">لتسريع إدارة يومك</span>
    </div>
    <div class="row g-2">
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('provider.activities.create') }}" class="btn btn-sm btn-outline-primary w-100 provider-quick-btn">
                <i class="fas fa-plus"></i> إضافة نشاط
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('provider.bookings.index') }}" class="btn btn-sm btn-outline-primary w-100 provider-quick-btn">
                <i class="fas fa-calendar-check"></i> مراجعة الحجوزات
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('providers.storefront', $user) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary w-100 provider-quick-btn">
                <i class="fas fa-store"></i> صفحتي العامّة
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <button
                type="button"
                class="btn btn-sm btn-outline-primary w-100 provider-quick-btn"
                id="copyStorefrontLinkBtn"
                data-storefront-url="{{ route('providers.storefront', $user) }}"
            >
                <i class="fas fa-link"></i> نسخ رابط الصفحة
            </button>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="provider-panel p-3 h-100">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                <div class="fw-semibold"><i class="fas fa-calendar-day text-primary"></i> أجندة اليوم</div>
                <span class="small text-muted">{{ now()->format('Y-m-d') }}</span>
            </div>
            @if($todayAgenda->isEmpty())
                <div class="text-muted small">لا يوجد مهام أو فعاليات لليوم.</div>
            @else
                <div class="provider-agenda-list">
                    @foreach($todayAgenda->take(8) as $item)
                        <div class="provider-agenda-item d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <div class="fw-semibold small">{{ $item['title'] }}</div>
                                <div class="text-muted small">{{ $item['meta'] }}</div>
                            </div>
                            <span class="provider-badge {{ $item['type'] === 'event' ? 'provider-badge-event' : 'provider-badge-soft' }}">
                                {{ $item['status'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
                @if($todayAgenda->count() > 8)
                    <a href="{{ route('provider.bookings.index') }}" class="small mt-2 d-inline-block">عرض المزيد</a>
                @endif
            @endif
        </div>
    </div>
    <div class="col-lg-4">
        <div class="provider-panel p-3 h-100">
            <div class="small text-muted mb-1">إشعارات غير مقروءة</div>
            <div class="fs-4 fw-bold">{{ $unreadNotificationsCount }}</div>
            @if($unreadNotificationsCount > 0)
                <a href="{{ route('provider.notifications.index') }}" class="small">فتح الإشعارات</a>
            @endif
            <hr>
            <div class="small text-muted mb-1">فعاليات خلال 7 أيام</div>
            @if($weekEvents->isEmpty())
                <div class="text-muted small">لا فعاليات بهذه الفترة.</div>
            @else
                <ul class="list-unstyled small mb-0">
                    @foreach($weekEvents->take(4) as $ev)
                        <li class="mb-1 d-flex align-items-center justify-content-between gap-2">
                            <span class="fw-semibold text-truncate">{{ $ev->name }}</span>
                            <span class="text-muted">{{ $ev->event_date?->format('m/d') }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-12">
        <div class="provider-panel p-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                <div class="fw-semibold"><i class="fas fa-trophy text-warning"></i> أفضل الأنشطة أداءً</div>
                <span class="small text-muted">بحسب الإيراد المؤهّل</span>
            </div>
            @php
                $maxTopEarnings = max(1, (int) collect($topActivities)->max('provider_earnings'));
            @endphp
            @if(collect($topActivities)->isEmpty())
                <div class="text-muted small">لا توجد بيانات كافية لعرض أفضل الأنشطة حالياً.</div>
            @else
                @foreach($topActivities as $activity)
                    <div class="provider-top-activity d-flex align-items-center gap-2">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <span class="fw-semibold small text-truncate">{{ $activity['name'] }}</span>
                                <span class="text-muted small">{{ number_format($activity['bookings_count']) }} حجز</span>
                            </div>
                            <div class="provider-progress-track mt-1">
                                <div
                                    class="provider-progress-bar"
                                    style="width: {{ round(($activity['provider_earnings'] / $maxTopEarnings) * 100, 2) }}%;"
                                ></div>
                            </div>
                        </div>
                        <div class="fw-semibold small text-success" style="min-width: 110px;">
                            {{ number_format($activity['provider_earnings'], 0) }} ل.س
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('provider.export.bookings') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-file-csv"></i> تصدير الحجوزات CSV
    </a>
    <a href="{{ route('provider.export.earnings') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-file-csv"></i> تصدير الأرباح (90 يوم) CSV
    </a>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-4 col-md-6">
        <div class="provider-stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">إجمالي أرباحك</span>
                    <span class="stat-icon"><i class="fas fa-wallet"></i></span>
                </div>
                <div class="fs-4 fw-bold text-success">{{ number_format($totalEarned, 0) }} ل.س</div>
                <div class="small text-muted mt-1">من الحجوزات المدفوعة والمؤهّلة فقط</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="provider-stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">حجوزات مؤهّلة</span>
                    <span class="stat-icon"><i class="fas fa-calendar-check"></i></span>
                </div>
                <div class="fs-4 fw-bold">{{ $bookingsCount }}</div>
                <div class="small text-muted mt-1">حجوزات بحالة دفع مكتملة</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="provider-stat-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">أنشطة مرتبطة بك</span>
                    <span class="stat-icon"><i class="fas fa-hiking"></i></span>
                </div>
                <div class="fs-4 fw-bold">{{ $activitiesCount }}</div>
                <div class="small text-muted mt-1">أنشطة ظاهرة في تبويب «أنشطتي»</div>
            </div>
        </div>
    </div>
</div>

<div class="provider-panel mb-3 p-3">
    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="provider-badge provider-badge-soft"><i class="fas fa-percentage"></i> نسبة المزوّد</span>
        <span class="fw-semibold">{{ number_format($commissionPercent, 0) }}٪</span>
        <span class="text-muted small">من إجمالي الحجز بعد تأكيد الدفع</span>
    </div>
</div>

@php
    $maxDaily = max(1, collect($dailySeries)->max('amount'));
    $maxMonthly = max(1, collect($monthlySeries)->max('amount'));
@endphp

<div class="row g-3 mb-3">
    <div class="col-lg-6">
        <div class="provider-panel p-3">
            <div class="fw-semibold mb-2">أرباح آخر 7 أيام</div>
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
    </div>
    <div class="col-lg-6">
        <div class="provider-panel p-3">
            <div class="fw-semibold mb-2">أرباح آخر 6 أشهر</div>
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

<div class="provider-panel">
    <div class="p-3 border-bottom fw-semibold">آخر الحجوزات المؤهّلة للأرباح</div>
    @if($recentBookings->isEmpty())
        <div class="p-4 text-center">
            <div class="mb-2"><i class="fas fa-inbox fa-2x text-muted"></i></div>
            <p class="text-muted mb-0">لا توجد حجوزات مؤهّلة بعد. عند ربط أنشطة بك وتأكيد دفعات العملاء، ستظهر هنا.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>المرجع</th>
                        <th>النشاط</th>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th>نصيبك</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $b)
                        <tr>
                            <td><code>{{ $b->booking_reference }}</code></td>
                            <td>{{ $b->activity?->name ?? '—' }}</td>
                            <td>{{ $b->booking_date?->format('Y-m-d') }}</td>
                            <td>{{ number_format($b->total_price, 0) }} ل.س</td>
                            <td class="text-success fw-semibold">{{ number_format($b->provider_share, 0) }} ل.س</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div id="copyStorefrontToast" class="provider-copy-toast d-none" role="status" aria-live="polite">
    تم نسخ رابط صفحتك العامّة
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var copyBtn = document.getElementById('copyStorefrontLinkBtn');
        var copyToast = document.getElementById('copyStorefrontToast');
        if (!copyBtn || !copyToast) {
            return;
        }

        function showCopyToast(message) {
            copyToast.textContent = message;
            copyToast.classList.remove('d-none');
            copyToast.classList.add('show');
            setTimeout(function () {
                copyToast.classList.remove('show');
                setTimeout(function () { copyToast.classList.add('d-none'); }, 180);
            }, 1800);
        }

        copyBtn.addEventListener('click', async function () {
            var url = copyBtn.getAttribute('data-storefront-url') || '';
            try {
                await navigator.clipboard.writeText(url);
                showCopyToast('تم نسخ رابط صفحتك العامّة');
            } catch (e) {
                showCopyToast('تعذّر النسخ، انسخ الرابط يدويًا');
            }
        });
    })();
</script>
@endpush
