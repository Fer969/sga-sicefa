@extends('sga::layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Dashboard del Aprendiz - SGA</h2>
        </div>
    </div>

    <!-- Alertas y Notificaciones -->
    @if($benefitData)
        <div class="row mb-4">
            <div class="col-12">
                @if($benefitStatus === 'Activo')
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            <strong>¡Beneficio Activo!</strong> Tu aplicación a la convocatoria "{{ $benefitData['convocatory_name'] }}" está vigente. 
                            Has obtenido {{ $benefitData['total_points'] }} puntos y estás en la posición #{{ $benefitData['position_by_points'] }} del cupo.
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Beneficio Inactivo:</strong> Tu aplicación existe pero la convocatoria no está activa actualmente.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        <strong>Bienvenido al SGA!</strong> Para acceder a beneficios de apoyo alimentario, debes aplicar a una convocatoria primero.
                        <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="alert-link ms-2">Haz clic aquí para aplicar</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tarjetas de resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Estado del Beneficio</h5>
                    @if($benefitStatus === 'Activo')
                        <h3 class="card-text">ACTIVO</h3>
                        @if($benefitData && $benefitData['registration_deadline'])
                            <small>Vigente hasta: {{ \Carbon\Carbon::parse($benefitData['registration_deadline'])->format('d/m/Y') }}</small>
                        @else
                            <small>Beneficio activo</small>
                        @endif
                    @elseif($benefitStatus === 'Inactivo')
                        <h3 class="card-text">INACTIVO</h3>
                        <small>Beneficio suspendido</small>
                    @else
                        <h3 class="card-text">NO APLICADO</h3>
                        <small>Sin beneficio activo</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Puntaje Obtenido</h5>
                    @if($benefitData)
                        <h3 class="card-text">{{ $benefitData['total_points'] }}</h3>
                        <small>Puntos totales</small>
                    @else
                        <h3 class="card-text">0</h3>
                        <small>Sin puntaje</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Posición en Cupo</h5>
                    @if($benefitData)
                        <h3 class="card-text">#{{ $benefitData['position_by_points'] }}</h3>
                        <small>{{ $benefitData['cup_level'] }}</small>
                    @else
                        <h3 class="card-text">N/A</h3>
                        <small>Sin aplicación</small>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Convocatorias</h5>
                    <h3 class="card-text">{{ $dashboardStats['available_calls'] }}</h3>
                    <small>Disponibles</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Información personal -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Información Personal</h5>
                </div>
                <div class="card-body">
                    @if($person)
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Nombre:</strong></td>
                                <td>{{ $person->first_name ?? '' }} {{ $person->first_last_name ?? '' }} {{ $person->second_last_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Documento:</strong></td>
                                <td>{{ $person->document_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Programa:</strong></td>
                                <td>
                                    @if($person->apprentices->first() && $person->apprentices->first()->course && $person->apprentices->first()->course->program)
                                        {{ $person->apprentices->first()->course->program->name ?? 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Ficha:</strong></td>
                                <td>
                                    @if($person->apprentices->first() && $person->apprentices->first()->course && $person->apprentices->first()->course->code)
                                        {{ $person->apprentices->first()->course->code }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            @if($person->apprentices->first() && $person->apprentices->first()->course)
                                <tr>
                                    <td><strong>Estado del Curso:</strong></td>
                                    <td>
                                        @if($person->apprentices->first()->course->status)
                                            <span class="badge bg-{{ $person->apprentices->first()->course->status === 'Active' ? 'success' : 'secondary' }}">
                                                {{ $person->apprentices->first()->course->status }}
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            @if($benefitData)
                                <tr>
                                    <td><strong>Convocatoria:</strong></td>
                                    <td>{{ $benefitData['convocatory_name'] }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trimestre:</strong></td>
                                    <td>Q{{ $benefitData['quarter'] }} - {{ $benefitData['year'] }}</td>
                                </tr>
                            @endif
                        </table>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-user-slash text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No se encontró información personal</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Información del Beneficio</h5>
                </div>
                <div class="card-body">
                    @if($benefitData)
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Estado del Beneficio</h6>
                                    <small class="text-muted">
                                        @if($benefitStatus === 'Activo')
                                            Beneficio activo y vigente
                                        @else
                                            Beneficio inactivo
                                        @endif
                                    </small>
                                </div>
                                @if($benefitStatus === 'Activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Fecha de Aplicación</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($benefitData['application_date'])->format('d/m/Y H:i') }}</small>
                                </div>
                                <span class="badge bg-info">Aplicado</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Posición en Cupo</h6>
                                    <small class="text-muted">#{{ $benefitData['position_by_points'] }} de {{ $benefitData['applications_count'] }} aplicaciones</small>
                                </div>
                                <span class="badge bg-{{ $benefitData['cup_status'] }}">{{ $benefitData['cup_level'] }}</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No tienes beneficios activos</p>
                            <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-edit"></i> Aplicar a Convocatoria
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('cefa.sga.apprentice.my-benefit') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-gift"></i> Mi Beneficio
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('cefa.sga.apprentice.ben-history') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-history"></i> Historial
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-edit"></i> Solicitar Convocatoria
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('cefa.sga.apprentice.profile') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-user"></i> Mi Perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection