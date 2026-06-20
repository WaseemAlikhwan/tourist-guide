@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'Tourist Destinations - Wander Point in Syria' : 'الوجهات السياحية - Wander Point in Syria')

@push('head')
<style>
    /* Hero Section */
    .page-hero {
        position: relative;
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.85) 0%, rgba(34, 139, 34, 0.75) 100%),
                    url('https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1920&q=80') center/cover no-repeat;
        color: white;
        padding: 100px 20px 80px;
        text-align: center;
        border-radius: 24px;
        margin: -80px 0 60px 0;
        overflow: hidden;
    }

    .page-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.3) 0%, transparent 100%);
        z-index: 1;
    }

    .page-hero > * {
        position: relative;
        z-index: 2;
    }

    .page-hero h1 {
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
    }

    .page-hero p {
        font-size: 1.25rem;
        opacity: 0.95;
        max-width: 700px;
        margin: 0 auto;
        text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
    }

    /* Filters Section */
    .filters-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 3rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #0f172a;
        font-size: 0.95rem;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: #8B4513;
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        background: white;
    }

    .filter-btn {
        width: 100%;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 69, 19, 0.3);
    }

    /* Destinations Grid */
    .destinations-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    .destination-card {
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .destination-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
    }

    .destination-image-wrapper {
        position: relative;
        height: 280px;
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
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
        padding: 2rem;
        color: white;
    }

    .destination-title {
        font-size: 1.75rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        color: white;
    }

    .destination-country {
        font-size: 1rem;
        opacity: 0.95;
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
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .destination-stats {
        display: flex;
        gap: 1.5rem;
        color: #64748b;
        font-size: 0.9rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        align-items: center;
    }

    .destination-stats span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Pagination */
    .pagination {
        margin-top: 4rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .pagination a,
    .pagination span {
        padding: 0.75rem 1.25rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        color: #0f172a;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 600;
        min-width: 44px;
        text-align: center;
    }

    .pagination a:hover {
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        color: white;
        border-color: #8B4513;
        transform: translateY(-2px);
    }

    .pagination .active span {
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        color: white;
        border-color: #8B4513;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        color: #64748b;
    }

    .empty-state svg {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #0f172a;
    }

    /* Comparison Features */
    .destination-card-wrapper {
        position: relative;
    }

    @media (max-width: 768px) {
        .page-hero {
            padding: 80px 20px 60px;
            margin: -80px 0 40px 0;
        }

        .page-hero h1 {
            font-size: 2.5rem;
        }

        .page-hero p {
            font-size: 1.1rem;
        }

        .destinations-grid {
            grid-template-columns: 1fr;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <h1>{{ $isEn ? 'Tourist Destinations' : 'الوجهات السياحية' }}</h1>
    <p>{{ $isEn ? 'Discover the most beautiful tourist destinations in Syria' : 'اكتشف أجمل الوجهات السياحية في سوريا' }}</p>
</div>

<!-- Comparison Bar -->
<div class="filters-card" id="comparison-bar" style="display: none; margin-bottom: 1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border: 2px solid #8B4513;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <strong style="color: #8B4513;">
                <i class="fas fa-balance-scale"></i>
                {{ $isEn ? 'Selected destinations for comparison:' : 'الوجهات المختارة للمقارنة:' }}
            </strong>
            <div id="selected-destinations" style="display: flex; gap: 0.5rem; flex-wrap: wrap;"></div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="#" id="compare-btn" class="btn" style="background: #8B4513; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-balance-scale"></i>
                {{ $isEn ? 'Compare' : 'مقارنة' }} (<span id="compare-count">0</span>)
            </a>
            <button type="button" onclick="clearComparison()" class="btn" style="background: #64748b; color: white; padding: 0.75rem 1.5rem; border-radius: 12px; border: none; font-weight: 600;">
                <i class="fas fa-times"></i>
                {{ $isEn ? 'Clear All' : 'مسح الكل' }}
            </button>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('destinations.index') }}" class="filters-card">
    <div class="filters-grid">
        <div class="filter-group">
            <label for="search">
                <i class="fas fa-search"></i> {{ $isEn ? 'Search' : 'البحث' }}
            </label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="{{ $isEn ? 'Search by name or country...' : 'ابحث بالاسم أو البلد...' }}">
        </div>
        <div class="filter-group">
            <label for="country">
                <i class="fas fa-globe"></i> {{ $isEn ? 'Country' : 'البلد' }}
            </label>
            <select name="country" id="country" onchange="this.form.submit()">
                <option value="">{{ $isEn ? 'All Countries' : 'جميع البلدان' }}</option>
                @foreach($countries as $country)
                    <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label for="sort">
                <i class="fas fa-sort"></i> {{ $isEn ? 'Sort' : 'الترتيب' }}
            </label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>{{ $isEn ? 'Latest' : 'الأحدث' }}</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>{{ $isEn ? 'By Name' : 'حسب الاسم' }}</option>
                <option value="activities_count" {{ request('sort') == 'activities_count' ? 'selected' : '' }}>{{ $isEn ? 'Most Activities' : 'الأكثر أنشطة' }}</option>
            </select>
        </div>
        <div class="filter-group" style="display: flex; align-items: flex-end;">
            <button type="submit" class="filter-btn">
                <i class="fas fa-search"></i>
                {{ $isEn ? 'Search' : 'بحث' }}
            </button>
        </div>
    </div>
</form>

<!-- Destinations Grid -->
@if($destinations->count() > 0)
    <div class="destinations-grid">
        @foreach($destinations as $destination)
            <div class="destination-card-wrapper" style="position: relative;">
                <a href="{{ route('destinations.show', $destination) }}" class="destination-card" style="text-decoration: none;">
                <div class="destination-image-wrapper">
                    @if($destination->image)
                        <img src="{{ $destination->image_url }}" alt="{{ $destination->name }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div style="display: none; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%); height: 100%;">
                            <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                <path d="M9 22V12h6v10"></path>
                            </svg>
                        </div>
                    @else
                        <div style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #e5e7eb 0%, #cbd5e1 100%); height: 100%;">
                            <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color: #94a3b8;">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                <path d="M9 22V12h6v10"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="destination-overlay">
                        <h3 class="destination-title">{{ $destination->name }}</h3>
                        <div class="destination-country">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $destination->country }}</span>
                        </div>
                    </div>
                </div>
                <div class="destination-content">
                    <p class="destination-description">{{ $destination->description }}</p>
                    <div class="destination-stats">
                        <span>
                            <i class="fas fa-hiking"></i>
                            {{ $destination->activities->count() }} {{ $isEn ? 'activities' : 'نشاط' }}
                        </span>
                        <span>
                            <i class="fas fa-arrow-left"></i>
                            {{ $isEn ? 'Explore Now' : 'استكشف الآن' }}
                        </span>
                    </div>
                </div>
                </a>
                <!-- Comparison Checkbox -->
                <button type="button" 
                        class="comparison-toggle-btn" 
                        data-destination-id="{{ $destination->id }}"
                        data-destination-name="{{ $destination->name }}"
                        data-destination-image="{{ $destination->image_url ?? '' }}"
                        onclick="toggleComparison(this)"
                        style="position: absolute; top: 10px; left: 10px; z-index: 10; background: white; border: 2px solid #8B4513; padding: 0.5rem; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; transition: all 0.3s ease; cursor: pointer;"
                        onmouseover="this.style.transform='scale(1.1)'; this.style.boxShadow='0 4px 12px rgba(139,69,19,0.4)'" 
                        onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.2)'">
                    <i class="fas fa-balance-scale comparison-icon" style="color: #8B4513;"></i>
                </button>
            </div>
        @endforeach
    </div>

    <div class="pagination">
        {{ $destinations->appends(request()->query())->links() }}
    </div>
