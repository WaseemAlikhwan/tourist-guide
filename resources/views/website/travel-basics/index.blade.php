@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $isEn ? 'Travel Basics to Syria - Wander Point in Syria' : 'أساسيات السفر إلى سوريا - Wander Point in Syria')

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

    .travel-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 2rem;
    }

    .travel-card {
        background: white;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
        border: 1px solid #e5e7eb;
    }

    .travel-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        border-color: #b89ff0;
    }

    .travel-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #b89ff0 0%, #9d7df0 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
        margin-bottom: 1.5rem;
    }

    .travel-card h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #0f172a;
    }

    .travel-card ul {
        list-style: none;
        padding: 0;
        margin: 0;
        flex: 1;
    }

    .travel-card ul li {
        padding: 0.75rem 0;
        padding-right: 2rem;
        position: relative;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
    }

    .travel-card ul li:last-child {
        border-bottom: none;
    }

    .travel-card ul li::before {
        content: '✓';
        position: absolute;
        right: 0;
        color: #b89ff0;
        font-weight: bold;
        font-size: 1.1rem;
    }

    .travel-card-content {
        color: #475569;
        line-height: 1.8;
        white-space: pre-line;
        max-height: 200px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .travel-card-footer {
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #b89ff0;
        font-weight: 600;
    }

    .custom-sections-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: rgba(215, 203, 249, 0.2);
        border-radius: 50px;
        font-size: 0.85rem;
        color: #b89ff0;
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 24px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .empty-state i {
        font-size: 5rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #0f172a;
    }

    .empty-state p {
        color: #64748b;
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

        .travel-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <h1>{{ $isEn ? 'Travel Basics to Syria' : 'أساسيات السفر إلى سوريا' }}</h1>
    <p>{{ $isEn ? 'A complete guide to everything you need before traveling' : 'دليل شامل لكل ما تحتاج معرفته قبل السفر' }}</p>
</div>

@if($travelBasics->count() > 0)
    <div class="travel-grid">
        @foreach($travelBasics as $basic)
            <a href="{{ route('travel-basics.show', $basic) }}" class="travel-card">
                @if($basic->icon)
                    <div class="travel-icon">
                        <i class="{{ $basic->icon }}"></i>
                    </div>
                @endif
                <h3>{{ $basic->title }}</h3>
                
                @if($basic->items && count($basic->items) > 0)
                    <ul>
                        @foreach(array_slice($basic->items, 0, 4) as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                    @if(count($basic->items) > 4)
                        <div class="travel-card-footer">
                            <span>
                                <i class="fas fa-arrow-left"></i>
                                {{ $isEn ? 'View More' : 'عرض المزيد' }} ({{ count($basic->items) - 4 }} {{ $isEn ? 'more items' : 'عنصر إضافي' }})
                            </span>
                        </div>
                    @endif
                @elseif($basic->content)
                    <div class="travel-card-content">
                        {{ Str::limit($basic->content, 200) }}
                    </div>
                    <div class="travel-card-footer">
                        <span>
                            <i class="fas fa-arrow-left"></i>
                            {{ $isEn ? 'View More' : 'عرض المزيد' }}
                        </span>
                    </div>
                @endif
                
                @php
                    $customSections = is_array($basic->custom_sections) ? $basic->custom_sections : (is_string($basic->custom_sections) ? json_decode($basic->custom_sections, true) : []);
                    $hasCustomSections = !empty($customSections) && is_array($customSections) && count($customSections) > 0;
                @endphp
                @if($hasCustomSections)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #e5e7eb;">
                        <span class="custom-sections-badge">
                            <i class="fas fa-file-alt"></i>
                            {{ count($customSections) }} {{ $isEn ? 'Additional Sections' : 'قسم إضافي' }}
                        </span>
                    </div>
                @endif
            </a>
        @endforeach
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-suitcase-rolling"></i>
        <h3>{{ $isEn ? 'No travel basics available right now' : 'لا توجد معلومات أساسيات السفر حالياً' }}</h3>
        <p>{{ $isEn ? 'Content will be added soon' : 'سيتم إضافة المحتوى قريباً' }}</p>
    </div>
@endif
@endsection
