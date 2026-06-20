@extends('website.layouts.app')

@section('title', __('website.home.title'))

@push('head')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    :root {
        --home-sand: #f8fafc;
        --home-sand-strong: #f1f5f9;
        --home-emerald: #0ea5a4;
        --home-emerald-dark: #0f766e;
        --home-gold: #f59e0b;
        --home-gold-dark: #d97706;
    }

    /* Hero Section - Visit Saudi Style */
    .hero-section {
        position: relative;
        height: 90vh;
        min-height: 600px;
        overflow: hidden;
        margin: -80px 0 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(15, 118, 110, 0.82) 0%, rgba(13, 148, 136, 0.72) 52%, rgba(245, 158, 11, 0.64) 100%),
                    url('https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1920&q=80') center/cover no-repeat;
        animation: zoomIn 20s ease-in-out infinite alternate;
    }

    @keyframes zoomIn {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }

    .hero-content {
        position: relative;
        z-index: 10;
        text-align: center;
        color: white;
        padding: 2rem;
        max-width: 760px;
        margin: 0 auto;
    }

    .hero-content h1 {
        font-size: 4.5rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        text-shadow: 3px 3px 15px rgba(0,0,0,0.5);
        line-height: 1.12;
        animation: fadeInUp 1s ease-out;
    }

    .hero-content p {
        font-size: 1.35rem;
        margin-bottom: 2.5rem;
        opacity: 0.95;
        line-height: 1.8;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
        animation: fadeInUp 1s ease-out 0.2s both;
    }

    .hero-cta {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        animation: fadeInUp 1s ease-out 0.4s both;
    }

    .hero-btn {
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 50px;
        transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease, color 0.25s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }

    .hero-btn-primary {
        background: white;
        color: var(--home-emerald-dark);
    }

    .hero-btn-primary:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.4);
        color: var(--home-emerald);
    }

    .hero-btn-secondary {
        background: rgba(255,255,255,0.2);
        color: white;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
    }

    .hero-btn-secondary:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-5px);
        color: white;
    }

    .hero-btn-provider {
        background: linear-gradient(135deg, var(--home-gold) 0%, var(--home-gold-dark) 100%);
        color: #1e293b;
        border: 2px solid rgba(255,255,255,0.4);
    }

    .hero-btn-provider:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.35);
        color: #0f172a;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Sections */
    .section {
        padding: 4.5rem 0;
    }

    .section-title {
        text-align: center;
        font-size: clamp(2rem, 4vw, 2.8rem);
        font-weight: 900;
        margin-bottom: 0.9rem;
        color: #0f172a;
        line-height: 1.2;
    }

    .section-subtitle {
        text-align: center;
        font-size: 1.08rem;
        color: #64748b;
        margin-bottom: 2.5rem;
        max-width: 760px;
        line-height: 1.85;
        margin-left: auto;
        margin-right: auto;
    }

    /* Destinations Grid - Visit Saudi Style */
    .destinations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .destination-card {
        position: relative;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 32px rgba(15, 23, 42, 0.12);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background: white;
        min-height: 440px;
        display: flex;
        flex-direction: column;
    }

    .destination-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 48px rgba(15, 23, 42, 0.18);
    }

    .destination-image-wrapper {
        position: relative;
        aspect-ratio: 4 / 3;
        min-height: 250px;
        overflow: hidden;
    }

    .destination-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .destination-card:hover .destination-image-wrapper img {
        transform: scale(1.1);
    }

    .destination-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(2, 6, 23, 0.82) 0%, transparent 100%);
        padding: 2rem;
        color: white;
    }

    .destination-title {
        font-size: 1.75rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        color: white;
    }

    .destination-location {
        font-size: 1rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .destination-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .destination-description {
        color: #475569;
        font-size: 0.95rem;
        line-height: 1.7;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .destination-stats {
        display: flex;
        gap: 1.5rem;
        color: #475569;
        font-size: 0.9rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .destination-stats span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Activities Swiper */
    .activities-swiper {
        padding: 2rem 0 4rem 0;
    }

    .activity-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 28px rgba(15, 23, 42, 0.11);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .activity-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 40px rgba(15, 23, 42, 0.16);
    }

    .activity-image {
        width: 100%;
        aspect-ratio: 16 / 10;
        min-height: 220px;
        object-fit: cover;
    }

    .activity-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .activity-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #0f172a;
    }

    .activity-location {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .activity-description {
        color: #475569;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .activity-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }

    .activity-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #f59e0b;
        font-weight: 700;
    }

    .activity-price {
        font-size: 1.25rem;
        font-weight: 900;
        color: var(--home-gold-dark);
    }

    /* Features Section */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .feature-card {
        text-align: center;
        padding: 2.5rem 1.5rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 5px 24px rgba(15, 23, 42, 0.08);
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.14);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, var(--home-emerald) 0%, var(--home-gold) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
    }

    .feature-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: #0f172a;
    }

    .feature-description {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Testimonials Section */
    .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1.5rem;
        margin-top: 2.5rem;
    }

    .testimonial-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.09);
        border: 1px solid #e2e8f0;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.14);
    }

    .testimonial-header {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        margin-bottom: 1rem;
    }

    .testimonial-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--home-emerald) 0%, var(--home-gold) 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .testimonial-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .testimonial-meta {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0.15rem 0 0;
    }

    .testimonial-rating {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        margin-bottom: 0.8rem;
    }

    .testimonial-rating i {
        color: #cbd5e1;
        font-size: 0.9rem;
    }

    .testimonial-rating i.active {
        color: #f59e0b;
    }

    .testimonial-text {
        color: #334155;
        font-size: 0.94rem;
        line-height: 1.75;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 79px;
    }

    .testimonial-link {
        color: var(--home-emerald-dark);
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .testimonial-link:hover {
        color: var(--home-emerald);
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, var(--home-emerald-dark) 0%, var(--home-emerald) 54%, var(--home-gold-dark) 100%);
        border-radius: 30px;
        padding: 4rem 2rem;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .cta-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .cta-content {
        position: relative;
        z-index: 10;
    }

    .cta-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1rem;
    }

    .cta-description {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.95;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cta-btn {
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: 700;
        border-radius: 50px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .cta-btn-light {
        background: white;
        color: var(--home-emerald-dark);
    }

    .cta-btn-light:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .cta-btn-outline {
        background: transparent;
        color: white;
        border: 2px solid white;
    }

    .cta-btn-outline:hover {
        background: white;
        color: var(--home-emerald-dark);
        transform: translateY(-3px);
    }

    /* Map Section */
    .map-section {
        margin: 3rem 0;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 10px 36px rgba(15, 23, 42, 0.13);
    }

    #map {
        height: 500px;
        width: 100%;
        border-radius: 0;
    }

    .map-controls {
        background: white;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .location-status {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-size: 0.9rem;
    }

    .location-status.success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .location-status.error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .location-status.info {
        background: #dbeafe;
        color: #1e40af;
        border: 1px solid #93c5fd;
    }

    /* Buttons */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--home-emerald) 0%, var(--home-emerald-dark) 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        transition: all 0.3s ease;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-custom:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(15, 118, 110, 0.35);
        color: white;
    }

    .hero-btn:focus-visible,
    .destination-card:focus-visible,
    .activity-card:focus-visible,
    .btn-primary-custom:focus-visible,
    .cta-btn:focus-visible {
        outline: none;
        box-shadow: 0 0 0 0.22rem rgba(14, 165, 164, 0.28);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-content p {
            font-size: 1.2rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .destinations-grid {
            grid-template-columns: 1fr;
        }

        .destination-card {
            height: auto;
        }

        .cta-title {
            font-size: 2rem;
        }

        .testimonials-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
        .testimonials-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #64748b;
    }

    .empty-state svg {
        width: 100px;
        height: 100px;
        margin-bottom: 1.5rem;
        opacity: 0.5;
    }
</style>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-section">
    <div class="hero-background"></div>
    <div class="hero-content">
        <h1>{{ __('website.home.hero_title') }}</h1>
        <p>{{ __('website.home.hero_subtitle') }}</p>
        <div class="hero-cta">
            <a href="{{ route('destinations.index') }}" class="hero-btn hero-btn-primary">
                <i class="fas fa-compass"></i>
                {{ __('website.home.explore_destinations') }}
            </a>
            <a href="{{ route('activities.index') }}" class="hero-btn hero-btn-secondary">
                <i class="fas fa-hiking"></i>
                {{ __('website.home.browse_activities') }}
            </a>
            @guest
                <a href="{{ route('provider.register') }}" class="hero-btn hero-btn-provider">
                    <i class="fas fa-handshake"></i>
                    {{ __('website.home.provider_cta') }}
                </a>
            @else
                @if(!auth()->user()->isApprovedContentProvider())
                    <a href="{{ route('provider.register') }}" class="hero-btn hero-btn-provider">
                        <i class="fas fa-handshake"></i>
                        {{ __('website.home.provider_cta') }}
                    </a>
                @endif
            @endguest
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">{{ __('website.home.why_visit') }}</h2>
        <p class="section-subtitle">{{ __('website.home.why_visit_subtitle') }}</p>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-monument"></i>
                </div>
                <h3 class="feature-title">{{ app()->getLocale() === 'en' ? 'Ancient Historical Heritage' : 'تراث تاريخي عريق' }}</h3>
                <p class="feature-description">{{ app()->getLocale() === 'en' ? 'Explore archaeological sites dating back thousands of years and unique architectural masterpieces.' : 'اكتشف مواقع أثرية تعود لآلاف السنين وتحف معمارية فريدة' }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mountain"></i>
                </div>
                <h3 class="feature-title">{{ app()->getLocale() === 'en' ? 'Breathtaking Nature' : 'طبيعة خلابة' }}</h3>
                <p class="feature-description">{{ app()->getLocale() === 'en' ? 'Enjoy stunning landscapes, towering mountains, and beautiful coastlines.' : 'استمتع بمناظر طبيعية ساحرة وجبال شاهقة وسواحل جميلة' }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 class="feature-title">{{ app()->getLocale() === 'en' ? 'Delicious Food' : 'مأكولات لذيذة' }}</h3>
                <p class="feature-description">{{ app()->getLocale() === 'en' ? 'Taste authentic Syrian dishes and popular local cuisine.' : 'تذوق أشهى الأطباق السورية الأصيلة والمأكولات الشعبية' }}</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <h3 class="feature-title">{{ app()->getLocale() === 'en' ? 'Warm Hospitality' : 'كرم الضيافة' }}</h3>
                <p class="feature-description">{{ app()->getLocale() === 'en' ? 'Experience genuine Arab hospitality and warm welcomes.' : 'استمتع بكرم الضيافة العربية الأصيلة والترحيب الحار' }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Destinations Section -->
<section class="section" style="background: var(--home-sand);">
    <div class="container">
        <h2 class="section-title">{{ app()->getLocale() === 'en' ? 'Featured Destinations' : 'وجهات مميزة' }}</h2>
        <p class="section-subtitle">{{ app()->getLocale() === 'en' ? 'Discover the most beautiful tourist destinations in Syria.' : 'اكتشف أجمل الوجهات السياحية في سوريا' }}</p>
        
        @if($featuredDestinations->count() > 0)
            <div class="destinations-grid">
                @foreach($featuredDestinations as $destination)
                    <a href="{{ route('destinations.show', $destination) }}" class="destination-card" style="text-decoration: none;">
                        <div class="destination-image-wrapper">
                            @if($destination->image_url)
                                <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" loading="lazy" decoding="async" onerror="this.src='https://via.placeholder.com/400x300?text={{ urlencode($destination->name) }}'">
                            @else
                                <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&q=80" alt="{{ $destination->name }}" loading="lazy" decoding="async">
                            @endif
                            <div class="destination-overlay">
                                <h3 class="destination-title">{{ $destination->name }}</h3>
                                <div class="destination-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $destination->country }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="destination-content">
                            <p class="destination-description">{{ Str::limit($destination->description, 120) }}</p>
                            <div class="destination-stats">
                                <span>
                                    <i class="fas fa-hiking"></i>
                                    {{ $destination->activities_count ?? $destination->activities->count() }} {{ app()->getLocale() === 'en' ? 'activities' : 'نشاط' }}
                                </span>
                                <span>
                                    <i class="fas fa-map"></i>
                                    {{ app()->getLocale() === 'en' ? 'Explore now' : 'استكشف الآن' }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <div style="text-align: center; margin-top: 3rem;">
                <a href="{{ route('destinations.index') }}" class="btn-primary-custom">
                    {{ app()->getLocale() === 'en' ? 'View all destinations' : 'عرض جميع الوجهات' }}
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                </svg>
                <h3>{{ app()->getLocale() === 'en' ? 'No destinations available at the moment' : 'لا توجد وجهات متاحة حالياً' }}</h3>
                <p>{{ app()->getLocale() === 'en' ? 'New destinations will be added soon.' : 'سيتم إضافة وجهات جديدة قريباً' }}</p>
            </div>
        @endif
    </div>
</section>

<!-- Activities Section -->
@if($featuredActivities->count() > 0)
<section class="section">
    <div class="container">
        <h2 class="section-title">{{ app()->getLocale() === 'en' ? 'Featured Activities' : 'أنشطة مميزة' }}</h2>
        <p class="section-subtitle">{{ app()->getLocale() === 'en' ? 'Discover the best tourist activities and experiences.' : 'اكتشف أفضل الأنشطة والتجارب السياحية' }}</p>
        
        <div class="swiper activities-swiper">
            <div class="swiper-wrapper">
                @foreach($featuredActivities as $activity)
                    <div class="swiper-slide">
                        <a href="{{ route('activities.show', $activity) }}" class="activity-card" style="text-decoration: none;">
                            @if($activity->image_url)
                                <img src="{{ $activity->image_url }}" alt="{{ $activity->name }}" class="activity-image" loading="lazy" decoding="async" onerror="this.src='https://via.placeholder.com/400x220?text={{ urlencode($activity->name) }}'">
                            @else
                                <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?w=400&q=80" alt="{{ $activity->name }}" class="activity-image" loading="lazy" decoding="async">
                            @endif
                            <div class="activity-content">
                                <h3 class="activity-title">{{ $activity->name }}</h3>
                                <div class="activity-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $activity->destination->name }}
                                </div>
                                <p class="activity-description">{{ Str::limit($activity->description, 100) }}</p>
                                <div class="activity-footer">
                                    <div class="activity-rating">
                                        <i class="fas fa-star"></i>
                                        <span>{{ number_format($activity->rating ?? 0, 1) }}</span>
                                    </div>
                                    <div class="activity-price">
                                        {{ number_format($activity->price ?? 0, 0) }} {{ app()->getLocale() === 'en' ? 'SYP' : 'ل.س' }}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('activities.index') }}" class="btn-primary-custom">
                {{ app()->getLocale() === 'en' ? 'View all activities' : 'عرض جميع الأنشطة' }}
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Testimonials Section -->
@if(isset($featuredReviews) && $featuredReviews->count() > 0)
<section class="section">
    <div class="container">
        <h2 class="section-title">{{ __('website.home.testimonials_title') }}</h2>
        <p class="section-subtitle">{{ __('website.home.testimonials_subtitle') }}</p>

        <div class="testimonials-grid">
            @foreach($featuredReviews as $review)
                <article class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="testimonial-avatar">
                            {{ strtoupper(mb_substr($review->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="testimonial-name">{{ $review->user->name ?? __('website.home.anonymous_traveler') }}</h3>
                            <p class="testimonial-meta">
                                {{ $review->activity->name ?? __('website.home.general_experience') }}
                                @if(optional($review->activity->destination)->name)
                                    • {{ $review->activity->destination->name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="testimonial-rating" aria-label="{{ __('website.home.rating_label') }}">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= (int) round($review->rating ?? 0) ? 'active' : '' }}"></i>
                        @endfor
                    </div>

                    <p class="testimonial-text">
                        {{ Str::limit($review->comment ?? $review->content ?? __('website.home.testimonial_fallback_text'), 130) }}
                    </p>

                    @if($review->activity)
                        <a href="{{ route('activities.show', $review->activity) }}" class="testimonial-link">
                            {{ __('website.home.view_activity') }}
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Interactive Map Section -->
@if(isset($allDestinations) && $allDestinations->count() > 0)
<section class="section" style="background: var(--home-sand);">
    <div class="container">
        <h2 class="section-title">{{ app()->getLocale() === 'en' ? 'Discover on the map' : 'اكتشف على الخريطة' }}</h2>
        <p class="section-subtitle">{{ app()->getLocale() === 'en' ? 'Explore tourist destinations on the interactive map.' : 'استكشف الوجهات السياحية على الخريطة التفاعلية' }}</p>
        
        <div class="map-section">
            <div id="map"></div>
            <div class="map-controls">
                <div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        {{ app()->getLocale() === 'en' ? 'Click "Locate me" to show your location on the map.' : 'اضغط على "تحديد موقعي" لعرض موقعك على الخريطة' }}
                    </small>
                    <div id="locationStatus" class="mt-2"></div>
                </div>
                <button id="getLocationBtn" class="btn btn-primary">
                    <i class="fas fa-crosshairs me-2"></i>
                    {{ app()->getLocale() === 'en' ? 'Locate me' : 'تحديد موقعي' }}
                </button>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ route('interactive-map') }}" class="btn-primary-custom">
                <i class="fas fa-map-marked-alt"></i>
                {{ app()->getLocale() === 'en' ? 'Open full interactive map' : 'فتح الخريطة التفاعلية الكاملة' }}
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="section">
    <div class="container">
        <div class="cta-section">
            <div class="cta-content">
                <h2 class="cta-title">{{ app()->getLocale() === 'en' ? 'Get ready for an unforgettable journey' : 'استعد لرحلة لا تُنسى' }}</h2>
                <p class="cta-description">{{ app()->getLocale() === 'en' ? 'Plan your trip to Syria and discover its many wonders.' : 'خطط لرحلتك إلى سوريا واكتشف عجائبها الكثيرة' }}</p>
                <div class="cta-buttons">
                    <a href="{{ route('destinations.index') }}" class="cta-btn cta-btn-light">
                        <i class="fas fa-map-marked-alt"></i>
                        {{ app()->getLocale() === 'en' ? 'Explore Destinations' : 'استكشف الوجهات' }}
                    </a>
                    <a href="{{ route('activities.index') }}" class="cta-btn cta-btn-outline">
                        <i class="fas fa-hiking"></i>
                        {{ app()->getLocale() === 'en' ? 'Browse Activities' : 'تصفح الأنشطة' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Initialize Map
    let map = L.map('map').setView([34.8021, 38.9968], 6);
    let userMarker = null;
    let userLocation = null;
    const isRtl = '{{ app()->getLocale() }}' === 'ar';
    const popupAlign = isRtl ? 'right' : 'left';
    const popupDirection = isRtl ? 'rtl' : 'ltr';

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // Add destinations to map
    @if(isset($allDestinations) && $allDestinations->count() > 0)
        @foreach($allDestinations as $destination)
            @if($destination->latitude && $destination->longitude)
                var destinationIcon = L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background: #0ea5a4; color: white; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.3);"><i class="fas fa-map-marker-alt"></i></div>',
                    iconSize: [35, 35],
                    iconAnchor: [17.5, 35]
                });

                var destinationMarker = L.marker([{{ $destination->latitude }}, {{ $destination->longitude }}], { icon: destinationIcon })
                    .addTo(map)
                    .bindPopup(`
                        <div style="text-align: ${popupAlign}; direction: ${popupDirection}; min-width: 200px;">
                            <h6 class="fw-bold mb-2">{{ $destination->name }}</h6>
                            <p class="mb-1 text-muted small">{{ $destination->country }}</p>
                            <a href="{{ route('destinations.show', $destination) }}" class="btn btn-primary btn-sm mt-2 w-100">
                                {{ app()->getLocale() === 'en' ? 'View details' : 'عرض التفاصيل' }}
                            </a>
                        </div>
                    `);
            @endif
        @endforeach
    @endif

    // Location functionality
    document.getElementById('getLocationBtn')?.addEventListener('click', function() {
        const btn = this;
        const statusDiv = document.getElementById('locationStatus');
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ app()->getLocale() === "en" ? "Locating..." : "جاري التحديد..." }}';
        statusDiv.innerHTML = '<div class="location-status info"><i class="fas fa-info-circle me-2"></i>{{ app()->getLocale() === "en" ? "Getting your location..." : "جاري الحصول على موقعك..." }}</div>';

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    userLocation = [lat, lng];

                    if (userMarker) {
                        map.removeLayer(userMarker);
                    }

                    var userIcon = L.divIcon({
                        className: 'custom-user-marker',
                        html: '<div style="background: #ef4444; color: white; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.4);"><i class="fas fa-user"></i></div>',
                        iconSize: [45, 45],
                        iconAnchor: [22.5, 45]
                    });

                    userMarker = L.marker([lat, lng], { icon: userIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div style="text-align: ${popupAlign}; direction: ${popupDirection};">
                                <h6 class="fw-bold mb-2"><i class="fas fa-map-marker-alt text-danger me-2"></i>{{ app()->getLocale() === "en" ? "Your current location" : "موقعك الحالي" }}</h6>
                                <p class="mb-1 small">{{ app()->getLocale() === "en" ? "Latitude" : "خط العرض" }}: ${lat.toFixed(6)}</p>
                                <p class="mb-0 small">{{ app()->getLocale() === "en" ? "Longitude" : "خط الطول" }}: ${lng.toFixed(6)}</p>
                            </div>
                        `)
                        .openPopup();

                    map.setView([lat, lng], 13);

                    statusDiv.innerHTML = `
                        <div class="location-status success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ app()->getLocale() === 'en' ? 'Your location was detected successfully!' : 'تم تحديد موقعك بنجاح!' }}
                        </div>
                    `;

                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-crosshairs me-2"></i>{{ app()->getLocale() === "en" ? "Update location" : "تحديث الموقع" }}';
                },
                function(error) {
                    let errorMsg = '{{ app()->getLocale() === "en" ? "An error occurred while detecting your location. " : "حدث خطأ في تحديد موقعك. " }}';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg += '{{ app()->getLocale() === "en" ? "Location access request was denied." : "تم رفض طلب الوصول للموقع." }}';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg += '{{ app()->getLocale() === "en" ? "Location information is unavailable." : "معلومات الموقع غير متاحة." }}';
                            break;
                        case error.TIMEOUT:
                            errorMsg += '{{ app()->getLocale() === "en" ? "Location request timed out." : "انتهت مهلة طلب الموقع." }}';
                            break;
                    }
                    statusDiv.innerHTML = `<div class="location-status error"><i class="fas fa-exclamation-circle me-2"></i>${errorMsg}</div>`;
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-crosshairs me-2"></i>{{ app()->getLocale() === "en" ? "Locate me" : "تحديد موقعي" }}';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            statusDiv.innerHTML = '<div class="location-status error"><i class="fas fa-exclamation-circle me-2"></i>{{ app()->getLocale() === "en" ? "Your browser does not support geolocation." : "المتصفح لا يدعم تحديد الموقع الجغرافي." }}</div>';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-crosshairs me-2"></i>{{ app()->getLocale() === "en" ? "Locate me" : "تحديد موقعي" }}';
        }
    });

    // Initialize Swiper
    @if($featuredActivities->count() > 0)
    const swiper = new Swiper('.activities-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
        },
    });
    @endif
</script>
@endpush
@endsection
