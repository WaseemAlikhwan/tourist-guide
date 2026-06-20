@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'Interactive Map - Wander Point in Syria' : 'الخريطة التفاعلية - Wander Point in Syria')

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .map-hero {
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.9) 0%, rgba(160, 82, 45, 0.9) 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        border-radius: 24px;
        margin-bottom: 3rem;
    }
    #interactiveMap {
        height: 600px;
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 1;
    }
    .map-controls {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .destination-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .destination-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .destination-item:hover {
        background: rgba(139, 69, 19, 0.05);
    }
    .destination-item.active {
        background: rgba(139, 69, 19, 0.1);
        border-right: 4px solid var(--primary);
    }
</style>
@endpush

@section('content')
<div class="map-hero">
    <h1 class="display-4 fw-bold mb-3">{{ $isEn ? 'Interactive Map' : 'الخريطة التفاعلية' }}</h1>
    <p class="lead">{{ $isEn ? 'Explore all tourist destinations in Syria on the map' : 'استكشف جميع الوجهات السياحية في سوريا على الخريطة' }}</p>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="map-controls">
            <h4 class="mb-3">
                <i class="fas fa-list me-2" style="color: var(--primary);"></i>
                {{ $isEn ? 'Tourist Destinations' : 'الوجهات السياحية' }}
            </h4>
            <div class="destination-list">
                @if($destinations->count() > 0)
                    @foreach($destinations as $destination)
                        <div class="destination-item" data-lat="{{ $destination->latitude }}" data-lng="{{ $destination->longitude }}" data-id="{{ $destination->id }}">
                            <h6 class="fw-bold mb-1">{{ $destination->name }}</h6>
                            <p class="text-muted small mb-0">{{ $destination->country }}</p>
                            <small class="text-primary">{{ $destination->activities->count() }} {{ $isEn ? 'activities' : 'نشاط' }}</small>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center">{{ $isEn ? 'No destinations available right now' : 'لا توجد وجهات متاحة حالياً' }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div id="interactiveMap"></div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // تهيئة الخريطة
    let map = L.map('interactiveMap').setView([34.8021, 38.9968], 6);
    let markers = [];

    // إضافة طبقة الخريطة
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    // إضافة الوجهات على الخريطة
    @if($destinations->count() > 0)
        @foreach($destinations as $destination)
            @if($destination->latitude && $destination->longitude)
                var marker = L.marker([{{ $destination->latitude }}, {{ $destination->longitude }}])
                    .addTo(map)
                    .bindPopup(`
                        <div style="text-align: {{ $isEn ? 'left' : 'right' }}; direction: {{ $isEn ? 'ltr' : 'rtl' }}; min-width: 200px;">
                            <h6 class="fw-bold mb-2">{{ $destination->name }}</h6>
                            <p class="mb-1 text-muted small">{{ $destination->country }}</p>
                            <p class="mb-2 small">{{ Str::limit($destination->description, 100) }}</p>
                            <a href="{{ route('destinations.show', $destination) }}" class="btn btn-primary btn-sm w-100">
                                {{ $isEn ? 'View Details' : 'عرض التفاصيل' }}
                            </a>
                        </div>
                    `);
                
                var customIcon = L.divIcon({
                    className: 'custom-marker',
                    html: '<div style="background: #8B4513; color: white; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"><i class="fas fa-map-marker-alt"></i></div>',
                    iconSize: [35, 35],
                    iconAnchor: [17, 35]
                });
                marker.setIcon(customIcon);
                
                markers[{{ $destination->id }}] = marker;
            @endif
        @endforeach
    @endif

    // تفاعل مع قائمة الوجهات
    document.querySelectorAll('.destination-item').forEach(item => {
        item.addEventListener('click', function() {
            const lat = parseFloat(this.dataset.lat);
            const lng = parseFloat(this.dataset.lng);
            const id = parseInt(this.dataset.id);
            
            // إزالة التمييز السابق
            document.querySelectorAll('.destination-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            // نقل الخريطة للموقع
            map.setView([lat, lng], 12);
            
            // فتح popup
            if (markers[id]) {
                markers[id].openPopup();
            }
        });
    });
</script>
@endpush

