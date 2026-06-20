@extends('admin.layouts.app')

@section('title', 'تحليلات ارتباط الفعاليات')

@section('content')
<div class="page-header">
    <div>
        <h1>تحليلات ارتباط الفعاليات</h1>
        <p class="subtitle">الأزواج الأكثر تكراراً وقوة لاتخاذ قرارات الباقات والتوصيات</p>
    </div>
</div>

<div class="card card-custom mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">أدنى ثقة (Confidence)</label>
                <input type="number" class="form-control" min="0" max="1" step="0.01" name="min_confidence" value="{{ $minConfidence }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">أدنى دعم (Support)</label>
                <input type="number" class="form-control" min="0" max="1" step="0.01" name="min_support" value="{{ $minSupport }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100" type="submit">تطبيق</button>
            </div>
        </form>
    </div>
</div>

<div class="card card-custom">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>الفعالية A</th>
                        <th>الفعالية B</th>
                        <th>التكرار المشترك</th>
                        <th>Support</th>
                        <th>Confidence</th>
                        <th>Lift</th>
                        <th>قرار مقترح</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($associations as $row)
                        <tr>
                            <td>{{ $row->activity?->name ?? '-' }}</td>
                            <td>{{ $row->associatedActivity?->name ?? '-' }}</td>
                            <td>{{ $row->co_occurrence_count }}</td>
                            <td>{{ number_format($row->support, 3) }}</td>
                            <td>{{ number_format($row->confidence, 3) }}</td>
                            <td>{{ number_format($row->lift, 3) }}</td>
                            <td>
                                @if($row->confidence >= 0.35 && $row->lift >= 1.2)
                                    <span class="badge bg-success">ادعم كـ باقة مشتركة</span>
                                @elseif($row->confidence >= 0.2)
                                    <span class="badge bg-info">اعرض كتوصية قوية</span>
                                @else
                                    <span class="badge bg-secondary">متابعة بدون ترويج خاص</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">لا توجد بيانات مطابقة</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $associations->links() }}
        </div>
    </div>
</div>
@endsection
