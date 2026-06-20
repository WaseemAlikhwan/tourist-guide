@extends('provider.layouts.app')

@section('title', 'الحجوزات')

@section('content')
<div class="provider-page-header d-flex flex-wrap justify-content-between align-items-start gap-3">
    <div>
        <h1>الحجوزات</h1>
        <p class="mb-0">جميع الحجوزات المرتبطة بأنشطتك. نسبة المزوّد الحالية: {{ number_format($commissionPercent, 0) }}٪.</p>
    </div>
    <a href="{{ route('provider.export.bookings') }}" class="btn btn-sm btn-outline-secondary align-self-center">
        <i class="fas fa-file-csv"></i> تصدير CSV
    </a>
</div>

<div class="provider-panel">
    @if($bookings->isEmpty())
        <div class="p-4 text-center">
            <div class="mb-2"><i class="fas fa-calendar-xmark fa-2x text-muted"></i></div>
            <p class="text-muted mb-0">لا توجد حجوزات حتى الآن.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>المرجع</th>
                        <th>النشاط</th>
                        <th>العميل</th>
                        <th>التاريخ</th>
                        <th>الإجمالي</th>
                        <th>الدفع</th>
                        <th>الحالة</th>
                        <th>نصيبك</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                        <tr>
                            <td><code>{{ $b->booking_reference }}</code></td>
                            <td>{{ $b->activity?->name ?? '—' }}</td>
                            <td>{{ $b->user?->name ?? '—' }}</td>
                            <td>{{ $b->booking_date?->format('Y-m-d') }}</td>
                            <td>{{ number_format($b->total_price, 0) }} ل.س</td>
                            <td>
                                @if($b->payment_status === 'paid')
                                    <span class="provider-badge provider-badge-success">مدفوع</span>
                                @else
                                    <span class="provider-badge provider-badge-soft">{{ $b->payment_status }}</span>
                                @endif
                            </td>
                            <td><span class="provider-badge provider-badge-soft">{{ $b->status }}</span></td>
                            <td>
                                @if($b->qualifiesForProviderEarnings())
                                    <span class="text-success fw-semibold">{{ number_format($b->provider_share, 0) }} ل.س</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">{{ $bookings->links() }}</div>
    @endif
</div>
@endsection
