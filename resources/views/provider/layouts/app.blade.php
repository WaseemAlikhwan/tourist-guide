<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة مزوّد المحتوى') | Wander Point</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --pd-primary: #b89ff0;
            --pd-primary-dark: #8b6fc9;
            --pd-bg: #f8f7fd;
            --pd-bg-soft: #ffffff;
            --pd-sidebar: #0f172a;
            --pd-text: #0f172a;
            --pd-muted: #64748b;
            --pd-border: #e9e3fa;
            --pd-success: #059669;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(184, 159, 240, 0.18), transparent 35%),
                radial-gradient(circle at 20% 10%, rgba(139, 111, 201, 0.12), transparent 30%),
                var(--pd-bg);
            color: var(--pd-text);
            min-height: 100vh;
        }
        .provider-shell {
            min-height: 100vh;
        }
        .provider-sidebar {
            background:
                linear-gradient(180deg, rgba(184, 159, 240, 0.15) 0%, rgba(15, 23, 42, 0) 28%),
                var(--pd-sidebar);
            color: #e2e8f0;
            min-height: 100vh;
            padding: 1rem;
            position: sticky;
            top: 0;
            border-inline-end: 1px solid rgba(148, 163, 184, 0.14);
        }
        .provider-sidebar .brand {
            font-weight: 900;
            font-size: 1.1rem;
            padding: 0.85rem 0.9rem;
            margin-bottom: 0.9rem;
            color: #fff;
            border-radius: 0.85rem;
            background: rgba(255, 255, 255, 0.06);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .provider-sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.68rem 0.9rem;
            border-radius: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 0.32rem;
            transition: all 0.2s ease;
        }
        .provider-sidebar .nav-link i {
            width: 1.1rem;
            text-align: center;
        }
        .provider-sidebar .nav-link:hover,
        .provider-sidebar .nav-link.active {
            color: #fff;
            background: rgba(184, 159, 240, 0.22);
            transform: translateX(-2px);
        }
        .provider-sidebar hr {
            border-color: rgba(148, 163, 184, 0.22);
            margin: 0.8rem 0;
        }
        .provider-main {
            padding: 1.65rem;
        }
        .provider-page-header {
            background: linear-gradient(135deg, #ffffff 0%, #f6f2ff 100%);
            border: 1px solid var(--pd-border);
            border-radius: 1rem;
            padding: 1rem 1.1rem;
            margin-bottom: 1rem;
        }
        .provider-page-header h1 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 800;
        }
        .provider-page-header p {
            margin: 0.35rem 0 0;
            color: var(--pd-muted);
            font-size: 0.92rem;
        }
        .provider-panel {
            background: var(--pd-bg-soft);
            border: 1px solid var(--pd-border);
            border-radius: 1rem;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.04);
        }
        .provider-panel .table thead th {
            background: #f8f5ff;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 700;
            border-bottom: 1px solid var(--pd-border);
            white-space: nowrap;
        }
        .provider-panel .table td {
            vertical-align: middle;
        }
        .provider-stat-card {
            border: 1px solid var(--pd-border);
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
        }
        .provider-stat-card .stat-icon {
            width: 2.4rem;
            height: 2.4rem;
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--pd-primary-dark);
            background: #f2edff;
        }
        .provider-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            border-radius: 999px;
            padding: 0.25rem 0.6rem;
            font-size: 0.78rem;
            font-weight: 700;
        }
        .provider-badge-success {
            background: #ecfdf5;
            color: var(--pd-success);
        }
        .provider-badge-soft {
            background: #f1f5f9;
            color: #475569;
        }
        .provider-badge-event {
            background: #ede9fe;
            color: #6d28d9;
        }
        .provider-quick-btn {
            border-radius: 0.7rem;
            padding: 0.5rem 0.65rem;
        }
        .provider-agenda-list {
            display: flex;
            flex-direction: column;
            gap: 0.55rem;
        }
        .provider-agenda-item {
            border: 1px solid var(--pd-border);
            border-radius: 0.75rem;
            padding: 0.55rem 0.65rem;
            background: rgba(255, 255, 255, 0.6);
        }
        .provider-top-activity {
            margin-bottom: 0.8rem;
        }
        .provider-top-activity:last-child {
            margin-bottom: 0;
        }
        .provider-progress-track {
            height: 9px;
            border-radius: 999px;
            background: #e2e8f0;
            overflow: hidden;
        }
        .provider-progress-bar {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #b89ff0, #8b6fc9);
        }
        .provider-copy-toast {
            position: fixed;
            bottom: 1rem;
            left: 1rem;
            z-index: 1080;
            padding: 0.6rem 0.85rem;
            border-radius: 0.7rem;
            background: #0f172a;
            color: #f8fafc;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.18);
            opacity: 0;
            transform: translateY(8px);
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        .provider-copy-toast.show {
            opacity: 1;
            transform: translateY(0);
        }
        @media (max-width: 991px) {
            .provider-sidebar {
                min-height: auto;
                position: relative;
                border-inline-end: 0;
                border-bottom: 1px solid rgba(148, 163, 184, 0.2);
            }
            .provider-main {
                padding: 1rem;
            }
        }
        body[data-theme="dark"] {
            --pd-bg: #0f172a;
            --pd-bg-soft: #1e293b;
            --pd-text: #f1f5f9;
            --pd-muted: #94a3b8;
            --pd-border: #334155;
        }
        body[data-theme="dark"] {
            background:
                radial-gradient(circle at top right, rgba(184, 159, 240, 0.12), transparent 35%),
                radial-gradient(circle at 20% 10%, rgba(139, 111, 201, 0.08), transparent 30%),
                var(--pd-bg);
            color: var(--pd-text);
        }
        body[data-theme="dark"] .provider-panel,
        body[data-theme="dark"] .provider-stat-card {
            background: var(--pd-bg-soft);
            border-color: var(--pd-border);
        }
        body[data-theme="dark"] .provider-page-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-color: var(--pd-border);
        }
        body[data-theme="dark"] .provider-panel .table thead th {
            background: #0f172a;
            color: #cbd5e1;
            border-color: var(--pd-border);
        }
        body[data-theme="dark"] .table {
            color: var(--pd-text);
            --bs-table-bg: transparent;
        }
        body[data-theme="dark"] .modal-content {
            background: var(--pd-bg-soft);
            color: var(--pd-text);
            border-color: var(--pd-border);
        }
        body[data-theme="dark"] .list-group-item {
            background: var(--pd-bg-soft);
            color: var(--pd-text);
            border-color: var(--pd-border);
        }
        body[data-theme="dark"] .list-group-item.active {
            background: rgba(184, 159, 240, 0.25);
            border-color: var(--pd-primary);
        }
        body[data-theme="dark"] .form-control {
            background: #0f172a;
            border-color: var(--pd-border);
            color: var(--pd-text);
        }
        body[data-theme="dark"] .form-control::placeholder {
            color: #94a3b8;
        }
        body[data-theme="dark"] .provider-main .text-muted {
            color: #94a3b8 !important;
        }
        body[data-theme="dark"] .provider-main .text-dark,
        body[data-theme="dark"] .provider-main .text-body {
            color: var(--pd-text) !important;
        }
        body[data-theme="dark"] .provider-main .card,
        body[data-theme="dark"] .provider-main .bg-white,
        body[data-theme="dark"] .provider-main .bg-light {
            background-color: var(--pd-bg-soft) !important;
            color: var(--pd-text);
            border-color: var(--pd-border) !important;
        }
        body[data-theme="dark"] .provider-main .provider-agenda-item {
            background: rgba(15, 23, 42, 0.48);
            border-color: var(--pd-border);
        }
        body[data-theme="dark"] .provider-main .provider-progress-track {
            background: #1f2937;
        }
        body[data-theme="dark"] .provider-main .provider-badge-soft {
            background: #334155;
            color: #cbd5e1;
        }
        body[data-theme="dark"] .provider-main .provider-badge-event {
            background: rgba(124, 58, 237, 0.25);
            color: #ddd6fe;
        }
        body[data-theme="dark"] .provider-main .provider-quick-btn {
            border-color: #475569;
            color: #dbeafe;
        }
        body[data-theme="dark"] .provider-main .provider-quick-btn:hover {
            background: rgba(184, 159, 240, 0.2);
            border-color: #8b6fc9;
            color: #f8fafc;
        }
        body[data-theme="dark"] .provider-copy-toast {
            background: #1e293b;
            border: 1px solid #334155;
        }
        body[data-theme="dark"] .provider-main .alert-info {
            background-color: rgba(56, 189, 248, 0.15);
            color: #e0f2fe;
            border-color: rgba(56, 189, 248, 0.35);
        }
        body[data-theme="dark"] .provider-main .alert-success {
            background-color: rgba(52, 211, 153, 0.15);
            color: #d1fae5;
            border-color: rgba(52, 211, 153, 0.35);
        }
        body[data-theme="dark"] .provider-main .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        #providerCommandPalette .modal-content {
            border-radius: 1rem;
        }
        #paletteList .list-group-item {
            cursor: pointer;
            border-radius: 0.5rem !important;
            margin-bottom: 2px;
        }
        #paletteList .list-group-item:hover,
        #paletteList .list-group-item.active {
            background: rgba(184, 159, 240, 0.15);
        }
    </style>
    @stack('head')
