<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'لوحة التحكم | Wander Point in Syria')</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap RTL -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary: #0ea5e9;
      --primary-dark: #075985;
      --accent: #6366f1;
      --bg: #f5f7fb;
      --sidebar-bg: #0f172a;
      --card-bg: #ffffff;
      --text-main: #0f172a;
      --text-light: #64748b;
      --radius: 20px;
      --shadow: 0 15px 40px rgba(15, 23, 42, 0.08);
    }

    * {
      font-family: "Cairo", sans-serif;
      transition: all 0.3s ease;
    }

    body {
      background: var(--bg);
      min-height: 100vh;
    }

    /* SIDEBAR */
    .sidebar {
      position: fixed;
      top: 0;
      right: 0;
      width: 280px;
      height: 100vh;
      background: var(--sidebar-bg);
      color: #e2e8f0;
      display: flex;
      flex-direction: column;
      padding: 30px 20px;
      box-shadow: -10px 0 40px rgba(0, 0, 0, 0.2);
      z-index: 100;
      overflow-y: auto;
      overflow-x: hidden;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 12px;
      font-weight: 800;
      font-size: 24px;
      margin-bottom: 40px;
      color: white;
    }

    .brand .icon {
      width: 46px;
      height: 46px;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      border-radius: 14px;
      display: grid;
      place-items: center;
      font-size: 22px;
      box-shadow: 0 8px 20px rgba(99, 102, 241, 0.25);
    }

    .user-greeting {
      font-size: 15px;
      color: #cbd5e1;
      background: rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 10px 14px;
      margin-bottom: 25px;
      text-align: center;
    }

    .nav-link {
      color: #cbd5e1;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      padding: 10px 16px;
      border-radius: 10px;
      font-weight: 600;
      margin-bottom: 8px;
      transition: 0.25s ease;
      position: relative;
    }

    .nav-link:hover,
    .nav-link.active {
      background: linear-gradient(90deg, var(--primary), var(--accent));
      color: #fff;
      transform: translateX(-4px);
      box-shadow: 0 6px 18px rgba(14, 165, 233, 0.25);
    }

    .nav-link i {
      width: 20px;
      text-align: center;
    }

    /* Badges في Navigation */
    .nav-badge {
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      border-radius: 12px;
      padding: 2px 8px;
      font-size: 11px;
      font-weight: 700;
      min-width: 20px;
      text-align: center;
    }

    .nav-link:not(.active) .nav-badge {
      background: var(--primary);
      color: white;
    }

    .nav-badge-danger {
      background: #ef4444 !important;
      animation: pulse-badge 2s infinite;
    }

    @keyframes pulse-badge {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0.7;
      }
    }

    /* Section Titles */
    .nav-section-title {
      color: #64748b;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      padding: 16px 16px 8px;
      margin-top: 8px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-section-title:first-child {
      border-top: none;
      margin-top: 0;
      padding-top: 8px;
    }

    .logout-btn {
      margin-top: auto;
      color: #fff;
      border: 1px solid rgba(255, 255, 255, 0.25);
      border-radius: 12px;
      padding: 12px 18px;
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(255, 255, 255, 0.06);
    }

    .logout-btn:hover {
      background: linear-gradient(90deg, var(--primary), var(--accent));
      border-color: transparent;
    }

    /* MAIN */
    .main {
      margin-right: 280px;
      padding: 50px;
      position: relative;
    }

    .content-shell {
      background: var(--card-bg);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 40px;
      backdrop-filter: blur(8px);
    }

    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      flex-wrap: wrap;
      gap: 16px;
    }

    .page-header h1 {
      font-size: 30px;
      font-weight: 800;
      color: var(--text-main);
      margin: 0;
    }

    .subtitle {
      color: var(--text-light);
      font-size: 15px;
    }

    .primary-action {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
      border: 0;
      border-radius: 12px;
      padding: 10px 20px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
      box-shadow: 0 12px 20px rgba(14, 165, 233, 0.25);
      transition: 0.3s;
    }

    .primary-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 16px 26px rgba(14, 165, 233, 0.35);
    }

    /* Table / Stats */
    .card-custom {
      border: 0;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
      background: var(--card-bg);
      transition: all 0.3s ease;
    }

    .card-custom:hover {
      box-shadow: 0 20px 50px rgba(15, 23, 42, 0.12);
      transform: translateY(-2px);
    }

    .stat-box {
      background: white;
      border-radius: var(--radius);
      padding: 24px;
      box-shadow: var(--shadow);
      text-align: center;
      transition: all 0.3s ease;
    }

    .stat-box:hover {
      transform: translateY(-4px);
      box-shadow: 0 20px 40px rgba(14, 165, 233, 0.2);
    }

    .stat-number {
      font-size: 32px;
      font-weight: 800;
      color: var(--primary);
      line-height: 1.2;
    }

    .stat-label {
      color: var(--text-light);
      font-size: 14px;
      margin-top: 4px;
    }

    /* Tables */
    .table {
      margin-bottom: 0;
    }

    .table thead th {
      background-color: #f8f9fa;
      border-bottom: 2px solid #e2e8f0;
      font-weight: 700;
      color: var(--text-main);
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 0.5px;
      padding: 16px;
    }

    .table tbody td {
      padding: 16px;
      vertical-align: middle;
      border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr {
      transition: all 0.2s ease;
    }

    .table tbody tr:hover {
      background-color: #f8f9fa;
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table-responsive {
      border-radius: var(--radius);
      overflow: hidden;
    }

    /* Buttons */
    .btn {
      border-radius: 10px;
      font-weight: 600;
      padding: 8px 16px;
      transition: all 0.2s ease;
      border: 1px solid transparent;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-sm {
      padding: 6px 12px;
      font-size: 13px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      border: none;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #0284c7, #4f46e5);
      box-shadow: 0 6px 20px rgba(14, 165, 233, 0.35);
    }

    .btn-outline-primary {
      border-color: var(--primary);
      color: var(--primary);
    }

    .btn-outline-primary:hover {
      background: var(--primary);
      border-color: var(--primary);
      color: white;
    }

    .btn-outline-secondary:hover {
      background: var(--text-light);
      border-color: var(--text-light);
      color: white;
    }

    .btn-outline-danger:hover {
      background: #ef4444;
      border-color: #ef4444;
      color: white;
    }

    /* Badges */
    .badge {
      padding: 6px 12px;
      font-weight: 600;
      border-radius: 8px;
      font-size: 12px;
    }

    /* Forms */
    .form-control,
    .form-select {
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      padding: 10px 14px;
      transition: all 0.2s ease;
      font-size: 14px;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
      outline: none;
    }

    /* Alerts */
    .alert {
      border-radius: 12px;
      border: none;
      padding: 16px 20px;
      margin-bottom: 24px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .alert-success {
      background: linear-gradient(135deg, #10b981, #059669);
      color: white;
    }

    .alert-danger {
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: white;
    }

    .alert-info {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: white;
    }

    .alert-warning {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: white;
    }

    /* BREADCRUMB */
    .breadcrumb-custom {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 20px;
      font-size: 14px;
      color: var(--text-light);
    }

    .breadcrumb-custom span {
      color: var(--text-light);
    }

    .breadcrumb-custom a {
      color: var(--primary);
      text-decoration: none;
    }

    .breadcrumb-custom a:hover {
      text-decoration: underline;
    }

    /* Primary Action Button */
    .primary-action .icon {
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(255,255,255,0.2);
      border-radius: 8px;
      font-size: 20px;
      font-weight: bold;
    }

    /* Cards with Statistics */
    .card-custom.p-4 {
      position: relative;
      overflow: hidden;
    }

    .card-custom.p-4::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 100px;
      height: 100px;
      background: radial-gradient(circle, rgba(14,165,233,0.1), transparent);
      border-radius: 50%;
      transform: translate(30%, -30%);
    }

    /* Page Header */
    .page-header h1 {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    /* Empty States */
    .text-center.py-5 {
      padding: 60px 20px !important;
    }

    /* Pagination - تحسين تصميم أزرار التنقل */
    .pagination {
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      margin: 0;
      padding: 0;
    }

    .pagination .page-item {
      margin: 0;
      list-style: none;
    }

    .pagination .page-link {
      display: flex;
      align-items: center;
      justify-content: center;
      min-width: 42px;
      height: 42px;
      padding: 8px 14px;
      border-radius: 10px;
      border: 2px solid #e2e8f0;
      background: white;
      color: var(--primary);
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .pagination .page-link::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--primary), var(--accent));
      transform: translate(-50%, -50%);
      transition: width 0.3s ease, height 0.3s ease;
      z-index: 0;
    }

    .pagination .page-link:hover::before {
      width: 100%;
      height: 100%;
    }

    .pagination .page-link span,
    .pagination .page-link svg {
      position: relative;
      z-index: 1;
    }

    .pagination .page-link:hover {
      color: white;
      border-color: var(--primary);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
    }

    .pagination .page-item.active .page-link {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      border-color: var(--primary);
      color: white;
      box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
      transform: scale(1.05);
    }

    .pagination .page-item.active .page-link::before {
      display: none;
    }

    .pagination .page-item.disabled .page-link {
      background: #f8f9fa;
      color: #cbd5e1;
      border-color: #e2e8f0;
      cursor: not-allowed;
      opacity: 0.6;
    }

    .pagination .page-item.disabled .page-link:hover {
      transform: none;
      box-shadow: none;
      background: #f8f9fa;
      color: #cbd5e1;
    }

    .pagination .page-item.disabled .page-link::before {
      display: none;
    }

    /* تحسين أزرار Previous و Next */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
      min-width: 100px;
      font-weight: 700;
      background: linear-gradient(135deg, #f8f9fa, #ffffff);
    }

    .pagination .page-item:first-child .page-link:hover,
    .pagination .page-item:last-child .page-link:hover {
      background: linear-gradient(135deg, var(--primary), var(--accent));
    }

    /* تحسين الأسهم في Bootstrap Icons */
    .pagination .page-link svg {
      width: 16px;
      height: 16px;
      transition: transform 0.3s ease;
    }

    .pagination .page-link:hover svg {
      transform: scale(1.2);
    }

    /* تأثير خاص للصفحة الأولى والأخيرة */
    .pagination .page-item:first-child .page-link {
      padding-right: 20px;
      padding-left: 16px;
    }

    .pagination .page-item:last-child .page-link {
      padding-left: 20px;
      padding-right: 16px;
    }

    /* تحسين عرض Pagination على الشاشات الصغيرة */
    @media (max-width: 768px) {
      .pagination {
        gap: 4px;
      }

      .pagination .page-link {
        min-width: 36px;
        height: 36px;
        padding: 6px 10px;
        font-size: 13px;
      }

      .pagination .page-item:first-child .page-link,
      .pagination .page-item:last-child .page-link {
        min-width: 80px;
        padding: 6px 12px;
      }
    }

    /* تحسين النصوص العربية في Pagination */
    .pagination .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border-width: 0;
    }

    /* تحسين أزرار السابق والتالي */
    .pagination .page-item:first-child .page-link {
      background: linear-gradient(135deg, #f8f9fa, #ffffff);
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .pagination .page-item:first-child .page-link:hover {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
    }

    .pagination .page-item:last-child .page-link {
      background: linear-gradient(135deg, #f8f9fa, #ffffff);
      font-weight: 700;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .pagination .page-item:last-child .page-link:hover {
      background: linear-gradient(135deg, var(--primary), var(--accent));
      color: white;
    }

    /* تأثيرات إضافية للأرقام */
    .pagination .page-link:not(.disabled):not(.active) {
      position: relative;
    }

    .pagination .page-link:not(.disabled):not(.active):active {
      transform: scale(0.95);
    }

    /* تحسين الأيقونات داخل Pagination */
    .pagination .page-link i,
    .pagination .page-link svg {
      transition: all 0.3s ease;
      font-size: 18px;
      font-weight: 700;
    }

    .pagination .page-link:hover i,
    .pagination .page-link:hover svg {
      transform: scale(1.2);
    }

    /* تأثير خاص للأسهم في RTL */
    [dir="rtl"] .pagination .page-item:first-child .page-link i {
      transform: scaleX(-1);
    }

    [dir="rtl"] .pagination .page-item:last-child .page-link i {
      transform: scaleX(-1);
    }

    [dir="rtl"] .pagination .page-link:hover i {
      transform: scaleX(-1) scale(1.2);
    }

    /* تأثير خاص للصفحة النشطة */
    .pagination .page-item.active .page-link {
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% {
        box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3);
      }
      50% {
        box-shadow: 0 4px 20px rgba(14, 165, 233, 0.5);
      }
    }

    /* تحسينات خاصة بـ RTL للـ Pagination */
    [dir="rtl"] .pagination {
      direction: rtl;
    }

    [dir="rtl"] .pagination .page-link {
      text-align: center;
    }

    /* تحسين مظهر Pagination Container */
    .pagination-wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
      margin-top: 24px;
      padding: 16px;
      background: #f8f9fa;
      border-radius: 12px;
    }

    /* تحسين عرض معلومات Pagination */
    .pagination-info {
      color: var(--text-light);
      font-size: 14px;
      font-weight: 600;
    }

    /* إضافة تأثيرات hover للـ container */
    .pagination-wrapper:hover .pagination .page-link:not(.disabled):not(.active) {
      opacity: 0.9;
    }

    /* Sidebar Scroll */
    .sidebar {
      overflow-y: auto;
      overflow-x: hidden;
    }

    .sidebar::-webkit-scrollbar {
      width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.05);
    }

    .sidebar::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
      border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.3);
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
      .sidebar {
        position: relative;
        width: 100%;
        height: auto;
        flex-direction: column;
        align-items: stretch;
        padding: 16px 20px;
        margin-bottom: 20px;
        border-radius: var(--radius);
        overflow-y: visible;
        max-height: none;
      }

      .brand {
        margin-bottom: 16px;
      }

      .user-greeting {
        display: none;
      }

      .nav {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 8px;
      }

      .nav-section-title {
        grid-column: 1 / -1;
        padding: 8px 0;
        margin-top: 12px;
      }

      .nav-link {
        margin-bottom: 0;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 12px 8px;
        gap: 4px;
      }

      .nav-link:hover {
        transform: translateY(-2px);
      }

      .main {
        margin-right: 0;
        padding: 24px 18px;
      }

      .content-shell {
        padding: 24px;
      }

      .page-header {
        flex-direction: column;
        align-items: flex-start !important;
      }

      .page-header h1 {
        font-size: 24px;
        margin-bottom: 12px;
      }

      .stat-number {
        font-size: 24px;
      }

      .table {
        font-size: 14px;
      }

      .table thead th,
      .table tbody td {
        padding: 12px 8px;
      }
    }

    @media (max-width: 768px) {
      .main {
        padding: 16px 12px;
      }

      .content-shell {
        padding: 16px;
      }

      .page-header h1 {
        font-size: 20px;
      }

      .subtitle {
        font-size: 13px;
      }

      .card-custom.p-4 {
        padding: 20px !important;
      }

      .stat-number {
        font-size: 22px;
      }

      .table-responsive {
        font-size: 12px;
      }

      .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
      }
    }

    /* LIGHT GLOW EFFECT */
    .glow {
      position: fixed;
      top: -200px;
      left: -100px;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(99,102,241,0.15), transparent 70%);
      z-index: 0;
      filter: blur(60px);
    }

    .glow2 {
      position: fixed;
      bottom: -200px;
      right: -100px;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(14,165,233,0.18), transparent 70%);
      z-index: 0;
      filter: blur(60px);
    }
  </style>

  @stack('head')
