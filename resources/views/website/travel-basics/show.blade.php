@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', $travelBasic->title . ($isEn ? ' - Travel Basics - Wander Point in Syria' : ' - أساسيات السفر - Wander Point in Syria'))

@push('head')
<style>
    .travel-detail-hero {
        background: linear-gradient(135deg, rgba(139, 69, 19, 0.9) 0%, rgba(160, 82, 45, 0.9) 100%);
        color: white;
        padding: 60px 20px;
        text-align: center;
        border-radius: 24px;
        margin-bottom: 3rem;
    }
    .travel-detail-card {
        background: #fff;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    .travel-detail-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        margin: 0 auto 2rem;
    }
    .travel-detail-title {
        color: var(--primary-dark);
        margin-bottom: 2rem;
        font-weight: 700;
        font-size: 2.5rem;
        text-align: center;
    }
    .travel-detail-content {
        color: #475569;
        line-height: 1.8;
        font-size: 1.1rem;
    }
    .travel-detail-list {
        list-style: none;
        padding: 0;
    }
    .travel-detail-list li {
        padding: 1rem 0;
        padding-right: 2rem;
        position: relative;
        border-bottom: 1px solid #e5e7eb;
        font-size: 1.05rem;
    }
    .travel-detail-list li:last-child {
        border-bottom: none;
    }
    .travel-detail-list li::before {
        content: '✓';
        position: absolute;
        right: 0;
        color: var(--primary);
        font-weight: bold;
        font-size: 1.2rem;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 2rem;
        transition: all 0.2s;
    }
    .back-link:hover {
        color: var(--primary-dark);
        transform: translateX(-5px);
    }
</style>
@endpush

@section('content')
<div class="container">
    <a href="{{ route('travel-basics.index') }}" class="back-link">
        <i class="fas fa-arrow-right"></i>
        {{ $isEn ? 'Back to Travel Basics' : 'العودة إلى أساسيات السفر' }}
    </a>

    <div class="travel-detail-hero">
        @if($travelBasic->icon)
            <div class="travel-detail-icon">
                <i class="{{ $travelBasic->icon }}"></i>
            </div>
        @endif
        <h1 class="display-4 fw-bold mb-3">{{ $travelBasic->title }}</h1>
        <p class="lead">{{ $isEn ? 'Comprehensive and useful information for travelers' : 'معلومات شاملة ومفيدة للمسافرين' }}</p>
    </div>

    <div class="travel-detail-card">
        @if($travelBasic->items && count($travelBasic->items) > 0)
            <ul class="travel-detail-list">
                @foreach($travelBasic->items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        @elseif($travelBasic->content)
            <div class="travel-detail-content" style="white-space: pre-line;">
                {{ $travelBasic->content }}
            </div>
        @else
            <div class="text-center text-muted py-5">
                <p>{{ $isEn ? 'No content available right now' : 'لا يوجد محتوى متاح حالياً' }}</p>
            </div>
        @endif
    </div>

    <!-- الأقسام المخصصة -->
    @php
        $customSections = is_array($travelBasic->custom_sections) ? $travelBasic->custom_sections : (is_string($travelBasic->custom_sections) ? json_decode($travelBasic->custom_sections, true) : []);
    @endphp
    @if(!empty($customSections) && is_array($customSections) && count($customSections) > 0)
        @foreach($customSections as $section)
            @if(isset($section['title']) && isset($section['content']) && !empty(trim($section['title'])) && !empty(trim($section['content'])))
            <div class="travel-detail-card">
                <h2 class="travel-detail-title" style="font-size: 2rem; text-align: right; margin-bottom: 1.5rem; border-bottom: 3px solid var(--primary); padding-bottom: 1rem;">
                    <i class="fas fa-file-alt" style="color: var(--primary); margin-left: 0.5rem;"></i>
                    {{ trim($section['title']) }}
                </h2>
                <div class="travel-detail-content" style="white-space: pre-line;">
                    {{ trim($section['content']) }}
                </div>
            </div>
            @endif
        @endforeach
    @endif

    <div class="text-center mb-5">
        <a href="{{ route('travel-basics.index') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-arrow-right"></i> {{ $isEn ? 'Back to Travel Basics List' : 'العودة إلى قائمة أساسيات السفر' }}
        </a>
    </div>
</div>
@endsection




