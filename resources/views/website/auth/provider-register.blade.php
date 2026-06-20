@extends('website.layouts.app')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

@section('title', __('website.auth.provider_register_title'))

@push('head')
<style>
    .auth-page {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
        background: linear-gradient(135deg, #e0f2fe 0%, #fef9c3 100%);
        position: relative;
        overflow: hidden;
    }

    .auth-page::before {
        content: '';
        position: absolute;
        top: -40%;
        left: -40%;
        width: 200%;
        height: 200%;
        background:
            radial-gradient(circle at 0% 0%, rgba(14, 165, 233, 0.18), transparent 60%),
            radial-gradient(circle at 100% 0%, rgba(234, 179, 8, 0.2), transparent 60%),
            radial-gradient(circle at 50% 100%, rgba(59, 130, 246, 0.18), transparent 60%);
        animation: pulse 18s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.05) rotate(180deg); }
    }

    .auth-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 1150px;
        margin: 0 auto;
    }

    .auth-card-wrapper {
        display: grid;
        grid-template-columns: 7fr 5fr;
        gap: 2rem;
        align-items: stretch;
    }

    @media (max-width: 992px) {
        .auth-card-wrapper {
            grid-template-columns: 1fr;
        }
    }

    .auth-form-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 2.5rem 2.5rem 2rem;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.12);
        border: 1px solid rgba(148, 163, 184, 0.25);
        position: relative;
        overflow: hidden;
    }

    .auth-form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #0ea5e9, #22c55e, #eab308);
    }

    .provider-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
        background: #ecfdf3;
        color: #166534;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
    }

    .provider-badge i {
        font-size: 0.95rem;
    }

    .auth-title {
        font-size: 1.75rem;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 0.4rem;
    }

    .auth-subtitle {
        color: var(--text-light);
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.2rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 0.45rem;
        font-size: 0.95rem;
    }

    .form-label i {
        color: #0ea5e9;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control-custom,
    .form-select-custom {
        width: 100%;
        padding: 0.9rem 0.9rem 0.9rem 3rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        font-family: 'Cairo', sans-serif;
    }

    .form-select-custom {
        padding-left: 0.9rem;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        outline: none;
        border-color: #0ea5e9;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
    }

    .input-icon {
        position: absolute;
        right: 0.9rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
    }

    .password-toggle {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.25rem;
    }

    .password-toggle:hover {
        color: #0f172a;
    }

    .file-input {
        padding: 0.7rem 0.8rem;
        border-radius: 12px;
        border: 1px dashed #cbd5e1;
        background: #f9fafb;
        font-size: 0.9rem;
    }

    .file-hint {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 0.35rem;
    }

    .error-message {
        color: #b91c1c;
        font-size: 0.8rem;
        margin-top: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .alert-auth {
        border-radius: 12px;
        padding: 0.9rem 1rem;
        margin-bottom: 1.2rem;
        border: none;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        font-size: 0.9rem;
    }

    .alert-auth-danger {
        background: #fef2f2;
        color: #991b1b;
    }

    .alert-auth-success {
        background: #ecfdf3;
        color: #166534;
    }

    .btn-auth {
        width: 100%;
        padding: 0.9rem 1rem;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #0ea5e9, #22c55e);
        color: #ffffff;
        font-weight: 700;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        box-shadow: 0 10px 30px rgba(34, 197, 94, 0.35);
        cursor: pointer;
        transition: all 0.25s ease;
        margin-top: 0.5rem;
    }

    .btn-auth:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 34px rgba(34, 197, 94, 0.45);
    }

    .btn-auth:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .auth-link {
        text-align: center;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: #64748b;
    }

    .auth-link a {
        color: #0ea5e9;
        text-decoration: none;
        font-weight: 700;
    }

    .auth-link a:hover {
        text-decoration: underline;
    }

    .auth-info-card {
        background: linear-gradient(135deg, #0f172a 0%, #0b1120 60%, #0369a1 100%);
        border-radius: 24px;
        padding: 2.5rem;
        color: #e5e7eb;
        position: relative;
        overflow: hidden;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.4);
    }

    .auth-info-card::before {
        content: '';
        position: absolute;
        inset: -40%;
        background:
            radial-gradient(circle at 0% 0%, rgba(56, 189, 248, 0.35), transparent 55%),
            radial-gradient(circle at 100% 100%, rgba(249, 115, 22, 0.4), transparent 60%);
        opacity: 0.9;
    }

    .auth-info-inner {
        position: relative;
        z-index: 1;
    }

    .info-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.3rem 0.9rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.75);
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
        color: #e5e7eb;
    }

    .info-title {
        font-size: 1.7rem;
        font-weight: 900;
        margin-bottom: 0.6rem;
    }

    .info-description {
        font-size: 0.95rem;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    .info-features {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem;
    }

    .info-features li {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        margin-bottom: 0.7rem;
        font-size: 0.9rem;
    }

    .info-features i {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: rgba(15, 23, 42, 0.8);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .info-note {
        font-size: 0.85rem;
        color: #e5e7eb;
        opacity: 0.9;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    @media (max-width: 768px) {
        .auth-form-card,
        .auth-info-card {
            padding: 1.8rem 1.5rem;
        }

        .auth-title {
            font-size: 1.5rem;
        }

        .info-title {
            font-size: 1.4rem;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card-wrapper">
            <div class="auth-form-card">
                <div class="provider-badge">
                    <i class="fas fa-certificate"></i>
                    {{ __('website.auth.provider_badge') }}
                </div>
                <h1 class="auth-title">{{ __('website.auth.provider_heading') }}</h1>
                <p class="auth-subtitle">
                    {{ __('website.auth.provider_subtitle') }}
                </p>

                @if($errors->any())
                    <div class="alert-auth alert-auth-danger">
                        <i class="fas fa-exclamation-triangle mt-1"></i>
                        <div>
                            <strong>{{ __('website.auth.provider_fix_errors') }}</strong>
                            <ul style="margin: 0.4rem 0 0; padding-right: 1.4rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert-auth alert-auth-success">
                        <i class="fas fa-check-circle mt-1"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @guest
                    <div class="alert-auth alert-auth-danger">
                        <i class="fas fa-lock mt-1"></i>
                        <div>
                            {{ __('website.auth.provider_guest_login_required') }}
                        </div>
                    </div>
                    <a href="{{ route('login') }}" class="btn-auth" style="text-decoration: none;">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('website.auth.provider_login_to_continue') }}</span>
                    </a>
                @endguest

                @auth
                <form id="providerRegisterForm" method="POST" action="{{ route('provider.register.submit') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="alert-auth alert-auth-success">
                        <i class="fas fa-user-check mt-1"></i>
                        <div>
                            {{ $isEn ? 'The request will be submitted using your current account:' : 'سيتم تقديم الطلب باستخدام حسابك الحالي:' }}
                            <strong>{{ auth()->user()->name }}</strong>
                            ({{ auth()->user()->email }}).
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name_preview" class="form-label">
                            <i class="fas fa-user-tie"></i>
                            {{ $isEn ? 'Name from current account' : 'الاسم من الحساب الحالي' }}
                        </label>
                        <div class="input-wrapper">
                            <input
                                id="name_preview"
                                type="text"
                                class="form-control-custom"
                                value="{{ auth()->user()->name }}"
                                readonly
                            >
                            <i class="fas fa-user input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email_preview" class="form-label">
                            <i class="fas fa-envelope"></i>
                            {{ $isEn ? 'Email from current account' : 'البريد الإلكتروني من الحساب الحالي' }}
                        </label>
                        <div class="input-wrapper">
                            <input
                                id="email_preview"
                                type="email"
                                class="form-control-custom"
                                value="{{ auth()->user()->email }}"
                                readonly
                            >
                            <i class="fas fa-at input-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="activity_type" class="form-label">
                            <i class="fas fa-briefcase"></i>
                            {{ $isEn ? 'Tourism Activity Type' : 'نوع النشاط السياحي' }}
                        </label>
                        <select
                            id="activity_type"
                            name="activity_type"
                            class="form-select-custom @error('activity_type') is-invalid @enderror"
                            required
                        >
                            <option value="">{{ $isEn ? 'Select activity type' : 'اختر نوع النشاط' }}</option>
                            <option value="hotel" {{ old('activity_type') === 'hotel' ? 'selected' : '' }}>{{ $isEn ? 'Hotel / Tourist Accommodation' : 'فندق / إقامة سياحية' }}</option>
                            <option value="travel_company" {{ old('activity_type') === 'travel_company' ? 'selected' : '' }}>{{ $isEn ? 'Tourism Company / Travel Agency' : 'شركة سياحية / مكتب سياحة وسفر' }}</option>
                            <option value="event_organizer" {{ old('activity_type') === 'event_organizer' ? 'selected' : '' }}>{{ $isEn ? 'Events and Activities Organizer' : 'منظم فعاليات وأنشطة' }}</option>
                            <option value="tour_guide" {{ old('activity_type') === 'tour_guide' ? 'selected' : '' }}>{{ $isEn ? 'Tour Guide' : 'دليل سياحي' }}</option>
                        </select>
                        <div class="file-hint">
                            {{ $isEn ? 'Choose the category that best matches your business to apply suitable permissions.' : 'اختر التصنيف الأقرب لطبيعة نشاطك ليتم تطبيق الصلاحيات المناسبة.' }}
                        </div>
                        @error('activity_type')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <hr class="my-3">

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-alt"></i>
                            {{ $isEn ? 'Official Documents (optional but recommended for faster approval)' : 'المستندات الرسمية (اختياري لكن يُفضّل رفعها لسرعة الاعتماد)' }}
                        </label>

                        <div class="mb-2">
                            <span style="font-size: 0.85rem; font-weight: 600;">{{ $isEn ? 'Commercial Registration' : 'عقد تسجيل تجاري' }}</span>
                            <input
                                type="file"
                                name="commercial_registration"
                                class="file-input @error('commercial_registration') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png"
                            >
                            <div class="file-hint">
                                {{ $isEn ? 'A document proving business registration (if available).' : 'مستند يثبت تسجيل النشاط التجاري (إن وجد).' }}
                            </div>
                            @error('commercial_registration')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <span style="font-size: 0.85rem; font-weight: 600;">{{ $isEn ? 'Tourism License' : 'ترخيص سياحي' }}</span>
                            <input
                                type="file"
                                name="tourism_license"
                                class="file-input @error('tourism_license') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png"
                            >
                            <div class="file-hint">
                                {{ $isEn ? 'A tourism activity license from the competent authority (if available).' : 'رخصة مزاولة النشاط السياحي من الجهة المختصة (إن وجدت).' }}
                            </div>
                            @error('tourism_license')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <span style="font-size: 0.85rem; font-weight: 600;">{{ $isEn ? 'Ownership or Management Proof' : 'وثيقة تثبت ملكية أو إدارة النشاط' }}</span>
                            <input
                                type="file"
                                name="ownership_document"
                                class="file-input @error('ownership_document') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png"
                            >
                            <div class="file-hint">
                                {{ $isEn ? 'Such as a lease contract, ownership deed, or official authorization.' : 'مثل عقد إيجار، سند ملكية، أو تفويض رسمي.' }}
                            </div>
                            @error('ownership_document')
                                <div class="error-message">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="file-hint">
                            {{ $isEn ? 'Maximum size per file: 2MB - Accepted formats: PDF, JPG, JPEG, PNG.' : 'الحد الأقصى لكل ملف 2 ميجا – صيغ مقبولة: PDF, JPG, JPEG, PNG.' }}
                        </div>
                    </div>

                    <button type="submit" class="btn-auth" id="submitBtn">
                        <i class="fas fa-paper-plane"></i>
                        <span>{{ __('website.auth.provider_submit') }}</span>
                    </button>
                </form>
                @endauth

                <div class="auth-link">
                    @auth
                        {{ $isEn ? 'Want to sign out and use a different account?' : 'تريد تسجيل الخروج بحساب مختلف؟' }} <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ $isEn ? 'Sign out' : 'تسجيل الخروج' }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    @else
                        {{ __('website.auth.provider_have_account') }} <a href="{{ route('login') }}">{{ __('website.auth.provider_go_to_login') }}</a>
                    @endauth
                </div>
            </div>

            <div class="auth-info-card">
                <div class="auth-info-inner">
                    <div class="info-pill">
                        <i class="fas fa-shield-alt"></i>
                        {{ __('website.auth.provider_info_system') }}
                    </div>
                    <h2 class="info-title">{{ __('website.auth.provider_info_title') }}</h2>
                    <p class="info-description">
                        {{ __('website.auth.provider_info_description') }}
                    </p>
                    <ul class="info-features">
                        <li>
                            <i class="fas fa-check"></i>
                            {{ __('website.auth.provider_info_feature_1') }}
                        </li>
                        <li>
                            <i class="fas fa-calendar-alt"></i>
                            {{ __('website.auth.provider_info_feature_2') }}
                        </li>
                        <li>
                            <i class="fas fa-user-shield"></i>
                            {{ __('website.auth.provider_info_feature_3') }}
                        </li>
                        <li>
                            <i class="fas fa-bell"></i>
                            {{ __('website.auth.provider_info_feature_4') }}
                        </li>
                    </ul>
                    <div class="info-note">
                        <i class="fas fa-info-circle mt-1"></i>
                        <div>
                            {{ __('website.auth.provider_info_note') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('providerRegisterForm');
        const submitBtn = document.getElementById('submitBtn');
        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span> {{ __('website.auth.provider_submit_loading') }} </span>';
            });
        }
    });
</script>
@endpush

