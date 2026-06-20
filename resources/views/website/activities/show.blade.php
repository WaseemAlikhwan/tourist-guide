@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $activity->name . ' - Wander Point in Syria')

@push('head')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .hero-gallery {
        position: relative;
        height: 500px;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 3rem;
    }
    .hero-gallery img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .gallery-thumbnails {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
        overflow-x: auto;
        padding-bottom: 0.5rem;
    }
    .gallery-thumbnail {
        width: 120px;
        height: 80px;
        border-radius: 12px;
        object-fit: cover;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.2s;
    }
    .gallery-thumbnail:hover,
    .gallery-thumbnail.active {
        border-color: var(--primary);
        transform: scale(1.05);
    }
    .activity-header-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    .activity-main-info {
        flex: 1;
    }
    .activity-booking-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        position: sticky;
        top: 100px;
        height: fit-content;
    }
    .price-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 2px solid #e5e7eb;
    }
    .price-main {
        font-size: 3rem;
        font-weight: 900;
        color: var(--primary-dark);
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .price-label {
        color: #64748b;
        font-size: 0.9rem;
    }
    .booking-form-section {
        margin-bottom: 2rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #0f172a;
    }
    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.2s;
    }
    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
    }
    .btn-book {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-book:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(139, 69, 19, 0.3);
    }
    .activity-badges {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .badge-item {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .badge-featured {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: white;
    }
    .badge-must-visit {
        background: linear-gradient(135deg, #DC143C 0%, #B22222 100%);
        color: white;
    }
    .badge-type {
        background: rgba(139, 69, 19, 0.1);
        color: var(--primary-dark);
    }
    .activity-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .activity-location {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    .activity-rating-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
    }
    .rating-stars {
        display: flex;
        gap: 0.25rem;
        color: #fbbf24;
        font-size: 1.5rem;
    }
    
    /* تحسين تصميم التقييم التفاعلي */
    .rating-input {
        direction: ltr;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #fff9e6 0%, #fffbf0 100%);
        border-radius: 16px;
        border: 2px solid #fde68a;
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.15);
        transition: all 0.3s ease;
        flex-wrap: wrap;
    }
    
    .rating-input:hover {
        box-shadow: 0 6px 20px rgba(251, 191, 36, 0.25);
        border-color: #fbbf24;
        transform: translateY(-2px);
    }
    
    .rating-input input[type="radio"] {
        display: none;
    }
    
    .rating-input .stars-group {
        display: flex;
        gap: 0.4rem;
        align-items: center;
    }
    
    .rating-input .star-label {
        cursor: pointer;
        font-size: 2.2rem;
        color: #e5e7eb;
        transition: all 0.2s ease;
        line-height: 1;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        position: relative;
    }
    
    .rating-input .star-label:hover {
        color: #fbbf24 !important;
        transform: scale(1.2);
    }
    
    .rating-input .star-label.active {
        color: #fbbf24;
    }
    
    .rating-input .star-label i {
        display: block;
    }
    
    /* نص التقييم */
    .rating-label-text {
        margin-right: auto;
        font-weight: 600;
        color: #64748b;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .rating-value-display {
        font-weight: 700;
        color: #f59e0b;
        font-size: 1.1rem;
        min-width: 70px;
        text-align: center;
        padding: 0.6rem 1.2rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .rating-value-display.active {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        transform: scale(1.05);
    }
    
    @media (max-width: 768px) {
        .rating-input {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .rating-label-text {
            margin-right: 0;
        }
        
        .rating-value-display {
            align-self: stretch;
        }
        
        .rating-input .star-label {
            font-size: 1.8rem;
        }
    }
    .rating-text {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.2rem;
    }
    .rating-count {
        color: #64748b;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }
    .info-card {
        background: white;
        padding: 1.5rem;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }
    .info-card-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.1) 0%, rgba(160, 82, 45, 0.1) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        color: var(--primary);
        font-size: 1.5rem;
    }
    .info-card-title {
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #0f172a;
    }
    .info-card-text {
        color: #64748b;
        font-size: 0.9rem;
    }
    .description-section {
        margin-bottom: 3rem;
    }
    .section-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .description-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #475569;
        white-space: pre-line;
    }
    .reviews-section {
        margin-top: 3rem;
    }
    .review-item {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid #e5e7eb;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }
    .review-user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .review-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
    }
    .review-user-name {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
    }
    .review-date {
        color: #64748b;
        font-size: 0.9rem;
    }
    .favorite-btn {
        background: {{ $isFavorited ? '#ef4444' : '#f1f5f9' }};
        color: {{ $isFavorited ? '#fff' : '#475569' }};
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
        margin-bottom: 1rem;
    }
    .favorite-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    @media (max-width: 992px) {
        .activity-header-content {
            grid-template-columns: 1fr;
        }
        .activity-booking-card {
            position: static;
        }
        .activity-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Gallery -->
<div class="hero-gallery">
    @if($activity->image)
        <img id="mainImage" src="{{ $activity->image_url }}" alt="{{ $activity->name }}">
    @else
        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%); display: flex; align-items: center; justify-content: center;">
            <svg width="120" height="120" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    @endif
    @if($activity->gallery->count() > 0)
        <div class="gallery-thumbnails">
            @if($activity->image)
                <img src="{{ $activity->image_url }}" alt="{{ $activity->name }}" class="gallery-thumbnail active" onclick="changeMainImage(this.src)">
            @endif
            @foreach($activity->gallery as $galleryItem)
                <img src="{{ asset('storage/' . $galleryItem->image_path) }}" alt="{{ $galleryItem->caption }}" class="gallery-thumbnail" onclick="changeMainImage(this.src)">
            @endforeach
        </div>
    @endif
</div>

<div class="activity-header-content">
    <div class="activity-main-info">
        <div class="activity-badges">
            @if($activity->is_featured)
                <span class="badge-item badge-featured">
                    <i class="fas fa-star"></i>
                    {{ $isEn ? 'Featured' : 'مميز' }}
                </span>
            @endif
            @if($activity->is_must_visit)
                <span class="badge-item badge-must-visit">
                    <i class="fas fa-heart"></i>
                    {{ $isEn ? 'Must Visit' : 'يجب زيارته' }}
                </span>
            @endif
            <span class="badge-item badge-type">
                <i class="fas fa-tag"></i>
                {{ $activity->type }}
            </span>
            @if($activity->provider_id && $activity->provider)
                <span class="badge-item" style="background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); color: #0f172a;">
                    <i class="fas fa-certificate"></i>
                    {{ $isEn ? 'Approved Content Provider' : 'مزوّد محتوى معتمد' }}
                </span>
            @endif
        </div>

        <h1 class="activity-title">{{ $activity->name }}</h1>
        
        <div class="activity-location">
            <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
            <span>{{ $activity->location }}, {{ $activity->destination->name }}, {{ $activity->destination->country }}</span>
        </div>

        <div class="activity-rating-section">
            <div class="rating-stars">
                @for($i = 0; $i < 5; $i++)
                    <i class="fas fa-star {{ $i < floor($activity->rating ?? 0) ? '' : 'far' }}"></i>
                @endfor
            </div>
            <div>
                <span class="rating-text">{{ number_format($activity->rating ?? 0, 1) }}</span>
                <span class="rating-count">({{ $activity->reviews->count() }} {{ $isEn ? 'reviews' : 'تقييم' }})</span>
            </div>
        </div>

        <!-- معلومات إضافية -->
        <div class="info-grid">
            @if($activity->is_event && $activity->event_date && $activity->formatted_duration)
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-card-title">{{ $isEn ? 'Duration' : 'المدة' }}</div>
                <div class="info-card-text">{{ $activity->formatted_duration }}</div>
            </div>
            @endif
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-language"></i>
                </div>
                <div class="info-card-title">{{ $isEn ? 'Language' : 'اللغة' }}</div>
                <div class="info-card-text">{{ $isEn ? 'Arabic and English' : 'العربية والإنجليزية' }}</div>
            </div>
            @if($activity->event_date)
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="info-card-title">{{ $isEn ? 'Event Date' : 'تاريخ الفعالية' }}</div>
                <div class="info-card-text">{{ $activity->event_date->format('d M Y') }}</div>
            </div>
            @endif
        </div>

        <!-- الوصف -->
        <div class="description-section">
            <h2 class="section-title">
                <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                {{ $isEn ? 'About This Activity' : 'عن هذا النشاط' }}
            </h2>
            <div class="description-text">{{ $activity->description ?? ($isEn ? 'No description available right now.' : 'لا يوجد وصف متاح حالياً.') }}</div>
        </div>

        <!-- المعالم المميزة -->
        @if($activity->highlights)
        <div class="description-section">
            <h2 class="section-title">
                <i class="fas fa-star" style="color: #fbbf24;"></i>
                {{ $isEn ? 'Highlights' : 'المعالم المميزة' }}
            </h2>
            <ul class="highlights-list" style="list-style: none; padding: 0;">
                @foreach(explode("\n", $activity->highlights) as $highlight)
                    @if(trim($highlight))
                        <li style="padding: 0.75rem 0; padding-right: 2rem; position: relative; border-bottom: 1px solid #e5e7eb;">
                            <i class="fas fa-check-circle" style="position: absolute; right: 0; top: 0.75rem; color: var(--primary);"></i>
                            <span style="color: #475569;">{{ trim($highlight) }}</span>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif

        <!-- تفاصيل الحجز (فقط إذا كان يتطلب حجز) -->
        @if($activity->requires_booking ?? true)
        <div class="description-section">
            <h2 class="section-title">
                <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                {{ $isEn ? 'Booking Details' : 'تفاصيل الحجز' }}
            </h2>

            @if($activity->whats_included)
            <div style="margin-bottom: 2rem;">
                <h4 style="color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-check-circle" style="color: #10b981;"></i>
                    {{ $isEn ? "What's Included" : 'ما يشمله العرض' }}
                </h4>
                <ul style="list-style: none; padding: 0; background: #f0fdf4; border-radius: 12px; padding: 1.5rem;">
                    @foreach(explode("\n", $activity->whats_included) as $item)
                        @if(trim($item))
                            <li style="padding: 0.5rem 0; padding-right: 2rem; position: relative; color: #475569;">
                                <i class="fas fa-check" style="position: absolute; right: 0; top: 0.5rem; color: #10b981;"></i>
                                <span>{{ trim($item) }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if($activity->whats_not_included)
            <div style="margin-bottom: 2rem;">
                <h4 style="color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-times-circle" style="color: #ef4444;"></i>
                    {{ $isEn ? "What's Not Included" : 'ما لا يشمله العرض' }}
                </h4>
                <ul style="list-style: none; padding: 0; background: #fef2f2; border-radius: 12px; padding: 1.5rem;">
                    @foreach(explode("\n", $activity->whats_not_included) as $item)
                        @if(trim($item))
                            <li style="padding: 0.5rem 0; padding-right: 2rem; position: relative; color: #475569;">
                                <i class="fas fa-times" style="position: absolute; right: 0; top: 0.5rem; color: #ef4444;"></i>
                                <span>{{ trim($item) }}</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if($activity->additional_info)
            <div style="margin-bottom: 2rem;">
                <h4 style="color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                    {{ $isEn ? 'Additional Information' : 'معلومات إضافية' }}
                </h4>
                <div style="background: #f0f9ff; border-radius: 12px; padding: 1.5rem; color: #475569; line-height: 1.8; white-space: pre-line;">
                    {{ $activity->additional_info }}
                </div>
            </div>
            @endif

            @if($activity->payment_policy)
            <div style="margin-bottom: 2rem;">
                <h4 style="color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-credit-card" style="color: var(--primary);"></i>
                    {{ $isEn ? 'Payment Policy' : 'سياسة الدفع' }}
                </h4>
                <div style="background: #f8fafc; border-right: 4px solid var(--primary); border-radius: 12px; padding: 1.5rem; color: #475569; line-height: 1.8; white-space: pre-line;">
                    {{ $activity->payment_policy }}
                </div>
            </div>
            @endif

            @if($activity->cancellation_policy)
            <div style="margin-bottom: 2rem;">
                <h4 style="color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-ban" style="color: #ef4444;"></i>
                    {{ $isEn ? 'Cancellation Policy' : 'سياسة الإلغاء' }}
                </h4>
                <div style="background: #fef2f2; border-right: 4px solid #ef4444; border-radius: 12px; padding: 1.5rem; color: #475569; line-height: 1.8; white-space: pre-line;">
                    {{ $activity->cancellation_policy }}
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- الأقسام المخصصة -->
        @php
            $customSections = is_array($activity->custom_sections) ? $activity->custom_sections : (is_string($activity->custom_sections) ? json_decode($activity->custom_sections, true) : []);
        @endphp
        @if(!empty($customSections) && is_array($customSections) && count($customSections) > 0)
        <div class="description-section">
            @foreach($customSections as $section)
                @if(isset($section['title']) && isset($section['content']) && !empty(trim($section['title'])) && !empty(trim($section['content'])))
                <div style="margin-bottom: 2.5rem;">
                    <h2 class="section-title">
                        <i class="fas fa-file-alt" style="color: var(--primary);"></i>
                        {{ trim($section['title']) }}
                    </h2>
                    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; border-right: 4px solid var(--primary);">
                        <div style="color: #475569; line-height: 1.8; white-space: pre-line; font-size: 1.05rem;">
                            {{ trim($section['content']) }}
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        @endif

        <!-- الفنادق القريبة -->
        @if($activity->destination && $activity->destination->activeHotels && $activity->destination->activeHotels->count() > 0)
        <div class="description-section">
            <h2 class="section-title">
                <i class="fas fa-hotel" style="color: var(--primary);"></i>
                {{ $isEn ? 'Nearby Hotels' : 'الفنادق القريبة' }} ({{ $activity->destination->activeHotels->count() }})
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                @foreach($activity->destination->activeHotels as $hotel)
                    <a href="{{ route('hotels.show', $hotel) }}" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e5e7eb; transition: all 0.3s; cursor: pointer; text-decoration: none; color: inherit; display: block;">
                        @if($hotel->image)
                            <img src="{{ asset('storage/' . $hotel->image) }}" alt="{{ $hotel->name }}" style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 200px; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-hotel" style="font-size: 3rem; color: #94a3b8;"></i>
                            </div>
                        @endif
                        <div style="padding: 1.5rem;">
                            <div style="margin-bottom: 0.75rem;">
                                <span style="background: #fbbf24; color: #78350f; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.875rem; font-weight: 600;">
                                    {!! str_repeat('⭐', $hotel->star_rating) !!}
                                </span>
                            </div>
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">{{ $hotel->name }}</h3>
                            @if($hotel->description)
                                <p style="color: #64748b; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1rem;">{{ Str::limit($hotel->description, 80) }}</p>
                            @endif
                            <div style="margin-bottom: 1rem;">
                                @if($hotel->price_per_night)
                                    <div style="font-size: 1.1rem; font-weight: 700; color: var(--primary-dark);">
                                        {{ number_format($hotel->price_per_night, 0) }} {{ $isEn ? 'SYP / night' : 'ل.س / ليلة' }}
                                    </div>
                                @else
                                    <div style="font-size: 0.9rem; color: #64748b;">
                                        {{ $isEn ? 'Call for inquiry' : 'اتصل للاستعلام' }}
                                    </div>
                                @endif
                            </div>
                            @if($hotel->address)
                                <div style="margin-bottom: 0.75rem; font-size: 0.875rem; color: #64748b; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-map-marker-alt" style="font-size: 0.75rem;"></i>
                                    <span>{{ Str::limit($hotel->address, 50) }}</span>
                                </div>
                            @endif
                            @php
                                $distance = $hotel->distanceFromDestination($activity->destination);
                            @endphp
                            @if($distance !== null)
                                <div style="margin-bottom: 0.75rem; font-size: 0.875rem; color: #8B4513; font-weight: 600;">
                                    <i class="fas fa-route"></i> {{ $isEn ? $distance . ' km away from destination' : 'على بعد ' . $distance . ' كم من الوجهة' }}
                                </div>
                            @endif
                            @if($hotel->amenities && count($hotel->amenities) > 0)
                                <div style="margin-bottom: 1rem; display: flex; flex-wrap: gap: 0.5rem;">
                                    @foreach(array_slice($hotel->amenities, 0, 2) as $amenity)
                                        <span style="font-size: 0.75rem; background: #f1f5f9; color: #475569; padding: 0.25rem 0.5rem; border-radius: 6px;">
                                            {{ $amenity }}
                                        </span>
                                    @endforeach
                                    @if(count($hotel->amenities) > 2)
                                        <span style="font-size: 0.75rem; color: #64748b;">
                                            {{ $isEn ? '+' . (count($hotel->amenities) - 2) . ' more' : '+' . (count($hotel->amenities) - 2) . ' أكثر' }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                            <div style="padding-top: 1rem; border-top: 1px solid #e5e7eb; text-align: center;">
                                <span style="color: var(--primary); font-weight: 600; font-size: 0.9rem;">
                                    {{ $isEn ? 'View Details' : 'عرض التفاصيل' }} <i class="fas fa-arrow-left"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- التقييمات (تقييم واحد فقط) -->
        <div class="reviews-section">
            <h2 class="section-title">
                <i class="fas fa-star" style="color: #fbbf24;"></i>
                {{ $isEn ? 'Ratings' : 'التقييمات' }} ({{ $activity->approvedReviews->count() }})
            </h2>
            
            @auth
                @if($userReview)
                    <div class="review-item">
                        <h4 style="margin-bottom: 1.5rem; color: #0f172a; font-weight: 700;">{{ $isEn ? 'Update Your Rating' : 'تحديث تقييمك' }}</h4>
                        <form method="POST" action="{{ route('reviews.update', $userReview) }}" id="update-rating-form">
                            @csrf
                            @method('PATCH')
                            <div class="rating-input" id="rating-container-update">
                                <span class="rating-label-text">
                                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                                    {{ $isEn ? 'Your rating:' : 'تقييمك:' }}
                                </span>
                                <div class="stars-group" style="display: flex; gap: 0.3rem;">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" id="rating-update-{{ $i }}" value="{{ $i }}" {{ old('rating', $userReview->rating) == $i ? 'checked' : '' }} required>
                                        <label for="rating-update-{{ $i }}" class="star-label" data-rating="{{ $i }}">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <span class="rating-value-display" id="rating-display-update">
                                    {{ old('rating', $userReview->rating) }}/5
                                </span>
                            </div>
                            <div style="display: flex; gap: 12px; margin-top: 1rem;">
                                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 600;">
                                    <i class="fas fa-check"></i> {{ $isEn ? 'Update Rating' : 'تحديث التقييم' }}
                                </button>
                                <form method="POST" action="{{ route('reviews.destroy', $userReview) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('{{ $isEn ? 'Are you sure you want to delete this rating?' : 'هل أنت متأكد من حذف التقييم؟' }}')" style="padding: 0.75rem 1.5rem;">
                                        <i class="fas fa-trash"></i> {{ $isEn ? 'Delete' : 'حذف' }}
                                    </button>
                                </form>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="review-item">
                        <h4 style="margin-bottom: 1.5rem; color: #0f172a; font-weight: 700;">{{ $isEn ? 'Add Your Rating' : 'أضف تقييمك' }}</h4>
                        <form method="POST" action="{{ route('reviews.store', $activity) }}" id="add-rating-form">
                            @csrf
                            <div class="rating-input" id="rating-container">
                                <span class="rating-label-text">
                                    <i class="fas fa-star" style="color: #fbbf24;"></i>
                                    {{ $isEn ? 'Choose your rating:' : 'اختر تقييمك:' }}
                                </span>
                                <div class="stars-group" style="display: flex; gap: 0.3rem;">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" id="rating-{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }} required>
                                        <label for="rating-{{ $i }}" class="star-label" data-rating="{{ $i }}">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                                <span class="rating-value-display" id="rating-display">0/5</span>
                            </div>
                            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-weight: 600; margin-top: 1rem;">
                                <i class="fas fa-star"></i> {{ $isEn ? 'Submit Rating' : 'إضافة التقييم' }}
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="review-item" style="background: #f0f9ff; border-color: #bae6fd;">
                    <p style="margin: 0; color: #64748b;">
                        <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">{{ $isEn ? 'Log in' : 'سجّل دخولك' }}</a> {{ $isEn ? 'to add a rating' : 'لإضافة تقييم' }}
                    </p>
                </div>
            @endauth

            @if($activity->approvedReviews->count() > 0)
                @foreach($activity->approvedReviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="review-user-info">
                                <div class="review-avatar">
                                    {{ mb_substr($review->user ? $review->user->name : ($isEn ? 'D' : 'م'), 0, 1) }}
                                </div>
                                <div>
                                    <div class="review-user-name">{{ $review->user ? $review->user->name : ($isEn ? 'Deleted user' : 'مستخدم محذوف') }}</div>
                                    <div class="review-date">{{ $review->created_at ? $review->created_at->diffForHumans() : ($isEn ? 'Date unavailable' : 'تاريخ غير متاح') }}</div>
                                </div>
                            </div>
                            <div class="rating-stars" style="font-size: 1rem;">
                                @for($i = 0; $i < ($review->rating ?? 0); $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                    <p>{{ $isEn ? 'No ratings yet. Be the first to rate this activity!' : 'لا توجد تقييمات بعد. كن أول من يقيّم هذا النشاط!' }}</p>
                </div>
            @endif
        </div>

        <!-- التعليقات (متعددة) -->
        <div class="reviews-section" style="margin-top: 2rem;">
            <h2 class="section-title">
                <i class="fas fa-comments" style="color: #3b82f6;"></i>
                {{ $isEn ? 'Comments' : 'التعليقات' }} ({{ $activity->approvedComments->count() }})
            </h2>
            
            @auth
                <div class="review-item">
                    <h4 style="margin-bottom: 1rem;">{{ $isEn ? 'Add Your Comment' : 'أضف تعليقك' }}</h4>
                    <form method="POST" action="{{ route('comments.store', $activity) }}">
                        @csrf
                        <textarea name="comment" rows="4" style="width:100%; padding:12px; border:2px solid #e5e7eb; border-radius:12px; font-family:inherit; margin-bottom:1rem;" required placeholder="{{ $isEn ? 'Write your comment here...' : 'اكتب تعليقك هنا...' }}">{{ old('comment') }}</textarea>
                        <button type="submit" class="btn btn-primary">{{ $isEn ? 'Add Comment' : 'إضافة التعليق' }}</button>
                    </form>
                </div>
            @else
                <div class="review-item" style="background: #f0f9ff; border-color: #bae6fd;">
                    <p style="margin: 0; color: #64748b;">
                        <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600;">{{ $isEn ? 'Log in' : 'سجّل دخولك' }}</a> {{ $isEn ? 'to add a comment' : 'لإضافة تعليق' }}
                    </p>
                </div>
            @endauth

            @if($activity->approvedComments->count() > 0)
                @foreach($activity->approvedComments as $comment)
                    <div class="review-item">
                        <div class="review-header">
                            <div class="review-user-info">
                                <div class="review-avatar">
                                    {{ mb_substr($comment->user ? $comment->user->name : ($isEn ? 'D' : 'م'), 0, 1) }}
                                </div>
                                <div>
                                    <div class="review-user-name">{{ $comment->user ? $comment->user->name : ($isEn ? 'Deleted user' : 'مستخدم محذوف') }}</div>
                                    <div class="review-date">{{ $comment->created_at ? $comment->created_at->diffForHumans() : ($isEn ? 'Date unavailable' : 'تاريخ غير متاح') }}</div>
                                </div>
                            </div>
                            @auth
                                @if(auth()->id() === $comment->user_id)
                                    <div style="display: flex; gap: 8px;">
                                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display: inline;" onsubmit="return confirm('{{ $isEn ? 'Are you sure you want to delete this comment?' : 'هل أنت متأكد من حذف التعليق؟' }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                        <div style="color: #475569; line-height: 1.7; margin-top: 0.5rem;">{{ $comment->comment }}</div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 40px 20px; color: #64748b;">
                    <p>{{ $isEn ? 'No comments yet. Be the first to comment on this activity!' : 'لا توجد تعليقات بعد. كن أول من يعلق على هذا النشاط!' }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- بطاقة الحجز -->
    <div class="activity-booking-card">
        @if(($activity->requires_booking ?? true) && $activity->price)
        <div class="price-section">
            <div class="price-main">{{ number_format($activity->price, 0) }}</div>
            <div class="price-label">{{ $isEn ? 'SYP per person' : 'ل.س للشخص الواحد' }}</div>
        </div>
        @endif

        @auth
            <button id="favorite-btn" class="favorite-btn" data-type="activity" data-id="{{ $activity->id }}">
                <i class="fas fa-heart"></i>
                <span id="favorite-text">{{ $isEn ? ($isFavorited ? 'In Favorites' : 'Add to Favorites') : ($isFavorited ? 'في المفضلة' : 'إضافة للمفضلة') }}</span>
            </button>

            @if($activity->requires_booking ?? true)
                <div class="booking-form-section">
                    <form action="{{ route('bookings.create', $activity) }}" method="GET">
                        @if($activity->is_event && $activity->event_date)
                            <div class="form-group">
                                <label for="date">{{ $isEn ? 'Event Date' : 'تاريخ الفعالية' }}</label>
                                <input type="date" 
                                       id="date" 
                                       name="date" 
                                       value="{{ $activity->event_date->format('Y-m-d') }}" 
                                       readonly 
                                       disabled
                                       style="background-color: #f1f5f9; cursor: not-allowed; opacity: 0.7;">
                                <input type="hidden" name="date" value="{{ $activity->event_date->format('Y-m-d') }}">
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-info-circle"></i> {{ $isEn ? 'Fixed event date' : 'تاريخ ثابت للفعالية' }}
                                </small>
                            </div>
                        @else
                            <div class="form-group">
                                <label for="date">{{ $isEn ? 'Booking Date' : 'تاريخ الحجز' }}</label>
                                <input type="date" id="date" name="date" required min="{{ date('Y-m-d') }}">
                            </div>
                        @endif
                        <div class="form-group">
                            <label for="guests">{{ $isEn ? 'Number of Guests' : 'عدد الأشخاص' }}</label>
                            <select id="guests" name="guests" required>
                                <option value="1">{{ $isEn ? '1 person' : '1 شخص' }}</option>
                                <option value="2">{{ $isEn ? '2 people' : '2 أشخاص' }}</option>
                                <option value="3">{{ $isEn ? '3 people' : '3 أشخاص' }}</option>
                                <option value="4">{{ $isEn ? '4 people' : '4 أشخاص' }}</option>
                                <option value="5">{{ $isEn ? '5+ people' : '5+ أشخاص' }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-book">
                            <i class="fas fa-calendar-check"></i>
                            {{ $isEn ? 'Book Now' : 'احجز الآن' }}
                        </button>
                    </form>
                </div>
            @else
                <div class="booking-form-section" style="text-align: center; padding: 2rem 0;">
                    <div style="background: linear-gradient(135deg, rgba(34, 139, 34, 0.1) 0%, rgba(0, 128, 0, 0.1) 100%); border-radius: 16px; padding: 2rem; margin-bottom: 1.5rem;">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #228B22; margin-bottom: 1rem;"></i>
                        <h4 style="color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'Direct Visit' : 'زيارة مباشرة' }}</h4>
                        <p style="color: #64748b; margin: 0;">{{ $isEn ? 'This activity does not require prior booking' : 'هذا النشاط لا يتطلب حجز مسبق' }}</p>
                    </div>
                    @php
                        $lat = $activity->destination->latitude ?? null;
                        $lng = $activity->destination->longitude ?? null;
                    @endphp
                    @if($lat && $lng)
                        <a href="https://maps.google.com/?q={{ $lat }},{{ $lng }}" target="_blank" class="btn-book" style="text-decoration: none; background: linear-gradient(135deg, #228B22 0%, #006400 100%);">
                            <i class="fas fa-map-marked-alt"></i>
                            {{ $isEn ? 'View on Map' : 'عرض على الخريطة' }}
                        </a>
                    @else
                        <div style="background: #f8fafc; border-radius: 12px; padding: 1.5rem; text-align: center;">
                            <i class="fas fa-info-circle" style="color: #64748b; font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <p style="color: #64748b; margin: 0; font-size: 0.9rem;">{{ $isEn ? 'Location is currently unavailable' : 'الموقع غير متاح حالياً' }}</p>
                        </div>
                    @endif
                </div>
            @endif
        @else
            @if($activity->requires_booking ?? true)
                <a href="{{ route('login') }}" class="btn-book" style="text-decoration: none;">
                    <i class="fas fa-sign-in-alt"></i>
                    {{ $isEn ? 'Log in to Book' : 'سجّل دخول للحجز' }}
                </a>
            @else
                <div style="text-align: center; padding: 2rem 0;">
                    <div style="background: linear-gradient(135deg, rgba(34, 139, 34, 0.1) 0%, rgba(0, 128, 0, 0.1) 100%); border-radius: 16px; padding: 2rem; margin-bottom: 1.5rem;">
                        <i class="fas fa-check-circle" style="font-size: 3rem; color: #228B22; margin-bottom: 1rem;"></i>
                        <h4 style="color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'Direct Visit' : 'زيارة مباشرة' }}</h4>
                        <p style="color: #64748b; margin: 0;">{{ $isEn ? 'This activity does not require prior booking' : 'هذا النشاط لا يتطلب حجز مسبق' }}</p>
                    </div>
                    @php
                        $lat = $activity->destination->latitude ?? null;
                        $lng = $activity->destination->longitude ?? null;
                    @endphp
                    @if($lat && $lng)
                        <a href="https://maps.google.com/?q={{ $lat }},{{ $lng }}" target="_blank" class="btn-book" style="text-decoration: none; background: linear-gradient(135deg, #228B22 0%, #006400 100%);">
                            <i class="fas fa-map-marked-alt"></i>
                            {{ $isEn ? 'View on Map' : 'عرض على الخريطة' }}
                        </a>
                    @else
                        <div style="background: #f8fafc; border-radius: 12px; padding: 1.5rem; text-align: center;">
                            <i class="fas fa-info-circle" style="color: #64748b; font-size: 2rem; margin-bottom: 0.5rem;"></i>
                            <p style="color: #64748b; margin: 0; font-size: 0.9rem;">{{ $isEn ? 'Location is currently unavailable' : 'الموقع غير متاح حالياً' }}</p>
                        </div>
                    @endif
                </div>
            @endif
        @endauth

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e5e7eb;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <i class="fas fa-shield-alt" style="color: var(--primary); font-size: 1.5rem;"></i>
                <div>
                    <div style="font-weight: 700; color: #0f172a;">{{ $isEn ? 'Secure Booking' : 'حجز آمن' }}</div>
                    <div style="color: #64748b; font-size: 0.9rem;">{{ $isEn ? 'Money-back guarantee' : 'ضمان استرداد الأموال' }}</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-headset" style="color: var(--primary); font-size: 1.5rem;"></i>
                <div>
                    <div style="font-weight: 700; color: #0f172a;">{{ $isEn ? '24/7 Support' : 'دعم 24/7' }}</div>
                    <div style="color: #64748b; font-size: 0.9rem;">{{ $isEn ? 'We are here to help you' : 'نحن هنا لمساعدتك' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- قسم الأنشطة المقترحة --}}
@if(isset($relatedActivities) && $relatedActivities->count() > 0)
<div style="margin-top: 4rem; margin-bottom: 2rem;">
    <h2 class="section-title" style="font-size: 2rem; font-weight: 700; color: #0f172a; margin-bottom: 2rem; display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-heart" style="color: var(--primary);"></i>
        {{ $isEn ? 'Activities or Events You May Like' : 'أنشطة أو فعاليات قد تعجبك' }}
    </h2>
    
    <div class="activities-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
        @foreach($relatedActivities as $relatedActivity)
            <a href="{{ route('activities.show', $relatedActivity) }}" class="activity-card" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden; transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; text-decoration: none; color: inherit;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.15)'; this.style.borderColor='var(--primary)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'; this.style.borderColor='#e5e7eb'">
                <div class="activity-image-wrapper" style="position: relative; overflow: hidden; height: 250px;">
                    @if($relatedActivity->image)
                        <img src="{{ $relatedActivity->image_url }}" alt="{{ $relatedActivity->name }}" class="activity-image" style="width: 100%; height: 100%; object-fit: cover; background: #e5e7eb; transition: transform 0.5s ease;">
                    @else
                        <div class="activity-image" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%);">
                            <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                                <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="activity-badge" style="position: absolute; top: 1rem; right: 1rem; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; color: var(--primary-dark); box-shadow: 0 2px 8px rgba(0,0,0,0.1);">{{ $relatedActivity->type }}</div>
                    @if($relatedActivity->is_featured)
                        <div style="position: absolute; top: 1rem; left: 1rem; background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; padding: 0.5rem 1rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                            <i class="fas fa-star"></i> {{ $isEn ? 'Featured' : 'مميز' }}
                        </div>
                    @endif
                </div>
                <div class="activity-content" style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                    <h3 class="activity-title" style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin-bottom: 0.75rem; line-height: 1.4;">{{ $relatedActivity->name }}</h3>
                    <div class="activity-location" style="display: flex; align-items: center; gap: 0.5rem; color: #64748b; font-size: 0.9rem; margin-bottom: 0.75rem;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                        <span>{{ $relatedActivity->destination->name }}, {{ $relatedActivity->location }}</span>
                    </div>
                    <p class="activity-description" style="color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem; flex: 1; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">{{ \Illuminate\Support\Str::limit(strip_tags($relatedActivity->description ?? ''), 120) }}</p>
                    <div class="activity-footer" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #e5e7eb; margin-top: auto;">
                        <div class="activity-rating" style="display: flex; align-items: center; gap: 0.25rem;">
                            <i class="fas fa-star" style="color: #fbbf24;"></i>
                            <span style="font-weight: 600; margin: 0 4px;">{{ number_format($relatedActivity->rating ?? 0, 1) }}</span>
                            <span style="color: #64748b; font-size: 0.85rem;">({{ $relatedActivity->reviews->count() }})</span>
                        </div>
                        <div class="activity-price" style="text-align: left;">
                            <span style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ number_format($relatedActivity->price, 0) }}</span>
                            <span style="color: #64748b; font-size: 0.9rem;">{{ $isEn ? 'SYP' : 'ل.س' }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    document.querySelectorAll('.gallery-thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    event.target.classList.add('active');
}

document.addEventListener('DOMContentLoaded', function() {
    const favoriteBtn = document.getElementById('favorite-btn');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = this.dataset.type;
            const id = this.dataset.id;
            
            fetch('{{ route("favorites.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    favoritable_type: type,
                    favoritable_id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const isFavorited = data.isFavorited;
                    favoriteBtn.style.background = isFavorited ? '#ef4444' : '#f1f5f9';
                    favoriteBtn.style.color = isFavorited ? '#fff' : '#475569';
                    document.getElementById('favorite-text').textContent = isFavorited
                        ? @json($isEn ? 'In Favorites' : 'في المفضلة')
                        : @json($isEn ? 'Add to Favorites' : 'إضافة للمفضلة');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }

    // تحسين التقييم التفاعلي
    function initRating(containerId, displayId) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        const display = document.getElementById(displayId);
        const labels = container.querySelectorAll('.star-label');
        const inputs = container.querySelectorAll('input[type="radio"]');
        
        // تحديث العرض عند التحديد
        function updateRating(value) {
            if (display) {
                display.textContent = value + '/5';
                display.classList.add('active');
                setTimeout(() => display.classList.remove('active'), 300);
            }
            
            // تحديث حالة النجوم
            labels.forEach((label) => {
                const ratingValue = parseInt(label.getAttribute('data-rating'));
                if (ratingValue <= value) {
                    label.classList.add('active');
                } else {
                    label.classList.remove('active');
                }
            });
        }
        
        // إضافة مستمعين للأحداث
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                updateRating(this.value);
            });
            
            // تحديث عند التحميل إذا كان محدد
            if (input.checked) {
                updateRating(input.value);
            }
        });
        
        // تأثير hover على النجوم
        labels.forEach((label) => {
            label.addEventListener('mouseenter', function() {
                const ratingValue = parseInt(this.getAttribute('data-rating'));
                labels.forEach((l) => {
                    const lValue = parseInt(l.getAttribute('data-rating'));
                    if (lValue <= ratingValue) {
                        l.style.color = '#fbbf24';
                    } else {
                        l.style.color = '#e5e7eb';
                    }
                });
            });
        });
        
        container.addEventListener('mouseleave', function() {
            // استعادة الحالة المحددة
            const checkedInput = container.querySelector('input[type="radio"]:checked');
            if (checkedInput) {
                updateRating(checkedInput.value);
            } else {
                labels.forEach(label => {
                    label.style.color = '#e5e7eb';
                });
                if (display) {
                    display.textContent = '0/5';
                    display.classList.remove('active');
                }
            }
        });
    }
    
    // تهيئة كلا التقييمين
    initRating('rating-container', 'rating-display');
    initRating('rating-container-update', 'rating-display-update');
});
</script>
@endpush
