@extends('website.layouts.app')

@section('title', 'حالة طلب مزوّد المحتوى')

@section('content')
<div class="py-5" style="max-width: 640px; margin: 0 auto;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">
            <h1 class="h4 fw-bold mb-3">حالة طلب الاعتماد</h1>
            @if($user->content_provider_status === 'pending')
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-hourglass-half me-2"></i>
                    طلبك قيد المراجعة من فريق المنصة. سنُبلغك عبر البريد عند اتخاذ قرار.
                </div>
            @elseif($user->content_provider_status === 'rejected')
                <div class="alert alert-danger mb-0">
                    <i class="fas fa-times-circle me-2"></i>
                    لم يُعتمد طلبك. يمكنك التواصل معنا من صفحة «تواصل معنا» لمزيد من التفاصيل.
                </div>
            @endif
            <p class="text-muted mt-3 mb-0 small">
                نوع النشاط المُرسل: {{ $user->activity_type ?? '—' }}
            </p>
        </div>
    </div>
</div>
@endsection
