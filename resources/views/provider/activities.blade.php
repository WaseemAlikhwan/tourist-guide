@extends('provider.layouts.app')

@section('title', 'أنشطتي')

@section('content')
<div class="provider-page-header">
    <h1>الأنشطة المرتبطة بك</h1>
    <p>أنشطتك تظهر على الموقع بعد الحفظ. يمكنك التعديل أو الحذف في أي وقت.</p>
</div>

<div class="mb-3">
    <a href="{{ route('provider.activities.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> إضافة نشاط أو فعالية
    </a>
</div>

<div class="provider-panel">
    @if($activities->isEmpty())
        <div class="p-4 text-center">
            <div class="mb-2"><i class="fas fa-hiking fa-2x text-muted"></i></div>
            <p class="text-muted mb-0">لا توجد أنشطة مرتبطة بحسابك حالياً. تواصل مع الإدارة لربط أنشطتك.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الوجهة</th>
                        <th>النوع</th>
                        <th>السعر</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activities as $act)
                        <tr>
                            <td class="fw-semibold">{{ $act->name }}</td>
                            <td>{{ $act->destination?->name ?? '—' }}</td>
                            <td><span class="provider-badge provider-badge-soft">{{ $act->type }}</span></td>
                            <td>
                                @if(!is_null($act->price))
                                    {{ number_format($act->price, 0) }} ل.س
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($act->provider_review_status === 'approved')
                                    <span class="provider-badge provider-badge-success">منشور</span>
                                @elseif($act->provider_review_status === 'rejected')
                                    <span class="provider-badge provider-badge-soft">مخفي</span>
                                @else
                                    <span class="provider-badge provider-badge-soft">{{ $act->provider_review_status }}</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('provider.activities.edit', $act) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="{{ route('activities.show', $act) }}" class="btn btn-sm btn-outline-primary" target="_blank" rel="noopener">
                                    <i class="fas fa-up-right-from-square"></i>
                                </a>
                                <form method="POST" action="{{ route('provider.activities.destroy', $act) }}" onsubmit="return confirm('هل تريد حذف هذا النشاط؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">{{ $activities->links() }}</div>
    @endif
</div>
@endsection
