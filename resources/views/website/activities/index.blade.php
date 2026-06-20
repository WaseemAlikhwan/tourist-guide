@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'Tourist Activities in Syria - Wander Point in Syria' : 'الأنشطة السياحية في سوريا - Wander Point in Syria')

@push('head')
<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.4) 100%), 
                    url('https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1920&q=80') center/cover no-repeat;
        color: #fff;
        padding: 120px 20px;
        text-align: center;
        border-radius: 24px;
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
        min-height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.7) 0%, rgba(184, 134, 11, 0.5) 100%);
        z-index: 1;
    }
    .hero-section > * {
        position: relative;
        z-index: 2;
    }
    .hero-section h1 {
        font-size: 4rem;
        margin-bottom: 24px;
        font-weight: 900;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
        line-height: 1.2;
    }
    .hero-section p {
        font-size: 1.5rem;
        opacity: 0.95;
        margin-bottom: 32px;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        max-width: 800px;
    }
    .page-header {
        margin-bottom: 32px;
    }
    .page-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #0f172a;
    }
    .category-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    .category-tab {
        padding: 0.75rem 1.5rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 50px;
        color: #64748b;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .category-tab:hover,
    .category-tab.active {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
    }
    .filters {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .filter-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #0f172a;
        font-size: 0.9rem;
    }
    .filter-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #0f172a;
        font-size: 0.9rem;
    }
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(14,165,233,.15);
    }
    .activities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 24px;
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
        transform: translateY(-8px);
        box-shadow: 0 12px 32px rgba(0,0,0,0.15);
        border-color: var(--primary);
    }
    .activity-image-wrapper {
        position: relative;
        overflow: hidden;
        height: 250px;
    }
    .activity-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: #e5e7eb;
        transition: transform 0.5s ease;
    }
    .activity-card:hover .activity-image {
        transform: scale(1.1);
    }
    .activity-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--primary-dark);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .activity-content {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .activity-type {
        display: inline-block;
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.1) 0%, rgba(160, 82, 45, 0.1) 100%);
        color: var(--primary-dark);
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 12px;
        width: fit-content;
    }
    .activity-title {
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: #0f172a;
        line-height: 1.3;
    }
    .activity-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .activity-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #f59e0b;
        font-weight: 600;
    }
    .activity-price {
        color: var(--primary);
        font-weight: 700;
        font-size: 1.2rem;
    }
    .activity-location {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .activity-description {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.7;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
        margin-bottom: 16px;
    }
    .activity-footer {
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .pagination {
        margin-top: 40px;
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .pagination a, .pagination span {
        padding: 8px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #0f172a;
        text-decoration: none;
        transition: all 0.2s;
    }
    .pagination a:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    .pagination .active span {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    .view-toggle {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 2rem;
        justify-content: flex-end;
    }
    .view-btn {
        padding: 0.5rem 1rem;
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .view-btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid var(--primary);
    }
    .section-header h2 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .section-header h2 i {
        color: var(--primary);
    }
    .section-header a {
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .section-header a:hover {
        color: var(--primary-dark);
        transform: translateX(-5px);
    }
    .featured-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 2;
    }
    .must-visit-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: linear-gradient(135deg, #DC143C 0%, #B22222 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 2;
    }
    .event-date-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: linear-gradient(135deg, #228B22 0%, #006400 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 2;
    }
    .section-wrapper {
        margin-bottom: 4rem;
    }
    @media (max-width: 768px) {
        .hero-section {
            padding: 80px 20px;
            min-height: 400px;
        }
        .hero-section h1 { font-size: 2.5rem; }
        .hero-section p { font-size: 1.2rem; }
        .activities-grid { grid-template-columns: 1fr; }
        .filters-grid { grid-template-columns: 1fr; }
        .category-tabs { justify-content: flex-start; }
        .view-toggle { justify-content: center; }
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="hero-section">
    <h1>{{ $isEn ? 'Discover Activities in Syria' : 'اكتشف الأنشطة في سوريا' }}</h1>
    <p>{{ $isEn ? 'Enjoy unforgettable experiences from culture and history to outdoor adventures' : 'استمتع بتجارب لا تُنسى من الثقافة والتاريخ إلى المغامرات الطبيعية' }}</p>
</div>

<form method="GET" action="{{ route('activities.index') }}" class="filters">
    <div class="filters-grid">
        <div class="filter-group">
            <label for="search">{{ $isEn ? 'Search' : 'البحث' }}</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ $isEn ? 'Search by name...' : 'ابحث بالاسم...' }}" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; color: #0f172a; font-size: 0.9rem;">
        </div>
        <div class="filter-group">
            <label for="type">{{ $isEn ? 'Activity Type' : 'نوع النشاط' }}</label>
            <select name="type" id="type" onchange="this.form.submit()">
                <option value="">{{ $isEn ? 'All Types' : 'جميع الأنواع' }}</option>
                <option value="ترفيهي" {{ request('type') == 'ترفيهي' ? 'selected' : '' }}>{{ $isEn ? 'Entertainment' : 'ترفيهي' }}</option>
                <option value="ثقافي" {{ request('type') == 'ثقافي' ? 'selected' : '' }}>{{ $isEn ? 'Cultural' : 'ثقافي' }}</option>
                <option value="رياضي" {{ request('type') == 'رياضي' ? 'selected' : '' }}>{{ $isEn ? 'Sports' : 'رياضي' }}</option>
                <option value="طبيعي" {{ request('type') == 'طبيعي' ? 'selected' : '' }}>{{ $isEn ? 'Nature' : 'طبيعي' }}</option>
                <option value="تاريخي" {{ request('type') == 'تاريخي' ? 'selected' : '' }}>{{ $isEn ? 'Historical' : 'تاريخي' }}</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="destination_id">{{ $isEn ? 'Destination' : 'الوجهة' }}</label>
            <select name="destination_id" id="destination_id" onchange="this.form.submit()">
                <option value="">{{ $isEn ? 'All Destinations' : 'جميع الوجهات' }}</option>
                @foreach($destinations as $dest)
                    <option value="{{ $dest->id }}" {{ request('destination_id') == $dest->id ? 'selected' : '' }}>
                        {{ $dest->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label for="sort">{{ $isEn ? 'Sort' : 'الترتيب' }}</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ $isEn ? 'Latest' : 'الأحدث' }}</option>
                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ $isEn ? 'Highest Rated' : 'الأعلى تقييماً' }}</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ $isEn ? 'Lowest Price' : 'الأقل سعراً' }}</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ $isEn ? 'Highest Price' : 'الأعلى سعراً' }}</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="price_min">{{ $isEn ? 'Minimum Price' : 'الحد الأدنى للسعر' }}</label>
            <input type="number" name="price_min" id="price_min" value="{{ request('price_min') }}" placeholder="0" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; color: #0f172a; font-size: 0.9rem;">
        </div>
        <div class="filter-group">
            <label for="price_max">{{ $isEn ? 'Maximum Price' : 'الحد الأقصى للسعر' }}</label>
            <input type="number" name="price_max" id="price_max" value="{{ request('price_max') }}" placeholder="10000" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; color: #0f172a; font-size: 0.9rem;">
        </div>
        <div class="filter-group">
            <label for="min_rating">{{ $isEn ? 'Minimum Rating' : 'الحد الأدنى للتقييم' }}</label>
            <select name="min_rating" id="min_rating" onchange="this.form.submit()" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; color: #0f172a; font-size: 0.9rem;">
                <option value="">{{ $isEn ? 'All Ratings' : 'جميع التقييمات' }}</option>
                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>4+ ⭐</option>
                <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>3+ ⭐</option>
                <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>2+ ⭐</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="date_from">{{ $isEn ? 'Event Date From' : 'من تاريخ فعالية' }}</label>
            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
        </div>
        <div class="filter-group">
            <label for="date_to">{{ $isEn ? 'Event Date To' : 'إلى تاريخ فعالية' }}</label>
            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
        </div>
        <div class="filter-group">
            <label for="duration_min">{{ $isEn ? 'Minimum Duration (minutes)' : 'المدة الأدنى (دقيقة)' }}</label>
            <input type="number" name="duration_min" id="duration_min" value="{{ request('duration_min') }}" min="0" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
        </div>
        <div class="filter-group">
            <label for="duration_max">{{ $isEn ? 'Maximum Duration (minutes)' : 'المدة الأعلى (دقيقة)' }}</label>
            <input type="number" name="duration_max" id="duration_max" value="{{ request('duration_max') }}" min="0" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px;">
        </div>
        <div class="filter-group">
            <label for="requires_booking">{{ $isEn ? 'Requires Booking' : 'يتطلب حجز' }}</label>
            <select name="requires_booking" id="requires_booking" onchange="this.form.submit()">
                <option value="">{{ $isEn ? 'All' : 'الكل' }}</option>
                <option value="1" {{ request('requires_booking') === '1' ? 'selected' : '' }}>{{ $isEn ? 'Yes' : 'نعم' }}</option>
                <option value="0" {{ request('requires_booking') === '0' ? 'selected' : '' }}>{{ $isEn ? 'No' : 'لا' }}</option>
            </select>
        </div>
        <div class="filter-group" style="display: flex; align-items: flex-end;">
            <button type="submit" style="width: 100%; padding: 10px 12px; background: var(--primary); color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">{{ $isEn ? 'Search' : 'بحث' }}</button>
        </div>
    </div>
