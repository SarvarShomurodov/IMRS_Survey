{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title', 'Бош саҳифа')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
            font-size: 16px; /* Improved base font size for mobile */
        }
        
        /* Enhanced Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, #01354d 0%, #005274 100%) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            transition: all 0.3s ease;
            min-height: 70px;
        }
        
        .navbar-brand {
            font-weight: 600;
            font-size: 1.3rem;
            color: white !important;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
            padding: 0.5rem 0;
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
            color: #f8f9fa !important;
        }
        
        .navbar-brand img {
            height: 100px;
            width: auto;
            margin-left: 40px;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        
        .navbar-brand-text {
            background: linear-gradient(45deg, #ffffff, #e3f2fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1rem;
        }
        
        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.75rem 1rem !important;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.95rem;
        }
        
        .navbar-nav .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }
        
        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: white;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .navbar-nav .nav-link:hover::before {
            width: 80%;
        }
        
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 0.5rem 0;
            margin-top: 0.5rem;
            min-width: 200px;
        }
        
        .dropdown-item {
            color: #333 !important;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0.25rem 0.5rem;
            font-size: 0.9rem;
        }
        
        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white !important;
            transform: translateX(5px);
        }
        
        .dropdown-item i {
            width: 20px;
            margin-right: 8px;
        }
        
        
        /* Mobile-First Responsive Design */
        @media (max-width: 575px) {
            /* Extra small devices */
            body {
                font-size: 14px;
            }
            
            .navbar {
                padding: 0.25rem 0;
                min-height: 60px;
            }
            
            .navbar-brand {
                font-size: 1rem;
                padding: 0.25rem 0;
            }
            
            .navbar-brand img {
                height: 65px;
                margin-left: 10px;
            }
            
            .navbar-brand-text {
                font-size: 0.85rem;
                display: none; /* Hide text on very small screens */
            }
            
            .container {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }
            
            .card {
                margin: 0.5rem 0;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .btn-group .btn {
                width: auto;
                margin-bottom: 0;
            }
            
            .form-control, .form-select {
                font-size: 16px; /* Prevents zoom on iOS */
                padding: 0.75rem;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .modal-dialog {
                margin: 0.5rem;
            }
            
            .alert {
                font-size: 0.9rem;
                padding: 0.75rem;
            }
            
            .navbar-collapse {
                background: rgba(0, 30, 60, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                margin: 0.5rem -10px 0 -10px;
                padding: 1rem;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        }
        @media (max-width: 576px) {
    .d-flex.gap-2 {
        flex-direction: column;
    }

    .d-flex.gap-2 .btn {
        width: 100%; /* Har biri to‘liq kenglikda */
    }
}
        @media (min-width: 576px) and (max-width: 767px) {
            /* Small devices */
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            .navbar-brand img {
                height: 50px;
            }
            
            .navbar-brand-text {
                font-size: 0.9rem;
            }
            
            .container {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }
        
        @media (min-width: 768px) and (max-width: 991px) {
            /* Medium devices */
            .navbar-brand img {
                height: 55px;
            }
            
            .container {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
            
            .navbar-collapse {
                background: rgba(0, 30, 60, 0.9);
                backdrop-filter: blur(10px);
                border-radius: 12px;
                margin-top: 0.75rem;
                padding: 1rem;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }
        }
        
        @media (max-width: 991px) {
            /* All mobile and tablet devices */
            .navbar-nav .nav-link {
                text-align: center;
                margin: 0.25rem 0;
                border-radius: 8px;
                background: rgba(255, 255, 255, 0.05);
            }
            
            .navbar-nav .nav-link:hover {
                background: rgba(255, 255, 255, 0.15);
            }
            
            .dropdown-menu {
                background: rgba(255, 255, 255, 0.98);
                position: static !important;
                transform: none !important;
                box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.1);
                margin: 0.5rem 0;
                border-radius: 8px;
            }
            
            .dropdown-item {
                text-align: center;
                margin: 0.25rem;
                border-radius: 6px;
            }
            
            /* Improve form layouts on mobile */
            .row > [class*='col-'] {
                margin-bottom: 1rem;
            }
            
            .input-group {
                flex-direction: column;
            }
            
            .input-group .btn {
                border-radius: 0.5rem !important;
                margin-top: 0.5rem;
            }
        }
        
        @media (min-width: 992px) {
            /* Desktop devices */
            .navbar-brand img {
                height: 70px;
                margin-right: 12px;
            }
            
            .navbar-brand {
                font-size: 1.4rem;
            }
            
            .navbar-brand-text {
                font-size: 1.1rem;
            }
        }
        
        @media (min-width: 1200px) {
            /* Large desktop devices */
            .navbar-brand img {
                height: 100px;
            }
        }
        
        /* Rest of the existing styles with mobile improvements */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        
        .btn {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .form-control, .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e0e7ff;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #01374f;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15);
            transform: translateY(-1px);
        }
        
        .progress-bar {
            background-color: #01374f;
        }
        
        .alert {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
        }
        
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 0.5rem;
            min-height: calc(3.5rem + 2px);
        }
        
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding: 0.75rem 1rem;
            line-height: 1.5;
        }
        
        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }
        
        /* Improved table responsiveness */
        .table-responsive {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            white-space: nowrap;
        }
        
        /* Modal improvements for mobile */
        .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }
        
        .modal-header {
            border-bottom: 1px solid #e9ecef;
            border-radius: 1rem 1rem 0 0;
        }
        
        .modal-footer {
            border-top: 1px solid #e9ecef;
            border-radius: 0 0 1rem 1rem;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Touch-friendly improvements */
        @media (hover: none) and (pointer: coarse) {
            .btn:hover {
                transform: none;
            }
            
            .navbar-nav .nav-link:hover {
                transform: none;
            }
            
            .form-control:focus, .form-select:focus {
                transform: none;
            }
        }
        
        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="{{ route('home') }}">
                {{-- Logo rasmini qo'ying (public/images/screen.png yo'li) --}}
                <img src="{{ asset('images/IMRS_1.png') }}" alt="Logo">
                {{-- <span class="navbar-brand-text">Меҳнат Бозори Сўровномаси</span> --}}
            </a>
        <div class="container">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(Auth::user()->isAdmin())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog me-2"></i>Админ
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="fas fa-tachometer-alt"></i>Дашборд
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.responses') }}">
                                            <i class="fas fa-clipboard-list"></i>Сўровномалар
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.skills.statistics') }}">
                                            <i class="fas fa-chart-bar"></i>Кадрлар статистикаси
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @else
                      
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="container">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        &copy; {{ date('Y') }} Меҳнат Бозори Талабини Аниқлаш Сўровномаси
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        Вазирлар Маҳкамаси ҳузуридаги Макроиқтисодий тадқиқотлар институти
                    </small>
                </div>
            </div>
        </div>
    </footer> --}}

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        // Global CSRF token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Smooth navbar scroll effect
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('.navbar').addClass('navbar-scrolled');
            } else {
                $('.navbar').removeClass('navbar-scrolled');
            }
        });
        
        // Mobile menu close on item click
        $('.navbar-nav .nav-link').on('click', function() {
            // Menu closing functionality removed as toggler is removed
        });
        
        // Improve Select2 for mobile
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                dropdownAutoWidth: true,
                width: '100%'
            });
            
            // Mobile-specific Select2 improvements
            if (window.innerWidth <= 768) {
                $('.select2').on('select2:open', function() {
                    setTimeout(function() {
                        $('.select2-dropdown').css({
                            'max-height': '200px',
                            'overflow-y': 'auto'
                        });
                    }, 1);
                });
            }
        });
        
        // Touch-friendly button feedback
        $('.btn').on('touchstart', function() {
            $(this).addClass('active');
        }).on('touchend touchcancel', function() {
            var btn = $(this);
            setTimeout(function() {
                btn.removeClass('active');
            }, 150);
        });
        
        // Prevent double-tap zoom on buttons
        $('.btn').on('touchend', function(e) {
            e.preventDefault();
            $(this).click();
        });
        
        // Loading state management
        function showLoading(element) {
            element.addClass('loading').prop('disabled', true);
        }
        
        function hideLoading(element) {
            element.removeClass('loading').prop('disabled', false);
        }
        
        // Form submission loading states
        $('form').on('submit', function() {
            var submitBtn = $(this).find('[type="submit"]');
            showLoading(submitBtn);
        });
        
        // AJAX loading states
        $(document).ajaxStart(function() {
            $('body').addClass('loading');
        }).ajaxStop(function() {
            $('body').removeClass('loading');
        });
    </script>
    
    @yield('scripts')
</body>
</html>