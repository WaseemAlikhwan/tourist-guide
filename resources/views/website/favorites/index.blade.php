@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'My Favorites - Tourist Guide' : 'مفضلاتي - الدليل السياحي')

@push('head')
<style>
    .page-header {
        margin-bottom: 32px;
    }
    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #0f172a;
    }
    .favorites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
    }
    .favorite-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .favorite-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .favorite-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #e5e7eb;
    }
    .favorite-content {
        padding: 20px;
    }
    .favorite-type {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        margin-bottom: 12px;
    }
    .favorite-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #0f172a;
    }
    .favorite-description {
        color: #475569;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 16px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .favorite-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #64748b;
        font-size: 0.85rem;
    }
    .remove-favorite {
        background: #fee2e2;
        color: #991b1b;
        padding: 8px 16px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .remove-favorite:hover {
        background: #fecaca;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }
    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    @media (max-width: 768px) {
        .favorites-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>{{ $isEn ? 'My Favorites' : 'مفضلاتي' }}</h1>
    <p>{{ $isEn ? 'Destinations and activities you saved' : 'الوجهات والأنشطة التي حفظتها' }}</p>
</div>

@if($favorites->count() > 0)
    <div class="favorites-grid">
        @foreach($favorites as $favorite)
            @php
                $item = $favorite->favoritable;
                $isDestination = $favorite->favoritable_type === 'App\\Models\\Destination';
                $routeName = $isDestination ? 'destinations.show' : 'activities.show';
            @endphp
            
            <div class="favorite-card">
                <a href="{{ route($routeName, $item) }}">
                    @if($item->image ?? null)
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="favorite-image">
                    @else
                        <div class="favorite-image" style="display: flex; align-items: center; justify-content: center; background: #e5e7eb;">
                            <svg width="60" height="60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                                @if($isDestination)
                                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                    <path d="M9 22V12h6v10"></path>
                                @else
                                    <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                @endif
                            </svg>
                        </div>
                    @endif
                </a>
                <div class="favorite-content">
                    <span class="favorite-type">{{ $isDestination ? ($isEn ? 'Destination' : 'وجهة') : ($item->type ?? ($isEn ? 'Activity' : 'نشاط')) }}</span>
                    <a href="{{ route($routeName, $item) }}" style="text-decoration: none;">
                        <h3 class="favorite-title">{{ $item->name }}</h3>
                    </a>
                    @if($isDestination)
                        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 12px;">{{ $item->country }}</p>
                        <p class="favorite-description">{{ $item->description }}</p>
                        <div class="favorite-meta">
                            <span>{{ $item->activities->count() }} {{ $isEn ? 'activities' : 'نشاط' }}</span>
                        </div>
                    @else
                        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 12px;">📍 {{ $item->destination->name }}</p>
                        <p class="favorite-description">{{ $item->description }}</p>
                        <div class="favorite-meta">
                            <span>⭐ {{ $item->rating ?? '0.0' }}</span>
                            <span style="color: var(--primary); font-weight: 700;">{{ number_format($item->price, 0) }} {{ $isEn ? 'SYP' : 'ل.س' }}</span>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('favorites.toggle') }}" style="margin-top: 16px;">
                        @csrf
                        <input type="hidden" name="favoritable_type" value="{{ $isDestination ? 'destination' : 'activity' }}">
                        <input type="hidden" name="favoritable_id" value="{{ $item->id }}">
                        <button type="submit" class="remove-favorite">{{ $isEn ? 'Remove from Favorites' : 'إزالة من المفضلة' }}</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="pagination" style="margin-top: 40px; display: flex; justify-content: center; gap: 8px;">
        {{ $favorites->links() }}
    </div>
@else
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
        <h3>{{ $isEn ? 'No favorites yet' : 'لا توجد مفضلات بعد' }}</h3>
        <p>{{ $isEn ? 'Start adding destinations and activities to your favorites' : 'ابدأ بإضافة وجهات وأنشطة إلى مفضلاتك' }}</p>
        <div style="margin-top: 24px;">
            <a href="{{ route('destinations.index') }}" class="btn" style="margin-left: 8px;">{{ $isEn ? 'Browse Destinations' : 'تصفح الوجهات' }}</a>
            <a href="{{ route('activities.index') }}" class="btn">{{ $isEn ? 'Browse Activities' : 'تصفح الأنشطة' }}</a>
        </div>
    </div>
@endif
@endsection