</form>

<!-- فئات الأنشطة -->
<div class="category-tabs">
    <a href="{{ route('activities.index', ['type' => '']) }}" class="category-tab {{ !request('type') ? 'active' : '' }}">
        <i class="fas fa-th"></i>
        <span>{{ $isEn ? 'All' : 'الكل' }}</span>
    </a>
    <a href="{{ route('activities.index', ['type' => 'ثقافي']) }}" class="category-tab {{ request('type') == 'ثقافي' ? 'active' : '' }}">
        <i class="fas fa-monument"></i>
        <span>{{ $isEn ? 'Cultural' : 'ثقافي' }}</span>
    </a>
    <a href="{{ route('activities.index', ['type' => 'تاريخي']) }}" class="category-tab {{ request('type') == 'تاريخي' ? 'active' : '' }}">
        <i class="fas fa-landmark"></i>
        <span>{{ $isEn ? 'Historical' : 'تاريخي' }}</span>
    </a>
    <a href="{{ route('activities.index', ['type' => 'طبيعي']) }}" class="category-tab {{ request('type') == 'طبيعي' ? 'active' : '' }}">
        <i class="fas fa-mountain"></i>
        <span>{{ $isEn ? 'Nature' : 'طبيعي' }}</span>
    </a>
    <a href="{{ route('activities.index', ['type' => 'رياضي']) }}" class="category-tab {{ request('type') == 'رياضي' ? 'active' : '' }}">
        <i class="fas fa-running"></i>
        <span>{{ $isEn ? 'Sports' : 'رياضي' }}</span>
    </a>
    <a href="{{ route('activities.index', ['type' => 'ترفيهي']) }}" class="category-tab {{ request('type') == 'ترفيهي' ? 'active' : '' }}">
        <i class="fas fa-theater-masks"></i>
        <span>{{ $isEn ? 'Entertainment' : 'ترفيهي' }}</span>
    </a>
