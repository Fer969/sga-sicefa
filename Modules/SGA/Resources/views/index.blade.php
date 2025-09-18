<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistema de Gestión de Almuerzos (SGA) - CEFA">

    <!-- title -->
    <title>{{ trans('sga::menu.sga-title') }}</title>

    <!-- favicon -->
    <link rel="shortcut icon" type="image/png" href="assets/img/favicon.png">
    <!-- google font -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,700&display=swap" rel="stylesheet">
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        /* Navbar */
        .navbar {
            background: linear-gradient(135deg, #14532d 0%, #064e3b 100%); /* Match hero gradient */
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        body.scrolled {
            padding-top: 70px; /* Prevent content from being hidden under fixed navbar */
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .navbar-nav {
            margin-left: auto; /* Align nav items to the right */
        }
        
        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 15px;
            transition: color 0.3s ease;
        }
        
        .navbar-nav .nav-link:hover {
            color: #a3e635 !important; /* Light green for hover */
        }
        
        .navbar-nav .nav-link.active {
            color: #a3e635 !important; /* Light green for active */
        }
        
        .info-user {
            color: white;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-right: 15px;
        }
        
        .info-user a,
        .info a {
            color: white;
            text-decoration: none;
        }
        
        .info-user a:hover,
        .info a:hover {
            color: #a3e635;
        }
        
        .info-user .small {
            color: #d1d5db; /* Light gray for role */
        }
        
        .info {
            display: flex;
            align-items: center;
        }
        
        .info i {
            font-size: 1.2rem;
        }
        
        /* Language Dropdown */
        .dropdown-menu {
            background: #ffffff;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        
        .dropdown-item {
            color: #1a1a1a;
            font-weight: 500;
            padding: 10px 20px;
            transition: background 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: #f8f9fa;
            color: #047857; /* Match green theme */
        }
        
        .dropdown-item.active {
            background: #e6f4ea; /* Light green background for active */
            color: #047857;
        }
        
        .dropdown-header {
            color: #047857; /* Match green theme */
            font-weight: 600;
        }
        
        .flag-icon {
            font-size: 1.2rem;
            margin-right: 8px;
        }
        
        .lang-text, .lang-code {
            font-weight: 500;
        }
        
        /* Hero principal */
        .hero-area {
            background: linear-gradient(135deg, #14532d 0%, #064e3b 100%); /* Match navbar gradient */
            padding: 120px 0;
            color: white;
            text-align: center;
        }
        
        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .hero-text .subtitle {
            font-size: 1.2rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .hero-text p {
            max-width: 700px;
            margin: 0 auto 30px auto;
            font-size: 1rem;
        }
        
        /* Botón principal */
        .btn-primary-custom {
            background: linear-gradient(45deg, #065f46 0%, #047857 100%); /* verde bosque */
            border: none;
            padding: 15px 35px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 20px rgba(0,0,0,0.2);
            color: white;
        }
        
        /* Botón secundario */
        .btn-secondary-custom {
            background: transparent;
            border: 2px solid white;
            padding: 15px 35px;
            border-radius: 50px;
            color: white;
            font-weight: 600;
            margin-left: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-secondary-custom:hover {
            background: white;
            color: #064e3b;
        }

        /* Features section */
        .features-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .feature-icon {
            font-size: 3rem;
            color: #047857; /* Adjusted to match green theme */
            margin-bottom: 20px;
        }
        
        /* System info section */
        .system-info {
            padding: 80px 0;
        }
        
        .system-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .system-header {
            background: linear-gradient(135deg, #14532d 0%, #064e3b 100%); /* Match hero gradient */
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .system-body {
            padding: 40px;
        }
        
        /* Developers section */
        .developers-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .developer-card {
            border: none;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            overflow: hidden;
        }
        
        .developer-card:hover {
            transform: translateY(-5px);
        }
        
        .developer-img-card {
            border-right: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 15px 0 0 15px;
            padding: 20px;
        }
        
        .developer-img {
            width: 140px;
            height: 140px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #ffffff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .developer-info-card {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .developer-info-card h5 {
            font-size: 1.5rem;
            color: #1a1a1a;
            margin-bottom: 0.5rem;
        }
        
        .developer-info-card p {
            color: #4a4a4a;
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }
        
        .social-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-github {
            background: #065f46; /* Match green theme */
            color: #ffffff;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.9rem;
            transition: background 0.3s ease;
            text-decoration: none;
        }
        
        .btn-github:hover {
            background: #047857;
        }
        
        .btn-linkedin {
            background: #065f46; /* Match green theme */
            color: #ffffff;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.9rem;
            transition: background 0.3s ease;
            text-decoration: none;
        }
        
        .btn-linkedin:hover {
            background: #047857;
        }
        
        .btn-whatsapp {
            background: #065f46; /* Match green theme */
            color: #ffffff;
            border-radius: 8px;
            padding: 8px 16px;
            font-size: 0.9rem;
            transition: background 0.3s ease;
            text-decoration: none;
        }
        
        .btn-whatsapp:hover {
            background: #047857;
        }
        
        /* Footer */
        .footer-area {
            background: #064e3b;
            color: white;
            padding: 60px 0 20px;
        }
        
        .footer-box h3 {
            color: #047857; /* Adjusted to match green theme */
            margin-bottom: 20px;
        }
        
        .copyright {
            background: #042f2e;
            padding: 20px 0;
            text-align: center;
            color: #95a5a6;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-text h1 {
                font-size: 2.5rem;
            }
            
            .system-body {
                padding: 20px;
            }
            
            .navbar-nav {
                text-align: center;
            }
            
            .info-user {
                align-items: center;
                margin-right: 0;
            }
            
            .info {
                margin-left: 0;
            }
            
            .developer-img-card {
                border-right: none;
                border-bottom: 1px solid #e9ecef;
                border-radius: 15px 15px 0 0;
            }
            
            .social-buttons {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-utensils me-2"></i>SGA
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#inicio">{{ trans('sga::menu.Home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#funciones">{{ trans('sga::menu.functions') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sistema">{{ trans('sga::menu.system') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">{{ trans('sga::menu.contact-us') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#desarrolladores">{{ trans('sga::menu.developers') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cefa.sga.home.manual') }}">Manual de Usuario</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            @php
                                $currentLang = session('lang', 'es');
                                $flags = [
                                    'es' => '🇪🇸',
                                    'en' => '🇺🇸',
                                    'de' => '🇩🇪',
                                    'fr' => '🇫🇷'
                                ];
                                $languages = [
                                    'es' => 'Español',
                                    'en' => 'English',
                                    'de' => 'Deutsch',
                                    'fr' => 'Français'
                                ];
                            @endphp
                            <span class="flag-icon">{{ $flags[$currentLang] ?? '🌐' }}</span>
                            <span class="lang-text d-none d-md-inline">{{ $languages[$currentLang] ?? session('lang') }}</span>
                            <span class="lang-code d-md-none">{{ strtoupper($currentLang) }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow-sm">
                            <h6 class="dropdown-header">
                                <i class="fas fa-globe me-2"></i>{{ trans('sga::menu.select_language') ?? 'Seleccionar idioma' }}
                            </h6>
                            <div class="dropdown-divider"></div>
                            <a href="{{ url('lang', ['es']) }}" class="dropdown-item {{ session('lang') == 'es' ? 'active' : '' }}">
                                <span class="flag-icon me-2">🇪🇸</span>
                                <span class="lang-name">{{ trans('sga::menu.es') ?? 'Español' }}</span>
                                @if(session('lang') == 'es')
                                    <i class="fas fa-check text-success ms-auto"></i>
                                @endif
                            </a>
                            <a href="{{ url('lang', ['en']) }}" class="dropdown-item {{ session('lang') == 'en' ? 'active' : '' }}">
                                <span class="flag-icon me-2">🇺🇸</span>
                                <span class="lang-name">{{ trans('sga::menu.en') ?? 'English' }}</span>
                                @if(session('lang') == 'en')
                                    <i class="fas fa-check text-success ms-auto"></i>
                                @endif
                            </a>
                        </div>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        @guest
                            <!-- Información del usuario invitado -->
                            <div class="info info-user flex-grow-1 ml-2">
                                <div>
                                    <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="d-block">{{ trans('Auth.Login') }}</a>
                                </div>
                            </div>
                            <!-- Botón de login -->
                            <div class="info ml-auto" data-toggle="tooltip" data-placement="right" title="{{ trans('Auth.Login') }}">
                                <a href="{{ route('login', ['redirect' => url()->current()]) }}" class="d-block">
                                    <i class="fas fa-sign-in-alt"></i>
                                </a>
                            </div>
                        @else
                            <!-- Información del usuario autenticado -->
                            <div class="info info-user flex-grow-1 ml-2">
                                <div data-toggle="tooltip" data-placement="top" title="{{ Auth::user()->person->first_name }} {{ Auth::user()->person->first_last_name }} {{ Auth::user()->person->second_last_name }}">
                                    <div>{{ Auth::user()->nickname }}</div>
                                </div>
                                <div class="small">
                                    <em>{{ Auth::user()->roles[0]->name }}</em>
                                </div>
                            </div>
                            <!-- Botón de logout -->
                            <div class="info ml-auto" data-toggle="tooltip" data-placement="right" title="{{ trans('Auth.Logout') }}">
                                <a href="{{ route('logout') }}" class="d-block" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </div>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        @endguest
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- end header -->

    <!-- Hero Area -->
    <div class="hero-area" id="inicio">
        <div class="container">
            <div class="hero-text">
                <h1>{{ trans('sga::menu.sga-title') }}</h1>
                <p class="subtitle">{{ trans('sga::contents.sga-description1') }}</p>

                <p>{{ trans('sga::contents.sga-text1') }}</p>

                @php
                    use Illuminate\Support\Facades\DB;

                    $redirectRoute = null;
                    $hasPermission = true;

                    if (auth()->check()) {
                        $userId = auth()->user()->id;

                        $roleSlugs = DB::table('role_user')
                            ->join('roles', 'role_user.role_id', '=', 'roles.id')
                            ->where('role_user.user_id', $userId)
                            ->pluck('roles.slug')
                            ->toArray();

                        if (in_array('sga.admin', $roleSlugs)) {
                            $redirectRoute = route('cefa.sga.admin.index');
                            $hasPermission = true;
                        } elseif (in_array('sga.staff', $roleSlugs)) {
                            $redirectRoute = route('cefa.sga.staff.index');
                            $hasPermission = true;
                        } elseif (in_array('sga.apprentice', $roleSlugs)) {
                            $redirectRoute = route('cefa.sga.apprentice.index');
                            $hasPermission = true;
                        } else {
                            $hasPermission = false;
                        }
                    }
                @endphp

                @if (auth()->check())
                    @if ($hasPermission && $redirectRoute)
                        <a href="{{ $redirectRoute }}" class="btn-primary-custom">
                            {{ trans('sga::contents.sga-button1') }}
                        </a>
                    @else
                        <div class="alert alert-danger mt-3">
                            {{ trans('sga::contents.sga-buttonalert1') }}
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn-secondary-custom">
                        Iniciar Sesión
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Features section -->
    <div class="features-section" id="funciones">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5">
                    <h2 class="display-5 fw-bold text-dark">{{ trans('sga::contents.sga-title1') }}</h2>
                    <p class="lead text-muted">{{ trans('sga::contents.sga-subtitle1') }}</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h4 class="mb-3">{{ trans('sga::contents.sga-title2') }}</h4>
                        <p class="text-muted">{{ trans('sga::contents.sga-description2') }}</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h4 class="mb-3">{{ trans('sga::contents.sga-title3') }}</h4>
                        <p class="text-muted">{{ trans('sga::contents.sga-description3') }}</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="mb-3">{{ trans('sga::contents.sga-title4') }}</h4>
                        <p class="text-muted">{{ trans('sga::contents.sga-description4') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end features section -->

    <!-- System info section -->
    <div class="system-info" id="sistema">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="system-card">
                        <div class="system-header">
                            <h3 class="mb-0 fw-bold">
                                <i class="fas fa-utensils me-2"></i>
                                {{ trans('sga::menu.sga-title') }}
                            </h3>
                        </div>
                        <div class="system-body">
                            <p class="lead mb-4">
                                {{ trans('sga::contents.sga-description5') }}
                            </p>
                            @if (auth()->check())
                                @if ($hasPermission && $redirectRoute)
                                    <a href="{{ $redirectRoute }}" class="btn-primary-custom">
                                        <i class="fas fa-arrow-right me-2"></i>{{ trans('sga::contents.sga-button1') }}
                                    </a>
                                @else
                                    <div class="alert alert-warning-custom">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>{{ trans('sga::contents.sga-alert1') }}</strong> {{ trans('sga::contents.sga-alert1-info') }}
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info-custom">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>{{ trans('sga::contents.sga-alert2') }}</strong> {{ trans('sga::contents.sga-alert2-info') }}
                                </div>
                                <a href="{{ route('login') }}" class="btn-primary-custom">
                                    <i class="fas fa-sign-in-alt me-2"></i>{{ trans('sga::contents.sga-login') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end system info section -->

    <!-- Contact section -->
    <div class="features-section" id="contacto">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold text-dark mb-4">{{ trans('sga::contents.sga-title5') }}</h2>
                    <p class="lead text-muted mb-4">
                        {{ trans('sga::contents.sga-description6') }}
                    </p>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h5>{{ trans('sga::contents.sga-email') }}</h5>
                                <p class="text-muted">soporte.sga@cefa.edu.co</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <h5>{{ trans('sga::contents.sga-phone') }}</h5>
                                <p class="text-muted">+57 (1) 234-5678</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <h5>{{ trans('sga::contents.sga-horary') }}</h5>
                                <p class="text-muted">{{ trans('sga::contents.sga-horaryinfo') }} 7:30 AM - 15:30 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end contact section -->

    <!-- Developers section -->
    <div class="developers-section" id="desarrolladores">
        <div class="container">
            <h2 class="text-center mb-5"><strong>{{ trans('sga::contents.sga-title6') }}</strong></h2>
            <div class="row justify-content-center">
                <!-- Developer 1 -->
                <div class="col-sm-12 col-md-10 col-lg-8 mb-4">
                    <div class="developer-card">
                        <div class="row g-0">
                            <div class="col-md-4 developer-img-card">
                                <img src="{{ asset('modules/sga/img/rene_developer.jpeg') }}" class="developer-img" alt="René Cabrera Cerón">
                            </div>
                            <div class="col-md-8 developer-info-card">
                                <h5><strong>René Cabrera Cerón</strong></h5>
                                <p class="mb-1">{{ trans('sga::contents.sga-course1') }}</p>
                                <p>{{ trans('sga::contents.sga-descriptionrene') }}</p>
                                <div class="social-buttons">
                                    <a href="https://github.com/Rene182007" class="btn btn-github"><i class="fab fa-github"></i> GitHub</a>
                                    <a href="https://linkedin.com/in/renecabrera" class="btn btn-linkedin"><i class="fab fa-linkedin"></i> LinkedIn</a>
                                    <a href="https://wa.me/573133485905" class="btn btn-whatsapp"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Developer 2 -->
                <div class="col-sm-12 col-md-10 col-lg-8 mb-4">
                    <div class="developer-card">
                        <div class="row g-0">
                            <div class="col-md-4 developer-img-card">
                                <img src="{{ asset('modules/sga/img/luisfer_developer.jpeg') }}" class="developer-img" alt="Luisfer Fuentes Montoya">
                            </div>
                            <div class="col-md-8 developer-info-card">
                                <h5><strong>Luisfer Fuentes Montoya</strong></h5>
                                <p class="mb-1">{{ trans('sga::contents.sga-course1') }}</p>
                                <p>{{ trans('sga::contents.sga-descriptionluisfer') }}</p>
                                <div class="social-buttons">
                                    <a href="https://github.com/Fer969" class="btn btn-github"><i class="fab fa-github"></i> GitHub</a>
                                    <a href="https://www.linkedin.com/in/luisfer-fuentes-montoya-28ab14331/" class="btn btn-linkedin"><i class="fab fa-linkedin"></i> LinkedIn</a>
                                    <a href="https://wa.me/573214710122" class="btn btn-whatsapp"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Developer 3 -->
                <div class="col-sm-12 col-md-10 col-lg-8 mb-4">
                    <div class="developer-card">
                        <div class="row g-0">
                            <div class="col-md-4 developer-img-card">
                                <img src="{{ asset('modules/sga/img/baez_developer.jpeg') }}" class="developer-img" alt="Breiner Andrés Rodríguez Báez">
                            </div>
                            <div class="col-md-8 developer-info-card">
                                <h5><strong>Breiner Andrés Rodríguez Báez</strong></h5>
                                <p class="mb-1">{{ trans('sga::contents.sga-course1') }}</p>
                                <p>{{ trans('sga::contents.sga-descriptionbreiner') }}</p>
                                <div class="social-buttons">
                                    <a href="https://github.com/programmerbaez" class="btn btn-github"><i class="fab fa-github"></i> GitHub</a>
                                    <a href="https://www.linkedin.com/in/breiner-andr%C3%A9s-rodr%C3%ADguez-b%C3%A1ez-379182364/?trk=opento_sprofile_goalscard" class="btn btn-linkedin"><i class="fab fa-linkedin"></i> LinkedIn</a>
                                    <a href="https://wa.me/573212374198" class="btn btn-whatsapp"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end developers section -->

    <!-- Footer -->
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-box">
                        <h3>{{ trans('sga::contents.sga-About') }}</h3>
                        <p class="text-light">{{ trans('sga::contents.sga-Aboutinfo') }}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-box">
                        <h3>{{ trans('sga::menu.Contact') }}</h3>
                        <ul class="list-unstyled text-light">
                            <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Campoalegre, Huila, Colombia</li>
                            <li class="mb-2"><i class="fas fa-envelope me-2"></i>info@cefa.edu.co</li>
                            <li><i class="fas fa-phone me-2"></i>+57 (1) 234-5678</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="footer-box">
                        <h3>{{ trans('sga::contents.sga-QuickLinks') }}</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#inicio" class="text-light text-decoration-none">{{ trans('sga::menu.Home') }}</a></li>
                            <li class="mb-2"><a href="#sistema" class="text-light text-decoration-none">{{ trans('sga::menu.system') }}</a></li>
                            <li class="mb-2"><a href="#funciones" class="text-light text-decoration-none">{{ trans('sga::menu.functions') }}</a></li>
                            <li class="mb-2"><a href="#contacto" class="text-light text-decoration-none">{{ trans('sga::menu.Contact') }}</a></li>
                            <li class="mb-2"><a href="#desarrolladores" class="text-light text-decoration-none">{{ trans('sga::menu.Developers') }}</a></li>
                            <li><a href="{{ route('cefa.sga.home.manual') }}" class="text-light text-decoration-none">Manual de Usuario</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end footer -->
    
    <!-- copyright -->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="mb-0">{{ trans('sga::contents.sga-Copyright') }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- end copyright -->

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sticky navbar on scroll
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            const body = document.body;
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                body.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
                body.classList.remove('scrolled');
            }
        });

        // Smooth scroll para los enlaces de navegación
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const navbarHeight = document.querySelector('.navbar').offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Enable Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>