@else
    <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
        </svg>
        <h3>{{ $isEn ? 'No destinations available right now' : 'لا توجد وجهات متاحة حالياً' }}</h3>
        <p>{{ $isEn ? 'Try changing the search filters or check back soon for new destinations' : 'جرب تغيير معايير البحث أو سيتم إضافة وجهات جديدة قريباً' }}</p>
    </div>
@endif

@push('scripts')
<script>
// نظام المقارنة
let comparisonDestinations = JSON.parse(localStorage.getItem('comparisonDestinations') || '[]');
const isEnglish = @json($isEn);

function updateComparisonBar() {
    const bar = document.getElementById('comparison-bar');
    const selectedDiv = document.getElementById('selected-destinations');
    const compareBtn = document.getElementById('compare-btn');
    const countSpan = document.getElementById('compare-count');
    
    if (comparisonDestinations.length === 0) {
        bar.style.display = 'none';
        return;
    }
    
    bar.style.display = 'block';
    countSpan.textContent = comparisonDestinations.length;
    
    selectedDiv.innerHTML = comparisonDestinations.map(dest => `
        <div style="background: white; padding: 0.5rem 1rem; border-radius: 8px; display: flex; align-items: center; gap: 0.5rem; border: 2px solid #8B4513;">
            <img src="${dest.image || '{{ asset("storage/default-destination.jpg") }}'}" 
                 alt="${dest.name}" 
                 style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
            <span style="font-weight: 600; color: #0f172a;">${dest.name}</span>
            <button type="button" 
                    onclick="removeFromComparison(${dest.id})" 
                    style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0.25rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `).join('');
    
    // تحديث رابط المقارنة
    const ids = comparisonDestinations.map(d => d.id).join(',');
    compareBtn.href = `{{ route('destinations.compare') }}?destinations[]=${comparisonDestinations.map(d => d.id).join('&destinations[]=')}`;
}

