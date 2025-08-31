@extends('sga::layouts.master')

@section('content')
<style>
    .profile-container {
        background: #f8f9fa;
        min-height: 100vh;
        padding: 20px 0;
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .profile-header {
        background: #495057;
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 25px;
        text-align: center;
    }

    .profile-tabs {
        background: #6c757d;
        border-radius: 0;
        border: none;
    }

    .nav-tabs .nav-link {
        background: transparent;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        padding: 15px 25px;
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link.active {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        border-radius: 8px 8px 0 0;
    }

    .nav-tabs .nav-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .tab-content {
        background: white;
        padding: 30px;
        border-radius: 0 0 12px 12px;
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
</style>

<div class="profile-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="profile-card">
                    <!-- Header -->
                    <div class="profile-header">
                        <h2 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>
                            Mi Perfil
                        </h2>
                        <p class="mb-0 mt-2 opacity-75">Gestiona tu información personal y configuración de cuenta</p>
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
                                <i class="fas fa-id-card me-2"></i>Información Personal
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                <i class="fas fa-key me-2"></i>Seguridad
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

                        <!-- Información Personal -->
                        <div class="tab-pane fade" id="personal" role="tabpanel">
                            <h4 class="section-title">
                                <i class="fas fa-id-card me-2"></i>Información Personal
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

                            <form action="{{ route('cefa.sga.admin.profile.update-personal-info') }}" method="POST" id="personalInfoForm">
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
                                        <input type="number" 
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
                                        <input type="number" 
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
                                        </select>
                                        @error('personal_info.gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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

                                <!-- Información Socioeconómica -->
                                <h5 class="section-title">
                                    <i class="fas fa-chart-line me-2"></i>Información Socioeconómica
                                </h5>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="socioeconomical_status" class="form-label">Estrato Socioeconómico</label>
                                        <select class="form-select @error('personal_info.socioeconomical_status') is-invalid @enderror" 
                                                id="socioeconomical_status" 
                                                name="personal_info[socioeconomical_status]">
                                            <option value="No registra" {{ old('personal_info.socioeconomical_status', auth()->user()->person->socioeconomical_status ?? 'No registra') == 'No registra' ? 'selected' : '' }}>No registra</option>
                                            <option value="1" {{ old('personal_info.socioeconomical_status', auth()->user()->person->socioeconomical_status ?? '') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('personal_info.socioeconomical_status', auth()->user()->person->socioeconomical_status ?? '') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('personal_info.socioeconomical_status', auth()->user()->person->socioeconomical_status ?? '') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('personal_info.socioeconomical_status', auth()->user()->person->socioeconomical_status ?? '') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('personal_info.socioeconomical_status', auth()->user()->person->socioeconomical_status ?? '') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('personal_info.socioeconomical_status', auth()->user()->person->socioeconomical_status ?? '') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                        @error('personal_info.socioeconomical_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="sisben_level" class="form-label">Nivel Sisbén</label>
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

                                <!-- Información de Contacto -->
                                <h5 class="section-title">
                                    <i class="fas fa-address-book me-2"></i>Información de Contacto
                                </h5>
                                <div class="row mb-4">
                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">Dirección</label>
                                        <input type="text" 
                                               class="form-control @error('personal_info.address') is-invalid @enderror" 
                                               id="address" 
                                               name="personal_info[address]" 
                                               value="{{ old('personal_info.address', auth()->user()->person->address ?? '') }}">
                                        @error('personal_info.address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="telephone1" class="form-label">Teléfono 1</label>
                                        <input type="number" 
                                               class="form-control @error('personal_info.telephone1') is-invalid @enderror" 
                                               id="telephone1" 
                                               name="personal_info[telephone1]" 
                                               value="{{ old('personal_info.telephone1', auth()->user()->person->telephone1 ?? '') }}">
                                        @error('personal_info.telephone1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="telephone2" class="form-label">Teléfono 2</label>
                                        <input type="number" 
                                               class="form-control @error('personal_info.telephone2') is-invalid @enderror" 
                                               id="telephone2" 
                                               name="personal_info[telephone2]" 
                                               value="{{ old('personal_info.telephone2', auth()->user()->person->telephone2 ?? '') }}">
                                        @error('personal_info.telephone2')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="telephone3" class="form-label">Teléfono 3</label>
                                        <input type="number" 
                                               class="form-control @error('personal_info.telephone3') is-invalid @enderror" 
                                               id="telephone3" 
                                               name="personal_info[telephone3]" 
                                               value="{{ old('personal_info.telephone3', auth()->user()->person->telephone3 ?? '') }}">
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
                                               value="{{ old('personal_info.personal_email', auth()->user()->person->personal_email ?? '') }}">
                                        @error('personal_info.personal_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="misena_email" class="form-label">Email Misena</label>
                                        <input type="email" 
                                               class="form-control @error('personal_info.misena_email') is-invalid @enderror" 
                                               id="misena_email" 
                                               name="personal_info[misena_email]" 
                                               value="{{ old('personal_info.misena_email', auth()->user()->person->misena_email ?? '') }}">
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
                                               value="{{ old('personal_info.sena_email', auth()->user()->person->sena_email ?? '') }}">
                                        @error('personal_info.sena_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <hr class="section-divider">

                                <!-- Información Institucional -->
                                <h5 class="section-title">
                                    <i class="fas fa-building me-2"></i>Información Institucional
                                </h5>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="eps_id" class="form-label">EPS</label>
                                        <select class="form-select @error('personal_info.eps_id') is-invalid @enderror" 
                                                id="eps_id" 
                                                name="personal_info[eps_id]" 
                                                required>
                                            <option value="">Seleccione una EPS...</option>
                                            @if(isset($eps))
                                                @foreach($eps as $ep)
                                                    <option value="{{ $ep->id }}" {{ old('personal_info.eps_id', auth()->user()->person->eps_id ?? '') == $ep->id ? 'selected' : '' }}>
                                                        {{ $ep->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('personal_info.eps_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="population_group_id" class="form-label">Grupo Poblacional</label>
                                        <select class="form-select @error('personal_info.population_group_id') is-invalid @enderror" 
                                                id="population_group_id" 
                                                name="personal_info[population_group_id]" 
                                                required>
                                            <option value="">Seleccione un grupo poblacional...</option>
                                            @if(isset($populationGroups))
                                                @foreach($populationGroups as $group)
                                                    <option value="{{ $group->id }}" {{ old('personal_info.population_group_id', auth()->user()->person->population_group_id ?? '') == $group->id ? 'selected' : '' }}>
                                                        {{ $group->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('personal_info.population_group_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="pension_entity_id" class="form-label">Entidad Pensional</label>
                                        <select class="form-select @error('personal_info.pension_entity_id') is-invalid @enderror" 
                                                id="pension_entity_id" 
                                                name="personal_info[pension_entity_id]" 
                                                required>
                                            <option value="">Seleccione una entidad pensional...</option>
                                            @if(isset($pensionEntities))
                                                @foreach($pensionEntities as $entity)
                                                    <option value="{{ $entity->id }}" {{ old('personal_info.pension_entity_id', auth()->user()->person->pension_entity_id ?? '') == $entity->id ? 'selected' : '' }}>
                                                        {{ $entity->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('personal_info.pension_entity_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="reset" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-undo me-1"></i> Restaurar
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> Guardar Información Personal
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

                                    <form action="{{ route('cefa.sga.admin.profile.change-password') }}" method="POST" id="changePasswordForm">
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
});
</script>
@endsection