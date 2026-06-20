@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $destination->name . ($isEn ? ' - Tourist Guide' : ' - الدليل السياحي'))
@section('meta_title', $destination->name . ($isEn ? ' - Tourist Guide' : ' - الدليل السياحي'))
@section('meta_description', Str::limit(strip_tags($destination->description), 160))
@section('meta_keywords', $destination->name . ', ' . $destination->country . ', وجهات سياحية, سياحة سوريا')

@php
    $ogImage = $destination->image_url ?: asset('storage/default-destination.jpg');
    $description = Str::limit(strip_tags($destination->description), 200);
@endphp

@section('og_title', $destination->name . ($isEn ? ' - Tourist Guide' : ' - الدليل السياحي'))
@section('og_description', $description)
@section('og_image', $ogImage)

@section('twitter_title', $destination->name . ($isEn ? ' - Tourist Guide' : ' - الدليل السياحي'))
@section('twitter_description', $description)
@section('twitter_image', $ogImage)

@php
$structuredData = [
    "@context" => "https://schema.org",
    "@type" => "TouristDestination",
    "name" => $destination->name,
    "description" => strip_tags($destination->description),
    "address" => [
        "@type" => "PostalAddress",
        "addressCountry" => $destination->country
    ]
];

if ($destination->latitude && $destination->longitude) {
    $structuredData["geo"] = [
        "@type" => "GeoCoordinates",
        "latitude" => $destination->latitude,
        "longitude" => $destination->longitude
    ];
}

if ($destination->image_url) {
    $structuredData["image"] = $destination->image_url;
}

if ($destination->activities->count() > 0) {
    $activities = [];
    foreach ($destination->activities as $activity) {
        $activities[] = [
            "@type" => "Offer",
            "itemOffered" => [
                "@type" => "TouristTrip",
                "name" => $activity->name,
                "description" => Str::limit(strip_tags($activity->description), 150),
                "provider" => [
                    "@type" => "Organization",
                    "name" => "Wander Point in Syria"
                ]
            ],
            "price" => (string)$activity->price,
            "priceCurrency" => "SYP"
        ];
    }
    
    $structuredData["hasOfferCatalog"] = [
        "@type" => "OfferCatalog",
        "name" => "أنشطة " . $destination->name,
        "itemListElement" => $activities
    ];
}
@endphp

@push('structured_data')
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@push('head')
<link rel="stylesheet" href="{{ asset('css/destinations.css') }}">
@if($destination->latitude && $destination->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endif
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.favorite-btn{{ $isFavorited ? '.active' : '' }} {
    background: {{ $isFavorited ? '#ef4444' : '#f1f5f9' }};
    color: {{ $isFavorited ? '#fff' : '#475569' }};
}
</style>
@endpush

@push('scripts')
@if($destination->latitude && $destination->longitude)
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endif
<script src="{{ asset('js/destinations.js') }}" defer></script>
<script>
const isEnglish = @json($isEn);
const numberLocale = isEnglish ? 'en-US' : 'ar';
const currencyUnit = isEnglish ? 'SYP' : 'ل.س';

// Pass data to JavaScript
window.destinationData = {
    latitude: {{ $destination->latitude ?? 'null' }},
    longitude: {{ $destination->longitude ?? 'null' }},
    name: "{{ addslashes($destination->name) }}",
    favoriteToggleUrl: "{{ route('favorites.toggle') }}",
    weatherUpdateUrl: "{{ route('destinations.weather.update', $destination) }}",
    isFavorited: {{ $isFavorited ? 'true' : 'false' }}
};

// Initialize map data attributes
@if($destination->latitude && $destination->longitude)
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('map');
    if (mapElement) {
        mapElement.dataset.latitude = {{ $destination->latitude }};
        mapElement.dataset.longitude = {{ $destination->longitude }};
        mapElement.dataset.name = "{{ addslashes($destination->name) }}";
    }
});
@endif

// Initialize favorite button data attributes
document.addEventListener('DOMContentLoaded', function() {
    const favoriteBtn = document.getElementById('favorite-btn');
    if (favoriteBtn) {
        favoriteBtn.dataset.toggleUrl = "{{ route('favorites.toggle') }}";
    }
    
    const weatherBtn = document.getElementById('update-weather-btn');
    if (weatherBtn) {
        weatherBtn.dataset.updateUrl = "{{ route('destinations.weather.update', $destination) }}";
    }
});

