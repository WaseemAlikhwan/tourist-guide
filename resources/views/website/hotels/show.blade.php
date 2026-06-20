@extends('website.layouts.app')

@section('title', $hotel->name . ' - Wander Point in Syria')

@push('head')
<style>
    .hotel-hero {
        position: relative;
        height: 500px;
        border-radius: 24px;
        overflow: hidden;
        margin-bottom: 3rem;
    }
    .hotel-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .hotel-header-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    .hotel-main-info {
        flex: 1;
    }
    .hotel-info-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        position: sticky;
        top: 100px;
        height: fit-content;
    }
    .hotel-badges {
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
    .badge-stars {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: white;
    }
    .badge-location {
        background: rgba(139, 69, 19, 0.1);
        color: var(--primary-dark);
    }
    .hotel-title {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1rem;
        color: #0f172a;
        line-height: 1.2;
    }
    .hotel-location {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
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
    .amenities-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    .amenity-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    .amenity-icon {
        color: var(--primary);
        font-size: 1.2rem;
    }
    .contact-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-top: 2rem;
    }
    .btn-contact {
        width: 100%;
        padding: 1rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }
    .btn-phone {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .btn-phone:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
        color: white;
    }
    .btn-email {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    .btn-email:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.3);
        color: white;
    }
    .btn-website {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
    }
    .btn-website:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(139, 69, 19, 0.3);
        color: white;
    }
    @media (max-width: 992px) {
        .hotel-header-content {
            grid-template-columns: 1fr;
        }
        .hotel-info-card {
            position: static;
        }
        .hotel-title {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Image -->
<div class="hotel-hero">
    @if($hotel->image)
        <img src="{{ asset('storage/' . $hotel->image) }}" alt="{{ $hotel->name }}">
    @else
        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%); display: flex; align-items: center; justify-content: center;">
            <i class="fas fa-hotel" style="font-size: 8rem; color: #94a3b8;"></i>
        </div>
    @endif
</div>

<!-- Back Link -->
<a href="{{ route('destinations.show', $hotel->destination) }}" class="back-link" style="margin-bottom: 2rem; display: inline-flex; align-items: center; gap: 0.5rem; color: #8B4513; font-weight: 600; text-decoration: none; padding: 0.75rem 1.5rem; background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease;">
    <i class="fas fa-arrow-right"></i>
    <span>العودة إلى {{ $hotel->destination->name }}</span>
</a>

<div class="hotel-header-content">
    <div class="hotel-main-info">
        <div class="hotel-badges">
            <span class="badge-item badge-stars">
                <i class="fas fa-star"></i>
                {!! str_repeat('⭐', $hotel->star_rating) !!} ({{ $hotel->star_rating }} نجوم)
            </span>
            <span class="badge-item badge-location">
                <i class="fas fa-map-marker-alt"></i>
                {{ $hotel->destination->name }}
            </span>
            @if($distance !== null)
                <span class="badge-item badge-location">
                    <i class="fas fa-route"></i>
                    على بعد {{ $distance }} كم من الوجهة
                </span>
            @endif
        </div>

        <h1 class="hotel-title">{{ $hotel->name }}</h1>
        
        @if($hotel->address)
            <div class="hotel-location">
                <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i>
                <span>{{ $hotel->address }}</span>
            </div>
        @endif

        <!-- معلومات إضافية -->
        <div class="info-grid">
            @if($hotel->price_per_night)
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="info-card-title">السعر</div>
                <div class="info-card-text">{{ number_format($hotel->price_per_night, 0) }} ل.س / ليلة</div>
            </div>
            @endif
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="info-card-title">التصنيف</div>
                <div class="info-card-text">{!! str_repeat('⭐', $hotel->star_rating) !!} نجوم</div>
            </div>
            @if($hotel->destination)
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="info-card-title">الوجهة</div>
                <div class="info-card-text">
                    <a href="{{ route('destinations.show', $hotel->destination) }}" style="color: var(--primary); text-decoration: none;">
                        {{ $hotel->destination->name }}
                    </a>
                </div>
            </div>
            @endif
            @if($distance !== null)
            <div class="info-card">
                <div class="info-card-icon">
                    <i class="fas fa-route"></i>
                </div>
                <div class="info-card-title">المسافة</div>
                <div class="info-card-text">{{ $distance }} كم من الوجهة</div>
            </div>
            @endif
        </div>

        <!-- الوصف -->
        @if($hotel->description)
        <div class="description-section">
            <h2 class="section-title">
                <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                عن الفندق
            </h2>
            <div class="description-text">{{ $hotel->description }}</div>
        </div>
        @endif

        <!-- المرافق والخدمات -->
        @if($hotel->amenities && count($hotel->amenities) > 0)
        <div class="description-section">
            <h2 class="section-title">
                <i class="fas fa-concierge-bell" style="color: var(--primary);"></i>
                المرافق والخدمات
            </h2>
            <div class="amenities-grid">
                @foreach($hotel->amenities as $amenity)
                    <div class="amenity-item">
                        <i class="fas fa-check-circle amenity-icon"></i>
                        <span style="color: #475569; font-weight: 500;">{{ $amenity }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- الموقع على الخريطة -->
        @if($hotel->latitude && $hotel->longitude)
        <div class="description-section">
            <h2 class="section-title">
                <i class="fas fa-map-marked-alt" style="color: var(--primary);"></i>
                الموقع على الخريطة
            </h2>
            <div id="map" style="width: 100%; height: 400px; border-radius: 20px; border: 1px solid #e5e7eb; box-shadow: 0 4px 20px rgba(0,0,0,0.08); overflow: hidden;"></div>
        </div>
        @endif
    </div>

    <!-- بطاقة المعلومات الجانبية -->
    <div class="hotel-info-card">
        @if($hotel->price_per_night)
        <div class="price-section">
            <div class="price-main">{{ number_format($hotel->price_per_night, 0) }}</div>
            <div class="price-label">ل.س لليلة الواحدة</div>
        </div>
        @else
        <div class="price-section">
            <div style="text-align: center; padding: 2rem 0;">
                <i class="fas fa-phone-alt" style="font-size: 3rem; color: var(--primary); margin-bottom: 1rem;"></i>
                <h4 style="color: #0f172a; margin-bottom: 0.5rem;">اتصل للاستعلام</h4>
                <p style="color: #64748b; margin: 0;">يرجى الاتصال للاستفسار عن الأسعار</p>
            </div>
        </div>
        @endif

        <div class="contact-buttons">
            @if($hotel->phone)
                <a href="tel:{{ $hotel->phone }}" class="btn-contact btn-phone">
                    <i class="fas fa-phone"></i>
                    الاتصال: {{ $hotel->phone }}
                </a>
            @endif
            @if($hotel->email)
                <a href="mailto:{{ $hotel->email }}" class="btn-contact btn-email">
                    <i class="fas fa-envelope"></i>
                    إرسال بريد إلكتروني
                </a>
            @endif
            @if($hotel->website)
                <a href="{{ $hotel->website }}" target="_blank" class="btn-contact btn-website">
                    <i class="fas fa-globe"></i>
                    زيارة الموقع الإلكتروني
                </a>
            @endif
        </div>

        <div style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e5e7eb;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <i class="fas fa-shield-alt" style="color: var(--primary); font-size: 1.5rem;"></i>
                <div>
                    <div style="font-weight: 700; color: #0f172a;">حجز آمن</div>
                    <div style="color: #64748b; font-size: 0.9rem;">ضمان أفضل الأسعار</div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-headset" style="color: var(--primary); font-size: 1.5rem;"></i>
                <div>
                    <div style="font-weight: 700; color: #0f172a;">دعم 24/7</div>
                    <div style="color: #64748b; font-size: 0.9rem;">نحن هنا لمساعدتك</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($hotel->latitude && $hotel->longitude)
<script>
    function initMap() {
        const hotelLocation = { lat: {{ $hotel->latitude }}, lng: {{ $hotel->longitude }} };
        
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: hotelLocation,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
        });
        
        const marker = new google.maps.Marker({
            position: hotelLocation,
            map: map,
            title: '{{ $hotel->name }}',
            animation: google.maps.Animation.DROP,
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 1rem;">
                    <h3 style="margin: 0 0 0.5rem 0; color: #0f172a;">{{ $hotel->name }}</h3>
                    @if($hotel->address)
                        <p style="margin: 0; color: #64748b; font-size: 0.9rem;">{{ $hotel->address }}</p>
                    @endif
                </div>
            `
        });
        
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
        
        infoWindow.open(map, marker);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key', '') }}&callback=initMap" async defer></script>
@endif
@endpush