</head>
<body>
<script>
    (function () {
        try {
            if (localStorage.getItem('provider-theme') === 'dark') {
                document.body.setAttribute('data-theme', 'dark');
            } else {
                document.body.setAttribute('data-theme', 'light');
            }
        } catch (e) {
            document.body.setAttribute('data-theme', 'light');
        }
    })();
</script>
<div class="container-fluid provider-shell">
    <div class="row g-0">
        <div class="col-lg-2 col-12 provider-sidebar">
            <div class="brand"><i class="fas fa-handshake"></i> مزوّد المحتوى</div>
            <div class="d-flex align-items-center justify-content-between gap-2 mb-2 px-1">
                <span class="small text-white-50 text-truncate" style="max-width: 7rem;" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</span>
                <button type="button" class="btn btn-sm btn-outline-light border-0 py-1 px-2" id="providerThemeToggle" title="تبديل الوضع">
                    <i class="fas fa-moon" id="providerThemeIconMoon"></i>
                    <i class="fas fa-sun d-none" id="providerThemeIconSun"></i>
                </button>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('provider.dashboard') ? 'active' : '' }}" href="{{ route('provider.dashboard') }}">
                    <i class="fas fa-chart-pie"></i> نظرة عامة
                </a>
                <a class="nav-link {{ request()->routeIs('provider.bookings.*') ? 'active' : '' }}" href="{{ route('provider.bookings.index') }}">
                    <i class="fas fa-calendar-check"></i> الحجوزات
                </a>
                <a class="nav-link {{ request()->routeIs('provider.activities.*') ? 'active' : '' }}" href="{{ route('provider.activities.index') }}">
                    <i class="fas fa-hiking"></i> أنشطتي
                </a>
                <a class="nav-link {{ request()->routeIs('provider.notifications.*') ? 'active' : '' }}" href="{{ route('provider.notifications.index') }}">
                    <i class="fas fa-bell"></i> الإشعارات
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <a class="nav-link {{ request()->routeIs('provider.account') ? 'active' : '' }}" href="{{ route('provider.account') }}">
                    <i class="fas fa-id-card"></i> الحساب
                </a>
                <hr>
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-globe"></i> الموقع العام
                </a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100 rounded-3 py-2">
                        <i class="fas fa-sign-out-alt"></i> خروج
                    </button>
                </form>
            </nav>
        </div>
        <div class="col-lg-10 provider-main">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</div>