// حاسبة تكلفة الرحلة
function calculateTripCost() {
    const days = parseInt(document.getElementById('days-count').value) || 1;
    const people = parseInt(document.getElementById('people-count').value) || 1;
    const budget = parseFloat(document.getElementById('budget-amount').value) || null;
    
    if (days < 1 || days > 30) {
        alert(isEnglish ? 'Number of days must be between 1 and 30' : 'عدد الأيام يجب أن يكون بين 1 و 30');
        return;
    }
    
    if (people < 1 || people > 20) {
        alert(isEnglish ? 'Number of people must be between 1 and 20' : 'عدد الأشخاص يجب أن يكون بين 1 و 20');
        return;
    }
    
    // عرض النتائج
    const resultDiv = document.getElementById('cost-result');
    const detailsDiv = document.getElementById('cost-details');
    
    if (!resultDiv || !detailsDiv) {
        alert(isEnglish ? 'Error loading elements. Please reload the page.' : 'خطأ في تحميل العناصر. يرجى إعادة تحميل الصفحة.');
        return;
    }
    
    // حساب تكلفة الأنشطة
    const activities = @json($destination->activities ?? []) || [];
    const totalActivitiesCost = Array.isArray(activities) && activities.length > 0 
        ? activities.reduce((sum, activity) => sum + (parseFloat(activity.price) || 0), 0) 
        : 0;
    const avgActivityCost = Array.isArray(activities) && activities.length > 0 ? totalActivitiesCost / activities.length : 0;
    
    // حساب تكلفة الفنادق
    const hotels = @json($destination->activeHotels ?? []) || [];
    const avgHotelCost = Array.isArray(hotels) && hotels.length > 0 
        ? hotels.reduce((sum, hotel) => sum + (parseFloat(hotel.price_per_night) || 0), 0) / hotels.length 
        : 50000; // متوسط افتراضي
    
    // الحسابات
    const estimatedActivitiesCost = avgActivityCost * days * people * 0.5; // تقدير: 2 نشاط في اليوم
    const estimatedHotelsCost = avgHotelCost * days * people; // تكلفة الفنادق
    const estimatedFoodCost = 30000 * days * people; // تقدير: 30000 ل.س للفرد في اليوم
    const estimatedTransportCost = 50000; // تقدير ثابت
    const totalEstimatedCost = estimatedActivitiesCost + estimatedHotelsCost + estimatedFoodCost + estimatedTransportCost;
    
    detailsDiv.innerHTML = `
        <div style="display: grid; gap: 1rem;">
            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border-right: 4px solid #b89ff0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #64748b;">${isEnglish ? 'Activities:' : 'الأنشطة:'}</span>
                    <strong style="color: #0f172a;">${Math.round(estimatedActivitiesCost).toLocaleString(numberLocale)} ${currencyUnit}</strong>
                </div>
                <small style="color: #94a3b8;">${isEnglish ? `Estimate: ${(days * 2).toFixed(0)} activities x ${people} people` : `تقدير: ${(days * 2).toFixed(0)} نشاط × ${people} أشخاص`}</small>
            </div>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border-right: 4px solid #667eea;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #64748b;">${isEnglish ? 'Hotels:' : 'الفنادق:'}</span>
                    <strong style="color: #0f172a;">${Math.round(estimatedHotelsCost).toLocaleString(numberLocale)} ${currencyUnit}</strong>
                </div>
                <small style="color: #94a3b8;">${isEnglish ? `${days} nights x ${people} people` : `${days} ليلة × ${people} أشخاص`}</small>
            </div>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border-right: 4px solid #10b981;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #64748b;">${isEnglish ? 'Food:' : 'المأكولات:'}</span>
                    <strong style="color: #0f172a;">${Math.round(estimatedFoodCost).toLocaleString(numberLocale)} ${currencyUnit}</strong>
                </div>
                <small style="color: #94a3b8;">${isEnglish ? 'Daily estimate: 30,000 SYP per person' : 'تقدير يومي: 30,000 ل.س للفرد'}</small>
            </div>
            <div style="background: #f8fafc; padding: 1rem; border-radius: 12px; border-right: 4px solid #f59e0b;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="color: #64748b;">${isEnglish ? 'Transport:' : 'المواصلات:'}</span>
                    <strong style="color: #0f172a;">${Math.round(estimatedTransportCost).toLocaleString(numberLocale)} ${currencyUnit}</strong>
                </div>
            </div>
            <div style="background: linear-gradient(135deg, #b89ff0 0%, #9d7df0 100%); padding: 1.5rem; border-radius: 12px; color: white; margin-top: 0.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 1.1rem; font-weight: 600;">${isEnglish ? 'Estimated total cost:' : 'التكلفة الإجمالية المتوقعة:'}</span>
                    <strong style="font-size: 1.5rem;">${Math.round(totalEstimatedCost).toLocaleString(numberLocale)} ${currencyUnit}</strong>
                </div>
                ${budget ? `
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.3);">
                        ${totalEstimatedCost <= budget 
                            ? `<span style="color: #10b981;"><i class="fas fa-check-circle"></i> ${isEnglish ? `Budget is sufficient (${Math.round(budget - totalEstimatedCost).toLocaleString(numberLocale)} ${currencyUnit} left)` : `الميزانية كافية (${Math.round(budget - totalEstimatedCost).toLocaleString(numberLocale)} ${currencyUnit} متبقي)`}</span>`
                            : `<span style="color: #ef4444;"><i class="fas fa-exclamation-triangle"></i> ${isEnglish ? `Budget is not enough (Shortage: ${Math.round(totalEstimatedCost - budget).toLocaleString(numberLocale)} ${currencyUnit})` : `الميزانية غير كافية (نقص: ${Math.round(totalEstimatedCost - budget).toLocaleString(numberLocale)} ${currencyUnit})`}</span>`
                        }
                    </div>
                ` : ''}
            </div>
        </div>
    `;
    
    resultDiv.style.display = 'block';
    resultDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// مولد خط السير الذكي
