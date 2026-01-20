<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('nav.pharmacy_system'))</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/default.jpg') }}">

    <!-- Bootstrap 5 CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom RTL Fixes -->
    @if(app()->getLocale() == 'ar')
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: right;
        }
        
        .navbar-brand {
            margin-right: 0;
            margin-left: 1rem;
        }
        
        .ms-auto {
            margin-left: 0 !important;
            margin-right: auto !important;
        }
        
        .btn-group {
            flex-direction: row-reverse;
        }
        
        .gap-2 > * + * {
            margin-right: 0.5rem;
            margin-left: 0;
        }
    </style>
    @endif
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-hospital"></i> {{ __('nav.pharmacy_system') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav {{ app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto' }}">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam"></i> {{ __('nav.products') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pharmacies.*') ? 'active' : '' }}" href="{{ route('pharmacies.index') }}">
                            <i class="bi bi-shop"></i> {{ __('nav.pharmacies') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.search') ? 'active' : '' }}" href="{{ route('products.search') }}">
                            <i class="bi bi-search"></i> {{ __('nav.search') }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-translate"></i> {{ __('nav.language') }}
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">
                                    English
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}" href="{{ route('language.switch', 'ar') }}">
                                    العربية
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="py-4">
        <div class="container">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">
                        <i class="bi bi-exclamation-triangle"></i> {{ __('messages.validation_errors') }}
                    </h5>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-light text-center py-4 mt-5">
        <div class="container">
            <p class="text-muted mb-0">
                &copy; {{ date('Y') }} {{ __('nav.pharmacy_system') }}. {{ __('nav.all_rights_reserved') }}
            </p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>