</div>

<!-- الجولات والتجارب المميزة -->
@if(isset($featuredActivities) && $featuredActivities->count() > 0)
<div class="section-wrapper">
    <div class="section-header">
        <h2>
            <i class="fas fa-star"></i>
            {{ $isEn ? 'Featured Tours & Experiences' : 'الجولات والتجارب المميزة' }}
        </h2>
        <a href="{{ route('activities.index', ['featured' => 1]) }}">
            {{ $isEn ? 'View All' : 'عرض الكل' }}
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    <div class="activities-grid">
        @foreach($featuredActivities as $activity)
            <a href="{{ route('activities.show', $activity) }}" class="activity-card">
                <div class="activity-image-wrapper">
                    @if($activity->image)
                        <img src="{{ $activity->image_url }}" alt="{{ $activity->name }}" class="activity-image">
                    @else
                        <div class="activity-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%);">
                            <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                                <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="featured-badge">
                        <i class="fas fa-star"></i> {{ $isEn ? 'Featured' : 'مميز' }}
                    </div>
                    <div class="activity-badge">{{ $activity->type }}</div>
                </div>
                <div class="activity-content">
                    <h3 class="activity-title">{{ $activity->name }}</h3>
                    <div class="activity-location">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                        <span>{{ $activity->destination->name }}, {{ $activity->location }}</span>
                    </div>
                    <p class="activity-description">{{ $activity->description }}</p>
                    <div class="activity-footer">
                        <div class="activity-rating">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <span style="font-weight: 600; margin: 0 4px;">{{ number_format($activity->rating ?? 0, 1) }}</span>
                            <span style="color: #64748b; font-size: 0.85rem;">({{ $activity->reviews->count() }})</span>
                        </div>
                        <div class="activity-price">
                            <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ number_format($activity->price, 0) }}</span>
                            <span style="color: #64748b; font-size: 0.9rem;">{{ $isEn ? 'SYP' : 'ل.س' }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- المعالم التي يجب عليك زيارتها -->
@if(isset($mustVisitActivities) && $mustVisitActivities->count() > 0)
<div class="section-wrapper">
    <div class="section-header">
        <h2>
            <i class="fas fa-map-pin"></i>
            {{ $isEn ? 'Must-Visit Landmarks' : 'المعالم التي يجب عليك زيارتها' }}
        </h2>
        <a href="{{ route('activities.index', ['must_visit' => 1]) }}">
            {{ $isEn ? 'View All' : 'عرض الكل' }}
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    <div class="activities-grid">
        @foreach($mustVisitActivities as $activity)
            <a href="{{ route('activities.show', $activity) }}" class="activity-card">
                <div class="activity-image-wrapper">
                    @if($activity->image)
                        <img src="{{ $activity->image_url }}" alt="{{ $activity->name }}" class="activity-image">
                    @else
                        <div class="activity-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%);">
                            <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                                <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="must-visit-badge">
                        <i class="fas fa-heart"></i> {{ $isEn ? 'Must Visit' : 'يجب زيارته' }}
                    </div>
                    <div class="activity-badge">{{ $activity->type }}</div>
                </div>
                <div class="activity-content">
                    <h3 class="activity-title">{{ $activity->name }}</h3>
                    <div class="activity-location">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                        <span>{{ $activity->destination->name }}, {{ $activity->location }}</span>
                    </div>
                    <p class="activity-description">{{ $activity->description }}</p>
                    <div class="activity-footer">
                        <div class="activity-rating">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <span style="font-weight: 600; margin: 0 4px;">{{ number_format($activity->rating ?? 0, 1) }}</span>
                            <span style="color: #64748b; font-size: 0.85rem;">({{ $activity->reviews->count() }})</span>
                        </div>
                        <div class="activity-price">
                            <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ number_format($activity->price, 0) }}</span>
                            <span style="color: #64748b; font-size: 0.9rem;">{{ $isEn ? 'SYP' : 'ل.س' }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

<!-- الفعاليات هذا الشهر -->
@if(isset($monthlyEvents) && $monthlyEvents->count() > 0)
<div class="section-wrapper">
    <div class="section-header">
        <h2>
            <i class="fas fa-calendar-alt"></i>
            {{ $isEn ? 'Events This Month' : 'الفعاليات هذا الشهر' }}
        </h2>
        <a href="{{ route('activities.index', ['monthly' => 1]) }}">
            {{ $isEn ? 'View All' : 'عرض الكل' }}
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    <div class="activities-grid">
        @foreach($monthlyEvents as $activity)
            <a href="{{ route('activities.show', $activity) }}" class="activity-card">
                <div class="activity-image-wrapper">
                    @if($activity->image)
                        <img src="{{ $activity->image_url }}" alt="{{ $activity->name }}" class="activity-image">
                    @else
                        <div class="activity-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%);">
                            <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                                <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="event-date-badge">
                        <i class="fas fa-calendar"></i> {{ $activity->event_date->format('d M') }}
                    </div>
                    <div class="activity-badge">{{ $activity->type }}</div>
                </div>
                <div class="activity-content">
                    <h3 class="activity-title">{{ $activity->name }}</h3>
                    <div class="activity-location">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                        <span>{{ $activity->destination->name }}, {{ $activity->location }}</span>
                    </div>
                    <p class="activity-description">{{ $activity->description }}</p>
                    <div class="activity-footer">
                        <div class="activity-rating">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <span style="font-weight: 600; margin: 0 4px;">{{ number_format($activity->rating ?? 0, 1) }}</span>
                            <span style="color: #64748b; font-size: 0.85rem;">({{ $activity->reviews->count() }})</span>
                        </div>
                        <div class="activity-price">
                            <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ number_format($activity->price, 0) }}</span>
                            <span style="color: #64748b; font-size: 0.9rem;">{{ $isEn ? 'SYP' : 'ل.س' }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

@if($activities->count() > 0)
    <div class="activities-grid">
        @foreach($activities as $activity)
            <a href="{{ route('activities.show', $activity) }}" class="activity-card">
                <div class="activity-image-wrapper">
                    @if($activity->image)
                        <img src="{{ $activity->image_url }}" alt="{{ $activity->name }}" class="activity-image">
                    @else
                        <div class="activity-image" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%);">
                            <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                                <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="activity-badge">{{ $activity->type }}</div>
                </div>
                <div class="activity-content">
                    <h3 class="activity-title">{{ $activity->name }}</h3>
                    <div class="activity-location">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                        <span>{{ $activity->destination->name }}, {{ $activity->location }}</span>
                    </div>
                    <p class="activity-description">{{ $activity->description }}</p>
                    <div class="activity-footer">
                        <div class="activity-rating">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <span style="font-weight: 600; margin: 0 4px;">{{ number_format($activity->rating ?? 0, 1) }}</span>
                            <span style="color: #64748b; font-size: 0.85rem;">({{ $activity->reviews->count() }})</span>
                        </div>
                        <div class="activity-price">
                            <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ number_format($activity->price, 0) }}</span>
                            <span style="color: #64748b; font-size: 0.9rem;">{{ $isEn ? 'SYP' : 'ل.س' }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="pagination">
        {{ $activities->appends(request()->query())->links() }}
    </div>
@else
    <div style="text-align: center; padding: 60px 20px; color: #64748b;">
        <svg width="80" height="80" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 16px; opacity: 0.5;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3>{{ $isEn ? 'No activities available right now' : 'لا توجد أنشطة متاحة حالياً' }}</h3>
        <p>{{ $isEn ? 'Try changing the search filters' : 'جرب تغيير معايير البحث' }}</p>
    </div>
@endif
@endsection

