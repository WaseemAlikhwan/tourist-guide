@extends('admin.layouts.auth')

@section('title', 'تسجيل دخول الأدمن | Wander Point in Syria')

@section('content')
  <div class="auth-container">
    <div class="brand-logo">
      <i class="bi bi-shield-lock"></i>
    </div>
    <div class="brand-title">لوحة التحكم</div>
    <div class="brand-sub">Wander Point in Syria - نظام إداري آمن</div>

    @if (session('status'))
      <div class="alert alert-info text-center mb-3">
        <i class="bi bi-info-circle me-2"></i>
        {{ session('status') }}
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success text-center mb-3">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger text-center mb-3">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        {{ $errors->first() }}
      </div>
    @endif

    <div class="form-area">
      <h3>تسجيل دخول الأدمن</h3>

      <form action="{{ route('admin.login.submit') }}"
            method="POST"
            class="auth-form d-grid gap-3"
            novalidate>
        @csrf

        <div class="form-group">
          <label for="email">
            <i class="bi bi-envelope me-1"></i>
            البريد الإلكتروني
          </label>
          <div class="input-wrapper">
            <input
              id="email"
              type="email"
              name="email"
              class="form-control @error('email') is-invalid @enderror"
              placeholder="admin@tourist.com"
              value="{{ old('email') }}"
              autocomplete="username"
              required
            >
            <i class="bi bi-envelope input-icon"></i>
            @error('email')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="form-group">
          <label for="password">
            <i class="bi bi-lock me-1"></i>
            كلمة المرور
          </label>
          <div class="input-wrapper">
            <input
              id="password"
              type="password"
              name="password"
              class="form-control @error('password') is-invalid @enderror"
              placeholder="••••••••"
              autocomplete="current-password"
              required
            >
            <button class="toggle-pass" type="button" aria-label="إظهار/إخفاء كلمة المرور">
              <i class="bi bi-eye"></i>
            </button>
            <i class="bi bi-lock input-icon"></i>
            @error('password')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center">
          <div class="form-check">
            <input
              class="form-check-input"
              type="checkbox"
              name="remember"
              value="1"
              id="remember"
              {{ old('remember') ? 'checked' : '' }}
            >
            <label class="form-check-label" for="remember">
              تذكرني
            </label>
          </div>
          <a href="#" class="forgot">نسيت كلمة المرور؟</a>
        </div>

        <button type="submit" class="btn-brand">
          <span>
            <i class="bi bi-box-arrow-in-right me-2"></i>
            تسجيل الدخول
          </span>
        </button>
      </form>

      <div class="security-badge">
        <i class="bi bi-shield-check"></i>
        <span>اتصال آمن ومشفر</span>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  (function () {
    const toggleBtn = document.querySelector('.toggle-pass');
    const passwordInput = document.getElementById('password');

    if (toggleBtn && passwordInput) {
      toggleBtn.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleBtn.querySelector('i')?.classList.toggle('bi-eye');
        toggleBtn.querySelector('i')?.classList.toggle('bi-eye-slash');
      });
    }
  })();
</script>
@endpush