<div class="modal fade" id="providerCommandPalette" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-3">
                <div class="d-flex align-items-center gap-2 mb-2 text-muted small">
                    <kbd class="px-2 py-1 rounded bg-light border">Ctrl</kbd>
                    <span>+</span>
                    <kbd class="px-2 py-1 rounded bg-light border">K</kbd>
                    <span class="me-auto">لوحة التنقل السريع</span>
                </div>
                <input type="text" class="form-control form-control-lg mb-2" id="paletteSearch" placeholder="ابحث عن صفحة..." autocomplete="off" dir="rtl">
                <div id="paletteList" class="list-group list-group-flush" style="max-height: 320px; overflow-y: auto;"></div>
            </div>
        </div>
    </div>
</div>

@php
    $providerCmdItems = [
        ['label' => 'نظرة عامة', 'url' => route('provider.dashboard'), 'icon' => 'fa-chart-pie', 'keywords' => 'dashboard overview'],
        ['label' => 'الحجوزات', 'url' => route('provider.bookings.index'), 'icon' => 'fa-calendar-check', 'keywords' => 'bookings'],
        ['label' => 'أنشطتي', 'url' => route('provider.activities.index'), 'icon' => 'fa-hiking', 'keywords' => 'activities'],
        ['label' => 'إضافة نشاط', 'url' => route('provider.activities.create'), 'icon' => 'fa-plus', 'keywords' => 'create new'],
        ['label' => 'الإشعارات', 'url' => route('provider.notifications.index'), 'icon' => 'fa-bell', 'keywords' => 'notifications'],
        ['label' => 'الحساب', 'url' => route('provider.account'), 'icon' => 'fa-id-card', 'keywords' => 'account profile'],
        ['label' => 'صفحتي العامّة (نسخ رابط)', 'url' => route('providers.storefront', auth()->id()), 'icon' => 'fa-store', 'keywords' => 'public storefront share', 'newTab' => true],
        ['label' => 'الموقع العام', 'url' => route('home'), 'icon' => 'fa-globe', 'keywords' => 'home wander', 'newTab' => true],
    ];
