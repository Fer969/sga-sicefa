<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $titlePage ?? 'SGA' }}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                @auth
                @if (Route::is('cefa.sga.admin.*'))
                <li class="nav-item dropdown d-lg-inline-block">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <i class="fas fa-user-shield nav-icon"></i> {{ trans('sga::menu.Home') }}
                    </a>
                    <div class="dropdown-menu p-0">
                        <a href="{{ route('cefa.sga.admin.index') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.index') ? 'active' : '' }}">
                            <i class="fas fa-home nav-icon"></i> {{ trans('sga::menu.Home') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.staff') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.staff') ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i> {{ trans('sga::menu.Staff') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.apprentice') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.apprentice') ? 'active' : '' }}">
                            <i class="fas fa-user-graduate nav-icon"></i> {{ trans('sga::menu.Apprentices') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.asv') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.asv') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check nav-icon"></i> {{ trans('sga::menu.Attendance') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.s-assgn') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.s-assgn') ? 'active' : '' }}">
                            <i class="fas fa-star nav-icon"></i> {{ trans('sga::menu.s-assgn') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.ev-history') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.ev-history') ? 'active' : '' }}">
                            <i class="fas fa-history nav-icon"></i> {{ trans('sga::menu.ev-history') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.b-summary') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.b-summary') ? 'active' : '' }}">
                            <i class="fas fa-list nav-icon"></i> {{ trans('sga::menu.b-summary') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.profile') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.profile') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon"></i> {{ trans('sga::menu.Profile') }}
                        </a>
                    </div>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Route::is('cefa.sga.admin.convocatories') || Route::is('cefa.sga.admin.points') || Route::is('cefa.sga.admin.external-events') ? 'active' : '' }}"
                        href="#"
                        id="navbarSysParams"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <i class="fas fa-cogs nav-icon"></i> {{ trans('sga::menu.sys-params') }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarSysParams">
                        <a href="{{ route('cefa.sga.admin.convocatories') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.convocatories') ? 'active' : '' }}">
                            <i class="fas fa-file-alt nav-icon"></i> {{ trans('sga::menu.convocatories') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.convocatory_points') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.convocatory_points') ? 'active' : '' }}">
                            <i class="fas fa-bullseye nav-icon"></i> {{ trans('sga::menu.points') }}
                        </a>
                        <a href="{{ route('cefa.sga.admin.external-events') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.admin.external-events') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt nav-icon"></i> {{ trans('sga::menu.external-events') }}
                        </a>
                    </div>
                </li>
                </li>
                @endif
                @if (Route::is('cefa.sga.staff.*'))
                <li class="nav-item dropdown d-lg-inline-block">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <i class="fas fa-user-tie nav-icon"></i> {{ trans('sga::menu.Home') }}
                    </a>
                    <div class="dropdown-menu p-0">
                        <a href="{{ route('cefa.sga.staff.index') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.staff.index') ? 'active' : '' }}">
                            <i class="fas fa-home nav-icon"></i> {{ trans('sga::menu.Home') }}
                        </a>
                        <a href="{{ route('cefa.sga.staff.ops-reports') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.staff.ops-reports') ? 'active' : '' }}">
                            <i class="fas fa-chart-bar nav-icon"></i> {{ trans('sga::menu.ops-reports') }}
                        </a>
                        <a href="{{ route('cefa.sga.staff.rec-validation') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.staff.rec-validation') ? 'active' : '' }}">
                            <i class="fas fa-check-square nav-icon"></i> {{ trans('sga::menu.attendance-registration') }}
                        </a>
                        <a href="{{ route('cefa.sga.staff.incidents') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.staff.incidents') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-triangle nav-icon"></i> {{ trans('sga::menu.incident-management') }}
                        </a>
                        <a href="{{ route('cefa.sga.staff.profile') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.staff.profile') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon"></i> {{ trans('sga::menu.Profile') }}
                        </a>
                    </div>
                </li>
                @endif
                @if (Route::is('cefa.sga.apprentice.*'))
                <li class="nav-item dropdown d-lg-inline-block">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">
                        <i class="fas fa-user-graduate nav-icon"></i> {{ trans('sga::menu.Home') }}
                    </a>
                    <div class="dropdown-menu p-0">
                        <a href="{{ route('cefa.sga.apprentice.index') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.apprentice.index') ? 'active' : '' }}">
                            <i class="fas fa-home nav-icon"></i> {{ trans('sga::menu.Home') }}
                        </a>
                        <a href="{{ route('cefa.sga.apprentice.my-benefit') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.apprentice.my-benefit') ? 'active' : '' }}">
                            <i class="fas fa-gift nav-icon"></i> {{ trans('sga::menu.my-benefit') }}
                        </a>
                        <a href="{{ route('cefa.sga.apprentice.ben-history') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.apprentice.ben-history') ? 'active' : '' }}">
                            <i class="fas fa-history nav-icon"></i> {{ trans('sga::menu.history-benefit') }}
                        </a>
                        <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.apprentice.apply-to-call') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane nav-icon"></i> {{ trans('sga::menu.apply-to-call') }}
                        </a>
                        <a href="{{ route('cefa.sga.apprentice.profile') }}"
                            class="dropdown-item {{ Route::is('cefa.sga.apprentice.profile') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon"></i> {{ trans('sga::menu.Profile') }}
                        </a>
                    </div>
                </li>
                @endif
                @endauth
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="{{ route('cefa.sga.home.manual') }}" class="nav-link" id="question">
                        <i class="fas fa-question-circle"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <!-- Language Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
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
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-success elevation-4">
            <!-- Brand Logo -->
            <a class="brand-link" style="text-decoration: none;">
                <img src="{{ asset('modules/sga/img/logo.png') }}" alt="SGA Logo" class="brand-image" style="opacity: .8; color: white">
                <span class="brand-text h3">SGA</span>
            </a>
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-1 pb-1 mb-1 d-flex align-items-center">
                    <!-- Imagen del usuario -->
                    <div class="image">
                        @if (isset(Auth::user()->person->avatar))
                        <img src="{{ asset('storage/' . Auth::user()->person->avatar) }}" class="img-circle elevation-2" alt="User Image">
                        @else
                        <img src="{{ asset('modules/sga/img/profile_user.png') }}" class="img-circle elevation-2" alt="User Image">
                        @endif
                    </div>

                    @guest
                    <!-- Información del usuario invitado -->
                    <div class="info info-user flex-grow-1 ml-2">
                        <div>{{ trans('menu.Welcome') }}</div>
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
                            <div style="color:dark">{{ Auth::user()->nickname }}</div>
                        </div>
                        <div class="small" style="color:dark">
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
                </div>

                <div class="user-panel mt-1 pb-1 mb-1 d-flex">
                    <nav>
                        <ul class="nav nav-pills nav-sidebar flex-column">
                            <li class="nav-item">
                                <a href="{{ route('cefa.welcome') }}" class="nav-link {{ Route::is('cefa.contact.maps') ? '' : 'active' }}">
                                    <i class="fas fa-puzzle-piece nav-icon"></i>
                                    <p>{{ trans('sica::menu.Back to') }} SICEFA</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- MENU PARA HOME (DE ACCESO GENERAL) -->
                        @if (Route::is('*sga.home.*'))
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.home.index') }}"
                                class="nav-link" {{ Route::is('cefa.sga.home.index') ? 'active' : '' }}>
                                <i class="fas fa-home nav-icon"></i>
                                <p>{{ trans('sga::menu.Home') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.home.developers') }}"
                                class="nav-link {{ Route::is('cefa.sga.home.developers') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon"></i>
                                <p>{{ trans('sga::menu.Developers') }}</p>
                            </a>
                        </li>
                        @endif
                        <!-- Menú Administrator -->
                        @if (Route::is('cefa.sga.admin.*'))
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.admin.index') }}"
                                class="nav-link {{ Route::is('cefa.sga.admin.index') ? 'active' : '' }}">
                                <i class="fas fa-home nav-icon"></i>
                                <p>{{ trans('sga::menu.Home') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.admin.staff') }}"
                                class="nav-link" {{ Route::is('cefa.sga.admin.staff') ? 'active' : '' }}>
                                <i class="fas fa-users nav-icon"></i>
                                <p>{{ trans('sga::menu.Staff') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.admin.apprentice') }}"
                                class="nav-link {{ Route::is('cefa.sga.admin.apprentice') ? 'active' : '' }}">
                                <i class="fas fa-user-graduate nav-icon"></i>
                                <p>{{ trans('sga::menu.Apprentices') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.admin.asv') }}"
                                class="nav-link {{ Route::is('cefa.sga.admin.asv') ? 'active' : '' }}">
                                <i class="fas fa-clipboard-check nav-icon"></i>
                                <p>{{ trans('sga::menu.Attendance') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.admin.s-assgn') }}"
                                class="nav-link {{ Route::is('cefa.sga.admin.s-assgn') ? 'active' : '' }}">
                                <i class="fas fa-star nav-icon"></i>
                                <p>{{ trans('sga::menu.s-assgn') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.admin.ev-history') }}"
                                class="nav-link {{ Route::is('cefa.sga.admin.ev-history') ? 'active' : '' }}">
                                <i class="fas fa-history nav-icon"></i>
                                <p>{{ trans('sga::menu.ev-history') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.admin.b-summary') }}"
                                class="nav-link {{ Route::is('cefa.sga.admin.b-summary') ? 'active' : '' }}">
                                <i class="fas fa-list nav-icon"></i>
                                <p>{{ trans('sga::menu.b-summary') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>{{ trans('sga::menu.sys-params') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('cefa.sga.admin.convocatories') }}" class="nav-link {{ Route::is('cefa.sga.admin.convocatories') ? 'active' : '' }}">
                                        <i class="fas fa-file-alt nav-icon"></i>
                                        <p>{{ trans('sga::menu.convocatories') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('cefa.sga.admin.convocatory_points') }}" class="nav-link {{ Route::is('cefa.sga.admin.convocatory_points') ? 'active' : '' }}">
                                        <i class="fas fa-bullseye nav-icon"></i>
                                        <p>{{ trans('sga::menu.points') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('cefa.sga.admin.external-events') }}" class="nav-link {{ Route::is('cefa.sga.admin.external-events') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-alt nav-icon"></i>
                                        <p>{{ trans('sga::menu.external-events') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.admin.profile') }}"
                                class="nav-link {{ Route::is('cefa.sga.admin.profile') ? 'active' : '' }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>{{ trans('sga::menu.Profile') }}</p>
                            </a>
                        </li>
                        @endif
                        <!-- Menú Funcionario -->
                        @if (Route::is('cefa.sga.staff.*'))
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.staff.index') }}"
                                class="nav-link {{ Route::is('cefa.sga.staff.index') ? 'active' : '' }}">
                                <i class="fas fa-home nav-icon"></i>
                                <p>{{ trans('sga::menu.Home') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.staff.ops-reports') }}"
                                class="nav-link {{ Route::is('cefa.sga.staff.ops-reports') ? 'active' : '' }}">
                                <i class="fas fa-chart-bar nav-icon"></i>
                                <p>{{ trans('sga::menu.ops-reports') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.staff.rec-validation') }}"
                                class="nav-link {{ Route::is('cefa.sga.staff.rec-validation') ? 'active' : '' }}">
                                <i class="fas fa-check-square nav-icon"></i>
                                <p>{{ trans('sga::menu.attendance-registration') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.staff.incidents') }}"
                                class="nav-link {{ Route::is('cefa.sga.staff.incidents') ? 'active' : '' }}">
                                <i class="fas fa-exclamation-triangle nav-icon"></i>
                                <p>{{ trans('sga::menu.incident-management') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.staff.profile') }}"
                                class="nav-link {{ Route::is('cefa.sga.staff.profile') ? 'active' : '' }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>{{ trans('sga::menu.Profile') }}</p>
                            </a>
                        </li>
                        @endif
                        <!-- Menú Apprentice -->
                        @if (Route::is('cefa.sga.apprentice.*'))
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.apprentice.index') }}"
                                class="nav-link {{ Route::is('cefa.sga.apprentice.index') ? 'active' : '' }}">
                                <i class="fas fa-home nav-icon"></i>
                                <p>{{ trans('sga::menu.Home') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.apprentice.my-benefit') }}"
                                class="nav-link {{ Route::is('cefa.sga.apprentice.my-benefit') ? 'active' : '' }}">
                                <i class="fas fa-gift nav-icon"></i>
                                <p>{{ trans('sga::menu.my-benefit') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.apprentice.ben-history') }}"
                                class="nav-link {{ Route::is('cefa.sga.apprentice.ben-history') ? 'active' : '' }}">
                                <i class="fas fa-history nav-icon"></i>
                                <p>{{ trans('sga::menu.history-benefit') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}"
                                class="nav-link {{ Route::is('cefa.sga.apprentice.apply-to-call') ? 'active' : '' }}">
                                <i class="fas fa-paper-plane nav-icon"></i>
                                <p>{{ trans('sga::menu.apply-to-call') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cefa.sga.apprentice.profile') }}"
                                class="nav-link {{ Route::is('cefa.sga.apprentice.profile') ? 'active' : '' }}">
                                <i class="fas fa-user nav-icon"></i>
                                <p>{{ trans('sga::menu.Profile') }}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div id="divbreadcrumb" class="row mb-2">
                        <div class="col-sm-12">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item active">SGA</li>
                                <li class="breadcrumb-item active">{{ $titlePage }}</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                {{ trans('sga::contents.FooterText-2') }}
            </div>
            <!-- Default to the left -->
            {{ trans('sga::contents.FooterText-1') }}
        </footer>
        <!-- /.footer -->
    </div>
    <!-- ./wrapper -->

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <style>
        /* Estilos para el selector de idiomas */
        .flag-icon {
            font-size: 1.2em;
            display: inline-block;
            min-width: 20px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            transform: translateX(2px);
        }

        .dropdown-item.active {
            background-color: #e3f2fd;
            color: #1976d2;
            font-weight: 500;
        }

        .lang-name {
            flex-grow: 1;
        }

        .dropdown-header {
            font-size: 0.875rem;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-link .flag-icon {
            margin-right: 0.5rem;
        }

        .lang-text {
            font-weight: 500;
        }

        .lang-code {
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Animación hover para el botón principal */
        .nav-link:hover .flag-icon {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        /* Mejora visual del dropdown */
        .dropdown-menu {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            min-width: 180px;
            padding: 0.5rem 0;
        }

        .dropdown-divider {
            margin: 0.5rem 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dropdown-menu {
                min-width: 150px;
            }

            .flag-icon {
                font-size: 1.1em;
            }
        }
    </style>
</body>

</html>