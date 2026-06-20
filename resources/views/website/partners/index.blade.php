@extends('website.layouts.app')

@section('title', __('ui.partners_page_title'))

@push('head')
<style>
    .partners-hero {
        position: relative;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.92) 0%, rgba(139, 111, 201, 0.82) 100%),
                    url('https://images.unsplash.com/photo-1526778548025-fa2f9223976e?w=1920&q=80') center/cover no-repeat;
        color: #fff;
        padding: 4rem 1.5rem 3rem;
        text-align: center;
        border-radius: 24px;
        margin: -60px 0 2.5rem 0;
        overflow: hidden;
    }
    .partners-hero h1 {
        font-weight: 900;
        font-size: clamp(1.75rem, 4vw, 2.75rem);
        margin-bottom: 0.75rem;
        text-shadow: 0 2px 12px rgba(0,0,0,0.35);
    }
    .partners-hero p {
        max-width: 640px;
        margin: 0 auto;
        opacity: 0.95;
        font-size: 1.1rem;
    }
    .partners-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
        margin-bottom: 2rem;
    }
    .partners-chips a {
        border-radius: 999px;
        padding: 0.45rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        border: 2px solid #e5e7eb;
        background: #fff;
        color: #334155;
        transition: all 0.2s ease;
    }
    .partners-chips a:hover {
        border-color: var(--primary, #8b6fc9);
        color: var(--primary-dark, #5b4a8a);
    }
    .partners-chips a.active {
        background: linear-gradient(135deg, #8b6fc9 0%, #6366f1 100%);
        border-color: transparent;
        color: #fff;
    }
    .partner-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        transition: box-shadow 0.25s ease, transform 0.25s ease;
        position: relative;
    }
    .partner-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 32px rgba(0,0,0,0.1);
        border-color: #c4b5fd;
    }
    .partner-card-avatar {
        height: 140px;
        background: linear-gradient(145deg, #f1f5f9 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .partner-card-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .partner-card-avatar .placeholder-icon {
        font-size: 3rem;
        color: #94a3b8;
    }
    .partner-card-body {
        padding: 1.25rem 1.25rem 1.5rem;
    }
    .partner-card-body h2 {
        font-size: 1.15rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .partner-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem 1.25rem;
        font-size: 0.88rem;
        color: #64748b;
        margin-bottom: 0.75rem;
    }
    .partner-stats strong {
        color: #0f172a;
    }
    .partner-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--primary, #8b6fc9);
    }
    .partner-empty {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #64748b;
        background: #f8fafc;
        border-radius: 20px;
        border: 1px dashed #cbd5e1;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="partners-hero">
        <h1>{{ __('ui.partners') }}</h1>
        <p>{{ __('ui.partners_hero_lead') }}</p>
    </div>

    @if($types->isNotEmpty())
        <div class="partners-chips">
            <a href="{{ route('partners.index') }}" class="{{ $type ? '' : 'active' }}">{{ __('ui.all') }}</a>
            @foreach($types as $t)
                <a href="{{ route('partners.index', ['type' => $t]) }}" class="{{ (string) $type === (string) $t ? 'active' : '' }}">{{ $t }}</a>
            @endforeach
        </div>
    @endif

    @if($providers->isEmpty())
        <div class="partner-empty">
            <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
            <p class="mb-0">{{ __('ui.partners_empty') }}</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($providers as $provider)
                @php
                    $avg = round((float) ($provider->provided_activities_avg_rating ?? 0), 1);
                    $av = $provider->avatar;
                    if ($av && ! \Illuminate\Support\Str::startsWith($av, ['http://', 'https://'])) {
                        $av = \Illuminate\Support\Str::startsWith($av, '/') ? $av : asset('storage/'.ltrim($av, '/'));
                    }
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="partner-card">
                        <div class="partner-card-avatar">
                            @if($av)
                                <img src="{{ $av }}" alt="{{ $provider->name }}">
                            @else
                                <span class="placeholder-icon"><i class="fas fa-building"></i></span>
                            @endif
                        </div>
                        <div class="partner-card-body">
                            <h2>{{ $provider->name }}</h2>
                            @if($provider->activity_type)
                                <span class="badge bg-info text-dark mb-2">{{ $provider->activity_type }}</span>
                            @endif
                            <div class="partner-stats">
                                <span><i class="fas fa-hiking text-primary"></i> <strong>{{ (int) $provider->approved_activities_count }}</strong> {{ __('ui.partners_activities_count') }}</span>
                                <span><i class="fas fa-star text-warning"></i> <strong>{{ number_format($avg, 1) }}</strong> {{ __('ui.partners_rating_short') }}</span>
                            </div>
                            <span class="partner-cta">{{ __('ui.partners_view_storefront') }} <i class="fas fa-chevron-left small"></i></span>
                        </div>
                        <a href="{{ route('providers.storefront', $provider) }}" class="stretched-link" aria-label="{{ __('ui.partners_view_storefront') }}: {{ $provider->name }}"></a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $providers->links() }}
        </div>
    @endif
</div>
@endsection
