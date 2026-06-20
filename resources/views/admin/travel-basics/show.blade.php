@extends('admin.layouts.app')

@section('title', 'تفاصيل أساسيات السفر | لوحة التحكم')

@section('content')
    <div class="breadcrumb-custom">
        <span>🏠 الرئيسية</span>
        <span>›</span>
        <span><a href="{{ route('admin.travel-basics.index') }}" class="text-decoration-none">أساسيات السفر</a></span>
        <span>›</span>
        <span>تفاصيل</span>
    </div>

    <div class="page-header">
        <div>
            <h1>تفاصيل أساسيات السفر: {{ $travelBasic->title }} 🧳</h1>
            <p class="subtitle">نظرة شاملة على بيانات أساسيات السفر</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.travel-basics.edit', $travelBasic) }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> تعديل
            </a>
            <a href="{{ route('admin.travel-basics.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-right"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card card-custom">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">المعلومات الأساسية</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>العنوان:</strong>
                                <div class="mt-1">{{ $travelBasic->title }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>الأيقونة:</strong>
                                <div class="mt-1">
                                    @if($travelBasic->icon)
                                        <i class="{{ $travelBasic->icon }}" style="font-size: 2rem; color: var(--primary);"></i>
                                        <span class="ms-2 text-muted">{{ $travelBasic->icon }}</span>
                                    @else
                                        <span class="text-muted">لا توجد أيقونة</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>الترتيب:</strong>
                                <div class="mt-1">{{ $travelBasic->order }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>الحالة:</strong>
                                <div class="mt-1">
                                    @if($travelBasic->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>تاريخ الإضافة:</strong>
                                <div class="mt-1">{{ $travelBasic->created_at->format('Y-m-d H:i') }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>آخر تحديث:</strong>
                                <div class="mt-1">{{ $travelBasic->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">المحتوى</h5>
                        @if($travelBasic->items && count($travelBasic->items) > 0)
                            <div>
                                <strong>نوع المحتوى:</strong> <span class="badge bg-info">قائمة عناصر</span>
                                <ul class="mt-3" style="list-style: none; padding: 0;">
                                    @foreach($travelBasic->items as $item)
                                        <li class="mb-2" style="padding-right: 1.5rem; position: relative;">
                                            <i class="fas fa-check-circle" style="position: absolute; right: 0; top: 0.25rem; color: var(--primary);"></i>
                                            <span>{{ $item }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @elseif($travelBasic->content)
                            <div>
                                <strong>نوع المحتوى:</strong> <span class="badge bg-secondary">نص حر</span>
                                <div class="mt-3 p-3 bg-light rounded" style="white-space: pre-line; line-height: 1.8;">
                                    {{ $travelBasic->content }}
                                </div>
                            </div>
                        @else
                            <div class="text-muted">لا يوجد محتوى</div>
                        @endif
                    </div>

                    <!-- الأقسام المخصصة -->
                    @php
                        $customSections = is_array($travelBasic->custom_sections) ? $travelBasic->custom_sections : (is_string($travelBasic->custom_sections) ? json_decode($travelBasic->custom_sections, true) : []);
                    @endphp
                    @if(!empty($customSections) && is_array($customSections) && count($customSections) > 0)
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3">الأقسام المخصصة</h5>
                            @foreach($customSections as $index => $section)
                                @if(isset($section['title']) && isset($section['content']) && !empty(trim($section['title'])) && !empty(trim($section['content'])))
                                <div class="card mb-3" style="border-right: 4px solid var(--primary);">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fas fa-file-alt text-primary me-2"></i>
                                            {{ trim($section['title']) }}
                                        </h6>
                                        <div class="text-muted" style="white-space: pre-line; line-height: 1.8;">
                                            {{ trim($section['content']) }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">معاينة</h5>
                    <div class="travel-card-preview" style="background: #fff; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        @if($travelBasic->icon)
                            <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; margin-bottom: 1rem;">
                                <i class="{{ $travelBasic->icon }}"></i>
                            </div>
                        @endif
                        <h3 style="color: var(--primary-dark); margin-bottom: 1rem; font-weight: 700;">{{ $travelBasic->title }}</h3>
                        
                        @if($travelBasic->items && count($travelBasic->items) > 0)
                            <ul style="list-style: none; padding: 0;">
                                @foreach($travelBasic->items as $item)
                                    <li style="padding: 0.5rem 0; padding-right: 1.5rem; position: relative;">
                                        <span style="position: absolute; right: 0; color: var(--primary); font-weight: bold;">✓</span>
                                        <span>{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @elseif($travelBasic->content)
                            <div style="color: #475569; line-height: 1.8; white-space: pre-line;">
                                {{ $travelBasic->content }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



