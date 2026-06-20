<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'تسجيل دخول الأدمن | Wander Point in Syria')</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary: #0ea5e9;
      --primary-dark: #075985;
      --accent: #6366f1;
      --accent-light: #818cf8;
      --bg-dark: #0f172a;
      --text-dark: #0f172a;
      --text-light: #64748b;
      --radius: 24px;
      --success: #10b981;
    }

    html {
      height: 100%;
      overflow-x: hidden;
    }

    * {
      font-family: "Cairo", sans-serif;
      box-sizing: border-box;
    }

    body {
      min-height: 100vh;
      height: 100%;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background:
        radial-gradient(circle at 75% 25%, rgba(99, 102, 241, 0.3), transparent 45%),
        radial-gradient(circle at 25% 75%, rgba(14, 165, 233, 0.35), transparent 45%),
        radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.15), transparent 50%),
        linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
      overflow-x: hidden;
      overflow-y: auto;
      position: relative;
      padding: 20px;
    }

    .auth-content {
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .glow {
      position: fixed;
      width: 700px;
      height: 700px;
      border-radius: 50%;
      filter: blur(120px);
      animation: float 12s ease-in-out infinite alternate;
      opacity: 0.6;
      pointer-events: none;
    }

    .glow:nth-of-type(1) {
      top: -200px;
      right: -250px;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.4), transparent 70%);
      animation-delay: 0s;
    }

    .glow:nth-of-type(2) {
      bottom: -250px;
      left: -200px;
      background: radial-gradient(circle, rgba(14, 165, 233, 0.4), transparent 70%);
      animation-delay: 2s;
    }

    .glow:nth-of-type(3) {
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(139, 92, 246, 0.25), transparent 70%);
      animation-delay: 4s;
    }

    @keyframes float {
      0% {
        transform: translateY(0) translateX(0) scale(1);
        opacity: 0.5;
      }
      50% {
        opacity: 0.7;
      }
      100% {
        transform: translateY(-30px) translateX(20px) scale(1.1);
        opacity: 0.6;
      }
    }

    .particles {
      position: fixed;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      overflow: hidden;
      z-index: 1;
      pointer-events: none;
    }

    .particle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: rgba(255, 255, 255, 0.5);
      border-radius: 50%;
      animation: particleFloat 15s infinite linear;
    }

    @keyframes particleFloat {
      0% {
        transform: translateY(100vh) translateX(0);
        opacity: 0;
      }
      10% {
        opacity: 1;
      }
      90% {
        opacity: 1;
      }
      100% {
        transform: translateY(-100vh) translateX(100px);
        opacity: 0;
      }
    }

    .auth-container {
      position: relative;
      z-index: 10;
      width: min(460px, 95vw);
      max-width: 100%;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(20px) saturate(180%);
      -webkit-backdrop-filter: blur(20px) saturate(180%);
      border: 1px solid rgba(255, 255, 255, 0.18);
      border-radius: var(--radius);
      padding: 48px 40px;
      box-shadow:
        0 8px 32px rgba(0, 0, 0, 0.4),
        inset 0 1px 0 rgba(255, 255, 255, 0.2);
      text-align: center;
      animation: fadeInUp 1s ease forwards;
      overflow: visible;
      margin: auto;
    }

    .auth-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg,
        transparent,
        var(--primary),
        var(--accent),
        transparent
      );
      animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
      0% {
        transform: translateX(-100%);
      }
      100% {
        transform: translateX(100%);
      }
    }

    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(40px) scale(0.95);
      }
      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    .brand-logo {
      width: 90px;
      height: 90px;
      background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
      border-radius: 24px;
      display: grid;
      place-items: center;
      font-size: 42px;
      color: #fff;
      margin: 0 auto 20px;
      box-shadow:
        0 10px 30px rgba(14, 165, 233, 0.5),
        0 0 0 0 rgba(14, 165, 233, 0.7);
      animation: logoPulse 3s ease-in-out infinite;
      position: relative;
      overflow: hidden;
    }

    .brand-logo::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.3),
        transparent
      );
      animation: logoShine 3s infinite;
    }

    @keyframes logoPulse {
      0%,
      100% {
        box-shadow:
          0 10px 30px rgba(14, 165, 233, 0.5),
          0 0 0 0 rgba(14, 165, 233, 0.7);
      }
      50% {
        box-shadow:
          0 10px 40px rgba(14, 165, 233, 0.6),
          0 0 0 10px rgba(14, 165, 233, 0);
      }
    }

    @keyframes logoShine {
      0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
      }
      100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
      }
    }

    .brand-logo i {
      position: relative;
      z-index: 1;
      animation: iconRotate 8s linear infinite;
    }

    @keyframes iconRotate {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }

    .brand-title {
      color: #fff;
      font-weight: 900;
      font-size: 32px;
      letter-spacing: 1.5px;
      margin-bottom: 6px;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      background: linear-gradient(135deg, #fff 0%, #e0e7ff 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .brand-sub {
      color: #cbd5e1;
      margin-bottom: 32px;
      font-size: 16px;
      font-weight: 500;
      letter-spacing: 0.5px;
    }

    .form-area {
      background: rgba(255, 255, 255, 0.98);
      border-radius: var(--radius);
      padding: 38px 32px;
      box-shadow:
        0 25px 60px rgba(14, 165, 233, 0.2),
        inset 0 1px 0 rgba(255, 255, 255, 1);
      text-align: right;
      position: relative;
      overflow: visible;
      width: 100%;
    }

    .form-area::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
    }

    .form-area h3 {
      font-weight: 800;
      font-size: 24px;
      margin-bottom: 24px;
      color: var(--text-dark);
      text-align: center;
      position: relative;
    }

    .form-area h3::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 3px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
      border-radius: 2px;
    }

    .form-group {
      margin-bottom: 20px;
      position: relative;
    }

    label {
      font-weight: 700;
      color: var(--text-dark);
      margin-bottom: 8px;
      display: block;
      font-size: 14px;
      letter-spacing: 0.3px;
    }

    .input-wrapper {
      position: relative;
      transition: transform 0.3s ease;
    }

    .input-icon {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-light);
      font-size: 18px;
      z-index: 2;
      transition: color 0.3s ease;
    }

    .toggle-pass {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      border: 0;
      background: transparent;
      color: var(--text-light);
      font-size: 18px;
      cursor: pointer;
      z-index: 2;
      padding: 0;
    }

    .toggle-pass:hover {
      color: var(--primary);
    }

    .form-control {
      border-radius: 16px;
      padding: 14px 16px 14px 50px;
      border: 2px solid rgba(15, 23, 42, 0.1);
      box-shadow: none !important;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f8fafc;
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15) !important;
      background: #fff;
      outline: none;
    }

    .form-control:focus + .input-icon {
      color: var(--primary);
    }

    .form-control::placeholder {
      color: #94a3b8;
      font-size: 14px;
    }

    .btn-brand {
      width: 100%;
      padding: 15px;
      border-radius: 16px;
      font-weight: 700;
      font-size: 16px;
      border: none;
      color: #fff;
      background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
      box-shadow:
        0 10px 25px rgba(14, 165, 233, 0.4),
        0 0 0 0 rgba(14, 165, 233, 0.5);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      margin-top: 8px;
    }

    .btn-brand::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn-brand:hover {
      transform: translateY(-3px);
      box-shadow:
        0 15px 35px rgba(14, 165, 233, 0.5),
        0 0 0 8px rgba(14, 165, 233, 0.1);
    }

    .btn-brand:hover::before {
      width: 300px;
      height: 300px;
    }

    .btn-brand:active {
      transform: translateY(-1px);
    }

    .btn-brand span {
      position: relative;
      z-index: 1;
    }

    .forgot {
      font-size: 14px;
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
    }

    .forgot::after {
      content: '';
      position: absolute;
      bottom: -2px;
      right: 0;
      width: 0;
      height: 2px;
      background: var(--primary);
      transition: width 0.3s ease;
    }

    .forgot:hover {
      color: var(--accent);
    }

    .forgot:hover::after {
      width: 100%;
    }

    .form-check {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .form-check-input {
      width: 18px;
      height: 18px;
      border-radius: 4px;
      border: 2px solid rgba(15, 23, 42, 0.2);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .form-check-input:checked {
      background-color: var(--primary);
      border-color: var(--primary);
    }

    .form-check-input:focus {
      box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.15);
    }

    .form-check-label {
      color: var(--text-light);
      font-weight: 500;
      font-size: 14px;
      cursor: pointer;
      user-select: none;
    }

    .alert {
      border-radius: 14px;
      font-size: 14px;
      padding: 12px 16px;
      border: none;
      animation: shake 0.5s ease;
    }

    @keyframes shake {
      0%,
      100% {
        transform: translateX(0);
      }
      25% {
        transform: translateX(-10px);
      }
      75% {
        transform: translateX(10px);
      }
    }

    .alert-danger {
      background: linear-gradient(135deg, #fee2e2, #fecaca);
      color: #991b1b;
      border-left: 4px solid #ef4444;
    }

    .security-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 16px;
      font-size: 12px;
      color: var(--text-light);
      opacity: 0.8;
    }

    .security-badge i {
      color: var(--success);
    }

    @media (max-width: 576px) {
      body {
        padding: 15px;
        align-items: flex-start;
        padding-top: 30px;
      }

      .auth-container {
        padding: 36px 28px;
        width: 100%;
        max-width: 100%;
      }

      .brand-logo {
        width: 75px;
        height: 75px;
        font-size: 36px;
      }

      .brand-title {
        font-size: 26px;
      }

      .brand-sub {
        font-size: 14px;
      }

      .form-area {
        padding: 28px 24px;
      }

      .glow {
        width: 400px;
        height: 400px;
      }

      .glow:nth-of-type(3) {
        width: 300px;
        height: 300px;
      }
    }

    @media (max-height: 700px) {
      body {
        align-items: flex-start;
        padding-top: 20px;
        padding-bottom: 20px;
      }

      .auth-container {
        margin: 20px auto;
      }
    }
  </style>

  @stack('head')
</head>
<body>
  <div class="glow"></div>
  <div class="glow"></div>
  <div class="glow"></div>

  <div class="particles" id="particles"></div>

  <div class="auth-content">
    @yield('content')
  </div>

  <script>
    function createParticles() {
      const particlesContainer = document.getElementById('particles');
      if (!particlesContainer) return;
      const particleCount = 20;
      particlesContainer.innerHTML = '';

      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 15 + 's';
        particle.style.animationDuration = (10 + Math.random() * 10) + 's';
        particlesContainer.appendChild(particle);
      }
    }

    document.addEventListener('DOMContentLoaded', function () {
      createParticles();

      const inputs = document.querySelectorAll('.input-wrapper .form-control');
      inputs.forEach(input => {
        input.addEventListener('focus', function () {
          this.parentElement.style.transform = 'scale(1.02)';
        });

        input.addEventListener('blur', function () {
          this.parentElement.style.transform = 'scale(1)';
        });
      });

      const submitForm = document.querySelector('form.auth-form');
      const submitBtn = document.querySelector('.btn-brand');

      if (submitForm && submitBtn) {
        submitForm.addEventListener('submit', function () {
          submitBtn.innerHTML = '<span><i class="bi bi-hourglass-split me-2"></i>جاري التحقق...</span>';
          submitBtn.disabled = true;
        });
      }
    });
  </script>

  @stack('scripts')
</body>
</html>

