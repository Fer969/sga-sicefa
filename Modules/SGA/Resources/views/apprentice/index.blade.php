@extends('sga::layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Dashboard del Aprendiz - SGA</h2>
        </div>
    </div>

    <!-- Alertas y Notificaciones -->
    @if($convocatory)
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
                @elseif($benefitStatus === 'Inactivo')
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <div>
                            <strong>Beneficio Inactivo:</strong> Tu aplicación existe pero la convocatoria "{{ $benefitData['convocatory_name'] }}" no está activa actualmente.
                        </div>
                    </div>
                @else
                    @php
                        $now = \Carbon\Carbon::now();
                        $startDate = \Carbon\Carbon::parse($convocatory->registration_start_date);
                        $deadline = \Carbon\Carbon::parse($convocatory->registration_deadline);
                        $inPeriod = $now->between($startDate, $deadline);
                        $canApply = $convocatory->status === 'Active' && $inPeriod;
                    @endphp
                    
                    @if($canApply)
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>
                                <strong>¡Convocatoria Abierta!</strong> La convocatoria "{{ $convocatory->name }}" está abierta para aplicaciones hasta el {{ $deadline->format('d/m/Y H:i') }}.
                                <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="alert-link ms-2">Haz clic aquí para aplicar</a>
                            </div>
                        </div>
                    @elseif($now->lt($startDate))
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-clock me-2"></i>
                            <div>
                                <strong>Convocatoria Próximamente:</strong> La convocatoria "{{ $convocatory->name }}" abrirá el {{ $startDate->format('d/m/Y H:i') }}.
                            </div>
                        </div>
                    @elseif($now->gt($deadline))
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="fas fa-times-circle me-2"></i>
                            <div>
                                <strong>Período Cerrado:</strong> El período de registro para la convocatoria "{{ $convocatory->name }}" finalizó el {{ $deadline->format('d/m/Y H:i') }}.
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary d-flex align-items-center" role="alert">
                            <i class="fas fa-pause-circle me-2"></i>
                            <div>
                                <strong>Convocatoria No Disponible:</strong> La convocatoria "{{ $convocatory->name }}" no está disponible para aplicaciones en este momento.
                            </div>
                        </div>
                    @endif
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
                    <h5 class="card-title">Convocatoria Activa</h5>
                    @if($convocatory)
                        <h3 class="card-text">{{ $convocatory->name }}</h3>
                        @php
                            $now = \Carbon\Carbon::now();
                            $startDate = \Carbon\Carbon::parse($convocatory->registration_start_date);
                            $deadline = \Carbon\Carbon::parse($convocatory->registration_deadline);
                            $inPeriod = $now->between($startDate, $deadline);
                        @endphp
                        @if($inPeriod)
                            <small class="text-success">Período Abierto</small>
                        @elseif($now->lt($startDate))
                            <small class="text-warning">Próximamente</small>
                        @else
                            <small class="text-danger">Cerrado</small>
                        @endif
                    @else
                        <h3 class="card-text">No disponible</h3>
                        <small>Sin convocatorias</small>
                    @endif
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
                            @if($convocatory)
                                <tr>
                                    <td><strong>Convocatoria Activa:</strong></td>
                                    <td>{{ $convocatory->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trimestre:</strong></td>
                                    <td>Q{{ $convocatory->quarter }} - {{ $convocatory->year }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Estado:</strong></td>
                                    <td>
                                        @if($convocatory->status === 'Active')
                                            <span class="badge bg-success">Activa</span>
                                        @else
                                            <span class="badge bg-secondary">Inactiva</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Período de Registro:</strong></td>
                                    <td>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $startDate = \Carbon\Carbon::parse($convocatory->registration_start_date);
                                            $deadline = \Carbon\Carbon::parse($convocatory->registration_deadline);
                                            $inPeriod = $now->between($startDate, $deadline);
                                        @endphp
                                        <div class="small">
                                            <div>Inicio: {{ $startDate->format('d/m/Y H:i') }}</div>
                                            <div>Cierre: {{ $deadline->format('d/m/Y H:i') }}</div>
                                            @if($inPeriod)
                                                <span class="badge bg-success mt-1">Abierto</span>
                                            @elseif($now->lt($startDate))
                                                <span class="badge bg-warning mt-1">Próximamente</span>
                                            @else
                                                <span class="badge bg-danger mt-1">Cerrado</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @if($benefitData)
                                    <tr>
                                        <td><strong>Mi Convocatoria:</strong></td>
                                        <td>{{ $benefitData['convocatory_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Mi Trimestre:</strong></td>
                                        <td>Q{{ $benefitData['quarter'] }} - {{ $benefitData['year'] }}</td>
                                    </tr>
                                @endif
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
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Cupos Disponibles</h6>
                                    <small class="text-muted">{{ $benefitData['coups'] }} cupos totales</small>
                                </div>
                                <span class="badge bg-primary">{{ $benefitData['coups'] }}</span>
                            </div>
                        </div>
                    @elseif($convocatory)
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Estado de Aplicación</h6>
                                    <small class="text-muted">No has aplicado a esta convocatoria</small>
                                </div>
                                <span class="badge bg-warning">Pendiente</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Convocatoria</h6>
                                    <small class="text-muted">{{ $convocatory->name }}</small>
                                </div>
                                <span class="badge bg-info">{{ $convocatory->status }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Cupos Disponibles</h6>
                                    <small class="text-muted">{{ $convocatory->coups }} cupos totales</small>
                                </div>
                                <span class="badge bg-primary">{{ $convocatory->coups }}</span>
                            </div>
                            @php
                                $now = \Carbon\Carbon::now();
                                $startDate = \Carbon\Carbon::parse($convocatory->registration_start_date);
                                $deadline = \Carbon\Carbon::parse($convocatory->registration_deadline);
                                $inPeriod = $now->between($startDate, $deadline);
                                $canApply = $convocatory->status === 'Active' && $inPeriod;
                            @endphp
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Período de Registro</h6>
                                    <small class="text-muted">
                                        @if($inPeriod)
                                            Abierto hasta {{ $deadline->format('d/m/Y H:i') }}
                                        @elseif($now->lt($startDate))
                                            Abre el {{ $startDate->format('d/m/Y H:i') }}
                                        @else
                                            Cerrado desde {{ $deadline->format('d/m/Y H:i') }}
                                        @endif
                                    </small>
                                </div>
                                @if($canApply)
                                    <span class="badge bg-success">Disponible</span>
                                @elseif($now->lt($startDate))
                                    <span class="badge bg-warning">Próximamente</span>
                                @else
                                    <span class="badge bg-danger">Cerrado</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            @if($canApply)
                                <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-success">
                                    <i class="fas fa-edit me-2"></i>Aplicar Ahora
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-clock me-2"></i>No Disponible
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No hay convocatorias disponibles</p>
                            <small class="text-muted">No hay convocatorias de alimentación activas en este momento.</small>
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