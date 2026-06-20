<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Wander Point in Syria">
    
    <!-- Primary Meta Tags -->
    <title>@yield('title', __('website.home.title'))</title>
    <meta name="title" content="@yield('meta_title', __('website.home.title'))">
    <meta name="description" content="@yield('meta_description', 'اكتشف أجمل الوجهات السياحية في سوريا، الأنشطة الترفيهية، الفنادق، وكل ما تحتاجه لتخطيط رحلتك المثالية')">
    <meta name="keywords" content="@yield('meta_keywords', 'سياحة سوريا, وجهات سياحية, أنشطة ترفيهية, فنادق سوريا, دليل سياحي')">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Wander Point in Syria - اكتشف جمال سوريا')">
    <meta property="og:description" content="@yield('og_description', 'اكتشف أجمل الوجهات السياحية في سوريا')">
    <meta property="og:image" content="@yield('og_image', asset('storage/default-og-image.jpg'))">
    <meta property="og:locale" content="ar_AR">
    <meta property="og:site_name" content="Wander Point in Syria">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('twitter_title', 'Wander Point in Syria - اكتشف جمال سوريا')">
    <meta name="twitter:description" content="@yield('twitter_description', 'اكتشف أجمل الوجهات السياحية في سوريا')">
    <meta name="twitter:image" content="@yield('twitter_image', asset('storage/default-og-image.jpg'))">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Google Fonts - Cairo -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    
    <!-- Structured Data (JSON-LD) -->
    @stack('structured_data')
    
    <style>
        :root {
            --primary: #0ea5a4;
            --primary-dark: #0f766e;
            --primary-light: #ccfbf1;
            --secondary: #f59e0b;
            --secondary-dark: #d97706;
            --accent: #fb7185;
            --bg: #f8fafc;
            --bg-alt: #ffffff;
            --text: #0f172a;
            --text-light: #64748b;
            --danger: #ef4444;
            --success: #10b981;
            --warning: #f59e0b;
            --border: #e2e8f0;
            --shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
            --shadow-lg: 0 18px 40px rgba(15, 23, 42, 0.14);
            --focus-ring: 0 0 0 0.22rem rgba(14, 165, 164, 0.28);
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.7;
            padding-top: 80px;
        }
        
        /* Navbar Styling - Visit Saudi Style */
        .navbar {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.12);
        }
        
        .navbar-brand {
            font-weight: 900;
            font-size: 1.75rem;
            color: var(--primary-dark) !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
            color: var(--primary) !important;
        }
        
        .navbar-brand i {
            font-size: 2rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .navbar-nav {
            align-items: center;
        }
        
        .navbar-nav .nav-item {
            margin: 0 0.5rem;
        }
        
        .navbar-nav .nav-link {
            color: var(--text) !important;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.75rem 1.25rem !important;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 2px;
            transition: width 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            width: 70%;
        }
        
        .navbar-nav .nav-link:hover {
            color: var(--primary-dark) !important;
            background: rgba(14, 165, 164, 0.12);
            transform: translateY(-2px);
        }
        
        .navbar-nav .nav-link i {
            font-size: 1.1rem;
        }
        
        /* Dropdown Menu Styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            min-width: 220px;
        }
        
        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .dropdown-item:hover {
            background: rgba(14, 165, 164, 0.14);
            color: var(--primary-dark);
            padding-right: 2rem;
        }
        
        .dropdown-item i {
            width: 20px;
            text-align: center;
        }
        
        /* Navbar Buttons */
        .navbar .btn {
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .navbar .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        
        .navbar .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(14, 165, 164, 0.32);
        }
        
        .navbar .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
        }
        
        .navbar .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px rgba(14, 165, 164, 0.36);
        }
        
        /* Navbar Toggler */
        .navbar-toggler {
            border: 2px solid var(--primary);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }
        
        .navbar-toggler:focus {
            box-shadow: var(--focus-ring);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28215, 203, 249, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }
        
        /* Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.625rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: white;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 1.25rem;
            font-weight: 700;
        }
        
        /* Badges */
        .badge {
            padding: 0.5rem 1rem;
            font-weight: 600;
            border-radius: 8px;
        }
        
        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            font-weight: 500;
            box-shadow: var(--shadow);
        }
        
        /* Forms */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--border);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: var(--focus-ring);
        }

        a:focus-visible,
        button:focus-visible,
        .btn:focus-visible,
        .nav-link:focus-visible {
            outline: none;
            box-shadow: var(--focus-ring);
        }
        
        /* Footer */
        footer {
            background: var(--text);
            color: white;
            padding: 2rem 0;
            margin-top: 4rem;
            text-align: center;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .navbar-nav {
                text-align: right;
                padding: 1rem 0;
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem !important;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }
            
            .navbar-nav .nav-link::after {
                display: none;
            }
            
            .navbar-nav .nav-link:hover {
                background: rgba(14, 165, 164, 0.16);
                padding-right: 1.5rem;
            }
            
            .dropdown-menu {
                margin-top: 0;
                border-radius: 0;
                box-shadow: none;
                border: none;
                background: rgba(0, 0, 0, 0.02);
            }
            
            .navbar .btn {
                width: 100%;
                margin: 0.5rem 0;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .navbar-brand i {
                font-size: 1.75rem;
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
    
    @stack('head')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-landmark"></i>
                <span>Wander Point</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home"></i>
                            <span>{{ __('ui.home') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('destinations.*') ? 'active' : '' }}" href="{{ route('destinations.index') }}">
                            <i class="fas fa-map-marked-alt"></i>
                            <span>{{ __('ui.destinations') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}" href="{{ route('activities.index') }}">
                            <i class="fas fa-hiking"></i>
                            <span>{{ __('ui.activities') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('partners.*') ? 'active' : '' }}" href="{{ route('partners.index') }}">
                            <i class="fas fa-handshake"></i>
                            <span>{{ __('ui.partners') }}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('travel-basics.*') || request()->routeIs('events.*') || request()->routeIs('interactive-map') ? 'active' : '' }}" href="#" id="infoDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-info-circle"></i>
                            <span>{{ __('ui.information') }}</span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="infoDropdown">
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('travel-basics.*') ? 'active' : '' }}" href="{{ route('travel-basics.index') }}">
                                    <i class="fas fa-suitcase-rolling"></i>
                                    <span>{{ __('ui.travel_basics') }}</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('events.*') ? 'active' : '' }}" href="{{ route('events.calendar') }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ __('ui.events_calendar') }}</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->routeIs('interactive-map') ? 'active' : '' }}" href="{{ route('interactive-map') }}">
                                    <i class="fas fa-map"></i>
                                    <span>{{ __('ui.interactive_map') }}</span>
                                </a>
                            </li>
                            @guest
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('provider.register') ? 'active' : '' }}" href="{{ route('provider.register') }}">
                                        <i class="fas fa-handshake"></i>
                                        <span>{{ __('website.layout.provider_signup') }}</span>
                                    </a>
                                </li>
                            @else
                                @if(!auth()->user()->isApprovedContentProvider())
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item {{ request()->routeIs('provider.register') ? 'active' : '' }}" href="{{ route('provider.register') }}">
                                            <i class="fas fa-handshake"></i>
                                            <span>{{ __('website.layout.provider_signup') }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endguest
                        </ul>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userMenuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ \Illuminate\Support\Str::limit(auth()->user()->name, 18) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('bookings.index') }}">
                                        <i class="fas fa-calendar-check"></i>
                                        <span>{{ __('website.layout.my_bookings') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('favorites.index') }}">
                                        <i class="fas fa-heart"></i>
                                        <span>{{ __('website.layout.favorites') }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-bell"></i>
                                        <span>{{ __('website.layout.notifications') }}</span>
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <span class="badge bg-danger ms-2">{{ auth()->user()->unreadNotifications->count() }}</span>
                                        @endif
                                    </a>
                                </li>
                                @if(auth()->user()->isApprovedContentProvider())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('provider.dashboard') }}">
                                            <i class="fas fa-chart-line"></i>
                                            <span>{{ __('website.layout.provider_dashboard') }}</span>
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->role === 'admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <span>{{ __('website.layout.admin_dashboard') }}</span>
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>{{ __('website.layout.logout') }}</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('notifications.index') }}" title="{{ __('website.layout.notifications') }}">
                                <i class="fas fa-bell"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="notification-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                                @endif
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-outline-primary me-2" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>
                                <span>{{ __('ui.login') }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>
                                <span>{{ __('ui.register_now') }}</span>
                            </a>
                        </li>
                    @endauth
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-secondary" href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}">
                            {{ app()->getLocale() === 'ar' ? 'EN' : 'AR' }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container fade-in">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>{{ __('website.layout.errors_happened') }}</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-md-start">
                    <h5 class="mb-3">
                        <i class="fas fa-landmark me-2"></i>
                        Wander Point in Syria
                    </h5>
                    <p class="mb-0 text-muted">
                        {{ __('website.layout.footer_tagline') }}
                    </p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <p class="mb-2">
                        <a href="{{ route('contact.create') }}" class="text-white text-decoration-none me-3">
                            <i class="fas fa-envelope"></i> {{ __('website.layout.contact_us') }}
                        </a>
                        @guest
                            <a href="{{ route('provider.register') }}" class="text-white text-decoration-none">
                                <i class="fas fa-handshake"></i> {{ __('website.layout.provider_partnership') }}
                            </a>
                        @else
                            @if(!auth()->user()->isApprovedContentProvider())
                                <a href="{{ route('provider.register') }}" class="text-white text-decoration-none">
                                    <i class="fas fa-handshake"></i> {{ __('website.layout.provider_partnership') }}
                                </a>
                            @endif
                        @endguest
                    </p>
                    <p class="mb-0 text-muted">
                        © {{ date('Y') }} {{ __('website.layout.copyright') }}
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect - Visit Saudi Style
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Active link highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
            
            navLinks.forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (currentPath === linkPath || 
                    (currentPath !== '/' && linkPath !== '/' && currentPath.startsWith(linkPath))) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>



