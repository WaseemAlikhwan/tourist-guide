@extends('website.layouts.app')

@section('title', 'اتصل بنا - Wander Point in Syria')

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

    .contact-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .contact-form {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        margin-bottom: 3rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #0f172a;
        font-size: 0.95rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8fafc;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #8B4513;
        box-shadow: 0 0 0 3px rgba(139, 69, 19, 0.1);
        background: white;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .required {
        color: #ef4444;
    }

    .submit-btn {
        width: 100%;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 69, 19, 0.3);
    }

    .contact-info {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }

    .contact-info h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .contact-info h2 i {
        color: #8B4513;
    }

    .contact-info-item {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 16px;
        transition: all 0.3s ease;
    }

    .contact-info-item:hover {
        background: #f1f5f9;
        transform: translateX(-5px);
    }

    .contact-info-item:last-child {
        margin-bottom: 0;
    }

    .contact-info-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        color: white;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1.5rem;
    }

    .contact-info-content h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #0f172a;
    }

    .contact-info-content p {
        color: #64748b;
        margin: 0;
        font-size: 1rem;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.9rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
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

        .contact-form,
        .contact-info {
            padding: 2rem 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Page Hero -->
<div class="page-hero">
    <h1>اتصل بنا</h1>
    <p>نحن هنا لمساعدتك! تواصل معنا في أي وقت</p>
</div>

<div class="contact-container">
    <!-- Contact Form -->
    <form method="POST" action="{{ route('contact.store') }}" class="contact-form">
        @csrf

        <div class="form-group">
            <label for="name">
                <i class="fas fa-user"></i> الاسم الكامل <span class="required">*</span>
            </label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="أدخل اسمك الكامل">
            @error('name')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">
                <i class="fas fa-envelope"></i> البريد الإلكتروني <span class="required">*</span>
            </label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required placeholder="example@email.com">
            @error('email')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="subject">
                <i class="fas fa-tag"></i> الموضوع
            </label>
            <input type="text" id="subject" name="subject" value="{{ old('subject') }}" placeholder="موضوع الرسالة (اختياري)">
            @error('subject')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="message">
                <i class="fas fa-comment"></i> الرسالة <span class="required">*</span>
            </label>
            <textarea id="message" name="message" rows="6" required placeholder="اكتب رسالتك هنا...">{{ old('message') }}</textarea>
            @error('message')
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i>
            إرسال الرسالة
        </button>
    </form>

    <!-- Contact Info -->
    <div class="contact-info">
        <h2>
            <i class="fas fa-info-circle"></i>
            معلومات الاتصال
        </h2>
        
        <div class="contact-info-item">
            <div class="contact-info-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <div class="contact-info-content">
                <h3>العنوان</h3>
                <p>سوريا</p>
            </div>
        </div>

        <div class="contact-info-item">
            <div class="contact-info-icon">
                <i class="fas fa-phone"></i>
            </div>
            <div class="contact-info-content">
                <h3>الهاتف</h3>
                <p>+963 XXX XXX XXX</p>
            </div>
        </div>

        <div class="contact-info-item">
            <div class="contact-info-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="contact-info-content">
                <h3>البريد الإلكتروني</h3>
                <p>info@tourist-guide.com</p>
            </div>
        </div>
    </div>
</div>
@endsection