function toggleComparison(button) {
    const id = parseInt(button.dataset.destinationId);
    const name = button.dataset.destinationName;
    const image = button.dataset.destinationImage;
    const icon = button.querySelector('.comparison-icon');
    
    const index = comparisonDestinations.findIndex(d => d.id === id);
    
    if (index === -1) {
        if (comparisonDestinations.length >= 4) {
            alert(isEnglish ? 'You can compare up to 4 destinations only' : 'يمكنك مقارنة حتى 4 وجهات فقط');
            return;
        }
        comparisonDestinations.push({ id, name, image });
        button.style.background = '#8B4513';
        icon.style.color = 'white';
        button.style.borderColor = '#8B4513';
    } else {
        comparisonDestinations.splice(index, 1);
        button.style.background = 'white';
        icon.style.color = '#8B4513';
        button.style.borderColor = '#8B4513';
    }
    
    localStorage.setItem('comparisonDestinations', JSON.stringify(comparisonDestinations));
    updateComparisonBar();
    
    // تحديث حالة جميع الأزرار
    updateAllComparisonButtons();
}

function removeFromComparison(id) {
    comparisonDestinations = comparisonDestinations.filter(d => d.id !== id);
    localStorage.setItem('comparisonDestinations', JSON.stringify(comparisonDestinations));
    updateComparisonBar();
    updateAllComparisonButtons();
}

function clearComparison() {
    if (confirm(isEnglish ? 'Are you sure you want to clear all selected destinations for comparison?' : 'هل أنت متأكد من مسح جميع الوجهات المختارة للمقارنة؟')) {
        comparisonDestinations = [];
        localStorage.removeItem('comparisonDestinations');
        updateComparisonBar();
        updateAllComparisonButtons();
    }
}

function updateAllComparisonButtons() {
    document.querySelectorAll('.comparison-toggle-btn').forEach(button => {
        const id = parseInt(button.dataset.destinationId);
        const icon = button.querySelector('.comparison-icon');
        const isSelected = comparisonDestinations.some(d => d.id === id);
        
        if (isSelected) {
            button.style.background = '#8B4513';
            icon.style.color = 'white';
            button.style.borderColor = '#8B4513';
        } else {
            button.style.background = 'white';
            icon.style.color = '#8B4513';
            button.style.borderColor = '#8B4513';
        }
    });
}

// تهيئة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    updateComparisonBar();
    updateAllComparisonButtons();
});

// منع النقر على زر المقارنة إذا لم يتم اختيار وجهات
document.addEventListener('DOMContentLoaded', function() {
    const compareBtn = document.getElementById('compare-btn');
    if (compareBtn) {
        compareBtn.addEventListener('click', function(e) {
            if (comparisonDestinations.length < 1) {
                e.preventDefault();
                alert(isEnglish ? 'Please select at least one destination for comparison' : 'يرجى اختيار وجهة واحدة على الأقل للمقارنة');
                return false;
            }
            if (comparisonDestinations.length === 1) {
                e.preventDefault();
                alert(isEnglish ? 'Please select another destination for comparison (minimum 2 destinations)' : 'يرجى اختيار وجهة أخرى للمقارنة (الحد الأدنى وجهتين)');
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection
