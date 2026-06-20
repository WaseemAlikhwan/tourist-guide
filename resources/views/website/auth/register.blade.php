@extends('website.layouts.app')

@section('title', 'إنشاء حساب')

@push('head')
<style>
    .auth-page {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
        position: relative;
        overflow: hidden;
    }

    .auth-page::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(139, 69, 19, 0.05) 0%, transparent 70%);
        animation: pulse 15s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1) rotate(0deg); }
        50% { transform: scale(1.1) rotate(180deg); }
    }

    .auth-container {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .auth-card-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: stretch;
    }

    @media (max-width: 992px) {
        .auth-card-wrapper {
            grid-template-columns: 1fr;
        }
    }

    .auth-form-card {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: all 0.3s ease;
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
        background: linear-gradient(90deg, #8B4513 0%, #DEB887 50%, #8B4513 100%);
    }

    .auth-form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
    }

    .auth-info-card {
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        border-radius: 24px;
        padding: 3rem;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(139, 69, 19, 0.3);
    }

    .auth-info-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }

    .auth-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #8B4513 0%, #DEB887 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(139, 69, 19, 0.3);
        position: relative;
        z-index: 1;
    }

    .auth-logo i {
        font-size: 2.5rem;
        color: white;
    }

    .auth-title {
        font-size: 2rem;
        font-weight: 900;
        color: var(--text);
        margin-bottom: 0.5rem;
        text-align: center;
        position: relative;
    }

    .auth-subtitle {
        color: var(--text-light);
        text-align: center;
        margin-bottom: 2rem;
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--text);
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .form-label i {
        color: var(--primary);
        font-size: 1.1rem;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control-custom {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8fafc;
        color: var(--text);
        font-family: 'Cairo', sans-serif;
    }

    .form-control-custom:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(139, 69, 19, 0.1);
        transform: translateY(-2px);
    }

    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        font-size: 1.1rem;
        transition: all 0.3s ease;
        pointer-events: none;
    }

    .form-control-custom:focus + .input-icon {
        color: var(--primary);
    }

    .password-toggle {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--text-light);
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        color: var(--primary);
    }

    .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .password-strength-bar.weak {
        width: 33%;
        background: #ef4444;
    }

    .password-strength-bar.medium {
        width: 66%;
        background: #f59e0b;
    }

    .password-strength-bar.strong {
        width: 100%;
        background: #10b981;
    }

    .password-strength-text {
        font-size: 0.75rem;
        color: var(--text-light);
        margin-top: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .password-requirements {
        margin-top: 0.75rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 8px;
        font-size: 0.85rem;
        color: var(--text-light);
    }

    .password-requirements ul {
        margin: 0;
        padding-right: 1.5rem;
        list-style: none;
    }

    .password-requirements li {
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .password-requirements li i {
        font-size: 0.75rem;
        transition: all 0.3s ease;
    }

    .password-requirements li.valid {
        color: #10b981;
    }

    .password-requirements li.valid i {
        color: #10b981;
    }

    .password-requirements li.invalid {
        color: var(--text-light);
    }

    .btn-auth {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(139, 69, 19, 0.3);
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-auth::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-auth:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 69, 19, 0.4);
    }

    .btn-auth:active {
        transform: translateY(0);
    }

    .btn-auth:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .auth-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 1.5rem 0;
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border);
    }

    .social-auth-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .btn-social {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 0.75rem;
        text-decoration: none;
        color: var(--text);
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-weight: 700;
        transition: all 0.2s ease;
    }

    .btn-social:hover {
        border-color: var(--primary-dark);
        color: var(--primary-dark);
        transform: translateY(-2px);
    }

    .auth-link {
        text-align: center;
        margin-top: 1.5rem;
        color: var(--text-light);
        font-size: 0.95rem;
    }

    .auth-link a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .auth-link a::after {
        content: '';
        position: absolute;
        bottom: -2px;
        right: 0;
        width: 0;
        height: 2px;
        background: var(--primary);
        transition: width 0.3s ease;
    }

    .auth-link a:hover::after {
        width: 100%;
    }

    .auth-link a:hover {
        color: var(--primary-dark);
    }

    .info-title {
        font-size: 1.75rem;
        font-weight: 900;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .info-description {
        font-size: 1.05rem;
        line-height: 1.8;
        opacity: 0.95;
        margin-bottom: 2rem;
        position: relative;
        z-index: 1;
    }

    .info-features {
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .info-features li {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 1rem;
        opacity: 0.95;
    }

    .info-features li i {
        font-size: 1.3rem;
        background: rgba(255, 255, 255, 0.2);
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .error-message {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-auth {
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 500;
    }

    .alert-auth-danger {
        background: #fee2e2;
        color: #991b1b;
    }

    .alert-auth-success {
        background: #d1fae5;
        color: #065f46;
    }

    .alert-auth-info {
        background: #dbeafe;
        color: #1e40af;
    }

    @media (max-width: 768px) {
        .auth-form-card,
        .auth-info-card {
            padding: 2rem 1.5rem;
        }

        .auth-title {
            font-size: 1.75rem;
        }

        .info-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card-wrapper">
            <!-- Form Card -->
            <div class="auth-form-card">
                <div class="auth-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="auth-title">إنشاء حساب جديد</h1>
                <p class="auth-subtitle">انضم إلينا واكتشف جمال سوريا</p>

                @if($errors->any())
                    <div class="alert-auth alert-auth-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <strong>حدثت الأخطاء التالية:</strong>
                            <ul style="margin: 0.5rem 0 0 0; padding-right: 1.5rem;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert-auth alert-auth-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i>
                            الاسم الكامل
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="name" 
                                type="text" 
                                name="name" 
                                class="form-control-custom @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" 
                                required
                                autofocus
                                placeholder="أدخل اسمك الكامل"
                            >
                            <i class="fas fa-user input-icon"></i>
                        </div>
                        @error('name')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i>
                            البريد الإلكتروني
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                class="form-control-custom @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" 
                                required
                                placeholder="أدخل بريدك الإلكتروني"
                            >
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i>
                            كلمة المرور
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                class="form-control-custom @error('password') is-invalid @enderror"
                                required
                                placeholder="أدخل كلمة المرور"
                            >
                            <button type="button" class="password-toggle" id="togglePassword" aria-label="إظهار/إخفاء كلمة المرور">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        <div class="password-strength" id="passwordStrength" style="display: none;">
                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                        </div>
                        <div class="password-strength-text" id="passwordStrengthText" style="display: none;"></div>
                        <div class="password-requirements" id="passwordRequirements">
                            <ul>
                                <li id="req-length" class="invalid">
                                    <i class="fas fa-circle"></i>
                                    <span>8 أحرف على الأقل</span>
                                </li>
                                <li id="req-upper" class="invalid">
                                    <i class="fas fa-circle"></i>
                                    <span>حرف كبير واحد على الأقل</span>
                                </li>
                                <li id="req-lower" class="invalid">
                                    <i class="fas fa-circle"></i>
                                    <span>حرف صغير واحد على الأقل</span>
                                </li>
                                <li id="req-number" class="invalid">
                                    <i class="fas fa-circle"></i>
                                    <span>رقم واحد على الأقل</span>
                                </li>
                            </ul>
                        </div>
                        @error('password')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock"></i>
                            تأكيد كلمة المرور
                        </label>
                        <div class="input-wrapper">
                            <input 
                                id="password_confirmation" 
                                type="password" 
                                name="password_confirmation" 
                                class="form-control-custom @error('password_confirmation') is-invalid @enderror"
                                required
                                placeholder="أعد إدخال كلمة المرور"
                            >
                            <button type="button" class="password-toggle" id="togglePasswordConfirm" aria-label="إظهار/إخفاء كلمة المرور">
                                <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                            </button>
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        <div id="passwordMatch" style="display: none; margin-top: 0.5rem; font-size: 0.875rem;"></div>
                        @error('password_confirmation')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-auth" id="submitBtn">
                        <i class="fas fa-user-plus"></i>
                        <span>إنشاء الحساب</span>
                    </button>
                </form>

                <div class="auth-divider">أو أكمل عبر</div>
                <div class="social-auth-grid">
                    <a href="{{ route('auth.social.redirect', 'google') }}" class="btn-social">
                        <i class="fab fa-google"></i>
                        <span>Google</span>
                    </a>
                    <a href="{{ route('auth.social.redirect', 'facebook') }}" class="btn-social">
                        <i class="fab fa-facebook-f"></i>
                        <span>Facebook</span>
                    </a>
                </div>

                <div class="auth-link">
                    لديك حساب بالفعل؟ <a href="{{ route('login') }}">سجّل دخول</a>
                </div>
            </div>

            <!-- Info Card -->
            <div class="auth-info-card">
                <h2 class="info-title">انضم إلى Wander Point in Syria</h2>
                <p class="info-description">
                    أنشئ حساباً جديداً للاستمتاع بتجربة سياحية متميزة واكتشاف أفضل الوجهات في سوريا
                </p>
                <ul class="info-features">
                    <li>
                        <i class="fas fa-gift"></i>
                        <span>انضم مجاناً بدون رسوم</span>
                    </li>
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <span>حساب آمن ومحمي</span>
                    </li>
                    <li>
                        <i class="fas fa-compass"></i>
                        <span>استكشف المئات من الوجهات</span>
                    </li>
                    <li>
                        <i class="fas fa-bookmark"></i>
                        <span>احفظ مفضلتك في مكان واحد</span>
                    </li>
                    <li>
                        <i class="fas fa-ticket-alt"></i>
                        <span>احجز الأنشطة بسهولة</span>
                    </li>
                    <li>
                        <i class="fas fa-bell"></i>
                        <span>احصل على عروض حصرية</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');
        const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
        const togglePasswordConfirmIcon = document.getElementById('togglePasswordConfirmIcon');
        const passwordStrength = document.getElementById('passwordStrength');
        const passwordStrengthBar = document.getElementById('passwordStrengthBar');
        const passwordStrengthText = document.getElementById('passwordStrengthText');
        const passwordRequirements = document.getElementById('passwordRequirements');
        const passwordMatch = document.getElementById('passwordMatch');
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('registerForm');

        // Toggle password visibility
        function setupPasswordToggle(toggleBtn, toggleIcon, input) {
            if (toggleBtn && toggleIcon && input) {
                toggleBtn.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    
                    if (type === 'password') {
                        toggleIcon.classList.remove('fa-eye-slash');
                        toggleIcon.classList.add('fa-eye');
                    } else {
                        toggleIcon.classList.remove('fa-eye');
                        toggleIcon.classList.add('fa-eye-slash');
                    }
                });
            }
        }

        setupPasswordToggle(togglePassword, togglePasswordIcon, passwordInput);
        setupPasswordToggle(togglePasswordConfirm, togglePasswordConfirmIcon, passwordConfirmInput);

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const checks = {
                length: password.length >= 8,
                upper: /[A-Z]/.test(password),
                lower: /[a-z]/.test(password),
                number: /\d/.test(password)
            };

            // Update requirement indicators
            updateRequirement('req-length', checks.length);
            updateRequirement('req-upper', checks.upper);
            updateRequirement('req-lower', checks.lower);
            updateRequirement('req-number', checks.number);

            // Calculate strength
            if (checks.length) strength++;
            if (checks.upper) strength++;
            if (checks.lower) strength++;
            if (checks.number) strength++;

            // Update strength bar
            if (password.length > 0) {
                passwordStrength.style.display = 'block';
                passwordStrengthText.style.display = 'block';
                
                if (strength <= 1) {
                    passwordStrengthBar.className = 'password-strength-bar weak';
                    passwordStrengthText.innerHTML = '<i class="fas fa-exclamation-circle"></i> كلمة مرور ضعيفة';
                    passwordStrengthText.style.color = '#ef4444';
                } else if (strength <= 2) {
                    passwordStrengthBar.className = 'password-strength-bar medium';
                    passwordStrengthText.innerHTML = '<i class="fas fa-exclamation-triangle"></i> كلمة مرور متوسطة';
                    passwordStrengthText.style.color = '#f59e0b';
                } else {
                    passwordStrengthBar.className = 'password-strength-bar strong';
                    passwordStrengthText.innerHTML = '<i class="fas fa-check-circle"></i> كلمة مرور قوية';
                    passwordStrengthText.style.color = '#10b981';
                }
            } else {
                passwordStrength.style.display = 'none';
                passwordStrengthText.style.display = 'none';
            }

            return strength >= 3;
        }

        function updateRequirement(id, valid) {
            const element = document.getElementById(id);
            if (element) {
                if (valid) {
                    element.classList.remove('invalid');
                    element.classList.add('valid');
                    const icon = element.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fa-circle');
                        icon.classList.add('fa-check-circle');
                    }
                } else {
                    element.classList.remove('valid');
                    element.classList.add('invalid');
                    const icon = element.querySelector('i');
                    if (icon) {
                        icon.classList.remove('fa-check-circle');
                        icon.classList.add('fa-circle');
                    }
                }
            }
        }

        // Check password match
        function checkPasswordMatch() {
            const password = passwordInput.value;
            const confirm = passwordConfirmInput.value;

            if (confirm.length > 0) {
                passwordMatch.style.display = 'block';
                if (password === confirm) {
                    passwordMatch.innerHTML = '<i class="fas fa-check-circle" style="color: #10b981;"></i> <span style="color: #10b981;">كلمة المرور متطابقة</span>';
                    passwordConfirmInput.style.borderColor = '#10b981';
                } else {
                    passwordMatch.innerHTML = '<i class="fas fa-times-circle" style="color: #ef4444;"></i> <span style="color: #ef4444;">كلمة المرور غير متطابقة</span>';
                    passwordConfirmInput.style.borderColor = '#ef4444';
                }
            } else {
                passwordMatch.style.display = 'none';
                passwordConfirmInput.style.borderColor = '';
            }
        }

        // Event listeners
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                checkPasswordMatch();
            });
        }

        if (passwordConfirmInput) {
            passwordConfirmInput.addEventListener('input', checkPasswordMatch);
        }

        // Form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirm = passwordConfirmInput.value;

                if (password !== confirm) {
                    e.preventDefault();
                    alert('كلمة المرور غير متطابقة!');
                    return false;
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>جاري إنشاء الحساب...</span>';
                }
            });
        }
    });
</script>
@endpush
