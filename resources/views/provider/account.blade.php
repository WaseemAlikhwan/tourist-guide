@extends('provider.layouts.app')

@section('title', 'الحساب')

@section('content')
<div class="provider-page-header d-flex flex-wrap justify-content-between align-items-start gap-3">
    <div>
        <h1>بيانات الحساب</h1>
        <p class="mb-0">معلومات اعتمادك كمزوّد محتوى داخل المنصة.</p>
    </div>
    <a href="{{ route('providers.storefront', $user) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary align-self-center">
        <i class="fas fa-store"></i> صفحتي العامّة
    </a>
</div>

<div class="provider-panel" style="max-width: 680px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('provider.account.update') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted">الاسم</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted">نوع النشاط</label>
                    <input type="text" name="activity_type" class="form-control" value="{{ old('activity_type', $user->activity_type) }}" placeholder="مثال: جولات سياحية">
                </div>
                <div class="col-md-6">
                    <div class="text-muted small mb-1">حالة الاعتماد</div>
                    @if($user->isApprovedContentProvider())
                        <span class="provider-badge provider-badge-success"><i class="fas fa-circle-check"></i> معتمد</span>
                    @else
                        <span class="provider-badge provider-badge-soft">{{ $user->content_provider_status ?? '—' }}</span>
                    @endif
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted small mb-0">
                    يمكنك تعديل بيانات الحساب الأساسية الخاصة بالمزوّد من هنا.
                </p>
                <button type="submit" class="btn btn-primary btn-sm px-3">
                    <i class="fas fa-save me-1"></i> حفظ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
