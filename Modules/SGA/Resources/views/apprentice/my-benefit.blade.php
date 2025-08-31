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
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-muted mb-3" style="font-size: 3rem;"></i>
                            <h6 class="text-muted">No has aplicado a ninguna convocatoria</h6>
                            <p class="text-muted small">Para obtener beneficios, debes aplicar a una convocatoria primero.</p>
                            <a href="{{ route('cefa.sga.apprentice.apply-to-call') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Aplicar a Convocatoria
                            </a>
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
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-chart-line text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted small mb-0">Sin datos disponibles</p>
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
                    @else
                        <div class="alert alert-warning">
                            <h6><strong>No tienes beneficios activos</strong></h6>
                            <p class="mb-0">Para obtener beneficios de apoyo alimentario, debes aplicar a una convocatoria primero. Haz clic en el botón "Aplicar a Convocatoria" para comenzar.</p>
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