function generateItinerary() {
    const days = parseInt(document.getElementById('itinerary-days').value) || 1;
    const budget = parseFloat(document.getElementById('itinerary-budget').value) || null;
    const interests = Array.from(document.querySelectorAll('.interest-checkbox:checked')).map(cb => cb.value);
    const destinationId = {{ $destination->id ?? 0 }};
    
    if (days < 1 || days > 14) {
        alert(isEnglish ? 'Number of days must be between 1 and 14' : 'عدد الأيام يجب أن يكون بين 1 و 14');
        return;
    }
    
    if (destinationId === 0) {
        alert(isEnglish ? 'Invalid destination' : 'خطأ في تحديد الوجهة');
        return;
    }
    
    // إظهار تحميل
    const resultDiv = document.getElementById('itinerary-result');
    const detailsDiv = document.getElementById('itinerary-details');
    if (!resultDiv || !detailsDiv) {
        console.error(isEnglish ? 'HTML elements are missing' : 'عناصر HTML غير موجودة');
        return;
    }
    detailsDiv.innerHTML = `<div style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #667eea;"></i><p style="margin-top: 1rem;">${isEnglish ? 'Generating itinerary...' : 'جاري إنشاء خط السير...'}</p></div>`;
    resultDiv.style.display = 'block';
    
    // التحقق من CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        detailsDiv.innerHTML = `<div style="text-align: center; padding: 2rem; color: #ef4444;"><i class="fas fa-exclamation-triangle"></i><p>${isEnglish ? 'Security error - please reload the page' : 'خطأ في الأمان - يرجى إعادة تحميل الصفحة'}</p></div>`;
        return;
    }
    
    const token = csrfToken.getAttribute('content');
    if (!token) {
        detailsDiv.innerHTML = `<div style="text-align: center; padding: 2rem; color: #ef4444;"><i class="fas fa-exclamation-triangle"></i><p>${isEnglish ? 'Security error - please reload the page' : 'خطأ في الأمان - يرجى إعادة تحميل الصفحة'}</p></div>`;
        return;
    }
    
    // إرسال الطلب
    fetch('{{ route("destinations.generate-itinerary") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            destination_id: destinationId,
            days: days,
            budget: budget || null,
            interests: interests
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || (isEnglish ? 'Request failed' : 'حدث خطأ في الطلب'));
            }).catch(() => {
                throw new Error(isEnglish ? 'Server connection error' : 'حدث خطأ في الاتصال بالخادم');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success && data.itinerary) {
            displayItinerary(data.itinerary, data.total_budget || 0, data.destination || (isEnglish ? 'Destination' : 'الوجهة'));
        } else {
            detailsDiv.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;"><i class="fas fa-exclamation-triangle"></i><p>' + (data.message || (isEnglish ? 'Failed to generate itinerary' : 'حدث خطأ في إنشاء خط السير')) + '</p></div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        detailsDiv.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;"><i class="fas fa-exclamation-triangle"></i><p>' + (error.message || (isEnglish ? 'Server connection error. Please try again later.' : 'حدث خطأ في الاتصال بالخادم. يرجى المحاولة لاحقاً.')) + '</p></div>';
    });
}

