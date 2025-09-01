@extends('sga::layouts.master')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Mi Beneficio - SGA</h2>
        </div>
    </div>

    <!-- Estado del beneficio -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Estado del Beneficio</h5>
                </div>
                <div class="card-body">
                    @if($application && $benefitData)
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Estado:</strong></h6>
                                @if($benefitStatus === 'Activo')
                                    <span class="badge bg-success fs-6">ACTIVO</span>
                                @else
                                    <span class="badge bg-danger fs-6">INACTIVO</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Vigencia:</strong></h6>
                                @if($benefitData['registration_start'] && $benefitData['registration_deadline'])
                                    <p class="mb-0">
                                        {{ \Carbon\Carbon::parse($benefitData['registration_start'])->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($benefitData['registration_deadline'])->format('d/m/Y') }}
                                    </p>
                                @else
                                    <p class="mb-0 text-muted">No definida</p>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Tipo de Beneficio:</strong></h6>
                                <p class="mb-0">{{ $benefitData['convocatory_name'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Trimestre:</strong></h6>
                                <p class="mb-0">Q{{ $benefitData['quarter'] }} - {{ $benefitData['year'] }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Puntaje Obtenido:</strong></h6>
                                <p class="mb-0"><span class="badge bg-primary fs-6">{{ $benefitData['total_points'] }} puntos</span></p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Fecha de Aplicación:</strong></h6>
                                <p class="mb-0">{{ \Carbon\Carbon::parse($benefitData['application_date'])->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><strong>Posición en el Cupo:</strong></h6>
                                <p class="mb-0"><span class="badge bg-info fs-6">#{{ $benefitData['position_by_points'] }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Estado del Cupo:</strong></h6>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $benefitData['cup_status'] }} fs-6">
                                        {{ $benefitData['cup_level'] }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    @elseif($convocatory)
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-muted mb-3" style="font-size: 3rem;"></i>
                            <h6 class="text-muted">No has aplicado a la convocatoria activa</h6>
                            <p class="text-muted small">La convocatoria "{{ $convocatory->name }}" está disponible para aplicar.</p>
                            @php
                                $now = \Carbon\Carbon::now();
                                $startDate = \Carbon\Carbon::parse($convocatory->registration_start_date);
                                $deadline = \Carbon\Carbon::parse($convocatory->registration_deadline);
                                $inPeriod = $now->between($startDate, $deadline);
                                $canApply = $convocatory->status === 'Active' && $inPeriod;
                            @endphp
                            @if($canApply)
                                <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i> Aplicar Ahora
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled>
                                    <i class="fas fa-clock"></i> No Disponible
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-muted mb-3" style="font-size: 3rem;"></i>
                            <h6 class="text-muted">No hay convocatorias activas</h6>
                            <p class="text-muted small">No hay convocatorias de alimentación disponibles en este momento.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Estadísticas</h5>
                </div>
                <div class="card-body">
                    @if($application && $benefitData)
                        <div class="text-center mb-3">
                            <h3 class="text-primary">{{ $benefitData['total_points'] }}</h3>
                            <small class="text-muted">Puntos totales</small>
                        </div>
                        <div class="text-center mb-3">
                            <h3 class="text-success">{{ $convocatory->coups ?? 'N/A' }}</h3>
                            <small class="text-muted">Cupos disponibles</small>
                        </div>
                        <div class="text-center mb-3">
                            <h3 class="text-info">{{ $benefitData['applications_count'] ?? 'N/A' }}</h3>
                            <small class="text-muted">Total de aplicaciones</small>
                        </div>
                        <div class="text-center">
                            @if($benefitData['registration_deadline'])
                                @php
                                    $deadline = \Carbon\Carbon::parse($benefitData['registration_deadline']);
                                    $daysLeft = $deadline->diffInDays(now(), false);
                                @endphp
                                <h3 class="text-warning">{{ max(0, $daysLeft) }}</h3>
                                <small class="text-muted">Días restantes</small>
                            @else
                                <h3 class="text-muted">N/A</h3>
                                <small class="text-muted">Sin fecha límite</small>
                            @endif
                        </div>
                    @elseif($convocatory)
                        <div class="text-center mb-3">
                            <h3 class="text-info">{{ $convocatory->name }}</h3>
                            <small class="text-muted">Convocatoria activa</small>
                        </div>
                        <div class="text-center mb-3">
                            <h3 class="text-success">{{ $convocatory->coups ?? 'N/A' }}</h3>
                            <small class="text-muted">Cupos disponibles</small>
                        </div>
                        <div class="text-center mb-3">
                            @php
                                $now = \Carbon\Carbon::now();
                                $startDate = \Carbon\Carbon::parse($convocatory->registration_start_date);
                                $deadline = \Carbon\Carbon::parse($convocatory->registration_deadline);
                                $inPeriod = $now->between($startDate, $deadline);
                            @endphp
                            @if($inPeriod)
                                <h3 class="text-success">Abierto</h3>
                                <small class="text-muted">Período activo</small>
                            @elseif($now->lt($startDate))
                                <h3 class="text-warning">Próximo</h3>
                                <small class="text-muted">Abre pronto</small>
                            @else
                                <h3 class="text-danger">Cerrado</h3>
                                <small class="text-muted">Período finalizado</small>
                            @endif
                        </div>
                        <div class="text-center">
                            @if($convocatory->registration_deadline)
                                @php
                                    $deadline = \Carbon\Carbon::parse($convocatory->registration_deadline);
                                    $daysLeft = $deadline->diffInDays(now(), false);
                                @endphp
                                <h3 class="text-warning">{{ max(0, $daysLeft) }}</h3>
                                <small class="text-muted">Días restantes</small>
                            @else
                                <h3 class="text-muted">N/A</h3>
                                <small class="text-muted">Sin fecha límite</small>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-line text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted small mb-0">Sin convocatorias disponibles</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detalles del beneficio -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Detalles del Beneficio</h5>
                </div>
                <div class="card-body">
                    @if($application && $benefitData)
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Convocatoria:</strong></td>
                                <td>{{ $benefitData['convocatory_name'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Trimestre:</strong></td>
                                <td>Q{{ $benefitData['quarter'] }} - {{ $benefitData['year'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Estado:</strong></td>
                                <td>
                                    @if($benefitStatus === 'Activo')
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Puntaje Total:</strong></td>
                                <td><span class="badge bg-primary">{{ $benefitData['total_points'] }} puntos</span></td>
                            </tr>
                            <tr>
                                <td><strong>Fecha de Aplicación:</strong></td>
                                <td>{{ \Carbon\Carbon::parse($benefitData['application_date'])->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Cupos Disponibles:</strong></td>
                                <td>{{ $convocatory->coups ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Posición en el Cupo:</strong></td>
                                <td><span class="badge bg-info">#{{ $benefitData['position_by_points'] ?? 'N/A' }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Estado del Cupo:</strong></td>
                                <td>
                                    <span class="badge bg-{{ $benefitData['cup_status'] ?? 'secondary' }}">
                                        {{ $benefitData['cup_level'] ?? 'N/A' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Total de Aplicaciones:</strong></td>
                                <td>{{ $benefitData['applications_count'] ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted small mb-0">No hay información disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-calendar-alt"></i> Información de la Convocatoria</h5>
                </div>
                <div class="card-body">
                    @if($application && $benefitData)
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Fecha de Inicio</h6>
                                    <small class="text-muted">
                                        @if($benefitData['registration_start'])
                                            {{ \Carbon\Carbon::parse($benefitData['registration_start'])->format('d/m/Y H:i') }}
                                        @else
                                            No definida
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-info">Inicio</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Fecha de Cierre</h6>
                                    <small class="text-muted">
                                        @if($benefitData['registration_deadline'])
                                            {{ \Carbon\Carbon::parse($benefitData['registration_deadline'])->format('d/m/Y H:i') }}
                                        @else
                                            No definida
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-warning">Cierre</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Estado Actual</h6>
                                    <small class="text-muted">
                                        @if($convocatory->status === 'Active')
                                            Convocatoria Abierta
                                        @else
                                            Convocatoria Cerrada
                                        @endif
                                    </small>
                                </div>
                                @if($convocatory->status === 'Active')
                                    <span class="badge bg-success">Abierta</span>
                                @else
                                    <span class="badge bg-danger">Cerrada</span>
                                @endif
                            </div>
                        </div>
                    @elseif($convocatory)
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Convocatoria</h6>
                                    <small class="text-muted">{{ $convocatory->name }}</small>
                                </div>
                                <span class="badge bg-info">{{ $convocatory->status }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Fecha de Inicio</h6>
                                    <small class="text-muted">
                                        @if($convocatory->registration_start_date)
                                            {{ \Carbon\Carbon::parse($convocatory->registration_start_date)->format('d/m/Y H:i') }}
                                        @else
                                            No definida
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-info">Inicio</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Fecha de Cierre</h6>
                                    <small class="text-muted">
                                        @if($convocatory->registration_deadline)
                                            {{ \Carbon\Carbon::parse($convocatory->registration_deadline)->format('d/m/Y H:i') }}
                                        @else
                                            No definida
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-warning">Cierre</span>
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
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted small mb-0">Sin convocatoria activa</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Información Importante</h5>
                </div>
                <div class="card-body">
                    @if($application && $benefitData)
                        <div class="alert alert-success">
                            <h6><strong>¡Felicidades! Tu aplicación ha sido procesada exitosamente.</strong></h6>
                            <ul class="mb-0">
                                <li>Has obtenido <strong>{{ $benefitData['total_points'] }} puntos</strong> en tu aplicación</li>
                                <li>Tu beneficio está <strong>{{ $benefitStatus === 'Activo' ? 'activo' : 'inactivo' }}</strong></li>
                                <li>Convocatoria: <strong>{{ $benefitData['convocatory_name'] }}</strong></li>
                                <li>Fecha de aplicación: <strong>{{ \Carbon\Carbon::parse($benefitData['application_date'])->format('d/m/Y H:i') }}</strong></li>
                                <li>Posición en el cupo: <strong>#{{ $benefitData['position_by_points'] }}</strong> de {{ $benefitData['applications_count'] }} aplicaciones</li>
                                <li>Estado del cupo: <strong>{{ $benefitData['cup_level'] }}</strong></li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><strong>Información Importante:</strong></h6>
                            <ul class="mb-0">
                                <li>Mantén tu información de perfil actualizada para futuras convocatorias</li>
                                <li>Los puntos se calculan automáticamente según la información registrada</li>
                                <li>Puedes ver tu historial de beneficios en la sección correspondiente</li>
                                <li>Para cualquier duda, contacta a la administración del SGA</li>
                            </ul>
                        </div>
                    @elseif($convocatory)
                        @php
                            $now = \Carbon\Carbon::now();
                            $startDate = \Carbon\Carbon::parse($convocatory->registration_start_date);
                            $deadline = \Carbon\Carbon::parse($convocatory->registration_deadline);
                            $inPeriod = $now->between($startDate, $deadline);
                            $canApply = $convocatory->status === 'Active' && $inPeriod;
                        @endphp
                        
                        @if($canApply)
                            <div class="alert alert-info">
                                <h6><strong>¡Convocatoria Disponible!</strong></h6>
                                <ul class="mb-0">
                                    <li>La convocatoria <strong>"{{ $convocatory->name }}"</strong> está abierta para aplicaciones</li>
                                    <li>Período de registro: <strong>{{ $startDate->format('d/m/Y H:i') }}</strong> hasta <strong>{{ $deadline->format('d/m/Y H:i') }}</strong></li>
                                    <li>Cupos disponibles: <strong>{{ $convocatory->coups }}</strong></li>
                                    <li>Puedes aplicar ahora haciendo clic en el botón "Aplicar Ahora"</li>
                                </ul>
                            </div>
                        @elseif($now->lt($startDate))
                            <div class="alert alert-warning">
                                <h6><strong>Convocatoria Próximamente</strong></h6>
                                <ul class="mb-0">
                                    <li>La convocatoria <strong>"{{ $convocatory->name }}"</strong> abrirá el <strong>{{ $startDate->format('d/m/Y H:i') }}</strong></li>
                                    <li>Cupos disponibles: <strong>{{ $convocatory->coups }}</strong></li>
                                    <li>Prepárate para aplicar cuando se abra el período de registro</li>
                                    <li>Mantén tu información de perfil actualizada</li>
                                </ul>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h6><strong>Período de Registro Cerrado</strong></h6>
                                <ul class="mb-0">
                                    <li>El período de registro para <strong>"{{ $convocatory->name }}"</strong> finalizó el <strong>{{ $deadline->format('d/m/Y H:i') }}</strong></li>
                                    <li>No puedes aplicar a esta convocatoria</li>
                                    <li>Espera a que se abra una nueva convocatoria</li>
                                    <li>Mantén tu información de perfil actualizada para futuras oportunidades</li>
                                </ul>
                            </div>
                        @endif
                        
                        <div class="alert alert-info">
                            <h6><strong>Información Importante:</strong></h6>
                            <ul class="mb-0">
                                <li>Los puntos se calculan automáticamente según la información registrada en tu perfil</li>
                                <li>Mantén tu información de perfil actualizada para obtener mejores puntajes</li>
                                <li>Puedes ver tu historial de beneficios en la sección correspondiente</li>
                                <li>Para cualquier duda, contacta a la administración del SGA</li>
                            </ul>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <h6><strong>No hay convocatorias disponibles</strong></h6>
                            <p class="mb-0">Actualmente no hay convocatorias de alimentación activas. Mantén tu información de perfil actualizada para estar listo cuando se abra una nueva convocatoria.</p>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><strong>Información Importante:</strong></h6>
                            <ul class="mb-0">
                                <li>Mantén tu información de perfil actualizada para futuras convocatorias</li>
                                <li>Los puntos se calculan automáticamente según la información registrada</li>
                                <li>Puedes ver tu historial de beneficios en la sección correspondiente</li>
                                <li>Para cualquier duda, contacta a la administración del SGA</li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="row mt-4">
        <div class="col-12 text-center">
            <a href="{{ route('cefa.sga.apprentice.ben-history') }}" class="btn btn-outline-info me-2">
                <i class="fas fa-history"></i> Ver Historial
            </a>
            <a href="{{ route('cefa.sga.apprentice.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Dashboard
            </a>
        </div>
    </div>
</div>
@endsection