@extends('sga::layouts.master')



@section('content')
<style>
    .profile-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        min-height: 100vh;
        padding: 20px 0;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
    }



    .profile-header {
        background: linear-gradient(135deg, #495057 0%, #6c757d 100%);
        color: white;
        border-radius: 0;
        padding: 30px;
    }

    .profile-tabs {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        border-radius: 0;
        border: none;
        padding: 0 20px;
    }

    .nav-tabs .nav-link {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        padding: 18px 25px;
        transition: all 0.3s ease;
        position: relative;
        margin-right: 5px;
    }

    .nav-tabs .nav-link.active {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
    }

    .nav-tabs .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        transform: translateY(-2px);
    }

    .feature-highlight {
        padding: 15px;
        border-radius: 12px;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        border: 1px solid #ffeaa7;
        transition: all 0.3s ease;
    }

    .feature-highlight:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
    }

    .rounded-4 {
        border-radius: 1rem !important;
    }

    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.175) !important;
    }

    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    /* Estilos mejorados para el indicador de progreso */
    .progress-section {
        background: white;
        padding: 15px 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .progress-section small {
        font-size: 0.875rem;
        font-weight: 600;
    }

    .progress-section .progress {
        background-color: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-section .progress-bar {
        background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
    }

    /* Mejoras para el contenido de los tabs */
    .tab-content {
        background: white;
        padding: 30px;
        border-radius: 0 0 16px 16px;
        min-height: 400px;
    }

    /* Mejoras para las secciones */
    .section-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid #dee2e6;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -3px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }



    .section-title {
        color: #495057;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #dee2e6;
    }

    .form-label {
        color: #495057;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #6c757d;
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.15);
    }

    .btn-primary {
        background: #495057;
        border: 1px solid #495057;
        border-radius: 6px;
        padding: 12px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #343a40;
        border-color: #343a40;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(73, 80, 87, 0.2);
    }

    .btn-secondary {
        background: #6c757d;
        border: 1px solid #6c757d;
        border-radius: 6px;
        padding: 12px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #5a6268;
        border-color: #545b62;
        transform: translateY(-1px);
    }

    .btn-success {
        background: #28a745;
        border: 1px solid #28a745;
        border-radius: 6px;
        padding: 12px 25px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-success:hover {
        background: #218838;
        border-color: #1e7e34;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(40, 167, 69, 0.2);
    }

    .alert-success {
        background: rgba(40, 167, 69, 0.1);
        border: 1px solid #d4edda;
        color: #155724;
        border-radius: 6px;
    }

    .alert-danger {
        background: rgba(220, 53, 69, 0.1);
        border: 1px solid #f5c6cb;
        color: #721c24;
        border-radius: 6px;
    }

    .info-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid #dee2e6;
    }

    .info-item {
        margin-bottom: 10px;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
    }

    .info-value {
        color: #6c757d;
    }

    .section-divider {
        height: 1px;
        background: #dee2e6;
        border: none;
        margin: 30px 0;
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
    }
    
    /* Estilos para el botón Guardar Todo */
    .btn-lg {
        font-size: 1.1rem;
        padding: 15px 30px;
        font-weight: 600;
    }
    
    .btn-lg:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    /* Estilos para la barra de progreso */
    .progress-section {
        background: rgba(255, 255, 255, 0.9);
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        transition: width 0.6s ease;
        border-radius: 10px;
    }
    
    /* Estilos para las alertas flotantes */
    .alert.position-fixed {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border: none;
        border-radius: 8px;
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    /* Estilos para alertas pequeñas */
    .alert-sm {
        padding: 8px 12px;
        font-size: 0.875rem;
        border-radius: 6px;
    }
</style>

<div class="profile-container">
    <div class="container">


        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="profile-card">
                    <!-- Header de la Card -->
                    <div class="profile-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                        <h2 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                                    Información del Perfil
                        </h2>
                                <p class="mb-0 mt-2 opacity-75">Completa todas las secciones para maximizar tu puntaje</p>
                            </div>
                            <div class="text-end">
                                <div class="progress-section">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-dark fw-bold">Progreso del Perfil</small>
                                        <small class="text-dark fw-bold" id="progressPercentage">0%</small>
                                    </div>
                                    <div class="progress bg-light" style="height: 10px; width: 150px; border: 1px solid #dee2e6;">
                                        <div class="progress-bar bg-success" id="progressBar" role="progressbar" style="width: 0%; transition: width 0.6s ease;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Información -->
                    <div class="bg-light p-4 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-primary btn-lg me-3" id="saveAllBtn" onclick="saveAllSections()">
                                        <i class="fas fa-save me-2"></i>Guardar Todo
                                    </button>
                                    <div class="text-muted">
                                        <small>Completa todas las secciones y guarda toda la información de una vez</small>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <div class="alert alert-info alert-sm mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Consejo:</strong> Completa tu perfil al 100% para maximizar tu puntaje en las convocatorias. 
                                        El botón "Guardar Todo" validará y guardará todas las secciones completadas automáticamente.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="feature-highlight">
                                    <i class="fas fa-star text-warning fa-2x mb-2"></i>
                                    <p class="mb-0 text-muted small">Perfil completo = Mayor puntaje</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs profile-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                                <i class="fas fa-user me-2"></i>Información de Cuenta
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                                <i class="fas fa-id-card me-2"></i>Datos del Aprendiz
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                <i class="fas fa-key me-2"></i>Seguridad
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="formation-tab" data-bs-toggle="tab" data-bs-target="#formation" type="button" role="tab">
                                <i class="fas fa-graduation-cap me-2"></i>Datos de Formación
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="representative-tab" data-bs-toggle="tab" data-bs-target="#representative" type="button" role="tab">
                                <i class="fas fa-users me-2"></i>Representante Legal
                            </button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="housing-tab" data-bs-toggle="tab" data-bs-target="#housing" type="button" role="tab">
                                <i class="fas fa-home me-2"></i>Vivienda
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab">
                                <i class="fas fa-heartbeat me-2"></i>Servicio Médico
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="socioeconomic-tab" data-bs-toggle="tab" data-bs-target="#socioeconomic" type="button" role="tab">
                                <i class="fas fa-chart-bar me-2"></i>Información Socioeconómica
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="conditions-tab" data-bs-toggle="tab" data-bs-target="#conditions" type="button" role="tab">
                                <i class="fas fa-clipboard-check me-2"></i>Condiciones del Aprendiz
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="declaration-tab" data-bs-toggle="tab" data-bs-target="#declaration" type="button" role="tab">
                                <i class="fas fa-file-signature me-2"></i>Declaración Juramentada
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Información de Cuenta -->
                        <div class="tab-pane fade show active" id="account" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-user me-2"></i>Información de Cuenta
                            </h4>
                            
                            <div class="info-card">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Nickname:</span>
                                            <span class="info-value">{{ auth()->user()->nickname ?? 'N/A' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Email:</span>
                                            <span class="info-value">{{ auth()->user()->email ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="info-label">Fecha de registro:</span>
                                            <span class="info-value">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">Última actualización:</span>
                                            <span class="info-value">{{ auth()->user()->updated_at ? auth()->user()->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Datos del Aprendiz -->
                        <div class="tab-pane fade" id="personal" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-id-card me-2"></i>Datos del Aprendiz
                            </h4>

                            @if(session('success_personal'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success_personal') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->has('personal_info'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach($errors->get('personal_info.*') as $error)
                                            <li>{{ $error[0] }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('cefa.sga.apprentice.profile.update-personal-info') }}" method="POST" id="personalInfoForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Información de Documento -->
                                <h5 class="section-title">
                                    <i class="fas fa-id-badge me-2"></i>Información de Documento
                                </h5>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="document_type" class="form-label">Tipo de Documento</label>
                                        <select class="form-select @error('personal_info.document_type') is-invalid @enderror" 
                                                id="document_type" 
                                                name="personal_info[document_type]" 
                                                required>
                                            <option value="">Seleccione...</option>
                                            <option value="Cédula de ciudadanía" {{ old('personal_info.document_type', auth()->user()->person->document_type ?? '') == 'Cédula de ciudadanía' ? 'selected' : '' }}>Cédula de ciudadanía</option>
                                            <option value="Tarjeta de identidad" {{ old('personal_info.document_type', auth()->user()->person->document_type ?? '') == 'Tarjeta de identidad' ? 'selected' : '' }}>Tarjeta de identidad</option>
                                            <option value="Cédula de extranjería" {{ old('personal_info.document_type', auth()->user()->person->document_type ?? '') == 'Cédula de extranjería' ? 'selected' : '' }}>Cédula de extranjería</option>
                                            <option value="Pasaporte" {{ old('personal_info.document_type', auth()->user()->person->document_type ?? '') == 'Pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                                            <option value="Documento nacional de identidad" {{ old('personal_info.document_type', auth()->user()->person->document_type ?? '') == 'Documento nacional de identidad' ? 'selected' : '' }}>Documento nacional de identidad</option>
                                            <option value="Registro civil" {{ old('personal_info.document_type', auth()->user()->person->document_type ?? '') == 'Registro civil' ? 'selected' : '' }}>Registro civil</option>
                                        </select>
                                        @error('personal_info.document_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="document_number" class="form-label">Número de Documento</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.document_number') is-invalid @enderror" 
                                               id="document_number" 
                                               name="personal_info[document_number]" 
                                               value="{{ old('personal_info.document_number', auth()->user()->person->document_number ?? '') }}"
                                               required>
                                        @error('personal_info.document_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_issue" class="form-label">Fecha de Expedición</label>
                                        <input type="date" 
                                               class="form-control @error('personal_info.date_of_issue') is-invalid @enderror" 
                                               id="date_of_issue" 
                                               name="personal_info[date_of_issue]" 
                                               value="{{ old('personal_info.date_of_issue', auth()->user()->person->date_of_issue ?? '') }}">
                                        @error('personal_info.date_of_issue')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="military_card" class="form-label">Tarjeta Militar</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.military_card') is-invalid @enderror" 
                                               id="military_card" 
                                               name="personal_info[military_card]" 
                                               value="{{ old('personal_info.military_card', auth()->user()->person->military_card ?? '') }}">
                                        @error('personal_info.military_card')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="section-divider">

                                <!-- Información Personal -->
                                <h5 class="section-title">
                                    <i class="fas fa-user me-2"></i>Datos Personales
                                </h5>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">Primer Nombre</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.first_name') is-invalid @enderror" 
                                               id="first_name" 
                                               name="personal_info[first_name]" 
                                               value="{{ old('personal_info.first_name', auth()->user()->person->first_name ?? '') }}"
                                               required>
                                        @error('personal_info.first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="first_last_name" class="form-label">Primer Apellido</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.first_last_name') is-invalid @enderror" 
                                               id="first_last_name" 
                                               name="personal_info[first_last_name]" 
                                               value="{{ old('personal_info.first_last_name', auth()->user()->person->first_last_name ?? '') }}"
                                               required>
                                        @error('personal_info.first_last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="second_last_name" class="form-label">Segundo Apellido</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.second_last_name') is-invalid @enderror" 
                                               id="second_last_name" 
                                               name="personal_info[second_last_name]" 
                                               value="{{ old('personal_info.second_last_name', auth()->user()->person->second_last_name ?? '') }}">
                                        @error('personal_info.second_last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" 
                                               class="form-control @error('personal_info.date_of_birth') is-invalid @enderror" 
                                               id="date_of_birth" 
                                               name="personal_info[date_of_birth]" 
                                               value="{{ old('personal_info.date_of_birth', auth()->user()->person->date_of_birth ?? '') }}">
                                        @error('personal_info.date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="gender" class="form-label">Género</label>
                                        <select class="form-select @error('personal_info.gender') is-invalid @enderror" 
                                                id="gender" 
                                                name="personal_info[gender]">
                                            <option value="No registra" {{ old('personal_info.gender', auth()->user()->person->gender ?? 'No registra') == 'No registra' ? 'selected' : '' }}>No registra</option>
                                            <option value="Masculino" {{ old('personal_info.gender', auth()->user()->person->gender ?? '') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="Femenino" {{ old('personal_info.gender', auth()->user()->person->gender ?? '') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                            <option value="No Binario" {{ old('personal_info.gender', auth()->user()->person->gender ?? '') == 'No Binario' ? 'selected' : '' }}>No Binario</option>
                                        </select>
                                        @error('personal_info.gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="age" class="form-label">Edad</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="age" 
                                               value="{{ auth()->user()->person->age ?? '--' }}" 
                                               readonly 
                                               disabled>
                                        <small class="form-text text-muted">Se calcula automáticamente según la fecha de nacimiento</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="marital_status" class="form-label">Estado Civil</label>
                                        <select class="form-select @error('personal_info.marital_status') is-invalid @enderror" 
                                                id="marital_status" 
                                                name="personal_info[marital_status]">
                                            <option value="No registra" {{ old('personal_info.marital_status', auth()->user()->person->marital_status ?? 'No registra') == 'No registra' ? 'selected' : '' }}>No registra</option>
                                            <option value="Soltero(a)" {{ old('personal_info.marital_status', auth()->user()->person->marital_status ?? '') == 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                                            <option value="Casado(a)" {{ old('personal_info.marital_status', auth()->user()->person->marital_status ?? '') == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                                            <option value="Separado(a)" {{ old('personal_info.marital_status', auth()->user()->person->marital_status ?? '') == 'Separado(a)' ? 'selected' : '' }}>Separado(a)</option>
                                            <option value="Unión libre" {{ old('personal_info.marital_status', auth()->user()->person->marital_status ?? '') == 'Unión libre' ? 'selected' : '' }}>Unión libre</option>
                                        </select>
                                        @error('personal_info.marital_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="blood_type" class="form-label">Tipo de Sangre</label>
                                        <select class="form-select @error('personal_info.blood_type') is-invalid @enderror" 
                                                id="blood_type" 
                                                name="personal_info[blood_type]">
                                            <option value="No registra" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? 'No registra') == 'No registra' ? 'selected' : '' }}>No registra</option>
                                            <option value="O+" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                            <option value="O-" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                                            <option value="A+" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                            <option value="A-" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                            <option value="B+" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                            <option value="B-" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                            <option value="AB+" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                            <option value="AB-" {{ old('personal_info.blood_type', auth()->user()->person->blood_type ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                        </select>
                                        @error('personal_info.blood_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="section-divider">

                                <!-- Nivel SISBEN -->
                                <h5 class="section-title">
                                    <i class="fas fa-chart-line me-2"></i>Nivel SISBEN
                                </h5>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="sisben_level" class="form-label">Grupo y Nivel de SISBEN</label>
                                        <select class="form-select @error('personal_info.sisben_level') is-invalid @enderror" 
                                                id="sisben_level" 
                                                name="personal_info[sisben_level]">
                                            <option value="">Seleccione...</option>
                                            <option value="A" {{ old('personal_info.sisben_level', auth()->user()->person->sisben_level ?? '') == 'A' ? 'selected' : '' }}>A</option>
                                            <option value="B" {{ old('personal_info.sisben_level', auth()->user()->person->sisben_level ?? '') == 'B' ? 'selected' : '' }}>B</option>
                                            <option value="C" {{ old('personal_info.sisben_level', auth()->user()->person->sisben_level ?? '') == 'C' ? 'selected' : '' }}>C</option>
                                            <option value="D" {{ old('personal_info.sisben_level', auth()->user()->person->sisben_level ?? '') == 'D' ? 'selected' : '' }}>D</option>
                                        </select>
                                        @error('personal_info.sisben_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="section-divider">

                                <!-- Información de Contacto y Residencia -->
                                <h5 class="section-title">
                                    <i class="fas fa-address-book me-2"></i>Información de Contacto y Residencia
                                </h5>
                                <div class="row mb-4">
                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">Dirección</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.address') is-invalid @enderror" 
                                               id="address" 
                                               name="personal_info[address]" 
                                               value="{{ old('personal_info.address', auth()->user()->person->address ?? '') }}"
                                               placeholder="Ingrese su dirección completa"
                                               required>
                                        @error('personal_info.address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="telephone1" class="form-label">Teléfono 1</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.telephone1') is-invalid @enderror" 
                                               id="telephone1" 
                                               name="personal_info[telephone1]" 
                                               value="{{ old('personal_info.telephone1', auth()->user()->person->telephone1 ?? '') }}"
                                               placeholder="Número de teléfono">
                                        @error('personal_info.telephone1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="telephone2" class="form-label">Teléfono 2</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.telephone2') is-invalid @enderror" 
                                               id="telephone2" 
                                               name="personal_info[telephone2]" 
                                               value="{{ old('personal_info.telephone2', auth()->user()->person->telephone2 ?? '') }}"
                                               placeholder="Número de teléfono (opcional)">
                                        @error('personal_info.telephone2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="telephone3" class="form-label">Teléfono 3</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.telephone3') is-invalid @enderror" 
                                               id="telephone3" 
                                               name="personal_info[telephone3]" 
                                               value="{{ old('personal_info.telephone3', auth()->user()->person->telephone3 ?? '') }}"
                                               placeholder="Número de teléfono (opcional)">
                                        @error('personal_info.telephone3')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="personal_email" class="form-label">Email Personal</label>
                                        <input type="email" 
                                               class="form-control @error('personal_info.personal_email') is-invalid @enderror" 
                                               id="personal_email" 
                                               name="personal_info[personal_email]" 
                                               value="{{ old('personal_info.personal_email', auth()->user()->person->personal_email ?? '') }}"
                                               placeholder="Correo electrónico personal">
                                        @error('personal_info.personal_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="contact_person_name" class="form-label">Nombre de Persona de Contacto</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.contact_person_name') is-invalid @enderror" 
                                               id="contact_person_name" 
                                               name="personal_info[contact_person_name]" 
                                               value="{{ old('personal_info.contact_person_name', auth()->user()->person->contact_person_name ?? '') }}"
                                               placeholder="Nombre completo de la persona de contacto">
                                        @error('personal_info.contact_person_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="contact_person_phone" class="form-label">Teléfono de Contacto</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.contact_person_phone') is-invalid @enderror" 
                                               id="contact_person_phone" 
                                               name="personal_info[contact_person_phone]" 
                                               value="{{ old('personal_info.contact_person_phone', auth()->user()->person->contact_person_phone ?? '') }}"
                                               placeholder="Teléfono de la persona de contacto">
                                        @error('personal_info.contact_person_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="misena_email" class="form-label">Email Misena</label>
                                        <input type="email" 
                                               class="form-control @error('personal_info.misena_email') is-invalid @enderror" 
                                               id="misena_email" 
                                               name="personal_info[misena_email]" 
                                               value="{{ old('personal_info.misena_email', auth()->user()->person->misena_email ?? '') }}"
                                               placeholder="Correo electrónico Misena">
                                        @error('personal_info.misena_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sena_email" class="form-label">Email SENA</label>
                                        <input type="email" 
                                               class="form-control @error('personal_info.sena_email') is-invalid @enderror" 
                                               id="sena_email" 
                                               name="personal_info[sena_email]" 
                                               value="{{ old('personal_info.sena_email', auth()->user()->person->sena_email ?? '') }}"
                                               placeholder="Correo electrónico SENA">
                                        @error('personal_info.sena_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>



                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Guardar Datos del Aprendiz
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Seguridad -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-key me-2"></i>Cambiar Contraseña
                            </h4>
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any() && !$errors->has('personal_info'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="info-card mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-shield-alt text-success me-3" style="font-size: 1.5rem;"></i>
                                            <div>
                                                <h6 class="mb-1">Seguridad de tu cuenta</h6>
                                                <small class="text-muted">Mantén tu cuenta segura cambiando tu contraseña regularmente</small>
                                            </div>
                                        </div>
                                        <p class="mb-0 small text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Tu contraseña debe tener al menos 8 caracteres y incluir una combinación de letras, números y símbolos.
                                        </p>
                                    </div>

                                    <form action="{{ route('cefa.sga.apprentice.profile.change-password') }}" method="POST" id="changePasswordForm">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">
                                                <i class="fas fa-lock me-1"></i>Contraseña Actual
                                            </label>
                                            <input type="password" 
                                                   class="form-control @error('current_password') is-invalid @enderror" 
                                                   id="current_password" 
                                                   name="current_password" 
                                                   placeholder="Ingresa tu contraseña actual"
                                                   required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">
                                                <i class="fas fa-key me-1"></i>Nueva Contraseña
                                            </label>
                                            <input type="password" 
                                                   class="form-control @error('new_password') is-invalid @enderror" 
                                                   id="new_password" 
                                                   name="new_password" 
                                                   placeholder="Ingresa tu nueva contraseña"
                                                   required
                                                   minlength="8">
                                            <div class="form-text">La contraseña debe tener al menos 8 caracteres.</div>
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="new_password_confirmation" class="form-label">
                                                <i class="fas fa-check-double me-1"></i>Confirmar Nueva Contraseña
                                            </label>
                                            <input type="password" 
                                                   class="form-control @error('new_password_confirmation') is-invalid @enderror" 
                                                   id="new_password_confirmation" 
                                                   name="new_password_confirmation" 
                                                   placeholder="Confirma tu nueva contraseña"
                                                   required
                                                   minlength="8">
                                            @error('new_password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="password-match-message" class="form-text text-danger d-none">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Las contraseñas no coinciden
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="reset" class="btn btn-secondary me-md-2">
                                                <i class="fas fa-undo me-1"></i> Limpiar
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-key me-1"></i> Cambiar Contraseña
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Datos de Formación -->
                        <div class="tab-pane fade" id="formation" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-graduation-cap me-2"></i>Datos de Formación
                            </h4>

                            @if(session('success_formation'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success_formation') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                            @endif

                            <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="formation_program_id" class="form-label">Programa de Formación</label>
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               id="formation_program_id" 
                                               value="{{ auth()->user()->person->apprentices->first()->course->program->name ?? 'No asignado' }}"
                                               readonly>
                                        <small class="text-muted">Este campo no se puede modificar</small>
                </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="formation_file_number" class="form-label">Número de Ficha</label>
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               id="formation_file_number" 
                                               value="{{ auth()->user()->person->apprentices->first()->course->code ?? 'No asignado' }}"
                                               readonly>
                                        <small class="text-muted">Este campo no se puede modificar</small>
            </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="formation_modality" class="form-label">Modalidad de Formación</label>
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               id="formation_modality" 
                                               value="{{ auth()->user()->person->apprentices->first()->course->deschooling ?? 'No asignado' }}"
                                               readonly>
                                        <small class="text-muted">Este campo no se puede modificar</small>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Información:</strong> Los datos de formación son administrados por el sistema y no se pueden modificar desde este perfil.
                                </div>
                        </div>

                        <!-- Representante Legal o Tutor -->
                        <div class="tab-pane fade" id="representative" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-users me-2"></i>Representante Legal o Tutor
                                <span class="badge bg-warning ms-2" id="minorAgeBadge" style="display: none;">Menor de Edad</span>
                            </h4>
                            
                            <!-- Mensaje informativo sobre cuándo completar esta sección -->
                            <div class="alert alert-info" id="representativeInfoMessage">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Información importante:</strong> Esta sección solo se debe completar si eres menor de edad (menor de 18 años).
                            </div>
                            
                            <!-- Contenedor del formulario que se puede mostrar/ocultar -->
                            <div id="representativeFormContainer" style="display: none;">
                                <p class="text-muted mb-4">Complete la información del representante legal o tutor:</p>

                            @if(session('success_representative'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success_representative') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('cefa.sga.apprentice.profile.update-representative-info') }}" method="POST" id="representativeInfoForm">
                                @csrf
                                @method('PUT')
                                
                                <!-- Campo oculto para indicar si es menor de edad -->
                                <input type="hidden" id="is_minor_age" name="representative_info[is_minor_age]" value="0">
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="representative_name" class="form-label">Nombres y Apellidos *</label>
                                        <input type="text" 
                                               class="form-control @error('representative_info.name') is-invalid @enderror" 
                                               id="representative_name" 
                                               name="representative_info[name]" 
                                               value="{{ old('representative_info.name', auth()->user()->person->representativeLegal->name ?? '') }}"
                                               placeholder="Nombres y apellidos completos"
                                               required>
                                        @error('representative_info.name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="representative_document_type" class="form-label">Tipo de Documento *</label>
                                        <select class="form-select @error('representative_info.document_type') is-invalid @enderror" 
                                                id="representative_document_type" 
                                                name="representative_info[document_type]"
                                                required>
                                            <option value="">Seleccione...</option>
                                            @foreach(\Modules\SGA\Entities\RepresentativeLegal::getDocumentTypes() as $value => $label)
                                                <option value="{{ $value }}" {{ old('representative_info.document_type', auth()->user()->person->representativeLegal->document_type ?? '') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('representative_info.document_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="representative_document_number" class="form-label">Número de Documento *</label>
                                        <input type="text" 
                                               class="form-control @error('representative_info.document_number') is-invalid @enderror" 
                                               id="representative_document_number" 
                                               name="representative_info[document_number]" 
                                               value="{{ old('representative_info.document_number', auth()->user()->person->representativeLegal->document_number ?? '') }}"
                                               placeholder="Número de documento"
                                               required>
                                        @error('representative_info.document_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="representative_relationship" class="form-label">Relación *</label>
                                        <select class="form-select @error('representative_info.relationship') is-invalid @enderror" 
                                                id="representative_relationship" 
                                                name="representative_info[relationship]"
                                                required>
                                            <option value="">Seleccione...</option>
                                            @foreach(\Modules\SGA\Entities\RepresentativeLegal::getRelationships() as $value => $label)
                                                <option value="{{ $value }}" {{ old('representative_info.relationship', auth()->user()->person->representativeLegal->relationship ?? '') == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('representative_info.relationship')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="representative_telephone1" class="form-label">Teléfono Principal *</label>
                                        <input type="text" 
                                               class="form-control @error('representative_info.telephone1') is-invalid @enderror" 
                                               id="representative_telephone1" 
                                               name="representative_info[telephone1]" 
                                               value="{{ old('representative_info.telephone1', auth()->user()->person->representativeLegal->telephone1 ?? '') }}"
                                               placeholder="Número de teléfono principal"
                                               required>
                                        @error('representative_info.telephone1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="representative_telephone2" class="form-label">Teléfono Secundario</label>
                                        <input type="text" 
                                               class="form-control @error('representative_info.telephone2') is-invalid @enderror" 
                                               id="representative_telephone2" 
                                               name="representative_info[telephone2]" 
                                               value="{{ old('representative_info.telephone2', auth()->user()->person->representativeLegal->telephone2 ?? '') }}"
                                               placeholder="Número de teléfono secundario (opcional)">
                                        @error('representative_info.telephone2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="representative_email" class="form-label">Correo Electrónico *</label>
                                        <input type="email" 
                                               class="form-control @error('representative_info.email') is-invalid @enderror" 
                                               id="representative_email" 
                                               name="representative_info[email]" 
                                               value="{{ old('representative_info.email', auth()->user()->person->representativeLegal->email ?? '') }}"
                                               placeholder="Correo electrónico"
                                               required>
                                        @error('representative_info.email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="representative_address" class="form-label">Dirección</label>
                                        <input type="text" 
                                               class="form-control @error('representative_info.address') is-invalid @enderror" 
                                               id="representative_address" 
                                               name="representative_info[address]" 
                                               value="{{ old('representative_info.address', auth()->user()->person->representativeLegal->address ?? '') }}"
                                               placeholder="Dirección completa (opcional)">
                                        @error('representative_info.address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Guardar Datos del Representante
                                    </button>
                                </div>
                            </form>
                            </div> <!-- Cierre del representativeFormContainer -->
                        </div>



                        <!-- Vivienda -->
                        <div class="tab-pane fade" id="housing" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-home me-2"></i>Información de Vivienda
                            </h4>

                            @if(session('success_housing'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success_housing') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('cefa.sga.apprentice.profile.update-housing-info') }}" method="POST" id="housingInfoForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="housing_stratum" class="form-label">Estrato Socioeconómico</label>
                                        <select class="form-select @error('housing_info.stratum') is-invalid @enderror" 
                                                id="housing_stratum" 
                                                name="housing_info[stratum]" 
                                                required>
                                            <option value="">Seleccione...</option>
                                            <option value="1" {{ old('housing_info.stratum', $person->socioeconomical_status) == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('housing_info.stratum', $person->socioeconomical_status) == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('housing_info.stratum', $person->socioeconomical_status) == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('housing_info.stratum', $person->socioeconomical_status) == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('housing_info.stratum', $person->socioeconomical_status) == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('housing_info.stratum', $person->socioeconomical_status) == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                        @error('housing_info.stratum')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Guardar Información de Vivienda
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Servicio Médico del Aprendiz -->
                        <div class="tab-pane fade" id="medical" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-heartbeat me-2"></i>Servicio Médico del Aprendiz
                            </h4>

                            @if(session('success_medical'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success_medical') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('cefa.sga.apprentice.profile.update-medical-info') }}" method="POST" id="medicalInfoForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="medical_eps" class="form-label">EPS</label>
                                        <select class="form-select @error('medical_info.eps_id') is-invalid @enderror" 
                                                id="medical_eps" 
                                                name="medical_info[eps_id]" 
                                                required>
                                            <option value="">Seleccione una EPS...</option>
                                            @if(isset($eps))
                                                @foreach($eps as $ep)
                                                    <option value="{{ $ep->id }}" {{ old('medical_info.eps_id', $person->eps_id) == $ep->id ? 'selected' : '' }}>
                                                        {{ $ep->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('medical_info.eps_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Guardar Información Médica
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Información Socioeconómica -->
                        <div class="tab-pane fade" id="socioeconomic" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-chart-bar me-2"></i>Información Socioeconómica
                            </h4>
                            <p class="text-muted mb-4">Marque con "Sí" o "No" según corresponda a su situación actual</p>

                            @if(session('success_socioeconomic'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success_socioeconomic') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @php
                                $socioeconomic = auth()->user()->person->socioeconomic;
                            @endphp

                            <form action="{{ route('cefa.sga.apprentice.profile.update-socioeconomic-info') }}" method="POST" id="socioeconomicInfoForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Es beneficiario del Programa Renta Joven?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                                                        <input class="form-check-input" type="radio" name="socioeconomic_info[renta_joven_beneficiary]" id="renta_joven_si" value="Sí" {{ old('socioeconomic_info.renta_joven_beneficiary', $socioeconomic?->renta_joven_beneficiary === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="renta_joven_si">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="socioeconomic_info[renta_joven_beneficiary]" id="renta_joven_no" value="No" {{ old('socioeconomic_info.renta_joven_beneficiary', $socioeconomic?->renta_joven_beneficiary === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="renta_joven_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Tiene contrato de aprendizaje?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                                                        <input class="form-check-input" type="radio" name="socioeconomic_info[has_apprenticeship_contract]" id="learning_contract_si" value="Sí" {{ old('socioeconomic_info.has_apprenticeship_contract', $socioeconomic?->has_apprenticeship_contract === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="learning_contract_si">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="socioeconomic_info[has_apprenticeship_contract]" id="learning_contract_no" value="No" {{ old('socioeconomic_info.has_apprenticeship_contract', $socioeconomic?->has_apprenticeship_contract === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="learning_contract_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Ha recibido apoyo de sostenimiento FIC en otro programa?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                                                        <input class="form-check-input" type="radio" name="socioeconomic_info[received_fic_support]" id="fic_support_si" value="Sí" {{ old('socioeconomic_info.received_fic_support', $socioeconomic?->received_fic_support === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="fic_support_si">Sí</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="socioeconomic_info[received_fic_support]" id="fic_support_no" value="No" {{ old('socioeconomic_info.received_fic_support', $socioeconomic?->received_fic_support === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="fic_support_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Ha recibido apoyo de sostenimiento REGULAR en otro programa?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[received_regular_support]" id="regular_support_si" value="Sí" {{ old('socioeconomic_info.received_regular_support', $socioeconomic?->received_regular_support === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="regular_support_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[received_regular_support]" id="regular_support_no" value="No" {{ old('socioeconomic_info.received_regular_support', $socioeconomic?->received_regular_support === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="regular_support_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Tiene vínculo laboral o contrato de prestación de servicios con ingresos económicos?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[has_income_contract]" id="income_contract_si" value="Sí" {{ old('socioeconomic_info.has_income_contract', $socioeconomic?->has_income_contract === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="income_contract_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[has_income_contract]" id="income_contract_no" value="No" {{ old('socioeconomic_info.has_income_contract', $socioeconomic?->has_income_contract === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="income_contract_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Tiene patrocinio o prácticas laborales con ingresos económicos?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[has_sponsored_practice]" id="sponsored_practice_si" value="Sí" {{ old('socioeconomic_info.has_sponsored_practice', $socioeconomic?->has_sponsored_practice === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sponsored_practice_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[has_sponsored_practice]" id="sponsored_practice_no" value="No" {{ old('socioeconomic_info.has_sponsored_practice', $socioeconomic?->has_sponsored_practice === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sponsored_practice_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Recibe apoyo de alimentación del SENA?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[receives_food_support]" id="food_support_si" value="Sí" {{ old('socioeconomic_info.receives_food_support', $socioeconomic?->receives_food_support === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="food_support_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[receives_food_support]" id="food_support_no" value="No" {{ old('socioeconomic_info.receives_food_support', $socioeconomic?->receives_food_support === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="food_support_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Recibe apoyo de transporte del SENA?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[receives_transport_support]" id="transport_support_si" value="Sí" {{ old('socioeconomic_info.receives_transport_support', $socioeconomic?->receives_transport_support === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="transport_support_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[receives_transport_support]" id="transport_support_no" value="No" {{ old('socioeconomic_info.receives_transport_support', $socioeconomic?->receives_transport_support === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="transport_support_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Recibe apoyo de medios tecnológicos del SENA?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[receives_tech_support]" id="tech_support_si" value="Sí" {{ old('socioeconomic_info.receives_tech_support', $socioeconomic?->receives_tech_support === 'SI' ? 'Sí' : '') == 'Sí' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tech_support_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="socioeconomic_info[receives_tech_support]" id="tech_support_no" value="No" {{ old('socioeconomic_info.receives_tech_support', $socioeconomic?->receives_tech_support === 'NO' ? 'No' : '') == 'No' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="tech_support_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Guardar Información Socioeconómica
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Condiciones del Aprendiz -->
                        <div class="tab-pane fade" id="conditions" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-clipboard-check me-2"></i>Condiciones del Aprendiz
                            </h4>
                            <p class="text-muted mb-4">Marque con "Sí" o "No" según corresponda a su situación actual</p>

                            @if(session('success_conditions'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success_conditions') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @php
                                $conditions = auth()->user()->person->conditions;
                            @endphp

                            <form action="{{ route('cefa.sga.apprentice.profile.update-conditions-info') }}" method="POST" id="conditionsInfoForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Es víctima del conflicto armado (Ley 1448 de 2011 - Decreto 4800 de 2011)?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                                                <input class="form-check-input" type="radio" name="apprentice_conditions[victim_conflict]" id="victim_conflict_si" value="SI" {{ old('apprentice_conditions.victim_conflict', $conditions?->victim_conflict) == 'SI' ? 'checked' : '' }}>
                                <label class="form-check-label" for="victim_conflict_si">Sí</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="apprentice_conditions[victim_conflict]" id="victim_conflict_no" value="NO" {{ old('apprentice_conditions.victim_conflict', $conditions?->victim_conflict) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="victim_conflict_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Es víctima de violencia de género o violencia contra la mujer?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[gender_violence_victim]" id="gender_violence_victim_si" value="SI" {{ old('apprentice_conditions.gender_violence_victim', $conditions?->gender_violence_victim) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_violence_victim_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[gender_violence_victim]" id="gender_violence_victim_no" value="NO" {{ old('apprentice_conditions.gender_violence_victim', $conditions?->gender_violence_victim) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_violence_victim_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="disability" class="form-label">¿Está en situación de discapacidad?</label>
                                        <input type="text" 
                                               class="form-control @error('apprentice_conditions.disability') is-invalid @enderror" 
                                               id="disability" 
                                               name="apprentice_conditions[disability]" 
                                               value="{{ old('apprentice_conditions.disability', $conditions?->disability) }}"
                                               placeholder="Describa su situación de discapacidad (opcional)">
                                        @error('apprentice_conditions.disability')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Es madre o padre cabeza de familia?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[head_of_household]" id="head_of_household_si" value="SI" {{ old('apprentice_conditions.head_of_household', $conditions?->head_of_household) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="head_of_household_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[head_of_household]" id="head_of_household_no" value="NO" {{ old('apprentice_conditions.head_of_household', $conditions?->head_of_household) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="head_of_household_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Está embarazada o en periodo de lactancia?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[pregnant_or_lactating]" id="pregnant_or_lactating_si" value="SI" {{ old('apprentice_conditions.pregnant_or_lactating', $conditions?->pregnant_or_lactating) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="pregnant_or_lactating_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[pregnant_or_lactating]" id="pregnant_or_lactating_no" value="NO" {{ old('apprentice_conditions.pregnant_or_lactating', $conditions?->pregnant_or_lactating) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="pregnant_or_lactating_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Pertenece a comunidades NARP (Negritudes, Afrocolombianos, Raizales, Palanqueros), Pueblo ROM o Población Indígena?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[ethnic_group_affiliation]" id="ethnic_group_affiliation_si" value="SI" {{ old('apprentice_conditions.ethnic_group_affiliation', $conditions?->ethnic_group_affiliation) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ethnic_group_affiliation_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[ethnic_group_affiliation]" id="ethnic_group_affiliation_no" value="NO" {{ old('apprentice_conditions.ethnic_group_affiliation', $conditions?->ethnic_group_affiliation) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ethnic_group_affiliation_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Ha sido desplazado por fenómenos naturales en los últimos 2 años?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[natural_displacement]" id="natural_displacement_si" value="SI" {{ old('apprentice_conditions.natural_displacement', $conditions?->natural_displacement) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="natural_displacement_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[natural_displacement]" id="natural_displacement_no" value="NO" {{ old('apprentice_conditions.natural_displacement', $conditions?->natural_displacement) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="natural_displacement_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Tiene nivel SISBEN grupo A 1,2,3,4 y 5?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[sisben_group_a]" id="sisben_group_a_si" value="SI" {{ old('apprentice_conditions.sisben_group_a', $conditions?->sisben_group_a) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sisben_group_a_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[sisben_group_a]" id="sisben_group_a_no" value="NO" {{ old('apprentice_conditions.sisben_group_a', $conditions?->sisben_group_a) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sisben_group_a_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Tiene nivel SISBEN grupo B 1,2,3,4,5,6 y 7?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[sisben_group_b]" id="sisben_group_b_si" value="SI" {{ old('apprentice_conditions.sisben_group_b', $conditions?->sisben_group_b) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sisben_group_b_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[sisben_group_b]" id="sisben_group_b_no" value="NO" {{ old('apprentice_conditions.sisben_group_b', $conditions?->sisben_group_b) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sisben_group_b_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Se reconoce como aprendiz campesino?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[rural_apprentice]" id="rural_apprentice_si" value="SI" {{ old('apprentice_conditions.rural_apprentice', $conditions?->rural_apprentice) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="rural_apprentice_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[rural_apprentice]" id="rural_apprentice_no" value="NO" {{ old('apprentice_conditions.rural_apprentice', $conditions?->rural_apprentice) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="rural_apprentice_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Es representante elegido según normatividad institucional?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[institutional_representative]" id="institutional_representative_si" value="SI" {{ old('apprentice_conditions.institutional_representative', $conditions?->institutional_representative) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="institutional_representative_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[institutional_representative]" id="institutional_representative_no" value="NO" {{ old('apprentice_conditions.institutional_representative', $conditions?->institutional_representative) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="institutional_representative_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Vive en área rural?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[lives_in_rural_area]" id="lives_in_rural_area_si" value="SI" {{ old('apprentice_conditions.lives_in_rural_area', $conditions?->lives_in_rural_area) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lives_in_rural_area_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[lives_in_rural_area]" id="lives_in_rural_area_no" value="NO" {{ old('apprentice_conditions.lives_in_rural_area', $conditions?->lives_in_rural_area) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="lives_in_rural_area_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Es vocero principal o suplente elegido?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[spokesperson_elected]" id="spokesperson_elected_si" value="SI" {{ old('apprentice_conditions.spokesperson_elected', $conditions?->spokesperson_elected) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="spokesperson_elected_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[spokesperson_elected]" id="spokesperson_elected_no" value="NO" {{ old('apprentice_conditions.spokesperson_elected', $conditions?->spokesperson_elected) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="spokesperson_elected_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="research_participation" class="form-label">¿Participa en Semillero de Investigación, WorldSkills, SENAsoft o producción del Centro?</label>
                                        <input type="text" 
                                               class="form-control @error('apprentice_conditions.research_participation') is-invalid @enderror" 
                                               id="research_participation" 
                                               name="apprentice_conditions[research_participation]" 
                                               value="{{ old('apprentice_conditions.research_participation', $conditions?->research_participation) }}"
                                               placeholder="Describa su participación (opcional)">
                                        @error('apprentice_conditions.research_participation')
                                            <div class="form-check-label">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Ha tenido cupo en el internado en la vigencia anterior?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[previous_boarding_quota]" id="previous_boarding_quota_si" value="SI" {{ old('apprentice_conditions.previous_boarding_quota', $conditions?->previous_boarding_quota) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="previous_boarding_quota_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[previous_boarding_quota]" id="previous_boarding_quota_no" value="NO" {{ old('apprentice_conditions.previous_boarding_quota', $conditions?->previous_boarding_quota) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="previous_boarding_quota_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Tiene certificado de tecnólogo o título profesional?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[has_certification]" id="has_certification_si" value="SI" {{ old('apprentice_conditions.has_certification', $conditions?->has_certification) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_certification_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[has_certification]" id="has_certification_no" value="NO" {{ old('apprentice_conditions.has_certification', $conditions?->has_certification) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="has_certification_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Adjunta información de declaración juramentada?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[attached_sworn_statement]" id="attached_sworn_statement_si" value="SI" {{ old('apprentice_conditions.attached_sworn_statement', $conditions?->attached_sworn_statement) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="attached_sworn_statement_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[attached_sworn_statement]" id="attached_sworn_statement_no" value="NO" {{ old('apprentice_conditions.attached_sworn_statement', $conditions?->attached_sworn_statement) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="attached_sworn_statement_no">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">¿Conoce las obligaciones si recibe apoyo socioeconómico?</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[knows_obligations_support]" id="knows_obligations_support_si" value="SI" {{ old('apprentice_conditions.knows_obligations_support', $conditions?->knows_obligations_support) == 'SI' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="knows_obligations_support_si">Sí</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="apprentice_conditions[knows_obligations_support]" id="knows_obligations_support_no" value="NO" {{ old('apprentice_conditions.knows_obligations_support', $conditions?->knows_obligations_support) == 'NO' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="knows_obligations_support_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-2"></i>Guardar Condiciones del Aprendiz
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Declaración Juramentada -->
                        <div class="tab-pane fade" id="declaration" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-file-signature me-2"></i>Declaración Juramentada
                            </h4>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Importante:</strong> Si no cuenta con soportes al momento de la convocatoria, el aprendiz puede presentar esta declaración firmada. Si resulta beneficiado, el SENA podrá solicitar evidencias en cualquier momento.
                            </div>

                            @if(session('success_declaration'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success_declaration') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('cefa.sga.apprentice.profile.update-declaration-info') }}" method="POST" id="declarationInfoForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="declaration_full_name" class="form-label">Nombre y Apellidos</label>
                                        <input type="text" 
                                               class="form-control @error('declaration_info.full_name') is-invalid @enderror" 
                                               id="declaration_full_name" 
                                               name="declaration_info[full_name]" 
                                               value="{{ old('declaration_info.full_name', auth()->user()->person->swornStatement->full_name ?? auth()->user()->person->getFullNameAttribute()) }}"
                                               placeholder="Nombres y apellidos completos"
                                               required>
                                        @error('declaration_info.full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="declaration_document_number" class="form-label">Número de Documento de Identidad</label>
                                        <input type="text" 
                                               class="form-control @error('declaration_info.document_number') is-invalid @enderror" 
                                               id="declaration_document_number" 
                                               name="declaration_info[document_number]" 
                                               value="{{ old('declaration_info.document_number', auth()->user()->person->swornStatement->document_number ?? auth()->user()->person->document_number) }}"
                                               placeholder="Número de documento"
                                               required>
                                        @error('declaration_info.document_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="declaration_condition" class="form-label">Condición que Declara</label>
                                        <textarea class="form-control @error('declaration_info.condition') is-invalid @enderror" 
                                                  id="declaration_condition" 
                                                  name="declaration_info[condition]" 
                                                  rows="3"
                                                  placeholder="Ej: Aprendiz embarazada, aprendiz campesino, aprendiz en situación de discapacidad, etc."
                                                  required>{{ old('declaration_info.condition', auth()->user()->person->swornStatement->condition ?? '') }}</textarea>
                                        @error('declaration_info.condition')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="declaration_apprentice_signature" class="form-label">Firma del Aprendiz</label>
                                        <input type="text" 
                                               class="form-control @error('declaration_info.apprentice_signature') is-invalid @enderror" 
                                               id="declaration_apprentice_signature" 
                                               name="declaration_info[apprentice_signature]" 
                                               value="{{ old('declaration_info.apprentice_signature', auth()->user()->person->swornStatement->apprentice_signature ?? '') }}"
                                               placeholder="Nombre del aprendiz (como firma)"
                                               required>
                                        @error('declaration_info.apprentice_signature')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="declaration_representative_signature" class="form-label">Firma del Representante Legal o Tutor (si aplica)</label>
                                        <input type="text" 
                                               class="form-control @error('declaration_info.representative_signature') is-invalid @enderror" 
                                               id="declaration_representative_signature" 
                                               name="declaration_info[representative_signature]" 
                                               value="{{ old('declaration_info.representative_signature', auth()->user()->person->swornStatement->representative_signature ?? '') }}"
                                               placeholder="Nombre del representante (como firma)">
                                        @error('declaration_info.representative_signature')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="declaration_date" class="form-label">Fecha</label>
                                        <input type="date" 
                                               class="form-control @error('declaration_info.date') is-invalid @enderror" 
                                               id="declaration_date" 
                                               name="declaration_info[date]" 
                                               value="{{ old('declaration_info.date', auth()->user()->person->swornStatement->date ?? date('Y-m-d')) }}"
                                               required>
                                        @error('declaration_info.date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="declaration_city" class="form-label">Ciudad</label>
                                        <input type="text" 
                                               class="form-control @error('declaration_info.city') is-invalid @enderror" 
                                               id="declaration_city" 
                                               name="declaration_info[city]" 
                                               value="{{ old('declaration_info.city', auth()->user()->person->swornStatement->city ?? '') }}"
                                               placeholder="Ciudad donde se declara"
                                               required>
                                        @error('declaration_info.city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Guardar Declaración Juramentada
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('changePasswordForm');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');
    const matchMessage = document.getElementById('password-match-message');

    // Validación en tiempo real de confirmación de contraseña
    function checkPasswordMatch() {
        if (confirmPassword.value && newPassword.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('Las contraseñas no coinciden');
            confirmPassword.classList.add('is-invalid');
            matchMessage.classList.remove('d-none');
        } else {
            confirmPassword.setCustomValidity('');
            confirmPassword.classList.remove('is-invalid');
            matchMessage.classList.add('d-none');
        }
    }

    newPassword.addEventListener('input', checkPasswordMatch);
    confirmPassword.addEventListener('input', checkPasswordMatch);

    // Validación antes de enviar el formulario
    form.addEventListener('submit', function(e) {
        if (newPassword.value !== confirmPassword.value) {
            e.preventDefault();
            checkPasswordMatch();
            return false;
        }
    });

    // Auto-switch to personal info tab if there are personal info errors
    @if($errors->has('personal_info'))
        const personalTab = new bootstrap.Tab(document.getElementById('personal-tab'));
        personalTab.show();
    @endif

    // Auto-switch to security tab if there are password errors
    @if($errors->any() && !$errors->has('personal_info'))
        const securityTab = new bootstrap.Tab(document.getElementById('security-tab'));
        securityTab.show();
    @endif

    // Auto-switch to other tabs if there are errors
    @if($errors->has('formation_info'))
        const formationTab = new bootstrap.Tab(document.getElementById('formation-tab'));
        formationTab.show();
    @endif

    @if($errors->has('representative_info'))
        const representativeTab = new bootstrap.Tab(document.getElementById('representative-tab'));
        representativeTab.show();
    @endif

    

                @if($errors->has('housing_info'))
                const housingTab = new bootstrap.Tab(document.getElementById('housing-tab'));
                housingTab.show();
            @endif
            @if($errors->has('medical_info'))
        const medicalTab = new bootstrap.Tab(document.getElementById('medical-tab'));
        medicalTab.show();
    @endif

    @if($errors->has('socioeconomic_info'))
        const socioeconomicTab = new bootstrap.Tab(document.getElementById('socioeconomic-tab'));
        socioeconomicTab.show();
    @endif

    @if($errors->has('apprentice_conditions'))
        const conditionsTab = new bootstrap.Tab(document.getElementById('conditions-tab'));
        conditionsTab.show();
    @endif

    @if($errors->has('declaration_info'))
        const declarationTab = new bootstrap.Tab(document.getElementById('declaration-tab'));
        declarationTab.show();
    @endif

    // Lógica para mostrar/ocultar campos adicionales en Condiciones del Aprendiz
    // Nota: Los campos disability y research_participation ahora son inputs de texto
    // por lo que no necesitan lógica de radio buttons
});

// Función para calcular el progreso del perfil
function calculateProfileProgress() {
    const sections = [
                    'personal', 'formation', 'representative', 'housing', 
        'medical', 'socioeconomic', 'conditions', 'declaration'
    ];
    
    let completedSections = 0;
    
    sections.forEach(section => {
        if (isSectionComplete(section)) {
            completedSections++;
        }
    });
    
    const percentage = Math.round((completedSections / sections.length) * 100);
    
    // Actualizar barra de progreso
    document.getElementById('progressBar').style.width = percentage + '%';
    document.getElementById('progressPercentage').textContent = percentage + '%';
    
    // Cambiar color según el progreso
    const progressBar = document.getElementById('progressBar');
    if (percentage < 25) {
        progressBar.className = 'progress-bar bg-danger';
    } else if (percentage < 50) {
        progressBar.className = 'progress-bar bg-warning';
    } else if (percentage < 75) {
        progressBar.className = 'progress-bar bg-info';
    } else {
        progressBar.className = 'progress-bar bg-success';
    }
    
    return percentage;
}

// Función para verificar si una sección está completa
function isSectionComplete(section) {
    const sectionElement = document.getElementById(section);
    if (!sectionElement) return false;
    
    // Si la sección de representante legal no es necesaria, no se considera para el progreso
    if (section === 'representative') {
        const representativeFormContainer = document.getElementById('representativeFormContainer');
        if (representativeFormContainer && representativeFormContainer.style.display === 'none') {
            return true; // Se considera completa si no es necesaria
        }
    }
    
    const requiredFields = sectionElement.querySelectorAll('[required]');
    let completedFields = 0;
    
    requiredFields.forEach(field => {
        if (field.value && field.value.trim() !== '') {
            completedFields++;
        }
    });
    
    // Una sección se considera completa si tiene al menos el 80% de los campos requeridos
    return requiredFields.length > 0 && (completedFields / requiredFields.length) >= 0.8;
}

// Función para guardar todas las secciones
function saveAllSections() {
    const saveAllBtn = document.getElementById('saveAllBtn');
    const originalText = saveAllBtn.innerHTML;
    
    // Cambiar estado del botón
    saveAllBtn.disabled = true;
    saveAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
    
    // Recolectar todos los datos de los formularios
    const allData = collectAllFormData();
    
    // Enviar datos al servidor
    fetch('{{ route("cefa.sga.apprentice.profile.save-all") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(allData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            showAlert('success', '¡Perfil guardado exitosamente!', 'Toda la información ha sido guardada correctamente.');
            
            // Recargar la página para mostrar los datos actualizados
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            // Mostrar errores de validación
            showAlert('danger', 'Error al guardar', data.message || 'Hubo un problema al guardar el perfil.');
            
            // Mostrar errores específicos por sección
            if (data.errors) {
                displayValidationErrors(data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Error de conexión', 'No se pudo conectar con el servidor. Intenta nuevamente.');
    })
    .finally(() => {
        // Restaurar estado del botón
        saveAllBtn.disabled = false;
        saveAllBtn.innerHTML = originalText;
    });
}

// Función para recolectar todos los datos de los formularios
function collectAllFormData() {
    const data = {};
    
    // Datos personales
    const personalForm = document.getElementById('personalInfoForm');
    if (personalForm) {
        const formData = new FormData(personalForm);
        data.personal_info = {};
        for (let [key, value] of formData.entries()) {
            data.personal_info[key] = value;
        }
    }
    
    
    
    // Datos del representante legal (solo si es menor de edad)
    const representativeForm = document.getElementById('representativeInfoForm');
    const representativeFormContainer = document.getElementById('representativeFormContainer');
    if (representativeForm && representativeFormContainer && representativeFormContainer.style.display !== 'none') {
        const formData = new FormData(representativeForm);
        data.representative_info = {};
        for (let [key, value] of formData.entries()) {
            data.representative_info[key] = value;
        }
    }
    
    
    
                // Datos de vivienda (solo estrato)
            const housingForm = document.getElementById('housingInfoForm');
            if (housingForm) {
                const formData = new FormData(housingForm);
                data.housing_info = {};
                for (let [key, value] of formData.entries()) {
                    data.housing_info[key] = value;
                }
            }

            // Datos médicos
    const medicalForm = document.getElementById('medicalInfoForm');
    if (medicalForm) {
        const formData = new FormData(medicalForm);
        data.medical_info = {};
        for (let [key, value] of formData.entries()) {
            data.medical_info[key] = value;
        }
    }
    
    // Datos socioeconómicos
    const socioeconomicForm = document.getElementById('socioeconomicInfoForm');
    if (socioeconomicForm) {
        const formData = new FormData(socioeconomicForm);
        data.socioeconomic_info = {};
        for (let [key, value] of formData.entries()) {
            data.socioeconomic_info[key] = value;
        }
    }
    
    // Condiciones del aprendiz
    const conditionsForm = document.getElementById('conditionsInfoForm');
    if (conditionsForm) {
        const formData = new FormData(conditionsForm);
        data.apprentice_conditions = {};
        for (let [key, value] of formData.entries()) {
            data.apprentice_conditions[key] = value;
        }
    }
    
    // Declaración juramentada
    const declarationForm = document.getElementById('declarationInfoForm');
    if (declarationForm) {
        const formData = new FormData(declarationForm);
        data.declaration_info = {};
        for (let [key, value] of formData.entries()) {
            data.declaration_info[key] = value;
        }
    }
    
    return data;
}

// Función para mostrar alertas
function showAlert(type, title, message) {
    const alertContainer = document.createElement('div');
    alertContainer.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertContainer.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertContainer.innerHTML = `
        <strong>${title}</strong><br>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alertContainer);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alertContainer.parentNode) {
            alertContainer.remove();
        }
    }, 5000);
}

// Función para mostrar errores de validación
function displayValidationErrors(errors) {
    // Limpiar errores anteriores
    document.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(feedback => {
        feedback.remove();
    });
    
    // Mostrar nuevos errores
    Object.keys(errors).forEach(field => {
        const [section, fieldName] = field.split('.');
        const input = document.querySelector(`[name="${section}[${fieldName}]"]`);
        
        if (input) {
            input.classList.add('is-invalid');
            
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errors[field][0];
            
            input.parentNode.appendChild(feedback);
        }
    });
}



// Función para filtrar municipios según departamento seleccionado
// NOTA: Esta funcionalidad ha sido removida ya que los campos de departamento y municipio no existen en la tabla people

// Función para calcular edad y mostrar/ocultar formulario de representante legal
function checkMinorAge() {
    const dateOfBirthInput = document.getElementById('date_of_birth');
    const representativeTab = document.getElementById('representative');
    const representativeFormContainer = document.getElementById('representativeFormContainer');
    const minorAgeBadge = document.getElementById('minorAgeBadge');
    const representativeInfoMessage = document.getElementById('representativeInfoMessage');
    const isMinorAgeField = document.getElementById('is_minor_age');
    
    if (dateOfBirthInput && representativeTab && representativeFormContainer && minorAgeBadge && representativeInfoMessage && isMinorAgeField) {
        const birthDate = new Date(dateOfBirthInput.value);
        const today = new Date();
        
        // Calcular edad
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        // Verificar si es menor de edad (menor de 18 años)
        const isMinor = age < 18;
        
        // Actualizar el campo oculto
        isMinorAgeField.value = isMinor ? '1' : '0';
        
        // Mostrar/ocultar el formulario de representante legal
        if (isMinor) {
            representativeFormContainer.style.display = 'block';
            minorAgeBadge.style.display = 'inline-block';
            representativeInfoMessage.className = 'alert alert-warning';
            representativeInfoMessage.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i><strong>¡Atención!</strong> Eres menor de edad. Debes completar la información del representante legal.';
            
            // Hacer obligatorios los campos del representante legal
            const requiredFields = representativeFormContainer.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                field.setAttribute('data-original-required', 'true');
            });
        } else {
            representativeFormContainer.style.display = 'none';
            minorAgeBadge.style.display = 'none';
            representativeInfoMessage.className = 'alert alert-success';
            representativeInfoMessage.innerHTML = '<i class="fas fa-check-circle me-2"></i><strong>Perfecto!</strong> Eres mayor de edad. No necesitas completar la información del representante legal.';
            
            // Hacer opcionales los campos del representante legal
            const requiredFields = representativeFormContainer.querySelectorAll('[data-original-required="true"]');
            requiredFields.forEach(field => {
                field.removeAttribute('required');
            });
        }
        
        // Recalcular progreso del perfil
        calculateProfileProgress();
    }
}

// Calcular progreso inicial
document.addEventListener('DOMContentLoaded', function() {
    calculateProfileProgress();
    
    // Recalcular progreso cuando cambien los campos
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('change', calculateProfileProgress);
        field.addEventListener('input', calculateProfileProgress);
    });
    
    // NOTA: La funcionalidad de filtrar municipios ha sido removida ya que los campos de departamento y municipio no existen en la tabla people
    
    // Agregar evento para verificar edad cuando cambie la fecha de nacimiento
    const dateOfBirthInput = document.getElementById('date_of_birth');
    if (dateOfBirthInput) {
        dateOfBirthInput.addEventListener('change', checkMinorAge);
        // Verificar edad inicial
        checkMinorAge();
    }
});

// Función para mostrar alertas con SweetAlert
function showSweetAlert(icon, title, text, timer = 3000) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        timer: timer,
        timerProgressBar: true,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });
}

// Función para mostrar confirmaciones con SweetAlert
function showConfirmAlert(title, text, confirmButtonText = 'Confirmar') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Cancelar'
    });
}

// Función para manejar el envío de formularios individuales
function handleIndividualFormSubmit(formId, successMessage, errorMessage = 'Error al guardar la información') {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Cambiar estado del botón
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Guardando...';
        
        // Recolectar datos del formulario
        const formData = new FormData(form);
        
        // Enviar datos al servidor
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito
                showSweetAlert('success', '¡Éxito!', successMessage);
                
                // Actualizar progreso del perfil
                setTimeout(() => {
                    calculateProfileProgress();
                }, 1000);
            } else {
                // Mostrar errores de validación
                if (data.errors) {
                    displayValidationErrors(data.errors);
                    showSweetAlert('error', 'Error de Validación', 'Por favor, corrige los errores indicados');
                } else {
                    showSweetAlert('error', 'Error', data.message || errorMessage);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showSweetAlert('error', 'Error de Conexión', 'No se pudo conectar con el servidor. Intenta nuevamente.');
        })
        .finally(() => {
            // Restaurar estado del botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
}

// Función para mostrar errores de validación
function displayValidationErrors(errors) {
    // Limpiar errores anteriores
    document.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(feedback => {
        feedback.remove();
    });
    
    // Mostrar nuevos errores
    Object.keys(errors).forEach(field => {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('is-invalid');
            
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errors[field][0];
            
            input.parentNode.appendChild(feedback);
        }
    });
}

// Inicializar manejo de formularios individuales cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    // Formulario de información personal
    handleIndividualFormSubmit(
        'personalInfoForm', 
        'Información personal actualizada correctamente'
    );
    
    // Formulario de información de formación
    handleIndividualFormSubmit(
        'formationInfoForm', 
        'Información de formación actualizada correctamente'
    );
    
    // Formulario de representante legal
    handleIndividualFormSubmit(
        'representativeInfoForm', 
        'Información del representante legal actualizada correctamente'
    );
    
    // Formulario de información de vivienda
    handleIndividualFormSubmit(
        'housingInfoForm', 
        'Información de vivienda actualizada correctamente'
    );
    
    // Formulario de información médica
    handleIndividualFormSubmit(
        'medicalInfoForm', 
        'Información médica actualizada correctamente'
    );
    
    // Formulario de información socioeconómica
    handleIndividualFormSubmit(
        'socioeconomicInfoForm', 
        'Información socioeconómica actualizada correctamente'
    );
    
    // Formulario de condiciones del aprendiz
    handleIndividualFormSubmit(
        'conditionsInfoForm', 
        'Información de condiciones del aprendiz actualizada correctamente'
    );
    
    // Formulario de declaración jurada
    handleIndividualFormSubmit(
        'declarationInfoForm', 
        'Declaración jurada actualizada correctamente'
    );
    
    // Formulario de cambio de contraseña
    const changePasswordForm = document.getElementById('changePasswordForm');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Cambiar estado del botón
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Cambiando...';
            
            // Recolectar datos del formulario
            const formData = new FormData(this);
            
            // Enviar datos al servidor
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    showSweetAlert('success', '¡Éxito!', 'Contraseña actualizada correctamente');
                    
                    // Limpiar formulario
                    this.reset();
                } else {
                    // Mostrar errores de validación
                    if (data.errors) {
                        displayValidationErrors(data.errors);
                        showSweetAlert('error', 'Error de Validación', 'Por favor, corrige los errores indicados');
                    } else {
                        showSweetAlert('error', 'Error', data.message || 'Error al cambiar la contraseña');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showSweetAlert('error', 'Error de Conexión', 'No se pudo conectar con el servidor. Intenta nuevamente.');
            })
            .finally(() => {
                // Restaurar estado del botón
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

</script>
@endsection
