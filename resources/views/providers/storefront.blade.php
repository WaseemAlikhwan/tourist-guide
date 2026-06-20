@extends('website.layouts.app')

@section('title', $user->name . ' — مزوّد محتوى | Wander Point')

@push('head')
<style>
    .ps-hero {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(139, 111, 201, 0.85) 100%);
        color: #fff;
        border-radius: 24px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .ps-hero h1 { font-weight: 900; font-size: clamp(1.75rem, 4vw, 2.5rem); margin-bottom: 0.5rem; }
    .ps-hero .sub { opacity: 0.9; font-size: 1.05rem; margin-bottom: 1.25rem; }
    .ps-badges-row { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.25rem; }
    .ps-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        background: rgba(255,255,255,0.15); padding: 0.35rem 0.75rem; border-radius: 999px;
        font-size: 0.8rem; font-weight: 600;
    }
    .ps-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center; }
    .ps-actions .btn-light { border-radius: 12px; font-weight: 600; }
    .ps-actions .btn-outline-light { border-radius: 12px; font-weight: 600; }
    .ps-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .ps-stat {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
    }
    .ps-stat .num { font-size: 1.75rem; font-weight: 800; color: var(--primary-dark); }
    .ps-stat .lbl { font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; }
    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .activity-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
    }
    .activity-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.12);
        border-color: var(--primary);
    }
    .activity-image-wrapper { position: relative; overflow: hidden; height: 200px; }
    .activity-image { width: 100%; height: 100%; object-fit: cover; background: #e5e7eb; }
    .activity-badge {
        position: absolute; top: 0.75rem; right: 0.75rem;
        background: rgba(255,255,255,0.95); padding: 0.35rem 0.75rem; border-radius: 999px;
        font-size: 0.75rem; font-weight: 600; color: var(--primary-dark);
    }
    .activity-content { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; }
    .activity-title { font-size: 1.15rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a; }
    .activity-location { color: #64748b; font-size: 0.85rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.35rem; }
    .activity-footer { margin-top: auto; display: flex; justify-content: space-between; align-items: center; }
    .activity-rating { color: #f59e0b; font-weight: 600; font-size: 0.9rem; }
    .activity-price { color: var(--primary); font-weight: 700; }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="ps-hero">
        <h1>{{ $user->name }}</h1>
        @if($user->activity_type)
            <p class="sub mb-0">{{ $user->activity_type }}</p>
        @else
            <p class="sub mb-0">مزوّد محتوى معتمد على Wander Point</p>
        @endif
        @if($user->badges->isNotEmpty())
            <div class="ps-badges-row">
                @foreach($user->badges as $badge)
                    <span class="ps-badge" title="{{ $badge->description }}">
                        @if($badge->icon)<i class="{{ $badge->icon }}"></i>@else<i class="fas fa-award"></i>@endif
                        {{ $badge->name }}
                    </span>
                @endforeach
            </div>
        @endif
        <div class="ps-actions">
            <button type="button" class="btn btn-light btn-sm" id="psCopyLink" data-url="{{ $publicUrl }}">
                <i class="fas fa-link"></i> نسخ الرابط
            </button>
            <a class="btn btn-outline-light btn-sm" href="https://wa.me/?text={{ urlencode($publicUrl) }}" target="_blank" rel="noopener">
                <i class="fab fa-whatsapp"></i> واتساب
            </a>
            <a class="btn btn-outline-light btn-sm" href="https://twitter.com/intent/tweet?url={{ urlencode($publicUrl) }}&text={{ urlencode($user->name . ' على Wander Point') }}" target="_blank" rel="noopener">
                <i class="fab fa-twitter"></i> X
            </a>
            <a href="{{ route('home') }}" class="btn btn-link text-white text-decoration-none small">الرئيسية</a>
        </div>
    </div>

    <div class="ps-stats">
        <div class="ps-stat">
            <div class="num">{{ $approvedCount }}</div>
            <div class="lbl">أنشطة معتمدة</div>
        </div>
        <div class="ps-stat">
            <div class="num">{{ number_format($avgRating, 1) }}</div>
            <div class="lbl">متوسط التقييم</div>
        </div>
        <div class="ps-stat">
            <div class="num">{{ number_format($bookingsCount) }}</div>
            <div class="lbl">حجوزات مؤكدة/مكتملة</div>
        </div>
    </div>

    <h2 class="mb-3 fw-bold" style="color:#0f172a;">الأنشطة</h2>

    @if($activities->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="fas fa-hiking fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">لا توجد أنشطة معتمدة لعرضها حالياً.</p>
        </div>
    @else
        <div class="activities-grid">
            @foreach($activities as $activity)
                <a href="{{ route('activities.show', $activity) }}" class="activity-card">
                    <div class="activity-image-wrapper">
                        @if($activity->image)
                            <img src="{{ $activity->image_url }}" alt="{{ $activity->name }}" class="activity-image">
                        @else
                            <div class="activity-image d-flex align-items-center justify-content-center">
                                <i class="fas fa-image fa-2x text-secondary opacity-50"></i>
                            </div>
                        @endif
                        <span class="activity-badge">{{ $activity->type }}</span>
                    </div>
                    <div class="activity-content">
                        <h3 class="activity-title">{{ $activity->name }}</h3>
                        <div class="activity-location">
                            <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                            <span>{{ $activity->destination?->name ?? '—' }}</span>
                        </div>
                        <div class="activity-footer">
                            <span class="activity-rating"><i class="fas fa-star"></i> {{ number_format($activity->rating ?? 0, 1) }}</span>
                            @if(!is_null($activity->price))
                                <span class="activity-price">{{ number_format($activity->price, 0) }} ل.س</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.getElementById('psCopyLink')?.addEventListener('click', function() {
    const url = this.getAttribute('data-url');
    if (navigator.clipboard && url) {
        navigator.clipboard.writeText(url).then(function() {
            alert('تم نسخ الرابط.');
        }).catch(function() {
            prompt('انسخ الرابط:', url);
        });
    } else if (url) {
        prompt('انسخ الرابط:', url);
    }
});
</script>
@endpush