function displayItinerary(itinerary, totalBudget, destinationName) {
    const detailsDiv = document.getElementById('itinerary-details');
    
    if (!itinerary || itinerary.length === 0) {
        detailsDiv.innerHTML = `<div style="text-align: center; padding: 2rem; color: #64748b;"><i class="fas fa-info-circle"></i><p>${isEnglish ? 'No activities available based on selected criteria' : 'لا توجد أنشطة متاحة بناءً على المعايير المحددة'}</p></div>`;
        return;
    }
    
    let html = `
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 12px; color: white; margin-bottom: 1.5rem;">
            <h5 style="margin-bottom: 0.5rem;"><i class="fas fa-map-marked-alt"></i> ${isEnglish ? `Itinerary for ${destinationName}` : `خط سير ${destinationName}`}</h5>
            <p style="margin: 0; opacity: 0.9;">${isEnglish ? 'Total budget:' : 'إجمالي الميزانية:'} <strong>${Math.round(totalBudget).toLocaleString(numberLocale)} ${currencyUnit}</strong></p>
        </div>
    `;
    
    itinerary.forEach((day, index) => {
        html += `
            <div style="background: white; border: 2px solid #e5e7eb; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 2px solid #f1f5f9;">
                    <h4 style="color: #b89ff0; margin: 0;">
                        <i class="fas fa-calendar-day"></i> ${isEnglish ? 'Day' : 'اليوم'} ${day.day}
                    </h4>
                    <div style="text-align: left;">
                        <div style="color: #64748b; font-size: 0.9rem;">${isEnglish ? 'Daily budget' : 'الميزانية اليومية'}</div>
                        <strong style="color: #0f172a; font-size: 1.1rem;">${Math.round(day.total_price).toLocaleString(numberLocale)} ${currencyUnit}</strong>
                    </div>
                </div>
                <div style="color: #64748b; margin-bottom: 1rem;">
                    <i class="fas fa-clock"></i> ${isEnglish ? 'Estimated duration:' : 'المدة المتوقعة:'} ${day.estimated_duration}
                </div>
                <div style="display: grid; gap: 1rem;">
        `;
        
        day.activities.forEach((activity, actIndex) => {
            html += `
                <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border-right: 3px solid #667eea;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <div style="flex: 1;">
                            <strong style="color: #0f172a; display: block; margin-bottom: 0.25rem;">${actIndex + 1}. ${activity.name}</strong>
                            <span style="display: inline-block; background: #e0e7ff; color: #4338ca; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600; margin-top: 0.25rem;">${activity.type}</span>
                        </div>
                        <strong style="color: #b89ff0; margin-right: 1rem;">${Math.round(activity.price).toLocaleString(numberLocale)} ${currencyUnit}</strong>
                    </div>
                    ${activity.description ? `<p style="color: #64748b; font-size: 0.9rem; margin: 0.5rem 0 0 0; line-height: 1.6;">${activity.description.substring(0, 100)}${activity.description.length > 100 ? '...' : ''}</p>` : ''}
                </div>
            `;
        });
        
        html += `
                </div>
            </div>
        `;
    });
    
    detailsDiv.innerHTML = html;
    document.getElementById('itinerary-result').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

</script>
@endpush

@section('content')
<a href="{{ route('destinations.index') }}" class="back-link">
    <i class="fas fa-arrow-right"></i>
    <span>{{ $isEn ? 'Back to destinations' : 'العودة إلى الوجهات' }}</span>
</a>

<!-- Hero Image -->
@if($destination->image)
    <div class="destination-hero">
        <img 
            src="{{ $destination->image_url }}" 
            alt="{{ $destination->name }} - {{ $destination->country }}"
            loading="lazy"
            decoding="async"
            width="1200"
            height="400"
            class="destination-hero-image"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
        >
        <div class="hero-placeholder" style="display: none; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%); height: 100%;">
            <svg width="120" height="120" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;" aria-hidden="true">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                <path d="M9 22V12h6v10"></path>
            </svg>
        </div>
    </div>
@endif

<!-- Header Info -->
<div class="destination-header-info">
    <div style="flex: 1;">
        <h1 class="destination-title">{{ $destination->name }}</h1>
        <div class="destination-country">
            <i class="fas fa-map-marker-alt" style="color: #b89ff0;"></i>
            <span>{{ $destination->country }}</span>
        </div>
    </div>
    @auth
    <button id="favorite-btn" class="favorite-btn" data-type="destination" data-id="{{ $destination->id }}">
        <i class="fas fa-heart"></i>
        <span id="favorite-text">{{ $isEn ? ($isFavorited ? 'In Favorites' : 'Add to Favorites') : ($isFavorited ? 'في المفضلة' : 'إضافة للمفضلة') }}</span>
    </button>
    @endauth
</div>

<!-- Description -->
<div class="destination-description">
    {{ $destination->description }}
</div>
    
    <!-- الأقسام المخصصة -->
    @php
        $customSections = is_array($destination->custom_sections) ? $destination->custom_sections : (is_string($destination->custom_sections) ? json_decode($destination->custom_sections, true) : []);
    @endphp
    @if(!empty($customSections) && is_array($customSections) && count($customSections) > 0)
    <div style="margin-top: 3rem;">
        @foreach($customSections as $section)
            @if(isset($section['title']) && isset($section['content']) && !empty(trim($section['title'])) && !empty(trim($section['content'])))
            <div style="margin-bottom: 2.5rem;">
                <h2 class="section-title">
                    <i class="fas fa-file-alt"></i>
                    {{ trim($section['title']) }}
                </h2>
                <div class="custom-section-card">
                    <div style="color: #475569; line-height: 1.8; white-space: pre-line; font-size: 1.05rem;">
                        {{ trim($section['content']) }}
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    @endif
    
    @if($destination->latitude && $destination->longitude)
    <div style="margin-top: 3rem; margin-bottom: 3rem;">
        <h2 class="section-title">
            <i class="fas fa-map-marked-alt"></i>
            {{ $isEn ? 'Location on map' : 'الموقع على الخريطة' }}
        </h2>
        <div id="map" class="map-container" data-latitude="{{ $destination->latitude }}" data-longitude="{{ $destination->longitude }}" data-name="{{ $destination->name }}"></div>
    </div>
    @endif

{{-- قسم معلومات الطقس --}}
@if($weather && $destination->latitude && $destination->longitude)
<div style="margin-top: 40px; margin-bottom: 40px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 class="section-title">🌤️ {{ $isEn ? 'Weather Status' : 'حالة الطقس' }}</h2>
        <button id="update-weather-btn" class="btn btn-outline-secondary" data-update-url="{{ route('destinations.weather.update', $destination) }}">
            <i class="fas fa-sync-alt me-2"></i>
            {{ $isEn ? 'Update' : 'تحديث' }}
        </button>
    </div>

    {{-- الطقس الحالي --}}
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 32px; color: white; margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: center;">
            <div>
                <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 8px;">{{ $isEn ? 'Current Weather' : 'الطقس الحالي' }}</div>
                <div style="display: flex; align-items: baseline; gap: 16px; margin-bottom: 16px;">
                    @if($weather->temperature)
                    <div style="font-size: 4rem; font-weight: 700; line-height: 1;">{{ round($weather->temperature) }}°</div>
                    @endif
                    <div style="font-size: 1.2rem; opacity: 0.9;">
                        @php
                            $currentDescription = $weather->description;
                            $hasArabicInCurrentDescription = is_string($currentDescription) && preg_match('/[\x{0600}-\x{06FF}]/u', $currentDescription);
                            $currentConditionFallback = Str::headline(str_replace('_', ' ', (string) ($weather->condition ?? '')));
                            $currentDescriptionText = $isEn && $hasArabicInCurrentDescription
                                ? ($currentConditionFallback !== '' ? $currentConditionFallback : 'Not available')
                                : ($currentDescription ?? ($isEn ? 'Not available' : 'غير متوفر'));
                        @endphp
                        <div style="text-transform: capitalize;">{{ $currentDescriptionText }}</div>
                        @if($weather->feels_like)
                        <div style="font-size: 0.9rem; margin-top: 4px;">{{ $isEn ? 'Feels like' : 'يشعر بـ' }} {{ round($weather->feels_like) }}°</div>
                        @endif
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 16px; margin-top: 24px;">
                    @if($weather->humidity)
                    <div>
                        <div style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 4px;">{{ $isEn ? 'Humidity' : 'الرطوبة' }}</div>
                        <div style="font-size: 1.1rem; font-weight: 600;">{{ $weather->humidity }}%</div>
                    </div>
                    @endif
                    @if($weather->wind_speed)
                    <div>
                        <div style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 4px;">{{ $isEn ? 'Wind' : 'الرياح' }}</div>
                        <div style="font-size: 1.1rem; font-weight: 600;">{{ round($weather->wind_speed) }} {{ $isEn ? 'm/s' : 'م/ث' }}</div>
                        @if($weather->wind_direction)
                        <div style="font-size: 0.8rem; opacity: 0.8;">{{ $weather->wind_direction }}</div>
                        @endif
                    </div>
                    @endif
                    @if($weather->pressure)
                    <div>
                        <div style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 4px;">{{ $isEn ? 'Pressure' : 'الضغط' }}</div>
                        <div style="font-size: 1.1rem; font-weight: 600;">{{ $weather->pressure }} hPa</div>
                    </div>
                    @endif
                    @if($weather->visibility)
                    <div>
                        <div style="font-size: 0.85rem; opacity: 0.8; margin-bottom: 4px;">{{ $isEn ? 'Visibility' : 'الرؤية' }}</div>
                        <div style="font-size: 1.1rem; font-weight: 600;">{{ round($weather->visibility / 1000, 1) }} {{ $isEn ? 'km' : 'كم' }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @if($weather->icon)
            <div style="text-align: center;">
                <img src="https://openweathermap.org/img/wn/{{ $weather->icon }}@4x.png" alt="{{ $isEn ? Str::headline(str_replace('_', ' ', (string) ($weather->condition ?? 'weather'))) : $weather->condition_arabic }}" style="width: 150px; height: 150px;" onerror="this.style.display='none';">
            </div>
            @endif
        </div>
        @if($weather->last_updated)
        <div style="font-size: 0.85rem; opacity: 0.8; margin-top: 16px; text-align: right;">
            {{ $isEn ? 'Last updated:' : 'آخر تحديث:' }} {{ \Carbon\Carbon::parse($weather->last_updated)->locale(app()->getLocale())->diffForHumans() }}
        </div>
        @endif
    </div>

    {{-- التحذيرات --}}
    @if($weather->hasAlerts() && !empty($weather->alerts))
    <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
            <svg width="24" height="24" fill="#dc2626" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
            </svg>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: #991b1b; margin: 0;">⚠️ {{ $isEn ? 'Weather Alerts' : 'تحذيرات الطقس' }}</h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            @foreach($weather->alerts as $alert)
            <div style="background: white; border-radius: 8px; padding: 16px;">
                <div style="font-weight: 600; color: #991b1b; margin-bottom: 8px;">
                    {{ $alert['event'] ?? ($isEn ? 'Alert' : 'تحذير') }}
                </div>
                @if(isset($alert['description']))
                <div style="color: #7f1d1d; line-height: 1.6;">
                    {{ $alert['description'] }}
                </div>
                @endif
                @if(isset($alert['start']) && isset($alert['end']))
                <div style="font-size: 0.9rem; color: #991b1b; margin-top: 8px;">
                    {{ $isEn ? 'From' : 'من' }} {{ \Carbon\Carbon::parse($alert['start'])->locale(app()->getLocale())->translatedFormat('d F Y, H:i') }} 
                    {{ $isEn ? 'To' : 'إلى' }} {{ \Carbon\Carbon::parse($alert['end'])->locale(app()->getLocale())->translatedFormat('d F Y, H:i') }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- التوقعات لـ 7 أيام --}}
    @if(!empty($weather->forecast))
    <div style="background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px;">
        <h3 style="font-size: 1.3rem; font-weight: 700; margin-bottom: 20px; color: #0f172a;">📅 {{ $isEn ? '7-Day Forecast' : 'التوقعات لـ 7 أيام' }}</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 16px;">
            @php
                $forecastGrouped = collect($weather->forecast)->groupBy('date')->take(7);
            @endphp
            @foreach($forecastGrouped as $date => $items)
                @php
                    $dayData = $items->first();
                    $temps = $items->pluck('temperature')->filter();
                @endphp
                <div style="text-align: center; padding: 16px; background: #f8fafc; border-radius: 8px;">
                    <div style="font-size: 0.9rem; color: #64748b; margin-bottom: 8px;">
                        {{ \Carbon\Carbon::parse($date)->locale(app()->getLocale())->translatedFormat('l') }}
                    </div>
                    <div style="font-size: 0.85rem; color: #475569; margin-bottom: 12px;">
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F') }}
                    </div>
                    @if(isset($dayData['icon']) && $dayData['icon'])
                    <img src="https://openweathermap.org/img/wn/{{ $dayData['icon'] }}@2x.png" alt="" style="width: 60px; height: 60px; margin: 8px auto;" onerror="this.style.display='none';">
                    @endif
                    <div style="font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        {{ round($temps->max()) }}° / {{ round($temps->min()) }}°
                    </div>
                    @if(isset($dayData['description']))
                    <div style="font-size: 0.8rem; color: #64748b; margin-top: 8px; text-transform: capitalize;">
                        @php
                            $forecastDescription = $dayData['description'] ?? null;
                            $hasArabicInForecastDescription = is_string($forecastDescription) && preg_match('/[\x{0600}-\x{06FF}]/u', $forecastDescription);
                            $forecastConditionFallback = Str::headline(str_replace('_', ' ', (string) ($dayData['condition'] ?? '')));
                            $forecastDescriptionText = $isEn && $hasArabicInForecastDescription
                                ? ($forecastConditionFallback !== '' ? $forecastConditionFallback : 'Not available')
                                : $forecastDescription;
                        @endphp
                        {{ $forecastDescriptionText }}
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@elseif($destination->latitude && $destination->longitude)
<div style="margin-top: 40px; margin-bottom: 40px; text-align: center; padding: 40px 20px; background: #f8fafc; border-radius: 12px; color: #64748b;">
    <p>{{ $isEn ? 'Weather information cannot be loaded right now. Please try again later.' : 'لا يمكن تحميل معلومات الطقس حالياً. يرجى المحاولة لاحقاً.' }}</p>
    <p style="font-size: 0.9rem; margin-top: 8px; color: #94a3b8;">
        {{ $isEn ? 'Make sure WEATHER_API_KEY is set in your .env file' : 'تأكد من إضافة WEATHER_API_KEY في ملف .env' }}
    </p>
    <button id="update-weather-btn-retry" class="btn btn-primary" data-update-url="{{ route('destinations.weather.update', $destination) }}" style="margin-top: 16px;">
        <i class="fas fa-sync-alt me-2"></i>
        {{ $isEn ? 'Retry Weather Update' : 'محاولة تحديث الطقس' }}
    </button>
</div>
@endif

<!-- حاسبة التكلفة ومولد خط السير -->
<div style="margin-top: 3rem; margin-bottom: 3rem;">
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="custom-section-card" style="height: 100%;">
                <h3 class="section-title" style="font-size: 1.5rem;">
                    <i class="fas fa-calculator"></i>
                    {{ $isEn ? 'Trip Cost Calculator' : 'حاسبة تكلفة الرحلة' }}
                </h3>
                <form id="cost-calculator-form">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'Number of days:' : 'عدد الأيام:' }}</label>
                        <input type="number" id="days-count" class="form-control" min="1" max="30" value="3" style="border-radius: 12px; padding: 0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'Number of people:' : 'عدد الأشخاص:' }}</label>
                        <input type="number" id="people-count" class="form-control" min="1" max="20" value="2" style="border-radius: 12px; padding: 0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'Estimated budget (SYP):' : 'الميزانية المتوقعة (ل.س):' }}</label>
                        <input type="number" id="budget-amount" class="form-control" min="0" placeholder="{{ $isEn ? 'Leave empty for auto calculation' : 'اتركه فارغاً لحساب تلقائي' }}" style="border-radius: 12px; padding: 0.75rem;">
                    </div>
                    <button type="button" onclick="calculateTripCost()" class="btn w-100" style="background: linear-gradient(135deg, #b89ff0 0%, #9d7df0 100%); color: white; border-radius: 12px; padding: 0.75rem; font-weight: 600;">
                        <i class="fas fa-calculator"></i> {{ $isEn ? 'Calculate Cost' : 'احسب التكلفة' }}
                    </button>
                </form>
                <div id="cost-result" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #e5e7eb; display: none;">
                    <h4 style="color: #b89ff0; margin-bottom: 1rem;">{{ $isEn ? 'Calculation Results:' : 'نتائج الحساب:' }}</h4>
                    <div id="cost-details"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="custom-section-card" style="height: 100%;">
                <h3 class="section-title" style="font-size: 1.5rem;">
                    <i class="fas fa-route"></i>
                    {{ $isEn ? 'Smart Itinerary Generator' : 'مولد خط السير الذكي' }}
                </h3>
                <form id="itinerary-generator-form">
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'Number of days:' : 'عدد الأيام:' }}</label>
                        <input type="number" id="itinerary-days" class="form-control" min="1" max="14" value="3" style="border-radius: 12px; padding: 0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'Budget (SYP):' : 'الميزانية (ل.س):' }}</label>
                        <input type="number" id="itinerary-budget" class="form-control" min="0" placeholder="{{ $isEn ? 'Leave empty for all activities' : 'اتركه فارغاً لجميع الأنشطة' }}" style="border-radius: 12px; padding: 0.75rem;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600; color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'Preferred activity types:' : 'نوع الأنشطة المفضلة:' }}</label>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem;">
                            @php
                                $activityTypes = $isEn
                                    ? ['Entertainment', 'Cultural', 'Sports', 'Nature', 'Historical', 'Heritage']
                                    : ['ترفيهي', 'ثقافي', 'رياضي', 'طبيعي', 'تاريخي', 'تراثي'];
                            @endphp
                            @foreach($activityTypes as $type)
                                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem; border-radius: 8px; border: 1px solid #e5e7eb; transition: all 0.3s ease;" 
                                       onmouseover="this.style.borderColor='#8B4513'; this.style.background='#f8fafc'" 
                                       onmouseout="this.style.borderColor='#e5e7eb'; this.style.background='transparent'">
                                    <input type="checkbox" name="interests[]" value="{{ $type }}" class="interest-checkbox" style="cursor: pointer;">
                                    <span style="font-size: 0.9rem;">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="button" onclick="generateItinerary()" class="btn w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; padding: 0.75rem; font-weight: 600;">
                        <i class="fas fa-magic"></i> {{ $isEn ? 'Generate Smart Itinerary' : 'إنشاء خط سير ذكي' }}
                    </button>
                </form>
                <div id="itinerary-result" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #e5e7eb; display: none;">
                    <h4 style="color: #667eea; margin-bottom: 1rem;">{{ $isEn ? 'Trip Itinerary:' : 'خط سير الرحلة:' }}</h4>
                    <div id="itinerary-details"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="margin-top: 3rem;">
    <h2 class="section-title">
        <i class="fas fa-hiking"></i>
        {{ $isEn ? 'Available Activities' : 'الأنشطة المتاحة' }} ({{ $destination->activities->count() }})
    </h2>
    
    @if($destination->activities->count() > 0)
        <div class="activities-grid">
            @foreach($destination->activities as $activity)
                <a href="{{ route('activities.show', $activity) }}" class="activity-card">
                    @if($activity->image)
                        <img 
                            src="{{ $activity->image_url }}" 
                            alt="{{ $activity->name }} - {{ $activity->type }}"
                            class="activity-image"
                            loading="lazy"
                            decoding="async"
                            width="400"
                            height="200"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >
                    @endif
                    <div class="activity-image-placeholder" style="{{ $activity->image ? 'display: none;' : 'display: flex;' }} align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%); height: 200px;">
                        <svg width="60" height="60" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;" aria-hidden="true">
                            <path d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="activity-content">
                        <span class="activity-type">{{ $activity->type }}</span>
                        <h3 class="activity-title">{{ $activity->name }}</h3>
                        <p class="activity-description">{{ Str::limit($activity->description, 100) }}</p>
                        <div class="activity-meta">
                            <div class="activity-rating">
                                <i class="fas fa-star"></i>
                                <span>{{ number_format($activity->rating ?? 0, 1) }}</span>
                            </div>
                            <div class="activity-price">
                                {{ number_format($activity->price, 0) }} {{ $isEn ? 'SYP' : 'ل.س' }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 5rem 2rem; background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <i class="fas fa-hiking" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
            <h3 style="color: #0f172a; margin-bottom: 0.5rem;">{{ $isEn ? 'No activities available' : 'لا توجد أنشطة متاحة' }}</h3>
            <p style="color: #64748b;">{{ $isEn ? 'No activities are currently available for this destination' : 'لا توجد أنشطة متاحة لهذه الوجهة حالياً' }}</p>
        </div>
    @endif
</div>

{{-- قسم الفنادق القريبة --}}
@if($destination->activeHotels && $destination->activeHotels->count() > 0)
<div style="margin-top: 3rem;">
    <h2 class="section-title">
        <i class="fas fa-hotel"></i>
        {{ $isEn ? 'Hotels Available for Booking' : 'فنادق متاحة للحجز' }} ({{ $destination->activeHotels->count() }})
    </h2>
    
    <div class="activities-grid" style="margin-top: 2rem;">
        @foreach($destination->activeHotels as $hotel)
            <a href="{{ route('hotels.show', $hotel) }}" class="activity-card" style="text-decoration: none; color: inherit; cursor: pointer;">
                @if($hotel->image)
                    <img 
                        src="{{ asset('storage/' . $hotel->image) }}" 
                        alt="{{ $hotel->name }} - {{ $destination->name }}"
                        class="activity-image"
                        loading="lazy"
                        decoding="async"
                        width="400"
                        height="200"
                        style="object-fit: cover;"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                @endif
                <div class="activity-image-placeholder" style="{{ $hotel->image ? 'display: none;' : 'display: flex;' }} align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%); height: 200px;">
                    <i class="fas fa-hotel" style="font-size: 3rem; color: #94a3b8;" aria-hidden="true"></i>
                </div>
                <div class="activity-content">
                    <span class="activity-type" style="background: #fbbf24; color: #78350f;">
                        {!! str_repeat('⭐', $hotel->star_rating) !!}
                    </span>
                    <h3 class="activity-title">{{ $hotel->name }}</h3>
                    @if($hotel->description)
                        <p class="activity-description">{{ Str::limit($hotel->description, 100) }}</p>
                    @endif
                    <div class="activity-meta">
                        @if($hotel->price_per_night)
                            <div class="activity-price">
                                {{ number_format($hotel->price_per_night, 0) }} {{ $isEn ? 'SYP / night' : 'ل.س / ليلة' }}
                            </div>
                        @else
                            <div class="activity-price" style="color: #64748b;">
                                {{ $isEn ? 'Call for inquiry' : 'اتصل للاستعلام' }}
                            </div>
                        @endif
                    </div>
                    @if($hotel->address)
                        <div style="margin-top: 0.75rem; font-size: 0.875rem; color: #64748b; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-map-marker-alt" style="font-size: 0.75rem;"></i>
                            <span>{{ Str::limit($hotel->address, 60) }}</span>
                        </div>
                    @endif
                    @php
                        $distance = $hotel->distanceFromDestination($destination);
                    @endphp
                    @if($distance !== null)
                        <div style="margin-top: 0.5rem; font-size: 0.875rem; color: #b89ff0; font-weight: 600;">
                            <i class="fas fa-route"></i> {{ $isEn ? $distance . ' km away from destination' : 'على بعد ' . $distance . ' كم من الوجهة' }}
                        </div>
                    @endif
                    @if($hotel->amenities && count($hotel->amenities) > 0)
                        <div style="margin-top: 0.75rem; display: flex; flex-wrap: gap: 0.5rem;">
                            @foreach(array_slice($hotel->amenities, 0, 3) as $amenity)
                                <span style="font-size: 0.75rem; background: #f1f5f9; color: #475569; padding: 0.25rem 0.5rem; border-radius: 6px;">
                                    {{ $amenity }}
                                </span>
                            @endforeach
                            @if(count($hotel->amenities) > 3)
                                <span style="font-size: 0.75rem; color: #64748b;">
                                    {{ $isEn ? '+' . (count($hotel->amenities) - 3) . ' more' : '+' . (count($hotel->amenities) - 3) . ' أكثر' }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>
@endif

@endsection