@endphp

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var CMD_ITEMS = @json($providerCmdItems);

    function applyThemeIcon() {
        var dark = document.body.getAttribute('data-theme') === 'dark';
        document.getElementById('providerThemeIconMoon').classList.toggle('d-none', dark);
        document.getElementById('providerThemeIconSun').classList.toggle('d-none', !dark);
    }
    applyThemeIcon();

    var themeBtn = document.getElementById('providerThemeToggle');
    if (themeBtn) {
        themeBtn.addEventListener('click', function () {
            var next = document.body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.body.setAttribute('data-theme', next);
            try { localStorage.setItem('provider-theme', next); } catch (e) {}
            applyThemeIcon();
        });
    }

    var paletteModalEl = document.getElementById('providerCommandPalette');
    var paletteModal = paletteModalEl ? new bootstrap.Modal(paletteModalEl) : null;
    var searchInput = document.getElementById('paletteSearch');
    var listEl = document.getElementById('paletteList');
    var filtered = CMD_ITEMS.slice();
    var activeIndex = 0;

    function normalize(s) { return (s || '').toString().toLowerCase(); }

    function renderList() {
        if (!listEl) return;
        listEl.innerHTML = '';
        filtered.forEach(function (item, idx) {
            var a = document.createElement('a');
            a.href = item.url;
            a.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2' + (idx === activeIndex ? ' active' : '');
            a.innerHTML = '<i class="fas ' + item.icon + ' text-primary" style="width:1.25rem;"></i><span>' + item.label + '</span>';
            if (item.newTab) { a.target = '_blank'; a.rel = 'noopener'; }
            a.addEventListener('click', function (e) {
                if (item.newTab) return;
                e.preventDefault();
                paletteModal.hide();
                window.location.href = item.url;
            });
            listEl.appendChild(a);
        });
        if (filtered.length === 0) {
            listEl.innerHTML = '<div class="text-muted small p-3 text-center">لا نتائج</div>';
        }
    }

    function filterItems(q) {
        var n = normalize(q).trim();
        if (!n) {
            filtered = CMD_ITEMS.slice();
            return;
        }
        filtered = CMD_ITEMS.filter(function (item) {
            return normalize(item.label).includes(n) || normalize(item.keywords).includes(n);
        });
    }

    function openPalette() {
        if (!paletteModal) return;
        activeIndex = 0;
        searchInput.value = '';
        filterItems('');
        renderList();
        paletteModal.show();
        setTimeout(function () { searchInput.focus(); }, 200);
    }

    document.addEventListener('keydown', function (e) {
        var mod = e.ctrlKey || e.metaKey;
        if (mod && (e.key === 'k' || e.key === 'K')) {
            e.preventDefault();
            openPalette();
        }
    });

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            activeIndex = 0;
            filterItems(this.value);
            renderList();
        });
        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = Math.min(activeIndex + 1, Math.max(filtered.length - 1, 0));
                renderList();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                renderList();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (filtered[activeIndex]) {
                    var u = filtered[activeIndex].url;
                    if (filtered[activeIndex].newTab) window.open(u, '_blank');
                    else window.location.href = u;
                    paletteModal.hide();
                }
            }
        });
    }

    paletteModalEl && paletteModalEl.addEventListener('shown.bs.modal', function () {
        searchInput && searchInput.focus();
    });
})();
</script>
@stack('scripts')
</body>
</html>
