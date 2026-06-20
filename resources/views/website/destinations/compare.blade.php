@extends('website.layouts.app')

@section('title', 'مقارنة الوجهات - الدليل السياحي')

@push('head')
<link rel="stylesheet" href="{{ asset('css/destinations.css') }}">
<style>
    .comparison-container {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .comparison-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2rem;
    }

    .comparison-table th,
    .comparison-table td {
        padding: 1rem;
        text-align: right;
        border-bottom: 1px solid #e5e7eb;
    }

    .comparison-table th {
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        color: white;
        font-weight: 700;
        position: sticky;
        top: 0;
    }

    .comparison-table tr:hover {
        background: #f8fafc;
    }

    .destination-header-cell {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        font-weight: 700;
        color: #0f172a;
        vertical-align: top;
    }

    .destination-image-compare {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .comparison-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        margin: 0.25rem;
    }

    .badge-best {
        background: #10b981;
        color: white;
    }

    .badge-good {
        background: #f59e0b;
        color: white;
    }

    .badge-average {
        background: #64748b;
        color: white;
    }

    .empty-cell {
        color: #94a3b8;
        font-style: italic;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn-compare {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary-compare {
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        color: white;
    }

    .btn-primary-compare:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
        color: white;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #8B4513;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="destination-title">
            <i class="fas fa-balance-scale"></i>
            مقارنة الوجهات
        </h1>
        <a href="{{ route('destinations.index') }}" class="back-link">
            <i class="fas fa-arrow-right"></i>
            <span>العودة إلى الوجهات</span>
        </a>
    </div>

    @if(isset($destinations) && $destinations->count() > 0)
        <div class="comparison-container">
            <div class="comparison-table-wrapper" style="overflow-x: auto;">
                <table class="comparison-table">
                    <thead>
                        <tr>
                            <th style="width: 200px;">المعيار</th>
                            @foreach($destinations as $destination)
                                <th>
                                    <img src="{{ $destination->image_url ?? asset('storage/default-destination.jpg') }}" 
                                         alt="{{ $destination->name }}" 
                                         class="destination-image-compare"
                                         onerror="this.src='{{ asset('storage/default-destination.jpg') }}'">
                                    <div style="margin-top: 0.5rem;">
                                        <strong>{{ $destination->name }}</strong>
                                        <br>
                                        <small style="opacity: 0.8;">{{ $destination->country }}</small>
                                    </div>
                                    <div style="margin-top: 1rem;">
                                        <a href="{{ route('destinations.show', $destination) }}" 
                                           class="btn-compare btn-primary-compare" 
                                           style="width: 100%; justify-content: center;">
                                            <i class="fas fa-eye"></i>
                                            عرض التفاصيل
                                        </a>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- الوصف -->
                        <tr>
                            <td class="destination-header-cell">الوصف</td>
                            @foreach($destinations as $destination)
                                <td>
                                    {{ Str::limit(strip_tags($destination->description), 150) }}
                                </td>
                            @endforeach
                        </tr>

                        <!-- عدد الأنشطة -->
                        <tr>
                            <td class="destination-header-cell">عدد الأنشطة</td>
                            @foreach($destinations as $destination)
                                <td>
                                    @php
                                        $count = $destination->activities->count();
                                        $max = $destinations->max(fn($d) => $d->activities->count());
                                    @endphp
                                    <strong style="font-size: 1.2rem; color: #8B4513;">{{ $count }}</strong>
                                    @if($count == $max && $max > 0)
                                        <span class="comparison-badge badge-best">الأكثر</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <!-- متوسط سعر الأنشطة -->
                        <tr>
                            <td class="destination-header-cell">متوسط سعر الأنشطة</td>
                            @foreach($destinations as $destination)
                                <td>
                                    @php
                                        $avgPrice = $destination->activities->avg('price');
                                        $minPrice = $destinations->min(fn($d) => $d->activities->avg('price') ?: PHP_INT_MAX);
                                    @endphp
                                    @if($avgPrice)
                                        <strong>{{ number_format($avgPrice, 0) }} ل.س</strong>
                                        @if($avgPrice == $minPrice && $minPrice < PHP_INT_MAX)
                                            <span class="comparison-badge badge-best">الأرخص</span>
                                        @endif
                                    @else
                                        <span class="empty-cell">لا توجد أنشطة</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <!-- عدد الفنادق -->
                        <tr>
                            <td class="destination-header-cell">الفنادق المتاحة</td>
                            @foreach($destinations as $destination)
                                <td>
                                    @php
                                        $hotelCount = $destination->activeHotels->count();
                                    @endphp
                                    @if($hotelCount > 0)
                                        <strong style="color: #8B4513;">{{ $hotelCount }}</strong> فندق
                                    @else
                                        <span class="empty-cell">لا توجد فنادق</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <!-- الموقع الجغرافي -->
                        <tr>
                            <td class="destination-header-cell">الموقع الجغرافي</td>
                            @foreach($destinations as $destination)
                                <td>
                                    @if($destination->latitude && $destination->longitude)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <i class="fas fa-map-marker-alt" style="color: #8B4513;"></i>
                                            <span>{{ number_format($destination->latitude, 4) }}, {{ number_format($destination->longitude, 4) }}</span>
                                        </div>
                                        <a href="https://www.google.com/maps?q={{ $destination->latitude }},{{ $destination->longitude }}" 
                                           target="_blank" 
                                           class="btn-compare" 
                                           style="margin-top: 0.5rem; padding: 0.5rem 1rem; font-size: 0.85rem; background: #f1f5f9; color: #475569;">
                                            <i class="fas fa-external-link-alt"></i>
                                            فتح في الخريطة
                                        </a>
                                    @else
                                        <span class="empty-cell">غير متوفر</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <!-- حالة الطقس -->
                        <tr>
                            <td class="destination-header-cell">الطقس الحالي</td>
                            @foreach($destinations as $destination)
                                <td>
                                    @php
                                        $weather = $destination->weather;
                                    @endphp
                                    @if($weather && $weather->temperature)
                                        <div>
                                            <strong style="font-size: 1.5rem; color: #667eea;">
                                                {{ round($weather->temperature) }}°
                                            </strong>
                                            <div style="font-size: 0.9rem; color: #64748b;">
                                                {{ $weather->description ?? 'غير متوفر' }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="empty-cell">غير متوفر</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>

                        <!-- التقييمات -->
                        <tr>
                            <td class="destination-header-cell">التقييم العام</td>
                            @foreach($destinations as $destination)
                                <td>
                                    @php
                                        $avgRating = $destination->activities->avg('rating');
                                        $maxRating = $destinations->max(fn($d) => $d->activities->avg('rating') ?: 0);
                                    @endphp
                                    @if($avgRating)
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <div style="color: #f59e0b; font-size: 1.2rem;">
                                                @for($i = 0; $i < 5; $i++)
                                                    <i class="fas fa-star{{ $i < round($avgRating) ? '' : '-o' }}"></i>
                                                @endfor
                                            </div>
                                            <strong>{{ number_format($avgRating, 1) }}</strong>
                                        </div>
                                        @if($avgRating == $maxRating && $maxRating > 0)
                                            <span class="comparison-badge badge-best" style="margin-top: 0.5rem; display: inline-block;">الأعلى تقييماً</span>
                                        @endif
                                    @else
                                        <span class="empty-cell">لا توجد تقييمات</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- إحصائيات إضافية -->
        <div class="row mt-4">
            @foreach($destinations as $destination)
                @php
                    $colSize = $destinations->count() > 0 ? max(12 / $destinations->count(), 4) : 12;
                    if ($destinations->count() > 3) $colSize = 3;
                @endphp
                <div class="col-md-{{ floor($colSize) }} mb-3">
                    <div class="comparison-container">
                        <h4 style="margin-bottom: 1rem; color: #8B4513;">
                            <i class="fas fa-chart-bar"></i>
                            إحصائيات {{ $destination->name }}
                        </h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $destination->activities->count() }}</div>
                                <div class="stat-label">نشاط</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $destination->activeHotels->count() }}</div>
                                <div class="stat-label">فندق</div>
                            </div>
                            @php
                                $totalPrice = $destination->activities->sum('price');
                            @endphp
                            @if($totalPrice > 0)
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($totalPrice / 1000, 0) }}K</div>
                                    <div class="stat-label">إجمالي الأسعار</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="comparison-container text-center" style="padding: 5rem 2rem;">
            <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #f59e0b; margin-bottom: 1rem;"></i>
            <h3>لا توجد وجهات للمقارنة</h3>
            <p style="color: #64748b; margin-top: 1rem;">يرجى اختيار وجهات للمقارنة</p>
            <a href="{{ route('destinations.index') }}" class="btn-compare btn-primary-compare" style="margin-top: 2rem; display: inline-block;">
                <i class="fas fa-arrow-right"></i>
                تصفح الوجهات
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
// تحديث شريط المقارنة إذا كان موجوداً في الصفحة
if (document.getElementById('comparison-bar')) {
    // إعادة تحميل حالة المقارنة من LocalStorage
    let comparisonDestinations = JSON.parse(localStorage.getItem('comparisonDestinations') || '[]');
    
    // إذا كان هناك وجهات محفوظة، عرض رسالة
    if (comparisonDestinations.length > 0) {
        console.log('يوجد ' + comparisonDestinations.length + ' وجهة محفوظة للمقارنة');
    }
}
</script>
@endpush
@endsection