</head>

<body>
  <div class="glow"></div>
  <div class="glow2"></div>

  <aside class="sidebar">
    <div class="brand">
      <div class="icon"><i class="bi bi-compass"></i></div>
      <div>
        <span>Wander Point</span>
        <small style="font-size: 13px; color:#cbd5e1;">in Syria</small>
      </div>
    </div>

    <p class="user-greeting">👋 مرحباً {{ optional(Auth::guard('admin')->user())->name ?? 'مستخدم' }}</p>

    <nav class="nav flex-column">
      <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-door"></i> الرئيسية
      </a>
      
      <div class="nav-section-title">إدارة المحتوى</div>
      <a href="{{ route('admin.destinations.index') }}" class="nav-link {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}">
        <i class="bi bi-geo-alt"></i> الوجهات
      </a>
      <a href="{{ route('admin.activities.index') }}" class="nav-link {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
        <i class="bi bi-activity"></i> الأنشطة
      </a>
      <a href="{{ route('admin.hotels.index') }}" class="nav-link {{ request()->routeIs('admin.hotels.*') ? 'active' : '' }}">
        <i class="bi bi-building"></i> الفنادق
      </a>
      <a href="{{ route('admin.travel-basics.index') }}" class="nav-link {{ request()->routeIs('admin.travel-basics.*') ? 'active' : '' }}">
        <i class="bi bi-suitcase"></i> أساسيات السفر
      </a>
      
      <div class="nav-section-title">إدارة المستخدمين</div>
      <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people"></i> المستخدمين
      </a>
      <a href="{{ route('admin.content-providers.index') }}" class="nav-link {{ request()->routeIs('admin.content-providers.*') || request()->routeIs('admin.providers.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> مزوّدو المحتوى
        @php
          $pendingProvidersCount = \App\Models\ContentProviderApplication::where('status', 'pending')->count();
        @endphp
        @if($pendingProvidersCount > 0)
          <span class="nav-badge nav-badge-danger">{{ $pendingProvidersCount }}</span>
        @endif
      </a>
      
      <div class="nav-section-title">إدارة الحجوزات والطلبات</div>
      <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check"></i> الحجوزات
        @php
          $pendingBookingsCount = \App\Models\Booking::where('status', 'pending')->count();
        @endphp
        @if($pendingBookingsCount > 0)
          <span class="nav-badge">{{ $pendingBookingsCount }}</span>
        @endif
      </a>
      <a href="{{ route('admin.recommendations.insights') }}" class="nav-link {{ request()->routeIs('admin.recommendations.*') ? 'active' : '' }}">
        <i class="bi bi-bar-chart-line"></i> تحليلات التوصية
      </a>
      <div class="nav-section-title">التفاعل والمراجعات</div>
      <a href="{{ route('admin.comments.index') }}" class="nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
        <i class="bi bi-chat-left-text"></i> التعليقات
        @php
          $pendingCommentsCount = \App\Models\Comment::where('is_approved', false)->count();
        @endphp
        @if($pendingCommentsCount > 0)
          <span class="nav-badge">{{ $pendingCommentsCount }}</span>
        @endif
      </a>
      <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
        <i class="bi bi-envelope"></i> رسائل التواصل
        @php
          $newContactsCount = \App\Models\Contact::where('status', 'new')->count();
        @endphp
        @if($newContactsCount > 0)
          <span class="nav-badge nav-badge-danger">{{ $newContactsCount }}</span>
        @endif
      </a>
      
      <div class="nav-section-title">إدارة العروض والمكافآت</div>
      <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
        <i class="bi bi-ticket-perforated"></i> الكوبونات
      </a>
    </nav>

    <a href="{{ route('admin.logout') }}" class="logout-btn mt-auto">
      <i class="bi bi-box-arrow-left"></i> تسجيل الخروج
    </a>
  </aside>

  <main class="main">
    <div class="content-shell">
      @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
      @endif
      @if(session('warning'))
        <div class="alert alert-warning mb-3">{{ session('warning') }}</div>
      @endif
      @yield('content')
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
